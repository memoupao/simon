<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLEquipo.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
$OjbTab = new BLTablasAux();
$idProy = $objFunc->__Request('idProy');
$obj['t04_num_soli'] = $objFunc->__Request('idCP');

$objProy = new BLProyecto();

$HC = new HardCode();

$objEqui = new BLEquipo();

if ($obj['t04_num_soli']) {
	$row = $objEqui->CambioPersonal_Seleccionar($idProy, $obj['t04_num_soli']);
	$CambPers[] = $row;
} else {
	$row = $objEqui->CambioPersonal_SeleccionarAll();
	
	$cont = 0;
	$CambPers;
	while ($r = mysqli_fetch_assoc($row)) {
	
		$CambPers[$cont] = $r;
		$cont ++;
	}
	
}


// $idEquipoAntes = $t04_id_equi_cambioi;

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

    
<?php

for ($x = 0; $x < count($CambPers); $x ++) {
    $obj = $CambPers[$x];
    
    $objPer = $objEqui->CambioPersonal_GetPartida($obj['t02_cod_proy'], $obj['t04_carg_equi']);
    $rproy = $objProy->ProyectoSeleccionar($obj['t02_cod_proy'], 1);
    
    ?>
<table width="774" border="0" cellpadding="0" cellspacing="2"
				class="TableEditReg">
				<tr>
					<td colspan="5"></td>
				</tr>

				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td colspan="3" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td width="8" align="left" valign="middle">&nbsp;</td>
					<td width="141" align="left" valign="middle"><strong>Número de
							Solicitud:</strong></td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 10px; height: 15px;">
	  <?php echo($obj['t04_num_soli']); ?>
   	  </div>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle"><strong>Proyecto</strong>:</td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$obj['t02_cod_proy']){echo(' height:15px;');}; ?>">
          <?php echo($rproy['t02_cod_proy'].' - '.htmlentities($rproy['t02_nom_proy'])); ?>
       </div>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle"><strong>Institución</strong>:</td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$obj['t01_sig_inst']){echo(' height:15px;');}; ?>">
          <?php echo($rproy['t01_sig_inst']); ?>
        </div>
					</td>
				</tr>


				<tr>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap="nowrap"><strong>Fecha de Pedido:</strong></td>
					<td align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($obj['t04_fec_ini']);?>
   	  </div>

					</td>
					<td align="left"><strong>Fecha de Aprobación:</strong></td>
					<td align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($obj['t04_fec_ter']);?>
   	  </div>
					</td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap="nowrap"><strong>Partida a Afectar:</strong></td>
					<td width="422" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
	  <?php echo($obj['cargo']); ?>
   	  </div>
					</td>
					<td width="95" align="right"><strong>Saldo de la Partida a Afectar:</strong></td>
					<td width="110" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; height: 15px; margin-right: 5px;">
	  <?php echo($objPer['saldo_partida']); ?></div>

					</td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td height="28" align="left" nowrap="nowrap"><strong>Nombre
							Personal:</strong></td>
					<td align="left" valign="top">

						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
	  <?php echo($objPer['nom_equi']); ?>
   	  </div>
					</td>
					<td align="right"><strong>Remuneración</strong> :</td>
					<td align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; height: 15px; margin-right: 5px;">
	  <?php echo($objPer['remuneracion']); ?>
   	  </div>


					</td>
				</tr>
				<tr>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td height="28" align="left" nowrap="nowrap"><strong>Solicitud
							Adjunta:</strong></td>
					<td colspan="3" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; margin-right: 5px; height: 15px;"><?php echo($obj['t04_adj_sol']); ?> </div>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">&nbsp;</td>
					<td height="28" align="left" valign="top"><strong>Comentarios del
							Ejecutor:</strong></td>
					<td colspan="3" align="left" valign="middle">
						<div class="ClassText" style="border:1px solid #A0A0A4; padding:5px; margin-right:5px; <?php if(!$obj['t04_obs_ejec']){echo(' height:15px;');}; ?>">
	  <?php echo($obj['t04_obs_ejec']); ?> 
      </div>
					</td>
				</tr>

				<tr>
					<td height="28" colspan="5">
						<fieldset style="padding: 1px;">
							<legend>Datos del Nuevo Personal</legend>
							<table width="100%" class="TableEditReg" style="padding: 1px;">
								<tr>
									<td align="left">&nbsp;</td>
									<td height="28" align="left"><strong>DNI</strong><strong>:</strong></td>
									<td align="left">

										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['t04_dni_equi']); ?>
   	  		  </div>
									</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td width="1%" align="left">&nbsp;</td>
									<td width="21%" align="left"><strong>Apelllido Paterno:</strong></td>
									<td width="14%" align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo(htmlentities($obj['t04_ape_pat'])); ?>
   	  		  </div>

									</td>
									<td width="13%" align="right" nowrap="nowrap"><strong>Apellido
											Materno:</strong></td>
									<td width="9%" align="left">

										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo(htmlentities($obj['t04_ape_mat'])); ?>
   	  		  </div>
									</td>
									<td width="9%" align="right"><strong>Nombres:</strong></td>
									<td width="33%" align="left">

										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo(htmlentities($obj['t04_nom_equi'])); ?>
   	  		  </div>
									</td>
								</tr>

								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Sexo:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['sexo']); ?>
   	  		  </div>
									</td>
									<td align="right"><strong>Edad:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['t04_edad_equi']); ?>
   	  		  </div>

									</td>
									<td align="right"><strong>Instrucción:</strong></td>
									<td align="left">

										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['instruccion']); ?>
   	  		  </div>
									</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Telefono:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['t04_telf_equi']); ?>
   	  		  </div>

									</td>
									<td align="right"><strong>Celular:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['t04_cel_equi']); ?>
   	  		  </div>

									</td>
									<td align="right"><strong>Mail:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;">
			  <?php echo($obj['t04_mail_equi']); ?>
   	  		  </div>
									</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Experiencia:</strong></td>
									<td colspan="5" align="left">
										<div class="ClassText" style="border:1px solid #A0A0A4; padding:5px; <?php if(!$obj['t04_exp_lab']){echo(' height:15px;');}; ?>">
			  <?php echo($obj['t04_exp_lab']); ?>
              </div>
									</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Función:</strong></td>
									<td colspan="5" align="left" valign="middle">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;"><?php echo($obj['t04_func_equi']); ?></div>
									</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Curriculum V. Adjunto:</strong></td>
									<td colspan="5" align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; height: 15px;"><?php echo($obj['t04_adj_cv']); ?> </div>

									</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>

				<tr>
					<td height="28" colspan="5">
	  
	  <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
        <fieldset style="padding: 1px;">
							<legend>Aprobaciones Acerca de la Solicitud de cambio de Personal</legend>
							<table width="780" border="0" align="center" cellpadding="0"
								class="TableEditReg" style="padding: 1px;">
								<tr>
									<td width="4" align="left">&nbsp;</td>
									<td width="200" align="left"><strong>Gestor de Proyectos:</strong></td>
									<td width="20" align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; width: 10px; height: 15px;">
	 			<?php if($obj['t04_aprob_mt']=='1'){echo('Si');} ?>
   	  			</div>


									</td>
									<td width="544" align="left">&nbsp;</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Responsable de Area:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; width: 10px; height: 15px;">
	 			<?php if($obj['t04_aprob_cmt']=='1'){echo('Si');} ?>
   	  			</div>
									</td>
									<td align="left">&nbsp;</td>
								</tr>
								<?php  /* ?>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Monitor Financiero:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; width: 10px; height: 15px;">
	 			<?php if($obj['t04_aprob_mf']=='1'){echo('Si');} ?>
   	  			</div>
									</td>
									<td align="left">&nbsp;</td>
								</tr>
								<tr>
									<td align="left">&nbsp;</td>
									<td align="left"><strong>Coordinador Monitoreo Financiero:</strong></td>
									<td align="left">
										<div class="ClassText"
											style="border: 1px solid #A0A0A4; padding: 5px; width: 10px; height: 15px;">
	 			<?php if($obj['t04_aprob_cmf']=='1'){echo('Si');} ?>
   	  			</div>
									</td>
									<td align="left">&nbsp;</td>
								</tr>
								<?php  */ ?>
								<tr>
									<td colspan="4" align="left" valign="middle">
										<fieldset style="padding: 1px;">
											<legend> Respuesta al Ejecutor</legend>

											<div class="ClassText"
												style="border: 1px solid #A0A0A4; padding: 5px; width: 60px; height: 15px;">
	 			<?php if($obj['t04_resp_ejec']=='1'){echo('Si Aprobar');} ?>
                </div>
											<br />
											<div class="ClassText" style="border:1px solid #A0A0A4; padding:5px; <?php if(!$obj['t04_resp_ejec_obs']){echo(' height:15px;');}; ?>"><?php echo($obj['t04_resp_ejec_obs']); ?></div>
										</fieldset>
									</td>
								</tr>
							</table>
						</fieldset>
		  <?php } ?>
      </td>
				</tr>




				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="3">&nbsp;</td>
				</tr>
			</table>
			<br /> <br /> <br />
	
	
	<?php
}
?>
	
