<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLFE.class.php");

$objFE = new BLFE();

$idconcurso = $objFunc->__Request('cboConcurso');
$Ejecutor = $objFunc->__Request('cboEjecutor');
$AnioIni = $objFunc->__Request('txtanioini');
$AnioFin = $objFunc->__Request('txtaniofin');
$MesIni = $objFunc->__Request('cboMesIni');
$MesFin = $objFunc->__Request('cboMesFin');

$inicio = $AnioIni . str_pad($MesIni, 2, "0", STR_PAD_LEFT);
$termino = $AnioFin . str_pad($MesFin, 2, "0", STR_PAD_LEFT);

$irs = $objFE->RepDesembolsos_PeriodoME($idconcurso, $Ejecutor, $inicio, $termino);
$arrValues = $objFE->ResultToTranspose($irs);

$arr_concursos = array_unique($arrValues['concurso']);

?>


<?php if($idoncurso=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title></title>
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
			<style>
.rotate90 {
	-webkit-transform: rotate(-90deg);
	-moz-transform: rotate(-90deg);
	filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
}
</style>
			<table width="98%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td rowspan="2" align="center" valign="middle">&nbsp;</td>
						<td width="68" rowspan="2" align="center" valign="middle">Código</td>
						<td width="86" rowspan="2" align="center" valign="middle">Ejecutor</td>
						<td width="86" rowspan="2" align="center" valign="middle">Monitor</td>
						<td width="202" rowspan="2" align="center" valign="middle">Proyecto</td>
						<td colspan="2" align="center" valign="middle"><p align="center">Periodo
								del Proyecto</p></td>
						<td height="20" colspan="7" align="center" valign="top">
							Desembolsos Realizados en el Periodo <br />
      (<?php echo($objFunc->MonthName($MesIni)." ".$AnioIni." - ".$objFunc->MonthName($MesFin)." ".$AnioFin); ?>)
      </td>
					</tr>
					<tr>
						<td width="57" align="center" valign="middle">Inicio</td>
						<td width="62" align="center" valign="middle">Término</td>
						<td width="67" align="center" valign="middle">Visita</td>
						<td width="153" height="20" align="center" valign="middle"><p
								align="center">Modalidad</p></td>
						<td width="64" align="center" valign="middle"><p align="center">Fecha
								Giro</p></td>
						<td width="65" align="center" valign="middle">Importe</td>
						<td width="65" align="center" valign="middle">N&deg; Cheque</td>
						<td width="67" align="center" valign="middle">Fecha Deposito</td>
						<td width="172" align="center" valign="middle">Observaciones</td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF" style="font-size: 11px;">
					<tr>
  
    <?php
    $sumatotal = 0;
    foreach ($arr_concursos as $conc) {
        $rowspanConcurso = 0;
        for ($i = 0; $i < count($arrValues['codigo']); $i ++) {
            if ($arrValues['concurso'][$i] == $conc) {
                $rowspanConcurso ++;
            }
        }
        
        echo ($newTR);
        $newTR = "";
        $sumaconcurso = 0;
        ?>
        <td rowspan="<?php echo($rowspanConcurso);?>" align="center"
							valign="middle" style="max-width: 15px;">
							<div class="rotate90" align="center"
								style="width: 70px; height: 60px;"><?php echo("Concurso ".$conc); ?></div>
						</td>
        <?php
        $last_proy = "";
        for ($y = 0; $y < count($arrValues['codigo']); $y ++) {
            $cod_proy = $arrValues['codigo'][$y];
            
            if ($conc == $arrValues['concurso'][$y] && $cod_proy != $last_proy) {
                /* Filtramos los Proyectos del Concurso */
                echo ($newTR);
                $newTR = "";
                
                $rowspanProyecto = 0;
                for ($i = 0; $i < count($arrValues['t60_id_desemb']); $i ++) {
                    if ($arrValues['concurso'][$i] == $conc && $arrValues['codigo'][$i] == $cod_proy) {
                        $rowspanProyecto ++;
                    }
                }
                
                ?>
  				  <td rowspan="<?php echo($rowspanProyecto );?>" height="43"
							align="center" valign="middle"><?php echo($arrValues['codigo'][$y]); ?><br /></td>
						<td rowspan="<?php echo($rowspanProyecto );?>" align="left"
							valign="middle"><?php echo($arrValues['siglas'][$y]); ?></td>
						<td rowspan="<?php echo($rowspanProyecto );?>" align="left"
							valign="middle"><strong><?php echo($arrValues['siglas_moni'][$y]); ?></strong></td>
						<td rowspan="<?php echo($rowspanProyecto );?>" align="left"
							valign="middle"><?php echo($arrValues['titulo'][$y]); ?></td>
						<td rowspan="<?php echo($rowspanProyecto );?>" align="center"
							valign="middle" nowrap="nowrap"><?php echo($arrValues['inicio'][$y]); ?></td>
						<td rowspan="<?php echo($rowspanProyecto );?>" align="center"
							valign="middle" nowrap="nowrap"><?php echo($arrValues['termino'][$y]); ?></td>
            	  
                <?php
                
                for ($z = 0; $z < count($arrValues['t60_id_desemb']); $z ++) {
                    
                    if ($conc == $arrValues['concurso'][$z] && $cod_proy == $arrValues['codigo'][$z]) {
                        /* Filtramos los Proyectos del Concurso */
                        $idDesemb = $arrValues['t60_id_desemb'][$z];
                        // echo("Des. $idDesemb <br>");
                        echo ($newTR);
                        ?>
                          <td align="center" valign="middle">
                          Visita&nbsp;:<?php echo($arrValues['t60_id_visita'][$z]); ?>
                          <br />
                          Pago:<?php echo($arrValues['t60_id_nropago'][$z]); ?>
                          </td>
						<td align="left" valign="middle"><?php echo($arrValues['tipopago'][$z]); ?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo($arrValues['t60_fch_giro'][$z]); ?><br /></td>
						<td align="right" valign="middle" nowrap="nowrap"><span
							style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($arrValues['t60_mto_des'][$z],2)); ?></span><br /></td>
						<td align="left" valign="middle"><?php echo($arrValues['t60_nro_cheque'][$z]); ?><br /></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo($arrValues['t60_fch_depo'][$z]); ?></td>
						<td align="left" valign="middle"><?php echo($arrValues['t60_obs_tippago'][$z]); ?></td>

					</tr>
                        <?php
                        $sumaconcurso += $arrValues['t60_mto_des'][$z];
                        $newTR = "<tr>";
                    }
                }
            }
            $last_proy = $arrValues['codigo'][$y];
        }
        
        ?>
          <tr>
						<td colspan="10" align="center" style="color: #036">Total Desembolsado en el Periodo del Concurso <?php echo($conc);?><br />
						</td>
						<td align="right" valign="middle" nowrap="nowrap"
							style="color: #036"><strong><span
								style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($sumaconcurso,2)); ?></span><br />
						</strong></td>
						<td colspan="3" align="left" valign="middle" style="color: #036"><strong><br />
						</strong></td>
					</tr>
		<?php
        $sumatotal += $sumaconcurso;
    }
    
    ?>

  </tbody>
				<tfoot
					style="text-align: right; font-size: 12px; font-weight: bold;">
					<tr>
						<td height="25" align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle"><strong>TOTALES</strong></td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="right" valign="middle">&nbsp;</td>
						<td align="right" valign="middle">&nbsp;</td>
						<td align="right" valign="middle">&nbsp;</td>
						<td align="right"><?php echo( number_format($sumatotal,2)); ?></td>
						<td align="right" valign="middle">&nbsp;</td>
						<td align="right" valign="middle">&nbsp;</td>
						<td align="right" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>