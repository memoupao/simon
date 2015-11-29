<?php 
include("../../../includes/constantes.inc.php"); 
include("../../../includes/validauser.inc.php"); 

require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');

if ($idProy == "" && $idAnio == "" && $idMes == "") {
    $idProy = $objFunc->__GET('idProy');
    $idAnio = $objFunc->__GET('idAnio');
    $idMes = $objFunc->__GET('idMes');
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

<title>Anexos</title>
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
					style="font-weight: bold;">Anexos del Informe Financiero</span></td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar Anexos"  onclick="LoadAnexos(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div --> <input type="button" value="Refrescar"
					title="Refrescar Anexos" onclick="LoadAnexos(true);"
					class="btn_save_custom" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<div class="TextDescripcion"
						style="float: left; text-align: left; color: #666;">
						<font style="color: #F00; font-weight: bold;"> A tener en
							consideración</font><br> Solo se pueden subir los archivos con
						extensiones pdf, word, excel, ppt, jpg, gif.<br> El tamaño
						máximo por archivo es de 2MB.
					</div>
				</td>
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
    $rowBn = $objInf->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);
    $iRs = $objInf->ListaAnexosInformeFinanc($idProy, $idAnio, $idMes);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

     <tr>
       <?php
            $urlFile = $row['t40_url_file'];
            $filename = $row['t40_nom_file'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_financ";
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            ?>
       <td align="center" valign="middle">&nbsp;</td>
						<td height="30" align="center" valign="middle"><a
							href="<?php echo($href);?>" title="Descargar Archivo"
							target="_blank"><?php echo($row['t40_nom_file']);?></a></td>
						<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t40_desc_file']);?></td>
						<td align="center" valign="middle"><a
							href="javascript:EliminarAnexos('<?php echo($row['t40_cod_anx']);?>');"><img
								src="../../../img/bt_elimina.gif" width="14" height="14"
								title="Eliminar Anexo" border="0" /></a></td>
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
								<!--img src="../../../img/aplicar.png" width="20" height="20" title="Guardar Anexo Fotografico" onclick="Guardar_Anexos();" /-->
								<input type="button" id="btnAnexo" value="Guardar"
								title="Guardar Anexo Fotografico" onclick="Guardar_Anexos();"
								class="btn_save" />
						</span></td>
						<td width="215" align="left" valign="middle"><input
							name="txtNomFile" type="file" id="txtNomFile" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t40_desc_file"
								rows="2" id="t40_desc_file" style="padding: 0px; width: 100%;"><?php echo($row['t40_desc_file']);?></textarea></td>
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
				value="<?php echo($idProy);?>" /> <input name="t40_anio"
				type="hidden" id="t40_anio" value="<?php echo($idAnio);?>" /> <input
				name="t40_mes" type="hidden" id="t40_mes"
				value="<?php echo($idMes);?>" />
			<script language="javascript" type="text/javascript">
function Guardar_Anexos()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var foto = $('#txtNomFile').val();
 var desc = $('#t40_desc_file').val();
 
 if(foto=='' || foto==null){alert("No ha seleccionado archivo a cargar");return false;}
 if(desc=='' || desc==null){alert("Ingrese Descripcion del archivo Cargada");return false;}
 
 
 var f = document.getElementById("FormData");
 f.action="inf_financ_process.php?action=<?php echo(md5('ajax_save_anexos'));?>" ;
 f.target="ifrmUploadFile" ;
 f.encoding="multipart/form-data";
 f.submit() ;
 f.target='_self';

}
function AnexosSuccessCallback	(req)
	{
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
function EliminarAnexos(idanx)
{
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t40_anio="+$('#t40_anio').val()+"&t40_mes="+$('#t40_mes').val()+"&t40_cod_anx="+idanx;

		var sURL = "inf_financ_process.php?action=<?php echo(md5("ajax_del_anexos"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, AnexosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}
<?php

$HardCode = new HardCode();
if ($ObjSession->PerfilID == $HardCode->Ejec && $rowBn['inf_fi_ter'] == 1) {
    ?>
			$("#btnAnexo").attr("disabled","disabled");
<?php } ?>
	
</script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>