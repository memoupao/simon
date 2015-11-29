<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLMonitoreoFinanciero.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idNum = $objFunc->__Request('idNum');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Anexos Informe Visita Monitor Financiero</title>
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
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" align="left" class="TableEditReg"><span
					style="font-weight: bold;">Anexos del informe de Visita del Monitor
						Financiero</span></td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Problemas y Soluciones"  onclick="LoadAnexos(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					class="btn_save_custom"
					title="Refrescar datos de Problemas y Soluciones"
					onclick="LoadAnexos(true);" />

				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">&nbsp;</td>
			</tr>
			<tr>
				<td><div class="TextDescripcion"
						style="float: left; text-align: left; color: #666;">
						<font style="color: #F00; font-weight: bold;"> A tener en
							consideración</font><br> Sólo se pueden subir los archivos con
						extensiones pdf, word, excel, ppt, jpg, gif.<br> El tamaño
						máximo por archivo es de 2MB.
					</div></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" border="0" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="52" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap"><strong>Nombre
								del Archivo</strong></td>
						<td width="481" height="23" align="center" valign="middle"><strong>Descripcion
								del Archivo</strong></td>
						<td width="52" align="center" valign="middle">&nbsp;</td>
					</tr>
    <?php
    $objInf = new BLMonitoreoFinanciero();
    $iRs = $objInf->Inf_visita_MF_ListaAnexos($idProy, $idNum);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

     <tr>
       <?php
            $urlFile = $row['t52_url_file'];
            $filename = $row['t52_nom_file'];
            $path = constant('APP_PATH') . "sme/monitoreofe/informes/inf_visita_mf/";
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            ?>
       <td align="center" valign="middle">&nbsp;</td>
						<td height="30" align="center" valign="middle"><a
							href="<?php echo($href);?>" title="Descargar Archivo"
							target="_blank"><?php echo($row['t52_nom_file']);?></a></td>
						<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t52_desc_file']);?></td>
						<td align="center" valign="middle"><a
							href="javascript:EliminarAnexoFotografico('<?php echo($row['t52_cod_anx']);?>');"><img
								src="../../../img/bt_elimina.gif" width="14" height="14"
								title="Eliminar Registro" border="0" /></a></td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>
    <tr>
						<td width="52" align="center" valign="middle"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<!--img src="../../../img/aplicar.png" width="20" height="20" title="Guardar Anexo Fotografico" onclick="Guardar_Anexos();" / osktgui-->
								<input type="button" value="Guardar" class="btn_save"
								title="Guardar Anexo Fotografico" onclick="Guardar_Anexos();" />
						</span></td>
						<td width="215" align="left" valign="middle"><input
							name="txtNomFile" type="file" id="txtNomFile" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t52_desc_file"
								rows="2" id="t52_desc_file" style="padding: 0px; width: 100%;"><?php echo($row['t52_desc_file']);?></textarea></td>
						<td width="52" align="center" valign="middle"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center" valign="middle">&nbsp; <iframe
								id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
							<div id="divLoadingAnexo"
								style="width: 99%; background-color: #FFF;"></div>
						</td>
					</tr>

				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">
function Guardar_Anexos()
{
 <?php $ObjSession->AuthorizedPage(); ?>
 var foto = $('#txtNomFile').val();
 var desc = $('#t52_desc_file').val();

 if(foto=='' || foto==null){alert("No ha seleccionado el Anexo a cargar");return false;}
 if(desc=='' || desc==null){alert("Ingrese Descripcion del archivo Anexado a Cargar");return false;}

 $("#divLoadingAnexo").html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");

 var f = document.getElementById("FormData");
 f.action="inf_visita_financ_process.php?action=<?php echo(md5('ajax_save_anexos'));?>" ;
 f.target="ifrmUploadFile" ;
 f.encoding="multipart/form-data";
 f.submit() ;
 f.target='_self';

}
function AnexosSuccessCallback	(req)
	{
	  $("#divLoadingAnexo").html("");
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAnexos(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}
function EliminarAnexoFotografico(idanx)
{
	if(confirm("¿Estás seguro de eliminar el Anexo del Informe de Monitoreo ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t52_id=<?php echo($idNum); ?>&t52_cod_anx="+idanx;
		$("#divLoadingAnexo").html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
		var sURL = "inf_visita_financ_process.php?action=<?php echo(md5("ajax_del_anexos"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, AnexosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}
</script>
			<input name="t52_id" type="hidden" id="t52_id"
				value="<?php echo($idNum);?>" />
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>