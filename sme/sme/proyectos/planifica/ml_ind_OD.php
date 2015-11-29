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

$rInd = $objML->ListadoIndicadoresOD($idProy, $idVersion);
$row_rsIndicador = mysql_fetch_assoc($rInd); // Recuperamos el primer registro

$objML = NULL; // Liberamos recursos

$urlEdicion = "ml_ind_OD_edit.php?idProy=$idProy&idVersion=$idVersion";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetSubTitle("Indicadores de Finalidad");
?>
<title>Indicadores Objetivo Desarrollo</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEditableHeadTag -->
<!--   <link href="../../css/TGrid.css" rel="stylesheet" type="text/css" /> -->
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 545px;
	top: 0px;
	width: 182px;
	height: 57px;
	z-index: 0;
	visibility: visible;
}
-->
</style>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>
<SCRIPT src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"
	type="text/javascript"></SCRIPT>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js"
	type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>

<body>
	<!-- InstanceBeginEditable name="tBody" -->

	<form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post"
		enctype="multipart/form-data" id="frmMain">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><button class="Button" onclick="return Nuevo();"
							value="Guardar" <?php if($modificar) echo "disabled"; ?>>Nuevo</button></td>
					<td width="11%"><button class="Button" value="Cancelar"
							onclick="return Refrescar();">Refrescar</button></td>
					<td width="2%">&nbsp;</td>
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
										<th width="180" align="center">Indicador</th>
										<th width="85" align="center">Unidad Medida</th>
										<th width="30" align="center">Meta</th>
										<th width="310" align="center">Fuentes de Verificación</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="data">
                      <?php do { ?>
                        <tr class="RowData">
										<td width="20"><span>
                          	<?php //if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
							<?php if($row_rsIndicador['t06_cod_fin_ind']>0 && !$modificar) { ?>
							<a href="#">
								<?php //if(!$modificar){ ?>
								<img src="../../../img/b_edit.png" width="14" height="14"
													title="Editar Registro" border="0"
													onclick="Editar('<?php echo $row_rsIndicador['t06_cod_fin_ind']; ?>');" />
								<?php //} ?>
							</a>
							<?php } ?>
						  <?php // } ?>
						  </span></td>
										<td width="180" align="left"><?php echo( $row_rsIndicador['t06_ind']); ?></td>
										<td width="85"><?php echo($row_rsIndicador['t06_um']); ?></td>
										<td width="30"><?php echo $row_rsIndicador['t06_mta']; ?></td>
										<td align="left"><?php echo($row_rsIndicador['t06_fv']); ?></td>
										<td align="left">
                          <?php /*if($ObjSession->AuthorizedOpcion("ELIMINAR")) { ?>
							<?php if(!$modificar){ ?>
                            <a href="#"><img
												src="../../../img/b_drop.png" width="14" height="14"
												title="Eliminar Registro" border="0"
												onclick="Eliminar('<?php echo $row_rsIndicador['t06_cod_fin_ind']; ?>', '<?php echo $row_rsIndicador['t06_ind']; ?>');" /></a>													 
							<?php } ?>
						 <?php } */ ?>
                          
                          </td>
									</tr>
                        <?php } while ($row_rsIndicador = mysql_fetch_assoc($rInd)); ?>
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
	  }
	  
	  function Editar(codigo)
	  {
		 var formulario = document.getElementById("frmMain") ;
		 formulario.action = "<?php echo($urlEdicion);?>&view=edit&id=" + codigo; 
		 formulario.submit() ;
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
		//formulario.submit() ;
		window.location.reload();
		return false;
	  }
	  </script>


	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
