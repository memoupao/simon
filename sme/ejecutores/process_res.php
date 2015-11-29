<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLEjecutor.class.php");
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

if (md5("ajax_del_cuenta_bancaria") == $Accion) {
    EliminarCuentaBancaria();
    exit;
}


if (md5("ajax_carga_contacto") == $Accion) {
    CargarDatosContacto();
    exit();
}

?>
<?php


function Guardar($tipo)
{
    $t01_id_inst = $_POST['id'];
    $t01_cod_repres = $_POST['t01_cod_rep'];
    $new_cod_repres = $_POST['t01_nom_rep'];
    
    $objEjec = new BLEjecutor();
    
    $bret = false;
    if ($tipo == md5("ajax_new"))
        $bret = $objEjec->ResponsableNuevo($new_cod_repres, $t01_id_inst);
    
    ob_clean();
    ob_start();
    if ($bret)
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    else
        echo ("ERROR : \n" . $objEjec->GetError());
    
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $idInst = $_POST['t01_id_inst'];
    $idResp = $_POST['t01_cod_rep'];
    
    $objEjec = new BLEjecutor();
    $bret = $objEjec->ResponsableEliminar($idInst, $idResp);
    
    if ($bret)
        echo ("Exito Se Elimino correctamente el Registro ");
    else
        echo ("ERROR : \n" . $objEjec->GetError());
    return $bret;
}


// -------------------------------------------------->
// DA 2.0 [13-11-2013 10:02]
// Se adiciono la funcion EliminarCuentaBancaria para el borrado 
// de una cuenta bancaria.
function EliminarCuentaBancaria()
{
    ob_clean();
    ob_start();
    $bret = false;
    $idInst = $_POST['id'];
    $idCuenta = $_POST['t01_cod_rep'];
    
    $objEjec = new BLEjecutor();
    $bret = $objEjec->eliminarCuentaBancaria($idInst, $idCuenta);
    
    if ($bret)
        echo ("Exito Se Elimino correctamente el Registro ");
    else
        echo ("ERROR : \n" . $objEjec->GetError());
    return $bret;
}
// --------------------------------------------------<

function CargarDatosContacto()
{
    $objEqui = new BLEjecutor();
    $idInst = $_REQUEST['idInst'];
    $idCto = $_REQUEST['idCto'];
    $row = $objEqui->ContactosSeleccionar($idInst, $idCto);
    echo (json_encode($row));
    return;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

?>

<?php ob_end_flush(); ?>