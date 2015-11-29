<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");

$idProy = $objFunc->__Request('idProy');

$like = $objFunc->__Request('like');
$ls_filter = "";

$inst = $objFunc->__Request('institucion');
$proy = $objFunc->__Request('txtCodProy');
$cargo = $objFunc->__Request('cargo');
$nombre = $objFunc->__Request('txtname');
$region = $objFunc->__Request('region');
$sector = $objFunc->__Request('sector');

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
			<table width="896" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">&nbsp;</th>
					<td width="55%" align="left"><?php echo($ls_filter);?></td>
					<th width="7%" align="left" nowrap="nowrap">&nbsp;</th>
					<td width="20%" align="left">&nbsp;</td>
				</tr>
			</table>

			<table width="896" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td align="center" height="33" valign="middle" width="10%">DNI</td>
						<td align="center" valign="middle" width="29%">APELLIDOS Y NOMBRE</td>
						<td align="center" valign="middle" width="10%">SEXO</td>
						<td align="center" valign="middle" width="25%">TELEFONOS</td>
						<td align="center" valign="middle" width="16%">EMAIL</td>
						<td align="center" valign="middle" width="17%">CARGO</td>
						<td align="center" valign="middle" width="25%">INSTITUCION</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    
    
	<?php
$objEjec = new BLEjecutor();
if ($like) {
    $rsInst = $objEjec->ContactosListadoRepFiltro($like);
} else {
    $rsInst = $objEjec->ContactosListadoRepFiltrosAlt($inst, $proy, $cargo, $nombre, $region, $sector);
}

$Index = 1;

while ($row = mysqli_fetch_assoc($rsInst)) {
    if ($inst != $row['t01_sig_inst']) {
        $flag = 1;
    }
    
    if ($flag == 1) {
        $flag = 0;
        $inst = $row['t01_sig_inst'];
        ?>
		<!--tr bgcolor="#CCCCFF" >
     	<td colspan="5" align="left" valign="middle" ><strong style="color:blue;">INSTITUCION: <?php echo($row['t01_sig_inst']);?></strong></td>
    	</tr-->	
		<?php
    }
    ?>
 	    
    <tr style="font-size: 11px;">
						<td align="center" valign="top"><?php echo($row['t01_dni_cto']);?>&nbsp;</td>
						<td align="left" valign="top"><?php echo($row['nombres']);?>&nbsp;</td>
						<td align="left" valign="top"><?php echo($row['sexo']);?>&nbsp;</td>
						<td align="left" valign="top"><strong>Teléfono</strong>: <?php echo($row['t01_fono_ofi']);?>&nbsp;
        <?php if($row['t01_tel2_cto']){echo(' - ');}; echo($row['t01_tel2_cto']);?>
        <br> <strong>Celular</strong>: &nbsp;<?php echo($row['t01_cel_cto']);?><br>
							<strong>Fax</strong>: &nbsp;<?php echo($row['t01_fax_cto']);?><br>
							<strong>RPM</strong>: &nbsp;<?php echo($row['t01_rpm_cto']);?><br>
							<strong>RPC</strong>: &nbsp;<?php echo($row['t01_rpc_cto']);?><br>
							<strong>NEXTEL</strong>: &nbsp;
        <?php echo($row['t01_nex_cto']);?> &nbsp;</td>
						<td align="left" valign="top"><?php echo($row['t01_mail_cto']);?>
      <?php if($row['t01_mail_cto2']){echo(' - ');}; echo($row['t01_mail_cto2']);?>
      <br></td>
						<td align="center" valign="top"><?php echo($row['cargo']);?></td>
						<td valign="top"><?php echo($row['institucion']);?></td>
					</tr>
    <?php
    $Index ++;
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
					</tr>
				</tfoot>
			</table>
			<br />
			<!--Excel Personalizado-->
			<div id="xlsCustom" style="display: none;">
				<div id="container" class="TableGrid">

					<table width="896" align="center" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<td align="center" height="33" valign="middle" width="10%">DNI</td>
								<td align="center" valign="middle" width="29%">APELLIDOS Y
									NOMBRE</td>
								<td align="center" valign="middle" width="10%">SEXO</td>
								<td align="center" valign="middle" width="25%">TELEFONOS</td>
								<td align="center" valign="middle" width="16%">EMAIL</td>
								<td align="center" valign="middle" width="17%">CARGO</td>
								<td align="center" valign="middle" width="25%">INSTITUCION</td>
							</tr>
							<tr>
								<td align="center" valign="middle">Telefono</td>
								<td align="center" valign="middle">Celular</td>
								<td align="center" valign="middle">Fax</td>
								<td align="center" valign="middle">RPM</td>
								<td align="center" valign="middle">RPC</td>
								<td align="center" valign="middle">NEXTEL</td>
							</tr>
						</thead>

						<tbody class="data" bgcolor="#FFFFFF">
    
    
	<?php
$objEjec = new BLEjecutor();
if ($like) {
    $rsInst = $objEjec->ContactosListadoRepFiltro($like);
} else {
    $rsInst = $objEjec->ContactosListadoRepFiltrosAlt($inst, $proy, $cargo, $nombre, $region, $sector);
}

$Index = 1;

while ($row = mysqli_fetch_assoc($rsInst)) {
    if ($inst != $row['t01_sig_inst']) {
        $flag = 1;
    }
    
    if ($flag == 1) {
        $flag = 0;
        $inst = $row['t01_sig_inst'];
        ?>
		<tr bgcolor="#CCCCFF">
								<td colspan="10" align="left" valign="middle"><strong
									style="color: blue;">INSTITUCION: <?php echo($row['t01_sig_inst']);?></strong></td>
							</tr>	
		<?php
    }
    ?>
 	    
    <tr style="font-size: 11px;">
								<td align="center" valign="top"><?php echo($row['t01_dni_cto']);?>&nbsp;</td>
								<td align="left" valign="top"><?php echo($row['nombres']);?>&nbsp;</td>
								<td align="left" valign="top"><?php echo($row['t01_fono_ofi']);?>&nbsp;
        <?php if($row['t01_tel2_cto']){echo(' - ');}; echo($row['t01_tel2_cto']);?>&nbsp;</td>
								<td align="left" valign="top"><?php echo($row['t01_cel_cto']);?></td>
								<td align="left" valign="top"><?php echo($row['t01_fax_cto']);?></td>
								<td align="left" valign="top"><?php echo($row['t01_rpm_cto']);?></td>
								<td align="left" valign="top"><?php echo($row['t01_rpc_cto']);?></td>
								<td align="left" valign="top"><?php echo($row['t01_nex_cto']);?></td>

								<td align="left" valign="top"><?php echo($row['t01_mail_cto']);?>
      <?php if($row['t01_mail_cto2']){echo(' - ');}; echo($row['t01_mail_cto2']);?>
      <br></td>
								<td align="center" valign="top"><?php echo($row['cargo']);?></td>
								<td valign="top"><?php echo($row['institucion']);?></td>
							</tr>
    <?php
    $Index ++;
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
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- InstanceEndEditable -->
		</div>

<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>