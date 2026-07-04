<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_model extends CI_Model {

    public function get_all() {
        return $this->db->get('channels')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('channels', ['id' => $id])->row_array();
    }

    // নতুন চ্যানেল যোগ করা
    // NOTE: আগে এখানে 'id' => uniqid() সেট করা হতো, কিন্তু channels টেবিলে id হলো
    // int AUTO_INCREMENT কলাম। uniqid() একটি স্ট্রিং রিটার্ন করে, যা int কলামে
    // ইনসার্ট করতে গেলে সাইলেন্টলি ০ (বা এরর) হয়ে যেত এবং auto_increment ভেঙে যেত।
    // এখন id বাদ দেওয়া হয়েছে, যাতে MySQL নিজেই next id জেনারেট করে।
    // এছাড়া stream_url (NOT NULL কলাম) আগে মিসিং ছিল, যেটা যুক্ত করা হয়েছে।
    public function add($data) {
        $insert_data = [
            'name'         => $data['name'],
            'category_id'  => $data['category_id'],
            'stream_url'   => $data['stream_url'],
            'logo_url'     => $data['logo_url'] ?? '',
            'description'  => $data['description'] ?? '',
            'quality'      => $data['quality'] ?? 'HD',
            'is_premium'   => isset($data['is_premium']) ? 1 : 0,
            'is_active'    => 1,
        ];
        return $this->db->insert('channels', $insert_data);
    }

    public function update($id, $data) {
        $update_data = [
            'name'         => $data['name'],
            'category_id'  => $data['category_id'],
            'stream_url'   => $data['stream_url'],
            'logo_url'     => $data['logo_url'] ?? '',
            'description'  => $data['description'] ?? '',
            'quality'      => $data['quality'] ?? 'HD',
            'is_premium'   => isset($data['is_premium']) ? 1 : 0,
        ];
        $this->db->where('id', $id);
        return $this->db->update('channels', $update_data);
    }

    public function delete_channel($id) {
        $this->db->trans_start();
        $this->db->where('channel_id', $id)->delete('package_channels');
        $this->db->where('id', $id)->delete('channels');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
