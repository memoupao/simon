<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant('PATH_CLASS') . "BLBene.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');
$idTrim = $objFunc->__Request('idTrim');
$idVs = $objFunc->__Request('idVersion');

$objProy = new BLProyecto();
$idVersion = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVersion);

$objInf = new BLInformes();
$rowInf = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idTrim, $idVs);

$objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVs);
// $objRep = NULL ;

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
<div id="divBodyAjax" class="TableGrid">
			<!-- InstanceBeginEditable name="BodyAjax" -->
			<!--<table width="99%" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid">
    <tr>
      <th width="18%" align="left">CODIGO DEL PROYECTO</th>
      <td width="55%" align="left"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
      <th width="7%" align="left" nowrap="nowrap">INICIO</th>
      <td width="20%" align="left"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?></td>
    </tr>
    <tr>
      <th align="left" nowrap="nowrap">TITULO DEL PROYECTO</th>
      <td align="left"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
      <th align="left" nowrap="nowrap">TERMINO</th>
      <td align="left"><?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
    </tr>
    <tr>
      <th align="left">&nbsp;</th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th align="left">&nbsp;</th>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>-->


<h3>1. INFORMACIÓN GENERAL.- (Sólo completar los cuadros)</h3>
<h4>Datos del Proyecto:</h4>

			<table width="700" cellpadding="0" cellspacing="0">
				<thead>

				</thead>

				<tbody class="data" bgcolor="#FFFFFF">

					<tr>
						<td width="27%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Numero del Informe</strong></td>
						<td align="left" valign="middle"><?php echo($idVs);?> &nbsp;</td>
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Código
								del Proyecto</strong></td>
						<td width="31%" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Periodo
								Referencia</strong></td>
						<td colspan="3" align="left" valign="middle"><strong>Año <?php echo($idAnio);?> - Trimestre <?php echo($idTrim);?> <br />(<?php echo($rowInf['t25_periodo']);?>)</strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Presentación</strong></td>
						<td colspan="3" align="left" valign="middle"><strong><?php echo($rowInf['t25_fch_pre']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#FFFFAA"><strong>Supervisor
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['jefe_proy']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Temático</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_tema']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Financiero</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_fina']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Externo</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_exte']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Nombre
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Institución
								Ejecutora</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t01_sig_inst'].chr(13).$row['t01_nom_inst']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Fundación</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['t01_fch_fund']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Presupuesto
								Promedio Anual de la Institución Ejecutora</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['presup_prom_anual'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Instituciones
								Colaboradores o asociadas</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['inst_colabora']);?></td>
					</tr>

					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Objetivos
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fin']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Propósito
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_pro']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Población
								Beneficiaria</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
					</tr>
      <?php
    $objProy = new BLProyecto();
    $rsAmbito = $objProy->AmbitoGeo_Listado($idProy, $idVersion);
    $rowspan = mysqli_num_rows($rsAmbito);
    ?>
    <tr style="font-size: 11px;">
						<td rowspan="<?php echo($rowspan+2);?>" align="left"
							valign="middle" bgcolor="#E8E8E8"><strong>ámbito de Ejecución
								del Proyecto</strong></td>
						<td colspan="3" align="center" valign="middle"
							style="display: none;">&nbsp;</td>
					</tr>

					<tr style="font-size: 11px;">
						<td width="27%" height="23" align="center" valign="middle"
							bgcolor="#E8E8E8"><strong>Departamento</strong></td>
						<td width="23%" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Provincia</strong></td>
						<td width="19%" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Distrito</strong></td>
					</tr>
        <?php while($r = mysqli_fetch_assoc($rsAmbito))  { ?>
        <tr style="font-size: 11px;">
						<td height="25" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dpto']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $r['prov']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dist']);?></td>
					</tr>
    	<?php
        }
        $rsAmbito->free();
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Duración
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['duracion']." Años");?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Presupuesto
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['t02_pres_tot'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Monto
								Solicitado a Fondoempleo</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['t02_pres_fe'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Aportes
								de Contrapartida</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['aportes_contra'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle">&nbsp;</td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fuentes
								de Financiamiento</strong></td>
					</tr>
    <?php
    // $rsFuentes = $objRep->RepFichaProy_Fuentes($idProy, $idVersion);
    $rsFuentes = $objRep->RepFichaProy_Fuentes($idProy, 1);
    while ($rfte = mysqli_fetch_assoc($rsFuentes)) {
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><?php echo($rfte['t01_sig_inst']);?></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($rfte['monto'],2));?>
      </div>
						</td>
					</tr>
	<?php
    }
    $rsFuentes->free();
    ?>

    <tr style="font-size: 11px;">
						<th colspan="4" align="left" valign="top">&nbsp;</th>
					</tr>


				</tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="3" align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />



			<table width="800" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">2. AVANCE DE PROPóSITO</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td width="19%" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">
      <?php
    if (is_int(($RowIndex + 1) / 4)) {
        ?>
      <table width="780" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $iRs = $objInf->ListaIndicadoresProposito($idProy, $idAnio, $idTrim);
        $RowIndex = 0;
        if ($iRs->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
          <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="412" align="left" valign="middle"><strong>Indicador
											de Proposito</strong></td>
									<td height="15" colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
								</tr>
								<tr>
									<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t07_cod_prop_ind']." ".$row['indicador']);?>
              <input name="t07_cod_prop_ind[]" type="hidden"
										id="t07_cod_prop_ind[]"
										value="<?php echo($row['t07_cod_prop_ind']);?>" /> <br /> <span><strong
											style="color: red;">Unidad Medida</strong>: <?php echo( $row['t07_um']);?></span></td>
									<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="55" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
								</tr>
								<tr>
									<td align="center"><?php echo( $row['plan_mtatotal']);?></td>
									<td align="center"><?php echo( $row['plan_mtaacum']);?></td>
									<td align="center"><?php echo( $row['plan_mtatrim']);?></td>
									<td align="center"><?php echo( $row['ejec_mtaacum']);?></td>
									<td align="center"><?php echo($row['ejec_mtatrim']);?></td>
									<td align="center"><?php echo($row['ejec_mtatotal']);?></td>
								</tr>
								<tr style="font-weight: 300; color: navy;">
									<td colspan="7" align="left">DESCRIPCION <br />
              <?php echo(nl2br($row['descripcion']));?>
              <br /> LOGROS <br />
              <?php echo(nl2br($row['logros']));?>
              <br /> DIFICULTADES <br />
              <?php echo(nl2br($row['dificultades']));?></td>
								</tr>
          <?php
                $RowIndex ++;
            }
            $iRs->free();
        } // Fin de SubActividades
        
        ?>
        </tbody>
							<tfoot>
							</tfoot>
						</table>
      <?php
    }
    ?>
      </td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">3. AVANCE DE COMPONENTES</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">
						<table width="780" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $rsc = $objInf->ListaComponentes($idProy);
        
        while ($row_comp = mysqli_fetch_assoc($rsc)) {
            ?>
          <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td height="15" colspan="7" align="left" valign="middle"
										style="background-color: #FF6;"><strong>COMPONENTE: </strong><?php echo($row_comp['t08_cod_comp'].'.-'.$row_comp['t08_comp_desc']);?></td>
								</tr>
        <?php
            $iRs = $objInf->ListaIndicadoresComponente($idProy, $row_comp['t08_cod_comp'], $idAnio, $idTrim);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
          <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="412" align="left" valign="middle"><strong>Indicador
											de Componente</strong></td>
									<td height="15" colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
								</tr>
								<tr>
									<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp_ind'].".- ".$row['indicador']);?>
              <input name="t08_cod_comp_ind[]" type="hidden"
										id="t08_cod_comp_ind[]"
										value="<?php echo($row['t08_cod_comp_ind']);?>" /> <br /> <span><strong
											style="color: red;">Unidad Medida</strong>: <?php echo( $row['t08_um']);?></span></td>
									<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="55" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
								</tr>
								<tr>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatrim']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatrim']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
								</tr>
								<tr style="font-weight: 300; color: navy;">
									<td colspan="7" align="left">DESCRIPCION <br />
              <?php echo(nl2br($row['descripcion']));?>
              <br /> LOGROS <br />
              <?php echo(nl2br($row['logros']));?>
              <br /> DIFICULTADES <br />
              <?php echo(nl2br($row['dificultades']));?>
			  <br /> OBSERVACIONES <br />
              <?php echo(nl2br($row['observaciones']));?></td>
								</tr>
          <?php
                    $RowIndex ++;
                }
                $iRs->free();
            } // Fin de SubActividades
        }
        
        ?>
        </tbody>
							<tfoot>
							</tfoot>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td align="left" valign="middle"><strong style="color: blue;">4.
							AVANCE DE ACTIVIDADES</strong></td>
					<td align="left" valign="middle" bgcolor="#CCCCFF">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><div
							id="divTableLista">
							<table width="856" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
            <?php
            // $objInf = new BLInformes();
            $rs = $objInf->ListaActividades($idProy);
            while ($row_act = mysqli_fetch_assoc($rs)) {
                
                $iRs = $objInf->ListaIndicadoresActividadTrim($idProy, $row_act['codigo'], $idAnio, $idTrim);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        ?>
            <tr
										style="border: solid 1px #CCC; background-color: #eeeeee;">
										<td width="412" align="left" valign="middle"><strong>Indicador
												de Actividad</strong></td>
										<td height="15" colspan="3" align="center" valign="middle"
											bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
										<td colspan="3" align="center" valign="middle"
											bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
									</tr>
									<tr>
										<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']." ".$row['indicador']);?>
                <input name="t09_cod_act_ind[]" type="hidden"
											id="t09_cod_act_ind[]"
											value="<?php echo($row['t09_cod_act_ind']);?>" /> <br /> <span><strong
												style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
										<td width="60" align="center" bgcolor="#CCCCCC"><strong>Meta
												Total </strong></td>
										<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
										<td width="55" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
										<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
										<td width="63" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
										<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									</tr>
									<tr>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtaacum']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtames']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
									</tr>
									<tr style="font-weight: 300; color: navy;">
										<td colspan="7" align="left">DESCRIPCION <br />
                <?php echo(nl2br($row['descripcion']));?>
                <br /> LOGROS <br />
                <?php echo(nl2br($row['logros']));?>
                <br /> DIFICULTADES <br />
                <?php echo(nl2br($row['dificultades']));?>
				<br /> OBSERVACIONES <br />
              <?php echo(nl2br($row['observaciones']));?></td>
									</tr>
            <?php
                        $RowIndex ++;
                    }
                    $iRs->free();
                } // Fin de SubActividades
            }
            
            ?>
          </tbody>
								<tfoot>
								</tfoot>
							</table>
							<input type="hidden" name="t02_cod_proy"
								value="<?php echo($idProy);?>" /> <input type="hidden"
								name="t02_version" value="<?php echo($idVersion);?>" /> <input
								type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
							<input type="hidden" name="t09_cod_act"
								value="<?php echo($idAct);?>" /> <input type="hidden"
								name="t09_ind_anio" value="<?php echo($idAnio);?>" /> <input
								type="hidden" name="t09_ind_trim" value="<?php echo($idTrim);?>" />
							<script language="JavaScript" type="text/javascript">
