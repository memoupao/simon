<?php
require_once ("BLBase.class.php");

class BLProyecto extends BLBase
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
    // -----------------------------------------------------------------------------
    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    // egion Proyectos
    // egion Read Contactos
    function Personal_ListadoEqui($proy)
    {
        $SP = "sp_sel_personal_equi";
        $params = array(
            $proy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function MaxVersion($CodProy)
    {
        return $this->GetValue("SELECT MAX(t02_version) FROM t02_dg_proy WHERE t02_cod_proy='" . $CodProy . "' ;");
    }

    function ProyectosListado($idInst)
    {
        return $this->ExecuteProcedureReader("sp_lis_proyectos", array(
            $idInst
        ));
    }
    // Agregado 30/11/2011
    function ProyectosListadoMT($idInst)
    {
        return $this->ExecuteProcedureReader("sp_lis_proyectos_mt", array(
            $idInst
        ));
    }
    // Agregado 01/12/2011
    function ProyectosListadoMF($idInst)
    {
        return $this->ExecuteProcedureReader("sp_lis_proyectos_mf", array(
            $idInst
        ));
    }

    function ProyectosPopup($idUsuario)
    {
        $SP = "sp_sel_proy_est";
        $params = array(
            $idUsuario
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ProyectoLisCargo()
    {
        $SP = "sp_lis_carg_cto";
        $params = array();
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ProyectoLisInst()
    {
        $SP = "sp_list_institucion_all";
        $params = array();
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ProyectoList()
    {
        $SP = "sp_lis_proy_alt";
        $params = array();
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function GetProyecto($idProy, $version)
    {
        $sql = " SELECT
		  inst.t01_sig_inst ,
		  proy.t02_cod_proy ,
		  proy.t02_nro_exp ,
		  proy.t02_version ,
		  proy.t02_nom_proy ,
		  proy.t01_id_inst,
		  -- inst.t01_nom_inst ,
		  inst.t01_nom_inst as t01_nom_inst_completo,
		  inst.t01_sig_inst as t01_nom_inst,
		  proy.t02_fin,
		  proy.t02_pro,
		  proy.t02_estado,
		  proy.usr_crea,
		  proy.t02_num_mes,
		  (CASE TRUNCATE((DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365),0) WHEN (DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365) THEN TRUNCATE((DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365),0) ELSE TRUNCATE((DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365),0) +1 END ) as duracion,
		  DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') as t02_fch_ini,
		  (
	     	SELECT 	(CASE WHEN v.t02_ver IS NULL
					 THEN 'Inicial'
					 ELSE
					 (CASE WHEN v.t02_tipo='POA' THEN CONCAT('Poa ',v.t02_ver) ELSE CONCAT('V.',v.t02_ver) END)
					 END) AS nom_version
			FROM t02_dg_proy p
			LEFT JOIN t02_proy_version v ON(p.t02_cod_proy=v.t02_cod_proy AND p.t02_version=v.t02_version)
			WHERE p.t02_cod_proy=proy.t02_cod_proy
			AND p.t02_version='$version'
			) AS version_poa,
		  	DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') as t02_fch_ter,
		  	apr.t02_vb_proy,
			apr.t02_aprob_proy,
			apr.t02_fch_vb_proy,
			apr.t02_fch_aprob_proy,
			apr.t02_obs_vb_proy,
			apr.t02_obs_aprob_proy,
			apr.t02_aprob_ml,
			apr.t02_aprob_cro,
			apr.t02_aprob_pre,
			apr.t02_fch_ml,
			apr.t02_fch_cro,
			apr.t02_fch_pre,
			apr.t02_aprob_ml_mon,
			apr.t02_aprob_cro_mon,
			apr.t02_aprob_pre_mon,
			apr.t02_obs_ml,
			apr.t02_obs_cro,
			apr.t02_obs_pre,
			apr.t02_fch_ml_mon,
			apr.t02_fch_cro_mon,
			apr.t02_fch_pre_mon,
			apr.t02_env_ml,
			apr.t02_env_pre,
			-- apr.t02_env_el,
			apr.t02_fch_env_ml,
			apr.t02_fch_env_pre,
			-- apr.t02_fch_env_el,
			apr.t02_fch_env_cro,
			apr.t02_env_cro,
            -- -------------------------------------------------->
            -- AQ 2.0 [28-11-2013 17:15]
            -- Envío a Revisión y Aprobación
            -- del Cronograma de Productos
			apr.t02_fch_env_croprod,
			apr.t02_env_croprod,
			apr.t02_obs_croprod,
			apr.t02_fch_croprod_mon,
			apr.t02_fch_croprod,
			apr.t02_aprob_croprod,
			apr.t02_aprob_croprod_mon
			-- --------------------------------------------------<
		FROM	  t02_dg_proy     proy
		LEFT JOIN t01_dir_inst    inst on (proy.t01_id_inst=inst.t01_id_inst)
		LEFT JOIN t02_aprob_proy  apr  on (proy.t02_cod_proy=apr.t02_cod_proy)
		WHERE proy.t02_cod_proy = '$idProy' AND proy.t02_version = '$version' ;";

        $ConsultaID = $this->ExecuteQuery($sql);

        $row = mysql_fetch_assoc($ConsultaID);

        return $row;
    }

    function ListaVersiones($idProy)
    {
        $sql = " SELECT p.t02_cod_proy,
				p.t02_version,
				p.t02_nom_proy,
				v.t02_tipo,
				(CASE WHEN v.t02_ver IS NULL
				 THEN 'Inicial'
				 ELSE
				 (CASE WHEN v.t02_tipo='POA' THEN CONCAT('Poa ',v.t02_ver) ELSE CONCAT('V.',v.t02_ver) END)
				 END) AS nom_version
		FROM t02_dg_proy p
		LEFT JOIN t02_proy_version v ON(p.t02_cod_proy=v.t02_cod_proy AND p.t02_version=v.t02_version)
		WHERE p.t02_cod_proy='$idProy'";
        /*
         * SELECT proy.t02_version , proy.t02_nom_proy FROM	 t02_dg_proy proy WHERE proy.t02_cod_proy = '$idProy';
         */

        return $this->ExecuteQuery($sql);
    }
    // modificado 01/12/2011
    function VerificarAnioPOA($idProy, $idVersion)
    {
        return $this->ExecuteFunction("fn_verificar_anio", array(
            $idProy,
            $idVersion
        ));
    }

    function GetAnioPOA($idProy, $idVersion)
    {
        return $this->ExecuteFunction("fn_anio_x_version_proy", array(
            $idProy,
            $idVersion
        ));
    }

    function PeriodosxAnio($CodProy, $Anio)
    {
        return $this->ExecuteProcedureReader("sp_sel_periodo_proy", array(
            $CodProy,
            $Anio
        ));
    }

    function NumeroMesesProy($CodProy)
    {
        return $this->ExecuteFunction("fn_numero_meses_proy", array(
            $CodProy,
            1
        ));
    }

    function NumeroAniosProy($CodProy)
    {
        return $this->ExecuteFunction("fn_duracion_proy", array(
            $CodProy,
            1
        ));
    }

    function NumeroAniosProyVer($pProy, $pVer)
    {
        return $this->ExecuteFunction("fn_duracion_proy", array(
            $pProy,
            $pVer
        ));
    }

    function UltimaVersionProy($pProy)
    {
        return $this->ExecuteFunction("fn_ult_version_proy", array(
            $pProy
        ));
    }

    function FechaInicioProy($pProy, $pVer)
    {
        return $this->ExecuteFunction("fn_fecha_inicio_proy", array(
            $pProy,
            $pVer
        ));
    }

    function FechaTerminoProy($pProy, $pVer)
    {
        return $this->ExecuteFunction("fn_fecha_termino_proy", array(
            $pProy,
            $pVer
        ));
    }

    function AnioMenor()
    {
        return $this->ExecuteFunction("fn_proy_order_by_anio_ini", array());
    }

    function AnioMax()
    {
        return $this->ExecuteFunction("fn_proy_order_by_anio_ter", array());
    }

    function ListaAniosProyecto($idProy, $idVersion)
    {
        $sql = "SELECT t02_anio AS codigo, CONCAT('Año ',t02_anio) AS descripcion , est_audi as bloqueado
                FROM t02_duracion
                WHERE t02_cod_proy = '$idProy' AND t02_version='$idVersion' ";

        return $this->Execute($sql);
    }

    // modificado 29/11/2011
    function actualizarRevision($idProy, $version)
    {
        $sql = "UPDATE t02_dg_proy
			SET
			env_rev = 1
			WHERE t02_cod_proy = '$idProy'
			AND t02_version = '$version';";
        $rsReturn = $this->Execute($sql);
        return $rsReturn;
    }
    // fin
    function ProyectoSeleccionar($Proy, $vs)
    {
        $SP = "sp_get_proyecto";
        $params = array(
            $Proy,
            $vs
        );
        $iRs = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($iRs);
        $iRs->free();
        return $row;
    }

    function SeleccionarCuentaProyeto($idProy)
    {
        $iRs = $this->ExecuteProcedureReader("sp_get_cuenta_proyecto", array(
            $idProy
        ));
        $row = mysqli_fetch_assoc($iRs);
        return $row;
    }
    // modificado 28/11/2011
    function SolicitudApro($solicitud, $t02_cod_proy, $vs)
    {
        $params = array(
            $solicitud,
            $t02_cod_proy,
            $vs
        );
        $SP = "sp_upd_sol_env_proy";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] >= 0) {
            return true;
        } else {
            if ($ret['msg'] != "") {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }
    // fin
    function GuardarCtaBancaria()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['cbobanco'],
            $_POST['cbotipocta'],
            $_POST['txtnrocuenta'],
            $_POST['cbomoneda'],
            $_POST['txtnombenef'],
            $this->Session->UserID
        );

        $SP = "sp_upd_cta_inst_proy";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] >= 0) {
            return true;
        } else {
            if ($ret['msg'] != "") {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    // ndRegion
    // egion CRUD Proyectos

    /**
     * Guarda en la base de datos un nuevo Proyecto.
     *
     * @author AD
     * @since Version 2.0
     * @access public
     * @param string $t02_nro_exp
     * @param string $t00_cod_linea
     * @param string $t01_id_inst
     * @param string $t01_id_cta Cuenta Bancaria de la institucion asignada a proyecto
     * @param string $t02_cod_proy
     * @param string $t02_nom_proy
     * @param string $t02_fch_apro
     * @param string $t02_estado
     * @param string $t02_fin
     * @param string $t02_pro
     * @param string $t02_ben_obj
     * @param string $t02_amb_geo
     * @param string $t02_moni_tema
     * @param string $t02_moni_fina
     * @param string $t02_moni_ext
     * @param string $t02_sup_inst
     * @param string $t02_dire_proy
     * @param string $t02_ciud_proy
     * @param string $t02_tele_proy
     * @param string $t02_fax_proy
     * @param string $t02_mail_proy
     * @param string $t02_fch_isc
     * @param string $t02_fch_ire
     * @param string $t02_fch_tre
     * @param string $t02_fch_tam
     * @param string $t02_num_mes
     * @param string $t02_num_mes_amp
     * @param string $t02_sector
     * @param string $t02_subsector
     * @param string $t02_cre_fe
     * @param float $t02_gratificacion
     *            Tasa de Gratificación
     * @param float $t02_porc_cts
     *            Tasa de CTS
     * @param float $t02_porc_ess
     *            Tasa de ESS
     * @param float $t02_porc_gast_func
     *            Tasa de Gastos Funcionales
     * @param float $t02_porc_linea_base
     *            Tasa de Linea Base
     * @param float $t02_porc_imprev
     *            Tasa de Imprevistos
     *
     * @param float $t02_proc_gast_superv	Gastos de Supervicion
     * @param float $t02_inst_asoc			Instituciones asociadas o colaboradas
     * 
     * @return boolean
     */
    function ProyectoNuevo($t02_nro_exp, $t00_cod_linea, $t01_id_inst, $t02_cod_proy, $t02_nom_proy, $t02_fch_apro, $t02_estado, $t02_fin, $t02_pro, $t02_ben_obj, $t02_amb_geo, $t02_moni_tema, $t02_moni_fina, $t02_moni_ext, $t02_sup_inst, $t02_dire_proy, $t02_ciud_proy, $t02_tele_proy, $t02_fax_proy, $t02_mail_proy, $t02_fch_isc, $t02_fch_ire, $t02_fch_tre, $t02_fch_tam, $t02_num_mes, $t02_num_mes_amp, /* $t02_sect_main, $t02_sector, $t02_subsector, $t02_prod_promovido,*/ $t02_cre_fe, $t02_gratificacion, $t02_porc_cts, $t02_porc_ess, $t02_porc_gast_func, $t02_porc_linea_base, $t02_porc_imprev, $t02_proc_gast_superv, $t02_inst_asoc)
    {

        // -------------------------------------------------->
        // DA 2.0 [22-10-2013 17:12]
        // Nueva variable $t00_cod_linea para el registro en la tabla t02_dg_proy en el nuevo campo t00_cod_linea
        //
        // DA 2.0 [23-10-2013 20:09]
        // Se adicionaron nuevas variables de las tasas para el proyecto $t02_gratificacion,
        // $t02_porc_cts, $t02_porc_ess, $t02_porc_gast_func, $t02_porc_linea_base, $t02_porc_imprev
        $params = array(
            $t02_nro_exp,
            $t00_cod_linea,
            $t01_id_inst,
            $t02_cod_proy,
            $t02_nom_proy,
            $this->ConvertDate($t02_fch_apro),
            $t02_estado,
            $t02_fin,
            $t02_pro,
            $t02_ben_obj,
            $t02_amb_geo,
            $t02_moni_tema,
            $t02_moni_fina,
            $t02_moni_ext,
            $t02_sup_inst,
            $t02_dire_proy,
            $t02_ciud_proy,
            $t02_tele_proy,
            $t02_fax_proy,
            $t02_mail_proy,
            $this->ConvertDate($t02_fch_isc),
            $this->ConvertDate($t02_fch_ire),
            $this->ConvertDate($t02_fch_tre),
            $this->ConvertDate($t02_fch_tam),
            $t02_num_mes,
            $t02_num_mes_amp,
            // -------------------------------------------------->
            // AQ 2.0 [27-11-2013 08:25]
            // Se mueve sección de Sectores a opción Sector Productivo
            // --------------------------------------------------<
            /*$t02_sect_main,
            $t02_sector,
            $t02_subsector,
            $t02_prod_promovido,*/
            $t02_cre_fe,

            $t02_gratificacion,
            $t02_porc_cts,
            $t02_porc_ess,
            $t02_porc_gast_func,
            $t02_porc_linea_base,
            $t02_porc_imprev,
            $t02_proc_gast_superv,
            $t02_inst_asoc,
            $this->Session->UserID
        );

        $SP = 'sp_ins_proyecto';

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            // $params = array($t02_cod_proy, $t01_id_inst, $_POST['t02_cta_bancaria'], $_POST['txtnombenefgiro'], $this->Session->UserID);
            // $this->ExecuteProcedureEscalar("sp_upd_cta_proy",$params);
            return true;
        } else {
            if ($ret != NULL) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    /**
     * Guarda en la base de datos la edición de un Proyecto.
     *
     * @author AD
     * @since Version 2.0
     * @access public
     * @param int $vs
     * @param string $t02_nro_exp
     *            Nro. Expediente
     * @param string $t00_cod_linea
     *            ID del tipo de Linea
     * @param string $t01_id_inst
     *            ID de la Institución
     * @param string $t02_cod_proy
     *            Codigo del proyecto
     * @param string $t02_nom_proy
     *            Nombre del Proyecto
     * @param string $t02_fch_apro
     *            Fecha de aprovación
     * @param string $t02_estado
     *            Estado o condición del proyecto
     * @param string $t02_fin
     * @param string $t02_pro
     * @param string $t02_ben_obj
     * @param string $t02_amb_geo
     * @param string $t02_moni_tema
     * @param string $t02_moni_fina
     * @param string $t02_moni_ext
     * @param string $t02_sup_inst
     * @param string $t02_dire_proy
     * @param string $t02_ciud_proy
     * @param string $t02_tele_proy
     * @param string $t02_fax_proy
     * @param string $t02_mail_proy
     * @param string $t02_fch_isc
     * @param string $t02_fch_ire
     * @param string $t02_fch_tre
     * @param string $t02_fch_tam
     * @param string $t02_num_mes
     * @param string $t02_num_mes_amp
     * @param string $t02_sector
     * @param string $t02_subsector
     * @param string $t02_cre_fe
     * @param float $t02_gratificacion
     *            Tasa de Gratificación
     * @param float $t02_porc_cts
     *            Tasa de CTS
     * @param float $t02_porc_ess
     *            Tasa de ESS
     * @param float $t02_porc_gast_func
     *            Tasa de Gastos Funcionales
     * @param float $t02_porc_linea_base
     *            Tasa de Linea Base
     * @param float $t02_porc_imprev
     *            Tasa de Imprevistos
     *
     * @return boolean
     */
    function ProyectoActualizar($vs, $t02_nro_exp, $t00_cod_linea, $t01_id_inst, $t02_cod_proy, $t02_nom_proy, $t02_fch_apro, $t02_estado, $t02_fin, $t02_pro, $t02_ben_obj, $t02_amb_geo, $t02_moni_tema, $t02_moni_fina, $t02_moni_ext, $t02_sup_inst, $t02_dire_proy, $t02_ciud_proy, $t02_tele_proy, $t02_fax_proy, $t02_mail_proy, $t02_fch_isc, $t02_fch_ire, $t02_fch_tre, $t02_fch_tam, $t02_num_mes, $t02_num_mes_amp, /* $t02_sect_main, $t02_sector, $t02_subsector, $t02_prod_promovido ,*/ $t02_cre_fe, $t02_gratificacion, $t02_porc_cts, $t02_porc_ess, $t02_porc_gast_func, $t02_porc_linea_base, $t02_porc_imprev, $t02_proc_gast_superv, $chkAprobado, $t02_inst_asoc)
    {
        // -------------------------------------------------->
        // DA 2.0 [22-10-2013 17:12]
        // Nueva variable $t00_cod_linea para el registro en la tabla t02_dg_proy en el nuevo campo t00_cod_linea
        $params = array(
            $vs,
            $t02_nro_exp,
            $t00_cod_linea,
            $t01_id_inst,
            $t02_cod_proy,
            $t02_nom_proy,
            $this->ConvertDate($t02_fch_apro),
            $t02_estado,
            $t02_fin,
            $t02_pro,
            $t02_ben_obj,
            $t02_amb_geo,
            $t02_moni_tema,
            $t02_moni_fina,
            $t02_moni_ext,
            $t02_sup_inst,
            $t02_dire_proy,
            $t02_ciud_proy,
            $t02_tele_proy,
            $t02_fax_proy,
            $t02_mail_proy,
            $this->ConvertDate($t02_fch_isc),
            $this->ConvertDate($t02_fch_ire),
            $this->ConvertDate($t02_fch_tre),
            $this->ConvertDate($t02_fch_tam),
            $t02_num_mes,
            $t02_num_mes_amp,            
            // -------------------------------------------------->
            // AQ 2.0 [27-11-2013 08:25]
            // Se mueve sección de Sectores a opción Sector Productivo
            // --------------------------------------------------<
            /*$t02_sect_main,
            $t02_sector,
            $t02_subsector,
            $t02_prod_promovido,*/
            $t02_cre_fe,

            $t02_gratificacion,
            $t02_porc_cts,
            $t02_porc_ess,
            $t02_porc_gast_func,
            $t02_porc_linea_base,
            $t02_porc_imprev,
            $t02_proc_gast_superv,
            $t02_inst_asoc,
            $this->Session->UserID,
            $chkAprobado
        );

        $SP = 'sp_upd_proyecto';

        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            // $params = array($t02_cod_proy, $t01_id_inst, $_POST['t02_cta_bancaria'], $_POST['txtnombenefgiro'], $this->Session->UserID);
            // $this->ExecuteProcedureEscalar("sp_upd_cta_proy",$params);
            return true;
        } else {
            // if($ret!=NULL) {$this->Error = $ret['msg'];}
            return false;
        }
    }

    function GetSuspentions($pProy)
    {
        $aQuery = "SELECT * FROM t02_suspenciones WHERE t02_cod_proy = '$pProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function GetMostRecentSuspention($pProy, $pVers)
    {
        $aNroSusp = $this->GetLastSuspNumber($pProy, $pVers);
        $aQuery = "SELECT * FROM t02_suspenciones WHERE t02_cod_proy = '$pProy' AND t02_version = $pVers AND t02_nro_susp = $aNroSusp";
        $aResult = $this->ExecuteQuery($aQuery);

        if (mysql_num_rows($aResult)) {
            return mysql_fetch_assoc($aResult);
        } else {
            return false;
        }
    }

    function GetLastSuspNumber($pProy, $pVer)
    {
        $aQuery = "SELECT IFNULL(MAX(t02_nro_susp), 0) AS nro_susp " . "FROM t02_suspenciones " . "WHERE t02_cod_proy = '$pProy' AND t02_version = $pVer";
        $aResult = $this->ExecuteQuery($aQuery);
        $aRow = mysql_fetch_assoc($aResult);

        return $aRow['nro_susp'];
    }

    function SuspendProject($pProy, $pVer, $pObs)
    {
        $aNroSusp = $this->GetLastSuspNumber($pProy, $pVer) + 1;
        $aQuery = "INSERT INTO t02_suspenciones " . "(t02_cod_proy, t02_version, t02_nro_susp, t02_fch_susp, t02_obs_susp, usr_crea, fch_crea, est_audi) " . "VALUES ('$pProy', $pVer, $aNroSusp, CURDATE(), '$pObs', '" . $this->Session->UserID . "', NOW(), '1')";

        return $this->Execute($aQuery);
    }

    function UnsuspendProject($pProy, $pVer, $pFchReinic, $pObs)
    {
        $aNroSusp = $this->GetLastSuspNumber($pProy, $pVer);
        $aQuery = "UPDATE t02_suspenciones " . "SET t02_fch_unsusp = CURDATE(), " . "t02_fch_reinic = '$pFchReinic', " . "t02_obs_unsusp = '$pObs', " . "usr_actu = '" . $this->Session->UserID . "', " . "fch_actu = NOW() " . "WHERE t02_cod_proy = '$pProy' AND t02_version = $pVer and t02_nro_susp = $aNroSusp";

        return $this->Execute($aQuery);
    }

    /**
     * Elimina un Proyecto en base el codigo y version del proyecto.
     *
     * @author AD
     * @since Version 2.0
     * @access public
     * @param string $t02_cod_proy
     * @param int $vs
     * @return boolean
     */
    function ProyectoEliminar($t02_cod_proy, $vs)
    {
        /*
         * $sql = "	DELETE from t02_dg_proy WHERE t02_cod_proy='$t02_cod_proy' and t02_version='$vs';";
         */
    /*$sql = "	DELETE from t02_dg_proy, t02_fuente_finan, t02_aprob_proy, t02_sector_prod
    			USING t02_dg_proy
    			INNER JOIN t02_fuente_finan USING(t02_cod_proy)
    			INNER JOIN t02_aprob_proy USING(t02_cod_proy)
    			INNER JOIN t02_sector_prod USING(t02_cod_proy)
    			WHERE t02_dg_proy.t02_cod_proy='$t02_cod_proy' and t02_dg_proy.t02_version='$vs';";
    return $this->ExecuteDelete($sql);*/
    $sp = 'sp_delete_proy';

        $params = array(
            $t02_cod_proy,
            $vs
        );
        $ret = $this->ExecuteProcedureEscalar($sp, $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }

    function ProyectoSolicituddg($proy, $tipo, $msg)
    {
        $params = array(
            $proy,
            $tipo,
            '1',
            $msg,
            $this->Session->UserID
        );

        $SP = "sp_upd_dg";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }

    function ProyectoSolicitudAprobacion($proy, $tipo, $msg)
    {
        $params = array(
            $proy,
            $tipo,
            '1',
            $msg,
            $this->Session->UserID
        );

        $SP = "sp_upd_sol_aprob";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }

    function ProyectoSolicitudAprobacionSCP($proy, $tipo, $msg, $scp)
    {
        $params = array(
            $proy,
            $tipo,
            '1',
            $msg,
            $this->Session->UserID,
            $scp
        );

        $SP = "sp_upd_sol_aprob_eje";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }
    // modificado 03/12/2011
    function ProyectoSolicitudCorrecion($proy, $tipo, $msg)
    {
        $params = array(
            $proy,
            $tipo,
            '0',
            $msg,
            $this->Session->UserID
        );

        $SP = "sp_upd_sol_correc";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }

    // ndRegion
    // in Region Proyectos
    function ListaEjecutores()
    {
        $sql = "SELECT t01_id_inst, t01_sig_inst  from  t01_dir_inst ;";

        return $this->ExecuteQuery($sql);
    }

    /**
     * Lista todas las lineas registradas
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @return mixed En caso de exito retorna resource de tipo resultset caso contrario FALSE o 0
     *
     */
    public function ListaLineas()
    {
        $res = $this->ExecuteQuery('SELECT t00_cod_linea, t00_nom_abre, t00_nom_lar  from  t00_linea');
        return $res;
    }

    /**
     * Listado de Cuentas Bancarias de la Institucion para asignarse al proyecto.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $t02_cod_proy Codigo del Proyecto
     * @param int $t01_id_inst Id del Nro. de Cuenta
     * @return mixed En caso de exito retorna resource de tipo resultset caso contrario FALSE o 0
     *
     */
    public function getCuentasBancarias($t02_cod_proy = null, $t01_id_inst = null, $returnResult = false)
    {

        if ($t01_id_inst && $t02_cod_proy ) {
            // Consulta cuando Editamos un proyecto
            $sql = 'SELECT c.t01_id_cta AS valor, c.t01_nro_cta AS texto,
                b.t00_nom_lar AS banco, tc.t00_nom_lar AS tipocuenta, tm.t00_nom_lar AS tipomoneda,
                p.t01_id_cta
                FROM
                t00_bancos AS b,
                t00_tipo_cuenta AS tc, t00_tipo_moneda AS tm,
                t01_inst_ctas AS c

                LEFT JOIN t02_dg_proy AS p ON p.t01_id_cta = c.t01_id_cta AND p.t01_id_inst = '.$t01_id_inst.'

                WHERE c.t01_id_inst = '.$t01_id_inst.' AND
                c.t00_cod_bco = b.t00_cod_bco  AND
                c.t00_cod_cta = tc.t00_cod_cta AND
                c.t00_cod_mon = tm.t00_cod_mon AND
                ISNULL(p.t01_id_cta)

                UNION ALL

                SELECT c.t01_id_cta AS valor, c.t01_nro_cta AS texto,
                b.t00_nom_lar AS banco, tc.t00_nom_lar AS tipocuenta, tm.t00_nom_lar AS tipomoneda,
                p.t01_id_cta
                FROM
                t00_bancos AS b,
                t00_tipo_cuenta AS tc, t00_tipo_moneda AS tm,
                t01_inst_ctas AS c

                LEFT JOIN t02_dg_proy AS p ON p.t01_id_cta = c.t01_id_cta AND p.t01_id_inst = '.$t01_id_inst.'

                WHERE c.t01_id_inst = '.$t01_id_inst.' AND
                c.t00_cod_bco = b.t00_cod_bco  AND
                c.t00_cod_cta = tc.t00_cod_cta AND
                c.t00_cod_mon = tm.t00_cod_mon AND
                p.t02_cod_proy = "'.$t02_cod_proy.'"



                GROUP BY c.t01_id_cta';

        } else {
            // Consulta cuando Adicionamos nuevo proyecto
            $sql = 'SELECT c.t01_id_cta AS valor, c.t01_nro_cta AS texto,
                b.t00_nom_lar AS banco, tc.t00_nom_lar AS tipocuenta, tm.t00_nom_lar AS tipomoneda,
                p.t01_id_cta
                FROM
                t00_bancos AS b,
                t00_tipo_cuenta AS tc, t00_tipo_moneda AS tm,
                t01_inst_ctas AS c

                LEFT JOIN t02_dg_proy AS p ON p.t01_id_cta = c.t01_id_cta AND p.t01_id_inst = '.$t01_id_inst.'

                WHERE c.t01_id_inst = '.$t01_id_inst.' AND
                c.t00_cod_bco = b.t00_cod_bco  AND
                c.t00_cod_cta = tc.t00_cod_cta AND
                c.t00_cod_mon = tm.t00_cod_mon AND
                ISNULL(p.t01_id_cta)
                GROUP BY c.t01_id_cta';
        }

        $res = $this->ExecuteQuery($sql);

        if ($returnResult) {
            $arrResult = array();
            while($aRow = mysql_fetch_assoc($res)) {
                $arrResult[] = array(
                	'banco' => $aRow['banco'],
                    'texto' => $aRow['texto'],
                    'tipocuenta' => $aRow['tipocuenta'],
                    'tipomoneda' => $aRow['tipomoneda'],
                    'valor' => $aRow['valor'],
                );
            }

            return $arrResult;
        }

        return $res;
    }

    /**
     * Listado de Cuentas Bancarias de la Institucion disponibles.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $idInst ID de la Institucion.
     * @param strinf $proy	Codigo del Proyecto.
     * @param int $banco ID del Banco o Entidad Financiera.
     * @param int $tipocuenta ID del tipo de cuenta.
     * @param int $mone ID del tipo de moneda.
     * @return mixed En caso de exito retorna resource de tipo resultset caso contrario FALSE o 0
     *
     */
    public function  getNrosDeCuentasPorInstitucion($idInst, $proy, $banco = null,$tipocuenta = null,$mone = null)
    {
        $res = false;
        if($idInst) {

        	$idInst = (int) $idInst;
            /*$sql = 'SELECT t00_cod_bco, t00_cod_cta, t00_cod_mon, t01_nro_cta FROM t01_inst_ctas WHERE t01_id_inst = '.$idInst;
            if ($banco) $sql .= ' AND t00_cod_bco = '.$banco;
            if ($tipocuenta) $sql .= ' AND t00_cod_cta = '.$tipocuenta;
            if ($mone) $sql .= ' AND t00_cod_mon = '.$mone;*/
        	
        	$sql = 'SELECT cta.t00_cod_bco, cta.t00_cod_cta, cta.t00_cod_mon, cta.t01_nro_cta FROM t01_inst_ctas cta ';
        	$sql .= 'LEFT JOIN t02_proy_ctas pcta ON (pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta) ';
        	$sql .= 'WHERE cta.t01_id_inst = '.$idInst;
        	$sql .= ' AND ISNULL(pcta.t01_id_cta)';
        	if ($banco) $sql .= ' AND cta.t00_cod_bco = '.$banco;
        	if ($tipocuenta) $sql .= ' AND cta.t00_cod_cta = '.$tipocuenta;
        	if ($mone) $sql .= ' AND cta.t00_cod_mon = '.$mone;
        	
        	$sql .= ' UNION ALL ';
        	
        	$sql .= 'SELECT cta.t00_cod_bco, cta.t00_cod_cta, cta.t00_cod_mon, cta.t01_nro_cta FROM t01_inst_ctas cta ';
        	$sql .= 'LEFT JOIN t02_proy_ctas pcta ON (pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta) ';
        	$sql .= 'WHERE cta.t01_id_inst = '.$idInst.' AND pcta.t02_cod_proy = "'.$proy.'"';        	
        	if ($banco) $sql .= ' AND cta.t00_cod_bco = '.$banco;
        	if ($tipocuenta) $sql .= ' AND cta.t00_cod_cta = '.$tipocuenta;
        	if ($mone) $sql .= ' AND cta.t00_cod_mon = '.$mone;

            $res = $this->ExecuteQuery($sql);

            $arrResult = array();
            while($aRow = mysql_fetch_assoc($res)) {
                $arrResult[] = array(
                    'banco' => $aRow['t00_cod_bco'],
                    'tipo' => $aRow['t00_cod_cta'],
                    'moneda' => $aRow['t00_cod_mon'],
                    'nro' => $aRow['t01_nro_cta'],
                );
            }

            return $arrResult;


        }

        return $res;
    }


    /**
     * Lista de los Gestores de Proyectos, antes Monitor Tematico
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @return resource Resultado de consulta mysql_query
     *
     */
    function ListaGestoresDeProyectos()
    {
        $aQuery = "SELECT 	fe.t90_id_equi AS codigo,
            CONCAT(TRIM(fe.t90_ape_pat),' ', TRIM(fe.t90_ape_mat), ', ', TRIM(fe.t90_nom_equi)) AS nombres
            FROM t90_equi_fe fe
            WHERE fe.t90_carg_equi IN (301,69) GROUP BY fe.t90_dni_equi
            ORDER BY 2 ";
        return $this->Execute($aQuery);
    }


    function ListaMonitorTematico()
    {
        return $this->ExecuteProcedureReader("sp_sel_monitor_tematico", NULL);
    }

    function ListaMonitorFinanciero()
    {
        return $this->ExecuteProcedureReader("sp_sel_monitor_financiero", NULL);
    }

    function ListaSupervisorInstitucional($idProy, $idVersion)
    {
        $params = array(
            $idProy,
            $idVersion
        );
        return $this->ExecuteProcedureReader("sp_sel_supervisor_intitucion", $params);
    }

    function ListaMonitorExterno($idProy, $idVersion)
    {
        $params = array(
            $idProy,
            $idVersion
        );
        return $this->ExecuteProcedureReader("sp_sel_monitor_externo", $params);
    }

    function ListaPersonasFE_cargo($idCargo)
    {
        $params = array(
            $idCargo
        );
        return $this->ExecuteProcedureReader("sp_sel_equipo_fe_by_cargo", $params);
    }

    // egion Sectores Productivos
    function SectoresProductivos_Listado($Proy)
    {
        $SP = "sp_sel_sector_prod";
        $params = array(
            $Proy
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }
    // / <summary>
    // / Guarda el Sector Productivo asociado al proyecto
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function SectorProdNuevo()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cbosectormain'],
            $_POST['cbosector'],
            $_POST['cbosubsector'],
            $_POST['t02_obs'],
            $this->Session->UserID
        );

        $SP = "sp_ins_sector_prod";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }
    // / <summary>
    // / Guarda el Sector Productivo asociado al proyecto que ha sido Modificado
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function SectorProdActualizar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_sector_main'],
            $_POST['t02_sector'],
            $_POST['t02_subsec'],
            $_POST['cbosectormain'],
            $_POST['cbosector'],
            $_POST['cbosubsector'],
            $_POST['t02_obs'],
            $this->Session->UserID
        );

        $SP = "sp_upd_sector_prod";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }
    // / <summary>
    // / Elimina el Sector Productivo asociado al proyecto.
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function SectorProdEliminar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_sector'],
            $_POST['t02_subsec']
        );
        $SP = "sp_del_sector_prod";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Sectores Productivos

    // egion Sectores Productivos
    function AmbitoGeo_Listado($Proy, $vs)
    {
        $SP = "sp_sel_ambito";
        $params = array(
            $Proy,
            $vs
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }
    // / <summary>
    // / Guarda el Sector Productivo asociado al proyecto
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function AmbitoGeoNuevo()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboversion'],
            $_POST['cbodpto'],
            $_POST['cboprov'],
            $_POST['cbodist'],
            $_POST['t03_obs'],
            $this->Session->UserID
        );
        $SP = "sp_ins_ambito_geo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }
    // / <summary>
    // / Guarda el Sector Productivo asociado al proyecto que ha sido Modificado
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function AmbitoGeoActualizar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboversion'],
            $_POST['t03_dpto'],
            $_POST['t03_prov'],
            $_POST['t03_dist'],
            $_POST['cbodpto'],
            $_POST['cboprov'],
            $_POST['cbodist'],
            $_POST['t03_obs'],
            $this->Session->UserID
        );

        $SP = "sp_upd_ambito_geo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }
    // / <summary>
    // / Elimina el Sector Productivo asociado al proyecto.
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function AmbitoGeoEliminar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t03_dpto'],
            $_POST['t03_prov'],
            $_POST['t03_dist']
        );
        $SP = "sp_del_ambito_geo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Sectores Productivos

    // egion - Carta Fianza
    function CartaFianzaListaPorProy($pCodProy)
    {
        $aQuery = "SELECT t02_id_cf, t02_des_cf FROM t02_carta_fianza WHERE t02_cod_proy = '$pCodProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function CartaFianza_Listado($Proy)
    {
        $SP = "sp_sel_cartafianza";
        $params = array(
            $Proy
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function CartaFianza_Seleccionar($Proy, $IdCF)
    {
        $SP = "sp_get_cartafianza";
        $params = array(
            $Proy,
            $IdCF
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        return mysqli_fetch_assoc($rs);
    }

    function CartaFianzaNuevo()
    {
        require_once ("UploadFiles.class.php");

        $objFiles = new UploadFiles("txtfileupload");
        $arrFilesRequired = array(
            "doc",
            "pdf",
            "jpg",
            "gif",
            "bmp",
            "ppt",
            "docx",
            "xls",
            "xlsx",
            "pptx",
            "tiff"
        );

        if (! $objFiles->ValidateExt($arrFilesRequired)) {
            $this->Error = "El Archivo adjunto no es valido !!!";
            return false;
        }

        $filename = $_POST['txtCodProy'] . '_' . $_POST['txtserie'] . '_' . $_POST['txtnumero'] . '.' . $objFiles->getExtension();
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cbobanco'],
            $_POST['txtdescripcion'],
            $_POST['txtmonto'],
            $_POST['txtnumero'],
            $_POST['txtserie'],
            $this->ConvertDate($_POST['txtfecrec']),
            $this->ConvertDate($_POST['txtfecgir']),
            $this->ConvertDate($_POST['txtfecvenc']),
            $_POST['chkVB'],
            $_POST['txtobs_adm'],
            $filename,
            $this->Session->UserID
        );
        $SP = "sp_ins_cartafianza";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            require_once ("HardCode.class.php");
            $HC = new HardCode();
            $objFiles->DirUpload .= $HC->FolderUploadCartaFianza;
            $objFiles->SavesAs($filename);
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function CartaFianzaActualizar()
    {
        require_once ("HardCode.class.php");
        require_once ("UploadFiles.class.php");
        if ( isset($_FILES['txtfileupload']) && $_FILES['txtfileupload']['name'] != '') {
            $objFiles = new UploadFiles("txtfileupload");
            $arrFilesRequired = array(
                "doc",
                "pdf",
                "jpg",
                "gif",
                "bmp",
                "ppt",
                "docx",
                "xls",
                "xlsx",
                "pptx",
                "tiff"
            );
            if (! $objFiles->ValidateExt($arrFilesRequired)) {
                $this->Error = "El Archivo adjunto no es valido !!!";
                return false;
            }

            $filename = $_POST['txtCodProy'] . '_' . $_POST['txtserie'] . '_' . $_POST['txtnumero'] . '.' . $objFiles->getExtension();
        }

        $params = array(
            $_POST['txtidcartafianza'],
            $_POST['t02_cod_proy'],
            $_POST['cbobanco'],
            $_POST['txtdescripcion'],
            $_POST['txtmonto'],
            $_POST['txtnumero'],
            $_POST['txtserie'],
            $this->ConvertDate($_POST['txtfecrec']),
            $this->ConvertDate($_POST['txtfecgir']),
            $this->ConvertDate($_POST['txtfecvenc']),
            $_POST['chkVB'],
            $_POST['txtobs_adm'],
            $filename,
            $this->Session->UserID
        );
        $SP = "sp_upd_cartafianza";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
        	if ( isset($_FILES['txtfileupload']) && $_FILES['txtfileupload']['name'] != '') {
                $HC = new HardCode();
                $objFiles->DirUpload .= $HC->FolderUploadCartaFianza;
                $objFiles->SavesAs($filename);
            }
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function CartaFianzaEliminar()
    {
        $params = array(
            $_POST['t02_id_cf'],
            $_POST['t02_cod_proy']
        );
        $SP = "sp_del_cartafianza";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    // ndRegion - Carta Fianza

    // o Objecion de Compras
    function NoObjecionCompra_ListaSols($pCodProy)
    {
        $aQuery = "SELECT t02_id_noc
				FROM t02_noobjecion_compra
				WHERE t02_cod_proy = '$pCodProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function NoObjecionCompra_Listado($Proy)
    {
        $SP = "sp_sel_noobjecion_compra";
        $params = array(
            $Proy
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function NoObjecionCompra_getNumero($Proy)
    {
        return $this->GetValue("SELECT MAX(t02_sco_noc) FROM t02_noobjecion_compra WHERE t02_cod_proy='" . $Proy . "' ;");
    }

    function NoObjecionCompra_Seleccionar($Proy, $IdNoc)
    {
        $SP = "sp_get_noobjecioncompra";
        $params = array(
            $Proy,
            $IdNoc
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        return mysqli_fetch_assoc($rs);
    }

    function NoObjecionCompraNuevo()
    {
        $params = array(
            $_POST['cod_proy'],
            $_POST['t02_sco_noc'],
            $_POST['cboComponente'],
            $_POST['cboActividad'],
            $_POST['cboSubActividad'],
            $_POST['cboCatGastos'],
            $_POST['t02_spa_noc'],
            $_POST['t02_imp_noc'],
            $_POST['t02_ccp_noc'],
            $_POST['t02_cop_noc'],
            $_POST['t02_amt_noc'],
            $_POST['t02_amf_noc'],
            $_POST['t02_cmf_noc'],
            $_POST['t02_cmt_noc'],
            $_POST['t02_pro_noc'],
            $_POST['t02_obs_noc'],
            $this->Session->UserID,
            date("Y-m-d")
        );

        $SP = "sp_ins_noobjecioncompra";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function NoObjecionCompraActualizar()
    {
        $params = array(
            $_POST['cod_proy'],
            $_POST['txtidobjecioncompra'],
            $_POST['t02_sco_noc'],
            $_POST['cboComponente'],
            $_POST['cboActividad'],
            $_POST['cboSubActividad'],
            $_POST['cboCatGastos'],
            $_POST['t02_spa_noc'],
            $_POST['t02_imp_noc'],
            $_POST['t02_ccp_noc'],
            $_POST['t02_cop_noc'],
            $_POST['t02_amt_noc'],
            $_POST['t02_amf_noc'],
            $_POST['t02_cmf_noc'],
            $_POST['t02_cmt_noc'],
            $_POST['t02_pro_noc'],
            $_POST['t02_obs_noc'],
            $this->Session->UserID
        );

        $SP = "sp_upd_noobjecioncompra";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function NoObjecionCompraEliminar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['id']
        );

        $SP = "sp_del_noobjecioncompra";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function NoObjecionCompraRevisar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['id'],
            $_POST['num'],
            $this->Session->UserID
        );

        $SP = "sp_rev_noobjecioncompra";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function ListaAnexosNoObjecionCompra($idProy, $id)
    {
        $SP = "sp_sel_noobjecion_compra_anx";
        $params = array(
            $idProy,
            $id
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function GuardarAnexoNoObjecionC()
    {
        require_once ("UploadFiles.class.php");
        $objFiles = new UploadFiles("txtNomFile");
        $NomAnx = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/anexos/anexos/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['id'],
            $NomAnx,
            $_POST['t02_desc_file'],
            $ext,
            $this->Session->UserID
        );

        $SP = "sp_ins_noobjecion_compras_anx";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }

    function EliminarNoObjecionCompraAnx()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_cod_anx']
        );

        $SP = "sp_del_noobjecion_compras_anx";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/anexos/anexos/" . $urlfoto;

            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    // ndRegion No Objecion de Compras
    function ListaAnexos($idProy, $idversion)
    {
        $SP = "sp_sel_proy_anx";
        $params = array(
            $idProy,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function GuardarAnexo()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomAnx = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/datos/anexos/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $NomAnx,
            $_POST['t02_desc_file'],
            $ext,
            $_POST['t02_anx_tip_desc'],
            $this->Session->UserID
        );

        $SP = "sp_ins_proy_anx";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }

    function EliminarAnx()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t02_version'],
            $_POST['t02_cod_anx']
        );
        $SP = "sp_del_proy_anx";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/datos/anexos/" . $urlfoto;

            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function ListaTipoAnexo()
    {
        return $this->ExecuteProcedureReader("sp_sel_tipo_anx_proy", NULL);
    }


    /**
     * Obtiene las tasas registradas por concurso y linea de proyecto
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $concurso Numero del Concurso
     * @param string $linea Codigo de linea
     * @return Array asociativo
     *
     */
    public function getTasasPorConcursoYLinea($concurso, $linea)
    {
        $aQuery = 'SELECT porc_gast_func, porc_linea_base, porc_imprev, porc_gast_superv_proy FROM adm_concursos_tasas WHERE num_conc = "'.$concurso.'" AND cod_linea = "'.$linea.'"';
        $aResult = $this->ExecuteQuery($aQuery);
        $aRow = mysql_fetch_assoc($aResult);


        return array(
        	'func' => $aRow['porc_gast_func'],
            'linea' => $aRow['porc_linea_base'],
            'imprev' => $aRow['porc_imprev'],
            'superv' => $aRow['porc_gast_superv_proy']
        );
    }

    /**
     * Aprobación de Proyecto desde Datos Generales
     *
     * @author AQ
     * @since Version 2.0
     * @access public
     * @param string $codProyecto Código del Proyecto.
     * @return boolean
     *
     */
    public function aprobarProyecto($idProy)
    {
        $params = array($idProy, $this->Session->UserID);
        return $this->ExecuteFunction("fn_aprob_proy", $params);
    }

    /**
     * Obtiene los datos separados por comas 
     * de Ámbito Geográfico 
     * 
     * @author AQ
     * @since Version 2.0
     * @access public
     * @param string $codProyecto Código del Proyecto.
     * @return boolean
     *
     */
    public function listarAmbitoGeoAgrupado($idProy, $idVersion)
    {
        $rs = $this->ExecuteProcedureReader("sp_sel_ambito_agrupado", array($idProy, $idVersion));
        return mysqli_fetch_assoc($rs);
    }
    
    /**
     * Obtiene los nombres del Gerente de Gestor de Proyecto 
     * asignado al proyecto 
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $idProy		Código del Proyecto.
     * @return resource
     *
     */
    public function getGerenteGestorProyecto($idProy)
    {
    	$sql = "SELECT nom_user FROM pg_test.adm_usuarios WHERE 
    			t02_cod_proy = '$idProy'
    			 AND tipo_user = '14' 
    			AND estado = 1";
    
    	$ConsultaID = $this->ExecuteQuery($sql);    
    	$row = mysql_fetch_assoc($ConsultaID);    
    	return $row;
    }
    
    
} // fin de la Clase BLProyecto

