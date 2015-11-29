<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInterface.class.php");

error_reporting("E_PARSE");

$idProy = $objFunc->__Request('idProy');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Detalle Interface</title>
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
<?php
}
?>
<div id="divTableLista">
			<table width="100%" border="0" cellspacing="1" cellpadding="0"
				class="TableEditReg">
				<tr>
					<td width="35%" height="22" valign="bottom"><strong>Sistema EVASIS
					</strong>(Pendientes por relacionar)</td>
					<td width="7%" valign="bottom">&nbsp;</td>
					<td width="35%" valign="bottom"><strong>Sistema Ejecutor </strong></td>
					<td width="23%" valign="bottom">&nbsp;</td>
				</tr>
				<tr>
					<td height="126" valign="top">
						<div style="border: 1px solid #666;">
							<div class="TableGrid">
								<table border="0" cellpadding="0" cellspacing="1"
									style="width: 340px;">
									<thead>
										<tr style="font-size: 11px;">
											<th width="15%">CODIGO</th>
											<th width="85%">DESCRIPCION DEL RUBRO</th>
										</tr>
									</thead>
								</table>
							</div>
     <?php if($idProy!='') { ?>
    <div id="divCodigoSYS" class="TableGrid"
								style="height: 170px; width: 340px; overflow: auto;">
								<table id="tabCodigoSYS" border="0" cellpadding="0"
									cellspacing="1" style="width: 322px;">
									<thead>
										<tr
											style="visibility: hidden; display: none; font-size: 11px;">
											<th width="15%">CODIGO</th>
											<th width="85%">DESCRIPCION DEL RUBRO</th>
										</tr>
									</thead>
									<tbody class="data">
       <?php
        $objInt = new BLInterface();
        
        $rsCodSYS = $objInt->ListaCodigosSistemaAll($idProy);
        $index = 0;
        while ($row = mysqli_fetch_assoc($rsCodSYS)) {
            $index ++;
            
            if ($row['tipo'] == 'componente') {
                $onClick = '';
            }
            if ($row['tipo'] == 'actividad') {
                $onClick = '';
            }
            if ($row['tipo'] == 'subactividad') {
                $onClick = '';
            }
            if ($row['tipo'] == 'rubro') {
                $onClick = 'onclick="SeleccionarSYS(this.id,\'' . $row['codigo'] . '\');"' . ' style="cursor:pointer; "';
            }
            ?>
      
      <tr class="RowData" id='<?php echo("sistematr_".$index);?>'
											<?php echo($onClick);?>>
											<td style="font-size: 11px;"><?php echo($row['codigo']);?></td>
											<td style="font-size: 11px;"><?php echo($row['descripcion']);?></td>
										</tr>
     
     <?php
        }
        $rsCodSYS->free();
        ?>
      
      </tbody>
								</table>
								<input type="hidden" name="txtCodigoSYS" id="txtCodigoSYS"
									value="" />
							</div>
						</div>
    <?php } ?>
    </td>
					<td align="center" valign="middle"><span
						style="font-size: 10px; font-weight: bold; cursor: pointer; color: #036;"
						title="Relacionar Cuentas" onclick="RelacionarCuentas();"> <img
							src="../../../img/syncronizar.gif" width="46" height="46" /> <br />
							Añadir Relación
					</span></td>
					<td valign="top">

						<div style="border: 1px solid #666;">
							<div class="TableGrid">
								<table border="0" cellpadding="0" cellspacing="1"
									style="width: 340px;">
									<thead>
										<tr style="font-size: 11px;">
											<th width="15%">CODIGO</th>
											<th width="85%" align="center">DESCRIPCION DEL RUBRO</th>
										</tr>
									</thead>
								</table>
							</div>
     <?php if($idProy!='') { ?>
    <div id="divCodigoEjec" class="TableGrid"
								style="height: 170px; width: 340px; overflow: auto;">
								<table id="tabCodigoEjecutor" border="0" cellpadding="0"
									cellspacing="1" style="width: 322px;">
									<thead>
										<tr
											style="visibility: hidden; display: none; font-size: 11px;">
											<th width="15%">CODIGO</th>
											<th width="85%">DESCRIPCION DEL RUBRO</th>
										</tr>
									</thead>
									<tbody class="data" style="cursor: pointer;">
       <?php
        $objInt = new BLInterface();
        
        $rsCodEJE = $objInt->ListaCodigosEjecutorAll($idProy);
        $index = 0;
        while ($row = mysqli_fetch_assoc($rsCodEJE)) {
            $index ++;
            // echo("<option value='".$row['codigo']."'>".$row['codigo'].$espacio.$row['descripcion']."</option>");
            ?>
      
      <tr class="RowData" id='<?php echo("ejecutortr_".$index);?>'
											onclick="SeleccionarEjecutor(this.id,'<?php echo($row['codigo']);?>','<?php echo($row['descripcion']);?>');">
											<td style="font-size: 11px;"><?php echo($row['codigo']);?></td>
											<td style="font-size: 11px;"><?php echo($row['descripcion']);?></td>
										</tr>
     
     <?php
        }
        $rsCodEJE->free();
        ?>
      
      </tbody>
								</table>
								<input type="hidden" name="txtCodigoEjecutor"
									id="txtCodigoEjecutor" value="" /> <input type="hidden"
									name="txtDescripcionEjecutor" id="txtDescripcionEjecutor"
									value="" />
							</div>
						</div>
    <?php } ?></td>
					<td align="left" valign="top">
						<table width="30" cellspacing="0" cellpadding="0"
							style="padding-left: 0px; padding-top: 0px;">
							<tr>
								<td height="22" align="center"><img
									src="../../../img/addicon.gif" width="16" height="16"
									title="Nuevo Codigo del Ejecutor" /></td>
							</tr>
							<tr>
								<td height="20" align="center"><img
									src="../../../img/b_drop.png" width="16" height="16"
									title="Eliminar Codigo del Ejecutor" /></td>
							</tr>
							<tr>
								<td align="center">&nbsp;</td>
							</tr>
							<tr>
								<td align="center"><img src="../../../img/upload.jpg" width="25"
									height="20" title="Importar Codigos del Ejecutor" /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="4"
						style="font-size: 11px; color: #039; border: 1px solid #999;">Seleccionar
						un Item de EVASIS, un item de Ejecutor y Añadir relación
						para establecer la relacion entre el sistema EVASIS y el Sistema
						del Ejecutor. Esta relacion sera empleado en los informes
						Financieros.</td>
				</tr>
			</table>
		</div>
		<script language="javascript" type="text/javascript"> 
