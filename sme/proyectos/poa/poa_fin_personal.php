<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLManejoProy.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Personal del Proyecto</title>
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
<div id="toolbar" style="height: 4px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="9%"><button class="Button"
							onclick="NuevoPersonal(); return false;" value="Nuevo"
							style="white-space: nowrap">Nuevo</button></td>
					<td width="37%"><button class="Button"
							onclick="LoadPersonal(true); return false;"
							value="Recargar Listado" style="white-space: nowrap">Refrescar</button></td>
					<td width="52%" align="right"><span
						style="color: #036; font-weight: bold; font-size: 12px;">Retribuciones
							del Personal </span></td>
					<td width="2%" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div id="divTableLista" class="TableGrid">
			<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="40" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="28" rowspan="2" align="center"
						style="border: solid 1px #CCC;">#</td>
					<td width="224" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cargo Personal</td>
					<td width="68" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="33" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="63" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unitario</td>
					<td width="82" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td colspan="6" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="78" height="20" align="center"
						style="border: solid 1px #CCC;">FE</td>
					<td width="47" align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td width="85" align="center" style="border: solid 1px #CCC;">Otras
						Fuentes</td>
					<td width="85" align="center" style="border: solid 1px #CCC;">Total</td>
					<td width="26" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="26" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
				</tr>

				<tbody class="data">
      <?php
    
    $objMP = new BLManejoProy();
    $iRs = $objMP->Personal_Listado($idProy, $idVersion);
    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sum_total += round($row["gasto_tot"],2,PHP_ROUND_HALF_UP);
            $sum_fte_fe += $row["fte_fe"];
            $sum_fte_otro += $row["otros"];
            $sum_ejecutor += $row["ejecutor"];
            
            $totFte = $row["fte_fe"] + $row["ejecutor"] + $row["otros"];
            $style = number_format($totFte, 2) != number_format($row["gasto_tot"], 2) ? 'style="background-color:#FF0;color:#F00; text-decoration:blink;"' : '';
            
            ?>
      <tr class="RowData" style="background-color: #FFF;">
						<td nowrap="nowrap"><span> <a href="javascript:"><img
									src="../../../img/pencil.gif" alt="" width="14" height="14"
									border="0" title="Editar Registro"
									onclick="EditarPersonal('<?php echo($row["codigo"]);?>'); return false;" /></a>

						</span></td>
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left">  <?php echo($row["cargo"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format($row["promedio"],2));?></td>
						<td align="right"><font <?php echo($style);?>><?php echo( number_format($row["gasto_tot"],2));?></font></td>
						<td width="64" align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td width="64" align="right"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td width="64" align="right"><?php echo(number_format($row["otros"],2));?></td>
						<td width="64" align="right" bgcolor="#EEEEEE"><font
							<?php echo($style);?>><?php echo(number_format($row["fte_fe"]+$row["ejecutor"]+ $row["otros"],2));?></font></td>
						<td align="center"><a href="#"><img src="../../../img/financ.gif"
								alt="" width="16" height="16" border="0"
								title="Agregar o Modificar Fuentes de Financiamiento"
								onclick="LoadPersonalFTE('<?php echo($row["codigo"]);?>'); return false;" /></a></td>
						<td nowrap="nowrap"><span> <a href="javascript:"><img
									src="../../../img/bt_elimina.gif" alt="" width="14" height="14"
									border="0" title="Eliminar Registro"
									onclick="EliminarPersonal('<?php echo($row["codigo"]);?>','<?php echo($row["cargo"]);?>');" /></a>
						</span></td>
					</tr>
        <?php
        
} // End While
        $iRs->free();
    }     // End If
    else {
        ?>
      <tr class="RowData">
						<td nowrap="nowrap">&nbsp;</td>

						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
      <?php } ?>
    </tbody>
				<tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th width="44" height="18">&nbsp;</th>
						<th width="28">&nbsp;</th>
						<th width="224">&nbsp;</th>
						<th width="68">&nbsp;</th>
						<th width="33">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2));?></th>
						<th align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
  <?php if( number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2) != number_format($sum_total,2) ) { ?>
  <span
				style="color: red; font-size: 11px; font-family: Arial, Helvetica, sans-serif;"><b>NOTA:</b><br />
				<span style="background-color: #FF0;"> El Total de Aporte de las
					Fuentes de financiamiento difiere del Costo total del Personal del
					Proyecto <br /> Corregir los valores marcados de amarillo, para la
					aprobacion del Presupuesto del Proyecto.
			</span> </span>
  <?php } ?>
<script language="javascript" type="text/javascript">
function NuevoPersonal()
{
	var url = "poa_fin_personal_edit.php?mode=<?php echo(md5("ajax_new"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	loadPopup("Retribuciones del Personal", url);
}
function EditarPersonal(idPer)
{
	var url = "poa_fin_personal_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idPer="+idPer;
	loadPopup("Retribuciones del Personal", url);
}
function LoadPersonalFTE(idPer)
{
	var url = "poa_fin_personal_fte.php?mode=<?php echo(md5("ajax_fte"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idPer="+idPer;
	loadPopup("Fuentes de Financiamiento - Personal ", url);
}
function EliminarPersonal(idPer, Cargo)
{
	<?php $ObjSession->AuthorizedPage(); ?>
	if(confirm("¿ Estás seguro de eliminar el Registro seleccionado \n"+Cargo+" ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t20_version=<?php echo($idVersion);?>&t03_id_per="+idPer;
		var sURL = "poa_fin_personal_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, PersonalCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});

	}

	return false ;
}

function PersonalCall(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	alert(respuesta.replace(ret,"")); LoadPersonal(true); }
	else
	{alert(respuesta);}
  }


</script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>