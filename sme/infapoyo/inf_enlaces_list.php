<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Información de Apoyo");
$objFunc->SetSubTitle("Enlaces de Interes");
require_once (constant("PATH_CLASS") . "BLApoyo.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');
$cod = $objFunc->__GET('cod');
?>
<script src="../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />

<script language="javascript" type="text/javascript">
		function AnadirPagina()
		  {
			  spryPopupDialogPagina.displayPopupDialog(true);
		  }
	</script>
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

				<div>

					<table width="800" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="155" height="232" valign="top">
								<table width="150" height="228" border="0" class="RowAlternate">
									<tr>
										<td width="140" height="30"><span id="toolbar"
											style="width: 70px; height: 8px;">
												<button class="Button" name="busqueda" id="busqueda"
													onclick="BuscarPagina(); return false;" value="Buscar">
													Buscar</button>
										</span></td>
									</tr>
									<tr>
										<td height="30"><input type="text" name="btitulo" id="btitulo" /></td>
									</tr>
									<tr>

									</tr>
									<tr>
										<td height="30" align="center"><p>
												<span id="toolbar" style="width: 70px; height: 8px;">
													<button class="Button" name="anadir" id="anadir"
														onclick="AnadirPagina(); return false;"
														value="Añadir Enlace">Añadir Enlace</button>
												</span></td>
									</tr>
								</table>
							</td>
							<td width="534" valign="top">
								<table width="634" border="0">
									<tr>
										<td width="264"><h3>
												<strong>Lista de Paginas WEB</strong>
											</h3></td>
										<td width="355" align="left" valign="middle"></td>
									</tr>
									<tr>
										<td colspan="2">

        <?php if($action!=md5("mostrar_pagina") && $action!=md5("ajax_buscar_Pagina"))  { ?>

          <table width="625" border="0">
        <?php
            $objApoyo = new BLApoyo();
            $iRs = $objApoyo->ListadoPaginas();
            
            while ($row = mysql_fetch_assoc($iRs)) {
                ?>

            <tr>
													<td width="48">&nbsp;</td>
													<td width="489">&nbsp;</td>
												</tr>
												<tr>
													<td valign="top"><strong>Título:</strong></td>
													<td><a
														href="inf_enlaces_list.php?cod=<?php echo($row['codigo']);?>&action=<?php echo(md5("mostrar_pagina"));?>"><?php echo( $row['titulo']);?></a></td>
												</tr>
												<tr>
													<td valign="top"><strong>URL:</strong></td>

													<td><a href="<?php echo( $row['url']);?>" target="_blank"><?php echo( $row['url']);?></a></td>
												</tr>

          <?php
            }
            ?>
          </table>

     <?php
        }
        ?>

     	<?php if($action==md5("ajax_buscar_Pagina") )  { ?>


          <table width="625" border="0">
        <?php
        $objApoyo = new BLApoyo();
        $texto = $objFunc->__GET('texto');
        
        $iRs = $objApoyo->BuscarPaginas($texto);
        
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            if (isset($row['msg'])) {
                ?>
                        <tr>
													<td colspan="2" width="48"><font color="red"><b><?php echo($row['msg']);?></b></font></td>
												</tr>
												<tr>
													<td colspan="2" height="49" colspan="2"><a
														href="inf_biblio_list.php?action=<?php echo(md5("mostrar_Todos"));?>">Mostrar
															todos</a></td>
												</tr>
                        <?php
                $iRs->free();
            } else {
                ?>
            <tr>
													<td width="48">&nbsp;</td>
													<td width="489">&nbsp;</td>
												</tr>
												<tr>
													<td valign="top"><strong>Título:</strong></td>
													<td><a
														href="inf_enlaces_list.php?cod=<?php echo($row['codigo']);?>&action=<?php echo(md5("mostrar_pagina"));?>"><?php echo( $row['titulo']);?></a></td>
												</tr>
												<tr>
													<td valign="top"><strong>URL:</strong></td>
													<td><a href="<?php echo( $row['url']);?>" target="_blank"><?php echo( $row['url']);?></a></td>
												</tr>

          	<?php
            }
        }
        
        ?>

          </table>




     <?php
    }
    ?>


     <?php if($action=md5("mostrar_pagina") )  {?>


          <table width="625" border="0">
        <?php
        $objApoyo = new BLApoyo();
        $iRs = $objApoyo->ListaPagina($cod);
        
        while ($row = mysql_fetch_assoc($iRs)) {
            ?>
            <tr>
													<td width="48">&nbsp;</td>
													<td width="489">&nbsp;</td>
												</tr>
												<tr>
													<td valign="top"><strong>Titulo:</strong></td>
													<td><?php echo( $row['titulo']);?></td>
												</tr>
												<tr>
													<td valign="top"><strong>URL:</strong></td>
													<td><a href="<?php echo( $row['url']);?>" target="_blank"><?php echo( $row['url']);?></a></td>
												</tr>
												<tr>
													<td valign="top"><strong>Resumen:</strong></td>
													<td><?php echo( $row['resumen']);?></td>
												</tr>

												<tr>
													<td valign="top"><strong>Email:</strong></td>
													<td> <?php echo( $row['email']);?></td>
												</tr>
												<tr>
													<td colspan="2"><a
														href="inf_enlaces_list.php?action=<?php echo(md5("mostrar_Todos"));?>">Mostrar
															todos</a></td>
												</tr>
			<?php
        }
        ?>

          </table>
     <?php
    }
    ?>



          </td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

				</div>



				<div id="panelDocumento" class="popupContainer"
					style="height: 500px;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"><span id="titlePopup">Añadir Documento</span></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogPagina.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText"></div>

							<table width="598" border="0">
								<tr>
									<td width="71" align="left" valign="top">URL</td>
									<td width="510" colspan="2"><textarea name="url" id="url"
											cols="55" rows="2"></textarea></td>
								</tr>
								<tr>
									<td></td>
									<td align="left" valign="top">Ejemplo:
										http://www.fondoempleo.com.pe</td>
								</tr>
								<tr>
									<td align="left" valign="top">Título</td>
									<td><textarea name="titulo" id="titulo" cols="55" rows="3"></textarea>
									</td>
								</tr>
								<tr>
									<td align="left" valign="top">Resumen</td>
									<td colspan="2"><textarea name="resumen" id="resumen" cols="55"
											rows="3"></textarea></td>
								</tr>
								<tr>
									<td align="left" valign="top">Email</td>
									<td colspan="2"><textarea name="email" id="email" cols="55"
											rows="3"></textarea></td>
								</tr>
								<tr>
									<td></td>
									<td align="left" valign="top">Ejemplo:
										fondoempleo@fondoempleo.com.pe</td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
									<td><input type="submit" name="guardar" id="guardar"
										onclick="GuardarPagina(); return false;" value="Guardar" /></td>
								</tr>
							</table>
							<p>&nbsp;</p>
							<div id="divChangePopup"
								style="background-color: #FFF; color: #333;"></div>

						</div>
						<iframe id="ifrmUploadFile" name="ifrmUploadFile"
							style="display: none;"></iframe>
					</div>
				</div>

				<script language="JavaScript" type="text/javascript">
			var spryPopupDialogPagina= new Spry.Widget.PopupDialog("panelDocumento", {modal:true, allowScroll:true, allowDrag:true});
			//var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
	  </script>


				<script language="javascript" type="text/javascript">
