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

<title>Actividades</title>
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 613px;
	top: 0px;
	width: 134px;
	height: 55px;
	z-index: 0;
	visibility: visible;
}
-->
</style>
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
    $iRs = $objInf->ListaSubActividadesTrim($idProy, $idActiv, $idAnio, $idTrim);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="377" align="left" valign="middle"><strong>Actividad</strong></td>
						<td height="15" colspan="4" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Ejecutado</strong></td>
					</tr>
					<tr>
						<td width="377" rowspan="3" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?>
         <input name="t09_cod_sub[]" type="hidden" id="t09_cod_sub[]"
							value="<?php echo($row['t09_cod_sub']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
						<td width="57" rowspan="2" align="center" bgcolor="#CCCCCC"><strong>Total
								de Proyecto</strong></td>
						<td width="57" rowspan="2" align="center" bgcolor="#CCCCCC"><strong>Total
								Año</strong></td>
						<td width="54" rowspan="2" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="52" rowspan="2" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
						<td width="54" rowspan="2" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td colspan="2" align="center" bgcolor="#CCCCCC"><strong>Trimestre</strong></td>
						<td width="73" rowspan="2" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
					</tr>
					<tr>
						<td width="49" align="center" bgcolor="#CCCCCC"><strong>1&ordm;,
								2&ordm; Mes</strong></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><strong>3&deg; Mes
						</strong></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaanio']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
						<td align="center" nowrap="nowrap"><input class="ITSA"
							name="txtSubActAcum[]" type="text" id="txtSubActAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo( $row['ejec_mtaacum']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap"><input class="ITSA"
							name="txtSubActAcumTrim[]" type="text" id="txtSubActAcumTrim[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo( $row['ejec_mtaacumtrim']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap"><input InfTSubAct='1'
							name="txtSubActTrim[]" type="text" id="txtSubActTrim[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtames']);?>" size="4"
							onkeyup="TotalAvanceSubActividad('<?php echo($RowIndex);?>');" /></td>
						<td align="center" nowrap="nowrap"><input class="ITSA"
							name="txtSubActTot[]" type="text" id="txtSubActTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="9" align="left" nowrap="nowrap">DESCRIPCION <br /> <textarea
								name="txtSubactdes[]" cols="2500" rows="3" id="txtSubactdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea>
							<br /> LOGROS <br /> <textarea name="txtSubActlog[]" cols="2500"
								rows="2" id="txtSubActlog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea>
							<br /> DIFICULTADES <br /> <textarea name="txtSubActdif[]"
								cols="2500" rows="2" id="txtSubActdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea>
							<br /> <font style="font-weight: bold; color: #F00;">Observaciones
								del Monitor Técnico </font> <br /> <textarea
								name="txtObsMonTec[]" cols="2500" rows="2" id="txtObsMonTec[]"
								style="padding: 0px; width: 100%;"><?php echo($row['observaciones']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de Actividades
    else {
        echo ("<b style='color:red'>El Producto seleccionado [" . $idActiv . "] no tiene registrado Actividades...<br />Verificar el Plan Operativo</b>");
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
			<input name="t09_sub_anio" type="hidden" id="t09_sub_anio"
				value="<?php echo($idAnio);?>" /> <input name="t09_sub_trim"
				type="hidden" id="t09_sub_trim" value="<?php echo($idTrim);?>" />

			<script language="javascript" type="text/javascript">
function Guardar_AvanceSubAct	()
{
<?php $ObjSession->AuthorizedPage(); ?>	


var BodyForm= serializeDIV('divAvanceSubActividades');  
if(BodyForm==""){alert("El Producto seleccionado, no Tiene Actividades !!!"); return;}

BodyForm=$("#FormData").serialize();
if(confirm("Estas seguro de Guardar el avance de las Actividades para el Informe ?"))
  {
	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_sub_actividad'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, SubActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
  }
}
function SubActSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
 {
 LoadSubActividades();
 alert(respuesta.replace(ret,""));
 }
else
{alert(respuesta);}  
}

function TotalAvanceSubActividad(x)
{ 
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtSubActTot[]") ;
  var xAcum =document.getElementsByName("txtSubActAcum[]");
  var xAcumT =document.getElementsByName("txtSubActAcumTrim[]");
  var xTrim =document.getElementsByName("txtSubActTrim[]") ;
  
  var mtaacum =parseFloat(xAcum[index].value) ;
  var mtaacumtrim = parseFloat(xAcumT[index].value);
  var mtatrim =parseFloat(xTrim[index].value) ;
  if(isNaN(mtaacum)){mtaacum=0;}
  if(isNaN(mtatrim)){mtatrim=0;}
  if(isNaN(mtaacumtrim)){mtaacumtrim=0;}
 
  var total=(mtaacum + mtatrim + mtaacumtrim) ;
  xTotal[index].value = total ;
}
	$("input[InfTSubAct='1']").numeric().pasteNumeric();		
	$('.ITSA:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>