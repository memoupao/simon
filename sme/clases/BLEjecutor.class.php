<?php
require_once ("BLBase.class.php");

class BLEjecutor extends BLBase
{

    var $fecha;

    var $Session;

    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
    }
    // Esta funcion es muy importante, ya que las funciones dependen de la conexion
    function SetConexionID($ConexID)
    {
        $this->SetConection($ConexID);
    }

    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    
    // egion Ejecutores
    // egion Read Ejecutores
    function EjecutorListado()
    {
        $inst = $this->Session->UserID;
        if ($inst == '*' || $inst == '') {
            $inst = '0';
        }
        return $this->ExecuteProcedureReader("sp_lis_institucion", array(
            $inst
        ));
    }

    function EjecutorSeleccionar($idnst)
    {
        $iRs = $this->ExecuteProcedureReader("sp_get_institucion", array(
            $idnst
        ));
        $row = mysqli_fetch_assoc($iRs);
        return $row;
    }

    function EjecutorListadoFilter($likeName)
    {
        $idUser = $this->Session->UserID;
        if ($inst == '*' || $inst == '') {
            $inst = '0';
        }
        return $this->ExecuteProcedureReader("sp_lis_institucion_filter", array(
            $idUser,
            $likeName
        ));
    }

    function GetEjecutor($idnst)
    {
        $iRs = $this->ExecuteProcedureReader("sp_get_institucion", array(
            $idnst
        ));
        $row = mysqli_fetch_assoc($iRs);
        return $row;
    }

    function TipoIntsRelFE($idnst)
    {
        return $this->ExecuteProcedureReader("sp_lis_tipo_inst_rel_fe", array(
            $idnst
        ));
        ;
    }
    
    // ndRegion
    
    // egion CRUD Ejecutores
    function EjecutorNuevo(&$t01_id_inst)
    {
        $params = array(
            $_POST['t01_ruc_inst'],
            $_POST['t01_sig_inst'],
            $_POST['t01_nom_inst'],
            $this->ConvertDate($_POST['t01_fch_fund']),
            $_POST['t01_pres_anio'],
            $_POST['cbodpto'],
            $_POST['cboprov'],
            $_POST['cbodist'],
            $_POST['t01_dire_inst'],
            $_POST['t01_ciud_inst'],
            $_POST['t01_fono_inst'],
            $_POST['t01_fax_inst'],
            $_POST['t01_mail_inst'],
            $_POST['t01_web_inst'],
            $_POST['t01_ape_rep'],
            $_POST['t01_nom_rep'],
            $_POST['t01_carg_rep'],
            $_POST['t01_tipo_inst'],
            $_POST['t01_fon2_inst'],
            $_POST['t01_rpm_inst'],
            $_POST['t01_rpc_inst'],
            $_POST['t01_nex_inst'],
            implode(',', $_POST['chktipoinst']),
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_ins_institucion", $params);
        if ($ret['numrows'] > 0) {
            $t01_id_inst = $ret['codigo'];
            return true;
        } else {
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function EjecutorActualizar(&$t01_id_inst)
    {
        $params = array(
            $_POST['t01_id_inst'],
            $_POST['t01_ruc_inst'],
            $_POST['t01_sig_inst'],
            $_POST['t01_nom_inst'],
            $this->ConvertDate($_POST['t01_fch_fund']),
            $_POST['t01_pres_anio'],
            $_POST['cbodpto'],
            $_POST['cboprov'],
            $_POST['cbodist'],
            $_POST['t01_dire_inst'],
            $_POST['t01_ciud_inst'],
            $_POST['t01_fono_inst'],
            $_POST['t01_fax_inst'],
            $_POST['t01_mail_inst'],
            $_POST['t01_web_inst'],
            $_POST['t01_ape_rep'],
            $_POST['t01_nom_rep'],
            $_POST['t01_carg_rep'],
            $_POST['t01_tipo_inst'],
            $_POST['t01_fon2_inst'],
            $_POST['t01_rpm_inst'],
            $_POST['t01_rpc_inst'],
            $_POST['t01_nex_inst'],
            implode(',', $_POST['chktipoinst']),
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_institucion", $params);
        
        if ($ret['numrows'] > 0) {
            $t01_id_inst = $ret['codigo'];
            return true;
        } else {
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function EjecutorGuardarRelacion(&$t01_id_inst)
    {
        $params = array(
            $_POST['t01_id_inst'],
            implode(',', $_POST['chktipoinst']),
            $this->Session->UserID
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_relacioninstitucion", $params);
        
        if ($ret['numrows'] > 0) {
            $t01_id_inst = $ret['codigo'];
            return true;
        } else {
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }

    function EjecutorEliminar($t01_id_inst)
    {
        $params = array(
            $t01_id_inst
        );
        $ret = $this->ExecuteProcedureEscalar("sp_del_institucion", $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['msg'];
            }
            return false;
        }
    }
    // ndRegion
    function ListaInstitucionesEjecutoras()
    {
        return $this->ExecuteProcedureReader("sp_lis_ejecutores", NULL);
    }
    
    // ndRegion Ejecutores
    
    // egion Contactos
    // egion Read Contactos
    function ContactosListado($idInst)
    {
        return $this->ExecuteProcedureReader("sp_sel_contactos_inst", array(
            $idInst
        ));
    }
    // egion Read Contactos para reporte
    function ContactosListadoRep()
    {
        return $this->ExecuteProcedureReader("sp_rpt_contactos_inst", array());
    }

    function ContactosListadoRepFiltro($like)
    {
        return $this->ExecuteProcedureReader("sp_rpt_contactos_inst_filtro", array(
            $like
        ));
    }

    function ContactosListadoRepFiltrosAlt($inst, $proy, $cargo, $nombre, $region, $sector)
    {
        $params = array(
            $inst,
            $proy,
            $cargo,
            $nombre,
            $region,
            $sector
        );
        
        $SP = "sp_rpt_contactos_ins_alt";
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function ContactosSeleccionar($idInst, $id)
    {
        return $this->ExecuteProcedureEscalar("sp_get_contacto_inst", array(
            $idInst,
            $id
        ));
    }
    // ndRegion
    // egion CRUD Contactos
    function ContactoNuevo($t01_id_inst, $t01_dni_cto, $t01_ape_pat, $t01_ape_mat, $t01_nom_cto, $t01_fono_ofi, $t01_mail_cto, $t01_mail_cto2, $t01_cel_cto, $t01_cgo_cto, $t01_tel2_cto, $t01_rpm_cto, $t01_fax_cto, $t01_rpc_cto, $t01_nex_cto, $t01_sexo_cto)
    { /* Validaciones antes de grabar */
        $sql = "Select count(*)  from t01_inst_cto where t01_dni_cto='" . $t01_dni_cto . "'; ";
        if ($this->GetValue($sql) > 0) {
            $this->Error = "El Contacto de la Institucion con DNI\"" . $t01_dni_cto . "\" ya  fue registrada";
            return false;
        }
        
        $est_audi = '1';
        $t01_id_cto = $this->Autogenerate("t01_inst_cto", "t01_id_cto", "t01_id_inst='$t01_id_inst'");
        
        $arrayfields = array(
            't01_id_inst',
            't01_id_cto',
            't01_dni_cto',
            't01_ape_pat',
            't01_ape_mat',
            't01_nom_cto',
            't01_fono_ofi',
            't01_mail_cto',
            't01_mail_cto2',
            't01_cel_cto',
            't01_cgo_cto',
            'est_audi',
            'usr_crea',
            'fch_crea',
            't01_tel2_cto',
            't01_rpm_cto',
            't01_fax_cto',
            't01_rpc_cto',
            't01_nex_cto',
            't01_sexo'
        );
        
        $arrayvalues = array(
            $t01_id_inst,
            $t01_id_cto,
            $t01_dni_cto,
            $t01_ape_pat,
            $t01_ape_mat,
            $t01_nom_cto,
            $t01_fono_ofi,
            $t01_mail_cto,
            $t01_mail_cto2,
            $t01_cel_cto,
            $t01_cgo_cto,
            $est_audi,
            $this->Session->UserID,
            $this->fecha,
            $t01_tel2_cto,
            $t01_rpm_cto,
            $t01_fax_cto,
            $t01_rpc_cto,
            $t01_nex_cto,
            $t01_sexo_cto
        );
        
        $sql = $this->DBOBaseMySQL->createqueryInsert("t01_inst_cto", $arrayfields, $arrayvalues);
        
        return $this->ExecuteCreate($sql);
    }

    function ContactoActualizar($t01_id_inst, $t01_id_cto, $t01_dni_cto, $t01_ape_pat, $t01_ape_mat, $t01_nom_cto, $t01_fono_ofi, $t01_mail_cto, $t01_mail_cto2, $t01_cel_cto, $t01_cgo_cto, $t01_tel2_cto, $t01_rpm_cto, $t01_fax_cto, $t01_rpc_cto, $t01_nex_cto, $t01_sexo_cto)
    {
        /* Validaciones antes de Actualizar */
        $sql = "Select count(*)  from t01_inst_cto where t01_dni_cto='" . $t01_sig_inst . "' and t01_id_inst ='" . $t01_id_inst . "' and t01_id_cto <>'" . $t01_id_cto . "' ; ";
        if ($this->GetValue($sql) > 0) {
            $this->Error = "La Contacto de la Intitucion con DNI \"" . $t01_dni_cto . "\" se encuentra registrada, Comuniquese con el Administrador";
            return false;
        }
        
        $arrayfields = array(
            't01_id_inst',
            't01_id_cto',
            't01_dni_cto',
            't01_ape_pat',
            't01_ape_mat',
            't01_nom_cto',
            't01_fono_ofi',
            't01_mail_cto',
            't01_mail_cto2',
            't01_cel_cto',
            't01_cgo_cto',
            'usr_actu',
            'fch_actu',
            't01_tel2_cto',
            't01_rpm_cto',
            't01_fax_cto',
            't01_rpc_cto',
            't01_nex_cto',
            't01_sexo'
        );
        
        $arrayvalues = array(
            $t01_id_inst,
            $t01_id_cto,
            $t01_dni_cto,
            $t01_ape_pat,
            $t01_ape_mat,
            $t01_nom_cto,
            $t01_fono_ofi,
            $t01_mail_cto,
            $t01_mail_cto2,
            $t01_cel_cto,
            $t01_cgo_cto,
            $this->Session->UserID,
            $this->fecha,
            $t01_tel2_cto,
            $t01_rpm_cto,
            $t01_fax_cto,
            $t01_rpc_cto,
            $t01_nex_cto,
            $t01_sexo_cto
        );
        
        $where = "t01_id_inst='$t01_id_inst' and t01_id_cto='$t01_id_cto' ";
        
        $sql = $this->DBOBaseMySQL->createqueryUpdate("t01_inst_cto", $arrayfields, $arrayvalues, $where);
        return $this->ExecuteUpdate($sql);
    }

    function ContactoEliminar($t01_id_inst, $t01_id_cto)
    {
        $sql = "	DELETE from t01_inst_cto 
			WHERE t01_id_inst='$t01_id_inst' and t01_id_cto='$t01_id_cto';";
        return $this->ExecuteDelete($sql);
    }
    // ndRegion
    // ndRegion Contactos
    
    // egion Responsables Intitucion
    function ResponsableNuevo($t01_cod_repres, $t01_id_inst)
    {
        $params = array(
            $t01_id_inst,
            $t01_cod_repres,
            $this->Session->UserID
        );
        
        $SP = "sp_ins_resp_inst";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function ResponsableListado($idnst)
    {
        $SP = "sp_sel_res_inst";
        
        $params = array(
            $idnst
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        return $ret;
    }

    function contactosListME()
    {
        $SP = "sp_contactos";
        
        $params = array();
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        return $ret;
    }

    function ResponsableSeleccionar($id)
    {
        $sql = "	SELECT * FROM t01_rep_inst
WHERE t01_id_inst = '" . $id . "' ORDER BY t01_ape_rep ASC";
        
        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        return $row;
    }

    function ResponsableEliminar($idInst, $idCto)
    {
        $sql = "UPDATE t01_inst_cto
		SET t01_rep_flg = '0' 
		WHERE t01_id_inst = $idInst AND t01_id_cto = $idCto";
        
        $ok = $this->ExecuteDelete($sql);
        
        if ($ok) {
        	$sql = "SELECT COUNT(*) FROM t01_inst_cto WHERE t01_id_inst = $idInst AND t01_id_cto = $idCto AND t01_rep_flg=1 ";
        	$res = $this->Execute($sql);
        	$rw = mysql_fetch_array($res);
        	if ($rw[0] <= 0) {
        		$sql = "UPDATE t01_dir_inst SET est_audi = 0 WHERE t01_id_inst= $idInst";
        		$this->Execute($sql);
        	} 
        
        	
        }
        
        return $ok;
    }
    
    // ndRegion
    
    // egion Cuentas Bancarias de las Instituciones
    function CtaBancaria_Nuevo()
    {
        $params = array(
            $_POST['idinst'],
            $_POST['cbobanco'],
            $_POST['cbotipocta'],
            $_POST['txtnrocuenta'],
            $_POST['cbomoneda'],
            $this->Session->UserID
        );
        
        $SP = "sp_ins_ctas_inst";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    
    function CtaBancaria_Actualizar()
    {
    	$params = array(
    			$_POST['idInst'],
    			$_POST['idCta'],
    			$_POST['cbobanco'],
    			$_POST['cbotipocta'],
    			$_POST['txtnrocuenta'],
    			$_POST['cbomoneda'],
    			$this->Session->UserID
    	);
    
    	$ret = $this->ExecuteProcedureEscalar("sp_upd_ctas_inst", $params);
    
    	if ($ret['numrows'] > 0) {
    		return true;
    	} else {
    		$this->Error = $ret['msg'];
    		return false;
    	}
    }

    function ObtenerCuentaDefault($idnst)
    {
        return $this->ExecuteFunction("fn_cuenta_default", array(
            $idnst
        ));
    }

    function ListadoCuentas($idnst)
    {
        return $this->ExecuteProcedureReader("sp_lis_cuenta", array(
            $idnst
        ));
    }
    
    
    /**
     * Eliminar una Cuenta Bancaria que pertenece a una Instituci�n.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $t01_id_inst   Id de la Institucion.
     * @param string $idCuenta      Id de la Cuenta Bancaria.
     * @return resurce  Respuesta de recurso de la consulta.
     *
     */
    public function eliminarCuentaBancaria($t01_id_inst, $idCuenta)
    {
        $sql = "DELETE from t01_inst_ctas WHERE t01_id_inst='$t01_id_inst' and t01_id_cta='$idCuenta'";
        return $this->ExecuteDelete($sql);
    }
    
    

    function SeleccionarCuenta($idnst, $idcta)
    {
        $iRs = $this->ExecuteProcedureReader("sp_get_cuenta", array(
            $idnst,
            $idcta
        ));
        $row = mysqli_fetch_assoc($iRs);
        return $row;
    }
    // ndRegion
    function ListadoRespCargo($cargo)
    {
        $params = array(
            $cargo
        );
        $SP = "sp_sel_resp_cargo";
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        return $ret;
    }

    function Rpt_Instituciones_proyectos_cuentas($tipo, $concurso, $idinst)
    {
        $SP = "sp_rpt_inst_proy_ctas";
        $params = array(
            $concurso,
            $idinst,
            $tipo
        );
        $ret = $this->ExecuteProcedureReader($SP, $params);
        return $ret;
    }
    
    // fin de la Clase BLEjecutor
}

?>

