<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php require(constant('PATH_CLASS')."BLManejoProy.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("save_gastos") == $Accion) {
    GuardarAportesFtes();
    exit();
}

?>
<?php


function GuardarAportesFtes()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->GastosAdm_Actualizar();
    if ($bret) {
        echo ("Exito Se Guardo correctamente, los montos registrados para Gastos de Administración !!!");
    } else {
        echo ("ERROR : \n" . $objMan->GetError());
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