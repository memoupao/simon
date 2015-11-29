<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idComp = $objFunc->__Request('idComp');
$idAnio = $objFunc->__Request('idAnio');
$idInforme = $objFunc->__Request('idnum');

if ($objFunc->__QueryString() == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Programación vs Avance por Componentes</title>
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
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" rowspan="2" align="left" valign="middle"><strong>Indicadores
								de Componente</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Meta Ejecutada Según Ejecutor</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Meta
								Ejecutada Según Monitor Externo</strong></td>
					</tr>
					<tr>
						<td width="60" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Meta
								Total</strong></td>
						<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="55" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="63" align="center" bgcolor="#CCCCCC"><strong>anual</strong></td>
						<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>

						<td width="150" align="center" bgcolor="#CCCCCC"><strong>Observación</strong></td>
						<!--td width="68" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
        <td width="68" align="center" bgcolor="#CCCCCC"><strong>anual</strong></td>
        <td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td-->
					</tr>
	<?php
$objInf = new BLInformes();
$iRs = $objInf->ListaIndicadoresComponenteIUA($idProy, $idComp, $idAnio);
$RowIndex = 0;
if ($iRs->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>
         
     
     <tr>
						<td width="412" align="left" valign="middle"><?php echo($row['t08_cod_comp_ind'].".- ".$row['indicador']);?>
         <input name="t08_cod_comp_ind[]" type="hidden"
							id="t08_cod_comp_ind[]"
							value="<?php echo($row['t08_cod_comp_ind']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t08_um']);?></span></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['meta_acum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['meta_anual']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['meta_acum_tri']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['meta_anual_tri']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['meta_acum_tri']+$row['meta_anual_tri']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['descrip']);?></td>
					</tr>
 
     <?php
        $RowIndex ++;
    }
    $iRs->free();
} // Fin de SubActividades
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
				name="t08_ind_trim" value="<?php echo($idInforme);?>" />

		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>