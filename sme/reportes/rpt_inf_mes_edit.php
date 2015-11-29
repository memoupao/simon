<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLProyecto.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

$idProy = $objFunc->__Request('idProy');
$idAnio = $objFunc->__Request('anio');
$idMes = $objFunc->__Request('mes');

$objProy = new BLProyecto();
$ultima_vs = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $ultima_vs);

$objInf = new BLInformes();
$rowInf = $objInf->InformeMensualSeleccionar($idProy, $idAnio, $idMes, $ultima_vs);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Informe Mensual</title>
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
			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">CODIGO DEL PROYECTO</th>
					<td width="55%" align="left"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
					<th width="7%" align="left" nowrap="nowrap">INICIO</th>
					<td width="20%" align="left"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">TITULO DEL PROYECTO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td align="left">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="19%" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#CC9966"><strong
						style="color: blue;">1. DATOS GENERALES</strong></td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td width="20%" align="left" valign="middle"><strong>AÑO DE EJECUCIóN:&nbsp;&nbsp;<?php echo($idAnio);?></strong></td>
					<td width="20%" align="left" valign="middle"><strong>MES DE EJECUCIóN:&nbsp;&nbsp;<?php echo($idMes);?></strong></td>
					<td width="20%" align="left" valign="middle"><strong>PERIODO REPORTADO:&nbsp;&nbsp;<?php echo($rowInf['t20_periodo']);?></strong></td>
					<td width="20%" align="left" valign="middle"><strong>FECHA DEL INFORME:&nbsp;&nbsp;<?php echo($rowInf['t20_fch_pre']);?></strong></td>
					<td width="19%" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle" bgcolor="#CC9966"><strong
						style="color: blue;">2. AVANCE DE ACTIVIDADES</strong></td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><div
							id="divTableLista">
							<table width="780" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
            <?php
            // $objInf = new BLInformes();
            $rs = $objInf->ListaActividades($idProy);
            while ($row_act = mysqli_fetch_assoc($rs)) {
                
                $iRs = $objInf->ListaIndicadoresActividad($idProy, $row_act['codigo'], $idAnio, $idMes);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        
                        ?>
            <tr
										style="border: solid 1px #CCC; background-color: #eeeeee;">
										<td width="412" align="left" valign="middle"><strong>Indicador
												de Actividad</strong></td>
										<td height="15" colspan="3" align="center" valign="middle"
											bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
										<td colspan="3" align="center" valign="middle"
											bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
									</tr>
									<tr>
										<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']." ".$row['indicador']);?>
                <input name="t09_cod_act_ind[]" type="hidden"
											id="t09_cod_act_ind[]"
											value="<?php echo($row['t09_cod_act_ind']);?>" /> <br /> <span><strong
												style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span></td>
										<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
										<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
										<td width="55" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
										<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
										<td width="63" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
										<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									</tr>
									<tr>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
										<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
										<td align="center" nowrap="nowrap"><?php echo( $row['ejec_mtaacum']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtames']);?></td>
										<td align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
									</tr>
									<tr style="font-weight: 300; color: navy;">
										<td colspan="7" align="left"><strong>DESCRIPCION </strong><br />
                <?php echo(nl2br($row['descripcion']));?> 
                <br />
										<br /> <strong>LOGROS </strong><br />
                <?php echo(nl2br($row['logros']));?>
                <br />
										<br />
										<strong> DIFICULTADES </strong><br />
                <?php echo(nl2br($row['dificultades']));?></td>
									</tr>
            <?php
                        $RowIndex ++;
                    }
                    $iRs->free();
                } // Fin de SubActividades
            }
            
            ?>
          </tbody>
								<tfoot>
								</tfoot>
							</table>
							<input type="hidden" name="t02_cod_proy"
								value="<?php echo($idProy);?>" /> <input type="hidden"
								name="t02_version" value="<?php echo($idVersion);?>" /> <input
								type="hidden" name="t08_cod_comp" value="<?php echo($idComp);?>" />
							<input type="hidden" name="t09_cod_act"
								value="<?php echo($idAct);?>" /> <input type="hidden"
								name="t09_ind_anio" value="<?php echo($idAnio);?>" /> <input
								type="hidden" name="t09_ind_mes" value="<?php echo($idMes);?>" />
							<script language="JavaScript" type="text/javascript">
	 function TotalAvanceIndicador(x)
		{ 
		  var index=parseInt(x) ;
		  /*
		  var xTotal=$("input[name=txtIndActTot[]]") ;
		  var xAcum =$("input[name=txtIndActAcum[]]") ;
  		  var xMes =$("input[name=txtIndActMes[]]") ;
		  */
		  var xTotal=document.getElementsByName("txtIndActTot[]") ;
		  var xAcum =document.getElementsByName("txtIndActAcum[]");
  		  var xMes =document.getElementsByName("txtIndActMes[]") ;
		  
		  var mtaacum =parseFloat(xAcum[index].value) ;
		  var mtames =parseFloat(xMes[index].value) ;
		  if(isNaN(mtaacum)){mtaacum=0;}
		  if(isNaN(mtames)){mtames=0;}
  		  var total=(mtaacum + mtames) ;
		  xTotal[index].value = total ;
		  
   		}
	  </script>
						</div></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="left" valign="middle" bgcolor="#CC9966"><strong
						style="color: blue;">3. AVANCE DE SUB ACTIVIDADES</strong></td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="780"
							cellpadding="0" cellspacing="0" class="TableEditReg">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $rs = $objInf->ListaActividades($idProy);
        while ($row_act = mysqli_fetch_assoc($rs)) {
            
            $iRs = $objInf->ListaSubActividades($idProy, $row_act['codigo'], $idAnio, $idMes);
            // $iRs = $objInf->ListaSubActividades($idProy, $idActiv, $idAnio, $idMes);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row_sa = mysqli_fetch_assoc($iRs)) {
                    
                    ?>
          <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="412" align="left" valign="middle"><strong>Sub
											Actividad</strong></td>
									<td height="15" colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong>Meta Planeada</strong></td>
									<td colspan="3" align="center" valign="middle"
										bgcolor="#CCCCCC"><strong> Ejecutado</strong></td>
								</tr>
								<tr>
									<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row_sa['t08_cod_comp'].".".$row_sa['t09_cod_act'].".".$row_sa['t09_cod_sub']." ".$row_sa['subactividad']);?>
              <input name="t09_cod_act_ind[]2" type="hidden"
										id="t09_cod_act_ind[]2"
										value="<?php echo($row_sa['t09_cod_sub']);?>" /> <br /> <span><strong
											style="color: red;">Unidad Medida</strong>: <?php echo( $row_sa['t09_um']);?></span></td>
									<td width="60" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
									<td width="58" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="55" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
									<td width="62" align="center" bgcolor="#CCCCCC"><strong>Acum</strong></td>
									<td width="63" align="center" bgcolor="#CCCCCC"><strong>Mes</strong></td>
									<td width="68" align="center" bgcolor="#CCCCCC"><strong>Total</strong></td>
								</tr>
								<tr>
									<td align="center" nowrap="nowrap"><?php echo( $row_sa['plan_mtatotal']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row_sa['plan_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row_sa['plan_mtames']);?></td>
									<td align="center" nowrap="nowrap"><?php echo( $row_sa['ejec_mtaacum']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row_sa['ejec_mtames']);?></td>
									<td align="center" nowrap="nowrap"><?php echo($row_sa['ejec_mtatotal']);?></td>
								</tr>
								<tr style="font-weight: 300; color: navy;">
									<td colspan="7" align="left"><strong>DESCRIPCION </strong><br />
              <?php echo(nl2br($row_sa['descripcion']));?> <br /> <br />
										<strong>LOGROS </strong><br />
              <?php echo(nl2br($row_sa['logros']));?> <br /> <br /> <strong>
											DIFICULTADES </strong><br />
              <?php echo(nl2br($row_sa['dificultades']));?></td>
								</tr>
          <?php
                    $RowIndex ++;
                }
                $iRs->free();
            } // Fin de SubActividades
        }
        
        ?>
        </tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" align="left" valign="middle" bgcolor="#CC9966"><strong
						style="color: blue;">4. PROBLEMAS Y SOLUCIONES</strong></td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="750"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="30" align="center" valign="middle">#</td>
									<td width="240" align="center" valign="middle" nowrap="nowrap"><strong>Problemas</strong></td>
									<td width="240" height="23" align="center" valign="middle"><strong>Soluciones
											Adoptadas</strong></td>
									<td width="240" align="center" valign="middle"><strong>
											Resultados</strong></td>
								</tr>
          <?php
        // $objInf = new BLInformes(); $iRs = $objInf->ListaSubActividades($idProy, $row_act['codigo'], $idAnio, $idMes);
        $iRs = $objInf->ListaProblemasSoluciones($idProy, $idAnio, $idMes, $ultima_vs);
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
									<td width="30" align="center" valign="middle">
              <?php echo($row['t20_cod_prob']);?></td>
									<td width="240" align="left" valign="top"><?php echo(nl2br($row['t20_problemas']));?></td>
									<td width="240" align="left"><?php echo(nl2br($row['t20_soluciones']));?></td>
									<td width="240" align="left"><?php echo(nl2br($row['t20_resultados']));?></td>
								</tr>
          <?php
                $RowIndex ++;
            }
            $iRs->free();
        } // Fin de Problemas y soluciones
        
        ?>
        </tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="left" valign="middle" bgcolor="#CC9966"><strong
						style="color: blue;">5. ANEXOS</strong></td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
					<td align="left" valign="middle" bgcolor="#CC9966">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="750"
							border="0" cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="250" align="center" valign="middle">&nbsp;</td>
									<td width="250" align="center" valign="middle" nowrap="nowrap"><strong>Nombre
											del Archivo</strong></td>
									<td width="250" height="23" align="center" valign="middle"><strong>Descripcion
											del Archivo</strong></td>
								</tr>
								<tr>
			<?php
