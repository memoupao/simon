<?php include("../../includes/constantes.inc.php"); ?>
<?php include("../../includes/validauser.inc.php"); ?>

<?php
require (constant("PATH_CLASS") . "BLMonitoreo.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

$action = $objFunc->__Request('action');

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$id = $objFunc->__POST('t46_id');

if ($idProy == "" && $id == "") {
    $idProy = $objFunc->__GET('idProy');
    $id = $objFunc->__GET('t46_id');
}

if ($idProy == "") {
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
			<table width="750" border="0" cellpadding="0" cellspacing="0"
				class="TableGrid">
				<thead>
					<tr>
						<td width="38" align="center" valign="middle">&nbsp;</td>
						<td align="center" valign="middle" nowrap="nowrap"><strong>No</strong></td>
						<td width="62" height="23" align="center" valign="middle"><strong>AÑO</strong></td>
						<td width="110" align="center" valign="middle"><strong>MES</strong></td>
						<td width="506" align="center" valign="middle"><strong>OBSERVACIONES</strong></td>
					</tr>
				</thead>
				<tbody class="data" bgcolor="#FFFFFF">

    <?php
    $objInf = new BLMonitoreo();
    $iRs = $objInf->PlanVisitasMIListado($idProy);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            ?>

    <?php
            if ($action == md5("ajax_edit") && $row['t46_id'] == $id) {
                ?>
      <tr class="RowSelected">
						<td width="38" align="center" valign="middle" nowrap="nowrap"
							bgcolor="#FFFFFF"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;">
								<img src="../../img/aplicar.png" width="15" height="15"
								title="Guardar Edición de la Visita"
								onclick="Guardar_PlanVisita();" /> <img
								src="../../img/closePopup.gif" width="16" height="16"
								title="Cancelar " onclick="LoadDataGrid('');" />
						</span></td>
						<td width="32" align="center" valign="middle"><input type="hidden"
							name="t46_id" id="t46_id" value="<?php echo($row['t46_id']);?>" />
         <?php echo($row['t46_id']);?></td>
						<td align="center" nowrap="nowrap"><input name="t46_anio"
							type="text" id="t46_anio" style="text-align: center;"
							value="<?php echo( $row['t46_anio']);?>" size="10" maxlength="4" /></td>
						<td align="center" nowrap="nowrap"><select name="t46_mes"
							id="t46_mes" style="width: 100px;">
								<option value="" selected="selected"></option>
       <?php
                $objTablas = new BLTablasAux();
                $rsMeses = $objTablas->ListadoMesesCalendario();
                $objFunc->llenarCombo($rsMeses, 'codigo', 'descripcion', $row['t46_mes']);

                ?>
       </select></td>
						<td align="center" nowrap="nowrap"><textarea name="t46_obs"
								rows="2" id="t46_obs" style="padding: 0px; width: 100%;"><?php echo( $row['t46_obs']);?></textarea></td>
					</tr>
      <?php } else { ?>
     <tr>
						<td align="center" valign="middle" nowrap="nowrap"><img
							src="../../img/pencil.gif" width="14" height="14"
							title="Editar Visita" border="0"
							onclick="btnEditar_Clic('<?php echo($row['t46_id']);?>'); return false;"
							style="cursor: pointer;" /> <img src="../../img/bt_elimina.gif"
							width="14" height="14" title="Eliminar Visita" border="0"
							onclick="EliminarPlanVisita('<?php echo($row['t46_id']);?>');"
							style="cursor: pointer;" /></td>
						<td height="30" align="center" valign="middle"><?php echo($row['t46_id']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['t46_anio']);?></td>
						<td align="center" valign="middle" nowrap="nowrap"><?php echo( $row['nommes']);?></td>
						<td align="left" valign="middle"><?php echo( $row['t46_obs']);?></td>
					</tr>
     <?php } ?>

     <?php
            $RowIndex ++;
        }
        $iRs->free();
    } // Fin de Anexos Fotograficos
    ?>

      <?php if($action!=md5("ajax_edit"))  { ?>
       <tr>
						<td width="38" align="center" valign="middle"><span
							style="text-align: center; color: navy; font-weight: bold; font-size: 10px; cursor: pointer; width: 50px;"><img
								src="../../img/aplicar.png" width="20" height="20"
								title="Guardar Nueva Visita de Supervisión"
								onclick="Guardar_PlanVisita();" /></span></td>
						<td width="32" align="left" valign="middle"><input type="hidden"
							name="t46_id" id="t46_id" value="<?php echo($row['t46_id']);?>" /></td>
						<td align="center" nowrap="nowrap"><input name="t46_anio"
							type="text" id="t46_anio" size="10" maxlength="4"
							style="text-align: center;" /></td>
						<td align="center" nowrap="nowrap"><select name="t46_mes"
							id="t46_mes" style="width: 100px;">
								<option value="" selected="selected"></option>
       <?php
        $objTablas = new BLTablasAux();
        $rsMeses = $objTablas->ListadoMesesCalendario();
        $objFunc->llenarCombo($rsMeses, 'codigo', 'descripcion', '');

        ?>
       </select></td>
						<td align="center" nowrap="nowrap"><textarea name="t46_obs"
								rows="2" id="t46_obs" style="padding: 0px; width: 100%;"></textarea></td>
					</tr>
      <?php } ?>

    </tbody>
				<tfoot>
					<tr>
						<td colspan="5" align="center" valign="middle">&nbsp;</td>
					</tr>

				</tfoot>
			</table>
			<input type="hidden" name="t02_cod_proy"
				value="<?php echo($idProy);?>" />
			<script language="javascript" type="text/javascript">
function Guardar_PlanVisita()
{
 <?php $ObjSession->AuthorizedPage(); ?>
 var anio = $('#t46_anio').val();
 var mes = $('#t46_mes').val();
 if(anio=='' || anio==null){alert("Ingrese A\u00f1o planeado para la visita.");return false;}
 if(mes=='' || mes==null){alert("Seleccione Mes planeado para la visita.");return false;}
 if($('#txtCodProy').val()==""){alert("Error: Seleccione Proyecto !!!"); return false;}


 var BodyForm=$('#FormData').serialize();

 <?php if($action==md5("ajax_edit")) {?>
 var sURL = "cron_visita_mi_process.php?action=<?php echo(md5("ajax_edit"));?>";
 <?php } else {?>
 var sURL = "cron_visita_mi_process.php?action=<?php echo(md5("ajax_new"));?>";
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
		var BodyForm = "t02_cod_proy="+$('#txtCodProy').val()+"&t46_id="+id;

		var sURL = "cron_visita_mi_process.php?action=<?php echo(md5("ajax_del"))?>";
		var req = Spry.Utils.loadURL("POST", sURL, true, PlanVisitaSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});

	}
}

function btnEditar_Clic(id)
  {
	<?php $ObjSession->AuthorizedPage(); ?>
	var idProy=$('#txtCodProy').val();
	var url = "cron_visita_mi_edit.php?action=<?php echo(md5("ajax_edit"));?>&idProy="+idProy+"&t46_id="+id;
	loadUrlSpry("divContentEdit",url);

	return;
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