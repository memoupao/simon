<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");

$objInf = new BLInformes();
$objPresup = new BLPresupuesto();

$idVersion = $objPresup->Proyecto->MaxVersion($idProy);

error_reporting("E_PARSE");

$idProy = $objFunc->__POST('idProy');
$idComp = $objFunc->__POST('idComp');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');
$idFte = $objFunc->__POST('idFte');

if ($idProy == "" && $idComp == "" && $idFte == "") {
    $idProy = $objFunc->__GET('idProy');
    $idComp = $objFunc->__GET('idComp');
    $idAnio = $objFunc->__GET('idAnio');
    $idMes = $objFunc->__GET('idMes');
    $idFte = $objFunc->__GET('idFte');
}

$HardCode = new HardCode();
$IsMF = false;
if ($ObjSession->PerfilID == $HardCode->GP || $ObjSession->PerfilID == $HardCode->RA || $ObjSession->PerfilID == $HardCode->SE) {
    $IsMF = true;
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Informe por Rubros de gasto</title>
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
?>

<script language="javascript" type="text/javascript"
			src="../../../jquery.ui-1.5.2/jquery.numeric.js"></script>
		<script src="../../../js/commons.js" type="text/javascript"></script>

		<div id="divTableLista">
<?php
if ($idFte <= 0) {
    echo ("<br><br><b style='color:red;'>No se ha especificado la Fuente de Financiamiento </b><br><br>");
    return;
}
?>

<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<th width="53" height="28" rowspan="2" align="center"
						valign="middle">Codigo</th>
					<th width="155" height="28" rowspan="2" align="center"
						valign="middle">Categoria de Gastos</th>
					<th width="49" rowspan="2" align="center" valign="middle">U.M.</th>
					<th width="47" rowspan="2" align="center" valign="middle">Meta
						Anual</th>
					<th width="80" rowspan="2" align="center" valign="middle">
						Presupuesto Anual</th>
					<th width="67" rowspan="2" align="center" valign="middle">Total <?php if($idFte==$HardCode->codigo_Fondoempleo){echo("Fondoempleo");} else {echo("Fuente Financ");} ?></th>
					<th colspan="2" align="center" valign="middle">Información del Mes</th>
					<th colspan="2" rowspan="2" align="center" valign="middle">Observaciones</th>
				</tr>
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<th width="70" align="center" valign="middle">Planeado</th>
					<th width="73" align="center" valign="middle">Ejecutado</th>
				</tr>
        
      <?php
    
    $total_presup = 0;
    $total_ejec = 0;
    
    $iRsAct = $objPresup->ListaActividades_InfFinanc($idProy, $idComp, $idAnio, $idMes, $idFte);
    while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
        $idAct = $rowAct['t09_cod_act'];
        
        $total_presup += $rowAct['total_presup'];
        $total_ejec += $rowAct['total_ejec'];
        $total_planeado += $rowAct['planeado'];
        $total_fuente += $rowAct['total_fuente'];
        
        ?>  
       <tbody class="data">
					<tr style="background-color: #FC9; height: 25px; cursor: pointer;"
						onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
						<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
						<td colspan="3" align="left"><?php echo( $rowAct['actividad']);?></td>
						<td align="right"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['fte_fe'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['planeado'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowAct['total_ejec'],2,'.',','));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF"
					id="<?php echo("tbody_".$idFte.'_'.$idAct);?>">
    <?php
        $iRs = $objPresup->ListaSubActividades_InfFinanc($idProy, $idComp, $idAct, $idAnio, $idMes, $idFte);
        while ($rowsub = mysqli_fetch_assoc($iRs)) {
            ?>
    <tr style="background-color: #E3FEE0;">
						<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
						<td align="left"><?php echo( $rowsub['subactividad']);?></td>
						<td align="center"><?php echo($rowsub['um']);?></td>
						<td align="center"><?php echo($rowsub['meta']);?></td>
						<td align="right"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['fte_fe'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['planeado'],2,'.',','));?></td>
						<td align="right"><?php echo(number_format($rowsub['total_ejec'],2,'.',','));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
      <?php
            $iRsCateg = $objPresup->ListaCategoriaGasto_InfFinanc($idProy, $idComp, $idAct, $rowsub['t09_cod_sub'], $idAnio, $idMes, $idFte);
            while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) {
                $idtxtAvance = $idFte . "_txtRubroEjec[]";
                $idtxtCodigo = $idFte . "_Id[]";
                
                $inputTextAvance = "<input gastosEje='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' value='" . $rowsubCateg['total_ejec'] . "' size='4' class='presup' " . ($rowsubCateg['fte_fe'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " />";
                $inputTextID = "<input  value='" . $rowsubCateg['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
                $inputTextEst = "<input  value='" . $rowsubCateg['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
                $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowsubCateg['observ'] . "</textarea>";
                
                // /*
                $styleRow = 'style="font-weight:300; color:navy;"';
                if ($rowsubCateg['t41_estado'] == '2')                 // Verificar que el MF no haya ingresado comentarios
                {
                    $styleRow = 'style="font-weight:300; color:red;"';
                }
                // */
                
                ?>
    <tr <?php echo($styleRow);?>>
						<td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
						<td align="left" nowrap="nowrap"><?php echo($rowsubCateg['categoria']);?></td>
						<td align="center" nowrap="nowrap">&nbsp;</td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextID); ?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_presup'],2,'.',','));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['fte_fe'],2,'.',','));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['planeado'],2,'.',','));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center">
        <?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowsubCateg['observ']);} ?>
        </td>
					</tr>
      <?php } $iRsCateg->free(); // Fin de Categorias de Gastos ?>
      <?php } $iRs->free(); // Fin de Actividades ?>
      </tbody>
	  <?php } $iRsAct->free(); // Fin de Actividades 	?>
    <tfoot>
					<tr style="color: #FFF; height: 20px;">
						<th>&nbsp;</th>
						<th colspan="3" align="right">Totales x Componente &nbsp;&nbsp;</th>
						<th align="right"><?php echo(number_format($total_presup  ,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_fuente  ,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_ejec    ,2,'.',','));?>&nbsp;</th>
						<th width="88" align="right">&nbsp;</th>
						<th width="71" align="right">&nbsp;</th>
					</tr>
				</tfoot>
			</table>




			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" class='presup' /> <input
				type="hidden" name="t02_version" value="<?php echo($idVersion);?>"
				class='presup' /> <input type="hidden" name="t08_cod_comp"
				value="<?php echo($idComp);?>" class='presup' /> <input
				type="hidden" name="t40_anio" id="t40_anio"
				value="<?php echo($idAnio);?>" class='presup' /> <input
				type="hidden" name="t40_mes" id="t40_mes"
				value="<?php echo($idMes);?>" class='presup' />

			<script language="javascript" type="text/javascript">	
	
	function ShowActividad(idAct)
	 {
		 var id = "#" + idAct ;
		 $(id).toggle();
   		 return false;
	 }
    
	$("input[gastosEje='1']").numeric().pasteNumeric();
	</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>