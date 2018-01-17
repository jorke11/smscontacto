<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CroncreaGrupos extends CI_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
        $this->tabla = '';
    }

    public function crearGrupos() {
        $grupos = $this->AdministracionModel->buscar("grupos", 'id,codigo');
        $datos = $this->AdministracionModel->buscar("datos group by ciclo", 'ciclo,count(id) cantidad');
        foreach ($grupos as $value) {
            foreach ($datos as $val) {
                if ($value["codigo"] == $val["ciclo"]) {
                    $data["cantidad"] = $val["cantidad"];
                    $this->AdministracionModel->update("grupos", $value["id"], $data);
                }
            }
        }
    }

    public function crearSectores() {
        
        $datos = $this->AdministracionModel->buscar("datos group by codigo_sector,sectores", 'codigo_sector,sectores');
        foreach ($datos as $value) {
            $valida = $this->AdministracionModel->buscar("jerarquias", 'id', "codigo='" . $value["codigo_sector"] . "' AND tipo=2", 'row');
            if (COUNT($valida) > 0) {
                $update["estado"] = 1;
                $update["nombre"] = $value["sectores"];
                $this->AdministracionModel->update("jerarquias", $valida->id, $update);
            } else {
                $data["codigo"] = $value["codigo_sector"];
                $data["nombre"] = $value["sectores"];
                $data["tipo"] = 2;
                $data["estado"] = 1;
                $this->AdministracionModel->insertar("jerarquias", $data);
            }
        }
        
    }

    public function crearGerencias() {
        $datos = $this->AdministracionModel->buscar("datos group by codigo_comercial,gerencia_comercial", 'codigo_comercial,gerencia_comercial');
        foreach ($datos as $value) {
            $valida = $this->AdministracionModel->buscar("jerarquias", 'id', "codigo='" . $value["codigo_comercial"] . "' AND tipo=1", 'row');
            if (COUNT($valida) > 0) {
                $update["estado"] = 1;
                $update["nombre"] = $value["gerencia_comercial"];
                $this->AdministracionModel->update("jerarquias", $valida->id, $update);
            } else {
                $data["codigo"] = $value["codigo_comercial"];
                $data["nombre"] = $value["gerencia_comercial"];
                $data["tipo"] = 1;
                $data["estado"] = 0;
                $this->AdministracionModel->insertar("jerarquias", $data);
            }
        }
    }

    public function crearTodo() {
        $this->crearGrupos();
        $this->crearSectores();
        $this->crearGerencias();
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */