<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>

<?php require(constant('PATH_CLASS')."BLMantenimiento.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarMenu($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarMenu();
    exit();
}

if (md5("ajax_lista_menu_paginas") == $Accion) {
    ListaMenuPagina();
    exit();
}
if (md5("ajax_agrega_menu_paginas") == $Accion) {
    AgregaMenuPagina();
    exit();
}
if (md5("ajax_elimina_menu_paginas") == $Accion) {
    EliminaMenuPagina();
    exit();
}

?>
<?php
// egion Mantenimiento de Menues
function GuardarMenu($tipo)
{
    $objMante = new BLMantenimiento();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objMante->MenuNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMante->MenuActualizar();
    }
    
    ob_clean();
    ob_start();
    if ($bret) {
        echo ("Exito Se Guardaron los Datos correctamente !!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

function EliminarMenu()
{
    ob_clean();
    ob_start();
    $objMante = new BLMantenimiento();
    
    $bret = false;
    $idMenu = $_POST['idMenu'];
    
    $bret = $objMante->MenuEliminar($idMenu);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el Menu [" . $idMenu . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}
// nd Region

// gregar Paginas Secundarias al Menu Seleccioando
function AgregaMenuPagina()
{
    ob_clean();
    ob_start();
    $objMante = new BLMantenimiento();
    
    $bret = false;
    $idMenu = $_POST['mnu_cod'];
    $NomMenu = $_POST['pag_nomb'];
    $Link = $_POST['pag_link'];
    
    $bret = $objMante->MenuAgregarPagina($idMenu, $NomMenu, $Link);
    if ($bret) {
        echo ("Exito Se Agregó correctamente la página secundaria al Menu [" . $idMenu . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

function ListaMenuPagina()
{
    ob_clean();
    ob_start();
    $objMante = new BLMantenimiento();
    $Fun = new Functions();
    $idMenu = $Fun->__Request('mnu_cod');
    
    $rsPaginas = $objMante->ListaMenusPagina($idMenu);
    $Fun->llenarComboI($rsPaginas, 'codigo', 'nombre', '0');
    $Fun = NULL;
    $objMante = NULL;
}

function EliminaMenuPagina()
{
    ob_clean();
    ob_start();
    $objMante = new BLMantenimiento();
    
    $bret = false;
    $idMenu = $_POST['txtcodigo'];
    $idPaginas = join(',', $_POST['lstpaginas']);
    
    $bret = $objMante->MenuEliminaPagina($idMenu, $idPaginas);
    if ($bret) {
        echo ("Éxito se eliminó correctamente las páginas secundarias del Menu [" . $idMenu . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

?>

<?php ob_end_flush(); ?>