<?php 
include ("../../includes/constantes.inc.php"); 
include ("../../includes/validauser.inc.php"); 


require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLInformes.class.php");


$idProy = $objFunc->__Request('idProy');

$objProy = new BLProyecto();

$ls_version = $objProy->MaxVersion($idProy);

$Proy = $objProy->GetProyecto($idProy, $ls_version);

?>


<?php  if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cronograma de Visitas ME</title>
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



			<table width="90%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="8%" align="left">PROYECTO</th>
					<td width="65%" align="left"><?php  echo($Proy['t02_cod_proy']);?></td>
					<th width="7%" align="left" nowrap="nowrap">INICIO:</th>
					<td width="20%" align="left"><?php echo($Proy['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">&nbsp;</th>
					<td align="left"><?php  echo($Proy['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TéRMINO:</th>
					<td align="left"><?php echo($Proy['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td align="left"><?php echo($Proy['t01_sig_inst']." - ".$Proy['t01_nom_inst']);?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<table width="90%" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<tbody class="data">
					<tr>
						
						<td rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Codigo</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Año</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Entregable</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Periodo</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Estado</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Visita</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Supervisor</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Costo</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Informe de Supervición</strong>
						</td>
					</tr>
					<tr>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Inicio</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Término</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Rol</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Nombre</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>1er. Pago</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>2do. Pago</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Entregado</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Fecha</strong>
						</td>														
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    
    $objInf = new BLInformes();    
    $iRs = $objInf->getListadoVisitasProyecto($idProy, $ls_version);
    
    if (mysql_num_rows($iRs) > 0) {
        $fecprogra = true;
        while ($row = mysql_fetch_array($iRs)) {
            ?>
    <tr>

						<td width="41" height="30" align="center" valign="middle"><?php echo($row['t02_cod_proy']);?></td>
						<td align="center" valign="middle" nowrap="nowrap" style="text-transform: capitalize;"><?php echo( $row['anio']);?></td>						
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['entregable']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['periodo']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['estado_text']);?></td>
						<td align="center" valign="middle">
							<?php 
							echo $row['fecha_visita_inicio'];
							?>	   
       					</td>
						<td align="center" valign="middle">
							<?php 							
							echo $row['fecha_visita_termino'];
							?>
						</td>
						<td align="center" valign="middle" nowrap="nowrap" style="text-transform: uppercase;">
							<?php 							
								echo $row['supervisor_cargo'];
							?>						
						</td>
						<td align="center" valign="middle" style="text-transform: capitalize;">
							<?php 							
								echo $row['supervisor_nombres'];
							?>				
						</td>
						<td align="right" valign="middle">						
							<?php echo number_format($row['costo_pago_1'],2, '.',',');?>
						</td>
						
						<td align="right" valign="middle">
							<?php echo number_format($row['costo_pago_2'],2, '.',',');?>
						</td>
						<td align="center" valign="middle">
							<?php 							
								if (empty($row['fec_pre_inf_sup'])) {
									echo 'No';
								} else {
									echo 'Si';
								}
							?>
						</td>
						<td align="center" valign="middle">
							<?php echo $row['fec_pre_inf_sup'];?>
						</td>
					</tr>
    <?php
            
        }
        
    } 
    ?>
  </tbody>
				
			</table>
			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>