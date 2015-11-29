<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLMantenimiento.class.php");
$objMante = new BLMantenimiento();
$view = $objFunc->__GET('mode');
$Modulo = $objFunc->__GET('modulo');

$row = 0;

if ($view == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Editando Menu");
    $id = $objFunc->__GET('id');
    $row = $objMante->MenuSeleccionar($id);
} else {
    $row = 0;
    $objFunc->SetSubTitle("Nuevo Menu");
}
?>

<?php if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<?php
    
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<script src="../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="TemplateEditDetails" -->

				<!-- InstanceEndEditable -->
				<div id="divContent">
					<!-- InstanceBeginEditable name="Contenidos" -->
 <?php } ?>
 
	<div id="EditForm" style="width: 780px; border: solid 1px #D3D3D3;">
						<br />
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="btnGuardar_Clic(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="btnCancelar_Clic(); return false;" value="Cancelar">
											Cancelar</button></td>
									<td width="31%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
								</tr>
							</table>
						</div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td width="542">
									<fieldset>
										<legend>Datos Generales</legend>
										<table width="100%" border="0" cellspacing="1" cellpadding="0">
											<tr>
												<td width="18%">Codigo</td>
												<td width="33%"><input name="txtcodigo" type="text"
													id="txtcodigo" value="<?php echo($row['codigo']); ?>"
													size="12" maxlength="8"
													<?php if($view == md5("ajax_edit")){echo('disabled="disabled"');}?> />
          <?php
        if ($view == md5("ajax_edit")) {
            echo ('<input name="txtcodigo" type="hidden" id="txtcodigo" value="' . $row['codigo'] . '">');
        }
        ?>
          <input type="hidden" name="txtmodulo" id="txtmodulo"
													value="<?php echo($Modulo);?>" /></td>
												<td width="3%">&nbsp;</td>
												<td width="46%" rowspan="9" align="left" valign="top">
													<fieldset>
														<legend>Paginas Secundarias</legend>
														<table width="100%" border="0" cellspacing="0"
															cellpadding="0" style="padding: 0px;">
															<tr>
																<td width="6%" align="center"><input type="image"
																	src="../img/addicon.gif" width="14" height="15"
																	style="border: none;"
																	onclick="AgregarPagina(); return false;"
																	title="Seleccionar Paginas Secundarias" /></td>
																<td width="16%" align="center" valign="middle"><span
																	style="font-size: 11px; font-weight: bold; cursor: pointer;"
																	onclick="AgregarPagina();">Agregar</span></td>
																<td width="4%">&nbsp;</td>
																<td width="6%"><input type="image" style="border: none;"
																	src="../img/b_drop.png" width="14" height="15"
																	onclick="EliminarPagina(); return false;"
																	title="Eliminar Pagina Secundaria" /></td>
																<td width="68%" valign="middle"><span
																	style="font-size: 11px; font-weight: bold; cursor: pointer;"
																	onclick="EliminarPagina();">Eliminar</span></td>
															</tr>
														</table>
														<select name="lstpaginas[]" id="lstpaginas" size="10"
															multiple style="width: 100%;">
														</select>
													</fieldset>
												</td>
											</tr>
											<tr>
												<td nowrap="nowrap">Titulo del Menu</td>
												<td><input name="txtnombre" type="text" id="txtnombre"
													value="<?php echo($row['titulo']); ?>" size="50"
													maxlength="50" /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Link Menu</td>
												<td><input name="txtlink" type="text" id="txtlink"
													value="<?php echo($row['link']); ?>" size="50"
													maxlength="150" /></td>
												<td><input type="image" name="btnBuscar2" id="btnBuscar2"
													src="../img/gosearch.gif" width="14" height="15"
													class="Image" onclick="BuscarLink(); return false;"
													title="Seleccionar Pagina" /></td>
											</tr>
											<tr>
												<td>Es Contenedor ?</td>
												<td><input type="checkbox" name="chkcontainer"
													id="chkcontainer" value="1"
													<?php if($row['clase']!=''){echo('checked');}?> /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Menu Contenedor</td>
												<td><select name="cboparent" id="cboparent"
													style="width: 190px">
														<option value=""></option>
            <?php
            $rs = $objMante->ListaMenusContenedores($Modulo);
            $objFunc->llenarComboGroupI($rs, 'codigo', 'nommenu', $row['parent'], 'parentmenu');
            ?>
          </select></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td height="19">Destino</td>
												<td><select name="cbotarget" id="cbotarget"
													style="width: 80px">
														<option value="_self"
															<?php if($row['target']=='_self') {echo('selected');}?>>_self</option>
														<option value="_parent"
															<?php if($row['target']=='_parent') {echo('selected');}?>>_parent</option>
														<option value="_blank"
															<?php if($row['target']=='_blank') {echo('selected');}?>>_blank</option>
														<option value="_top"
															<?php if($row['target']=='_top') {echo('selected');}?>>_top</option>
														<option value="_tab"
															<?php if($row['target']=='_tab') {echo('selected');}?>>_tab</option>
												</select></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Orden</td>
												<td><input name="txtsort" type="text" id="txtsort"
													value="<?php echo($row['orden']); ?>" size="3"
													maxlength="2" /></td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>Nombre Imagen</td>
												<td><input name="txtimage" type="text" id="txtimage"
													value="<?php echo($row['link_imagen']); ?>" size="50"
													maxlength="60" /></td>
												<td><input type="image" name="btnBuscar" id="btnBuscar"
													src="../img/gosearch.gif" width="14" height="15"
													class="Image" onclick="BuscarImagenes(); return false;"
													title="Seleccionar Imagenes" /></td>
											</tr>
											<tr>
												<td>Activo</td>
												<td><input type="checkbox" name="chkactivo" id="chkactivo"
													value="1" <?php if($row['activo']=='1'){echo('checked');}?> /></td>
												<td>&nbsp;</td>
											</tr>

										</table>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>

					<script language="javascript">
  function btnGuardar_Clic()
	{
	 if( $('#txtcodigo').val()=="" ) {alert("Ingrese Codigo del Menu"); return false;}	
	 if( $('#txtnombre').val()=="" ) {alert("Ingrese Titulo del Menu"); return false;}	
	 
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = "man_mnu_process.php?action=<?php echo($view);?>"
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	
	return false;
	
	}
	
	function BuscarImagenes()
	{
		var url = 'man_mnu_busca_img.php?mode=<?php echo(md5("buscar Imagenes"));?>&txtReturn=txtimage' ;
		loadPopup("Buscar Imagenes",url)
		return false ;
	}
	function BuscarLink()
	{
		var url = 'man_mnu_busca_link.php?mode=<?php echo(md5("buscar pagina"));?>&txtReturn=txtlink' ;
		loadPopup("Buscar Imagenes",url)
		return false ;
	}
	function AgregarPagina()
	{
		if($('#txtcodigo').val()=="")
		{ alert("Ingrese Codigo del Menu"); return false;}
		
		var url = 'man_mnu_pag_sec.php?mode=<?php echo(md5("buscar paginas"));?>&txtReturn=txtimage&txtmodulo='+$('#txtmodulo').val() ;
		loadPopup("Agregar Pagina Secundaria de [" + $('#txtnombre').val() + "]" ,url)
		return false ;
	}
	function EliminarPagina()
	{
		 if( $('#lstpaginas').html()=="" ) {alert("No se han asignado paginas secundarias para el Menu"); return false;}	
		 
		 var BodyForm = $("#FormData").serialize() ;
		 var sURL = "man_mnu_process.php?action=<?php echo(md5("ajax_elimina_menu_paginas"));?>"
		 var req = Spry.Utils.loadURL("POST", sURL, true, SuccessEliminarPaginaMenu, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	 
	}
	function SuccessEliminarPaginaMenu(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{ 	
			alert(respuesta.replace(ret,"")); 
			CargarListaMenusPagina();
		}
		else
		{alert(respuesta);}  
	}
	function CargarListaMenusPagina()
	{
	  var pURL = "man_mnu_process.php?action=<?php echo(md5("ajax_lista_menu_paginas"));?>&mnu_cod="+$('#txtcodigo').val();
	  var req = Spry.Utils.loadURL("POST", pURL, true, MySuccessLoadPaginas, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	}
	
	function MySuccessLoadPaginas(req)
    {
	  var respuesta = req.xhRequest.responseText;
	  $("#lstpaginas").html(respuesta);
 	  return;
    }
  </script>


					<script>
  CargarListaMenusPagina();
  </script>
  

 <?php if($view=='') { ?>
  <!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>
<?php } ?>

