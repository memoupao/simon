<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $anio == "" && $idEntregable == "") {
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
    <title>Conclusiones</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
$objInf = new BLInformes();
$row = $objInf->getInfSI($idProy, $anio, $idEntregable);

//print_r($row);

?>
        <table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
				    <input type="button" value="Refrescar" onclick="LoadConclusiones(true);" class="btn_save_custom" />
			    </td>
				<td width="10%" rowspan="2" align="right" valign="middle">
				    <input id='saveConcBtn' type="button" value="Guardar" onclick="Guardar_Conclusiones();" class="btn_save_custom" />
			    </td>
			</tr>
			<tr>
				<td><b>Conclusiones y Recomendaciones</b></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><b>Avances</b> <br/>
						    <textarea name="avance" rows="7" id="avance" class="obs"><?php echo($row['t45_avance']);?></textarea>
					    </td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <b>Logros</b><br/>
						    <textarea name="logros" rows="5" id="logros" class="obs"><?php echo($row['t45_logros']);?></textarea>
					    </td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <b>Dificultades</b><br/>
						    <textarea name="dificul" rows="5" id="dificul" class="obs"><?php echo($row['t45_dificul']);?></textarea>
					    </td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <br />
						    <b>Recomendaciones al Proyecto</b>
						    <textarea name="recoProy" rows="5" id="recoProy" class="obs"><?php echo($row['t45_reco_proy']);?></textarea>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <span class="nota">Este cuadro será visualizado sólo por FondoEmpleo</span><br />
						    <b>Recomendaciones a Fondoempleo</b>
						    <textarea name="recoFE" rows="5" id="recoFE" class="obs"><?php echo($row['t45_reco_fe']);?></textarea>
						</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="idProy" id="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
            <input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">

            $(document).ready(function(){
            	if ($('#pageMode').val() == 'view')
            		$('#saveConcBtn').attr({disabled:'disabled'});
            });

            function Guardar_Conclusiones()
            {
                <?php $ObjSession->AuthorizedPage(); ?>

                var arrParams = new Array();

                arrParams[0] = "idProy=" + $("#idProy").val();
                arrParams[1] = "anio=" + $("#anio").val();
        		arrParams[2] = "idEntregable=" + $("#idEntregable").val();
        		arrParams[3] = "avance=" + $("#avance").val();
        		arrParams[4] = "logros=" + $("#logros").val();
        		arrParams[5] = "dificul=" + $("#dificul").val();
        		arrParams[6] = "recoProy=" + $("#recoProy").val();
        		arrParams[7] = "recoFE=" + $("#recoFE").val();

                var BodyForm = arrParams.join("&");

                if(confirm("Estas seguro de guardar el Conclusiones, para el Informe de Supervisión?"))
                {
                	var sURL = "inf_monint_process.php?action=<?php echo(md5('ajax_conclusiones'));?>";
                	var req = Spry.Utils.loadURL("POST", sURL, true, ConclusionesSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                }
            }

            function ConclusionesSuccessCallback(req)
            {
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);

                if(ret=="Exito")
                {
                    LoadConclusiones(true);
                    alert(respuesta.replace(ret,""));
                }
                else
                {alert(respuesta);}
            }
            </script>
		</div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>