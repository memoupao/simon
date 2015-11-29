<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLEjecutor.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;
if ($view == md5("ajax_edit")) {
    $id = $objFunc->__GET('id');
    $objEjec = new BLEjecutor();
    
    $row = $objEjec->EjecutorSeleccionar($id);
    
    $t01_id_inst = $row['t01_id_inst'];
    $t01_ruc_inst = $row['t01_ruc_inst'];
    $t01_sig_inst = $row['t01_sig_inst'];
    $t01_nom_inst = $row['t01_nom_inst'];
    $t01_fch_fund = $row['t01_fch_fund'];
    $t01_pres_anio = $row['t01_pres_anio'];
    $t01_dire_inst = $row['t01_dire_inst'];
    $t01_ciud_inst = $row['t01_ciud_inst'];
    $t01_fono_inst = $row['t01_fono_inst'];
    $t01_fax_inst = $row['t01_fax_inst'];
    $t01_mail_inst = $row['t01_mail_inst'];
    $t01_web_inst = $row['t01_web_inst'];
    $t01_ape_rep = $row['t01_ape_rep'];
    $t01_nom_rep = $row['t01_nom_rep'];
    $t01_carg_rep = $row['t01_carg_rep'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $usr_actu = $row['usr_actu'];
    $fch_actu = $row['fch_actu'];
    $est_audi = $row['est_audi'];
    $t01_tipo_inst = $row['t01_tipo_inst'];
    
    $objEjec = NULL;
    // Se va a modificar el registro !!
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplContentAjax.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php $objFunc->verifyAjax(); ?>
<?php if(!$objFunc->Ajax) { ?>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
    $objFunc->SetTitle("Pagina Principal");
    $objFunc->SetSubTitle("Pagina Principal");
    ?>
<!-- InstanceEndEditable -->
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />
<!-- InstanceEndEditable -->
<SCRIPT type=text/javascript>
    $(document).ready(function() {
        $('#slider').s3Slider({
            timeOut: 4500
        });
    });
</SCRIPT>
<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
  <?php if(!$objFunc->Ajax) { ?>
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
  <?php } ?>
  
  <div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="TemplateEditDetails" -->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1%">&nbsp;</td>
						<td width="73%">&nbsp;</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td><b style="text-decoration: underline"> </b> &nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<!-- InstanceEndEditable -->
				<div id="divContent">
  <?php
if ($objFunc->Ajax) {
    ob_clean();
    ob_start();
}
?>
  <!-- InstanceBeginEditable name="Contenidos" -->
  <?php

if ($objFunc->Ajax) {
    header('Content-type: text/html; charset=utf-8');
    header('Content-type: text/html; charset=UTF-8');
}
?>
  <div style="width: 40px">&nbsp;</div>
					<div style="width: 700px; border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="9%"><button class="Button"
											onclick="btnGuardar_Clic(); return false;" value="Guardar">Guardar
										</button></td>
									<td width="9%"><button class="Button"
											onclick="btnCancelar_Clic(); return false;" value="Cancelar">
											Cancelar</button></td>
									<td width="31%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="2%">&nbsp;</td>
									<td width="47%" align="right">&nbsp;</td>
								</tr>
							</table>
						</div>
						<table width="100%" border="0" cellspacing="2" cellpadding="0">
							<tr>
								<td>&nbsp;</td>
								<td>

									<table width="100%" border="0" cellpadding="0" cellspacing="2"
										class="TableEditReg">
										<tr>
											<td width="18%">RUC</td>
											<td width="26%">Tipo Institución</td>
											<td width="17%">Fec.Fundaci&ograve;n</td>
											<td width="18%">Presup. Anual</td>
											<td width="21%">&nbsp;</td>
										</tr>
										<tr>
											<td><input name="t01_ruc_inst" type="text" id="t01_ruc_inst"
												maxlength="11" value="<?php echo($t01_ruc_inst); ?>" /></td>
											<td><select name="t01_tipo_inst" id="t01_tipo_inst"
												style="width: 145px">
													<option value=""></option>
	   <?php
    $rs = $OjbTab->TipoUnidades();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_tipo_inst);
    ?>
      </select> <input type="hidden" name="t01_id_inst" id="t01_id_inst"
												value="<?php echo($t01_id_inst); ?>" /></td>
											<td><input name="t01_fch_fund" type="text" id="t01_fch_fund"
												size="14" value="<?php echo($t01_fch_fund); ?>" /></td>
											<td><input name="t01_pres_anio" type="text"
												id="t01_pres_anio" size="14"
												value="<?php echo($t01_pres_anio); ?>"
												style="text-align: right;" /></td>
											<td></td>
										</tr>
										<tr>
											<td>Siglas</td>
											<td nowrap="nowrap">Nombre de la Institución</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td><input name="t01_sig_inst" type="text" id="t01_sig_inst"
												value="<?php echo($t01_sig_inst); ?>" /></td>
											<td colspan="4"><input name="t01_nom_inst" type="text"
												id="t01_nom_inst" size="80"
												value="<?php echo($t01_nom_inst); ?>" /></td>
										</tr>
									</table> <script type="text/javascript">
		   jQuery("#t01_fch_fund").datepicker();
      </script>
									<table border="0" cellpadding="0" cellspacing="2"
										class="TableEditReg">
										<tr>
											<td colspan="3">Direccion <br /></td>
											<td width="40%">Ciudad</td>
										</tr>
										<tr>
											<td colspan="3"><input name="t01_dire_inst" type="text"
												id="t01_dire_inst" size="65"
												value="<?php echo($t01_dire_inst); ?>" /></td>
											<td><input name="t01_ciud_inst" type="text"
												id="t01_ciud_inst" size="35"
												value="<?php echo($t01_ciud_inst); ?>" /></td>
										</tr>
										<tr>
											<td width="20%" align="left" nowrap="nowrap">Telefonos</td>
											<td width="20%" align="left" nowrap="nowrap">Fax</td>
											<td width="20%" nowrap="nowrap">Mail Institucional</td>
											<td nowrap="nowrap">Web Institucional</td>
										</tr>
										<tr>
											<td align="left"><input name="t01_fono_inst" type="text"
												id="t01_fono_inst" value="<?php echo($t01_fono_inst); ?>"
												size="14" /></td>
											<td align="left"><input name="t01_fax_inst" type="text"
												id="t01_fax_inst" value="<?php echo($t01_fax_inst); ?>"
												size="14" /></td>
											<td><input name="t01_mail_inst" type="text"
												id="t01_mail_inst" value="<?php echo($t01_mail_inst); ?>"
												size="25" /></td>
											<td><input name="t01_web_inst" type="text" id="t01_web_inst"
												value="<?php echo($t01_web_inst); ?>" size="35" /></td>
										</tr>
									</table>
									<fieldset style="width: 620px;">
										<legend>Responsable</legend>
										<table width="100%" border="0" cellpadding="0" cellspacing="2"
											class="TableEditReg">
											<tr>
												<td width="18%">Cargo del Responsable</td>
												<td width="24%">Apellidos</td>
												<td width="58%">Nombres</td>
											</tr>
											<tr>
												<td><select name="t01_carg_rep" id="t01_carg_rep"
													style="width: 150px;">
														<option value=""></option>
	   <?php
    $rs = $OjbTab->TipoCargos();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_carg_rep);
    ?>
    	</select></td>
												<td><input name="t01_ape_rep" type="text" id="t01_ape_rep"
													value="<?php echo($t01_ape_rep); ?>" size="35" /></td>
												<td><input name="t01_nom_rep" type="text" id="t01_nom_rep"
													value="<?php echo($t01_nom_rep); ?>" size="35" /></td>
											</tr>
										</table>

									</fieldset>
								</td>
							</tr>
						</table>

						<br />
					</div>
					<script language="javascript">
   function btnGuardar_Clic()
	  {
		 
		 var BodyForm = $("#FormData").serialize() ;
		 <?php if($id=="") { ?>
		 var sURL = "process.php?action=<?php echo(md5("ajax_new"));?>" ;
		 <?php } else { ?>
		 var sURL = "process.php?action=<?php echo(md5("ajax_edit"));?>" ;
		 <?php }  ?>
		 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

		return false;
		
	  }
	 function btnCancelar_Clic()
	  {
	    CancelEdit();
	  }
	  
	  
	  function MySuccessCallback(req)
		{
		  // Throw an alert with the message that was
		  // passed to us via the userData.
		  var respuesta = req.xhRequest.responseText;
		  var ret = respuesta.substring(0,5);
		  if(ret=="Exito")
		  {
			alert(respuesta.replace(ret,""));
			ReloadLista();  
		  }
		  else
		  {alert(respuesta);}  
		  
		}
		
		function MyErrorCallback(req)
		{
		  // Throw an alert with the message that was
		  // passed to us via the userData.
		  alert("ERROR: " + req);
		}

	  
	  
	  
  </script>
					<!-- InstanceEndEditable -->
    <?php 
  	if ($objFunc->Ajax)
	{
		ob_end_flush(); 
		exit();
	}
    ?>
    </div>
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

<?php 
function LimpiarBuffer($buffer)
{
    return trim($buffer);
}
?>