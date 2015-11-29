<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$objProy = new BLProyecto();
$rowProy = $objProy->ProyectoSeleccionar($idProy, $idVersion);

$objMP = new BLManejoProy();
$rsPresup = $objMP->Rpt_PresupuestoAnalitico($idProy, $idVersion);

$objPres = new BLPresupuesto();

if ($idVersion > 1) {
    $msgTitle = "POA Año " . ($idVersion - 1);
}
?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Presupuesto Analitico del A&nacute;o <?php echo $msgTitle; ?></title>
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
}

.actividad {
	background-color: #FC9;
}

.subactividad {
	background-color: #CFF;
}

.rubro {
	background-color: #FFF;
}
</style>

			<div style="color: #003366; font-size: 16px; font-weight: bold;">
  <?php
if ($idVersion > 1) {
    echo ("A&nacute;o " . ($idVersion - 1));
}
?>
  </div>
			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="0" class="TableGrid">
				<tr>
					<th width="13%" height="23" align="left">CODIGO DEL PROYECTO</th>
					<td width="2%" align="left">&nbsp;</td>
					<td width="54%" align="left"><?php echo($rowProy['t02_cod_proy']);?> - <a
						href="<?php if($rowProy['t01_web_inst']==''){echo('#');}else{echo($objFunc->verifyURL($rowProy['t01_web_inst']));} ?>"
						target="_blank" title="Ir a Pagina web del Ejecutor"><?php echo($rowProy['t01_sig_inst']);?></a></td>
					<th width="4%" align="left" nowrap="nowrap">INICIO</th>
					<td width="27%" align="left"><?php echo($rowProy['ini']);?></td>
				</tr>
				<tr>
					<th colspan="2" align="left">DESCRIPCION DEL PROYECTO</th>
					<td align="left"><?php echo($rowProy['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO.</th>
					<td align="left"><?php echo($rowProy['fin']);?></td>
				</tr>
				<tr>
					<th height="18" align="left">&nbsp;</th>
					<td colspan="2">&nbsp;</td>
					<td colspan="2"><strong><?php echo($msgTitle);?></strong></td>
				</tr>
			</table>
			<table width="99%" border="0" cellpadding="0" cellspacing="0">
				<thead>
  <?php
$campos = $objMP->iGetArrayFields($rsPresup);
$arrfuentes = NULL;

for ($x = 9; $x < count($campos); $x ++) {
    $arrfuentes[$x - 9][0] = $campos[$x]; // --> Para los Nombres de Fuentes
    $arrfuentes[$x - 9][1] = 0; // --> Para los Totales
}

/*
 * echo("<pre>"); print_r($campos); print_r($arrfuentes); echo("</pre>") ;
 */
$sumaTotal = 0;
$colspan = count($arrfuentes) + 1; // 1=columna de Total
?>
  		<tr style="background: #CFC;">
						<td colspan="2" rowspan="2" align="center" valign="middle">COMPONENTE / PRODUCTO / ACTIVIDAD</td>
						<td width="196" rowspan="2" align="center" valign="middle">Unidad
							de Medida</td>
						<td width="100" rowspan="2" align="center" valign="middle">Costo
							Parcial</td>
						<td width="87" rowspan="2" align="center" valign="middle">Meta
							Física</td>
						<td width="147" rowspan="2" align="center" valign="middle">Costo
							Total</td>
						<td colspan="<?php echo($colspan);?>" align="center">Fuentes de
							finaciamiento</td>
		</tr>
		<tr>
    <?php
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo ('<td width="103" align="center">' . $arrfuentes[$x][0] . '</td>');
    }
    ?>
    <td width="110" align="center">TOTAL</td>
					</tr>
				</thead>

				<tbody class="data">
  <?php
$Index = 1;

while ($row = mysqli_fetch_assoc($rsPresup)) {
    $tipo = $row['tipo'];
    $total_fila = 0;

    if(empty($row['descripcion'])) continue;

    if ($tipo == 'componente') {
        $sumaTotal += $row['total'];
        for ($x = 0; $x < count($arrfuentes); $x ++) {
            $arrfuentes[$x][1] += $row[$arrfuentes[$x][0]];
        }


    }
    ?>
   <tr class="<?php echo($tipo);?>">
						<td width="48" align="left" valign="middle"><?php echo($row['codigo']);?></td>
						<td width="412" align="left" valign="middle"><?php echo($row['descripcion']);?></td>
						<td align="center" valign="middle"><?php echo($row['um']);?></td>
						<td align="right" valign="middle"><?php echo(number_format($row['parcial'],2));?></td>
						<td align="center" valign="middle"><?php echo($row['meta']);?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total'],2));?></td>

    <?php
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo ('<td width="103" align="right">' . number_format($row[$arrfuentes[$x][0]], 2) . '</td>');
        $total_fila += $row[$arrfuentes[$x][0]];
    }
    ?>
    <td align="right" style="background-color: #DCD1FA;"><font
							<?php if(number_format($total_fila ,2) != number_format($row['total'],2) ) { echo('style="background-color:#FF0;color:#F00;"'); }?>>
	<?php echo( number_format($total_fila,2));?>
    </font></td>

					</tr>
  <?php
    $Index ++;
} // End While
$rsPresup->free();
?>
  </tbody>
				<tfoot>
					<tr style="color: #333; height: 20px;">
						<th align="center" valign="middle">&nbsp;</th>
						<th align="left" valign="middle">&nbsp;</th>
						<th align="center" valign="middle">&nbsp;</th>
						<th align="center" valign="middle">&nbsp;</th>
						<th align="center" valign="middle">&nbsp;</th>
						<th align="right" valign="middle"><?php echo( number_format($sumaTotal,2));?></th>
      <?php
    $total_fila = 0;
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo ('<th align="right">' . number_format($arrfuentes[$x][1], 2) . '</th>');
        $total_fila += $arrfuentes[$x][1];
    }
    ?>
      <th align="right" valign="middle"><?php echo( number_format($total_fila,2));?></th>
					</tr>
				</tfoot>
			</table>
			<br /> <br />

	<?php
