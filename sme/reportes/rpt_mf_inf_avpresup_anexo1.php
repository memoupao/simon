<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");

$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idInforme');
// $idMes = $objFunc->__Request('idMes');

$HardCode = new HardCode();
$idFte = $HardCode->codigo_Fondoempleo;

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$rowProy = $objProy->ProyectoSeleccionar($idProy, $ultima_vs);
$objProy = NULL;

$objInformes = new BLMonitoreoFinanciero();
$rowInf = $objInformes->Inf_MF_Seleccionar($idProy, $idInforme);

/*
 * echo("<pre>"); print_r($rowProy); print_r($rowInf); echo("</pre>");
 */
?>


<?php if($objFunc->__QueryString()=="") { ?>
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
			<table width="950" cellpadding="0" cellspacing="0"
				style="border: none;">
				<tr>
					<td width="1176" colspan="8" align="center" valign="middle"><span
						class="ClassField" style="text-transform: uppercase;">CONVENIO&nbsp; INSTITUCION (<?php echo($rowProy['t01_sig_inst']);?>)  -    FONDOEMPLEO</span></td>
				</tr>
				<tr>
					<td width="1176" colspan="8" rowspan="2" align="center"
						valign="middle"><span class="ClassField">&quot;<font
							style="text-transform: uppercase"><?php echo($rowProy['t02_nom_proy']);?></font>&quot;
					</span></td>
				</tr>
				<tr>
				</tr>
				<tr>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField" style="text-transform: uppercase;">RESUMEN FINANCIERO    DEL PROYECTO AL <?php echo($objFunc->FechaLarga($rowInf['t40_fch_pre']));?></span></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField">(Expresados en S/. Nuevos Soles)</span></td>
				</tr>
				<tr>
					<td colspan="8" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="8" align="left" valign="middle">Inicio del Proyecto :
						<span class="ClassField" style="text-transform: uppercase;"><?php echo($rowProy['ini']);?> </span>
					</td>
				</tr>
				<tr>
					<td colspan="8" align="left" valign="middle">Termino del Proyecto:
						<span class="ClassField" style="text-transform: uppercase;"><?php echo($rowProy['fin']);?> </span>
					</td>
				</tr>
			</table>
			<BR />
			<table width="950" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="115" rowspan="2" align="center" valign="bottom"
							bgcolor="#FFFF99" style="border: none;">MES</td>
						<td width="126" rowspan="2" align="center" valign="top"
							bgcolor="#FFFF99" style="border: none;">CONCEPTO</td>
						<td width="115" rowspan="2" align="center" bgcolor="#FFFF99">TOTAL
							MONTO DESEMBOLSADO</td>
						<td width="119" rowspan="2" align="center" bgcolor="#FFFF99">TOTAL
							MONTO PRESUPUESTADO (FONDOEMPLEO)</td>
						<td colspan="4" align="center" valign="middle" bgcolor="#FFFF99">MONTO
							EJECUTADO</td>
						<td width="107" rowspan="2" align="center" bgcolor="#FFFF99">ABONOS
							DEL BANCO</td>
					</tr>

					<tr>
						<td width="102" align="center" bgcolor="#FFFF99">Gastos (sin
							créditos)</td>
						<td width="98" align="center" bgcolor="#FFFF99">Créditos
							otorgados</td>
						<td width="98" align="center" bgcolor="#FFFF99">AJUSTE AL MONTO
							EJECUTADO</td>
						<td width="78" align="center" bgcolor="#FFFF99">Total&nbsp;</td>
					</tr>
				</thead>
				<tbody class="data">
   <?php
$irsItems = $objInformes->RepInforme_Financ_Anexo01($idProy, $idInforme);
$rc = mysqli_fetch_assoc($irsItems);
$total_proy = $rc['mto_tot_des'];
?>
   <tr>
						<td colspan="2" align="left"><?php echo($rc['concepto']);?></td>
						<td align="right">&nbsp;</td>
						<td align="right"><?php echo(number_format($rc['tot_mto_pre'],2));?></td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
   <?php $rc = mysqli_fetch_assoc($irsItems) ;  ?>
   <tr>
						<td colspan="2" align="left" nowrap="nowrap"><?php echo($rc['concepto']);?></td>
						<td align="right">&nbsp;</td>
						<td align="right"><?php echo(number_format($rc['tot_mto_pre'],2));?></td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
   <?php $rc = mysqli_fetch_assoc($irsItems) ;  ?>   
   <tr>
						<td colspan="2" align="left"><?php echo($rc['concepto']);?></td>
						<td align="right">&nbsp;</td>
						<td align="right"><?php echo(number_format($rc['tot_mto_pre'],2));?></td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
   <?php $rc = mysqli_fetch_assoc($irsItems) ;  ?>
   <tr>
						<td colspan="2" align="left"><?php echo($rc['concepto']);?></td>
						<td align="right"><?php echo(number_format($rc['mto_tot_des'],2));?></td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
    <?php $rc = mysqli_fetch_assoc($irsItems) ;  ?>
   <tr>
						<td colspan="2" align="left"><?php echo($rc['concepto']);?></td>
						<td align="right"><?php echo(number_format($rc['mto_tot_des'],2));?></td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="2" align="left">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
   <?php
$suma = NULL;
while ($rc = mysqli_fetch_assoc($irsItems)) {
    $tipo = $rc['tipo'];
    $rc['gasto_ejecutado_tot'] = ($rc['gasto_ejecutado_sc'] + $rc['gasto_ejecutado_cc']) - $rc['gasto_no_aceptado'];
    
    if ($tipo == 'tri') {
        
        $suma['mto_tot_des'] += $rc['mto_tot_des'];
        $suma['tot_mto_pre'] += $rc['tot_mto_pre'];
        $suma['gasto_ejecutado_sc'] += $rc['gasto_ejecutado_sc'];
        $suma['gasto_ejecutado_cc'] += $rc['gasto_ejecutado_cc'];
        $suma['gasto_ejecutado_tot'] += $rc['gasto_ejecutado_tot'];
        $suma['gasto_no_aceptado'] += $rc['gasto_no_aceptado'];
        $suma['otros_ingresos'] += $rc['otros_ingresos'];
        $suma['abono_bancos'] += $rc['abono_bancos'];
        
        ?>
    <tr <?php if($rc['trimestre']==1){echo('style="display:none;"');} ?>>
						<td colspan="2" align="left">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="left" bgcolor="#C8F0C6"><?php echo(nl2br($rc['concepto']));?></td>
						<td align="right" bgcolor="#C8F0C6"><?php echo(number_format($rc['mto_tot_des'],2));?></td>
						<td align="right" bgcolor="#C8F0C6"><?php echo(number_format($rc['tot_mto_pre'],2));?></td>
						<td align="right" bgcolor="#C8F0C6"><?php echo(number_format($rc['gasto_ejecutado_sc'],2));?></td>
						<td align="right" bgcolor="#C8F0C6"><?php echo(number_format($rc['gasto_ejecutado_cc'],2));?></td>
						<td align="right" bgcolor="#C8F0C6" style="color: #00F">(<?php echo(number_format($rc['gasto_no_aceptado'],2));?>)</td>
						<td align="right" bgcolor="#C8F0C6"><?php echo(number_format( $rc['gasto_ejecutado_tot'] ,2));?></td>
						<td align="right" bgcolor="#C8F0C6"><?php echo(number_format($rc['abono_bancos'],2));?></td>
					</tr>
   
   <?php
    
} else {
        ?>
        <tr>
						<td colspan="2" align="left"><?php echo(nl2br($rc['concepto']));?></td>
						<td align="right"><?php echo(number_format($rc['mto_tot_des'],2));?></td>
						<td align="right"><?php echo(number_format($rc['tot_mto_pre'],2));?></td>
						<td align="right"><?php echo(number_format($rc['gasto_ejecutado_sc'],2));?></td>
						<td align="right"><?php echo(number_format($rc['gasto_ejecutado_cc'],2));?></td>
						<td align="right" style="color: #00F">(<?php echo(number_format($rc['gasto_no_aceptado'],2));?>)</td>
						<td align="right"><?php echo(number_format( $rc['gasto_ejecutado_tot'] ,2));?></td>
						<td align="right"><?php echo(number_format($rc['abono_bancos'],2));?></td>
					</tr>
    <?php
    }
}
?>

   </tbody>
				<tbody class="data">
					<tr>
						<td colspan="2" align="left" bgcolor="#E8E8E8">TOTAL GENERAL</td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['mto_tot_des'],2));?></td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['tot_mto_pre'],2));?></td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['gasto_ejecutado_sc'],2));?></td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['gasto_ejecutado_cc'],2));?></td>
						<td align="right" bgcolor="#E8E8E8" style="color: #00F">(<?php echo(number_format($suma['gasto_no_aceptado'],2));?>)</td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['gasto_ejecutado_tot'] ,2));?></td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['abono_bancos'],2));?></td>
					</tr>
					<tr>
						<td height="32" colspan="8" valign="bottom"
							style="border-right: none;"><strong>EXCEDENTE POR EJECUTAR</strong></td>
						<td align="right" valign="bottom" style="border-left: none;"><strong><?php echo(number_format(  (($suma['mto_tot_des']-$suma['gasto_ejecutado_tot'])+$suma['otros_ingresos'])  ,2));?></strong></td>
					</tr>
				</tbody>
			</table>
			<br />
			<table width="950" border="0" cellspacing="0" cellpadding="0">
				<caption>El ajuste al presupuesto ejecutado corresponde a lo
					siguiente:</caption>
 <?php
$irs = $objInformes->RepInforme_Financ_Anexo01_Detalle($idProy, $idInforme);
$total = 0;
while ($r = mysqli_fetch_assoc($irs)) {
    if ($r['tipo'] == resumen) {
        $total += $r['t09_mto_no_acept'];
        ?>
  <tr>
					<td width="232" align="left"><strong><?php echo($r['periodo']);?></strong></td>
					<td width="118" align="right"></td>
					<td width="116" align="right"><strong><?php echo(number_format($r['t09_mto_no_acept'],2));?></strong></td>
					<td width="482" align="left">&nbsp;</td>
				</tr>
 <?php } else { ?> 
  <tr>
					<td align="left">&nbsp;&nbsp;<?php echo($r['t51_obs']);?></td>
					<td align="right"><?php echo(number_format($r['t09_mto_no_acept'],2));?></td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
 <?php } } ?>
 <tr>
					<td align="left">&nbsp;</td>
					<td align="right">=============</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="left"><strong>Total </strong></td>
					<td align="right"><strong><?php echo(number_format($total,2));?></strong></td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
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