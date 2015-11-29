<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once ("../../../clases/Functions.class.php");
require_once ("../../../clases/BLMarcoLogico.class.php");
error_reporting("E_PARSE");
$Function  = new Functions();
$proc = $Function->__GET('proc');

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idOE');
$idAct  = $Function->__GET('idAct');
$idInd  = $Function->__GET('idInd');

$view = $Function->__GET('view');

$urlListado = "ml_ind_act.php?idProy=$idProy&idVersion=$idVersion&t08_cod_comp=$idComp&t09_cod_act=$idAct";

if($proc=="save")
{
	$ReturnPage = false;
	if($view=="edit")
	  {
	  	$objML = new BLMarcoLogico();
		$ReturnPage = $objML->ActualizarIndicadoresAct();
		$objML = NULL;
	  }
	if($view=="new")
	  {
		$objML = new BLMarcoLogico();
		$ReturnPage = $objML->NuevoindIcadoresAct();
		$objML = NULL;
	  }

	if($ReturnPage) {$Function->Redirect($urlListado);}

}

if($proc==md5("del"))
{

$objML = new BLMarcoLogico();
$id = $Function->__GET('id');
$ReturnPage = $objML->EliminarIndicadoresAct($idProy, $idVersion, $idComp, $idAct, $id);
$objML  = NULL;
$Function->Redirect($urlListado."&error=".$objML->Error);

}

$urlSave = "ml_ind_act_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp&idAct=$idAct";

$row=0;
if($view=="edit")
{
	$idInd = $Function->__GET('id');
	$objML  = new BLMarcoLogico();
	$row = $objML->GetIndicadoresAct($idProy, $idVersion, $idComp, $idAct, $idInd);
	$objML = NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<?php if($view=="new")
		{ $objFunc->SetSubTitle("Indicadores de Producto  -  Nuevo Registro"); }
      else { $objFunc->SetSubTitle("Indicadores de Producto -  Editar Registro"); }

?>
<title>Indicadores Objetivo Especifico</title>
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
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></SCRIPT>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
</head>

<body>
<?php if($ObjSession->MaxVersionProy($idProy) > $idVersion) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
   <script type="text/javascript">
   <!--
   		jQuery(document).ready(function(){
   			$('#t09_mta').numeric().pasteNumeric();
   			$('.mtasMensuales').numeric().pasteNumeric();
   		});
   // -->
   </script>

<form id="frmMain" name="frmMain" method="post" action="#">
 <div id="toolbar" style="height:4px;" class="Subtitle">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="6%"><button class="Button" onclick="return Guardar(); return false;" value="Guardar">Guardar </button></td>
          <td width="10%"><button class="Button" value="Cancelar" onclick="return Cancelar();"> Cancelar </button></td>

          <td align="center" style="color:#00F;"><?php echo($objFunc->SubTitle) ;?></td>
        </tr>
    </table>
  </div>
<table width="760" align="center" class="TableEditReg">
      <tr valign="baseline">
        <td align="left" nowrap class="TextDescripcion">Componente</td>
        <td colspan="5" align="left">
		<select name="t08_cod_comp" id="t08_cod_comp" style="width:500px;" disabled="disabled">
          <?php
		  	$objML  = new BLMarcoLogico();
			$rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);

			$idComp = $Function->__POST("t08_cod_comp") ;
			if($idComp==""){$idComp = $Function->__GET("t08_cod_comp") ; }
			$Function->llenarCombo($rsOE,'t08_cod_comp','descripcion', $idComp);
		?>
        </select>
		<input name="t08_cod_comp" type="hidden" id="t08_cod_comp" value="<?php echo($idComp);?>" /></td>
      </tr>

    <tr valign="baseline">
      <td nowrap align="left">Producto</td>
        <td colspan="5" align="left">
        <select name="t09_cod_act" id="t09_cod_act" style="width:500px;" <?php if($view=="edit") {echo("disabled");}?>  >
          <?php
			$rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
			$idAct = $Function->__POST("t09_cod_act") ;
			if($idAct==""){$idAct = $Function->__GET("t09_cod_act") ; }


			$Function->llenarCombo($rsAct,'t09_cod_act','descripcion', $idAct);
		?>
        </select>
        <?php if($view=="edit")
		{	echo("<input type='hidden' name='t09_cod_act' id='t09_cod_act' value='".$idAct."'>");  }
		?>
        </td>
    </tr>
    <tr valign="baseline">
        <td nowrap align="left">Indicador</td>
        <td colspan="5" align="left"><input name="t09_cod_act_ind" type="text" id="t09_cod_act_ind" value="<?php echo($row["t09_cod_act_ind"]);?>" size="2" maxlength="5" readonly="readonly" style="text-align:center" />          <input name="t09_ind" type="text" id="t09_ind" value="<?php echo($row["t09_ind"]);?>" size="85"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left">Unidad Medida</td>
        <td width="152" align="left"><input name="t09_um" type="text" id="t09_um" value="<?php echo($row["t09_um"]);?>" size="30"></td>
        <td width="28" align="left">Meta</td>
        <td width="52" align="left"><input name="t09_mta" type="text" id="t09_mta" value="<?php echo($row["t09_mta"]);?>" size="12" maxlength="8" /></td>
        <td width="63" align="left">&nbsp;</td>
        <td width="303" align="left">&nbsp;</td>
      </tr>

      <tr valign="baseline">
        <td nowrap align="left" valign="top">Fuentes Verificacion</td>
        <td colspan="5" align="left"><textarea name="t09_fv" cols="85" rows="3" id="t09_fv"><?php echo($row["t09_fv"]);?></textarea>        </td>
      </tr>

      <tr valign="baseline">
        <td nowrap align="left" valign="top">Observaciones</td>
        <td colspan="5" align="left"><textarea name="t09_obs" cols="85" rows="3" id="t09_obs"><?php echo($row["t09_obs"]);?></textarea>        </td>
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
		 return true;
	  }

	  function Guardar()
	  {
  	     <?php $ObjSession->AuthorizedPage(); ?>
	  	 var formulario = document.getElementById("frmMain") ;


 		 if($('#t09_cod_act').val()=="") {
		 	alert("Seleccione Producto");
			$('#t09_cod_act').focus();
		 	return false ;
		 }
<?php if ($view!="edit") {?>
		 if(formulario.t09_ind.value=="") {
		 	alert("Ingrese Nombre del Indicador");
			formulario.t09_ind.focus();
		 	return false ;
		 }

		 if ($('#t09_um').val().trim() == "") {
		 	alert("Ingrese Unidad de Medida");
		 	$('#t09_um').focus();
		 	return false;
		 }
<?php } ?>
		 formulario.action = "<?php echo($urlSave);?>&proc=save&view=<?php echo($view);?>";
		 return true ;
	  }

   </script>
</body>
</html>