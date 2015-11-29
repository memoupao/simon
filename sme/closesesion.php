<?php
include("includes/constantes.inc.php");
include("includes/validauser.inc.php");
$ObjSession->UpdateCloseseSesion();
$_SESSION['UserID'] = '';
unset($_SESSION['UserID']);
session_destroy();

setcookie('remember_sgp', '', time() - 60*60*24*30);
unset($_COOKIE['remember_sgp']);

$objFunc->Redirect("login.php");
