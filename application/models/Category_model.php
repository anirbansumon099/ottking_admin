<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // সব ক্যাটাগরি + প্রতিটির চ্যানেল সংখ্যা
    public function get_all_with_count() {
        $this->db->select('categories.*, COUNT(channels.id) as channel_count');
        $this->db->from('categories');
        $this->db->join('channels', 'channels.category_id = categories.id', 'left');
        $this->db->group_by('categories.id');
        $this->db->order_by('categories.sort_order', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('categories', ['id' => $id])->row_array();
    }

    public function add($data) {
        return $this->db->insert('categories', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
}
