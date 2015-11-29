<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");

$aTipoInst = $objFunc->__Request('tinst');
$aTipoInst = ! isset($aTipoInst) || is_nan($aTipoInst) || ! $aTipoInst ? '*' : $aTipoInst;
?>
<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Relación de Proyectos</title>
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
		<?php
$objRep = new BLReportes();
$rowD = $objRep->RelFichaProy($aTipoInst);
?>
  
		<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="3%" height="33" align="center" valign="middle">Código</td>
						<td width="1%" align="center" valign="middle">Institución</td>
						<td width="2%" align="center" valign="middle">Fecha de Fundación</td>
						<td width="2%" align="center" valign="middle">Tipo Institución</td>
						<td width="2%" align="center" valign="middle">Proyecto</td>
						<td width="2%" align="center" valign="middle">Finalidad</td>
						<td width="2%" align="center" valign="middle">Propósito</td>
						<td width="2%" align="center" valign="middle">Ambito Geográfico</td>
						<td width="2%" align="center" valign="middle">Región</td>
						<td width="2%" align="center" valign="middle">Sector</td>
						<td width="2%" align="center" valign="middle">Subsector</td>
						<td width="2%" align="center" valign="middle">Fecha de Aprobación</td>
						<td width="2%" align="center" valign="middle">Fecha de Inicio</td>
						<td width="2%" align="center" valign="middle">Fecha de Término</td>
						<td width="2%" align="center" valign="middle">Fecha de Inicio Real</td>
						<td width="2%" align="center" valign="middle">Fecha de Término
							Real</td>
						<td width="2%" align="center" valign="middle">Número Meses</td>
						<td width="2%" align="center" valign="middle">Número Meses
							Ampliación</td>
						<td width="2%" align="center" valign="middle">Meses Ejecución
							Restantes</td>
						<td width="2%" align="center" valign="middle">Presupuesto Total</td>
						<td width="2%" align="center" valign="middle">Presupuesto
							FondoEmpleo</td>
						<td width="2%" align="center" valign="middle">Presupuesto Otras
							Fuentes</td>
						<td width="2%" align="center" valign="middle">Desembolsado FE</td>
						<td width="2%" align="center" valign="middle">Gasto<br />reportado<br />financiamiento<br />FE
						</td>
						<td width="2%" align="center" valign="middle">Gasto<br />reportado<br />financiamiento<br />Otros
						</td>
						<td width="2%" align="center" valign="middle">Total<br />gasto<br />reportado
						</td>
						<td width="2%" align="center" valign="middle">Descripción
							Beneficiarios</td>
						<td width="2%" align="center" valign="middle">Total Beneficiarios</td>
						<td width="2%" align="center" valign="middle">Total Personas
							Capacitadas en Producción</td>
						<td width="2%" align="center" valign="middle">Total Personas
							Capacitadas en Gestión</td>
						<td width="2%" align="center" valign="middle">Total Personas
							Capacitadas en Comercialización</td>
						<td width="2%" align="center" valign="middle">Total Personas
							Capacitadas en Emprendedurismo</td>
						<td width="2%" align="center" valign="middle">Total Personas con
							AT en Producción</td>
						<td width="2%" align="center" valign="middle">Total Personas con
							AT en Gestión</td>
						<td width="2%" align="center" valign="middle">Total Personas con
							AT en Comercialización</td>
						<td width="2%" align="center" valign="middle">Total Personas
							Créditos</td>
						<td width="2%" align="center" valign="middle">Total Personas Otros
							Servicios</td>
						<td width="2%" align="center" valign="middle">Avance Propósito</td>
						<td width="2%" align="center" valign="middle">Avance Componentes</td>
						<td width="2%" align="center" valign="middle">Avance Total Sub
							Actividades</td>
						<td width="2%" align="center" valign="middle">Avance Acumulado Sub
							Actividades</td>
						<td width="6%" align="center" valign="middle">Monitor Técnico</td>
						<td width="4%" align="center" valign="middle">Monitor Financiero</td>
						<td width="7%" align="center" valign="middle">Monitor Externo</td>
						<td width="7%" align="center" valign="middle">Estado del Proyecto</td>
						<td width="7%" align="center" valign="middle">N&ordm; Visitas ME</td>
						<td width="7%" align="center" valign="middle">Ultima Visita ME</td>
						<td width="7%" align="center" valign="middle">Calificación ME</td>
						<td width="7%" align="center" valign="middle">N&ordm; Visitas
							Momitor FE</td>
						<td width="7%" align="center" valign="middle">Ultima Visita
							Monitor FE</td>
						<td width="7%" align="center" valign="middle">Calificación
							Monitor FE</td>
						<td width="2%" align="center" valign="middle">Dirección Proyecto</td>
						<td width="2%" align="center" valign="middle">Ciudad</td>
						<td width="2%" align="center" valign="middle">Teléfono</td>
						<td width="2%" align="center" valign="middle">Fax</td>
						<td width="2%" align="center" valign="middle">Mail</td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
			<?php
