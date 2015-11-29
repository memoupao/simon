<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLFuentes.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");

$idProy = $objFunc->__Request('idProy');
$IdFte = $objFunc->__Request('idFte');
$idAnio = $objFunc->__Request('idAnio');
$idMes = $objFunc->__Request('idMes');

if ($idAnio == '' && $idMes == '') {
    $NumeroMes = $objFunc->__Request('NumMes');
    $idAnio = $objFunc->NumeroMesRev($NumeroMes, '1');
    $idMes = $objFunc->NumeroMesRev($NumeroMes, '2');
}

/*
 * echo("<pre>"); print_r(array($idProy, $IdFte, $idAnio, $idMes)); print_r($_REQUEST); exit();
 */

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$rowProy = $objProy->ProyectoSeleccionar($idProy, $ultima_vs);

$irsPeriodo = $objProy->PeriodosxAnio($idProy, $idAnio);
$arrPeriodo = NULL;
$cont = 1;
while ($r = mysqli_fetch_assoc($irsPeriodo)) {
    $arrPeriodo[$cont] = $r;
    $cont ++;
}
$irsPeriodo->free();

$objProy = NULL;

// $objRep = new BLReportes();
// $objPresup = new BLPresupuesto();
$objInformes = new BLInformes();
$rowInf = $objInformes->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title></title>
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
			<style>
.componente {
	background-color: #FFC;
	font-size: 10px;
	font-weight: bold;
}

.actividad {
	background-color: #FC9;
	font-size: 10px;
}

.subactividad {
	background-color: #CFF;
	font-size: 10px;
}