$objInf = new BLInformes();
$iRs = $objInf->ListaAnexosFotograficos($idProy, $idAnio, $idMes, $ultima_vs);
$RowIndex = 0;
if ($iRs->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>
          
					<?php
        $urlFile = $row['t20_url_file'];
        $filename = $row['t20_nom_file'];
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));
        $path = constant('APP_PATH') . "sme/proyectos/informes/anx_mes";
        $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
        if ($file_extension == 'gif' or $file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'png' or $file_extension == 'bmp') {
            $file_vista = "<img src=../../sme/proyectos/informes/anx_mes/" . $urlFile . " />";
        } else {
            $file_vista = "<a href=" . $href . " title='Descargar Archivo' target='_blank'>" . $row['t20_nom_file'] . "</a>";
        }
        ?>
            <td width="250" height="30" align="center" valign="top"> <?php echo ($file_vista); ?><br>
              <?php echo(nl2br($row['t20_desc_file']));?>
            </td>
            <?php if (is_int(($RowIndex+1)/3)){ echo("</tr><tr>");}?>   
          <?php
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
											style="display: none;"></iframe></td>
								</tr>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
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

function retInformeMensual($proy, $fecini)
{
    $objInf = new BLInformes();
    $rsInf = $objInf->InformeMensualListado($proy);
    while ($row = mysqli_fetch_assoc($rsInf)) {
        // echo(print_r($row));
        $linkInforme = "<a href='#' target='_blank'>" . $row['fec_pre'] . "</a>";
        echo ('<tr class="ClassText">');
        echo ('	<td width="18" align="center">' . $row['nummes'] . '</td>');
        echo ('	<td width="68" align="center">' . $row['fec_plan'] . '</td>');
        echo ('	<td width="39" align="center">' . $linkInforme . '</td>');
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