function SeleccionarSYS(idRow, value)
{
	$("#tabCodigoSYS tbody tr").css({
									 "font-weight":"normal", 
									 "color":"black", 
									 "background-color":"black"
									 }
									 );
	$("#"+idRow).css({"background-color":"navy", "font-weight":"bold", "color":"red"}) ;
	$("#txtCodigoSYS").val(value);
}

function SeleccionarEjecutor(idRow, value, descrip)
{
	$("#tabCodigoEjecutor tbody tr").css({
									 "font-weight":"normal", 
									 "color":"black", 
									 "background-color":"black"
									 }
									 );
	$("#"+idRow).css({"background-color":"navy", "font-weight":"bold", "color":"red"}) ;
	$("#txtCodigoEjecutor").val(value);
	$("#txtDescripcionEjecutor").val(descrip);
	
}

function RelacionarCuentas()
{
	var sys = $.trim($("#txtCodigoSYS").val());
	var eje = $.trim($("#txtCodigoEjecutor").val());
	
	if(sys=='' || sys==null){alert("Error:  No ha seleccionado Ninguna fila correspondiente al sistema."); return false;} 
	if(eje=='' || eje==null){alert("Error:  No ha seleccionado Ninguna fila correspondiente al Ejecutor."); return false;} 
	
	<?php $ObjSession->AuthorizedPage(); ?>	

    var arrParams = new Array();
		arrParams[0] = "t02_cod_proy=" + $("#txtCodProy").val();
		arrParams[1] = "t50_cod_sis=" + sys ;
		arrParams[2] = "t50_cod_ext=" + eje ;
		arrParams[3] = "t50_des_ext=" + $("#txtDescripcionEjecutor").val(); ;

	var BodyForm = arrParams.join("&");
		
	if(confirm("Estas seguro de relacionar los codigos: Sistema["+sys+"] ==> Ejecutor["+eje+"] ?"))
	  {
		var sURL = "process.php?action=<?php echo(md5('ajax_relacionar_cuentas'));?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, SuccessRelacionar, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
	  }
	
	
}

 function SuccessRelacionar(req)
  {
	var respuesta = req.xhRequest.responseText;
	respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	var ret = respuesta.substring(0,5);
	if(ret=="Exito")
	{ 	alert(respuesta.replace(ret,"")); }
	else
	{alert(respuesta);}  
  }

</script>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>