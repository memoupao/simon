<?php
/**
 * CticServices
 *
 * Gestiona la lista de Informes
 * de Supervisión de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */

include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require(constant("PATH_CLASS")."HardCode.class.php");

$objFunc->SetTitle("V°B° Informe de Supervisión");
$objFunc->SetSubTitle("V°B° Informe de Supervisión");
$objHC = new HardCode();

switch ($objFunc->oSession->PerfilID) {
    case $objHC->GP:
        $res_editar = "'{estado}' == 'Elaboración' || '{estado}' == 'Corrección' || '{estado}' == 'Revisión' || '{estado}' == 'Corrección RA'";
        break;
    case $objHC->RA:
        $res_editar = "'{estado}' == 'V°B°' || '{estado}' == 'Revisión'";
        break;
    case $objHC->FE:
        $res_editar = "'{estado}' == 'Elaboración' || '{estado}' == 'Corrección' || '{estado}' == 'Revisión' || '{estado}' == 'Corrección RA' || '{estado}' == 'Aprobado'";
        break;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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

    	function LoadDataGrid(idProy, idVersion)
    	{
    		var xmlurl = "inf_monext_process.xml.php?action=<?php echo(md5("lista_inf_se_vb"));?>&idProy="+idProy+"&idVersion="+idVersion;

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
    			dsLista = new Spry.Data.XMLDataSet(xmlurl, "informes/rowdata", {useCache: false});
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
    		<form id="FormData" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo($_SERVER['PHP_SELF']);?>">
                <script>
                    LoadDataGrid();
                </script>
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
                                	<button class="Button" name="btnRefrescar" id="btnRefrescar" onclick="dsLista.loadData(); return false;">Refrescar</button>
                                </td>
                                <!-- <td width="28%"><button name="btnRefrescar" class="Button" id="btnExportar" onclick="ExportarInformeTriTec(); return false;">Exportar</button></td> -->
                                <td width="9%">&nbsp;</td>
                                <td width="46%" align="right">
                                	<div>
                                		<span>Mostrar</span>
                                		<select name="cbopageSize" id="cbopageSize" onchange="pvLista.setPageSize(parseInt(this.value));" style="width: 60px">
                                			<option value="5">5 Reg.</option>
                                			<option value="10" selected="selected">10 Reg.</option>
                                			<option value="20">20 Reg.</option>
                                			<option value="30">30 Reg.</option>
                                			<option value="50">50 Reg.</option>
                                		</select>
                                		<span>Ir a </span>
                                		<span spry:region="pvListaPagedInfo" style="width: 75px;">
                                			<select name="cboFilter" id="cboFilter" onchange="pvLista.goToPage(parseInt(this.value));" spry:repeatchildren="pvListaPagedInfo" style="width: 60px">
                                				<option spry:if="({ds_PageNumber} == pvLista.getCurrentPage())" value="{ds_PageNumber}" selected="selected">Pag. {ds_PageNumber}</option>
                                				<option spry:if="({ds_PageNumber} != pvLista.getCurrentPage())" value="{ds_PageNumber}">Pag. {ds_PageNumber}</option>
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
						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th height="26" nowrap="nowrap" width="30">&nbsp;</th>
									<th width="38" align="center" spry:sort="proy">Proyecto</th>
									<th width="38" align="center" spry:sort="anio">Año</th>
									<th width="35" align="center" spry:sort="entregable">Entregable</th>
									<th width="119" align="center" spry:sort="periodo">Periodo Referencia</th>
									<th width="70" align="center" spry:sort="fec_pre">Fecha Infome</th>
									<th width="60" align="center" spry:sort="estado">Estado</th>
									<th width="277" align="center">Introducción</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista" id="{@id}" spry:select="RowSelected">
									<td nowrap="nowrap">
									    <span>
                                            <a href="#">
                                                <img src="../../../img/pencil.gif" width="14" height="14" title="Editar Registro" border="0" onclick="btnEditar_Clic('{proy}','{t02_anio}','{t02_entregable}'); return false;" />
                                            </a>
                                        </span>
                                    </td>
									<td align="center" nowrap="nowrap">{proy}</td>
									<td align="center" nowrap="nowrap">{anio}</td>
									<td align="center" nowrap="nowrap">{entregable}</td>
									<td align="left">{periodo}</td>
									<td align="center">{fec_pre}</td>
									<td align="center">{estado}</td>
									<td align="left">{introduccion}</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th nowrap="nowrap" colspan="6">&nbsp;</th>
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
				<div id="divContentEdit" style="position: relative; padding-left: 5px; padding-right: 3px; border: none;"></div>
				<br />

				<input type="hidden" id="idVersion" name="idVersion" value="<?php echo $ObjSession->VerProyecto;?>"/>

				<script language="JavaScript" type="text/javascript">
                    function btnEditar_Clic(proy, anio, idEntregable)
                    {
                      	var url = "inf_monext_edit_vb.php?mode=<?php echo(md5("ajax_edit"));?>&idProy="+proy+"&anio="+anio+"&idEntregable="+idEntregable+"&idVersion="+$('#idVersion').val();
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
                    var TabsInforme = null;

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
                      TabsInforme = new Spry.Widget.TabbedPanels("ssTabInforme");
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
                    {	  alert("ERROR: " + req);   }

                    function ExportarInformeTriTec()
                	{
                    	var row = dsLista.getRowByID(dsLista.getCurrentRowID());
                    	if(row)
                    	{
                    		var arrayControls = new Array();
                    			arrayControls[0] = "idProy=" + $("#txtCodProy").val();
                    			arrayControls[1] = "idVersion=" + row.vsinf	;
                    			arrayControls[2] = "idAnio=" + row.t02_anio;
                    			arrayControls[3] = "idTrim=" + row.t25_trim;
                    			var params = arrayControls.join("&");

                    		var sID = "8" ;
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