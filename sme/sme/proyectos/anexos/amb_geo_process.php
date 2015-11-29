<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLProyecto.class.php");
// require(constant('PATH_CLASS')."Functions.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarAmbitoGeo($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarSectorProd();
    exit();
}

if (md5("lista_provincias") == $Accion) {
    ListadoProvincias();
    exit();
}

if (md5("lista_distritos") == $Accion) {
    ListadoDistritos();
    exit();
}

if (md5("lista_caserios") == $Accion) {
    ListadoCaserios();
    exit();
}

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarAmbitoGeo($tipo)
{
    $objProy = new BLProyecto();
    
    $bret = false;
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objProy->AmbitoGeoNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objProy->AmbitoGeoActualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function EliminarSectorProd()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    
    $bret = false;
    $bret = $objProy->AmbitoGeoEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Ambito Geografico para el Proyecto !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

// istado de Provincias
function ListadoProvincias()
{
    $Fun = new Functions();
    $objTablas = new BLTablasAux();
    $dpto = $Fun->__Request('dpto');
    
    $rsProv = $objTablas->ListaProvincias($dpto);
    echo ("<option value='' selected></option>");
    $Fun->llenarComboI($rsProv, 'codigo', 'descripcion', '0');
    $Fun = NULL;
    $objTablas = NULL;
}
// istado de Provincias
function ListadoDistritos()
{
    $Fun = new Functions();
    $objTablas = new BLTablasAux();
    $dpto = $Fun->__Request('dpto');
    $prov = $Fun->__Request('prov');
    $rsProv = $objTablas->ListaDistritos($dpto, $prov);
    echo ("<option value='' selected></option>");
    $Fun->llenarComboI($rsProv, 'codigo', 'descripcion', '0');
    $Fun = NULL;
    $objTablas = NULL;
}

// ---
function ListadoCaserios()
{
    $Fun = new Functions();
    $objTablas = new BLTablasAux();
    $dpto = $Fun->__Request('dpto');
    $prov = $Fun->__Request('prov');
    $dist = $Fun->__Request('dist');
    $case = $Fun->__Request('case');
    
    $rsCase = $objTablas->ListaCaserios($dpto, $prov, $dist);
    echo ("<option value='' selected></option>");
    $Fun->llenarComboI($rsCase, 'codigo', 'descripcion', $case);
    $Fun = NULL;
    $objTablas = NULL;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>