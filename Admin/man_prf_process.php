<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>

<?php require(constant('PATH_CLASS')."BLMantenimiento.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarPerfil($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarPerfil();
    exit();
}

?>
<?php
// egion Mantenimiento de Perfiles
function GuardarPerfil($tipo)
{
    $objMante = new BLMantenimiento();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objMante->PerfilNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMante->PerfilActualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

function EliminarPerfil()
{
    ob_clean();
    ob_start();
    $objMante = new BLMantenimiento();
    
    $bret = false;
    $idPerfil = $_POST['id'];
    
    $bret = $objMante->PerfilEliminar($idPerfil);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Perfil [" . $idPerfil . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

// nd Region
?>

<?php ob_end_flush(); ?>