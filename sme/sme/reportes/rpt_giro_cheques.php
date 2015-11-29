<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$Consurso = $objFunc->__Request('cboConcurso');
$idInstitucion = $objFunc->__Request('cboInstitucion') ? $objFunc->__Request('cboInstitucion') : $objFunc->__Request('cboEjecutor');
?>


<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Autorización de Giro de Cheques</title>
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
			<div id="divTableLista" class="TableGrid">
				<!--  -->
				<table border="0" cellpadding="0" cellspacing="0">
					<tbody class="data">
						<tr>
							<td width="134" rowspan="2" align="center" valign="middle"
								nowrap="nowrap" bgcolor="#E9E9E9"><strong>Entidad Supervisora</strong></td>
							<td width="88" rowspan="2" align="center" valign="middle"
								nowrap="nowrap" bgcolor="#E9E9E9"><strong>RUC</strong></td>
							<td colspan="3" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Proyecto
									a Supervisar</strong></td>
							<td width="104" rowspan="2" align="center" valign="middle"
								bgcolor="#E9E9E9"><strong>Importe del Contrato <br />S/.
							</strong></td>
							<td width="69" rowspan="2" align="center" valign="middle"
								bgcolor="#E9E9E9"><strong>N&deg; de Visitas Program.</strong></td>
							<td width="54" rowspan="2" align="center" valign="middle"
								bgcolor="#E9E9E9"><strong>N&deg; de Visita a Ejecutar</strong></td>
							<td colspan="4" align="center" valign="middle" bgcolor="#E9E9E9"><strong>
									Primer Pago</strong></td>
							<td colspan="4" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Segundo
									Pago</strong></td>
						</tr>
						<tr>
							<td width="76" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Proyecto</strong></td>
							<td width="167" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Supervisor
									Externo</strong></td>
							<td width="150" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Gestor de Proyectos</strong></td>
							<td width="90" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
									<br />S/.
							</strong></td>
							<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Autorizado
									Si/ NO</strong></td>
							<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
							<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Fecha
									Desemb.</strong></td>
							<td width="90" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
									<br /> S/.
							</strong></td>
							<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Autorizado
									Si/ NO</strong></td>
							<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
							<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Fecha
									Desemb.</strong></td>
						</tr>
					</tbody>
					<tbody class="data">
      <?php
    $objFE = new BLFE();
    $iRs = $objFE->ListadoAutorizaChequeME($Consurso, $idInstitucion, '*');
    /*
     * echo("<pre>"); print_r($objFE); echo("</pre>");
     */
    
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            ?>
      <tr class="RowData"
							ondblclick="EditarEjecucion('<?php echo($row["t02_cod_proy"]);?>','<?php echo($row["trim_desembolsar"]);?>','<?php echo($row["idaprobacion"]);?>'); return false;"
							<?php if($row['nro_trans']>0){echo('style="color:#093;"');}?>>
							<td align="left" valign="middle"><?php echo($row['inst_moni_sig']);?></td>
							<td height="30" align="center" valign="middle"><?php echo( $row['inst_moni_ruc']);?></td>
							<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['codigo']);?></td>
							<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_exte']);?></td>
							<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_tema']);?></td>
							<td align="right" valign="middle"><?php echo(number_format($row['costo_tot_visita'],2));?></td>
							<td align="center" valign="middle"><?php echo($row['nro_visitas']);?></td>
							<td align="center" valign="middle"><?php echo($row['nro_visita_ejec']);?></td>
							<td align="right" valign="middle" bgcolor="#CAC5FA"><?php echo( number_format($row['importe_visita01'],2));?></td>
							<td align="center" valign="middle" bgcolor="#CAC5FA"><?php if($row['visita01_vb']=='1') {echo("Si");} ?></td>
        <?php
            // Si fue Aprobado el primer Pago, mostramos el enlace hacia los desembolsos.
            if ($row['visita01_vb'] == '1') {
                $enlace = '<a href="javascript:;" onclick="viewDesembolso(\'' . $row['codigo'] . '\', \'' . $row['nro_visita_ejec'] . '\', \'1\');" title="Ver Desembolsos del Primer Pago de la Visita.">' . number_format($row['desembolso_01'], 2) . '</a>';
            } else {
                $enlace = number_format($row['desembolso_01'], 2);
            }
            
            ?>
        
        <!-- Para el Segundo Pago previa entrega de informe -->
							<td align="right" valign="middle" bgcolor="#CAC5FA"><?php echo($enlace);?></td>
							<td align="center" valign="middle" bgcolor="#CAC5FA"><?php echo($row['fecha_desemb01']); ?></td>

							<td align="right" valign="middle" bgcolor="#FFFF9D"><?php echo( number_format($row['importe_visita02'],2));?></td>
							<td align="center" valign="middle" nowrap="nowrap"
								bgcolor="#FFFF9D"><?php if($row['visita02_vb']=='1') {echo("Si");} ?></td>
        
        
        
        <?php
            // Si fue Aprobado el primer Pago, mostramos el enlace hacia los desembolsos.
            if ($row['visita02_vb'] == '1') {
                $enlace = '<a href="javascript:;" onclick="viewDesembolso(\'' . $row['codigo'] . '\', \'' . $row['nro_visita_ejec'] . '\', \'2\');" title="Ver Desembolsos del Primer Pago de la Visita.">' . number_format($row['desembolso_02'], 2) . '</a>';
            } else {
                $enlace = number_format($row['desembolso_02'], 2);
            }
            
            ?>
        
        <td align="right" valign="middle" nowrap="nowrap"
								bgcolor="#FFFF9D"><?php echo($enlace);?></td>
							<td align="center" valign="middle" nowrap="nowrap"
								bgcolor="#FFFF9D"><?php echo($row['fecha_desemb02']); ?></td>
						</tr>
      
      <?php
            $RowIndex ++;
        }
        $iRs->free();
    } else {
        ?>
      <tr>
							<td colspan="16" align="left" valign="middle"><strong
								style="color: #F00">No hay proyectos con la busqueda requerida.
									Solo se muetran los proyectos que fueron aprobados desembolsos
									y que aun no se se han completado el total desembolsado</strong>
							</td>
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
						</tr>
					</tfoot>
				</table>

				<p>
					<input type="hidden" name="t02_cod_proy"
						value="<?php echo($idProy);?>" />
<script language="javascript" type="text/javascript">

function viewDesembolso(idproy, idVisita, idApro)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>	
	var url = "auto_giro_chk_edit.php?action=<?php echo(md5("ajax_view"));?>&idProy="+idproy+"&idVisita="+idVisita+"&idAprob="+idApro ;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Desembolsos del Supervisor Externo");?>",url);
	return;
  }
  
</script>
				</p>
			</div>


			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>