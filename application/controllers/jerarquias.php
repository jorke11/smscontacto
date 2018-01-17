<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jerarquias extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = 'jerarquias';
    }

    public function index() {
        $data["gerencias"] = $this->AdministracionModel->buscar("jerarquias", 'codigo,nombre', "tipo=1 and nombre!=''");
        $datos = $this->AdministracionModel->buscar("perfiles", 'id,perfil', 'id!=3');
        $data["perfiles"] = $datos;
        $this->load->view("administracion/gerencia", $data);
    }

    public function cargaTabla() {
        $campos = "je.id,je.nombre,je.codigo,per.perfil,cupo,CASE WHEN (je.estado=1) THEN 'Activo' ELSE 'Inactivo' END";
        $join = " LEFT JOIN perfiles per ON per.id= je.tipo";
        $datos = $this->AdministracionModel->buscar("jerarquias je" . $join, $campos, "je.estado=1 and nombre!='' ");
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function obtieneJearaquiasId($idext = null) {
        $data = $this->input->post("id");
        $data = (isset($data) & $data != '') ? $data : $idext;
        $where = "id=" . $data;
        $response = $this->AdministracionModel->buscar("jerarquias", '*', $where, 'row');
        echo json_encode($response);
    }

    public function gestion() {
        $data = $this->input->post();

        $id = $data["id"];
        $data["estado"] = (isset($data["estado"])) ? 1 : 0;
        unset($data["id"]);
        if ($id == '') {
            $ok = $this->AdministracionModel->insertar("jerarquias", $data);

            $this->obtieneJearaquiasId($ok);
        } else {
            $data = $this->asignaNull($data);
            $ok = $this->AdministracionModel->update("jerarquias", $id, $data);
            $this->obtieneJearaquiasId($ok);
        }
    }

    public function cargaGerencias() {
        $data = $this->AdministracionModel->buscar("jerarquias", 'codigo,nombre', 'tipo=1 and estado=1');
        echo json_encode($data);
    }

    public function validaCodigo() {
        $data = $this->input->post();
        $datos = $this->AdministracionModel->buscar("jerarquias", 'codigo', "codigo ilike '" . $data["buscar"] . "' and estado=1");
        $res["respuesta"] = (count($datos) > 0) ? FALSE : TRUE;
        echo json_encode($res);
    }

    public function borrar() {
        $data = $this->input->post();
        $ok = $this->AdministracionModel->delete("jerarquias", $data["id"]);
        echo ($ok) ? true : FALSE;
    }

}
