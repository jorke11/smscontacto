<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Programados extends MY_Controller {

    public $tabla;
    public $ci;

    public function __construct() {
        parent::__construct();
        $this->load->model("ProgramacionModel");
        $this->tabla = '';
        $this->ci = &get_instance();
    }

    public function index() {
        $this->load->view("envio/programados/inicio");
    }

    public function cargaTabla() {

        $data = $this->input->post();

        $idbase = $data["data"]["idbase"];
        $ffinal = $data["data"]["inicio"];

        if ($idbase != '') {
            $this->datatables->where("idbase", (int) $idbase);
        }

        if ($ffinal != '') {
            $ffinal = " AND fechaenvio<='" . $ffinal . "'";
        }

        if ($this->session->userdata("idperfil") != 3) {
            $this->datatables->where("idsector", $this->session->userdata("idsector"));
        }


        $where = "fechaenvio >='" . date("Y-m-d H:i:s") . "'" . $ffinal;


        echo $this->datatables
                ->select("idbase,numero,mensaje,nota,fechaenvio")
                ->from("registros")
                ->where($where)
                ->where("estado", "2")
                ->generate();
    }

    public function datosConfirmacion() {
        $data = $this->input->post();
        $idbase = $data["idbase"];
        $ffinal = $data["inicio"];

        if ($idbase != '') {
            $this->ci->db->where("idbase", $idbase);
        }

        if ($ffinal != '') {
            $ffinal = " AND fechaenvio<='" . $ffinal . "'";
        }

        if ($this->session->userdata("idperfil") != 3) {
            $this->ci->db->where("idsector", $this->session->userdata("idsector"));
        }

        $where = "fechaenvio >='" . date("Y-m-d H:i:s") . "'" . $ffinal;

        $res = $this->ci->db->select("idbase")
                ->from("registros")
                ->where($where)
                ->where("estado", "2")
                ->group_by("idbase")
                ->get();

        $base = array();
        foreach ($res->result_array() as $value) {
            $base [] = $value["idbase"];
        }

        $res = $this->ci->db->select("*")
                ->from("bases")
                ->where_in("id", $base)
                ->get();
        
        echo json_encode($res->result_array());
    }

    public function cancelar() {

        $data = $this->input->post();
        $res["total"] = $this->ProgramacionModel->reversionCupo($data);
        echo json_encode($res);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */