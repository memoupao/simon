<?php
require_once ("BLBase.class.php");
require_once ("HardCode.class.php");

class BLApprDesemb extends BLBase
{
    // Boilerplate code that should be placed on another class into the hierarchy
    var $fecha;

    var $Session;

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
    
    // ---------------------------------------------------------------------------
    function Aprobacion_Desemb_Parciales($pIdProy, $pIdTrim)
    {
        $aFun = new Functions();
        $aParams = array(
            $pIdProy,
            $aFun->NumeroTrimRev($pIdTrim, 1),
            $aFun->NumeroTrimRev($pIdTrim, 2)
        );
        $aResult = $this->ExecuteProcedureReader("sp_get_aprueba_desemb_parciales", $aParams);
        $aList = array();
        $aCurrent = null;
        
        if ($aResult->num_rows > 0) {
            while ($aRow = mysqli_fetch_assoc($aResult)) {
                if ($aRow['t60_nro_aprob'])
                    array_push($aList, $aRow);
                $aFlg = isset($aRow['t60_fch_aprob']) && $aRow['t60_fch_aprob'] && $aRow['t60_flg_desemb'];
                if (! $aFlg)
                    $aCurrent = $aRow;
            }
        }
        $aResult->free();
        
        return array(
            $aList,
            $aCurrent
        );
    }

    function Aprobacion_Desemb_Persist(&$pCodigo)
    {
        $aMtoAcum = $_POST['monto_desemb_acum'];
        $aMtoPlan = $_POST['t60_montoplan'];
        $aMtoParc = null;
        
        if ($_POST['t60_nro_aprob'] || ! $_POST['t60_mto_par_aprob'])
            $aMtoParc = str_replace(',', '', $_POST['txt_mto_desemb']);
        else
            $aMtoParc = $_POST['t60_mto_par_aprob'];
        
        if (! $aMtoParc && $_POST['t60_mto_par_aprob'])
            $aMtoParc = $_POST['t60_mto_par_aprob'];
        
        if (round($aMtoParc + $aMtoAcum, 2) > round($aMtoPlan, 2)) {
            $this->Error = "Monto a desembolsar total no puede ser superior al monto presupuestado para el Trimestre.";
            return false;
        }
        
        $aFun = new Functions();
        /*$aParams = array(
            $_POST['t02_proyecto'],
            $aFun->NumeroTrimRev($_POST['t60_trimestre'], 1),
            $aFun->NumeroTrimRev($_POST['t60_trimestre'], 2),
            $_POST['t60_montoplan'],
            $_POST['t60_id_aprob'],
            $_POST['t60_nro_aprob'],
            $aMtoParc,
            $_POST['chk_aprueba_mt'],
            $_POST['txt_obs_mt'],
            $_POST['chk_aprueba_mf'],
            $_POST['txt_obs_mf'],
            $_POST['chk_aprueba_cmt'],
            $_POST['txt_obs_cmt'],
            $_POST['chk_aprueba_cmf'],
            $_POST['txt_obs_cmf'],
            $this->Session->UserID
        ); */
        
        $aParams = array(
        		$_POST['t02_proyecto'],
        		$aFun->NumeroTrimRev($_POST['t60_trimestre'], 1),
        		$aFun->NumeroTrimRev($_POST['t60_trimestre'], 2),
        		$_POST['t60_montoplan'],
        		$_POST['t60_id_aprob'],
        		$_POST['t60_nro_aprob'],
        		$aMtoParc,
        		$_POST['chk_aprueba_mt'],
        		$_POST['txt_obs_mt'],
        		$_POST['chk_aprueba_mt'],
        		'',
        		$_POST['chk_aprueba_cmt'],
        		$_POST['txt_obs_cmt'],
        		$_POST['chk_aprueba_cmt'],
        		'',
        		$this->Session->UserID
        );
        
        
        $aReturn = $this->ExecuteProcedureEscalar("sp_upd_aprueba_desemb_parcial", $aParams);
        
        if ($aReturn['numrows'] >= 0) {
            $pCodigo = $aReturn['codigo'];
            return true;
        } else {
            return false;
        }
    }
}

