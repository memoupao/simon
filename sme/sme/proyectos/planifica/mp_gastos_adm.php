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
<meta http-equiv="Content-Type"
    content="text/html; charset=charset=utf-8" />
<title>Gastos Administrativos del Proyecto</title>
<script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
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
                            onclick="LoadGastoAdminis(true); return false;"
                            value="Recargar Listado">Refrescar Costos</button></td>
                    <td width="16%"><button class="Button"
                            onclick="GuardarGastosADM(); return false;" value="Guardar"
                            id="BtnGuardarADM" <?php if($modificar) echo "disabled"; ?>>
                            Guardar Aportes</button></td>
                    <td width="5%">&nbsp;</td>
                    <td width="13%">&nbsp;</td>
                    <td width="43%" align="right"><span
                        style="color: #036; font-weight: bold; font-size: 12px;">Costos
                            Administrativos </span></td>
                    <td width="6%" align="right">&nbsp;</td>
                </tr>
            </table>
        </div>
        <div id="divTableLista" class="TableGrid">
<?php
$objMP = new BLManejoProy();
$objHC = new HardCode();
$iRs = $objMP->GastosAdm_ResumenCostos($idProy, $idVersion);
$campos = $objMP->iGetArrayFields($iRs);
unset($campos[1]);
unset($campos[0]);
$numftes = count($campos);
$sumaTotal = 0;

$datos = $objMP->listarTasasParametros($idProy, $idVersion);
?>
         <div class="TextDescripcion">Cuadro que muestra el Total de Costos Operativos del proyecto</div>
            <table width="780" border="0" cellpadding="0" cellspacing="0">
                <tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
                    <td height="26" colspan="2" rowspan="2" style="border: solid 1px #CCC;">&nbsp;&nbsp;Componentes</td>
                    <td width="89" rowspan="2" align="center" style="border: solid 1px #CCC;">Costo Total</td>
                    <td colspan="<?php echo($numftes);?>" align="center">Financiamiento</td>
                </tr>
                <tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
        <?php
        for ($col == 0; $col < $numftes; $col ++) {
            ?>
                    <td width="60" align="center" style="border: solid 1px #CCC;"><?php echo($campos[$col+2]);?></td>
        <?php } ?>
                </tr>
            <tbody class="data">
      <?php

    $sum_total = 0;
    $sum_fte_fe = 0;
    $sum_fte_otro = 0;
    $sum_ejecutor = 0;
    // if($iRs->num_rows > 0)
    // {
    while ($row = mysqli_fetch_assoc($iRs)) {
        $sum_total += round($row["costo_total"], 2);

        ?>
      <tr class="RowData" style="background-color: #FFF;">
                        <td colspan="2"><?php echo($row["codigo"]);?>  <?php echo($row["componente"]);?></td>
                        <td align="right"><?php echo( number_format(($row["costo_total"]),2));?></td>
         <?php
        $col = 0;
        for ($col == 0; $col < $numftes; $col ++) {
            $field = $campos[$col + 2];
            $sum_fte[$col] += $row[$field];
            ?>
        <td align="right"><?php echo(number_format($row[$field],2));?>&nbsp;</td>
        <?php } ?>
        </tr>
        <?php

} // End While
    $iRs->free();
    // } // End If
    ?>
    </tbody>
                <tfoot>
                    <tr style="color: #FFF; font-size: 11px;">
                        <th width="74" height="18">&nbsp;</th>
                        <th width="473">TOTAL DE COSTOS DIRECTOS DEL PROYECTO</th>
                        <th align="right"><?php echo(number_format($sum_total,2));?>&nbsp;</th>
        <?php
        $col = 0;
        for ($col == 0; $col < $numftes; $col ++) {
            $sumaTotal += $sum_fte[$col];
            ?>
        <th align="right"><?php echo(number_format($sum_fte[$col],2));?>&nbsp;</th>
        <?php } ?>
        </tr>
                </tfoot>
            </table>
            <span
                style="background-color: #FF0; color: #F00; font-size: 11px; font-family: Arial, Helvetica, sans-serif">
  <?php