.rubro {
	background-color: #FFF;
	font-size: 10px;
}
</style>

			<table width="950" cellpadding="0" cellspacing="0"
				style="border: none;">
				<tr>
					<td width="1176" colspan="8" align="center" valign="middle"><span
						class="ClassField" style="text-transform: uppercase;">CONVENIO&nbsp; INSTITUCION (<?php echo($rowProy['t01_sig_inst']);?>)  -    FONDOEMPLEO</span></td>
				</tr>
				<tr>
					<td width="1176" colspan="8" rowspan="2" align="center"
						valign="middle"><span class="ClassField">&quot;<font
							style="text-transform: uppercase"><?php echo($rowProy['t02_nom_proy']);?></font>&quot;
					</span></td>
				</tr>
				<tr>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
					<td align="center" valign="middle"></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField" style="text-transform: uppercase;">PRESUPUESTO EJECUTADO ACUMULADO AL <?php echo($objFunc->FechaLarga($rowInf['t40_fch_pre']));?></span></td>
				</tr>
				<tr>
					<td colspan="8" align="center" valign="middle"><span
						class="ClassField">(Expresados en Moneda Nacional)</span></td>
				</tr>
			</table>
			<BR />


			<table border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr class="<?php echo($tipo);?>">
						<td colspan="23" align="left" valign="middle"><table width="100%"
								border="0" cellspacing="0" cellpadding="0" class="TableEditReg"
								style="border: none;">
								<tr>
									<td width="13%" height="29" align="center" nowrap="nowrap"><strong>Fuente
											Financiamiento</strong></td>
									<td width="87%">&nbsp; <select name="cboFteFinanc"
										id="cboFteFinanc" style="width: 320px;"
										onchange="ViewAnexoContrapartida();">
            <?php
            $objFte = new BLFuentes();
            $Rs = $objFte->ContactosListado($idProy);
            $objFunc->llenarCombo($Rs, "t01_id_inst", "t01_sig_inst", $IdFte);
            
            $rowinst = $objFte->ContactosSeleccionar($idProy, $IdFte);
            $institucion = $rowinst['t01_sig_inst'];
            $rowinst = NULL;
            $objFte = NULL;
            ?>
          </select></td>
								</tr>
							</table></td>
					</tr>

    <?php
    $sumaTotal = 0;
    $arrTotales = NULL;
    ?>
    <tr style="background: #E6E6E6;">
						<td colspan="2" rowspan="2" align="center" valign="middle">CUENTAS
							PRESUPUESTALES</td>
						<td width="53" rowspan="2" align="center" valign="middle">Unidad
							de Medida</td>
						<td width="37" rowspan="2" align="center" valign="middle">Meta
							Física</td>
						<td width="44" rowspan="2" align="center" valign="middle">Costo
							Parcial</td>
						<td width="44" rowspan="2" align="center" valign="middle">Costo
							Total</td>
						<td width="79" rowspan="2" align="center" valign="middle">Presupuesto
							Aprobado <br />
						<strong><?php echo(strtoupper($institucion));?></strong>
						</td>
						<td width="71" rowspan="2" align="center" valign="middle">Gasto
							Ejecutado Acumulado</td>
						<td colspan="12" align="center">Año de Ejecución</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
					</tr>
					<tr style="background: #E6E6E6;">
						<td width="21" align="center"><?php echo($arrPeriodo[1]['nom_abrev']."-".$arrPeriodo[1]['num_anio']);?> </td>
						<td width="21" align="center"><?php echo($arrPeriodo[2]['nom_abrev']."-".$arrPeriodo[2]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[3]['nom_abrev']."-".$arrPeriodo[3]['num_anio']);?></td>
						<?php /* ?><td width="21" align="center">Trim 1</td><?php */ ?>
						<td width="21" align="center"><?php echo($arrPeriodo[4]['nom_abrev']."-".$arrPeriodo[4]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[5]['nom_abrev']."-".$arrPeriodo[5]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[6]['nom_abrev']."-".$arrPeriodo[6]['num_anio']);?></td>
						<?php /* ?><td width="21" align="center">Trim 2</td><?php */ ?>
						<td width="21" align="center"><?php echo($arrPeriodo[7]['nom_abrev']."-".$arrPeriodo[7]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[8]['nom_abrev']."-".$arrPeriodo[8]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[9]['nom_abrev']."-".$arrPeriodo[9]['num_anio']);?></td>
						<?php /* ?><td width="21" align="center">Trim 3</td><?php */ ?>
						<td width="21" align="center"><?php echo($arrPeriodo[10]['nom_abrev']."-".$arrPeriodo[10]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[11]['nom_abrev']."-".$arrPeriodo[11]['num_anio']);?></td>
						<td width="21" align="center"><?php echo($arrPeriodo[12]['nom_abrev']."-".$arrPeriodo[12]['num_anio']);?></td>
						<?php /* ?><td width="21" align="center">Trim 4</td><?php */ ?>
						<td width="63" align="center">Total presup. Ejecutado</td>
						<td width="62" align="center">Porcent. De Ejecución</td>
						<td width="62" align="center">Presup. Por Ejecutar</td>
					</tr>
				</thead>
				<tbody class="data">
    <?php
    $Index = 1;
    $iRs = $objInformes->RepInforme_Anexo03($idProy, $idAnio, $idMes, $IdFte);
    
    /*
     * $getarrayfields = $objInformes->iGetArrayFields($iRs); foreach($getarrayfields as $r ) { echo("\$arrTotales['".$r."'] += \$row['".$r."']; <br>"); } exit();
     */
    
    $objInformes = NULL;
    while ($row = mysqli_fetch_assoc($iRs)) {
        $tipo = $row['tipo'];
        $total_fila = 0;
        
        if ($tipo == 'componente') {
            $sumaTotal += $row['total'];
            
            $arrTotales['total'] += $row['total'];
            $arrTotales['aporte_fe'] += $row['aporte_fe'];
            $arrTotales['gasto_acum'] += $row['gasto_acum'];
            $arrTotales['m1'] += $row['m1'];
            $arrTotales['m2'] += $row['m2'];
            $arrTotales['m3'] += $row['m3'];
            $arrTotales['t1'] += $row['t1'];
            $arrTotales['m4'] += $row['m4'];
            $arrTotales['m5'] += $row['m5'];
            $arrTotales['m6'] += $row['m6'];
            $arrTotales['t2'] += $row['t2'];
            $arrTotales['m7'] += $row['m7'];
            $arrTotales['m8'] += $row['m8'];
            $arrTotales['m9'] += $row['m9'];
            $arrTotales['t3'] += $row['t3'];
            $arrTotales['m10'] += $row['m10'];
            $arrTotales['m11'] += $row['m11'];
            $arrTotales['m12'] += $row['m12'];
            $arrTotales['t4'] += $row['t4'];
            $arrTotales['tot_pre_ejec'] += $row['tot_pre_ejec'];
            // $arrTotales['porc_ejec'] += $row['porc_ejec'];
            $arrTotales['pre_x_ejec'] += $row['pre_x_ejec'];
        }
        ?>
    <tr class="<?php echo($tipo);?>">
						<td width="26" align="left" valign="middle"><?php echo($row['codigo']);?></td>
						<td align="left" valign="middle"
							style="max-width: 300px; min-width: 200px; display: compact;"><?php echo($row['descripcion']);?></td>
						<td align="center" valign="middle"><?php echo($row['um']);?></td>
						<td align="center" valign="middle"><?php echo($row['meta']);?></td>
						<td align="center" valign="middle"><?php echo(number_format($row['parcial'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['aporte_fe'],2));?></td>
						<td align="right" valign="middle"
							style="background-color: #DCD1FA;"><?php echo( number_format($row['gasto_acum'],2));?></td>
						<td align="right"><?php echo( number_format($row['m1'],2));?></td>
						<td align="right"><?php echo( number_format($row['m2'],2));?></td>
						<td align="right"><?php echo( number_format($row['m3'],2));?></td>
						<?php /* ?><td align="right" style="color: #FFF; background-color: #336;"><?php echo( number_format($row['t1'],2));?></td><?php*/ ?>
						<td align="right"><?php echo( number_format($row['m4'],2));?></td>
						<td align="right"><?php echo( number_format($row['m5'],2));?></td>
						<td align="right"><?php echo( number_format($row['m6'],2));?></td>
						<?php /* ?><td align="right" style="color: #FFF; background-color: #336;"><?php echo( number_format($row['t2'],2));?></td><?php*/ ?>
						<td align="right"><?php echo( number_format($row['m7'],2));?></td>
						<td align="right"><?php echo( number_format($row['m8'],2));?></td>
						<td align="right"><?php echo( number_format($row['m9'],2));?></td>
						<?php /* ?><td align="right" style="color: #FFF; background-color: #336;"><?php echo( number_format($row['t3'],2));?></td><?php*/ ?>
						<td align="right"><?php echo( number_format($row['m10'],2));?></td>
						<td align="right"><?php echo( number_format($row['m11'],2));?></td>
						<td align="right"><?php echo( number_format($row['m12'],2));?></td>
						<?php /* ?><td align="right" style="color: #FFF; background-color: #336;"><?php echo( number_format($row['t4'],2));?></td><?php*/ ?>
						<td align="right"><span style="background-color: #DCD1FA;"><?php echo( number_format($row['tot_pre_ejec'],2));?></span></td>
						<td align="right"><span style="background-color: #DCD1FA;"><?php echo( number_format($row['porc_ejec'],2));?> %</span></td>
						<td align="right"><?php echo( number_format($row['pre_x_ejec'],2));?></td>
					</tr>
    <?php
        $Index ++;
    } // End While
    $iRs->free();
    
    $arrTotales['porc_ejec'] = (($arrTotales['tot_pre_ejec'] * 100) / $arrTotales['aporte_fe']);
    
    ?>
  </tbody>
				<tbody class="data">
					<tr style="font-size: 11px;" class="RowSelected";>
						<td colspan="5" align="center" valign="middle">TOTALES</td>
						<td align="right" valign="middle"><?php echo( number_format($arrTotales['total'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($arrTotales['aporte_fe'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($arrTotales['gasto_acum'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m1'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m2'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m3'],2));?></td>
						<?php /* ?><td align="right" style="background-color: #DCD1FA;"><?php echo( number_format($arrTotales['t1'],2));?></td><?php */ ?>
						<td align="right"><?php echo( number_format($arrTotales['m4'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m5'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m6'],2));?></td>
						<?php /* ?><td align="right" style="background-color: #DCD1FA;"><?php echo( number_format($arrTotales['t2'],2));?></td><?php */ ?>
						<td align="right"><?php echo( number_format($arrTotales['m7'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m8'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m9'],2));?></td>
						<?php /* ?><td align="right" style="background-color: #DCD1FA;"><?php echo( number_format($arrTotales['t3'],2));?></td><?php */ ?>
						<td align="right"><?php echo( number_format($arrTotales['m10'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m11'],2));?></td>
						<td align="right"><?php echo( number_format($arrTotales['m12'],2));?></td>
						<?php /* ?><td align="right" style="background-color: #DCD1FA;"><?php echo( number_format($arrTotales['t4'],2));?></td><?php */ ?>
						<td align="right"><span style="background-color: #DCD1FA;"><?php echo( number_format($arrTotales['tot_pre_ejec'],2));?></span></td>
						<td align="right"><span style="background-color: #DCD1FA;"><?php echo( number_format($arrTotales['porc_ejec'],2));?> %</span></td>
						<td align="right"><?php echo( number_format($arrTotales['pre_x_ejec'],2));?></td>
					</tr>
				</tbody>
			</table>

			<br />
			<script>
function ViewAnexoContrapartida()
{
	var arrayParams = new Array();
	arrayParams[0]  = "idProy=<?php echo($idProy); ?>" ; 
	arrayParams[1]  = "idAnio=<?php echo($idAnio); ?>" ;  
	arrayParams[2]  = "idMes=<?php echo($idMes); ?>" ;  
	arrayParams[3]  = "idFte=" + $("#cboFteFinanc").val() ;  
	var parameters  = arrayParams.join("&") ;
	$("#txtparams").val( parameters );
	RefreshReport();
	
}

</script>
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>