<?php

class UploadFiles
{

    var $idFileUpload = "";

    var $FileName = "";

    var $DirTemp = "";

    var $DirUpload = "";
    /* Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función */
    function __construct($FileId)
    {
        $this->idFileUpload = $FileId;
        $this->DirUpload = constant("APP_PATH");
        $this->DirTemp = constant("PATH_TEMP_UPLOAD");
        $this->FileName = $_FILES[$this->idFileUpload]['name'];
        return true;
    }

    function getFileName()
    {
        return $_FILES[$this->idFileUpload]['name'];
    }

    function getFileSize()
    {
        return $_FILES[$this->idFileUpload]['size'];
    }

    function getFileType()
    {
        return $_FILES[$this->idFileUpload]['type'];
    }

    function getTemporaryDir()
    {
        return $_FILES[$this->idFileUpload]['tmp_name'];
    }

    function getExtension()
    {
        $name = explode(".", $this->getFileName());
        return $name[count($name) - 1];
    }

    function SavesAs($newName = '')
    {
        if ($newName != '') {
            $nombre = $newName;
        } else {
            $nombre = $this->getFileName();
        }
        
        $temp = $this->getTemporaryDir();
        $filesave = $this->DirUpload . "" . $nombre;
        
        if (move_uploaded_file($temp, $filesave)) {
            return true;
        } else {
            return false;
        }
    }

    function ValidateExt($filesExtension)
    {
        $ext = strtolower($this->getExtension());
        $ret = false;
        for ($x = 0; $x < count($filesExtension); $x ++) {
            if (strtolower($filesExtension[$x]) == $ext) {
                $ret = true;
            }
        }
        return $ret;
    }
}
// fin de la Clase UploadFiles

?>