function Guardar_AvanceIndAct	()
{
<?php $ObjSession->AuthorizedPage(); ?>
//	var BodyForm= serializeDIV('divAvanceActividades');
var BodyForm=$("#FormData").serialize();

if(BodyForm==""){alert("La Actividad Seleccionada, no Tiene indicadores !!!"); return;}

if(confirm("Estas seguro de Guardar el avance de los Indicadores para el Informe ?"))
  {
	var activ = $('#cboactividad_ind').val();
	var anio = $('#cboanio').val();
	var trim = $('#cbotrim').val();
	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_indicadores_actividad'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, indActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
  }
}
function indActSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
 {
 LoadIndicadoresActividad();
 alert(respuesta.replace(ret,""));
 }
else
{alert(respuesta);}

}

function TotalAvanceIndicador(x)
{
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtIndActTot[]") ;
  var xAcum =document.getElementsByName("txtIndActAcum[]");
  var xMes =document.getElementsByName("txtIndActTrim[]") ;

  var mtaacum =parseFloat(xAcum[index].value) ;
  var mtames =parseFloat(xMes[index].value) ;
  if(isNaN(mtaacum)){mtaacum=0;}
  if(isNaN(mtames)){mtames=0;}
  var total=(mtaacum + mtames) ;
  xTotal[index].value = total ;

}
        </script>
						</div></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">5. AVANCE DE SUB ACTIVIDADES</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="856"
							height="181" cellpadding="0" cellspacing="0" class="TableEditReg">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $rs = $objInf->ListaActividades($idProy);
        while ($row_act = mysqli_fetch_assoc($rs)) {
            $iRs = $objInf->ListaSubActividadesTrim($idProy, $row_act['codigo'], $idAnio, $idTrim);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
          <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="412" align="left" valign="middle"><strong>SubActividad</strong></td>
									<td height="15" colspan="4" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
								</tr>
								<tr>
									<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?>
              <input name="t09_cod_sub[]" type="hidden"
										id="t09_cod_sub[]" value="<?php echo($row['t09_cod_sub']);?>" />
										<br /> <span><strong style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
									<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total
											Proyecto</strong></td>
									<td width="60" height="23" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="55" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Trim</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
								</tr>
								<tr>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
									<td height="26" align="center" nowrap="nowrap"><?php echo( $row['plan_mtaanio']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtames'] + $row['ejec_mtaacumtrim']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
								</tr>
								<tr style="font-weight: 300; color: navy;">
									<td height="107" colspan="8" align="left">DESCRIPCION <br />
              <?php echo(nl2br($row['descripcion']));?>
              <br /> LOGROS <br />
              <?php echo(nl2br($row['logros']));?>
              <br /> DIFICULTADES <br />
              <?php echo(nl2br($row['dificultades']));?>
			   <br /> OBSERVACIONES <br />
              <?php echo(nl2br($row['observaciones']));?></td>

								</tr>
          <?php
                    $RowIndex ++;
                }
                $iRs->free();
            } // Fin de SubActividades
        }
        
        ?>
        </tbody>
							<tfoot>
							</tfoot>
						</table> <input type="hidden" name="t02_cod_proy2"
						value="<?php echo($idProy);?>" /> <input type="hidden"
						name="t02_version2" value="<?php echo($idVersion);?>" /> <input
						type="hidden" name="t08_cod_comp2" value="<?php echo($idComp);?>" />
						<input type="hidden" name="t09_cod_act2"
						value="<?php echo($idAct);?>" /> <input name="t09_sub_anio"
						type="hidden" id="t09_sub_anio" value="<?php echo($idAnio);?>" />
						<input name="t09_sub_trim" type="hidden" id="t09_sub_trim"
						value="<?php echo($idTrim);?>" /> <script language="JavaScript"
							type="text/javascript">
