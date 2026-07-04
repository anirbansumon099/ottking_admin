<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->helper('url'); 
        $this->load->library('session');
    	$this->load->model('Api_model');

        $this->load->model('Channel_model');
        $this->load->model('Category_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

#================Index Pages==============#



    public function index() {
        redirect('admin/dashboard');
    }

    
#================Logout===================#
public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }




#================Dashboard================#

    public function dashboard() {
        $data['title'] = "Admin Dashboard";
        $data['main_content'] = 'admin/dashboard_overview'; 
        $this->load->view('admin/index', $data);
    }

#==============Channels Sections =========#

public function channels($param1 = '', $param2 = '') {
    $this->load->library('pagination');

    // ১. ডিলিট অপারেশন
    if ($param1 == 'delete' && !empty($param2)) {
        $this->Channel_model->delete_channel($param2);
        redirect('admin/channels');
    }

    // নতুন চ্যানেল যোগ করা (আগে এই অংশটি ছিলই না, ফলে "Add New" বাটনে
    // কিছুই সেভ হতো না — মডাল ফর্মটাও ভিউতে মিসিং ছিল, সেটাও যুক্ত করা হয়েছে)
    if ($param1 == 'add' && $this->input->post('name')) {
        $this->Channel_model->add([
            'name'        => $this->input->post('name'),
            'category_id' => $this->input->post('category_id'),
            'stream_url'  => $this->input->post('stream_url'),
            'logo_url'    => $this->input->post('logo_url'),
            'description' => $this->input->post('description'),
            'quality'     => $this->input->post('quality'),
            'is_premium'  => $this->input->post('is_premium'),
        ]);
        redirect('admin/channels');
    }

    // এডিট ফর্ম দেখানো (আগে 'edit' কে কোনোভাবেই হ্যান্ডেল করা হতো না,
    // ফলে Edit লিংকে ক্লিক করলে সাইলেন্টলি সাধারণ লিস্ট পেজ দেখাতো)
    if ($param1 == 'edit' && !empty($param2)) {
        $data['channel'] = $this->Channel_model->get_by_id($param2);
        if (!$data['channel']) {
            show_404();
        }
        $data['categories']   = $this->db->get('categories')->result_array();
        $data['main_content'] = 'admin/edit_channel';
        $this->load->view('admin/index', $data);
        return;
    }

    // এডিট ফর্ম সাবমিট হওয়ার পর আপডেট করা
    if ($param1 == 'update' && !empty($param2) && $this->input->post()) {
        $this->Channel_model->update($param2, [
            'name'        => $this->input->post('name'),
            'category_id' => $this->input->post('category_id'),
            'stream_url'  => $this->input->post('stream_url'),
            'logo_url'    => $this->input->post('logo_url'),
            'description' => $this->input->post('description'),
            'quality'     => $this->input->post('quality'),
            'is_premium'  => $this->input->post('is_premium'),
        ]);
        redirect('admin/channels');
    }

    // ২. সার্চ এবং ফিল্টার প্যারামিটার ধরা
    $search = $this->input->get('search');
    $cat_id = $this->input->get('category_id');

    // ৩. টোটাল ডাটা কাউন্ট করার জন্য কুয়েরি (Pagination এর জন্য)
    $this->db->from('channels');
    if (!empty($search)) $this->db->like('name', $search);
    if (!empty($cat_id)) $this->db->where('category_id', $cat_id);
    $total_rows = $this->db->count_all_results();

    // ৪. Pagination কনফিগারেশন
    $config['base_url'] = base_url('admin/channels');
    $config['total_rows'] = $total_rows;
    $config['per_page'] = 10;
    $config['reuse_query_string'] = TRUE; // এটি খুব জরুরি, যেন ফিল্টার করার পর পেজেশনে সার্চ ডাটা হারিয়ে না যায়
    $this->pagination->initialize($config);

    // ৫. ডাটা ফেচ করা (JOIN সহ)
    $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    
    $this->db->select('channels.*, categories.name as category_name');
    $this->db->from('channels');
    $this->db->join('categories', 'categories.id = channels.category_id', 'left');
    
    if (!empty($search)) $this->db->like('channels.name', $search);
    if (!empty($cat_id)) $this->db->where('channels.category_id', $cat_id);
    
    $this->db->limit($config['per_page'], $page);
    $data['channels'] = $this->db->get()->result_array();
    
    // ভিউ ডাটা
    $data['categories'] = $this->db->get('categories')->result_array();
    $data['pagination'] = $this->pagination->create_links();
    $data['main_content'] = 'admin/view_channels';
    $this->load->view('admin/index', $data);
}

