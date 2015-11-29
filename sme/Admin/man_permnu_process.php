<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>

<?php require(constant('PATH_CLASS')."BLMantenimiento.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("guardar_accesos") == $Accion) {
    Guardar_Menu_Perfil();
    exit();
}

?>
<?php
// egion Mantenimiento de Menu x Perfiles
function Guardar_Menu_Perfil()
{
    $objMante = new BLMantenimiento();
    $bret = false;
    $bret = $objMante->MenuPerfil_Actualizar();
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

// nd Region
?>

<?php ob_end_flush(); ?>