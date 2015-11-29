<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php require(constant('PATH_CLASS')."HardCode.class.php");  ?>
<!--Primera modificación-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Fuentes de Financiamiento - Proyectos");
$objFunc->SetSubTitle("Fuentes de Financiamiento");
$objHC = new HardCode(); // 2da modificacion
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
function ExportarFuentesFin()
	{
		if(dsLista.getRowCount()==0)
		{
			alert("No hay datos para exportar !!!");
			return;
		}

		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + $('#txtCodProy').val();
			arrayControls[1] = "idVersion=1";

		var params = arrayControls.join("&");
		var sID = "21" ;

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
		var xmlurl = "fuentes_process.xml.php?action=<?php echo(md5("lista"));?>&idProy=" + idProy ;

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
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "fuentes/rowdata", {useCache: false});
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
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
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
    // modificacion 3
    if ((($row["t02_aprob_pre"] == 1 || $row["t02_env_pre"] == 1) && $ObjSession->PerfilID == $objHC->Ejec) || $ObjSession->PerfilID == $objHC->MT || ($row["t02_aprob_pre"] == 1 && $ObjSession->PerfilID == $objHC->MF)) {
        $modificar = true;
    } else {
        $modificar = false;
    }
    ?>
     <fieldset id="proyecto">
								<legend>Busqueda de Proyectos</legend>

								<table width="86%" border="0" cellpadding="0" cellspacing="2"
									class="TableEditReg">
									<tr>
										<td width="82" valign="middle" nowrap="nowrap"><input
											name="txtidInst" type="hidden" id="txtidInst"
											value="<?php echo($row['t01_id_inst']);?>" /> <input
											name="idVersion" type="hidden" id="idVersion"
											value="<?php echo($row['t02_version']);?>" /> <input
											name="txtCodProy" type="text" id="txtCodProy" size="16"
											title="Ingresar Siglas de la Institución"
											style="text-align: center;" readonly="readonly"
											value="<?php echo($row['t02_cod_proy']);?>" /></td>
										<td width="18" align="center" valign="middle" nowrap="nowrap">
											<input type="image" name="btnBuscar" id="btnBuscar"
											src="../../../img/gosearch.gif" width="14" height="15"
											class="Image" onclick="Buscarproyectos(); return false;"
											title="Seleccionar proyectos Ejecutoras" />
										</td>
										<td width="327" align="left" valign="middle"><input
											name="txtNomejecutor" type="text" readonly="readonly"
											id="txtNomejecutor" size="65"
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
						<td width="43%">&nbsp;</td>
					</tr>
				</table>

				<script>
  LoadDataGrid('<?php echo($ObjSession->CodProyecto);?>');
  </script>
  <?php if($row["t02_aprob_cro"]==1){ ?>
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
		var str = row["t01_sig_inst"] + "|" + row["t01_nom_inst"];
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
										onclick="btnNuevo_Clic(); return false;" value="Nuevo"
										<?php if($modificar) echo "disabled"; ?>>Nuevo</button>
								</td>
<?php 

// -------------------------------------------------->
// DA 2.0 [17-11-2013 13:45]
// Se comento segun requerimiento del mantis nuevo ID 49:

/* ?>								
								<td width="9%">
									<button class="Button" name="btnEditar" id="btnEditar"
										onclick="btnEditar_Clic('',''); return false;" value="Nuevo"
										<?php if($modificar) echo "disabled"; ?>>Modificar</button>
								</td>
								<td width="8%"><button class="Button" name="btnEliminar"
										id="btnEliminar"
										onclick="if(dsLista.getRowCount()>0){Eliminar(dsLista.getRowByID(dsLista.getCurrentRowID()).t01_id_inst, 	dsLista.getRowByID(dsLista.getCurrentRowID()).t01_nom_inst); }return false;"
										value="Eliminar">Eliminar</button></td>
<?php */
// --------------------------------------------------<
?>										
								<td width="9%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Actualizar</button>
								</td>
								<td width="9%"><button class="Button"
										onclick="ExportarFuentesFin(); return false;" id="exportar"
										value="Exportar">Exportar</button></td>
								<td width="30%">&nbsp;</td>
								<td width="50%" align="right">
									<div>
										<span>Buscar</span> <input name="txtBuscar" type="text"
											id="txtBuscar" title="Buscar Fuente de Financiamiento"
											onkeyup="StartFilterTimer();" size="17" /> <span>Mostrar</span>
										<select name="cbopageSize" id="cbopageSize"
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
						<div spry:state="loading" align="center">
							<img src="../../../img/indicator.gif" width="16" height="16" />
						</div>
						<table width="787" border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="22" height="26">&nbsp;</th>
									<th width="111" align="center" spry:sort="t04_dni_equi">SIGLAS</th>
									<th width="193" align="center" spry:sort="nombres">NOMBRE</th>
									<th width="206" align="center" spry:sort="t04_telf_equi">DIRECCION</th>
									<th width="231" align="center" spry:sort="cargo">REPRESENTANTE</th>
									<th width="22" height="26">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected">
									<td nowrap="nowrap"><span>
		 <?php if(!$modificar){ ?>
            <a href="#"><img src="../../../img/pencil.gif" width="14"
												height="14" title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{t01_id_inst}'); return false;" /></a>
        <?php } ?>
		</span></td>
									<td align="left">{t01_sig_inst}</td>
									<td align="left">{t01_nom_inst}</td>
									<td align="left">{t01_dire_inst}</td>
									<td align="left">{representante}</td>
									<td nowrap="nowrap">
		<?php if($ObjSession->AuthorizedOpcion("ELIMINAR")) { ?>
		<a href="#"><img src="../../../img/bt_elimina.gif" width="14"
											height="14" title="Eliminar Registro" border="0"
											onclick="Eliminar('{t01_id_inst}','{t01_nom_inst}');" /></a>
 		 <?php } ?>
		</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th width="44">&nbsp;</th>
									<th width="111">&nbsp;</th>
									<th width="193">&nbsp;</th>
									<th width="206">&nbsp;</th>
									<th align="right" colspan="2"><input type="button"
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
 <?php }else{ ?>
 <div id="Alerta"
					style="padding: 20px; font-size: 14px; color: #003366;">Para
					continuar con el llenado de los Fuentes de Financiamiento, el
					Cronograma de Actividades del Proyecto debe ser Aprobado</div>
 <?php } ?>
