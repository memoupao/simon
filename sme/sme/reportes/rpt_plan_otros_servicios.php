<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLPOA.class.php");
// require(constant('PATH_CLASS')."BLProyecto.class.php");

$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');

$ls_filter = "";
$objProy = new BLProyecto();
$rowp = $objProy->GetProyecto($idProy, $idVersion);

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
			<table width="733" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th align="left">&nbsp;</th>
					<td align="left"><?php echo($ls_filter);?></td>
					<th align="left" nowrap="nowrap">&nbsp;</th>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<th align="left" valign="top">Codigo del Proyecto:</th>
					<td align="left" valign="top"><?php echo($rowp['t02_cod_proy']);?></td>
					<th align="left" valign="top" nowrap="nowrap">Versión:</th>
					<td align="left" valign="top"><?php echo($rowp['version_poa']);?></td>
				</tr>
				<tr>
					<th align="left" valign="top">Nombre del Proyecto:</th>
					<td align="left" valign="top"><?php echo($rowp['t02_nom_proy']);?></td>
					<th align="left" valign="top" nowrap="nowrap">&nbsp;</th>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<th align="left" valign="top">Institución:</th>
					<td align="left" valign="top"><?php echo($rowp['t01_nom_inst']);?></td>
					<th align="left" valign="top" nowrap="nowrap">&nbsp;</th>
					<td align="left" valign="top">&nbsp;</td>
				</tr>
				<tr>
					<th width="18%" align="left">&nbsp;</th>
					<td width="60%" align="left">&nbsp;</td>
					<th width="7%" align="left" nowrap="nowrap">&nbsp;</th>
					<td width="15%" align="left">&nbsp;</td>
				</tr>
			</table>

			<table width="734" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="48" height="26" align="center"
						style="border: solid 1px #CCC;"><strong>Cod.</strong></td>
					<td width="234" align="center" style="border: solid 1px #CCC;"><strong>Actividad</strong></td>
					<td width="157" align="center" style="border: solid 1px #CCC;"><strong>Número
							de Bienes o Servicios a entregar por Beneficiario</strong></td>
					<td width="110" align="center" style="border: solid 1px #CCC;"><strong>Número
							de Beneficiarios que recibirán el Servicio</strong></td>
					<td width="106" align="center" style="border: solid 1px #CCC;"><strong>Total
							de Bienes o Servicios a Entregar</strong></td>
					<td width="79" align="center" style="border: solid 1px #CCC;"><strong>Tipo
						de Servicio</strong></td>
				</tr>
				<tbody class="data">
        <?php
        
        $objPOA = new BLPOA();
        $iRs = $objPOA->PlanOtros_Listado($idProy, $idVersion);
        if ($iRs->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
        <tr class="RowData" style="background-color: #FFF;">
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left">
		  <?php echo($row["descripcion"]);?>
          <br /> <font style="color: red; font-size: 11px;">Unidad
								medida:</font> <font style="color: #00F; font-size: 11px;"><?php echo($row["um"]);?></font>
						</td>
						<td align="center"><?php echo(number_format($row["t12_nro_ent"],0)." ".$row["t12_producto"]);?></td>
						<td align="center"><?php echo(number_format($row["t12_nro_ben"],0));?></td>
						<td align="center"><?php echo( number_format($row["t12_tot_ent"],0)." ".$row["t12_producto"]);?></td>
						<td align="center"><?php echo( $row["tipo"]);?></td>
					</tr>
        <?php
            
} // End While
            $iRs->free();
        }         // End If
        else {
            ?>
        <tr class="RowData">
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
        <?php } ?>
      </tbody>
				<tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th width="48" height="18">&nbsp;</th>
						<th width="234">&nbsp;</th>
						<th width="157">&nbsp;</th>
						<th width="110">&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
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