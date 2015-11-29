<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

$Consurso = $objFunc->__Request('cboconcurso');
$idInstitucion = $objFunc->__Request('cboinstitucion');

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$id = $objFunc->__POST('t46_id');

if ($idProy == "" && $id == "") {
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('t46_id');
}

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Coronograma Visitas</title>
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
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr>
						<td width="29" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9">&nbsp;</td>
						<td width="67" rowspan="2" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#E9E9E9"><strong>Ejecutor</strong></td>
						<td width="66" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Proyecto</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Periodo
								de Ejecucion</strong></td>
						<td width="74" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Monitor Técnico</strong></td>
						<td width="74" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Monitor Financiero</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Datos
								de la Institución Ejecutora para el Desembolso</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#CAC5FA"><strong>
								Aprobación MT y MF</strong></td>
						<td colspan="5" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Administración</strong></td>
					</tr>
					<tr>
						<td width="46" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Inicio</strong></td>
						<td width="64" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Termino</strong></td>
						<!--
                        // -------------------------------------------------- >
                        // AQ 2.0 [23-11-2013 17:33]
                        // Se suprime la columna "Trim. Desemb"
                        // -------------------------------------------------- <
                        -->
                        <!-- <td width="65" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Trim. por desemb.</strong></td> -->
						<td width="79" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Nombre
								del Beneficiario a Girar</strong></td>
						<td width="77" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Entidad
								Financiera</strong></td>
						<td width="59" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Cuenta
								en Nuevos Soles</strong></td>
						<td width="71" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Fecha
								Ultimo Informe Financiero</strong></td>
						<td width="64" align="center" valign="middle" bgcolor="#CAC5FA"><strong>Monto
								Aprobado</strong></td>
						<td width="84" align="center" valign="middle" bgcolor="#CAC5FA"><strong>Fecha
								Aprobación</strong></td>
						<td width="93" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Total
								Desembolsado</strong></td>
						<td width="93" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Saldo
								x Desembolsar</strong></td>
						<td width="91" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Nro
								Transac.</strong></td>
						<td width="91" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Fecha
								del Ultimo desembolso</strong></td>
						<td width="91" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Observaciones</strong></td>
					</tr>
				</tbody>
				<tbody class="data">
	<?php
