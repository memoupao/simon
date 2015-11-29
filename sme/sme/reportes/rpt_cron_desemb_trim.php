<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");

$idProy = $objFunc->__Request('idProy');

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$rowProy = $objProy->ProyectoSeleccionar($idProy, $ultima_vs);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cronograma de Desembolsos</title>
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
			<fieldset style="width: 550px;">
				<legend style="font-size: 11px; font-weight: bold; color: #036;">Datos
					del Proyecto</legend>
				<table width="100%" border="0" align="left" cellpadding="0"
					cellspacing="0" style="padding: 10px;">
					<tr>
						<th width="9%" height="23" align="left">CODIGO</th>
						<td width="67%" align="left"><?php echo($rowProy['t02_cod_proy']);?> - <a
							href="<?php if($rowProy['t01_web_inst']==''){echo('#');}else{echo($objFunc->verifyURL($rowProy['t01_web_inst']));} ?>"
							target="_blank" title="Ir a Pagina web del Ejecutor"><?php echo($rowProy['t01_sig_inst']);?></a></td>
						<th width="7%" align="left" nowrap="nowrap">INICIO</th>
						<td width="17%" align="left"><?php echo($rowProy['ini']);?></td>
					</tr>
					<tr>
						<th align="left">NOMBRE <br /></th>
						<th height="18" align="left"><?php echo($rowProy['t02_nom_proy']);?></th>
						<th align="left" nowrap="nowrap">TERMINO</th>
						<td align="left"><?php echo($rowProy['fin']);?></td>
					</tr>
					<tr>
						<td align="left" nowrap="nowrap"><strong>FINALIDAD</strong></td>
						<td height="34" colspan="3"><?php echo($rowProy['t02_fin']);?></td>
					</tr>
				</table>
			</fieldset>
			<br />
			<div class="TableGrid">
				<table width="560" border="0" cellspacing="0" cellpadding="0"
					style="color: #000;">
					<thead>
						<tr>
							<th colspan="5" align="center">Desembolsos</th>
						</tr>
						<tr>
							<td width="21%" rowspan="2" align="center">Trim</td>
							<td colspan="2" align="center">Programado</td>
							<td colspan="2" align="center">Ejecutado</td>
						</tr>
						<tr>
							<td width="16%" align="center">Fecha</td>
							<td width="23%" align="center">Monto</td>
							<td width="19%" align="center">Fecha</td>
							<td width="21%" align="center">Monto</td>
						</tr>
					</thead>
					<tbody class="data">
    <?php
    $objPresup = new BLPresupuesto();
    $rsDesemb = $objPresup->RepCronogramaDesembolsoTrimestral($idProy);
    $anioInicio = 0;
    $monto_planeado_trim = 0;
    $monto_desemb_trim = 0;
    
    $monto_planeado = 0;
    $monto_desemb = 0;
    
    while ($r = mysqli_fetch_assoc($rsDesemb)) {
        $monto_planeado_trim += $r['monto_planeado_trim'];
        $monto_desemb_trim += $r['monto_desemb_trim'];
        
        $monto_planeado += $r['monto_planeado_trim'];
        $monto_desemb += $r['monto_desemb_trim'];
        ?>
        
        <?php if($r['anio']!=$anioInicio) { ?>
        
        <?php if($anioInicio > 0 ) { ?>
        <tr class="RowData" style="background-color: #E9E9E9;">
							<td align="center" style="font-size: 9px;">&nbsp;</td>
							<td align="center">&nbsp;</td>
							<td align="right"><?php echo( number_format($monto_planeado - $r['monto_planeado_trim'],2)) ?></td>
							<td align="center">&nbsp;</td>
							<td align="right"><?php echo( number_format($monto_desemb - $r['monto_desemb_trim'],2)) ?></td>
						</tr>
        <?php
                $monto_planeado = $r['monto_planeado_trim'];
                $monto_desemb = $r['monto_desemb_trim'];
            }
            ?>
        
        <tr>
							<td colspan="5" style="background-color: #EFF4B0;"><?php echo("Año: ".$r['anio']); ?></td>
						</tr>
        <?php
            
$anioInicio = $r['anio'];
        }
        ?>
        <tr class="RowData">
							<td align="center" style="font-size: 9px;"><?php echo("Trim ".$r['trimestre']."<br>(".$r['fec_ini_trim']." - ".$r['fec_ter_trim'].")") ?></td>
							<td align="center"><?php echo($r['inicio_trim']) ?></td>
							<td align="right"><?php echo( number_format($r['monto_planeado_trim'],2)) ?></td>
							<td align="center"><?php echo($r['fecha_desemb']) ?></td>
							<td align="right"><?php echo( number_format($r['monto_desemb_trim'],2)) ?></td>
						</tr>
    <?php
    
}
    $rsDesemb->free();
    ?>
  </tbody>

					<tbody class="data">
						<tr class="RowData" style="background-color: #E9E9E9;">
							<td align="center" style="font-size: 9px;">&nbsp;</td>
							<td align="center">&nbsp;</td>
							<td align="right"><?php echo( number_format($monto_planeado,2)) ?></td>
							<td align="center">&nbsp;</td>
							<td align="right"><?php echo( number_format($monto_desemb,2)) ?></td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>TOTALES</strong></td>
							<td align="right"><strong><?php echo( number_format($monto_planeado_trim,2)) ?></strong></td>
							<td align="right">&nbsp;</td>
							<td align="right"><strong><?php echo( number_format($monto_desemb_trim,2)) ?></strong></td>
						</tr>
					</tbody>

				</table>
			</div>
			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>