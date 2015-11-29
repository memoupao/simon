<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('anio');

$objInf = new BLInformes();
$row = $objInf->InformeUnicoAnualSeleccionar($idProy, $idAnio);
$idAnio = $row['t55_anio'];
$idnum = $row['t55_id'];
$idFecha = $row['t55_fch_pre'];
$idEstado = $row['t55_estado'];
$idPeriodo = $row['t55_periodo'];
$aComponentArr = array();

$rs = $objInf->ListaComponentes($idProy);
while ($aRow = mysqli_fetch_assoc($rs)) {
    $aComponentArr[] = $aRow;
}
$rs->free();
$idFuente = 10;
?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe de Presupuesto Anual</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">
			<!-- InstanceBeginEditable name="BodyAjax" -->
			<table width="95%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<td><b>Avance Proyecto</b></td>
				</tr>
				<tr>
					<td>Avance Presupuestal</td>
				</tr>  
	
			<?php
$aIndex = 0;
while ($aIndex < count($aComponentArr) && $rcom = $aComponentArr[$aIndex ++]) {
    ?>
			<tr>
					<td><?php echo $rcom['componente']; ?>
					
					<td>
				
				</tr>
				<tr>
					<td>

						<table width="771" border="1" cellpadding="0" cellspacing="0"
							align="center">
							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
								<th width="53" rowspan="2" align="center" valign="middle">Codigo</th>
								<th width="144" rowspan="2" align="center" valign="middle">Actividades</th>
								<th width="47" rowspan="2" align="center" valign="middle">U.M.</th>
								<th width="75" rowspan="2" align="center" valign="middle">Presupuesto
								</th>
								<th height="28" colspan="3" align="center" valign="middle">Año&nbsp;<?php echo($idAnio); ?></th>
								<th width="209" rowspan="2" align="center" valign="middle">Observaciones
									del Monitor</th>
							</tr>
							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
								<th width="70" height="28" align="center" valign="middle">Programado</th>
								<th width="70" align="center" valign="middle">Ejecutado</th>
								<th align="center" valign="middle">% Ejec.</th>
							</tr>
						
					  <?php
    $total_presup = 0;
    $total_ejec = 0;
    $total_GNA = 0;
    
    $iRsAct = $objInf->Inf_UA_ListadoActividades($idProy, $rcom['t08_cod_comp'], $idnum, 10);
    
    while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
        $idAct = $rowAct['t09_cod_act'];
        
        $total_presup += $rowAct['total_presup'];
        $total_ejec += $rowAct['ejecutado'];
        $total_planeado += $rowAct['programado'];
        
        $porcEjecucion = round((($rowAct['ejecutado'] / $rowAct['programado']) * 100), 2);
        
        ?>  
					   <tbody class="data">
								<tr
									style="background-color: #FC9; height: 25px; cursor: pointer;"
									onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
									<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
									<td colspan="2" align="left"><?php echo( $rowAct['actividad']);?></td>
									<td align="center"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
									<td align="right"><?php echo(number_format($rowAct['programado'],2,'.',','));?></td>
									<td align="right"><?php echo(number_format($rowAct['ejecutado'],2,'.',','));?></td>
									<td align="center"><?php echo($porcEjecucion);?>%</td>
									<td align="center">&nbsp;</td>
								</tr>
							</tbody>
							<tbody class="data" bgcolor="#FFFFFF"
								id="<?php echo("tbody_".$idFte.'_'.$idAct);?>">
					<?php
        $iRs = $objInf->Inf_UA_ListadoSubActividades($idProy, $rcom['t08_cod_comp'], $idAct, $idnum, 10);
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
									<td align="left"><?php echo($rowsub['observaciones']);?></td>
								</tr>
					  <?php } $iRs->free(); // Fin de SubActividades ?>
					  </tbody>
					  <?php } $iRsAct->free(); // Fin de Actividades 	?>
					<tfoot>
								<tr style="color: #FFF; height: 20px;">
									<th colspan="3">Totales x Componente &nbsp;&nbsp;</th>
									<th align="center"><?php echo(number_format($total_presup,2,'.',','));?></th>
									<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
									<th align="right"><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</th>
									<th align="center"><?php echo(round((($total_ejec/$total_planeado)*100),2));?>%</th>
									<th align="right">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
					</td>
				</tr>
			<?php
}
?>
			
		<tr>
					<td>Comparativo Avance Físico y Presupuestal</td>
				</tr> 
		<?php
