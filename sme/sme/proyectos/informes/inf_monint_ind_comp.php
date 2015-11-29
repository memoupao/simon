<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idComp = $objFunc->__POST('idComp');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $idComp == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idComp = $objFunc->__GET('idComp');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <title>Indicadores de Componentes SE</title>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <div id="divTableLista">
			<table width="950" cellpadding="0" cellspacing="0" class="TableEditReg">
				<tr class="head">
					<th width="50" rowspan="2">Código</th>
					<th width="150" rowspan="2">Indicador de Componente</th>
					<th width="50" rowspan="2">U.M.</th>
					<th width="40" rowspan="2">Meta Planeada</th>
					<th height="28" colspan="3">Ejecutado</th>
					<th width="40" rowspan="2">Avance Verificado</th>
					<th width="220" rowspan="2">Observaciones del Supervisor</th>
				</tr>
				<tr class="head">
					<th width="60" height="28">Acum</th>
					<th width="60">Avance</th>
					<th width="60">Total</th>
				</tr>
				<tbody class="data" bgcolor="#FFFFFF">
                <?php
                $objInf = new BLInformes();
                $iRs = $objInf->listarIndicadoresComponenteSI($idProy, $idVersion, $idComp, $anio, $idEntregable);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        ?>
            	    <tr align="center">
						<td><?php echo($row['t08_cod_comp_ind']);?></td>
						<td align="left"><?php echo( $row['indicador']);?>
                            <input name="indComp[]" id="indComp[]" type="hidden" value="<?php echo($row['t08_cod_comp_ind']);?>" />
                        </td>
						<td><?php echo( $row['t08_um']);?></td>
						<td><?php echo( $row['plan_mtatotal']);?></td>
						<td><?php echo( $row['ejec_mtaacum']);?></td>
						<td><?php echo($row['ejec_avance']);?></td>
						<td><?php echo($row['ejec_mtatotal']);?></td>
						<td><input type="text" name="avancesComp[]" id="avancesComp[]" size="8" value="<?php echo($row['avance']);?>" class="center"/>
						<td><textarea name="obsComp[]" rows="2" id="obsComp[]" class="obs"><?php echo($row['obs']);?></textarea></td>
					</tr>
                 <?php
                        $RowIndex ++;
                    }
                    $iRs->free();
                }
                else {
                    echo ("<span class='nota'>El Componente seleccionado [" . $idComp . "] no tiene registrado Indicadores. Verificar el Marco Lógico</span>");
                    exit();
                }
                ?>
                </tbody>
			</table>
			<input type="hidden" name="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
            <input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
            function Guardar_AvanceIndComp	()
            {
                <?php $ObjSession->AuthorizedPage(); ?>

                var comp = $('#cbocomponente_ind').val();
                if(comp==""){alert("El componente seleccionado, no tiene indicadores"); return;}

                var BodyForm=$("#FormData").serialize();

                if(confirm("Estas seguro de Guardar el avance de los Indicadores de Componente para el Informe ?"))
                {
                	var sURL = "inf_monint_process.php?action=<?php echo(md5('ajax_ind_componente'));?>";
                	var req = Spry.Utils.loadURL("POST", sURL, true, indCompSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                }
            }

            function indCompSuccessCallback	(req)
            {
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);

                if(ret=="Exito")
                {
                    LoadIndicadoresComponente();
                    alert(respuesta.replace(ret,""));
                }
                else
                {alert(respuesta);}

            }

            $('.MEIC:input[readonly="readonly"]').css("background-color", "#eeeecc") ;

            </script>
		</div>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>