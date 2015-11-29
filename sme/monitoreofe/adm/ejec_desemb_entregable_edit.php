<?php 
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant('PATH_CLASS') . "BLFE.class.php");
require_once (constant('PATH_CLASS') . "BLEjecutor.class.php");

$HC = new HardCode();
$objFE = new BLFE();
$objEjec = new BLEjecutor();

$view = $objFunc->__Request('view');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$anio = $objFunc->__Request('anio');
$mes = $objFunc->__Request('mes');

$row = $objFE->selDesembEncabezado($idProy, $idVersion);
$rc = $objEjec->SeleccionarCuenta($row['t01_id_inst'], $row['t01_id_cta']);
$re = $objFE->getDatosEntregable($idProy, $idVersion, $anio, $mes);

$objFunc->SetSubTitle("Ejecución de Desembolsos");
?>

<?php if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php

$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo($_SERVER['PHP_SELF']);?>">
				<div id="divContent" style="margin:5px">
 <?php } ?>
					<script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
					<link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>

					<div class="BackColor" style="height: 4px;" id="toolbar">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tbody>
								<tr>						    		
									<td width="27%">
										<button onclick="CancelEdit(); return false;" class="Button">Cerrar</button>
									</td>
																		
									<td align="right" width="24%">Modificación de Ejecucion de Desembolsos por Entregables</td>
								</tr>
							</tbody>
						</table>
					</div>
						<table border="0" cellpadding="0" cellspacing="0" style="width: 99%">
							<tr>
								<td>
									<table border="0" cellpadding="5" cellspacing="2" class="TableEditReg">
										<tr>
											<td><b>Entidad Financiera</b></td>
											<td colspan="3"><?php echo($rc["banco"]);?></td>
										</tr>
										<tr>
											<td><b>Tipo Cuenta</b></td>
											<td><?php echo($rc["tipocuenta"]);?></td>
											<td><b>N° Cuenta</b></td>
											<td valign="middle"><?php echo($rc["nrocuenta"]." / ".$rc["moneda"]);?></td>
										</tr>
										<tr>
											<td><b>Entregable</b></td>
											<td><?php echo($re["entregable"]);?></td>
											<td><b>Periodo</b></td>
											<td valign="middle"><?php echo($re["periodo"]);?></td>
										</tr>
										<tr>
											<td><b>Planeado</b></td>
											<td colspan="3"><?php echo(number_format($re["planeado"], 2));?></td>
										</tr>
										<tr>
											<td><b>Desembolsado</b></td>
											<td colspan="3"><?php echo(number_format($re["desembolsado"], 2));?></td>
										</tr>
										<tr>
											<td><b>Saldo</b></td>
											<td colspan="3"><?php echo(number_format($re["planeado"] - $re["desembolsado"], 2));?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr valign="baseline">
								<td align="left"><br />
									<div id="divEjecutadesembolso">
										<div class="TableGrid" id="divEjecutadesembolso">

      <?php
    $montoaprobado = $re["planeado"];
    $iRs = $objFE->getListaDesembolsados($idProy, $idVersion, $anio, $mes);
    $sum_desemb = 0;
    $saldo = $montoaprobado;
    $sum_saldo = 0;
    $aReportData = array();

    while ($row = mysqli_fetch_assoc($iRs)) {
        $sum_desemb += $row["t60_mto"];
        $saldo = ($montoaprobado - $sum_desemb);
        $sum_saldo += $saldo;
        $aReportData[] = array(
            'idDesemb' => $row["t60_id"],
            'modalidad' => $row["modalidad"],
            'fechaGiro' => $row["t60_fch_giro"],
            'importeDesemb' => $row["t60_mto"],
            'saldo' => round($saldo, 2),
            'cheque' => $row["t60_cheque"],
            'fechaDep' => $row["t60_fch_depo"],
            'obs' => $row["t60_obs"]
        );
    }
    ?>
      <table width="580" border="0" cellpadding="0" cellspacing="0">
		<tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
			<td width="17" height="26" align="center" style="border: solid 1px #CCC; background-color: #FFF;">
				<a href="javascript:"> 
					<input type="image" <?php echo($lsDisabled)?> src="../../../img/nuevo.gif"
					alt="" width="16" height="16" border="0" title="Nuevo desembolso"
					onclick="btnNuevoDesembolso(); return false;" />
				</a>
			</td>
			<td width="117" align="center" style="border: solid 1px #CCC;">Modalidad</td>
			<td width="76" align="center" style="border: solid 1px #CCC;">Fecha de Giro</td>
			<td width="85" align="center" style="border: solid 1px #CCC;">Importe Desembolsado</td>
			<td width="83" align="center" style="border: solid 1px #CCC;">Saldo por Desembolsar</td>
			<td width="87" align="center" style="border: solid 1px #CCC;">Nro Cheque / Transferencia</td>
			<td width="117" align="center" style="border: solid 1px #CCC;">Fecha Deposito</td>
			<td width="96" align="center" style="border: solid 1px #CCC;">Observaciones</td>
			<td width="21" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
		</tr>
		<tbody class="data">
		<?php
