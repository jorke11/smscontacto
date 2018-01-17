<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ProgramacionModel extends MY_Model {

    public function __constructor() {
        parent::__construct();
        $this->load->database();
    }

    public function reversionCupo($data) {



        $this->db->trans_start();

        $cont = 0;

        foreach ($data["bases"] as $value) {
            $user = $this->db
                    ->select("idusuario")
                    ->from("bases")
                    ->where_in("id", (int) $value)
                    ->get()
                    ->row_array();



            $env = $this->db
                    ->select("pendientes")
                    ->from("usuarios")
                    ->where_in("id", (int) $user["idusuario"])
                    ->get()
                    ->row_array();


            $up["estado"] = 4;

            $this->db->where_in("idbase", $value);
            $this->db->where_in("estado", "2");
            $this->db->update("registros", $up);



            $upd["estado"] = 0;
            $this->db->where("id", (int) $value);
            $this->db->update("bases", $upd);



            $total = $this->db
                    ->select("count(*) total")
                    ->from("registros")
                    ->where("idbase", (int) $value)
                    ->get()
                    ->row_array();
            
            $cont += $total["total"];

            $upda["pendientes"] = $env["pendientes"] - $total["total"];

            $this->db->where_in("id", (int) $user["idusuario"]);
            $this->db->update("usuarios", $upda);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $cont;
        }
    }

}
