<?php
/**
 * CticServices
 *
 * Gestiona el Reporte de Cronograma de Productos
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
    <title>Cronograma de Producto</title>
    <script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../css/reportes.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
        <div id="divBodyAjax" class="TableGrid"  style="width: 100%; text-align: left;">
        	<div style="width: 99%; display:block; margin: 0 auto;">
            <table cellpadding="0" cellspacing="1" style=" border:1px solid #525E94; display:block; width: 100%;">
                <tr>
                	<th style="width: 20%; text-align: left;">CODIGO DEL PROYECTO</th>
                	<td  style="width: 60%; text-align: left;"><?php echo($ML['t02_cod_proy']);?></td>
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
<!--                         <td rowspan="3" align="center" valign="middle">TOTAL</td> -->
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
                    <tr class="SubtitleTable">
                            <td width="49" colspan="4" align="center">ENTREGABLES</td>
                        <?php
                            $entregables = $objML->listarEntregablesReporte($idProy, $idVersion);
                            $i = 0;
                            while ($anios > $i) {
                                $i++;
                                $j = 0;
                                while(MESES > $j){
                                    $j++;
                        ?>
    					    <td width="27" align="center"><?php echo ((isset($entregables[$i][$j]))?'X':'');?></td>
                        <?php } } ?>
    				</tr>
                </thead>
				<tbody class="data">
                <?php
                    $objPOA = new BLPOA();

                    $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
                    while ($rowcomp = mysql_fetch_assoc($rsComp)) {

                        // -------------------------------------------------->
                        // DA 2.0 [11-12-2013 00:12]
                        // Componentes con descripcion en blanco no son tomados en cuenta:
                        if (empty($rowcomp['t08_comp_desc'])) {
                            continue;
                        }
                        // --------------------------------------------------<

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
						if(empty($rowInd['t09_ind'])) {
							continue;
						}
                ?>
                    <tr class="RowData">
						<td align="left" nowrap="nowrap"><span style="font-family: Tahoma;">I</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind']);?></td>
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
                        <!--  <td align="center" valign="middle" style="min-width: 40px;">0</td>-->
					</tr>
					<?php
                    $cRs = $objML->listarCaracteristicas($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act'], $rowInd['t09_cod_act_ind']);
                    while ($rowCar = mysql_fetch_assoc($cRs)) {
                ?>
                    <tr class="RowData">
						<td align="left" nowrap="nowrap"><span style="font-family: Tahoma;">C</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind'].'.'.$rowCar["t09_cod_act_ind_car"]);?></td>
						<td align="left" colspan="3"><?php echo($rowCar['t09_ind']);?></td>
						<?php
                            $i = 0;
                            while ($anios > $i) {
                                $i++;
                                $j = 0;

                                $progCaracteristica = $objML->listarProgramacionCaracteristicasReporte($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act'], $rowInd['t09_cod_act_ind'], $rowCar["t09_cod_act_ind_car"]);
                                while(MESES > $j){
                                    $j++;
                                ?>
            					    <td align="center" valign="middle"><?php echo ((isset($progCaracteristica[$i][$j]))?'X':'');?></td>
                            <?php } }?>
					</tr>
        <?php } //Fin Características ?>
        <?php } //Fin Indicadores ?>
        <?php } //Fin Productos ?>
        <?php } //Fin Componentes ?>
                </tbody>
            </table>
        </div>
<?php if($objFunc->__QueryString()=="") { ?>
    </form>
</body>
</html>
<?php } ?>