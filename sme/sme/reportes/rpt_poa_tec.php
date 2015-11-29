<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio') ? $objFunc->__Request('idAnio') : $objFunc->__Request('anio');
$idVersion = $objFunc->__Request('idVersion') ? $objFunc->__Request('idVersion') : $idAnio + 1;
$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);
$objRep = new BLReportes();
$objProy = new BLProyecto();
$row = $objRep->RepFichaProy($idProy, $objProy->MaxVersion($idProy));
$objPOA = new BLPOA();
$rowInfPoa = $objPOA->POA_Seleccionar($idProy, $idAnio);
$rsSector = $objProy->SectoresProductivos_Listado($idProy);
$objMP = new BLManejoProy();
$rsPresup = $objMP->Rpt_PresupuestoAnalitico($idProy, $idVersion);
$objPres = new BLPresupuesto();
$objInf = new BLInformes();

if ($idVersion > 1) {
	$msgTitle = "POA Año " . ($idVersion - 1);
}

function EscribirFila($resumen, $indicador, $um, $meta, $medios, $supuestos, $isMerge, $numRows, $tituloHead = '')
{
	if($isMerge && $numRows>1)
	{
		$strFila = '
		<tr>
	      <td align="left" valign="top" rowspan="'.$numRows.'">'.$tituloHead.($resumen).'</td>
	      <td align="left" valign="top" >'.($indicador).'</td>
	      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
	      <td align="left" valign="top" rowspan="'.$numRows.'">'.($supuestos).'&nbsp;</td>
	    </tr> ' ;
		echo($strFila);
		return;
	}
	if(!$isMerge && $numRows>1)
	{
		$strFila = '
		<tr>
	      <td align="left" valign="top">'.($indicador).'</td>
	      <td align="left" valign="top"">'.($medios).'&nbsp;</td>
	    </tr> ';
	}
	else
	{
		if (strstr($indicador,'.-')) {
			$arTextIndi = explode('.-',$indicador);
			$textIndi = trim($arTextIndi[1]);
			if (!empty($textIndi)) {
				$strFila = '
							<tr >
						      <td align="left" valign="top" >'.($resumen).'</td>
						      <td align="left" valign="top" >'.($indicador).'</td>						      
						      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
						      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
						    </tr> ' ;
			} else {
				$strFila = '
						<tr >
					      <td align="left" valign="top" >'.($resumen).'</td>
					      <td align="left" valign="top" ></td>					      
					      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
					      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
					    </tr> ' ;
			}
		} else {
			$strFila = '
					<tr >
				      <td align="left" valign="top" >'.($resumen).'</td>
				      <td align="left" valign="top" >'.($indicador).'</td>				      
				      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
				      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
				    </tr> ' ;
		}
	}
	echo($strFila);
}
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
		<div id="divBodyAjax" class="TableGrid">
	<?php
	$fecha_fin_aprobada = $row['t02_fch_tam']; 
	if ($row['t02_fch_tam']=='00/00/0000') {
		$fecha_fin_aprobada = $row['t02_fch_tre'];
	}
	?>
			<table width="650" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td width="24%" height="25" align="left" valign="middle" nowrap="nowrap" bgcolor="#ccffcc">
							<b>POA Correspondiente al año</b>
						</td>
						<td align="left" valign="middle"><b>Año <?php echo($idAnio);?></b>&nbsp;</td>
						<td colspan="2" align="left" valign="middle" bgcolor="#ccffcc"><b>Código del Proyecto</b></td>
						<td width="34%" align="left" valign="middle"><b><?php echo($row['t02_cod_proy']);?></b></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#ccffcc"><b>Periodo de Referencia</b></td>
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Del:</b></td>
						<td align="left" valign="middle"><b><?php echo date("d/m/Y",strtotime($rowInfPoa['inicio']));?></b></td>
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Al:</b></td>
						<td align="left" valign="middle"><b><?php echo date("d/m/Y",strtotime($rowInfPoa['fin']));?></b></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#ccffcc"><b>Fecha de Presentación:</b></td>
						<td colspan="4" align="left" valign="middle"><b>
        				<?php echo (date("d/m/Y",strtotime($rowInfPoa['fch_crea'])));?>
		 				</b>
		 				</td>
					</tr>
				</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr style="font-size: 11px;">
						<td height="32" width="24%" align="left" valign="middle" bgcolor="#ccffcc"><b>Título
								del Proyecto</b></td>
						<td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>
					</tr>
					<?php    
					    $rsAmbGeo = $objProy->listarAmbitoGeoAgrupado($idProy, $idVersion);
				    ?>
					<tr style="font-size: 11px;">
						<td rowspan="3" height="32" align="left" valign="middle" bgcolor="#ccffcc"><b>Ubicación:</b></td>
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Departamento</b></td>
						<td height="32" align="left" valign="middle"><?php echo( $rsAmbGeo['dpto']);?> </td>
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Provincia(s)</b></td>
						<td height="32" align="left" valign="middle"><?php echo( $rsAmbGeo['prov']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Distrito(s)</b></td>
						<td colspan="3" height="32" align="left" valign="middle"> <?php echo( $rsAmbGeo['dist']);?> </td>
					</tr>
      				<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Propósito del Proyecto</b></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_pro']));?></td>
					</tr>
				</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc" width="24%"><b>Institución Ejecutora</b></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t01_nom_inst']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Instituciones Asociadas o Colaboradoras</b></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['inst_asoc_colab']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><b>Población Objetivo</b></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
						<!-- <td align="left" valign="middle" bgcolor="#ccffcc"><strong>Fecha
								programada para el término del proyecto</strong></td>
						<td  align="left" valign="middle"><?php echo $fecha_fin_aprobada;?></td> -->
					</tr>
				</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" cellpadding="0" cellspacing="0">
					<tbody class="data" bgcolor="#FFFFFF">			
						<tr style="font-size: 11px;">
							<td width="40%" align="left" valign="middle" bgcolor="#ccffcc">
								<b>Fecha real de Inicio del proyecto</b></td>
							<td align="left" valign="middle"><?php echo ($row['t02_fch_ini']);?></td>
						</tr>
						<tr style="font-size: 11px;">
							<td align="left" valign="middle" bgcolor="#ccffcc">
								<b>Fecha programada para el término del proyecto</b></td>
							<td  align="left" valign="middle"><?php echo ($fecha_fin_aprobada);?></td>
						</tr>
					</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr bgcolor="#ccffcc" style="font-size: 11px;">
						<td width="24%" align="center" valign="middle"><b>Resumir datos de
								programacion presupuestal</b></td>
						<td  align="left" valign="middle"><div align="center">
								<b>Total programado S/.</b>
							</div></td>
						<td width="19%" align="left" valign="middle"><div align="center">
								<b>Total ejecutado S/.</b>
							</div></td>
						<td align="left" valign="middle"><div align="center">
								<b>Porcentaje de avance (%)</b>
							</div></td>
					</tr>
				    <?php
				    $totPro = 0;
				    $totEje = 0;
				    $aPoaPresupArr = array();
				    $rsPres = $objRep->Rep_POA_Presupuesto($idProy, $idAnio);
				    while ($aRow = mysqli_fetch_assoc($rsPres)) {
				        $aPoaPresupArr[$aRow['t41_fte_finan']] = $aRow['total'];
				        $totEje += $aRow['total'];
				    }
				    $rsPres->free();
				    $rsFuentes = $objRep->RepFichaProy_Fuentes($idProy, $idVersion);
				    $aTotEje = 0;
				    $aTotPor = 0;
				    while ($rfte = mysqli_fetch_assoc($rsFuentes)) {
				        $aPor = $aPoaPresupArr[$rfte['t01_id_inst']] * 100 / $rfte['monto'];
				        $totPro += $rfte['monto'];
				        $aTotEje += $aPoaPresupArr[$rfte['t01_id_inst']];
				        ?>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#ccffcc"><?php echo $rfte['t01_sig_inst']; ?></td>
						<td  align="left" valign="middle">
							<div style="width: 120px; text-align: right;"><?php echo number_format($rfte['monto'],2); ?></div>
						</td>
						<td>
							<div style="width: 90px; text-align: right;"><?php echo number_format($aPoaPresupArr[$rfte['t01_id_inst']], 2); ?></div>
						</td>
						<td>
							<div style="width: 130px; text-align: right;">
				      			<?php echo number_format($aPor, 2)." %"; ?>
				      		</div>
						</td>
					</tr>
					<?php
				    }
				    $rsFuentes->free();
				    ?>
				</tbody>
				<tfoot>
					<tr>
						<td align="left" width="24%"  bgcolor="#ccffcc" style="background-color: #ccffcc;" valign="middle">TOTAL DEL PROYECTO</td>
						<td align="left"  bgcolor="#ffffff" style="background-color: #ffffff; border-left: 1px solid #333333; border-right: 1px solid #333333; padding: 3px 4px;" valign="middle"><?php echo(number_format($totPro,2));?></td>
	  					<?php $porcentaje = ($aTotEje * 100) / $totPro ;?>
	  					<td style="background-color: #ffffff; border-left: 1px solid #333333; border-right: 1px solid #333333; padding: 3px 4px;"><?php echo(number_format($aTotEje,2));?></td>
						<td style="background-color: #ffffff; border-left: 1px solid #333333; border-right: 1px solid #333333; padding: 3px 4px;"><?php echo(number_format($porcentaje,2)." %");?></td>
					</tr>
				</tfoot>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" cellpadding="0" cellspacing="0">
					<tbody class="data" bgcolor="#FFFFFF">			
						<tr style="font-size: 11px;">
							<td width="15%" align="center" valign="middle" bgcolor="#ccffcc" style="text-transform: uppercase;"><b>Relación del Personal</b></td>
							<td width="20%" align="center" valign="middle" style="text-transform: uppercase;"><b>Nombre</b></td>
							<td width="20%"align="center" valign="middle" style="text-transform: uppercase;"><b>Calificación</b></td>
							<td align="center" valign="middle" style="text-transform: uppercase;"><b>Principales Funciones</b></td>
						</tr>
						<?php 
						$listaPersonal = $objMP->Personal_Listado($idProy, $idVersion);
						while ($row = mysqli_fetch_assoc($listaPersonal)) {
						?>
						<tr style="font-size: 11px;">
							<td align="left" valign="middle" bgcolor="#ccffcc"><?php echo($row["cargo"]);?></td>
							<td align="left" valign="middle" ><?php echo($row["nombre"]);?> <?php echo($row["paterno"]);?> <?php echo($row["materno"]);?></td>
							<td align="left" valign="middle" ><?php echo($row["especialidad"]);?></td>
							<td align="left" valign="middle" ><?php echo($row["tdr"]);?></td>
						</tr>
						<?php 
						}
        				$listaPersonal->free();	
						?>
					</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		    <?php
		    $objRep = new BLPOA();
		    $row = $objRep->POA_Seleccionar($idProy, $idAnio);
		    ?>
			<table width="650" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid" style="border:none;">
				<tr>
					<td align="left" valign="middle"><b>1. PUNTOS ATENCION</b></td>
				</tr>
				<tr>
					<td align="left" style="text-align: justify;">
						Referirse a los aspectos más importantes que se debe tomar en
						cuenta, antes de iniciar la ejecución del periodo programado.
						Hacer alusión a posibles reprogramaciones debido al cambio de la
						situación prevista en el momento de diseño del proyecto.
						Considerar los factores externos que hayan variado respecto a la
						etapa de diseño. De haber actividades que deban ejecutarse
						antes de la fecha prevista, indicar por qué se requiere adelantar
						la ejecución.</td>
				</tr>
				<tr>
					<td align="left" style="text-align: justify;"><?php echo(nl2br($row["t02_punto_aten"]));?></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle"><b>2.
							COYUNTURA ACTUAL</b></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td width="751" height="23" align="left" valign="middle"><b>2.1.
							Política Nacional y/o sectorial</b></td>
				</tr>
				<tr>
					<td>Mencionar
						los cambios o nuevos dispositivos legales que estén vinculados al
						desarrollo del proyecto y que se hayan registrado entre la
						culminación la etapa anterior y el inicio de la fase objetivo del
						POA. Estos cambios stán referidos al ámbito nacional o al del
						gobierno local.</td>
				</tr>
				<tr>
					<td align="left" style="font-size: 11px; text-align: justify;"><?php echo(nl2br($row["t02_politica"]));?></td>
				</tr>
				<tr>
					<td style="color: #009; font-size: 11px;">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable" >
					<td width="751" height="23" align="left" valign="middle"><b>2.2.
							Beneficiarios y principales partes implicadas</b></td>
				</tr>
				<tr>
					<td align="left">Referirse a
						la población objetivo, a las organizaciones que las involucran y
						a los directivos que participarían en cualquiera de las
						actividades programadas, y, de existir barreras, como se plantea
						en esta etapa la uperación de las mismas.</td>
				</tr>
				<tr>
					<td align="left" style="text-align: justify;"><?php echo(nl2br($row["t02_benefic"]));?></td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td width="751" height="23" align="left" valign="middle"><b>2.3.
							Otras Intervenciones</b></td>
				</tr>
				<tr>
					<td align="left">Comentar si
						en forma paralela al desarrollo del proyecto, se registran en la
						zona de trabajo otras intervenciones convergentes con los
						objetivos del proyecto.</td>
				</tr>
				<tr>
					<td align="left" style="text-align: justify;"><?php echo(nl2br($row["t02_otras_interv"]));?></td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td width="751" height="23" align="left" valign="middle"><b>2.4.
							Documentación disponible</b></td>
				</tr>
				<tr>
					<td align="left">
						Señalar con que documentación adicional al POA se cuenta, para efectos de enriquecer el POA y que sirvan de apoyo al proceso de monitoreo y evaluación.
					</td>
				</tr>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid" style="border:none;">
				<tr>
					<td align="left" valign="middle"><b>3. DESARROLLO DEL PROYECTO</b></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td width="751" height="23" align="left" valign="middle"><b>3.1. Marco Lógico</b></td>
				</tr>
			</table>
<table width="650" align="center" cellpadding="0" cellspacing="0"  >
  <thead>
	<tr style="font-weight:bold;">
      <td width="18%" valign="top" style="background-color:#fde9d9;"><p align="center">ESTRATEGIA DE INTERVENCIÓN</p></td>
      <td width="19%" valign="top" style="background-color:#fde9d9;"><p align="center">INDICADORES VERIFICABLES OBJETIVAMENTE </p></td>
      <td width="10%" valign="top" style="background-color:#fde9d9;"><p align="center">MEDIOS DE VERIFICACIÓN</p></td>      
      <td width="20%" valign="top" style="background-color:#fde9d9;"><p align="center">RIESGOS / SUPUESTOS</p></td>
    </tr>
  </thead>
  <tbody class="data" bgcolor="#FFFFFF">
  	<?php 
	/* --------------------------------------- Finalidad ----------------------------------------------- */
	$rsIndFin = $objML->ListadoIndicadoresOD($idProy, $idVersion);
	$rsSupFin = $objML->ListadoSupuestosOD($idProy, $idVersion);
	$NumRows = mysql_num_rows($rsIndFin);
	$rowInd = mysql_fetch_assoc($rsIndFin) ; 
	$Sup =  $objFunc->resultToString($rsSupFin,array('t06_cod_fin_sup','t06_sup'));
	EscribirFila($ML['t02_fin'],  $rowInd['t06_cod_fin_ind'].".- ".$rowInd['t06_ind'], $rowInd['t06_um'], $rowInd['t06_mta'], $rowInd['t06_fv'], $Sup,  true, $NumRows, '<b>1.1.1 FIN </b> ');
	while($rowInd = mysql_fetch_assoc($rsIndFin))  
	{
		EscribirFila($ML['t02_fin'],  $rowInd['t06_cod_fin_ind'].".- ".$rowInd['t06_ind'], $rowInd['t06_um'], $rowInd['t06_mta'], $rowInd['t06_fv'], $Sup,  false, $NumRows);	
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
    <?php
	/* --------------------------------------- Proposito ----------------------------------------------- */
	$rsIndProp = $objML->ListadoIndicadoresOG($idProy, $idVersion);
	$rsSupProp = $objML->ListadoSupuestosOG($idProy, $idVersion);
	$NumRows = mysql_num_rows($rsIndProp);
	$rowInd = mysql_fetch_assoc($rsIndProp) ; 
	$Sup =  $objFunc->resultToString($rsSupProp,array('t07_cod_prop_sup','t07_sup'));
	EscribirFila($ML['t02_pro'], $rowInd['t07_cod_prop_ind'].".- ".$rowInd['t07_ind'], $rowInd['t07_um'], $rowInd['t07_mta'], $rowInd['t07_fv'], $Sup,  true, $NumRows,'<b>1.1.2 PROPOSITO </b> ');
	while($rowInd = mysql_fetch_assoc($rsIndProp))  
	{
		EscribirFila($ML['t02_pro'], $rowInd['t07_cod_prop_ind'].".- ".$rowInd['t07_ind'], $rowInd['t07_um'], $rowInd['t07_mta'], $rowInd['t07_fv'], $Sup,  false, $NumRows);	
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
     <tr >
      <td colspan="4" align="left" valign="middle" style="background-color: #ffff99;">COMPONENTES: INDICADORES DE PRODUCTO</td>
     </tr>
    <?php
	/* --------------------------------------- Componentes ----------------------------------------------- */
	$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
	while($rowcomp = mysql_fetch_assoc($rsComp))  
	{
		$rsIndComp = $objML->ListadoIndicadoresOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
		$rsSupComp = $objML->ListadoSupuestosOE($idProy, $idVersion,  $rowcomp['t08_cod_comp']);
		$NumRows = mysql_num_rows($rsIndComp);
		$rowInd = mysql_fetch_assoc($rsIndComp) ; 
		$Sup =  $objFunc->resultToString($rsSupComp,array('t08_cod_comp_sup','t08_sup'));
		$num_correlativo = $rowcomp['t08_cod_comp'].".".$rowInd['t08_cod_comp_ind'].".- ".$rowInd['t08_ind'];
		EscribirFila($rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'], $num_correlativo, $rowInd['t08_um'], $rowInd['t08_mta'], $rowInd['t08_fv'], $Sup,  true, $NumRows);
		while($rowInd = mysql_fetch_assoc($rsIndComp))  
		{
			$num_correlativo = $rowcomp['t08_cod_comp'].".".$rowInd['t08_cod_comp_ind'].".- ".$rowInd['t08_ind'];							
			EscribirFila($rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'], $num_correlativo , $rowInd['t08_um'], $rowInd['t08_mta'], $rowInd['t08_fv'], $Sup,  false, $NumRows);
		}
	}
	?>
  </tbody>  
</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid" style="border:none;">
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td height="23" align="left" valign="middle"><b>3.2. Componentes y lineamientos estratégicos</b></td>
				</tr>
			</table>
			<div class="TableGrid">
				<table width="650" cellpadding="0" cellspacing="0">
					<tbody class="data" bgcolor="#FFFFFF">
						<tr style="font-weight: bold;">
							<td colspan="2" height="15" align="center"style="background-color: #ccffcc; word-break: break-all;"  valign="middle">AÑO <?php echo($idAnio);?></td>
							<td rowspan="2" style="background-color: #ccffcc; word-break: break-all;" width="51" height="15" align="center" valign="middle">Unidad
								Medida</td>
							<td rowspan="2"  width="42" height="15" align="center" valign="middle" style="background-color: #ccffcc; word-break: break-all;">Meta
								Fisica</td>
							<td  rowspan="2"  height="15" align="center" valign="middle" style="background-color: #99ccff; word-break: break-all;">Meta Ejecutada al corte</td>
							<td  rowspan="2"  height="15" align="center" valign="middle" style="background-color: #99ccff; word-break: break-all;">Meta Ejecutada Proyectada</td>
							<td  rowspan="2"  height="15" align="center" valign="middle" style="background-color: #99ccff; word-break: break-all;">Meta Ejecutada Total</td>
							<td  rowspan="2" height="15" align="center" valign="middle" style="background-color: #99ccff; word-break: break-all;">Aumento (+) o Disminución (-) de Actividades</td>
							<td  rowspan="2" height="15" align="center" valign="middle" style="background-color: #99ccff; word-break: break-all;">Meta por Ejecutar en el Periodo</td>
							<td  rowspan="2" height="15" align="center" valign="middle" style="background-color: #ffcc00; word-break: break-all;">Meta Total Reprogramada</td>
						</tr>
						<tr style="font-weight: bold;">
							<td colspan="2" height="15"  style="background-color: #ccffcc;" align="center" valign="middle">MES</td>							
						</tr>
		<?php
$objML = new BLMarcoLogico();
$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
$rowsCO = mysql_num_rows($rsComp);
while ($rowCO = mysql_fetch_assoc($rsComp)) {
    ?>
      <tr style="background-color: #ffffcc;">
							<td colspan="10" align="left" valign="middle">
								<div style="display: inline-block;">
									<b>Componente <?php
    echo " " . $rowCO['t08_cod_comp'];
    ?>:</b>
								</div>
								<div style="display: inline-block;">
		<?php
    echo $rowCO['t08_comp_desc'];
    ?>
        </div>
							</td>
						</tr>
    <?php
    $objML = new BLMarcoLogico();
    $objPOA = new BLPOA();
    $rs = $objML->ListadoActividadesOE($idProy, $idVersion, $rowCO['t08_cod_comp']);
    $rows = mysql_num_rows($rs);
    while ($rowAct = mysql_fetch_assoc($rs)) {
        ?>
   		    <tr style="border: solid 1px #CCC; background-color: #ffcc99">
							<td width="28" align="left" valign="middle">
              <?php echo($rowCO['t08_cod_comp'].".".$rowAct['t09_cod_act']);?></td>
							<td height="15" colspan="10" align="left" valign="middle"><b><?php echo($rowAct['t09_act']);?></b></td>
						</tr>
			<?php
        $iRs = $objPOA->POA_ListadoSubActividades($idProy, $idVersion, $rowCO['t08_cod_comp'], $rowAct['t09_cod_act'], $idAnio);
        $RowIndex = 0;
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
                    <tr class="RowData"
							<?php if($row['t09_obs_mt']!=''){echo("style='color:red;'");}?>>
							<td width="28" align="left" valign="middle" bgcolor="#FFFFFF"><span style="font-family: Tahoma;">I</span>.<?php echo($row['codigo']);?></td>
							<td align="left" valign="middle"><?php echo( $row['descripcion']);?></td>
							<td align="center" valign="middle"><?php echo( $row['um']);?></td>
							<td align="center" valign="middle" nowrap="nowrap"><?php echo(number_format($row['mfi']));?></td>
							<td width="67" align="center" valign="middle"><?php echo(number_format($row['mpaa']));?></td>
							<td width="62" align="center" valign="middle"><?php echo(number_format($row['meaa']));?></td>
							<td width="51" align="center" valign="middle"><?php echo(number_format($row['mtpe']));?></td>
							<td width="44" align="center" valign="middle">
                          	<?php echo(number_format($row['meta_poa']));?>
                      		</td>
                      		<td width="77" align="center" valign="middle"><?php echo(number_format($row['mpar']));?></td>
							<td width="63" align="center" valign="middle"><?php echo(number_format($row['mprog']));?></td>													
						</tr>
	   <?php
        }
        $iRs->free();
    }
} /* cerrar componentes */
?>
				<tr style="background-color: #FFF;">
					<td colspan="10" align="left" valign="middle">
						<b>MANEJO DEL PROYECTO</b>
					</td>
				</tr>
				<tr style="border: solid 1px #CCC; background-color: #DAF3DD;">
					<td width="28" align="left" valign="middle">6.1</td>
					<td height="15" colspan="10" align="left" valign="middle"><b>PERSONAL DEL PROYECTO</b></td>
				</tr>
				<tr style="border: solid 1px #CCC; background-color: #DAF3DD;">
					<td width="28" align="left" valign="middle">6.1.1</td>
					<td height="15" colspan="10" align="left" valign="middle"><b>Retribuciones al personal</b></td>
				</tr>				
				<tr class="RowData">
					<td width="28" align="left" valign="middle" bgcolor="#FFFFFF">6.1.1.1</td>
					<td align="left" valign="middle" bgcolor="#FFFFFF">Jefe de Proyecto</td>
					<td align="left" valign="middle">Remuneración</td>
					<td align="center" valign="middle" nowrap="nowrap">36</td>
					<td width="67" align="center" valign="middle">12</td>
					<td width="62" align="center" valign="middle">0</td>
					<td width="51" align="center" valign="middle">12</td>
					<td width="44" align="center" valign="middle"></td>
                    <td width="77" align="center" valign="middle">24</td>
					<td width="63" align="center" valign="middle">36</td>													
				</tr>
				<tr style="border: solid 1px #CCC; background-color: #DAF3DD;">
					<td width="28" align="left" valign="middle">6.2</td>
					<td height="15" colspan="10" align="left" valign="middle"><b>EQUIPAMIENTO DEL PROYECTO</b></td>
				</tr>				
				<tr style="border: solid 1px #CCC; background-color: #DAF3DD;">
					<td width="28" align="left" valign="middle">6.2.1</td>
					<td height="15" colspan="10" align="left" valign="middle"><b>Equipos y bienes duraderos</b></td>
				</tr>				
				<tr class="RowData">
					<td width="28" align="left" valign="middle" bgcolor="#FFFFFF">6.2.1.1</td>
					<td align="left" valign="middle" bgcolor="#FFFFFF">Computadora</td>
					<td align="left" valign="middle">unidad</td>
					<td align="center" valign="middle" nowrap="nowrap">1</td>
					<td width="67" align="center" valign="middle">1</td>
					<td width="62" align="center" valign="middle">0</td>
					<td width="51" align="center" valign="middle">1</td>
					<td width="44" align="center" valign="middle"></td>
                    <td width="77" align="center" valign="middle">0</td>
					<td width="63" align="center" valign="middle">1</td>													
				</tr>
  </tbody>
				</table>
			</div>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table class="TableGrid" cellspacing="1" cellpadding="0" border="0" align="center" width="560" style="border:none;">
				<tbody>
					<tr>
						<td>						
						<ul>
						<?php 
							$rs = $objML->ListaTodasSubActividadesParaReporte($idProy, $idVersion, $idAnio);							
							while ($rowAct = mysql_fetch_assoc($rs)) { ?>
							<li style="padding-bottom: 10px; text-align: justify;">
								Sub actividad
								<?php echo $rowAct['comp'].'.'.
											$rowAct['act'].'.'.
											$rowAct['subact'].'. '.
											$rowAct['descripcion'];										 
								?>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer
							</li>
							<?php } ?>
						</ul>
						</td>
					</tr>
				</tbody>
			</table>	
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid" style="border:none;">
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td height="23" align="left" valign="middle"><b>3.3. Cronograma de actividades:</b></td>
				</tr>
			</table>
            <table border="0" cellpadding="0" cellspacing="0" width="650">
				<thead >
					<tr>
						<td  bgcolor="#ccffcc" height="28" colspan="2" align="center" valign="middle"><b>Año</b></td>
						<td bgcolor="#ccffcc" rowspan="2" align="center" valign="middle"><b>Unidad de Medida</b></td>
						<td bgcolor="#ccffcc" width="41" rowspan="2" align="center" valign="middle"><b>Meta Física</b></td>
					    <td bgcolor="#ccffcc" colspan="12" align="center" valign="middle"><b>Año <?php echo($idAnio);?></b></td>
                        <td bgcolor="#ccffcc" rowspan="2" align="center" valign="middle"><b>TOTAL</b></td>
					</tr>
					<tr style="font-size: 10px">
						<td bgcolor="#ccffcc" colspan="2" rowspan="1" align="center" valign="middle"><b>MES</b></td>
                    <?php
                    $arrPProy = NULL;
                    //for ($x = 1; $x <= $anios; $x ++) {
                        $irsPeriodo = $objProy->PeriodosxAnio($idProy, $idAnio);
                        $arrPeriodo = NULL;
                        $cont = 1;
                        while ($r = mysqli_fetch_assoc($irsPeriodo)) {
                            $arrPeriodo[$cont] = $r;
                            $arrPProy[((12 * $idAnio) - 12) + $cont] = $r;
                            $cont ++;
                        }
                        $irsPeriodo->free();
                        $j = 0;
                        while(MESES > $j){
                            $j++;
                        ?>
                        <td align="center" bgcolor="#ccffcc" valign="middle" style="min-width: 20px;"><b><?php echo $j;?></b></td>
                    <?php } 
					//} 
					?>
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
                        <td align="left" nowrap="nowrap" style="word-break: break-all;"><?php echo($rowcomp['t08_cod_comp']);?></td>
                        <td align="left"  style="word-break: break-all;" colspan="<?php echo(MESES  + 4);?>"><?php echo($rowcomp['t08_comp_desc']);?></td>
                    </tr>
                <?php
                    $rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
                    while ($rowact = mysql_fetch_assoc($rsAct)) {
                ?>
                    <tr class="RowData" style="background-color: #EEF8AD;">
                        <td align="left" nowrap="nowrap" style="word-break: break-all;"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act']);?></td>
                        <td align="left"  style="word-break: break-all;" colspan="<?php echo(MESES + 4);?>"><?php echo($rowact['t09_act']);?></td>
                    </tr>
                <?php
                    $iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
                    while ($rowInd = mysql_fetch_assoc($iRs)) {
                ?>
                    <tr class="RowData" style="background-color: #EEF8FA;">
						<td align="left" nowrap="nowrap"></td>
						<td align="left"  style="word-break: break-all;"><?php echo($rowInd['t09_ind']);?></td>
						<td align="center"  style="word-break: break-all;"><?php echo($rowInd['t09_um']);?></td>
						<td align="center" valign="middle" style="word-break: break-all; background-color: #eeeeee;"><?php echo(number_format($rowInd['t09_mta'],0));?></td>
						<?php
                            /*$i = 0;
                            while ($anios > $i) {
                                $i++; */
                                $j = 0;
                                $lista = $objML->getProgramaIndicador($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act'], $rowInd['t09_cod_act_ind'], $idAnio);
                                while(MESES > $j) {
                                    $j++;
                                    echo '<td align="center" valign="middle" style="word-break: break-all;">'.(array_key_exists($j, $lista)?$lista[$j]:'').'</td>';
                                }
                            //} ?>
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
						<?php  for ( $i=1; $i<=(12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>
					</tr>
					<tr class="RowData" style="background-color: #EEF8AD;">
						<td nowrap="nowrap">10.1.12</td>
						<td>Remuneraciones</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php  for ( $i=1; $i<=(12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>
					</tr>
					<tr class="RowData">					
	  <?php
$per = $objPOA->getListaPersonalParaCronogramaActividades($idProy, $idVersion, $idAnio);
$c = '';
$totalAnio = 0;
$tot = 0;
while ($p = mysql_fetch_array($per)) {
	 ?>
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
				<td><?php echo $p['t03_tot_anio'];?></td>
		<?php 
}
?>
	  <tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.2</td>
						<td>Equipamiento del Proyecto</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php  for ( $i=1; $i<=(12); $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>
					</tr>
					<tr class="RowData" style="background-color: #EEF8AD;">
						<td nowrap="nowrap">10.2.3</td>
						<td>Equipos y Bienes Duraderos</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php  for ( $i=1; $i<=(12); $i++ ) { ?>
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
				<?php for($m = 1; $m <= 12; $m++){ ?>
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
						<?php  for ( $i=1; $i<=(12); $i++ ) { ?>
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
	$array_mp[$e['descrip']]['cod'] = $e['cod_ext'];
    $array_mp[$e['descrip']]['um'] = $e['t03_um'];
    $array_mp[$e['descrip']]['total'] = $array_mp[$e['descrip']]['total'] + $e['meta'];
    $array_mp[$e['descrip']][($e['t03_anio'] - 1) * 12 + $e['t03_mes']] = $e['meta'];   
}
foreach ($array_mp as $index => $item) {
    ?>
	<tr style="background-color: #EEF8AD;" class="RowData">
						<td><?php echo "10.3.".$item['cod']; ?></td>
						<td><?php echo $index; ?></td>
						<td><?php echo $item['um']; ?> </td>
						<td><?php echo $item['total']; ?> </td>
	  <?php for($i=1;$i <= 12; $i++) { ?>
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
						<?php  for ( $i=1; $i<=12; $i++ ) { ?>
						<td align="right">&nbsp;</td>						
						<?php } ?>
						<td align="right">&nbsp;</td>
					</tr>
					<tr class="RowData" style="background-color: #D7DC78;">
						<td nowrap="nowrap">10.6</td>
						<td>Imprevistos</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<?php  for ( $i=1; $i<=12; $i++ ) { ?>
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
        for ($x = 2; $x <= 12; $x ++) {
            echo $MesesBlank;
        }
        ?>
                    </tr>
                </tbody>
            </table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table cellspacing="1" cellpadding="0" border="0" align="center" width="650" style="border:none;" class="TableGrid">
				<tbody><tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td align="left" width="751" valign="middle" height="23"><b>3.4. Presupuesto para el año por partidas:</b></td>
				</tr>
			</tbody></table>
			<table width="650" border="0" cellpadding="0" cellspacing="0">
				<thead>
  <?php
$campos = $objMP->iGetArrayFields($rsPresup);
$arrfuentes = NULL;
for ($x = 9; $x < count($campos); $x ++) {
    $arrfuentes[$x - 9][0] = $campos[$x]; // --> Para los Nombres de Fuentes
    $arrfuentes[$x - 9][1] = 0; // --> Para los Totales
}
$sumaTotal = 0;
$colspan = count($arrfuentes) + 1; // 1=columna de Total
?>
  		<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
						<td colspan="2" rowspan="2" align="center" valign="middle">COMPONENTE / PRODUCTO / ACTIVIDAD</td>
						<td width="196" rowspan="2" align="center" valign="middle">Unidad de Medida</td>
						<td width="147" rowspan="2" align="center" valign="middle">Costo Total</td>
						<td colspan="<?php echo($colspan);?>" align="center">Fuentes de finaciamiento</td>
		</tr>
		<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
    <?php
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo ('<td width="103" align="center" style=" word-break: break-all;">' . $arrfuentes[$x][0] . '</td>');
    }
    ?>
    <td width="110" align="center">Aporte de Beneficiarios</td>
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
    if (trim($row['descripcion']) == 'Manejo del Proyecto') { ?>
		</table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table width="650" border="0" cellpadding="0" cellspacing="0">
				<thead>
  					<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
						<td colspan="2" rowspan="2" align="center" valign="middle">COMPONENTE / PRODUCTO / ACTIVIDAD</td>
						<td width="196" rowspan="2" align="center" valign="middle">Unidad de Medida</td>
						<td width="147" rowspan="2" align="center" valign="middle">Costo Total</td>
						<td colspan="<?php echo($colspan);?>" align="center">Fuentes de finaciamiento</td>
					</tr>
					<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
				    <?php
				    for ($x = 0; $x < count($arrfuentes); $x ++) {
				        echo ('<td width="103" align="center" style=" word-break: break-all;">' . $arrfuentes[$x][0] . '</td>');
				    }
				    ?>
    					<td width="110" align="center">Aporte de Beneficiarios</td>
					</tr>
				</thead>
				<tbody class="data">
	<?php }
    ?>
   <tr style="background-color: <?php if ($tipo == 'componente')  { echo '#ffff99'; } else { if ($tipo=='subactividad') { echo '#ffcc99'; } else { echo '#ccffff'; }}?>;">
						<td width="48" align="left" valign="middle" ><?php echo($row['codigo']);?></td>
						<td width="412" align="left" valign="middle"><?php echo($row['descripcion']);?></td>
						<td align="center" valign="middle" style="<?php if ($tipo == 'componente')  { echo 'background-color:#ffffcc'; }?>" ><?php echo($row['um']);?></td>
						<td align="right" valign="middle" style="<?php if ($tipo == 'componente')  { echo 'background-color:#ffffcc'; }?>"><?php echo( number_format($row['total'],2));?></td>
    <?php
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo '<td width="103" align="right" ';
        if ($tipo == 'componente')  { echo 'style="background-color:#ffffcc;"'; }
        echo	'  style=" word-break: break-all;">' . number_format($row[$arrfuentes[$x][0]], 2) . '</td>';
        $total_fila += $row[$arrfuentes[$x][0]];
    }
    ?>
    <td align="right" style="<?php if ($tipo == 'componente')  { echo 'background-color:#ffffcc'; } ?>"><font
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
					<tr style="color: #333; height: 20px; background-color: #ffff99;">
						<th colspan="3" align="center" valign="middle"  style="padding-right: 3px; border-right: 1px solid black;">TOTAL COSTOS DIRECTOS DEL PROYECTO</th>						
						<th align="right" valign="middle"  style="padding-right: 3px; border-right: 1px solid black;"><?php echo( number_format($sumaTotal,2));?></th>
      <?php
    $total_fila = 0;
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo ('<th align="right"  style="border-right: 1px solid black; padding-right: 3px;">' . number_format($arrfuentes[$x][1], 2) . '</th>');
        $total_fila += $arrfuentes[$x][1];
    }
    ?>
      <th align="right" valign="middle" style="padding-right: 3px;"><?php echo( number_format($total_fila,2));?></th>
					</tr>
				</tfoot>
			</table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table width="650" border="0" cellpadding="0" cellspacing="0">
				<thead>
  					<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
						<td colspan="2" rowspan="2" align="center" valign="middle">COMPONENTE / PRODUCTO / ACTIVIDAD</td>
						<td width="196" rowspan="2" align="center" valign="middle">Unidad de Medida</td>
						<td width="147" rowspan="2" align="center" valign="middle">Costo Total</td>
						<td colspan="<?php echo($colspan);?>" align="center">Fuentes de finaciamiento</td>
					</tr>
					<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
				    <?php
				    for ($x = 0; $x < count($arrfuentes); $x ++) {
				        echo ('<td width="103" align="center">' . $arrfuentes[$x][0] . '</td>');
				    }
				    ?>
    					<td width="110" align="center">Aporte de Beneficiarios</td>
					</tr>
				</thead>
				<tbody class="data">
					<tr style="background-color: #ffcc99;">
						<td align="left" width="48" valign="middle">10.4</td>
						<td align="left" width="412" valign="middle">GASTOS ADMINISTRATIVOS PARA TODO EL PROYECTO</td>
						<td align="center" valign="middle" style=""></td>
						<td align="right" valign="middle" style="">162,323.92</td>
    					<td align="right" width="103">36,044.89</td>
    					<td align="right" width="103">120,723.56</td>
    					<td align="right" width="103">0.00</td>
    					<td align="right" width="103">5,555.47</td>    
    					<td align="right" style=""><font>162,323.92</font></td>
					</tr>
					<tr style="background-color: #ffcc99;">
						<td align="left" width="48" valign="middle">10.5</td>
						<td align="left" width="412" valign="middle">Línea de Base y Evaluación de Impacto</td>
						<td align="center" valign="middle" style=""></td>
						<td align="right" valign="middle" style="">26,056.55</td>
						<td align="right" width="103">26,056.55</td>
						<td align="right" width="103">0.00</td>
						<td align="right" width="103">0.00</td>
						<td align="right" width="103">0.00</td>    
						<td align="right" style=""><font>26,056.55    </font></td>
					</tr>
					<tr style="background-color: #ffcc99;">
						<td align="left" width="48" valign="middle">10.6</td>
						<td align="left" width="412" valign="middle">Imprevistos</td>
						<td align="center" valign="middle" style=""></td>
						<td align="right" valign="middle" style="">8,685.52</td>
    					<td align="right" width="103">8,685.52</td>
    					<td align="right" width="103">0.00</td>
    					<td align="right" width="103">0.00</td>
    					<td align="right" width="103">0.00</td>    
    					<td align="right" style=""><font> 8,685.52</font></td>
					</tr>										
				</tbody>
				<tfoot>
					<tr style="color: #000000; height: 20px; background-color: #ffff99;">
						<th align="center" valign="middle" style="padding-right: 3px; border-right: 1px solid black;" colspan="3">TOTAL COSTOS INDIRECTOS DEL PROYECTO</th>						
						<th align="right" valign="middle" style="padding-right: 3px; border-right: 1px solid black;">2,338,556.07</th>
      					<th align="right" style="border-right: 1px solid black; padding-right: 3px;">583,836.35</th>
      					<th align="right" style="border-right: 1px solid black; padding-right: 3px;">1,595,864.24</th>
      					<th align="right" style="border-right: 1px solid black; padding-right: 3px;">0.00</th>
      					<th align="right" style="border-right: 1px solid black; padding-right: 3px;">158,855.47</th>      
      					<th align="right" valign="middle" style="padding-right: 3px;">2,338,556.05</th>
					</tr>				
				</tfoot>
		</table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table width="650" border="0" cellpadding="0" cellspacing="0"> 		 
		 	<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
					<th align="center" valign="middle" style="padding-right: 3px; border-right: 1px solid black;" colspan="3">TOTAL COSTO TOTAL DEL PROYECTO</th>						
					<th align="right" valign="middle" style="padding-right: 3px; border-right: 1px solid black;">2,338,556.07</th>
      				<th align="right" style="border-right: 1px solid black; padding-right: 3px;">583,836.35</th>
      				<th align="right" style="border-right: 1px solid black; padding-right: 3px;">1,595,864.24</th>
      				<th align="right" style="border-right: 1px solid black; padding-right: 3px;">0.00</th>
      				<th align="right" style="border-right: 1px solid black; padding-right: 3px;">158,855.47</th>      
      				<th align="right" valign="middle" style="padding-right: 3px;">2,338,556.05</th>
				</tr>
		 </table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		<table width="650" border="0" cellpadding="0" cellspacing="0" style="border:none;"> 		 
		 	<tr style="background: #ffffff; color: #000000; ">
				<td align="left" valign="middle" width="250">Fondoempleo</td>						
				<td align="left" valign="middle" width="30">S/.</td>
				<td align="right" valign="middle" width="115">570 873,16</td>
				<td align="left" valign="middle" width="460" colspan="4">&nbsp;</td>				
			</tr>
			<tr style="background: #ffffff; color: #000000; ">
				<td align="left" valign="middle" width="250">Contrapartida Southern Perú</td>						
				<td align="left" valign="middle" width="30">S/.</td>
				<td align="right" valign="middle" width="115">9 880,60</td>
				<td align="left" valign="middle" width="460" colspan="4">&nbsp;</td>				
			</tr>
			<tr style="background: #ffffff; color: #000000;  ">
				<td align="left" valign="middle" width="250">Aporte Beneficiarios</td>						
				<td align="left" valign="middle" width="30">S/.</td>
				<td align="right" valign="middle" width="115">1 590 675,12</td>
				<td align="left" valign="middle" width="460" colspan="4">&nbsp;</td>				
			</tr>
			<tr style="background: #ffffff; color: #000000; font-weight: bold;">
				<td align="left" valign="middle" width="250" style="border-top:1px solid black;">Total</td>						
				<td align="left" valign="middle" width="30" style="border-top:1px solid black;">S/.</td>
				<td align="right" valign="middle" width="115" style="border-top:1px solid black;">2 171 428,88</td>
				<td align="left" valign="middle" width="460" colspan="4">&nbsp;</td>				
			</tr>
		 </table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table cellspacing="1" cellpadding="0" border="0" align="center" width="650" style="border:none;" class="TableGrid">
				<tbody><tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable">
					<td align="left" width="751" valign="middle" height="23"><b>3.5. Productos a supervisar en cada Entregable:</b></td>
				</tr>
			</tbody></table>
			<table width="650" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<?php
						$entregables = $objML->listarEntregablesReporte($idProy, $idVersion);
						$duracion = $objML->obtenerDuracion($idProy, $idVersion);
						$objPOA = new BLPOA();
						$colsAdicional = 0;
						$totalCols = count($entregables[$idAnio]) + MESES;
                	?>
					<tr class="rpt-head">
    					<td width="10" rowspan="2">Cod</td>
    					<td width="225" rowspan="2">Componente / Producto / Indicador</td>
    					<td rowspan="2">UM</td>
    					<td rowspan="2">Meta</td>
    					<td height="26" colspan="<?php echo($totalCols);?>">Año <?php echo($idAnio);?></td>
    				</tr>
    				<tr class="center">
                        <?php
                            $j = 0;
                            while(MESES > $j){
                                $j++;
                        ?>
    					<td><?php echo($j);?></td>
    					<?php
    					        if (isset($entregables[$idAnio][$j])){
                        ?>
                        <td rowspan="2" class="row-entregable">E</td>
                        <?php } } ?>
    				</tr>	
				</thead>
				<tbody class="data">
					<?php
                        $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
                        while ($rComp = mysql_fetch_assoc($rsComp)) {
                        	$idComp = $rComp['t08_cod_comp'];
							$progIndicadores = $objPOA->listarProgramacionIndicadores($idProy, $idVersion, $idComp);
                	?>
				        <tr style="background: #ccffcc; color: #000000;">
							<td class="left"><?php echo $rComp['t08_cod_comp'];?></td>
							<td colspan="<?php echo($totalCols + 3);?>" width="50" class="left"><?php echo $rComp['t08_comp_desc'];?></td>
						</tr>
						<?php
	                    $aRs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
	                    if (mysql_num_rows($aRs) > 0) {
	                        while ($ract = mysql_fetch_assoc($aRs)) {
	                            $idAct = $ract["t09_cod_act"];
	                    ?>
	                  	<tr class="row-producto">
							<td class="left"><?php echo($ract["t08_cod_comp"].'.'.$ract["t09_cod_act"]);?></td>
							<td colspan="<?php echo($totalCols + 3);?>" class="left"><?php echo($ract["t09_act"]);?></td>
						</tr>
						<?php
                        $iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $idComp, $idAct);
                        while ($row = mysql_fetch_assoc($iRs)) {
                            $idInd = $row["t09_cod_act_ind"];
	                    ?>
	                    <tr>
							<td align="left"><?php echo($row["t08_cod_comp"].'.'.$row["t09_cod_act"].'.'.$row["t09_cod_act_ind"]);?></td>
							<td align="left"><?php echo($row["t09_ind"]);?></td>
							<td class="col-um"><?php echo($row["t09_um"]);?></td>
							<td class="col-meta">
			                    <?php echo(number_format($row["t09_mta"]));?>
							</td>
							<?php
	                                $j = 0;
	                                $lista = $objML->getProgramaIndicador($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_act_ind"], $idAnio);
	                                while(MESES > $j){
	                                    $j++;
	                                ?>
	                                    <td class="col-meta"><?php echo((array_key_exists($j, $lista)?$lista[$j]:''));?></td>
	                                    <?php
	                                    if (isset($entregables[$idAnio][$j])){
	                                        ?>
	                                    <td class="row-entregable col-meta">
                                            <?php echo($progIndicadores[$idAct][$row["t09_cod_act_ind"]][$idAnio][$j]);?>
	                                    </td>
	                                    <?php
	                                    }
	                                }
	                            } ?>
						</tr>
						<?php
						 }   }
	                    ?>
					<?php
					    }
                    ?>
				</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table width="650" border="0" cellpadding="0" cellspacing="0" style="font-size:10px;">
				<tbody class="data">
					<tr style="background: #ccffcc; color: #000000; font-weight: bold;">
						<td rowspan="2"  valign="middle" align="center" colspan="2">Concepto</td>
						<td colspan="12" style="background: #ffffcc;"  valign="middle" align="center" width="450">AÑO <?php echo $idAnio;?></td>
					</tr>
					<tr class="center bold" style="background: #ffffcc;">						
						<?php
                            $j = 0;
                            while(MESES > $j){
                                $j++;
                        ?>
    					<td><?php echo($j);?></td>
    					<?php
						    }
	                    ?>
					</tr>
					<tr class="center bold" style="background: #ffffcc; color: #000000;">
						<td bgcolor="#ffffff" colspan="2">
							Productos a Supervisar
						</td>
						<?php
							$resPresup = $objInf->getResumenPresupuestal($idProy, $idVersion, $idAnio);
                            while($rp = mysqli_fetch_assoc($resPresup)){
                            	$j = 0;
                            	?>
                            	<td class="row-entregable"><?php echo $rp['nro_productos'];?></td>
                            	<?php
                            	while($j < ($rp['duracion'] - 1)){
										$j++;                            		
                            		?>
                        			<td>&nbsp;</td>
                    			<?php
                            	}
                        	}
			      		?>
					</tr>
					<tr class="center bold" style="background: #ffffcc; color: #000000;">
						<td bgcolor="#ffffff" colspan="2">
							Entregables a Supervisar
						</td>
						<?php 
						$resPresup = $objInf->getResumenPresupuestal($idProy, $idVersion, $idAnio);
						while($rp = mysqli_fetch_assoc($resPresup)){
                            	$j = 0;
                            	?>
                            	<td bgcolor="#e26b0a"><?php echo $rp['nro_entregable'];?></td>
                            	<?php
                            	while($j < ($rp['duracion'] - 1)){
                            		$j++;
                            		?>
                        			<td>&nbsp;</td>
                    			<?php
                            	}
                        	}
                    	?>
					</tr>
					<tr style="background: #ffffff; color: #000000; font-weight: bold;">
						<td bgcolor="#ffffff"  valign="middle" align="center" colspan="2">
							Proyeccion de gasto Mensual sin la linea de base
						</td>
						<?php 
						$listaGastos = $objInf->getListaGastosPlaneadosMensuales($idProy, $idVersion, $idAnio);
						while($gt = mysql_fetch_assoc($listaGastos)){
                    	?>
                        	<td class="center bold"><?php echo number_format($gt['planeado'], 2);?></td>
                    	<?php
                    	}
                    	?>
					</tr>
					<tr style="background: #ffffff; color: #000000; font-weight: bold;">
						<td bgcolor="#ffffff" valign="middle" align="center" colspan="2">
							<b>Monto a Desembolsar</b>
						</td>
						<?php 
						$resPresup = $objInf->getResumenPresupuestal($idProy, $idVersion, $idAnio);
						while($rp = mysqli_fetch_assoc($resPresup)){
                            	$j = 0;
                            	?>
                            	<td class="center bold"><?php echo number_format($rp['desembolso_planeado'], 2);?></td>
                            	<?php
                            	while($j < ($rp['duracion'] - 1)){
                            		$j++;
                            		?>
                        			<td>&nbsp;</td>
                    			<?php
                            	}
                        	}
                    	?>
					</tr>
					<tr style="background: #ffffff; color: #000000; font-weight: bold;">
						<td bgcolor="#ffffff" colspan="2" class="center">
							Entregable Nro
						</td>
						<?php 
						$resPresup = $objInf->getResumenPresupuestal($idProy, $idVersion, $idAnio, $idMes);
						while($rp = mysqli_fetch_assoc($resPresup)){
                            	$j = 0;
                            	?>
                            	<?php
                            	while($j < ($rp['duracion'])){
                            		$j++;
                            		?>
                    			<?php
                            	}
                            	?>
                            	<td colspan="<?php echo $j;?>" bgcolor="#b7dee8" class="center bold"><?php echo $rp['nro_entregable'];?></td>
                            	<?php
                        	}
                    	?>
					</tr>
					<tr style="background: #ffffff; color: #000000; font-weight: bold;">
						<td rowspan="2" bgcolor="#ffffff" valign="middle" align="center">Tipo de Desembolso</td>
						<td bgcolor="#ffffff" valign="middle" align="center">
							Adelanto
						</td>
						<?php
							$adelantos = $objInf->getListaAdelantosAnio($idProy, $idVersion, $idAnio);
							while($ad = mysql_fetch_assoc($adelantos)){
								$j=0;
                            	while($j < ($ad['duracion'])){
                            		$j++;
                            		if(($j == 3 && $ad['nro_entregable'] == 1) OR ($j == 2 && $ad['nro_entregable'] != 1)){
                            			error_log("\n alditis:  j: ".$j, 3, '/var/www/log.log');
                            			error_log("\n alditis:  n: ".$ad['nro_entregable'], 3, '/var/www/log.log');
                            		?>
                            			<td class="center bold"><?php echo number_format($ad['adelanto'], 2);?></td>
                            		<?php
                            		}
                            		else{
                        			?>
                            			<td></td>
                            		<?php	
                            		}
                        		}
                        	}
						?>
					</tr>
					<tr style="background: #ffffff; color: #000000; font-weight: bold;">
						<td bgcolor="#ffffff" valign="middle" align="center">
							Saldo
						</td>
						<?php
							$saldos = $objInf->getListaSaldosAnio($idProy, $idVersion, $idAnio);
							while($sd = mysql_fetch_assoc($saldos)){
								$j=0;
                            	while($j < ($sd['duracion'])){
                            		$j++;
                            		if($j == 1){
                            		?>
                            			<td class="center bold"><?php echo number_format($sd['saldo'], 2);?></td>
                            		<?php
                            		}
                            		else{
                        			?>
                            			<td></td>
                            		<?php	
                            		}
                        		}
                        	}
						?>
					</tr>
				</body>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table cellspacing="1" cellpadding="0" border="0" align="center" width="650" style="border:none;" class="TableGrid">
				<tbody>
					<tr class="SubtitleTable">
						<td align="left" width="751" valign="middle" height="23"><b>3.6. Cronograma de desembolsos por Entregables:</b></td>
					</tr>
				</tbody>
			</table>
			<table cellspacing="0" cellpadding="0" border="0" align="center" width="650">
				<thead>
					<tr class="cab">
						<td>Desembolso del período</td>
						<td>Entregables</td>
						<td>Mes</td>
						<td>Monto a desembolsar</td>
					</tr>
				</thead>
				<tbody class="data">
				<?php
				$lista = $objPOA->getCronogramaDesembolsosPorEntregables($idProy, $idVersion, $idAnio);
				while ($l = mysql_fetch_array($lista)) {
				?>
					<tr class="center">
						<td>Adelanto</td>
						<td><?php echo ($l['entregable']);?></td>
						<td><?php echo ($l['periodo_adelanto']);?></td>
						<td class="right">S/. <?php echo (number_format($l['adelanto'], 2));?></td>
					</tr>
					<tr class="center">
						<td>Saldo</td>
						<td><?php echo ($l['entregable']);?></td>
						<td><?php echo ($l['periodo_saldo']);?></td>
						<td class="right">S/. <?php echo (number_format($l['saldo'], 2));?></td>
					</tr>
				<?php 
				}
				?>
				</tbody>
			</table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>			
			<table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>