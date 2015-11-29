<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Padrón de Beneficiarios - Proyectos");
$objFunc->SetSubTitle("Padrón de Beneficiarios");
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script src="../../../SpryAssets/SpryTabbedPanels.js"
	type="text/javascript"></script>
<script type="text/javascript">
x=$(document);
x.ready(inicializarEventos);

function inicializarEventos()
{
	if ($("#txtCodProy").length > 0) {
	$("#exportar").removeAttr("disabled");
	}else{
		$("#exportar").attr("disabled","-1");
		}
}

function ExportarPadronBenef()
{
	var arrayControls = new Array();
		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
		arrayControls[1] = "idVersion=" + $('#idVersion').val();
	var params = arrayControls.join("&");

	var sID = "13" ;

	showReport(sID, params);
}

function ImportarPadronBenef()
{
	if ($('#txtCodProy').val()!="") {
		$("#divContent").fadeOut("slow");
		$("#divContent").css('display', 'none');
		var url = "bene_import.php?idProy="+$('#txtCodProy').val();
		loadUrlSpry("divContentEdit",url);
		return;	 
	} else {

		alert("Seleccione primero un proyecto.");
		return false;
		
	}
	
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

var dsproyectos = null ;
var pvProyectos = null;
var pvProyectosPagedInfo = null;
Loadproyectos();
function Loadproyectos()
{
	var xmlurl = "../datos/process.xml.php?action=<?php echo(md5("buscar"));?>&idInst=<?php echo($ObjSession->IdInstitucion);?>";
	dsproyectos = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: true});

	pvProyectos	= new Spry.Data.PagedView(dsproyectos, { pageSize: 10});
	pvProyectosPagedInfo = pvProyectos.getPagingInfo();
}

	var dsLista = null ;
	var pvLista = null;
	var pvListaPagedInfo = null;

	$("#divContent").css('display', 'none');

	function LoadDataGrid(idProy)
	{
		var xmlurl = "bene_process.xml.php?action=<?php echo(md5("lista"));?>&idProy=" + idProy ;
		if(dsLista!=null)
		{
			if(dsLista.url != xmlurl)
			{
				//document.write(xmlurl);
				dsLista.setURL(xmlurl);
				dsLista.loadData();
			}
			else {dsLista.loadData();}
		}
		else
		{
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "bene/rowdata", {useCache: false});

			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});
			pvListaPagedInfo = pvLista.getPagingInfo();
		}
	}
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");

</script>

<script type="text/javascript">
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
</script>
<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />
<style type="text/css">
.oneColElsCtrHdr #container #mainContent #FormData table tr td #proyecto table
	{
	font-weight: bold;
}
</style>
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceEndEditable -->
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
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
						<td width="63%">
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
								<input name="idVersion" type="hidden" id="idVersion"
									value="<?php echo($row['t02_version']);?>" />
								<table width="86%" border="0" cellpadding="0" cellspacing="2"
									class="TableEditReg">
									<tr>
										<td width="106" valign="middle" nowrap="nowrap"><input
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
										<td width="366" valign="middle"><input name="txtNomejecutor"
											type="text" readonly="readonly" id="txtNomejecutor" size="65"
											value="<?php echo($row['t01_nom_inst']);?>" /></td>
									</tr>
									<tr>
										<td colspan="3" valign="middle" nowrap="nowrap"><input
											name="txtNomproyecto" type="text" readonly="readonly"
											id="txtNomproyecto" size="88"
											value="<?php echo($row['t02_nom_proy']);?>" /></td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td width="0%"></td>
						<td width="37%">&nbsp;</td>
					</tr>
				</table>
				<div id="divContent"
					style="font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
					<br />
		<?php
