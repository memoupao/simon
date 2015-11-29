<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php

require_once ("../../../clases/BLMarcoLogico.class.php");
require_once ("../../../clases/Functions.class.php");
require_once ("../../../clases/BLPOA.class.php");

$Function = new Functions();
$objML = new BLMarcoLogico();
$objPOA = new BLPOA();

$idProy = $Function->__Request('idProy');
$idVersion = $Function->__Request('idVersion');
$idComp = $Function->__Request("t08_cod_comp");
$idAct = $Function->__Request("t09_cod_act");

$snew = $Function->__Request("snew");

if ($idComp == "") {
    $idComp = $Function->__GET("t08_cod_comp");
}
if ($idAct == "") {
    $idAct = $Function->__GET("t09_cod_act");
}

$urlEdicion = "ml_ind_act_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp&idAct=$idAct";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
$objFunc->SetSubTitle("Actividad");
?>
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<title>Actividades</title>
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
		action="<?php echo($_SERVER['PHP_SELF']);?><?php echo("?idProy=".$idProy."&idVersion=".$idVersion);?>"
		method="post" enctype="multipart/form-data" id="frmMain">
		<input type="hidden" name=snew value="<?php echo $snew;?>"/>
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					<td width="31%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="9%"><button class="Button" onclick="return Nuevo();"
							value="Guardar">Nuevo</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return Exportar();">Exportar</button></td>
				</tr>
			</table>
		</div>
		<table width="580" height="45" border="0" cellpadding="0"
			cellspacing="0" class="TableEditReg">
			<tr>
				<td width="93" height="21" align="left"><span
					class="TextDescripcion"><b>Componente</b></span></td>
				<td width="324" align="left"><select name="t08_cod_comp"
					id="t08_cod_comp" style="width: 480px;" onchange="ChangeLista(0);">
						<option value="" selected="selected"></option>
        <?php
        $rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);
        $Function->llenarCombo($rsOE, 't08_cod_comp', 'descripcion', $idComp);
        ?>
      </select></td>
				<td width="325" align="left">&nbsp; <input type="image"
					name="btnRefresh" id="btnRefresh"
					src="../../../img/btnRecuperar.gif" /></td>
			</tr>
			<tr>
				<td height="22" align="left"><span class="TextDescripcion"><b>Producto</b></span></td>
				<td align="left"><select name="t09_cod_act" id="t09_cod_act"
					style="width: 480px;" onchange="ChangeLista(1);">
						<option value=""></option>
	    <?php
    $rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
    $Function->llenarCombo($rsAct, 't09_cod_act', 'descripcion', $idAct);
    ?>
      </select></td>
				<td align="left">&nbsp;</td>
      <?php

    $iRs = $objPOA->Lista_Subactividad($idProy, $idVersion, $idComp, $idAct);
    $objPOA = NULL; // Liberamos recursos

    ?>
    </tr>
		</table>
  <?php
$GridHeigth = 250;
$GridWidth = 565;
?>
            <table width="575" border="0" cellpadding="0"
			cellspacing="0">
			<tr>
				<td width="11">&nbsp;</td>
				<td width="606" colspan="3" rowspan="2">

					<div class="TableGrid">
						<table width="566" border="1" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th width="50" nowrap="nowrap" height="25">&nbsp;</th>
									<th width="40">Código</th>
									<th width="310">Actividad</th>
									<th width="80">Unidad Medida</th>
									<th width="80">Es Critica</th>
									<th width="50" nowrap="nowrap" height="25">&nbsp;</th>
								</tr>
							</thead>
						</table>
						<div style="overflow:auto; width:<?php echo($GridWidth-2);?>px; height:<?php echo($GridHeigth);?>px; border: 1px solid #abb6ec;">
							<table width="<?php echo($GridWidth-2);?>" border="1"
								cellpadding="0" cellspacing="0">
								<tbody class="data">
                      <?php
                    while ($row_rsSubAct = mysqli_fetch_assoc($iRs)) {
                        ?>
                        <tr class="RowData">
										<td width="40" nowrap="nowrap"><span>
						  <?php if($row_rsSubAct['subact']>0) { ?>
							<a href="#"><img src="../../../img/b_edit.png" width="16"
													height="16" title="Editar Registro" border="0"
													onclick="Editar('<?php echo($row_rsSubAct['t02_cod_proy']);?>','<?php echo($row_rsSubAct['t02_version']);?>','<?php echo($row_rsSubAct['comp']);?>','<?php echo($row_rsSubAct['act']);?>','<?php echo($row_rsSubAct['subact']);?>'); return false;" /></a>

						  <?php } ?>
						  </span></td>
										<td width="40"><?php echo $row_rsSubAct['subact']; ?></td>
										<td width="320" align="left"><?php echo $row_rsSubAct['descripcion']; ?></td>
										<td width="80"><?php echo $row_rsSubAct['um']; ?></td>
										<td width="80" align="center"><?php echo $row_rsSubAct['critica']; ?></td>
										<td width="40" nowrap="nowrap"><span>
						  <?php  if(empty($snew) && $row_rsSubAct['subact']>0) { ?>

                            <a href="#"><img
													src="../../../img/b_drop.png" width="16" height="16"
													title="Eliminar Registro" border="0"
													onclick="Eliminar('<?php echo($row_rsSubAct['t02_cod_proy']);?>','<?php echo($row_rsSubAct['t02_version']);?>','<?php echo($row_rsSubAct['comp']);?>','<?php echo($row_rsSubAct["act"]);?>','<?php echo($row_rsSubAct["subact"]);?>','<?php echo($row_rsSubAct["descripcion"]);?>'); return false;" /></a>
						  <?php }  ?>
						  </span></td>
									</tr>
                      <?php }?>
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
	  function ChangeLista(tipo)
		{
		 if(tipo==0){document.getElementById("t09_cod_act").value="";}
		 var formulario = document.getElementById("frmMain") ;
		 formulario.submit() ;
		}

	  function Nuevo()
	  {

	  var idProy 	=  "<?php echo($idProy);?>";
	  var idVersion =  <?php echo($idVersion);?>;
	  var idComp	=	$("#t08_cod_comp").val();
	  var idAct		=	$("#t09_cod_act").val();

	  if( idComp == '' || idComp==null) {alert("Seleccione un Componente"); return false;}
	  if( idAct == '' || idComp==null) {alert("Seleccione un Producto"); return false;}

	    parent.btnNuevo_Sub(idProy,idVersion, idComp, idAct);
		return false;
	  }

	  function Editar(idProy,idVersion, idComponente, idActividad, idSAct)
	  {

		parent.btnEditar_Sub(idProy,idVersion, idComponente, idActividad, idSAct);
		return false;
	  }

	  function Eliminar(idProy,idVersion, idComponente, idActividad, idSAct, Subact)
	  {
		  parent.btnEliminar_Sub(idProy,idVersion, idComponente, idActividad, idSAct, Subact);
		  return false;
	  }

	  function Exportar()
	  {
	  	alert("No Implementado !!");
		return false;
	  }
	  </script>


	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
