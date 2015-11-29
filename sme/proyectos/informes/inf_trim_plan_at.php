<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>

<?php
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
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

<title>Plan de Asistencia Técnica</title>
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
					style="font-weight: bold;">Avance en Asistencia Técnica a
						Beneficiarios</span></th>
				<th width="8%" rowspan="2" align="center" class=""><input
					type="button" value="Refrescar"
					title="Refrescar los Avances de Asistencia Técnica"
					onclick="ReLoadPlanesAT();" class="btn_save_custom" /></th>
				<th width="8%" rowspan="2" align="right" valign="middle"><input
					type="button" value="Guardar"
					title="Guardar participacion en los talleres de Capacitación"
					onclick="Guardar_PlanAT();" class="btn_save_custom btn_save" /></th>
				<th width="10%" rowspan="2" align="right" valign="middle"><input
					type="button" value="Exportar"
					title="Exportar los Avances de Capacitación"
					onclick="ExportPlanesAT();" class="btn_save_custom" /></th>
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
								onchange="LoadProv1();" class="PlanAT">
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
								onchange="LoadDist1();" class="PlanAT">
									<option value="" selected="selected"></option>
        <?php
        $objBene = new BLBene();
        $rsDpto = $objBene->ListaUbigeoProv($idProy, $dpto);
        $prov1 = $objFunc->llenarComboI($rsDpto, 'idprov', 'prov', $prov);
        ?>
        </select></td>
							<td><select name="cbodist" id="cbodist" style="width: 120px;"
								onchange="LoadCase1();" class="PlanAT">
									<option value="" selected="selected"></option>
          <?php
        $objTablas = new BLTablasAux();
        $rsProv = $objTablas->ListaDistritos($dpto, $prov);
        $objFunc->llenarComboI($rsProv, 'codigo', 'descripcion', $dist);
        ?>
        </select></td>
							<td><select name="cbocase" id="cbocase" style="width: 120px;"
								onchange="ReLoadPlanesAT();" class="PlanAT">
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

		<div class="TableGrid"
			style="overflow: auto; max-width: 780px; max-height: 350px;">
<?php
$objPOA = new BLPOA();
$idVS = $objPOA->UltimaVersionPoa($idProy, $idAnio);
$rsMod = $objPOA->Lista_InfTrim_PlanAT(1, $idProy, $idVS, NULL);
$arrMod = NULL;
$arrSub = NULL;

?>



  <table border="0" cellspacing="0" cellpadding="0" width="770">
				<tbody class="data" bgcolor="#eeeeee">
					<tr>
						<td colspan="2" align="center" valign="middle"><strong>Datos del
								Beneficiario </strong></td>
      <?php
    while ($rm = mysqli_fetch_assoc($rsMod)) {
        $arrMod[] = $rm['codmodulo'];
        ?>
      <td colspan="<?php echo($rm['numsub']); ?>" align="center"
							valign="middle" style="min-width: 120px;"><strong><?php echo( $rm['nommodulo']);?></strong></td>
      <?php } ?>
    </tr>
					<tr>
						<td width="3%" rowspan="2" align="center" valign="middle"><strong>DNI</strong></td>
						<td rowspan="2" align="center" valign="middle"
							style="min-width: 250px;"><strong>Apellidos y </strong><strong>Nombres</strong></td>
      <?php
    for ($x = 0; $x < count($arrMod); $x ++) {
        $rsSub = $objPOA->Lista_InfTrim_PlanAT(2, $idProy, $idVS, $arrMod[$x]);
        while ($rsub = mysqli_fetch_assoc($rsSub)) {
            $codig = $rsub['codigo'];
            $arrSub[$arrMod[$x]][] = $rsub['codigo'];
            ?>
      <td colspan="<?php echo($rsub['numtema']);?>" align="center"
							valign="top" style="min-width: 120px;"><strong><?php echo($rsub['codigo'].'<br>'.$rsub['t09_sub'])?></strong>
							<input name="txtcodsub[]" type="hidden" id="txtcodsub[]"
							value="<?php echo($codig); ?>" class="PlanAT" /></td>
      <?php
        } // EndWhile
    } // EndFor    ?>
    </tr>

				</tbody>
				<tbody class="data">
    <?php
    
    $objInf = new BLInformes();
    
    $iRsBenef = $objInf->InfTrim_AT_Lista($idProy, $idAnio, $idTrim, $dpto, $prov, $dist, $case);
    
    while ($rb = mysqli_fetch_assoc($iRsBenef)) {
        ?>
    <tr>
						<td width="3%" align="center" valign="middle"><input
							name="txtbenef[]" type="hidden" id="txtbenef[]"
							value="<?php echo($rb['t11_cod_bene']); ?>" class="PlanAT" />
	  	<?php echo($rb['t11_dni']); ?>
      </td>
						<td valign="middle" style="min-width: 150px;"><?php echo($rb['nombres']); ?></td>
      <?php
        
        for ($x = 0; $x < count($arrMod); $x ++) {
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $codig = $arrSub[$arrMod[$x]][$y];
                $valor = $rb[$codig];
                
                ?>
        			<td width="3%" valign="middle" align="center"><input
							type="hidden" name="txt_<?php echo($codig);?>[]"
							id="txt_<?php echo($codig);?>[]" class="PlanAT" maxlength="20"
							value="<?php echo($valor);?>"
							style="width: 20px; text-align: center; text-transform: uppercase;"
							title="<?php echo($codig);?>" /> <input type="checkbox"
							name="chk_<?php echo($codig);?>[]"
							id="chk_<?php echo($codig);?>[]" class="PlanAT" maxlength="20"
							value="1" <?php if($valor=='1'){echo("checked");}?>
							title="<?php echo($codig);?>"
							onclick="ActivarPlanAT('<?php echo($codig);?>');"
							subact="<?php echo($codig);?>" /></td>
        <?php
            }
        }
        
        ?>
    </tr>
    <?php } ?>
    </tbody>
			</table>
		</div>
		<input type="hidden" name="t25_cod_proy"
			value="<?php echo($idProy);?>" class="PlanAT" /> <input type="hidden"
			name="t25_version" value="<?php echo($idVersion);?>" class="PlanAT" />
		<input type="hidden" name="t25_anio" value="<?php echo($idAnio);?>"
			class="PlanAT" /> <input type="hidden" name="t25_trim"
			value="<?php echo($idTrim);?>" class="PlanAT" />

		<script language="javascript">
