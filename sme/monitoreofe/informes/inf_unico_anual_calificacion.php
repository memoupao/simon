<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('t55_ver_inf');
$idAnio = $objFunc->__POST('idAnio');
$idNum = $objFunc->__POST('idNum');

if ($idProy == "" && $idNum == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('t55_ver_inf');
    $idAnio = $objFunc->__POST('idAnio');
    $idNum = $objFunc->__GET('idNum');
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

<title>Analisis de Avances</title>
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
<?php

$objInf = new BLInformes();

?>
<table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar datos de Analisis de Avances"  onclick="LoadCalifMonitor(true);" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div--> <input type="button" value="Refrescar"
					class="btn_save_custom"
					title="Refrescar datos de Analisis de Avances"
					onclick="LoadCalifMonitor(true);" />
				</td>
				<td width="10%" rowspan="2" align="right" valign="middle"></td>
			</tr>
			<tr>
				<td><span style="font-weight: bold;">Cuadro de Calificaciones de
						Monitor Externo y Monitor Interno</span></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>

						<td align="center" valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								style="font-size: 10px;">
								<tr>
									<td width="29%" align="center" bgcolor="#E8E8E8"><strong>Fecha</strong></td>
									<td width="28%" align="center" valign="middle"
										bgcolor="#E8E8E8"><strong>Calificación Monitor Externo</strong></td>
									<td width="22%" align="center" valign="middle"
										bgcolor="#E8E8E8"><strong>Calificación Monitor Interno</strong></td>
									<td width="21%" align="center" valign="middle"
										bgcolor="#E8E8E8"><strong>Calificación Monitor Financiero</strong></td>
								</tr>
        <?php
        for ($x = 1; $x <= $idAnio; $x ++) {
            $retCalif = $objInf->RepCalificacionIUA($idProy, $x);
            while ($rowInf = mysqli_fetch_assoc($retCalif)) {
                $msg_ME = '';
                $msg_MI = '';
                $msg_MF = '';
                $valoraME = "";
                if ($rowInf['Califica_ME'] != '') {
                    $msg_ME = $objFunc->calificacionInforme($rowInf['Califica_ME'], array(
                        "style='background-color:red'",
                        "style='background-color:#FC0;'",
                        "style='background-color:#70FB60;'"
                    ), $valoraME);
                }
                
                $valoraMI = "";
                if ($rowInf['Califica_MI'] != '') {
                    $msg_MI = $objFunc->calificacionInforme($rowInf['Califica_MI'], array(
                        "style='background-color:red'",
                        "style='background-color:#FC0;'",
                        "style='background-color:#70FB60;'"
                    ), $valoraMI);
                }
                
                $valoraMF = "";
                if ($rowInf['Califica_MF'] != '') {
                    if ($rowInf['Califica_MF'] == 0) {
                        $valoraMF = "style='background-color:red'";
                        $msg_MF = "Desaprobado";
                    }
                    if ($rowInf['Califica_MF'] == 1) {
                        $valoraMF = "style='background-color:#FC0;'";
                        $msg_MF = "Aprobado con Reservas";
                    }
                    if ($rowInf['Califica_MF'] == 2) {
                        $valoraMF = "style='background-color:#70FB60;'";
                        $msg_MF = "Aprobado";
                    }
                }
                
                ?>
        <tr>
									<td align="center"><?php echo($rowInf['fecha']);?></td>
									<td align="center" valign="middle" <?php echo($valoraME);?>><?php echo($msg_ME);?></td>
									<td align="center" valign="middle" <?php echo($valoraMI);?>><?php echo($msg_MI);?></td>
									<td align="center" valign="middle" <?php echo($valoraMF);?>><?php echo($msg_MF);?></td>
								</tr>
        
        <?php
            }
            
            // $retInfMoni->free();
        }
        ?>
        
      </table>
						</td>

					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" class="AnalisisAvances" /> <input
				type="hidden" name="t55_id" value="<?php echo($idNum);?>"
				class="AnalisisAvances" /> <input type="hidden" name="t55_ver_inf"
				value="<?php echo($idVersion);?>" class="AnalisisAvances" />

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>