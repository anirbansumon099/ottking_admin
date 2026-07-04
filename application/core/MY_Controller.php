<?php
// application/core/MY_Controller.php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base API Controller
 * সমস্ত API Controller এই ক্লাস থেকে extend করবে।
 * ডাইনামিক ক্রেডেনশিয়াল লোড ও সিকিউর রিকোয়েস্ট পার্সিং এখানে হ্যান্ডেল করা হয়।
 */
class Base_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Crypto লাইব্রেরি লোড
        $this->load->library('crypto_lib');

        // সব রেসপন্স JSON ফরম্যাটে সেট করা হলো
        header('Content-Type: application/json; charset=utf-8');
    }

    // ── রেসপন্স হেল্পার ──────────────────────────────────────────────────────

    protected function send_response(int $status, array $body): void
    {
        http_response_code($status);
        echo json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── Secure Payload Parser ─────────────────────────────────────────────────

    /**
     * Request হেডার ভেরিফাই, ডাইনামিক কী লোড, HMAC চেক ও AES-GCM ডিক্রিপ্ট করে ডেটা রিটার্ন করে।
     */
    protected function get_secure_request_payload(): array
    {
        $headers = array_change_key_case(getallheaders() ?: [], CASE_LOWER);

        // অ্যাপের পাঠানো x-api-key-কে আমরা ডেটাবেইসের app_key_id হিসেবে ব্যবহার করব
        $appKeyId  = $headers['x-api-key']   ?? $_SERVER['HTTP_X_API_KEY']   ?? '';
        $timestamp = $headers['x-timestamp'] ?? $_SERVER['HTTP_X_TIMESTAMP'] ?? '';
        $signature = $headers['x-signature'] ?? $_SERVER['HTTP_X_SIGNATURE'] ?? '';

        if (empty($appKeyId)) {
            throw new InvalidArgumentException("Header Match Error: Missing x-api-key (App Key ID)");
        }

        if ($timestamp === '' || $signature === '') {
            throw new InvalidArgumentException("Header Match Error: Missing x-timestamp [{$timestamp}] or x-signature [{$signature}]");
        }

        // ১. ডাইনামিক ক্রেডেনশিয়াল লোড (প্রধান পরিবর্তন)
        // ডেটাবেইস থেকে এই নির্দিষ্ট app_key_id এর জন্য encryption_key এবং hmac_secret সেট করা হচ্ছে
        if (!$this->crypto_lib->init_keys_by_id($appKeyId)) {
            throw new InvalidArgumentException("Security Error: Invalid or inactive App Key ID [{$appKeyId}]");
        }

        // ২. টাইমস্ট্যাম্প ভ্যালিডেশন (Replay Attack রোধের জন্য)
        $ts = @strtotime($timestamp);
        if ($ts === false || abs(time() - $ts) > 300) {
            throw new InvalidArgumentException("Security Error: Timestamp drift too high. Server time: " . time() . ", App time: " . $ts);
        }

        // ৩. ইনপুট বডি রিড করা
        $rawBody = file_get_contents('php://input') ?: '{}';
        $body    = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($body) || !isset($body['encrypted_payload']) || !is_string($body['encrypted_payload'])) {
            throw new InvalidArgumentException('Payload Error: encrypted_payload is missing or not a string');
        }

        // ৪. সিগনেচার ভেরিফিকেশন লজিক
        $endpoint      = $this->_get_endpoint();
        $signedPayload = $timestamp . '|' . $endpoint . '|' . $body['encrypted_payload'];
        $isValid       = $this->crypto_lib->verify_signature($signedPayload, $signature);

        // Fallback: basename দিয়ে চেক (যেমন auth/login → login)
        if (!$isValid && strpos($endpoint, '/') !== false) {
            $fallbackEndpoint = basename($endpoint);
            $fallbackPayload  = $timestamp . '|' . $fallbackEndpoint . '|' . $body['encrypted_payload'];
            $isValid          = $this->crypto_lib->verify_signature($fallbackPayload, $signature);
        }

        if (!$isValid) {
            throw new InvalidArgumentException("Crypto Signature Error: HMAC Verification failed.");
        }

        // ৫. ডেটা ডিক্রিপশন (ডাইনামিক কী দিয়ে সম্পন্ন হবে)
        $decrypted = $this->crypto_lib->decrypt_payload($body['encrypted_payload']);
        $decoded   = json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($decoded)) {
            throw new InvalidArgumentException('Payload Error: Decrypted structure is not valid JSON array');
        }

        return $decoded;
    }

    /**
     * বর্তমান endpoint স্ট্রিং বের করে (যেমন: auth/login, catalog, health)
     */
    private function _get_endpoint(): string
    {
        $uri       = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');

        if ($scriptDir !== '/' && $scriptDir !== '.' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        }

        if (strpos($uri, '/index.php') === 0) {
            $uri = substr($uri, strlen('/index.php'));
        }

        $uri = trim($uri, '/');
        return $uri === '' ? 'health' : $uri;
    }
}