<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>anexos</title>
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
					style="font-weight: bold;">Documentación Disponible</span></td>
				<td width="8%" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar Anexos"  onclick="LoadDocAdicional(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					class="btn_save_custom" title="Refrescar Anexos"
					onclick="LoadDocAdicional(true);" />

				</td>
				<td width="10%" align="right" valign="middle">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" style="font-size: 10px; color: #036;">
					Señalar con que documentación adicional al POA se cuenta
                    para efectos de enriquecer el POA y que sirvan de apoyo al proceso
                    de monitoreo y evaluación. <br> Los formatos permitidos que pueden
                    ser cargados son: pdf, doc, docx, xls, xlsx, ppt, pptx
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
    $objPOA = new BLPOA();
    $HC = new HardCode();

    $iRs = $objPOA->POA_ListaAnexos($idProy, $idAnio);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

     <tr>
       <?php

            $urlFile = $row['t02_url_file'];
            $filename = $row['t02_nom_file'];
            $path = constant('APP_PATH') . $HC->FolderUploadPOA;
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            ?>
       <td align="center" valign="middle"></td>
						<td height="30" align="center" valign="middle"><a
							href="<?php echo($href);?>" title="Descargar Archivo"
							target="_blank"><?php echo($row['t02_nom_file']);?></a></td>
						<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t02_desc_file']);?></td>
						<td align="center" valign="middle"><a href="#"><img
								src="../../../img/bt_elimina.gif" width="14" height="14"
								title="Eliminar Registro" border="0"
								onclick="EliminarInfAdicional('<?php echo($row['t02_cod_anx']);?>');" /></a></td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>
                    <tr>
						<td width="52" align="center" valign="middle">
						    <span style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
						        <?php
                                    $rowPOA = $objPOA->POA_Seleccionar($idProy, $idAnio);
                                    $disabledGuardar = "";
                                    if ($rowPOA['t02_estado'] == $HC->especTecAprobRA) {
                                        $disabledGuardar = " disabled";
                                    }
                                ?>
								<input type="button" value="Guardar" class="btn_save" title="Guardar Anexo Fotografico" onclick="Guardar_AnexoFotografico();" <?php echo($disabledGuardar);?>/>
						    </span>
						</td>
						<td width="215" align="left" valign="middle"><input
							name="txtNomFile" type="file" id="txtNomFile" class="Anexos" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t02_desc_file"
								rows="2" id="t02_desc_file" style="padding: 0px; width: 100%;"
								class="Anexos"><?php echo($row['t02_desc_file']);?></textarea></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" align="center" valign="middle">&nbsp; <iframe
								id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
						</td>
					</tr>

				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy" id="t02_cod_proy"
				value="<?php echo($idProy);?>" class="Anexos" /> <input
				type="hidden" name="t02_anio" id="t02_anio"
				value="<?php echo($idAnio);?>" class="Anexos" />
			<script language="javascript" type="text/javascript">
function Guardar_AnexoFotografico()
{
 <?php $ObjSession->AuthorizedPage(); ?>
 var foto = $('#txtNomFile').val();
 var desc = $('#t02_desc_file').val();

 if(foto=='' || foto==null){alert("No ha seleccionado el Archivo a cargar");return false;}

 var arFile = foto.split('.');
 var extFile = arFile[arFile.length-1];
 if ( ['pdf','doc','docx','xls','xlsx','ppt','pptx'].indexOf(extFile) < 0){
	 	alert('Verifique el nombre del archivo por favor, al parecer no tiene el nombre y/o extensión correcta');
	 	return false;
 }

 if(desc=='' || desc==null){alert("Ingrese la descripción del Archivo Cargado");return false;}

 var f = document.getElementById("FormData");
 f.action="poa_tec_process.php?action=<?php echo(md5('ajax_documen_adicional'));?>" ;
 f.target="ifrmUploadFile" ;
 f.encoding="multipart/form-data";
 f.submit() ;
 f.target='_self';


}
function DocumAdicionalSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadDocAdicional(true);
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}
	}
function EliminarInfAdicional(idanx)
{
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy=<?php echo($idProy);?>&t02_anio=<?php echo($idAnio);?>&t02_cod_anx="+idanx;

		var sURL = "poa_tec_process.php?action=<?php echo(md5("ajax_del_docum_adicional"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, DocumAdicionalSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

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