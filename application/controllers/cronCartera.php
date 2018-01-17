<?php

include(APPPATH . "controllers/croncreagrupos.php");


class CronCartera extends MY_Controller {

    public $rutaftp;
    public $ci;

    public function __construct() {
        parent::__construct();
//        $this->load->library("reader");
        $this->load->model("AdministracionModel");
        $this->load->model("CargarexcelModel");
        $this->load->model("CroncargaModel");
        $this->rutaftp = '/home/pruebasftp/';

        $this->ci = &get_instance();
//        $this->rutaftp = '/home/autonatura/';
    }

    public function index() {
        $ci = & get_instance();
        $ruta = $this->rutaftp . "cartera";
//        $ruta = "/home/autonatura/archivos";
        $lista = $this->Directorios($ruta);
        if (count($lista) == 1) {
            $sql = "TRUNCATE cartera RESTART IDENTITY CASCADE;";
            $ci->db->query($sql);
            $this->procesaArchivo($lista[0]);
        } else {
            $in["nombre"] = "ERROR carpeta vacia";
            $in["ruta"] = "cartera";
            $in["idusuario"] = -1;
            $in["fecha"] = date("Y-m-d H:i:s");
            $this->CroncargaModel->insertar("archivos", $in);
            print_r($in);
        }
    }

//    public function procesaArchivo($datos) {
//        $this->CroncargaModel->cargaDatosCartera($datos);
//    }

    public function procesaArchivo($datos) {
        $html = '';
        $data = new Spreadsheet_Excel_Reader();
        $archivo = $datos["ruta"];
        $error = $data->read($archivo);

        if ($error["error"] == '') {
            $this->CroncargaModel->cargaDatosCartera($datos, $data->sheets[0]);
        } else {
            exit("no ingreso por errores en la libreria");
        }
    }
}
