<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLPagoProv.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if (md5("ajax_importar_file_xls") == $Accion) {
    
    CargarArchivoXLS();
    exit();
}

if (md5("ajax_validar_xls_bd") == $Accion) {
    ValidarXLS();
    exit();
}

?>
<?php
// aso 1 : Cargar Archivo de Proyectos
function CargarArchivoXLS()
{
    $objPP = new BLPagoProv();
    $bret = false;
    $urlFile = '';
    $bret = $objPP->ImportarXLS($urlFile);
    
    ob_clean();
    ob_start();
    
    $oFn = new Functions();
    if ($bret) {
        $HardCode = "alert('Archivo Cargado correctamente'); \n";
        $HardCode .= "parent.SuccessCargarFormatXLS('" . $urlFile . "'); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objPP->Error . "'); \n";
        $HardCode .= "parent.SuccessCargarFormatXLS(''); \n";
        $oFn->Javascript($HardCode);
    }
    return $bret;
}

function ValidarXLS()
{
    $objPP = new BLPagoProv();
    
    $bret = false;
    
    $bret = $objPP->ValidaXLS();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito");
    } else {
        echo ("ERROR : \n" . $objPP->GetError());
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