<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLEjecutor.class.php");

$HC = new HardCode();
$objFE = new BLFE();
$objEjec = new BLEjecutor();

$view = $objFunc->__Request('action');
$idProy = $objFunc->__Request('idProy');
$idTrim = $objFunc->__Request('idTrim');
$idAprobacion = $objFunc->__Request('idAprobacion');
$continuar = false;

if ($idTrim == 1) {
    $row = $objFE->Aprobacion_Primer_Desemb_Seleccionar($idAprobacion);
} else {
    $row = $objFE->Aprobacion_Desemb_Seleccionar2($idAprobacion);
    $comp = $objFE->Aprob_desemb_Con($idAprobacion, $idProy);
    if ($comp['t60_mto_desemb'] >= $comp['t60_mto_aprob']) {
        $continuar = true;
    }
}

$rcuenta = $objEjec->SeleccionarCuenta($row['t01_id_inst'], $row['t01_id_cta']);

$objFunc->SetSubTitle("Ejecución de Desembolsos");

function getTrimTxt($pRow)
{
    $aTrim = $pRow["trim_desembolsar"];
    $aNroApr = $pRow['t60_nro_aprob'];

    return "Trimestre $aTrim" . ($aNroApr ? " ($aNroApr)" : '');
}

?>

<?php if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<?php

