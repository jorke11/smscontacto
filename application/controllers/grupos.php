<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grupos extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = "grupos";
    }

    public function index() {
        $this->load->view("grupos/index");
    }

    public function tabla() {
        $data["contactos"] = $this->contactos;
        $this->load->view("grupos/tabla", $data);
    }

    public function cargaTabla() {
        $campos = "id,finsert,nombre,CASE WHEN estado=1 THEN 'Activo' ELSE 'Inactivo' END estado";
        $datos = $this->AdministracionModel->buscar($this->tabla, $campos);

        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function obtieneGruposId($idext = null) {
        $data = $this->input->post("id");
        $data = (isset($data) & $data != '') ? $data : $idext;
        $where = "id=" . $data;
        $campos = "*";
        $response = $this->AdministracionModel->buscar($this->tabla, $campos, $where, 'row');
        echo json_encode($response);
    }

    public function gestion() {
        $data = $this->input->post();
        $id = $data["id"];
        $data["estado"] = (isset($data["estado"])) ? 1 : 0;
        unset($data["id"]);
        if ($id == '') {
            $data["finsert"] = date("Y-m-d H:i:s");
            $gruponuevo = $data["gruponuevo"];
            unset($data["gruponuevo"]);
            $ok = $this->AdministracionModel->insertar("grupos", $data);
//            $ok = $this->AdministracionModel->insertar($this->tabla, $data);
            $this->obtieneGruposId($ok);
        } else {
            echo "editar";
            exit;
        }
    }

}
