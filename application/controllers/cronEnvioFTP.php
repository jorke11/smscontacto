<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronEnvioFTP extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {  
        $contador = 0;
        $suma = 0; 
        /**
         * se pregunta el estado del registro de cron, para establecer si se ejecuta o no
         */
        $where = "nombre = 'cronenvioFTP' ";
        //echo $where;
        $data["cron"] = $this->AdministracionModel->buscar('crones', 'id,ejecutado,estado,consulta', $where);
        
        //si no existe el registro lo ingresa
        if( !isset($data["cron"][0]["id"]))
        {
            $datos["nombre"] = 'cronenvioFTP';
            $datos["unidad"] = 1;
            $datos["medida"] = 'minuto';
            $datos["ejecutado"] = NULL;
            $datos["estado"] = 0;
            $datos["consulta"] = 0;
            $this->AdministracionModel->insertar('crones', $datos);
        } //fin if   
        else
        {
            //echo $data["cron"][0]["estado"]."---";
            //echo $data["cron"][0]["consulta"]."---";
            //evalua si esta ejecutado
            if($data["cron"][0]["estado"] == 1)
            {
                if( $data["cron"][0]["consulta"] > 15 OR $data["cron"][0]["consulta"] == null)
                {
                    $cambios = array("consulta" => 0, "estado" => 0, "ejecutado" => null);
                }
                else
                {
                    $nuevo = $data["cron"][0]["consulta"] + 1;
                    $cambios = array("consulta" => $nuevo);
                }
                $this->AdministracionModel->update('crones', $data["cron"][0]["id"], $cambios);
                echo "cron en proceso, no se debe ejecutar de nuevo";
            }    
            else
            {
                $this->cronenvioIn($data["cron"][0]["id"]);
                
                    while($contador < 55)
                    {
                        $this->benchmark->mark('codigo_inicio');

                        $where = "estado = '2' "
                                . " order by fecha desc  LIMIT 10";
                        $data["pendientes"] = $this->AdministracionModel->buscar('archivosftp', '*', $where);
                        
                        //print_r($data["pendientes"]);
                        $tam = sizeof($data["pendientes"]);


                        //valida si hay pendiente
                        if ($tam == 0)
                        {
                            echo $contador." - NO HAY MENSAJES PARA ENVIAR<br>";
                            //$coeficiente = $contador % 5;
                            //if( $coeficiente == 0)
                            sleep(1);
                            $contador++;
                        }
                        else 
                        {

                            for ($i = 0; $i < $tam; $i++) {
									$mensaje = $this->LimpiaMensaje($data["pendientes"][$i]["mensaje"]);
                                    $numero = $data["pendientes"][$i]["numero"];
                                    $mensaje = str_replace(" ", "%20", trim($mensaje));
                                    $mensaje = str_replace("&", "%20", trim($mensaje));
                                    $mensaje = str_replace("#", "%20", trim($mensaje));
                                    
                                    $nota = str_replace(" ", "%20", trim($data["pendientes"][$i]["archivo"]));
                                    $usr = "SMSNATURA11";
                                    $pwd = "3Qf9RwhP";
                                    $url = "http://200.41.6.123/wsurl?"
                                        . "usuario=$usr&clave=$pwd&numero=$numero"
                                        . "&mensaje=".$mensaje."&nota=".$nota;
                                    //echo $url;
                                    echo date("H:i:s")."\n";
                                    $rta =  file_get_contents($url)."<br>";
                                    echo $url;
                                    echo "***********************************\n";
                                    var_dump($rta);
                                    echo date("H:i:s")."\n";
                                    $pos = strpos($rta, "DATO CARGADO EXITOSAMENTE");
                                    if ($pos === false) {
                                        
                                        $cambios = array("estado" => '3',"fechaenvio" => date("Y-m-d H:i:s"),"respuesta" => $rta);
                                        $this->AdministracionModel->update('archivosftp', $data["pendientes"][$i]["id"], $cambios);
                                        
                                    } else {
                                        
                                        $respuesta = explode("</div>", $rta);
                                            if(!isset($respuesta[1]))
                                                $respuesta[1] = $rta;
                                        $cambios = array("estado" => '1',"fechaenvio" => date("Y-m-d H:i:s"),"respuesta" => $respuesta[1]);
                                        $this->AdministracionModel->update('archivosftp', $data["pendientes"][$i]["id"], $cambios);
                                        //$this->actualizar($data["pendientes"][$i]["idcontacto"], $data["pendientes"][$i]["idcarrier"]);
                                    }
                            }//fin for
                            //desconexion del servidor smpp
                            $contador++;
                        }//fin else    
                        $this->benchmark->mark('codigo_fin');
                        echo $this->benchmark->elapsed_time('codigo_inicio', 'codigo_fin')."<br>";
                        $suma += $this->benchmark->elapsed_time('codigo_inicio', 'codigo_fin');
                        
                    }//fin WHILE
                    echo "<b>".$suma."</b>";
                
                    $this->cronenvioOut($data["cron"][0]["id"]);
            }   
        }// fin else*/
    }

    function cronenvioIn($id){
        $cambios = array("estado" => 1,
                         "ejecutado" => date("Y-m-d H:i:s"),"consulta" => 0);
        $this->AdministracionModel->update('crones', $id, $cambios);        
    }

    function cronenvioOut($id){
        $cambios = array("estado" => 0,
                         "ejecutado" => null,"consulta" => 0);
        $this->AdministracionModel->update('crones', $id, $cambios);        
    }

    /*function actualizar($idusuario,$idcarrie) {
            $where = "idusuario = '" . $idusuario . "' ";
            $where .= " AND idcarrie = '".$idcarrie."'";
            $where .= " AND fecha = '".date("Y-m-d")."'";
            $data["resumen"] = $this->AdministracionModel->buscar('resumenes', 'id,cantidad', $where);
           
            if($data["resumen"] == null)
            {
                $datos = array("idusuario" => $idusuario,
                               "idcarrie" => $idcarrie, "fecha" => date("Y-m-d"), "cantidad" => 1);  
                $this->AdministracionModel->insertar('resumenes', $datos);
    
            }//fin else
            else 
            {
                $nuevo = $data["resumen"][0]["cantidad"] + 1;
                $cambios = array("cantidad" => $nuevo);
                $this->AdministracionModel->update('resumenes', $data["resumen"][0]["id"], $cambios);
            }//fin if
            
            $where = "id = '" . $idusuario . "' ";
            $data["usuarios"] = $this->AdministracionModel->buscar('usuarios', 'id,enviados,pendientes', $where);
           
            $nuevo1 = $data["usuarios"][0]["enviados"] + 1;
            $nuevo2 = $data["usuarios"][0]["pendientes"] - 1;
            $cambios = array("enviados" => $nuevo1,"pendientes" => $nuevo2);
            $this->AdministracionModel->update('usuarios', $data["usuarios"][0]["id"], $cambios);

    }*/
    
}
