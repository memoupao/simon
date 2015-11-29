<?php
/**
 * CticServices
 *
 * Gestiona el Reporte de Cronograma de Actividades
 *
 * @package     sme/reportes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");

require (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);
$objProy = new BLProyecto();

if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Plan Operativo</title>
    <script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../css/reportes.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
        <div id="divBodyAjax" class="TableGrid" style="width: 100%; text-align: left;">
            <div style="width: 99%; display:block; margin: 0 auto;">
            <table cellpadding="0" cellspacing="1" style=" border:1px solid #525E94; display:block; width: 100%;">
                <tr>
                	<th style="width: 20%; text-align: left;">CODIGO DEL PROYECTO</th>
                	<td style="width: 60%; text-align: left;"><?php echo($ML['t02_cod_proy']);?></td>
                	<th style="width:10%; text-align: left;">INICIO</th>
                	<td style="width: 10%; text-align: left;"><?php echo($ML['t02_fch_ini']);?></td>
                </tr>
                <tr>
                	<th align="left" nowrap="nowrap">DESCRIPCION DEL PROYECTO</th>
                	<td align="left"><?php echo($ML['t02_nom_proy']);?></td>
                	<th align="left" nowrap="nowrap">TERMINO</th>
                	<td align="left"><?php echo($ML['t02_fch_ter']);?></td>
            	</tr>
            	<tr>
            		<th align="left">&nbsp;</th>
            		<td>&nbsp;</td>
            		<td>&nbsp;</td>
            		<td>&nbsp;</td>
            	</tr>
            </table>
            </div>
    <?php
        $anios = $ML['duracion'];
        $MergeMeses = '<td width="32" align="center" valign="middle">1</td>
        				<td width="32" align="center" valign="middle">2</td>
        				<td width="32" align="center" valign="middle">3</td>
        				<td width="32" align="center" valign="middle">4</td>
        				<td width="32" align="center" valign="middle">5</td>
        				<td width="32" align="center" valign="middle">6</td>
        				<td width="32" align="center" valign="middle">7</td>
        				<td width="32" align="center" valign="middle">8</td>
        				<td width="32" align="center" valign="middle">9</td>
        				<td width="32" align="center" valign="middle">10</td>
        				<td width="32" align="center" valign="middle">11</td>
        				<td width="32" align="center" valign="middle">12</td>';
        ?>
            <table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto; width: 99%;">
				<thead style="font-size: 11px;">
					<tr>
						<td align="center" valign="middle">&nbsp;</td>
						<td height="28" align="center" valign="middle">Año</td>
						<td rowspan="3" align="center" valign="middle">Unidad de Medida</td>
						<td width="41" rowspan="3" align="center" valign="middle">Meta Física</td>
						<?php

    				    $i = 0;
    				    while ($anios > $i) {
                            $i++;
                    ?>
                        <td colspan="12" align="center" valign="middle">Año <?php echo($i);?></td>
    			     <?php } ?>
                        <td rowspan="3" align="center" valign="middle">TOTAL</td>
					</tr>
				    <tr>
				        <td width="27" rowspan="2" align="center" valign="middle">&nbsp;</td>
						<td rowspan="2" align="center" valign="middle">MES</td>
					<?php
					$j = 0;
                        while(MESES > $j){
                            $j++;
                        ?>
						<td width="33" height="28" align="center" valign="middle"><?php echo($j);?></td>
                    <?php
                        }
                        for ($x = 2; $x <= $anios; $x ++) {
                            echo ($MergeMeses);
                        }
                    ?>
                    </tr>
					<tr style="font-size: 10px">
                    <?php
                    $arrPProy = NULL;

                    for ($x = 1; $x <= $anios; $x ++) {
                        $irsPeriodo = $objProy->PeriodosxAnio($idProy, $x);
                        $arrPeriodo = NULL;
                        $cont = 1;
                        while ($r = mysqli_fetch_assoc($irsPeriodo)) {
                            $arrPeriodo[$cont] = $r;
                            $arrPProy[((12 * $x) - 12) + $cont] = $r;
                            $cont ++;
                        }
                        $irsPeriodo->free();

                        $j = 0;
                        while(MESES > $j){
                            $j++;
                        ?>
                        <td align="center" valign="middle" style="min-width: 35px;"><?php echo($arrPeriodo[$j]['nom_abrev']." ".$arrPeriodo[$j]['num_anio']);?></td>
                    <?php } } ?>
                    </tr>
                </thead>
				<tbody class="data">
                <?php
                    $objPOA = new BLPOA();

                    $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
                    while ($rowcomp = mysql_fetch_assoc($rsComp)) {

						if (empty($rowcomp['t08_comp_desc'])) {
							continue;
						}											

                ?>
                    <tr class="RowData" style="background-color: #D7DC78;">
                        <td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp']);?></td>
                        <td align="left" colspan="<?php echo($anios*MESES  + 4);?>"><?php echo($rowcomp['t08_comp_desc']);?></td>
                    </tr>
                <?php
                    $rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
                    while ($rowact = mysql_fetch_assoc($rsAct)) {
                ?>
                    <tr class="RowData" style="background-color: #EEF8AD;">
                        <td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act']);?></td>
                        <td align="left" colspan="<?php echo($anios*MESES + 4);?>"><?php echo($rowact['t09_act']);?></td>
                    </tr>
                <?php
                    $iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
                    while ($rowInd = mysql_fetch_assoc($iRs)) {
                ?>
                    <tr class="RowData" style="background-color: #EEF8FA;">
						<!-- <td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind']);?></td> -->
						<td align="left" nowrap="nowrap"></td>
						<td align="left"><?php echo($rowInd['t09_ind']);?></td>
						<td align="center"><?php echo($rowInd['t09_um']);?></td>
						<td align="center" valign="middle" style="background-color: #eeeeee;"><?php echo(number_format($rowInd['t09_mta'],0));?></td>

						<?php
                            $i = 0;
                            while ($anios > $i) {
                                $i++;
                                $j = 0;

                                $lista = $objML->getProgramaIndicador($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act'], $rowInd['t09_cod_act_ind'], $i);

                                while(MESES > $j){
                                    $j++;
                                    echo '<td align="center" valign="middle">'.(array_key_exists($j, $lista)?$lista[$j]:'').'</td>';
                                }
                            } ?>
                        <td align="center" valign="middle" style="min-width: 40px;"></td>
					</tr>
        <?php } //Fin Indicadores ?>
            <?php
                    $rsSubAct = $objPOA->ListadoSubActividades($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
                    while ($rowsub = mysqli_fetch_assoc($rsSubAct)) {
                    ?>
                    <tr class="RowData">
						<td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowsub['t09_cod_sub']);?></td>
						<td align="left"><?php echo($rowsub['t09_sub']);?></td>
						<td align="center"><?php echo($rowsub['t09_um']);?></td>
						<td align="center" valign="middle"
							style="background-color: #eeeeee;"><?php echo(number_format($rowsub['t09_mta'],0));?></td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_1']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_2']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_3']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_4']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_5']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_6']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_7']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_8']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_9']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_10']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_11']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_1_12']);?>&nbsp;</td>
                    <?php

                    $rowtot = $rowsub['mes_1_1'] + $rowsub['mes_1_2'] + $rowsub['mes_1_3'] + $rowsub['mes_1_4'] + $rowsub['mes_1_5'] + $rowsub['mes_1_6'] + $rowsub['mes_1_7'] + $rowsub['mes_1_8'] + $rowsub['mes_1_9'] + $rowsub['mes_1_10'] + $rowsub['mes_1_11'] + $rowsub['mes_1_12'];

                    for ($x = 2; $x <= $anios; $x ++) {
                        $nummes = ((12 * $x) - 12);
                        $Meses = '
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 1]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . ' >' . $rowsub['mes_' . $x . '_1'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 2]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_2'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 3]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_3'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 4]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_4'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 5]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_5'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 6]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_6'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 7]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_7'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 8]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_8'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 9]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_9'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 10]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_10'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 11]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_11'] . '&nbsp;</td>
    				<td align="center" valign="middle" ' . ($arrPProy[$nummes + 12]['mes_ok'] == '0' ? 'style="background-color:#DDD9B7"' : '') . '>' . $rowsub['mes_' . $x . '_12'] . '&nbsp;</td>';

                        $rowtot += $rowsub['mes_' . $x . '_1'] + $rowsub['mes_' . $x . '_2'] + $rowsub['mes_' . $x . '_3'] + $rowsub['mes_' . $x . '_4'] + $rowsub['mes_' . $x . '_5'] + $rowsub['mes_' . $x . '_6'] + $rowsub['mes_' . $x . '_7'] + $rowsub['mes_' . $x . '_8'] + $rowsub['mes_' . $x . '_9'] + $rowsub['mes_' . $x . '_10'] + $rowsub['mes_' . $x . '_11'] + $rowsub['mes_' . $x . '_12'];

                        echo ($Meses);
                }
                ?>
                    <td align="center" valign="middle" style="min-width: 40px;"><?php echo( number_format($rowtot,0));?></td>
                </tr>
            <?php } $rsSubAct->free(); //Fin SubActividades ?>
        <?php } //Fin Productos ?>
        <?php } //Fin Componentes ?>
                    <tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.1</td>
						<td>Personal del Proyecto </td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						
						<td align="right">&nbsp;</td>

					</tr>
					<tr class="RowData" style="background-color: #EEF8AD;">
						<td nowrap="nowrap">10.1.12</td>
						<td>Remuneraciones</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						
						<td align="right">&nbsp;</td>
						
					</tr>
					
					<tr class="RowData">					
	  <?php

$per = $objPOA->Inf_Financ_Lista_Personal_CA($idProy, $idVersion);

$c = '';
$totalAnio = 0;
$tot = 0;
while ($p = mysqli_fetch_assoc($per)) {
	
	if ($c == $p['t03_id_per']) {
		
		 ?>
		 		<td><?php echo $p['t03_mes1'] ? $p['t03_mes1'] : '';?></td>
				<td><?php echo $p['t03_mes2'] ? $p['t03_mes2'] : '';?></td>
				<td><?php echo $p['t03_mes3'] ? $p['t03_mes3'] : '';?></td>
				<td><?php echo $p['t03_mes4'] ? $p['t03_mes4'] : '';?></td>
				<td><?php echo $p['t03_mes5'] ? $p['t03_mes5'] : '';?></td>
				<td><?php echo $p['t03_mes6'] ? $p['t03_mes6'] : '';?></td>
				<td><?php echo $p['t03_mes7'] ? $p['t03_mes7'] : '';?></td>
				<td><?php echo $p['t03_mes8'] ? $p['t03_mes8'] : '';?></td>
				<td><?php echo $p['t03_mes9'] ? $p['t03_mes9'] : '';?></td>
				<td><?php echo $p['t03_mes10'] ? $p['t03_mes10'] : '';?></td>
				<td><?php echo $p['t03_mes11'] ? $p['t03_mes11'] : '';?></td>
				<td><?php echo $p['t03_mes12'] ? $p['t03_mes12'] : '';?></td>
				
		 <?php 
		
	} else { ?>
			
			</tr>
			<tr class="RowData">
				<td>10.1.12.<?php echo $c; ?></td>
				<td><?php echo $p['t03_nom_per']; ?></td>
				<td><?php echo $p['abrev']; ?></td>
				<td><?php echo $p['totalMeta'];?></td>
				
				<td><?php echo $p['t03_mes1'] ? $p['t03_mes1'] : '';?></td>
				<td><?php echo $p['t03_mes2'] ? $p['t03_mes2'] : '';?></td>
				<td><?php echo $p['t03_mes3'] ? $p['t03_mes3'] : '';?></td>
				<td><?php echo $p['t03_mes4'] ? $p['t03_mes4'] : '';?></td>
				<td><?php echo $p['t03_mes5'] ? $p['t03_mes5'] : '';?></td>
				<td><?php echo $p['t03_mes6'] ? $p['t03_mes6'] : '';?></td>
				<td><?php echo $p['t03_mes7'] ? $p['t03_mes7'] : '';?></td>
				<td><?php echo $p['t03_mes8'] ? $p['t03_mes8'] : '';?></td>
				<td><?php echo $p['t03_mes9'] ? $p['t03_mes9'] : '';?></td>
				<td><?php echo $p['t03_mes10'] ? $p['t03_mes10'] : '';?></td>
				<td><?php echo $p['t03_mes11'] ? $p['t03_mes11'] : '';?></td>
				<td><?php echo $p['t03_mes12'] ? $p['t03_mes12'] : '';?></td>
		<?php 

	}
	
	
	
	
	$c = $p['t03_id_per'];
	$totalAnio = $p['t03_tot_anio'];
	$tot++;
	
}




/*
$c = 0;
$cont = 0;
?>
	  <?php while ($p = mysqli_fetch_assoc($per)) {

	  		if ($c != $p['t03_id_per']) {?>	  
				<?php if($c != 0) {?>
					<?php
					for($i = $cont; $i <=  ($anios*12) - 1; $i++) {
						echo '<td></td>';
					} 
					echo '<td>'.$totMeta.'</td>';
					$cont = 0;
					?>
				</tr>
				<?php
				
					} 
				
				$c = $p['t03_id_per']; 
				$totMeta = $p['totMeta'];
				?>
				<tr class="RowData">
						<td>10.1.12.<?php echo $c; ?></td>
						<td><?php echo $p['t03_nom_per']; ?></td>
						<td><?php echo $p['abrev']; ?></td>
						<td><?php echo $p['totMeta'];?></td>
	  <?php } ?>
	  		
				<?php echo '<td>'.$p['t03_mta'].'</td>'; ?>  

	  <?php 	  		
	  		$cont++; 
		} ?>
	  
	  
	  <?php for($i = $cont; $i <= ($anios*12) - 1; $i++){ ?>
		<td></td>
	  <?php }  ?>
	  <?php  if($c != 0) {?>
	  				<td><?php echo $totMeta;?></td>					
					</tr>
	  <?php  } */?>


	  <tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.2</td>
						<td>Equipamiento del Proyecto</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						
						<td align="right">&nbsp;</td>

					</tr>
					<tr class="RowData" style="background-color: #EEF8AD;">
						<td nowrap="nowrap">10.2.3</td>
						<td>Equipos y Bienes Duraderos</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>
					</tr>

	  <?php

