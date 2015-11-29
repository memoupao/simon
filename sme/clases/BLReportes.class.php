<?php
require_once ("BLBase.class.php");
// / -------------------------------------------------------------------------
// / Programmer Name : Aima R. Christian Created Date : 2010-07-01
// / Comments : Clase Manejadora de Reportes
// / Visor de Reportes
// / -------------------------------------------------------------------------
class BLReportes extends BLBase
{

    var $fecha;

    var $Session;

    var $Error;

    var $title = "";

    var $linkPage = "";

    var $params = "";

    var $filter = "";
    // -----------------------------------------------------------------------------
    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
        $this->GetParams();
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
    // isor de Reportes
    // / <summary>
    // / Prepara los Parametros para la generacion del Reporte.
    // / </summary>
    function GetParams()
    {
        try {
            error_reporting(0);
            $ret = "?";
            foreach ($_GET as $key => $value) {
                $KeyValue = $key . "=" . $value . "&";
                $ret = str_replace($KeyValue, "", $ret);
                if ($key != 'link' && $key != 'title' && $key != 'filter') {
                    $ret .= $key . "=" . $value . "&";
                }
            }
            if ($ret != "") {
                $ret = substr($ret, 0, strlen($ret) - 1);
            }
        } catch (Exception $ex) {
            $ret = "";
        }
        
        $querystring = implode("&", $_GET);
        
        $idReport = $_GET['ReportID'];
        
        if ($idReport == '') {
            $this->title = $_GET['title'];
            if ($this->title == "" || $this->title == NULL) {
                $this->title = "Sin Título";
            }
            $this->linkPage = $_GET['link'];
            $this->params = str_replace("?", "", $ret);
            $this->filter = $_GET['filter'];
        } else {
            $rowRPT = $this->SeleccionarReporte($idReport);
            $this->linkPage = $rowRPT['url_rpt'];
            $this->title = $rowRPT['tit_rpt'];
            $this->filter = $rowRPT['url_param'];
            $this->params = str_replace("?", "", $ret);
        }
        
        return true;
    }
    
    // / <summary>
    // / Genera script para la visualizacion del reporte
    // / </summary>
    function ViewReport()
    {
        /* Invocamos al metodo LoadReport para Cargar el Reporte */
        $script = '<script language="javascript">';
        $script .= 'LoadReport("' . $this->linkPage . '", "' . $this->params . '");';
        $script .= '</script>';
        echo ($script);
        return true;
    }

    private function ExportExcel()
    {
        return true;
    }

    private function ExportWord()
    {
        return true;
    }

    function Export($to)
    {
        if ($to == 'word') {
            return ExportWord();
        }
    }

    function ListadoExport()
    {
        $sql = "SELECT 1 as codigo, 'Excel'  as descripcion
		  UNION
		  SELECT 2 as codigo, 'Word'  as descripcion
		  UNION
		  SELECT 3 as codigo, 'PDF'  as descripcion
		  UNION
		  SELECT 4 as codigo, 'HTML'  as descripcion; ";
        
        return $this->Execute($sql);
    }
    // nd Visor
    
    // egion CRUD Reportes
    function ListadoCategoriaReportes()
    {
        return $this->ExecuteProcedureReader("sp_sel_reportes_categorias", NULL);
    }