$objHC = new HardCode();
$aLBRow = $objMP->LineaBase_Imprevistos($idProy, $idVersion);
$aLineaBaseImprv = $aLBRow['imprevistos'];
$aCostosDirectos = $objMP->GetCostosDirectos($idProy, $idVersion);
$aAdmisCostoTotal = $objMP->Adminis_CostoTotal($idProy, $idVersion);
$aCompoCostoTotal = $objMP->Componentes_CostoTotal($idProy, $idVersion);
$aPersGastoTotal = $objMP->Personal_GastoTotal($idProy, $idVersion);
$aEquipCostoTotal = $objMP->Equipamiento_CostoTotal($idProy, $idVersion);
$aFuncCostoTotal = $objMP->Funcionamiento_CostoTotal($idProy, $idVersion);

$costoTotalComp = $objMP->Componentes_CostoTotal($idProy, 1);
$costoDirecInicial = $objMP->GetCostosDirectos($idProy, 1);
$costoPersonalInicial = $objMP->Personal_GastoTotal($idProy, 1);
$costoTotalEquipamiento = $objMP->Equipamiento_CostoTotal($idProy, 1);
$costoTotalFunc = $objMP->Funcionamiento_CostoTotal($idProy, 1);
$costoAdminist = $objMP->Adminis_CostoTotal($idProy, 1);

$sumaFE = 0;
$aRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
while ($row = mysqli_fetch_assoc($aRs)) {
    $sumaFE += $row[$objHC->Nombre_Fondoempleo];
}
$aRs->free();

/*$datos = $objMP->listarTasasParametros($idProy, $idVersion);
$aLineaBase = round(($sumaFE * $datos['t02_porc_linea_base']) / 100, 2);
$aImprevistos = round(($sumaFE * $datos['t02_porc_imprev']) / 100, 2);
$aSupervision = round(($sumaFE * $datos['t02_proc_gast_superv']) / 100, 2);*/

/*$lineaBaseInicial = $objMP->lineaBase_total($idProy, 1);*/
$rowLBI = $objMP->LineaBase_Imprevistos($idProy, 1);
$lineaBaseInicial = $rowLBI['linea_base'];
$imprevistosInicialReal = $rowLBI['imprevistos'];
$supervisionInicialReal = $rowLBI['supervision'];

$rowLBActual = $objMP->LineaBase_Imprevistos($idProy, $idVersion);

$aLineaBase = $rowLBActual['linea_base'];
$aImprevistos = $rowLBActual['imprevistos'];
$aSupervision = $rowLBActual['supervision'];

