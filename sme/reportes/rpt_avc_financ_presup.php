<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");

$objInf = new BLMonitoreoFinanciero();
$objPresup = new BLPresupuesto();

// $idVersion = $objPresup->Proyecto->MaxVersion($idProy);
error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idFuente = $objFunc->__Request('idFuente');
$ini = $objFunc->__Request('ini');
$ter = $objFunc->__Request('ter');

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Informe Gastos x SubActividad</title>
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
<div class="TableGrid">
			<table width="771" border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr style="border: solid 1px #CCC; background-color: #FFF;">
						<td height="28" colspan="9" align="center" valign="middle"><strong>Avance
								Presupuestal</strong></td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="70" rowspan="2" align="center" valign="middle"><strong>Codigo</strong></td>
						<td width="144" rowspan="2" align="center" valign="middle"><strong>Actividades</strong></td>
						<td width="47" rowspan="2" align="center" valign="middle"><strong>U.M.</strong></td>
						<td width="75" rowspan="2" align="center" valign="middle"><strong>Presupuesto
						</strong></td>
						<td height="28" colspan="4" align="center" valign="middle"><strong>Total Periodo <?php echo(" (".$ini." - ".$ter.")"); ?></strong></td>
						<td width="209" rowspan="2" align="center" valign="middle"><strong>Observaciones</strong></td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="70" height="28" align="center" valign="middle"><strong>Programado</strong></td>
						<td width="70" align="center" valign="middle"><strong>Ejecutado</strong></td>
						<td width="35" align="center" valign="middle"><strong>% Ejec.</strong></td>
						<td width="35" align="center" valign="middle"><strong>Gastos No
								Aceptado</strong></td>
					</tr>
				</tbody>
				<tbody class="data">
       <?php
    
    $iRsComp = $objInf->ListaComponentes($idProy);
    
    $numComp = 1;
    while ($rowComp = mysqli_fetch_assoc($iRsComp)) {
        $idComp = $rowComp['t08_cod_comp'];
        
        $total_presup = 0;
        $total_ejec = 0;
        $total_planeado = 0;
        $total_GNA = 0;
        ?>  
        <tr
						style="background-color: #FFC; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><strong><?php echo( $rowComp['t08_cod_comp']);?></strong></td>
						<td colspan="8" align="left"><strong><?php echo( $rowComp['t08_comp_desc']);?></strong></td>
					</tr>
      <?php
        
        $iRsAct = $objInf->Rpt_MF_Avance_Presup_Act($idProy, $idComp, $idFuente, $ini, $ter);
        while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
            $idAct = $rowAct['t09_cod_act'];
            
            $total_presup += $rowAct['total_presup'];
            $total_ejec += $rowAct['ejecutado'];
            $total_planeado += $rowAct['programado'];
            
            $porcEjecucion = round((($rowAct['ejecutado'] / $rowAct['programado']) * 100), 2);
            
            ?>  
      
        <tr
						style="background-color: #FC9; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
						<td colspan="2" align="left"><?php echo( $rowAct['actividad']);?></td>
						<td align="center"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['programado'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['ejecutado'],2,'.',','));?></td>
						<td align="center"><?php echo($porcEjecucion);?>%</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
    <?php
            $iRs = $objInf->Rpt_MF_Avance_Presup_SubAct($idProy, $idComp, $idAct, $idFuente, $ini, $ter);
            while ($rowsub = mysqli_fetch_assoc($iRs)) {
                $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
                $total_GNA += $rowsub['gasto_no_aceptado'];
                ?>
    <tr>
						<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?>
        </td>
						<td align="left"><?php echo( $rowsub['subactividad']);?></td>
						<td align="center"><?php echo($rowsub['um']);?></td>
						<td align="center"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['programado'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['ejecutado'],2,'.',','));?></td>
						<td align="center"><?php echo($porcEjecucion);?>%</td>
						<td align="center">
        <?php echo(number_format($rowsub['gasto_no_aceptado'],2));?>
        </td>
						<td align="center"><?php echo($rowsub['observaciones']);?></td>
					</tr>
      <?php } $iRs->free(); // Fin de SubActividades ?>
	  <?php } $iRsAct->free(); // Fin de Actividades 	?>
    
    <?php if( $numComp <= $iRsComp->num_rows ) { ?>
    <tr style="color: #009; height: 20px;">
						<td colspan="3" align="center"><strong>Totales x Componente
								&nbsp;&nbsp;</strong></td>
						<td align="center"><strong><?php echo(number_format($total_presup,2,'.',','));?></strong></td>
						<td align="right"><strong><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</strong></td>
						<td align="right"><strong><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</strong></td>
						<td align="center"><strong><?php echo(round((($total_ejec/$total_planeado)*100),2));?>%</strong></td>
						<td align="center"><strong><?php echo(number_format($total_GNA,2));?></strong></td>
						<td align="right">&nbsp;</td>
					</tr>
    <?php
        
}
        $numComp ++;
        ?> 
    <?php } $iRsComp->free(); ?>
    
    <?php
    $objMP = new BLManejoProy();
    ?>
    
	<tr style="background-color: #FFC; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><strong><?php echo '10';?></strong></td>
						<td colspan="8" align="left"><strong><?php echo 'Manejo de Proyecto';?></strong></td>
					</tr>
	
	<?php
$idAnio = 1;

