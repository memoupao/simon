<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLMonitoreo.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");
require (constant("PATH_CLASS") . "HardCode.class.php");

$HC = new HardCode();

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$id = $objFunc->__Request('t31_id');

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
			<table width="780" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<tbody class="data">
					<tr>
						<td rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">&nbsp;</td>
						<td rowspan="2" align="center" valign="middle" bgcolor="#eeeeee"><strong>N&deg;
								Visita</strong></td>
						<td height="23" colspan="2" align="center" valign="middle"
							bgcolor="#eeeeee"><strong>Plan de visita - según Contrato</strong></td>
						<td colspan="2" align="center" valign="middle" bgcolor="#eeeeee"><strong>Costo
								de la Visita</strong></td>
						<td colspan="3" align="center" valign="middle" bgcolor="#eeeeee"><strong>Fecha
								de la Visita</strong></td>
						<td width="255" rowspan="2" align="center" valign="middle"
							bgcolor="#eeeeee"><strong>OBSERVACIONES</strong></td>
						<td rowspan="2" align="center" valign="middle" bgcolor="#eeeeee">&nbsp;</td>
					</tr>
					<tr>
						<td width="42" height="23" align="center" valign="middle"
							nowrap="nowrap" bgcolor="#eeeeee"><strong>AÑO</strong></td>
						<td width="61" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#eeeeee"><strong>MES</strong></td>
						<td width="59" align="center" valign="middle" bgcolor="#eeeeee"><strong>Primer
								Pago</strong></td>
						<td width="67" align="center" valign="middle" bgcolor="#eeeeee"><strong>Segundo
								Pago</strong></td>
						<td width="40" align="center" valign="middle" bgcolor="#eeeeee"><strong>Inicio
						</strong></td>
						<td width="40" align="center" valign="middle" bgcolor="#eeeeee"><strong>Termino</strong></td>
						<td width="38" align="center" valign="middle" bgcolor="#eeeeee"><strong>V.B.
								GP</strong></td>						
					</tr>
				</tbody>
				<tbody class="data" bgcolor="#FFFFFF">
   
    <?php
    $objInf = new BLMonitoreo();
    $iRs = $objInf->PlanVisitasListado($idProy);
    $RowIndex = 0;
    $costototal = 0;
    if ($iRs->num_rows > 0) {
        $fecprogra = true;
        while ($row = mysqli_fetch_assoc($iRs)) {
            $costototal += ($row['t31_mto_v1'] + $row['t31_mto_v2']);
            ?>

     <tr>
						<td align="center" valign="middle" nowrap="nowrap">
       <?php if($row['t31_vb_v1']!='1') { ?>
       <?php if($ObjSession->AuthorizedOpcion("EDITAR")) { ?>
       <img src="../../img/pencil.gif" width="14" height="14"
							title="Editar Registro" border="0"
							onclick="btnEditar_Clic('<?php echo($row['t31_id']);?>'); return false;"
							style="cursor: pointer;" />
       <?php } ?>
       <?php } ?>
       
       
       
						
						<td width="45" height="30" align="center" valign="middle"
							nowrap="nowrap"><?php echo($row['t31_id']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t31_anio']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['nommes']);?></td>
						<td align="right" valign="middle" nowrap="nowrap"><?php echo( number_format($row['t31_mto_v1'],2));?></td>
						<td align="right" valign="middle" nowrap="nowrap"><?php echo( number_format($row['t31_mto_v2'],2));?></td>
						<td align="center" valign="middle">
	   <?php
            if ($row['sol_via'] == '1') {
                if ($ObjSession->PerfilID == $HC->SE || $ObjSession->PerfilID == $HC->GP) {
                    if ($fecprogra) {
                        echo ('<a href="javascript:;" onclick="EnviarSolicitudViaje(\'' . $row['t31_id'] . '\');" title="Enviar Solicitud de Viaje al Gestor de Proyectos">Enviar Solicitud de Viaje</a>');
                    }
                }
            } else {
                if ($row['t31_vb_v1'] == '1') {
					echo ($row['t31_fec_vis']);
					$fecprogra = true;
                } else {
                    echo ('<a href="javascript:;" onclick="EnviarSolicitudViaje(\'' . $row['t31_id'] . '\');" title="Enviar Solicitud de Viaje al Gestor de Proyectos">' . $row['t31_fec_vis'] . '</a>');
                    $fecprogra = false;
                }
            }
            
            ?>
       </td>
						<td align="center" valign="middle">
	   <?php
            
            if ($row['sol_via'] == '1') {
                if ($ObjSession->PerfilID == $HC->SE || $ObjSession->PerfilID == $HC->GP) {
                    if ($fecprogra) {
                        echo ('<a href="javascript:;" onclick="EnviarSolicitudViaje(\'' . $row['t31_id'] . '\');" title="Enviar Solicitud de Viaje al Gestor de Proyectos">Enviar Solicitud de Viaje</a>');
                    }
                }
            } else {
                if ($row['t31_vb_v1'] == '1') {
					echo ($row['t31_fec_ter']);
					$fecprogra = true;
                } else {
                    echo ('<a href="javascript:;" onclick="EnviarSolicitudViaje(\'' . $row['t31_id'] . '\');" title="Enviar Solicitud de Viaje al Gestor de Proyectos">' . $row['t31_fec_ter'] . '</a>');
                    $fecprogra = false;
                }
            }
            
            ?></td>
						<td align="center" valign="middle" nowrap="nowrap"><input
							type="checkbox" value="1" disabled="disabled"
							<?php if($row['t31_vb_v1']=='1'){echo('checked="checked"');} ?> /></td>
						<td align="left" valign="middle"><?php echo(  substr($row['t31_obs'],0,150 )); if(strlen($row['t31_obs'])>150){echo(" ...");} ?></td>
						<td align="left" valign="middle">
       <?php if($row['t31_vb_v1']!='1') { ?>
       <?php if($ObjSession->AuthorizedOpcion("ELIMINAR")) { ?>
       <img src="../../img/bt_elimina.gif" width="14" height="14"
							title="Eliminar Registro" border="0"
							onclick="EliminarPlanVisita('<?php echo($row['t31_id']);?>');"
							style="cursor: pointer;" />
       <?php } ?>
       <?php } ?>
       </td>
					</tr>
     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>
    
      
      
    </tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center" valign="middle"><strong
							style="color: #FFF;">&nbsp; COSTO TOTAL DE LAS VISITAS</strong> <iframe
								id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
						</td>
						<td colspan="2" align="right" valign="middle"><strong
							style="color: #FFF;"><?php echo( number_format($costototal,2));?></strong></td>
						<td colspan="2" align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle">&nbsp;</td>
					</tr>

				</tfoot>
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
function EliminarPlanVisita(id)
{
	<?php $ObjSession->AuthorizedPage(); ?>	
	
	if(confirm("¿Estás seguro de eliminar el Registro seleccionado ?"))
	{
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t31_id="+id;

		var sURL = "plan_visita_me_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, PlanVisitaSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}

  
 function btnEditar_Clic(id)
  {
	<?php $ObjSession->AuthorizedPage(); ?>	
	var idProy=$('#txtCodProy').val();
	var url = "plan_visita_me_edit2.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&t31_id="+id;
	
	loadPopup("Editar Visita de Supervisor Externo", url);
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