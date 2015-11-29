<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idActiv = $objFunc->__Request('idActiv');
$idAnio = $objFunc->__Request('idAnio');
$idInforme = $objFunc->__Request('idnum');

$actividad = explode(".", $idActiv);
$idComp = $actividad[0];
$idAct = $actividad[1];

// $objFunc->Debug(true);

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>SubActividades</title>
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
						<td width="377" rowspan="2" align="left" valign="middle"><strong>Subactividades
								Críticas</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong> Meta Ejecutada Según Ejecutor</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Monitor
								Externo</strong></td>
					</tr>
					<tr>
						<td width="57" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
						<td width="57" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="54" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
						<td width="52" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="54" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
						<td width="49" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
						<!-- <td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
       <td width="73" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
       <td width="73" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td> -->
						<td width="150" align="center" bgcolor="#CCCCCC"><strong>Observación</strong></td>
					</tr>

    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaSubActividadesIUA($idProy, $idActiv, $idAnio, $idInforme);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
    
     <tr>
						<td width="377" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?>
         <input name="t09_cod_sub[]" type="hidden" id="t09_cod_sub[]"
							value="<?php echo($row['t09_cod_sub']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaanio']);?></td>



						<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaanual']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']+$row['ejec_mtaanual']);?></td>
						<!-- 
       <td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacumext']);?></td>
       <td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaanualext']);?></td>
       <td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacumext']+$row['ejec_mtaanualext']);?></td>
		
		-->
						<td align="center" nowrap="nowrap"><?php echo( $row['descrip']);?></td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de SubActividades
    else {
        echo ("<b style='color:red'>La Actividad Seleccionada [" . $idActiv . "] no tiene registrado Sub Actividades Críticas.</b><br/>");
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
				type="hidden" id="t09_sub_trim" value="<?php echo($idInforme);?>" />

		</div>  
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>