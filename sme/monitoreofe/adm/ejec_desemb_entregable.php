<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$objFunc->SetTitle("Gestores Fondoempleo");
$objFunc->SetSubTitle("Gestores Fondoempleo - Ejecucion de Desembolsos por Entregables");
?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPagedView.js" type="text/javascript"></script>
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<SCRIPT src="../../../js/s3Slider.js" type=text/javascript></SCRIPT>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<script src="<?php echo(PATH_JS);?>jquery.formatCurrency-1.4.0.min.js" type="text/javascript"></script>
<script type="text/javascript">

	var dsproyectos = null ;
	var pvProyectos = null;
	var pvProyectosPagedInfo = null;
	Loadproyectos();

	function Loadproyectos()
	{
		var xmlurl = "../../proyectos/datos/process.xml.php?action=<?php echo(md5("buscar"));?>&idInst=<?php echo($ObjSession->IdInstitucion);?>";
		dsproyectos = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: true});

		pvProyectos	= new Spry.Data.PagedView(dsproyectos, { pageSize: 10});
		pvProyectosPagedInfo = pvProyectos.getPagingInfo();
	}

	function ExportarEjecDesembolsos()
	{
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + $('#txtCodProy').val();
			arrayControls[1] = "idVersion=" + $('#cboversion').val();
			arrayControls[2] = "horizontal=1";
		var params = arrayControls.join("&");
		var sID = "82" ;
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

	function ChangeVersion(id)
	{
	    var sVersion = $("#cboversion").val();
	    if(sVersion > 0)
	    {
	    $('#FormData').submit();
	    }
	    else {alert("No se especificado la version del Proyecto");}
	}

	$("#divContent").css('display', 'none');
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
</script>

<style>
   .tblDesembolsos thead tr{
   	background: #E9E9E9 !important;
   }
   .tblDesembolsos thead tr th{
   	border: 1px solid #999999;
   	padding: 3px 4px;
   }
</style>
<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet" type="text/css" />
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		
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
										
										<td width="13" align="right" valign="middle">vs</td>
							            <td width="39" valign="middle">
							            <select name="cboversion" id="cboversion" onchange="ChangeVersion(this.id);">
							            <?php
							            	$rsVer = $objProy->ListaVersiones($row['t02_cod_proy']) ;
											$objFunc->llenarCombo($rsVer,'t02_version','nom_version',$row['t02_version']);
										?>
							            </select>
							            
							            </td>
										
										<td align="left" valign="middle"><input name="txtNomejecutor"
											type="text" readonly="readonly" id="txtNomejecutor" size="65"
											value="<?php echo($row['t01_nom_inst']);?>" /></td>
									</tr>
									<tr>
										<td colspan="5" valign="middle" nowrap="nowrap"><input
											name="txtNomproyecto" type="text" readonly="readonly"
											id="txtNomproyecto" style="width: 678px;" 
											value="<?php echo($row['t02_nom_proy']);?>" /></td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td width="0%"></td>
						<td width="43%">&nbsp;</td>
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
					
					require (constant('PATH_CLASS') . "BLFE.class.php");

					if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
						$idProy = $ObjSession->CodProyecto;
						$idVersion = $ObjSession->VerProyecto;
					}

					?>
					
					<?php if ($row == 0) { 
					echo 'Es necesario antes que seleccione un proyecto.';
					} else {  ?>
					
					<div id="toolbar" style="height: 4px;">						
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="1%">&nbsp;</td>
        						<td width="7%">
									<button class="Button" name="btnExportar" id="btnExportar"
										onclick="ExportarEjecDesembolsos(); return false;" value="Exportar">Exportar</button>
								</td>
								<td width="1%">&nbsp;</td>
        						<td width="10%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="window.location.reload();" value="Refrescar">
										Refrescar</button>
								</td>								
								<td width="18%"></td>
								<td width="30%"></td>
							</tr>
						</table>
					</div>
					<?php 
					$objEjecDesem = new BLFE();
					
					$rs = $objEjecDesem->getResumenEjecDesembolsos($idProy, $idVersion);
					$numEntregables = mysql_num_rows($rs);

					$data = array();
					$i = 0;
					while ($row = mysql_fetch_array($rs)) {
						$data[$i]['anio'] = $row['anio'];
						$data[$i]['mes'] = $row['mes'];
						$data[$i]['entregable'] = $row['entregable'];
						$data[$i]['periodo'] = $row['periodo'];
						$data[$i]['planeado'] = $row['planeado'];
						$data[$i]['desembolsado'] = $row['desembolsado'];
						$i++;
					}

					?>
					<div class="TableGrid">
						<table cellspacing="0" cellpadding="0" border="0" class="tblDesembolsos">
							<thead>
								<tr>
									<th width="100" align="center" rowspan="2">RESUMEN</th>									
									<th width="" align="center" colspan="<?php echo $numEntregables; ?>">ENTREGABLES</th>
									<th width="120" align="center" rowspan="2">TOTAL</th>
								</tr>
								<tr>
									<?php 
									$i = 0;
									while ($i < $numEntregables) { ?>
									<th width="95" align="center"><?php echo $data[$i]['entregable']?> <br/><?php echo $data[$i]['periodo']?></th>
									<?php 
										$i++;
									}
									?>
								</tr>
							</thead>
							<tbody class="data">
								<tr>
									<td>PLANEADO</td>
									<?php 
									$i = 0;
									$total_plan = 0;

									while ($i < $numEntregables) { ?>
									<td class="right data-plan" val="<?php echo $data[$i]['planeado']; ?>"><?php echo number_format($data[$i]['planeado'], 2); ?></td>
									<?php 
										$total_plan += round($data[$i]['planeado'], 2);
										$i++;
									}
									?>
									<td class="right"><?php echo number_format($total_plan, 2); ?></td>
								</tr>
								<tr>
									<td>DESEMBOLSADO</td>
									<?php 
									$i = 0;
									$total_desemb = 0;

									while ($i < $numEntregables) { ?>
									<td class="right data-desemb" val="<?php echo $data[$i]['desembolsado']; ?>">
										<a href="javascript:editarDesembolsos(<?php echo $data[$i]['anio']; ?>, <?php echo $data[$i]['mes']; ?>);"
											title="Editar Desembolsos">
				                        	<?php echo number_format($data[$i]['desembolsado'], 2); ?>
										</a>
									</td>
									<?php
										$total_desemb += round($data[$i]['desembolsado'], 2);
										$i++;
									} ?>
									<td class="right"><?php echo number_format($total_desemb, 2); ?></td>
								</tr>
								
								<tr>
									<td>SALDO</td>
									<?php 
									$i = 0;
									$total_saldo = 0;

									while ($i < $numEntregables) { ?>
									<td class="right data-saldo" val=""></td>
									<?php
										$total_saldo += (round($data[$i]['planeado'], 2) - round($data[$i]['desembolsado'], 2));
										$i++;
									} ?>
									<td class="right"><?php echo number_format($total_saldo, 2); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php } ?>
					
					<div spry:detailregion="pvLista" class="DetailContainer"></div>
   <?php
