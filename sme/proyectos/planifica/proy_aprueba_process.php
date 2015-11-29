<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");

require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLMonitoreoFinanciero.class.php");

$Accion = $objFunc->__Request('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar_aprob_ml") == $Accion) {
    GuardarAprueba();
    exit();
}
// modificado 01/12/2011
if (md5("guardar_aprob_proy") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_vbpy") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_evml") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_evel") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_evcr") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_evpr") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_cron") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_pre") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_scp") == $Accion) {
    GuardarApruebaSCP();
    exit();
} // guardar aprobacion de cambio de personal
if (md5("guardar_aprob_reff") == $Accion) {
    GuardarApruebaEff();
    exit();
} // guardar aprobacion de cambio de personal
if (md5("guardar_aprob_ceff") == $Accion) {
    GuardarApruebaEff();
    exit();
} // guardar aprobacion de cambio de personal
if (md5("guardar_aprob_aeff") == $Accion) {
    GuardarApruebaEff();
    exit();
} // guardar aprobacion de cambio de personal
if (md5("guardar_aprob_srdg") == $Accion) {
    GuardarAprueba();
    exit();
} // guardar revision de datos generales
if (md5("guardar_aprob_ieva") == $Accion) {
    GuardarApruebadg();
    exit();
} // enviar info de datos generales a administracion

// -------------------------------------------------->
// AQ 2.0 [28-11-2013 17:15]
// Envío a Revisión y Aprobación
// del Cronograma de Productos
if (md5("guardar_aprob_evcrprod") == $Accion) {
    GuardarAprueba();
    exit();
}
if (md5("guardar_aprob_cronprod") == $Accion) {
    GuardarAprueba();
    exit();
}
// --------------------------------------------------<

/* Proceso de datos generales */
function GuardarApruebadg()
{
    $objProy = new BLProyecto();
    $bret = false;

    $idProy = $_REQUEST['idProy'];
    $mensaje = $_REQUEST['txtmensaje'];
    $tipo = $_REQUEST['txttipoaprueba'];

    $bret = $objProy->ProyectoSolicituddg($idProy, $tipo, $mensaje);

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se envió correctamente la solicitud");
    } else {
        echo ("ERROR: \n" . $objProy->GetError());
    }
    return $bret;
}
/* Fin de Proceso de datos generales */

// uardar - Costos Operativos
function GuardarAprueba()
{
    $objProy = new BLProyecto();
    $bret = false;

    $idProy = $_REQUEST['idProy'];
    $mensaje = $_REQUEST['txtmensaje'];
    $tipo = $_REQUEST['txttipoaprueba'];

    $bret = $objProy->ProyectoSolicitudAprobacion($idProy, $tipo, $mensaje);

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se envió correctamente la solicitud");
    } else {
        echo ("ERROR: \n" . $objProy->GetError());
    }
    return $bret;
}

function GuardarApruebaSCP()
{
    $objProy = new BLProyecto();
    $bret = false;

    $idProy = $_REQUEST['idProy'];
    $mensaje = $_REQUEST['txtmensaje'];
    $tipo = $_REQUEST['txttipoaprueba'];
    $scp = $_REQUEST['txtidscp'];

    $bret = $objProy->ProyectoSolicitudAprobacionSCP($idProy, $tipo, $mensaje, $scp);

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se envió correctamente la solicitud de Aprobación");
    } else {
        echo ("ERROR: \n" . $objProy->GetError());
    }
    return $bret;
}

function GuardarApruebaEff()
{
    $objProy = new BLMonitoreoFinanciero();
    $bret = false;

    $idProy = $_REQUEST['idProy'];
    $estado = $_REQUEST['estado'];
    $mensaje = $_REQUEST['txtmensaje'];
    $tipo = $_REQUEST['txttipoaprueba'];
    $eff = $_REQUEST['txteff'];

    $bret = $objProy->ProyectoSolicitudAprobacionEff($idProy, $tipo, $estado, $mensaje, $eff);

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se envió correctamente la solicitud de Aprobación");
    } else {
        echo ("ERROR____: \n" . $objProy->GetError());
    }
    return $bret;
}

function GuardarApruebaCron($mode)
{
    $objPresup = new BLPresupuesto();
    $bret = false;

    $bret = $objPresup->ActualizarFuentesFinan();

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito" . " Se guardó correctamente las Fuentes de Financiamiento.");
    } else {
        echo ("ERROR : \n" . $objPresup->GetError());
    }
    return $bret;
}

/* En caso de Error escribir en el Navegador, en color Rojo */
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>