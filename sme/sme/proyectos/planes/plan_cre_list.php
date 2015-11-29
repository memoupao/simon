<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");

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

<title>Plan de Créditos del Proyecto</title>
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
					<td width="14%"><button class="Button"
							onclick="LoadPlanes(); return false;" value="Recargar Listado">
							Refrescar Lista</button></td>
					<td width="30%"><button class="Button" name="btnExportar"
							id="btnExportar" onclick="ExportarPlanCredito(); return false;"
							value="Refrescar">Exportar</button></td>
					<td width="51%" align="right"><span
						style="color: #036; font-weight: bold; font-size: 12px;">Listado
							de Actividades definidas como Crédito</span></td>
					<td width="5%" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div id="divTableLista" class="TableGrid">
			<table width="1080" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="44" height="26" style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="37" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="398" align="center" style="border: solid 1px #CCC;">Actividad</td>
					<td width="90" align="center" style="border: solid 1px #CCC;"><strong>Número
							de Productores que recibirán Crédito </strong></td>
					<td width="110" align="center" style="border: solid 1px #CCC;"><strong>Monto
							del Crédito por Beneficiario </strong></td>
					<td width="99" align="center" style="border: solid 1px #CCC;">Número
						de Cuotas como Maximo a Pagar por Beneficiario</td>
					<td width="44" height="26" style="border: solid 1px #CCC;">&nbsp;</td>
				</tr>
				<tbody class="data">
        <?php

        $objPOA = new BLPOA();
        /*
         * if ($idVersion ==1){ $iRs = $objPOA->PlanCred_Listado($idProy, $idVersion); } else { $iRs = $objPOA->PlanCred_Listado($idProy, $idVersion-1); }
         */

        $iRs = $objPOA->PlanCred_Listado($idProy, $idVersion);

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
						<td nowrap="nowrap" align="center"><span> <a href="javascript:"><img
									src="../../../img/pencil.gif" alt="" width="14" height="14"
									border="0" title="Editar Registro"
									onclick="EditarPlanCred('<?php echo($row["comp"]);?>', '<?php echo($row["act"]);?>', '<?php echo($row["subact"]);?>'); return false;" /></a>
						</span></td>
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left">
		  <?php echo($row["descripcion"]);?>
          <br /> <font style="color: red; font-size: 11px;">Unidad
								medida:</font> <font style="color: #00F; font-size: 11px;"><?php echo($row["um"]);?></font>
						</td>
						<td align="center"><?php echo(number_format($row["t12_nro_ben"],0));?></td>
						<td align="right"><?php echo(number_format($row["t12_mto_ben"],2));?></td>
						<td align="center"><?php echo( number_format($row["t12_nro_cuo"],0));?></td>
						<td nowrap="nowrap" align="center"><span>             <?php if($row["sub2"]!="") { ?>            <a
								href="javascript:"><img src="../../../img/bt_elimina.gif" alt=""
									width="14" height="14" border="0" title="Eliminar Registro"
									onclick="EliminarPlanCred('<?php echo($row["comp"]);?>', '<?php echo($row["act"]);?>', '<?php echo($row["subact"]);?>');" /></a>            <?php } ?>            </span>
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
					</tr>
        <?php } ?>
      </tbody>
				<tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th width="44" height="18">&nbsp;</th>
						<th width="37">&nbsp;</th>
						<th width="398">&nbsp;</th>
						<th width="90">&nbsp;</th>
						<th width="110">&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th width="44" height="18">&nbsp;</th>
					</tr>
				</tfoot>
			</table>

			<script>
function EditarPlanCred(comp, act, subact)
{

	if(subact=='' || comp=='' || act=='') { alert("No ha seleccionado ninguna Actividad"); return false; }

	var idProy    = $("#txtCodProy").val();
	var idVersion = $("#cboversion").val();
	var BodyForm = "idProy="+idProy+"&idVersion="+idVersion+"&idComp="+comp+"&idAct="+act+"&idSub="+subact ;
	var sURL = "plan_cre_edit.php?action=<?php echo(md5("edit_plan_cre"));?>";

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


function EliminarPlanCred(comp, act, subact)
{
	<?php $ObjSession->AuthorizedPage(); ?>

	if(subact=='' || comp=='' || act=='') { alert("No ha seleccionado ninguna Actividad"); return false; }

	if(confirm("Estas seguro de querer borrar el Plan de Capacitacion  para la Actividad [" + comp + '.' + act + '.' + subact + "]"))
	{
		var idProy    = $("#txtCodProy").val();
		var idVersion = $("#cboversion").val();
		var BodyForm = "idProy="+idProy+"&idVersion="+idVersion+"&idComp="+comp+"&idAct="+act+"&idSub="+subact ;
		var sURL = "plan_cre_process.php?action=<?php echo(md5("ajax_del_plan_cred"));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessEliminarPlanCred, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorPlanes });
	}
}

function SuccessEliminarPlanCred	(req)
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

function ExportarPlanCredito()
	{

		var arrayControls = new Array();
				arrayControls[0] = "idProy=" + $("#txtCodProy").val();
			arrayControls[1] = "idVersion=" + $("#cboversion").val();
		var params = arrayControls.join("&");
		var sID = "50" ;
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