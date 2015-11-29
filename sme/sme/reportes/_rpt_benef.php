<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLBene.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);

?>


<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Reporte de Beneficiarios</title>
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
<?php
$objBenef = new BLBene();
$rsBenef = $objBenef->ListadoBeneficiarios($idProy);
$num = mysqli_num_rows($rsBenef);
?>
<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="19%" align="left">CODIGO DEL PROYECTO</th>
					<td width="47%" align="left"><?php echo($ML['t02_cod_proy']);?></td>
					<th width="15%" align="left" nowrap="nowrap">INICIO</th>
					<td width="19%" align="left"><?php echo($ML['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">DESCRIPCION DEL PROYECTO</th>
					<td align="left"><?php echo($ML['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($ML['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th height="20" align="left">&nbsp;</th>
					<td>&nbsp;</td>
					<td align="left"><strong>Total de Beneficiarios</strong></td>
					<td align="left">  <?php echo($num);?>  </td>
				</tr>
			</table>

			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="3%" height="33" align="center" valign="middle">N&ordm;</td>
						<!--Agregado PS-->
						<td width="1%" align="center" valign="middle">Fecha de
							incorporación</td>
						<!--fin de agregado Ps-->
						<td width="2%" align="center" valign="middle">Apellidos y Nombres</td>
						<!--quitado PS<td width="16%" align="center" valign="middle">Datos Generales</td>-->
						<!--Agregado PS-->
						<td width="2%" align="center" valign="middle">DNI</td>
						<td width="2%" align="center" valign="middle">Departamento</td>
						<td width="2%" align="center" valign="middle">Provincia</td>
						<td width="2%" align="center" valign="middle">Distrito</td>
						<td width="2%" align="center" valign="middle">Caserío</td>
						<td width="2%" align="center" valign="middle">Edad</td>
						<td width="2%" align="center" valign="middle">Sexo</td>
						<td width="2%" align="center" valign="middle">Actividad Principal</td>
						<!--fin de agregado Ps-->
						<td width="6%" align="center" valign="middle">Sector Productivo</td>
						<td width="4%" align="center" valign="middle">Sub Sector</td>
						<td width="7%" align="center" valign="middle">Unidades de
							Producción</td>
						<td width="7%" align="center" valign="middle">Total de Unidades de
							Producción</td>
						<td width="7%" align="center" valign="middle">Unidades de
							Producción con el Proyecto</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $Index = 1;
    while ($row = mysqli_fetch_assoc($rsBenef)) {
        ?>
    <tr style="font-size: 11px;">
						<td rowspan="3" align="center" valign="middle"><?php echo($Index);?></td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['fec_ini']);?></td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['nombres']);?></td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['dni']);?> </td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['dpto']);?></td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['prov']);?> </td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['dist']);?> </td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['caserio']);?> </td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['edad']);?> </td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['sexo']);?></td>
						<td rowspan="3" align="left" valign="middle"><?php echo($row['t11_act_princ']);?></td>
						<td align="left" valign="top"><?php echo($row['sector1']);?></td>
						<td align="left" valign="top"><?php echo($row['subsector1']);?></td>
						<td align="center" valign="top"><?php echo($row['uni_prod1']);?></td>
						<td align="center" valign="top"><?php echo($row['t11_tot_unid_prod']);?></td>
						<td align="center" valign="top"><?php echo($row['t11_nro_up_b']);?></td>

					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="top"><?php echo($row['sector2']);?></td>
						<td align="left" valign="top"><?php echo($row['subsector2']);?></td>
						<td align="center" valign="top"><?php echo($row['uni_prod2']);?></td>
						<td align="center" valign="top"><?php echo($row['t11_tot_unid_prod_2']);?></td>
						<td align="center" valign="top"><?php echo($row['t11_nro_up_b_2']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="top"><?php echo($row['sector3']);?></td>
						<td align="left" valign="top"><?php echo($row['subsector3']);?></td>
						<td align="center" valign="top"><?php echo($row['uni_prod3']);?></td>
						<td align="center" valign="top"><?php echo($row['t11_tot_unid_prod_3']);?></td>
						<td align="center" valign="top"><?php echo($row['t11_nro_up_b_3']);?></td>
					</tr>
    <?php
        $Index ++;
    } // End While
    $rsBenef->free();
    ?>
  </tbody>
				<tfoot>
					<tr>
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