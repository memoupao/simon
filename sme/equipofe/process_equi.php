<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
// header('Content-type: text/html; charset=UTF-8');
require (constant('PATH_CLASS') . "BLFE.class.php");
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

?>
<?php


function Guardar($tipo)
{
    $objEqui = new BLFE();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objEqui->EquipoNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objEqui->EquipoActualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objEqui->GetError());
    }
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $idEquipo = $_POST['id'];
    $objEqui = new BLFE();
    $bret = $objEqui->EquipoEliminar($idEquipo);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . $idEquipo . "] !!!");
    } else {
        echo ("ERROR : \n" . $objEqui->GetError());
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