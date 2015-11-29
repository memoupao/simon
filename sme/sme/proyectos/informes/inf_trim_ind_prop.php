<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');
$view = $objFunc->__POST('view');
$view = $view == "" ? $objFunc->__GET("view") : $view;

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idAnio = $objFunc->__GET('idAnio');
    $idTrim = $objFunc->__GET('idTrim');
}

// $objFunc->Debug(true);

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Indicadores de Proposito</title>
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
 
<table width="780" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="82%" align="left" class=""><span
					style="font-weight: bold;">Avance de Indicadores de Propósito(Solo
						para el 4to Trimestre)</span></th>
				<th width="8%" align="center" class="">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar los Indicadores de Proposito"  onclick="LoadIndicadoresProposito(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					title="Refrescar los Indicadores de Proposito"
					onclick="LoadIndicadoresProposito(true);" class="btn_save_custom"
					style="margin: 4px 0px 4px 0px;" />
				</th>
				<!--th width="10%" align="right" valign="middle" osktgui-->
				<th width="10%" align="left" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Avances de los indicadores de Proposito"  onclick="Guardar_IndicadoresProposio();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div osktgui--> <input type="button" value="Guardar"
					title="Guardar Avances de los indicadores de Proposito"
					onclick="Guardar_IndicadoresProposio();"
					class="btn_save_custom btn_save" style="margin: 4px 4px 4px 0px;" />
				</th>
			</tr>
		</table>

		<div id="divTableLista">
			<table width="780" cellpadding="0" cellspacing="0"
				class="TableEditReg">
				<thead>

				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaIndicadoresProposito($idProy, $idAnio, $idTrim);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" align="left" valign="middle"><strong>Indicador de
								Proposito</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Ejecutado</strong></td>
					</tr>
					<tr>
						<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t07_cod_prop_ind']." ".$row['indicador']);?>
         <input name="t07_cod_prop_ind[]" type="hidden"
							id="t07_cod_prop_ind[]"
							value="<?php echo($row['t07_cod_prop_ind']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t07_um']);?></span></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
						<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="55" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="63" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
						<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatrim']);?></td>
						<td align="center" nowrap="nowrap"><input class="ITI_Pro"
							name="txtIndPropAcum[]" type="text" id="txtIndPropAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo( $row['ejec_mtaacum']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap"><input MontoIndPropTri='1'
							name="txtIndPropTrim[]" type="text" id="txtIndPropTrim[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatrim']);?>" size="4"
							onkeyup="TotalAvanceIndicadorProp('<?php echo($RowIndex);?>');" /></td>
						<td align="center" nowrap="nowrap"><input class="ITI_Pro"
							name="txtIndPropTot[]" type="text" id="txtIndPropTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="7" align="left" nowrap="nowrap">DESCRIPCION <br /> <textarea
								name="txtIndPropdes[]" cols="2500" rows="2" id="txtIndPropdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea>
							<br /> LOGROS <br /> <textarea name="txtIndProplog[]" cols="2500"
								rows="2" id="txtIndProplog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea><br />
							DIFICULTADES <br /> <textarea name="txtIndPropdif[]" cols="2500"
								rows="2" id="txtIndPropdif[]" style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea>
							<br /> <font style="font-weight: bold; color: #F00;">Observaciones
								del Monitor Técnico </font> <br /> <textarea
								name="txtIndPropdif[]" cols="2500" rows="2" id="txtIndPropdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de Actividades
    else {
        echo ("<b style='color:red'>No se tienen registrado Indicadores de Proposito...<br />Verificar el Marco Logico</b>");
        exit();
    }
    
    ?>
    </tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input type="hidden"
				name="t02_version" value="<?php echo($idVersion);?>" /> <input
				type="hidden" name="t07_ind_anio" value="<?php echo($idAnio);?>" />
			<input type="hidden" name="t07_ind_trim"
				value="<?php echo($idTrim);?>" />

			<script language="javascript" type="text/javascript">
	function Guardar_IndicadoresProposio()
	{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	var trim = $("#cbotrim").val();
	
	if(trim<4)
	{ alert("Error: No esta permitido el registro de los indicadores de Proposito para el \"Trimestre "+trim+"\""); return false;}
	
	var BodyForm=$("#FormData").serialize();
	if(confirm("Estas seguro de Guardar el avance de los indicadores de Proposito para el Informe ?"))
	{
		var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_ind_proposito'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, IndPropSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
	}
	}
	function IndPropSuccessCallback(req)
	{ 
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadIndicadoresProposito(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	function TotalAvanceIndicadorProp(x)
	{ 
	  var index=parseInt(x) ;
	  var xTotal=document.getElementsByName("txtIndPropTot[]") ;
	  var xAcum =document.getElementsByName("txtIndPropAcum[]");
	  var xMes  =document.getElementsByName("txtIndPropTrim[]") ;
	  var mtaacum =parseFloat(xAcum[index].value) ;
	  var mtatrim =parseFloat(xMes[index].value) ;
	  if(isNaN(mtaacum)){mtaacum=0;}
	  if(isNaN(mtatrim)){mtatrim=0;}
	  var total=(mtaacum + mtatrim) ;
	  xTotal[index].value = total ;
	  
	} 
		$("input[MontoIndPropTri='1']").numeric().pasteNumeric();		
		$('.ITI_Pro:input[readonly="readonly"]').css("background-color", "#eeeecc") ; 
		
	</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>