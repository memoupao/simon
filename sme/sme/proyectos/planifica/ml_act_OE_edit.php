<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once ("../../../clases/Functions.class.php");
require_once ("../../../clases/BLMarcoLogico.class.php");
error_reporting("E_PARSE");
$Function  = new Functions();
$proc = $Function->__GET('proc');

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idComp');

$view = $Function->__GET('view');

$urlListado = "ml_act_OE.php?idProy=$idProy&idVersion=$idVersion&t08_cod_comp=$idComp";


if($proc=="save")
{
	//--> Hacemos el Insert o Update
	$ReturnPage = false;
	if($view=="edit")
	  {
	  	$objML = new BLMarcoLogico();
		$ReturnPage = $objML->ActualizarActividadesOE();
		$objML = NULL;
	  }
	if($view=="new")
	  {
	  	$objML = new BLMarcoLogico();
		$ReturnPage = $objML->NuevoActividadesOE();
		$objML = NULL;
	  }

	if($ReturnPage) {$Function->Redirect($urlListado);}

}

if($proc==md5("del"))
{

$objML = new BLMarcoLogico();
$id = $Function->__GET('id');
$ReturnPage = $objML->EliminarActividadesOE($idProy , $idVersion, $idComp , $id);
$objML  = NULL;
$Function->Redirect($urlListado."&error=".$objML->Error);

}

$urlSave = "ml_act_OE_edit.php?idProy=$idProy&idVersion=$idVersion&idComp=$idComp";

?>



<?php
$row=0;
if($view=="edit")
{
	$idIndicador = $Function->__GET('id');
	$objML  = new BLMarcoLogico();
	$row = $objML->GetActividadesOE($idProy, $idVersion, $idComp, $idIndicador);
	$objML = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<!--  <link href="../../css/calendar.css" rel="stylesheet" type="text/css" media="all" />
<script language="javascript" type="text/javascript" src="../../js/calendar.js"></script>
-->
<?php if($view=="new")
		{ $objFunc->SetSubTitle("Productos -  Nuevo Registro"); }
      else { $objFunc->SetSubTitle("Productos -  Editar Registro"); }
?>
<title>Productos Objetivo Especifico</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:613px;
	top:0px;
	width:134px;
	height:55px;
	z-index:0;
	visibility: visible;
}
-->
</style>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
  <!-- InstanceBeginEditable name="tBody" -->
<?php if($ObjSession->MaxVersionProy($idProy) > 1) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
<form id="frmMain" name="frmMain" method="post" action="#">
  <div id="toolbar" style="height:4px;" class="Subtitle">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="7%"><button class="Button" onclick="return Guardar(); return false;" value="Guardar">Guardar </button></td>
          <td width="9%"><button class="Button" value="Cancelar" onclick="return Cancelar();"> Cancelar </button></td>
          <td align="center" style="color:#00F;"><?php echo($objFunc->SubTitle) ;?></td>
        </tr>
    </table>
</div>
    <br />
<table width="760" align="center" class="TableEditReg">
      <tr valign="baseline">
        <td height="34" align="left" nowrap class="TextDescripcion">Componente </td>
        <td width="617" align="left">
		<select name="t08_cod_comp" id="t08_cod_comp" style="width:465px;" <?php if($view=="edit") {echo("disabled");}?>  >
          <option value="" selected="selected"></option>
          <?php
		  	$objML  = new BLMarcoLogico();
			$rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);

			$idComp = $Function->__POST("t08_cod_comp") ;
			if($idComp==""){$idComp = $Function->__GET("t08_cod_comp") ; }
			$Function->llenarCombo($rsOE,'t08_cod_comp','descripcion', $idComp);
		?>
        </select>
		<?php if($view=="edit")
		{	echo("<input type='hidden' name='t08_cod_comp' id='t08_cod_comp' value='".$idComp."'>");  }
		?>		</td>
      </tr>
      <tr valign="baseline">
        <td width="115" align="left" nowrap>Codigo</td>
        <td align="left">
		<input name="t09_cod_act" id="t09_cod_act" type="text" class="Centrado" value="<?php echo($row["t09_cod_act"]);?>" size="2" maxlength="4" readonly></td>
      </tr>
      <tr valign="baseline">
        <td height="30" align="left" nowrap>Producto</td>
        <td align="left"><input name="t09_act" type="text" id="t09_act" value="<?php echo($row["t09_act"]);?>" style="width:465px;" <?php if($view=="edit") { if (!empty($lsDisabled)) echo 'readonly'; }?> /></td>
      </tr>


      <tr valign="baseline">
        <td height="128" align="left" valign="middle" nowrap>Observaciones</td>
        <td align="left"><textarea name="t09_obs" style="width:465px;" rows="7" id="t09_obs"><?php echo($row["t09_obs"]);?></textarea>        </td>
      </tr>

      <tr valign="baseline">
        <td nowrap align="left">Fecha registro:</td>
        <td align="left"><input type="text" name="fch_crea" value="<?php echo($row["fch_crea"]);?>" size="25" disabled/>          Usuario: <input type="text" name="usr_crea" value="<?php echo($row["usr_crea"]);?>" size="30" disabled/> </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left">&nbsp;</td>
        <td></td>
      </tr>
  </table>
  <input type="hidden" name="t02_cod_proy" value="<?php echo($idProy);?>">
    <input type="hidden" name="t02_version" value="<?php echo($idVersion);?>">
</form>

<script language="javascript" type="text/javascript">
	  function Cancelar()
	  {
		 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "<?php echo($urlListado);?>"
		 //formulario.submit() ;
		 return true;
	  }

	  function Guardar()
	  {
	  	 <?php $ObjSession->AuthorizedPage(); ?>
	  	 var formulario = document.getElementById("frmMain") ;
	  	 <?php if($view!="edit") { ?>
 		 if(formulario.t09_act.value=="")
		 {
		 	alert("Ingrese Producto");
			formulario.t09_act.focus();
		 	return false ;
		 }
 	    <?php } ?>
		 formulario.action = "<?php echo($urlSave);?>&proc=save&view=<?php echo($view);?>";
		 //formulario.submit() ;
		 return true ;
	  }

   </script>

<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
