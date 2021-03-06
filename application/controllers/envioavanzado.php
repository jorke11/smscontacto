<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EnvioAvanzado extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("reader");
        $this->load->model("AdministracionModel");
    }

    public function index() {
        $this->load->view("envio/avanzado");
    }

    public function insertaMensaje() {
        $cont = 0;
        $data = $this->input->post();

        $numeros = array();


        if (isset($data["destinos"])) {
            $data["destinatarios"] = array_filter(explode(",", $data["destinos"]));
        } else {
            $data["destinatarios"] = array();
        }
//        $data["destinarios"] = (isset($data["destinos"])) ? $this->divideNumero($data["destinos"]["numeros"]) : array();

        if (isset($data["grupos"]) && !empty($data["grupos"]) || $data["grupos"] == 0) {
            $in = $data["grupos"];
            unset($data["grupos"]);
            $where = "ciclo in($in) AND codigo_sector='" . $this->session->userdata("idsector") . "'";
            $datos = $this->AdministracionModel->buscar("datos", 'celular', $where);
            $numeros = $this->extraeNumeros($datos);
        }

        $data["fechaenvio"] = (isset($data["fechaenvio"]) && !empty($data["fechaenvio"])) ? $data["fechaenvio"] : date("Y-m-d H:i:s");
        $numeros = array_merge($numeros, $data["destinatarios"]);
        unset($data["destinarios"]);
        unset($data["destinos"]);

        $idusuario = $this->session->userdata("idusuario");
        $cupo = $this->SaldoUsuario($idusuario);

        if ($cupo["consumousuario"]->cupo >= count($numeros)) {
            $this->controlEnvio($numeros, $data);
        } else {
            echo json_encode(array("error" => 'Usuario sin cupo disponible!'));
        }
    }

    function insertaMensajeArchivo() {
        $data = $this->input->post();
        $where = "idbase=" . $data["idbase"];
        $datos = $this->AdministracionModel->buscar("registros", 'id,mensaje,fechaenvio', $where);

        $idusuario = $this->session->userdata("idusuario");
        $idsector = $this->session->userdata("idsector");
        $idperfil = $this->session->userdata("idperfil");
        $cupo = $this->SaldoUsuario($idusuario);
        if ($cupo["consumousuario"]->cupo >= count($datos)) {

            $param["estado"] = ($idperfil == "4") ? 6 : 2;
            $param["idcontacto"] = $idusuario;
            $where = "codigo='" . $idsector . "'";
            $nota = $this->AdministracionModel->buscar("jerarquias", 'nombre', $where, 'row');
            $nota = (COUNT($nota) > 0) ? $nota->nombre : 'Usuario sin sector asignado';
            $param["nota"] = $nota;

            foreach ($datos as $value) {
                $param["fechaenvio"] = ($value["fechaenvio"] == '') ? date("Y-m-d H:i:s") : $value["fechaenvio"];
                $param["mensaje"] = ($value["mensaje"] == '') ? $data["mensaje"] : $value["mensaje"];
                $param["mensaje"] = trim($param["mensaje"]);
                $this->AdministracionModel->update("registros", $value["id"], $param);
            }
        }

        $campos = "coalesce(pendientes,0) pendientes";
        $user = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $idusuario, 'row');
        $where = "idbase=" . $data["idbase"] . " and estado='" . $param["estado"] . "'";

        $inser = $this->AdministracionModel->buscar("registros", 'count(*) insertados', $where, 'row');
        $respuesta["registros"] = (count($inser) > 0) ? $inser->insertados : 0;
        $where = "idbase=" . $data["idbase"];
        $error = $this->AdministracionModel->buscar("errores", 'count(*) errores', $where, 'row');
        $respuesta["errores"] = $error->errores;
        $respuesta["idbase"] = $data["idbase"];
        $this->AdministracionModel->update("usuarios", $idusuario, array("pendientes" => $user->pendientes + $inser->insertados));

        echo json_encode($respuesta);
    }

    function insertarMensajeExcel() {

        $post = $this->input->post();
        $nombreArchivo = str_replace(" ", "_", $_FILES["archivo"]["name"]);
        $archivo = $_FILES["archivo"]["tmp_name"];
        $datos = new Spreadsheet_Excel_Reader();
        $error = $datos->read($archivo);
        $ruta = "/var/www/html/smscontacto/tmp/envios/" . date("Y-m-d") . "/";
//        $ruta = $_SERVER['DOCUMENT_ROOT'] . "smscontacto/tmp/" . date("Y-m-d") . "/envios/";

        $ruta = $this->crearRutaCarpeta($ruta, $nombreArchivo);
        $config['upload_path'] = $ruta;
        $config['allowed_types'] = '*';
        $config['file_name'] = $nombreArchivo;
        $this->load->library('upload', $config);
        $respuesta = array();

        if (!$this->upload->do_upload('archivo')) {
            print_r($this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
            $respuesta["ruta"] = $data["upload_data"]["full_path"];
            $respuesta["size"] = $data["upload_data"]["file_size"];
        }
        $arc["nombre"] = $nombreArchivo;
        $arc["fecha"] = date("Y-m-d H:i:s");
        $arc["ruta"] = $ruta . $nombreArchivo;
        $arc["idusuario"] = $this->session->userdata("idusuario");
        $arc["registros"] = count($datos->sheets[0]["cells"]);
        $ok = $this->AdministracionModel->insertar("archivos", $arc);

        $return["idarchivo"] = $ok;
        echo json_encode($return);
    }

    public function grabaDatos($idext = NULL) {

        $idusuario = $this->session->userdata("idusuario");
        $idsector = $this->session->userdata("idsector");
        $idperfil = $this->session->userdata("idperfil");

        $data = ($idext == NULL) ? $this->input->post() : $idext;
        /**
         * Se instancia objeto de la clase para leer el archivo excel
         */
        $where = "id=" . $data["idarchivo"];

        $archivo = $this->AdministracionModel->buscar("archivos", 'ruta', $where, 'row');

        $con = 0;
        $datos = new Spreadsheet_Excel_Reader();
        $error = $datos->read($archivo->ruta);
        $arreglo = array();
        $cantidad = count($datos->sheets[0]["cells"]);
        $cupo = $this->consultaSaldoCon();

        if ($cupo >= $cantidad) {

            $data["idcontacto"] = $idusuario;
            $data["idsector"] = $idsector;
            $estado = ($idperfil == '4') ? 6 : 4;
            $base["idusuario"] = $idusuario;
            $base["nombre"] = "web_" . date("Y-m-d H:i:s");
            $base["fecha"] = date("Y-m-d H:i:s");
            $base["ip"] = $_SERVER["REMOTE_ADDR"];
            $base["idarchivo"] = $data["idarchivo"];
            $idbase = $this->AdministracionModel->insertar('bases', $base);

            $arreglo["idbase"] = $idbase;
            $cont = 0;
            foreach ($datos->sheets[0]['cells'] as $i => $value) {
                if ($i > 1) {
                    $blacklist = $this->AdministracionModel->buscar("blacklist", 'id', "numero='{$value[1]
                            }'");

                    if (!isset($blacklist->id)) {

                        $valido = $this->validaNumero($value[1]);

                        if ($valido != FALSE && is_array($valido)) {

                            $arreglo["numero"] = $valido["numero"];
                            $arreglo["estado"] = $estado;
                            $arreglo["fecha"] = date("Y-m-d H:i:s");
                            $arreglo["orden"] = $con;
                            $arreglo["idcarrier"] = $valido["idcarrier"];

                            $mensaje = $this->LimpiaMensaje($value[2]);
                            $anterior = 0;
                            $largo = 0;

                            if (strlen($mensaje) >= 160) {
                                $tam = ceil(strlen($mensaje) / 160);
                                $sms = array();
                                for ($i = 1; $i <= $tam; ++$i) {
                                    $largo = $i * 160;
                                    $mensaje2 = substr($mensaje, $anterior, 160);
                                    $arreglo["mensaje"] = $mensaje2;
                                    $idinsert = $this->AdministracionModel->insertar("registros", $arreglo);
                                    $anterior = $largo;
                                    if (is_numeric($idinsert)) {
                                        ++$cont;
                                    }
                                }
                            } else {
                                $arreglo["mensaje"] = $mensaje;
                                $idinsert = $this->AdministracionModel->insertar("registros", $arreglo);
                                if (is_numeric($idinsert)) {
                                    ++$cont;
                                }
                            }
                        } else {
                            $error["error"] = $valido;
                            $error["idbase"] = $idbase;
                            $error["fecha"] = date("Y-m-d H:i:s");
                            $error["numero"] = $value[1];
                            $error["idcontacto"] = $idusuario;
                            $error["mensaje"] = $this->LimpiaMensaje(utf8_encode($value[2]));
                            $this->AdministracionModel->insertar("errores", $error);
                        }
                    } else {
                        $error["idbase"] = $idbase;
                        $error["idcontacto"] = $idusuario;
                        $error["error"] = "Black List";
                        $error["idgerencia"] = $gerencia->id;
                        $error["fecha"] = date("Y-m-d H:i:s");
                        $error["linea"] = $i;
                        $error["numero"] = $value[7];
                        $error["mensaje"] = $this->LimpiaMensaje(utf8_encode($value[2]));
                        $this->AdministracionModel->insertar("errores", $error);
                    }
                }
            }

            $inser = $this->AdministracionModel->buscar("registros", 'count(*) insertados', 'idbase=' . $idbase, 'row');
            $error = $this->AdministracionModel->buscar("errores", 'count(*) errores', 'idbase=' . $idbase, 'row');
            $respuesta["registros"] = $inser->insertados;
            $respuesta["errores"] = $error->errores;
            $respuesta["idbase"] = $idbase;
            echo json_encode($respuesta);
        } else {
            echo json_encode(array("error" => 'Usuario sin cupo sucifiente!'));
        }
    }

    public function extraeNumeros($arreglo) {
        $respuesta = array();
        foreach ($arreglo as $value) {
            $respuesta[] = $value["celular"];
        }
        return $respuesta;
    }

    public function extraeDestinatarios($arreglo) {
        $respuesta = array();
        if (count($arreglo) > 0) {
            foreach ($arreglo as $value) {
                $respuesta[] = $value;
            }
        }

        return $respuesta;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */