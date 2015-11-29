<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");

$OjbTab = new BLTablasAux();
$objHC = new HardCode();

$view = $objFunc->__GET('mode');
$row = 0;
$idProy = $objFunc->__GET('idProy');

$objProy = new BLProyecto();
$rproy = $objProy->ProyectoSeleccionar($idProy, 1);
$objProy = NULL;

if ($view == md5("ajax_edit") || $view == md5("ajax_view")) {
    $objFunc->SetSubTitle("CartaFianza / Editar Registro");
    $id = $objFunc->__GET('id');
    $objProy = new BLProyecto();
    
    $row = $objProy->CartaFianza_Seleccionar($idProy, $id);
    $objProy = NULL;
    // echo($t04_telf_equi);
    // Se va a modificar el registro !!
} else {
    $objFunc->SetSubTitle("Carta Fianza / Nuevo Registro");
    $idProy = $objFunc->__GET('idProy');
    $id = "";
}

?>

<?php if($idProy=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
    $objFunc->SetTitle("Proyectos - Carta Fianza");
    ?>
<!-- InstanceEndEditable -->
<?php
    
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
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

<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
						type="text/javascript"></script>
					<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
						rel="stylesheet" type="text/css" />
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
						type=text/javascript></script>
					<script src="../../../js/commons.js" type="text/javascript"></script>

					<script>
  function HabilitarObservaciones()
  {
	  if($("#chkVB").attr('checked'))
	  {
		  $('#txtobs_adm').removeAttr("disabled");
		   $('#txtobs_adm').focus();
	  }
	  else
	  {
		  $('#txtobs_adm').attr("disabled","disabled");
	  }
  }
  function DeshabilitarAll()
  {
	$("#btnGuadraCF").attr("disabled","disabled");
    $('#cbobanco').attr("disabled","disabled");
 	$('#txtnumero').attr("disabled","disabled");
	$('#txtserie').attr("disabled","disabled");
	$('#txtfecrec').attr("disabled","disabled");
	$('#txtfecvenc').attr("disabled","disabled");
	$('#txtfecgir').attr("disabled","disabled");
	$('#txtmonto').attr("disabled","disabled");
	$('#txtdescripcion').attr("disabled","disabled");
	$('#txtfileupload').attr("disabled","disabled");
	$('#chkVB').attr("disabled","disabled");
	$('#txtobs_adm').attr("disabled","disabled");
  }
  HabilitarObservaciones();
  <?php
if ($view == md5("ajax_view")) {
    echo ("DeshabilitarAll();");
}
?>
	 
  </script>



					<div id="toolbar" style="height: 8px;" class="BackColor">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%"><button class="Button"
										onclick="btnGuardar_Clic(); return false;" value="Guardar"
										id="btnGuadraCF">Guardar</button></td>
								<td width="9%"><button class="Button"
										onclick="btnCancelar_Clic(); return false;" value="Cancelar">
										Cancelar</button></td>
								<td width="31%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
					<div>
						<fieldset>
							<legend>
								<strong>Carta Fianza</strong>
							</legend>
							<table width="700" border="0" cellpadding="0" cellspacing="2"
								class="TableEditReg">
								<tr>
									<td width="1">&nbsp;</td>
									<td colspan="5">
      <?php
    
if ($id == "") {
        $sURL = "carta_fianza_process.php?action=" . md5("ajax_new");
    } else {
        $sURL = "carta_fianza_process.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave"
										value="<?php echo($sURL); ?>" /> <input type="hidden"
										name="t02_cod_proy" id="t02_cod_proy"
										value="<?php echo($idProy); ?>" /> <input type="hidden"
										name="txtidcartafianza" id="txtidcartafianza"
										value="<?php echo($row['t02_id_cf']); ?>" />
									</td>
								</tr>
								<tr height="10">
									<td height="27">&nbsp;</td>
									<td><strong>Periodo Proyecto</strong></td>
									<td colspan="3"><strong>Inicio: </strong><?php echo($rproy['ini']); ?> <strong>&nbsp;&nbsp;&nbsp;Término:
									</strong><?php echo($rproy['fin']); ?></td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr height="10">
									<td height="27">&nbsp;</td>
									<td><strong>Entidad Financiera</strong></td>
									<td colspan="3">
										<div>
											<select name="cbobanco" id="cbobanco" style="width: 280px;">
												<option value=""></option>
        <?php
        $rs = $OjbTab->ListaBancos();
        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['t02_id_bco']);
        ?>
      </select>

										</div>
									</td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr height="10">
									<td height="24">&nbsp;</td>
									<td width="114"><strong>Numero</strong></td>
									<td width="174"><input name="txtnumero" type="text"
										id="txtnumero" value="<?php echo($row['t02_num_cf']); ?>"
										size="20" maxlength="20" /></td>
									<td width="72">&nbsp;</td>
									<td width="107"><strong>Serie</strong></td>
									<td width="218" align="left" bgcolor="#553FFF"><input
										name="txtserie" type="text" id="txtserie"
										value="<?php echo($row['t02_ser_cf']); ?>" size="20"
										maxlength="20" /></td>
								</tr>
								<tr height="10">
									<td height="23">&nbsp;</td>
									<td><strong>Fecha de Emisión </strong></td>
									<td><input name="txtfecgir" type="text" id="txtfecgir"
										value="<?php echo($row['t02_fgir_cf']); ?>" size="20" /></td>
									<td align="right">&nbsp;</td>
									<td><strong>Fecha Recepcion</strong></td>
									<td align="left" bgcolor="#553FFF"><input name="txtfecrec"
										type="text" id="txtfecrec"
										value="<?php echo($row['t02_frec_cf']); ?>" size="20" /></td>
								</tr>

								<tr height="10">
									<td height="27">&nbsp;</td>
									<td nowrap="nowrap" bgcolor="#553FFF"><strong>Fecha Vencimiento</strong></td>
									<td><input name="txtfecvenc" type="text" id="txtfecvenc"
										value="<?php echo($row['t02_fven_cf']); ?>" size="20"
										onchange="VerificarEstadoCF();" /></td>
									<td align="right">&nbsp;</td>
									<td><strong>Monto - Carta Fianza</strong></td>
									<td align="left" bgcolor="#553FFF"><input name="txtmonto"
										type="text" id="txtmonto"
										value="<?php echo(round($row['t02_mto_cf'],2)); ?>" size="20" /></td>
								</tr>
								<tr height="10">
									<td height="26">&nbsp;</td>
									<td><strong>Estado</strong></td>
									<td colspan="2"><select name="cboestado" id="cboestado"
										style="width: 180px;" disabled="disabled">
											<option value=""></option>
        <?php
        $rs = $OjbTab->TipoEstadoCartaFianza();
        $objFunc->llenarCombo($rs, 'cod_ext', 'descripcion', $row['t02_est_cf']);
        ?>
      </select></td>
									<td>&nbsp;</td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr height="10">
									<td height="25">&nbsp;</td>
									<td><strong>Descripcion</strong></td>
									<td colspan="4"><input name="txtdescripcion" type="text"
										id="txtdescripcion" value="<?php echo($row['t02_des_cf']); ?>"
										size="90" /></td>
								</tr>
								<tr height="10">
									<td height="35">&nbsp;</td>
									<td><strong>Adjuntar Archivo de Carta Fianza</strong></td>
									<td colspan="4">
    <?php
    if ($row['t02_file_cf'] != "") {
        $filename = constant("DOCS_PATH") . $objHC->FolderUploadCartaFianza . $row['t02_file_cf'];
        
        echo ("<a href=\"#\" onclick=\"window.open('" . $filename . "','dwCF');\">" . $row['t02_file_cf'] . "</a><br>");
    }
    ?>
    <input name="txtfileupload" type="file" id="txtfileupload" size="70"
										class="Centrado" />
									</td>
								</tr>
								<tr height="10">
									<td height="35">&nbsp;</td>
									<td><strong>VB-SP</strong></td>
									<td colspan="4"><?php if($ObjSession->PerfilID==$objHC->SP || $ObjSession->PerfilID==$objHC->FE ) { ?>
      <input name="chkVB" type="checkbox" id="chkVB" value="1"
										<?php if($row['t02_vb_adm']=='1'){echo('checked="checked"');} ?>
										onclick="HabilitarObservaciones();" />
      <?php } else { ?>
      <input name="chkVB" type="checkbox" id="chkVB" value="1"
										<?php if($row['t02_vb_adm']=='1'){echo('checked="checked"');} ?>
										disabled="disabled" />
      <?php } ?></td>
								</tr>
								<tr height="10">
									<td height="35">&nbsp;</td>
									<td><strong>Observaciones SP</strong></td>
									<td colspan="4">
    <?php if($ObjSession->PerfilID==$objHC->SP || $ObjSession->PerfilID==$objHC->FE ) { ?>
      <textarea name="txtobs_adm" cols="90" id="txtobs_adm"><?php echo($row['t02_obs_adm']); ?></textarea>
      <?php } else { ?>
     <textarea name="txtobs_adm" cols="90" id="txtobs_adm"
											readonly="readonly"><?php echo($row['t02_obs_adm']); ?></textarea>
      <?php } ?>
    </td>
								</tr>
								<tr height="10">
									<td height="20">&nbsp;</td>
									<td colspan="5"><iframe id="ifrmUploadFile"
											name="ifrmUploadFile" style="display: none;"></iframe></td>
								</tr>
								<tr height="10">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</fieldset>
					</div>
					<script>
	function btnGuardar_Clic()
	{
		
	 var f_ini = CDate("<?php echo($rproy['ini']);?>");
	 var f_ter = CDate("<?php echo($rproy['ini']);?>");
	 
	 if( $('#cbobanco').val()=="" ) 
	 {	alert("Seleccione Entidad Financiera"); 
	 	$('#cbobanco').focus() ;
	 	return false;
	 }	
	 
	 if( $('#txtnumero').val()=="" ) 
	 {	alert("Ingrese Numero de la Carta Fianza"); 
	 	$('#txtnumero').focus() ;
	 	return false;
	 }	
	
	 if( $('#txtserie').val()=="" ) 
	 {	alert("Ingrese Serie de la Carta Fianza"); 
	 	$('#txtserie').focus() ;
	 	return false;
	 }	
	
	if( CDate($('#txtfecrec').val())== null ) 
	 {	
	 	alert("Ingrese Fecha de Presentacion de la Carta Fianza"); 
	 	$('#txtfecrec').focus() ;
	 	return false;
	 }	
	
	if( CDate($('#txtfecvenc').val())== null ) 
	 {	
	 	alert("Ingrese Fecha de Vencimiento de la Carta Fianza"); 
	 	$('#txtfecvenc').focus() ;
	 	return false;
	 }	
	
	 if( $('#txtmonto').val()=="" ) 
	 {	alert("Ingrese Monto de la Carta Fianza"); 
	 	$('#txtmonto').focus() ;
	 	return false;
	 }	
	 
	 
	 var urlPost = $('#txturlsave').val();
	 $('#FormData').attr({target: "ifrmUploadFile"});
	 $('#FormData').attr({action: urlPost});
	 $('#FormData').attr({encoding: "multipart/form-data"});
	 $('#FormData').submit();
	 $('#FormData').attr({target: "_self"});
	 
	 return false;
	
	}
	
	function ReturnGuardar(arg, msg)
	{
		alert($('<div></div>').html(msg).text()) ;
		if(arg)
			ReloadLista();  
	}
	
	function VerificarEstadoCF()
	{
		var fechahoy  = new Date(<?php echo($objFunc->AnioActual());?>, <?php echo($objFunc->MesActual()-1); ?> ,<?php echo($objFunc->DiaActual()); ?>) ;
		var fechavenc = CDate($("#txtfecvenc").val());
		var vStatusCF  = '0' ;
		
		if(fechavenc!=null)
		{
			if(fechahoy < fechavenc )
			{	vStatusCF = "1"; }
		}
		$("#cboestado").val(vStatusCF);
		return ;
	}
	
	
	function CDate(strDate)
	{
		if(strDate==""){return null ;}
		try
		{
			var dt=strDate.split('/');
			var ndate = new Date(Number(dt[2]),Number(dt[1])-1,Number(dt[0])) ;
			return ndate ;
		}
		catch(e)
		{
			return null ;
		}
	} 
	
	
	function ShowFileCF()
	{
		<?php
$objHC = new HardCode();
$urlFile = $row['t02_file_cf'];
$filename = $row['t02_file_cf'];
$path = constant('APP_PATH') . $HC->FolderUploadCartaFianza;
			$href = constant("DOCS_PATH")."download.php?filename=".$filename."&fileurl=".$urlFile."&path=".$path;
		?>
		var url = "<?php echo($filename);?>";
		if(url=="") {alert("No se ha establecido el archivo adjunto"); return false;}
		var url = "<?php echo($href);?>";
		window.open(url,"DownloadCF",null,true);
		return;
	}
	
	</script>

					<script>
	    /* Establecer Campos de Texto como Calendarios*/
		jQuery("#txtfecrec").datepicker();
		jQuery("#txtfecgir").datepicker();
		jQuery("#txtfecvenc").datepicker();
		$("#txtmonto").numeric().pasteNumeric();
	</script>
    
<?php if($idProy=="") { ?>
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

