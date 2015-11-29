<?php
/**
 * CticServices
 *
 * Lista el Plan de Capacitación para el Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include_once ("../../../includes/constantes.inc.php");
include_once ("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");

$idProy = $_GET['idProy'];
$idVersion = $_GET['idVersion'];
$anio = $_GET['anio'];
$idEntregable = $_GET['idEntregable'];
$partes = explode("-", $_GET['idEntregable']);
$idEntregable = $partes[1];
$dpto = $_GET['cbodpto'];
$prov = $_GET['cboprov'];
$dist = $_GET['cbodist'];
$case = $_GET['cbocase'];

$objPOA = new BLPOA();
//$idVS = $objPOA->UltimaVersionPoa($idProy, $anio);

$rsMod = $objPOA->listarPlanCapac(1, $idProy, $idVersion, NULL, NULL);
$arrMod = NULL;
$arrSub = NULL;
$arrTem = NULL;
?>
<table border="0" cellspacing="0" cellpadding="0" width="770">
	<tbody class="data" bgcolor="#eeeeee">
		<tr>
			<td colspan="2" align="center" valign="middle"><b>Datos del Beneficiario </b></td>
			<?php
            while ($rm = mysqli_fetch_assoc($rsMod)) {
                $arrMod[] = $rm['codmodulo'];
            ?>
			<td colspan="<?php echo($rm['numsub']); ?>" align="center" valign="middle">
			    <b><?php echo( $rm['nommodulo']);?></b>
		    </td>
			<?php
            } // while
            ?>
		</tr>
		<tr>
			<td width="3%" rowspan="2" align="center" valign="middle"><b>DNI</b></td>
			<td rowspan="2" align="center" valign="middle"><b>Apellidos y Nombres</b></td>
			<?php
            for ($x = 0; $x < count($arrMod); $x ++) {
                $rsSub = $objPOA->listarPlanCapac(2, $idProy, $idVersion, $arrMod[$x], NULL);
                while ($rsub = mysqli_fetch_assoc($rsSub)) {
                    $arrSub[$arrMod[$x]][] = $rsub['codigo'];
            ?>
			<td colspan="<?php echo($rsub['numtema']);?>" align="center" valign="top">
			    <b><?php echo($rsub['codigo'].'<br>'.$rsub['t09_sub'])?></b>
	        </td>
			<?php
                } // while
            } // for
            ?>
		</tr>
		<tr>
		<?php
        for ($x = 0; $x < count($arrMod); $x ++) {
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $rsTema = $objPOA->listarPlanCapac(3, $idProy, $idVersion, $arrMod[$x], $arrSub[$arrMod[$x]][$y]);
                while ($rtema = mysqli_fetch_assoc($rsTema)) {
                    $codig = $arrSub[$arrMod[$x]][$y] . '.' . $rtema['t12_cod_tema'];
                    $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_cod_tema'];
                    $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_tem_espe'];
                    ?>
        				<td align="center" valign="middle" style="min-width: 120px;"><b><?php echo($rtema['t12_tem_espe']); ?></b>
        				<input name="txtcodtemas[]" type="hidden" id="txtcodtemas[]"
        				value="<?php echo($codig); ?>" class="PlanCapacitacion" /></td>
        			<?php
                } // while
            } // for
        } // for
        ?>
		</tr>
	</tbody>
	<tbody class="data">
	<?php
    $objInf = new BLInformes();
    $iRsBenef = $objInf->listarPlanCapacInfEntregable($idProy, $idVersion, $anio, $idEntregable, $dpto, $prov, $dist, $case);
    while ($rb = mysqli_fetch_assoc($iRsBenef)) {
    ?>
		<tr>
			<td width="3%" align="center" valign="middle">
		        <input name="txtbenef[]" type="hidden" id="txtbenef[]" value="<?php echo($rb['t11_cod_bene']); ?>" class="PlanCapacitacion" />
				<?php echo($rb['t11_dni']); ?>
			</td>
			<td valign="middle" style="min-width: 250px;">
				<?php echo($rb['nombres']); ?>
			</td>
			<?php
            for ($x = 0; $x < count($arrMod); $x ++) {
                for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                    $arritem = $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]];
                    $nomtema = $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]];
                    for ($z = 0; $z < count($arritem); $z ++) {
                        $codig = $arrSub[$arrMod[$x]][$y] . '.' . $arritem[$z];
                        $valor = $rb[$codig];
            ?>
			<td width="3%" valign="middle" align="center">
			    <input type="hidden" name="txt_<?php echo($codig);?>[]" id="txt_<?php echo($codig);?>[]" class="PlanCapacitacion"
			    maxlength="20" value="<?php echo($valor);?>" style="width: 20px; text-align: center; text-transform: uppercase;" />
				<input type="checkbox" name="chk_<?php echo($codig);?>[]" id="chk_<?php echo($codig);?>[]" class="PlanCapacitacion"
				value="1" onclick="ActivarPlanCapac('<?php echo($codig);?>');" subact="<?php echo($codig);?>"
				title="<?php echo($codig.' - '.$nomtema[$z]);?>" <?php if($valor=='1'){echo("checked");}?> />
			</td>
			<?php
            } // for
        } // for
    } // for
    ?>
		</tr>
<?php
} // while
?>
    </tbody>
</table>