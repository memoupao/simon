<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "Validator.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarCartaFianza($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarCartaFianza();
    exit();
}

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarCartaFianza($tipo)
{
    $objProy = new BLProyecto();
    $oFn = new Functions();
    $bret = false;
    
    $isValidGDate = Validator::validateDate($_POST['txtfecgir']);
    $isValidRDate = Validator::validateDate($_POST['txtfecrec']);
    $isValidVDate = Validator::validateDate($_POST['txtfecvenc']);
    
    ob_clean();
    ob_start();
    
    if (! $isValidGDate) {
        $CodeJS = "parent.ReturnGuardar(false, 'Fecha de Emisión no es válida'); \n";
    } elseif (! $isValidRDate) {
        $CodeJS = "parent.ReturnGuardar(false, 'Fecha de Recepción no es válida.'); \n";
    } elseif (! $isValidVDate) {
        $CodeJS = "parent.ReturnGuardar(false, 'Fecha de Vencimiento no es válida.'); \n";
    } else {
        if ($tipo == md5("ajax_new"))
            $bret = $objProy->CartaFianzaNuevo();
        
        if ($tipo == md5("ajax_edit"))
            $bret = $objProy->CartaFianzaActualizar();
        
        if ($bret)
            $CodeJS = "parent.ReturnGuardar(true, 'Se Guardo correctamente la Carta Fianza'); \n";
        else
            $CodeJS = "parent.ReturnGuardar(false, '" . $objProy->GetError() . "'); \n";
    }
    
    $oFn->Javascript($CodeJS);
    
    return $bret;
}

function EliminarCartaFianza()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    
    $bret = false;
    $bret = $objProy->CartaFianzaEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente la Carta Fianza del Proyecto !!!");
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

// ---
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>