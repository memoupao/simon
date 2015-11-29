<?php

include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");

require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLInterface.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLPOA.class.php");
require_once (constant('PATH_CLASS') . "Functions.class.php");



$objFunc = new Functions();
$Accion = $objFunc->__GET('action');
if ($Accion == '') {
    echo (Error());
    exit();
}
if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    Guardar($Accion);
    exit();
}

if (md5("ajax_cta_bancaria") == $Accion) {
    GuardarCtaBancaria();
    exit();
}

if (md5("ajax_del") == $Accion) {
    Eliminar();
    exit();
}
if (md5("ajax_lista_versiones") == $Accion) {
    ListaVersionesProy();
    exit();
}

if (md5("ajax_relacionar_cuentas") == $Accion) {
    RelacionarCuentas();
    exit();
}

if (md5("ajax_anexos") == $Accion) {
    GuardarAnexos();
    exit();
}
if (md5("ajax_del_anx") == $Accion) {
    EliminarAnexo();
    exit();
} // modificado 30/11/2011
if (md5("env_rev") == $Accion) {
    EnviarRevision();
    exit();
}

if (md5("ajax_num_susp_days") == $Accion) {
    CalcularNumeroMesesSuspencion();
    exit();
}

// DA 2.0 [08-11-2013 12:40]
// Calculos de tasas por concurso y Linea
if (md5('getTasasPorLineaConcurso') == $Accion) {
    calcularTasasPorLineaConcurso();
    exit;
}

// -------------------------------------------------->
// DA 2.0 [08-11-2013 23:14]
// Lista de cuentas bancarias de la institucion
if (md5('getCuentasBancariasInstitucion') == $Accion) {
    getCuentasBancariasInstitucion();
    exit;
}

if (md5('getNroCuentasBancariasPorInstitucion') == $Accion) {
    getNrosCuentasBancariasPorInstitucion();
    exit;
}
// -------------------------------------------------->

// -------------------------------------------------->
// AQ 2.0 [06-12-2013 13:00]
// Aprobación de Proyecto
if (md5("aprobarProyecto") == $Accion) {
    aprobarProyecto();
    exit();
}
// -------------------------------------------------->

