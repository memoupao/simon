<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
// require_once(constant("PATH_CLASS")."BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");

// $objInf = new BLMonitoreoFinanciero();
$objInf = new BLInformes();
$objPresup = new BLPresupuesto();

// $idVersion = $objPresup->Proyecto->MaxVersion($idProy);
error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idComp = $objFunc->__POST('idComp');
$idInforme = $objFunc->__POST('idNum');
$idAnio = $objFunc->__POST('idAnio');
$idFuente = 10;

// $idFte = $objFunc->__POST('idFte');

$row = $objInf->Inf_UA_Seleccionar($idProy, $idInforme);
// $row = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Informe Gastos x SubActividad</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}

// --> Validamos que se haya pasado correctamente el ID del Informe, para calacular el periodo de Ejecución
if ($idInforme == "") {
    $objFunc->MensajeError("No se ha Establecido el Periodo de Referencia para el Informe de Monitoreo Financiero");
    $idComp = - 1;
}

?>
<div id="divTableLista">
			<table width="771" border="1" cellpadding="0" cellspacing="0">
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<th width="53" rowspan="2" align="center" valign="middle">Codigo</th>
					<th width="144" rowspan="2" align="center" valign="middle">Actividades</th>
					<th width="47" rowspan="2" align="center" valign="middle">U.M.</th>
					<th width="75" rowspan="2" align="center" valign="middle">Presupuesto
					</th>
					<th height="28" colspan="3" align="center" valign="middle">Año&nbsp;<?php echo($idAnio); ?></th>
					<th width="209" rowspan="2" align="center" valign="middle">Observaciones</th>
				</tr>
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<th width="70" height="28" align="center" valign="middle">Programado</th>
					<th width="70" align="center" valign="middle">Ejecutado</th>
					<th align="center" valign="middle">% Ejec.</th>
				</tr>
        
      <?php
    
    $total_presup = 0;
    $total_ejec = 0;
    $total_GNA = 0;
    
    $iRsAct = $objInf->Inf_UA_ListadoActividades($idProy, $idComp, $idInforme, $idFuente);
    
    while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
        $idAct = $rowAct['t09_cod_act'];
        
        $total_presup += $rowAct['total_presup'];
        $total_ejec += $rowAct['ejecutado'];
        $total_planeado += $rowAct['programado'];
        
        $porcEjecucion = round((($rowAct['ejecutado'] / $rowAct['programado']) * 100), 2);
        
        ?>  
       <tbody class="data">
					<tr style="background-color: #FC9; height: 25px; cursor: pointer;"
						onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
						<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
						<td colspan="2" align="left"><?php echo( $rowAct['actividad']);?></td>
						<td align="center"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['programado'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['ejecutado'],2,'.',','));?></td>
						<td align="center"><?php echo($porcEjecucion);?>%</td>
						<td align="center">&nbsp;</td>
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF"
					id="<?php echo("tbody_".$idFte.'_'.$idAct);?>">
 
 
 
    <?php
        
        $iRs = $objInf->Inf_UA_ListadoSubActividades($idProy, $idComp, $idAct, $idInforme, $idFuente);
        
        while ($rowsub = mysqli_fetch_assoc($iRs)) {
            $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
            $total_GNA += $rowsub['gasto_no_aceptado'];
            
            ?>
    <tr>
						<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?>
        </td>
						<td align="left"><?php echo( $rowsub['subactividad']);?>
          <input name="txt_cod_sub[]" id="txt_cod_sub[]" type="hidden"
							value="<?php echo($rowsub['codigo']); ?>" class="presup" /></td>
						<td align="center"><?php echo($rowsub['um']);?></td>
						<td align="center"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['programado'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['ejecutado'],2,'.',','));?></td>
						<td align="center"><?php echo($porcEjecucion);?>%</td>
						<td align="center"><textarea name="txtcoment_presup[]" rows="2"
								class='presup' id="txtcoment_presup[]"
								style="width: 100%; height: 100%"><?php echo($rowsub['observaciones']);?></textarea></td>
					</tr>
      <?php } $iRs->free(); // Fin de SubActividades ?>
      </tbody>
	  <?php } $iRsAct->free(); // Fin de Actividades 	?>
    <tfoot>
					<tr style="color: #FFF; height: 20px;">
						<th colspan="3">Totales x Componente &nbsp;&nbsp;</th>
						<th align="center"><?php echo(number_format($total_presup,2,'.',','));?></th>
						<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</th>
						<th align="center"><?php echo(round((($total_ejec/$total_planeado)*100),2));?>%</th>
						<th align="right">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" class='presup' /> <input
				type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>"
				class='presup' /> <input type="hidden" name="t55_num" id="t55_num"
				value="<?php echo($idInforme);?>" class='presup' /> <input
				type="hidden" name="idFuente" id="idFuente"
				value="<?php echo($idFuente);?>" class='presup' />

			<script language="javascript" type="text/javascript">

function ShowActividad(idAct)
 {
	 var id = "#" + idAct ;
	 $(id).toggle();
	 return false;
 }
 
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>