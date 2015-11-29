<?php
include ('class.mail.php');

$mail = new simplemail();
$mail->addrecipient('cristhian80@gmail.com', 'Para Gmail');
$mail->addrecipient('cristhian_80@hotmail.com', 'Para Hotmail');
// $mail -> addbcc('xxx@xxx.com','plouf');
$mail->addfrom('johnnysoftt@gmail.com', 'Sistema EVASIS Fondoempleo');
$mail->addsubject('Sistema de Monitoreo Fondoempleo');

// le message text
$mail->text = 'plain text etc. etc. bla bla ...';

// le message format html
$mail->html = "bla<hr><img src=\"cid:doc1\" align=\"right\">blbala\n1\t2\t3\na\tb\tc";

// un attachement html ( image jointe afficher ds le html ).
$mail->addhtmlattachement('KT400.gif', 'doc1', 'image/gif');

// une piece jointe.
$mail->addattachement('KT400.gif');

if ($mail->sendmail()) {
    echo "Enviado";
} else {
    echo "Error";
    echo $mail->error_log;
}
?>