#===============Categories Section========#

    public function categories($param1 = '', $param2 = '') {
        $data['main_content'] = 'admin/view_categories';
        $this->load->view('admin/index', $data); 
    }

    // নিচের ৪টি মেথড আগে একেবারেই ছিল না। view_categories.php এর jQuery কোড
    // admin/get_categories_ajax, admin/add_category_ajax ইত্যাদি এন্ডপয়েন্টে
    // কল করত, কিন্তু Admin.php তে সেগুলোর কোনো হ্যান্ডলার না থাকায় প্রতিটা
    // রিকোয়েস্ট 404 পেত এবং ক্যাটাগরি লিস্ট/অ্যাড কখনোই কাজ করত না।

    public function get_categories_ajax() {
        $categories = $this->Category_model->get_all_with_count();
        echo json_encode($categories);
    }

    public function add_category_ajax() {
        $name = trim($this->input->post('category_name'));
        if ($name === '') {
            return $this->_json_error('Category name is required.');
        }
        $this->Category_model->add(['name' => $name]);
        echo json_encode(['status' => 'ok']);
    }

    public function edit_category_ajax($id = '') {
        $name = trim($this->input->post('category_name'));
        if (empty($id) || $name === '') {
            return $this->_json_error('Category id and name are required.');
        }
        $this->Category_model->update($id, ['name' => $name]);
        echo json_encode(['status' => 'ok']);
    }

    public function delete_category_ajax($id = '') {
        if (empty($id)) {
            return $this->_json_error('Category id is required.');
        }
        $this->Category_model->delete($id);
        echo json_encode(['status' => 'ok']);
    }

    private function _json_error($message) {
        $this->output->set_status_header(400);
        echo json_encode(['status' => 'error', 'message' => $message]);
    }

    // sidebar.php আগে থেকেই admin/subscriptions এবং admin/subscriptions/packages
    // লিংক করত, কিন্তু এই মেথডটাই আদৌ ছিল না — তাই দুটো মেনু আইটেমই 404 দিত।
    public function subscriptions($param1 = '', $param2 = '') {

        // --- সাব-সেকশন: প্ল্যান ম্যানেজমেন্ট (admin/subscriptions/packages/...) ---
        if ($param1 == 'packages') {
            if ($param2 == 'add') {
                $this->db->insert('subscription_plans', [
                    'name'        => $this->input->post('name'),
                    'price'       => $this->input->post('price'),
                    'description' => $this->input->post('description'),
                    'badge'       => $this->input->post('badge'),
                    'features'    => $this->input->post('features'),
                ]);
                redirect('admin/subscriptions/packages');
            }
            if ($param2 == 'edit' && $this->input->post('name')) {
                $id = $this->uri->segment(5);
                $this->db->where('id', $id)->update('subscription_plans', [
                    'name'        => $this->input->post('name'),
                    'price'       => $this->input->post('price'),
                    'description' => $this->input->post('description'),
                    'badge'       => $this->input->post('badge'),
                    'features'    => $this->input->post('features'),
                ]);
                redirect('admin/subscriptions/packages');
            }
            if ($param2 == 'delete') {
                $id = $this->uri->segment(5);
                $this->db->where('id', $id)->delete('subscription_plans');
                redirect('admin/subscriptions/packages');
            }

            // package_channels টেবিলের জন্য এতদিন কোনো UI/ব্যাকএন্ডই ছিল না —
            // এই অংশটা একটা প্ল্যানের সাথে কোন কোন চ্যানেল যুক্ত থাকবে সেটা সেট করে।
            if ($param2 == 'channels') {
                $plan_id = $this->uri->segment(5);
                if (!empty($plan_id) && $this->input->post()) {
                    $selected_channel_ids = $this->input->post('channel_ids'); // array বা null

                    $this->db->trans_start();
                    $this->db->where('plan_id', $plan_id)->delete('package_channels');
                    if (!empty($selected_channel_ids)) {
                        $rows = [];
                        foreach ($selected_channel_ids as $cid) {
                            $rows[] = ['plan_id' => $plan_id, 'channel_id' => $cid];
                        }
                        $this->db->insert_batch('package_channels', $rows);
                    }
                    $this->db->trans_complete();
                    redirect('admin/subscriptions/packages');
                }
            }

            // প্রতিটা প্ল্যানের সাথে কোন কোন চ্যানেল যুক্ত আছে সেটা মোডাল প্রি-ফিল করার জন্য পাঠানো হচ্ছে
            $data['channels'] = $this->db->get('channels')->result_array();
            $assigned = [];
            foreach ($this->db->get('package_channels')->result_array() as $pc) {
                $assigned[$pc['plan_id']][] = (int) $pc['channel_id'];
            }
            $data['assigned_channels'] = $assigned;

            $data['packages'] = $this->db->get('subscription_plans')->result_array();
            $data['main_content'] = 'admin/view_packages';
            $this->load->view('admin/index', $data);
            return;
        }

        // --- সাব-সেকশন: ইউজারের প্ল্যান এক্সটেন্ড করা (কুইক +৩০ দিন) ---
        if ($param1 == 'extend' && !empty($param2)) {
            $user = $this->db->get_where('users', ['id' => $param2])->row_array();
            if ($user) {
                $base = (!empty($user['plan_expires_at']) && strtotime($user['plan_expires_at']) > time())
                    ? $user['plan_expires_at']
                    : date('Y-m-d H:i:s');
                $new_expiry = date('Y-m-d H:i:s', strtotime($base . ' +30 days'));
                $this->db->where('id', $param2)->update('users', ['plan_expires_at' => $new_expiry]);
            }
            redirect('admin/subscriptions');
        }

        // --- সাব-সেকশন: ইউজারের প্ল্যান পরিবর্তন করা ---
        if ($param1 == 'update' && !empty($param2) && $this->input->post()) {
            $plan_id = $this->input->post('plan_id');
            $update = ['plan_expires_at' => $this->input->post('plan_expires_at')];
            if (!empty($plan_id)) {
                $plan = $this->db->get_where('subscription_plans', ['id' => $plan_id])->row_array();
                $update['plan_id'] = $plan_id;
                $update['plan'] = $plan ? $plan['name'] : 'Premium';
            } else {
                $update['plan_id'] = null;
                $update['plan'] = 'Free';
            }
            $this->db->where('id', $param2)->update('users', $update);
            redirect('admin/subscriptions');
        }

        // --- ডিফল্ট: সব ইউজারের সাবস্ক্রিপশন লিস্ট ---
        $this->db->order_by('plan_expires_at', 'DESC');
        $data['subscriptions'] = $this->db->get('users')->result_array();
        $data['plans'] = $this->db->get('subscription_plans')->result_array();
        $data['main_content'] = 'admin/view_subscriptions';
        $this->load->view('admin/index', $data);
    }

