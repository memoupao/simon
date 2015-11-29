<?php
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

$prod = explode(".", $idProd);
$idComp = $prod[0];
$idProd = $prod[1];

$objInf = new BLInformes();

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Actividades</title>
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
				<tr class="head">
					<th width="53" rowspan="2">Codigo</th>
					<th width="144" rowspan="2">Actividades</th>
					<th width="47" rowspan="2">U.M.</th>
					<th width="37" rowspan="2">Meta Planeada</th>
					<th height="28" colspan="3">Total al Entregable</th>
					<th width="209" rowspan="2">Observaciones del Supervisor</th>
				</tr>
				<tr class="head">
					<th width="70" height="28">Programado</th>
    				<th width="70">Ejecutado</th>
					<th width="35">%Ejec</th>
				</tr>
				<tbody class="data" bgcolor="#FFFFFF">
	            <?php
                $iRs = $objInf->listarActSE($idProy, $idComp, $idProd, $anio, $idEntregable);
                //$iRMIs = $objInf->ListaSubActividadesMExterno($idProy, $idActiv, $idNum, 1, $ini, $fin);
                //echo $idProy . '.' . $idActiv . '.' . $idNum;
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        //$rowMI = mysqli_fetch_assoc($iRMIs);
                        //$porcEjecucion = round((($rowMI['ejecutado'] / $rowMI['programado']) * 100), 2);
                        $porcEjecucion = round((($row['ejecutado'] / $row['programado']) * 100), 2);
                        if ($porcEjecucion > 100) {
                            $porcEjecucion = 100;
                        }
                        ?>
                	<tr class="center">
						<td align="left" nowrap="nowrap"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']);?></td>
						<td align="left">
						    <?php echo( $row['subactividad']);?>
                            <input name="listaIdAct[]" id="listaIdAct[]" type="hidden" value="<?php echo($row['t09_cod_sub']);?>" />
                        </td>
						<td><?php echo( $row['t09_um']);?></td>
						<td><?php echo( $row['plan_mtatotal']);?></td>
						<td><?php echo(round($row['programado'],2));?></td>
						<td><?php echo(round($row['ejecutado'],2));?></td>
						<td><?php echo($porcEjecucion);?>%</td>
						<td><textarea name="obsAct[]" id="obsAct[]" rows="2" class="AvanceSubActividades obs"><?php echo($row['obs']);?></textarea></td>
					</tr>
     <?php
        $RowIndex ++;
    }
    $iRs->free();
}
else {
    echo ("<span class='nota'>El Producto seleccionado [" . $idProd . "] no tiene registrado Actividades. Verificar el Plan Operativo</span>");
    exit();
}

?>
                </tbody>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="idComp" value="<?php echo($idComp);?>" />
			<input type="hidden" name="idProd" value="<?php echo($idProd);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
            <input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
            function Guardar_AvanceSubAct()
            {
                <?php $ObjSession->AuthorizedPage(); ?>

                var BodyForm=$("#FormData").serialize();

                if(BodyForm==""){alert("El Producto seleccionado no tiene Actividades"); return;}

                if(confirm("Estas seguro de guardar las observaciones de las Actividades para el Informe?"))
                {
                    var sURL = "inf_monext_process.php?action=<?php echo(md5('ajax_actividad'));?>";
                    var req = Spry.Utils.loadURL("POST", sURL, true, SubActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                }
            }

            function SubActSuccessCallback(req)
            {
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);
                if(ret=="Exito")
                {
                    LoadSubActividades();
                    alert(respuesta.replace(ret,""));
                }
                else
                {alert(respuesta);}
            }

            $('.MEISAc:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
            </script>
		</div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>