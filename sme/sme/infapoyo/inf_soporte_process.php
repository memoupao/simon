<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLApoyo.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");
// require_once(constant('PATH_LIBRERIAS')."pjmail/Email.class.php");

?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("enviar_email") == $Accion) {
    EnviarEmail();
    exit();
}

function EnviarEmail()
{
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $perfil = $_POST['perfil'];
    $mensaje = $_POST['mensaje'];
    
    $oFn = new Functions();
    $OHard = new HardCode();
    $mail_destino = $OHard->MailAdmin;
    /*
     * $mail = new Email(); $mail->De($email,$perfil); $mail->Para($mail_destino,"Hugo Salcedo"); $mail->Asunto($nombre." Solicita soporte tecnico...."); $mail->Mensaje("<b>".$mensaje."</b>", true); $bret = $mail->Enviar();
     */
    $to = $mail_destino;
    $subject = $nombre . ' Solicita soporte tecnico.';
    $message = $mensaje;
    $headers = '';
    
    // -------------------------------------------------->
    // DA 2.0 [18-01-2014 17:49]
    // Envio de correos deshabilitado
    //if (mail($to, $subject, $message, $headers)) {
    if (false) {
        $HardCode = "alert('" . "Mensaje enviado correctamente" . "'); \n";
    } else {
        $HardCode = "alert('" . "El mensaje no fue enviado" . "'); \n";        
    }
    // --------------------------------------------------<    
    $oFn->Javascript($HardCode);
    // file_put_contents('hugo.txt',$HardCode);
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>