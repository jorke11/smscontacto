<?php

class Informes extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function mes() {
        $this->load->view("informes/mes");
    }

    public function tablames() {
        $where=" estado='1' AND fechaenvio BETWEEN '" . date("Y-m") . "-01' AND '" . date("Y-m-d") . " 23:59:59'";
        $datos = $this->AdministracionModel->buscar("registros", "id,numero,mensaje,fechaenvio", $where);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }
    
    public function dia() {
        $this->load->view("informes/dia");
    }
    
    public function tabladia() {
        $where=" estado='1' AND fechaenvio BETWEEN '" . date("Y-m-d") . " 00:00:00' AND '" . date("Y-m-d") . " 23:59:59'";
        $datos = $this->AdministracionModel->buscar("registros", "id,numero,mensaje,fechaenvio", $where);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }
    public function consolidado() {
        $this->load->view("informes/consolidado");
    }
    
    public function tablaconsolidado() {
        $where=" estado='1' group by consolidado,estado";
        $datos = $this->AdministracionModel->buscar("registros", "estado,to_char(fechaenvio, 'yyyy-mm-dd') consolidado,count(id) cantidad", $where);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

}
