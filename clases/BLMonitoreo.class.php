<?php
require_once ("BLBase.class.php");
// / -------------------------------------------------------------------------
// / Programmer Name : Aima R. Christian Created Date : 2010-06-01
// / Comments : Clase de Monitoreo, BLInformes heredado de BLBase
// / Monitoreo Externo, Monitoreo Interno
// / -------------------------------------------------------------------------
class BLMonitoreo extends BLBase
{

    var $fecha;

    var $Session;
    // -----------------------------------------------------------------------------
    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }

    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }

    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    function ListaInstitucionesMonitoras()
    {
        return $this->ExecuteProcedureReader("sp_lis_inst_monitoras", array(
            0
        ));
    }
    
    // egion Cronograma de Visitas Monitoreo Externo
    // / <summary>
    // / Retorna Listado de los Planes de Visita para cada Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function PlanVisitasListado($idProy)
    {
        $SP = "sp_sel_plan_visita";
        $params = array(
            $idProy,
            0
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function PlanVisitasSeleccionar($idProy, $idVisita)
    {
        $SP = "sp_sel_plan_visita";
        $params = array(
            $idProy,
            $idVisita
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        return $ret;
    }
    
    
    
    
    /**
     * Obtiene el detalle del plan de visitas del proyecto
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @param string $idProy 		ID o codigo del Proyecto
     * @param string $idVersion 	ID de la Version de Proyecto
     * @param string $anio 			ID del anio del informe entregable
     * @param string $entregable 	Nro. del Informe entregable
     * @return resource				
     *
     */
    public function getDetailPlanVisitasProyecto($idProy, $idVersion, $anio, $entregable)
    {
    	$sql = 'SELECT 
		        inf.t02_cod_proy,
		        inf.t25_anio,
		        CONCAT("Año ",inf.t25_anio) AS anio,
		        inf.t25_entregable,
		        fn_numero_entregable("'.$idProy.'", "'.$idVersion.'", inf.t25_anio, inf.t25_entregable) AS entregable,        
		        inf.t25_periodo AS periodo,
				cronv.estado,
				DATE_FORMAT(cronv.fecha_visita_inicio,"%d/%m/%Y") AS fecha_visita_inicio,
				DATE_FORMAT(cronv.fecha_visita_termino,"%d/%m/%Y") AS fecha_visita_termino,
				cronv.costo_pago_1,
				cronv.costo_pago_2,
		        cronv.id_supervisor  
		    FROM t25_inf_entregable inf 
		    LEFT JOIN t46_cron_visita_proy cronv 
				ON (
					inf.t02_cod_proy = cronv.t02_cod_proy AND 
					inf.t25_anio = cronv.t25_anio AND 
					inf.t25_entregable = cronv.t25_entregable 
				)
		    WHERE inf.t02_cod_proy = "'.$idProy.'" AND   
		    inf.t25_anio = '.$anio.' AND
		   	inf.t25_entregable = '.$entregable; 
		    
    
    	return $this->ExecuteQuery($sql);
    }
    
    /**
     * Obtiene la lista de supervisores y gestores de proyectos asociados al proyecto
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @param string $idProy 		ID o codigo del Proyecto
     * @param string $idVersion 	ID de la Version de Proyecto
     * @return resource
     *
     */
    public function getListaSupervisoresVisita($idProy, $idVersion)
    {
    	
        $ret = $this->ExecuteProcedureReader(
        		'sp_sel_supervisores_visitas', 
        		array(
		            $idProy,
		            $idVersion
		        ));
        
        return $ret;
    	
    }
    
    // / <summary>
    // / Guarda el nuevo Plan de Visita
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitasNuevo()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t31_anio'],
            $_POST['t31_mes'],
            $_POST['t31_obs'],
            $_POST['t31_mto_v1'],
            $_POST['t31_mto_v2'],
            $this->ConvertDate($_POST['t31_fec_vis']),
            $this->ConvertDate($_POST['t31_fec_ter']),
            $_POST['t31_vb_v1'],        	
            $this->Session->UserID
        );
        
        $SP = "sp_ins_plan_visita_me";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            
            $this->Error = $ret['error'];
            return false;
        }
    }
    
    
    /**
     * Actualiza el plan de visitas del proyecto
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @return boolean		true: Exito, false: No se actualizo
     *
     */
    function PlanVisitasActualizar()
    {
        
        $arF1 = explode('/',$_POST['fecha_visita_inicio']);
        $f1 = $arF1[2].'-'.$arF1[1].'-'.$arF1[0];
        $arF2 = explode('/',$_POST['fecha_visita_termino']);
        $f2 = $arF2[2].'-'.$arF2[1].'-'.$arF2[0];
        
        $sqlInitial = 'SELECT t02_cod_proy FROM t46_cron_visita_proy 
        			WHERE  
	        		t02_cod_proy = "'.$_POST['t02_cod_proy'].'" AND 
					t25_anio = "'.$_POST['t25_anio'].'"  AND 
					t25_entregable = "'.$_POST['t25_entregable'].'" ';
        
        $rsInitial = $this->ExecuteQuery($sqlInitial);
        if (mysql_num_rows($rsInitial)>0) {
        	$sql = 'UPDATE t46_cron_visita_proy
			     SET estado = "'.$_POST['estado'].'" ,
				fecha_visita_inicio  = "'.$f1.'" ,
				fecha_visita_termino  = "'.$f2.'" ,
				costo_pago_1 = "'.$_POST['costo_pago_1'].'",
				costo_pago_2 = "'.$_POST['costo_pago_2'].'",
				id_supervisor = "'.$_POST['id_supervisor'].'",
				usr_actu = "'.$this->Session->UserID.'" ,
				fch_actu = NOW()
			  WHERE
				t02_cod_proy = "'.$_POST['t02_cod_proy'].'" AND
				t25_anio = "'.$_POST['t25_anio'].'"  AND
				t25_entregable = "'.$_POST['t25_entregable'].'"';
        	
        } else {
        	$sql = 'INSERT INTO t46_cron_visita_proy VALUES(
	        		"'.$_POST['t02_cod_proy'].'",
					"'.$_POST['t25_anio'].'", 
					"'.$_POST['t25_entregable'].'", 			    
					"'.$f1.'",
					"'.$f2.'",
					"'.$_POST['estado'].'",
					"'.$_POST['costo_pago_1'].'",
					"'.$_POST['costo_pago_2'].'",
					"'.$this->Session->UserID.'" ,
					NOW(),
					"'.$_POST['id_supervisor'].'"
				)';			  			
        	
        }
        
        
        $rs =  $this->Execute($sql);
        if (mysql_affected_rows() > 0) {
        	return true;
        } else {
        	return false;
        }
         
    }
    // / <summary>
    // / Elimina el Plan de Visita del Monitor Externo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitasEliminar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t31_id']
        );
        $SP = "sp_del_plan_visita";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Cronograma de Visitas - Monitoreo Externo
    
    // egion Plan de Visitas - Actividades - Monitoreo Externo
    // / <summary>
    // / Retorna Listado de las actividades para el Planes de Visita
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function PlanVisitaActivListado($idProy)
    {
        $SP = "sp_sel_plan_visita_act";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna todos los datos correspondientes al Plan de Visita
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idVisita">Numero de la Visita</param>
    // / <returns>Array asociativo [Resultset mysqli_fetch_assoc]</returns>
    function PlanVisitaActivSeleccionar($idProy, $idVisita)
    {
        $SP = "sp_get_plan_visita_cab";
        $params = array(
            $idProy,
            $idVisita
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }
    // / <summary>
    // / Retorna Listado de las actividades para el Planes de Visita
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idVta">Codigo de la Visita Planeada</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function PlanVisitaActivListadoDet($idProy, $idVta)
    {
        $SP = "sp_sel_plan_visita_act_det";
        $params = array(
            $idProy,
            $idVta
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Guarda el nuevo Plan Especifico de la Visita
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitaActivNuevo(&$retnum)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $this->ConvertDate($_POST['t32_fch_vta']),
            $this->ConvertDate($_POST['t32_fch_vtater']),
            $_POST['t32_obj_vta'],
            $_POST['t32_per_ent'],
            $_POST['t32_rec_nec'],
            $this->Session->UserID
        );
        $SP = "sp_ins_plan_visita_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retnum = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Actualiza el Plan de Visita Especifico
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitaActivActualizar(&$retnum)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t32_id_vta'],
            $this->ConvertDate($_POST['t32_fch_vta']),
            $this->ConvertDate($_POST['t32_fch_vtater']),
            $_POST['t32_obj_vta'],
            $_POST['t32_per_ent'],
            $_POST['t32_rec_nec'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_plan_visita_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retnum = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina el Plan de Visita Especifico
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitaActivEliminar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t32_id_vta']
        );
        $SP = "sp_del_plan_visita_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda nueva Actividad del Plan Especifico de la Visita
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitaActivNuevoDet()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t32_id_vta'],
            $_POST['t32_cod_act'],
            $this->ConvertDate($_POST['t32_fch_ini']),
            $this->ConvertDate($_POST['t32_fch_ter']),
            $_POST['t32_esp_act'],
            $this->Session->UserID
        );
        $SP = "sp_ins_plan_visita_det";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Actualiza Actividad del Plan de Visita Especifico
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitaActivActualizarDet()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t32_id_vta'],
            $_POST['t32_id_act'],
            $_POST['t32_cod_act'],
            $this->ConvertDate($_POST['t32_fch_ini']),
            $this->ConvertDate($_POST['t32_fch_ter']),
            $_POST['t32_esp_act'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_plan_visita_det";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina la Actividad del Plan de Visita Especifico
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitaActivEliminarDet()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t32_id_vta'],
            $_POST['t32_id_act']
        );
        $SP = "sp_del_plan_visita_det";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Plan de Visitas - Actividades - Monitoreo Externo
    
    // egion Cronograma de Visitas Monitoreo Interno
    // / <summary>
    // / Retorna Listado del Cronograma de Visitas del Monitoreo Interno para un proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function PlanVisitasMIListado($idProy)
    {
        $SP = "sp_sel_cron_visita";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Guarda el nueva Fecha del Cronograma de Visita
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitasMINuevo()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t46_anio'],
            $_POST['t46_mes'],
            $_POST['t46_obs'],
            $this->Session->UserID
        );
        
        $SP = "sp_ins_cron_visita_mi";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            
            $this->Error = $ret['error'];
            return false;
        }
    }
    // / <summary>
    // / Actualiza la Visita del Cronograma para el Monitor Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitasMIActualizar(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t46_id'],
            $_POST['t46_anio'],
            $_POST['t46_mes'],
            $_POST['t46_obs'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_cron_visita_mi";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina Visita del Cronograma de Vistas del Monitor Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function PlanVisitasMIEliminar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t46_id']
        );
        $SP = "sp_del_cron_visita";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Cronograma de Visitas - Monitoreo Externo
} // fin de la Clase BLMonitoreo
