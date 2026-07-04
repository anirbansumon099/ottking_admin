<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form', 'security']);
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $this->redirect_by_role($this->session->userdata('role'));
        }
        $this->load->view('login_view');
    }

    public function login_process() {
        // ফর্ম ভ্যালিডেশন
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login_view');
        } else {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE); 

            $sql = "SELECT * FROM `admin` WHERE `email` = ? LIMIT 1";
            $query = $this->db->query($sql, array($email));

            if ($query->num_rows() == 1) {
                $user = $query->row_array(); 
                
                // ভেরিফিকেশন চেক
                if ($user['is_verify'] == 0) {
                    $this->session->set_flashdata('error', 'আপনার অ্যাকাউন্টটি এখনো ভেরিফাই হয়নি!');
                    redirect('login');
                }
                
                // পাসওয়ার্ড ভেরিফাই
                if (password_verify($password, $user['password'])) {
                    
                    // সেশন রিজেনারেশন (নিরাপত্তার জন্য)
                    $this->session->sess_regenerate(TRUE);

                    $this->db->where('id', $user['id']);
                    $this->db->update('admin', ['last_login' => date('Y-m-d H:i:s')]);

                    $user_data = [
                        'user_id'   => $user['id'],
                        'username'  => $user['username'],
                        'email'     => $user['email'],
                        'role'      => $user['role'],
                        'logged_in' => TRUE
                    ];
                    $this->session->set_userdata($user_data);

                    $this->redirect_by_role($user['role']);
                } else {
                    $this->session->set_flashdata('error', 'ভুল পাসওয়ার্ড!');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('error', 'এই ইমেইলটি নিবন্ধিত নয়!');
                redirect('login');
            }
        }
    }

    private function redirect_by_role($role) {
        switch ($role) {
            case 'superadmin': redirect('superadmin'); break;
            case 'admin': redirect('admin'); break;
            case 'editor': redirect('editor'); break;
            default: $this->logout(); break;
        }
    }

    
}