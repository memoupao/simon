<?php
require_once ("BLBase.class.php");

class BLMantenimiento extends BLBase
{

    var $fecha;

    var $Session;
    // -----------------------------------------------------------------------------
    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        if ($this->Session != NULL) {
            $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
        }
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
    
    // egion Listados
    function ListaPerfiles()
    {
        return $this->ExecuteProcedureReader("sp_sel_perfiles", NULL);
    }

    function ListaConcursos()
    {
        return $this->ExecuteProcedureReader("sp_sel_concursos", NULL);
    }

    function ListaUsuarios($Perfil)
    {
        $params = array(
            $Perfil
        );
        return $this->ExecuteProcedureReader("sp_sel_usuarios", $params);
    }

    function ListaMenus($Modulo)
    {
        $params = array(
            $Modulo
        );
        return $this->ExecuteProcedureReader("sp_sel_adm_menus", $params);
    }

    function ListaMenusContenedores($Modulo)
    {
        $params = array(
            $Modulo
        );
        return $this->ExecuteProcedureReader("sp_sel_adm_menus_contenedor", $params);
    }

    function ListaMenusPagina($idMenu)
    {
        $params = array(
            $idMenu
        );
        return $this->ExecuteProcedureReader("sp_sel_menus_pagina", $params);
    }
    /*
     * function ListaMenusContenedores ($Modulo) { $params = array($Modulo); return $this->ExecuteProcedureReader("sp_sel_adm_menus_contenedor", $params); }
     */
    
    // ndRegion
    
