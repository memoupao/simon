<?php
require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");
require_once ("UploadFiles.class.php");
require_once ("HardCode.class.php");

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

    // Region SubActividades POA
    function Lista_Subactividad($idProy, $idVersion, $idComp, $idAct)
    {
        $SP = "sp_sel_subactividad";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function GetSubActividad($idProy, $idVersion, $idComp, $idAct, $idSAct)
    {
        return $this->ExecuteProcedureEscalar("sp_get_subactividad", array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSAct
        ));
    }

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
            'usr_crea',
            'fch_crea'
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
            ($_POST['t09_act_crit'] ? $_POST['t09_act_crit'] : 0),
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
            'usr_actu',
            'fch_actu'
        );

        $arrayvalues = array(
            $_POST['t09_sub'],
            $_POST['t09_um'],
            $_POST['t09_mta'],
            $_POST['t09_fv'],
            $_POST['t09_tipo_sub'],
            ($_POST['t09_act_crit'] ? $_POST['t09_act_crit'] : 0),
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
        // $this->Error=$sql ;
        // return false;
        return $this->ExecuteDelete($sql);
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
		  SUM(CASE WHEN mta.t09_mes=1 THEN mta.t09_mta ELSE NULL END) AS t09_mes1,
		  SUM(CASE WHEN mta.t09_mes=2 THEN mta.t09_mta ELSE NULL END) AS t09_mes2,
		  SUM(CASE WHEN mta.t09_mes=3 THEN mta.t09_mta ELSE NULL END) AS t09_mes3,
		  SUM(CASE WHEN mta.t09_mes=4 THEN mta.t09_mta ELSE NULL END) AS t09_mes4,
		  SUM(CASE WHEN mta.t09_mes=5 THEN mta.t09_mta ELSE NULL END) AS t09_mes5,
		  SUM(CASE WHEN mta.t09_mes=6 THEN mta.t09_mta ELSE NULL END) AS t09_mes6,
		  SUM(CASE WHEN mta.t09_mes=7 THEN mta.t09_mta ELSE NULL END) AS t09_mes7,
		  SUM(CASE WHEN mta.t09_mes=8 THEN mta.t09_mta ELSE NULL END) AS t09_mes8,
		  SUM(CASE WHEN mta.t09_mes=9 THEN mta.t09_mta ELSE NULL END) AS t09_mes9,
		  SUM(CASE WHEN mta.t09_mes=10 THEN mta.t09_mta ELSE NULL END) AS t09_mes10,
		  SUM(CASE WHEN mta.t09_mes=11 THEN mta.t09_mta ELSE NULL END) AS t09_mes11,
		  SUM(CASE WHEN mta.t09_mes=12 THEN mta.t09_mta ELSE NULL END) AS t09_mes12,
		  SUM(mta.t09_mta) AS t09_mta_anio,
		  p.t02_nom_proy,
		  DATE_FORMAT(p.t02_fch_ini, '%d/%m/%Y') AS fch_inicio,
		  DATE_FORMAT(p.t02_fch_ter, '%d/%m/%Y') AS fch_termino,
		  i.t01_nom_inst
		FROM        t09_subact sub
		  LEFT JOIN t09_sub_act_mtas mta ON (sub.t02_cod_proy = mta.t02_cod_proy AND sub.t02_version = mta.t02_version AND sub.t08_cod_comp = mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub = mta.t09_cod_sub AND mta.t09_anio=$idAnio)
		  LEFT JOIN t02_dg_proy p ON (p.t02_cod_proy=sub.t02_cod_proy AND p.t02_version=sub.t02_version)
		  LEFT JOIN t01_dir_inst i ON (i.t01_id_inst=p.t01_id_inst)
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
                        $this->fecha,
                        $this->Session->UserID
                    );

                    $sql = $this->DBOBaseMySQL->createqueryInsert("t09_sub_act_mtas", $arrayfields, $arrayvalues);
                    // echo($sql."<br>");
                    $bret = $this->ExecuteCreate($sql);
                }
            }
        }
        // --> Actualizamos Metas Totales
        if ($_POST['t02_version'] > 1) {
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t02_version'],
                $_POST['t08_cod_comp'],
                $_POST['t09_cod_act'],
                $_POST['t09_cod_sub'],
                $Anio
            );
            $ret = $this->ExecuteProcedureEscalar("sp_upd_subact_total_metas", $params);
        } else {
            $sql = "DELETE FROM t09_sub_act_mtas where t02_cod_proy='" . $_POST['t02_cod_proy'] . "' AND t09_mta <=0  ";
            $this->ExecuteCreate($sql);
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

        // --> Actualizamos Metas Totales
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $_POST['t09_cod_sub'],
            $Anio
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_subact_total_metas", $params);

        return $bret;
    }
    // ndRegion

    // egion Reportes
    function ListadoSubActividades($proy, $version, $comp, $act)
    {
        $SP = "sp_rpt_poa_subact";
        $params = array(
            $proy,
            $version,
            $comp,
            $act
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_Financ_Lista_Personal_CA($proy, $ver)
    {
        $params = array(
            $proy,
            $ver
        );
        return $this->ExecuteProcedureReader("sp_ml_per", $params);
    }

    function Inf_Financ_Lista_Equi_CA($proy, $ver)
    {
        $params = array(
            $proy,
            $ver
        );
        return $this->ExecuteProcedureReader("sp_ml_equi", $params);
    }

    function Inf_Financ_Lista_Par_CA($proy, $ver)
    {
        $params = array(
            $proy,
            $ver
        );
        return $this->ExecuteProcedureReader("sp_ml_partida", $params);
    }

    // ndRegion Reportes

    // egion POA - Restructuracion
    function POA_Listado($idProy)
    {
        return $this->ExecuteProcedureReader("sp_sel_poa", array($idProy));
    }

    function listarPOAsTecSinVB()
    {
        return $this->ExecuteProcedureReader("sp_sel_poa_tec_vb", array($this->Session->UserID));
    }

    function listarPOAsFinSinVB()
    {
        return $this->ExecuteProcedureReader("sp_sel_poa_fin_vb", array($this->Session->UserID));
    }

    function POA_ListadoIndicadoresComponente($idProy, $vs, $anioPOA)
    {
        $SP = "sp_poa_ind_comp";
        $params = array(
            $idProy,
            $vs,
            $anioPOA
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function POA_ListadoIndicadoresActividad($idProy, $vs, $anioPOA) // --> Falta Desarrollar
    {
        $SP = "sp_poa_ind_act";
        $params = array(
            $idProy,
            $vs,
            $anioPOA
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function POA_ListadoSubActividades($idProy, $vs, $idComp, $idAct, $anioPOA)
    {
        $SP = "sp_poa_subact";
        $params = array(
            $idProy,
            $vs,
            $idComp,
            $idAct,
            $anioPOA
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        // print_r($this);
        return $ret;
    }

    function POA_ListadoSubActividadesMetas($idProy, $vs, $idComp, $idAct, $idSubAct, $anioPOA)
    {
        $SP = "sp_poa_subact_metas";
        $params = array(
            $idProy,
            $vs,
            $idComp,
            $idAct,
            $idSubAct,
            $anioPOA
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function POA_ListaAnexos($idProy, $anioPOA)
    {
        $SP = "sp_sel_poa_anexos";
        $params = array(
            $idProy,
            $anioPOA
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // ecurar los datos del POA.
    function POA_Seleccionar($idProy, $idAnio)
    {
        $params = array(
            $idProy,
            $idAnio
        );
        $rs = $this->ExecuteProcedureReader("sp_get_poa", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }

    // uardar Definicion del POA.
    function POACabUltimo($idProy)
    {
        return $this->ExecuteProcedureEscalar("sp_get_poa_ult", array(
            $idProy
        ));
    }

    function SaveNewPOAHeader($pParams, &$pRetvs)
    {
        array_push($pParams, $this->Session->UserID);
        $proy = $pParams['t02_cod_proy'];
        $anio = $pParams['cboanio'];
        $ret = $this->ExecuteProcedureEscalar("sp_poa_ins_cab", $pParams);

        if ($ret['numrows'] > 0) {
            $this->duplicarPlanCap($proy, $anio);
            $this->duplicarTemasCap($proy, $anio);
            $this->duplicarTemasAT($proy, $anio);
            $this->duplicarCred($proy, $anio);
            $this->duplicarTemasOtros($proy, $anio);

            $pRetvs = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function GuardarPOACabNuevo(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['t02_periodo'],
            $_POST['txtptoatencion'],
            $_POST['txtpolitica'],
            $_POST['txtbeneficiarios'],
            $_POST['txtotrasinterv'],
            $_POST['t02_estado'],
            $_POST['txtobserv_cmt'],
            $_POST['chkAprobado'],
            '0'
        );
        return $this->SaveNewPOAHeader($params, $retvs);
    }

    function duplicarPlanCap($proy, $anio)
    {
        $params = array(
            $proy,
            $anio
        );
        $SP = "sp_sel_dup_plan_cap";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
    }

    function duplicarTemasCap($proy, $anio)
    {
        $params = array(
            $proy,
            $anio
        );
        $SP = "sp_sel_plan_dup_cap_tema";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
    }

    function duplicarCred($proy, $anio)
    {
        $params = array(
            $proy,
            $anio
        );
        $SP = "sp_plan_cre_dup";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
    }

    function duplicarTemasAT($proy, $anio)
    {
        $params = array(
            $proy,
            $anio
        );
        $SP = "sp_plan_at_dup";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
    }

    function duplicarTemasOtros($proy, $anio)
    {
        $params = array(
            $proy,
            $anio
        );
        $SP = "sp_plan_otros_dup";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
    }

    function GuardarPOACabActualizar(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['t02_periodo'],
            $_POST['txtptoatencion'],
            $_POST['txtpolitica'],
            $_POST['txtbeneficiarios'],
            $_POST['txtotrasinterv'],
            $_POST['t02_estado'],
            $_POST['txtobserv_cmt'],
            $_POST['chkAprobado'],
            $this->Session->UserID,
            $_POST['vb_se_tec']
        );

        $ret = $this->ExecuteProcedureEscalar("sp_poa_upd_cab", $params);

        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function GuardarPOACabActualizar_CMF(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_anio'],
            $_POST['txtobserv_cmf'],
            $_POST['t02_estado_mf'],
            $_POST['chkAprobado'],
            $this->Session->UserID,
            $_POST['vb_se_fin']
        );

        $ret = $this->ExecuteProcedureEscalar("sp_poa_upd_cabII", $params);

        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function POA_Eliminar($idProy, $idAnio)
    {
        $params = array(
            $idProy,
            $idAnio
        );
        $SP = "sp_poa_eliminar";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = ($ret['msg'] == '' ? $ret['msg'] : $this->Error);
            return false;
        }
    }

    function GenerarVersionPOA($idProy, $idAnio)
    {
        $params = array(
            $idProy,
            $idAnio,
            $this->Session->UserID
        );
        $SP = "sp_poa_genera_version";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['msg'] == '') {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function GenerarVersionPOA_CronINI($idProy, $idAnio)
    {
        $params = array(
            $idProy,
            $idAnio,
            $this->Session->UserID
        );
        $SP = "sp_poa_genera_version_inicial";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['msg'] == '') {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    // uardar Indicadores de Componente
    function GuardarIndicadoresComponente()
    {
        $codigos = $_POST['t08_cod_comp_ind'];
        $acums = $_POST['txtIndCompAcum'];
        $metas = $_POST['txtIndCompMeta'];
        $descr = $_POST['txtIndCompDescrip'];

        $countRet = 0;
        if (is_array($codigos)) {
            $SP = "sp_upd_poa_ind_comp";
            for ($ax = 0; $ax < count($codigos); $ax ++) {
                $cod = explode(".", $codigos[$ax]);
                $idComp = $cod[0];
                $idInd = $cod[1];

                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t02_version'],
                    $idComp,
                    $idInd,
                    $Meta,
                    $des,
                    $this->Session->UserID
                );
                /*
                 * echo("<pre>"); print_r($params); echo("</pre>"); exit();
                 */
                $ret = $this->ExecuteProcedureEscalar($SP, $params);
                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet > 0) {
            return true;
        } else {
            return false;
        }
    }

    function GuardarIndicadoresActividad()
    {
        $codigos = $_POST['t08_cod_comp_ind'];
        $acums = $_POST['txtIndCompAcum'];
        $metas = $_POST['txtIndCompMeta'];
        $descr = $_POST['txtIndCompDescrip'];

        $countRet = 0;
        if (is_array($codigos)) {
            $SP = "sp_upd_poa_ind_act";
            for ($ax = 0; $ax < count($codigos); $ax ++) {
                $cod = explode(".", $codigos[$ax]);
                $idComp = $cod[0];
                $idAct = $cod[1];
                $idInd = $cod[2];

                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t02_version'],
                    $idComp,
                    $idAct,
                    $idInd,
                    $Meta,
                    $des,
                    $this->Session->UserID
                );
                /*
                 * echo("<pre>"); print_r($params); echo("</pre>"); exit();
                 */
                $ret = $this->ExecuteProcedureEscalar($SP, $params);
                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet > 0) {
            return true;
        } else {
            return false;
        }
    }

    function GuardarMetas_SubActividades()
    {
        $SP = "sp_upd_poa_subact_metas";
        $idAnio = 0;
        $anios = $_POST['anios'];
        for ($x = 0; $x < count($anios); $x ++) {
            $nameIdMes = "anio_" . $anios[$x] . "_mes";
            $meses = implode("|", $_POST[$nameIdMes]);
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t02_version'],
                $_POST['t08_cod_comp'],
                $_POST['t09_cod_act'],
                $_POST['t09_cod_sub'],
                $anios[$x],
                $meses,
                $this->ConvertNumber($_POST['txtmpar']),
                $this->ConvertNumber($_POST['txtmprog']),
                $this->Session->UserID
            );
            $this->ExecuteProcedureEscalar($SP, $params);
            $idAnio = $anios[$x];
        }

        $this->ExecuteProcedureEscalar("sp_upd_poa_subact_metas2", array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $_POST['t09_cod_sub'],
            $idAnio
        ));
        $this->ExecuteProcedureEscalar("sp_upd_poa_subact_observa", array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t08_cod_comp'],
            $_POST['t09_cod_act'],
            $_POST['t09_cod_sub'],
            $_POST['txtobs_mt']
        ));

        return true;
    }

    // uardar Anexos(Documentacion disponible) del POA
    function GuardarDocumentacionAdicional()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $HC = new HardCode();
        $ext = strtolower($objFiles->getExtension());

        switch ($ext) {
            case 'doc':
            case 'docx':
            case 'xls':
            case 'xlsx':
            case 'pdf':
            case 'ppt':
            case 'pptx':
                $ret = true;
                break;
            default:
                $ret = false;
                break;
        }

        if (! $ret) {
            $this->Error = "Archivo cargado, no es permitido!!!";
            return false;
        }

        $NomFoto = $objFiles->getFileName();
        $objFiles->DirUpload .= $HC->FolderUploadPOA;
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_anio'],
            $NomFoto,
            $_POST['t02_desc_file'],
            $ext,
            $this->Session->UserID
        );

        $SP = "sp_ins_poa_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $saved = $objFiles->SavesAs($urlfoto);
            return $saved;
        } else {
            return false;
        }
    }

    // liminar Anexos del POA
    function EliminarDocumentacionAdicional()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_anio'],
            $_POST['t02_cod_anx']
        );

        $SP = "sp_del_poa_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $HC = new HardCode();
            $path = constant('APP_PATH') . $HC->FolderUploadPOA . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function UltimaVersionPoa($idProy, $anio)
    {
        return $this->ExecuteFunction('fn_version_proy_poa', array(
            $idProy,
            $anio
        ));
    }

    // egion Planes de Capacitación
    function PlanCapacitacion_Listado($idProy, $idVersion)
    {
        $SP = "sp_sel_plan_capac";
        $params = array(
            $idProy,
            $idVersion
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function PlanCapacitacion_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub)
    {
        $SP = "sp_get_plan_capac";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSub
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($rs);
        return $row;
    }

    function PlanCapacitacion_ListadoTemas($idProy, $idVersion, $idComp, $idAct, $idSub)
    {
        $SP = "sp_sel_plan_capac_temas";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSub
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function PlanCapacitacion_Guardar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $_POST['cbomodulo'],
            $_POST['txtcontenidos'],
            $this->Session->UserID,
            $_POST['txtnrohoras_total'],
            $_POST['txtnrobenef_total']
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_plan_capac", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PlanCapacitacion_Eliminar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_plan_capac", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function PlanCapacitacion_GuardarTema($Nuevo)
    {
        if ($Nuevo) {
            $params = array(
                $_POST['idProy'],
                $_POST['idVersion'],
                $_POST['idComp'],
                $_POST['idAct'],
                $_POST['idSub'],
                $_POST['txtcurso'],
                $_POST['txtnrohoras'],
                $_POST['txtnrobenef'],
                $this->Session->UserID,
                $_POST['txtnrohoras_total'],
                $_POST['txtnrobenef_total']
            );
            $ret = $this->ExecuteProcedureEscalar("sp_ins_plan_capac_tema", $params);
        } else {
            $params = array(
                $_POST['idProy'],
                $_POST['idVersion'],
                $_POST['idComp'],
                $_POST['idAct'],
                $_POST['idSub'],
                $_POST['idTema'],
                $_POST['txtcurso'],
                $_POST['txtnrohoras'],
                $_POST['txtnrobenef'],
                $this->Session->UserID,
                $_POST['txtnrohoras_total'],
                $_POST['txtnrobenef_total']
            );
            $ret = $this->ExecuteProcedureEscalar("sp_upd_plan_capac_tema", $params);
        }

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function PlanCapacitacion_EliminarTema()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $_POST['idTema'],
            $_POST['txtnrohoras_total'],
            $_POST['txtnrobenef_total']
        );

        $SP = "sp_del_plan_capac_tema";

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    // ndregion

    // egion Planes de Asistencia Tecnica
    function PlanAT_Listado($idProy, $idVersion)
    {
        $SP = "sp_sel_plan_at";
        $params = array(
            $idProy,
            $idVersion
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function PlanAT_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub)
    {
        $SP = "sp_get_plan_at";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSub
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($rs);
        return $row;
    }

    function PlanAT_Guardar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $_POST['cbomodulo'],
            $_POST['txtvisitas'],
            $_POST['txthoras'],
            $_POST['txtbenef'],
            $_POST['txtcontenidos'],
            $this->Session->UserID
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_plan_at", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PlanAT_Eliminar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_plan_at", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    // ndRegion Plan AT

    // egion Planes de Credito a Beneficiarios
    function PlanCred_Listado($idProy, $idVersion)
    {
        $SP = "sp_sel_plan_cred";
        $params = array(
            $idProy,
            $idVersion
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function PlanCred_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub)
    {
        $SP = "sp_get_plan_cred";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSub
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($rs);
        return $row;
    }

    function PlanCred_Guardar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $_POST['txtnprods'],
            $_POST['txtmonto'],
            $_POST['txtcuotas'],
            $_POST['txtobservaciones'],
            $this->Session->UserID
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_plan_cred", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PlanCred_GuardarBenef()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            implode("|", $_POST['beneficarios']),
            $this->Session->UserID
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_plan_cred_benef", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = ($ret['msg'] == '' ? $this->Error : $ret['msg']);
            return false;
        }
    }

    function PlanCred_GuardarBenef_Montos()
    {
        $pidPoy = $_POST['idProy'];
        $idVer = $_POST['idVersion'];
        $idComp = $_POST['idComp'];
        $idAct = $_POST['idAct'];
        $idSub = $_POST['idSub'];

        $arrbenef = $_POST['txtbenef'];
        $arrmonto = $_POST['txtmontos'];
        $arrcuota = $_POST['txtcuotas'];
        $arrtasa = $_POST['txttasa'];

        $SP = "sp_upd_plan_cred_benef_montos";

        $params = array(
            $pidPoy,
            $idVer,
            $idComp,
            $idAct,
            $idSub,
            implode("|", $arrbenef),
            implode("|", $arrmonto),
            implode("|", $arrcuota),
            implode("|", $arrtasa),
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PlanCred_Eliminar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_plan_cred", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function PlanCreditos_ListadoBenef($idProy, $idVersion, $idComp, $idAct, $idSub)
    {
        $SP = "sp_lis_plan_cred_benef";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSub
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function PlanCred_EliminarBenef()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $_POST['idBenef']
        );

        $ret = $this->ExecuteProcedureEscalar("sp_del_plan_cred_benef", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    // ndRegion Credito a Beneficiarios

    // egion Planes de Otros Servicios
    function PlanOtros_Listado($idProy, $idVersion)
    {
        $SP = "sp_sel_plan_otros";
        $params = array(
            $idProy,
            $idVersion
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function PlanOtros_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub)
    {
        $SP = "sp_get_plan_otros";
        $params = array(
            $idProy,
            $idVersion,
            $idComp,
            $idAct,
            $idSub
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($rs);
        return $row;
    }

    function PlanOtros_Guardar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $_POST['txtnomprod'],
            $_POST['cbotipo'],
            $_POST['txtnumero'],
            $_POST['txtbenef'],
            $_POST['txttotal'],
            $_POST['txtcontenidos'],
            $this->Session->UserID
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_plan_otros", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PlanOtros_Eliminar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['idVersion'],
            $_POST['idComp'],
            $_POST['idAct'],
            $_POST['idSub'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_plan_otros", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    // ndRegion Plan AT

    // egion Listados de Planes Especificos
    function Lista_InfTrim_PlanCapac($Tipo, $idProy, $idVersion, $idModulo, $idSubAct)
    {
        $SP = "sp_lis_plan_capac_trim";
        $params = array(
            $Tipo,
            $idProy,
            $idVersion,
            $idModulo,
            $idSubAct
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function Lista_InfTrim_PlanAT($Tipo, $idProy, $idVersion, $idModulo)
    {
        $SP = "sp_lis_plan_at_trim";
        $params = array(
            $Tipo,
            $idProy,
            $idVersion,
            $idModulo
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function Lista_InfTrim_PlanOtros($Tipo, $idProy, $idVersion, $idTipo)
    {
        $SP = "sp_lis_plan_otros_trim";
        $params = array(
            $Tipo,
            $idProy,
            $idVersion,
            $idTipo
        );

        return $this->ExecuteProcedureReader($SP, $params);
    }

    /**
     * Programar Entregables
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Programación
     * @return  boolean
     *
     */
    function programarEntregables()
    {
        $sql = "DELETE FROM t02_entregable_act_ind ".
                "WHERE t02_cod_proy = '".$_POST['idProy']."' ".
                "AND t02_version  = ".$_POST['idVersion']." ".
                "AND t08_cod_comp = ".$_POST['idComp'];

        $this->ExecuteQuery($sql);

        $indicadores = $_POST['indicadores'];

        $sql = "INSERT INTO t02_entregable_act_ind (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, ".
                        "t02_anio, t02_mes, t09_cod_act_ind_val, usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        foreach ($indicadores as $key=>$val){
            foreach ($val as $indicador=>$valor){
                if(!empty($valor)){
                    $sql .= " ('".$_POST['idProy']."', ".$_POST['idVersion'].", ".$_POST['idComp'].
                            ", ".$indicador.", ".$valor.", '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
                }
            }
        }

        $this->ExecuteQuery(trim($sql, ',') . ";");

        $sql = "DELETE FROM t02_entregable_act_ind_car ".
                        "WHERE t02_cod_proy = '".$_POST['idProy']."' ".
                        "AND t02_version  = ".$_POST['idVersion']." ".
                        "AND t08_cod_comp = ".$_POST['idComp'];

        $this->ExecuteQuery($sql);

        $caracteristicas = $_POST['caracteristicas'];

        $sql = "INSERT INTO t02_entregable_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, ".
                        "t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        foreach ($caracteristicas as $key=>$val){
            foreach ($val as $caracteristica=>$valor){
                if(!empty($valor)){
                    $sql .= " ('".$_POST['idProy']."', ".$_POST['idVersion'].", ".$_POST['idComp'].
                    ", ".$caracteristica.", '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
                }
            }
        }

        $this->ExecuteQuery(trim($sql, ',') . ";");

        return true;
    }

    /**
     * Lista la Programación en Entregables de los Indicadores
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function listarProgramacionIndicadores($idProy, $idVersion, $idComp)
    {
        $sql = "SELECT t09_cod_act, t09_cod_act_ind, t02_anio, t02_mes, t09_cod_act_ind_val
                FROM t02_entregable_act_ind
                WHERE t02_cod_proy = '$idProy'
                AND t02_version = '$idVersion'
                AND t08_cod_comp = '$idComp'";

        $r = $this->ExecuteQuery($sql);

        while($row = mysql_fetch_assoc($r)){
            $res[$row["t09_cod_act"]][$row["t09_cod_act_ind"]][$row["t02_anio"]][$row["t02_mes"]] = $row["t09_cod_act_ind_val"];
        }

        return $res;
    }

    /**
     * Lista la Programación en Entregables de las Características
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $idProy       Id del Proyecto
     * @param   int  $idVersion    Id de la Versión
     * @return  int
     *
     */
    function listarProgramacionCaracteristicas($idProy, $idVersion, $idComp)
    {
        $sql = "SELECT t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t02_anio, t02_mes
                FROM t02_entregable_act_ind_car
                WHERE t02_cod_proy = '$idProy'
                AND t02_version = '$idVersion'
                AND t08_cod_comp = '$idComp'";

        $r = $this->ExecuteQuery($sql);

        while($row = mysql_fetch_assoc($r)){
            $res[$row["t09_cod_act"]][$row["t09_cod_act_ind"]][$row["t09_cod_act_ind_car"]][$row["t02_anio"]][$row["t02_mes"]] = 1;
        }

        return $res;
    }

    /**
     * Lista el Plan de Capacitación
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $tipo        tipo de Plan
     * @param   int  $idProy      Id del Proyecto
     * @param   int  $idVersion   Id de la Versión
     * @param   int  $idModulo    Id del Módulo
     * @param   int  $idAct       Id de la Actividad
     * @return  int
     *
     */
    function listarPlanCapac($tipo, $idProy, $idVersion, $idModulo, $idAct)
    {
        $params = array($tipo,
                        $idProy,
                        $idVersion,
                        $idModulo,
                        $idAct);
        return $this->ExecuteProcedureReader("sp_lis_plan_capac", $params);
    }

    /**
     * Lista el Plan de Asistencia Técnica
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $tipo        tipo de Plan
     * @param   int  $idProy      Id del Proyecto
     * @param   int  $idVersion   Id de la Versión
     * @param   int  $idModulo    Id del Módulo
     * @param   int  $idAct       Id de la Actividad
     * @return  int
     *
     */
    function listarPlanAT($tipo, $idProy, $idVersion, $idModulo)
    {
        $params = array($tipo,
                        $idProy,
                        $idVersion,
                        $idModulo);

        return $this->ExecuteProcedureReader("sp_lis_plan_at", $params);
    }

    /**
     * Lista el Plan Otros
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   int  $tipo        tipo de Plan
     * @param   int  $idProy      Id del Proyecto
     * @param   int  $idVersion   Id de la Versión
     * @param   int  $idModulo    Id del Módulo
     * @param   int  $idAct       Id de la Actividad
     * @return  int
     *
     */
    function listarPlanOtros($tipo, $idProy, $idVersion, $idModulo)
    {
        $params = array($tipo,
                        $idProy,
                        $idVersion,
                        $idModulo);

        return $this->ExecuteProcedureReader("sp_lis_plan_otros", $params);
    }
    
    
    /**
     * Lista del Personal del Proyecto
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param   int  $proy      Id del Proyecto
     * @param   int  $ver   	Id de la Versión
     * @param   int  $anio    	Id del Anio
     * @return  resource
     *
     */
    public function getListaPersonalParaCronogramaActividades($proy, $ver, $anio)
    {
        
    	$sql = "SELECT  anio.t02_cod_proy, 
	anio.t02_version , 	
	per.t03_nom_per,
	aux.abrev, 	
	anio.t02_anio,
	per.t03_id_per,
	COUNT(anio.t02_anio) AS totalMeta,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=1 THEN mta.t03_mta ELSE 0 END) AS t03_mes1,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=2 THEN mta.t03_mta ELSE 0 END) AS t03_mes2,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=3 THEN mta.t03_mta ELSE 0 END) AS t03_mes3,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=4 THEN mta.t03_mta ELSE 0 END) AS t03_mes4,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=5 THEN mta.t03_mta ELSE 0 END) AS t03_mes5,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=6 THEN mta.t03_mta ELSE 0 END) AS t03_mes6,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=7 THEN mta.t03_mta ELSE 0 END) AS t03_mes7,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=8 THEN mta.t03_mta ELSE 0 END) AS t03_mes8,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=9 THEN mta.t03_mta ELSE 0 END) AS t03_mes9,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=10 THEN mta.t03_mta ELSE 0 END) AS t03_mes10,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=11 THEN mta.t03_mta ELSE 0 END) AS t03_mes11,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=12 THEN mta.t03_mta ELSE 0 END) AS t03_mes12,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio THEN mta.t03_mta ELSE 0 END) AS t03_tot_anio
	
FROM       t02_duracion     anio
LEFT  JOIN t03_mp_per       per ON (anio.t02_cod_proy=per.t02_cod_proy AND anio.t02_version=per.t02_version )
LEFT  JOIN t03_mp_per_metas mta ON (per.t02_cod_proy=mta.t02_cod_proy AND per.t02_version=mta.t02_version AND per.t03_id_per=mta.t03_id_per)
LEFT JOIN adm_tablas_aux aux ON (per.t03_um=aux.codi)

 WHERE anio.t02_cod_proy= '$proy' 
   AND anio.t02_version = '$ver' 
   AND anio.t02_anio = '$anio'  
GROUP BY 1,2,3,4,5 

order by per.t03_id_per, t02_anio ASC";
    	
    	$res = $this->ExecuteQuery($sql);    	
    	return $res;
    	    
    }

    /**    
    * Obtiene el Cronograma de Desembolsos
    * por Entregables mostrando Adelanto y Saldo
    *    
    * @author AQ
    * @since Version 2.1
    * @access public
    * @param string $idProyecto Código del Proyecto
    * @param integer $idVersion Numero de Version
    * @param integer $anio Año
    * @return resource
    *
    */
    public function getCronogramaDesembolsosPorEntregables($idProyecto, $idVersion, $anio)
    {
        $hc = new HardCode();

        $sql = 'SELECT  
                    t02_anio AS anio, 
                    t02_mes AS mes,
                    fn_nom_periodo_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS periodo, 
                    fn_numero_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS entregable,
                    fn_desembolso_planeado_del_entregable_fte(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS planeado,
                    fn_desembolso_planeado_del_entregable_fte_adelanto(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS adelanto,
                    fn_desembolso_planeado_del_entregable_fte_saldo(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS saldo,
                    fn_nom_periodo_anio_mes(t02_cod_proy, t02_version, t02_anio, fn_get_nro_mes_adelanto(t02_cod_proy, t02_version, t02_anio, t02_mes)) AS periodo_adelanto,
                    fn_nom_periodo_anio_mes(t02_cod_proy, t02_version, t02_anio, t02_mes - 1) AS periodo_saldo
                FROM
                    t02_entregable
                WHERE t02_cod_proy = "'.$idProyecto.'" 
                AND t02_version = "'.$idVersion.'" 
                AND t02_anio = "'.$anio.'" 
                ORDER BY t02_anio, t02_mes';
        
        return $this->ExecuteQuery($sql);

    }
    

} // fin de la Clase BLPOA

