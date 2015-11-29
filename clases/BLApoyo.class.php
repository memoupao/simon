<?php
require_once ("BLBase.class.php");
require_once ("UploadFiles.class.php");
require_once ("HardCode.class.php");

class BLApoyo extends BLBase
{

    var $fecha;

    var $Session;

    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }
    // Esta funcion es muy importante, ya que las funciones dependen de la conexion
    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }

    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    function GuardarDocumentoApoyo($titulo, $autor, $resumen, $edicion, $sector, $clave, $tipoarch, $arch)
    {
        $objFiles = new UploadFiles("arch");
        $objHC = new HardCode();
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();
        
        $objFiles->DirUpload .= $objHC->FolderUploadBV;
        
        $params = array(
            $titulo,
            $autor,
            $resumen,
            $edicion,
            $sector,
            $clave,
            $NomFoto,
            $arch,
            $this->Session->UserID,
            $ext,
            $tipoarch
        );
        
        $SP = "sp_ins_doc_apoyo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);
            
            return true;
        } else {
            return false;
        }
    }

    function GuardarPaginaApoyo($url, $titulo, $resumen, $email)
    {
        $params = array(
            $url,
            $titulo,
            $resumen,
            $email
        );
        
        $SP = "sp_ins_enl_apoyo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ListadoLibros()
    {
        $sql = "	SELECT 	codigo, 
					titulo, 
					autor, 
					resena, 
					resumen, 
					edicion, 
					sector, 
					clave, 
					t70_nom_file, 
					t70_url_file
			FROM 	t70_apo_bib
			ORDER BY  titulo
					LIMIT 0, 20";
        return $this->ExecuteQuery($sql);
    }

    function ListadoPaginas()
    {
        $sql = "	SELECT 	codigo, 
					fn_verifica_url(url) as url,
					url, 
					titulo, 
					resumen, 
					email
			FROM 	t70_apo_enl 
			ORDER BY url
			LIMIT 0, 20";
        return $this->ExecuteQuery($sql);
    }

    function ListaLibro($cod)
    {
        $sql = "	SELECT 	codigo, 
					titulo, 
					autor, 
					resena, 
					resumen, 
					edicion, 
					sector, 
					descrip,
					clave, 
					t70_nom_file, 
					t70_url_file
			FROM 	t70_apo_bib bib
			LEFT JOIN adm_tablas_aux sec ON (bib.sector=sec.codi)
			WHERE codigo=" . $cod;
        return $this->ExecuteQuery($sql);
    }

    function ListaPagina($cod)
    {
        $sql = "	SELECT 	codigo, 
					fn_verifica_url(url) as url, 
					titulo, 
					resumen, 
					email
			FROM 	t70_apo_enl 
			WHERE codigo=" . $cod;
        return $this->ExecuteQuery($sql);
    }

    function BuscarDocumentos($texto)
    {
        $SP = "sp_sel_bus_doc";
        $params = array(
            $texto
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function BuscarPaginas($texto)
    {
        $SP = "sp_sel_bus_pag";
        
        $params = array(
            $texto
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // fin de la Clase BLApoyo
}

?>

