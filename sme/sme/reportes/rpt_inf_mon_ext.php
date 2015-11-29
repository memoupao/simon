<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idanio');
$idTrim = $objFunc->__Request('idtrim');
$idVersion = $objFunc->__Request('vs') ? $objFunc->__Request('vs') : $idAnio + 1;

$idNum = $objFunc->__Request('idNum');
$idVer = 1;

// $idVs = $objFunc->__Request('idversion');

$objProy = new BLProyecto();
$rsSector = $objProy->SectoresProductivos_Listado($idProy);

$idVs = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVs);

$objInf = new BLInformes();

$objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVs);
// $objRep = NULL ;

$rowInfME = $objInf->InformeMESeleccionar($idProy, $idNum, $idVer);
// print "<pre>";print_r($row); print "<pre>";

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe Mensual</title>
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


			<table width="700" cellpadding="0" cellspacing="0">


				<tbody class="data" bgcolor="#FFFFFF">

					<tr>
						<td width="24%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Numero del Informe</strong></td>
						<td colspan="2" align="left" valign="middle"><strong><?php echo($idNum);?></strong>&nbsp;</td>
						<td colspan="2" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Código
								del Proyecto</strong></td>
						<td width="34%" align="left" valign="middle"><strong><?php echo($rowInfME['t02_cod_proy']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Periodo
								Referencia</strong></td>
						<td colspan="5" align="left" valign="middle"><strong><?php echo($rowInfME['t30_periodo']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								del presente informe</strong></td>

						<td colspan="5" align="left" valign="middle"><strong>
        <?php echo($rowInfME['t30_fch_pre']);?>	 &nbsp; &nbsp;&nbsp;&nbsp; </strong>Correspondiente<strong>:	Año 
		 <?php echo($rowInfME['t30_anio']);?> </strong>de Ejecución</td>
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
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Nombre
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
    
    $rsAmbito = $objProy->AmbitoGeo_Listado($idProy, $idVs);
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
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Fundación</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['t01_fch_fund']);?></td>
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
						<td colspan="5" align="left" valign="middle"><?php echo ($row['t02_fch_ter']);?></td>
					</tr>
					<tr bgcolor="#E8E8E8" style="font-size: 11px;">
						<td align="left" valign="middle"><strong>Resumir datos de
								programacion presupuestal</strong></td>
						<td colspan="3" align="left" valign="middle"><div align="center">
								<strong>Total programado S/.</strong>
							</div></td>
						<td width="19%" align="left" valign="middle"><div align="center">
								<strong>Total ejecutado S/.</strong>
							</div></td>
						<td align="left" valign="middle"><div align="center">
								<strong>Porcentaje de avance (%)</strong>
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
						<td align="left" valign="middle" bgcolor="#E8E8E8"><?php echo $rfte['t01_sig_inst']; ?></td>
						<td colspan="3" align="left" valign="middle">
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
						<td align="left" valign="middle">TOTAL DEL PROYECTO</td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($totPro,2));?></td>
      <?php $porcentaje = ($aTotEje * 100) / $totPro ;?>
      <td><?php echo(number_format($aTotEje,2));?></td>
						<td><?php echo(number_format($porcentaje,2)." %");?></td>
					</tr>
				</tfoot>

			</table>


			<br />



			<table width="800" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#CCCCFF">
					<td align="left" valign="middle"><strong style="color: blue;">2.
							INTRODUCCION</strong></td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7" align="left" valign="middle"
						style="padding-left: 5px;"><?php echo(nl2br($rowInfME['t30_intro']));?></td>
				</tr>

				<tr bgcolor="#CCCCFF">
					<td align="left" valign="middle"><strong style="color: blue;">3.
							METODOS Y FUENTES DE INFORMACION UTILIZADAS</strong></td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle" style="padding-left: 5px;"><?php echo(nl2br($rowInfME['t30_fuentes']));?></td>
				</tr>

				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">4. AVANCE DE COMPONENTES</strong></td>

				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>

				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">
						<table width="800" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $rsc = $objInf->ListaComponentes($idProy);
        
        while ($row_comp = mysqli_fetch_assoc($rsc)) {
            
            ?>
        
         <tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td colspan="7" height="23" align="left" valign="middle"><strong>COMPONENTE: <?php echo($row_comp['t08_cod_comp'].".- ".$row_comp['t08_comp_desc']);?></strong></td>
								</tr>
        <?php
            
            $iRs = $objInf->ListaIndicadoresComponenteME($idProy, $row_comp['t08_cod_comp'], $idNum);
            
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="492" align="left" valign="middle"><strong>Indicador
											de Componente</strong></td>
									<td width="93" height="15" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
								</tr>
								<tr>
									<td width="492" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp_ind'].".- ".$row['indicador']);?>
     <?php echo($row['t08_cod_comp_ind']);?> 
         <br /> <span><strong style="color: red;">Unidad Medida</strong>: <?php echo( $row['t08_um']);?></span></td>
									<td align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Avance</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
								</tr>
								<tr>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_avance']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
								</tr>

								<tr style="font-weight: 300; color: navy;">
									<td height="107" colspan="7" align="left"><strong>OBSERVACIONES</strong><br />
              <?php echo(nl2br($row['descripcion']));?>
			</td>
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
			</table>

			<table width="800" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#CCCCFF">
					<td align="left" valign="middle"><strong style="color: blue;">5
							AVANCE DE ACTIVIDADES</strong></td>

				</tr>
				<tr>
					<td align="left" valign="middle"><div id="divTableLista">
							<table width="800" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
            <?php
            // $objInf = new BLInformes();
            $rs = $objInf->ListaActividades($idProy);
            while ($row_act = mysqli_fetch_assoc($rs)) {
                
                $iRs = $objInf->ListaIndicadoresActividadME($idProy, $row_act['codigo'], $idNum);
                
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        ?>
       <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
										<td align="left" valign="middle"><strong>Indicador de
												Actividad</strong></td>
										<td height="15" align="center" valign="middle" nowrap="nowrap"
											bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
										<td colspan="3" align="center" valign="middle"
											bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
									</tr>
									<tr>
										<td rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']." ".$row['indicador']);?>
         <input name="t09_cod_act_ind[]" type="hidden"
											id="t09_cod_act_ind[]"
											value="<?php echo($row['t09_cod_act_ind']);?>" /> <br /> <span><strong
												style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
										<td width="93" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
										<td width="70" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
										<td width="71" align="center" bgcolor="#CCCCCC"><strong>Avance</strong></td>
										<td width="78" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									</tr>
									<tr>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtaacum']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_avance']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
									</tr>
									<tr style="font-weight: 300; color: navy;">
										<td colspan="7" align="left"><strong>OBSERVACIONES</strong><br />
                <?php echo(nl2br($row['descripcion']));?>
              </td>
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

						</div></td>
				</tr>

			</table>

			<table width="800" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="5" align="left" valign="middle"><strong
						style="color: blue;">6. AVANCE DE SUB ACTIVIDADES</strong></td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">
						<table width="800" height="181" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $rs = $objInf->ListaActividades($idProy);
        while ($row_act = mysqli_fetch_assoc($rs)) {
            ?>
          <tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td colspan="7" height="23" align="left" valign="middle"><strong>ACTIVIDAD: <?php echo($row_act['actividad']);?></strong></td>
								</tr>
        <?php
            $iRs = $objInf->ListaSubActividadesME($idProy, $row_act['codigo'], $idNum);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
           <tr
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="492" align="left" valign="middle"><strong>SubActividad</strong></td>
									<td height="15" align="center" valign="middle" nowrap="nowrap"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Periodo (<?php echo $rowInfME['t30_per_ini'] . ' - ' . $rowInfME['t30_per_fin']; ?>)</strong></td>
								</tr>
								<tr>
									<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?>
         <input name="t09_cod_sub[]" type="hidden" id="t09_cod_sub[]"
										value="<?php echo($row['t09_cod_sub']);?>" /> <br /> <span><strong
											style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
									<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Programado</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Ejecutado</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>% Ejec.</strong></td>
								</tr>
								<tr>
									<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_avance']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
								</tr>
								<tr style="font-weight: 300; color: navy;">
									<td height="107" colspan="7" align="left"><strong>OBSERVACIONES</strong><br />
              <?php echo(nl2br($row['descripcion']));?>
            </td>
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
        </script>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="3" align="left" valign="middle"><strong
						style="color: blue;">6. CONCLUSIONES</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="800"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Avances</strong></td>
								</tr>

								<tr>
          <?php
        $objInf = new BLInformes();
        $rowAnalisis = $objInf->InformeMESeleccionar($idProy, $idNum, $idVer);
        
        ?>
            <td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t30_avance']));?></td>
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
					<td colspan="5" align="left" valign="middle"><table width="800"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Logros</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t30_logros']));?></td>
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
					<td colspan="5" align="left" valign="middle"><table width="800"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Dificultades</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t30_dificul']));?></td>
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
					<td colspan="5" align="left" valign="middle"><table width="800"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Recomendaciones
											al Proyecto</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t30_reco_proy']));?></td>
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
					<td colspan="5" align="left" valign="middle"><table width="800"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="742" height="23" align="left" valign="middle"><strong>Recomendaciones
											a Fondoempleo</strong></td>
								</tr>
								<tr>
									<td width="742" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t30_reco_fe']));?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>


			</table>

			<table width="800" cellpadding="0" cellspacing="0">
				<tr bgcolor="#CCCCFF">
					<td align="left" valign="middle"><strong style="color: blue;">7.
							CALIFICACION</strong></td>
				</tr>
   
	<?php
$rowV = $objInf->InformeMESeleccionar($idProy, $idNum, $idVer);
?>   
  <tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle">
							<table class="grid-table grid-width" class="TableEditReg">
								<tr>
									<td>&nbsp;</td>
									<td align="center"><b>Valoración</b></td>
								</tr>
								<tr>
									<td align="left">Relación planificado y ejecutado operativo</td>
									<td align="center" valign="middle"> 
          <?php
        $objTablas = new BLTablasAux();
        $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva1']);
        echo ($objT['descrip']);
        ?>           
          </td>
								</tr>
								<tr>
									<td align="left">Relación entre ejecución financiera y
										ejecución técnica.</td>
									<td align="center">
             <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva2']);
            echo ($objT['descrip']);
            ?>  </td>
								</tr>
								<tr>
									<td align="left">Avance de actividades críticas</td>
									<td align="center">
          
            <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva3']);
            echo ($objT['descrip']);
            ?>  </td>
								</tr>
								<tr>
									<td align="left">Calidad de las Capacitaciones</td>
									<td align="center">  
          <?php
        $objTablas = new BLTablasAux();
        $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva4']);
        echo ($objT['descrip']);
        ?>  </td>
								</tr>
								<tr>
									<td align="left">Calidad&nbsp; y congruencia&nbsp; (capacidad
										de cobertura del ámbito del proyecto) del equipo técnico</td>
									<td align="center">
          <?php
        $objTablas = new BLTablasAux();
        $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva5']);
        echo ($objT['descrip']);
        ?>  </td>
								</tr>
								<tr>
									<td align="left">Opinión de los beneficiarios respecto al
										proyecto y sus resultados</td>
									<td align="center">
            <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva6']);
            echo ($objT['descrip']);
            ?>  </td>
								</tr>
								<tr>
									<td align="left">Manejo adecuado del Proyecto</td>
									<td align="center">
            <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t30_crit_eva7']);
            echo ($objT['descrip']);
            ?>  </td>
								</tr>

								<!--        <tr>
          <td align="left">Sistema  de control presupuestal descentralizada </td>
          <td align="center">
          <select name="t30_crit_eva7" class="Boton" id="t30_crit_eva7" style="width:150px;">
            <option value="" selected="selected"></option>
            <?php
            // $rs = $objTablas->ValoraInformesME();
            // $objFunc->llenarCombo($rs,'codigo','descripcion',$row['t30_crit_eva7'], 'cod_ext');
            ?>
          </select></td>
          </tr>-->
          <?php
        $aprobado = $objFunc->calificacionInforme($rowV['puntaje']);
        ?>
        <tr>
									<td align="right"><strong>RESULTADO</strong></td>
									<td align="center"><span id="spanTotResult"><?php echo($rowV['puntaje'])?>&nbsp;</span>
										&nbsp;&nbsp; <span id="spanTotResult2"><?php echo($aprobado)?>&nbsp;</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr style="font-weight: 300; color: navy;">
						<td colspan="2" align="left"><strong>Sustento de Calificación</strong><br />
             <?php echo($rowV['t30_califica']);?>
              <br /></td>
					</tr>


				</tbody>

			</table>



			<table width="800" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="5" align="left" valign="middle"><strong
						style="color: blue;">8. ANEXOS</strong></td>
				</tr>
				<tr>
					<td colspan="7" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7" align="left" valign="middle">
						<table width="800" border="0" cellpadding="0" cellspacing="0">
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td height="22" colspan="3" align="left" valign="middle"><strong>Archivos
											Adjuntos al Informe</strong></td>
								</tr>
								<tr>
			<?php
// $objInf = new BLInformes();
$objInf = new BLInformes();
$iRs = $objInf->ListaAnexosInformeME($idProy, $idNum, $idVer);
$RowIndex = 0;
if ($iRs->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>
          
					<?php
        $urlFile = $row['t30_url_file'];
        $filename = $row['t30_nom_file'];
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));
        $path = constant('APP_PATH') . "sme/proyectos/informes/anx_me";
        $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
        if ($file_extension == 'gif' or $file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'png' or $file_extension == 'bmp') {
            $file_vista = "<img src=../../sme/proyectos/informes/anx_me/" . $urlFile . " />";
        } else {
            $file_vista = "<a href=" . $href . " title='Descargar Archivo' target='_blank'>" . $row['t30_nom_file'] . "</a>";
        }
        ?>
            <td width="800" height="30" align="center" valign="top"> <?php echo ($file_vista); ?><br>
              <?php echo(nl2br($row['t30_desc_file']));?>
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
						</table>
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
			<p></p>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>