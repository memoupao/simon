<?php
 
include("../../includes/constantes.inc.php"); 
include("../../includes/validauser.inc.php"); 

require (constant("PATH_CLASS") . "HardCode.class.php");
require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");
require (constant("PATH_CLASS") . "BLReportes.class.php");
require (constant("PATH_CLASS") . "BLPOA.class.php");
require (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require (constant("PATH_CLASS") . "BLManejoProy.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$idProy = $objFunc->__POST('idProy');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');
$objRep = new BLReportes();
$objPresup = new BLPresupuesto();
$objInformes = new BLInformes();
$rowinf = $objInformes->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);
$objProy = new BLProyecto();
$idVs = $objProy->MaxVersion($idProy);
$HardCode = new HardCode();
$idFte = $HardCode->codigo_Fondoempleo;
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVs);
$row = $objRep->RepFichaProy($idProy, $idVs);
$rsSector = $objProy->SectoresProductivos_Listado($idProy);

$fecha_fin_aprobada = $row['t02_fch_tam']; 
if ($row['t02_fch_tam']=='00/00/0000') {
    $fecha_fin_aprobada = $row['t02_fch_tre'];
}

?>
<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
    content="text/html; charset=charset=utf-8" />
<title>Informe Mensual</title>
<script language="javascript" type="text/javascript"
    src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
    media="all" />
