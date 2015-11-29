<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>


<?php
require_once (constant('PATH_CLASS') . "BLManejoProy.class.php");

$action = $objFunc->__Request('action');
$proy = $objFunc->__Request('proy');
$cargo = $objFunc->__Request('cargo');
?>
<?php

$objEqui = new BLManejoProy();
$row = $objEqui->Personal_Seleccionar($proy, '1', $cargo);

if ($row != NULL) {
    ?>



<table width="786" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50" style="padding: 5px;"></td>
		<td width="736">
			<fieldset>
				<legend>Retribuciones del personal</legend>
				<table width="650" border="0" cellpadding="0" cellspacing="0"
					style="font-size: 10px; font-weight: normal;">
					<tr>
						<td width="18%" height="29" valign="top" class="col"><strong>TDR</strong></td>
						<td colspan="4"><?php echo( $row['t03_tdr']);?></td>
					</tr>
					<tr>
						<td height="28" valign="bottom"><strong>% Dedicación</strong></td>
						<td width="35%" valign="bottom"><?php echo( $row['t03_dedica']);?></td>
						<td colspan="2" valign="bottom"><strong>Permanencia Zona</strong></td>
						<td width="19%" valign="bottom"><?php echo( $row['t03_perma']);?></td>
					</tr>
					<tr>
						<td valign="bottom"><strong>Unidad de Medida</strong></td>
						<td valign="bottom"><?php echo( $row['descrip']);?></td>
						<td colspan="2" valign="bottom"><strong>Remuneración Bruta</strong></td>
						<td valign="bottom"><?php echo( $row['t03_remu']);?></td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>


<?php
}
?> 