<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
    }

    public function index() {
        // UI এবং লজিক একই কন্ট্রোলারে
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Create Admin - OTTking</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        </head>
        <body class="bg-slate-900 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-[400px] bg-white p-8 rounded-2xl shadow-xl">';
        
        if ($this->session->flashdata('msg')) {
            echo '<div class="alert alert-info text-sm mb-3">'.$this->session->flashdata('msg').'</div>';
        }

        echo form_open('create_admin/save');
        echo '<h2 class="text-xl font-bold mb-4 text-center">Add New Admin</h2>
              <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
              <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
              <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
              <div class="mb-3">
                <select name="role" class="form-control">
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-100">Create Admin</button>';
        echo form_close();
        
        echo '</div></body></html>';
    }

    public function save() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[admin.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('msg', validation_errors());
        } else {
            $data = [
                'username'   => $this->input->post('username'),
                'email'      => $this->input->post('email'),
                'password'   => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role'       => $this->input->post('role'),
                'is_verify'  => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->db->insert('admin', $data)) {
                $this->session->set_flashdata('msg', 'Admin created successfully!');
            } else {
                $this->session->set_flashdata('msg', 'Failed to create.');
            }
        }
        redirect('create_admin');
    }
}