<div id="divContentEdit"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
				</div>
				<div id="panelBusquedaProy" class="popupContainer"
					style="visibility: hidden;">
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
							<div id="toolbar" style="height: 8px;">
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
											<td align="center">
					<?php if($ObjSession->PerfilID==$objHC->Ejec){ ?>
					<a spry:if="('{t02_aprob_cro}' == '1')"
												href="javascript:Seleccionarproyecto();"
												title="Seleccionar Proyecto">{codigo}</a> <span
												spry:if="('{t02_aprob_cro}' != '1')">{codigo}</span>
					<?php } else {?>
					<a href="javascript:Seleccionarproyecto();"
												title="Seleccionar Proyecto">{codigo}</a>
					<?php } ?>
				</td>
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
				<div id="panelAgregaInst" class="popupContainer">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">NUEVA INSTITUCIóN</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogNewIns.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent">
							<div id="popupText"></div>
							<div id="toolbar" style="height: 2px;" class="BackColor">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="9%"><button class="Button"
												onclick="btnGuardar_Inst(); return false;" value="Guardar">Guardar
											</button></td>
										<td width="9%"><button class="Button"
												onclick="spryPopupDialogNewIns.displayPopupDialog(false); return false;"
												value="Cancelar">Cancelar</button></td>
										<td width="31%">&nbsp;</td>
										<td width="2%">&nbsp;</td>
										<td width="2%">&nbsp;</td>
										<td width="2%">&nbsp;</td>
										<td width="47%">&nbsp;</td>
									</tr>
								</table>
							</div>
							<table width="580" border="0" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<tr>
									<td align='left'>&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr>
									<td align='left'>&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr>
									<td align='left'>&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr>
									<td width="20" align='left'>&nbsp;</td>
									<td width="207" align="left">SIGLAS</td>
									<td width="117" align="left">&nbsp;</td>
									<td width="117" align="left">&nbsp;</td>
									<td width="119" align="left">&nbsp;</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td colspan="4" align="left"><input name="t01_sig_inst"
										type="text" id="t01_sig_inst"
										value="<?php echo($t01_sig_inst); ?>" /></td>
								</tr>

								<tr>
									<td colspan="5">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>NOMBRE</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td colspan="4"><input name="t01_nom_inst" type="text"
										id="t01_nom_inst" size="70"
										value="<?php echo($t01_nom_inst); ?>" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>TIPO DE INSTITUCIóN</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td colspan="2"><select name="t01_tipo_inst" id="t01_tipo_inst"
										style="width: 300px">
											<option value=""></option>
	   <?php
    require (constant('PATH_CLASS') . "BLTablasAux.class.php");
    
    $OjbTab = new BLTablasAux();
    $rs = $OjbTab->TipoUnidades();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_tipo_inst);
    ?>
                  </select></td>
									<td align="right">
                      <?php $sURLinst = "../../ejecutores/process.php?action=".md5("ajax_new");  ?>
               <input type="hidden" name="txturlsaveInst"
										id="txturlsaveInst" value="<?php echo($sURLinst); ?>" />
									</td>
									<td align="right">&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="right">&nbsp;</td>
								</tr>
							</table>

						</div>
					</div>
				</div>

				<script language="JavaScript" type="text/javascript">
			var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
