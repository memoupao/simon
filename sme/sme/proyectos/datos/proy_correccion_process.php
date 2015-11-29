<?php
include ("../../../includes/constantes.inc.php");
include ("../../../includes/validauserxml.inc.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");

$Accion = $objFunc->__Request('action');

if (md5("sol_correccion_ml") == $Accion)
    correccion();
elseif (md5("sol_correccion_cron") == $Accion)
    correccion();
elseif (md5("sol_correccion_cronprod") == $Accion)
    correccion();
elseif (md5("sol_correccion_pre") == $Accion)
    correccion();
elseif (md5("sol_correccion_proy") == $Accion)
    correccion();
elseif (md5("sol_correccion_vbpr") == $Accion)
    correccion();
else{
    
    echo (Error());
    exit();
}

function correccion()
{
    $objProy = new BLProyecto();
    $idProy = $_REQUEST['idProy'];
    $mensaje = $_REQUEST['txtmensaje'];
    $tipo = $_REQUEST['txttipoaprueba'];
    
    $bret = $objProy->ProyectoSolicitudCorrecion($idProy, $tipo, $mensaje);
    
    ob_clean();
    ob_start();
    
    if ($bret)
        echo ("Exito" . " Se envió correctamente las Observaciones para la Corrección");
    else
        echo ("ERROR: \n" . $objProy->GetError());
    
    return $bret;
}

ob_end_flush();
