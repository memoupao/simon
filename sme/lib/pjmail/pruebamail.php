<?php
require_once ("Email.class.php");
$mail = new Email();

$mail->De("cristhian_80@hotmail.com", "John Aima Ramos");

$mail->Para("cristhianxxxx80@gmail.com", "John Aima Ramos");
// $mail->Para("cristhian_80@hotmail.com","John Aima Ramos");

$mail->Asunto("Envio automatico de Correos....");

$body = "Probando mensaje";

$mail->Mensaje($body, true);

$ret = $mail->Enviar();

echo ("<pre>");
print_r($ret);
echo ("<hr>");
print_r($mail);
echo ("</pre>");

?>