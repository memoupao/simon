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
    GuardarEquipamiento($Accion);
    exit();
}
if (md5("ajax_del") == $Accion) {
    EliminarEquipamiento();
    exit();
}

if (md5("save_fuentes_financ") == $Accion) {
    GuardarFuentesFinanc();
    exit();
}

?>
<?php


function GuardarEquipamiento($tipo)
{
    $objMan = new BLManejoProy();
    $bret = false;
    $idEqui = 0;
    if ($tipo == md5("ajax_new")) {
        $bret = $objMan->Equipamiento_Nuevo($idEqui);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMan->Equipamiento_Actualizar($idEqui);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $idEqui . " Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
    }
    return $bret;
}

function EliminarEquipamiento()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->Equipamiento_Eliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Equipamiento Seleccionado !!!");
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
    $bret = $objMan->Equipamiento_ActualizarFTE();
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