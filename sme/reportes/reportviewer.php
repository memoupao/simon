<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLReportes.class.php");
$objRep = new BLReportes();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="<?php echo(constant("PATH_IMG"));?>feicon.ico" type="image/x-icon">
<link href="../../css/reportes.css" type="text/css" rel="stylesheet" media="screen" />
<script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../SpryAssets/SpryData.js" type="text/javascript"></script>
<title><?php echo("Reportes - ".$objRep->title);?></title>
<script src="../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryPopupDialog.css" rel="stylesheet" type="text/css" />
<script src="../../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<script src="<?php echo(PATH_JS);?>jquery.formatCurrency-1.4.0.min.js" type="text/javascript"></script>

<script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()

var widthReport = 0;
function AjusteTamanioRPT()
{
	var size = $(window).height();
	if(size<=200){return;}
	var sizeBar = $("#divToolbar").height();
	$("#divBackContentReport").height(size-(sizeBar + 16)) ;
	$("#divBackContentReport").width(widthReport-0.1) ;
	var maxX = document.getElementById("divBackContentReport").scrollWidth - document.getElementById("divBackContentReport").clientWidth;
	if(maxX>0){	$("#divContentReport").width(widthReport+maxX);}
	//widthReport = $("#divContentReport").width();
	widthReport = $("#divToolbar").width() -30;
	//alert($("#divToolbar").width());
}

function LoadReport(url, BodyForm)
{
	$('#divBackContentReport').height(document.height);
	if(url==""){alert("No se ha especificado el Reporte"); return false;}
    var sURL = url + "?action=<?php echo(md5("ajax"));?>";
    $('#divBodyReport').html($('#divCargando').html());
    var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadReport, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoadReport });
}

function SuccessLoadReport	 (req)
{
   var respuesta = req.xhRequest.responseText;
   $("#divBodyReport").html(respuesta);
   setTimeout('AjusteTamanioRPT()',200);
   return;
}

function onErrorLoadReport(req)
{
	$("#divBodyReport").html("<b style='color:red;'>Ocurrio un error al intentar cargar el reporte..</b");
}

function SuccessLoadFilter	 (req)
{
   var respuesta = req.xhRequest.responseText;
   $("#divFilterReport").html(respuesta);
   cpFilter.open();
   return;
}

function onErrorLoadFilter(req)
{
	$("#divFilterReport").html("<b style='color:red;'>Ocurrio un error al intentar cargar los filtros del Reporte..</b");
}

function Cerrar()
{
	if(confirm("Estas Seguro de Cerrar la Pagina Actual ?")) { window.close(); }
}

function Imprimir()
{
	var printContent = document.getElementById("divContentReport");
	var windowUrl = 'about:blank';
	var uniqueName = new Date();
	var windowName = 'Print' + uniqueName.getTime();
	var printWindow = window.open(windowUrl, windowName, 'left=1,top=1,width=0,height=0');
	var varhtml = '<html><head>';
	varhtml = varhtml + '\n' + '<style type="text/css" media="print">@page port {size: portrait;} @page land {size: landscape;} .portrait {page: port;} .landscape {page: land;}</style><link href="../../css/reportes.css" type="text/css" rel="stylesheet" media="all" /></head>';
	varhtml = varhtml + '\n' + '<body class="landscape"><center>' ;
	 varhtml = varhtml + '\n' + printContent.innerHTML;
	 varhtml = varhtml + '\n' + '</center></body></html>' ;

	printWindow.document.write(varhtml);
	printWindow.document.close();
	printWindow.focus();
	printWindow.print();
	printWindow.close();
}

function showfilterReport()
{
	var urlFilter = '<?php echo($objRep->filter);?>';
	if(urlFilter==""){alert("El reporte no requiere filtros personalizados"); return;}

	cpFilter.enableAnimation =false;
	if(cpFilter.isOpen())
	{
		$('#imgFilter').attr('src',"<?php echo(constant("PATH_IMG"))?>expandir.gif") ;
		cpFilter.close();
	}
	else
	{
		$('#imgFilter').attr('src',"<?php echo(constant("PATH_IMG"))?>contraer.gif") ;
		cpFilter.open();
	}
	AjusteTamanioRPT();
	return false;
}

function ReloadReport()
{   var url    = $("#txturl").val();
	var params = $("#txtparams").val();
	var BodyForm= $("#frmFilter").serialize();
	if(params=="")
	{BodyForm = "?" + BodyForm ;}
	else
	{BodyForm = params + "&" + BodyForm ;}
	LoadReport(url, BodyForm);
	spryPopupFilter.displayPopupDialog(false);
	return;
}

function showReport(reportID, params)
{
	$("#txtparams").val( params );
	RefreshReport();
	spryPopupFilter.displayPopupDialog(false);
}

