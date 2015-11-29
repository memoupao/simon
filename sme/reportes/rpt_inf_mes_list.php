<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

$idProy = $objFunc->__Request('idProy');

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $ultima_vs);

$objInf = new BLInformes();
$rowInf = $objInf->InformeMensualListado($idProy);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe Mensual</title>
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
a {
	cursor: pointer;
}
</style>
			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">CODIGO DEL PROYECTO</th>
					<td width="55%" align="left"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
					<th width="7%" align="left" nowrap="nowrap">INICIO</th>
					<td width="20%" align="left"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">TITULO DEL PROYECTO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td align="left">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td rowspan="2" align="center" valign="middle">AÑO</td>
						<td rowspan="2" align="center" valign="middle">MES</td>
						<td rowspan="2" align="center" valign="middle">PERIODO</td>
						<td rowspan="2" align="center" valign="middle">FECHA DE
							PRESENTACIóN</td>
						<td rowspan="2" align="center" valign="middle">ESTADO</td>
						<td colspan="2" align="center" valign="middle">% CUMPLIMIENTO</td>
						<td rowspan="2" align="center" valign="middle">DIFICULTADES Y
							OTROS ASPECTOS</td>
					</tr>
					<tr>
						<td align="center" valign="middle">MES</td>
						<td align="center" valign="middle">ACUM</td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    while ($rInforme = mysqli_fetch_assoc($rowInf)) {
        
        if ($rInforme['cump_mes'] >= 75) {
            $semaforo_mes = 'style="background-color:#70FB60;"';
        }
        if ($rInforme['cump_mes'] >= 50 && $rInforme['cump_mes'] < 75) {
            $semaforo_mes = 'style="background-color:#FC0;"';
        }
        if ($rInforme['cump_mes'] < 50) {
            $semaforo_mes = 'style="background-color:red;"';
        }
        
        if ($rInforme['cump_acum_mes'] >= 75) {
            $semaforo_acum = 'style="background-color:#70FB60;"';
        }
        if ($rInforme['cump_acum_mes'] >= 50 && $rInforme['cump_acum_mes'] < 75) {
            $semaforo_acum = 'style="background-color:#FC0;"';
        }
        if ($rInforme['cump_acum_mes'] < 50) {
            $semaforo_acum = 'style="background-color:red;"';
        }
        
        ?>
    <tr style="min-height: 35px; height: 35px;">
						<td width="7%" align="center" valign="middle"><?php echo($rInforme['anio']); ?></td>
						<td width="8%" align="center" valign="middle"><?php echo($rInforme['mes']); ?></td>
						<td width="14%" align="center" valign="middle"><?php echo($rInforme['periodo']); ?></td>
						<td width="10%" align="center" valign="middle"><a
							style="color: #036; text-decoration: underline;"
							href"#" onclick="RepInfo('<?php echo($idProy); ?>', '<?php echo($rInforme['t20_anio']); ?>', '<?php echo($rInforme['t20_mes']); ?>')"
							title="Ver el Informe Mensual">
	  <?php echo($rInforme['fec_pre']); ?>
      </a></td>
						<td width="8%" align="center" valign="middle"><?php echo($rInforme['estado']); ?></td>
						<td width="8%" align="right" valign="middle"
							<?php echo($semaforo_mes)?>><a
							style="color: #336; text-decoration: underline;"
							href"#" onclick="RepAvanceMes('<?php echo($idProy); ?>', '<?php echo($rInforme['t20_anio']); ?>', '<?php echo($rInforme['t20_mes']); ?>', '<?php echo($rInforme['vsinf']); ?>')"
							title="Ver Detalle del Cumplimiento">
       <?php echo($rInforme['cump_mes']); ?>
       </a> &nbsp;&nbsp;</td>
						<td width="8%" align="right" valign="middle"
							<?php echo($semaforo_acum)?>><a
							style="color: #336; text-decoration: underline;"
							href"#" onclick="RepAvanceAcum('<?php echo($idProy); ?>', '<?php echo($rInforme['t20_anio']); ?>', '<?php echo($rInforme['t20_mes']); ?>', '<?php echo($rInforme['vsinf']); ?>')"
							title="Ver Detalle del Cumplimiento">
	  <?php echo($rInforme['cump_acum_mes']); ?>
      </a> &nbsp;&nbsp;</td>
						<td width="37%" align="left" valign="middle"><?php echo(nl2br( substr($rInforme['dificultades'],0,90 ))); ?></td>
					</tr>
	<?php
    } // End While
    $rowInf->free();
    ?>
    </tbody>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td colspan="2" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="2" align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />
			<p>
				<script language="JavaScript" type="text/javascript">
	function RepInfo(idproy, anio, mes)
	{
		var params = "idProy="+idproy+"&anio="+anio+"&mes="+mes;
		NewReport("Informe Mensual","rpt_inf_mes_edit.php",params);
	}
	function RepAvanceMes(idproy, anio, mes, ver)
	{
		var params = "idProy="+idproy+"&anio="+anio+"&mes="+mes+"&vs="+ver;
		NewReport("Cumplimiento del Proyecto","rpt_inf_mes_cumpli.php",params);	
	}
	function RepAvanceAcum(idproy, anio, mes)
	{
		var params = "idProy="+idproy+"&anio="+anio+"&mes="+mes;
		NewReport("Cumplimiento Acumulado del Proyecto","rpt_inf_mes_cumpli_acum.php",params);	
	}
    </script>
			</p>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>