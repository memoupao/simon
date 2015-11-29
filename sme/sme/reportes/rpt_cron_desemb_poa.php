<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idFuente = $objFunc->__Request('idFte');

$objProy = new BLProyecto();
$rowProy = $objProy->ProyectoSeleccionar($idProy, $idVersion);

$objEje = new BLEjecutor();
$rowFte = $objEje->EjecutorSeleccionar($idFuente);
$objEje = NULL;

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cronograma de Desembolsos</title>
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
			<style>
.componente {
	background-color: #FFC;
}

.actividad {
	background-color: #FC9;
}

.subactividad {
	background-color: #CFF;
}

.rubro {
	background-color: #FFF;
}
</style>
			<div align="left">
				<fieldset style="width: 900px;">
					<legend style="font-size: 11px; font-weight: bold; color: #036;">Datos
						del Proyecto</legend>
					<table width="100%" border="0" align="left" cellpadding="0"
						cellspacing="0" style="padding: 10px;">
						<tr>
							<th width="9%" height="23" align="left">CODIGO</th>
							<td width="67%" align="left"><?php echo($rowProy['t02_cod_proy']);?> - <a
								href="<?php if($rowProy['t01_web_inst']==''){echo('#');}else{echo($objFunc->verifyURL($rowProy['t01_web_inst']));} ?>"
								target="_blank" title="Ir a Pagina web del Ejecutor"><?php echo($rowProy['t01_sig_inst']);?></a></td>
							<th width="7%" align="left" nowrap="nowrap">INICIO</th>
							<td width="17%" align="left"><?php echo($rowProy['ini']);?></td>
						</tr>
						<tr>
							<th align="left">NOMBRE <br /></th>
							<th height="18" align="left"><?php echo($rowProy['t02_nom_proy']);?></th>
							<th align="left" nowrap="nowrap">TERMINO</th>
							<td align="left"><?php echo($rowProy['fin']);?></td>
						</tr>
						<tr>
							<th align="left" nowrap="nowrap">FINALIDAD</th>
							<td height="34"><?php echo($rowProy['t02_fin']);?></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th height="34" colspan="4" align="left">FUENTE DE
								FINANCIAMIENTO: <font style="font-weight: normal;"> <?php echo($rowFte['t01_sig_inst']." - ".$rowFte['t01_nom_inst']); ?></font>
							</th>
						</tr>
					</table>
				</fieldset>
			</div>
			<br />
			<table width="99%" border="0" cellpadding="0" cellspacing="0"
				style="min-width: 780px;">
				<thead>
  <?php

$arrPeriodos = NULL;
$contador = 0;
$conta_mes = 1;
$conta_trim = 0;
$rsAnios = $objProy->ListaAniosProyecto($idProy, $idVersion);
while ($ranio = mysql_fetch_assoc($rsAnios)) {
    if ($ranio['codigo'] == ($idVersion - 1)) {
        $rsMeses = $objProy->PeriodosxAnio($idProy, $ranio['codigo']);
        while ($rmes = mysqli_fetch_assoc($rsMeses)) {
            $arrPeriodos[$contador][0] = $rmes['anio'] . '.' . $rmes['mes']; // Codigo del Periodo
            $arrPeriodos[$contador][1] = $rmes['num_anio']; // Año
            $arrPeriodos[$contador][2] = $rmes['mes']; // Mes
            $arrPeriodos[$contador][3] = $rmes['nom_abrev'] . '-' . $rmes['num_anio']; // Periodo Mes
            $arrPeriodos[$contador][4] = 0;
            
            if ($conta_mes == 3) {
                $contador ++;
                $arrPeriodos[$contador][0] = "";
                $arrPeriodos[$contador][1] = $rmes['anio']; // Año
                $arrPeriodos[$contador][2] = $conta_trim;
                $arrPeriodos[$contador][3] = $arrPeriodos[$contador - 3][3] . ' / ' . $arrPeriodos[$contador - 1][3]; // Periodo Mes
                $arrPeriodos[$contador][4] = 0; // Total del Periodo Trimestre
                $conta_trim ++;
                $conta_mes = 0;
            }
            
            if ($conta_trim == 4 && $conta_mes == 0) {
                $contador ++;
                $arrPeriodos[$contador][0] = "";
                $arrPeriodos[$contador][1] = ""; // Año
                $arrPeriodos[$contador][2] = $conta_trim;
                $arrPeriodos[$contador][3] = $rmes['num_anio']; // Periodo Mes
                $arrPeriodos[$contador][4] = 0; // Total del Año
                $conta_trim = 0;
            }
            
            $conta_mes ++;
            $contador ++;
        }
        $rsMeses->free();
    }
}

