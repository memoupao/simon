<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");
require (constant("PATH_CLASS") . "HardCode.class.php");
require (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require (constant("PATH_CLASS") . "BLManejoProy.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$idProy = $objFunc->__Request('idProy');
$idVs = $objFunc->__Request('idVers');
if ($idVs == '') {
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
			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
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
			</table>

			<table width="99%" border="0" cellspacing="0" cellpadding="0"
				class="TableEditReg">
				<tr>
					<td colspan="4" align="left"><strong>1. Caratula</strong></td>
				</tr>
				<tr>
					<td width="9%" height="25" align="left">Año</td>
					<td width="32%" align="left">
        Año
         <?php
        echo " " . $idAnio;
        ?>
        
					
					<td width="22%" align="left">Fecha de Presentación: <?php echo($row['t20_fch_pre'])?></td>
					<td width="37%" align="left"></td>
				</tr>
				<tr>
					<td height="27" align="left" nowrap="nowrap">Periodo Ref.</td>
					<td align="left" nowrap="nowrap"><?php echo($row['t20_periodo'])?></td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left"></td>
				</tr>
				<tr>
					<td height="26" align="left">Estado:</td>
					<td align="left"><strong><?php echo( $row['estado']); ?>    </strong></td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
			</table>


			<p>&nbsp;</p>


<?php /* ?>
			<table width="99%" cellpadding="0" cellspacing="0"
				class="TableEditReg">
				<tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
					<td height="33" colspan="10" align="left" valign="middle"><div
							style="display: inline-block;">
							<strong>AVANCE METAS ACTIVIDADES</strong>
						</div>
						<div style="display: inline-block;"></div></td>
				</tr>

       <?php
    
    $rs = $objInf->ListaActividades($idProy);
    
    while ($rowAct = mysqli_fetch_assoc($rs)) {
        
        ?>
        <tr>
					<td height="33" colspan="10" align="left" valign="middle"><div
							style="display: inline-block;">
							<strong> Producto: </strong>
						</div>
						<div style="display: inline-block;">
              <?php
        echo $rowAct['actividad'];
        $idAc = $rowAct['codigo'];
        ?>
            </div></td>
				</tr>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
        
        $iRs = $objInf->ListaIndicadoresActividad($idProy, $idAc, $idAnio, $idMes);
        $RowIndex = 0;
        if ($iRs->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td align="left" valign="middle"><strong>Indicador de Actividad</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"
							bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Ejecutado</strong></td>
					</tr>
					<tr>
						<td rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']." ".$row['indicador']);?>
         <input name="t09_cod_act_ind[]" type="hidden"
							id="t09_cod_act_ind[]"
							value="<?php echo($row['t09_cod_act_ind']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
						<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="55" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
						<td width="63" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
						<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo($row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['plan_mtames']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtames']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td height="147" colspan="7" align="left">DESCRIPCION <br /><?php echo($row['descripcion']);?><br />
							LOGROS <br />
         <?php echo($row['logros']);?><br /> DIFICULTADES <br />
         <?php echo($row['dificultades']);?></td>
					</tr>
     <?php
                $RowIndex ++;
            }
            $iRs->free();
        } // Fin de SubActividades
        
        ?>
       <?php } // Fin de Actividades?>
    </tbody>
				<tfoot>
				</tfoot>

			</table>
			
			<p>&nbsp;</p>
<?php */ ?>

			<table width="99%" cellpadding="0" cellspacing="0"
				class="TableEditReg">
				<tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
					<td height="33" colspan="11" align="left" valign="middle"><div
							style="display: inline-block;">
							<strong> AVANCE METAS ACTIVIDADES</strong>
						</div>
						<div style="display: inline-block;"></div></td>
				</tr>

       <?php
    
    $rs = $objInf->ListaActividades($idProy);
    
    while ($rowAct = mysqli_fetch_assoc($rs)) {
        
        ?>
        <tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
					<td height="33" colspan="11" align="left" valign="middle"><div
							style="display: inline-block;">
							<strong> Producto: </strong>
						</div>
						<div style="display: inline-block;">
              <?php
        echo $rowAct['actividad'];
        $idAc = $rowAct['codigo'];
        ?>
            </div></td>
				</tr>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
        
        $iRs = $objInf->ListaSubActividades($idProy, $idAc, $idAnio, $idMes);
        $RowIndex = 0;
        if ($iRs->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td align="left" valign="middle"><strong>Actividad</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#CCCCCC"><strong>Meta
								Planeada</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><strong>
								Ejecutado</strong></td>
					</tr>
					<tr>
						<td rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?>
         <input name="t09_cod_sub[]" type="hidden" id="t09_cod_sub[]"
							value="<?php echo($row['t09_cod_sub']);?>" /> <br /> <span><strong
								style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total de
								Proyecto</strong></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total
								Año</strong></td>
						<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum.
								anterior</strong></td>
						<td width="55" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum
								anterior</strong></td>
						<td width="63" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
						<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaanio']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtames']);?></td>
						<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="8" align="left">DESCRIPCION <br />
        <?php echo($row['descripcion']);?><br /> LOGROS <br />
         <?php echo($row['logros']);?><br /> DIFICULTADES <br />
         <?php echo($row['dificultades']);?><br /> OBSERVACIONES <br />
         <?php echo($row['omt']);?><br /></td>
					</tr>
     <?php
                $RowIndex ++;
            }
            $iRs->free();
        } // Fin de SubActividades
        
        ?>
       <?php } // Fin de Actividades?>
    </tbody>
				<tfoot>
				</tfoot>
			</table>
			<p>&nbsp;</p>

			<table width="99%" border="0" cellspacing="1" cellpadding="0">
				<tr>
					<td width="82%" align="left" class="TableEditReg">&nbsp;</td>
					<td width="8%" rowspan="2" align="center" class="TableEditReg"><div
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
							title="Refrescar datos de Problemas y Soluciones">
							<br />
						</div></td>
					<td width="10%" rowspan="2" align="right" valign="middle"><div
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;"
							title="Guardar Problemas y Soluciones">
							<br />
						</div></td>
				</tr>
				<tr>
					<td align="left"><span style="font-weight: bold;">4.1 Problemas y
							Adaptaciones</span></td>
				</tr>
			</table>
			<div id="divTableLista" class="TableGrid">
				<table width="99%" cellpadding="0" cellspacing="0">
					<thead>
					</thead>
					<tbody class="data" bgcolor="#FFFFFF">
						<tr class="SubtitleTable"
							style="border: solid 1px #CCC; background-color: #eeeeee;">
							<td width="26" align="center" valign="middle">#</td>
							<td align="center" valign="middle"><strong>Problemas</strong></td>
							<td width="395" height="23" align="center" valign="middle"><strong>Soluciones
									Adoptadas</strong></td>
							<td width="378" align="center" valign="middle"><strong>
									Resultados</strong></td>
						</tr>
    <?php
    
    $iRs = $objInf->ListaProblemasSoluciones($idProy, $idAnio, $idMes, $idVs);
    $RowIndex = 0;
    $t20_dificul = "";
    $t20_program = "";
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            if ($row['t20_dificul'] != "") {
                $t20_dificul = $row['t20_dificul'];
            }
            if ($row['t20_program'] != "") {
                $t20_program = $row['t20_program'];
            }
            ?>

     <tr>
							<td width="26" align="center" valign="middle"><?php echo($row['t20_cod_prob']);?></td>
							<td width="404" align="left" valign="middle"><?php echo($row['t20_problemas']);?></td>
							<td align="center"><?php echo($row['t20_soluciones']);?></td>
							<td align="center"><?php echo($row['t20_resultados']);?></td>
						</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Problemas y soluciones
    
    ?>
    </tbody>
					<tfoot>
					</tfoot>
				</table>
				<table width="99%" cellpadding="0" cellspacing="0">
					<thead>
					</thead>
					<tbody class="data" bgcolor="#FFFFFF">
						<tr>
							<td height="49" align="left" valign="middle"><span
								style="font-weight: bold; font-size: 12px;">4.2 Dificultades y
									otros aspectos</span> <br>
  <?php echo($t20_dificul);?></td>
						</tr>
						<tr>
							<td align="left" valign="middle"><span
								style="font-weight: bold; font-size: 12px;"><?php echo("4.3 Programación del mes siguiente (resaltando actividades de mayor relevancia)");?></span>
								<br>
      <?php echo($t20_program);?></td>
						</tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<p>&nbsp;</p>

				<table width="99%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td width="82%" class="TableEditReg">&nbsp;</td>
						<td width="8%" rowspan="2" align="center" class="TableEditReg"><div
								style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
								title="Refrescar Anexos">
								<br />
							</div></td>
						<td width="10%" rowspan="2" align="right" valign="middle">&nbsp;</td>
					</tr>
					<tr>
						<td align="left"><span style="font-weight: bold;">Anexos en el Mes</span></td>
					</tr>
				</table>
				<div id="divTableLista" class="TableGrid">
					<table width="99%" border="0" cellpadding="0" cellspacing="0">
						<thead>
						</thead>
						<tbody class="data" bgcolor="#FFFFFF">
							<tr class="SubtitleTable"
								style="border: solid 1px #CCC; background-color: #eeeeee;">
								<td width="34" align="center" valign="middle">&nbsp;</td>
								<td width="226" align="center" valign="middle"><strong>Nombre
										del Archivo</strong></td>
								<td width="490" height="23" align="center" valign="middle"><strong>Descripcion
										del Archivo</strong></td>
							</tr>
    <?php
    $iRs = $objInf->ListaAnexosFotograficos($idProy, $idAnio, $idMes, $idVs);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            ?>

     <tr>
       <?php
            $urlFile = $row['t20_url_file'];
            $filename = $row['t20_nom_file'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_mes";
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            $j = 1;
            ?>
       <td align="center" valign="middle"><?php echo($j);?></td>
								<td height="30" align="left" valign="middle"><a
									href="<?php echo($href);?>" title="Descargar Archivo"
									target="_blank"><?php echo($row['t20_nom_file']);?></a></td>
								<td align="left" valign="middle"><?php echo( $row['t20_desc_file']);?></td>
							</tr>
     <?php
            $RowIndex ++;
            $j ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>

    </tbody>
						<tfoot>
							<tr>
								<td colspan="3" align="center" valign="middle">&nbsp; <iframe
										id="ifrmUploadFile" name="ifrmUploadFile"
										style="display: none;"></iframe>
								</td>
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