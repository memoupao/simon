<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");
$objHC = new HardCode();
$OjbTab = new BLTablasAux();

$idProy = $objFunc->__Request('idProy');
$idCF = $objFunc->__Request('idCF');

$objProy = new BLProyecto();
$rproy = $objProy->ProyectoSeleccionar($idProy, 1);
$objProy = NULL;

$objProy = new BLProyecto();

$row = $objProy->CartaFianza_Seleccionar($idProy, $idCF);
$objProy = NULL;
// echo($t04_telf_equi);
// Se va a modificar el registro !!
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

			<table width="700" border="0" cellpadding="0" cellspacing="2"
				class="TableEditReg">
				<tr>
					<td width="1">&nbsp;</td>
					<td colspan="5"><input type="hidden" name="txturlsave"
						id="txturlsave" value="<?php echo($sURL); ?>" /> <input
						type="hidden" name="t02_cod_proy" id="t02_cod_proy"
						value="<?php echo($idProy); ?>" /> <input type="hidden"
						name="txtidcartafianza" id="txtidcartafianza"
						value="<?php echo($row['t02_id_cf']); ?>" /></td>
				</tr>
				<tr height="10">
					<td height="27">&nbsp;</td>
					<td align="left"><strong>Proyecto</strong>:</td>
					<td colspan="4" align="left" valign="top">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$row['t02_cod_proy']){echo(' height:15px;');}; ?>">
          <?php echo($rproy['t02_cod_proy'].' - '.$rproy['t02_nom_proy']); ?>
          </div>
					</td>
				</tr>
				<tr height="10">
					<td height="27">&nbsp;</td>
					<td align="left"><strong>Institución:</strong></td>
					<td colspan="4" align="left">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$row['t01_sig_inst']){echo(' height:15px;');}; ?>">
          <?php echo($rproy['t01_sig_inst']); ?>
        </div>
					</td>
				</tr>
				<tr height="10">
					<td height="27">&nbsp;</td>
					<td align="left"><strong>Periodo:</strong></td>
					<td colspan="3" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 200px;">
	  <?php echo($rproy['ini'].' al '.$rproy['fin']); ?>
      </div>
					</td>
					<td align="left">&nbsp;</td>
				</tr>

				<tr height="10">
					<td height="27">&nbsp;</td>
					<td align="left"><strong>Entidad Financiera:</strong></td>
					<td colspan="3" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; margin-right: 5px; height: 15px;">
	  <?php echo($row['banco']); ?> 
      </div>

					</td>
					<td align="left">&nbsp;</td>
				</tr>
				<tr height="10">
					<td height="24">&nbsp;</td>
					<td width="123" align="left"><strong>Número:</strong></td>
					<td width="233" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($row['t02_num_cf']); ?>
   	  </div>
					</td>
					<td width="16">&nbsp;</td>
					<td width="134" align="right"><strong>Serie:</strong></td>
					<td width="177" align="left" bgcolor="#553FFF">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($row['t02_ser_cf']); ?>
   	  </div>
					</td>
				</tr>
				<tr height="10">
					<td height="23">&nbsp;</td>
					<td align="left"><strong>Fecha de Emisión:</strong></td>
					<td align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($row['t02_fgir_cf']); ?>
   	  </div>
					</td>
					<td align="right">&nbsp;</td>
					<td align="right"><strong>Fecha Recepción:</strong></td>
					<td align="left" bgcolor="#553FFF">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($row['t02_frec_cf']); ?>
   	  </div>
					</td>
				</tr>

				<tr height="10">
					<td height="27">&nbsp;</td>
					<td align="left" nowrap="nowrap" bgcolor="#553FFF"><strong>Fecha
							Vencimiento:</strong></td>
					<td align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo($row['t02_fven_cf']); ?>
   	  </div>
					</td>
					<td align="right">&nbsp;</td>
					<td align="right"><strong>Monto - Carta Fianza:</strong></td>
					<td align="left" bgcolor="#553FFF">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	  <?php echo(round($row['t02_mto_cf'],2)); ?>
   	  </div>
					</td>
				</tr>
				<tr height="10">
					<td height="26">&nbsp;</td>
					<td align="left"><strong>Estado:</strong></td>
					<td colspan="2" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 200px; height: 15px;">
	  <?php echo($row['estado']); ?>
      </div>
					</td>
					<td align="right">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				<tr height="10">
					<td height="25">&nbsp;</td>
					<td align="left"><strong>Descripción:</strong></td>
					<td colspan="4" align="left">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$row['t02_des_cf']){echo(' height:15px;');}; ?>">
   <?php echo($row['t02_des_cf']); ?>
   </div>
					</td>
				</tr>
				<tr height="10">
					<td height="35">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td colspan="4" align="left">      
        <?php
        if ($row['t02_file_cf'] != "") {
            $filename = constant("DOCS_PATH") . $objHC->FolderUploadCartaFianza . $row['t02_file_cf'];
            
            echo ("<a href=\"#\" onclick=\"window.open('" . $filename . "','dwCF');\">Descargar Carta Fianza</a><br>");
        }
        ?>
    </td>
				</tr>
				<tr height="10">
					<td height="35">&nbsp;</td>
					<td align="left"><strong>VB-ADM:</strong></td>
					<td colspan="4" align="left">
						<div class="ClassText"
							style="border: 1px solid #A0A0A4; padding: 5px; width: 100px; height: 15px;">
	<?php if($row['t02_vb_adm']=='1'){echo('Si');}; ?>
   	</div>


					</td>
				</tr>
				<tr height="10">
					<td height="35">&nbsp;</td>
					<td align="left"><strong>Observaciones ADM:</strong></td>
					<td colspan="4" align="left">
						<div class="ClassText" style="border:1px solid #A0A0A4;  margin-right:5px; padding:5px; <?php if(!$row['t02_obs_adm']){echo(' height:15px;');}; ?>">
    <?php echo($row['t02_obs_adm']); ?>
    </div>
					</td>
				</tr>
				
				<tr height="10">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br />

			<div id="xlsCustom" style="display: none;">
				<div id="container" class="TableGrid">

					<table width="700" border="1" cellpadding="1" cellspacing="1"
						class="TableEditReg">
						<thead>
							<td align="center"><strong>Proyecto</strong></td>
							<td align="center"><strong>Institución</strong></td>
							<td align="center"><strong>Periodo</strong></td>
							<td align="center"><strong>Entidad Financiera</strong></td>
							<td align="center"><strong>Número</strong></td>
							<td align="center"><strong>Serie</strong></td>
							<td align="center"><strong>Fecha de Emisión</strong></td>
							<td align="center"><strong>Fecha Recepción</strong></td>
							<td align="center"><strong>Fecha Vencimiento</strong></td>
							<td align="center"><strong>Monto - Carta Fianza</strong></td>
							<td align="center"><strong>Estado</strong></td>
							<td align="center"><strong>Descripción</strong></td>
							<td align="center"><strong>Carta Fianza</strong></td>
							<td align="center"><strong>VB-ADM</strong></td>
							<td align="center"><strong>Observaciones ADM</strong></td>
						</thead>
						<tbody>
							<td align="center"> 
          <?php echo($rproy['t02_cod_proy'].' - '.$rproy['t02_nom_proy']); ?>
          </td>
							<td align="center"><?php echo($rproy['t01_sig_inst']); ?>
       </td>
							<td align="center"> <?php echo($rproy['ini'].' al '.$rproy['fin']); ?>
      </td>
							<td align="center"><?php echo($row['banco']); ?> 
      </td>
							<td align="center"><?php echo($row['t02_num_cf']); ?>
   	  </td>
							<td align="center"> <?php echo($row['t02_ser_cf']); ?>
   	 </td>
							<td align="center"> <?php echo($row['t02_fgir_cf']); ?>
   	  </td>
							<td align="center"> <?php echo($row['t02_frec_cf']); ?>
   	  </td>
							<td align="center">  <?php echo($row['t02_fven_cf']); ?>
   	 </td>
							<td align="center"> <?php echo(round($row['t02_mto_cf'],2)); ?>
   	  </td>
							<td align="center"><?php echo($row['estado']); ?>
      </td>
							<td align="center"> <?php echo($row['t02_des_cf']); ?>
	   </td>
							<td align="center">
		<?php
if ($row['t02_file_cf'] != "") {
    $filename = constant("DOCS_PATH") . $objHC->FolderUploadCartaFianza . $row['t02_file_cf'];
    
    echo ("<a href=\"#\" onclick=\"window.open('" . $filename . "','dwCF');\">Descargar Carta Fianza</a><br>");
}
?>
		</td>
							<td><?php if($row['t02_vb_adm']=='1'){echo('Si');}; ?>
   	</td>
							<td align="center"><?php echo($row['t02_obs_adm']); ?>
    </td>
						</tbody>
					</table>

				</div>

			</div>
			<br />
			
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php  } ?>