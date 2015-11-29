<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauserxml.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLMarcoLogico.class.php");
require_once (constant('PATH_CLASS') . "BLPresupuesto.class.php");
?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarNoObjecionCompras($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarNoObjecionCompras();
    exit();
}

if (md5("ajax_envrev") == $Accion) {
    RevisarNoObjecionCompras();
    exit();
}

if (md5("lista_actividades") == $Accion) {
    Lista_Actividades();
    exit();
}

if (md5("lista_sub_actividades") == $Accion) {
    Lista_SubActividades();
    exit();
}

if (md5("lista_cat_gastos") == $Accion) {
    Lista_CatGastos();
    exit();
}

if (md5("ajax_anexos") == $Accion) {
    GuardarAnexos();
    exit();
}

if (md5("ajax_del_anx") == $Accion) {
    EliminarAnexo();
    exit();
}

if (md5("ajax_gasto") == $Accion) {
    SaldoPartida();
    exit();
}

?>
<?php
// lan de Vistas de Monitoreo Externo
function GuardarNoObjecionCompras($tipo)
{
    $objProy = new BLProyecto();
    $bret = false;
    
    if ($tipo == md5("ajax_new")) {
        $bret = $objProy->NoObjecionCompraNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objProy->NoObjecionCompraActualizar();
    }
    
    $oFn = new Functions();
    
    ob_clean();
    ob_start();
    
    if ($bret) {
        $CodeJS = "parent.ReturnGuardar(true, 'Se Guardo correctamente la Objecion de Compras'); \n";
        $oFn->Javascript($CodeJS);
    } else {
        $CodeJS = "parent.ReturnGuardar(false, '" . $objProy->GetError() . "'); \n";
        $oFn->Javascript($CodeJS);
    }
    return $bret;
}

function EliminarNoObjecionCompras()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    
    $bret = false;
    $bret = $objProy->NoObjecionCompraEliminar();
    if ($bret) {
        echo ("Exito Se Elimino correctamente la Objeción de Compras !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function RevisarNoObjecionCompras()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    $bret = false;
    $bret = $objProy->NoObjecionCompraRevisar();
    if ($bret) {
        echo ("Exito Se Envio a Revision la Objecion de Compras !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function Lista_Actividades()
{
    $objML = new BLMarcoLogico();
    $objFunc = new Functions();
    
    $idProy = $objFunc->__REQUEST('proy');
    $idVersion = $objFunc->__REQUEST('idVersion');
    $idComp = $objFunc->__REQUEST('comp');
    
    $rsActv = $objML->ListadoActNOC($idProy, $idComp);
    
    echo ("<option value='' selected></option>");
    $objFunc->llenarComboI($rsActv, 't09_cod_act', 'descripcion', '');
    $objFunc = NULL;
    $objML = NULL;
}

function Lista_SubActividades()
{
    $objML = new BLMarcoLogico();
    $objFunc = new Functions();
    
    $idProy = $objFunc->__REQUEST('proy');
    $idVersion = $objFunc->__REQUEST('idVersion');
    $idComp = $objFunc->__REQUEST('comp');
    $idAct = $objFunc->__REQUEST('idAct');
    
    $rsActv = $objML->ListadoSubActNOC($idProy, $idComp, $idAct);
    
    echo ("<option value='' selected></option>");
    $objFunc->llenarComboI($rsActv, 'subact', 'descrip', '');
    $objFunc = NULL;
    $objML = NULL;
}

function Lista_CatGastos()
{
    $objPresup = new BLPresupuesto();
    $objFunc = new Functions();
    
    $idProy = $objFunc->__REQUEST('proy');
    $idVersion = $objFunc->__REQUEST('idVersion');
    $idComp = $objFunc->__REQUEST('comp');
    $idAct = $objFunc->__REQUEST('idAct');
    $idSub = $objFunc->__REQUEST('idSAct');
    
    $iRsCateg = $objPresup->ListadoCatNOC($idProy, $idComp, $idAct, $idSub);
    echo ("<option value='' selected></option>");
    $objFunc->llenarComboI($iRsCateg, 'codcat', 'desc_cat', '');
    $objFunc = NULL;
    $objPresup = NULL;
}

function GuardarAnexos()
{
    $objProy = new BLProyecto();
    $oFn = new Functions();
    
    $bret = false;
    $bret = $objProy->GuardarAnexoNoObjecionC();
    
    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Documentos de No Objecion de Compras !!!" . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $CodeJS = "parent.ReturnGuardar(false, '" . $objProy->GetError() . "'); \n";
        $oFn->Javascript($CodeJS);
    }
    return $bret;
}

function EliminarAnexo()
{
    ob_clean();
    ob_start();
    $objProy = new BLProyecto();
    $bret = false;
    $bret = $objProy->EliminarNoObjecionCompraAnx();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Documento de No Objecion de Conmpras !!!");
    } else {
        echo ("ERROR : \n" . $objProy->GetError());
    }
    return $bret;
}

function SaldoPartida()
{
    ob_clean();
    ob_start();
    $objPresup = new BLPresupuesto();
    $objFunc = new Functions();
    $sret = false;
    
    $idProy = $objFunc->__REQUEST('idProy');
    $idComp = $objFunc->__REQUEST('idComp');
    $idAct = $objFunc->__REQUEST('idAct');
    $idSubAct = $objFunc->__REQUEST('idSubAct');
    $idCat = $objFunc->__REQUEST('idCat');
    
    $sret = $objPresup->SaldoCategGasto($idProy, $idComp, $idAct, $idSubAct, $idCat);
    
    echo $sret;
}

// ---
function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>