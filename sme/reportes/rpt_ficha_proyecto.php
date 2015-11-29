<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$all = $objFunc->__Request('all');
$ls_filter = "";

?>


<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Ficha del Proyecto</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />

</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">
			
<?php
$numRows = 0;
$dir = null;
$objRep = new BLReportes();

if (isset($all)) {
    $rowD = $objRep->RepFichaProyAll("", "", 1);
} else {
    $rowD = $objRep->RepFichaProyAll($idProy, $idVersion, "");
}
while ($row = mysqli_fetch_assoc($rowD)) {
	
    $dir[$numRows] = $row;
    $numRows ++;
}

for ($x = 0; count($dir) > $x; $x ++) {
    $row = $dir[$x];
    ?>
<table width="700" align="center" cellpadding="0" cellspacing="0">
				<thead>

				</thead>

				<tbody class="data" bgcolor="#FFFFFF">

					<tr>
						<td width="24%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Código del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
					</tr>
					<tr>
						<td width="24%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Nro Expediente</strong></td>
						<td colspan="3" align="left" valign="middle"><strong><?php echo($row['t02_nro_exp']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Nombre
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Estado</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['estado']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Aprobación(Segun convenio)</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fch_apro']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Inicio(Segun convenio)</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fch_ini']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>N°
								de meses</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_num_mes']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Inicio Real</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fch_ire']));?></td>
					</tr>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>N°
								de Meses por Ampliación</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_num_mes_amp']));?></td>
					</tr>

					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Termino Real</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fch_tre']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Termino por Ampliación</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fch_tam']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Sector</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['sector_main']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Sub Sector</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['sector']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Producto Principal</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['subsector']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Producto Promovido</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['prod_promovido']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Ciudad</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['ciudad']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Direccion</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['direccion']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Telefono</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_tele_proy']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fax</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fax_proy']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Email</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_mail_proy']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Institución
								Ejecutora</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t01_sig_inst']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Fundación</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['t01_fch_fund']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Presupuesto
								Promedio Anual</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['presup_prom_anual'],2));?></td>
					</tr>
					<?php /* ?>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Instituciones
								Colaboradores o asociadas</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['inst_colabora']);?></td>
					</tr>
					<?php */ ?>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Gestor del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_tema']);?></td>
					</tr>
					<?php /* ?>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Monitor
								Financiero</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_fina']);?></td>
					</tr>
					<?php */ ?>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Supervisor 
								Externo</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_exte']);?></td>
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
    $rsAmbito = $objProy->AmbitoGeo_Listado($row['t02_cod_proy'], $row['t02_version']);
    $rowspan = mysqli_num_rows($rsAmbito);
    if ($rowspan == 0) {
        $rowspan = 1;
    }
    ?>
    <tr style="font-size: 11px;">
						<td rowspan="<?php echo($rowspan+2);?>" align="left"
							valign="middle" bgcolor="#E8E8E8"><strong>ámbito de Ejecución
								del Proyecto</strong></td>
						<td colspan="3" align="center" valign="middle"
							style="display: none;">&nbsp;</td>
					</tr>

					<tr style="font-size: 11px;">
						<td width="24%" height="23" align="center" valign="middle"
							bgcolor="#E8E8E8"><strong>Departamento</strong></td>
						<td width="26%" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Provincia</strong></td>
						<td width="28%" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Distrito</strong></td>
					</tr>
        <?php
    $idx = 0;
    while ($r = mysqli_fetch_assoc($rsAmbito)) {
        $idx ++;
        ?>
        <tr style="font-size: 11px;">
						<td height="25" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dpto']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $r['prov']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dist']);?></td>
					</tr>
    	<?php
    }
    $rsAmbito->free();
    ?>
        
        <?php if($idx==0) { ?>
         <tr style="font-size: 11px;">
						<td height="25" align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap">&nbsp;&nbsp;</td>
					</tr>
        <?php } ?>
        
    <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Duración
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['duracion']." Años");?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Presupuesto
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($row['t02_pres_tot'],2));?>
      </div>
						</td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Monto
								Solicitado a Fondoempleo</strong></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($row['t02_pres_fe'],2));?>
      </div>
						</td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Aportes
								de Contrapartida</strong></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($row['aportes_contra'],2));?>
      </div>
						</td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle">&nbsp;</td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fuentes
								de Financiamiento</strong></td>
					</tr>
    <?php
    $rsFuentes = $objRep->RepFichaProy_Fuentes($row['t02_cod_proy'], 1); // $idVersion);
    $rowFuentes[] = null;
    $contador = 0;
    while ($rfte = mysqli_fetch_assoc($rsFuentes)) {
        $rowFuentes[$contador] = $rfte;
        $contador ++;
    }
    
    $tot = 0;
    for ($i = 0; $contador > $i; $i ++) {
        $rfte = $rowFuentes[$i];
        $tot += $rfte['monto'];
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><?php echo($rfte['t01_sig_inst']);?></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($rfte['monto'],2)); ?>
      </div>
						</td>
					</tr>
	<?php
    }
    $rsFuentes->free();
    ?>
    
    <tr style="font-size: 11px;">
						<td align="left" valign="middle"><strong>Total de Aportes</strong></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
								<strong><?php echo(number_format($tot,2));?></strong>
							</div>
						</td>
					</tr>


					<tr style="font-size: 11px;">
						<th colspan="4" align="left" valign="top">&nbsp;</th>
					</tr>

					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Documentos
								Anexos del Proyecto</strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle">
							<table width="750" border="0" cellpadding="0" cellspacing="0">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
									<tr class="SubtitleTable"
										style="border: solid 1px #CCC; background-color: #eeeeee;">
										<td width="192" align="center" valign="middle" nowrap="nowrap"><strong>Tipo
												de Anexo</strong></td>
										<td width="245" align="center" valign="middle" nowrap="nowrap"><strong>Nombre
												del Archivo</strong></td>
										<td width="272" height="23" align="center" valign="middle"><strong>Descripcion
												del Archivo</strong></td>
									</tr>
          <?php
    $objProy = new BLProyecto();
    $iRs = $objProy->ListaAnexos($row['t02_cod_proy'], $row['t02_version']);
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>
          <tr>
            <?php
        $urlFile = $row['t02_url_file'];
        $filename = $row['t02_nom_file'];
        $path = constant('APP_PATH') . "sme/proyectos/datos/anexos/";
        $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
        ?>
            <td height="30" align="center" valign="middle"><?php echo( $row['t02_anx_tip_desc']);?> &nbsp;</td>
										<td height="30" align="center" valign="middle"><a
											href="<?php echo($href);?>" title="Descargar Archivo"
											target="_blank"><?php echo($row['t02_nom_file']);?></a></td>
										<td align="left" valign="middle"><?php echo( $row['t02_desc_file']);?></td>
									</tr>
          <?php
    }
    $iRs->free();
    ?>
             <tr>
										<td height="30" align="center" valign="middle">&nbsp;</td>
										<td height="30" align="center" valign="middle">&nbsp;</td>
										<td align="left" valign="middle">&nbsp;</td>
									</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</td>
					</tr>

				</tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="3" align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<p>
				<br />
			</p>

