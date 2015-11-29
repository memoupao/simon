<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");
require_once (constant('PATH_CLASS') . "BLMantenimiento.class.php");
require_once (constant('PATH_CLASS') . "HardCode.class.php");

$objHC = new HardCode();
if($ObjSession->PerfilID==$objHC->SP || $ObjSession->PerfilID==$objHC->FE ) ;
else {
	exit;
}

$Function = new Functions();
$proc = $Function->__GET('proc');
$view = $Function->__GET('action');

$dep = $Function->__GET('dep');
$prov = $Function->__GET('prov');
$dist = $Function->__GET('dist');

if ($proc == "save") {
    // --> Hacemos el Insert o Update
    $ReturnPage = false;
    if ($view == md5("new")) {
        $pdep = $Function->__POST('pcbodpto');
        $pprov = $Function->__POST('pcboprov');
        $pdist = $Function->__POST('pcbodist');
        $pcaserio = $Function->__POST('caserio');
        
        $objMan = new BLMantenimiento();
        $id = 0;
        
        $ReturnMant = $objMan->NuevoCaserio($pdep, $pprov, $pdist, $pcaserio, $id);
        $objMan = NULL;
    }
    
    if ($ReturnMant) {
        $pdep = $Function->__POST('pcbodpto');
        $pprov = $Function->__POST('pcboprov');
        $pdist = $Function->__POST('pcbodist');
        
        $depar = $Function->__POST('dep');
        $provin = $Function->__POST('prov');
        $distri = $Function->__POST('dist');
        
        $script = "alert('Se grabó correctamente el Registro'); \n";
        $script .= "parent.btnCancelar_popup(); \n";
        if ($depar == $pdep && $provin == $pprov && $distri == $pdist) {
            $script .= "parent.LoadCase('" . $id . "'); \n";
        }
        $Function->Javascript($script);
        exit(1);
    }
}

?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
 
<?php

if ($view == md5("new")) {
    $objFunc->SetSubTitle("Padron de Beneficiarios  -  Nuevo Centro Poblado");
} else {
    $objFunc->SetSubTitle("Padron de Beneficiarios -  Editar Centro Poblado");
}

?>

<title>Padron de Beneficiarios</title>

<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->



	<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript"
		src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>

	<form id="frmMain" name="frmMain" method="post" action="#">
		<div id="toolbar" style="height: 4px;" class="Subtitle">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="47%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
					<td width="8%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="2%">&nbsp;</td>
					<td width="9%"><button class="Button"
							onclick="return Guardar(); return false;" value="Guardar">Guardar
						</button></td>
					<td width="9%"><button class="Button" value="Cancelar"
							onclick="return Cancelar(); return false;">Cancelar</button></td>
				</tr>
			</table>
		</div>
		<br />

		<table width="90%" align="center" class="TableEditReg">
			<tr valign="baseline">
				<td width="150" align="left" nowrap>Departamento</td>
				<td width="360" align="left"><select name="pcbodpto" id="pcbodpto"
					style="width: 220px;" onchange="pLoadProv();">
						<option value="" selected="selected"></option>
        <?php
        $objTablas = new BLTablasAux();
        $rsDpto = $objTablas->ListaDepartamentos();
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $dep);
        ?>
        </select></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="top">Provincia</td>
				<td align="left"><select name="pcboprov" id="pcboprov"
					style="width: 220px;" onchange="pLoadDist();">
						<option value="" selected="selected"></option>
          <?php
        $rsDpto = $objTablas->ListaProvincias($dep);
        $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $prov);
        ?>
        </select></td>
			</tr>
			<tr valign="baseline">
				<td nowrap align="left" valign="top">Distrito</td>
				<td align="left"><select name="pcbodist" id="pcbodist"
					style="width: 220px;">
						<option value="" selected="selected"></option>
            <?php
            $rsDpto = $objTablas->ListaDistritos($dep, $prov);
            $objFunc->llenarComboI($rsDpto, 'codigo', 'descripcion', $dist);
            ?>
        </select></td>
			</tr>
			<tr valign="baseline">
				<td align="left" valign="top">Nombre de Centro Poblado</td>
				<td align="left"><input name="caserio" type="text" id="caserio"
					value="" size="50" /></td>
			</tr>
			<tr valign="baseline">
			</tr>
		</table>

		<input name="dep" type="hidden" id="dep" value="<?php echo($dep);?>" />
		<input name="prov" type="hidden" id="prov"
			value="<?php echo($prov);?>" /> <input name="dist" type="hidden"
			id="dist" value="<?php echo($dist);?>" />
	</form>

	<script language="javascript" type="text/javascript">

 						   
	  function Cancelar()
	  {
		 parent.btnCancelar_popup();
		 return false;
	  }
	  
	  function Guardar()
	  {
		 
		  
	     <?php $ObjSession->AuthorizedPage(); ?>	
		 
		 if( $('#pcbodpto').val()=="" ) {alert("Seleccione el Departamento"); return false;}
	     if( $('#pcboprov').val()=="" ) {alert("Seleccione la Provincia"); return false;}
	     if( $('#pcbodist').val()=="" ) {alert("Seleccione el Distrito"); return false;}
	     if( $('#caserio').val()=="" ) {alert("Ingrese el Nombre del Centro Poblado"); return false;}
	  	 
		 var formulario = document.getElementById("frmMain") ;
 		 
		 formulario.action = "bene_cacerio_nuevo.php?proc=save&action=<?php echo($view);?>"; 
		 return true ;
	  }
	      
function pLoadProv()
	{	
	 	var BodyForm = "dpto=" + $('#pcbodpto').val();	 
		var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
		$('#pcboprov').html('<option> Cargando ... </option>');
		$('#pcbodist').html('');
		var req = Spry.Utils.loadURL("POST", sURL, true, pProvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, '': '' });
	}
	function pProvSuccessCallback(req)
	{
		var respuesta = req.xhRequest.responseText;
		$('#pcboprov').html(respuesta);
		$('#pcboprov').focus();
	}
	function pLoadDist()
	{ 
		var BodyForm = "dpto=" + $('#pcbodpto').val() + "&prov=" + $('#pcboprov').val() ;
		var sURL = "amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
		$('#pcbodist').html('<option> Cargando ... </option>');
		var req = Spry.Utils.loadURL("POST", sURL, true, pDistSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, '': '' });
	}
	function pDistSuccessCallback(req)
	{
		var respuesta = req.xhRequest.responseText;
		$('#pcbodist').html(respuesta);
		$('#pcbodist').focus();
	}

	

 
   </script>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
