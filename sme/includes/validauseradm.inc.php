<?php
if (! isset($_SESSION)) {
    session_start();
}

// *** Restrict Access To Page: Grant or deny access to this page
require (constant('PATH_CLASS') . "MySQLDB.class.php");
require (constant('PATH_CLASS') . "BLSession.class.php");
require (constant('PATH_CLASS') . "Functions.class.php");

$con = new DB_mysql();
$con->conectar(constant('DB_NAME'), constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'));
$ObjSession = new BLSession($con->Conexion_ID);
$_SESSION['ObjSession'] = $ObjSession;
$con = NULL;

$objFunc = new Functions();

if (! $ObjSession->Authorized()) {
    $urlIndex = constant('DOCS_PATH') . "login.php?error=No ha iniciado Sesion";
    $objFunc->Redirect($urlIndex);
    exit();
}

require (constant('PATH_CLASS') . "HardCode.class.php");
$objHC = new HardCode();
if ($ObjSession->PerfilID != $objHC->Admin) {
    $urlIndex = constant('DOCS_PATH') . "default.php?error=Pagina No Autorizada, para este Perfil.";
    $objFunc->Redirect($urlIndex);
    exit();
}
$objHC = NULL;

?>