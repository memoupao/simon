<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainMantenimiento.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
require (constant('PATH_CLASS') . "BLMantenimiento.class.php");
$objMante = new BLMantenimiento();

$objFunc->SetTitle("Mantenimiento de Usuarios");
$objFunc->SetSubTitle("Mantenimiento de Usuarios");
$ObjSession->MostrarBotonesAccesoDirecto = false;
?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../img/feicon.ico" type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../css/template.css" rel="stylesheet" media="all" />
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet"
	type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<SCRIPT src="../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<SCRIPT src="../js/s3Slider.js" type=text/javascript></SCRIPT>
<!-- InstanceBeginEditable name="jQuery" -->
<script src="../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryPagedView.js" type="text/javascript"></script>

<script type="text/javascript">
	var dsLista = null ;
	var pvLista = null;
	var pvListaPagedInfo = null; 
	
	$("#divContent").css('display', 'none');
	function LoadDataGrid()
	{
		var perfil = $("#cboPerfil").val();
		var xmlurl = "man.xml.php?action=<?php echo(md5("lista_usuarios"));?>&perfil="+perfil ;
		
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
			dsLista = new Spry.Data.XMLDataSet(xmlurl, "usuarios/rowdata", {useCache: false});
			pvLista = new Spry.Data.PagedView(dsLista, { pageSize: 20});
			pvListaPagedInfo = pvLista.getPagingInfo();
		}
	}
	$.getScript("<?php echo(constant("PATH_JS")."editdata.js");?>");
