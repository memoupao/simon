<?php
session_cache_limiter('private');
session_cache_expire(1);

if (! isset($_SESSION)) {
    session_start();
}
header("Cache-Control: no-store, no-cache, must-revalidate");
require (constant('PATH_CLASS') . "MySQLDB.class.php");
require (constant('PATH_CLASS') . "BLSession.class.php");
require (constant('PATH_CLASS') . "Functions.class.php");

$con = new DB_mysql();
$con->conectar(constant('DB_NAME'), constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'));
$ObjSession = new BLSession($con->Conexion_ID);

// $ObjSession->UserID = "john.aima"; //-> Estableceer el usuario de conexion

$_SESSION['ObjSession'] = $ObjSession;
$con = NULL;

$objFunc = new Functions();

if (! $ObjSession->Authorized()) {
    // $urlIndex = constant('DOCS_PATH')."login.php?error=No ha iniciado Sesion" ;
    echo ("Sesion del Usuario, Terminada o no valida !!!");
    exit();
}

?>