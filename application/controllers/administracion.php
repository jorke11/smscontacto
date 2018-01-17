<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administracion extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = '';
    }

    public function procesos() {
        $carpeta["archivos"] = $this->Directorios("/home/ftpnatura/archivos/");
        $this->load->view("administracion/procesos", $carpeta);
    }

    public function archivos() {
        $this->load->view("administracion/archivos");
    }

    public function robotCartera() {
        $this->load->view("administracion/robotcartera");
    }
    public function carteraManual() {
        $this->load->view("administracion/carteramanual");
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */