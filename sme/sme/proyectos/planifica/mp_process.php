<?php 
include("../../../includes/constantes.inc.php"); 
include("../../../includes/validauserxml.inc.php"); 

require (constant('PATH_CLASS') . "BLManejoProy.class.php");


$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    // GuardarInforme($Accion);
    exit();
}




// nforme Mensual - Cabecera
function GuardarInforme($tipo)
{
    /*
     * $objInf = new BLInformes(); $bret = false; $retvs = 0; //Version del Informe if($tipo==md5("ajax_new")) { $bret = $objInf->InformeMINuevoCab($retvs); } if($tipo==md5("ajax_edit")) { $bret = $objInf->InformeMIActualizarCab($retvs); } ob_clean(); ob_start(); if($bret) { echo("Exito".$retvs." Se Guardaron los Datos correctamente !!!"); } else { echo("ERROR : \n".$objInf->GetError()); } return $bret;
     */
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

ob_end_flush(); ?>