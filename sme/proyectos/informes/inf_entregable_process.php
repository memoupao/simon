<?php
/**
 * CticServices
 *
 * Procesa Datos del Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */

include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLInformes.class.php");

$Accion = $objFunc->__GET('action');
if (! $Action)
    $Action = $_GET['action'];

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    guardarCaratula($Accion);
    exit();
}

if (md5("ajax_edit_vb") == $Accion) {
    guardarInformeVB();
    exit();
}

if (md5("ajax_del") == $Accion) {
    eliminarInforme();
    exit();
}

if (md5("ajax_ind_proposito") == $Accion) {
    guardarIndicadoresProposito();
    exit();
}
if (md5("ajax_ind_componente") == $Accion) {
    guardarIndicadoresComponente();
    exit();
}

if (md5("ajax_indicadores_producto") == $Accion) {
    guardarIndicadoresProducto();
    exit();
}

if (md5("ajax_sub_actividad") == $Accion) {
    GuardarSubActividades();
    exit();
}

if (md5("ajax_analisis") == $Accion) {
    guardarAnalisis();
    exit();
}

if (md5("ajax_anexos") == $Accion) {
    guardarAnexos();
    exit();
}

if (md5("ajax_del_anx") == $Accion) {
    eliminarAnexo();
    exit();
}

if (md5("ajax_aprueba_mt") == $Accion) {
    AprobarInformeMT();
    exit();
}

if (md5("save_plan_capac") == $Accion) {
    guardarPlanCapacitacion();
    exit();
}

if (md5("save_plan_at") == $Accion) {
    guardarPlanAT();
    exit();
}

if (md5("save_plan_cred") == $Accion) {
    GuardarPlanCreditos();
    exit();
}

if (md5("save_plan_otros") == $Accion) {
    guardarPlanOtros();
    exit();
}

if (md5("ajax_count_benef") == $Action) {
    countBenef();
    exit();
}

/*
 * Revisión y Aprobación
 */
if (md5("ajax_envio_inf_entregable") == $Accion) {
    enviarInf();
    exit();
}
if (md5("ajax_envio_inf_entregable_corr") == $Accion) {
    enviarInformeCorr();
    exit();
}

function guardarCaratula($tipo)
{
    $objInf = new BLInformes();
    $bret = false;

    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->guardarCaratulaInfEntregable();
    } else if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->actualizarCaratulaInfEntregable();
    }

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito: Se guardaron los datos correctamente");
    } else {
        echo ("ERROR : " . $objInf->GetError());
    }
    return $bret;
}

function guardarInformeVB()
{
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->actualizarCaratulaInfEntregable();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function eliminarInforme()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->infEntregableEliminarCab();
    if ($bret) {
        echo ("Exito Se eliminó correctamente el Informe de Entregable Seleccionado");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// Indicadores de Proposito
function guardarIndicadoresProposito()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->guardarIndicadoresPropositoEntregable();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Indicadores de Propósito");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// Indicadores de Componente
function guardarIndicadoresComponente()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->guardarIndicadoresComponenteEntregable();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Indicadores de Componente");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// Indicadores de Producto
function guardarIndicadoresProducto()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->guardarIndicadoresProductoEntregable();

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se guardaron correctamente los Indicadores del Producto");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function guardarAnalisis()
{
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->guardarAnalisisEntregable();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los datos del Informe de Entregable");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// Anexos
function guardarAnexos()
{
    $objInf = new BLInformes();
    $oFn = new Functions();

    $bret = false;
    $bret = $objInf->guardarAnexoInfEntregable();

    ob_clean();
    ob_start();
    if ($bret) {
        $HardCode = "alert('" . "Se Guardaron correctamente los Anexos del Informe" . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objInf->GetError() . "'); \n";
    }
    return $bret;
}

function eliminarAnexo()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->eliminarAnexoInfEntregable();
    if ($bret) {
        echo ("Exito Se eliminó correctamente el Anexo");
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
        echo ("Exito Se dio VB correctamente el Informe Trimestral Seleccionado");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function guardarPlanCapacitacion()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->guardarAvancePlanCapacEntregable();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Capacitación");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }

    return $bret;
}

function guardarPlanAT()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->guardarAvancePlanATEntregable();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Asistencia Técnica");
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
        echo ("Exito Se Guardo correctamente los avances en Creditos");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }

    return $bret;
}

function guardarPlanOtros()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->guardarAvancePlanOtrosEntregable();
    if ($bret) {
        echo ("Exito Se Guardo correctamente los avances en Otros Servicios");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }

    return $bret;
}

function countBenef()
{
    header("Content-Type: application/json");
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $aResult = $objInf->countInfBenef();
    // print_r ($aResult);
    echo json_encode($aResult);
}

function enviarInf()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->enviarInfEntregable();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito se envió a " . $_REQUEST['nomestado'] . " el informe de Entregable");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

/*function enviarInformeCorr()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->EnviarCambioEstadoTrimCorr();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Éxito se envió a " . $_REQUEST['nomestado'] . " el informe de Entregable");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}*/
// n Caso de Errores
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>