<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLReportes.class.php");
$ls_filter = "";
?>


<?php if($idProy=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Directorio de Instituciones</title>
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
					<th width="18%" align="left">&nbsp;</th>
					<td width="55%" align="left"><?php echo($ls_filter);?></td>
					<th width="7%" align="left" nowrap="nowrap">&nbsp;</th>
					<td width="20%" align="left">&nbsp;</td>
				</tr>
			</table>

			<table width="99%" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td width="4%" height="33" align="center" valign="middle">RUC</td>
						<td width="8%" align="center" valign="middle">Sigla de la
							Institución</td>
						<td width="8%" align="center" valign="middle">Tipo de Institución</td>
						<td width="17%" align="center" valign="middle">Nombre de la
							Institución</td>
						<td width="17%" align="center" valign="middle">Fecha de Fundación</td>
						<td width="17%" align="center" valign="middle">Presupuesto Anual</td>
						<td width="17%" align="center" valign="middle">Tipo de Relación
							con FE</td>
						<td width="17%" align="center" valign="middle">Sector Productivo</td>
						<td width="17%" align="center" valign="middle">Departamento</td>
						<td width="17%" align="center" valign="middle">Provincia</td>
						<td width="17%" align="center" valign="middle">Distrito</td>
						<td width="17%" align="center" valign="middle">Ciudad</td>
						<td width="17%" align="center" valign="middle">Dirección</td>
						<td width="17%" align="center" valign="middle">Teléfono</td>
						<td width="17%" align="center" valign="middle">Fax</td>
						<td width="17%" align="center" valign="middle">RPM</td>
						<td width="17%" align="center" valign="middle">RPC</td>
						<td width="17%" align="center" valign="middle">Nextel</td>
						<td width="17%" align="center" valign="middle">Mail</td>
						<td width="17%" align="center" valign="middle">Web</td>
						<td width="16%" align="center" valign="middle">Responsables de la
							institución</td>
						<td width="17%" align="center" valign="middle">Nro. Proyectos</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $objRep = new BLReportes();
    
    $rsInst = $objRep->RepInstituciones();
    
    $Index = 1;
    while ($row = mysqli_fetch_assoc($rsInst)) {
        ?>
    <tr style="font-size: 11px;">
						<td align="center" valign="middle"><?php echo($row['t01_ruc_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_sig_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['tipo_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_nom_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['fec_fun']);?></td>
						<td align="left" valign="middle"><?php echo($row['pres_anio']);?></td>
						<td align="left" valign="middle"><?php echo($row['tipo_rel_fe']);?></td>
						<td align="left" valign="middle"><?php echo($row['t02_sect_prod']);?></td>
						<td align="left" valign="middle"><?php echo($row['dpto']);?></td>
						<td align="left" valign="middle"><?php echo($row['prov']);?></td>
						<td align="left" valign="middle"><?php echo($row['dist']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_ciud_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_dire_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_fono_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_fax_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_rpm_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_rpc_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_next_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_mail_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_web_inst']);?></td>


      <?php
        $rep = "<b>" . $row['represen'];
        $rep = str_replace("\n", "<br><b>", $rep);
        $rep = str_replace(":", ":</b>", $rep);
        ?>
      <td align="left" valign="top"><?php echo($rep);?></td>
						<td align="center" valign="center"><?php echo $row['nro_proy'];?></td>
					</tr>
    <?php
        $Index ++;
    } // End While
    $rsInst->free();
    ?>
  </tbody>
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