function GuardarPagina()
{
 <?php $ObjSession->AuthorizedPage(); ?>
 var url 		= $('#url').val();
 var titulo 	= $('#titulo').val();
 var resumen 	= $('#resumen').val();
 var email 		= $('#email').val();

 var regex=/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i;
 var re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/;

 if(!regex.test(url)){alert("URL es incorrecta");return false;}

 if(url=='' || url==null){alert("Ingrese la URL");return false;}
 if(titulo=='' || titulo==null){alert("Ingrese el título");return false;}
 if(email.length > 0){if(!re.exec(email)){alert("El correo electronico es incorrecto");return false;}}

 var f = document.getElementById("FormData");
 f.action="inf_enlaces_process.php?action=<?php echo(md5('ajax_save_Pagina'));?>" ;
 f.target="ifrmUploadFile" ;
 f.encoding="multipart/form-data";
 f.submit();
 f.target='_self';
}




function BuscarPagina()
	{
	 var btitulo = $('#btitulo').val();

	 if(btitulo=='' || btitulo==null){alert("Ingrese el texto para la busqueda");return false;}

	 var f = document.getElementById("FormData");
		 f.action="inf_enlaces_list.php?texto="+btitulo+"&action=<?php echo(md5('ajax_buscar_Pagina'));?>";
	     f.submit();
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
