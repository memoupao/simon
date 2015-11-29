<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant('PATH_CLASS') . "BLBene.class.php");

require_once (constant("PATH_CLASS")."BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS")."BLPOA.class.php");


$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');
$idTrim = $objFunc->__Request('idTrim');
$idVs = $objFunc->__Request('idVersion');
$nroEntregable = ($objFunc->__Request('numEntre') ? $objFunc->__Request('numEntre') : 1);

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);

$objProy = new BLProyecto();
$idVersion = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVersion);

$objInf = new BLInformes();
$rowInf = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idTrim, $idVs);

$objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVs);
// $objRep = NULL ;


$periodoDel =  $objFunc->fechaEnLetras($row['t02_fch_ini'],3);
$fechaAl = $row['t02_fch_tam'];
if ($fechaAl == '00/00/0000') {
	$fechaAl = $row['t02_fch_tre'];
}
$periodoAl =  $objFunc->fechaEnLetras($fechaAl,3);

$fechaSupervicionDel = $objFunc->fechaEnLetras(date('d/m/Y'),1);
$fechaSupervicionAl = $objFunc->fechaEnLetras(date('d/m/Y'),1);


?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe Trimestral</title>
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

<style>
table.headTable{
	border:none !important;
}
table.headTable tr:hover {
    border: none !important;
    padding: inherit !important;
}


.TableGrid table tbody tr:hover{
	border: none !important;
	
}
</style>

