<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLReportes.class.php");
$like = $objFunc->__Request('like');
$inst = $objFunc->__Request('institucion');
$rela = $objFunc->__Request('relacion');
$reg = $objFunc->__Request('region');
$sec = $objFunc->__Request('sector');

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
						<td width="17%" align="center" valign="middle">Datos Generales de
							la Institución</td>
						<td width="19%" align="center" valign="middle">Ubicación de la
							Institución</td>
						<td width="13%" align="center" valign="middle">Teléfonos de
							Oficina</td>
						<td width="18%" align="center" valign="middle">Mail y Web
							Institucional</td>
						<td width="16%" align="center" valign="middle">Responsables de la
							institución</td>
						<td width="5%" align="center" valign="middle">N&deg; Proyectos</td>
					</tr>
				</thead>

				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $objRep = new BLReportes();
    if ($like) {
        $rsInst = $objRep->RepInstitucionesFiltro($like);
    } else 
        if ($inst) {
            $rsInst = $objRep->RepInstitucionesFilterPer($inst, $rela, $reg, $sec);
        } else {
            $rsInst = $objRep->RepInstituciones();
        }
    $Index = 1;
    while ($row = mysqli_fetch_assoc($rsInst)) {
        ?>
    <tr style="font-size: 11px;">
						<td align="center" valign="middle"><?php echo($row['t01_ruc_inst']);?></td>
						<td align="left" valign="middle"><?php echo($row['t01_sig_inst']);?></td>
						<td align="left" valign="top"><strong>Tipo de Ins</strong>t: &nbsp;<?php echo($row['tipo_inst']);?><br>
							<strong>Nombre </strong><strong>de Inst</strong>:<?php echo($row['t01_nom_inst']);?> &nbsp;<br>
							<strong>Fecha de Fundación</strong>: <?php echo($row['fec_fun']);?>&nbsp;<br>
							<strong>Presupuesto Anual</strong>: &nbsp;<?php echo($row['pres_anio']);?> <br />
							<strong>Tipo Relación con FE</strong>:&nbsp;<?php echo($row['tipo_rel_fe']);?><br />
							<strong>Sector Productivo</strong>:&nbsp;<?php echo($row['t02_sect_prod']);?>
        
      </td>
						<td align="left" valign="top"><strong>Departamento</strong>: &nbsp;<?php echo($row['dpto']);?><br>
							<strong>Provincia</strong>: &nbsp;<?php echo($row['prov']);?><br>
							<strong>Distrito</strong>: &nbsp;<?php echo($row['dist']);?><br>
							<strong>Ciudad</strong>: &nbsp;<?php echo($row['t01_ciud_inst']);?><br>
							<strong>Direccion</strong>: &nbsp;<?php echo($row['t01_dire_inst']);?></td>
						<td align="left" valign="top"><strong>Teléfonos</strong>:&nbsp;<?php echo($row['t01_fono_inst']);?>
        <?php if($row['t01_fon2_inst']){echo(' - ');}; echo($row['t01_fon2_inst']);?>
        <br> <strong>Fax</strong>: &nbsp;<?php echo($row['t01_fax_inst']);?><br>
							<strong>RPM</strong>: &nbsp;<?php echo($row['t01_rpm_inst']);?><br>
							<strong>RPC</strong>: &nbsp;<?php echo($row['t01_rpc_inst']);?><br>
							<strong>NEXTEL</strong>: &nbsp;<?php echo($row['t01_next_inst']);?></td>
						<td align="left" valign="top"><strong>Mail</strong>: &nbsp;<?php echo($row['t01_mail_inst']);?><br>
							<strong>Web</strong>: &nbsp;<?php echo($row['t01_web_inst']);?>
      </td>
      <?php
        $rep = "<b>" . $row['represen'];
        $rep = str_replace("\n", "<br><b>", $rep);
        $rep = str_replace(":", ":</b>", $rep);
        ?>
      <td align="left" valign="top"><?php echo($rep);?></td>
						<td align="left" valign="top"><strong title="En Ejecución">Ejec:</strong> <?php echo($row['nro_proy_eje']);?> <br />
							<strong title="Monitoreados">Moni:</strong> <?php echo($row['nro_proy_mon']);?>
      </td>
					</tr>
    <?php
        $Index ++;
    } // End While
    $rsInst->free();
    ?>
  </tbody>
				<tfoot>
					<tr>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td align="left" valign="middle">&nbsp;</td>
						<td colspan="4" align="left" valign="middle">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
			<!--Excel Personalizado-->
			<div id="xlsCustom" style="display: none;">
				<div id="container" class="TableGrid">
					<table width="99%" align="center" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<td width="4%" height="33" align="center" valign="middle"
									rowspan="2">RUC</td>
								<td width="8%" align="center" valign="middle" rowspan="2">Sigla
									de la Institución</td>
								<td width="17%" align="center" valign="middle" colspan="6">Datos
									Generales de la Institución</td>
								<td width="19%" align="center" valign="middle" colspan="5">Ubicación
									de la Institución</td>
								<td width="13%" align="center" valign="middle" colspan="5">Teléfonos
									de Oficina</td>
								<td width="18%" align="center" valign="middle" colspan="2">Mail
									y Web Institucional</td>
								<td width="16%" align="center" valign="middle" rowspan="2">Responsables
									de la institución</td>
								<td width="5%" align="center" valign="middle" colspan="2">N&deg;
									Proyectos</td>
							</tr>
							<tr>
								<td align="center" valign="middle">Tipo de Institución</td>
								<td align="center" valign="middle">Nombre de Institución</td>
								<td align="center" valign="middle">Fecha de Fundación</td>
								<td align="center" valign="middle">Presupuesto Anual</td>
								<td align="center" valign="middle">Tipo Relación con FE</td>
								<td align="center" valign="middle">Sector Productivo</td>
								<td align="center" valign="middle">Departamento</td>
								<td align="center" valign="middle">Provincia</td>
								<td align="center" valign="middle">Distrito</td>
								<td align="center" valign="middle">Ciudad</td>
								<td align="center" valign="middle">Dirección</td>
								<td align="center" valign="middle">Teléfonos</td>
								<td align="center" valign="middle">Fax</td>
								<td align="center" valign="middle">RPM</td>
								<td align="center" valign="middle">RPC</td>
								<td align="center" valign="middle">NEXTEL</td>
								<td align="center" valign="middle">Mail</td>
								<td align="center" valign="middle">Web</td>
								<td align="center" valign="middle">Ejec.</td>
								<td align="center" valign="middle">Moni.</td>
							</tr>
						</thead>

						<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $objRep = new BLReportes();
    if ($like) {
        $rsInst = $objRep->RepInstitucionesFiltro($like);
    } else 
        if ($inst) {
            $rsInst = $objRep->RepInstitucionesFilterPer($inst, $rela, $reg, $sec);
        } else {
            $rsInst = $objRep->RepInstituciones();
        }
    
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
								<td align="left" valign="middle"><?php echo($row['t01_fono_inst']);?>
        <?php if($row['t01_fon2_inst']){echo(' - ');}; echo($row['t01_fon2_inst']);?></td>
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
								<td align="left" valign="top"><?php echo($row['nro_proy_eje']);?></td>
								<td align="left" valign="top"> <?php echo($row['nro_proy_mon']);?></td>

							</tr>
    <?php
        $Index ++;
    } // End While
    $rsInst->free();
    ?>
  </tbody>
						<tfoot>
							<tr>
								<td align="left" valign="middle">&nbsp;</td>
								<td align="left" valign="middle">&nbsp;</td>
								<td align="left" valign="middle">&nbsp;</td>
								<td align="left" valign="middle">&nbsp;</td>
								<td colspan="4" align="left" valign="middle">&nbsp;</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<br />
			<!-- InstanceEndEditable -->
		</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>