<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$modif = $objFunc->__POST('modif');
$modificar = false;
if (md5("enable") == $modif) {
    $modificar = true;
}
if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Gastos Administrativos del Proyecto</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
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
                            value="Guardar Costos" id="btnGuardarLB"
                            <?php if($modificar) echo "disabled"; ?>>Guardar</button></td>
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
    $iRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
    $campos = $objMP->iGetArrayFields($iRs);
    unset($campos[1]);
    unset($campos[0]);
    $numftes = count($campos);
    $sumaTotal = 0;
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
        <?php } ?>
        </tr>
        <tbody class="data">
        <?php
        $objHC = new HardCode();
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
            } // End While
            $iRs->free();
        } // End If
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
            <?php } ?>
            </tr>
        </tbody>
    </table>
    <span style="background-color: #FF0; color: #F00; font-size: 11px; font-family: Arial, Helvetica, sans-serif">
    <?php
    if (number_format($sum_total, 2) != number_format($sumaTotal, 2)) {
        echo ("<b>Error:</b> <br>Las Fuentes de Financiamiento no corresponden al Costo total del Proyecto.
       <br>Verifique los aportes de Fuentes de Financiamiento para los Costos Operativos y luego clic en Refrescar Costos.
       ");
    }
    ?>
    </span> <br />
    <?php
    /*$LineaBase = round(($sumaFE * $objHC->Porcent_Linea_Base) / 100, 2);
    $Imprevistos = round(($sumaFE * $objHC->Porcent_Imprevistos) / 100, 2);*/

    $datos = $objMP->listarTasasParametros($idProy, $idVersion);
    $LineaBase = round(($sumaFE * $datos['t02_porc_linea_base']) / 100, 2);
    $Imprevistos = round(($sumaFE * $datos['t02_porc_imprev']) / 100, 2);
    $gastosSupervision = round(($sumaFE * $datos['t02_proc_gast_superv']) / 100, 2);
    ?>
    <form action="#" method="post" name="frmMain" id="frmMain">
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
                        <td align="center"><strong>Monto Real</strong></td>
                    </tr>
                    <?php
                        $rowLB = $objMP->LineaBase_Imprevistos($idProy, $idVersion);
                        // if($rowLB['linea_base']<=0){$rowLB['linea_base']= $LineaBase ;}
                        // if($rowLB['imprevistos']<=0){$rowLB['imprevistos']= $LineaBase ;}
                    ?>
                        <tr>
                            <td height="21">
                                Monto de la Linea de base y Evaluación de Impacto (<?php echo ($datos['t02_porc_linea_base']);?>%)
                                <input type="hidden" name="txtMontoLB" id="txtMontoLB" value="<?php echo($LineaBase);?>" class="LineaBase" />
                            </td>
                            <td width="82" align="center">
                                <?php echo(number_format($LineaBase,2));?>
                            </td>
                        </tr>
                        <tr style="font-size: 11px;">
                            <th height="18" colspan="5" align="left" style="padding-left: 4px;"></th>
                        </tr>
                    </tbody>
                </table>

                <table width="387" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <td height="23" colspan="3">
                                Los imprevistos no deben exceder el <?php echo ($datos['t02_porc_imprev']);?>% de los Costos Directos</td>
                        </tr>
                    </thead>
                    <tbody class="data">
                        <tr>
                            <td height="21">&nbsp;</td>
                            <td align="center" nowrap="nowrap"><strong>Monto Máximo</strong></td>
                            <td align="center"><strong>Monto Real</strong></td>
                        </tr>
                        <tr>
                            <td width="212" height="21">Monto de Imprevistos (<?php echo ($datos['t02_porc_imprev']);?>%)</td>
                            <td width="91" align="center">
                                <?php echo(number_format($Imprevistos,2));?>
                            </td>
                            <td width="82" align="center">
                                <input id="txtMontoImprevistos" name="txtMontoImprevistos" type="text" class="LineaBase roundDec" style="text-align: right" value="<?php echo($rowLB['imprevistos']);?>" size="14" onkeyup="ValidarMonto();" />
                                <input id="txtImp" type="hidden" value="<?php echo($Imprevistos);?>"/>
                            </td>
                        </tr>
                    </tbody>
                    <tr style="font-size: 11px;">
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
                            <td align="center"><strong>Monto Real</strong></td>
                        </tr>
                        <tr>
                            <td width="212" height="21">Monto de Gastos de Supervisión (<?php echo ($datos['t02_proc_gast_superv']);?>%)</td>
                            <td width="91" align="center">
                                <?php echo(number_format($gastosSupervision,2));?>
                                <input type="hidden" name="txtMontoSupervision" id="txtMontoSupervision" value="<?php echo($gastosSupervision);?>" class="LineaBase"/>
                            </td>
                        </tr>
                    </tbody>
                    <tr>
                        <th height="18" colspan="5"></th>
                    </tr>
                </table>
            </div>
            <div class="TableGrid" style="width: 350px; display: inline-table;"
                align="center">
                <table width="281" border="0" cellspacing="1" cellpadding="0">
                    <thead>
                        <tr>
                            <td height="23" colspan="2"><b>Resumen del Presupuesto</b></td>
                        </tr>
                    </thead>
                    <tbody class="data">
                        <tr>
                            <td width="145" height="21">Costos Directos</td>
                            <td width="116" align="right"><?php echo(number_format($objMP->GetCostosDirectos($idProy, $idVersion),2));?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Costos Indirectos</td>
                            <td align="right"><?php echo(number_format($objMP->GetCostosInDirectos($idProy, $idVersion),2));?>&nbsp;</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td height="20" align="left"><strong style="color: white">Costo Total del Proyecto </strong></td>
                            <td style="text-align: right; padding: 5px"><strong style="color: white"><?php echo(number_format($objMP->GetCostosTotalProyecto($idProy, $idVersion),2));?></strong>&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </form>
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
         BodyForm += "&" + $("#frmMain").serialize() ;

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

<?php
if (number_format($sum_total, 2) != number_format($sumaTotal, 2)) {
    ?>
 $("#btnGuardarLB").attr('disabled','disabled');
 <?php
}
?>
 $(".LineaBase").numeric().pasteNumeric();
 $('.linea_mp:input[readonly="readonly"]').css("background-color", "#eeeecc") ;
</script>
  <?php if($idProy=="") { ?>
</body>
</html>
<?php } ?>