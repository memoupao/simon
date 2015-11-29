<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idInforme = $objFunc->__Request('idnum'); // $idInforme = cambie esto $objFunc->__Request('idnum');

$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t25_ver_inf');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');
$idMode = $objFunc->__POST('mode');

if ($idProy == "" && $idAnio == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t25_ver_inf');
    $idAnio = $objFunc->__GET('idAnio');
    $idTrim = $objFunc->__GET('idTrim');
    $idAnio = $objFunc->__POST('idAnio');
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

<title>SubActividades</title>
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
<?php

$objInf = new BLInformes();

?>
 
<div>
			<div id="ssTabPlanes" class="TabbedPanels">
				<ul class="TabbedPanelsTabGroup">
					<li class="TabbedPanelsTab" tabindex="0">Avance Presupuestal</li>
					<li class="TabbedPanelsTab" tabindex="1">Comparativo Avance Físico
						y Presupuestal</li>
				</ul>
				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="4" align="center" valign="bottom">
              	<?php
            if ($idMode != md5("ajax_view")) {
                ?>
					<input type="button" value="Guardar Comentarios"
									class="btn_save_custom" title="Guardar Comentarios"
									onclick="GuardarComentarios();" />
              	<?php
            }
            ?>
			  </td>
							</tr>
							<tr>
								<td nowrap="nowrap"></td>
								<td width="42%"></td>
								<td width="14%" align="left">&nbsp;</td>
							</tr>
							<tr>
								<td nowrap="nowrap"><b>Componente</b>&nbsp;</td>
								<td><select name="cbocomponente_ia" id="cbocomponente_ia"
									style="width: 500px;" onChange="LoadAvancePresupuestal();">
										<option value=""></option>
                <?php
                $rs = $objInf->ListaComponentes($idProy);
                $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                ?>
              </select></td>
								<td align="left">&nbsp; <input type="button" value="Refrescar"
									class="btn_save_custom" onClick="LoadAvancePresupuestal();"
									title="Refrescar Presupuesto" />
								</td>
							</tr>
							<tr>
								<td colspan="3"></td>
							</tr>
						</table>
						<div id="divAvancePresupuestal" class="TableGrid">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td height="207">&nbsp;</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="TabbedPanelsContent">
						<table width="100%" border="0" cellspacing="0" cellpadding="0"
							class="TableEditReg">
							<tr>
								<td width="8%">&nbsp;</td>
								<td colspan="2">&nbsp;</td>
								<td width="36%" rowspan="4" align="center" valign="bottom">
              <?php
            if ($idMode != md5("ajax_view")) {
                ?>
				  <input type="button" value="Guardar Comentarios"
									class="btn_save_custom" title="Guardar Comentarios"
									onclick="GuardarComentarios2();" />
              <?php
            }
            ?>
			  </td>
							</tr>
							<tr>
								<td nowrap="nowrap"></td>
								<td width="42%"></td>
								<td width="14%" align="left">&nbsp;</td>
							</tr>
							<tr>
								<td nowrap="nowrap"><b>Componente</b>&nbsp;</td>
								<td><select name="cbocomponente_fi" id="cbocomponente_fi"
									style="width: 500px;" onChange="LoadAvanceFisico();">
										<option value=""></option>
                <?php
                $rs = $objInf->ListaComponentes($idProy);
                $objFunc->llenarComboI($rs, 't08_cod_comp', 'componente', '');
                ?>                
              </select></td>
								<td align="left">&nbsp; <!--img src="../../../img/btnRecuperar.gif" width="17" height="17" style="cursor:pointer;" onClick="LoadAvanceFisico();" title="Refrescar Presupuesto" / osktgui-->
									<input type="button" value="Refrescar" class="btn_save_custom"
									onClick="LoadAvanceFisico();" title="Refrescar Presupuesto" />
								</td>
							</tr>
							<tr>
								<td colspan="3"></td>
							</tr>
						</table>
						<div id="divComparativo" class="TableGrid"></div>
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" name="t02_cod_proy"
			value="<?php echo($idProy);?>" /> <input type="hidden"
			name="t02_version" value="<?php echo($idVersion);?>" /> <input
			name="t25_anio" type="hidden" id="t25_anio"
			value="<?php echo($idAnio);?>" /> <input name="t25_trim"
			type="hidden" id="t25_trim" value="<?php echo($idTrim);?>" />
		<script language="javascript" type="text/javascript">
   
   	function LoadAvancePresupuestal		()
	{
		var comp  = $('#cbocomponente_ia').val();
		var idNum = "<?php echo($idInforme)?>";
		var idAnio = "<?php echo($idAnio)?>";
		var BodyForm = "action=<?php echo(md5("lista_presupuesto"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idNum="+idNum+"&idFuente=10&idAnio="+idAnio;
		var sURL = "";
		if(comp=="mp")
		{ sURL = "inf_financ_presup_mp.php"; }
		else
		{ sURL = "inf_unico_anual_avance_pre.php"; }
		
		$('#divAvancePresupuestal').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAvancePresupuestal, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	
	function SuccessAvancePresupuestal		(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divAvancePresupuestal").html(respuesta);
 	   return;
	}

	function GuardarComentarios	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if('<?php echo($idInforme);?>'=='')
	{
		alert('Primero debe Grabar la Cabecera del Informe.');
		return ;
	}
	
	var BodyForm=$("#FormData .presup").serialize();
	
	if(BodyForm=='')
	{
		alert("No hay Datos para Grabar...");
		return;
	}
	
	if(confirm("Estas seguro de Guardar los comentarios ingresados en el Informe  ?"))
	  {
		var sURL = "inf_unico_anual_process.php?action=<?php echo(md5('ajax_coment_avance_presup'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, ComentariosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function ComentariosSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAvancePresupuestal();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}

  function GuardarComentarios2	()
	{
		<?php $ObjSession->AuthorizedPage(); ?>	
	<?php
if ($idInforme == '') {
    echo ("alert('Primero debe Grabar la Cabecera del Informe, y Establecer el Periodo de Referencia');");
    echo ("return;");
}
?>
	var BodyForm=$("#FormData .fisico").serialize();
	if(BodyForm=='')
	{
		alert("No hay Datos para Grabar...");
		return;
	}
	
	if(confirm("Estas seguro de Guardar los Comentarios acerca del Avance Fisico ingresados para el Informe ?"))
	  {
		var sURL = "inf_unico_anual_process.php?action=<?php echo(md5('ajax_coment_avance_fisico'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, Comentarios2SuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function Comentarios2SuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAvanceFisico();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
  function LoadAvanceFisico		()
	{	
		var comp  = $('#cbocomponente_fi').val();
		var idAnio = "<?php echo($idAnio)?>";
		var idNum = "<?php echo($idInforme)?>";
		var BodyForm = "action=<?php echo(md5("lista_Avance_Fisico"));?>&idProy=<?php echo($idProy);?>&idComp="+ comp  + "&idNum="+idNum+"&idAnio="+idAnio;
		
		var sURL = "";
		if(comp=="mp")
		{ sURL = "inf_financ_fisico.php"; }
		else
		{ sURL = "inf_unico_anual_avance_fisico.php"; }
				
		$('#divComparativo').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessAvanceFisico, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	}
	function SuccessAvanceFisico			(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divComparativo").html(respuesta);
 	   return;
	}

	var TabsPlanes = new Spry.Widget.TabbedPanels("ssTabPlanes", {defaultTab:0});
	
	LoadAvancePresupuestal(false);
	
</script>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>