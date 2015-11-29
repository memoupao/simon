<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLPOA.class.php");
require_once (constant('PATH_CLASS') . "BLPresupuesto.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar_costo_operativo") == $Accion) {
    $mode = $objFunc->__Request('mode');
    GuardarCostosOperativos($mode);
    exit();
}

if (md5("guardar_fuentes_financ_cost_ope") == $Accion) {
    GuardarCostosOperativosFtes();
    exit();
}

?>
<?php

// uardar - Costos Operativos
function GuardarCostosOperativos($mode)
{
    $objPresup = new BLPresupuesto();
    $bret = false;
    $retCodPago = 0;
    
    if ($mode == md5("new")) {
        $bret = $objPresup->NuevoCosteo($retCodPago);
    }
    
    if ($mode == md5("edit")) {
        $bret = $objPresup->ActualizarCosteo($retCodPago);
    }
    
    if ($mode == md5("delete")) {
        $bret = $objPresup->EliminarCosteo();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        if ($mode == md5("delete")) {
            echo ("Exito" . " Se eliminó correctamente el Gasto.");
        } else {
            echo ("Exito" . " Se guardó correctamente el Gasto indicado.");
        }
    } else {
        echo ("ERROR : \n" . $objPresup->GetError());
    }
    return $bret;
}

function GuardarCostosOperativosFtes($mode)
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