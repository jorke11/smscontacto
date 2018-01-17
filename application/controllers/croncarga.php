<?php

include(APPPATH . "controllers/croncreagrupos.php");

class CronCarga extends MY_Controller {

    public $rutaftp;

    public function __construct() {
        parent::__construct();
        $this->load->library("reader");
        $this->load->model("AdministracionModel");
        $this->load->model("CargarexcelModel");
        $this->load->model("CroncargaModel");
//        $this->rutaftp = '/home/pruebasftp/';
        $this->rutaftp = '/home/autonatura/';
    }

    public function index() {

        $ci = & get_instance();
        $ruta = $this->rutaftp . "archivos";
//        $ruta = "/home/autonatura/archivos";
        $lista = $this->Directorios($ruta);

        $param["estado"] = 1;
        $param["ejecutado"] = date("Y-m-d H:i:s");
        $this->AdministracionModel->update("crones", 16, $param);
        $jornada = (date("H") < 12) ? 1 : 2;

        if (count($lista) == 7) {
            $arg["titulo"] = 'Informacion archivos Completos';
            $arg["archivos"] = $lista;
            $html = $this->load->view("formatos/correos/alertas", $arg, true);
        }
        if (count($lista) < 6 && count($lista) != 0) {
            $arg["titulo"] = 'Alerta por Archivos incompletos';
            $arg["archivos"] = $lista;

            $html = $this->load->view("formatos/correos/alertas", $arg, true);
        }

        if (count($lista) > 0) {
            $sql = "TRUNCATE macros RESTART IDENTITY CASCADE;";
            $ci->db->query($sql);

            foreach ($lista as $value) {
                $this->procesaArchivo($value);
            }
        } else {
            $arg["titulo"] = '<b>Alerta por falta de Archivos</b>';
            $arg["mensajeerror"] = 'No se encontraron Archivos del ' . date("Y-m-d") . ' subidos al FTP';
            $arg["archivos"] = $lista;
            $html = $this->load->view("formatos/correos/alertas", $arg, true);
        }

        $param["estado"] = 0;
        $param["ejecutado"] = date("Y-m-d H:i:s");
        $this->AdministracionModel->update("crones", 16, $param);
        $this->enviaNotificaciones();
    }

    public function enviaNotificaciones() {
        $jornada = (date("H") < 12) ? 1 : 2;
        $campos = "arc.nombre,arc.fecha,arc.registros,arc.procesado,mot.descripcion";
        $where = 'arc.jornada=' . $jornada . " AND arc.fecha >='" . date("Y-m-d") . " 00:00'";
        $join = " LEFT JOIN motivos mot ON mot.id = arc.tipo ";

        $envios = $this->AdministracionModel->buscar("archivos arc " . $join, $campos, $where);
        if (count($envios) > 0) {
            $arg["titulo"] = '<b>Resumen Carga</b>';
            $arg["archivos"] = $envios;
            $html = $this->load->view("formatos/correos/alertas", $arg, true);
            $this->notificacion($html);
        }
    }

    public function procesaArchivo($datos) {
        $html = '';
        $data = new Spreadsheet_Excel_Reader();
//        $ruta = '/home/autonatura/archivos/' . $datos["nombre"];
//        $archivo = $ruta . 'archivos/' . $datos["nombre"];

        $archivo = $datos["ruta"];

        $error = $data->read($archivo);

        if ($error["error"] == '') {
            $res = $this->CroncargaModel->cargaDatos($datos, $data->sheets[0]);
            if ($res["error"] == true) {
                $arg["titulo"] = 'El archivo tiene vacios en departamentos y municipios';
                $arg["mensaje"] = "El archivo: " . $res["archivo"] . "<br> En la linea: " . $res["linea"];
                $html = $this->load->view("formatos/correos/notificacion", $arg, true);
//                $this->notificacion($html);
            }
        } else {
            exit("no ingreso");
        }
    }

    function grabaDatos() {

        $macros = $this->AdministracionModel->buscar("macros", '*');
        $arreglo = array();
        $codigos = array();
        if (count($macros) > 0) {
            foreach ($macros as $i => $value) {
                $blacklist = $this->AdministracionModel->buscar("blacklist", 'id', "numero = '{$value["celular"]}'");
                if (!isset($blacklist->id)) {
                    $where = "codigo_comercial = '{$value["codigo_comercial"]}'";
                    $gerencia = $this->AdministracionModel->buscar("datos", 'id', $where, 'row');
                    if (count($gerencia) > 0) {
                        if (!in_array($value["codigo_comercial"], $codigos)) {

                            $codigos[] = $value["codigo_comercial"];
                            $this->CargarexcelModel->eliminaGerenciaMacro($value);
                        }
                    } else {
                        $codigos[] = $value["codigo_comercial"];
                    }
                    $mun = $value["municipio"];
                    unset($value["observacion"]);
                    unset($value["municipio"]);
                    unset($value["departamento"]);
                    unset($value["tipo"]);
                    unset($value["id"]);
                    $value["fechacargue"] = date("Y-m-d H:i:s");
                    $value["estado"] = 1;
                    $value["poblacion"] = $mun;

                    $this->AdministracionModel->insertar("datos", $value);
                } else {
                    $error["error"] = "Black List";
                    $error["idgerencia"] = $gerencia->id;
                    $error["fecha"] = date("Y-m-d H:i:s");
                    $error["linea"] = $i;
                    $error["numero"] = $value["celular"];

                    $this->AdministracionModel->insertar("errores", $error);
                }
            }


            $obj = new CroncreaGrupos();
            $obj->crearTodo();
        } else {
            echo "sin datos";
            exit;
        }
    }

}
