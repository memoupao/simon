<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $idNum == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Anexos Informe SE</title>
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
				<td width="82%" align="left" class="TableEditReg">
				    <b>Anexos del informe de Supervisión</b></td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<input type="button" value="Refrescar" onclick="LoadAnexosSE(true);" class="btn_save_custom" />
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
						<td align="center" valign="middle" nowrap="nowrap"><b>Nombre del Archivo</b></td>
						<td width="481" height="23" align="center" valign="middle"><b>Descripcion del Archivo</b></td>
						<td width="52" align="center" valign="middle">&nbsp;</td>
					</tr>
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->listarAnexosInfSE($idProy, $anio, $idEntregable);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
    ?>
     <tr>
       <?php
            $urlFile = $row['t30_url_file'];
            $filename = $row['t30_nom_file'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_me";
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            ?>
       <td align="center" valign="middle">&nbsp;</td>
						<td height="30" align="center" valign="middle"><a
							href="<?php echo($href);?>" title="Descargar Archivo"
							target="_blank"><?php echo($row['t30_nom_file']);?></a></td>
						<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t30_desc_file']);?></td>
						<td align="center" valign="middle"><a
							href="javascript:EliminarAnexoFotografico('<?php echo($row['t30_cod_anx']);?>');"><img
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
								<input type="button" id='saveAnxBtn' value="Guardar"
								title="Guardar Anexo Fotografico" onclick="Guardar_Anexos();"
								class="btn_save" />
						</span></td>
						<td width="215" align="left" valign="middle"><input
							name="txtNomFile" type="file" id="txtNomFile" /></td>
						<td align="center" nowrap="nowrap"><textarea name="t30_desc_file"
								rows="2" id="t30_desc_file" style="padding: 0px; width: 100%;"><?php echo($row['t30_desc_file']);?></textarea></td>
						<td width="52" align="center" valign="middle">&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" align="center" valign="middle">&nbsp; <iframe
								id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
							<div id="divLoadingAnexo"
								style="width: 99%; background-color: #FFF;"></div>
						</td>
					</tr>

				</tfoot>
			</table>
			<input type="hidden" name="idProy" id="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
            <input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">

            $(document).ready(function(){
            	if ($('#pageMode').val() == 'view')
            		$('#saveAnxBtn').attr({disabled:'disabled'});
            });

            function Guardar_Anexos()
            {
                <?php $ObjSession->AuthorizedPage(); ?>
                var foto = $('#txtNomFile').val();
                var desc = $('#t30_desc_file').val();

                if(foto=='' || foto==null){alert("No ha seleccionado el Anexo a cargar");return false;}
                if(desc=='' || desc==null){alert("Ingrese Descripcion del archivo Anexado a Cargar");return false;}

                $("#divLoadingAnexo").html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");

                var f = document.getElementById("FormData");
                f.action="inf_monext_process.php?action=<?php echo(md5('ajax_save_anexos'));?>" ;
                f.target="ifrmUploadFile";
                f.encoding="multipart/form-data";
                f.submit() ;
                f.target='_self';
            }

            function AnexosSuccessCallback(req)
        	{
                $("#divLoadingAnexo").html("");
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);

                if(ret=="Exito")
                {
                    LoadAnexosSE(true);
                    alert(respuesta.replace(ret,""));
                }
                else
                {alert(respuesta);}
        	}

            function EliminarAnexoFotografico(idanx)
            {
            	if(confirm("¿Estás seguro de eliminar el Anexo del Informe de Supervisión ?"))
            	{
            		var BodyForm = "idProy="+$('#idProy').val()+"&anio="+$('#anio').val()+"&idEntregable="+$('#idEntregable').val()+"&idAnx="+idanx;
            		$("#divLoadingAnexo").html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
            		var sURL = "inf_monext_process.php?action=<?php echo(md5("ajax_del_anexos"))?>";
            		var req = Spry.Utils.loadURL("POST", sURL, true, AnexosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

            	}
            }
            </script>
		</div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>