</script>
<script src="../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
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
							style="font-size: 12px;">&nbsp;<strong>Perfil de Usuario</strong>
							&nbsp;
						</td>
						<td><select name="cboPerfil" id="cboPerfil" style="width: 240px"
							onchange="LoadDataGrid();">
								<option value="*">{Todos}</option>
      <?php
    $Perfil = $ObjSession->PerfilID;
    ;
    $rs = $objMante->ListaPerfiles();
    $objFunc->llenarComboI($rs, 'codigo', 'descripcion', $Perfil);
    $rs->free();
    ?>
    </select></td>
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
	var str = row["nom_user"];
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
								<td width="8%" nowrap="nowrap">
									<button class="Button" name="btnNuevo" id="btnNuevo"
										onclick="btnNuevo_Clic(); return false;" value="Nuevo">Nuevo
										Usuario</button>
								</td>
								<td width="13%" nowrap="nowrap"><button class="Button"
										name="btnNuevo" id="btnNuevo2"
										onclick="ChangePWD(); return false;" value="Nuevo">Cambiar
										Password</button></td>
								<td width="2%">&nbsp;</td>
								<td width="19%">&nbsp;</td>
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
						<table class="grid-table grid-width">
							<thead>
								<tr>
									<th width="48">&nbsp;</th>
									<th width="81" height="25" align="center" spry:sort="coduser">Usuario</th>
									<th width="241" align="center" spry:sort="nom_user">Nombres</th>
									<th width="159" align="center" spry:sort="mail">E-mail</th>
									<th width="102" align="center" spry:sort="perfil">Perfil</th>
									<th width="78" align="center" spry:sort="t02_cod_proy">Proyecto</th>
									<th width="79" align="center" spry:sort="estado">Estado</th>
									<th width="48">&nbsp;</th>
								</tr>
							</thead>
							<tbody class="data">
								<tr class="RowData" spry:repeat="pvLista" spry:setrow="pvLista"
									id="{@id}" spry:select="RowSelected">
									<td><span> <a href="#"><img src="../img/pencil.gif" width="14"
												height="14" title="Editar Registro" border="0"
												onclick="btnEditar_Clic('{coduser}');" /></a>
									</span></td>
									<td>{coduser}</td>
									<td>{nom_user}</td>
									<td align="left">{mail}</td>
									<td align="center">{perfil}</td>
									<td align="center">{t02_cod_proy}</td>
									<td align="center">{estado}</td>
									<td><span> <a href="#"><img src="../img/bt_elimina.gif"
												width="14" height="14" title="Eliminar Registro" border="0"
												onclick="Eliminar('{coduser}', '{nom_user}');" /></a>
									</span></td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th width="48">&nbsp;</th>
									<th width="81">&nbsp;</th>
									<th width="241">&nbsp;</th>
									<th width="159">&nbsp;</th>
									<th width="102">&nbsp;</th>
									<th colspan="3" align="right">
										<div>
											<input type="button" class="Boton"
												title="Ir a la Primera Pagina"
												onclick="pvLista.firstPage();"
												value="<<" />
            <input  type="button"
												class="Boton" title="Pagina Anterior"
												onclick="pvLista.previousPage();"
												value="<" />
            <input  type="button"
												class="Boton" title="Pagina Siguiente"
												onclick="pvLista.nextPage();" value=">" /> <input
												type="button" class="Boton" title="Ir a la Ultima Pagina"
												onclick="pvLista.lastPage();" value=">>" />
										</div>
									</th>
								</tr>
							</tfoot>
						</table>
						<p spry:state="loading" align="center" id="pLoading">
							<img src="../img/indicator.gif" width="16" height="16" /><br>
							Cargando...
						</p>
					</div>

				</div>
				<div id="divContentEdit"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
				</div>


				<script language="javascript" type="text/javascript">
 function btnNuevo_Clic()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	var url = "man_usu_edit.php?mode=<?php echo(md5("ajax_new"));?>&id=" ;
	loadUrlSpry("divContentEdit",url);
	return;

  }
 function btnEditar_Clic(idusr)
  {
	if(dsLista.getRowCount()==0)
	{
		alert("No ha seleccionado ningun registro !!!");
		return;
	}
	
	var url = "man_usu_edit.php?mode=<?php echo(md5("ajax_edit"));?>&id="+idusr ;
	loadUrlSpry("divContentEdit",url);
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');

	return;


  }

 function ReloadLista()
 {
	$("#divContentEdit").fadeOut("slow");
	$("#divContentEdit").css('display', 'none');
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	$('#cboPerfil').removeAttr('disabled'); 
	dsLista.loadData();
	
 }
 function CancelEdit()
 {
	$("#divContentEdit").fadeOut("slow");
	$("#divContent").fadeIn("slow");
	$("#divContent").css('display', 'block');
	$("#divContentEdit").css('display', 'none');
	$('#cboPerfil').removeAttr('disabled'); 
	return true;
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
		var BodyForm = "idUser="+codigo;
		var sURL = "man_usu_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
  }
  var idContainerLoading = "";
  function loadUrlSpry(ContainerID, pURL)
  {
	  idContainerLoading = "#"+ContainerID;
	  $(idContainerLoading).css('display', 'none');
	  //$('#cboPerfil').attr('disabled','disabled'); 
	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
  }
  
  function MySuccessLoad(req)
  {
	  var respuesta = req.xhRequest.responseText;
  	  $(idContainerLoading).css('display', 'block');
	  $(idContainerLoading).html(respuesta);
	  /*var htmlshowin = $("#EditForm").html();
	  $(idContainerLoading).html(htmlshowin);*/
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
  
</script>

				<script language="javascript">
  LoadDataGrid();
  </script>

				<div id="panelPWD" class="popupContainer">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">Cambiar Contraseña</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogPWD.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText"></div>

							<div id="divChangePWD"></div>

						</div>
					</div>
				</div>

				<script language="JavaScript" type="text/javascript">
  var spryPopupDialogPWD= new Spry.Widget.PopupDialog("panelPWD", {modal:true, allowScroll:true, allowDrag:true});
  
   function ChangePWD()
	{
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		var iduser = '' ;
		if(row)
		{ iduser = row.coduser; }
		
		var url = 'man_usu_pwd.php?mode=<?php echo(md5("change_pwd_mante"));?>&id='+iduser ;
		loadUrlSpry("divChangePWD", url)
		spryPopupDialogPWD.displayPopupDialog(true);
		return false ;
	}
	
  </script>


				<!-- InstanceEndEditable -->
			</form>
		</div>
		<div id="footer">
	<?php include("../includes/Footer.php"); ?>
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