if (count($aReportData) > 0) {
    foreach ($aReportData as $aRow) {
        ?>
		<tr class="RowData" style="background-color: #FFF;">
			<td nowrap="nowrap">
				<span> 
					<a href="javascript:"> 
						<img src="../../../img/pencil.gif" alt="" width="14"
						height="14" border="0" title="Editar Desembolso"
						onclick="btnEditarDesembolso(<?php echo($aRow["idDesemb"]);?>); return false;" />
					</a>
				</span>
			</td>
			<td align="left"><?php echo $aRow["modalidad"];?></td>
			<td align="center"><?php echo $aRow["fechaGiro"];?></td>
			<td align="center"><?php echo number_format($aRow["importeDesemb"],2);?></td>
			<td align="center"><?php echo number_format($aRow["saldo"], 2);?></td>
			<td align="center"><?php echo $aRow["cheque"];?></td>
			<td align="center"><?php echo $aRow["fechaDep"];?></td>
			<td align="left"><?php echo $aRow["obs"];?></td>
			<td align="center"><a href="javascript:"> <img
					src="../../../img/bt_elimina.gif" alt="" width="14"
					height="14" border="0" title="Eliminar Registro"
					onclick="btnEliminarDesembolso('<?php echo($aRow["idDesemb"]);?>','<?php echo($aRow["modalidad"]." - ". number_format($aRow["importeDesemb"],2 ));?>');" />
			</a></td>
		</tr>
		<?php
    } // for
} // if
else {
    ?>
			<tr class="RowData">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
	<?php
} // else
?>
        </tbody>
		<tfoot>
			<tr style="color: #FFF; font-size: 11px;">
				<th height="18">&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th align="right"><?php echo(number_format($sum_desemb,2));?></th>
				<th align="right"><?php echo(number_format($montoaprobado - $sum_desemb,2));?></th>
				<th colspan="4" align="right">&nbsp;</th>
			</tr>
		</tfoot>
	</table>
</div>
</div></td>
</tr>
</table>
<input type="hidden" id="txtmontoaprobado" name="txtmontoaprobado" value="<?php echo($montoaprobado); ?>" /> 
<input type="hidden" id="txtmontodesembolsado" name="txtmontodesembolsado" value="<?php echo($sum_desemb); ?>" />

<script language="javascript">
  function btnNuevoDesembolso()
	{
	  var url = "ejec_desemb_entregable_edit2.php?action=<?php echo(md5("new"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&anio=<?php echo($anio);?>&mes=<?php echo($mes);?>";

	  loadUrlSpry("divEjecutadesembolso",url);
	}

   function btnEditarDesembolso(idDesemb)
	{
	  var url = "ejec_desemb_entregable_edit2.php?action=<?php echo(md5("edit"));?>&idDesemb=" + idDesemb +"&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&anio=<?php echo($anio);?>&mes=<?php echo $mes;?>";
	  // var totdesemb = CNumber($('#txtmontodesembolsado').val());
	  // var newtot = totdesemb - CNumber(monto) ;
	  // $('#txtmontodesembolsado').val(newtot) ;

	  loadUrlSpry("divEjecutadesembolso",url);
	}


	function btnEliminarDesembolso(idDesemb, descripcion)
	{
	<?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>

	 if( confirm("Esta seguro de eliminar el desembolso \"" + descripcion + "\"") )
	 {
		 var BodyForm = "idDesemb=" + idDesemb;

		 var sURL = "ejec_desemb_entregable_process.php?action=<?php echo(md5("eliminar"));?>" ;
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
		 editarDesembolsos(<?php echo($anio);?>, <?php echo($mes);?>);
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

	function CancelEdit()
	 {
		 spryPopupDialog01.displayPopupDialog(false);
		 window.parent.location.reload();
		 return false;
	 }

</script>

 <?php if($view=='') { ?>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
<?php } ?>