$aIndex = 0;
while ($aIndex < count($aComponentArr) && $rcom = $aComponentArr[$aIndex ++]) {
    
    ?>
			<tr>
					<td><?php echo $rcom['componente']; ?></td>
				</tr>
				<tr>
					<td>
						<table width="771" border="1" cellpadding="0" cellspacing="0"
							align="center">
							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
								<th width="53" rowspan="2" align="center" valign="middle">Codigo</th>
								<th width="144" rowspan="2" align="center" valign="middle">Actividades</th>
								<th width="47" rowspan="2" align="center" valign="middle">U.M.</th>
								<th width="37" rowspan="2" align="center" valign="middle">Metas</th>
								<th width="38" rowspan="2" align="center" valign="middle">Presupuesto</th>
								<th height="28" colspan="3" align="center" valign="middle">Año&nbsp;<?php echo($idAnio); ?></th>
								<th width="209" rowspan="2" align="center" valign="middle">Observaciones
									del Monitor</th>
							</tr>
							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
								<th width="70" height="28" align="center" valign="middle">Programado</th>
								<th width="70" align="center" valign="middle">Ejecutado</th>
								<th width="35" align="center" valign="middle">% Ejec.</th>
							</tr>        
						  <?php
    $total_presup = 0;
    $total_ejec = 0;
    
    $iRsAct = $objInf->Inf_UA_ListadoActividades2($idProy, $rcom['t08_cod_comp'], $idnum);
    
    while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
        $idAct = $rowAct['t09_cod_act'];
        
        $total_presup += $rowAct['total_presup'];
        $total_ejec += $rowAct['ejecutado'];
        $total_planeado += $rowAct['programado'];
        
        $porcEjecucion = round((($rowAct['ejecutado'] / $rowAct['programado']) * 100), 2);
        ?>  
						   <tbody class="data">
								<tr
									style="background-color: #FC9; height: 25px; cursor: pointer;"
									onclick="ShowActividadFis('<?php echo("tbody2_".$idFte.'_'.$idAct);?>');">
									<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
									<td colspan="2" align="left"><?php echo( $rowAct['actividad']);?></td>
									<td align="center"></td>
									<td align="right"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
									<td align="center"><?php echo(round($rowAct['programado'],2));?></td>
									<td align="center"><?php echo(round($rowAct['ejecutado'],2));?></td>
									<td align="center"><?php echo($porcEjecucion);?>%</td>
									<td align="center">&nbsp;</td>
								</tr>
							</tbody>
							<tbody class="data" bgcolor="#FFFFFF"
								id="<?php echo("tbody2_".$idFte.'_'.$idAct);?>">
						<?php
        $iRs = $objInf->Inf_UA_ListadoSubActividades2($idProy, $rcom['t08_cod_comp'], $idAct, $idnum);
        
        while ($rowsub = mysqli_fetch_assoc($iRs)) {
            $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
            ?>
						<tr>
									<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
									<td align="left"><?php echo( $rowsub['subactividad']);?></td>
									<td align="center"><?php echo($rowsub['um']);?></td>
									<td align="center"><?php echo(round($rowsub['meta'],2));?></td>
									<td align="right"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
									<td align="center"><?php echo(round($rowsub['programado'],2));?></td>
									<td align="center"><?php echo(round($rowsub['ejecutado'],2));?></td>
									<td align="center"><?php echo($porcEjecucion);?>%</td>
									<td align="left"><?php echo($rowsub['observaciones']);?></td>
								</tr>
						  <?php } $iRs->free(); // Fin de SubActividades ?>
						  </tbody>
						  <?php } $iRsAct->free(); // Fin de Actividades 	?>
						<tfoot>
								<tr style="color: #FFF; height: 20px;">
									<th colspan="3">Totales x Componente &nbsp;&nbsp;</th>
									<th colspan="2" align="center"><?php echo(number_format($total_presup,2,'.',','));?></th>
									<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
									<th align="right"><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</th>
									<th align="center"><?php echo(round((($total_ejec/$total_planeado)*100),2));?>%</th>
									<th align="right">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
					</td>
				
				
				<tr>
		<?php
} // end while
?>
		
		
				
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Programación vs Avance por Componentes</b></td>
				</tr>
		<?php
