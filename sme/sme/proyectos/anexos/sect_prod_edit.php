<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant('PATH_CLASS') . "BLTablas.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$sector = $objFunc->__Request('sector');
$subsec = $objFunc->__Request('subsec');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Proyectos - Sector Productivo</title>
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
			<table width="100%" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<thead>
					<tr>
						<td width="50" align="center" valign="middle">&nbsp;</td>
						<td width="177" height="23" align="center" valign="middle"><strong>Sector</strong></td>
						<td width="177" height="23" align="center" valign="middle"><strong>SubSector</strong></td>
						<td width="140" align="center" valign="middle"><strong>Producto Principal</strong></td>
						<td width="381" align="center" valign="middle"><strong>Producto Promovido</strong></td>
						<td width="50" align="center" valign="middle">&nbsp;</td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
   
    <?php
    $objProy = new BLProyecto();
    $iRs = $objProy->SectoresProductivos_Listado($idProy);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

    <?php
            if ($action == md5("ajax_edit") && $row['t02_sector'] == $sector && $row['t02_subsec'] == $subsec) {
                ?>
      <tr class="RowSelected">
						<td width="50" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<!--img src="../../../img/aplicar.png" width="15" height="15" title="Guardar Sector Productivo modificado" onclick="Guardar_SectorProductivo();" /modificado-->
								<input value="Guardar" class="btn_save" width="15" height="15"
								title="Guardar Sector Productivo modificado"
								onclick="Guardar_SectorProductivo();" />
					   </td>
					   <td>
					       <input type="hidden" name="t02_sector_main" id="t02_sector_main" value="<?php echo($row['t02_sector_main']);?>" />
                            <input type="hidden" name="t02_sector" id="t02_sector" value="<?php echo($row['t02_sector']);?>" /> 
                            <input type="hidden" name="t02_subsec" id="t02_subsec" value="<?php echo($row['t02_subsec']);?>" />
                            <select name="cbosectormain" id="cbosectormain" style="width: 180px;" onchange="LoadSubSectoresMain();">
								<option value="" selected="selected"></option>
                           <?php
                                $objTablas = new BLTablas();
                                $rsSectoresMain = $objTablas->getListaSectoresMain();
                                $objFunc->llenarCombo($rsSectoresMain, 'codigo', 'descripcion', $row['t02_sector_main']);
                           ?>
                           </select>
					       
					   </td>
						<td align="center" nowrap="nowrap">
						   
						  <select name="cbosector" id="cbosector" style="width: 180px;" onchange="LoadSubSectores();">
								<option value="" selected="selected"></option>
                           <?php
                                $objTablas = new BLTablasAux();
                                $rsSectores = $objTablas->SectoresProductivos($row['t02_sector_main']);
                                $objFunc->llenarCombo($rsSectores, 'codigo', 'descripcion', $row['t02_sector']);
                           ?>
                           </select>
                        </td>
						<td align="center" nowrap="nowrap">
						  <select name="cbosubsector" id="cbosubsector" style="width: 180px;">
								<option value="" selected="selected"></option>
                             <?php
                                $objTablas = new BLTablasAux();
                                $rsSectores = $objTablas->SubSectoresProductivos($row['t02_sector']);
                                $objFunc->llenarCombo($rsSectores, 'codigo', 'descripcion', $row['t02_subsec']);
                            ?>
                           </select>
                        </td>
						<td align="center" nowrap="nowrap"><textarea name="t02_obs"
								rows="2" id="t02_obs" style="padding: 0px; width: 100%;"><?php echo( $row['t02_obs']);?></textarea></td>
						<td width="50" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">

								<img src="../../../img/closePopup.gif" width="16" height="16"
								title="Cancelar " onclick="LoadDataGrid('');" />
						</span></td>

					</tr>
      <?php } else { ?>
     <tr>
						<td height="30" align="center" valign="middle" nowrap="nowrap">
                            <img src="../../../img/pencil.gif" width="14" height="14" title="Modificar Registro" border="0" onclick="btnEditar_Clic('<?php echo($row['t02_sector']);?>','<?php echo($row['t02_subsec']);?>'); return false;" style="cursor: pointer;" />
                        </td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['sector_main']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['sector']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['subsector']);?></td>
						<td align="left" valign="middle"><?php echo(  wordwrap( $row['t02_obs'],30, "\n", true ));?></td>
						<td height="30" align="center" valign="middle" nowrap="nowrap">
                            <img src="../../../img/bt_elimina.gif" width="14" height="14" title="Eliminar Sector Productivo" border="0" onclick="EliminarSectorProductivo('<?php echo($row['t02_sector']);?>','<?php echo($row['t02_subsec']);?>');" style="cursor: pointer;" />
                        </td>
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
								<!--img src="../../../img/aplicar.png" width="20" height="20" title="Guardar Sector Productivo " onclick="Guardar_SectorProductivo();" /></span modificado-->
								<input value="Guardar" width="20" height="20"
								title="Guardar Sector Productivo "
								onclick="Guardar_SectorProductivo();" class="btn_save" />
						</span>
						</td>
						<td>
						  <input type="hidden" name="t02_sector" id="t02_sector" value="<?php echo($row['t02_sector']);?>" /> 
						  <input type="hidden" name="t02_subsec" id="t02_subsec" value="<?php echo($row['t02_subsec']);?>" />
						  <select name="cbosectormain" id="cbosectormain" style="width: 180px;" onchange="LoadSubSectoresMain();">
								<option value="" selected="selected"></option>
                            <?php
                                $objTablas = new BLTablas();
                                $rsSectoresMain = $objTablas->getListaSectoresMain();
                                $objFunc->llenarCombo($rsSectoresMain, 'codigo', 'descripcion', '');
                                
                            ?>
                           </select>
						  
						</td>
						<td align="center" nowrap="nowrap">
						   
						  <select name="cbosector" id="cbosector" style="width: 180px;" onchange="LoadSubSectores();">
								<option value="" selected="selected"></option>
                            <?php
                                $objTablas = new BLTablasAux();
                                $rsSectores = $objTablas->SectoresProductivos();
                                $objFunc->llenarCombo($rsSectores, 'codigo', 'descripcion', '');
                                
                            ?>
                           </select>
                        </td>
						<td align="center" nowrap="nowrap"><select name="cbosubsector"
							id="cbosubsector" style="width: 180px;">
								<option value="" selected="selected"></option>
						</select></td>
						<td align="center" nowrap="nowrap"><textarea name="t02_obs"
								rows="2" id="t02_obs" style="padding: 0px; width: 100%;"></textarea></td>
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
function LoadSubSectoresMain()
{
	var BodyForm = "sector=" + $('#cbosectormain').val();
	var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_sector_main"))?>" ;
	$('#cbosector').html('<option> Cargando ... </option>');
	$('#cbosubsector').html('<option></option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, function(req){

		var respuesta = req.xhRequest.responseText;
		  $('#cbosector').html(respuesta);
		  $('#cbosector').focus();


		}, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	
}

