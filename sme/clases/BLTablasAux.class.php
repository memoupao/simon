<?php
require_once ("BLBase.class.php");

class BLTablasAux extends BLBase
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
    function ListaTablas()
    {
        $sql = "SELECT cod_tabla, nom_tabla FROM adm_tablas where flg_act='1' ORDER BY cod_tabla ASC;";
        return $this->ExecuteQuery($sql);
    }

    function TipoUnidades()
    {
        return $this->ListaTablasaux(2);
    }

    function TipoCargos()
    {
        return $this->ListaTablasaux(3);
    }

    function ListaEstados()
    {
        return $this->ListaTablasaux(4);
    }

    function TipoIndicadores()
    {
        return $this->ListaTablasaux(5);
    }

    function TipoCargoContacto()
    {
        return $this->ListaTablasaux(6);
    }

    function EstadoInformes()
    {
        return $this->ListaTablasaux(15);
    }

    function EstadoInformesPOAFin()
    {
        return $this->ListaTablasaux(32);
    }

    function EstadoInformesFinanc()
    {
        return $this->ListaTablasaux(30);
    }

    function EstadoInformesMonInt()
    {
        return $this->ListaTablasaux(31);
    }

    function EstadoInformesMonExt()
    {
        return $this->ListaTablasaux(34);
    }

    function EstadoInformesMonitor()
    {
        return $this->ListaTablasaux(19);
    }

    function TipoUnidadesFE()
    {
        return $this->ListaTablasaux(7);
    }

    function TipoCargosFE()
    {
        return $this->ListaTablasaux(8);
    }

    function Sexo()
    {
        return $this->ListaTablasaux(12); // /Sexo
    }

    function NivelEdu()
    {
        return $this->ListaTablasaux(13); // Nivel Educativo
    }

    function EstadoParti()
    {
        return $this->ListaTablasaux(14); // Estado de participacion de los integrantes de equipo ejecutor y beneficiarios
    }

    function ListadoMeses()
    {
        $sql = "SELECT '1' as codigo, 'Mes 1'  as descripcion
		  UNION
		  SELECT '2' as codigo, 'Mes 2'  as descripcion
		  UNION
		  SELECT '3' as codigo, 'Mes 3'  as descripcion
		  UNION
		  SELECT '4' as codigo, 'Mes 4'  as descripcion
		  UNION
		  SELECT '5' as codigo, 'Mes 5'  as descripcion
		  UNION
		  SELECT '6' as codigo, 'Mes 6'  as descripcion
		  UNION
		  SELECT '7' as codigo, 'Mes 7'  as descripcion
		  UNION
		  SELECT '8' as codigo, 'Mes 8'  as descripcion
		  UNION
		  SELECT '9' as codigo, 'Mes 9'  as descripcion
		  UNION
		  SELECT '10' as codigo, 'Mes 10'  as descripcion
		  UNION
		  SELECT '11' as codigo, 'Mes 11'  as descripcion
		  UNION
		  SELECT '12' as codigo, 'Mes 12'  as descripcion ; ";
        
        return $this->Execute($sql);
    }

    function ListadoMesesCalendario()
    {
        $sql = "SELECT '1' as codigo, 'Enero'  as descripcion
		  UNION
		  SELECT '2' as codigo, 'Febrero'  as descripcion
		  UNION
		  SELECT '3' as codigo, 'Marzo'  as descripcion
		  UNION
		  SELECT '4' as codigo, 'Abril'  as descripcion
		  UNION
		  SELECT '5' as codigo, 'Mayo'  as descripcion
		  UNION
		  SELECT '6' as codigo, 'Junio'  as descripcion
		  UNION
		  SELECT '7' as codigo, 'Julio'  as descripcion
		  UNION
		  SELECT '8' as codigo, 'Agosto'  as descripcion
		  UNION
		  SELECT '9' as codigo, 'Setiembre'  as descripcion
		  UNION
		  SELECT '10' as codigo, 'Octubre'  as descripcion
		  UNION
		  SELECT '11' as codigo, 'Noviembre'  as descripcion
		  UNION
		  SELECT '12' as codigo, 'Diciembre'  as descripcion ; ";
        
        return $this->Execute($sql);
    }

    function ListadoTrimestres()
    {
        $sql = "SELECT 1 as codigo, 'Primer Trimestre'  as descripcion
		  UNION
		  SELECT 2 as codigo, 'Segundo Trimestre'  as descripcion
		  UNION
		  SELECT 3 as codigo, 'Tercer Trimestre'  as descripcion
		  UNION
		  SELECT 4 as codigo, 'Cuarto Trimestre'  as descripcion; ";
        
        return $this->Execute($sql);
    }

    function ValoraInformesME()
    {
        return $this->ListaTablasaux(16);
    }
    
    
    /**    
    * Obtiene los valores de Calificaciones .    
    *    
    * @author DA    
    * @since Version 2.1    
    * @access public           
    * @return boolean    
    *    
    */
    public function getValoresCalificacionSE()
    {
    	return $this->ListaTablasaux(37);
    }
    
    /**
     * Obtiene los valores de Estado del Cronograma 
     * de visitas del proyecto.
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @return boolean
     *
     */
    public function getValoresEstadoCronogramaVisitas()
    {
    	return $this->ListaTablasaux(39);
    }
    
    
    /**
     * Obtiene los valores de Tipo de Desembolsos.
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @return boolean
     *
     */
    public function getTipoDesembolsosSE()
    {
    	return $this->ListaTablasaux(38);
    }
    

    function TipoSubActividades()
    {
        return $this->ListaTablasaux(9);
    }

    function TipoCategoriaGastos()
    {
        return $this->ListaTablasaux(10); // Categorias de Gasto aplicable a presupuestos y gastos
    }

    function TipoCargosEquipoProy()
    {
        return $this->ListaTablasaux(11); // /Cargos de los Integrantes de Equipos Ejecutor
    }

    function ActividadPlanVisita()
    {
        return $this->ListaTablasaux(17);
    }

    /**
     * Obtener resultados de los subsectores.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $t02_sector_main ID del Tipo de Principal Sector     
     * @return resource
     *
     */
    public function SectoresProductivos($t02_sector_main)
    {
        return $this->ListaTablasaux($t02_sector_main);
    }
    

    function SubSectoresProductivos($idSector)
    {
        return $this->ListaTablasaux2($idSector);
    }

    function ListaDepartamentos()
    {
        $SP = "sp_sel_ubigeo_dpto";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaProvincias($dpto)
    {
        $SP = "sp_sel_ubigeo_prov";
        $params = array(
            $dpto
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function ListaDistritos($dpto, $prov)
    {
        $SP = "sp_sel_ubigeo_dist";
        $params = array(
            $dpto,
            $prov
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function ListaCaserios($dpto, $prov, $dist)
    {
        $SP = "sp_sel_ubigeo_case";
        $params = array(
            $dpto,
            $prov,
            $dist
        );
        return $this->ExecuteProcedureReader($SP, $params);
    }

    function ListaConcursosActivos()
    {
        $SP = "sp_sel_concursos_activos";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaConcursosCrear()
    {
        $SP = "sp_sel_concursos_nuevos";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaBancos()
    {
        $SP = "sp_sel_bancos";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaTipoCuentas()
    {
        $SP = "sp_sel_tipocuenta";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaTipoMoneda()
    {
        $SP = "sp_sel_tipomoneda";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaTipoPagoDesembolso()
    {
        $SP = "sp_sel_tipopago";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ListaAniosConcurso($concurso)
    {
        $SP = "sp_lis_anios_concurso";
        return $this->ExecuteProcedureReader($SP, array(
            $concurso
        ));
    }

    function ADMMensajes_Listado()
    {
        $SP = "sp_sel_mensajes";
        return $this->ExecuteProcedureReader($SP, NULL);
    }

    function ADMMensajes_Actualizar($id)
    {
        $SP = "sp_upd_mensajes";
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_mensajes", array(
            $id
        ));
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    function TipoRemuneracion()
    {
        return $this->ListaTablasaux(20);
    }

    function TipoPartidasMP()
    {
        return $this->ListaTablasaux(21);
    }

    function TipoDocsManejaInst()
    {
        return $this->ListaTablasaux(22);
    }

    function TipoDocsManejaInstEstado()
    {
        return $this->ListaTablasaux(23);
    }

    function TipoEstadoCartaFianza()
    {
        return $this->ListaTablasaux(24);
    }

    function TipoModulosPlanesEspecificos()
    {
        return $this->ListaTablasaux(25);
    } // Modulos - Plan Capacitación
    function EspecialidadPer()
    {
        return $this->ListaTablasaux(26); // Especialidad de Personal
    }

    function TipoOtrosServiciosPlanesEspecificos()
    {
        return $this->ListaTablasaux(27); // Especialidad de Personal
    }

    function UnidadesProdBen()
    {
        return $this->ListaTablasaux(28); // Unidades de Produccion de Beneficiarios
    }

    function TipoRelacion()
    {
        return $this->ListaTablasaux(29); // Unidades de Produccion de Beneficiarios
    }

    protected function ListaTablasaux($idTabla)
    {
        $sql = "select codi as Codigo, 
				descrip as Descripcion, 
				abrev as Abreviado,
				cod_ext as cod_ext
		from adm_tablas_aux
	    where idTabla = $idTabla and flg_act=1 
	    order by orden, descrip ASC";
        return $this->ExecuteQuery($sql);
    }

    protected function ListaTablasaux2($idTablaAux)
    {
        $sql = "select  codi as Codigo, 
				descrip as Descripcion, 
				abrev as Abreviado,
				cod_ext as cod_ext
		from adm_tablas_aux2
	    where id_tabla_aux = $idTablaAux and flg_act=1 
	    order by orden, descrip ASC";
        return $this->ExecuteQuery($sql);
    }
    
    // egion Tablas Aux
    function ListadoTipos($idTabla)
    {
        return $this->ExecuteProcedureReader("sp_sel_tablas_aux", array(
            $idTabla
        ));
    }
    
    /**
     * Listado de Productos Principales (tercera clasificacion de productos)
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $idTabla Id de Tabla adm_tablas_aux2     
     * @return resource Resultado de consulta
     * 
     */
    public  function ListadoTiposProdPrinc($idTabla)
    {
        $sql = "SELECT codi as codigo, 
            	 descrip as descripcion, 
            	 abrev   as abreviatura, 
            	 ifnull(cod_ext,'-') as externo, 
            	 (case when flg_act='1' then 'Activo' else 'Inactivo' end) as activo, 
            	 orden, 
            	 usu_crea
            	FROM adm_tablas_aux3 
            	where idTabla = ".$idTabla;
    }
    
    
    
    /**
     * Listado de Productos Principales (tercera clasificacion de productos)
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $idTabla Id de Tabla adm_tablas_aux2
     * @param string $idTablaAux Id de Tabla adm_tablas_aux3
     * @return resource Resultado de consulta
     *
     */
    public function ListadoProductosPrincipales($idTabla, $idTablaAux)
    {
        $sql = "SELECT codi as codigo, 
            	 descrip as descripcion, 
            	 abrev   as abreviatura, 
            	 ifnull(cod_ext,'-') as externo, 
            	 (case when flg_act='1' then 'Activo' else 'Inactivo' end) as activo, 
            	 orden, 
            	 usu_crea
            	FROM adm_tablas_aux2 
            	WHERE idTabla = ".$idTabla." AND id_tabla_aux2 = ".$idTablaAux;
        return $this->ExecuteQuery($sql);
    }
           

    function TipoSeleccionar($idtablaaux)
    {
        $params = array(
            $idtablaaux
        );
        $ret = $this->ExecuteProcedureReader("sp_get_tablaaux", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function TipoNuevo()
    {
        $params = array(
            $_POST['txtnombre'],
            $_POST['txtabrev'],
            $_POST['txtexterno'],
            $_POST['cbotablaaux'],
            $_POST['txtorden'],
            $_POST['chkactivo'],
            $this->Session->UserID
        );
        $SP = "sp_ins_tablasaux";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function TipoActualizar()
    {
        $params = array(
            $_POST['txtcodigo'],
            $_POST['txtnombre'],
            $_POST['txtabrev'],
            $_POST['txtexterno'],
            $_POST['cbotablaaux'],
            $_POST['txtorden'],
            $_POST['chkactivo'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_tablasaux";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function TipoEliminar($idtablaaux)
    {
        $SP = "sp_del_tablasaux";
        $params = array(
            $idtablaaux
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    // in Tablas aux
    
    // egion Tablas Aux
    function ListadoSubTipos($idTabla, $idTablaAux)
    {
        return $this->ExecuteProcedureReader("sp_sel_tablas_aux_2", array(
            $idTabla,
            $idTablaAux
        ));
    }

    function SubTipoSeleccionar($idtablaaux)
    {
        $params = array(
            $idtablaaux
        );
        $ret = $this->ExecuteProcedureReader("sp_get_tablaaux2", $params);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }

    function SubTipoNuevo()
    {
        $params = array(
            $_POST['txtnombre'],
            $_POST['txtabrev'],
            $_POST['txtexterno'],
            $_POST['txtidtabla'],
            $_POST['cbotablaaux'],
            $_POST['txtorden'],
            $_POST['chkactivo'],
            $this->Session->UserID
        );
        $SP = "sp_ins_tablasaux2";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function SubTipoActualizar()
    {
        $params = array(
            $_POST['txtcodigo'],
            $_POST['txtnombre'],
            $_POST['txtabrev'],
            $_POST['txtexterno'],
            $_POST['txtidtabla'],
            $_POST['cbotablaaux'],
            $_POST['txtorden'],
            $_POST['chkactivo'],
            $this->Session->UserID
        );
        
        $SP = "sp_upd_tablasaux2";
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }

    function SubTipoEliminar($idaux)
    {
        $SP = "sp_del_tablasaux2";
        $params = array(
            $idaux
        );
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        if ($ret['numrows'] > 0) {
            return true;
        } else {
            $this->Error = $ret['msg'];
            return false;
        }
    }
    
    /**
     * Obtiene listado de los Niveles de Instruccion.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @return resource Resultado de consulta mysql
     *
     */
    public function getListNivelInstruccion()
    {
        return $this->ListaTablasaux(13);
    }
    
    
    /**
     * Obtiene listado de los Centros Poblados.
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @return resource Resultado de consulta mysql
     *
     */
    public function getListCentrosPoblados()
    {
    	$sql = 'SELECT CONCAT(cod_dpto, cod_prov, cod_dist, cod_case) AS codigo, nom_case AS centro FROM adm_caserios ORDER BY nom_case';
        return $this->ExecuteQuery($sql);
    }
    
    
    
    
    // in Tablas aux
    
    // fin de la Clase TablasAux
}

