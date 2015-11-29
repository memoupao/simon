<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idNum');
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
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Problemas y Soluciones"  onclick="LoadConclusiones(true);" > <img src="../../../img/gestion.jpg" alt="" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					class="btn_save_custom"
					title="Refrescar datos de Problemas y Soluciones"
					onclick="LoadConclusiones(true);" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Problemas y Soluciones"  onclick="Guardar_Conclusiones();" > <img src="../../../img/aplicar.png" alt="" width="22" height="22" /><br />
      Guardar </div osktgui--> <input type="button" value="Guardar"
					class="btn_save_custom" title="Guardar Problemas y Soluciones"
					onclick="Guardar_Conclusiones();" <?php echo $ver; ?> />
				</td>
			</tr>
			<tr>
				<td><span style="font-weight: bold;">Conclusiones y Calificación</span></td>
			</tr>
		</table>
		<table width="750" cellpadding="0" cellspacing="0"
			class="TableEditReg">
			<thead>
			</thead>
			<tbody class="data" bgcolor="#FFFFFF">
				<tr>
					<td align="left" valign="middle"><span
						style="font-weight: bold; font-size: 12px;">Conclusiones</span> <br />
						<textarea name="t51_conclu" rows="15" id="t51_conclu"
							style="padding: 0px; width: 100%;" class="Conclusiones"><?php echo($row['t51_conclu']);?></textarea></td>
				</tr>
				<tr>
					<td align="left" valign="middle"><span
						style="font-weight: bold; font-size: 12px;">Calificación</span><br /></td>
				</tr>
				<tr>
					<td align="left" valign="middle">Efectuada la revisión de los
						reportes recibidos, se califica la información presentada por <br />
						<select name="t51_califica" id="t51_califica"
						style="width: 160px;" class="Conclusiones">
							<option value="" selected="selected" title="2"></option>
          <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t51_califica'], 'cod_ext');
        ?>
        </select> <br /> <br /> <br />
					</td>
				</tr>
			</tbody>
			<tfoot>
			</tfoot>
		</table>
		<div id="divTableLista" class="TableGrid">
			<p>
				<input name="t02_cod_proy" id="t02_cod_proy" type="hidden"
					value="<?php echo($idProy);?>" class="Conclusiones" /> <input
					name="t51_num" id="t51_num" type="hidden" class="Conclusiones"
					value="<?php echo($idInforme);?>" />
				<script language="javascript" type="text/javascript">
	function HabilitarOpcion1(id)
	{
	if($('#'+id).attr("checked"))
	{
	   $('#chkopcion1_1').removeAttr("disabled");
	   $('#chkopcion1_2').removeAttr("disabled");
	   $('#chkopcion1_3').removeAttr("disabled");
	   $('#txtquest1_1').removeAttr("disabled");
	   $('#txtquest1_2').removeAttr("disabled");
	   $('#txtquest1_3').removeAttr("disabled");
	   
	}
	else
	{
		$('#chkopcion1_1').attr("disabled", "disabled");
		$('#chkopcion1_2').attr("disabled", "disabled");
		$('#chkopcion1_3').attr("disabled", "disabled");
		$('#txtquest1_1').attr("disabled", "disabled");
		$('#txtquest1_2').attr("disabled", "disabled");
		$('#txtquest1_3').attr("disabled", "disabled");
	}
	}
	
	function MostrarCuestionario(valor)
	{
	if(valor=='1')   
	{
		$('#divCuestionarioInicial').css("display","block"); 
		$('#divCuestionarioEjecucion').css("display","none"); 
		
	}
	else
	{
		$('#divCuestionarioInicial').css("display","none");
		$('#divCuestionarioEjecucion').css("display","block"); 
	}
	}
	
	
	
	function Guardar_Conclusiones	()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	

	var BodyForm=$("#FormData .Conclusiones").serialize();
	
	if(confirm("Estas seguro de Guardar los datos ingresados para el Informe ?"))
	  {
		var sURL = "inf_financ_process.php?action=<?php echo(md5('ajax_conclusiones'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, ConclusionesSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	  }
	}
	function ConclusionesSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadConclusiones(true);
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