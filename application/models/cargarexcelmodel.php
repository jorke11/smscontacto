<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CargarexcelModel extends MY_Model {

    public function __constructor() {
        parent::__construct();
        $this->load->database();
    }

    public function eliminaGerencias($data) {

        $sql = "
            DELETE FROM datos where codigo_comercial='" . $data[4] . "'
                ";

        $this->db->query($sql);
    }

    public function eliminaGerenciaMacro($data) {
        $sql = "
            DELETE FROM datos where codigo_comercial='" . $data["codigo_comercial"] . "'
                ";
        $this->db->query($sql);
    }

}