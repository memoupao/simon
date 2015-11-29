<?php 
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$objProy = new BLProyecto();

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Gastos Administrativos del Proyecto</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery.numeric.js"></script>
</head>
<body>
    <form action="#" method="post" enctype="multipart/form-data"
        name="frmMain" id="frmMain">
  <?php
}
?>
  <div id="toolbar" style="height: 4px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="17%"><button class="Button"
                            onclick="LoadLineaBase(true); return false;"
                            value="Recargar Listado">Refrescar Datos</button></td>
                    <td width="16%"><button class="Button"
                            onclick="GuardarLineaBaseImprevistos(); return false;"
                            value="Guardar Costos" id="btnGuardarLB">Guardar</button></td>
                    <td width="5%">&nbsp;</td>
                    <td width="13%">&nbsp;</td>
                    <td width="43%" align="right"><span
                        style="color: #036; font-weight: bold; font-size: 12px;">Linea de Base / Imprevistos</span></td>
                    <td width="6%" align="right">&nbsp;</td>
                </tr>
            </table>
        </div>
        <div id="divTableLista" class="TableGrid">
<?php
    $objMP = new BLManejoProy();
    $objPres = new BLPresupuesto();
    $objHC = new HardCode();
    $objML = new BLMarcoLogico();

    $iRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
    $campos = $objMP->iGetArrayFields($iRs);
    unset($campos[1]);
    unset($campos[0]);
    $numftes = count($campos);
    $sumaTotal = 0;
    $vs = 1;
    $costoPersonalInicial = $objMP->Personal_GastoTotal($idProy, $vs);
    $costoTotalEquipamiento = $objMP->Equipamiento_CostoTotal($idProy, $vs);
    $costoTotalFunc = $objMP->Funcionamiento_CostoTotal($idProy, $vs);
    $costoTotalComp = $objMP->Componentes_CostoTotal($idProy, $vs);
    $costoAdminist = $objMP->Adminis_CostoTotal($idProy, $vs);

    $anAnioPOA = $objProy->GetAnioPOA($idProy, $idVersion);
    $aAdmisCostoTotal = $objMP->Adminis_CostoTotal($idProy, $idVersion);
    $aCompoCostoTotal = $objMP->Componentes_CostoTotal($idProy, $idVersion);
    $aPersGastoTotal = $objMP->Personal_GastoTotal($idProy, $idVersion);
    $aEquipCostoTotal = $objMP->Equipamiento_CostoTotal($idProy, $idVersion);
    $aFuncCostoTotal = $objMP->Funcionamiento_CostoTotal($idProy, $idVersion);

    $res = $objMP->listarCostosIndirectos($idProy, $vs);
    $lineaBaseInicial = $res['linea_base'];
    $imprevistosInicial = $res['imprevistos'];
    $supervisionInicial = $res['supervision'];

    //$duracion = $objML->obtenerDuracion($idProy, $idVersion);
    $factorDuracion = $objML->obtenerFactorDuracion($idProy, $idVersion);
    // $duracionEnElAnio = $objML->obtenerDuracionEnElAnio($idProy, $idVersion);

    $res2 = $objMP->listarCostosIndirectos($idProy, $idVersion);

    $lineaBase = $res2['linea_base'];
    $imprevistos = $res2['imprevistos'];
    $supervision = $res2['supervision'];

    $imprevistosMax = round($res2['impmax']*$factorDuracion, 2);

    $datos = $objMP->listarTasasParametros($idProy, $idVersion);

    $costosDirectos = $objMP->GetCostosDirectos($idProy, $idVersion);
    $costosIndirectos = $objMP->GetCostosInDirectos($idProy, $idVersion);
    $costosTotales = $costosDirectos + $costosIndirectos;

    $costosDirectosInicial = $objMP->GetCostosDirectos($idProy, $vs);
    $costosIndirectosInicial = $objMP->GetCostosInDirectos($idProy, $vs);
    $costosTotalesInicial = $costosDirectosInicial + $costosIndirectosInicial;

