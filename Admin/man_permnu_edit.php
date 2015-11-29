<?php include("../includes/constantes.inc.php"); ?>
<?php include("../includes/validauseradm.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLMantenimiento.class.php");
$objMante = new BLMantenimiento();
$view = $objFunc->__GET('mode');

$row = 0;
$objFunc->SetSubTitle("Modificando Restricciones x Perfil");

$idPerfil = $objFunc->__Request('idPerfil');

if ($view == md5("ajax_edit")) {
    
    $idUser = $objFunc->__GET('id');
    
    $row = $objMante->UsuarioSeleccionar($idUser);
} else {
    $row = 0;
}
?>

<?php if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<?php
    
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<script src="../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="TemplateEditDetails" -->

				<!-- InstanceEndEditable -->
				<div id="divContent">
					<!-- InstanceBeginEditable name="Contenidos" -->
 <?php } ?>
<link
						href="<?php echo(constant("DOCS_PATH"))?>jquery.ui-1.5.2/ui/jquery.treeview.css"
						rel="stylesheet" />
					<script
						src="<?php echo(constant("DOCS_PATH"))?>jquery.ui-1.5.2/ui/lib/jquery.cookie.js"
						type="text/javascript"></script>
					<script
						src="<?php echo(constant("DOCS_PATH"))?>jquery.ui-1.5.2/ui/jquery.treeview.js"
						type="text/javascript"></script>
					<style>
.rowTree {
	border-bottom: 1px dotted #CCC;
}

.negrita {
	color: #036;
	font-weight: bold;
}
</style>
					<br />
					<table width="700" border="0" align="center" cellpadding="0"
						cellspacing="0" class="TableEditReg">
						<tr>
							<td width="542">
								<fieldset>
									<legend>Opciones del Sistema</legend>
									<div
										style="width: 688px; border: 1px solid #CCC; padding: 5px; padding: 1px;">
										<div class="TableGrid">
											<table width="100%" border="0" cellpadding="0"
												cellspacing="0">
												<tbody class="data">
													<tr class="RowData">
														<td width="%" rowspan="2"
															style="background-color: #E9E9E9;">Nombre del Menú</td>
														<td colspan="4" align="center"
															style="background-color: #E9E9E9;">Opciones</td>
													</tr>
													<tr class="RowData">
														<td style="width: 70px; background-color: #E9E9E9;"
															align="center">Ver</td>
														<td style="width: 70px; background-color: #E9E9E9;"
															align="center">Nuevo</td>
														<td style="width: 70px; background-color: #E9E9E9;"
															align="center">Editar</td>
														<td style="width: 70px; background-color: #E9E9E9;"
															align="center">Eliminar</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>


									<div
										style="height: 300px; width: 680px; overflow-x: hidden; overflow-y: scroll; border: 1px solid #CCC; padding: 5px;">
										<ul id="divMenusPerfil" class="filetree"
											style="visibility: hidden;">
      <?php
    WriteMenuMain($ObjSession);

    function WriteMenuMain($Sesion)
    {
        $MenuOptions = $Sesion->ListaMenus(FALSE, $_REQUEST['idPerfil']);
        while ($row = mysqli_fetch_assoc($MenuOptions)) { // Inicio while
            echo ("<li><span class='folder'><font class='negrita'>" . $row['mnu_nomb'] . " &nbsp;</font></span>   \n");
            WriteMenuItems($Sesion, $row['mnu_cod']);
            echo ("</li>\n");
        } // fin While
        $MenuOptions->free();
    }

    function WriteMenuItems($Sesion, $strMenuParent)
    {
        $SubItems = $Sesion->ListaMenusItems(FALSE, $strMenuParent, $_REQUEST['idPerfil']);
        if (mysqli_num_rows($SubItems) > 0) {
            echo ("<ul> \n");
            while ($r = mysqli_fetch_assoc($SubItems)) { // Inicio while
                $TieneHijos = $r['submenus']; // $Sesion->NumeroSubItems($r['mnu_cod']);
                $checked = ($r['tiene_permiso'] == '1' ? 'checked="checked"' : '');
                $disopci = ($r['tiene_permiso'] == '0' ? 'disabled="disabled"' : '');
                
                $chkver = ($r['ver'] == '1' ? 'checked="checked"' : '');
                $chknew = ($r['nuevo'] == '1' ? 'checked="checked"' : '');
                $chkedt = ($r['editar'] == '1' ? 'checked="checked"' : '');
                $chkdel = ($r['eliminar'] == '1' ? 'checked="checked"' : '');
                
                $checkBox = '<input type="checkbox" name="chkMenu[]" id="' . $r['mnu_cod'] . '" value="' . $r['mnu_cod'] . '" link="' . $r['mnu_link'] . '" menu="1"  parent="' . $r['mnu_parent'] . '" ' . $checked . ' onclick="ActivarOpciones(\'' . $r['mnu_cod'] . '\')" class="MenusPerfil" />';
                
                if ($TieneHijos > 0) {
                    $span = "<span class='folder'><font class='negrita'>" . $r['mnu_nomb'] . "</font> </span>";
                } else {
                    $span = "<span class=''>" . $checkBox . "<label for='" . $r['mnu_cod'] . "' style='cursoe:pointer;'>" . $r['mnu_nomb'] . "</label> </span>";
                }
                
                $idchk = $r['mnu_cod'];
                
                $div = '
					    <div class="rowTree">
							 <div align="left" style="display:inline-block;">
								' . $span . '
							 </div>
							 
							 <div style="display:inline-block; float:right;"> 
							  <div style="display:inline-block; width:70px; text-align:center">
								<input type="checkbox" name="' . $idchk . '_ver" id="' . $idchk . '_ver" parent="' . $r['mnu_cod'] . '" value="1" ' . $chkver . ' ' . $disopci . ' class="MenusPerfil" />
							  </div>
							  <div style="display:inline-block; width:70px; text-align:center">
								<input type="checkbox" name="' . $idchk . '_nuevo" id="' . $idchk . '_nuevo" parent="' . $r['mnu_cod'] . '" value="1" ' . $chknew . ' ' . $disopci . ' class="MenusPerfil" />
							  </div>
							  <div style="display:inline-block; width:70px; text-align:center">
								<input type="checkbox" name="' . $idchk . '_editar" id="' . $idchk . '_editar" parent="' . $r['mnu_cod'] . '" value="1" ' . $chkedt . ' ' . $disopci . ' class="MenusPerfil" />
							  </div>
							   <div style="display:inline-block; width:62px; text-align:center">
								<input type="checkbox" name="' . $idchk . '_eliminar" id="' . $idchk . '_eliminar" parent="' . $r['mnu_cod'] . '" value="1" ' . $chkdel . ' ' . $disopci . ' class="MenusPerfil" />
							  </div>
							</div>
							
						</div>';
                
                if ($TieneHijos > 0) {
                    echo ("<li>" . $span . " \n");
                    WriteMenuItems($Sesion, $r['mnu_cod']);
                } else {
                    echo ("<li>" . $div . " \n");
                }
                
                echo ("</li>\n");
            } // fin While
            echo ("</ul> \n");
            $SubItems->free();
        }
    }
    
    ?> 
      </ul>
									</div>

								</fieldset>
							</td>
						</tr>
						<tr>
							<td></td>
						</tr>
					</table>
					<br />

					<script language="javascript">
  $(document).ready(function(){
	$("#divMenusPerfil").treeview({collapsed: false, animated:"fast", persist: "location"});
	});	
  
  $('#divMenusPerfil').css('visibility','visible');
  
  function ActivarOpciones(menu)
  {
	  var idchk = '#' + menu ;
	  var classchk = "input[parent='"+menu+"']" ;
	  
	  if($(idchk).attr('checked'))
	  {
	  	$(classchk).removeAttr('disabled','disabled');
	  }
	  else
	  {
		  $(classchk).attr('disabled','disabled');
	  }
  }
  	
	
  </script>
  

 <?php if($view=='') { ?>
  <!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>
<?php } ?>

