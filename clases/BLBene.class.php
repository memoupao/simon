<?php
require_once ("BLBase.class.php");

class BLBene extends BLBase

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

    // egion Read Beneficiarios
    function BeneListado($idProy)

    { // ContactosListado
        $sql = "SELECT t11_bco_bene.t11_cod_bene, t11_bco_bene.t02_cod_proy, t11_bco_bene.t11_dni, CONCAT(t11_bco_bene.t11_ape_pat, ' ', t11_bco_bene.t11_ape_mat, ', ', t11_bco_bene.t11_nom ) as nombres,

		t11_bco_bene.t11_sexo, t11_bco_bene.t11_edad, t11_bco_bene.t11_nivel_educ, t11_bco_bene.t11_especialidad,

		DATE_FORMAT(t11_bco_bene.t11_fec_ini,'%d/%m/%Y') as t11_fec_ini,

		DATE_FORMAT(t11_bco_bene.t11_fec_ter,'%d/%m/%Y') as t11_fec_ter,

		t11_bco_bene.t11_sec_prod,t11_bco_bene.t11_subsector,t11_bco_bene.t11_unid_prod_1,t11_bco_bene.t11_nro_up_b,t11_bco_bene.t11_sec_prod_2,t11_bco_bene.t11_subsec_prod_2,t11_bco_bene.t11_tot_unid_prod,t11_bco_bene.t11_tot_unid_prod_2,t11_bco_bene.t11_unid_prod_2,t11_bco_bene.t11_nro_up_b_2,t11_bco_bene.t11_sec_prod_3, t11_bco_bene.t11_subsec_prod_3, t11_bco_bene.t11_unid_prod_3, t11_bco_bene.t11_tot_unid_prod_3, t11_bco_bene.t11_nro_up_b_3,t11_bco_bene.t11_nom_prod,

		t11_bco_bene.t11_direccion, t11_bco_bene.t11_ciudad, t11_bco_bene.t11_telefono, t11_bco_bene.t11_celular, t11_bco_bene.t11_mail, t11_bco_bene.t11_estado, t11_bco_bene.usr_crea, t11_bco_bene.fch_crea, t11_bco_bene.usr_actu, t11_bco_bene.fch_actu, t11_bco_bene.est_audi , u.nom_ubig

		FROM t11_bco_bene
		LEFT JOIN adm_ubigeo u ON (u.cod_dpto = t11_bco_bene.t11_dpto  AND u.cod_prov = '00' AND u.cod_dist = '00')

		WHERE t02_cod_proy = '$idProy' ;";

        return $this->ExecuteQuery($sql);
    }

    function BeneSeleccionar($idProy, $id)

    { // ContactosSeleccionar
        $sql = " SELECT t11_cod_bene, t02_cod_proy, t11_dni, t11_ape_pat, t11_ape_mat,  t11_nom ,

		t11_sexo, t11_edad, t11_nivel_educ,t11_especialidad,

		DATE_FORMAT(t11_fec_ini,'%d/%m/%Y') as t11_fec_ini,

		DATE_FORMAT(t11_fec_ter,'%d/%m/%Y') as t11_fec_ter,

		t11_sec_prod_main, t11_sec_prod,t11_subsector,t11_unid_prod_1,t11_nro_up_b,t11_sec_prod_main_2, t11_sec_prod_2,t11_subsec_prod_2,t11_tot_unid_prod,t11_tot_unid_prod_2,t11_unid_prod_2,t11_nro_up_b_2, t11_sec_prod_main_3, t11_sec_prod_3, t11_subsec_prod_3, t11_unid_prod_3, t11_tot_unid_prod_3, t11_nro_up_b_3,t11_nom_prod,

		t11_direccion, t11_dpto,t11_prov,t11_dist, t11_ciudad, t11_case,  t11_telefono, t11_celular, t11_mail, t11_act_princ, t11_estado, t11_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi, t11_esp_otro

		 FROM t11_bco_bene

		 WHERE t02_cod_proy = '$idProy' and t11_cod_bene='$id' ;";

        // echo("<pre>".$sql."</pre>");

        $ConsultaID = $this->ExecuteQuery($sql);

        $row = mysql_fetch_assoc($ConsultaID);

        return $row;
    }

    // ndRegion

    // egion CRUD Beneficiarios
    function BeneNuevo($t11_cod_bene, $t02_cod_proy, $t11_dni, $t11_ape_pat, $t11_ape_mat, $t11_nom, $t11_sexo, $t11_edad, $t11_nivel_educ, $t11_especialidad, $t11_fec_ini, $t11_fec_ter, $t11_sec_prod_main, $t11_sec_prod, $t11_subsector, $t11_unid_prod_1, $t11_nro_up_b, $t11_sec_prod_main_2, $t11_sec_prod_2, $t11_subsec_prod_2, $t11_tot_unid_prod, $t11_tot_unid_prod_2, $t11_unid_prod_2, $t11_nro_up_b_2, $t11_sec_prod_main_3, $t11_sec_prod_3, $t11_subsec_prod_3, $t11_unid_prod_3, $t11_tot_unid_prod_3, $t11_nro_up_b_3, $t11_nom_prod, $t11_direccion, $t11_dpto, $t11_prov, $t11_dist, $t11_ciudad, $t11_case, $t11_act_princ, $t11_telefono, $t11_celular, $t11_mail, $t11_estado, $t11_obs, $t11_esp_otros)

    {

        // ContactoNuevo

        /* Validaciones antes de grabar */
        $sql = "Select count(*)  from t11_bco_bene where t11_dni='" . $t11_dni . "' and t02_cod_proy ='" . $t02_cod_proy . "'; ";
        $sql2 = "Select *  from t11_bco_bene where t11_dni='" . $t11_dni . "' and t02_cod_proy ='" . $t02_cod_proy . "'; ";
        $resultado = mysql_query($sql2);
        while ($row = mysql_fetch_array($resultado)) {
            $nombre = $row['t11_nom'];
            $paterno = $row['t11_ape_pat'];
            $materno = $row['t11_ape_mat'];
        }

        if ($this->GetValue($sql) > 0)

        {
            $this->Error = "La Persona con DNI \"" . $t11_dni . "\" se encuentra registrada como \" " . $paterno . " " . $materno . ", " . $nombre . "\". Comuniquese con el Administrador";
            return false;
        }

        $est_audi = '1';

        $t11_cod_bene = $this->Autogenerate("t11_bco_bene", "t11_cod_bene");

        $arrayfields = array(
            't11_cod_bene',

            't02_cod_proy',

            't11_dni',

            't11_ape_pat',

            't11_ape_mat',

            't11_nom',

            't11_sexo',

            't11_edad',

            't11_nivel_educ',

            't11_especialidad',

            't11_fec_ini',

            't11_fec_ter',

            't11_sec_prod_main',

            't11_sec_prod',

            't11_subsector',

            't11_unid_prod_1',

            't11_nro_up_b',

            't11_sec_prod_main_2',

            't11_sec_prod_2',

            't11_subsec_prod_2',

            't11_tot_unid_prod',

            't11_tot_unid_prod_2',

            't11_unid_prod_2',

            't11_nro_up_b_2',

            't11_sec_prod_main_3',

            't11_sec_prod_3',

            't11_subsec_prod_3',

            't11_unid_prod_3',

            't11_tot_unid_prod_3',

            't11_nro_up_b_3',

            't11_nom_prod',

            't11_direccion',

            't11_dpto',

            't11_prov',

            't11_dist',

            't11_ciudad',

            't11_case',

            't11_act_princ',

            't11_telefono',

            't11_celular',

            't11_mail',

            't11_estado',

            't11_obs',

            'usr_crea',

            'fch_crea',

            'est_audi',
            't11_esp_otro'
        );

        $arrayvalues = array(
            $t11_cod_bene,
            $t02_cod_proy,
            $t11_dni,
            $t11_ape_pat,
            $t11_ape_mat,
            $t11_nom,
            $t11_sexo,
            $t11_edad,
            $t11_nivel_educ,
            $t11_especialidad,
            $this->ConvertDate($t11_fec_ini),
            $this->ConvertDate($t11_fec_ter),
            $t11_sec_prod_main,
            $t11_sec_prod,
            $t11_subsector,
            $t11_unid_prod_1,
            $t11_nro_up_b,
            $t11_sec_prod_main_2,
            $t11_sec_prod_2,
            $t11_subsec_prod_2,
            $t11_tot_unid_prod,
            $t11_tot_unid_prod_2,
            $t11_unid_prod_2,
            $t11_nro_up_b_2,
            $t11_sec_prod_main_3,
            $t11_sec_prod_3,
            $t11_subsec_prod_3,
            $t11_unid_prod_3,
            $t11_tot_unid_prod_3,
            $t11_nro_up_b_3,
            $t11_nom_prod,
            $t11_direccion,
            $t11_dpto,
            $t11_prov,
            $t11_dist,
            $t11_ciudad,
            $t11_case,
            $t11_act_princ,
            $t11_telefono,
            $t11_celular,
            $t11_mail,
            $t11_estado,
            $t11_obs,
            $this->Session->UserID,
            $this->fecha,
            $est_audi,
            $t11_esp_otros
        );

        $sql = $this->DBOBaseMySQL->createqueryInsert("t11_bco_bene", $arrayfields, $arrayvalues);

        return $this->ExecuteCreate($sql);
    }

    function BeneActualizar($t11_cod_bene, $t02_cod_proy, $t11_dni, $t11_ape_pat, $t11_ape_mat, $t11_nom, $t11_sexo, $t11_edad, $t11_nivel_educ, $t11_especialidad, $t11_fec_ini, $t11_fec_ter, $t11_sec_prod_main, $t11_sec_prod, $t11_subsector, $t11_unid_prod_1, $t11_nro_up_b, $t11_sec_prod_main_2, $t11_sec_prod_2, $t11_subsec_prod_2, $t11_tot_unid_prod, $t11_tot_unid_prod_2, $t11_unid_prod_2, $t11_nro_up_b_2, $t11_sec_prod_main_3, $t11_sec_prod_3, $t11_subsec_prod_3, $t11_unid_prod_3, $t11_tot_unid_prod_3, $t11_nro_up_b_3, $t11_nom_prod, $t11_direccion, $t11_dpto, $t11_prov, $t11_dist, $t11_ciudad, $t11_case, $t11_act_princ, $t11_telefono, $t11_celular, $t11_mail, $t11_estado, $t11_obs, $t11_esp_otros)

    {
        /*
         * $sql = "Select count(*) from t11_bco_bene where t11_dni='".$t11_dni."' and t02_cod_proy ='".$t02_cod_proy."'; " ; $sql2 = "Select * from t11_bco_bene where t11_dni='".$t11_dni."' and t02_cod_proy ='".$t02_cod_proy."'; " ; $resultado=mysql_query($sql2); while($row = mysql_fetch_array($resultado)) { $nombre=$row['t11_nom']; $paterno=$row['t11_ape_pat']; $materno=$row['t11_ape_mat']; $dni=$row['t11_dni']; } if($this->GetValue($sql)>0 ) {	$this->Error=" La Persona con DNI \"".$t11_dni."\" se encuentra registrada como \" ". $paterno." ".$materno. ", ".$nombre."\". Comuniquese con el Administrador"; return false;	}
         */
        $est_audi = '1';

        $arrayfields = array(
            't11_cod_bene',

            't02_cod_proy',

            't11_dni',

            't11_ape_pat',

            't11_ape_mat',

            't11_nom',

            't11_sexo',

            't11_edad',

            't11_nivel_educ',

            't11_especialidad',

            't11_fec_ini',

            't11_fec_ter',

            't11_sec_prod_main',

            't11_sec_prod',

            't11_subsector',

            't11_unid_prod_1',

            't11_nro_up_b',

            't11_sec_prod_main_2',

            't11_sec_prod_2',

            't11_subsec_prod_2',

            't11_tot_unid_prod',

            't11_tot_unid_prod_2',

            't11_unid_prod_2',

            't11_nro_up_b_2',

            't11_sec_prod_main_3',

            't11_sec_prod_3',

            't11_subsec_prod_3',

            't11_unid_prod_3',

            't11_tot_unid_prod_3',

            't11_nro_up_b_3',

            't11_nom_prod',

            't11_direccion',

            't11_dpto',

            't11_prov',

            't11_dist',

            't11_ciudad',

            't11_case',

            't11_act_princ',

            't11_telefono',

            't11_celular',

            't11_mail',

            't11_estado',

            't11_obs',

            'usr_actu',

            'fch_actu',

            'est_audi',
            't11_esp_otro'
        );

        $arrayvalues = array(
            $t11_cod_bene,
            $t02_cod_proy,
            $t11_dni,
            $t11_ape_pat,
            $t11_ape_mat,
            $t11_nom,
            $t11_sexo,
            $t11_edad,
            empty($t11_nivel_educ) ? 0 : $t11_nivel_educ,
            $t11_especialidad,
            $this->ConvertDate($t11_fec_ini),
            $this->ConvertDate($t11_fec_ter),
            $t11_sec_prod_main,
            $t11_sec_prod,
            $t11_subsector,
            empty($t11_unid_prod_1) ? 0 : $t11_unid_prod_1,
            $t11_nro_up_b,
            empty($t11_sec_prod_main_2) ? 0 : $t11_sec_prod_main_2,
            empty($t11_sec_prod_2) ? 0 : $t11_sec_prod_2,
            empty($t11_subsec_prod_2) ? 0 : $t11_subsec_prod_2,
            $t11_tot_unid_prod,
            $t11_tot_unid_prod_2,
            empty($t11_unid_prod_2) ? 0 : $t11_unid_prod_2,
            $t11_nro_up_b_2,
            empty($t11_sec_prod_main_3) ? 0 : $t11_sec_prod_main_3,
            empty($t11_sec_prod_3) ? 0 : $t11_sec_prod_3,
            empty($t11_subsec_prod_3) ? 0 : $t11_subsec_prod_3,
            empty($t11_unid_prod_3) ? 0 : $t11_unid_prod_3,
            $t11_tot_unid_prod_3,
            $t11_nro_up_b_3,
            $t11_nom_prod,
            $t11_direccion,
            $t11_dpto,
            $t11_prov,
            $t11_dist,
            $t11_ciudad,
            $t11_case,
            $t11_act_princ,
            $t11_telefono,
            $t11_celular,
            $t11_mail,
            $t11_estado,
            $t11_obs,
            $this->Session->UserID,
            $this->fecha,
            $est_audi,
            $t11_esp_otros
        );

        $where = "t02_cod_proy='$t02_cod_proy' and t11_cod_bene='$t11_cod_bene' ";
        $sql = $this->DBOBaseMySQL->createqueryUpdate("t11_bco_bene", $arrayfields, $arrayvalues, $where);
        return $this->ExecuteUpdate($sql);
    }

    function BeneEliminar($t02_cod_proy, $t11_cod_bene)

    { // ContactoEliminar
        $sql = "	DELETE from t11_bco_bene

			WHERE t02_cod_proy='$t02_cod_proy' and t11_cod_bene='$t11_cod_bene';";

        return $this->ExecuteDelete($sql);
    }

    // ndRegion Beneficiarios
    function ListadoBeneficiarios($idProy)

    {
        $SP = "sp_rpt_beneficiarios";

        $params = array(
            $idProy
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroBeneficiariosCap($idProy, $anio, $trim)

    {
        $SP = "sp_cap_x_inf_tri";

        $params = array(
            $idProy,
            $anio,
            $trim
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroBeneficiariosAT($idProy, $anio, $trim)

    {
        $SP = "sp_at_x_inf_tri";

        $params = array(
            $idProy,
            $anio,
            $trim
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroBeneficiariosOtros($idProy, $anio, $trim)

    {
        $SP = "sp_otros_x_inf_tri";

        $params = array(
            $idProy,
            $anio,
            $trim
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroBeneficiariosCred($idProy, $anio, $trim)

    {
        $SP = "sp_cred_x_inf_tri";

        $params = array(
            $idProy,
            $anio,
            $trim
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroTemasPOA($idProy, $ver)

    {
        $SP = "sp_lis_temas";

        $params = array(
            $idProy,
            $ver
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroTemasAT($idProy, $ver)
    {
        $SP = "sp_lis_at";

        $params = array(
            $idProy,
            $ver
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroTemasOtros($idProy, $ver)
    {
        $SP = "sp_lis_otros";

        $params = array(
            $idProy,
            $ver
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function NumeroTemasCred($idProy, $ver)
    {
        $SP = "sp_lis_cred";

        $params = array(
            $idProy,
            $ver
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    function ListadoBeneficiariosTotales($pIdConc = null)
    {
        $SP = "sp_rpt_beneficiarios_tot";

        $params = array(
            $pIdConc
        );

        $ret = $this->ExecuteProcedureReader($SP, $params);

        return $ret;
    }

    // egion Ubigeo
    function ListaUbigeoDpto($proy)

    {
        return $this->ExecuteProcedureReader("sp_bene_ubigeo", array(
            $proy,
            '',
            '',
            ''
        ));
    }

    function ListaUbigeoProv($proy, $dpto)

    {
        return $this->ExecuteProcedureReader("sp_bene_ubigeo", array(
            $proy,
            $dpto,
            '',
            ''
        ));
    }

    function ListaUbigeoDist($proy, $dpto, $prov)

    {
        return $this->ExecuteProcedureReader("sp_bene_ubigeo", array(
            $proy,
            $dpto,
            $prov,
            ''
        ));
    }

    function ListaUbigeoCaserio($proy, $dpto, $prov, $dist)

    {
        return $this->ExecuteProcedureReader("sp_bene_ubigeo", array(
            $proy,
            $dpto,
            $prov,
            $dist
        ));
    }

    function ListaBeneficiarioUbigeo($proy, $dpto, $prov, $dist, $case)

    {
        return $this->ExecuteProcedureReader("sp_lis_bene_ubigeo", array(
            $proy,
            $dpto,
            $prov,
            $dist,
            $case
        ));
    }
    
    
    
    /**
     * Importador datos desde XLSX de beneficiarios.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $t02_cod_proy		Codigo del Proyecto 
     * @param string $t11_dni 			Nro. de DNI
     * @param string $t11_ape_pat		Apellidos Paternos
     * @param string $t11_ape_mat		Apellidos Maternos
     * @param string $t11_nom			Nombres 
     * @param string $t11_sexo			Sexo 
     * @param string $t11_edad			Edad 
     * @param string $t11_nivel_educ	Nivel de Educacion 
     * @param string $t11_especialidad	Especialidad 
     * @param string $t11_fec_ini		Fecha de Inicio laboral (dd/mm/yyyy)
     * @param string $t11_fec_ter		Fecha de Termino laboral (dd/mm/yyyy)
     * @param string $t11_direccion		Direccion 
     * @param string $t11_ciudad		Ciudad 
     * @param string $t11_act_princ		Actividad Principal 
     * @param string $t11_telefono		Telefono 
     * @param string $t11_celular		Celular 
     * @param string $t11_mail			Email 
     * @param string $t11_estado		Estado 
     * @param string $t11_obs			Observaciones 
     * @param string $t11_esp_otros		Otra especialidad
	 * @param string $text_t11_dpto		Departamento
	 * @param string $text_t11_prov		Provincia
	 * @param string $text_t11_dist		Distrito
	 * @param string $centro_poblado	Centro Poblado     
     * @return resource
     *
     */
    
    function importBeneNuevos($t02_cod_proy, $t11_dni, $t11_ape_pat, $t11_ape_mat, $t11_nom, $t11_sexo, $t11_edad, $t11_nivel_educ, $t11_especialidad, $t11_fec_ini, $t11_fec_ter, $t11_direccion, $t11_ciudad, $t11_act_princ, $t11_telefono, $t11_celular, $t11_mail, $t11_estado, $t11_obs, $t11_esp_otros, $text_t11_dpto, $text_t11_prov, $text_t11_dist, $centro_poblado)    
    {
    
    	/* Validaciones antes de grabar */
    	$t11_dni = mysql_real_escape_string($t11_dni);
    	$t02_cod_proy = mysql_real_escape_string($t02_cod_proy);
    	
    	$t11_fec_ini = trim($t11_fec_ini);
    	$t11_fec_ter = trim($t11_fec_ter);
    	
    	
    	$sql = "select count(t11_dni) from t11_bco_bene where t11_dni='" . $t11_dni . "' and t02_cod_proy ='" . $t02_cod_proy . "'";
    	$sql2 = "select t11_nom, t11_ape_pat, t11_ape_mat FROM t11_bco_bene WHERE t11_dni='" . $t11_dni . "' and t02_cod_proy ='" . $t02_cod_proy . "' GROUP BY t11_dni";
    	$resultado = mysql_query($sql2);
    	if (mysql_num_rows($resultado) >0) {
    		$row = mysql_fetch_array($resultado);
    		 
    		$nombre = $row['t11_nom'];
    		$paterno = $row['t11_ape_pat'];
    		$materno = $row['t11_ape_mat'];
    		 
    		
    		if ($this->GetValue($sql) > 0) {
    			
    			$this->Error = "La Persona con DNI \"" . $t11_dni . "\" se encuentra registrada como \" " . $paterno . " " . $materno . ", " . $nombre . "\". Comuniquese con el Administrador <br/>";
    			return false;
    			
    		}	
    	}
    	
    
    	$est_audi = '1';
    
    	$t11_cod_bene = $this->Autogenerate("t11_bco_bene", "t11_cod_bene");
    
    	
    
    	//$sql = $this->DBOBaseMySQL->createqueryInsert("t11_bco_bene", $arrayfields, $arrayvalues);
    	$sql = "INSERT INTO t11_bco_bene(t11_cod_bene,    
    			t02_cod_proy,    
    			t11_dni,    
    			t11_ape_pat,    
    			t11_ape_mat,    
    			t11_nom,    
    			t11_sexo,    
    			t11_edad,    
    			t11_nivel_educ,    
    			t11_especialidad, ";
    	
    	if (!empty($t11_fec_ini)) {
    		$sql .= " t11_fec_ini,";
    	}
    	
    	if (!empty($t11_fec_ter)) {
    		$sql .= " t11_fec_ter, ";
    	}
    	
    	$sql .= "
    			t11_dpto,
				t11_prov,
            	t11_dist, 
            	t11_case,
    			t11_direccion,
    			t11_ciudad,
    			t11_act_princ,
    			t11_telefono,
    			t11_celular,
    			t11_mail,
    			t11_estado,
    			t11_obs,
    			usr_crea,
    			fch_crea,
    			est_audi,
    			t11_esp_otro) VALUES(
    			'{$t11_cod_bene}',
    			'{$t02_cod_proy}',
    			'{$t11_dni}',
    			'{$t11_ape_pat}',
    			'{$t11_ape_mat}',
    			'{$t11_nom}',
    			'{$t11_sexo}',
    			'{$t11_edad}',    			
    			'{$t11_nivel_educ}', 
    			'{$t11_especialidad}',  ";
    	
    	if (!empty($t11_fec_ini)) {
    		
    		$sql .= "'{$t11_fec_ini}',";
    	}
    	
    	if (!empty($t11_fec_ter)) {
    		$sql .= "'{$t11_fec_ter}',";
    	}
    	
    	$sqlDpto = "SELECT cod_dpto FROM adm_ubigeo WHERE cod_prov='00' AND cod_dist='00' AND nom_ubig = '{$text_t11_dpto}' GROUP BY cod_dpto";
    	$sqlProv = "SELECT cod_prov FROM adm_ubigeo WHERE cod_dpto = ($sqlDpto) AND cod_prov<>'00' AND cod_dist='00' AND nom_ubig = '{$text_t11_prov}' GROUP BY cod_prov";
    	$sqlDist = "SELECT cod_dist FROM adm_ubigeo WHERE cod_dpto = ($sqlDpto) AND cod_prov = ($sqlProv) AND cod_dist<>'00' AND nom_ubig = '{$text_t11_dist}' GROUP BY cod_dist";
    	$sqlCent = "SELECT cod_case FROM adm_caserios WHERE cod_dpto = ($sqlDpto) AND cod_prov = ($sqlProv) AND cod_dist<>'00' AND cod_dist=($sqlDist)  AND nom_case = '{$centro_poblado}' GROUP BY cod_case";
    	
    	$sql .= "(".$sqlDpto."),";
    	$sql .= "(".$sqlProv."),";
    	$sql .= "(".$sqlDist."),";
    	$sql .= "(".$sqlCent."),";
    	
    	$sql .= "'{$t11_direccion}',
    			'{$t11_ciudad}',
    			'{$t11_act_princ}',
    			'{$t11_telefono}',
    			'{$t11_celular}',
    			'{$t11_mail}',
    			'{$t11_estado}',
    			'{$t11_obs}',
    			'{$this->Session->UserID}',
    			'{$this->fecha}',
    			'{$est_audi}',
    			'{$t11_esp_otros}')";
        	
    	return $this->ExecuteCreate($sql);
    	
    }
    
    
    
    

    // ndRegion
} // fin de la Clase BLBene

