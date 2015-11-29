<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');
$view = $objFunc->__POST('view');
$view = $view == "" ? $objFunc->__GET("view") : $view;

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Indicadores de Proposito</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <table width="780" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="75%">&nbsp;</td>
				<td width="10%"><button onclick="Guardar_IndicadoresProposito(); return false;" class="boton btn_guardar">Guardar</button></td>
				<td width="10%"><button onclick="LoadIndicadoresProposito(true); return false;" class="boton">Refrescar</button></td>
			</tr>
		</table>

		<div id="divTableLista">
			<table width="780" cellpadding="0" cellspacing="0" class="TableEditReg">
				<tbody class="data" bgcolor="#FFFFFF">
                <?php
                $objInf = new BLInformes();
                $iRs = $objInf->listarIndicadoresPropositoEntregable($idProy, $idVersion, $anio, $idEntregable);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) { ?>
                     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" align="left" valign="middle"><b>Indicador de Proposito</b></td>
						<td height="15" colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><b>Meta Planeada</b></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><b>Ejecutado</b></td>
					</tr>
					<tr align="center">
						<td width="412" rowspan="2" align="left" valign="middle">
						    <?php echo($row['t07_cod_prop_ind']." ".$row['indicador']);?>
                            <input name="t07_cod_prop_ind[]" type="hidden" id="t07_cod_prop_ind[]" value="<?php echo($row['t07_cod_prop_ind']);?>"/> <br />
                            <span><b style="color: #48628A;">Unidad Medida</b>: <?php echo( $row['t07_um']);?></span>
                        </td>
						<td width="60"><b>Total</b></td>
						<td width="58"><b>Acum</b></td>
						<td width="55"><b>Entregable</b></td>
						<td width="62"><b>Acum</b></td>
						<td width="63"><b>Entregable</b></td>
						<td width="68"><b>Total</b></td>
					</tr>
					<tr align="center">
						<td nowrap="nowrap"><?php echo( $row['plan_mtaTotal']);?></td>
						<td nowrap="nowrap"><?php echo( $row['plan_mtaAcum']);?></td>
						<td nowrap="nowrap"><?php echo( $row['plan_mtaEntregable']);?></td>
						<td nowrap="nowrap">
						    <input class="ITI_Pro" name="txtIndPropAcum[]" type="text" id="txtIndPropAcum[]"
							style="text-align: center;" value="<?php echo( $row['ejec_mtaAcum']);?>" size="4"readonly="readonly" />
						</td>
						<td nowrap="nowrap">
						    <input MontoIndPropTri='1' name="txtIndPropEntregable[]" type="text" id="txtIndPropEntregable[]" style="text-align: center;"
                            value="<?php echo($row['ejec_mtaEntregable']);?>" size="4" onkeyup="TotalAvanceIndicadorProp('<?php echo($RowIndex);?>');" />
						</td>
						<td nowrap="nowrap"><input class="ITI_Pro"
							name="txtIndPropTot[]" type="text" id="txtIndPropTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtaTotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="7" align="left" nowrap="nowrap">
						    DESCRIPCION <br />
						    <textarea name="txtIndPropdes[]" cols="2500" rows="2" id="txtIndPropdes[]" style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea><br />
							LOGROS <br />
							<textarea name="txtIndProplog[]" cols="2500" rows="2" id="txtIndProplog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea><br />
							DIFICULTADES <br />
							<textarea name="txtIndPropdif[]" cols="2500" rows="2" id="txtIndPropdif[]" style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea>
							<br /> <b style="color: #48628A;">Observaciones del Gestor de Proyecto</b> <br />
						    <textarea name="txtIndPropObs[]" cols="2500" rows="2" id="txtIndPropObs[]" style="padding: 0px; width: 100%;"><?php echo($row['observaciones']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de Actividades
    else {
        echo ("<b style='color:red'>No se tienen registrado Indicadores de Proposito...<br />Verificar el Marco Logico</b>");
        exit();
    }

    ?>
    </tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="anio" value="<?php echo($anio);?>" />
			<input type="hidden" name="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
	function Guardar_IndicadoresProposito()
	{
	    <?php $ObjSession->AuthorizedPage(); ?>

        var BodyForm=$("#FormData").serialize();
    	if(confirm("Está seguro de guardar el avance de los indicadores de Propósito para el Informe?"))
    	{
    		var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_ind_proposito'));?>";
    		var req = Spry.Utils.loadURL("POST", sURL, true, IndPropSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
    	}
	}

	function IndPropSuccessCallback(req)
	{
        var respuesta = req.xhRequest.responseText;
        respuesta = respuesta.replace(/^\s*|\s*$/g,"");
        var ret = respuesta.substring(0,5);

        if(ret=="Exito")
        {
            LoadIndicadoresProposito(true);
            alert(respuesta.replace(ret,""));
        }
        else
        {alert(respuesta);}
	}

	function TotalAvanceIndicadorProp(x)
	{
	  var index=parseInt(x) ;
	  var xTotal=document.getElementsByName("txtIndPropTot[]") ;
	  var xAcum =document.getElementsByName("txtIndPropAcum[]");
	  var xMes  =document.getElementsByName("txtIndPropEntregable[]") ;
	  var mtaacum =parseFloat(xAcum[index].value) ;
	  var mtaEntregable =parseFloat(xMes[index].value) ;
	  if(isNaN(mtaacum)){mtaacum=0;}
	  if(isNaN(mtaEntregable)){mtaEntregable=0;}
	  var total=(mtaacum + mtaEntregable) ;
	  xTotal[index].value = total ;

	}
		$("input[MontoIndPropTri='1']").numeric().pasteNumeric();
		$('.ITI_Pro:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
	</script>
		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>