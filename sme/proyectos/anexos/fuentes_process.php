<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLFuentes.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    Guardar($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    Eliminar();
    exit();
}

if (md5("ajax_lista_fte") == $Accion) {
    ListadoFuentes();
    exit();
}

?>
<?php


function Guardar($tipo)
{
    $t02_cod_proy = $_POST['t02_cod_proy'];
    $t01_id_inst = $_POST['t01_id_inst'];
    $t02_obs_fte = $_POST['t02_obs_fte'];
    
    $objFuentes = new BLFuentes();
    
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objFuentes->ContactoNuevo($t02_cod_proy, $t01_id_inst, $t02_obs_fte);
    }
    
    if ($tipo == md5("ajax_edit")) {
        
        $bret = $objFuentes->ContactoActualizar($t02_cod_proy, $t01_id_inst, $t02_obs_fte);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objFuentes->GetError());
    }
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $t02_cod_proy = $_POST['idProy'];
    $t04_id_equi = $_POST['id'];
    $objFuentes = new BLFuentes();
    $aCount = $objFuentes->CountFte($t02_cod_proy, $t04_id_equi);
    if ($aCount > 0) {
        echo ("ERROR : \nNo se puede eliminar una Fuente de Financiamiento con montos asignados.");
    } else {
        $bret = $objFuentes->ContactoEliminar($t02_cod_proy, $t04_id_equi);
        
        if ($bret)
            echo ("Exito Se Elimino correctamente el Registro [" . $t04_id_equi . "] !!!");
        else
            echo ("ERROR : \n" . $objFuentes->GetError());
    }
    return $bret;
}

function ListadoFuentes()
{
    ob_clean();
    ob_start();
    $t02_cod_proy = $_REQUEST['idProy'];
    $objFuentes = new BLFuentes();
    $objFunc = new Functions();
    $Rs = $objFuentes->ContactosListado($t02_cod_proy);
    $objFunc->llenarCombo($Rs, "t01_id_inst", "t01_sig_inst", '10');
    $objFuentes = NULL;
    
    return true;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

?>

<?php ob_end_flush(); ?>