$objFunc->verifyAjax();
if ($objFunc->Ajax) {
    ob_clean();
    ob_start();
}
?>
   <div id="toolbar" style="height: 4px;">
						<script language="JavaScript" type="text/javascript">
	<!--
	function FilterData()
	{
	var tf = document.getElementById("txtBuscar");
	if (!tf.value)
	{
		dsLista.filter(null);
		return;
	}

	var regExpStr = tf.value;
	//regExpStr = "^" + regExpStr;
	var regExp = new RegExp(regExpStr, "i");
	var filterFunc = function(ds, row, rowNumber)
	{
		var str = row["nombres"]+"|"+row["t11_especialidad"]+"|"+row["t11_sec_prod"]+"|"+row["t11_subsector"] +"|"+row["t11_direccion"]+"|"+row["t11_ciudad"]+"|"+row["nom_ubig"];
		// var str = row["t01_nom_inst"]+"|"+row["t01_sig_inst"]+"|"+row["t01_tipo_inst"]; // Busqueda Multiple
		if (str && str.search(regExp) != -1)
			return row;
		return null;
	};
	dsLista.filter(filterFunc);
	}

	function StartFilterTimer()
	{
	if (StartFilterTimer.timerID)
		clearTimeout(StartFilterTimer.timerID);
		StartFilterTimer.timerID = setTimeout(function() { StartFilterTimer.timerID = null; FilterData(); }, 100);
	}

	-->

	</script>

						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="7%">
									<button class="Button" name="btnNuevo" id="btnNuevo"
										onclick="btnNuevo_Clic(); return false;" value="Nuevo">Nuevo</button>
								</td>
								<td width="9%">
									<button class="Button" name="btnEditar" id="btnEditar"
										onclick="btnEditar_Clic('',''); return false;" value="Nuevo">
										Modificar</button>
								</td>
								<td width="8%"><button class="Button" name="btnEliminar"
										id="btnEliminar"
										onclick="if(dsLista.getRowCount()>0){Eliminar(dsLista.getRowByID(dsLista.getCurrentRowID()).t11_cod_bene, 	dsLista.getRowByID(dsLista.getCurrentRowID()).nombres); }return false;"
										value="Eliminar">Eliminar</button></td>
								<td width="9%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Actualizar</button>
								</td>
								<td width="9%">
									<button class="Button" onclick="ExportarPadronBenef(); return false;" id="exportar" value="Exportar">Exportar</button>
								</td>
								<td width="8%">
									<button class="Button" onclick="ImportarPadronBenef(); return false;" id="importar" value="Importar">Importar</button>
								</td>
								<td width="50%" align="right">
									<div>
										<span>Buscar</span> <input name="txtBuscar" type="text"
											id="txtBuscar" size="17" onkeyup="StartFilterTimer();"
											title="Buscar Contacto" /> <span>Mostrar</span> <select
											name="cbopageSize" id="cbopageSize"
											onchange="pvLista.setPageSize(parseInt(this.value));"
											style="width: 60px">
											<option value="5">5 Reg.</option>
											<option value="10" selected="selected">10 Reg.</option>
											<option value="20">20 Reg.</option>
											<option value="30">30 Reg.</option>
											<option value="50">50 Reg.</option>
										</select> <span>Ir a </span> <span
											spry:region="pvListaPagedInfo" style="width: 75px;"> <select
											name="cboFilter" id="cboFilter"
											onchange="pvLista.goToPage(parseInt(this.value));"
											spry:repeatchildren="pvListaPagedInfo" style="width: 60px">
												<option
													spry:if="({ds_PageNumber} == pvLista.getCurrentPage())"
													value="{ds_PageNumber}" selected="selected">Pag.
													{ds_PageNumber}</option>
												<option
													spry:if="({ds_PageNumber} != pvLista.getCurrentPage())"
													value="{ds_PageNumber}">Pag. {ds_PageNumber}</option>
										</select>
										</span>
									</div>
								</td>
							</tr>
						</table>
					</div>

					<div class="TableGrid" spry:region="pvLista">

						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th width="44" height="26">&nbsp;</th>
									<th width="79" align="center" spry:sort="t04_dni_equi">DNI</th>
									<th width="160" align="center" spry:sort="nombres">APELLIDOS Y NOMBRES</th>
									<th width="142" align="center" spry:sort="t04_telf_equi">DIRECCIÓN</th>
									<th width="178" align="center" spry:sort="t04_mail_equi">TELEFONOS</th>
									<th width="185" align="center" spry:sort="cargo">MAIL</th>
									<th width="44" height="26">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected">
									<td nowrap="nowrap"><span> <a href="#"><img
												src="../../../img/pencil.gif" width="14" height="14"
												title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{t11_cod_bene}'); return false;" /></a>
									</span></td>
									<td align="left">{t11_dni}</td>
									<td align="left">{nombres}</td>
									<td align="left">{t11_direccion}<br>{t11_ciudad}
									</td>
									<td align="left">{t11_telefono}<br>{t11_celular}
									</td>
									<td align="left">{t11_mail}</td>
									<td nowrap="nowrap"><span> <a href="#"><img
												src="../../../img/bt_elimina.gif" width="14" height="14"
												title="Eliminar Registro" border="0"
												onclick="Eliminar('{t11_cod_bene}', '{nombres}');" /></a>
									</span></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2" style="color: #FFFFFF">Total de Beneficiarios:</th>

									<th width="160" align="left" style="color: #FFFFFF">&nbsp;{ds_PageTotalItemCount}</th>

									<th width="142">&nbsp;</th>
									<th width="178">&nbsp;</th>
									<th align="right"><input type="button" class="Boton"
										title="Ir a la Primera Pagina" onclick="pvLista.firstPage();"
										value="&lt;&lt;" /> <input type="button" class="Boton"
										title="Pagina Anterior" onclick="pvLista.previousPage();"
										value="&lt;" /> <input type="button" class="Boton"
										title="Pagina Siguiente" onclick="pvLista.nextPage();"
										value="&gt;" /> <input type="button" class="Boton"
										title="Ir a la Ultima Pagina" onclick="pvLista.lastPage();"
										value="&gt;&gt;" /></th>
									<th width="45" height="18">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
					</div>
					<div spry:detailregion="pvLista" class="DetailContainer"></div>



   <?php
