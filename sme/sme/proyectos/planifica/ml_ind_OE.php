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
$idComp = $Function->__POST("t08_cod_comp");
if ($idComp == "") {
    $idComp = $Function->__GET("t08_cod_comp");
}

$urlEdicion = "ml_ind_OE_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetSubTitle("Indicadores de Componentes");
?>
<title>Indicadores Objetivo Especifico</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEditableHeadTag -->
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->

	<form
		action="<?php echo($_SERVER['PHP_SELF']);?><?php echo($Function->__QueryString());?>"
		method="post" enctype="multipart/form-data" id="frmMain">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="5%"><button class="Button" onclick="return Nuevo();"
							value="Guardar" <?php if($modificar) echo "disabled"; ?>>Nuevo</button></td>
					<td width="13%"><button class="Button" value="Refresh"
							onclick="return Refrescar();">Refrescar</button></td>

					<td width="82%" align="center" style="color: #00F;"><?php echo($objFunc->SubTitle) ;?></td>
				</tr>
			</table>
		</div>
		<table width="742" height="42" border="0" cellpadding="0"
			cellspacing="0" class="TableEditReg">
			<tr>
				<td width="93" height="27"><span class="TextDescripcion"><strong>&nbsp;
							Componente</strong></span></td>
				<td width="324" align="left"><select name="t08_cod_comp"
					id="t08_cod_comp" style="width: 530px;" onchange="ChangeLista();">
						<option value="" selected="selected"></option>
        <?php
        $rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);
        $Function->llenarComboSinItemsBlancos($rsOE, 't08_cod_comp', 'descripcion', $idComp,'',array(), 't08_comp_desc');
        ?>
      </select></td>
				<td width="325" align="left">&nbsp;<input type="button"
					name="btnRefresh" id="btnRefresh" value="Refrescar"
					class="btn_save_custom" onclick="return Refrescar();"
					style="font-weight: bold; background-color: #EEE; font-size: 11px; border: 1px solid #999; cursor: pointer;" />
				</td>
	  <?php
$rInd = $objML->ListadoIndicadoresOE($idProy, $idVersion, $idComp);
$row_rsIndicador = mysql_fetch_assoc($rInd); // Recuperamos el primer registro
$objML = NULL; // Liberamos recursos
?>
    </tr>
		</table>
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
										<th width="180">Indicador</th>
										<th width="85">Unidad Medida</th>
										<th width="30">Meta</th>
										<th width="310">Fuentes de Verificación</th>
										<th></th>
									</tr>
								</thead>
								<tbody class="data">
                      <?php do { ?>
                        <tr class="RowData">
										<td width="20" nowrap="nowrap"><span>
						  <?php if($row_rsIndicador['t08_cod_comp_ind']>0 && !$modificar) { ?>
							<a href="#"><img src="../../../img/b_edit.png" width="14"
													height="14" title="Editar Registro" border="0"
													onclick="Editar('<?php echo $row_rsIndicador['t08_cod_comp_ind']; ?>');" /></a>
						  <?php } ?>
						  </span></td>
										<td width="180" align="left"><?php echo $row_rsIndicador['t08_ind']; ?></td>
										<td width="85"><?php echo $row_rsIndicador['t08_um']; ?></td>
										<td width="30"><?php echo $row_rsIndicador['t08_mta']; ?></td>
										<td align="left"><?php echo $row_rsIndicador['t08_fv']; ?></td>
										<td>
                           <?php /* if($row_rsIndicador['t08_cod_comp_ind']>0 && !$modificar) { ?>
                            <a href="#"><img
												src="../../../img/b_drop.png" width="14" height="14"
												title="Eliminar Registro" border="0"
												onclick="Eliminar('<?php echo $row_rsIndicador['t08_cod_comp_ind']; ?>', '<?php echo $row_rsIndicador['t08_ind']; ?>');" /></a>													 
						  <?php }*/ ?>
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
	  function ChangeLista(ls_value, lb_vacio)
		{
		 if (lb_vacio==false) {
		 if(ls_value=="" )
			{ return false  } }
			
		 var formulario = document.getElementById("frmMain") ;
		 formulario.submit() ;
		
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
<!-- InstanceEnd -->
</html>
