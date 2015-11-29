<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>

<?php require(constant('PATH_CLASS')."BLTablasAux.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarSubTipo($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarSubTipo();
    exit();
}

if (md5("lista_tablas_aux3") == $Accion) {
    ListaTablasAux();
    exit();
}

?>
<?php
// egion Mantenimiento de SubTipoes
function GuardarSubTipo($tipo)
{
    $objTablas = new BLTablasAux();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objTablas->SubTipoNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objTablas->SubTipoActualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objTablas->GetError());
    }
    return $bret;
}

function EliminarSubTipo()
{
    ob_clean();
    ob_start();
    $objTablas = new BLTablasAux();
    
    $bret = false;
    $idSubTipo = $_POST['idSubTipo'];
    
    $bret = $objTablas->SubTipoEliminar($idSubTipo);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el SubTipo [" . $idSubTipo . "]!!!");
    } else {
        echo ("ERROR : \n" . $objTablas->GetError());
    }
    return $bret;
}
// nd Region

// istado de Tablas Auxiliares
function ListaTablasAux()
{
    ob_clean();
    ob_start();
    
    $objTablas = new BLTablasAux();
    $Fun = new Functions();
    $idTabla = $Fun->__Request('idTabla');
    $rsPaginas = $objTablas->ListadoTiposProdPrinc($idTabla);
    $Fun->llenarComboI($rsPaginas, 'codigo', 'descripcion', '0');
    $Fun = NULL;
    $objMante = NULL;
}

?>

<?php ob_end_flush(); ?>