$equi = $objPOA->Inf_Financ_Lista_Equi_CA($idProy, $idVersion);
$c = 0;
$cont = 1;
?>

	  <?php

while ($e = mysqli_fetch_assoc($equi)) {
    ?>
				<tr>
				<?php if($c !=  $e['t03_id_equi']){ ?>
						<?php if($c != 0){ echo "</tr>";} ?>



					<tr class="RowData">
						<td>10.2.<?php echo $e['t03_id_equi']; ?></td>
						<td><?php echo $e['t03_nom_equi']; ?></td>
						<td><?php echo $e['t03_um']; ?></td>
						<td><?php echo $e['t03_mta']; ?></td>
				<?php } $cont = 1; $c = $e['t03_id_equi'];?>

				<?php for($m = 1; $m <= $anios*12; $m++){ ?>
				<?php if($m == (($e['t03_anio']-1)*12 + $e['t03_mes'])){ ?>
					<td><?php echo $e['t03_mta'] ?></td>
				<?php }else{ ?>
					<td></td>
				<?php

}
        ?>
				<?php } ?>
				<td><?php echo $e['t03_mta']; ?></td>
					</tr>
	  <?php

}
?>


		<tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.3</td>
						<td>Gastos de Funcionamiento</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>

					</tr>

	  <?php

