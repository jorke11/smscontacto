<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include(APPPATH . "third_party/SpreadsheetReader.php");
include(APPPATH . "third_party/php-excel-reader/excel_reader2.php");
include APPPATH . 'third_party/PHPExcel/IOFactory.php';

class CroncarteraModel extends MY_Model {

//    public $rutaftp = '/home/autonatura/';
    public $rutaftp = '/var/www/html/smscontacto/autonatura/';
//    public $rutaftp = '/home/pruebasftp/';
    public $archivo = '';
    public $idarchivo = '';

    public function __constructor() {
        parent::__construct();
        $this->load->database();
//        $this->rutaftp = '/home/autonatura/';
    }

    function cargaDatosCartera($datos) {


        $inputFileName = $datos["ruta"];
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
        $col = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
        $fil = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $err = 0;

        $arc["nombre"] = $datos["nombre"];
        $arc["ruta"] = $inputFileName;
        $arc["idusuario"] = -1;
        $arc["fecha"] = date("Y-m-d H:i:s");
        $arc["registros"] = $fil;
        $this->idarchivo = $this->insertar("archivos", $arc);

//        if ($col == 'E' && $fil > 2) {
        if ($fil > 2) {
            $sql = "TRUNCATE cartera RESTART IDENTITY CASCADE;";
            $this->db->query($sql);

            foreach ($rowIterator as $k => $row) {
                $cellIterator = $row->getCellIterator();

                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                if (1 == $row->getRowIndex())
                    continue; //skip first row la del encabezado
                $rowIndex = $row->getRowIndex();
                $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '');

                foreach ($cellIterator as $key => $cell) {

                    switch ($cell->getColumn()) {
                        case 'A': {

                                $in["nombre"] = $this->LimpiaMensaje($cell->getValue());
                                if ($in["nombre"] == '') {
                                    $err++;
                                }
                                break;
                            }
                        case 'B': {
                                $in["sector"] = $cell->getValue();
                                if ($in["sector"] == '') {
                                    $err++;
                                }
                                break;
                            }
                        case 'C': {
                                $in["celular"] = $cell->getValue();
                                if ($in["sector"] == '') {
                                    $err++;
                                }
                                break;
                            }
                        case 'D': {
                                $in["deuda"] = $cell->getValue();
                                break;
                            }
                        case 'E': {
                                $in["diamora"] = $cell->getValue();
                                break;
                            }
                    }
                }

                $in["fechacargue"] = date("Y-m-d H:i:s");
                $in["idarchivo"] = $this->idarchivo;

                if ($err == 0) {
                    $this->insertar("cartera", $in);
                    echo "<br>OK:";
                    print_r($in);
                } else {
                    $errores["error"] = 'Campos vacios';
                    $errores["idarchivo"] = $this->idarchivo;
                    $errores["fecha"] = date("Y-m-d H:i:s");

                    $this->insertar("errores", $errores);
                    echo "<br>Error";
                    print_r($in);
                    $err = 0;
                }
                ob_flush();
                flush();
            }
            $where = 'idarchivo=' . $this->idarchivo;
            echo $where;
            $r = $this->buscar("cartera", "count(*) procesado", $where, 'row');
            $up["procesado"] = $r->procesado;
            $e = $this->buscar("errores", "count(*) errores", $where, 'row');
            $up["errores"] = $e->errores;
            $up["status"] = true;
            print_r($r);
            $this->update("archivos", $this->idarchivo, $up);

//            $ftp = $this->rutaftp . "procesados/" . date("Y-m-d");
//            $this->crearRutaCarpeta($ftp);
//            $rutanueva = $ftp . "/" . $datos["nombre"];
//            if (copy($datos["ruta"], $rutanueva)) {
//                unlink($datos["ruta"]);
//            }
        } else {
            echo "asd";
            Exit;
            $update["nombre"] = ($fil < 2) ? 'Archivo vacio' : 'formato invalido';
            $this->update("archivos", $this->idarchivo, $update);
            print_r($update);
        }