<div id="divBodyAjax" class="TableGrid" style="font-size:10px; font-family:Arial; line-height: 1.5;">
			

			<table class="headTable"  width="650" cellpadding="0" cellspacing="0">
				<tr>
					<td><h3 style="padding:0px; margin:0px">1. INFORMACIÓN GENERAL.- (Sólo completar los cuadros)</h3></td>
				</tr>
				<tr>
					<td><h4 style="padding:0px; margin:0px">1.1. Datos del Proyecto:</h4></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		
			<table width="650" cellpadding="0" cellspacing="0">
				<thead>

				</thead>

				<tbody class="data" bgcolor="#FFFFFF">

					<tr>
						<td width="20%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#ccffcc" style="text-align: left;"><strong>Informe Correspondiente al entregable N°</strong></td>
						<td align="left" valign="middle"><?php echo($nroEntregable);?> &nbsp;</td>
						<td align="left" colspan="2" valign="middle" bgcolor="#ccffcc"><strong>Código del Proyecto</strong></td>
						<td width="24%" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#ccffcc"><strong>Período de referencia</strong></td>
						<td  align="left" valign="middle"><strong>Del</strong></td>
						<td align="left" valign="middle" width="24%"><strong><?php echo $periodoDel;?></strong></td>
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Al</strong></td>
						<td align="left" valign="middle"><strong><?php echo $periodoAl;?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><strong>Fecha de Supervisión:</strong></td>
						<td  align="left" valign="middle"><strong>Del</strong></td>
						<td align="left" valign="middle"><strong><?php echo $fechaSupervicionDel; ?></strong></td>
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Al</strong></td>
						<td align="left" valign="middle"><strong><?php echo $fechaSupervicionAl; ?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32"  colspan="2" align="left" valign="middle" bgcolor="#ccffcc"><strong>Nombre del jefe de proyecto:</strong></td>
						<td colspan="3" align="left" valign="middle"> ---- </td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#ccffcc"><strong>Nombre del encargado de la Supervisión:</strong></td>
						<td colspan="2" align="left" valign="middle">-----</td>
						<td align="left" valign="middle" bgcolor="#ccffcc">Cargo: </td>
						<td align="left" valign="middle">----- </td>
					</tr>
					
					
					<tr style="font-size: 11px;">
						<td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><strong>Título del proyecto:</strong></td>
						<td colspan="4" align="left" valign="middle"><?php echo($row['t02_nom_proy']);?></td>
					</tr>
					
					<tr style="font-size: 11px;">
						<td height="32" rowspan="2"  align="left" valign="middle" bgcolor="#ccffcc"><strong>Ubicación:</strong></td>
						<td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><strong>Departamento:</strong></td>						
						<td height="32"  align="left" valign="middle"><strong>-----</strong></td>
						<td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><strong>Provincia (s)</strong></td>
						<td height="32"  align="left" valign="middle"><strong>------</strong></td>						
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" colspan="1"  align="left" valign="middle" bgcolor="#ccffcc"><strong>Distrito(s):</strong></td>						
						<td height="32" colspan="3"  align="left" valign="middle"><strong>-----</strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Propósito (Objetivo Central)</strong></td>
						<td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t02_fin']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Institución Ejecutora:</strong></td>
						<td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t01_sig_inst'].chr(13).$row['t01_nom_inst']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Instituciones Asociadas:</strong></td>
						<td colspan="4" align="left" valign="middle">-------</td>
					</tr>
					
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Instituciones Colaboradoras:</strong></td>
						<td colspan="4" align="left" valign="middle">-------</td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><strong>Población Objetivo</strong></td>
						<td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
					</tr>
					
					<tr style="font-size: 11px;">
						<td colspan="3" align="left" valign="middle" bgcolor="#ccffcc"><strong>Fecha autorizada para el inicio del proyecto</strong></td>
						<td colspan="2" align="left" valign="middle"><?php echo $row['t02_fch_apro'];?> </td>
					</tr>
					
					<tr style="font-size: 11px;">
						<td colspan="3" align="left" valign="middle" bgcolor="#ccffcc"><strong>Fecha autorizada para el término del proyecto</strong></td>
						<td colspan="2" align="left" valign="middle"><?php echo $fechaAl;?> </td>
					</tr>

				</tbody>
				
			</table>
			

			
			<table class="headTable"  width="650" cellpadding="0" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h4 style="padding:0px; margin:0px">1.2. Resumen de Productos a entregar:</h4></td>
					
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			
			
			<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;" width="650">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="center" valign="middle" bgcolor="#ccffcc">&nbsp;</td>
						<td height="28" align="center" valign="middle" bgcolor="#ccffcc"><strong>Año</strong></td>
						<td rowspan="3" align="center" valign="middle" bgcolor="#ccffcc"><strong>Unidad de Medida</strong></td>
						<td width="41" rowspan="3" align="center" valign="middle" bgcolor="#ccffcc"><strong>Meta Física</strong></td>
                        <td colspan="12" align="center" valign="middle" bgcolor="ffffcc"><strong>Año <?php echo $idAnio;?></strong></td>    			     
					</tr>
				    <tr>
				        <td width="27" rowspan="2" align="center" valign="middle" bgcolor="#ccffcc">&nbsp;</td>
						<td rowspan="2" align="center" valign="middle" bgcolor="#ccffcc"><strong>MES</strong></td>
					<?php
						$j = 0;
                        while(MESES > $j) {
                            $j++;
                        ?>
						<td width="10" height="28" align="center" valign="middle" bgcolor="#ffffcc"><strong><?php echo($j);?></strong></td>
                    <?php
                        }
                        //for ($x = 2; $x <= $anios; $x ++) {
                        //    echo ($MergeMeses);
                        //}
                    ?>
                    </tr>
					<tr style="font-size: 10px">
                    <?php
                    $arrPProy = NULL;

                    //for ($x = 1; $x <= $anios; $x ++) {
                        $irsPeriodo = $objProy->PeriodosxAnio($idProy, $idAnio);
                        $arrPeriodo = NULL;
                        $cont = 1;
                        while ($r = mysqli_fetch_assoc($irsPeriodo)) {
                            $arrPeriodo[$cont] = $r;
                            $arrPProy[((12 * 1) - 12) + $cont] = $r;
                            $cont ++;
                        }
                        $irsPeriodo->free();

                        $j = 0;
                        while(MESES > $j){
                            $j++;
                        ?>
                        <td align="center" valign="middle" style="min-width: 10px;" bgcolor="#ffffcc"><strong><?php echo($arrPeriodo[$j]['nom_abrev']." ".$arrPeriodo[$j]['num_anio']);?></strong></td>
                    <?php } 
					//} ?>
                    </tr>
                    <tr>
                            <td  colspan="4" align="center" bgcolor="#ccffcc"><strong>ENTREGABLES</strong></td>
                        <?php
                            $entregables = $objML->listarEntregablesReporte($idProy, $idVersion);
                            //$i = 0;
                            //while ($anios > $i) {
                                //$i++;
                                $j = 0;
                                while(MESES > $j){
                                    $j++;
                        ?>
    					    <td width="10" bgcolor="#ccffcc" align="center"><?php echo ((isset($entregables[$idAnio][$j]))?'X':'');?></td>
                        <?php } 
							//} ?>
    				</tr>
                
				
                <?php
                    $objPOA = new BLPOA();

                    $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
                    while ($rowcomp = mysql_fetch_assoc($rsComp)) {

                        if (empty($rowcomp['t08_comp_desc'])) {
                            continue;
                        }
                        
                ?>
                    <tr bgcolor="#D7DC78">
                        <td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp']);?></td>
                        <td align="left" colspan="<?php echo(MESES  + 4);?>"><?php echo($rowcomp['t08_comp_desc']);?></td>
                    </tr>
                <?php
                    $rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
                    while ($rowact = mysql_fetch_assoc($rsAct)) {
                ?>
                    <tr  bgcolor="#EEF8AD">
                        <td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act']);?></td>
                        <td align="left" colspan="<?php echo(MESES + 4);?>"><?php echo($rowact['t09_act']);?></td>
                    </tr>
                <?php
                    $iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
                    while ($rowInd = mysql_fetch_assoc($iRs)) {
						if(empty($rowInd['t09_ind'])) {
							continue;
						}
                ?>
                    <tr bgcolor="#ffffff">
						<td align="left" nowrap="nowrap"><span style="font-family: Tahoma;">I</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind']);?></td>
						<td align="left"><?php echo($rowInd['t09_ind']);?></td>
						<td align="center"><?php echo($rowInd['t09_um']);?></td>
						<td align="center" valign="middle" style="background-color: #eeeeee;"><?php echo(number_format($rowInd['t09_mta'],0));?></td>

						<?php
                            //$i = 0;
                            //while ($anios > $i) {
                                //$i++;
                                $j = 0;

                                $lista = $objML->getProgramaIndicador($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act'], $rowInd['t09_cod_act_ind'], $idAnio);

                                while(MESES > $j){
                                    $j++;
                                    echo '<td align="center" valign="middle">'.(array_key_exists($j, $lista)?$lista[$j]:'').'</td>';
                                }
                            //} ?>                        
					</tr>
					
        <?php } //Fin Indicadores ?>
        <?php } //Fin Productos ?>
        <?php } //Fin Componentes ?>
        
        			
        			
        			
        			
        			<tr>
        				<td colspan="16">&nbsp;</td>
        			</tr>
        			
        			<tr>
						
						<td colspan="4" rowspan="2" height="28" align="center" valign="middle" bgcolor="#ccffcc"><strong>CONCEPTO</strong></td>						
                        <td colspan="12" align="center" valign="middle" bgcolor="ffffcc"><strong>Año <?php echo $idAnio;?></strong></td>    			     
					</tr>
				    
					<tr style="font-size: 10px">
                    <?php
                    $arrPProy = NULL;

                    //for ($x = 1; $x <= $anios; $x ++) {
                        $irsPeriodo = $objProy->PeriodosxAnio($idProy, $idAnio);
                        $arrPeriodo = NULL;
                        $cont = 1;
                        while ($r = mysqli_fetch_assoc($irsPeriodo)) {
                            $arrPeriodo[$cont] = $r;
                            $arrPProy[((12 * 1) - 12) + $cont] = $r;
                            $cont ++;
                        }
                        $irsPeriodo->free();

                        $j = 0;
                        while(MESES > $j){
                            $j++;
                        ?>
                        <td align="center" valign="middle" style="min-width: 10px;" bgcolor="#ffffcc"><strong><?php echo($arrPeriodo[$j]['nom_abrev']." ".$arrPeriodo[$j]['num_anio']);?></strong></td>
                    <?php } 
					//} ?>
                    </tr>
                    
                    <?php for ($i=0; $i<4; $i++) { ?>
                    <tr>
                    	<td colspan="4">Productos a supervisar</td>
                    	
                    	<td>0</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    	<td>1</td>
                    </tr>
                    
                    <?php } ?>
                    
        
        
                </tbody>
            </table>
            
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<table class="headTable"  width="650" cellpadding="0" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h4 style="padding:0px; margin:0px">1.3. Avance en Relación a los Productos.</h4></td>					
				</tr>
				<tr>					
					<td><h4 style="padding:0px; margin:0px; padding-left:20px;">1.3.1. Productos comprometidos al entregable.</h4></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			
			
			
			
			<?php

			$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
			$nom_comp = '';
			while ($rowcomp = mysql_fetch_assoc($rsComp)) {
			
				if (empty($rowcomp['t08_comp_desc'])) {
					continue;
				}
				
				$componente = $rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'];
				
				
				$rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
				while ($rowact = mysql_fetch_assoc($rsAct)) {
				

			?>
			<table cellspacing="0" cellpadding="0" width="650" style="border:none;">
					<?php if ($nom_comp!=$componente) { ?>
					<tr>
						<td colspan="2"  style="text-align: left; font-weight:bold; padding-left:20px;">&nbsp;</td>
					</tr>			
					<tr>
						<td colspan="2"  style="text-align: left; font-weight:bold; padding-left:20px;"><strong>COMPONENTE: <?php echo $componente;?></strong></td>
					</tr>
					<?php } else { ?>
					<tr>
						<td colspan="2"  style="text-align: left; font-weight:bold; padding-left:20px;">&nbsp;</td>
					</tr>
					<?php } ?>
					<tr>
						<td width="115"></td>
						<td style="text-align: left; font-weight:bold; padding-left:20px;"><strong>PRODUCTO: <?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].' '.$rowact['t09_act']);?></strong></td>
					</tr>
			</table>
			
			<table cellspacing="0" cellpadding="0" width="650">
				
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td bgcolor="#ccffcc" valign="middle" align="center" ><strong>N°</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"><strong>Indicador 1 del  producto 1</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"><strong>Unidad de <br/>Medida</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"><strong>Meta <br/>Total</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"><strong>Meta al <br/>Entregable</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"><strong>Reportado <br/>por la IE</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"><strong>Avance <br/>verificado  <br/>Supervisor</strong></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="6">Indicador del producto</td>
					</tr>
					<?php
					$iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
					$nomIndicador = '';
					while ($rowInd = mysql_fetch_assoc($iRs)) {
						if(empty($rowInd['t09_ind'])) {
							continue;
						}
					?>
					
					
					
					
					<tr>
						<td><span style="font-family: Tahoma;">I</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind']);?></td>
						<td><?php echo($rowInd['t09_ind']);?></td>
						<td><?php echo($rowInd['t09_um']);?></td>
						<td><?php echo(number_format($rowInd['t09_mta'],0));?></td>
						<td>0000</td>
						<td>0000</td>
						<td>0000</td>
					</tr>
					
					
					<?php if ($nomIndicador != $rowInd['t09_ind']) {?>										
					<tr>						
						<td colspan="2" bgcolor="#ccffcc" valign="middle" align="center" ><strong>Características indispensables del Producto o criterios de aceptación</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center" ><strong>Unidad de <br/>Medida</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center" ><strong>Meta al <br/>Entregable</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center" ><strong>Logro <br/>Supervisado</strong></td>
						<td colspan="2" bgcolor="#ccffcc" valign="middle" align="center" ><strong>Observaciones o  <br/>Comentarios</strong></td>
						
					</tr>
					<?php } ?>
					
					
					<?php
					
					$cRs = $objML->listarCaracteristicas($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act'], $rowInd['t09_cod_act_ind']);
					while ($rowCar = mysql_fetch_assoc($cRs)) {						
					?>
					<tr>
						<td colspan="2">
							<span style="font-family: Tahoma;">C</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind'].'.'.$rowCar["t09_cod_act_ind_car"]);?> 
							<?php echo($rowCar['t09_ind']);?>
						</td>						
						<td>1111</td>
						<td>1111</td>
						<td>1111</td>
						<td colspan="2">1111</td>						
					</tr>
					<?php } ?>
					
					
					
					<tr>						
						<td colspan="2" bgcolor="#ccffcc" valign="middle" align="center" ><strong>Beneficiarios o Unidades evaluadas</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"  ><strong>Unidad de <br/>Medida</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center"  ><strong>Meta al <br/>Entregable</strong></td>
						<td bgcolor="#ccffcc" valign="middle" align="center" ><strong>Logro <br/>Supervisado</strong></td>
						<td colspan="2" bgcolor="#ccffcc" valign="middle" align="center" ><strong>Observaciones</strong></td>
						
					</tr>
					<tr>
						<td colspan="2"></td>						
						<td></td>
						<td></td>
						<td></td>
						<td colspan="2"></td>						
					</tr>
					<tr>
						<td colspan="2"></td>						
						<td></td>
						<td></td>
						<td></td>
						<td colspan="2"></td>						
					</tr>
					<tr>
						<td colspan="2"></td>						
						<td></td>
						<td></td>
						<td></td>
						<td colspan="2"></td>						
					</tr>
					
					
					
					<tr>
						<td bgcolor="#cccccc" valign="middle" align="center"  colspan="7">
							<strong>Participación de beneficiarios en actividades de desarrollo de capacidades referidas al producto</strong>
						</td>
					</tr>
					<tr>
						<td colspan="7">
							
						</td>
					</tr>
					
					
					<tr>
						<td bgcolor="#cccccc" valign="middle" align="center"  colspan="7">
							<strong>Análisis del avance físico y presupuestal de las actividades vinculadas al producto</strong>
						</td>
					</tr>
					<tr>
						<td colspan="7">
							
						</td>
					</tr>
					
					
					
					
					<?php
						$nomIndicador = $rowInd['t09_ind']; 
					} ?> 
					
					
					
					
					
					
					
					
				</tbody>
			</table>
			<?php
					$nom_comp = $componente;
				}
				
			} ?>
			
			
			
			
			
			
			
			
			
			<table class="headTable"  width="650" cellpadding="0" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h3 style="padding:0px; margin:0px">2. ANÁLISIS DEL AVANCE</h3></td>					
				</tr>
				<tr>					
					<td><h4 style="padding:0px; margin:0px; padding-left:20px;">2.1.   Resumen del avance técnico de productos. </h4></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			
			
			<table cellspacing="0" cellpadding="0" align="center" width="650">
				<tbody bgcolor="#FFFFFF" class="data">
					<tr>
						<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Nro.</strong></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Componente /<br/> Producto / Indicador</strong></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Unidad<br/> de<br/> Medida</strong></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Meta <br/>Total<br/> del proyecto</strong></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Meta en el<br/> entregable</strong></td>
						<td colspan="2" bgcolor="#ccffcc" align="center" valign="middle"><strong>Avance Técnico<br/> al entregable <br/>según IE</strong></td>
						<td colspan="2" bgcolor="#ccffcc" align="center" valign="middle"><strong>Avance Técnico<br/> Supervisado<br/>Al Entregable</strong></td>
						<td colspan="2" bgcolor="#ccffcc" align="center" valign="middle"><strong>% Avance <br/>Técnico <br/>Supervisado <br/>en relación a <br/>la meta total <br/>del Proyecto</strong></td>
					</tr>
					<?php
					$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
					$nom_comp = '';
					while ($rowcomp = mysql_fetch_assoc($rsComp)) {
							
						if (empty($rowcomp['t08_comp_desc'])) {
							continue;
						}
					
					?>
					<tr>
						<td bgcolor="#ffffff" align="center" valign="middle"><strong><?php echo $rowcomp['t08_cod_comp'];?></strong></td>
						<td bgcolor="#ffffff" align="left" valign="middle"><strong><?php echo $rowcomp['t08_comp_desc'];?></strong></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"></td>
						<td bgcolor="#ccffcc" align="center" valign="middle"></td>
						<td colspan="2" bgcolor="#ccffcc" align="center" valign="middle"></td>
						<td colspan="2" bgcolor="#ccffcc" align="center" valign="middle"></td>
						<td colspan="2" bgcolor="#ccffcc" align="center" valign="middle"></td>
					</tr>
					<?php 
						$rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
						while ($rowact = mysql_fetch_assoc($rsAct)) { 
					?>
					<tr>
						<td bgcolor="#ffffff" align="center" valign="middle"><?php echo $rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'];?></td>
						<td bgcolor="#ffffff" align="left" valign="middle"><?php echo $rowact['t09_act'];?></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
					</tr>

					<?php
								$iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
								$nomIndicador = '';
								while ($rowInd = mysql_fetch_assoc($iRs)) {
									if(empty($rowInd['t09_ind'])) {
										continue;
									}
					?>
					<tr>
						<td bgcolor="#ffffff" align="center" valign="middle"><span style="font-family: Tahoma;">I</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind']);?></td>
						<td bgcolor="#ffffff" align="left" valign="middle"><?php echo($rowInd['t09_ind']);?></td>
						<td bgcolor="#ffffff" align="center" valign="middle"><?php echo($rowInd['t09_um']);?></td>
						<td bgcolor="#ffffff" align="center" valign="middle"><?php echo(number_format($rowInd['t09_mta'],0));?></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
						<td bgcolor="#ffffff" align="center" valign="middle"></td>
					</tr>
					
					<?php		
								} 
							} 						
					} ?>
				</tbody>
			</table>
			
		


			
			

			
			


 
	
	
		
			
		<table class="headTable"  width="650" cellpadding="0" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<h4 style="padding:0px; margin:0px">2.2. Resumen de la ejecución presupuestal.</h4>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
		</table>
		
		
		
		<table width="650" align="center" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
				<tr>
					<td  rowspan="2" align="center" valign="middle" bgcolor="#ccffcc"><strong>Resumir datos de<br/> programación <br/>presupuestal</strong></td>
					<td  rowspan="2" align="center" valign="middle" bgcolor="#ccffcc"><strong>Total <br/>según<br/> convenio<br/> S/.</strong></td>
					<td  colspan="2" align="center" valign="middle" bgcolor="#ccffcc"><strong>Acumulado al entregable</strong></td>
					<td  colspan="2" align="center" valign="middle" bgcolor="#ccffcc"><strong>% de Avance Acumulado</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Observaciones encontradas</strong></td>
				</tr>
				<tr>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Programado S/.</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Ejecutado S/.</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>En relación <br/>a lo programado <br/>al Entregable <br/>% </strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>En relación <br/>al total <br/>según <br/>convenio <br/>%</strong></td>
					<td  align="center" valign="middle" bgcolor="#cccccc"></td>
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#ccffcc">Fondoempleo</td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>					
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#ccffcc">Institución Ejecutora</td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>	
					<td align="left" valign="middle"></td>				
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#ccffcc">Instituciones Asociadas</td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>	
					<td align="left" valign="middle"></td>				
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#ccffcc">Instituciones Colaboradoras</td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>	
					<td align="left" valign="middle"></td>				
				</tr>
				
				<tr>
					<td align="left" valign="middle" bgcolor="#ccffcc">Beneficiarios</td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>	
					<td align="left" valign="middle"></td>				
				</tr>
				
				<tr>
					<td align="left" valign="middle" bgcolor="#ccffcc"><strong>TOTAL</strong></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>
					<td align="left" valign="middle"></td>					
				</tr>
				
				</tbody>
		</table>
		
		
		
		
		
		<table class="headTable"  width="650" cellpadding="0" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<h4 style="padding:0px; margin:0px">2.3. Avance en Relación a los indicadores de los componentes.</h4>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
		</table>
		
		<table width="650" align="center" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
				<tr>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Componente</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Indicadores <br/>de Componente</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Unidad de <br/>Medida</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Total <br/>del<br/> proyecto</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Planificado <br/>al<br/>  Entregable</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Informado <br/>al Entregable  <br/>por la IE</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Supervisado Al Entregable</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Avance <br/>Absoluto <br/>Acumulado <br/>(en relación al total )</strong></td>
					<td  align="center" valign="middle" bgcolor="#ccffcc"><strong>Avance <br/>Absoluto <br/>Supervisado <br/>(en relación al total )</strong></td>
					
				</tr>
				<?php
					$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
					$nom_comp = '';
					
					while ($rowcomp = mysql_fetch_assoc($rsComp)) {
							
						if (empty($rowcomp['t08_comp_desc'])) {
							continue;
						}
						
						if ($nom_comp != $rowcomp['t08_comp_desc']) { 
							echo '</tr>';
						} 
						
						$iRs = $objML->ListadoIndicadoresComponentes($idProy, $idVersion, $rowcomp['t08_cod_comp']);
						 
						$totalIndicadores = mysql_num_rows($iRs);
						while ($rowInd = mysql_fetch_assoc($iRs)) {
							if(empty($rowInd['t09_ind'])) {
								continue;
							}
							?>
					<tr>						
					<?php if ($nom_comp != $rowcomp['t08_comp_desc']) { ?>				
						<td  rowspan="<?php echo $totalIndicadores;?>" align="center" valign="middle" bgcolor="#ffffff"><strong><?php echo $rowcomp['t08_cod_comp'];?></strong></td>
					<?php }   ?>
				
						<td  align="left" valign="middle" bgcolor="#ffffff">
							<span style="font-family: Tahoma;">I</span>.<?php echo($rowcomp['t08_cod_comp'].'.'.$rowInd['t09_cod_act'].'.'.$rowInd['t09_cod_act_ind']);?> 
							 <?php echo($rowInd['t09_ind']);?>
						</td>
						<td  align="center" valign="middle" bgcolor="#ffffff"><?php echo($rowInd['t09_um']);?></td>
						<td  align="center" valign="middle" bgcolor="#ffffff"><?php echo(number_format($rowInd['t09_mta'],0));?></td>
						<td  align="center" valign="middle" bgcolor="#ffffff">aaaa</td>
						<td  align="center" valign="middle" bgcolor="#ffffff">aaaaaa</td>
						<td  align="center" valign="middle" bgcolor="#ffffff">aaaaaa</td>
						<td  align="center" valign="middle" bgcolor="#ffffff">aaaa</td>
						<td  align="center" valign="middle" bgcolor="#ffffff">aaaaaa</td>
					</tr>
				<?php 
							
							
							$nom_comp = $rowcomp['t08_comp_desc'];
						}
				 	
						
				} ?>
				
				</tbody>
		</table>
		
		
		
		
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h3 style="padding:0px; margin:0px">3. SÍNTESIS AVANCE TÉCNICO Y PRESUPUESTAL</h3></td>					
				</tr>
				<tr>					
					<td><h4 style="padding:0px; margin:0px; padding-left:20px;">3.1. CUADRO DE AVANCE TÉCNICO PORCENTUAL DEL PROYECTO </h4></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		
		
		<table cellspacing="0" cellpadding="0" align="center" width="650">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>
					<td bgcolor="#ccffcc" align="center" valign="middle" rowspan="2"><strong>CATEGORÍAS</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle" colspan="2"><strong>% Avance en relación <br/>a la meta del <br/>Entregable</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle" colspan="2"><strong>% Avance acumulado <br/>en relación a la meta <br/>total del Proyecto</strong></td>					
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Actividades %</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Productos % </strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Actividades %</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Productos % </strong></td>					
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">AVANCE TÉCNICO</td>
					<td bgcolor="#ffffff" align="center" valign="middle">1111111</td>
					<td bgcolor="#ffffff" align="center" valign="middle">111111</td>
					<td bgcolor="#ffffff" align="center" valign="middle">11111</td>					
					<td bgcolor="#ffffff" align="center" valign="middle">11111</td>
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>TOTAL</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle">1111111</td>
					<td bgcolor="#ccffcc" align="center" valign="middle">111111</td>
					<td bgcolor="#ccffcc" align="center" valign="middle">11111</td>					
					<td bgcolor="#ccffcc" align="center" valign="middle">11111</td>
				</tr>
				</tbody>
		</table>
		
		
		
		
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>					
					<td><h4 style="padding:0px; margin:0px; padding-left:20px;">3.2. CUADRO DE AVANCE PRESUPUESTAL PORCENTUAL DEL PROYECTO</h4></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		
		
		<table cellspacing="0" cellpadding="0" align="center" width="650">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Categorías</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>% Avance en relación <br/>a la meta del <br/>Entregable</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>% Avance acumulado <br/>en relación a la meta <br/>total del Proyecto</strong></td>					
				</tr>				
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">FONDOEMPLEO</td>
					<td bgcolor="#ffffff" align="center" valign="middle">1111111</td>
					<td bgcolor="#ffffff" align="center" valign="middle">111111</td>					
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">Institución Ejecutora</td>
					<td bgcolor="#ffffff" align="center" valign="middle">1111111</td>
					<td bgcolor="#ffffff" align="center" valign="middle">111111</td>					
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">Instituciones Asociadas</td>
					<td bgcolor="#ffffff" align="center" valign="middle">1111111</td>
					<td bgcolor="#ffffff" align="center" valign="middle">111111</td>					
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>TOTAL</strong></td>
					<td bgcolor="#ffffff" align="center" valign="middle">1111111</td>
					<td bgcolor="#ffffff" align="center" valign="middle">111111</td>					
				</tr>
				</tbody>
		</table>
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>					
					<td><h4 style="padding:0px; margin:0px; padding-left:20px;">3.3. Aspectos cualitativos</h4></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		
		
		<table cellspacing="0" cellpadding="0" align="center" width="650">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Características del trabajo del equipo técnico</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Mala</strong></td>
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Regular</strong></td>					
					<td bgcolor="#ccffcc" align="center" valign="middle"><strong>Buena</strong></td>
				</tr>				
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">Registro adecuado de la participación de los beneficiarios en las actividades del proyecto.</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>					
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">Capacidad de atender a los beneficiarios en el ámbito de ejecución de parte del equipo técnico.</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>					
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">Opinión de los beneficiarios respecto a los servicios prestados por el personal del proyecto.</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>					
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
				</tr>
				<tr>
					<td bgcolor="#ccffcc" align="left" valign="middle">Disponibilidad de los medios de verificación de los indicadores del proyecto.</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>					
					<td bgcolor="#ffffff" align="center" valign="middle">Ingreso</td>
				</tr>
				
				</tbody>
		</table>
		
		
		
		
		
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h3 style="padding:0px; margin:0px">4. CONCLUSIONES</h3></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" align="center" width="650">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>
					<td bgcolor="#ffffff" align="left" valign="middle">
						
					</td>					
				</tr>				
				
				</tbody>
		</table>
		
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h3 style="padding:0px; margin:0px">5. RECOMENDACIONES</h3></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" align="center" width="650" style="border:none;">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>
					<td bgcolor="#ffffff" align="left" valign="middle" style="border: none;">
						Principales recomendaciones efectuadas de visitas anteriores y la verificación de su cumplimiento (a partir de la segunda visita).<br/>
						Indicar las principales acciones propuestas para mejorar la ejecución del proyecto, tanto en los aspectos técnicos como administrativos en la presente supervisión.
					</td>					
				</tr>				
				<tr>					
					<td bgcolor="#ffffff" align="left" valign="middle" style="border: none;">
						Ingreso
					</td>
				</tr>
				
				</tbody>
		</table>
		
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h3 style="padding:0px; margin:0px">6. CALIFICACIÓN</h3></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" align="center" width="650" style="border:none;">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>
					</td>
					<td bgcolor="#ffffff" align="justify" valign="middle" style="border: none;">
						Se debe justificar la calificación del proyecto en base a lo encontrado 
						durante la supervisión, el avance de actividades se trabaja en el cuadro 
						de avance de actividades supervisado, el avance de productos se determina 
						en el cuadro de avance de productos supervisado, el avance de resultados 
						se trabaja en el cuadro de avance de resultados supervisados y el avance 
						presupuestal en el anexo 3 supervisado.
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff" align="left" valign="middle" style="border: none;">
						Ingreso
					</td>					
				</tr>
				
				<tr>					
					<td bgcolor="#ffffff" align="justify" valign="middle" style="border: none;">
					<p>
						<strong>APROBADO</strong>, Cuando el avance del proyecto es satisfactorio, es decir ha cumplido con las actividades y productos programados al Entregable. En relación a la gestión administrativa esta calificación asume que se ha presentado la información solicitada dentro de los plazos previstos y se ha cumplido con los requerimientos técnicos,  los parámetros solicitados en los procesos establecidos para las compras, autorizaciones de compra, contratación de personal u otro señalado en los procesos administrativos del manual de gestión de proyectos.
 					</p>
 					<p>
						<strong>APROBADO CON RESERVA</strong>, cuando el desarrollo de las actividades o los productos, ha tenido retrasos significativos pero que pueden corregirse en base a una reprogramación en el plazo de ejecución previsto.  Para ello se debe realizar una reprogramación del POA. El Aprobado con Reserva se otorga también si el avance presupuestal no se desarrolla de acuerdo a lo programado o se ha incumplido con algún aspecto de la gestión administrativa. 
					</p>
					<p>	
						<strong>DESAPROBADO</strong>, la calificación DESAPROBADO se otorga cuando el desarrollo de las actividades y productos  tiene un retraso significativo en relación a lo programado, que evidencia la imposibilidad de alcanzar las metas propuestas por el proyecto.  En este caso el Informe de Supervisión incluirá las observaciones necesarias y las recomendaciones a la institución de cómo superar el desfase. En esas circunstancias, se suspende el desembolso hasta que levante las observaciones.
					</p>
					</td>
				</tr>
								
				
				</tbody>
		</table>
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>				
					<td  align="center" width="50">&nbsp;</td>					
					<td  align="center" width="100">____________________________________________</td>
					<td  align="center" width="740">&nbsp;</td>
				</tr>
				<tr>									
					<td  align="center" width="50">&nbsp;</td>
					<td align="center" width="100">					
					FIRMA DEL ENCARGADO DE LA SUPERVISIÓN
					</td>
					<td  align="center" width="740">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				
			</tbody>
		</table>
		
		
		
		
		
		
		
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><h3 style="padding:0px; margin:0px">7. ANEXOS</h3></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="0" align="center" width="650" style="border:none;">
				<tbody bgcolor="#FFFFFF" class="data">
				<tr>					
					<td bgcolor="#ffffff" align="justify" valign="middle" style="border: none;">
						<ul>
							<?php
						    
						    $iRs = $objInf->listarAnexosInfSE($idProy, $idAnio, $idTrim);						    
						    if ($iRs->num_rows > 0) {
						        while ($row = mysqli_fetch_assoc($iRs)) {

									$urlFile = $row['t30_url_file'];
									$filename = $row['t30_nom_file'];
									$path = constant('APP_PATH') . "sme/proyectos/informes/anx_me";
									$href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
						    ?>
							<li style="list-style:upper-alpha;"><?php echo( $row['t30_desc_file']);?>  
								(<a href="<?php echo($href);?>" title="Descargar Archivo" target="_blank">
									<?php echo($row['t30_nom_file']);?>
								</a>)
							</li>
							<?php }
							} ?>
							<li style="list-style:upper-alpha;"><a href="<?php echo constant('PATH_RPT');?>reportviewer.php?ReportID=3&idProy=<?php echo $idProy;?>&idVersion=<?php echo $idVs;?>" title="Marco lógico del proyecto">Marco lógico del proyecto.</a></li>							
						</ul>
					</td>					
				</tr>
				<tr>
					<td bgcolor="#ffffff" align="justify" valign="middle" style="border: none;">
						El informe con todos los anexos debe entregarse firmado en versión digital.
					</td>
				</tr>
				</tbody>
		</table>
		
		
		
		
		
			
			
		<table cellspacing="0" cellpadding="0" width="650" class="headTable">
				<tbody>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</tbody>
		</table>

		
<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>