#==============Admin Sections=============#

    public function view_admin($param1 = '', $param2 = ''){
        // ডিলিট
        if ($param1 == 'delete' && !empty($param2)) {
            // নিজের অ্যাকাউন্ট নিজেই ডিলিট করা আটকানো হলো
            if ($param2 != $this->session->userdata('user_id')) {
                $this->db->where('id', $param2)->delete('admin');
            }
            redirect('admin/view_admin');
        }

        // ভেরিফাই/আনভেরিফাই টগল
        if ($param1 == 'verify' && !empty($param2)) {
            $current = $this->db->get_where('admin', ['id' => $param2])->row_array();
            if ($current) {
                $this->db->where('id', $param2)
                          ->update('admin', ['is_verify' => $current['is_verify'] ? 0 : 1]);
            }
            redirect('admin/view_admin');
        }

        // নতুন অ্যাডমিন যোগ করা
        if ($param1 == 'add' && $this->input->post('username')) {
            $this->db->insert('admin', [
                'username' => $this->input->post('username'),
                'email'    => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role'     => $this->input->post('role'),
                'is_verify' => 1,
            ]);
            redirect('admin/view_admin');
        }

        // অ্যাডমিন এডিট করা (পাসওয়ার্ড ফাঁকা রাখলে পুরনো পাসওয়ার্ডই থাকবে)
        if ($param1 == 'edit' && !empty($param2) && $this->input->post('username')) {
            $update = [
                'username' => $this->input->post('username'),
                'email'    => $this->input->post('email'),
                'role'     => $this->input->post('role'),
            ];
            $new_password = $this->input->post('password');
            if (!empty($new_password)) {
                $update['password'] = password_hash($new_password, PASSWORD_DEFAULT);
            }
            $this->db->where('id', $param2)->update('admin', $update);
            redirect('admin/view_admin');
        }

        $this->db->order_by('created_at', 'DESC');
        $data['admins'] = $this->db->get('admin')->result_array();
        $data['main_content'] = 'admin/view_admin';
        $this->load->view('admin/index', $data);
    }


