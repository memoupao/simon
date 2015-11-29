<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");

$idConcurso = $objFunc->__Request('cboConcurso');
$idInsEjec = $objFunc->__Request('cboEjecutor');

$ls_filter = "";

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Directorio de Instituciones</title>
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

			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="6%" rowspan="2" align="center" valign="middle">CODIGO</td>
						<td width="28%" rowspan="2" align="center" valign="middle">TITULO
							DEL PROYECTO</td>
						<td height="33" colspan="2" align="center" valign="middle">PERIODO
							DE EJECUCION</td>
						<td colspan="5" align="center" valign="middle">DATOS DE LA CUENTA
							BANCARIA DEL PROYECTO</td>
					</tr>
					<tr>
						<td width="6%" height="33" align="center" valign="middle">INICIO</td>
						<td width="6%" align="center" valign="middle">TERMINO</td>
						<td width="14%" align="center" valign="middle">BANCO</td>
						<td width="9%" align="center" valign="middle">TIPO CUENTA</td>
						<td width="11%" align="center" valign="middle">N&deg; CUENTA</td>
						<td width="7%" align="center" valign="middle">MONEDA</td>
						<td width="13%" align="center" valign="middle">BENEFICIARIO A
							GIRAR</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    
    
	<?php
$objEjec = new BLEjecutor();
$rsInst = $objEjec->Rpt_Instituciones_proyectos_cuentas(1, $idConcurso, $idInsEjec);

while ($row = mysqli_fetch_assoc($rsInst)) {
    
    ?>
    
    <tr>
						<td colspan="9" align="left" valign="middle">

							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								style="border: none; padding: 2px;">
								<tr>
									<td width="56%" align="left" style="border: none;"><strong
										style="color: blue; font-size: 14px; text-transform: uppercase;"><?php echo($row['siglas']);?></strong><br />
									<font style="font-size: 10px;"><?php echo($row['nominst']);?></font></td>
									<td width="44%" align="right" style="border: none;">Numero de
										Proyectos en Ejecución: <strong><?php echo($row['nroproy']);?></strong>
									</td>
								</tr>
							</table>
						</td>
					</tr>	

 	 <?php
    $rsProy = $objEjec->Rpt_Instituciones_proyectos_cuentas(2, $idConcurso, $row['codinst']);
    
    while ($r = mysqli_fetch_assoc($rsProy)) {
        
        ?>
    <tr style="font-size: 11px;">
						<td align="center" valign="middle"><?php echo($r['codigo']);?>&nbsp;</td>
						<td align="left" valign="middle"><?php echo($r['titulo']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo(htmlentities($r['inicio']));?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($r['termino']);?>&nbsp;</td>
						<td align="left" valign="middle"><?php echo($r['banco']);?></td>
						<td align="center" valign="middle"><?php echo($r['tcuenta']);?>&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo($r['nrocuenta']);?></td>
						<td align="center" valign="middle"><?php echo($r['moneda']);?>&nbsp;</td>
						<td align="left" valign="middle"><?php echo($r['t02_nom_benef']);?></td>
					</tr>
    <?php
    }
    $rsProy->free();
} // End While
$rsInst->free();
?>
  </tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="7" align="left" valign="middle">&nbsp;</td>
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