<?php
// application/controllers/Health.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Health extends Base_Controller
{
    public function index()
    {
        $this->send_response(200, [
            'status'  => 'ok',
            'service' => 'oTtking API',
            'time'    => date('c'),
        ]);
    }
}
