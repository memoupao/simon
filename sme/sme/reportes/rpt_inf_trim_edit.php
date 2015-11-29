<?php 
include("../../includes/constantes.inc.php");
include("../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant('PATH_CLASS') . "BLBene.class.php");
require_once (constant("PATH_CLASS")."BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS")."BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");

$ObjSession->VerifyProyecto();
$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('idAnio');
$idMes = $objFunc->__Request('idMes');
$idVs = $objFunc->__Request('idVersion');
$nroEntregable = $objFunc->__Request('numEntre');
$objProy = new BLProyecto();
$idVersion = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVersion);
$objInf = new BLInformes();
$rowInf = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idMes, $idVs);
$objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVersion);
$objML = new BLMarcoLogico();
$objPOA = new BLPOA();
$objMP = new BLManejoProy();

if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title>Informe de Entregable</title>
<script language="javascript" type="text/javascript"
    src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
    <form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<style>
table.headTable{
    border:none !important;
}
table.headTable tr:hover {
    border: none !important;
    padding: inherit !important;
}
.TableGrid table tbody tr:hover{
    border: none !important;
}
</style>
    <?php
    $fecha_fin_aprobada = $row['t02_fch_tam']; 
    if ($row['t02_fch_tam']=='00/00/0000') {
        $fecha_fin_aprobada = $row['t02_fch_tre'];
    }
    ?>
    <div id="divBodyAjax" class="TableGrid" style="font-size:10px; font-family:Arial; line-height: 1.5;">
            <table class="headTable"  width="650" cellpadding="0" cellspacing="0">
                <tr>
                    <td><h3 style="padding:0px; margin:0px">1. INFORMACIÓN GENERAL.-</h3></td>
                </tr>
                <tr>
                    <td><h4 style="padding:0px; margin:0px">Datos del Proyecto:</h4></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <table width="650" cellpadding="0" cellspacing="0">
                <thead>
                </thead>
                <tbody class="data" bgcolor="#FFFFFF">
                    <tr>
                        <td width="27%" height="25" align="left" valign="middle"
                            nowrap="nowrap" bgcolor="#ccffcc"><b>Informe Correspondiente al entregable N°</b></td>
                        <td align="left" valign="middle"><?php echo($nroEntregable);?> &nbsp;</td>
                        <td align="left" colspan="2" valign="middle" bgcolor="#ccffcc"><b>Código del Proyecto</b></td>
                        <td width="31%" align="left" valign="middle"><b><?php echo($row['t02_cod_proy']);?></b></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td height="32" align="left" valign="middle" bgcolor="#ccffcc"><b>Período de referencia</b></td>
                        <td  align="left" valign="middle" bgcolor="#ccffcc"><b>Del</b></td>
                        <td align="left" valign="middle"><b><?php echo($row['t02_fch_ini']);?></b></td>
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Al</b></td>
                        <td align="left" valign="middle"><b><?php echo($row['t02_fch_tre']);?></b></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td height="32" align="left" valign="middle" bgcolor="#ccffcc"><b>Fecha del presente Informe:</b></td>
                        <td colspan="4" align="left" valign="middle"><b><?php echo date('d/m/Y');?></b></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td height="32" align="left" valign="middle" bgcolor="#ccffcc"><b>Nombre del Jefe de Proyecto:</b></td>
                        <td colspan="4" align="left" valign="middle"><?php echo($objMP->getJefeProyecto($idProy, $idVersion)); ?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><b>Título del proyecto:</b></td>
                        <td colspan="4" align="left" valign="middle"><?php echo($row['t02_nom_proy']);?></td>
                    </tr>
                    <?php    
                        $rsAmbGeo = $objProy->listarAmbitoGeoAgrupado($idProy, $idVersion);
                    ?>
                    <tr style="font-size: 11px;">
                        <td height="32" rowspan="2"  align="left" valign="middle" bgcolor="#ccffcc"><b>Ubicación:</b></td>
                        <td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><b>Departamento:</b></td>                       
                        <td height="32"  align="left" valign="middle"><?php echo( $rsAmbGeo['dpto']);?></td>
                        <td height="32"  align="left" valign="middle" bgcolor="#ccffcc"><b>Provincia (s)</b></td>
                        <td height="32"  align="left" valign="middle"><?php echo( $rsAmbGeo['prov']);?></td>                        
                    </tr>
                    <tr style="font-size: 11px;">
                        <td height="32" colspan="1"  align="left" valign="middle" bgcolor="#ccffcc"><b>Distrito(s):</b></td>                        
                        <td height="32" colspan="3"  align="left" valign="middle"><?php echo( $rsAmbGeo['dist']);?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Propósito (Objetivo Central)</b></td>
                        <td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t02_fin']));?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Institución Ejecutora:</b></td>
                        <td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t01_sig_inst'].chr(13).$row['t01_nom_inst']));?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Instituciones Asociadas o Colaboradoras:</b></td>
                        <td colspan="4" align="left" valign="middle"><?php echo($row['inst_asoc_colab']);?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Población Objetivo</b></td>
                        <td colspan="4" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Fecha autorizada para el inicio del proyecto</b></td>
                        <td colspan="4" align="left" valign="middle"> <?php echo(nl2br($row['t02_fch_apro']));?></td>
                    </tr>
                    <tr style="font-size: 11px;">
                        <td align="left" valign="middle" bgcolor="#ccffcc"><b>Fecha autorizada para el término del proyecto</b></td>
                        <td colspan="4" align="left" valign="middle"> <?php echo ($fecha_fin_aprobada);?></td>
                    </tr>
                </tbody>
            </table>
            <table class="headTable"  width="650" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><h4 style="padding:0px; margin:0px">Resumen de Productos a entregar:</h4></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <table width="650" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <?php
                        $entregables = $objML->listarEntregablesReporte($idProy, $idVersion);
                        $duracion = $objML->obtenerDuracion($idProy, $idVersion);
                        $objPOA = new BLPOA();
                        $colsAdicional = 0;
                        $totalCols = count($entregables[$idAnio]) + MESES;
                    ?>
                    <tr class="rpt-head">
                        <td width="10" rowspan="2">Cod</td>
                        <td width="225" rowspan="2">Componente / Producto / Indicador</td>
                        <td rowspan="2">UM</td>
                        <td rowspan="2">Meta</td>
                        <td height="26" colspan="<?php echo($totalCols);?>">Año <?php echo($idAnio);?></td>
                    </tr>
                    <tr class="center">
                        <?php
                            $j = 0;
                            while(MESES > $j){
                                $j++;
                        ?>
                        <td><?php echo($j);?></td>
                        <?php
                                if (isset($entregables[$idAnio][$j])){
                        ?>
                        <td rowspan="2" class="row-entregable">E</td>
                        <?php } } ?>
                    </tr>   
                </thead>
                <tbody class="data">
                    <?php
                        $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
                        while ($rComp = mysql_fetch_assoc($rsComp)) {
                            $idComp = $rComp['t08_cod_comp'];
                            $progIndicadores = $objPOA->listarProgramacionIndicadores($idProy, $idVersion, $idComp);
                    ?>
                        <tr style="background: #ccffcc; color: #000000;">
                            <td class="left"><?php echo $rComp['t08_cod_comp'];?></td>
                            <td colspan="<?php echo($totalCols + 3);?>" width="50" class="left"><?php echo $rComp['t08_comp_desc'];?></td>
                        </tr>
                        <?php
                        $aRs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
                        if (mysql_num_rows($aRs) > 0) {
                            while ($ract = mysql_fetch_assoc($aRs)) {
                                $idAct = $ract["t09_cod_act"];
                        ?>
                        <tr class="row-producto">
                            <td class="left"><?php echo($ract["t08_cod_comp"].'.'.$ract["t09_cod_act"]);?></td>
                            <td colspan="<?php echo($totalCols + 3);?>" class="left"><?php echo($ract["t09_act"]);?></td>
                        </tr>
                        <?php
                        $iRs = $objML->ListadoIndicadoresAct($idProy, $idVersion, $idComp, $idAct);
                        while ($row = mysql_fetch_assoc($iRs)) {
                            $idInd = $row["t09_cod_act_ind"];
                        ?>
                        <tr>
                            <td align="left"><?php echo($row["t08_cod_comp"].'.'.$row["t09_cod_act"].'.'.$row["t09_cod_act_ind"]);?></td>
                            <td align="left"><?php echo($row["t09_ind"]);?></td>
                            <td class="col-um"><?php echo($row["t09_um"]);?></td>
                            <td class="col-meta">
                                <?php echo(number_format($row["t09_mta"]));?>
                            </td>
                            <?php
                                    $j = 0;
                                    $lista = $objML->getProgramaIndicador($idProy, $idVersion, $idComp, $idAct, $row["t09_cod_act_ind"], $idAnio);
                                    while(MESES > $j){
                                        $j++;
                                    ?>
                                        <td class="col-meta"><?php echo((array_key_exists($j, $lista)?$lista[$j]:''));?></td>
                                        <?php
                                        if (isset($entregables[$idAnio][$j])){
                                            ?>
                                        <td class="row-entregable col-meta">
                                            <?php echo($progIndicadores[$idAct][$row["t09_cod_act_ind"]][$idAnio][$j]);?>
                                        </td>
                                        <?php
                                        }
                                    }
                                } ?>
                        </tr>
                        <?php
                         }   }
                        ?>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            <table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
            <table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>           
            <table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
            <table width="650" border="0" cellpadding="0" cellspacing="0" style="font-size:10px;">
                <tbody class="data">
                    <tr style="background: #ccffcc; color: #000000; font-weight: bold;">
                        <td rowspan="2"  valign="middle" align="center" colspan="2">Concepto</td>
                        <td colspan="12" style="background: #ffffcc;"  valign="middle" align="center" width="450">AÑO <?php echo $idAnio;?></td>
                    </tr>
                    <tr class="center bold" style="background: #ffffcc;">                       
                        <?php
                            $j = 0;
                            while(MESES > $j){
                                $j++;
                        ?>
                        <td><?php echo($j);?></td>
                        <?php
                            }
                        ?>
                    </tr>
                    <tr class="center bold" style="background: #ffffcc; color: #000000;">
                        <td bgcolor="#ffffff" colspan="2">
                            Productos a Supervisar
                        </td>
                        <?php
                            $resPresup = $objInf->getResumenPresupuestal($idProy, $idVs, $idAnio);
                            while($rp = mysqli_fetch_assoc($resPresup)){
                                $j = 0;
                                while($j < ($rp['duracion'])){
                                    
                                    if($j == $rp['duracion']){
                                        ?>
                                        <td class="row-entregable"><?php echo $rp['nro_productos'];?></td>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <td>&nbsp;</td>
                                        <?php
                                    }
                                    $j++;
                                }
                            }
                        ?>
                    </tr>
                    <tr class="center bold" style="background: #ffffcc; color: #000000;">
                        <td bgcolor="#ffffff" colspan="2">
                            Entregables a Supervisar
                        </td>
                        <?php 
                        $resPresup = $objInf->getResumenPresupuestal($idProy, $idVs, $idAnio);
                        while($rp = mysqli_fetch_assoc($resPresup)){
                                $j = 0;
                                ?>
                                <td bgcolor="#e26b0a"><?php echo $rp['nro_entregable'];?></td>
                                <?php
                                while($j < ($rp['duracion'] - 1)){
                                    $j++;
                                    ?>
                                    <td>&nbsp;</td>
                                <?php
                                }
                            }
                        ?>
                    </tr>
                    <tr style="background: #ffffff; color: #000000; font-weight: bold;">
                        <td bgcolor="#ffffff"  valign="middle" align="center" colspan="2">
                            Proyeccion de gasto Mensual sin la linea de base
                        </td>
                        <?php 
                        $listaGastos = $objInf->getListaGastosPlaneadosMensuales($idProy, $idVs, $idAnio);
                        while($gt = mysql_fetch_assoc($listaGastos)){
                        ?>
                            <td class="center bold"><?php echo number_format($gt['planeado'], 2);?></td>
                        <?php
                        }
                        ?>
                    </tr>
                    <tr style="background: #ffffff; color: #000000; font-weight: bold;">
                        <td bgcolor="#ffffff" valign="middle" align="center" colspan="2">
                            <b>Monto a Desembolsar</b>
                        </td>
                        <?php 
                        $resPresup = $objInf->getResumenPresupuestal($idProy, $idVs, $idAnio);
                        while($rp = mysqli_fetch_assoc($resPresup)){
                                $j = 0;
                                ?>
                                <td class="center bold"><?php echo number_format($rp['desembolso_planeado'], 2);?></td>
                                <?php
                                while($j < ($rp['duracion'] - 1)){
                                    $j++;
                                    ?>
                                    <td>&nbsp;</td>
                                <?php
                                }
                            }
                        ?>
                    </tr>
                    <tr style="background: #ffffff; color: #000000; font-weight: bold;">
                        <td bgcolor="#ffffff" colspan="2" class="center">
                            Entregable Nro
                        </td>
                        <?php 
                        $resPresup = $objInf->getResumenPresupuestal($idProy, $idVs, $idAnio, $idMes);
                        while($rp = mysqli_fetch_assoc($resPresup)){
                                $j = 0;
                                ?>
                                <?php
                                while($j < ($rp['duracion'])){
                                    $j++;
                                    ?>
                                <?php
                                }
                                ?>
                                <td colspan="<?php echo $j;?>" bgcolor="#b7dee8" class="center bold"><?php echo $rp['nro_entregable'];?></td>
                                <?php
                            }
                        ?>
                    </tr>
                    <tr style="background: #ffffff; color: #000000; font-weight: bold;">
                        <td rowspan="2" bgcolor="#ffffff" valign="middle" align="center">Tipo de Desembolso</td>
                        <td bgcolor="#ffffff" valign="middle" align="center">
                            Adelanto
                        </td>
                        <?php
                            $adelantos = $objInf->getListaAdelantosAnio($idProy, $idVs, $idAnio);
                            while($ad = mysql_fetch_assoc($adelantos)){
                                $j=0;
                                while($j < ($ad['duracion'])){
                                    $j++;
                                    if(($j == 3 && $ad['nro_entregable'] == 1) OR ($j == 2 && $ad['nro_entregable'] != 1)){
                                        error_log("\n alditis:  j: ".$j, 3, '/var/www/log.log');
                                        error_log("\n alditis:  n: ".$ad['nro_entregable'], 3, '/var/www/log.log');
                                    ?>
                                        <td class="center bold"><?php echo number_format($ad['adelanto'], 2);?></td>
                                    <?php
                                    }
                                    else{
                                    ?>
                                        <td></td>
                                    <?php   
                                    }
                                }
                            }
                        ?>
                    </tr>
                    <tr style="background: #ffffff; color: #000000; font-weight: bold;">
                        <td bgcolor="#ffffff" valign="middle" align="center">
                            Saldo
                        </td>
                        <?php
                            $saldos = $objInf->getListaSaldosAnio($idProy, $idVs, $idAnio);
                            while($sd = mysql_fetch_assoc($saldos)){
                                $j=0;
                                while($j < ($sd['duracion'])){
                                    $j++;
                                    if($j == 1){
                                    ?>
                                        <td class="center bold"><?php echo number_format($sd['saldo'], 2);?></td>
                                    <?php
                                    }
                                    else{
                                    ?>
                                        <td></td>
                                    <?php   
                                    }
                                }
                            }
                        ?>
                    </tr>
                </body>
            </table>
            <table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
            <table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>           
            <table style="border: none;" width="650" cellpadding="0" cellspacing="0"><tr><td>&nbsp;</td></tr></table>
            <table class="headTable"  width="650" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>    
                    <td><h3 style="padding:0px; margin:0px">2.  INTRODUCCIÓN.-</h3></td>
                </tr>
                <tr>
                    <td>
                        <p style="padding:0px; margin:0px">
                        Desarrollar el avance en general del proyecto y las situaciones presentadas durante el periodo reportado que han influido en su desarrollo (sólo texto).
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4 style="padding:0px; margin:0px">Resumen de Avance alcanzado:</h4>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <table width="650" align="center" cellpadding="0" cellspacing="0">
                <tbody class="data" bgcolor="#FFFFFF">
                <tr>
                    <td colspan="2" align="left" valign="middle" bgcolor="#ccffcc"><b>Componente</b></td>
                    <td colspan="3" align="center" valign="middle" bgcolor="#ccffcc"><b>N° de Productos</b></td>
                    <td rowspan="2" align="center" valign="middle" bgcolor="#ccffcc"><b>Avance presupuestal en relación a lo programado %</b></td>
                </tr>
                <tr>
                    <td align="center" valign="middle" bgcolor="#ccffcc" width="20"><b>N°</b></td>
                    <td align="left" valign="middle" bgcolor="#ccffcc"><b>DEFINICIÓN</b></td>
                    <td align="center" valign="middle" bgcolor="#ccffcc"><b>Totales</b></td>
                    <td align="center" valign="middle" bgcolor="#ccffcc"><b>En el entregable</b></td>
                    <td align="center" valign="middle" bgcolor="#ccffcc"><b>Alcanzados al 100%</b></td>                 
                </tr>
                <?php 
                    $raa = $objInf->getAvanceAlcanzado($idProy, $idVs, $idAnio, $idMes);
                    while ($r = mysqli_fetch_assoc($raa)) { ?>
                <tr>
                    <td align="center" valign="middle"><b><?php echo $r['num_comp'];?></b></td>
                    <td align="left" valign="middle"><?php echo $r['nom_comp'];?></td>
                    <td align="center" valign="middle"><?php echo $r['nro_prods'];?></td>
                    <td align="center" valign="middle"><?php echo $r['nro_prods_entregable'];?></td>
                    <td align="center" valign="middle"><?php echo $r['nro_prods_entregable_completados'];?></td>
                    <td align="center" valign="middle"><?php echo round(($r['ejecutado_en_entregable'] / $r['planeado_en_entregable']) * 100, 2);?> %</td>
                </tr>
                <?php } ?>
                </tbody>
        </table>
        <table class="headTable" width="650">
            <tr>
                <td>
                    <div class="b" style="padding:5px 0px; margin:10px">Resumen de la ejecución presupuestal:</div>
                </td>
            </tr>
        </table>
        <?php
            $objInf = new BLInformes();
            $lista = $objInf->getInfAvanceFinancieroSE($idProy, $idAnio, $idMes);
        ?>
        <table width="650" align="center" cellpadding="0" cellspacing="0">
            <tbody class="data" bgcolor="#FFFFFF">
                <tr class="cab">
                    <td>Resumir datos de programación presupuestal</td>
                    <td>Total según convenio S/.</td>
                    <td>Total programado al entregable S/.</td>
                    <td>Total ejecutado al entregable S/.</td>
                    <td>Avance en relación a lo programado %</td>
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
                    <td class="left"><?php echo($row['sigla']);?></td>
                    <td class="av-tot" val="<?php echo($row['aporte']);?>"><?php echo(number_format($row['aporte'], 2));?></td>
                    <td class="av-prog" val="<?php echo($row['planeado']);?>"><?php echo(number_format($row['planeado'], 2));?></td>
                    <td class="av-entr" val="<?php echo($row['ejecutado']);?>"><?php echo(number_format($row['ejecutado'], 2));?></td>
                    <td class="av-prog-porc center"></td>
                </tr>
                <?php } } ?>
                <tr class="right tblhead">
                    <td class="right">Total</td>
                    <td class="av-tot" val="<?php echo($total_conv);?>"><?php echo(number_format($total_conv, 2));?></td>
                    <td class="av-prog" val="<?php echo($total_plan);?>"><?php echo(number_format($total_plan, 2));?></td>
                    <td class="av-entr" val="<?php echo($total_ejec);?>"><?php echo(number_format($total_ejec, 2));?></td>
                    <td class="av-prog-porc center"></td>
                </tr>
            </tbody>
        </table>
        <table class="headTable"  width="650" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <h3 style="padding:0px; margin:0px">3. SÍNTESIS DEL PROYECTO.-</h3>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
        </table>
