<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLInformes.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');
if (! $Action)
    $Action = $_GET['action'];

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

if (md5("ajax_ind_proposito") == $Accion) {
    GuardarIndicadoresProposito();
    exit();
}
if (md5("ajax_ind_componente") == $Accion) {
    GuardarIndicadoresComponente();
    exit();
}

// GuardarIndicadoresComponente

if (md5("ajax_indicadores_actividad") == $Accion) {
    GuardarIndicadoresActividad();
    exit();
}

if (md5("ajax_sub_actividad") == $Accion) {
    GuardarSubActividades();
    exit();
}

if (md5("ajax_analisis") == $Accion) {
    GuardarAnalisis();
    exit();
}

if (md5("ajax_anexos_fotograficos") == $Accion) {
    GuardarAnexosFotograficos();
    exit();
}

if (md5("ajax_del_anx_foto") == $Accion) {
    EliminarAnexoFotografico();
    exit();
}

if (md5("ajax_aprueba_mt") == $Accion) {
    AprobarInformeMT();
    exit();
}

// lanes Especificos
if (md5("save_plan_capac") == $Accion) {
    GuardarPlanCapacitacion();
    exit();
}

if (md5("save_plan_at") == $Accion) {
    GuardarPlanAT();
    exit();
}

if (md5("save_plan_cred") == $Accion) {
    GuardarPlanCreditos();
    exit();
}

if (md5("save_plan_otros") == $Accion) {
    GuardarPlanOtros();
    exit();
}

if (md5("ajax_count_benef") == $Action) {
    CountBenef();
    exit();
}

if (md5("ajax_envio_inf_trim") == $Accion) {
    EnviarInformeA();
    exit();
}
if (md5("ajax_envio_inf_trim_corr") == $Accion) {
    EnviarInformeCorr();
    exit();
}

?>
<?php
// nforme Mensual - Cabecera
function GuardarInforme($tipo)
{
    $objInf = new BLInformes();
    $bret = false;
    $retvs = 0; // Version del Informe
    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->InformeTrimNuevoCab($retvs);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->InformeTrimActualizarCab($retvs);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $retvs . " Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR :" . $retvs . " " . $objInf->GetError());
    }
    return $bret;
}

function EliminarInforme()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->InformeTrimEliminarCab();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Informe Trimestral Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// ---

// ndicadores de Proposito
function GuardarIndicadoresProposito()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarIndicadoresProposito();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Indicadores de Proposito !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// ndicadores de Componente
function GuardarIndicadoresComponente()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarIndicadoresComponente();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Indicadores de Componente !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// ndicadores de Actividad
function GuardarIndicadoresActividad()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarIndicadoresActividadTrim();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Indicadores de Actividad !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// ubActividades
function GuardarSubActividades()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarSubActividadesTrim();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los avances para las Actividades !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// nalisis
function GuardarAnalisis()
{
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->GuardarAnalisisTrim();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los datos del Informe Trimestral !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// nexos Fotograficos
function GuardarAnexosFotograficos()
{
    $objInf = new BLInformes();
    $oFn = new Functions();
    
    $bret = false;
    $bret = $objInf->GuardarAnexoFotograficoTrim();
    
    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Anexos del Informe !!!" . "'); \n";
        $HardCode .= "parent.LoadAnexosFotograficos(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objInf->GetError() . "'); \n";
        // echo("ERROR : \n".$objInf->GetError());
    }
    return $bret;
}

function EliminarAnexoFotografico()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->InformeEliminarAnxFotoTrim();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Anexo!!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// probacion VB del Informe, por el Monitor tematico
function AprobarInformeMT()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->InformeTrimAprobar();
    if ($bret) {
        echo ("Exito Se dio VB correctamente el Informe Trimestral Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarPlanCapacitacion()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarAvancePlanCapac();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Capacitación !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    
    return $bret;
}

function GuardarPlanAT()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarAvancePlanAT();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Asistencia Técnica !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    
    return $bret;
}

function GuardarPlanCreditos()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarAvancePlanCreditos();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Creditos !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    
    return $bret;
}

function GuardarPlanOtros()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarAvancePlanOtros();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Otros Servicios !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    
    return $bret;
}

function CountBenef()
{
    header("Content-Type: application/json");
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $aResult = $objInf->countInfBenef();
    // print_r ($aResult);
    echo json_encode($aResult);
}

function EnviarInformeA()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->EnviarCambioEstadoTrim();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Éxito se envió a " . $_REQUEST['nomestado'] . " el informe  Trimestral Técnico y Financiero!!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EnviarInformeCorr()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->EnviarCambioEstadoTrimCorr();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Éxito se envió a " . $_REQUEST['nomestado'] . " el informe  Trimestral Técnico Trimestral!!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// n Caso de Errores
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>