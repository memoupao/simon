<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');

// $t02_periodo= $objFunc->__Request('t02_periodo');
$t02_estado = $objFunc->__Request('t02_estado');

$objPOA = new BLPOA();

$rpoa = $objPOA->POA_Seleccionar($idProy, $idAnio);
$t02_periodo = $rpoa['t02_periodo'];

$objProy = new BLProyecto();

$VerPOA = $objPOA->UltimaVersionPoa($idProy, $idAnio);
$idVersion = $VerPOA;

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);

?>

<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cronograma Anual</title>
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

			<table width="840" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th align="left">&nbsp;</th>
					<td colspan="3">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th width="21%" height="18" align="left">CODIGO DEL PROYECTO</th>
					<td colspan="3" align="left"><?php echo($ML['t02_cod_proy']);?></td>
					<td width="2%" align="left">&nbsp;</td>
					<th width="7%" align="left" nowrap="nowrap">INICIO</th>
					<td width="13%" align="left"><?php echo($ML['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th height="18" align="left" nowrap="nowrap">DESCRIPCION DEL
						PROYECTO</th>
					<td colspan="3" align="left"><?php echo($ML['t02_nom_proy']);?></td>
					<td align="left">&nbsp;</td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($ML['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th height="18" align="left" valign="top">PERIODO</th>
					<td width="32%" align="left" valign="top"><?php echo($t02_periodo);?></td>
					<td width="7%" align="left" valign="top"><strong>ESTADO</strong></td>
					<td width="18%" align="left" valign="top"><?php echo($t02_estado);?></td>
					<td width="2%" align="left" valign="top">&nbsp;</td>
					<td width="7%" align="left" valign="top"><strong>AÑO</strong></td>
					<td align="left" valign="top"><?php echo($idAnio);?></td>
				</tr>

				<tr>
					<th align="left">&nbsp;</th>
					<td colspan="3">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			
			</table>
			<table width="840" border="0" cellpadding="0" cellspacing="0">
				<thead style="font-size: 11px;">
					<tr>
						<td align="center" valign="middle">&nbsp;</td>
						<td width="213" height="28" align="center" valign="middle">AÑO</td>
						<td width="38" rowspan="4" align="center" valign="middle">Unidad
							de Medida</td>
						<td width="40" rowspan="4" align="center" valign="middle">Meta
							Fisica</td>
						<td colspan="12" align="center" valign="middle">AÑO <?php echo($idAnio);?></td>
					</tr>
					<tr>
						<td align="center" valign="middle">&nbsp;</td>
						<td height="28" align="center" valign="middle">TRIMESTRE</td>
						<td colspan="3" align="center" valign="middle">1</td>
						<td colspan="3" align="center" valign="middle">2</td>
						<td colspan="3" align="center" valign="middle">3</td>
						<td colspan="3" align="center" valign="middle">4</td>
					</tr>
					<tr>
						<td width="50" rowspan="2" align="center" valign="middle">&nbsp;</td>
						<td rowspan="2" align="center" valign="middle">MES</td>
						<td width="50" height="50" align="center" valign="middle">1</td>
						<td width="50" align="center" valign="middle">2</td>
						<td width="50" align="center" valign="middle">3</td>
						<td width="50" align="center" valign="middle">4</td>
						<td width="50" align="center" valign="middle">5</td>
						<td width="50" align="center" valign="middle">6</td>
						<td width="50" align="center" valign="middle">7</td>
						<td width="50" align="center" valign="middle">8</td>
						<td width="50" align="center" valign="middle">9</td>
						<td width="50" align="center" valign="middle">10</td>
						<td width="50" align="center" valign="middle">11</td>
						<td width="52" align="center" valign="middle">12</td>
					</tr>
     <?php
    $irsPeriodo = $objProy->PeriodosxAnio($idProy, $idAnio);
    $arrPeriodo = NULL;
    $cont = 1;
    while ($r = mysqli_fetch_assoc($irsPeriodo)) {
        $arrPeriodo[$cont] = $r;
        $cont ++;
    }
    $irsPeriodo->free();
    ?> 
     <tr class="RowData">
						<td align="center" valign="middle"><?php echo($arrPeriodo[1]['nom_abrev']." ".$arrPeriodo[1]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[2]['nom_abrev']." ".$arrPeriodo[2]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[3]['nom_abrev']." ".$arrPeriodo[3]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[4]['nom_abrev']." ".$arrPeriodo[4]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[5]['nom_abrev']." ".$arrPeriodo[5]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[6]['nom_abrev']." ".$arrPeriodo[6]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[7]['nom_abrev']." ".$arrPeriodo[7]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[8]['nom_abrev']." ".$arrPeriodo[8]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[9]['nom_abrev']." ".$arrPeriodo[9]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[10]['nom_abrev']." ".$arrPeriodo[10]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[11]['nom_abrev']." ".$arrPeriodo[11]['num_anio']);?></td>
						<td align="center" valign="middle"><?php echo($arrPeriodo[12]['nom_abrev']." ".$arrPeriodo[12]['num_anio']);?></td>
					</tr>

				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
	<?php

$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
while ($rowcomp = mysql_fetch_assoc($rsComp)) {
    ?>
    
      <tr class="RowData" style="background-color: #D7DC78;">
						<td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp']);?></td>
						<td align="left"><?php echo($rowcomp['t08_comp_desc']);?></td>
						<td align="center">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>

					</tr>
    
  
      
	  <?php
    $rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
    while ($rowact = mysql_fetch_assoc($rsAct)) {
        ?>
      <tr class="RowData" style="background-color: #EEF8AD;">
						<td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act']);?></td>
						<td align="left"><?php echo($rowact['t09_act']);?></td>
						<td align="center">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
      
      <?php
        $rsSubAct = $objPOA->ListadoSubActividades($idProy, $idVersion, $rowcomp['t08_cod_comp'], $rowact['t09_cod_act']);
        while ($rowsub = mysqli_fetch_assoc($rsSubAct)) {
            ?>
      <tr class="RowData"
						<?php if ($rowsub["act_add"]=='1'){echo ("style='color:green; font-weight:bold'");}?>>
						<td align="left" nowrap="nowrap"><?php echo($rowcomp['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'.'.$rowsub['t09_cod_sub']);?></td>
						<td align="left"><?php echo($rowsub['t09_sub']);?></td>
						<td align="center"><?php echo($rowsub['t09_um']);?></td>
						<td align="center" valign="middle"
							style="background-color: #eeeeee;"><?php echo($rowsub['t09_mta']);?></td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_1']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_2']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_3']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_4']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_5']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_6']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_7']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_8']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_9']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_10']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_11']);?>&nbsp;</td>
						<td align="center" valign="middle"><?php echo($rowsub['mes_'.$idAnio.'_12']);?>&nbsp;</td>
					</tr>
      <?php } $rsSubAct->free(); //Fin SubActividades ?>
      <?php } //Fin Actividades ?>
      <?php } //Fin Componentes ?>
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
					</tr>
				</tfoot>
			</table>

			<br />
			<p>
				<script language="JavaScript" type="text/javascript">
  </script>
			</p>


			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>