<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Directorio de Instituciones");
$objFunc->SetSubTitle("Directorio de Instituciones");
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../img/feicon.ico"
	type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../../css/template.css" rel="stylesheet" media="all" />
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

 var idContainerLoading = "";
 
 
	var dsLista = null ;
	var pvLista = null;//= new Spry.Data.PagedView(dsLista, { pageSize: 10});
	var pvListaPagedInfo = null; //= pvLista.getPagingInfo();
	LoadDataGrid();

	$("#divContent").css('display', 'none');
	
	function LoadDataGrid()
	{
		dsLista = new Spry.Data.XMLDataSet("process.xml.php?action=<?php echo(md5("lista"));?>", "ejecutor/rowdata", {sortOnLoad: "t01_sig_inst", sortOrderOnLoad: "ascending", useCache: false});
		dsLista.setColumnType("t01_fch_fund", "date");
		dsLista.setColumnType("t01_pres_anio","number");
		
		pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 10});
		pvListaPagedInfo = pvLista.getPagingInfo();
	}
	
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
	
	
	
	function ExportarInstitucion()
	{
	
	var row = dsLista.getRowByID(dsLista.getCurrentRowID());
	if(row)
	{ 	var like = document.getElementById("txtBuscar");
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + row.t01_id_inst; 
			if(like.value!=""){
				arrayControls[1] = "like=" + like.value; 
			}
		var params = arrayControls.join("&"); 
		var sID = "5" ;
 
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
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
<link href="../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />

<style type="text/css">
<!--
#apDiv1 {
	position: absolute;
	left: 429px;
	top: 342px;
	width: 38px;
	height: 25px;
	z-index: 2;
}
-->
</style>
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
						<td width="73%">Aqui se muestra el listado de las Instituciones
							Ejecutoras de los proyectos de Fondoempleo</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td><b style="text-decoration: underline"> </b> Es un
							mantenimiento basico, donde se le permite agregar modificar y
							eliminar los datos de los ejecutores</td>
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
//regExpStr = "^" + regExpStr;

var regExp = new RegExp(regExpStr, "i");
var filterFunc = function(ds, row, rowNumber)
{	
	var str = row["t01_nom_inst"]+"|"+row["t01_sig_inst"]+"|"+row["t01_tipo_inst"]; // Busqueda Multiple
	 if (str && str.search(regExp) != -1){
		return row;
		}
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
								<td> 
        <?php if($ObjSession->AuthorizedOpcion("NUEVO")) { ?>
        <button class="Button" name="btnNuevo" id="btnNuevo"
										onclick="btnNuevo_Clic(); return false;" value="Nuevo">Nuevo</button> &nbsp;         
         <?php } ?>
        <button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Refrescar&nbsp;</button>&nbsp;
									<button id="exportar" class="Button"
										onclick="ExportarInstitucion(); return false;"
										value="Exportar">Exportar</button>
								</td>
								<td width="3%">&nbsp;</td>
								<td width="44%" align="right">
									<div>
										<span>Buscar</span> <input name="txtBuscar" type="text"
											id="txtBuscar" size="17" onkeyup="StartFilterTimer();" /> <span>Mostrar</span>
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
						<p spry:state="loading" align="center" id="pLoading">
							<img src="../../img/indicator.gif" width="16" height="16" /><br>Cargando...
						</p>
						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th width="24" spry:sort="t01_id_inst">&nbsp;</th>
									<th width="133" align="center" spry:sort="t01_sig_inst">SIGLAS</th>
									<th width="222" align="center" spry:sort="t01_nom_inst">NOMBRES
										DE LA INSTITUCION</th>
									<th width="78" align="center" spry:sort="t01_nom_inst">RUC</th>
									<th width="83" align="center" spry:sort="t01_fch_fund">FEC.
										FUNDACION</th>
									<th width="87" align="center" spry:sort="presup">Presup Anual</th>
									<th width="137" align="center" spry:sort="t01_tipo_inst">Tipo
										Inst.</th>
									<th width="24" align="center" spry:sort="t01_tipo_inst">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData"
									ondblclick="btnEditar_Clic('{t01_id_inst}','<?php echo(md5("ver"));?>');"
									spry:repeat="pvLista" spry:setrow="pvLista" id="{@id}"
									spry:select="RowSelected"
									title="Doble Click para ver el contenido">
									<td nowrap="nowrap"><span>
           <?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
            <img src="../../img/pencil.gif" width="14" height="14"
											title="Editar Registro" border="0"
											onclick="btnEditar_Clic('{t01_id_inst}', '<?php echo(md5("editar"));?>');"
											style="cursor: pointer" />
            <?php } ?>
            
            <?php if($ObjSession->AuthorizedOpcion("VER")) { ?>
            <img src="../../img/bullet.gif" width="12" height="12"
											title="Ver Registro" border="0"
											onclick="btnEditar_Clic('{t01_id_inst}',  '<?php echo(md5("ver"));?>');"
											style="cursor: pointer" />
            <?php } ?>
           </span></td>

									<td><span spry:if="'{t01_web_inst}'!=''"> <a
											href="{t01_web_inst}" target="_blank">{t01_sig_inst}</a>
									</span> <span spry:if="'{t01_web_inst}'==''"> {t01_sig_inst} </span>
									</td>
									<td>{t01_nom_inst}</td>
									<td align="center">{t01_ruc_inst}</td>
									<td align="center">{t01_fch_fund}</td>
									<td align="right">{presup}</td>
									<td>{t01_tipo_inst}</td>
									<td>
        <?php if($ObjSession->AuthorizedOpcion("ELIMINAR")) { ?>
        <img src="../../img/bt_elimina.gif" width="14" height="14"
										title="Eliminar Registro" border="0"
										onclick="Eliminar('{t01_id_inst}', '{t01_nom_inst}');"
										style="cursor: pointer" />
									</td>
        <?php } ?>
      </tr>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="4" align="left">&nbsp; <font color="#FFFFFF">
											Numero de Instituciones: &nbsp; <span
											spry:if="'{ds_PageTotalItemCount}'<=0">0</span> <span
											spry:if="'{ds_PageTotalItemCount}'>0">{ds_PageTotalItemCount}</span>
									</font>
									</th>
									<th align="right">&nbsp;</th>
									<th colspan="3" align="right"><input type="button"
										class="Boton" title="Ir a la Primera Pagina"
										onclick="pvLista.firstPage();"
										value="<<" />
          <input  type="button" class="Boton"
										title="Pagina Anterior" onclick="pvLista.previousPage();"
										value="<" />
          <input  type="button" class="Boton"
										title="Pagina Siguiente" onclick="pvLista.nextPage();"
										value=">" /> <input type="button" class="Boton"
										title="Ir a la Ultima Pagina" onclick="pvLista.lastPage();"
										value=">>" /></th>
								</tr>
							</tfoot>
						</table>
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

						</div>
					</div>
				</div>

				<script language="javascript" type="text/javascript">
var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";

function loadPopup(title, url)
{
	$('#titlePopup').html(title);
	$('#divChangePopup').html(htmlLoading);
	$('#divChangePopup').load(url);
	spryPopupDialog01.displayPopupDialog(true);
	return false ;
}

 function btnNuevo_Clic()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "ejec_edit.php?mode=<?php echo(md5("ajax_new"));?>&id=" ;
	loadUrlSpry("divContentEdit",url);
	return;

  }
 function btnEditar_Clic(idnst, accion)
  {
	if(idnst=='')
	{
		alert("No ha seleccionado ningun registro !!!");
		return;
	}
	
	var url = "ejec_edit.php?mode=<?php echo(md5("ajax_edit"));?>&id="+idnst+"&accion="+accion ;	
	loadUrlSpry("divContentEdit",url);
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	
	$("#divContent2").css('display', 'none');

	return;

  }

 function ReloadLista()
 {
	$("#divContentEdit").fadeOut("slow");
	$("#divContentEdit").css('display', 'none');
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	dsLista.loadData();
 }

 	  
  function Eliminar(codigo,Descripcion)
  {
	<?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>	
	if(dsLista.getRowCount()==0)
	{
		alert("No hay Registros para eliminar");
		return;
	}
	if(confirm("Estas seguro de eliminar el Registro \n" + Descripcion))
	{
<?php
// -------------------------------------------------->
// DA 2.0 [21-11-2013 14:04]
// Uso del parametro que se recibe en la funcion 
// y no de la seleccion que realice en la fila (row)	
		/*var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ id = row.t01_id_inst; }
		if(id<0) {alert("Selecione un Registro !!!");return false;}*/
// --------------------------------------------------<
?>
		if(codigo) {
			var BodyForm = "id="+codigo;
			var sURL = "process.php?action=<?php echo(md5("ajax_del"))?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
		} else {
			alert("Por favor seleccione correctamente el item que desea eliminar.");return false;
		}	
		  
		

	}
  }
 
  function loadUrlSpry(ContainerID, pURL)
  {
	  idContainerLoading = "#"+ContainerID;
	  //$(idContainerLoading).css('display', 'none');
	  $(idContainerLoading).html('<p align="center"><img src="<?php echo(constant("PATH_IMG"))?>indicator.gif" width="16" height="16" /><br>Cargando...</p>');
	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
  }
  
  function MySuccessLoad(req)
  {
	  var respuesta = req.xhRequest.responseText;
  	  $(idContainerLoading).css('display', 'block');
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
  {	  alert("ERROR: " + req);   }
  
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
