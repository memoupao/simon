<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php require_once(constant('PATH_CLASS')."HardCode.class.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Instituciones - Contactos");
$objFunc->SetSubTitle("Contactos de Institucion ");
$objHC = new HardCode();
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../img/feicon.ico"
	type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../../css/template.css" rel="stylesheet" media="all" />
<script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<SCRIPT src="../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<SCRIPT src="../../js/s3Slider.js" type=text/javascript></SCRIPT>
<!-- InstanceBeginEditable name="jQuery" -->
<script src="../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../SpryAssets/SpryPagedView.js" type="text/javascript"></script>


<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<script src="../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<script type="text/javascript">
var dsInstituciones = null ;
LoadInstituciones();

function LoadInstituciones()
{
	dsInstituciones = new Spry.Data.XMLDataSet("process.xml.php?action=<?php echo(md5("filter"));?>", "ejecutor/rowdata", {sortOnLoad: "t01_sig_inst", sortOrderOnLoad: "ascending", useCache: true});
	dsInstituciones.setColumnType("t01_fch_fund", "date");
}
</script>

<script type="text/javascript">
	var dsLista = null ;
	var pvLista = null;
	var pvListaPagedInfo = null;

	$("#divContent").css('display', 'none');
	<?php
$idInstDefault = "*";
if ($ObjSession->PerfilID == $objHC->Ejec || $ObjSession->PerfilID == $objHC->ME) {
    $idInstDefault = $ObjSession->IdInstitucion;
}
?>
	LoadDataGrid('<?php echo(idInstDefault);?>');
	function LoadDataGrid(idInst)
	{
	 	var xmlurl = "process_cto.xml.php?action=<?php echo(md5("lista"));?>&idInst="+idInst;

		if(dsLista!=null)
		{
			if(dsLista.url != xmlurl)
			{
				dsLista.setURL(xmlurl);
				dsLista.loadData();

			}
			else {dsLista.loadData();}
		}
		else
		{
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "contactos/rowdata", {useCache: false});
			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});
			pvListaPagedInfo = pvLista.getPagingInfo();
		}
	}

	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");


	function ExportarContacto()
{

	var row = dsLista.getRowByID(dsLista.getCurrentRowID());
	if(row)
	{
	    var like = document.getElementById("txtBuscar");
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + row.t01_id_inst;
			 if(like.value!=""){
				 arrayControls[1] = "like=" + like.value;
			 }
		var params = arrayControls.join("&");
		var sID = "27" ;

		showReport(sID, params);
	}
	else
	{ alert("No se ha seleccionado ningun Proyecto."); return;}
}

 function showReport(reportID, params)
  {
	 var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;

	 $('#FormData').attr({target: "winReport"});
	 $('#FormData').attr({action: newURL});
	 $('#FormData').submit();
	 $('#FormData').attr({target: "_self"});
     $("#FormData").removeAttr("action");
  }

