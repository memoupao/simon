<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require (constant('PATH_CLASS') . "BLProyecto.class.php");
require (constant('PATH_CLASS') . "HardCode.class.php");

$objProy = new BLProyecto();
$HC = new HardCode();

error_reporting("E_PARSE");
$id = $objFunc->__POST('id');
$vs = $objFunc->__POST('vs');
$accion = $objFunc->__GET('accion');

if ($id == "" && $vs == "") {
    $id = $objFunc->__GET('id');
    $vs = $objFunc->__GET('vs');
}

$oculto = "1";
if ($oculto == "") {
    ?>

<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->

<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->

<?php
}
?>


     <div style="width: 750px; display: block;" align="right">
		<div class="TextDescripcion"
			style="float: left; text-align: left; color: #666;">
			<font style="color: #F00; font-weight: bold;"> A tener en
				consideración</font><br> Solo se pueden subir los archivos con
			extensiones pdf, word, excel, ppt, jpg, gif.<br> El tamaño máximo
			por archivo es de 2MB.
		</div>
		<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar Anexos"  onclick="LoadAnexos(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui-->
		<input type="button" value="Refrescar" title="Refrescar Anexos"
			onclick="LoadAnexos(true);" class="btn_save_custom" />
	</div>


	<div id="divTableLista" class="TableGrid">
		<table width="750" border="0" cellpadding="0" cellspacing="0">
			<thead>
			</thead>
			<tbody class="data" bgcolor="#FFFFFF">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td width="39" align="center" valign="middle">&nbsp;</td>
					<td width="192" align="center" valign="middle" nowrap="nowrap"><strong>Tipo
							de Anexo</strong></td>
					<td width="245" align="center" valign="middle" nowrap="nowrap"><strong>Nombre
							del Archivo</strong></td>
					<td width="272" height="23" align="center" valign="middle"><strong>Descripcion
							del Archivo</strong></td>
					<td width="39" align="center" valign="middle">&nbsp;</td>
				</tr>
            <?php
            $iRs = $objProy->ListaAnexos($id, $vs);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
            <tr>
               <?php
                    $urlFile = $row['t02_url_file'];
                    $filename = $row['t02_nom_file'];
                    $path = constant('APP_PATH') . "sme/proyectos/datos/anexos/";
                    $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
                    ?>
               <td align="center" valign="middle">&nbsp;</td>
					<td height="30" align="center" valign="middle">
               <?php echo( $row['t02_anx_tip_desc']);?>
               &nbsp;</td>
					<td height="30" align="center" valign="middle"><a
						href="<?php echo($href);?>" title="Descargar Archivo"
						target="_blank"><?php echo($row['t02_nom_file']);?></a></td>
					<td align="left" valign="middle"><?php echo( $row['t02_desc_file']);?></td>
					<td align="center" valign="middle">
						<!--?php if($HC->EVA == $ObjSession->PerfilID || $HC->Admin == $ObjSession->PerfilID )  { ? modificado 29/11/2011-->
               <?php
                    // -------------------------------------------------->
                    // DA 2.0 [02-11-2013 16:16]
                    // if($HC->Admin == $ObjSession->PerfilID || $HC->CMT == $ObjSession->PerfilID || $HC->CMF == $ObjSession->PerfilID || $HC->ADM == $ObjSession->PerfilID)
                    // --------------------------------------------------<
                    ?>
               <?php if($HC->SP == $ObjSession->PerfilID                
               || $HC->Ejec == $ObjSession->PerfilID
               || $HC->ADM == $ObjSession->PerfilID)  { ?>
               <a href="#">
                    <img src="../../../img/bt_elimina.gif" width="14" height="14" title="Eliminar Registro" border="0" onClick="EliminarAnexo('<?php echo($row['t02_cod_anx']);?>');" />
               </a>
               <?php } ?>
               </td>
				</tr>
             <?php
                    $RowIndex ++;
                }
                $iRs->free();
            } // Fin de Anexos
            ?>
           <!--?php if($HC->EVA == $ObjSession->PerfilID || $HC->Admin == $ObjSession->PerfilID )  { ? modificado 30/11/2011-->
           <?php
        // -------------------------------------------------->
        // DA 2.0 [02-11-2013 16:16]
        // if($HC->Admin == $ObjSession->PerfilID || $HC->CMT == $ObjSession->PerfilID || $HC->CMF == $ObjSession->PerfilID || $HC->ADM == $ObjSession->PerfilID)
        // --------------------------------------------------<
        ?>
           <?php if($HC->SP == $ObjSession->PerfilID 
           || $HC->Ejec == $ObjSession->PerfilID 
           || $HC->ADM == $ObjSession->PerfilID)  { ?>
            <tr>
					<td width="39" align="center" valign="middle"><span
						style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
							<!--input type="image" src="../../../img/aplicar.png" width="20" height="20" title="Guardar Anexo" onClick="Guardar_Anexo(); return false;" class="AnxProyectos"  /></span></td osktgui-->
							<input type="button" value="Guardar" width="20" height="20"
							title="Guardar Anexo" onClick="Guardar_Anexo(); return false;"
							class="AnxProyectos btn_save"
							style="cursor: pointer; font-weight: bold; font-size: 11px;" />
					</span></td>
					<td align="left" valign="middle"><select name="t02_anx_tip_desc"
						id="t02_anx_tip_desc" style="width: 200px" class="AnxProyectos">
							<option value=""></option>
                 <?php
            $rs = $objProy->ListaTipoAnexo();
            $objFunc->llenarComboI($rs, 'codigo', 'descripcion', $t02_anx_tip_desc);
            ?>
               </select></td>
					<td align="left" valign="middle"><input name="txtNomFile"
						type="file" id="txtNomFile" class="AnxProyectos" /></td>
					<td align="center" nowrap="nowrap"><textarea name="t02_desc_file"
							rows="2" id="t02_desc_file" style="padding: 0px; width: 100%;"
							class="AnxProyectos"><?php echo($row['t02_desc_file']);?></textarea></td>
					<td width="39" align="center" valign="middle">&nbsp;</td>
				</tr>
             <?php } ?>
            </tbody>
			<tfoot>
				<tr>
					<td colspan="5" align="center" valign="middle">&nbsp; <iframe
							id="ifrmUploadFile2" name="ifrmUploadFile2"
							style="display: none;"></iframe>
					</td>
				</tr>
			</tfoot>
		</table>

		<input type="hidden" name="t02_cod_proy" value="<?php echo($id);?>" />
		<input type="hidden" name="t02_cod_proyecto" id="t02_cod_proyecto"
			value="<?php echo($id);?>" /> <input type="hidden" name="t02_version"
			id="t02_version" value="<?php echo($vs);?>" />

		<script language="javascript" type="text/javascript">
function Guardar_Anexo()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var foto = $('#txtNomFile').val();
 var desc = $('#t02_desc_file').val();
 var tipanx = $('#t02_anx_tip_desc').val();
 
 if(tipanx=='' || tipanx==null){alert("Seleccione el Tipo de Anexo");return false;} 
 if(foto=='' || foto==null){alert("No ha seleccionado ningun Documento");return false;}
 if(desc=='' || desc==null){alert("Ingrese Descripcion del Documento");return false;}

 
 var f = document.getElementById("FormData");
 f.action="process.php?action=<?php echo(md5('ajax_anexos'));?>" ;
 f.target="ifrmUploadFile2" ;
 f.encoding="multipart/form-data";
 f.submit() ;
 f.target='_self';
}

function AnexoSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadAnexos();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
	

function EliminarAnexo(idanx)
{
	if(confirm(" Estas seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#t02_cod_proyecto').val()+"&t02_version="+$('#t02_version').val()+"&t02_cod_anx="+idanx;
		var sURL = "process.php?action=<?php echo(md5("ajax_del_anx"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, AnexoSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}

<?php if($accion==md5("ver")) { ?>
$(".AnxProyectos").attr('disabled','disabled');
<?php } ?>

</script>
	</div>
<?php if($oculto=="") { ?> 
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>