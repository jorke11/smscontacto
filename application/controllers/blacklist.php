<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blacklist extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        $this->load->view("blacklist/index");
    }

    public function cargaTabla() {
        $campos = "id,numero,motivo,CASE WHEN (estado=1) THEN 'Activo' ELSE 'Inactivo' END";
        $datos = $this->AdministracionModel->buscar("blacklist", $campos);

        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function obtieneBlacklistId($idext = null) {
        $data = $this->input->post("id");
        $data = (isset($data) & $data != '') ? $data : $idext;
        $where = "id=" . $data;
        $response = $this->AdministracionModel->buscar("blacklist", '*', $where, 'row');
        echo json_encode($response);
    }

    public function gestion() {
        $data = $this->input->post();
        $id = $data["id"];
        $data["estado"] = (isset($data["estado"])) ? 1 : 0;
        unset($data["id"]);
        if ($id == '') {
            $ok = $this->AdministracionModel->insertar("blacklist", $data);
            $this->obtieneBlacklistId($ok);
        } else {
            $this->AdministracionModel->update("blacklist", $id, $data);
            $this->obtieneBlacklistId($id);
        }
    }

}
