<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant('PATH_CLASS') . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$objMP = new BLManejoProy();
$objPOA = new BLPOA();

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio') ? $objFunc->__Request('idAnio') : $objFunc->__Request('anio');
$idVersion = $objFunc->__Request('idVersion') ? $objFunc->__Request('idVersion') : $idAnio + 1;

$vs = 1;

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);

$objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVersion);

$rowInfPoa = $objPOA->POA_Seleccionar($idProy, $idAnio);

$objProy = new BLProyecto();
$rsSector = $objProy->SectoresProductivos_Listado($idProy);

$aCostosDirectos = $objMP->GetCostosDirectos($idProy, $idVersion);
$aCostosIndirectos = $objMP->GetCostosInDirectos($idProy, $idVersion);

?>

<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Plan Operativo</title>
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
			<table width="700" cellpadding="0" cellspacing="0">


				<tbody class="data" bgcolor="#FFFFFF">

					<tr>
						<td width="24%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>POA Correspondiente al
								año</strong></td>
						<td colspan="2" align="left" valign="middle"><strong>Año <?php echo($idAnio);?></strong>&nbsp;</td>
						<td colspan="2" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Código
								del Proyecto</strong></td>
						<td width="34%" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Periodo
								Referencia</strong></td>
						<td colspan="5" align="left" valign="middle"><strong><?php echo($rowInfPoa['t02_periodo']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Presentación</strong></td>

						<td colspan="5" align="left" valign="middle"><strong>
        <?php echo (date("d-m-Y",strtotime($rowInfPoa['fch_crea'])));?>		 
		 </strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#FFFFAA"><strong>Nombre
								del Jefe del Proyecto</strong></td>
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
			<table width="800" border="0" cellpadding="0" cellspacing="0">

				<tr bgcolor="#CCCCFF">
					<td colspan="11" align="left" valign="middle"><strong
						style="color: blue;">1. COSTOS OPERATIVOS</strong></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td rowspan="2" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="190" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Categoria de Gastos</td>
					<td width="62" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="37" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cant.</td>
					<td width="46" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unit.</td>
					<td width="62" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Parcial</td>
					<td width="42" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="64" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td height="26" colspan="3" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td height="17" align="center" style="border: solid 1px #CCC;">FE</td>
					<td align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td align="center" style="border: solid 1px #CCC;">Otras Fuentes</td>
				</tr>
  <?php
$objML = new BLMarcoLogico();
$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
$nrAct = mysql_num_rows($rsComp);

while ($rsCom = mysql_fetch_assoc($rsComp)) {
    $idComp = $rsCom['t08_cod_comp'];
    ?>
    <tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
					<td height="33" colspan="11" align="left" valign="middle"><div
							style="display: inline-block;">
							<strong>Componente&nbsp;</strong>
						</div>
						<div style="display: inline-block;">
          <?php
    echo $rsCom['descripcion'];
    ?>
        </div></td>
				</tr>
				<tbody class="data">
      	<?php
    // $objPresup = new BLManejoProy();
    $objPresup = new BLPresupuesto();
    
    $aRs = $objPresup->ListaActividades($idProy, $idVersion, $idComp);
    
    if ($aRs->num_rows > 0) {
        while ($ract = mysqli_fetch_assoc($aRs)) {
            $idAct = $ract['t09_cod_act'];
            $sum_total += $ract["ctototal"];
            $sum_fte_fe += $ract["fte_fe"];
            $sum_fte_otro += $ract["otros"];
            $sum_ejecutor += $ract["ejecutor"];
            ;
            
            ?>           
      	<tr class="RowData" style="background-color: #FC9;">
						<td align="left"><?php echo($ract["codigo"]);?></td>
						<td colspan="4" align="left"><?php echo($ract["actividad"]);?></td>
						<td align="right"><?php echo( number_format($ract["ctoparcial"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format(($ract["ctototal"]),2));?></td>
						<td width="88" align="right"><?php echo(number_format($ract["fte_fe"],2));?></td>
						<td width="70" align="right"><?php echo(number_format($ract["ejecutor"],2));?></td>
						<td width="64" align="right"><?php echo(number_format($ract["otros"],2));?></td>
					</tr>
      <?php
            
            $iRs = $objPresup->ListaSubActividades($idProy, $idVersion, $idComp, $idAct);
            while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
      <tr class="RowData" style="background-color: #E3FEE0;">
						<td align="left"><?php echo($row["codigo"]);?></td>
						<td align="left"><?php echo($row["subactividad"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($row["ctoparcial"],2));?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format(($row["ctototal"]),2));?></td>
						<td width="88" align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td width="70" align="right"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td width="64" align="right"><?php echo(number_format($row["otros"],2));?></td>
					</tr>
      <?php
                $iRsCateg = $objPresup->ListaSubActividadesCateg($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_sub"]);
                while ($rowcateg = mysqli_fetch_assoc($iRsCateg)) {
                    ?>
      <tr class="RowData" style="background-color: #FFF; color: #036;">
						<td align="left"><?php echo($rowcateg["codigo"]);?></td>
						<td align="left" style="font-weight: bold;"><?php echo($rowcateg["categoria"]);?></td>
						<td align="center"><?php echo($rowcateg["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($rowcateg["ctoparcial"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format(($rowcateg["ctototal"]),2));?></td>
						<td width="88" align="right"><?php echo(number_format($rowcateg["fte_fe"],2));?></td>
						<td width="70" align="right"><?php echo(number_format($rowcateg["ejecutor"],2));?></td>
						<td width="64" align="right"><?php echo(number_format($rowcateg["otros"],2));?></td>
					</tr>
      <?php
                    $iRsGasto = $objPresup->ListaSubActividadesCosteo($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_sub"], $rowcateg['t10_cate_cost']);
                    while ($rowgasto = mysqli_fetch_assoc($iRsGasto)) {
                        ?>
      <tr class="RowData" style="background-color: #FFF;">
						<td align="center" nowrap="nowrap"><span> </span></td>
						<td align="left"><?php echo($rowgasto["t10_cost"]);?></td>
						<td align="center"><?php echo($rowgasto["t10_um"]);?></td>
						<td align="center"><?php echo(round($rowgasto["t10_cant"],2));?></td>
						<td align="right"><?php echo( number_format($rowgasto["t10_cu"],2));?></td>
						<td align="right"><?php echo( number_format($rowgasto["t10_cost_parc"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format(($rowgasto["t10_cost_tot"]),2));?></td>
						<td width="88" align="right"><?php echo(number_format($rowgasto["fte_fe"],2));?></td>
						<td width="70" align="right"><?php echo(number_format($rowgasto["ejecutor"],2));?></td>
						<td width="64" align="right"><?php echo(number_format($rowgasto["otros"],2));?></td>
					</tr>
      <?php
                    }
                    $iRsGasto->free();
                }
                $iRsCateg->free();
                ?>
      <?php
            
} // End While
            $iRs->free();
        }
        $aRs->free();
    }     // End If
    else {
        ?>
      <tr class="RowData">
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
				</tbody>
      <?php
    }
} /* cerrar componente */
?>     
    <tfoot>
					<tr style="font-size: 11px;">
						<th width="35" height="18">&nbsp;</th>
						<th width="190">&nbsp;</th>
						<th width="62">&nbsp;</th>
						<th width="37">&nbsp;</th>
						<th width="46">&nbsp;</th>
						<th width="62">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			<table width="800" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="11" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="11" align="left" valign="middle"><strong
						style="color: blue;">2. MANEJO DEL PROYECTO</strong></td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td colspan="11" height="23" align="left" valign="middle"><strong>2.1.
							Personal Proyecto</strong></td>
				</tr>       
     <?php
    $iRs = $objMP->Personal_Listado($idProy, $idVersion);
    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;
    if ($iRs->num_rows == 0) {
        ?>
        <tr>
					<td colspan="11" align="left" valign="middle" style="color: #F00">No
						existen datos</td>
				</tr>
				<tr>
					<td colspan="11" align="left" valign="middle">&nbsp;</td>
				</tr> 
         <?php
    } else {
        ?>   
      <tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="31" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="82" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cod.</td>
					<td width="259" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cargo Personal</td>
					<td width="89" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="95" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="97" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unitario</td>
					<td width="88" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td colspan="3" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="93" height="20" align="center"
						style="border: solid 1px #CCC;">FE</td>
					<td width="97" align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td width="99" align="center" style="border: solid 1px #CCC;">Otras
						Fuentes</td>
				</tr>
				<tbody class="data">
      <?php
        
        if ($iRs->num_rows > 0) {
            $c = 0;
            while ($row = mysqli_fetch_assoc($iRs)) {
                $sum_total += $row["gasto_tot"];
                $sum_fte_fe += $row["fte_fe"];
                $sum_fte_otro += $row["otros"];
                $sum_ejecutor += $row["ejecutor"];
                $c ++;
                ?>
        <tr class="RowData" style="background-color: #FFF;">
						<td nowrap="nowrap"><span><?php echo $c;?></span></td>
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left">  <?php echo($row["cargo"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format($row["promedio"],2));?></td>
						<td align="right"><?php echo( number_format($row["gasto_tot"],2));?></td>
						<td align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td align="right"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td align="right"><?php echo(number_format($row["otros"],2));?></td>

					</tr>
        <?php
            
} // End While
            $iRs->free();
        }         // End If
        else {
            ?>
      <tr class="RowData">
						<td nowrap="nowrap">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>

					</tr>
      <?php } ?>
    </tbody>
				<tfoot>
					<tr style="font-size: 11px;">
						<th width="31" height="18">&nbsp;</th>
						<th width="82">&nbsp;</th>
						<th width="259">&nbsp;</th>
						<th width="89">&nbsp;</th>
						<th width="95">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?></th>

					</tr>
				</tfoot>
 <?php
    
}
    ?>   
	</table>
			<table width="800" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td colspan="11" height="23" align="left" valign="middle"><strong>2.2.
							Equipamiento Proyecto</strong></td>
				</tr> 
      
           <?php
        $iRs = $objMP->Equipamiento_Listado($idProy, $idVersion);
        $sum_total = 0;
        $sum_fte_fe = 0;
        $sum_fte_otro = 0;
        $sum_ejecutor = 0;
        if ($iRs->num_rows == 0) {
            ?>
        <tr>
					<td colspan="11" align="left" valign="middle" style="color: #F00">No
						existen datos</td>
				</tr>
				<tr>
					<td colspan="11" align="left" valign="middle">&nbsp;</td>
				</tr> 
         <?php
        } else {
            ?>  
     
     
      <tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="33" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;</td>
					<td width="86" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cod.</td>
					<td width="252" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Nombre de Equipo / Bien</td>
					<td width="90" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="97" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="96" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unitario</td>
					<td width="86" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td colspan="3" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="93" align="center" style="border: solid 1px #CCC;">FE</td>
					<td width="101" align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td width="96" align="center" style="border: solid 1px #CCC;">Otras
						Fuentes</td>
				</tr>
				<tbody class="data">
      <?php
            if ($iRs->num_rows > 0) {
                $e = 1;
                while ($row = mysqli_fetch_assoc($iRs)) {
                    $sum_total += $row["total"];
                    $sum_fte_fe += $row["fte_fe"];
                    $sum_fte_otro += $row["otros"];
                    $sum_ejecutor += $row["ejecutor"];
                    $e ++;
                    ?>
      <tr class="RowData" style="background-color: #FFF;">
						<td nowrap="nowrap"><span><?php echo $e;?>
        </span></td>
						<td align="center"><?php echo($row["codigo"]);?></td>
						<td align="left">  <?php echo($row["equipo"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format($row["costo"],2));?></td>
						<td align="right"><?php echo( number_format(($row["total"]),2));?></td>
						<td align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td align="right"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td align="right"><?php echo(number_format($row["otros"],2));?></td>
					</tr>
        <?php
                
} // End While
                $iRs->free();
            }             // End If
            else {
                ?>
      <tr class="RowData">
						<td nowrap="nowrap">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
      <?php } ?>
    </tbody>
				<tfoot>
					<tr style="font-size: 11px;">
						<th width="33" height="18">&nbsp;</th>
						<th width="86">&nbsp;</th>
						<th width="252">&nbsp;</th>
						<th width="90">&nbsp;</th>
						<th width="97">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?>&nbsp;</th>
					</tr>
				</tfoot>
   <?php
        
}
        ?> 
    </table>
			<table width="800" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td colspan="11" height="23" align="left" valign="middle"><strong>2.3.
							Gastos Funcionamiento</strong></td>
				</tr>
				<tr bgcolor="#D9DAE8" style="background-color: #A9D0F5;">
					<td height="20" colspan="11" align="left" valign="middle"><div
							style="display: inline-block;">
							<div style="text-align: justify; font-size: 11px; color: #F00;">
								Gastos necesarios para el normal funcionamiento de los
								vehículos y los equipos, además de los pasajes y viáticos
								para las acciones de supervisión del mismo.</div>
						</div></td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">

					<td rowspan="2" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="231" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Categoria de Gastos</td>
					<td width="78" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="49" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Cant.</td>
					<td width="70" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Unit.</td>
					<td width="77" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Parcial</td>
					<td width="51" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Fisica</td>
					<td width="92" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Costo Total</td>
					<td colspan="4" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td align="center" style="border: solid 1px #CCC;">FE</td>
					<td align="center" style="border: solid 1px #CCC;">Ejecutor</td>
					<td align="center" style="border: solid 1px #CCC;">Otras Fuentes</td>
				</tr>

				<tbody class="data">
      <?php
    
    $iRs = $objMP->GastFunc_Listado($idProy, $idVersion);
    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sum_total += $row["total"];
            $sum_fte_fe += $row["fte_fe"];
            $sum_fte_otro += $row["otros"];
            $sum_ejecutor += $row["ejecutor"];
            
            ?>
        <tr class="RowData" style="background-color: #E3FEE0;">
						<td height="27" align="left"><?php echo($row["codigo"]);?></td>
						<td align="left">  <?php echo($row["partida"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($row["parcial"],2));?></td>
						<td align="center"><?php echo($row["meta"]);?></td>
						<td align="right"><?php echo( number_format(($row["total"]),2));?></td>
						<td width="102" align="right"><?php echo(number_format($row["fte_fe"],2));?></td>
						<td width="96" align="right"><?php echo(number_format($row["ejecutor"],2));?></td>
						<td width="106" align="right"><?php echo(number_format($row["otros"],2));?></td>
					</tr>
           <?php
            $iRsCateg = $objMP->GastFunc_ListadoCateg($idProy, $idVersion, $row['idpartida']);
            while ($rowcateg = mysqli_fetch_assoc($iRsCateg)) {
                ?>
   	    <tr class="RowData" style="background-color: #FFF; color: #036;">
						<td align="left"><?php echo($rowcateg["codigo"]);?></td>
						<td align="left" style="font-weight: bold;">  <?php echo($rowcateg["categoria"]);?></td>
						<td align="center"><?php echo($rowcateg["um"]);?></td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format($rowcateg["parcial"],2));?></td>
						<td align="center"><?php echo($rowcateg["meta"]);?></td>
						<td align="right"><?php echo( number_format(($rowcateg["total"]),2));?></td>
						<td width="102" align="right"><?php echo(number_format($rowcateg["fte_fe"],2));?></td>
						<td width="96" align="right"><?php echo(number_format($rowcateg["ejecutor"],2));?></td>
						<td width="106" align="right"><?php echo(number_format($rowcateg["otros"],2));?></td>
					</tr>
            <?php
                
                $iRsGasto = $objMP->GastFunc_ListadoCateg_Gasto($idProy, $idVersion, $row['idpartida'], $rowcateg['idcategoria']);
                while ($rowgasto = mysqli_fetch_assoc($iRsGasto)) {
                    ?>
                   <tr class="RowData" style="background-color: #FFF;">
						<td align="center" nowrap="nowrap"><span> </span></td>
						<td align="left">  <?php echo($rowgasto["gasto"]);?></td>
						<td align="center"><?php echo($rowgasto["um"]);?></td>
						<td align="center"><?php echo($rowgasto["t03_cant"]);?></td>
						<td align="right"><?php echo( number_format($rowgasto["t03_cu"],2));?></td>
						<td align="right"><?php echo( number_format($rowgasto["parcial"],2));?></td>
						<td align="center">&nbsp;</td>
						<td align="right"><?php echo( number_format(($rowgasto["total"]),2));?></td>
						<td width="102" align="right"><?php echo(number_format($rowgasto["fte_fe"],2));?></td>
						<td width="96" align="right"><?php echo(number_format($rowgasto["ejecutor"],2));?></td>
						<td width="106" align="right"><?php echo(number_format($rowgasto["otros"],2));?></td>
					</tr>
                   <?php
                }
                $iRsGasto->free();
            }
            $iRsCateg->free();
            ?>
        
        <?php
        
} // End While
        $iRs->free();
    }     // End If
    else {
        ?>
      <tr class="RowData">
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
      <?php } ?>
    </tbody>
				<tfoot>
					<tr style="font-size: 11px;">
						<th width="36">&nbsp;</th>
						<th width="231">&nbsp;</th>
						<th width="78">&nbsp;</th>
						<th width="49">&nbsp;</th>
						<th width="70">&nbsp;</th>
						<th width="77">&nbsp;</th>
						<th colspan="2" align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_fte_fe,2));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($sum_ejecutor,2));?></th>
						<th align="right"><?php echo(number_format($sum_fte_otro,2));?>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			<div id="divTableLista" class="TableGrid">
<?php

$objHC = new HardCode();
$iRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
$campos = $objMP->iGetArrayFields($iRs);
unset($campos[1]);
unset($campos[0]);
$numftes = count($campos);

$sumaTotal = 0;
?>
 <div class="TextDescripcion"></div>
				<table width="800" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="11" align="left" valign="middle">&nbsp;</td>
					</tr>
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td colspan="11" height="23" align="left" valign="middle"><strong>2.4.
								Costos Administrativos</strong></td>
					</tr>
					<tr>
						<td colspan="11" align="left" valign="middle">&nbsp;</td>
					</tr>
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td height="26" colspan="2" rowspan="2"
							style="border: solid 1px #CCC;">&nbsp;&nbsp;Componentes</td>
						<td width="89" rowspan="2" align="center"
							style="border: solid 1px #CCC;">Costo Total</td>
						<td height="28" colspan="<?php echo($numftes);?>" align="center">Financiamiento</td>
					</tr>
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
        <?php
        for ($col == 0; $col < $numftes; $col ++) {
            
            ?>
        <td width="60" height="25" align="center"
							style="border: solid 1px #CCC;"><?php echo($campos[$col+2]);?></td>
        <?php } ?>
      </tr>

					<tbody class="data">
      <?php
    
    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;
    // if($iRs->num_rows > 0)
    // {
    while ($row = mysqli_fetch_assoc($iRs)) {
        $sum_total += $row["costo_total"];
        
        ?>
      <tr class="RowData" style="background-color: #FFF;">
							<td colspan="2" align="left"><?php echo($row["codigo"]);?>  <?php echo($row["componente"]);?></td>
							<td align="right"><?php echo( number_format(($row["costo_total"]),2));?></td>
         <?php
        $col = 0;
        for ($col == 0; $col < $numftes; $col ++) {
            $field = $campos[$col + 2];
            $sum_fte[$col] += $row[$field];
            ?>
        <td align="right"><?php echo(number_format($row[$field],2));?>&nbsp;</td>
        <?php } ?>
        
        
        </tr>
        <?php
    
} // End While
    $iRs->free();
    // } // End If
    ?>
    </tbody>
					<tfoot>
						<tr style="font-size: 11px;">
							<th width="74" height="18">&nbsp;</th>
							<th width="473">&nbsp;</th>
							<th align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
        <?php
        $col = 0;
        for ($col == 0; $col < $numftes; $col ++) {
            $sumaTotal += $sum_fte[$col];
            ?>
        <th align="right"><?php echo(number_format($sum_fte[$col],2));?>&nbsp;</th>
        <?php } ?>
        
        
        </tr>
					</tfoot>
				</table>
				<table width="800" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td height="27" colspan="11" align="left" valign="middle"><div
									style="display: inline-block;">
									<strong>Gastos Administrativos necesarios para el adecuado
										funcionamiento del proyecto. <br\>El monto no podrá ser mayor
										al 8% de los costos y gastos directos de cada fuente.
									</strong>
								</div></td>
						</tr>
						<tr>
							<th width="6%" height="30" align="center" valign="middle">#</th>
							<th width="43%" align="center" valign="middle">Fuente de
								Financiamiento</th>
							<th width="17%" align="center" valign="middle" nowrap="nowrap">Monto
								Financiado</th>
							<th width="17%" align="center" valign="middle">Monto Maximo (8%)</th>
							<th width="17%" align="center" valign="middle" nowrap="nowrap">Monto
								Aportado</th>
						</tr>
					</thead>

					<tbody class="data">
						<tr style="background-color: #D6DFF8;">
							<td colspan="2" align="left" valign="middle"><strong>Costos de
									Administración Total</strong></td>
							<td align="right"><strong><?php echo(number_format($sumaTotal,2));?></strong></td>
							<td align="right"><strong><?php echo(number_format(($sumaTotal*$objHC->Porcent_Gast_Func/100),2));?></strong></td>
							<td align="right">&nbsp;</td>
						</tr>
          <?php
        $rsFte = $objMP->GastosAdm_Listado($idProy, $idVersion);
        $index = 1;
        $sumaAportado = 0;
        while ($rowFTE = mysqli_fetch_assoc($rsFte)) {
            ?>
          <tr class="RowData">
							<td align="center" valign="middle"><?php echo($index);?></td>
							<td align="left"><input name="txtInstit[]" id="txtInstit[]"
								type="hidden" value="<?php echo($rowFTE['codigo']);?>"
								class="GastosAdministrativos" /><?php echo($rowFTE['fuente']);?></td>
							<td align="right"><?php echo(number_format($rowFTE['financiado'],2));?></td>
							<td align="right"><?php echo(number_format($rowFTE['maximo'],2));?></td>
							<td align="right">
            <?php echo($rowFTE["costo"]);?></td>
						</tr>
          <?php
            
$sumaAportado += $rowFTE["costo"];
            $index ++;
        }
        ?>
          </tbody>
					<tfoot>
						<tr>
							<td align="center" valign="middle">&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right">&nbsp;</td>
							<td align="right">&nbsp;</td>
							<td align="right"><b id="bSumaTotal" style="text-align: right"><?php echo(number_format($sumaAportado,2));?></b></td>
						</tr>
					</tfoot>
				</table>

				<script language="javascript" type="text/javascript">
 function GuardarGastosADM()
  {
	 <?php $ObjSession->AuthorizedPage(); ?>	
	 var BodyForm = "txtCodProy=" + $("#txtCodProy").val() + "&cboversion=" + $('#cboversion').val()
	 BodyForm += "&" + $("#FormData .GastosAdministrativos").serialize() ;
	 
	 var sURL = "mp_gastos_adm_process.php?action=<?php echo(md5("save_gastos"));?>" ;
	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarGastosADM, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

	 return false;
  }
  
  function MySuccessGuardarGastosADM(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{
	 LoadGastoAdminis(true);	
	 alert(respuesta.replace(ret,""));
	}
	else
	{  alert(respuesta); }
  }
</script>

				<script>
function CalcularTotales()
{
   var sum = 0;
	$("input[montoADM='1']").each(function() {
		if(!isNaN(this.value) && this.value.length!=0) 
		  { 	sum += CNumber(this.value); }
		} );
	$('#bSumaTotal').html(sum.toFixed(2));
}
function CNumber(str)
{
  var numero =0;
  if (str !="" && str !=null)
  { numero = parseFloat(str);}
  if(isNaN(numero)) { numero=0;}
 return numero;
}

</script>
			</div>

<?php
$iRs->free();

$iRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
$campos = $objMP->iGetArrayFields($iRs);
unset($campos[1]);
unset($campos[0]);
$numftes = count($campos);
$sumaTotal = 0;

?>
<table width="800" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="11" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td colspan="11" height="23" align="left" valign="middle"><strong>2.5.
							Línea Base / Imprevistos</strong></td>
				</tr>
				<tr>
					<td colspan="11" align="left" valign="middle">&nbsp;</td>
				</tr>

				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="224" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;&nbsp;</td>
					<td width="161" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Total Costos Directos</td>
					<td width="365" height="20" colspan="<?php echo($numftes);?>"
						align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
        <?php
        
        for ($col = 0; $col < $numftes; $col ++) {
            
            ?>
        
        <td height="20" align="center" style="border: solid 1px #CCC;"><?php echo($campos[$col+2]);?></td>
        <?php } ?>
      </tr>

				<tbody class="data">
      <?php
    
    $objHC = new HardCode();
    $sum_total = 0;
    if ($iRs->num_rows > 0) {
        $sumaFE = 0;
        $sum_fte = NULL;
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sum_total += $row["costo_total"];
            
            $col = 0;
            for ($col == 0; $col < $numftes; $col ++) {
                
                ;
                $field = $campos[$col + 2];
                
                $sum_fte[$col] += $row[$field];
                
                // $sum_fte[$col] += $row[$field];
            }
            
            $sumaFE += $row[$objHC->Nombre_Fondoempleo];
        } // End While
        $iRs->free();
    } // End If
    ?>
      <tr style="">
						<td height="18">Total de Costos Directos y Aportes por Fuente de
							Financiamiento</td>
						<td align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</td>
        <?php
        $col = 0;
        
        // Nombre_Fondoempleo
        for ($col == 0; $col < $numftes; $col ++) {
            
            $sumaTotal += $sum_fte[$col];
            
            ?>
        <td align="right"><?php echo(number_format($sum_fte[$col],2));?>&nbsp;</td>
        <?php
        
}
        
        ?>
        
        
        </tr>
				</tbody>
				<tfoot>

				</tfoot>
			</table>
   <?php
$LineaBase = (($sumaFE * $objHC->Porcent_Linea_Base) / 100);
$Imprevistos = (($sumaFE * $objHC->Porcent_Imprevistos) / 100);
?>
   	<table width="800" border="0" cellspacing="1" cellpadding="0">
				<thead>
					<tr>
						<td height="23" colspan="2" align="left">La Línea de Base
							representa el 4% de Costos directos</td>
					</tr>
				</thead>
				<tbody class="data">
					<tr>
						<td width="350" height="21" align="left">Monto de la Linea de
							base(4%)</td>
						<td width="102"><?php echo(number_format($LineaBase,2));?></td>
					</tr>
				</tbody>
			</table>
			<table width="800" border="0" cellspacing="1" cellpadding="0">
				<thead>
					<tr>
						<td height="23" colspan="2" align="left">Los imprevistos no deben
							exceder el 2% de los Costos Directos</td>
					</tr>
				</thead>
				<tbody class="data">
					<tr>
						<td width="350" height="21" align="left">Monto de Imprevistos(2%)</td>
						<td width="102"><?php echo(number_format($Imprevistos,2));?></td>
					</tr>
				</tbody>
			</table>
			<table width='800' border='0' cellspacing='1' cellpadding='0'>
				<thead>
					<tr>
						<td height='23' colspan='2' align='left'>
					Resumen del Presupuesto Anual - Año <?php echo($idAnio); ?>
				</td>
					</tr>
				</thead>
				<tbody class='data'>
					<tr>
						<td width='350' height='21' align='left'>Costos Directos</td>
						<td width='102'><?php echo number_format($aCostosDirectos, 2); ?></td>
					</tr>
					<tr>
						<td width='350' height='21' align='left'>Costos Indrectos</td>
						<td width='102'><?php echo number_format($aCostosIndirectos, 2); ?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>Costo Total del Proyecto</td>
						<td><?php echo number_format($aCostosDirectos + $aCostosIndirectos, 2); ?></td>
					</tr>
				</tfoot>
			</table>
<?php
$costoDirecInicial = $objMP->GetCostosDirectos($idProy, $vs);
$costoTotalComp = $objMP->Componentes_CostoTotal($idProy, $vs);
$costoPersonalInicial = $objMP->Personal_GastoTotal($idProy, $vs);
$costoTotalEquipamiento = $objMP->Equipamiento_CostoTotal($idProy, $vs);
$costoTotalFunc = $objMP->Funcionamiento_CostoTotal($idProy, $vs);
$costoAdminist = $objMP->Adminis_CostoTotal($idProy, $vs);
$lineaBaseInicial = $objMP->lineaBase_total($idProy, $vs);
$LineaBase = (($sumaFE * $objHC->Porcent_Linea_Base) / 100);

$rowLBI = $objMP->LineaBase_Imprevistos($idProy, $vs);
$imprevistosInicialReal = $rowLBI['imprevistos'];

$rowLB = $objMP->LineaBase_Imprevistos($idProy, $idVersion);

$anAnioPOA = $objProy->GetAnioPOA($idProy, $idVersion);
$aCompoCostoTotal = $objMP->Componentes_CostoTotal($idProy, $idVersion);
$aPersGastoTotal = $objMP->Personal_GastoTotal($idProy, $idVersion);
$aEquipCostoTotal = $objMP->Equipamiento_CostoTotal($idProy, $idVersion);
$aFuncCostoTotal = $objMP->Funcionamiento_CostoTotal($idProy, $idVersion);
$aAdmisCostoTotal = $objMP->Adminis_CostoTotal($idProy, $idVersion);

$aRs = $objPresup->Acum_Costos_Ejecutados($idProy, $idVersion);
$aRow = mysqli_fetch_assoc($aRs);
$totalEjecutado = $aRow['TotalEjecutado'];
$costoPersonal = $aRow['CostoPersonal'];
$costoEquipa = $aRow['CostoEquipamiento'];
$costoFunc = $aRow['CostoFuncionamiento'];
$costoAdm = $aRow['CostoAdministrativo'];
$costoImprev = $aRow['CostoImprevisto'];
$aRs->free();

?>
	<table width='800' border='0' cellspacing='1' cellpadding='0'>
				<thead>
					<tr>
						<td height="23" colspan="5" align="left">
					Saldo Proyectado por Ejecutar - Año <?php echo($anAnioPOA);?>
				</td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="53" height="28" align="center" valign="middle"
							style="padding: 5px 10px; font-weight: bold;">&nbsp;</td>
						<td width="155" height="28" align="center" valign="middle"
							style="padding: 5px 10px; font-weight: bold;">PRESUPUESTO INICIAL</td>
						<td width="49" rowspan="2" align="center" valign="middle"
							style="padding: 5px 10px; font-weight: bold;">GASTOS</td>
						<td width="47" rowspan="2" align="center" valign="middle"
							style="padding: 5px 10px; font-weight: bold;">PRESUPUESTO ANUAL</td>
						<td width="80" rowspan="2" align="center" valign="middle"
							style="padding: 5px 10px; font-weight: bold;">SALDO PROYECTADO</td>
					</tr>
				</thead>
				<tbody class="data">
					<tr style="background-color: #E3FEE0; height: 25px;">
						<td style="font-weight: bold;">COSTOS DIRECTOS</td>
						<td><?php echo number_format($costoDirecInicial, 2); ?></td>
						<td><?php echo number_format(($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc), 2); ?></td>
						<td><?php echo number_format(($aCostosDirectos), 2); ?></td>
						<td><?php echo number_format(($costoDirecInicial - ($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) - $aCostosDirectos), 2); ?></td>
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF" id="costosDirectos">
					<tr>
						<td style="font-weight: bold;">Costo de los Componentes</td>
						<td><?php echo number_format($costoTotalComp, 2); ?></td>
						<td><?php echo number_format($totalEjecutado, 2); ?></td>
						<td><?php echo number_format($aCompoCostoTotal, 2); ?></td>
						<td><?php echo number_format(($costoTotalComp - $totalEjecutado - $aCompoCostoTotal), 2); ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Costo Personal</td>
						<td><?php echo number_format($costoPersonalInicial, 2); ?></td>
						<td><?php echo number_format($costoPersonal, 2); ?></td>
						<td><?php echo number_format($aPersGastoTotal, 2); ?></td>
						<td><?php echo number_format(($costoPersonalInicial - $costoPersonal - $aPersGastoTotal), 2); ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Costo Equipamiento</td>
						<td><?php echo number_format($costoTotalEquipamiento, 2); ?></td>
						<td><?php echo number_format($costoEquipa, 2); ?></td>
						<td><?php echo number_format($aEquipCostoTotal, 2); ?></td>
						<td><?php echo number_format(($costoTotalEquipamiento - $costoEquipa - $aEquipCostoTotal), 2); ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Costo de Funcionamiento</td>
						<td><?php echo number_format($costoTotalFunc, 2); ?></td>
						<td><?php echo number_format($costoFunc, 2); ?></td>
						<td><?php echo number_format($aFuncCostoTotal, 2); ?></td>
						<td><?php echo number_format(($costoTotalFunc - $costoFunc - $aFuncCostoTotal), 2); ?></td>
					</tr>
				</tbody>
				<tbody class="data">
					<tr style="background-color: #E3FEE0; height: 25px;"">
						<td style="font-weight: bold;">COSTOS INDIRECTOS</td>
						<td><?php echo number_format(($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial), 2); ?></td>
						<td><?php echo number_format(($costoAdm + $costoImprev), 2); ?></td>
						<td><?php echo number_format(($aAdmisCostoTotal + $rowLB['imprevistos'] + $LineaBase), 2); ?></td>
						<td><?php
    
echo number_format(($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial) - ($costoAdm + $costoImprev) - ($aAdmisCostoTotal + $rowLB['imprevistos'] + $LineaBase), 2);
    ?></td>
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF" id="costosIndirectos">
					<tr>
						<td style="font-weight: bold;">Costos Administrativos</td>
						<td><?php echo number_format($costoAdminist, 2); ?></td>
						<td><?php echo number_format($costoAdm, 2); ?></td>
						<td><?php echo number_format($aAdmisCostoTotal, 2); ?></td>
						<td><?php echo number_format(($costoAdminist - $costoAdm - $aAdmisCostoTotal), 2); ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold;">Costo Imprevistos</td>
						<td><?php echo number_format($imprevistosInicialReal, 2); ?></td>
						<td><?php echo number_format($costoImprev, 2); ?></td>
						<td><?php echo number_format($rowLB['imprevistos'], 2); ?></td>
						<td><?php echo number_format(($imprevistosInicialReal - $costoImprev - $rowLB['imprevistos']), 2); ?></td>

					</tr>
					<tr>
						<td style="font-weight: bold;">Costo Linea Base</td>
						<td><?php echo number_format($lineaBaseInicial, 2); ?></td>
						<td>0.00</td>
						<td><?php echo number_format($LineaBase, 2); ?></td>
						<td><?php echo number_format(($lineaBaseInicial - $LineaBase), 2); ?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>TOTAL</td>
						<td>
					<?php
    
echo number_format($costoDirecInicial + $costoAdminist + $imprevistosInicialReal + $lineaBaseInicial, 2);
    ?>
				</td>
						<td>
					<?php
    
echo number_format($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc + $costoAdm + $costoImprev, 2);
    ?>
				</td>
						<td>
					<?php
    
echo number_format($aCostosDirectos + $aAdmisCostoTotal + $rowLB['imprevistos'] + $LineaBase, 2);
    ?>
				</td>
						<td>
					<?php
    
echo number_format(($costoDirecInicial - ($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) - $aCostosDirectos + (($costoAdminist + $imprevistosInicialReal + $lineaBaseInicial) - ($costoAdm + $costoImprev) - ($aAdmisCostoTotal + $rowLB['imprevistos'] + $LineaBase))), 2);
    ?>
        		</td>
					</tr>
				</tfoot>
			</table>



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