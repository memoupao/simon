<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idnum');

$ver = $objFunc->__Request('ver');

$objInf = new BLMonitoreoFinanciero();
$row = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Documentos Presentados por la Institución</title>
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
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%"><strong>Documentos presentados por la institución</strong></td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Otros Gastos"  onclick="LoadEvaluacion(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					title="Refrescar datos de Otros Gastos"
					onclick="LoadEvaluacion(true);" class="btn_save_custom" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Datos"  onclick="Guardar_Evaluacion();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div osktgui--> <input type="button" value="Guardar"
					title="Guardar Datos" onclick="Guardar_Evaluacion();"
					class="btn_save_custom" <?php echo $ver; ?> />
				</td>
			</tr>
			<tr>
				<td><font style="font-size: 11px; color: #2A3F55;">De acuerdo al
						convenio firmado entre la Institucion y FONDOEMPLEO, la
						institución debe elaborar reportes sobre la base de Reportes
						Contables y de Ejecución Presupuestal que emita el sistema
						contable que manejan.</font></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="775" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="375" align="center" valign="middle">Reporte /
							Información</td>
						<td align="center" valign="middle">Estado</td>
						<td width="278" height="23" colspan="6" align="center"
							valign="middle">OBSERVACIONES</td>
					</tr>
     <?php
    require (constant("PATH_CLASS") . "BLTablasAux.class.php");
    $objTablas = new BLTablasAux();
    
    $iRs = $objInf->Inf_MF_Listado_Docum_Inst($idProy, $idInforme);
    
    while ($r = mysqli_fetch_assoc($iRs)) {
        ?>

     <tr>
						<td height="43" align="left" nowrap="nowrap" class="TableGrid"><input
							name="t51_tipdoc[]" id="t51_tipdoc[]" type="hidden"
							class="evaluacion" value="<?php echo($r['t51_tipdoc']); ?>" />
	   <?php echo($r['tipodoc']); ?>
       </td>
						<td width="120" align="left" valign="middle"><select
							name="t51_estdoc[]" id="t51_estdoc[]" style="width: 110px;"
							class="evaluacion">
								<option value=""></option>
         <?php
        $rs = $objTablas->TipoDocsManejaInstEstado();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $r['t51_estdoc']);
        ?>
       </select></td>
						<td colspan="6" align="left" nowrap="nowrap"><textarea
								name="t51_estobs[]" cols="50" rows="2" id="t51_estobs[]"
								class="evaluacion"><?php echo($r['observaciones']);?></textarea></td>
					</tr>
       <?php
    }
    $iRs->free();
    ?>
     </tbody>
				<tfoot>
				</tfoot>
			</table>
			<p>
				<input name="t02_cod_proy" type="hidden"
					value="<?php echo($idProy);?>" id="t02_cod_proy" class="evaluacion" />
				<input name="t15_num" type="hidden"
					value="<?php echo($idInforme);?>" id="t15_num" class="evaluacion" />
				<script language="javascript" type="text/javascript">
	function Guardar_Evaluacion	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if('<?php echo($idInforme);?>'=='')
	{
		alert("ERROR: \n No se ha grabado la cabecera del Informe.");
		return;
	}
	
	var validaestado = '' ;	
	$("select[name='t51_estdoc[]']").each(function() { validaestado += this.value; });
	if(validaestado=='') {alert("No ha seleccionado ningun \"Estado\" para ningun Documento"); return ; }
	
	var BodyForm=$("#FormData .evaluacion").serialize();
	
	
	if(confirm("Estas seguro de Guardar los datos ingresados para el Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_evaluacion_docs'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, EvaluacionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function EvaluacionSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadEvaluacion(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	} 
  </script>
			</p>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>