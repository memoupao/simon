<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLManejoProy.class.php");
require (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require (constant("PATH_CLASS") . "BLPresupuesto.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idComp = $objFunc->__POST('idComp');
$idActiv = $objFunc->__POST('idAct');
$modif = $objFunc->__POST('modif');
$modificar = false;
if (md5("enable") == $modif) {
    $modificar = true;
}

$objHC = new HardCode();

$ObjSession->VerifyProyecto();
$ObjSession->VerProyecto = 1;

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Costos Operativos del Proyecto</title>
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

<?php
if ($idProy != "") {
    if ($ObjSession->MaxVersionProy($idProy) > $idVersion) {
        $disabled = true;
    }
}
?>

<div style="height: 30px;" id="toolbar">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="6%" height="28" nowrap="nowrap">Componente&nbsp;</td>
					<td colspan="2" align="left"><span style="display: inline-block;">
							<select name="cboComponente_ope" id="cboComponente_ope"
							style="width: 520px;" class="SubActividad"
							onchange="$('#cboActividad_ope').val('1'); LoadCostosOperativos();">
              <?php
            $objML = new BLMarcoLogico();
            $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
            $objFunc->llenarComboSinItemsBlancos($rsComp, "t08_cod_comp", 'descripcion', $idComp,'',array(),'t08_comp_desc');
            $rsComp = NULL;
            ?>
            </select>
					</span></td>
					
					<td width="48%" rowspan="2" align="center">
						<!-- <button id="btnRefrescar" class="Button" value="Refrescar" onclick="return Refrescar();" name="btnRefrescar"> Refrescar&nbsp;</button></td>-->
							<input type="button" value="Refrescar" class="btn_save_custom"
							style="padding: 2px 4px;" onclick = "LoadCostosOperativos();" />
					</td>	
					<td width="48%" rowspan="2" align="center">
						<button class="boton" name="btnExport" id="btnExport" onclick="ExportarCronograma(); return false;"
						value="Exportar">Exportar Presupuesto</button>
					</td>					
				</tr>
				<tr>
					<td width="6%" nowrap="nowrap">Producto</td>
					<td width="38%" align="left" nowrap="nowrap"><select
						name="cboActividad_ope" id="cboActividad_ope"
						style="width: 520px;" onchange="LoadCostosOperativos();">
							<!--<option value="*" style="text-align:center"> { Todos } </option>-->
              <?php
            $rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
            $objFunc->llenarCombo($rsAct, "t09_cod_act", 'descripcion', $idActiv);
            $rsAct = NULL;
            $objML = NULL;
            ?>
            </select> 
					<td width="8%" align="left">&nbsp;</td>
					<td width="8%" align="left">&nbsp;</td>
					<td width="8%" align="left">&nbsp;</td>
				</tr>
			</table>
			<br />
		</div>

		<div id="divTableLista" class="TableGrid">
			<div style="text-align: justify; font-size: 11px; color: #F00;"></div>
			<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td rowspan="2" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="180" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Categoria de Gastos</td>
					<td width="30" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="37" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cant.</td>
					<td width="46" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unit.</td>
					<td width="62" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Parcial</td>
					<td width="42" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="64" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td height="26" colspan="6" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td height="26" align="center" style="border: solid 1px #CCC;">FE</td>
					<td align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td align="center" style="border: solid 1px #CCC;">Otras Fuentes</td>
					<td align="center" style="border: solid 1px #CCC;">Total</td>
					<td align="center" style="border: solid 1px #CCC;" colspan="2">&nbsp;</td>
				</tr>

				<tbody class="data">
      <?php

    // $objPresup = new BLManejoProy();
    $objPresup = new BLPresupuesto();
    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;

    $aRs = $objPresup->ListaActividades($idProy, $idVersion, $idComp);

    if ($aRs->num_rows > 0) {
        while ($ract = mysqli_fetch_assoc($aRs)) {
		
			$actividad = trim($ract["actividad"]);
			if (empty($actividad)) {
				continue;
			}
			
            $idAct = $ract['t09_cod_act'];

            if ($idActiv != '*') {
                if ($idActiv != $idAct) {
                    continue;
                }
            }

            $sum_total += $ract["ctototal"];
            $sum_fte_fe += $ract["fte_fe"];
            $sum_fte_otro += $ract["otros"];
            $sum_ejecutor += $ract["ejecutor"];
            $sumtotalfte += ($ract["fte_fe"] + $ract["otros"] + $ract["ejecutor"]);

            ?>
      <tr class="RowData" style="background-color: #FC9;">
						<td align="left"><?php echo($ract["codigo"]);?></td>
						<td colspan="4" align="left">  <?php echo($actividad);?></td>
						<td align="right"><?php echo( number_format($ract["ctoparcial"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format(($ract["ctototal"]),2));?></td>
						<td width="0" align="right" nowrap="nowrap"><?php echo(number_format($ract["fte_fe"],2));?></td>
						<td width="0" align="right"><?php echo(number_format($ract["ejecutor"],2));?></td>
						<td width="0" align="right"><?php echo(number_format($ract["otros"],2));?></td>
						<td width="0" align="right" bgcolor="#eeeeee"><?php echo(number_format($ract["fte_fe"] + $ract["ejecutor"] + $ract["otros"],2));?></td>
						<td align="center" style="padding: 1px;" colspan="2">&nbsp;</td>
					</tr>
           <?php

            $iRs = $objPresup->ListaSubActividades($idProy, $idVersion, $idComp, $idAct);
            while ($row = mysqli_fetch_assoc($iRs)) {
				$nomSubActividad = trim($row["subactividad"]);
				if (empty($nomSubActividad)) {
					continue;
				}
                ?>
            <tr class="RowData" style="background-color: #E3FEE0;">
						<td align="left"><?php echo($row["codigo"]);?></td>
						<td align="left">  <?php echo($nomSubActividad);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($row["ctoparcial"],2));?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format(($row["ctototal"]),2));?></td>
						<td width="0" align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td width="0" align="right"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td width="0" align="right"><?php echo(number_format($row["otros"],2));?></td>
						<td width="0" align="right" bgcolor="#eeeeee"><?php echo(number_format($row["fte_fe"] + $row["ejecutor"] + $row["otros"],2));?></td>
						<td align="center" style="padding: 1px;" colspan="2">
			<?php if(!$modificar){ ?>
				<a href="javascript:"><img src="../../../img/nuevo.gif" alt=""
								width="16" height="16" border="0" title="Ingresar Nuevo Gasto "
								onclick="NuevoCostosOperativos('<?php echo($idAct);?>', '<?php echo($row["t09_cod_sub"]);?>'); return false;"
								class="CosteoSubAct" /></a>
			<?php } ?>
			</td>
					</tr>
           <?php
                $iRsCateg = $objPresup->ListaSubActividadesCateg($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_sub"]);
                while ($rowcateg = mysqli_fetch_assoc($iRsCateg)) {
                    ?>
           		 <tr class="RowData"
						style="background-color: #FFF; color: #036;">
						<td align="left"><?php echo($rowcateg["codigo"]);?></td>
						<td align="left" style="font-weight: bold; min-width: 100px;">  <?php echo($rowcateg["categoria"]);?></td>
						<td align="center"><?php echo($rowcateg["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($rowcateg["ctoparcial"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format(($rowcateg["ctototal"]),2));?></td>
						<td width="0" align="right"><?php echo(number_format($rowcateg["fte_fe"],2));?></td>
						<td width="0" align="right"><?php echo(number_format($rowcateg["ejecutor"],2));?></td>
						<td width="0" align="right"><?php echo(number_format($rowcateg["otros"],2));?></td>
						<td width="0" align="right" bgcolor="#eeeeee"><?php echo(number_format($rowcateg["fte_fe"] + $rowcateg["ejecutor"] + $rowcateg["otros"],2));?></td>
						<td align="center" colspan="2" style="padding: 1px;">&nbsp;</td>
					</tr>
            <?php

                    $iRsGasto = $objPresup->ListaSubActividadesCosteo($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_sub"], $rowcateg['t10_cate_cost']);
                    while ($rowgasto = mysqli_fetch_assoc($iRsGasto)) {
                        ?>
                   <tr class="RowData" style="background-color:#FFF; <?php if(number_format(($rowgasto["fte_fe"] + $rowgasto["ejecutor"] + $rowgasto["otros"]),2)!=number_format($rowgasto["t10_cost_tot"],2)){echo("color:#F00; text-decoration:blink;");}?> ">
						<td align="center" nowrap="nowrap"><span>
                        <?php if(!$modificar){ ?>
						<a href="javascript:"><img src="../../../img/pencil.gif" alt=""
									width="11" height="12" border="0" title="Editar Registro"
									onclick="EditarCostosOperativos('<?php echo($idAct);?>','<?php echo($row['t09_cod_sub']);?>', '<?php echo($rowgasto['t10_cod_cost']);?>'); return false;"
									class="CosteoSubAct" /></a>
                       <?php } ?>
                        </span></td>
						<td align="left">  <?php echo($rowgasto["t10_cost"]);?></td>
						<td align="center"><?php echo($rowgasto["t10_um"]);?></td>
						<td align="center"><?php echo(round($rowgasto["t10_cant"],4));?></td>
						<td align="right"><?php echo( number_format($rowgasto["t10_cu"],3));?></td>
						<td align="right"><?php echo( number_format($rowgasto["t10_cost_parc"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><font
							<?php if(number_format(($rowgasto["fte_fe"] + $rowgasto["ejecutor"] + $rowgasto["otros"]),2)!=number_format($rowgasto["t10_cost_tot"],2)){echo(' style="background-color:#FF0;"');}?>><?php echo( number_format(($rowgasto["t10_cost_tot"]),2));?></font></td>
						<td width="65" align="right" nowrap="nowrap"><?php echo(number_format($rowgasto["fte_fe"],2));?></td>
						<td width="65" align="right" nowrap="nowrap"><?php echo(number_format($rowgasto["ejecutor"],2));?></td>
						<td width="65" align="right" nowrap="nowrap"><?php echo(number_format($rowgasto["otros"],2));?></td>
						<td width="65" align="right" nowrap="nowrap" bgcolor="#eeeeee"><?php echo(number_format($rowgasto["fte_fe"] + $rowgasto["ejecutor"] + $rowgasto["otros"],2));?></td>
						<td align="center" style="padding: 1px;">
						<?php if(!$modificar){ ?>
							<a href="javascript:"><img src="../../../img/financ.gif" alt=""
								width="16" height="16" border="0"
								title="Agregar o Modificar Fuentes de Financiamiento"
								onclick="LoadGastoFuncionFTE('<?php echo($idAct);?>','<?php echo($row['t09_cod_sub']);?>', '<?php echo($rowgasto['t10_cod_cost']);?>'); return false;"
								class="CosteoSubAct" /></a>
						<?php } ?>
						</td>
						<td align="center" nowrap="nowrap"><span>
						<?php if(!$modificar){ ?>
							<a href="javascript:"><img src="../../../img/bt_elimina.gif"
									alt="" width="11" height="12" border="0"
									title="Eliminar Registro"
									onclick="EliminarCostosOperativos('<?php echo($idAct);?>','<?php echo($row['t09_cod_sub']);?>', '<?php echo($rowgasto['t10_cod_cost']);?>','<?php echo htmlentities($rowgasto["t10_cost"]);?>');"
									class="CosteoSubAct" /></a>
						<?php } ?>
						</span></td>
					</tr>
                   <?php
                    }
                    $iRsGasto->free();
                }
                $iRsCateg->free();
                ?>

        <?php

} // End While
            $iRs->free();
        }
        $aRs->free();
    }     // End If
    else {
        ?>
      <tr class="RowData">
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
						<td align="right">&nbsp;</td>
					</tr>
      <?php } ?>
    </tbody>
				<tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th width="35" height="18">&nbsp;</th>
						<th width="190">&nbsp;</th>
						<th width="62">&nbsp;</th>
						<th width="37">&nbsp;</th>
						<th width="46">&nbsp;</th>
						<th width="62">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?>&nbsp;</th>
						<th align="right" bgcolor="#eeeeee" style="color: #000;"><?php echo(number_format($sumtotalfte,2));?>    </th>
						<th align="right">&nbsp;</th>
						<th width="35" height="18">&nbsp;</th>
					</tr>
				</tfoot>
			</table>

   <?php if( number_format($sumtotalfte,2) != number_format($sum_total,2) ) { ?>
  <span
				style="color: red; font-size: 11px; font-family: Arial, Helvetica, sans-serif;"><b>NOTA:</b><br />
				<span style="background-color: #FF0;">
  El Total de Aporte de las Fuentes de financiamiento difiere del Costo total de los gastos del Componente  <?php echo($idComp);?><br />
					Corregir los valores marcados de amarillo, para la aprobacion del
					Presupuesto del Proyecto.
			</span> </span>
  <?php } ?>

<script language="javascript" type="text/javascript">
<?php if($disabled && $ObjSession->PerfilID!=$objHC->Admin) { ?>
$(".CosteoSubAct").attr("onclick","alert('La version del Proyecto no permite cambios en el Presupuesto'); return false;");
<?php } ?>


function Refrescar()
{
	if(confirm("Esta Seguro que desea refrescar los datos ? "))
	{ LoadCostosOperativos(); }
	return false;
}

function NuevoCostosOperativos(activ, subact)
{
	<?php $ObjSession->AuthorizedPage('NUEVO'); ?>
	var url = "cost_ope_edit.php?mode=<?php echo(md5("new"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp=<?php echo($idComp);?>&idActiv=" + activ + "&idSActiv=" + subact ;
	loadPopup("Costos Operativos - Nuevo Costeo", url);
}
function EditarCostosOperativos(activ, subact, idgasto)
{
	<?php $ObjSession->AuthorizedPage('EDITAR'); ?>
	var url = "cost_ope_edit.php?mode=<?php echo(md5("edit"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp=<?php echo($idComp);?>&idActiv=" + activ + "&idSActiv=" + subact + "&idcosto=" + idgasto ;
	loadPopup("Gastos de Funcionamiento - Partidas", url);
}

function LoadGastoFuncionFTE(activ, subact, idgasto)
{
	var url = "cost_ope_fte.php?mode=<?php echo(md5("fte"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp=<?php echo($idComp);?>&idActiv=" + activ + "&idSActiv=" + subact + "&idcosto=" + idgasto ;
	loadPopup("Fuentes de Financiamiento - Costos Operativos", url);
}
function EliminarCostosOperativos(activ, subact, idgasto, Descrip)
{
	<?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>

	if(confirm("¿ Estás seguro de eliminar el costeo de: \n"+Descrip+"?"))
	{
		var arrParams = new Array();
		arrParams[0] = "t02_cod_proy=<?php echo($idProy);?>";
		arrParams[1] = "t02_version=<?php echo($idVersion);?>";
		arrParams[2] = "t08_cod_comp=<?php echo($idComp);?>";
		arrParams[3] = "t09_cod_act=" + activ ;
		arrParams[4] = "t09_cod_sub=" + subact ;
		arrParams[5] = "idcosto="     + idgasto  ;

		var BodyForm = arrParams.join("&");
		var sURL = "cost_ope_process.php?mode=<?php echo(md5("delete"));?>&action=<?php echo(md5("guardar_costo_operativo"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, CostosOperativosCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorCosteo});
	}

	return false ;
}

function CostosOperativosCall(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
{ 	alert(respuesta.replace(ret,"")); LoadCostosOperativos(); }
else
{alert(respuesta);}
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