<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

$Consurso = $objFunc->__Request('cboconcurso');
$idInstitucion = $objFunc->__Request('cboinstitucion');

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Control de Pago a Supervisores Externos</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form action="#" method="post" enctype="multipart/form-data"
		name="frmMain" id="frmMain">
<?php
}
?>
<div id="divTableLista" class="TableGrid grid-width grid-list">
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr>
						<td width="48" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9" style="display: none;">&nbsp;</td>
						<td width="123" rowspan="2" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#E9E9E9"><strong>Entidad Supervisora</strong></td>
						<td width="61" rowspan="2" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#E9E9E9"><strong>RUC</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Proyecto
								a Supervisar</strong></td>
						<td width="86" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Importe del Contrato <br />S/.
						</strong></td>
						<td width="72" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>N&deg; de Visitas Program.</strong></td>
						<td width="63" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>N&deg; de Visita a Ejecutar</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#E9E9E9"
							style="border-right: solid 3px;" s><strong> Primer Pago</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Segundo
								Pago</strong></td>
					</tr>
					<tr>
						<td width="74" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Proyecto</strong></td>
						<td width="106" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Supervisor Externo</strong></td>
						<td width="99" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Gestor de Proyectos</strong></td>
						<td width="92" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
								de Visita<br />S/.
						</strong></td>
						<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Autorizado
								Si/ NO</strong></td>
						<td width="77" align="center" valign="middle" bgcolor="#E9E9E9"
							style="border-right: solid 3px;"><strong>Importe <br />
								Autorizado S/.
						</strong></td>
						<td width="105" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
								del Visita<br /> S/.
						</strong></td>
						<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Autorizado
								Si/ NO</strong></td>
						<td width="76" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
								<br /> Autorizado S/.
						</strong></td>
					</tr>
				</tbody>
				<tbody class="data">
      <?php
    $objFE = new BLFE();
    $iRs = $objFE->ListadoControlPagoME($Consurso, $idInstitucion, "*");
    /*
     * echo("<pre>"); print_r($objFE); echo("</pre>");
     */
    
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            ?>
      <tr class="RowData"
						<?php if($row['visita01_vb']=='1'){echo('style="color:#093;"');}?>>
						<?php /* ?>
						<td align="center" valign="middle" nowrap="nowrap"
							style="display: none;"><a href="javascript:"> <img
								src="../../../img/bullet.gif" alt="" width="14" height="14"
								border="0" title="Ver mas Datos"
								onclick="EditarEjecucion('<?php echo($row["t02_cod_proy"]);?>','<?php echo($row["trim_desembolsar"]);?>','<?php echo($row["idaprobacion"]);?>'); return false;" /></a>
						</td>
						<?php */ ?>
						<td align="left" valign="middle"><?php echo($row['inst_moni_sig']);?></td>
						<td height="30" align="center" valign="middle"><?php echo( $row['inst_moni_ruc']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['codigo']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_exte']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_tema']);?></td>
						<td align="right" valign="middle"><?php echo(number_format($row['costo_tot_visita'],2));?></td>
						<td align="center" valign="middle"><?php echo($row['nro_visitas']);?></td>
						<td align="center" valign="middle"><?php echo($row['nro_visita_ejec']);?></td>
						<td align="right" valign="middle"><?php echo( number_format($row['importe_visita01'],2));?></td>
						<td align="center" valign="middle"><?php if($row['visita01_vb']=='1') {echo("Si");} ?></td>
						<td align="right" valign="middle" style="border-right: solid 3px;">
         <?php
            // Si fue Aprobado el primer Pago, mostramos el enlace hacia los desembolsos.
            if ($row['visita01_vb'] == '1') {
                $enlace = '<a href="javascript:;" onclick="viewAprobacion(\'' . $row['codigo'] . '\', \'' . $row['nro_visita_ejec'] . '\', \'1\');" title="Ver Aporbacion del Primer Pago de la Visita.">' . number_format($row['aprob_visita01'], 2) . '</a>';
            } else {
                $enlace = "";
            } // number_format($row['aprob_visita01'],2) ;}
            
            ?>
        <strong><?php echo($enlace);?></strong>
						</td>
						<td align="right" valign="middle"><?php echo( number_format($row['importe_visita02'],2));?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php if($row['visita02_vb']=='1') {echo("Si");} ?></td>
						<td align="right" valign="middle" nowrap="nowrap">
          <?php
            // Si fue Aprobado el primer Pago, mostramos el enlace hacia los desembolsos.
            if ($row['visita02_vb'] == '1') {
                $enlace = '<a href="javascript:;" onclick="viewAprobacion(\'' . $row['codigo'] . '\', \'' . $row['nro_visita_ejec'] . '\', \'2\');" title="Ver Aporbacion del Segundo Pago de la Visita.">' . number_format($row['aprob_visita02'], 2) . '</a>';
            } else {
                $enlace = "";
            } // number_format($row['aprob_visita01'],2) ;}
            
            ?>
        <strong><?php echo($enlace);?></strong>
						</td>
					</tr>
      
      <?php
            $RowIndex ++;
        }
        $iRs->free();
    } else {
        ?>
      <tr>
						<td colspan="14" align="left" valign="middle"><strong
							style="color: #F00">No hay proyectos con la busqueda requerida.
								Solo se muetran los proyectos que fueron aprobados desembolsos y
								que aun no se se han completado el total desembolsado</strong></td>
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
						
					</tr>
				</tfoot>
			</table>

			<p>
				<input type="hidden" name="t02_cod_proy"
					value="<?php echo($idProy);?>" />
				<script language="javascript" type="text/javascript">

function viewAprobacion(idproy, idVisita, idAprob)
{
<?php $ObjSession->AuthorizedPage("VER"); ?>	
var url = "ctrl_pag_me_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idproy+"&idVisita="+idVisita+"&idAprob="+ idAprob;
// ChangeStylePopup("PopupDialog");
loadPopup("<?php echo("Autorización del Pago de la Visita ");?> " + idVisita,url);

return;
}

</script>
			</p>
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>