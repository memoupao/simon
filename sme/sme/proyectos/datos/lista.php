<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php require_once(constant('PATH_CLASS')."HardCode.class.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Proyectos");
$objFunc->SetSubTitle("Proyectos - Datos Generales");
$objHC = new HardCode();
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
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/SpryPopupDialog.js"
	type="text/javascript"></script>
<script type="text/javascript">

var dsInstituciones = null ;
LoadInstituciones();
function LoadInstituciones()
{
	dsInstituciones = new Spry.Data.XMLDataSet("../../ejecutores/process.xml.php?action=<?php echo(md5("filter"));?>", "ejecutor/rowdata", {sortOnLoad: "t01_sig_inst", sortOrderOnLoad: "ascending", useCache: true});
}

</script>
<script type="text/javascript">
	var dsLista = null ;
	var pvLista = null;
	var pvListaPagedInfo = null;
	//modificado 01/11/2011
   <?php
$idInstDefault = "*";
if ($ObjSession->PerfilID == $objHC->Ejec || $ObjSession->PerfilID == $objHC->SE) {
    $idInstDefault = $ObjSession->IdInstitucion;
    ?>
	LoadDataGrid('<?php echo($idInstDefault);?>');	<?php

} else
    if ($ObjSession->PerfilID == $objHC->GP) {
        /* $idInstDefault=$ObjSession->IdInstitucion; */
        ?>
	LoadDataGridMT('<?php echo($idInstDefault);?>');
	<?php

} else  if($ObjSession->PerfilID==$objHC->GP){
	//$idInstDefault=$ObjSession->IdInstitucion;	?>
	LoadDataGrid('<?php echo($idInstDefault);?>');
	<?php

} else {
        ?>	LoadDataGrid('<?php echo($idInstDefault);?>');	<?php } ?>

	$("#divContent").css('display', 'none');

	function LoadDataGrid(idInst)
	{
		var xmlurl = "process.xml.php?action=<?php echo(md5("lista"));?>&idInst="+idInst;
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
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: false});
			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});
			pvListaPagedInfo = pvLista.getPagingInfo();
		}
	}
	//Agregado 30/11/2011
	function LoadDataGridMT(idInst)	{
		var xmlurl = "process.xml.php?action=<?php echo(md5("listaMT"));?>&idInst="+idInst;
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
		dsLista = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: false});
		pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});
		pvListaPagedInfo = pvLista.getPagingInfo();
		}
	}		//Agregado 01/12/2011
	function LoadDataGridMF(idInst)	{				var xmlurl = "process.xml.php?action=<?php echo(md5("listaMF"));?>&idInst="+idInst;		if(dsLista!=null)		{			if(dsLista.url != xmlurl)			{				dsLista.setURL(xmlurl);				dsLista.loadData();			}			else {dsLista.loadData();}		}		else		{ 			dsLista = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: false});			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});			pvListaPagedInfo = pvLista.getPagingInfo();		}	}
$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");

