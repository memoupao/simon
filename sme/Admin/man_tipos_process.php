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
    GuardarTipo($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarTipo();
    exit();
}

if (md5("ajax_llenar_combo") == $Accion) {
    LlenarCombos();
    exit();
}

?>
<?php
// egion Mantenimiento de Tipoes
function GuardarTipo($tipo)
{
    $objTablas = new BLTablasAux();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objTablas->TipoNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objTablas->TipoActualizar();
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

function EliminarTipo()
{
    ob_clean();
    ob_start();
    $objTablas = new BLTablasAux();
    
    $bret = false;
    $idTipo = $_POST['idTipo'];
    
    $bret = $objTablas->TipoEliminar($idTipo);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Tipo [" . $idTipo . "]!!!");
    } else {
        echo ("ERROR : \n" . $objTablas->GetError());
    }
    return $bret;
}

function LlenarCombos()
{
    ob_clean();
    ob_start();
    $bret = false;
    $Fun = new Functions();
    $params = $Fun->__Request('params');
    $nameFunc = $Fun->__Request('name');
    $sparams = "";
    
    if (is_array($params)) {
        for ($x = 0; $x < count($params); $x ++) {
            $sparams = "'" . $params[$x] . "',";
        }
    }
    
    if (strlen($sparams) > 3) {
        $sparams = substr($sparams, 0, strlen($sparams) - 1);
    }
    
    $retRS = NULL;
    $objTablas = new BLTablasAux();
    
    $evaluate = "\$retRS = \$objTablas->" . $nameFunc . "(" . $sparams . ");";
    header('Content-Type: text/html; charset=UTF-8');
    eval($evaluate);
    
    if (is_a($retRS, "mysqli_result")) {
        $Fun->llenarComboI($retRS, 'codigo', 'descripcion', '');
        exit();
    }
    
    if (is_a($retRS, "mysql_result")) {
        $Fun->llenarCombo($retRS, 'codigo', 'descripcion', '');
        exit();
    } else {
        print_r($retRS);
    }
    $Fun = NULL;
    $objTablas = NULL;
}

// nd Region

?>

<?php ob_end_flush(); ?>