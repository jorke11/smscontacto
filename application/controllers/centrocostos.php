<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CentroCostos extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = '';
    }

    public function index() {

        $this->load->view("administracion/centrocosto");
    }

    public function cargaTabla() {
        $where = 'estado=1';
        $datos = $this->AdministracionModel->buscar("centrocosto", "id,initcap(nombre) nombre,codigo", $where);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function gestion() {
        $data = $this->input->post();
        $id = $data["id"];
        unset($data["id"]);
        if ($id == '') {
            $data["estado"] = 1;
            $ok = $this->AdministracionModel->insertar("centrocosto", $data);
            $this->obtieneCentroId($ok);
        } else {
            $data["estado"] = ($data["estado"] == 'on') ? 1 : 0;
            $this->AdministracionModel->update("centrocosto", $id, $data);
            $this->obtieneCentroId($id);
        }
    }

    public function obtieneCentroId($idext = null) {
        $data = $this->input->post("id");
        $data = (isset($data) & $data != '') ? $data : $idext;
        $where = "id=" . $data;
        $response = $this->AdministracionModel->buscar("centrocosto", '*', $where, "row");
        echo json_encode($response);
    }

    public function borrar() {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $ok = $this->AdministracionModel->update("centrocosto", $data["id"], array("estado" => 0));
            echo ($ok == 'ok') ? 'ok' : $ok;
        }
    }

}
