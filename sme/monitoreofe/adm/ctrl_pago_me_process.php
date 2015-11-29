<?php 
include("../../../includes/constantes.inc.php"); 
include("../../../includes/validauserxml.inc.php"); 

require (constant('PATH_CLASS') . "BLFE.class.php");

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar") == $Accion) {
    GuardarAprobDesembolso();
    exit();
}


// lan de Vistas de Monitoreo Externo
function GuardarAprobDesembolso()
{
    $objFE = new BLFE();
    $bret = false;
    $bret = $objFE->Aprobacion_Desemb_MonitorExterno_Actualizar();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objFE->GetError());
    }
    return $bret;
}

// ---
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

ob_end_flush();
