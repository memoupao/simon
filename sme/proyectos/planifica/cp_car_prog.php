<?php
/**
 * CticServices
 *
 * Gestiona la Programación de las Características de
 * Indicadores de Productos
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

$Function  = new Functions();
$action = $Function->__GET('action');
$tipo = $Function->__GET('tipo');
$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$idComp = $Function->__GET('idComp');
$idAct  = $Function->__GET('idAct');
$idInd  = $Function->__GET('idInd');
$idCar  = $Function->__GET('idCar');

$view = $Function->__GET('view');

if($action=="save")
{
	$ReturnPage = false;
    $objML = new BLMarcoLogico();
    $nuevo = false;

    if($tipo=="nuevo")
    {
        $nuevo = true;
    }

    $ReturnPage = $objML->programarCaracteristica($nuevo);
    $objML = NULL;

    if ($ReturnPage) {
        $script = "alert('Se grabó correctamente la Característica'); \n";
        $script .= "parent.btnSuccess(); \n";
        $Function->Javascript($script);
        exit(1);
    }
}

if($action==md5("del"))
{
    $objML = new BLMarcoLogico();
    $ReturnPage = $objML->eliminarCaracteristica($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar);
    $objML = NULL;
    if ($ReturnPage) {
        $script = "alert('Se eliminó la Característica correctamente'); \n";
        $script .= "parent.btnSuccess(); \n";
        $Function->Javascript($script);
        exit(1);
    } else {
        $script = "alert(\"Error\"); \n";
        $Function->Javascript($script);
        exit(1);
    }
}

$objML  = new BLMarcoLogico();
$row = $objML->obtenerCaracteristica($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar);
if ($view =='new') {
	$title = "Características de Producto - Nuevo Registro";
} else {
	$title = "Características de Producto - Editar Registro";
}

$objFunc->SetSubTitle($title);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Características Objetivo Especifico</title>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
</head>

<body>
<?php if($ObjSession->MaxVersionProy($idProy) > 1 && ($view == 'edit')) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
<script type="text/javascript">
	jQuery(document).ready(function(){
	    $($('#ids').val()).attr('checked', true);
	});
</script>

<form id="frmMain" name="frmMain" method="post" action="#">
 <div id="toolbar" style="height:4px;" class="Subtitle">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="6%"><button class="Button" onclick="guardar()">Guardar </button></td>
          <td width="10%"><button class="Button" onclick="cancelar()"> Cancelar </button></td>
          <td align="center" style="color:#00F;"><?php echo($objFunc->SubTitle);?></td>
        </tr>
    </table>
  </div>
<table width="760" align="center" class="TableEditReg">
    <tr valign="baseline">
        <td nowrap align="left">Característica</td>
        <td colspan="5" align="left">
            <input name="t09_cod_act_ind_car" type="text" id="t09_cod_act_ind_car" value="<?php echo($row["t09_cod_act_ind_car"]);?>" size="2" maxlength="5" readonly="readonly" style="text-align:center" />
            <input name="t09_ind" type="text" id="t09_ind" value="<?php echo($row["t09_ind"]);?>" size="51" <?php if (!empty($lsDisabled)) echo 'readonly'; ?>/>
        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left" valign="top">Fuentes Verificación</td>
        <td colspan="5" align="left"><textarea name="t09_fv" cols="55" rows="3" id="t09_fv" <?php if (!empty($lsDisabled)) echo 'readonly'; ?>><?php echo($row["t09_fv"]);?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left" valign="top">Observaciones</td>
        <td colspan="5" align="left"><textarea name="t09_obs" cols="55" rows="3" id="t09_obs"><?php echo($row["t09_obs"]);?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td colspan="6" align="left" nowrap><strong>Registro de Metas Mensuales</strong></td>
      </tr>
      <tr valign="baseline">
        <td colspan="6" align="left" valign="top" nowrap>
        <div class="TableGrid">
          <table width="590" border="0" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <th width="5" height="24" align="center" valign="middle">Año</th>
                <?php
                    $i = 0;
                    while(MESES > $i){
                        $i++;
                ?>
                <th width="32" align="center" valign="middle" >Mes <?php echo($i);?></th>
                <?php } ?>
              </tr>
            </thead>
            <tbody class="data">
            <?php
			$r = $objML->listarCaracteristicasCtrl($idProy, $idVersion, $idComp, $idAct, $idInd, $idCar, false);
            $i = 0;
            ?>
            <input type="hidden" value="<?php echo($r['ids']);?>" id="ids" name="ids"/>
            <?php
			while($r['duracion'] > $i){
			    $i++;
			    ?>
                 <tr class="RowData">
                    <td nowrap="nowrap" align="left"><input type="hidden" value="<?php echo($i);?>" id="anios[]" name="anios[]">Año <?php echo($i);?></td>
                    <?php
                        $j = 0;
                        while(MESES > $j){
                            $j++;
                            $name = "ctrls[".$i."][".$j."]";
                    ?>
                            <td valign="middle" align="center"><input type="checkbox" id="<?php echo($name);?>" name="<?php echo($name);?>"/></td>
                 <?php } ?>
                 </tr>
			<?php } ?>
            </tbody>
          </table>
        </div></td>
      </tr>
  </table>
    <input type="hidden" name="t02_cod_proy" value="<?php echo($idProy);?>"/>
    <input type="hidden" name="t02_version" value="<?php echo($idVersion);?>"/>
    <input type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>"/>
    <input type="hidden" name="t09_cod_act" value="<?php echo($idAct);?>"/>
    <input type="hidden" name="t09_cod_act_ind" value="<?php echo($idInd);?>"/>
    <input type="hidden" name="t09_cod_act_ind_car" value="<?php echo($idCar);?>"/>
</form>

<script language="javascript" type="text/javascript">
	  function cancelar()
	  {
		  parent.btnCancel_Clic();
		  return false;
	  }

	  function guardar()
	  {
  	     <?php $ObjSession->AuthorizedPage(); ?>
	  	 var formulario = document.getElementById("frmMain") ;
	  	<?php if ($view!='edit') { ?>
		 if(formulario.t09_ind.value=="") {
		 	alert("Ingrese Nombre de la Característica");
			formulario.t09_ind.focus();
		 	return false;
		 }
		 <?php } ?>

		 var tipo = "nuevo";
		 if($('#t09_cod_act_ind_car').val() != ""){
			 tipo = "edicion";
		 }
		 formulario.action = "cp_car_prog.php?action=save&tipo="+tipo;
		 return true;
	  }
   </script>
</body>
</html>