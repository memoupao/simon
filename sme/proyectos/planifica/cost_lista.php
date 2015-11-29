<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once ("../../../clases/Functions.class.php");

error_reporting("E_PARSE");
$Function = new Functions();
$idProy = $Function->__POST('idProy');
$idVersion = $Function->__POST('idVersion');
$idComp = $Function->__POST('idComp');
$idAct = $Function->__POST('idActiv');

if ($idProy == "" && $idVersion == "" && $idComp == "" && $idAct == "") {
    $idProy = $Function->__GET('idProy');
    $idVersion = $Function->__GET('idVersion');
    $idComp = $Function->__GET('idComp');
    $idAct = $Function->__GET('idActiv');
}

// $Function->Debug(true);

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Detalle del Costeo de Actividades</title>
<style type="text/css">
<!--
#Layer1 {
	position: absolute;
	left: 613px;
	top: 0px;
	width: 134px;
	height: 55px;
	z-index: 0;
	visibility: visible;
}
-->
</style>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<div id="divTableLista">
			<table class="grid-table grid-width">
				<thead>
					<tr>
						<th width="41" height="28" align="center" valign="middle">Codigo</th>
						<th width="266" height="28" align="center" valign="middle">Categoria
							de Gastos</th>
						<th width="71" align="center" valign="middle">U.M.</th>
						<th width="51" align="center" valign="middle">Cantidad</th>
						<th width="50" align="center" valign="middle">Costo Unit.</th>
						<th width="58" align="center" valign="middle">Costo Parcial</th>
						<th width="49" align="center" valign="middle">Meta Fisica</th>
						<th width="59" align="center" valign="middle">Costo Total</th>
						<th width="102" align="center" valign="middle">Fuentes de
							Financiamiento</th>
						<th width="41" align="center" valign="middle">&nbsp;</th>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    require (constant("PATH_CLASS") . "BLPresupuesto.class.php");
    $objPresup = new BLPresupuesto();
    $iRs = $objPresup->ListaSubActividades($idProy, $idVersion, $idComp, $idAct);
    while ($rowsub = mysqli_fetch_assoc($iRs)) {
        ?>
    <tr class="RowData" style="background-color: #E3FEE0;">
						<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
						<td align="left"><?php echo( $rowsub['subactividad']);?></td>
						<td align="center"><?php echo($rowsub['um']);?></td>
						<td align="left" bgcolor="#eeeeee">&nbsp;</td>
						<td align="left" bgcolor="#eeeeee">&nbsp;</td>
						<td align="right"><?php echo(number_format($rowsub['ctoparcial'],2,'.',','));?></td>
						<td align="center"><?php echo($rowsub['meta']);?></td>
						<td align="right"><?php echo(number_format($rowsub['ctototal'],2,'.',','));?></td>
						<td align="center"><?php echo(number_format($rowsub['fuentesfinan'],2,'.',','));?></td>
						<td align="center"><a href="javascript:"><img
								src="../../../img/addicon.gif" width="16" height="16"
								title="Nuevo Registro de Gasto" border="0"
								onclick="NuevoCosteo_OnClic('<?php echo($rowsub['t09_cod_sub']);?>','');"
								style="border: none" /></a>Nuevo</td>
					</tr>
      <?php
        $iRsCateg = $objPresup->ListaSubActividadesCateg($idProy, $idVersion, $idComp, $idAct, $rowsub['t09_cod_sub']);
        while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) {
            ?>
    <tr class="RowData" style="font-weight: 300; color: navy;">
						<td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
						<td align="left" nowrap="nowrap"><?php echo($rowsubCateg['categoria']);?></td>
						<td align="center" nowrap="nowrap">&nbsp;</td>
						<td align="left" nowrap="nowrap">&nbsp;</td>
						<td align="left" nowrap="nowrap">&nbsp;</td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['ctoparcial'],2,'.',','));?></td>
						<td align="center" nowrap="nowrap">&nbsp;</td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['ctototal'],2,'.',','));?></td>
						<td align="center" nowrap="nowrap"><?php echo(number_format($rowsubCateg['fuentesfinan'],2,'.',','));?></td>
						<td align="center" nowrap="nowrap"><a href="javascript:"><img
								src="../../../img/addicon.gif" width="14" height="14"
								style="border: none"
								title="Nuevo Registro de <?php echo($rowsubCateg['categoria']);?>"
								border="0"
								onclick="NuevoCosteo_OnClic('<?php echo($rowsubCateg['subact']);?>','<?php echo($rowsubCateg['t10_cate_cost']);?>');" /></a></td>
					</tr>
      <?php
            $iRsCosteo = $objPresup->ListaSubActividadesCosteo($idProy, $idVersion, $idComp, $idAct, $rowsubCateg['subact'], $rowsubCateg['t10_cate_cost']);
            while ($rowCosteo = mysqli_fetch_assoc($iRsCosteo)) {
                $financ = $rowCosteo['fuentesfinan'];
                if ($financ == 0) {
                    $financ = "Agregar Fte.Finan";
                } else {
                    $financ = number_format($financ, 2, '.', ',');
                }
                ?>
    <tr class="RowData">
						<td align="center" nowrap="nowrap"><a href="#"><img
								src="../../../img/b_edit.png" width="14" height="14"
								title="Editar Registro" border="0"
								onclick="EditarCosteo_OnClic('<?php echo($rowsubCateg['subact']);?>','<?php echo($rowCosteo['t10_cod_cost']);?>');" /></a>
							<a href="#"><img src="../../../img/b_drop.png" width="14"
								height="14" title="Eliminar Registro" border="0"
								onclick="btnEliminar_Clic('<?php echo($rowsubCateg['subact']);?>','<?php echo($rowCosteo['t10_cod_cost']);?>');" /></a></td>
						<td align="left"><?php echo($rowCosteo['t10_cost']);?></td>
						<td align="center"><?php echo($rowCosteo['t10_um']);?></td>
						<td align="center" valign="middle"><?php echo(round($rowCosteo['t10_cant'],4));?></td>
						<td align="right" valign="middle"><?php echo(round($rowCosteo['t10_cu'],4));?></td>
						<td align="right" valign="middle"><?php echo(number_format($rowCosteo['t10_cost_parc'],2,'.',','));?></td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="right" valign="middle"><?php echo(number_format($rowCosteo['t10_cost_tot'],2,'.',','));?></td>
						<td align="center" valign="middle"><a
							href="javascript:FuentesFinanc('<?php echo($rowCosteo['t09_cod_sub']);?>','<?php echo($rowCosteo['t10_cod_cost']);?>')"
							title="Fuentes de Financiamiento"><?php echo($financ);?></a></td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>
    <?php
            }
            $iRsCosteo->free();
        }
        $iRsCateg->free(); // Fin de Categorias de Gastos
    }
    $iRs->free(); // Fin de Actividades
    
    ?>
    </tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th width="266" align="right">&nbsp;</th>
						<th colspan="8" align="right">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input type="hidden"
				name="t02_version" value="<?php echo($idVersion);?>" /> <input
				type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
			<input type="hidden" name="t09_cod_act"
				value="<?php echo($idActiv);?>" />
			<script language="javascript" type="text/javascript">
	  function NuevoCosteo_OnClic(subact, categ)
	  {
		    var idProy = $('#txtCodProy').val();
			var idVersion = $('#cboversion').val();
			var idComponente = $('#cboComponente').val();
			var idActividad = $('#cboActividad').val();
			var idSAct = subact ;
			
			if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}
			if( idSAct == '' || idSAct==null) {alert("No se ha seleccionado ninguna Actividad !!!"); return false;}
			
			var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct+"&categ="+categ+"&idcosto=";
			var url = "cost_edit.php?action=<?php echo(md5("new"));?>" + params ;
		    $('#iSubActividades').attr('src',url);
		    spryPopupDialogEditReg.displayPopupDialog(true);
			
	  }
	  function EditarCosteo_OnClic(subact, idcosto)
	  {
		    var idProy = $('#txtCodProy').val();
			var idVersion = $('#cboversion').val();
			var idComponente = $('#cboComponente').val();
			var idActividad = $('#cboActividad').val();
			var idSAct = subact ;
			
			if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad !!!"); return false;}
			if( idSAct == '' || idSAct==null) {alert("No se ha seleccionado ninguna Actividad !!!"); return false;}
			
			var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct+"&categ=0&idcosto="+idcosto;
			var url = "cost_edit.php?action=<?php echo(md5("edit"));?>" + params ;
		    $('#iSubActividades').attr('src',url);
		    spryPopupDialogEditReg.displayPopupDialog(true);
			
	  }
	  function FuentesFinanc(subact, idcosto)
	  {
		
		var idProy = $('#txtCodProy').val();
		var idVersion = $('#cboversion').val();
		var idComponente = $('#cboComponente').val();
		var idActividad = $('#cboActividad').val();
		var idSAct = subact ;
		var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct+"&categ=0&idcosto="+idcosto;
		var url = "cost_fte_finan.php?action=<?php echo(md5("edit"));?>" + params ;
		$('#iSubActividades').attr('src',url);
		spryPopupDialogEditReg.displayPopupDialog(true);

	  }
   </script>

			<script language="javascript" type="text/javascript">
	  function CNumber(str)
	  {
		  var numero =0;
		  if (str !="" && str !=null)
		  { numero = parseFloat(str);}
		  
		  if(isNaN(numero)) { numero=0;}
		 
		 return numero;
	  }
  
 </script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>