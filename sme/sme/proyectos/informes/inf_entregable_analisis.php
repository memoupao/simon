<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $anio == "" && $idEntregable == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Actividades</title>
<script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
$objInf = new BLInformes();
$row = $objInf->listarAnalisisInfEntregable($idProy, $idVersion, $anio, $idEntregable);
?>
        <table width="1000" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="50%">&nbsp;</td>
				<td width="30%" rowspan="2" class="TableEditReg">
					<button onclick="Guardar_Analisis(); return false;" class="boton">Guardar</button>
				    <button onclick="LoadAnalisis(true); return false;" class="boton">Refrescar</button>
				</td>
			</tr>
			<tr>
				<td><b>Analisis del Informe</b></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle">
    						<b>Análisis de Resultados</b><br/>
    						<textarea name="t25_resulta" rows="7" id="t25_resulta" style="padding: 0px; width: 100%;"><?php echo($row['t25_resulta']);?></textarea>
					    </td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <b>Conclusiones</b><br/>
							<textarea name="t25_conclu" rows="5" id="t25_conclu" style="padding: 0px; width: 100%;"><?php echo($row['t25_conclu']);?></textarea>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle">
    						<b>Limitaciones</b><br/>
    						<textarea name="t25_limita" rows="5" id="t25_limita" style="padding: 0px; width: 100%;"><?php echo($row['t25_limita']);?></textarea>
					    </td>
					</tr>
					<tr>
						<td align="left" valign="middle">
    						<b>Factores Positivos</b><br/>
    						<textarea name="t25_fac_pos" rows="5" id="t25_fac_pos" style="padding: 0px; width: 100%;"><?php echo($row['t25_fac_pos']);?></textarea>
						</td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <b>Perspectivas para el Próximo Entregable</b><br/>
						    <textarea name="t25_perspec" rows="5" id="t25_perspec" style="padding: 0px; width: 100%;"><?php echo($row['t25_perspec']);?></textarea>
					    </td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
			<input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
            function Guardar_Analisis()
            {
                <?php $ObjSession->AuthorizedPage(); ?>
                var BodyForm=$("#FormData").serialize();
                if(confirm("Estas seguro de guardar el Análisis?"))
                {
                	var sURL = "inf_entregable_process.php?action=<?php echo(md5('ajax_analisis'));?>";
                	var req = Spry.Utils.loadURL("POST", sURL, true, AnalisisSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                }
            }

            function AnalisisSuccessCallback(req)
            {
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);

                if(ret=="Exito")
                {
                    LoadAnalisis(true);
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