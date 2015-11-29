<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauserxml.inc.php"); ?>

<?php require_once(constant('PATH_CLASS')."BLMantenimiento.class.php"); ?>
<?php require_once(constant('PATH_CLASS')."HardCode.class.php"); ?>
<?php require_once(constant('PATH_CLASS')."BLProyecto.class.php"); ?>
<?php require_once(constant('PATH_CLASS')."BLEjecutor.class.php"); ?>

<?php

$Accion = $objFunc->__GET('action');

if ($Accion == '') {
    echo (Error());
    exit();
}

if (md5("ajax_new") == $Accion || md5("ajax_edit") == $Accion) {
    GuardarUsuario($Accion);
    exit();
}

if (md5("ajax_del") == $Accion) {
    EliminarUsuario();
    exit();
}

if (md5("change_pwd_mante") == $Accion || md5("change_pwd") == $Accion) {
    ChangePWDMante($Accion); // No se valida el Password Anterior
    exit();
}

if (md5("ajax_carga_asociar_usuario") == $Accion) {
    CargarAsociarUsuario();
    exit();
}

?>
<?php
// egion Mantenimiento de Usuarios
function GuardarUsuario($tipo)
{
    $objMante = new BLMantenimiento();
    $bret = false;
    if ($tipo == md5("ajax_new")) {
        $bret = $objMante->UsuarioNuevo();
    }
    
    if ($tipo == md5("ajax_edit")) {
        $bret = $objMante->UsuarioActualizar();
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

function EliminarUsuario()
{
    ob_clean();
    ob_start();
    $objMante = new BLMantenimiento();
    
    $bret = false;
    $idUser = $_POST['idUser'];
    $bret = $objMante->UsuarioEliminar($idUser);
    if ($bret) {
        echo ("Exito Se Elimino correctamente el usuario [" . $idUser . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

function ChangePWDMante($tipo)
{
    ob_clean();
    ob_start();
    
    $ususario = $_POST['txtuser'];
    
    if (md5("change_pwd") == $tipo) {
        $pwd_anterior1 = $_POST['txtpwdanterior1'];
        $pwd_anterior2 = $_POST['txtpwdanterior2'];
        if (md5($pwd_anterior2) != $pwd_anterior1) {
            echo ("ERROR : \n" . "La contraseña anterior no es correcta.");
            return false;
        }
    }
    
    $pwd_new1 = $_POST['txtpwd1'];
    $pwd_new2 = $_POST['txtpwd2'];
    
    if ($pwd_new1 != $pwd_new2) {
        echo ("ERROR : \n" . "Las Nuevas Contraseñas no coinciden, verifique por favor...");
        return false;
    }
    
    $objMante = new BLMantenimiento();
    $bret = false;
    $bret = $objMante->UsuarioCambiarPwd($ususario, $pwd_new1);
    if ($bret) {
        echo ("Exito Se Cambio correctamente la Contraseña del usuario [" . $ususario . "]!!!");
    } else {
        echo ("ERROR : \n" . $objMante->GetError());
    }
    return $bret;
}

function CargarAsociarUsuario()
{
    ob_clean();
    ob_start();
    // $objTablas = new BLTablasAux();
    $objMante = new BLMantenimiento();
    $objProy = new BLProyecto();
    $objHC = new HardCode();
    $objEjec = new BLEjecutor();
    
    $Fun = new Functions();
    $idUser = $Fun->__Request('idUser');
    $idTipo = $Fun->__Request('idTipoUser');
    // [t01_id_uni] => *
    // [t02_cod_proy] => *
    $row = $objMante->UsuarioSeleccionar($idUser);
    $lastOption = $row['t01_id_uni'];
    
    switch ($idTipo) {
        case $objHC->ME:
            // $rs = $objProy->ListaMonitorExterno('00',1);
            $rs = $objEjec->ListadoRespCargo(60);
            $Fun->llenarComboGroupI($rs, 'codigo', 'nombres', $lastOption, 'tipo');
            break;
        case $objHC->MT:
            $rs = $objProy->ListaMonitorTematico();
            $Fun->llenarComboI($rs, 'codigo', 'nombres', $lastOption);
            break;
        case $objHC->MF:
            $rs = $objProy->ListaMonitorFinanciero();
            $Fun->llenarComboI($rs, 'codigo', 'nombres', $lastOption);
            break;
        case $objHC->Ejec:
            $rs = $objEjec->ListaInstitucionesEjecutoras();
            $Fun->llenarComboI($rs, 't01_id_inst', 't01_sig_inst', $lastOption);
            
            break;
        
        case $objHC->CMT:
        case $objHC->AMT:
        // case $objHC->SE:
        case $objHC->CMF:
        case $objHC->ADM:
        case $objHC->EVA:
            
            $idCargo = '';
            if ($objHC->CMT == $idTipo) {
                $idCargo = 67;
            }
            if ($objHC->AMT == $idTipo) {
                $idCargo = 159;
            }
            
            // -------------------------------------------------->
            // DA 2.0 [02-11-2013 16:32]
            // Secretaria Ejecutiva no existira en el sistema el termino SE sera del Supervisor Externo
            // if($objHC->SE==$idTipo){$idCargo=66;}
            // --------------------------------------------------<
            
            if ($objHC->CMF == $idTipo) {
                $idCargo = 68;
            }
            if ($objHC->ADM == $idTipo) {
                $idCargo = 155;
            }
            if ($objHC->EVA == $idTipo) {
                $idCargo = 240;
            }
            
            $rs = $objProy->ListaPersonasFE_cargo($idCargo);
            $Fun->llenarComboI($rs, 'codigo', 'nombres', $lastOption);
            break;
        
        default:
            $rs = NULL;
    }
    
    $Fun = NULL;
    $objMante = NULL;
    $objProy = NULL;
    $objEjec = NULL;
}

// nd Region
?>

<?php ob_end_flush(); ?>