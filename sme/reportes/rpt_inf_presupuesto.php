<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLManejoProy.class.php");
require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLPresupuesto.class.php");
require_once (constant("PATH_CLASS") . "BLFuentes.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');

$objProy = new BLProyecto();
$rowProy = $objProy->ProyectoSeleccionar($idProy, $idVersion);

$objMP = new BLManejoProy();
// $rsPresup = $objMP->Rpt_PresupuestoAnalitico($idProy, $idVersion);
$rsPresup = $objMP->Rpt_PresupuestoEjeMensual($idProy, $idVersion);

$objPresup = new BLPresupuesto();
$objFuentes = new BLFuentes();

$rowFuentes = $objFuentes->ListadoFuentesFinan($idProy);

if ($idVersion > 1) {
    $msgTitle = "POA Anio " . ($idVersion - 1);
    $msgAnio = "Anio " . ($idVersion - 1);
}

?>
 

<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Presupuesto Analitico</title>
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
			<style>
.componente {
	background-color: #FFC;
}

.actividad {
	background-color: #FC9;
}

.subactividad {
	background-color: #CFF;
}

.rubro {
	background-color: #FFF;
}

.addSubActividad {
	color: #a2b500;
}
</style>

			<table width="99%" border="0" align="center" cellpadding="0"
				cellspacing="0" class="TableGrid">
				<tr>
					<th width="13%" height="23" align="left">CODIGO DEL PROYECTO</th>
					<td width="2%" align="left">&nbsp;</td>
					<td width="54%" align="left"><?php echo($rowProy['t02_cod_proy']);?> - <a
						href="<?php if($rowProy['t01_web_inst']==''){echo('#');}else{echo($objFunc->verifyURL($rowProy['t01_web_inst']));} ?>"
						target="_blank" title="Ir a Pagina web del Ejecutor"><?php echo($rowProy['t01_sig_inst']);?></a></td>
					<th width="4%" align="left" nowrap="nowrap">INICIO</th>
					<td width="27%" align="left"><?php echo($rowProy['ini']);?></td>
				</tr>
				<tr>
					<th colspan="2" align="left">DESCRIPCION DEL PROYECTO</th>
					<td align="left"><?php echo($rowProy['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($rowProy['fin']);?></td>
				</tr>
				<tr>
					<th height="18" align="left">&nbsp;</th>
					<td colspan="2">&nbsp;</td>
					<td colspan="2"><strong><?php echo($msgTitle);?></strong></td>
				</tr>
			</table>
			<table width="99%" border="0" cellpadding="0" cellspacing="0">
				<thead>
  <?php
$campos = $objMP->iGetArrayFields($rsPresup);

$arrfuentes = NULL;

$Fuentes = NULL;
// $fuents=$objMP->iGetArrayFields($rowFuentes);

for ($x = 8; $x < count($campos); $x ++) {
    $arrfuentes[$x - 8][0] = $campos[$x]; // --> Para los Nombres de Fuentes
    $arrfuentes[$x - 8][1] = 0; // --> Para los Totales
}
$FuentesNombres = NULL;

// echo("<pre>");
// print_r($Fuentes);

while ($rowf = mysqli_fetch_assoc($rowFuentes)) {
    $FuentesNombres[] = $rowf["t01_sig_inst"];
    $Fuentes[] = $rowf["t01_id_inst"];
}

// echo("</pre>") ;
// print_r($campos);

$sumaTotal = 0;
$colspan = count($arrfuentes) + 1; // 1=columna de Total

for ($contador = 0; $contador < count($Fuentes); $contador ++) {
    
    $rsPresup = $objMP->Rpt_PresupuestoEjeMensual($idProy, $idVersion);
    
    // print_r($rsPresup);
    
    ?>
  
	<tr>
						<td colspan="27" style="background: #ffffff; text-align: center;"><?php  echo '<h1 style="margin-top:15px; text-align:center; font-size:14px;">'.$FuentesNombres[$contador].'</h1>'; ?></td>
					</tr>
					<tr style="background: #CFC;">
						<td colspan="2" rowspan="3" align="center" valign="middle">Cuentas
							Presupuestales</td>
						<td width="196" rowspan="3" align="center" valign="middle">Unidad
							de Medida</td>
						<td width="100" rowspan="3" align="center" valign="middle">Costo
							Parcial</td>
						<td width="87" rowspan="3" align="center" valign="middle">Meta
							Reprogramada</td>
						<td width="147" rowspan="3" align="center" valign="middle">Costo
							Total</td>
						<td width="100" rowspan="3" align="center" valign="middle">Presupuesto
							Aprobado</td>
						<td width="100" rowspan="3" align="center" valign="middle">Gasto Acumulado <?php echo($msgAnio);?></td>
						<td width="100" colspan="16" align="center" valign="middle"><?php echo($msgAnio);?></td>
						<td width="100" rowspan="3" align="center" valign="middle">Total
							Presupuesto Ejecutado</td>
						<td width="100" rowspan="3" align="center" valign="middle">% de
							Ejecucion</td>
						<td width="100" rowspan="3" align="center" valign="middle">Presupuesto
							por Ejecutar</td>

						<!--td colspan="<?php echo($colspan);?>" align="center">Fuentes de finaciamiento</td-->
					</tr>
					<tr>
						<td colspan="4" align="center">Trimestre 1</td>
						<td colspan="4" align="center">Trimestre 2</td>
						<td colspan="4" align="center">Trimestre 3</td>
						<td colspan="4" align="center">Trimestre 4</td>

	<?php
    for ($x = 0; $x < count($arrfuentes); $x ++) { /*
       * echo('<td width="103" rowspan="2" align="center">'. $arrfuentes[$x][0] .'</td>');
       */
    }
    ?>
    <!--td rowspan="2" width="110" align="center">TOTAL</td-->
					</tr>
					<tr>
						<td align="center">mes 1</td>
						<td align="center">mes 2</td>
						<td align="center">mes 3</td>
						<td align="center">Total Trim 1</td>
						<td align="center">mes 1</td>
						<td align="center">mes 2</td>
						<td align="center">mes 3</td>
						<td align="center">Total Trim 2</td>
						<td align="center">mes 1</td>
						<td align="center">mes 2</td>
						<td align="center">mes 3</td>
						<td align="center">Total Trim 3</td>
						<td align="center">mes 1</td>
						<td align="center">mes 2</td>
						<td align="center">mes 3</td>
						<td align="center">Total Trim 4</td>


					</tr>

				</thead>

				<tbody class="data">
  <?php
    $Index = 1;
    
    while ($row = mysqli_fetch_assoc($rsPresup)) {
        
        $tipo = $row['tipo'];
        $total_fila = 0;
        
        if ($tipo == 'componente') {
            $sumaTotal += $row['total'];
            for ($x = 0; $x < count($arrfuentes); $x ++) {
                $arrfuentes[$x][1] += $row[$arrfuentes[$x][0]];
            }
        }
        
        $numNivel = 0;
        $nivel = split("[.]", $row['codigo']);
        $idComp = 0;
        $idAct = 0;
        $idSubAct = 0;
        $cat = 0;
        $tGasAcum = 0;
        
        if (count($nivel) == 1) {
            $numNivel = 0;
            $idComp = $nivel[0];
        }
        if (count($nivel) == 2) {
            $numNivel = 1;
            $idComp = $nivel[0];
            $idAct = $nivel[1];
        } else 
            if (count($nivel) == 3) {
                $numNivel = 2;
                $idComp = $nivel[0];
                $idAct = $nivel[1];
                $idSubAct = $nivel[2];
            } else 
                if (count($nivel) == 4) {
                    $numNivel = 3;
                    $idComp = $nivel[0];
                    $idAct = $nivel[1];
                    $idSubAct = $nivel[2];
                    $cat = $nivel[3];
                }
        
        for ($j = 1; $j < ($idVersion - 1); $j ++) {
            
            $roePresA = $objPresup->ListaActSubCat($idProy, $idComp, $idAct, $idSubAct, $cat, $j, $numNivel, $Fuentes[$contador]);
            $rowAt = mysqli_fetch_assoc($roePresA);
            
            $x = 1;
            $trimTtl01 = 0;
            
            for ($i = 1; $i < 17; $i ++) {
                if ($i % 4 == 0) {
                    
                    $tGasAcum += $trimTtl01;
                    $trimTtl01 = 0;
                } else 
                    if ($x == $rowAt['t40_mes']) {
                        $trimTtl01 += $rowAt['total_ejec'];
                        $rowAt = mysqli_fetch_assoc($roePresA);
                        $x ++;
                    } else {
                        $x ++;
                    }
            }
        }
        
        $presupuesto = $row[$arrfuentes[$contador + 1][0]];
        /*
         * for($x=0; $x<count($arrfuentes); $x++) { echo('<td width="103" align="right">'. number_format($row[$arrfuentes[$x][0]],2) .'</td>'); $total_fila += $row[$arrfuentes[$x][0]] ; }
         */
        ?>
   <tr
						class="<?php echo($tipo); if($row['act_add']==1){echo " addSubActividad";}?>">

						<td width="48" align="left" valign="middle"><?php echo($row['codigo']);?></td>
						<td width="412" align="left" valign="middle"><?php echo($row['descripcion']);?></td>
						<td align="center" valign="middle"><?php echo($row['um']);?></td>
						<td align="right" valign="middle"><?php echo(number_format($row['parcial'],2));?></td>
						<td align="center" valign="middle"><?php /*if(count($nivel)!=3){ */ echo($row['meta']);/*}*/ ?></td>
						<td align="right" valign="middle"><?php echo(number_format($row['total'],2));?></td>
						<td align="right" valign="middle"><?php echo(number_format($presupuesto,2));?></td>

						<td align="right" valign="middle"><?php echo(number_format($tGasAcum,2));?></td>
	

	
	
    <?php
        
        $roePresA = $objPresup->ListaActSubCat($idProy, $idComp, $idAct, $idSubAct, $cat, $idVersion - 1, $numNivel, $Fuentes[$contador]);
        
        $rowAt = mysqli_fetch_assoc($roePresA);
        
        $x = 1;
        $trimTtl01 = 0;
        // $trimTtl = $tGasAcum;
        $trimTtl = 0;
        
        for ($i = 1; $i < 17; $i ++) {
            if ($i % 4 == 0) {
                
                echo ('<td width="103" align="right" style="background-color:#DCD1FA;">' . $trimTtl01 . '</td>');
                $trimTtl += $trimTtl01;
                $trimTtl01 = 0;
            } else 
                if ($x == $rowAt['t40_mes']) {
                    echo ('<td width="103" align="right">' . $rowAt['total_ejec'] . '</td>');
                    $trimTtl01 += $rowAt['total_ejec'];
                    $rowAt = mysqli_fetch_assoc($roePresA);
                    
                    $x ++;
                } else {
                    
                    echo ('<td width="103" align="right">0.00</td>');
                    $x ++;
                }
        }
        ?>
	 <td align="right" valign="middle"><?php echo(number_format($trimTtl,2));?></td>
						<td align="right" valign="middle"><?php echo(number_format(($trimTtl/$presupuesto),2));?>%</td>
						<td align="right" valign="middle"><?php echo(number_format(($presupuesto-$trimTtl),2));?></td>
	
	
	<?php
        /*
         * for($x=0; $x<count($arrfuentes); $x++) { echo('<td width="103" align="right">'. number_format($row[$arrfuentes[$x][0]],2) .'</td>'); $total_fila += $row[$arrfuentes[$x][0]] ; }
         */
        ?>
	
	
	
    <!--td align="right" style="background-color:#DCD1FA;">
    <font <?php if(number_format($total_fila ,2) != number_format($row['total'],2) ) { echo('style="background-color:#FF0;color:#F00;"'); }?>> 
	<?php echo( number_format($total_fila,2));?>
    </font>
    </td-->

					</tr>
  <?php
        $Index ++;
    } // End While
    $rsPresup->free();
} // ENd for

?>
  </tbody>
				<!--tfoot>
    <tr style="color:#333; height:20px;">
      <th align="center" valign="middle">&nbsp;</th>
      <th align="left" valign="middle">&nbsp;</th>
      <th align="center" valign="middle">&nbsp;</th>
      <th align="center" valign="middle">&nbsp;</th>
      <th align="center" valign="middle">&nbsp;</th>
      <th align="right" valign="middle"><?php echo( number_format($sumaTotal,2));?></th>
      <?php
    $total_fila = 0;
    for ($x = 0; $x < count($arrfuentes); $x ++) {
        echo ('<th align="right">' . number_format($arrfuentes[$x][1], 2) . '</th>');
        $total_fila += $arrfuentes[$x][1] ;
		}
	  ?>
      <th align="right" valign="middle"><?php echo( number_format($total_fila,2));?></th>
      </tr>
    </tfoot-->
			</table>

			<br />

			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>