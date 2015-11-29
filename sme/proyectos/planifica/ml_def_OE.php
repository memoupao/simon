<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once ("../../../clases/BLMarcoLogico.class.php");
require_once ("../../../clases/Functions.class.php");

$Function = new Functions();
$objML = new BLMarcoLogico();

$idProy = $Function->__GET('idProy');
$idVersion = $Function->__GET('idVersion');
$modifDispon = $Function->__GET('modif');
$modificar = false;
if (md5("enable") == $modifDispon) {
    $modificar = true;
}
$rHip = $objML->ListadoDefinicionOE($idProy, $idVersion);
$row_rsDefinicion = mysql_fetch_assoc($rHip); // Recuperamos el primer registro

$objML = NULL; // Liberamos recursos

$urlEdicion = "ml_def_OE_edit.php?idProy=$idProy&idVersion=$idVersion";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetSubTitle("Definición de Componentes");
?>

<title>Objetivos Especificos</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEditableHeadTag -->

<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 545px;
	top: 0px;
	width: 182px;
	height: 56px;
	z-index: 0;
	visibility: visible;
}
-->
</style>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<?php if($ObjSession->MaxVersionProy($idProy) > 1) {$lsDisabled = 'disabled="disabled"' ; } else { $lsDisabled ='';} ?>
	<form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post"
		enctype="multipart/form-data" id="frmMain">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><button class="Button" onclick="return Nuevo();"
							value="Guardar" <?php if($modificar) echo "disabled"; ?>>Nuevo</button></td>
					<td width="11%"><button class="Button" value="Cancelar"
							onclick="return Refrescar();">Refrescar</button></td>

					<td align="center"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<br>
  <?php
$GridHeigth = 250;
$GridWidth = 700;
?>
            <table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="11">&nbsp;</td>
				<td width="731" colspan="3" rowspan="2">

					<div class="TableGrid">
						<div style="overflow:auto; width:<?php echo($GridWidth-2);?>px; height:<?php echo($GridHeigth);?>px; border: 1px solid #abb6ec;">
							<table width="<?php echo($GridWidth -20);?>" border="1"
								cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<th width="20" nowrap="nowrap" height="25">&nbsp;</th>
										<th width="60" nowrap="nowrap">Codigo</th>
										<th>Objetivo Especifico</th>
										<th width="20">&nbsp;</th>
									</tr>
								</thead>
								<tbody class="data">
                      <?php do { ?>
                        <tr class="RowData">
										<td width="20" nowrap="nowrap"><span>
						  <?php if($row_rsDefinicion['t08_cod_comp']>0 && !$modificar && !$lsDisabled ) { ?>
							<a href="#"><img src="../../../img/b_edit.png" width="14"
													height="14" title="Editar Registro" border="0"
													onclick="Editar('<?php echo $row_rsDefinicion['t08_cod_comp']; ?>');" /></a>
						  <?php } ?>
						  </span></td>
										<td width="50" nowrap="nowrap"><?php echo $row_rsDefinicion['t08_cod_comp']; ?></td>
										<td align="left"><?php echo $row_rsDefinicion['t08_comp_desc']; ?></td>
										<td width="20" align="left">
                          
                          <?php /*if($row_rsDefinicion['t08_cod_comp']>0 && !$modificar) { ?>
                            <a href="#"><img
												src="../../../img/b_drop.png" width="14" height="14"
												title="Eliminar Registro" border="0"
												onclick="Eliminar('<?php echo $row_rsDefinicion['t08_cod_comp']; ?>', '<?php echo($row_rsDefinicion['t08_comp_desc'] ); ?>');" /></a>													 
						  <?php } */ ?>
						  
                          </td>
									</tr>
                        <?php } while ($row_rsDefinicion = mysql_fetch_assoc($rHip)); ?>
                    </tbody>
							</table>
						</div>
						<table width="<?php echo($GridWidth);?>" border="0"
							cellpadding="0" cellspacing="0">
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
					</div>
				</td>
			</tr>
		</table>
	</form>

	<script language="javascript" type="text/javascript">
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
<!-- InstanceEnd -->
</html>
