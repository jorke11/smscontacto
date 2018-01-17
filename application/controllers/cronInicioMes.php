<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronInicioMes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        /**
         * reinicio de los contadores de enviados, errores, pendientes y adicionales para todos los usuarios
         */
        $query = "update usuarios set enviados = 0, pendientes = 0, errores = 0, adicion = 0";
        $this->AdministracionModel->ejecutar($query);
    }
}
