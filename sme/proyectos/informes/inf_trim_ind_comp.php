<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idComp = $objFunc->__POST('idComp');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idComp = $objFunc->__GET('idComp');
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

<title>Indicadores de Componentes</title>
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
    $iRs = $objInf->ListaIndicadoresComponente($idProy, $idComp, $idAnio, $idTrim);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" align="left" valign="middle"><strong>Indicador de
								Componente</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Ejecutado</strong></td>
					</tr>
					<tr>
						<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp_ind'].".- ".$row['indicador']);?>
         <input name="t08_cod_comp_ind[]" type="hidden"
							id="t08_cod_comp_ind[]"
							value="<?php echo($row['t08_cod_comp_ind']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t08_um']);?></span></td>
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
						<td align="center" nowrap="nowrap"><input class="ITC_Pro"
							name="txtIndCompAcum[]" type="text" id="txtIndCompAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtaacum']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap"><input MontoIndCompTri='1'
							name="txtIndCompTrim[]" type="text" id="txtIndCompTrim[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatrim']);?>" size="4"
							onkeyup="TotalAvanceIndicadorComp('<?php echo($RowIndex);?>');" /></td>
						<td align="center" nowrap="nowrap"><input class="ITC_Pro"
							name="txtIndCompTot[]" type="text" id="txtIndCompTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="7" align="left" nowrap="nowrap">DESCRIPCION <br /> <textarea
								name="txtIndCompdes[]" cols="2500" rows="2" id="txtIndCompdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea>
							<br /> LOGROS <br /> <textarea name="txtIndComplog[]" cols="2500"
								rows="2" id="txtIndComplog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea>
							<br /> DIFICULTADES <br /> <textarea name="txtIndCompdif[]"
								cols="2500" rows="2" id="txtIndCompdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea>
							<br /> <font style="font-weight: bold; color: #F00;">Observaciones
								del Monitor Técnico </font> <br /> <textarea
								name="txtIndCompobs[]" cols="2500" rows="2" id="txtIndCompobs[]"
								style="padding: 0px; width: 100%;"><?php echo($row['observaciones']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de Actividades
    else {
        echo ("<b style='color:red'>El Componente Seleccionado[" . $idComp . "] no tiene registrado Indicadores...<br />Verificar el Marco Logico</b>");
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
			<input type="hidden" name="t08_ind_anio"
				value="<?php echo($idAnio);?>" /> <input type="hidden"
				name="t08_ind_trim" value="<?php echo($idTrim);?>" />

			<script language="javascript" type="text/javascript">
function Guardar_AvanceIndComp	()
{
<?php $ObjSession->AuthorizedPage(); ?>	


var comp = $('#cbocomponente_ind').val();
if(comp==""){alert("El componente seleccionado, no Tiene indicadores !!!"); return;}

var BodyForm=$("#FormData").serialize();

if(confirm("Estas seguro de Guardar el avance de los Indicadores de Componente para el Informe ?"))
{
	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_ind_componente'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, indCompSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}

}

function indCompSuccessCallback	(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	LoadIndicadoresComponente();
	alert(respuesta.replace(ret,""));
  }
  else
  {alert(respuesta);}  
  
}


function TotalAvanceIndicadorComp(x)
{ 
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtIndCompTot[]") ;
  var xAcum =document.getElementsByName("txtIndCompAcum[]");
  var xMes =document.getElementsByName("txtIndCompTrim[]") ;
  
  var mtaacum =parseFloat(xAcum[index].value) ;
  var mtames =parseFloat(xMes[index].value) ;
  if(isNaN(mtaacum)){mtaacum=0;}
  if(isNaN(mtames)){mtames=0;}
  var total=(mtaacum + mtames) ;
  xTotal[index].value = total ;
  
}
	$("input[MontoIndCompTri='1']").numeric().pasteNumeric();		
	$('.ITC_Pro:input[readonly="readonly"]').css("background-color", "#eeeecc") ; 
		
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>