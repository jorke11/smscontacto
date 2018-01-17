<?php

include(APPPATH . "controllers/croncreagrupos.php");

class CronPoblaciones extends MY_Controller {

    public $rutaftp;
    public $ci;

    public function __construct() {
        parent::__construct();
        $this->load->library("reader");
        $this->load->model("AdministracionModel");
        $this->load->model("CargarexcelModel");
        $this->load->model("CroncargaModel");
//        $this->rutaftp = '/home/pruebasftp/';
        $this->rutaftp = '/home/autonatura/';

        $this->ci = &get_instance();
//        $this->rutaftp = '/home/autonatura/';
    }

//    public function index() {
//        $ci = & get_instance();
//        $ruta = $this->rutaftp . "archivos";
////        $ruta = "/home/autonatura/archivos";
//        $lista = $this->Directorios($ruta);
//
//        $jornada = (date("H") < 12) ? 1 : 2;
//
//        if (count($lista) > 0) {
//            $sql = "TRUNCATE poblaciones RESTART IDENTITY CASCADE;";
//            $ci->db->query($sql);
//
//            foreach ($lista as $value) {
//                $this->procesaArchivo($value);
//            }
//        }
//    }


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
            $sql = "TRUNCATE poblaciones RESTART IDENTITY CASCADE;";
            $ci->db->query($sql);
		
            foreach ($lista as $value) {
                $this->procesaArchivo($value);
            }
        } else {
            $arg["titulo"] = '<b>Alerta por falta de Archivos</b>';
            $arg["mensajeerror"] = 'No se encontraron Archivos del ' . date("Y-m-d") . ' subidos al FTP';
            $arg["archivos"] = $lista;
            $html = $this->load->view("formatos/correos/alertas", $arg, true);
		//debugger($html);
	     $this->notificacion($html);
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
        $archivo = $datos["ruta"];
	if(file_exists($datos["ruta"])){
        	$error = $data->read($archivo);
		
	        if ($error["error"] == '') {
        	    $this->CroncargaModel->cargaDatosPoblaciones($datos, $data->sheets[0]);
	        } else {
		    var_dump($error);
	            exit("no ingreso");
        	}
	}else{
		echo $datos["ruta"]." no Existe";
	}
    }

    function grabaDatos() {

        $macros = $this->AdministracionModel->buscar("poblaciones", '*');
        $arreglo = array();
        $codigos = array();
        if (count($macros) > 0) {
            foreach ($macros as $i => $value) {
                $blacklist = $this->AdministracionModel->buscar("blacklist", 'id', "numero = '{$value["celular"]}'");
                if (!isset($blacklist->id)) {
                    $where = "codigo_comercial = '{$value["codigo_comercial"]}'";
                    $gerencia = $this->AdministracionModel->buscar("datos", 'id', $where, 'row');
                    if (count($gerencia) > 0) {
                        if (!in_array(trim($value["codigo_comercial"]), $codigos)) {

                            $codigos[] = $value["codigo_comercial"];
                            $this->CargarexcelModel->eliminaGerenciaMacro($value);
                        }
                    } else {
                        $codigos[] = trim($value["codigo_comercial"]);
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

                    $where = "celular='" . trim($value["celular"]) . "'";

                    $valida = $this->AdministracionModel->buscar("datos", 'id,celular', $where, 'row');

                    if (count($valida) > 0) {
                        $this->AdministracionModel->update("datos", $valida->id, $value);
                        $salida = 'edita: ';
                    } else {
                        $num = $this->validaNumero($value["celular"]);
                        if ($num == FALSE) {
                            $salida = 'error: ';
                            $error["error"] = "cronPoblaciones numero: " . $value["celular"];
                            $error["idgerencia"] = $gerencia->id;
                            $error["fecha"] = date("Y-m-d H:i:s");
                            $error["linea"] = $i;
                            $error["numero"] = $value["celular"];
                            $this->AdministracionModel->insertar("errores", $error);
                        } else {
                            $this->AdministracionModel->insertar("datos", $value);
                            $salida = 'guerda: ';
                        }
                    }
                    echo $salida . print_r($value, true) . "<br>";
                    ob_flush();
                    flush();
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

    public function assoc_to_array($datos) {
        $arr = array();
        foreach ($datos as $key => $value) {
            $arr[] = $value[key($value)];
        }
        return $arr;
    }

}
