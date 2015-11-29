<?php
/**
 * CticServices
 *
 * Permite la edición de Indicadores
 * de Productos del Informe de Entregable
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
$idProd = $objFunc->__POST('idProd');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $idComp == "" && $idProd == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idProd = $objFunc->__GET('idProd');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

$actividad = explode(".", $idProd);
$idComp = $actividad[0];
$idProd = $actividad[1];

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Indicadores de Producto</title>
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
                $iRs = $objInf->listarIndicadoresProductoEntregable($idProy, $idVersion, $idComp, $idProd, $anio, $idEntregable);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
                    <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" align="left" valign="middle"><b>Indicador de Producto</b></td>
						<td height="15" colspan="3" align="center" valign="middle"><b>Metas</b></td>
					</tr>
					<tr align="center">
						<td width="412" rowspan="2" align="left" valign="middle">
       		                <?php echo(($RowIndex + 1).".- ".$row['indicador']);?>
                            <input name="listIdProd[]" type="hidden" id="listIdProd[]" value="<?php echo($row['t09_cod_act_ind']);?>" /> <br />
                            <span class="nota">Unidad Medida:</span> <?php echo( $row['t09_um']);?>
						</td>
						<td width="60"><b>Total en el Proyecto</b></td>
						<td width="58"><b>Planeada Acumulada al Entregable</b></td>
						<td width="55"><b>Ejecutada Acumulada al Entregable</b></td>
					</tr>
					<tr align="center">
						<td nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
						<td nowrap="nowrap">
						    <input MontoIndProdvTri='1' class="center" name="txtIndProdEntregable[]" type="text" id="txtIndProdEntregable[]" value="<?php echo($row['ejec_mtames']);?>" size="4" onkeyup="TotalAvanceIndicador('<?php echo($RowIndex);?>');" />
					    </td>
					</tr>
					<tr>
						<td colspan="7" align="left" nowrap="nowrap">
						    <p class="nota">DESCRIPCIÓN</p>
						    <textarea name="txtIndProddes[]" class="obs" id="txtIndProddes[]"><?php echo($row['descripcion']);?></textarea>
							<p class="nota">LOGROS</p>
							<textarea name="txtIndProdlog[]" class="obs" id="txtIndProdlog[]"><?php echo($row['logros']);?></textarea>
							<p class="nota">DIFICULTADES</p>
							<textarea name="txtIndProddif[]" class="obs" id="txtIndProddif[]"><?php echo($row['dificultades']);?></textarea>
							<p class="nota">Observaciones del Gestor de Proyecto</p>
							<textarea name="txtIndProdobs[]" class="obs" id="txtIndProdobs[]"><?php echo($row['observaciones']);?></textarea>
						</td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }
    else {
        echo ("<span class='nota'>El Producto seleccionado [" . $idProd . "] no tiene registrado Indicadores...</span>");
        exit();
    }
    ?>
                </tbody>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="idComp" value="<?php echo($idComp);?>" />
			<input type="hidden" name="idProd" value="<?php echo($idProd);?>" />
			<input type="hidden" name="anio" value="<?php echo($anio);?>" />
			<input type="hidden" name="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
                function Guardar_AvanceIndProd()
                {
                    <?php $ObjSession->AuthorizedPage(); ?>
                    var BodyForm=$("#FormData").serialize();

                    if(BodyForm==""){alert("El Producto seleccionado, no Tiene indicadores"); return;}

                    if(confirm("Estas seguro de Guardar el avance de los Indicadores para el Informe?"))
                    {
                        var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_indicadores_producto'));?>";
                        var req = Spry.Utils.loadURL("POST", sURL, true, indActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                    }
                }

                function indActSuccessCallback(req)
                {
                    var respuesta = req.xhRequest.responseText;
                    respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                    var ret = respuesta.substring(0,5);
                    if(ret=="Exito")
                     {
                    	LoadIndicadoresProd();
                        alert(respuesta.replace(ret,""));
                     }
                    else
                    {alert(respuesta);}
                }

                function TotalAvanceIndicador(x)
                {
                    var index=parseInt(x) ;
                    var xTotal=document.getElementsByName("txtIndProdTot[]") ;
                    var xAcum =document.getElementsByName("txtIndProdAcum[]");
                    var xMes =document.getElementsByName("txtIndProdEntregable[]") ;

                    var mtaacum =parseFloat(xAcum[index].value);
                    var mtames =parseFloat(xMes[index].value);
                    if(isNaN(mtaacum)){mtaacum=0;}
                    if(isNaN(mtames)){mtames=0;}
                    var total=(mtaacum + mtames);
                    xTotal[index].value = total;
                }

            	$("input[MontoIndProdvTri='1']").numeric().pasteNumeric();
            	$('.ITAc:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
            </script>

		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>