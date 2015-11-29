<?php
require_once ("BLBase.class.php");
require_once ("BLProyecto.class.php");
require_once ("UploadFiles.class.php");

// / ----------------------------------------------------------------------------
// / Programmer Name : Aima R. Christian Created Date : 2010-06-01
// / Comments : Clase de Monitoreo Financiero, heredado de BLBase
// / Maneja las operaciones correspondientes a los Informes del
// / Monitor Financiero de FE.
// / ----------------------------------------------------------------------------
class BLMonitoreoFinanciero extends BLBase
{

    var $fecha;

    var $Session;

    var $Error;
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
    function InformeMFListaPeriodo($pCodProy)
    {
        $aQuery = "SELECT t51_num, t51_periodo FROM t51_inf_mf WHERE t02_cod_proy = '$pCodProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function ListaPeriodoInformesVisitaMF($pCodProy)
    {
        $aQuery = "SELECT t52_num, IFNULL(t52_periodo, '') as t52_periodo FROM t52_inf_visita_mf  WHERE t02_cod_proy = '$pCodProy'";
        return $this->ExecuteQuery($aQuery);
    }

    function ListaComponentes($idProy)
    {
        $SP = "sp_sel_componentes";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function ListadoPeriodosEjecutados($idProy)
    {
        $SP = "sp_lis_periodos_inf_financ";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // egion Informe de Monitoreo Financiero
    // / <summary>
    // / Retorna Listado de los Informes Realizados por el Monitor Financiero
    // / </summary>
    // / <param name="$idProy">Codigo del Proyecto</param>
    // / <returns>Conjunto de Registros [Resultset mysqli]</returns>
    function Inf_MF_Listado($idProy)
    {
        $SP = "sp_sel_inf_financ_mf";
        $params = array(
            $idProy
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_Seleccionar($idProy, $idInforme)
    {
        $SP = "sp_get_inf_financ_mf";
        $params = array(
            $idProy,
            $idInforme
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function Inf_MF_ListaAnexos($idProy, $idNum)
    {
        $SP = "sp_sel_inf_mf_anexos";
        $params = array(
            $idProy,
            $idNum
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // egion CRUD
    function InformeMF_NuevoCab(&$retNumero)
    {
        $params = array(
            $_POST['proy'],
            $this->ConvertDate($_POST['fchpres']),
            $_POST['per_ini'],
            $_POST['per_fin'],
            $_POST['obs'],
            $_POST['conclu'],
            $_POST['calif'],
            $_POST['estado'],
            $this->Session->UserID,
            $_POST['obs_cmt']
        );
        
        $SP = "sp_ins_inf_mf_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retNumero = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function InformeMF_ActualizaCab(&$retNumero)
    {
        $params = array(
            $_POST['proy'],
            $_POST['idnum'],
            $this->ConvertDate($_POST['fchpres']),
            $_POST['per_ini'],
            $_POST['per_fin'],
            $_POST['estado'],
            $this->Session->UserID,
            $_POST['obs_cmt']
        );
        
        $SP = "sp_upd_inf_mf_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retNumero = $ret['codigo'];
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
            $_POST['idnum']
        );
        
        $SP = "sp_del_inf_financ_cab";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function InformeMF_Guardar_EvaluacionDocs()
    {
        if ($_POST['t15_num'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $arrayTipDoc = $_POST['t51_tipdoc'];
        $arrayEstDoc = $_POST['t51_estdoc'];
        $arrayObsDoc = $_POST['t51_estobs'];
        
        $numrows = 0;
        for ($x = 0; $x < count($arrayTipDoc); $x ++) {
            $params = array(
                $_POST['t02_cod_proy'],
                $_POST['t15_num'],
                $arrayTipDoc[$x],
                $arrayEstDoc[$x],
                $arrayObsDoc[$x],
                $this->Session->UserID
            );
            
            $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_mf_eva_doc", $params);
            $numrows += $ret['numrows'] == - 1 ? 0 : $ret['numrows'];
        }
        
        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }

    function InformeMF_Guardar_ComentariosPresup()
    {
        if ($_POST['t51_num'] == '') {
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
                $_POST['t51_num'],
                $arrCodi[0],
                $arrCodi[1],
                $arrCodi[2],
                $_POST['idFuente'],
                $arrayComSub[$x],
                $this->Session->UserID
            );
            
            $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_mf_coment_presup", $params);
            $numrows += $ret['numrows'] == - 1 ? 0 : $ret['numrows'];
        }
        
        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }

    function InformeMF_Guardar_ComentariosFisico()
    {
        if ($_POST['t51_num'] == '') {
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
                $_POST['t51_num'],
                $arrCodi[0],
                $arrCodi[1],
                $arrCodi[2],
                $arrayComSub[$x],
                $this->Session->UserID
            );
            
            $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_mf_coment_fisico", $params);
            $numrows += $ret['numrows'] == - 1 ? 0 : $ret['numrows'];
        }
        
        if ($numrows > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }

    function InformeMF_Guardar_GastosNoAceptados()
    {
        if ($_POST['txtinforme'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $params = array(
            $_POST['txtproyecto'],
            $_POST['txtinforme'],
            $_POST['txtcomponente'],
            $_POST['txtactividad'],
            $_POST['txtsubactividad'],
            $_POST['txtcat'],
            $_POST['txtfuente'],
            implode("|", $_POST['txtperiodos']),
            implode("|", $_POST['txtmonto']),
            implode("|", $_POST['txtobs']),
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_up_gastos_no_aceptados", $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = "No se ha logrado grabar los datos, Comuniquese con el Administrador del Sistema" . "\n" . $this->Error;
            return false;
        }
    }
    
    // / <summary>
    // / Guarda Conclusiones del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeMF_Guardar_Conclusiones()
    {
        if ($_POST['t51_num'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t51_num'],
            $_POST['t51_conclu'],
            $_POST['t51_califica'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_mf_conclusiones", $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // / <summary>
    // / Guarda Comentarios del Informe de Monitoreo
    // / </summary>
    // / <returns>Verdadero o Falso</returns>
    function InformeMF_Guardar_Comentarios()
    {
        if ($_POST['t51_num'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t51_num'],
            $_POST['t51_obs'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar("sp_upd_inf_mf_comentarios", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function InformeMF_Excedentes()
    {
        if ($_POST['t51_num'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t51_num'],
            $this->ConvertDate($_POST['t51_cor_ctb']),
            $_POST['t51_caja'],
            $_POST['t51_bco_mn'],
            $_POST['t51_ent_rend'],
            $_POST['t51_cxc'],
            $_POST['t51_cxp'],
            $_POST['t51_caja_obs'],
            $_POST['t51_bco_mn_obs'],
            $_POST['t51_ent_rend_obs'],
            $_POST['t51_cxc_obs'],
            $_POST['t51_cxp_obs'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_inf_mf_excedentes";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function InformeMF_GuardarAnexos()
    {
        if ($_POST['t51_id'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();
        
        $objFiles->DirUpload .= 'sme/monitoreofe/informes/inf_mf/';
        
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t51_id'],
            $NomFoto,
            $_POST['t51_desc_file'],
            $ext,
            $this->Session->UserID
        );
        
        $SP = "sp_ins_inf_mf_anexos";
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
    function InformeMF_EliminarAnexos()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t51_id'],
            $_POST['t51_cod_anx']
        );
        
        $SP = "sp_del_inf_mf_anexos";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/monitoreofe/informes/inf_mf/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }

    function ObtenerComentariosSubActividades($idProy, $idNum, $Tipo = 1)
    {
        return $this->ExecuteFunction('fn_get_comentarios_avances_inf_mf', array(
            $idProy,
            $idNum,
            $Tipo
        ));
    }
    
    // nd Region
    
    // egion Listados Anexos al Informe del Monitor Financiero
    function Inf_MF_Listado_Docum_Inst($idProy, $idInforme)
    {
        $SP = "sp_sel_inf_financ_mf_docs";
        $params = array(
            $idProy,
            $idInforme
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_ListadoActividades($idProy, $idComp, $idNum, $idFte)
    {
        $SP = "sp_sel_inf_mf_financ_act";
        $params = array(
            $idProy,
            $idComp,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_ListadoPersonal($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_personal_total";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_ListaPer($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_per";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_ListaE_Total($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_equi_total";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_ListaEqui($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_equi";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_GastoFuncionamiento_Total($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_gast_func_total";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_Financ_Lista_PartidasGF($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_partida";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function GastFunc_ListadoCategMF($proy, $num, $idFte, $partida)
    {
        $params = array(
            $proy,
            $num,
            $idFte,
            $partida
        );
        return $this->ExecuteProcedureReader("sp_sel_mp_gasfunc_categ_mf", $params);
    }

    function Inf_MF_Financ_Lista_GastosAdministrativos($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_gast_adm";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_Financ_Lista_Imprevistos($idProy, $idNum, $idFte)
    {
        $sp = "sp_sel_inf_mf_financ_mp_imprevistos";
        $params = array(
            $idProy,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($sp, $params);
        return $ret;
    }

    function Inf_MF_ListadoActividades_MP($idProy, $idComp, $idNum, $idFte)
    {
        $SP = "sp_sel_inf_mf_financ_act_mp";
        $params = array(
            $idProy,
            $idComp,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_ListadoActividades2($idProy, $idComp, $idNum)
    {
        $SP = "sp_sel_inf_mf_financ_act2";
        $params = array(
            $idProy,
            $idComp,
            $idNum
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_ListadoSubActividades($idProy, $idComp, $idAct, $idNum, $idFte)
    {
        $SP = "sp_sel_inf_mf_financ_subact";
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

    function Inf_MF_ListadoSubActividades_GastosNoAceptados($idProy, $idComp, $idAct, $idSub, $idcat, $idNum, $idFte)
    {
        $SP = "sp_sel_inf_mf_financ_subact_no_gasto";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idSub,
            $idcat,
            $idNum,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_ListadoSubActividades2($idProy, $idComp, $idAct, $idNum)
    {
        $SP = "sp_sel_inf_mf_financ_subact2";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idNum
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Inf_MF_ListadoObservaciones($idProy, $idNum)
    {
        $SP = "sp_sel_inf_mf_observaciones";
        $params = array(
            $idProy,
            $idNum
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    // Evaluación Financiera de Fondoempleo
    function ProyectoSolicitudAprobacionEff($proy, $tipo, $estado, $msg, $eff)
    {
        $params = array(
            $proy,
            $tipo,
            $estado,
            $msg,
            $this->Session->UserID,
            $eff
        );
        
        $SP = "sp_upd_sol_aprob_mf";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['error'];
            return false;
        }
    }
    
    // egion Informe de Visita del Monitor Financiero
    function Inf_visita_MF_Listado($idProy)
    {
        return $this->ExecuteProcedureReader("sp_sel_inf_visita_financ_mf", array(
            $idProy
        ));
    }

    function Inf_visita_MF_Seleccionar($idProy, $idInforme)
    {
        return $this->ExecuteProcedureEscalar("sp_get_inf_visita_financ_mf", array(
            $idProy,
            $idInforme
        ));
    }

    function Informe_visita_MF_NuevoCab(&$retNumero)
    {
        $params = array(
            $_POST['txtCodProy'],
            $_POST['cbocuestionario'],
            $_POST['cboper_ini'],
            $_POST['cboper_fin'],
            $_POST['txtpersona'],
            $_POST['txtcargoper'],
            $_POST['chkopcion1'],
            $_POST['txtquest1'],
            $_POST['chkopcion2'],
            $_POST['txtquest2'],
            $_POST['chkopcion3'],
            $_POST['txtquest3'],
            $_POST['chkopcion4'],
            $_POST['txtquest4'],
            $_POST['chkopcion5'],
            $_POST['txtquest5'],
            $_POST['chkopcion6'],
            $_POST['txtquest6'],
            $_POST['chkopcion7'],
            $_POST['txtquest7'],
            $_POST['chkopcion8'],
            $_POST['txtquest8'],
            $_POST['chkopcion9'],
            $_POST['txtquest9'],
            $_POST['chkopcion10'],
            $_POST['txtquest10'],
            $_POST['chkopcion11'],
            $_POST['txtquest11'],
            $_POST['chkopcion12'],
            $_POST['txtquest12'],
            $_POST['chkopcion13'],
            $_POST['txtquest13'],
            $_POST['chkopcion14'],
            $_POST['txtquest14'],
            $_POST['chkopcion15'],
            $_POST['txtquest15'],
            $_POST['chkopcion16'],
            $_POST['txtquest16'],
            $_POST['chkopcion17'],
            $_POST['txtquest17'],
            $_POST['chkopcion18'],
            $_POST['txtquest18'],
            $_POST['chkopcion19'],
            $_POST['txtquest19'],
            $_POST['chkopcion20'],
            $_POST['txtquest20'],
            $_POST['chkopcion21'],
            $_POST['txtquest21'],
            $_POST['chkopcion22'],
            $_POST['txtquest22'],
            $_POST['chkopcion23'],
            $_POST['txtquest23'],
            $this->ConvertDate($_POST['t52_fch_pre']),
            $_POST['cboestado'],
            $_POST['txtobs'],
            $_POST['t52_conclu'],
            $_POST['t52_califica'],
            $this->Session->UserID,
            $_POST['txtObsCMT']
        );
        
        $SP = "sp_ins_inf_visita_mf";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retNumero = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function Informe_visita_MF_ActualizaCab(&$retNumero)
    {
        $params = array(
            $_POST['txtCodProy'],
            $_POST['t52_num'],
            $_POST['cbocuestionario'],
            $_POST['cboper_ini'],
            $_POST['cboper_fin'],
            $_POST['txtpersona'],
            $_POST['txtcargoper'],
            $_POST['chkopcion1'],
            $_POST['txtquest1'],
            $_POST['chkopcion2'],
            $_POST['txtquest2'],
            $_POST['chkopcion3'],
            $_POST['txtquest3'],
            $_POST['chkopcion4'],
            $_POST['txtquest4'],
            $_POST['chkopcion5'],
            $_POST['txtquest5'],
            $_POST['chkopcion6'],
            $_POST['txtquest6'],
            $_POST['chkopcion7'],
            $_POST['txtquest7'],
            $_POST['chkopcion8'],
            $_POST['txtquest8'],
            $_POST['chkopcion9'],
            $_POST['txtquest9'],
            $_POST['chkopcion10'],
            $_POST['txtquest10'],
            $_POST['chkopcion11'],
            $_POST['txtquest11'],
            $_POST['chkopcion12'],
            $_POST['txtquest12'],
            $_POST['chkopcion13'],
            $_POST['txtquest13'],
            $_POST['chkopcion14'],
            $_POST['txtquest14'],
            $_POST['chkopcion15'],
            $_POST['txtquest15'],
            $_POST['chkopcion16'],
            $_POST['txtquest16'],
            $_POST['chkopcion17'],
            $_POST['txtquest17'],
            $_POST['chkopcion18'],
            $_POST['txtquest18'],
            $_POST['chkopcion19'],
            $_POST['txtquest19'],
            $_POST['chkopcion20'],
            $_POST['txtquest20'],
            $_POST['chkopcion21'],
            $_POST['txtquest21'],
            $_POST['chkopcion22'],
            $_POST['txtquest22'],
            $_POST['chkopcion23'],
            $_POST['txtquest23'],
            $this->ConvertDate($_POST['t52_fch_pre']),
            $_POST['cboestado'],
            $_POST['txtobs'],
            $_POST['t52_conclu'],
            $_POST['t52_califica'],
            $this->Session->UserID,
            $_POST['txtObsCMT']
        );
        
        $SP = "sp_upd_inf_visita_mf";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $retNumero = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function Informe_visita_MF_Eliminar()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t52_num']
        );
        $SP = "sp_del_inf_visita_mf";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // --> Anexos al Informe de Visita del Monitor Financiero
    function Inf_visita_MF_ListaAnexos($idProy, $idNum)
    {
        return $this->ExecuteProcedureReader("sp_sel_inf_visita_mf_anexos", array(
            $idProy,
            $idNum
        ));
    }

    function Inf_visita_MF_GuardarAnexos()
    {
        if ($_POST['t52_id'] == '') {
            $this->Error = 'Primero debe Grabar la Cabecera del Informe';
            return false;
        }
        
        $objFiles = new UploadFiles("txtNomFile");
        $NomFoto = $objFiles->getFileName();
        $ext = $objFiles->getExtension();
        
        $objFiles->DirUpload .= 'sme/monitoreofe/informes/inf_visita_mf/';
        
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t52_id'],
            $NomFoto,
            $_POST['t52_desc_file'],
            $ext,
            $this->Session->UserID
        );
        
        $SP = "sp_ins_inf_visita_mf_anexos";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $objFiles->SavesAs($urlfoto);
            
            return true;
        } else {
            return false;
        }
    }

    function Inf_visita_MF_EliminarAnexos()
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t52_id'],
            $_POST['t52_cod_anx']
        );
        
        $SP = "sp_del_inf_visita_mf_anexos";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $urlfoto = $ret['url'];
            $path = constant('APP_PATH') . "sme/monitoreofe/informes/inf_visita_mf/" . $urlfoto;
            if (file_exists($path)) {
                unlink($path);
            }
            return true;
        } else {
            return false;
        }
    }
    
    // ndRegion
    
    // Reportes
    function RepInforme_Financ_Anexo01($proy, $idInf)
    {
        $SP = "sp_rpt_mf_inf_anexo_01";
        $params = array(
            $proy,
            $idInf
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepInforme_Financ_Anexo01_Detalle($proy, $idInf)
    {
        $SP = "sp_rpt_mf_inf_anexo_01_det";
        $params = array(
            $proy,
            $idInf
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function RepInforme_Financ_Anexo03($proy, $idInf, $idFte)
    {
        $SP = "sp_rpt_mf_inf_anexo_03";
        $params = array(
            $proy,
            $idInf,
            $idFte
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rpt_MF_Avance_Presup_Act($idProy, $idComp, $idFte, $ini, $fin)
    {
        $SP = "sp_rpt_avance_presup_act";
        $params = array(
            $idProy,
            $idComp,
            $idFte,
            $ini,
            $fin
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rpt_MF_Avance_Fisico_Act($idProy, $idComp, $ini, $fin)
    {
        $SP = "sp_rpt_avance_fisico_act";
        $params = array(
            $idProy,
            $idComp,
            $ini,
            $fin
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rpt_MF_Avance_Presup_SubAct($idProy, $idComp, $idAct, $idFte, $ini, $fin)
    {
        $SP = "sp_rpt_avance_presup_subact";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $idFte,
            $ini,
            $fin
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }

    function Rpt_MF_Avance_Fisico_SubAct($idProy, $idComp, $idAct, $ini, $fin)
    {
        $SP = "sp_rpt_avance_fisico_subact";
        $params = array(
            $idProy,
            $idComp,
            $idAct,
            $ini,
            $fin
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
} // fin de la Clase BLMonitoreoFinanciero

?>
