<?php
/**
 * CticServices
 *
 * Código reutilizable incuido en la cabecera para el mantenimiento de conceptos.
 *
 * @package	Admin/general
 * @author	AQ
 * @since	Version 2.0
 *
 */
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");
$objMante = new BLMantenimiento();

$objFunc->SetSubTitle($subTitle);
$ObjSession->MostrarBotonesAccesoDirecto = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainMantenimiento.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../img/feicon.ico" type="image/x-icon">
<title><?php echo($objFunc->SubTitle);?></title>
<link href="../css/template.css" rel="stylesheet" media="all" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<script src="../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../js/s3Slider.js" type="text/javascript"></script>
<!-- InstanceBeginEditable name="jQuery" -->
<script src="../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryPagedView.js" type="text/javascript"></script>
<script src="../js/admin.js" type="text/javascript"></script>

<script type="text/javascript">
	var dsLista = null ;
	var pvLista = null;
	var pvListaPagedInfo = null;

	$("#divContent").css('display', 'none');
	function LoadDataGrid()
	{
		var xmlurl = "man.xml.php?action=<?php echo(md5($concepto));?>";

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
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "<?php echo($concepto);?>/rowdata", {useCache: false});
			dsLista.setColumnType("codigo","number");
			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 15});
			pvListaPagedInfo = pvLista.getPagingInfo();
		}
	}
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
</script>
<script src="../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />

<!-- InstanceEndEditable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#slider').s3Slider({
            timeOut: 4500
        });
    });
</script>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="banner">
      <?php include("../includes/Banner.php"); ?>
  </div>
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
        <?php include("../includes/MenuBarAdmin.php"); ?>
      </ul>
		</div>
		<script type='text/javascript'>
        var MenuBar1 = new Spry.Widget.MenuBar('MenuBar1');
     </script>

		<div class="Subtitle">
    <?php include("../includes/subtitle.php");?>
    </div>
		<div class="AccesosDirecto">
        <?php include("../includes/accesodirecto.php"); ?>
    </div>

		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="Contenidos" -->


				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="14%">&nbsp;</td>
						<td width="86%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18" align="center" nowrap="nowrap"
							style="font-size: 12px;">&nbsp;&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<div id="divContent"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
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
		var str = row["nombre"];
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
								<td width="10%" nowrap="nowrap">
									<button class="Button" name="btnNuevo" id="btnNuevo"
										onclick="btnNuevo_Clic(); return false;" value="Nuevo">Nuevo</button>
								</td>
								<td width="10%" nowrap="nowrap"><button class="Button"
										name="btnNuevo" id="btnNuevo2"
										onclick="LoadDataGrid(); return false;" value="Nuevo">Refrescar</button></td>
								<td width="5%">&nbsp;</td>
								<td width="10%">&nbsp;</td>
								<td width="58%" align="right">
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
						<table class="grid-table grid-width center">
							<thead>