#========Admin Profile Sections==========#

public function profile($param1 = '', $param2 = ''){
        $data['main_content'] = 'admin/view_profiles';
        $this->load->view('admin/index', $data);
    }



#==============User Sections==============#

    // 5. User Management (সম্পূর্ণ কোড)
    public function users($param1 = '', $param2 = '') {
        
        // যদি $param1 'delete' হয় এবং $param2 তে আইডি থাকে
        if ($param1 == 'delete' && !empty($param2)) {
            // ইউজারকে ডাটাবেস থেকে ডিলিট করা
            $this->db->where('id', $param2);
            $this->db->delete('users');
            
            // ডিলিট করার পর আবার ইউজার লিস্ট পেজে পাঠানো
            redirect('admin/users', 'refresh');
        }

        // যদি $param1 'status' হয় (অ্যাক্টিভ বা ব্যান করার জন্য)
        if ($param1 == 'status' && !empty($param2)) {
            $status_value = $this->input->get('val'); // URL থেকে ভ্যালু নেয়া (যেমন: ?val=0)
            
            $this->db->where('id', $param2);
            $this->db->update('users', ['status' => $status_value]);
            
            redirect('admin/users', 'refresh');
        }

        // ইউজার লিস্ট দেখানোর জন্য সব ইউজার ডাটাবেস থেকে নেয়া
        $this->db->order_by('created_at', 'DESC'); // নতুন ইউজার উপরে দেখানোর জন্য
        $data['users'] = $this->db->get('users')->result_array();
        
        // ভিউ ফাইল সেট করা
        $data['main_content'] = 'admin/view_users';
        $this->load->view('admin/index', $data);
    }

#==============>Subscription==============#

    public function packages($param1 = '', $param2 = '') {
    if ($param1 == 'add') {
        $data = [
            'name'        => $this->input->post('name'),
            'price'       => $this->input->post('price'),
            'description' => $this->input->post('description'),
            'badge'       => $this->input->post('badge'),
            'features'    => $this->input->post('features')
        ];
        $this->db->insert('subscription_plans', $data);
        redirect('admin/packages');
    }
    
    if ($param1 == 'edit' && !empty($param2)) {
        $data = [
            'name'        => $this->input->post('name'),
            'price'       => $this->input->post('price'),
            'description' => $this->input->post('description'),
            'badge'       => $this->input->post('badge'),
            'features'    => $this->input->post('features')
        ];
        $this->db->where('id', $param2)->update('subscription_plans', $data);
        redirect('admin/packages');
    }

    if ($param1 == 'delete' && !empty($param2)) {
        $this->db->where('id', $param2)->delete('subscription_plans');
        redirect('admin/packages');
    }

    // নতুন সাব-প্যারাম: assign_channels — একটা প্ল্যানের সাথে কোন কোন চ্যানেল
    // যুক্ত থাকবে সেটা এখান থেকেই অ্যাড/এডিট করা যাবে (package_channels টেবিল)
    if ($param1 == 'assign_channels' && !empty($param2)) {
        if ($this->input->post()) {
            $selected_channel_ids = $this->input->post('channel_ids'); // চেকবক্স থেকে আসা array, কিছু সিলেক্ট না করলে null

            $this->db->trans_start();
            $this->db->where('plan_id', $param2)->delete('package_channels');
            if (!empty($selected_channel_ids)) {
                $rows = [];
                foreach ($selected_channel_ids as $channel_id) {
                    $rows[] = ['plan_id' => $param2, 'channel_id' => $channel_id];
                }
                $this->db->insert_batch('package_channels', $rows);
            }
            $this->db->trans_complete();
            redirect('admin/packages');
        }
    }

    // প্রতিটা প্ল্যানের সাথে এখন কোন কোন চ্যানেল যুক্ত আছে — ভিউতে মোডাল প্রি-ফিল করার জন্য
    $data['channels'] = $this->db->get('channels')->result_array();
    $assigned = [];
    foreach ($this->db->get('package_channels')->result_array() as $pc) {
        $assigned[$pc['plan_id']][] = (int) $pc['channel_id'];
    }
    $data['assigned_channels'] = $assigned;

    $data['packages'] = $this->db->get('subscription_plans')->result_array();
    $data['main_content'] = 'admin/view_pacages';
    $this->load->view('admin/index', $data);
}


