<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php

require_once (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");
require_once (constant("PATH_CLASS") . "BLFuentes.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$objInf = new BLMonitoreoFinanciero();
$objProy = new BLProyecto();
$objRep = new BLReportes();
$HardCode = new HardCode();
$objTablas = new BLTablasAux();
$objFuentes = new BLFuentes();

$idProy = $objFunc->__Request('idProy');
$idInforme = $objFunc->__Request('idNum');
$idVs = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVs);
// $rowinf = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);
$rowinf = $objInf->Inf_visita_MF_Seleccionar($idProy, $idInforme);

$rsSector = $objProy->SectoresProductivos_Listado($idProy);
$row = $objRep->RepFichaProy($idProy, $idVs);

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Detalle del Informe de Monitoreo Financiero</title>

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script src="../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></script>
<!-- InstanceEndEditable -->
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
  <?php
}
?>
   
  <div class="TableGrid">
			<table width="700" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td width="24%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Número del Informe</strong></td>
						<td colspan="2" align="left" valign="middle"><strong><?php echo($idInforme);?></strong></td>
						<td colspan="2" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Código
								del Proyecto</strong></td>
						<td width="34%" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Periodo
								Referencia</strong></td>
						<td colspan="5" align="left" valign="middle"><?php if($rowinf['t52_tipo_vis']=='2') { ?>
            <strong> <?php echo(  $rowinf['t52_periodo']);?> </strong>
          <?php } ?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Presentación</strong></td>
						<td colspan="5" align="left" valign="middle"><strong><?php echo($rowinf['t52_fch_pre']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#FFFFAA"><strong>Supervisor
								del Proyecto</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['jefe_proy']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Temático</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['moni_tema']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Financiero</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['moni_fina']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Externo</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['moni_exte']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Título
								del Proyecto</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>
					</tr>
        <?php while($rsS = mysqli_fetch_assoc($rsSector))  { ?>
        <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Sector
								Productivo</strong></td>
						<td colspan="2" align="left" valign="middle"><?php echo($rsS['sector']);?></td>
						<td colspan="2" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Subsector</strong></td>
						<td align="left" valign="middle"><?php echo($rsS['subsector']);?></td>
					</tr>
        <?php }?>
        <?php
        
        $rsAmbito = $objProy->AmbitoGeo_Listado($idProy, $idVs);
        $rowspan = mysqli_num_rows($rsAmbito);
        ?>
        <tr style="font-size: 11px;">
						<td rowspan="<?php echo($rowspan+2);?>" align="left"
							valign="middle" bgcolor="#E8E8E8"><strong>Ubicación</strong></td>
						<td colspan="5" align="center" valign="middle"
							style="display: none;">&nbsp;</td>
					</tr>
					<tr style="font-size: 11px;">
						<td width="24%" height="23" align="center" valign="middle"
							bgcolor="#E8E8E8"><strong>Departamento</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Provincia</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Distrito</strong></td>
					</tr>
        <?php while($r = mysqli_fetch_assoc($rsAmbito))  { ?>
        <tr style="font-size: 11px;">
						<td height="25" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dpto']);?></td>
						<td colspan="3" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['prov']);?></td>
						<td colspan="3" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dist']);?></td>
					</tr>
        <?php
        }
        $rsAmbito->free();
        ?>
        <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Propósito
								del Proyecto</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_pro']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Institución
								Ejecutora</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t01_nom_inst']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Instituciones
								Colaboradoras</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo($row['inst_colabora']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Población
								Beneficiaria</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								real de Inicio del proyecto</strong></td>
						<td colspan="5" align="left" valign="middle"><?php echo ($row['t02_fch_ini']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								programada para el término del proyecto</strong></td>
						<td colspan="6" align="left" valign="middle"><?php echo ($row['t02_fch_ter']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8">&nbsp;</td>
						<td colspan="6" align="left" valign="middle">&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<br />
			<div id="divCdrAvancePresup" style="display: none;"></div>
			<br />

			<div id="divCdrAvancePFisico" style="display: none;"></div>
			<p>&nbsp;</p>

			<table width="900" border="0" cellspacing="0" cellpadding="0"
				style="border: none;">
				<tr>
					<td><table width="100%" border="0" cellpadding="0" cellspacing="1">
							<tr>
								<td height="30"><strong style="font-size: 15px;">
             <?php if($rowinf['t52_tipo_vis']=='1'){echo('Visita Previa al Inicio del Proyecto');} ?>
             <?php if($rowinf['t52_tipo_vis']=='2'){echo(' Visita durante la Ejecución del proyecto');} ?>
              </strong></td>
							</tr>
							<tr>
								<td width="82%"><legend>
										<strong>Persona Entrevistada</strong>
									</legend>
									<table border="0" cellspacing="0" cellpadding="0" width="900"
										style="padding: 1px;" class="TableEditReg">
										<tr>
											<td width="78" height="18" valign="top"><strong>Nombre:</strong></td>
											<td width="620" valign="top"><?php echo($rowinf['t52_nom_per_ent'])?></td>
										</tr>
										<tr>
											<td width="78" height="18" valign="top"><strong>Cargo:</strong></td>
											<td width="620" valign="top"><?php echo($rowinf['t52_car_per_ent'])?></td>
										</tr>
									</table> <br />
                <?php if($rowinf['t52_tipo_vis']=='1') { ?>
              
              <table width="900" border="0" cellpadding="0"
										cellspacing="0" class="TableEditReg">
										<tbody style="border: solid 1px #666;" class="data">
											<tr style="border: solid 1px, #666;">
												<td height="21" colspan="3" valign="top"
													style="background-color: #FFF;"><strong>CUESTIONARIO</strong></td>
											</tr>
											<tr style="border: solid 1px, #666;">
												<td width="39%" height="21" valign="top"
													style="background-color: #E9E9E9;"><strong>DETALLE</strong></td>
												<td width="8%" valign="top"
													style="background-color: #E9E9E9;"><strong>SI /NO</strong></td>
												<td width="52%" valign="top"
													style="background-color: #E9E9E9;"><strong>COMENTARIOS</strong></td>
											</tr>
											<tr>
												<td height="21" colspan="3" valign="top"><strong>Información
														Contable Presupuestal</strong></td>
											</tr>
											<tr>
												<td width="39%" height="35" valign="top"><p>
														La Institución cuenta con un <br /> programa de
														contabilidad adecuado?
													</p></td>
												<td width="8%" align="center" valign="middle">
                        <?php if($rowinf['t52_quest_01']=='1'){echo('Si');}else{echo('No');} ?>
                       </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_01']);?>
                       </td>
											</tr>
											<tr>
												<td width="39%"><em
													style="padding-left: 30px; display: block;">a) La
														contabilidad institucional&nbsp;es llevada con el sistema
														contable referido?</em></td>
												<td width="8%" align="center" valign="middle"> <?php if($rowinf['t52_quest_02']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_02']);?></td>
											</tr>
											<tr>
												<td height="26"><em
													style="padding-left: 30px; display: block;">b) El programa
														de contabilidad&nbsp; permite manejar la ejecución
														presupuestal por proyectos de acuerdo a lo requerido por
														FE? </em></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_03']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_03']);?></td>
											</tr>
											<tr>
												<td height="25"><em
													style="padding-left: 30px; display: block;">c) La
														contabilidad institucional se encuentra al día?</em><br />
												</td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_04']=='1'){echo('Si');}else{echo('No');} ?></td>
												<td valign="middle"><?php echo($rowinf['t52_obs_04']);?></td>
											</tr>
											<tr>
												<td width="39%" height="35" valign="top">Los registros
													contables de las operaciones institucionales y de&nbsp; los
													proyectos se realizan de acuerdo a las normas y principios
													contables?</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_05']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_05']);?></td>
											</tr>
											<tr>
												<td width="39%" valign="top">La Institución ha implementado
													las cuentas contables y/o anexos&nbsp; dentro del sistema
													contable institucional, que permita diferenciar las
													operaciones del proyecto de las operaciones ajenas ( de
													otros proyectos o&nbsp; propios de LA INSTITUCION),</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_06']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_06']);?></td>
											</tr>
											<tr>
												<td width="39%" valign="top">Los análisis de cuentas que
													arroje el sistema contable sirven de base para los Anexos
													solicitados por FONDOEMPLEO</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_07']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_07']);?></td>
											</tr>
										</tbody>
									</table>
              
             <?php } ?>
              
              
               <?php if($rowinf['t52_tipo_vis']=='2') { ?>
               
               <table width="900" border="0" cellpadding="0"
										cellspacing="0" class="TableEditReg">
										<tbody style="border: solid 1px #666;" class="data">
											<tr style="border: solid 1px, #666;">
												<td width="39%" height="21" valign="top"
													style="background-color: #E9E9E9;"><strong>DETALLE</strong></td>
												<td width="8%" valign="top"
													style="background-color: #E9E9E9;"><strong>SI /NO</strong></td>
												<td width="52%" valign="top"
													style="background-color: #E9E9E9;"><strong>COMENTARIOS</strong></td>
											</tr>
											<tr>
												<td height="21" colspan="3" valign="top"><strong>I.
														Información Administrativa </strong></td>
											</tr>
											<tr>
												<td width="39%" height="35" valign="top"><p>&iquest;Se
														cuenta con la relación de todo el personal técnico y
														administrativo de la Institución?</p></td>
												<td width="8%" align="center" valign="middle">
                            <?php if($rowinf['t52_quest_01']=='1'){echo('Si');}else{echo('No');} ?>
                          </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_01']);?>
                          </td>
											</tr>
											<tr>
												<td>&iquest;Cuenta un registro adecuado de control de
													personal?</td>
												<td align="center" valign="middle">
                            <?php if($rowinf['t52_quest_02']=='1'){echo('Si');}else{echo('No');} ?> 
                          </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_02']);?>
                          </td>
											</tr>
											<tr>
												<td>&iquest;La institución cumple con los procedimientos
													Administrativos según su manual enviado a FE?</td>
												<td align="center" valign="middle">
                            <?php if($rowinf['t52_quest_03']=='1'){echo('Si');}else{echo('No');} ?> 
                          </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_03']);?>
                          </td>
											</tr>
											<tr>
												<td colspan="3"><strong>II. Información contable
														presupuestal y Control Interno</strong></td>
											</tr>
											<tr>
												<td width="39%"><em
													style="padding-left: 30px; display: block;">a) La
														contabilidad institucional&nbsp;es llevada con el sistema
														contable referido?</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_04']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_04']);?></td>
											</tr>
											<tr>
												<td height="26"><em
													style="padding-left: 30px; display: block;">b) El programa
														de contabilidad&nbsp; permite manejar la ejecución
														presupuestal por proyectos de acuerdo a lo requerido por
														FE?</td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_05']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_05']);?></td>
											</tr>
											<tr>
												<td height="25"><em
													style="padding-left: 30px; display: block;">c) La
														contabilidad institucional se encuentra al día?<br /></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_06']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_06']);?></td>
											</tr>
											<tr>
												<td width="39%" height="35" valign="top">Los registros
													contables de las operaciones institucionales y de&nbsp; los
													proyectos se realizan de acuerdo a las normas y principios
													contables?</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_07']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_07']);?></td>
											</tr>
											<tr>
												<td width="39%" valign="top">La Institución ha implementado
													las cuentas contables y/o anexos&nbsp; dentro del sistema
													contable institucional, que permita diferenciar las
													operaciones del proyecto de las operaciones ajenas ( de
													otros proyectos o&nbsp; propios de LA INSTITUCION),</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_08']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_08']);?></td>
											</tr>
											<tr>
												<td width="39%" valign="top">Los análisis de cuentas que
													arroje el sistema contable sirven de base para los Anexos
													solicitados por FONDOEMPLEO</td>
												<td width="8%" align="center" valign="middle"><?php if($rowinf['t52_quest_09']=='1'){echo('Si');}else{echo('No');} ?></td>
												<td width="52%" valign="middle"><?php echo($rowinf['t52_obs_09']);?></td>
											</tr>
											<tr>
												<td valign="top">Se han presentado los Informes Financieros
													de Ejecución Presupuestal en las fechas establecidas</td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_10']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_10']);?></td>
											</tr>
											<tr>
												<td colspan="3" valign="top"><h3>Control interno.</h3></td>
											</tr>
											<tr>
												<td colspan="3" valign="top"><p>
														<strong>Logística o Abastecimiento</strong>
													</p></td>

											</tr>
											<tr>
												<td><ul>
														<li>Las compra mayor a S/. 2,000.00 cuentan con las tres
															cotizaciones.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_11']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_11']);?></td>
											</tr>
											<tr>
												<td height="26"><ul>
														<li>Las adquisiciones mayores a S/. 7,000.00&nbsp; cuentan
															con la aprobación previa de FONDOEMPLEO.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_12']=='1'){echo('Si');}else{echo('No');} ?></td>
												<td valign="middle"><?php echo($rowinf['t52_obs_12']);?> </td>
											</tr>
											<tr>
												<td colspan="3" valign="top"><p>
														<strong>Tesorería</strong>
													</p></td>
											</tr>
											<tr>
												<td><ul>
														<li>Cuenta con un control detallado por cada cuenta
															bancaria que maneje para fines del Proyecto.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_13']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_13']);?></td>
											</tr>
											<tr>
												<td height="26"><ul>
														<li>Se han realizado operaciones de transferencias a otras
															cuentas no relacionadas con el proyecto.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_14']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_14']);?></td>
											</tr>
											<tr>
												<td valign="top"><ul>
														<li>La institución ha realizado arqueos al Fondos fijo
															destinado al proyecto</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_15']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_15']);?></td>
											</tr>
											<tr>
												<td valign="top"><ul>
														<li>Las entregas a rendir están siendo liquidadas en los
															plazos establecidos (tres días máximo de efectuado el
															gasto).</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_16']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_16']);?></td>
											</tr>
											<tr>
												<td colspan="3" valign="top"><p>
														<strong>Activos Fijos</strong>
													</p></td>
											</tr>
											<tr>
												<td><ul>
														<li>La institución cuenta registro de los Activos Fijos
															adquiridos con fondos del proyecto.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_17']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_17']);?></td>
											</tr>
											<tr>
												<td height="26"><ul>
														<li>Los Activos fijos adquiridos están siendo usados para
															los fines del proyecto.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_18']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_18']);?></td>
											</tr>
											<tr>
												<td colspan="3"><strong>III. Fondo de Crédito</strong></td>
											</tr>
											<tr>
												<td><ul>
														<li>&iquest;La institución ha tenido alguna experiencia
															en manejo de fondos de crédito?</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_19']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_19']);?></td>
											</tr>
											<tr>
												<td height="26"><ul>
														<li>La Cuenta con un Manual de Manejo de créditos.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_20']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_20']);?></td>
											</tr>
											<tr>
												<td><ul>
														<li>El manejo del crédito, tanto de los gastos e ingresos
															por recuperaciones están siendo&nbsp; manejados en una
															cuenta bancaria separada y exclusiva para este fin.</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_21']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_21']);?></td>
											</tr>
											<tr>
												<td height="26"><ul>
														<li>Cuenta con una lista de beneficiarios que apliquen el
															crédito.</li>
													</ul></td>
												<td align="center" valign="middle"> <?php if($rowinf['t52_quest_22']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_22']);?></td>
											</tr>
											<tr>
												<td valign="top"><ul>
														<li>Los créditos que otorgue la Institución a los
															beneficiarios del proyecto, están siendo reportados a
															FONDOEMPLEO dentro de los informes mensuales,&nbsp; tal
															como se establece en el Anexo D del Convenio,</li>
													</ul></td>
												<td align="center" valign="middle"><?php if($rowinf['t52_quest_23']=='1'){echo('Si');}else{echo('No');} ?> </td>
												<td valign="middle"><?php echo($rowinf['t52_obs_23']);?></td>
											</tr>
											<tr>
												<td valign="top">&nbsp;</td>
												<td align="center" valign="middle">&nbsp;</td>
												<td valign="middle">&nbsp;</td>
											</tr>
											<tr>
												<td valign="top">&nbsp;</td>
												<td align="center" valign="middle">&nbsp;</td>
												<td valign="middle">&nbsp;</td>
											</tr>
										</tbody>
									</table>
               
               <?php } ?>
            </td>
							</tr>

						</table> <br />
					<br /></td>
				</tr>

				<tr>
					<td height="42"><strong>Conclusiones</strong> <br />
						<div
							style="border: solid 1px #666; min-height: 30px; max-width: 900px; text-align: left;">
        <?php echo(nl2br($rowinf['t52_conclu']));?>
        </div> <br /> <br /> <strong style="text-align: left;">Calificción</strong><br />
         <?php
        $rCal = $objTablas->TipoSeleccionar($rowinf['t52_califica']);
        echo ($rCal['descrip']);
        ?>
           <br />
					<br /></td>
				</tr>
				<tr>
					<td height="42"><strong>Documentos Anexos</strong><br />
						<table width="900" border="0" cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="215" align="center" valign="middle" nowrap="nowrap"><strong>Nombre
											del Archivo</strong></td>
									<td width="481" height="23" align="center" valign="middle"><strong>Descripcion
											del Archivo</strong></td>
								</tr>
              <?php
            $iRs = $objInf->Inf_visita_MF_ListaAnexos($idProy, $idInforme);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
              <tr>
                <?php
                    $urlFile = $row['t52_url_file'];
                    $filename = $row['t52_nom_file'];
                    $path = constant('APP_PATH') . "sme/monitoreofe/informes/inf_visita_mf/";
                    $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
                    ?>
                <td height="30" align="center" valign="middle"><a
										href="<?php echo($href);?>" title="Descargar Archivo"
										target="_blank"><?php echo($row['t52_nom_file']);?></a></td>
									<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t52_desc_file']);?></td>
								</tr>
              <?php
                    $RowIndex ++;
                }
                $iRs->free();
            } // Fin de Anexos Fotograficos
            ?>
            </tbody>
							<tfoot>
								<tr>
									<td colspan="2" align="center" valign="middle">&nbsp; <iframe
											id="ifrmUploadFile" name="ifrmUploadFile"
											style="display: none;"></iframe>
										<div id="divLoadingAnexo"
											style="width: 99%; background-color: #FFF;"></div></td>
								</tr>
							</tfoot>
						</table>
						<p>
							<br />
						</p></td>
				</tr>
			</table>
			<p>&nbsp;</p>
		</div>

		<script language="javascript" type="text/javascript">
  <?php
$ls_urlRpt_Presup = constant("PATH_SME") . "reportes/rpt_avc_financ_presup.php?idProy=" . $idProy . "&idFuente=10&ini=" . $rowinf['t52_per_ini'] . "&ter=" . $rowinf['t52_per_fin'];
$ls_urlRpt_Fisico = constant("PATH_SME") . "reportes/rpt_avc_financ_fisico.php?idProy=" . $idProy . "&ini=" . $rowinf['t52_per_ini'] . "&ter=" . $rowinf['t52_per_fin'];

if ($rowinf['t52_tipo_vis'] == '2') {
    echo ("LoadAvancesPresu_Fisico();");
}

?>
  
    function LoadAvancesPresu_Fisico()
	{
		$("#divCdrAvancePFisico").css("display","block") ;
		$("#divCdrAvancePresup").css("display","block")
		$("#divCdrAvancePFisico").load("<?php echo($ls_urlRpt_Fisico);?>") ;
		$("#divCdrAvancePresup").load("<?php echo($ls_urlRpt_Presup);?>") ;
		
	}

  </script>
  
  <?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>