function LoadSubSectores()
{
	var BodyForm = "sector=" + $('#cbosector').val();
	var sURL = "sect_prod_process.php?action=<?php echo(md5("lista_subsector"))?>" ;
	$('#cbosubsector').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, SubSectorSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function SubSectorSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbosubsector').html(respuesta);
  $('#cbosubsector').focus();
}

function Guardar_SectorProductivo()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 if($('#txtCodProy').val()==""){alert("Error: Seleccione Proyecto !!!"); return false;}

 var sectormain = $('#cbosectormain').val();
 var sector = $('#cbosector').val();
 var subsec = $('#cbosubsector').val();
 if( sectormain == '' || sectormain==null) { alert("Seleccione el Sector");return false; }
 if(sector=='' || sector==null){alert("Seleccione SubSector");return false;}
 if(subsec=='' || subsec==null){alert("Seleccione Producto Principal");return false;}
 
 var BodyForm=$('#FormData').serialize();
 
 <?php if($action==md5("ajax_edit")) {?>
 var sURL = "sect_prod_process.php?action=<?php echo(md5("ajax_edit"));?>";
 <?php } else {?>
 var sURL = "sect_prod_process.php?action=<?php echo(md5("ajax_new"));?>";
 <?php }?>
 
 var req = Spry.Utils.loadURL("POST", sURL, true, SectorProductivoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

}
function SectorProductivoSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadDataGrid('');
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
function EliminarSectorProductivo(sector, subsec)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t02_sector="+sector+"&t02_subsec="+subsec;
		var sURL = "sect_prod_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, SectorProductivoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}

function btnEditar_Clic(sector, subsec)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idProy=$('#txtCodProy').val();
	var url = "sect_prod_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&sector="+sector+"&subsec="+subsec;
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