#=================>Payment================#
public function payments($param1 = '', $param2 = '') {
    // Add Method
    if ($param1 == 'add') {
        $data = [
            'name'       => $this->input->post('name'),
            'api_key'    => $this->input->post('api_key'),
            'api_secret' => $this->input->post('api_secret'),
            'status'     => $this->input->post('status')
        ];
        $this->db->insert('payment_methods', $data);
        redirect('admin/payments');
    }
    
    // Edit Method
    if ($param1 == 'edit' && !empty($param2)) {
        $data = [
            'name'       => $this->input->post('name'),
            'api_key'    => $this->input->post('api_key'),
            'api_secret' => $this->input->post('api_secret'),
            'status'     => $this->input->post('status')
        ];
        $this->db->where('id', $param2)->update('payment_methods', $data);
        redirect('admin/payments');
    }

    // Delete Method
    if ($param1 == 'delete' && !empty($param2)) {
        $this->db->where('id', $param2)->delete('payment_methods');
        redirect('admin/payments');
    }

    $data['payments'] = $this->db->get('payment_methods')->result_array();
    $data['main_content'] = 'admin/view_payments';
    $this->load->view('admin/index', $data);
}


public function transactions() {
    // শুধুমাত্র লিস্ট দেখা এবং ফিল্টার করা
    $this->db->select('transactions.*, users.username'); // ধরে নিচ্ছি users নামে একটি টেবিল আছে
    $this->db->from('transactions');
    $this->db->join('users', 'users.id = transactions.user_id', 'left');
    $this->db->order_by('transactions.created_at', 'DESC');
    
    $data['transactions'] = $this->db->get()->result_array();
    $data['main_content'] = 'admin/view_transactions';
    $this->load->view('admin/index', $data);
}





#=================>SETTINGS<================#    
    
public function systems($param1 = '', $param2 = ''){
        $data['main_content'] = 'admin/view_profiles';
        $this->load->view('admin/index', $data);
    }


  

public function api_settings($param1 = 'list', $param2 = '') {
        $data['title'] = 'API Settings';

        // ১. DELETE অপারেশন
        if ($param1 == 'delete' && !empty($param2)) {
            $this->Api_model->delete_api($param2);
            redirect('admin/api_settings');
        }

        // ২. ADD অথবা EDIT অপারেশন (ফর্ম সাবমিট হলে)
        if ($this->input->post('submit')) {
            $api_data = [
                'app_name'       => $this->input->post('app_name'),
                'app_version'    => $this->input->post('app_version'),
                'app_key_id'     => $this->input->post('app_key_id'),
                'status'         => $this->input->post('status')
            ];

            // শুধু নতুন যোগ করার সময় সিকিউর কি জেনারেট হবে
            if ($param1 == 'add') {
                $api_data['hmac_secret'] = bin2hex(random_bytes(16));
                $api_data['encryption_key'] = bin2hex(random_bytes(16));
                $this->Api_model->insert_api($api_data);
            } 
            // এডিট করার সময়
            elseif ($param1 == 'edit' && !empty($param2)) {
                $this->Api_model->update_api($param2, $api_data);
            }
            redirect('admin/api_settings');
        }

        // ৩. ভিউ লোড করা
        $data['api_list'] = $this->Api_model->get_all();
        
        // এডিট মোড হলে নির্দিষ্ট ডাটা পাঠানো
        if ($param1 == 'edit' && !empty($param2)) {
            $data['edit_data'] = $this->Api_model->get_by_id($param2);
        }

        $data['main_content'] = 'admin/view_apikey';
        $this->load->view('admin/index', $data);
    }




public function auth($param1 = '', $param2 = ''){
        $data['main_content'] = 'admin/view_profiles';
        $this->load->view('admin/index', $data);
    }
    
    
    
    
}





