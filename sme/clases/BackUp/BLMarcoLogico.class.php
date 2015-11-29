<?php
require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");

class BLMarcoLogico extends BLBase
{

    var $fecha;

    var $Session;

    var $Proyecto;

    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
        $this->Proyecto = new BLProyecto();
    }
    
    // Esta funcion es muy importante, ya que las funciones dependen de la conexion
    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }
    // -----------------------------------------------------------------------------
    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    function GetUnidad($codigo)
    {
        $sql = "	SELECT 	t1.*,
					t2.t02_version, t2.t02_ini_proy, t2.t02_ter_proy, t2.t02_fin_proy, t2.t02_pro_proy
			FROM 	  t01_unidad t1
			LEFT JOIN t02_ml     t2 ON(t1.t02_cod_proy=t2.t02_cod_proy AND t2.t02_version=(SELECT MAX(t02_ml.t02_version) 
																					 FROM t02_ml  
																					 WHERE t02_ml.t02_cod_proy=t1.t02_cod_proy) 
																					 )
			WHERE t1.t02_cod_proy = $codigo ";
        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        
        return $row;
    }

    function GetML($idProy, $idVersion)
    {
        return $this->Proyecto->GetProyecto($idProy, $idVersion);
    }

    function GetUltimaVersion($idProy)
    {
        return $this->Proyecto->MaxVersion($idProy);
    }

    function VerifyVersion($idProy, $idVersion)
    {
        if ($idProy != "" && $idVersion > 0) {
            $UltVer = $this->GetUltimaVersion($idProy);
            if ($idVersion < $UltVer) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    function ActualizarOD_def($idProy, $idVersion, $finalidad)
    {
        $sql = "UPDATE t02_dg_proy 
			SET t02_fin = '$finalidad',
				fch_actu = '" . $this->fecha . "',
				usr_actu = '" . $this->Session->UserID . "' 
		WHERE t02_cod_proy = '$idProy' AND t02_version='$idVersion' ;";
        return $this->ExecuteUpdate($sql);
    }

    function ActualizarOG_def($idProy, $idVersion, $Proposito)
    {
        $sql = "UPDATE t02_dg_proy 
			SET t02_pro = '$Proposito',
				fch_actu = '" . $this->fecha . "',
				usr_actu = '" . $this->Session->UserID . "' 
		WHERE t02_cod_proy = '$idProy' AND t02_version='$idVersion' ;";
        return $this->ExecuteUpdate($sql);
    }
    
    // egion "Indicadores de Objetivo de Desarrollo"
    function ListadoIndicadoresOD($idProy, $idVersion)
    {
        $sql = "SELECT * FROM t06_fin_ind 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' ;";
        return $this->ExecuteQuery($sql);
    }

    function GetIndicadorOD($idProy, $idVersion, $idIndicador)
    {
        $sql = "SELECT * FROM t06_fin_ind 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t06_cod_fin_ind = $idIndicador";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoIndicadorOD()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "' ";
        $codigo = $this->Autogenerate("t06_fin_ind", "t06_cod_fin_ind", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't06_cod_fin_ind',
            't06_ind',
            't06_um',
            't06_mta',
            't06_fv',
            't06_obs',
            'usr_crea',
            'fch_crea'
        );
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $codigo,
            $_POST['t06_ind'],
            $_POST['t06_um'],
            $_POST['t06_mta'],
            $_POST['t06_fv'],
            $_POST['t06_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t06_fin_ind", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarIndicadorOD()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t06_cod_fin_ind=" . $_POST['t06_cod_fin_ind'];
        
        $arrayfields = array(
            't06_ind',
            't06_um',
            't06_mta',
            't06_fv',
            't06_obs',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $_POST['t06_ind'],
            $_POST['t06_um'],
            $_POST['t06_mta'],
            $_POST['t06_fv'],
            $_POST['t06_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t06_fin_ind", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarIndicadorOD($idProy, $idVersion, $idIndicador)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t06_cod_fin_ind=" . $idIndicador;
        
        $sql1 = "DELETE FROM t06_fin_ind WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Indicadores de Objetivo General"
    function ListadoIndicadoresOG($idProy, $idVersion)
    {
        $sql = "SELECT * FROM t07_prop_ind 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' ;";
        return $this->ExecuteQuery($sql);
    }

    function GetIndicadorOG($idProy, $idVersion, $idIndicador)
    {
        $sql = "SELECT * FROM t07_prop_ind 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t07_cod_prop_ind = $idIndicador";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoIndicadorOG()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "' ";
        $codigo = $this->Autogenerate("t07_prop_ind", "t07_cod_prop_ind", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't07_cod_prop_ind',
            't07_ind',
            't07_um',
            't07_mta',
            't07_fv',
            't07_obs',
            'usr_crea',
            'fch_crea'
        );
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $codigo,
            $_POST['t07_ind'],
            $_POST['t07_um'],
            $_POST['t07_mta'],
            $_POST['t07_fv'],
            $_POST['t07_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t07_prop_ind", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarIndicadorOG()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t07_cod_prop_ind=" . $_POST['t07_cod_prop_ind'];
        
        $arrayfields = array(
            't07_ind',
            't07_um',
            't07_mta',
            't07_fv',
            't07_obs',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $_POST['t07_ind'],
            $_POST['t07_um'],
            $_POST['t07_mta'],
            $_POST['t07_fv'],
            $_POST['t07_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t07_prop_ind", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarIndicadorOG($idProy, $idVersion, $idIndicador)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t07_cod_prop_ind=" . $idIndicador;
        
        $sql1 = "DELETE FROM t07_prop_ind WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Supuestos de Objetivo Desarrollo"
    function ListadoSupuestosOD($idProy, $idVersion)
    {
        $sql = "SELECT * FROM t06_fin_sup 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' ;";
        return $this->ExecuteQuery($sql);
    }

    function GetSupuestosOD($idProy, $idVersion, $idSup)
    {
        $sql = "SELECT * FROM t06_fin_sup 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t06_cod_fin_sup = $idSup";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoSupuestosOD()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "' ";
        $codigo = $this->Autogenerate("t06_fin_sup", "t06_cod_fin_sup", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't06_cod_fin_sup',
            't06_sup',
            'usr_crea',
            'fch_crea'
        );
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $codigo,
            $_POST['t06_sup'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t06_fin_sup", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarSupuestosOD()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t06_cod_fin_sup=" . $_POST['t06_cod_fin_sup'];
        
        $arrayfields = array(
            't06_sup',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $_POST['t06_sup'],
            $this->Session->UserID,
            $this->fecha
        );
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t06_fin_sup", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarSupuestosOD($idProy, $idVersion, $idSup)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t06_cod_fin_sup=" . $idSup;
        
        $sql1 = "DELETE FROM t06_fin_sup WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Supuestos de Objetivo General"
    function ListadoSupuestosOG($idProy, $idVersion)
    {
        $sql = "SELECT * FROM t07_prop_sup 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' ;";
        return $this->ExecuteQuery($sql);
    }

    function GetSupuestosOG($idProy, $idVersion, $idSup)
    {
        $sql = "SELECT * FROM t07_prop_sup 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t07_cod_prop_sup = $idSup";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoSupuestosOG()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "' ";
        $codigo = $this->Autogenerate("t07_prop_sup", "t07_cod_prop_sup", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't07_cod_prop_sup',
            't07_sup',
            'usr_crea',
            'fch_crea'
        );
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $codigo,
            $_POST['t07_sup'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t07_prop_sup", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarSupuestosOG()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t07_cod_prop_sup=" . $_POST['t07_cod_prop_sup'];
        
        $arrayfields = array(
            't07_sup',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $_POST['t07_sup'],
            $this->Session->UserID,
            $this->fecha
        );
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t07_prop_sup", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarSupuestosOG($idProy, $idVersion, $idSup)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t07_cod_prop_sup=" . $idSup;
        
        $sql1 = "DELETE FROM t07_prop_sup WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Objetivo Especifico - Definicion "
    function ListadoDefinicionOE($idProy, $idVersion)
    {
        $sql = "SELECT * FROM t08_comp 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' ;";
        return $this->ExecuteQuery($sql);
    }

    function GetDefinicionOE($idProy, $idVersion, $idComp)
    {
        $sql = "SELECT * FROM t08_comp 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t08_cod_comp = $idComp";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoDefinicionOE()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "' ";
        $codigo = $this->Autogenerate("t08_comp", "t08_cod_comp", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't08_comp_desc',
            'usr_crea',
            'fch_crea'
        );
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $codigo,
            $_POST['t08_comp_desc'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t08_comp", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarDefinicionOE()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'];
        
        $arrayfields = array(
            't08_comp_desc',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $_POST['t08_comp_desc'],
            $this->Session->UserID,
            $this->fecha
        );
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t08_comp", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarDefinicionOE($idProy, $idVersion, $idComp)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t08_cod_comp=" . $idComp;
        
        $sql1 = "DELETE FROM t08_comp WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Objetivo Especifico - Indicadores "
    function ListadoIndicadoresOE($idProy, $idVersion, $idComp)
    {
        $sql = "SELECT * FROM t08_comp_ind 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t08_cod_comp = $idComp;";
        return $this->ExecuteQuery($sql);
    }

    function GetIndicadoresOE($idProy, $idVersion, $idComp, $idInd)
    {
        $sql = "SELECT * FROM t08_comp_ind 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t08_cod_comp = $idComp
		  AND  t08_cod_comp_ind= $idInd";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoindIcadoresOE()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . "";
        $codigo = $this->Autogenerate("t08_comp_ind", "t08_cod_comp_ind", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't08_cod_comp_ind',
            't08_ind',
            't08_um',
            't08_mta',
            't08_fv',
            't08_obs',
            'usr_crea',
            'fch_crea'
        );
        
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $codigo,
            $_POST['t08_ind'],
            $_POST['t08_um'],
            $_POST['t08_mta'],
            $_POST['t08_fv'],
            $_POST['t08_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t08_comp_ind", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarIndicadoresOE()
    {
        $arrayfields = array(
            't08_ind',
            't08_um',
            't08_mta',
            't08_fv',
            't08_obs',
            'usr_actu',
            'fch_actu'
        );
        
        $arrayvalues = array(
            $_POST['t08_ind'],
            $_POST['t08_um'],
            $_POST['t08_mta'],
            $_POST['t08_fv'],
            $_POST['t08_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " AND t08_cod_comp_ind=" . $_POST['t08_cod_comp_ind'];
        
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t08_comp_ind", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarIndicadoresOE($idProy, $idVersion, $idComp, $idInd)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t08_cod_comp=" . $idComp . " AND t08_cod_comp_ind=" . $idInd . "";
        
        $sql1 = "DELETE FROM t08_comp_ind WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Objetivo Especifico - Supuestos "
    function ListadoSupuestosOE($idProy, $idVersion, $idComp)
    {
        $sql = "SELECT * FROM t08_comp_sup 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t08_cod_comp = $idComp;";
        return $this->ExecuteQuery($sql);
    }

    function GetSupuestosOE($idProy, $idVersion, $idComp, $idSup)
    {
        $sql = "SELECT * FROM t08_comp_sup 
		WHERE  t02_cod_proy = '$idProy' 
		  AND  t02_version = '$idVersion' 
		  AND  t08_cod_comp = $idComp
		  AND  t08_cod_comp_sup= $idSup";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoSupuestosOE()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . "";
        $codigo = $this->Autogenerate("t08_comp_sup", "t08_cod_comp_sup", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't08_cod_comp_sup',
            't08_sup',
            'usr_crea',
            'fch_crea'
        );
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $codigo,
            $_POST['t08_sup'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t08_comp_sup", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarSupuestosOE()
    {
        $arrayfields = array(
            't08_sup',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $_POST['t08_sup'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " AND t08_cod_comp_sup=" . $_POST['t08_cod_comp_sup'];
        
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t08_comp_sup", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarSupuestosOE($idProy, $idVersion, $idComp, $idInd)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t08_cod_comp=" . $idComp . " AND t08_cod_comp_sup=" . $idInd . "";
        
        $sql1 = "DELETE FROM t08_comp_sup WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Objetivo Especifico - Actividades "
    function ListadoActividadesOE($idProy, $idVersion, $idComp)
    {
        $sql = "SELECT t09.*
		FROM t09_act t09
		WHERE  t09.t02_cod_proy = '$idProy' 
		  AND  t09.t02_version = '$idVersion' 
		  AND  t09.t08_cod_comp = $idComp;";
        return $this->ExecuteQuery($sql);
    }

    function GetActividadesOE($idProy, $idVersion, $idComp, $idAct)
    {
        $sql = "SELECT *
		FROM t09_act t09
		WHERE  t09.t02_cod_proy = '$idProy' 
		  AND  t09.t02_version = '$idVersion' 
		  AND  t09.t08_cod_comp = $idComp
		  AND  t09.t09_cod_act= $idAct ";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function NuevoActividadesOE()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . "";
        $codigo = $this->Autogenerate("t09_act", "t09_cod_act", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't09_cod_act',
            't09_act',
            't09_obs',
            'usr_crea',
            'fch_crea'
        );
        
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $codigo,
            $_POST['t09_act'],
            $_POST['t09_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql1 = $this->DBOBaseMySQL->createqueryInsert("t09_act", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql1);
    }

    function ActualizarActividadesOE()
    {
        $arrayfields = array(
            't09_act',
            't09_obs',
            'usr_actu',
            'fch_actu'
        );
        
        $arrayvalues = array(
            $_POST['t09_act'],
            $_POST['t09_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " AND t09_cod_act=" . $_POST['t09_cod_act'];
        
        $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t09_act", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql1);
    }

    function EliminarActividadesOE($idProy, $idVersion, $idComp, $idAct)
    {
        $where = "t02_cod_proy='" . $idProy . "' AND t02_version='" . $idVersion . "' AND t08_cod_comp=" . $idComp . " AND t09_cod_act=" . $idAct . "";
        
        $sql1 = "DELETE FROM t09_act WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql1);
    }
    // nd Region
    
    // egion "Sub-Actividades"
    function ListaSubActividades($idProy, $idVersion, $idComp, $idAct)
    {
        $sql = "SELECT
		  t09.t02_cod_proy, 
		  t09.t02_version, 
		  t09.t08_cod_comp  as comp, 
		  t09.t09_cod_act	as act, 
		  t09.t09_cod_sub	as subact,  
		  t09.t09_sub		as descripcion, 
		  t09.t09_um		as um, 
		  t09.t09_mta		as meta,
		  0 as m1,
		  0 as m2,
		  0 as m3,
		  0 as m4,
		  0 as m5,
		  0 as m6,
		  0 as m7,
		  0 as m8,
		  0 as m9,
		  0 as m10,
		  0 as m11,
		  0 as m12
		FROM
		  t09_subact t09
		WHERE  t09.t02_cod_proy = '$idProy' 
		  AND  t09.t02_version = '$idVersion' 
		  AND  t09.t08_cod_comp = '$idComp'
		  AND  t09.t09_cod_act= '$idAct'";
        return $this->ExecuteQuery($sql);
    }
    // ndregion
    
    // fin de la Clase BLMarcoLogico
}

?>
