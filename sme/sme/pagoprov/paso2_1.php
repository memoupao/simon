<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauserxml.inc.php"); ?>
<?php

require_once (constant("PATH_CLASS") . "BLPagoProv.class.php");
require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$action = $objFunc->__Request('action');

$objPProv = new BLPagoProv();

if ($action == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<!-- InstanceEndEditable -->
<?php
    
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($objFunc->Title);?></title>
<link href="../../css/template.css" rel="stylesheet" media="all" />
<SCRIPT src="../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></SCRIPT>
<script src="../../jquery.ui-1.5.2/ui/ui.datepicker.js"
	type="text/javascript"></script>
<link href="../../jquery.ui-1.5.2/themes/ui.datepicker.css"
	rel="stylesheet" type="text/css" />

<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<!-- Inicio de Container Page-->
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<!-- InstanceBeginEditable name="TemplateEditDetails" -->

				<!-- InstanceEndEditable -->
				<div id="divContent">
					<!-- InstanceBeginEditable name="Contenidos" -->
 <?php
}
?>

 

<?php if($action==md5("mostrar_datos_validados")) {  ?>
<?php

    $objHC = new HardCode();
    
    $objInst = new BLEjecutor();
    $idCuentaDefault = $objInst->ObtenerCuentaDefault($objHC->codigo_Fondoempleo);
    $rcuenta = $objInst->SeleccionarCuenta($objHC->codigo_Fondoempleo, $idCuentaDefault);
    
    ?>

<div style="display: inline-block; width: 450px; position: relative;">
						<fieldset
							style="text-align: left; background-color: #FFF; font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
							<legend style="color: #003;">
								Datos de la Cuenta de Cargo <strong><?php echo($objHC->Nombre_Fondoempleo);?></strong>
							</legend>
							<table width="100%" cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<td width="104" align="left" valign="middle"><strong>BANCO :</strong></td>
										<td width="634" height="18" align="left" valign="middle"><?php echo($rcuenta['banco']);?></td>
									</tr>
									<tr>
										<td align="left" valign="middle" nowrap="nowrap"><strong>TIPO
												DE CUENTA :</strong></td>
										<td height="20" align="left" valign="middle"><?php echo($rcuenta['tipocuenta']);?></td>
									</tr>
									<tr>
										<td align="left" valign="middle"><strong>NRO DE CUENTA :</strong></td>
										<td height="18" align="left" valign="middle"><?php echo($rcuenta['nrocuenta']);?></td>
									</tr>
									<tr>
										<td align="left" valign="middle"><strong>MONEDA :</strong></td>
										<td height="18" align="left" valign="middle"><?php echo($rcuenta['moneda']);?></td>
									</tr>
									<tr>
										<td align="left" valign="middle"><strong>TEXTO REFERENCIAL :</strong></td>
										<td height="27" align="left" valign="middle"><?php echo($rcuenta['textoref']);?></td>
									</tr>
								</thead>
							</table>

						</fieldset>
					</div>
					<div style="display: inline-block; width: 300px;">
						<fieldset
							style="text-align: left; background-color: #FFF; font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
							<legend style="color: #003;"></legend>
							<table cellpadding="0" cellspacing="0">
								<thead>
									<tr>
										<td width="104" align="left" valign="middle">&nbsp;</td>
										<td width="326" height="16" align="left" valign="middle">&nbsp;</td>
									</tr>
									<tr>
										<td height="54" colspan="2" align="center" valign="middle">
											<div
												style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 60px;"
												title="Generar archivo de Texto"
												onclick="LoadPaso3('<?php echo($idCuentaDefault);?>');">
												<img src="../../img/aplicar.png" width="32" height="32" /><br />
												Generar TXT
											</div>
										</td>
									</tr>
									<tr>
										<td align="left" valign="middle">&nbsp;</td>
										<td height="27" align="left" valign="middle">&nbsp;</td>
									</tr>
								</thead>
							</table>

						</fieldset>
					</div>
					<br /> <br />
					<fieldset style="text-align: left; background-color: #FFF;">
						<legend> Datos Cargados correctamente</legend>


						<div class="TableGrid"
							style="overflow-y: scroll; overflow-x: scroll; border: solid 1px #666; width: 740px; height: 400px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody class="data">
									<tr
										style="background-color: #D8D8D8; border: 1 px solid #000; font-weight: bold;">
										<td style="" valign="middle" align="center">CONCURSO</td>
										<td style="" valign="middle" align="center">CODIGO PROYECTO</td>
										<td style="" valign="middle" align="center">Region</td>
										<td style="" valign="middle" align="center">INICIO DEL
											PROYECTO</td>

										<td style="" valign="middle" align="center">TERMINO DEL
											PROYECTO</td>
										<td style="" valign="middle" align="center">Encargado</td>
										<td style="" valign="middle" align="center">INSTITUCION
											EJECUTORA</td>
										<td style="" valign="middle" align="center">RUC</td>
										<td style="" valign="middle" align="center">MODO DE GIRO</td>
										<td style="" valign="middle" align="center">BANCO</td>

										<td style="" valign="middle" align="center">TIPO DE CUENTA</td>
										<td style="" valign="middle" align="center">NUMERO DE CUENTA</td>
										<td style="" valign="middle" align="center">MONEDA CUENTA</td>
										<td style="" valign="middle" align="center">TEXTO REFERENCIAL
											PLANILLA</td>
										<td style="" valign="middle" align="center">MONTO A TRANSFERIR</td>
									</tr>
								</tbody>
								<tbody id="scrollContent" class="data" bgcolor="#FFFFFF">
         <?php
    $iRs = $objPProv->ListarTempValidado();
    
    while ($row = mysqli_fetch_assoc($iRs)) {
        ?>
          <tr>
										<td style="" valign="middle"><?php echo( $row['concurso']);?></td>
										<td align="center" valign="middle" style=""><?php echo( $row['codproy']);?></td>
										<td style="" valign="middle"><?php echo( $row['region']);?></td>
										<td align="center" valign="middle" style=""><?php echo( $row['inicio']);?></td>
										<td align="center" valign="middle" style=""><?php echo( $row['termino']);?></td>
										<td style="" valign="middle"><?php echo( $row['encargado']);?></td>
										<td style="" valign="middle"><?php echo( $row['ejecutor']);?></td>
										<td style="" valign="middle"><?php echo( $row['ruc']);?></td>
										<td style="" valign="middle"><?php echo( $row['modogiro']);?></td>
										<td style="" valign="middle"><?php echo( $row['banco']);?></td>
										<td style="" valign="middle"><?php echo( $row['tipocuenta']);?></td>
										<td style="" valign="middle"><?php echo( $row['nrocuenta']);?></td>
										<td style="" valign="middle"><?php echo( $row['montranf']);?></td>
										<td style="" valign="middle"><?php echo( $row['textoref']);?></td>
										<td align="right" valign="middle" style=""><?php echo( number_format($row['montotransf'],2));?></td>
									</tr>
          <?php
    }
    ?>
        </tbody>
								<tfoot>
									<tr>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
										<th style="" valign="middle" align="center">&nbsp;</th>
									</tr>
								</tfoot>
							</table>
						</div>

						<div align="left">
							<table width="684" border="0" cellspacing="1" cellpadding="0">
								<tr>
									<td width="473" nowrap="nowrap">&nbsp;</td>
									<td width="44" align="center" nowrap="nowrap"><span
										style="text-align: center; color: navy; font-weight: bold; font-size: 10px;">Cerrar</span></td>
									<td width="48" nowrap="nowrap"><img src="../../img/delete.png"
										alt="" width="16" height="16" style="cursor: pointer;"
										onclick="CancelarAll();" title="Cancelar" /></td>
									<td width="97" height="30" align="right" nowrap="nowrap"><span
										style="text-align: center; color: navy; font-weight: bold; font-size: 10px;">Actualizar</span></td>
									<td width="16" align="center" nowrap="nowrap"><img
										src="../../img/btnRecuperar.gif" alt="" width="15" height="15"
										style="cursor: pointer;"
										onclick="LoadMostrarDatosValidados();" title="Recargar Datos" /><br /></td>
								</tr>
							</table>
						</div>
					</fieldset>


<?php  }  ?>
    
    
    
    
<?php
if ($action == "") {
    ?>
  <!-- InstanceEndEditable -->
				</div>
			</form>
		</div>
		<!-- Fin de Container Page-->
	</div>

</body>
<!-- InstanceEnd -->
</html>

<?php
 }	 
 ?>