<?php
function EscribirFila($resumen, $indicador, $um, $meta, $medios, $supuestos, $isMerge, $numRows)
{
    if($isMerge && $numRows>1)
    { 
        $strFila = '
        <tr style="font-size:10px;">
          <td align="left" valign="top" rowspan="'.$numRows.'">'.($resumen).'</td>
          <td align="left" valign="top" >'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
          <td align="center" valign="top" >'.$meta.'&nbsp;</td>
          <td align="left" valign="top" >'.($medios).'&nbsp;</td>
          <td align="left" valign="top" rowspan="'.$numRows.'">'.($supuestos).'&nbsp;</td>
        </tr> ' ;
        echo($strFila);
        return;
    }
    if(!$isMerge && $numRows>1)
    {
        $strFila = '
        <tr>
          <td align="left" valign="top" style="font-size:10px;">'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
          <td align="center" valign="top" >'.$meta.'&nbsp;</td>
          <td align="left" valign="top" style="font-size:10px;">'.($medios).'&nbsp;</td>
        </tr> ';
    }
    else
    { 
        if (strstr($indicador,'.-')) {
            $arTextIndi = explode('.-',$indicador);
            $textIndi = trim($arTextIndi[1]);
            if (!empty($textIndi)) {
                $strFila = '
                            <tr style="font-size:10px;">
                              <td align="left" valign="top" >'.($resumen).'</td>
                              <td align="left" valign="top" >'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
                              <td align="center" valign="top" >'.$meta.'&nbsp;</td>
                              <td align="left" valign="top" >'.($medios).'&nbsp;</td>
                              <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
                            </tr> ' ;
            } else {
                $strFila = '
                        <tr style="font-size:10px;">
                          <td align="left" valign="top" >'.($resumen).'</td>
                          <td align="left" valign="top" ></td>
                          <td align="center" valign="top" >'.$meta.'&nbsp;</td>
                          <td align="left" valign="top" >'.($medios).'&nbsp;</td>
                          <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
                        </tr> ' ;
            }
        } else {
            $strFila = '
                    <tr style="font-size:10px;">
                      <td align="left" valign="top" >'.($resumen).'</td>
                      <td align="left" valign="top" >'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
                      <td align="center" valign="top" >'.$meta.'&nbsp;</td>
                      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
                      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
                    </tr> ' ;
        }
    }
    echo($strFila);
}
$ML = $objML->GetML($idProy, $idVersion);
?>      
<table width="650" align="center" cellpadding="0" cellspacing="0"  >
  <tbody class="data" bgcolor="#FFFFFF">
  <tr>
      <td  bgcolor="#ccffcc" width="21%" valign="top"><p align="center"><b>Resumen Narrativo de Objetivos</b></p></td>
      <td  bgcolor="#ccffcc" width="27%" valign="top"><p align="center"><b>Indicadores Verificables Objetivamente (IVO)</b> </p></td>
      <td  bgcolor="#ccffcc" width="10%" valign="top"><p align="center"><b>Metas Globales</b></p></td>
      <td  bgcolor="#ccffcc" width="22%" valign="top"><p align="center"><b>Medios de Verificacion</b></p></td>
      <td  bgcolor="#ccffcc" width="20%" valign="top"><p align="center"><b>Supuestos</b></p></td>
    </tr>
    <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >FINALIDAD</td>
    </tr>
    <?php 
    /* --------------------------------------- Finalidad ----------------------------------------------- */
    $rsIndFin = $objML->ListadoIndicadoresOD($idProy, $idVersion);
    $rsSupFin = $objML->ListadoSupuestosOD($idProy, $idVersion);
    $NumRows = mysql_num_rows($rsIndFin);
    $rowInd = mysql_fetch_assoc($rsIndFin) ; 
    $Sup =  $objFunc->resultToString($rsSupFin,array('t06_cod_fin_sup','t06_sup'));
    EscribirFila($ML['t02_fin'],  $rowInd['t06_cod_fin_ind'].".- ".$rowInd['t06_ind'], $rowInd['t06_um'], $rowInd['t06_mta'], $rowInd['t06_fv'], $Sup,  true, $NumRows);
    while($rowInd = mysql_fetch_assoc($rsIndFin))  
    {
        EscribirFila($ML['t02_fin'],  $rowInd['t06_cod_fin_ind'].".- ".$rowInd['t06_ind'], $rowInd['t06_um'], $rowInd['t06_mta'], $rowInd['t06_fv'], $Sup,  false, $NumRows);   
    }
    /* -------------------------------------------------------------------------------------------------- */
    ?>
     <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >PROPOSITO</td>
     </tr>
    <?php
    /* --------------------------------------- Proposito ----------------------------------------------- */
    $rsIndProp = $objML->ListadoIndicadoresOG($idProy, $idVersion);
    $rsSupProp = $objML->ListadoSupuestosOG($idProy, $idVersion);
    $NumRows = mysql_num_rows($rsIndProp);
    $rowInd = mysql_fetch_assoc($rsIndProp) ; 
    $Sup =  $objFunc->resultToString($rsSupProp,array('t07_cod_prop_sup','t07_sup'));
    EscribirFila($ML['t02_pro'], $rowInd['t07_cod_prop_ind'].".- ".$rowInd['t07_ind'], $rowInd['t07_um'], $rowInd['t07_mta'], $rowInd['t07_fv'], $Sup,  true, $NumRows);
    while($rowInd = mysql_fetch_assoc($rsIndProp))  
    {
        EscribirFila($ML['t02_pro'], $rowInd['t07_cod_prop_ind'].".- ".$rowInd['t07_ind'], $rowInd['t07_um'], $rowInd['t07_mta'], $rowInd['t07_fv'], $Sup,  false, $NumRows);   
    }
    /* -------------------------------------------------------------------------------------------------- */
    ?>
     <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >COMPONENTES</td>
     </tr>
    <?php
    /* --------------------------------------- Componentes ----------------------------------------------- */
    $rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
    while($rowcomp = mysql_fetch_assoc($rsComp))  
    {
        $rsIndComp = $objML->ListadoIndicadoresOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
        $rsSupComp = $objML->ListadoSupuestosOE($idProy, $idVersion,  $rowcomp['t08_cod_comp']);
        $NumRows = mysql_num_rows($rsIndComp);
        $rowInd = mysql_fetch_assoc($rsIndComp) ; 
        $Sup =  $objFunc->resultToString($rsSupComp,array('t08_cod_comp_sup','t08_sup'));
        $num_correlativo = $rowcomp['t08_cod_comp'].".".$rowInd['t08_cod_comp_ind'].".- ".$rowInd['t08_ind'];
        EscribirFila($rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'], $num_correlativo, $rowInd['t08_um'], $rowInd['t08_mta'], $rowInd['t08_fv'], $Sup,  true, $NumRows);
        while($rowInd = mysql_fetch_assoc($rsIndComp))  
        {
            $num_correlativo = $rowcomp['t08_cod_comp'].".".$rowInd['t08_cod_comp_ind'].".- ".$rowInd['t08_ind'];                           
            EscribirFila($rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'], $num_correlativo , $rowInd['t08_um'], $rowInd['t08_mta'], $rowInd['t08_fv'], $Sup,  false, $NumRows);
        }
    }
    /* -------------------------------------------------------------------------------------------------- */
    ?>
    <tr >
      <td colspan="5" align="left" valign="middle"  bgcolor="#ccffcc"  ><b>PRODUCTOS</b></td>
     </tr>
     <tr >
      <td align="left" valign="middle"  bgcolor="#ccffcc"  >&nbsp;</td>
      <td align="left" valign="middle"  bgcolor="#ccffcc"  ><b>Indicadores de Producto</b></td>
      <td align="left" valign="middle"  bgcolor="#ccffcc"  >&nbsp;</td>
      <td align="left" valign="middle"  bgcolor="#ccffcc"  >&nbsp;</td>
      <td align="left" valign="middle"  bgcolor="#ccffcc"  >&nbsp;</td>
     </tr>
      <?php
    /* --------------------------------------- Indicadores ----------------------------------------------- */
    $rsAct = $objML->ListadoActividades($idProy, $idVersion);
    while($rowact = mysql_fetch_assoc($rsAct))  
    {
        $rsIndAct = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowact['t08_cod_comp'], $rowact['t09_cod_act']);
        $NumRows = mysql_num_rows($rsIndAct);
        $rowInd = mysql_fetch_assoc($rsIndAct) ; 
        if($NumRows > 0)  
        { 
            $Sup =  "";
            $Actividad = $rowact['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'. '.$rowact['t09_act'] ;
            EscribirFila($Actividad,  $rowInd['t09_ind'], $rowInd['t09_um'], $rowInd['t09_mta'], $rowInd['t09_fv'], $Sup,  true, $NumRows);
            while($rowInd = mysql_fetch_assoc($rsIndAct))  
            {
                EscribirFila($Actividad,  $rowInd['t09_ind'], $rowInd['t09_um'], $rowInd['t09_mta'], $rowInd['t09_fv'], $Sup,  false, $NumRows);
            }
        }
    }
    /* -------------------------------------------------------------------------------------------------- */
    ?>
</tbody>  
</table>
        <table class="headTable"  width="650" cellpadding="0" cellspacing="0">
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <h3 style="padding:0px; margin:0px">4. ANÁLISIS DEL AVANCE EN RELACIÓN A LOS PRODUCTOS.-</h3>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
        </table>
<?php
    $rsProd = $objInf->listarProductosPorEntregable($idProy, $idVersion, $idAnio);
    while ($rowProd = mysql_fetch_array($rsProd)) {
        $idProd = $rowProd['t09_cod_act']; 
        $idMes = $rowProd['t02_mes'];
        $idComp = $rowProd['t08_cod_comp'];
    ?>  
    <table cellspacing="0" cellpadding="0" align="center" width="650">
        <tbody class="data" bgcolor="#FFFFFF">
        <tr>
            <td>Producto: <?php echo $rowProd['producto']?></td>
        </tr>
        <tr>
            <td style="padding:0px; margin:0px; border:0px;">
                <table cellspacing="0" cellpadding="0" align="center" width="650">
                    <tbody class="data" bgcolor="#FFFFFF">
                    <?php
                    $iRs = $objInf->listarIndicadoresProductoEntregable($idProy, $idVersion, $idComp, $idProd, $idAnio, $idMes);
                    $RowIndex = 0;
                    if ($iRs->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>
                    <tr>
                        <td bgcolor="#ccffcc">Indicador del Producto</td>
                        <td bgcolor="#ccffcc" colspan="3">Metas</td>
                    </tr>
                    <tr>
                        <td bgcolor="#ccffcc" rowspan="2">
                            <?php echo(($RowIndex + 1).".- ".$row['indicador']);?>
                            <span class="nota">Unidad Medida:</span> <?php echo( $row['t09_um']);?>
                        </td>
                        <td bgcolor="#ccffcc" style="word-break: break-all;">Total del Proyecto</td>
                        <td bgcolor="#ccffcc" style="word-break: break-all;">Planeada Acumulada al Entregable</td>
                        <td bgcolor="#ccffcc" style="word-break: break-all;">Ejecutada Acumulada al Entregable</td>
                    </tr>
                    <tr>
                        <td><?php echo( $row['plan_mtatotal']);?></td>
                        <td><?php echo( $row['plan_mtames']);?></td>
                        <td><?php echo($row['ejec_mtames']);?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            Análisis<br>
                            <?php echo($row['descripcion']);?>
                        </td>                       
                    </tr>
                    <?php }
                    } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
<?php 
    }
?>      
        <table cellspacing="0" cellpadding="0" width="650" class="headTable">
                <tbody><tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <h3 style="padding:0px; margin:0px">5. ANÁLISIS DEL AVANCE EN RELACIÓN A LOS RESULTADOS.- </h3>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
        </tbody></table>
        <?php
        $rowAnalisisAvance = $objInf->listarAnalisisInfEntregable($idProy, $idVs, $idAnio, $idMes);
        //var_dump($rowAnalisisAvance);
        ?>
        <table cellspacing="0" cellpadding="0" width="650" class="headTable">
                <tbody><tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <b> Análisis de Resultados</b>
                    </td>
                </tr>
                <tr>
                    <td align="justify">
                        <?php echo nl2br($rowAnalisisAvance['t25_resulta']);?>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <b> Conclusiones</b>
                    </td>
                </tr>
                <tr>
                    <td align="justify">
                        <?php echo nl2br($rowAnalisisAvance['t25_conclu']);?>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <b> Limitaciones</b>
                    </td>
                </tr>
                <tr>
                    <td align="justify">
                        <?php echo nl2br($rowAnalisisAvance['t25_limita']);?>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <b> Factores Positivos</b>
                    </td>
                </tr>
                <tr>
                    <td align="justify">
                        <?php echo nl2br($rowAnalisisAvance['t25_fac_pos']);?>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <b>  Perspectivas para el Próximo Entregable</b>
                    </td>
                </tr>
                <tr>
                    <td align="justify">
                        <?php echo nl2br($rowAnalisisAvance['t25_perspec']);?>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                </tbody>
        </table>
        <table cellspacing="0" cellpadding="0" width="650" class="headTable">
                <tbody><tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <h3 style="padding:0px; margin:0px">6. ANEXOS.-</h3>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
        </tbody></table>
        <table cellspacing="0" cellpadding="0" width="650" class="headTable">
                <tbody><tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <b> Cuadro consolidado de beneficiarios a la fecha</b>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <b> Cuadro de avance de productos</b>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td>
                        <b> Otros Anexos</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                        <?php
                        $rsOtrosAnexos = $objInf->listarAnexosInfEntregable($idProy, $idVs, $idAnio, $idMes);
                        if ($rsOtrosAnexos->num_rows > 0) {
                            while ($rowOtrosAnexos = mysqli_fetch_assoc($rsOtrosAnexos)) {
                                $urlFile = $rowOtrosAnexos['t25_url_file'];
                                $filename = $rowOtrosAnexos['t25_nom_file'];
                                $path = constant('APP_PATH') . "sme/proyectos/informes/anx_entregable";
                                $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
                            ?>
                            <li>
                                <b>
                                    <a href="<?php echo($href);?>" title="Descargar Archivo" target="_blank"><?php echo($rowOtrosAnexos['t25_nom_file']);?></a>
                                </b>
                                <br/>
                                <p><?php echo( $rowOtrosAnexos['t25_desc_file']);?></p>
                            </li>
                            <?php 
                            }
                        }
                        ?>
                        </ul>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                </tbody>
        </table>
            <p>
                <script language="JavaScript" type="text/javascript">
    function ViewML(codigo,version)
    {
        urlReport = "rpt_ml.php";
        urlParams = "&idProy="+codigo+"&idVersion="+version;
        urlViewer = "reportviewer.php?link="+urlReport+"&title=Marco Logico" + urlParams;
        var win =  window.open(urlViewer,"MarcoLogico","");
        win.focus();
    }
    function ViewPOA(codigo,version)
    {
        urlReport = "rpt_poa.php";
        urlParams = "&idProy="+codigo+"&idVersion="+version;
        urlViewer = "reportviewer.php?link="+urlReport+"&title=Plan Operativo" + urlParams;
        var win =  window.open(urlViewer,"PlanOperativo","");
        win.focus();
    }
    $('.av-prog-porc').each(function () {
        var total = parseFloat($(this).siblings('.av-tot').attr('val'));
        var entr = parseFloat($(this).siblings('.av-entr').attr('val'));
        var prog = parseFloat($(this).siblings('.av-prog').attr('val'));
        var prog_porc = total>0?parseFloat((entr/prog)*100).toFixed(2):0.00;
        $(this).html(prog_porc + ' %');
    });
    </script>
            </p>
<?php
function retComponentes($proy, $vs)
{
    $objML = new BLMarcoLogico();
    $rsComp = $objML->ListadoDefinicionOE($proy, $vs);
    while ($row = mysql_fetch_assoc($rsComp)) {
        echo ($row['t08_cod_comp'] . ". " . $row['t08_comp_desc'] . "<br />");
    }
    $rsComp = NULL;
}
function retPresupFuentesFinanc($proy, $vs)
{
    $objPresup = new BLPresupuesto();
    $rsFte = $objPresup->RepFuentesFinac($proy, $vs);
    $total = 0;
    if ($rsFte->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($rsFte)) {
            echo ("<tr style='font-size:10px;'>");
            echo ("     <td class='ClassText'>" . $row['fuente'] . "</td>");
            echo ("     <td class='ClassText' align='right'>" . number_format($row['total'], 2) . "</td>");
            echo ("</tr>");
            $total += $row['total'];
        }
        $rsFte->free();
        echo ("<tfoot><tr style='font-size:10px;'>");
        echo ("     <td class='ClassText'> Total </td>");
        echo ("     <td class='ClassText' align='right'>" . number_format($total, 2) . "</td>");
        echo ("</tr></tfoot>");
    }
}
function retInformeMensual($proy, $fecini)
{
    $objInf = new BLInformes();
    $rsInf = $objInf->InformeMensualListado($proy);
    while ($row = mysqli_fetch_assoc($rsInf)) {
        $linkInforme = "<a href='#' target='_blank'>" . $row['fec_pre'] . "</a>";
        echo ('<tr class="ClassText">');
        echo (' <td width="18" align="center">' . $row['nummes'] . '</td>');
        echo (' <td width="68" align="center">' . $row['fec_plan'] . '</td>');
        echo (' <td width="39" align="center">' . $linkInforme . '</td>');
        echo ('</tr>');
    }
    $rsInf->free();
}
?>
        </div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
</html>
<?php } ?>