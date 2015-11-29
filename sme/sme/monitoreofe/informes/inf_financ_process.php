<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLMonitoreoFinanciero.class.php");
require_once (constant('PATH_CLASS') . "Validator.class.php");
// require(constant('PATH_CLASS')."HardCode.class.php");
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
if (md5("ajax_del_cab") == $Accion) {
    EliminarInformeCab();
    exit();
}
if (md5("ajax_del") == $Accion) {
    EliminarInforme();
    exit();
}

if (md5("ajax_evaluacion_docs") == $Accion) {
    GuardarEvaluacionDocumentaria();
    exit();
}

if (md5("ajax_coment_avance_presup") == $Accion) {
    GuardarComentariosAvancePresup();
    exit();
}

if (md5("ajax_gastos_no_aceptados") == $Accion) {
    GuardarGastosNoAceptados();
    exit();
}

if (md5("ajax_coment_avance_fisico") == $Accion) {
    GuardarComentariosAvanceFisico();
    exit();
}

if (md5("ajax_conclusiones") == $Accion) {
    GuardarConclusiones();
    exit();
}

if (md5("ajax_comentarios") == $Accion) {
    GuardarComentarios();
    exit();
}

if (md5("ajax_obtener_subact") == $Accion) {
    ObtenerComentariosSubAct();
    exit();
}

if (md5("ajax_lista_informes_mf_financ") == $Accion) {
    Listado_Informes_MF_Finac();
    exit();
}

// ObtenerComentariosSubActividades

if (md5("ajax_excedentes_ejecutar") == $Accion) {
    GuardarExcedentesEjecutar();
    exit();
}

if (md5("ajax_save_anexos") == $Accion) {
    GuardarAnexos();
    exit();
}

if (md5("ajax_del_anexos") == $Accion) {
    EliminarAnexos();
    exit();
}

?>
<?php
// nforme Mensual - Cabecera
function GuardarInforme($tipo)
{
    $isValidDate = Validator::validateDate($_POST['fchpres']);
    
    ob_clean();
    ob_start();
    
    if (! $isValidDate) {
        echo ("ERROR : \n" . "Fecha de Presentación no es válida.");
        return false;
    }
    
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $NumeroInforme = 0;
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->InformeMF_NuevoCab($NumeroInforme);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->InformeMF_ActualizaCab($NumeroInforme);
    }
    
    if ($bret) {
        echo ("Exito" . $NumeroInforme . " Se guardó correctamente, los datos del Informe !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EliminarInforme()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $NumeroInforme = 0;
    $bret = false;
    $bret = $objInf->InformeFinancieroEliminarCab($NumeroInforme);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Informe Financiero del Mes Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// eliminar informe de evaluacion financiera de fondoempleo
function EliminarInformeCab()
{
    ob_clean();
    ob_start();
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeFinancieroEliminarCab();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Registro");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// valuacion de Documentos Manejados por la Institución
function GuardarEvaluacionDocumentaria()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeMF_Guardar_EvaluacionDocs();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Documentos presentados por la institución !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// omentarios: Avance de Gastos
function GuardarComentariosAvancePresup()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeMF_Guardar_ComentariosPresup();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los comentarios.");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// omentarios: Avance Fisico
function GuardarComentariosAvanceFisico()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeMF_Guardar_ComentariosFisico();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los comentarios.");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarGastosNoAceptados()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeMF_Guardar_GastosNoAceptados();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Gastos No aceptados para la SubActividad !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarConclusiones()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeMF_Guardar_Conclusiones();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se guardó correctamente las Conclusiones del Monitor Financiero !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarComentarios()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $bret = $objInf->InformeMF_Guardar_Comentarios();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se guardó correctamente los Comentarios del Monitor Financiero !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function ObtenerComentariosSubAct()
{
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    
    $t1 = $objInf->ObtenerComentariosSubActividades($_REQUEST['t02_cod_proy'], $_REQUEST['t51_num'], 1);
    $t2 = $objInf->ObtenerComentariosSubActividades($_REQUEST['t02_cod_proy'], $_REQUEST['t51_num'], 2);
    
    ob_clean();
    ob_start();
    $return = ("Avance Presupuestal:\r\n" . trim($t1 . "\r\n\r\n") . "Avance Fisico:\r\n" . trim($t2));
    echo ($return);
    
    return;
}

function GuardarExcedentesEjecutar()
{
    $objInf = new BLMonitoreoFinanciero();
    
    $bret = false;
    $bret = $objInf->InformeMF_Excedentes();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Excedentes por ejecutar !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarAnexos()
{
    $objInf = new BLMonitoreoFinanciero();
    $oFn = new Functions();
    
    $bret = false;
    $bret = $objInf->InformeMF_GuardarAnexos();
    
    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Anexos del Informe de Monitoreo Financiero !!!" . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
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
    $objInf = new BLMonitoreoFinanciero();
    $bret = false;
    $bret = $objInf->InformeMF_EliminarAnexos();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Anexo del Informe de Monitoreo Financiero !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function Listado_Informes_MF_Finac()
{
    ob_clean();
    ob_start();
    $t02_cod_proy = $_REQUEST['idProy'];
    $idNumInf = - 1;
    
    $objFunc = new Functions();
    $objFinanc = new BLMonitoreoFinanciero();
    $Rs = $objFinanc->Inf_MF_Listado($t02_cod_proy);
    $objFunc->llenarComboI($Rs, "num", "periodo", $idInf);
    $objFinanc = NULL;
    
    return true;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>