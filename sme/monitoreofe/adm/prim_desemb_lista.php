<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

$Consurso = $objFunc->__Request('cboconcurso');
$idInstitucion = $objFunc->__Request('cboinstitucion');

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form action="#" method="post" enctype="multipart/form-data"
		name="frmMain" id="frmMain">
<?php
}
?>
<div id="divTableLista" class="TableGrid grid-width grid-list">
			<span
				style="font-family: Arial, Helvetica, sans-serif; color: #006; font-weight: bold; text-align: center; font-size: 11px;">
				Proyectos Pendientes por Desembolsar y/o Aprobación del Primer
				Desembolso </span>
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr>
						<td width="31" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9">&nbsp;</td>
						<td width="78" rowspan="2" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#E9E9E9"><strong>Ejecutor</strong></td>
						<td width="73" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Proyecto</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Periodo
								de Ejecucion</strong></td>
						<td width="81" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Monitor Financiero</strong></td>
						<td width="99" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Presupuesto Fondoempleo</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Condiciones
								para el Primer Desembolso</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Resultados
								- Monitor Financiero</strong></td>
					</tr>
					<tr>
						<td width="53" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Inicio</strong></td>
						<td width="71" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Termino</strong></td>
						<td width="67" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Total
								Desem-bolsado</strong></td>
						<td width="99" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
								a Desembolsar Según Cronograma</strong></td>
						<td width="68" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Apertura
								de Cuenta Bancaria</strong></td>
						<td width="56" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Carta
								Fianza Vigente</strong></td>
						<td width="64" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Convenio
								firmado </strong></td>
						<td width="64" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Otros
								Documentos</strong></td>
						<td width="89" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Desemb
								<br> Si / No
						</strong></td>
						<td width="89" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Observaciones</strong></td>
						<td width="91" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Monto
								a Desembolsar</strong></td>
						<td width="97" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Fecha
								Aprobación</strong></td>
					</tr>
				</tbody>
				<tbody class="data">
      <?php
    $objFE = new BLFE();
    $iRs = $objFE->ListadoProyectos_Aprob_Primer_Desembolso($Consurso, $idInstitucion);
    
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $rAprob = $objFE->Aprobacion_Primer_Desemb_Seleccionar($row['t59_id_aprob']);
            if ($rAprob['t59_aprueba_mf'] == '1') {
                $colorpendiente = "style=\"color:#039;\"";
            } else {
                $colorpendiente = "";
            }
            ?>
      <tr class="RowData" <?php echo($colorpendiente);?>
						ondblclick="EditarAprobacion('<?php echo($row["t02_cod_proy"]);?>', '<?php echo( $row['t59_id_aprob']);?>'); return false;">
						<td align="center" valign="middle" nowrap="nowrap"><a
							href="javascript:"> <img src="../../../img/bullet.gif" alt=""
								width="14" height="14" border="0" title="Ver mas Datos"
								onclick="EditarAprobacion('<?php echo($row["t02_cod_proy"]);?>', '<?php echo( $row['trim_desembolsar']);?>','<?php echo($row["monto_plan_trim"]);?>'); return false;" /></a>
						</td>
						<td height="30" align="center" valign="middle"><?php echo($row['t01_sig_inst']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_cod_proy']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_fch_ini']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_fch_tre']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_fina']);?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total_aporte_fe'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total_desembolsado'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['monto_plan_trim'],2));?></td>
						<td align="center" valign="middle"><input type="checkbox" value=""
							<?php if($row['ctabancaria']=='1'){echo("checked=\"checked\"");} ?>
							disabled="disabled" style="border: none;" /></td>
						<td align="center" valign="middle"><input type="checkbox" value=""
							<?php if($row['cartafianza']=='1'){echo("checked=\"checked\"");} ?>
							disabled="disabled" style="border: none;" /></td>
						<td align="center" valign="middle"><input type="checkbox" value=""
							<?php if($row['conv_firma']=='1'){echo("checked=\"checked\"");} ?>
							disabled="disabled" style="border: none;" /></td>
						<td align="center" valign="middle"><input type="checkbox" value=""
							<?php if($row['otros_docum']=='1'){echo("checked=\"checked\"");} ?>
							disabled="disabled" style="border: none;" /></td>

						<td align="center" valign="middle" bgcolor="#F9FAC9"><?php if($rAprob['t59_aprueba_mf']=='1'){echo("Si");}else{echo("No");}?></td>
						<td align="left" valign="middle" bgcolor="#F9FAC9"><span
							style="min-width: 100px;"><?php echo( $rAprob['t59_obs_aprob']);?></span></td>
						<td align="right" valign="middle" bgcolor="#F9FAC9"><?php echo( number_format($rAprob['t59_mto_aprob'],2));?></td>
						<td align="center" valign="middle" bgcolor="#F9FAC9"><?php echo( $rAprob['t59_fch_aprob']);?></td>
					</tr>
      <?php
            $RowIndex ++;
        }
        $iRs->free();
    } else {
        ?>
        <tr>
						<td colspan="17" align="left" valign="middle"><strong
							style="color: #F00">No hay proyectos con la busqueda requerida.
								Solo se muetran los proyectos activos y que tienen registrado
								aporte por parte de Fondoempleo</strong></td>
					</tr>
       <?php  }  ?>
    </tbody>
				<tfoot>
					<tr>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">
function EditarAprobacion(idproy, idaprob)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>	
	
	var url = "prim_desemb_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idproy+"&idAprob="+idaprob;
	loadPopup("<?php echo("Aprobación de Primer Desembolso");?>",url);
	
	return;
  }

</script>
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>