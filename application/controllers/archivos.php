<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Archivos extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = '';
    }

    public function cargaTabla() {
        $fecha = date('Y-m-d');
        $nuevafecha = strtotime('-2 day', strtotime($fecha));
        $nuevafecha = date('Y-m-d', $nuevafecha);

        $draw = 1;
        $campos = "*";
        $datos = $this->AdministracionModel->buscar("archivos ", 'id,nombre,fecha,registros,procesado', "fecha>'" . $nuevafecha . "'");
        $respuesta = $this->dataTable($datos);
        $respuesta["draw"] = 1;
        echo json_encode($respuesta);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */