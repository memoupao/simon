<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$OjbTab = new BLTablasAux();
$action = $objFunc->__Request('action');
$idProy = $objFunc->__Request('idProy');
$idVer = $objFunc->__Request('idVersion');
$idInst = $objFunc->__Request('idInst');
$nameBenef = $objFunc->__Request('nameInst');

$objProy = new BLProyecto();
$rproy = $objProy->ProyectoSeleccionar($idProy, $idVer);
$nom_proy = $rproy['t02_cod_proy'] . " - " . $rproy['t01_sig_inst'];

if ($action == md5("ajax_edit")) {
    $objFunc->SetSubTitle("Registro de Cuenta del Proyecto - Modificar");
    $row = $objProy->SeleccionarCuentaProyeto($idProy);
} else {
    $objFunc->SetSubTitle("Registro de Cuenta del Proyecto");
    $row = 0;
}

$lstNroCuentas = $objProy->getNrosDeCuentasPorInstitucion($idInst);

/*
 * $id = $objFunc->__GET('id'); 
 * if ($objFunc->__GET('idInst')) {
 *  $id = $objFunc->__GET('idInst');
 * } 
 * 
 * $t01_Id_Inst = $objFunc->__GET('idInst'); 
 * $t01_id_carg = $objFunc->__GET('cargo'); 
 * $t01_ape_rep = $objFunc->__GET('ape'); 
 * $t01_nom_rep = $objFunc->__GET('nom'); $t01_dni_rep = $objFunc->__GET('dni'); $t01_cod_rep = $objFunc->__GET('cod');
 */
if ($OjbTab == NULL) {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title></title>
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

<?php
}
?>
  <div id="toolbar" style="height: 4px;" class="BackColor">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="8%"><button class="Button"
						onclick="btnGuardar_CtaBancaria(); return false;" value="Guardar">Guardar
					</button></td>
				<td width="25%"><button class="Button"
						onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
						value="Volver y Cerrar">Cancelar</button></td>
				<td width="14%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="50%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
			</tr>
		</table>
	</div>



	<table width="500" border="0" cellspacing="1" cellpadding="0"
		class="TableEditReg">
		<tr>
			<td height="30"><strong>Proyecto</strong></td>
			<td><?php echo($nom_proy); ?></td>
		</tr>
		<tr>
			<td width="108" height="30"><strong>Entidad Financiera</strong></td>
			<td width="389"><select name="cbobanco" id="cbobanco"
				style="width: 180px;" class="CtaBanco" onchange="changeBanco()">
					<option value=""></option>
      <?php
    $OjbTab = new BLTablasAux();
    $rs = $OjbTab->ListaBancos();
    $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['cod_banco']);
    ?>
    </select></td>
		</tr>
		<tr>
			<td height="28"><strong>Tipo de Cuenta</strong></td>
			<td><select name="cbotipocta" id="cbotipocta" style="width: 150px;"
				class="CtaBanco" onchange="changeTipoCuenta()">
					<option value=""></option>
      <?php
    $OjbTab = new BLTablasAux();
    $rs = $OjbTab->ListaTipoCuentas();
    $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['cod_tipocuenta']);
    ?>
    </select></td>
		</tr>
		<tr>
			<td height="28"><strong>Moneda</strong></td>
			<td><select name="cbomoneda" class="CtaBanco" id="cbomoneda"
				style="width: 150px;" onchange="changeMoneda();">
					<option value=""></option>
      <?php
    $OjbTab = new BLTablasAux();
    $rs = $OjbTab->ListaTipoMoneda();
    $objFunc->llenarComboI($rs, 'codigo', 'nombre', $row['cod_moneda']);
    ?>
    </select></td>
		</tr>
		<tr>
			<td height="27"><strong>Nro Cuenta</strong></td>
			<td>
<?php /* ?>			
                <input name="txtnrocuenta" type="text" class="CtaBanco" id="txtnrocuenta" value="<?php echo($row['t01_nro_cta']); ?>" size="30" maxlength="25" />
<?php */ ?>
            
                <select name="lstNroCuentas" id="lstNroCuentas" onchange="changeNroCuentas()">
                    <option></option>
                </select>
			     <input name="txtnrocuenta" type="hidden" class="CtaBanco" id="txtnrocuenta" value="<?php echo($row['t01_nro_cta']); ?>" />
			</td>
		</tr>
		
		<tr>
			<td><strong>Beneficiario(*)</strong></td>
			<td><input name="txtnombenef" type="text" class="CtaBanco"
				id="txtnombenef" value="<?php if (isset($row['beneficiario'])) { echo($row['beneficiario']); } else { echo $nameBenef;} ?>"
				size="50" maxlength="50" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2"><strong>(*)</strong> Nombre del beneficiario a Girar,
				para temas de Desembolsos.</td>
		</tr>
	</table>
	<input name="t01_cod_cta" type="hidden" class="CtaBanco"
		id="t01_cod_cta" value="<?php echo($row['t01_id_cta']);?>" />
	<input type="hidden" name="id" id="id" value="<?php echo($id);?>" />


	<script language="javascript" type="text/javascript">
var lstNroCuentas;

function changeNroCuentas()
{
	$("#txtnrocuenta").val($('#lstNroCuentas').val());    
}

