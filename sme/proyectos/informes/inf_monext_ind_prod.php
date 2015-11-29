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

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Indicadores de Producto</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/template.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>

        <div id="divTableLista">
			<table width="100%" cellpadding="0" cellspacing="0" class="TableEditReg">
				<tr class="head">
					<th width="50" rowspan="2">Código</th>
					<th width="150" rowspan="2">Indicador de Producto /<br />Característica de Indicador</th>
					<th width="50" rowspan="2">U.M.</th>
					<th width="40" rowspan="2">Meta Total</th>
                    <th width="40" rowspan="2">Meta al Entregable</th>
                    <th width="40" rowspan="2">Reportado por la IE</th>
					<th width="40" rowspan="2">Avance Verificado</th>
					<th width="220" rowspan="2">Observaciones del Supervisor</th>
					<th width="220" rowspan="2">Participación de Beneficiarios</th>
					<th width="220" rowspan="2">Analisis del Avance Físico y Presupuestal</th>
				</tr>
				<tbody class="data">
                <?php
                $objInf = new BLInformes();
                $iRs = $objInf->listarIndicadoresProdSE($idProy, $idVersion, $idComp, $idProd, $anio, $idEntregable);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        ?>
    				<tr class="center row-indicador">
                        <td><?php echo("I.".$row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']);?></td>
						<td align="left"><?php echo( $row['indicador']);?>
          					<input type="hidden" name="prodInd[]" id="prodInd[]" value="<?php echo($row['t09_cod_act_ind']);?>" class="IndicadoresActividad" />
						</td>
						<td><?php echo($row['t09_um']);?></td>
						<td><?php echo($row['plan_mtatotal']);?></td>
						<td><?php echo($row['meta_al_entregable']);?></td>
						<!-- <td><?php echo($row['ejec_mtaacum']);?></td>
						<td><?php echo($row['ejec_mtatotal']);?></td>-->
						<td><?php echo($row['ejec_avance']);?></td>
						<td><input type="text" name="avances[]" id="avances[]" size="8" value="<?php echo($row['avance']);?>" class="center"/>
						<td><textarea name="observaciones[]" id="observaciones[]" rows="2" class="obs"><?php echo($row['obs']);?></textarea></td>
						
						<td><textarea name="participabenef[]" id="participabenef[]" rows="2" class="obs"><?php echo($row['partbenef']);?></textarea></td>
						<td><textarea name="avancefisicopres[]" id="avancefisicopres[]" rows="2" class="obs"><?php echo($row['avancefisicopres']);?></textarea></td>
						
                    </tr>
                    <?php
                        $caracteristicas = $objInf->listarCaracteristicasIndProdSE($idProy, $idVersion, $idComp, $idProd, $row['t09_cod_act_ind'], $anio, $idEntregable);
                        $k = 0;
                        if ($caracteristicas->num_rows > 0) {
                            while ($car = mysqli_fetch_assoc($caracteristicas)) {
                                $name = "[".$row["t09_cod_act_ind"]."][".$car['t09_cod_act_ind_car']."]";
                    ?>
                    <tr class="center row-crct">
                        <td><?php echo("C.".$row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind'].".".$car['t09_cod_act_ind_car']);?></td>
						<td align="left">
						    <?php echo( $car['nombre']);?>
						</td>
						<td><?php echo($row['t09_um']);?></td>
						<td><?php echo($row['plan_mtatotal']);?></td>
						<td><?php echo($row['meta_al_entregable']);?></td>
						<td>-</td>
						<td><input type="text" name="avancesCar<?php echo($name);?>" id="avancesCar<?php echo($name);?>" size="8" value="<?php echo($car['avance']);?>" class="center"/>
						<td><textarea name="observacionesCar<?php echo($name);?>" id="observacionesCar<?php echo($name);?>" rows="2" class="obs"><?php echo($car['obs']);?></textarea></td>
						
						<td>
						
						</td>
						
						<td>
							
						</td>
						
                    </tr>
                            <?php
                                $k ++;
                            }
                            $caracteristicas->free();
                        }
                        ?>

                 <?php
                        $RowIndex ++;
                    }
                    $iRs->free();
                }
                else {
                    echo ("<span class='nota'>El Producto seleccionado [" . $idProd . "] no tiene registrado Indicadores. Verificar el Marco Lógico</span>");
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
            function Guardar_AvanceIndProd()
            {
                <?php $ObjSession->AuthorizedPage(); ?>
                var BodyForm=$("#FormData").serialize();

                if(BodyForm==""){alert("El Producto seleccionado, no tiene indicadores"); return;}

                if(confirm("Estas seguro de guardar el avance de los Indicadores para el Informe ?"))
                {
                    var sURL = "inf_monext_process.php?action=<?php echo(md5('ajax_indicadores_prod'));?>";
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

            $('.MEIAc:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
            </script>
		</div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>