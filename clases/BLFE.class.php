<?php
require_once ("BLBase.class.php");
require_once ("HardCode.class.php");

class BLFE extends BLBase
{

    var $fecha;

    var $Session;

    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }
    // -----------------------------------------------------------------------------
    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }

    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    
    // egion Equipo FE
    // egion Read
    function EquipoListado()
    {
        return $this->ExecuteProcedureReader("sp_sel_equi_fe", NULL);
    }

    function EquipoSeleccionar($id)
    {
        $params = array(
            $id
        );
        $rs = $this->ExecuteProcedureReader("sp_get_equi_fe", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }
    // ndRegion
    // egion CRUD Equipo FE
    function EquipoNuevo()
    {
        $params = array(
            $_POST['t90_dni_equi'],
            $_POST['t90_ape_pat'],
            $_POST['t90_ape_mat'],
            $_POST['t90_nom_equi'],
            $_POST['t90_sexo_equi'],
            $_POST['t90_edad_equi'],
            $_POST['t90_cali_equi'],
            $_POST['t90_mail_equi'],
            $_POST['t90_telf_equi'],
            $_POST['t90_unid_fe'],
            $_POST['t90_carg_equi'],
            $_POST['t90_func_equi'],
            $_POST['t90_direccion'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_ins_equi_fe", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function EquipoActualizar()
    {
        $params = array(
            $_POST['t90_id_equi'],
            $_POST['t90_dni_equi'],
            $_POST['t90_ape_pat'],
            $_POST['t90_ape_mat'],
            $_POST['t90_nom_equi'],
            $_POST['t90_sexo_equi'],
            $_POST['t90_edad_equi'],
            $_POST['t90_cali_equi'],
            $_POST['t90_mail_equi'],
            $_POST['t90_telf_equi'],
            $_POST['t90_unid_fe'],
            $_POST['t90_carg_equi'],
            $_POST['t90_func_equi'],
            $_POST['t90_direccion'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_equi_fe", $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function EquipoEliminar($idEquipo)
    {
        $params = array(
            $idEquipo
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_equi_fe", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = "No se logro Eliminar ningun registro";
            return false;
        }
    }
    // ndRegion
    // ndRegion Equipo FE
    
    // odulo Administración
    function ListadoProyectos_Aprob_Primer_Desembolso($concuso, $Inst, $Proy = '')
    {
        $params = array(
            $concuso,
            $Inst,
            $Proy
        );
        return $this->ExecuteProcedureReader("sp_lis_aprueba_primer_desembolso", $params);
    }

    function Aprobacion_Primer_Desemb_Seleccionar($idAprobacion)
    {
        $params = array(
            $idAprobacion
        );
        $rs = $this->ExecuteProcedureReader("sp_get_aprueba_primer_desemb", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }

    function Aprobacion_Primer_Desemb_Actualizar(&$codigo)
    {
        $params = array(
            $_POST['t02_proyecto'],
            $_POST['txt_mto_desemb'],
            $_POST['chk_cta'],
            $_POST['chk_cf'],
            $_POST['chk_cvf'],
            $_POST['chk_od'],
            $_POST['txt_obs_mf'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_aprueba_primer_desemb", $params);
        
        if ($ret['numrows'] >= 0) {
            $codigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function ListadoProyectos_AprobDesembolsos($concuso, $Inst, $Proy = '')
    {
        $objHC = new HardCode();
        $params = array(
            $objHC->codigo_Fondoempleo,
            $concuso,
            $Inst,
            $Proy
        );
        return $this->ExecuteProcedureReader("sp_lis_aprueba_desembolso", $params);
    }

    function ListadoProyectos_AprobDesembolsosParciales($concuso, $Inst, $Proy = '')
    {
        $objHC = new HardCode();
        $params = array(
            $objHC->codigo_Fondoempleo,
            $concuso,
            $Inst,
            $Proy
        );
        return $this->ExecuteProcedureReader("sp_lis_aprueba_desembolso2", $params);
    }

    function Aprobacion_Desemb_Seleccionar($idProy, $idTrimestre)
    {
        $Fun = new Functions();
        $params = array(
            $idProy,
            $Fun->NumeroTrimRev($idTrimestre, 1),
            $Fun->NumeroTrimRev($idTrimestre, 2)
        );
        $rs = $this->ExecuteProcedureReader("sp_get_aprueba_desemb", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }

    function Aprobacion_Desemb_Seleccionar2($idAprob)
    {
        $params = array(
            $idAprob
        );
        $rs = $this->ExecuteProcedureReader("sp_get_aprueba_desemb2", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }

    function Aprob_desemb_Con($idAprob, $cod)
    {
        $params = array(
            $idAprob,
            $cod
        );
        $rs = $this->ExecuteProcedureReader("sp_desem_comprob", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }

    function Aprobacion_Desemb_Actualizar(&$codigo)
    {
        $Fun = new Functions();
        $params = array(
            $_POST['t02_proyecto'],
            $Fun->NumeroTrimRev($_POST['t60_trimestre'], 1),
            $Fun->NumeroTrimRev($_POST['t60_trimestre'], 2),
            $_POST['t60_montoplan'],
            $_POST['txt_mto_desemb'],
            $_POST['chk_aprueba_mt'],
            $_POST['txt_obs_mt'],
            $_POST['chk_aprueba_mf'],
            $_POST['txt_obs_mf'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_aprueba_desemb", $params);
        
        if ($ret['numrows'] >= 0) {
            $codigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function ListadoProyectos_EjecDesembolsos($concuso, $Inst)
    {
        $params = array(
            $concuso,
            $Inst
        );
        return $this->ExecuteProcedureReader("sp_lis_ejecuc_desembolso", $params);
    }

    // function ListaDesembolsos($idProy, $idTrim)
    // {
    //     $params = array(
    //         $idProy,
    //         $idTrim
    //     );
    //     return $this->ExecuteProcedureReader("sp_sel_desembolsos_fe", $params);
    // }

    function ListaDesembolsosME($idProy, $idVisita, $idAprob)
    {
        $params = array(
            $idProy,
            $idVisita,
            $idAprob
        );
        return $this->ExecuteProcedureReader("sp_sel_desembolsos_fe_me", $params);
    }
    // ndRegion
    
    // egion Desembolsos

    // function Desembolso_Actualizar(&$codigo)
    // {
    //     $params = array(
    //         $_POST['t02_proyecto'],
    //         $_POST['t60_trimestre'],
    //         $_POST['txtcodigo_desemb'],
    //         $_POST['cbomodalidad'],
    //         $_POST['txt_id_fe'],
    //         $_POST['cbo_cta_ori'],
    //         $_POST['txt_id_inst'],
    //         $_POST['txt_cta_dest'],
    //         '',
    //         $this->ConvertDate($_POST['txtfecgiro']),
    //         $this->ConvertDate($_POST['txtfechadeposito']),
    //         str_replace(',', '', $_POST['txtmontodesemb']),
    //         $_POST['txtnrocheque'],
    //         $_POST['txtobserva'],
    //         $this->Session->UserID
    //     );
        
    //     $ret = $this->ExecuteProcedureEscalar("sp_upd_desembolso", $params);
        
    //     if ($ret['numrows'] >= 0) {
    //         $codigo = $ret['codigo'];
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // function Desembolso_Eliminar()
    // {
    //     $params = array(
    //         $_POST['t02_proyecto'],
    //         $_POST['t60_trimestre'],
    //         $_POST['txtcodigo_desemb']
    //     );
    //     $ret = $this->ExecuteProcedureEscalar("sp_del_desembolso", $params);
    //     if ($ret['numrows'] > 0) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    function SeleccionarAprobacionPagoME($Proy, $Visita, $idAprob)
    {
        $params = array(
            $Proy,
            $Visita,
            $idAprob
        );
        return $this->ExecuteProcedureEscalar("sp_sel_aprueba_pago_me", $params);
    }

    function ListadoControlPagoME($concuso, $Inst, $idProy)
    {
        $params = array(
            $concuso,
            $Inst,
            $idProy
        ); // '*' -> corresponde a todos los proyectos
        return $this->ExecuteProcedureReader("sp_lis_control_pago_me", $params);
    }

    function ListadoAutorizaChequeME($concuso, $Inst)
    {
        $params = array(
            $concuso,
            $Inst,
            '*'
        ); // '*' -> corresponde a todos los proyectos
        return $this->ExecuteProcedureReader("sp_lis_autoriza_cheque_me", $params);
    }

    function Aprobacion_Desemb_MonitorExterno_Actualizar()
    {
        $params = array(
            $_POST['t02_proyecto'],
            $_POST['t60_visita'],
            $_POST['t60_pago'],
            $_POST['chk_aprueba'],
            $_POST['txt_mto_desemb'],
            $_POST['txt_obs_mt'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_aprueba_desemb_me", $params);
        
        if ($ret['numrows'] >= 0) {
            // $codigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function DesembolsoME_Seleccionar($idProy, $idVisita, $idAprob, $idDesemb)
    {
        $params = array(
            $idProy,
            $idVisita,
            $idAprob,
            $idDesemb
        );
        $rs = $this->ExecuteProcedureReader("sp_get_desembolso_me", $params);
        $row = mysqli_fetch_assoc($rs);
        $rs->free();
        return $row;
    }

    function DesembolsoME_Nuevo(&$codigo)
    {
        $params = array(
            $_POST['t02_proyecto'],
            $_POST['t60_visita'],
            $_POST['t60_id_aproba'],
            $_POST['cbomodalidad'],
            $_POST['txt_id_fe'],
            $_POST['cbo_cta_ori'],
            $_POST['txt_id_inst'],
            $_POST['txt_cta_dest'],
            '',
            $this->ConvertDate($_POST['txtfecgiro']),
            $this->ConvertDate($_POST['txtfechadeposito']),
            $_POST['txtmontodesemb'],
            $_POST['txtnrocheque'],
            $_POST['txtobserva'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_ins_desembolso_me", $params);
        
        if ($ret['numrows'] >= 0) {
            $codigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function DesembolsoME_Actualizar(&$codigo)
    {
        $params = array(
            $_POST['t02_proyecto'],
            $_POST['t60_visita'],
            $_POST['t60_id_aproba'],
            $_POST['txtcodigo_desemb'],
            $_POST['cbomodalidad'],
            $_POST['txt_id_fe'],
            $_POST['cbo_cta_ori'],
            $_POST['txt_id_inst'],
            $_POST['txt_cta_dest'],
            '',
            $this->ConvertDate($_POST['txtfecgiro']),
            $this->ConvertDate($_POST['txtfechadeposito']),
            $_POST['txtmontodesemb'],
            $_POST['txtnrocheque'],
            $_POST['txtobserva'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_desembolso_me", $params);
        
        if ($ret['numrows'] >= 0) {
            $codigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function DesembolsoME_Eliminar()
    {
        $params = array(
            $_POST['t02_proyecto'],
            $_POST['t60_visita'],
            $_POST['t60_id_aproba'],
            $_POST['txtcodigo_desemb']
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_desembolso_me", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // ndRegion
    
    // egion Reportes
    function RepCronograma_y_Ejecucion_Desembolsos($consurso, $ejecutor = 0, $proy = '*', $Anio = 1, $sector, $region)
    {
        $SP = "sp_rpt_cronograma_ejec_desembolsos_trim";
        $params = array(
            $consurso,
            $ejecutor,
            $proy,
            $Anio,
            $sector,
            $region
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepCromDesembFE($anio, $consurso, $sector, $region)
    {
        $SP = "sp_rpt_desm_fe";
        $params = array(
            $anio,
            $consurso,
            $sector,
            $region
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ReCromPlaneadoAnio($anio, $consurso, $sector, $region)
    {
        $SP = "sp_ejec_anio";
        $params = array(
            $anio,
            $consurso,
            $sector,
            $region
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepDesembolsos_Periodo($concurso, $idinst, $inicio, $termino)
    {
        $SP = "sp_rpt_desembolsos_periodo";
        $params = array(
            $concurso,
            $idinst,
            $inicio,
            $termino
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepTipoServ($anio, $concurso)
    {
        $SP = "sp_sel_tipo_serv_tema";
        $params = array(
            $anio,
            $concurso
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepTipoServRegion($pAnio, $pConcurso)
    {
        return $this->ExecuteProcedureReader("sp_sel_tipo_serv_tema_region", array(
            $pAnio,
            $pConcurso
        ));
    }

    function RepTipoServSector($pAnio, $pConcurso)
    {
        return $this->ExecuteProcedureReader("sp_sel_tipo_serv_tema_sector", array(
            $pAnio,
            $pConcurso
        ));
    }

    function RepDesembolsos_PeriodoME($concurso, $idinst, $inicio, $termino)
    {
        $SP = "sp_rpt_desembolsos_periodo_me";
        $params = array(
            $concurso,
            $idinst,
            $inicio,
            $termino
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    public function listadoEjecDesemPorEntregables($idInst)
    {
    	return $this->ExecuteProcedureReader("sp_lis_proyectos_ejec_desemb_entre", array(
    			$idInst
    	));
    }
    
    public function itemEjecDesemPorEntrSeleccionar($codProyecto, $version)
    {
    	$ret =  $this->ExecuteProcedureReader("sp_sel_proyectos_ejec_desemb_entre", array(
    			$codProyecto, 
    			$version
    	));
    	
    	return $ret;
    
    }
    
    public function updateDesembolsoPorEntregable($sql)
    {    	
    	$ret = $this->ExecuteUpdate($sql);
    	return $ret;
    	
    }
    
    /**    
    * Guarda en la base de datos el concepto.    
    *    
    * @author DA
    * @since Version 2.1
    * @access public
    * @param string $codProyecto 	Codigo del Proyecto
    * @param string $version		Numero de Version
    * @return resource
    *
    */
    public function getNombresPeriodos($codProyecto, $version)
    {
    	$sql = 'SELECT  
                fn_nom_periodo_entregable(entre.t02_cod_proy, entre.t02_version, entre.t02_anio,entre.t02_mes ) AS periodo, 
                fn_numero_entregable(entre.t02_cod_proy, entre.t02_version, entre.t02_anio,entre.t02_mes) AS entregable  
            FROM
              t02_entregable entre
            WHERE entre.t02_cod_proy = "'.$codProyecto.'" AND entre.t02_version = "'.$version.'" 
            GROUP BY entre.t02_mes,entre.t02_anio
            ORDER BY entre.t02_anio, entre.t02_mes';
    	
    	return $this->ExecuteQuery($sql);
    }
    
    
    
    /**
     * Seleccion del Encabezado para el nuevo registro de Nuevo Desembolso.
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @param string $codProyecto 	Codigo del Proyecto
     * @param string $version		Numero de Version     
     * @return resource
     *
     */
    public function selDesembEncabezado($codProyecto, $version)
    {
    	$sql = 'SELECT 	
        	proy.t01_id_inst,
        	ejec.t01_sig_inst,
        	proy.t02_cod_proy,
        	proy.t02_nom_proy,			
        	proy.t02_fch_ini,		
        	proy.t02_fch_tre,		
        	cta.t01_id_cta
        FROM t02_dg_proy proy
        LEFT  JOIN t01_dir_inst  ejec ON (proy.t01_id_inst = ejec.t01_id_inst )
        LEFT  JOIN t02_proy_ctas cta ON (proy.t02_cod_proy=cta.t02_cod_proy)
        WHERE 
        proy.t02_cod_proy = "'.$codProyecto.'" AND  
        proy.t02_version = "'.$version.'" ';
    	 
    	$ConsultaID = $this->ExecuteQuery($sql);
    	return mysql_fetch_array($ConsultaID);
    }
    
    /**    
    * Obtiene el cuadro resumen de la
    * Ejecución de Desembolsos
    *    
    * @author AQ
    * @since Version 2.1
    * @access public
    * @param string $codProyecto    Codigo del Proyecto
    * @param string $version        Numero de Version
    * @return resource
    *
    */
    public function getResumenEjecDesembolsos($codProyecto, $version)
    {
        $hc = new HardCode();

        $sql = 'SELECT  
                  t02_anio AS anio, 
                  t02_mes AS mes,
                  fn_nom_periodo_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS periodo, 
                  fn_numero_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS entregable,
                  fn_desembolso_planeado_del_entregable_fte(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS planeado,
                  fn_desembolsado_del_entregable(t02_cod_proy, t02_anio, t02_mes) AS desembolsado
                FROM
                    t02_entregable
                WHERE t02_cod_proy = "'.$codProyecto.'" 
                AND t02_version = "'.$version.'" 
                ORDER BY t02_anio, t02_mes';

        return $this->ExecuteQuery($sql);
    }


    /**    
    * Obtiene el Número y Periodo del Entregable
    *    
    * @author AQ
    * @since Version 2.1
    * @access public
    * @param string $codProyecto    Codigo del Proyecto
    * @param string $version        Numero de Version
    * @return resource
    *
    */
    public function getDatosEntregable($idProy, $idVersion, $anio, $mes)
    {
        $hc = new HardCode();

        $sql = "SELECT  
                fn_nom_periodo_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS periodo, 
                fn_numero_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS entregable,
                fn_desembolso_planeado_del_entregable_fte(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS planeado,
                fn_desembolsado_del_entregable(t02_cod_proy, t02_anio, t02_mes) AS desembolsado  
            FROM t02_entregable
            WHERE t02_cod_proy = '".$idProy."'  
            AND t02_version = ".$idVersion."
            AND t02_anio = ".$anio." 
            AND t02_mes = ".$mes;

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function getListaDesembolsados($idProy, $idVersion, $anio, $mes)
    {
        return $this->ExecuteProcedureReader("sp_sel_desembolsados_del_entregable", array($idProy, $idVersion, $anio, $mes));
    }

    function guardarDesembolsado(&$codigo)
    {
        $params = array(
            $_POST['dsmb_id'],
            $_POST['dsmb_proy'],
            $_POST['dsmb_anio'],
            $_POST['dsmb_mes'],
            $_POST['dsmb_tip_pago'],
            $_POST['dsmb_inst_ori'],
            $_POST['dsmb_cta_ori'],
            $_POST['dsmb_modalidad'],
            $this->ConvertDate($_POST['dsmb_fec_giro']),
            str_replace(',', '', $_POST['dsmb_mto']),
            $_POST['dsmb_cheque'],
            $this->ConvertDate($_POST['dsmb_fec_deposito']),
            $_POST['dsmb_obs'],
            $this->Session->UserID
        );

        $ret = $this->ExecuteProcedureEscalar("sp_guardar_desembolsado", $params);

        if ($ret['numrows'] >= 0) {
            $codigo = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function getDesembolsado($idDesemb)
    {
        $sql = "SELECT  
                t02_cod_proy, t02_anio, t02_mes,
                t60_tip_pago, t60_inst_ori, 
                t60_cta_ori, t60_mod_pago, 
                t01_id_inst, t01_id_cta, 
                DATE_FORMAT(t60_fch_giro, '%d/%m/%y') AS t60_fch_giro, 
                DATE_FORMAT(t60_fch_depo, '%d/%m/%y') AS t60_fch_depo, 
                t60_mto, t60_cheque, t60_obs
            FROM t60_desembolsado
            WHERE t60_id = ".$idDesemb;

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function eliminarDesembolsado()
    {
        $sql = "DELETE FROM t60_desembolsado 
                WHERE t60_id = ".$_POST['idDesemb'] . " LIMIT 1";

        $this->ExecuteQuery($sql);
        $num = mysql_affected_rows();

        if ($num == 1) {
            return true;
        } else {
            return false;
        }
    }

    // ndRegion
    
    // fin de la Clase BLFE
}