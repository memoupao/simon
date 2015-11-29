<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

error_reporting("E_PARSE");
$view = $objFunc->__REQUEST('view');

$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t20_ver_inf');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');

if ($idProy == "" && $idAnio == "" && $idMes == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t20_ver_inf');
    $idAnio = $objFunc->__GET('idAnio');
    $idMes = $objFunc->__GET('idMes');
}

$HardCode = new HardCode();
$IsMT = false;
if ($ObjSession->PerfilID == $HardCode->MT || $ObjSession->PerfilID == $HardCode->CMT) {
    $IsMT = true;
}

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />

<title>Actividades</title>
<script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Problemas y Soluciones"  onclick="LoadProblemasSoluciones(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input value="Refrescar" type="button"
					title="Refrescar datos de Problemas y Soluciones"
					onclick="LoadProblemasSoluciones(true);" class="btn_save_custom" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar Problemas y Soluciones"> <img src="../../../img/aplicar.png" width="22" height="22" onclick="Guardar_ProblemasSoluciones();" class="btn_InformeMPS"/><br />
      Guardar  </div osktgui--> <input value="Guardar" type="button"
					title="Guardar Problemas y Soluciones"
					onclick="Guardar_ProblemasSoluciones();" class="btn_save_custom" />
				</td>
			</tr>
			<tr>
				<td><b>4.1 Problemas y Soluciones Adoptadas</b></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr class="SubtitleTable"
						style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="28" align="center" valign="middle">#</td>
						<td align="center" valign="middle" nowrap="nowrap"><b>Problemas</b></td>
						<td width="249" height="23" align="center" valign="middle"><b>Soluciones
								Adoptadas</b></td>
						<td width="236" align="center" valign="middle"><b> Resultados</b></td>
					</tr>
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaProblemasSoluciones($idProy, $idAnio, $idMes, $idVersion);
    $RowIndex = 0;
    $t20_dificul = "";
    $t20_program = "";
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            if ($row['t20_dificul'] != "") {
                $t20_dificul = $row['t20_dificul'];
            }
            if ($row['t20_program'] != "") {
                $t20_program = $row['t20_program'];
            }
            ?>

     <tr>
						<td width="28" align="center" valign="middle"><input
							name="t20_cod_prob[]" type="hidden" id="t20_cod_prob[]"
							value="<?php echo($row['t20_cod_prob']);?>" /><?php echo($row['t20_cod_prob']);?></td>
						<td width="235" align="left" valign="middle"><textarea
								name="t20_problemas[]" maxlength="2500" rows="3"
								id="txtSubActlog[]4" style="padding: 0px; width: 100%;"><?php echo($row['t20_problemas']);?></textarea></td>
						<td align="center" nowrap="nowrap"><textarea
								name="t20_soluciones[]" maxlength="2500" rows="3"
								id="t20_soluciones[]" style="padding: 0px; width: 100%;"><?php echo($row['t20_soluciones']);?></textarea></td>
						<td align="center" nowrap="nowrap"><textarea
								name="t20_resultados[]" maxlength="2500" rows="3"
								id="t20_resultados[]" style="padding: 0px; width: 100%;"><?php echo($row['t20_resultados']);?></textarea></td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Problemas y soluciones

    if ($t20_dificul == "" || $t20_program == "") {
        $rInforme = $objInf->InformeMensualSeleccionar($idProy, $idAnio, $idMes, $idVersion);
        $t20_dificul = $rInforme['t20_dificul'];
        $t20_program = $rInforme['t20_program'];
    }
    ?>
    </tbody>
				<tfoot>
				</tfoot>
			</table>
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><span
							style="font-weight: bold; font-size: 12px;">4.2 Dificultades y
								otros aspectos</span> <br> <textarea name="t20_dificul" rows="4"
								id="t20_dificul" style="padding: 0px; width: 100%;"><?php echo($t20_dificul);?></textarea></td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <b>4.3 Programación del mes siguiente (resaltando actividades de mayor relevancia)</b><br/>
						    <textarea name="t20_program" rows="4" id="t20_program" style="padding: 0px; width: 100%;"><?php echo($t20_program);?></textarea>
					    </td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" /> <input type="hidden"
				name="t02_version" value="<?php echo($idVersion);?>" /> <input
				name="t20_anio" type="hidden" id="t20_anio"
				value="<?php echo($idAnio);?>" /> <input name="t20_mes"
				type="hidden" id="t20_mes" value="<?php echo($idMes);?>" />

			<script language="javascript" type="text/javascript">
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

		<?php if($view==md5("ver")) { ?>
	 	 $(".btn_InformeMPS").removeAttr('onclick');
		<?php } ?>

		<?php if($IsMT) { ?>
		 	 $(".btn_InformeMPS").removeAttr('onclick');
		<?php } ?>

	</script>

		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>