function Guardar_AvanceSubAct	()
{
<?php $ObjSession->AuthorizedPage(); ?>


var BodyForm= serializeDIV('divAvanceSubActividades');
if(BodyForm==""){alert("La Actividad Seleccionada, no Tiene SubActividades !!!"); return;}

BodyForm=$("#FormData").serialize();
if(confirm("Estas seguro de Guardar el avance de las SubActividades para el Informe ?"))
  {
	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_sub_actividad'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, SubActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
  }
}
function SubActSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
 {
 LoadSubActividades();
 alert(respuesta.replace(ret,""));
 }
else
{alert(respuesta);}
}

function TotalAvanceSubActividad(x)
{
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtSubActTot[]") ;
  var xAcum =document.getElementsByName("txtSubActAcum[]");
  var xTrim =document.getElementsByName("txtSubActTrim[]") ;

  var mtaacum =parseFloat(xAcum[index].value) ;
  var mtatrim =parseFloat(xTrim[index].value) ;
  if(isNaN(mtaacum)){mtaacum=0;}
  if(isNaN(mtatrim)){mtatrim=0;}
  var total=(mtaacum + mtatrim) ;
  xTotal[index].value = total ;
}
        </script></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="3" align="left" valign="middle"><strong
						style="color: blue;">6. ANáLISIS</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="780"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Resultados</strong></td>
								</tr>

								<tr>
          <?php
        $rowAnalisis = $objInf->ListaAnalisisInfTrim($idProy, $idAnio, $idTrim, $idVs);
        ?>
            <td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t25_resulta']));?></td>
								</tr>

							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="780"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Conclusiones</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t25_conclu']));?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="780"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Limitaciones</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t25_limita']));?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="780"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Factores
											Positivos</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t25_fac_pos']));?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="780"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Perspectivas
											para el Próximo Trimestre</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t25_perspec']));?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">5. ANEXOS</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="750"
							border="0" cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td height="22" colspan="3" align="left" valign="middle"><strong>Archivos
											Adjuntos al Informe Trimestral</strong></td>
								</tr>
								<tr>
			<?php
