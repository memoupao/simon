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
    GuardarInforme($Accion);
    exit();
}

if (md5("ajax_edit_vb") == $Accion) {
    guardarInformeVB();
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarInforme();
    exit();
}

if (md5("ajax_indicadores_actividad") == $Accion) {
    GuardarIndicadoresActividad();
    exit();
}

if (md5("ajax_sub_actividad") == $Accion) {
    GuardarSubActividades();
    exit();
}

if (md5("ajax_problemas_soluc") == $Accion) {
    GuardarProblemasSoluc();
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

if (md5("ajax_envio_rev") == $Accion) {
    EnviarRevision();
    exit();
}

if (md5("ajax_envio_corr") == $Accion) {
    EnviarCorrecion();
    exit();
}

if (md5("ajax_envio_aprob") == $Accion) {
    EnviarAprobacion();
    exit();
}

?>
<?php
// nforme Mensual - Cabecera
function GuardarInforme($tipo)
{
    $objInf = new BLInformes();
    $bret = false;
    $vsinf = 0;
    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->InformeNuevoCab($vsinf);
    }

    if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->InformeActualizarCab($vsinf);
    }

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . $vsinf . " Se Guardaron los Datos correctamente");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function guardarInformeVB()
{
    $objInf = new BLInformes();
    $bret = false;
    $vsinf = 0;
    $bret = $objInf->InformeActualizarCab($vsinf);
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
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

    $bret = false;
    $bret = $objInf->InformeEliminarCab();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Informe del Mes Seleccionado");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// ---

// ndicadores de Actividad
function GuardarIndicadoresActividad()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->GuardarIndicadoresActividad();

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Indicadores de Actividad");
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
    $bret = $objInf->GuardarSubActividades();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los avances para las Actividades");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarProblemasSoluc()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->GuardarProblemasSoluciones();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los problemas y Soluciones");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarAnexosFotograficos()
{
    $objInf = new BLInformes();
    $oFn = new Functions();

    $bret = false;
    $bret = $objInf->GuardarAnexoFotografico();

    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Anexos del Informe" . "'); \n";
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
    $bret = $objInf->InformeEliminarAnxFoto();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Anexo del Informe");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EnviarRevision()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->EnviarCambioEstado();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Éxito se envió a Revisión  el informe Mensual");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EnviarCorrecion()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->EnviarCambioEstado();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Éxito se envió a Corrección  el informe Mensual Técnico");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EnviarAprobacion()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->EnviarCambioEstado();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Éxito se dió VB al Informe Mensual correctamente");
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