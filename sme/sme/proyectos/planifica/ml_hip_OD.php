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
$rHip = $objML->ListadoSupuestosOD($idProy, $idVersion);
$row_rsSupuestos = mysql_fetch_assoc($rHip); // Recuperamos el primer registro

$objML = NULL; // Liberamos recursos

$urlEdicion = "ml_hip_OD_edit.php?idProy=$idProy&idVersion=$idVersion";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetSubTitle("Supuestos de Finalidad");
?>

<title>Supuestos Objetivo Desarrollo</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEditableHeadTag -->
<!--   <link href="../../css/TGrid.css" rel="stylesheet" type="text/css" /> -->
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 597px;
	top: 0px;
	width: 130px;
	height: 52px;
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

	<form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post"
		enctype="multipart/form-data" id="frmMain">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><button class="Button" onclick="return Nuevo();"
							value="Guardar" <?php if($modificar) echo "disabled";?>>Nuevo</button></td>
					<td width="11%"><button class="Button" value="Cancelar"
							onclick="return Refrescar();">Refrescar</button></td>
					<td align="center" style="color: #00F;"><?php echo($objFunc->SubTitle) ;?></td>
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
										<th width="40" nowrap="nowrap">Codigo</th>
										<th>Descripción</th>
										<th width="20">&nbsp;</th>
									</tr>
								</thead>
								<tbody class="data">
                      <?php do { ?>
                        <tr class="RowData">
										<td width="20" nowrap="nowrap"><span>
						  <?php if($row_rsSupuestos['t06_cod_fin_sup']>0 && !$modificar) { ?>
							<a href="#"><img src="../../../img/b_edit.png" width="14"
													height="14" title="Editar Registro" border="0"
													onclick="Editar('<?php echo $row_rsSupuestos['t06_cod_fin_sup']; ?>');" /></a>
						  <?php } ?> 
						  </span></td>
										<td width="40" nowrap="nowrap"><?php echo $row_rsSupuestos['t06_cod_fin_sup']; ?></td>
										<td align="left"><?php echo $row_rsSupuestos['t06_sup']; ?></td>
										<td width="20" align="left">
                          <?php /* if($row_rsSupuestos['t06_cod_fin_sup']>0 && !$modificar) { ?>
                            <a href="#"><img
												src="../../../img/b_drop.png" width="14" height="14"
												title="Eliminar Registro" border="0"
												onclick="Eliminar('<?php echo $row_rsSupuestos['t06_cod_fin_sup']; ?>', '<?php echo $row_rsSupuestos['t06_sup']; ?>');" /></a>													 
						  <?php } */ ?>
                          </td>
									</tr>
                        <?php } while ($row_rsSupuestos = mysql_fetch_assoc($rHip)); ?>
                    </tbody>
							</table>
						</div>
						<table width="<?php echo($GridWidth);?>" border="1"
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
