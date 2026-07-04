<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // সব ডাটা নিয়ে আসার জন্য
    public function get_all() {
        $this->db->order_by('id', 'DESC');
        return $this->db->get('app_credentials')->result_array();
    }

    // নির্দিষ্ট একটি ডাটা নিয়ে আসার জন্য (এডিট করার সময় প্রয়োজন হবে)
    public function get_by_id($id) {
        return $this->db->get_where('app_credentials', array('id' => $id))->row_array();
    }

    // নতুন API যোগ করার জন্য
    public function insert_api($data) {
        return $this->db->insert('app_credentials', $data);
    }

    // API আপডেট করার জন্য (স্ট্যাটাস এবং অন্যান্য তথ্য)
    public function update_api($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('app_credentials', $data);
    }

    // API ডিলিট করার জন্য
    public function delete_api($id) {
        $this->db->where('id', $id);
        return $this->db->delete('app_credentials');
    }
    
    // স্ট্যাটাস টগল (এক্টিভ/ডিয়েক্টিভ) করার জন্য কুইক ফাংশন
    public function toggle_status($id, $current_status) {
        $new_status = ($current_status == 1) ? 0 : 1;
        $this->db->where('id', $id);
        return $this->db->update('app_credentials', array('status' => $new_status));
    }
}