function Guardar($tipo)
{

    // DA 2.0 [22-10-2013 17:12]
    // En las variables $_POST se eliminaron el uso de la funcion utf8_decode ya que por defecto
    // ya que por defecto la coneccion es UTF8.
    // Se adiciono la variable $t00_cod_linea en los parametros de los metodos
    // $objProy->ProyectoNuevo y $objProy->ProyectoActualizar
    // para el registro en los SP.
    $vs = $_GET['vs'];
    $t02_nro_exp = $_POST['t02_nro_exp'];
    // -------------------------------------------------->
    // DA 2.0 [22-10-2013 17:12]
    // Nueva variable para el registro en la tabla t02_dg_proy en el nuevo campo t00_cod_linea
    $t00_cod_linea = $_POST['t00_cod_linea'];
    // --------------------------------------------------<
    $t01_id_inst = $_POST['t01_id_inst'];
    // DA 2.0 [08-11-2013 20:33]
    // Nueva variable de cuenta bancaria del proyecto
    //$t01_id_cta = $_POST['t01_id_cta'];
    // --------------------------------------------------<
    $t02_cod_proy = $_POST['t02_cod_proy'];
    $t02_nom_proy = $_POST['t02_nom_proy'];
    $t02_fch_apro = $_POST['t02_fch_apro'];
    $t02_estado = $_POST['t02_estado'];
    $t02_fin = $_POST['t02_fin'];
    $t02_pro = $_POST['t02_pro'];
    $t02_ben_obj = $_POST['t02_ben_obj'];

    // -------------------------------------------------->
    // DA 2.0 [29-10-2013 15:22]
    // Campos no seran tomados en cuenta y seran eliminados

    // $t02_amb_geo= $_POST['t02_amb_geo'];
    $t02_amb_geo = '';
    /*
     * $t02_pres_fe= 	$_POST['t02_pres_fe']; $t02_pres_eje= 	$_POST['t02_pres_eje']; $t02_pres_otro= $_POST['t02_pres_otro']; $t02_pres_tot= 	$_POST['t02_pres_tot'];
     */
    // $t02_moni_fina= $_POST['t02_moni_fina'];
    $t02_moni_fina = 0;
    // $t02_cre_fe = $_POST['t02_cre_fe'];
    $t02_cre_fe = 'N';
    // --------------------------------------------------<

    $t02_moni_tema = $_POST['t02_moni_tema'];

    $t02_moni_ext = $_POST['t02_moni_ext'];
    // -------------------------------------------------->
    // DA 2.0 [22-10-2013 17:12]
    // El id del Supervisor Proyecto tiene valor vacio y se envia a la tabla que es de tipo entero.
    // DA 2.0 [29-10-2013 15:22]
    // No sera tomado en cuenta y sera eliminado.

    // $t02_sup_inst= (empty($_POST['t02_sup_inst']) ? 0 : $_POST['t02_sup_inst']);
    $t02_sup_inst = 0;

    // --------------------------------------------------<

    $t02_dire_proy = $_POST['t02_dire_proy'];
    $t02_ciud_proy = $_POST['t02_ciud_proy'];
    $t02_tele_proy = $_POST['t02_tele_proy'];
    $t02_fax_proy = $_POST['t02_fax_proy'];
    $t02_mail_proy = $_POST['t02_mail_proy'];
    $t02_fch_isc = $_POST['t02_fch_isc'];
    $t02_fch_ire = $_POST['t02_fch_ire'];
    $t02_fch_tre = $_POST['t02_fch_tre'];
    $t02_fch_tam = $_POST['t02_fch_tam'];
    $t02_num_mes = $_POST['t02_num_mes'];
    $t02_num_mes_amp = $_POST['t02_num_mes_amp'];

    /*$t02_sect_main = $_POST['t02_sector_main'];
    $t02_prod_promovido = $_POST['t02_prod_promovido'];

    $t02_sector = $_POST['t02_sec_prod'];
    $t02_subsector = $_POST['t02_subsec_prod'];*/

    $t02_fch_reinic = $_POST['t02_fch_reinic'];
    $t02_obs_susp = $_POST['t02_obs_susp'];
    $origProyState = $_POST['origProyState'];

    // -------------------------------------------------->
    // DA 2.0 [23-10-2013 15:42]:
    // Valores recibido del formulario de Tasas:
    // DA 2.0 [08-11-2013 11:33]:
    // Nueva tasa de gastos de supervisi�n del proyecto

    $t02_gratificacion = floatval($_POST['t02_gratificacion']);
    $t02_porc_cts = floatval($_POST['t02_porc_cts']);
    $t02_porc_ess = floatval($_POST['t02_porc_ess']);
    $t02_porc_gast_func = floatval($_POST['t02_porc_gast_func']);
    $t02_porc_linea_base = floatval($_POST['t02_porc_linea_base']);
    $t02_porc_imprev = floatval($_POST['t02_porc_imprev']);
    $t02_proc_gast_superv = floatval($_POST['t02_proc_gast_superv']);
    // --------------------------------------------------<

    
    // -------------------------------------------------->
    // DA 2.1 [19-04-2014 19:13]:
    // Nuevo campo de Instituciones asociadas o colaboradoras [RF-001]:
    $t02_inst_asoc = strip_tags($_POST['t02_inst_asoc']);
    $t02_inst_asoc = htmlentities($t02_inst_asoc, ENT_QUOTES, "UTF-8");
    // --------------------------------------------------<
    
    
    // -------------------------------------------------->
    // AQ 2.0 [19-11-2013 20:54]:
    // Aprobación
    // AQ 2.0 [27-11-2013 11:16]:
    $chkVB = ($_POST['chkVB'] != NULL) ? 1 : 0;

    // --------------------------------------------------<

    $HC = new HardCode();
    $objProy = new BLProyecto();
    $bret = false;
    $anErrorMsg = null;

    try {
        if ($tipo == md5("ajax_new")) {
            $vs = 1;
            $bret = $objProy->ProyectoNuevo($t02_nro_exp, $t00_cod_linea, $t01_id_inst, $t02_cod_proy, $t02_nom_proy, $t02_fch_apro, $t02_estado, $t02_fin, $t02_pro, $t02_ben_obj, $t02_amb_geo, $t02_moni_tema, $t02_moni_fina, $t02_moni_ext, $t02_sup_inst, $t02_dire_proy, $t02_ciud_proy, $t02_tele_proy, $t02_fax_proy, $t02_mail_proy, $t02_fch_isc, $t02_fch_ire, $t02_fch_tre, $t02_fch_tam, $t02_num_mes, $t02_num_mes_amp, /*$t02_sect_main, $t02_sector, $t02_subsector, $t02_prod_promovido, */ $t02_cre_fe, $t02_gratificacion, $t02_porc_cts, $t02_porc_ess, $t02_porc_gast_func, $t02_porc_linea_base, $t02_porc_imprev, $t02_proc_gast_superv, $t02_inst_asoc);
        }

        if ($tipo == md5("ajax_edit")) {
            $bret = $objProy->ProyectoActualizar($vs, $t02_nro_exp, $t00_cod_linea, $t01_id_inst, $t02_cod_proy, $t02_nom_proy, $t02_fch_apro, $t02_estado, $t02_fin, $t02_pro, $t02_ben_obj, $t02_amb_geo, $t02_moni_tema, $t02_moni_fina, $t02_moni_ext, $t02_sup_inst, $t02_dire_proy, $t02_ciud_proy, $t02_tele_proy, $t02_fax_proy, $t02_mail_proy, $t02_fch_isc, $t02_fch_ire, $t02_fch_tre, $t02_fch_tam, $t02_num_mes, $t02_num_mes_amp, /* $t02_sect_main, $t02_sector, $t02_subsector, $t02_prod_promovido,*/ $t02_cre_fe, $t02_gratificacion, $t02_porc_cts, $t02_porc_ess, $t02_porc_gast_func, $t02_porc_linea_base, $t02_porc_imprev, $t02_proc_gast_superv, $chkVB, $t02_inst_asoc);

            if ($bret) {
                if ($origProyState != $HC->Proy_Suspendido && $t02_estado == $HC->Proy_Suspendido) {
                    $objProy->SuspendProject($t02_cod_proy, $vs, $t02_obs_susp);
                } else
                    if ($origProyState == $HC->Proy_Suspendido && $t02_estado != $HC->Proy_Suspendido) {
                        $objFunc = new Functions();
                        $objProy->UnsuspendProject($t02_cod_proy, $vs, $objFunc->convertDate($t02_fch_reinic), $t02_obs_susp);
                        if ($t02_estado == $HC->Proy_Ejecucion) {
                            $objPOA = new BLPOA();
                            $aPoaCabRow = $objPOA->POACabUltimo($t02_cod_proy);
                            $aRetAnio = 0;
                            $aParamsArr = array(
                                $t02_cod_proy,
                                $aPoaCabRow['t02_anio'],
                                $aPoaCabRow['t02_periodo'],
                                '',
                                '',
                                '',
                                '',
                                $aPoaCabRow['t02_estado'],
                                '',
                                null,
                                '1'
                            );
                            $objPOA->SaveNewPOAHeader($aParamsArr, $aRetAnio);
                        }
                    }
            }
        }
    } catch (Exception $e) {
        $bret = false;
        $anErrorMsg = $e->getMessage();
    }

    /* En PHP v. 5.2 y 5.3 aun no esta implementado el bloque "finally" asi que se tiene que manejar de otra manera */
    ob_clean();
    ob_start();

    if ($bret) {
        echo ("Exito" . $vs . " Se guardaron los datos correctamente !!!$aQuery");
    } else {
        echo ("ERROR : \n" . ($anErrorMsg ? $anErrorMsg : $objProy->GetError()));
    }

    return $bret;
}

