<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLEjecutor.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$accion = $objFunc->__GET('accion');

$row = 0;

$objFunc->SetSubTitle("Cuenta Bancaria - Editar Registro");
$sURL = "process_ctas.php?action=" . md5("ajax_edit");

$idInst = $objFunc->__GET('idInst');
$idCta = $objFunc->__GET('idCta');

$objEjec = new BLEjecutor();

$row = $objEjec->SeleccionarCuenta($idInst, $idCta);
$objEjec = NULL;
?>
<?php if($objFunc->__QueryString()=='') {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    $objFunc->SetTitle("Instituciones - Contactos");
	$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../css/template.css" rel="stylesheet" media="all" />
<script src="../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />
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
    <script src="../../jquery.ui-1.5.2/jquery.maskedinput.js"
						type="text/javascript"></script>
					<script src="../../jquery.ui-1.5.2/jquery.numeric.js"
						type="text/javascript"></script>
					<br />
					<div id="toolbar" style="height: 4px;" class="BackColor">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="9%">
									<button value="Guardar" class="Button" id="btnGuardar" onclick="guardarCuentaBancaria(); return false;">Guardar</button></td>
								<td width="22%">
									<button class="Button" onclick="spryPopupDialog01.displayPopupDialog(false); return false;" value="Cancelar" style="white-space: nowrap;">Cerrar y Volver</button></td>
								<td align="center"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
					<table width="100%" border="0" cellpadding="0" cellspacing="2"
						class="TableEditReg">
						<tr>
							<td width="1%">&nbsp;</td>
							<td colspan="6">
								<input name="idInst" type="hidden" class="CtaBancoEdit" id="idInst" value="<?php echo($idInst); ?>" /> 
								<input name="idCta" type="hidden" class="CtaBancoEdit" id="idCta" value="<?php echo($idCta); ?>" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td width="20%"><b>Entidad Financiera</b></td>
							<td colspan="5">
								<select name="cbobanco" class="CtaBancoEdit" id="cbobanco" style="width: 200px;">
							        <?php
							        $OjbTab = new BLTablasAux();
							        $rs = $OjbTab->ListaBancos();
							        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['cod_bco']);
							        ?>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td nowrap="nowrap"><b>Tipo de Cuenta</b></td>
							<td colspan="5">
								<select name="cbotipocta" id="cbotipocta" style="width: 150px;" class="CtaBancoEdit">
					  		  		<?php
					                $OjbTab = new BLTablasAux();
					                $rs = $OjbTab->ListaTipoCuentas();
					                $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['tip_cta']);
					                ?>
							    </select>
	    					</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td nowrap="nowrap"><b>Nro Cuenta</b></td>
							<td colspan="5">
								<input name="txtnrocuenta" type="text" class="CtaBancoEdit" id="txtnrocuenta" value="<?php echo($row['nrocuenta']); ?>" size="25" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><b>Moneda</b></td>
							<td colspan="5">
								<select name="cbomoneda" class="CtaBancoEdit" id="cbomoneda" style="width: 120px;">
									<?php
					                $OjbTab = new BLTablasAux();
					                $rs = $OjbTab->ListaTipoMoneda();
					                $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['cod_mon']);
					                ?>
        						</select>
        					</td>
						</tr>
					</table>
					<br />
					<script language="javascript">
	function guardarCuentaBancaria()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	

	if( $('#cbobanco.CtaBancoEdit').val()=="" ) {alert("No ha seleccionado la Entidad Finaciera"); return false;}
	if( $('#cbotipocta.CtaBancoEdit').val()=="" ) {alert("No ha seleccionado el Tipo de Cuenta"); return false;}
	if( $('#txtnrocuenta.CtaBancoEdit').val()=="" ) {alert("No ha ingresado el Número de Cuenta"); return false;}
    if( $('#cbomoneda.CtaBancoEdit').val()=="" ) {alert("No ha seleccionado la Moneda"); return false;}	

	 var BodyForm = $("#FormData .CtaBancoEdit").serialize() ;
	 var req = Spry.Utils.loadURL("POST", "<?php echo ($sURL); ?>", true, MySuccessGuardarCtaBancaria, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	
	return false;
	}
  
  function MySuccessGuardarCtaBancaria(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	
		alert(respuesta.replace(ret,"")); 
		LoadCuentasBancarias();
		spryPopupDialog01.displayPopupDialog(false);	
	}
	else
	{alert(respuesta);}  
  }
  </script>
  <?php if($objFunc->__QueryString()=='') {?>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
<?php } ?>