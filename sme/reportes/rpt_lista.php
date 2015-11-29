<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLReportes.class.php");
$objRep = new BLReportes();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Banco de Reportes");
$objFunc->SetSubTitle("Banco de Reportes");
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
<script src="../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>

<script type="text/javascript">
	var dsLista = null ;
	var pvLista = null;//= new Spry.Data.PagedView(dsLista, { pageSize: 10});
	var pvListaPagedInfo = null; //= pvLista.getPagingInfo();
	LoadDataGrid('');
	$("#divContent").css('display', 'none');
	function LoadDataGrid(categ)
	{
		var xmlurl = "rpt_process.xml.php?action=<?php echo(md5("lista"));?>&idCategoria="+categ ;

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
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "reportes/rowdata", {useCache: false});
			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 20});
			pvListaPagedInfo = pvLista.getPagingInfo();
		}
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

				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1%">&nbsp;</td>
						<td width="73%" rowspan="2">Aqui se muestra el banco de reportes
							manejados por el sistema.</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
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

var regExp = new RegExp(regExpStr, "i");
var filterFunc = function(ds, row, rowNumber)
{
	var str = row["titulo"];
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
						<style>
.categoria {
	background-color: #E8E8E8;
	font-size: 11px;
	font-weight: bold;
	color: #315786;
}
</style>


						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="8%">Categoria</td>
								<td><select name="cboCategoria" id="cboCategoria"
									onchange="LoadDataGrid(this.value);" style="width: 200px">
										<option value="">Todos</option>
            <?php
            $rs = $objRep->ListadoCategoriaReportes();
            $objFunc->llenarComboI($rs, 'codigo', 'descripcion', '');
            ?>
            </select></td>
								<td width="0%">&nbsp;</td>
								<td width="0%">&nbsp;</td>
								<td width="44%" align="right">
									<div>
										<span>Buscar</span> <input name="txtBuscar" type="text"
											id="txtBuscar" size="17" onkeyup="StartFilterTimer();" /> <span>Mostrar</span>
										<select name="cbopageSize" id="cbopageSize"
											onchange="pvLista.setPageSize(parseInt(this.value));"
											style="width: 60px">
											<option value="5">5 Reg.</option>
											<option value="10">10 Reg.</option>
											<option value="20" selected="selected">20 Reg.</option>
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
						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th width="26" height="23">&nbsp;</th>
									<th width="272" align="center">Titulo del Reporte</th>
									<th width="411" align="center">Descripción del Contenido</th>
									<th width="79" align="center">&nbsp;</th>
								</tr>
							</thead>

							<tbody class="data" spry:repeatchildren="pvLista"
								spry:choose="choose">
								<tr class="categoria" spry:when="'{codigo}' == ''" id="{@id}">
									<td height="22" align="center" nowrap="nowrap">&nbsp;</td>
									<td>{titulo}</td>
									<td>&nbsp;</td>
									<td align="center">&nbsp;</td>
								</tr>
								<tr class="RowData" spry:when="'{codigo}' != ''" id="{@id}">
									<td align="center" nowrap="nowrap"><span
										spry:if="'{codigo}'!=''"> <a
											href="javascript:PrepareReport('{ds_RowID}');"> <img
												src="../../img/bullet.gif" width="13" height="13"
												title="Ver Reporte" border="0" />
										</a>
									</span></td>
									<td>{titulo}</td>
									<td>{descripcion}</td>
									<td align="center">&nbsp;</td>
								</tr>
							</tbody>

							<tfoot>
								<tr>
									<th width="26">&nbsp;</th>
									<th width="272">&nbsp;</th>
									<th width="411">&nbsp;</th>
									<th width="79">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
						<p spry:state="loading" align="center" id="pLoading">
							<img src="../../img/indicator.gif" width="16" height="16" /><br>
							Cargando...
						</p>
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
				<script language="javascript" type="text/javascript">
 function PrepareReport(rowid)
  {
	var row = dsLista.getRowByID(rowid);
	if(row)
	{
		var stitulo = htmlEncode(row.titulo);
		var surlParams    = row.parametros;
		var sID    = row.codigo;

		if(row.url==''){alert("No se ha especificado la Pagina de Salida del reporte\n \""+row.titulo+"\""); return;}

		if(surlParams!='')
		{
			surlParams = surlParams + ((surlParams.indexOf('?') > 0) ? '&' : '?') + "ReportID=" + sID ;
			loadPopup(stitulo, surlParams);
		}
		else
		{
			showReport(sID,'');
		}
		return;
	}
  }

  function showReport(reportID, params)
  {
	 var newURL = "reportviewer.php?ReportID=" + reportID + "&" + params ;
	 /*
	 $('#FormData').attr({target: "_tab"});
	 $('#FormData').attr({action: newURL});
	 $('#FormData').submit();
	 $('#FormData').attr({target: "_self"});
	 */
	 var d = new Date();
	 var nameWindow = "wrpt_"+d.getHours() +'_' + d.getMinutes()  + '_' + d.getSeconds();
	 window.open(newURL,nameWindow,"fullscreen,scrollbars") ;

  }

  function loadPopup(title, url)
  {
	$('#titlePopup').html("[Reporte] "+title);
	$('#divChangePopup').html('<p align="center"><img src="<?php echo(constant("PATH_IMG"));?>indicator.gif" width="16" height="16" /><br>Cargando...</p>');
	$('#divChangePopup').load(url);
	spryPopupDialog01.displayPopupDialog(true);
	return false ;
  }

</script>


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
			var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
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
