<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EnvioSimple extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        $this->load->view("envio/simple");
    }

    public function insertaMensaje() {
        $data = $this->input->post();
        $data["destinarios"] = (isset($data["destinarios"])) ? $this->divideNumero($data["destinarios"]) : array();
        $data["contactos"] = (isset($data["contactos"])) ? $data["contactos"] : array();
        $numeros = array_merge($data["contactos"], $data["destinarios"]);
        $data["fechaenvio"] = (isset($data["fechaenvio"]) && !empty($data["fechaenvio"])) ? $data["fechaenvio"] : date("Y-m-d H:i:s");
        unset($data["contactos"]);
        unset($data["destinarios"]);
        $idusuario = $this->session->userdata("idusuario");
        $cupo = $this->SaldoUsuario($idusuario);

        $this->controlEnvio($numeros, $data);
    }

}

/* End of file welcome.php */
                                /* Location: ./application/controllers/welcome.php */  

                        