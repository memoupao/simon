<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLBene.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$idTrim = $objFunc->__Request('idTrim');
$dpto = $objFunc->__Request('dpto');
$prov = $objFunc->__Request('prov');
$dist = $objFunc->__Request('dist');
$case = $objFunc->__Request('case');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>Plan de Capacitación</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<script language="javascript" type="text/javascript"
	src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
<!-- InstanceEndEditable -->
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css"
	media="all" />
</head>

<body>
	<!-- InstanceBeginEditable name="tBody" -->
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
<table width="780" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th width="74%" align="left" class=""><span
					style="font-weight: bold;">Avance en Capacitaciones de los
						Beneficiarios</span></th>
				<th width="9%" rowspan="2" align="center" class=""><input
					type="button" value="Refrescar"
					title="Refrescar los Avances de Capacitación"
					onclick="ReLoadPlanesCapacitacion();" class="btn_save_custom" /></th>
				<th width="7%" rowspan="2" align="center" valign="middle"><input
					type="button" value="Guardar"
					title="Guardar participacion en los talleres de Capacitación"
					onclick="Guardar_PlanCapacita();" class="btn_save_custom btn_save" />
				</th>
				<th width="10%" rowspan="2" align="center" valign="middle"><input
					type="button" value="Exportar"
					title="Exportar los Avances de Capacitación"
					onclick="ExportPlanesCapacitacion();" class="btn_save_custom" /></th>
			</tr>
			<tr>
				<th align="left" class="">
					<table width="200" border="0" cellspacing="2" class="TableEditReg">
						<tr
							style="font: Verdana, Geneva, sans-serif; font-weight: normal;">
							<td>&nbsp;</td>
							<td align="center">Departamento</td>
							<td align="center">Provincia</td>
							<td align="center">Distrito</td>
							<td align="center">Caserio</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><select name="cbodpto" id="cbodpto" style="width: 120px;"
								onchange="LoadProv();" class="PlanCapacitacion PlanCapacList">
            <?php
            $objBene = new BLBene();
            $rsDpto = $objBene->ListaUbigeoDpto($idProy);
            $dpto1 = $objFunc->llenarComboI($rsDpto, 'iddpto', 'dpto', $dpto);
            if ($dpto1 != $dpto) {
                $dpto = $dpto1;
            }
            ?>
        	</select></td>
							<td><select name="cboprov" id="cboprov" style="width: 120px;"
								class="PlanCapacitacion PlanCapacList">
									<option value="" selected="selected"></option>
        	<?php
        $objBene = new BLBene();
        $rsDpto = $objBene->ListaUbigeoProv($idProy, $dpto);
        $prov1 = $objFunc->llenarComboI($rsDpto, 'idprov', 'prov', $prov);
        ?>
        	</select></td>
							<td><select name="cbodist" id="cbodist" style="width: 120px;"
								class="PlanCapacitacion PlanCapacList">
									<option value="" selected="selected"></option>
          	<?php
        $objTablas = new BLTablasAux();
        $rsProv = $objTablas->ListaDistritos($dpto, $prov);
        $objFunc->llenarComboI($rsProv, 'codigo', 'descripcion', $dist);
        ?>
        	</select></td>
							<td><select name="cbocase" id="cbocase" style="width: 120px;"
								class="PlanCapacitacion PlanCapacList">
									<option value="" selected="selected"></option>
        	<?php
        $rsCase = $objTablas->ListaCaserios($dpto, $prov, $dist);
        $objFunc->llenarComboI($rsCase, 'codigo', 'descripcion', $case);
        ?>
        	</select></td>
						</tr>

					</table>
				</th>
			</tr>
		</table>

		<div class="TableGrid" id='PlanCapacTableGrid'
			style="overflow: auto; max-width: 780px; max-height: 350px;"></div>

		<input type="hidden" name="t25_cod_proy"
			value="<?php echo($idProy);?>" class="PlanCapacitacion PlanCapacList" />
		<input type="hidden" name="t25_version"
			value="<?php echo($idVersion);?>"
			class="PlanCapacitacion PlanCapacList" /> <input type="hidden"
			name="t25_anio" value="<?php echo($idAnio);?>"
			class="PlanCapacitacion PlanCapacList" /> <input type="hidden"
			name="t25_trim" value="<?php echo($idTrim);?>"
			class="PlanCapacitacion PlanCapacList" />

		<script language="javascript">
	$(document).ready(function(){
		LoadPlanCapacList();
		$('#cbodpto').change(function(pEvent) {
			LoadPlanCapacList();
			LoadProv();
		});
		$('#cboprov').change(function(pEvent) {
			LoadPlanCapacList();
			if ($(pEvent.target).val()) {
				LoadDist();
			}
			else {
				$('#cbodist').html('');
				$('#cbocase').html('');
			}
		});
		$('#cbodist').change(function(pEvent) {
			LoadPlanCapacList();
			if ($(pEvent.target).val())
				LoadCase();
			else
				$('#cbocase').html('');
		});
		$('#cbocase').change(function(pEvent) {
			LoadPlanCapacList();
		});
	});
	
	function LoadPlanCapacList()
	{
		var aQueryString = $('.PlanCapacList').serialize();
		aQueryString	=	aQueryString.replace('t25_cod_proy', 'idProy')
										.replace('t25_version', 'idVersion')
										.replace('t25_anio', 'idAnio')
										.replace('t25_trim', 'idTrim');
		$('#PlanCapacTableGrid').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>")
								.load('inf_trim_plan_capac_list.php?' + aQueryString);
	}
	