$aMpRes = $objMP->Inf_Financ_Lista_Personal_Total_Intv($idProy, $idAnio, $ini, $ter, $idFuente);
$rowPer = mysqli_fetch_assoc($aMpRes);
$porcEje = round((($rowPer['ejecutado'] / $rowPer['planeado']) * 100), 2);
$aMpRes->free();
?>
	<tr style="background-color: #FC9; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><?php echo "10.1";?></td>
						<td colspan="2" align="left"><?php echo 'Personal del Proyecto';?></td>
						<td align="center"><?php echo number_format($rowPer['total_presup'],2); ?></td>
						<td align="right"><?php echo number_format($rowPer['planeado'],2); ?></td>
						<td align="right"><?php echo number_format($rowPer['ejecutado'],2); ?></td>
						<td align="center"><?php echo "$porcEje%"; ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="left" nowrap="nowrap"><?php echo('10.1.12');?></td>
						<td align="left" colspan='2'><?php echo( htmlentities("Remuneraciones"));?></td>
						<td align="center"><?php echo(number_format($rowPer['total_presup'],2));?></td>
						<td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
						<td align="center"><?php echo "$porcEje%";?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
    
	<?php
$aMpRes = $objMP->Inf_Financ_Lista_Equipamiento_Total_Intv($idProy, $idAnio, $ini, $ter, $idFuente);
$rowEqu = mysqli_fetch_assoc($aMpRes);
$porcEje = round((($rowEqu['ejecutado'] / $rowEqu['planeado']) * 100), 2);
$aMpRes->free();
?>
	<tr style="background-color: #FC9; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><?php echo "10.2";?></td>
						<td colspan="2" align="left"><?php echo 'Equipamiento del Proyecto';?></td>
						<td align="center"><?php echo number_format($rowEqu['gasto_tot'],2); ?></td>
						<td align="right"><?php echo number_format($rowEqu['planeado'],2); ?></td>
						<td align="right"><?php echo number_format($rowEqu['ejecutado'],2); ?></td>
						<td align="center"><?php echo "$porcEje%"; ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="left" nowrap="nowrap"><?php echo('10.2.3');?></td>
						<td align="left" colspan='2'><?php echo( htmlentities("Equipos y Bienes Duraderos"));?></td>
						<td align="center"><?php echo(number_format($rowEqu['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqu['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqu['ejecutado'],2));?></td>
						<td align="center"><?php echo "$porcEje%";?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
    
	<?php
$aMpRes = $objMP->Inf_Financ_Lista_GastoFuncionamiento_Intv($idProy, $idAnio, $ini, $ter, $idFuente);
$rowFun = mysqli_fetch_assoc($aMpRes);
$porcEje = round((($rowFun['ejecutado'] / $rowFun['planeado']) * 100), 2);
$aMpRes->free();
?>
	<tr style="background-color: #FC9; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><?php echo "10.3";?></td>
						<td colspan="2" align="left"><?php echo 'Gastos de Funcionamiento';?></td>
						<td align="center"><?php echo number_format($rowFun['gasto_tot'],2); ?></td>
						<td align="right"><?php echo number_format($rowFun['planeado'],2); ?></td>
						<td align="right"><?php echo number_format($rowFun['ejecutado'],2); ?></td>
						<td align="center"><?php echo "$porcEje%"; ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
	<?php
$aMpRes = $objMP->Inf_Financ_Lista_PartidasGF_Intv($idProy, $idAnio, $ini, $ter, $idFuente);
while ($aRowGF = mysqli_fetch_assoc($aMpRes)) {
    $porcEje = round((($aRowGF['ejecutado'] / $aRowGF['planeado']) * 100), 2);
    ?>
	 	<tr>
						<td align="left" nowrap="nowrap"><?php echo $aRowGF['codigo'];?></td>
						<td align="left"><?php echo $aRowGF['partida'];?></td>
						<td align="center"><?php echo $aRowGF['um'];?></td>
						<td align="center"><?php echo(number_format($aRowGF['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($aRowGF['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($aRowGF['ejecutado'],2));?></td>
						<td align="center"><?php echo "$porcEje%";?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
	<?php
} // while
$aMpRes->free();
?>
    
	<?php
$aMpRes = $objMP->Inf_Financ_Lista_GastosAdministrativos_Intv($idProy, $idAnio, $ini, $ter, $idFuente);
$rowAdm = mysqli_fetch_assoc($aMpRes);
$porcEje = round((($rowAdm['ejecutado'] / $rowAdm['planeado']) * 100), 2);
$aMpRes->free();
?>
	<tr style="background-color: #FC9; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><?php echo "10.4";?></td>
						<td colspan="2" align="left"><?php echo 'Gastos Administrativos';?></td>
						<td align="center"><?php echo number_format($rowAdm['gasto_tot'],2); ?></td>
						<td align="right"><?php echo number_format($rowAdm['planeado'],2); ?></td>
						<td align="right"><?php echo number_format($rowAdm['ejecutado'],2); ?></td>
						<td align="center"><?php echo "$porcEje%"; ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
    
	<?php
$aMpRes = $objMP->Inf_Financ_Lista_Imprevistos_Intv($idProy, $idAnio, $ini, $ter, $idFuente);
$rowImp = mysqli_fetch_assoc($aMpRes);
$porcEje = round((($rowImp['ejecutado'] / $rowImp['planeado']) * 100), 2);
$aMpRes->free();
?>
	<tr style="background-color: #FC9; height: 25px; border: 1px solid;">
						<td align="left" nowrap="nowrap"><?php echo "10.6";?></td>
						<td colspan="2" align="left"><?php echo 'Imprevistos';?></td>
						<td align="center"><?php echo number_format($rowImp['gasto_tot'],2); ?></td>
						<td align="right"><?php echo number_format($rowImp['planeado'],2); ?></td>
						<td align="right"><?php echo number_format($rowImp['ejecutado'],2); ?></td>
						<td align="center"><?php echo "$porcEje%"; ?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>

				</tbody>
			</table>

		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>