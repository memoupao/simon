<?php include("../../includes/constantes.inc.php"); ?>

<?php include("../../includes/validauser.inc.php"); ?>



<?php

require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");

require_once (constant("PATH_CLASS") . "BLFuentes.class.php");

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

require_once (constant("PATH_CLASS") . "BLReportes.class.php");

require_once (constant("PATH_CLASS") . "HardCode.class.php");

require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objInf = new BLMonitoreoFinanciero();

$objProy = new BLProyecto();

$objRep = new BLReportes();

$HardCode = new HardCode();

$objTablas = new BLTablasAux();

$objFuentes = new BLFuentes();

$idProy = $objFunc->__Request('idProy');

$idInforme = $objFunc->__Request('idNum');

$idVs = $objProy->MaxVersion($idProy);

$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVs);

$rowinf = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);

$rsSector = $objProy->SectoresProductivos_Listado($idProy);

$row = $objRep->RepFichaProy($idProy, $idVs);

if ($objFunc->__QueryString() == "") {
    
    ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>


<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />

<!-- InstanceBeginEditable name="doctitle" -->



<title>Detalle del Informe de Monitoreo Financiero</title>



<!-- InstanceEndEditable -->

<!-- InstanceBeginEditable name="head" -->

<script src="../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></script>

<!-- InstanceEndEditable -->

<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />

</head>



<body>

	<!-- InstanceBeginEditable name="tBody" -->

	<form id="frmMain" name="frmMain" method="post" action="#">

  <?php
}

