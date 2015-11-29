<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
// header('Content-type: text/html; charset=UTF-8');
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

?>
<?php


function Guardar($tipo)
{
    $t01_id_inst = $_POST['t01_id_inst'];
    $t01_dni_cto = $_POST['t01_dni_cto'];
    $t01_ape_pat = $_POST['t01_ape_pat'];
    $t01_ape_mat = $_POST['t01_ape_mat'];
    $t01_nom_cto = $_POST['t01_nom_cto'];
    $t01_fono_ofi = $_POST['t01_fono_ofi'];
    $t01_mail_cto = $_POST['t01_mail_cto'];
    $t01_mail_cto2 = $_POST['t01_mail_cto2'];
    $t01_cel_cto = $_POST['t01_cel_cto'];
    $t01_cgo_cto = $_POST['t01_cgo_cto'];
    
    $t01_tel2_cto = $_POST['t01_tel2_cto'];
    $t01_rpm_cto = $_POST['t01_rpm_cto'];
    $t01_fax_cto = $_POST['t01_fax_cto'];
    $t01_rpc_cto = $_POST['t01_rpc_cto'];
    $t01_nex_cto = $_POST['t01_nex_cto'];
    $t01_nex_cto = $_POST['t01_nex_cto'];
    $t01_sexo_cto = $_POST['t11_sexo'];
    
    $objEjec = new BLEjecutor();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objEjec->ContactoNuevo($t01_id_inst, $t01_dni_cto, $t01_ape_pat, $t01_ape_mat, $t01_nom_cto, $t01_fono_ofi, $t01_mail_cto, $t01_mail_cto2, $t01_cel_cto, $t01_cgo_cto, $t01_tel2_cto, $t01_rpm_cto, $t01_fax_cto, $t01_rpc_cto, $t01_nex_cto, $t01_sexo_cto);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $t01_id_cto = $_POST['t01_id_cto'];
        $bret = $objEjec->ContactoActualizar($t01_id_inst, $t01_id_cto, $t01_dni_cto, $t01_ape_pat, $t01_ape_mat, $t01_nom_cto, $t01_fono_ofi, $t01_mail_cto, $t01_mail_cto2, $t01_cel_cto, $t01_cgo_cto, $t01_tel2_cto, $t01_rpm_cto, $t01_fax_cto, $t01_rpc_cto, $t01_nex_cto, $t01_sexo_cto);
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
    $t01_id_inst = $_POST['idInst'];
    $t01_id_cto = $_POST['id'];
    $objEjec = new BLEjecutor();
    $bret = $objEjec->ContactoEliminar($t01_id_inst, $t01_id_cto);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . $t01_id_cto . "] !!!");
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