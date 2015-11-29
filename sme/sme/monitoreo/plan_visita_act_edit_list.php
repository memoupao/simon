<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLMonitoreo.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVta = $objFunc->__POST('idVta');
$id = $objFunc->__POST('t32_id_act');

if ($idProy == "" && $id == $idVta) {
    $idProy = $objFunc->__GET('idProy');
    $idVta = $objFunc->__GET('idVta');
    $id = $objFunc->__GET('t32_id_act');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Actividades Programadas</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />
<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
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
			<table width="780" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<thead>
					<tr>
						<td width="42" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap"><strong>No</strong></td>
						<td width="210" height="23" align="center" valign="middle"><strong>Actividad</strong></td>
						<td width="83" align="center" valign="middle"><strong>Inicio</strong></td>
						<td width="88" align="center" valign="middle"><strong>Termino</strong></td>
						<td width="338" align="center" valign="middle"><strong>Especificaciones</strong></td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
   
    <?php
    $objInf = new BLMonitoreo();
    $iRs = $objInf->PlanVisitaActivListadoDet($idProy, $idVta);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

    <?php
            if ($action == md5("ajax_edit") && $row['t32_id_act'] == $id) {
                ?>
      <tr class="RowSelected">
						<td width="42" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<img src="../../img/aplicar.png" width="15" height="15"
								title="Guardar Edición del Plan de Visita"
								onclick="Guardar_Actividad();" /> <img
								src="../../img/closePopup.gif" width="16" height="16"
								title="Cancelar " onclick="LoadActividades();" />
						</span></td>
						<td width="27" align="center" valign="middle"><input type="hidden"
							name="t32_id_act" id="t32_id_act"
							value="<?php echo($row['t32_id_act']);?>" />
         <?php echo($row['t32_id_act']);?></td>
						<td align="left" nowrap="nowrap"><select name="t32_cod_act"
							class="TextDescripcion" id="t32_cod_act" style="width: 230px;">
								<option value="" selected="selected"></option>
         <?php
                $objTablas = new BLTablasAux();
                $rsAct = $objTablas->ActividadPlanVisita();
                $objFunc->llenarCombo($rsAct, 'codigo', 'descripcion', $row['t32_cod_act']);
                
                ?>
       </select></td>
						<td align="center" nowrap="nowrap"><input name="t32_fch_ini"
							type="text" id="t32_fch_ini" style="text-align: center;"
							value="<?php echo($row['t32_fch_ini']);?>" size="12"
							maxlength="10" /></td>
						<td align="center"><input name="t32_fch_ter" type="text"
							id="t32_fch_ter" style="text-align: center;"
							value="<?php echo($row['t32_fch_ter']);?>" size="12"
							maxlength="10" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t32_esp_act"
								rows="2" id="t32_esp_act" style="padding: 0px; width: 100%;"><?php echo( $row['t32_esp_act']);?></textarea></td>
					</tr>
      <?php } else { ?>
     <tr>
						<td align="center" valign="middle" nowrap="nowrap"><img
							src="../../img/pencil.gif" width="14" height="14"
							title="Editar Registro" border="0"
							onclick="btnEditarAct_Clic('<?php echo($row['t32_id_act']);?>'); return false;"
							style="cursor: pointer;" /> <img src="../../img/bt_elimina.gif"
							width="14" height="14" title="Eliminar Registro" border="0"
							onclick="EliminarActivPlanVisita('<?php echo($row['t32_id_act']);?>');"
							style="cursor: pointer;" /></td>
						<td height="30" align="center" valign="middle"><?php echo($row['t32_id_act']);?></td>
						<td align="left" valign="middle"><?php echo( $row['actividad']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t32_fch_ini']);?></td>
						<td align="center" valign="middle"><?php echo( $row['t32_fch_ter']);?></td>
						<td align="left" valign="middle"><?php echo( stripcslashes($row['t32_esp_act']));?></td>
					</tr>
     <?php } ?>
     
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>
    
      <?php if($action!=md5("ajax_edit") && $idVta!='')  { ?>
       <tr>
						<td width="42" align="center" valign="middle"><img
							src="../../img/aplicar.png" width="20" height="20"
							title="Guardar Nuevo Plan de Visita"
							onclick="Guardar_Actividad();" style="cursor: pointer;" /></td>
						<td width="27" align="left" valign="middle"><input type="hidden"
							name="t32_id_act" id="t32_id_act"
							value="<?php echo($row['t32_id_act']);?>" /></td>
						<td align="left" nowrap="nowrap"><select name="t32_cod_act"
							class="TextDescripcion" id="t32_cod_act" style="width: 230px;">
								<option value="" selected="selected"></option>
         <?php
        $objTablas = new BLTablasAux();
        $rsAct = $objTablas->ActividadPlanVisita();
        $objFunc->llenarCombo($rsAct, 'codigo', 'descripcion', '');
        
        ?>
       </select></td>
						<td align="center" nowrap="nowrap"><input name="t32_fch_ini"
							type="text" id="t32_fch_ini" style="text-align: center;"
							size="14" maxlength="12" /></td>
						<td align="center" nowrap="nowrap"><input name="t32_fch_ter"
							type="text" id="t32_fch_ter" size="12" maxlength="10"
							style="text-align: center;" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t32_esp_act"
								rows="2" id="t32_esp_act" style="padding: 0px; width: 100%;"></textarea></td>
					</tr>
      <?php } ?>
      
    </tbody>
				<tfoot>
					<tr>
						<td colspan="6" align="center" valign="middle">&nbsp;</td>
					</tr>

				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">
function Guardar_Actividad()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var act = $('#t32_cod_act').val();
 var ini = $('#t32_fch_ini').val();
 var ter = $('#t32_fch_ter').val();
 
 if(act=='' || act==null){alert("Seleccione Actividad a realizar.");return false;}
 if(ini=='' || ini==null){alert("Ingrese fecha de inicio");return false;}
 if(ter=='' || ter==null){alert("Ingrese fecha de Termino");return false;}
  
 var arrParams = new Array();
		arrParams[0] = "t02_cod_proy=" + $("#txtCodProy").val(); 
		arrParams[1] = "t32_id_vta=" + $("#t32_id_vta").val();
		arrParams[2] = "t32_id_act=" + $("#t32_id_act").val();
		arrParams[3] = "t32_cod_act=" + act;
		arrParams[4] = "t32_fch_ini=" + ini ;
		arrParams[5] = "t32_fch_ter=" + ter ;
		arrParams[6] = "t32_esp_act=" + $("#t32_esp_act").val();

  var BodyForm = arrParams.join("&");
	
 
 <?php if($action==md5("ajax_edit")) {?>
 var sURL = "plan_visita_act_process.php?action=<?php echo(md5("ajax_act_edit"));?>";
 <?php } else {?>
 var sURL = "plan_visita_act_process.php?action=<?php echo(md5("ajax_act_new"));?>";
 <?php }?>
 
 var req = Spry.Utils.loadURL("POST", sURL, true, GuardarActivSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

}
function GuardarActivSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadActividades();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
function EliminarActivPlanVisita(id)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
	    var arrParams = new Array();
			arrParams[0] = "t02_cod_proy=" + $("#txtCodProy").val(); 
			arrParams[1] = "t32_id_vta=" + $("#t32_id_vta").val();
			arrParams[2] = "t32_id_act=" + id;

  		var BodyForm = arrParams.join("&");
		
		var sURL = "plan_visita_act_process.php?action=<?php echo(md5("ajax_act_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GuardarActivSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}

function btnEditarAct_Clic(id)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idProy=$('#txtCodProy').val();
	var idVta = $('#t32_id_vta').val();
	
	var url = "plan_visita_act_edit_list.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&idVta="+idVta+"&t32_id_act="+id;
	loadUrlSpry("divActividadesVisita",url);

	return;
  }

</script>
			<script language="javascript" type="text/javascript">
  jQuery("#t32_fch_ini").datepicker();
  jQuery("#t32_fch_ter").datepicker();
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>