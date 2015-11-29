<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauserxml.inc.php");
require (constant('PATH_CLASS') . "BLPOA.class.php");

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion || md5("ajax_edit_cmf") == $Accion) {
    GuardarPOACab($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarPOA();
    exit();
}

if (md5("ajax_generate_version_poa") == $Accion) {
    GenerateVersionPOA();
    exit();
}

if (md5("ajax_ind_componente") == $Accion) {
    GuardarIndicadoresComponente();
    exit();
}

if (md5("ajax_ind_act") == $Accion) {
    GuardarIndicadoresActividad();
    exit();
}
/*
 * if(md5("ajax_indicadores_actividad")==$Accion) { GuardarIndicadoresActividad(); exit(); }
 */
if (md5("ajax_sub_actividad_metas") == $Accion) {
    GuardarMetasSubActividades();
    exit();
}
/*
 * if(md5("ajax_analisis_avances")==$Accion) { GuardarAnalisisAvances(); exit(); } if(md5("ajax_informacion_adicional")==$Accion) { GuardarInformacionAdicional(); exit(); }
 */
if (md5("ajax_documen_adicional") == $Accion) {
    GuardarAnexosPOA();
    exit();
}

if (md5("ajax_del_docum_adicional") == $Accion) {
    EliminarAnexoPOA();
    exit();
}

if (md5("ajax_prog_entregables") == $Accion) {
    programarEntregables();
    exit();
}

?>
<?php
// OA Técnico - Cabecera
function GuardarPOACab($tipo)
{
    $objPOA = new BLPOA();
    $bret = false;
    $retanio = 0; // Año del POA
    if ($tipo == md5("ajax_new")) {
        $bret = $objPOA->GuardarPOACabNuevo($retanio);
    }

    if ($tipo == md5("ajax_edit")) {
        $bret = $objPOA->GuardarPOACabActualizar($retanio);
    }

    if ($tipo == md5("ajax_edit_cmf")) {
        $bret = $objPOA->GuardarPOACabActualizar_CMF($retanio);
    }

    ob_clean();
    ob_start();
    if ($bret) {

        echo ("Exito" . $retanio . " Se Guardaron los Datos del POA correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function EliminarPOA()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();

    $idProy = $_REQUEST['t02_cod_proy'];
    $idAnio = $_REQUEST['anio'];

    $bret = false;
    $bret = $objPOA->POA_Eliminar($idProy, $idAnio);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Plan Operativo del Año " . $idAnio . "!!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

// ---

// enerar la Nueva version del Proyecto correspondiente al POA
function GenerateVersionPOA()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();

    $bret = false;
    $idProy = $_POST['idProy'];
    $idAnio = $_POST['idAnio'];
    //$Restructura = $_POST['restruc'];

    $bret = $objPOA->GenerarVersionPOA($idProy, $idAnio);

    /*if ($Restructura == '1') {
        $bret = $objPOA->GenerarVersionPOA($idProy, $idAnio);
    } else {
        $bret = $objPOA->GenerarVersionPOA_CronINI($idProy, $idAnio);
    }*/
    if ($bret) {
        echo ("Exito Se Generó correctamente la versión del POA !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

// ndicadores de Componente
function GuardarIndicadoresComponente()
{
    $objPOA = new BLPOA();
    $bret = false;
    $bret = $objPOA->GuardarIndicadoresComponente();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente las metas del año para los Indicadores de Componente !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}
// ndicadores de Actividad
function GuardarIndicadoresActividad()
{
    $objPOA = new BLPOA();
    $bret = false;
    $bret = $objPOA->GuardarIndicadoresActividad();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente las metas del " . "Año" . " para los Indicadores de la Actividad !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}
/*
 * function GuardarIndicadoresActividad() { $objPOA = new BLPOA(); $bret = false; $bret = $objPOA->GuardarIndicadoresActividadMI(); ob_clean(); ob_start(); if($bret) { echo("Exito Se Guardaron correctamente Los Avances de Indicadores de Actividad !!!"); } else { echo("ERROR : \n".$objPOA->GetError()); } return $bret; }
 */
// ubActividades
function GuardarMetasSubActividades()
{
    $objPOA = new BLPOA();

    $bret = false;
    $bret = $objPOA->GuardarMetas_SubActividades();
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron correctamente las metas especificadas para la Actividad !!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}
// nalisis de Avances
// function GuardarAnalisisAvances()
// {
// $objPOA = new BLPOA();
// $bret = false;
// $bret = $objPOA->GuardarAnalisisAvancesMI();
// ob_clean();
// ob_start();
// if($bret)
// {
// echo("Exito Se Guardaron correctamente los Comentarios de Avances del Informe de Monitoreo Interno!!!");
// }
// else
// {
// echo("ERROR : \n".$objPOA->GetError());
// }
// return $bret;
// }
// alificaciones
// function GuardarInformacionAdicional()
// {
// $objPOA = new BLPOA();
//
// $bret = false;
// $bret = $objPOA->GuardarInfAdicionalMI();
//
// ob_clean();
// ob_start();
// if($bret)
// {
// echo("Exito Se guardó correctamente la Información Adicional del Informe de Monitoreo Interno !!!");
// }
// else
// {
// echo("ERROR : \n".$objPOA->GetError());
// }
// return $bret;
//
// }
// nexos
function GuardarAnexosPOA()
{
    $objPOA = new BLPOA();
    $oFn = new Functions();

    $bret = false;
    $bret = $objPOA->GuardarDocumentacionAdicional();

    ob_clean();
    ob_start();
    if ($bret) {
        // echo("Exito Se Guardaron correctamente los Anexos Fotograficos !!!");
        $HardCode = "alert('" . "Se Guardaron correctamente el documento Adicional al POA !!!" . "'); \n";
        $HardCode .= "parent.LoadDocAdicional(true); \n";
        $oFn->Javascript($HardCode);
    } else {
        $HardCode = "alert('" . $objPOA->GetError() . "'); \n";
        $oFn->Javascript($HardCode);
        // echo("ERROR : \n".$objPOA->GetError());
    }
    return $bret;
}
// liminación de Documentación Adicional al POA
function EliminarAnexoPOA()
{
    ob_clean();
    ob_start();
    $objPOA = new BLPOA();
    $bret = false;
    $bret = $objPOA->EliminarDocumentacionAdicional();
    if ($bret) {
        echo ("Exito Se Elimino correctamente el archivo adicional al POA!!!");
    } else {
        echo ("ERROR : \n" . $objPOA->GetError());
    }
    return $bret;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $err = "<b style='color:red'>Error:</b><br>" . $errormsg;
    echo ($err);
    return;
}

function programarEntregables(){
    $objPOA = new BLPOA();
    $oFn = new Functions();

    $bret = false;
    $bret = $objPOA->programarEntregables();

    ob_clean();
    ob_start();

    if ($bret) {
        echo("Exito Se guardó correctamente la Programación de Entregables");
    } else {
        echo("ERROR : \n".$objPOA->GetError());
    }
    return $bret;
}
?>

<?php ob_end_flush(); ?>