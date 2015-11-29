<?php
require_once("BLBase.class.php");
require_once("BLProyecto.class.php");
//error_reporting(E_ALL);

class BLMarcoLogico extends BLBase
{
var $fecha ;
var $Session ;
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
{ 	$this->SetConection($ConexID) ; }
//-----------------------------------------------------------------------------
function Dispose()
{ 	$this->Destroy() ; }
//-----------------------------------------------------------------------------

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

function GetML    ($idProy, $idVersion)
{
	return $this->Proyecto->GetProyecto($idProy, $idVersion) ;
}

function GetUltimaVersion($idProy)
{
	return $this->Proyecto->MaxVersion($idProy) ;
}
function VerifyVersion($idProy, $idVersion)
{
	if($idProy!="" && $idVersion >0)
	  {	  $UltVer = $this->GetUltimaVersion($idProy);
		  if($idVersion < $UltVer)
		    { return false;	}
			else
			{ return true ; }

	  }
    else
	  { return true; }
}

function ActualizarOD_def($idProy, $idVersion, $finalidad)
{
$sql = "UPDATE t02_dg_proy
			SET t02_fin = '$finalidad',
				fch_actu = '".$this->fecha."',
				usr_actu = '".$this->Session->UserID."'
		WHERE t02_cod_proy = '$idProy' AND t02_version='$idVersion' ;" ;
return $this->ExecuteUpdate($sql);
}
function ActualizarOG_def($idProy, $idVersion, $Proposito)
{
$sql = "UPDATE t02_dg_proy
			SET t02_pro = '$Proposito',
				fch_actu = '".$this->fecha."',
				usr_actu = '".$this->Session->UserID."'
		WHERE t02_cod_proy = '$idProy' AND t02_version='$idVersion' ;" ;
return $this->ExecuteUpdate($sql);
}

#region "Indicadores de Objetivo de Desarrollo"
function ListadoIndicadoresOD  ($idProy, $idVersion)
{
$sql = "SELECT * FROM t06_fin_ind
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		 ORDER BY t06_cod_fin_ind ASC ;";
return $this->ExecuteQuery($sql);
}
function GetIndicadorOD		    ($idProy, $idVersion, $idIndicador)
{
$sql = "SELECT * FROM t06_fin_ind
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t06_cod_fin_ind = $idIndicador" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}
function NuevoIndicadorOD	   ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."' ";
$codigo = $this->Autogenerate("t06_fin_ind","t06_cod_fin_ind", $where);

$arrayfields = array(	't02_cod_proy', 't02_version' ,
					't06_cod_fin_ind', 't06_ind', 't06_um',
					't06_mta', 't06_fv',
					't06_obs', 'usr_crea','fch_crea' ) ;
$arrayvalues = array(  $_POST['t02_cod_proy'], $_POST['t02_version'], $codigo,
					$_POST['t06_ind'], $_POST['t06_um'],
					$_POST['t06_mta'],  $_POST['t06_fv'],
					$_POST['t06_obs'], $this->Session->UserID, $this->fecha) ;


$sql1 = $this->DBOBaseMySQL->createqueryInsert("t06_fin_ind",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarIndicadorOD ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t06_cod_fin_ind=".$_POST['t06_cod_fin_ind'];

$arrayfields = array('t06_ind', 't06_um',
					't06_mta', 't06_fv',
					't06_obs', 'usr_actu','fch_actu' ) ;
$arrayvalues = array( $_POST['t06_ind'], $_POST['t06_um'],
					$_POST['t06_mta'],  $_POST['t06_fv'],
					$_POST['t06_obs'], $this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t06_fin_ind",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarIndicadorOD    ($idProy,$idVersion, $idIndicador )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t06_cod_fin_ind=".$idIndicador;

$sql1 = "DELETE FROM t06_fin_ind WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region


#region "Indicadores de Objetivo General"
function ListadoIndicadoresOG ($idProy, $idVersion)
{
$sql = "SELECT * FROM t07_prop_ind
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		ORDER BY t07_cod_prop_ind ASC ;";
return $this->ExecuteQuery($sql);
}
function GetIndicadorOG       ($idProy, $idVersion, $idIndicador)
{
$sql = "SELECT * FROM t07_prop_ind
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t07_cod_prop_ind = $idIndicador" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}

function ActualizarIndicadorOG()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t07_cod_prop_ind=".$_POST['t07_cod_prop_ind'];

$arrayfields = array('t07_ind', 't07_um',
					't07_mta', 't07_fv',
					't07_obs', 'usr_actu','fch_actu' ) ;
$arrayvalues = array( $_POST['t07_ind'], $_POST['t07_um'],
					$_POST['t07_mta'],  $_POST['t07_fv'],
					$_POST['t07_obs'], $this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t07_prop_ind",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function NuevoIndicadorOG     ()
{

$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."' ";
$codigo = $this->Autogenerate("t07_prop_ind","t07_cod_prop_ind", $where);

$arrayfields = array(	't02_cod_proy', 't02_version' ,
					't07_cod_prop_ind', 't07_ind', 't07_um',
					't07_mta', 't07_fv',
					't07_obs', 'usr_crea','fch_crea' ) ;
$arrayvalues = array(  $_POST['t02_cod_proy'], $_POST['t02_version'], $codigo,
					$_POST['t07_ind'], $_POST['t07_um'],
					$_POST['t07_mta'], $_POST['t07_fv'],
					$_POST['t07_obs'], $this->Session->UserID, $this->fecha) ;


$sql1 = $this->DBOBaseMySQL->createqueryInsert("t07_prop_ind",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function EliminarIndicadorOG  ($idProy,$idVersion, $idIndicador )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t07_cod_prop_ind=".$idIndicador;

$sql1 = "DELETE FROM t07_prop_ind WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region


#region "Supuestos de Objetivo Desarrollo"
function ListadoSupuestosOD   ($idProy, $idVersion)
{
$sql = "SELECT * FROM t06_fin_sup
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion' ;";
return $this->ExecuteQuery($sql);
}
function GetSupuestosOD       ($idProy, $idVersion, $idSup)
{
$sql = "SELECT * FROM t06_fin_sup
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t06_cod_fin_sup = $idSup" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}
function NuevoSupuestosOD     ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."' ";
$codigo = $this->Autogenerate("t06_fin_sup","t06_cod_fin_sup", $where);

$arrayfields = array(	't02_cod_proy', 't02_version' ,
						't06_cod_fin_sup', 't06_sup',
						'usr_crea','fch_crea' ) ;
$arrayvalues = array(  	$_POST['t02_cod_proy'], $_POST['t02_version'], $codigo,
						$_POST['t06_sup'],
						$this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryInsert("t06_fin_sup",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarSupuestosOD()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t06_cod_fin_sup=".$_POST['t06_cod_fin_sup'];

$arrayfields = array(	't06_sup',
						'usr_actu','fch_actu' ) ;
$arrayvalues = array(  	$_POST['t06_sup'],
						$this->Session->UserID, $this->fecha) ;
$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t06_fin_sup",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarSupuestosOD  ($idProy,$idVersion, $idSup )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t06_cod_fin_sup=".$idSup;

$sql1 = "DELETE FROM t06_fin_sup WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region


#region "Supuestos de Objetivo General"
function ListadoSupuestosOG   ($idProy, $idVersion)
{
$sql = "SELECT * FROM t07_prop_sup
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion' ;";
return $this->ExecuteQuery($sql);
}
function GetSupuestosOG       ($idProy, $idVersion, $idSup)
{
$sql = "SELECT * FROM t07_prop_sup
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t07_cod_prop_sup = $idSup" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}
function NuevoSupuestosOG     ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."' ";
$codigo = $this->Autogenerate("t07_prop_sup","t07_cod_prop_sup", $where);

$arrayfields = array(	't02_cod_proy', 't02_version' ,
						't07_cod_prop_sup', 't07_sup',
						'usr_crea','fch_crea' ) ;
$arrayvalues = array(  	$_POST['t02_cod_proy'], $_POST['t02_version'], $codigo,
						$_POST['t07_sup'],
						$this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryInsert("t07_prop_sup",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarSupuestosOG()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t07_cod_prop_sup=".$_POST['t07_cod_prop_sup'];

$arrayfields = array(	't07_sup',
						'usr_actu','fch_actu' ) ;
$arrayvalues = array(  	$_POST['t07_sup'],
						$this->Session->UserID, $this->fecha) ;
$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t07_prop_sup",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarSupuestosOG  ($idProy,$idVersion, $idSup )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t07_cod_prop_sup=".$idSup;

$sql1 = "DELETE FROM t07_prop_sup WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region

#region "Objetivo Especifico - Definicion "
function ListadoDefinicionOE   ($idProy, $idVersion)
{
$sql = "SELECT 	t02_cod_proy,
				t02_version,
				t08_cod_comp,
				t08_comp_desc,
				CONCAT(t08_cod_comp,'.- ', SUBSTRING(t08_comp_desc,1,120 )) AS descripcion,
				usr_crea,
				fch_crea
  		 FROM t08_comp
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		 ORDER BY t08_cod_comp ASC ;";

		 error_log("\n alditis:  ".$sql, 3, '/var/www/log.log');

return $this->ExecuteQuery($sql);

}
function GetDefinicionOE       ($idProy, $idVersion, $idComp)
{
$sql = "SELECT * FROM t08_comp
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t08_cod_comp = $idComp" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}
function NuevoDefinicionOE     ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."' ";
$codigo = $this->Autogenerate("t08_comp","t08_cod_comp", $where);

$arrayfields = array(	't02_cod_proy', 't02_version' ,
						't08_cod_comp', 't08_comp_desc',
						'usr_crea','fch_crea' ) ;
$arrayvalues = array(  	$_POST['t02_cod_proy'], $_POST['t02_version'], $codigo,
						$_POST['t08_comp_desc'],
						$this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryInsert("t08_comp",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarDefinicionOE()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp'];

$arrayfields = array(	't08_comp_desc',
						'usr_actu','fch_actu' ) ;
$arrayvalues = array(  	$_POST['t08_comp_desc'],
						$this->Session->UserID, $this->fecha) ;
$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t08_comp",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarDefinicionOE  ($idProy,$idVersion, $idComp )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t08_cod_comp=".$idComp;

$sql1 = "DELETE FROM t08_comp WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region

#region "Objetivo Especifico - Indicadores "
function ListadoIndicadoresOE   ($idProy, $idVersion, $idComp)
{
$sql = "SELECT * FROM t08_comp_ind
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t08_cod_comp = $idComp
		 ORDER BY t08_cod_comp_ind ASC  ;";
return $this->ExecuteQuery($sql);
}
function GetIndicadoresOE       ($idProy, $idVersion, $idComp, $idInd)
{
$sql = "SELECT * FROM t08_comp_ind
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t08_cod_comp = $idComp
		  AND  t08_cod_comp_ind= $idInd" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}
function NuevoindIcadoresOE     ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']."";
$codigo = $this->Autogenerate("t08_comp_ind","t08_cod_comp_ind", $where);

$arrayfields = array('t02_cod_proy', 't02_version' , 't08_cod_comp',
					't08_cod_comp_ind', 't08_ind', 't08_um',
					't08_mta', 't08_fv',
					't08_obs', 'usr_crea','fch_crea' ) ;

$arrayvalues = array(  $_POST['t02_cod_proy'], $_POST['t02_version'], $_POST['t08_cod_comp'],
					   $codigo, $_POST['t08_ind'], $_POST['t08_um'],
					$_POST['t08_mta'], $_POST['t08_fv'],
					$_POST['t08_obs'], $this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryInsert("t08_comp_ind",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarIndicadoresOE()
{
$arrayfields = array('t08_ind', 't08_um',
					't08_mta', 't08_fv',
					't08_obs', 'usr_actu','fch_actu' ) ;

$arrayvalues = array($_POST['t08_ind'], $_POST['t08_um'],
					$_POST['t08_mta'], $_POST['t08_fv'],
					$_POST['t08_obs'], $this->Session->UserID, $this->fecha) ;

$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']. " AND t08_cod_comp_ind=".$_POST['t08_cod_comp_ind'];

$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t08_comp_ind",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarIndicadoresOE  ($idProy,$idVersion, $idComp, $idInd )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t08_cod_comp=".$idComp." AND t08_cod_comp_ind=".$idInd."";

$sql1 = "DELETE FROM t08_comp_ind WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region

#region "Objetivo Especifico - Supuestos "
function ListadoSupuestosOE   ($idProy, $idVersion, $idComp)
{
$sql = "SELECT * FROM t08_comp_sup
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t08_cod_comp = $idComp;";
return $this->ExecuteQuery($sql);
}
function GetSupuestosOE       ($idProy, $idVersion, $idComp, $idSup)
{
$sql = "SELECT * FROM t08_comp_sup
		WHERE  t02_cod_proy = '$idProy'
		  AND  t02_version = '$idVersion'
		  AND  t08_cod_comp = $idComp
		  AND  t08_cod_comp_sup= $idSup" ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));
}
function NuevoSupuestosOE     ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']."";
$codigo = $this->Autogenerate("t08_comp_sup","t08_cod_comp_sup", $where);

$arrayfields = array(	't02_cod_proy', 't02_version', 't08_cod_comp', 't08_cod_comp_sup', 't08_sup', 'usr_crea', 'fch_crea' ) ;
$arrayvalues = array(  $_POST['t02_cod_proy'], $_POST['t02_version'], $_POST['t08_cod_comp'], $codigo , $_POST['t08_sup'], $this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryInsert("t08_comp_sup",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarSupuestosOE()
{
$arrayfields = array('t08_sup', 'usr_actu', 'fch_actu' ) ;
$arrayvalues = array($_POST['t08_sup'], $this->Session->UserID, $this->fecha) ;


$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']. " AND t08_cod_comp_sup=".$_POST['t08_cod_comp_sup'];

$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t08_comp_sup",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarSupuestosOE  ($idProy,$idVersion, $idComp, $idInd )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t08_cod_comp=".$idComp." AND t08_cod_comp_sup=".$idInd."";

$sql1 = "DELETE FROM t08_comp_sup WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region

#Region "Objetivo Especifico - Actividades "
function ListadoActividades   ($idProy, $idVersion)
{
$sql = "SELECT t09.*
		FROM t09_act t09
		WHERE  t09.t02_cod_proy = '$idProy'
		  AND  t09.t02_version = '$idVersion'
		ORDER BY t09.t08_cod_comp, t09.t09_cod_act ASC";
return $this->ExecuteQuery($sql);
}

function ListadoActividadesOE($idProy, $idVersion, $idComp)
{
    $sql = "SELECT 	t09.t02_cod_proy,
				t09.t02_version,
				t09.t08_cod_comp,
				t09.t09_cod_act,
				t09.t09_act,
				CONCAT(t09.t08_cod_comp,'.',t09.t09_cod_act, '.- ', SUBSTRING(t09.t09_act,1,100)) AS descripcion,
				t09.t09_obs,
				t09.usr_crea,
				t09.fch_crea
    		FROM t09_act t09
    		WHERE  t09.t02_cod_proy = '$idProy'
    		  AND  t09.t02_version = '$idVersion'
    		  AND  t09.t08_cod_comp = '$idComp'
    		 ORDER BY t09.t08_cod_comp, t09.t09_cod_act ASC; ";

    return $this->ExecuteQuery($sql);
}

function GetActividadesOE       ($idProy, $idVersion, $idComp, $idAct)
{
$sql = "SELECT *
		FROM t09_act t09
		WHERE  t09.t02_cod_proy = '$idProy'
		  AND  t09.t02_version = '$idVersion'
		  AND  t09.t08_cod_comp = $idComp
		  AND  t09.t09_cod_act= $idAct " ;

return mysql_fetch_assoc($this->ExecuteQuery($sql));

}
function NuevoActividadesOE     ()
{
$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']."";
$codigo = $this->Autogenerate("t09_act","t09_cod_act", $where);

$arrayfields = array(	't02_cod_proy', 't02_version', 't08_cod_comp',
						't09_cod_act', 't09_act', 't09_obs',
						'usr_crea', 'fch_crea' ) ;

$arrayvalues = array(  $_POST['t02_cod_proy'], $_POST['t02_version'], $_POST['t08_cod_comp'],
					   $codigo , $_POST['t09_act'], $_POST['t09_obs'],
					   $this->Session->UserID, $this->fecha) ;

$sql1 = $this->DBOBaseMySQL->createqueryInsert("t09_act",$arrayfields, $arrayvalues);

return $this->ExecuteCreate($sql1);

}
function ActualizarActividadesOE()
{
$arrayfields = array(	't09_act', 't09_obs',
						'usr_actu', 'fch_actu' ) ;

$arrayvalues = array(  $_POST['t09_act'], $_POST['t09_obs'],
					   $this->Session->UserID, $this->fecha) ;

$where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']. " AND t09_cod_act=".$_POST['t09_cod_act'];

$sql1 = $this->DBOBaseMySQL->createqueryUpdate("t09_act",$arrayfields, $arrayvalues, $where);

return $this->ExecuteUpdate($sql1);

}
function EliminarActividadesOE  ($idProy,$idVersion, $idComp, $idAct )
{
$where = "t02_cod_proy='".$idProy ."' AND t02_version='".$idVersion."' AND t08_cod_comp=".$idComp." AND t09_cod_act=".$idAct."";

$sql1 = "DELETE FROM t09_act WHERE (".$where.")" ;

return $this->ExecuteDelete($sql1);

}
#end Region

#Region "Sub-Actividades"
function ListaSubActividades	($idProy, $idVersion, $idComp, $idAct , $anio=1)
{
$sql = " SELECT
		  sub.t02_cod_proy,
		  sub.t02_version,
		  sub.t08_cod_comp  as comp,
		  sub.t09_cod_act	as act,
		  sub.t09_cod_sub	as subact,
		  sub.t09_sub		as descripcion,
		  sub.t09_um		as um,
		  sub.t09_mta		as meta,
		  sum(case when mta.t09_mes=1 then mta.t09_mta else NULL end) as m1,
		  sum(case when mta.t09_mes=2 then mta.t09_mta else NULL end) as m2,
		  sum(case when mta.t09_mes=3 then mta.t09_mta else NULL end) as m3,
		  sum(case when mta.t09_mes=4 then mta.t09_mta else NULL end) as m4,
		  sum(case when mta.t09_mes=5 then mta.t09_mta else NULL end) as m5,
		  sum(case when mta.t09_mes=6 then mta.t09_mta else NULL end) as m6,
		  sum(case when mta.t09_mes=7 then mta.t09_mta else NULL end) as m7,
		  sum(case when mta.t09_mes=8 then mta.t09_mta else NULL end) as m8,
		  sum(case when mta.t09_mes=9 then mta.t09_mta else NULL end) as m9,
		  sum(case when mta.t09_mes=10 then mta.t09_mta else NULL end) as m10,
		  sum(case when mta.t09_mes=11 then mta.t09_mta else NULL end) as m11,
		  sum(case when mta.t09_mes=12 then mta.t09_mta else NULL end) as m12
		FROM        t09_subact sub
		  LEFT JOIN t09_sub_act_mtas mta ON (sub.t02_cod_proy = mta.t02_cod_proy AND sub.t02_version = mta.t02_version AND sub.t08_cod_comp = mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub = mta.t09_cod_sub AND mta.t09_anio=$anio)
		WHERE  sub.t02_cod_proy = '$idProy'
		  AND  sub.t02_version = '$idVersion'
		  AND  sub.t08_cod_comp = '$idComp'
		  AND  sub.t09_cod_act= '$idAct'
		GROUP BY 1,2,3,4,5,6,7,8
		ORDER BY
		  sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub;" ;
return $this->ExecuteQuery($sql);
}
function ListaSubActividades2	($idProy, $idVersion, $idComp, $idAct)
{
$anio = 1;
$sql = " SELECT
		  sub.t02_cod_proy,
		  sub.t02_version,
		  sub.t08_cod_comp  as comp,
		  sub.t09_cod_act	as act,
		  sub.t09_cod_sub	as subact,
		  sub.t09_sub		as descripcion,
		  sub.t09_um		as um,
		  sub.t09_mta		as meta,
		  CONCAT(sub.t08_cod_comp,'.',sub.t09_cod_act,'.',sub.t09_cod_sub, '.- ', SUBSTRING(sub.t09_sub,1,100)) AS descrip,
		  sum(case when mta.t09_mes=1 then mta.t09_mta else NULL end) as m1,
		  sum(case when mta.t09_mes=2 then mta.t09_mta else NULL end) as m2,
		  sum(case when mta.t09_mes=3 then mta.t09_mta else NULL end) as m3,
		  sum(case when mta.t09_mes=4 then mta.t09_mta else NULL end) as m4,
		  sum(case when mta.t09_mes=5 then mta.t09_mta else NULL end) as m5,
		  sum(case when mta.t09_mes=6 then mta.t09_mta else NULL end) as m6,
		  sum(case when mta.t09_mes=7 then mta.t09_mta else NULL end) as m7,
		  sum(case when mta.t09_mes=8 then mta.t09_mta else NULL end) as m8,
		  sum(case when mta.t09_mes=9 then mta.t09_mta else NULL end) as m9,
		  sum(case when mta.t09_mes=10 then mta.t09_mta else NULL end) as m10,
		  sum(case when mta.t09_mes=11 then mta.t09_mta else NULL end) as m11,
		  sum(case when mta.t09_mes=12 then mta.t09_mta else NULL end) as m12
		FROM        t09_subact sub
		  LEFT JOIN t09_sub_act_mtas mta ON (sub.t02_cod_proy = mta.t02_cod_proy AND sub.t02_version = mta.t02_version AND sub.t08_cod_comp = mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub = mta.t09_cod_sub AND mta.t09_anio=$anio)
		WHERE  sub.t02_cod_proy = '$idProy'
		  AND  sub.t02_version = '$idVersion'
		  AND  sub.t08_cod_comp = '$idComp'
		  AND  sub.t09_cod_act= '$idAct'
		GROUP BY 1,2,3,4,5,6,7,8
		ORDER BY
		  sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub;" ;
return $this->ExecuteQuery($sql);
}
#endregion



#Actividades - Indicadores
function ListadoIndicadoresAct   ($idProy, $idVersion, $idComp, $idAct)
{
    $sql = "SELECT
                t02_cod_proy,
                t02_version,
                t08_cod_comp,
                t09_cod_act,
                t09_cod_act_ind,
                t09_ind,
                t09_um,
                t09_mta,
                t09_fv,
                t09_obs,
                CONCAT(t08_cod_comp,'.',t09_cod_act,'.',t09_cod_act_ind, '.- ', SUBSTRING(t09_ind,1,100)) AS descripcion
            FROM t09_act_ind
		    WHERE  t02_cod_proy = '$idProy'
            AND  t02_version = '$idVersion'
            AND  t08_cod_comp = '$idComp'
            AND  t09_cod_act  = '$idAct' ; ";
    return $this->ExecuteQuery($sql);
}

function GetIndicadoresAct($idProy, $idVersion, $idComp, $idAct, $idInd)
{
    $sql = "SELECT * FROM t09_act_ind
		    WHERE  t02_cod_proy = '$idProy'
    		  AND  t02_version  = '$idVersion'
    		  AND  t08_cod_comp = '$idComp'
    		  AND  t09_cod_act  = '$idAct'
    		  AND  t09_cod_act_ind= '$idInd' ;" ;

    return mysql_fetch_assoc($this->ExecuteQuery($sql));
}

function ListadoIndicadoresActMta($idProy, $idVersion, $idComp, $idAct, $Ind)
{
    $SP = "sp_sel_ml_indicadores_metas";
    $params = array($idProy, $idVersion, $idComp, $idAct, $Ind);
    $ret = $this->ExecuteProcedureReader($SP, $params);
    return $ret;
}

function NuevoindIcadoresAct()
{
    $where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']." AND t09_cod_act=".$_POST['t09_cod_act'];
    $codigo = $this->Autogenerate("t09_act_ind","t09_cod_act_ind", $where);

    $sql = "INSERT INTO t09_act_ind (t02_cod_proy, t02_version , t08_cod_comp, t09_cod_act,
    					t09_cod_act_ind, t09_ind, t09_um, t09_mta, t09_fv, t09_obs, usr_crea,fch_crea) 
			VALUES ('".$_POST['t02_cod_proy']."', ".$_POST['t02_version'].", ".$_POST['t08_cod_comp'].", ".$_POST['t09_cod_act'].",
				   $codigo, '".$_POST['t09_ind']."', '".$_POST['t09_um']."', ".$_POST['t09_mta'].", '".$_POST['t09_fv']."', 
				   '".$_POST['t09_obs']."', '".$this->Session->UserID."', '".$this->fecha."');";

    mysql_fetch_assoc($this->ExecuteQuery($sql));

    return true;
}

function ActualizarIndicadoresAct()
{
    $arrayfields = array('t09_ind', 't09_um',
    					't09_mta', 't09_fv',
    					't09_obs', 'usr_actu','fch_actu' ) ;
    $arrayvalues = array($_POST['t09_ind'], $_POST['t09_um'],
    					$_POST['t09_mta'], $_POST['t09_fv'],
    					$_POST['t09_obs'], $this->Session->UserID, $this->fecha) ;

    $where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']." AND t09_cod_act=".$_POST['t09_cod_act']." AND t09_cod_act_ind=".$_POST['t09_cod_act_ind'];
    $sql1 = $this->DBOBaseMySQL->createqueryUpdate("t09_act_ind",$arrayfields, $arrayvalues, $where);

    return $this->ExecuteUpdate($sql1);
}

function EliminarIndicadoresAct($idProy,$idVersion, $idComp, $idAct, $idInd)
{
    $sql = "DELETE FROM t09_act_ind ".
            "WHERE t02_cod_proy='".$idProy ."' ".
            "AND t02_version=".$idVersion." ".
            "AND t08_cod_comp=".$idComp." ".
            "AND t09_cod_act=".$idAct." ".
            "AND t09_cod_act_ind=".$idInd;
    return $this->ExecuteQuery($sql);
}
#EndRegion

function ListadoCompNOC($idProy)
{
$SP = "sp_sel_comp_noc";
$params = array($idProy);
$ret = $this->ExecuteProcedureReader($SP , $params);
return $ret;
}

function ListadoActNOC($idProy, $idComp)
{
$SP = "sp_sel_act_noc";
$params = array($idProy, $idComp);
$ret = $this->ExecuteProcedureReader($SP , $params);
return $ret;
}

function ListadoSubActNOC($idProy, $idComp, $idAct)
{
$SP = "sp_sel_sub_noc";
$params = array($idProy, $idComp, $idAct);
$ret = $this->ExecuteProcedureReader($SP , $params);
return $ret;
}

    /**
     * Registra la Característica del Indicador de la Actividad (Producto)
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Característica
     * @return  boolean
     *
     */
    function registrarCaracteristica()
    {
        $where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']." AND t09_cod_act=".$_POST['t09_cod_act']." AND t09_cod_act_ind=".$_POST['t09_cod_act_ind'];
        $codigo = $this->Autogenerate("t09_act_ind_car", "t09_cod_act_ind_car", $where);
        $arrayfields = array('t02_cod_proy', 't02_version' , 't08_cod_comp', 't09_cod_act',
                            't09_cod_act_ind', 't09_cod_act_ind_car', 't09_ind',
                            't09_fv',
                            't09_obs', 'usr_crea','fch_crea');

        $arrayvalues = array($_POST['t02_cod_proy'], $_POST['t02_version'], $_POST['t08_cod_comp'], $_POST['t09_cod_act'], $_POST['t09_cod_act_ind'],
                            $codigo, $_POST['t09_ind'],
                            $_POST['t09_fv'],
                            $_POST['t09_obs'], $this->Session->UserID, $this->fecha);

        $sql = $this->DBOBaseMySQL->createqueryInsert("t09_act_ind_car", $arrayfields, $arrayvalues);        
        $ret = $this->ExecuteCreate($sql);

        /*$ctrls = $_POST['ctrls'];

        $sql = "INSERT INTO t09_act_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_car, ".
                        "t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        foreach ($ctrls as $anio=>$meses){
            foreach ($meses as $mes=>$on){
                $sql .= " ('".$_POST['t02_cod_proy']."', ".$_POST['t02_version'].", ".$_POST['t08_cod_comp'].", ".$_POST['t09_cod_act'].
                        ", ".$codigo.", ".$anio.", ".$mes.", 1, '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
            }
        }

        mysql_fetch_assoc($this->ExecuteQuery(trim($sql, ',') . ";"));*/

        return true;
    }

    /**
     * Lista las Características de una Actividad (Producto)
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @param   int  $idComp       Id del Componente
     * @param   int  $idAct        Id de la Actividad
     * @return  lista
     *
     */
    function listarCaracteristicas($idProy, $idVersion, $idComp, $idAct, $idInd)
    {
        $sql = "SELECT * FROM t09_act_ind_car
                WHERE  t02_cod_proy = '$idProy'
                AND  t02_version = '$idVersion'
                AND  t08_cod_comp = '$idComp'
                AND  t09_cod_act  = '$idAct'
                AND  t09_cod_act_ind  = '$idInd';";

        return $this->ExecuteQuery($sql);
    }

    /**
     * Obtiene la Característica de la Actividad (Producto)
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @param   int  $idComp       Id del Componente
     * @param   int  $idAct        Id de la Actividad
     * @return  lista
     *
     */
    function obtenerCaracteristica($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar)
    {
        $sql = "SELECT * FROM t09_act_ind_car
                WHERE  t02_cod_proy = '$idProy'
                AND  t02_version  = '$idVersion'
                AND  t08_cod_comp = '$idComp'
                AND  t09_cod_act  = '$idAct'
                AND  t09_cod_act_ind  = '$idInd'
                AND  t09_cod_act_ind_car = '$idCar' ;" ;

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    /**
     * Actualiza Característica de la Actividad (Producto)
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Característica
     * @return  boolean
     *
     */
    function actualizarCaracteristica()
    {
        $arrayfields = array('t09_ind',
        					't09_mta', 't09_fv',
        					't09_obs', 'usr_actu','fch_actu');
        $arrayvalues = array($_POST['t09_ind'],
        					$_POST['t09_mta'], $_POST['t09_fv'],
        					$_POST['t09_obs'], $this->Session->UserID, $this->fecha) ;

        $where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."' AND t08_cod_comp=".
                  $_POST['t08_cod_comp']." AND t09_cod_act=".$_POST['t09_cod_act']." AND t09_cod_act_ind=".$_POST['t09_cod_act_ind']." AND t09_cod_act_ind_car=".$_POST['t09_cod_act_ind_car'];
        $sql = $this->DBOBaseMySQL->createqueryUpdate("t09_act_ind_car", $arrayfields, $arrayvalues, $where);
        $this->ExecuteUpdate($sql);
        return true;
    }

    /**
     * Elimina Característica de la Actividad (Producto)
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @param   int  $idComp       Id del Componente
     * @param   int  $idAct        Id de la Actividad
     * @return  boolean
     *
     */
    function eliminarCaracteristica($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar)
    {
        $sql = "DELETE FROM t09_act_ind_car ".
                "WHERE t02_cod_proy='".$idProy ."' ".
                "AND t02_version=".$idVersion." ".
                "AND t08_cod_comp=".$idComp." ".
                "AND t09_cod_act=".$idAct." ".
                "AND t09_cod_act_ind=".$idInd." ".
                "AND t09_cod_act_ind_car=".$idCar;

        return $this->ExecuteQuery($sql);
    }

    /**
     * Listar Característica de la Actividad (Producto)
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @param   int  $idComp       Id del Componente
     * @param   int  $idAct        Id de la Actividad
     * @param   int  $idCar        Id de la Característica
     * @return  boolean
     *
     */
    function listarCaracteristicasCtrl($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar, $view)
    {
        $duracion = $this->obtenerDuracion($idProy, $idVersion);

        if($view){
            $sql = "SELECT GROUP_CONCAT(CONCAT('#ctrls-', c.t08_cod_comp,'-', c.t09_cod_act,'-', c.t09_cod_act_ind,'-', c.t09_cod_act_ind_car,'-','[', d.t02_anio, '][', ctrl.t09_car_mes, ']')) AS ids ";
        }
        else{
            $sql = "SELECT GROUP_CONCAT(CONCAT('#ctrls[', d.t02_anio, '][', ctrl.t09_car_mes, ']')) AS ids ";
        }

        $sql .= "FROM t02_duracion d
                JOIN t09_act_ind_car c
                    ON (c.t02_cod_proy=d.t02_cod_proy AND c.t02_version=d.t02_version)
                JOIN t09_act_ind_car_ctrl ctrl
                    ON (c.t02_cod_proy=ctrl.t02_cod_proy AND c.t02_version=ctrl.t02_version AND c.t08_cod_comp=ctrl.t08_cod_comp AND c.t09_cod_act=ctrl.t09_cod_act AND c.t09_cod_act_ind=ctrl.t09_cod_act_ind AND c.t09_cod_act_ind_car=ctrl.t09_cod_act_ind_car AND d.t02_anio=ctrl.t09_car_anio)
                WHERE d.t02_cod_proy='$idProy'
                AND d.t02_version='$idVersion'
                AND c.t08_cod_comp='$idComp'
                AND c.t09_cod_act='$idAct'
                AND c.t09_cod_act_ind='$idInd'
                AND c.t09_cod_act_ind_car='$idCar'
                AND ctrl.t09_car_ctrl = 1";

        $rb = mysql_fetch_assoc($this->ExecuteQuery($sql));

        $r['duracion'] = $duracion;
        $r['ids'] = str_replace("[", "\\[", str_replace("]", "\\]", $rb['ids']));
        return $r;
    }

    /**
     * Listar Característica del Producto para el Reporte
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @param   int  $idComp       Id del Componente
     * @param   int  $idAct        Id de la Actividad
     * @param   int  $idCar        Id de la Característica
     * @return  boolean
     *
     */
    function listarProgramacionCaracteristicasReporte($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar)
    {
        $sql = "SELECT t09_car_anio, t09_car_mes
                FROM t09_act_ind_car_ctrl ctrl
                WHERE t02_cod_proy='$idProy'
                AND t02_version='$idVersion'
                AND t08_cod_comp='$idComp'
                AND t09_cod_act='$idAct'
                AND t09_cod_act_ind='$idInd'
                AND t09_cod_act_ind_car='$idCar'";

        $r = $this->ExecuteQuery($sql);

        while($row = mysql_fetch_assoc($r)){
            $res[$row["t09_car_anio"]][$row["t09_car_mes"]] = 1;
        }

        return $res;
    }

    /**
     * Programa la Característica
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Característica
     * @return  boolean
     *
     */
    function programarCaracteristica($nuevo)
    {
        $codigo = $_POST['t09_cod_act_ind_car'];

        if ($nuevo){
            $where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']." AND t09_cod_act=".$_POST['t09_cod_act']." AND t09_cod_act_ind=".$_POST['t09_cod_act_ind'];
            $codigo = $this->Autogenerate("t09_act_ind_car", "t09_cod_act_ind_car", $where);
            $arrayfields = array('t02_cod_proy', 't02_version' , 't08_cod_comp', 't09_cod_act',
                                't09_cod_act_ind', 't09_cod_act_ind_car', 't09_ind',
                                't09_fv',
                                't09_obs', 'usr_crea','fch_crea');

            $arrayvalues = array($_POST['t02_cod_proy'], $_POST['t02_version'], $_POST['t08_cod_comp'], $_POST['t09_cod_act'], $_POST['t09_cod_act_ind'],
                                $codigo, $_POST['t09_ind'],
                                $_POST['t09_fv'],
                                $_POST['t09_obs'], $this->Session->UserID, $this->fecha);

            $sql = $this->DBOBaseMySQL->createqueryInsert("t09_act_ind_car", $arrayfields, $arrayvalues);
            $ret = $this->ExecuteCreate($sql);

        }
        else{
            $sql = "UPDATE t09_act_ind_car SET t09_ind = '".$_POST['t09_ind']."', ".
                            "t09_mta = '".$_POST['t09_mta']."', ".
                                "t09_fv = '".$_POST['t09_fv']."', ".
                            "t09_obs = '".$_POST['t09_obs']."', ".
                            "usr_actu = '".$this->Session->UserID."', ".
                            "fch_actu = '".$this->fecha."' ".
                            "WHERE t02_cod_proy = '".$_POST['t02_cod_proy']."' ".
                            "AND t02_version  = ".$_POST['t02_version']." ".
                            "AND t08_cod_comp = ".$_POST['t08_cod_comp']." ".
                            "AND t09_cod_act  = ".$_POST['t09_cod_act']." ".
                            "AND t09_cod_act_ind = ".$_POST['t09_cod_act_ind']." ".
                            "AND t09_cod_act_ind_car = ".$codigo;

            $this->ExecuteUpdate($sql);

            $sql = "DELETE FROM t09_act_ind_car_ctrl ".
                    "WHERE t02_cod_proy = '".$_POST['t02_cod_proy']."' ".
                    "AND t02_version  = ".$_POST['t02_version']." ".
                    "AND t08_cod_comp = ".$_POST['t08_cod_comp']." ".
                    "AND t09_cod_act  = ".$_POST['t09_cod_act']." ".
                    "AND t09_cod_act_ind = ".$_POST['t09_cod_act_ind']." ".
                    "AND t09_cod_act_ind_car = ".$codigo;

            mysql_fetch_assoc($this->ExecuteQuery($sql));
        }

        $ctrls = $_POST['ctrls'];

        $sql = "INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, ".
                        "t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        foreach ($ctrls as $anio=>$meses){
            foreach ($meses as $mes=>$on){
                $sql .= " ('".$_POST['t02_cod_proy']."', ".$_POST['t02_version'].", ".$_POST['t08_cod_comp'].", ".$_POST['t09_cod_act'].
                            ", ".$_POST['t09_cod_act_ind'].", ".$codigo.", ".$anio.", ".$mes.", 1, '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
            }
        }

        mysql_fetch_assoc($this->ExecuteQuery(trim($sql, ',') . ";"));
        return true;
    }

    /**
     * Obtiene la duración del proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function obtenerDuracion($idProy, $idVersion)
    {
        $sql = "SELECT MAX(t02_anio) AS duracion FROM t02_duracion
        WHERE  t02_cod_proy = '$idProy'
        AND  t02_version  = '$idVersion'";

        $r = mysql_fetch_assoc($this->ExecuteQuery($sql));
        return intval($r['duracion']);
    }

    /**
     * Obtiene intervalo de la duración del proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function obtenerDuracionEnElAnio($idProy, $idVersion)
    {
		return $this->ExecuteFunction("fn_sel_duracion_en_el_anio" , array($idProy, $idVersion));
    }

    /**
     * Obtiene la duración total en meses del proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function obtenerTotalMeses($idProy, $idVersion)
    {
        $sql = "SELECT (t02_num_mes + t02_num_mes_amp) AS duracion 
        FROM t02_dg_proy
        WHERE t02_cod_proy = '$idProy'
        AND t02_version  = '$idVersion'";

        $r = mysql_fetch_assoc($this->ExecuteQuery($sql));
        return intval($r['duracion']);
    }

    /**
     * Obtiene el factor actual de la duración del proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function obtenerFactorDuracion($idProy, $idVersion)
    {
        return $this->ExecuteFunction("fn_sel_factor_duracion" , array($idProy, $idVersion));
    }
    /**
     * Lista los Entregables
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function listarEntregables($idProy, $idVersion)
    {
        $duracion = $this->obtenerDuracion($idProy, $idVersion);

        $sql = "SELECT GROUP_CONCAT(CONCAT('#entregables[', t02_anio, '][', t02_mes, ']')) AS ids FROM t02_entregable
                WHERE  t02_cod_proy = '$idProy'
                AND  t02_version  = '$idVersion'";

        $rb = mysql_fetch_assoc($this->ExecuteQuery($sql));

        $r['duracion'] = $duracion;
        $r['ids'] = str_replace("[", "\\[", str_replace("]", "\\]", $rb['ids']));
        return $r;
    }

    /**
     * Lista los Entregables para el Reporte
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function listarEntregablesReporte($idProy, $idVersion)
    {
        $sql = "SELECT t02_anio, t02_mes FROM t02_entregable
                WHERE  t02_cod_proy = '$idProy'
                AND  t02_version  = '$idVersion'";

        $r = $this->ExecuteQuery($sql);

        while($row = mysql_fetch_assoc($r)){
            $res[$row["t02_anio"]][$row["t02_mes"]] = 1;
        }

        return $res;
    }

    /**
     * Programa los Entregables
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function programarEntregables()
    {
        $sql = "DELETE FROM t02_entregable ".
                "WHERE t02_cod_proy = '".$_POST['t02_cod_proy']."' ".
                "AND t02_version  = ".$_POST['t02_version'];

        mysql_fetch_assoc($this->ExecuteQuery($sql));

        $ctrls = $_POST['entregables'];

        $sql = "INSERT INTO t02_entregable (t02_cod_proy, t02_version, t02_anio, t02_mes, ".
                        "usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        foreach ($ctrls as $anio=>$meses){
            foreach ($meses as $mes=>$on){
                $sql .= " ('".$_POST['t02_cod_proy']."', ".$_POST['t02_version'].", ".$anio.", ".$mes.", '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
            }
        }

        mysql_fetch_assoc($this->ExecuteQuery(trim($sql, ',') . ";"));
        return true;
    }

    /**
     * Programa el Indicador
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Característica
     * @return  boolean
     *
     */
    function programarIndicador($nuevo)
    {
        $codigo = $_POST['t09_cod_act_ind'];
        if ($nuevo){
            $where = "t02_cod_proy='".$_POST['t02_cod_proy'] ."' AND t02_version='".$_POST['t02_version']."'  AND t08_cod_comp=".$_POST['t08_cod_comp']." AND t09_cod_act=".$_POST['t09_cod_act'];
            $codigo = $this->Autogenerate("t09_act_ind","t09_cod_act_ind", $where);
            $arrayfields = array('t02_cod_proy', 't02_version' , 't08_cod_comp', 't09_cod_act',
                            't09_cod_act_ind', 't09_ind', 't09_um',
                            't09_mta', 't09_fv',
                            't09_obs', 'usr_crea','fch_crea' ) ;

            $arrayvalues = array(  $_POST['t02_cod_proy'], $_POST['t02_version'], $_POST['t08_cod_comp'], $_POST['t09_cod_act'],
                            $codigo, $_POST['t09_ind'], $_POST['t09_um'],
                            $_POST['t09_mta'], $_POST['t09_fv'],
                            $_POST['t09_obs'], $this->Session->UserID, $this->fecha) ;

            $sql1 = $this->DBOBaseMySQL->createqueryInsert("t09_act_ind",$arrayfields, $arrayvalues);
            $ret = $this->ExecuteCreate($sql1);
        }
        else{
            $sql = "UPDATE t09_act_ind SET t09_ind = '".$_POST['t09_ind']."', ".
                            "t09_mta = '".$_POST['t09_mta']."', ".
                            "t09_um = '".$_POST['t09_um']."', ".
                            "t09_fv = '".$_POST['t09_fv']."', ".
                            "t09_obs = '".$_POST['t09_obs']."', ".
                            "usr_actu = '".$this->Session->UserID."', ".
                            "fch_actu = '".$this->fecha."' ".
                            "WHERE t02_cod_proy = '".$_POST['t02_cod_proy']."' ".
                            "AND t02_version  = ".$_POST['t02_version']." ".
                            "AND t08_cod_comp = ".$_POST['t08_cod_comp']." ".
                            "AND t09_cod_act  = ".$_POST['t09_cod_act']." ".
                            "AND t09_cod_act_ind = ".$codigo;

            $this->ExecuteQuery($sql);

            $sql = "DELETE FROM t09_act_ind_mtas ".
                            "WHERE t02_cod_proy = '".$_POST['t02_cod_proy']."' ".
                            "AND t02_version  = ".$_POST['t02_version']." ".
                            "AND t08_cod_comp = ".$_POST['t08_cod_comp']." ".
                            "AND t09_cod_act  = ".$_POST['t09_cod_act']." ".
                            "AND t09_cod_act_ind = ".$codigo;

            $this->ExecuteQuery($sql);
        }

        $sql = "INSERT INTO t09_act_ind_mtas (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, ".
                        "usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        $metas = $_POST['metas'];

        foreach ($metas as $anio=>$meses){
            foreach ($meses as $mes=>$meta){
                if($meta != NULL && $meta != ''){
                    $sql .= " ('".$_POST['t02_cod_proy']."', ".$_POST['t02_version'].", ".$_POST['t08_cod_comp'].", ".$_POST['t09_cod_act'].", ".$codigo.", ".$anio.", ".$mes.", ".$meta.", '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
                }
            }
        }

        mysql_fetch_assoc($this->ExecuteQuery(trim($sql, ',') . ";"));

        return true;
    }

    /**
     * Mostrar Programa de Indicador de Producto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Característica
     * @return  boolean
     *
     */
    function getProgramaIndicador($idProy, $idVersion, $idComp, $idAct, $idInd, $anio)
    {
        $sql = "SELECT t09_ind_mes, t09_ind_mta FROM t09_act_ind_mtas ".
                "WHERE t02_cod_proy = '".$idProy."' ".
                "AND t02_version  = ".$idVersion." ".
                "AND t08_cod_comp = ".$idComp." ".
                "AND t09_cod_act  = ".$idAct." ".
                "AND t09_cod_act_ind = ".$idInd." ".
                "AND t09_ind_anio = ".$anio;

        $r = $this->ExecuteQuery($sql);

        while($row = mysql_fetch_assoc($r)){
            $res[$row["t09_ind_mes"]] = $row["t09_ind_mta"];
        }

        return $res;
    }
    
    /**
     * Mostrar Programa de Indicador de Producto
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param	int $idProy			Codigo del Proyecto
     * @param	int $idVersion		Id de la Version del Proyecto
     * @return  resource
     *
     */
    public function ListaTodasSubActividadesParaReporte($idProy, $idVersion, $anio=1)
	{
	$sql = " SELECT
			  sub.t02_cod_proy,
			  sub.t02_version,
			  sub.t08_cod_comp  as comp,
			  sub.t09_cod_act	as act,
			  sub.t09_cod_sub	as subact,
			  sub.t09_sub		as descripcion,
			  sub.t09_um		as um,
			  sub.t09_mta		as meta,
			  sum(case when mta.t09_mes=1 then mta.t09_mta else NULL end) as m1,
			  sum(case when mta.t09_mes=2 then mta.t09_mta else NULL end) as m2,
			  sum(case when mta.t09_mes=3 then mta.t09_mta else NULL end) as m3,
			  sum(case when mta.t09_mes=4 then mta.t09_mta else NULL end) as m4,
			  sum(case when mta.t09_mes=5 then mta.t09_mta else NULL end) as m5,
			  sum(case when mta.t09_mes=6 then mta.t09_mta else NULL end) as m6,
			  sum(case when mta.t09_mes=7 then mta.t09_mta else NULL end) as m7,
			  sum(case when mta.t09_mes=8 then mta.t09_mta else NULL end) as m8,
			  sum(case when mta.t09_mes=9 then mta.t09_mta else NULL end) as m9,
			  sum(case when mta.t09_mes=10 then mta.t09_mta else NULL end) as m10,
			  sum(case when mta.t09_mes=11 then mta.t09_mta else NULL end) as m11,
			  sum(case when mta.t09_mes=12 then mta.t09_mta else NULL end) as m12
			FROM        t09_subact sub
			  LEFT JOIN t09_sub_act_mtas mta ON (sub.t02_cod_proy = mta.t02_cod_proy AND sub.t02_version = mta.t02_version AND sub.t08_cod_comp = mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub = mta.t09_cod_sub AND mta.t09_anio=$anio)
			WHERE  sub.t02_cod_proy = '$idProy'
			  AND  sub.t02_version = '$idVersion'			  
			GROUP BY 1,2,3,4,5,6,7,8
			ORDER BY
			  sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub;" ;
			return $this->ExecuteQuery($sql);
	}
	
	/**
	 * Listado de Indicadores de Componente
	 *
	 * @author  DA
	 * @since   Version 2.1
	 * @access  public
	 * @param	int $idProy			Codigo del Proyecto
	 * @param	int $idVersion		Id de la Version del Proyecto
	 * @param	int $idComp			Id del Componente 
	 * @return  resource
	 *
	 */
	public function ListadoIndicadoresComponentes ($idProy, $idVersion, $idComp)
	{
	    $sql = "SELECT
	                t02_cod_proy,
	                t02_version,
	                t08_cod_comp,
	                t09_cod_act,
	                t09_cod_act_ind,
	                t09_ind,
	                t09_um,
	                t09_mta,
	                t09_fv,
	                t09_obs,
	                CONCAT(t08_cod_comp,'.',t09_cod_act,'.',t09_cod_act_ind, '.- ', SUBSTRING(t09_ind,1,100)) AS descripcion
	            FROM t09_act_ind
			    WHERE  t02_cod_proy = '$idProy'
	            AND  t02_version = '$idVersion'
	            AND  t08_cod_comp = '$idComp' ";
	    return $this->ExecuteQuery($sql);
	}
    

//fin de la Clase BLMarcoLogico
}

