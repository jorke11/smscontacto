<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $contactos;
    protected $gerencias;
    protected $grupos;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("America/Bogota");


        $this->load->model("AdministracionModel");
        $idusuario = $this->session->userdata("idusuario");



        if (!empty($idusuario)) {
            $seg = $this->uri->segment(1);
            
            if (strpos($seg, 'cron') !== FALSE) {
                redirect('login');
            }
        }

//        if (isset($idusuario)) {
//            $where = "id=" . $this->session->userdata("idusuario");
//            $user = $this->AdministracionModel->buscar("usuarios", '(cupo-(coalesce(enviados,0)-coalesce(pendientes,0) )) cupo', $where, 'row');
//            if ($this->session->userdata("cupo") != $user->cupo) {
//                $this->session->unset_userdata("cupo");
//                $this->session->set_userdata(array("cupo" => $user->cupo));
//            }
//        }
    }

    public function divideNumero($numero) {
        $numeros = (str_replace("\n", ',', $numero));
        $numeros = explode(",", $numeros);
        return array_filter($numeros);
    }

    /**
     * Metodo para asignar NULL a las campos POST que llegan del formulario
     * @param array $arreglo
     * @return array
     */
    public function asignaNull($arreglo) {
        foreach ($arreglo as $i => $value) {
            $respuesta[$i] = ($value == '') ? NULL : $value;
        }
        return $respuesta;
    }

    /**
     * Metodo para crear o escribir en un plano
     * @param type $archivo
     * @param type $datos
     * @param type $separador
     */
    public function crearPlano($archivo, $titulo, $datos, $separador) {
        /**
         * crea el texto a escribir
         */
        $alto = sizeof($datos);
        $largo = sizeof($datos[0]);
        $largotitulo = sizeof($titulo);
        $texto = '';

        /**
         * crea los titulos del archivo si no existe
         */
        if (!file_exists($archivo)) {
            for ($j = 0; $j < $largotitulo; $j++)
                $texto .= trim($titulo[$j]) . $separador;
            $texto .= "\n";
        }
        /**
         * crea el texto a escribir
         */
        for ($i = 0; $i < $alto; $i++) {
            for ($j = 0; $j < $largo; $j++)
                $texto .= trim($datos[$i][$j]) . $separador;
            $texto .= "\n";
        }

        /**
         * abre la conexion con el archivo
         */
        $link = fopen($archivo, "a");

        /**
         * escribre en el archivo
         */
        fwrite($link, $texto);

        /**
         * cierra el archivo
         */
        fclose($link);
    }

    /**
     * Metodo para reemplazar valores no validos para los envios de mensaje
     * @param string $string
     * @return string
     */
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

    /**
     * Metodo para listar la informacion de un directorio
     * @param type $ruta
     * @return string|boolean
     */
    function Directorios($ruta) {
        $respuesta = array();
        /**
         * Valida se existe una ruta
         */
        if (is_dir($ruta)) {
            /**
             * Abre la carpetas
             */
            if ($aux = opendir($ruta)) {
                /**
                 * recorre la carpeta
                 */
                while (($archivo = readdir($aux)) !== false) {
                    /**
                     * No tome directorios superiores
                     */
                    if ($archivo != "." && $archivo != "..") {
                        $ruta_completa = $ruta . '/' . $archivo;


                        if (is_dir($ruta_completa)) {
                            $otro[] = $ruta_completa;
                        } else {
                            $archivos["nombre"] = $archivo;
                            $archivos["size"] = filesize($ruta_completa);
                            $archivos["fecha"] = date('Y-m-d H:i:s', filemtime($ruta_completa));
                            $archivos["ruta"] = $ruta_completa;

                            $respuesta[] = $archivos;
                        }
                    }
                }
                closedir($aux);
                return $respuesta;
            }
        } else {

            return false;
        }
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

    function dataTable($data) {
        $datos = array();
        if ($data && is_array($data)) {
            foreach ($data as $i => $value) {
                foreach ($value as $val) {
                    $arreglo[] = ($val == NULL) ? '' : $val;
                }
                $datos[] = $arreglo;
                $arreglo = array();
            }
        }
        $respuesta["data"] = (COUNT($datos) > 0) ? $datos : array();
        return $respuesta;
    }

    /**
     * Meotodo para eliminar cache
     */
    public function removeCache() {
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    public function validaNumero($numero) {
        if (strlen(trim($numero)) == 10) {
            $num = substr($numero, 0, 3);
            $where = "prefijos ILIKE '%{$num}%'";
            $existe = $this->AdministracionModel->buscar('carrier', 'codigo', $where, 'row');

            $return["idcarrier"] = (count($existe) > 0) ? $existe->codigo : '';
            $return["numero"] = $numero;

            return (count($existe) > 0) ? $return : FALSE;
        } else {
            return FALSE;
        }
    }

    public function consultaSaldoCon() {
        $campos = "coalesce(cupo,0) + coalesce(adicion,0)- (coalesce(enviados,0) - coalesce(pendientes,0)) cupo";
        $datos = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $this->session->userdata("idusuario"), 'row');
        $this->session->unset_userdata('cupo');
        $this->session->set_userdata(array('cupo' => $datos->cupo));
        return $datos->cupo;
    }

    public function SaldoUsuario($idusuario = NULL, $idgerencia = NULL) {

        if ($idusuario != NULL) {
            $campos = "coalesce(cupo,0) + coalesce(adicion,0)- (coalesce(enviados,0) - coalesce(pendientes,0)) cupo";
            $datos["consumousuario"] = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $idusuario, 'row');
        }

        if ($idgerencia != NULL) {

            $campos = "jer.nombre gerencia,jer.cupo cupogerencia,sum(us.cupo) + sum(us.adicion) cupousado,jer.cupo-(sum(us.cupo) + sum(us.adicion)) cupodisponible";
            $join = " JOIN jerarquias jer ON jer.codigo=us.idjerarquia ";
            $datos["saldogerencia"] = $this->AdministracionModel->buscar("usuarios us " . $join, $campos, "idjerarquia='" . $idgerencia . "' and us.estado=1" . ' group by 1,2', 'row');
            if (count($datos["saldogerencia"]) == 0) {
                $datos["saldogerencia"] = $this->AdministracionModel->buscar("jerarquias ", 'cupo cupodisponible', "codigo='" . $idgerencia . "' and estado=1", 'row');
            }
        }
        return $datos;
    }

    public function ValidaCupo($id) {
        $where2 = "idjerarquia = '" . $id . "' and estado=1";
        $campos = " sum(coalesce(cupo,0)) + sum(coalesce(adicion,0)) - (sum(coalesce(enviados,0)) + sum(pendientes)) disponible ";
        $usuarios = $this->AdministracionModel->buscar("usuarios us ", $campos, $where2, 'row');

        $where = "codigo = '" . $id . "' and estado=1";
        $cupogerencia = $this->AdministracionModel->buscar("jerarquias ", 'cupo', $where, 'row');

        $respuesta["cupousuario"] = $usuarios->disponible;
        $respuesta["cupogerencia"] = $cupogerencia->cupo;
        $respuesta["disponible"] = $cupogerencia->cupo - $usuarios->disponible;
        return $respuesta;
    }

    public function cupoGerencia($gerencia) {
        $campos = "";
        $datos = $this->AdministracionModel->buscar("jerarquias", $campos, "codigo='" . $gerencia . "'", 'row');
        return $datos->cupo;
    }

    public function controlEnvio($numeros, $data, $tipo = 'web') {
        $idusuario = $this->session->userdata("idusuario");

        if ($idusuario != FALSE) {

            $idsector = $this->session->userdata("idsector");
            $idperfil = $this->session->userdata("idperfil");
            $cont = 0;
            $param["idcontacto"] = $idusuario;
            $param["idsector"] = $idsector;

            $estado = ($idperfil == '4') ? 6 : 2;
//        $estado = 6;
            $base["idusuario"] = $idusuario;
            $base["nombre"] = $tipo . "_" . date("Y-m-d H:i:s");
            $base["fecha"] = date("Y-m-d H:i:s");
            $base["ip"] = $_SERVER["REMOTE_ADDR"];
            $idbase = $this->AdministracionModel->insertar('bases', $base);
            $idbase = ($idbase > 0) ? $idbase : -1;

            foreach ($numeros as $value) {
                $mensaje = '';
                $mensaje2 = '';

                $val = $this->validaNumero(trim($value));

                if ($val != FALSE || $val != 'numero errado') {
                    $param["idcarrier"] = (isset($val["idcarrier"])) ? $val["idcarrier"] : '';
                    $param["numero"] = $val["numero"];
                    $param["fecha"] = date("Y-m-d H:i:s");

                    if (is_array($val)) {
                        $where = " numero ILIKE '{$val["numero"]}' and estado=1";
                        $black = $this->AdministracionModel->buscar("blacklist", 'id', $where);
                        $param["fechaenvio"] = (isset($data["fechaenvio"])) ? $data["fechaenvio"] : date("Y-m-d H:i:s");
                        if (COUNT($black) == 0) {
                            $param["estado"] = $estado;
                            unset($param["error"]);
                            $param["idbase"] = $idbase;
                            $where = "codigo='" . $idsector . "'";
                            $nota = $this->AdministracionModel->buscar("jerarquias", 'nombre', $where, 'row');
                            $nota = (COUNT($nota) > 0) ? $nota->nombre : 'Usuario sin sector asignado';
                            $param["nota"] = $nota;
                            $mensaje = $this->LimpiaMensaje($data["mensaje"]);


                            if (strlen($mensaje) >= 160) {
                                $tam = ceil(strlen($mensaje) / 160);
                                $sms = array();
                                $anterior = 0;
                                $largo = 0;
                                for ($i = 1; $i <= $tam; ++$i) {
                                    $largo = $i * 160;
                                    $mensaje2 = substr($mensaje, $anterior, 160);
                                    $param["mensaje"] = $mensaje2;

                                    $idinsert = $this->AdministracionModel->insertar("registros", $param);
                                    $anterior = $largo;
                                    if (is_numeric($idinsert)) {
                                        $campos = "coalesce(pendientes,0) pendientes";
                                        $user = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $idusuario, 'row');
                                        $pendiente = (COUNT($user) >= 0) ? $user->pendientes : 0;
                                        $this->AdministracionModel->update("usuarios", $idusuario, array("pendientes" => $pendiente + 1));
                                    }
                                }
                            } else {
                                $param["mensaje"] = $data["mensaje"];
                                $idinsert = $this->AdministracionModel->insertar("registros", $param);
                                if (is_numeric($idinsert)) {
                                    $campos = "coalesce(pendientes,0) pendientes";
                                    $user = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $idusuario, 'row');
                                    $pendiente = (COUNT($user) >= 0) ? $user->pendientes : 0;
                                    $this->AdministracionModel->update("usuarios", $idusuario, array("pendientes" => $pendiente + 1));
                                }
                            }
                        } else {
                            unset($param["idcarrier"]);
                            unset($param["estado"]);
                            unset($param["idsector"]);
                            $param["numero"] = $value;
                            $param["mensaje"] = $data["mensaje"];
                            $param["error"] = 'Blacklist';
                            $param["fecha"] = date("Y-m-d H:i:s");
                            $param["idbase"] = $idbase;
                            $this->AdministracionModel->insertar("errores", $param);
                        }
                    } else {

                        unset($param["idcarrier"]);
                        unset($param["estado"]);
                        unset($param["idsector"]);
                        $param["mensaje"] = $data["mensaje"];
                        $param["numero"] = $value;
                        $param["error"] = $val;
                        $param["idbase"] = $idbase;
                        $param["error"] = 'Problemas con el numero o con el prefijo del Operador';

                        $this->AdministracionModel->insertar("errores", $param);
                    }
                } else {
                    unset($param["idcarrier"]);
                    unset($param["estado"]);
                    unset($param["idsector"]);
                    $param["numero"] = $value;
                    $param["mensaje"] = $data["mensaje"];
                    $param["error"] = 'Problemas con el numero';
                    $param["fecha"] = date("Y-m-d H:i:s");
                    $param["idbase"] = $idbase;
                    $this->AdministracionModel->insertar("errores", $data);
                }
            }

            $insertados = $this->AdministracionModel->buscar("registros", 'count(*) valor', 'idbase=' . $idbase);
            $errores = $this->AdministracionModel->buscar("errores", 'count(*) valor', 'idbase=' . $idbase);
            $respuesta["registros"] = (count($insertados) >= 0) ? $insertados[0]["valor"] : 0;
            $respuesta["errores"] = ($errores >= 0) ? $errores[0]["valor"] : 0;
            $this->AdministracionModel->update('bases', $idbase, $respuesta);
            $campos = "coalesce(cupo,0) + coalesce(adicion,0)- (coalesce(enviados,0) + coalesce(pendientes,0)) cupo";
            $datos = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $idusuario, 'row');
            $respuesta["cupoactual"] = $datos->cupo;
            $respuesta["idbase"] = $idbase;
            echo json_encode($respuesta);
        } else {
            $respuesta["sesion"] = 'Se perdio datos de Sesion, por favor recargar!';
            echo json_encode($respuesta);
        }
    }

    public function ultimoid() {
        $idusuario = $this->session->userdata("idusuario");
        $datos = $this->AdministracionModel->buscar("bases", 'max(id)+1 max', 'idusuario=' . $idusuario, 'row');
        echo json_encode($datos);
    }

    function abecedario($inicio = NULL, $fin = NULL, $length = NULL) {

        $ab = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
        $posini = stripos($ab, $inicio) / 2;
        $posfin = (stripos($ab, $fin) / 2) + 1;
        $postotal = $posfin - $posini;
        $arreglo = explode(",", $ab);

        if ($length == NULL) {
            return ($inicio != NULL && $fin != NULL) ? array_slice($arreglo, $posini, $postotal) : $arreglo;
        } else {
            return array_slice($arreglo, 0, $length);
        }
    }

    public function peticionExcel($menu) {
        switch ($menu) {
            case '1': {
                    $titulo = "Informe_dia";
                    break;
                }
            case '2': {
                    $titulo = "Informe_rango";
                    break;
                }
        }


        $datos = $this->AdministracionModel->ejecutar($this->session->userdata("sqlinforme"));
        $this->generadorInforme($titulo, $datos);
    }

    public function peticionErrores($idbase = NULL) {
        $campos = "error,fecha,mensaje,numero,idbase codigo";
        $datos = $this->AdministracionModel->buscar("errores", $campos, 'idbase=' . $idbase);
        $this->generadorInforme('Informe Errores', $datos);
    }

    public function generadorInforme($titulo, $datos) {

        $this->load->library('excel');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Contactosms");

        $abc = $this->abecedario(null, null, count($datos[0]));
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:' . $abc[count($abc) - 1] . '1');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($titulo));

        $conta = 0;

        foreach ($datos[0] as $j => $valor) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($abc[$conta] . '2', strtoupper($j));
            $conta++;
        }

        $cont = 0;
        $letra = 3;

        foreach ($datos as $i => $value) {
            foreach ($value as $j => $valor) {
                $valor = ($valor == null) ? 0 : $valor;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($abc[$cont] . $letra, $valor);

                $cont++;
            }
            $letra++;
            $cont = 0;
        }


        $objPHPExcel->getActiveSheet()->setTitle($titulo);
        $objPHPExcel->setActiveSheetIndex(0);
        $tituloarchivo = str_replace(" ", "_", $titulo);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $tituloarchivo . date("Y-m-d") . '.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function notificacion($html, $data = NULL) {
        $this->load->library("email");

        $data["from"] = (isset($data["from"])) ? $data["from"] : 'Alertas';
        $data["to"] = (isset($data["to"])) ? $data["to"] : 'jpinedom@hotmail.com';
        $data["subject"] = (isset($data["subject"])) ? $data["subject"] : 'Alertas archivos excel';

        $correo = $this->AdministracionModel->buscar("correos", '*', 'id=1', 'row');
        $config['protocol'] = $correo->protocolo;
        $config['smtp_host'] = $correo->host;
        $config['smtp_port'] = $correo->puerto;
        $config['smtp_user'] = $correo->usuario;
        $config['smtp_pass'] = $correo->clave;
        $config['smtp_timeout'] = '7';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->email->initialize($config);

//        $this->email->from('reportes contactosms');
        $this->email->from($data["from"]);

        $this->email->to('jpinedom@hotmail.com,andresrodriguez.sonda@natura.net,servicioalcliente@contactosms.com.co');
//       $this->email->to('jpinedom@hotmail.com,servicioalcliente@contactosms.com.co');
//        $this->email->to("jpinedom@hotmail.com");
        $this->email->subject('Alertas archivos excel');

        $this->email->message($html);

        $this->email->send();


        //con esto podemos ver el resultado
        var_dump($this->email->print_debugger());
        $this->email->clear(TRUE);
    }

}
