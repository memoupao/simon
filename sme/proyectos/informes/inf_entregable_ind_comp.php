<?php
/**
 * CticServices
 *
 * Permite la edición de Indicadores
 * de Componentes del Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idComp = $objFunc->__POST('idComp');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idComp = $objFunc->__GET('idComp');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Indicadores de Componentes</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <div id="divTableLista">
			<table width="780" cellpadding="0" cellspacing="0" class="TableEditReg">
				<tbody class="data" bgcolor="#FFFFFF">
                <?php
                $objInf = new BLInformes();
                $iRs = $objInf->listarIndicadoresComponenteEntregable($idProy, $idVersion, $idComp, $anio, $idEntregable);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) { ?>
                    <tr align="center" style="border: solid 1px #CCC; background-color: #eeeeee;">
                        <td width="412" align="left" valign="middle"><b>Indicador de Componente</b></td>
                        <td height="15" rowspan="2" valign="middle"><b>Meta Total Planeada en el Proyecto</b></td>
                        <td colspan="3" valign="middle" bgcolor="#CCCCCC"><b>Ejecutado</b></td>
					</tr>
					<tr align="center">
						<td width="412" rowspan="2" align="left" valign="middle">
						    <?php echo($row['t08_cod_comp_ind'].".- ".$row['indicador']);?>
                             <input name="t08_cod_comp_ind[]" type="hidden" id="t08_cod_comp_ind[]" value="<?php echo($row['t08_cod_comp_ind']);?>"/> <br />
                             <span class="nota">Unidad Medida:</span> <?php echo( $row['t08_um']);?></td>
						<td width="62"><b>Acum</b></td>
						<td width="63"><b>Entregable</b></td>
						<td width="68"><b>Total</b></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaTotal']);?></td>
						<td align="center" nowrap="nowrap">
						    <input class="ITC_Pro" name="txtIndCompAcum[]" type="text" id="txtIndCompAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtaAcum']);?>" size="4"
							readonly="readonly" />
						</td>
						<td align="center" nowrap="nowrap">
						    <input MontoIndCompTri='1' name="txtIndCompEntregable[]" type="text" id="txtIndCompEntregable[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtaEntregable']);?>" size="4"
							onkeyup="TotalAvanceIndicadorComp('<?php echo($RowIndex);?>');" /></td>
						<td align="center" nowrap="nowrap">
						    <input class="ITC_Pro" name="txtIndCompTot[]" type="text" id="txtIndCompTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtaTotal']);?>" size="4"
							readonly="readonly" />
						</td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="7" align="left" nowrap="nowrap">DESCRIPCION <br /> <textarea
								name="txtIndCompdes[]" cols="2500" rows="2" id="txtIndCompdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?> </textarea>
							<br /> LOGROS <br /> <textarea name="txtIndComplog[]" cols="2500"
								rows="2" id="txtIndComplog[]" style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea>
							<br /> DIFICULTADES <br /> <textarea name="txtIndCompdif[]"
								cols="2500" rows="2" id="txtIndCompdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea>
							<br /> <span class="nota">Observaciones del Gestor de Proyecto</span> <br /> <textarea
								name="txtIndCompobs[]" cols="2500" rows="2" id="txtIndCompobs[]"
								style="padding: 0px; width: 100%;"><?php echo($row['observaciones']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }
    else {
        echo ("<b style='color:red'>El Componente Seleccionado[" . $idComp . "] no tiene registrado Indicadores...<br />Verificar el Marco Lógico</b>");
        exit();
    }
    ?>
    </tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="idComp" value="<?php echo($idComp);?>" />
			<input type="hidden" name="anio" value="<?php echo($anio);?>" />
			<input type="hidden" name="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
                function Guardar_AvanceIndComp()
                {
                <?php $ObjSession->AuthorizedPage(); ?>

                var comp = $('#cbocomponente_ind').val();

                if(comp==""){alert("El componente seleccionado, no tiene indicadores"); return;}

                    var BodyForm=$("#FormData").serialize();

                    if(confirm("Estas seguro de Guardar el avance de los Indicadores de Componente para el Informe ?"))
                    {
                    	var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_ind_componente'));?>";
                    	var req = Spry.Utils.loadURL("POST", sURL, true, indCompSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                    }
                }

                function indCompSuccessCallback	(req)
                {
                    var respuesta = req.xhRequest.responseText;
                    respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                    var ret = respuesta.substring(0,5);

                    if(ret=="Exito")
                    {
                    LoadIndicadoresComponente();
                    alert(respuesta.replace(ret,""));
                    }
                    else
                    {alert(respuesta);}
                }

                function TotalAvanceIndicadorComp(x)
                {
                    var index=parseInt(x) ;
                    var xTotal=document.getElementsByName("txtIndCompTot[]") ;
                    var xAcum =document.getElementsByName("txtIndCompAcum[]");
                    var xMes =document.getElementsByName("txtIndCompEntregable[]") ;

                    var mtaacum =parseFloat(xAcum[index].value);
                    var mtames =parseFloat(xMes[index].value);
                    if(isNaN(mtaacum)){mtaacum=0;}
                    if(isNaN(mtames)){mtames=0;}
                    var total=(mtaacum + mtames);
                    xTotal[index].value = total;
                }

            	$("input[MontoIndCompTri='1']").numeric().pasteNumeric();
            	$('.ITC_Pro:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
            </script>
		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>