</head>
<body>
    <form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">
            <table width="650" cellpadding="0" cellspacing="0">
                <tbody class="data" bgcolor="#FFFFFF">
                    <tr valign="middle">
                        <td width="24%" height="25" nowrap="nowrap" bgcolor="#E8E8E8"><b>Número del Informe</b></td>
                        <td><b><?php echo($objFunc->NumeroMes($idAnio, $idMes));?></b></td>
                        <td bgcolor="#E8E8E8"><b>Código del Proyecto</b></td>
                        <td colspan="2" width="34%"><b><?php echo($row['t02_cod_proy']);?></b></td>
                    </tr>
                    <tr valign="middle">
                        <td height="32" bgcolor="#E8E8E8"><b>Periodo de Referencia</b></td>
                        <td colspan="4">
                            <b>Año <?php echo($idAnio);?> &nbsp; Mes <?php echo($idMes);?>
                            &nbsp;&nbsp;&nbsp;
                            (<?php echo($objFunc->MonthName($rowinf['t40_periodo'])."-". substr($rowinf['t40_fch_pre'],strlen($rowinf['t40_fch_pre'])-4) );?>)
                        </td>
                    </tr>
                    <tr valign="middle">
                        <td height="32" bgcolor="#E8E8E8"><b>Fecha de Presentación</b></td>
                        <td colspan="4"><b><?php echo($rowinf['t40_fch_pre']);?></b></td>
                    </tr>
                    <!-- <tr valign="middle">
                        <td height="32" bgcolor="#FFFFAA"><b>Supervisor del Proyecto</b></td>
                        <td colspan="4"><?php echo($row['jefe_proy']);?></td>
                    </tr> -->
                    <tr valign="middle">
                        <td bgcolor="#FFFFAA"><b>Gestor de Proyecto</b></td>
                        <td colspan="4"><?php echo($row['moni_tema']);?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#FFFFAA"><b>Supervisor Externo</b></td>
                        <td colspan="4"><?php echo($row['moni_exte']);?></td>
                    </tr>
                    <tr valign="middle">
                        <td height="32" bgcolor="#E8E8E8"><b>Título del Proyecto</b></td>
                        <td colspan="4"><?php echo(nl2br($row['t02_nom_proy']));?></td>
                    </tr>
                    <?php while($rsS = mysqli_fetch_assoc($rsSector))  { ?>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Sector</b></td>
                        <td bgcolor="#E8E8E8"><b>Sector Productivo</b></td>
                        <td><?php echo($rsS['sector']);?></td>
                        <td bgcolor="#E8E8E8"><b>Sub Sector</b></td>
                        <td><?php echo($rsS['subsector']);?></td>
                    </tr>
                    <?php }?>
                    <?php    
                        $rsAmbGeo = $objProy->listarAmbitoGeoAgrupado($idProy, $idVs);
                    ?>
                    <tr valign="middle">
                        <td rowspan="2" bgcolor="#E8E8E8"><b>Ubicación:</b></td>
                        <td bgcolor="#E8E8E8"><b>Departamento</b></td>
                        <td><?php echo( $rsAmbGeo['dpto']);?> </td>
                        <td bgcolor="#E8E8E8"><b>Provincia(s)</b></td>
                        <td><?php echo( $rsAmbGeo['prov']);?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Distrito(s)</b></td>
                        <td colspan="3"> <?php echo( $rsAmbGeo['dist']);?> </td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Propósito del Proyecto</b></td>
                        <td colspan="4"><?php echo(nl2br($row['t02_pro']));?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Institución Ejecutora</b></td>
                        <td colspan="4"><?php echo(nl2br($row['t01_nom_inst']));?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Instituciones Asociadas o Colaboradoras</b></td>
                        <td colspan="4"><?php echo($row['inst_asoc_colab']);?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Población Beneficiaria</b></td>
                        <td colspan="4"><?php echo(nl2br($row['t02_ben_obj']));?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Fecha real de Inicio del proyecto</b></td>
                        <td colspan="4"><?php echo ($row['t02_fch_ini']);?></td>
                    </tr>
                    <tr valign="middle">
                        <td bgcolor="#E8E8E8"><b>Fecha programada para el término del proyecto</b></td>
                        <td colspan="4"><?php echo ($fecha_fin_aprobada);?></td>
                    </tr>
                    <tr valign="middle">
                        <td rowspan="3" bgcolor="#E8E8E8"><b>ANEXOS</b></td>
                        <td colspan="4">
                            <a href="#" onclick="ExportarAnexos('1');">Anexo 1: Resumen Financiero del Proyecto</a></td>
                    </tr>
                    <tr valign="middle">
                        <td colspan="4">
                            <a href="#" onclick="ExportarAnexos('2');"> Anexo 2: Resumen del Excedente por Ejecutar</a></td>
                    </tr>
                    <tr valign="middle">
                        <td colspan="4">
                            <a href="#" onclick="ExportarAnexos('3');">Anexo 3: Presupuesto Ejecutado acumulado</a></td>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
            <br /> <br />
            <table width="650" border="0" cellpadding="0" cellspacing="0">
                <tr bgcolor="#CCCCFF">
                    <td colspan="10" align="left" valign="middle"><b
                        style="color: blue;">1. GASTOS FONDOEMPLEO</b></td>
                </tr>
                <tr colspan="10">
                    <td align="left" valign="middle">&nbsp;</td>
                </tr>
                <tbody class="data">
                <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
                    <td width="53" height="28" rowspan="2" align="center"
                        valign="middle">Codigo</td>
                    <td width="197" style="word-break: break-all;" height="28" rowspan="2" align="center"
                        valign="middle">Categoria de Gastos</td>
                    <td width="57" rowspan="2" align="center" valign="middle">U.M.</td>
                    <td width="41" rowspan="2" align="center" valign="middle">Meta</td>
                    <td width="80" style="word-break: break-all;" rowspan="2" align="center" valign="middle">Total
                        Presupuesto</td>
                    <td width="80" rowspan="2" align="center" valign="middle" style="word-break: break-all;">Total
        <?php if($idFte==$HardCode->codigo_Fondoempleo){echo("Fondoempleo");} else {echo("Fuente Financ");} ?></td>
                    <td colspan="2" align="center" valign="middle" style="word-break: break-all;">Información del Mes</td>
                    <td colspan="2" rowspan="2" align="center" valign="middle" style="word-break: break-all;">Observaciones
                        Monitor</td>
                </tr>
                <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
                    <td width="73" align="center" valign="middle">Planeado</td>
                    <td width="81" align="center" valign="middle">Ejecutado</td>
                </tr>
                </tbody>
                <tbody class="data">
        <?php
    $total_presup = 0;
    $total_ejecutado = 0;
    $LC = $objInformes->ListaComponentes($idProy);
    while ($rowCo = mysqli_fetch_assoc($LC)) 
    {
        $idComp = $rowCo['t08_cod_comp'];
        ?>
        <tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
                        <td height="33" colspan="10" align="left" valign="middle"><b>Componente&nbsp;</b> <?php echo $rowCo['componente']; ?>
          </td>
                    </tr>
      <?php
        $objPresup = new BLPresupuesto();
        $iRsAct = $objPresup->ListaActividades_InfFinanc($idProy, $idComp, $idAnio, $idMes, $idFte);
        while ($rowAct = mysqli_fetch_assoc($iRsAct)) 
        {
            $idAct = $rowAct['t09_cod_act'];
            $total_presup += $rowAct['total_presup'];
            $total_ejecutado += $rowAct['total_ejec'];
            $total_planeado += $rowAct['planeado'];
            $total_fuente += $rowAct['total_fuente'];
            ?>  
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo( $rowAct['actividad']);?></td>
                        <td align="right"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowAct['total_fuente'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowAct['planeado'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowAct['total_ejec'],2,'.',','));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
    <?php
            $iRs = $objPresup->ListaSubActividades_InfFinanc($idProy, $idComp, $idAct, $idAnio, $idMes, $idFte);
            while ($rowsub = mysqli_fetch_assoc($iRs)) 
            {
                ?>
    <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
                        <td align="left"><?php echo( $rowsub['subactividad']);?></td>
                        <td align="center"><?php echo($rowsub['um']);?></td>
                        <td align="center"><?php echo($rowsub['meta']);?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_fuente'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowsub['planeado'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_ejec'],2,'.',','));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
      <?php
                $iRsCateg = $objPresup->ListaCategoriaGasto_InfFinanc($idProy, $idComp, $idAct, $rowsub['t09_cod_sub'], $idAnio, $idMes, $idFte);
                while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) 
                {
                    $idtxtAvance = $idFte . "_txtRubroEjec[]";
                    $idtxtCodigo = $idFte . "_Id[]";
                    $inputTextAvance = $rowsubCateg['total_ejec'];
                    $inputTextID = "<input  value='" . $rowsubCateg['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
                    $inputTextEst = "<input  value='" . $rowsubCateg['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
                    $inputTextObs = "<input  value='" . $rowsubCateg['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
                    // /*
                    $styleRow = 'style="font-weight:300; color:navy;"';
                    if ($rowsubCateg['t41_estado'] == '2')                     // Verificar que el MF no haya ingresado comentarios
                    {
                        $styleRow = 'style="font-weight:300; color:red;"';
                    }
                    // */
                    ?>
    <tr <?php echo($styleRow);?>>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['categoria']);?></td>
                        <td align="center" nowrap="nowrap">&nbsp;</td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextID); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_presup'],2,'.',','));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_fuente'],2,'.',','));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['planeado'],2,'.',','));?></td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap">
        <?php echo($inputTextEst); echo($inputTextObs); ?>
        </td>
                    </tr>
      <?php } $iRsCateg->free(); // Fin de Categorias de Gastos ?>
      <?php } $iRs->free(); // Fin de SubActividades ?>
      <?php } $iRsAct->free(); // Fin de Actividades    ?>
      <?php
    } // fclose(handle)in de while componentes
    ?> 
    <tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
                        <td height="33" colspan="10" align="left" valign="middle"><b>Componente&nbsp;</b> <?php echo "10.- Manejo del proyecto"; ?>
      </td>
                    </tr>
        <?php
    $idAct = 1;
    $objMP = new BLManejoProy();
    $rsPer = $objMP->Inf_Financ_Lista_Personal_Total($idProy, $idAnio, $idMes, $idFte);
    $rowPer = mysqli_fetch_assoc($rsPer);
    $total_presup += $rowPer['gasto_tot'];
    $total_fuente += $rowPer['total_fuente'];
    $total_planeado += $rowPer['planeado'];
    $total_ejecutado += $rowPer['ejecutado'];
    $rsPer->free();
    ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo("10.1");?></td>
                        <td colspan="3" align="left"><?php echo( "Personal del Proyecto");?></td>
                        <td align="right"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo("10.1.12");?></td>
                        <td colspan="3" align="left"><?php echo( "Remuneraciones");?></td>
                        <td align="right"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
                        <td align="right"><b><?php echo(number_format($rowPer['total_fuente'],2));?></b></td>
                        <td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
        <?php
        $rsPer = $objMP->Inf_Financ_Lista_Personal($idProy, $idAnio, $idMes, $idFte);
        while ($rowPer = mysqli_fetch_assoc($rsPer)) 
        {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";
            $inputTextAvance = $rowPer['ejecutado'];
            $inputTextID = "<input  value='" . $rowPer['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowPer['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            $inputTextObs = "<input  value='" . $rowPer['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
            ?> 
        <tr style='color: navy;'>
                        <td align="left" nowrap="nowrap"><?php echo($rowPer['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowPer['cargo']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowPer['um']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowPer['meta']); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['total_fuente'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['planeado'],2));?></td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowPer['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
        <?php
}
        $rsPer->free();
        ?>
        <?php
        $idAct = 2;
        $rsEqui = $objMP->Inf_Financ_Lista_Equipamiento_Total($idProy, $idAnio, $idMes, $idFte);
        $rowEqui = mysqli_fetch_assoc($rsEqui);
        $total_presup += $rowEqui['gasto_tot'];
        $total_planeado += $rowEqui['planeado'];
        $total_fuente += $rowEqui['total_fuente'];
        $total_ejecutado += $rowEqui['ejecutado'];
        $rsEqui->free();
        ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo("10.$idAct");?></td>
                        <td colspan="3" align="left"><?php echo( "Equipamiento del Proyecto");?></td>
                        <td align="right"><?php echo(number_format($rowEqui['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo("10.$idAct.3");?></td>
                        <td colspan="3" align="left"><?php echo( "Equipos y Bienes Duraderos");?></td>
                        <td align="right"><?php echo(number_format($rowEqui['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
        <?php
        $rsEqui = $objMP->Inf_Financ_Lista_Equipamiento($idProy, $idAnio, $idMes, $idFte);
        while ($rowEqui = mysqli_fetch_assoc($rsEqui)) 
        {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";
            $inputTextAvance = $rowEqui['ejecutado'];
            $inputTextID = "<input  value='" . $rowEqui['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowEqui['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            $inputTextObs = "<input  value='" . $rowEqui['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
            ?> 
        <tr style='color: navy;'>
                        <td align="left" nowrap="nowrap"><?php echo($rowEqui['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowEqui['equipo']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowEqui['um']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowEqui['meta']); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['costo_tot'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['planeado'],2));?></td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowEqui['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
        <?php
        }
        ?>
          <?php
        // $total_ejec =0;
        $rsGF = $objMP->Inf_Financ_Lista_GastoFuncionamiento_Total($idProy, $idAnio, $idMes, $idFte);
        $rowGF = mysqli_fetch_assoc($rsGF);
        $rsGF->free();
        $idAct = 3;
        $total_presup += $rowGF['gasto_tot'];
        $total_planeado += $rowGF['planeado'];
        $total_fuente += $rowGF['total_fuente'];
        $total_ejecutado += $rowGF['ejecutado'];
        ?>  
                <tbody class="data">
                    <tr style="background-color: #FC9; height: 25px; cursor: pointer;"
                        onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
                        <td align="left" nowrap="nowrap"><?php echo("10.$idAct");?></td>
                        <td colspan="3" align="left"><?php echo( "Gastos de Funcionamiento");?></td>
                        <td align="right"><?php echo(number_format($rowGF['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowGF['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowGF['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowGF['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
    <?php
    $iRs = $objMP->Inf_Financ_Lista_PartidasGF($idProy, $idAnio, $idMes, $idFte);
    while ($rowsub = mysqli_fetch_assoc($iRs)) 
    {
        ?>
        <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
                        <td align="left"><?php echo( $rowsub['partida']);?></td>
                        <td align="center"><?php echo($rowsub['um']);?></td>
                        <td align="center"><?php echo($rowsub['meta']);?></td>
                        <td align="right"><?php echo(number_format($rowsub['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowsub['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowsub['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
      <?php
        $iRsCateg = $objMP->Inf_Financ_Lista_CategoriasGF($idProy, $idAnio, $idMes, $rowsub['idpartida'], $idFte);
        while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) 
        {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";
            $inputTextAvance = $rowsubCateg['ejecutado'];
            $inputTextID = "<input  value='" . $rowsubCateg['codigo_real'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowsubCateg['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            $inputTextObs = "<input  value='" . $rowsubCateg['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
            // /*
            $styleRow = 'style="font-weight:300; color:navy;"';
            if ($rowsubCateg['t41_estado'] == '2')             // Verificar que el MF no haya ingresado comentarios
            {
                $styleRow = 'style="font-weight:300; color:red;"';
            }
            // */
            ?>
        <tr <?php echo($styleRow);?>>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['categoria']);?></td>
                        <td align="center" nowrap="nowrap">&nbsp;</td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextID); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['gasto_tot'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_fuente'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap">
        <?php echo($inputTextEst); echo($inputTextObs); ?>
        </td>
                    </tr>
      <?php } $iRsCateg->free(); // Fin de Categorias de Gastos ?>
      <?php } $iRs->free(); // Fin de SubActividades ?>      
       <?php
    $idAct = 4;
    $iRsG = $objMP->Inf_Financ_Lista_GastosAdministrativos($idProy, $idAnio, $idMes, $idFte);
    $rowG = mysqli_fetch_assoc($iRsG);
    $iRsG->free();
    $total_fuente += $rowG['total_fuente'];
    $total_presup += $rowG['gasto_tot'];
    $total_planeado += $rowG['planeado'];
    $total_fuente += $rowG['total_fuente'];
    $total_ejecutado += $rowG['ejecutado'];
    $idtxtAvance = $idFte . "_txtRubroEjec[]";
    $idtxtCodigo = $idFte . "_Id[]";
    $inputTextAvance = $rowG['ejecutado'];
    $inputTextID = "<input  value='" . $rowG['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
    $inputTextEst = "<input  value='" . $rowG['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
    $inputTextObs = "<input  value='" . $rowG['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
    ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowG['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo( $rowG['descripcion']);?></td>
                        <td align="right"><?php echo(number_format($rowG['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowG['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowG['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowG['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
          <?php
        $idAct = 6;
        $iRsI = $objMP->Inf_Financ_Lista_Imprevistos($idProy, $idAnio, $idMes, $idFte);
        $rowI = mysqli_fetch_assoc($iRsI);
        $iRsI->free();
        $total_presup += $rowI['gasto_tot'];
        $total_planeado += $rowI['planeado'];
        $total_fuente += $rowI['total_fuente'];
        $total_ejecutado += $rowI['ejecutado'];
        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";
        $inputTextAvance = $rowI['ejecutado'];
        $inputTextID = "<input  value='" . $rowI['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowI['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        $inputTextObs = "<input  value='" . $rowI['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
        ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowI['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo( $rowI['descripcion']);?></td>
                        <td align="right"><?php echo(number_format($rowI['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowI['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowI['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowI['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>

        <?php
        $idAct = 7;
        $iRsSup = $objMP->infFinancGastosSupervision($idProy, $idAnio, $idMes, $idFte);
        $rowSup = mysqli_fetch_assoc($iRsSup);
        $iRsSup->free();
        $total_presup += $rowSup['gasto_tot'];
        $total_planeado += $rowSup['planeado'];
        $total_fuente += $rowSup['total_fuente'];
        $total_ejecutado += $rowSup['ejecutado'];
        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";
        $inputTextAvance = $rowSup['ejecutado'];
        $inputTextID = "<input  value='" . $rowSup['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowSup['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        $inputTextObs = "<input  value='" . $rowSup['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
        ?>
                    <tr style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowSup['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo( $rowSup['descripcion']);?></td>
                        <td align="right"><?php echo(number_format($rowSup['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowSup['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowSup['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowSup['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="height: 20px;">
                        <th height="27">&nbsp;</th>
                        <th colspan="3" align="right">Total: &nbsp;&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_presup  ,2,'.',','));?>&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_fuente  ,2,'.',','));?>&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_ejecutado,2,'.',','));?>&nbsp;</th>
                        <th width="88" align="right">&nbsp;</th>
                        <th width="71" align="right">&nbsp;</th>
                    </tr>
                </tfoot>
            </table>
            <br /> <br />
            <?php
                $total_ejecutado = 0;
                $objPresup = new BLPresupuesto();
                $rsFuen = $objPresup->ListaFuentesFinanciamiento($idProy, false);
            ?>
            <table width="650" border="0" cellpadding="0" cellspacing="0">
                <tr bgcolor="#CCCCFF">
                    <td height="25" colspan="10" align="left" valign="middle"><b
                        style="color: blue;">2. GASTOS CONTRAPARTIDAS</b></td>
                </tr>
            </table>
  <?php
while ($rowP = mysqli_fetch_assoc($rsFuen)) {
    $total_presup = 0;
    $total_ejec = 0;
    $total_planeado = 0;
    $total_fuente = 0;
    $idFte = $rowP['t01_id_inst'];
    $NomFte = $rowP['t01_sig_inst'];
    ?>
    <table width="650" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="21" colspan="10" align="left" valign="middle"><b
                        style="font-size: 14px; word-break: break-all;"><?php echo($NomFte);?></b></td>
                </tr>
                <tbody class="data">
                <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
                    <td width="53" height="28" rowspan="2" align="center"
                        valign="middle">Codigo</td>
                    <td width="197" height="28" style="word-break: break-all;" rowspan="2" align="center"
                        valign="middle">Categoria de Gastos</td>
                    <td width="57" rowspan="2" align="center" valign="middle">U.M.</td>
                    <td width="41" rowspan="2" align="center" valign="middle">Meta</td>
                    <td width="80" rowspan="2" align="center" valign="middle" style="word-break: break-all;">Total
                        Presupuesto</td>
                    <td width="80" style="word-break: break-all;" rowspan="2" align="center" valign="middle">Total <?php echo($NomFte);?></td>
                    <td colspan="2" align="center" valign="middle" style="word-break: break-all;">Información del Mes</td>
                    <td colspan="2" rowspan="2" align="center" valign="middle" style="word-break: break-all;">Observaciones
                        Monitor</td>
                </tr>
                <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
                    <th width="73" align="center" valign="middle">Planeado</th>
                    <th width="81" align="center" valign="middle">Ejecutado</th>
                </tr>
                </tbody>
                <tbody class="data">
        <?php
    $LC = $objInformes->ListaComponentes($idProy);
    while ($rowCo = mysqli_fetch_assoc($LC)) 
    {
        $idComp = $rowCo['t08_cod_comp'];
        ?>
        <tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
                        <td height="33" colspan="10" align="left" valign="middle"><b>Componente&nbsp;</b> <?php echo $rowCo['componente']; ?>
          </td>
                    </tr>
      <?php
        $objPresup = new BLPresupuesto();
        $iRsAct = $objPresup->ListaActividades_InfFinanc($idProy, $idComp, $idAnio, $idMes, $idFte);
        while ($rowAct = mysqli_fetch_assoc($iRsAct)) 
        {
            $idAct = $rowAct['t09_cod_act'];
            $total_presup += $rowAct['total_presup'];
            $total_ejecutado += $rowAct['total_ejec'];
            $total_planeado += $rowAct['planeado'];
            $total_fuente += $rowAct['total_fuente'];
            ?>  
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap" style="word-break: break-all;"><?php echo($rowAct['codigo']);?></td>
                        <td colspan="3" align="left" style="word-break: break-all;"><?php echo( $rowAct['actividad']);?></td>
                        <td align="right" style="word-break: break-all;"><?php echo(number_format($rowAct['total_presup'],2,'.',','));?></td>
                        <td align="right" style="word-break: break-all;"><?php echo(number_format($rowAct['total_fuente'],2,'.',','));?></td>
                        <td align="right" style="word-break: break-all;"><?php echo(number_format($rowAct['planeado'],2,'.',','));?></td>
                        <td align="right" style="word-break: break-all;"><?php echo(number_format($rowAct['total_ejec'],2,'.',','));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
    <?php
            $iRs = $objPresup->ListaSubActividades_InfFinanc($idProy, $idComp, $idAct, $idAnio, $idMes, $idFte);
            while ($rowsub = mysqli_fetch_assoc($iRs)) 
            {
                ?>
    <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap" style="word-break: break-all;"><?php echo($rowsub['codigo']);?></td>
                        <td align="left" style="word-break: break-all;"><?php echo( $rowsub['subactividad']);?></td>
                        <td align="center" style="word-break: break-all;"><?php echo($rowsub['um']);?></td>
                        <td align="center"><?php echo($rowsub['meta']);?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_presup'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_fuente'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowsub['planeado'],2,'.',','));?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_ejec'],2,'.',','));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
      <?php
                $iRsCateg = $objPresup->ListaCategoriaGasto_InfFinanc($idProy, $idComp, $idAct, $rowsub['t09_cod_sub'], $idAnio, $idMes, $idFte);
                while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) 
                {
                    $idtxtAvance = $idFte . "_txtRubroEjec[]";
                    $idtxtCodigo = $idFte . "_Id[]";
                    $inputTextAvance = $rowsubCateg['total_ejec'];
                    $inputTextID = "<input  value='" . $rowsubCateg['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
                    $inputTextEst = "<input  value='" . $rowsubCateg['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
                    $inputTextObs = "<input  value='" . $rowsubCateg['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
                    // /*
                    $styleRow = 'style="font-weight:300; color:navy;"';
                    if ($rowsubCateg['t41_estado'] == '2')                     // Verificar que el MF no haya ingresado comentarios
                    {
                        $styleRow = 'style="font-weight:300; color:red;"';
                    }
                    // */
                    ?>
    <tr <?php echo($styleRow);?>>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['categoria']);?></td>
                        <td align="center" nowrap="nowrap">&nbsp;</td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextID); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_presup'],2,'.',','));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_fuente'],2,'.',','));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['planeado'],2,'.',','));?></td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap">
        <?php echo($inputTextEst); echo($inputTextObs); ?>
        </td>
                    </tr>
      <?php } $iRsCateg->free(); // Fin de Categorias de Gastos ?>
      <?php } $iRs->free(); // Fin de SubActividades ?>
      <?php } $iRsAct->free(); // Fin de Actividades    ?>
      <?php
    } // fin de while componentes
    ?> 
    <tr bgcolor="#D9DAE8" style="background-color: #D9DAE8;">
                        <td height="33" colspan="10" align="left" valign="middle"><b>Componente&nbsp;</b> <?php echo "10.- Manejo del proyecto"; ?>
      </td>
                    </tr>
        <?php
    $idAct = 1;
    $objMP = new BLManejoProy();
    $rsPer = $objMP->Inf_Financ_Lista_Personal_Total($idProy, $idAnio, $idMes, $idFte);
    $rowPer = mysqli_fetch_assoc($rsPer);
    $total_presup += $rowPer['gasto_tot'];
    $total_fuente += $rowPer['total_fuente'];
    $total_planeado += $rowPer['planeado'];
    $total_ejecutado += $rowPer['ejecutado'];
    $rsPer->free();
    ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo("10.1");?></td>
                        <td colspan="3" align="left"><?php echo( "Personal del Proyecto");?></td>
                        <td align="right"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo("10.1.12");?></td>
                        <td colspan="3" align="left"><?php echo( "Remuneraciones");?></td>
                        <td align="right"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
                        <td align="right"><b><?php echo(number_format($rowPer['total_fuente'],2));?></b></td>
                        <td align="right"><?php echo(number_format($rowPer['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowPer['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
        <?php
    $rsPer = $objMP->Inf_Financ_Lista_Personal($idProy, $idAnio, $idMes, $idFte);
    while ($rowPer = mysqli_fetch_assoc($rsPer)) 
    {
        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";
        $inputTextAvance = $rowPer['ejecutado'];
        $inputTextID = "<input  value='" . $rowPer['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowPer['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        $inputTextObs = "<input  value='" . $rowPer['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
        ?> 
        <tr style='color: navy;'>
                        <td align="left" nowrap="nowrap"><?php echo($rowPer['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowPer['cargo']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowPer['um']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowPer['meta']); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['gasto_tot'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['total_fuente'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowPer['planeado'],2));?></td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowPer['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
        <?php
}
    $rsPer->free();
    ?>
        <?php
    $idAct = 2;
    $rsEqui = $objMP->Inf_Financ_Lista_Equipamiento_Total($idProy, $idAnio, $idMes, $idFte);
    $rowEqui = mysqli_fetch_assoc($rsEqui);
    $total_presup += $rowEqui['gasto_tot'];
    $total_planeado += $rowEqui['planeado'];
    $total_fuente += $rowEqui['total_fuente'];
    $total_ejecutado += $rowEqui['ejecutado'];
    $rsEqui->free();
    ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo("10.$idAct");?></td>
                        <td colspan="3" align="left"><?php echo( "Equipamiento del Proyecto");?></td>
                        <td align="right"><?php echo(number_format($rowEqui['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
                    <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo("10.$idAct.3");?></td>
                        <td colspan="3" align="left"><?php echo( "Equipos y Bienes Duraderos");?></td>
                        <td align="right"><?php echo(number_format($rowEqui['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowEqui['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
        <?php
    $rsEqui = $objMP->Inf_Financ_Lista_Equipamiento($idProy, $idAnio, $idMes, $idFte);
    while ($rowEqui = mysqli_fetch_assoc($rsEqui)) 
    {
        $idtxtAvance = $idFte . "_txtRubroEjec[]";
        $idtxtCodigo = $idFte . "_Id[]";
        $inputTextAvance = $rowEqui['ejecutado'];
        $inputTextID = "<input  value='" . $rowEqui['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
        $inputTextEst = "<input  value='" . $rowEqui['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
        $inputTextObs = "<input  value='" . $rowEqui['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
        ?> 
        <tr style='color: navy;'>
                        <td align="left" nowrap="nowrap"><?php echo($rowEqui['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowEqui['equipo']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowEqui['um']);?></td>
                        <td align="center" nowrap="nowrap"><?php echo($rowEqui['meta']); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['costo_tot'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['total_fuente'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowEqui['planeado'],2));?></td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowEqui['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
        <?php
    }
    ?>
          <?php
    // $total_ejec =0;
    $rsGF = $objMP->Inf_Financ_Lista_GastoFuncionamiento_Total($idProy, $idAnio, $idMes, $idFte);
    $rowGF = mysqli_fetch_assoc($rsGF);
    $rsGF->free();
    $idAct = 3;
    $total_presup += $rowGF['gasto_tot'];
    $total_planeado += $rowGF['planeado'];
    $total_fuente += $rowGF['total_fuente'];
    $total_ejecutado += $rowGF['ejecutado'];
    ?>  
                <tbody class="data">
                    <tr style="background-color: #FC9; height: 25px; cursor: pointer;"
                        onclick="ShowActividad('<?php echo("tbody_".$idFte.'_'.$idAct);?>');">
                        <td align="left" nowrap="nowrap"><?php echo("10.$idAct");?></td>
                        <td colspan="3" align="left"><?php echo( "Gastos de Funcionamiento");?></td>
                        <td align="right"><?php echo(number_format($rowGF['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowGF['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowGF['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowGF['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
    <?php
    $iRs = $objMP->Inf_Financ_Lista_PartidasGF($idProy, $idAnio, $idMes, $idFte);
    while ($rowsub = mysqli_fetch_assoc($iRs)) 
    {
        ?>
        <tr style="background-color: #E3FEE0;">
                        <td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
                        <td align="left"><?php echo( $rowsub['partida']);?></td>
                        <td align="center"><?php echo($rowsub['um']);?></td>
                        <td align="center"><?php echo($rowsub['meta']);?></td>
                        <td align="right"><?php echo(number_format($rowsub['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowsub['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowsub['planeado'],2));?></td>
                        <td align="right"><?php echo(number_format($rowsub['ejecutado'],2));?></td>
                        <td colspan="2" align="center">&nbsp;</td>
                    </tr>
      <?php
        $iRsCateg = $objMP->Inf_Financ_Lista_CategoriasGF($idProy, $idAnio, $idMes, $rowsub['idpartida'], $idFte);
        while ($rowsubCateg = mysqli_fetch_assoc($iRsCateg)) 
        {
            $idtxtAvance = $idFte . "_txtRubroEjec[]";
            $idtxtCodigo = $idFte . "_Id[]";
            $inputTextAvance = $rowsubCateg['ejecutado'];
            $inputTextID = "<input  value='" . $rowsubCateg['codigo_real'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
            $inputTextEst = "<input  value='" . $rowsubCateg['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
            $inputTextObs = "<input  value='" . $rowsubCateg['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
            // /*
            $styleRow = 'style="font-weight:300; color:navy;"';
            if ($rowsubCateg['t41_estado'] == '2')             // Verificar que el MF no haya ingresado comentarios
            {
                $styleRow = 'style="font-weight:300; color:red;"';
            }
            // */
            ?>
        <tr <?php echo($styleRow);?>>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['codigo']);?></td>
                        <td align="left" nowrap="nowrap"><?php echo($rowsubCateg['categoria']);?></td>
                        <td align="center" nowrap="nowrap">&nbsp;</td>
                        <td align="center" nowrap="nowrap"><?php echo($inputTextID); ?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['gasto_tot'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['total_fuente'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo(number_format($rowsubCateg['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap">
        <?php echo($inputTextEst); echo($inputTextObs); ?>
        </td>
                    </tr>
      <?php } $iRsCateg->free(); // Fin de Categorias de Gastos ?>
      <?php } $iRs->free(); // Fin de SubActividades ?>      
       <?php
    $idAct = 4;
    $iRsG = $objMP->Inf_Financ_Lista_GastosAdministrativos($idProy, $idAnio, $idMes, $idFte);
    $rowG = mysqli_fetch_assoc($iRsG);
    $iRsG->free();
    $total_fuente += $rowG['total_fuente'];
    $total_presup += $rowG['gasto_tot'];
    $total_planeado += $rowG['planeado'];
    $total_fuente += $rowG['total_fuente'];
    $total_ejecutado += $rowG['ejecutado'];
    $idtxtAvance = $idFte . "_txtRubroEjec[]";
    $idtxtCodigo = $idFte . "_Id[]";
    $inputTextAvance = $rowG['ejecutado'];
    $inputTextID = "<input  value='" . $rowG['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
    $inputTextEst = "<input  value='" . $rowG['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
    $inputTextObs = "<input  value='" . $rowG['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
    ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowG['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo( $rowG['descripcion']);?></td>
                        <td align="right"><?php echo(number_format($rowG['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowG['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowG['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowG['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
          <?php
    $idAct = 6;
    $iRsI = $objMP->Inf_Financ_Lista_Imprevistos($idProy, $idAnio, $idMes, $idFte);
    $rowI = mysqli_fetch_assoc($iRsI);
    $iRsI->free();
    $total_presup += $rowI['gasto_tot'];
    $total_planeado += $rowI['planeado'];
    $total_fuente += $rowI['total_fuente'];
    $total_ejecutado += $rowI['ejecutado'];
    $idtxtAvance = $idFte . "_txtRubroEjec[]";
    $idtxtCodigo = $idFte . "_Id[]";
    $inputTextAvance = $rowI['ejecutado'];
    $inputTextID = "<input  value='" . $rowI['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
    $inputTextEst = "<input  value='" . $rowI['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
    $inputTextObs = "<input  value='" . $rowI['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
    ?>
        <tr
                        style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowI['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo( $rowI['descripcion']);?></td>
                        <td align="right"><?php echo(number_format($rowI['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowI['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowI['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowI['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
    <?php
    $idAct = 7;
    $iRsSup = $objMP->infFinancGastosSupervision($idProy, $idAnio, $idMes, $idFte);
    $rowSup = mysqli_fetch_assoc($iRsSup);
    $iRsSup->free();
    $total_presup += $rowSup['gasto_tot'];
    $total_planeado += $rowSup['planeado'];
    $total_fuente += $rowSup['total_fuente'];
    $total_ejecutado += $rowSup['ejecutado'];
    $idtxtAvance = $idFte . "_txtRubroEjec[]";
    $idtxtCodigo = $idFte . "_Id[]";
    $inputTextAvance = $rowSup['ejecutado'];
    $inputTextID = "<input  value='" . $rowSup['codigo'] . "'  name='" . $idtxtCodigo . "' type='hidden' id='" . $idtxtCodigo . "' class='presup' />";
    $inputTextEst = "<input  value='" . $rowSup['estado'] . "'  name='" . $idFte . "_Estado[]' type='hidden' id='" . $idFte . "_Estado[]' class='presup' />";
    $inputTextObs = "<input  value='" . $rowSup['observ'] . "'  name='" . $idFte . "_Obs[]' type='hidden' id='" . $idFte . "_Obs[]' class='presup' />";
    ?>
                    <tr style="background-color: #FC9; height: 25px; cursor: pointer;">
                        <td align="left" nowrap="nowrap"><?php echo($rowSup['codigo']);?></td>
                        <td colspan="3" align="left"><?php echo utf8_decode($rowSup['descripcion']); ?></td>
                        <td align="right"><?php echo(number_format($rowSup['gasto_tot'],2));?></td>
                        <td align="right"><?php echo(number_format($rowSup['total_fuente'],2));?></td>
                        <td align="right"><?php echo(number_format($rowSup['planeado'],2));?></td>
                        <td align="right" nowrap="nowrap"><?php echo($inputTextAvance); ?></td>
                        <td colspan="2" align="center" nowrap="nowrap"><?php echo($inputTextObs); echo($rowSup['observ']);  echo($inputTextID); echo($inputTextEst); ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="height: 20px;">
                        <th height="27">&nbsp;</th>
                        <th colspan="3" align="right">Total: &nbsp;&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_presup  ,2,'.',','));?>&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_fuente  ,2,'.',','));?>&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_planeado,2,'.',','));?>&nbsp;</th>
                        <th align="right"><?php echo(number_format($total_ejecutado,2,'.',','));?>&nbsp;</th>
                        <th width="88" align="right">&nbsp;</th>
                        <th width="71" align="right">&nbsp;</th>
                    </tr>
                </tfoot>
            </table>
            <br />
    <?php 
        $total_ejecutado = 0;
    } ?>
            <table width="650" cellpadding="0" cellspacing="0">
                <tr colspan="3">
                    <td align="left" valign="middle">&nbsp;</td>
                </tr>
                <tr bgcolor="#CCCCFF">
                    <td colspan="3" align="left" valign="middle"><b
                        style="color: blue;">3. OTROS GASTOS / BANCOS</b></td>
                </tr>
                <tr>
                    <td colspan="3" align="left" valign="middle">&nbsp;</td>
                </tr>
                <thead>
                </thead>
                <tbody class="data" bgcolor="#FFFFFF">
                    <tr class="SubtitleTable"
                        style="border: solid 1px #CCC; background-color: #eeeeee;">
                        <td width="128" align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">MONTOS S/.</td>
                        <td width="483" height="23" align="center"
                            valign="middle">OBSERVACIONES ACERCA DE LOS MONTOS REPORTADOS</td>
                    </tr>
     <?php
    $objInf = new BLInformes();
    $row = $objInf->InformeFinancieroSeleccionar($idProy, $idAnio, $idMes);
    $iRs = $objInf->ListaProblemasSoluciones($idProy, $idAnio, $idMes, $idVs);
    $RowIndex = 0;
    $t20_dificul = "";
    $t20_program = "";
    $iRs->free();
    ?>
     <tr>
                        <td align="left" nowrap="nowrap" class="TableGrid"><b>OTROS
                                INGRESOS</b></td>
                        <td width="139" align="right" valign="middle"><?php echo($row['t40_otro_ing'])?></td>
                        <td  align="left" nowrap="nowrap"><?php echo($row['t40_otro_ing_obs'])?></td>
                    </tr>
                    <tr>
                        <td align="left" nowrap="nowrap" class="TableGrid"><b>ABONOS
                                DEL BANCO</b></td>
                        <td align="right" valign="middle"><?php echo($row['t40_abo_bco'])?></td>
                        <td align="left" nowrap="nowrap"><?php echo($row['t40_abo_bco_obs'])?></td>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
            <table width="650" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="3" align="left" valign="middle">&nbsp;</td>
                </tr>
                <tr bgcolor="#CCCCFF">
                    <td colspan="3" align="left" valign="middle"><b
                        style="color: blue;">4. EXCEDENTES POR EJECUTAR</b></td>
                </tr>
                <tbody class="data" bgcolor="#FFFFFF">
                    <tr class="SubtitleTable"
                        style="border: solid 1px #CCC; background-color: #FFF;">
                        <td width="211" align="center" valign="middle">FECHA AL:</td>
                        <td height="23" colspan="2" align="left" valign="middle"><?php echo($row['t40_cor_ctb'])?>  </td>
                    </tr>
                    <tr class="SubtitleTable"
                        style="border: solid 1px #CCC; background-color: #eeeeee;">
                        <td align="center" valign="middle">&nbsp;</td>
                        <td width="100" align="center" valign="middle">MONTO S/.</td>
                        <td width="200" height="23" align="center" valign="middle">OBSERVACIONES
                            AL MONTO REPORTADO ( MONITOR )</td>
                    </tr>
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaProblemasSoluciones($idProy, $idAnio, $idMes, $idVs);
    $RowIndex = 0;
    $t20_dificul = "";
    $t20_program = "";
    $iRs->free();
    ?>
    <tr>
                        <td align="left" class="SubtitleTable"
                            style="border: solid 1px #CCC; background-color: #eeeeee;">CAJA
                            CHICA</td>
                        <td height="25" align="right" valign="middle"><?php echo($row['t40_caja'])?></td>
                        <td  align="left" valign="middle"><?php echo($row['t40_caja_obs'])?></td>
                    </tr>
                    <tr>
                        <td align="left" class="SubtitleTable"
                            style="border: solid 1px #CCC; background-color: #eeeeee;">BANCO
                            MONEDA NACIONAL</td>
                        <td height="25" align="right" valign="middle"><?php echo($row['t40_bco_mn'])?></td>
                        <td  align="left" valign="middle"><?php echo($row['t40_bco_mn_obs'])?></td>
                    </tr>
                    <tr>
                        <td align="left" class="SubtitleTable"
                            style="border: solid 1px #CCC; background-color: #eeeeee;">ENTREGAS
                            A RENDIR CUENTA</td>
                        <td height="25" align="right" valign="middle"><?php echo($row['t40_ent_rend'])?></td>
                        <td align="left" valign="middle"><?php echo($row['t40_ent_rend_obs'])?></td>
                    </tr>
                    <tr>
                        <td align="left" class="SubtitleTable"
                            style="border: solid 1px #CCC; background-color: #eeeeee;">CUENTAS
                            X PAGAR</td>
                        <td height="25" align="right" valign="middle"><?php echo($row['t40_cxp'])?></td>
                        <td  align="left" valign="middle"><?php echo($row['t40_cxp_obs'])?></td>
                    </tr>
                    <tr>
                        <td align="left" class="SubtitleTable"
                            style="border: solid 1px #CCC; background-color: #eeeeee;">CUENTAS
                            X COBRAR</td>
                        <td height="25" align="right" valign="middle"><?php echo($row['t40_cxc'])?></td>
                        <td align="left" valign="middle"><?php echo($row['t40_cxc_obs'])?></td>
                    </tr>
    <?php $suma = $row['t40_caja']+$row['t40_bco_mn']+$row['t40_ent_rend']+$row['t40_cxp']+$row['t40_cxc'];?>
  </tbody>
                <tfoot>
                    <tr>
                        <td height="27" nowrap="nowrap" class="TableGrid"><b>TOTAL</b></td>
                        <td align="left" valign="middle"><?php echo($suma)?></td>
                        <td width="97" align="center" nowrap="nowrap" bgcolor="#CC9933">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            <table width="650" border="0" cellspacing="1" cellpadding="0">
                <tr>
                    <td colspan="3" align="left" valign="middle">&nbsp;</td>
                </tr>
                <tr bgcolor="#CCCCFF">
                    <td colspan="3" align="left" valign="middle"><b
                        style="color: blue;">5. ANEXOS</b></td>
                </tr>
                <tr>
                    <td colspan="3" align="left" valign="middle">&nbsp;</td>
                </tr>
            </table>
            <div id="divTableLista" class="TableGrid">
                <table width="650" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    </thead>
                    <tbody class="data" bgcolor="#FFFFFF">
                        <tr class="SubtitleTable"
                            style="border: solid 1px #CCC; background-color: #eeeeee;">
                            <td width="29" align="center" valign="middle">&nbsp;</td>
                            <td align="center" valign="middle" nowrap="nowrap"><b>Nombre
                                    del Archivo</b></td>
                            <td width="481" height="23" align="center" valign="middle"><b>Descripcion
                                    del Archivo</b></td>
                        </tr>
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaAnexosInformeFinanc($idProy, $idAnio, $idMes);
    $RowIndex = 0;
    $j = 1;
    if ($iRs->num_rows > 0) 
    {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>
     <tr>
       <?php
            $urlFile = $row['t40_url_file'];
            $filename = $row['t40_nom_file'];
            $path = constant('APP_PATH') . "sme/proyectos/informes/anx_financ";
            $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
            ?>
       <td align="center" valign="middle"><?php echo $j;?></td>
                            <td height="30" align="center" valign="middle"><a
                                href="<?php echo($href);?>" title="Descargar Archivo"
                                target="_blank"><?php echo($row['t40_nom_file']);?></a></td>
                            <td align="left" valign="middle" nowrap="nowrap"><?php echo( $row['t40_desc_file']);?></td>
                        </tr>
     <?php
            $j ++;
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>
    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="center" valign="middle">&nbsp; <iframe
                                    id="ifrmUploadFile" name="ifrmUploadFile"
                                    style="display: none;"></iframe>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script>
function ExportarAnexos(idAnx)
{
    var arrayControls = new Array();
        arrayControls[0] = "idProy=<?php echo($idProy);?>";         
        arrayControls[1] = "idAnio=<?php echo($idAnio);?>" ;
        arrayControls[2] = "idMes=<?php echo($idMes);?>" ;
        arrayControls[3] = "idFte=<?php echo($idFte);?>" ;
    var params = arrayControls.join("&"); 
    var sID = "0" ;
    switch(idAnx)
    {
        case "1" : sID = "36"; break;
        case "2" : sID = "37"; break;
        case "3" : sID = "38"; break;
    }
    NewReportID(sID, params);
    return;
}
</script>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
<?php
function retComponentes($proy, $vs)
{
    $objML = new BLMarcoLogico();
    $rsComp = $objML->ListadoDefinicionOE($proy, $vs);
    while ($row = mysql_fetch_assoc($rsComp)) 
    {
        echo ($row['t08_cod_comp'] . ". " . $row['t08_comp_desc'] . "<br />");
    }
    $rsComp = NULL;
}
function retPresupFuentesFinanc($proy, $vs)
{
    $objPresup = new BLPresupuesto();
    $rsFte = $objPresup->RepFuentesFinac($proy, $vs);
    $total = 0;
    if ($rsFte->num_rows > 0) 
    {
        while ($row = mysqli_fetch_assoc($rsFte)) 
        {
            echo ("<tr style='font-size:10px;'>");
            echo ("     <td class='ClassText'>" . $row['fuente'] . "</td>");
            // echo(" <td > 0 </td>");
            // echo(" <td > 0 </td>");
            // echo(" <td > 0 </td>");
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
    while ($row = mysqli_fetch_assoc($rsInf)) 
    {
        // echo(print_r($row));
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
<!-- InstanceEndEditable -->
            </div>
<?php if($objFunc->__QueryString()=="") { ?>
    </form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>