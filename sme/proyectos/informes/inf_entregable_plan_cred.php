<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLBene.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$idEntregable = $objFunc->__Request('idEntregable');
$sub = $objFunc->__Request('cbosub');

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Plan de Creditos</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <table width="780" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="82%" align="left" class=""><span
					style="font-weight: bold;">Avance sobre Plan de Creditos</span></th>
				<th width="8%" rowspan="2" align="center" class="">
					<input type="button" value="Refrescar"
					title="Refrescar los Avances de Asistencia Técnica"
					onclick="ReLoadPlanesCred();" class="btn_save_custom" />
				</th>
				<th width="10%" rowspan="2" align="center" valign="middle">
					<input type="button" value="Guardar"
					title="Guardar participacion en los talleres de Capacitación"
					onclick="Guardar_PlanCred();" class="btn_save_custom btn_save" />
				</th>
			</tr>
			<tr>
				<th align="left" class="">
					<table border="0" cellspacing="2" class="TableEditReg">
						<tr>
							<td><strong style="font-size: 11px; color: #003;">Actividad</strong></td>
							<td colspan="4"><select name="cbosub" id="cbosub"
								style="width: 550px;" onchange="ReLoadPlanesCred();"
								class="PlanCred">
									<option value="" style="font-size: 10px; color: #036;"
										selected="selected">-- Seleccionar Actividad ---</option>
             <?php
            $objPOA = new BLPOA();
            $ver = $objPOA->UltimaVersionPoa($idProy, $idAnio);
            $rsSub = $objPOA->PlanCred_Listado($idProy, $ver);

            $objFunc->llenarComboI($rsSub, "codigo", "subactividad", $sub);
            ?>

            </select></td>
						</tr>

					</table>
				</th>
			</tr>
		</table>

		<div class="TableGrid"
			style="overflow: auto; max-width: 780px; max-height: 350px;">
			<table border="0" cellspacing="0" cellpadding="0" width="770">
				<tbody class="data" bgcolor="#eeeeee">
					<tr>
						<td align="center" valign="middle"><strong>Datos del Beneficiario
						</strong></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td width="12%" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle"><strong> </strong></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
					<tr>
						<td width="10%" align="center" valign="middle"><strong>DNI</strong></td>
						<td align="center" valign="middle"><strong>Apellidos y </strong><strong>Nombres</strong></td>
						<td align="center" valign="middle"><strong> </strong><strong>Monto
								del Credito Recibido</strong><strong></strong></td>
						<td align="center" valign="middle"><strong>Numero de Cuotas a
								Pagar</strong><strong> </strong></td>
						<td align="center" valign="middle"><strong>Monto Pagado Acumulado</strong><strong></strong></td>
						<td align="center" valign="middle"><strong>Monto Pagado en el
								Trimestre</strong><strong></strong></td>
						<td align="center" valign="middle"><strong>Saldo x Pagar</strong><strong></strong></td>
					</tr>

				</tbody>
				<tbody class="data">
    <?php

    $objInf = new BLInformes();
    $iRsBenef = $objInf->InfTrim_Credito_Lista($idProy, $idAnio, $idEntregable, $sub);
    while ($rb = mysqli_fetch_assoc($iRsBenef)) {
        $valor = $rb['trim_pagado'];
        $saldo = ($rb['plan_monto'] - ($rb['pagado_antes'] + $rb['trim_pagado']));
        ?>
    <tr>
						<td width="10%" align="center" valign="middle"><?php echo($rb['t11_dni']); ?></td>
						<td width="30%" align="left"><input name="txtbenef[]"
							type="hidden" id="txtbenef[]"
							value="<?php echo($rb['codigo']); ?>" class="PlanCred" /> <span
							style="min-width: 150px;"><?php echo($rb['nombres']); ?></span></td>
						<td align="center" valign="middle" style="min-width: 150px;"><?php echo(number_format($rb['plan_monto'],2)); ?></td>
						<td width="10%" valign="middle" align="center"><span
							style="min-width: 150px;"><?php echo(number_format($rb['plan_cuota'])); ?></span></td>
						<td width="11%" valign="middle" align="center"><span
							style="min-width: 150px;"><?php echo(number_format($rb['pagado_antes'])); ?></span></td>
						<td width="10%" valign="middle" align="center"><input type="text"
							name="txt_montos[]" id="txt_montos[]" class="PlanCred"
							maxlength="15" value="<?php echo($valor);?>"
							style="width: 80px; text-align: center; text-transform: uppercase;"
							title="<?php echo($codig);?>" /></td>
						<td width="17%" valign="middle" align="center"><span
							style="min-width: 150px;"><?php echo(number_format($saldo,2)); ?></span></td>
					</tr>
    <?php } ?>
    </tbody>
			</table>
		</div>
		<input type="hidden" name="t25_cod_proy"
			value="<?php echo($idProy);?>" class="PlanCred" /> <input
			type="hidden" name="t25_version" value="<?php echo($idVersion);?>"
			class="PlanCred" /> <input type="hidden" name="t25_anio"
			value="<?php echo($idAnio);?>" class="PlanCred" /> <input
			type="hidden" name="t25_trim" value="<?php echo($idEntregable);?>"
			class="PlanCred" />

		<script language="javascript" type="text/javascript">
function ReLoadPlanesCred()
{
	var BodyForm = "action=<?php echo(md5("ListaPlanCred"));?>&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idEntregable=<?php echo($idEntregable);?>&t25_ver_inf="+$('#t25_ver_inf').val()+"&cbosub=" + $('#cbosub.PlanCred').val() ;
	var sURL = "inf_trim_plan_cred.php";
	$('#divPlanCreditos').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanCreditos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}

function Guardar_PlanCred()
{

<?php $ObjSession->AuthorizedPage(); ?>

var BodyForm=$("#FormData .PlanCred").serialize();
if(confirm("Estas seguro de Guardar el avance en Creditos para el informe Trimestral ?"))
{
    var sURL = "inf_trim_process.php?action=<?php echo(md5('save_plan_cred'));?>";
    var req = Spry.Utils.loadURL("POST", sURL, true, PlanCredSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
}
function PlanCredSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
    alert(respuesta.replace(ret,""));
	ReLoadPlanesCred();
  }
  else
  {alert(respuesta);}
}


$('.PlanCred').numeric().pasteNumeric();

</script>


		<fieldset style="font-size: 11px; color: navy;">
			<legend style="color: red; font-size: 9px;">Notas</legend>
			<font style="color: #666">Especificar los montos que se entregaron
				como creditos a los beneficarios, en el trimestre.</font>
		</fieldset>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>