<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idActiv = $objFunc->__POST('idActiv');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idActiv = $objFunc->__GET('idActiv');
    $idAnio = $objFunc->__GET('idAnio');
    $idMes = $objFunc->__GET('idMes');
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

<title>POA - Indicadores de Actividad</title>
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
    $objInf = new BLPOA();
    $iRs = $objInf->ListaIndicadoresActividad($idProy, $idActiv, $idAnio, $idMes);
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
						<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']." ".$row['indicador']);?>
         <input name="t09_cod_act_ind[]" type="hidden"
							id="t09_cod_act_ind[]"
							value="<?php echo($row['t09_cod_act_ind']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
						<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="55" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="63" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
						<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
						<td align="center" nowrap="nowrap"><input name="txtIndActAcum[]"
							type="text" id="txtIndActAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo( $row['ejec_mtaacum']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap"><input name="txtIndActMes[]"
							type="text" id="txtIndActMes[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtames']);?>" size="4"
							onkeyup="TotalAvanceIndicador('<?php echo($RowIndex);?>');" /></td>
						<td align="center" nowrap="nowrap"><input name="txtIndActTot[]"
							type="text" id="txtIndActTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="7" align="left" nowrap="nowrap">DESCRIPCION <br /> <textarea
								name="txtIndactdes[]" cols="2500" rows="3" id="txtIndactdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea>
							<br /> LOGROS <br /> <textarea name="txtIndActlog[]" cols="2500"
								rows="3" id="txtIndActlog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea>
							<br /> DIFICULTADES <br /> <textarea name="txtIndActdif[]"
								cols="2500" rows="3" id="txtIndActdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea></td>
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
				name="t09_ind_mes" value="<?php echo($idMes);?>" />

			<script language="javascript" type="text/javascript">
	 function TotalAvanceIndicador(x)
		{
		  var index=parseInt(x) ;
		  /*
		  var xTotal=$("input[name=txtIndActTot[]]") ;
		  var xAcum =$("input[name=txtIndActAcum[]]") ;
  		  var xMes =$("input[name=txtIndActMes[]]") ;
		  */
		  var xTotal=document.getElementsByName("txtIndActTot[]") ;
		  var xAcum =document.getElementsByName("txtIndActAcum[]");
  		  var xMes =document.getElementsByName("txtIndActMes[]") ;

		  var mtaacum =parseFloat(xAcum[index].value) ;
		  var mtames =parseFloat(xMes[index].value) ;
		  if(isNaN(mtaacum)){mtaacum=0;}
		  if(isNaN(mtames)){mtames=0;}
  		  var total=(mtaacum + mtames) ;
		  xTotal[index].value = total ;

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