<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuarios extends MY_Controller {

    public $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->library("reader");
        $this->tabla = 'usuarios';
    }

    public function index() {
        $data["perfiles"] = $this->AdministracionModel->buscar("perfiles  order by orden asc", '*');
        $data["sucursales"] = $this->AdministracionModel->buscar("centrocosto ", '*', "estado=1 AND codigo!='' order by codigo asc");
        $data["gerencias"] = $this->AdministracionModel->buscar("jerarquias", 'codigo,nombre', "tipo=1 and nombre !='' and estado=1 and nombre!=''");
        $data["planes"] = $this->AdministracionModel->buscar("planes", 'id,cantidad');

        $this->load->view("administracion/usuarios", $data);
    }

    public function cargaTabla() {
        $gerencia = '';
        $idgerencia = $this->session->userdata("idgerencia");
        $perfil = $this->session->userdata("idperfil");
        $join = " LEFT JOIN perfiles per ON per.id=us.idperfil ";
        $join.=" LEFT JOIN centrocosto cos ON cos.id=us.idcentrocosto ";
        $join .=" LEFT JOIN jerarquias jer ON jer.codigo=us.idjerarquia ";

        $campos = "us.id,us.usuario, initcap(per.perfil) perfil,jer.nombre,us.idsector,";
        $campos .="cos.codigo,jer.cupo cupogerencia,";
        $campos .="case WHEN jer.cupo- (select sum(cupo) + sum(adicion) from usuarios where idjerarquia=us.idjerarquia and estado=1)> 0 ";
        $campos .="THEN jer.cupo- (select sum(cupo) + sum(adicion) from usuarios where idjerarquia=us.idjerarquia and estado=1) ELSE 0 END cupodisponible,";
        $campos .="us.cupo cupouinicial, us.adicion, (us.enviados + us.pendientes) enviados,";
        $campos .="CASE WHEN (us.cupo - (coalesce(us.enviados, 0) + coalesce(pendientes, 0)) + coalesce(adicion, 0)) < 0 THEN 0 ELSE (us.cupo - (coalesce(us.enviados, 0) + coalesce(pendientes, 0)) + coalesce(adicion, 0)) END saldo";

        if ($perfil != 3) {
            $gerencia = (isset($idgerencia)) ? " idjerarquia = '" . $this->session->userdata("idgerencia") . "' AND idperfil!=3" : '';
        }
        $where = ($gerencia == '') ? 'us.estado=1' : $gerencia . " AND us.estado = 1 AND jer.nombre!='' Order by us.id";
        $datos = $this->AdministracionModel->buscar("usuarios us " . $join, $campos, $where);
        $datos = $this->datatable($datos);
        echo json_encode($datos);
    }

    public function cargaSectores($idext = NULL, $return = NULL) {
        $id = ($idext == NULL) ? $this->input->post("id") : $idext;
        if ($id != 0) {
            $where = "idpadre = '" . $id . "'";
            $consulta["sectores"] = $this->AdministracionModel->buscar("jerarquias", 'codigo valor,nombre texto', $where);
            $consulta["datos"] = $this->SaldoUsuario(NULL, $id);

            if ($return == NULL) {
                echo json_encode($consulta);
            } else {
                return $consulta;
            }
        }
    }

    public function obtieneUsuarioId() {
        $data = $this->input->post();
        $where = "us.id = " . $data["id"];
        $campos = 'us.id,us.usuario,us.idperfil,us.idjerarquia,us.idsector,us.cupo,us.referencia,';
        $campos.="us.estado, us.idcentrocosto, us.clave, ";
        $campos .="us.clave confirmacion,";
        $campos .="case WHEN jer.cupo- (select sum(cupo) + sum(adicion) from usuarios where idjerarquia=us.idjerarquia and estado=1)> 0 ";
        $campos .="THEN jer.cupo- (select sum(cupo) + sum(adicion) from usuarios where idjerarquia=us.idjerarquia and estado=1) ELSE 0 END cupodisponible,";
        $campos .="CASE WHEN (coalesce(us.cupo, 0)+coalesce(us.adicion, 0)-coalesce(us.enviados, 0)-coalesce(us.pendientes, 0))<0 THEN 0 ELSE ";
        $campos .=" (coalesce(us.cupo, 0)+coalesce(us.adicion, 0)-coalesce(us.enviados, 0)-coalesce(us.pendientes, 0)) END cupoactual";
        $campos .=", us.cupo cupoinicial";
        $join = " LEFT JOIN jerarquias jer ON jer.codigo = us.idjerarquia ";
        $response["usuario"] = $this->AdministracionModel->buscar("usuarios us " . $join, $campos, $where, "row");
        $response["sectores"] = $this->cargaSectores($response["usuario"]->idjerarquia, TRUE);
        echo json_encode($response);
    }

    public function gestionUsuarios() {
        $data = $this->asignaNull($this->input->post());
        $id = $data["id"];
        $this->tabla = 'usuarios';
        if (isset($data["simbolo"])) {
            $data["adicion"] = ($data["simbolo"] == "2") ? "-" . $data["adicion"] : $data["adicion"];
        }

        unset($data["simbolo"]);
        unset($data["id"]);
        unset($data["confirmacion"]);
        unset($data["cupodisponible"]);
        unset($data["cupoactual"]);
        $data["estado"] = (isset($data["estado"])) ? 1 : 0;
        $saldo = $this->SaldoUsuario(NULL, $data["idjerarquia"]);
        if ($id == '') {
            $data["cupo"] = (isset($data["cupo"])) ? $data["cupo"] : 0;
            $solicitud = $data["adicion"] + $data["cupo"];
            if ($solicitud <= $saldo["saldogerencia"]->cupodisponible) {
                $data["estado"] = 1;
                $where = "usuario = '" . $data["usuario"] . "' and estado = 1";
                $user = $this->AdministracionModel->buscar("usuarios", 'usuario', $where);
                if (count($user) > 0) {
                    $respuesta["respuesta"] = 'Usuario ya existe';
                    echo json_encode($respuesta);
                } else {
                    $data["clave"] = base64_encode($data["clave"]);
                    $ok = $this->AdministracionModel->insertar($this->tabla, $data);
                    $this->cargaDatos($ok, $this->tabla);
                }
            } else {
                echo json_encode(array("error" => 'Gerencia Sin cupo suficiente!'));
            }
        } else {

            $campos = "sum(cupo) + sum(adicion)-sum(enviados) - sum(pendientes) consumo,cupo";
            $where = "id=" . $id . " and estado=1 group by 2";
            $consumo = $this->AdministracionModel->buscar("usuarios", $campos, $where, 'row');
            $consumo = (count($consumo) > 0) ? $consumo->consumo : 0;

            $where = "clave = '" . $data["clave"] . "' AND id = " . $id . " and estado=1 group by 1";
            $campos = 'clave,coalesce(sum(adicion),0) adicion,sum(cupo) + sum(adicion)-sum(enviados) - sum(pendientes) consumo';
            $user = $this->AdministracionModel->buscar("usuarios", $campos, $where, 'row');

            $add = $this->AdministracionModel->buscar("usuarios", 'adicion', 'id=' . $id . ' and estado=1', 'row');

            $user = (count($user) > 0) ? $user : FALSE;
            $add = (count($add) > 0) ? $add->adicion : 0;

            $solicitud = $data["adicion"] + $consumo;
            if ($solicitud <= $saldo["saldogerencia"]->cupodisponible) {
                if ($user != FALSE) {
                    unset($data["clave"]);
                } else {
                    $data["clave"] = base64_encode($data["clave"]);
                }

                $adicion = ($user == FALSE) ? 0 : $user->adicion;

                $data["adicion"] = $data["adicion"] + $add;

                $this->AdministracionModel->update($this->tabla, $id, $data);
                $this->cargaDatos($id, $this->tabla);
            } else {
                echo json_encode(array("error" => 'Gerencia Sin cupo suficiente! Disponible: ' . $saldo["saldogerencia"]->cupodisponible));
            }
        }
    }

    public function adicionaCupo() {
        $data = $this->input->post();
        $idgerencia = $this->session->userdata("idgerencia");
        $saldo = $this->SaldoUsuario(NULL, $idgerencia);
        $datos = $this->AdministracionModel->buscar("usuarios", 'adicion', 'id=' . $data["id"], 'row');
        $data["simbolo"] = ($data["simbolo"] == '1') ? 1 : -1;
        $param ["adicion"] = $datos->adicion + ($data["simbolo"] * $data["adicion"]);


        if ($data["simbolo"] == '-1') {
            $campos = "cupo+coalesce(adicion,0)-coalesce(pendientes,0)-coalesce(enviados,0) total";
            $cupo = $this->AdministracionModel->buscar("usuarios", $campos, 'id=' . $data["id"]);
            
            if ($cupo[0]["total"] >= $data["adicion"]) {
                $ok = $this->AdministracionModel->update('usuarios', $data["id"], $param);
                $this->cargaDatos($ok, $this->tabla);
            } else {
                echo json_encode(array("error" => 'Problemas con la ejecución!'));
            }
        } else {
            unset($param["simbolo"]);
            if ($data["adicion"] <= $saldo["saldogerencia"]->cupodisponible) {
                $ok = $this->AdministracionModel->update('usuarios', $data["id"], $param);
                $this->cargaDatos($ok, $this->tabla);
            } else {
                echo json_encode(array("error" => 'La gerencia no cuenta con cupo Suficiente!'));
            }
        }
    }

    public function borrar() {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $ok = $this->AdministracionModel->update($data["tabla"], $data["id"], array("estado" => 0));
            echo (is_numeric($ok)) ? $ok : FALSE;
        }
    }

    public function cargaDatos($idext, $tabla = null) {
        $data = $this->input->post();
        $id = ($idext != '') ? $idext : $data["id"];
        $where = "id = " . $id;
        $datos = $this->AdministracionModel->buscar($tabla, '*', $where, 'row');

        echo json_encode($datos);
    }

    public function subeArchivo() {
        $nombreArchivo = str_replace(" ", "_", $_FILES["fileusuarios"]["name"]);
        $archivo = $_FILES["fileusuarios"]["tmp_name"];
        $datos = new Spreadsheet_Excel_Reader();
        $error = $datos->read($archivo);
        $act = 0;
        $ins = 0;
        foreach ($datos->sheets[0]['cells'] as $i => $value) {
            if ($i > 1) {

                $user = $this->AdministracionModel->buscar('usuarios', 'id', "usuario ilike '" . $value[1] . "'", 'row');
                $campos = "cupo - (coalesce(enviados, 0) + coalesce(pendientes, 0)) ++coalesce(adicion, 0) saldo";
                $cupo = $this->AdministracionModel->buscar('usuarios', $campos, "idjerarquia = '" . $value[4] . "'", 'row');

                if (COUNT($cupo) > 0) {
                    if ($value[7] > $cupo->cupo) {
                        $param["cupo"] = 0;
                    } else {
                        $param["cupo"] = (isset($value[7])) ? $value[7] : 0;
                    }
                } else {
                    $param["cupo"] = (isset($value[7])) ? $value[7] : 0;
                }

                $param["usuario"] = $value[1];
                $perfil = $this->AdministracionModel->buscar('perfiles', 'id', "perfil ilike '" . $value[3] . "'", 'row');
                $param["clave"] = ($value[2] == '') ? '123' : $value[2];
                $param["clave"] = base64_encode($value[2]);
                $param["idperfil"] = (COUNT($perfil) > 0) ? $perfil->id : 0;

                $param["adicion"] = (isset($value[8])) ? $value[8] : 0;
                $param["estado"] = ($value[11] == 'Activo') ? 1 : 0;
                $param["idjerarquia"] = (isset($value[4])) ? $value[4] : 0;
                $param["idsector"] = (isset($value[5])) ? $value[5] : 0;
                $centrocosto = (isset($value[6])) ? $value[6] : 0;

                if ($centrocosto != 0) {
                    $centro = $this->AdministracionModel->buscar('centrocosto', 'id', "codigo = '" . $centrocosto . "'", 'row');
                } else {
                    $centro = null;
                }

                $param["idcentrocosto"] = (count($centro) > 0) ? $centro->id : 0;
                if (count($user) > 0) {
                    $ok = $this->AdministracionModel->update('usuarios', $user->id, $param);
                    if ($ok) {
                        $act++;
                    }
                } else {
                    $ok = $this->AdministracionModel->insertar('usuarios', $param);
                    if ($ok) {
                        $ins++;
                    }
                }
            }
        }
        $respuesta["insertados"] = $ins;
        $respuesta["actualizados"] = $act;
        echo json_encode($respuesta);
    }

    public function muestraCupo() {
        $gerencia = $this->SaldoUsuario($_SESSION["idusuario"], $_SESSION["idgerencia"]);
        echo json_encode($gerencia);
    }

    public function Clave() {
        $data = $this->input->post();
        $datos = $this->AdministracionModel->buscar("usuarios", 'clave', "id = '" . $data["id"] . "'", 'row');
        echo json_encode(array("cont" => base64_decode($datos->clave)));
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */