<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$idVersion = $objFunc->__POST('idVersion');
$idActiv = $objFunc->__POST('idActiv');
$idAnio = $objFunc->__POST('idAnio');
$idMes = $objFunc->__POST('idMes');

if ($idProy == "" && $idComp == "" && $idAct == "") {
    $idProy = $objFunc->__GET('idProy');
    $idVersion = $objFunc->__GET('idVersion');
    $idActiv = $objFunc->__GET('idActiv');
    $idAnio = $objFunc->__GET('idAnio');
    $idMes = $objFunc->__GET('idMes');
}

$actividad = explode(".", $idActiv);
$idComp = $actividad[0];
$idAct = $actividad[1];

$HardCode = new HardCode();
$IsMA = false;
if ($ObjSession->PerfilID == $HardCode->GP || $ObjSession->PerfilID == $HardCode->RA) {
    $IsMA = true;
}

// $objFunc->Debug(true);

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Actividades</title>
    <style type="text/css">
    <!--
    #Layer1 {
    	position: absolute;
    	left: 613px;
    	top: 0px;
    	width: 134px;
    	height: 55px;
    	z-index: 0;
    	visibility: visible;
    }
    -->
    </style>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery.numeric.js"></script>
		<script src="../../../js/commons.js" type="text/javascript"></script>
		<div id="divTableLista">
			<table width="780" cellpadding="0" cellspacing="0" class="TableEditReg">
				<thead>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $objInf = new BLInformes();
    $iRs = $objInf->ListaSubActividades($idProy, $idActiv, $idAnio, $idMes);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {

            ?>
                    <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="412" align="left" valign="middle"><b>Actividad</b></td>
						<td height="15" colspan="4" align="center" valign="middle" bgcolor="#CCCCCC"><b>Meta Planeada</b></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#CCCCCC"><b>Ejecutado</b></td>
					</tr>
					<tr>
						<td width="412" rowspan="2" align="left" valign="middle"><?php echo($row['t08_cod_comp'].".".$row['t09_cod_act'].".".$row['t09_cod_sub']." ".$row['subactividad']);?>
                            <input name="t09_cod_sub[]" type="hidden" id="t09_cod_sub[]"
							value="<?php echo($row['t09_cod_sub']);?>" /> <br /> <span><b>Unidad Medida</b>: <?php echo( $row['t09_um']);?></span></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><b>Total de Proyecto</b></td>
						<td width="60" align="center" bgcolor="#CCCCCC"><b>Total Año</b></td>
						<td width="58" align="center" bgcolor="#CCCCCC"><b>Acum</b></td>
						<td width="55" align="center" bgcolor="#CCCCCC"><b>Mes</b></td>
						<td width="62" align="center" bgcolor="#CCCCCC"><b>Acum</b></td>
						<td width="63" align="center" bgcolor="#CCCCCC"><b>Mes</b></td>
						<td width="68" align="center" bgcolor="#CCCCCC"><b>Total</b></td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtatotal']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaanio']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtaacum']);?></td>
						<td align="center" nowrap="nowrap"><?php echo( $row['plan_mtames']);?></td>
						<td align="center" nowrap="nowrap"><input class="AM_SAct"
							name="txtSubActAcum[]" type="text" id="txtSubActAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo( $row['ejec_mtaacum']);?>" size="4"
							readonly="readonly" /></td>
						<td align="center" nowrap="nowrap">
         <?php if($IsMA){ ?>
         <input MontoIndSActMes='1' name="txtSubActMes[]" type="hidden"
							id="txtSubActMes[]" value="<?php echo($row['ejec_mtames']);?>" />
							<input readonly="readonly" MontoIndSActMes='1'
							name="txtSubActMes[]" type="text" id="txtSubActMes[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtames']);?>" size="4" />
         <?php }else{?>
         <input MontoIndSActMes='1' name="txtSubActMes[]" type="text"
							id="txtSubActMes[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtames']);?>" size="4"
							onkeyup="TotalAvanceSubActividad('<?php echo($RowIndex);?>');" />
         <?php }?>
       </td>
						<td align="center" nowrap="nowrap"><input class="AM_SAct"
							name="txtSubActTot[]" type="text" id="txtSubActTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejec_mtatotal']);?>" size="4"
							readonly="readonly" /></td>
					</tr>
					<tr style="font-weight: 300; color: navy;">
						<td colspan="8" align="left" nowrap="nowrap">Descripción<br />

         <?php if($IsMA){ ?>
         <input name="txtSubactdes[]" type="hidden" id="txtSubactdes[]"
							value="<?php echo($row['descripcion']);?>" /> <textarea
								class="InformeSubAct" cols="2500" rows="3" readonly="readonly"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?></textarea><br />
         <?php }else{?>
         <textarea class="InformeSubAct" name="txtSubactdes[]"
								cols="2500" rows="3" id="txtSubactdes[]"
								style="padding: 0px; width: 100%;"><?php echo($row['descripcion']);?></textarea><br />
         <?php }?>
         <br /> Logros <br />
         <?php if($IsMA){ ?>
         <input name="txtSubActlog[]" type="hidden" id="txtSubActlog[]"
							value="<?php echo($row['logros']);?>" /> <textarea
								class="InformeSubAct" cols="2500" rows="3" readonly="readonly"
								style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea><br />
         <?php }else{?>
         <textarea class="InformeSubAct" name="txtSubActlog[]"
								cols="2500" rows="3" id="txtSubActlog[]"
								style="padding: 0px; width: 100%;"><?php echo($row['logros']);?></textarea><br />
         <?php }?>
         Dificultades <br />
         <?php if($IsMA){ ?>
         <input name="txtSubActdif[]" type="hidden" id="txtSubActdif[]"
							value="<?php echo($row['dificultades']);?>" /> <textarea
								class="InformeSubAct" cols="2500" rows="3" readonly="readonly"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea><br />
         <?php }else{?>
         <textarea class="InformeSubAct" name="txtSubActdif[]"
								cols="2500" rows="3" id="txtSubActdif[]"
								style="padding: 0px; width: 100%;"><?php echo($row['dificultades']);?></textarea><br />
         <?php }?>
         <div><b>Observaciones del Gestor de Proyectos</b></div>
							<br />
         <?php if($IsMA){ ?>
         <textarea class="InformeSubAct" name="txtObsMonTec[]"
								cols="2500" rows="3" id="txtObsMonTec[]"
								style="padding: 0px; width: 100%;"><?php echo($row['omt']);?></textarea>
							<br />
         <?php }else{?>
            <input name="txtObsMonTec[]" type="hidden" id="txtObsMonTec[]" value="<?php echo($row['omt']);?>" />
            <textarea class="InformeSubAct" cols="2500" rows="3" readonly="readonly" style="padding: 0px; width: 100%; color: red;"><?php echo($row['omt']);?></textarea>
            <br />
         <?php }?>
         </td>
		</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    }     // Fin de Actividades
    else {
        echo ("<b class='nota'>El Producto seleccionado [" . $idActiv . "] no tiene registrado Actividades...<br />Verificar el Plan Operativo</b>");
        exit();
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
			<input type="hidden" name="t09_cod_act" value="<?php echo($idAct);?>" />
			<input name="t09_sub_anio" type="hidden" id="t09_sub_anio"
				value="<?php echo($idAnio);?>" /> <input name="t09_sub_mes"
				type="hidden" id="t09_sub_mes" value="<?php echo($idMes);?>" />

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

		$("input[MontoIndSActMes='1']").numeric().pasteNumeric();
		$('.AM_SAct:input[readonly="readonly"]').css("background-color", "white") ;
		$('.AM_SAct:input[readonly="readonly"]').css("border", "none") ;
	</script>

		</div>
<?php if($idProy=="") { ?>
</form>
</body>
</html>
<?php } ?>