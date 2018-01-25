<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data["contactos"] = $this->contactos;
        $data["grupos"] = $this->grupos;

        $menu = $this->AdministracionModel->buscar("perfiles", '*', 'id=' . $this->session->userdata("idperfil"), 'row');
        $data["menu"] = $this->cargaMenu($menu->menu);
        $this->load->view('inicio', $data);
    }

    public function extraeNumeros($arreglo) {
        $respuesta = '';
        foreach ($arreglo as $value) {
            $respuesta[] = $value["celular"];
        }
        return $respuesta;
    }

    public function envioMovil() {
        $cont = 0;
        $numeros = array();
        $data = $this->input->post();
        $data["manuales"] = (isset($data["manuales"])) ? $this->divideNumero($data["manuales"]) : array();
        $data["cartera"] = (isset($data["cartera"])) ? $this->divideNumero($data["cartera"]) : array();

        $gerencia = $this->session->userdata("idgerencia");
        $sector = $this->session->userdata("idsector");

        $data["idcontacto"] = $this->session->userdata("idusuario");

        if (isset($data["grupos"]) && $data["grupos"] != '') {
            $where = "ciclo in(" . $data["grupos"] . ") and codigo_comercial='" . $gerencia . "' and codigo_sector='" . $sector . "'";
            $datos = $this->AdministracionModel->buscar("datos", 'celular', $where);
            $numeros = $this->extraeNumeros($datos);
        }


        if (!empty($data["destino"])) {
            $numeros = array_merge($numeros, explode(",", $data["destino"]));
        }

        if (!empty($data["manuales"])) {
            $numeros = array_merge($numeros, $data["manuales"]);
        }

        if (!empty($data["cartera"])) {
            $numeros = array_merge($numeros, $data["cartera"]);
        }


        unset($data["contactos"]);
        unset($data["destino"]);
        unset($data["grupos"]);

        $numeros = array_filter($numeros);

        $idusuario = $this->session->userdata("idusuario");
        $cupo = $this->consultaSaldo(TRUE);
        $data["fechaenvio"] = ($data["fechaenvio"] == '') ? date("Y-m-d H:i:s") : $data["fechaenvio"];
        if ($cupo->cupo >= $data["cantmensajes"]) {
            $this->controlEnvio($numeros, $data, 'movil');
        } else {
            echo json_encode(array("error" => 'Usuario sin cupo disponible'));
        }
    }

    public function cargaMenu($ruta) {

        $ruta = base_url() . $ruta;

        switch ($_SESSION["idperfil"]) {
            case 1: {
                    $ruta = "/var/www/html/prbsmscontacto/public/menu/gerencia.ini";
                    break;
                }

            case 2: {
                    $ruta = "/var/www/html/prbsmscontacto/public/menu/sector.ini";
                    break;
                }
            case 3: {
                    $ruta = "/var/www/html/prbsmscontacto/public/menu/administrador.ini";
                    break;
                }
            case 4: {
                    $ruta = "/var/www/html/prbsmscontacto/public/menu/administrador.ini";
                    break;
                }
        }

        //return (parse_ini_file($ruta, true));
        return (parse_ini_file($ruta, true));
    }

    public function cerrarSession() {
        $this->session->sess_destroy();
        redirect(base_url() . "login");
    }

    public function validaSession() {
        $idusuario = $this->session->userdata("idusuario");
        if ($idusuario == FALSE) {
            echo json_encode(array("session" => 'Se perdio la session'));
        } else {
            echo json_encode(array("validado" => 'ok'));
        }
    }

    public function consultaSaldo($ext = NULL) {
        $idusuario = $this->session->userdata("idusuario");
        $idusuario = (isset($idusuario)) ? $idusuario : '';
        if ($idusuario != '') {
            $campos = "CASE WHEN (coalesce(cupo,0) + coalesce(adicion,0)- (coalesce(enviados,0) + coalesce(pendientes,0)))<0 THEN 0";
            $campos .= " ELSE (coalesce(cupo,0) + coalesce(adicion,0)- (coalesce(enviados,0) + coalesce(pendientes,0))) END cupo";
            $datos = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $this->session->userdata("idusuario"), 'row');
            if ($ext == TRUE) {
                return $datos;
            } else {
                echo json_encode($datos);
            }
        }
    }

}
