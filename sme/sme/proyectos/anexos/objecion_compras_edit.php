<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php

require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$accion = $objFunc->__GET('accion');
$view = $objFunc->__GET('mode');
$row = 0;
$objML = new BLMarcoLogico();
$objPresup = new BLPresupuesto();
$HC = new HardCode();

$idVersion = 1;

if ($view == md5("ajax_edit") || $view == md5("ajax_view")) {
    $objFunc->SetSubTitle("No Objecion de Compras / Editar Registro");
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('id');
    $objProy = new BLProyecto();

    $row = $objProy->NoObjecionCompra_Seleccionar($idProy, $id);

    $objProy = NULL;
} else {
    $objFunc->SetSubTitle("No Objecion de Compras / Nuevo Registro");
    $idProy = $objFunc->__GET('idProy');
}

?>

<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
    $objFunc->SetTitle("Proyectos - No Objecion de Compras");
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

					<script>
  function DeshabilitarAll()
  {
	$("#btnGuardarOC").attr("disabled","disabled");
    $("#t02_sco_noc").attr("disabled","disabled");
 	$("#cboComponente").attr("disabled","disabled");
	$("#cboActividad").attr("disabled","disabled");
	$("#cboSubActividad").attr("disabled","disabled");
	$("#cboCatGastos").attr("disabled","disabled");
	$("#t02_spa_noc").attr("disabled","disabled");
	$("#t02_imp_noc").attr("disabled","disabled");
	$("#t02_ccp_noc_1").attr("disabled","disabled");
	$("#t02_ccp_noc_2").attr("disabled","disabled");
	$("#t02_amt_noc_1").attr("disabled","disabled");
	$("#t02_amt_noc_2").attr("disabled","disabled");
	$("#t02_cmf_noc_1").attr("disabled","disabled");
	$("#t02_cmf_noc_2").attr("disabled","disabled");
	$("#t02_pro_noc_1").attr("disabled","disabled");
	$("#t02_pro_noc_2").attr("disabled","disabled");
	$("#t02_cop_noc_1").attr("disabled","disabled");
	$("#t02_cop_noc_2").attr("disabled","disabled");
	$("#t02_amf_noc_1").attr("disabled","disabled");
	$("#t02_amf_noc_2").attr("disabled","disabled");
	$("#t02_cmt_noc_1").attr("disabled","disabled");
	$("#t02_cmt_noc_2").attr("disabled","disabled");
	$("#t02_obs_noc").attr("disabled","disabled");

  }

  <?php
if ($view == md5("ajax_view")) {
    echo ("DeshabilitarAll();");
}
?>

  </script>



					<div id="toolbar" style="height: 8px;" class="BackColor">
						<table width="99%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%"><button class="Button"
										onclick="btnGuardar_Clic(); return false;" value="Guardar"
										id="btnGuardarOC">Guardar</button></td>
								<td width="9%"><button class="Button"
										onclick="btnCancelar_Clic(); return false;" value="Cancelar">
										Cancelar</button></td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="38%">
		  <?php
		  //var_dump($row['t02_env_rev']);
		  //var_dump($row['t02_estado']);
if ($view == md5("ajax_edit")) {
		
        if (($row['t02_env_rev'] != '1' && $row['t02_estado'] == '') || $row['t02_estado'] == 270) {
            ?>
			<button class="Button" onclick="btnRevision_Clic(); return false;"
										value="Revision">Enviar a Revisión</button>
		  <?php

}
        /*if ($row['t02_estado'] == 264) {*/
		if ($row['t02_estado'] == 267) {
            echo ('En Revisión');
        }
    }
    ?>

		  </td>
								<td width="40%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
					<div style="margin-top:10px;">
						<fieldset>
							<legend>
								<strong>No Objecion de Compra</strong>
							</legend>
							<table width="99%" border="0" cellpadding="0" cellspacing="2"
								class="TableEditReg">
								<tr>
									<td colspan="6">
      <?php

