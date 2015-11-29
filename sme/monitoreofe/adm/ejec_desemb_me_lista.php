<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLFE.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

$Consurso = $objFunc->__Request('cboconcurso');
$idInstitucion = $objFunc->__Request('cboinstitucion');

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$id = $objFunc->__POST('t46_id');

if ($idProy == "" && $id == "") {
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('t46_id');
}

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Coronograma Visitas</title>
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
<div id="divTableLista" class="TableGrid">
			<!--style=" width:1490px; max-height:900px; min-height:550px; overflow:auto;"-->
			<img src="/FE/img/clip_image002.gif" alt="" />
			<table border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr>
						<td width="39" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9">&nbsp;</td>
						<td width="110" rowspan="2" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#E9E9E9"><strong>Entidad Monitora</strong></td>
						<td width="73" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Proyecto</strong></td>
						<td width="74" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Monitor Externo</strong></td>
						<td width="74" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Monitor Técnico</strong></td>
						<td width="80" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>Importe del Contrato <br />S/.
						</strong></td>
						<td width="61" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>N&deg; de Visitas Program.</strong></td>
						<td width="62" rowspan="2" align="center" valign="middle"
							bgcolor="#E9E9E9"><strong>N&deg; de Visita a Ejecutar</strong></td>
						<td align="center" valign="middle" bgcolor="#E9E9E9"><strong>
								Primer Pago</strong></td>
						<td align="center" valign="middle" bgcolor="#E9E9E9">&nbsp;</td>
						<td align="center" valign="middle" bgcolor="#E9E9E9"><strong>Segundo
								Pago</strong></td>
						<td align="center" valign="middle" bgcolor="#E9E9E9">&nbsp;</td>
						<td align="center" valign="middle" bgcolor="#FFFF9D">&nbsp;</td>
						<td align="center" valign="middle" bgcolor="#FFFF9D">&nbsp;</td>
						<td align="center" valign="middle" bgcolor="#FFFF9D">&nbsp;</td>
					</tr>
					<tr>
						<td width="64" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Importe
								<br />S/.
						</strong></td>
						<td width="73" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Autorizado
								Si/ NO</strong></td>
						<td width="129" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Desembolsos</strong></td>
						<td width="99" align="center" valign="middle" bgcolor="#E9E9E9"><strong>Saldo
								x Desembolsar</strong></td>
						<td width="81" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Nro
								Transac.</strong></td>
						<td width="96" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Fecha
								del Ultimo desembolso</strong></td>
						<td width="113" align="center" valign="middle" bgcolor="#FFFF9D"><strong>Observaciones</strong></td>
					</tr>
				</tbody>
				<tbody class="data">
      <?php
    $objFE = new BLFE();
    $iRs = $objFE->ListadoProyectos_EjecDesembolsos($Consurso, $idInstitucion);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $lsTrime = $row['trim_desembolsar'];
            if ($lsTrime == 1) {
                ?>
        <tr class="RowData"
						ondblclick="EditarEjecucion('<?php echo($row["t02_cod_proy"]);?>','<?php echo($row["trim_desembolsar"]);?>','<?php echo($row["idaprobacion"]);?>'); return false;"
						<?php if($row['nro_trans']>0){echo('style="color:#093;"');}?>>
						<td align="center" valign="middle" nowrap="nowrap"><a
							href="javascript:"> <img src="../../../img/bullet.gif" alt=""
								width="14" height="14" border="0" title="Ver mas Datos"
								onclick="EditarEjecucion('<?php echo($row["t02_cod_proy"]);?>','<?php echo($row["trim_desembolsar"]);?>','<?php echo($row["idaprobacion"]);?>'); return false;" /></a>
						</td>
						<td height="30" align="center" valign="middle"><?php echo($row['t01_sig_inst']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t02_cod_proy']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_tema']);?></td>
						<td align="left" valign="middle" style="min-width: 100px;"><?php echo( $row['moni_fina']);?></td>
						<td align="left" valign="middle"><?php echo($row['bene_girar']);?></td>
						<td align="left" valign="middle"><?php echo($row['banco']);?></td>
						<td align="center" valign="middle"><?php echo($row['nrocuenta']);?></td>
        <?php
                
                $rAprob = $objFE->Aprobacion_Primer_Desemb_Seleccionar($row['idaprobacion']);
                
                ?>
        
        <td align="right" valign="middle" bgcolor="#CAC5FA"><a
							href="javascript:;"
							onclick="viewAprobacion1('<?php echo($row["t02_cod_proy"]);?>', '<?php echo($row['idaprobacion']);?>');"
							title="Ver Detalle de la Aprobación">
		<?php echo( number_format($rAprob['t59_mto_aprob'],2));?>
        </a></td>
						<td align="center" valign="middle" bgcolor="#CAC5FA"><a
							href="javascript:;"
							onclick="viewAprobacion1('<?php echo($row["t02_cod_proy"]);?>', '<?php echo( $row['idaprobacion'] );?>');"
							title="Ver Detalle de la Aprobación">
		<?php echo( $rAprob['t59_fch_aprob']);?>
        </a></td>
						<td align="right" valign="middle" bgcolor="#FFFF9D"><?php echo( number_format($row['desembolso'],2));?></td>
						<td align="right" valign="middle" bgcolor="#FFFF9D"><?php echo( number_format($row['monto_desembolsar']-$row['desembolso'],2));?></td>
						<td align="center" valign="middle" bgcolor="#FFFF9D"><?php echo( $row['nro_trans']);?></td>
						<td align="center" valign="middle" bgcolor="#FFFF9D"><?php echo( $row['fec_ult_desemb']);?></td>
						<td align="left" valign="middle" bgcolor="#FFFF9D"><?php echo( nl2br( $row['observaciones']));?></td>
					</tr>
            <?php
            } else {
                ?>
        <?php
            }
            ?>
     	
      <?php
            $RowIndex ++;
        }
        $iRs->free();
    } else {
        ?>
        <tr>
						<td colspan="15" align="left" valign="middle"><strong
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
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>

			<p>
				<input type="hidden" name="t02_cod_proy"
					value="<?php echo($idProy);?>" />
				<script language="javascript" type="text/javascript">

function viewAprobacion1(idproy, idapro)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>	
	
	var url = "prim_desemb_edit.php?action=<?php echo(md5("ajax_view"));?>&idProy="+idproy+"&idAprob="+idapro ;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Aprobación del Primer Desembolsos");?>",url);
	
	return;
  }
  
function viewAprobacion(idproy, idtrim, mtoplan)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>	
	
	var url = "aprob_desemb_edit.php?action=<?php echo(md5("ajax_view"));?>&idProy="+idproy+"&idTrimEjec="+idtrim+"&mtoplan="+mtoplan;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Aprobación de Desembolsos");?>",url);
	
	return;
  }

 function EditarEjecucion(idProy, idTrim, idAprob)
  {
	<?php $ObjSession->AuthorizedPage("VER"); ?>	
	
	var url = "ejec_desemb_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&idTrim="+idTrim+"&idAprobacion="+idAprob;
	ChangeStylePopup("PopupDialog");
	loadPopup("<?php echo("Ejecución de Desembolsos");?>",url);
	
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