if ($objFunc->Ajax) {
    ob_end_flush();
    exit();
}
?>
  </div>

				<div id="divContentEdit"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
				</div>
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

				<div id="panelNuevoCacerio" class="popupContainer"
					style="visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">PADRON DE BENEFICIARIOS</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogNuevoCacerio.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>
						<div class="popupContent">
							<div id="popupText2"></div>
							<iframe class="Iframe" src="bene_cacerio_nuevo.php"
								id="iNuevoCacerio" name="iNuevoCacerio" scrolling="no"
								style="width: 99%; height: 320px;"></iframe>
						</div>
					</div>
				</div>


				<script language="JavaScript" type="text/javascript">
			var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
			var spryPopupDialogNuevoCacerio= new Spry.Widget.PopupDialog("panelNuevoCacerio", {modal:true, allowScroll:true, allowDrag:true});


 function btnNuevo_Caserio()
  {
  	var dep = $('#cbodpto').val();
	var prov = $('#cboprov').val();
	var dist = $('#cbodist').val();

	var params = "";
	var url = "bene_cacerio_nuevo.php?action=<?php echo(md5("new"));?>&dep="+dep+"&prov="+prov+"&dist="+dist;
	$('#iNuevoCacerio').attr('src',url);
	spryPopupDialogNuevoCacerio.displayPopupDialog(true);
	return true;
  }

 function btnNuevo_Clic()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "bene_edit.php?mode=<?php echo(md5("ajax_new"));?>&idProy="+$('#txtCodProy').val()+"&id=";
	loadUrlSpry("divContentEdit",url);
	return;
  }
 function btnEditar_Clic(idBene)
  {
    if(dsLista.getRowCount()==0)
	{
		alert("No ha seleccionado ningun registro !!!");
		return;
	}
	if(idBene=="")
	{
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ id = row.t11_cod_bene; }
	}
	else
	{ id = idBene; }


	if(id<0) {alert("Seleccione un Registro !!!");return false;}

	var url = "bene_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idProy="+$('#txtCodProy').val()+"&id="+id ;
	loadUrlSpry("divContentEdit",url);
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');

	return;
  }


 function ReloadLista()
 {
	dsLista.loadData();
	$("#divContentEdit").fadeOut("slow");
	$("#divContentEdit").css('display', 'none');
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
 }
 function CancelEdit()
 {
	$("#divContentEdit").fadeOut("slow");
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	$("#divContentEdit").css('display', 'none');
	return true;
 }
  function btnCancelar_popup()
 {
 	spryPopupDialogNuevoCacerio.displayPopupDialog(false);
	return true;
 }


 function btnGuardar_Clic()
	{
	if( $('#t11_dni').val()=="" ) {alert("Ingrese el DNI"); $('#t11_dni').focus(); return false;}
	if( $('#t11_dni').val().length != 8 ) {alert("DNI incorrecto"); $('#t11_dni').focus(); return false;}

	if ($("#t11_estado").val()=="") {
		alert('Seleccione por favor el Estado');
		$("#t11_estado").focus();
		return false;
	}



	if ($("#t11_nivel_educ").val()=="") {
		alert('Seleccione por favor el Nivel de Instrucción');
		$("#t11_nivel_educ").focus();
		return false;
	}


	if( $('#t11_ape_pat').val()=="" ) {alert("Ingrese el Apellido Paterno"); $('#t11_ape_pat').focus(); return false;}
	if( $('#t11_ape_mat').val()=="" ) {alert("Ingrese el Apellido Materno"); $('#t11_ape_mat').focus(); return false;}
	if( $('#t11_nom').val()=="" ) {alert("Ingrese el Nombre"); $('#t11_nom').focus(); return false;}
//	if( $('#t04_dni_equi').val()=="" ) {alert("Ingrese la Ubicación"); return false;}
	//if( $('#t11_sec_prod_main').val()=="" ) {alert("Ingrese el Principal Sector"); $('#t11_sec_prod_main').focus(); return false;}
	//if( $('#t11_sec_prod').val()=="" ) {alert("Ingrese el Sector Productivo"); $('#t11_sec_prod').focus(); return false;}
	//if( $('#t11_unid_prod_1').val()=="" ) {alert("Ingrese la Unidad de Producción"); $('#t11_unid_prod_1').focus(); return false;}
	//if( $('#t11_tot_unid_prod').val()=="" ) {alert("Ingrese el total de Unidades de Producción"); $('#t11_tot_unid_prod').focus(); return false;}
	//if( $('#t11_nro_up_b').val()=="" ) {alert("Ingrese las unidades producidas por el Proyecto"); $('#t11_nro_up_b').focus(); return false;}
	//if( $('#cbodpto').val()=="" ) {alert("Ingrese Departamento"); $('#cbodpto').focus(); return false;}


	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = $('#txturlsave').val();
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardar, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	return false;

	}


 function MySuccessGuardar(req)
 {

	  var respuesta = req.xhRequest.responseText;

	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var vs = respuesta.substring(0,6);
		alert(respuesta.replace(vs,""));

		var url = "bene_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idProy="+$('#txtCodProy').val()+"&id="+$("#t11_cod_bene").val() ;
		
		

		if ($("#t11_cod_bene").val()=="") {

			ReloadLista();
			
			$("#divContentEdit").fadeOut("slow");
			$("#divContent").fadeIn("slow");
			$("#divContent").css('display', 'block');
			$("#divContentEdit").css('display', 'none');
			
			
			
			
		} else {
			loadUrlSpry("divContentEdit",url);
			
			$("#divContent").fadeOut("slow");
			$("#divContent").css('display', 'none');
		}


	  }
	  else
	  {
		  alert(respuesta);
	  }

 }

  function Eliminar(codigo,Descripcion)
  {
	<?php //$ObjSession->AuthorizedPage(); ?>
	if(dsLista.getRowCount()==0)
	{
		alert("No hay Registros para eliminar");
		return;
	}
	if(confirm("Estas seguro de eliminar el Registro \n" + Descripcion))
	{
		/*
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ codigo = row.t11_cod_bene ; }
		if(codigo<0) {alert("Selecione un Registro !!!");return false;}
		*/
		var BodyForm = "idProy="+$('#txtCodProy').val()+"&id="+codigo+"&nom="+Descripcion;
		var sURL = "bene_process.php?action=<?php echo(md5("ajax_del"))?>";
		//alert(sURL);
		var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
  }


  var idContainerLoading = "";
  function loadUrlSpry(ContainerID, pURL)
  {
	  idContainerLoading = "#"+ContainerID;
	  $(idContainerLoading).css('display', 'none');
	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
  }

  function MySuccessLoad(req)
  {
	  var respuesta = req.xhRequest.responseText;
	  $(idContainerLoading).css('display', 'block');
	  $(idContainerLoading).html(respuesta);
 	  return;
  }

  function MySuccessCall(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	alert(respuesta.replace(ret,"")); dsLista.loadData();	}
	else
	{alert(respuesta);}
  }

  function MyErrorCall(req)
  {	  alert("ERROR: " + req);   }





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
				//var str = row["codigo"];+"|"+row["t11_especialidad"]+"|"+row["t11_sec_prod"]+"|"+row["t11_subsector"] +"|"+row["t11_direccion"]+"|"+row["t11_ciudad"]
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

					//$('#FormData').submit();
					LoadDataGrid(row.codigo);

					var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
					$.get(setURL);

					return;
				}
				else
				{ alert("Error al Seleccionar el Proyecto !!!"); }

			}
			</script>

				<script type="text/javascript">

	LoadDataGrid('<?php echo($ObjSession->CodProyecto);?>');

  </script>

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
