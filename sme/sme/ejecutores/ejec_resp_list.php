<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$OjbTab = new BLTablasAux();
$action = $objFunc->__Request('action');
$id = $objFunc->__GET('id');
if ($objFunc->__GET('idInst')) {
    $id = $objFunc->__GET('idInst');
}

$t01_Id_Inst = $objFunc->__Request('idInst');
$t01_cod_rep = $objFunc->__Request('idCto');

if ($objFunc->__QueryString() == "") {
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
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
<?php
}
?>
  <div class="TableGrid">
		<table width="667" border="0" cellpadding="0" cellspacing="0"
			class="TableGrid">
			<thead>
				<tr>
					<th width="27" align="center" valign="middle"><span
						style="text-align: center; cursor: pointer; width: 50px;"><img
							src="../../img/file.gif" width="14" height="17"
							title="Nuevo Contacto" onclick="AgregarNuevoContacto();" /></span></th>
					<th width="183" height="23" align="center" valign="middle"><strong>Apellidos
							y Nombres </strong></th>
					<th width="166" align="center" valign="middle"><strong>DNI</strong></th>
					<th width="165" align="center" valign="middle"><strong>Cargo</strong></th>
					<th width="130" align="center" valign="middle"><strong>Telefono</strong></th>
					<th width="27" align="center" valign="middle">E-mail</th>
					<th align="center" valign="middle">&nbsp;</th>
				</tr>
			</thead>
			<tbody class="data" bgcolor="#FFFFFF">
     <?php
    $objEjecutor = new BLEjecutor();
    $iRs = $objEjecutor->ResponsableListado($id);
    
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
		<tr>
					<td height="30" align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
					<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['nombres']);?></td>
					<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t01_dni_cto']);?></td>
					<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['cargo']);?></td>
					<td align="center" valign="middle"><?php echo( $row['t01_fono_ofi'].' - '.$row['t01_cel_cto']);?></td>
					<td align="left" valign="middle"><?php echo( $row['t01_mail_cto']);?></td>
					<td align="center" valign="middle"><img
						src="../../img/bt_elimina.gif" alt="" width="14" height="14"
						border="0" style="cursor: pointer;" title="Eliminar Responsable"
						onclick="EliminarResponsable('<?php echo($row['t01_id_cto']);?>');" />
					</td>
				</tr>
	<?php
        } // while
        $iRs->free();
    } // if
    ?>
		<tr>
					<td width="27" align="center" valign="middle"
						style="padding: 3px 1px;"><span
						style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
							<input type="button" value="Guardar" width="16" height="16"
							title="Guardar Responsable "
							onclick="Guardar_Reponsable(); return false ;"
							class="RespInst btn_save" />
					</span></td>
					<td align="left"><select name="t01_nom_rep" id="t01_nom_rep"
						style="width: 180px;" class="RespInst"
						onchange="CargaContactoInst();">
							<option value=""></option>
					<?php
    $rs = $objEjecutor->ContactosListado($id);
    $objFunc->llenarComboI($rs, 't01_id_cto', 'nombres', $t01_id_carg, 'cargo');
    ?>
        			<option value="add"
								style="font-weight: bold; color: #00C; background-color: #FF3;">Agregar
								Nuevo Contacto</option>
					</select></td>
					<td align="center"><input name="t01_dni_rep" type="text"
						disabled="disabled" class="RespInst" id="t01_dni_rep" value=""
						size="10" /></td>
					<td align="center"><input name="t01_carg_rep" type="text"
						disabled="disabled" class="RespInst" id="t01_carg_rep" value=""
						size="20" /></td>
					<td align="center"><input name="t01_tlf_rep" type="text"
						disabled="disabled" class="RespInst" id="t01_tlf_rep" value=""
						size="17" /></td>
					<td colspan="2" align="left"><input name="t01_mail_rep" type="text"
						disabled="disabled" class="RespInst" id="t01_mail_rep" size="25" />
					</td>
				</tr>

			</tbody>
			<tfoot>
				<tr>
					<td colspan="7" align="center" valign="middle">&nbsp; <iframe
							id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
					</td>
				</tr>

			</tfoot>
		</table>
	</div>
	<input name="t01_cod_repres" type="hidden" class="RespInst"
		id="t01_cod_repres" value="<?php echo($t01_cod_rep);?>" />
	<input name="id" type="hidden" class="RespInst" id="id"
		value="<?php echo($id);?>" />


	<script language="javascript" type="text/javascript">
