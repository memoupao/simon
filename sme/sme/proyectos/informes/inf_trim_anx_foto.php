<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t25_ver_inf');
$idAnio = $objFunc->__POST('idAnio');
$idTrim = $objFunc->__POST('idTrim');

if ($idProy == "" && $idAnio == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t25_ver_inf');
    $idAnio = $objFunc->__GET('idAnio');
    $idTrim = $objFunc->__GET('idTrim');
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

<title>Anexos Fotograficos</title>
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
				<td width="82%" class="TableEditReg"><span
					style="font-weight: bold;">Anexos en el trimestre</span></td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar Anexos"  onclick="LoadAnexosFotograficos(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					title="Refrescar Anexos" onclick="LoadAnexosFotograficos(true);"
					class="btn_save_custom" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">&nbsp;</td>
			</tr>
			<tr>
				<td><div class="TextDescripcion"
						style="float: left; text-align: left; color: #666;">
						<font style="color: #F00; font-weight: bold;"> A tener en
							consideración</font><br> Solo se pueden subir los archivos con
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
    $objInf = new BLInformes();
    $iRs = $objInf->ListaAnexosFotograficosTrim($idProy, $idAnio, $idTrim, $idVersion);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

     <tr>
       <?php
            $urlFile = $row['t25_url_file'];
            $filename = $row['t25_nom_file'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_trim";
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            ?>
       <td align="center" valign="middle">&nbsp;</td>
						<td height="30" align="center" valign="middle"><a
							href="<?php echo($href);?>" title="Descargar Archivo"
							target="_blank"><?php echo($row['t25_nom_file']);?></a></td>
						<td align="left" valign="middle"><?php echo( $row['t25_desc_file']);?></td>
						<td align="center" valign="middle"><a href="#"><img
								src="../../../img/bt_elimina.gif" width="14" height="14"
								title="Eliminar Registro" border="0"
								onclick="EliminarAnexoFotografico('<?php echo($row['t25_cod_anx']);?>');" /></a></td>
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
								<!--img src="../../../img/aplicar.png" width="20" height="20" title="Guardar Anexo Fotografico" onclick="Guardar_AnexoFotografico();" / osktgui-->
								<input type="button" value="Guardar"
								title="Guardar Anexo Fotografico"
								onclick="Guardar_AnexoFotografico();" class="btn_save" />
						</span></td>
						<td width="215" align="left" valign="middle"><input
							name="txtNomFile" type="file" id="txtNomFile" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t25_desc_file"
								rows="2" id="t25_desc_file" style="padding: 0px; width: 100%;"><?php echo($row['t25_desc_file']);?></textarea></td>
						<td width="52" align="center" valign="middle">&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center" valign="middle">&nbsp; <iframe
								id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
						</td>
					</tr>

				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input name="t02_ver_inf"
				type="hidden" id="t02_ver_inf" value="<?php echo($idVersion);?>" />
			<input name="t25_anio" type="hidden" id="t25_anio"
				value="<?php echo($idAnio);?>" /> <input name="t25_trim"
				type="hidden" id="t25_trim" value="<?php echo($idTrim);?>" />
			<script language="javascript" type="text/javascript">
function Guardar_AnexoFotografico()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var foto = $('#txtNomFile').val();
 var desc = $('#t25_desc_file').val();
 
 if(foto=='' || foto==null){alert("No ha seleccionado la Foto a cargar");return false;}
 if(desc=='' || desc==null){alert("Ingrese Descripcion de la Foto Cargada");return false;}
 
 
 var f = document.getElementById("FormData");
 f.action="inf_trim_process.php?action=<?php echo(md5('ajax_anexos_fotograficos'));?>" ;
 f.target="ifrmUploadFile" ;
 f.encoding="multipart/form-data";
 f.submit() ;
 f.target='_self';

}
function AnexoFotograficoSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAnexosFotograficos(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
function EliminarAnexoFotografico(idanx)
{
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t25_anio="+$('#t25_anio').val()+"&t25_trim="+$('#t25_trim').val()+"&t02_ver_inf="+$('#t02_ver_inf').val()+"&t25_cod_anx="+idanx;

		var sURL = "inf_trim_process.php?action=<?php echo(md5("ajax_del_anx_foto"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, AnexoFotograficoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
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