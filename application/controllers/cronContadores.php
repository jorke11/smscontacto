<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronContadores extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        /**
         * trae los usuarios con cupo asignado  
         */
        $where = " fecha > '".date("Y-m")."-01' group by 1 ";
        $data["usuarios"] = $this->AdministracionModel->buscar('registros', 'idcontacto', $where);
        
        for($i=0;$i<sizeof($data["usuarios"]);$i++)
        {
            if($data["usuarios"][$i]["idcontacto"])
            {
                $where = " idcontacto = ".$data["usuarios"][$i]["idcontacto"]." AND fecha > '".date("Y-m")."-01' group by 1";
                $data["enviados"] = $this->AdministracionModel->buscar('registros', 'estado, count(*)', $where);
                
                $enviados = $errores = $pendientes = '0'; 
                for($j=0;$j<sizeof($data["enviados"]);$j++)
                {
                    if($data["enviados"][$j]["estado"] == 1)
                       $enviados = $data["enviados"][$j]["count"];
                    if($data["enviados"][$j]["estado"] == 2)
                       $errores = $data["enviados"][$j]["count"];
                    if($data["enviados"][$j]["estado"] == 3)
                       $pendientes = $data["enviados"][$j]["count"];
                }//fin for j
                $cambios = array("enviados"=>$enviados,"pendientes" => $pendientes,"errores" => $errores);
                //var_dump($cambios);
                $this->AdministracionModel->update('usuarios', $data["usuarios"][$i]["idcontacto"], $cambios);
            }
            else 
                $cambios = null;    
            echo $data["usuarios"][$i]["idcontacto"]." - ".var_dump($cambios)."<br>";
        }//fin for i
    }
}
