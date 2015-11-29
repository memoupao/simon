<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$modif = $objFunc->__POST('modif');
$modificar = false;
if (md5("enable") == $modif) {
    $modificar = true;
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Equipamiento del Proyecto</title>
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
							onclick="NuevoEquipamiento(); return false;" value="Nuevo"
							<?php if($modificar) echo "disabled"; ?>>Nuevo</button></td>
					<td width="25%"><button class="Button"
							onclick="LoadEquipamiento(true); return false;"
							value="Recargar Listado">Refrescar Lista</button></td>
					<td width="62%" align="right"><span
						style="color: #036; font-weight: bold; font-size: 12px;">Equipos y
							Bienes Duraderos </span></td>
					<td width="4%" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div id="divTableLista" class="TableGrid">
			<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="45" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="27" rowspan="2" align="center"
						style="border: solid 1px #CCC;">#</td>
					<td width="189" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Nombre de Equipo / Bien</td>
					<td width="68" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="34" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="68" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unitario</td>
					<td width="68" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td colspan="6" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="50" align="center" style="border: solid 1px #CCC;">FE</td>
					<td width="62" align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td width="76" align="center" style="border: solid 1px #CCC;">Otras
						Fuentes</td>
					<td width="65" align="center" style="border: solid 1px #CCC;">Total</td>
					<td align="center" style="border: solid 1px #CCC;">&nbsp;</td>
					<td align="center" style="border: solid 1px #CCC;">&nbsp;</td>
				</tr>

				<tbody class="data">
    <?php
    $objMP = new BLManejoProy();
    $iRs = $objMP->Equipamiento_Listado($idProy, $idVersion);
    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sum_total += $row["total"];
            $sum_fte_fe += $row["fte_fe"];
            $sum_fte_otro += $row["otros"];
            $sum_ejecutor += $row["ejecutor"];
            
            $totFte = $row["fte_fe"] + $row["ejecutor"] + $row["otros"];
            $style = number_format($totFte, 2) != number_format($row["total"], 2) ? 'style="background-color:#FF0;color:#F00; text-decoration:blink;"' : '';
            
            ?>
      <tr class="RowData" style="background-color: #FFF;">
						<td nowrap="nowrap"><span>
		<?php if(!$modificar){ ?>
        <a href="javascript:"><img src="../../../img/pencil.gif" alt=""
									width="14" height="14" border="0" title="Editar Registro"
									onclick="EditarEquipamiento('<?php echo($row["codigo"]);?>'); return false;" /></a>
        <?php } ?>
		</span></td>
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left" style="min-width: 200px;">  <?php echo($row["equipo"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format($row["costo"],2));?></td>
						<td align="right"><font <?php echo($style);?>><?php echo( number_format(($row["total"]),2));?></font></td>
						<td width="50" align="right" nowrap="nowrap"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td width="62" align="right" nowrap="nowrap"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td width="76" align="right" nowrap="nowrap"><?php echo(number_format($row["otros"],2));?></td>
						<td width="65" align="right" nowrap="nowrap" bgcolor="#EEEEEE"><font
							<?php echo($style);?>><?php echo(number_format($row["fte_fe"] + $row["ejecutor"] + $row["otros"],2));?></font></td>
						<td align="center"><span>
		<?php if(!$modificar){ ?>
		<a href="#"><img src="../../../img/financ.gif" alt="" width="16"
									height="16" border="0"
									title="Agregar o Modificar Fuentes de Financiamiento"
									onclick="LoadEquipamientoFTE('<?php echo($row["codigo"]);?>'); return false;" /></a>
		<?php } ?>
		<span></td>
						<td nowrap="nowrap"><span>
		<?php if(!$modificar){ ?>
		<a href="javascript:"><img src="../../../img/bt_elimina.gif" alt=""
									width="14" height="14" border="0" title="Eliminar Registro"
									onclick="EliminarEquipamiento('<?php echo($row["codigo"]);?>','<?php echo($row["equipo"]);?>');" /></a>
		<?php } ?>
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
					</tr>
      <?php } ?>
    </tbody>
     <?php
    /* calculo del 4% de los costos totales del proyecto */
    $hc = new HardCode();
    
    $total_proy = $objMP->GetTotalAporteFuente($idProy, $idVersion, $hc->codigo_Fondoempleo);
    $por_cuatro = (4 * $total_proy) / 100;
    
    if ($sum_total > $por_cuatro) {
        $estilo = "style='background-color:#F00; color:#FFF;'";
        $mensaje = "El Costo Total " . number_format($sum_total, 2) . " supera el 4% del Aporte total del " . $hc->Nombre_Fondoempleo . " " . number_format($por_cuatro, 2);
    } else {
        $estilo = "";
        $mensaje = "El 4% del aporte total de " . $hc->Nombre_Fondoempleo . " es " . number_format($por_cuatro, 2);
    }
    
    ?>
    <tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th height="18" colspan="5" align="left"></th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2));?></th>
						<th align="right">&nbsp;</th>
						<th width="44" height="18">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php /*?>
		<span
			style="color: red; font-size: 11px; font-family: Arial, Helvetica, sans-serif;">
 <?php if( number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2) != number_format($sum_total,2) || $mensaje != "") { ?>
  <b>NOTA:</b><br />
  <?php } ?>
  <?php if( number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2) != number_format($sum_total,2)) { ?>
  <span style="background-color: #FF0;"> El Total de Aporte de las
				Fuentes de financiamiento difiere del Costo total del Equipamiento
				del Proyecto <br /> Corregir los valores marcados de amarillo, para
				la aprobacion del Presupuesto del Proyecto.
		</span> <br /> <br />
  <?php } ?>
  <span style="background-color: #FF0;"> <b><?php echo($mensaje);?></b></span>
		</span>
		<?php */ ?>


		<script language="javascript" type="text/javascript">
function NuevoEquipamiento()
{
	var url = "mp_equipa_edit.php?mode=<?php echo(md5("ajax_new"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	loadPopup("Equipamiento y Bienes Duraderos", url);
}
function EditarEquipamiento(idEqui)
{
	var url = "mp_equipa_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idEqui="+idEqui;
	loadPopup("Equipamiento y Bienes Duraderos", url);
}
function LoadEquipamientoFTE(idEqui)
{
	var url = "mp_equipa_fte.php?mode=<?php echo(md5("ajax_fte"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idEqui="+idEqui;
	loadPopup("Fuentes de Financiamiento - Equipamiento", url);
}
function EliminarEquipamiento(idEqui, Descrip)
{
	<?php $ObjSession->AuthorizedPage(); ?>
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado \n"+Descrip+" ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t20_version="+$('#cboversion').val()+"&t03_id_equi="+idEqui;
		var sURL = "mp_equipa_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, EquipamientoCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});
	}

	return false ;
}

function EquipamientoCall(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	alert(respuesta.replace(ret,"")); LoadEquipamiento(true); }
	else
	{alert(respuesta);}
  }


</script>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>