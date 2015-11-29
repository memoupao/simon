<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLManejoProy.class.php");

$objMan = new BLManejoProy();

$view = $objFunc->__GET('mode');
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idEqui = $objFunc->__Request('idEqui');

$HC = new HardCode();

error_reporting("E_PARSE");
$retcodigo = 0;

$row = 0;
$objMan = new BLManejoProy();
$row = $objMan->Equipamiento_Seleccionar($idProy, $idVersion, $idEqui);
$objFunc->SetSubTitle("Registro de Fuentes de  Financiamiento")?>



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
							onclick="GuardarFTE(); return false;" value="Guardar">Guardar</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return CancelarFTE();">Cancelar</button></td>
				</tr>
			</table>
		</div>

		<table width="516" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td height="23" colspan="5" align="left" valign="bottom" nowrap><strong>Descripción
						del Equipo</strong></td>
			</tr>
			<tr valign="baseline">
				<td height="29" colspan="5" align="left" nowrap="nowrap"><input
					name="t03_id_equi" type="text" id="t03_id_equi"
					value="<?php echo($row["t03_id_equi"]);?>" size="1" maxlength="5"
					readonly="readonly" style="text-align: center" /> <input
					name="t03_nom_equi" type="text" id="t03_nom_equi" tabindex="0"
					value="<?php echo($row["t03_nom_equi"]);?>" size="70"
					maxlength="70" readonly="readonly" /></td>
			</tr>
			<tr valign="baseline">
				<td width="102" align="center" nowrap="nowrap"><strong>Unidad Medida</strong></td>
				<td width="128" align="center"><strong>Costo Unitario</strong></td>
				<td width="52" align="center" nowrap="nowrap"><strong>Meta</strong></td>
				<td width="102" align="center"><strong>Total</strong></td>
				<td width="108" align="left">&nbsp;</td>
			</tr>
			<tr valign="baseline">
				<td height="25" align="center" valign="middle" nowrap><input
					name="t03_um" type="text" id="t03_um" style="text-align: center"
					value="<?php echo($row["t03_um"]);?>" size="20" readonly="readonly" /></td>
				<td height="25" align="center" valign="middle" nowrap><input
					name="t03_costo" type="text" id="t03_costo"
					style="text-align: center"
					value="<?php echo(number_format($row["t03_costo"],2));?>" size="20"
					maxlength="10" readonly="readonly" /></td>
				<td height="25" align="center" valign="middle" nowrap><input
					name="t03_meta" type="text" id="t03_meta"
					style="text-align: center" value="<?php echo($row["meta"]);?>"
					size="10" maxlength="10" readonly="readonly" /></td>
				<td height="25" align="center" valign="middle" nowrap><input
					name="t03_costo_tot" type="hidden" disabled="disabled"
					value="<?php echo( round($row["t03_costo_tot"],2));?>"
					readonly="readonly" /> <input disabled="disabled"
					id="t03_costo_tot" style="text-align: center"
					value="<?php echo( number_format($row["t03_costo_tot"],2));?>"
					size="20" maxlength="10" readonly="readonly" /></td>
				<td align="center" valign="middle" nowrap><input name="t03_total"
					type="hidden" id="t03_total"
					value="<?php echo($row["t03_costo_tot"]);?>" /></td>
			</tr>

			<tr valign="baseline">
				<td colspan="5" align="left" valign="middle" nowrap>
					<div id="divTableFinan" class="TableGrid">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="5%" height="18" align="center">#</th>
									<th width="69%" align="center">Fuente de Financiamiento</th>
									<th width="15%" align="center">Monto</th>
									<th width="11%" align="center">%</th>
								</tr>
							</thead>
							<tbody class="data">
          <?php
        // ListaFuentesFinanc
        $rsFte = $objMan->Equipamiento_FuentesFinanc($idProy, $idVersion, $idEqui);
        $index = 1;
        $suma = 0;
        while ($rowFTE = mysqli_fetch_assoc($rsFte)) {
            ?>
          <tr class="RowData">
									<td align="center" valign="middle"><?php echo($index);?></td>
									<td><input name="txtInstit[]" id="txtInstit[]" type="hidden"
										value="<?php echo($rowFTE['t01_id_inst']);?>" /><?php echo($rowFTE['t01_sig_inst']);?></td>
									<td align="right"><input name="txtmonto[]" type="text"
										id="txtmonto[]" style="text-align: right"
										value="<?php echo($rowFTE["mtofinan"]);?>" size="13"
										maxlength="10" onkeyup="CalcularTotales();"
										class="summonto roundDec" /></td>
									<td align="center" valign="middle" nowrap="nowrap"><span
										id="porcFTE_<?php echo($index);?>"><?php echo($rowFTE['porcentaje']);?></span></td>
								</tr>
          <?php
            
$suma = $suma + $rowFTE["mtofinan"];
            $index ++;
        }
        ?>
          <tr>
									<td align="center" valign="middle">&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right"><b id="bSumaTotal"><?php echo(number_format($suma,2));?></b></td>
									<td align="center" valign="middle">&nbsp;</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td align="center" valign="middle">&nbsp;</td>
									<td>&nbsp;</td>
									<td align="right">&nbsp;</td>
									<td align="center" valign="middle">&nbsp;</td>
								</tr>
							</tfoot>
						</table>
						<span id="msgFteEquipa"
							style="background-color: #FF0; color: #F00; font-weight: bold; font-size: 11px; font-family: Arial, Helvetica, sans-serif">
						</span>
					</div>
				</td>
			</tr>
		</table>
		<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			bindRoundDecimals();
		});

	  function CancelarFTE()
	  {
		 spryPopupDialog01.displayPopupDialog(false); 
		 return false;
	  }

	  function GuardarFTE()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>	
		 
		 if($('#t03_id_equi').val()=="")
		 { alert("No se ha Cargado correctamente el Equipo"); return false ;}
		 
		 if( $("#msgFteEquipa").text()!="" )
		 {
			 alert("Error:\n"+$("#msgFteEquipa").text()) ;
			 return false ;
	     }
		 
		 var BodyForm = $("#FormData").serialize() ;
		 var sURL = "mp_equipa_process.php?action=<?php echo(md5("save_fuentes_financ"));?>" ;
		 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarPersonalFTE, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	
		 return false;
	  }
	  
	  function MySuccessGuardarPersonalFTE(req)
	  {
		var respuesta = req.xhRequest.responseText;
		respuesta = respuesta.replace(/^\s*|\s*$/g,"");
		var ret = respuesta.substring(0,5);
		if(ret=="Exito")
		{
		 LoadEquipamiento(true);	
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
		  var costotot = $('#t03_total').val();
		   var lsindex = 1 ; 
		   var sum = 0;
		   var porc =0;
		   var mens ="";
	        $(".summonto").each(function() {
	            if(!isNaN(this.value) && this.value.length!=0) 
				{ 	sum += CNumber(this.value);
					porc = (this.value * 100) / CNumber(costotot); }
				else
				{ porc=0;}
				$('#porcFTE_'+lsindex).html(porc.toFixed(2));
				lsindex++;
				} );
			$('#bSumaTotal').html(sum.toFixed(2)); 
			
			
			if (sum > costotot)
			{	mens ="El monto total de las fuentes de financiamiento es mayor que el Costo Total del Equipo " ; }	
			
			$("#msgFteEquipa").text(mens);
			
			
	  }
	  
	  function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  if(isNaN(numero)) { numero=0;}
		 return numero;
	  }
  
  
     $(".summonto").numeric().pasteNumeric();
	 CalcularTotales();
  
 </script>
 
<?php if($view=='') { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>
