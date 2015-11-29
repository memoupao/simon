<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLMonitoreoFinanciero.class.php");
require_once (constant('PATH_CLASS') . "BLPOA.class.php");

$objInf = new BLMonitoreoFinanciero();
$objPOA = new BLPOA();

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
// $idVersion = $objFunc->__Request('');
$IdActividad = $objFunc->__Request('codigo');
$idInforme = $objFunc->__Request('idInforme');
$idFuente = $objFunc->__Request('idFuente');

$IdActividad = explode('.', $IdActividad);

$idComp = $IdActividad[0];
$idAct = $IdActividad[1];
$idSub = $IdActividad[2];
$idcat = $IdActividad[3];

$idVersion = $objPOA->Proyecto->MaxVersion($idProy);
$rowSub = $objPOA->GetSubActividad($idProy, $idVersion, $idComp, $idAct, $idSub);

$rowInf = $objInf->Inf_MF_Seleccionar($idProy, $idInforme);

// $HC = new HardCode();

error_reporting("E_PARSE");
$retcodigo = 0;

$row = 0;
// $objInf= new BLManejoProy();
// $row = $objInf->GastFunc_SeleccionarCosteo($idProy, $idVersion, $Partida, $idGasto);
$objFunc->SetSubTitle("Gastos No Aceptados ")?>