$aIndex = 0;
while ($aIndex < count($aComponentArr) && $rcom = $aComponentArr[$aIndex ++]) {
    $aResult = $objInf->ListaIndicadoresComponenteIUA($idProy, $rcom['t08_cod_comp'], $idAnio);
    if ($aResult->num_rows > 0) {
        ?>
		<tr>
					<td><?php echo $rcom['componente']; ?></td>
				</tr>
				<tr>
					<td>
						<table width="780" cellpadding="0" cellspacing="0" align="center">
							<tbody class="data" bgcolor="#FFFFFF">
								<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="412" rowspan="2" align="left" valign="middle"><strong>Indicadores
											de Componente</strong></td>
									<td height="15" colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Meta Ejecutada Según Ejecutor</strong></td>
									<td align="center" valign="middle" bgcolor="#CCCCCC"><strong>Meta
											Ejecutada Según Monitor Externo</strong></td>
								</tr>
								<tr>
									<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="55" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Observación</strong></td>
								</tr>
			<?php
        while ($row = mysqli_fetch_assoc($aResult)) {
            ?>
					<tr>
									<td width="377" align="left" valign="middle">
							<?php echo($row['t08_cod_comp_ind'] . " " . $row['indicador']);?><br />
										<span><strong style="color: red;">Unidad Medida</strong>: <?php echo( $row['t08_um']);?></span>
									</td>
									<td align="center" nowrap="nowrap"><?php echo number_format($row['plan_mtatotal'], 2);?></td>
									<td align="center" nowrap="nowrap"><?php echo number_format($row['meta_acum'], 2);?></td>
									<td align="center" nowrap="nowrap"><?php echo number_format($row['meta_anual'], 2);?></td>
									<td align="center" nowrap="nowrap"><?php echo number_format($row['meta_acum_tri'], 2);?></td>
									<td align="center" nowrap="nowrap"><?php echo number_format($row['meta_anual_tri'], 2);?></td>
									<td align="center" nowrap="nowrap"><?php echo number_format($row['meta_acum_tri']+$row['meta_anual_tri'], 2);?></td>
									<td align="center" nowrap="nowrap"><?php echo $row['descrip'];?></td>
								</tr>
			<?php
        } // end while
        ?>
				</tbody>
						</table>
					</td>
				
				
				<tr>
		<?php
    } // end if
    $aResult->free();
} // end while
?>

		
				
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Programación vs Sub Actividades Críticas</b></td>
				</tr>
				<tr>
					<td>Sub Actividades</td>
				</tr>
		<?php
$aActivityArr = array();
$rss = $objInf->ListaActividades($idProy);
while ($aRow = mysqli_fetch_assoc($rss)) {
    $aActivityArr[] = $aRow;
}
$rss->free();

