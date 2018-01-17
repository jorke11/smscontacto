<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronenvioSMS extends MY_Controller {

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
        $where = "nombre = 'cronenvioSMS' ";
        //echo $where;
        $data["cron"] = $this->AdministracionModel->buscar('crones', 'id,ejecutado,estado,consulta', $where);
        
        //si no existe el registro lo ingresa
        if(COUNT($data["cron"]) == 0)
        {
            $datos["nombre"] = 'cronenvioSMS';
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

                        $where = "estado = '2' AND fechaenvio < now() "
                                . " ORDER BY id LIMIT 10";
                        $data["pendientes"] = $this->AdministracionModel->buscar('registros', '*', $where);
                        
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
                                    $numero = $data["pendientes"][$i]["numero"];
                                    $mensaje = str_replace(" ", "%20", trim($data["pendientes"][$i]["mensaje"]));
                                    $usr = "NATURA";
                                    $pwd = "N4TUR4";
                                    $nota = date("Y-m-d");
                                    $url = "http://www.appcontacto.com.co/wsurl?"
                                        . "usuario=$usr&clave=$pwd&numero=$numero"
                                        . "&mensaje=".$mensaje;
                                    //echo $url;
                                    $rta =  file_get_contents($url)."<br>";
                                    
                                    $pos = strpos($rta, "DATO CARGADO EXITOSAMENTE");
                                    if ($pos === false) {
                                        
                                        $cambios = array("estado" => '3',"fechaenvio" => date("Y-m-d H:i:s"),"respuesta" => $rta);
                                        $this->AdministracionModel->update('registros', $data["pendientes"][$i]["id"], $cambios);
                                        
                                    } else {
                                        
                                        $respuesta = explode("</div>", $rta);
                                        if(!$respuesta[1])
                                            $respuesta[1] = $rta;
                                        $cambios = array("estado" => '1',"fechaenvio" => date("Y-m-d H:i:s"),"respuesta" => $respuesta[1]);
                                        $this->AdministracionModel->update('registros', $data["pendientes"][$i]["id"], $cambios);
                                        $this->actualizar($data["pendientes"][$i]["idcontacto"], $data["pendientes"][$i]["idcarrier"]);
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

    function actualizar($idusuario,$idcarrie) {
            /**
             * actualiza la tabla de resumenes
             */
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
            
    }
    
}
