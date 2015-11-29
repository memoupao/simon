<?php include("../../includes/constantes.inc.php"); ?><?php include("../../includes/validauser.inc.php"); ?><?php

require_once (constant("PATH_CLASS") . "BLFE.class.php");

$objFE = new BLFE();
$Concurso = $objFunc->__Request('cboConcurso');
$Anio = $objFunc->__Request('anio');

?><?php if($objFunc->__QueryString()=="") { ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" --><head><meta http-equiv="Content-Type"	content="text/html; charset=charset=utf-8" /><!-- InstanceBeginEditable name="doctitle" --><title></title><!-- InstanceEndEditable --><script language="javascript" type="text/javascript"	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script><link href="../../css/reportes.css" rel="stylesheet" type="text/css"	media="all" /><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable --></head><body>	<form id="frmMain" name="frmMain" method="post" action="#"><?php } ?>	<div id="divBodyAjax" class="TableGrid">			<!-- InstanceBeginEditable name="BodyAjax" -->	<?php

function LoadReportData(&$pResultSet, $pLv1, $pLv2, $pLv3, $pSum)
{
    $total = 0;
    $aReportData = array();
    while ($row = mysqli_fetch_assoc($pResultSet)) {
        $aSum = $row[$pSum] == "" ? 0 : $row[$pSum];
        $aReportData[$row[$pLv1]][$row[$pLv2]][$row[$pLv3]] = $aSum;
        $total += $aSum;
    }
    return $aReportData;
}

function GenerateReportOutput($pReportData)
{
    $aTotal = 0;
    foreach ($pReportData as $aLv1Name => $aLv1ArrData) {
        $aLv1Rows = 0;
        $aLv1Flg = true;
        $aLv2Td = '';
        foreach ($aLv1ArrData as $aLv2Name => $aLv2ArrData) {
            $aLv2Rows = 0;
            $aLv2Flg = true;
            $aLv3Td = '';
            foreach ($aLv2ArrData as $aLv3Name => $aSumNum) {
                $aLv1Rows ++;
                $aLv2Rows ++;
                $aTotal += $aSumNum;
                if (! $aLv2Flg)
                    $aLv3Td .= "<tr>";
                $aLv3Td .= "<td>$aLv3Name</td><td align='center'>" . number_format($aSumNum) . "</td></tr>";
                $aLv2Flg = false;
            }
            if (! $aLv1Flg)
                $aLv2Td .= "<tr>";
            $aLv2Td .= "<td rowspan='$aLv2Rows' " . "align='left' valign='middle'>" . $aLv2Name . "</td>" . $aLv3Td;
            $aLv1Flg = false;
        }
        $aLv1Td = "<tr><td rowspan='$aLv1Rows' " . "align='left' valign='middle'>" . $aLv1Name . "</td>" . $aLv2Td . "</tr>";
        echo $aLv1Td;
    }
    
    return $aTotal;
}
?>	<table width="660px" align="center" cellpadding="0" cellspacing="0">				<thead>					<tr>						<td width="159px" valign="middle"><p align="center">Tipo Servicio</p></td>						<td width="159px" valign="middle"><p align="center">Tema</p></td>						<td width="159px" valign="middle"><p align="center">Región</p></td>						<td width="69px" valign="middle"><p align="center">Total</p></td>					</tr>				</thead>				<tbody class="data" bgcolor="#FFFFFF" style="font-size: 11px;">		<?php
$aResultSet = $objFE->RepTipoServRegion($Anio, $Concurso);
$aReportData = LoadReportData($aResultSet, 'servicio', 'modulo', 'region', 'benef');
$aReportTotal = GenerateReportOutput($aReportData);

?>		</tbody>				<tbody					style="text-align: right; font-size: 10px; font-weight: bold;">					<tr>						<td height="24" align="center" valign="middle" colspan='3'>TOTAL</td>						<td align="center" valign="middle"><?php echo $aReportTotal; ?></td>					</tr>				</tbody>			</table>			<br />			<br />			<br />			<table width="660px" align="center" cellpadding="0" cellspacing="0">				<thead>					<tr>						<td width="159px" valign="middle"><p align="center">Tipo Servicio</p></td>						<td width="159px" valign="middle"><p align="center">Tema</p></td>						<td width="159px" valign="middle"><p align="center">Actividad								Económica Principal</p></td>						<td width="69px" valign="middle"><p align="center">Total</p></td>					</tr>				</thead>				<tbody class="data" bgcolor="#FFFFFF" style="font-size: 11px;">		<?php
$aResultSet = $objFE->RepTipoServSector($Anio, $Concurso);
$aReportData = LoadReportData($aResultSet, 'servicio', 'modulo', 'actividad', 'benef');
$aReportTotal = GenerateReportOutput($aReportData);
?>		</tbody>				<tbody					style="text-align: right; font-size: 10px; font-weight: bold;">					<tr>						<td height="24" align="right" valign="middle" colspan='3'>TOTAL</td>						<td align="center" valign="middle"><?php echo $aReportTotal; ?></td>					</tr>				</tbody>			</table>			<!-- InstanceEndEditable -->		</div>	<?php if($objFunc->__QueryString()=="") { ?>	</form></body><!-- InstanceEnd --></html><?php } ?>