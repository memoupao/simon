﻿<?php

include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require (constant('PATH_CLASS') . "BLTablas.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLFE.class.php");




$objEjecDesem = new BLFE();
$OjbTab = new BLTablasAux();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$accion = $objFunc->__GET('accion');
$isUnsuspendFlg = false;

$row = 0;
$row_aprob_proy = 0;

if ($view == md5("ajax_edit")) {
    if ($accion == md5("editar")) {
        $objFunc->SetSubTitle("Editando Ejecucion de Desembolsos por Entregable");
    } else {
        $objFunc->SetSubTitle("Ver Ejecucion de Desembolsos por Entregable");
    }

    $id = $objFunc->__GET('idproy');
    $version = $objFunc->__GET('vs');
    
    $rs = $objEjecDesem->itemEjecDesemPorEntrSeleccionar($id,$version);
    
    
} else {
    exit(0);
}

if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$objFunc->SetTitle("Gestores Fondoempleo - Ejecucion de Desembolsos por Entregables");
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
    <meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
    <meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo($objFunc->Title);?></title>
    <link href="../../../css/template.css" rel="stylesheet" media="all" />
    <link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
    <script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
    <script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
    <style type="text/css">
        #toolbar .Button {
        	color: #000;
        }
    </style>
<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
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
				<div id="divContent">
<?php } ?>
                    <script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js" type="text/javascript"></script>
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
					<script src="../../../js/commons.js" type="text/javascript"></script>
					<br />
					<div id="EditForm" style="border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="8%"><button class="Button" id="btnGuardar" onclick="btnGuardar_Clic(); return false;">Guardar</button></td>
									<td width="13%"><button class="Button" onclick="btnCancelar_Clic(); return false;">Volver y Cerrar</button></td>									
									<td width="40%" colspan="3">&nbsp;</td>
									<td width="25%" align="right"><?php echo($objFunc->SubTitle);?></td>
								</tr>
							</table>
						</div>	
						
  						<table width="760" border="0" cellpadding="0" cellspacing="0" class="TableEditReg">
							<tr>
								<td>
									<fieldset>
										<legend>Ejecucion de Desembolso</legend>
										<table border="0" cellpadding="3" cellspacing="0" class="TableEditReg"  style="width: 100%;">
											<tr>
												<th>Codigo: </th>
												<th>Inst. Ejecutora</th>
												<th></th>
											</tr>
											<tr>
												<td id="l_cod_proy"></td>
												<td id="l_inst_eje"></td>
												<td></td>
											</tr>
											<tr>
												<th colspan="6">Nombre Proyecto:</th>												
											</tr>
											<tr>
												<td colspan="6" id="l_nom_proy"></td>												
											</tr>
											<tr>												
												<th style="height: 28px; font-weight: bold; width: 10%;">Año</th>
												<th style="height: 28px; font-weight: bold; width: 10%;">Entregable</th>
												<th style="height: 28px; font-weight: bold; width: 23%;">
													Periodo de Referencia:  												
												</th>
												<th style="height: 28px; font-weight: bold; width: 35%;">Monto Desembolso</th>
												<th style="height: 28px; font-weight: bold; width: 5%;"></th>
											</tr>
											<?php 
												$codProy = '';
												$nomProy = '';
												$ejecutorProy = '';
											?>											
											<?php while ($row = mysqli_fetch_array($rs)) { ?>
											<?php
												if (empty($codProy)) {
													$codProy = $row['codigo'];	
													$nomProy = $row['nombre'];
													$ejecutorProy = $row['ejecutor'];
												} 
											?>
											<tr>
																							
												<td style="height: 28px;">
													<?php echo $row['anio'];?>
												</td>
												<td style="height: 28px;">
													<?php echo $row['entregable'];?>
												</td>
												<td style="height: 28px;">
													<?php echo $row['periodo'];?>
												</td>
												<td style="height: 28px;">
													<input type="text" name="monto[]" value="<?php echo $row['monto'];?>" class="num_monto" />
													<input type="hidden" name="anio[]" value="<?php echo $row['anio'];?>" />
													<input type="hidden" name="mes[]" value="<?php echo $row['mes'];?>" />
												</td>
												<td style="height: 28px;">
													
												</td>												
											</tr>
											<?php } ?>
										</table>
										<input type="hidden" name="proy"  id="proy" value="<?php echo $id;?>" />
										<input type="hidden" name="vs" id="vs" value="<?php echo $version;?>" />
									</fieldset>
								</td>
							</tr>



	
    <tr>
								<td colspan="6">&nbsp;</td>
							</tr>
</table>
						
    

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#l_cod_proy').html("<?php echo $codProy; ?>");
	$('#l_inst_eje').html("<?php echo $ejecutorProy;?>");
	$('#l_nom_proy').html("<?php echo $nomProy;?>");

	$(".num_monto").numeric().pasteNumeric();
	
});											
	function btnGuardar_Clic()
	{
		 <?php $ObjSession->AuthorizedPage(); ?>

		var BodyForm = $("#FormData").serialize() ;		
		var req = Spry.Utils.loadURL("POST", "ejec_desemb_entregable_process.php?action=<?php echo md5("ajax_edit");?>", true, MySuccessGuardar, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

		return false;
	}

	function MySuccessGuardar(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var vs = respuesta.substring(0,6);
		alert(respuesta.replace(vs,""));
		

		btnEditar_Clic($('#proy').val(),$('#vs').val(), '<?php echo md5('editar');?>');
	  } else {
		  alert(respuesta);
	  }

	}





	   
  </script>
					</div>
					
  <?php if($view=='') { ?>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
<?php } ?>
