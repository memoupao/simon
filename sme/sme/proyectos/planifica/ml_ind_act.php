<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php
	require_once ("../../../clases/BLMarcoLogico.class.php");
	require_once("../../../clases/Functions.class.php");

	$Function  = new Functions();
	$objML = new BLMarcoLogico();

	$idProy = $Function->__GET('idProy');
	$idVersion = $Function->__GET('idVersion');
	$modifDispon = $Function->__GET('modif');
	$modificar = (md5("enable")==$modifDispon);

	$idComp = $Function->__POST("t08_cod_comp") ;
	$idAct  = $Function->__POST("t09_cod_act") ;
	if($idComp==""){$idComp = $Function->__GET("t08_cod_comp") ; }
	if($idAct==""){$idAct = $Function->__GET("t09_cod_act") ; }

	$urlEdicion = "ml_ind_act_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp&idAct=$idAct";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
// -------------------------------------------------->
// AQ 2.0 [29-10-2013 14:38]
// Cambio de Actividades a Productos
    $objFunc->SetSubTitle("Indicadores de Producto");
?>
<title>Indicadores de Productos</title>
<!-- --------------------------------------------------< -->
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEditableHeadTag -->
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
  <!-- InstanceBeginEditable name="tBody" -->

  <form action="<?php echo($_SERVER['PHP_SELF']);?><?php echo("?idProy=".$idProy."&modif=$modifDispon&idVersion=".$idVersion);?>" method="post" enctype="multipart/form-data" id="frmMain">
  <div id="toolbar" style="height:4px;" class="Subtitle">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="5%"><button class="Button" onclick="return Nuevo();" value="Guardar" <?php if($modificar) echo "disabled"; ?>>Nuevo </button></td>
          <td width="11%"><button class="Button" value="Cancelar" onclick="return Refrescar();"> Refrescar</button></td>

          <td align="center" style="color:#00F;"><?php echo($objFunc->SubTitle) ;?></td>
        </tr>
    </table>
	</div>
