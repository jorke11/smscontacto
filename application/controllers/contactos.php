<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contactos extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = '';
    }

    public function index() {
        $data["gerencias"] = $this->AdministracionModel->buscar("jerarquias", 'codigo,nombre', "tipo=1 and nombre!='' and estado=1");
        $data["sectores"] = $this->AdministracionModel->buscar("jerarquias", 'codigo,nombre', "tipo=2 and nombre!='' and estado=1");
        $datos = $this->AdministracionModel->buscar("grupos order by id", "id,nombre");
        $data["grupos"] = $datos;
        $this->load->view("administracion/contactos", $data);
    }

//    public function cargaTabla() {
//        $idsector = '';
//        $idgerencia = '';
//
//        if ($this->session->userdata("idperfil") != 3) {
//            if ($this->session->userdata("idgerencia")) {
//                $idgerencia = " codigo_comercial='" . $this->session->userdata("idgerencia") . "'";
//            }
//
//            if ($this->session->userdata("idsector")) {
//                $and = ($idgerencia == '') ? '' : ' AND ';
//                $idsector = $and . " codigo_sector='" . $this->session->userdata("idsector") . "'";
//            }
//        }
//
//        $campos = "datos.id,datos.nombre,datos.celular,grupos.nombre grupo";
//        $join = " JOIN grupos ON CAST(grupos.codigo as INTEGER)=datos.ciclo";
//        $and2 = ($idgerencia != '') ? ' AND ' : '';
//        $datos = $this->AdministracionModel->buscar("datos " . $join, $campos, ' datos.estado=1 ' . $and2 . $idgerencia . $idsector);
//        $datos = $this->datatable($datos);
//        echo json_encode($datos);
//    }
    function cargaTabla($parametro = NULL) {

        $idsector = '';
        $idgerencia = '';

        if ($this->session->userdata("idperfil") != 3) {
            if ($this->session->userdata("idgerencia")) {
                $idgerencia = " codigo_comercial='" . $this->session->userdata("idgerencia") . "'";
            }

            if ($this->session->userdata("idsector")) {
                $and = ($idgerencia == '') ? '' : ' AND ';
                $idsector = $and . " codigo_sector='" . $this->session->userdata("idsector") . "'";
            }
        }

        $join = " JOIN grupos ON CAST(grupos.codigo as INTEGER)=datos.ciclo ";

        $columnas = "datos.id,datos.nombre,datos.celular,grupos.nombre grupo";

        $columnasfor = "id,nombre,celular,grupo";

        $and2 = ($idgerencia != '') ? ' AND ' : '';

        $columnaslike = "datos.nombre, datos.celular, grupos.nombre";
        $where = $and2 . $idgerencia . $idsector;
        $datos = $this->AdministracionModel->dataTable('datos', $join, $columnaslike, $columnas, $columnasfor, $where);
        echo json_encode($datos);
    }

    public function gestion() {
        $data = $this->input->post();
        $id = $data["id"];
        unset($data["id"]);
        $idgerencia = $data["idgerencia"];
        $idsector = $data["idsector"];
        unset($data["idgerencia"]);
        unset($data["idsector"]);

        $gerencia = $this->AdministracionModel->buscar("jerarquias", 'nombre,codigo', "codigo='" . $idgerencia . "'", 'row');
        $sector = $this->AdministracionModel->buscar("jerarquias", 'nombre,codigo', "codigo='" . $idsector . "'", 'row');

        $data["codigo_comercial"] = $gerencia->codigo;
        $data["gerencia_comercial"] = $gerencia->nombre;
        $data["codigo_sector"] = $sector->codigo;
        $data["sectores"] = $sector->nombre;

        if ($id == '') {

            $nombre = $this->AdministracionModel->buscar("datos", 'id', "nombre='" . $data["nombre"] . "' and estado=1", 'row');
            $numero = $this->AdministracionModel->buscar("datos", 'id', "celular='" . $data["celular"] . "' and estado=1", 'row');


            if (!empty($nombre) || !empty($numero)) {
                $respuesta["error"] = 'Nombre de contacto o Número de Celular Ya existen';
                echo json_encode($respuesta);
            } else {


                $data["estado"] = 1;
                $data["fechacargue"] = date("Y-m-d H:i:s");
                $data["confirmado"] = 'Si';

                $ok = $this->AdministracionModel->insertar("datos", $data);
                $this->obtieneContactoId($ok);
            }
        } else {
            $this->AdministracionModel->update("datos", $id, $data);
            $this->obtieneContactoId($id);
        }
    }

    function buscaSector() {
        $data = $this->input->post();
        $where = "idpadre='" . $data["codigo"] . "'";
        $response = $this->AdministracionModel->buscar("jerarquias", 'nombre,codigo', $where);
        echo json_encode($response);
    }

    public function obtieneContactoId($idext = null) {
        $data = $this->input->post("id");
        $data = (isset($data) & $data != '') ? $data : $idext;
        $where = "id=" . $data;
        $campos = "id,nombre,ciclo,codigo_comercial idgerencia,codigo_sector idsector,celular";
        $response = $this->AdministracionModel->buscar("datos", $campos, $where, 'row');
        echo json_encode($response);
    }

    public function borrar() {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $ok = $this->AdministracionModel->delete("datos", $data["id"]);
            echo ($ok) ? true : FALSE;
        }
    }

}
