<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php require_once(constant("PATH_CLASS")."HardCode.class.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<?php

$objHC = new HardCode();

$objFunc->SetTitle("V°B° Plan Operativo - Especificación Técnica - Proyectos");
$objFunc->SetSubTitle("V°B° Plan Operativo - Especificación Técnica");

switch ($ObjSession->PerfilID) {
    case $objHC->SP:
    case $objHC->RA:
        $res_editar = "'{estado}' == 'V°B°' || '{estado}' == 'Revisión' || '{estado}' == 'Aprobado'";
        break;
    case $objHC->GP:
        $res_editar = "'{estado}' == 'Elaboración' || '{estado}' == 'Corrección' || '{estado}' == 'Revisión'";
        break;
    case $objHC->FE:
        $res_editar = "'{estado_mf}' == 'Elaboración' || '{estado_mf}' == 'Corrección' || '{estado_mf}' == 'V°B°' || '{estado_mf}' == 'Revision'";
        break;
}

?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link rel="shortcut icon" href="../../../img/feicon.ico" type="image/x-icon">
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPagedView.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
<script src="../../../js/s3Slider.js" type=text/javascript></script>
<script src="../../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
<script type="text/javascript">
var dsLista = null ;
var pvLista = null;
var pvListaPagedInfo = null;

$("#divContent").css('display', 'none');

function LoadDataGrid()
{
	var xmlurl = "poa.xml.php?action=<?php echo(md5("lista_poa_tec_vb"));?>";
	if(dsLista!=null)
	{
		if(dsLista.url != xmlurl)
		{
			dsLista.setURL(xmlurl);
			dsLista.loadData();
		}
		else {dsLista.loadData();}
		checkNewButtonVisible();
	}
	else
	{
		dsLista = new Spry.Data.XMLDataSet(xmlurl, "poa/rowdata", {useCache: false});
		pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});
		pvListaPagedInfo = pvLista.getPagingInfo();
	}
}
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
</script>

