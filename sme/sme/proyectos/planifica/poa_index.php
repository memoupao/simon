<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Cronograma de Actividades");
$objFunc->SetSubTitle("Cronograma de Actividades");
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../../img/feicon.ico"
	type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryMenuBarHorizontal.css"
	rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPagedView.js"
	type="text/javascript"></script>
<!-- InstanceEndEditable -->
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>
<SCRIPT src="../../../js/s3Slider.js" type=text/javascript></SCRIPT>
<!-- InstanceBeginEditable name="jQuery" -->

<script src="../../../SpryAssets/SpryPopupDialog.js"
	type="text/javascript"></script>
<script type="text/javascript">
var dsproyectos = null ;
var pvProyectos = null;
var pvProyectosPagedInfo = null;
Loadproyectos();
function Loadproyectos()
{
	var xmlurl = "../datos/process.xml.php?action=<?php echo(md5("buscar"));?>&idInst=<?php echo($ObjSession->IdInstitucion);?>";

	dsproyectos = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: false});
	pvProyectos	= new Spry.Data.PagedView(dsproyectos, { pageSize: 10});
	pvProyectosPagedInfo = pvProyectos.getPagingInfo();
}
</script>

<script type="text/javascript">
	function ChangeVersion(id)
		{

		 var sVersion = $("#cboversion").val();
		 if(sVersion > 0)
		 {
			 $('#FormData').submit();
		 }
		 else {alert("No se especificado la version del Proyecto");}
		}
</script>
<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />
<style type="text/css">
.oneColElsCtrHdr #container #mainContent #FormData table tr td #proyecto table
	{
	font-weight: bold;
}
</style>
<!-- InstanceEndEditable -->
<SCRIPT type=text/javascript>
    $(document).ready(function() {
        $('#slider').s3Slider({
            timeOut: 4500
        });
    });
