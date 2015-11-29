<?php
require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");
require_once ("UploadFiles.class.php");
require_once ("HardCode.class.php");

// / -------------------------------------------------------------------------
// / Programmer Name : Aima R. Christian Created Date : 2010-06-01
// / Comments : Clase BLInformes heredado de BLBase
// / Administra los Informes, Mensual, Trimestral, Financiero,
// / Monitoreo Externo y Monioreo Interno.
// / -------------------------------------------------------------------------
class BLInformes extends BLBase
{

    var $fecha;

    var $Session;

    var $Error;

    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }
    // Establece la Conexion de la base de Datos actual
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
    // / <summary>
    // / Retorna Listado de Componentes del Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaPeriodoInformesMensuales($pCodProy)
    {
        $aQuery = "SELECT t20_anio, t20_mes, MAX(t20_ver_inf) AS t20_ver_inf
				FROM t20_inf_mes
				WHERE t02_cod_proy = '$pCodProy'
				GROUP BY t20_anio, t20_mes";
        return $this->ExecuteQuery($aQuery);
    }

    function ListaPeriodoInformesTrimestrales($pCodProy)
    {
        $aQuery = "SELECT t25_anio, t25_trim, MAX(t25_ver_inf) AS t25_ver_inf
				FROM t25_inf_trim
				WHERE t02_cod_proy = '$pCodProy'
				GROUP BY t25_anio, t25_trim";
        return $this->ExecuteQuery($aQuery);
    }
    
    public function ListaPeriodoInformesSupervEntre($pCodProy, $idVersion)
    {
    	$aQuery = "SELECT fn_numero_entregable('".$pCodProy."', '".$idVersion."', 
    	t25_anio, t25_entregable) AS entregable, t25_anio, 
    	t25_entregable, MAX(t02_version) AS t02_version
    	FROM t25_inf_entregable
    	WHERE t02_cod_proy = '$pCodProy'
    	GROUP BY t25_anio, t25_entregable";
    	
    	return $this->ExecuteQuery($aQuery);
    }

    function ListaPeriodoInformesFinanciros($pCodProy)
    {
        $aQuery = "SELECT t40_anio, t40_mes, '1' as t40_ver_inf
				FROM t40_inf_financ
				WHERE t02_cod_proy = '$pCodProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function ListaPeriodoInformesVisitaMI($pCodProy)
    {
        $aQuery = "SELECT t45_id, t45_periodo, MAX(t45_ver_inf) as t45_ver_inf
				FROM t45_inf_mi
				WHERE t02_cod_proy = '$pCodProy'
				GROUP BY t45_id, t45_periodo";
        return $this->ExecuteQuery($aQuery);
    }

    /*function countInfBenef()
    {
        $aCodProy = $_GET['idProy'];
        $aCodAnio = $_GET['idAnio'];
        $aCodTrim = $_GET['idTrim'];
        $aQuery = "SELECT COUNT(*) AS total_benef FROM t25_inf_trim_capac
				WHERE t02_cod_proy = '$aCodProy' AND t25_anio = $aCodAnio AND t25_trim = $aCodTrim
				UNION
				SELECT COUNT(*) AS total_benef FROM t25_inf_trim_at
				WHERE t02_cod_proy = '$aCodProy3' AND t25_anio = $aCodAnio AND t25_trim = $aCodTrim
				UNION
				SELECT COUNT(*) AS total_benef FROM t25_inf_trim_cred
				WHERE t02_cod_proy = '$aCodProy' AND t25_anio = $aCodAnio AND t25_trim = $aCodTrim
				UNION
				SELECT COUNT(*) AS total_benef FROM t25_inf_trim_otros
				WHERE t02_cod_proy = '$aCodProy' AND t25_anio = $aCodAnio AND t25_trim = $aCodTrim";
        $aResult = $this->ExecuteQuery($aQuery);
        $aRow = mysql_fetch_assoc($aResult);
        mysql_free_result($aResult);

        return $aRow;
    }*/

    function ListaComponentes($idProy)
    {
        $SP = "sp_sel_componentes";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaActividadesComp($idProy, $idComp)
    {
        $Proy = new BLProyecto();
        $ver = $Proy->MaxVersion($idProy);
        $Proy = NULL;
        $SP = "sp_sel_actividades_comp";
        $params = array(
            $idProy,
            $ver,
            $idComp
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Retorna Listado de Actividades del Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaActividades($idProy)
    {
        $Proy = new BLProyecto();
        $ver = $Proy->MaxVersion($idProy);
        $Proy = NULL;
        $SP = "sp_sel_actividades";
        $params = array(
            $idProy,
            $ver
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna los Indicadores de Actividad, Planeados y Ejecutados
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresActividad($idProy, $idActiv, $anio, $mes)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_ind_act";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Subactividades, Planeados y Ejecutados
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaSubActividades($idProy, $idActiv, $anio, $mes)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_sub_act";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Problemas y Soluciones Adoptadas con el Informe
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <param name="$idversion">Version del Informe mensual </param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaProblemasSoluciones($idProy, $anio, $mes, $idversion)
    {
        $SP = "sp_sel_inf_prob_sol";
        $params = array(
            $idProy,
            $anio,
            $mes,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Anexos Fotograficos adjuntos al Informe
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <param name="$idversion">Version del Informe mensual </param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaAnexosFotograficos($idProy, $anio, $mes, $idversion)
    {
        $SP = "sp_sel_inf_anx_foto";
        $params = array(
            $idProy,
            $anio,
            $mes,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna los Indicadores de Proposito, Planeados y Ejecutados
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresProposito($idProy, $anio, $mes)
    {
        $SP = "sp_sel_inf_trim_ind_prop";
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna los Indicadores de Componente, Planeados y Ejecutados
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$comp">Codigo del Componente</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresComponente($idProy, $comp, $anio, $mes)
    {
        $SP = "sp_sel_inf_trim_ind_comp";
        $params = array(
            $idProy,
            $comp,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna los Indicadores de Componente, Planeados y Ejecutados para el Monitoreo Externo
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$comp">Codigo del Componente</param>
    // / <param name="$num">Numero del Informe de Monitoreo Externo</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    /*function ListaIndicadoresComponenteME($idProy, $comp, $num)
    {
        $SP = "sp_sel_inf_monext_ind_comp";
        $params = array(
            $idProy,
            $comp,
            $num
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }*/
    // / <summary>
    // / Retorna los Indicadores de Componente, Planeados y Ejecutados para el Monitoreo Interno
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$comp">Codigo del Componente</param>
    // / <param name="$num">Numero del Informe de Monitoreo Interno</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresComponenteMI($idProy, $comp, $num)
    {
        $SP = "sp_sel_inf_monint_ind_comp";
        $params = array(
            $idProy,
            $comp,
            $num
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Retorna los Indicadores de Actividad, Planeados y Ejecutados para el Informe trimestral
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$trim">Trimestre del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresActividadTrim($idProy, $idActiv, $anio, $trim)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_ind_act_trim";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $anio,
            $trim
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna los Indicadores de Actividad, Planeados y Ejecutados para el Informe trimestral
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$num">Numero del informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    /*function ListaIndicadoresActividadME($idProy, $idActiv, $num)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_ind_act_monext";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $num
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }*/


    /**
     * Lista de Caracteristicas de Productos
     *
     * @author  DA
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   string  $idActiv Id del Producto
     * @param   string  $num Id del Informe
     * @return  array
     *
     */
    /* function ListaIndicadoresActividadCarME($idProy, $idComp, $idActiv, $idInd, $num)
    {
    	$sql = "SELECT * FROM t09_act_ind_car
                WHERE  t02_cod_proy = '$idProy'
                AND  t02_version = '$idVersion'
                AND  t08_cod_comp = '$idComp'
                AND  t09_cod_act  = '$idActiv'
                AND  t09_cod_act_ind  = '$idInd';";

        return $this->ExecuteQuery($sql);
    } */



    // / <summary>
    // / Retorna los Indicadores de Actividad, Planeados y Ejecutados para el Informe de Monitoreo Interno
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$num">Numero del informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresActividadMI($idProy, $idActiv, $num)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_ind_act_monint";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $num
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Subactividades, Planeados y Ejecutados para el trimestre
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$trim">Trimestre del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaSubActividadesTrim($idProy, $idActiv, $anio, $trim)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_sub_act_trim";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $anio,
            $trim
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Subactividades, Planeados y Ejecutados para el Informe de Monitoreo
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$num">Numero del Informe de Monitoreo</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    /*function ListaSubActividadesME($idProy, $idActiv, $num)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_sub_act_monext";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $num
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }*/
    // / <summary>
    // / Retorna listado de Subactividades, Planeados y Ejecutados para el Informe de Monitoreo Interno
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$num">Numero del Informe de Monitoreo</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaSubActividadesMI($idProy, $idActiv, $num, $verInf)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_sub_act_monint";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $num,
            $verInf
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListaSubActividadesMExterno($idProy, $idActiv, $num, $verInf, $ini, $fin)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_sub_act_moni_ext";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $num,
            $verInf,
            $ini,
            $fin
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Retorna Analisis de Resultados, Problemas y Soluciones reportado en el informe Trimestral
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <param name="$idversion">Version del Informe mensual </param>
    // / <returns>Array [mysqli_fetch_assoc]</returns>
    function ListaAnalisisInfTrim($idProy, $anio, $mes, $idversion)
    {
        $SP = "sp_sel_inf_trim_analisis";
        $params = array(
            $idProy,
            $anio,
            $mes,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }
    // / <summary>
    // / Retorna listado de Anexos Fotograficos adjuntos al Informe Trimestral
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$trim">Trimestre del Informe</param>
    // / <param name="$idversion">Version del Informe Trimestral </param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaAnexosFotograficosTrim($idProy, $anio, $trim, $idversion)
    {
        $SP = "sp_sel_inf_anx_foto_trim";
        $params = array(
            $idProy,
            $anio,
            $trim,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Anexos adjuntos al Informe de Monitoreo Externo
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idNum">Numero del informe</param>
    // / <param name="$idversion">Version del Informe ME</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    /*function ListaAnexosInformeME($idProy, $idNum, $idversion)
    {
        $SP = "sp_sel_inf_monext_anexos";
        $params = array(
            $idProy,
            $idNum,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }*/
    // / <summary>
    // / Retorna listado de Anexos adjuntos al Informe de Monitoreo Interno
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idNum">Numero del informe</param>
    // / <param name="$idversion">Version del Informe ME</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaAnexosInformeMI($idProy, $idNum, $idversion)
    {
        $SP = "sp_sel_inf_monint_anexos";
        $params = array(
            $idProy,
            $idNum,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna listado de Anexos adjuntos al Informe Financiero
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaAnexosInformeFinanc($idProy, $idNum, $idversion)
    {
        $SP = "sp_sel_inf_financ_anexos";
        $params = array(
            $idProy,
            $idNum,
            $idversion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // egion Informes Mensuales
    // / <summary>
    // / Retorna Listado de Informes Mensuales Generados para cada Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function InformeMensualListado($idProy)
    {
        $SP = "sp_sel_inf_mes";
        $params = array(
            $idProy,
            0
        ); // 0=Anio (Si es 0 Todos los años)
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Selecciona los datos del informe
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <param name="$vs">Version del Informe</param>
    // / <returns>Array asociativo [mysqli_fetch_assoc]</returns>
    function InformeMensualSeleccionar($idProy, $anio, $mes, $vs)
    {
        $SP = "sp_get_inf_mes";
        $params = array(
            $idProy,
            $anio,
            $mes,
            $vs
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function InformeMensualUltimo($idProy)
    {
        $SP = "sp_get_inf_mes_ult";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    // / <summary>
    // / Guarda la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeNuevoCab(&$retvs)
    {
        $paramsa = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio']
        );
        $Arobado = $this->ExecuteFunction('fn_aprob_poa_tec', $paramsa);
        if (! $Arobado) {
            $this->Error = "El Poa a este año no esta aprobado";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['cbomes'],
            $_POST['t20_periodo'],
            $this->ConvertDate($_POST['t20_fch_pre']),
            $_POST['t20_estado'],
            $this->Session->UserID
        );
        $SP = "sp_ins_inf_mes_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    // / <summary>
    // / Actualiza la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeActualizarCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t20_anio'],
            $_POST['t20_mes'],
            $_POST['cboanio'],
            $_POST['cbomes'],
            $_POST['t20_ver_inf'],
            $_POST['t20_periodo'],
            $this->ConvertDate($_POST['t20_fch_pre']),
            $_POST['t20_estado'],
            $this->Session->UserID,
            $_POST['vb_se']
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_mes_cab", $params);

        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeEliminarCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t20_anio'],
            $_POST['t20_mes'],
            $_POST['t20_ver_inf']
        );
        $SP = "sp_del_inf_mes_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda los avances de Metas del Informe Mensual - Para los Indicadores de Actividad
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarIndicadoresActividad()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_mes', array(
            $_POST['t02_cod_proy'],
            $_POST['t09_ind_anio'],
            $_POST['t09_ind_mes']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "La Actividad Seleccionada no tiene registrado Indicadores...";
            return false;
        }

        $indic = $_POST['t09_cod_act_ind'];
        $metas = $_POST['txtIndActMes'];
        $descr = $_POST['txtIndactdes'];
        $logro = $_POST['txtIndActlog'];
        $dific = $_POST['txtIndActdif'];
        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_mes_ind_act";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $idInd,
                    $_POST['t09_ind_anio'],
                    $_POST['t09_ind_mes'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );

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

    // / <summary>
    // / Guarda los avances de Metas del Informe Mensual - Para las subActividades
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarSubActividades()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_mes', array(
            $_POST['t02_cod_proy'],
            $_POST['t09_sub_anio'],
            $_POST['t09_sub_mes']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Elija Primero la Actividad..";
            return false;
        }

        $subact = $_POST['t09_cod_sub'];
        $metas = $_POST['txtSubActMes'];
        $descr = $_POST['txtSubactdes'];
        $logro = $_POST['txtSubActlog'];
        $dific = $_POST['txtSubActdif'];
        $obsMT = $_POST['txtObsMonTec'];

        $countRet = 0;
        if (is_array($subact)) {
            $SP = "sp_upd_inf_mes_sub_act";

            for ($ax = 0; $ax < count($subact); $ax ++) {
                $sub = $subact[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }

                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];
                $omt = $obsMT[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $sub,
                    $_POST['t09_sub_anio'],
                    $_POST['t09_sub_mes'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $omt,
                    $this->Session->UserID
                );
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
    // / <summary>
    // / Guarda los Problemas y Soluciones reportados en el Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarProblemasSoluciones()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_mes', array(
            $_POST['t02_cod_proy'],
            $_POST['t20_anio'],
            $_POST['t20_mes']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Primero debe Guardar el Informe Mensual ...";
            return false;
        }

        $codis = $_POST['t20_cod_prob'];
        $probl = $_POST['t20_problemas'];
        $soluc = $_POST['t20_soluciones'];
        $resul = $_POST['t20_resultados'];
        /* $dific = $_POST['txtIndActdif']; */

        $countRet = 0;
        if (is_array($codis)) {
            $SP = "sp_upd_inf_mes_prob_solu";

            for ($ax = 0; $ax < count($codis); $ax ++) {
                $cod = $codis[$ax];
                $pro = $probl[$ax];
                $sol = $soluc[$ax];
                $res = $resul[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t20_anio'],
                    $_POST['t20_mes'],
                    $_POST['t02_version'],
                    $cod,
                    $pro,
                    $sol,
                    $res,
                    $this->Session->UserID
                );
                $ret = $this->ExecuteProcedureEscalar($SP, $params);

                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet >= 0) {
            // actualizamos los datos del Informe Mensual y Eliminamos los problemas en que estan vacios
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t20_anio'],
                $_POST['t20_mes'],
                $_POST['t02_version'],
                $_POST['t20_dificul'],
                $_POST['t20_program'],
                $this->Session->UserID
            );
            $this->ExecuteProcedureEscalar("sp_upd_inf_mes_cab_dif_pro", $params);
        }

        if ($countRet > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda los Anexos Fotograficos del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnexoFotografico()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_mes/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t20_anio'],
            $_POST['t20_mes'],
            $_POST['t02_ver_inf'],
            $NomFoto,
            $_POST['t20_desc_file'],
            $ext,
            $this->Session->UserID
        );

        $SP = "sp_ins_inf_anx_foto";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina Anexo Fotografico
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeEliminarAnxFoto()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t20_anio'],
            $_POST['t20_mes'],
            $_POST['t02_ver_inf'],
            $_POST['t20_cod_anx']
        );

        $SP = "sp_del_inf_anx_foto";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_mes/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Informes Mensuales

    // egion Informes Trimestral
    // / <summary>
    // / Retorna Listado de Informes Trimestrales Generados para cada Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function InformeTrimestralListado($idProy)
    {
        $SP = "sp_sel_inf_trim";
        $params = array(
            $idProy,
            0
        ); // 0=Anio (Si es 0 Todos los años)
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Selecciona los datos del informe Trimestral
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$trim">Mes del Informe</param>
    // / <param name="$vs">Version del Informe</param>
    // / <returns>Array asociativo [mysqli_fetch_assoc]</returns>
    function InformeTrimestralSeleccionar($idProy, $anio, $trim, $vs)
    {
        $SP = "sp_get_inf_trim";
        $params = array(
            $idProy,
            $anio,
            $trim,
            $vs
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }
    // argar Datos Predefinidos de un Nuevo Informe Trimestral
    function InformeTrimestralUltimo($idProy)
    {
        $SP = "sp_get_inf_trim_ult";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    // / <summary>
    // / Guarda la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeTrimNuevoCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['cbotrim']
        );
        $paramsa = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio']
        );
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_trim', $params);
        $Arobado = $this->ExecuteFunction('fn_aprob_poa_tec', $paramsa);
        if ($ExisteInfTrim) {
            $this->Error = "Ya fue Registrado el Informe Trimestral";
            return false;
        }
        if (! $Arobado) {
            $this->Error = "El Poa a este año no esta aprobado";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['cbotrim'],
            $_POST['t25_periodo'],
            $this->ConvertDate($_POST['t25_fch_pre']),
            $_POST['t25_estado'],
            $this->Session->UserID,
            $_POST['obs_mt']
        );
        $SP = "sp_ins_inf_trim_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    // / <summary>
    // / Actualiza la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeTrimActualizarCab(&$retvs)
    {
        if ($_POST['t25_anio'] != $_POST['cboanio'] || $_POST['t25_trim'] != $_POST['cbotrim']) {
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['cboanio'],
                $_POST['cbotrim']
            );
            $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_trim', $params);
            if ($ExisteInfTrim) {
                $this->Error = "El Informe Trimestral, ya se encuentra registrado, no se puede actualizar...";
                return false;
            }
        }
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim'],
            $_POST['cboanio'],
            $_POST['cbotrim'],
            $_POST['t25_ver_inf'],
            $_POST['t25_periodo'],
            $this->ConvertDate($_POST['t25_fch_pre']),
            $_POST['t25_estado'],
            $this->Session->UserID,
            $_POST['obs_mt']
        );

        $SP = "sp_upd_inf_trim_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            $retvs = $ret['msg'];
            return false;
        }
    }
    // / <summary>
    // / Elimina la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeTrimEliminarCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim'],
            $_POST['t25_ver_inf']
        );
        $SP = "sp_del_inf_trim_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Aprobar Informe Trimestral
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeTrimAprobar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim'],
            $_POST['t25_ver_inf'],
            $_POST['t25_comentarios'],
            $this->Session->UserID
        );
        $SP = "sp_aprueba_inf_trim";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    // egion Planes Especificos
    function InfTrim_Capac_Lista($idProy, $Anio, $Trim, $dpto, $prov, $dist, $case)
    {
        $SP = "sp_lis_inf_trim_capac";
        $params = array(
            $idProy,
            $Anio,
            $Trim,
            $dpto,
            $prov,
            $dist,
            $case
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    /* nuevo reporte */
    function InfTrim_Capac_Lista2($idProy, $Anio, $Trim, $dpto, $prov, $dist, $case)
    {
        $SP = "sp_lis_inf_trim_capac2";
        $params = array(
            $idProy,
            $Anio,
            $Trim,
            $dpto,
            $prov,
            $dist,
            $case
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function GuardarAvancePlanCapac()
    {
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t25_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim']
        ));
        if (! $ExisteInfTrim) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $proy = $_POST['t25_cod_proy'];
        $ver = $_POST['t25_version'];
        $anio = $_POST['t25_anio'];
        $trim = $_POST['t25_trim'];
        $arrtemas = $_POST['txtcodtemas'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);

        $countRet = 0;
        if (is_array($arrtemas)) {
            $SP = "sp_upd_inf_trim_capac";

            for ($ax = 0; $ax < count($arrtemas); $ax ++) {

                $name_tema = "txt_" . str_replace(".", "_", $arrtemas[$ax]);
                $arrvalues = $_POST[$name_tema];
                $strvalues = strtoupper(implode("|", $arrvalues));

                $arrcodi = explode(".", $arrtemas[$ax]);

                $params = array(
                    $proy,
                    $ver,
                    $anio,
                    $trim,
                    $arrcodi[0],
                    $arrcodi[1],
                    $arrcodi[2],
                    $arrcodi[3],
                    $strbenef,
                    $strvalues,
                    $this->Session->UserID
                );
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

    // lan de Asistencia tecnica
    function InfTrim_AT_Lista($idProy, $Anio, $Trim, $dpto, $prov, $dist, $case)
    {
        $SP = "sp_lis_inf_trim_at";
        $params = array(
            $idProy,
            $Anio,
            $Trim,
            $dpto,
            $prov,
            $dist,
            $case
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function InfTriReport_AT_temas($idProy, $version)
    {
        $SP = "sp_lis_inf_trim_mod_at";
        $params = array(
            $idProy,
            $version
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function InfTriReport_AT_Cap($idProy, $anio, $trim, $codBen)
    {
        $SP = "sp_lis_inf_trim_at_2";
        $params = array(
            $idProy,
            $anio,
            $trim,
            $codBen
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function InfTrim_Otros_Lista($idProy, $Anio, $Trim, $dpto, $prov, $dist, $case)
    {
        $SP = "sp_lis_inf_trim_otros";
        $params = array(
            $idProy,
            $Anio,
            $Trim,
            $dpto,
            $prov,
            $dist,
            $case
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function InfTrim_Otros_Lista_prod($idProy, $version)
    {
        $SP = "sp_lis_inf_plan_otros_infor";
        $params = array(
            $idProy,
            $version
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function InfTrim_Otros_Lista_Ben($idProy, $anio, $trim, $ben)
    {
        $SP = "sp_lis_inf_trim_otros2";
        $params = array(
            $idProy,
            $anio,
            $trim,
            $ben
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    /*function GuardarAvancePlanAT()
    {
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t25_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim']
        ));
        if (! $ExisteInfTrim) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $proy = $_POST['t25_cod_proy'];
        $ver = $_POST['t25_version'];
        $anio = $_POST['t25_anio'];
        $trim = $_POST['t25_trim'];
        $arrsub = $_POST['txtcodsub'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);

        $countRet = 0;
        if (is_array($arrsub)) {
            $SP = "sp_upd_inf_trim_at";

            for ($ax = 0; $ax < count($arrsub); $ax ++) {

                $name_sub = "txt_" . str_replace(".", "_", $arrsub[$ax]);
                $arrvalues = $_POST[$name_sub];
                $strvalues = strtoupper(implode("|", $arrvalues));

                $arrcodi = explode(".", $arrsub[$ax]);

                $params = array(
                    $proy,
                    $ver,
                    $anio,
                    $trim,
                    $arrcodi[0],
                    $arrcodi[1],
                    $arrcodi[2],
                    $strbenef,
                    $strvalues,
                    $this->Session->UserID
                );
                $ret = $this->ExecuteProcedureEscalar($SP, $params);
                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet > 0) {
            return true;
        } else {
            $this->Error .= "\n" . "No hay datos para actualizar";
            return false;
        }
    }*/

    // lan para Otros Servicios
    function GuardarAvancePlanOtros()
    {
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t25_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim']
        ));
        if (! $ExisteInfTrim) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $proy = $_POST['t25_cod_proy'];
        $ver = $_POST['t25_version'];
        $anio = $_POST['t25_anio'];
        $trim = $_POST['t25_trim'];
        $arrsub = $_POST['txtcodsub'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);

        $countRet = 0;
        if (is_array($arrsub)) {
            $SP = "sp_upd_inf_trim_otros";

            for ($ax = 0; $ax < count($arrsub); $ax ++) {
                $name_sub = "txt_" . str_replace(".", "_", $arrsub[$ax]);
                $arrvalues = $_POST[$name_sub];
                $strvalues = strtoupper(implode("|", $arrvalues));

                $name_sub = "val_" . str_replace(".", "_", $arrsub[$ax]);
                $arrconts = $_POST[$name_sub];
                $strcontenidos = strtoupper(implode("|", $arrconts));

                $arrcodi = explode(".", $arrsub[$ax]);

                $params = array(
                    $proy,
                    $ver,
                    $anio,
                    $trim,
                    $arrcodi[0],
                    $arrcodi[1],
                    $arrcodi[2],
                    $strbenef,
                    $strvalues,
                    $strcontenidos,
                    $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar($SP, $params);

                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet > 0) {
            return true;
        } else {
            $this->Error .= "\n" . "No hay datos para actualizar";
            return false;
        }
    }

    // lan de Creditos a beneficiarios
    function InfTrim_Credito_Lista($idProy, $Anio, $Trim, $codsub)
    {
        $arr = explode(".", $codsub);
        $idComp = $arr[0];
        $idAct = $arr[1];
        $idSub = $arr[2];
        $SP = "sp_lis_inf_trim_cred";
        $params = array(
            $idProy,
            $Anio,
            $Trim,
            $idComp,
            $idAct,
            $idSub
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        // print_r($this);
        return $ret;
    }

    function GuardarAvancePlanCreditos()
    {
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t25_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim']
        ));
        if (! $ExisteInfTrim) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $proy = $_POST['t25_cod_proy'];
        $ver = $_POST['t25_version'];
        $anio = $_POST['t25_anio'];
        $trim = $_POST['t25_trim'];
        $sub = $_POST['cbosub'];

        $arrsub = explode(".", $sub);

        $idComp = $arrsub[0];
        $idAct = $arrsub[1];
        $idSub = $arrsub[2];

        $arrmontos = $_POST['txt_montos'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);
        $strmontos = implode("|", $arrmontos);

        $countRet = 0;
        if (is_array($arrmontos)) {
            $SP = "sp_upd_inf_trim_cred";

            $params = array(
                $proy,
                $ver,
                $idComp,
                $idAct,
                $idSub,
                $anio,
                $trim,
                $strbenef,
                $strmontos,
                $this->Session->UserID
            );
            $ret = $this->ExecuteProcedureEscalar($SP, $params);
        }

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error .= "\n" . "No hay datos para actualizar";
            return false;
        }
    }

    // ndRegion

    // / <summary>
    // / Guarda los avances de Metas del Informe Trimestral - Para los Indicadores de Proposito
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarIndicadoresProposito()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t02_cod_proy'],
            $_POST['t07_ind_anio'],
            $_POST['t07_ind_trim']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $indic = $_POST['t07_cod_prop_ind'];
        $metas = $_POST['txtIndPropTrim'];
        $descr = $_POST['txtIndPropdes'];
        $logro = $_POST['txtIndProplog'];
        $dific = $_POST['txtIndPropdif'];
        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_trim_ind_prop";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $idInd,
                    $_POST['t07_ind_anio'],
                    $_POST['t07_ind_trim'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );
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
    // / <summary>
    // / Guarda los avances de Metas del Informe Trimestral - Para los Indicadores de Componente
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarIndicadoresComponente()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t02_cod_proy'],
            $_POST['t08_ind_anio'],
            $_POST['t08_ind_trim']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $indic = $_POST['t08_cod_comp_ind'];
        $metas = $_POST['txtIndCompTrim'];
        $descr = $_POST['txtIndCompdes'];
        $logro = $_POST['txtIndComplog'];
        $dific = $_POST['txtIndCompdif'];
        $obs = $_POST['txtIndCompobs'];

        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_trim_ind_comp_obs";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $ob = $obs[$ax];
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $idInd,
                    $_POST['t08_ind_anio'],
                    $_POST['t08_ind_trim'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $ob,
                    $this->Session->UserID
                );

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
    // / <summary>
    // / Guarda los avances de indicadores de actividad del Informe Trimestral
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarIndicadoresActividadTrim()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t02_cod_proy'],
            $_POST['t09_ind_anio'],
            $_POST['t09_ind_trim']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Primero debe Guardar la Caratula del Informe Trimestral ...";
            return false;
        }

        $indic = $_POST['t09_cod_act_ind'];
        $metas = $_POST['txtIndActTrim'];
        $descr = $_POST['txtIndactdes'];
        $logro = $_POST['txtIndActlog'];
        $dific = $_POST['txtIndActdif'];
        $obs = $_POST['txtIndActobs'];

        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_trim_ind_act_obs";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];
                $ob = $obs[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $idInd,
                    $_POST['t09_ind_anio'],
                    $_POST['t09_ind_trim'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $ob,
                    $this->Session->UserID
                );

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
    // / <summary>
    // / Guarda los avances de SubActividades en el Informe Trimestral
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarSubActividadesTrim()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t02_cod_proy'],
            $_POST['t09_sub_anio'],
            $_POST['t09_sub_trim']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Primero debe Guardar la Caratula del Informe trimestral...";
            return false;
        }

        $subact = $_POST['t09_cod_sub'];
        $metas = $_POST['txtSubActTrim'];
        $descr = $_POST['txtSubactdes'];
        $logro = $_POST['txtSubActlog'];
        $dific = $_POST['txtSubActdif'];
        $obsMT = $_POST['txtObsMonTec'];

        $countRet = 0;
        if (is_array($subact)) {
            $SP = "sp_upd_inf_trim_sub_act";

            for ($ax = 0; $ax < count($subact); $ax ++) {
                $sub = $subact[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }

                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];
                $omt = $obsMT[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $sub,
                    $_POST['t09_sub_anio'],
                    $_POST['t09_sub_trim'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $omt,
                    $this->Session->UserID
                );

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
    // / <summary>
    // / Guarda Analisis del Informe Trimestral
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnalisisTrim()
    {
        $ExisteInfMes = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim']
        ));
        if (! $ExisteInfMes) {
            $this->Error = "Primero debe Guardar el Informe Trimestral ...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim'],
            $_POST['t02_version'],
            $_POST['t25_resulta'],
            $_POST['t25_conclu'],
            $_POST['t25_limita'],
            $_POST['t25_fac_pos'],
            $_POST['t25_perspec'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_trim_analisis", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda los Anexos Fotograficos del Informe Trimestral
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnexoFotograficoTrim()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_trim/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim'],
            $_POST['t02_ver_inf'],
            $NomFoto,
            $_POST['t25_desc_file'],
            $ext,
            $this->Session->UserID
        );

        $SP = "sp_ins_inf_anx_foto_trim";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina Anexo Fotografico del Informe Trimestral
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeEliminarAnxFotoTrim()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t25_anio'],
            $_POST['t25_trim'],
            $_POST['t02_ver_inf'],
            $_POST['t25_cod_anx']
        );
        $SP = "sp_del_inf_anx_foto_trim";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_trim/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Informes Trimestrales

    // egion Informes Monitoreo Externo
    // / <summary>
    // / Retorna Listado de Informes Monitoreo Externo Generados para cada Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    /*function InformeMEListado($idProy)
    {
        $SP = "sp_sel_inf_monext";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }*/
    // / <summary>
    // / Selecciona los datos del informe Trimestral
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$trim">Mes del Informe</param>
    // / <param name="$vs">Version del Informe</param>
    // / <returns>Array asociativo [mysqli_fetch_assoc]</returns>
    /*function InformeMESeleccionar($idProy, $num, $vs)
    {
        $SP = "sp_get_inf_monext";
        $params = array(
            $idProy,
            $num,
            $vs
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }*/
    // / <summary>
    // / Guarda la Cabecera del Informe de Monitoreo externo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function InformeMENuevoCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['txtnuminf']
        );
        $paramsvb = array(
            $_POST['t02_cod_proy']
        );
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_monext', $params);


        $ExistvbInf = $this->ExecuteFunction('fn_existe_vb_inf_monext', $paramsvb);
        if ($ExistvbInf == 0) {
            $this->Error = "La solicitud de la visita aun no ha sido Aprobada";
            return false;
        }
        if ($ExistvbInf == 10) {
            $this->Error = "La visita aun no ha sido solicitada";
            return false;
        }
        if ($ExistvbInf == 250) {
            $this->Error = "El Informe Anterior esta en estado de Elaboracion";
            return false;
        }

        if ($ExisteInfTrim) {
            $this->Error = "Ya fue Registrado el Informe de Monitoreo";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t30_periodo'],
            $this->ConvertDate($_POST['t30_fch_pre']),
            $_POST['t30_estado'],
            $_POST['t30_intro'],
            $_POST['t30_fuentes'],
            $this->ConvertDate($_POST['t30_fec_ini_vis']),
            $this->ConvertDate($_POST['t30_fec_ter_vis']),
            $_POST['t30_anio'],
            $this->Session->UserID,
            $_POST['t30_per_ini'],
            $_POST['t30_per_fin']
        );

        $SP = "sp_ins_inf_monext_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }*/
    // / <summary>
    // / Actualiza la Cabecera del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function InformeMEActualizarCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['txtnuminf'],
            $_POST['t30_ver_inf'],
            $_POST['t30_periodo'],
            $this->ConvertDate($_POST['t30_fch_pre']),
            $_POST['t30_estado'],
            $_POST['t30_intro'],
            $_POST['t30_fuentes'],
            $this->ConvertDate($_POST['t30_fec_ini_vis']),
            $this->ConvertDate($_POST['t30_fec_ter_vis']),
            $_POST['t30_anio'],
            $this->Session->UserID,
            $_POST['t30_per_ini'],
            $_POST['t30_per_fin'],
            $_POST['t30_obs']
        );

        $SP = "sp_upd_inf_monext_cab";

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }*/
    // / <summary>
    // / Elimina la Cabecera del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function InformeMEEliminarCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id'],
            $_POST['t30_ver_inf']
        );
        $SP = "sp_del_inf_monext_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }*/
    // / <summary>
    // / Guarda los avances de Metas del Informe de Monitoreo - Para los Indicadores de Componente
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*
    function GuardarIndicadoresComponenteME()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monext', array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo ...";
            return false;
        }

        $indic = $_POST['t08_cod_comp_ind'];
        $metas = $_POST['txtIndCompTrim'];
        $descr = $_POST['txtIndCompdes'];
        $logro = $_POST['txtIndComplog'];
        $dific = $_POST['txtIndCompdif'];
        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_monext_ind_comp";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $idInd,
                    $_POST['t30_id'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );

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
    */
    // / <summary>
    // / Guarda los avances de indicadores de actividad del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function GuardarIndicadoresActividadME()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monext', array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo ...";
            return false;
        }

        $indic = $_POST['t09_cod_act_ind'];
        $metas = $_POST['txtIndActTrim'];
        $descr = $_POST['txtIndactdes'];
        $logro = $_POST['txtIndActlog'];
        $dific = $_POST['txtIndActdif'];
        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_monext_ind_act";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $idInd,
                    $_POST['t30_id'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );

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
    }*/
    // / <summary>
    // / Guarda los avances de SubActividades en el Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function GuardarSubActividadesME()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monext', array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo ...";
            return false;
        }

        $subact = $_POST['t09_cod_sub'];
        $metas = $_POST['txtSubActTrim'];
        $descr = $_POST['txtSubactdes'];
        $logro = $_POST['txtSubActlog'];
        $dific = $_POST['txtSubActdif'];

        $countRet = 0;
        if (is_array($subact)) {
            $SP = "sp_upd_inf_monext_sub_act";

            for ($ax = 0; $ax < count($subact); $ax ++) {
                $sub = $subact[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }

                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $sub,
                    $_POST['t30_id'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );

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
    }*/
    // / <summary>
    // / Guarda Conclusiones del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function GuardarConclusionesME()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monext', array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo ...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id'],
            $_POST['t30_ver_inf'],
            $_POST['t30_avance'],
            $_POST['t30_logros'],
            $_POST['t30_dificul'],
            $_POST['t30_reco_proy'],
            $_POST['t30_reco_fe'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_monext_conc", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }*/
    // / <summary>
    // / Guarda Calificaciones del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function GuardarCalificacionesME()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monext', array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo ...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id'],
            $_POST['t30_ver_inf'],
            $_POST['t30_crit_eva1'],
            $_POST['t30_crit_eva2'],
            $_POST['t30_crit_eva3'],
            $_POST['t30_crit_eva4'],
            $_POST['t30_crit_eva5'],
            $_POST['t30_crit_eva6'],
            $_POST['t30_crit_eva7'],
            $_POST['t30_califica'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_monext_calif", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }*/
    // / <summary>
    // / Guarda los Anexos del Informe de Monitoreo Externo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function GuardarAnexosME()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_me/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id'],
            $_POST['t30_ver_inf'],
            $NomFoto,
            $_POST['t30_desc_file'],
            $ext,
            $this->Session->UserID
        );

        $SP = "sp_ins_inf_monext_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }*/
    // / <summary>
    // / Elimina Anexo del Informe de Monitoreo Externo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    /*function EliminarAnexosME()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t30_id'],
            $_POST['t30_ver_inf'],
            $_POST['t30_cod_anx']
        );
        $SP = "sp_del_inf_monext_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_me/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }*/

    function InformeMESeleccionarFecha($idProy, $fecha)
    {
        $SP = "sp_get_inf_monext_fecha";
        $params = array(
            $idProy,
            $fecha
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }
    // ndRegion Informe Monitoreo Externo

    // egion Informes Monitoreo Interno
    // / <summary>
    // / Retorna Listado de Informes Monitoreo Interno Generados para cada Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    /*function InformeMIListado($idProy)
    {
        $SP = "sp_sel_inf_monint";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }*/
    // / <summary>
    // / Selecciona los datos del informe de Monitoreo Interno
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$num">Numero del Informe</param>
    // / <param name="$vs">Version del Informe</param>
    // / <returns>Array asociativo [mysqli_fetch_assoc]</returns>
    /*function InformeMISeleccionar($idProy, $num, $vs)
    {
        $SP = "sp_get_inf_monint";
        $params = array(
            $idProy,
            $num,
            $vs
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }*/
    // / <summary>
    // / Guarda la Cabecera del Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeMINuevoCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['txtnuminf']
        );
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_monint', $params);
        if ($ExisteInfTrim) {
            $this->Error = "Ya fue Registrado el Informe de Monitoreo Interno";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t45_periodo'],
            $this->ConvertDate($_POST['t45_fch_pre']),
            $_POST['t45_estado'],
            $_POST['t45_intro'],
            $_POST['t45_fuentes'],
            $this->ConvertDate($_POST['t45_fec_ini_vis']),
            $this->ConvertDate($_POST['t45_fec_ter_vis']),
            $_POST['t45_per_ini'],
            $_POST['t45_per_fin'],
            $this->Session->UserID
        );

        $SP = "sp_ins_inf_monint_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Actualiza la Cabecera del Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeMIActualizarCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['txtnuminf'],
            $_POST['t45_ver_inf'],
            $_POST['t45_periodo'],
            $this->ConvertDate($_POST['t45_fch_pre']),
            $_POST['t45_estado'],
            $_POST['t45_intro'],
            $_POST['t45_fuentes'],
            $this->ConvertDate($_POST['t45_fec_ini_vis']),
            $this->ConvertDate($_POST['t45_fec_ter_vis']),
            $_POST['t45_per_ini'],
            $_POST['t45_per_fin'],
            $this->Session->UserID
        );

        $SP = "sp_upd_inf_monint_cab";

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina la Cabecera del Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeMIEliminarCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id'],
            $_POST['t45_ver_inf']
        );
        $SP = "sp_del_inf_monint_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda los avances de Metas del Informe de Monitoreo Interno- Para los avances de Indicadores de Componente
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarIndicadoresComponenteMI()
    {
        $ExisteInfMI = $this->ExecuteFunction('fn_existe_inf_monint', array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id']
        ));
        if (! $ExisteInfMI) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo Interno ...";
            return false;
        }

        $indic = $_POST['t08_cod_comp_ind'];
        $metas = $_POST['txtIndCompTrim'];
        $descr = $_POST['txtIndCompdes'];
        $logro = $_POST['txtIndComplog'];
        $dific = $_POST['txtIndCompdif'];

        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_monint_ind_comp";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];
                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $idInd,
                    $_POST['t45_id'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );
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
    // / <summary>
    // / Guarda los avances de indicadores de actividad del Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarIndicadoresActividadMI()
    {
        $ExisteInfMI = $this->ExecuteFunction('fn_existe_inf_monint', array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id']
        ));
        if (! $ExisteInfMI) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo Interno...";
            return false;
        }

        $indic = $_POST['t09_cod_act_ind'];
        $metas = $_POST['txtIndActTrim'];
        $descr = $_POST['txtIndactdes'];
        $logro = $_POST['txtIndActlog'];
        $dific = $_POST['txtIndActdif'];
        $countRet = 0;
        if (is_array($indic)) {
            $SP = "sp_upd_inf_monint_ind_act";

            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $_POST['t08_cod_comp'],
                    $_POST['t09_cod_act'],
                    $idInd,
                    $_POST['t45_id'],
                    $Meta,
                    $des,
                    $log,
                    $dif,
                    $this->Session->UserID
                );

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
    // / <summary>
    // / Guarda los Avances de SubActividades en el Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarSubActividadesMI()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monint', array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo ...";
            return false;
        }

        $arrayCodSub = $_POST['txt_cod_sub'];
        $arrayComSub = $_POST['txtcoment_fisico'];

        $numrows = 0;
        for ($x = 0; $x < count($arrayCodSub); $x ++) {
            $arrCodi = explode(".", $arrayCodSub[$x]);
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t45_id'],
                $_POST['t45_ver_inf'],
                $arrCodi[0],
                $arrCodi[1],
                $arrCodi[2],
                $arrayComSub[$x],
                $this->Session->UserID
            );

            $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_monint_sub_act", $params);
            $numrows += $ret['numrows'] == - 1 ? 0 : $ret['numrows'];
        }

        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }
    // / <summary>
    // / Guarda Los comentarios acerca del analisis de los avances del Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnalisisAvancesMI()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monint', array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo Interno...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id'],
            $_POST['t45_ver_inf'],
            $_POST['t45_avance_comp'],
            $_POST['t45_avance_cap'],
            $_POST['t45_avance_fin'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_monint_avanc", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda Informacion Adicional sobre el Informe de Monitoreo Interno.
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarInfAdicionalMI()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_monint', array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe de Monitoreo Interno...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id'],
            $_POST['t45_ver_inf'],
            $_POST['t45_eva1'],
            $_POST['t45_eva2'],
            $_POST['t45_eva3'],
            $_POST['t45_eva4'],
            $_POST['t45_eva5'],
            $_POST['t45_eva6'],
            $_POST['t45_eva7'],
            $_POST['t45_eva1_obs'],
            $_POST['t45_eva2_obs'],
            $_POST['t45_eva3_obs'],
            $_POST['t45_eva4_obs'],
            $_POST['t45_eva5_obs'],
            $_POST['t45_eva6_obs'],
            $_POST['t45_eva7_obs'],
            $_POST['t45_logros'],
            $_POST['t45_dificul'],
            $_POST['t45_reco_proy'],
            $_POST['t45_reco_fe'],
            $_POST['t45_califica'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_monint_adicional", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda los Anexos del Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnexosMI()
    {
        $objFiles = new UploadFiles("txtNomFile");

        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_mi/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id'],
            $_POST['t45_ver_inf'],
            $NomFoto,
            $_POST['t45_desc_file'],
            $ext,
            $this->Session->UserID
        );
        $SP = "sp_ins_inf_monint_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina Anexo del Informe de Monitoreo Interno
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function EliminarAnexosMI()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t45_id'],
            $_POST['t45_ver_inf'],
            $_POST['t45_cod_anx']
        );
        $SP = "sp_del_inf_monint_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_mi/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function InformeMISeleccionarFecha($idProy, $fecha)
    {
        $SP = "sp_get_inf_monint_fecha";
        $params = array(
            $idProy,
            $fecha
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    // ndRegion Informe Monitoreo Interno

    // egion Informes Financieros
    function InformeFinancierolUltimo($idProy)
    {
        return $this->ExecuteProcedureEscalar("sp_get_inf_fin_mes_ult", array(
            $idProy
        ));
    }

    function GetSuspentions($pProy)
    {
        $aQuery = "SELECT * FROM t02_suspenciones WHERE t02_cod_proy = '$pProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function GetStartingYearMoth($pProy, $pYear, $pIntval)
    {
        $aSusRs = $this->GetSuspentions($pProy);
        if (mysql_num_rows($aSusRs) > 0) {
            while ($aRow = mysql_fetch_assoc($aSusRs)) {
                if (($pYear + 1) == $aRow['t02_version']) {
                    $pYear ++;
                    $pIntval = 1;
                }
            }
        }
        return array(
            $pYear,
            $pIntval
        );
    }

    function GetStartingPeriod($pProy, $pYear, $pMonth, $pAsTrim = false)
    {
        $aMesesTxt = 'Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre';
        $aPerNum = null;
        $aPerTxt = null;
        $aPoasFechas = $this->GetPoasSuspentions($pProy);

        foreach ($aPoasFechas as $aPoaDate) {
            if ($aPoaDate['t02_anio'] == $pYear) {
                $aMesArr = explode(',', $aMesesTxt);

                if ($pAsTrim) {
                    $aPerNum = ceil($pMonth / 3);
                    $aTrimIni = strtotime($aPoaDate['poa_fch_ini'] . ' + ' . ($pMonth - 3) . ' months');
                    $aTrimFin = strtotime($aPoaDate['poa_fch_ini'] . ' + ' . ($pMonth - 1) . ' months');
                    $aPerTxt = $aMesArr[date('m', $aTrimIni) - 1] . ' ' . date('Y', $aTrimIni) . ' - ' . $aMesArr[date('m', $aTrimFin) - 1] . ' ' . date('Y', $aTrimFin);
                } else {
                    $aDate = date("m", strtotime($aPoaDate['poa_fch_ini'])) + ($pMonth - 1);
                    $aPerNum = $aDate;
                    $aPerTxt = $aMesArr[$aDate - 1] . ' ' . date("Y", strtotime($aPoaDate['poa_fch_ini'] . " + " . ($pMonth - 1) . " months"));
                }
                break;
            }
        }

        return array(
            $aPerNum,
            $aPerTxt
        );
    }

    function GetPoasSuspentions($pProy)
    {
        $aQuery = "SELECT	w1.t02_cod_proy, " . "w1.t02_version, " . "w1.fch_ini, " . "DATE_ADD(w1.fch_ini, INTERVAL (w1.t02_anio - 1) * 12 MONTH) AS poa_fch_ini, " . "DATE_ADD(w1.fch_ini, INTERVAL w1.t02_anio * 12 MONTH) AS poa_fch_fin, " . "w1.t02_anio, " . "w1.t02_fch_susp, " . "w1.t02_fch_unsusp, " . "w1.t02_fch_reinic, " . "w1.meses_susp " . "FROM ( " . "SELECT	t1.t02_cod_proy, " . "t2.t02_version, " . "fn_fecha_inicio_proy(t1.t02_cod_proy, t2.t02_version) AS fch_ini, " . "t1.t02_anio, " . "t3.t02_fch_susp, " . "t3.t02_fch_unsusp, " . "t3.t02_fch_reinic, " . "TIMESTAMPDIFF(MONTH, t3.t02_fch_susp, t3.t02_fch_reinic) AS meses_susp " . "FROM	t02_poa t1 " . "JOIN	t02_proy_version t2 ON (t2.t02_cod_proy = t1.t02_cod_proy AND t2.t02_anio = t1.t02_anio) " . "LEFT JOIN t02_suspenciones t3 ON (t3.t02_cod_proy = t1.t02_cod_proy AND t3.t02_version = t2.t02_version) " . "WHERE	t1.t02_cod_proy = '$pProy' " . ") AS w1;";
        $aRs = $this->ExecuteQuery($aQuery);
        $aResult = array();
        while ($aRow = mysql_fetch_assoc($aRs))
            $aResult[] = $aRow;

        for ($i = 0; $i < count($aResult); $i ++) {
            if ($aResult[$i]['t02_fch_susp']) {
                $aResult[$i]['poa_fch_fin'] = $aResult[$i]['t02_fch_susp'];
            }
            if ($i > 0 && $aResult[$i - 1]['t02_fch_reinic']) {
                $aResult[$i]['poa_fch_ini'] = $aResult[$i - 1]['t02_fch_reinic'];
            }
        }

        return $aResult;
    }

    // / <summary>
    // / Retorna Listado de Informes Financieros Generados para cada Proyecto
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function InformeFinancieroListado($idProy)
    {
        $SP = "sp_sel_inf_financ";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function InformeProcentajeListado($idProy, $mes, $idVersion)
    {
        $SP = "sp_lis_presup_gastoAcum_mes";
        $params = array(
            $idProy,
            $mes,
            $idVersion
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Selecciona los datos del informe Financiero
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Array asociativo [mysqli_fetch_assoc]</returns>
    function InformeFinancieroSeleccionar($idProy, $anio, $mes)
    {
        $SP = "sp_get_inf_financ";
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function InformeFinancieroNuevoCab()
    {
        $paramsa = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio']
        );
        $Arobado = $this->ExecuteFunction('fn_aprob_poa_fin', $paramsa);
        if (! $Arobado) {
            $this->Error = "El Poa a este año no esta aprobado";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['cbomes'],
            $this->ConvertDate($_POST['t40_fch_pre']),
            $_POST['t40_periodo'],
            $_POST['t40_obs'],
            $_POST['t40_est_eje'],
            $this->Session->UserID,
            $_POST['inf_fi_ter']
        ); // modificado 28/11/2011

        $SP = "sp_ins_inf_financ_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function InformeFinancieroActualizaCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes'],
            $_POST['cboanio'],
            $_POST['cbomes'],
            $this->ConvertDate($_POST['t40_fch_pre']),
            $_POST['t40_periodo'],
            $_POST['t40_obs'],
            $_POST['t40_obs_moni'],
            $_POST['t40_est_eje'],
            $_POST['t40_est_mon'],
            $this->Session->UserID,
            $_POST['inf_fi_ter'],
            $_POST['vb_se']
        );

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_financ_cab", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function InformeFinancieroEliminarCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes']
        );

        $SP = "sp_del_inf_financ";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function InformeFinancieroOtrosGastos()
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_financ', array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes']
        ));
        if (! $ExisteInf) {
            $this->Error = "Primero debe Guardar el Periodo del Informe Financiero ...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes'],
            $_POST['t40_otro_ing'],
            $_POST['t40_abo_bco'],
            $_POST['t40_otro_ing_obs'],
            $_POST['t40_abo_bco_obs'],
            $this->Session->UserID
        );

        $SP = "sp_upd_inf_financ_otro_gasto";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function InformeFinancieroExcedentes()
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_financ', array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes']
        ));
        if (! $ExisteInf) {
            $this->Error = "Primero debe Guardar el Periodo del Informe Financiero ...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes'],
            $this->ConvertDate($_POST['t40_cor_ctb']),
            $_POST['t40_caja'],
            $_POST['t40_bco_mn'],
            $_POST['t40_ent_rend'],
            $_POST['t40_cxc'],
            $_POST['t40_cxp'],
            $_POST['t40_caja_obs'],
            $_POST['t40_bco_mn_obs'],
            $_POST['t40_ent_rend_obs'],
            $_POST['t40_cxc_obs'],
            $_POST['t40_cxp_obs'],
            $this->Session->UserID
        );

        $SP = "sp_upd_inf_financ_excedentes";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    // / <summary>
    // / Guarda los avances de Gastos del Informe Financiero
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAvanceGastos($FteFinanc)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_financ', array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes']
        ));
        if (! $ExisteInf) {
            $this->Error = "Primero debe Guardar el Periodo del Informe Financiero ...";
            return false;
        }

        $Montos = $_POST[$FteFinanc . "_txtRubroEjec"];
        $Codigo = $_POST[$FteFinanc . "_Id"];
        $Estado = $_POST[$FteFinanc . "_Estado"];
        $Observa = $_POST[$FteFinanc . "_Obs"];

        $countRet = 0;
        if (is_array($Codigo)) {
            $SP = "sp_upd_inf_financ_gastos";

            for ($ax = 0; $ax < count($Codigo); $ax ++) {
                $arrCodigos = explode(".", $Codigo[$ax]);
                $idComp = $arrCodigos[0];
                $idAct = $arrCodigos[1];
                $idSAct = $arrCodigos[2];
                $idRub = $arrCodigos[3];

                $Gasto = $Montos[$ax];
                if ($Gasto == "") {
                    $Gasto = 0;
                }
                $est = $Estado[$ax];
                $obs = $Observa[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $idComp,
                    $idAct,
                    $idSAct,
                    $idRub,
                    $FteFinanc,
                    $_POST['t40_anio'],
                    $_POST['t40_mes'],
                    $Gasto,
                    $est,
                    $obs,
                    $this->Session->UserID
                );

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
    // modificado 28/11/2011 --> verificar si Periodo de informe financiero esta guardado
    function vPIFinancieroGuardado($t02_cod_proy, $t40_anio, $t40_mes)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_financ', array(
            $t02_cod_proy,
            $t40_anio,
            $t40_mes
        ));
        if (! $ExisteInf) {
            return false;
        }
        return true;
    }

    function vInformeMensual($t02_cod_proy, $t40_anio, $t40_mes)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_mes', array(
            $t02_cod_proy,
            $t40_anio,
            $t40_mes
        ));
        if (! $ExisteInf) {
            return false;
        }
        return true;
    }

    function vInformeTrimestral($t02_cod_proy, $t40_anio, $t40_mes)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_trim', array(
            $t02_cod_proy,
            $t40_anio,
            $t40_mes
        ));
        if (! $ExisteInf) {
            return false;
        }
        return true;
    }

    /*function vInformeMI($t02_cod_proy, $txtnuminf)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_monint', array(
            $t02_cod_proy,
            $txtnuminf
        ));
        if (! $ExisteInf) {
            return false;
        }
        return true;
    }*/
    // fin
    function GuardarComentariosGastos($FteFinanc)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_financ', array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes']
        ));
        if (! $ExisteInf) {
            $this->Error = "Primero debe Guardar el Periodo del Informe Financiero ...";
            return false;
        }

        $Codigo = $_POST[$FteFinanc . "_Id"];
        $Observa = $_POST[$FteFinanc . "_Obs"];

        $countRet = 0;
        if (is_array($Codigo)) {
            $SP = "sp_upd_inf_financ_gastos_coment";

            for ($ax = 0; $ax < count($Codigo); $ax ++) {
                $arrCodigos = explode(".", $Codigo[$ax]);
                $idComp = $arrCodigos[0];
                $idAct = $arrCodigos[1];
                $idSAct = $arrCodigos[2];
                $idRub = $arrCodigos[3];

                $obs = $Observa[$ax];

                $params = array(
                    $_POST['t02_cod_proy'],
                    $idComp,
                    $idAct,
                    $idSAct,
                    $idRub,
                    $FteFinanc,
                    $_POST['t40_anio'],
                    $_POST['t40_mes'],
                    $obs,
                    $this->Session->UserID
                );
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

    // / <summary>
    // / Guarda los Anexos del Informe Financiero
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnexosInformeFinanc()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_financ/';

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes'],
            $NomFoto,
            $_POST['t40_desc_file'],
            $ext,
            $this->Session->UserID
        );

        $SP = "sp_ins_inf_financ_anexo";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Elimina Anexo del Informe financiero
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function EliminarAnexosInformeFinanc()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t40_anio'],
            $_POST['t40_mes'],
            $_POST['t40_cod_anx']
        );

        $SP = "sp_del_inf_financ_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_financ/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function ImportarGastos_01($FolderUpload, &$urlFile)
    {
        $objFiles = new UploadFiles("txtarchivo");
        $NomFile = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= $FolderUpload;

        // $urlfoto = $_POST['t02_cod_proy'].'_'.$this->Session->UserID.'_'.$ext ;
        $urlFile = $_POST['txtCodProy'] . '___' . $NomFile;
        return $objFiles->SavesAs($urlFile);
    }

    function ImportarGastos_02($idProy, $anio, $mes, $fte, $codigos, $rubros, $montos)
    {
        $SP = "sp_inf_financ_imp_gastos";
        $params = array(
            $idProy,
            $anio,
            $mes,
            $fte,
            $codigos,
            $rubros,
            $montos,
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        if ($ret) {
            $row = mysqli_fetch_assoc($ret);
            $ret->free();
        } else {
            $row = NULL;
        }
        return $row;
    }

    function ImportarGastos_03($idProy, $anio, $mes, $fte)
    {
        $SP = "sp_inf_financ_imp_gastos_err";
        $params = array(
            $idProy,
            $anio,
            $mes,
            $fte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ImportarGastos_04($idProy, $anio, $mes, $fte)
    {
        $params = array(
            $idProy,
            $anio,
            $mes,
            $fte,
            $this->Session->UserID
        );

        $SP = "sp_inf_financ_imp_gastos_guardar";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    // eportes - Informe Financiero
    function RepInforme_Anexo01($proy, $Anio, $Mes, $FteFinanc)
    {
        $SP = "sp_rpt_inf_financ_anexo_01";
        $params = array(
            $proy,
            $Anio,
            $Mes,
            $FteFinanc
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepInforme_Anexo03($proy, $Anio, $Mes, $FteFinanc)
    {
        $SP = "sp_rpt_inf_financ_anexo_03";
        $params = array(
            $proy,
            $Anio,
            $Mes,
            $FteFinanc
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // ndRegion Financieros

    // eportes y Estadisticas de los Informes
    // / <summary>
    // / Resumen de las SubActividades planeadas para el mes y año
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año Planeado</param>
    // / <param name="$mes">Mes Planeado</param>
    // / <returns>ResultSet [mysqli]</returns>
    function RptCumplimiento_mes($idProy, $anio, $mes)
    {
        $SP = "sp_rpt_cump_inf_mes";
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Resumen del Calculo del % de Cumplimiento en medicion a las SubActividades planeadas acumuladas al periodo en cuestion
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año Planeado</param>
    // / <param name="$mes">Mes Planeado</param>
    // / <returns>ResultSet [mysqli]</returns>
    function RptCumplimiento_mesAcumulado($idProy, $anio, $mes)
    {
        $SP = "sp_rpt_cump_inf_mes_acum";
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RptCumplimientoFinanc_mesAcumulado($idProy, $anio, $mes)
    {
        $SP = "sp_rta_cump_inf_financ_acum_mes";
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RptCumplimientoFinanc_mes($idProy, $anio, $mes)
    {
        $SP = "sp_rta_cump_inf_financ_mes";
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Resumen de las SubActividades planeadas para el trimestre y año
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año Planeado</param>
    // / <param name="$mes">Trimestre Planeado</param>
    // / <returns>ResultSet [mysqli]</returns>
    function RptCumplimiento_trim($idProy, $anio, $trim)
    {
        $SP = "sp_rpt_cump_inf_trim";
        $params = array(
            $idProy,
            $anio,
            $trim
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RptCumplimiento_trimAcumulado($idProy, $anio, $trim)
    {
        $SP = "sp_rpt_cump_inf_mes_acum";
        $mes = ($trim * 3);
        $params = array(
            $idProy,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // ndRegion
    function EnviarCambioEstado()
    {
        $SP = "sp_upd_inf_mes_cam_est";
        $SPf = "sp_upd_inf_mes_financ_cam_est";

        $params = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            $_POST['idMes'],
            $_POST['t20_ver_inf'],
            $_POST['estado']
        );

        $paramsf = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            $_POST['idMes'],
            $_POST['estado']
        );

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        $retf = $this->ExecuteProcedureEscalar($SPf, $paramsf);
        /*
         * print_r($this); exit();
         */
        /*if ($ret['numrows'] >= 0 && $retf['numrows'] > 0) {
            $objFunc = new Functions();

            $SP = "sp_sel_mensajes";
            $ret = $this->ExecuteProcedureReader($SP, '');

            $id = $objFunc->enviar_mail($ret);
            $ids = array(implode(",",$id));
            $SPA = "sp_upd_mensajes";
            $retA = $this->ExecuteProcedureReader($SPA, $ids);

            return true;
        } else {
            return false;
        }*/

        return true;
        }

    /*function EnviarCambioEstadoTrim()
    {
        $SP = "sp_upd_inf_trim_cambio_estado";
        $SPf = "sp_upd_inf_mes_financ_cam_est";

        $params = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            $_POST['idTrim'],
            $_POST['vs'],
            $_POST['estado']
        );

        $paramsf = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            ($_POST['idTrim'] * 3),
            $_POST['estado']
        );

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        $retf = $this->ExecuteProcedureEscalar($SPf, $paramsf);

        if ($ret['numrows'] >= 0 || $retf['numrows'] >= 0) {
            $objFunc = new Functions();
            $ret = $this->ExecuteProcedureReader("sp_sel_mensajes", NULL);

            $id = $objFunc->enviar_mail($ret);
            $strMensajes = implode(",", $id);
            $retA = $this->ExecuteProcedureEscalar("sp_upd_mensajes", $strMensajes);

            return true;
        } else {
            return false;
        }
    }*/

    /*function EnviarCambioEstadoTrimCorr()
    {
        $SP = "sp_upd_inf_trim_upd_est";
        $SPf = "sp_upd_inf_mes_financ_cam_est";

        $params = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            $_POST['idTrim'],
            $_POST['obs'],
            $_POST['vs'],
            $_POST['estado']
        );

        $paramsf = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            ($_POST['idTrim'] * 3),
            $_POST['estado']
        );

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        $retf = $this->ExecuteProcedureEscalar($SPf, $paramsf);

        if ($ret['numrows'] >= 0 || $retf['numrows'] >= 0) {

            return true;
        } else {
            return false;
        }
    }*/

    function EnviarCambioEstadoF()
    {
        $SP = "sp_upd_inf_fin_mes_cam_est";

        $params = array(
            $_POST['idProy'],
            $_POST['idAnio'],
            $_POST['idMes'],
            $_POST['estado']
        );

        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        /*
         * print_r($params); exit();
         */
        if ($ret['numrows'] >= 0) {
            $objFunc = new Functions();

            $SP = "sp_sel_mensajes";
            $ret = $this->ExecuteProcedureReader($SP, '');

            $id = $objFunc->enviar_mail($ret);
            $SPA = "sp_upd_mensajes";
            $retA = $this->ExecuteProcedureReader($SPA, $id);

            return true;
        } else {
            return false;
        }
    }

    // / <summary>
    // / Muestra el ultimo Informe Unico Anual
    // / </summary>
    // / <returns>Array Asociativo</returns>
    function InformeUnicoAnualUltimo($idProy)
    {
        $SP = "sp_get_inf_unico_anual_ult";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    // / <summary>
    // / Guarda la Cabecera del Informe Mensual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeUnicoAnualCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio']
        );
        $ExisteInfTrim = $this->ExecuteFunction('fn_existe_inf_unico_anual', $params);
        if ($ExisteInfTrim) {
            $this->Error = "Ya fue Registrado el Informe Unico Anual";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['fini'],
            $_POST['ffin'],
            $_POST['t55_periodo'],
            $this->ConvertDate($_POST['t55_fch_pre']),
            $_POST['t55_estado'],
            $this->Session->UserID
        );
        $SP = "sp_ins_inf_unico_anual_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);

        if ($ret['numrows'] > 0) {
            $retvs = $ret['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function InformeUnicoAnualCabUpdate($pParams, &$pReturnVals)
    {
        array_push($pParams, $this->Session->UserID);
        $aResult = $this->ExecuteProcedureEscalar("sp_upd_inf_unico_anual_cab", $pParams);

        if ($aResult['numrows'] > 0) {
            $pReturnVals = $aResult['codigo'];
            return true;
        } else {
            return false;
        }
    }

    function InformeUnicoAnualUpdCab(&$retvs)
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['cboanio'],
            $_POST['fini'],
            $_POST['ffin'],
            $_POST['t55_periodo'],
            $this->ConvertDate($_POST['t55_fch_pre']),
            $_POST['t55_estado']
        );

        return $this->InformeUnicoAnualCabUpdate($params, $retvs);
    }
    // / <summary>
    // / Elimina la Cabecera del Informe Unico Anual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeUnicoAnualEliminarCab()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t55_anio']
        );
        $SP = "sp_del_inf_unico_anual_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    // / <summary>
    // / Listado de los Informes Unicos anuales
    // / </summary>
    // / <returns>Array Asociativo</returns>
    function InformeUnicoAnualListado($idProy)
    {
        $SP = "sp_sel_inf_unico_anual";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Guardar el comentario del Monitor del avance presupuestal
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeUA_Guardar_ComentariosPresup()
    {
        if ($_POST['t55_num'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }

        $arrayCodSub = $_POST['txt_cod_sub'];
        $arrayComSub = $_POST['txtcoment_presup'];

        $numrows = 0;
        for ($x = 0; $x < count($arrayCodSub); $x ++) {
            $arrCodi = explode(".", $arrayCodSub[$x]);
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t55_num'],
                $arrCodi[0],
                $arrCodi[1],
                $arrCodi[2],
                $_POST['idFuente'],
                $arrayComSub[$x],
                $this->Session->UserID
            );

            $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_unico_anual_coment_presup", $params);
            $numrows += $ret['numrows'] == - 1 ? 0 : $ret['numrows'];
        }

        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }

    // / <summary>
    // / Guardar el comentario del Monitor del avance fisico
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeUA_Guardar_ComentariosAFisico()
    {
        if ($_POST['t55_num'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }

        $arrayCodSub = $_POST['txt_cod_sub'];
        $arrayComSub = $_POST['txtcoment_fisico'];

        $numrows = 0;
        for ($x = 0; $x < count($arrayCodSub); $x ++) {
            $arrCodi = explode(".", $arrayCodSub[$x]);
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t55_num'],
                $arrCodi[0],
                $arrCodi[1],
                $arrCodi[2],
                $arrayComSub[$x],
                $this->Session->UserID
            );

            $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_unico_anual_coment_avanc_fisico", $params);
            $numrows += $ret['numrows'] == - 1 ? 0 : $ret['numrows'];
        }

        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }

    // / <summary>
    // / Selecciona los datos del informe Unico anual
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$anio">Año del Informe</param>
    // / <returns>Array asociativo</returns>
    function InformeUnicoAnualSeleccionar($idProy, $anio)
    {
        $SP = "sp_get_inf_unico_anual";
        $params = array(
            $idProy,
            $anio
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    // / <summary>
    // / Retorna listado de los datos del informe Unico anual
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idInforme">Codigo del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function Inf_UA_Seleccionar($idProy, $idInforme)
    {
        $SP = "sp_get_inf_unico_anual_periodo";
        $params = array(
            $idProy,
            $idInforme
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    // / <summary>
    // / Retorna listado de las actividades
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idInforme">Codigo del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function Inf_UA_ListadoActividades($idProy, $idComp, $idNum, $idFte)
    {
        $SP = "sp_sel_inf_unico_anual_financ_act";
        $params = array(
            $idProy,
            $idComp,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Retorna listado de las actividades para avance físico
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idInforme">Codigo del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function Inf_UA_ListadoActividades2($idProy, $idComp, $idNum)
    {
        $SP = "sp_sel_inf_unico_anual_financ_act2";
        $params = array(
            $idProy,
            $idComp,
            $idNum
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }
    // / <summary>
    // / Retorna listado de las sub actividades
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idComp">Codigo del Componenete</param>
    // / <param name="$idAct">Codigo de la Actividad</param>
    // / <param name="$idNum">Codigo del Informe</param>
    // / <param name="$idFte">Codigo de la Fuente de Financiamiento</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function Inf_UA_ListadoSubActividades($idProy, $idComp, $idAct, $idNum, $idFte)
    {
        $SP = "sp_sel_inf_unico_anual_financ_subact";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function InformeAnualRevisar()
    {
        $params = array(
            $_POST['idProy'],
            $_POST['id'],
            $this->Session->UserID
        );

        $SP = "sp_rev_infanual";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Retorna listado de las sub actividades para avance fisico
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idComp">Codigo del Componenete</param>
    // / <param name="$idAct">Codigo de la Actividad</param>
    // / <param name="$idNum">Codigo del Informe</param>
    // / <param name="$idFte">Codigo de la Fuente de Financiamiento</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function Inf_UA_ListadoSubActividades2($idProy, $idComp, $idAct, $idNum)
    {
        $SP = "sp_sel_inf_unico_anual_financ_subact2";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idNum
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    // / <summary>
    // / Guarda Los comentarios acerca del analisis de los avances para el informe unico anual
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarAnalisisAvancesIUA()
    {
        $ExisteInfUA = $this->ExecuteFunction('fn_existe_inf_unico_anual_cod', array(
            $_POST['t02_cod_proy'],
            $_POST['t55_id']
        ));

        if (! $ExisteInfUA) {
            $this->Error = "Primero debe Guardar la Caratula del Informe...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t55_id'],
            $_POST['t55_avance_comp'],
            $_POST['t55_avance_cap'],
            $_POST['t55_avance_fin'],
            isset($_POST['t55_mt']) ? $_POST['t55_mt'] : '0',
            isset($_POST['t55_mf']) ? $_POST['t55_mf'] : '0',
            $_POST['t55_estado'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_unico_anual_analisis_avanc", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // / <summary>
    // / Guarda Informacion Adicional sobre el Informe unico anual.
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function GuardarInfAdicionalUA()
    {
        $ExisteInfME = $this->ExecuteFunction('fn_existe_inf_unico_anual_cod', array(
            $_POST['t02_cod_proy'],
            $_POST['t55_id']
        ));
        if (! $ExisteInfME) {
            $this->Error = "Primero debe Guardar la Caratula del Informe ...";
            return false;
        }

        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t55_id'],
            $_POST['t55_eva1'],
            $_POST['t55_eva2'],
            $_POST['t55_eva3'],
            $_POST['t55_eva4'],
            $_POST['t55_eva5'],
            $_POST['t55_eva6'],
            $_POST['t55_eva7'],
            $_POST['t55_eva1_obs'],
            $_POST['t55_eva2_obs'],
            $_POST['t55_eva3_obs'],
            $_POST['t55_eva4_obs'],
            $_POST['t55_eva5_obs'],
            $_POST['t55_eva6_obs'],
            $_POST['t55_eva7_obs'],
            $_POST['t55_logros'],
            $_POST['t55_dificul'],
            $_POST['t55_reco_proy'],
            $_POST['t55_reco_fe'],
            $_POST['t55_califica'],
            $_POST['t55_cmt'],
            $_POST['t55_cmf'],
            $_POST['t55_estado'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_unico_anual_adicional", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    // / <summary>
    // / Retorna los Indicadores de Componente, Planeados para el informe unico anual
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$comp">Codigo del Componente</param>
    // / <param name="$anio">Año del Informe</param>
    // / <param name="$mes">Mes del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaIndicadoresComponenteIUA($idProy, $comp, $anio)
    {
        $SP = "sp_sel_inf_unico_anual_prg_vs_avance_comp";
        $params = array(
            $idProy,
            $comp,
            $anio
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    // / <summary>
    // / Retorna listado de Subactividades para el informe unico anual
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <param name="$idActiv">Codigo de Componente y Actividad ejemplo (1.1) </param>
    // / <param name="$anio">Año del Informe</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaSubActividadesIUA($idProy, $idActiv, $idAnio, $idInf)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $SP = "sp_sel_inf_unico_anual_sub_act_crit";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idAnio,
            $idInf
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function RepCalificacionIUA($proy, $idAnio)
    {
        $SP = "sp_inf_unico_anual_calificacion";
        $params = array(
            $proy,
            $idAnio
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    /**
     * Obtiene la lista de Entregables de un Proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array    $_POST()    Datos de la Característica
     * @return  boolean
     *
     */
    function listarEntregables($idProy, $idVersion, $anio)
    {
        $sql = "SELECT CONCAT(t02_anio, '-', t02_mes) AS codigo, fn_numero_entregable('".$idProy."', ".$idVersion.", ".$anio.", t02_mes) AS descripcion ".
                "FROM t02_entregable ".
                "WHERE t02_cod_proy = '".$idProy."' ".
                "AND t02_version  = ".$idVersion." ".
                "AND t02_anio = ".$anio." ".
                "ORDER BY t02_mes";

        //echo("sql: ".$sql."<br/>");

        return $this->ExecuteQuery($sql);
    }

    /**
     * Obtiene el último Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function getInformeEntregableUltimo($idProy, $idVersion)
    {
        $sql = "SELECT ".
                    "t02_mes AS entregable, ".
                    "t02_anio AS anio, ".
                    "fn_nom_periodo_entregable('".$idProy."', '".$idVersion."', t02_anio, t02_mes) AS periodo, ".
                    "DATE_FORMAT(NOW(),'%d/%m/%Y') AS fch_pre, ".
                    "45 AS estado ".
                    "FROM t02_entregable ".
                "WHERE t02_cod_proy = '".$idProy."' ".
                "AND t02_version = ".$idVersion." ".
                "AND CONCAT(t02_anio, '-', t02_mes) NOT IN (SELECT CONCAT(i.t25_anio, '-', i.t25_entregable) FROM t25_inf_entregable i WHERE i.t02_cod_proy = '".$idProy."')
                ORDER BY t02_anio, t02_mes
                LIMIT 1 ";

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    /**
     * Registra la carátula del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function guardarCaratulaInfEntregable()
    {
        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['periodo'],
                        $this->ConvertDate($_POST['fchPresentacion']),
                        $_POST['estadoInf'],
                        $this->Session->UserID,
                        $_POST['obs_gp'],
        				$_POST['intro_gp']);

        $ret = $this->ExecuteProcedureEscalar("sp_ins_inf_entregable_caratula", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    /**
     * Actualiza la carátula del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function actualizarCaratulaInfEntregable()
    {
        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['periodo'],
                        $this->ConvertDate($_POST['fchPresentacion']),
                        $_POST['estadoInf'],
                        $this->Session->UserID,
                        $_POST['obs_gp'],
        				$_POST['intro_gp'],        		
                        $_POST['vb_se']);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_cab", $params);
        if ($ret['numrows'] >= 0) {
            return true;
        } else {
            $retvs = $ret['msg'];
            return false;
        }
    }

    /**
     * Lista los Informes de Entregable del Proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function listarInformesEntregable($idProy, $idVersion)
    {
        $params = array($idProy, $idVersion);
        $ret = $this->ExecuteProcedureReader("sp_sel_inf_entregable", $params);
        return $ret;
    }

    /**
     * Lista los Informes de Entregable sin VB del Proyecto
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function listarInformesEntregableSinVB()
    {
        return $this->ExecuteProcedureReader("sp_sel_inf_entregable_vb", array($this->Session->UserID));
    }

    /**
     * Obtiene el último Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function getInformeEntregable($idProy, $idVersion, $idAnio, $idEntregable)
    {
        $sql = "SELECT ".
                    "DATE_FORMAT(t25_fch_pre,'%d/%m/%Y') AS fch_pre, ".
                    "t25_periodo AS periodo, ".
                    "t25_estado AS estado, ".
                    "t25_resulta AS resultado, ".
                    "t25_conclu AS conclusiones, ".
                    "obs_gp, intro_gp, vb_se ".
                "FROM  t25_inf_entregable ".
                "WHERE t02_cod_proy = '".$idProy."' ".
                "AND t25_anio = ".$idAnio." ".
                "AND t25_entregable = ".$idEntregable;

        //echo("sql: ".$sql."<br/>");

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    /**
     * Verifica si existe Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function existeInformeEntregable($idProy, $idVersion, $idAnio, $idEntregable)
    {
        $sql = "SELECT COUNT(*) AS num_informes ".
                "FROM  t25_inf_entregable ".
                "WHERE t02_cod_proy = '".$idProy."' ".
                "AND t25_anio = ".$idAnio." ".
                "AND t25_entregable = ".$idEntregable;

        $res = mysql_fetch_assoc($this->ExecuteQuery($sql));

        if ($res['num_informes'] == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Registra los Indicadores de Propósito del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @return  array
     *
     */
    function guardarIndicadoresPropositoEntregable()
    {
        $indic = $_POST['t07_cod_prop_ind'];
        $metas = $_POST['txtIndPropEntregable'];
        $descr = $_POST['txtIndPropdes'];
        $logro = $_POST['txtIndProplog'];
        $dific = $_POST['txtIndPropdif'];
        $observaciones = $_POST['txtIndPropObs'];

        $countRet = 0;

        if (is_array($indic)) {
            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];
                $obs = $observaciones[$ax];

                $params = array(
                                $_POST['idProy'],
                                $_POST['idVersion'],
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $Meta,
                                $des,
                                $log,
                                $dif,
                                $obs,
                                $this->Session->UserID
                );
                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_ind_prop", $params);
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

    /**
     * Lista los Indicadores de Propósito del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int     $idVersion Id de la Versión
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @return  array
     *
     */
    function listarIndicadoresPropositoEntregable($idProy, $idVersion, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_entregable_ind_prop", $params);;
    }

    /**
     * Lista los Indicadores de Componente del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int     $idVersion Id de la Versión
     * @param   string  $idComp Id del Componente
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @return  array
     *
     */
    function listarIndicadoresComponenteEntregable($idProy, $idVersion, $idComp, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_entregable_ind_comp", $params);
    }

    /**
     * Registra los Indicadores de Componente del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @return  array
     *
     */
    function guardarIndicadoresComponenteEntregable()
    {

        $indic = $_POST['t08_cod_comp_ind'];
        $metas = $_POST['txtIndCompEntregable'];
        $descr = $_POST['txtIndCompdes'];
        $logro = $_POST['txtIndComplog'];
        $dific = $_POST['txtIndCompdif'];
        $obs = $_POST['txtIndCompobs'];

        $countRet = 0;

        if (is_array($indic)) {
            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $ob = $obs[$ax];
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];

                $params = array(
                                $_POST['idProy'],
                                $_POST['idVersion'],
                                $_POST['idComp'],
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $Meta,
                                $des,
                                $log,
                                $dif,
                                $ob,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_ind_comp_obs", $params);
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

    /**
     * Lista los Indicadores de Producto del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int     $idVersion Id de la Versión
     * @param   string  $idProd Id del Producto
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @return  array
     *
     */
    function listarIndicadoresProductoEntregable($idProy, $idVersion, $idComp, $idProd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $idProd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_ind_prod_entregable", $params);
    }

    /**
     * Registra los Indicadores de Producto del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @return  array
     *
     */
    function guardarIndicadoresProductoEntregable()
    {
        $indic = $_POST['listIdProd'];
        $metas = $_POST['txtIndProdEntregable'];
        $descr = $_POST['txtIndProddes'];
        $logro = $_POST['txtIndProdlog'];
        $dific = $_POST['txtIndProddif'];
        $observaciones = $_POST['txtIndProdobs'];
        $countRet = 0;

        if (is_array($indic)) {
            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $Meta = $metas[$ax];
                if ($Meta == "") {
                    $Meta = 0;
                }
                $des = $descr[$ax];
                $log = $logro[$ax];
                $dif = $dific[$ax];
                $obs = $observaciones[$ax];

                $params = array($_POST['idProy'],
                                $_POST['idVersion'],
                                $_POST['idComp'],
                                $_POST['idProd'],
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $Meta,
                                $des,
                                $log,
                                $dif,
                                $obs,
                                $this->Session->UserID);

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_ind_producto", $params);
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

    /**
     * Registrar el Avance del Plan de Capacitación
     * del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array  $_POST
     * @return  array
     *
     */
    function guardarAvancePlanCapacEntregable()
    {
        $partes = explode("-", $_POST['idEntregable']);
        $idEntregable = $partes[1];

        $arrtemas = $_POST['txtcodtemas'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);

        $countRet = 0;
        if (is_array($arrtemas)) {
            for ($ax = 0; $ax < count($arrtemas); $ax ++) {

                $name_tema = "txt_" . str_replace(".", "_", $arrtemas[$ax]);
                $arrvalues = $_POST[$name_tema];
                $strvalues = strtoupper(implode("|", $arrvalues));

                $arrcodi = explode(".", $arrtemas[$ax]);

                $params = array(
                                $_POST['idProy'],
                                $_POST['idVersion'],
                                $_POST['anio'],
                                $idEntregable,
                                $arrcodi[0],
                                $arrcodi[1],
                                $arrcodi[2],
                                $arrcodi[3],
                                $strbenef,
                                $strvalues,
                                $this->Session->UserID
                );
                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_capac", $params);
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

    /**
     * Lista el Plan de Capacitación del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int     $idVersion Id de la Versión
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @param   string  $dpto Id del Departamento
     * @param   string  $prov Id de la Provincia
     * @param   string  $dist Id del Distrito
     * @param   string  $case Id del Caserio
     * @return  array
     *
     */
    function listarPlanCapacInfEntregable($idProy, $idVersion, $anio, $idEntregable, $dpto, $prov, $dist, $case)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable,
                        $dpto,
                        $prov,
                        $dist,
                        $case);

        return $this->ExecuteProcedureReader("sp_lis_inf_entregable_capac", $params);
    }

    /**
     * Lista el Plan de Asistencia Técnica del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int     $idVersion Id de la Versión
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @param   string  $dpto Id del Departamento
     * @param   string  $prov Id de la Provincia
     * @param   string  $dist Id del Distrito
     * @param   string  $case Id del Caserio
     * @return  array
     *
     */
    function listarPlanATInfEntregable($idProy, $idVersion, $anio, $idEntregable, $dpto, $prov, $dist, $case)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable,
                        $dpto,
                        $prov,
                        $dist,
                        $case);

        return $this->ExecuteProcedureReader("sp_lis_inf_entregable_at", $params);
    }

    /**
     * Lista el Plan Otros Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int     $idVersion Id de la Versión
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @param   string  $dpto Id del Departamento
     * @param   string  $prov Id de la Provincia
     * @param   string  $dist Id del Distrito
     * @param   string  $case Id del Caserio
     * @return  array
     *
     */
    function listarPlanOtrosInfEntregable($idProy, $idVersion, $anio, $idEntregable, $dpto, $prov, $dist, $case)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable,
                        $dpto,
                        $prov,
                        $dist,
                        $case);

        return $this->ExecuteProcedureReader("sp_lis_inf_entregable_otros", $params);
    }

    /**
     * Registrar el Avance del Plan de Asistencia Técnica
     * del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array  $_POST
     * @return  array
     *
     */
    function guardarAvancePlanATEntregable()
    {
        $partes = explode("-", $_POST['idEntregable']);
        $idEntregable = $partes[1];

        $arrsub = $_POST['txtcodsub'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);

        $countRet = 0;
        if (is_array($arrsub)) {
            for ($ax = 0; $ax < count($arrsub); $ax ++) {

                $name_sub = "txt_" . str_replace(".", "_", $arrsub[$ax]);
                $arrvalues = $_POST[$name_sub];
                $strvalues = strtoupper(implode("|", $arrvalues));

                $arrcodi = explode(".", $arrsub[$ax]);

                $params = array(
                                $_POST['idProy'],
                                $_POST['idVersion'],
                                $_POST['anio'],
                                $idEntregable,
                                $arrcodi[0],
                                $arrcodi[1],
                                $arrcodi[2],
                                $strbenef,
                                $strvalues,
                                $this->Session->UserID
                );
                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_at", $params);
                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet > 0) {
            return true;
        } else {
            $this->Error .= "\n" . "No hay datos para actualizar";
            return false;
        }
    }

    /**
     * Registrar el Avance del Plan de Otros Servicios
     * del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   array  $_POST
     * @return  array
     *
     */
    function guardarAvancePlanOtrosEntregable()
    {
        $partes = explode("-", $_POST['idEntregable']);
        $idEntregable = $partes[1];

        $arrsub = $_POST['txtcodsub'];
        $arrbenef = $_POST['txtbenef'];
        $strbenef = implode("|", $arrbenef);

        $countRet = 0;
        if (is_array($arrsub)) {

            for ($ax = 0; $ax < count($arrsub); $ax ++) {
                $name_sub = "txt_" . str_replace(".", "_", $arrsub[$ax]);
                $arrvalues = $_POST[$name_sub];
                $strvalues = strtoupper(implode("|", $arrvalues));

                $name_sub = "val_" . str_replace(".", "_", $arrsub[$ax]);
                $arrconts = $_POST[$name_sub];
                $strcontenidos = strtoupper(implode("|", $arrconts));

                $arrcodi = explode(".", $arrsub[$ax]);

                $params = array(
                                $_POST['idProy'],
                                $_POST['idVersion'],
                                $_POST['anio'],
                                $idEntregable,
                                $arrcodi[0],
                                $arrcodi[1],
                                $arrcodi[2],
                                $strbenef,
                                $strvalues,
                                $strcontenidos,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_otros", $params);

                if ($ret['numrows'] > 0) {
                    $countRet += $ret['numrows'];
                }
            }
        }

        if ($countRet > 0) {
            return true;
        } else {
            $this->Error .= "\n" . "No hay datos para actualizar";
            return false;
        }
    }

    /**
     * Lista los Información adicional del Informe de Entregable
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   string  $idProd Id del Producto
     * @param   string  $anio Año del Informe
     * @param   string  $idEntregable Id del Entregable
     * @param   string  $idVersion Id de la Version
     * @return  array
     *
     */
    function listarAnalisisInfEntregable($idProy, $idVersion, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable);		
        $ret = $this->ExecuteProcedureReader("sp_sel_inf_entregable_analisis", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function guardarAnalisisEntregable()
    {
        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['t25_resulta'],
                        $_POST['t25_conclu'],
                        $_POST['t25_limita'],
                        $_POST['t25_fac_pos'],
                        $_POST['t25_perspec'],
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_analisis", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function infEntregableEliminarCab()
    {
        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable']);

        $ret = $this->ExecuteProcedureEscalar("sp_del_inf_entregable", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function listarAnexosInfEntregable($idProy, $idVersion, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_anx_inf_entregable", $params);
    }

    function guardarAnexoInfEntregable()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_entregable/';

        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $NomFoto,
                        $_POST['t25_desc_file'],
                        $ext,
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_ins_inf_anx_inf_entregable", $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);

            return true;
        } else {
            return false;
        }
    }

    function eliminarAnexoInfEntregable()
    {
        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['idAnexo']);

        $ret = $this->ExecuteProcedureEscalar("sp_del_anx_inf_entregable", $params);
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_entregable/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function enviarInfEntregable()
    {
        $params = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['estado'],
                        $_POST['obs'],
        				$_POST['intro']);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_entregable_cambio_estado", $params);

        /*
        $paramsf = array($_POST['idProy'],
                        $_POST['idVersion'],
                        $_POST['idAnio'],
                        ($_POST['idTrim'] * 3),
                        $_POST['estado']);

        $retf = $this->ExecuteProcedureEscalar("sp_upd_inf_mes_financ_cam_est", $paramsf);*/
        /*
         * print_r($this); exit();
        */
        //if ($ret['numrows'] >= 0 || $retf['numrows'] >= 0) {
        if ($ret['numrows'] >= 0) {
            /*$objFunc = new Functions();
            $ret = $this->ExecuteProcedureReader("sp_sel_mensajes", NULL);

            $id = $objFunc->enviar_mail($ret);
            $strMensajes = implode(",", $id);
            $retA = $this->ExecuteProcedureEscalar("sp_upd_mensajes", $strMensajes);*/

            return true;
        } else {
            return false;
        }
    }

    function countInfBenef()
    {
        $idProy = $_GET['idProy'];
        $idEntregable = $_GET['idEntregable'];
        $anio = $_GET['anio'];
        $idVersion = $_GET['idVersion'];

        $aQuery = "SELECT COUNT(*) AS total_benef FROM t25_inf_entregable_capac
                    WHERE t02_cod_proy = '$idProy' AND t02_version = $idVersion AND t25_anio = $anio AND t25_entregable = $idEntregable
                    UNION
                    SELECT COUNT(*) AS total_benef FROM t25_inf_entregable_at
                    WHERE t02_cod_proy = '$idProy' AND t02_version = $idVersion AND t25_anio = $anio AND t25_entregable = $idEntregable
                    UNION
                    SELECT COUNT(*) AS total_benef FROM t25_inf_entregable_cred
                    WHERE t02_cod_proy = '$idProy' AND t02_version = $idVersion AND t25_anio = $anio AND t25_entregable = $idEntregable
                    UNION
                    SELECT COUNT(*) AS total_benef FROM t25_inf_entregable_otros
                    WHERE t02_cod_proy = '$idProy' AND t02_version = $idVersion AND t25_anio = $anio AND t25_entregable = $idEntregable";

        $aResult = $this->ExecuteQuery($aQuery);
        $aRow = mysql_fetch_assoc($aResult);
        mysql_free_result($aResult);
        return $aRow;
    }

    /*
     * Métodos de Informe de Supervisión Externa
     */
    function listarInfSE($idProy, $idVersion)
    {
        $params = array($idProy, $idVersion);
        return $this->ExecuteProcedureReader("sp_sel_inf_se", $params);
    }

    function listarInformesSupSinVB()
    {
        return $this->ExecuteProcedureReader("sp_sel_inf_se_vb", array($this->Session->UserID));
    }

    function getInfSE($idProy, $anio, $idEntregable)
    {
        $params = array($idProy, $anio, $idEntregable);

        $ret = $this->ExecuteProcedureReader("sp_get_inf_se", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function existeInfSE($idProy, $anio, $idEntregable)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_se', array($idProy, $anio, $idEntregable));
        if (! $ExisteInf) {
            return false;
        }
        return true;
    }
    
    /**
     * Obtiene el siguiente Informe de Supervisión Externa
     * a Registrar
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @return  array
     *
     */
    function getSiguienteInfSE($idProy, $idVersion)
    {
        $sql = "SELECT ".
                "t02_mes AS entregable, ".
                "t02_anio AS anio, ".
                "fn_nom_periodo_entregable('".$idProy."', '".$idVersion."', t02_anio, t02_mes) AS periodo, ".
                "DATE_FORMAT(NOW(),'%d/%m/%Y') AS fch_pre, ".
                "280 AS estado ".
                "FROM t02_entregable ".
                "WHERE t02_cod_proy = '".$idProy."' ".
                "AND t02_version = '".$idVersion."' ".
                "AND CONCAT(t02_anio, '-', t02_mes) NOT IN (SELECT CONCAT(i.t02_anio, '-', i.t02_entregable) FROM t30_inf_se i WHERE i.t02_cod_proy = '".$idProy."') ".
                "ORDER BY t02_anio, t02_mes ".
                "LIMIT 1 ";

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function guardarCabInfSE()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['periodo'],
                        $this->ConvertDate($_POST['fchPresentacion']),
                        $_POST['estadoInf'],
                        $this->ConvertDate($_POST['iniVisita']),
                        $this->ConvertDate($_POST['terVisita']),
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_ins_inf_se_cab", $params);

        if ($ret['codigo'] == 4) {
            $this->Error = "La solicitud de la visita aun no ha sido Aprobada";
            return false;
        }
        if ($ret['codigo'] == 5) {
            $this->Error = "Aún no se solicitó fecha para la visita";
            return false;
        }
        if ($ret['codigo'] == 3) {
            $this->Error = "Existe otro Informe de Supervisión en proceso";
            return false;
        }
        if ($ret['codigo'] == 2) {
            $this->Error = "Ya fue registrado el Informe de Supervisión del Entregable";
            return false;
        }

        if ($ret['numrows'] > 0 && $ret['codigo'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    function eliminarInfSE()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable']);

        $ret = $this->ExecuteProcedureEscalar("sp_del_inf_se", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function actualizarCabInfSE()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['periodo'],
                        $this->ConvertDate($_POST['fchPresentacion']),
                        $_POST['estadoInf'],
                        $_POST['intro'],
                        $_POST['fuentes'],
                        $this->ConvertDate($_POST['iniVisita']),
                        $this->ConvertDate($_POST['terVisita']),
                        $_POST['obsGP'],
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_cab", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function listarIndicadoresComponenteSE($idProy, $idVersion, $idComp, $anio, $idEntregable)
    {
        $params = array($idProy, $idVersion, $idComp, $anio, $idEntregable);
        return $this->ExecuteProcedureReader("sp_sel_inf_se_ind_comp", $params);
    }

    function guardarIndicadoresComponenteSE()
    {
        $indic = $_POST['indComp'];
        $observaciones = $_POST['obsComp'];
        $avances = $_POST['avancesComp'];

        $countRet = 0;
        if (is_array($indic)) {
            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $obs = $observaciones[$ax];
                $av = $avances[$ax];

                $params = array($_POST['idProy'],
                                $_POST['cbocomponente_ind'],
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $obs,
                                $av,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_ind_comp", $params);
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

    function guardarIndicadoresProdSE()
    {
        $prod = explode(".", $_POST['cboprod_ind']);
        $idComp = $prod[0];
        $idProd = $prod[1];

        $indic = $_POST['prodInd'];
        $observaciones = $_POST['observaciones'];
        $participabenef = $_POST['participabenef'];
        $avancefisicopres = $_POST['avancefisicopres'];
        $avances = $_POST['avances'];

        $observacionesCar = $_POST['observacionesCar'];
        $avancesCar = $_POST['avancesCar'];

        $countRet = 0;

        if (is_array($indic)) {
            $total = count($indic);
            for ($ax = 0; $ax < $total; $ax ++) {
                $idInd = $indic[$ax];
                $obs = $observaciones[$ax];
                $partbenef = $participabenef[$ax];
                $avancefispres = $avancefisicopres[$ax];
                $av = $avances[$ax];

                $listaAvancesCar = $avancesCar[$idInd];
                $listaObsCar = $observacionesCar[$idInd];
                
                foreach ($listaAvancesCar as $k=>$v){
                    $obsCar = $listaObsCar[$k];
                    $avCar = $listaAvancesCar[$k];

                    $paramsCar = array($_POST['idProy'],
                                    $idComp,
                                    $idProd,
                                    $idInd,
                                    $k,
                                    $_POST['anio'],
                                    $_POST['idEntregable'],
                                    $obsCar,
                    				$avCar,
                                    $this->Session->UserID
                    );

                    $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_ind_car_prod", $paramsCar);

                    if ($ret['numrows'] > 0) {
                        $countRet += $ret['numrows'];
                    }
                }

                $paramsInd = array($_POST['idProy'],
                                $idComp,
                                $idProd,
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $obs,
                				$partbenef,
                				$avancefispres,
                                $av,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_ind_prod", $paramsInd);

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

    function listarIndicadoresProdSE($idProy, $idVersion, $idComp, $idProd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $idProd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_ind_prod_se", $params);
    }

    function listarCaracteristicasIndProdSE($idProy, $idVersion, $idComp, $idProd, $idInd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $idProd,
                        $idInd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_car_ind_prod_se", $params);
    }

    function guardarActSE()
    {
        $listaIdAct = $_POST['listaIdAct'];
        $observaciones = $_POST['obsAct'];

        $countRet = 0;
        if (is_array($listaIdAct)) {
            for ($ax = 0; $ax < count($listaIdAct); $ax ++) {
                $idAct = $listaIdAct[$ax];
                $obs = $observaciones[$ax];

                $params = array($_POST['idProy'],
                                $_POST['idComp'],
                                $_POST['idProd'],
                                $idAct,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $obs,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_act", $params);
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

    function listarActSE($idProy, $idComp, $idProd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idComp,
                        $idProd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_act_se", $params);
    }

    /**    
    * Guarda las observaciones del  Avance Financiero.    
    *    
    * @author DA    
    * @since Version 2.1    
    * @access public    
    * @return boolean    
    *    
    */
    public function guardarAvanceFinancieroSE()
    {
        $observaciones = $_POST['obs'];

        $sql = "REPLACE INTO t30_inf_se_observacion (t02_cod_proy, t02_anio, t02_entregable, t01_id_inst, t30_obs, ".
                        "usr_crea, fch_crea, usr_actu, fch_actu, est_audi) VALUES ";

        foreach ($observaciones as $key=>$val){
             foreach ($val as $obs=>$valor){
                if(!empty($valor)){
                    $sql .= " ('".$_POST['idProy']."', ".$_POST['anio'].", ".$_POST['idEntregable'].", ".$key.
                    ", '".$valor."', '".$this->Session->UserID."', '".$this->fecha."', NULL, NULL, '1'),";
                }
             }
        }

        $this->ExecuteQuery(trim($sql, ',') . ";");

        return true;
    }
    

    function guardarConclusionesSE()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['avance'],
                        $_POST['logros'],
                        $_POST['dificul'],
                        $_POST['recoProy'],
                        $_POST['recoFE'],
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_conc", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function guardarCalificacionesSE()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['t30_crit_eva1'],
                        $_POST['t30_crit_eva2'],
                        $_POST['t30_crit_eva3'],
                        $_POST['t30_crit_eva4'],
        				$_POST['t30_crit_final'],
                        $_POST['t30_califica'],
                        $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_se_calif", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function guardarAnexosSE()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_me/';

        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $NomFoto,
                        $_POST['t30_desc_file'],
                        $ext,
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_ins_inf_se_anexos", $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);
            return true;
        } else {
            return false;
        }
    }

    function listarAnexosInfSE($idProy, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_se_anexos", $params);
    }

    function eliminarAnexoSE()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['idAnx']);

        $ret = $this->ExecuteProcedureEscalar("sp_del_inf_se_anexo", $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_me/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    /*
     * Métodos de Informe de Supervisión Interna
    */
    function listarInfSI($idProy, $idVersion)
    {
        $params = array($idProy, $idVersion);
        return $this->ExecuteProcedureReader("sp_sel_inf_si", $params);
    }

    /**
     * Obtiene el siguiente Informe de Supervisión Interna
     * a Registrar
     *
     * @author  AQ
     * @since   Version 2.0
     * @access  public
     * @param   string  $idProy Id del Proyecto
     * @param   int  $idVersion Versión del Proyecto
     * @return  array
     *
     */
    function getSiguienteInfSI($idProy, $idVersion)
    {
        $sql = "SELECT ".
                        "t02_mes AS entregable, ".
                        "t02_anio AS anio, ".
                        "fn_nom_periodo_entregable('".$idProy."', '".$idVersion."', t02_anio, t02_mes) AS periodo, ".
                        "DATE_FORMAT(NOW(),'%d/%m/%Y') AS fch_pre, ".
                        "280 AS estado ".
                        "FROM t02_entregable ".
                        "WHERE t02_cod_proy = '".$idProy."' ".
                        "AND t02_version = '".$idVersion."' ".
                        "AND CONCAT(t02_anio, '-', t02_mes) NOT IN (SELECT CONCAT(i.t02_anio, '-', i.t02_entregable) FROM t45_inf_si i WHERE i.t02_cod_proy = '".$idProy."') ".
                        "ORDER BY t02_anio, t02_mes ".
                        "LIMIT 1 ";

        return mysql_fetch_assoc($this->ExecuteQuery($sql));
    }

    function getInfSI($idProy, $anio, $idEntregable)
    {
        $params = array($idProy, $anio, $idEntregable);

        $ret = $this->ExecuteProcedureReader("sp_get_inf_si", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function existeInfSI($idProy, $anio, $idEntregable)
    {
        $ExisteInf = $this->ExecuteFunction('fn_existe_inf_si', array($idProy, $anio, $idEntregable));
        if (! $ExisteInf) {
            return false;
        }
        return true;
    }

    function guardarCabInfSI()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['periodo'],
                        $this->ConvertDate($_POST['fchPresentacion']),
                        $_POST['estadoInf'],
                        $this->ConvertDate($_POST['iniVisita']),
                        $this->ConvertDate($_POST['terVisita']),
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_ins_inf_si_cab", $params);

        if ($ret['codigo'] == 4) {
            $this->Error = "La solicitud de la visita aun no ha sido Aprobada";
            return false;
        }
        if ($ret['codigo'] == 5) {
            $this->Error = "Aún no se solicitó fecha para la visita";
            return false;
        }
        if ($ret['codigo'] == 3) {
            $this->Error = "Existe otro Informe de Supervisión en proceso";
            return false;
        }
        if ($ret['codigo'] == 2) {
            $this->Error = "Ya fue registrado el Informe de Supervisión del Entregable";
            return false;
        }

        if ($ret['numrows'] > 0 && $ret['codigo'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    function eliminarInfSI()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable']);

        $ret = $this->ExecuteProcedureEscalar("sp_del_inf_si", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function actualizarCabInfSI()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['periodo'],
                        $this->ConvertDate($_POST['fchPresentacion']),
                        $_POST['estadoInf'],
                        $_POST['intro'],
                        $_POST['fuentes'],
                        $this->ConvertDate($_POST['iniVisita']),
                        $this->ConvertDate($_POST['terVisita']),
                        $_POST['obsGP'],
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_cab", $params);

        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function listarIndicadoresComponenteSI($idProy, $idVersion, $idComp, $anio, $idEntregable)
    {
        $params = array($idProy, $idVersion, $idComp, $anio, $idEntregable);
        return $this->ExecuteProcedureReader("sp_sel_inf_si_ind_comp", $params);
    }

    function guardarIndicadoresComponenteSI()
    {
        $indic = $_POST['indComp'];
        $observaciones = $_POST['obsComp'];
        $avances = $_POST['avancesComp'];

        $countRet = 0;
        if (is_array($indic)) {
            for ($ax = 0; $ax < count($indic); $ax ++) {
                $idInd = $indic[$ax];
                $obs = $observaciones[$ax];
                $av = $avances[$ax];

                $params = array($_POST['idProy'],
                                $_POST['cbocomponente_ind'],
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $obs,
                                $av,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_ind_comp", $params);
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

    function guardarIndicadoresProdSI()
    {
        $prod = explode(".", $_POST['cboprod_ind']);
        $idComp = $prod[0];
        $idProd = $prod[1];

        $indic = $_POST['prodInd'];
        $observaciones = $_POST['observaciones'];
        $avances = $_POST['avances'];

        $observacionesCar = $_POST['observacionesCar'];
        $avancesCar = $_POST['avancesCar'];

        $countRet = 0;

        if (is_array($indic)) {
            $total = count($indic);
            for ($ax = 0; $ax < $total; $ax ++) {
                $idInd = $indic[$ax];
                $obs = $observaciones[$ax];
                $av = $avances[$ax];

                $listaAvancesCar = $avancesCar[$idInd];
                $listaObsCar = $observacionesCar[$idInd];

                foreach ($listaAvancesCar as $k=>$v){
                    $obsCar = $listaObsCar[$k];
                    $avCar = $listaAvancesCar[$k];

                    $params = array($_POST['idProy'],
                                    $idComp,
                                    $idProd,
                                    $idInd,
                                    $k,
                                    $_POST['anio'],
                                    $_POST['idEntregable'],
                                    $obsCar,
                                    $avCar,
                                    $this->Session->UserID
                    );

                    $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_ind_car_prod", $params);

                    if ($ret['numrows'] > 0) {
                        $countRet += $ret['numrows'];
                    }
                }

                $params = array($_POST['idProy'],
                                $idComp,
                                $idProd,
                                $idInd,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $obs,
                                $av,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_ind_prod", $params);

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

    function listarIndicadoresProdSI($idProy, $idVersion, $idComp, $idProd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $idProd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_ind_prod_si", $params);
    }

    function listarCaracteristicasIndProdSI($idProy, $idVersion, $idComp, $idProd, $idInd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $idProd,
                        $idInd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_car_ind_prod_si", $params);
    }

    function guardarActSI()
    {
        $listaIdAct = $_POST['listaIdAct'];
        $observaciones = $_POST['obsAct'];

        $countRet = 0;
        if (is_array($listaIdAct)) {
            for ($ax = 0; $ax < count($listaIdAct); $ax ++) {
                $idAct = $listaIdAct[$ax];
                $obs = $observaciones[$ax];

                $params = array($_POST['idProy'],
                                $_POST['idComp'],
                                $_POST['idProd'],
                                $idAct,
                                $_POST['anio'],
                                $_POST['idEntregable'],
                                $obs,
                                $this->Session->UserID
                );

                $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_act", $params);
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

    function listarActSI($idProy, $idVersion, $idComp, $idProd, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $idComp,
                        $idProd,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_act_si", $params);
    }

    function guardarConclusionesSI()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['avance'],
                        $_POST['logros'],
                        $_POST['dificul'],
                        $_POST['recoProy'],
                        $_POST['recoFE'],
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_conc", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function guardarCalificacionesSI()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['t45_crit_eva1'],
                        $_POST['t45_crit_eva2'],
                        $_POST['t45_crit_eva3'],
                        $_POST['t45_crit_eva4'],
                        $_POST['t45_crit_eva5'],
                        $_POST['t45_crit_eva6'],
                        $_POST['t45_crit_eva7'],
                        $_POST['t45_califica'],
                        $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_si_calif", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function guardarAnexosSI()
    {
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();

        $objFiles->DirUpload .= 'sme/proyectos/informes/anx_mi/';

        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $NomFoto,
                        $_POST['t45_desc_file'],
                        $ext,
                        $this->Session->UserID);

        $ret = $this->ExecuteProcedureEscalar("sp_ins_inf_si_anexos", $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);
            return true;
        } else {
            return false;
        }
    }

    function listarAnexosInfSI($idProy, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_si_anexos", $params);
    }

    function eliminarAnexoSI()
    {
        $params = array($_POST['idProy'],
                        $_POST['anio'],
                        $_POST['idEntregable'],
                        $_POST['idAnx']);

        $ret = $this->ExecuteProcedureEscalar("sp_del_inf_si_anexo", $params);

        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_mi/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function listarProductosEnEntregable($idProy, $idVersion, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $idVersion,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_prod_en_entregable", $params);
    }

    function listarInfFinSinVB()
    {
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_vb", array($this->Session->UserID));
    }

    function listarInfTecSinVB()
    {
        return $this->ExecuteProcedureReader("sp_sel_inf_mes_vb", array($this->Session->UserID));
    }

    /**
     * Obtiene los datos del Informe Financiero      
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto
     * @param   string  $anio           Anio
     * @param   string  $idEntregable   Id del Entregable
     * @return  array
     *
     */
    public function getInfAvanceFinancieroSE($idProy, $anio, $idEntregable)
    {
        $params = array($idProy,
                        $anio,
                        $idEntregable);

        return $this->ExecuteProcedureReader("sp_sel_inf_supervision_avance", $params);
    }
    
    
    /**
     * Obtiene las fechas Mensuales de cada informe mensual: 
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto     
     * @return  resource
     *
     */
    public function getInfMensDates($idProy)
    {
    	
    	$aQuery = "SELECT t20_anio, t20_mes, t20_fch_pre, t20_periodo   
    	FROM t20_inf_mes
    	WHERE t02_cod_proy = '$idProy'
    	GROUP BY t20_anio, t20_mes";
    	return $this->ExecuteQuery($aQuery);
    
    }    
    
    
    /**
     * Obtiene los valores de meses ejecutados y meses programados.
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto
     * @param   string  $idActiv        Id del Producto
     * @param   string  $anio        	Id de anio 
     * @param   string  $mes         	Id de mes 
     * @return  resource
     *
     */
	public function ListaSubActividadesMeses($idProy, $idActiv, $anio, $mes)
    {
        $actividad = explode(".", $idActiv);
        $idComp = $actividad[0];
        $idAct = $actividad[1];
        $idSubAct = $actividad[2];
        $SP = "sp_sel_inf_sub_act_meses";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
        	$idSubAct,
            $anio,
            $mes
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    
    /**
     * Obtiene los productos por entregables por proyecto, versio e Id del anio.
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto
     * @param   string  $idVersion      Id de la Version
     * @param   string  $idAnio        	Id de anio     
     * @return  resource
     *
     */
    public function listarProductosPorEntregable($idProy, $idVersion, $idAnio)
    {
    	$sql = "SELECT DISTINCT
    	concat(prod.t08_cod_comp,'.',prod.t09_cod_act) as codigo,
    	concat(prod.t08_cod_comp,'.',prod.t09_cod_act, ' ', prod.t09_act ) as producto,
    	prod.t08_cod_comp,
    	prod.t09_cod_act,
    	prod.t09_act,
    	prod.t09_obs,
    	prog.t02_mes
    	FROM t09_act prod
    	JOIN t02_entregable_act_ind prog
    	ON (prod.t02_cod_proy = prog.t02_cod_proy
    	AND prod.t08_cod_comp = prog.t08_cod_comp
    	AND prod.t09_cod_act = prog.t09_cod_act
    	AND prog.t02_anio = $idAnio )
    	WHERE prod.t02_cod_proy = '$idProy'
    	AND prod.t02_version = '$idVersion'
    	ORDER BY prod.t08_cod_comp, prod.t09_cod_act, prog.t02_mes";
    	 
    	return $this->ExecuteQuery($sql);
    }
    
    function getAvanceAlcanzado($idProy, $idVersion, $anio, $idEntregable)
    {
        return $this->ExecuteProcedureReader("sp_inf_entregable_avance_alcanzado", array($idProy, $idVersion, $anio, $idEntregable));
    }

    function getResumenPresupuestal($idProy, $idVersion, $anio)
    {
        return $this->ExecuteProcedureReader("sp_inf_entregable_resumen_presup", array($idProy, $idVersion, $anio));
    }

    /**
     * Obtiene los gastos planeados del Año
     *
     * @author  AQ
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto
     * @param   string  $idVersion      Id de la Version
     * @param   string  $idAnio         Id de anio     
     * @return  resource
     *
     */
    public function getListaGastosPlaneadosMensuales($idProy, $idVersion, $idAnio)
    {
        $hc = new HardCode();

        $sql = "SELECT 
            fn_desembolso_planeado_periodo_fte('$idProy', '$idVersion', idmes, idmes, ".$hc->codigo_Fondoempleo.") AS planeado
        FROM v_meses_anio
        ORDER BY idmes";
         
        return $this->ExecuteQuery($sql);
    }

    /**
     * Obtiene los Adelantos del Año
     *
     * @author  AQ
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto
     * @param   string  $idVersion      Id de la Version
     * @param   string  $idAnio         Id de anio     
     * @return  resource
     *
     */
    public function getListaAdelantosAnio($idProy, $idVersion, $idAnio)
    {
        $hc = new HardCode();

        $sql = 'SELECT  
                    t02_mes AS mes,
                    fn_numero_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS nro_entregable,
                    fn_desembolso_planeado_del_entregable_fte_adelanto(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS adelanto,
                    fn_numero_mes(t02_anio, t02_mes) - fn_mes_entregable_ant(t02_cod_proy, t02_version, t02_anio, t02_mes) AS duracion
                FROM
                    t02_entregable
                WHERE t02_cod_proy = "'.$idProy.'" 
                AND t02_version = "'.$idVersion.'" 
                AND t02_anio = "'.$idAnio.'" 
                ORDER BY t02_anio, t02_mes';

        return $this->ExecuteQuery($sql);
    }

    /**
     * Obtiene los Saldos del Año
     *
     * @author  AQ
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy         Id del Proyecto
     * @param   string  $idVersion      Id de la Version
     * @param   string  $idAnio         Id de anio     
     * @return  resource
     *
     */
    public function getListaSaldosAnio($idProy, $idVersion, $idAnio)
    {
        $hc = new HardCode();

        $sql = 'SELECT  
                    t02_mes AS mes,
                    fn_numero_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS nro_entregable,
                    fn_desembolso_planeado_del_entregable_fte_saldo(t02_cod_proy, t02_version, t02_anio, t02_mes, '.$hc->codigo_Fondoempleo.') AS saldo,
                    fn_numero_mes(t02_anio, t02_mes) - fn_mes_entregable_ant(t02_cod_proy, t02_version, t02_anio, t02_mes) AS duracion
                FROM
                    t02_entregable
                WHERE t02_cod_proy = "'.$idProy.'" 
                AND t02_version = "'.$idVersion.'" 
                AND t02_anio = "'.$idAnio.'" 
                ORDER BY t02_anio, t02_mes';

        return $this->ExecuteQuery($sql);
    }
    
    
    /**
     * Obtiene el plan de visitas del proyecto.
     *
     * @author  DA
     * @since   Version 2.1
     * @access  public
     * @param   string  $idProy       Id del Proyecto
     * @param   string  $version      Id de la Version del Proyecto
     * @return  resource
     *
     */
    public function getListadoVisitasProyecto($idProy, $version)
    {
    	
    	$sqlSup = "SELECT 	
					CONCAT(c.t01_id_inst, '.', c.t01_id_cto) AS codigo,
						'(SE)' AS cargo,
						CONCAT(TRIM(c.t01_ape_pat), ' ', TRIM(c.t01_ape_mat), ', ', TRIM(c.t01_nom_cto)) AS nombres	 
					FROM  t01_inst_cto c  
					WHERE c.t01_cgo_cto = 60 
					AND  c.t01_id_inst NOT IN (SELECT p.t01_id_inst
					FROM  t02_dg_proy p 
					WHERE p.t02_cod_proy = '$idProy'
					  AND p.t02_version = '$version')
					UNION 
					
					SELECT 	
					i.t01_id_inst  AS codigo,
						'(SE)' AS cargo,
						CONCAT(i.t01_sig_inst) AS nombres 	
					FROM  t01_inst_cto c
					INNER JOIN t01_dir_inst i ON (c.t01_id_inst=i.t01_id_inst)
					WHERE c.t01_cgo_cto = 60 
					AND  c.t01_id_inst NOT IN (SELECT p.t01_id_inst
					FROM  t02_dg_proy p 
					WHERE p.t02_cod_proy = '$idProy'
					  AND p.t02_version = '$version')
					
					UNION 
					
					SELECT p.t02_moni_tema AS codigo,
					'(GP)' AS cargo, 
					CONCAT(TRIM(fe.t90_ape_pat),' ', TRIM(fe.t90_ape_mat), ', ', TRIM(fe.t90_nom_equi)) AS nombres
					FROM t02_dg_proy p 
					LEFT JOIN t90_equi_fe fe ON fe.t90_id_equi = p.t02_moni_tema 
					WHERE p.t02_cod_proy = '$idProy' AND p.t02_version = '$version'
					
					group by nombres";
    	
    	
    	
    	$sql = 'SELECT 
		        inf.t02_cod_proy,
		        inf.t25_anio,
		        CONCAT("Año ",inf.t25_anio) AS anio,
		        inf.t25_entregable,
		        fn_numero_entregable("'.$idProy.'", "'.$version.'", inf.t25_anio, inf.t25_entregable) AS entregable,        
		        inf.t25_periodo AS periodo,
				cronv.estado,
				DATE_FORMAT(cronv.fecha_visita_inicio,"%d/%m/%Y") AS fecha_visita_inicio,
				DATE_FORMAT(cronv.fecha_visita_termino,"%d/%m/%Y") AS fecha_visita_termino,		
				cronv.costo_pago_1,
				cronv.costo_pago_2,
		        aux.descrip AS estado_text,
		        DATE_FORMAT(infsup.t30_fch_pre,"%d/%m/%Y") AS fec_pre_inf_sup,
		        supv.cargo AS supervisor_cargo,
		        supv.nombres AS supervisor_nombres
		    FROM t25_inf_entregable inf 
		    LEFT JOIN t46_cron_visita_proy cronv 
				ON (
					inf.t02_cod_proy = cronv.t02_cod_proy AND 
					inf.t25_anio = cronv.t25_anio AND 
					inf.t25_entregable = cronv.t25_entregable 
				)
		    LEFT JOIN adm_tablas_aux aux 
		         ON (aux.idTabla = 39 AND aux.codi = cronv.estado)
		    LEFT JOIN t30_inf_se infsup 
		       ON ( 
					infsup.t02_cod_proy = inf.t02_cod_proy AND 
					infsup.t02_anio = inf.t25_anio AND 
					infsup.t02_entregable = inf.t25_entregable 
				)
		    LEFT JOIN ('.$sqlSup.') AS supv ON TRIM(supv.codigo) = TRIM(cronv.id_supervisor)    
		    WHERE inf.t02_cod_proy = "'.$idProy.'" 
		    ORDER BY inf.t25_anio, entregable';
    
    	return $this->ExecuteQuery($sql);
    }
    
    
} 
