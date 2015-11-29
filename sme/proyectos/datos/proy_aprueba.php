<?php include("../../../includes/constantes.inc.php"); ?>

<?php include("../../../includes/validauser.inc.php"); ?>



<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");

require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");

require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$OjbTab = new BLTablasAux();

$action = $objFunc->__Request('action');

$idProy = $objFunc->__Request('idProy');

$ml = $objFunc->__Request('ml');

$cron = $objFunc->__Request('cron');

$pre = $objFunc->__Request('pre');

// modificado 28/11/2011
$dg = $objFunc->__Request('dg');

$objProy = new BLProyecto();

$rproy = $objProy->ProyectoSeleccionar($idProy, $idVer);

$nom_proy = $rproy['t02_cod_proy'] . " - " . $rproy['t01_sig_inst'];

$objFunc->SetSubTitle("Aprobación del Proyecto");

if ($ml == '1') {
    $objFunc->SetSubTitle("Aprobación Marco Lógico");
}

if ($cron == '1') {
    $objFunc->SetSubTitle("Aprobación Cronograma de Actividades");
}

if ($pre == '1') {
    $objFunc->SetSubTitle("Aprobación Presupuesto");
}

// modificado 28/11/2011
if ($dg == '1') {
    $objFunc->SetSubTitle("Aprobación del Documento");
}
;

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



<?php
}

?>

  <div id="toolbar" style="height: 4px;" class="BackColor">

		<table width="100%" border="0" cellpadding="0" cellspacing="0">

			<tr>

				<!--td width="25%"><button class="Button" onclick="closePopup(); return false;" value="Volver y Cerrar" style="white-space:nowrap;"> Volver y Cerrar</button></td modificado 28/11/2011-->
				<td width="25%"><button class="Button"
						onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
						value="Volver y Cerrar" style="white-space: nowrap;">Cerrar</button></td>

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
					class="contenttext"><strong>&iquest; Estas Seguro de Enviar a Aprobación el Documento de Datos Generales del Proyecto &quot;<?php echo($idProy); ?>&quot; ?

    </strong></span><br /> Al enviar a Aprobación no se permitirá
					modificar posteriormente cualquier elemento del Dumento.
			</em></td>

		</tr>

		<tr>

			<td height="17"><strong>Mensaje al Gestor de Proyectos</strong></td>

		</tr>

		<tr>

			<td><textarea name="txtmensaje" cols="" rows="6" class="Aprueba"
					id="txtmensaje" style="width: 99%"><?php echo($row['beneficiario']); ?></textarea></td>

		</tr>

		<tr>
			<!--modificado 28/11/2011-->
			<td height="58" align="right" id="toolbar"><input type="hidden"
				name="txttipoaprueba" id="txttipoaprueba" value="dg" class="Aprueba" />
				<button class="Button" onclick="GuardarAprobacion(); return false;"
					value="Guardar" style="white-space: nowrap; color: black;">Enviar</button></td>

		</tr>

	</table>

	<script language="javascript" type="text/javascript">



function GuardarAprobacion()

{

 <?php $ObjSession->AuthorizedPage(); ?>	

 

 var BodyForm="idProy=<?php echo($idProy);?>&" + $('#FormData .Aprueba').serialize();
//modificado 28/11/2011
 var sURL = "proy_aprueba_process.php?action=<?php echo(md5("guardar_aprob_dg"));?>";

   

 var req = Spry.Utils.loadURL("POST", sURL, true, AprobacionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });



}

function AprobacionSuccessCallback	(req)

{

  var respuesta = req.xhRequest.responseText;

  respuesta = respuesta.replace(/^\s*|\s*$/g,"");

  var ret = respuesta.substring(0,5);

  if(ret=="Exito")
  {


	alert(respuesta.replace(ret,""));

	closePopup();

	ChangeVersion(1);

  }

  else
  {

	alert(respuesta);
	spryPopupDialog01.displayPopupDialog(false); return false;
  }  

  

}



function MyErrorCall(req)

{	  
	alert("ERROR: " + req.responseText);  
}



</script>

 

<?php if($objFunc->__QueryString()==NULL){?>

<!-- InstanceEndEditable -->

</body>

<!-- InstanceEnd -->
</html>

<?php } ?>