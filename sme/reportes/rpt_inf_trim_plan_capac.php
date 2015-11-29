<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$idTrim = $objFunc->__Request('idTrim');

$objProy = new BLProyecto();
$idVersion = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVersion);

$objInf = new BLInformes();
$rowInf = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idTrim, 1);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>.</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">
			<!-- InstanceBeginEditable name="BodyAjax" -->
			<table width="800" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">CODIGO DEL PROYECTO</th>
					<td colspan="4" align="left"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
					<th width="10%" align="left" nowrap="nowrap">INICIO</th>
					<td width="18%" align="left"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">TITULO DEL PROYECTO</th>
					<td colspan="4" align="left"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th height="26" align="left">FECHA DEL INFORME</th>
					<td width="18%"><?php echo($rowInf['t25_fch_pre']);?></td>
					<td width="11%">&nbsp;</td>
					<th width="5%">AÑO</th>
					<td width="20%">Año <?php echo($rowInf['t25_anio']);?></td>
					<th>&nbsp;</th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th align="left">TRIMESTRE</th>
					<td colspan="4" align="left">Trim <?php echo($rowInf['t25_trim']);?> (<?php echo($rowInf['t25_periodo']);?>) </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br />
			<div>
  <?php
$objPOA = new BLPOA();
$idVS = $objPOA->UltimaVersionPoa($idProy, $idAnio);
$rsMod = $objPOA->Lista_InfTrim_PlanCapac(1, $idProy, $idVS, NULL, NULL);
$arrMod = NULL;
$arrSub = NULL;
$arrTem = NULL;
?>
  <table border="0" cellspacing="0" cellpadding="0" width="770">
					<tbody class="data" bgcolor="#eeeeee">
						<tr>
							<td colspan="2" align="center" valign="middle"><strong>Datos del
									Beneficiario </strong></td>
        <?php
        while ($rm = mysqli_fetch_assoc($rsMod)) {
            $arrMod[] = $rm['codmodulo'];
            $arrNomMod[] = $rm['nommodulo'];
            ?>
        <td colspan="<?php echo($rm['numsub']); ?>" align="center"
								valign="middle"><strong><?php echo( $rm['nommodulo']);?></strong></td>

        <?php } ?>
         <td rowspan="2" colspan="<?php echo(count($arrNomMod));?>"
								align="center" valign="middle"><strong>Total Horas Capacitación
									Recibidos por Beneficiario </strong></td>
						</tr>
						<tr>
							<td width="3%" rowspan="2" align="center" valign="middle"><strong>DNI</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Apellidos
									y </strong><strong>Nombres</strong></td>
        <?php
        for ($x = 0; $x < count($arrMod); $x ++) {
            $rsSub = $objPOA->Lista_InfTrim_PlanCapac(2, $idProy, $idVS, $arrMod[$x], NULL);
            while ($rsub = mysqli_fetch_assoc($rsSub)) {
                $arrSub[$arrMod[$x]][] = $rsub['codigo'];
                ?>
        <td colspan="<?php echo($rsub['numtema']);?>" align="center"
								valign="top"><strong><?php echo($rsub['codigo'].'<br>'.$rsub['t09_sub'])?></strong></td>
        <?php
            } // EndWhile
        } // EndFor        ?>
      </tr>
						<tr>
        <?php
        for ($x = 0; $x < count($arrMod); $x ++) {
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $rsTema = $objPOA->Lista_InfTrim_PlanCapac(3, $idProy, $idVS, $arrMod[$x], $arrSub[$arrMod[$x]][$y]);
                while ($rtema = mysqli_fetch_assoc($rsTema)) {
                    $arrTemasCap[] = $rtema;
                    
                    $codig = $arrSub[$arrMod[$x]][$y] . '.' . $rtema['t12_cod_tema'];
                    $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_cod_tema'];
                    $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_tem_espe'];
                    ?>
        <td align="center" valign="middle" style="min-width: 120px;"><strong><?php echo($rtema['t12_tem_espe']); ?></strong></td>
        
        <?php
                } // EndWhile
            } // EndFor
        } // EndFor
        ?>
              
      <?php
    for ($m = 0; $m < count($arrNomMod); $m ++) {
        ?>
		  <td width="3%" valign="middle" align="center"><strong><?php echo($arrNomMod[$m]);?></strong></td>
	 <?php } ?>
      </tr>
					</tbody>
					<tbody class="data">
      <?php
    
    $objInf = new BLInformes();
    
    $iRsBenef = $objInf->InfTrim_Capac_Lista($idProy, $idAnio, $idTrim, '', '', '', '');
    
    while ($rb = mysqli_fetch_assoc($iRsBenef)) {
        ?>
      <tr>
							<td width="3%" align="center" valign="middle"><input
								name="txtbenef[]" type="hidden" id="txtbenef[]"
								value="<?php echo($rb['t11_cod_bene']); ?>"
								class="PlanCapacitacion" />
          <?php echo($rb['t11_dni']); ?></td>
							<td align="left" valign="middle" style="min-width: 250px;"><?php echo($rb['nombres']); ?></td>
        <?php
        $t = 0;
        $arrSumMOD = NULL;
        
        for ($x = 0; $x < count($arrMod); $x ++) {
            $arrSumMOD[$x] = 0;
            
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $arritem = $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]];
                $nomtema = $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]];
                for ($z = 0; $z < count($arritem); $z ++) {
                    $codig = $arrSub[$arrMod[$x]][$y] . '.' . $arritem[$z];
                    $valor = $rb[$codig];
                    
                    if ($valor == '1') {
                        $arrSumMOD[$x] += $arrTemasCap[$t]['t12_nro_hora'];
                    }
                    ?>
        <td width="3%" valign="middle" align="center">
          <?php if($valor=='1'){echo("<b>X</b>");}?>
          </td>
        
        <?php
                    
$t ++;
                }
            }
        }
        
        ?>
      
		
         <?php
        for ($m = 0; $m < count($arrSumMOD); $m ++) {
            ?>
		  <td width="3%" valign="middle" align="center"><?php echo(($arrSumMOD[$m]==0 ? '' : $arrSumMOD[$m] ) );?></td>
	 <?php } ?>
		 
      </tr>
      <?php } ?>
    </tbody>
				</table>
			</div>


			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>