function NewReport(title, url, params)
{
	var d= new Date() ;
	var nameWindow = "w_"+d.getHours() +'_' + d.getMinutes()  + '_' + d.getSeconds();
	var urlViewer = "reportviewer.php?link="+url+"&title="+title+"&"+ params;
	var win =  window.open(urlViewer, nameWindow, "fullscreen,scrollbars");
	win.focus();
	return;
}

function NewReportID(reportID, params)
{
 var newURL = "reportviewer.php?ReportID=" + reportID + "&" + params ;
 var d= new Date() ;
 var nameWindow = "w_"+d.getHours() +'_' + d.getMinutes()  + '_' + d.getSeconds();
 var win =  window.open(newURL, nameWindow, "fullscreen,scrollbars");
     win.focus();
 return;
}

function RefreshReport()
{
	var url    = $("#txturl").val();
	var params = $("#txtparams").val();
	LoadReport(url, params);
	return;
}
function ExportReport()
{
	var idReport = $('#cboExportar').val();
	if(idReport==null || idReport=="")
	{
	  alert("No se ha especificado el Formato a Exportar");
	  return false;
	}

	var url = "";

	switch(idReport)
	{
		case '1' : url="exportXLS.php"; break;		
		case '2' : url="exportDOC.php"; break;
		<?php /* ?>
		case '3' : url="exportPDF.php"; break;
		<?php */ ?>
		case '4' : url="exportHTM.php"; break;
	}
	var xls = ""
	if($('#xlsCustom').html() != null){
		xls = $('#xlsCustom').html();
		$('#xlsCustom').remove();
	}

	if(idReport=="1" && xls!=""){
		var actual = $('#divBodyReport').html();
		$('#divBodyReport').html(xls);
	}

	var Body = $('#divContentReport').html();

	$('#txtcontents').val(Body);


	 $('#frmData').attr({action: url});
	 $('#frmData').submit();
	 $('#txtContent').val("");




	if(idReport=="1" && xls!=""){
	$('#divBodyReport').html(actual);
	// $('#xlsCustom').html(xls);
	}
}
//window.onresize=AjusteTamanioRPT;
function Maximizar()
{
/*if( $(window).width() < screen.width )
 {
  window.moveTo(0,0);
  window.resizeTo(screen.width,screen.height);
 }*/
}
</script>


</head>