$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type=text/javascript></SCRIPT>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="TemplateEditDetails" -->

				<!-- InstanceEndEditable -->
				<div id="divContent"  style="margin:5px">
					<!-- InstanceBeginEditable name="Contenidos" -->
 <?php } ?>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js"
						type="text/javascript"></script>
					<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css"
						rel="stylesheet" type="text/css" />
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
						type="text/javascript"></script>

					<div class="BackColor" style="height: 4px;" id="toolbar">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tbody>
								<tr>						    		
									<td width="27%">
										<button onclick="CancelEdit(); return false;" class="Button">Cerrar y Volver</button>
									</td>
																		
									<td align="right" width="24%">Modificación de Ejecucion de Desembolsos por Entregables</td>
								</tr>
							</tbody>
						</table>
					</div>
														
										
										
						<table border="0" cellpadding="0" cellspacing="0"
							style="width: 99%">
							<tr valign="baseline">
								<td align="left">
									<table style="max-width: 580px;" border="0" align="left"
										cellpadding="0" cellspacing="2" class="TableEditReg">
										<tr valign="baseline">
											<td width="115" align="left" nowrap="nowrap"><strong>Codigo
													Proyecto</strong>:</td>
											<td width="105" align="left"><?php echo($row["t02_cod_proy"]);?></td>
											<td width="63"><strong>Institución</strong>:</td>
											<td width="253"><?php echo($row["t01_sig_inst"]);?></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap="nowrap"><strong>Nombre Proyecto</strong>:
											</td>
											<td colspan="3" align="left"><?php echo($row["t02_nom_proy"]);?>
              <input name="t02_proyecto" type="hidden"
												class="ejecdesemb" id="t02_proyecto"
												value="<?php echo($idProy); ?>" /> <input
												name="t60_trimestre" type="hidden" class="ejecdesemb"
												id="t60_trimestre" value="<?php echo($idTrim); ?>" /> <input
												name="t60_id_aprob" type="hidden" class="ejecdesemb"
												id="t60_id_aprob" value="<?php echo($idAprobacion); ?>" /> <input
												name="txt_id_inst" type="hidden" class="ejecdesemb"
												id="txt_id_inst" value="<?php echo($row["t01_id_inst"]); ?>" />
												<input name="txt_id_fe" type="hidden" class="ejecdesemb"
												id="txt_id_fe"
												value="<?php echo($HC->codigo_Fondoempleo); ?>" /> <input
												name="txt_cta_dest" type="hidden" class="ejecdesemb"
												id="txt_cta_dest" value="<?php echo($row["t01_id_cta"]); ?>" />
												<input name='t60_nro_aprob' type='hidden' class='ejecdesemb'
												id='t60_nro_aprob'
												value='<?php echo $row['t60_nro_aprob']; ?>' /></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap="nowrap"><strong>Fecha de Inicio</strong>:</td>
											<td align="left"><?php echo($row["t02_fch_ini"]);?></td>
											<td align="left"><strong>F. </strong><strong>Término:</strong></td>
											<td align="left" valign="middle"><?php echo($row["t02_fch_tre"]);?></td>
										</tr>
										<tr valign="baseline">
											<td colspan="4" align="left" nowrap="nowrap"><HR /></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap="nowrap"><strong>Entidad Financiera</strong></td>
											<td colspan="3" align="left"><?php echo($rcuenta["banco"]);?></td>
										</tr>
										<tr valign="baseline">
											<td height="15" align="left" nowrap="nowrap"><strong>Tipo
													Cuenta</strong></td>
											<td align="left"><?php echo($rcuenta["tipocuenta"]);?></td>
											<td align="left"><strong>N° Cuenta</strong></td>
											<td align="left" valign="middle"><?php echo($rcuenta["nrocuenta"]." / ".$rcuenta["moneda"]);?></td>
										</tr>
										<tr valign="baseline">
											<td align="left" nowrap="nowrap"><strong>Trim. por desemb.</strong></td>
											<td align="left"><?php echo getTrimTxt($row); ?></td>
											<td colspan="2" align="left"><strong>Importe Aprobado: </strong> <?php echo(number_format(($idTrim==1 ? $row["t59_mto_aprob"] : $row["t60_mto_aprob"]),2))?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr valign="baseline">
								<td align="left"><br />
									<div id="divEjecutadesembolso">
										<div class="TableGrid" id="divEjecutadesembolso">

      <?php
    $montoaprobado = ($idTrim == 1 ? $row["t59_mto_aprob"] : $row["t60_mto_aprob"]);
    $iRs = $objFE->ListaDesembolsos($idProy, $idTrim);
    $sum_desemb = 0;
    $saldo = $montoaprobado;
    $sum_saldo = 0;
    $aReportData = array();

    while ($row = mysqli_fetch_assoc($iRs)) {
        $sum_desemb += $row["t60_mto_des"];
        $saldo = ($montoaprobado - $sum_desemb);
        $sum_saldo += $saldo;
        $aReportData[] = array(
            'IdDesemb' => $row["t60_id_desemb"],
            'Modalidad' => $row["tipopago"],
            'FechaGiro' => $row["t60_fch_giro"],
            'ImporteDesemb' => $row["t60_mto_des"],
            'SaldoxDesemb' => round($saldo, 2),
            'NroChequeTrans' => $row["t60_nro_cheque"],
            'FechaDep' => $row["t60_fch_depo"],
            'Observaciones' => $row["t60_obs_tippago"]
        );
    }
    ?>
      <table width="580" border="0" cellpadding="0" cellspacing="0">
												<tr class="SubtitleTable"
													style="border: solid 1px #CCC; background-color: #eeeeee;">
													<td width="17" height="26" align="center"
														style="border: solid 1px #CCC; background-color: #FFF;"><a
														href="javascript:"> <input type="image"
															<?php echo($lsDisabled)?> src="../../../img/nuevo.gif"
															alt="" width="16" height="16" border="0"
															title="Nuevo desembolso"
															onclick="btnNuevoDesembolso('<?php echo($idAprobacion);?>'); return false;" />
													</a></td>
													<td width="117" align="center"
														style="border: solid 1px #CCC;">Modalidad</td>
													<td width="76" align="center"
														style="border: solid 1px #CCC;">Fecha de Giro</td>
													<td width="85" align="center"
														style="border: solid 1px #CCC;">Importe Desembolsado</td>
													<td width="83" align="center"
														style="border: solid 1px #CCC;">Saldo por Desembolsar</td>
													<td width="87" align="center"
														style="border: solid 1px #CCC;">Nro Cheque / Transferencia</td>
													<td width="117" align="center"
														style="border: solid 1px #CCC;">Fecha Deposito</td>
													<td width="96" align="center"
														style="border: solid 1px #CCC;">Observaciones</td>
													<td width="21" align="center"
														style="border: solid 1px #CCC;">&nbsp;</td>
												</tr>
												<tbody class="data">
		<?php
