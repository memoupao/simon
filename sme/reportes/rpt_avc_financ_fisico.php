<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");

$objInf = new BLMonitoreoFinanciero();
// $objPresup = new BLPresupuesto();

// $idVersion = $objPresup->Proyecto->MaxVersion($idProy);

error_reporting("E_PARSE");

$idProy = $objFunc->__Request('idProy');
$ini = $objFunc->__Request('ini');
$ter = $objFunc->__Request('ter');

// $idFte = $objFunc->__POST('idFte');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Informe de MF</title>
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
<div id="divTableLista" class="TableGrid">
			<table width="771" border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr style="border: solid 1px #CCC; background-color: #FFF;">
						<td height="28" colspan="9" align="center" valign="middle"><strong>Avance
								Fisico</strong></td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="53" rowspan="2" align="center" valign="middle"><strong>Codigo</strong></td>
						<td width="144" rowspan="2" align="center" valign="middle"><strong>Actividades</strong></td>
						<td width="47" rowspan="2" align="center" valign="middle"><strong>U.M.</strong></td>
						<td width="37" rowspan="2" align="center" valign="middle"><strong>Metas</strong></td>
						<td width="38" rowspan="2" align="center" valign="middle"><strong>Presupuesto</strong></td>
						<td height="28" colspan="3" align="center" valign="middle"><strong>Total Periodo <?php echo(" (".$ini." - ".$ter.")"); ?></strong></td>
						<td width="209" rowspan="2" align="center" valign="middle"><strong>Observaciones</strong></td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="70" height="28" align="center" valign="middle"><strong>Programado</strong></td>
						<td width="70" align="center" valign="middle"><strong>Ejecutado</strong></td>
						<td width="35" align="center" valign="middle"><strong>% Ejec.</strong></td>
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
            
            ?>  
        <tr
						style="background-color: #FFC; height: 25px; border: solid 1px #CCC;">
						<td align="left" nowrap="nowrap"><strong><?php echo( $rowComp['t08_cod_comp']);?></strong></td>
						<td colspan="8" align="left"><strong><?php echo( $rowComp['t08_comp_desc']);?></strong></td>
					</tr>
      <?php
            
            $total_presup = 0;
            $total_ejec = 0;
            
            $iRsAct = $objInf->Rpt_MF_Avance_Fisico_Act($idProy, $idComp, $ini, $ter);
            
            while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
                $idAct = $rowAct['t09_cod_act'];
                
                $total_presup += $rowAct['total_presup'];
                $total_ejec += $rowAct['ejecutado'];
                $total_planeado += $rowAct['programado'];
                // $porcEjecucion = round((($rowAct['ejecutado'] / $rowAct['programado'] ) * 100 ), 2) ;
                
                ?>  
        <tr
						style="background-color: #FC9; height: 25px; border: solid 1px;">
						<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
						<td colspan="3" align="left"><?php echo( $rowAct['actividad']);?></td>
						<td align="right"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>

    <?php
                $iRs = $objInf->Rpt_MF_Avance_Fisico_SubAct($idProy, $idComp, $idAct, $ini, $ter);
                
                while ($rowsub = mysqli_fetch_assoc($iRs)) {
                    $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
                    ?>
   	 	<tr bgcolor="#FFFFFF">
						<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
						<td align="left"><?php echo( $rowsub['subactividad']);?></td>
						<td align="center"><?php echo($rowsub['um']);?></td>
						<td align="center"><?php echo(round($rowsub['meta'],2));?></td>
						<td align="right"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
						<td align="center"><?php echo(round($rowsub['programado'],2));?></td>
						<td align="center"><?php echo(round($rowsub['ejecutado'],2));?></td>
						<td align="center"><?php echo($porcEjecucion);?>%</td>
						<td align="center"><?php echo($rowsub['observaciones']);?></td>
					</tr>
      <?php } $iRs->free(); // Fin de SubActividades ?>
	  <?php } $iRsAct->free(); // Fin de Actividades 	?>
      
	<?php if( $numComp <= $iRsComp->num_rows ) { ?>  
    <tr style="color: #00C; height: 20px;">
						<td colspan="3" align="center"><strong>Totales x Componente
								&nbsp;&nbsp;</strong></td>
						<td colspan="2" align="right"><strong><?php echo(number_format($total_presup,2,'.',','));?></strong></td>
						<td align="right"></td>
						<td align="right"></td>
						<td align="center"></td>
						<td align="right">&nbsp;</td>
					</tr>
    <?php
            
}
            $numComp ++;
            ?> 
    <?php } $iRsComp->free(); ?>
    </tbody>
			</table>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>