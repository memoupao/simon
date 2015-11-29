<?php include("../../includes/constantes.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "Functions.class.php");

$objFunc = new Functions();
$nameDOC = "Pago Proveedores " . $objFunc->AnioActual() . $objFunc->MesActual() . $objFunc->DiaActual() . " .txt";
$BodyDOC = $objFunc->__Request('txtcontents');
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Transfer-Encoding: binary");
header("Cache-Control: private", false);
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . basename($nameDOC) . "\";");

ob_clean();
echo (stripcslashes($BodyDOC));
exit(0);

?>