<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLManejoProy.class.php");

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

<title>GastFunc del Proyecto</title>
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
					<td width="15%"><button class="Button"
							onclick="NuevoGastFunc(); return false;" value="Nuevo"
							<?php if($modificar) echo "disabled"; ?>>Nueva Partida</button></td>
					<td width="14%"><button class="Button"
							onclick="NuevoGastFunc_Costeo(); return false;"
							value="Recargar Listado" <?php if($modificar) echo "disabled"; ?>>
							Nuevo Gasto</button></td>
					<td width="15%"><button class="Button"
							onclick="LoadGastoFuncion(true); return false;"
							value="Recargar Listado">Refrescar Lista</button></td>
					<td width="52%" align="right"><span
						style="color: #036; font-weight: bold; font-size: 12px;">Gastos de
							Funcionamiento </span></td>
					<td width="4%" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div id="divTableLista" class="TableGrid">
			<div style="text-align: justify; font-size: 11px; color: #03C;">
				Gastos necesarios para el normal funcionamiento de los vehículos y
				los equipos, además de los pasajes y viáticos para las acciones de
				supervisión del mismo.</div>
			<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="32" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;</td>
					<td rowspan="2" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="181" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Categoria de Gastos</td>
					<td width="60" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="37" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cant.</td>
					<td width="54" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unit.</td>
					<td width="59" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Parcial</td>
					<td width="39" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="71" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td colspan="5" align="center">Aporte - Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td align="center" style="border: solid 1px #CCC;">FE</td>
					<td align="center" style="border: solid 1px #CCC;">Otras Fuentes</td>
					<td align="center" style="border: solid 1px #CCC;">Total Aportes</td>
					<td width="18" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="18" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
				</tr>

				<tbody class="data">
      <?php
    
    $objMP = new BLManejoProy();
    $iRs = $objMP->GastFunc_Listado($idProy, $idVersion);
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
            
            ?>
        <tr class="RowData" style="background-color: #E3FEE0;">
						<td nowrap="nowrap" style="padding: 1px;"><span>
		<?php if(!$modificar){ ?>
        <a href="javascript:"><img src="../../../img/pencil.gif" alt=""
									width="11" height="12" border="0" title="Editar Registro"
									onclick="EditarGastFunc('<?php echo($row["idpartida"]);?>'); return false;" /></a>
        <?php } ?>
		</span></td>
						<td align="left"><?php echo($row["codigo"]);?></td>
						<td align="left">  <?php echo($row["partida"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($row["parcial"],2));?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format(($row["total"]),2));?></td>
						<td width="72" align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td width="67" align="right"><?php echo(number_format($row["ejecutor"] + $row["otros"],2));?></td>
						<td width="61" align="right" bgcolor="#EEEEEE"><?php echo(number_format($row["otros"] + $row["ejecutor"] + $row["fte_fe"] ,2));?></td>
						<td align="center" style="padding: 1px;">&nbsp;</td>
						<td align="center" style="padding: 1px;"><span>
		<?php if(!$modificar){ ?>
		<a href="javascript:"><img src="../../../img/bt_elimina.gif" alt=""
									width="11" height="12" border="0" title="Eliminar Registro"
									onclick="EliminarGastFunc('<?php echo($row["idpartida"]);?>','<?php echo($row["partida"]);?>');" /></a>
		<?php } ?>
		</span></td>
					</tr>
           <?php
            $iRsCateg = $objMP->GastFunc_ListadoCateg($idProy, $idVersion, $row['idpartida']);
            while ($rowcateg = mysqli_fetch_assoc($iRsCateg)) {
                ?>
   	    <tr class="RowData" style="background-color: #FFF; color: #036;">
						<td nowrap="nowrap" style="padding: 1px;">&nbsp;</td>
						<td align="left"><?php echo($rowcateg["codigo"]);?></td>
						<td align="left" style="font-weight: bold;">  <?php echo($rowcateg["categoria"]);?></td>
						<td align="center"><?php echo($rowcateg["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($rowcateg["parcial"],2));?></td>
						<td align="center"><?php echo($rowcateg["meta"]);?></td>
						<td align="right"><?php echo( number_format(($rowcateg["total"]),2));?></td>
						<td width="72" align="right"><?php echo(number_format($rowcateg["fte_fe"],2));?></td>
						<td width="67" align="right"><?php echo(number_format($rowcateg["otros"] + $rowcateg["ejecutor"],2));?></td>
						<td width="61" align="right" bgcolor="#EEEEEE"><?php echo(number_format($rowcateg["ejecutor"] + $rowcateg["otros"] + $rowcateg["fte_fe"],2));?></td>
						<td align="center" style="padding: 1px;">&nbsp;</td>
						<td align="center" style="padding: 1px;">&nbsp;</td>
					</tr>
            <?php
                
                $iRsGasto = $objMP->GastFunc_ListadoCateg_Gasto($idProy, $idVersion, $row['idpartida'], $rowcateg['idcategoria']);
                while ($rowgasto = mysqli_fetch_assoc($iRsGasto)) {
                    
                    $totFte = $rowgasto["fte_fe"] + $rowgasto["ejecutor"] + $rowgasto["otros"];
                    $style = number_format($totFte, 2) != number_format($rowgasto["total"], 2) ? 'style="background-color:#FF0;color:#F00; text-decoration:blink;"' : '';
                    
                    ?>
                   <tr class="RowData" style="background-color: #FFF;">
						<td nowrap="nowrap" style="padding: 1px;">&nbsp;</td>
						<td align="center" nowrap="nowrap"><span>
					<?php if(!$modificar){ ?>
						<a href="javascript:"><img src="../../../img/pencil.gif" alt=""
									width="11" height="12" border="0" title="Editar Registro"
									onclick="EditarGastFunc_Costeo('<?php echo($rowgasto["idpartida"]);?>','<?php echo($rowgasto["idgasto"]);?>'); return false;" /></a>
                    <?php } ?>
					</span></td>
						<td align="left">  <?php echo($rowgasto["gasto"]);?></td>
						<td align="center"><?php echo($rowgasto["um"]);?></td>
						<td align="center"><?php echo($rowgasto["t03_cant"]);?></td>
						<td align="right"><?php echo( number_format($rowgasto["t03_cu"],2));?></td>
						<td align="right"><?php echo( number_format($rowgasto["parcial"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><font <?php echo($style);?>> <?php echo( number_format(($rowgasto["total"]),2));?> </font>
						</td>
						<td width="72" align="right"><?php echo(number_format($rowgasto["fte_fe"],2));?></td>
						<td width="67" align="right"><?php echo(number_format($rowgasto["otros"] + $rowgasto["ejecutor"],2));?></td>
						<td width="61" align="right" bgcolor="#EEEEEE"><font
							<?php echo($style);?>> <?php echo(number_format($rowgasto["fte_fe"] + $rowgasto["ejecutor"] + $rowgasto["otros"],2));?> </font></td>
						<td align="center" style="padding: 1px;">
					<?php if(!$modificar){ ?>
					<a href="#"><img src="../../../img/financ.gif" alt="" width="16"
								height="16" border="0"
								title="Agregar o Modificar Fuentes de Financiamiento"
								onclick="LoadGastoFuncionFTE('<?php echo($rowgasto["idpartida"]);?>', '<?php echo($rowgasto["idgasto"]);?>'); return false;" /></a>
					<?php } ?>
					</td>
						<td align="center" nowrap="nowrap"><span>
					<?php if(!$modificar){ ?>
					<a href="javascript:"><img src="../../../img/bt_elimina.gif" alt=""
									width="11" height="12" border="0" title="Eliminar Registro"
									onclick="EliminarGastFunc_Costeo('<?php echo($rowgasto["idpartida"]);?>', '<?php echo($rowgasto["idgasto"]);?>','<?php echo($rowgasto["gasto"]);?>');" /></a>
					<?php } ?>
					</span></td>
					</tr>
                   <?php
                }
                //$iRsGasto->free();
            }
            //$iRsCateg->free();
            ?>

        <?php
        
} // End While
        //$iRs->free();
    }     // End If
    else {
        ?>
      <tr class="RowData">
						<td nowrap="nowrap">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
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
						<th width="32" height="18">&nbsp;</th>
						<th width="27">&nbsp;</th>
						<th width="181">&nbsp;</th>
						<th width="60">&nbsp;</th>
						<th width="37">&nbsp;</th>
						<th width="54">&nbsp;</th>
						<th width="59">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor + $sum_fte_otro,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2));?>&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th width="32" height="18">&nbsp;</th>
					</tr>
				</tfoot>
			</table>

<?php if( number_format($sum_fte_fe + $sum_ejecutor + $sum_fte_otro,2) != number_format($sum_total,2) ) { ?>
<span
				style="color: red; font-size: 11px; font-family: Arial, Helvetica, sans-serif;"><b>NOTA:</b><br />
				<span style="background-color: #FF0;"> El Total de Aporte de las
					Fuentes de financiamiento difiere del Costo total de los Gastos de
					Funcionamiento del Proyecto <br /> Corregir los valores marcados de
					amarillo, para la aprobacion del Presupuesto del Proyecto.
			</span> </span>
<?php } ?>

<script language="javascript" type="text/javascript">
function NuevoGastFunc()
{
	var url = "mp_gast_func_edit.php?mode=<?php echo(md5("ajax_new"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	loadPopup("Gastos de Funcionamiento - Partidas", url);
}
function EditarGastFunc(idPartida)
{
	var url = "mp_gast_func_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idPartida="+idPartida;
	loadPopup("Gastos de Funcionamiento - Partidas", url);
}

function NuevoGastFunc_Costeo()
{
	var url = "mp_gast_func_costeo.php?mode=<?php echo(md5("ajax_new_gasto"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>";
	loadPopup("Gastos de Funcionamiento - Costeo", url);
}
function EditarGastFunc_Costeo(idPartida, idGasto)
{
	var url = "mp_gast_func_costeo.php?mode=<?php echo(md5("ajax_edit_gasto"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idPartida="+idPartida+"&idGasto="+idGasto;
	loadPopup("Gastos de Funcionamiento - Costeo", url);
}

function LoadGastoFuncionFTE(idPartida, idGasto)
{
	var url = "mp_gast_func_fte.php?mode=<?php echo(md5("ajax_fte"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idPartida="+idPartida+"&idGasto="+idGasto;
	loadPopup("Fuentes de Financiamiento - Gastos de Funcionamiento", url);
}
function EliminarGastFunc(idPartida, Descrip)
{
	<?php $ObjSession->AuthorizedPage(); ?>
	if(confirm("¿ Estás seguro de eliminar la partida seleccionado, con todos sus gastos ? \n\""+Descrip+"\""))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t20_version="+$('#cboversion').val()+"&t03_partida="+idPartida;
		var sURL = "mp_gast_func_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GastFuncCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});
	}

	return false ;
}
function EliminarGastFunc_Costeo(idPartida, idGasto, Descrip)
{
	<?php $ObjSession->AuthorizedPage(); ?>
	if(confirm("¿ Estás seguro de eliminar el gasto seleccionado ? \n\""+Descrip+"\""))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t20_version="+$('#cboversion').val()+"&t03_partida="+idPartida+"&t03_id_gasto="+idGasto;
		var sURL = "mp_gast_func_process.php?action=<?php echo(md5("ajax_del_gasto"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GastFuncCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});
	}

	return false ;
}

function GastFuncCall(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	alert(respuesta.replace(ret,"")); LoadGastoFuncion(true); }
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