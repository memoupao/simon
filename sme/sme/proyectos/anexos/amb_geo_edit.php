<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$dpto = $objFunc->__Request('dpto');
$prov = $objFunc->__Request('prov');
$dist = $objFunc->__Request('dist');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Proyectos - Ambito Geografico</title>
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
			<table width="780" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<thead>
					<tr>
						<td width="50" align="center" valign="middle">&nbsp;</td>
						<td width="177" height="23" align="center" valign="middle"><strong>Departamento</strong></td>
						<td width="140" align="center" valign="middle"><strong>Provincia</strong></td>
						<td width="140" align="center" valign="middle"><strong>Distrito</strong></td>
						<td width="381" align="center" valign="middle"><strong>Observaciones</strong></td>
						<td width="50" align="center" valign="middle">&nbsp;</td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
   
    <?php
    $objProy = new BLProyecto();
    $iRs = $objProy->AmbitoGeo_Listado($idProy, $idVersion);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

    <?php
            if ($action == md5("ajax_edit") && $row['t03_dpto'] == $dpto && $row['t03_prov'] == $prov && $row['t03_dist'] == $dist) {
                ?>
      <tr class="RowSelected">
						<td width="50" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<!--img src="../../../img/aplicar.png" width="15" height="15" title="Guardar Ambito Geografico modificado" onclick="Guardar_AmbitoGeo();" /modificado-->
								<input value="Guardar" width="15" height="15"
								title="Guardar Ambito Geografico modificado"
								onclick="Guardar_AmbitoGeo();" class="btn_save" />
						</span></td>
						<td align="center" nowrap="nowrap"><input type="hidden"
							name="t03_dpto" id="t03_dpto"
							value="<?php echo($row['t03_dpto']);?>" /> <input type="hidden"
							name="t03_prov" id="t03_prov"
							value="<?php echo($row['t03_prov']);?>" /> <input type="hidden"
							name="t03_dist" id="t03_dist"
							value="<?php echo($row['t03_dist']);?>" /> <select name="cbodpto"
							id="cbodpto" style="width: 150px;" onchange="LoadProv();">
								<option value="" selected="selected"></option>
           <?php
                $objTablas = new BLTablasAux();
                $rsDpto = $objTablas->ListaDepartamentos();
                $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $row['t03_dpto']);
                ?>
         </select></td>
						<td align="center" nowrap="nowrap"><select name="cboprov"
							id="cboprov" style="width: 150px;" onchange="LoadDist();">
								<option value="" selected="selected"></option>
         <?php
                $rsDpto = $objTablas->ListaProvincias($row['t03_dpto']);
                $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $row['t03_prov']);
                ?>
       </select></td>
						<td align="center" nowrap="nowrap"><select name="cbodist"
							id="cbodist" style="width: 150px;">
								<option value="" selected="selected"></option>
         <?php
                $rsDpto = $objTablas->ListaDistritos($row['t03_dpto'], $row['t03_prov']);
                $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $row['t03_dist']);
                ?>
       </select></td>
						<td align="center" nowrap="nowrap"><textarea name="t03_obs"
								rows="2" id="t03_obs" style="padding: 0px; width: 100%;"><?php echo( $row['t03_obs']);?></textarea></td>
						<td width="50" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<img src="../../../img/closePopup.gif" width="16" height="16"
								title="Cancelar " onclick="LoadDataGrid('','');" />
						</span></td>
					</tr>
      <?php } else { ?>
     <tr>
						<td height="30" align="center" valign="middle" nowrap="nowrap"><img
							src="../../../img/pencil.gif" width="14" height="14"
							title="Modificar Registro" border="0"
							onclick="btnEditar_Clic('<?php echo($row['t03_dpto']);?>','<?php echo($row['t03_prov']);?>','<?php echo($row['t03_dist']);?>'); return false;"
							style="cursor: pointer;" /></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['dpto']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['prov']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['dist']);?></td>
						<td align="left" valign="middle"><?php echo( wordwrap( $row['t03_obs'],30, "\n", true ));?></td>
						<td height="30" align="center" valign="middle" nowrap="nowrap"><img
							src="../../../img/bt_elimina.gif" width="14" height="14"
							title="Eliminar Ambito Geografico" border="0"
							onclick="EliminarAmbitoGeo('<?php echo($row['t03_dpto']);?>','<?php echo($row['t03_prov']);?>','<?php echo($row['t03_dist']);?>');"
							style="cursor: pointer;" /></td>
					</tr>
     <?php } ?>
     
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>
    
      <?php if($action!=md5("ajax_edit"))  { ?>
       <tr>
						<td width="50" align="center" valign="middle"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<!--img src="../../../img/aplicar.png" width="20" height="20" title="Guardar Ambito Geografico " onclick="Guardar_AmbitoGeo();" /modificar-->
								<input value="Guardar" width="20" height="20"
								title="Guardar Ambito Geografico "
								onclick="Guardar_AmbitoGeo();" class="btn_save" />
						</span></td>
						<td align="center" nowrap="nowrap"><input type="hidden"
							name="t03_dpto" id="t03_dpto"
							value="<?php echo($row['t02_sector']);?>" /> <input type="hidden"
							name="t03_prov" id="t03_prov"
							value="<?php echo($row['t02_subsec']);?>" /> <select
							name="cbodpto" id="cbodpto" style="width: 150px;"
							onchange="LoadProv();">
								<option value="" selected="selected"></option>
           <?php
        $objTablas = new BLTablasAux();
        $rsDpto = $objTablas->ListaDepartamentos();
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $row['t03_dpto']);
        ?>
         </select></td>
						<td align="center" nowrap="nowrap"><select name="cboprov"
							id="cboprov" style="width: 150px;" onchange="LoadDist();">
								<option value="" selected="selected"></option>
						</select></td>
						<td align="center" nowrap="nowrap"><select name="cbodist"
							id="cbodist" style="width: 150px;">
								<option value="" selected="selected"></option>
						</select></td>
						<td align="center" nowrap="nowrap"><textarea name="t03_obs"
								rows="2" id="t03_obs" style="padding: 0px; width: 100%;"></textarea></td>
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
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">
function LoadProv()
{
	var BodyForm = "dpto=" + $('#cbodpto').val();
	var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
	$('#cboprov').html('<option> Cargando ... </option>');
	$('#cbodist').html('');
	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function ProvSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboprov').html(respuesta);
  $('#cboprov').focus();
}
function LoadDist()
{
	var BodyForm = "dpto=" + $('#cbodpto').val() + "&prov=" + $('#cboprov').val() ;
	var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function DistSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist').html(respuesta);
  $('#cbodist').focus();
}


