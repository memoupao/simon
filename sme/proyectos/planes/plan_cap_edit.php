<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objPOA = new BLPOA();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idComp = $objFunc->__Request('idComp');
$idAct = $objFunc->__Request('idAct');
$idSub = $objFunc->__Request('idSub');

$rowcab = $objPOA->PlanCapacitacion_Seleccionar($idProy, $idVersion, $idComp, $idAct, $idSub);
$idSub2 = $rowcab['subplan'];

$action = $objFunc->__Request('action');

if ($action == "") {
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
	<form id="frmMain" name="frmMain" method="post" action="#">
  <?php
}
?>

  <div>
			<div id="toolbar" style="height: 4px;" class="BackColor">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="9%"><button class="Button"
								onclick="Guardar_Plan(); return false;" value="Guardar">Guardar
							</button></td>
						<td width="25%"><button class="Button"
								onclick="LoadPlanes(); return false;" value="Cancelar">Cerrar y
								Volver</button></td>
						<td width="9%">&nbsp;</td>
						<td width="2%">&nbsp;</td>
						<td width="2%">&nbsp;</td>
						<td width="2%">&nbsp;</td>
						<td width="47%" align="right">Plan de Capacitacion</td>
					</tr>
				</table>
			</div>
			<div>
				<table width="788" border="0" cellpadding="0" cellspacing="1"
					class="TableEditReg">
					<tr>
						<td width="11%" height="35" nowrap="nowrap"><strong>Actividad</strong></td>
						<td width="89%">
							<div style="border: 1px solid #7F9FAA; width: 90%;">
        <?php echo( "<b>".$rowcab["codigo"]."</b>.- ".$rowcab["subact"]); ?>
        </div>
						</td>
					</tr>
					<tr>
						<td height="35"><strong>Tema</strong></td>
						<td><select name="cbomodulo" id="cbomodulo" style="width: 280px;">
								<option value="" selected="selected"></option>
             <?php
            $objTab = new BLTablasAux();
            $rs = $objTab->TipoModulosPlanesEspecificos();
            $objFunc->llenarCombo($rs, "codigo", "descripcion", $rowcab["t12_modulo"]);
            $objTab = NULL;
            ?>
             </select></td>
					</tr>
					<tr>
						<td><strong>Contenidos </strong></td>
						<td><textarea name="txtcontenidos" cols="95" rows="3"
								id="txtcontenidos"><?php echo($rowcab['t12_conten']);?></textarea>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="hidden" name="t02_cod_proy"
							value="<?php echo($idProy);?>" /> <input type="hidden"
							name="t02_version" value="<?php echo($idVersion);?>" /> <input
							type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
							<input type="hidden" name="t09_cod_act"
							value="<?php echo($idAct);?>" /> <input type="hidden"
							name="t09_cod_sub" value="<?php echo($idSub);?>" /></td>
					</tr>
				</table>
			</div style="width:700px;">
			<fieldset>
				<legend> Talleres</legend>
				<div class="TableGrid" id="divTemas"></div>
			</fieldset>

		</div>
		<script language="javascript" type="text/javascript">
    function Guardar_Plan()
	{
		 <?php $ObjSession->AuthorizedPage(); ?>			 var horas_total = $('#txtnrohoras_total').val();		 var benef_total = $('#txtnrobenef_total').val();
		 var modulo = $('#cbomodulo').val();
		 var conten = $('#txtcontenidos').val();

		 if(modulo=='' || modulo==null){alert("Seleccione Modulo al que corresponde la Actividad de Capacitacion"); $('#cbomodulo').focus(); return false;}
		 if(conten=='' || conten==null){alert("Ingrese el contenido de la capacitacion"); $('#txtcontenidos').focus(); return false;}

		var arrParams = new Array();
			arrParams[0] = "idProy=<?php echo($idProy); ?>" ;
			arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
			arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
			arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
			arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
			arrParams[5] = "cbomodulo=" + modulo ;
			arrParams[6] = "txtcontenidos=" + conten ;
			arrParams[7] = "txtnrohoras_total=" + horas_total ;
			arrParams[8] = "txtnrobenef_total=" + benef_total ;

		  var BodyForm = arrParams.join("&");
		  var sURL = "plan_cap_process.php?action=<?php echo(md5("ajax_save_plan_cab"));?>";

		  var req = Spry.Utils.loadURL("POST", sURL, true, GuardarPlanCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
   function GuardarPlanCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		EditarPlanCapac('<?php echo($idComp); ?>','<?php echo($idAct); ?>','<?php echo($idSub); ?>');
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}

	function LoadTemas			()
	{
		var BodyForm = "action=<?php echo(md5("lista_temas"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp=<?php echo($idComp);?>&idAct=<?php echo($idAct);?>&idSub=<?php echo($idSub2);?>";
	 	var sURL = "plan_cap_list_temas.php";
		$('#divTemas').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessTemas, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessTemas	 	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divTemas").html(respuesta);
 	   return;
	}
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos \n" +  req.xhRequest.responseText);
	}

	LoadTemas() ;

  </script>


  <?php if($action=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>