if ($id == "") {
        $sURL = "objecion_compras_process.php?action=" . md5("ajax_new");
    } else {
        $sURL = "objecion_compras_process.php?action=" . md5("ajax_edit");
    }
    ?>
      <input type="hidden" name="cod_proy" id="cod_proy"
										value="<?php echo($idProy); ?>" /> <input type="hidden"
										name="idVersion" id="idVersion"
										value="<?php echo($idVersion); ?>" /> <input type="hidden"
										name="txturlsave" id="txturlsave"
										value="<?php echo($sURL); ?>" /> <input type="hidden"
										name="t02_cod_proy" id="t02_cod_proy"
										value="<?php echo($row['proyecto']); ?>" /> <input
										type="hidden" name="txtidobjecioncompra"
										id="txtidobjecioncompra"
										value="<?php echo($row['codigo']); ?>" />
									</td>
								</tr>
								<tr height="10" colspan="6">
									<td width="125" height="36"><strong>Fecha de Solicitud:</strong></td>
									<td width="117" height="36"><input name="t02_fch_ped"
										type="text" id="t02_fch_ped" readonly="readonly"
										value="<?php if($id!=''){echo($row['t02_fch_ped']);}else {echo(date('d/m/Y'));} ?>"
										size="20" maxlength="20" /></td>
									<td width="207">&nbsp;</td>
									<td width="200" height="36">&nbsp;</td>
									<td width="120" align="left">&nbsp;</td>
									<td width="7" height="36" align="left">&nbsp;</td>
								</tr>

								<tr height="10" colspan="6">
									<td width="125" height="36"><strong>Solicitud de Compra</strong></td>
      <?php if($view == md5("ajax_edit")) { ?>
	  <td width="117" height="36"><input name="t02_sco_noc" readonly
										type="text" id="t02_sco_noc"
										value="<?php echo($row['t02_sco_noc']); ?>" size="20"
										maxlength="20" />
		<?php

} else {
        $objProy = new BLProyecto();
        $orden = $objProy->NoObjecionCompra_getNumero($idProy);
        $orden = $orden + 1;
        ?>


									<td width="117" height="36"><input name="t02_sco_noc" readonly
										type="text" id="t02_sco_noc" value="<?php echo($orden); ?>"
										size="20" maxlength="20" />

		<?php } ?>
      </td>
									<td width="207">&nbsp;</td>
									<td width="200" height="36">&nbsp;</td>
									<td width="120" align="left">&nbsp;</td>
									<td width="7" height="36" align="left">&nbsp;</td>
								</tr>
							</table>
							<fieldset>
								<legend>
									<strong>Partida Afectada</strong>
								</legend>
								<table width="764" border="0" cellpadding="0" cellspacing="2"
									class="TableEditReg">
									<tr>

										<td height="26"><strong>Componente</strong></td>
										<td height="26" colspan="5"><select name="cboComponente"
											id="cboComponente" style="width: 520px;"
											onchange="LoadActividad();">
												<option value="" selected="selected"></option>
        <?php
        $rsComp = $objML->ListadoCompNOC($idProy);
        $objFunc->llenarComboI($rsComp, "t08_cod_comp", "descripcion", $row["t02_com_noc"]);        
        $rsComp = NULL;
        ?>
      </select></td>
									</tr>
									<tr height="10">
										<td height="30"><strong>Producto</strong></td>
										<td height="30" colspan="5"><select name="cboActividad"
											id="cboActividad" style="width: 520px;"
											onchange="LoadSubActividad();">
												<option value="" selected="selected"></option>
          <?php
        $rsAct = $objML->ListadoActNOC($idProy, $row["t02_com_noc"]);
        $objFunc->llenarComboI($rsAct, "t09_cod_act", "descripcion", $row["t02_act_noc"]);
        $rsAct = NULL;
        ?>
          </select></td>
									</tr>
									<tr height="10">
										<td height="26"><strong>Actividad</strong></td>
										<td height="26" colspan="5"><select name="cboSubActividad"
											id="cboSubActividad" style="width: 520px;"
											onchange="LoadCatGastos();">
												<option value="" selected="selected"></option>
          <?php
        $rsSAct = $objML->ListadoSubActNOC($idProy, $row["t02_com_noc"], $row["t02_act_noc"]);
        $objFunc->llenarComboI($rsSAct, "subact", "descrip", $row["t02_sub_noc"]);
        $rsSAct = NULL;
        ?>
          </select></td>
									</tr>
									<tr height="10">
										<td width="136" height="30"><strong>Categoria de Gastos</strong></td>
										<td height="30" colspan="4"><select name="cboCatGastos"
											id="cboCatGastos" style="width: 250px;"
											onchange="LoadSaldo();">
												<option value="" selected="selected"></option>
              <?php
            $iRsCateg = $objPresup->ListadoCatNOC($idProy, $row["t02_com_noc"], $row["t02_act_noc"], $row["t02_sub_noc"]);
            $objFunc->llenarComboI($iRsCateg, "codcat", "desc_cat", $row["t02_par_noc"]);
            $iRsCateg = NULL;
            ?>
      </select></td>
									</tr>


								</table>
							</fieldset>
							<table width="99%" border="0" cellpadding="0" cellspacing="2"
								class="TableEditReg">

 	   			<?php
        $total_fuente = $objPresup->SaldoCategGasto($idProy, $row["t02_com_noc"], $row["t02_act_noc"], $row["t02_sub_noc"], $row["t02_par_noc"]);
        ?>

    <tr height="10">
									<td width="31%" height="36"><strong>Saldo en la Partida a
											Afectar </strong></td>
									<td width="14%" height="36"><input name="t02_spa_noc"
										type="text" id="t02_spa_noc"
										value="<?php echo( $total_fuente ); ?>" size="20" /></td>
									<td width="1%" align="left">&nbsp;</td>
									<td width="19%" height="36" align="left"><strong>Importe
											Solicitado</strong></td>
									<td width="13%" align="left" bgcolor="#553FFF"><input
										name="t02_imp_noc" type="text" id="t02_imp_noc"
										value="<?php echo($row['importe']); ?>" size="20" /></td>
									<td width="22%" height="36" align="left" bgcolor="#553FFF">&nbsp;</td>
								</tr>
								<tr height="10">
									<td height="37"><strong>Observación</strong></td>
									<td height="37" colspan="5"><textarea name="t02_obs_noc"
											id="t02_obs_noc" cols="100" rows="3"><?php echo($row['t02_obs_noc']); ?></textarea></td>
								</tr>
								<tr height="10">
									<td height="21" bgcolor="#553FFF">&nbsp;</td>
									<td height="21" align="center">&nbsp;</td>
									<td align="left">&nbsp;</td>
									<td height="21" align="left">&nbsp;</td>
									<td align="center" bgcolor="#553FFF">&nbsp;</td>
									<td height="21" align="left" bgcolor="#553FFF">&nbsp;</td>
								</tr>
								<tbody
									<?php if( $ObjSession->PerfilID == $HC->GP || $ObjSession->PerfilID == $HC->RA) {echo('');} else {echo('style="display:none;"');} ?>>
									<tr height="10">
										<td height="36" bgcolor="#553FFF"><strong>Cuadro Comparativo
												de Proveedores</strong></td>
										<td height="36" align="center"><strong>Si <input type="radio"
												name="t02_ccp_noc" id="t02_ccp_noc_1" value="S"
												<?php if($row['t02_ccp_noc'] =='S'){echo ' checked ';}?> />
												No <input type="radio" name="t02_ccp_noc" id="t02_ccp_noc_2"
												value="N"
												<?php if($row['t02_ccp_noc'] =='N'){echo ' checked ';}?> />
										</strong></td>
										<td align="left">&nbsp;</td>
										<td height="36" align="left"><strong>Cotizaciones de
												Proveedores</strong></td>
										<td align="center" bgcolor="#553FFF"><strong>Si <input
												type="radio" name="t02_cop_noc" id="t02_cop_noc_1" value="S"
												<?php if($row['t02_cop_noc'] =='S'){echo ' checked ';}?> />
												No <input type="radio" name="t02_cop_noc" id="t02_cop_noc_2"
												value="N"
												<?php if($row['t02_cop_noc'] =='N'){echo ' checked ';}?> />
										</strong></td>
										<td height="36" align="left" bgcolor="#553FFF">&nbsp;</td>
									</tr>
									<tr height="10">
										<td height="36"><strong>Aprobacion de Gestor de Proyectos</strong></td>
										<td height="36" align="center"><strong>Si <input type="radio"
												name="t02_amt_noc" id="t02_amt_noc_1" value="S"
												<?php if($row['t02_amt_noc'] =='S'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->GP) {echo('disabled');} ?> />
												No <input type="radio" name="t02_amt_noc" id="t02_amt_noc_2"
												value="N"
												<?php if($row['t02_amt_noc'] =='N'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->GP) {echo('disabled');} ?> />
      <?php if($ObjSession->PerfilID!=$HC->GP ) {echo('<input type="hidden" name="t02_amt_noc" value="'.$row['t02_amt_noc'].'">');} ?>

    </strong></td>
										<td align="left">&nbsp;</td>
										
										
										
										<td height="36" align="left"><strong>Responsable de Area</strong></td>
										<td align="center"><strong>Si <input type="radio"
												name="t02_cmt_noc" id="t02_cmt_noc_1" value="S"
												<?php if($row['t02_cmt_noc'] =='S'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
												No <input type="radio" name="t02_cmt_noc" id="t02_cmt_noc_2"
												value="N"
												<?php if($row['t02_cmt_noc'] =='N'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
<?php if($ObjSession->PerfilID!=$HC->RA) {echo('<input type="hidden" name="t02_cmt_noc" value="'.$row['t02_cmf_noc'].'">');} ?>
    </strong></td>
										
										
										
										
										<?php /* ?>
										<td height="36" align="left"><strong>Aprobacion de Monitor
												Financiero</strong></td>
										<td align="center"><strong>Si <input type="radio"
												name="t02_amf_noc" id="t02_amf_noc_1" value="S"
												<?php if($row['t02_amf_noc'] =='S'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->GP) {echo('disabled');} ?> />
												No <input type="radio" name="t02_amf_noc" id="t02_amf_noc_2"
												value="N"
												<?php if($row['t02_amf_noc'] =='N'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->GP) {echo('disabled');} ?> />
    <?php if($ObjSession->PerfilID!=$HC->GP) {echo('<input type="hidden" name="t02_amf_noc" value="'.$row['t02_amf_noc'].'">');} ?>
    </strong></td>
    									<?php */ ?>
										<td height="36" align="left">&nbsp;</td>
									</tr>
									<?php /*?>
									<tr height="10">
										
										<td height="36"><strong>Coordinador de Monitoreo Financiero</strong></td>
										<td height="36" align="center"><strong>Si <input type="radio"
												name="t02_cmf_noc" id="t02_cmf_noc_1" value="S"
												<?php if($row['t02_cmf_noc'] =='S'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
												No <input type="radio" name="t02_cmf_noc" id="t02_cmf_noc_2"
												value="N"
												<?php if($row['t02_cmf_noc'] =='N'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
    <?php if($ObjSession->PerfilID!=$HC->RA) {echo('<input type="hidden" name="t02_cmf_noc" value="'.$row['t02_cmf_noc'].'">');} ?>
    </strong></td>
    									    									
										<td align="left">&nbsp;</td>
										<td height="36" align="left"><strong>Coordinador de Monitoreo
												Técnico</strong></td>
										<td align="center"><strong>Si <input type="radio"
												name="t02_cmt_noc" id="t02_cmt_noc_1" value="S"
												<?php if($row['t02_cmt_noc'] =='S'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
												No <input type="radio" name="t02_cmt_noc" id="t02_cmt_noc_2"
												value="N"
												<?php if($row['t02_cmt_noc'] =='N'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
<?php if($ObjSession->PerfilID!=$HC->RA) {echo('<input type="hidden" name="t02_cmt_noc" value="'.$row['t02_cmf_noc'].'">');} ?>
    </strong></td>
										<td height="36">&nbsp;</td>
									</tr>
									<?php */ ?>
									
									<tr height="10">
										<td height="37"><strong>Respuesta a Ejecutor: Procede</strong></td>
										<td height="37" align="center"><strong>Si <input type="radio"
												name="t02_pro_noc" id="t02_pro_noc_1" value="S"
												<?php if($row['t02_pro_noc'] =='S'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
												No <input type="radio" name="t02_pro_noc" id="t02_pro_noc_2"
												value="N"
												<?php if($row['t02_pro_noc'] =='N'){echo ' checked ';}?>
												<?php if($ObjSession->PerfilID!=$HC->RA) {echo('disabled');} ?> />
<?php if($ObjSession->PerfilID!=$HC->RA ) {echo('<input type="hidden" name="t02_pro_noc" value="'.$row['t02_pro_noc'].'">');} ?>
    </strong>

										<td height="37">

										<td height="37">

										<td height="37">

										<td height="37">

									</tr>
									<tr height="10">
										<td height="26">&nbsp;</td>
										<td height="26" colspan="5">

									</tr>
									<tr height="10">
										<td height="20" colspan="6"><iframe id="ifrmUploadFile"
												name="ifrmUploadFile" style="display: none;"></iframe></td>
									</tr>
								</tbody>
								<tr height="10">
									<td colspan="6">
    <?php if($view != md5("ajax_new")) { ?>
    <fieldset>
											<legend>Anexos de la No Objeción de Compras</legend>
											<div id="divAnexos" style="width: 100%;"></div>
										</fieldset>
     <?php } ?>
    </td>
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

	$(function() {

		$("input[name='t02_cmt_noc']").click( function( event )
			{
			var sn = $('input[name=t02_cmt_noc]:checked').val();
			if (sn=="S"){
			$("#t02_pro_noc_1").attr("checked", "checked");
			}

			if (sn=="N"){
			$("#t02_pro_noc_2").attr("checked", "checked");
			}

			}) }
		);

	function LoadActividad()
	{
		var BodyForm = "comp=" + $('#cboComponente').val()+"&proy=" + $('#cod_proy').val()+"&idVersion=" + $('#idVersion').val();
		var sURL = "objecion_compras_process.php?action=<?php echo(md5("lista_actividades"))?>" ;

		//$('#cboComponente').html('<option> Cargando ... </option>');
		$('#cboActividad').html('');
		$('#cboSubActividad').html('');
		$('#cboCatGastos').html('');
		$('#t02_spa_noc').val('');


		var req = Spry.Utils.loadURL("POST", sURL, true, ActvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, "": "" });
	}
	function ActvSuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  $('#cboActividad').html(respuesta);
	  $('#cboActividad').focus();

	}

	function LoadSubActividad()
	{
		var BodyForm = "comp=" + $('#cboComponente').val()+"&proy=" + $('#cod_proy').val()+"&idVersion=" + $('#idVersion').val()+"&idAct=" + $('#cboActividad').val();
		var sURL = "objecion_compras_process.php?action=<?php echo(md5("lista_sub_actividades"))?>" ;

		//$('#cboComponente').html('<option> Cargando ... </option>');
		$('#cboSubActividad').html('');
		$('#cboCatGastos').html('');
		$('#t02_spa_noc').val('');

		var req = Spry.Utils.loadURL("POST", sURL, true, SubActvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, "": "" });
	}
	function SubActvSuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  $('#cboSubActividad').html(respuesta);
	  $('#cboSubActividad').focus();
	}

	function LoadCatGastos()
	{
		var BodyForm = "comp=" + $('#cboComponente').val()+"&proy=" + $('#cod_proy').val()+"&idVersion=" + $('#idVersion').val()+"&idAct=" + $('#cboActividad').val()+"&idSAct=" + $('#cboSubActividad').val();
		var sURL = "objecion_compras_process.php?action=<?php echo(md5("lista_cat_gastos"))?>" ;

		$('#cboCatGastos').html('');
		$('#t02_spa_noc').val('');
		var req = Spry.Utils.loadURL("POST", sURL, true, CatGastosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, "": "" });
	}
	function CatGastosSuccessCallback(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  $('#cboCatGastos').html(respuesta);
	  $('#cboCatGastos').focus();
	}

	function btnGuardar_Clic()
	{
	 $("#t02_spa_noc").removeAttr('disabled');
	 // if( $('#t02_sco_noc').val()=="" )
	 // {	alert("Ingrese la Solicitud de Compra");
	 	// $('#t02_sco_noc').focus() ;
	 	// return false;
	 // }
	 if( $('#t02_spa_noc').val()=="" )
	 {	alert("Ingrese la Partida a afectar");
	 	$('#t02_spa_noc').focus() ;
	 	return false;
	 }
	 if( $('#t02_imp_noc').val()=="" )
	 {	alert("Ingrese el importe solicitado");
	 	$('#t02_imp_noc').focus() ;
	 	return false;
	 }
	 if( $('#t02_obs_noc').val()=="" )
	 {	alert("Ingrese la observacion");
	 	$('#t02_imp_co').focus() ;
	 	return false;
	 }

	  if( $('#t02_imp_co').val()=="" )
	 {	alert("Ingrese el importe");
	 	$('#t02_imp_co').focus() ;
	 	return false;
	 }

	 if( $('#t02_imp_noc').val() < 7000 )
	 {	alert("El importe solicitado es menor a S/. 7,000");
	 	$('#t02_imp_noc').focus() ;
	 	return false;
	 }

	 if ($("#TotalAnexos").val() == 0) { alert("No se anexaron documentos"); return false;  }
	 var urlPost = $('#txturlsave').val();

	 $('#FormData').attr({target: "ifrmUploadFile"});
	 $('#FormData').attr({action: urlPost});
	 $('#FormData').attr({encoding: "multipart/form-data"});
	 $('#FormData').submit();
	 $('#FormData').attr({target: "_self"});

	 return false;

	}
	function btnRevision_Clic()
	{
		if(confirm("Estas seguro de enviar el registro a revision?")) {
			var BodyForm = "idProy="+$('#cod_proy').val()+"&id="+$('#txtidobjecioncompra').val()+"&num="+$('#t02_sco_noc').val();
			var sURL = "objecion_compras_process.php?action=<?php echo(md5("ajax_envrev"))?>";
			var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallEnvRevision, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
		}
	}

	function MySuccessCallEnvRevision(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito") {
			alert(respuesta.replace(ret,""));
			ReloadLista();
		}
		else {
			alert(respuesta);
		}
	}

	function ReturnGuardar(arg, msg)
	{
		if(arg)
		{
			alert(msg) ;
			ReloadLista();
		}
		else
		{
			alert(msg) ;
		}

	}

	</script>

					<script>
	LoadAnexos();

    function LoadAnexos()
		{
		var url = "objecion_compras_anexos.php?action=<?php echo(md5("lista"));?>&idProy=<?php echo($idProy);?>&id=<?php echo($id); ?>&accion=<?php echo($accion)?>";
		loadUrlSpry("divAnexos",url);
		}



	function LoadSaldo()
	{
		var idProy		=	$("#cod_proy").attr("value");
		var idComp		=	$("#cboComponente").attr("value");
		var idAct		=	$("#cboActividad").attr("value");
		var idSubAct	=	$("#cboSubActividad").attr("value");
		var idCat		=	$("#cboCatGastos").attr("value");

		var sURL = "objecion_compras_process.php?&action=<?php echo(md5("ajax_gasto"))?>";
		var BodyForm = "idProy="+idProy+"&idComp="+idComp+"&idAct="+idAct+"&idSubAct="+idSubAct+"&idCat="+idCat;

		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadSaldo, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
	}

	function SuccessLoadSaldo(req)
	{
	var strNumero = jQuery.trim(req.xhRequest.responseText) ;

	$('#t02_spa_noc').val(strNumero) ;
	}

	$("#t02_spa_noc").attr("disabled","disabled");

	</script>

<?php if($objFunc->__QueryString()=="") { ?>
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