// $objInf = new BLInformes();
$iRs = $objInf->ListaAnexosFotograficosTrim($idProy, $idAnio, $idTrim, $idVs);
$RowIndex = 0;
if ($iRs->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>

					<?php
        $urlFile = $row['t25_url_file'];
        $filename = $row['t25_nom_file'];
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));
        $path = constant('APP_PATH') . "sme/proyectos/informes/anx_trim";
        $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
        if ($file_extension == 'gif' or $file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'png' or $file_extension == 'bmp') {
            $file_vista = "<img src=../../sme/proyectos/informes/anx_trim/" . $urlFile . " />";
        } else {
            $file_vista = "<a href=" . $href . " title='Descargar Archivo' target='_blank'>" . $row['t25_nom_file'] . "</a>";
        }
        ?>
            <td width="250" height="30" align="center" valign="top"> <?php echo ($file_vista); ?><br>
              <?php echo(nl2br($row['t25_desc_file']));?>
            </td>
            <?php if (is_int(($RowIndex+1)/3)){ echo("</tr><tr>");}?>
          <?php
        $RowIndex ++;
    }
    $iRs->free();
} // Fin de Anexos Fotograficos
?>
        
							
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" align="center" valign="middle">&nbsp; <iframe
											id="ifrmUploadFile" name="ifrmUploadFile"
											style="display: none;"></iframe></td>
								</tr>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr style="font-size: 11px;">
					<td colspan="5" align="left" valign="middle" class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;"><strong>Planes
							Especificos</strong></td>
				</tr>
				<tr style="font-size: 11px;">
	 <?php $tb = 0; ?>
      <td align="left" valign="middle" bgcolor="#E8E8E8">Plan de
						Capacitación</td>
					<td colspan="4" align="left" valign="middle">
						<div style="width: 85px; text-align: right;">
	  <?php
$objBene = new BLBene();

$cap = $objBene->NumeroBeneficiariosCap($idProy, $idAnio, $idTrim);
$numcap = mysqli_fetch_assoc($cap);
echo $numcap['capacitados'];
$tb += $numcap['capacitados'];
?>
		&nbsp;Capacitados
      </div>
					</td>
				</tr>
				<tr style="font-size: 11px;">
					<td align="left" valign="middle" bgcolor="#E8E8E8">Plan de
						Asistencia Tecnica</td>
					<td colspan="4" align="left" valign="middle">
						<div style="width: 85px; text-align: right;">
	  <?php
$cap = $objBene->NumeroBeneficiariosAT($idProy, $idAnio, $idTrim);
$numcap = mysqli_fetch_assoc($cap);
echo $numcap['capacitados'];

$tb += $numcap['capacitados'];
?>
		&nbsp;Capacitados
      </div>
					</td>
				</tr>
				<tr style="font-size: 11px;">
					<td align="left" valign="middle" bgcolor="#E8E8E8">Otros Servicios</td>
					<td colspan="4" align="left" valign="middle">
						<div style="width: 85px; text-align: right;">
	  <?php
$cap = $objBene->NumeroBeneficiariosOtros($idProy, $idAnio, $idTrim);
$numcap = mysqli_fetch_assoc($cap);
echo $numcap['capacitados'];
$tb += $numcap['capacitados'];
?>
		&nbsp;Capacitados
      </div>
					</td>
				</tr>
				<tr style="font-size: 11px;">
					<td align="left" valign="middle" bgcolor="#E8E8E8">Créditos</td>
					<td colspan="4" align="left" valign="middle">
						<div style="width: 85px; text-align: right;">
	  <?php
$cap = $objBene->NumeroBeneficiariosCred($idProy, $idAnio, $idTrim);
$numcap = mysqli_fetch_assoc($cap);
echo $numcap['capacitados'];
$tb += $numcap['capacitados'];
?>
		&nbsp;Capacitados
      </div>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#E8E8E8">Total De
						Capacitados</td>
					<td colspan="3" align="left" valign="middle">
						<div style="width: 85px; text-align: right;"> <?php print $tb; ?> </div>
					</td>
				</tr>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />
			<p>
				<script language="JavaScript" type="text/javascript">
	function ViewML(codigo,version)
	{
		urlReport = "rpt_ml.php";
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?link="+urlReport+"&title=Marco Logico" + urlParams;
		var win =  window.open(urlViewer,"MarcoLogico","");
		win.focus();

	}
	function ViewPOA(codigo,version)
	{
		urlReport = "rpt_poa.php";
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?link="+urlReport+"&title=Plan Operativo" + urlParams;
		var win =  window.open(urlViewer,"PlanOperativo","");
		win.focus();
	}
    </script>
			</p>
<?php

function retComponentes($proy, $vs)
{
    $objML = new BLMarcoLogico();
    $rsComp = $objML->ListadoDefinicionOE($proy, $vs);
    while ($row = mysql_fetch_assoc($rsComp)) {
        echo ($row['t08_cod_comp'] . ". " . $row['t08_comp_desc'] . "<br />");
    }
    $rsComp = NULL;
}

function retPresupFuentesFinanc($proy, $vs)
{
    $objPresup = new BLPresupuesto();
    $rsFte = $objPresup->RepFuentesFinac($proy, $vs);
    $total = 0;
    if ($rsFte->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($rsFte)) {
            echo ("<tr style='font-size:10px;'>");
            echo ("     <td class='ClassText'>" . $row['fuente'] . "</td>");
            // echo(" <td > 0 </td>");
            // echo(" <td > 0 </td>");
            // echo(" <td > 0 </td>");
            echo ("     <td class='ClassText' align='right'>" . number_format($row['total'], 2) . "</td>");
            echo ("</tr>");
            $total += $row['total'];
        }
        $rsFte->free();
        echo ("<tfoot><tr style='font-size:10px;'>");
        echo ("     <td class='ClassText'> Total </td>");
        
        echo ("     <td class='ClassText' align='right'>" . number_format($total, 2) . "</td>");
        echo ("</tr></tfoot>");
    }
}

function retInformeMensual($proy, $fecini)
{
    $objInf = new BLInformes();
    $rsInf = $objInf->InformeMensualListado($proy);
    while ($row = mysqli_fetch_assoc($rsInf)) {
        // echo(print_r($row));
        $linkInforme = "<a href='#' target='_blank'>" . $row['fec_pre'] . "</a>";
        echo ('<tr class="ClassText">');
        echo ('	<td width="18" align="center">' . $row['nummes'] . '</td>');
        echo ('	<td width="68" align="center">' . $row['fec_plan'] . '</td>');
        echo ('	<td width="39" align="center">' . $linkInforme . '</td>');
        echo ('</tr>');
    }
    $rsInf->free();
}

?>
<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>