var spryPopupDialogNewIns= new Spry.Widget.PopupDialog("panelAgregaInst", {modal:true, allowScroll:true, allowDrag:true});



 function btnNuevo_Clic()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "fuentes_edit.php?mode=<?php echo(md5("ajax_new"));?>&idProy="+$('#txtCodProy').val()+"&id=";

	loadUrlSpry("divContentEdit",url);
	return;
  }
 function btnEditar_Clic(idFte)
  {
    if(dsLista.getRowCount()==0)
	{
		alert("No ha seleccionado ningun registro !!!");
		return;
	}
	if(idFte=="")
	{
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ id = row.t01_id_inst; }
	}
	else
	{ id = idFte; }

	if(id<0) {alert("Selecione un Registro !!!");return false;}

	var url = "fuentes_edit.php?mode=<?php echo(md5("ajax_edit"));?>&idProy="+$('#txtCodProy').val()+"&id="+id ;
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

function MySuccessCallInst(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	alert(respuesta.replace(ret,""));
	btnNuevo_Clic();
	 spryPopupDialogNewIns.displayPopupDialog(false);

  }
  else
  {alert(respuesta);}
}

 function btnGuardar_Clic()
	{
	 if( $('#t01_id_inst').val()=="" ) {alert($('<div></div>').html("Escoja una Institución").text()); $('#t01_id_inst').focus(); return false;}
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = $('#txturlsave').val();

	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	return false;

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
	{	/*
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ id = row.t01_id_inst ; }
		if(id<0) {alert("Selecione un Registro !!!");return false;}
		  */
		var BodyForm = "idProy="+$('#txtCodProy').val()+"&id="+codigo;
		var sURL = "fuentes_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
  }

 function btnGuardar_Inst()
	{
	if( $('#t01_sig_inst').val()=="" ) {alert("Ingrese Siglas de la Institución"); return false;}
	 var t01_sig_inst = $('#t01_sig_inst').val();
	 var t01_nom_inst = $('#t01_nom_inst').val();
	 var t01_tipo_inst = $('#t01_tipo_inst').val();

	 var BodyForm = "t01_ruc_inst=&t01_fch_fund=&t01_pres_anio=&t01_dire_inst=&t01_ciud_inst=&t01_fono_inst=&t01_fax_inst=	&t01_mail_inst=	&t01_web_inst=	&t01_ape_rep=	&t01_nom_rep=	&t01_carg_rep=	&t01_sig_inst="+t01_sig_inst+"&t01_nom_inst="+t01_nom_inst+"&t01_tipo_inst="+t01_tipo_inst;

	 var sURL = $('#txturlsaveInst').val();
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallInst, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	return false;

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
	  var htmlshowin = $("#EditForm").html();
	  $(idContainerLoading).html(htmlshowin);
  	  $(idContainerLoading).fadeIn("slow");
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
				var str = row["nombre"]+"|"+row["ejecutor"]+"|"+row["codigo"] ; // Busqueda Multiple
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


					LoadDataGrid(row.codigo);
					var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
					$.get(setURL);

					$('#FormData').submit();
					return;
				}
				else
				{ alert("Error al Seleccionar el Proyecto !!!"); }
			}

function AgregarInst()
{
  spryPopupDialogNewIns.displayPopupDialog(true);
}
			</script>

				<script type="text/javascript">

<!--

//-->
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