<body scroll=no onload="Maximizar();" onresize="AjusteTamanioRPT();">

	<div class="toolbar" id="divToolbar">
		<div class="barra">
			<table width="100%" border="0" cellpadding="0" cellspacing="0"
				class="Subtitle">
				<tr>
					<td width="4%" align="right" nowrap="nowrap">Imprimir</td>
					<td width="4%" align="left" nowrap="nowrap">&nbsp;<img
						src="../../img/imprimir.gif" width="20" height="19"
						title="Imprimir Reporte" onclick="Imprimir();" /></td>
					<td width="4%" align="center" nowrap="nowrap">Exportar</td>
					<td width="7%" align="left" nowrap="nowrap">
						<select name="cboExportar" class="Boton" id="cboExportar" style="width: 70px;">
							<option value="" selected="selected"></option>
        <?php
        /*$rs = $objRep->ListadoExport();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', '');
        $rs = NULL;*/        	       
        ?>
        					<option value="1">Excel</option>
        					<option value="2">Word</option>
							<option value="4">HTML</option>
        				</select>
        			</td>
					<td width="2%" align="left" valign="middle" nowrap="nowrap">&nbsp;<img
						src="../../img/export.gif" width="20" height="19"
						title="Exportar Reporte" onclick="ExportReport();" /></td>
					<td width="2%" align="right" nowrap="nowrap">&nbsp;</td>
					<td width="7%" align="right" nowrap="nowrap">Filtrar Reporte</td>
					<td width="2%" align="center" nowrap="nowrap"><div id="divCargando"
							style="display: none;">
							<p align='center'>
								<img src='../../img/loading.gif' width='32' height='32' /><br>Cargando..<br>
							</p>
						</div> <img src="<?php echo(constant('PATH_IMG'));?>expandir.gif"
						name="imgFilter" width="18" height="18" id="imgFilter"
						title="Filtrar Reporte" onclick="showfilterReport();" /></td>
					<td width="1%">&nbsp;</td>
					<td width="8%" align="right">Refrescar</td>
					<td width="2%">&nbsp;<img src="../../img/btnRecuperar.gif"
						width="15" height="15" title="Actualizar los datos del reporte"
						onclick="RefreshReport();" /></td>
					<td width="9%">&nbsp;</td>
					<td width="11%"><input type="hidden" id="txturl" name="txturl"
						value="<?php echo($objRep->linkPage);?>" /> <input type="hidden"
						id="txtparams" name="txtparams"
						value="<?php echo($objRep->params);?>" /></td>
					<td width="11%" align="right">Cerrar</td>
					<td width="26%" align="left">&nbsp;<img src="../../img/close.gif"
						width="20" height="19" title="Cerrar" onclick="Cerrar();" /></td>
				</tr>
			</table>
		</div>
		<!-- Para los Filtros Personalizados -->
		<div id="divFilterPanel" class="CollapsiblePanel"
			style="background-color: #FFF; border-bottom: 1px solid #808080;">
			<div class="CollapsiblePanelTab" style="display: none;">
				<a href="#"></a>
			</div>
			<div class="CollapsiblePanelContent">
				<form name="frmFilter" id="frmFilter" method="post" action="#"
					enctype="application/x-www-form-urlencoded">
					<div id="divFilterReport"
						style="min-height: 30px; overflow: auto; background-color: #FFF;">
					</div>
				</form>
			</div>
		</div>
		<!-- -->
	</div>
	<form id="frmData" name="frmData" method="post"
		enctype="multipart/form-data" action="" target="ifrDownloadFile"
		accept-charset="UTF-8">
		<input type="text" id="txtname" name="txtname"
			value="<?php 
				if (!empty($objRep->title)) { 
					echo(str_replace(" ","", substr($objRep->title,0,30))); 
				} else {
					echo 'reporte';
				} ?>"
			style="display: none;" />
		<textarea id="txtcontents" name="txtcontents" style="display: none;"></textarea>
		<input type="hidden" name="horizontal" id="horizontal" value="<?php if ($objFunc->__Request("horizontal")==='1') echo '1';?>" />
	</form>
	<iframe id="ifrDownloadFile" name="ifrDownloadFile"
		style="display: none; width: 100%;"></iframe>

	<div class="BackContentReport" id="divBackContentReport">
		<div class="ContentReport" id="divContentReport" align="center">
			<div class="Head" style='width: 100%;'>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td nowrap="nowrap" style="width: 10%;text-align: left;">
							<img src="<?php echo(constant("PATH_IMG"));?>FE.jpg" width="140" height="79" onclick="AjusteTamanioRPT();" />
						</td>
						<td  style="width: 80%; vertical-align: middle; text-align: center; ">
							<?php echo($objRep->title);?> 
						</td>						
						<td style="font-weight: normal; width: 10%; text-align: right;">
							<div style="font-size: 10px; color: #808080; font-family: Arial, Helvetica, sans-serif; width: 120px; text-align: left;">
								<b>Fecha:</b> <?php echo($objFunc->Fecha); ?> <br /> <b>Hora:</b> <?php echo($objFunc->Hora); ?>
          					</div>
						</td>
					</tr>					
				</table>
			</div>
			<br />
			<div class="BodyReport" id="divBodyReport"></div>
		</div>

	</div>


	<div id="panelFilter" class="PopupDialog" style="visibility: hidden;">
		<div class="popupBox">
			<div class="popupBar">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%"><span id="spanTitle">Buscar Datos</span></td>
						<td align="right"><a class="popupClose" href="javascript:;"
							onclick="spryPopupFilter.displayPopupDialog(false);"><b>X</b></a></td>
					</tr>
				</table>
			</div>
			<div class="popupContent" style="background-color: #FFF;">
				<div id="popupText">
					<div id="divPopupText"></div>
				</div>
			</div>
		</div>
	</div>


	<script language="javascript">
widthReport = $("#divToolbar").width() -30;//$("#divBackContentReport").width();
AjusteTamanioRPT();
var spryPopupFilter= new Spry.Widget.PopupDialog("panelFilter", {modal:true, allowScroll:true, allowDrag:true});

var cpFilter = new Spry.Widget.CollapsiblePanel("divFilterPanel");
function VerifyFilterReport()
{
	var urlFilter = '<?php echo($objRep->filter);?>';
	if(urlFilter!='' && '<?php echo($objFunc->__Request("no_filter"));?>'=='')
	{
		$('#imgFilter').attr('src',"<?php echo(constant("PATH_IMG"))?>contraer.gif") ;
		var BodyForm = $("#txtparams").val()
		//spryPopupFilter.displayPopupDialog(true);
		$('#divFilterReport').html($('#divCargando').html());
		var req = Spry.Utils.loadURL("POST", urlFilter, true, SuccessLoadFilter, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoadFilter });
	}
	else
	{
		$('#imgFilter').attr('src',"<?php echo(constant("PATH_IMG"))?>expandir.gif") ;
		cpFilter.close();
	}
}

VerifyFilterReport();

function loadPopup(title, url)
{
	$('#divPopupText').html('<p align="center"><img src="../../img/indicator.gif" width="16" height="16" /><br>Cargando...</p>');
	$('#spanTitle').html(title);
	spryPopupFilter.displayPopupDialog(true);
	$('#divPopupText').load(url);
	return false ;
}
function ChangeStylePopup(style)
{
	$('#panelFilter').attr("class",style);
}

</script>
<?php
$objRep->ViewReport();
$objRep = NULL;
?>
</body>
</html>
