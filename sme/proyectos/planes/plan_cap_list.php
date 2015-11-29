<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");
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

<title>Plan Capacitacion del Proyecto</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->

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
					<td width="14%"><button class="Button"
							onclick="LoadPlanes(); return false;" value="Recargar Listado">
							Refrescar Lista</button></td>
					<td width="26%"><button class="Button" name="btnExportar"
							id="btnExportar"
							onclick="ExportarPlanCapacitacion(); return false;"
							value="Refrescar">Exportar</button></td>
					<td width="55%" align="right"><span
						style="color: #036; font-weight: bold; font-size: 12px;">Listado
							de Actividades definidas como Capacitación </span></td>
					<td width="5%" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div id="divTableLista" class="TableGrid">
			<table width="1080" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="45" height="26" style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="28" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="350" align="center" style="border: solid 1px #CCC;">Actividad</td>
					<td width="74" align="center" style="border: solid 1px #CCC;"><strong>Número
							de Talleres de Capacitación por beneficiario</strong></td>
					<td width="94" align="center" style="border: solid 1px #CCC;"><strong>Número
							de horas totales de capacitación por beneficiario</strong></td>
					<td width="80" align="center" style="border: solid 1px #CCC;"><strong>Número
							de Beneficiarios Capacitados Totales&nbsp; </strong></td>
					<td align="center"><strong>Tema de la Capacitación</strong></td>
					<td width="145" align="center" style="border: solid 1px #CCC;"><strong>Contenido
							de la Capacitación </strong></td>
					<td colspan="4" align="center"><strong>Listado de Talleres</strong></td>
					<td width="45" height="26" style="border: solid 1px #CCC;">&nbsp;</td>
				</tr>
				<tbody class="data">
        <?php

        $objMP = new BLPOA();
        /*
         * if ($idVersion ==1){ $iRs = $objMP->PlanCapacitacion_Listado($idProy, $idVersion); } else { $iRs = $objMP->PlanCapacitacion_Listado($idProy, $idVersion-1); }
         */
        $iRs = $objMP->PlanCapacitacion_Listado($idProy, $idVersion);
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
        <tr class="RowData" style="background-color: #FFF;">
						<td nowrap="nowrap"><span> <a href="javascript:"><img
									src="../../../img/pencil.gif" alt="" width="14" height="14"
									border="0" title="Editar Registro"
									onclick="EditarPlanCapac('<?php echo($row["comp"]);?>', '<?php echo($row["act"]);?>', '<?php echo($row["subact"]);?>'); return false;" /></a>
						</span></td>
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left">
		  <?php echo($row["descripcion"]);?>
          <br /> <font style="color: red; font-size: 11px;">Unidad
								medida:</font> <font style="color: #00F; font-size: 11px;">
		  <?php echo($row["um"]);?></font>
						</td>
						<td align="center"><?php echo(number_format($row["t12_nro_tema"],0));?></td>
						<td align="center"><?php echo(number_format($row["t12_hor_cap"],0));?></td>
						<td align="center"><?php echo(number_format($row["t12_nro_ben"],0));?></td>
						<td><?php echo($row["modulo"]); ?></td>
						<td align="left"><?php echo(wordwrap($row["t12_conten"], 30, "\n", true));?>
          </td>

						<td colspan="4" align="left">
							<!--?php echo($row["modulo"]);?--> <!-- modificado dia 26/11/2011 -->
		  <?php

$iRsTemas = $objMP->PlanCapacitacion_ListadoTemas($idProy, $idVersion, $row["comp"], $row["act"], $row["subact"], $idTema);
                if ($iRsTemas->num_rows > 0) {
                    $cont = 0;
                    while ($rowTemas = mysqli_fetch_assoc($iRsTemas)) {
                        $cont ++;
                        echo ("$cont" . '.-' . $rowTemas['t12_tem_espe'] . '<br/>');
                    }
                }
                $iRsTemas->free();
                ?> <!--fin-->
						</td>
						<td nowrap="nowrap"><span> <a href="javascript:"><img
									src="../../../img/bt_elimina.gif" alt="" width="14" height="14"
									border="0" title="Eliminar Registro"
									onclick="EliminarPlanCapac('<?php echo($row["comp"]);?>', '<?php echo($row["act"]);?>', '<?php echo($row["subact"]);?>');" /></a></span>
						</td>
					</tr>
        <?php

} // End While
            $iRs->free();
        }         // End If
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
						<td colspan="4" align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
        <?php } ?>
      </tbody>
				<tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th width="45" height="18">&nbsp;</th>
						<th width="28">&nbsp;</th>
						<th width="162">&nbsp;</th>
						<th width="74">&nbsp;</th>
						<th width="94">&nbsp;</th>
						<th colspan="2" align="right">&nbsp;</th>
						<th width="3" align="right">&nbsp;</th>
						<th width="47" align="right">&nbsp;</th>
						<th width="65" align="right">&nbsp;</th>
						<th width="35" align="right">&nbsp;</th>
						<th width="45" height="18">&nbsp;</th>
						<th width="45" height="18">&nbsp;</th>
					</tr>
				</tfoot>
			</table>

			<script>
function EditarPlanCapac(comp, act, subact)
{

	if(subact=='' || comp=='' || act=='') { alert("No ha seleccionado ninguna Actividad"); return false; }

	var idProy    = $("#txtCodProy").val();
	var idVersion = $("#cboversion").val();
	var BodyForm = "idProy="+idProy+"&idVersion="+idVersion+"&idComp="+comp+"&idAct="+act+"&idSub="+subact ;
	var sURL = "plan_cap_edit.php?action=<?php echo(md5("edit_plan_capac"));?>";

	$('#divContent').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanes, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorPlanes });
}

function SuccessPlan(req)
{
  var respuesta = req.xhRequest.responseText;
  $("#divContent").html(respuesta);
  return;
}
function onErrorPlan(req)
{
	alert("No se ha logrado cargar la pagina \n"+req.xhRequest.responseText);
	return ;
}


function EliminarPlanCapac(comp, act, subact)
{
	<?php $ObjSession->AuthorizedPage(); ?>

	if(subact=='' || comp=='' || act=='') { alert("No ha seleccionado ninguna Actividad"); return false; }

	if(confirm("Estas seguro de querer borrar el Plan de Capacitacion  para la Actividad [" + comp + '.' + act + '.' + subact + "]"))
	{
		var idProy    = $("#txtCodProy").val();
		var idVersion = $("#cboversion").val();
		var BodyForm = "idProy="+idProy+"&idVersion="+idVersion+"&idComp="+comp+"&idAct="+act+"&idSub="+subact ;
		var sURL = "plan_cap_process.php?action=<?php echo(md5("ajax_del_plan_capac"));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessEliminarPlanCapac, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorPlanes });
	}
}

function SuccessEliminarPlanCapac	(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	LoadPlanes();
	alert(respuesta.replace(ret,""));
  }
  else
  {alert(respuesta);}
}

function ExportarPlanCapacitacion()
	{
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + $("#txtCodProy").val();
			arrayControls[1] = "idVersion=" + $("#cboversion").val();
		var params = arrayControls.join("&");
		var sID = "48" ;
		showReport(sID, params);
	}

 function showReport(reportID, params)
	  {
		 var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;

		 $('#FormData').attr({target: "winReport"});
		 //alert($('#FormData').attr({target: "winReport"}));
		 $('#FormData').attr({action: newURL});
		 $('#FormData').submit();
		 $('#FormData').attr({target: "_self"});
		 $("#FormData").removeAttr("action");
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