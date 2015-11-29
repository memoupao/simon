<?php

class simplemail
{

    protected $recipientlist = array();

    protected $subject;

    protected $hfrom;

    protected $hbcc;

    protected $hcc;

    protected $Xsender;

    protected $ErrorsTo;

    protected $XMailer = 'PHP';

    protected $XPriority = 3;

    protected $set_mode = 'php';

    protected $text;

    protected $html;

    protected $attachement;

    protected $htmlattachement;

    protected $recipient;

    protected $body;

    protected $headers;

    protected $error_log;

    protected $connect;

    protected $default_charset = 'UTF-8';

    protected $B1B = "----=_001";

    protected $B2B = "----=_002";

    protected $B3B = "----=_003";

    function simplemail()
    {
        $this->attachement = array();
        $this->htmlattachement = array();
    }

    private function checkaddress($address)
    {
        if (preg_match('`([[:alnum:]]([-_.]?[[:alnum:]])*@[[:alnum:]]([-_.]?[[:alnum:]])*\.([a-z]{2,4}))`', $address)) {
            return TRUE;
        } else {
            $this->error_log("l'adresse $address est invalide");
            return FALSE;
        }
    }

    private function checkname($name)
    {
        if (preg_match("`[0-9a-zA-Z\.\-_ ]*`", $name)) {
            return TRUE;
        } else {
            $this->error_log(" le pseudo $name est invalide\n");
            return FALSE;
        }
    }

    private function makenameplusaddress($address, $name)
    {
        if (! $this->checkaddress($address))
            return FALSE;
        if (! $this->checkname($name))
            return FALSE;
        if (empty($name)) {
            return $address;
        } else {
            $tmp = $name . " <" . $address . ">";
            return $tmp;
        }
    }

    protected function addrecipient($newrecipient, $name = '')
    {
        $tmp = $this->makenameplusaddress($newrecipient, $name);
        if (! $tmp) {
            $this->error_log(" To: error");
            return FALSE;
        }
        $this->recipientlist[] = array(
            'mail' => $newrecipient,
            'nameplusmail' => $tmp
        );
        return TRUE;
    }

    protected function addbcc($bcc, $name = '')
    {
        $tmp = $this->makenameplusaddress($bcc, $name);
        if (! $tmp) {
            $this->error_log(" Bcc: error");
            return FALSE;
        }
        if (! empty($this->hbcc))
            $this->hbcc .= ",";
        $this->hbcc .= $tmp;
        return TRUE;
    }

    protected function addcc($cc, $name = '')
    {
        $tmp = $this->makenameplusaddress($cc, $name);
        if (! $tmp) {
            $this->error_log(" Cc: error\n");
            return FALSE;
        }
        if (! empty($this->hcc))
            $this->hcc .= ",";
        $this->hcc .= $tmp;
        return TRUE;
    }

    protected function addsubject($subject)
    {
        if (! empty($subject))
            $this->subject = $subject;
    }

    protected function addfrom($from, $name = '')
    {
        $tmp = $this->makenameplusaddress($from, $name);
        if (! $tmp) {
            $this->error_log(" From: error");
            return FALSE;
        }
        $this->hfrom = $tmp;
        return TRUE;
    }

    protected function addreturnpath($return)
    {
        $tmp = $this->makenameplusaddress($return, '');
        if (! $tmp) {
            $this->error_log("Return-Path: error");
            return FALSE;
        }
        $this->returnpath = $return;
        return TRUE;
    }

    protected function addreplyto($replyto)
    {
        $tmp = $this->makenameplusaddress($replyto, '');
        if (! $tmp) {
            $this->error_log(" Reply-To: error");
            return FALSE;
        }
        $this->returnpath = $tmp;
        return TRUE;
    }
    
    // les attachements
    protected function addattachement($filename)
    {
        array_push($this->attachement, array(
            'filename' => $filename
        ));
    }
    
    // les attachements html
    protected function addhtmlattachement($filename, $cid = '', $contenttype = '')
    {
        array_push($this->htmlattachement, array(
            'filename' => $filename,
            'cid' => $cid,
            'contenttype' => $contenttype
        ));
    }

    private function writeattachement(&$attachement, $B)
    {
        $message = '';
        if (! empty($attachement)) {
            foreach ($attachement as $AttmFile) {
                $patharray = explode("/", $AttmFile['filename']);
                $FileName = $patharray[count($patharray) - 1];
                
                $message .= "\n--" . $B . "\n";
                
                if (! empty($AttmFile['cid'])) {
                    $message .= "Content-Type: {$AttmFile['contenttype']};\n name=\"" . $FileName . "\"\n";
                    $message .= "Content-Transfer-Encoding: base64\n";
                    $message .= "Content-ID: <{$AttmFile['cid']}>\n";
                    $message .= "Content-Disposition: inline;\n filename=\"" . $FileName . "\"\n\n";
                } else {
                    $message .= "Content-Type: application/octetstream;\n name=\"" . $FileName . "\"\n";
                    $message .= "Content-Transfer-Encoding: base64\n";
                    $message .= "Content-Disposition: attachment;\n filename=\"" . $FileName . "\"\n\n";
                }
                
                $fd = fopen($AttmFile['filename'], "rb");
                $FileContent = fread($fd, filesize($AttmFile['filename']));
                fclose($fd);
                
                $FileContent = chunk_split(base64_encode($FileContent));
                $message .= $FileContent;
                $message .= "\n\n";
            }
            $message .= "\n--" . $B . "--\n";
        }
        return $message;
    }

