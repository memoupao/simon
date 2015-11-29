<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLProyecto.class.php");
// require(constant('PATH_CLASS')."Functions.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLTablas.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarSectorProd($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarSectorProd();
    exit();
}

if (md5("lista_subsector") == $Accion) {
    ListadoSubSectores();
    exit();
}

if (md5('lista_sector_main' == $Action)) {
    ListadoSectoresMain();
    exit;
}

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarSectorProd($tipo)
{
    $objProy = new BLProyecto();
    $bret = false;
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objProy->SectorProdNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objProy->SectorProdActualizar();
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
    $bret = $objProy->SectorProdEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Sector Productivo para el Proyecto !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

// istado de Subsectores
function ListadoSubSectores()
{
    $Fun = new Functions();
    
    $objTablas = new BLTablasAux();
    $sector = $Fun->__Request('sector');
    
    $rsSubsector = $objTablas->SubSectoresProductivos($sector);
    echo ("<option value='' selected></option>");
    $Fun->llenarCombo($rsSubsector, 'codigo', 'descripcion', '1');
    $Fun = NULL;
    $objTablas = NULL;
}


function ListadoSectoresMain()
{
    $Fun = new Functions();
    
    $objTablas = new BLTablas();
    $sector = $Fun->__Request('sector');
    
    $rsSubsector = $objTablas->SectoresGenerales($sector);
    echo ("<option value='' selected></option>");
    $Fun->llenarCombo($rsSubsector, 'codigo', 'descripcion', '1');
    
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