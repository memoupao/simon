<?php
// inclusion de la source de la classe
include ('class.mail.php');
// creation de l'instance
$mail = new simplemail();
// ajout du destinataire
$mail->addrecipient('cristhian80@gmail.com', 'John Aima Ramos');
// ajout de l'expediteur
$mail->addfrom('cristhian@msn.com', 'JohnnySoft');
// ajout du sujet
$mail->addsubject('Asunto de Prueba de Correo');
// le message plaintext
$mail->text = 'plain text etc. etc. bla bla ...';

echo ("<pre>");
print_r($mail);
echo ("</pre>");

// envoie du message
if ($mail->sendmail()) {
    echo "Enviado";
} else {
    echo "Error al enviar";
    echo $mail->error_log;
}
?>