?>

   

  <div class="TableGrid">

			<table width="700" cellpadding="0" cellspacing="0">

				<tbody class="data" bgcolor="#FFFFFF">

					<tr>

						<td width="24%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Número del Informe</strong></td>

						<td colspan="2" align="left" valign="middle"><strong><?php echo($idInforme);?></strong></td>

						<td colspan="2" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Código
								del Proyecto</strong></td>

						<td width="34%" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>

					</tr>

					<tr style="font-size: 11px;">

						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Periodo
								Referencia</strong></td>

						<td colspan="5" align="left" valign="middle"><strong><?php echo(  $rowinf['t51_periodo']);?></strong></td>

					</tr>

					<tr style="font-size: 11px;">

						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Presentación</strong></td>

						<td colspan="5" align="left" valign="middle"><strong><?php echo($rowinf['fecpre']);?></strong></td>

					</tr>

					<tr style="font-size: 11px;">

						<td height="32" align="left" valign="middle" bgcolor="#FFFFAA"><strong>Supervisor
								del Proyecto</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo($row['jefe_proy']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Temático</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo($row['moni_tema']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Financiero</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo($row['moni_fina']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Externo</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo($row['moni_exte']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Título
								del Proyecto</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>

					</tr>

        <?php while($rsS = mysqli_fetch_assoc($rsSector))  { ?>

        <tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Sector
								Productivo</strong></td>

						<td colspan="2" align="left" valign="middle"><?php echo($rsS['sector']);?></td>

						<td colspan="2" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Subsector</strong></td>

						<td align="left" valign="middle"><?php echo($rsS['subsector']);?></td>

					</tr>

        <?php }?>

        <?php
        
        $rsAmbito = $objProy->AmbitoGeo_Listado($idProy, $idVersion);
        
        $rowspan = mysqli_num_rows($rsAmbito);
        
        ?>

        <tr style="font-size: 11px;">

						<td rowspan="<?php echo($rowspan+2);?>" align="left"
							valign="middle" bgcolor="#E8E8E8"><strong>Ubicación</strong></td>

						<td colspan="5" align="center" valign="middle"
							style="display: none;">&nbsp;</td>

					</tr>

					<tr style="font-size: 11px;">

						<td width="24%" height="23" align="center" valign="middle"
							bgcolor="#E8E8E8"><strong>Departamento</strong></td>

						<td colspan="3" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Provincia</strong></td>

						<td colspan="3" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Distrito</strong></td>

					</tr>

        <?php while($r = mysqli_fetch_assoc($rsAmbito))  { ?>

        <tr style="font-size: 11px;">

						<td height="25" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dpto']);?></td>

						<td colspan="3" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['prov']);?></td>

						<td colspan="3" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dist']);?></td>

					</tr>

        <?php
        }
        
        $rsAmbito->free();
        
        ?>

        <tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Propósito
								del Proyecto</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_pro']));?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Institución
								Ejecutora</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t01_nom_inst']));?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Instituciones
								Colaboradoras</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo($row['inst_colabora']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Población
								Beneficiaria</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								real de Inicio del proyecto</strong></td>

						<td colspan="5" align="left" valign="middle"><?php echo ($row['t02_fch_ini']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								programada para el término del proyecto</strong></td>

						<td colspan="6" align="left" valign="middle"><?php echo ($row['t02_fch_ter']);?></td>

					</tr>

					<tr style="font-size: 11px;">

						<td align="left" valign="middle" bgcolor="#E8E8E8">&nbsp;</td>

						<td colspan="6" align="left" valign="middle">&nbsp;</td>

					</tr>

					<tr style="font-size: 11px;">

						<td rowspan="3" align="left" valign="middle" bgcolor="#E8E8E8"><strong>ANEXOS</strong></td>

						<td colspan="6" align="left" valign="middle"><a href="#"
							onclick="ExportarAnexos('1');">Anexo 1: Resumen Financiero del
								Proyecto</a></td>

					</tr>

					<tr style="font-size: 11px;">

						<td colspan="6" align="left" valign="middle"><a href="#"
							onclick="ExportarAnexos('2');">Anexo 2: Resumen del Excedente por
								Ejecutar</a></td>

					</tr>

					<tr style="font-size: 11px;">

						<td colspan="6" align="left" valign="middle"><a href="#"
							onclick="ExportarAnexos('3');">Anexo 3: Presupuesto Ejecutado
								acumulado</a></td>

					</tr>

				</tbody>

				<tfoot>

				</tfoot>

			</table>

			<p>&nbsp;</p>

			<table width="900" border="0" cellspacing="0" cellpadding="0"
				style="border: none;">

				<tr>

					<td><table border="0" cellspacing="1" cellpadding="0">

							<tr>

								<td width="82%"><strong>Documentos presentados por la
										institución</strong></td>

							</tr>

							<tr>

								<td><font style="font-size: 11px; color: #2A3F55;">De acuerdo al
										convenio firmado entre la Institucion y FONDOEMPLEO, la
										institución debe elaborar reportes sobre la base de Reportes
										Contables y de Ejecución Presupuestal que emita el sistema
										contable que manejan.</font></td>

							</tr>

						</table>

						<table width="900" cellpadding="0" cellspacing="0">

							<thead>

							</thead>

							<tbody class="data" bgcolor="#FFFFFF">

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">

									<td width="352" align="center" valign="middle">Reporte /
										Información</td>

									<td align="center" valign="middle">Estado</td>

									<td width="413" height="23" colspan="6" align="center"
										valign="middle">OBSERVACIONES</td>

								</tr>

              <?php
            
            $iRs = $objInf->Inf_MF_Listado_Docum_Inst($idProy, $idInforme);
            
            while ($r = mysqli_fetch_assoc($iRs)) 

            {
                
                ?>

              <tr>

									<td height="30" align="left" nowrap="nowrap" class="TableGrid">

                  <?php echo($r['tipodoc']); ?></td>

									<td width="133" align="center" valign="middle">

                  <?php
                
                $rdoc = $objTablas->TipoSeleccionar($r['t51_estdoc']);
                
                echo ($rdoc['descrip']);
                
                ?>

               </td>

									<td colspan="6" align="left" nowrap="nowrap"><?php echo($r['observaciones']);?></td>

								</tr>

              <?php
            }
            
            $iRs->free();
            
            ?>

            </tbody>

							<tfoot>

							</tfoot>

						</table> <br />
					<br /></td>

				</tr>

				<tr>

					<td height="32">



						<table width="900" border="1" cellpadding="0" cellspacing="0">

							<tr>

								<td height="36" colspan="9" align="left" nowrap="nowrap"><strong>Avance Presupuestal - <?php echo($HardCode->Nombre_Fondoempleo);?></strong></td>

							</tr>

							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">

								<th width="39" rowspan="2" align="center" valign="middle">Codigo</th>

								<th width="193" rowspan="2" align="center" valign="middle">Actividades</th>

								<th width="55" rowspan="2" align="center" valign="middle">U.M.</th>

								<th width="86" rowspan="2" align="center" valign="middle">Presupuesto</th>

								<th height="28" colspan="4" align="center" valign="middle">Total Periodo <?php echo(" (".$rowinf['t51_per_ini']." - ".$rowinf['t51_per_fin'].")"); ?></th>

								<th width="255" rowspan="2" align="center" valign="middle">Observaciones
									del Monitor</th>

							</tr>

							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">

								<th width="84" height="28" align="center" valign="middle">Programado</th>

								<th width="83" align="center" valign="middle">Ejecutado</th>

								<th width="42" align="center" valign="middle">% Ejec.</th>

								<th width="42" align="center" valign="middle">Gastos No Aceptado</th>

							</tr>

        

      <?php
    
    $total_presup = 0;
    
    $total_ejec = 0;
    
    $total_GNA = 0;
    
    $idFuente = $HardCode->codigo_Fondoempleo;
    
    ?>  

       <tbody class="data">

        <?php
        
        $rsComp = $objInf->ListaComponentes($idProy);
        
        while ($rowC = mysqli_fetch_assoc($rsComp)) 

        {
            
            $idComp = $rowC['t08_cod_comp'];
            
            ?>

        <tr style="background-color: #FFC; height: 25px;">

									<td align="left" nowrap="nowrap"><?php echo($idComp);?></td>

									<td colspan="8" align="left"><?php echo( $rowC['t08_comp_desc']);?></td>

								</tr>

        

        <?php
            
            $iRsAct = $objInf->Inf_MF_ListadoActividades($idProy, $idComp, $idInforme, $idFuente);
            
            while ($rowAct = mysqli_fetch_assoc($iRsAct)) 

            {
                
                $idAct = $rowAct['t09_cod_act'];
                
                $total_presup += $rowAct['total_presup'];
                
                $total_ejec += $rowAct['ejecutado'];
                
                $total_planeado += $rowAct['programado'];
                
                ?>

        

        <tr
									style="background-color: #FC9; height: 25px; cursor: pointer;"
									onclick="ShowActividadFis('<?php echo("tbody2_".$idFte.'_'.$idAct);?>');">

									<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>

									<td colspan="2" align="left"><?php echo( $rowAct['actividad']);?></td>

									<td align="center"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>

									<td align="right"><?php echo(number_format($rowAct['programado'],2,'.',','));?></td>

									<td align="right"><?php echo(number_format($rowAct['ejecutado'],2,'.',','));?></td>

									<td align="center"><?php echo($porcEjecucion);?>%</td>

									<td align="center">&nbsp;</td>

									<td align="center">&nbsp;</td>

								</tr>

    <?php
                
                $iRs = $objInf->Inf_MF_ListadoSubActividades($idProy, $idComp, $idAct, $idInforme, $idFuente);
                
                while ($rowsub = mysqli_fetch_assoc($iRs)) 

                {
                    
                    $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
                    
                    $total_GNA += $rowsub['gasto_no_aceptado'];
                    
                    ?>

    <tr>

									<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>

									<td align="left"><?php echo( $rowsub['subactividad']);?></td>

									<td align="center"><?php echo($rowsub['um']);?></td>

									<td align="center"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>

									<td align="right"><?php echo(number_format($rowsub['programado'],2,'.',','));?></td>

									<td align="right"><?php echo(number_format($rowsub['ejecutado'],2,'.',','));?></td>

									<td align="center"><?php echo($porcEjecucion);?>%</td>

									<td align="center"><?php if($rowsub['ejecutado']<=0) { ?>

        <?php echo(number_format($rowsub['gasto_no_aceptado'],2));?>

        <?php } else { ?>

        <a href="javascript:;"
										onclick="AgregarGastosNoAceptados('<?php echo($rowsub['codigo']);?>');"><?php echo(number_format($rowsub['gasto_no_aceptado'],2));?></a>

        <?php } ?></td>

									<td align="center"><?php echo($rowsub['observaciones']);?></td>

								</tr>

      <?php } $iRs->free(); // Fin de SubActividades ?>

	  <?php } $iRsAct->free(); // Fin de Actividades 	?>

      <?php } $rsComp->free(); // Fin de Actividades 	?>

      </tbody>

							<tfoot>

								<tr style="color: #000; height: 20px;">

									<th colspan="3">Totales</th>

									<th align="center"><?php echo(number_format($total_presup,2,'.',','));?></th>

									<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>

									<th align="right"><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</th>

									<th align="center"><?php echo(round((($total_ejec/$total_planeado)*100),2));?>%</th>

									<th align="center"><?php echo(number_format($total_GNA,2));?></th>

									<th align="right">&nbsp;</th>

								</tr>

							</tfoot>

						</table>



					</td>

				</tr>

				<tr>

					<td><p>&nbsp;</p>

						<table width="900" border="1" cellpadding="0" cellspacing="0">

							<tr>

								<td height="36" colspan="9" align="left" nowrap="nowrap"><strong>Avance
										Fisico </strong></td>

							</tr>

							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">

								<th width="39" rowspan="2" align="center" valign="middle">Codigo</th>

								<th width="193" rowspan="2" align="center" valign="middle">Actividades</th>

								<th width="55" rowspan="2" align="center" valign="middle">U.M.</th>

								<th width="43" rowspan="2" align="center" valign="middle">Metas</th>

								<th width="86" rowspan="2" align="center" valign="middle">Presupuesto</th>

								<th height="28" colspan="3" align="center" valign="middle">Total Periodo <?php echo(" (".$rowinf['t51_per_ini']." - ".$rowinf['t51_per_fin'].")"); ?></th>

								<th width="255" rowspan="2" align="center" valign="middle">Observaciones
									del Monitor</th>

							</tr>

							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">

								<th width="84" height="28" align="center" valign="middle">Programado</th>

								<th width="83" align="center" valign="middle">Ejecutado</th>

								<th width="42" align="center" valign="middle">% Ejec.</th>

							</tr>

            <?php
            
            $total_presup = 0;
            
            $total_ejec = 0;
            
            ?>

            <tbody class="data">

              <?php
            
            $rsComp = $objInf->ListaComponentes($idProy);
            
            while ($rowC = mysqli_fetch_assoc($rsComp)) 

            {
                
                $idComp = $rowC['t08_cod_comp'];
                
                ?>

              <tr style="background-color: #FFC; height: 25px;">

									<td align="left" nowrap="nowrap"><?php echo($idComp);?></td>

									<td colspan="8" align="left"><?php echo( $rowC['t08_comp_desc']);?></td>

								</tr>

              <?php
                
                $iRsAct = $objInf->Inf_MF_ListadoActividades2($idProy, $idComp, $idInforme);
                
                while ($rowAct = mysqli_fetch_assoc($iRsAct)) 

                {
                    
                    $idAct = $rowAct['t09_cod_act'];
                    
                    $total_presup += $rowAct['total_presup'];
                    
                    $total_ejec += $rowAct['ejecutado'];
                    
                    $total_planeado += $rowAct['programado'];
                    
                    ?>

              <tr
									style="background-color: #FC9; height: 25px; cursor: pointer;"
									onclick="ShowActividadFis('<?php echo("tbody2_".$idFte.'_'.$idAct);?>');">

									<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>

									<td colspan="2" align="left"><?php echo( $rowAct['actividad']);?></td>

									<td align="center">&nbsp;</td>

									<td align="right"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>

									<td align="center">&nbsp;</td>

									<td align="center">&nbsp;</td>

									<td align="center">&nbsp;</td>

									<td align="center">&nbsp;</td>

								</tr>

              <?php
                    
                    $iRs = $objInf->Inf_MF_ListadoSubActividades2($idProy, $idComp, $idAct, $idInforme);
                    
                    while ($rowsub = mysqli_fetch_assoc($iRs)) 

                    {
                        
                        $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
                        
                        ?>

              <tr>

									<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>

									<td align="left"><?php echo( $rowsub['subactividad']);?>

                  <input name="txt_cod_sub[]2" id="txt_cod_sub[]2"
										type="hidden" value="<?php echo($rowsub['codigo']); ?>"
										class="fisico" /></td>

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

              <?php } $rsComp->free(); // Fin de Actividades 	?>

            </tbody>

							<tfoot>

								<tr style="color: #006; height: 20px;">

									<th colspan="3">Totales</th>

									<th colspan="2" align="center"><?php echo(number_format($total_presup,2,'.',','));?></th>

									<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>

									<th align="right"><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</th>

									<th align="center"><?php echo(round((($total_ejec/$total_planeado)*100),2));?>%</th>

									<th align="right">&nbsp;</th>

								</tr>

							</tfoot>

						</table>

						<p>&nbsp;</p></td>

				</tr>

				<tr>

					<td><strong class="caption">Excedentes por Ejecutar - Monitor
							Financiero</strong> <br />

						<table width="750" cellpadding="0" cellspacing="0">

							<tbody class="data" bgcolor="#FFFFFF">

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #FFF;">

									<td align="center" valign="middle">FECHA AL:</td>

									<td height="23" colspan="6" align="left" valign="middle"><?php echo($row['t51_cor_ctb']);?></td>

								</tr>

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">

									<td width="74" align="center" valign="middle">&nbsp;</td>

									<td width="176" align="center" valign="middle">MONTO S/.</td>

									<td height="23" colspan="5" align="center" valign="middle">OBSERVACIONES</td>

								</tr>

								<tr>

									<td class="SubtitleTable"
										style="border: solid 1px #CCC; background-color: #eeeeee;"><strong>CAJA
											CHICA</strong></td>

									<td height="25" align="right" valign="middle"><?php echo(number_format($row['t51_caja'])); ?></td>

									<td colspan="5" align="left" valign="middle"><?php echo($row['t51_caja_obs']); ?></td>

								</tr>

								<tr>

									<td class="SubtitleTable"
										style="border: solid 1px #CCC; background-color: #eeeeee;"><strong>BANCO
											MONEDA NACIONAL</strong></td>

									<td height="25" align="right" valign="middle"><?php echo(number_format($row['t51_bco_mn']));?></td>

									<td colspan="5" align="left" valign="middle"><?php echo($row['t51_bco_mn_obs']);?></td>

								</tr>

								<tr>

									<td class="SubtitleTable"
										style="border: solid 1px #CCC; background-color: #eeeeee;"><strong>ENTREGAS
											A RENDIR CUENTA</strong></td>

									<td height="25" align="right" valign="middle"><?php echo(number_format($row['t51_ent_rend']));?></td>

									<td colspan="5" align="left" valign="middle"><?php echo($row['t51_ent_rend_obs']);?></td>

								</tr>

								<tr>

									<td class="SubtitleTable"
										style="border: solid 1px #CCC; background-color: #eeeeee;"><strong>CUENTAS
											X PAGAR</strong></td>

									<td height="25" align="right" valign="middle"><?php echo(number_format($row['t51_cxp']));?></td>

									<td colspan="5" align="left" valign="middle"><?php echo($row['t51_cxp_obs']);?></td>

								</tr>

								<tr>

									<td class="SubtitleTable"
										style="border: solid 1px #CCC; background-color: #eeeeee;"><strong>CUENTAS
											X COBRAR</strong></td>

									<td height="25" align="right" valign="middle"><?php echo(number_format($row['t51_cxc']));?></td>

									<td colspan="5" align="left" valign="middle"><?php echo($row['t51_cxc_obs']);?></td>

								</tr>

							</tbody>

							<tfoot>

								<tr style="color: #FFF;">

									<td height="27" nowrap="nowrap" class="TableGrid"><strong>TOTAL</strong></td>

									<td align="right" valign="middle"><strong><?php echo(number_format($row['t51_exc'],2));?></strong></td>

									<td width="85" align="center" nowrap="nowrap">&nbsp;</td>

									<td width="96" align="center" nowrap="nowrap">&nbsp;</td>

									<td width="99" align="center" nowrap="nowrap">&nbsp;</td>

									<td width="109" align="center" nowrap="nowrap">&nbsp;</td>

									<td width="118" align="center" nowrap="nowrap"
										bgcolor="#CC9933">&nbsp;</td>

								</tr>

							</tfoot>

						</table>

						<p>&nbsp;</p></td>

				</tr>

				<tr>

					<td height="45"><strong>Observaciones de Evaluaciones de Periodos
							Anteriores</strong> <br />

						<table width="775" cellpadding="0" cellspacing="0">

							<thead>

							</thead>

							<tbody class="data" bgcolor="#FFFFFF">

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">

									<td width="16" align="center" valign="middle">#</td>

									<td align="center" valign="middle">Fecha Informe</td>

									<td width="324" align="center" valign="middle">Observaciones
										del Monitor</td>

									<td width="299" align="center" valign="middle">Respuesta del
										Ejecutor</td>

									<td width="53" height="23" colspan="14" align="center"
										valign="middle">OK Monitor</td>

								</tr>

     <?php
    
    $iRs = $objInf->Inf_MF_ListadoObservaciones($idProy, $idInforme);
    
    $contador = 1;
    
    while ($r = mysqli_fetch_assoc($iRs)) 

    {
        
        ?>



     <tr>

									<td height="43" align="center" nowrap="nowrap"
										class="TableGrid"><?php echo($contador); ?></td>

									<td width="81" align="center" valign="middle"><?php echo($r['fecha']); ?></td>

									<td align="left"><?php echo(nl2br($r['t51_obs_moni'])); ?></td>

									<td align="left"><?php echo(nl2br($r['t51_obs_eje'])); ?></td>

									<td colspan="14" align="center" nowrap="nowrap"><?php if($r['t51_ok_moni']=='1'){echo('Si');}else{echo('No');} ?></td>

								</tr>

       <?php
        
        $contador ++;
    }
    
    $iRs->free();
    
    ?>

     </tbody>

							<tfoot>

							</tfoot>

						</table>
						<br />
					<br />

						<table width="775" border="0" cellpadding="0" cellspacing="0">

							<thead>

							</thead>

							<tbody class="data" bgcolor="#FFFFFF">

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">

									<td height="23" align="left" valign="middle"><strong>Observaciones
											del Monitor </strong> <br /></td>

								</tr>

								<tr>

									<td height="43" align="left">

                <?php echo(nl2br( $rowinf['t51_obs']));?></td>

								</tr>

							</tbody>

							<tfoot>

							</tfoot>

						</table></td>

				</tr>

				<tr>

					<td height="42"><strong>Conclusiones</strong> <br />

						<div
							style="border: solid 1px #666; min-height: 30px; max-width: 700px;">

        <?php echo(nl2br($rowinf['t51_conclu']));?>

        </div> <br /> <br /> <strong>Calificción</strong><br />

         <?php
        
        $rCal = $objTablas->TipoSeleccionar($rowinf['t51_califica']);
        
        echo ($rCal['descrip']);
        
        ?>

          <br />
					<br /></td>

				</tr>

				<tr>

					<td height="42"><strong>Documentos Anexos</strong><br />

						<table width="750" border="0" cellpadding="0" cellspacing="0">

							<thead>

							</thead>

							<tbody class="data" bgcolor="#FFFFFF">

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">

									<td width="215" align="center" valign="middle" nowrap="nowrap"><strong>Nombre
											del Archivo</strong></td>

									<td width="481" height="23" align="center" valign="middle"><strong>Descripcion
											del Archivo</strong></td>

								</tr>

              <?php
            
            $iRs = $objInf->Inf_MF_ListaAnexos($idProy, $idInforme);
            
            $RowIndex = 0;
            
            if ($iRs->num_rows > 0) 

            {
                
                while ($row = mysqli_fetch_assoc($iRs)) {
                    
                    ?>

              <tr>

                <?php
                    
                    $urlFile = $row['t51_url_file'];
                    
                    $filename = $row['t51_nom_file'];
                    
                    $path = constant('APP_PATH') . "sme/monitoreofe/informes/inf_mf/";
                    
                    $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
                    
                    ?>

                <td height="30" align="center" valign="middle"><a
										href="<?php echo($href);?>" title="Descargar Archivo"
										target="_blank"><?php echo($row['t51_nom_file']);?></a></td>

									<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t51_desc_file']);?></td>

								</tr>

              <?php
                    
                    $RowIndex ++;
                }
                
                $iRs->free();
            } // Fin de Anexos Fotograficos
            
            ?>

            </tbody>

							<tfoot>

								<tr>

									<td colspan="2" align="center" valign="middle">&nbsp; <iframe
											id="ifrmUploadFile" name="ifrmUploadFile"
											style="display: none;"></iframe>

										<div id="divLoadingAnexo"
											style="width: 99%; background-color: #FFF;"></div></td>

								</tr>

							</tfoot>

						</table>

						<p>
							<br />

						</p></td>

				</tr>

			</table>

			<p>&nbsp;</p>

		</div>



		<script language="javascript" type="text/javascript">

    function ExportarAnexos(idAnx)

	{

		if(idAnx=="") {return ;}

		

		var arrayControls = new Array();

			arrayControls[0] = "idProy=<?php echo($idProy);?>";			

			arrayControls[1] = "idInforme=<?php echo($idInforme);?>" ;

			arrayControls[2] = "idFte=<?php echo($HardCode->codigo_Fondoempleo);?>" ;

		var params = arrayControls.join("&"); 

		var sID = "0" ;

		switch(idAnx)

		{

			case "1" : sID = "39"; break;

			case "2" : sID = "40"; break;

			case "3" : sID = "41"; break;

		}

		showReportAnx(sID, params);

		return;

	}

	

	function showReportAnx(reportID, params)

	{

	 var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;

	 window.open(newURL,"wrAnexos","fullscreen,scrollbars");

	}



	

  </script>

  

  <?php if($objFunc->__QueryString()=="") { ?>

</form>

	<!-- InstanceEndEditable -->

</body>

<!-- InstanceEnd -->
</html>

<?php } ?>