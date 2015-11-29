<?php 
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

if ($idProy == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Gastos Administrativos del Proyecto</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form action="#" method="post" enctype="multipart/form-data" name="frmMain" id="frmMain">
<?php
}
?>
        <div id="toolbar" style="height: 4px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="17%"><button class="Button"
							onclick="LoadLineaBase(true); return false;"
							value="Recargar Listado">Refrescar Datos</button></td>
					<td width="16%"></td>
					<td width="5%">&nbsp;</td>
					<td width="13%">&nbsp;</td>
					<td width="43%" align="right"><span
						style="color: #036; font-weight: bold; font-size: 12px;">Linea de
							Base / Imprevistos</span></td>
					<td width="6%" align="right">&nbsp;</td>
				</tr>
			</table>
		</div>
		<div id="divTableLista" class="TableGrid">
        <?php
        $objMP = new BLManejoProy();
        $iRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
        $campos = $objMP->iGetArrayFields($iRs);
        unset($campos[1]);
        unset($campos[0]);
        $numftes = count($campos);
        $sumaTotal = 0;
        ?>
            <table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="604" height="26" rowspan="2"
						style="border: solid 1px #CCC;">&nbsp;&nbsp;</td>
					<td width="84" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Total Costos Directos</td>
					<td colspan="<?php echo($numftes);?>" align="center">Financiamiento</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
        <?php
        for ($col == 0; $col < $numftes; $col ++) {
            ?>
        <td width="90" align="center" style="border: solid 1px #CCC;"><?php echo($campos[$col+2]);?></td>
        <?php } ?>
      </tr>

				<tbody class="data">
      <?php
    $objHC = new HardCode();
    $sum_total = 0;
    if ($iRs->num_rows > 0) {
        $sumaFE = 0;
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sum_total += $row["costo_total"];

            $col = 0;
            for ($col == 0; $col < $numftes; $col ++) {
                $field = $campos[$col + 2];
                $sum_fte[$col] += $row[$field];
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
        <?php } ?>


        </tr>
				</tbody>
				<tfoot>

				</tfoot>
			</table>
			<br />
  <?php
$LineaBase = (($sumaFE * $objHC->Porcent_Linea_Base) / 100);
$Imprevistos = (($sumaFE * $objHC->Porcent_Imprevistos) / 100);
?>

   <div class="TableGrid">
				<table width="457" border="0" cellspacing="1" cellpadding="0">
					<thead>
						<tr>
							<td height="23" colspan="2">La Línea de Base representa el 4% de
								Costos directos</td>
						</tr>
					</thead>
					<tbody class="data">
						<tr>
							<td width="350" height="21">Monto de la Linea de base(4%)</td>
							<td width="102"><input type="text" style="text-align: right"
								value="<?php echo(number_format($LineaBase,2));?>" size="24"
								readonly="readonly" /></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<table width="457" border="0" cellspacing="1" cellpadding="0">
					<thead>
						<tr>
							<td height="23" colspan="2">Los imprevistos no deben exceder el
								2% de los Costos Directos</td>
						</tr>
					</thead>
					<tbody class="data">
						<tr>
							<td width="350" height="21">Monto de Imprevistos(2%)</td>
							<td width="102"><input type="text" class="summontoADM"
								style="text-align: right"
								value="<?php echo(number_format($Imprevistos,2));?>" size="24"
								readonly="readonly" /></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>

  