    // egion Usuarios
    function UsuarioSeleccionar($idUser)
    {
        $params = array(
            $idUser
        );
        $ret = $this->ExecuteProcedureReader("sp_get_usuario", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function UsuarioNuevo()
    {
        $params = array(
            $_POST['coduser'],
            $_POST['clave_user1'],
            $_POST['nom_user'],
            $_POST['mail'],
            $_POST['tipo_user'],
            $_POST['t01_id_inst'],
            $_POST['t02_cod_proy'],
            $_POST['chkActivo'],
            $this->Session->UserID
        );
        $SP = "sp_ins_usuarios";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function UsuarioActualizar()
    {
        $SP = "sp_upd_usuarios";
        $params = array(
            $_POST['coduser'],
            $_POST['nom_user'],
            $_POST['mail'],
            $_POST['tipo_user'],
            $_POST['t01_id_inst'],
            $_POST['t02_cod_proy'],
            $_POST['chkActivo'],
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function UsuarioEliminar($idUser)
    {
        $SP = "sp_del_usuario";
        $params = array(
            $idUser
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function UsuarioCambiarPwd($usr, $newpwd)
    {
        $SP = "sp_upd_usuarios_pwd";
        $params = array(
            $usr,
            $newpwd,
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    // ndRegion Usuarios
    
    // egion Concursos
    function ConcursoSeleccionar($idConcurso)
    {
        $params = array(
            $idConcurso
        );
        $ret = $this->ExecuteProcedureReader("sp_get_concursos", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function ConcursoNuevo()
    {
        $params = array(
            $_POST['txtanio'],
            $_POST['txtnombre'],
            $_POST['txtabreviado'],
            $_POST['txtcomentario'],
            $this->Session->UserID
        );
        $SP = "sp_ins_concurso";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            // -------------------------------------------------->
            // DA 2.0 [07-11-2013 10:48]
            // Registro de las tasas del concurso
            $totLineas = (int) $_POST['totLineas'];
            $num_conc = $ret['codigo'];
            for ($i=1; $i<=$totLineas; $i++) {
                $cod_linea = mysql_real_escape_string($_POST['linea_'.$i]);
                $porc_gast_func = mysql_real_escape_string($_POST['tfun_'.$i]);
                $porc_linea_base = mysql_real_escape_string($_POST['tlib_'.$i]);
                $porc_imprev = mysql_real_escape_string($_POST['timp_'.$i]);
                $porc_gast_superv_proy = mysql_real_escape_string($_POST['tgsp_'.$i]);
            
                $sql = 'insert into adm_concursos_tasas (num_conc, cod_linea, porc_gast_func, porc_linea_base, porc_imprev, porc_gast_superv_proy, fec_crea, usr_crea )
                values("'.$num_conc.'", "'.$cod_linea.'", "'.$porc_gast_func.'", "'.$porc_linea_base.'", "'.$porc_imprev.'", "'.$porc_gast_superv_proy.'", NOW(), "'.$this->Session->UserID.'")
                on duplicate key
                update
                    porc_gast_func=values(porc_gast_func),
                    porc_linea_base=values(porc_linea_base),
                    porc_imprev=values(porc_imprev),
                    porc_gast_superv_proy=values(porc_gast_superv_proy),
                    fec_actu=values(fec_actu),
                    usr_actu=values(usr_actu)';
                $retorno = $this->Execute($sql);                            
            }
            // --------------------------------------------------<
            
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function ConcursoActualizar()
    {
        $params = array(
            $_POST['txtnumero'],
            $_POST['txtanio'],
            $_POST['txtnombre'],
            $_POST['txtabreviado'],
            $_POST['txtcomentario'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar('sp_upd_concurso', $params);
        if ($ret['numrows'] > 0) {
            // -------------------------------------------------->
            // DA 2.0 [07-11-2013 10:48]
            // Actualizacion de las tasas del concurso
            $totLineas = (int) $_POST['totLineas'];
            $num_conc = mysql_real_escape_string($_POST['txtnumero']);
            for ($i=1; $i<=$totLineas; $i++) {
                $cod_linea = mysql_real_escape_string($_POST['linea_'.$i]);
                $porc_gast_func = mysql_real_escape_string($_POST['tfun_'.$i]);
                $porc_linea_base = mysql_real_escape_string($_POST['tlib_'.$i]);
                $porc_imprev = mysql_real_escape_string($_POST['timp_'.$i]);
                $porc_gast_superv_proy = mysql_real_escape_string($_POST['tgsp_'.$i]);
                
                $sql = 'insert into adm_concursos_tasas (num_conc, cod_linea, porc_gast_func, porc_linea_base, porc_imprev, porc_gast_superv_proy, fec_actu, usr_actu )
                values("'.$num_conc.'", "'.$cod_linea.'", "'.$porc_gast_func.'", "'.$porc_linea_base.'", "'.$porc_imprev.'", "'.$porc_gast_superv_proy.'", NOW(), "'.$this->Session->UserID.'")  
                on duplicate key 
                update 
                    porc_gast_func=values(porc_gast_func),
                    porc_linea_base=values(porc_linea_base),
                    porc_imprev=values(porc_imprev),
                    porc_gast_superv_proy=values(porc_gast_superv_proy),
                    fec_actu=values(fec_actu),
                    usr_actu=values(usr_actu)';
                $retorno = $this->Execute($sql);
            } 
            // --------------------------------------------------<
            return true;
            
            
        } else {
            return false;
        }
    }

    function ConcursoEliminar($idConcurso)
    {
        $SP = "sp_del_concurso";
        $params = array(
            $idConcurso
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            // -------------------------------------------------->
            // DA 2.0 [07-11-2013 10:48]
            // Eliminacion de las tasas del concurso
            $idConcurso = mysql_real_escape_string($idConcurso);
            $sql = 'DELETE FROM adm_concursos_tasas WHERE num_conc = "'.$idConcurso.'"';
            $retorno = $this->Execute($sql);
            // --------------------------------------------------<
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    // nd Region Concursos
    
    // egion Perfiles
    function PerfilSeleccionar($idPerfil)
    {
        $params = array(
            $idPerfil
        );
        $ret = $this->ExecuteProcedureReader("sp_get_perfil", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function PerfilNuevo()
    {
        $params = array(
            $_POST['txtnombre'],
            $_POST['txtabreviado'],
            $this->Session->UserID
        );
        $SP = "sp_ins_perfil";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function PerfilActualizar()
    {
        $params = array(
            $_POST['txtid'],
            $_POST['txtnombre'],
            $_POST['txtabreviado'],
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar('sp_upd_perfil', $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function PerfilEliminar($idPerfil)
    {
        $SP = "sp_del_perfil";
        $params = array(
            $idPerfil
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    // ndRegion Perfiles
    
    // egion Menus y Accesos Directos
    function MenuSeleccionar($idMenu)
    {
        $params = array(
            $idMenu
        );
        $ret = $this->ExecuteProcedureReader("sp_get_menu", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function MenuNuevo()
    {
        $params = array(
            $_POST['txtcodigo'],
            $_POST['txtnombre'],
            $_POST['txtlink'],
            $_POST['chkcontainer'],
            $_POST['cboparent'],
            $_POST['cbotarget'],
            $_POST['txtimage'],
            $_POST['chkactivo'],
            $_POST['txtsort'],
            $_POST['txtmodulo']
        );
        $SP = "sp_ins_menu";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function MenuActualizar()
    {
        $params = array(
            $_POST['txtcodigo'],
            $_POST['txtnombre'],
            $_POST['txtlink'],
            $_POST['chkcontainer'],
            $_POST['cboparent'],
            $_POST['cbotarget'],
            $_POST['txtimage'],
            $_POST['chkactivo'],
            $_POST['txtsort'],
            $_POST['txtmodulo']
        );
        $ret = $this->ExecuteProcedureEscalar('sp_upd_menu', $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function MenuEliminar($idMenu)
    {
        $SP = "sp_del_menu";
        $params = array(
            $idMenu
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function MenuAgregarPagina($idmenu, $nombre, $link)
    {
        $params = array(
            $idmenu,
            $nombre,
            $link
        );
        
        $SP = "sp_ins_menu_pagina";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function MenuEliminaPagina($idmenu, $idpaginas)
    {
        $params = array(
            $idmenu,
            $idpaginas
        );
        
        $SP = "sp_del_menu_pagina";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    
    // ndRegion Menus y Accesos Directos
    function MenuPerfil_Actualizar()
    {
        $perfil = $_POST['cboPerfil'];
        $arrMenus = $_POST['chkMenu'];
        
        $arrAccesos = NULL;
        
        for ($x = 0; $x < count($arrMenus); $x ++) {
            $arr_acc_mnu = array(
                abs($_POST[$arrMenus[$x] . "_ver"]),
                abs($_POST[$arrMenus[$x] . "_nuevo"]),
                abs($_POST[$arrMenus[$x] . "_editar"]),
                abs($_POST[$arrMenus[$x] . "_eliminar"])
            );
            $arrAccesos[$x] = implode("-", $arr_acc_mnu);
        }
        
        $params = array(
            $perfil,
            implode("|", $arrMenus),
            implode("|", $arrAccesos),
            $this->Session->UserID
        );
        $ret = $this->ExecuteProcedureEscalar('sp_upd_menu_perfil', $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function NuevoCaserio($dep, $prov, $dist, $caserio, &$id)
    {
        $params = array(
            $dep,
            $prov,
            $dist,
            $caserio,
            $this->Session->UserID
        );
        
        $SP = "sp_ins_caserio";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            $id = $ret['codigo'];
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    
    // ndRegion
    // fin de la Clase BLUsuario
    
    // --------------------------------------------------------------------
    // Version 2
    // --------------------------------------------------------------------
    function listar($concepto)
    {
        $params = array(
            $concepto
        );
        return $this->ExecuteProcedureReader("sp_sel_concepto", $params);
    }

    function seleccionar($concepto, $id)
    {
        $params = array(
            $concepto,
            $id
        );
        $ret = $this->ExecuteProcedureReader("sp_get_concepto", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function guardar($concepto)
    {
        // -------------------------------------------------->
        // DA 2.0 [22-10-2013 18:53]
        // eliminado del utf8_encode ya que la coneccion es UTF8 por defecto.
        $params = array(
            $concepto,
            $_POST['nombre'],
            $_POST['abreviatura'],
            $this->Session->UserID
        );
        // --------------------------------------------------<
        
        $ret = $this->ExecuteProcedureEscalar("sp_ins_concepto", $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function actualizar($concepto)
    {
        // -------------------------------------------------->
        // DA 2.0 [22-10-2013 18:53]
        // eliminado del utf8_encode ya que la coneccion es UTF8 por defecto.
        $params = array(
            $concepto,
            $_POST['numero'],
            $_POST['nombre'],
            $_POST['abreviatura'],
            $this->Session->UserID
        );
        // --------------------------------------------------<
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_concepto", $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function eliminar($concepto, $id)
    {
        $params = array(
            $concepto,
            $id
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_del_concepto", $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    
    /**
     * Obtener listado de tasas de Lineas por Concurso
     *
     * @author AD
     * @since Version 2.0
     * @access public
     * @param string $num_conc Numero del concurso.
     * @return resource Resultado de consulta.
     *
     */
    public function getListTasasPorConcurso($num_conc = null)
    {
        if ($num_conc) {
            $num_conc = mysql_real_escape_string($num_conc);
            $sql = 'SELECT l.t00_cod_linea as codigo, l.t00_nom_abre as abrev, l.t00_nom_lar as nombre,
                ct.porc_gast_func, ct.porc_imprev, ct.porc_gast_superv_proy, ct.porc_linea_base
                FROM t00_linea as l, adm_concursos_tasas as ct
                WHERE  l.t00_cod_linea!=0 AND l.t00_cod_linea = ct.cod_linea AND ct.num_conc = "'.$num_conc.'"
                ORDER BY codigo ASC';
            $ConsultaID = $this->ExecuteQuery($sql);
            return $ConsultaID;
        } else {
            $sql = 'SELECT l.t00_cod_linea as codigo, l.t00_nom_abre as abrev, l.t00_nom_lar as nombre                
                FROM t00_linea as l 
                WHERE  l.t00_cod_linea!=0 
                ORDER BY codigo ASC';
            $ConsultaID = $this->ExecuteQuery($sql);
            return $ConsultaID;
        }
        
        
    }
    
}