if ($objFunc->Ajax) {
    ob_end_flush();
    exit();
}
?>
</div>
				<div id="divContentEdit"></div>

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
				
				<div id="panelPopup" class="popupContainer"
					style="height: 500px; visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"><span id="titlePopup"></span></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialog01.displayPopupDialog(false); btnRefrescar.click();"><b>X</b></a></td>
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
	var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
	var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
	var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";

	var dsLista = null ;
	
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
		spryPopupDialog01.displayPopupDialog(true);
		return false;
	}

	function btnNuevo_Clic()
	{
		$("#divContent").fadeOut("slow");
		$("#divContent").css('display', 'none');
		var url = "ejec_desemb_entregable_edit.php?view=new&idProy="+$('#txtCodProy').val()+"&idTrim=1&idAprobacion=&vs="+$('#cboversion').val();
		loadUrlSpry("divContentEdit",url);
		return;
	}

	function btnEditar_Clic(idproy,vs ,accion)
	{
		if(idproy=="" || vs=="") {
			alert("No ha seleccionado ningun registro !!!");
			return false;
		}

		var url = "ejec_desemb_entregable_edit.php?view=edit&idproy="+idproy+"&accion="+accion+"&vs="+vs;

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

	var idContainerLoading = "";
	var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";
	
	function loadUrlSpry(ContainerID, pURL)
	{
		idContainerLoading = "#"+ContainerID;
		$(idContainerLoading).html(htmlLoading);
		$(idContainerLoading).fadeIn("slow");
		var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	}

	function MySuccessLoad(req)
	{
		var respuesta = req.xhRequest.responseText;
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
	{
		alert("ERROR: " + req);
	}

	function Filterproyectos()
	{
		var tf = document.getElementById("txtBuscaproyecto");
		if (!tf.value) {
			dsproyectos.filter(null);
			return;
		}

		var regExpStr = tf.value;
		var regExp = new RegExp(regExpStr, "i");

		var filterFunc = function(ds, row, rowNumber) {
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
			var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo +"&idVersion="+row.vs;
			$.get(setURL);

			return;
		} else { 
			alert("Error al Seleccionar el Proyecto !!!"); 
		}
	}
</script>
			</form>
		</div>
		<div id="footer">
	<?php include("../../../includes/Footer.php"); ?>
  </div>
</div>

<script language="javascript" type="text/javascript">

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

	$('.right.data-desemb').each(function () {

		var planeado = parseFloat($(this).parent().prev().find('td').eq($(this).index()).attr('val'));
		var desembolsado = parseFloat($(this).attr('val'));
		var saldo = planeado - desembolsado;

		$(this).parent().next().find('td').eq($(this).index()).html(saldo).formatCurrency({symbol: ''});
		$(this).parent().next().find('td').eq($(this).index()).attr('val', saldo);

	});

	function editarDesembolsos(anio, mes)
    {
        var url = "ejec_desemb_entregable_edit.php?idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&anio="+anio+"&mes="+mes+"&mode=<?php echo(md5("edit"));?>";
        loadPopup("<?php echo('Modificación');?> de Desembolsos", url);
    }
</script>
</body>
</html>