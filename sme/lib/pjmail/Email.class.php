<?php
require_once ("class.mail.php");

class Email extends simplemail
{

    private $old_sendmail_from = null;

    private $env_name = '';

    function __construct()
    {
        $this->simplemail();
    }

    function Adjuntar($filename, $source)
    {
        array_push($this->attachement, array(
            'filename' => $filename,
            'source' => $source
        ));
    }

    function De($correo, $nombre)
    {
        $this->addfrom($correo, $nombre);
        $this->SetearMail($correo, $nombre);
    }

    function Para($correo, $nombre)
    {
        $this->addrecipient($correo, $nombre);
    }

    function Asunto($Asunto)
    {
        $this->addsubject($Asunto);
    }

    function Mensaje($textBody, $IsHTML = true)
    {
        if ($IsHTML) {
            $this->html = $textBody;
        } else {
            $this->text = $textBody;
        }
    }

    private function SetearMail($email, $name)
    {
        $this->addfrom($email, $name);
        $this->returnpath = $this->hfrom;
        $this->Xsender = $this->hfrom;
        $this->ErrorsTo = $this->hfrom;
        $this->old_sendmail_from = ini_set('sendmail_from', $email);
    }

    function Enviar()
    {
        ob_start();
        $ret = parent::sendmail();
        $error = trim(strip_tags(ob_get_clean()));
        
        if ($this->old_sendmail_from)
            ini_set('sendmail_from', $this->old_sendmail_from);
        
        if ($error) {
            return $error;
        }
        return $ret;
    }
}

?>