<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
// header('Content-type: text/html; charset=UTF-8');
require_once (constant('PATH_CLASS') . "BLEjecutor.class.php");
require_once (constant('PATH_CLASS') . "Validator.class.php");
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
if (md5("ajax_edit_relacion") == $Accion) {
    GuardarRelacion();
    exit();
}
if (md5("ajax_del") == $Accion) {
    Eliminar();
    exit();
}

?>
<?php


function GuardarRelacion($tipo)
{
    $bret = false;
    $t01_id_inst = $_POST['t01_id_inst'];
    $nombre_ins = $_POST['t01_nom_inst'];
    $objEjec = new BLEjecutor();
    $bret = $objEjec->EjecutorGuardarRelacion($t01_id_inst);
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Se actualizó correctamente la relación entre la institución " . $nombre_ins . " y Fondoempleo  !!!");
    } else {
        echo ("ERROR : \n" . $objEjec->GetError());
    }
    return $bret;
}

function Guardar($tipo)
{
    $objEjec = new BLEjecutor();
    $bret = false;
    $t01_id_inst = "";
    $isValidRuc = Validator::validateRuc($_POST['t01_ruc_inst']);
    $isValidFDate = Validator::validateDate($_POST['t01_fch_fund']);
    
    ob_clean();
    ob_start();
    
    if ($isValidRuc !== true) {
        echo ("ERROR : \n" . ($isValidRuc === false ? "Número de RUC no es válido." : $isValidRuc));
    } elseif (! $isValidFDate) {
        echo ("ERROR : \n" . "Fecha de Fundación no es válida.");
    } else {
        if ($tipo == md5("ajax_new")) {
            $bret = $objEjec->EjecutorNuevo($t01_id_inst);
        }
        
        if ($tipo == md5("ajax_edit")) {
            // $t01_id_inst = $_POST['t01_id_inst'];
            $bret = $objEjec->EjecutorActualizar($t01_id_inst);
        }
        if ($bret)
            echo ("Exito " . $t01_id_inst . "   Se Guardaron los Datos correctamente !!!");
        else
            echo ("ERROR : \n" . $objEjec->GetError());
    }
    
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $t01_id_inst = $_POST['id'];
    $objEjec = new BLEjecutor();
    $bret = $objEjec->EjecutorEliminar($t01_id_inst);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . $t01_id_inst . "] !!!");
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