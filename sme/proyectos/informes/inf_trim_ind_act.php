<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idActiv = $objFunc->__POST('idActiv');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idActiv = $objFunc->__GET('idActiv');
    $idAnio = $objFunc->__GET('idAnio');
    $idTrim = $objFunc->__GET('idTrim');
}
$actividad = explode(".", $idActiv);
$idComp = $actividad[0];
$idAct = $actividad[1];

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Indicadores de Actividad</title>
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
<div id="divTableLista">
			<table width="780" cellpadding="0" cellspacing="0"
				class="TableEditReg">
				<thead>

				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaIndicadoresActividadTrim($idProy, $idActiv, $idAnio, $idTrim);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" align="left" valign="middle"><strong>Indicador de
								Actividad</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Ejecutado</strong></td>
					</tr>
					<tr>
						<td width="412" rowspan="2" align="left" valign="middle">
       		<?php echo(($RowIndex + 1).".- ".$row['indicador']);?>
         <input name="t09_cod_act_ind[]" type="hidden"
							id="t09_cod_act_ind[]"
							value="<?php echo($row['t09_cod_act_ind']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span>
						</td>
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
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
						<td align="center" nowrap="nowrap"><input class="ITAc"
							name="txtIndActAcum[]" type="text" id="txtIndActAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtaacum']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap"><input MontoIndActvTri='1'
							name="txtIndActTrim[]" type="text" id="txtIndActTrim[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtames']);?>" size="4"
							onkeyup="TotalAvanceIndicador('<?php echo($RowIndex);?>');" /></td>
						<td align="center" nowrap="nowrap"><input class="ITAc"
							name="txtIndActTot[]" type="text" id="txtIndActTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="7" align="left" nowrap="nowrap">DESCRIPCION <br /> <textarea
								name="txtIndactdes[]" cols="2500" rows="2" id="txtIndactdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea>
							<br /> LOGROS <br /> <textarea name="txtIndActlog[]" cols="2500"
								rows="2" id="txtIndActlog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea>
							<br /> DIFICULTADES <br /> <textarea name="txtIndActdif[]"
								cols="2500" rows="2" id="txtIndActdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea><br />
							<font style="font-weight: bold; color: #F00;">Observaciones del
								Monitor Técnico </font> <br /> <textarea name="txtIndActobs[]"
								cols="2500" rows="2" id="txtIndActobs[]"
								style="padding: 0px; width: 100%;"><?php echo($row['observaciones']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de Actividades
    else {
        echo ("<b style='color:red'>El Producto seleccionado [" . $idActiv . "] no tiene registrado Indicadores...<br />Verificar el Marco Logico</b>");
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
				type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
			<input type="hidden" name="t09_cod_act" value="<?php echo($idAct);?>" />
			<input type="hidden" name="t09_ind_anio"
				value="<?php echo($idAnio);?>" /> <input type="hidden"
				name="t09_ind_trim" value="<?php echo($idTrim);?>" />

			<script language="javascript" type="text/javascript">
function Guardar_AvanceIndAct	()
{
<?php $ObjSession->AuthorizedPage(); ?>	
//	var BodyForm= serializeDIV('divAvanceActividades');  
var BodyForm=$("#FormData").serialize();

if(BodyForm==""){alert("El Producto seleccionado, no Tiene indicadores !!!"); return;}

if(confirm("Estas seguro de Guardar el avance de los Indicadores para el Informe ?"))
  {
	var activ = $('#cboactividad_ind').val(); 
	var anio = $('#cboanio').val();
	var trim = $('#cbotrim').val();

	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_indicadores_actividad'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, indActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
  }
}
function indActSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
 {
 LoadIndicadoresActividad();
 alert(respuesta.replace(ret,""));
 }
else
{alert(respuesta);}  

}
	
function TotalAvanceIndicador(x)
{ 
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtIndActTot[]") ;
  var xAcum =document.getElementsByName("txtIndActAcum[]");
  var xMes =document.getElementsByName("txtIndActTrim[]") ;
  
  var mtaacum =parseFloat(xAcum[index].value) ;
  var mtames =parseFloat(xMes[index].value) ;
  if(isNaN(mtaacum)){mtaacum=0;}
  if(isNaN(mtames)){mtames=0;}
  var total=(mtaacum + mtames) ;
  xTotal[index].value = total ;
  
}
	$("input[MontoIndActvTri='1']").numeric().pasteNumeric();		
	$('.ITAc:input[readonly="readonly"]').css("background-color", "#eeeecc") ; 
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>