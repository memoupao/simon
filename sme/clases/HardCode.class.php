<?php
class HardCode
{
    /* Codigo de Instituciones */
    var $codigo_Fondoempleo = 10;

    var $Nombre_Fondoempleo = "FONDOEMPLEO";

    var $CodigoMP = 10;

    /* Ruta de Carpetas */
    var $FolderUploadXLS = "uploadxls/";

    var $FolderUploadTDR = "sme/proyectos/planifica/per_tdr/";

    var $FolderUploadPOA = "sme/proyectos/poa/anx/";

    var $FolderUploadCartaFianza = "sme/proyectos/anexos/CF/";

    var $FolderUploadSolicitudCP = "sme/proyectos/anexos/CP/";

    var $FolderUploadBV = "sme/infapoyo/bv/"; /* Directorio de Almacen de Documentos - Bilcioteca Virtual */

    /* Codigo de Tipos de Usuario */

    var $Admin = 1; // Adminitrador
    var $Ejec = 2; // Institucion Ejecutora

    // -------------------------------------------------->
    // DA 2.0 [02-11-2013 16:32]
    // Secretaria Ejecutiva no existira en el sistema el termino SE sera del Supervisor Externo
    // var $SE = 3; // Secretaria Ejecutiva
    // --------------------------------------------------<
    var $CMT = 4; // Coordinador de Monitoreo tecnico
    var $AMT = 5; // Asistente de Monitoreo tecnico
    var $MT = 6; // Monitor Tematico
    var $ME = 7; // Monitor Externo
    var $MF = 8; // Monitor Financiero
    var $CMF = 9; // Coordinador Monitoreo Financiero
    //var $ADM = 10; // Administracion - Fondoempleo
    var $EVA = 11; // Administracion - Fondoempleo

    // -------------------------------------------------->
    // DA 2.0 [02-11-2013 15:48]
    // Nuevas propiedades publicas para los nuevos perfiles definidos.
    // Se recomienda en la nueva version que sean constantes estaticas y que los
    // valores sean definidos desde la bd y no estaticamente para no depender del codigo y/o bd.

    /**
     * @type int Id del Gestor de Proyectos (GP).
     */
    public $GP = 13;

    /**
     * @type int Id de Gerente de Gestion de Proyectos (GGP).
     */
    public $GGP = 14;

    /**
     * @type int Id del Supervisor de Proyectos (SP).
     */
    public $SP = 12;

    /**
     * @type int Id del Respensable del Area (RA).
     */
    public $RA = 15;

    /**
     * @type int Id del Supervisor Externo (SE).
     */
    public $SE = 16;

    // --------------------------------------------------<

    // -------------------------------------------------->
    // AQ 2.0 [17-12-2013 11:46]
    // Se reutiliza el perfil ADM para convertirlo en FE
    // que será el superusuario en la parte funcional
    public $FE = 10;
    // --------------------------------------------------<

    /* Variables Para Calculos del Personal / Manejo del Proyecto */
    /**
     * @type double Tasa de Gratificación.
     */
    var $gratificacion = 0;

    /**
     * @type double Tasa de CTS.
     */
    var $porc_CTS = 8.3333333;

    /**
     * @type double Tasa de Seguro de EsSalud.
     */
    var $porc_ESS = 9;

    /* Variables: gastos Funcionamiento Linea de Base e Imprevistos */
	/**
     * @type double (%) Porcentaje de Gastos Funcionales o Administrativos.
     */
    var $Porcent_Gast_Func = 8; // 8%

	/**
     * @type double (%) Porcentaje de Linea Base.
     */
    var $Porcent_Linea_Base = 4; // 4%

	/**
     * @type double (%) Porcentaje de Gastos Imprevistos.
     */
    var $Porcent_Imprevistos = 2; // 2%

	// -------------------------------------------------->
    // DA 2.0 [07-11-2013 12:00]
	// Nuevo porcentaje adicionado para Supervisión del Proyecto
	// sera parte del mantenimiento de Tasas Bases
	/**
     * @type double (%) Porcentaje de Gastos de Supervisión del Proyecto.
     */
	public $porcentGastSupervProy = 1; // 1%
    // --------------------------------------------------<

    // Envíos de Correo
    var $MailAdmin = "carlos.rojas@cticservices.com";

    // Estados de Informes
    var $EstInf_Ela = 45;
    var $EstInf_Rev = 46;
    var $EstInf_Corr = 47;
    var $EstInf_Aprob = 135;
    /*var $EstInf_VBMon = 135;
    var $EstInf_AprobCMT = 257;*/
    var $especTecVBGP = 135;
    var $especTecAprobRA = 257;

    // Estado POA Financiero
    var $EstInf_ElaFin = 258;
    var $EstInf_RevFin = 259;
    var $EstInf_CorrFin = 260;
    //var $EstInf_AprobFin = 261;
    //var $EstInf_AprobCMF = 262;
    // var $especFinVBGP = 135; Por definir
    var $especFinVBGP = 261;
    var $especFinAprobRA = 262;

    // Estados Informe Financiero
    var $EstInf_ElaF = 246;
    var $EstInf_RevF = 247;
    var $EstInf_CorrF = 248;
    var $EstInf_AprobF = 249;

    // Estados Informe Monitor Interno Tecnico
    var $EstInf_ElaMIT = 250;
    var $EstInf_RevMIT = 251;
    var $EstInf_CorrMIT = 252;
    var $EstInf_AprobMIT = 253;

    // Estados Informe Monitor Externo
    var $EstInf_ElaME = 280;
    var $EstInf_RevME = 281;
    var $EstInf_CorME = 282;
    var $EstInf_VoBME = 283;
    var $EstInf_CorMTME = 284;
    var $EstInf_ApprCMTME = 285;

    // Estados del Proyecto
    var $Proy_PorIniciar = 40;
    var $Proy_Ejecucion = 41;
    var $Proy_Suspendido = 42;
    var $Proy_Cancelado = 43;
    var $Proy_Concluido = 44;

    /**
     * Constructor de HardCode
     *
     * @author DA
     * @since Version 2.0
     * @access public
     *
     */
    public function __construct()
    {
        $mysqli = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'), constant('DB_NAME'));

        if ($mysqli->connect_errno == 0) {
            $result = $mysqli->query('SELECT t00_cod_tasa as codigo, t00_nom_lar, 1 as tipo FROM t00_tasa UNION  SELECT t00_cod_param as codigo, t00_nom_lar, 2 as tipo FROM t00_parametro ORDER BY tipo, codigo');
            if($result->num_rows) {
                while ($row = $result->fetch_array()) {

                    $valor = floatval($row['t00_nom_lar']);

                    if ($row['tipo']==1) {
                        switch ($row['codigo']) {
                        	case 1:
                        	    $this->Porcent_Gast_Func = $valor;
                        	    break;
                        	case 2:
                        	    $this->Porcent_Linea_Base = $valor;
                        	    break;
                        	case 3:
                        	    $this->Porcent_Imprevistos = $valor;
                        	    break;
                        	case 4:
                        	    $this->porcentGastSupervProy = $valor;
                        	    break;
                        }
                    } else {
                        switch ($row['codigo']) {
                        	case 1:
                        	    $this->gratificacion = $valor;
                        	    break;
                        	case 2:
                        	    $this->porc_CTS = $valor;
                        	    break;
                        	case 3:
                        	    $this->porc_ESS = $valor;
                        	    break;
                        }
                    }



                }

            }

        }
    }
}
// fin de la Clase HardCode


