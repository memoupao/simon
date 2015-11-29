<?php 
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once(constant("PATH_CLASS")."HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$idComp = $objFunc->__Request('idComp');
$objHC = new HardCode();

if ($idProy == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type"	content="text/html; charset=charset=utf-8" />
    <title>POA - Actividades</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <div id="divTableLista" class="TableGrid">
			<table width="780" cellpadding="0" cellspacing="0" class="">
				<thead>
				</thead>
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td height="40" colspan="3" align="left" valign="middle" bgcolor="#FFFFFF"><b>Planificacion de Metas - Actividades</b></td>
					<td width="59" height="40" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" colspan="3" align="center" valign="middle" bgcolor="#FFFFFF">
                        <div align="center" style="width: 120px; height: 40px; display: inline-table;">
							<input type="button" value="Refrescar" title="Recargar Datos" onclick="LoadSubActividades(true);" class="btn_save_custom" style="margin-top: 10px;" /> &nbsp; &nbsp;
						</div>
                    </td>
					<td height="40" colspan="3" align="center" valign="middle" bgcolor="#FFFFFF">
                        Exportar <select name="cboExport" id="cboExport" style="width: 130px; font-size: 11px;" class="SubActividad" onchange="LoadExport();">
							<option value="0" style="color: #F00">Seleccionar opción</option>
							<option value="1">Cronograma Anual</option>
							<option value="2">POA Anual</option>
					   </select>
                    </td>
				</tr>
				<tr style="background-color: #FFF;">
					<td height="36" colspan="11" align="left" valign="middle">
						<div style="display: inline-block;">
							<b>Componente</b>
						</div> &nbsp; &nbsp;
						<div style="display: inline-block;">
							<select name="cboComponente_sub" id="cboComponente_sub"
								style="width: 490px; font-size: 11px;" class="SubActividad"
								onchange="LoadSubActividades(true);">
                        		<?php
                                    $objML = new BLMarcoLogico();
                                    $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
                                    $objFunc->llenarComboSinItemsBlancos($rsComp, "t08_cod_comp", 'descripcion', $idComp,'',array(),'t08_comp_desc');
                                ?>
                                </select> &nbsp; &nbsp;&nbsp; &nbsp; <select
								name="cboAgregar_cas" id="cboAgregar_cas"
								style="width: 130px; font-size: 11px;" class="SubActividad"
								onchange="LoadCAS(true);">
								<option value="0" style="color: #F00">Seleccionar opción</option>
								<option value="1" style="background: #FFBF55; font-weight: bold">Agregar Componente</option>
								<!-- <option value="2" style="background: #DAF3DD; font-weight: bold">Agregar Producto</option> -->
								<option value="3" style="font-weight: bold">Agregar Actividad</option>
							</select>
						</div>
					</td>
				</tr>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td height="15" align="left" valign="middle">&nbsp;</td>
						<td width="221" height="15" align="left" valign="middle">Actividades</td>
						<td height="15" align="center" valign="middle">Unidad Medida</td>
						<td height="15" align="center" valign="middle">Meta Física Total Inicial</td>
						<td height="15" align="center" valign="middle">Meta Proyectada del Año Anterior</td>
						<td height="15" align="center" valign="middle">Meta Física Total Vigente</td>
						<td height="15" align="center" valign="middle">Meta Ejecutada Años Anteriores</td>
						<td height="15" align="center" valign="middle">Meta Total por Ejecutar</td>
						<td height="15" align="center" valign="middle">Meta Total del Año <?php echo($idAnio);?></td>
						<td height="15" align="center" valign="middle">Meta Proyectada Años Restantes</td>
						<td height="15" align="center" valign="middle">Meta Reprogram</td>
						<td height="15" align="center" valign="middle">Variac</td>
					</tr>
    <?php
    $objML = new BLMarcoLogico();
    $objPOA = new BLPOA();

    $rs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
    $rows = mysql_num_rows($rs);

    while ($rowAct = mysql_fetch_assoc($rs)) {

        ?>

   		    <tr style="border: solid 1px #CCC; background-color: #DAF3DD;">
						<td width="31" align="left" valign="middle">
              <?php echo($idComp.".".$rowAct['t09_cod_act']);?></td>
						<td height="15" colspan="11" align="left" valign="middle"><b><?php echo($rowAct['t09_act']);?>
                <input name="t08_cod_comp_ind[]2" type="hidden"
								class="ind_comp" id="t08_cod_comp_ind[]2"
								value="<?php echo($rowAct['t09_cod_act']);?>" /> </b></td>
					</tr>
			<?php
        $iRs = $objPOA->POA_ListadoSubActividades($idProy, $idVersion, $idComp, $rowAct['t09_cod_act'], $idAnio);
        $RowIndex = 0;

        while ($row = mysqli_fetch_assoc($iRs)) {
            $bold = "normal";
            $color = "black";
            if ($row["act_add"] == '1') {
                $color = "green";
                $bold = "bold";
            }

            // AQ: Incidencia #230
            $row['mreprog'] = $row['meaa'] + $row['meta_poa'] + $row['mpar'];

            if ($row['t09_obs_mt'] != '') {
                $color = "red";
            }

            $mftv = $idAnio==1?$row['mfi']:$row['mftv'];
            $mtpe = $mftv - $row['meaa'];
            $mvar = ($row['meaa'] + $row['meta_poa'] + $row['mpar']) - $mftv;
            ?>
                    <tr style="color:<?php echo($color);?>; font-weight:<?php echo($bold);?>;" title="<?php if($row['t09_obs_mt']!=''){ echo("Observaciones: ". substr($row['t09_obs_mt'],0,400 )); } ?>">
						<td width="31" align="left" valign="middle" bgcolor="#FFFFFF">
							<a href="javascript:;" onclick="ModificarSubAct('<?php echo($rowAct['t09_cod_act']);?>','<?php echo($row["subact"]);?>');"><?php echo($row['codigo']);?></a></td>
						<td align="left" valign="middle"><?php echo( $row['descripcion']);?>
                      		<input name="t08_cod_comp_ind[]" type="hidden" class="ind_comp" id="t08_cod_comp_ind[]" value="<?php echo($row['codigo']);?>" /></td>
						<td align="center" valign="middle"><?php echo( $row['um']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo(number_format($row['mfi']));?></td>
						<td width="68" align="center" valign="middle"><?php echo(number_format($row['mpaa']));?></td>
						<td width="60" align="center" valign="middle"><?php echo(number_format($mftv));?></td>
						<td width="60" align="center" valign="middle"><?php echo(number_format($row['meaa']));?></td>
						<td width="55" align="center" valign="middle"><?php echo(number_format($mtpe));?></td>
						<td width="46" align="center" valign="middle" bgcolor="#F2F7B9"><a
							href="javascript:CambiarMetas('<?php echo($row['codigo']);?>');"
							title="Modificar las Metas del Año">
                          <?php echo(number_format($row['meta_poa']));?>
                          </a></td>
						<td width="68" align="center" valign="middle"><?php echo(number_format($row['mpar']));?></td>
						<td width="63" align="center" valign="middle"><?php echo(number_format($row['mreprog']));?></td>
						<td width="42" align="center" valign="middle"><?php echo(number_format($mvar));?></td>
					</tr>
	   <?php
        }
        $iRs->free();
    }
    ?>
  </tbody>
				<tfoot>
				</tfoot>
			</table>
<?php
if ($rows <= 0) {
    echo ("<font style='color:red'>" . "El Poryecto no cuenta con subActividades para el Componente seleccionado, o Aún no se ha generado la Nueva versión" . "</font>");
}
?>


</div>
		<p>&nbsp;</p>
		<div id="divTableLista">
			<input type="hidden" name="t02_cod_proy" id="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input type="hidden"
				name="t02_version" id="t02_version"
				value="<?php echo($idVersion);?>" /> <input type="hidden"
				name="t08_cod_comp" id="t08_cod_comp" value="<?php echo($idComp);?>" />
	<script language="javascript" type="text/javascript">
    	<?php
            $rowPOA = $objPOA->POA_Seleccionar($idProy, $idAnio);
            $disabledGuardar = "0";
            if ($rowPOA['t02_estado'] == $objHC->especTecAprobRA) {
                $disabledGuardar = "1";
            }
        ?>
        var disabledGuardar = "<?php echo($disabledGuardar); ?>";

        function CambiarMetas(idSubAct)
        {
        	if(disabledGuardar == "0"){
                var cods = idSubAct.split(".");
                var comp   = cods[0];
                var activ  = cods[1];
                var subact = cods[2];

                var url = "poa_tec_sub_act_metas.php?idProy=<?php echo($idProy);?>&idVersion=<?php echo($idVersion);?>&anio=<?php echo($idAnio); ?>&idComp="+comp+"&idActiv="+activ+"&idSActiv="+subact+"&mode=<?php echo(md5("edit"));?>";
                loadPopup("<?php echo('Modificación');?> de Metas - POA <?php echo($idAnio); ?>", url);
        	}
        }

     function LoadCAS()
     {	var idProy 		= $('#t02_cod_proy').val();
	 	var idVersion 	= $('#t02_version').val();
	 	var idComp 		= $('#t08_cod_comp').val();
	 	var idActiv 	= $('#idActiv').val();
		$('#divChangePopup').html('<iframe class="Iframe" id="iAgregarPOA" name="iAgregarPOA" scrolling="no" style="width:99%; height:380px;"></iframe>');

		var cod = $('#cboAgregar_cas').val();
		parent.AgregarPOA(cod, idProy, idVersion, idComp, idActiv);
		return false;
	 }

	 function ModificarSubAct(idActiv, idSub)
	  {
		 if(disabledGuardar == "0"){
		    var idProy 		= "<?php echo($idProy);?>";
            var idVersion 	= "<?php echo($idVersion);?>";
            var idComp 		= "<?php echo($idComp);?>";

            $('#divChangePopup').html('<iframe class="Iframe" id="iAgregarPOA" name="iAgregarPOA" scrolling="no" style="width:99%; height:380px;"></iframe>');

             var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComp+"&idActiv="+idActiv+"&idSActiv="+idSub+"&view=edit";
             var url = "<?php echo (constant('PATH_SME').'proyectos/poa/');?>poa_sact_editar.php?action=<?php echo(md5("edit"));?>" + params ;
             $('#iAgregarPOA').attr('src',url);

            spryPopupDialog01.displayPopupDialog(true);
	    }
		return true;
	  }


	function LoadExport()
	{
		var arrayControls = new Array();
			arrayControls[0] = "idProy=" + $('#t02_cod_proy').val();
			arrayControls[1] = "idVersion=" + $('#t02_version').val();
			arrayControls[2] = "idAnio=" + $('#t02_anio').val();
			arrayControls[3] = "t02_periodo=" + $('#t02_periodo').val();
			arrayControls[4] = "t02_estado=" + $('#t02_estado option:selected').text();

		var cod = $('#cboExport').val();
		switch(cod)
		{	case '0'	: 	; return false;
			case '1'	: 	var sID = "32" ; break;
			case '2'	: 	var sID = "33" ; break;
		}
		var params = arrayControls.join("&");
		$("#cboExport").val(0);
		showReport(sID, params);
	}

 	function showReport(reportID, params)
	  {
		 var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;
		 $('#FormData').attr({target: "winReport"});
		 //alert($('#FormData').attr({target: "winReport"}));
		 $('#FormData').attr({action: newURL});
		 $('#FormData').submit();
		 $('#FormData').attr({target: "_self"});
		 $("#FormData").removeAttr("action");
	  }

</script>

		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>