<table width="742" height="45" border="0" cellpadding="0" cellspacing="0" class="TableEditReg">
	<tr>
      <td width="93" height="21" align="left"><span class="TextDescripcion"><strong>&nbsp; Componente</strong></span> </td>
      <td width="324" align="left">
	    <select name="t08_cod_comp" id="t08_cod_comp" style="width:520px;" onchange="ChangeLista(0);">
        <option value="" selected="selected"></option>
        <?php
			$rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);
			$Function->llenarComboSinItemsBlancos($rsOE,'t08_cod_comp','descripcion', $idComp,'',array(),'t08_comp_desc');
		?>
      </select>	  </td>
      <td width="325" align="left">
      &nbsp;
		<!--input type="image" name="btnRefresh" id="btnRefresh" src="../../../img/btnRecuperar.gif" /osktgui-->
		<input type="button" value="Refrescar" title="Refrescar" name="btnRefresh" id="btnRefresh" style="background-color:#EEEEEE; border:1px solid ##999999; cursor:pointer; font-weight:bolder; font-size:11px;" />

      </td>
   </tr>
	<tr>
        <!-- -------------------------------------------------- >
        // AQ 2.0 [29-10-2013 14:38]
        // Cambio de Actividades a Productos -->
        <td height="22" align="left"><span class="TextDescripcion"><strong>&nbsp; Producto</strong></span></td>
        <!-- -------------------------------------------------- < -->

	  <td align="left">
      <select name="t09_cod_act" id="t09_cod_act" style="width:530px;" onchange="ChangeLista(1);">
      <option value=""></option>
	    <?php
			$rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
			//$Function->llenarCombo($rsAct,'t09_cod_act','descripcion', $idAct);
			$Function->llenarComboSinItemsBlancos($rsAct,'t09_cod_act','descripcion', $idAct,'',array(),'t09_act');
		?>
	    </select></td>
	  <td align="left">&nbsp;</td>
      <?php
	  	$rInd = $objML->ListadoIndicadoresAct($idProy, $idVersion, $idComp, $idAct );
		$row_rsIndicador = mysql_fetch_assoc($rInd); //Recuperamos el primer registro
		$objML = NULL; //Liberamos recursos
	  ?>
    </tr>
	</table>
  <?php
  $GridHeigth=250;
  $GridWidth = 700;
  ?>
            <table  border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="11">&nbsp;</td>
                <td width="731" colspan="3" rowspan="2">

				<div class="TableGrid">
				<div style="overflow:auto; width:<?php echo($GridWidth-2);?>px; height:<?php echo($GridHeigth);?>px; border: 1px solid #abb6ec;">
                  <table width="<?php echo($GridWidth -20);?>" border="1" cellpadding="0" cellspacing="0" >
                  <thead>
				  <tr>
					<th width="20" nowrap="nowrap" height="25" >&nbsp;</th>
					<th width="180">Indicador </th>
					<th width="85">Unidad Medida</th>
					<th width="30">Meta</th>
					<th width="310">Fuentes de Verificación</th>
					<th width="20">&nbsp;</th>
				  </tr>
				</thead>

                    <tbody class="data" >
                      <?php do { ?>
                      <?php
                      		$nomIndicador = trim($row_rsIndicador['t09_ind']);
                      		//if (empty($nomIndicador)) continue;

                      ?>
                        <tr class="RowData">
                          <td width="20" nowrap="nowrap">
						  <span>
						  <?php if($row_rsIndicador['t09_cod_act_ind']>0 && !$modificar) { ?>
							<a href="#"><img src="../../../img/b_edit.png" width="14" height="14" title="Editar Registro" border="0" onclick="Editar('<?php echo $row_rsIndicador['t09_cod_act_ind']; ?>');" /></a>
						  <?php } ?>
						  </span>
						  </td>
                          <td width="180" align="left"><?php echo $nomIndicador; ?></td>
                          <td width="85"><?php echo $row_rsIndicador['t09_um']; ?></td>
                          <td width="30"><?php echo $row_rsIndicador['t09_mta']; ?></td>
                          <td align="left"><?php echo $row_rsIndicador['t09_fv']; ?></td>
                          <td width="20" align="left">
                          <?php /*if($row_rsIndicador['t09_cod_act_ind']>0 && !$modificar) { ?>
                            <a href="#"><img src="../../../img/b_drop.png" width="14" height="14" title="Eliminar Registro" border="0" onclick="Eliminar('<?php echo $row_rsIndicador['t09_cod_act_ind']; ?>', '<?php echo $row_rsIndicador['t09_ind']; ?>');" /></a>
						  <?php }*/ ?>


                          </td>
                      </tr>
                        <?php } while ($row_rsIndicador = mysql_fetch_assoc($rInd)); ?>
                    </tbody>
				  </table>
   </div>
					<table width="<?php echo($GridWidth);?>" border="1" cellpadding="0" cellspacing="0">
                    <tfoot>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tfoot>
                  </table>
				</div>				</td>
              </tr>
            </table>
  </form>

  <script language="javascript" type="text/javascript">
	  function ChangeLista(tipo)
		{
		 if(tipo==0){document.getElementById("t09_cod_act").value="";}

		 var formulario = document.getElementById("frmMain") ;
		 formulario.submit() ; return false;
		}

	  function Nuevo()
	  {
	     <?php $ObjSession->AuthorizedPage(); ?>
		 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "<?php echo($urlEdicion);?>&view=new&id=";
		 formulario.submit() ;
		 return false;
		 //OpenPopupDialog("Editar Unidad Organizativa", "organiza_edit.php?id=" + codigo, 500, 350, true);
	  }

	  function Editar(codigo)
	  {
		 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "<?php echo($urlEdicion);?>&view=edit&id=" + codigo;
		 formulario.submit() ;
		 //OpenPopupDialog("Editar Unidad Organizativa", "organiza_edit.php?id=" + codigo, 500, 350, true);
	  }

	  function Eliminar(codigo,Descripcion)
	  {
	    <?php $ObjSession->AuthorizedPage(); ?>
	  	if(confirm("Estas seguro de eliminar el Registro \n" + Descripcion))
		{
	  	 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "<?php echo($urlEdicion);?>&id=" + codigo + "&proc=<?php echo(md5("del"));?>";
		 formulario.submit() ;
		}
	  }

	  function Refrescar()
	  {
	  	window.location.reload();
		return false;
	  }
	  </script>


  <!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