<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Fuentes de Financiamiento</title>
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
	<form id="frmMain" name="frmMain" method="post" action="#">
 <?php } ?>
 
   <div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					<td width="31%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="9%"><button class="Button"
							onclick="GuardarGNA(); return false;" value="Guardar">Guardar</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="CancelarGNA(); return false;">Cancelar</button></td>
				</tr>
			</table>
		</div>

		<table width="580" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td height="17" align="left" valign="bottom" nowrap><strong>SubActividad</strong></td>
				<td height="17" colspan="3" align="left" valign="bottom" nowrap><?php echo($objFunc->__Request('codigo'." - ".$rowSub['t09_sub']));?></td>
			</tr>
			<tr valign="baseline">
				<td width="91" align="left" nowrap="nowrap"><strong>Periodo Informe</strong></td>
				<td colspan="3" align="left"><?php echo($rowInf['t51_periodo']); ?>
        <input name="txtcomponente" id="txtcomponente" type="hidden"
					class="GastosNoAcpetados" value="<?php echo($idComp);?>" /> <input
					name="txtactividad" id="txtactividad" type="hidden"
					class="GastosNoAcpetados" value="<?php echo($idAct);?>" /> <input
					name="txtsubactividad" id="txtsubactividad" type="hidden"
					class="GastosNoAcpetados" value="<?php echo($idSub);?>" /> <input
					name="txtcat" id="txtcat" type="hidden" class="GastosNoAcpetados"
					value="<?php echo($idcat);?>" /> <input name="txtproyecto"
					id="txtproyecto" type="hidden" class="GastosNoAcpetados"
					value="<?php echo($idProy);?>" /> <input name="txtinforme"
					id="txtinforme" type="hidden" class="GastosNoAcpetados"
					value="<?php echo($idInforme);?>" /> <input name="txtfuente"
					id="txtfuente" type="hidden" class="GastosNoAcpetados"
					value="<?php echo($idFuente);?>" /></td>
			</tr>
			<tr valign="baseline">

				<td height="24" align="left" valign="top" nowrap>&nbsp;</td>
				<td width="111" height="24" align="center" valign="top" nowrap>&nbsp;</td>
				<td width="110" height="24" align="left" valign="top" nowrap>&nbsp;</td>
				<td width="184" height="24" align="left" valign="middle" nowrap>&nbsp;</td>
			</tr>

			<tr valign="baseline">
				<td colspan="4" align="left" valign="middle" nowrap>
					<div id="divTableFinan" class="TableGrid">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="25%" height="18" align="center">Año / Mes</th>
									<th width="15%" align="center">Gasto Ejecutado</th>
									<th width="18%" align="center">Gasto No Aceptado</th>
									<th width="42%" align="center">Observaciones</th>
								</tr>
							</thead>
							<tbody class="data">
          <?php
        // ListaFuentesFinanc
        $rsGastos = $objInf->Inf_MF_ListadoSubActividades_GastosNoAceptados($idProy, $idComp, $idAct, $idSub, $idcat, $idInforme, $idFuente);
        $index = 1;
        $suma = 0;
        $sumagna = 0;
        while ($rowGastos = mysqli_fetch_assoc($rsGastos)) {
            $NomPeriodo = "Año " . $rowGastos['t40_anio'] . " / Mes " . $rowGastos['t40_mes'] . " - " . $rowGastos['nommes'];
            $nameInputGasto = "txtgasto_" . $index;
            ?>
          <tr class="RowData">
									<td align="left" valign="middle"><input name="txtperiodos[]"
										id="txtperiodos[]" type="hidden" class="GastosNoAcpetados"
										value="<?php echo($rowGastos['t40_anio'].".".$rowGastos['t40_mes']);?>" /><?php echo($NomPeriodo);?></td>
									<td align="right"><input type="hidden"
										name="<?php echo($nameInputGasto);?>"
										id="<?php echo($nameInputGasto);?>"
										value="<?php echo($rowGastos['gasto']);?>"
										class="GastosNoAcpetados" />
            <?php echo(number_format($rowGastos['gasto'],2));?>
           </td>
									<td align="center" valign="middle" nowrap="nowrap"><input
										name="txtmonto[]" type="text" id="txtmonto[]"
										style="text-align: right"
										value="<?php echo($rowGastos["t09_mto_no_acept"]);?>"
										size="17" maxlength="10" onkeyup="CalcularTotales();"
										class="GastosNoAcpetados" gasto="1" /></td>
									<td align="left" valign="middle"><textarea name="txtobs[]"
											rows="2" class="GastosNoAcpetados" id="txtobs[]"
											style="width: 100%; height: 99%"><?php echo($rowGastos["t51_obs"]);?></textarea></td>
								</tr>
          <?php
            
$suma += $rowGastos["gasto"];
            $sumagna += $rowGastos["t09_mto_no_acept"];
            $index ++;
        }
        ?>
          </tbody>
							<tfoot>
								<tr style="color: #FFF;">
									<td align="center" valign="middle">&nbsp;</td>
									<td align="center"><b><?php echo(number_format($suma,2));?></b></td>
									<td align="center" valign="middle"><b id="bSumaTotal"><?php echo(number_format($sumagna,2));?></b></td>
									<td align="center" valign="middle">&nbsp;</td>
								</tr>
							</tfoot>
						</table>
						<table width="100%" border="0" cellpadding="0" cellspacing="0"
							style="padding-left: 4px;">
							<tr style="font-size: 11px;">
								<th height="18" align="left" id="estiloff"
									style="padding-left: 4px;"></th>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
		<br /> <br />
		<script language="javascript" type="text/javascript">
	  function CancelarGNA()
	  {
		 spryPopupDialog01.displayPopupDialog(false); 
		 return false;
	  }

	  function GuardarGNA()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>	
		 
		 var BodyForm = $("#FormData .GastosNoAcpetados").serialize() ;
		 var sURL = "inf_financ_process.php?action=<?php echo(md5("ajax_gastos_no_aceptados"));?>" ;
		 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarGNA, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	
		 return false;
	  }
	  
	  function MySuccessGuardarGNA(req)
	  {
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadAvancePresupuestal();	
		 alert(respuesta.replace(ret,""));
		 spryPopupDialog01.displayPopupDialog(false); 
		}
		else
		{  alert(respuesta); }
	  }
	  
   </script>

		<script language="javascript" type="text/javascript">
	  function CalcularTotales()
	  {
		   var lsindex = 1 ; 
		   var sum  = 0 ;
		   var porc = 0 ;
		   var gasto= 0 ;
		   var nogasto= 0 ;
		   
	        $('.GastosNoAcpetados:input[gasto="1"]').each(function() {
				nogasto = CNumber(this.value) ;													   
				gasto   = CNumber($('#txtgasto_' + lsindex).val());
				if(nogasto > gasto )
				{
					alert("El monto es mayor al Gasto reportado por el Ejecutor");
					nogasto = 0;
					$(this).val(0);
					// return;
				}
				sum += nogasto ;

				lsindex++;
				} );
			$('#bSumaTotal').html(sum.toFixed(2)); 
			
			
	  }
	  
	  function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  if(isNaN(numero)) { numero=0;}
		 return numero;
	  }
  
 </script>
 
<?php if($idProy==='') { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>
