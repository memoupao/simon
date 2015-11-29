<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$Concurso = $objFunc->__Request('idConcurso');
$idInstitucion = $objFunc->__Request('idInst');

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Directorio de Instituciones</title>
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


			<table border="0" width="90%" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr>
						<td width="78" rowspan="2" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#E9E9E9"><strong>Ejecutor</strong></td>
						<td width="73" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Proyecto</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Periodo
								de Ejecucion</strong></td>
						<td width="81" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Monitor Financiero</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Trimestre</strong></td>
						<td width="99" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Presupuesto Fondoempleo</strong></td>
						<td width="69" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Total Ejecutado</strong></td>
						<td width="107" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Excedente por Ejecutar del último
								Informe</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
						<td colspan="4" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Condiciones
								de Desembolso</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Observaciones
								Monitor Tecnico</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Observaciones
								Monitor Financiero</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Resultados</strong></td>
					</tr>
					<tr>
						<td width="53" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Inicio</strong></td>
						<td width="71" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Termino</strong></td>
						<td width="57" align="center" valign="middle" bgcolor="#E9E9E9"><strong>En
								Ejecu-ción</strong></td>
						<td width="67" align="center" valign="middle" bgcolor="#E9E9E9"><strong>A
								Desem-bolsar</strong></td>
						<td width="67" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Total
								Desem-bolsado</strong></td>
						<td width="99" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
								a Desembolsar Según Cronograma</strong></td>
						<td width="68" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Inf.
								Ejec. Presup. al dia</strong></td>
						<td width="72" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Inf.
								Tec. Mensual al dia</strong></td>
						<td width="56" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Inf.
								Tec. Trim. al dia</strong></td>
						<td width="64" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Carta
								Fianza Vigente</strong></td>
						<td width="49" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFF9D"><strong>Si / No</strong></td>
						<td width="97" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Descripción</strong></td>
						<td width="49" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFF9D"><strong>Si / No</strong></td>
						<td width="104" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Descripción</strong></td>
						<td width="89" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Desemb
								<br> Si / No
						</strong></td>
						<td width="91" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Monto
								a Desembolsar</strong></td>
						<td width="97" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Fecha
								Aprobación</strong></td>
					</tr>
				</tbody>
				<tbody class="data">
      <?php
    $objFE = new BLFE();
    
    $iRs = $objFE->ListadoProyectos_AprobDesembolsos($Concurso, $idInstitucion);
    
    $RowIndex = 0;
    
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
      <tr class="RowData">
						<td height="30" align="center" valign="middle"><?php echo($row['t01_sig_inst']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_cod_proy']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_fch_ini']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_fch_tre']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_fina']);?></td>
						<td align="center" valign="middle"><?php echo( $row['trim_ejecucion']);?></td>
						<td align="center" valign="middle"><?php echo( $row['trim_desembolsar']);?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total_aporte_fe'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total_ejecutado'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['excedente_ejecutar'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['total_desembolsado'],2));?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['monto_plan_trim'],2));?></td>
						<td align="center" valign="middle">
        <?php if($rAprob['inf_financ']=='1'){echo("Si");}else{echo("No");}?>
        </td>
						<td align="center" valign="middle">
        <?php if($rAprob['inf_mes']=='1'){echo("Si");}else{echo("No");}?>
        </td>
						<td align="center" valign="middle">
         <?php if($rAprob['inf_trim']=='1'){echo("Si");}else{echo("No");}?>
        </td>
						<td align="center" valign="middle">
        <?php if($rAprob['cartafianza']=='1'){echo("Si");}else{echo("No");}?>
        </td>
        
        <?php
            $rAprob = $objFE->Aprobacion_Desemb_Seleccionar($row['t02_cod_proy'], $row['trim_desembolsar']);
            
            ?>
        
        <td align="center" valign="middle" bgcolor="#F9FAC9"><?php if($rAprob['t60_apro_mt']=='1'){echo("Si");}else{echo("No");}?></td>
						<td align="left" valign="middle" bgcolor="#F9FAC9"><?php echo( $rAprob['t60_obs_mt']);?></td>
						<td align="center" valign="middle" bgcolor="#F9FAC9"><?php if($rAprob['t60_apro_mf']=='1'){echo("Si");}else{echo("No");}?></td>
						<td align="left" valign="middle" bgcolor="#F9FAC9"><?php echo( $rAprob['t60_obs_mt']);?></td>
						<td align="center" valign="middle" bgcolor="#F9FAC9"><?php if($rAprob['t60_apro_mt']=='1' && $rAprob['t60_apro_mf']=='1'){echo("Si");}else{echo("No");}?></td>
						<td align="right" valign="middle" bgcolor="#F9FAC9"><?php echo( number_format($rAprob['t60_mto_aprob'],2));?></td>
						<td align="center" valign="middle" bgcolor="#F9FAC9"><?php echo( $rAprob['t60_fch_aprob']);?></td>
					</tr>
      <?php
            $RowIndex ++;
        }
        $iRs->free();
    } else {
        ?>
        <tr>
						<td colspan="23" align="left" valign="middle"><strong
							style="color: #F00">No hay proyectos con la busqueda requerida.
								Solo se muetran los proyectos activos y que tienen registrado
								aporte por parte de Fondoempleo</strong></td>
					</tr>
       <?php  }  ?>
    </tbody>
				<tfoot>
					<tr>
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