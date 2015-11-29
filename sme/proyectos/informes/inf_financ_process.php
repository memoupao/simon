<?php 
include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLInformes.class.php");
// require (constant('PATH_CLASS') . "HardCode.class.php");

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarInforme($Accion);
    exit();
}

if (md5("ajax_edit_vb") == $Accion) {
    guardarInformeVB();
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarInforme();
    exit();
}

if (md5("ajax_gastos_fe") == $Accion) {
    $HardCode = new HardCode();
    GuardarAvanceGastos($HardCode->codigo_Fondoempleo);
    $HardCode = NULL;
    exit();
}

if (md5("ajax_coment_fe") == $Accion) {
    $HardCode = new HardCode();
    GuardarComentariosGastos($HardCode->codigo_Fondoempleo);
    $HardCode = NULL;
    exit();
}

if (md5("ajax_comentarios_fte") == $Accion) {
    $idFte = $_POST['cbofuentes'];
    GuardarComentariosGastos($idFte);
    exit();
}

if (md5("ajax_gastos_fte") == $Accion) {
    $idFte = $_POST['cbofuentes'];
    GuardarAvanceGastos($idFte);
    exit();
}
if (md5("ajax_otros_gastos") == $Accion) {
    GuardarOtrosGastos();
    exit();
}
if (md5("ajax_excedentes_ejecutar") == $Accion) {
    GuardarExcedentesEjecutar();
    exit();
}

if (md5("ajax_save_anexos") == $Accion) {
    GuardarAnexos();
    exit();
}

if (md5("ajax_del_anexos") == $Accion) {
    EliminarAnexos();
    exit();
}

if (md5("ajax_importar_gastos_carga_file") == $Accion) {
    CargarArchivoGastos();
    exit();
}

if (md5("ajax_importar_finalizar") == $Accion) {
    FinalizarImportarGastos();
    exit();
}

if (md5("ajax_lista_informes_financ") == $Accion) {
    Listado_Informes_Finac();
    exit();
}

?>
<?php
// nforme Mensual - Cabecera
function GuardarInforme($tipo)
{
    $objInf = new BLInformes();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objInf->InformeFinancieroNuevoCab();
    }

    if ($tipo == md5("ajax_edit")) {
        $bret = $objInf->InformeFinancieroActualizaCab();
    }

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function guardarInformeVB()
{
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->InformeFinancieroActualizaCab();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function EliminarInforme()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->InformeFinancieroEliminarCab();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Informe Financiero del Mes Seleccionado !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// nforme de Gastos
function GuardarAvanceGastos($fte)
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->GuardarAvanceGastos($fte);

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los gastos del informe !!!");
    } else {
        echo ("ERROR: \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarComentariosGastos($fte)
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->GuardarComentariosGastos($fte);

    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los comentario de los Gastos !!!");
    } else {
        echo ("ERROR: \n" . $objInf->GetError());
    }
    return $bret;
}

// tros Gastos
function GuardarOtrosGastos()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->InformeFinancieroOtrosGastos();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los otros gastos !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarExcedentesEjecutar()
{
    $objInf = new BLInformes();

    $bret = false;
    $bret = $objInf->InformeFinancieroExcedentes();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente los Excedentes por ejecutar !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

function GuardarAnexos()
{
    $objInf = new BLInformes();
    $oFn = new Functions();

    $bret = false;
    $bret = $objInf->GuardarAnexosInformeFinanc();

    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente los Anexos del Informe Financiero !!!" . "'); \n";
        $HardCode .= "parent.LoadAnexos(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objInf->GetError() . "'); \n";
        // echo("ERROR : \n".$objInf->GetError());
    }
    return $bret;
}

function EliminarAnexos()
{
    ob_clean();
    ob_start();
    $objInf = new BLInformes();
    $bret = false;
    $bret = $objInf->EliminarAnexosInformeFinanc();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Anexo del Informe !!!");
    } else {
        echo ("ERROR : \n" . $objInf->GetError());
    }
    return $bret;
}

// argar Archivo de Gastos
function CargarArchivoGastos()
{
    $objInf = new BLInformes();
    $objHardCode = new HardCode();

    $bret = false;
    $urlFile = '';
    $bret = $objInf->ImportarGastos_01($objHardCode->FolderUploadXLS, $urlFile);
    $objHardCode = NULL;
    ob_clean();
    ob_start();

    $oFn = new Functions();
    if ($bret) {
        $HardCode .= "parent.LoadImportarGastos02('" . $urlFile . "'); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('No se logro cargar el archivo, verifique el formato del archivo'); \n";
        $oFn->Javascript($HardCode);
        // echo("ERROR : \n".$objInf->GetError());
    }
    return $bret;
}

function FinalizarImportarGastos()
{
    $objInf = new BLInformes();

    $bret = false;

    $bret = $objInf->ImportarGastos_04($_POST['idProy'], $_POST['idAnio'], $_POST['idMes'], $_POST['idFte']);
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Importaron correctamente los Gastos !!!");
    } else {
        // echo("ERROR : \n".$objInf->GetError());
        echo ("ERROR : \n Ocurrio un error al intentar grabar los gastos del Informe");
    }
    return $bret;
}

function Listado_Informes_Finac()
{
    ob_clean();
    ob_start();
    $t02_cod_proy = $_REQUEST['idProy'];
    $idNumMesInf = - 1;

    $objFinanc = new BLInformes();
    $objFunc = new Functions();

    $Rs = $objFinanc->InformeFinancieroListado($t02_cod_proy);
    $objFunc->llenarComboGroupII($Rs, "nummes", "descripcion", $idNumMesInf, 'anio');
    $objFinanc = NULL;

    return true;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}
?>

<?php ob_end_flush(); ?>