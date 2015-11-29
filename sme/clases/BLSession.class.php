<?php
require_once ("BLBase.class.php");

class BLSession extends BLBase
{

    var $UserID = "";

    var $UserName = "";

    var $PerfilID = "";

    var $PerfilName = "";

    var $CodProyecto = "";

    var $VerProyecto = 0;

    var $RowValidaPagina = NULL;

    var $IdInstitucion = - 1;

    var $Conectado = 0;

    var $MostrarBotonesAccesoDirecto = true;

    function __construct($ConexID = 0)
    {
        $this->SetConexionID($ConexID);
        try {
            error_reporting(0);
            $this->UserID = $_SESSION["UserID"];
            if ($this->UserID != "") {
                $this->ReloadUser($this->UserID);
            }
        } catch (Exception $ex) {
            return;
        }
        
        $this->ReloadAccessPage();
    }
    
    // Esta funcion es muy importante, ya que las funciones dependen de la conexion
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
    function Login($user, $pwd, $sessionid)
    {
        $SP = "sp_login";
        
        $params = array(
            $user,
            $pwd,
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_X_FORWARDED_FOR'],
            $sessionid
        );
        
        $rs = $this->ExecuteProcedureReader($SP, $params);
        
        if ($rs->num_rows > 0) {
            $row = mysqli_fetch_assoc($rs);
            $ret = true;
            $this->UserID = $row['coduser'];
            $this->PerfilID = $row['idperfil'];
            $this->PerfilName = $row["perfil"];
            $this->UserName = $row['nom_user'];
            $this->IdInstitucion = $row['t01_id_uni'];
            $this->CodProyecto = $row['t02_cod_proy'];
            $this->VerProyecto = $row['t02_version'];
            $sesion_anterior = $row['sesion_anterior'];
            $_SESSION['NumberIdAcceso'] = $row['idacceso'];
        } else {
            $ret = false;
        }
        
        $rs->free();
        
        if ($sesion_anterior != "" && $sesion_anterior != session_id()) {
            // damos de baja a la ultima sesion iniciada por el usuario
            $session_path = session_save_path();
            
            $directorio = opendir($session_path); // ruta actual
            while ($archivo = readdir($directorio))             // obtenemos un archivo y luego otro sucesivamente
            {
                if (! is_dir($archivo)) {
                    if ($archivo == "sess_" . $sesion_anterior) {
                        unlink($session_path . "/" . $archivo);
                        break;
                    }
                }
            }
            
            closedir($directorio);
        }
        return $ret;
    }

    function ReloadUser($user)
    {
        $ret = false;
        $SP = "sp_sel_usuario";
        $params = array(
            $user
        );
        $rs = $this->ExecuteProcedureReader($SP, $params);
        
        if ($rs->num_rows > 0) {
            $row = mysqli_fetch_assoc($rs);
            $ret = true;
            $this->UserID = $row['coduser'];
            $this->PerfilID = $row['idperfil'];
            $this->PerfilName = $row["perfil"];
            $this->UserName = $row['nom_user'];
            $this->IdInstitucion = $row['t01_id_uni'];
            $this->CodProyecto = $row['t02_cod_proy'];
            $this->VerProyecto = $row['t02_version'];
        }
        $rs->free();
        
        return $ret;
    }

    function Authorized(&$retmsg = "")
    {
        if ($this->UserID == "") {
            $pageInicio = $_SERVER['PHP_SELF'];
            if (strpos($pageInicio, '/default.php') > 0 || strpos($pageInicio, '/index.php') > 0) {
                $retmsg = "";
            } else {
                $retmsg = "Su Sesión ha Expirado ...  Vuelva a Iniciar Sesión ";
            }
            
            return false;
        } else {
            return true;
        }
    }

    function PWD($idUsr)
    {
        return $this->ExecuteFunction("fn_pwdusuario", array(
            $idUsr
        ));
    }

