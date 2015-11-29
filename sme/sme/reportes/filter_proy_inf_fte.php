<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
error_reporting("E_PARSE");

require (constant('PATH_CLASS') . "BLInformes.class.php");
require (constant('PATH_CLASS') . "BLFuentes.class.php");

$objProy = new BLProyecto();
$idProy = "___";
$ObjSession->VerifyProyecto();

$row = 0;
if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
    $row = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
}

?>


<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Fuentes de Financiamiento</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
 <?php } ?>
 

<div style="width: 580px;">
			<table width="530" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="middle">
						<fieldset id="proyecto" style="font-size: 10px;">
							<legend>Busqueda de Informes</legend>
							<table width="100%" border="0" cellpadding="0" cellspacing="2"
								class="TableEditReg">
								<tr>
									<td width="105" height="21" align="left" valign="middle"><strong>Proyecto</strong>
									</td>
									<td colspan="2" align="left" valign="bottom" nowrap="nowrap"><input
										name="txtCodProy" type="text" id="txtCodProy" size="16"
										style="text-align: center;" readonly="readonly"
										value="<?php echo($row['t02_cod_proy']);?>" /> <input
										type="image" name="btnBuscar" id="btnBuscar"
										src="../../img/gosearch.gif" width="14" height="15"
										class="Image" onclick="Buscarproyectos(); return false;"
										title="Seleccionar Proyectos" /> <input name="txtNomejecutor"
										type="text" readonly="readonly" id="txtNomejecutor" size="50"
										value="<?php echo( $row['t01_nom_inst']);?>" /></td>
								</tr>
								<tr>
									<td height="35" align="left" valign="middle"><strong>Titulo del
											Proyecto</strong></td>
									<td colspan="2" align="left" valign="middle" nowrap="nowrap"><textarea
											name="txtNomproyecto" cols="70" rows="2" readonly="readonly"
											id="txtNomproyecto">
	                					<?php echo($row['t02_nom_proy']);?>
	                				</textarea> <strong> <input name="cboversion"
											type="hidden" id="cboversion" value="1" /> <input
											name="txtidInst" type="hidden" id="txtidInst"
											value="<?php echo($row['t01_id_inst']);?>" />
									</strong></td>
								</tr>
								<tr>
									<td height="23" valign="middle" nowrap="nowrap"><strong>Informe
											Financ.</strong></td>
									<td width="226" align="left" valign="middle" nowrap="nowrap"><select
										name="cboInforme" id="cboInforme" style="width: 220px;">
											<option value="" selected="selected"></option>
								<?php
        $idNumMesInf = $objFunc->NumeroMes($_POST['idAnio'], $_POST['idMes']);
        $objFinanc = new BLInformes();
        $Rs = $objFinanc->InformeFinancieroListado($row['t02_cod_proy']);
        $objFunc->llenarComboGroupII($Rs, "nummes", "descripcion", $idNumMesInf, 'anio');
        $objFinanc = NULL;
        ?>
			                		</select></td>
									<td width="286" rowspan="2" align="right" valign="middle"
										nowrap="nowrap"><span class="Button">
											<button onclick="NuevoReporte(); return false;" value="Nuevo">
												Ver Reporte</button>
									</span></td>
								</tr>
								<tr>
									<td height="30" valign="middle" nowrap="nowrap"><strong> Fuente
											Financ.</strong></td>
									<td align="left" valign="middle" nowrap="nowrap"><select
										name="cboFtesFinanc" id="cboFtesFinanc" style="width: 220px;">
								<?php
        $objFte = new BLFuentes();
        $Rs = $objFte->ContactosListado($row['t02_cod_proy']);
        $objFunc->llenarCombo($Rs, "t01_id_inst", "t01_sig_inst", $_POST['idFte']);
        $objFte = NULL;
        ?>
									</select></td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
			<script language="javascript" type="text/javascript">

function Buscarproyectos()
{
	var arrayControls = new Array();
	arrayControls[0] = "ctrl_idinst=txtidInst";
	arrayControls[1] = "ctrl_idproy=txtCodProy";
	arrayControls[2] = "ctrl_idversion=cboversion";
	arrayControls[3] = "ctrl_ejecutor=txtNomejecutor";
	arrayControls[4] = "ctrl_proyecto=txtNomproyecto";
	arrayControls[5] = "evaljs=LoadInformesFinanc";
	
	var params = "?" + arrayControls.join("&"); 
	var sUrl = "<?php echo(constant("DOCS_PATH"));?>sme/proyectos/datos/lista_proyectos.php" + params;
	window.open(sUrl,"BuscaProy", "width=603, height=400,menubar=no, scrollbars=yes, location=no, resizable=no, status=no",true);
}

function NuevoReporte()
{
	var arrayControls = new Array();
		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
		arrayControls[1] = "NumMes=" + $('#cboInforme').val();
		arrayControls[2] = "idFte=" + $('#cboFtesFinanc').val();
	var params = arrayControls.join("&"); 
	var sID = "<?php echo($objFunc->__Request('ReportID'));?>" ;
	showReport(sID, params);
}

function LoadFuentesFinanc()
{
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/anexos/");?>fuentes_process.php?action=<?php echo(md5("ajax_lista_fte"))?>" ;

	var arrayControls = new Array();
		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
		arrayControls[1] = "idVersion=" + $('#cboversion').val();
	var BodyForm = arrayControls.join("&"); 
	$('#cboFtesFinanc').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, FuentesFinancSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" } });
}
function FuentesFinancSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboFtesFinanc').html(respuesta);
  $('#cboFtesFinanc').focus();
}

function LoadInformesFinanc()
{
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/informes/");?>inf_financ_process.php?action=<?php echo(md5("ajax_lista_informes_financ"))?>" ;

	var arrayControls = new Array();
		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
	var BodyForm = arrayControls.join("&"); 
	$('#cboInforme').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, InformeFinancSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" } });
	LoadFuentesFinanc();
}
function InformeFinancSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboInforme').html(respuesta);
  $('#cboInforme').focus();
}

</script>
		</div>
    
<?php if($view=='') { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>