function ActivarPlanAT(subact)
{
	$('.PlanAT:input[subact="'+subact+'"]').each( function(i) {
												  var iTxt = document.getElementsByName("txt_"+subact+"[]")[i];
												    if(iTxt.className=="PlanAT")
												    { iTxt.value = (this.checked ? "1" : "0"); }
												  } ) ;
}


function LoadProv1()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.PlanAT').val();
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
	$('#cboprov.PlanAT').html('<option> Cargando ... </option>');
	$('#cbodist.PlanAT').html('');
	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function ProvSuccessCallback1(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboprov.PlanAT').html(respuesta);
  if($("#cboprov.PlanAT" ).val()=="")
  {
  	$('#cboprov.PlanAT').focus();
  }
  else
  {
  	LoadDist1();
  }
}
function LoadDist1()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.PlanAT').val() + "&prov=" + $('#cboprov.PlanAT').val() ;
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist.PlanAT').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function DistSuccessCallback1(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist.PlanAT').html(respuesta);
  
  if($("#cbodist.PlanAT").val()=="")
  {
  	$('#cboprov.PlanAT').focus();
  }
  else
  {
  	LoadCase1();
  }
  
  $('#cbodist.PlanAT').focus();
}
function LoadCase1()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.PlanAT').val() + "&prov=" + $('#cboprov.PlanAT').val()+ "&dist=" + $('#cbodist.PlanAT').val() ;
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
	$('#cbocase.PlanAT').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function CaseSuccessCallback1(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbocase.PlanAT').html(respuesta);
  $('#cbocase.PlanAT').focus();
}
</script>

		<script language="javascript" type="text/javascript">
function ReLoadPlanesAT()
{
	var BodyForm = "action=<?php echo(md5("ListaPlanAT"));?>&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idTrim=<?php echo($idTrim);?>&t25_ver_inf="+$('#t25_ver_inf').val()+"&dpto=" + $('#cbodpto.PlanAT').val() +"&prov=" + $('#cboprov.PlanAT').val() +"&dist=" + $('#cbodist.PlanAT').val() +"&case=" + $('#cbocase.PlanAT').val() ;
	var sURL = "inf_trim_plan_at.php";
	$('#divPlanAT').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanAT, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}

function Guardar_PlanAT()
{
<?php $ObjSession->AuthorizedPage(); ?>	

var BodyForm=$("#FormData .PlanAT").serialize();
if(confirm("Estas seguro de Guardar el avance en Asistencia Tecnica para el informe Trimestral ?"))
{
    var sURL = "inf_trim_process.php?action=<?php echo(md5('save_plan_at'));?>";
    var req = Spry.Utils.loadURL("POST", sURL, true, PlanATSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
}
function PlanATSuccessCallback(req)
{ 
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
    alert(respuesta.replace(ret,""));
	ReLoadPlanesAT();
  }
  else
  {alert(respuesta);}  
}

function ExportPlanesAT()
{
	var params = "&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idTrim=<?php echo($idTrim);?>";
	var url = "<?php echo(constant("PATH_RPT"))?>reportviewer.php?ReportID=45" + params;
	var win =  window.open(url, "wrpt_planAT", "fullscreen,scrollbars");
    win.focus();
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