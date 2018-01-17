<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administracion extends CI_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = '';
    }
    
    public function procesos(){
        $this->load->view("administracion/procesos");
    }
    
     public function archivos(){
        $this->load->view("administracion/archivos");
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */