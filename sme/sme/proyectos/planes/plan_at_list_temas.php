<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLPOA.class.php");

$objPOA = new BLPOA();

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idComp = $objFunc->__Request('idComp');
$idAct = $objFunc->__Request('idAct');
$idSub = $objFunc->__Request('idSub');
$idTema = $objFunc->__Request('idTema');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></script>
<SCRIPT src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></SCRIPT>

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
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="RowSelected">
					</tr>
				</tbody>
			</table>
			<table width="760" border="0" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="58" align="center" valign="middle">&nbsp;</td>
						<td width="436" height="28" align="center" valign="middle"><strong>Nombre
								del Taller</strong></td>
						<td width="132" align="center" valign="middle"><strong>N&ordm;
								Horas</strong></td>
						<td width="132" align="center" valign="middle"><strong>N&ordm;
								Beneficiarios</strong></td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
      <?php
    $iRs = $objPOA->PlanCapacitacion_ListadoTemas($idProy, $idVersion, $idComp, $idAct, $idSub, $idTema);
    
    $RowIndex = 0;
    $sumhoras = 0;
    $sumbenef = 0;
    $sumtemas = 0;
    
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $sumhoras += $row['t12_nro_hora'];
            $sumbenef += $row['t12_nro_bene'];
            $sumtemas ++;
            
            ?>
      <?php
            if ($action == md5("ajax_edit") && $row['t12_cod_tema'] == $idTema) {
                
                ?>
      <tr class="RowSelected">
						<td width="58" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<img src="../../../img/aplicar.png" width="16" height="16"
								title="Guardar Responsable" onclick="GuardarTema();" /> <img
								src="../../../img/closePopup.gif" width="16" height="16"
								title="Cancelar " onclick="LoadTemas();" />
						</span></td>
						<td align="center" nowrap="nowrap"><input type="hidden"
							name="t01_cod_rep" id="t01_cod_rep"
							value="<?php echo($t01_cod_rep);?>" /> <input name="txtcurso"
							type="text" id="txtcurso"
							value="<?php echo( $row['t12_tem_espe']); ?>" style="width: 99%;" /></td>
						<td><input name="txtnrohoras" type="text" id="txtnrohoras"
							value="<?php echo($row['t12_nro_hora']); ?>"
							style="width: 99%; text-align: center;" /></td>
						<td align="center" nowrap="nowrap"><input name="txtnrobenef"
							type="text" id="txtnrobenef"
							value="<?php echo($row['t12_nro_bene']); ?>"
							style="width: 99%; text-align: center;" /></td>
					</tr>
      <?php } else { ?>
      <tr>
						<td height="30" align="center" valign="middle" nowrap="nowrap"><img
							src="../../../img/pencil.gif" width="14" height="13"
							title="Modificar Registro" border="0"
							onclick="EditarTema('<?php echo($row['t12_cod_tema']);?>');"
							style="cursor: pointer;" /> <img
							src="../../../img/bt_elimina.gif" width="16" height="16"
							title="Eliminar Registro" border="0"
							onclick="EliminarTema('<?php echo($row['t12_cod_tema']);?>');"
							style="cursor: pointer;" /></td>
						<td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t12_tem_espe']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t12_nro_hora']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t12_nro_bene']);?></td>
					</tr>
      <?php } ?>
      <?php
            $RowIndex ++;
        }
        $iRs->free();
    }
    ?>
      <?php if($action!=md5("ajax_edit") && $idSub!='' )  { ?>
      <tr>
						<td width="58" align="center" valign="middle"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<img src="../../../img/aplicar.png" width="16" height="16"
								title="Guardar Tema " onclick="GuardarTema();" />
						</span></td>
						<td align="center" nowrap="nowrap"><input name="txtcurso"
							type="text" id="txtcurso" value="" style="width: 99%;" /></td>
						<td align="center" nowrap="nowrap"><input name="txtnrohoras"
							type="text" id="txtnrohoras" value=""
							style="width: 99%; text-align: center;" /></td>
						<td align="center" nowrap="nowrap"><input name="txtnrobenef"
							type="text" id="txtnrobenef" value=""
							style="width: 99%; text-align: center;" /></td>
					</tr>
      <?php } ?>
	  <?php
