<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php

require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "HardCode.class.php");

$HC = new HardCode();
if ($ObjSession->PerfilID != $HC->SP ) {
    die('Acceso no autorizado');
}

$OjbTab = new BLTablasAux();
$action = $objFunc->__Request('action');
$id = $objFunc->__GET('id');
if ($objFunc->__GET('idInst')) {
    $id = $objFunc->__GET('idInst');
}
$t01_Id_Inst = $objFunc->__GET('idInst');
$t01_id_carg = $objFunc->__GET('cargo');
$t01_ape_rep = $objFunc->__GET('ape');
$t01_nom_rep = $objFunc->__GET('nom');
$t01_dni_rep = $objFunc->__GET('dni');
$t01_cod_rep = $objFunc->__GET('cod');

if ($OjbTab == NULL) { 
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>

<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->

<?php
}
?>
  <div class="TableGrid">
		<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="TableGrid">
			<thead>
				<tr>
					<th width="27" align="center" valign="middle">&nbsp;</th>
					<th width="183" height="23" align="center" valign="middle"><strong>Entidad
							Financiera</strong></th>
					<th width="166" align="center" valign="middle"><strong>Tipo de
							Cuenta</strong></th>
					<th width="165" align="center" valign="middle"><strong>Nro Cuenta</strong></th>
					<th width="130" align="center" valign="middle"><strong>Moneda</strong></th>
					<th width="27" align="center" valign="middle">&nbsp;</th>
				</tr>
			</thead>
			<tbody class="data" bgcolor="#FFFFFF">
   
     <?php
    
    $objEjecutor = new BLEjecutor();
    $iRs = $objEjecutor->ListadoCuentas($id);
    
    $RowIndex = 0;
    
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
     	<?php
            if ($action == md5("ajax_edit") && $row['t01_ape_rep'] == $t01_ape_rep && $row['t01_nom_rep'] == $t01_nom_rep && $row['t01_dni_rep'] == $t01_dni_rep) {
                
                ?>
      <tr class="RowSelected">
					<td width="27" align="center" valign="middle" nowrap="nowrap"
						bgcolor="#FFFFFF"><span
						style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
       <?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
        <img src="../../img/aplicar.png" width="16" height="16"
							title="Guardar Cuenta Bancaria" onclick="Guardar_CtaBancaria();" />
       <?php } ?>
       </span></td>
					<td align="center" nowrap="nowrap"><input type="hidden"
						name="t01_cod_rep" id="t01_cod_rep"
						value="<?php echo($t01_cod_rep);?>" /> <select name="cbobanco"
						id="cbobanco" style="width: 120px;" class="CtaBanco">
							<option value=""></option>
	   		<?php
                $OjbTab = new BLTablasAux();
                $rs = $OjbTab->ListaBancos();
                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_id_carg);
                ?>
    		</select></td>

					<td><select name="cbotipocta" id="cbotipocta" style="width: 120px;"
						class="CtaBanco">
							<option value=""></option>
  		  <?php
                $OjbTab = new BLTablasAux();
                $rs = $OjbTab->ListaTipoCuentas();
                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_id_carg);
                ?>
	    </select></td>

					<td align="center" nowrap="nowrap"><input name="txtnrocuenta"
						type="text" class="CtaBanco" id="txtnrocuenta"
						value="<?php echo($t01_nom_rep); ?>" size="20" /></td>
					<td align="center" nowrap="nowrap"><select name="cbomoneda"
						class="CtaBanco" id="cbomoneda" style="width: 100px;">
							<option value=""></option>
           <?php
                $OjbTab = new BLTablasAux();
                $rs = $OjbTab->ListaTipoMoneda();
                $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t01_id_carg);
                ?>
        </select></td>
					<td align="center" nowrap="nowrap"><span
						style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
          <?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
          <img src="../../img/closePopup.gif" width="16" height="16"
							title="Cancelar " onclick="LoadCuentasBancarias();" />
          <?php } ?>
        </span></td>
				</tr>
      <?php } else { ?>
     <tr>

					<td height="30" align="center" valign="middle" nowrap="nowrap"><img
						src="../../img/pencil.gif" width="14" height="13"
						title="Modificar Registro" border="0"
						onclick="editarCuentaBancaria('<?php echo($id);?>','<?php echo($row['t01_id_cta']);?>','<?php echo($row['t01_nom_rep']);?>','<?php echo($row['t01_dni_rep']);?>','<?php echo($row['t01_cod_rep']);?>');"
						style="cursor: pointer;" /></td>

					<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['banco']);?></td>
					<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['tipocuenta']);?></td>
					<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t01_nro_cta']);?></td>
					<td align="center" valign="middle"><?php echo( $row['moneda']);?></td>
					<td align="center" valign="middle">
    					<?php
    					// -------------------------------------------------->
    					// DA 2.0 [13-11-2013 10:02]
    					// Se actualizo reemplazando por el id correcto de la tabla.
    					/*  <img src="../../img/bt_elimina.gif" width="16" height="16" title="Eliminar Cuenta Bancaria" border="0" onclick="EliminarCuentasBancarias('<?php echo($row['t01_cod_rep']);?>');" style="cursor: pointer;" /> */
    					// --------------------------------------------------<
    					?>
    					<img src="../../img/bt_elimina.gif" width="16" height="16" title="Eliminar Cuenta Bancaria" border="0" onclick="EliminarCuentasBancarias('<?php echo($row['t01_id_cta']);?>');" style="cursor: pointer;" />
					</td>
				</tr>
     <?php } ?>
     
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }
    ?>
    
      <?php if($action!=md5("ajax_edit") )  { ?>
       <tr>
					<td width="27" align="center" valign="middle"><span
						style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
							<input type="image" src="../../img/aplicar.png" width="16"
							height="16" title="Guardar Cuenta Bancaria "
							onclick="Guardar_CtaBancaria(); return false ;" class="CtaBanco" />
					</span></td>
					<td align="center" nowrap="nowrap"><select name="cbobanco"
						class="CtaBanco" id="cbobanco" style="width: 120px;">
							<option value=""></option>
         <?php
        $OjbTab = new BLTablasAux();
        $rs = $OjbTab->ListaBancos();
        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $t01_id_carg);
        ?>
       </select></td>
					<td align="center" nowrap="nowrap"><select name="cbotipocta"
						class="CtaBanco" id="cbotipocta" style="width: 120px;">
							<option value=""></option>
      	  <?php
        $rs = $OjbTab->ListaTipoCuentas();
        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $t01_id_carg);
        ?>
   	     </select></td>
					<td align="center" nowrap="nowrap"><input name="txtnrocuenta"
						type="text" class="CtaBanco" id="txtnrocuenta"
						value="<?php echo($t01_nom_rep); ?>" size="20" /></td>
					<td align="center" nowrap="nowrap"><select name="cbomoneda"
						class="CtaBanco" id="cbomoneda" style="width: 100px;">
							<option value=""></option>
    	    <?php
        $rs = $OjbTab->ListaTipoMoneda();
        $objFunc->llenarComboI($rs, 'codigo', 'nombre', $t01_id_carg);
        ?>
  	    </select></td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
				</tr>
      	<?php } ?>
      
    </tbody>
			<tfoot>
				<tr>
					<td colspan="6" align="center" valign="middle">&nbsp; <iframe
							id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
					</td>
				</tr>

			</tfoot>
		</table>
	</div>
	<input name="t01_cod_cta" type="hidden" class="CtaBanco"
		id="t01_cod_cta" value="<?php echo($t01_cod_rep);?>" />
	<input type="hidden" name="id" id="id" value="<?php echo($id);?>" />


	<script language="javascript" type="text/javascript">

