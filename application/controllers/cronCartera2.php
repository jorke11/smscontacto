<?php

class CronCartera2 extends MY_Controller {

    public $rutaftp;
    public $ci;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->load->model("CroncarteraModel");
        $this->ci = &get_instance();
        //        $this->rutaftp = '/home/pruebasftp/';
//        $this->rutaftp = '/home/autonatura/';
        $this->rutaftp = '/var/www/html/smscontacto/autonatura/';
    }

    public function index() {
        
        $ci = & get_instance();
        $ruta = $this->rutaftp . "cartera";
//        $ruta = "/home/autonatura/archivos";
        
        $lista = $this->Directorios($ruta);
        
        if (count($lista) == 1) {
            $this->procesaArchivo($lista[0]);
        } else {
            $mensaje = (count($lista) > 1) ? 'Carpeta contiene mas de un archivo' : "ERROR carpeta vacia";
            $in["nombre"] = $mensaje;
            $in["ruta"] = "cartera";
            $in["idusuario"] = -1;
            $in["fecha"] = date("Y-m-d H:i:s");
            $this->CroncarteraModel->insertar("archivos", $in);
            print_r($in);
        }
    }

    public function procesaArchivo($datos) {
        $this->CroncarteraModel->cargaDatosCartera($datos);
    }

}