$objMP = new BLManejoProy();

$rsDesemb = $objMP->RepCronogramaDesembolsos($idProy, $idVersion, $idFuente);

$campos = $objMP->iGetArrayFields($rsDesemb);
$arrfuentes = NULL;

/*
 * for($x=9; $x<count($campos); $x++) { $arrfuentes[$x-9][0] = $campos[$x] ; //--> Para los Nombres de Fuentes $arrfuentes[$x-9][1] = 0 ; //--> Para los Totales }
 */
	
	/*
	echo("<pre>");
	print_r($campos);
	print_r($arrfuentes);
	echo("</pre>") ;
	*/
	$sumaTotal = 0;
$totalFte = 0;
$colspan = count($arrPeriodos); // 1=columna de Total
?>
  <tr style="background: #CFC;">
						<td colspan="2" rowspan="3" align="center" valign="middle"><strong>COMPONENTE/ACTIVIDAD/SUB-ACTIVIDAD</strong></td>
						<td width="196" rowspan="3" align="center" valign="middle"><strong>Unidad
								de Medida</strong></td>
						<td width="100" rowspan="3" align="center" valign="middle"><strong>Costo
								Parcial</strong></td>
						<td width="87" rowspan="3" align="center" valign="middle"><strong>Meta
								Física</strong></td>
						<td width="147" rowspan="3" align="center" valign="middle"><strong>Costo
								Total</strong></td>
						<td width="147" rowspan="3" align="center" valign="middle"><strong
							style="color: #00F;"><?php echo($rowFte['t01_sig_inst']); ?></strong></td>
    <?php
    $idano = $idVersion - 1;
    for ($x = 0; $x < count($arrPeriodos); $x ++) {
        if ($arrPeriodos[$x][0] == "" && $arrPeriodos[$x][1] == "") {
            ?>
		<td colspan="17" align="center"><strong>Año <?php echo($idano." (".$arrPeriodos[$x][3].")");?></strong></td>
	<?php
            
$idano ++;
        }
    }
    ?>
    <td width="110" rowspan="3" align="center"><strong>TOTAL</strong></td>
					</tr>
					<tr style="background: #CFC;">
   <?php
$idtrim = 1;
for ($x = 0; $x < count($arrPeriodos); $x ++) {
    if ($arrPeriodos[$x][0] == "" && $arrPeriodos[$x][1] != "") {
        ?>
    	<td colspan="4" align="center"><strong>Trimestre <?php echo($idtrim." (".$arrPeriodos[$x][3].")");?></strong></td>
	<?php
        if ($idtrim == 4) {
            echo ('<td width="110" rowspan="2" align="center"><strong>Total <br /> Año ' . $arrPeriodos[$x][1] . ' </strong></td>');
            $idtrim = 0;
        }
        
        $idtrim ++;
    }
}
?>
    
  </tr>
					<tr style="background: #CFC;">
    <?php
    $idtrim = 1;
    for ($x = 0; $x < count($arrPeriodos); $x ++) {
        if ($arrPeriodos[$x][1] != "") {
            if ($arrPeriodos[$x][0] != "")             // Mes
            {
                echo ('<td width="103" align="center" style="font-size:10px;">' . "Mes " . $arrPeriodos[$x][2] . "<br>" . "(" . $arrPeriodos[$x][3] . ')</td>');
            } else             // Suma del Trimestre
            {
                echo ('<td width="103" align="center">' . "Total <br>" . "Trim " . $idtrim . '</td>');
                $idtrim ++;
            }
        }
    }
    ?>
    </tr>
				</thead>

				<tbody class="data">
  <?php
