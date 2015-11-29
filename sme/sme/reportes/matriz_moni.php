<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
// $objFunc->xx

$objRep = new BLReportes();
$objPOA = new BLPOA();

$concurso = $objFunc->__Request('cboConcurso');
$region = $objFunc->__Request('cboRegion');
$sector = $objFunc->__Request('cboSectorProd');
$tipoinst = $objFunc->__Request('cboTipoInst');
$estado = $objFunc->__Request('cboEstado');
$proyecto = $objFunc->__Request('txtCodProy');
$monitor = $objFunc->__Request('cboMoniExt');

$rsMatriz = $objRep->RepMatrizMonitoreo($concurso, $region, $sector, $tipoinst, $estado, $proyecto, $monitor);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Matriz General de Supervisión</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css"
	media="all" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">
			<!-- InstanceBeginEditable name="BodyAjax" -->
			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="19%" valign="top"><p align="center">Datos Generales</p></td>
						<td width="18%" valign="top"><p align="center">Objetivos</p></td>
						<td width="21%" valign="top"><p align="center">Financiamiento</p></td>
						<td width="18%" valign="top"><p align="center">Informes de
								Ejecución</p></td>
						<td width="24%" valign="top"><p align="center">Informes de
								Supervisión</p></td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    while ($rMatriz = mysqli_fetch_assoc($rsMatriz)) {
        $aFechTerTS = 0;
        $aFechTerTxt = '00/00/0000';
        $aDateArr1 = explode('-', $rMatriz['fecha_termino']);
        if ($aDateArr1[0] > 0) {
            $aFechTerTS = mktime(0, 0, 0, $aDateArr1[1], $aDateArr1[2], $aDateArr1[0]);
            $aFechTerTxt = date('d/m/Y', $aFechTerTS);
        }
        ?>
    <tr>
						<td align="left" valign="top" style="font-size: 10px;"><span
							class="ClassField">Código:</span> <span class="ClassText"><?php echo($rMatriz['codigo']); ?></span>
							<br /> <span class="ClassField">Ejecutor:</span> <span
							class="ClassText"><?php echo($rMatriz['siglas']); ?></span> <br />
							<span class="ClassField">Tipo Institución:</span> <span
							class="ClassText"><?php echo($rMatriz['tipoinst']); ?></span> <br />
							<span class="ClassField">Nombre:</span><span class="ClassText"><?php echo($rMatriz['titulo']); ?></span>
							<br /> <span class="ClassField">Inicio&nbsp;&nbsp;&nbsp; :</span>
							<span class="ClassText"><?php echo($rMatriz['inicio']); ?></span>
							<br /> <span class="ClassField">Término:</span> <span
							class="ClassText"><?php echo $aFechTerTxt; ?></span> <br /> <span
							class="ClassField">Responsable:</span> <span class="ClassText"><?php echo($rMatriz['jefeproyecto']); ?></span>
							<br />
						<br /> <u><span style="font-size: 11px; font-weight: bold;"><a
									href="javascript:ViewEquiProy('<?php echo($rMatriz['codigo']);?>');"
									title="Ver Equipo Ejecutor">Equipo Ejecutor</a></span></u><br />
						<br /> <span class="ClassField">Supervisor&nbsp;:</span> <span
							class="ClassText"><?php echo($rMatriz['supe_inst']); ?></span> <br />
							<span class="ClassField">Monitor Tem:</span> <span
							class="ClassText"><?php echo($rMatriz['moni_tema']); ?></span> <br />
							<span class="ClassField">Monitor Fin:</span> <span
							class="ClassText"><?php echo($rMatriz['moni_fina']); ?></span> <br />
							<span class="ClassField">Monitor Ext:</span> <span
							class="ClassText"><a
								href="javascript:ViewMExt('<?php echo($rMatriz['cod_me']);?>');"
								title="Ver Datos del Monitor Externo"> <?php echo($rMatriz['moni_exte']); ?> </a></span>
							<br /> <u><span style="font-size: 11px">ámbito Geográfico</span></u><br />
							<span class="ClassText"><?php echo($rMatriz['ambito']); ?></span>
							<br /> <u><span style="font-size: 11px; font-weight: bold;">Beneficiarios</span></u>&nbsp;&nbsp;(<?php echo($rMatriz['num_benef']); ?>) <a
							href="javascript:ViewBenef('<?php echo($rMatriz['codigo']);?>','<?php echo($rMatriz['version']);?>');"
							title="Ver Listado de Beneficiarios"> Ver Listado </a><br /> <span
							class="ClassText"><?php echo($rMatriz['beneficiarios']); ?></span>
							<br />
						<br /> <span style="font-size: 11px; font-weight: bold;"><a
								href="javascript:ViewSector('<?php echo($rMatriz['codigo']);?>');"
								title="Ver Sectores Productivos">Sectores Productivos</a></span><span
							class="ClassText"></span> <br /> <span class="ClassText"><?php echo($rMatriz['sectoresprod']); ?></span><br />
						<br /> <span class="ClassField">Estado :</span> <span
							class="ClassText"><?php echo($rMatriz['estado']); ?></span> <br />
							<span class="ClassField">Sede :</span> <span class="ClassText"><?php echo($rMatriz['direccion']." ".$rMatriz['ciudad']."<br>Fono: ".$rMatriz['telefono']."<br>Mail: ".$rMatriz['mail']); ?></span>
							<br /></td>
						<td align="left" valign="top" style="font-size: 10px;"><span
							class="ClassField">Finalidad:</span> <span class="ClassText"><?php echo($rMatriz['finalidad']); ?></span><br />
							<span class="ClassField">Proposito:</span> <span
							class="ClassText"><?php echo($rMatriz['proposito']); ?></span> <br />
							<span class="ClassField">Componentes:</span> <br /> <span
							class="ClassText"><?php retComponentes($rMatriz['codigo'], $rMatriz['version']) ?></span><br />
							<a
							href="javascript:ViewML('<?php echo($rMatriz['codigo']);?>','<?php echo($rMatriz['version']);?>')"
							title="Marco Logico"> Marco Logico </a><br /> <a
							href="javascript:ViewCronograma('<?php echo($rMatriz['codigo']);?>','1')"
							title="Cronograma de Actividades" style="width: 50px;">Cronograma
								Actividades </a> <br /> <br /></td>
						<td align="center" valign="top">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								class="TableGrid">
								<thead>
									<tr class="ClassField">
										<td width="132" align="center">Fuentes</td>
										<td width="55" align="center" nowrap="nowrap">Total</td>
									</tr>
								</thead>
        <?php
        retPresupFuentesFinanc($rMatriz['codigo'], 1);
        ?>
      </table> <br /> <br />
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<thead>
									<tr>
										<th colspan="3" align="center">Planes Operativos</th>
									</tr>
									<tr>
										<td width="22%" align="center">Año</td>
										<td width="46%" align="center">Presupuesto</td>
									</tr>
								</thead>
								<tbody>
        
        <?php
        $rsPOAS = $objPOA->POA_Listado($rMatriz['codigo']);
        while ($rpoa = mysqli_fetch_assoc($rsPOAS)) {
            ?>
        <tr>
										<td align="center"><a
											href="javascript:ViewPOA('<?php echo($rMatriz['codigo']);?>','<?php echo($rpoa['t02_anio']);?>');"><?php echo($rpoa['anio']); ?></a></td>
										<td align="center"><?php echo($rpoa['presupuesto']); ?></td>
									</tr>
        <?php
        
}
        $rsPOAS->free();
        ?>
        
        </tbody>
							</table> <br /> <br /> <span> <span style="font-size: 10px;"><a
									href="javascript:ViewPresupuesto('<?php echo($rMatriz['codigo']);?>','1')"
									title="Presupuesto Analítico">Presupuesto Analítico</a> </span>
								<br /> <span style="font-size: 10px;"><a
									href="javascript:ViewCronDesembolsos('<?php echo($rMatriz['codigo']);?>','1')"
									title="Cronograma de Desembolsos de Fondoempleo">Cronograma de
										Desembolsos</a></span> <br /> <br /> <span
								style="font-size: 10px;"><a
									href="javascript:ViewResumenDesembolsos('<?php echo($rMatriz['codigo']);?>')"
									title="Resumen de Desembolsos">Resumen de Desembolsos</a></span>
						</span> <br />
						</td>
						<td align="left" valign="top" style="font-size: 10px;">
							<table width="200" border="0" cellpadding="0" cellspacing="0"
								style="font-size: 10px;">
								<thead>
									<tr>
										<td colspan="2" align="center"><span class="ClassField">Informes
												Mensuales</span></td>
									</tr>
								</thead>
								<tr>
									<td colspan="2" class="RowAlternate">INFORMES TECNICOS</td>
								</tr>
								<tr style="color: #090; font-weight: bold;">
									<td>N&deg; Informes Planeados</td>
									<td align="center"><?php echo(round($rMatriz['inf_planeados'],0)); ?></td>
								</tr>
								<tr>
									<td width="170">N&deg; Inf. Planeados a la Fecha</td>
          <?php
        $aPlanFecha = 0;
        $aNroInfTrimFecha = 0;
        if ($aFechTerTS) {
            $aDateArr2 = explode('-', $rMatriz['t02_fch_ini']);
            $aDate2 = mktime(0, 0, 0, $aDateArr2[1], $aDateArr2[2], $aDateArr2[0]);
            if (time() > $aFechTerTS)
                $aDateDiff = ($aFechTerTS - $aDate2) / 60 / 60 / 24 / 30;
            else
                $aDateDiff = (time() - $aDate2) / 60 / 60 / 24 / 30;
            $aPlanFecha = ($aDateDiff - ($aDateDiff * (1 / 3)));
            $aNroInfTrimFecha = floor($aPlanFecha * 0.5);
        }
        ?>
          <td width="28" align="center"><?php echo( $aFechTerTS ? round($aPlanFecha,0) : '0'); ?></td>
								</tr>
								<tr>
									<td>N&deg; Informes Entregados</td>
									<td align="center"><?php
        $ls_urlRpt = "rpt_inf_mes_list.php";
        $ls_urlPar = "&idProy=" . $rMatriz['codigo'];
        $ls_urlVie = "reportviewer.php?link=" . $ls_urlRpt . "&title=Listado de Informes Mensuales" . $ls_urlPar;
        
        ?>
       <span class="ClassText"><a href="#"
											onclick="window.open('<?php echo($ls_urlVie); ?>','InformMes','fullscreen,scrollbars')"><?php echo($rMatriz['inf_entregados']); ?></a></span></td>
								</tr>
								<tr>
									<td>N&deg; Informes Pendientes</td>
									<td align="center"><span class="ClassText"><?php echo((round($aPlanFecha,0)-$rMatriz['inf_entregados'])); ?></span></td>
								</tr>
								<tr>
									<td colspan="2" class="RowAlternate">INFORMES FINANCIEROS</td>
								</tr>
								<tr style="color: #090; font-weight: bold;">
									<td>N&deg; Informes Planeados</td>
									<td align="center"><?php echo(round($rMatriz['inf_planeados_financ'],0)); ?></td>
								</tr>
								<tr>
									<td>N&deg; Inf. Planeados a la Fecha</td>
									<td align="center"><?php echo(round($rMatriz['inf_planeados_fecha_financ'],0)); ?></td>
								</tr>
								<tr>
									<td>N&deg; Informes Entregados</td>
									<td align="center"><?php
        $ls_urlRpt = "rpt_inf_mes_financ_list.php";
        $ls_urlPar = "&idProy=" . $rMatriz['codigo'];
        $ls_urlVie = "reportviewer.php?link=" . $ls_urlRpt . "&title=Listado de Informes Financieros Mensuales" . $ls_urlPar;
        
        ?>
            <span class="ClassText"><a href="#"
											onclick="window.open('<?php echo($ls_urlVie); ?>','InformMes','fullscreen,scrollbars')"><?php echo($rMatriz['inf_entregados_financ']); ?></a></span></td>
								</tr>
								<tr>
									<td>N&deg; Informes Pendientes</td>
									<td align="center"><span class="ClassText"><?php echo((round($rMatriz['inf_planeados_fecha_financ'],0)-$rMatriz['inf_entregados_financ'])); ?></span></td>
								</tr>
							</table>
							<p>
								<br />
							</p>
							<table width="200" border="0" cellpadding="0" cellspacing="0"
								style="font-size: 10px;">
								<thead>
									<tr>
										<td colspan="2" align="center"><span class="ClassField">Informes
												Entregables</span></td>
									</tr>
								</thead>
								<tr>
									<td colspan="2" class="RowAlternate">INFORMES TECNICOS</td>
								</tr>
								<tr style="color: #090; font-weight: bold;">
									<td>N&deg; Informes Planeados</td>
									<td align="center"><?php echo(round(((1/2)*$rMatriz['inf_planeados']),0)); ?></td>
								</tr>
								<tr>
									<td width="170">N&deg; Inf. Planeados a la Fecha</td>
									<td width="28" align="center"><?php echo $aNroInfTrimFecha; ?></td>
								</tr>
								<tr>
									<td>N&deg; Informes Entregados</td>
									<td align="center"><?php
        $ls_urlRpt = "rpt_inf_mes_list.php";
        $ls_urlPar = "&idProy=" . $rMatriz['codigo'];
        $ls_urlVie = "reportviewer.php?link=" . $ls_urlRpt . "&title=Listado de Informes Mensuales" . $ls_urlPar;
        
        ?>
       <span class="ClassText"><?php echo($rMatriz['inf_tri_entregados']); ?></span></td>
								</tr>
								<tr>
									<td>N&deg; Informes Pendientes</td>
									<td align="center"><span class="ClassText"><?php echo $aNroInfTrimFecha - floor((1/2) * $rMatriz['inf_entregados']); ?></span></td>
								</tr>
								<tr>
									<td colspan="2" class="RowAlternate">INFORMES FINANCIEROS</td>
								</tr>
								<tr style="color: #090; font-weight: bold;">
									<td>N&deg; Informes Planeados</td>
									<td align="center"><?php echo(round($rMatriz['inf_planeados_financ'],0)); ?></td>
								</tr>
								<tr>
									<td>N&deg; Inf. Planeados a la Fecha</td>
									<td align="center"><?php echo(round($rMatriz['inf_planeados_fecha_financ'],0)); ?></td>
								</tr>
								<tr>
									<td>N&deg; Informes Entregados</td>
									<td align="center"><?php
        $ls_urlRpt = "rpt_inf_mes_financ_list.php";
        $ls_urlPar = "&idProy=" . $rMatriz['codigo'];
        $ls_urlVie = "reportviewer.php?link=" . $ls_urlRpt . "&title=Listado de Informes Financieros Mensuales" . $ls_urlPar;
        
        ?>
            <span class="ClassText"><a href="#"
											onclick="window.open('<?php echo($ls_urlVie); ?>','InformMes','fullscreen,scrollbars')"><?php echo($rMatriz['inf_entregados_financ']); ?></a></span></td>
								</tr>
								<tr>
									<td>N&deg; Informes Pendientes</td>
									<td align="center"><span class="ClassText"><?php echo((round($rMatriz['inf_planeados_fecha_financ'],0)-$rMatriz['inf_entregados_financ'])); ?></span></td>
								</tr>
							</table>
							<p>
								</br>

							</p>
							<table width="200" border="0" cellpadding="0" cellspacing="0"
								class="TableGrid" style="font-size: 10px;">
								<thead>
									<tr class="ClassField">
										<td colspan="4" align="center">Informes Entregables
											Presentados</td>
									</tr>
									<tr class="ClassField">
										<td width="36" rowspan="2" align="center"># Ent</td>
										<td colspan="2" align="center">Desempeño</td>
										<td width="79" rowspan="2" align="center">Fecha de
											Presentación</td>
									</tr>
									<tr class="ClassField">
										<td width="40" align="center">Ent</td>
										<td width="43" align="center">Acum</td>
									</tr>
								</thead>
        <?php
        retInformeTrim($rMatriz['codigo'], $rMatriz['inicio']);
        ?>
      </table> <br />
						</td>
						<td align="center" valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								style="font-size: 10px;">
								<tr>
									<td width="6%" bgcolor="#E8E8E8">&nbsp;</td>
									<td colspan="2" align="center" valign="middle"
										bgcolor="#E8E8E8"><strong>Supervisor Externo</strong></td>
									<td colspan="2" align="center" valign="middle"
										bgcolor="#E8E8E8"><strong>Supervisor Interno</strong></td>
									<td colspan="2" align="center" valign="middle"
										bgcolor="#E8E8E8"><strong>Gestor de Proyectos</strong></td>
								</tr>
								<tr>
									<td align="center" bgcolor="#E8E8E8"># Inf</td>
									<td width="22%" align="center" valign="middle"
										bgcolor="#E8E8E8">Fecha</td>
									<td width="14%" align="center" valign="middle"
										bgcolor="#E8E8E8">Calif</td>
									<td width="14%" align="center" valign="middle"
										bgcolor="#E8E8E8">Fecha</td>
									<td width="14%" align="center" valign="middle"
										bgcolor="#E8E8E8">Calif</td>
									<td width="14%" align="center" valign="middle"
										bgcolor="#E8E8E8">Fecha</td>
									<td width="16%" align="center" valign="middle"
										bgcolor="#E8E8E8">Calif</td>
								</tr>
        
        <?php
        $retInfMoni = $objRep->RepInformesMonitoreo($rMatriz['codigo']);
        while ($rowInf = mysqli_fetch_assoc($retInfMoni)) {
            
            $valoraME = "";
            if ($rowInf['fecha_me'] != "") {
                $msg_ME = $objFunc->calificacionInforme($rowInf['califica_me'], array(
                    "style='background-color:red'",
                    "style='background-color:#FC0;'",
                    "style='background-color:#70FB60;'"
                ), $valoraME);
            }
            
            $valoraMI = "";
            if ($rowInf['fecha_mi'] != "") {
                $msg_ME = $objFunc->calificacionInforme($rowInf['califica_mi'], array(
                    "style='background-color:red'",
                    "style='background-color:#FC0;'",
                    "style='background-color:#70FB60;'"
                ), $valoraMI);
            }
            
            $valoraMF = "";
            if ($rowInf['fecha_mf'] != "") {
                if ($rowInf['califica_mf'] == 0) {
                    $valoraMF = "style='background-color:red'";
                } // Desaprobado
                if ($rowInf['califica_mf'] == 1) {
                    $valoraMF = "style='background-color:#FC0;'";
                } // Aprobado con Reservas
                if ($rowInf['califica_mf'] == 2) {
                    $valoraMF = "style='background-color:#70FB60;'";
                } // Aprobado con Reservas
            }
            
            ?>
        <tr>
									<td align="center"><?php echo($rowInf['num']);?></td>
									<td align="center" valign="middle"><a href="javascript:;"
										onclick="ViewInformeME('<?php echo($rMatriz['codigo']);?>','<?php echo($rowInf['num']);?>');"><?php echo($rowInf['fecha_me']);?></a></td>
									<td align="center" valign="middle" <?php echo($valoraME);?>><a
										href="javascript:ViewObservacioensME('<?php echo($rMatriz['codigo']);?>', '<?php echo($rowInf['fecha_me']);?>');"><?php echo($rowInf['califica_me']);?></a></td>
									<td align="center" valign="middle"><a href="javascript:;"
										onclick="ViewInformeMI('<?php echo($rMatriz['codigo']);?>','<?php echo($rowInf['num']);?>');"><?php echo($rowInf['fecha_mi']);?></a></td>
									<td align="center" valign="middle" <?php echo($valoraMI);?>><a
										href="javascript:ViewObservacioensMI('<?php echo($rMatriz['codigo']);?>', '<?php echo($rowInf['fecha_mi']);?>');"><?php echo($rowInf['califica_mi']);?></a></td>
									<td align="center" valign="middle"><a href="javascript:;"
										onclick="ViewInformeMF('<?php echo($rMatriz['codigo']);?>','<?php echo($rowInf['num']);?>');"><?php echo($rowInf['fecha_mf']);?></a></td>
									<td align="center" valign="middle" <?php echo($valoraMF);?>><?php echo($rowInf['califica_mf']);?></td>
								</tr>
        <?php
        }
        $retInfMoni->free();
        ?>
       
      </table> <span style="font-size: 10px; margin-top: 20px;"><a
								href="javascript:ViewCronogramaVisitas('<?php echo($rMatriz['codigo']);?>','1')"
								title="Cronograma de Visitas del SE">Cronograma de Visitas del
									SE</a> </span>

						</td>
					</tr>
    <?php
    } // End While
    $rsMatriz->free();
    ?>
  </tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />
			<p>
				<script language="JavaScript" type="text/javascript">
	function ViewMExt(idME)
	{
		var urlParams = "&idME="+idME;
		var reportID = 14;
		var newURL = "reportviewer.php?ReportID=" + reportID + "&" + urlParams ;
		var win =  window.open(newURL,"MonitorExterno","fullscreen,scrollbars");
		win.focus();
	}
	
	function ViewEquiProy(idProy)
	{
		
		var urlParams = "&idProy="+idProy;
		var reportID = 15;
		var newURL = "reportviewer.php?ReportID=" + reportID + "&" + urlParams ;
		var win =  window.open(newURL,"EquipoEjecutor","fullscreen,scrollbars");
		win.focus();
	}
	
	function ViewSector(idProy)
	{		
		var urlParams = "&idProy="+idProy;
		var reportID = 16;
		var newURL = "reportviewer.php?ReportID=" + reportID + "&" + urlParams ;
		var win =  window.open(newURL,"SectorProductivo","fullscreen,scrollbars");
		win.focus();
	}
	
	function ViewML(codigo,version)
	{
		urlReport = "rpt_ml.php";
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?link="+urlReport+"&title=Marco Logico" + urlParams;
		var win =  window.open(urlViewer,"MarcoLogico","fullscreen,scrollbars");
		win.focus();
		
	}
	function ViewCronograma(codigo,version)
	{
		urlReport = "rpt_poa.php";
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?link="+urlReport+"&title=Plan Operativo" + urlParams;
		var win =  window.open(urlViewer,"Cronograma","fullscreen,scrollbars");
		win.focus();
	}
	
	function ViewCronogramaVisitas(codigo,version){    
		urlReport = "rpt_cron_plan.php";
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?link="+urlReport+"&title=Reporte Cronograma de Visitas de Supervisor Externo" + urlParams;
		var win =  window.open(urlViewer,"Cronograma","fullscreen,scrollbars");
		win.focus();
	}
	function ViewPresupuesto(codigo,version)
	{
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?ReportID=12" + urlParams;
		var win =  window.open(urlViewer,"Presupuesto","fullscreen,scrollbars");
		win.focus();
	}
	
	function ViewCronDesembolsos(codigo, version)
	{
		var url = "filter_proyecto_fte.php?idProy="+codigo+"&idVersion="+version+"&ReportID=34";
		ChangeStylePopup("PopupDialog");
		loadPopup("Cronograma de Desembolsos - Seleccionar Fuente de Financiamiento" , url);
	}
	function ViewResumenDesembolsos(codigo)
	{
		var url = "rpt_cron_desemb_trim.php?idProy="+codigo;
		ChangeStylePopup("PopupDialog");
		loadPopup("Resumen de Desembolsos " , url);
	}

	function ViewBenef(codigo,version)
	{
		urlReport = "rpt_benef.php";
		urlParams = "&idProy="+codigo+"&idVersion="+version;
		urlViewer = "reportviewer.php?link="+urlReport+"&title=Listado de Beneficiarios" + urlParams;
		var win =  window.open(urlViewer,"Beneficiarios","fullscreen,scrollbars");
		win.focus();
	}
	function ViewDetailsTrim(proy, anio, trim)
	{ 
		var params = "idProy="+proy+"&anio="+anio+"&trim="+trim;
		NewReport("Cumplimiento Entregable del Proyecto","rpt_inf_trim_cumpli.php",params);	
	}
	function ViewDetailsTrimAcum(idproy, anio, trim)
	{
		var params = "idProy="+idproy+"&anio="+anio+"&trim="+trim;
		NewReport("Cumplimiento Entregable Acumulado del Proyecto","rpt_inf_trim_cumpli_acum.php",params);	
	}
	
	function ViewObservacioensME(idproy, fecha)
	{
		var url = "rpt_inf_me_conclusiones.php?idProy="+idproy+"&idFecha="+fecha;
		ChangeStylePopup("PopupDialog2");
		loadPopup("Conclusiones del Informe SE - " + fecha, url);
	}
	
	function ViewObservacioensMI(idproy, fecha)
	{
		var url = "rpt_inf_mi_conclusiones.php?idProy="+idproy+"&idFecha="+fecha;
		ChangeStylePopup("PopupDialog2");
		loadPopup("Conclusiones del Informe MI - " + fecha, url);
	}
	
	function ViewInformeME(idproy, idInf)
	{
		var arrayControls = new Array();
				arrayControls[0] = "idProy=" + idproy ;		
				arrayControls[1] = "num=" + idInf;
				arrayControls[2] = "vs=" + 1;
		var params = arrayControls.join("&"); 		
		NewReportID("10",params);	
	}
	
	function ViewInformeMI(idproy, idInf)
	{
		var arrayControls = new Array();
				arrayControls[0] = "idProy=" + idproy ;		
				arrayControls[1] = "num=" + idInf;
				arrayControls[2] = "idVersion=" + 1;
		var params = arrayControls.join("&"); 		
		NewReportID("55",params);	
	}
	
	function ViewInformeMF(idproy, idInf)
	{
		var arrayControls = new Array();
				arrayControls[0] = "idProy=" + idproy ;		
				arrayControls[1] = "idnum=" + idInf;
		var params = arrayControls.join("&"); 		
		NewReportID("57",params);	
	}
	
	
    function ViewPOA(pProy, pAnio)
	{
		urlViewer = "reportviewer.php?ReportID=79&idProy=" + pProy + "&idAnio="+pAnio;
		var win =  window.open(urlViewer,"Reporte POA - Especificacion Financiera","fullscreen,scrollbars");
		win.focus();
	}
	
	
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
        // echo(" <td > 0 </td>");
        // echo(" <td > 0 </td>");
        // echo(" <td > 0 </td>");
        echo ("     <td class='ClassText' align='right'>" . number_format($total, 2) . "</td>");
        echo ("</tr></tfoot>");
    }
}