</SCRIPT>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="banner">
      <?php include("../../../includes/Banner.php"); ?>
  </div>
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
        <?php include("../../../includes/MenuBar.php"); ?>
      </ul>
		</div>
		<script type='text/javascript'>
        var MenuBar1 = new Spry.Widget.MenuBar('MenuBar1');
     </script>

		<div class="Subtitle">
    <?php include("../../../includes/subtitle.php");?>
    </div>
		<div class="AccesosDirecto">
        <?php include("../../../includes/accesodirecto.php"); ?>
    </div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="Contenidos" -->
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="0%">&nbsp;</td>
						<td width="57%">
    <?php
    require (constant('PATH_CLASS') . "BLProyecto.class.php");
    $objProy = new BLProyecto();
    
    $ObjSession->VerifyProyecto();
    
    $row = 0;
    if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
        $row = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
    }
    ?>
      <fieldset id="proyecto">
								<legend>Busqueda de Proyectos</legend>

								<table width="86%" border="0" cellpadding="0" cellspacing="2"
									class="TableEditReg">
									<tr>
										<td width="102" valign="middle" nowrap="nowrap"><input
											name="txtidInst" type="hidden" id="txtidInst"
											value="<?php echo($row['t01_id_inst']);?>" /> <input
											name="txtCodProy" type="text" id="txtCodProy" size="16"
											title="Ingresar Siglas de la Institución"
											style="text-align: center;" readonly="readonly"
											value="<?php echo($row['t02_cod_proy']);?>" /></td>
										<td width="16" align="center" valign="middle" nowrap="nowrap">
											<input type="image" name="btnBuscar" id="btnBuscar"
											src="../../../img/gosearch.gif" width="14" height="15"
											class="Image" onclick="Buscarproyectos(); return false;"
											title="Seleccionar proyectos Ejecutoras" />
										</td>
										<td width="23" align="right" valign="middle">vs</td>
										<td width="41" valign="middle"><select name="cboversion"
											id="cboversion" onchange="ChangeVersion(this.id);">
            <?php
            $rsVer = $objProy->ListaVersiones($row['t02_cod_proy']);
            $objFunc->llenarCombo($rsVer, 't02_version', 'nom_version', $row['t02_version']);
            ?>
            </select></td>
										<td width="302" valign="middle"><input name="txtNomejecutor"
											type="text" readonly="readonly" id="txtNomejecutor" size="53"
											value="<?php echo($row['t01_nom_inst']);?>" /></td>
									</tr>
									<tr>
										<td colspan="5" valign="middle" nowrap="nowrap"><input
											name="txtNomproyecto" type="text" readonly="readonly"
											id="txtNomproyecto" size="88"
											value="<?php echo($row['t02_nom_proy']);?>" /></td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td width="0%"></td>
						<td width="43%">&nbsp;</td>
					</tr>
				</table>
				<script language="javascript" type="text/javascript">
	var dsComponente = null;
	var dsActividades = null;
	var dsSubActividades = null;

	LoadComponentes();
	function LoadComponentes()
	{
		var idProy = '<?php echo($row['t02_cod_proy']);?>';
		var idVersion = '<?php echo($row['t02_version']);?>' ;
		var xmlurl = "process.xml.php?action=<?php echo(md5("lista_componentes"));?>&idProy="+idProy+"&idVersion="+idVersion;
		dsComponente = new Spry.Data.XMLDataSet(xmlurl, "componente/rowdata", {useCache: false});
		LoadActividades();
	}

	function LoadActividades()
	{
		var idProy = '<?php echo($row['t02_cod_proy']);?>';
		var idVersion = '<?php echo($row['t02_version']);?>' ;
		var idComp = 0 ;
		try
		{idComp = document.getElementById("cboComponente").value; }
		catch(ex)
		{idComp=0;}

		var xmlurl = "process.xml.php?action=<?php echo(md5("lista_actividades"));?>&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComp;
		if(dsActividades!=null)
		{ dsActividades.setURL(xmlurl); dsActividades.loadData(); }
		else
		{dsActividades = new Spry.Data.XMLDataSet(xmlurl, "actividades/rowdata", {useCache: false}); }

		LoadSubActividades('0');
	}

	function LoadSubActividades(idAct)
	{
		var idProy = '<?php echo($row['t02_cod_proy']);?>';
		var idVersion = '<?php echo($row['t02_version']);?>' ;
		var idComp = 0 ;
		var idActiv = 0 ;
		var anio = 1;
		try
		{
			idComp = document.getElementById("cboComponente").value;
			anio = document.getElementById("cboAnios").value;
			if(idAct==""){ idActiv = document.getElementById("cboActividad").value; }
			else {idActiv=idAct;}
		}
		catch(ex)
		{
			idComp=0;
			idActiv=0;
		}
		var xmlurl = "process.xml.php?action=<?php echo(md5("lista_subactividades"));?>&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComp+"&idActiv="+idActiv+"&anio="+anio;
		if(dsSubActividades!=null)
		{ dsSubActividades.setURL(xmlurl); dsSubActividades.loadData(); }
		else
		{dsSubActividades = new Spry.Data.XMLDataSet(xmlurl, "subactividades/rowdata", {useCache: false}); }

	}

	</script>



				<div id="divContent"
					style="font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
					<div>
						<table width="100%" border="0" cellpadding="0" cellspacing="2">
							<tr>
								<td width="12%"><strong>COMPONENTE</strong></td>
								<td width="64%">
									<div spry:region="dsComponente">
										<select name="cboComponente" class="TextDescripcion"
											id="cboComponente" style="width: 500px;"
											onchange="LoadActividades();"
											spry:repeatchildren="dsComponente">
											<option value="{t08_cod_comp}">{t08_cod_comp}.
												{t08_comp_desc}</option>
										</select>
									</div>
								</td>
								<td width="20%" rowspan="2" align="left" valign="middle"
									id="toolbar">
									<button class="Button" name="btnCargar" id="btnCargar"
										onclick="LoadSubActividades(''); return false;" value="Cargar"
										style="width: 130px;">Ver Actividades</button>
								</td>
								<td width="4%" rowspan="2" align="left" valign="top"
									nowrap="nowrap">&nbsp;</td>
							</tr>
							<tr>
								<td height="21"><strong>ACTIVIDAD</strong></td>
								<td>
									<div spry:region="dsActividades">
										<select name="cboActividad" class="TextDescripcion"
											id="cboActividad" style="width: 407px;"
											spry:repeatchildren="dsActividades"
											onfocus="VerifyActividades();">
											<option value="{t09_cod_act}">{t08_cod_comp}.{t09_cod_act}
												{t09_act}</option>
										</select> &nbsp;&nbsp; <select name="cboAnios"
											class="TextDescripcion" id="cboAnios" style="width: 80px;"
											onchange="LoadSubActividades('');">
          <?php
        $rs = $objProy->ListaAniosProyecto($row['t02_cod_proy'], $row['t02_version']);
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', '1');
        ?>
        </select>
									</div>

								</td>
							</tr>
							<tr>
								<td colspan="4"><table width="770" border="0" cellspacing="0"
										cellpadding="0">
										<tr>
											<td colspan="15">
												<table width="780" border="0" cellspacing="0"
													cellpadding="0">
													<tr>
														<td><div id="toolbar" style="height: 4px;"
																class="Subtitle">
																<table width="102%" border="0" cellspacing="0"
																	cellpadding="0">
																	<tr>
																		<td width="69%" style="font-size: 12px;">SUB
																			ACTIVIDADES</td>
																		<td width="7%">
																			<button class="Button" name="btnNuevo" id="btnNuevo"
																				onclick="btnNuevo_Clic(); return false;"
																				value="Nuevo" title="Nueva Actividad">Nuevo</button>
																		</td>
																		<!--<td width="10%"> <button class="Button" name="btnEditar"   id="btnEditar" onclick="btnEditar_Clic('',''); return false;" value="Nuevo"> Modificar </button></td>
              <td width="8%"><button class="Button" name="btnEliminar" id="btnEliminar" onclick="btnEliminar(); return false;" value="Eliminar"> Eliminar </button></td>-->
																		<td width="9%">
																			<button class="Button" name="btnExportar"
																				id="btnExportar"
																				onclick="btnExportar_onclick(); return false;"
																				value="Exportar">Exportar</button>
																		</td>
																		<td width="15%" align="right">
																			<button class="Button" name="btnMetas" id="btnMetas"
																				onclick="btnMetas_Clic(); return false;"
																				value="Metas" title="Registro de Metas">Registro
																				Metas</button>
																		</td>
																	</tr>
																</table>
															</div></td>
														<td>&nbsp;</td>
													</tr>
												</table> <br />
												<div class="TableGrid" spry:region="dsSubActividades">
													<div spry:state="loading" align="center">
														<img src="../../../img/indicator.gif" width="16"
															height="16" />
													</div>
													<table class="grid-table grid-width">
														<thead>
															<tr>
																<th width="32" align="center" valign="middle">&nbsp;</th>
																<th width="5" height="28" align="center" valign="middle"
																	spry:sort="subactividad">&nbsp;</th>
																<th width="200" height="28" align="center"
																	valign="middle" spry:sort="subactividad">Actividad</th>
																<th width="55" align="center" valign="middle"
																	spry:sort="unidad">U.M.</th>
																<th width="49" align="center" valign="middle"
																	spry:sort="meta">Meta Fisica</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m1">Mes 1</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m2">Mes 2</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m3">Mes 3</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m4">Mes 4</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m5">Mes 5</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m6">Mes 6</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m7">Mes 7</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m8">Mes 8</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m9">Mes 9</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m10">Mes 10</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m11">Mes 11</th>
																<th width="32" align="center" valign="middle"
																	spry:sort="m12">Mes 12</th>
															</tr>
														</thead>
														<tbody class="data" bgcolor="#FFFFFF">
															<tr class="RowData" spry:repeat="dsSubActividades"
																spry:setrow="dsSubActividades" id="subact_{@id}"
																spry:select="RowSelected">
																<td align="left" nowrap="nowrap" width="32"><a href="#"><img
																		src="../../../img/b_edit.png" width="16" height="16"
																		title="Editar Registro" border="0"
																		onclick="btnEditar_Clic('{subact}');" /></a> <a
																	href="#"><img src="../../../img/b_drop.png" width="16"
																		height="16" title="Eliminar Registro" border="0"
																		onclick="btnEliminar_Clic('{subact}');" /></a></td>
																<td align="left" nowrap="nowrap">{comp}.{act}.{subact}</td>
																<td align="left">{descripcion}</td>
																<td align="center">{um}</td>
																<td align="center" valign="middle">{meta}</td>
																<td align="center" valign="middle">{m1}</td>
																<td align="center" valign="middle">{m2}</td>
																<td align="center" valign="middle">{m3}</td>
																<td align="center" valign="middle">{m4}</td>
																<td align="center" valign="middle">{m5}</td>
																<td align="center" valign="middle">{m6}</td>
																<td align="center" valign="middle">{m7}</td>
																<td align="center" valign="middle">{m8}</td>
																<td align="center" valign="middle">{m9}</td>
																<td align="center" valign="middle">{m10}</td>
																<td align="center" valign="middle">{m11}</td>
																<td align="center" valign="middle">{m12}</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<th colspan="2">&nbsp;</th>
																<th width="81" align="right">&nbsp;</th>
																<th colspan="14" align="right">&nbsp;</th>
															</tr>
														</tfoot>
													</table>

												</div>

											</td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="2">&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>

				<br /> <br />
				<div id="panelEditSubAct" class="popupContainer">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">REGISTRO DE SUBACTIVIDADES</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogEditReg.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>
						<div class="popupContent">
							<div id="popupText2"></div>
							<iframe class="Iframe" src="poa_sact_edit.php"
								id="iSubActividades" name="iSubActividades" scrolling="no"
								style="width: 99%; height: 320px;"></iframe>
						</div>
					</div>
				</div>

				<script language="javascript" type="text/ecmascript">
		  function VerifyActividades()
		  {
			  var idComponente = $('#cboComponente').val();
			  if(idComponente > 0)
			  {
				  var idAct = $('#cboActividad').val();
				  if(idAct=="" || idAct==null )
				  {LoadActividades();}
			  }

		  }

		  function btnNuevo_Clic()
		  {
			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();
			  var idActividad = $('#cboActividad').val();
			  //var idAnio = $('#cboAnios').val();

			  if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto !!!"); return false;}
			  if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}

			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv=";
			  var url = "poa_sact_edit.php?action=<?php echo(md5("new"));?>" + params ;
			  $('#iSubActividades').attr('src',url);
			  spryPopupDialogEditReg.displayPopupDialog(true);
			  return true;
		  }

		  function btnEditar_Clic(idSAct)
		  {
			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();
			  var idActividad = $('#cboActividad').val();

			  if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto !!!"); return false;}
			  if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}
			  if( idSAct == '' || idSAct==null) {alert("Seleccione una Actividad !!!"); return false;}

			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct;
			  var url = "poa_sact_edit.php?action=<?php echo(md5("edit"));?>" + params ;
			  $('#iSubActividades').attr('src',url);
			  spryPopupDialogEditReg.displayPopupDialog(true);
			  return true;
		  }

		  function btnEliminar_Clic(idSAct)
		  {
			  <?php $ObjSession->AuthorizedPage(); ?>

			  if(!confirm("¿ Estas Seguro de Eliminar el Registro seleccionado ?")){return false ;}

			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();
			  var idActividad = $('#cboActividad').val();

			  if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}
			  if( idSAct == '' || idSAct==null) {alert("Seleccione una Actividad !!!"); return false;}

			  var params = "&t02_cod_proy="+idProy+"&t02_version="+idVersion+"&t08_cod_comp="+idComponente+"&t09_cod_act="+idActividad+"&t09_cod_sub="+idSAct;
			  var url = "poa_sact_edit.php?proc=<?php echo(md5("del"));?>" + params ;
			  //spryPopupDialogEditReg.displayPopupDialog(true);
			  $('#iSubActividades').attr('src',url);

			  return true;
		  }

		  function btnExportar_onclick()
		  {
			  alert("En etapa de Desarrollo") ;
			  return false;
		  }

		  function btnCancel_Clic()
		  {
			  spryPopupDialogEditReg.displayPopupDialog(false);
			  return true;
		  }

		  function btnSuccess()
		  {
			  spryPopupDialogEditReg.displayPopupDialog(false);
			  dsSubActividades.loadData();
			  return true;
		  }

		  function btnMetas_Clic()
		  {
			var idProy = $('#txtCodProy').val();
			var idVersion = $('#cboversion').val();
			var idComponente = $('#cboComponente').val();
			var idActividad = $('#cboActividad').val();
			var idSAct = "" ;
			var row = dsSubActividades.getRowByID(dsSubActividades.getCurrentRowID()) ;
			if(row) {  idSAct = row.subact ; }

			if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}
			if( idSAct == '' || idSAct==null) {alert("No se ha seleccionado ninguna Actividad !!!"); return false;}

			var anio = $('#cboAnios').val();
			var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct+"&anio="+anio;
			var url = "poa_meta_edit.php?action=<?php echo(md5("edit"));?>" + params ;
		    $('#iSubActividades').attr('src',url);
		    spryPopupDialogEditReg.displayPopupDialog(true);
		  }
		  </script>




				<div id="panelBusquedaProy" class="popupContainer">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">BUSQUEDA DE PROYECTOS</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogBuqueda.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent">
							<div id="popupText"></div>
							<div id="toolbar">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="11%" height="25" valign="middle">Buscar:</td>
										<td width="30%" valign="top"><input name="txtBuscaproyecto"
											type="text" class="Boton" id="txtBuscaproyecto"
											style="text-align: center;" title="Nombre del Proyecto"
											size="35" onkeyup="StartFilterInstTimer();" /><br /></td>
										<td width="5%" valign="middle">&nbsp;&nbsp;<input type="image"
											src="../../../img/btnRecuperar.gif" width="16" height="16"
											onclick="dsproyectos.loadData(); return false;"
											title="Actualizar Datos" /></td>
										<td width="5%" valign="middle"></td>
										<td width="49%" valign="middle"><span>Mostrar</span> <select
											name="cbopageSize" id="cbopageSize"
											onchange="pvProyectos.setPageSize(parseInt(this.value));"
											style="width: 60px">
												<option value="5">5 Reg.</option>
												<option value="10" selected="selected">10 Reg.</option>
												<option value="20">20 Reg.</option>
												<option value="30">30 Reg.</option>
												<option value="50">50 Reg.</option>
										</select> <span>Ir a </span> <span
											spry:region="pvProyectosPagedInfo" style="width: 75px;"> <select
												name="cboFilter" id="cboFilter"
												onchange="pvProyectos.goToPage(parseInt(this.value));"
												spry:repeatchildren="pvProyectosPagedInfo"
												style="width: 60px">
													<option
														spry:if="({ds_PageNumber} == pvProyectos.getCurrentPage())"
														value="{ds_PageNumber}" selected="selected">Pag.
														{ds_PageNumber}</option>
													<option
														spry:if="({ds_PageNumber} != pvProyectos.getCurrentPage())"
														value="{ds_PageNumber}">Pag. {ds_PageNumber}</option>
											</select>
										</span></td>
									</tr>
								</table>
							</div>
							<div class="TableGrid" spry:region="pvProyectos dsproyectos">
								<p spry:state="loading" align="center">
									<img src="../../../img/indicator.gif" width="16" height="16" /><br>
									Cargando...
								</p>
								<table width="580" border="0" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<th width="115" height="26" align="center"
												spry:sort="ejecutor">EJECUTOR</th>
											<th width="59" align="center" spry:sort="codigo">CODIGO</th>
											<th width="38" align="center" spry:sort="exp">EXP</th>
											<th width="20" align="center" spry:sort="vs">VS</th>
											<th width="335" align="center" spry:sort="nombre">DESCRIPCION
												DEL PROYECTO</th>
										</tr>
									</thead>
									<tbody class="data" bgcolor="#FFFFFF">
										<tr class="RowData" spry:repeat="pvProyectos"
											spry:setrow="pvProyectos" id="{@id}"
											spry:select="RowSelected">
											<td align="left">{ejecutor}</td>
											<td align="center"><A
												href="javascript:Seleccionarproyecto();"
												title="Seleccionar Proyecto">{codigo}</A></td>
											<td align="center">{exp}</td>
											<td align="center">{vs}</td>
											<td align="left">{nombre}</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<th width="115">&nbsp;</th>
											<th width="59" align="right">&nbsp;</th>
											<th colspan="3" align="right"><input type="button"
												class="Boton" title="Ir a la Primera Pagina"
												onclick="pvProyectos.firstPage();" value="&lt;&lt;" /> <input
												type="button" class="Boton" title="Pagina Anterior"
												onclick="pvProyectos.previousPage();" value="&lt;" /> <input
												type="button" class="Boton" title="Pagina Siguiente"
												onclick="pvProyectos.nextPage();" value="&gt;" /> <input
												type="button" class="Boton" title="Ir a la Ultima Pagina"
												onclick="pvProyectos.lastPage();" value="&gt;&gt;" /></th>
										</tr>
									</tfoot>
								</table>

							</div>
						</div>
					</div>
				</div>
				<script language="JavaScript" type="text/javascript">
			var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
			var spryPopupDialogEditReg= new Spry.Widget.PopupDialog("panelEditSubAct", {modal:true, allowScroll:true, allowDrag:true});

		  </script>
				<script language="JavaScript" type="text/javascript">
			function Filterproyectos()
			{
			var tf = document.getElementById("txtBuscaproyecto");
			if (!tf.value)
			{
				dsproyectos.filter(null);
				return;
			}

			var regExpStr = tf.value;
			//regExpStr = "^" + regExpStr;

			var regExp = new RegExp(regExpStr, "i");
			var filterFunc = function(ds, row, rowNumber)
			{
				//var str = row["ejecutor"];
				var str = row["nombre"]+"|"+row["ejecutor"]+"|"+row["codigo"] ;
				if (str && str.search(regExp) != -1)
					return row;
				return null;
			};

			dsproyectos.filter(filterFunc);
			}

			function StartFilterInstTimer()
			{
			if (StartFilterInstTimer.timerID)
				clearTimeout(StartFilterInstTimer.timerID);
			StartFilterInstTimer.timerID = setTimeout(function() { StartFilterInstTimer.timerID = null; Filterproyectos(); }, 100);
			}
			</script>
				<script language="javascript" type="text/javascript">
			function Buscarproyectos()
			  {
				  spryPopupDialogBuqueda.displayPopupDialog(true);
			  }
			function Seleccionarproyecto()
			{
				var rowid = dsproyectos.getCurrentRowID()
				var row = dsproyectos.getRowByID(rowid);
				if(row)
				{
					$("#txtidInst").val(row.t01_id_inst);
					$("#txtCodProy").val(row.codigo);
					$("#cboversion").val(row.vs);
					$("#txtNomejecutor").val( htmlEncode(row.ejecutor));
					$("#txtNomproyecto").val(htmlEncode(row.nombre));


					spryPopupDialogBuqueda.displayPopupDialog(false);

					$("#divContent").css('display', 'block');
					$("#divContentEdit").css('display', 'none');

					$('#FormData').submit();
					//LoadDataGrid(row.t01_id_inst);

					var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
					$.get(setURL);

					return;
				}
				else
				{ alert("Error al Seleccionar el Proyecto !!!"); }
			}
			</script>

<?php
	if($ObjSession->MaxVersionProy($ObjSession->CodProyecto) > $ObjSession->VerProyecto)
	  { echo("<script>alert('Esta versión \"".$ObjSession->VerProyecto."\" del Proyecto \"".$ObjSession->CodProyecto."\", no es Modificable !!!');</script>"); }
?>


  <!-- InstanceEndEditable -->
			</form>
		</div>
		<div id="footer">
	<?php include("../../../includes/Footer.php"); ?>
  </div>

		<!-- Fin de Container Page-->
	</div>

	<script language="javascript" type="text/javascript">
//FormData : Formulario Principal
var FormData = document.getElementById("FormData");
function CloseSesion()
{
	if(confirm("Estas seguro de Cerrar la Sesion de <?php echo($ObjSession->UserName);?>"))
	  {
			FormData.action = "<?php echo(constant("DOCS_PATH"));?>closesesion.php";
			FormData.submit();
	  }
	return true;
}
</script>
</body>
<!-- InstanceEnd -->
</html>
