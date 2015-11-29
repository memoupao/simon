<?php 
include("../../includes/constantes.inc.php"); 
include("../../includes/validauser.inc.php"); 

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");



$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');


$objProy = new BLProyecto();
if (!$idVersion) {
	$idVersion = $objProy->MaxVersion($idProy);
}

$proyecto = $objProy->GetProyecto($idProy, $idVersion);

$ggp = $objProy->getGerenteGestorProyecto($idProy);

$objF = new Functions();
$objML = new BLMarcoLogico();

/* $objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVersion); */


$componentes = $objML->ListadoDefinicionOE($idProy, $idVersion);

?>


<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe Preliminar</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />

</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">


<table width="700" align="center" cellpadding="0" cellspacing="0" style="border: 0px none; line-height: 15px;">
	<tbody  bgcolor="#FFFFFF">
		<tr>
			<th colspan="3" style="text-align: center; text-decoration: underline; font-size: 13px;">INFORME NRO. 125-PP-GP-2014</th>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<th align="left" valign="top" width="20%">DE</th>
			<th align="left" valign="top" width="5%">:</th>
			<td align="left" valign="top" width="75%"><?php echo $ObjSession->UserName;?><br/><?php echo $ObjSession->PerfilName;?> </td>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<th align="left" valign="top">A</th>
			<th align="left" valign="top">:</th>
			<td align="left" valign="top"><?php echo $ggp['nom_user'];?><br/>Gerente de Proyectos </td>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<th align="left" valign="top">ASUNTO</th>
			<th align="left" valign="top">:</th>
			<td align="left" valign="top">
			Estado situcion del proyecto <?php echo $proyecto['t02_cod_proy'];?> <b>"<?php echo $proyecto['t02_nom_proy'];?>"</b>
			</td>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<th align="left" valign="top">REFERENCIA</th>
			<th align="left" valign="top">:</th>
			<td align="left" valign="top">
			Solicitud  de Transferencia de Fondos
			</td>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<th align="left" valign="top">FECHA</th>
			<th align="left" valign="top">:</th>
			<td align="left" valign="top">
			<?php echo $objF->fechaEnLetras(date('d/m/Y'));?> 
			</td>
		</tr>
		<tr>
			<th colspan="3">&nbsp;</th>
		</tr>
		
		<tr>
			<th colspan="3" style="border-top: 1px solid black;">&nbsp;</th>
		</tr>
		
		
		<tr>
			<td colspan="3">
				<p>
					Es grato dirigirme a usted a fin de informarle sobre la situacion actual del proyecto indicado en el asunto, el cual es ejecutado por la institucion <?php echo $proyecto['t01_nom_inst_completo'];?>
				</p>
				<table cellpadding="0" cellspacing="0" border=0 style="border:0px;">
					<tr>
						<td style="font-weight: bold;"><b>I.</b></td>
						<td  style="font-weight: bold;text-decoration: underline;"><b>ANTECEDENTES.-</b></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<ol> 
								<li>Inicio de Actividades: <?php echo $objF->fechaEnLetras($proyecto['t02_fch_apro'])?></li>
								<li>Tiempo de ejecucion: <?php echo $proyecto['t02_num_mes'];?> meses</li>
								<li>Objetivo del proyecto: <?php echo(nl2br($proyecto['t02_fin']));?></li>
								<li>Componentes: </li>
							</ol>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table width="700" align="center" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<td><b>NRO.</b></td>
										<td><b>DEFINICION</b></td>
									</tr>
								</thead>
								<tbody class="data">
									<?php while($rwComp = mysql_fetch_array($componentes)) :?>
									<tr>
										<td>Componente <?php echo $rwComp['t08_cod_comp'];?></td>
										<td><?php echo $rwComp['t08_comp_desc'];?></td>
									</tr>
									<?php endwhile;?>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>Adicionalmente como en todos los proyecto existe un Componente del Manejo del Proyecto.</p>
							<p>El presupuesto total del proyecto es el siguiente:</p>
						</td>						
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<table width="700" align="center" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<td><b>Fuente</b></td>
										<td><b>S/. 6'220,331.56</b></td>
									</tr>
								</thead>
								<tbody class="data">
									<tr>
										<td>FONDOEMPLEO</td>
										<td>S/. 1'459,574.53</td>
									</tr>
									<tr>
										<td>Fuente 1: Southern Peru</td>
										<td>S/. 159,574.53</td>
									</tr>
								</tbody>
							</table>
							
						</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>
							<ol start= "5">
								<li>Entregables reportados y Visitas de Monitoreo realizados.</li>					
							</ol>
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<p>
								El proyecto trabajo hasta setiembre 2013 bajo la metodologia anterior (Trimestral). A partir de Octubre 2013 se implementa la nueva metodologia de productos entregables. Se han realizado un total de 5 monitoreos, 01 interno y 04 externos todos aprobados.
							</p>
						</td>
					</tr>
					
					<tr>
						<td><b>II.</b></td>
						<td style="text-decoration: underline;"><b>ANALISIS DE LA EJECUCION TECNICA Y PRESUPUESTAL.-</b></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<p>
								Respecto al avance de las actividades programadas a nivel de componentes, el proyecto se encuentra en aproximadamente un 70.97% en promedio de avance acumulado.
							</p>
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<?php
							
							$arX = array('Componente 1','Componente 2','Componente 3','Componente 4','Promedio');
							$arY = array(79.58, 63.99, 87.55, 52.76, 70.97);
							$titleGrafico = 'Grafico Nro. 01';
							 
							$vX = implode('@',$arX);
							$vY = implode('@',$arY);							
							$imageChart = constant('DOCS_PATH').'sme/reportes/chart/chart_inf_preliminar_1.php?x='.$vX.'&y='.$vY.'&title='.$titleGrafico.'&w=600&h=400';
							?>
							<p align="center">							
								<img src="<?php echo $imageChart;?>" border=0 alt="Grafico 01" align="center" style="border:1px solid black;">
							</p>
							<p align="center">
								<small>Fuente: Informe de avance Fisico Octubre 2013</small>
							</p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>
								En el caso de ejecucion presupuestal. En total, se ha desembolsado S/. 1'081,486.63 del presupuesto correspondiente a FONDOEMPLEO. En total a noviembre 2013 se ha ejecutado S/. 1'022,008.94 que representa el 94.5% del presupuesto desembolsado. (Referencia Anexo 3-Noviembre 2013). La ejecucion presupuestal representa el 70.02% del desembolso programado por Fondoempleo.
							</p>
						</td>						
					</tr>
					
					<tr>
						<td colspan="2">
							<?php
							
							$arX = array('Ejecucion Fisica Promedio','Ejecucion Presupuestal');
							$arY = array(70.97, 70.02);
							$titleGrafico = 'Grafico Nro. 02';
							 
							$vX = implode('@',$arX);
							$vY = implode('@',$arY);							
							$imageChart = constant('DOCS_PATH').'sme/reportes/chart/chart_inf_preliminar_2.php?x='.$vX.'&y='.$vY.'&title='.$titleGrafico.'&w=400&h=300';
							?>
							<p align="center">							
								<img src="<?php echo $imageChart;?>" border=0 alt="Grafico 2" align="center" style="border:1px solid black;">
							</p>
							<p align="center">
								<small>Fuente: Informe de ejecucion presupuestal correspondiente al mes de noviembre 2013</small>
							</p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
					
							<p>Comentarios:</p>
							<p>
							 El desarrollo de actividades y la ejecucion presupuestal estan siendo ejecutados casi en forma paralela.
							</p>
						
						</td>
					</tr>
					
					<tr>
						<td colspan="2">					
							<p>&nbsp;</p>						
						</td>
					</tr>
					
					<tr>
						<td colspan="2" style="border:1px solid black; text-align: center; font-weight:bold;" >					
							<p>JUSTIFICACION DESEMBOLSO</p>						
						</td>
					</tr>
					
					<tr>
						<td colspan="2">					
							<p>&nbsp;</p>						
						</td>
					</tr>
						
					<tr>
						<td colspan="2">					
							<table  width="700" align="center" cellpadding="3" cellspacing="0" style="border:0px;">
								<tr>
									<td>Importe Ultimo Entregable</td>
									<td>S/. 256,659.84</td>
								</tr>
								<tr>
									<td>Excedente por Ejecutar (A finales de noviembte 2013)</td>
									<td>S/.256,659.84</td>
								</tr>
								<tr>
									<td>Total ejecutado respecto al ultimo desembolso (76.83%)</td>
									<td>S/. 59,477.69</td>
								</tr>								
							</table>						
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>Justificar si el Excedente por Ejecutar esta comprometido</p>
							<br/>
						</td>
					</tr>
					
					<tr>
						<td colspan="2" style="border:1px solid black; text-align: justify;padding: 10px;" >					
							<p>
								Respecto al entregable se ha ejecutado el 76.83% del ultimo entregable; por lo que el excedente que representa el 23.17% se encuentra comprometido por el importe de 47,435.60, quedando un saldo del excedente de S/. 12,042.24 nuevos soles, como se observa en el sustento de movimiento de cuenta.
							</p>
							<br/>						
						</td>
					</tr>
					
					
					<tr>
						<td colspan="2">					
							<p>
								<p>
									Conclusion: Se debe realizar el desembolso correspondiente al adelanto del segundo entregable que corresponde a <strong>S/. 77,578.17</strong>
									</p>
									<br/>									
									<p>Antentamente,</p>
								
							</p>						
						</td>
					</tr>
					<tr>
						<td colspan="2">					
							<p>&nbsp;</p>						
						</td>
					</tr>
					<tr>
						<td colspan="2">					
							<p>
							Maximo M. Palacio Aymara<br/>
							Gestor de Proyecto<br/>
							FONDOEMPLEO<br/>
							</p>						
						</td>
					</tr>
					
									
				</table>				
			</td>
		</tr>
	
	
		

	</tbody>

</table>


</div>

<script>
$(document).ready(function(){
	var logo  = $('img[onclick="AjusteTamanioRPT();"]');
	$('img[onclick="AjusteTamanioRPT();"]').addClass('removed');
	$('img[onclick="AjusteTamanioRPT();"]').parent().parent().find('td').eq(2).html('');
	$('img[onclick="AjusteTamanioRPT();"]').parent().parent().find('td').eq(2).append(logo);
	$('img.remove').remove();
});
	
</script>

<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>