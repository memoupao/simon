<?php
/**
 * CticServices
 *
 * Muestra el Cronograma de Productos
 *
 * @package     sme/proyectos/planifica
 * @author      AQ
 * @since       Version 2.0
 *
 */

include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$ObjSession->VerProyecto = $idVersion;
//$idVersion = 1;
$idComp = $objFunc->__Request('idComp');
$modif = $objFunc->__Request('modif');
$modificar = false;
$objProy = new BLProyecto();

if (md5("enable") == $modif) {
    $modificar = true;
}

$row = $objProy->ProyectoSeleccionar($idProy, $idVersion);
$t02_num_mes = $row['mes'];

$objHC = new HardCode();

/*if ($ObjSession->MaxVersionProy($idProy) > $idVersion && $ObjSession->PerfilID != $objHC->Admin) {
    $lsDisabled = 'disabled="disabled"';
} else {
    $lsDisabled = '';
}
if ($ObjSession->MaxVersionProy($idProy) > $idVersion && $ObjSession->PerfilID != $objHC->Admin) {
    $alsDisabled = 'onclick="return false"';
} else {
    $alsDisabled = '';
}*/

$objML = new BLMarcoLogico();
$entregables = $objML->listarEntregablesReporte($idProy, $idVersion);
$duracion = $objML->obtenerDuracion($idProy, $idVersion);

$objPOA = new BLPOA();
$progIndicadores = $objPOA->listarProgramacionIndicadores($idProy, $idVersion, $idComp);
$progCaracteristicas = $objPOA->listarProgramacionCaracteristicas($idProy, $idVersion, $idComp);

$colsAdicional = 0;

