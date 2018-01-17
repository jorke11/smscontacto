<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronDescargaFTP extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministracionModel");
    }

    public function index() {
        // definir algunas variables
        $ftp_server = '200.43.192.44';
        $ftp_user_name = 'tracking2.colombia';
        $ftp_user_pass = 'x001mxjhHha';
        $hoy = date('Ymd');
        //$hoy = '20150710';
        
        // establecer una conexión básica
        $conn_id = ftp_connect($ftp_server);
        
        // iniciar sesión con nombre de usuario y contraseña
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        
        // Obtener los archivos contenidos en el directorio actual
        $contents = ftp_nlist($conn_id, "/natura/ok/".$hoy."*");
        
        // output $contents
        //var_dump($contents);
        
        for($i=0;$i<sizeof($contents);$i++)
        {
            $nom = explode("ok",$contents[$i]);
            $local_file = '/data/postgres/sql/archivos_natura'.$nom[1];
            $server_file = $contents[$i];
            // intenta descargar $server_file y guardarlo en $local_file
            if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
                echo "Se ha guardado satisfactoriamente en $local_file\n";
            } else {
                echo "Ha habido un problema\n";
            }
        }//fin for
        
        // cerrar la conexión ftp
        ftp_close($conn_id);
    }
}