<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet" type="text/css" />
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
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
    <?php include("../../../includes/subtitle.php");?>
    </div>
		<div class="AccesosDirecto">
        <?php include("../../../includes/accesodirecto.php"); ?>
    </div>
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">

				<div id="divContent" style="font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
					<br />
                		<?php
                            $objFunc->verifyAjax();
                            if ($objFunc->Ajax) {
                                ob_clean();
                                ob_start();
                            }
                        ?>
                       <div id="toolbar" style="height: 4px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="10%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Refrescar</button>
								</td>
								<!-- <td width="28%"><button name="btnRefrescar" value="Refrescar" class="Button" id="btnExportar" onclick="ExportarPoa(); return false;">Exportar</button></td> -->
								<td width="9%">&nbsp;</td>
								<td width="46%" align="right">
									<div>
										<span>Mostrar</span> <select name="cbopageSize"
											id="cbopageSize"
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

					<input id='t02_estado_proy' type='hidden' value='<?php echo $row['t02_estado']; ?>' />
					<div class="TableGrid" spry:region="pvLista">
						<div spry:state="loading" align="center">
							<img src="../../../img/indicator.gif" width="16" height="16" />
						</div>
						<table class="grid-table grid-width" id='poaListTbl'>
							<thead>
								<tr>
									<th width="20" height="26">&nbsp;</th>
									<th width="40" align="center" spry:sort="proy">Proyecto</th>
									<th width="40" align="center" spry:sort="anio">Año</th>
									<th width="200" align="center" spry:sort="periodo">Periodo Referencia</th>
									<th width="60" align="center" spry:sort="estado">Estado</th>
									<th width="40" align="center" spry:sort="vb_se">V°B° SE</th>
									<th align="center" spry:sort="presupuesto">Presupuesto Anual</th>
									<th width="400" align="center" spry:sort="punto_atencion">Puntos de Atención</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected">
									<td width="20" nowrap="nowrap"><span>
                                    <a href="#"><img src="../../../img/pencil.gif" width="14" height="14"
												title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{proy}', '{t02_anio}'); return false; " /></a>
                                     </span></td>
                                    <td align="center">{proy}</td>
									<td align="center">{anio}</td>
									<td align="center">{periodo}</td>
									<td align="center">{estado}</td>
									<td align="center">{vb_se}</td>
									<td align="right">{presupuesto}</td>
									<td align="left">{punto_atencion}</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="6">&nbsp;</th>
									<th colspan="2" align="right"><input type="button"
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

				</div>

				<div id="divContentEdit"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
				</div>

				<br />

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
							<!--<iframe class="Iframe" id="iAgregarPOA" name="iAgregarPOA" scrolling="no" style="width:99%; height:380px;"></iframe>
           -->
						</div>
					</div>
				</div>

				<script>
            	LoadDataGrid();
                var spryPopupDialog01 = new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});

                function AgregarItemPOA(cod, idProy, idVersion, idComp)
                {

                	var Url = "about:blank";
                	var params = "";

                    switch(cod)
                	{	case '0'	: 	; return false;
                		case '1'	: 	params = "idProy="+idProy+"&idVersion="+idVersion; Url = "../planifica/ml_def_OE.php?" + params ; break;
                		case '2'	: 	params = "idProy="+idProy+"&idVersion="+idVersion +"&t08_cod_comp="+idComp; Url = "../planifica/ml_act_OE.php?" + params ; break;
                	}

                	$('#iAgregarPOA').attr('src',Url);
                	spryPopupDialog01.displayPopupDialog(true);

                	$("#cboAgregarItem").val(0);

                	return true;
                }

                function btnNuevo_Sub(idProy,idVersion, idComponente, idActividad)
                {
                    if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto !!!"); return false;}
                    if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}

                    var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv=";
                    var url = "<?php echo (constant('PATH_SME').'proyectos/poa/');?>poa_sact_editar.php?action=<?php echo(md5("new"));?>" + params ;
                    $('#iAgregarPOA').attr('src',url);
                    spryPopupDialog01.displayPopupDialog(true);
                    return true;
                }

                function btnEditar_Sub(idProy,idVersion, idComponente, idActividad, idSAct)
                {
                    if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto !!!"); return false;}
                    if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}
                    if( idSAct == '' || idSAct==null) {alert("Seleccione una Actividad !!!"); return false;}

                    var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct;
                    var url = "<?php echo (constant('PATH_SME').'proyectos/poa/');?>poa_sact_editar.php?action=<?php echo(md5("edit"));?>" + params ;
                    $('#iAgregarPOA').attr('src',url);
                    spryPopupDialog01.displayPopupDialog(true);
                    return true;
                }

                function btnEliminar_Sub(idProy,idVersion, idComponente,idActividad, idSAct, Subact)
                {
                    <?php $ObjSession->AuthorizedPage(); ?>

                    if(!confirm("¿ Estas Seguro de Eliminar el Registro seleccionado \n\"" + Subact + "\"?")){return false ;}

                    var params = "&t02_cod_proy="+idProy+"&t02_version="+idVersion+"&t08_cod_comp="+idComponente+"&t09_cod_act="+idActividad+"&t09_cod_sub="+idSAct;
                    var url = "<?php echo (constant('PATH_SME').'proyectos/poa/');?>poa_sact_editar.php?proc=<?php echo(md5("del"));?>" + params ;
                    $('#iAgregarPOA').attr('src',url);
                    return true;
                }

                function btnCancel_Clic()
                {
                  spryPopupDialog01.displayPopupDialog(false);
                  return true;
                }

                function btnSuccessSubAct(idProy,idVersion, idComponente, idActividad)
                {	  //spryPopupDialog01.displayPopupDialog(false);

                  AgregarPOA('3',idProy,idVersion, idComponente, idActividad);
                  return true;
                }

                function loadPopup(title, url)
                {
                    $('#titlePopup').html(title);
                    $('#divChangePopup').html('<p align="center"><img src="../../../img/indicator.gif" width="16" height="16" /><br>Cargando...</p>');
                    $('#divChangePopup').load(url);
                    spryPopupDialog01.displayPopupDialog(true);
                    return false ;
                }

                function btnEditar_Clic(proy, anio)
                {
                    var url = "poa_tec_edit_vb.php?mode=<?php echo(md5("ajax_edit"));?>&idProy="+proy+"&idAnio="+anio;
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
                 function CancelEdit ()
                 {
                	$("#divContentEdit").fadeOut("slow");
                	$("#divContent").fadeIn("slow");
                	$("#divContent").css('display', 'block');
                	$("#divContentEdit").css('display', 'none');
                	return true;
                 }

                  var idContainerLoading = "";
                  var TabsPOA = null;

                  function loadUrlSpry(ContainerID, pURL)
                  {
                	  idContainerLoading = "#"+ContainerID;
                	  $(idContainerLoading).html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
                	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
                  }

                  function MySuccessLoad(req)
                  {
                	  var respuesta = req.xhRequest.responseText;
                  	  $(idContainerLoading).css('display', 'block');
                	  $(idContainerLoading).html(respuesta);
                	  TabsPOA = new Spry.Widget.TabbedPanels("ssTabPOA");
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

                  function Buscarproyectos()
                  {
                	  spryPopupDialogBuqueda.displayPopupDialog(true);
                  }

                function ExportarPoa()
                {
                	var row = dsLista.getRowByID(dsLista.getCurrentRowID());
                	if(row)
                	{
                		var arrayControls = new Array();
                			arrayControls[0] = "idProy=" + $("#txtCodProy").attr("value");
                			arrayControls[1] = "idVersion=" + row.version;//$("#txtidversion").attr("value");
                			arrayControls[2] = "idAnio=" + row.t02_anio;
                		var params = arrayControls.join("&");

                		var sID = "24" ;
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
    </script>
</body>
</html>