$objFE = new BLFE();
$iRs = $objFE->ListadoProyectos_EjecDesembolsos($Consurso, $idInstitucion);
if ($iRs->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($iRs)) {
        printEjecDesembListRow($row, $objFE);
    } // while
    $iRs->free();
} // if
else {
    ?>
		<tr>
						<td colspan="19" align="left" valign="middle"><strong
							style="color: #F00">No hay proyectos con la busqueda requerida.
								Solo se muetran los proyectos que fueron aprobados desembolsos y
								que aun no se se han completado el total desembolsado. (Err 003)</strong>
						</td>
					</tr>
	<?php
} // else
function printEjecDesembListRow($row, $objFE)
{
    $aCodProy = $row["t02_cod_proy"];
    $aTrimDesemb = $row['trim_desembolsar'];
    if ($aTrimDesemb == 1) {
        $anIdAprov = $row['idaprobacion'];
        $jsCallViewAprov = "viewAprobacion1('$aCodProy', '$anIdAprov')";
        $rAprob = $objFE->Aprobacion_Primer_Desemb_Seleccionar($anIdAprov);
        $aMontoAprov = $rAprob['t59_mto_aprob'];
        $aFechaAprov = $rAprob['t59_fch_aprob'];
    } else {
        $aMontoPlanTr = $row["monto_plan_trim"];
        $jsCallViewAprov = "viewAprobacion('$aCodProy', '$aTrimDesemb', '$aMontoPlanTr')";
        $rAprob = $objFE->Aprobacion_Desemb_Seleccionar($row['t02_cod_proy'], $aTrimDesemb);
        $aMontoAprov = $rAprob['t60_mto_aprob'];
        $aFechaAprov = $rAprob['t60_fch_aprob'];
    }
    ?>
			<tr class="RowData"
						ondblclick="EditarEjecucion('<?php echo($row["t02_cod_proy"]);?>','<?php echo($row["trim_desembolsar"]);?>','<?php echo($row["idaprobacion"]);?>'); return false;"
						<?php if($row['nro_trans']>0){echo('style="color:#093;"');}?>>
						<td align="center" valign="middle" nowrap="nowrap"><a
							href="javascript:"> <img src="../../../img/bullet.gif" alt=""
								width="14" height="14" border="0" title="Ver mas Datos"
								onclick="EditarEjecucion('<?php echo($row["t02_cod_proy"]);?>','<?php echo($row["trim_desembolsar"]);?>','<?php echo($row["idaprobacion"]);?>'); return false;" />
						</a></td>
						<td height="30" align="center" valign="middle"><?php echo($row['t01_sig_inst']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_cod_proy']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_fch_ini']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_fch_tre']);?></td>
						<!--
                        // -------------------------------------------------- >
                        // AQ 2.0 [23-11-2013 17:33]
                        // Se suprime la columna "Trim. Desemb"
                        // -------------------------------------------------- <
                        -->
						<!-- <td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['trim_desembolsar']);?></td> -->
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_tema']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_fina']);?></td>
						<td align="left" valign="middle"><?php echo($row['bene_girar']);?></td>
						<td align="left" valign="middle"><?php echo($row['banco']);?></td>
						<td align="center" valign="middle"><?php echo($row['nrocuenta']);?></td>
						<td align="center" valign="middle"><?php echo($row['fec_ult_informe']);?></td>
						<td align="right" valign="middle" bgcolor="#CAC5FA"><a
							href="javascript:;" onclick="<?php echo $jsCallViewAprov; ?>;"
							title="Ver Detalle de la Aprobación">
						<?php echo( number_format($aMontoAprov, 2));?>
					</a></td>
						<td align="center" valign="middle" bgcolor="#CAC5FA"><a
							href="javascript:;" onclick="<?php echo $jsCallViewAprov; ?>;"
							title="Ver Detalle de la Aprobación">
						<?php echo( $aFechaAprov);?>
					</a></td>
						<td align="right" valign="middle" bgcolor="#FFFF9D"><?php echo( number_format($row['desembolso'],2));?></td>
						<td align="right" valign="middle" bgcolor="#FFFF9D"><?php echo( number_format($aMontoAprov - ($row['desembolso'] ? $row['desembolso'] : 0),2));?></td>
						<td align="center" valign="middle" bgcolor="#FFFF9D"><?php echo( $row['nro_trans']);?></td>
						<td align="center" valign="middle" bgcolor="#FFFF9D"><?php echo( $row['fec_ult_desemb']);?></td>
						<td align="left" valign="middle" bgcolor="#FFFF9D"><?php echo( nl2br( $row['observaciones']));?></td>
					</tr>
        	<?php
} // end: function printEjecDesembListRow
?>
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
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />

			<script language="javascript" type="text/javascript">

function viewAprobacion1(idproy, idapro)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>

	var url = "prim_desemb_edit.php?action=<?php echo(md5("ajax_view"));?>&idProy="+idproy+"&idAprob="+idapro ;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Aprobación del Primer Desembolsos");?>",url);

	return;
  }

function viewAprobacion(idproy, idtrim, mtoplan)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>

	var url = "aprob_desemb_edit.php?action=<?php echo(md5("ajax_view"));?>&idProy="+idproy+"&idTrimEjec="+idtrim+"&mtoplan="+mtoplan;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Aprobación de Desembolsos");?>",url);

	return;
  }

 function EditarEjecucion(idProy, idTrim, idAprob)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>

	var url = "ejec_desemb_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&idTrim="+idTrim+"&idAprobacion="+idAprob;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Ejecución de Desembolsos");?>",url);

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