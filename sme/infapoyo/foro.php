<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php

require_once (constant("PATH_LIBRERIAS") . "phpbbclass/phpbb.class.php");

$phpbbPath = constant("APP_PATH") . "foros/";
$phpbb = new phpbb($phpbbPath);

$r = $ObjSession->VerificarUsuarioForo($ObjSession->UserID);

if ($r != NULL) {
    if ($r["pwd"] == "") {
        $msg = "Por favor cambie su password, para que pueda acceder al foro \n";
        $msg .= "<a href='" . constant("DOCS_PATH") . "login.php?changePWD=1" . "'>Clic Aqui para Cambiar su Password</a>";
        echo ($msg);
        exit();
    }
    
    $phpbb_vars = array(
        "username" => $r["username"],
        "password" => $r["pwd"]
    );
    $phpbb_result = $phpbb->user_login($phpbb_vars);
    
    if ($phpbb_result == 'SUCCESS') {
        $urlForo = constant("DOCS_PATH") . "foros/index.php?sid=" . $_SESSION["sid"];
        $objFunc->Redirect($urlForo);
        exit();
    } else {
        $phpbb_vars = array(
            "username" => $r["username"],
            "password" => $r["pwd"]
        );
        $phpbb_result = $phpbb->user_change_password($phpbb_vars);
        if ($phpbb_result == 'SUCCESS') {
            $phpbb_result = $phpbb->user_login($phpbb_vars);
            if ($phpbb_result == 'SUCCESS') {
                $urlForo = constant("DOCS_PATH") . "foros/index.php?sid=" . $_SESSION["sid"];
                $objFunc->Redirect($urlForo);
                exit();
            }
        }
    }
} else {
    
    $usu = $ObjSession->GetUsuario();
    $pass = $ObjSession->PWD($usu["coduser"]);
    
    if ($pass == "") {
        $msg = "<center style='color:red; font-size:12px'><br><br>Por favor cambie su password, para que pueda acceder al foro <br>";
        $msg .= "<a href='" . constant("DOCS_PATH") . "login.php?changePWD=1" . "'>Clic Aqui para Cambiar su Password</a></center>";
        echo ($msg);
        exit();
    }
    
    if ($usu["idperfil"] == 1) {
        $group_id = 4;
    } else {
        $group_id = 2;
    }
    
    $phpbb_vars = array(
        "username" => $usu["coduser"],
        "user_password" => $pass,
        "user_email" => $usu["mail"],
        "group_id" => $group_id
    );
    $phpbb_result = $phpbb->user_add($phpbb_vars);
    
    if ($phpbb_result == 'SUCCESS') {
        $phpbb_vars = array(
            "username" => $usu["coduser"],
            "password" => $pass
        );
        $phpbb_result = $phpbb->user_login($phpbb_vars);
        if ($phpbb_result == 'SUCCESS') {
            $urlForo = constant("DOCS_PATH") . "foros/index.php?sid=" . $_SESSION["sid"];
            $objFunc->Redirect($urlForo);
            exit();
        }
    } else {
        $msg = "<center style='color:red; font-size:12px'><br><br>Ocurrio un Error al acceder al Foro <br> Comuniquese con el Administrador del sistema <br><br>";
        $msg .= "<a href='" . constant("DOCS_PATH") . "index.php" . "'>Volver al Sistema</a></center>";
        echo ($msg);
        exit();
    }
}

?>