</script>
<link href="../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />

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
      <?php include("../../includes/Banner.php"); ?>
  </div>
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
        <?php include("../../includes/MenuBar.php"); ?>
      </ul>
		</div>
		<script type='text/javascript'>
        var MenuBar1 = new Spry.Widget.MenuBar('MenuBar1');
     </script>

		<div class="Subtitle">
    <?php include("../../includes/subtitle.php");?>
    </div>
		<div class="AccesosDirecto">
        <?php include("../../includes/accesodirecto.php"); ?>
    </div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="Contenidos" -->
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="0%">&nbsp;</td>
						<td width="72%">
    <?PHP
    $row = 0;
    if ($idInstDefault > 0) {
        require (constant('PATH_CLASS') . "BLEjecutor.class.php");
        $objEjec = new BLEjecutor();
        $row = $objEjec->GetEjecutor($idInstDefault);
    }
    ?>
      <fieldset id="Institucion">
								<legend>Institución Ejecutora</legend>

								<table width="100%" border="0" cellpadding="0" cellspacing="2">
									<tr>
										<td width="18%" valign="middle" nowrap="nowrap"><input
											name="txtidInst" type="hidden" id="txtidInst"
											value="<?php echo($row['t01_id_inst']);?>" /> <input
											name="txtSiglaInstitucion" type="text"
											id="txtSiglaInstitucion" size="16"
											title="Ingresar Siglas de la Institución"
											style="text-align: center;" readonly="readonly"
											value="<?php echo($row['t01_sig_inst']);?>" /></td>
										<td width="18" align="center" valign="middle" nowrap="nowrap">
											<input type="image" name="btnBuscar" id="btnBuscar"
											src="../../img/gosearch.gif" width="14" height="15"
											class="Image" onclick="BuscarInstituciones(); return false;"
											title="Seleccionar Instituciones Ejecutoras" />
										</td>
										<td width="80%" valign="middle"><input
											name="txtNomInstitucion" type="text" disabled="disabled"
											class="Readonly" id="txtNomInstitucion" size="70"
											value="<?php echo($row['t01_nom_inst']);?>" /></td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td width="3%"></td>
						<td width="25%">&nbsp;</td>
					</tr>
				</table>

				<div id="divContent"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
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
		var str = row["nombres"]+"|"+row["cargo"]+"|"+row["t01_sig_inst"] ; // Busqueda Multiple
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
								<td width="11%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Refrescar</button>
								</td>
								<td width="34%"><button class="Button" name="btnEditar"
										id="btnEditar" onclick="MostrarTodos(); return false;"
										value="Nuevo" style="white-space: nowrap;">Mostrar Todos</button>
									<button id="exportar" class="Button"
										onclick="ExportarContacto(); return false;" value="Exportar">Exportar</button>
								</td>
								<td width="1%">&nbsp;</td>
								<td width="47%" align="right">
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
					<div class="TableGrid" spry:region="pvLista dsLista">
						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th width="27" height="26">&nbsp;</th>
									<th width="134" align="center" spry:sort="t01_sig_inst">INSTITUCION</th>
									<th width="90" align="center" spry:sort="t01_dni_cto">DNI</th>
									<th width="244" align="center" spry:sort="nombres">APELLIDOS Y
										NOMBRES</th>
									<th width="161" align="center" spry:sort="cargo">CARGO</th>
									<th width="108" align="center" spry:sort="t01_fono_ofi">TELEFONO</th>
									<th width="24" align="center" spry:sort="t01_fono_ofi">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected"
									ondblclick="btnEditar_Clic('{t01_id_inst}','{t01_id_cto}', '<?php echo(md5("ver"));?>');"
									title="Doble Clic para ver el contenido">
									<td nowrap="nowrap"><span>
          	<?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
            <a href="#"><img src="../../img/pencil.gif" width="14"
												height="14" title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{t01_id_inst}','{t01_id_cto}', '<?php echo(md5("editar"));?>');" /></a>
            <?php } ?>
            <?php if($ObjSession->AuthorizedOpcion("VER")) { ?>
            <a href="#"><img src="../../img/bullet.gif" width="12"
												height="12" title="Ver Registro" border="0"
												onclick="btnEditar_Clic('{t01_id_inst}','{t01_id_cto}', '<?php echo(md5("ver"));?>');" /></a>
            <?php } ?>
          </span></td>
									<td align="center">{t01_sig_inst}</td>
									<td align="center">{t01_dni_cto}</td>
									<td align="left">{nombres}</td>
									<td align="center">{cargo}</td>
									<td align="center">{t01_fono_ofi}</td>
									<td align="center"><a href="#"><img
											src="../../img/bt_elimina.gif" width="14" height="14"
											title="Eliminar Registro" border="0"
											onclick="Eliminar('{t01_id_inst}','{t01_id_cto}', '{nombres}');" /></a></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" align="left"><font color="#FFFFFF"> Numero de
											Contactos: &nbsp; <span
											spry:if="'{ds_PageTotalItemCount}'<=0">0</span> <span
											spry:if="'{ds_PageTotalItemCount}'>0">{ds_PageTotalItemCount}</span>
									</font></th>
									<th colspan="3" align="right"><input type="button"
										class="Boton" title="Ir a la Primera Pagina"
										onclick="pvLista.firstPage();" value="&lt;&lt;" /> <input
										type="button" class="Boton" title="Pagina Anterior"
										onclick="pvLista.previousPage();" value="&lt;" /> <input
										type="button" class="Boton" title="Pagina Siguiente"
										onclick="pvLista.nextPage();" value="&gt;" /> <input
										type="button" class="Boton" title="Ir a la Ultima Pagina"
										onclick="pvLista.lastPage();" value="&gt;&gt;" /></th>
								</tr>
							</tfoot>
						</table>
					</div>

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
				<div id="panelBusquedaInst" class="popupContainer"
					style="width: 350px; height: 400px; visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">BUSQUEDA DE INSTITUCIONES EJECUTORAS</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogBuqueda.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent">
							<div id="popupText"></div>
							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								class="TableEditReg">
								<tr>
									<td width="11%" height="25" valign="middle">Buscar:</td>
									<td width="30%" valign="top"><input name="txtBuscaInstitucion"
										type="text" class="Boton" id="txtBuscaInstitucion"
										style="text-align: center;"
										title="Ingresar Siglas de la Institución" size="35"
										onkeyup="StartFilterInstTimer();" /><br /></td>
									<td width="5%" valign="middle">&nbsp;&nbsp;<input type="image"
										src="../../img/btnRecuperar.gif" width="16" height="16"
										onclick="dsInstituciones.loadData(); return false;"
										title="Actualizar Datos" /></td>
									<td width="54%" valign="middle"></td>
								</tr>
							</table>
							<div class="TableGrid" spry:region="dsInstituciones">
								<p spry:state="loading" align="center">
									<img src="../../img/indicator.gif" width="16" height="16" /><br>Cargando...
								</p>
								<table width="580" border="0" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<th width="94" height="20" align="center"
												spry:sort="t01_sig_inst">SIGLAS</th>
											<th width="262" align="center" spry:sort="t01_nom_inst">NOMBRES
												DE LA INSTUTUCION</th>
											<th width="84" align="center" spry:sort="t01_nom_inst">RUC</th>
											<th width="138" align="center" spry:sort="t01_tipo_inst">Tipo
												Inst.</th>
										</tr>
									</thead>
									<tbody class="data" bgcolor="#FFFFFF">
										<tr class="RowData" spry:repeat="dsInstituciones"
											spry:setrow="dsInstituciones" id="Inst{@id}"
											spry:select="RowSelected">
											<td align="center"><A
												href="javascript:SeleccionarInstitucion('{t01_id_inst}')"
												title="Seleccionar Institución">{t01_sig_inst}</A></td>
											<td>{t01_nom_inst}</td>
											<td align="center">{t01_ruc_inst}</td>
											<td>{t01_tipo_inst}</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<th width="94">&nbsp;</th>
											<th width="262">&nbsp;</th>
											<th width="84">&nbsp;</th>
											<th width="138">&nbsp;</th>
										</tr>
									</tfoot>
								</table>
								<script language="JavaScript" type="text/javascript">
	function FilterInstituciones()
	{
	var tf = document.getElementById("txtBuscaInstitucion");
	if (!tf.value)
	{
		dsInstituciones.filter(null);
		return;
	}

	var regExpStr = tf.value;
	//regExpStr = "^" + regExpStr;

	var regExp = new RegExp(regExpStr, "i");
	var filterFunc = function(ds, row, rowNumber)
	{
		var str = row["t01_nom_inst"]+"|"+row["t01_sig_inst"] ; // Busqueda Multiple
		if (str && str.search(regExp) != -1)
			return row;
		return null;
	};

	dsInstituciones.filter(filterFunc);
	}

	function StartFilterInstTimer()
	{
	if (StartFilterInstTimer.timerID)
		clearTimeout(StartFilterInstTimer.timerID);
	StartFilterInstTimer.timerID = setTimeout(function() { StartFilterInstTimer.timerID = null; FilterInstituciones(); }, 100);
	}
	</script>
								<script language="javascript" type="text/javascript">
		function BuscarInstituciones()
		  {
			  spryPopupDialogBuqueda.displayPopupDialog(true);
		  }
		function SeleccionarInstitucion(idInst)
		{
			var rowid = dsInstituciones.getCurrentRowID()
			var row = dsInstituciones.getRowByID(rowid);
			if(row)
			{
				$("#txtidInst").val(row.t01_id_inst);
				$("#txtSiglaInstitucion").val( htmlEncode(row.t01_sig_inst));
				$("#txtNomInstitucion").val(htmlEncode(row.t01_nom_inst));

				spryPopupDialogBuqueda.displayPopupDialog(false);

				$("#divContent").css('display', 'block');
				$("#divContentEdit").css('display', 'none');

				LoadDataGrid(row.t01_id_inst);

				return;
			}
			else
			{ alert("Error al Seleccionar !!!"); }
		}
	</script>
							</div>
						</div>
					</div>
				</div>

				<script language="javascript" type="text/javascript">
