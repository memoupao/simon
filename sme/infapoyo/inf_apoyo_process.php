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

if (md5("ajax_save_Documento") == $Accion) {
    GuardarDocumento();
    exit();
}

function GuardarDocumento()
{
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $resumen = $_POST['resumen'];
    $edicion = $_POST['edicion'];
    $sector = $_POST['sector'];
    $clave = $_POST['clave'];
    $tipoarch = $_POST['tipoarch'];
    $arch = $_POST['arch'];
    
    $objApoyo = new BLApoyo();
    $oFn = new Functions();
    $bret = false;
    
    $bret = $objApoyo->GuardarDocumentoApoyo($titulo, $autor, $resumen, $edicion, $sector, $clave, $tipoarch, $arch);
    print_r($objApoyo);
    
    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardo correctamente el Documento !!!" . "'); \n";
        $HardCode .= "parent.spryPopupDialogDocumento.displayPopupDialog(false); \n";
        $HardCode .= "location.href='inf_biblio_list.php?action=" . md5('mostrar_Todos') . "'; \n";
        
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objApoyo->GetError() . "'); \n";
        $oFn->Javascript($HardCode);
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