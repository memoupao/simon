<?php
include_once ("../../../includes/constantes.inc.php");
include_once ("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");

$idProy = $_GET['idProy'];
$idVersion = $_GET['idVersion'];
$idAnio = $_GET['idAnio'];
$idTrim = $_GET['idTrim'];
$dpto = $_GET['cbodpto'];
$prov = $_GET['cboprov'];
$dist = $_GET['cbodist'];
$case = $_GET['cbocase'];

$objPOA = new BLPOA();
$idVS = $objPOA->UltimaVersionPoa($idProy, $idAnio);

$rsMod = $objPOA->Lista_InfTrim_PlanCapac(1, $idProy, $idVS, NULL, NULL);
$arrMod = NULL;
$arrSub = NULL;
$arrTem = NULL;
?>
<table border="0" cellspacing="0" cellpadding="0" width="770">
	<!-- BEGIN HEADER -->
	<tbody class="data" bgcolor="#eeeeee">
		<tr>
			<td colspan="2" align="center" valign="middle"><strong>Datos del
					Beneficiario </strong></td>
				<?php
    while ($rm = mysqli_fetch_assoc($rsMod)) {
        $arrMod[] = $rm['codmodulo'];
        ?>
				<td colspan="<?php echo($rm['numsub']); ?>" align="center"
				valign="middle"><strong><?php echo( $rm['nommodulo']);?></strong></td>
				<?php
    } // while
    ?>
			</tr>
		<tr>
			<td width="3%" rowspan="2" align="center" valign="middle"><strong>DNI</strong>
			</td>
			<td rowspan="2" align="center" valign="middle"><strong>Apellidos y
					Nombres</strong></td>
				<?php
    for ($x = 0; $x < count($arrMod); $x ++) {
        $rsSub = $objPOA->Lista_InfTrim_PlanCapac(2, $idProy, $idVS, $arrMod[$x], NULL);
        while ($rsub = mysqli_fetch_assoc($rsSub)) {
            $arrSub[$arrMod[$x]][] = $rsub['codigo'];
            ?>
					<td colspan="<?php echo($rsub['numtema']);?>" align="center"
				valign="top"><strong><?php echo($rsub['codigo'].'<br>'.$rsub['t09_sub'])?></strong>
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
        $rsTema = $objPOA->Lista_InfTrim_PlanCapac(3, $idProy, $idVS, $arrMod[$x], $arrSub[$arrMod[$x]][$y]);
        while ($rtema = mysqli_fetch_assoc($rsTema)) {
            $codig = $arrSub[$arrMod[$x]][$y] . '.' . $rtema['t12_cod_tema'];
            $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_cod_tema'];
            $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_tem_espe'];
            ?>
				<td align="center" valign="middle" style="min-width: 120px;"><strong><?php echo($rtema['t12_tem_espe']); ?></strong>
				<input name="txtcodtemas[]" type="hidden" id="txtcodtemas[]"
				value="<?php echo($codig); ?>" class="PlanCapacitacion" /></td>
			<?php
        } // while
    } // for
} // for
?>
			</tr>
	</tbody>
	<!-- END HEADER -->

	<tbody class="data">
		<?php
$objInf = new BLInformes();
$iRsBenef = $objInf->InfTrim_Capac_Lista($idProy, $idAnio, $idTrim, $dpto, $prov, $dist, $case);
while ($rb = mysqli_fetch_assoc($iRsBenef)) {
    ?>
		<tr>
			<td width="3%" align="center" valign="middle"><input
				name="txtbenef[]" type="hidden" id="txtbenef[]"
				value="<?php echo($rb['t11_cod_bene']); ?>" class="PlanCapacitacion" />
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
			<td width="3%" valign="middle" align="center"><input type="hidden"
				name="txt_<?php echo($codig);?>[]" id="txt_<?php echo($codig);?>[]"
				class="PlanCapacitacion" maxlength="20"
				value="<?php echo($valor);?>"
				style="width: 20px; text-align: center; text-transform: uppercase;" />
				<input type="checkbox" name="chk_<?php echo($codig);?>[]"
				id="chk_<?php echo($codig);?>[]" class="PlanCapacitacion" value="1"
				onclick="ActivarPlanCapac('<?php echo($codig);?>');"
				subact="<?php echo($codig);?>"
				title="<?php echo($codig.' - '.$nomtema[$z]);?>"
				<?php if($valor=='1'){echo("checked");}?> /></td>
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