if ($objFunc->__QueryString() == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
	<title>Cronograma de Productos</title>
</head>
<body>
	<form id=frmMain name="frmMain" method="post" action="#">
<?php
}
?>

        <div id="divTableLista" class="cronprog">
			<table>
			    <thead>
    				<tr>
    					<td rowspan="2">&nbsp;</td>
    					<td rowspan="2">Cod.</td>
    					<td width="217" rowspan="2">Ind. / Caract.</td>
    					<td rowspan="2">UM</td>
    					<td rowspan="2">Meta</td>
    					<?php
        				    $i = 0;
        				    while ($duracion > $i) {
                                $i++;
                                $k = count($entregables[$i]);
                                $colsAdicional += $k;
                        ?>
                            <td height="26" colspan="<?php echo(MESES + $k);?>" class="row-anio-<?php echo(($i%2==0?'par':'impar'));?>">Año <?php echo($i);?></td>
        			     <?php } ?>
    				</tr>
    				<tr>
                        <?php
                            $i = 0;
                            while ($duracion > $i) {
                                $i++;
                                $j = 0;
                                while(MESES > $j){
                                    $j++;
                        ?>
    					<td class="row-anio-<?php echo(($i%2==0?'par':'impar'));?>"><?php echo($j);?></td>
    					<?php
    					        if (isset($entregables[$i][$j])){
                            ?>
                            <td rowspan="2" class="row-entregable">E</td>
                            <?php } ?>
                        <?php } } ?>
    				</tr>
    				<tr>
    				    <td>
    				        <a href="#"> <input type="image"    							
    							src="../../../img/pencil.gif"
    							title="Editar Entregables"
    							onclick="btnEstablecerEntregables(); return false;" />
                            </a>
                        </td>
                        <td width="49" colspan="4">ENTREGABLES</td>
                        <?php
                            $i = 0;
                            while ($duracion > $i) {
                                $i++;
                                $j = 0;
                                while(MESES > $j){
                                    $j++;
                        ?>
                            <td class="row-anio-<?php echo(($i%2==0?'par':'impar'));?>">&nbsp;</td>
                        <?php } } ?>
    				</tr>
				</thead>
				<tbody class="data">
                    <?php
                    $aRs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
                    $AnioPOA = $objML->Proyecto->GetAnioPOA($idProy, $idVersion);

                    if (mysql_num_rows($aRs) > 0) {
                        while ($ract = mysql_fetch_assoc($aRs)) {
                            $idAct = $ract["t09_cod_act"];
                    ?>
                  <tr class="RowData row-producto">
						<td>
							
						    <a href="#">
						        <input type="image"								
								src="../../../img/nuevo.gif" alt="" width="12" height="13" border="0" title="Agregar Indicador"
								onclick="btnAgregarIndicador('<?php echo($ract["t09_cod_act"]);?>'); return false;" />
                            </a>
                            
						</td>
						<td><?php echo($ract["t08_cod_comp"].'.'.$ract["t09_cod_act"]);?></td>
						<td colspan="<?php echo($duracion*MESES + 3 + $colsAdicional);?>" align="left"><?php echo($ract["t09_act"]);?></td>
					</tr>
                    <?php
                        $iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $idComp, $idAct);
                        while ($row = mysql_fetch_assoc($iRs)) {
                            $idInd = $row["t09_cod_act_ind"];
                            
                            $nomIndicador = trim($row["t09_ind"]);
                            if (empty($nomIndicador)) {
								continue;
							}
                    ?>
                    <tr class="RowData row-indicador">
						<td height="22" nowrap="nowrap">
							
						    <span>
						        <a href="#"> <input type="image"
									src="../../../img/nuevo.gif" alt="" width="12" height="13"
									border="0" style="border: 0px;" title="Agregar Característica"
									onclick="btnAgregarCaracteristica('<?php echo($row["t09_cod_act"]);?>','<?php echo($row["t09_cod_act_ind"]);?>'); return false;" />
                                </a>
                            </span>
                            
                            <span>
						        <a href="#"> <input type="image"
									src="../../../img/pencil.gif" alt="" width="12" height="13"
									border="0" style="border: 0px;" title="Editar Indicador"
									onclick="btnProgramarIndicador('<?php echo($row["t09_cod_act"]);?>','<?php echo($row["t09_cod_act_ind"]);?>'); return false;" />
                                </a>
                            </span>
                            
                        </td>
						<td> <span style="font-family: Tahoma;">I</span>.<?php echo($row["t08_cod_comp"].'.'.$row["t09_cod_act"].'.'.$row["t09_cod_act_ind"]);?></td>
						<td align="left"><?php echo $nomIndicador;?></td>
						<td><?php echo($row["t09_um"]);?></td>
						<td>
							<span>
			                    <?php echo(number_format($row["t09_mta"]));?>
                            </span>
						</td>
						<?php
                            $i = 0;
                            while ($duracion > $i) {
                                $i++;
                                $j = 0;

                                $lista = $objML->getProgramaIndicador($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_act_ind"], $i);

                                while(MESES > $j){
                                    $j++;
                                ?>
                                    <td class="row-indicador-<?php echo(($i%2==0?'par':'impar'));?>"><?php echo((array_key_exists($j, $lista)?$lista[$j]:''));?></td>
                                    <?php
                                    if (isset($entregables[$i][$j])){
                                        ?>
                                    <td class="row-entregable">
                                        <?php if($i >= $AnioPOA){
                                            $name = "indicadores[][".$idAct.", ".$row["t09_cod_act_ind"].", ".$i.", ".$j."]"; ?>
                                        <input type="text" id="<?php echo($name);?>" name="<?php echo($name);?>" size="3" class="prog" value="<?php echo($progIndicadores[$idAct][$row["t09_cod_act_ind"]][$i][$j]);?>"/>
                                        <?php }else{ ?>
                                        <?php echo($progIndicadores[$idAct][$row["t09_cod_act_ind"]][$i][$j]);?>
                                        <?php } ?>
                                    </td>
                                    <?php
                                    }
                                }
                            } ?>
					</tr>
					<?php
                        $cRs = $objML->listarCaracteristicas($idProy, $idVersion, $idComp, $idAct, $idInd);

                        while ($row = mysql_fetch_assoc($cRs)) {

							$nomCaracteristica = trim($row["t09_ind"]);
							if (empty($nomCaracteristica)) {
								continue;
							}
							
                    ?>
                    <tr class="RowData row-crct">
						<td height="22" nowrap="nowrap">
						    <span>
						        <a href="javascript:"> <input type="image"
									
									src="../../../img/pencil.gif" title="Editar Característica"
									onclick="btnProgramarCaracteristica('<?php echo($row["t09_cod_act"]);?>','<?php echo($row["t09_cod_act_ind"]);?>','<?php echo($row["t09_cod_act_ind_car"]);?>'); return false;" />
                                </a>
                            </span>
                        </td>
						<td> <span style="font-family: Tahoma;">C</span>.<?php echo($row["t08_cod_comp"].'.'.$row["t09_cod_act"].'.'.$row["t09_cod_act_ind"].'.'.$row["t09_cod_act_ind_car"]);?></td>
						<td align="left" colspan="3"><?php echo $nomCaracteristica;?></td>
						<?php
						    $progCaracteristica = $objML->listarProgramacionCaracteristicasReporte($idProy, $idVersion, $idComp, $idAct, $idInd, $row["t09_cod_act_ind_car"]);

                            $i = 0;
                            while ($duracion > $i) {
                                $i++;
                                $j = 0;
                                while(MESES > $j){
                                    $j++;
                        ?>
                            <td class="row-crct-<?php echo(($i%2==0?'par':'impar'));?>"><?php echo ((isset($progCaracteristica[$i][$j]))?'X':'');?></td>
                            <?php
                                if (isset($entregables[$i][$j])){
                                    if($i >= $AnioPOA){
                                        $name = "caracteristicas[][".$idAct.", ".$idInd.", ".$row["t09_cod_act_ind_car"].", ".$i.", ".$j."]";
                                        if (isset($progCaracteristicas[$idAct][$row["t09_cod_act_ind"]][$row["t09_cod_act_ind_car"]][$i][$j])) { ?>
                                            <td class="row-entregable"><input type="checkbox" id="<?php echo($name);?>" name="<?php echo($name);?>" class="prog row-entregable" checked/></td>
                                        <?php
                                        }
                                        else { ?>
                                            <td class="row-entregable"><input type="checkbox" id="<?php echo($name);?>" name="<?php echo($name);?>" class="prog"/></td>
                                <?php   }
                                    } else {
                                        if (isset($progCaracteristicas[$idAct][$row["t09_cod_act_ind"]][$row["t09_cod_act_ind_car"]][$i][$j])) { ?>
                                            <td class="row-entregable">X</td>
                                        <?php
                                        }
                                        else { ?>
                                            <td class="row-entregable">&nbsp;</td>
                                <?php   }
                                    } } } } ?>
					</tr>
                    <?php
                            }
                        }
                    }
                }
                ?>
                </tbody>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" class="prog" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" class="prog" />
		</div>
        <?php if($objFunc->__QueryString()=="") { ?>
    </form>
</body>
</html>
<?php } ?>
