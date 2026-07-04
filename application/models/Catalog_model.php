<?php
// application/models/Catalog_model.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ইউজারের plan_id অনুযায়ী ক্যাটালগ ডেটা রিটার্ন করে।
     * $planId: null হলে (লগইন নেই / সেশন ইনভ্যালিড / প্ল্যান এক্সপায়ার্ড) -> শুধু is_premium = 0 চ্যানেল
     *          int হলে (ভ্যালিড সেশন, প্ল্যান এক্সপায়ার হয়নি) -> ওই plan_id এর সাথে package_channels এ
     *          ম্যাচ করা channel_id গুলো
     */
    public function fetch(?int $planId = null): array
    {
        $channels = $this->_get_channels($planId);
        $categories = $this->_get_categories();
         $banners    = [];//$this->_get_banners();
        $plans      = $this->_get_plans();

        return [
            'channels'   => $channels,
            'categories' => $categories,
            'banners'    => $banners,
            'plans'      => $plans,
        ];
    }

    private function _get_channels(?int $planId): array
    {
        if ($planId !== null) {
            // ভ্যালিড, নন-এক্সপায়ার্ড প্যাকেজ -> package_channels এ plan_id ম্যাচ করা চ্যানেলগুলো
            $this->db->select('channels.*, categories.name AS category_name');
            $this->db->from('channels');
            $this->db->join('package_channels', 'package_channels.channel_id = channels.id');
            $this->db->join('categories', 'categories.id = channels.category_id', 'left');
            $this->db->where('package_channels.plan_id', $planId);
            $this->db->order_by('channels.id', 'ASC');
            $query = $this->db->get();
        } else {
            // সেশন নেই / ইনভ্যালিড / প্ল্যান এক্সপায়ার্ড -> শুধু ফ্রি (is_premium = 0) চ্যানেল
            $this->db->select('channels.*, categories.name AS category_name');
            $this->db->from('channels');
            $this->db->join('categories', 'categories.id = channels.category_id', 'left');
            $this->db->where('channels.is_premium', 0);
            $this->db->order_by('channels.id', 'ASC');
            $query = $this->db->get();
        }

        $channels = [];
        foreach ($query->result_array() as $row) {
            $channels[] = [
                'id'          => (string)$row['id'],
                'channelId'   => (string)$row['id'],
                'name'        => (string)($row['name'] ?? 'Unknown'),
                // category_id দিয়ে categories টেবিল থেকে জয়েন করা আসল নাম;
                // কোনো কারণে category_id ম্যাচ না করলে (orphan) 'General' এ ফলব্যাক
                'category'    => (string)($row['category_name'] ?? 'General'),
                'streamUrl'   => (string)($row['stream_url'] ?? $row['url'] ?? ''),
                'logoUrl'     => (string)($row['logo_url'] ?? $row['logo'] ?? ''),
                'description' => (string)($row['description'] ?? ''),
                'quality'     => (string)($row['quality'] ?? 'HD'),
                'isPremium'   => (int)($row['is_premium'] ?? 0),
            ];
        }
        return $channels;
    }

    private function _get_categories(): array
    {
        $query = $this->db->order_by('name', 'ASC')->get('categories');
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[] = [
                'name' => (string)$row['name'],
                'icon' => (string)($row['icon'] ?? '📺'),
            ];
        }
        return $result;
    }

    private function _get_banners(): array
    {
        $query = $this->db->order_by('id', 'DESC')->get('banners');
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[] = [
                'title'     => (string)$row['title'],
                'subtitle'  => (string)($row['subtitle'] ?? ''),
                'imageUrl'  => (string)$row['image_url'],
                'channelId' => (string)$row['channel_id'],
            ];
        }
        return $result;
    }

    private function _get_plans(): array
    {
        $query = $this->db->select('name, price, description, badge, features')
                          ->order_by('id', 'ASC')
                          ->get('subscription_plans');
        $result = [];
        foreach ($query->result_array() as $row) {
            $feats    = !empty($row['features']) ? array_map('trim', explode(',', $row['features'])) : [];
            $result[] = [
                'name'        => (string)$row['name'],
                'price'       => (string)$row['price'],
                'description' => (string)($row['description'] ?? ''),
                'badge'       => (string)($row['badge'] ?? ''),
                'features'    => $feats,
            ];
        }
        return $result;
    }
}
