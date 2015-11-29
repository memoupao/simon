<?php

require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");
require_once ("UploadFiles.class.php");
// / -------------------------------------------------------------------------
// / Programmer Name : Aima R. Christian Created Date : 2010-08-25
// / Comments : Clase que trabaja con los codigos del Ejecutor y los codigos
// / del Sistema EVASIS
// / -------------------------------------------------------------------------
class BLInterface extends BLBase
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
    // / Retorna Listado de Codigos del MARCO Logico mas cronograna de subactividades
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaCodigosSistemaAll($idProy)
    {
        $SP = "sp_sel_equiv_sistema";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // / <summary>
    // / Retorna Listado de Codigos del Ejecutor importados de su sistema contable.
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function ListaCodigosEjecutorAll($idProy)
    {
        $SP = "sp_sel_equiv_ejecutor";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RelacionarCuentas($idProy, $idCtaSYS, $idCtaEjec, $desCtaEjec)
    {
        $params = array(
            $idProy,
            $idCtaSYS,
            $idCtaEjec,
            $desCtaEjec,
            $this->Session->UserID
        );
        $SP = "sp_ins_equiv_codis";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
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
    
    // ndRegion
} // fin de la Clase BLInformes

