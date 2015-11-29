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
    GuardarAportes();
    exit();
}

?>
<?php


function GuardarAportes()
{
    ob_clean();
    ob_start();
    $objMan = new BLManejoProy();
    
    $bret = false;
    $bret = $objMan->LineaBaseImprevistos_Actualizar();
    if ($bret) {
        echo ("Exito Se Guardo correctamente, los montos registrados para Linea de Base e Imprevistos !!!");
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