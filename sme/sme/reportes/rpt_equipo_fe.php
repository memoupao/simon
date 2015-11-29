<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLFE.class.php");

$ls_filter = "";

?>


<?php /* if($idProy=='') { ?>
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
<?php } */ ?>
<div id="divBodyAjax" class="TableGrid">
			
			<table width="80%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">&nbsp;</th>
					<td width="55%" align="left"><?php echo($ls_filter);?></td>
					<th width="7%" align="left" nowrap="nowrap">&nbsp;</th>
					<td width="20%" align="left">&nbsp;</td>
				</tr>
			</table>

			<table width="80%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="6%" height="33" align="center" valign="middle">DNI</td>
						<td width="17%" align="center" valign="middle">Apellidos y Nombre</td>
						<td width="5%" align="center" valign="middle">Sexo</td>
						<td width="5%" align="center" valign="middle">Edad</td>
						<td width="19%" align="center" valign="middle">Contacto</td>
						<td width="12%" align="center" valign="middle">Unidad</td>
						<td width="12%" align="center" valign="middle" nowrap="nowrap">Cargo</td>
						<td width="8%" align="center" valign="middle">Calificación</td>
						<td width="25%" align="center" valign="middle"><p>Funciones
								Principales</p></td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    
    $objEquipo = new BLFE();
    $rs = $objEquipo->EquipoListado();
    
    $Index = 1;
    while ($row = mysqli_fetch_assoc($rs)) {
        ?>
    <tr style="font-size: 11px;">
						<td align="center" valign="top"><?php echo($row['dni']);?>&nbsp;</td>
						<td align="left" valign="top"><?php echo($row['nombres']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['sexo']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['edad']);?>&nbsp;</td>
						<td align="left" valign="top"><strong>Teléfono</strong>: <?php echo($row['fono']);?><br />
							<strong>Email</strong>: <?php echo($row['mail']);?>&nbsp;<br /> <strong>Dirección</strong>: <?php echo($row['direccion']);?>&nbsp;</td>
						<td align="center" valign="top"><?php echo($row['unidad']);?></td>
						<td align="center" valign="top"><?php echo($row['cargo']);?></td>
						<td align="center" valign="top"><?php echo($row['calificacion']);?></td>
						<td align="left" valign="top"><?php echo($row['funcion']);?>&nbsp;</td>
					</tr>
    <?php
        $Index ++;
    } // End While
    //$rsInst->free();
    ?>
  </tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="5" align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />
			
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>