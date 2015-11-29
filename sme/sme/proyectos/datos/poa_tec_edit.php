<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objPOA = new BLPOA();
$objHC = new HardCode();

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');

$action = $objFunc->__Request('mode');

if (md5("ajax_new") == $action) {
    $objFunc->SetSubTitle('Nuevo Plan Operativo');
    $row = $objPOA->POACabUltimo($idProy);
    $idAnio = $row['t02_anio'];
    $NumAniosProy = $objPOA->Proyecto->NumeroAniosProy($idProy);

    if ($idAnio > $NumAniosProy) {
        $objFunc->MsgBox("No se permite crear POAs para años posteriores a la duarción del Proyecto ");
        $objFunc->Javascript("dsLista.loadData(); CancelEdit();");
        exit();
    }
}

if (md5("ajax_edit") == $action) {
    $objFunc->SetSubTitle('Modificación del Plan Operativo');
    $row = $objPOA->POA_Seleccionar($idProy, $idAnio);
    /* Obtener Version del Proyecto, para el POA en Cuestion */
    $idVersion = $row['version'];
}

if ($action == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>POA</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
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
						<td width="7%"><button class="Button"
								onclick="GuardarPOA(); return false;" value="Guardar">Guardar</button></td>
						<td width="27%"><button class="Button"
								onclick="btnCancelar_Clic(); return false;" value="Cancelar"
								style="white-space: nowrap;">Cerrar y Volver</button></td>
						<td width="17%" align="right">
          <?php
        if (md5("ajax_new") == $action) {
            $disabledGenerar = "disabled";
        } else {
            if ($row['generado'] >= 1) {
                $disabledGenerar = "disabled";
            } else {
                $disabledGenerar = "";
            }
        }
        ?>
          <button class="Button" id="btnGenerarVS"
								onclick="GenerarVersionPOA(); return false;" value="Generar"
								<?php echo($disabledGenerar);?>
								title="Generar Version: Repuera los Datos del Cronograma Inicial para ser corregido en el POA Actual"
								style="white-space: nowrap;">Generar Version</button>
						</td>
						<td width="1%">&nbsp;</td>
						<td width="48%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					</tr>
				</table>
			</div>



			<div align="left">
				<fieldset>
					<legend>Especificacion del Periodo de Ejecución</legend>
					<table width="99%" border="0" cellspacing="0" cellpadding="0"
						class="TableEditReg">
						<tr>
							<td width="3%" height="25">Año</td>
							<td width="12%" nowrap="nowrap"><input name="t02_cod_proy"
								type="hidden" class="Cabecera" id="t02_cod_proy"
								value="<?php echo($idProy);?>" /> <input name="t02_anio"
								type="hidden" class="Cabecera" id="t02_anio"
								value="<?php echo($idAnio);?>" /> <select name="cboanio"
								id="cboanio" style="width: 100px;" class="Cabecera">
            <?php
            $objProy = new BLProyecto();
            $ver_proy = $objProy->MaxVersion($idProy);
            $rs = $objProy->ListaAniosProyecto($idProy, $ver_proy);
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $idAnio);
            $objProy = NULL;
            ?>
            </select></td>
							<td width="12%" align="right" nowrap="nowrap">Periodo Ref.</td>
							<td width="29%"><input name="t02_periodo" type="text"
								class="Cabecera" id="t02_periodo"
								value="<?php echo($row['t02_periodo'])?>" size="40"
								maxlength="50" /></td>
							<td align="right" nowrap="nowrap">Estado</td>
							<td><select name="t02_estado" class="Cabecera" id="t02_estado"
								style="width: 130px;">
            <?php
            $objTablas = new BLTablasAux();
            $rs = $objTablas->EstadoInformes();
            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t02_estado']);
            $objTablas = NULL;
            ?>
            </select></td>
						</tr>
						<tr>
							<td height="27" nowrap="nowrap">&nbsp;</td>
							<td nowrap="nowrap">&nbsp;</td>
							<td nowrap="nowrap">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</fieldset>

			</div>
			<br />
			<div id="ssTabPOA" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Puntos Atención</li>
					<li class="TabbedPanelsTab" tabindex="1">Coyuntura Actual</li>
					<li class="TabbedPanelsTab" tabindex="2"
						onclick="LoadComponentes(false);">Componentes</li>
					<li class="TabbedPanelsTab" tabindex="3"
						onclick="LoadActividades(false);">Productos</li>
					<li class="TabbedPanelsTab" tabindex="4"
						onclick="LoadSubActividades(false);">Actividades</li>
					<li class="TabbedPanelsTab" tabindex="5"
						onclick="LoadDocAdicional(false);">Doc. Adicional</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<div id="divPersonal">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td colspan="2"><strong>1.1. Puntos de atención</strong></td>
								</tr>
								<tr>
									<td colspan="2"
										style="color: #009; font-size: 11px; text-align: justify;">
										Referirse a los aspectos más importantes que se debe tomar en
										cuenta, antes de iniciar la ejecución del periodo programado.
										Hacer alusión a posibles reprogramaciones debido al cambio de
										la situación prevista en el momento de diseño del
										proyecto. Considerar los factores externos que hayan variado
										respecto a la etapa de diseño. De haber actividades que
										deban ejecutarse antes de la fecha prevista, indicar por qué
										se requiere adelantar la ejecución.</td>
								</tr>
								<tr>
									<td colspan="2"><textarea name="txtptoatencion" cols="80"
											rows="15" class="Cabecera" id="txtptoatencion"
											style="padding: 0px; width: 99%;"><?php echo(stripcslashes( $row['t02_punto_aten']));?> </textarea>
									</td>
								</tr>
								<tr>
									<td width="29%">&nbsp;</td>
									<td width="71%">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divActividades">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td><strong> 2.1. Política Nacional y/o sectorial</strong></td>
								</tr>
								<tr>
									<td style="color: #009; font-size: 11px;">Mencionar los cambios
										o nuevos dispositivos legales que estén vinculados al
										desarrollo del proyecto y que se hayan registrado entre la
										culminación la etapa anterior y el inicio de la fase objetivo
										del POA. Estos cambios stán referidos al ámbito nacional o
										al del gobierno local.</td>
								</tr>
								<tr>
									<td><textarea name="txtpolitica" cols="80" rows="8"
											class="Cabecera" id="txtpolitica"
											style="padding: 0px; width: 99%;"><?php echo(stripcslashes($row['t02_politica']));?> </textarea></td>
								</tr>
								<tr>
									<td><strong> 2.2. Beneficiarios y principales partes implicadas</strong></td>
								</tr>
								<tr>
									<td style="color: #009; font-size: 11px;">Referirse a la
										población objetivo, a las organizaciones que las involucran y
										a los directivos que participarían en cualquiera de las
										actividades programadas, y, de existir barreras, como se
										plantea en esta etapa la uperación de las mismas.</td>
								</tr>
								<tr>
									<td><textarea name="txtbeneficiarios" cols="80" rows="8"
											class="Cabecera" id="txtbeneficiarios"
											style="padding: 0px; width: 99%;"><?php echo(stripcslashes($row['t02_benefic']));?> </textarea></td>
								</tr>
								<tr>
									<td><strong> 2.3. Otras Intervenciones</strong></td>
								</tr>
								<tr>
									<td style="color: #009; font-size: 11px;">Comentar si en forma
										paralela al desarrollo del proyecto, se registran en la zona
										de trabajo otras intervenciones convergentes con los objetivos
										del proyecto.</td>
								</tr>
								<tr>
									<td><textarea name="txtotrasinterv" cols="80" rows="8"
											class="Cabecera" id="txtotrasinterv"
											style="padding: 0px; width: 99%;"><?php echo(stripcslashes($row['t02_otras_interv']));?> </textarea></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divComponentes"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divActividades"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divSubActividades"></div>
					</div>
					<div class="TabbedPanelsContent">
						<div id="divDocAdicional"></div>
					</div>
				</div>
			</div>
		</div>

		<script language="javascript" type="text/javascript">
  function GuardarPOA		()
	{
	<?php $ObjSession->AuthorizedPage(); ?>


	var anio = $('#cboanio').val();
	var per = $('#t02_periodo').val();
	var est = $('#t02_estado').val();

	if(anio=="" || anio==null){alert("Seleccione A\u00f1o del POA"); return false;}
	if(per=="" || per==null){alert("No se ha especificado el periodo del POA"); return false;}
	if(est=="" || est==null){alert("Seleccione Estado del del presente POA"); return false;}

	var BodyForm= $("#FormData .Cabecera").serialize() ;
	var sURL = "poa_tec_process.php?action=<?php echo($action);?>" ;
	var req = Spry.Utils.loadURL("POST", sURL, true, POASuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	}
	function POASuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var anio = respuesta.substring(0,7);
		alert(respuesta.replace(anio,""));
		anio = anio.replace(ret,"");
		btnEditar_Clic(anio);
	  }
	  else
	  {alert(respuesta);}
	}

	function GenerarVersionPOA()
	{
		var anio = "<?php echo($idAnio); ?>";
		var proy = "<?php echo($idProy); ?>";

		if(anio=="" || anio==null){alert("No se ha Especificado el A\u00f1o del POA"); return false;}
		if(proy=="" || proy==null){alert("NO ha selecconado ningun Proyecto"); return false;}

		if(confirm("<?php echo( "¿ Esta seguro de generar la Nueva Versión del Proyecto para el POA del Año $idAnio ? \\nSe van a jalar los datos del Marco Lógico, Actividades y Presupuesto, del Cronograma Inicial y Presupuesto Inicial ..." ); ?> "))
		{
			var IsRestruc = -1 ;

			if(confirm("<?php echo( "¿ El POA del año $idAnio, va a ser una restructuración ?" ); ?>"))
			{
				IsRestruc = 1 ;
			}
			else
			{
				if(confirm("<?php echo( "Se van a Copiar las metas de las actividades del Cronograma Inicial \\n ¿Esta de Acuerdo, Confirme para Continuar ?" ); ?> "))
				{
					IsRestruc = 0 ;
				}

			}

			if(IsRestruc==-1){alert("No se ha decidido el Origen de la Generación de las Actividades"); return ;}

			$('#btnGenerarVS').attr('disabled', 'true');
			var BodyForm= "idProy="+proy+"&idAnio="+anio+"&restruc="+IsRestruc ;
			var sURL = "poa_tec_process.php?action=<?php echo(md5("ajax_generate_version_poa"));?>" ;
			var req = Spry.Utils.loadURL("POST", sURL, true, GenerarVersionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
		}
	}
	function GenerarVersionSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		alert($('<div/>').html(respuesta.replace(ret,"")).text());
		btnEditar_Clic('<?php echo($idAnio); ?>');
	  }
	  else
	  {
		  alert($('<div/>').html(respuesta).text());
		  $('#btnGenerarVS').removeAttr('disabled');
	  }

	}
  </script>
		<script language="javascript" type="text/javascript">
	function LoadComponentes			(recargar)
	{
		if($('#divComponentes').html()!="")
		{ if(!recargar){return false;} 	}

		var BodyForm = "action=<?php echo(md5("lista_componentes"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idAnio=" + $('#cboanio').val();
	 	var sURL = "poa_tec_comp.php";
		$('#divComponentes').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessComponentes, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessComponentes	 	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divComponentes").html(respuesta);
 	   return;
	}
	function onErrorLoad			(req)
	{
		alert("Ocurrio un error al cargar los datos");
	}
	function LoadActividades		(recargar)
	{
		if($('#divActividades').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("lista_actividades"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idAnio=" + $('#cboanio').val();
	 	var sURL = "poa_tec_ind_act.php";
		$('#divActividades').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessActividades, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessActividades	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divActividades").html(respuesta);
 	   return;
	}
	function LoadSubActividades		(recargar)
	{
		if($('#divSubActividades').html()!="")
		{
			if(!recargar){return false;}
		}

		var idcomp=$('#cboComponente_sub').val();
		if(idcomp=="" || idcomp==null){idcomp=1;}

		var BodyForm = "action=<?php echo(md5("lista_subactividades"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idAnio=<?php echo($idAnio)?>&idComp="+idcomp;
	 	var sURL = "poa_tec_sub_act.php";
		$('#divSubActividades').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessSubActividades, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessSubActividades	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divSubActividades").html(respuesta);
 	   return;
	}

	function LoadDocAdicional		(recargar)
	{
		if($('#divDocAdicional').html()!="")
		{
			if(!recargar){return false;}
		}
		var BodyForm = "action=<?php echo(md5("docum_adicional"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idAnio=<?php echo($idAnio)?>";
	 	var sURL = "poa_tec_inf_adic.php";
		$('#divDocAdicional').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessDocAdicional, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessDocAdicional	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divDocAdicional").html(respuesta);
 	   return;
	}

	$("#cboanio option").attr('disabled','disabled');
	$("#cboanio option:selected").removeAttr('disabled');

	$("#t02_periodo").attr('readonly','readonly');

	$("#t02_estado option").attr('disabled','disabled');
	$("#t02_estado option:selected").removeAttr('disabled');

	var estado = $("#t02_estado").val();
	var Elaboracion= "<?php echo($objHC->EstInf_Ela);?>";
	var AprobadoMT = "<?php echo($objHC->EstInf_Aprob);?>";
	var Correccion = "<?php echo($objHC->EstInf_Corr);?>";
	var Revision   = "<?php echo($objHC->EstInf_Rev);?>";

	<?php if($ObjSession->PerfilID == $objHC->MT || $ObjSession->PerfilID == $objHC->CMT )  { ?>
		if(estado==Revision)
		  {
			  $('#t02_estado option[value="'+Correccion+'"]').removeAttr('disabled');
			  $('#t02_estado option[value="'+AprobadoMT+'"]').removeAttr('disabled');
		  }
	<?php } ?>

	<?php if($ObjSession->PerfilID == $objHC->Ejec) { ?>
		if(estado==Elaboracion || estado==Correccion)
		  {
			  $('#t02_estado option[value="'+Revision+'"]').removeAttr('disabled');
		  }
	<?php } ?>
</script>


  <?php if($action=="") { ?>
</form>
	<script type="text/javascript">
<!--
var TabsPOA = new Spry.Widget.TabbedPanels("ssTabPOA");
//-->
</script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>