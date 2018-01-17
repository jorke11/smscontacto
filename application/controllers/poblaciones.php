<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Poblaciones extends MY_Controller {

    public $idsector = '';
    public $idgerencia = '';

    public function __construct() {
        parent::__construct();
        $this->load->library("reader");
        $this->load->model("AdministracionModel");
        $this->idsector = $this->session->userdata("idsector");
        $this->idgerencia = $this->session->userdata("idgerencia");
    }

    public function index() {

        $gerencia = ($this->idgerencia != '') ? "codigo_comercial='" . $this->idgerencia . "'" : '';
        $sector = ($this->idsector != '') ? "codigo_sector='" . $this->idsector . "'" : '';

        if ($gerencia != '') {
            $sector = ' AND ' . $sector;
        }

        $where = "$gerencia $sector  AND poblacion is not null group by 1";
        $archivo["poblacion"] = $this->AdministracionModel->buscar("datos", 'poblacion', $where, 'debug  ');

        $join = " JOIN grupos ON CAST(grupos.codigo as INT)= datos.ciclo group by ciclo,grupos.nombre order by 1";
        $archivo["grupos"] = $this->AdministracionModel->buscar("datos" . $join, " datos.ciclo,count(datos.id) cantidad,grupos.nombre ");
        $this->load->view("envio/poblacion/inicio", $archivo);
    }

    public function insertaMensaje() {
        $cont = 0;
        $data = $this->input->post();
        $numeros = array();


        if (isset($data["destinarios"]) && !empty($data["destinarios"])) {
            $data["destinarios"] = explode("\n", $data["destinarios"]);
        } else {
            $data["destinarios"] = array();
        }
        $data["contactos"] = (isset($data["contactos"]) && !empty($data["contactos"])) ? $data["contactos"] : array();

//        $data["destinarios"] = (isset($data["destinos"])) ? $this->divideNumero($data["destinos"]["numeros"]) : array();
//        if (isset($data["grupos"]) && !empty($data["grupos"]) || $data["grupos"] == 0) {
//            $in = implode(',', $data["grupos"]);
//            unset($data["grupos"]);
//            $where = "ciclo in($in) AND codigo_sector='" . $this->session->userdata("idsector") . "'";
//
//            $datos = $this->AdministracionModel->buscar("datos", 'celular', $where);
//            $numeros = $this->extraeNumeros($datos);
//        }

        $data["fechaenvio"] = (isset($data["fechaenvio"]) && !empty($data["fechaenvio"])) ? $data["fechaenvio"] : date("Y-m-d H:i:s");
        $numeros = array_merge($numeros, $data["destinarios"]);
        $numeros = array_merge($numeros, $data["contactos"]);
        unset($data["destinarios"]);

        $idusuario = $this->session->userdata("idusuario");
        $cupo = $this->SaldoUsuario($idusuario);
        if ($cupo["consumousuario"]->cupo >= count($numeros)) {
            $this->controlEnvio($numeros, $data);
        } else {
            echo json_encode(array("error" => 'Usuario sin cupo disponible!'));
        }
    }

    function getContactos() {
        $datos = $this->input->post();

        $poblaciones = '';
        if (isset($datos["poblaciones"])) {
            $pob = '';
            foreach ($datos["poblaciones"] as $value) {
                $pob .= ($pob == '') ? '' : ',';
                $pob .= "'" . $value . "'";
            }



            $poblaciones = " AND  poblacion IN ($pob)";
        }

        $sin = '';
        $con = '';
        if (isset($datos["sin"]) && isset($datos["con"])) {
            $sin = '';
            $con = '';
        } else {
            if (isset($datos["sin"]) && !isset($datos["con"])) {
                $sin = (isset($datos["sin"])) ? ' AND poblacion IS NULL ' : '';
            }
            if (isset($datos["con"]) && !isset($datos["sin"])) {
                $con = (isset($datos["con"])) ? ' AND poblacion IS NOT NULL ' : '';
            }
        }

        $gerencia = ($this->idgerencia != '') ? "codigo_comercial='" . $this->idgerencia . "'" : '';
        $sector = ($this->idsector != '') ? "codigo_sector='" . $this->idsector . "'" : '';


        if ($gerencia != '') {
            $sector = ' AND ' . $sector;
        }
        $grupos = '';
        if (isset($datos["grupos"])) {

            $gru = implode(",", $datos["grupos"]);
            $grupos = " AND  ciclo IN (" . $gru . ")";
        }

        $where = "$gerencia $sector $poblaciones $grupos $sin $con AND estado=1 order by 2";
        $archivo["contactos"] = $this->AdministracionModel->buscar("datos", "id,nombre,celular", $where);
        $archivo["registros"] = count($archivo["contactos"]);
        echo json_encode($archivo);
    }

    function cargaDatos() {
        $datos = $this->input->post();

        $sector = ($this->idsector != '') ? " codigo_sector='" . $this->idsector . "'" : '';
        $comercial = ($this->idgerencia != '') ? "codigo_comercial='" . $this->idgerencia . "'" : '';

        if ($comercial != '') {
            $sector = ' AND ' . $sector;
        }

        $where = $comercial . $sector . ' AND estado=1 order by 2';
        $res["contactos"] = $this->AdministracionModel->buscar("datos", "id,nombre,celular", $where);
        $join = " JOIN grupos ON CAST(grupos.codigo as INT)= datos.ciclo ";
        $where = $comercial . $sector . "group by ciclo,grupos.nombre order by 1";
        $res["grupos"] = $this->AdministracionModel->buscar("datos" . $join, " datos.ciclo,count(datos.id) cantidad,grupos.nombre ", $where);
        echo json_encode($res);
    }

    function insertaMensajeArchivo() {
        $data = $this->input->post();
        $where = "idbase=" . $data["idbase"];
        $datos = $this->AdministracionModel->buscar("registros", 'id, mensaje, fechaenvio', $where);

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
        $user = $this->AdministracionModel->buscar("usuarios", $campos, 'id = ' . $idusuario, 'row');

        $where = "idbase=" . $data["idbase"] . " and estado='6'";
        $inser = $this->AdministracionModel->buscar("registros", 'count(*) insertados', $where, 'row');
        $respuesta["registros"] = (count($inser) > 0) ? $inser->insertados : 0;
        $where = "idbase=" . $data["idbase"];
        $error = $this->AdministracionModel->buscar("errores", 'count(*) errores', $where, 'row');
        $respuesta["errores"] = $error->errores;
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
                    $blacklist = $this->AdministracionModel->buscar("blacklist", 'id', "numero='{$value[1] }'");

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

            $inser = $this->AdministracionModel->buscar("registros", 'count(*) insertados', 'idbase = ' . $idbase, 'row');
            $error = $this->AdministracionModel->buscar("errores", 'count(*) errores', 'idbase = ' . $idbase, 'row');
            $respuesta["registros"] = $inser->insertados;
            $respuesta["errores"] = $error->errores;
            $respuesta["idbase"] = $idbase;
            echo json_encode($respuesta);
        } else {
            echo json_encode(array("error" => 'Usuario sin cupo sucifiente!

        

        

        

        '
            ));
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