    function GetUsuario()
    {
        $params = array(
            $this->UserID
        );
        $ret = $this->ExecuteProcedureReader("sp_get_usuario", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function ReloadAccessPage()
    {
        $pageUrl = $_SERVER['PHP_SELF'];
        $perfil = $this->PerfilID;
        
        if ($perfil == "") {
            $perfil = 0;
        }
        
        $sql = "SELECT pag.mnu_cod, mnu.mnu_nomb, prf.per_cod, pag.pag_link, pag.pag_nomb, prf.ver, prf.nuevo, prf.editar, prf.eliminar
		  FROM adm_menus_pagina pag
		 INNER JOIN adm_menus mnu ON (pag.mnu_cod = mnu.mnu_cod)
		 INNER JOIN adm_menus_perfil prf ON (prf.mnu_cod=mnu.mnu_cod)
		 WHERE pag.flg_act='1'
		   AND prf.per_cod = " . $perfil . "
		   AND pag.pag_link = '" . $pageUrl . "'  ;";
        
        // echo($sql);
        
        $ConsultaID = $this->ExecuteQuery($sql);
        
        if ($ConsultaID) {
            $this->RowValidaPagina = mysql_fetch_assoc($ConsultaID);
        }
        return true;
    }

    function AuthorizedOpcion($param)
    {
        if ($this->RowValidaPagina == NULL) {
            return false;
        }
        if (count($this->RowValidaPagina) <= 0) {
            return false;
        }
        $acceso = $this->RowValidaPagina[strtolower($param)];
        if ($acceso == '0') {
            return false;
        } else {
            return true;
        }
    }

    function AuthorizedPage($param = 'VER')
    {
        return true;
        $msg = "";
        
        if ($this->RowValidaPagina != NULL) {
            if (count($this->RowValidaPagina) > 0) {
                $acceso = $this->RowValidaPagina[strtolower($param)];
                if ($acceso == '0') {
                    $stropcion = strtoupper(substr($param, 0, 1)) . strtolower(substr($param, 1));
                    $msg = "Usuario: " . $this->UserName . "\\n";
                    $msg .= "No tiene acceso en la Opción - " . $this->RowValidaPagina['mnu_nomb'] . " [" . $stropcion . "] \\n";
                    $msg .= "Comuníquese con el Administrador del Sistema";
                } else {
                    $msg = '';
                }
            } else {
                $msg = 'No tiene acceso en este Modulo';
            }
        } else {
            if ($this->PerfilID != 1) {
                $msg = 'No se han establecido los permisos para este modulo';
            }
        }
        
        if ($msg == '') {
            return true;
        } else {
            echo ("alert(\"" . $this->unicode_encode($msg) . "\"); \n");
            echo ("return false;");
            return false;
        }
    }

    function unicode_encode($texto)
    {
        $search = explode(",", "ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,ï¿½,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ï¿½Ã¡,ï¿½Ã©,ï¿½Ã­,ï¿½Ã³,ï¿½Ãº,ï¿½Ã±,&");
        $replace = explode(",", "\\u00e1,\\u00e9,\\u00ed,\\u00f3,\\u00fa,\\u00f1,\\u00c1,\\u00c9,\\u00cd,\\u00d3,\\u00da,\\u00d1,\\u00d1,\\u00e1,\\u00e9,\\u00ed,\\u00f3,\\u00fa,\\u00f1,\\u00c1,\\u00c9,\\u00cd,\\u00d3,\\u00da,\\u00d1,\\u00d1,&");
        return str_replace($search, $replace, $texto);
    }

    function UpdateCloseseSesion()
    { // Grabar la Hora en finaliza la Sesion
        $sql = "UPDATE adm_usuarios_accesos
			   SET fch_fin=NOW()
			 WHERE coduser='" . $this->UserID . "' AND idacceso='" . $_SESSION['NumberIdAcceso'] . "';";
        $this->Execute($sql);
        $_SESSION['NumberIdAcceso'] = NULL;
        return true;
    }

    function ListaUsuariosActivos()
    {
        return $this->ExecuteProcedureReader("sp_sel_usuarios_activos", NULL);
    }

    function MaxVersionProy($CodProy)
    {
        return $this->GetValue("SELECT MAX(t02_version) FROM t02_dg_proy WHERE t02_cod_proy='" . $CodProy . "' ;");
    }

    function VerifyProyecto()
    {
        if (! empty($_GET['txtCodProy'])) {
            $this->CodProyecto = $_GET['txtCodProy'];
            $this->VerProyecto = $_GET['cboversion'];
        }
        
        if (! empty($_POST['txtCodProy'])) {
            $this->CodProyecto = $_POST['txtCodProy'];
            $this->VerProyecto = $_POST['cboversion'];
        }
        
        if (isset($_GET['proyecto'])) {
            $this->CodProyecto = $_GET['proyecto'];
            $this->VerProyecto = $_GET['version'];
        }
        
        if (isset($_REQUEST['idProy'])) {
            $this->CodProyecto = $_REQUEST['idProy'];
            $this->VerProyecto = $_REQUEST['idVersion'];
        }
        
        if ($_SESSION["idProy"] != "" && ($this->CodProyecto == "" || $this->CodProyecto == "*")) {
            $this->CodProyecto = $_SESSION["idProy"];
        }
        
        $nnver = $this->MaxVersionProy($this->CodProyecto);
        
        if ($this->CodProyecto != "") {
            $_SESSION["idProy"] = $this->CodProyecto;
            if (empty($this->VerProyecto)) {
                $this->VerProyecto = $nnver;
            }
        }
        
        if ($this->VerProyecto > $nnver) {
            $this->VerProyecto = $nnver;
        }
        
        return;
    }

    function ActualizaParamProyecto()
    {
        $sql = "UPDATE adm_parametros SET t01_cod_prog ='$this->CodProyecto' WHERE idparam=1;";
        $this->ExecuteQuery($sql);
    }

    function ListaMenus($Admin, $perfil)
    {
        $SP = "sp_sel_menus_perfil";
        $params = array(
            $Admin,
            '',
            $perfil
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function ListaMenusItems($Admin, $MenuPadre, $perfil)
    {
        $SP = "sp_sel_menus_perfil";
        $params = array(
            $Admin,
            $MenuPadre,
            $perfil
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function NumeroSubItems($MenuPadre)
    {
        return $this->GetValue("SELECT count(*) FROM adm_menus WHERE mnu_activo='1' AND mnu_parent = '" . $MenuPadre . "';");
    }

    function ListaBotonesAccesoDirecto($perfil)
    {
        $params = array(
            $perfil
        );
        return $this->ExecuteProcedureReader("sp_sel_acc_directo_perfil", $params);
    }

    function VerificarUsuarioForo($user)
    {
        $rs = $this->ExecuteProcedureReader("sp_get_usuario_foro", array(
            $user
        ));
        
        if ($rs->num_rows > 0) {
            $row = mysqli_fetch_assoc($rs);
            $rs->free();
            return $row;
        } else {
            return NULL;
        }
    }
    
    // fin de la Clase BLSession
}

?>
