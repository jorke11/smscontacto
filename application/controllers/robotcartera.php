<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RobotCartera extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        $this->load->view("envio/cartera/index");
    }

    public function cargaTabla() {
        echo $this->datatables->select("id,nombre,fecha,registros,procesado,COALESCE(errores,0) errores", FALSE)
                ->from("archivos")
                ->like("ruta", "cartera", 'both')
                ->generate();
    }

}
