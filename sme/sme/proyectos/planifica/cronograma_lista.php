<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant('PATH_CLASS') . "BLProyecto.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
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

if ($ObjSession->MaxVersionProy($idProy) > $idVersion && $ObjSession->PerfilID != $objHC->Admin) {
    $lsDisabled = 'disabled="disabled"';    
} else {
    $lsDisabled = '';
}
if ($ObjSession->MaxVersionProy($idProy) > $idVersion && $ObjSession->PerfilID != $objHC->Admin) {
    $alsDisabled = 'onclick="return false"';
} else {
    $alsDisabled = '';
}

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Cronograma de Actividades</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<div id="divTableLista" class="TableGrid">
			<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td rowspan="2" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
					<td rowspan="2" align="center" style="border: solid 1px #CCC;">Cod.</td>
					<td width="217" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Actividad</td>
					<td width="72" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Unidad Medida</td>
					<td width="49" rowspan="2" align="center"
						style="border: solid 1px #CCC;">Meta Física</td>
					<td width="74" rowspan="2" align="center"
						style="border: solid 1px #CCC;">&iquest;Es Crítica?</td>
					<td height="26" colspan="5" align="center"
						style="border: solid 1px #CCC;">Metas Parciales</td>
				</tr>
				<tr class="SubtitleTable"
					style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td height="26" align="center" style="border: solid 1px #CCC;">Año 1</td>
					<td align="center" style="border: solid 1px #CCC;">Año 2</td>
					<td align="center" style="border: solid 1px #CCC;">Año 3</td>
					<td width="27" colspan="2" align="center" style="border: solid 1px #CCC;">&nbsp;</td>
				</tr>
				<tbody class="data">
      <?php

    $objML = new BLMarcoLogico();
    $aRs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);

    $AnioPOA = $objML->Proyecto->GetAnioPOA($idProy, $idVersion);

    $objPOA = new BLPOA();

    if (mysql_num_rows($aRs) > 0) {
        while ($ract = mysql_fetch_assoc($aRs)) {
            $idAct = $ract["t09_cod_act"];
			$nomItem = trim($ract["t09_act"]);
			if (empty($nomItem)) {
				continue;
			}
			
            ?>
      <tr class="RowData"
						style="background-color: #FC9; border: 1px #000;">
						<td align="left">&nbsp;</td>
						<td align="center"><?php echo($ract["t08_cod_comp"].'.'.$ract["t09_cod_act"]);?></td>
						<td colspan="4" align="left"><?php echo ($nomItem);?></td>
						<td width="99" align="center">&nbsp;</td>
						<td width="79" align="center">&nbsp;</td>
						<td width="85" align="center">&nbsp;</td>
						<td align="center" bgcolor="#FFFFFF" style="padding: 1px;"><a
							href="javascript:"> <input type="image"
								<?php echo($lsDisabled); ?>
								src="../../../img/nuevo.gif" style="border: 0px;" alt=""
								width="16" height="16" border="0" title="Nueva Actividad"
								onclick="btnNuevo_Clic('<?php echo($idAct);?>'); return false;" /></a></td>
					</tr>
      <?php
            $iRs = $objPOA->Lista_Subactividad($idProy, $idVersion, $idComp, $idAct);
            while ($row = mysqli_fetch_assoc($iRs)) {
                $metatot = $row["a1"] + $row["a2"] + $row["a3"];
                ?>

          <tr class="RowData"
						<?php if ($row["act_add"]=='1'){echo ("style='color:green; font-weight:bold'");}?>>
						<td height="22" align="left" nowrap="nowrap"><span> <a
								href="javascript:"> <input type="image"
									<?php echo($lsDisabled);  ?>
									src="../../../img/pencil.gif" alt="" width="12" height="13"
									border="0" style="border: 0px;" title="Editar Registro"
									onclick="btnEditar_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>'); return false;" />
							</a>
						</span></td>
						<td align="center"> <?php echo($row["comp"].'.'.$row["act"].'.'.$row["subact"]);?></td>
						<td align="left"><?php echo($row["descripcion"]);?></td>
						<td align="center"><?php echo($row["um"]);?></td>
						<td align="center"
							<?php if($row["meta"] > $metatot) {echo('style="background-color:#FF3"');}?>>
							<span>
			<?php echo(number_format($row["meta"]));?>
            </span>
						</td>
						<td align="center"><?php echo($row["critica"]);?></td>
            <?php if($AnioPOA <0) { ?>


			<td width="99" align="center"><a <?php echo($alsDisabled)?>
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','1');"
							title="Registrar Metas"></a><a <?php echo($alsDisabled)?>
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','1');"
							title="Registrar Metas"><?php echo(number_format($row["a1"]));?></a></td>

			<?php if($t02_num_mes > 12) { ?>
			<td width="79" align="center"><a <?php echo($alsDisabled)?>
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','2');"
							title="Registrar Metas"><?php echo(number_format($row["a2"]));?></a></td>
            <?php } else {?>
			<td width="79" align="center" style="background: #CCC;"></td>
             <?php }?>
			<?php if($t02_num_mes > 24) { ?>
			<td width="85" align="center"><a <?php echo($alsDisabled)?>
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','3');"
							title="Registrar Metas"><?php echo(number_format($row["a3"]));?></a></td>
            <?php } else {?>
			<td width="85" align="center" style="background: #CCC;"></td>
             <?php }?>

			<?php } else { ?>
            <td width="99" align="center">
            	<?php if($AnioPOA==1) { ?>
           	<a <?php echo($alsDisabled)?>
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','1');"
							title="Registrar Metas"><?php echo(number_format($row["a1"]));?></a>
           	<?php } else { echo(number_format($row["a1"])); }?></td>
						<td width="79" align="center"><?php if($AnioPOA==2) { ?>
              <a <?php echo($alsDisabled)?>
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','2');"
							title="Registrar Metas"><?php echo(number_format($row["a2"]));?></a>
                <?php } else { echo(number_format($row["a2"])); }?>
            </td>
						<td width="85" align="center">
            	<?php if($AnioPOA==3) { ?>
            	<a
							href="javascript:btnMetas_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','3');"
							title="Registrar Metas"><?php echo(number_format($row["a3"]));?></a>
                <?php } else { echo(number_format($row["a3"])); }?>
            </td>
            <?php } ?>

            <td align="center" style="padding: 1px;">
            <?php
                if ($row["meta"] > $metatot) {
                    echo ('<img src="../../../img/warning.jpg" width="15" height="15" border="0" title="La meta Total del Proyecto es: ' . $row["meta"] . ' y las metas mensuales, suman : ' . $metatot . '" />');
                }
                ?>

            </td>
						<td height="22" align="left" nowrap="nowrap">
						    <!-- <a href="javascript:">
						        <input
								type="image"
								<?php echo($lsDisabled); if($modificar){ echo " disabled";}?>
								src="../../../img/bt_elimina.gif" alt="" style="border: 0px;"
								width="12" height="13" border="0" title="Eliminar Registro"
								onclick="btnEliminar_Clic('<?php echo($row["act"]);?>','<?php echo($row["subact"]);?>','<?php echo($row["descripcion"]);?>'); return false;" />
						    </a> -->
					    </td>
					</tr>

      <?php

} // End While
            $iRs->free();
        }
        // $aRs->free();
    }     // End If
    else {
        ?>
      <tr class="RowData">
						<td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="left">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="center">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
						<td align="right">&nbsp;</td>
					</tr>
      <?php } ?>
    </tbody>
				<tfoot>
					<tr style="color: #FFF; font-size: 11px;">
						<th width="29">&nbsp;</th>
						<th width="49" height="18">&nbsp;</th>
						<th width="217">&nbsp;</th>
						<th width="72">&nbsp;</th>
						<th colspan="2" align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
						<th align="right">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input type="hidden"
				name="t02_version" value="<?php echo($idVersion);?>" /> <input
				type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
			<input type="hidden" name="t09_cod_act"
				value="<?php echo($idActiv);?>" />

		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>