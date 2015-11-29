<?php 

include("../../includes/constantes.inc.php"); 
include("../../includes/validauser.inc.php"); 


require_once (constant('PATH_CLASS') . "BLInformes.class.php");


$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$id = $objFunc->__Request('t31_id');
$version = $objFunc->__Request('v');

if ($objFunc->__QueryString() == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Coronograma Visitas</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form action="#" method="post" enctype="multipart/form-data"
		name="frmMain" id="frmMain">
<?php
}
?>
<div id="divTableLista" class="TableGrid">
			<table width="100%" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<tbody class="data">
					<tr>
						<td rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">&nbsp;</td>
						<td rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Codigo</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Año</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Entregable</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Periodo</strong>
						</td>
						<td height="23" rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Estado</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Visita</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Supervisor</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Costo</strong>
						</td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee">
							<strong>Informe de Supervición</strong>
						</td>
					</tr>
					<tr>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Inicio</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Término</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Rol</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Nombre</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>1er. Pago</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>2do. Pago</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Entregado</strong>
						</td>
						<td height="23" align="center" valign="middle" nowrap="nowrap" bgcolor="#eeeeee">
							<strong>Fecha</strong>
						</td>														
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF">
   
    <?php
//     $objInf = new BLMonitoreo();
//     $iRs = $objInf->PlanVisitasListado($idProy);

    $objInf = new BLInformes();
    $iRs = $objInf->getListadoVisitasProyecto($idProy, $version);
    
    
    
    if (mysql_num_rows($iRs) > 0) {
        
        while ($row = mysql_fetch_array($iRs)) {
            //$costototal += ($row['t31_mto_v1'] + $row['t31_mto_v2']);
            ?>

     <tr>
						<td align="center" valign="middle" nowrap="nowrap">
       
       <?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
       <img src="../../img/pencil.gif" width="14" height="14"
							title="Editar Registro" border="0"
							onclick="btnEditar_Clic('<?php echo($row['t25_anio']);?>','<?php echo $row['t25_entregable'];?>'); return false;"
							style="cursor: pointer;" />
       <?php } ?>
       
       
       
       
						</td>
						<td width="45" height="30" align="center" valign="middle"
							nowrap="nowrap"><?php echo($row['t02_cod_proy']);?></td>
						<td align="center" valign="middle" nowrap="nowrap" style="text-transform: capitalize;"><?php echo( $row['anio']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['entregable']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['periodo']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['estado_text']);?></td>
						<td align="center" valign="middle">
							<?php 
							echo $row['fecha_visita_inicio'];
							?>	   
       					</td>
						<td align="center" valign="middle">
							<?php 							
							echo $row['fecha_visita_termino'];
							?>
						</td>
						<td align="center" valign="middle" nowrap="nowrap" style="text-transform: uppercase;">
							<?php 							
								echo $row['supervisor_cargo'];
							?>						
						</td>
						<td align="center" valign="middle" style="text-transform: capitalize;">
							<?php 							
								echo $row['supervisor_nombres'];
							?>				
						</td>
						<td align="right" valign="middle">						
							<?php echo number_format($row['costo_pago_1'],2, '.',',');?>
						</td>
						
						<td align="right" valign="middle">
							<?php echo number_format($row['costo_pago_2'],2, '.',',');?>
						</td>
						<td align="center" valign="middle">
							<?php 							
								if (empty($row['fec_pre_inf_sup'])) {
									echo 'No';
								} else {
									echo 'Si';
								}
							?>
						</td>
						<td align="center" valign="middle">
							<?php 							
								echo $row['fec_pre_inf_sup'];
							?>
						</td>
					</tr>
     <?php
            
        }
        
    } // Fin de Anexos Fotograficos
    ?>
    
      
      
    </tbody>				
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">
function EnviarSolicitudViaje(id)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idProy=$('#txtCodProy').val();
	var url = "plan_visita_me_edit2.php?action=<?php echo(md5("ajax_solicita_viaje"));?>&idProy="+idProy+"&t31_id="+id;
	
	loadPopup("Enviar Solicitud de Visita - Supervisor Externo", url);
	
	// alert("Solicitud de Viaje");
}

function Guardar_PlanVisita()
{
 <?php $ObjSession->AuthorizedPage(); ?>	
 var anio = $('#t31_anio').val();
 var mes = $('#t31_mes').val();
 if(anio=='' || anio==null){alert("Ingrese A\u00f1o planeado para la visita.");return false;}
 if(mes=='' || mes==null){alert("Seleccione Mes planeado para la visita.");return false;}
 if($('#txtCodProy').val()==""){alert("Error: Seleccione Proyecto !!!"); return false;}
 
 
 var BodyForm=$('#FormData').serialize();
 
 <?php if($action==md5("ajax_edit")) {?>
 var sURL = "plan_visita_me_process.php?action=<?php echo(md5("ajax_edit"));?>";
 <?php } else {?>
 var sURL = "plan_visita_me_process.php?action=<?php echo(md5("ajax_new"));?>";
 <?php }?>
 
 var req = Spry.Utils.loadURL("POST", sURL, true, PlanVisitaSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

}
function PlanVisitaSuccessCallback	(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		LoadDataGrid('');
		alert(respuesta.replace(ret,""));
		
	  }
	  else
	  {alert(respuesta);}  
	}


  
 function btnEditar_Clic(anio, entregable)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idProy=$('#txtCodProy').val();
	var idVersion=$('#cboversion').val();
	var url = "plan_visita_me_edit2.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&anio="+anio+'&entregable='+entregable+'&version='+idVersion;
	
	loadPopup("Editar Cronograma de Visita del Proyecto", url);
  } 
 

</script>
		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>