if (number_format($sum_total, 2) != number_format($sumaTotal, 2)) {
    echo ("<b>Error:</b> <br>Las Fuentes de Financiamiento no corresponden al Costo total del Proyecto.
           <br>Verifique los aportes de Fuentes de Financiamiento para los Costos Operativos y luego clic en Refrescar Costos.
           ");
}
?>
  </span> <br />
            <div id="divTableFinan" class="TableGrid">
                <div class="TextDescripcion" style="width: 500px;">
                    Gastos Administrativos necesarios para el adecuado funcionamiento
                    del proyecto. <br/> El monto no podrá ser mayor al <?php echo ($datos['t02_porc_gast_func']);?>% de los
                    costos y gastos directos de Fondoempleo.
                </div>
                <table width="600" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="6%" height="30" align="center" valign="middle">#</th>
                            <th width="43%" align="center" valign="middle">Fuente de Financiamiento</th>
                            <th width="17%" align="center" valign="middle" nowrap="nowrap">Total Costos Directos</th>
                            <th width="17%" align="center" valign="middle">Monto Maximo (<?php echo ($datos['t02_porc_gast_func']);?>%)</th>
                            <th width="17%" align="center" valign="middle" nowrap="nowrap">Monto Aportado</th>
                        </tr>
                    </thead>
                    <tbody class="data">
                        <tr style="background-color: #D6DFF8;">
                            <td colspan="2" align="left" valign="middle"><strong>Costos de Administración Total</strong></td>
                            <td align="right"><strong><?php echo(number_format($sumaTotal,2));?></strong></td>
                            <td align="right"><strong><?php echo(number_format(($sumaTotal*$datos['t02_porc_gast_func']/100),2));?></strong></td>
                            <td align="right">&nbsp;</td>
                        </tr>
          <?php
        $rsFte = $objMP->GastosAdm_Listado($idProy, $idVersion);

        $index = 1;
        $sumaAportado = 0;
        $max = 0;
        while ($rowFTE = mysqli_fetch_assoc($rsFte)) {
            $max = ($rowFTE['financiado']*$datos['t02_porc_gast_func']/100);
            ?>
                        <tr class="RowData">
                            <td align="center" valign="middle">
                                <input name="" id="por_<?php echo($index);?>" type="hidden" value="<?php echo($max);?>" />
                                <input name="" id="fue_<?php echo($index);?>" type="hidden" value="<?php echo($rowFTE['fuente']);?>" />
                                <input name="" id="idfuente_<?php echo($index);?>" type="hidden" value="<?php echo($rowFTE['codigo']);?>" />
                                <?php echo($index);?>
                            </td>
                            <td>
                                <input name="txtadminstit[]" id="txtadminstit[]" type="hidden" value="<?php echo($rowFTE['codigo']);?>" class="GastosAdministrativos" />
                                <?php echo($rowFTE['fuente']);?>
                            </td>
                            <td align="right"><?php echo(number_format($rowFTE['financiado'],2));?></td>
                            <td id="maximo[]" align="right" <?php if($rowFTE['codigo']!=$objHC->codigo_Fondoempleo) {echo("style='color:gray;'");} ?>>
                                <?php echo(number_format($max, 2));?>
                            </td>
                            <td align="right">
                                <input name="txtadmmonto[]" type="text" id="txtadmmonto[]" style="text-align: right" value="<?php echo(number_format($rowFTE["costo"], 2));?>"
                                size="17" maxlength="10" onkeyup="CalcularTotalesADM();" montoADM='1' class="GastosAdministrativos roundDec" />
                            </td>
                        </tr>
          <?php

            $sumaAportado += $rowFTE["costo"];
            $index ++;
        }
        ?>
          </tbody>
                    <tfoot>
                        <tr>
                            <td align="center" valign="middle"></td>
                            <td>&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right">&nbsp;</td>
                            <td align="right"><b id="bSumaTotal" style="color: #FFF; text-align: right"><?php echo(number_format($sumaAportado,2));?></b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <table width="602" border="0" cellpadding="0" cellspacing="0"
            style="padding-left: 4px;">
            <tr style="font-size: 11px;">
                <th height="18" colspan="5" align="left" id="estiloga"
                    style="padding-left: 4px;"></th>
            </tr>
        </table>
        <script language="javascript" type="text/javascript">
        $(document).ready(function() {
            bindRoundDecimals();
        });

        function GuardarGastosADM()
        {
            <?php $ObjSession->AuthorizedPage(); ?>
            var BodyForm = "txtCodProy=" + $("#txtCodProy").val() + "&cboversion=" + $('#cboversion').val()
            BodyForm += "&" + $("#FormData .GastosAdministrativos").serialize() ;

            if($("#estiloga").text()!= ""){
                alert("Error: "+$("#estiloga").text());
            }
            else{
                var sURL = "mp_gastos_adm_process.php?action=<?php echo(md5("save_gastos"));?>" ;
                var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardarGastosADM, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
            }
            return false;
        }

        function MySuccessGuardarGastosADM(req)
        {
            var respuesta = req.xhRequest.responseText;
            respuesta = respuesta.replace(/^\s*|\s*$/g,"");
            var ret = respuesta.substring(0,5);
            if(ret=="Exito")
            {
                LoadGastoAdminis(true);
                alert(respuesta.replace(ret,""));
            }
            else
            {  alert(respuesta); }
        }

        function CalcularTotalesADM(){
            var mens= "";
            var sum = 0;
            var n=1;
            var fuente = "";
            $("input[montoADM='1']").each(function(i,e) {

                var  item = $(e).val().replace(',', '');
                item = $.trim(item);
                if(item.length > 0) {
                    var monto_aportado = parseFloat(parseFloat(item).toFixed(2));
                    var monto_maximo = parseFloat(parseFloat($("#por_"+n).val()).toFixed(2));

                    if (monto_aportado > monto_maximo) {
                        fuente += $("#fue_"+n).val()+", ";
                    }
                    n = n+1;

                    var aValue = item;
                    if(!isNaN(aValue) && aValue.length != 0) {
                        sum += CNumber(aValue);
                    }
                }
            });
            // -------------------------------------------------->
            // AQ 2.0 [05-12-2013 01:32]
            // No permite mostrar el monto total adecuadamente
            //$('#bSumaTotal').html(sum.toFixed(2));
            // --------------------------------------------------<

            mens ="Se está excediendo del monto máximo(<?php echo ($datos['t02_porc_gast_func']);?>%) para las fuentes: " ;

            if (fuente.length > 0)
            {
                $("#estiloga").css("color","#FFF");
                $("#estiloga").css("background-color","#F00");
                $("#estiloga").text(mens+fuente.substr(0,fuente.length-2));
            }else{
                $("#estiloga").css("color","white");
                $("#estiloga").css("background-color","white");
                $("#estiloga").text("");
            }

            $('#bSumaTotal').html(sum.toFixed(2));
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
            $("#BtnGuardarADM").attr('disabled','disabled');
         <?php
        }
        ?>

    $("input[montoADM='1']").numeric().pasteNumeric();
    CalcularTotalesADM();
</script>

<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>