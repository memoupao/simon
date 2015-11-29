<?php
include ("../includes/constantes.inc.php");
error_reporting(0);
require ("../clases/MySQLDB.class.php");
require ("../lib/pjmail/Email.class.php");

$con = new DB_mysql();
$con->conectar(constant('DB_NAME'), constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'));

$instituciones = $con->ExecuteReaderSP('sp_lis_proyectos_monit', null);

while ($row = mysqli_fetch_assoc($instituciones)) {
    $eje = $con->ExecuteReaderSP("sp_upd_monitor", array(
        $row['t02_cod_proy']
    ));
}

$fecha_actual = date("Y-m-d");
$fa = explode('-', $fecha_actual);
$factual = mktime(0, 0, 0, $fa[1], $fa[2], $fa[0]);
$algo = $con->ExecuteReaderSP('sp_sel_mensajescp', NULL);

while ($rs = mysqli_fetch_assoc($algo)) {
    
    $mensaje = $rs["id_msj"];
    $fecha = $rs["t04_fec_ini"];
    $f = explode('-', $fecha);
    $fsoli = mktime(0, 0, 0, $f[1], $f[2], $fa[0]);
    $diferencia = ($factual - $fsoli) / (60 * 60 * 24);
    
    if ($diferencia >= 15) {
        
        $ejecutar = $con->ExecuteNonQuerySP("sp_upd_mensajescp", array(
            $rs["id_msj"]
        ));
    }
}

$irsMsg = $con->ExecuteReaderSP("sp_sel_mensajes", NULL);

echo ("<pre>");

$res = NULL;
$ret = NULL;
while ($row = mysqli_fetch_assoc($irsMsg)) {
    if ($row["ori_men"] != "" && $row["dest_men"] != "") {
        $mail = new Email();
        
        $mail->Asunto($row["asu_men"]);
        $mail->De($row["ori_men"], "");
        $mail->Para($row["dest_men"], "");
        $mail->Mensaje($row["tex_men"], true);
        $id = $row["id_men"];
        
        // -------------------------------------------------->
        // DA 2.0 [18-01-2014 17:49]
        // Deshabilitado el envio o ejecucion del proceso de envio de correo electronico.        
        /* $res = $mail->Enviar(); */
        // --------------------------------------------------<        
        $res = true;
        
        $mail = NULL;
        
        if ($res) {
            $ret = $con->ExecuteNonQuerySP("sp_upd_mensajes", array(
                $row["id_men"]
            ));
            if ($ret['numrows'] > 1) {
                $ret[$row["id_men"]] = true;
            } else {
                $ret[$row["id_men"]] = false;
            }
        }
    }
}
if ($ret) {
    echo ("Se Lograron Enviar los siguientes mensaje: <br>");
    print_r($ret);
} else {
    echo ("No se envio ningun mensaje");
}

echo ("</pre>");

ob_end_flush();

?>