<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>
<?php
require(constant("PATH_CLASS")."BLReportes.class.php");
require_once (constant("PATH_CLASS")."BLMarcoLogico.class.php"); 
require_once (constant("PATH_CLASS")."BLPOA.class.php"); 

$idProy = $objFunc->__Request('idProy'); //$_POST['idProy'];
$idVersion = $objFunc->__Request('idVersion'); //$_POST['idVersion'];

/*$idProy = $_GET['idProy'];
$idVersion = $_GET['idVersion'];*/

$objML = new BLMarcoLogico();
$objPOA = new BLPOA();
$ML = $objML->GetML($idProy, $idVersion);

?>


<?php if($objFunc->__QueryString()=="") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempReports.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Marco Logico</title>
<!-- InstanceEndEditable -->
<script language="javascript" type="text/javascript" src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<link href="../../css/reportes.css" rel="stylesheet" type="text/css" media="all" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
<form id="frmMain" name="frmMain" method="post" action="#">
<?php } ?>
<div id="divBodyAjax" class="TableGrid">
<!-- InstanceBeginEditable name="BodyAjax" -->
  <table width="99%" border="0" align="center" cellpadding="0" cellspacing="1" class="TableGrid">
    <tr>
      <th width="18%" align="left">CODIGO DEL PROYECTO</th>
      <td width="55%" align="left"><?php echo($ML['t02_cod_proy']);?></td>
      <th width="7%" align="left" nowrap="nowrap">INICIO</th>
      <td width="20%" align="left"><?php echo($ML['t02_fch_ini']);?></td>
    </tr>
    <tr>
      <th align="left" nowrap="nowrap">DESCRIPCION DEL PROYECTO</th>
      <td align="left"><?php echo($ML['t02_nom_proy']);?></td>
      <th align="left" nowrap="nowrap">TERMINO</th>
      <td align="left"><?php echo($ML['t02_fch_ter']);?></td>
    </tr>
    <tr>
      <th align="left">&nbsp;</th>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="99%" align="center" cellpadding="0" cellspacing="0"  >
  <thead>
      <tr style="font-size:13px; font-weight:bold;">
      <td width="21%" valign="top"><p align="center">Resumen Narrativo de Objetivos</p></td>
      <td width="27%" valign="top"><p align="center">Indicadores Verificables Objetivamente (IVO) </p></td>
      <td width="10%" valign="top"><p align="center">Metas Globales</p></td>
      <td width="22%" valign="top"><p align="center">Medios de Verificacion</p></td>
      <td width="20%" valign="top"><p align="center">Supuestos</p></td>
    </tr>
  </thead>
  
  <tbody class="data" bgcolor="#FFFFFF">
    <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >FINALIDAD</td>
    </tr>
    <?php 
	
	/* --------------------------------------- Finalidad ----------------------------------------------- */
	$rsIndFin = $objML->ListadoIndicadoresOD($idProy, $idVersion);
	$rsSupFin = $objML->ListadoSupuestosOD($idProy, $idVersion);
	
	$NumRows = mysql_num_rows($rsIndFin);
	$rowInd = mysql_fetch_assoc($rsIndFin) ; 
	
	$Sup =  $objFunc->resultToString($rsSupFin,array('t06_cod_fin_sup','t06_sup'));
	
	EscribirFila($ML['t02_fin'],  $rowInd['t06_cod_fin_ind'].".- ".$rowInd['t06_ind'], $rowInd['t06_um'], $rowInd['t06_mta'], $rowInd['t06_fv'], $Sup,  true, $NumRows);
	
	while($rowInd = mysql_fetch_assoc($rsIndFin))  
	{
		EscribirFila($ML['t02_fin'],  $rowInd['t06_cod_fin_ind'].".- ".$rowInd['t06_ind'], $rowInd['t06_um'], $rowInd['t06_mta'], $rowInd['t06_fv'], $Sup,  false, $NumRows);	
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
     <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >PROPOSITO</td>
     </tr>
    <?php
	/* --------------------------------------- Proposito ----------------------------------------------- */
	$rsIndProp = $objML->ListadoIndicadoresOG($idProy, $idVersion);
	$rsSupProp = $objML->ListadoSupuestosOG($idProy, $idVersion);
	
	$NumRows = mysql_num_rows($rsIndProp);
	$rowInd = mysql_fetch_assoc($rsIndProp) ; 
	
	$Sup =  $objFunc->resultToString($rsSupProp,array('t07_cod_prop_sup','t07_sup'));
	
	EscribirFila($ML['t02_pro'], $rowInd['t07_cod_prop_ind'].".- ".$rowInd['t07_ind'], $rowInd['t07_um'], $rowInd['t07_mta'], $rowInd['t07_fv'], $Sup,  true, $NumRows);
	
	while($rowInd = mysql_fetch_assoc($rsIndProp))  
	{
		EscribirFila($ML['t02_pro'], $rowInd['t07_cod_prop_ind'].".- ".$rowInd['t07_ind'], $rowInd['t07_um'], $rowInd['t07_mta'], $rowInd['t07_fv'], $Sup,  false, $NumRows);	
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
     <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >COMPONENTES</td>
     </tr>
    <?php
	/* --------------------------------------- Componentes ----------------------------------------------- */
	$rsComp = $objML->ListadoDefinicionOE($idProy, $idVersion);
	while($rowcomp = mysql_fetch_assoc($rsComp))  
	{
		$rsIndComp = $objML->ListadoIndicadoresOE($idProy, $idVersion, $rowcomp['t08_cod_comp']);
		$rsSupComp = $objML->ListadoSupuestosOE($idProy, $idVersion,  $rowcomp['t08_cod_comp']);
		
		$NumRows = mysql_num_rows($rsIndComp);
		$rowInd = mysql_fetch_assoc($rsIndComp) ; 
		
		$Sup =  $objFunc->resultToString($rsSupComp,array('t08_cod_comp_sup','t08_sup'));

		
		$num_correlativo = $rowcomp['t08_cod_comp'].".".$rowInd['t08_cod_comp_ind'].".- ".$rowInd['t08_ind'];
		EscribirFila($rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'], $num_correlativo, $rowInd['t08_um'], $rowInd['t08_mta'], $rowInd['t08_fv'], $Sup,  true, $NumRows);
		
		while($rowInd = mysql_fetch_assoc($rsIndComp))  
		{
			$num_correlativo = $rowcomp['t08_cod_comp'].".".$rowInd['t08_cod_comp_ind'].".- ".$rowInd['t08_ind'];							
			EscribirFila($rowcomp['t08_cod_comp'].'. '.$rowcomp['t08_comp_desc'], $num_correlativo , $rowInd['t08_um'], $rowInd['t08_mta'], $rowInd['t08_fv'], $Sup,  false, $NumRows);
						
				
		}
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
    <tr >
      <td colspan="5" align="left" valign="middle" class="ClassField" >PRODUCTOS</td>
     </tr>
     <tr >
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
      <td align="left" valign="middle" class="RowSelected" ><strong>Indicadores de Producto</strong></td>
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
     </tr>
     
      <?php
	/* --------------------------------------- Indicadores ----------------------------------------------- */
	$rsAct = $objML->ListadoActividades($idProy, $idVersion);
	while($rowact = mysql_fetch_assoc($rsAct))  
	{
		$rsIndAct = $objML->ListadoIndicadoresAct($idProy, $idVersion, $rowact['t08_cod_comp'], $rowact['t09_cod_act']);
				
		$NumRows = mysql_num_rows($rsIndAct);
		$rowInd = mysql_fetch_assoc($rsIndAct) ; 
		if($NumRows > 0)  
		{ 
			$Sup =  "";
			$Actividad = $rowact['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'. '.$rowact['t09_act'] ;
			
			EscribirFila($Actividad,  $rowInd['t09_ind'], $rowInd['t09_um'], $rowInd['t09_mta'], $rowInd['t09_fv'], $Sup,  true, $NumRows);
			
			while($rowInd = mysql_fetch_assoc($rsIndAct))  
			{
				EscribirFila($Actividad,  $rowInd['t09_ind'], $rowInd['t09_um'], $rowInd['t09_mta'], $rowInd['t09_fv'], $Sup,  false, $NumRows);
			}
		}
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
	
	
     <tr >
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
      <td align="left" valign="middle" class="RowSelected" ><strong>Actividades</strong></td>
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
      <td align="left" valign="middle" class="RowSelected" >&nbsp;</td>
     </tr>
     
     
     <?php
	/* --------------------------------------- Actividades ----------------------------------------------- */
	$rsAct = $objML->ListadoActividades($idProy, $idVersion);
	while($rowact = mysql_fetch_assoc($rsAct))  
	{
		$rsIndAct = $objPOA->Lista_Subactividad($idProy, $idVersion, $rowact['t08_cod_comp'], $rowact['t09_cod_act']);
				
		$NumRows = mysqli_num_rows($rsIndAct);
		$rowInd = mysqli_fetch_assoc($rsIndAct) ; 
		if($NumRows > 0)  
		{ 
			$Sup =  "";
			$Actividad = $rowact['t08_cod_comp'].'.'.$rowact['t09_cod_act'].'. '.$rowact['t09_act'] ;
			$subActividad =$rowInd['comp'].'.'.$rowInd['act'].'.'.$rowInd['subact'].".- ".$rowInd['descripcion'] ;
			
			EscribirFila($Actividad,  $subActividad, $rowInd['um'], $rowInd['meta'], $rowInd['fv'], $Sup,  true, $NumRows);
			
			while($rowInd = mysqli_fetch_assoc($rsIndAct))  
			{
				$subActividad =$rowInd['comp'].'.'.$rowInd['act'].'.'.$rowInd['subact'].".- ".$rowInd['descripcion'] ;
				EscribirFila($Actividad,  $subActividad , $rowInd['um'], $rowInd['meta'], $rowInd['fv'], $Sup,  false, $NumRows);
			}
		}
	}
	/* -------------------------------------------------------------------------------------------------- */
	?>
     
     
    
    
    
    
    
    
  </tbody>
  <tfoot>
   <tr >
      <td align="left" valign="middle" >&nbsp;</td>
      <td align="left" valign="middle" >&nbsp;</td>
      <td align="left" valign="middle" >&nbsp;</td>
      <td align="left" valign="middle" >&nbsp;</td>
      <td align="left" valign="middle" >&nbsp;</td>
   </tr>
  </tfoot>
</table>
<br />
  <p>
    <script language="JavaScript" type="text/javascript">
  </script>
  </p>

<?php
function EscribirFila($resumen, $indicador, $um, $meta, $medios, $supuestos, $isMerge, $numRows)
{
	if($isMerge && $numRows>1)
	{ 
		$strFila = '
		<tr style="font-size:10px;">
	      <td align="left" valign="top" rowspan="'.$numRows.'">'.($resumen).'</td>
	      <td align="left" valign="top" >'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
	      <td align="center" valign="top" >'.$meta.'&nbsp;</td>
	      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
	      <td align="left" valign="top" rowspan="'.$numRows.'">'.($supuestos).'&nbsp;</td>
	    </tr> ' ;
		echo($strFila);
		return;
	}
	
	if(!$isMerge && $numRows>1)
	{
		$strFila = '
		<tr>
	      <td align="left" valign="top" style="font-size:10px;">'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
	      <td align="center" valign="top" >'.$meta.'&nbsp;</td>
	      <td align="left" valign="top" style="font-size:10px;">'.($medios).'&nbsp;</td>
	    </tr> ';
	}
	else
	{ 
		if (strstr($indicador,'.-')) {
			$arTextIndi = explode('.-',$indicador);
			$textIndi = trim($arTextIndi[1]);
			if (!empty($textIndi)) {
				$strFila = '
							<tr style="font-size:10px;">
						      <td align="left" valign="top" >'.($resumen).'</td>
						      <td align="left" valign="top" >'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
						      <td align="center" valign="top" >'.$meta.'&nbsp;</td>
						      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
						      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
						    </tr> ' ;
			} else {
				$strFila = '
						<tr style="font-size:10px;">
					      <td align="left" valign="top" >'.($resumen).'</td>
					      <td align="left" valign="top" ></td>
					      <td align="center" valign="top" >'.$meta.'&nbsp;</td>
					      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
					      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
					    </tr> ' ;
			}
		} else {
			$strFila = '
					<tr style="font-size:10px;">
				      <td align="left" valign="top" >'.($resumen).'</td>
				      <td align="left" valign="top" >'.($indicador).'<br /><span class="ClassField">Unidad de Medida: '.($um).'</span></td>
				      <td align="center" valign="top" >'.$meta.'&nbsp;</td>
				      <td align="left" valign="top" >'.($medios).'&nbsp;</td>
				      <td align="left" valign="top" >'.($supuestos).'&nbsp;</td>
				    </tr> ' ;
		}
		
		 
		
	  
	}
	
	
	echo($strFila);
	
}


?>
<!-- InstanceEndEditable -->
</div>
<?php if($objFunc->__QueryString()=="") { ?>
</form>
</body>
<!-- InstanceEnd --></html>
<?php } ?>