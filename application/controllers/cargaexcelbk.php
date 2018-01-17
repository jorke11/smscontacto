<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include(APPPATH . "controllers/croncreagrupos.php");

class CargaExcel extends MY_Controller {

    public $idusuario;

    public function __construct() {
        parent::__construct();
        $this->load->library("reader");
        $this->load->model("CargarexcelModel");
        $this->idusuario = $this->session->userdata("idusuario");
    }

    public function index() {
        $this->load->view("cargaexcel/index");
    }

    public function subeArchivo() {

        $nombreArchivo = str_replace(" ", "_", $_FILES["archivo"]["name"]);
        $archivo = $_FILES["archivo"]["tmp_name"];
        $datos = new Spreadsheet_Excel_Reader();
        $error = $datos->read($archivo);
        $ruta = "/var/www/html/smscontacto/tmp/" . date("Y-m-d") . "/";
//        $ruta = $_SERVER['DOCUMENT_ROOT'] . "smscontacto/tmp/" . date("Y-m-d") . "/";

        $ruta = $this->crearRutaCarpeta($ruta, $nombreArchivo);
//        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . $ruta;

        $config['upload_path'] = $ruta;
        $config['allowed_types'] = '*';
        $config['file_name'] = $nombreArchivo;
        $this->load->library('upload', $config);

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
        $arc["idusuario"] = $this->idusuario;
        $arc["registros"] = $datos->sheets[0]['numRows'];
        $return["idarchivo"] = $this->CargarexcelModel->insertar("archivos", $arc);
        echo json_encode($return);
    }

    /**
     * Metodo para que el cron cree la ruta de la carpeta
     */
    public function creaRuta() {
        $ftp = "/home/ftpnatura/procesados/" . date("Y-m-d");
        $ruta = $this->crearRutaCarpeta($ftp);
    }

    public function cargaFtp() {
        $ftp = "/home/ftpnatura/archivos/";
        $archivos = $this->Directorios($ftp);
        rename($archivos[0]["ruta"], str_replace(" ", "_", $archivos[0]["ruta"]));

        $archivos = $this->Directorios($ftp);
        $arc["nombre"] = $archivos[0]["nombre"];
        $arc["fecha"] = date("Y-m-d H:i:s");
        $arc["ruta"] = $archivos[0]["ruta"];
        $arc["idusuario"] = $this->idusuario;
        $return["idarchivo"] = $this->CargarexcelModel->insertar("archivos", $arc);
        $this->grabaDatos($return);
    }

    public function grabaDatos($idext = NULL) {
        $data = ($idext == NULL) ? $this->input->post() : $idext;
        /**
         * Se instancia objeto de la clase para leer el archivo excel
         */
        $where = "id=" . $data["idarchivo"];

        $archivo = $this->CargarexcelModel->buscar("archivos", 'ruta,nombre,fecha', $where, 'row');
        $con = 0;
        $datos = new Spreadsheet_Excel_Reader();
        $error = $datos->read($archivo->ruta);

        $arreglo = array();
        $codigos = array();
        $indice = array("nombre", "ciclo", 'gerencia_comercial', "codigo_comercial", "sectores", "codigo_sector", "celular", "confirmado");

        
        foreach ($datos->sheets[0]['cells'] as $i => $value) {
            $blacklist = $this->CargarexcelModel->buscar("blacklist", 'id', "numero='{$value[6]}'");
            if (!isset($blacklist->id)) {

                $where = "codigo_comercial='{$value[4]}'";
                $gerencia = $this->CargarexcelModel->buscar("datos", 'id', $where, 'row');

                if (count($gerencia) > 0) {
                    if (!in_array($value[4], $codigos)) {

                        $codigos[] = $value[4];
                        $this->CargarexcelModel->eliminaGerencias($value);
                    }
                } else {
                    $codigos[] = $value[4];
                }

                foreach ($value as $j => $val) {
                    $arreglo[$indice[$con]] = $this->LimpiaMensaje(utf8_encode($val));
                    $con++;
                }

                $arreglo["fechacargue"] = date("Y-m-d H:i:s");
                $arreglo["idarchivo"] = $data["idarchivo"];
                $arreglo["estado"] = 1;
                
                $this->CargarexcelModel->insertar("datos", $arreglo);
                $con = 0;
            } else {
                $error["error"] = "Black List";
                $error["idgerencia"] = $gerencia->id;
                $error["fecha"] = date("Y-m-d H:i:s");
                $error["linea"] = $i;
                $error["numero"] = $value[7];

                $this->CargarexcelModel->insertar("errores", $error);
            }
        }

        $this->creaRuta();
        $rutanueva = "/home/ftpnatura/procesados/" . date("Y-m-d") . "/" . $archivo->nombre;

        if (copy($archivo->ruta, $rutanueva)) {
            unlink($archivo->ruta);
        }
        $update["ruta"] = $rutanueva;
        $this->CargarexcelModel->update("archivos", $data["idarchivo"], $update);

        $respuesta = $this->CargarexcelModel->buscar("datos", 'count(*) insertados', 'idarchivo=' . $data["idarchivo"], 'row');
        $obj = new CroncreaGrupos();
        $obj->crearTodo();
        echo json_encode($respuesta);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
