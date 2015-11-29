<?php
/**
 * CticServices
 *
 * Gestiona la Programación de los Indicadores de Productos
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

	$ReturnPage = $objML->programarIndicador($nuevo);
	$objML = NULL;

    if ($ReturnPage) {
        $script = "alert('Se grabó correctamente el Indicador'); \n";
        $script .= "parent.btnSuccess(); \n";
        $Function->Javascript($script);
        exit(1);
    }
}

if($action==md5("del"))
{
    $objML = new BLMarcoLogico();
    $ReturnPage = $objML->EliminarIndicadoresAct($idProy, $idVersion, $idComp, $idAct, $idInd);
    $objML = NULL;
    if ($ReturnPage) {
        $script = "alert('Se eliminó el Indicador correctamente'); \n";
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
$row = $objML->GetIndicadoresAct($idProy, $idVersion, $idComp, $idAct, $idInd);
$objFunc->SetSubTitle("Programando Indicador de Producto");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Indicadores Objetivo Especifico</title>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
</head>

<body>
<?php if($ObjSession->MaxVersionProy($idProy) > 1 && $view=='edit') {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
<form id="frmMain" name="frmMain" method="post" action="#">
 <div id="toolbar" style="height:4px;" class="Subtitle">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="6%"><button class="Button" onclick="guardar()">Guardar </button></td>
          <td width="10%"><button class="Button" onclick="cancelar()"> Cancelar </button></td>
          <td align="center" style="color:#00F;"><?php echo($objFunc->SubTitle) ;?></td>
        </tr>
    </table>
  </div>
<table width="760" align="center" class="TableEditReg">
    <tr valign="baseline">
        <td nowrap align="left">Indicador</td>
        <td colspan="5" align="left">
            <input name="t09_cod_act_ind" type="text" id="t09_cod_act_ind" value="<?php echo($row["t09_cod_act_ind"]);?>" size="2" maxlength="5" readonly="readonly" style="text-align:center" />
            <input name="t09_ind" type="text" id="t09_ind" value="<?php echo($row["t09_ind"]);?>" size="51" <?php if (!empty($lsDisabled)) echo 'readonly'; ?>/></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left">Unidad Medida</td>
        <td width="152" align="left"><input name="t09_um" type="text" id="t09_um" value="<?php echo($row["t09_um"]);?>" size="20" <?php if (!empty($lsDisabled)) echo 'readonly'; ?>/></td>
        <td width="28" align="left">Meta</td>
        <td width="52" align="left"><input name="t09_mta" type="text" id="t09_mta" value="<?php echo($row["t09_mta"]);?>" size="12" maxlength="8" <?php if (!empty($lsDisabled)) echo 'readonly'; ?> /></td>
        <td width="63" align="left">&nbsp;</td>
        <td width="303" align="left">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left" valign="top">Fuentes Verificacion</td>
        <td colspan="5" align="left"><textarea name="t09_fv" cols="55" rows="3" id="t09_fv" <?php if (!empty($lsDisabled)) echo 'readonly'; ?>><?php echo($row["t09_fv"]);?></textarea>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="left" valign="top">Observaciones</td>
        <td colspan="5" align="left"><textarea name="t09_obs" cols="55" rows="3" id="t09_obs"><?php echo($row["t09_obs"]);?></textarea>        </td>
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
                    $j = 0;
                    while(MESES > $j){
                        $j++;
                ?>
                    <th width="32" align="center" valign="middle" >Mes <?php echo($j);?></th>
                 <?php } ?>
              </tr>
            </thead>
            <tbody class="data">
            <?php

			$rsMetas = $objML->ListadoIndicadoresActMta($idProy, $idVersion, $idComp, $idAct, $idInd);
			while($rowMeta= mysqli_fetch_assoc($rsMetas))
			{
 			?>
               <tr class="RowData">
                <td align="left" nowrap="nowrap"><input name="t09_anios[]" type="hidden" id="t09_anios[]" value="<?php echo($rowMeta['t02_anio']);?>" />
                Año <?php echo($rowMeta['t02_anio']); ?></td>
                <?php
                    $j = 0;
                    while(MESES > $j){
                        $j++;
                        $idMeta = "metas[".$rowMeta['t02_anio']."][".$j."]";
                ?>
                    <td align="center" valign="middle"><input name="<?php echo($idMeta);?>" type="text" id="<?php echo($idMeta);?>" value="<?php echo($rowMeta["t09_mes".$j]);?>" size="2" maxlength="8" style="text-align:center" class="mtasMensuales" /></td>
                 <?php } ?>
             </tr>
             <?php }
			 if($rsMetas){
			 $rsMetas->free();}
 			 ?>
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

 		 if(formulario.t09_cod_act.value=="") {
		 	alert("Seleccione Producto");
			formulario.t09_cod_act.focus();
		 	return false ;
		 }
 		<?php if ($view!='edit') { ?>
		 if(formulario.t09_ind.value=="") {
		 	alert("Ingrese Nombre del Indicador");
			formulario.t09_ind.focus();
		 	return false ;
		 }
		 <?php } ?>
		 if ($('#t09_um').val().trim() == "") {
		 	alert("Ingrese Unidad de Medida");
		 	$('#t09_um').focus();
		 	return false;
		 }

		 var tipo = "nuevo";
		 if($('#t09_cod_act_ind').val() != ""){
			 tipo = "edicion";
		 }

		 formulario.action = "cp_ind_prog.php?action=save&tipo="+tipo;
		 return true ;
	  }

   </script>

<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
