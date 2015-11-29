<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
require_once (constant('PATH_CLASS') . "BLMonitoreo.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

$objFunc->SetTitle("Autorización para Giro de Cheques para Supervisores Externos - Administracion");
$objFunc->SetSubTitle("Autorización de Giro de Supervisores a S.E.");

$objTablas = new BLTablasAux();

?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../../img/feicon.ico"
	type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="../../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryMenuBarHorizontal.css"
	rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<!-- InstanceEndEditable -->
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>
<SCRIPT src="../../../js/s3Slider.js" type=text/javascript></SCRIPT>
<!-- InstanceBeginEditable name="jQuery" -->
<script src="../../../SpryAssets/SpryPopupDialog.js"
	type="text/javascript"></script>
<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceEndEditable -->
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
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
				<fieldset style="padding: 1px; margin-top: 10px; margin-left: 6px; margin-right: 6px; width: 98.5%;">
					<legend>Filtro de Busqueda</legend>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table width="70%" border="0">
									<tr>
										<td width="80%">
											<table width="100%" border="0" cellpadding="0"
												cellspacing="0" class="TableEditReg">
												<tr>
													<td width="60" height="44" valign="middle" nowrap="nowrap"><strong>Concurso</strong></td>
													<td width="67" align="center" valign="middle"
														nowrap="nowrap"><select name="cboconcurso"
														id="cboconcurso" style="width: 60px;" class="AprobDesemb">
															<option value="*">Todos</option>
              <?php
            $rs = $objTablas->ListaConcursosActivos();
            $objFunc->llenarComboI($rs, 'codigo', 'abreviatura', "");
            ?>
            </select></td>
													<td width="20" valign="middle">&nbsp;</td>
													<td width="118" valign="middle" nowrap="nowrap"><strong>Institución
															Supervisora</strong></td>
													<td width="255" valign="middle"><select
														name="cboinstitucion" id="cboinstitucion"
														style="width: 230px;" class="AprobDesemb">
															<option value="*">Todos</option>
              <?php
            $objMoni = new BLMonitoreo();
            $rs = $objMoni->ListaInstitucionesMonitoras();
            $objFunc->llenarComboI($rs, 't01_id_inst', 't01_sig_inst', "");
            ?>
            </select></td>
												</tr>
											</table>
										</td>
										<td width="20%" align="center" valign="middle" id="toolbar">
										<span style="display:block; margin-top:10px;">
												<button name="btnRefrescar" value="Refrescar"
													class="Button" id="btnBuscar"
													onclick="LoadDataGrid(); return false;">Buscar</button>
												<button name="btnExportar" value="Exportar"
													class="Button" id="btnExportar"
													onclick="Exportar(); return false;">Exportar</button>
										</span></td>
									</tr>
								</table>



							</td>
							
						</tr>
					</table>
				</fieldset>

				<div id="divContentEdit"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
				</div>

				<br />

				<div id="divpanelPopup" class="popupContainer"
					style="visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"><span id="spnTitle"></span></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialog01.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText">
								<div id="divContenidoPopup" style="background-color: #FFF;"></div>
							</div>

						</div>
					</div>
				</div>
				<script language="JavaScript" type="text/javascript">
 var spryPopupDialog01= new Spry.Widget.PopupDialog("divpanelPopup", {modal:true, allowScroll:true, allowDrag:true});
 function ChangeStylePopup(style)
 {
	$('#divpanelPopup').attr("class",style);
 }
 function loadPopup(title, url)
	{
	$('#spnTitle').html(title);
	$('#divContenidoPopup').html('<p align="center"><img src="../../../img/indicator.gif" width="16" height="16" /><br>Cargando...</p>');
	$('#divContenidoPopup').load(url);
	spryPopupDialog01.displayPopupDialog(true);
	return false ;
	}
</script>

				<script language="JavaScript" type="text/javascript">
  var idContainerLoading = "";
  function loadUrlSpry(ContainerID, pURL)
  {
	  idContainerLoading = "#"+ContainerID;
	  $(idContainerLoading).html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
  }
  function MySuccessLoad(req)
  {
	  var respuesta = req.xhRequest.responseText;
  	  $(idContainerLoading).css('display', 'block');
	  $(idContainerLoading).html(respuesta);
  	  $(idContainerLoading).fadeIn("slow");
	  //LoadIndicadoresProposito(true);
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
  {	  alert("ERROR: " + req.xhRequest.responseText);   }
</script>

				<script language="javascript" type="text/javascript">
$("#cboconcurso option:last").attr('selected','selected');
LoadDataGrid();
function LoadDataGrid()
{
	var url = "auto_giro_chk_me_lista.php?mode=<?php echo(md5("ajax_list"));?>&cboconcurso="+$("#cboconcurso").val() + "&cboinstitucion=" + $("#cboinstitucion").val() ;
	loadUrlSpry("divContentEdit",url);

}

	function Exportar()
	{
		var aParams = ["cboConcurso=" + $("#cboconcurso").val(),
						"cboEjecutor=" + $("#cboinstitucion").val()].join('&');
		var sID = "62" ;
		showReport(sID, aParams);
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
