<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    private $gerencias;

    public function __construct() {
        parent::__construct();
        $this->load->model("LoginModel");
    }

    public function index() {       
        $this->load->view('login');
    }

    public function valida() {
        $gerencia = '';
        $sector = '';
        $data = $this->input->post();
        $where = "usuario='".strtolower($data["usuario"])."' and clave='" . base64_encode($data["clave"]) . "' AND estado=1";
        $campos = "id,usuario,clave,idperfil,idjerarquia,idsector,(coalesce(cupo,0)+ coalesce(adicion,0)-(coalesce(enviados,0)+coalesce(pendientes,0) )) cupo";
        $datos = $this->LoginModel->buscar("usuarios", $campos, $where, 'row');

        if (count($datos) > 0) {


            $session["cupo"] = $datos->cupo;
            $session["idusuario"] = $datos->id;
            $session["usuario"] = $datos->usuario;

            $session["idperfil"] = $datos->idperfil;
            $session["idsector"] = $datos->idsector;

            if (!empty($datos->idjerarquia) && $datos->idjerarquia != 0) {
                $session["idgerencia"] = $datos->idjerarquia;
                $gerencia = " codigo_comercial='" . $datos->idjerarquia . "'";
            }

            if (isset($datos->idsector) && $datos->idsector != 0) {

                $or = ($gerencia == '') ? '' : ' AND ';
                $session["idsector"] = $datos->idsector;
                $sector = $or." codigo_sector='" . $datos->idsector . "'";
            }

            $contactos = $this->LoginModel->buscar("datos", "id,nombre,celular", $gerencia . $sector.' AND estado=1 order by 2');
            $join = " JOIN grupos ON CAST(grupos.codigo as INT)= datos.ciclo ";
            $grupos = $this->LoginModel->buscar("datos" . $join, " datos.ciclo,count(datos.id) cantidad,grupos.nombre ", $gerencia . $sector . "group by ciclo,grupos.nombre order by 1");

            $session["contactos"] = $contactos;
            $session["grupos"] = $grupos;
            $this->session->set_userdata($session);
						
            redirect(base_url() . "inicio");
        } else {

            $this->session->set_flashdata('error', 'Datos Incorrectos!');
            redirect(base_url() . "login");
        }
    }

}
