<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ১. ইউজার ডাটা খোঁজার মেথড
    public function find_by_email(string $email): ?array
    {
        $email = strtolower(trim($email));
        if (empty($email)) return null;

        // users.plan_id এখন সরাসরি subscription_plans.id এর সাথে FK দিয়ে যুক্ত
        // (আগে plan নাম দিয়ে ম্যাচ করা হতো, এখন নাম পরিবর্তন হলেও ভাঙবে না)
        $query = $this->db->select('id, email, password_hash AS password, plan, plan_id, plan_expires_at, device_limit')
                          ->where('email', $email)
                          ->limit(1)
                          ->get('users');

        if ($query->num_rows() === 0) return null;

        $user = $query->row_array();

        // প্ল্যান এক্সপায়ারি চেক
        $plan   = (string)($user['plan'] ?? 'Free');
        $planId = isset($user['plan_id']) ? (int)$user['plan_id'] : null;

        if ($plan === 'Free' || empty($user['plan_expires_at'])) {
            // Free প্ল্যান বা মেয়াদ সেট করা নেই -> প্যাকেজ চ্যানেল প্রযোজ্য নয়
            $plan   = 'Free';
            $planId = null;
        } elseif (new DateTime() > new DateTime($user['plan_expires_at'])) {
            // মেয়াদ শেষ -> Free এ ফলব্যাক
            $plan   = 'Free';
            $planId = null;
        }

        return [
            'id'           => (string)$user['id'],
            'email'        => (string)$user['email'],
            'password'     => (string)$user['password'],
            'plan'         => $plan,
            'plan_id'      => $planId, // এক্সপায়ার না হলে subscription_plans.id, নাহলে null
            'device_limit' => (int)($user['device_limit'] ?? 1),
        ];
    }

    // ২. সেশন ও ডিভাইস ভ্যালিডেশন (ক্যাটালগের জন্য প্রয়োজনীয়)
    public function validate_device_session(string $email, string $sessionToken, string $deviceId): ?array
    {
        $user = $this->find_by_email($email);
        if (!$user) return null;

        $this->db->where('user_id', $user['id']);
        $this->db->where('session_token', $sessionToken);
        $this->db->where('device_id', $deviceId);
        $this->db->where('token_expiry >=', date('Y-m-d H:i:s'));
        
        $session = $this->db->get('user_sessions')->row_array();

        if ($session) {
            return [
                'plan'    => $user['plan'],
                'plan_id' => $user['plan_id'] ?? null, // মেয়াদ থাকলে subscription_plans.id, না হলে null
                'email'   => $user['email']
            ];
        }
        return null;
    }

    // ৩. অথেন্টিকেশন ও ডিভাইস লিমিট লজিক
    public function authenticate(string $email, string $password, string $deviceId): array
    {
        $user = $this->find_by_email($email);
        if (!$user || !password_verify(trim($password), $user['password'])) {
            throw new InvalidArgumentException('Auth: Invalid email or password.');
        }

        // লিমিট চেক
        $this->db->where('user_id', $user['id']);
        $this->db->where('token_expiry >=', date('Y-m-d H:i:s'));
        $activeSessions = $this->db->get('user_sessions')->result_array();

        $isAlreadyLoggedIn = false;
        foreach ($activeSessions as $s) {
            if ($s['device_id'] === $deviceId) {
                $isAlreadyLoggedIn = true;
                break;
            }
        }

        if (!$isAlreadyLoggedIn && count($activeSessions) >= $user['device_limit']) {
            throw new RuntimeException("Device Limit Error: Maximum login limit reached.");
        }

        // সেশন ইনসার্ট বা আপডেট
        $token = bin2hex(random_bytes(24));
        $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));

        $this->db->where(['user_id' => $user['id'], 'device_id' => $deviceId]);
        if ($this->db->get('user_sessions')->row()) {
            $this->db->where(['user_id' => $user['id'], 'device_id' => $deviceId])
                     ->update('user_sessions', ['session_token' => $token, 'token_expiry' => $expiry]);
        } else {
            $this->db->insert('user_sessions', [
                'user_id' => $user['id'], 'email' => $user['email'],
                'session_token' => $token, 'device_id' => $deviceId, 'token_expiry' => $expiry
            ]);
        }

        return ['token' => $token, 'email' => $user['email'], 'plan' => $user['plan']];
    }

    // ৪. রেজিস্ট্রেশন
    public function register(string $email, string $password, string $deviceId, string $ipAddress): array
    {
        if ($this->find_by_email($email)) {
            throw new InvalidArgumentException('Auth: Email already registered');
        }

        $this->db->insert('users', [
            'email' => strtolower(trim($email)),
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'plan' => 'Free',
            'device_limit' => 1,
            'reg_ip_address' => $ipAddress
        ]);

        return ['email' => $email, 'plan' => 'Free'];
    }
}