<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cartera extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        $this->load->view("envio/cartera/index");
    }

    public function datosTabla() {
        $data = $this->input->post();

        $order = ' ORDER BY 1 DESC';
        if ($data["order"] != '' && isset($data["order"])) {
            $order = ' ORDER BY ' . $data["order"];
        }

        $where = "sector='" . $this->session->userdata("idsector") . "' " . $order;

        $datos = $this->AdministracionModel->buscar("cartera", 'diamora,nombre,celular,id', $where);
        echo json_encode($datos);
    }

    public function envioCartera() {
        $data = $this->input->post();

        $idusuario = $this->session->userdata("idusuario");
        $cupo = $this->SaldoUsuario($idusuario);

        if ($cupo["consumousuario"]->cupo >= count($data["cartera"])) {
            $this->controlEnvio($data["cartera"], $data);
        } else {
            echo json_encode(array("error" => 'Usuario sin cupo disponible'));
        }
    }

    public function datosCartera() {
        $where = "sector='" . $this->session->userdata("idsector") . "'";
        $datos = $this->AdministracionModel->buscar("cartera", 'diamora,nombre,celular,id', $where);
        echo json_encode($datos);
    }

}
