<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");

$objInf = new BLInformes();
$objPresup = new BLPresupuesto();
$objMP = new BLManejoProy();

$idVersion = $objPresup->Proyecto->MaxVersion($idProy);

error_reporting("E_PARSE");

$idProy = $objFunc->__Request('idProy');
$idComp = $objFunc->__Request('idComp');
$idAnio = $objFunc->__Request('idAnio');
$idMes = $objFunc->__Request('idMes');
$idFte = $objFunc->__Request('idFte');

$HardCode = new HardCode();
$IsMF = false;
if ($ObjSession->PerfilID == $HardCode->MF || $ObjSession->PerfilID == $HardCode->CMF) {
    $IsMF = true;
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Informe por Rubros de gasto</title>
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

<script language="javascript" type="text/javascript"
			src="../../../jquery.ui-1.5.2/jquery.numeric.js"></script>
		<script src="../../../js/commons.js" type="text/javascript"></script>

		<div id="divTableLista">
<?php
if ($idFte <= 0) {
    echo ("<br><br><b style='color:red;'>No se ha especificado la Fuente de Financiamiento </b><br><br>");
    return;
}
?>
<table width="780" border="0" cellpadding="0" cellspacing="0">
				<tbody class="data">
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="53" height="28" rowspan="2" align="center"
							valign="middle"><strong>Codigo</strong></td>
						<td width="197" height="28" rowspan="2" align="center"
							valign="middle"><strong>Categoria de Gastos</strong></td>
						<td width="57" rowspan="2" align="center" valign="middle"><strong>U.M.</strong></td>
						<td width="41" rowspan="2" align="center" valign="middle"><strong>Meta
								Anual</strong></td>
						<td width="80" rowspan="2" align="center" valign="middle"><strong>
								Presupuesto Anual</strong></td>
						<td width="80" rowspan="2" align="center" valign="middle"><strong>Total
          <?php if($idFte==$HardCode->codigo_Fondoempleo){echo("Fondoempleo");} else {echo("Fuente Financ");} ?>
        </strong></td>
						<td colspan="2" align="center" valign="middle"><strong>Información
								del Mes</strong></td>
						<td colspan="2" rowspan="2" align="center" valign="middle"><strong>Observaciones</strong></td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="73" align="center" valign="middle"><strong>Planeado</strong></td>
						<td width="81" align="center" valign="middle"><strong>Ejecutado</strong></td>
					</tr>
				</tbody>
        <?php
        $idAct = 1;
        $total_presup = 0;
        $total_planeado = 0;
        $total_ejecutado = 0;
        $total_fuente = 0;

        $rsPer = $objMP->Inf_Financ_Lista_Personal_Total($idProy, $idAnio, $idMes, $idFte);
        $rowPer = mysqli_fetch_assoc($rsPer);
        $total_presup += $rowPer['gasto_tot'];
        $total_fuente += $rowPer['total_fuente'];
        $total_planeado += $rowPer['planeado'];
        $total_ejecutado += $rowPer['ejecutado'];
        $rsPer->free();

        ?>
        <tbody class="data">
					<tr style="background-color: #FC9; height: 25px; cursor: pointer;"
						onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
						<td align="left" nowrap="nowrap"><?php echo($HardCode->CodigoMP.".1");?></td>
						<td colspan="3" align="left"><?php echo( "Personal del Proyecto");?></td>
						<td align="right"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowPer['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF"
					id="<?php echo("tbody_".$idFte.'_'.$idAct);?>">
					<tr style="background-color: #E3FEE0;">
						<td align="left" nowrap="nowrap"><?php echo($HardCode->CodigoMP.".1.12");?></td>
						<td colspan="3" align="left"><?php echo( "Remuneraciones");?></td>
						<td align="right"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
						<td align="right"><strong><?php echo(number_format($rowPer['total_fuente'],2));?></strong></td>
						<td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
        <?php
        $rsPer = $objMP->Inf_Financ_Lista_Personal($idProy, $idAnio, $idMes, $idFte);
        while ($rowPer = mysqli_fetch_assoc($rsPer)) {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";
            $inputTextAvance = "<input gastosEjemp='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' " . ($rowPer['total_fuente'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " value='" . $rowPer['ejecutado'] . "' size='4' class='presup' />";
            $inputTextID = "<input  value='" . $rowPer['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowPer['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            // $inputTextObs = "<input value='".$rowPer['observ']."' name='".$idFte."_Obs[]' type='hidden' id='".$idFte."_Obs[]' class='presup' />" ;
            $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowPer['observ'] . "</textarea>";

            ?>
        <tr style='color: navy;'>
						<td align="left" nowrap="nowrap"><?php echo($rowPer['codigo']);?></td>
						<td align="left"><?php echo($rowPer['cargo']);?></td>
						<td align="center"><?php echo($rowPer['um']);?></td>
						<td align="center"><?php echo($rowPer['meta']); ?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['total_fuente'],2));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['planeado'],2));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center" nowrap="nowrap">
        <?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowPer['observ']);} ?>
		<?php echo($inputTextID); echo($inputTextEst); ?></td>
					</tr>
        <?php

}
        $rsPer->free();
        ?>
        </tbody>
         <?php
        $idAct = 2;
        $rsEqui = $objMP->Inf_Financ_Lista_Equipamiento_Total($idProy, $idAnio, $idMes, $idFte);
        $rowEqui = mysqli_fetch_assoc($rsEqui);
        $total_presup += $rowEqui['gasto_tot'];
        $total_planeado += $rowEqui['planeado'];
        $total_fuente += $rowEqui['total_fuente'];
        $total_ejecutado += $rowEqui['ejecutado'];
        $rsEqui->free();

        ?>
        <tbody class="data">
					<tr style="background-color: #FC9; height: 25px; cursor: pointer;"
						onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
						<td align="left" nowrap="nowrap"><?php echo($HardCode->CodigoMP.".$idAct");?></td>
						<td colspan="3" align="left"><?php echo( "Equipamiento del Proyecto");?></td>
						<td align="right"><?php echo(number_format($rowEqui['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqui['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqui['ejecutado'],2));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
				</tbody>

				<tbody class="data" bgcolor="#FFFFFF"
					id="<?php echo("tbody_".$idFte.'_'.$idAct);?>">
					<tr style="background-color: #E3FEE0;">
						<td align="left" nowrap="nowrap"><?php echo($HardCode->CodigoMP.".$idAct.3");?></td>
						<td colspan="3" align="left"><?php echo( "Equipos y Bienes Duraderos");?></td>
						<td align="right"><?php echo(number_format($rowEqui['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqui['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowEqui['ejecutado'],2));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
        <?php
        $rsEqui = $objMP->Inf_Financ_Lista_Equipamiento($idProy, $idAnio, $idMes, $idFte);
        while ($rowEqui = mysqli_fetch_assoc($rsEqui)) {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";
            $inputTextAvance = "<input gastosEjemp='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' " . ($rowEqui['total_fuente'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " value='" . $rowEqui['ejecutado'] . "' size='4' class='presup' />";
            $inputTextID = "<input  value='" . $rowEqui['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowEqui['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            // $inputTextObs = "<input value='".$rowEqui['observ']."' name='".$idFte."_Obs[]' type='hidden' id='".$idFte."_Obs[]' class='presup' />" ;
            $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowEqui['observ'] . "</textarea>";
            ?>
        <tr style='color: navy;'>
						<td align="left" nowrap="nowrap"><?php echo($rowEqui['codigo']);?></td>
						<td align="left"><?php echo($rowEqui['equipo']);?></td>
						<td align="center"><?php echo($rowEqui['um']);?></td>
						<td align="center"><?php echo($rowEqui['meta']); ?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['costo_tot'],2));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['planeado'],2));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center" nowrap="nowrap">
		<?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowsubCateg['observ']);} ?>
		<?php echo($inputTextID); echo($inputTextEst); ?></td>

					</tr>
        <?php
        }
        $rsEqui->free();
        ?>
        </tbody>



      <?php
    $total_ejec = 0;
    $rsGF = $objMP->Inf_Financ_Lista_GastoFuncionamiento_Total($idProy, $idAnio, $idMes, $idFte);
    $rowGF = mysqli_fetch_assoc($rsGF);
    $rsGF->free();
    $idAct = 3;
    $total_presup += $rowGF['gasto_tot'];
    $total_planeado += $rowGF['planeado'];
    $total_fuente += $rowGF['total_fuente'];
    $total_ejecutado += $rowGF['ejecutado'];

    ?>
       <tbody class="data">
					<tr style="background-color: #FC9; height: 25px; cursor: pointer;"
						onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
						<td align="left" nowrap="nowrap"><?php echo($HardCode->CodigoMP.".$idAct");?></td>
						<td colspan="3" align="left"><?php echo( "Gastos de Funcionamiento");?></td>
						<td align="right"><?php echo(number_format($rowGF['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowGF['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowGF['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowGF['ejecutado'],2));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF"
					id="<?php echo("tbody_".$idFte.'_'.$idAct);?>">
    <?php
    $iRs = $objMP->Inf_Financ_Lista_PartidasGF($idProy, $idAnio, $idMes, $idFte);
    while ($rowsub = mysqli_fetch_assoc($iRs)) {
        ?>
        <tr style="background-color: #E3FEE0;">
						<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
						<td align="left"><?php echo( $rowsub['partida']);?></td>
						<td align="center"><?php echo($rowsub['um']);?></td>
						<td align="center"><?php echo($rowsub['meta']);?></td>
						<td align="right"><?php echo(number_format($rowsub['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowsub['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowsub['planeado'],2));?></td>
						<td align="right"><?php echo(number_format($rowsub['ejecutado'],2));?></td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
      <?php
        $iRsCateg = $objMP->Inf_Financ_Lista_CategoriasGF($idProy, $idAnio, $idMes, $rowsub['idpartida'], $idFte);
        while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";

            $inputTextAvance = "<input gastosEjemp='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' " . ($rowsubCateg['total_fuente'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " value='" . $rowsubCateg['ejecutado'] . "' size='4' class='presup' />";
            $inputTextID = "<input  value='" . $rowsubCateg['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowsubCateg['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowsubCateg['observ'] . "</textarea>";
            // /*
            $styleRow = 'style="font-weight:300; color:navy;"';
            if ($rowsubCateg['t41_estado'] == '2')             // Verificar que el MF no haya ingresado comentarios
            {
                $styleRow = 'style="font-weight:300; color:red;"';
            }
            // */

            ?>
    <tr <?php echo($styleRow);?>>
						<td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
						<td align="left"><?php echo($rowsubCateg['categoria']);?></td>
						<td align="center">&nbsp;</td>
						<td align="center"><?php echo($inputTextID); ?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['gasto_tot'],2));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_fuente'],2));?></td>
						<td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['planeado'],2));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center">
         <?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowsubCateg['observ']);} ?>
        </td>
					</tr>
      <?php } $iRsCateg->free(); // Fin de Categorias de Gastos ?>
      <?php } $iRs->free(); // Fin de Actividades ?>
      </tbody>
				<!-- Gastos Administartivos -->
				<tbody class="data">
        <?php
        $idAct = 4;
        $iRsG = $objMP->Inf_Financ_Lista_GastosAdministrativos($idProy, $idAnio, $idMes, $idFte);
        $rowG = mysqli_fetch_assoc($iRsG);
        $iRsG->free();

        $total_fuente += $rowG['total_fuente'];
        $total_presup += $rowG['gasto_tot'];
        $total_planeado += $rowG['planeado'];
        $total_fuente += $rowG['total_fuente'];
        $total_ejecutado += $rowG['ejecutado'];

        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";

        $inputTextAvance = "<input gastosEjemp='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' " . ($rowG['total_fuente'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " value='" . $rowG['ejecutado'] . "' size='4' class='presup' />";
        $inputTextID = "<input  value='" . $rowG['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowG['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        // $inputTextObs = "<input value='".$rowG['observ']."' name='".$idFte."_Obs[]' type='hidden' id='".$idFte."_Obs[]' class='presup' />" ;
        $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowG['observ'] . "</textarea>";

        ?>
        <tr
						style="background-color: #FC9; height: 25px; cursor: pointer;">
						<td align="left" nowrap="nowrap"><?php echo($rowG['codigo']);?></td>
						<td colspan="3" align="left"><?php echo( $rowG['descripcion']);?></td>
						<td align="right"><?php echo(number_format($rowG['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowG['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowG['planeado'],2));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center" nowrap="nowrap">
          <?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowG['observ']);} ?>
		  <?php echo($inputTextID); echo($inputTextEst); ?>

          </td>
					</tr>
                <!-- Imprevistos -->
          <?php
        $idAct = 6;

        $iRsI = $objMP->Inf_Financ_Lista_Imprevistos($idProy, $idAnio, $idMes, $idFte);
        $rowI = mysqli_fetch_assoc($iRsI);
        $iRsI->free();

        $total_presup += $rowI['gasto_tot'];
        $total_planeado += $rowI['planeado'];
        $total_fuente += $rowI['total_fuente'];
        $total_ejecutado += $rowI['ejecutado'];

        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";

        $inputTextAvance = "<input gastosEjemp='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' " . ($rowI['total_fuente'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " value='" . $rowI['ejecutado'] . "' size='4' class='presup' />";
        $inputTextID = "<input  value='" . $rowI['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowI['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        // $inputTextObs = "<input value='".$rowI['observ']."' name='".$idFte."_Obs[]' type='hidden' id='".$idFte."_Obs[]' class='presup' />" ;
        $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowI['observ'] . "</textarea>";

        ?>
        <tr
						style="background-color: #FC9; height: 25px; cursor: pointer;">
						<td align="left" nowrap="nowrap"><?php echo($rowI['codigo']);?></td>
						<td colspan="3" align="left"><?php echo( $rowI['descripcion']);?></td>
						<td align="right"><?php echo(number_format($rowI['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowI['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowI['planeado'],2));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center" nowrap="nowrap">
          <?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowI['observ']);} ?>
		  <?php echo($inputTextID); echo($inputTextEst); ?></td>
					</tr>
					<!-- Supervisión -->

		<?php
        $idAct = 7;

        $iRsSup = $objMP->infFinancGastosSupervision($idProy, $idAnio, $idMes, $idFte);
        $rowSup = mysqli_fetch_assoc($iRsSup);
        $iRsSup->free();

        $total_presup += $rowSup['gasto_tot'];
        $total_planeado += $rowSup['planeado'];
        $total_fuente += $rowSup['total_fuente'];
        $total_ejecutado += $rowSup['ejecutado'];

        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";

        $inputTextAvance = "<input gastosEjemp='1' name='" . $idtxtAvance . "' type='text' id='" . $idtxtAvance . "' style='padding:0px; width:100%; height:100%; text-align:center;' " . ($rowSup['total_fuente'] == 0 ? 'readonly' : '') . " " . ($IsMF == true ? 'disabled' : '') . " value='" . $rowSup['ejecutado'] . "' size='4' class='presup' />";
        $inputTextID = "<input  value='" . $rowSup['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowSup['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        // $inputTextObs = "<input value='".$rowSup['observ']."' name='".$idFte."_Obs[]' type='hidden' id='".$idFte."_Obs[]' class='presup' />" ;
        $inputTextObs = "<textarea name='" . $idFte . "_Obs[]' id='" . $idFte . "_Obs[]' class='presup' style='width:99%; height:99%;' >" . $rowSup['observ'] . "</textarea>";

        ?>
        <tr
						style="background-color: #FC9; height: 25px; cursor: pointer;">
						<td align="left" nowrap="nowrap"><?php echo($rowSup['codigo']);?></td>
						<td colspan="3" align="left"><?php echo( $rowSup['descripcion']);?></td>
						<td align="right"><?php echo(number_format($rowSup['gasto_tot'],2));?></td>
						<td align="right"><?php echo(number_format($rowSup['total_fuente'],2));?></td>
						<td align="right"><?php echo(number_format($rowSup['planeado'],2));?></td>
						<td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
						<td colspan="2" align="center" nowrap="nowrap">
          <?php if($IsMF) { echo($inputTextEst); echo($inputTextObs); } else {echo($rowSup['observ']);} ?>
		  <?php echo($inputTextID); echo($inputTextEst); ?></td>
					</tr>

				</tbody>
				<tfoot>
					<tr style="color: #FFF; height: 20px;">
						<th>&nbsp;</th>
						<th colspan="3" align="right">Totales x Componente &nbsp;&nbsp;</th>
						<th align="right"><?php echo(number_format($total_presup,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_fuente,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
						<th align="right"><?php echo(number_format($total_ejecutado,2,'.',','));?>&nbsp;</th>
						<th width="99" align="right">&nbsp;</th>
						<th width="78" align="right">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" class='presup' /> <input
				type="hidden" name="t02_version" value="<?php echo($idVersion);?>"
				class='presup' /> <input type="hidden" name="t08_cod_comp"
				value="<?php echo($idComp);?>" class='presup' /> <input
				type="hidden" name="t40_anio" id="t40_anio"
				value="<?php echo($idAnio);?>" class='presup' /> <input
				type="hidden" name="t40_mes" id="t40_mes"
				value="<?php echo($idMes);?>" class='presup' />

			<script language="javascript" type="text/javascript">
	function ShowActividad(idAct)
	 {
		 var id = "#" + idAct ;
		 $(id).toggle();
   		 return false;
	 }


	 function TotalAvanceSubActividad(x)
		{
		  var index=parseInt(x) ;
		  /*
		  var xTotal=$("input[name=txtSubActTot[]]") ;
		  var xAcum =$("input[name=txtSubActAcum[]]") ;
  		  var xMes =$("input[name=txtSubActMes[]]") ;
		  */
		  var xTotal=document.getElementsByName("txtSubActTot[]") ;
		  var xAcum =document.getElementsByName("txtSubActAcum[]");
  		  var xMes =document.getElementsByName("txtSubActMes[]") ;

		  var mtaacum =parseFloat(xAcum[index].value) ;
		  var mtames =parseFloat(xMes[index].value) ;
		  if(isNaN(mtaacum)){mtaacum=0;}
		  if(isNaN(mtames)){mtames=0;}
  		  var total=(mtaacum + mtames) ;
		  xTotal[index].value = total ;

   		}

		$("input[gastosEjemp='1']").numeric().pasteNumeric();
	</script>

		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>