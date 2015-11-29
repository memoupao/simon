<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");

$idProy = $objFunc->__Request('idProy');
$id = $objFunc->__Request('idNOC');

$row = 0;
$objML = new BLMarcoLogico();
$objPresup = new BLPresupuesto();
$objProy = new BLProyecto();
$HC = new HardCode();

$rproy = $objProy->ProyectoSeleccionar($idProy, 1);

$idVersion = 1;

$row = $objProy->NoObjecionCompra_Seleccionar($idProy, $id);
$objProy = NULL;

?>


<?php  if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Solicitud de Cambio de Personal</title>


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



			<table width="774" border="0" cellpadding="0" cellspacing="2"
				class="TableEditReg">
				<tr>
					<td colspan="5"></td>
				</tr>

				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td width="685" colspan="3" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle"><strong>Proyecto:</strong></td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$row['t02_cod_proy']){echo(' height:15px;');}; ?>">
          <?php echo($rproy['t02_cod_proy'].' - '.$rproy['t02_nom_proy']); ?>
       </div>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle"><strong>Institución:</strong></td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$row['t01_sig_inst']){echo(' height:15px;');}; ?>">
          <?php echo($rproy['t01_sig_inst']); ?>
        </div>
					</td>
				</tr>
				<tr>
					<td width="5" align="left" valign="middle">&nbsp;</td>
					<td width="117" align="left" valign="middle"><strong>Solicitud de
							Compra:</strong></td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	   <?php echo($row['t02_sco_noc']); ?>
   	  </div>

					</td>
				</tr>

				<tr>
					<td></td>
					<td height="28" colspan="4">
						<fieldset style="padding: 1px;">
							<legend>Partida Afectada</legend>
							<table width="100%" class="TableEditReg"
								style="padding: 1px; border: #EEE">
								<tr>
									<td align="left">&nbsp;</td>
									<td height="28" align="left"><strong>Componente</strong><strong>:</strong></td>
									<td align="left">

										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
                   <?php echo($row['componente']); ?>
                  </div>
									</td>
									<td width="2%">&nbsp;</td>
								</tr>
								<tr>
									<td width="2%" align="left">&nbsp;</td>
									<td width="16%" align="left"><strong>Actividad:</strong></td>
									<td width="80%" align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
                   <?php echo($row['actividad']); ?>
                  </div>

									</td>
									<td align="right" nowrap="nowrap">&nbsp;</td>
								</tr>

								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>SubActividad:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
                  <?php echo($row['subactividad']); ?>
                  </div>
									</td>
									<td align="right">&nbsp;</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Categoria de Gastos:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
                  <?php echo($row['categoria']); ?>
                 </div>

									</td>
									<td align="right">&nbsp;</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>

				<tr>
					<td height="28" colspan="5">


						<table width="780" border="0" align="center" cellpadding="0"
							class="TableEditReg" style="padding: 1px; border: #EEE">
							<tr>
								<td height="21" align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>

								<td width="1" align="left">&nbsp;</td>
								<td width="3" align="left">&nbsp;</td>
								<td width="173" align="left"><strong>Saldo de la Partida a
										Afectar:</strong></td>
								<td width="122" align="left">
									<div class="ClassText"
										style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	   			<?php echo($row['saldo']); ?>
   	  			</div>


								</td>
								<td width="129" align="right"><strong>Importe Solicitado:</strong></td>
								<td width="369" align="left">
									<div class="ClassText"
										style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	   			<?php echo($row['importe']); ?>
   	  			</div>
								</td>
							</tr>
							<tr>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td colspan="3" align="left">&nbsp;</td>
							</tr>
							<tr>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left"><strong>Observación</strong>:</td>
								<td colspan="3" align="left"><div class="ClassText" style="border:1px solid #A0A0A4; padding:5px; margin-right:5px; <?php if(!$row['t02_obs_noc']){echo(' height:15px;');}; ?>">
                <?php echo($row['t02_obs_noc']); ?>
              </div></td>
							</tr>
							<tr>
								<td height="23" align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
								<td align="left">&nbsp;</td>
							</tr>
							<tr>
								<td></td>
								<td colspan="5" align="left" valign="middle">
			   <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
                <fieldset style="padding: 1px;">
										<legend>Aprobación</legend>
										<table width="800px" style="border: #EEE;">
											<tr>
												<td align="left">&nbsp;</td>
												<td align="left"><strong>Cuadro Comparativo de Proveedores:</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
	 				<?php if($row['t02_ccp_noc']=='S'){echo('Si');}else{if($row['t02_ccp_noc']=='N'){echo('No');}else{echo('');}}?>
                   	</div>
												</td>
												<td align="left"><strong>Cotizaciones de Proveedores</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
	 				<?php if($row['t02_cop_noc']=='S'){echo('Si');}else{if($row['t02_cop_noc']=='N'){echo('No');}else{echo('');}}?>
                   	</div>
												</td>
												<td align="left">&nbsp;</td>
											</tr>
											<tr>
												<td align="left">&nbsp;</td>
												<td align="left"><strong>Aprobacion del Gestor de Proyectos</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
	 				<?php if($row['t02_amt_noc']=='S'){echo('Si');}else{if($row['t02_amt_noc']=='N'){echo('No');}else{echo('');}}?>
                   	</div>
												</td>
												
												<?php /* ?>
												<td align="left"><strong>Aprobacion de Monitor Financiero</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
	 				<?php if($row['t02_amf_noc']=='S'){echo('Si');}else{if($row['t02_amf_noc']=='N'){echo('No');}else{echo('');}}?>
                   	</div>
												</td>
												<?php */ ?>
												
												<td align="left"><strong>Responsable de Area</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
                      <?php if($row['t02_cmt_noc']=='S'){echo('Si');}else{if($row['t02_cmt_noc']=='N'){echo('No');}else{echo('');}}?>
                   </div>
												</td>
												
												
												
												<td align="left">&nbsp;</td>
											</tr>
											
											<?php /* ?>
											<tr>
												<td align="left">&nbsp;</td>
												<td align="left"><strong>Coordinador de Monitoreo Financiero</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
	 				<?php if($row['t02_cmf_noc']=='S'){echo('Si');}else{if($row['t02_cmf_noc']=='N'){echo('No');}else{echo('');}}?>
                   	</div>
												</td>
												<td align="left"><strong>Coordinador de Monitoreo Técnico</strong></td>
												<td align="center">
													<div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
                      <?php if($row['t02_cmt_noc']=='S'){echo('Si');}else{if($row['t02_cmt_noc']=='N'){echo('No');}else{echo('');}}?>
                   </div>
												</td>
												<td align="left">&nbsp;</td>
											</tr>
											<?php */ ?>
											
											<tr>
												<td width="12" align="left">&nbsp;</td>
												<td width="222" align="left"><strong>Respuesta a Ejecutor:
														Procede</strong></td>
												<td width="35" align="center"><div class="ClassText"
														style="border: 1px solid #A0A0A4; padding: 5px; width: 15px; height: 15px;">
	 				<?php if($row['t02_pro_noc']=='S'){echo('Si');}else{if($row['t02_pro_noc']=='N'){echo('No');}else{echo('');}}?>
                   	</div></td>
												<td width="201" align="left">&nbsp;</td>
												<td width="32" align="left">&nbsp;</td>
												<td width="270" align="left">&nbsp;</td>
											</tr>
										</table>
									</fieldset>
				   <?php } ?>
                </td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td colspan="3" align="left"></td>
				</tr>
			</table>
			</br>
			<div id="xlsCustom" style="display: none;">
				<div class="TableGrid">
					<table width="774" border="0" cellpadding="0" cellspacing="2"
						class="TableEditReg">
						<thead>
							<tr>

								<td align="center" valign="middle" rowspan="2"><strong>Proyecto</strong></td>
								<td align="center" valign="middle" rowspan="2"><strong>Institución</strong></td>
								<td align="center" valign="middle" rowspan="2"><strong>Solicitud
										de Compra</strong></td>
								<td align="center" valign="middle" colspan="4"><strong>Partida
										Afectada</strong></td>
								<td align="center" valign="middle" rowspan="2"><strong>Saldo de
										Partida Afectada</strong></td>
								<td align="center" valign="middle" rowspan="2"><strong>Importe
										Solicitado </strong></td>
								<td align="center" valign="middle" rowspan="2"><strong>Observación</strong></td>
	   <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?> <td align="center"
									valign="middle" colspan="7"><strong>Aprobación</strong></td> <?php } ?>
     </tr>
							<tr>
								<td align="center" valign="middle"><strong>Componente</strong></td>
								<td align="center" valign="middle"><strong>Actividad</strong></td>
								<td align="center" valign="middle"><strong>Sub Actividad</strong></td>
								<td align="center" valign="middle"><strong>Categoria de Gastos</strong></td>
	    <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
	   <td align="center" valign="middle"><strong>Cuadro Comparativos de
										Proveedores</strong></td>
								<td align="center" valign="middle"><strong>Cotizacion de
										Proveedores</strong></td>
								<td align="center" valign="middle"><strong>Aprobacion de Monitor
										Tecnico</strong></td>
								<td align="center" valign="middle"><strong>Aprobacion de Monitor
										Financiero</strong></td>
								<td align="center" valign="middle"><strong>Coordinador de
										Monitoreo Financiero</strong></td>
								<td align="center" valign="middle"><strong>Coordinador de
										Monitoreo Tecnico</strong></td>
								<td align="center" valign="middle"><strong>Respuesta a
										Ejecutor(Procede)</strong></td>
		<?php } ?>
    </tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo($rproy['t02_cod_proy'].' - '.$rproy['t02_nom_proy']); ?></td>
								<td><?php echo($rproy['t01_sig_inst']); ?></td>
								<td><?php echo($row['t02_sco_noc']); ?></td>
								<td><?php echo($row['componente']); ?></td>
								<td><?php echo($row['actividad']); ?></td>
								<td> <?php echo($row['subactividad']); ?> </td>
								<td><?php echo($row['categoria']); ?></td>
								<td><?php echo($row['saldo']); ?></td>
								<td><?php echo($row['importe']); ?></td>
								<td><?php echo($row['t02_obs_noc']); ?></td>
	 <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
     <td><?php if($row['t02_ccp_noc']=='S'){echo('Si');}else{if($row['t02_ccp_noc']=='N'){echo('No');}else{echo('');}}?></td>
								<td><?php if($row['t02_cop_noc']=='S'){echo('Si');}else{if($row['t02_cop_noc']=='N'){echo('No');}else{echo('');}}?></td>
								<td><?php if($row['t02_amt_noc']=='S'){echo('Si');}else{if($row['t02_amt_noc']=='N'){echo('No');}else{echo('');}}?></td>
								<td><?php if($row['t02_amf_noc']=='S'){echo('Si');}else{if($row['t02_amf_noc']=='N'){echo('No');}else{echo('');}}?></td>
								<td><?php if($row['t02_cmf_noc']=='S'){echo('Si');}else{if($row['t02_cmf_noc']=='N'){echo('No');}else{echo('');}}?></td>
								<td> <?php if($row['t02_cmt_noc']=='S'){echo('Si');}else{if($row['t02_cmt_noc']=='N'){echo('No');}else{echo('');}}?></td>
								<td><?php if($row['t02_pro_noc']=='S'){echo('Si');}else{if($row['t02_pro_noc']=='N'){echo('No');}else{echo('');}}?></td>
  <?php } ?>
  </tr>
					
					</table>

				</div>

			</div>
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>