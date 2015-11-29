<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLInformes.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarInformeUnicoAnual($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarInformeUnicoAnual();
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

if (md5("ajax_coment_avance_presup") == $Accion) {
    GuardarComentariosAvancePresup();
    exit();
}

if (md5("ajax_coment_avance_fisico") == $Accion) {
    GuardarComentariosAvanceFisico();
    exit();
}

if (md5("ajax_analisis_avances") == $Accion) {
    GuardarAnalisisAvances();
    exit();
}
if (md5("ajax_informacion_adicional") == $Accion) {
    GuardarInformacionAdicional();
    exit();
}
if (md5("ajax_envrev") == $Accion) {
    RevisarInformeUnico();
    exit();
}
?>
<?php
// nforme Unico Anual - Cabecera
function RevisarInformeUnico()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->InformeAnualRevisar();
    if ($bret) {
        echo ("Exito Se Envio a Revision el Informe Unico Anual!!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function GuardarInformeUnicoAnual($tipo)
{
    $objInf = new BLInformes();
    $bret = false;
    $retvs = 0; // Version del Informe
    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->InformeUnicoAnualCab($retvs);
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->InformeUnicoAnualUpdCab($retvs);
    }
    if ($tipo == md5("env_rev")) {
        $bret = $objInf->InformeUnicoAnualUpdCab($retvs);
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $retvs . " Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EliminarInformeUnicoAnual()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->InformeUnicoAnualEliminarCab();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Informe Unico Anual Seleccionado !!!");
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
        echo ("Exito Se Guardaron correctamente los avances para las SubActividades !!!");
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

// omentarios: Avance de Gastos
function GuardarComentariosAvancePresup()
{
    $objInf = new BLInformes();
    $bret = false;
    
    $bret = $objInf->InformeUA_Guardar_ComentariosPresup();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los comentarios del Monitor !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarComentariosAvanceFisico()
{
    $objInf = new BLInformes();
    $bret = false;
    
    $bret = $objInf->InformeUA_Guardar_ComentariosAFisico();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los comentarios del Monitor !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// nalisis de Avances
function GuardarAnalisisAvances()
{
    $objInf = new BLInformes();
    $aResult = $objInf->GuardarAnalisisAvancesIUA();
    
    ob_clean();
    ob_start();
    
    if ($aResult) {
        echo ("Exito Se Guardaron correctamente los Comentarios de Avances del Informe !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    
    return $aResult;
}

// alificaciones
function GuardarInformacionAdicional()
{
    $objInf = new BLInformes();
    
    $bret = false;
    $bret = $objInf->GuardarInfAdicionalUA();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se guardó correctamente la Información Adicional del Informe !!!");
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