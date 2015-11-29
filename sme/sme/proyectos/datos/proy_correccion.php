<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php


require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");
$OjbTab = new BLTablasAux();
$action = $objFunc->__Request('action');
$idProy = $objFunc->__Request('idProy');
$tSolicitud = "";
$abrev = "";
$ml = $objFunc->__Request('ml');
$cron = $objFunc->__Request('cron');
$cronprod = $objFunc->__Request('cronprod');
$pre = $objFunc->__Request('pre');
$proy = $objFunc->__Request('proy');
$vbpr = $objFunc->__Request('vbpr');

// DA 2.0 [28-11-2013 09:21]
// La variable $idVer deberia de ser la version del proyecto no esta definida
// y se utiliza (mas abajo) como parametro en la funcion ProyectoSeleccionar
// Por el momento si no esta definido optamos por darle el valor de 1 (version 1)
if (!isset($idVer)) {
    $idVer = 1;
}
// -------------------------------------------------->

$objProy = new BLProyecto();
$rproy = $objProy->ProyectoSeleccionar($idProy, $idVer);
$nom_proy = $rproy['t02_cod_proy'] . " - " . $rproy['t01_sig_inst'];
$objFunc->SetSubTitle("Proyecto " . $rproy['t02_cod_proy']);
if ($ml == '1') {
    $objFunc->SetSubTitle("Corrección Marco Lógico");
    $tSolicitud = "Marco Lógico del Proyecto";
    $abrev = "ml";
}
if ($cron == '1') {
    $objFunc->SetSubTitle("Corrección Cronograma de Actividades");
    $tSolicitud = "Cronograma de Actividades del Proyecto";
    $abrev = "cron";
}
if ($cronprod == '1') {
    $objFunc->SetSubTitle("Corrección Cronograma de Productos");
    $tSolicitud = "Cronograma de Productos del Proyecto";
    $abrev = "cronprod";
}
if ($pre == '1') {
    $objFunc->SetSubTitle("Corrección Presupuesto");
    $tSolicitud = "Presupuesto del Proyecto";
    $abrev = "pre";
}
if ($proy == '1') {
    $objFunc->SetSubTitle("Corrección Datos Generales");
    $tSolicitud = "Proyecto";
    $abrev = "proy";
}
if ($vbpr == '1') {
    $objFunc->SetSubTitle("Corrección Datos Generales");
    $tSolicitud = "Proyecto";
    $abrev = "vbpr";
}
// $action = md5("solicita")
// $action = md5("aprueba")

if ($objFunc->__QueryString() == '') {
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
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>
<body>
	<!-- InstanceBeginEditable name="tBody" -->
<?php }?>  
<div id="toolbar" style="height: 4px;" class="BackColor">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="25%"><button class="Button"
						onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
						value="Volver y Cerrar" style="white-space: nowrap;">Volver y
						Cerrar</button></td>
				<td width="14%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="50%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
			</tr>
		</table>
	</div>
	<table width="558" border="0" cellspacing="1" cellpadding="0"
		class="TableEditReg">
		<tr>
			<td height="79" align="center" valign="middle"><em> <span
					class="contenttext"><strong>&iquest; Estas Seguro de Enviar a Corrección <?php echo($tSolicitud); ?> &quot;<?php echo($idProy); ?>&quot; ?    </strong></span><br />
					Al enviar a Corrección se le notificara a la Institución
					Ejecutora.
			</em></td>
		</tr>
		<tr>
			<td height="17"><strong>Mensaje a Institución Ejecutora</strong></td>
		</tr>
		<tr>
			<td><textarea name="txtmensaje" cols="" rows="6" class="correcion"
					id="txtmensaje" style="width: 99%"><?php echo($row['beneficiario']); ?></textarea></td>
		</tr>
		<tr>
			<td height="58" align="right" id="toolbar"><input type="hidden"
				name="txttipoaprueba" id="txttipoaprueba"
				value="<?php echo($abrev); ?>" class="correcion" />
				<button class="Button" onclick="GuardarAprobacion(); return false;"
					value="Guardar" style="white-space: nowrap; color: black;">Enviar</button></td>
		</tr>
	</table>
<script language="javascript" type="text/javascript">
function GuardarAprobacion() { 
    <?php $ObjSession->AuthorizedPage(); ?>
    var BodyForm="idProy=<?php echo($idProy);?>&" + $('#FormData .correcion').serialize(); 
    var sURL = "../datos/proy_correccion_process.php?action=<?php echo(md5("sol_correccion_".$abrev));?>"; 
    var req = Spry.Utils.loadURL("POST", sURL, true, AprobacionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
}

function AprobacionSuccessCallback(req){  
	var respuesta = req.xhRequest.responseText;  respuesta = respuesta.replace(/^\s*|\s*$/g,"");  
	var ret = respuesta.substring(0,5);  
	if(ret=="Exito")  {		
		alert(respuesta.replace(ret,""));	
		spryPopupDialog01.displayPopupDialog(false);
		 // btnEditar_Clic('C-08-001','1', '<?php echo('ver');?>');			
        btnGuardarMsg(); 
        return false;		
        ChangeVersion(1); 
     }  else  {
         alert(respuesta);
     }    
}

	 
 
 function MyErrorCall(req){	  alert("ERROR: " + req.responseText);   }
 
 </script> <?php if($objFunc->__QueryString()==NULL){?>
 <!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html><?php } ?>