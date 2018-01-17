<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists("debugger")) {

    function debugger($message) {
        $filepath = APPPATH . 'logs/debugger-' . date('Y-m-d') . '.txt';
        exec("chmod -R -f 0777 " . APPPATH . "logs/");

        if (!$fp = fopen($filepath, "a+")) {
            return FALSE;
        }

        $message = (is_array($message)) ? print_r($message, true) : $message;

        $message = "[" . date("Y-m-d H:i:s") . "] : " . $message . chr(13);

        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}
