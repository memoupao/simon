<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Proyectos - Planes de Creditos a Beneficiarios");
$objFunc->SetSubTitle("Plan de Créditos a Beneficiarios");
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


function ExportarManejoProy()
	{
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + $('#txtCodProy').val();
			arrayControls[1] = "idVersion=" + $('#cboversion').val();
		var params = arrayControls.join("&");

		var sID = "12" ;
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

	dsproyectos = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: false});
	pvProyectos	= new Spry.Data.PagedView(dsproyectos, { pageSize: 10});
	pvProyectosPagedInfo = pvProyectos.getPagingInfo();
}
</script>
<SCRIPT src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></SCRIPT>
<script src="../../../js/commons.js" type="text/javascript"></script>

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
											id="cboversion" onchange="LoadPlanes();">
            <?php
            $rsVer = $objProy->ListaVersiones($row['t02_cod_proy']);
            $objFunc->llenarCombo($rsVer, 't02_version', 'nom_version', $row['t02_version']);
            ?>
            </select></td>
										<td width="302" valign="middle"><input name="txtNomejecutor"
											type="text" readonly="readonly" id="txtNomejecutor" size="50"
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
						<td width="37%" align="right" valign="bottom"
							style="padding-right: 30px;"></td>
					</tr>
				</table>
				<div id="divContent"
					style="font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">

					<div></div>
				</div>


				<br />

				<script language="javascript" type="text/ecmascript">
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
				<div id="panelPopup" class="popupContainer" style="height: 500px;">
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

				<script language="JavaScript" type="text/javascript">
			var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
			var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
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

			function loadPopup(title, url)
			  {
				$('#titlePopup').html(title);
				$('#divChangePopup').html('<p align="center"><img src="../../../img/indicator.gif" width="16" height="16" /><br>Cargando...</p>');
				$('#divChangePopup').load(url);
				spryPopupDialog01.displayPopupDialog(true);
				return false ;
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
					$("#txtNomejecutor").val( htmlEncode(row.ejecutor));
					$("#txtNomproyecto").val(htmlEncode(row.nombre));

					spryPopupDialogBuqueda.displayPopupDialog(false);

					$("#divContent").css('display', 'block');
					$("#divContentEdit").css('display', 'none');

					//$('#FormData').submit();
					var BodyForm = 'idProy='+row.codigo;
					var url = '../datos/process.php?action=<?php echo(md5("ajax_lista_versiones"));?>';
					var req = Spry.Utils.loadURL("POST", url, true, SuccessVS, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorPlanes });

					var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
					$.get(setURL);

					return;
				}
				else
				{ alert("Error al Seleccionar el Proyecto !!!"); }
			}

			function SuccessVS(req)
			{
				var respuesta = req.xhRequest.responseText;
				$("#cboversion").html(respuesta);
				LoadPlanes();
			}

			function LoadPlanes()
			{
				var idProy    = $("#txtCodProy").val();
				var idVersion = $("#cboversion").val();
				var BodyForm = "idProy="+idProy+"&idVersion="+idVersion ;
				var sURL = "plan_cre_list.php?action=<?php echo(md5("edit_plan_capac"));?>";

				$('#divContent').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
				var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanes, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorPlanes });
			}
			function SuccessPlanes(req)
			{
			  var respuesta = req.xhRequest.responseText;
			  $("#divContent").html(respuesta);
			  return;
			}
			function onErrorPlanes(req)
			{
				alert("No se ha logrado cargar la pagina \n"+req.xhRequest.responseText);
				return ;
			}

			LoadPlanes();
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
