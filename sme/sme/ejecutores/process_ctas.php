<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLEjecutor.class.php");

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

function Guardar($tipo)
{
    $objEjec = new BLEjecutor();
    
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objEjec->CtaBancaria_Nuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objEjec->CtaBancaria_Actualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objEjec->GetError());
    }
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $id = $_POST['t01_cod_rep'];
    
    $objEjec = new BLEjecutor();
    $bret = $objEjec->ResponsableEliminar($id);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . $id . "] !!!");
    } else {
        echo ("ERROR : \n" . $objEjec->GetError());
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