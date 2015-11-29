<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$idProy = $objFunc->__Request('idProy');
$idVs = 1;
$num = $objFunc->__Request('idNum');

$objProy = new BLProyecto();
$idVersion = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVersion);

$objInf = new BLInformes();
$rowInf = $objInf->InformeMISeleccionar($idProy, $num, $idVs);
$idInforme = $num;
$idVSInf = $idVs;

$objRep = new BLReportes();
$row = $objRep->RepFichaProy($idProy, $idVersion);
// $objRep = NULL ;

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
			<!--<table width="99%" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid">
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
  </table>-->

			<table width="700" cellpadding="0" cellspacing="0">
				<thead>

				</thead>

				<tbody class="data" bgcolor="#FFFFFF">

					<tr>
						<td width="27%" height="25" align="left" valign="middle"
							nowrap="nowrap" bgcolor="#E8E8E8"><strong>Numero del Informe</strong></td>
						<td align="left" valign="middle"><?php echo($idInforme);?> &nbsp;</td>
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Código
								del Proyecto</strong></td>
						<td width="31%" align="left" valign="middle"><strong><?php echo($row['t02_cod_proy']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Periodo
								Referencia</strong></td>
						<td colspan="3" align="left" valign="middle"><strong><?php echo($rowInf['t45_periodo']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Presentación</strong></td>
						<td colspan="3" align="left" valign="middle"><strong><?php echo($rowInf['t45_fch_pre']);?></strong></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#FFFFAA"><strong>Nombre
								del Jefe del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['jefe_proy']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Técnico</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_tema']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Financiero</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_fina']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#FFFFAA"><strong>Monitor
								Externo</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['moni_exte']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td height="32" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Nombre
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_nom_proy']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Institución
								Ejecutora</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t01_nom_inst']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fecha
								de Fundación</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['t01_fch_fund']);?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Presupuesto
								Promedio Anual</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['presup_prom_anual'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Instituciones
								Colaboradores o asociadas</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['inst_colabora']);?></td>
					</tr>

					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Objetivos
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_fin']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Propósito
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_pro']));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Población
								Beneficiaria</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(nl2br($row['t02_ben_obj']));?></td>
					</tr>
      <?php
    $objProy = new BLProyecto();
    $rsAmbito = $objProy->AmbitoGeo_Listado($idProy, $idVersion);
    $rowspan = mysqli_num_rows($rsAmbito);
    ?>
    <tr style="font-size: 11px;">
						<td rowspan="<?php echo($rowspan+2);?>" align="left"
							valign="middle" bgcolor="#E8E8E8"><strong>ámbito de Ejecución
								del Proyecto</strong></td>
						<td colspan="3" align="center" valign="middle"
							style="display: none;">&nbsp;</td>
					</tr>

					<tr style="font-size: 11px;">
						<td width="27%" height="23" align="center" valign="middle"
							bgcolor="#E8E8E8"><strong>Departamento</strong></td>
						<td width="23%" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Provincia</strong></td>
						<td width="19%" align="center" valign="middle" bgcolor="#E8E8E8"><strong>Distrito</strong></td>
					</tr>
        <?php while($r = mysqli_fetch_assoc($rsAmbito))  { ?>
        <tr style="font-size: 11px;">
						<td height="25" align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dpto']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $r['prov']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $r['dist']);?></td>
					</tr>
    	<?php
        }
        $rsAmbito->free();
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Duración
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo($row['duracion']." Años");?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Presupuesto
								del Proyecto</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['t02_pres_tot'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Monto
								Solicitado a Fondoempleo</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['t02_pres_fe'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><strong>Aportes
								de Contrapartida</strong></td>
						<td colspan="3" align="left" valign="middle"><?php echo(number_format($row['aportes_contra'],2));?></td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle">&nbsp;</td>
					</tr>
					<tr style="font-size: 11px;">
						<td colspan="4" align="left" valign="middle" bgcolor="#E8E8E8"><strong>Fuentes
								de Financiamiento</strong></td>
					</tr>
    <?php
    // $rsFuentes = $objRep->RepFichaProy_Fuentes($idProy, $idVersion);
    $rsFuentes = $objRep->RepFichaProy_Fuentes($idProy, 1);
    while ($rfte = mysqli_fetch_assoc($rsFuentes)) {
        ?>
    <tr style="font-size: 11px;">
						<td align="left" valign="middle" bgcolor="#E8E8E8"><?php echo($rfte['t01_sig_inst']);?></td>
						<td colspan="3" align="left" valign="middle">
							<div style="width: 85px; text-align: right;">
	  <?php echo(number_format($rfte['monto'],2));?>
      </div>
						</td>
					</tr>
	<?php
    }
    $rsFuentes->free();
    ?>
    
    <tr style="font-size: 11px;">
						<th colspan="4" align="left" valign="top">&nbsp;</th>
					</tr>

				</tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="3" align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<br />



			<table width="762" align="center" cellpadding="0" cellspacing="0">
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">2. INTRODUCCION</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td width="19%" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="799"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="800" height="23" align="left" valign="middle"><strong>Introducción</strong></td>
								</tr>
								<tr>
									<td width="800" align="left" valign="middle"><?php echo(nl2br($rowInf['t45_intro']));?></td>
								</tr>
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="800" height="23" align="left" valign="middle"><strong>Métodos
											y Fuentes de Información Utilizadas</strong></td>
								</tr>
								<tr>
									<td width="800" align="left" valign="middle"><?php echo(nl2br($rowInf['t45_fuentes']));?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table></td>
				</tr>
				<tr>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td width="20%" align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">3. AVANCE DE COMPONENTES</strong></td>
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
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">
						<table width="799" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<thead>

								<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<th style="border: solid 1px #CCC;" width="53" rowspan="2"
										align="center" valign="middle">Codigo</th>
									<th style="border: solid 1px #CCC;" width="144" rowspan="2"
										align="center" valign="middle">Indicador de Componente</th>
									<th style="border: solid 1px #CCC;" width="47" rowspan="2"
										align="center" valign="middle">U.M.</th>
									<th style="border: solid 1px #CCC;" width="37" rowspan="2"
										align="center" valign="middle">Meta Global</th>
									<th style="border: solid 1px #CCC;" height="28" colspan="3"
										align="center" valign="middle">Ejecutado</th>
									<th style="border: solid 1px #CCC;" width="209" rowspan="2"
										align="center" valign="middle">Observaciones del Monitor</th>
								</tr>

								<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
									<th style="border: solid 1px #CCC;" width="70" height="28"
										align="center" valign="middle">Acum</th>
									<th style="border: solid 1px #CCC;" width="70" align="center"
										valign="middle">Avance</th>
									<th style="border: solid 1px #CCC;" width="35" align="center"
										valign="middle">Total</th>
								</tr>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
          <?php
        // $objInf = new BLInformes();
        $rsc = $objInf->ListaComponentes($idProy);
        
        while ($row_comp = mysqli_fetch_assoc($rsc)) {
            
            $iRs = $objInf->ListaIndicadoresComponenteMI($idProy, $row_comp['t08_cod_comp'], $num);
            $RowIndex = 0;
            if ($iRs->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($iRs)) {
                    ?>

	<tr>
									<td align="left" nowrap="nowrap"><?php echo($row['t08_cod_comp_ind']);?></td>
									<td align="left"><?php echo( $row['indicador']);?>
          <input name="t08_cod_comp_ind[]" id="t08_cod_comp_ind[]"
										type="hidden" value="<?php echo($row['t08_cod_comp_ind']);?>"
										class="IndicadorComponente" /></td>
									<td align="center"><?php echo( $row['t08_um']);?></td>
									<td align="center"><?php echo( $row['plan_mtatotal']);?></td>
									<td align="center"><?php echo( $row['ejec_mtaacum']);?></td>
									<td align="center"><?php echo($row['ejec_avance']);?></td>
									<td align="center"><?php echo($row['ejec_mtatotal']);?></td>
									<td align="center">
										<div style="padding: 0px; width: 100%;"
											class="IndicadorComponente"><?php echo($row['descripcion']);?> </div>
									</td>
								</tr>  
		  
          <?php
                    
                    $RowIndex ++;
                }
                ?>
		
		<tr>
									<td align="left" valign="middle" colspan="8"><strong
										style="color: blue;"></strong></td>
								</tr>
		  <?php
                $iRs->free();
            } // Fin de SubActividades
        }
        
        ?>
        </tbody>
							<tfoot>
							</tfoot>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr bgcolor="#CCCCFF">
					<td align="left" valign="middle"><strong style="color: blue;">4.
							AVANCE DE ACTIVIDADES</strong></td>
					<td align="left" valign="middle" bgcolor="#CCCCFF">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
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
							<table width="799" cellpadding="0" cellspacing="0"
								class="TableEditReg">
								<thead>
								</thead>
								<tbody class="data" bgcolor="#FFFFFF">
            <?php
            // $objInf = new BLInformes();
            $rs = $objInf->ListaActividades($idProy);
            while ($row_act = mysqli_fetch_assoc($rs)) {
                
                $iRs = $objInf->ListaIndicadoresActividadMI($idProy, $row_act['codigo'], $num);
                $RowIndex = 0;
                if ($iRs->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($iRs)) {
                        ?>
            <tr
										style="border: solid 1px #CCC; background-color: #eeeeee;">
										<td width="497" align="left" valign="middle"><strong>Indicador
												de Actividad</strong></td>
										<td height="15" colspan="3" align="center" valign="middle"
											bgcolor="#CCCCCC"><strong>Ejecutado</strong></td>
									</tr>
									<tr>
										<td width="497" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_act_ind']." ".$row['indicador']);?>
                <input name="t09_cod_act_ind[]" type="hidden"
											id="t09_cod_act_ind[]"
											value="<?php echo($row['t09_cod_act_ind']);?>"
											class="IndicadoresActividad" /> <br /> <span><strong
												style="color: red;">Unidad Medida</strong>: <?php echo( $row['t09_um']);?></span>
											<br /> <span><strong style="color: red;">Meta Global</strong>: <?php echo( $row['plan_mtatotal']);?></span></td>
										<td width="93" align="center" nowrap="nowrap"
											bgcolor="#CCCCCC"><strong>Acum</strong></td>
										<td width="93" align="center" nowrap="nowrap"
											bgcolor="#CCCCCC"><strong>Avance</strong></td>
										<td width="114" align="center" nowrap="nowrap"
											bgcolor="#CCCCCC"><strong>Total</strong></td>
									</tr>
									<tr>
										<td width="93" align="center" nowrap="nowrap"><?php echo($row['ejec_mtaacum']);?></td>
										<td width="93" align="center" nowrap="nowrap"><?php echo($row['ejec_avance']);?></td>
										<td width="114" align="center" nowrap="nowrap"><?php echo($row['ejec_mtatotal']);?></td>
									</tr>
									<tr style="font-weight: 300; color: navy;">
										<td colspan="4" align="left"><strong>DESCRIPCION</strong><br />
                <?php echo(nl2br($row['descripcion']));?> <br /> <strong>LOGROS</strong><br />
                <?php echo(nl2br($row['logros']));?> <br /> <strong>DIFICULTADES</strong><br />
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
								type="hidden" name="t09_ind_trim" value="<?php echo($idTrim);?>" />
							<script language="JavaScript" type="text/javascript">
function Guardar_AvanceIndAct	()
{
<?php $ObjSession->AuthorizedPage(); ?>	
//	var BodyForm= serializeDIV('divAvanceActividades');  
var BodyForm=$("#FormData").serialize();

if(BodyForm==""){alert("La Actividad Seleccionada, no Tiene indicadores !!!"); return;}

if(confirm("Estas seguro de Guardar el avance de los Indicadores para el Informe ?"))
  {
	var activ = $('#cboactividad_ind').val(); 
	var anio = $('#cboanio').val();
	var trim = $('#cbotrim').val();
	var sURL = "inf_trim_process.php?action=<?php echo(md5('ajax_indicadores_actividad'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, indActSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
  }
}
function indActSuccessCallback	(req)
{
var respuesta = req.xhRequest.responseText;
respuesta = respuesta.replace(/^\s*|\s*$/g,"");
var ret = respuesta.substring(0,5);
if(ret=="Exito")
 {
 LoadIndicadoresActividad();
 alert(respuesta.replace(ret,""));
 }
else
{alert(respuesta);}  

}
	
function TotalAvanceIndicador(x)
{ 
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtIndActTot[]") ;
  var xAcum =document.getElementsByName("txtIndActAcum[]");
  var xMes =document.getElementsByName("txtIndActTrim[]") ;
  
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
				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">5. AVANCE DE SUB ACTIVIDADES</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">

						<table width="771" border="1" cellpadding="0" cellspacing="0">
							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
								<th width="53" rowspan="2" align="center" valign="middle">Codigo</th>
								<th width="144" rowspan="2" align="center" valign="middle">Actividades</th>
								<th width="47" rowspan="2" align="center" valign="middle">U.M.</th>
								<th width="37" rowspan="2" align="center" valign="middle">Meta
									Total</th>
								<th height="28" colspan="3" align="center" valign="middle">Total Periodo <?php echo(" (".$rowInf['t45_per_ini']." - ".$rowInf['t45_per_fin'].")"); ?></th>
								<th width="209" rowspan="2" align="center" valign="middle">Observaciones
									del Monitor</th>
							</tr>
							<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
								<th width="70" height="28" align="center" valign="middle">Programado</th>
								<th width="70" align="center" valign="middle">Ejecutado</th>
								<th width="35" align="center" valign="middle">% Ejec.</th>
							</tr>
          <?php
        
        $total_presup = 0;
        $total_ejec = 0;
        
        $iRsComp = $objInf->ListaComponentes($idProy);
        while ($rowcomp = mysqli_fetch_assoc($iRsComp)) {
            $idComp = $rowcomp['t08_cod_comp'];
            $NomComp = $rowcomp['t08_comp_desc'];
            
            ?>
        <tbody class="data">
								<tr
									style="background-color: #FFC; height: 25px; cursor: pointer;">
									<td align="left" nowrap="nowrap"><?php echo($idComp);?></td>
									<td colspan="7" align="left"><?php echo( $NomComp);?></td>
								</tr>
	   <?php
            
            $iRsAct = $objInf->ListaActividadesComp($idProy, $idComp);
            while ($rowAct = mysqli_fetch_assoc($iRsAct)) {
                $idAct = $rowAct['t09_cod_act'];
                
                // $total_presup += $rowAct['total_presup'];
                $total_ejec += $rowAct['ejecutado'];
                $total_planeado += $rowAct['programado'];
                
                $porcEjecucion = round((($rowAct['ejecutado'] / $rowAct['programado']) * 100), 2);
                
                ?>
          
            <tr
									style="background-color: #FC9; height: 25px; cursor: pointer;">
									<td align="left" nowrap="nowrap"><?php echo($rowAct['codigo']);?></td>
									<td colspan="7" align="left"><?php echo( $rowAct['actividad']);?></td>
								</tr>
       
            <?php
                $iRs = $objInf->ListaSubActividadesMI($idProy, ($idComp . '.' . $idAct), $idInforme, $idVSInf);
                while ($rowsub = mysqli_fetch_assoc($iRs)) {
                    $porcEjecucion = round((($rowsub['ejecutado'] / $rowsub['programado']) * 100), 2);
                    ?>
            <tr>
									<td align="left" nowrap="nowrap"><?php echo($rowsub['codigo']);?></td>
									<td align="left"><?php echo( $rowsub['subactividad']);?></td>
									<td align="center"><?php echo($rowsub['um']);?></td>
									<td align="center"><?php echo(round($rowsub['meta'],2));?></td>
									<td align="center"><?php echo(round($rowsub['programado'],2));?></td>
									<td align="center"><?php echo(round($rowsub['ejecutado'],2));?></td>
									<td align="center"><?php echo($porcEjecucion);?>%</td>
									<td align="left"><?php echo($rowsub['observaciones']);?></td>
								</tr>
            <?php } $iRs->free(); // Fin de SubActividades ?>
          </tbody>
          <?php } $iRsAct->free(); // Fin de Actividades 	?>
          <?php } $iRsComp->free();?>
          <tfoot>
								<tr style="color: #FFF; height: 20px;">
									<th colspan="3">&nbsp;</th>
									<th align="center">&nbsp;</th>
									<th align="right">&nbsp;</th>
									<th align="right">&nbsp;</th>
									<th align="center">&nbsp;</th>
									<th align="right">&nbsp;</th>
								</tr>
							</tfoot>
						</table>
					</td>
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
				<tr bgcolor="#CCCCFF">
					<td colspan="3" align="left" valign="middle"><strong
						style="color: blue;">6. ANáLISIS DE AVANCES</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="799"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Análisis
											de Avances del Proyecto</strong></td>
								</tr>

								<tr>
          <?php
        $rowAnalisis = $objInf->InformeMISeleccionar($idProy, $num, $idVs);
        ?>
            <td width="797" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_avance_comp']));?></td>
								</tr>

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
					<td colspan="5" align="left" valign="middle"><table width="799"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="776" height="23" align="left" valign="middle"><strong>Análisis
											de Avances en Capacitación, Asistencia Técnica y Otros
											Servicios a Beneficiarios</strong><br /></td>
								</tr>
								<tr>
									<td width="776" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_avance_cap']));?></td>
								</tr>
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
					<td colspan="5" align="left" valign="middle"><table width="799"
							cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="772" height="23" align="left" valign="middle"><strong>Análisis
											de Avance Financiero </strong></td>
								</tr>
								<tr>
									<td width="772" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_avance_fin']));?></td>
								</tr>
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

				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">7. INFORMACION ADICIONAL</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
    
    <?php
    $rowV = $objInf->InformeMISeleccionar($idProy, $num, $idVs);
    ?>
    
     <tr>
					<td colspan="5" align="left" valign="middle"><table width="799"
							cellpadding="0" cellspacing="0">

							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Calificación
											del Proyecto</strong></td>
								</tr>

								<tr>

									<td align="left" valign="middle">

										<table class="grid-table grid-width" class="TableEditReg">
											<tr>
												<td>&nbsp;</td>
												<td align="center"><b>Valoración</b></td>
											</tr>
											<tr>
												<td align="left">Relación planificado y ejecutado operativo
												</td>
												<td align="center" valign="middle"> 
          <?php
        $objTablas = new BLTablasAux();
        $objT = $objTablas->TipoSeleccionar($rowV['t45_eva1']);
        echo ($objT['descrip']);
        ?>           
          </td>
											</tr>
											<tr>
												<td align="left">Relación entre ejecución financiera y
													ejecución técnica.</td>
												<td align="center">
             <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t45_eva2']);
            echo ($objT['descrip']);
            ?>  </td>
											</tr>
											<tr>
												<td align="left">Avance de actividades críticas</td>
												<td align="center">
          
            <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t45_eva3']);
            echo ($objT['descrip']);
            ?>  </td>
											</tr>
											<tr>
												<td align="left">Calidad de las Capacitaciones</td>
												<td align="center">  
          <?php
        $objTablas = new BLTablasAux();
        $objT = $objTablas->TipoSeleccionar($rowV['t45_eva4']);
        echo ($objT['descrip']);
        ?>  </td>
											</tr>
											<tr>
												<td align="left">Calidad&nbsp; y congruencia&nbsp;
													(capacidad de cobertura del ámbito del proyecto) del
													equipo técnico</td>
												<td align="center">
          <?php
        $objTablas = new BLTablasAux();
        $objT = $objTablas->TipoSeleccionar($rowV['t45_eva5']);
        echo ($objT['descrip']);
        ?>  </td>
											</tr>
											<tr>
												<td align="left">Opinión de los beneficiarios respecto al
													proyecto y sus resultados</td>
												<td align="center">
            <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t45_eva6']);
            echo ($objT['descrip']);
            ?>  </td>
											</tr>

											<tr>
												<td align="left">Manejo adecuado de Proyecto</td>
												<td align="center">
            <?php
            $objTablas = new BLTablasAux();
            $objT = $objTablas->TipoSeleccionar($rowV['t45_eva7']);
            echo ($objT['descrip']);
            ?>  </td>
											</tr>
          <?php
        $aprobado = $objFunc->calificacionInforme($rowV['puntaje']);
        ?>
          <tr>
												<td align="right"><strong>RESULTADO</strong></td>
												<td align="center"><span id="spanTotResult"><?php echo($rowV['puntaje'])?>&nbsp;</span>
													&nbsp;&nbsp; <span id="spanTotResult2"><?php echo($aprobado)?>&nbsp;</span>
												</td>
											</tr>
										</table>
									</td>
							
							
							<thead>
							</thead>

							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Logros</strong></td>
								</tr>

								<tr>
									<td width="797" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_logros']));?></td>
								</tr>


								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Dificultades</strong></td>
								</tr>

								<tr>
									<td width="797" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_dificul']));?></td>
								</tr>

								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Recomendaciones
											al Proyecto</strong></td>
								</tr>

								<tr>
									<td width="797" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_reco_proy']));?></td>
								</tr>


								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Recomendaciones
											a Fondoempleo</strong></td>
								</tr>

								<tr>
									<td width="797" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_reco_fe']));?></td>
								</tr>



								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td width="797" height="23" align="left" valign="middle"><strong>Calificacición</strong></td>
								</tr>

								<tr>
									<td width="797" align="left" valign="middle"><?php echo(nl2br($rowAnalisis['t45_califica']));?></td>
								</tr>






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


				<tr bgcolor="#CCCCFF">
					<td colspan="2" align="left" valign="middle"><strong
						style="color: blue;">8. ANEXOS</strong></td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
					<td align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" align="left" valign="middle"><table width="801"
							border="0" cellpadding="0" cellspacing="0">
							<thead>
							</thead>
							<tbody class="data" bgcolor="#FFFFFF">
								<tr class="SubtitleTable"
									style="border: solid 1px #CCC; background-color: #eeeeee;">
									<td height="22" colspan="3" align="left" valign="middle"><strong>Archivos
											Adjuntos al Informe de Monitoreo Interno</strong></td>
								</tr>
								<tr>
			<?php
// $objInf = new BLInformes();
$iRs = $objInf->ListaAnexosInformeMI($idProy, $num, $idVs);
$RowIndex = 0;
if ($iRs->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>
          
					<?php
        $urlFile = $row['t45_url_file'];
        $filename = $row['t45_nom_file'];
        $file_extension = strtolower(substr(strrchr($filename, "."), 1));
        $path = constant('APP_PATH') . "sme/proyectos/informes/anx_mi";
        $href = constant("DOCS_PATH") . "download.php?filename=" . $filename . "&fileurl=" . $urlFile . "&path=" . $path;
        if ($file_extension == 'gif' or $file_extension == 'jpg' or $file_extension == 'jpeg' or $file_extension == 'png' or $file_extension == 'bmp') {
            $file_vista = "<img src=../../sme/proyectos/informes/anx_trim/" . $urlFile . " />";
        } else {
            $file_vista = "<a href=" . $href . " title='Descargar Archivo' target='_blank'>" . $row['t45_nom_file'] . "</a>";
        }
        ?>
            <td width="799" height="30" align="center" valign="top"> <?php echo ($file_vista); ?><br>
              <?php echo(nl2br($row['t45_desc_file']));?>
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