        exit;
    }

    function cargaDatosCarteraCron($datos, $cron_id) {


        $inputFileName = $datos["ruta"];
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
        $col = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
        $fil = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $err = 0;

        $arc["nombre"] = $datos["nombre"];
        $arc["ruta"] = $inputFileName;
        $arc["idusuario"] = -1;
        $arc["fecha"] = date("Y-m-d H:i:s");
        $arc["registros"] = $fil;

        $this->update("crones", $cron_id, array("unidad" => ($fil-1)));

        $this->idarchivo = $this->insertar("archivos", $arc);

//        if ($col == 'E' && $fil > 2) {
        if ($fil > 2) {
            $sql = "TRUNCATE cartera RESTART IDENTITY CASCADE;";
            $this->db->query($sql);

            foreach ($rowIterator as $k => $row) {
                $cellIterator = $row->getCellIterator();

                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                if (1 == $row->getRowIndex())
                    continue; //skip first row la del encabezado
                $rowIndex = $row->getRowIndex();
                $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '');

                foreach ($cellIterator as $key => $cell) {

                    switch ($cell->getColumn()) {
                        case 'A': {

                                $in["nombre"] = $this->LimpiaMensaje($cell->getValue());
                                if ($in["nombre"] == '') {
                                    $err++;
                                }
                                break;
                            }
                        case 'B': {
                                $in["sector"] = $cell->getValue();
                                if ($in["sector"] == '') {
                                    $err++;
                                }
                                break;
                            }
                        case 'C': {
                                $in["celular"] = $cell->getValue();
                                if ($in["sector"] == '') {
                                    $err++;
                                }
                                break;
                            }
                        case 'D': {
                                $in["deuda"] = $cell->getValue();
                                break;
                            }
                        case 'E': {
                                $in["diamora"] = $cell->getValue();
                                break;
                            }
                    }
                }

                $in["fechacargue"] = date("Y-m-d H:i:s");
                $in["idarchivo"] = $this->idarchivo;

                if ($err == 0) {
                    $this->insertar("cartera", $in);
                    echo "<br>OK:";
                    print_r($in);
                } else {
                    $errores["error"] = 'Campos vacios';
                    $errores["idarchivo"] = $this->idarchivo;
                    $errores["fecha"] = date("Y-m-d H:i:s");

                    $this->insertar("errores", $errores);
                    echo "<br>Error";
                    print_r($in);
                    $err = 0;
                }
                ob_flush();
                flush();
            }
            $where = 'idarchivo=' . $this->idarchivo;
            echo $where;
            $r = $this->buscar("cartera", "count(*) procesado", $where, 'row');
            $up["procesado"] = $r->procesado;
            $e = $this->buscar("errores", "count(*) errores", $where, 'row');
            $up["errores"] = $e->errores;
            $up["status"] = true;
            print_r($r);
            $this->update("archivos", $this->idarchivo, $up);


            $ftp = $this->rutaftp . "procesados/" . date("Y-m-d");

            $this->crearRutaCarpeta($ftp);
            $rutanueva = $ftp . "/" . $datos["nombre"];
            if (copy($datos["ruta"], $rutanueva)) {
//                unlink($datos["ruta"]);
            }
        } else {
            echo "asd";
            Exit;
            $update["nombre"] = ($fil < 2) ? 'Archivo vacio' : 'formato invalido';
            $this->update("archivos", $this->idarchivo, $update);
            print_r($update);
        }
        $this->update("crones", $cron_id, array("estado" => 0, "consulta" => 0));
        echo "Fin del proceso!";
        exit;
    }

    public function getprocesss() {
        $row = $this->buscar("cartera", "count(*) procesado", null, 'row');
        echo json_decode($row);
    }

    public function crearRutaCarpeta($rutaCompleta, $archivo = null) {
        $ruta = explode("/", $rutaCompleta);
        $completa = '';
        foreach ($ruta as $value) {
            $completa .= $value . "/";
            if (!file_exists($completa)) {
                mkdir($completa);
                chmod($completa, 0777);
            } else {
                if ($archivo != null) {
                    if (file_exists($completa . $archivo)) {
                        unlink($completa . $archivo);
                    }
                }
            }
        }

        return $rutaCompleta;
    }

    function InsertaDatosCar($arreglo, $tipo) {
        $data["nombre"] = $this->LimpiaMensaje($arreglo[1]);
        $data["sector"] = trim($arreglo[2]);
        $data["celular"] = trim($arreglo[3]);
        $data["deuda"] = trim($arreglo[4]);
        $data["diamora"] = trim($arreglo[5]);
        $data["fechacargue"] = date("Y-m-d H:i:s");
        $data["idarchivo"] = $this->idarchivo;
        $this->insertar("cartera", $data);
        print_r($data);
        echo "<br>";
    }

    function LimpiaMensaje($string) {
        $string = trim($string);
        $string = $this->quitaTilde($string);
        $string = utf8_encode((filter_var($string, FILTER_SANITIZE_STRING)));
        $string = $this->quitaTilde($string);

        $string = str_replace(
                array("\\", "¨", "º", "–", "~", "|", "·",
            "¡", "[", "^", "`", "]", "¨", "´", "¿",
            '§', '¤', '¥', 'Ð', 'Þ'), '', $string
        );


        $string = str_replace(
                array(";",), array(","), $string
        );

        $string = str_replace(
                array("&#39;", "&#39,", '&#34;', '&#34,'), array("'", "'", '"', '"'), $string
        );

        $string = htmlentities($string, ENT_QUOTES | ENT_IGNORE, 'UTF-8');

        $string = str_replace(
                array('&quot;', '&#39;', '&#039;'), array('"', "'", "'"), $string
        );
        $string = str_replace(
                array('&amp;', '&nbsp;'), array('&', ' '), $string
        );
        $string = str_replace(
                array('&deg;', '&sup3;', '&shy;'), array(''), $string
        );
        $string = str_replace(
                array('&copy;', '&sup3;', '&shy;', '&plusmn;'), array('e', 'o', 'i', 'n'), $string
        );


        return $string;
    }

    function quitaTilde($string) {
        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'Ã'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string
        );
        return $string;
    }

}
