<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronProcesarFTP extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        // definir algunas variables
      $d = dir("/data/postgres/sql/archivos_natura/");
      $hoy = date("Ymd");
      
      echo "Handle: ".$d->handle."<br>\n";
      echo "Path: ".$d->path."<br>\n";
      while($entry=$d->read())
	  {
          //ruta completa del archivo
	      $rut_arch = "/data/postgres/sql/archivos_natura/".$entry;
          //verifica si es del dia
          $pos = strpos($rut_arch,$hoy);
          if ($pos === false) {
                echo "NO ES UN ARCHIVO DEL DIA O ES UN DIRECTORIO \n<BR>";
            } else {
                //PROCESA CADA ARCHIVO 
                 //abre el archivo
	             $archivo = fopen($rut_arch,"r");
	             $row = 1;
                 while ($dats = fgetcsv ($archivo, 1000, ";"))
        	      {
        	         $num = count ($dats);
        	         $row++;
                    $datos["fecha"] = date("Y-m-d H:i:s");
                    $datos["archivo"] = $entry;
                    $datos["fechadespacho"] = substr($dats[12],0,4)."-".substr($dats[12],4,2)."-".substr($dats[12],6,2);
                    $datos["pedido"] = $dats[1];
                    $datos["ctransportadora"] = $dats[2];
                    if($dats[2] == '1234')
                       $transp = 'SERVIENTREGA';
                    else if($dats[2] == '5678')
                       $transp = 'ENVIA';
                    else if($dats[2] == '221697')
                       $transp = 'INTERRAPIDISIMO';
                    else
                       $transp = '';
                    $datos["transportadora"] = $transp;
                    $datos["numero"] = $dats[15];
                    $datos["mensaje"] = "Querida Consultora tu pedido ".$datos["pedido"].", llegara por ".$transp." el ".$datos["fechadespacho"].". Mas inf: http://200.43.192.44:8080/nwtcolombia/verPedido.html?id=".$datos["pedido"];
                    $datos["fechaenvio"] = null;
                    if($datos["fechadespacho"]<date("Y-m-d"))
                    {
                        $datos["estado"] = '3';
                        $datos["respuesta"] = 'Error de fecha de Despacho';
                    }
                    else
                    {
                        $datos["estado"] = '2';
                        $datos["respuesta"] = null;
                    }
                    $registro = "";  
                    for($i=0;$i<18;$i++)
                        $registro .= $dats[$i].";";
                    $datos["registro"] = $registro = str_replace("'","",$this->LimpiaMensaje($registro));     
                    /*echo "<pre>";
                    var_dump($datos);              
                    echo "<pre>";*/   
                    $where = "registro ilike '%".$registro."%'";
                    $data["pendientes"] = $this->AdministracionModel->buscar('archivosftp', '*', $where); 
                    
                        //valida si hay pendiente
                        if (sizeof($data["pendientes"]) == 0)
                        {
                            echo "REGISTRO ".$datos["pedido"]." INSERTADO \n<BR>";
                            $this->AdministracionModel->insertar('archivosftp', $datos);
                        }    
                  }   
                  $close = fclose($archivo);
	               echo "\nLectura de Archivo Finalizada con Exito \n <BR>";

				   //se elimina el archivo
				  if(copy('/data/postgres/sql/archivos_natura/'.$entry,'/data/postgres/sql/archivos_natura/procesados/'.$entry))
					 unlink('/data/postgres/sql/archivos_natura/'.$entry);
            }

       } //fin while
       $d->close();
    }
}