function Guardar_AmbitoGeo()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 if($('#txtCodProy').val()==""){alert("Error: Seleccione Proyecto !!!"); return false;}
 
 var dpto = $('#cbodpto').val();
 var prov = $('#cboprov').val();
 var dist = $('#cbodist').val();
 
 if(dpto=='' || dpto==null){alert("Seleccione Departamento");return false;}
/* if(prov=='' || prov==null){alert("Seleccione Provincia");return false;}
 if(dist=='' || dist ==null){alert("Seleccione Distrito");return false;}*/
 
 var BodyForm=$('#FormData').serialize();

 <?php if($action==md5("ajax_edit")) {?>
 var sURL = "amb_geo_process.php?action=<?php echo(md5("ajax_edit"));?>";
 <?php } else {?>
 var sURL = "amb_geo_process.php?action=<?php echo(md5("ajax_new"));?>";
 <?php }?>
 
 var req = Spry.Utils.loadURL("POST", sURL, true, AmbitoGeoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

}
function AmbitoGeoSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadDataGrid('','');
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
function EliminarAmbitoGeo(dpto, prov, dist)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t02_version="+$('#cboversion').val()+"&t03_dpto="+dpto+"&t03_prov="+prov+"&t03_dist="+dist;
		var sURL = "amb_geo_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, AmbitoGeoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}

function btnEditar_Clic(dpto, prov, dist)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idProy=$('#txtCodProy').val();
	var idVersion=$('#cboversion').val();
	var url = "amb_geo_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&idVersion="+idVersion+"&dpto="+dpto+"&prov="+prov+"&dist="+dist;
	loadUrlSpry("divContentEdit",url);
	
	return;
  }

</script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>