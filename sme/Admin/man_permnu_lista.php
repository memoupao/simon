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

$objFunc->SetTitle("Restricciones por Perfil de Usuario");
$objFunc->SetSubTitle("Restricciones por Perfil de Usuario");
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
      <?php echo(substr($ObjSession->UserName,0,25));?>
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
							onchange="LoadListaMenu();" class="MenusPerfil">
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
				<div id="toolbar" style="height: 4px;" class="BackColor">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="23%" nowrap="nowrap">
								<button class="Button" name="btnGuardar" id="btnGuardar"
									onclick="btnGuardar_Clic(); return false;"
									value="Guardar Cambios">Guardar Cambios</button>
							</td>
							<td width="2%" nowrap="nowrap">&nbsp;</td>
							<td width="13%"><button class="Button" name="btnGuardar"
									id="btnGuardar2" onclick="CheckAll(true); return false;"
									value="Marcar Todos">Marcar Todos</button></td>
							<td width="24%"><button class="Button" name="btnGuardar"
									id="btnGuardar3" onclick="CheckAll(false); return false;"
									value="Desmarcar Todos">Desmarcar Todos</button></td>
							<td width="38%" align="right"></td>
						</tr>
					</table>
				</div>

				<div id="divContent"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
					<div id="divLoading">
						<p align="center" id="pLoading">
							<img src="../img/indicator.gif" width="16" height="16" /><br>
							Cargando...
						</p>
					</div>

				</div>
				<div id="divContentEdit"
					style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
				</div>


				<script language="javascript" type="text/javascript">
 function LoadListaMenu()
  {
	$("#divContent").fadeOut("slow");
	$("#divContent").css('display', 'none');
	$('#btnGuardar').attr('disabled','disabled'); 
	var url = "man_permnu_edit.php?mode=<?php echo(md5("ajax_new"));?>&id=&idPerfil=" + $('#cboPerfil').val() ;
	loadUrlSpry("divContentEdit",url);
	return;

  }
  
 
  function btnGuardar_Clic()
	{
	 <?php $ObjSession->AuthorizedPage(); ?>	
	 if($("#divMenusPerfil").html()=="")
	 {
		 alert("No hya Datos para grabar !!!"); 
		 return false ;
	 }
	 
	 var BodyForm = $("#FormData .MenusPerfil").serialize() ;
	 var sURL = "man_permnu_process.php?action=<?php echo(md5("guardar_accesos"));?>"
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
	
	return false;
	
	}
	
	
function CheckAll(arg)
{ 
	$("input[type='checkbox'].MenusPerfil").attr('checked',arg) ;
	if(arg)
	{
		$("input[type='checkbox'].MenusPerfil").removeAttr('disabled') ;
	}
	else
	{
		$("input[type='checkbox'].MenusPerfil").attr('disabled','disabled') ;
		$("input[menu='1'].MenusPerfil").removeAttr('disabled') ;
	}
}

  
  var idContainerLoading = "";
  function loadUrlSpry(ContainerID, pURL)
  {
	  idContainerLoading = "#"+ContainerID;
	  //$(idContainerLoading).css('display', 'none');
	  $('#btnGuardar').removeAttr('disabled'); 
	  $(idContainerLoading).html($('#divLoading').html());
	  
	  var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
  }
  
  function MySuccessLoad(req)
  {
	  var respuesta = req.xhRequest.responseText;
  	  $(idContainerLoading).css('display', 'block');
	  $(idContainerLoading).html(respuesta);
	  $(idContainerLoading).fadeIn("slow");
 	  return;
  }
  
  function MySuccessCall(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	
		alert(respuesta.replace(ret,"")); 
		LoadListaMenu();
		// dsLista.loadData();	
	}
	else
	{alert(respuesta);}  
  }
  
  function MyErrorCall(req)
  {	  alert("ERROR: " + req.xhRequest.responseText);   }
  
</script>

				<script language="javascript">
  LoadListaMenu();
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
