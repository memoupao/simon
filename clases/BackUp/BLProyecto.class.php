<?php
require_once ("BLBase.class.php");

class BLProyecto extends BLBase
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
    // -----------------------------------------------------------------------------
    function Dispose()
    {
        $this->Destroy();
    }
    // -----------------------------------------------------------------------------
    // egion Proyectos
    // egion Read Contactos
    function MaxVersion($CodProy)
    {
        return $this->GetValue("SELECT MAX(t02_version) FROM t02_dg_proy WHERE t02_cod_proy='" . $CodProy . "' ;");
    }

    function ProyectosListado($idInst)
    {
        $sql = " SELECT
		  inst.t01_sig_inst as ejecutor,
		  proy.t02_cod_proy as codigo,
		  proy.t02_nro_exp as exp,
		  proy.t02_version as vs,
		  proy.t02_nom_proy as nombre,
		  DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') as inicio,
		  DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') as termino,
		  proy.t02_fch_apro,
		  proy.t01_id_inst,
		  proy.t02_fin,
		  proy.t02_pro,
		  proy.t02_amb_geo,
		  proy.t02_ben_obj,
		  proy.t02_pres_tot,
		  /*  proy.t02_pres_fe,  proy.t02_pres_otro,   proy.t02_pres_tot,   proy.t02_moni_tema,   proy.t02_moni_fina,
		  proy.t02_sup_inst,   proy.t02_moni_ext, 	  proy.t02_dire_proy,   proy.t02_ciud_proy,   proy.t02_fax_proy,
		  proy.t02_tele_proy,  proy.t02_mail_proy, 	  proy.t02_estado, 	  proy.t02_line_base, 	  proy.t02_est_imp, */
		  proy.usr_crea,
		  proy.fch_crea
		FROM
		  t02_dg_proy proy
		LEFT JOIN t01_dir_inst inst on (proy.t01_id_inst=inst.t01_id_inst)
		WHERE proy.t02_version = (SELECT max(v.t02_version) from t02_dg_proy v where v.t02_cod_proy=proy.t02_cod_proy)
		  and (CASE WHEN '" . $idInst . "'='*' THEN proy.t01_id_inst ELSE '" . $idInst . "' END ) = proy.t01_id_inst  ;";

        return $this->ExecuteQuery($sql);
    }

    function ProyectosPopup($idInst)
    {
        $sql = " SELECT
		  inst.t01_sig_inst as ejecutor,
		  proy.t02_cod_proy as codigo,
		  proy.t02_nro_exp as exp,
		  proy.t02_version as vs,
		  proy.t02_nom_proy as nombre,
		  DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') as inicio,
		  DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') as termino,
		  proy.t01_id_inst,
		  inst.t01_nom_inst as nomejecutor,
		  proy.usr_crea
		FROM	  t02_dg_proy proy
		LEFT JOIN t01_dir_inst inst on (proy.t01_id_inst=inst.t01_id_inst)
		WHERE proy.t02_version = (SELECT max(v.t02_version) from t02_dg_proy v where v.t02_cod_proy=proy.t02_cod_proy)
		  and (CASE WHEN '" . $idInst . "'='*' THEN proy.t01_id_inst ELSE '" . $idInst . "' END ) = proy.t01_id_inst  ;";

        return $this->ExecuteQuery($sql);
    }

    function GetProyecto($idProy, $version)
    {
        $sql = " SELECT
		  inst.t01_sig_inst ,
		  proy.t02_cod_proy ,
		  proy.t02_nro_exp ,
		  proy.t02_version ,
		  proy.t02_nom_proy ,
		  proy.t01_id_inst,
		  inst.t01_nom_inst ,
		  proy.t02_fin,
		  proy.t02_pro,
		  proy.usr_crea,
		  (CASE TRUNCATE((DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365),0) WHEN (DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365) THEN TRUNCATE((DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365),0) ELSE TRUNCATE((DATEDIFF(proy.t02_fch_ter, proy.t02_fch_ini) / 365),0) +1 END ) as duracion
		FROM	  t02_dg_proy proy
		LEFT JOIN t01_dir_inst inst on (proy.t01_id_inst=inst.t01_id_inst)
		WHERE proy.t02_cod_proy = '$idProy' AND proy.t02_version = '$version' ;";

        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        return $row;
    }

    function ListaVersiones($idProy)
    {
        $sql = " SELECT
		  proy.t02_version ,
		  proy.t02_nom_proy
		FROM	  t02_dg_proy proy
		WHERE proy.t02_cod_proy = '$idProy';";

        return $this->ExecuteQuery($sql);
    }

    function ProyectoSeleccionar($id, $vs)
    {
        $sql = " SELECT t02_cod_proy,
				t02_version,
				t02_nro_exp,
				t01_id_inst,
				t02_nom_proy,
				DATE_FORMAT(t02_fch_apro,'%d/%m/%Y') as apro,
				DATE_FORMAT(t02_fch_ini,'%d/%m/%Y') as ini,
				DATE_FORMAT(t02_fch_ter,'%d/%m/%Y') as fin ,
				t02_fin,
				t02_pro,
				t02_ben_obj,
				t02_amb_geo,
				t02_pres_fe,
				t02_pres_eje,
				t02_pres_otro,
				t02_pres_tot,
				t02_moni_tema,
				t02_moni_fina,
				t02_moni_ext,
				t02_sup_inst,
				t02_dire_proy,
				t02_ciud_proy,
				t02_tele_proy,
				t02_fax_proy,
				t02_mail_proy,
				t02_estado, usr_crea, fch_crea, usr_actu, fch_actu, est_audi
		 FROM t02_dg_proy
		 WHERE t02_cod_proy = '$id' and t02_version='$vs' ;";
        $ConsultaID = $this->ExecuteQuery($sql);
        $row = mysql_fetch_assoc($ConsultaID);
        return $row;
    }
    // ndRegion
    // egion CRUD Proyectos
    function ProyectoNuevo($vs, $t02_nro_exp, $t01_id_inst, $t02_cod_proy, $t02_nom_proy, $t02_fch_apro, $t02_fch_ini, $t02_fch_ter, $t02_estado, $t02_fin, $t02_pro, $t02_ben_obj, $t02_amb_geo, $t02_pres_fe, $t02_pres_eje, $t02_pres_otro, $t02_pres_tot, $t02_moni_tema, $t02_moni_fina, $t02_moni_ext, $t02_sup_inst, $t02_dire_proy, $t02_ciud_proy, $t02_tele_proy, $t02_fax_proy, $t02_mail_proy)
    {
        $vs = 1;
        $est_audi = '1';

        $arrayfields = array(
            't02_version',
            't02_nro_exp',
            't01_id_inst',
            't02_cod_proy',
            't02_nom_proy',
            '@t02_fch_apro',
            '@t02_fch_ini',
            '@t02_fch_ter',
            't02_estado',
            't02_fin',
            't02_pro',
            't02_ben_obj',
            't02_amb_geo',
            't02_pres_fe',
            't02_pres_eje',
            't02_pres_otro',
            't02_pres_tot',
            't02_moni_tema',
            't02_moni_fina',
            't02_moni_ext',
            't02_sup_inst',
            't02_dire_proy',
            't02_ciud_proy',
            't02_tele_proy',
            't02_fax_proy',
            't02_mail_proy',
            'est_audi',
            'usr_crea',
            'fch_crea'
        );

        $arrayvalues = array(
            $vs,
            $t02_nro_exp,
            $t01_id_inst,
            $t02_cod_proy,
            $t02_nom_proy,
            $t02_fch_apro,
            $t02_fch_ini,
            $t02_fch_ter,
            $t02_estado,
            $t02_fin,
            $t02_pro,
            $t02_ben_obj,
            $t02_amb_geo,
            $t02_pres_fe,
            $t02_pres_eje,
            $t02_pres_otro,
            $t02_pres_tot,
            $t02_moni_tema,
            $t02_moni_fina,
            $t02_moni_ext,
            $t02_sup_inst,
            $t02_dire_proy,
            $t02_ciud_proy,
            $t02_tele_proy,
            $t02_fax_proy,
            $t02_mail_proy,
            $est_audi,
            $this->Session->UserID,
            $this->fecha
        );

        $sql = $this->DBOBaseMySQL->createqueryInsert("t02_dg_proy", $arrayfields, $arrayvalues);

        return $this->ExecuteCreate($sql);
    }

    function ProyectoActualizar($vs, $t02_nro_exp, $t01_id_inst, $t02_cod_proy, $t02_nom_proy, $t02_fch_apro, $t02_fch_ini, $t02_fch_ter, $t02_estado, $t02_fin, $t02_pro, $t02_ben_obj, $t02_amb_geo, $t02_pres_fe, $t02_pres_eje, $t02_pres_otro, $t02_pres_tot, $t02_moni_tema, $t02_moni_fina, $t02_moni_ext, $t02_sup_inst, $t02_dire_proy, $t02_ciud_proy, $t02_tele_proy, $t02_fax_proy, $t02_mail_proy)
    {
        $arrayfields = array(
            't02_version',
            't02_nro_exp',
            't01_id_inst',
            't02_cod_proy',
            't02_nom_proy',
            '@t02_fch_apro',
            '@t02_fch_ini',
            '@t02_fch_ter',
            't02_estado',
            't02_fin',
            't02_pro',
            't02_ben_obj',
            't02_amb_geo',
            't02_pres_fe',
            't02_pres_eje',
            't02_pres_otro',
            't02_pres_tot',
            't02_moni_tema',
            't02_moni_fina',
            't02_moni_ext',
            't02_sup_inst',
            't02_dire_proy',
            't02_ciud_proy',
            't02_tele_proy',
            't02_fax_proy',
            't02_mail_proy',
            'usr_actu',
            'fch_actu'
        );
        $arrayvalues = array(
            $vs,
            $t02_nro_exp,
            $t01_id_inst,
            $t02_cod_proy,
            $t02_nom_proy,
            $t02_fch_apro,
            $t02_fch_ini,
            $t02_fch_ter,
            $t02_estado,
            $t02_fin,
            $t02_pro,
            $t02_ben_obj,
            $t02_amb_geo,
            $t02_pres_fe,
            $t02_pres_eje,
            $t02_pres_otro,
            $t02_pres_tot,
            $t02_moni_tema,
            $t02_moni_fina,
            $t02_moni_ext,
            $t02_sup_inst,
            $t02_dire_proy,
            $t02_ciud_proy,
            $t02_tele_proy,
            $t02_fax_proy,
            $t02_mail_proy,
            $this->Session->UserID,
            $this->fecha
        );
        $where = "t02_cod_proy='$t02_cod_proy' and t02_version='$vs' ";
        $sql = $this->DBOBaseMySQL->createqueryUpdate("t02_dg_proy", $arrayfields, $arrayvalues, $where);

        return $this->ExecuteUpdate($sql);
    }

    function ProyectoEliminar($t02_cod_proy, $vs)
    {
        $sql = "	DELETE from t02_dg_proy
			WHERE t02_cod_proy='$t02_cod_proy' and t02_version='$vs';";
        return $this->ExecuteDelete($sql);
    }

    // ndRegion
    // in Region Proyectos
    function ListaEjecutores()
    {
        $sql = "SELECT t01_id_inst, t01_sig_inst  from  t01_dir_inst ;";

        return $this->ExecuteQuery($sql);
    }

    function ListaMonitor($ls_tipo)
    {
        $sql = "SELECT t90_id_equi as codigo, concat(t90_ape_pat, ' ', t90_ape_mat, ', ', t90_nom_equi) as descripcion
		from  t90_equi_fe
		Where t90_carg_equi = " . $ls_tipo . " Order by t90_ape_pat, t90_ape_mat, t90_nom_equi;";

        return $this->ExecuteQuery($sql);
    }
} // fin de la Clase BLProyecto

?>

