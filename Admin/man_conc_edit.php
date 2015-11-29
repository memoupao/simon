<?php include_once("../includes/constantes.inc.php"); ?>

<?php include_once("../includes/validauseradm.inc.php"); ?>

<?php

require_once (constant('PATH_CLASS') . "BLMantenimiento.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");

$objMante = new BLMantenimiento();
$HC = new HardCode();

$view = $objFunc->__GET('mode');

$row = 0;

if ($view == md5("ajax_edit")) 

{
    
    $objFunc->SetSubTitle("Editando Concursos");
    
    $id = $objFunc->__GET('id');
    
    $row = $objMante->ConcursoSeleccionar($id);
} 

else {
    
    $row = 0;
    
    $objFunc->SetSubTitle("Nuevo Concurso");
}

?>



<?php if($view=='') { ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tplAjaxForm.dwt.php" codeOutsideHTMLIsLocked="false" --><head><!-- InstanceBeginEditable name="doctitle" --><!-- InstanceEndEditable -->

<?php
    
$objFunc->verifyAjax();
    
    if (! $objFunc->Ajax) {
        ?>

<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" /><meta name="description" content="<?php echo($objFunc->MetaTags); ?>" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title><?php echo($objFunc->Title);?></title><link href="../css/template.css" rel="stylesheet" media="all" />
<link href="../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" /><SCRIPT src="../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></SCRIPT><script src="../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<?php } ?>

</head><body class="oneColElsCtrHdr">	<!-- Inicio de Container Page-->	<div id="container">		<div id="mainContent">			<form id="FormData" method="post"				enctype="application/x-www-form-urlencoded"				action="<?php echo($_SERVER['PHP_SELF']);?>">				<!-- InstanceBeginEditable name="TemplateEditDetails" -->				<!-- InstanceEndEditable -->				<div id="divContent">					<!-- InstanceBeginEditable name="Contenidos" -->

 <?php } ?>

	<div id="EditForm" style="width: 700px; border: solid 1px #D3D3D3;">						<br />						<div id="toolbar" style="height: 4px;" class="BackColor">							<table width="100%" border="0" cellpadding="0" cellspacing="0">								<tr>									<td width="9%"><button class="Button"											onclick="btnGuardar_Clic(); return false;" value="Guardar">Guardar										</button></td>									<td width="9%"><button class="Button"											onclick="btnCancelar_Clic(); return false;" value="Cancelar">											Cancelar</button></td>									<td width="31%">&nbsp;</td>									<td width="2%">&nbsp;</td>									<td width="2%">&nbsp;</td>									<td width="2%">&nbsp;</td>									<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>								</tr>							</table>						</div>						<table width="700" border="0" cellpadding="0" cellspacing="0" class="TableEditReg">							<tr>								<td width="542">									<fieldset>										<legend>Datos Generales</legend>										<table width="100%" border="0" cellspacing="1" cellpadding="0">											<tr>												<td width="18%">Número de Concurso</td>												<td width="39%"><input name="txtnumero" type="text"													disabled="disabled" id="txtnumero"													value="<?php echo($row['num_conc']); ?>" size="5"													style="text-align: center;" /> <input name="txtnumero"													type="hidden" id="txtnumero"													value="<?php echo($row['num_conc']); ?>" /></td>												<td width="9%">&nbsp;</td>												<td width="34%">&nbsp;</td>											</tr>											<tr>												<td nowrap="nowrap">Año del concurso</td>												<td><input name="txtanio" type="text" id="txtanio"													value="<?php echo( $row['anio_conc']); ?>" size="50"													maxlength="4" required /></td>												<td>&nbsp;</td>												<td>&nbsp;</td>											</tr>											<tr>												<td nowrap="nowrap">Nombre del concurso</td>												<td><input name="txtnombre" type="text" id="txtnombre"													value="<?php echo( $row['nom_conc']); ?>" size="50"													maxlength="50" required /></td>												<td>&nbsp;</td>												<td>&nbsp;</td>											</tr>											<tr>												<td>Abreviatura</td>												<td><input name="txtabreviado" type="text" id="txtabreviado"													value="<?php echo($row['abr_conc']); ?>" size="50"													maxlength="15" required /></td>												<td>&nbsp;</td>												<td>&nbsp;</td>											</tr>											<tr>												<td>Comentario</td>												<td><textarea name="txtcomentario" type="text"														id="txtcomentario" rows="2" cols="90"><?php echo($row['coment_conc']); ?></textarea></td>												<td>&nbsp;</td>												<td>&nbsp;</td>											</tr>											<tr>												<td>&nbsp;</td>												<td>&nbsp;</td>												<td align="right">&nbsp;</td>												<td>&nbsp;</td>											</tr>										</table>									</fieldset>								</td>							</tr>							<tr>								<td>&nbsp;</td>							</tr>
							<?php 
							// -------------------------------------------------->
							// DA 2.0 [07-11-2013 10:48]
							// Nuevos campos de tasas por lineas del concurso.
							// En caso de que exista un concurso ya registrado y mas adelante se
							// se registre nuevas lineas, estas tendran que ser adicionados manualmente
							// ya que para el registro se obtendran con las ultimas lineas registradas y
							// si se edita el concurso se obtendrasn con las lineas ya registradas mas no 
							// las nuevas lineas (si en caso de haberlas).
							
							?>
							<tr>
								<td>
								
								<fieldset>
    								<legend>Tasas del Concurso</legend>
    								<div class="TableGrid">
    								<table width="100%" border="0" cellspacing="1" cellpadding="0" class='TableGrid'>
    								    <thead>
    								    <tr>
    								        <th>Lineas \ Tasas</th>
    								        <th>(%) Gastos Funcionales</th>
    								        <th>(%) Linea Base</th>
    								        <th>(%) Imprevistos</th>
    								        <th>(%) Gastos Supervision del Proyecto</th>
    								    </tr>
    								    </thead>
    								    <tbody>
    								    <?php
    								    
    								    $rs = $objMante->getListTasasPorConcurso($id);
    								    $posLinea = 0;
    								    while ($row = mysql_fetch_assoc($rs)) {            
                                            $posLinea++;
                                             
                                        ?>
                                        <tr>
    								        <th>
    								            <?php echo $row['abrev'];?>
    								            <small><?php echo $row['nombre'];?></small>
    								            <input type="hidden" name="linea_<?php echo $posLinea;?>" value="<?php echo $row['codigo'];?>">
    								        </th>
    								        
    								        <td><input class="inputSmall" type="text" name="tfun_<?php echo $posLinea;?>" value="<?php echo  (isset($row['porc_gast_func']) ? $row['porc_gast_func'] : $HC->Porcent_Gast_Func);;?>"/></td>
    								        <td><input class="inputSmall" type="text" name="tlib_<?php echo $posLinea;?>" value="<?php echo (isset($row['porc_linea_base']) ? $row['porc_linea_base'] : $HC->Porcent_Linea_Base); ?>"/></td>
    								        <td><input class="inputSmall" type="text" name="timp_<?php echo $posLinea;?>" value="<?php echo (isset($row['porc_imprev']) ? $row['porc_imprev'] : $HC->Porcent_Imprevistos);?>"/></td>
    								        <td><input class="inputSmall" type="text" name="tgsp_<?php echo $posLinea;?>" value="<?php echo (isset($row['porc_gast_superv_proy']) ? $row['porc_gast_superv_proy'] : $HC->porcentGastSupervProy);?>"/></td>
    								    </tr>                                   
                                        <?php } ?>
                                        </tbody>    								    
    								</table>
    								</div>
    								    <input type="hidden" name="totLineas" value="<?php echo $posLinea;?>"  />
							     </fieldset>
								
								</td>
							</tr>
							<?php // --------------------------------------------------< ?>						</table>					</div><script language="javascript">

  function btnGuardar_Clic()

	{

	 if( $('#txtanio').val()=="" ) {alert("Ingrese el año del concurso"); $('#txtanio').focus(); return false;}	
	 if( $('#txtnombre').val()=="" ) {alert("Ingrese el nombre del concurso"); $('#txtnombre').focus(); return false;}	

	 if( $('#txtabreviado').val()=="" ) {alert("Ingrese Nombre Abreviado del Concurso"); $('#txtabreviado').focus(); return false;}	

		// -------------------------------------------------->
	    // DA 2.0 [04-11-2013 12:54]
	    // Validacion de longitud minima en formulario de edicion y nuevo 	
	    if (typeof(isValidFormsMantenimiento) == "function") {		    
		    	if( ! isValidFormsMantenimiento() ) return false;
		}
		// --------------------------------------------------<
			 

	 var BodyForm = $("#FormData").serialize() ;

	 var sURL = "man_conc_process.php?action=<?php echo($view);?>"

	 var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

	

	return false;

	

	}

</script>

  



 <?php if($view=='') { ?>

  <!-- InstanceEndEditable -->				</div>			</form>		</div>		<!-- Fin de Container Page-->	</div></body><!-- InstanceEnd --></html><?php } ?>



