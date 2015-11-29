<?php
require_once ("BLBase.class.php");

require_once ("UploadFiles.class.php");

require_once ("HardCode.class.php");

// / -------------------------------------------------------------------------

// / Programmer Name : Aima R. Christian Created Date : 2010-09-23

// / Comments : Clase BLInformes heredado de BLBase

// / Administra el Modulo, Manejo del Proyecto (Componente 6)

// / -------------------------------------------------------------------------
class BLManejoProy extends BLBase

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
    
    // egion Varios Listados
    function ListadoFuentesFinanc($proy, $incFE = TRUE)
    
    {
        $params = array(
            $proy,
            $incFE
        );
        
        return $this->ExecuteProcedureReader("sp_sel_fuentes_financ", $params);
    }
    
    // ndRegion
    function VerificarAnioPOA($idProy)
    
    {
        return $this->ExecuteFunction("fn_verificar_anio", array(
            $idProy
        ));
    }

    function GetAnioPOA($idProy, $idVersion)
    
    {
        return $this->ExecuteFunction("fn_anio_x_version_proy", array(
            $idProy,
            $idVersion
        ));
    }
    
    // egion Personal del Proyecto
    function Personal_Listado($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_personal", $params);
    }

    function Personal_ListadoEqui($proy)
    
    {
        $params = array(
            $proy
        );
        
        return $this->ExecuteProcedureReader("sp_sel_personal_equi", $params);
    }

    function Personal_Listado_Metas($proy, $version, $idPer)
    
    {
        $params = array(
            $proy,
            $version,
            $idPer
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_personal_metas", $params);
    }

    function Personal_Listado_Metas($proy, $version, $idPer)
    
    {
        $params = array(
            $proy,
            $version,
            $idPer
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_personal_metas", $params);
    }

    function Personal_Seleccionar($proy, $version, $idPer)
    
    {
        $params = array(
            $proy,
            $version,
            $idPer
        );
        
        $rs = $this->ExecuteProcedureReader("sp_get_mp_personal", $params);
        
        $row = mysqli_fetch_assoc($rs);
        
        $rs->free();
        
        return $row;
    }

    function Man_ListadoPersonal($idProy, $idVersion, $idPer, $anio)
    
    {
        $SP = "sp_poa_per";
        
        $params = array(
            $idProy,
            $idVersion,
            $idPer,
            $anio
        );
        
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        // print_r($this);
        
        return $ret;
    }

    function Man_ListadoEquipo($idProy, $idVersion, $idEqui, $anio)
    
    {
        $SP = "sp_poa_equi";
        
        $params = array(
            $idProy,
            $idVersion,
            $idEqui,
            $anio
        );
        
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        // print_r($this);
        
        return $ret;
    }

    function Man_ListadoGastosFunc($idProy, $idVersion, $Partida, $anio)
    
    {
        $SP = "sp_poa_func";
        
        $params = array(
            $idProy,
            $idVersion,
            $Partida,
            $anio
        );
        
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        // print_r($this);
        
        return $ret;
    }

    function Personal_Nuevo(&$idPer)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_nom_per'],
            
            $_POST['t03_tdr'],
            
            $_POST['t03_per_um'],
            
            $_POST['t03_dedica'],
            
            $_POST['t03_remu'],
            
            $_POST['t03_perma'],
            
            $_POST['txtSumaTotalMeses'],
            
            $this->Session->UserID
        );
        
        $SP = "sp_ins_mp_personal";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idPer = $ret['codigo'];
            
            $anios = $_POST['t03_anios'];
            
            for ($x = 0; $x < count($anios); $x ++) 

            {
                
                $nameIdMes = "t03_anio_" . $anios[$x] . "_mes";
                
                $meses = implode("|", $_POST[$nameIdMes]);
                
                $params = array(
                    $_POST['txtCodProy'],
                    
                    $_POST['cboversion'],
                    
                    $idPer,
                    
                    $anios[$x],
                    
                    $meses,
                    
                    $this->Session->UserID
                );
                
                $this->ExecuteProcedureEscalar("sp_upd_mp_personal_metas", $params);
            }
            
            $this->ExecuteProcedureEscalar("sp_upd_mp_total_metas", array(
                $_POST['txtCodProy'],
                $_POST['cboversion'],
                1,
                $idPer
            ));
            
            return true;
        } 

        else 

        {
            
            $this->Error = $ret['error'];
            
            return false;
        }
    }

    function Personal_Actualizar(&$idPer)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_id_per'],
            
            $_POST['t03_nom_per'],
            
            $_POST['t03_tdr'],
            
            $_POST['t03_per_um'],
            
            $_POST['t03_dedica'],
            
            $_POST['t03_remu'],
            
            $_POST['t03_perma'],
            
            $_POST['txtmta'],
            
            $this->Session->UserID,
            
            $_POST['txtmpar'],
            
            $_POST['txtmprog']
        )
        ;
        
        $SP = "sp_upd_mp_personal";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idPer = $ret['codigo'];
            
            $anios = $_POST['t03_anios'];
            
            for ($x = 0; $x < count($anios); $x ++) 

            {
                
                $nameIdMes = "t03_anio_" . $anios[$x] . "_mes";
                
                $meses = implode("|", $_POST[$nameIdMes]);
                
                $params = array(
                    $_POST['txtCodProy'],
                    
                    $_POST['cboversion'],
                    
                    $idPer,
                    
                    $anios[$x],
                    
                    $meses,
                    
                    $this->Session->UserID
                );
                
                $this->ExecuteProcedureEscalar("sp_upd_mp_personal_metas", $params);
            }
            
            $this->ExecuteProcedureEscalar("sp_upd_mp_total_metas", array(
                $_POST['txtCodProy'],
                $_POST['cboversion'],
                1,
                $idPer
            ));
            
            return true;
        } 

        else 

        {
            
            $this->Error = $ret['error'];
            
            return false;
        }
    }

    function Personal_Eliminar()
    
    {
        $params = array(
            $_POST['t02_cod_proy'],
            
            $_POST['t20_version'],
            
            $_POST['t03_id_per']
        );
        
        $SP = "sp_del_mp_personal";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            return true;
        } 

        else 

        {
            
            $this->Error = "No se logro Eliminar ningun registro";
            
            return false;
        }
    }

    function Personal_AdjuntarTDR()
    
    {
        $objFiles = new UploadFiles("txtFileUploadTDR");
        
        $HC = new HardCode();
        
        $ext = $objFiles->getExtension();
        
        $url = $_POST['txtCodProy'] . '_' . $_POST['cboversion'] . '_' . $_POST['t03_id_per'] . '.' . $ext;
        
        $objFiles->DirUpload .= $HC->FolderUploadTDR;
        
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_id_per'],
            
            $url,
            
            $this->Session->UserID
        );
        
        $SP = "sp_upd_mp_personal_TDR";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $objFiles->SavesAs($url);
            
            return true;
        } 

        else 

        {
            return false;
        }
    }

    function Personal_FuentesFinanc($proy, $version, $per)
    
    {
        $params = array(
            $proy,
            $version,
            $per
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_personal_fuentes", $params);
    }

    function Personal_ActualizarFTE()
    
    {
        $Montos = $_POST['txtmonto'];
        
        $Instit = $_POST['txtInstit'];
        
        $insts = implode("|", $Instit);
        
        $montos = implode("|", $Montos);
        
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_id_per'],
            
            $insts,
            
            $montos,
            
            $this->Session->UserID
        )
        ;
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_mp_personal_fuentes", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            return false;
        }
    }

    function Personal_GastoTotal($proy, $version)
    {
        $params = array(
            $proy,
            $version
        );
        
        $sp = "fn_gasto_total_mp_personal";
        
        return $this->ExecuteFunction($sp, $params);
    }
    
    // ndRegion
    
    // egion Equipamiento del proyecto
    function Equipamiento_Listado($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_equipa", $params);
    }

    function Equipamiento_Listado_Metas($proy, $version, $idEqui)
    
    {
        $params = array(
            $proy,
            $version,
            $idEqui
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_equipa_metas", $params);
    }

    function Equipamiento_Seleccionar($proy, $version, $idEqui)
    
    {
        $params = array(
            $proy,
            $version,
            $idEqui
        );
        
        $rs = $this->ExecuteProcedureReader("sp_get_mp_equipa", $params);
        
        $row = mysqli_fetch_assoc($rs);
        
        $rs->free();
        
        return $row;
    }

    function Equipamiento_Nuevo(&$idEqui)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_nom_equi'],
            
            $_POST['t03_um'],
            
            $_POST['t03_cu'],
            
            $_POST['t03_cant'],
            
            $_POST['t03_costo'],
            
            $_POST['txtSumaTotalMeses'],
            
            $this->Session->UserID
        );
        
        $SP = "sp_ins_mp_equipa";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idEqui = $ret['codigo'];
            
            $anios = $_POST['t03_anios'];
            
            for ($x = 0; $x < count($anios); $x ++) 

            {
                
                $nameIdMes = "t03_anio_" . $anios[$x] . "_mes";
                
                $meses = implode("|", $_POST[$nameIdMes]);
                
                $params = array(
                    $_POST['txtCodProy'],
                    
                    $_POST['cboversion'],
                    
                    $idEqui,
                    
                    $anios[$x],
                    
                    $meses,
                    
                    $this->Session->UserID
                );
                
                $this->ExecuteProcedureEscalar("sp_upd_mp_equipa_metas", $params);
            }
            
            $this->ExecuteProcedureEscalar("sp_upd_mp_total_metas", array(
                $_POST['txtCodProy'],
                $_POST['cboversion'],
                2,
                $idEqui
            ));
            
            return true;
        } 

        else 

        {
            
            $this->Error = $ret['error'];
            
            return false;
        }
    }

    function Equipamiento_Actualizar(&$idEqui)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_id_equi'],
            
            $_POST['t03_nom_equi'],
            
            $_POST['t03_um'],
            
            $_POST['t03_cu'],
            
            $_POST['t03_cant'],
            
            $_POST['t03_costo'],
            
            $_POST['txtmta'],
            
            $this->Session->UserID,
            
            $_POST['txtmpar'],
            
            $_POST['txtmprog']
        )
        ;
        
        $SP = "sp_upd_mp_equipa";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idEqui = $_POST['t03_id_equi'];
            
            $anios = $_POST['t03_anios'];
            
            for ($x = 0; $x < count($anios); $x ++) 

            {
                
                $nameIdMes = "t03_anio_" . $anios[$x] . "_mes";
                
                $meses = implode("|", $_POST[$nameIdMes]);
                
                $params = array(
                    $_POST['txtCodProy'],
                    
                    $_POST['cboversion'],
                    
                    $idEqui,
                    
                    $anios[$x],
                    
                    $meses,
                    
                    $this->Session->UserID
                );
                
                $this->ExecuteProcedureEscalar("sp_upd_mp_equipa_metas", $params);
            }
            
            $this->ExecuteProcedureEscalar("sp_upd_mp_total_metas", array(
                $_POST['txtCodProy'],
                $_POST['cboversion'],
                2,
                $idEqui
            ));
            
            return true;
        } 

        else 

        {
            
            $this->Error = $ret['error'];
            
            return false;
        }
    }

    function Equipamiento_Eliminar()
    
    {
        $params = array(
            $_POST['t02_cod_proy'],
            
            $_POST['t20_version'],
            
            $_POST['t03_id_equi']
        );
        
        $SP = "sp_del_mp_equipa";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            return true;
        } 

        else 

        {
            
            $this->Error = "No se logro Eliminar ningun registro";
            
            return false;
        }
    }

    function Equipamiento_FuentesFinanc($proy, $version, $per)
    
    {
        $params = array(
            $proy,
            $version,
            $per
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_equipa_fuentes", $params);
    }

    function Equipamiento_ActualizarFTE()
    
    {
        $Montos = $_POST['txtmonto'];
        
        $Instit = $_POST['txtInstit'];
        
        $insts = implode("|", $Instit);
        
        $montos = implode("|", $Montos);
        
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_id_equi'],
            
            $insts,
            
            $montos,
            
            $this->Session->UserID
        )
        ;
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_mp_equipa_fuentes", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            return false;
        }
    }

    function Equipamiento_CostoTotal($proy, $version)
    {
        $params = array(
            $proy,
            $version
        );
        
        $sp = "fn_costo_total_mp_equi";
        
        return $this->ExecuteFunction($sp, $params);
    }
    
    // ndRegion
    
    // egion Gastos de Funcionamiento
    function GastFunc_Listado($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_gasfunc", $params);
    }

    function GastFunc_ListadoCateg($proy, $version, $partida)
    
    {
        $params = array(
            $proy,
            $version,
            $partida
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_gasfunc_categ", $params);
    }

    function GastFunc_ListadoCateg_Gasto($proy, $version, $partida, $categ)
    
    {
        $params = array(
            $proy,
            $version,
            $partida,
            $categ
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_gasfunc_categ_gasto", $params);
    }

    function GastFunc_Listado_Metas($proy, $version, $partida)
    
    {
        $params = array(
            $proy,
            $version,
            $partida
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_gasfunc_metas", $params);
    }

    function GastFunc_Listado_Partidas($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteProcedureReader("sp_lis_mp_partidas", $params);
    }

    function GastFunc_Nuevo(&$idPartida)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['cbopartida'],
            
            $_POST['t03_um'],
            
            $_POST['txtSumaTotalMeses'],
            
            $this->Session->UserID
        );
        
        $SP = "sp_ins_mp_gasfunc";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idPartida = $ret['codigo'];
            
            $anios = $_POST['t03_anios'];
            
            for ($x = 0; $x <= count($anios); $x ++) 

            {
                
                $nameIdMes = "t03_anio_" . $anios[$x] . "_mes";
                
                $meses = implode("|", $_POST[$nameIdMes]);
                
                $params = array(
                    $_POST['txtCodProy'],
                    
                    $_POST['cboversion'],
                    
                    $idPartida,
                    
                    $anios[$x],
                    
                    $meses,
                    
                    $this->Session->UserID
                );
                
                $this->ExecuteProcedureEscalar("sp_upd_mp_gasfunc_metas", $params);
            }
            
            $this->ExecuteProcedureEscalar("sp_upd_mp_total_metas", array(
                $_POST['txtCodProy'],
                $_POST['cboversion'],
                3,
                $idPartida
            ));
            
            return true;
        } 

        else 

        {
            
            $this->Error = $ret['error'];
            
            return false;
        }
    }

    function GastFunc_Actualizar(&$idPartida)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_partida'],
            
            $_POST['cbopartida'],
            
            $_POST['t03_um'],
            
            $_POST['txtmta'],
            
            $this->Session->UserID,
            
            $_POST['txtmpar'],
            
            $_POST['txtmprog']
        )
        ;
        
        $SP = "sp_upd_mp_gasfunc";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idPartida = $_POST['t03_partida'];
            
            $anios = $_POST['t03_anios'];
            
            for ($x = 0; $x < count($anios); $x ++) 

            {
                
                $nameIdMes = "t03_anio_" . $anios[$x] . "_mes";
                
                $meses = implode("|", $_POST[$nameIdMes]);
                
                $params = array(
                    $_POST['txtCodProy'],
                    
                    $_POST['cboversion'],
                    
                    $idPartida,
                    
                    $anios[$x],
                    
                    $meses,
                    
                    $this->Session->UserID
                );
                
                $this->ExecuteProcedureEscalar("sp_upd_mp_gasfunc_metas", $params);
            }
            
            $this->ExecuteProcedureEscalar("sp_upd_mp_total_metas", array(
                $_POST['txtCodProy'],
                $_POST['cboversion'],
                3,
                $idPartida
            ));
            
            return true;
        } 

        else 

        {
            
            $this->Error = $ret['error'];
            
            return false;
        }
    }

    function GastFunc_Seleccionar($proy, $version, $partida)
    
    {
        $params = array(
            $proy,
            $version,
            $partida
        );
        
        $rs = $this->ExecuteProcedureReader("sp_get_mp_gasfunc", $params);
        
        $row = mysqli_fetch_assoc($rs);
        
        $rs->free();
        
        return $row;
    }

    function GastFunc_Eliminar()
    
    {
        $params = array(
            $_POST['t02_cod_proy'],
            $_POST['t20_version'],
            $_POST['t03_partida']
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_del_mp_gasfunc", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            
            $this->Error = "No se logro Eliminar ningun registro";
            
            return false;
        }
    }

    function Funcionamiento_CostoTotal($proy, $version)
    {
        $params = array(
            $proy,
            $version
        );
        
        $sp = "fn_costo_total_mp_func";
        
        return $this->ExecuteFunction($sp, $params);
    }

    function Componentes_CostoTotal($proy, $version)
    {
        $params = array(
            $proy,
            $version
        );
        
        $sp = "fn_costos_componentes";
        
        return $this->ExecuteFunction($sp, $params);
    }

    function gastoPersonal_Ejecutado($proy, $anio)
    {
        $params = array(
            $proy,
            $anio
        );
        
        $sp = "fn_financ_mp_personal_ejecutado";
        
        return $this->ExecuteFunction($sp, $params);
    }
    
    // -----------------------------------------------------#
    function GastFunc_SeleccionarCosteo($proy, $version, $partida, $gasto)
    
    {
        $params = array(
            $proy,
            $version,
            $partida,
            $gasto
        );
        
        $rs = $this->ExecuteProcedureReader("sp_get_mp_gasfunc_gasto", $params);
        
        $row = mysqli_fetch_assoc($rs);
        
        $rs->free();
        
        return $row;
    }

    function GastFunc_NuevoCosteo(&$idPartida, &$idGasto)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['cbopartida'],
            
            $_POST['t03_descrip'],
            
            $_POST['t03_um'],
            
            $_POST['t03_cant'],
            
            $_POST['t03_cu'],
            
            $_POST['cbocatgasto'],
            
            $this->Session->UserID
        );
        
        $SP = "sp_ins_mp_gasfunc_costeo";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idPartida = $ret['partida'];
            
            $idGasto = $ret['codigo'];
            
            return true;
        } 

        else 

        {
            
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['error'];
            }
            
            return false;
        }
    }

    function GastFunc_ActualizarCosteo(&$idPartida, &$idGasto)
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_partida'],
            
            $_POST['t03_id_gasto'],
            
            $_POST['t03_descrip'],
            
            $_POST['t03_um'],
            
            $_POST['t03_cant'],
            
            $_POST['t03_cu'],
            
            $_POST['cbocatgasto'],
            
            $this->Session->UserID
        );
        
        $SP = "sp_upd_mp_gasfunc_costeo";
        
        $ret = $this->ExecuteProcedureEscalar($SP, $params);
        
        if ($ret['numrows'] > 0) 

        {
            
            $idPartida = $ret['partida'];
            
            $idGasto = $ret['codigo'];
            
            return true;
        } 

        else 

        {
            
            if ($ret['numrows'] == 0) {
                $this->Error = $ret['error'];
            }
            
            return false;
        }
    }

    function GastFunc_EliminarCosteo()
    
    {
        $params = array(
            $_POST['t02_cod_proy'],
            
            $_POST['t20_version'],
            
            $_POST['t03_partida'],
            
            $_POST['t03_id_gasto']
        );
        
        $ret = $this->ExecuteProcedureEscalar("sp_del_mp_gasfunc_gasto", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            
            $this->Error = "No se logro Eliminar ningun registro";
            
            return false;
        }
    }

    function GastFunc_FuentesFinanc($proy, $version, $partida, $gasto)
    
    {
        $params = array(
            $proy,
            $version,
            $partida,
            $gasto
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_gasfunc_fuentes", $params);
    }

    function GastFunc_ActualizarFTE()
    
    {
        $Montos = $_POST['txtmonto'];
        
        $Instit = $_POST['txtInstit'];
        
        $insts = implode("|", $Instit);
        
        $montos = implode("|", $Montos);
        
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['t03_partida'],
            
            $_POST['t03_id_gasto'],
            
            $insts,
            
            $montos,
            
            $this->Session->UserID
        )
        ;
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_mp_gasfunc_fuentes", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            return false;
        }
    }
    
    // ndRegion
    
    // egion Gastos Administrativos
    function GastosAdm_ResumenCostos($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteProcedureReader("sp_lis_resumen_gastos_comp_fte", $params);
    }

    function GastosAdm_Listado($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteProcedureReader("sp_sel_mp_gastos_adm", $params);
    }

    function GastosAdm_Actualizar()
    
    {
        $Montos = $_POST['txtadmmonto'];
        
        $Instit = $_POST['txtadminstit'];
        
        $insts = implode("|", $Instit);
        
        $montos = implode("|", $Montos);
        
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $insts,
            
            $montos,
            
            $this->Session->UserID
        )
        ;
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_mp_gastos_adm", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            return false;
        }
    }

    function LineaBase_Imprevistos($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        $ret = $this->ExecuteProcedureReader("sp_sel_mp_linea_base_imprevistos", $params);
        
        $row = mysqli_fetch_assoc($ret);
        
        $ret->free();
        
        return $row;
    }

    function LineaBaseImprevistos_Actualizar()
    
    {
        $params = array(
            $_POST['txtCodProy'],
            
            $_POST['cboversion'],
            
            $_POST['txtMontoLB'],
            
            $_POST['txtMontoImprevistos'],
            
            $this->Session->UserID
        )
        ;
        
        $ret = $this->ExecuteProcedureEscalar("sp_upd_mp_linea_base_imprevistos", $params);
        
        if ($ret['numrows'] > 0) 

        {
            return true;
        } 

        else 

        {
            return false;
        }
    }

    function GetCostosDirectos($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteFunction("fn_costos_directos", $params);
    }

    function GetCostosInDirectos($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteFunction("fn_costos_indirectos", $params);
    }

    function GetCostosTotalProyecto($proy, $version)
    
    {
        $params = array(
            $proy,
            $version
        );
        
        return $this->ExecuteFunction("fn_costos_total_proyecto", $params);
    }

    function GetPresupuestoReasignado($proy, $version)
    
    {
        $SP = "sp_get_pp_reasignar";
        
        $params = array(
            $proy,
            $version
        );
        
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        // print_r($this);
        
        return $ret;
    }

    function GetTotalAporteFuente($proy, $version, $idFte)
    
    {
        $params = array(
            $proy,
            $version,
            $idFte
        );
        
        return $this->ExecuteFunction("fn_total_aporte_fuentes_financ3", $params);
    }

    function Adminis_CostoTotal($proy, $version)
    {
        $params = array(
            $proy,
            $version
        );
        
        $sp = "fn_costos_adm";
        
        return $this->ExecuteFunction($sp, $params);
    }

    function lineaBase_total($proy, $inst)
    {
        $params = array(
            $proy,
            $inst
        );
        
        $sp = "fn_costo_linea_base";
        
        return $this->ExecuteFunction($sp, $params);
    }
    
    // ndRegion
    
    // egion Informe Financiero
    function Inf_Financ_Lista_Personal($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_per", $params);
    }

    function Inf_Financ_Lista_Personal_Total($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_per_total", $params);
    }

    function Inf_Financ_Lista_Equipamiento_Total($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_equi_total", $params);
    }

    function Inf_Financ_Lista_GastoFuncionamiento_Total($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_gast_func_total", $params);
    }

    function Inf_Financ_Lista_Equipamiento($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_equi", $params);
    }

    function Inf_Financ_Lista_PartidasGF($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_partida", $params);
    }

    function Inf_Financ_Lista_CategoriasGF($proy, $anio, $mes, $partida, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $partida,
            $fte
        );
        
        $ret = $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_gas_func_categ", $params);
        
        return $ret;
    }

    function Inf_Financ_Lista_GastosAdministrativos($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        return $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_gast_adm", $params);
    }

    function Inf_Financ_Lista_Imprevistos($proy, $anio, $mes, $fte)
    
    {
        $params = array(
            $proy,
            $anio,
            $mes,
            $fte
        );
        
        $ret = $this->ExecuteProcedureReader("sp_sel_inf_financ_mp_imprevistos", $params);
        
        return $ret;
    }
    
    // ndRegion Informe Financiero
    
    // egion Reportes
    function Rpt_PresupuestoAnalitico($proy, $vs)
    
    {
        $params = array(
            $proy,
            $vs
        );
        
        return $this->ExecuteProcedureReader("sp_rpt_presup_analitico", $params);
    }

    function Rpt_PresupuestoEjeMensual($proy, $vs)
    
    {
        $params = array(
            $proy,
            $vs
        );
        
        return $this->ExecuteProcedureReader("sp_rpt_presup_eje_mensual", $params);
    }

    function RepCronogramaDesembolsos($proy, $vs, $FteFinanc)
    
    {
        $SP = "sp_rpt_cronograma_desembolsos_fte";
        
        $params = array(
            $proy,
            $vs,
            $FteFinanc
        );
        
        $ret = $this->ExecuteProcedureReader($SP, $params);
        
        return $ret;
    }
    
    // ndRegion Reportes
} // fin de la Clase BLManejoProy

?>
