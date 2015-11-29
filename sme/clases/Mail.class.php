<?php

class Mail
{
    // var $From = "evasis@monela.org";
    var $From = "carlnss@hotmail.com";
    // var $Response= "evasis@monela.org";
    var $Response = "osktgui@gmail.com";

    var $To = "osktgui@gmail.com";

    var $Subject = "Este mensaje es de prueba";

    var $Body = '';

    var $CC = array();

    var $CCO = array();

    var $Atach = array();

    var $Headers = "";

    var $HtmlTags = ' <html>
				<head>
				   <title>Envio Automatico</title>
				</head>
				<body>
				  #BODY#
				</body>
				</html> ';

    function AddCC($mailCC)
    {
        $CC[] = $mailCC;
    }

    private function PrepareMail()
    {
        // para el envío en formato HTML
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        
        // dirección del remitente
        $headers .= "From: luis Angel Alvarez <carlnss@hotmail.com>\r\n";
        
        // dirección de respuesta, si queremos que sea distinta que la del remitente
        $headers .= "Reply-To: osktgui@gmail.com\r\n";
        
        // ruta del mensaje desde origen a destino
        $headers .= "Return-path: holahola@desarrolloweb.com\r\n";
        
        // direcciones que recibirán copia
        // $headers .= "Cc: maria@desarrolloweb.com\r\n";
        
        // direcciones que recibirán copia oculta
        // $headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n";
        
        $this->Headers = $headers;
    }

    function EnviarMail()
    {
    	PrepareMail();
    	// -------------------------------------------------->
    	// DA 2.0 [18-01-2014 17:49]
    	// Envio de correos deshabilitado    	       	       
        //if (mail($this->To, $this->Subject, $this->Body, $this->Headers))
    	if (true)
       	// --------------------------------------------------<
            echo "Mensaje enviado correctamente";
    }
}
// fin de la Clase Mail

?>
