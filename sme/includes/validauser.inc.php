<?php
if (! isset($_SESSION)) {
    session_start();
}
// *** Restrict Access To Page: Grant or deny access to this page
require (constant('PATH_CLASS') . "MySQLDB.class.php");
require (constant('PATH_CLASS') . "BLSession.class.php");
require (constant('PATH_CLASS') . "Functions.class.php");

$con = new DB_mysql();
$con->conectar(DB_NAME, DB_HOST, DB_USER, DB_PWD);
$ObjSession = new BLSession($con->Conexion_ID);

$objFunc = new Functions();


// -------------------------------------------------->
// DA 2.0 [16-11-2013 23:11]
// Nueva opcion de Recordar accesos
if (isset($_COOKIE['remember_sgp']) && !empty($_COOKIE['remember_sgp'])) {
    $remember_sgp = $objFunc->decrypt($_COOKIE['remember_sgp']);
    $_SESSION["UserID"] = $remember_sgp;
    $ObjSession->UserID = $remember_sgp;
}
// --------------------------------------------------<




$objFunc->oSession = $ObjSession;
$_SESSION['ObjSession'] = $ObjSession;
$con = NULL;
$retmsg = "";
if (! $ObjSession->Authorized($retmsg)) {
    
    // -------------------------------------------------->
    // DA 2.0 [16-11-2013 23:11]
    // Nueva opcion de Recordar accesos son eliminados si existen
    // en caso de no tener acceso autorizado.
    setcookie('remember_sgp', '', time() - 60*60*24*30);
    unset($_COOKIE['remember_sgp']);
    // --------------------------------------------------<
    
    
    if ($retmsg != "") {
        echo ("<font style='color:red; font-size:11px; font-weight:bold;'><br><br>" . $retmsg . "</font>");
    }
    
    $urlIndex = constant('DOCS_PATH') . "login.php?lasturl=" . $_SERVER['PHP_SELF'] . "&error=" . $retmsg;
    $objFunc->Redirect($urlIndex);
    exit();
}
$queryString = "";

?>