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
    GuardarPersonal($Accion);
    exit();
}
if (md5("ajax_del") == $Accion) {
    EliminarPersonal();
    exit();
}
if (md5("ajax_save_TDR") == $Accion) {
    AdjuntarTDR();
    exit();
}

if (md5("save_fuentes_financ") == $Accion) {
    GuardarFuentesFinanc();
    exit();
}

?>
<?php


function GuardarPersonal($tipo)
{
    $objMan = new BLManejoProy();
    $bret = false;
    $idPer = 0;
    if ($tipo == md5("ajax_new")) {
        $bret = $objMan->Personal_Nuevo($idPer);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMan->Personal_Actualizar($idPer);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $idPer . " Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function EliminarPersonal()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->Personal_Eliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Personal Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function AdjuntarTDR()
{
    $objMan = new BLManejoProy();
    $oFn = new Functions();
    
    $bret = false;
    $bret = $objMan->Personal_AdjuntarTDR();
    
    ob_clean();
    ob_start();
    if ($bret) {
        $HardCode = "alert('" . "Se Adjunto correctamente el TDR !!!" . "'); \n";
        $HardCode .= "parent.EditarPersonal('" . $_POST['t03_id_per'] . "'); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objMan->GetError() . "'); \n";
    }
    return $bret;
}

function GuardarFuentesFinanc()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->Personal_ActualizarFTE();
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