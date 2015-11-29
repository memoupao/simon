<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLProyecto.class.php");
require (constant('PATH_CLASS') . "BLBene.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
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

// istado de Provincias
function ListadoProvincias()
{
    $Fun = new Functions();
    $objBene = new BLBene();
    
    $idproy = $Fun->__Request('idproy');
    $dpto = $Fun->__Request('dpto');
    
    $rsProv = $objBene->ListaUbigeoProv($idproy, $dpto);
    $Fun->llenarComboI($rsProv, 'idprov', 'prov', '0');
    $Fun = NULL;
    $objBene = NULL;
}
// istado de Provincias
function ListadoDistritos()
{
    $Fun = new Functions();
    $objBene = new BLBene();
    $idproy = $Fun->__Request('idproy');
    $dpto = $Fun->__Request('dpto');
    $prov = $Fun->__Request('prov');
    $rsProv = $objBene->ListaUbigeoDist($idproy, $dpto, $prov);
    $Fun->llenarComboI($rsProv, 'iddist', 'dist', '0');
    $Fun = NULL;
    $objBene = NULL;
}

// istado de Caserios
function ListadoCaserios()
{
    $Fun = new Functions();
    $objBene = new BLBene();
    $idproy = $Fun->__Request('idproy');
    $dpto = $Fun->__Request('dpto');
    $prov = $Fun->__Request('prov');
    $dist = $Fun->__Request('dist');
    $case = $Fun->__Request('case');
    
    $rsCase = $objBene->ListaUbigeoCaserio($idproy, $dpto, $prov, $dist);
    $Fun->llenarComboI($rsCase, 'idcase', 'caserio', $case);
    $Fun = NULL;
    $objBene = NULL;
}
// ---
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>