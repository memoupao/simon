<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLFuentes.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');
$idMes = $objFunc->__Request('idMes');
$idFte = $objFunc->__Request('idFte');

$HardCode = new HardCode();
if ($idFte == '') {
    $idFte = $HardCode->codigo_Fondoempleo;
}

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$rowProy = $objProy->ProyectoSeleccionar($idProy, $ultima_vs);
$objProy = NULL;

$objInformes = new BLInformes();

$rowInf = $objInformes->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);

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
						class="ClassField" style="text-transform: uppercase;">RESUMEN FINANCIERO DEL PROYECTO AL <?php echo($objFunc->FechaLarga($rowInf['t40_fch_pre']));?></span></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField">(Expresados en Moneda Nacional)</span></td>
				</tr>
			</table>
			<BR />
			<table width="950" cellpadding="0" cellspacing="0">

				<thead>
					<tr>
						<td colspan="9" align="left">
							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								class="TableEditReg" style="border: none;">
								<tr>
									<td width="15%" height="29" align="center" nowrap="nowrap"><strong>Fuente
											Financiamiento</strong></td>
									<td width="85%">&nbsp; <select name="cboFteFinanc"
										id="cboFteFinanc" style="width: 320px;"
										onchange="ViewAnexoContrapartida();">
           <?php
        $objFte = new BLFuentes();
        $Rs = $objFte->ContactosListado($idProy);
        $objFunc->llenarCombo($Rs, "t01_id_inst", "t01_sig_inst", $idFte);
        
        $row = $objFte->ContactosSeleccionar($idProy, $idFte);
        $fuente = $row['t01_sig_inst'];
        $objFte = NULL;
        ?>
         </select></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="115" rowspan="2" align="center" valign="bottom"
							style="border: none;">MES</td>
						<td width="126" rowspan="2" align="center" valign="top"
							style="border: none;">CONCEPTO</td>
						<td width="115" rowspan="2" align="center">TOTAL MONTO
							DESEMBOLSADO</td>
						<td width="119" rowspan="2" align="center">TOTAL MONTO <br />PRESUPUESTADO
							<br />(<?php echo($fuente);?>)</td>
						<td colspan="3" align="center" valign="middle">MONTO EJECUTADO</td>
						<td width="88" rowspan="2" align="center">OTROS INGRESOS</td>
						<td width="107" rowspan="2" align="center">ABONOS DEL BANCO</td>
					</tr>

					<tr>
						<td width="102" align="center">Gastos (sin créditos)</td>
						<td width="98" align="center">Créditos otorgados</td>
						<td width="78" align="center">Total&nbsp;</td>
					</tr>
				</thead>
				<tbody class="data">
   <?php
$irsItems = $objInformes->RepInforme_Anexo01($idProy, $idAnio, $idMes, $idFte);
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
	    $suma['mto_tot_des'] += $rc['mto_tot_des'];
	    $suma['tot_mto_pre'] += $rc['tot_mto_pre'];
	    $suma['gasto_ejecutado_sc'] += $rc['gasto_ejecutado_sc'];
	    $suma['gasto_ejecutado_cc'] += $rc['gasto_ejecutado_cc'];
	    $suma['gasto_ejecutado_tot'] += ($rc['gasto_ejecutado_sc'] + $rc['gasto_ejecutado_cc']);
	    $suma['otros_ingresos'] += $rc['otros_ingresos'];
	    $suma['abono_bancos'] += $rc['abono_bancos'];
	?>
    <tr>
		<td colspan="2" align="left"><?php echo(nl2br($rc['concepto']));?></td>
		<td align="right"><?php echo(number_format($rc['mto_tot_des'],2));?></td>
		<td align="right"><?php echo(number_format($rc['tot_mto_pre'],2));?></td>
		<td align="right"><?php echo(number_format($rc['gasto_ejecutado_sc'],2));?></td>
		<td align="right"><?php echo(number_format($rc['gasto_ejecutado_cc'],2));?></td>
		<td align="right"><?php echo(number_format( ($rc['gasto_ejecutado_sc']+$rc['gasto_ejecutado_cc']) ,2));?></td>
		<td align="right"><?php echo(number_format($rc['otros_ingresos'],2));?></td>
		<td align="right"><?php echo(number_format($rc['abono_bancos'],2));?></td>
	</tr>
       			
    <?php
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
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['gasto_ejecutado_tot'] ,2));?></td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['otros_ingresos'],2));?></td>
						<td align="right" bgcolor="#E8E8E8"><?php echo(number_format($suma['abono_bancos'],2));?></td>
					</tr>
					<tr>
						<td height="32" colspan="8" valign="bottom"
							style="border-right: none;"><strong>EXCEDENTE POR EJECUTAR</strong></td>
						<td align="right" valign="bottom" style="border-left: none;"><strong><?php echo(number_format(  (($suma['mto_tot_des']-$suma['gasto_ejecutado_tot'])+$suma['otros_ingresos'])  ,2));?></strong></td>
					</tr>
				</tbody>
			</table>
			<p>
				<br />
			</p>

			<br />
			<script>
function ViewAnexoContrapartida()
{
	var arrayParams = new Array();
	arrayParams[0]  = "idProy=<?php echo($idProy); ?>" ; 
	arrayParams[1]  = "idAnio=<?php echo($idAnio); ?>" ;  
	arrayParams[2]  = "idMes=<?php echo($idMes); ?>" ;  
	arrayParams[3]  = "idFte=" + $("#cboFteFinanc").val() ;  
	var parameters  = arrayParams.join("&") ;
	$("#txtparams").val( parameters );
	RefreshReport();
	
}

</script>
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>