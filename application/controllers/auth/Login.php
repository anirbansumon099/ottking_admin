<?php
// application/controllers/auth/Login.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index()
    {
        try {
            $data = $this->get_secure_request_payload();

            if (empty($data['email']) || empty($data['password']) || empty($data['device_id'])) {
                throw new InvalidArgumentException('Missing required fields.');
            }

            $session = $this->User_model->authenticate(
                (string)$data['email'],
                (string)$data['password'],
                (string)$data['device_id']
            );

            $this->send_response(200, $this->crypto_lib->secure_response($session));

        } catch (InvalidArgumentException $e) {
            $this->send_response(400, ['error' => $e->getMessage()]);
        } catch (RuntimeException $e) {
            $this->send_response(403, ['error' => $e->getMessage()]); // ৪MD৩ ব্যবহার করা উত্তম
        } catch (Throwable $e) {
            $this->send_response(500, ['error' => 'Server Error']);
        }
    }

    /**
     * লগআউট মেথড
     */
    public function logout()
    {
        try {
            $data = $this->get_secure_request_payload();
            
            $userId   = $data['user_id'] ?? null;
            $deviceId = $data['device_id'] ?? null;

            if (!$userId || !$deviceId) {
                throw new InvalidArgumentException('User ID and Device ID are required for logout.');
            }

            // সেশন মুছে ফেলা
            $this->db->where('user_id', $userId);
            $this->db->where('device_id', $deviceId);
            $deleted = $this->db->delete('user_sessions');

            if ($deleted) {
                $this->send_response(200, ['status' => 'success', 'message' => 'Logged out successfully.']);
            } else {
                throw new RuntimeException('Failed to logout. Session not found.');
            }

        } catch (Exception $e) {
            $this->send_response(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}