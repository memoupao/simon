<?php
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLProyecto.class.php");

$objFE = new BLFE();
$objProy = new BLProyecto();

$Concurso = $objFunc->__Request('cboConcurso');
$Anio = $objFunc->__Request('cboAnios');
$Ejecutor = $objFunc->__Request('cboEjecutor');
$Proyecto = $objFunc->__Request('txtCodProy');
$sector = $objFunc->__Request('sector');
$region = $objFunc->__Request('region');

$rsProyectos = $objFE->RepCronograma_y_Ejecucion_Desembolsos($Concurso, $Ejecutor, $Proyecto, $Anio, $sector, $region);
$proy = $objProy->GetProyecto($Proyecto, 1);

if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title></title>
    <script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../css/reportes.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
        <div id="divBodyAjax" class="TableGrid">
			<table width="90%" border="0" cellpadding="2" cellspacing="2"
				style="border: none;">
				<tr>
					<td colspan="4" align="left">Proyecto: <strong><?php print $proy['t02_nom_proy']; ?></strong></p></td>
				</tr>
				<tr>
					<td colspan="4" align="left"><p>Cronograma de Desembolsos por Proyecto Planificado y Ejecutado</p></td>
				</tr>
				<tr>
					<td width="6%">Planificado</td>
					<td width="7%" align="left"><span style="border: 1px solid #999; background-color: #FFF;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
					<td width="5%">Desembolsado</td>
					<td width="82%" align="left"><span style="border: 1px solid #999; background-color: #FFFFCC;">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
				</tr>
			</table>
			<br />
			<table width="98%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr valign="middle" align="center">
						<td width="3%" rowspan="2">Código</td>
						<td width="7%" rowspan="2">Ejecutor</td>
						<td width="31%" rowspan="2">Proyecto</td>
						<td colspan="2">Periodo de Ejecución</td>
						<td width="8%" rowspan="2">Desembolsos Previos <br />al Año <?php echo($Anio);?></td>
						<td height="20" colspan="4" valign="top">Año <?php echo($Anio);?></td>
						<td width="7%" rowspan="2" bgcolor="#eeeccc">Total Año <?php echo($Anio);?></td>
						<td width="10%" rowspan="2" bgcolor="#ccceee">Total Acumulado</td>
					</tr>
					<tr valign="middle" align="center">
						<td width="6%">Inicio</td>
						<td width="5%">Término</td>
						<td width="5%" height="20">Trim 1</td>
						<td width="5%">Trim 2</td>
						<td width="6%">Trim 3</td>
						<td width="7%">Trim 4</td>
					</tr>
				</thead>
				<tbody class="data" style="font-size: 11px;">
                <?php
                $resumen = NULL;
                while ($row = mysqli_fetch_assoc($rsProyectos)) {
                    $total_anio[1] = $row['mpt_1'] + $row['mpt_2'] + $row['mpt_3'] + $row['mpt_4'];
                    $total_anio[2] = $row['mdt_1'] + $row['mdt_2'] + $row['mdt_3'] + $row['mdt_4'];

                    $total_acum[1] = $total_anio[1] + $row['plan_desemb_anterior'];
                    $total_acum[2] = $total_anio[2] + $row['ejec_desemb_anterior'];

                    $resumen['anterior']['planeado'] += $row['plan_desemb_anterior'];
                    $resumen['anterior']['ejecutado'] += $row['ejec_desemb_anterior'];

                    $resumen['mpt_1']['planeado'] += $row['mpt_1'];
                    $resumen['mpt_1']['ejecutado'] += $row['mdt_1'];
                    $resumen['mpt_2']['planeado'] += $row['mpt_2'];
                    $resumen['mpt_2']['ejecutado'] += $row['mdt_2'];
                    $resumen['mpt_3']['planeado'] += $row['mpt_3'];
                    $resumen['mpt_3']['ejecutado'] += $row['mdt_3'];
                    $resumen['mpt_4']['planeado'] += $row['mpt_4'];
                    $resumen['mpt_4']['ejecutado'] += $row['mdt_4'];

                    $resumen['total_anio']['planeado'] += $total_anio[1];
                    $resumen['total_anio']['ejecutado'] += $total_anio[2];

                    $resumen['total_acum']['planeado'] += $total_acum[1];
                    $resumen['total_acum']['ejecutado'] += $total_acum[2];

                    ?>
                    <tr valign="middle" align="center">
						<td height="43" rowspan="2" nowrap="nowrap"><?php echo($row['codigo']); ?><br /></td>
						<td rowspan="2" align="left"><?php echo($row['siglas']); ?></td>
						<td rowspan="2" align="left"><?php echo($row['titulo']); ?></td>
						<td rowspan="2"><?php echo($row['inicio']); ?></td>
						<td rowspan="2"><?php echo($row['termino']); ?></td>
						<td align="right"><span style="background-color: #FFF font-size:10px; color: #333; width: 100%;"><?php echo( number_format($row['plan_desemb_anterior'],2)); ?></span><br /></td>
						<td align="right"><span style="background-color: #FFF font-size:10px; color: #333; width: 100%;"><?php echo( number_format($row['mpt_1'],2)); ?></span><br /></td>
						<td align="right"><span style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($row['mpt_2'],2)); ?></span><br /></td>
						<td align="right"><span style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($row['mpt_3'],2)); ?></span><br /></td>
						<td align="right"><span style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($row['mpt_4'],2)); ?></span><br /></td>
						<td align="right" bgcolor="#eeeccc"><span style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($total_anio[1],2)); ?></span><br /></td>
						<td align="right" bgcolor="#ccceee"><span style="background-color: #FFF font-size:11px; color: #333; width: 100%;"><?php echo( number_format($total_acum[1],2)); ?></span><br /></td>
					</tr>
					<tr valign="middle" align="right">
						<td bgcolor="#FFFFCC"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($row['ejec_desemb_anterior'],2)); ?></span></td>
						<td bgcolor="#FFFFCC" style="font-size: 10px;"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($row['mdt_1'],2)); ?></span></td>
						<td bgcolor="#FFFFCC"><span style="font-size: 10px;"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($row['mdt_2'],2)); ?></span></span></td>
						<td bgcolor="#FFFFCC"><span style="font-size: 10px;"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($row['mdt_3'],2)); ?></span></span></td>
						<td bgcolor="#FFFFCC"><span style="font-size: 10px;"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($row['mdt_4'],2)); ?></span></span></td>
						<td bgcolor="#eeeccc"><span style="font-size: 10px;"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($total_anio[2],2)); ?></span></span></td>
						<td bgcolor="#ccceee"><span style="font-size: 10px;"><span style="font-size: 11px; color: #333; width: 100%;"><?php echo( number_format($total_acum[2],2)); ?></span></span></td>
					</tr>
                <?php
                } // End While
                $rsProyectos->free();

                ?>
                </tbody>
				<tbody style="text-align: right; font-size: 10px; font-weight: bold;">
					<tr valign="middle">
						<td height="24" align="left">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td rowspan="3" align="left" style="font-size: 14px;">RESUMEN</td>
						<td colspan="2" align="right"><strong>PLANEADO&nbsp;&nbsp;</strong></td>
						<td align="right"><?php echo( number_format($resumen['anterior']['planeado'],2)); ?></td>
						<td align="right"><?php echo( number_format($resumen['mpt_1']['planeado'],2)); ?></td>
						<td align="right"><?php echo( number_format($resumen['mpt_2']['planeado'],2)); ?></td>
						<td align="right"><?php echo( number_format($resumen['mpt_3']['planeado'],2)); ?></td>
						<td align="right"><?php echo( number_format($resumen['mpt_4']['planeado'],2)); ?></td>
						<td align="right" bgcolor="#eeeccc"><?php echo( number_format($resumen['total_anio']['planeado'],2)); ?></td>
						<td align="right" bgcolor="#ccceee"><?php echo( number_format($resumen['total_acum']['planeado'],2)); ?></td>
					</tr>
					<tr valign="middle">
						<td height="22" align="left">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td colspan="2" align="right"><strong>EJECUTADO&nbsp;&nbsp;</strong></td>
						<td align="right"><span style="background-color: #FF0;"><?php echo( number_format($resumen['anterior']['ejecutado'],2)); ?></span></td>
						<td align="right"><span style="background-color: #FF0;"><?php echo( number_format($resumen['mpt_1']['ejecutado'],2)); ?></span></td>
						<td align="right"><span style="background-color: #FF0;"><?php echo( number_format($resumen['mpt_2']['ejecutado'],2)); ?></span></td>
						<td align="right"><span style="background-color: #FF0;"><?php echo( number_format($resumen['mpt_3']['ejecutado'],2)); ?></span></td>
						<td align="right"><span style="background-color: #FF0;"><?php echo( number_format($resumen['mpt_4']['ejecutado'],2)); ?></span></td>
						<td align="right" bgcolor="#eeeccc"><span style="background-color: #FF0;"><?php echo( number_format($resumen['total_anio']['ejecutado'],2)); ?></span></td>
						<td align="right" bgcolor="#ccceee"><span style="background-color: #FF0;"><?php echo( number_format($resumen['total_acum']['ejecutado'],2)); ?></span></td>
					</tr>
					<tr valign="middle">
						<td height="23" align="left">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td colspan="2" align="right"><strong>SALDO&nbsp;&nbsp;</strong></td>
						<td align="right" nowrap="nowrap"
							style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['anterior']['planeado'] - $resumen['anterior']['ejecutado'],2)); ?></span></td>
						<td align="right" nowrap="nowrap"
							style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['mpt_1']['planeado'] - $resumen['mpt_1']['ejecutado'],2)); ?></span></td>
						<td align="right" nowrap="nowrap"
							style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['mpt_2']['planeado'] - $resumen['mpt_2']['ejecutado'],2)); ?></span></td>
						<td align="right" nowrap="nowrap"
							style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['mpt_3']['planeado'] - $resumen['mpt_3']['ejecutado'],2)); ?></span></td>
						<td align="right" nowrap="nowrap"
							style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['mpt_4']['planeado'] - $resumen['mpt_4']['ejecutado'],2)); ?></span></td>
						<td align="right" nowrap="nowrap"
							bgcolor="#eeeccc" style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['total_anio']['planeado'] - $resumen['total_anio']['ejecutado'],2)); ?></span></td>
						<td align="right" nowrap="nowrap"
							bgcolor="#ccceee" style="border-top: solid 1px">&nbsp;<span
							style="background-color: #0F0;"><?php echo( number_format($resumen['total_acum']['planeado'] - $resumen['total_acum']['ejecutado'],2)); ?></span></td>
					</tr>
				</tbody>
			</table>
			<br />
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>