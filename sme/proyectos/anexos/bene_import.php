<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require (constant('PATH_CLASS') . "BLTablas.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLBene.class.php");

$OjbTab = new BLTablasAux();
$view = $objFunc->__GET('mode');
$row = 0;

    $objFunc->SetSubTitle("Padrón de Beneficiarios / Importar datos");
    $t02_cod_proy = $objFunc->__GET('idProy');


?>


<div id="toolbar" style="height: 4px;" class="BackColor">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="12%"><button class="Button"
										onclick="btnImport_Clic(); return false;" type="button" value="Guardar">Importar datos
									</button></td>
								<td width="9%"><button class="Button"
										onclick="btnCancelar_Clic(); return false;"  type="button" value="Cerrar y Volver">
										Cerrar y Volver</button></td>
								<td width="31%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="2%">&nbsp;</td>
								<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
							</tr>
						</table>
					</div>
					<div id="EditForm"
						style="width: 100%; border: solid 1px #D3D3D3; font-weight: bold;">
						<br />
						<table width="100%" border="0" cellpadding="0" cellspacing="2"
							class="TableEditReg">
							<tr>
								<td>&nbsp;</td>
								<td>

      								<input type="hidden" name="t02_cod_proy" id="t02_cod_proy" value="<?php echo($t02_cod_proy); ?>" />

								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>

							</tr>


							<tr>
								<td>&nbsp;</td>
								<td>
								El archivo o plantilla de beneficiarios los puede obtener desde
								<a href="javascript:void(0);" onclick="bajarPlantilla();" title="El archivo o plantilla de beneficiarios los puede obtener desde AQUI">AQUI</a>
								Las columnas resaltadas de
								<span style="background-color: red; color:white;">color rojo son obligatorias</span>
								y las columnas de
								<span style="background-color: yellow;">color amarillo son opcionales.</span>
								</td>

							</tr>

							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>

							</tr>


							<tr>
								<td>&nbsp;</td>
								<td> Seleccione el archivo Excel que contiene los datos de beneficiarios:
									<input type="file" id="txtNomFile" name="txtNomFile">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<div style="float: left; text-align: left; color: #666;" class="TextDescripcion">
										<font style="color: #F00; font-weight: bold;"> A tener en
											consideración</font><br> Sólo se puede subir el archivo con
										extension excel (.xlsx).<br> El tamaño máximo
										por archivo es de 2MB.
									</div>
								</td>
							</tr>



							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>

							</tr>
						</table>

						<br />
					</div>
<iframe id="ifrmUploadFileImport" name="ifrmUploadFileImport" style="display: block; height: 250px; padding: 0px; margin: 0px; border: 0px none; width: 100%;"></iframe>

<script>
function bajarPlantilla()
{

	ifrmUploadFileImport.location.href='bene_process.php?action=<?php echo md5('generate_template_import_beneficiarios'); ?>&p='+$('#txtCodProy').val()+'&v='+$('#cboversion').val();
}


function btnImport_Clic()
{
	var archivo = $('#txtNomFile').val(), noms = $('#txtNomFile').val().split('.');
	if (archivo=='' || archivo==null) {
		alert("No ha seleccionado ningun Archivo");
		return false;
	}

	if (typeof(noms[1]) !='undefined') {
		if (noms[1] != "xlsx") {
			alert("El Archivo seleccionado no es correcto.");
			return false;
		}

	} else {
		alert("El Archivo seleccionado no es correcto.");
		return false;
	}



	var f = document.getElementById("FormData");
	f.action="bene_process.php?action=<?php echo md5('import_data_benef');?>" ;
	f.target="ifrmUploadFileImport" ;
	f.encoding="multipart/form-data";
	f.submit() ;
	f.target='_self';

}
</script>