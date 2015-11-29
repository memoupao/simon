<?php
require_once 'BLBase.class.php';

/**
 * CticServices
 *
 * Gestion de Tablas
 *
 * @package Admin
 * @author DA
 * @since Version 2.0
 *
 */

class BLTablas extends BLBase
{

    public $fecha;

    public $Session;

    
    public function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }

    public function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }

    public function Dispose()
    {
        $this->Destroy();
    }

    /**
     * Obtener Listado de las tablas auxiliares 
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param int $idTabla Id de la Tabla principal
     * @return resource Resultado de consulta
     *
     */
    protected function ListaTablaAux($idTabla)
    {
        $sql = "select  codi as codigo, 
				descrip as descripcion 				
		from adm_tablas_aux
	    where idTabla = $idTabla and flg_act=1 
	    order by orden, descrip ASC";
        return $this->ExecuteQuery($sql);
    }

    
    /**
     * Obtener Listado de las tablas auxiliares
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param int $idTabla Id de la Tabla principal
     * @return resource Resultado de consulta
     *
     */
    public function SectoresGenerales($idSector)
    {
        return $this->ListaTablaAux($idSector);
    }
    

    /**
     * Obtener Listado de la Tabla principal
     *
     * @author DA
     * @since Version 2.0
     * @access public     
     * @return resource Resultado de consulta
     *
     */
    public function getListaSectoresMain()
    {
        $sql = "SELECT cod_tabla as codigo,
            	 nom_tabla as descripcion
            	FROM adm_tablas
            	WHERE cod_tabla IN('18','35','36')";
        return $this->ExecuteQuery($sql);
    
    }
    
    
    
}

