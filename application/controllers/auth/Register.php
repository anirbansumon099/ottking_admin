<?php
// application/controllers/auth/Register.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends Base_Controller
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

            // ৪টি প্যারামিটারই পাস করা হচ্ছে
            $session = $this->User_model->register(
                strtolower(trim((string)($data['email'] ?? ''))),
                (string)($data['password'] ?? ''),
                trim((string)($data['device_id'] ?? '')), // ৩ নম্বর প্যারামিটার
                $this->input->ip_address()               // ৪ নম্বর প্যারামিটার (IP)
            );

            $this->send_response(200, $this->crypto_lib->secure_response($session));

        } catch (InvalidArgumentException $e) {
            $this->send_response(400, [
                'debug_status' => 'FAIL_VALIDATION',
                'error'        => $e->getMessage(),
                'endpoint'     => 'auth/register',
            ]);
        } catch (RuntimeException $e) {
            $this->send_response(500, [
                'debug_status' => 'FAIL_RUNTIME',
                'error'        => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            $this->send_response(500, [
                'debug_status' => 'FAIL_FATAL',
                'error'        => $e->getMessage(),
                'trace'        => $e->getFile() . ' Line: ' . $e->getLine(),
            ]);
        }
    }
}