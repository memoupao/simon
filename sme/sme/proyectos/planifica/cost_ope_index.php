<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
require (constant("PATH_CLASS") . "HardCode.class.php");

$objFunc->SetTitle("Proyectos - Presupuesto");
$objFunc->SetSubTitle("Presupuesto");
$objHC = new HardCode();
?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../../img/feicon.ico" type="image/x-icon">
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
<script src="../../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
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

	function ExportarCronograma()
	{
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + $('#txtCodProy').val();
			arrayControls[1] = "idVersion=" + $('#cboversion').val();
		var params = arrayControls.join("&");

		var sID = "12" ;

		//http://localhost/FE/sme/reportes/reportviewer.php?ReportID=3&idProy=C-08-02&idVersion=2
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

<script type="text/javascript">
	function ChangeVersion(id)
		{
		 LoadCostosOperativos();
		 return ;

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
.boton_exp {
	top: 1px;
	vertical-align: middle;
	font: bold 0.7em sans-serif;
	background-color: #EBEBEB;
	font-weight: bold;
	font-size: 11px;
	list-style: none;
	border-left: solid 1px #CCC;
	border-bottom: solid 1px #999;
	border-top: solid 1px #999;
	border-right: solid 1px #999;
	cursor: pointer;
}
</style>
</head>
<body class="oneColElsCtrHdr">
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
						<td width="56%">
    <?php
    require (constant('PATH_CLASS') . "BLProyecto.class.php");
    $objProy = new BLProyecto();

    $ObjSession->VerifyProyecto();
    $ObjSession->VerProyecto = 1;

    $row = 0;
    if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
        $row = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
    }

    if (($row["t02_aprob_pre"] == 1 || $row["t02_env_pre"] == 1) && $ObjSession->PerfilID == $objHC->Ejec) {
        $modificar = true;
    } else {
        $modificar = false;
    }
    ?>
      <fieldset id="proyecto">
								<legend>Busqueda de Proyectos</legend>

								<table width="88%" border="0" cellpadding="0" cellspacing="2"
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
										<td width="21" align="right" valign="middle">vs</td>
										<td width="39" valign="middle"><select name="cboversion"
											id="cboversion" onchange="ChangeVersion(this.id);">
            <?php
            $rsVer = $objProy->ListaVersiones($row['t02_cod_proy']);
            $objFunc->llenarCombo($rsVer, 't02_version', 'nom_version', $row['t02_version']);
            ?>
            </select> <script language="javascript">
			$("#cboversion").val("1");
			$("#cboversion option").attr('disabled','disabled');
			$("#cboversion option:first").removeAttr('disabled');
			</script></td>
										<td width="252" valign="middle"><input name="txtNomejecutor"
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
						<td width="0%" valign="bottom"></td>
						<td width="44%" valign="bottom"></td>
					</tr>
				</table>
				<script language="javascript" type="text/javascript">

	var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";
  	function LoadCostosOperativos()
	{
		var idProy    = $("#txtCodProy").val();
		var idVersion = $("#cboversion").val();

		var idcomp=$('#cboComponente_ope').val();
		if(idcomp=="" || idcomp==null){idcomp=1;}

		var idAct=$('#cboActividad_ope').val();
		if(idAct=="" || idAct==null){idAct=1;}

		var BodyForm = "action=<?php echo(md5("costos_operativos"));?>&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idcomp+"&idAct="+idAct+"&modif=<?php if($modificar){ echo md5("enable");} ?>";
	 	var sURL = "cost_ope.php";
		$('#divCostosOperativos').html(htmlLoading);
	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCostosOperativos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorCosteo });
	}
    function SuccessCostosOperativos	 	(req)
	{
	   var respuesta = req.xhRequest.responseText;
	   $("#divCostosOperativos").html(respuesta);
 	   return;
	}
	function onErrorCosteo(req)
	{
		alert(req.xhRequest.responseText) ;
		return ;
	}

	function loadPopup(title, url)
	  {
		$('#titlePopup').html(title);
		$('#divChangePopup').html(htmlLoading);
		$('#divChangePopup').load(url);
		spryPopupDialog01.displayPopupDialog(true);
		return false ;
	  }

	</script>


  <?php if($row["t02_aprob_cro"]==1){ ?>
  <div id="divContent"
					style="font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
					<div id="divCostosOperativos" style="width: 770px;"></div>
				</div>
	<div id="divContentEdit"
		style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
	</div>
  <?php }else{ ?>		<div id="Alerta"
					style="padding: 20px; font-size: 14px; color: #003366;">Para
					continuar con el llenado del Presupuesto, el Cronograma de
					Actividades del Proyecto debe ser Aprobado</div><?php } ?>
  <br />
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


				<script language="javascript" type="text/ecmascript">
		  function btnCancel_Clic()
		  {
			  spryPopupDialogEditReg.displayPopupDialog(false);
			  return true;
		  }

		  function btnSuccess()
		  {
			  spryPopupDialogEditReg.displayPopupDialog(false);
			  LoadCostosOperativos();
			  return true;
		  }


		  </script>




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
											<td align="center">				<?php if($ObjSession->PerfilID==$objHC->Ejec){ ?>
				<A spry:if="('{t02_aprob_cro}' == '1')"
												href="javascript:Seleccionarproyecto();"
												title="Seleccionar Proyecto">{codigo}</A> <span
												spry:if="('{t02_aprob_cro}' != '1')">{codigo}</span>								<?php } else {?>					<A
												href="javascript:Seleccionarproyecto();"
												title="Seleccionar Proyecto">{codigo}</A>				<?php } ?>				</td>
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
			var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});


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
					//$("#cboversion").val(row.vs);
					$("#txtNomejecutor").val( htmlEncode(row.ejecutor));
					$("#txtNomproyecto").val(htmlEncode(row.nombre));


					spryPopupDialogBuqueda.displayPopupDialog(false);

					$("#divContent").css('display', 'block');
					$("#divContentEdit").css('display', 'none');

					var BodyForm = 'idProy='+row.codigo;
					var url = '../datos/process.php?action=<?php echo(md5("ajax_lista_versiones"));?>';

					var req = Spry.Utils.loadURL("POST", url, true, SuccessVS, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorCosteo });

					var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
					$.get(setURL);

					$('#FormData').submit();
					return;
				}
				else
				{ alert("Error al Seleccionar el Proyecto !!!"); }
			}


			function SuccessVS(req)
			{
				var respuesta = req.xhRequest.responseText;
				$("#cboversion").html(respuesta);
				$("#cboversion").val("1");
				$("#cboversion option").attr('disabled','disabled');
				$("#cboversion option:first").removeAttr('disabled');

				LoadCostosOperativos();
			}


			LoadCostosOperativos();

			</script>

<?php
// modificado 02/12/201 $objHC = new HardCode();
if ($ObjSession->MaxVersionProy($ObjSession->CodProyecto) > $ObjSession->VerProyecto && $ObjSession->PerfilID != $objHC->Admin) {
    echo ("<script>alert('Esta versión \"" . $ObjSession->VerProyecto . "\" del Proyecto \"" . $ObjSession->CodProyecto . "\", no es Modificable !!!');</script>");
}
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