<br />
			<div id="xlsCustom" style="display: none;">
				<div class="TableGrid">


					<table width="774" border="0" cellpadding="0" cellspacing="2"
						class="TableEditReg">
						<thead>
							<tr>
								<td align="left" valign="middle" rowspan="2"><strong>Número de
										Solicitud</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Proyecto</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Institución</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Fecha
										Pedido</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Fecha
										Aprobación</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Partida a
										Afectar</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Saldo de la
										Partida a Afectar</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Nombre
										Personal</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Remuneración</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Solicitud
										Adjuntada</strong></td>
								<td align="left" valign="middle" rowspan="2"><strong>Comentarios
										del Ejecutor</strong></td>
								<td align="left" valign="middle" colspan="13"><strong>Datos de
										Nuevo Personal</strong></td>
	  <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
      <td align="left" valign="middle" colspan="5"><strong>Aprobaciones
										Acerca de la Solicitud de cambio de Personal</strong></td>
	  <?php } ?>
	  </tr>
							<tr>
								<td align="left" valign="middle"><strong>DNI</strong></td>
								<td align="left" valign="middle"><strong>Apelllido Paterno</strong></td>
								<td align="left" valign="middle"><strong>Apellido Materno</strong></td>
								<td align="left" valign="middle"><strong>Nombres</strong></td>
								<td align="left" valign="middle"><strong>Sexo</strong></td>
								<td align="left" valign="middle"><strong>Edad</strong></td>
								<td align="left" valign="middle"><strong>Instrucción</strong></td>
								<td align="left" valign="middle"><strong>Telefono</strong></td>
								<td align="left" valign="middle"><strong>Celular</strong></td>
								<td align="left" valign="middle"><strong>Mail</strong></td>
								<td align="left" valign="middle"><strong>Experiencia</strong></td>
								<td align="left" valign="middle"><strong>Función</strong></td>
								<td align="left" valign="middle"><strong>Curriculum V. Adjunto</strong></td>
	  <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
	  <td align="left" valign="middle"><strong>Gestor de Proyectos</strong></td>
								<td align="left" valign="middle"><strong>Responsable de Area</strong></td>
								<?php /* ?>
								<td align="left" valign="middle"><strong>Coordinador Monitoreo
										Financiero</strong></td>
								<?php */ ?>
								<td align="left" valign="middle"><strong>Respuesta al Ejecutor</strong></td>
								
								