$Index = 1;

while ($row = mysqli_fetch_assoc($rsDesemb)) {
    $tipo = $row['tipo'];
    $total_fila = 0;
    
    if ($tipo == 'componente') {
        $sumaTotal += $row['total'];
        $totalFte += $row['total_aporte_fte'];
        for ($x = 0; $x < count($arrPeriodos); $x ++) {
            $arrPeriodos[$x][4] += $row[$arrPeriodos[$x][0]];
        }
    }
    ?>
   <tr class="<?php echo($tipo);?>">
						<td width="48" align="left" valign="middle"><?php echo($row['codigo']);?></td>
						<td width="412" align="left" valign="middle"
							style="min-width: 240px;"><?php echo($row['descripcion']);?></td>
						<td align="center" valign="middle" style="min-width: 100px;"><?php echo($row['um']);?></td>
						<td align="right" valign="middle"><?php echo(number_format($row['parcial'],2));?></td>
						<td align="center" valign="middle"><?php echo($row['meta']);?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total_aporte_fte'],2));?></td>
    <?php
    $sumTrim = 0;
    $sumAnio = 0;
    $MontoMes = 0;
    for ($x = 0; $x < count($arrPeriodos); $x ++) {
        if ($arrPeriodos[$x][1] != "") {
            if ($arrPeriodos[$x][0] != "") {
                $MontoMes = $row[$arrPeriodos[$x][0]];
                $sumTrim += $MontoMes;
                echo ('<td width="103" align="right">' . number_format($MontoMes, 2) . '</td>');
            } else {
                $sumAnio += $sumTrim;
                echo ('<td width="103" align="right" style="background-color:#CCC;">' . number_format($sumTrim, 2) . '</td>');
                $sumTrim = 0;
            }
        } else {
            if ($arrPeriodos[$x][0] == "") {
                $sumAnio += $sumTrim;
                echo ('<td width="103" align="right" style="background-color:#CC9;">' . number_format($sumAnio, 2) . '</td>');
                $sumAnio = 0;
            }
        }
        $total_fila += $row[$arrPeriodos[$x][0]];
    }
    ?>
    <td align="right" style="background-color: #DCD1FA;"><?php echo( number_format($total_fila,2));?></td>

					</tr>
  <?php
    $Index ++;
} // End While
$rsDesemb->free();
?>
  </tbody>
				<tfoot>
					<tr style="color: #333; height: 20px;">
						<th align="center" valign="middle">&nbsp;</th>
						<th align="left" valign="middle">&nbsp;</th>
						<th align="center" valign="middle">&nbsp;</th>
						<th align="center" valign="middle">&nbsp;</th>
						<th align="center" valign="middle">&nbsp;</th>
						<th align="right" valign="middle"><?php echo( number_format($sumaTotal,2));?></th>
						<th align="right" valign="middle"><?php echo( number_format($totalFte,2));?></th>
      <?php
    $total_fila = 0;
    $sumTrim = 0;
    $sumAnio = 0;
    $MontoMes = 0;
    for ($x = 0; $x < count($arrPeriodos); $x ++) {
        
        if ($arrPeriodos[$x][1] != "") {
            if ($arrPeriodos[$x][0] != "") {
                $MontoMes = $arrPeriodos[$x][4];
                $sumTrim += $MontoMes;
                echo ('<th width="103" align="right">' . number_format(MontoMes, 2) . '</th>');
            } else {
                $sumAnio += $sumTrim;
                echo ('<th width="103" align="right" style="background-color:#CCC;">' . number_format($sumTrim, 2) . '</th>');
                $sumTrim = 0;
            }
        } else {
            if ($arrPeriodos[$x][0] == "") {
                $sumAnio += $sumTrim;
                echo ('<th width="103" align="right" style="background-color:#CC9;">' . number_format($sumAnio, 2) . '</th>');
                $sumAnio = 0;
            }
        }
        $total_fila += $arrPeriodos[$x][4];
    }
    ?>
      <th align="right" valign="middle"><?php echo( number_format($total_fila,2));?></th>
					</tr>
				</tfoot>
			</table>

			<br />

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>