    private function BodyLineWrap($Value)
    {
        return wordwrap($Value, 78, "\n ");
    }

    protected function makebody()
    {
        $message = '';
        if (! $this->html && $this->text && ! empty($this->attachement)) {
            
            // Messages start with text/html alternatives in OB
            $message = "This is a multi-part message in MIME format.\n";
            $message .= "\n--" . $this->B1B . "\n";
            
            $message .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
            $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
            // plaintext goes here
            $message .= $this->BodyLineWrap($this->text) . "\n\n";
            
            $message .= $this->writeattachement($this->attachement, $this->B1B);
        } elseif (! $this->html && $this->text && empty($this->attachement)) {
            
            // plaintext goes here
            $message .= $this->BodyLineWrap($this->text) . "\n\n";
        } elseif ($this->html) {
            
            // Messages start with text/html alternatives in OB
            $message = "This is a multi-part message in MIME format.\n";
            $message .= "\n--" . $this->B1B . "\n";
            
            $message .= "Content-Type: multipart/related;\n\t boundary=\"" . $this->B2B . "\"\n\n";
            // plaintext section
            $message .= "\n--" . $this->B2B . "\n";
            
            $message .= "Content-Type: multipart/alternative;\n\t boundary=\"" . $this->B3B . "\"\n\n";
            // plaintext section
            $message .= "\n--" . $this->B3B . "\n";
            
            $message .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
            $message .= "Content-Transfer-Encoding: quoted-printable\n\n";
            // plaintext goes here
            $message .= $this->BodyLineWrap($this->text) . "\n\n";
            
            // html section
            $message .= "\n--" . $this->B3B . "\n";
            $message .= "Content-Type: text/html; charset=\"UTF-8\"\n";
            $message .= "Content-Transfer-Encoding: base64\n\n";
            // html goes here
            $message .= chunk_split(base64_encode($this->html)) . "\n\n";
            
            // end of text
            $message .= "\n--" . $this->B3B . "--\n";
            
            // attachments html
            if (empty($this->htmlattachement)) {
                $message .= "\n--" . $this->B2B . "--\n";
            } else {
                $message .= $this->writeattachement($this->htmlattachement, $this->B2B);
            }
            
            // attachments
            if (empty($this->attachement)) {
                $message .= "\n--" . $this->B1B . "--\n";
            } else {
                $message .= $this->writeattachement($this->attachement, $this->B1B);
            }
        }
        
        $this->body = $message;
        
        return $message;
    }
    
    // Mail Headers Methods
    private function MakeHeaderField($Field, $Value)
    {
        return wordwrap($Field . ": " . $Value, 78, "\n ") . "\r\n";
    }

    protected function AddField2Header($Field, $Value)
    {
        $this->headers .= $this->MakeHeaderField($Field, $Value);
    }

    private function makeheader()
    {
        $this->headers = '';
        
        if (empty($this->recipientlist)) {
            $this->error_log("destinataire manquant");
            return FALSE;
        }
        // else { $this->AddField2Header("To",$this->recipient); }
        
        if (empty($this->subject)) {
            $this->error_log("sujet manquant");
            return FALSE;
        } else {
            if ($this->set_mode != 'php') {
                $this->AddField2Header("Subject", $this->subject);
            }
        }
        
        // Date: Mon, 03 Nov 2003 20:48:06 +0100
        $this->AddField2Header("Date", date('r'));
        
        if (! empty($this->Xsender)) {
            $this->AddField2Header("X-Sender", $this->Xsender);
        } else {
            $this->AddField2Header("X-Sender", $this->hfrom);
        }
        
        if (! empty($this->ErrorsTo)) {
            $this->AddField2Header("Errors-To", $this->ErrorsTo);
        } else {
            $this->AddField2Header("Errors-To", $this->hfrom);
        }
        
        if (! empty($this->XMailer))
            $this->AddField2Header("X-Mailer", $this->XMailer);
        
        if (! empty($this->XPriority))
            $this->AddField2Header("X-Priority", $this->XPriority);
        
        if (! empty($this->hfrom))
            $this->AddField2Header("From", $this->hfrom);
        
        if (! empty($this->returnpath))
            $this->AddField2Header("Return-Path", $this->returnpath);
        
        if (! empty($this->replyto))
            $this->AddField2Header("Reply-To", $this->replyto);
        
        $this->headers .= "MIME-Version: 1.0\r\n";
        
        if (! $this->html && $this->text && ! empty($this->attachement)) {
            $this->headers .= "Content-Type: multipart/mixed;\r\n\t boundary=\"" . $this->B1B . "\"\r\n";
        } elseif (! $this->html && $this->text && empty($this->attachement)) {
            $this->headers .= "Content-Type: text/plain; charset=us-ascii; format=flowed\r\n";
            $this->headers .= "Content-Transfer-Encoding: 7bit\r\n";
        } elseif ($this->html) {
            if (! $this->text) {
                $this->text = "HTML only!";
            }
            $this->headers .= "Content-Type: multipart/mixed;\r\n\t boundary=\"" . $this->B1B . "\"\r\n";
        }
        
        if (! empty($this->hcc))
            $this->AddField2Header("Cc", $this->hcc);
        if (! empty($this->hbcc))
            $this->AddField2Header("Bcc", $this->hbcc);
        
        return $this->headers;
    }

