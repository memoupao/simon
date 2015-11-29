<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t25_ver_inf');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');

if ($idProy == "" && $idAnio == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t25_ver_inf');
    $idAnio = $objFunc->__GET('idAnio');
    $idTrim = $objFunc->__GET('idTrim');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Actividades</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<?php

$objInf = new BLInformes();
$row = $objInf->ListaAnalisisInfTrim($idProy, $idAnio, $idTrim, $idVersion);

?>
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Problemas y Soluciones"  onclick="LoadAnalisis(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					title="Refrescar datos de Problemas y Soluciones"
					onclick="LoadAnalisis(true);" class="btn_save_custom" />
				</td>
				<!--td width="10%" rowspan="2" align="right" valign="middle"-->
				<td width="10%" rowspan="2" align="left" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Problemas y Soluciones"  onclick="Guardar_Analisis();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div osktgui--> <input type="button" value="Guardar"
					title="Guardar Problemas y Soluciones"
					onclick="Guardar_Analisis();" class="btn_save_custom btn_save" />
				</td>
			</tr>
			<tr>
				<td><span style="font-weight: bold;">Analisis del Informe</span></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Análisis de
								Resultados</span> <br> <textarea name="t25_resulta" rows="7"
								id="t25_resulta" style="padding: 0px; width: 100%;"><?php echo($row['t25_resulta']);?></textarea></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Conclusiones</span><br>
							<textarea name="t25_conclu" rows="5" id="t25_conclu"
								style="padding: 0px; width: 100%;"><?php echo($row['t25_conclu']);?></textarea></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Limitaciones</span><br>
						<textarea name="t25_limita" rows="5" id="t25_limita"
								style="padding: 0px; width: 100%;"><?php echo($row['t25_limita']);?></textarea></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Factores Positivos</span>
							<textarea name="t25_fac_pos" rows="5" id="t25_fac_pos"
								style="padding: 0px; width: 100%;"><?php echo($row['t25_fac_pos']);?></textarea>
							</p></td>
					</tr>
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">Perspectivas para el
								Próximo Trimestre</span> <textarea name="t25_perspec" rows="5"
								id="t25_perspec" style="padding: 0px; width: 100%;"><?php echo($row['t25_perspec']);?></textarea></td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input type="hidden"
				name="t02_version" value="<?php echo($idVersion);?>" /> <input
				name="t25_anio" type="hidden" id="t25_anio"
				value="<?php echo($idAnio);?>" /> <input name="t25_trim"
				type="hidden" id="t25_trim" value="<?php echo($idTrim);?>" />

			<script language="javascript" type="text/javascript">
function Guardar_Analisis()
{
<?php $ObjSession->AuthorizedPage(); ?>	
//var BodyForm= serializeDIV('divAnalisis');  
var BodyForm=$("#FormData").serialize();
if(confirm("Estas seguro de Guardar el Analisis, para el Informe Trimestral?"))
{
	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_analisis'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, AnalisisSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
}
function AnalisisSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
{
LoadAnalisis(true);
alert(respuesta.replace(ret,""));
}
else
{alert(respuesta);}  
}
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>