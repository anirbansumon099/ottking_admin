<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Catalog_model');
    }

    public function index()
    {
        $requestData = [];
        $debug       = false;
        $debugInfo   = [];

        try {
            // ১. সিকিউর পেলোড রিসিভ ও ডিক্রিপ্ট করা
            $requestData = $this->get_secure_request_payload();

            // পেলোডে "debug": true পাঠালে রেসপন্সের সাথে ডিবাগ তথ্যও যোগ হবে
            // (টেস্টিং শেষ হলে এই ব্লকটা রিমুভ করে দেবেন)
            $debug = !empty($requestData['debug']);

            $planId = null; // ডিফল্ট: সেশন নেই/ইনভ্যালিড -> is_premium=0 চ্যানেল দেখাবে

            $email        = isset($requestData['email']) ? trim((string)$requestData['email']) : '';
            $sessionToken = isset($requestData['session_token']) ? trim((string)$requestData['session_token']) : '';
            $deviceId     = isset($requestData['device_id']) ? trim((string)$requestData['device_id']) : '';

            $debugInfo['email']         = $email;
            $debugInfo['has_session']   = !empty($sessionToken);
            $debugInfo['has_device_id'] = !empty($deviceId);

            // ২. যদি ইমেইল, সেশন টোকেন এবং ডিভাইস আইডি থাকে, তবে ভ্যালিডেট করব
            if (!empty($email) && !empty($sessionToken) && !empty($deviceId)) {

                // মডেলের মাধ্যমে ডিভাইস এবং সেশন ভ্যালিডেশন
                $validationResult = $this->User_model->validate_device_session($email, $sessionToken, $deviceId);
                $debugInfo['validation_result'] = $validationResult;

                // ভ্যালিড ইউজার হলে এবং প্যাকেজের মেয়াদ শেষ না হলে plan_id সেট থাকবে
                // (এক্সপায়ারি চেক User_model::find_by_email এর ভিতরেই হয়ে যায়)
                if (is_array($validationResult) && !empty($validationResult['plan_id'])) {
                    $planId = (int)$validationResult['plan_id'];
                }
                // নোট: সেশন ইনভ্যালিড হলে, ডিভাইস না মিললে, বা প্যাকেজ এক্সপায়ার্ড হলে
                // $planId null-ই থাকবে -> ফলাফলে শুধু is_premium=0 চ্যানেল দেখানো হবে।
            } else {
                $debugInfo['skip_reason'] = 'email/session_token/device_id এর একটা খালি এসেছে';
            }

            $debugInfo['resolved_plan_id'] = $planId;

            // ৩. plan_id অনুযায়ী ক্যাটালগ নিয়ে আসা (package_channels ম্যাচিং সহ)
            $catalog = $this->Catalog_model->fetch($planId);

            if ($debug) {
                $catalog['_debug'] = $debugInfo;
            }

            // ৪. সফল রেসপন্স (সব সময় ২০০ পাঠানো হচ্ছে)
            $this->send_response(200, $this->crypto_lib->secure_response($catalog));

        } catch (Throwable $e) {
            $debugInfo['exception'] = [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ];

            // ডিবাগ লগ (লগিং চালু থাকলে application/logs/log-YYYY-MM-DD.php এ যাবে)
            log_message('error', 'Catalog::index exception: ' . $e->getMessage()
                . ' | file=' . $e->getFile() . ':' . $e->getLine());

            // কোনো সিস্টেম এরর হলেও অ্যাপ ক্র্যাশ করবে না, শুধু ফ্রি (is_premium=0) ক্যাটালগ লোড হবে
            $catalog = $this->Catalog_model->fetch(null);

            if ($debug) {
                $catalog['_debug'] = $debugInfo;
            }

            $this->send_response(200, $this->crypto_lib->secure_response($catalog));
        }
    }
}
