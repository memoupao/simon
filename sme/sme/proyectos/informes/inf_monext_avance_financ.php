<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $idNum == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Anexos Informe SE</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form action="#" method="post" enctype="multipart/form-data" name="frmMain" id="frmMain">
<?php
}

$objInf = new BLInformes();
//$row = $objInf->getInfSE($idProy, $anio, $idEntregable);
$lista = $objInf->getInfAvanceFinancieroSE($idProy, $anio, $idEntregable);

$idx = 0;
?>

<style>textarea{width:95%; resize:none;} .titleHead{text-align: center;}; .TableGrid table tbody tr td {text-align: center;};</style>

		<table width="795" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="100%" align="right" valign="middle">
				    <input id='saveAvcFroBtn' type="button" value="Guardar" onclick="Guardar_AvanceFinanciero();" class="btn_save_custom" />
			    </td>
			</tr>
			<tr>
				<td><b>Resumen del Avance Presupuestal</b></td>
			</tr>
		</table>
		
		<div id="divTableLista" class="TableGrid">
			<table width="795" border="0" cellpadding="0" cellspacing="0">
				<thead>					
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td rowspan="2" class="titleHead">Resumir datos de programación presupuestal</td>
						<td rowspan="2" class="titleHead">Total según convenio S/.</td>
						<td colspan="2" class="titleHead">Acumulado al entregable</td>
						<td colspan="2" class="titleHead">% de Avance Acumulado</td>
						<td rowspan="2" class="titleHead"  width="220px">Observaciones encontradas</td>
					</tr>
					<tr class="SubtitleTable" style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td class="titleHead">Programado S/.</td>
						<td class="titleHead">Ejecutado S/.</td>						
						<td class="titleHead">En relación a lo programado al Entregable %</td>
						<td class="titleHead">En relación al total segun convenio %</td>
					</tr>
                    <?php
                        $total_conv = 0;
                        $total_plan = 0;
                        $total_ejec = 0;

                        if ($lista->num_rows > 0) {
                            
                            while ($row = mysqli_fetch_assoc($lista)) {
                                $total_conv += $row['aporte'];
                                $total_plan += $row['planeado'];
                                $total_ejec += $row['ejecutado'];
                    ?>
                    <tr class="right">
                        <td class="SubtitleTable left tblhead"><?php echo($row['sigla']);?></td>
                        <td class="av-tot" val="<?php echo($row['aporte']);?>"><?php echo(number_format($row['aporte'], 2));?></td>
                        <td class="av-prog" val="<?php echo($row['planeado']);?>"><?php echo(number_format($row['planeado'], 2));?></td>
                        <td class="av-entr" val="<?php echo($row['ejecutado']);?>"><?php echo(number_format($row['ejecutado'], 2));?></td>
                        <td class="av-prog-porc"></td>
                        <td class="av-conv-porc"></td>
                        <td>
                            <textarea rows="2" name="obs[<?php echo($row['codigo']);?>][]" id="obs[<?php echo($row['codigo']);?>][]" class="datos"><?php echo($row['obs']);?></textarea>
                        </td>
                    </tr>
                    <?php } } ?>

                    <tr class="right tblhead">
                        <td class="SubtitleTable right">Total</td>
                        <td class="av-tot" val="<?php echo($total_conv);?>"><?php echo(number_format($total_conv, 2));?></td>
                        <td class="av-prog" val="<?php echo($total_plan);?>"><?php echo(number_format($total_plan, 2));?></td>
                        <td class="av-entr" val="<?php echo($total_ejec);?>"><?php echo(number_format($total_ejec, 2));?></td>
                        <td class="av-prog-porc"></td>
                        <td class="av-conv-porc"></td>
                        <td>&nbsp;</td>
                    </tr>

				</tbody>				
			</table>
			<input type="hidden" name="idProy" id="idProy" value="<?php echo($idProy);?>" class="datos"/>
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" class="datos"/>
            <input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" class="datos"/>

			<script language="javascript" type="text/javascript">

            $(document).ready(function(){
            	if ($('#pageMode').val() == 'view')
            		$('#saveAvcFroBtn').attr({disabled:'disabled'});
            });

            function Guardar_AvanceFinanciero()
            {
                <?php $ObjSession->AuthorizedPage(); ?>
                var BodyForm=$(".datos").serialize();
                var sURL = "inf_monext_process.php?action=<?php echo(md5('ajax_avance_financiero'));?>";
                var req = Spry.Utils.loadURL("POST", sURL, true, AvanceFinancieroSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
            }

            function Guardar_AvanceFinanciero__()
            {
                <?php $ObjSession->AuthorizedPage(); ?>

                var arrParams = new Array();

                arrParams[0] = "idProy=" + $("#idProy").val();
                arrParams[1] = "anio=" + $("#anio").val();
        		arrParams[2] = "idEntregable=" + $("#idEntregable").val();
        		arrParams[3] = "af_obs_1=" + $("#af_obs_1").val();
        		arrParams[4] = "af_obs_2=" + $("#af_obs_2").val();
        		arrParams[5] = "af_obs_3=" + $("#af_obs_3").val();
        		arrParams[6] = "af_obs_4=" + $("#af_obs_4").val();
        		arrParams[7] = "af_obs_5=" + $("#af_obs_5").val();

                var BodyForm = arrParams.join("&");

				var sURL = "inf_monext_process.php?action=<?php echo(md5('ajax_avance_financiero'));?>";
                var req = Spry.Utils.loadURL("POST", sURL, true, AvanceFinancieroSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                
            }

            function AvanceFinancieroSuccessCallback(req)
            {
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);

                if(ret=="Exito") {
                	LoadAvanceFinanciero(true);
                    alert(respuesta.replace(ret,""));
                } else {
                    alert(respuesta);
                }
            }

            $('.av-prog-porc').each(function () {
                var total = parseFloat($(this).siblings('.av-tot').attr('val'));
                var entr = parseFloat($(this).siblings('.av-entr').attr('val'));
                var prog = parseFloat($(this).siblings('.av-prog').attr('val'));

                var prog_porc = total>0?parseFloat((entr/prog)*100).toFixed(2):0.00;
                var conv_porc = total>0?parseFloat((entr/total)*100).toFixed(2):0.00;

                $(this).html(prog_porc);
                $(this).siblings('.av-conv-porc').html(conv_porc);
            });
            
            </script>
		</div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>