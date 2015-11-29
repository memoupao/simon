<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLReportes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$idTrim = $objFunc->__Request('idTrim');

$objProy = new BLProyecto();
$idVersion = $objProy->MaxVersion($idProy);
$Proy_Datos_Bas = $objProy->GetProyecto($idProy, $idVersion);

$objInf = new BLInformes();
$rowInf = $objInf->InformeTrimestralSeleccionar($idProy, $idAnio, $idTrim, 1);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>.</title>
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
			<table width="800" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th width="18%" align="left">CODIGO DEL PROYECTO</th>
					<td colspan="4" align="left"><?php echo($Proy_Datos_Bas['t02_cod_proy']);?></td>
					<th width="10%" align="left" nowrap="nowrap">INICIO</th>
					<td width="18%" align="left"><?php echo($Proy_Datos_Bas['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th align="left" nowrap="nowrap">TITULO DEL PROYECTO</th>
					<td colspan="4" align="left"><?php echo($Proy_Datos_Bas['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($Proy_Datos_Bas['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th height="26" align="left">FECHA DEL INFORME</th>
					<td width="18%"><?php echo($rowInf['t25_fch_pre']);?></td>
					<td width="11%">&nbsp;</td>
					<th width="5%">AÑO</th>
					<td width="20%">Año <?php echo($rowInf['t25_anio']);?></td>
					<th>&nbsp;</th>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th align="left">TRIMESTRE</th>
					<td colspan="4" align="left">Trim <?php echo($rowInf['t25_trim']);?> (<?php echo($rowInf['t25_periodo']);?>) </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br />
			<div>
  <?php
$objPOA = new BLPOA();
$idVS = $objPOA->UltimaVersionPoa($idProy, $idAnio);
$rsMod = $objPOA->Lista_InfTrim_PlanCapac(1, $idProy, $idVS, NULL, NULL);
$arrMod = NULL;
$arrSub = NULL;
$arrTem = NULL;
?>
  <table border="0" cellspacing="0" cellpadding="0" width="770">
					<tbody class="data" bgcolor="#eeeeee">
						<tr>
							<td colspan="15" align="center" valign="middle"><strong>Datos del
									Beneficiario </strong></td>
							<td colspan="16" align="center" valign="middle"><strong>Datos
									Productivos</strong></td>
        <?php
        $rowAt = $objInf->InfTriReport_AT_temas($idProy, $idVS);
        $rowOtros = $objInf->InfTrim_Otros_Lista_prod($idProy, $idVS);
        $totalOtros = 0;
        while ($rowotros = mysqli_fetch_assoc($rowOtros)) {
            $totalOtros ++;
        }
        
        while ($rm = mysqli_fetch_assoc($rsMod)) {
            $arrMod[] = $rm['codmodulo'];
            $arrNomMod[] = $rm['nommodulo'];
            ?>
        <td colspan="<?php echo($rm['numsub']); ?>" align="center"
								valign="middle"><strong><?php echo( $rm['nommodulo']);?></strong></td>

        <?php } ?>
		
         <td rowspan="3" colspan="1" align="center" valign="middle"><strong>Resultado</strong></td>
		 <?php if(count(mysqli_fetch_assoc($rowAt)) >0){ ?>
		 <td colspan="<?php echo(count(mysqli_fetch_assoc($rowAt))+1);?>"
								align="center" valign="top" rowspan="2"><strong>Asistencia
									Tecnica</strong></td>

							<td rowspan="3" colspan="1" align="center" valign="middle"><strong>Resultado</strong></td>
	  <?php }?>
	  
	   <?php if($totalOtros>0){ ?>
		 <td colspan="<?php echo $totalOtros; ?>" align="center" valign="top"
								rowspan="2"><strong>Otros Servicios</strong></td>
	  <?php }?>
	  
	  </tr>
						<tr>
							<td width="3%" rowspan="2" align="center" valign="middle"><strong>DNI</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Apellidos
									y Nombres</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Sexo</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Edad</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Intrucción</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Especialidad</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Departamento</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Provincia</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Distrito</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Caserío</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Dirección</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Ciudad</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Teléfono</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Celular</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Mail</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Actividad
									Principal</strong></td>

							<td rowspan="2" align="center" valign="middle"><strong>Sector
									Productivo 1</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Sector
									Productivo 2</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Sector
									Productivo 3</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Sub Sector
									1</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Sub Sector
									2</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Sub Sector
									3</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Unidades
									de<br />Producción 1
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Unidades
									de<br />Producción 2
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Unidades
									de<br />Producción 3
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Total
									Unidades de<br />Producción. 1
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Total
									Unidades de<br />Producción 2
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Total
									Unidades de<br />Producción 3
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Unidades
									de<br />Producción con el <br />Proyecto 1
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Unidades
									de<br />Producción con el <br />Proyecto 2
							</strong></td>
							<td rowspan="2" align="center" valign="middle"><strong>Unidades
									de<br />Producción con el <br />Proyecto 3
							</strong></td>
		
        <?php
        for ($x = 0; $x < count($arrMod); $x ++) {
            $rsSub = $objPOA->Lista_InfTrim_PlanCapac(2, $idProy, $idVS, $arrMod[$x], NULL);
            while ($rsub = mysqli_fetch_assoc($rsSub)) {
                $arrSub[$arrMod[$x]][] = $rsub['codigo'];
                ?>
        <td colspan="<?php echo($rsub['numtema']);?>" align="center"
								valign="top"><strong><?php echo($rsub['codigo'].'<br>'.$rsub['t09_sub'])?></strong></td>
        <?php
            } // EndWhile
        } // EndFor
        ?>
		
        
      </tr>
						<tr>
        <?php
        for ($x = 0; $x < count($arrMod); $x ++) {
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $rsTema = $objPOA->Lista_InfTrim_PlanCapac(3, $idProy, $idVS, $arrMod[$x], $arrSub[$arrMod[$x]][$y]);
                while ($rtema = mysqli_fetch_assoc($rsTema)) {
                    $arrTemasCap[] = $rtema;
                    
                    $codig = $arrSub[$arrMod[$x]][$y] . '.' . $rtema['t12_cod_tema'];
                    $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_cod_tema'];
                    $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]][] = $rtema['t12_tem_espe'];
                    ?>
        <td align="center" valign="middle" style="min-width: 120px;"><strong><?php echo($rtema['t12_tem_espe']); ?></strong></td>
        
        <?php
                } // EndWhile
            } // EndFor
        } // EndFor
        $totalAt = 0;
        
        $rowAt = $objInf->InfTriReport_AT_temas($idProy, $idVS);
        $rowOtros = $objInf->InfTrim_Otros_Lista_prod($idProy, $idVS);
        while ($rowat = mysqli_fetch_assoc($rowAt)) {
            $totalAt ++;
            ?>
			 <td align="center" valign="middle" style="min-width: 120px;"><strong><?php echo($rowat['t12_conten']); ?></strong></td>
        
			  <?php
        }
        
        while ($rowotros = mysqli_fetch_assoc($rowOtros)) {
            
            ?>
			 <td align="center" valign="middle" style="min-width: 120px;"><strong><?php echo($rowotros['t12_producto']); ?></strong></td>
        
			  <?php
        }
        ?>
             
		
      </tr>
					</tbody>
					<tbody class="data">
      <?php
    
    $objRpt = new BLReportes();
    $iRsBenef = $objRpt->Rep_Consolidado_Beneficiarios($idProy, $idAnio, $idTrim);
    
    while ($rb = mysqli_fetch_assoc($iRsBenef)) {
        
        ?>
      <tr>
							<td width="3%" align="center" valign="middle"><input
								name="txtbenef[]" type="hidden" id="txtbenef[]"
								value="<?php echo($rb['t11_cod_bene']); ?>"
								class="PlanCapacitacion" />
          <?php echo($rb['t11_dni']); ?></td>
							<td align="left" valign="middle" style="min-width: 250px;"><?php echo($rb['nombres']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['sexo']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_edad']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['nivel']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['especialidad']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['departamento']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['provincia']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['distrito']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['caserio']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_direccion']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_ciudad']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_telefono']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_celular']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_mail']); ?></td>
							<td align="left" valign="middle" style="min-width: 30px;"><?php echo($rb['t11_act_princ']); ?></td>

							<!--datos productivos-->
							<td align="left" valign="middle" style="min-width: 200px;"><?php echo($rb['sec_prod_1']); ?></td>
							<td align="left" valign="middle" style="min-width: 200px;"><?php echo($rb['sec_prod_2']); ?></td>
							<td align="left" valign="middle" style="min-width: 200px;"><?php echo($rb['sec_prod_3']); ?></td>
							<td align="left" valign="middle" style="min-width: 200px;"><?php echo($rb['subsec_prod_1']); ?></td>
							<td align="left" valign="middle" style="min-width: 200px;"><?php echo($rb['subsec_prod_2']); ?></td>
							<td align="left" valign="middle" style="min-width: 200px;"><?php echo($rb['subsec_prod_3']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_unid_prod_1']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_unid_prod_2']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_unid_prod_3']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_tot_unid_prod_1']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_tot_unid_prod_2']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_tot_unid_prod_3']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_nro_up_b_1']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_nro_up_b_2']); ?></td>
							<td align="center" valign="middle" style="min-width: 30px;"> <?php echo($rb['t11_nro_up_b_3']); ?></td>

							<!-- fin -->
        <?php
        $t = 0;
        $arrSumMOD = NULL;
        $resultado = 0;
        $numItems = 0;
        
        for ($x = 0; $x < count($arrMod); $x ++) {
            $arrSumMOD[$x] = 0;
            
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $arritem = $arrTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]];
                $nomtema = $arrNomTem[$arrMod[$x]][$arrSub[$arrMod[$x]][$y]];
                for ($z = 0; $z < count($arritem); $z ++) {
                    $numItems ++;
                    $codig = $arrSub[$arrMod[$x]][$y] . '.' . $arritem[$z];
                    $valor = $rb[$codig];
                    
                    if ($valor == '1') {
                        $resultado ++;
                        $arrSumMOD[$x] += $arrTemasCap[$t]['t12_nro_hora'];
                    }
                    ?>
        <td width="3%" valign="middle" align="center">
          <?php if($valor=='1'){echo("<b>X</b>");}?>
          </td>
        
        <?php
                    
$t ++;
                }
            }
        }
        ?>
		  <td width="3%" valign="middle" align="center">
		  <?php
        // echo "--->".$trim;
        if ($rowInf['t25_trim'] == 4) {
            if ($rb['estado'] == "Activo") {
                if ($resultado == $numItems)
                    echo "Capacitado";
                else
                    echo "Incompleto";
            } else 
                if ($rb['estado'] == "Inactivo") {
                    if ($resultado == $numItems)
                        echo "Completo";
                    else
                        echo "Retirado";
                }
        } else {
            if ($rb['estado'] == "Activo") {
                if ($resultado == $numItems)
                    echo "Proceso";
                else
                    echo "Incompleto";
            } else 
                if ($rb['estado'] == "Inactivo") {
                    if ($resultado == $numItems)
                        echo "Completo";
                    else
                        echo "Retirado";
                }
        }
        
        ?>
		  </td>  
		<?php
        $rowAtCap = $objInf->InfTriReport_AT_Cap($idProy, $idAnio, $idTrim, $rb['t11_cod_bene']);
        $contadorAt = 0;
        $rowAtcap = mysqli_fetch_assoc($rowAtCap);
        $rowAt = $objInf->InfTriReport_AT_temas($idProy, ($idVS));
        
        while ($rowat = mysqli_fetch_assoc($rowAt)) {
            if ($rowAtcap[$rowat['tema']] == 1) {
                $contador ++;
                ?>	
				<td width="3%" valign="middle" align="center"><b>X</b></td>
				<?php
            } else {
                ?>
				<td width="3%" valign="middle" align="center"><b></b></td>
				<?php
            }
        }
        ?>
		  <td width="3%" valign="middle" align="center">
		  <?php
        // echo "--->".$trim;
        if ($rowInf['t25_trim'] == 4) {
            if ($rb['estado'] == "Activo") {
                if ($totalAt == $contador)
                    echo "Capacitado";
                else
                    echo "Incompleto";
            } else 
                if ($rb['estado'] == "Inactivo") {
                    if ($totalAt == $contador)
                        echo "Completo";
                    else
                        echo "Retirado";
                }
        } else {
            if ($rb['estado'] == "Activo") {
                if ($totalAt == $contador)
                    echo "Proceso";
                else
                    echo "Incompleto";
            } else 
                if ($rb['estado'] == "Inactivo") {
                    if ($totalAt == $contador)
                        echo "Completo";
                    else
                        echo "Retirado";
                }
        }
        ?>
		  </td>  
		  <?php
        $rowOtrosBen = $objInf->InfTrim_Otros_Lista_Ben($idProy, $idAnio, $idTrim, $rb['t11_cod_bene']);
        $rowotrosBen = mysqli_fetch_assoc($rowOtrosBen);
        $rowOtros = $objInf->InfTrim_Otros_Lista_prod($idProy, $idVS);
        $aContador = 0;
        
        while ($rowotros = mysqli_fetch_assoc($rowOtros)) {
			if($rowotrosBen[$rowotros['codigo']]==1){
				$aContador++;
			?>	
				<td width="3%" valign="middle" align="center"><b>X</b></td>
				<?php
				}else{
				?>
				<td width="3%" valign="middle" align="center"><b></b></td>
				<?php
				}
		}
	  ?>
      </tr>
    <?php 
	} ?>
    </tbody>
				</table>
			</div>

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>