<?php
require_once ("BLBase.class.php");

class BLEquipo extends BLBase
{

    var $fecha;

    var $Session;
    // -----------------------------------------------------------------------------
    function __construct()
    {
        $this->fecha = date("Y-m-d H:i:s", time());
        $this->Session = $_SESSION['ObjSession'];
        $this->SetConexionID($this->Session->GetConection()->Conexion_ID);
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
    
    // egion Equipo Ejecutor
    // egion Read Contactos
    function ContactosListado($idProy)
    {
        $sql = " SELECT DISTINCT equi.t02_cod_proy, equi.t04_id_equi, equi.t04_dni_equi,
			   CONCAT(equi.t04_ape_pat, ' ', equi.  t04_ape_mat , ', ' , equi.t04_nom_equi ) AS nombres,
			   IFNULL(equi.t04_telf_equi,'') AS t04_telf_equi, IFNULL(equi.t04_mail_equi,'') AS t04_mail_equi,			   
			   p.t03_nom_per AS cargo, 
			   equi.t04_estado AS cod_estado,
			   ta.descrip AS estado,
			   equi.usr_crea			   
		  FROM t04_equi_proy equi
		  LEFT JOIN adm_tablas_aux t ON (equi.t04_carg_equi=t.codi AND equi.t04_estado=t.codi)
		  LEFT JOIN adm_tablas_aux ta ON (equi.t04_estado=ta.codi)
		  LEFT JOIN t03_mp_per p ON (p.t03_id_per=equi.t04_carg_equi AND equi.t02_cod_proy=p.t02_cod_proy)
		  WHERE equi.t02_cod_proy = '$idProy';";
        
        return $this->ExecuteQuery($sql);
    }

    function ContactosSeleccionar($idProy, $id)
    {
        $sql = " SELECT t02_cod_proy, t04_id_equi, t04_dni_equi, t04_ape_pat, t04_ape_mat, t04_nom_equi, t04_sexo_equi, t04_edad_equi, t04_cali_equi, t04_mail_equi, t04_telf_equi, t04_carg_equi, t04_esp_equi, DATE_FORMAT(t04_fec_ini,'%d/%m/%Y') AS t04_fec_ini , DATE_FORMAT(t04_fec_ter,'%d/%m/%Y') AS t04_fec_ter ,t04_func_equi, t04_exp_lab, t04_estado, usr_crea, fch_crea, usr_actu, fch_actu, est_audi, t04_cel_equi, t04_esp_otro  
		 FROM t04_equi_proy 
		 WHERE t02_cod_proy = '$idProy' and t04_id_equi='$id' ;";
        
        // echo("<pre>".$sql."</pre>");
        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        return $row;
    }
    // ndRegion
    // egion CRUD Contactos
    function ContactoNuevo($t02_cod_proy, $t04_id_equi, $t04_dni_equi, $t04_ape_pat, $t04_ape_mat, $t04_nom_equi, $t04_sexo_equi, $t04_edad_equi, $t04_cali_equi, $t04_telf_equi, $t04_cel_equi, $t04_mail_equi, $t04_exp_lab, $t04_func_equi, $t04_carg_equi, $t04_fec_ini, $t04_fec_ter, $t04_especialidad, $t04_estado, $t04_especialidad_otros)
    {
        $params = array(
            $t02_cod_proy,
            $t04_id_equi,
            $t04_dni_equi,
            $t04_ape_pat,
            $t04_ape_mat,
            $t04_nom_equi,
            $t04_sexo_equi,
            $t04_edad_equi,
            $t04_cali_equi,
            $t04_telf_equi,
            $t04_cel_equi,
            $t04_mail_equi,
            $t04_exp_lab,
            $t04_func_equi,
            $t04_carg_equi,
            $t04_especialidad,
            $this->ConvertDate($t04_fec_ini),
            $this->ConvertDate($t04_fec_ter),
            $t04_estado,
            $this->Session->UserID,
            $this->fecha,
            $est_audi,
            $t04_especialidad_otros
        );
        
        $SP = "sp_ins_equi_proy";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = ($ret['msg'] == '' ? $this->Error : $ret['msg']);
            return false;
        }
    }

