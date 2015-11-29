<?php
include ("../../../includes/constantes.inc.php");
include ("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLApprDesemb.class.php");

$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar") == $Accion) {
    GuardarAprobDesembolso();
    exit();
}

function GuardarAprobDesembolso()
{
    $objAppr = new BLApprDesemb();
    $retcodigo = 0;
    $bret = $objAppr->Aprobacion_Desemb_Persist($retcodigo);
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
        return $retcodigo;
    } else {
        echo ("ERROR : \n" . $objAppr->GetError());
        return false;
    }
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

ob_end_flush();