function ActivarPlanCapac(subact)
{
	$('.PlanCapacitacion:input[subact="'+subact+'"]').each( function(i) {
												  var iTxt = document.getElementsByName("txt_"+subact+"[]")[i];
												  if(iTxt.className=="PlanCapacitacion")
												    { iTxt.value = (this.checked ? "1" : "0"); }
												  } ) ;
}

function LoadProv()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto').val();
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
	$('#cboprov').html('<option> Cargando ... </option>');
	$('#cbodist').html('');
	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function ProvSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboprov').html(respuesta);
  if($("#cboprov").val()=="")
  {
  	$('#cboprov').focus();
  }
  else
  {
  	LoadDist();
  }
}
function LoadDist()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto').val() + "&prov=" + $('#cboprov').val() ;
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function DistSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist').html(respuesta);
  
  if($("#cbodist").val()=="")
  {
  	$('#cboprov').focus();
  }
  else
  {
  	LoadCase();
  }
  
  $('#cbodist').focus();
}
function LoadCase()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto').val() + "&prov=" + $('#cboprov').val()+ "&dist=" + $('#cbodist').val() ;
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
	$('#cbocase').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function CaseSuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbocase').html(respuesta);
  $('#cbocase').focus();
}
</script>

		<script language="javascript" type="text/javascript">
function ReLoadPlanesCapacitacion()
{
	var BodyForm = "action=<?php echo(md5("ListaPlanCapac"));?>&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idTrim=<?php echo($idTrim);?>&t25_ver_inf="+$('#t25_ver_inf').val()+"&dpto=" + $('#cbodpto').val() +"&prov=" + $('#cboprov').val() +"&dist=" + $('#cbodist').val() +"&case=" + $('#cbocase').val() ;
	var sURL = "inf_trim_plan_capac.php";
	$('#divPlanCapacitacion').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanesEspecificos, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}

function Guardar_PlanCapacita()
{
<?php $ObjSession->AuthorizedPage(); ?>	

var BodyForm=$("#FormData .PlanCapacitacion").serialize();
if(confirm("Estas seguro de Guardar el avance en Capacitacion para el informe Trimestral ?"))
{
    var sURL = "inf_trim_process.php?action=<?php echo(md5('save_plan_capac'));?>";
    var req = Spry.Utils.loadURL("POST", sURL, true, PlanCapacSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
}
function PlanCapacSuccessCallback(req)
{ 
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
    alert(respuesta.replace(ret,""));
	ReLoadPlanesCapacitacion();
  }
  else
  {alert(respuesta);}  
}

function ExportPlanesCapacitacion()
{
	var params = "&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idTrim=<?php echo($idTrim);?>";
	var url = "<?php echo(constant("PATH_RPT"))?>reportviewer.php?ReportID=44" + params;
	var win =  window.open(url, "wrpt_plancapac", "fullscreen,scrollbars");
    win.focus();
	
}
function ExportPlanesCapacitacion2()
{
	//var params = "&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idTrim=<?php echo($idTrim);?>";
	//var url = "<?php echo(constant("PATH_RPT"))?>reportviewer.php?ReportID=60" + params;
	//var win =  window.open(url, "wrpt_plancapac", "fullscreen,scrollbars");
    //win.focus();
}
</script>


		<fieldset style="font-size: 11px; color: navy; display: none;">
			<legend style="color: red; font-size: 9px;">Opciones</legend>
			<strong>C</strong> : Capacitado &nbsp;&nbsp;|&nbsp;&nbsp; <strong>P</strong>
			: En Proceso &nbsp;&nbsp;|&nbsp;&nbsp; <strong>R</strong> : Retirado
			<br /> <font style="color: #666">Colocar en los cuadros de Texto solo
				una de las 3 opciones, cuaquier otro valor, no sera tomado en
				cuenta.</font>
		</fieldset>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>