var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaInst", {modal:true, allowScroll:true, allowDrag:true});

 function btnNuevo_Clic()
  {
	if($('#txtSiglaInstitucion').val()=="")
	{
		alert("Seleccione una Institucion, para agregar el Contacto");
		BuscarInstituciones();
		return false ;
	}

	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "cto_edit.php?mode=<?php echo(md5("ajax_new"));?>&idInst="+$('#txtidInst').val()+"&id=";
	loadUrlSpry("divContentEdit",url);
	return;
  }

 function btnEditar_Clic(idnst, idcto, accion)
  {
	if(idcto=="" || idnst =="")
	{
		alert("No ha seleccionado ningun registro !!!");
		return;
	}

	var url = "cto_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idInst="+ idnst +"&id="+idcto+"&accion="+accion ;

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
 function btnGuardar_Clic()
	{
	<?php $ObjSession->AuthorizedPage(); ?>

	var aRegex = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;

    if( $('#t01_id_inst').val()=="" ) {alert("No ha seleccionado Institución"); return false;}
	if( $('#t01_ape_pat').val()=="" ) {alert("Ingrese Apellido paterno"); $('#t01_ape_pat').focus(); return false;}
	if( $('#t01_ape_mat').val()=="" ) {alert("Ingrese Apellido Materno"); $('#t01_ape_mat').focus(); return false;}
	if( $('#t01_nom_cto').val()=="" ) {alert("Ingrese Nombres del Contacto"); $('#t01_nom_cto').focus(); return false;}
	if( $('#t01_dni_cto').val()=="" ) {alert("Ingrese DNI"); $('#t01_dni_cto').focus(); return false;}
	if( $('#t01_cgo_cto').val()=="" ) {alert("Seleccione Cargo del Contacto"); $('#t01_cgo_cto').focus(); return false;}
	if( $('#t01_mail_cto').val()=="" ) {alert("Ingrese e-mail del Contacto"); $('#t01_mail_cto').focus(); return false;}
	if (!$('#t01_mail_cto').val().trim().match(aRegex)) {
		alert($('<div></div>').html("Dirección E-mail no es válida.").text());
		$('#t01_mail_cto').focus();
		return false;
	}
	if ($('#t01_mail_cto2').val().trim() != "" && !$('#t01_mail_cto2').val().trim().match(aRegex)) {
		alert($('<div></div>').html("Dirección E-mail 2 no es válida.").text());
		$('#t01_mail_cto2').focus();
		return false;
	}

	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = $('#txturlsave').val();

	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	return false;

	}

  function Eliminar(inst,codigo,Descripcion)
  {
	<?php $ObjSession->AuthorizedPage(); ?>
	if(dsLista.getRowCount()==0)
	{
		alert("No hay Registros para eliminar");
		return;
	}
	if(confirm("Estas seguro de eliminar el Registro \n" + Descripcion))
	{
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ id = row.t01_id_cto; }
		if(id<0) {alert("Selecione un Registro !!!");return false;}

		var BodyForm = "idInst="+inst+"&id="+id;
		var sURL = "process_cto.php?action=<?php echo(md5("ajax_del"))?>";
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
	  //var htmlshowin = $("#EditForm").html();
	  //$(idContainerLoading).html(htmlshowin);
  	  //$(idContainerLoading).fadeIn("slow");
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


 function MostrarTodos()
 {
	 if($('#txtBuscar').val()!="") { dsLista.filter(null); $('#txtBuscar').val(""); }

	 if($('#txtSiglaInstitucion').val()!="")
	 { LoadDataGrid('<?php echo($ObjSession->IdInstitucion);?>');}
	 $('#txtSiglaInstitucion').val("");
	 $('#txtNomInstitucion').val("");
 }

</script>

				<!-- InstanceEndEditable -->
			</form>
		</div>
		<div id="footer">
	<?php include("../../includes/Footer.php"); ?>
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
