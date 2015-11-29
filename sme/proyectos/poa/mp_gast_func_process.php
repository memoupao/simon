<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLManejoProy.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarGastFunc($Accion);
    exit();
}
if (md5("ajax_del") == $Accion) {
    EliminarGastFunc();
    exit();
}

if (md5("ajax_new_gasto") == $Accion || md5("ajax_edit_gasto") == $Accion) {
    GuardarCosteoGastFunc($Accion);
    exit();
}
if (md5("ajax_del_gasto") == $Accion) {
    EliminarCosteoGastFunc();
    exit();
}

if (md5("save_fuentes_financ") == $Accion) {
    GuardarFuentesFinanc();
    exit();
}

?>
<?php


function GuardarGastFunc($tipo)
{
    $objMan = new BLManejoProy();
    $bret = false;
    $Partida = 0;
    if ($tipo == md5("ajax_new")) {
        $bret = $objMan->GastFunc_Nuevo($Partida);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMan->GastFunc_Actualizar($Partida);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $Partida . " Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function EliminarGastFunc()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->GastFunc_Eliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente la partida seleccionada !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function GuardarCosteoGastFunc($tipo)
{
    $objMan = new BLManejoProy();
    $bret = false;
    $Partida = 0;
    $Gasto = 0;
    
    if ($tipo == md5("ajax_new_gasto")) {
        $bret = $objMan->GastFunc_NuevoCosteo($Partida, $Gasto);
    }
    
    if ($tipo == md5("ajax_edit_gasto")) {
        $bret = $objMan->GastFunc_ActualizarCosteo($Partida, $Gasto);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function EliminarCosteoGastFunc()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->GastFunc_EliminarCosteo();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el gasto seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function GuardarFuentesFinanc()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->GastFunc_ActualizarFTE();
    if ($bret) {
        echo ("Exito Se Guardo correctamente, los montos asignados a las Fuentes de Financiamiento !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>