function retInformeTrim($proy, $fecini)
{
    $objInf = new BLInformes();
    $rsInf = $objInf->InformeTrimestralListado($proy);
    
    while ($row = mysqli_fetch_assoc($rsInf)) {
        $ls_urlReport = "rpt_inf_trim_edit.php";
        $ls_urlParams = "&idProy=" . $proy . "&idanio=" . $row['t25_anio'] . "&idtrim=" . $row['t25_trim'] . "&idversion=" . $row['vsinf'];
        $ls_urlViewer = "reportviewer.php?link=" . $ls_urlReport . "&title=Informe Trimestral" . $ls_urlParams;
        
        // echo(print_r($row));
        $linkInforme = "<a href=\"#\" onclick='window.open(\"" . $ls_urlViewer . "\", \"InfTrim\", \"fullscreen,scrollbars\");'>" . $row['fec_pre'] . "</a>";
        
        if ($row['cump_trim'] >= 75) {
            $semaforo_trim = 'style="background-color:#70FB60;"';
        }
        if ($row['cump_trim'] >= 50 && $row['cump_trim'] < 75) {
            $semaforo_trim = 'style="background-color:#FC0;"';
        }
        if ($row['cump_trim'] < 50) {
            $semaforo_trim = 'style="background-color:red;"';
        }
        
        if ($row['cump_acum_trim'] >= 75) {
            $semaforo_acum = 'style="background-color:#70FB60;"';
        }
        if ($row['cump_acum_trim'] >= 50 && $row['cump_acum_trim'] < 75) {
            $semaforo_acum = 'style="background-color:#FC0;"';
        }
        if ($row['cump_acum_trim'] < 50) {
            $semaforo_acum = 'style="background-color:red;"';
        }
        
        $linkTrim = 'ViewDetailsTrim("' . $proy . '","' . $row['t25_anio'] . '","' . $row['t25_trim'] . '");';
        $linkTrimAcum = 'ViewDetailsTrimAcum("' . $proy . '","' . $row['t25_anio'] . '","' . $row['t25_trim'] . '");';
        
        echo ('<tr class="ClassText">');
        echo ('	<td width="18" align="center">' . $row['nrotrim'] . '</td>');
        echo ('	<td width="68" align="right" ' . $semaforo_trim . '> <a href=javascript:' . $linkTrim . '> ' . $row['cump_trim'].' </a></td>');  
	echo('	<td            align="right" '.$semaforo_acum.'> <a href=javascript:'.$linkTrimAcum.'> '.$row['cump_acum_trim'].'</a></td>');
	echo('	<td width="39" align="center">'.$linkInforme.'</td>');
    echo('</tr>');
	
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