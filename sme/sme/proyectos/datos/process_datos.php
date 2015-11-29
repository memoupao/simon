<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
?>
<?php

$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_fecha_mes") == $Accion) {
    CalculoFechaMes();
    exit();
}

if (md5("ajax_dif_fecha") == $Accion) {
    CalculoDiferenciaFecha();
    exit();
}

if (md5("ajax_fecha_amp") == $Accion) {
    CalculoFechaAmpliacion();
    exit();
}

if (md5("ajax_mes_amp") == $Accion) {
    CalculoMesAmpliacion();
    exit();
}

?>

<?php

function CalculoFechaMes()
{
    $fecha = $_REQUEST['fecha'];
    $numero = $_REQUEST['numero'];
    $oFunc = new Functions();
    $oFunc->oSession = $_SESSION['ObjSession'];
    $fecha2 = $oFunc->AgregarFecha($fecha, $numero, 'MONTH');
    ob_clean();
    echo $fecha2;
    return;
}

function CalculoFechaAmpliacion()
{
    $fecha = $_REQUEST['fecha'];
    $numero = $_REQUEST['numero'];
    $oFunc = new Functions();
    $oFunc->oSession = $_SESSION['ObjSession'];
    $fecha2 = $oFunc->FechaAmpliacion($fecha, $numero, 'MONTH');
    ob_clean();
    echo $fecha2;
    return;
}

function CalculoDiferenciaFecha()
{
    $fecha1 = $_REQUEST['fecha1'];
    $fecha2 = $_REQUEST['fecha2'];
    $oFunc = new Functions();
    $oFunc->oSession = $_SESSION['ObjSession'];
    $numero = $oFunc->DiferenciaFecha($fecha1, $fecha2, 'MONTH');
    ob_clean();
    echo $numero;
    return;
}

function CalculoMesAmpliacion()
{
    $fecha1 = $_REQUEST['fecha1'];
    $fecha2 = $_REQUEST['fecha2'];
    $oFunc = new Functions();
    $oFunc->oSession = $_SESSION['ObjSession'];
    $numero = $oFunc->DiferenciaFecha($fecha1, $fecha2, 'MONTH');
    ob_clean();
    echo $numero;
    return;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

?>



<?php ob_end_flush(); ?>