<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplMainLista.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetTitle("Información de Apoyo");
$objFunc->SetSubTitle("Biblioteca Virtual");
require_once (constant("PATH_CLASS") . "BLApoyo.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$action = $objFunc->__Request('action');
$cod = $objFunc->__GET('cod');
?>
<script src="../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryPopupDialog.css" rel="stylesheet"
	type="text/css" />

<script language="javascript" type="text/javascript">
		function AnadirDocumento()
		  {
			  spryPopupDialogDocumento.displayPopupDialog(true);
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
													onclick="BuscarDocumento(); return false;" value="Buscar">
													Buscar</button>
										</span></td>
									</tr>
									<tr>
										<td height="30"><input type="text" name="btitulo" id="btitulo" /></td>
									</tr>
									<tr>

									</tr>
									<tr>
										<td height="30" align="center"><p></p>
											<p>
												<span id="toolbar" style="width: 70px; height: 8px;">
													<button class="Button" name="anadir" id="anadir"
														onclick="AnadirDocumento(); return false;"
														value="Añadir Documento">Añadir Documento</button>
												</span>
											</p></td>
									</tr>
								</table>
							</td>
							<td width="534" valign="top">
								<table width="634" border="0">
									<tr>
										<td width="264"><h3>
												<strong>Lista de Libros Digitales</strong>
											</h3></td>
										<td width="355" align="left" valign="middle"></td>
									</tr>
									<tr>
										<td colspan="2">
          
        <?php if($action!=md5("mostrar_libro") && $action!=md5("ajax_buscar_Documento"))  { ?>        
            
          <table width="625" border="0">
        <?php
            $objApoyo = new BLApoyo();
            $iRs = $objApoyo->ListadoLibros();
            
            while ($row = mysql_fetch_assoc($iRs)) {
                ?>         
          
            <tr>
													<td width="48">&nbsp;</td>
													<td width="489">&nbsp;</td>
												</tr>
												<tr>
													<td valign="top">Titulo:</td>
													<td><a
														href="inf_biblio_list.php?cod=<?php echo($row['codigo']);?>&action=<?php echo(md5("mostrar_libro"));?>"><?php echo( $row['titulo']);?></a></td>
												</tr>
												<tr>
													<td valign="top">Autor:</td>
													<td><?php echo( nl2br($row['autor']));?></td>
												</tr>
												<tr>
													<td valign="top">Reseña:</td>
													<td><?php echo( $row['resena']);?></td>
												</tr>
            
          <?php
            }
            ?>
          </table> 
      
     <?php
        }
        ?>   
       
     	<?php if($action==md5("ajax_buscar_Documento") )  { ?>
        
            
          <table width="625" border="0">
        <?php
        $objApoyo = new BLApoyo();
        $texto = $objFunc->__GET('texto');
        
        $iRs = $objApoyo->BuscarDocumentos($texto);
        
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            ?>         
          
            <tr>
													<td width="48">&nbsp;</td>
													<td width="489">&nbsp;</td>
												</tr>
												<tr>
													<td valign="top">Titulo:</td>
													<td><a
														href="inf_biblio_list.php?cod=<?php echo($row['codigo']);?>&action=<?php echo(md5("mostrar_libro"));?>"><?php echo( $row['titulo']);?></a></td>
												</tr>
												<tr>
													<td valign="top">Autor:</td>
													<td><?php echo( nl2br( $row['autor']));?></td>
												</tr>
												<tr>
													<td valign="top">Reseña:</td>
													<td><?php echo( $row['resena']);?></td>

												</tr>
           
          <?php
        }
        ?>
          
          </table> 
      
     <?php
    }
    ?>   
          
              
     <?php if($action=md5("mostrar_libro") )  { ?>
        
            
          <table width="625" border="0">
        <?php
        $objApoyo = new BLApoyo();
        $iRs = $objApoyo->ListaLibro($cod);
        
        while ($row = mysql_fetch_assoc($iRs)) {
            ?>           
            <tr>
													<td width="48">&nbsp;</td>
													<td width="489">&nbsp;</td>
												</tr>
												<tr>
													<td valign="top">Titulo:</td>
													<td><?php echo( $row['titulo']);?></td>
												</tr>
												<tr>
													<td valign="top">Autor:</td>
													<td><?php echo( nl2br($row['autor']));?></td>
												</tr>
												<tr>
													<td valign="top">Resumen:</td>
													<td><?php echo( $row['resumen']);?></td>
												</tr>
            
            <?php
            $urlFile = $row['t70_url_file'];
            $filename = $row['t70_url_file'];
            $arr = explode('.', $urlFile);
            
            // $file_extension = strtolower(substr(strrchr($filename,"."),1));
            $file_extension = $arr[count($arr) - 1];
            $objHC = new HardCode();
            
            $path = constant('APP_PATH') . $objHC->FolderUploadBV;
            $pathimg = constant("DOCS_PATH") . $objHC->FolderUploadBV . $row['t70_url_file'];
            $href = constant("DOCS_PATH") . "download.php?fileurl=" . $urlFile . "&path=" . $path . "&filename=" . $filename;
            
            if ($file_extension == 'gif' or $file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'png' or $file_extension == 'bmp') {
                $file_vista = "<a href=" . $href . " title='Descargar Archivo' target='_blank'><img src='" . $pathimg . "'" . $urlFile . " /></a>";
            } else {
                $file_vista = "<a href=" . $href . " title='Descargar Archivo' target='_blank'>" . $row['t70_nom_file'] . "</a>";
            }
            ?>
            
            <tr>
													<td valign="top">Archivo:</td>
													<td> <?php echo ($file_vista); ?></td>
												</tr>
          <?php
            ?>
		  	<tr>
													<td height="49" colspan="2"><a
														href="inf_biblio_list.php?action=<?php echo(md5("mostrar_Todos"));?>">Mostrar
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
					style="height: 500px; visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"><span id="titlePopup">Añadir Documento</span></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogDocumento.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText"></div>

							<table width="591" border="0" class="TableEditReg">
								<tr>
									<td width="100" align="left" valign="top">Titulo</td>
									<td colspan="4"><textarea name="titulo" id="titulo" cols="80"
											rows="2"></textarea></td>
								</tr>
								<tr>
									<td align="left" valign="top">Autor(es)</td>
									<td width="202"><textarea name="autor" cols="40" rows="2"
											id="autor"></textarea></td>
									<td width="41">Edicion</td>
									<td><input name="edicion" type="text" id="edicion" size="23" /></td>
								</tr>
								<tr>
									<td align="left" valign="top">Reseña</td>
									<td colspan="4"><textarea name="resena" id="resena" cols="80"
											rows="2"></textarea></td>
								</tr>
								<tr>
									<td align="left" valign="top">Resumen</td>
									<td colspan="4"><textarea name="resumen" id="resumen" cols="80"
											rows="3"></textarea></td>
								</tr>
								<tr>
									<td align="left" valign="top">Sector</td>
									<td colspan="3"><select name="sector" id="sector"
										style="width: 160px">
											<option value="" selected="selected"></option>
                <?php
                $objTablas = new BLTablasAux();
                $rsSectores = $objTablas->SectoresProductivos();
                $objFunc->llenarCombo($rsSectores, 'codigo', 'descripcion', $t02_sector);
                ?>
                </select></td>
								</tr>
								<tr>
									<td align="left" valign="top">Palabras Claves</td>
									<td colspan="3"><input name="clave" type="text" id="clave"
										size="73" /></td>
								</tr>
								<tr>
									<td align="left" valign="top">Subir Archivo</td>
									<td colspan="3"><input name="arch" type="file" id="arch"
										size="50" /></td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
									<td colspan="3"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="1" cellpadding="0">
								<tr>
									<td height="36"><span id="toolbar"
										style="width: 70px; height: 8px;"> &nbsp;&nbsp;&nbsp;
											<button class="Button"
												onclick="GuardarDocumento(); return false;" value="Guardar">
												Guardar</button>
									</span></td>
								</tr>
							</table>
						</div>
						<iframe id="ifrmUploadFile" name="ifrmUploadFile"
							style="display: none;"></iframe>
					</div>
				</div>

				<script language="JavaScript" type="text/javascript">
			var spryPopupDialogDocumento= new Spry.Widget.PopupDialog("panelDocumento", {modal:true, allowScroll:true, allowDrag:true});
			//var spryPopupDialog01= new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
	  </script>


				<script language="javascript" type="text/javascript">
function GuardarDocumento()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var titulo = $('#titulo').val();
 var autor = $('#autor').val();
 var resena = $('#resena').val();
 var resumen = $('#resumen').val();
 var edicion = $('#edicion').val();
 var sector = $('#sector').val();
 var clave = $('#clave').val();
 var tipoarch = $('#tipoarch').val();
 var arch = $('#arch').val();
 
 if(titulo=='' || titulo==null){alert("Ingrese el título");return false;}
 if(autor=='' || autor==null){alert("Ingrese el autor");return false;}
 if(resena=='' || resena==null){alert("Ingrese la reseña");return false;}
/*
 if(resumen=='' || resumen==null){alert("Ingrese el resumen");return false;}
 if(edicion=='' || edicion==null){alert("Ingrese la edicion");return false;}
 if(sector=='' || sector==null){alert("Ingrese el sector");return false;}
 if(clave=='' || clave==null){alert("Ingrese la clave");return false;}
 if(tipoarch=='' || tipoarch==null){alert("Ingrese el tipo de archivo");return false;}
 */
 if(arch=='' || arch==null){alert("Seleccione al archivo");return false;}

 var f = document.getElementById("FormData");
 f.action="inf_apoyo_process.php?action=<?php echo(md5('ajax_save_Documento'));?>" ;
 f.target="ifrmUploadFile" ;
 f.encoding="multipart/form-data";
 f.submit();
 f.target='_self';
 

}




function BuscarDocumento()
	{
	 var btitulo = $('#btitulo').val();
	
	 if(btitulo=='' || btitulo==null){alert("Ingrese el texto para la busqueda");return false;}

	 var f = document.getElementById("FormData");
		 f.action="inf_biblio_list.php?texto="+btitulo+"&action=<?php echo(md5('ajax_buscar_Documento'));?>";
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