if ($idSub == '') {
    ?>
        <tr>
						<td colspan="4" align="left" valign="middle"><strong
							style="color: red;">Nota:</strong> Primero debe grabar los datos
							de Módulo y Contenidos</td>
					</tr>
        <?php
}
?>
    </tbody>
				<tfoot>
					<tr style="color: #FFF; font-weight: bold;">
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;Numero de Cursos: <?php echo( $sumtemas);?></td>
						<td align="center" valign="middle">&nbsp;<?php echo( $sumhoras);?></td>
						<td align="center" valign="middle">&nbsp;<?php echo( $sumbenef);?></td>
					</tr>
				</tfoot>
			</table>
			<script language="javascript" type="text/javascript">
function GuardarTema()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var curso = $('#txtcurso').val();
 var horas = $('#txtnrohoras').val();
 var benef = $('#txtnrobenef').val();
 
 if(curso=='' || curso==null){alert("Ingrese nombre del curso."); $('#txtcurso').focus(); return false;}
 if(horas=='' || horas==null){alert("Ingrese numero estimado de horas"); $('#txtnrohoras').focus(); return false;}
 if(benef=='' || benef==null){alert("Ingrese numero estimado de beneficiarios"); $('#txtnrobenef').focus(); return false;}
  
 var arrParams = new Array();
	arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
	arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
	arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
	arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
	arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
	arrParams[5] = "idTema=<?php echo($idTema); ?>" ;
	arrParams[6] = "txtcurso=" + curso ;
	arrParams[7] = "txtnrohoras=" + horas ;
	arrParams[8] = "txtnrobenef=" + benef ;

 var BodyForm = arrParams.join("&");

 <?php if($action==md5("ajax_edit")) {?>
 var sURL = "plan_cap_process.php?action=<?php echo(md5("ajax_tema_edit"));?>";
 <?php } else {?>
 var sURL = "plan_cap_process.php?action=<?php echo(md5("ajax_tema_new"));?>";
 <?php }?>
 
 var req = Spry.Utils.loadURL("POST", sURL, true, GuardarTemaSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });

}
function GuardarTemaSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadTemas();
		alert(respuesta.replace(ret,""));
	  }
	  else
	  {alert(respuesta);}  
	}
function EliminarTema(idTema)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm(" Estas seguro de eliminar el Registro seleccionado ["+idTema+"]?"))
	{
		var arrParams = new Array();
			arrParams[0] = "idProy=<?php echo($idProy); ?>" ; 
			arrParams[1] = "idVersion=<?php echo($idVersion); ?>" ;
			arrParams[2] = "idComp=<?php echo($idComp); ?>" ;
			arrParams[3] = "idAct=<?php echo($idAct); ?>" ;
			arrParams[4] = "idSub=<?php echo($idSub); ?>" ;
			arrParams[5] = "idTema=" + idTema ;
	
		 var BodyForm = arrParams.join("&");
		
		var sURL = "plan_cap_process.php?action=<?php echo(md5("ajax_tema_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, GuardarTemaSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad});

	}
}

function EditarTema(idTema)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	var BodyForm = "action=<?php echo(md5("ajax_edit"));?>&idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&idComp=<?php echo($idComp);?>&idAct=<?php echo($idAct);?>&idSub=<?php echo($idSub);?>&idTema=" + idTema; 
	var sURL = "plan_cap_list_temas.php";
	$('#divTemas').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessTemas, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
	

	return;
  }
  
   <?php if($idSub!='') { ?> 
	$("#txtnrohoras").numeric().pasteNumeric();
	$("#txtnrobenef").numeric().pasteNumeric();
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