function CargaContactoInst()
{	
	var idInst = "<?php echo($id);?>";
	var idCto  = $("#t01_nom_rep").val();
	
	if(idCto=='add') 
	{  
		AgregarNuevoContacto();
		$("#t01_nom_rep").val("");
		$("#t01_dni_rep").val("");			  
		$("#t01_carg_rep").val("");
		$("#t01_tlf_rep").val("");
		$("#t01_mail_rep").val("");
		return; 
	}
	
	var pURL  = "process_res.php?idInst="+idInst+"&idCto="+idCto+"&action=<?php echo(md5("ajax_carga_contacto"));?>";
	var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessCargaContactoInst, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
} 
	
function MySuccessCargaContactoInst(req)
{
	try
	  {
		var respuesta = req.xhRequest.responseText;
		var obj = jQuery.parseJSON(respuesta);
		$("#t01_dni_rep").val(obj.t01_dni_cto);
		$("#t01_carg_rep").val(obj.cargo);
		$("#t01_tlf_rep").val(obj.t01_fono_ofi + ' - ' + obj.t01_cel_cto  );
		$("#t01_mail_rep").val(obj.t01_mail_cto);
		
	  }
	catch(err)
	  {		
		  $("#t01_dni_rep").val("");			  
		  $("#t01_carg_rep").val("");
		  $("#t01_tlf_rep").val("");
		  $("#t01_mail_rep").val("");
	  return false;
	  }
}

function AgregarNuevoContacto()
{
	var pURL  = "cto_new_resp.php?idInst=<?php echo trim($id);?>&accion=<?php echo(md5("ajax_new"));?>";
	var Titulo = "<?php echo 'Agregar Contacto de Institución ';?>" + $("#t01_sig_inst").val() ;
	loadPopup(Titulo, pURL);
}

function Guardar_Reponsable()
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	if( $('#t01_id_inst').val()=="" ) {alert("Error: Tiene que registrar una Institucion !!!"); return false;}
	
	var res = $('#t01_nom_rep').val();
	if(res=='' || res ==null){
		alert("Seleccione Nombre del Contacto de la Institucion"); 
		$('#t01_nom_rep').focus(); 
		return false;
	}
	
	var BodyForm=$('#FormData .RespInst').serialize();
	
	<?php if($action==md5("ajax_edit")) {?>
	var sURL = "process_res.php?action=<?php echo(md5("ajax_edit"));?>";
	<?php } else {?>
	var sURL = "process_res.php?action=<?php echo(md5("ajax_new"));?>";
	<?php }?>
	  
	var req = Spry.Utils.loadURL("POST", sURL, true, ResponsableSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}

function ResponsableSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadResponsables();
		$('#spnMensaje').html("");
		alert(respuesta.replace(ret,""));
		
	  }
	  else
	  {alert(respuesta);}  
	  
	}
	
function EliminarResponsable(codres)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?")) {
		var BodyForm = "t01_id_inst=<?php echo($id);?>&t01_cod_rep="+codres;
		var sURL = "process_res.php?action=<?php echo(md5("ajax_del"))?>";
	 	var req = Spry.Utils.loadURL("POST", sURL, true, ResEliminarReponsableSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	}
}

function ResEliminarReponsableSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadResponsables();
		alert(respuesta.replace(ret,""));

		var totItem = $("#divResponsables table").find("img[src$='bt_elimina.gif']").length;
		if (totItem <= 0) {
			$('#spnMensaje').html('<br /> La Institución esta Inactiva, debe registrar al menos un Responsable. <br /> <br />');
		} 


		
	  }
	  else
	  {alert(respuesta);}  
	 
	}
	
</script>
 
<?php if($OjbFunc->__QueryString()==""){?>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>