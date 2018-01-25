<?php

class CarteraManual extends MY_Controller {

    public $rutaftp;
    public $ci;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->load->model("CroncarteraModel");
        $this->ci = &get_instance();
        //        $this->rutaftp = '/home/pruebasftp/';
//        $this->rutaftp = '/home/autonatura/';
        $this->rutaftp = '/var/www/html/prbsmscontacto/autonatura/';
    }

    public function index() {

        $cron = $this->CroncarteraModel->buscar("crones", "*", "nombre='cartera' and estado=0 and consulta=1", 'row');

        if ($cron) {

            $this->CroncarteraModel->update("crones", $cron->id, array("estado" => 0, "unidad" => 0, "ejecutado" => date("Y-m-d H:i")));
            $ci = & get_instance();
            $ruta = $this->rutaftp . "cartera";
//        $ruta = "/home/autonatura/archivos";

            $lista = $this->Directorios($ruta);

            if (count($lista) == 1) {
                $this->procesaArchivo($lista[0], $cron->id);
            } else {
                $mensaje = (count($lista) > 1) ? 'Carpeta contiene mas de un archivo' : "ERROR carpeta vacia";
                $in["nombre"] = $mensaje;
                $in["ruta"] = "cartera";
                $in["idusuario"] = -1;
                $in["fecha"] = date("Y-m-d H:i:s");
                $this->CroncarteraModel->insertar("archivos", $in);
                print_r($in);
            }
        } else {
            echo "Cron en proceso!";
        }
    }

    public function setprocess() {
        $cron = $this->CroncarteraModel->buscar("crones", "*", "nombre='cartera' and estado=0", 'row');

        $sql = "TRUNCATE cartera RESTART IDENTITY CASCADE;";
        $this->ci->db->query($sql);

        if (count($cron) > 0) {
            $this->CroncarteraModel->update("crones", $cron->id, array("consulta" => 1, "unidad" => 0));
        }

        echo json_encode(array("status" => true));
    }

    public function getprocess() {
        $row = $this->CroncarteraModel->buscar("cartera", "count(*) procesado", null, 'row');
        $total = $this->CroncarteraModel->buscar("crones", "unidad,estado", "nombre='cartera'", 'row');
        $res = array();

        if ($total->estado == 0) {
            $res = array("status" => true, "quantity" => $total->unidad);
        } else {
            $res = array("status" => false, "quantity" => $row->procesado);
        }

        echo json_encode($res);
    }

    public function procesaArchivo($datos, $cron_id) {
        $this->CroncarteraModel->cargaDatosCarteraCron($datos, $cron_id);
    }

}
