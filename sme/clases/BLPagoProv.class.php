<?php
require_once ("BLBase.class.php");
require_once ("UploadFiles.class.php");

class BLPagoProv extends BLBase
{

    var $fecha;

    var $Session;
    // -----------------------------------------------------------------------------
    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        if ($this->Session != NULL) {
            $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
        }
    }

    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }

    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    
    // egion Importar formato de Proyectos.
    function ImportarXLS(&$urlFile)
    {
        $objFiles = new UploadFiles("txtarchivo");
        $NomFile = $objFiles->getFileName();
        
        if ($NomFile == "") {
            $this->Error = "El archivo cargado no es valido, o excede el tamaño permitido !!!";
            return false;
        }
        
        $arrext = array(
            "xls",
            "xlsx",
            "csv"
        );
        $ret = $objFiles->ValidateExt($arrext);
        
        if (! $ret) {
            $this->Error = "El archivo \"" . $NomFile . "\" no es correcto, se requiere un archivo de formato Excel";
            return false;
        }
        
        $ext = $objFiles->getExtension();
        $objFiles->DirUpload = $objFiles->DirTemp;
        
        $urlFile = $this->Session->UserID . '_' . str_replace(" ", "", $NomFile);
        return $objFiles->SavesAs($urlFile);
    }

    function ValidaXLS()
    {
        $ret = $this->ExecuteQuery("TRUNCATE TABLE tmp_pago_prov_bcp");
        if (! $ret) {
            return false;
        }
        
        $SP = "sp_ins_importar_pprov";
        
        $arrProy = NULL;
        if (! is_array($_SESSION['MatrizProyectos'])) {
            $this->Error = "No se ha cargado ningun dato";
            return false;
        } else {
            $arrProy = $_SESSION['MatrizProyectos'];
        }
        
        $concurso = 1;
        $codproy = 2;
        $region = 3;
        $inicio = 4;
        $termino = 5;
        $encargado = 6;
        $ejecutor = 7;
        $ruc = 8;
        $modogiro = 9;
        $banco = 10;
        $tipocuenta = 11;
        $nrocuenta = 12;
        $moncuenta = 13;
        $textoref = 14;
        $montotransf = 15;
        $montranf = 16;
        
        $numrows = 0;
        
        for ($r = 0; $r < count($arrProy); $r ++) {
            $params = array(
                $arrProy[$r][$concurso],
                $arrProy[$r][$codproy],
                $arrProy[$r][$region],
                $arrProy[$r][$inicio],
                $arrProy[$r][$termino],
                $arrProy[$r][$encargado],
                $arrProy[$r][$ejecutor],
                $arrProy[$r][$ruc],
                $arrProy[$r][$modogiro],
                $arrProy[$r][$banco],
                $arrProy[$r][$tipocuenta],
                $arrProy[$r][$nrocuenta],
                $arrProy[$r][$moncuenta],
                $arrProy[$r][$textoref],
                $arrProy[$r][$montotransf],
                $arrProy[$r][$montranf],
                $this->Session->UserID
            );
            $ret = $this->ExecuteProcedureEscalar($SP, $params);
            $numrows += $ret['numrows'];
        }
        
        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se lograron grabar todos los datos... \n" . $this->Error;
            return false;
        }
    }

    function ListarTempValidado()
    {
        $params = NULL; // array($idMenu);
        return $this->ExecuteProcedureReader("sp_lis_importar_pprov", $params);
    }

    function ListaTXTGenerado($idCuentaFE)
    {
        $params = array(
            $idCuentaFE
        );
        return $this->ExecuteProcedureReader("sp_genera_txt_bcp", $params);
    }
    
    // fin de la Clase BLPagoProv
}

?>

