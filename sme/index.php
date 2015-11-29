<?php 
include 'includes/constantes.inc.php'; 
include 'includes/validauser.inc.php'; 

if ($ObjSession->Authorized()) {
    $objFunc->Redirect('default.php');
    
} else {
    $objFunc->Redirect('login.php');
    
}
