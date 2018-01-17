<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class InformesModel extends MY_Model {

    public function __constructor() {
        parent::__construct();
        $this->load->database();
    }

    public function informeMes($data) {
        $where = '';
        $finicio = '';
        $ffinal = '';
        $cantidad = $data["cantidad"];

        if (isset($data["finicio"]) && !empty($data["finicio"])) {
            list($finicio, $hinicio) = explode(" ", $data["finicio"]);
        }
        if (isset($data["ffinal"]) && !empty($data["ffinal"])) {
            list($ffinal, $hfinal) = explode(" ", $data["ffinal"]);
        }
        if ($data["tiporeporte"] == 'consolidado') {
            $group = "GROUP BY us.usuario,car.nombre,to_char(fechaenvio,'yyyy-mm'),idbase";
            $campos = "car.nombre,upper(us.usuario) usuario,to_char(fechaenvio,'yyyy-mm') fecha,idbase,count(*)";
        } else {
            $group = '';
            $campos = "reg.numero,reg.mensaje,car.nombre,upper(us.usuario) usuario,to_char(fechaenvio,'yyyy-mm-dd') fecha,idbase,1";
        }

        $idusuario = $this->session->userdata("idusuario");

        if ($finicio != '' && $ffinal != '') {
            $where = " AND reg.fechaenvio BETWEEN '" . $finicio . " 00:00' AND '" . $ffinal . " 23:59' AND reg.idcontacto=" . $idusuario;
        }

        $idsector = $this->session->userdata("idsector");

//cambiar el estado para subir a produccion
        $sql = "
            SELECT 
                $campos
            FROM registros reg
                JOIN carrier car ON CAST(car.codigo as INT)=reg.idcarrier
                JOIN usuarios us ON us.id=reg.idcontacto
            WHERE reg.estado='1' $where 
            $group
            ORDER BY 1 limit $cantidad
                ";

        $this->session->set_userdata(array("sqlinforme" => $sql));
        $datos = $this->db->query($sql);
        return $datos->result_array();
    }

    public function informeConsumo($data) {
        $where = '';
        $finicio = '';
        $ffinal = '';
        $cantidad = $data["cantidad"];

        if (isset($data["finicio"]) && !empty($data["finicio"])) {
            list($finicio, $hinicio) = explode(" ", $data["finicio"]);
        }
        if (isset($data["ffinal"]) && !empty($data["ffinal"])) {
            list($ffinal, $hfinal) = explode(" ", $data["ffinal"]);
        }

        if ($finicio != '' && $ffinal != '') {
            $where = " AND to_char(reg.fechaenvio,'yyyy-mm-dd') BETWEEN '" . $finicio . "' AND '" . $ffinal . "'";
        }

        $idsector = $this->session->userdata("idsector");

        $sql = "
            SELECT 
                car.nombre,to_char(fechaenvio,'yyyy-mm'),upper(us.usuario),count(*) enviados,
                coalesce(us.cupo,0) - (coalesce(us.enviados,0) + coalesce(us.pendientes,0)) cupo
            FROM registros reg
                JOIN carrier car ON CAST(car.codigo as INT)=reg.idcarrier
                JOIN usuarios us ON us.id=reg.idcontacto
            WHERE (reg.estado='1' AND reg.idsector= '" . $idsector . "')$where
            GROUP BY reg.idcarrier,car.nombre,to_char(fechaenvio,'yyyy-mm'),us.usuario,us,cupo,us.enviados,us.pendientes
            ORDER BY 3 limit $cantidad
                ";
        $datos = $this->db->query($sql);
        return $datos->result_array();
    }

    public function informeOperador($data) {
        $where = '';
        $finicio = '';
        $ffinal = '';
        $cantidad = $data["cantidad"];

        if (isset($data["finicio"]) && !empty($data["finicio"])) {
            list($finicio, $hinicio) = explode(" ", $data["finicio"]);
        }
        if (isset($data["ffinal"]) && !empty($data["ffinal"])) {
            list($ffinal, $hfinal) = explode(" ", $data["ffinal"]);
        }

        if ($finicio != '' && $ffinal != '') {
            $where = " AND reg.fechaenvio BETWEEN '" . $finicio . "' AND '" . $ffinal . "'";
        }

        if ($data["tiporeporte"] == 'consolidado') {
            $group = "GROUP BY reg.idcarrier,car.nombre,to_char(fechaenvio,'yyyy-mm-dd')";
            $fecha = " to_char(fechaenvio,'yyyy-mm') ";
        } else {
            $group = '';
            $campos = ',1';
            $fecha = " to_char(fechaenvio,'yyyy-mm-dd') ";
        }

        $idsector = $this->session->userdata("idsector");

        $sql = "
            SELECT 
                car.nombre operador,$fecha,count(*) enviados
            FROM registros reg
                JOIN carrier car ON CAST(car.codigo as INT)=reg.idcarrier
                JOIN usuarios us ON us.id=reg.idcontacto
            WHERE (reg.estado='1'AND reg.idsector= '" . $idsector . "') $where
            GROUP BY reg.idcarrier,car.nombre,$fecha
            ORDER BY 2 limit $cantidad
                ";

        $datos = $this->db->query($sql);
        return $datos->result_array();
    }

    public function informeDia($data) {

        $where = '';
        if (!empty($data["finicio"])) {
            $finicio = explode(" ", $data["finicio"]);
            $fecha = $finicio[0];
        } else {
            $fecha = date("Y-m-d");
        }

        $cantidad = $data["cantidad"];
        $idsector = $this->session->userdata("idsector");
        $idusuario = $this->session->userdata("idusuario");

        $where = " AND reg.fechaenvio BETWEEN  '" . $fecha . " 00:00' AND '" . $fecha . " 23:59' AND reg.idcontacto=" . $idusuario;


        if ($data["tiporeporte"] == 'consolidado') {
            $group = "GROUP BY reg.idcarrier,car.nombre,to_char(fechaenvio,'yyyy-mm-dd'),us.usuario,idbase";
            $campos = "car.nombre operador,upper(us.usuario) usuario,to_char(fechaenvio,'yyyy-mm-dd') fecha,idbase codigo,count(*) cantidad";
        } else {
            $group = '';
            $campos = "reg.numero,reg.mensaje,car.nombre operador,upper(us.usuario) usuario,to_char(fechaenvio,'yyyy-mm-dd') fecha,idbase codigo,1 cantidad";
        }

        //cambiar el estado para subir a produccion
        $sql = "
            SELECT 
                $campos
            FROM registros reg
                JOIN carrier car ON CAST(car.codigo as INT)=reg.idcarrier
                JOIN usuarios us ON us.id=reg.idcontacto
            WHERE reg.estado='1' $where
            $group
            ORDER BY 3 limit $cantidad
                ";

//        print_r($sql);exit;
        $this->session->set_userdata(array("sqlinforme" => $sql));
        
        $datos = $this->db->query($sql);
        return $datos->result_array();
    }

}
