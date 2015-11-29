<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<?php
$objFunc->SetSubTitle("Proposito del Proyecto");
?>

<title>Definicion OG</title>
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 466px;
	top: 0px;
	width: 92px;
	height: 51px;
	z-index: 1;
}
-->
</style>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
<?php
require_once ("../../../clases/BLMarcoLogico.class.php");
require_once ("../../../clases/Functions.class.php");
$Function = new Functions();
$objML = new BLMarcoLogico();

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$modifDispon = $Function->__GET('modif');
$iframe = $objFunc->__GET('iframe');
$modificar = false;
if (md5("enable") == $modifDispon) {
    $modificar = true;
}
$view = $Function->__GET('view');

if ($view == "edit") {
    $Definicion = $Function->__POST("txtObjetivo");
    
    $objML->ActualizarOG_def($idProy, $idVersion, $Definicion);
}

$rowML = $objML->GetML($idProy, $idVersion);
?>

<?php if($ObjSession->MaxVersionProy($idProy) > $idVersion) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>

  <form id="frmMain" name="frmMain" method="post" action="#">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="7%"><button class="Button"
							onclick="return Guardar(); return false;" value="Guardar"
							<?php echo($lsDisabled); if($modificar) echo " disabled";?>>Guardar
						</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return Refrescar();">Refrescar</button></td>
					<td width="84%" align="center" style="color: #00F;"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<table width="100%" height="304" border="0" cellpadding="3"
			cellspacing="0" class="TableEditReg">
			<tr>
				<td width="2%" height="27">&nbsp;</td>
				<td width="9%">&nbsp;</td>
				<td>&nbsp;</td>
				<td width="26%" rowspan="2">&nbsp;</td>
				<td width="15%">&nbsp;</td>
			</tr>
			<tr style="display: none;">
				<td height="25">&nbsp;</td>
				<td align="left">Codigo</td>
				<td width="48%" align="left"><input name="txtcodigo" type="text"
					id="txtcodigo" value="<?php echo($rowML["t02_version"]);?>"
					size="5" readonly="readonly" /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td height="112">&nbsp;</td>
				<td align="left" valign="top">Proposito</td>
				<td colspan="2" align="left"><textarea name="txtObjetivo"
						id="txtObjetivo" cols="110" rows="12"><?php echo($rowML["t02_pro"]);?></textarea></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td height="22">&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td height="28">&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>

	</form>

	<script language="javascript" type="text/javascript">
	function Guardar()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>	
	  	 var formulario = document.getElementById("frmMain") ;
 		 if(formulario.txtObjetivo.value=="")
		 {
		 	alert("Ingrese Descripcion del objetivo"); 
			formulario.txtObjetivo.focus();
		 	return false ;
		 }
		 
		 formulario.action = "<?php echo($_SERVER['PHP_SELF']);?><?php echo($Function->__QueryString());?><?php echo("&view=edit");?>"; 
		 return true ;
	  }
	  
	  function Refrescar()
	  {
	  	  // window.location.reload();
	  	  parent.LoadInherit("<?php echo $iframe; ?>");
		  return false ;
	  }
	  
	</script>
</body>
</html>