function GuardarCtaBancaria()
{
    $objProy = new BLProyecto();
    $bret = false;

    $bret = $objProy->GuardarCtaBancaria();

    ob_clean();
    ob_start();

    if ($bret) {
        echo ("Exito Se Guardaron los Datos de la Cuenta correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function Eliminar()
{
    ob_clean();
    ob_start();
    $bret = false;
    $t02_cod_proy = $_POST['idProy'];
    $vs = $_POST['vs'];
    $objProy = new BLProyecto();
    $bret = $objProy->ProyectoEliminar($t02_cod_proy, $vs);
    if ($bret) {
        // -------------------------------------------------->
        // DA 2.0 [24-11-2013 21:30]
        // Eliminamos los archivo anexos del proyecto:
        eliminarAnexosDelProyecto($t02_cod_proy, $vs);
        // --------------------------------------------------<
        echo ("Exito Se Elimino correctamente el Registro [" . $t02_cod_proy . "] !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function RelacionarCuentas()
{
    ob_clean();
    ob_start();
    $bret = false;
    $t02_cod_proy = $_POST['t02_cod_proy'];
    $t50_cod_sis = $_POST['t50_cod_sis'];
    $t50_cod_ext = $_POST['t50_cod_ext'];
    $t50_des_ext = $_POST['t50_des_ext'];

    $objInt = new BLInterface();
    $bret = $objInt->RelacionarCuentas($t02_cod_proy, $t50_cod_sis, $t50_cod_ext, $t50_des_ext);

    if ($bret) {
        echo ("Exito Se agrego correctamente la relacion !!!");
    } else {
        echo ("ERROR : \n" . $objInt->GetError());
    }
    return $bret;
}

// istados Ajax
// Listado de Versiones del Proyecto
function ListaVersionesProy()
{
    ob_clean();
    ob_start();
    $bret = false;
    $Fun = new Functions();
    $t02_cod_proy = $Fun->__Request('idProy');
    $objProy = new BLProyecto();
    $MaxVersion = $objProy->MaxVersion($t02_cod_proy);
    $rsVS = $objProy->ListaVersiones($t02_cod_proy);
    $Fun->llenarCombo($rsVS, 't02_version', 'nom_version', $MaxVersion);
    $Fun = NULL;
    $objProy = NULL;
}

function GuardarAnexos()
{
    $objProy = new BLProyecto();
    $oFn = new Functions();

    $bret = false;
    $bret = $objProy->GuardarAnexo();

    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Documentos del Proyecto !!!" . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objInf->GetError() . "'); \n";
        // echo("ERROR : \n".$objInf->GetError());
    }
    return $bret;
}

function EliminarAnexo()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    $bret = false;
    $bret = $objProy->EliminarAnx();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Documento del Proyecto !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}
// modificado 30/11/2011
function EnviarRevision()
{
    ob_clean();
    ob_start();
    $bret = true;
    $t02_cod_proy = $_POST['idProy'];
    $vs = $_POST['vs'];
    $objProy = new BLProyecto();
    $bret = $objProy->actualizarRevision($t02_cod_proy, $vs);
    if ($bret) {
        echo ("Exito" . $vs . " El Documento fue enviado correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function CalcularNumeroMesesSuspencion()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    $aIdProy = $_GET['idProy'];
    $aIdVers = $_GET['idVer'];
    $aSuspRecord = $objProy->GetMostRecentSuspention($aIdProy, $aIdVers);
    $nroMonths = 0;

    if ($aSuspRecord['t02_fch_susp']) {
        $nroMonths = ceil((strtotime("now") - strtotime($aSuspRecord['t02_fch_susp'])) / (60 * 60 * 24 * 30));
    }

    echo $nroMonths;

    return $nroMonths;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

// DA 2.0 [08-11-2013 12:40]
// Funcion para el calculo de tasas por concurso y Linea
function calcularTasasPorLineaConcurso()
{

    $concurso = (isset($_POST['concurso']) ? $_POST['concurso'] : null);
    $linea = (isset($_POST['linea']) ? $_POST['linea'] : null);
    $arrValues = array ();

    if ($concurso && $linea) {

        $objProy = new BLProyecto();
        $arrValues = $objProy->getTasasPorConcursoYLinea($concurso, $linea);
    }

    echo json_encode(array('data'=>$arrValues));
    exit;

}
// -------------------------------------------------->

// DA 2.0 [08-11-2013 23:14]
// Lista de cuentas bancarias de la institucion
function getCuentasBancariasInstitucion()
{
    $institucion = (isset($_POST['inst']) ? $_POST['inst'] : null);
    $arrValues = array ();
    if ($institucion) {
        $objProy = new BLProyecto();
        $arrValues = $objProy->getCuentasBancarias(null,$institucion,true);

    }

    echo json_encode(array('data'=>$arrValues));
    exit;
}

function getNrosCuentasBancariasPorInstitucion()
{
	$proy = (isset($_POST['proy']) ? $_POST['proy'] : null);
	$idInst = (isset($_POST['inst']) ? $_POST['inst'] : null);
    $banco = (isset($_POST['banco']) ? $_POST['banco'] : null);
    $tipocuenta = (isset($_POST['tipocuenta']) ? $_POST['tipocuenta'] : null);
    $mone = (isset($_POST['mone']) ? $_POST['mone'] : null);


    $arrValues = array ();
    if ($idInst) {
        $objProy = new BLProyecto();
        $arrValues = $objProy->getNrosDeCuentasPorInstitucion($idInst,$proy,$banco,$tipocuenta,$mone);

    }

    echo json_encode(array('data'=>$arrValues));
    exit;
}
// -------------------------------------------------->

// -------------------------------------------------->
// DA 2.0 [24-11-2013 21:30]
// Eliminamos los archivo anexos del proyecto:
function eliminarAnexosDelProyecto($t02_cod_proy, $vs)
{

    $pattern = 'anexos/'.trim($t02_cod_proy)."*".$vs.".txt";

    foreach (glob($pattern) as $archivo) {

        if (file_exists($archivo)) {
            unlink($archivo);
        }
    }


}
// --------------------------------------------------<

// -------------------------------------------------->
// AQ 2.0 [06-12-2013 13:00]
// Aprobación de Proyecto
function aprobarProyecto()
{
    $objProy = new BLProyecto();
    echo $objProy->aprobarProyecto($_POST['idProy']);
}
// -------------------------------------------------->

ob_end_flush();