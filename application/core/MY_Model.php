<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public $order;
    public $like;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("America/Bogota");
        $this->like = '';
        $this->order = '';
    }

    /**
     * 
     * @param string $campos ejemplo ='usuarios'
     * @return array
     */
    public function buscar($tabla, $campos, $where = NULL, $tipo = NULL) {

        $limit = ($tipo != "row") ? '' : 'limit 1';
        $where = ($where == NULL) ? '' : 'WHERE ' . $where;
        $sql = "
                SELECT $campos
                FROM $tabla
                $where $limit";
        $datos = $this->db->query($sql);

        if ($tipo == 'debug') {
            print_r($sql);
            exit;
        } else if ($tipo == 'row') {
            if ($datos != false) {
                return $datos->row();
            } else {

                $mensaje = "Es posible que por el tiempo de inactividad se pierdan datos y generer un posible error,";
                $mensaje.=" por favor Recargar la pagina!, de persistir el problema contactar con Soporte. ";
                $mensaje.='<a href="' . base_url() . '">Click aqui para Recargar</a>';
                show_error($mensaje);
                $datos = array();
            }
        } else if ($tipo == 'pre') {
            echo"<pre>";
            print_r($datos->result_array());
            echo"</pre>";
            exit;
        } else {
            return $datos->result_array();
        }
    }

    public function insertar($tabla, $datos, $debug = null) {

        $this->db->insert($tabla, $datos);
        if ($debug == 'debug') {
            print_r($this->db->last_query());
            exit;
        } else {
            return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : $this->db->last_query();
        }
    }

    /**
     * Metodo para actualizar registros
     * @param type $tabla ejem 'usuarios'
     * @param type $id '1'
     * @param type $datos = array('usuario'=>'xxxxx')
     * @param type $debug = para mostrar el sql
     * @return strinf
     */
    function update($tabla, $id, $datos, $debug = '') {
        $this->db->where('id', $id);
        $res = $this->db->update($tabla, $datos);

        if ($debug == 'debug') {
            print_r($this->db->last_query());
            $this->db->trans_rollback();
            exit;
        } else {

            return ($this->db->affected_rows() > 0) ? $id : $this->db->last_query();
        }
    }

    /**
     * Metodo para borrar registros
     * @param type $table
     * @param type $id
     * @param type $debug
     * @return type
     */
    function delete($table, $id, $debug = '') {
        $this->db->where('id', $id);
        $this->db->delete($table);
        if ($debug == 'debug') {
            print_r($this->db->last_query());
            exit;
        } else {
            return ($this->db->affected_rows() > 0) ? 'ok' : $this->db->query();
        }
    }

    public function logs($evento, $sql = NULL) {

        $archivo = $_SERVER['DOCUMENT_ROOT'] . '/logs/' . date("Y-m-d") . '.txt';

        $texto = date("Y-m-d H:i:s") . " ; " . $evento . " ; " . $sql . "\n";

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

    function dataTable($tabla, $join = NULL, $columnaslike, $columnasreal, $columnasfor, $where, $tipo = NULL) {

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->limit = "limit " . $iDisplayLength . " offset " . $iDisplayStart;
//            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
// Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, true);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, true);

                if ($bSortable == 'true') {
                    $this->order = " ORDER BY " . $iSortCol . " " . $sSortDir;
//                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */


        $columnaslike = explode(",", $columnaslike);
        $or = '';

        if (isset($sSearch) && !empty($sSearch)) {

            foreach ($columnaslike as $i => $value) {
                $this->like .= ($this->like == '') ? '' : ' OR ';
                $this->like .=' ' . trim($value) . ' ilike \'%' . trim($sSearch) . '%\' ';
            }
        }


//        $rResult = $this->ObtenerDatos($tabla, $like, $columnasreal);
        $tipo = ($tipo == NULL) ? NULL : $tipo;
//        $rResult = $this->Buscar($tabla . " " . $like . " " . $this->order . " " . $this->limit, $columnasreal, $where);
        $this->like = ($this->like == '') ? '' : ' WHERE ' . $this->like;
        $sql = "
              SELECT $columnasreal FROM datos $join $this->like $this->order $this->limit";
        $rResult = $this->ejecutar($sql);

// Data set length after filtering
        $iFilteredTotal = $this->buscar($tabla, 'max(id) ultimo', '', 'row');


// Total data set length
//        $iTotal = $this->db->count_all($sTable);
        $iTotal = $this->buscar($tabla, 'count(*) total', '', 'row');

// Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal->total,
            'iTotalDisplayRecords' => $iFilteredTotal->ultimo,
            'aaData' => array()
        );
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iFilteredTotal->ultimo,
            'iTotalDisplayRecords' => $iTotal->total,
            'aaData' => array()
        );

        $columnasfor = explode(",", $columnasfor);


        foreach ($rResult as $aRow) {
            $row = array();

            foreach ($columnasfor as $col) {
                $valor = ($aRow[trim($col)] == NULL) ? '' : $aRow[trim($col)];
                $row[] = $valor;
            }

            $output['aaData'][] = $row;
        }

        return $output;
    }

    public function ejecutar($sql, $print = NULL) {

        if ($print == 'debug') {
            print_r($sql);
            exit;
        } else {
            $datos = $this->db->query($sql);

            if ($print == 'row') {
                return $datos->row();
            } else if ($print == 'update') {
                if ($this->db->affected_rows() > 0) {
                    return $this->db->insert_id();
                } else {
                    echo $this->db->last_query();
                }
            } else {
                return $datos->result_array();
            }
        }
    }

}
