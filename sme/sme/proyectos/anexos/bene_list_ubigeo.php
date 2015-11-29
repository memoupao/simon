<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "BLPOA.class.php");
require_once (constant('PATH_CLASS') . "BLBene.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__Request('action');
$row = 0;

$idProy = $objFunc->__Request('idProy');
$dpto = $objFunc->__Request('dpto');
$prov = $objFunc->__Request('prov');
$dist = $objFunc->__Request('dist');
$case = $objFunc->__Request('case');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<?php
    $objFunc->SetTitle("Proyectos - Padrón de Beneficiarios");
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
  
<div id="divListaBenef">
						<table width="550" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<th width="82%" align="left" class="">
									<table width="560" border="0" cellspacing="2"
										class="TableEditReg">
										<tr
											style="font: Verdana, Geneva, sans-serif; font-weight: normal;">
											<td width="6" height="28">&nbsp;</td>
											<td width="120" align="center"><strong>Departamento</strong></td>
											<td width="120" align="center"><strong>Provincia</strong></td>
											<td width="120" align="center"><strong>Distrito</strong></td>
											<td width="120" align="center"><strong>Caserio</strong></td>
											<td width="60" rowspan="2" align="right" nowrap="nowrap">
												<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar participacion en los talleres de Capacitación"  onclick="Guardar_SelecBenef();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
          Guardar </div osktgui--> <input type="button" value="Guardar"
												title="Guardar participacion en los talleres de Capacitación"
												onclick="Guardar_SelecBenef();" class="btn_save_custom" />

											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td><select name="cbodpto" id="cbodpto" style="width: 120px;"
												onchange="LoadProv();" class="Benef">
          <?php
        $objBene = new BLBene();
        $rsDpto = $objBene->ListaUbigeoDpto($idProy);
        $dpto1 = $objFunc->llenarComboI($rsDpto, 'iddpto', 'dpto', $dpto);
        if ($dpto1 != $dpto && $view == "") {
            $dpto = $dpto1;
            $prov = '';
            $dist = '';
            $case = '';
        }
        ?>
          </select></td>
											<td><select name="cboprov" id="cboprov" style="width: 120px;"
												onchange="LoadDist();" class="Benef">
          <?php
        $objBene = new BLBene();
        $rsDpto = $objBene->ListaUbigeoProv($idProy, $dpto);
        $prov1 = $objFunc->llenarComboI($rsDpto, 'idprov', 'prov', $prov);
        // if($prov1!=$prov && $view==""){$prov=$prov1;$dist='';$case='';}
        ?>
          </select></td>
											<td><select name="cbodist" id="cbodist" style="width: 120px;"
												onchange="ReloadBeneficiarios();" class="Benef">
													<option></option>
          <?php
        $objTablas = new BLTablasAux();
        $rsProv = $objTablas->ListaDistritos($dpto, $prov);
        $objFunc->llenarComboI($rsProv, 'codigo', 'descripcion', $dist);
        // $rsDpto = $objBene->ListaUbigeoDist($idProy, $dpto, $prov);
        // = $objFunc->llenarComboI($rsDpto,'iddist','dist',$dist);
        // if($dist1!=$dist && $view==""){$dist=$dist1;$case='';}
        ?>
          </select></td>
											<td><select name="cbocase" id="cbocase" style="width: 120px;"
												onchange="ReloadBeneficiarios();" class="Benef">
													<option></option>
          <?php
        // $objBene = new BLBene();
        // $rsDpto = $objBene->ListaUbigeoCaserio($idProy, $dpto, $prov, $dist);
        // $case = $objFunc->llenarComboI($rsDpto,'idcase','caserio', $case );
        
        $rsCase = $objTablas->ListaCaserios($dpto, $prov, $dist);
        $objFunc->llenarComboI($rsCase, 'codigo', 'descripcion', $case);
        
        ?>
        </select></td>
										</tr>
									</table>
								</th>
							</tr>
						</table>
						<div class="TableGrid"
							style="overflow: auto; max-width: 580px; max-height: 320px;">
							<table width="550" border="0" cellpadding="0" cellspacing="0"
								id="3">
								<tbody class="data" bgcolor="#eeeeee">
									<tr>
										<td align="center" valign="middle">&nbsp;</td>
										<td height="26" align="center" valign="middle"><strong>DNI</strong></td>
										<td height="26" align="center" valign="middle"><strong>Apellidos
												y </strong><strong>Nombres</strong></td>
										<td align="center" valign="middle"><strong>Direccion</strong><strong></strong></td>
									</tr>

								</tbody>
								<tbody class="data">
      <?php
    $objBene = new BLBene();
    $irsBenef = $objBene->ListaBeneficiarioUbigeo($idProy, $dpto, $prov, $dist, $case);
    
    while ($rb = mysqli_fetch_assoc($irsBenef)) {
        ?>
      <tr>
										<td width="4%" align="center" valign="middle"><input
											type="checkbox" name="beneficarios[]" id="beneficarios[]"
											value="<?php echo($rb['t11_cod_bene']);?>" class="Benef" /></td>
										<td width="14%" align="center" valign="middle"><?php echo($rb['t11_dni']);?>&nbsp;</td>
										<td width="43%" align="center" valign="middle"><?php echo(  $rb['nombres']);?>&nbsp;</td>
										<td colspan="<?php echo($rsub['numtema']);?>" align="center"
											valign="top"><?php echo(  $rb['t11_ciudad']);?>&nbsp;</td>
									</tr>

      <?php } ?>
    </tbody>
							</table>
						</div>
						<script language="javascript" type="text/javascript">
 
function ReloadBeneficiarios()
{
var BodyForm = "action=<?php echo(md5("ListaBenef"));?>&idProy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.Benef').val() +"&prov=" + $('#cboprov.Benef').val() +"&dist=" + $('#cbodist.Benef').val() +"&case=" + $('#cbocase.Benef').val() ;
var sURL = "<?php echo( constant("PATH_SME")."proyectos/anexos/")?>bene_list_ubigeo.php";
$('#divListaBenef').html("<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
var req = Spry.Utils.loadURL("POST", sURL, true, SuccessReloadBeneficiarios, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });	
}

function SuccessReloadBeneficiarios		(req)
{
   var respuesta = req.xhRequest.responseText;
   $("#divListaBenef").html(respuesta);
   return;
}
 

function LoadProv()
{
	var BodyForm = "dpto=" + $('#cbodpto.Benef').val();
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/anexos/");?>amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
	$('#cboprov.Benef').html('<option> Cargando ... </option>');
	$('#cbodist.Benef').html('');
	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}
function ProvSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboprov.Benef').html(respuesta);
  $('#cboprov.Benef').focus();
}
function LoadDist()
{
	var BodyForm = "dpto=" + $('#cbodpto.Benef').val() + "&prov=" + $('#cboprov.Benef').val() ;
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/anexos/");?>amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist.Benef').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}
function DistSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist.Benef').html(respuesta);
  $('#cbodist.Benef').focus();
}
function LoadCase(valor)
{
	var BodyForm = "dpto=" + $('#cbodpto.Benef').val() + "&prov=" + $('#cboprov.Benef').val()+ "&dist=" + $('#cbodist.Benef').val() + "&case=" + valor;
	var sURL = "<?php echo(constant("PATH_SME")."proyectos/anexos/");?>amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
	$('#cbocase.Benef').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}
function CaseSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbocase.Benef').html(respuesta);
  $('#cbocase.Benef').focus();
}

function Guardar_SelecBenef()
{
	var selecioandos = $('#FormData .Benef').serialize();
	SeleccionarBeneficiariosOK(selecioandos) ;
	spryPopupDialog01.displayPopupDialog(false);
	return true;
}

</script>
					</div>

<?php
if($idProy=="")
{
?>

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