$aRCs = $objPres->Acum_Costos_Ejecutados($idProy, $idVersion);
$aRow = mysqli_fetch_assoc($aRCs);

$totalEjecutado = $aRow['TotalEjecutado'];
$costoPersonal = $aRow['CostoPersonal'];
$costoEquipa = $aRow['CostoEquipamiento'];
$costoFunc = $aRow['CostoFuncionamiento'];
$costoAdm = $aRow['CostoAdministrativo'];
$costoImprev = $aRow['CostoImprevisto'];
$costoSup = $aRow['CostoSupervision'];
$aRCs->free();

?>

	<table width="99%" border="0" cellpadding="0" cellspacing="0">
				<thead style='font-weight: bold'>
					<tr style="background: #CFC;">
						<td colspan="4" align="center">Resúmen del Presupuesto <?php echo $idVersion > 1 ? ("Anual - Año " . ($idVersion - 1)) : "Inicial"; ?> </td>
					</tr>
					<tr>
						<td align="center">Costos Directos</td>
						<td align="center">Costos Indirectos</td>
						<td align="center">Costo Total del Proyecto</td>
						<td align="center">Presupuesto por Reasignar</td>
					</tr>
				</thead>
				<tbody class='data'>
					<tr>
						<td align='right'><?php echo(number_format($objMP->GetCostosDirectos($idProy, $idVersion),2));?></td>
						<td align='right'><?php echo(number_format($objMP->GetCostosInDirectos($idProy, $idVersion),2));?></td>
						<td align='right'><?php echo(number_format($objMP->GetCostosTotalProyecto($idProy, $idVersion),2));?></td>
						<td align='right'>
				<?php
                $aPreRs = $objMP->GetPresupuestoReasignado($idProy, $idVersion);
                while ($aRow = mysqli_fetch_assoc($aPreRs)) {
                    echo number_format($aRow['mreas'], 2) . '<br/>';
                }
                $aPreRs->free();
                ?>
				</td>
					</tr>
				</tbody>
			</table>

			<br />
			<br />

			<table width='99%' border='0' cellpadding='0' cellspacing='0'>
				<thead style='font-weight: bold'>
					<tr style="background: #CFC;">
						<td colspan="5" align="center">Saldo Proyectado por Ejecutar - <?php echo $idVersion > 1 ? ("Año " . ($idVersion - 1)) : "Inicial"; ?> </td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width='20%' align="center">&nbsp;</td>
						<td width='20%' align="center">PRESUPUESTO INICIAL</td>
						<td width='20%' align="center">GASTOS</td>
						<td width='20%' align="center">PRESUPUESTO ANUAL</td>
						<td width='20%' align="center">SALDO PROYECTADO</td>
					</tr>
				</thead>
				<tbody class='data'>
					<tr style="background-color: #E3FEE0;">
						<td style='font-weight: bold'>COSTOS DIRECTOS</td>
						<td align='right'><?php echo number_format($costoDirecInicial, 2); ?></td>
						<td align='right'><?php echo number_format(($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc), 2); ?></td>
						<td align='right'><?php echo number_format(($aCostosDirectos), 2); ?></td>
						<td align='right'><?php echo number_format(($costoDirecInicial - ($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) - $aCostosDirectos), 2); ?></td>
					</tr>
					<tr>
						<td>Costo de los Componentes</td>
						<td align='right'><?php echo number_format($costoTotalComp, 2); ?></td>
						<td align='right'><?php echo number_format($totalEjecutado, 2); ?></td>
						<td align='right'><?php echo number_format($aCompoCostoTotal, 2); ?></td>
						<td align='right'><?php echo number_format(($costoTotalComp - $totalEjecutado - $aCompoCostoTotal), 2); ?></td>
					</tr>
					<tr>
						<td>Costo Personal</td>
						<td align='right'><?php echo number_format($costoPersonalInicial, 2); ?></td>
						<td align='right'><?php echo number_format($costoPersonal, 2); ?></td>
						<td align='right'><?php echo number_format($aPersGastoTotal, 2); ?></td>
						<td align='right'><?php echo number_format(($costoPersonalInicial - $costoPersonal - $aPersGastoTotal), 2); ?></td>
					</tr>
					<tr>
						<td>Costo Equipamiento</td>
						<td align='right'><?php echo number_format($costoTotalEquipamiento, 2); ?></td>
						<td align='right'><?php echo number_format($costoEquipa, 2); ?></td>
						<td align='right'><?php echo number_format($aEquipCostoTotal, 2); ?></td>
						<td align='right'><?php echo number_format(($costoTotalEquipamiento - $costoEquipa - $aEquipCostoTotal), 2); ?></td>
					</tr>
					<tr>
						<td>Costo de Funcionamiento</td>
						<td align='right'><?php echo number_format($costoTotalFunc, 2); ?></td>
						<td align='right'><?php echo number_format($costoFunc, 2); ?></td>
						<td align='right'><?php echo number_format($aFuncCostoTotal, 2); ?></td>
						<td align='right'><?php echo number_format(($costoTotalFunc - $costoFunc - $aFuncCostoTotal), 2); ?></td>
					</tr>
					<tr style="background-color: #E3FEE0;">
						<td style="font-weight: bold;">COSTOS INDIRECTOS</td>
						<td align='right'><?php echo number_format(($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial + $supervisionInicialReal), 2); ?></td>
						<td align='right'><?php echo number_format(($costoAdm + $costoImprev + $costoSup), 2); ?></td>
						<td align='right'><?php echo number_format(($aAdmisCostoTotal + $aLineaBaseImprv + $aLineaBase + $aSupervision), 2); ?></td>
						<td align='right'><?php echo number_format(($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial + $supervisionInicialReal) - ($costoAdm + $costoImprev + $costoSup) - ($aAdmisCostoTotal + $aLineaBaseImprv + $aLineaBase + $aSupervision), 2);?></td>
					</tr>
					<tr>
						<td>Costos Administrativos</td>
						<td align='right'><?php echo number_format($costoAdminist, 2); ?></td>
						<td align='right'><?php echo number_format($costoAdm, 2); ?></td>
						<td align='right'><?php echo number_format($aAdmisCostoTotal, 2); ?></td>
						<td align='right'><?php echo number_format(($costoAdminist - $costoAdm - $aAdmisCostoTotal), 2); ?></td>
					</tr>
					<tr>
						<td>Costo Línea Base</td>
						<td align='right'><?php echo number_format($lineaBaseInicial, 2); ?></td>
						<td align='right'>0.00</td>
						<td align='right'><?php echo number_format($aLineaBase, 2); ?></td>
						<td align='right'><?php echo number_format(($lineaBaseInicial - $aLineaBase), 2); ?></td>
					</tr>
					<tr>
						<td>Costo Imprevistos</td>
						<td align='right'><?php echo number_format($imprevistosInicialReal, 2); ?></td>
						<td align='right'><?php echo number_format($costoImprev, 2); ?></td>
						<td align='right'><?php echo number_format($aLineaBaseImprv, 2); ?></td>
						<td align='right'><?php echo number_format(($imprevistosInicialReal - $costoImprev - $aLineaBaseImprv), 2); ?></td>
					</tr>
					<tr>
						<td>Costo Supervisión</td>
						<td align='right'><?php echo number_format($supervisionInicialReal, 2); ?></td>
						<td align='right'><?php echo number_format($costoSup, 2); ?></td>
						<td align='right'><?php echo number_format($aSupervision, 2); ?></td>
						<td align='right'><?php echo number_format(($supervisionInicialReal - $costoSup - $aSupervision), 2); ?></td>
					</tr>
				</tbody>
				<tfoot style='font-weight: bold;'>
					<tr>
						<td>TOTAL</td>
						<td align='right'><?php echo number_format(($costoDirecInicial + ($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial + $supervisionInicialReal)), 2); ?></td>
						<td align='right'><?php echo number_format((($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) + ($costoAdm + $costoImprev + $costoSup)), 2); ?></td>
						<td align='right'><?php echo number_format(($aCostosDirectos + ($aAdmisCostoTotal + $aLineaBaseImprv + $aLineaBase + $aSupervision)), 2); ?></td>
						<td align='right'><?php

echo number_format(($costoDirecInicial - ($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) - $aCostosDirectos + (($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial + $supervisionInicialReal) - ($costoAdm + $costoImprev + $costoSup) -
            																						($aAdmisCostoTotal + $aLineaBaseImprv + $aLineaBase + $aSupervision))), 2); ?></td>
					</tr>
				</tfoot>
			</table>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>