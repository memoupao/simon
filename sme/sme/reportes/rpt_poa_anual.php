<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require (constant("PATH_CLASS") . "BLReportes.class.php");
require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");

$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$t02_periodo = $objFunc->__Request('t02_periodo');
$t02_estado = $objFunc->__Request('t02_estado');

$objPOA = new BLPOA();

$objML = new BLMarcoLogico();
$ML = $objML->GetML($idProy, $idVersion);

?>

<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Cronograma Anual</title>
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

			<table width="850" border="0" align="center" cellpadding="0"
				cellspacing="1" class="TableGrid">
				<tr>
					<th align="left">&nbsp;</th>
					<td colspan="3">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th width="21%" height="18" align="left">CODIGO DEL PROYECTO</th>
					<td colspan="3" align="left"><?php echo($ML['t02_cod_proy']);?></td>
					<th width="2%" align="left" nowrap="nowrap">&nbsp;</th>
					<th width="7%" align="left" nowrap="nowrap">INICIO</th>
					<td width="13%" align="left"><?php echo($ML['t02_fch_ini']);?></td>
				</tr>
				<tr>
					<th height="18" align="left" nowrap="nowrap">DESCRIPCION DEL
						PROYECTO</th>
					<td colspan="3" align="left"><?php echo($ML['t02_nom_proy']);?></td>
					<th align="left" nowrap="nowrap">&nbsp;</th>
					<th align="left" nowrap="nowrap">TERMINO</th>
					<td align="left"><?php echo($ML['t02_fch_ter']);?></td>
				</tr>
				<tr>
					<th height="18" align="left" valign="top">PERIODO</th>
					<td width="24%" align="left" valign="top"><?php echo($t02_periodo);?></td>
					<td width="8%" align="left" valign="top"><strong>ESTADO</strong></td>
					<td width="25%" align="left" valign="top"><?php echo($t02_estado);?></td>
					<td align="left" valign="top">&nbsp;</td>
					<td align="left" valign="top"><strong>AÑO</strong></td>
					<td align="left" valign="top"><?php echo($idAnio);?></td>
				</tr>
				<tr>
					<th align="left">&nbsp;</th>
					<td colspan="3">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>

			<table width="850" cellpadding="0" cellspacing="0">

				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td height="40" colspan="4" align="left" valign="middle"
						bgcolor="#FFFFFF">&nbsp;Planificacion de Metas - SubActividades</td>
					<td height="40" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" align="center" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" colspan="3" align="center" valign="middle"
						bgcolor="#FFFFFF">&nbsp;</td>
				</tr>

				<tr style="background-color: #eeeeee;">
					<td style="border: solid 1px #6B6B6B;" height="15" align="left"
						valign="middle">&nbsp;</td>
					<td style="border: solid 1px #6B6B6B; text-align: center"
						width="221" height="15" align="left" valign="middle">SubActividades</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Unidad Mediada</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Fisica Inicial</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Proyectada del Año Anterior</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Ejecutada Año Anterior</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Total por Ejecutar</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Total  del Año <?php echo($idAnio);?></td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Proyectada Años Restantes</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Meta Reprogram</td>
					<td style="border: solid 1px #6B6B6B;" height="15" align="center"
						valign="middle">Variac</td>
				</tr>
   
 
  <?php
$objML = new BLMarcoLogico();
$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
$nrAct = mysql_num_rows($rsComp);

while ($rsCom = mysql_fetch_assoc($rsComp)) {
    $idComp = $rsCom['t08_cod_comp'];
    ?>


    <tr style="background-color: #D9DAE8;">
					<td height="30" colspan="11" align="left" valign="middle"
						style="border-bottom: solid 1px #640065;">
						<div style="display: inline-block;">
							<strong>Componente&nbsp;</strong>
						</div>
						<div style="display: inline-block;">
          <?php
    echo $rsCom['descripcion'];
    ?>
        </div>
					</td>
				</tr>

				<tbody class="data" bgcolor="#FFFFFF">   

    <?php
    $objML = new BLMarcoLogico();
    $objPOA = new BLPOA();
    
    $rs = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
    $rows = mysql_num_rows($rs);
    
    while ($rowAct = mysql_fetch_assoc($rs)) {
        
        ?>
    
   		    <tr style="border: solid 1px #CCC; background-color: #DAF3DD;">
						<td width="31" align="left" valign="middle">
              <?php echo($idComp.".".$rowAct['t09_cod_act']);?></td>
						<td height="15" colspan="10" align="left" valign="middle"><strong><?php echo($rowAct['t09_act']);?>
                <input name="t08_cod_comp_ind[]2" type="hidden"
								class="ind_comp" id="t08_cod_comp_ind[]2"
								value="<?php echo($rowAct['t09_cod_act']);?>" /> </strong></td>
					</tr>
			<?php
        $iRs = $objPOA->POA_ListadoSubActividades($idProy, $idVersion, $idComp, $rowAct['t09_cod_act'], $idAnio);
        $RowIndex = 0;
        
        while ($row = mysqli_fetch_assoc($iRs)) {
            
            ?>
                    <tr
						<?php if ($row["act_add"]=='1'){echo ("style='color:green; font-weight:bold'");}?>>
						<td width="31" align="left" valign="middle" bgcolor="#FFFFFF"><?php echo($row['codigo']);?></td>
						<td align="left" valign="middle"><?php echo( $row['descripcion']);?>
                      <input name="t08_cod_comp_ind[]" type="hidden"
							class="ind_comp" id="t08_cod_comp_ind[]"
							value="<?php echo($row['codigo']);?>" /></td>
						<td align="center" valign="middle"><?php echo( $row['um']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo(number_format($row['mfi']));?></td>
						<td width="68" align="center" valign="middle"><?php echo(number_format($row['mpaa']));?></td>
						<td width="60" align="center" valign="middle"><?php echo(number_format($row['meaa']));?></td>
						<td width="55" align="center" valign="middle"><?php echo(number_format($row['mtpe']));?></td>
						<td width="46" align="center" valign="middle" bgcolor="#F2F7B9">                           
                          <?php echo(number_format($row['meta_poa']));?>                       
                      </td>
						<td width="68" align="center" valign="middle"><?php echo(number_format($row['mpar']));?></td>
						<td width="63" align="center" valign="middle"><?php echo(number_format($row['mprog']));?></td>
						<td width="42" align="center" valign="middle"><?php echo(number_format($row['mvar']));?></td>
					</tr>
	   <?php
        }
        $iRs->free();
    }
    ?>
  </tbody>
  
  <?php
}
?>
  <tfoot>
				</tfoot>
			</table>



			<br />
			<p>
				<script language="JavaScript" type="text/javascript">
  </script>
			</p>


			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>