if (count($aReportData) > 0) {
    foreach ($aReportData as $aRow) {
        ?>
				<tr class="RowData" style="background-color: #FFF;">
														<td nowrap="nowrap"><span> <a href="javascript:"> <img
																	src="../../../img/pencil.gif" alt="" width="14"
																	height="14" border="0" title="Editar Desembolso"
																	onclick="btnEditarDesembolso('<?php echo($aRow["IdDesemb"]);?>','<?php echo($aRow["ImporteDesemb"]);?>', <?php echo $continuar ? 'true' : 'false'; ?>); return false;" />
															</a>
														</span></td>
														<td align="left"><?php echo $aRow["Modalidad"];?></td>
														<td align="center"><?php echo $aRow["FechaGiro"];?></td>
														<td align="center"><?php echo number_format($aRow["ImporteDesemb"],2);?></td>
														<td align="center"><?php echo number_format($aRow["SaldoxDesemb"], 2);?></td>
														<td align="center"><?php echo $aRow["NroChequeTrans"];?></td>
														<td align="center"><?php echo $aRow["FechaDep"];?></td>
														<td align="left"><?php echo $aRow["Observaciones"];?></td>
														<td align="center"><a href="javascript:"> <img
																src="../../../img/bt_elimina.gif" alt="" width="14"
																height="14" border="0" title="Eliminar Registro"
																onclick="EliminarDesembolso('<?php echo($aRow["IdDesemb"]);?>','<?php echo($aRow["Modalidad"]." - ". number_format($aRow["ImporteDesemb"],2 ));?>');" />
														</a></td>
													</tr>
				<?php
    } // for
} // if
else {
    ?>
			<tr class="RowData">
														<td nowrap="nowrap">&nbsp;</td>
														<td align="left">&nbsp;</td>
														<td align="left">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="right">&nbsp;</td>
														<td align="right">&nbsp;</td>
														<td align="right">&nbsp;</td>
													</tr>
		<?php
} // else
?>
        </tbody>
												<tfoot>
													<tr style="color: #FFF; font-size: 11px;">
														<th width="17" height="18">&nbsp;</th>
														<th width="117">&nbsp;</th>
														<th width="76">&nbsp;</th>
														<th width="85" align="right"><?php echo(number_format($sum_desemb,2));?></th>
														<th width="83" align="right"><?php echo(number_format($montoaprobado - $sum_desemb,2));?></th>
														<th width="87">&nbsp;</th>
														<th colspan="3" align="right">&nbsp;</th>
													</tr>
												</tfoot>
											</table>
										</div>
									</div></td>
							</tr>
						</table>
						<input type="hidden" id="txtmontoaprobado" name="txtmontoaprobado"
							value="<?php echo($montoaprobado); ?>" /> <input type="hidden"
							id="txtmontodesembolsado" name="txtmontodesembolsado"
							value="<?php echo($sum_desemb); ?>" />
					


					<script language="javascript">
  function btnNuevoDesembolso()
	{
	  var url = "ejec_desemb_edit2.php?action=<?php echo(md5("ajax_new"));?>&idProy=<?php echo($idProy);?>&idTrim=<?php echo($idTrim);?>&idAprobacion=<?php echo($idAprobacion);?>&idDesemb=" ;

	  loadUrlSpry("divEjecutadesembolso",url);
	}

   function btnEditarDesembolso(idDesemb, monto, continuar)
	{
	  if(!continuar){
		alert("Ud. aún no a desembolsado el total del anterior autorización");
		return false;
	  }
	  var url = "ejec_desemb_edit2.php?action=<?php echo(md5("ajax_edit"));?>&idProy=<?php echo($idProy);?>&idTrim=<?php echo($idTrim);?>&idAprobacion=<?php echo($idAprobacion);?>&idDesemb=" + idDesemb ;
	  var totdesemb = CNumber($('#txtmontodesembolsado').val());
	  var newtot = totdesemb - CNumber(monto) ;
	  $('#txtmontodesembolsado').val(newtot) ;

	  loadUrlSpry("divEjecutadesembolso",url);
	}


	function EliminarDesembolso(codigo, descripcion)
	{
	<?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>

	 if( confirm("Esta seguro de eliminar el desembolso \"" + descripcion + "\"") )
	 {
		 var BodyForm = "t02_proyecto=<?php echo($idProy);?>&t60_trimestre=<?php echo($idTrim);?>&txtcodigo_desemb="+codigo;

		 var sURL = "ejec_desemb_process.php?action=<?php echo(md5("ajax_eliminar"));?>" ;
		 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessEliminarDesembolso, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
	 }

	 return false;
	}

	function MySuccessEliminarDesembolso(req)
	{
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 EditarEjecucion('<?php echo($idProy);?>', '<?php echo($idTrim);?>', '<?php echo($idAprobacion);?>');
		 alert(respuesta.replace(ret,""));
		}
		else
		{  alert(respuesta); }
	}


	function CNumber(str)
	{
	  var numero =0;
	  if (str !="" && str !=null)
	  { numero = parseFloat(str);}

	  if(isNaN(numero)) { numero=0;}

	 return numero;
	}

	function CDate(strDate)
	{
		if(strDate==""){return null ;}
		try
		{
			var dt=strDate.split('/');
			var ndate = new Date(Number(dt[2]),Number(dt[1])-1,Number(dt[0])) ;
			return ndate ;
		}
		catch(e)
		{
			return null ;
		}
	}

</script>

 <?php if($view=='') { ?>
  <!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>
<?php } ?>

