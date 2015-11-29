<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");

$objRep = new BLReportes();

$concurso = $objFunc->__Request('cboConcurso');
$region = $objFunc->__Request('cboRegion');
$sector = $objFunc->__Request('cboSectorProd');
$tipoinst = $objFunc->__Request('cboTipoInst');
$estado = $objFunc->__Request("cboEstado");

$rsMatriz = $objRep->Benchmark01($concurso, $region, $sector, $tipoinst, $estado);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Benchmarking</title>
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
			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="6%" height="20" valign="top"><p align="center">Código</p></td>
						<td width="14%" valign="top"><p align="center">Ejecutor</p></td>
						<td width="41%" valign="top"><p align="center">Proyecto</p></td>
						<td width="18%" align="center" valign="middle">Sector Productivo</td>
						<td width="14%" valign="top"><p align="center">Periodo de
								Ejecución</p></td>
						<td width="14%" valign="top"><p align="center">Meses de Ejecución
								Restantes</p></td>
						<td width="14%" valign="top"><p align="center">% Avance General</p></td>
						<td width="7%" valign="top"><p align="center">% Avance Acumulado</p></td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    while ($rMatriz = mysqli_fetch_assoc($rsMatriz)) {
        // $avance = $rMatriz['Avance'] ;
        $avance = $rMatriz['AvanceTotal'];
        
        if ($avance >= 75) {
            $coloracion = "#7FFF55";
        }
        
        if ($avance < 75 && $avance >= 50) {
            $coloracion = "#FFDF00";
        }
        
        if ($avance < 50) {
            $coloracion = "#FF1F55";
        }
        if (! strtotime($rMatriz['presentacion'])) {
            $inicio = explode("/", $rMatriz['inicio']);
            $rMatriz['presentacion'] = $inicio[2] . "/" . $inicio[1] . "/" . $inicio[0];
        }
        $fecha = strftime("%Y/%m/%d", time());
        $segundos = strtotime($rMatriz['ftermino']) - strtotime($fecha);
        $meses = ((($segundos / 60) / 60) / 24) / 30;
        if ($meses < 0) {
            $meses = 0;
        }
        ?>
    <tr style="background-color:<?php echo($coloracion);?>" >
						<td align="left" valign="middle" style="font-size: 10px;"
							height="20"><span class="ClassText"><?php echo($rMatriz['codigo']); ?></span><br />
							<br /></td>
						<td align="left" valign="middle" style="font-size: 10px;"><span
							class="ClassText"><?php echo($rMatriz['siglas']); ?></span></td>
						<td align="left" valign="middle"><span style="font-size: 10px;"><span
								class="ClassText"><?php echo($rMatriz['titulo']); ?></span></span></td>
						<td align="center" valign="middle"><span class="ClassText"><?php echo($rMatriz['sectoresprod']); ?></span></td>
						<td align="left" valign="middle" style="font-size: 10px;"><span
							class="ClassField">Inicio&nbsp;&nbsp;&nbsp; :</span> <span
							class="ClassText"><?php echo($rMatriz['finicio']); ?></span> <br />
							<span class="ClassField">Termino:</span> <span class="ClassText"><?php echo($rMatriz['ftermino']); ?></span><br />
						</td>
						<td align="center" valign="middle"><span class="ClassText"><?php echo(number_format($meses,2)); ?></span></td>
      <?php
        $linkTrimAcum = 'ViewDetailsTrimAcum("' . $rMatriz['codigo'] . '","' . $rMatriz['anioProy'] . '","' . $rMatriz['mesProy'] . '");';
        ?>
	   <td align="center" valign="middle"><span class="ClassText">
	   		<?php echo(number_format($rMatriz['Avance'],2)); ?>
	   	</span></td>
						<td align="center" valign="middle"><a
							style="color: #000; text-decoration: underline;"
							href='javascript:<?php echo($linkTrimAcum);?>'
							title="Ver Cumplimiento Trimestral Acumulado del Proyecto">
      		<?php if($rMatriz['AvanceTotal']>100){echo "100.00";}else{ echo(number_format($rMatriz['AvanceTotal'], 2));} ?>
      	</a></td>
					</tr>
    <?php
    } // End While
    $rsMatriz->free();
    ?>
  </tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="2" align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />

			<script language="javascript">
function ViewDetailsTrimAcum(idproy, anio, mes)
	{
		var params = "idProy="+idproy+"&anio="+anio+"&trim=0&mes="+mes;
		NewReport("Cumplimiento Trimestral Acumulado del Proyecto","rpt_inf_trim_cumpli_acum.php",params);	
	}
</script>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>