function Guardar_CtaBancaria()
{
 <?php $ObjSession->AuthorizedPage(); ?>	

 if( $('#t01_id_inst').val()=="" ) {alert("Error: Tiene que registrar una Institucion !!!"); return false;}
 if( $('#cbobanco').val() == '' ) {
	    alert('Error: Por favor elija una Entidad Financiera.');
	    $('#cbobanco').focus();
	    return false;
 }
 
 if ( $('#cbotipocta').val() == '' ){
	    alert('Error: Por favor elija un Tipo de Cuenta.');
     $('#cbotipocta').focus();
     return false;
	 
 } 

 if ( $.trim($('#txtnrocuenta').val()) == '' ){
    	alert('Error: Por favor Ingrese el Nro de Cuenta.');
        $('#txtnrocuenta').focus();
        return false;
 }
 
 if ( $('#cbomoneda').val() == '' ){
	    alert('Error: Por favor elija el Tipo de Moneda.');
	    $('#cbomoneda').focus();
	    return false;
 }

 
 
 
	 	

    
 var BodyForm="idinst="+ $('#t01_id_inst').val() + "&" + $('#FormData .CtaBanco').serialize();
 
 <?php if($action==md5("ajax_edit")) {?>
   
 var sURL = "process_ctas.php?action=<?php echo(md5("ajax_edit"));?>";
 <?php } else {?>
 var sURL = "process_ctas.php?action=<?php echo(md5("ajax_new"));?>";
 <?php }?>
  
var req = Spry.Utils.loadURL("POST", sURL, true, CuentasBancariasSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });


}
function CuentasBancariasSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadCuentasBancarias();
		alert(respuesta.replace(ret,""));
		
	  }
	  else
	  {alert(respuesta);}  
	  
	}
	
function EliminarCuentasBancarias(codres)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		<?php 
		// -------------------------------------------------->
		// DA 2.0 [13-11-2013 10:02]
		// Se adiciono el id de la Institucion
		?>
		var BodyForm = "t01_cod_rep="+codres+'&id='+$('#id').val();
		<?php // --------------------------------------------------< ?>		
		var sURL = "process_res.php?action=<?php echo(md5("ajax_del_cuenta_bancaria"))?>";
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
        <?php 
            // -------------------------------------------------->
            // DA 2.0 [13-11-2013 10:02]
            // Se actualizo al nombre correcto de la funcion de LoadCuentasBancariass a LoadCuentasBancarias
        ?>
		LoadCuentasBancarias();
		<?php // --------------------------------------------------< ?>		
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

function editarCuentaBancaria(idIns, idCta)
{
	loadPopup("Editar Cuenta Bancaria", "cta_bancaria_edit.php?idInst="+idIns+"&idCta="+idCta);
}

</script>
 
<?php if($OjbTab==NULL){?>
</body>
</html>
<?php } ?>