    function ListadoReportes($Categoria = 0)
    {
        $SP = "sp_sel_reportes";
        $params = array(
            $Categoria
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function SeleccionarReporte($id)
    {
        $SP = "sp_get_reporte";
        $params = array(
            $id
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }
    // ndRegion
    
    // egion Reportes Varios
    function RepMatrizMonitoreo($concurso = '', $region = '', $sector = '', $tipoinst = '', $estado = '', $proyecto = '', $monitor = '')
    {
        $SP = "sp_rpt_matriz_monitoreo";
        $params = array(
            $concurso,
            $region,
            $sector,
            $tipoinst,
            $estado,
            $proyecto,
            $monitor
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Benchmark01($concurso = '*', $region = '*', $sector = '*', $tipoinst = '*', $estado = '*')
    {
        $SP = "sp_rpt_benchmark";
        $params = array(
            $concurso,
            $region,
            $sector,
            $tipoinst,
            $estado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepInformesMonitoreo($proy)
    {
        $SP = "sp_rpt_eval_informes_monitoreo";
        $params = array(
            $proy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepInstitucionesFiltro($like)
    {
        $SP = "sp_rpt_instituciones_filtro";
        $params = array(
            $like
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepInstituciones()
    {
        $SP = "sp_rpt_instituciones";
        // $params = array();
        $ret = $this->ExecuteProcedureReader($SP, NULL);
        return $ret;
    }

    function RepInstitucionesFilterPer($inst, $rela, $reg, $sec)
    {
        $SP = "sp_rpt_instituciones_filter_per";
        // $params = array();
        $ret = $this->ExecuteProcedureReader($SP, array(
            $inst,
            $rela,
            $reg,
            $sec
        ));
        return $ret;
    }

    function ListaNroProy_TiposInst($idConcurso, $cboEstado)
    {
        $SP = "sp_sel_tip_inst_proy";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaNroProy_TiposInstRegion($idConcurso, $cboEstado)
    {
        $SP = "sp_rpt_nroproy_region_alt";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaNroProy_MontoEstadoEjecucion()
    {
        $SP = "sp_rpt_nroproy_mont_estejec";
        $ret = $this->ExecuteProcedureReader($SP, NULL);
        return $ret;
    }

    function ListaNroProy_SecInst($idConcurso, $cboEstado)
    {
        $SP = "sp_sel_sec_inst_proy2";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaNroProy_SecReg($idConcurso, $cboEstado, $dpto)
    {
        $SP = "sp_sel_sec_inst_proy3";
        $params = array(
            $idConcurso,
            $cboEstado,
            $dpto
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // Eventually this should replace ListaNroProy_SecReg
    function ListaNroProy_SecReg2($idConcurso, $cboEstado)
    {
        $SP = "sp_sel_sec_inst_proy3";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaNroProy_RegSecInst($idConcurso, $cboEstado)
    {
        $SP = "sp_rpt_nroproy_region_act_inst";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaMontoProy_TiposInst($idConcurso, $cboEstado)
    {
        $SP = "sp_sel_montotip_inst_proy";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaAportesProy_TiposInst($idConcurso, $cboEstado)
    {
        $SP = "sp_sel_aporte_proy";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaTip_Inst()
    {
        $SP = "sp_sel_tip_inst";
        $ret = $this->ExecuteProcedureReader($SP, NULL);
        return $ret;
    }

    function RepFichaProy($idProy, $idVersion)
    {
        $SP = "sp_rpt_ficha_proyecto";
        $params = array(
            $idProy,
            $idVersion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function RepFichaProyAll($idProy, $idVersion, $all)
    {
        $SP = "sp_rpt_ficha_proyecto_all";
        $params = array(
            $idProy,
            $idVersion,
            $all
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RelFichaProy($pTipoInst)
    {
        $SP = "sp_rel_proy";
        $params = array(
            $pTipoInst
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepFichaProy_Fuentes($idProy, $idVersion)
    {
        $SP = "sp_rpt_ficha_proyecto_fuentes";
        $params = array(
            $idProy,
            $idVersion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepFichaProy_Fuentes_clasf($idProy, $idVersion, $idInstr)
    {
        $SP = "sp_rpt_ficha_proyecto_fee";
        $params = array(
            $idProy,
            $idVersion,
            $idInstr
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaNroProy_Region($idConcurso, $cboEstado)
    {
        $SP = "sp_rpt_nroproy_region";
        $params = array(
            $idConcurso,
            $cboEstado
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaNroProyxRegion($idConcurso, $dpto)
    {
        $SP = "sp_rpt_nroproy_region_c";
        $params = array(
            $idConcurso,
            $dpto
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rep_POA_Presupuesto($idProy, $isAnio)
    {
        $SP = "sp_rpt_poa_presupuesto";
        $params = array(
            $idProy,
            $isAnio
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rep_proy_mon_est($concurso)
    {
        $SP = "sp_rpt_proy_mont_est";
        $params = array(
            $concurso
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rep_Consolidado_Beneficiarios($pProy, $pAnio, $pTrim)
    {
        return $this->ExecuteProcedureReader("sp_rpt_consolidado_benef", array(
            $pProy,
            $pAnio,
            $pTrim
        ));
    }
    
    // egion Read Instituciones Monitoras
    function InstitucionesMonitorasRep()
    {
        return $this->ExecuteProcedureReader("sp_rpt_inst_monitoras", array());
    }

    function InstitucionesMonitorasRepMonitor($monitor)
    {
        return $this->ExecuteProcedureReader("sp_rpt_inst_monitoras_monitor", array(
            $monitor
        ));
    }

    function InstitucionesMonitorasRepMonitorProy($concurso, $region, $sector)
    {
        return $this->ExecuteProcedureReader("sp_rpt_inst_monitoras_proy", array(
            $concurso,
            $region,
            $sector
        ));
    }

    function InstitucionesEjecutoras($concurso, $region, $sector)
    {
        return $this->ExecuteProcedureReader("sp_rpt_inst_ejecutoras", array(
            $concurso,
            $region,
            $sector
        ));
    }
    
    /**
     * Genera resultados para el reporte de Avance Tecnico por Proyecto.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $proyecto 	Codigo del Proyecto
     * @return resource mixed
     *
     */
    public function avanceTecnicoPorProyecto($proyecto)
    {
    	$SP = "sp_rpt_avance_tec_finan";
    	$params = array(
    			$proyecto
    	);
    
    	$ret = $this->ExecuteProcedureReader($SP, $params);
    	return $ret;
    	 
    }
    
    
    
    // nd Reportes Varios
    
    // fin de la Clase BLReportes
}