    protected function sendmail()
    {
        $this->makebody();
        $this->makeheader();
        $ret = false;
        switch ($this->set_mode) {
            case 'php':
                $ret = $this->phpmail();
                break;
            case 'socket':
                $ret = $this->socketmailloop();
                break;
        }
        return $ret;
    }
    
    // Mail send by PHPmail
    private function phpmail()
    {
        while (list ($key, $to) = each($this->recipientlist)) {
            $this->recipient = $to['mail'];
            $header = $this->makeheader();
            //$ret = mail($to['mail'], $this->subject, $this->body, $header);
            $ret = true;
            echo ("<pre>");
            print_r($to['mail']);
            print_r($this->subject);
            print_r($this->body);
            print_r($header);
            print_r($ret);
            echo ("</pre>");
            
            if ($ret) {
                $this->error_log("Mensaje de {$to['nameplusmail']} ha sido enviado");
            } else {
                $this->error_log("Mensaje de {$to['nameplusmail']} no se logro enviar");
            }
        }
        return $ret;
    }
    
    // Socket Function
    private function SocketStart()
    {
        if (! $this->connect = fsockopen(ini_get("SMTP"), ini_get("smtp_port"), $errno, $errstr, 30)) {
            $this->error_log("Could not talk to the sendmail server!");
            return FALSE;
        }
        ;
        return fgets($this->connect, 1024);
    }

    private function SocketStop()
    {
        fclose($this->connect);
        return TRUE;
    }

    private function SocketSend($in, $wait = '')
    {
        fputs($this->connect, $in, strlen($in));
        echo "-";
        flush();
        if (empty($wait)) {
            $rcv = fgets($this->connect, 1024);
            return $rcv;
        }
        return TRUE;
    }
    
    // Mail Socket
    private function socketmailstart()
    {
        $this->SocketStart();
        if (! isset($_SERVER['SERVER_NAME']) || empty($_SERVER['SERVER_NAME'])) {
            $serv = 'unknown';
        } else {
            $serv = $_SERVER['SERVER_NAME'];
        }
        $this->SocketSend("HELO $serv\r\n");
    }

    private function socketmailsend($to)
    {
        /* $this->recipient = $to;
        $this->error_log("Socket vers $to");
        
        $this->SocketSend("MAIL FROM:{$this->hfrom}\r\n");
        $this->SocketSend("RCPT TO:$to\r\n");
        $this->SocketSend("DATA\r\n");
        $this->SocketSend($this->CleanMailDataString($this->headers) . "\r\n", 'NOWAIT');
        $this->SocketSend($this->CleanMailDataString($this->body) . "\r\n", 'NOWAIT');
        $this->SocketSend(".\r\n");
        $this->SocketSend("RSET\r\n");
        
        $this->error_log("Fin de l'envoi vers $to"); */
        
        return TRUE;
    }

    private function socketmailstop()
    {
        $this->SocketSend("QUIT\r\n");
        $this->SocketStop();
        return TRUE;
    }

    private function socketmailloop()
    {
        /* $this->socketmailstart();
        while (list ($key, $to) = each($this->recipientlist)) {
            $this->recipient = $to['mail'];
            $this->makeheader();
            $this->socketmailsend($to['mail']);
        }
        $this->socketmailstop(); */
    	return true;
    }
    
    // Misc.
    private function error_log($msg = '')
    {
        if (! empty($msg)) {
            $this->error_log .= $msg . "\r\n--\r\n";
            return TRUE;
        }
        return " --- Error Log --- \r\n\r\n" . $this->error_log;
    }

    private function CleanMailDataString($data)
    {
        $data = preg_replace("/([^\r]{1})\n/", "\\1\r\n", $data);
        $data = preg_replace("/\n\n/", "\n\r\n", $data);
        $data = preg_replace("/\n\./", "\n..", $data);
        return $data;
    }
}
?>