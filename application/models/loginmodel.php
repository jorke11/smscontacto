<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LoginModel extends MY_Model {

    public function __constructor() {
        parent::__construct();
        $this->load->database();
    }
}
