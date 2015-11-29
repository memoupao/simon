<?php
include ("../../../includes/constantes.inc.php");
include ("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLFE.class.php");

$Accion = $objFunc->__GET('action');
$mode = $objFunc->__GET('mode');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar") == $Accion) {
    GuardarEjecDesembolso($mode);
    exit();
}

if (md5("ajax_eliminar") == $Accion) {
    EliminarDesembolso();
    exit();
}

function GuardarEjecDesembolso($mode)
{
    $objFE = new BLFE();
    $bret = false;
    $retcodigo = 0;
    
    if ($mode == md5("ajax_new"))
        $bret = $objFE->Desembolso_Nuevo($retcodigo);
    else
        $bret = $objFE->Desembolso_Actualizar($retcodigo);
    
    ob_clean();
    ob_start();
    if ($bret)
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    else
        echo ("ERROR : \n" . $objFE->GetError());
    return $bret;
}

function EliminarDesembolso()
{
    ob_clean();
    ob_start();
    
    $objFE = new BLFE();
    $bret = false;
    
    $bret = $objFE->Desembolso_Eliminar();
    if ($bret)
        echo ("Exito Se Elimino correctamente el Desembolso Seleccionado !!!");
    else
        echo ("ERROR : \n" . $objFE->GetError());
    
    $objFE = NULL;
    return $bret;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

ob_end_flush();
?>