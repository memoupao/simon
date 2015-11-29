<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
// header('Content-type: text/html; charset=UTF-8');
require (constant('PATH_CLASS') . "BLEquipo.class.php");
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

if (md5("new_solicitud_cp") == $Accion || md5("edit_solicitud_cp") == $Accion) {
    GuardarCambioPersonal($Accion);
    exit();
}

if (md5("ajax_partida_personal") == $Accion) {
    CargarPartidaPersonal();
    exit();
}

if (md5("ajax_del_solicitud") == $Accion) {
    EliminarSolicitud();
    exit();
}

?>
<?php


function Guardar($tipo)
{
    if (($_POST['t04_especialidad']) != '255') {
        $otros = '';
    } else {
        $otros = $_POST['t04_especialidad_otros'];
    }
    $t02_cod_proy = $_POST['t02_cod_proy'];
    $t04_id_equi = $_POST['t04_id_equi'];
    $t04_dni_equi = $_POST['t04_dni_equi'];
    $t04_ape_pat = $_POST['t04_ape_pat'];
    $t04_ape_mat = $_POST['t04_ape_mat'];
    $t04_nom_equi = $_POST['t04_nom_equi'];
    $t04_sexo_equi = $_POST['t04_sexo_equi'];
    $t04_edad_equi = $_POST['t04_edad_equi'];
    $t04_cali_equi = $_POST['t04_cali_equi'];
    $t04_telf_equi = $_POST['t04_telf_equi'];
    $t04_cel_equi = $_POST['t04_cel_equi'];
    $t04_mail_equi = $_POST['t04_mail_equi'];
    $t04_exp_lab = $_POST['t04_exp_lab'];
    $t04_func_equi = $_POST['t04_func_equi'];
    $t04_carg_equi = $_POST['t04_carg_equi'];
    $t04_fec_ini = $_POST['t04_fec_ini'];
    $t04_fec_ter = $_POST['t04_fec_ter'];
    $t04_esp = $_POST['t04_especialidad'];
    $t04_esp_otros = $otros;
    $t04_estado = $_POST['t04_estado'];
    
    $objEqui = new BLEquipo();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objEqui->ContactoNuevo($t02_cod_proy, $t04_id_equi, $t04_dni_equi, $t04_ape_pat, $t04_ape_mat, $t04_nom_equi, $t04_sexo_equi, $t04_edad_equi, $t04_cali_equi, $t04_telf_equi, $t04_cel_equi, $t04_mail_equi, $t04_exp_lab, $t04_func_equi, $t04_carg_equi, $t04_fec_ini, $t04_fec_ter, $t04_esp, $t04_estado, $t04_esp_otros);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $t04_id_equi = $_POST['t04_id_equi'];
        $bret = $objEqui->ContactoActualizar($t02_cod_proy, $t04_id_equi, $t04_dni_equi, $t04_ape_pat, $t04_ape_mat, $t04_nom_equi, $t04_sexo_equi, $t04_edad_equi, $t04_cali_equi, $t04_telf_equi, $t04_cel_equi, $t04_mail_equi, $t04_exp_lab, $t04_func_equi, $t04_carg_equi, $t04_fec_ini, $t04_fec_ter, $t04_esp, $t04_estado, $t04_esp_otros);
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
    $t02_cod_proy = $_POST['idProy'];
    $t04_id_equi = $_POST['id'];
    $objEqui = new BLEquipo();
    $bret = $objEqui->ContactoEliminar($t02_cod_proy, $t04_id_equi);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . $t04_id_equi . "] !!!");
    } else {
        echo ("ERROR : \n" . $objEqui->GetError());
    }
    return $bret;
}

function EliminarSolicitud()
{
    ob_clean();
    ob_start();
    $bret = false;
    $t02_cod_proy = $_POST['idProy'];
    $t04_num_soli = $_POST['id'];
    $objEqui = new BLEquipo();
    $bret = $objEqui->EliminarSolicitudPer($t02_cod_proy, $t04_num_soli);
    
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro [" . $t04_num_soli . "] !!!");
    } else {
        echo ("ERROR : \n" . $objEqui->GetError());
    }
    return $bret;
}

function CargarPartidaPersonal()
{
    $objEqui = new BLEquipo();
    $row = $objEqui->CambioPersonal_GetPartida($_REQUEST['idProy'], $_REQUEST['idPersonal']);
    echo (json_encode($row));
    return;
}

function GuardarCambioPersonal($tipo)
{
    $objEqui = new BLEquipo();
    $bret = false;
    if ($tipo == md5("new_solicitud_cp")) {
        $bret = $objEqui->CambioPersonal_Nuevo();
    }
    
    if ($tipo == md5("edit_solicitud_cp")) {
        // $t04_id_equi = $_POST['t04_num_soli'];
        $bret = $objEqui->CambioPersonal_Actualizar();
    }
    
    $oFn = new Functions();
    
    ob_clean();
    ob_start();
    if ($bret) {
        $CodeJS = "parent.ReturnGuardar(true, \"Se Guardo correctamente la Solicitud de Cambio de Personal\"); \n";
        $oFn->Javascript($CodeJS);
    } else {
        $CodeJS = "parent.ReturnGuardar(false, \"" . $objEqui->GetError() . "\"); \n";
        $oFn->Javascript($CodeJS);
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