<?php } ?>
	  </tr>
						</thead>
						<tbody>
	  <?php
for ($i = 0; $i < count($CambPers); $i ++) {
    $obj = $CambPers[$i];
    $objPer = $objEqui->CambioPersonal_GetPartida($obj['t02_cod_proy'], $obj['t04_carg_equi']);
    $rproy = $objProy->ProyectoSeleccionar($obj['t02_cod_proy'], 1);
    ?>
    <tr>

								<td> <?php echo($obj['t04_num_soli']); ?></td>
								<td><?php echo($rproy['t02_cod_proy'].' - '.htmlentities($rproy['t02_nom_proy'])); ?></td>
								<td><?php echo($rproy['t01_sig_inst']); ?></td>
								<td><?php echo($obj['t04_fec_ini']);?></td>
								<td><?php echo($obj['t04_fec_ter']);?></td>
								<td><?php echo($obj['cargo']); ?></td>
								<td><?php echo($objPer['saldo_partida']); ?></td>
								<td><?php echo($objPer['nom_equi']); ?></td>
								<td><?php echo($objPer['remuneracion']); ?></td>
								<td><?php echo($obj['t04_adj_sol']); ?></td>
								<td><?php echo($obj['t04_obs_ejec']); ?></td>
								<td><?php echo($obj['t04_dni_equi']); ?></td>
								<td><?php echo(htmlentities($obj['t04_ape_pat'])); ?></td>
								<td><?php echo(htmlentities($obj['t04_ape_mat'])); ?></td>
								<td><?php echo(htmlentities($obj['t04_nom_equi'])); ?></td>
								<td><?php echo($obj['sexo']); ?></td>
								<td><?php echo($obj['t04_edad_equi']); ?></td>
								<td><?php echo($obj['instruccion']); ?></td>
								<td><?php echo($obj['t04_telf_equi']); ?></td>
								<td><?php echo($obj['t04_cel_equi']); ?></td>
								<td><?php echo($obj['t04_mail_equi']); ?></td>
								<td><?php echo($obj['t04_exp_lab']); ?></td>
								<td><?php echo($obj['t04_func_equi']); ?></td>
								<td><?php echo($obj['t04_adj_cv']); ?></td>
		 
		 <?php if($ObjSession->PerfilID!=$HC->Ejec){ ?>
         <td><?php if($obj['t04_aprob_mt']=='1'){echo('Si');} ?></td>
								<td><?php if($obj['t04_aprob_cmt']=='1'){echo('Si');} ?></td>
								<?php /* ?>
								<td><?php if($obj['t04_aprob_mf']=='1'){echo('Si');} ?></td>								
								<td><?php if($obj['t04_aprob_cmf']=='1'){echo('Si');} ?></td>
								<?php */ ?>
								<td><?php if($obj['t04_resp_ejec']=='1'){echo('Si Aprobar');} ?></td>
		  
		  <?php } ?>
		  

      </tr>		
	  <?php
}
?>
    
					
					</table>


				</div>

			</div>

			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>