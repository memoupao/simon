<?php
ob_start('LimpiarBuffer');
header("Content-type: text/xml");
?>
<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauserxml.inc.php"); ?>

<?php
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
?>

<?php
$action = $objFunc->__GET('action');

if ($action == '') {
    echo (Error());
    exit();
}

// Si la accion encriptada el Lista entonces, Selecionamos
if (md5("lista_usuarios") == $action) {
    echo (ListaUsuarios());
    exit();
}

if (md5("lista_perfiles") == $action) {
    echo (ListaPerfiles());
    exit();
}
if (md5("lista_concursos") == $action) {
    echo (ListaConcursos());
    exit();
}
if (md5("lista_menus") == $action) {
    echo (ListaMenus());
    exit();
}

if (md5("lista_tablas_aux") == $action) {
    echo (ListaTablasAux());
    exit();
}

if (md5("lista_subtablas_aux") == $action) {
    echo (ListaSubTablasAux());
    exit();
}

// -------------------------------------------------->
// DA 2.0 [10-11-2013 22:05]
// Lista de Productos principales: Tercer nivel de clasificacion
//
if (md5("lista_subtablas_aux3") == $action) {
    echo (ListaSubTablasAux3());
    exit();
}
// --------------------------------------------------<

if (md5("banco") == $action) {
    echo (listar("banco"));
    exit();
}

if (md5("moneda") == $action) {
    echo (listar("moneda"));
    exit();
}

// -------------------------------------------------->
// AQ 2.0 [21-10-2013 16:15]
// Casos agregados: tipo_anexo y tipo_cuenta
//
if (md5("tipo_anexo") == $action) {
    echo (listar("tipo_anexo"));
    exit();
}

if (md5("tipo_cuenta") == $action) {
    echo (listar("tipo_cuenta"));
    exit();
}
// --------------------------------------------------<

// -------------------------------------------------->
// DA 2.0 [21-10-2013 16:22]
// Casos agregados: linea y tasa
//
// DA 2.0 [22-10-2013 20:14]
// Cambio con una nueva funcion ListarTasa()
//
if (md5("linea") == $action) {
    echo (listar('linea'));
    exit();
}

if (md5("tasa") == $action) {
    echo (ListarTasa());
    exit();
}
// --------------------------------------------------<


if (md5("lista_parametros") == $action) {
    echo listar('parametro');
    exit;
}

?>

<?php
/* Definición de Funciones que escriben el archivo XML */
function ListaUsuarios()
{
    $objFun = new Functions();
    $objMante = new BLMantenimiento();
    $idPerfil = $_GET['perfil'];
    $rs = $objMante->ListaUsuarios($idPerfil);
    $fields = $objMante->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "usuarios", $fields);
    return $xmlResult;
}

function ListaPerfiles()
{
    $objFun = new Functions();
    $objMante = new BLMantenimiento();
    $rs = $objMante->ListaPerfiles();
    $fields = $objMante->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "perfiles", $fields);
    return $xmlResult;
}

function ListaConcursos()
{
    $objFun = new Functions();
    $objMante = new BLMantenimiento();
    $rs = $objMante->ListaConcursos();
    $fields = $objMante->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "concursos", $fields);
    return $xmlResult;
}

function ListaMenus()
{
    $objFun = new Functions();
    $objMante = new BLMantenimiento();
    $idModulo = $_GET['modulo'];
    $rs = $objMante->ListaMenus($idModulo);
    $fields = $objMante->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "menus", $fields);
    return $xmlResult;
}

function ListaTablasAux()
{
    $objFun = new Functions();
    $objTabla = new BLTablasAux();
    $idTabla = $_GET['idTabla'];
    $rs = $objTabla->ListadoTipos($idTabla);
    $fields = $objTabla->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "tipos", $fields);
    return $xmlResult;
}

function ListaSubTablasAux()
{
    $objFun = new Functions();
    $objTabla = new BLTablasAux();
    $idTabla = $_GET['idTabla'];
    $idTablaAux = $_GET['idTablaAux'];
    $rs = $objTabla->ListadoSubTipos($idTabla, $idTablaAux);
    $fields = $objTabla->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "subtipos", $fields);
    return $xmlResult;
}


// ------------------------------------------------->
// DA 2.0 [22-10-2013 2013]
// Nueva funcion para el listado de Productos Principales (tercera clasificacion de productos)
function ListaSubTablasAux3()
{
    $objFun = new Functions();
    $objTabla = new BLTablasAux();
    $idTabla = $_GET['idTabla'];
    $idTablaAux = $_GET['idTablaAux'];
    $rs = $objTabla->ListadoProductosPrincipales($idTabla, $idTablaAux);
    $fields = $objTabla->iGetArrayFields($rs);
    $xmlResult = $objFun->iGenerateXML($rs, "subtipos", $fields);
    return $xmlResult;
}
// --------------------------------------------------<


// ------------------------------------------------->
// DA 2.0 [22-10-2013 2013]
// Nueva funcion para el listado de Tasas
function ListarTasa()
{
    $objFun = new Functions();
    $objMante = new BLMantenimiento();
    $rs = $objMante->listar('tasa');
    $fields = $objMante->iGetArrayFields($rs);
    
    $xmlResult = $objFun->iGenerateXML($rs, 'tasa', $fields);
    return $xmlResult;
}
// --------------------------------------------------<



function listar($lista)
{
    $objFun = new Functions();
    $objMante = new BLMantenimiento();
    $rs = $objMante->listar($lista);
    $fields = $objMante->iGetArrayFields($rs);
    
    $xmlResult = $objFun->iGenerateXML($rs, $lista, $fields);
    return $xmlResult;
}

function Error($errormsg = "Error al Recibir Parametros")
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
		<error>
		  <msgs id=\"1\">
			<msg>" . $errormsg . "</msg>
		  </msgs>
		</error>";
    return $xml;
}

function MsgXML($msg)
{
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
		<msgxml>
		  <msgs id=\"1\">
			<msg>" . $msg . "</msg>
		  </msgs>
		</msgxml>";
    return $xml;
}

function LimpiarBuffer($buffer)
{
    return trim($buffer);
}
?>
<?php ob_end_flush();  ?>