function changeBanco()
{    
	if ($('#cbobanco').val().length > 0) {
		$('#cbotipocta').val('');
		$('#cbomoneda').val('');
		$('#lstNroCuentas').val('');
	    var option = '<option></option>';
		$.each(lstNroCuentas.data,function(i,e){
			
		    if (e.banco == parseInt($('#cbobanco').val())) {
			    option += '<option value="'+e.nro+'">'+e.nro+'</option>';
		    }		
	    });
		
	    $('#lstNroCuentas').html(option);
	}
	

}

function changeTipoCuenta()
{	

	if ($('#cbotipocta').val().length > 0) {
		$('#cbomoneda').val('');
		$('#lstNroCuentas').val('');

		var option = '<option></option>';
		$.each(lstNroCuentas.data,function(i,e){
			
		    if (e.banco == parseInt($('#cbobanco').val()) && e.tipo == parseInt($('#cbotipocta').val()) ) {
			    option += '<option value="'+e.nro+'">'+e.nro+'</option>';
		    }		
	    });
		
	    $('#lstNroCuentas').html(option);
	}
	

    

}

function changeMoneda()
{
	if ($('#cbomoneda').val().length > 0) {
		$('#lstNroCuentas').val('');

		var option = '<option></option>';
		$.each(lstNroCuentas.data,function(i,e){
			
		    if (e.banco == parseInt($('#cbobanco').val()) && e.tipo == parseInt($('#cbotipocta').val()) && e.moneda == parseInt($('#cbomoneda').val())  ) {			   
			    option += '<option value="'+e.nro+'">'+e.nro+'</option>';
		    }		
	    });
		
	    $('#lstNroCuentas').html(option);
	}
	
    
	
}


function loadNroCuentas()
{
	
	var BodyForm = "inst=<?php echo $idInst;?>&proy=<?php echo $idProy;?>";
	var sURL = "process.php?action=<?php echo(md5("getNroCuentasBancariasPorInstitucion"))?>" ;
	var req = Spry.Utils.loadURL("POST", sURL, true, function(req){
	    
		   var respuesta = JSON.parse(req.xhRequest.responseText);
		   lstNroCuentas = respuesta;

<?php if (isset($row['t01_nro_cta'])) {  ?>
            $('#lstNroCuentas').val('');            
            var option = '<option></option>';
            $.each(lstNroCuentas.data,function(i,e){
            	
                if (e.banco == parseInt($('#cbobanco').val()) && e.tipo == parseInt($('#cbotipocta').val()) && e.moneda == parseInt($('#cbomoneda').val())  ) {			   
                    if (e.nro == "<?php echo $row['t01_nro_cta'];?>" ) {
                        option += '<option value="'+e.nro+'" selected="selected">'+e.nro+'</option>';
                    } else {
                    	option += '<option value="'+e.nro+'">'+e.nro+'</option>';
                    }
                }		
            });            
            $('#lstNroCuentas').html(option);
<?php } ?>


		}, 
		{ postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, 
		errorCallback: function(req){
			alert("ERROR: " + req.xhRequest.responseText);
		}
		 
	});
    
}

loadNroCuentas();

	
function btnGuardar_CtaBancaria()
{
 <?php $ObjSession->AuthorizedPage(); ?>	

 if( $('#t01_id_inst').val()=="" ) {alert("Error: Tiene que registrar una Institucion !!!"); return false;}	

 if( $('#cbobanco').val()=="" ) {alert("Error: Seleccione Entidad Financiera !!!"); $('#cbobanco').focus(); return false;}	
 if( $('#cbotipocta').val()=="" ) {alert("Error: Seleccione Tipo de Cuenta!!!"); $('#cbotipocta').focus(); return false;}	
 if( $('#txtnrocuenta').val()=="" ) {alert("Error: Ingrese Numero de la Cuenta !!!"); $('#txtnrocuenta').focus(); return false;}	
 if( $('#cbomoneda').val()=="" ) {alert("Error: Seleccione Moneda !!!"); $('#cbomoneda').focus(); return false;}	
 
   
 var BodyForm="idProy=<?php echo($idProy);?>&" + $('#FormData .CtaBanco').serialize();
 var sURL = "process.php?action=<?php echo(md5("ajax_cta_bancaria"));?>";
   
 var req = Spry.Utils.loadURL("POST", sURL, true, CuentasBancariasSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

}
function CuentasBancariasSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		spryPopupDialog01.displayPopupDialog(false);
		alert(respuesta.replace(ret,""));
		btnEditar_Clic('<?php echo($idProy);?>','<?php echo($idVer);?>', '<?php echo(md5("editar"));?>');
	  }
	  else
	  {alert(respuesta);}  
	  
	}
	
function EliminarCuentasBancarias(codres)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t01_cod_rep="+codres;
		var sURL = "process_res.php?action=<?php echo(md5("ajax_del"))?>";
	 	var req = Spry.Utils.loadURL("POST", sURL, true, ResEliminarSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	
	}
	

}

function ResEliminarSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadCuentasBancariass();
		alert(respuesta.replace(ret,""));
		
	  }
	  else
	  {alert(respuesta);}  
	 
	}
	

function btnEditarRes_Clic(cargo, ape, nom, dni, cod)
  { 
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idInst=$('#t01_id_inst').val();
	 
	var url = "ejec_resp_list.php?action=<?php echo(md5("ajax_edit"));?>&idInst="+idInst+"&cargo="+cargo+"&ape="+ape+"&nom="+nom+"&dni="+dni+"&cod="+cod;
	loadUrlSpry("divCuentasBancariass",url);
	return;
  }

$('#t01_dni_rep').mask('99999999');

</script>
 
<?php if($OjbTab==NULL){?>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>