?>
    <table width="780" border="0" cellpadding="0" cellspacing="0">
        <tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
            <td width="604" height="26" rowspan="2" style="border: solid 1px #CCC;">&nbsp;&nbsp;</td>
            <td width="84" rowspan="2" align="center" style="border: solid 1px #CCC;">Total Costos Directos</td>
            <td colspan="<?php echo($numftes);?>" align="center">Financiamiento</td>
        </tr>
        <tr class="SubtitleTable"
            style="border: solid 1px #CCC; background-color: #eeeeee;">
        <?php
        for ($col == 0; $col < $numftes; $col ++) {
            ?>
            <td width="90" align="center" style="border: solid 1px #CCC;"><?php echo($campos[$col+2]);?></td>
        <?php
        }
        ?>
        </tr>
        <tbody class="data">
        <?php
        $sum_total = 0;
        if ($iRs->num_rows > 0) {
            $sumaFE = 0;
            while ($row = mysqli_fetch_assoc($iRs)) {
                $sum_total += round($row["costo_total"], 2);
                $col = 0;
                for ($col == 0; $col < $numftes; $col ++) {
                    $field = $campos[$col + 2];
                    $sum_fte[$col] += $row[$field];
                }
                $sumaFE += $row[$objHC->Nombre_Fondoempleo];
            }
            $iRs->free();
        }
        ?>
            <tr style="">
                <td height="18">Total de Costos Directos y Aportes por Fuente de Financiamiento</td>
                <td align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</td>
                <?php
                $col = 0;
                // Nombre_Fondoempleo
                for ($col == 0; $col < $numftes; $col ++) {
                    $sumaTotal += $sum_fte[$col];
                ?>
                        <td align="right"><?php echo(number_format($sum_fte[$col],2));?>&nbsp;</td>
                <?php
                }
                ?>
            </tr>
        </tbody>
    </table>
    <span style="background-color: #FF0; color: #F00; font-size: 11px; font-family: Arial, Helvetica, sans-serif">
    <?php
    if (number_format($sum_total, 2) != number_format($sumaTotal, 2)) {
        echo ("<b>Error:</b> <br>Las Fuentes de Financiamiento no corresponden al Costo total del Proyecto. " . "<br>Verifique los aportes de Fuentes de Financiamiento para los Costos Operativos y luego clic en Refrescar Costos.");
    }
    ?>
    </span> <br />
    <div class="TableGrid" style="width: 400px; display: inline-table;">
        <table width="386" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td height="23" colspan="2">La Línea de Base y Evaluación de Impacto representa el <?php echo ($datos['t02_porc_linea_base']);?>% de Costos directos</td>
                </tr>
            </thead>
            <tbody class="data">
                <tr>
                    <td width="302" height="21">&nbsp;</td>
                    <td align="center"><b>Monto Real</b></td>
                </tr>
                <tr>
                    <td height="21">
                        Monto de la Linea de base y Evaluación de Impacto (<?php echo ($datos['t02_porc_linea_base']);?>%)
                        <input type="hidden" name="txtMontoLB" id="txtMontoLB" value="<?php echo($lineaBase);?>" class="LineaBase" />
                    </td>
                    <td width="82" align="center">
                        <?php echo(number_format($lineaBase,2));?>
                    </td>
                </tr>
                <tr>
                    <th height="18" colspan="5"></th>
                </tr>
            </tbody>
        </table>
        <table width="387" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <td height="23" colspan="3">Los imprevistos no deben exceder el <?php echo ($datos['t02_porc_imprev']);?>% de los Costos Directos</td>
                </tr>
            </thead>
            <tbody class="data">
                <tr>
                    <td height="21">&nbsp;</td>
                    <td align="center" nowrap="nowrap"><b>Monto Máximo</b></td>
                    <td align="center"><b>Monto Real</b></td>
                </tr>
                <tr>
                    <td width="212" height="21">Monto de Imprevistos (<?php echo ($datos['t02_porc_imprev']);?>%)</td>
                    <td width="91" align="center">
                        <?php echo(number_format($imprevistosMax,2));?>
                    </td>
                    <td width="82" align="center">
                        <input id="txtMontoImprevistos" name="txtMontoImprevistos" type="text" class="LineaBase roundDec" style="text-align: right" value="<?php echo(number_format($imprevistos, 2));?>" size="14" onkeyup="ValidarMonto();" />
                        <input id="txtImp" type="hidden" value="<?php echo($imprevistosMax);?>"/>
                    </td>
                </tr>
            </tbody>
            <tr>
                <th height="18" colspan="5" align="left" id="estilolb" style="padding-left: 4px;"></th>
            </tr>
        </table>
        <table width="387" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <td height="23" colspan="2">Los Gastos de Supervisión de Proyectos <?php echo ($datos['t02_proc_gast_superv']);?>%</td>
                </tr>
            </thead>
            <tbody class="data">
                <tr>
                    <td height="21">&nbsp;</td>
                    <td align="center"><b>Monto Real</b></td>
                </tr>
                <tr>
                    <td width="212" height="21">Monto de Gastos de Supervisión (<?php echo ($datos['t02_proc_gast_superv']);?>%)</td>
                    <td width="91" align="center">
                        <?php echo(number_format($supervision,2));?>
                        <input type="hidden" name="txtMontoSupervision" id="txtMontoSupervision" value="<?php echo($supervision);?>" class="LineaBase"/>
                    </td>
                </tr>
            </tbody>
            <tr style="font-size: 11px;">
                <th height="18" colspan="5"></th>
            </tr>
        </table>
    </div>
    <div class="TableGrid" style="width: 350px; display: inline-table;" align="center">
        <table width="281" border="0" cellspacing="1" cellpadding="0">
            <thead>
                <tr>
                    <td height="23" colspan="2">Resumen del Presupuesto Anual - Año <?php echo($anAnioPOA);?></td>
                </tr>
            </thead>
            <tbody class="data">
                <tr>
                    <td width="145" height="21">Costos Directos</td>
                    <td width="116" align="right"><?php echo(number_format($costosDirectos,2));?></td>
                </tr>
                <tr>
                    <td>Costos Indirectos</td>
                    <td align="right"><?php echo(number_format($costosIndirectos,2));?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td height="20" align="left"><b style="color: white">Costo Total del Proyecto </b></td>
                    <td style="text-align: right; padding: 5px"><b style="color: white"><?php echo(number_format($costosTotales, 2));?></b></td>
                </tr>
            </tfoot>
        </table>
        <?php
        // -------------------------------------------------->
        // AQ 2.0 [18-12-2013 16:54]
        // Se revisará en la Fase 3
        // --------------------------------------------------<
            /*$iRs = $objMP->GetPresupuestoReasignado($idProy, $idVersion);
            $RowIndex = 0;
            while ($row = mysqli_fetch_assoc($iRs)) {
                ?>
                  <h1 style="font-size: 12px;">Presupuesto por Reasignar: <?php echo (number_format(($row['mreas'] - $costosIndirectosInicial),2)); ?></H1>
                     <?php
            }
            $iRs->free();*/
        ?>
    </div>
    <?php
        $aRs = $objPres->Acum_Costos_Ejecutados($idProy, $idVersion);
        $aRow = mysqli_fetch_assoc($aRs);
        $totalEjecutado = $aRow['TotalEjecutado'];
        $costoPersonal = $aRow['CostoPersonal'];
        $costoEquipa = $aRow['CostoEquipamiento'];
        $costoFunc = $aRow['CostoFuncionamiento'];
        $costoAdm = $aRow['CostoAdministrativo'];
        $costoImprev = $aRow['CostoImprevisto'];
        $aRs->free();
    ?>
    <div class="TableGrid" style="width: 350px; display: inline-table;"
        align="center">
        <table width="281" border="0" cellspacing="1" cellpadding="0">
            <thead>
                <tr>
                    <td height="23" colspan="5" align="center" style="font-weight: bold;">Saldo Proyectado por Ejecutar - Año <?php echo($anAnioPOA);?></td>
                </tr>
                <tr align="center" valign="middle" style="border: solid 1px #CCC; background-color: #eeeeee; font-weight: bold;">
                    <td width="53" height="28" style="padding: 5px 10px;">&nbsp;</td>
                    <td width="155" height="28" style="padding: 5px 10px;">PRESUPUESTO INICIAL</td>
                    <td width="49" rowspan="2" style="padding: 5px 10px;">GASTOS</td>
                    <td width="47" rowspan="2" style="padding: 5px 10px;">PRESUPUESTO ANUAL</td>
                    <td width="80" rowspan="2" style="padding: 5px 10px;">SALDO PROYECTADO</td>
                </tr>
            </thead>
            <tbody class="data">
                <tr style="background-color: #E3FEE0; height: 25px; cursor: pointer;" onclick="ShowActividad('costosDirectos');">
                    <td style="font-weight: bold;">COSTOS DIRECTOS</td>
                    <td><?php echo number_format($costosDirectosInicial, 2); ?></td>
                    <td><?php echo number_format(($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc), 2); ?></td>
                    <td><?php echo number_format(($costosDirectos), 2); ?></td>
                    <td><?php echo number_format(($costosDirectosInicial - ($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) - $costosDirectos), 2); ?></td>
                </tr>
            </tbody>
            <tbody class="data" bgcolor="#FFFFFF" id="costosDirectos" style="display: none;">
                <tr>
                    <td style="font-weight: bold;">Costo de los Componentes</td>
                    <td><?php echo number_format($costoTotalComp, 2); ?></td>
                    <td><?php echo number_format($totalEjecutado, 2); ?></td>
                    <td><?php echo number_format($aCompoCostoTotal, 2); ?></td>
                    <td><?php echo number_format(($costoTotalComp - $totalEjecutado - $aCompoCostoTotal), 2); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Costo Personal</td>
                    <td><?php echo number_format($costoPersonalInicial, 2); ?></td>
                    <td><?php echo number_format($costoPersonal, 2); ?></td>
                    <td><?php echo number_format($aPersGastoTotal, 2); ?></td>
                    <td><?php echo number_format(($costoPersonalInicial - $costoPersonal - $aPersGastoTotal), 2); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Costo Equipamiento</td>
                    <td><?php echo number_format($costoTotalEquipamiento, 2); ?></td>
                    <td><?php echo number_format($costoEquipa, 2); ?></td>
                    <td><?php echo number_format($aEquipCostoTotal, 2); ?></td>
                    <td><?php echo number_format(($costoTotalEquipamiento - $costoEquipa - $aEquipCostoTotal), 2); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Costo de Funcionamiento</td>
                    <td><?php echo number_format($costoTotalFunc, 2); ?></td>
                    <td><?php echo number_format($costoFunc, 2); ?></td>
                    <td><?php echo number_format($aFuncCostoTotal, 2); ?></td>
                    <td><?php echo number_format(($costoTotalFunc - $costoFunc - $aFuncCostoTotal), 2); ?></td>
                </tr>
            </tbody>
            <tbody class="data">
                <tr
                    style="background-color: #E3FEE0; height: 25px; cursor: pointer;"
                    onclick="ShowActividad('costosIndirectos');">
                    <td style="font-weight: bold;">COSTOS INDIRECTOS</td>
                    <td><?php echo number_format($costosIndirectosInicial, 2); ?></td>
                    <td><?php echo number_format(($costoAdm + $costoImprev), 2); ?></td>
                    <td><?php echo number_format($costosIndirectos, 2); ?></td>
                    <td><?php echo number_format(($costosIndirectosInicial) - ($costoAdm + $costoImprev) - $costosIndirectos, 2); ?></td>
                </tr>
            </tbody>
            <tbody class="data" bgcolor="#FFFFFF" id="costosIndirectos" style="display: none;">
                <tr>
                    <td style="font-weight: bold;">Costos Administrativos</td>
                    <td><?php echo number_format($costoAdminist, 2); ?></td>
                    <td><?php echo number_format($costoAdm, 2); ?></td>
                    <td><?php echo number_format($aAdmisCostoTotal, 2); ?></td>
                    <td><?php echo number_format(($costoAdminist - $costoAdm - $aAdmisCostoTotal), 2); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Costo Imprevistos</td>
                    <td><?php echo number_format($imprevistosInicial, 2); ?></td>
                    <td><?php echo number_format($costoImprev, 2); ?></td>
                    <td><?php echo number_format($imprevistos, 2); ?></td>
                    <td><?php echo number_format(($imprevistosInicial - $costoImprev - $imprevistos), 2); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Costo Linea Base</td>
                    <td><?php echo number_format($lineaBaseInicial, 2); ?></td>
                    <td>0.00</td>
                    <td><?php echo number_format($lineaBase, 2); ?></td>
                    <td><?php echo number_format(($lineaBaseInicial - $lineaBase), 2); ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Costo Supervisión</td>
                    <td><?php echo number_format($supervisionInicial, 2); ?></td>
                    <td>0.00</td>
                    <td><?php echo number_format($supervision, 2); ?></td>
                    <td><?php echo number_format(($supervisionInicial - $supervision), 2); ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td style="font-weight: bold; color: #FFF;">TOTAL</td>
                    <td style="font-weight: bold; color: #FFF;"><?php echo number_format($costosTotalesInicial, 2); ?></td>
                    <td style="font-weight: bold; color: #FFF;"><?php echo number_format(($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) + ($costoAdm + $costoImprev), 2); ?></td>
                    <td style="font-weight: bold; color: #FFF;"><?php echo number_format($costosTotales, 2); ?></td>
                    <td style="font-weight: bold; color: #FFF;"><?php echo number_format(($costosTotalesInicial - (($totalEjecutado + $costoPersonal + $costoEquipa + $costoFunc) + ($costoAdm + $costoImprev))) - $costosTotales, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script language="javascript">
    $(document).ready(function() {
        bindRoundDecimals();
    });

    function GuardarLineaBaseImprevistos()
    {
        <?php $ObjSession->AuthorizedPage(); ?>
        if($("#estilolb").text()!= ""){
            alert("Error: "+$("#estilolb").text());
        }
        else{
            var BodyForm = "txtCodProy=" + $("#txtCodProy").val() + "&cboversion=" + $('#cboversion').val()
            BodyForm += "&" + $("#FormData .LineaBase").serialize() ;

            var sURL = "mp_lineabase_process.php?action=<?php echo(md5("save_gastos"));?>" ;
            var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessLineaBaseImprevistos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
        }
        return false;
    }

    function MySuccessLineaBaseImprevistos(req)
    {
        var respuesta = req.xhRequest.responseText;
        respuesta = respuesta.replace(/^\s*|\s*$/g,"");
        var ret = respuesta.substring(0,5);

        if(ret=="Exito")
        {
             LoadLineaBase(true);
             alert(respuesta.replace(ret,""));
        }
        else
        {  alert(respuesta); }
    }

    function ExportarCuadroF()
    {
        var reportID = 59 ;
        var arrayControls = new Array();
            arrayControls[0] = "idProy=<?php echo($idProy);?>";
            arrayControls[1] = "idVersion=<?php echo($idVersion);?>";
            arrayControls[2] = "no_filter=1";
        var params = arrayControls.join("&");

        var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;
        var win =  window.open(newURL, "w_exportpoafin", "fullscreen,scrollbars");
        win.focus();
        return;
    }

    function ValidarMonto()
    {
        var fuente = "";
        var mIm = CNumber($('#txtMontoImprevistos').val().replace(',', ''));
        var Im  = CNumber($('#txtImp').val());
        var msj = "El Monto Real excede al monto de ";

        if (mIm > Im)
        {
            fuente += "Imprevistos (<?php echo ($datos['t02_porc_imprev']);?>%)";
        }

        if (fuente.length > 0)
        {
            $("#estilolb").css("color","#FFF");
            $("#estilolb").css("background-color","#F00");
            $("#estilolb").text(msj+fuente);
        }else{
            $("#estilolb").css("color","white");
            $("#estilolb").css("background-color","white");
            $("#estilolb").text("");
        }
    }

    function CNumber(str)
    {
        var numero =0;
        if (str !="" && str !=null)
        { numero = parseFloat(str);}
        if(isNaN(numero)) { numero=0;}
        return numero;
    }

    function ShowActividad(idAct)
    {
        var id = "#" + idAct ;
        $(id).toggle();
        return false;
    }

    <?php
    if(number_format($sum_total,2) != number_format($sumaTotal,2))
    {
     ?>
     $("#btnGuardarLB").attr('disabled','disabled');
     <?php
    }
    ?>
    $(".LineaBase").numeric().pasteNumeric();
    $('.linea_mp:input[readonly="readonly"]').css("background-color", "#eeeecc");
</script>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>