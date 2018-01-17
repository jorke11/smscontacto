<?php

class Informes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("InformesModel");
    }

    public function tablames() {
        $data = $this->input->post();
        $datos = $this->InformesModel->informeMes($data);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function tabladia() {
        $data = $this->input->post();
        $datos = $this->InformesModel->informeDia($data);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function tablaoperador() {
        $data = $this->input->post();
        $datos = $this->InformesModel->informeOperador($data);

        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function tablaconsumo() {
        $data = $this->input->post();
        $datos = $this->InformesModel->informeConsumo($data);

        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function dinamico() {
        $data["operadores"] = $this->AdministracionModel->buscar("carrier", "codigo,nombre");
        $this->load->view("informes/dinamico", $data);
    }

    public function tabladinamico() {
        $where = " estado='1' group by consolidado,estado";
        $datos = $this->AdministracionModel->buscar("registros", "estado,to_char(fechaenvio, 'yyyy-mm-dd') consolidado,count(id) cantidad", $where);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

}
