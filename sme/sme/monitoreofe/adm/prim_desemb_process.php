<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLFE.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar") == $Accion) {
    GuardarAprobDesembolso();
    exit();
}

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarAprobDesembolso()
{
    $objFE = new BLFE();
    $bret = false;
    $retcodigo = 0;
    // $aMonto = (float)preg_replace('/[^0-9\.]*/s', '', $_POST['txt_mto_desemb']);
    
    // if ($aMonto > 0)
    $bret = $objFE->Aprobacion_Primer_Desemb_Actualizar($retcodigo);
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        // if ($aMonto <= 0)
        // echo("ERROR : \nMonto a desembolsar debe ser mayor que cero." . $_POST['txt_mto_desemb']);
        // else
        echo ("ERROR : \n" . $objFE->GetError());
    }
    return $bret;
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