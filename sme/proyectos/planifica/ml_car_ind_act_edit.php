<?php
/**
 * CticServices
 *
 * Gestiona edición de las Características de
 * Indicadores a los Productos
 *
 * @package     sme
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once ("../../../clases/Functions.class.php");
require_once ("../../../clases/BLMarcoLogico.class.php");

include(APP_PATH."lib/FirePHPCore/FirePHP.class.php");
$fb = FirePHP::getInstance(true);

//error_reporting("E_PARSE");
error_reporting("E_ALL");
$Function  = new Functions();
$proc = $Function->__GET('proc');
$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idOE');
$idAct  = $Function->__GET('idAct');
$idCar  = $Function->__GET('idCar');
$idInd  = $Function->__GET('idInd');
$view = $Function->__GET('view');
$urlListado = "ml_car_ind_act.php?idProy=$idProy&idVersion=$idVersion&t08_cod_comp=$idComp&t09_cod_act=$idAct&t09_cod_act_ind=$idInd";
$urlSave = "ml_car_ind_act_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp&idAct=$idAct&idInd=$idInd";

if($proc=="save")
{
    $fb->log('Guardando');

	$ReturnPage = false;

	$fb->log($view, 'Vista');

	if($view=="edit")
    {
        $fb->log($_POST, 'Post');
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->actualizarCaracteristica();
        $fb->log($ReturnPage, 'ReturnPage');
        $objML = NULL;
    }
	if($view=="new")
    {
        $objML = new BLMarcoLogico();
        $ReturnPage = $objML->registrarCaracteristica();
        $objML = NULL;
    }
	if($ReturnPage) {$Function->Redirect($urlListado);}
}

if($proc==md5("del"))
{
    $objML = new BLMarcoLogico();
    $id = $Function->__GET('id');
    $ReturnPage = $objML->eliminarCaracteristica($idProy, $idVersion, $idComp, $idAct, $idInd, $id);
    $objML  = NULL;
    $Function->Redirect($urlListado."&error=".$objML->Error);
}

$row=0;
if($view=="edit")
{
	$idCar = $Function->__GET('id');
	$objML  = new BLMarcoLogico();
	$row = $objML->obtenerCaracteristica($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar);
	$objML = NULL;
}

if($view=="new") { $objFunc->SetSubTitle("Características de Producto - Nuevo Registro"); }
else { $objFunc->SetSubTitle("Características de Producto - Editar Registro"); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Características Objetivo Especifico</title>
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
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
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
   		    $($('#ids').val()).attr('checked', true);
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
        <td colspan="5" align="left">
            <select name="t09_cod_act_ind" id="t09_cod_act_ind" style="width:530px;">
                <option value=""></option>
                <?php
                	$rsInd = $objML->ListadoIndicadoresAct($idProy, $idVersion, $idComp, $idAct);
                	$idInd = $Function->__POST("t09_cod_act_ind") ;
                	if($idInd==""){$idInd = $Function->__GET("t09_cod_act_ind") ; }
                	$Function->llenarCombo($rsInd,'t09_cod_act_ind', 'descripcion', $idInd);
                ?>
            </select>
        <?php if($view=="edit")
		{	echo("<input type='hidden' name='t09_cod_act_ind' id='t09_cod_act_ind' value='".$idInd."'>");  }
		?>
        </td>
    </tr>
    <tr valign="baseline">
        <td nowrap align="left">Característica</td>
        <td colspan="5" align="left">
            <input name="t09_cod_act_ind_car" type="text" id="t09_cod_act_ind_car" value="<?php echo($row["t09_cod_act_ind_car"]);?>" size="2" maxlength="5" readonly="readonly" style="text-align:center" />
            <input name="t09_ind" type="text" id="t09_ind" value="<?php echo($row["t09_ind"]);?>" size="85">
        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left" valign="top">Fuentes Verificación</td>
        <td colspan="5" align="left"><textarea name="t09_fv" cols="85" rows="3" id="t09_fv"><?php echo($row["t09_fv"]);?></textarea>        </td>
      </tr>

      <tr valign="baseline">
        <td nowrap align="left" valign="top">Observaciones</td>
        <td colspan="5" align="left"><textarea name="t09_obs" cols="85" rows="3" id="t09_obs"><?php echo($row["t09_obs"]);?></textarea>        </td>
      </tr>
<!--       <tr valign="baseline"> -->
<!--         <td colspan="6" align="left" nowrap><strong>Registro de Metas Mensuales</strong></td> -->
<!--       </tr> -->
<!--       <tr valign="baseline"> -->
<!--         <td colspan="6" align="left" valign="top" nowrap> -->
<!--         <div class="TableGrid"> -->
<!--           <table width="590" border="0" cellpadding="0" cellspacing="0"> -->
<!--             <thead> -->
<!--               <tr> -->
<!--                 <th width="5" height="24" align="center" valign="middle">Año</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 1</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 2</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 3</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 4</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 5</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 6</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 7</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 8</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 9</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 10</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 11</th> -->
<!--                 <th width="32" align="center" valign="middle" >Mes 12</th> -->
<!--               </tr> -->
<!--             </thead> -->
<!--             <tbody class="data" bgcolor="#FFFFFF"> -->
            <?php
// 			$r = $objML->listarCaracteristicasCtrl($idProy, $idVersion, $idComp, $idAct, $idCar);
//             $i = 0;
//             ?>

            <!-- <input type="hidden" value="<?php echo($r['ids']);?>" id="ids" name="ids"/> -->

            <?php
// 			while($r['duracion'] > $i){
// 			    $i++;
// 			    ?>
<!--                 <tr class="RowData">
                    <td nowrap="nowrap" align="left"><input type="hidden" value="<?php echo($i);?>" id="anios[]" name="anios[]">Año <?php echo($i);?></td>-->
                    <?php
//                         $j = 0;
//                         while(MESES > $j){
//                             $j++;
//                             $name = "ctrls[".$i."][".$j."]";
//                     ?>
                            <!--<td valign="middle" align="center"><input type="checkbox" id="<?php echo($name);?>" name="<?php echo($name);?>"/></td>-->
                 <?php //} ?>
<!--                 </tr> -->
			<?php //} /*if($rsMetas){
// 			 $rsMetas->free();}*/    ?>
<!--             </tbody> -->
<!--             <tfoot> -->
<!--               <tr> -->
<!--                 <th >&nbsp;</th> -->
<!--                 <th colspan="12" align="right" >&nbsp;</th> -->
<!--               </tr> -->
<!--             </tfoot> -->
<!--           </table> -->
<!--         </div></td> -->
<!--       </tr> -->
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
		 	return false ;
		 }

 		if($('#t09_cod_act_ind').val()=="") {
		 	alert("Seleccione Indicador");			
		 	return false ;
		 }
		 
<?php if ($view!="edit") {?>
		 if(formulario.t09_ind.value=="") {
		 	alert("Ingrese Nombre de la Característica");
			formulario.t09_ind.focus();
		 	return false ;
		 }
<?php } ?>
		 formulario.action = "<?php echo($urlSave);?>&proc=save&view=<?php echo($view);?>";
		 return true ;
	  }
   </script>
</body>
</html>