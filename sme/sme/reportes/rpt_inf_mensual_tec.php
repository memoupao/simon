<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");

require(constant("PATH_CLASS")."BLProyecto.class.php");
require(constant("PATH_CLASS")."BLInformes.class.php");
require(constant("PATH_CLASS")."BLPresupuesto.class.php");
require(constant("PATH_CLASS")."BLManejoProy.class.php");
require(constant("PATH_CLASS")."BLTablasAux.class.php");

$idProy = $objFunc->__Request('idProy');
$idVs = $objFunc->__Request('idVers');
if($idVs == '') {
	$idVs = 1;
}

$idAnio = $objFunc->__Request('idAnio');
$idMes = $objFunc->__Request('idMes');

$objTablas = new BLTablasAux();
$HardCode = new HardCode();

$idFte = $HardCode->codigo_Fondoempleo;

$objProy = new BLProyecto();
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $objProy->MaxVersion($idProy));

$objInf = new BLInformes();
$row = $objInf->InformeMensualSeleccionar($idProy, $idAnio, $idMes, $idVs);
$rsInfMensFechas = $objInf->getInfMensDates($idProy);
$numInfMensFechas = mysql_num_rows($rsInfMensFechas);
$arAnios = array();
$arInfMensFechas = array();

$objFunc = new Functions();
$tmpAnio = 0;
while($rwFechas = mysql_fetch_array($rsInfMensFechas)) {
	$time = strtotime($rwFechas ['t20_fch_pre']);
	$arInfMensFechas [] = array(
			'anio' => $rwFechas ['t20_anio'],
			'mes' => $rwFechas ['t20_mes'],
			'fecha' => date('m/y', $time),
			'periodo' => $objFunc->periodoFormat($rwFechas ['t20_periodo']) 
	);
	if($tmpAnio != $rwFechas ['t20_anio']) {
		$arAnios [] = $rwFechas ['t20_anio'];
	}
	$tmpAnio = $rwFechas ['t20_anio'];
}
$arTotPercent = array();
$arTotPercentTot = $arTotPercentCurrent = array();
?>
<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>CUADRO DE AVANCE FÍSICO DE ACTIVIDADES</title>
<script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<style>
.th50 {
	height: 50px !important;
}
.tdw35 {
	width: 35px !important;
	min-width: 35px !important;
}
.thAnio {
	background-color: #FFCC99;
	display: block;
	height: 18px;
	line-height: 16px;
	vertical-align: middle;
}
.TableGrid table tbody tr:hover {
	border: none;
	padding: 0px;
}
.tbl {
	border: 1px solid black;
	/*float: left;*/
	margin-left: 5px; 
}
.tbl thead {
	background: none !important;
	color: #000000 !important;
	font-size: 11px !important;
}
.tbl tr {
	background: none !important;
}
.tbl tr td,.tbl tr th {
	border: 1px solid black;
	height: 16px;
}
.tbl tr th {
	height: 18px;
}
.tbl tr td {
	padding: 2px;
}
.tbl tr th:hover {
	color: #000000;
	cursor: auto;
	text-decoration: none;
}
</style>
		<div id="divBodyAjax" class="TableGrid">
			<!-- InstanceBeginEditable name="BodyAjax" -->
			<h3 style="margin: 0; padding-left: 5px; text-align: left;">CUADRO DE
				AVANCE FÍSICO DE ACTIVIDADES</h3>
			<h3 style="margin: 0; padding-left: 5px; text-align: left;">Nombre del Proyecto: <?php echo($Proy_Datos_Bas['t02_nom_proy']);?></h3>
			<p
				style="font-size: 12px; font-weight: bold; margin: 0; padding: 0 0 0 5px; text-align: left;">Inicio del proyecto: <?php echo $objFunc->fechaEnLetras($Proy_Datos_Bas['t02_fch_ini']);?></p>
			<p
				style="font-size: 12px; font-weight: bold; margin: 0; padding: 0 0 0 5px; text-align: left;">Avance reportado al: <?php echo $objFunc->fechaEnLetras(date('d/m/Y'));?></p>
			<table  border="0" align="center" cellpadding="0" width="auto"
				cellspacing="1" class="tbl">
				<thead>
					<tr>
						<th align="left" colspan="4">Código del Proyecto: <?php echo $idProy;?> </th>
						<?php foreach($arAnios as $item) { ?>
						<th align="center" colspan="36"><div class="thAnio"><?php echo $item;?>° año </div>
						</th>
						<?php } ?>
						<th colspan="4"><span
							style="background-color: #CCFFFF; display: block; height: 17px; line-height: 16px;">
								Acumulado al mes reportado </span></th>
					</tr>
					<tr>
						<th align="left" nowrap="nowrap" colspan="4">Ejecutor: <?php echo $Proy_Datos_Bas['t01_nom_inst'];?> </th>
						<?php foreach($arInfMensFechas as $item) { ?>
						<th align="center" colspan="3"> <?php echo $item['periodo'];?> </th>
						<?php } ?>
						<th colspan="4" rowspan="2"><span
							style="background-color: #CCFFFF; display: block; height: 37px; line-height: 32px;">
							<?php echo $objFunc->fechaEnLetras(date('d/m/Y'),2);?>
							</span></th>
					</tr>
					<tr>
						<th align="center" colspan="3">INDICADORES</th>
						<th align="center" rowspan="2" style="min-width:55px  ;width: 55px ! important;">METAS</th>
						<?php foreach($arInfMensFechas as $item) { ?>
						<th align="center" colspan="3"> <?php echo $item['mes'].'° mes';?> </th>
						<?php } ?>
					</tr>
					<tr>
						<th align="center" class="th50" style="min-width: 90px; width: 90px ! important;">TIPO</th>
						<th align="center" class="th50" style="min-width: 400px; width: 400px ! important;">OBJETIVO</th>
						<th align="center" class="th50">UNIDAD DE MEDIDA</th>
						<?php foreach($arInfMensFechas as $item) { ?>
						<th align="center" class="th50 tdw35">Prog.</th>
						<th align="center" class="th50 tdw35">Ejec.</th>
						<th align="center" class="th50 tdw35">%</th>
						<?php } ?>
						<th align="center" class="th50 tdw35"
							style="min-width: 55px; width: 55px ! important;"><span
							style="background-color: #CCFFFF; display: block; height: 75px; line-height: 73px;">Prog.</span></th>
						<th align="center" class="th50 tdw35"
							style="min-width: 55px; width: 55px ! important;"><span
							style="background-color: #CCFFFF; display: block; height: 75px; line-height: 73px;">Ejec.</span></th>
						<th align="center" class="th50 tdw35"
							style="min-width: 55px; width: 55px ! important;"><span
							style="background-color: #CCFFFF; display: block; height: 75px; line-height: 73px;">%</span></th>
						<th align="center" class="th50 tdw35"
							style="min-width: 70px; width: 70px ! important;"><span
							style="background-color: #CCFFFF; display: table-cell; height: 75px; line-height: 15px; vertical-align: middle;">%
								de Avance en relación al total</span></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$rs = $objInf->ListaComponentes($idProy);
					while($rowComp = mysqli_fetch_assoc($rs)) {
						?>
					<tr>
						<td
							colspan="<?php echo 8 +($numInfMensFechas * count($arAnios));?>">COMPONENTE <?php echo $rowComp['t08_cod_comp'];?>: <?php echo $rowComp['t08_comp_desc'];?></td>
					</tr>
						<?php
						$rsProd = $objInf->ListaActividadesComp($idProy, $rowComp ['t08_cod_comp']);
						while($rowProd = mysqli_fetch_assoc($rsProd)) {
							?>
						<tr>
						<td
							colspan="<?php echo 8 +($numInfMensFechas * count($arAnios));?>">PRODUCTO <?php echo $rowProd['codigo']?>: <?php echo $rowProd['actividad']?></td>
					</tr>
						<?php
							$rsAct = $objInf->ListaSubActividades($idProy, $rowProd ['codigo'], 1, 1);
							$numAct = mysqli_num_rows($rsAct);
							if(! $numAct)
								continue;
							?>						
							<tr>
						<td rowspan="<?php echo($numAct+1)?>">Actividad</td>
					</tr>
							<?php while($rowAct = mysqli_fetch_assoc($rsAct)) { ?>
							<tr>
						<td><?php echo $rowAct['t08_cod_comp'].".".$rowAct['t09_cod_act'].".".$rowAct['t09_cod_sub']." ".$rowAct['subactividad'];?></td>
						<td align="center"><?php echo $rowAct['t09_um'];?></td>
						<!-- <td align="center"><?php echo $rowAct['plan_mtaanio'];?></td> -->
						<td align="center"><?php echo $rowAct['plan_mtatotal'];?></td>
								<?php
								$totalProg = $totalEjec = 0;
								foreach($arInfMensFechas as $k => $item) {
									$rsActMeses = $objInf->ListaSubActividadesMeses($idProy, $rowAct ['t08_cod_comp'] . "." . $rowAct ['t09_cod_act'] . "." . $rowAct ['t09_cod_sub'], $item ['anio'], $item ['mes']);
									$rwActMeses = mysqli_fetch_array($rsActMeses);
									$totPer = round(((int) $rwActMeses ['ejec_mtames'] /(int) $rwActMeses ['plan_mtames']) * 100);
									$arTotPercent [$k] [] = $totPer;
									?>
								<td align="center"><?php echo $rwActMeses['plan_mtames'];?></td>
						<td align="center"><?php echo $rwActMeses['ejec_mtames'];?></td>
						<td align="center"><?php echo $totPer;?> %</td>
								<?php
									$totalProg +=(int) $rwActMeses ['plan_mtames'];
									$totalEjec +=(int) $rwActMeses ['ejec_mtames'];
								}
								?>
									<?php
								$totPercentTot = round(($totalEjec / $totalProg) * 100);
								// $totPercentCurrent = round(($totalEjec /(int) $rowAct ['plan_mtaanio']) * 100);
								$totPercentCurrent = round(($totalEjec /(int) $rowAct ['plan_mtatotal']) * 100);
								$arTotPercentTot [] = $totPercentTot;
								$arTotPercentCurrent [] = $totPercentCurrent;
								?>
								<td align="center" style="padding: 0px; background-color: #CCFFFF;">
										<?php echo $totalProg;?>
								</td>
						<td align="center" style="padding: 0px;background-color: #CCFFFF;" >
										<?php echo $totalEjec;?>
						</td>
						<td align="center" style="padding: 0px;background-color: #CCFFFF;">
										<?php echo $totPercentTot;?> %
									</td>
						<td align="center" style="padding: 0px;background-color: #CCFFFF;">
										<?php echo $totPercentCurrent;?> %
									</td>
					</tr>
							<?php	} ?>
						<?php } ?>
					<?php } ?>
				</tbody>
				<tfooter>
				<th colspan="4">TOTAL</th>
					<?php foreach($arInfMensFechas as $k => $item) { ?>					
					<th align="center" style="background-color: #CCCCCC;"></th>
				<th align="center" style="background-color: #CCCCCC;"></th>
				<th align="center"><?php echo round(array_sum($arTotPercent[$k]) / count($arTotPercent[$k])) ?> % </th>
					<?php } ?>
					<th align="center" align="center" style="padding: 0px;"></th>
				<th align="center" align="center" style="padding: 0px;"></th>
				<th align="center" align="center" style="padding: 0px;"><span
					style="background-color: #CCFFFF; display: block; line-height: 20px;">
							<?php echo round(array_sum($arTotPercentTot)/ count($arTotPercentTot)) ?> %
						</span></th>
				<th align="center" align="center" style="padding: 0px;"><span
					style="background-color: #CCFFFF; display: block; line-height: 20px;">
							<?php echo round(array_sum($arTotPercentCurrent) / count($arTotPercentCurrent)) ?> %
						</span></th>
				</tfooter>
			</table>
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
	</form>
</body>
</html>
<?php } ?>