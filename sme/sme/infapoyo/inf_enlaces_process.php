<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLApoyo.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarInforme($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarInforme();
    exit();
}

if (md5("ajax_save_Pagina") == $Accion) {
    GuardarPagina();
    exit();
}

function GuardarPagina()
{
    $url = $_POST['url'];
    $titulo = $_POST['titulo'];
    $resumen = $_POST['resumen'];
    $email = $_POST['email'];
    
    $objApoyo = new BLApoyo();
    $oFn = new Functions();
    $bret = false;
    
    $bret = $objApoyo->GuardarPaginaApoyo($url, $titulo, $resumen, $email);
    
    ob_clean();
    ob_start();
    if ($bret) {
        
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardo correctamente la Pagina !!!" . "'); \n";
        $HardCode .= "parent.spryPopupDialogPagina.displayPopupDialog(false); \n";
        $HardCode .= "location.href='inf_enlaces_list.php?action=" . md5('mostrar_Todos') . "'; \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objInf->GetError() . "'); \n";
        // echo("ERROR : \n".$objInf->GetError());
    }
    return $bret;
}

function EliminarAnexos()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->EliminarAnexosInformeFinanc();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Anexo del Informe !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
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