$Index = 1;
while ($row = mysqli_fetch_assoc($rowD)) {
    ?>
				<tr style="font-size: 11px;">
						<td align="left" valign="middle"><?php echo $row['codigo']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t01_nom_inst']; ?> </td>
						<td align="left" valign="middle"><?php echo $row['t01_fch_fund']; ?></td>
						<td align="left" valign="middle"><?php echo $row['tipo_inst']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_nom_proy']; ?> </td>
						<td align="left" valign="top"><?php echo $row['t02_fin']; ?> </td>
						<td align="left" valign="top"><?php echo $row['t02_pro']; ?></td>
						<td align="left" valign="top"><?php echo $row['t02_amb_geo']; ?></td>
						<td align="left" valign="middle"><?php echo $row['region']; ?></td>
						<td align="left" valign="middle"><?php echo $row['sector']; ?></td>
						<td align="left" valign="middle"><?php echo $row['subsector']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_fch_apro']; ?> </td>
						<td align="left" valign="middle"><?php echo $row['t02_fch_ini']; ?> </td>
						<td align="left" valign="middle"><?php echo $row['t02_fch_ter']; ?> </td>
						<td align="left" valign="middle"><?php echo $row['t02_fch_ire']; ?> </td>
						<td align="left" valign="middle"><?php echo $row['t02_fch_tre']; ?> </td>
						<td align="center" valign="middle"><?php echo $row['t02_num_mes']; ?></td>
						<td align="center" valign="middle"><?php echo $row['t02_num_mes_amp']; ?></td>
						<td align="center" valign="middle"><?php echo $row['num_mes_ejec_rest']; ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['pres_tot'], 2); ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['t02_pres_fe'], 2); ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['t02_pres_otro'], 2); ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['total_desem_fe'], 2); ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['gasto_fe'], 2); ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['gasto_otros'], 2); ?></td>
						<td align="right" valign="middle"><?php echo number_format($row['gasto_fe'] + $row['gasto_otros'], 2); ?></td>
						<td align="left" valign="top"><?php echo $row['t02_ben_obj']; ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['num_tot_benef']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_cap_prod']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_cap_gest']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_cap_comer']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_cap_autoest']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_at_prod']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_at_gest']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_at_comer']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_cred']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['tot_ben_otros']); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['avance_prop'], 2); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['avance_comp'], 2); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['avance_total_sub'], 2); ?></td>
						<td align="center" valign="middle"><?php echo number_format($row['avance_acum_sub'], 2); ?></td>
						<td align="left" valign="middle"><?php echo $row['moni_tema']; ?></td>
						<td align="left" valign="middle"><?php echo $row['moni_fina']; ?></td>
						<td align="left" valign="middle"><?php echo $row['moni_exte']; ?></td>
						<td align="left" valign="middle"><?php echo $row['estado']; ?></td>
						<td align="center" valign="middle"><?php echo $row['nro_visitas_me']; ?></td>
						<td align="center" valign="middle"><?php echo $row['fch_ultima_visita_me']; ?></td>
						<td align="center" valign="middle"><?php echo $row['califica_me']; ?></td>
						<td align="center" valign="middle"><?php echo $row['nro_visitas_mfe']; ?></td>
						<td align="center" valign="middle"><?php echo $row['fch_ultima_visita_mfe']; ?></td>
						<td align="center" valign="middle"><?php echo $row['califica_mfe']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_dire_proy']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_ciud_proy']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_tele_proy']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_fax_proy']; ?></td>
						<td align="left" valign="middle"><?php echo $row['t02_mail_proy']; ?></td>
					</tr>
			<?php 
				$Index++;
				} //End While
				$rowD->free();
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
<?php } ?>