<?php

}
$rowD->free();
?>






<p>
				<br />
			</p>








			<?php /* <div id="xlsCustom" style="display: none;"> */ ?>
			<?php /* ?>
			<div id="DisabledxlsCustom" style="display: none;">
				<div class="TableGrid">
  <?php

$objRep = new BLReportes();
for ($x = 0; count($dir) > $x; $x ++) {
    $row = $dir[$x];
    
    $rsFuentes = $objRep->RepFichaProy_Fuentes_clasf($row['t02_cod_proy'], 1, $row['t01_id_inst']); // $idVersion);
    $rowFuentes[] = null;
    $contador = 0;
    while ($rfte = mysqli_fetch_assoc($rsFuentes)) {
        $rowFuentes[$contador] = $rfte;
        $contador ++;
    }
    ?>
	
	
<table width="700" align="center" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<td colspan="20" align="center">Datos Generales</td>
								<td colspan="4" align="center">Detalles</td>
								<td colspan="2" align="center">Supervicion</td>
								<td colspan="5" align="center">Ubicacion</td>
		<?php if($contador>0) { echo "<td colspan='3' align='center'>Fuentes de Financiamiento</td>"; }?>
		<td colspan="3" align="center">Documentos Anexos del Proyecto</td>

							</tr>
							<tr>
								<td>Expediente</td>
								<td>Codigo</td>
								<td>Nombre del Proyecto</td>
								<td>Ejecutor</td>
								<td>Estado</td>
								<td>Fecha Aprobacion</td>
								<td>Fecha Inicio</td>
								<td>Fecha Inicio Real</td>
								<td>N° de Meses</td>
								<td>Fecha Termino Real</td>
								<td>N° de Meses por Ampliación</td>
								<td>Fecha Termino por Ampliacion</td>
								<td>Presupuesto Promedio Anual</td>
								<td>Instituciones colaboradores</td>
								<td>Duracion Proyecto</td>
								<td>Presupuesto del Proyecto</td>
								<td>Monto solicitado a fondoempleo</td>
								<td>Aportes de Contrapartida</td>
								<td>Objetivo del Proyecto</td>
								<td>Proposito del Proyecto</td>

								<td>Poblacion Beneficiaria</td>
								<td>Ambito Geografico</td>
								<td>Sector Productivo</td>
								<td>Sub Sector</td>

								<td>Gestor de Proyecto</td>
								<?php // <td>Monitor Financiero</td>  ?>
								<td>Supervisor Externo</td>

								<td>Direccion</td>
								<td>Ciudad</td>
								<td>Telefono</td>
								<td>Fax</td>
								<td>Mail</td>
		
		<?php
    
    for ($i = 0; $i < $contador; $i ++) {
        $rfte = $rowFuentes[$i];
        if ($i < 2) {
            ?>
				<td><?php echo($rfte['t01_sig_inst']);?></td>
		<?php
        
} else {
            echo "<td>Otros Aportes</td>";
            break;
        }
    }
    ?>
		<td>Tipo de Anexo</td>
								<td>Nombre del Archivo</td>
								<td>Descripcion del Archivo</td>
							</tr>
						</thead>

						<tbody class="data" bgcolor="#FFFFFF">

							<tr>

								<td align="left" valign="middle"><strong><?php echo($row['t02_nro_exp']);?></strong></td>
								<td align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t01_sig_inst']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['estado']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fch_apro']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fch_ini']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_num_mes']));?></td>

								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fch_ire']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_num_mes_amp']));?></td>

								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fch_tre']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fch_tam']));?></td>
								<td align="left" valign="middle"><?php echo(number_format($row['presup_prom_anual'],2));?></td>
								<td align="left" valign="middle"><?php echo($row['inst_colabora']);?></td>
								<td align="left" valign="middle"><?php echo($row['duracion']." Años");?></td>
								<td align="left" valign="middle">
									<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($row['t02_pres_tot'],2));?>
      </div>
								</td>
								<td align="left" valign="middle">
									<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($row['t02_pres_fe'],2));?>
      </div>
								</td>
								<td align="left" valign="middle">
									<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($row['aportes_contra'],2));?>
      </div>
								</td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fin']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_pro']));?></td>


								<td align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
								<td align="left" valign="middle"></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['sector']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['subsector']));?></td>
								<td align="left" valign="middle"><?php echo($row['moni_tema']);?></td>
								<?php  
								//// <td align="left" valign="middle"><?php echo($row['moni_fina']);?></td>
								 ?>
								<td align="left" valign="middle"><?php echo($row['moni_exte']);?></td>

								<td align="left" valign="middle"><?php echo(nl2br($row['direccion']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['ciudad']));?></td>

								<td align="left" valign="middle"><?php echo(nl2br($row['t02_tele_proy']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_fax_proy']));?></td>
								<td align="left" valign="middle"><?php echo(nl2br($row['t02_mail_proy']));?></td>
	 <?php
    
    $tot = 0;
    for ($i = 0; $contador > $i; $i ++) {
        $rfte = $rowFuentes[$i];
        if ($i < 2) {
            ?>
		<td align="left" valign="middle"><?php echo(nl2br($rfte['monto']));?></td>
	<?php
        } else {
            if ($i == 2) {
                echo "<td align='left' valign='middle'>";
            }
            echo $rfte['t01_sig_inst'] . ": " . $rfte['monto'] . "<br>";
            if ($i == ($contador - 1)) {
                echo "</td>";
            }
        }
    }
    
    ?>
	  
	    <?php
    $objProy = new BLProyecto();
    $iRs = $objProy->ListaAnexos($row['t02_cod_proy'], $row['t02_version']);
    $contador = 0;
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>

            <?php
        $urlFile = $row['t02_url_file'];
        $filename = $row['t02_nom_file'];
        $path = constant('APP_PATH') . "sme/proyectos/datos/anexos/";
        $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=".$urlFile."&path=".$path;
               ?>
			 
            <td height="30" align="center" valign="middle"><?php echo( $row['t02_anx_tip_desc']);?> &nbsp;</td>
								<td height="30" align="center" valign="middle"><a
									href="<?php echo($href);?>" title="Descargar Archivo"
									target="_blank"><?php echo($row['t02_nom_file']);?></a></td>
								<td align="left" valign="middle"><?php echo( $row['t02_desc_file']);?></td>
	   <?php 
	   break;
	   } 
	   
	   }
?>
	  
	  
     </tr>







						</tbody>
						<tfoot>
							<tr>
								<td align="left" valign="middle">&nbsp;</td>
								<td colspan="37" align="left" valign="middle">&nbsp;</td>
							</tr>
						</tfoot>
					</table>
					<p>
						<br />
					</p>
				</div>
			</div>
			
			
			<?php */ ?>
			
			
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>