    function ContactoActualizar($t02_cod_proy, $t04_id_equi, $t04_dni_equi, $t04_ape_pat, $t04_ape_mat, $t04_nom_equi, $t04_sexo_equi, $t04_edad_equi, $t04_cali_equi, $t04_telf_equi, $t04_cel_equi, $t04_mail_equi, $t04_exp_lab, $t04_func_equi, $t04_carg_equi, $t04_fec_ini, $t04_fec_ter, $t04_especialidad, $t04_estado, $t04_especialidad_otros)
    {
        $params = array(
            $t02_cod_proy,
            $t04_id_equi,
            $t04_dni_equi,
            $t04_ape_pat,
            $t04_ape_mat,
            $t04_nom_equi,
            $t04_sexo_equi,
            $t04_edad_equi,
            $t04_cali_equi,
            $t04_telf_equi,
            $t04_cel_equi,
            $t04_mail_equi,
            $t04_exp_lab,
            $t04_func_equi,
            $t04_carg_equi,
            $t04_especialidad,
            $this->ConvertDate($t04_fec_ini),
            $this->ConvertDate($t04_fec_ter),
            $t04_estado,
            $this->Session->UserID,
            $this->fecha,
            $est_audi,
            $t04_especialidad_otros
        );
        
        $SP = "sp_upd_equi_proy";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function ContactoEliminar($t02_cod_proy, $t04_id_equi)
    {
        $sql = "	DELETE from t04_equi_proy 
			WHERE t02_cod_proy='$t02_cod_proy' and t04_id_equi='$t04_id_equi';";
        return $this->ExecuteDelete($sql);
    }
    // ndRegion
    
    // egion Cambio de Personal
    function CambioPersonal_Listado($t02_cod_proy)
    {
        return $this->ExecuteProcedureReader("sp_sel_cambio_personal", array(
            $t02_cod_proy
        ));
    }

    function CambioPersonal_Seleccionar($idProy, $idSol)
    {
        return $this->ExecuteProcedureEscalar("sp_get_cambio_personal", array(
            $idProy,
            $idSol
        ));
    }

    function CambioPersonal_SeleccionarAll()
    {
        $ret = $this->ExecuteProcedureReader("sp_get_cambio_personal_all", array());
        return $ret;
    }

    function CambioPersonal_GetPartida($idProy, $idCargo)
    {
        return $this->ExecuteProcedureEscalar("sp_get_partida_cambio_personal", array(
            $idProy,
            $idCargo
        ));
    }

    function CambioPersonal_Nuevo()
    {
        require_once ("UploadFiles.class.php");
        require_once ("HardCode.class.php");
        
        $adjCV = new UploadFiles('txtFileUploadCV');
        $adjSol = new UploadFiles('txtFileUploadSCP');
        
        $arrFilesRequired = array(
            "doc",
            "pdf",
            "docx"
        );
        
        if (! $adjSol->ValidateExt($arrFilesRequired)) {
            $this->Error = "El Archivo de Solicitud de Cambio de Personal, no es valido !!!";
            return false;
        }
        
        $nomFileSol = 'SCP_' . $_POST['t02_cod_proy'] . '_' . $_POST['t04_dni_equi'] . '.' . $adjSol->getExtension();
        
        if (! $adjCV->ValidateExt($arrFilesRequired)) {
            $this->Error = "El Archivo del Campo CV, no es valido !!!";
            return false;
        }
        $NomFileCV = 'CV_' . $_POST['t02_cod_proy'] . '_' . $_POST['t04_dni_equi'] . '.' . $adjCV->getExtension();
        
        // $_POST['t04_num_soli']='',
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t04_dni_equi'],
            $_POST['t04_ape_pat'],
            $_POST['t04_ape_mat'],
            $_POST['t04_nom_equi'],
            $_POST['t04_sexo_equi'],
            $_POST['t04_edad_equi'],
            $_POST['t04_cali_equi'],
            $_POST['t04_telf_equi'],
            $_POST['t04_cel_equi'],
            $_POST['t04_mail_equi'],
            $_POST['t04_exp_lab'],
            $_POST['t04_func_equi'],
            $_POST['t04_especialidad'],
            $NomFileCV,
            $nomFileSol,
            $_POST['txtidEquipoAntes'],
            $_POST['txtsaldopartida'],
            $_POST['txtComentarios'],
            $this->Session->UserID,
            date("Y-m-d")
        );
        
        $SP = "sp_ins_cambio_personal";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $HC = new HardCode();
            
            $adjSol->DirUpload .= $HC->FolderUploadSolicitudCP;
            $adjSol->SavesAs($nomFileSol);
            
            $adjCV->DirUpload .= $HC->FolderUploadSolicitudCP;
            $adjCV->SavesAs($NomFileCV);
            return true;
        } else {
            $this->Error = ($ret['msg'] == '' ? $this->Error : $ret['msg']);
            return false;
        }
    }

    function CambioPersonal_Actualizar()
    {
        require_once ("UploadFiles.class.php");
        require_once ("HardCode.class.php");
        
        $adjCV = new UploadFiles('txtFileUploadCV');
        $adjSol = new UploadFiles('txtFileUploadSCP');
        
        $arrFilesRequired = array(
            "doc",
            "pdf",
            "docx"
        );
        
        if ($adjSol->FileName != "") {
            if (! $adjSol->ValidateExt($arrFilesRequired)) {
                $this->Error = "El Archivo de Solicitud de Cambio de Personal, no es valido !!!";
                return false;
            }
            $nomFileSol = 'SCP_' . $_POST['t02_cod_proy'] . '_' . $_POST['t04_dni_equi'] . '.' . $adjSol->getExtension();
        }
        
        if ($adjCV->FileName != "") {
            if (! $adjCV->ValidateExt($arrFilesRequired)) {
                $this->Error = "El Archivo del Campo CV, no es valido !!!";
                return false;
            }
            $NomFileCV = 'CV_' . $_POST['t02_cod_proy'] . '_' . $_POST['t04_dni_equi'] . '.' . $adjCV->getExtension();
        }
        
        // $_POST['t04_num_soli']='',
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t04_num_soli'],
            $_POST['t04_dni_equi'],
            $_POST['t04_ape_pat'],
            $_POST['t04_ape_mat'],
            $_POST['t04_nom_equi'],
            $_POST['t04_sexo_equi'],
            $_POST['t04_edad_equi'],
            $_POST['t04_cali_equi'],
            $_POST['t04_telf_equi'],
            $_POST['t04_cel_equi'],
            $_POST['t04_mail_equi'],
            $_POST['t04_exp_lab'],
            $_POST['t04_func_equi'],
            $_POST['t04_especialidad'],
            $NomFileCV,
            $nomFileSol,
            $_POST['txtidEquipoAntes'],
            $_POST['txtsaldopartida'],
            // $_POST['cboPartida'],
            $_POST['txtComentarios'],
            $_POST['chkAprobarMT'],
            $_POST['chkAprobarMF'],
            $_POST['chkAprobarCMT'],
            $_POST['chkAprobarCMF'],
            $_POST['chkAprobar'],
            $_POST['txtrespuesta'],
            $this->Session->UserID,
            date("Y-m-d")
        );
        $SP = "sp_upd_cambio_personal";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) {
            $HC = new HardCode();
            
            $adjSol->DirUpload .= $HC->FolderUploadSolicitudCP;
            $adjSol->SavesAs($nomFileSol);
            
            $adjCV->DirUpload .= $HC->FolderUploadSolicitudCP;
            $adjCV->SavesAs($NomFileCV);
            return true;
        } else {
            $this->Error = ($ret['msg'] == '' ? $this->Error : $ret['msg']);
            return false;
        }
    }

    function EliminarSolicitudPer($idProy, $idSol)
    {
        $SP = "sp_del_cambio_personal";
        $ret = $this->ExecuteProcedureEscalar($SP, array(
            $idProy,
            $idSol
        ));
        if ($ret['numrows'] > 0) {
            $url1 = $ret['url1'];
            $url2 = $ret['url2'];
            $pathurl1 = constant('APP_PATH') . "sme/proyectos/anexos/CP/" . $url1;
            $pathurl2 = constant('APP_PATH') . "sme/proyectos/anexos/CP/" . $url2;
            
            if (file_exists($pathurl1)) {
                unlink($pathurl1);
            }
            if (file_exists($pathurl2)) {
                unlink($pathurl2);
            }
            return true;
        } else {
            return false;
        }
    }
    
    // nd Region
    function ListaGralEquipo($t02_cod_proy)
    {
        return $this->ExecuteProcedureReader("sp_sel_equi_proy", array(
            $t02_cod_proy
        ));
    }
    // ndRegion Equipo ejecutor
    
    // fin de la Clase BLEjecutor
}

?>