$equi = $objPOA->Inf_Financ_Lista_Par_CA($idProy, $idVersion);
$c = 0;
$cont = 1;
?>

	  <?php
$array_mp = array();
while ($e = mysqli_fetch_assoc($equi)) {
    ?>

		<?php

$array_mp[$e['descrip']]['cod'] = $e['cod_ext'];
    $array_mp[$e['descrip']]['um'] = $e['t03_um'];
    $array_mp[$e['descrip']]['total'] = $array_mp[$e['descrip']]['total'] + $e['meta'];
    $array_mp[$e['descrip']][($e['t03_anio'] - 1) * 12 + $e['t03_mes']] = $e['meta'];
    ?>

	  <?php

}

foreach ($array_mp as $index => $item) {
    ?>

	<tr style="background-color: #EEF8AD;" class="RowData">
						<td><?php echo "10.3.".$item['cod']; ?></td>
						<td><?php echo $index; ?></td>
						<td><?php echo $item['um']; ?> </td>
						<td><?php echo $item['total']; ?> </td>
	  <?php for($i=1;$i <= ($anios*12); $i++) { ?>
	  					<td><?php echo $item[$i]; ?> </td>
	  <?php } ?>
	   					<td><?php echo $item['total']; ?> </td>
					</tr>
	<?php

}
?>


		<tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.4</td>
						<td>Gastos Administrativos</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>

					</tr>

					<tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.6</td>
						<td>Imprevistos</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<?php  for ( $i=1; $i<=($anios*12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>

					</tr>

				</tbody>
				<tfoot>
					<tr>
						<td>&nbsp;</td>
						<td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>						
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						
						<td align="right">&nbsp;</td>
        <?php
        for ($x = 2; $x <= ($anios*12); $x ++) {
            echo $MesesBlank;
        }
        ?>
                    </tr>
                </tbody>
            </table>
        </div>
<?php if($objFunc->__QueryString()=="") { ?>
    </form>
</body>
</html>
<?php } ?>