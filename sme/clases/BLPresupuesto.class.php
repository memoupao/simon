<?php
require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");

class BLPresupuesto extends BLBase
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
    
    // egion Presupuesto Lista
    function ListaActividades($idProy, $idVersion, $idComp)
    {
        $SP = "sp_sel_actividad_costeo";
        $params = array(
            $idProy,
            $idVersion,
            $idComp
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaSubActividades($idProy, $idVersion, $idComp, $idAct)
    {
        $SP = "sp_sel_subactividad_costeo";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaSubActividadesCateg($idProy, $idVersion, $idComp, $idAct, $idSubAct)
    {
        $SP = "sp_sel_subactividad_costeo_categ";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSubAct
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaSubActividadesCosteo($idProy, $idVersion, $idComp, $idAct, $idSubAct, $Categ)
    {
        $SP = "sp_sel_subactividad_costeo_gastos";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSubAct,
            $Categ
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function GetCosteo($idProy, $idVersion, $idComp, $idAct, $idSubAct, $idcosteo)
    {
        $SP = "sp_get_costeo";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSubAct,
            $idcosteo
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function ListaFuentesFinanc($idProy, $idVersion, $idComp, $idAct, $idSubAct, $idcosteo)
    {
        $SP = "sp_sel_cost_fuentes";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSubAct,
            $idcosteo
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // ndRegion
    function ListaFuentesFinanciamiento($idProy, $showFE = true)
    {
        $SP = "sp_sel_fuentes_financ";
        $params = array(
            $idProy,
            $showFE
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // egion Informe-Financiero / Mensual
    function ListaActividades_InfFinanc($idProy, $idComp, $idAnio = 0, $idMes = 0, $idFteFinanc = 0)
    {
        $SP = "sp_sel_inf_financ_act";
        $params = array(
            $idProy,
            $idComp,
            $idAnio,
            $idMes,
            $idFteFinanc
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaSubActividades_InfFinanc($idProy, $idComp, $idAct, $idAnio = 0, $idMes = 0, $idFteFinanc = 0)
    {
        $SP = "sp_sel_inf_financ_subact";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idAnio,
            $idMes,
            $idFteFinanc
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaCategoriaGasto_InfFinanc($idProy, $idComp, $idAct, $idSubAct, $idAnio = 0, $idMes = 0, $idFteFinanc = 0)
    {
        $SP = "sp_sel_inf_financ_categ";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idSubAct,
            $idAnio,
            $idMes,
            $idFteFinanc
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaActSubCat($idProy, $idComp, $idAct, $idSubAct, $cat, $idAnio, $nivel, $idFteFinanc)
    {
        $SP = "sp_lis_presup_ejec_men";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idSubAct,
            $cat,
            $idAnio,
            $nivel,
            $idFteFinanc
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // ndRegion
    
    // egion CRUD Costeo
    function NuevoCosteo(&$retcodigo)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $_POST['t09_cod_sub'],
            $_POST['t10_cost'],
            $_POST['cbocatgasto'],
            $_POST['t10_um'],
            $_POST['t10_cant'],
            $_POST['t10_cu'],
            $this->Session->UserID
        );
        $SP = "sp_ins_costeo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retcodigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function ActualizarCosteo(&$retcodigo)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $_POST['t09_cod_sub'],
            $_POST['t10_cod_cost'],
            $_POST['t10_cost'],
            $_POST['cbocatgasto'],
            $_POST['t10_um'],
            $_POST['t10_cant'],
            $_POST['t10_cu'],
            $_POST['txtobs_mf'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_costeo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $retcodigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function EliminarCosteo()
    {
        $params = array(
            $_REQUEST['t02_cod_proy'],
            $_REQUEST['t02_version'],
            $_REQUEST['t08_cod_comp'],
            $_REQUEST['t09_cod_act'],
            $_REQUEST['t09_cod_sub'],
            $_REQUEST['idcosto']
        );
        
        $SP = "sp_del_costeo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ActualizarFuentesFinan()
    {
        $SP = "sp_upd_cost_fte_finan";
        $Montos = $_POST['txtmonto'];
        $Instit = $_POST['txtInstit'];
        $countRet = 0;
        if (is_array($Montos)) {
            for ($ax = 0; $ax < count($Montos); $ax ++) {
                $idinst = $Instit[$ax];
                $monto = $Montos[$ax];
                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t02_version'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $_POST['t09_cod_sub'],
                    $_POST['t10_cod_cost'],
                    $idinst,
                    $monto,
                    $this->Session->UserID
                );
                $ret = $this->ExecuteProcedureEscalar($SP, $params);
                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }
        
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $_POST['t09_cod_sub'],
            $_POST['t10_cod_cost']
        );
        $this->ExecuteProcedureEscalar("sp_upd_cost_total_fte_finan", $params);
        
        if ($countRet > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // egion SubActividades
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
    // ndRegion
    
    // egion SubActividades Metas
    function GetMetasSubActividad($idProy, $idVersion, $idComp, $idAct, $idSAct, $idAnio = 1)
    {
        $sql = " SELECT
		  sub.t02_cod_proy, 
		  sub.t02_version, 
		  sub.t08_cod_comp,  
		  sub.t09_cod_act ,
		  sub.t09_cod_sub ,  
		  sub.t09_sub	  , 
		  sub.t09_um	  , 
		  sub.t09_mta	  ,
		  sum(case when mta.t09_mes=1 then mta.t09_mta else NULL end) as t09_mes1,
		  sum(case when mta.t09_mes=2 then mta.t09_mta else NULL end) as t09_mes2,
		  sum(case when mta.t09_mes=3 then mta.t09_mta else NULL end) as t09_mes3,
		  sum(case when mta.t09_mes=4 then mta.t09_mta else NULL end) as t09_mes4,
		  sum(case when mta.t09_mes=5 then mta.t09_mta else NULL end) as t09_mes5,
		  sum(case when mta.t09_mes=6 then mta.t09_mta else NULL end) as t09_mes6,
		  sum(case when mta.t09_mes=7 then mta.t09_mta else NULL end) as t09_mes7,
		  sum(case when mta.t09_mes=8 then mta.t09_mta else NULL end) as t09_mes8,
		  sum(case when mta.t09_mes=9 then mta.t09_mta else NULL end) as t09_mes9,
		  sum(case when mta.t09_mes=10 then mta.t09_mta else NULL end) as t09_mes10,
		  sum(case when mta.t09_mes=11 then mta.t09_mta else NULL end) as t09_mes11,
		  sum(case when mta.t09_mes=12 then mta.t09_mta else NULL end) as t09_mes12,
		  sum(mta.t09_mta) as t09_mta_anio
		FROM        t09_subact sub
		  LEFT JOIN t09_sub_act_mtas mta ON (sub.t02_cod_proy = mta.t02_cod_proy AND sub.t02_version = mta.t02_version AND sub.t08_cod_comp = mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub = mta.t09_cod_sub AND mta.t09_anio=$idAnio)
		WHERE  sub.t02_cod_proy = '$idProy' 
		  AND  sub.t02_version = '$idVersion' 
		  AND  sub.t08_cod_comp = '$idComp'
		  AND  sub.t09_cod_act= '$idAct'
		  AND  sub.t09_cod_sub = '$idSAct'
		GROUP BY 1,2,3,4,5,6,7,8 ;";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function GetAcumMetas($idProy, $idVersion, $idComp, $idAct, $idSAct, $idAnio = 1)
    {
        $sql = "
		SELECT (SELECT IFNULL(sum(mta.t09_mta),0) 
				FROM t09_sub_act_mtas mta
				WHERE
					  mta.t02_cod_proy = '$idProy'  
				  AND mta.t02_version  = '$idVersion' 
				  AND mta.t08_cod_comp = '$idComp'
				  AND mta.t09_cod_act  = '$idAct'
				  AND mta.t09_cod_sub  = '$idSAct'
				  AND mta.t09_anio < $idAnio
				) as MetaAcumAnio ,
				(SELECT IFNULL(sum(mta.t09_mta),0) 
				 FROM t09_sub_act_mtas mta
				 WHERE
					  mta.t02_cod_proy = '$idProy'  
				  AND mta.t02_version  = '$idVersion' 
				  AND mta.t08_cod_comp = '$idComp'
				  AND mta.t09_cod_act  = '$idAct'
				  AND mta.t09_cod_sub  = '$idSAct'
				  AND mta.t09_anio <> $idAnio
				 ) as MetaTotalAcum ; ";
        
        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function ActualizarMetasSubActividad()
    {
        $Anio = $_POST['cboAnios'];
        $numMeses = 12;
        $bret = false;
        
        $where = "		t02_cod_proy='" . $_POST['t02_cod_proy'] . "' 
			AND t02_version='" . $_POST['t02_version'] . "'  
			AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " 
			AND t09_cod_act=" . $_POST['t09_cod_act'] . " 
			AND t09_cod_sub=" . $_POST['t09_cod_sub'] . " 
			AND t09_anio=" . $Anio . " AND t09_mes='{@mes}'";
        
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't09_cod_act',
            't09_cod_sub',
            't09_anio',
            't09_mes',
            't09_mta',
            'fch_crea',
            'usr_crea'
        );
        
        for ($x = 1; $x <= $numMeses; $x ++) {
            $IndexMetaMes = "t09_mes" . $x;
            $MetaMes = $_POST[$IndexMetaMes];
            
            if ($MetaMes != "") {
                $lsWhere = str_replace("{@mes}", $x, $where);
                $sql = "SELECT COUNT(t09_mes) FROM t09_sub_act_mtas where (" . $lsWhere . ")";
                if ($this->GetValue($sql) > 0)                 // SI es mayor a 0, eontonces se actualiza
                {
                    
                    $arrayfieldsUPD = array(
                        't09_mta',
                        'fch_crea',
                        'usr_crea'
                    );
                    $arrayvalues = array(
                        $MetaMes,
                        $this->Session->UserID,
                        $this->fecha
                    );
                    
                    $sql = $this->DBOBaseMySQL->createqueryUpdate("t09_sub_act_mtas", $arrayfieldsUPD, $arrayvalues, $lsWhere);
                    // echo($sql."<br>");
                    $bret = $this->ExecuteUpdate($sql);
                } else                 // SI es igual a 0, eontonces se Inserta un Nuevo Registro
                {
                    $arrayvalues = array(
                        $_POST['t02_cod_proy'],
                        $_POST['t02_version'],
                        $_POST['t08_cod_comp'],
                        $_POST['t09_cod_act'],
                        $_POST['t09_cod_sub'],
                        $Anio,
                        $x,
                        $MetaMes,
                        $this->Session->UserID,
                        $this->fecha
                    );
                    
                    $sql = $this->DBOBaseMySQL->createqueryInsert("t09_sub_act_mtas", $arrayfields, $arrayvalues);
                    // echo($sql."<br>");
                    $bret = $this->ExecuteCreate($sql);
                }
            }
        }
        
        return $bret;
    }

    function ReplicarMetasSubActividad()
    {
        $Anio = $_POST['cboAnios'];
        $FinAnios = $_POST['txtFinAnio'];
        $numMeses = 12;
        $bret = false;
        
        // --> Eliminamos las Metas del Año y POsteriores
        $sql = "DELETE FROM t09_sub_act_mtas 
		WHERE t02_cod_proy='" . $_POST['t02_cod_proy'] . "' 
		  AND t02_version ='" . $_POST['t02_version'] . "'  
		  AND t08_cod_comp=" . $_POST['t08_cod_comp'] . " 
		  AND t09_cod_act =" . $_POST['t09_cod_act'] . " 
		  AND t09_cod_sub=" . $_POST['t09_cod_sub'] . " 
		  AND t09_anio  >=" . $Anio . "";
        $this->ExecuteDelete($sql);
        /* --------------------------------------------------- */
        $arrayfields = array(
            't02_cod_proy',
            't02_version',
            't08_cod_comp',
            't09_cod_act',
            't09_cod_sub',
            't09_anio',
            't09_mes',
            't09_mta',
            'fch_crea',
            'usr_crea'
        );
        
        for ($y = $Anio; $y <= $FinAnios; $y ++) {
            $Anio = $y;
            for ($x = 1; $x <= $numMeses; $x ++) {
                $IndexMetaMes = "t09_mes" . $x;
                $MetaMes = $_POST[$IndexMetaMes];
                if ($MetaMes != "") {
                    $arrayvalues = array(
                        $_POST['t02_cod_proy'],
                        $_POST['t02_version'],
                        $_POST['t08_cod_comp'],
                        $_POST['t09_cod_act'],
                        $_POST['t09_cod_sub'],
                        $Anio,
                        $x,
                        $MetaMes,
                        $this->Session->UserID,
                        $this->fecha
                    );
                    $sql = $this->DBOBaseMySQL->createqueryInsert("t09_sub_act_mtas", $arrayfields, $arrayvalues);
                    $bret = $this->ExecuteCreate($sql);
                }
            }
        }
        
        return $bret;
    }
    // ndRegion
    
    // esumenes
    function RepFuentesFinac($proy, $vs)
    {
        $SP = "sp_rpt_presup_fuentes";
        $params = array(
            $proy,
            $vs
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepCronogramaDesembolsoTrimestral($proy)
    {
        $SP = "sp_rpt_cron_desemb_trim";
        $params = array(
            $proy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function SaldoCategGasto($idProy, $idComp, $idAct, $idSubAct, $idCat)
    {
        $F = "fn_saldo_categ_gasto";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idSubAct,
            $idCat
        );
        $ret = $this->ExecuteFunction($F, $params);
        /*
         * print_r($this); exit();
         */
        return round($ret, 2);
    }

    function ListadoCatNOC($idProy, $idComp, $idAct, $idSubAct)
    {
        $SP = "sp_sel_cat_noc";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idSubAct
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Acum_Costos_Ejecutados($pProy, $pVers)
    {
        return $this->ExecuteProcedureReader("sp_get_costos_ejecutados", array(
            $pProy,
            $pVers
        ));
    }
    
    // ndResumenes
    function Componentes_CostoTotalEjecutado($proy, $anno)
    {
        $params = array(
            $proy,
            $anno
        );
        $sp = "fn_costo_componentes_anio";
        return $this->ExecuteFunction($sp, $params);
    }

    function Costos_ejecutados($proy, $anno, $act)
    {
        $params = array(
            $proy,
            $anno,
            $act
        );
        $sp = "fn_costos_ejecutados_anio";
        return $this->ExecuteFunction($sp, $params);
    }
    // fin de la Clase BLPOA
}

?>