function ExportarProyecto(codigo, vs)
{

	var row = dsLista.getRowByID(dsLista.getCurrentRowID());
	if(row)
	{

		var arrayControls = new Array();
		if($("#cboBuscarPor").val() != "todos"){
			arrayControls[0] = "idProy=" + row.codigo;
			arrayControls[1] = "idVersion=" + row.vs; //1
		}
		else{
			arrayControls[0] = "all=1";
		}

		var params = arrayControls.join("&");
		var sID = "7" ;
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

<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
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
    <?php include("../../../includes/subtitle.php"); ?>
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
											id="txtSiglaInstitucion" style="text-align: center;"
											title="Ingresar Siglas de la Institución"
											value="<?php echo($row['t01_sig_inst']);?>" size="16"
											readonly="readonly" /></td>
										<td width="18" align="center" valign="middle" nowrap="nowrap">
											<input type="image" name="btnBuscar" id="btnBuscar"
											src="../../../img/gosearch.gif" width="14" height="15"
											class="Image" onclick="BuscarInstituciones(); return false;"
											title="Seleccionar Instituciones Ejecutoras" />
										</td>
										<td width="80%" valign="middle"><input
											name="txtNomInstitucion" type="text" disabled="disabled"
											class="Readonly" id="txtNomInstitucion" size="60"
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
	$(document).ready(function(){
		$("#cboBuscarPor").change(function(){
			if($(this).val() == "todos"){ $("#txtBuscar").attr("disabled","disabled");}
			else{$("#txtBuscar").removeAttr("disabled");}
		});
	});

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
		// var str = row["nombre"]+"|"+row["ejecutor"]+"|"+row["codigo"] ; // Busqueda Multiple
		var field = $("#cboBuscarPor").val();
		var str = row[field] ;
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

								<!--modificado 29/11/2011-->
 		<?php
// -------------------------------------------------->
// DA 2.0 [02-11-2013 16:32]
// CMF Ya no existira en el sistema
// if($ObjSession->AuthorizedOpcion("NUEVO") && ($ObjSession->PerfilID!=$objHC->Ejec) && ($ObjSession->PerfilID!=$objHC->CMF))
// --------------------------------------------------<
?>
        <?php if($ObjSession->AuthorizedOpcion("NUEVO") ) { ?>
        <td width="7%">
									<button class="Button" name="btnNuevo" id="btnNuevo"
										onclick="btnNuevo_Clic(); return false;" value="Nuevo">Nuevo</button>
								</td>
		<?php }  ?>
        <td width="10%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Refrescar</button>
								</td>
								<td width="13%"><button class="Button" name="btnEliminar"
										id="btnEliminar" onclick="MostrarTodos(); return false;"
										value="Eliminar" style="white-space: nowrap;">Mostrar Todos</button></td>
								<td width="18%"><button id="exportar" class="Button"
										onclick="ExportarProyecto(); return false;" value="Exportar">Exportar</button></td>

								<td width="30%">
									<div>
										<span>Buscar</span> <select name="cboBuscarPor"
											id="cboBuscarPor" onchange="StartFilterTimer();"
											style="width: 80px">
											<option value="ejecutor" selected="selected">Ejecutor</option>
											<option value="codigo">Codigo Proyecto</option>
											<option value="exp">Expediente</option>
											<option value="nombre">Nombre del Proyecto</option>
											<option value="todos">Todos</option>
										</select> <input name="txtBuscar" type="text" id="txtBuscar"
											size="20" onkeyup="StartFilterTimer();"
											title="Buscar Proyecto" />
									</div>

								</td>
								<td width="22%" align="right">
									<div>
										<span>Ver</span> <select name="cbopageSize" id="cbopageSize"
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
									<th width="40" height="26">&nbsp;</th>
									<th width="85" align="center" spry:sort="ejecutor">EJECUTOR</th>
									<th width="50" align="center" spry:sort="codigo">CODIGO</th>
									<th width="36" align="center" spry:sort="exp">EXP</th>
									<th width="30" align="center" spry:sort="vs">VS</th>
									<th width="325" align="center" spry:sort="nombre">NOMBRE DEL
										PROYECTO</th>
									<th width="62" align="center" spry:sort="inicio">INICIO</th>
									<th width="57" align="center" spry:sort="termino">TERMINO</th>
									<th width="79" align="center" spry:sort="t02_pres_tot">PRESUP
										GENERAL</th>
									<th width="24" align="center" spry:sort="t02_pres_tot">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected"
									ondblclick="btnEditar_Clic('{codigo}','{vs}'); return false;">
									<td nowrap="nowrap" align="right"><span>
          <?php

if ($ObjSession->AuthorizedOpcion("EDITAR")) {
            // modificado 01/12/2011
            if ($ObjSession->PerfilID == $objHC->Ejec) {
                ?>
            <a href="#" spry:if="'{env_rev}' == '0'"> <img
												src="../../../img/pencil.gif" width="14" height="14"
												title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{codigo}','{vs}', '<?php echo(md5("editar"));?>');" /></a>
          <?php

} else
                if ($ObjSession->PerfilID == $objHC->GP) {
                    ?>
		  <span spry:if="'{env_rev}' != '0'"> <a href="#"
												spry:if="'{vbProyecto}' != '1'"> <!--span spry:if="'{mtecnico}' == '<?php echo $ObjSession->IdInstitucion;?>'" <?php echo $ObjSession->IdInstitucion;?>-->
													<img src="../../../img/pencil.gif" width="14" height="14"
													title="Editar Registro" border="0"
													onclick="btnEditar_Clic('{codigo}','{vs}', '<?php echo(md5("ver"));?>');" />
													<!--/span-->
											</a>
										</span>
		<?php

} else {
                    ?>		  <a href="#"><img src="../../../img/pencil.gif"
												width="14" height="14" title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{codigo}','{vs}', '<?php echo(md5("editar"));?>');" /></a>		  <?php		  }		  } ?>
          <?php if($ObjSession->AuthorizedOpcion("VER")) { ?>
            <a href="#"><img src="../../../img/bullet.gif" width="12"
												height="12" title="Ver Registro" border="0"
												onclick="btnEditar_Clic('{codigo}','{vs}', '<?php echo(md5("ver"));?>');" /></a>
          <?php } ?>
		 </span></td>
									<td align="left">{ejecutor}</td>
									<td align="center">{codigo}</td>
									<td align="center">{exp}</td>
									<td align="center">{vs}</td>
									<td align="left">{nombre}</td>
									<td align="center">{inicio}</td>
									<td align="center">{termino}</td>
									<td align="right">{t02_pres_tot}</td>
									<td align="center" nowrap="nowrap">
         <?php if($ObjSession->AuthorizedOpcion("ELIMINAR")) { ?>
        <a href="#"><img src="../../../img/bt_elimina.gif" width="14"
											height="14" title="Eliminar Registro" border="0"
											onclick="Eliminar('{codigo}', '{vs}');" /></a>
         <?php } ?>
        </td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th width="40">&nbsp;</th>
									<th width="85">&nbsp;</th>
									<th width="50">&nbsp;</th>
									<th width="36">&nbsp;</th>
									<th width="30">&nbsp;</th>
									<th width="325" align="right">&nbsp;</th>
									<th colspan="4" align="right"><input type="button"
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


				<div id="panelBusquedaInst" class="popupContainer"
					style="width: 350px; height: 400px; visibility: hidden">
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
										src="../../../img/btnRecuperar.gif" width="16" height="16"
										onclick="dsInstituciones.loadData(); return false;"
										title="Actualizar Datos" /></td>
									<td width="54%" valign="middle"></td>
								</tr>
							</table>
							<div class="TableGrid" spry:region="dsInstituciones">
								<p spry:state="loading" align="center">
									<img src="../../../img/indicator.gif" width="16" height="16" /><br>
									Cargando...
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
		var str = row["t01_sig_inst"];
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


				<div id="panelPopup" class="popupContainer"
					style="height: 500px; visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"><span id="titlePopup"></span></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialog01.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText"></div>

							<div id="divChangePopup"
								style="background-color: #FFF; color: #333;"></div>

						</div>
					</div>
				</div>

				<script language="javascript" type="text/javascript">
var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaInst", {modal:true, allowScroll:true, allowDrag:true});
var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";

$(document).ready(function(){

$("form").keypress(function(e) {
  //Enter key
  if (e.which == 13) {
    return false;
  }
});


});

function loadPopup(title, url)
{
	$('#titlePopup').html(title);
	$('#divChangePopup').html(htmlLoading);
	$('#divChangePopup').load(url);
	//$('#divChangePopup').load(url,function(e){ $('#divChangePopup').html(e); });
	
	spryPopupDialog01.displayPopupDialog(true);
	return false ;
}


 function btnNuevo_Clic()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "proy_edit.php?mode=<?php echo(md5("ajax_new"));?>&idInst="+$('#txtidInst').val()+"&id=";
	loadUrlSpry("divContentEdit",url);
	return;
  }


 function MostrarTodos()
 {
	 if($('#txtBuscar').val()!="") { dsLista.filter(null); $('#txtBuscar').val(""); }

	 if($('#txtSiglaInstitucion').val()!="")
	 { 	 //modificado 1/12/2011
	 <?php
if ($ObjSession->PerfilID == $objHC->Ejec || $ObjSession->PerfilID == $objHC->SE) {
    $idInstDefault = $ObjSession->IdInstitucion;
    ?>				
    LoadDataGrid('<?php echo($idInstDefault);?>');			
    <?php			
} else if($ObjSession->PerfilID==$objHC->GP) {				
    $idInstDefault=$ObjSession->IdInstitucion;			
    ?>				
    LoadDataGridMT('<?php echo($idInstDefault);?>');			
    <?php			
} else if($ObjSession->PerfilID==$objHC->GP) {				
    $idInstDefault=$ObjSession->IdInstitucion;			
    ?>				
    LoadDataGridMF('<?php echo($idInstDefault);?>');			
    <?php			
} else { ?>			
    LoadDataGrid('<?php echo($idInstDefault);?>');			
    <?php 
} ?>	 	 
     }


	 $('#txtSiglaInstitucion').val("");
	 $('#txtNomInstitucion').val("");	 	 	 	 //////////		 //////////
 }

 function btnEditar_Clic(idproy, idvs, accion)
  {
	if(idproy=="" || idvs=="")
	{
		alert("No ha seleccionado ningun registro !!!");
		return false;
	}

	var url = "proy_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idproy="+idproy+"&vs="+idvs+"&accion="+accion;

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
	return false;
 }

function CDate(strDate)
    {
        if(strDate==""){return null ;}
        try
        {
            var dt=strDate.split('/');
            var ndate = new Date(Number(dt[2]),Number(dt[1])-1,Number(dt[0])) ;
            return ndate ;
        }
        catch(e)
        {
            return null ;
        }
    }
  function Eliminar(codigo,vs)
  {
	<?php $ObjSession->AuthorizedPage(); ?>

	if(dsLista.getRowCount()==0)
	{
		alert("No hay Registros para eliminar");
		return;
	}

	if(confirm("Estas seguro de eliminar el Registro \n" + codigo))
	{
		//var BodyForm = "idInst="+$('#txtidInst').val()+"&id="+id;
		var BodyForm = "idProy="+ codigo +"&vs="+vs;
		var sURL = "process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	}
  }

  var idContainerLoading = "";
  var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";
  function loadUrlSpry(ContainerID, pURL)
  {
	  idContainerLoading = "#"+ContainerID;
	  //$(idContainerLoading).css('display', 'none');
	  $(idContainerLoading).html(htmlLoading);
	  $(idContainerLoading).fadeIn("slow");
	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

  }

  function MySuccessLoad(req)
  {
	  var respuesta = req.xhRequest.responseText;
  	  //$(idContainerLoading).css('display', 'block');
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
</html>
