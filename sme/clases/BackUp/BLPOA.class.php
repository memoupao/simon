<?php
require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");

class BLPOA extends BLBase
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
    function GetProyecto($idProy, $idVersion)
    {
        return $this->Proyecto->GetProyecto($idProy, $idVersion);
    }

    function GetSubActividad($idProy, $idVersion, $idComp, $idAct, $idSAct)
    {
        $sql = "SELECT
		  t09.t02_cod_proy, t09.t02_version, t09.t08_cod_comp,
		  t09.t09_cod_act, t09.t09_cod_sub, t09.t09_sub,
		  t09.t09_fv, t09.t09_act_crit, t09.t09_mta, t09.t09_tipo_sub,
		  t09.t09_um, t09.t09_obs, t09.usr_crea, t09.est_audi
		FROM
		  t09_subact t09
		WHERE  t09.t02_cod_proy = '$idProy' 
		  AND  t09.t02_version = '$idVersion' 
		  AND  t09.t08_cod_comp = '$idComp'
		  AND  t09.t09_cod_act = '$idAct'  
		  AND  t09.t09_cod_sub = '$idSAct' ;";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }
    
    // egion Eventos POA
    function NuevoSubActividad()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " AND t09_cod_act=" . $_POST['t09_cod_act'] . "";
        $codigo = $this->Autogenerate("t09_subact", "t09_cod_sub", $where);
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't09_cod_act',
            't09_cod_sub',
            't09_sub',
            't09_um',
            't09_mta',
            't09_fv',
            't09_tipo_sub',
            't09_act_crit',
            't09_obs',
            'fch_crea',
            'usr_crea'
        );
        
        $arrayvalues = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $codigo,
            $_POST['t09_sub'],
            $_POST['t09_um'],
            $_POST['t09_mta'],
            $_POST['t09_fv'],
            $_POST['t09_tipo_sub'],
            $_POST['t09_act_crit'],
            $_POST['t09_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql = $this->DBOBaseMySQL->createqueryInsert("t09_subact", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql);
    }

    function ActualizarSubActividad()
    {
        $where = "t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t02_version='" . $_POST['t02_version'] . "'  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " AND t09_cod_act=" . $_POST['t09_cod_act'] . " AND t09_cod_sub=" . $_POST['t09_cod_sub'];
        $arrayfields = array(
            't09_sub',
            't09_um',
            't09_mta',
            't09_fv',
            't09_tipo_sub',
            't09_act_crit',
            't09_obs',
            'fch_actu',
            'usr_actu'
        );
        
        $arrayvalues = array(
            $_POST['t09_sub'],
            $_POST['t09_um'],
            $_POST['t09_mta'],
            $_POST['t09_fv'],
            $_POST['t09_tipo_sub'],
            $_POST['t09_act_crit'],
            $_POST['t09_obs'],
            $this->Session->UserID,
            $this->fecha
        );
        
        $sql = $this->DBOBaseMySQL->createqueryUpdate("t09_subact", $arrayfields, $arrayvalues, $where);
        
        return $this->ExecuteUpdate($sql);
    }

    function EliminarSubActividad()
    {
        $where = "t02_cod_proy='" . $_GET['t02_cod_proy'] . "' AND t02_version='" . $_GET['t02_version'] . "'  AND t08_cod_comp=" . $_GET['t08_cod_comp'] . " AND t09_cod_act=" . $_GET['t09_cod_act'] . " AND t09_cod_sub=" . $_GET['t09_cod_sub'];
        
        $sql = "DELETE FROM  t09_subact WHERE (" . $where . ")";
        
        return $this->ExecuteDelete($sql);
    }
    
    // nd Region
    
    // fin de la Clase BLPOA
}

?>
