<?php
/**
 * CticServices
 *
 * Permite registrar Anexos al Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $anio == "" && $idEntregable == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Anexos</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form action="#" method="post" enctype="multipart/form-data" name="frmMain" id="frmMain">
<?php
}
?>
        <table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">
				    <b>Anexos</b></td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<input type="button" value="Refrescar" onclick="LoadAnexos(true);" class="btn_save_custom" />
				</td>
				<td width="10%" rowspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td>
				    <div class="TextDescripcion nota">
						A tener en consideración<br/>
						Sólo se pueden subir los archivos con extensiones pdf, word, excel, ppt, jpg, gif.<br/>
						El tamaño máximo por archivo es de 2MB.
					</div>
				</td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" border="0" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="52" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap"><b>Nombre del Archivo</b></td>
						<td width="481" height="23" align="center" valign="middle"><b>Descripción del Archivo</b></td>
						<td width="52" align="center" valign="middle">&nbsp;</td>
					</tr>
                        <?php
                        $objInf = new BLInformes();
                        $iRs = $objInf->listarAnexosInfEntregable($idProy, $idVersion, $anio, $idEntregable);
                        $RowIndex = 0;
                        if ($iRs->num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($iRs)) {
                        ?>
                    <tr>
                        <?php
                            $urlFile = $row['t25_url_file'];
                            $filename = $row['t25_nom_file'];
                            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_entregable";
                            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
                        ?>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td height="30" align="center" valign="middle">
                            <a href="<?php echo($href);?>" title="Descargar Archivo" target="_blank"><?php echo($row['t25_nom_file']);?></a>
                        </td>
                        <td align="left" valign="middle"><?php echo( $row['t25_desc_file']);?></td>
                        <td align="center" valign="middle">
                            <a href="#">
                                <img src="../../../img/bt_elimina.gif" width="14" height="14" border="0" onclick="EliminarAnexo('<?php echo($row['t25_cod_anx']);?>');" />
                    		</a>
                		</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }
    ?>
                    <tr>
						<td width="52" align="center" valign="middle">
						    <span style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<input type="button" value="Guardar" onclick="Guardar_Anexo();" class="btn_save" />
						    </span>
						</td>
						<td width="215" align="left" valign="middle">
						    <input name="txtNomFile" type="file" id="txtNomFile" />
					    </td>
						<td align="center" nowrap="nowrap">
						    <textarea name="t25_desc_file" rows="2" id="t25_desc_file" style="padding: 0px; width: 100%;"><?php echo($row['t25_desc_file']);?></textarea>
					    </td>
						<td width="52" align="center" valign="middle">&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center" valign="middle">&nbsp;
						    <iframe id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
						</td>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" id="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
			<input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />
			<script language="javascript" type="text/javascript">
                function Guardar_Anexo()
                {
                    <?php $ObjSession->AuthorizedPage(); ?>
                    var foto = $('#txtNomFile').val();
                    var desc = $('#t25_desc_file').val();

                    if(foto=='' || foto==null){alert("No ha seleccionado el archivo a cargar");return false;}
                    if(desc=='' || desc==null){alert("Ingrese la descripción del Anexo a cargar");return false;}

                    var f = document.getElementById("FormData");
                    f.action="inf_entregable_process.php?action=<?php echo(md5('ajax_anexos'));?>";
                    f.target="ifrmUploadFile" ;
                    f.encoding="multipart/form-data";
                    f.submit() ;
                    f.target='_self';
                }

                function AnexoSuccessCallback(req)
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

                function EliminarAnexo(idanx)
                {
                	if(confirm("¿Estás seguro de eliminar el Registro seleccionado?"))
                	{
                		var BodyForm = "idProy="+$('#txtCodProy').val()+"&anio="+$('#anio').val()+"&idEntregable="+$('#idEntregable').val()+"&idVersion="+$('#idVersion').val()+"&idAnexo="+idanx;
                		var sURL = "inf_entregable_process.php?action=<?php echo(md5("ajax_del_anx"))?>";
                		var req = Spry.Utils.loadURL("POST", sURL, true, AnexoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
                	}
                }
            </script>
        </div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>