<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Equipo Fondoempleo");
$objFunc->SetSubTitle("Equipo Fondoempleo");
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

<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<script type="text/javascript">
	var dsLista = null ;
	var pvLista = null;//= new Spry.Data.PagedView(dsLista, { pageSize: 10});
	var pvListaPagedInfo = null; //= pvLista.getPagingInfo();
	LoadDataGrid();
	$("#divContent").css('display', 'none');
	function LoadDataGrid()
	{
		dsLista = new Spry.Data.XMLDataSet("process.xml.php?action=<?php echo(md5("lista"));?>", "equipofe/rowdata", {sortOnLoad: "nombres", sortOrderOnLoad: "ascending", useCache: false});
		pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 20});
		pvListaPagedInfo = pvLista.getPagingInfo();
	}
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
</script>
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet"
	type="text/css" />
<link href="../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />
<SCRIPT src="../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></SCRIPT>
<script src="../../js/commons.js" type="text/javascript"></script>
<!-- InstanceEndEditable -->
<?php /* ?>
<SCRIPT type=text/javascript>
    $(document).ready(function() {
        $('#slider').s3Slider({
            timeOut: 4500
        });
    });
</SCRIPT>
<?php */ ?>
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
						<td width="73%">&nbsp;</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td>Banco de Personas a cargo de Fondoempleo que supervisará los
							Proyectos</td>
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
	var str = row["nombres"];
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
								<?php if($ObjSession->AuthorizedOpcion("NUEVO")) { ?>
								<td width="8%">
									<button class="Button" name="btnNuevo" id="btnNuevo"
										onclick="btnNuevo_Clic(); return false;" value="Nuevo">Nuevo</button>
								</td>
								<?php } ?>
								<td width="8%">
									<button class="Button" name="btnRefrescar" id="btnRefrescar"
										onclick="dsLista.loadData(); return false;" value="Refrescar">
										Actualizar</button>
								</td>

								<td width="37%"><button id="exportar" class="Button"
										onclick="ExportarEFE(); return false;" value="Exportar">Exportar</button></td>

								<td width="2%">&nbsp;</td>
								<td width="43%" align="right">
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
						<p spry:state="loading" align="center" id="pLoading">
							<img src="../../img/indicator.gif" width="16" height="16" /><br>Cargando...
						</p>
						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th width="44" height="23" spry:sort="codigo">&nbsp;</th>
									<th width="66" align="center" spry:sort="dni">DNI</th>
									<th width="220" align="center" spry:sort="nombres">Apellidos y
										Nombres</th>
									<th width="161" align="center" spry:sort="mail">E-mail</th>
									<th width="115" align="center" spry:sort="cargo">Cargo</th>
									<th width="182" align="center" spry:sort="funcion">Funciones
										Principales</th>
									<th width="44" height="23" spry:sort="codigo">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected">
									<td nowrap="nowrap"><span>
										<?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?> 
										<a href="#"><img
												src="../../img/pencil.gif" width="14" height="14"
												title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{codigo}');" />
										</a>
										<?php } ?>
									</span></td>
									<td>{dni}</td>
									<td>{nombres}</td>
									<td align="center">{mail}</td>
									<td align="center">{cargo}</td>
									<td align="left">{funcion}</td>
									<td nowrap="nowrap"><span>
										<?php if($ObjSession->AuthorizedOpcion("ELIMINAR")) { ?> 
										<a href="#"><img
												src="../../img/bt_elimina.gif" width="14" height="14"
												title="Eliminar Registro" border="0"
												onclick="Eliminar('{codigo}', '{nombres}');" />
										</a>
										<?php } ?>
									</span></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th width="44">&nbsp;</th>
									<th width="66">&nbsp;</th>
									<th width="220">&nbsp;</th>
									<th width="161">&nbsp;</th>
									<th width="115">&nbsp;</th>
									<th width="182">&nbsp;</th>
									<th width="44">&nbsp;</th>
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
				<script language="javascript" type="text/javascript">
 function btnNuevo_Clic()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "equipo_edit.php?mode=<?php echo(md5("ajax_new"));?>&id=" ;
	//url = url + " #divContent";
	loadUrlSpry("divContentEdit",url);
	return;
	
	//$("#divContentEdit").load(url);
	//$("#divContentEdit").fadeIn("slow");
	//$("#divContentEdit").css('display', 'block');
  }
 function btnEditar_Clic(idnst)
  {
	if(dsLista.getRowCount()==0)
	{
		alert("No ha seleccionado ningun registro !!!");
		return;
	}
	if(idnst=="")
	{
		var rowid = dsLista.getCurrentRowID()
		var row = dsLista.getRowByID(rowid);
		if(row)
		{ id = row.codigo; }
	}
	else
	{
		id = idnst;
	}
	
	
	if(id<0) {alert("Selecione un Registro !!!");return false;}

	var url = "equipo_edit.php?mode=<?php echo(md5("ajax_edit"));?>&id="+id ;
	loadUrlSpry("divContentEdit",url);
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');

	return;
	
/*	url = url + " #EditForm";
	$("#divContentEdit").load(url);
	$("#divContentEdit").fadeIn("slow");
	$("#divContentEdit").css('display', 'block');*/

  }

 function ReloadLista()
 {
	$("#divContentEdit").fadeOut("slow");
	$("#divContentEdit").css('display', 'none');
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	dsLista.loadData();
 }
 function CancelEdit()
 {
	$("#divContentEdit").fadeOut("slow");
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	$("#divContentEdit").css('display', 'none');
	return true;
 }
 
 function getAlerText(pText)
 {
 	return $("<div></div>").html(pText).text();
 }
 
 function btnGuardar_Clic()
	{
	if( $('#t90_dni_equi').val()=="" ) {alert(getAlerText("Ingrese DNI de la Persona")); $('#t90_dni_equi').focus(); return false;}	
	if( isNaN($('#t90_dni_equi').val().trim()) ) {alert(getAlerText("DNI debe ser de sólo dígitos.")); $('#t90_dni_equi').focus(); return false;}	
	if( $('#t90_dni_equi').val().length != 8 ) {alert(getAlerText("Número de DNI debe ser de 8 dígitos.")); $('#t90_dni_equi').focus(); return false;}	
	if( $('#t90_ape_pat').val()=="" ) {alert(getAlerText("Ingrese Apellidos")); $('#t90_ape_pat').focus(); return false;}	
	if( $('#t90_nom_equi').val()=="" ) {alert(getAlerText("Ingrese Nombres")); $('#t90_nom_equi').focus(); return false;}	
	if( $('#t90_unid_fe').val()=="" ) {alert(getAlerText("Seleccione Unidad de FE")); $('#t90_unid_fe').focus(); return false;}	
	if( $('#t90_carg_equi').val()=="" ) {alert(getAlerText("Seleccione Cargo")); $('#t90_carg_equi').focus(); return false;}	
	if ( $('#t90_mail_equi').val()=="" ) { alert(getAlerText("Ingrese el Email.")); $('#t90_mail_equi').focus(); return false; }
	if ( $('#t90_cali_equi').val()=="" ) { alert(getAlerText("Ingrese la Calificación.")); $('#t90_cali_equi').focus(); return false; }
	if ( $('#t90_edad_equi').val()=="" ) { alert(getAlerText("Ingrese la Edad.")); $('#t90_edad_equi').focus(); return false; }
	if( isNaN($('#t90_edad_equi').val().trim()) ) {alert(getAlerText("La Edad debe ser sólo dígitos.")); $('#t90_edad_equi').focus(); return false;}
	
		
	 var BodyForm = $("#FormData").serialize() ;
	 var sURL = $('#txturlsave').val();
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	return false;
	}
 	  
  function Eliminar(codigo,Descripcion)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	if(dsLista.getRowCount()==0)
	{
		alert("No hay Registros para eliminar");
		return;
	}
	if(confirm("Estas seguro de eliminar el Registro \n" + Descripcion))
	{
		var BodyForm = "id="+codigo;
		var sURL = "process_equi.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
  }
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
  
  function MySuccessCall(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	console.log(ret);
	if(ret=="Exito")
	{ 	
		dsLista.loadData();
		alert(respuesta.replace(ret,"")); 		
		CancelEdit();	
	}
	else
	{alert(respuesta);}  
  }
  
  function MyErrorCall(req)
  {	  alert("ERROR: " + req);   }
  
  function ExportarEFE()
	{
	
	 var row =  dsLista.getRowByID(dsLista.getCurrentRowID());
	if(row)
	 { 	
	 //var like = //document.getElementById("txtBuscar");
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + row.t01_id_inst; 
			// if(like.value!=""){
				// arrayControls[1] = "like=" + like.value; 
			// }
		var params = arrayControls.join("&"); 
		var sID = "30" ;
 
		showReport(sID, params);
	}
	else
	{ alert("No se ha seleccionado ningun Proyecto."); return;}
}
  function showReport(reportID, params)
  {
 
	 var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID;
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
