<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idNum');

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
				<td width="82%" valign="bottom"><strong>Observaciones de
						Evaluaciones de Periodos Anteriores</strong></td>
				<td width="8%" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos"  onclick="LoadObservaciones(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					class="btn_save_custom" title="Refrescar datos"
					onclick="LoadObservaciones(true);" />
				</td>
				<td width="10%" align="right" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Datos"  onclick="Guardar_Observaciones();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div osktgui--> <input type="button" value="Guardar"
					class="btn_save_custom" title="Guardar Datos"
					onclick="Guardar_Observaciones();" />
				</td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="775" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="16" align="center" valign="middle">#</td>
						<td align="center" valign="middle">Fecha Informe</td>
						<td width="324" align="center" valign="middle">Observaciones del
							Monitor</td>
						<td width="299" align="center" valign="middle">Respuesta del
							Ejecutor</td>
						<td width="53" height="23" colspan="14" align="center"
							valign="middle">OK Monitor</td>
					</tr>
     <?php
    
    $iRs = $objInf->Inf_MF_ListadoObservaciones($idProy, $idInforme);
    $contador = 1;
    while ($r = mysqli_fetch_assoc($iRs)) {
        
        ?>

     <tr>
						<td height="43" align="center" nowrap="nowrap" class="TableGrid"><?php echo($contador); ?></td>
						<td width="81" align="center" valign="middle"><?php echo($r['fecha']); ?></td>
						<td align="left"><?php echo(nl2br($r['t51_obs_moni'])); ?></td>
						<td align="left"><?php echo(nl2br($r['t51_obs_eje'])); ?></td>
						<td colspan="14" align="center" nowrap="nowrap"><input
							name="chkOK[]" type="checkbox" id="chkOK[]" value="1"
							<?php if($r['t51_ok_moni']=='1'){echo('checked="checked"');} ?> /></td>
					</tr>
       <?php
        $contador ++;
    }
    $iRs->free();
    ?>
     </tbody>
				<tfoot>
				</tfoot>
			</table>
			<br />
			<table width="775" border="0" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td height="23" align="left" valign="middle">
							<div style="display: inline-block; width: 300px;">Observaciones</div>
							<div
								style="display: inline-block; width: 450px; text-align: right;">
								<span
									style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
									title="Obtener Observaciones sobre las SubActividades Reportadas"
									onclick="ObtenerComentarios();"><img
									src="../../../img/file.gif" width="16" height="19" /></span>
							</div>
						</td>
					</tr>
					<tr>
						<td height="43" align="center" nowrap="nowrap"><textarea
								name="t51_obs" rows="10" id="t51_obs"
								style="padding: 0px; width: 100%;" class="Observaciones"><?php echo(  $row['t51_obs']);?></textarea></td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<p>
				<input name="t02_cod_proy" id="t02_cod_proy" type="hidden"
					value="<?php echo($idProy);?>" class="Observaciones" /> <input
					name="t51_num" id="t51_num" type="hidden"
					value="<?php echo($idInforme);?>" class="Observaciones" />
				<script language="javascript" type="text/javascript">
    function ObtenerComentarios()
	{
		<?php $ObjSession->AuthorizedPage(); ?>	
		
	 $("#t51_obs.Observaciones").html("Cargando ...");	
	  if(confirm("Estas seguro de recuperar las observaciones ingresadas en los Avances Fisico y Presupuestal del Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5("ajax_obtener_subact"));?>" ;
		var BodyForm = "t02_cod_proy=<?php echo($idProy);?>&t51_num=<?php echo($idInforme);?>" ;
		var req = Spry.Utils.loadURL("POST", sURL, true, ObtenerComentariosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
		
		
	}
    
	
	function ObtenerComentariosSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  $("#t51_obs.Observaciones").html(respuesta);
	} 
	
  
	function Guardar_Observaciones	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	

	var BodyForm=$("#FormData .Observaciones").serialize();
	
	if(confirm("Estas seguro de Guardar los datos ingresados para el Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_comentarios'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, ObservacionesSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function ObservacionesSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadObservaciones(true);
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