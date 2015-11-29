<?php
/**
 * CticServices
 *
 * Gestiona la Programación de los Entregables
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
$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');

if($action=="save")
{
	$ReturnPage = false;

    $objML = new BLMarcoLogico();
    $ReturnPage = $objML->programarEntregables();
    $objML = NULL;

    if ($ReturnPage) {
        $script = "alert('Se grabó correctamente el Registro'); \n";
        $script .= "parent.btnSuccess(); \n";
        $Function->Javascript($script);
        exit(1);
    }
}

$objML  = new BLMarcoLogico();
$r = $objML->listarEntregables($idProy, $idVersion);
$objFunc->SetSubTitle("Entregables -  Editar Registro");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Entregables</title>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
</head>

<body>
<script type="text/javascript">
    jQuery(document).ready(function(){
        $($('#ids').val()).attr('checked', true);
    });
</script>
<?php if($ObjSession->MaxVersionProy($idProy) > $idVersion) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
<form id="frmMain" name="frmMain" method="post" action="#">
 <div id="toolbar" class="Subtitle">
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
        <td colspan="6" align="left" valign="top">
        <div class="TableGrid">
            <input type="hidden" value="<?php echo($r['ids']);?>" id="ids" name="ids"/>
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
            $i = 0;
			while($r['duracion'] > $i){
			    $i++;
			    ?>
                 <tr class="RowData">
                    <td nowrap="nowrap" align="left"><input type="hidden" value="<?php echo($i);?>" id="anios[]" name="anios[]"/>Año <?php echo($i);?></td>
                    <?php
                        $j = 0;
                        while(MESES > $j){
                            $j++;
                            $name = "entregables[".$i."][".$j."]";
                    ?>
                            <td valign="middle" align="center"><input type="checkbox" id="<?php echo($name);?>" name="<?php echo($name);?>"/></td>
                 <?php } ?>
                 </tr>
			<?php } ?>
            </tbody>
          </table>
        </div>
        </td>
      </tr>
    </table>
    <input type="hidden" name="t02_cod_proy" value="<?php echo($idProy);?>"/>
    <input type="hidden" name="t02_version" value="<?php echo($idVersion);?>"/>
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
	  	 var formulario = document.getElementById("frmMain");
		 formulario.action = "cp_ent_prog.php?action=save";
		 return true;
	  }
   </script>
</body>
</html>