$aActIdx = 0;
while ($aActIdx < count($aActivityArr) && $sub = $aActivityArr[$aActIdx ++]) {
    $iRs = $objInf->ListaSubActividadesIUA($idProy, $sub['codigo'], $idAnio, $idnum);
    if ($iRs->num_rows > 0) {
        ?>
			<tr>
					<td>
					<?php echo $sub['actividad']; ?>
				</td>
				</tr>
				<tr>
					<td>
						<table width="780" cellpadding="0" cellspacing="0"
							class="TableEditReg" align="center">
							<tbody class="data" bgcolor="#FFFFFF">
								<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="377" rowspan="2" align="left" valign="middle"><strong>Subactividades
											Críticas</strong></td>
									<td height="15" colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td height="15" colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Meta Ejecutada Según Ejecutor</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Ejecutada Según Monitor
											Externo</strong></td>
								</tr>
								<tr>
									<td width="57" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="57" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="54" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
									<td width="52" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="54" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
									<td width="49" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="73" align="center" bgcolor="#CCCCCC"><strong>Anual</strong></td>
									<td width="73" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
								</tr>
						<?php
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
						<tr>
									<td width="377" align="left" valign="middle">
								<?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?><br />
										<span><strong style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span>
									</td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaanio']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaanual']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']+$row['ejec_mtaanual']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacumext']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaanualext']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacumext']+$row['ejec_mtaanualext']);?></td>
								</tr>
					 	<?php
        } // while
        ?>
					</tbody>
						</table>
					</td>
				</tr>
		<?php
    } // end if
    $iRs->free();
} // end while
?>
		
			<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Calificación del Monitor</b></td>
				</tr>

				<tr>
					<td>
						<table width="750" cellpadding="0" cellspacing="0" align="center">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr>

									<td align="center" valign="top">
										<table width="100%" border="0" cellspacing="0" cellpadding="0"
											style="font-size: 10px;">
											<tr>
												<td width="29%" align="center" bgcolor="#E8E8E8"><strong>Fecha</strong></td>
												<td width="28%" align="center" valign="middle"
													bgcolor="#E8E8E8"><strong>Calificación Monitor Externo</strong></td>
												<td width="22%" align="center" valign="middle"
													bgcolor="#E8E8E8"><strong>Calificación Monitor Interno</strong></td>
												<td width="21%" align="center" valign="middle"
													bgcolor="#E8E8E8"><strong>Calificación Monitor Financiero</strong></td>
											</tr>
							<?php
    $retCalif = $objInf->RepCalificacionIUA($idProy, $idAnio);
    
    while ($rowInf = mysqli_fetch_assoc($retCalif)) {
        $msg_ME = '';
        $msg_MI = '';
        $msg_MF = '';
        $valoraME = "";
        if ($rowInf['Califica_ME'] != '') {
            $msg_ME = $objFunc->calificacionInforme($rowInf['Califica_ME'], array(
                "style='background-color:red'",
                "style='background-color:#FC0;'",
                "style='background-color:#70FB60;'"
            ), $valoraME);
        }
        
        $valoraMI = "";
        if ($rowInf['Califica_MI'] != '') {
            $msg_MI = $objFunc->calificacionInforme($rowInf['Califica_MI'], array(
                "style='background-color:red'",
                "style='background-color:#FC0;'",
                "style='background-color:#70FB60;'"
            ), $valoraMI);
        }
        
        $valoraMF = "";
        if ($rowInf['Califica_MF'] != '') {
            if ($rowInf['Califica_MF'] == 0) {
                $valoraMF = "style='background-color:red'";
                $msg_MF = "Desaprobado";
            }
            if ($rowInf['Califica_MF'] == 1) {
                $valoraMF = "style='background-color:#FC0;'";
                $msg_MF = "Aprobado con Reservas";
            }
            if ($rowInf['Califica_MF'] == 2) {
                $valoraMF = "style='background-color:#70FB60;'";
                $msg_MF = "Aprobado";
            }
        }
        
        ?>
							<tr>
												<td align="center"><?php echo($rowInf['fecha']);?></td>
												<td align="center" valign="middle" <?php echo($valoraME);?>><?php echo($msg_ME);?></td>
												<td align="center" valign="middle" <?php echo($valoraMI);?>><?php echo($msg_MI);?></td>
												<td align="center" valign="middle" <?php echo($valoraMF);?>><?php echo($msg_MF);?></td>
											</tr>
							
							<?php
    }
    $retCalif->free();
    ?>
							
						  </table>
									</td>

								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table>

					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Análisis</b></td>
				</tr>
				<tr>
					<td>
					<?php
    $objInf = new BLInformes();
    
    $row = $objInf->Inf_UA_Seleccionar($idProy, $idnum);
    
    ?>
					<div id="divTableLista" class="TableGrid">
							<table width="750" cellpadding="0" cellspacing="0" align="center">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; font-size: 12px;">Análisis de
												Avances en relación a los componentes </span> <br>
											<div
												style="min-height: 150px; width: 738px; border: thin solid black"><?php echo($row['t55_avan_comp']);?></div></td>
									</tr>
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; font-size: 12px;">Análisis de
												Avances en Capacitación, Asistencia Técnica y Otros
												Servicios a Beneficiarios</span><br>
											<div
												style="min-height: 150px; width: 738px; border: thin solid black"><?php echo($row['t55_avan_cap']);?></div></td>
									</tr>
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; font-size: 12px;">Análisis de
												Avance financiero </span><br>
											<div
												style="min-height: 150px; width: 738px; border: thin solid black"><?php echo($row['t55_avan_fin']);?></div></td>
									</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><b>Información Adicional</b></td>
				</tr>
				<tr>
					<td>
					<?php
    $objInf = new BLInformes();
    $row = $objInf->Inf_UA_Seleccionar($idProy, $idnum);
    
    ?>
					 
					<div id="divTableLista" class="TableGrid" align="center">
							<table width="750" cellpadding="0" cellspacing="0">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
									<tr>
										<td align="left" valign="middle"><span
											style="font-weight: bold; font-size: 12px;"> Calificación
												del Proyecto</span> <br />
											<table width="100%" border="0" cellpadding="0"
												cellspacing="0" class="TableEditReg">
												<tr>
													<td width="39%">&nbsp;</td>
													<td width="22%" align="center"><b>Valoración</b></td>
													<td width="39%" align="center"><b>Comentarios</b></td>
												</tr>
												<tr>
													<td align="left">Relación planificado y ejecutado
														operativo</td>
													<td align="center"><select name="t55_eva1" id="t55_eva1"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected" title="2"></option>
							  <?php
        $objTablas = new BLTablasAux();
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva1'], 'cod_ext');
        ?>
							  </select></td>
													<td align="center"><?php echo($row['t55_eva1_obs']);?>
							</td>
												</tr>
												<tr>
													<td align="left">Relación entre ejecución financiera y
														ejecución técnica.</td>
													<td align="center"><select name="t55_eva2" id="t55_eva2"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected"></option>
								<?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva2'], 'cod_ext');
        ?>
							  </select></td>
													<td align="center"><?php echo($row['t55_eva2_obs']);?></td>
												</tr>
												<tr>
													<td align="left">Avance de actividades críticas</td>
													<td align="center"><select name="t55_eva3" id="t55_eva3"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected"></option>
								<?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva3'], 'cod_ext');
        ?>
							  </select></td>
													<td align="center"><?php echo($row['t55_eva3_obs']);?></td>
												</tr>
												<tr>
													<td align="left">Calidad de las Capacitaciones</td>
													<td align="center"><select name="t55_eva4" id="t55_eva4"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected"></option>
								<?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva4'], 'cod_ext');
        ?>
							  </select></td>
													<td align="center"><?php echo($row['t55_eva4_obs']);?></td>
												</tr>
												<tr>
													<td align="left">Calidad&nbsp; y congruencia&nbsp;
														(capacidad de cobertura del ámbito del proyecto) del
														equipo técnico</td>
													<td align="center"><select name="t55_eva5" id="t55_eva5"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected"></option>
								<?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva5'], 'cod_ext');
        ?>
							  </select></td>
													<td align="center"><?php echo($row['t55_eva5_obs']);?></td>
												</tr>
												<tr>
													<td align="left">Opinión de los beneficiarios respecto al
														proyecto y sus resultados</td>
													<td align="center"><select name="t55_eva6" id="t55_eva6"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected"></option>
								  <?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva6'], 'cod_ext');
        ?>
								  </select></td>
													<td align="center"><?php echo($row['t55_eva6_obs']);?></td>
												</tr>
												<tr>
													<td align="left">Manejo Adecuado de Proyecto</td>
													<td align="center"><select name="t55_eva7" id="t55_eva7"
														style="width: 150px;" onchange="CalcularResultado();"
														class="InformacionAdicional" disabled="disabled">
															<option value="" selected="selected"></option>
								  <?php
        $rs = $objTablas->ValoraInformesME();
        $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t55_eva7'], 'cod_ext');
        ?>
								  </select></td>
													<td align="center"><?php echo($row['t55_eva6_obs']);?></td>
												</tr>
												<tr>
							<?php
    $aprobado = $objFunc->calificacionInforme($row['puntaje']);
    ?>
							  <td align="right"><strong>RESULTADO</strong></td>
													<td align="center"><span id="spanTotResult"><?php echo($row['puntaje'])?>&nbsp;</span></td>
													<td align="left"><span id="spanTotResult2"><?php echo($aprobado)?>&nbsp;</span></td>
												</tr>
											</table> <br></td>
									</tr>
									<tr>
										<td align="left" valign="middle">
											<fieldset>
												<legend>Conclusiones</legend>
												<span style="font-weight: bold; font-size: 12px;">Logros </span><br>
												<div
													style="min-height: 75px; width: 715px; border: thin solid black"><?php echo($row['t55_logros']);?></div>
												<br /> <span style="font-weight: bold; font-size: 12px;">Dificultades
												</span><br>
												<div
													style="min-height: 75px; width: 715px; border: thin solid black"><?php echo($row['t55_dificultades']);?></div>
												<br />
											</fieldset> <br />
											<fieldset>
												<legend>Recomendaciones</legend>
												<span style="font-weight: bold; font-size: 12px;">Recomendaciones
													al Proyecto para el próximo POA</span><br>
												<div
													style="min-height: 75px; width: 715px; border: thin solid black"><?php echo($row['t55_reco_proy']);?></div>
												<br /> <span style="font-weight: bold; font-size: 12px;">Recomendaciones
													a Fondoempleo </span><br>
												<div
													style="min-height: 75px; width: 715px; border: thin solid black"><?php echo($row['t55_reco_fe']);?></div>
												<br />
											</fieldset> <br />
											<fieldset>
												<legend>Calificación</legend>
												<span style="font-weight: bold; font-size: 11px;">Ingresar
													comentario respectivo de acuerdo a la calificación dada </span><br>
												<div
													style="min-height: 75px; width: 715px; border: thin solid black"><?php echo($row['t55_recomendaciones']);?></div>
												<br />
											</fieldset>
										</td>
									</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					</td>

				</tr>
			</table>
			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>