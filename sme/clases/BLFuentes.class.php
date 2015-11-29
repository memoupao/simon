<?php
require_once ("BLBase.class.php");

class BLFuentes extends BLBase
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
    
    // egion Equipo Ejecutor
    // egion Read Contactos
    function Directorio()
    {
        $sql = " SELECT t01_id_inst, t01_sig_inst, t01_nom_inst 
		  FROM t01_dir_inst Order by t01_sig_inst ;";
        
        return $this->ExecuteQuery($sql);
    }

    function ContactosListado($idProy)
    {
        $sql = " SELECT fte.t02_cod_proy, 
				fte.t01_id_inst, 
				ins.t01_sig_inst, 
				ins.t01_nom_inst, 
				ins.t01_dire_inst, 
				ins.t01_ciud_inst, 
				ins.t01_fono_inst, 
				ins.t01_fax_inst, 
				ins.t01_mail_inst, 
				ins.t01_ape_rep, 
				ins.t01_nom_rep, 				
				CONCAT(insrepre.t01_nom_cto, ' ' , insrepre.t01_ape_pat, ' ',insrepre.t01_ape_mat) as 'representante', 
				ins.t01_carg_rep,  
				fte.usr_crea
		  FROM t02_fuente_finan fte
		  LEFT JOIN t01_dir_inst ins on (fte.t01_id_inst=ins.t01_id_inst) 
		  LEFT JOIN t01_inst_cto insrepre on (ins.t01_id_inst=insrepre.t01_id_inst AND (insrepre.t01_cgo_cto IN ('224','225') ) )   		  
		  WHERE fte.t02_cod_proy = '$idProy' 
          GROUP BY fte.t01_id_inst ;";
        	//die($sql);
        return $this->ExecuteQuery($sql);
    }

    function ContactosSeleccionar($idProy, $id)
    {
        $sql = " SELECT fte.t02_cod_proy, fte.t01_id_inst, fte.t02_obs_fte, fte.usr_crea, fte.fch_crea, fte.usr_actu, fte.fch_actu, ins.t01_sig_inst
		 FROM t02_fuente_finan fte
		 LEFT JOIN t01_dir_inst ins on (fte.t01_id_inst=ins.t01_id_inst)
		 WHERE fte.t02_cod_proy = '$idProy' and fte.t01_id_inst='$id' ;";
        
        // echo("<pre>".$sql."</pre>");
        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        return $row;
    }
    // ndRegion
    // egion CRUD Contactos
    function ContactoNuevo($t02_cod_proy, $t01_id_inst, $t02_obs_fte)
    { /* Validaciones antes de grabar */
        $sql = "Select count(*)  from t02_fuente_finan where t02_cod_proy='" . $t02_cod_proy . "' and t01_id_inst ='" . $t01_id_inst . "' ; ";
        if ($this->GetValue($sql) > 0) {
            $this->Error = "Esta fuente de financiamiento ya ha sido registrada, Comuniquese con el Administrador.";
            return false;
        }
        
        $arrayfields = array(
            't02_cod_proy',
            't01_id_inst',
            't02_obs_fte',
            'usr_crea',
            'fch_crea'
        );
        
        $arrayvalues = array(
            $t02_cod_proy,
            $t01_id_inst,
            $t02_obs_fte,
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql = $this->DBOBaseMySQL->createqueryInsert("t02_fuente_finan", $arrayfields, $arrayvalues);
        return $this->ExecuteCreate($sql);
    }

    function ContactoActualizar($t02_cod_proy, $t01_id_inst, $t02_obs_fte)
    {
        /* Validaciones antes de Actualizar */
        $arrayfields = array(
            't02_cod_proy',
            't01_id_inst',
            't02_obs_fte',
            'usr_actu',
            'fch_actu'
        );
        
        $arrayvalues = array(
            $t02_cod_proy,
            $t01_id_inst,
            $t02_obs_fte,
            $this->Session->UserID,
            $this->fecha
        );
        
        $where = "t02_cod_proy='$t02_cod_proy' and t01_id_inst='$t01_id_inst' ";
        
        $sql = $this->DBOBaseMySQL->createqueryUpdate("t02_fuente_finan", $arrayfields, $arrayvalues, $where);
        // echo("<pre>".$sql."</pre>");
        return $this->ExecuteUpdate($sql);
    }

    function CountFte($pProy, $pFte)
    {
        $aQuery = "SELECT
	(SELECT COUNT(*) FROM t10_cost_fte WHERE t02_cod_proy = '$pProy' AND t10_cod_fte = $pFte) +
	(SELECT COUNT(*) FROM t03_mp_per_ftes WHERE t02_cod_proy = '$pProy' AND t03_id_inst = $pFte) +
	(SELECT COUNT(*) FROM t03_mp_gas_fun_ftes WHERE t02_cod_proy = '$pProy' AND t03_id_inst = $pFte) +
	(SELECT COUNT(*) FROM t03_mp_equi_ftes WHERE t02_cod_proy = '$pProy' AND t03_id_inst = $pFte)
	AS cnt_fte";
        
        $aHandler = $this->ExecuteQuery($aQuery);
        $row = mysql_fetch_assoc($aHandler);
        
        return $row['cnt_fte'];
    }

    function ContactoEliminar($t02_cod_proy, $t01_id_inst)
    {
        $sql = "	DELETE from t02_fuente_finan 
			WHERE t02_cod_proy='$t02_cod_proy' and t01_id_inst='$t01_id_inst';";
        return $this->ExecuteDelete($sql);
    }
    
    // ndRegion
    // ndRegion Contactos
    function ListadoFuentesFinan($idProy)
    {
        $SP = "sp_rpt_fuente_financiamiento";
        $ret = $this->ExecuteProcedureReader($SP, array(
            $idProy
        ));
        return $ret;
    }
    
    // fin de la Clase BLEjecutor
}

?>

