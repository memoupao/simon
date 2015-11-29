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
				<th width="82%" align="left" class=""><span
					style="font-weight: bold;">Avance en Asistencia Técnica a
						Beneficiarios</span></th>
				<th width="8%" rowspan="2" align="center" class="">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:60px;" title="Refrescar los Avances de Asistencia Técnica"  onclick="ReLoadPlanesOtros();" > <img src="../../../img/gestion.jpg" width="24" height="24" /><br />
      Refrescar </div osktgui--> <input type="button" value="Refrescar"
					title="Refrescar los Avances de Asistencia Técnica"
					onclick="ReLoadPlanesOtros();" class="btn_save_custom" />
				</th>
				<th width="10%" rowspan="2" align="center" valign="middle">
					<!--div style="text-align:center; color:navy; font-weight:bold; font-size:10px; cursor:pointer; width:50px;" title="Guardar participacion en los talleres de Capacitación"  onclick="Guardar_PlanOtros();" > <img src="../../../img/aplicar.png" width="22" height="22" /><br />
      Guardar  </div osktgui--> <input type="button" value="Guardar"
					title="Guardar participacion en los talleres de Capacitación"
					onclick="Guardar_PlanOtros();" class="btn_save_custom btn_save" />
				</th>
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
								onchange="LoadProv2();" class="PlanOtros">
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
								onchange="LoadDist2();" class="PlanOtros">
									<option value="" selected="selected"></option>
        <?php
        $objBene = new BLBene();
        $rsDpto = $objBene->ListaUbigeoProv($idProy, $dpto);
        $prov1 = $objFunc->llenarComboI($rsDpto, 'idprov', 'prov', $prov);
        // if($prov1!=$prov){$prov=$prov1;$dist='';$case='';}
        ?>
        </select></td>
							<td><select name="cbodist" id="cbodist" style="width: 120px;"
								onchange="LoadCase2();" class="PlanOtros">
									<option value="" selected="selected"></option>
          <?php
        /*
         * $objBene = new BLBene(); $rsDpto = $objBene->ListaUbigeoDist($idProy, $dpto, $prov); $dist1 = $objFunc->llenarComboI($rsDpto,'iddist','dist',$dist); if($dist1!=$dist){$dist=$dist1;$case='';}
         */
        $objTablas = new BLTablasAux();
        $rsProv = $objTablas->ListaDistritos($dpto, $prov);
        $objFunc->llenarComboI($rsProv, 'codigo', 'descripcion', $dist);
        ?>
        </select></td>
							<td><select name="cbocase" id="cbocase" style="width: 120px;"
								onchange="ReLoadPlanesOtros();" class="PlanOtros">
									<option value="" selected="selected"></option>
        <?php
        /*
         * $objBene = new BLBene(); $rsDpto = $objBene->ListaUbigeoCaserio($idProy, $dpto, $prov, $dist); $case = $objFunc->llenarComboI($rsDpto,'idcase','caserio', $case );
         */
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
$rsMod = $objPOA->Lista_InfTrim_PlanOtros(1, $idProy, $idVS, NULL);
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
        $arrMod[] = $rm['codtipo'];
        ?>
      <td colspan="<?php echo($rm['numsub']); ?>" align="center"
							valign="middle"><strong><?php echo( $rm['nomtipo']);?></strong></td>
      <?php } ?>
    </tr>
					<tr>
						<td width="3%" rowspan="2" align="center" valign="middle"><strong>DNI</strong></td>
						<td rowspan="2" align="center" valign="middle"><strong>Apellidos y
						</strong><strong>Nombres</strong></td>
      <?php
    for ($x = 0; $x < count($arrMod); $x ++) {
        $rsSub = $objPOA->Lista_InfTrim_PlanOtros(2, $idProy, $idVS, $arrMod[$x]);
        while ($rsub = mysqli_fetch_assoc($rsSub)) {
            $codig = $rsub['codigo'];
            $arrSub[$arrMod[$x]][] = $rsub['codigo'];
            ?>
      <td colspan="<?php echo($rsub['numtema']);?>" align="center"
							valign="top" style="min-width: 120px;"><strong><?php echo($rsub['codigo'].'<br>'.$rsub['t09_sub'])?></strong>
							<input name="txtcodsub[]" type="hidden" id="txtcodsub[]"
							value="<?php echo($codig); ?>" class="PlanOtros" /></td>
      <?php
        } // EndWhile
    } // EndFor    ?>
    </tr>

				</tbody>
				<tbody class="data">
    <?php
    
    $objInf = new BLInformes();
    
    $iRsBenef = $objInf->InfTrim_Otros_Lista($idProy, $idAnio, $idTrim, $dpto, $prov, $dist, $case);
    
    while ($rb = mysqli_fetch_assoc($iRsBenef)) {
        ?>
    <tr>
						<td width="3%" align="center" valign="middle"><input
							name="txtbenef[]" type="hidden" id="txtbenef[]"
							value="<?php echo($rb['t11_cod_bene']); ?>" class="PlanOtros" />
	  	<?php echo($rb['t11_dni']); ?>
      </td>
						<td valign="middle" style="max-width: 250px;"><?php echo($rb['nombres']); ?></td>
      <?php
        
        for ($x = 0; $x < count($arrMod); $x ++) {
            for ($y = 0; $y < count($arrSub[$arrMod[$x]]); $y ++) {
                $codig = $arrSub[$arrMod[$x]][$y];
                $avalotros = explode('|', $rb[$codig]);
                $valor = $avalotros[0]; // $rb[$codig] ;
                $cont = $avalotros[1];
                
                ?>
        			<td width="3%" valign="middle" align="center"><input
							type="hidden" name="txt_<?php echo($codig);?>[]"
							id="txt_<?php echo($codig);?>[]" class="PlanOtros" maxlength="20"
							value="<?php echo($valor);?>"
							style="width: 20px; text-align: center; text-transform: uppercase;"
							title="<?php echo($codig);?>" /> <input type="checkbox"
							name="chk_<?php echo($codig);?>[]"
							id="chk_<?php echo($codig);?>[]" class="PlanOtros" maxlength="20"
							value="1" <?php if($valor=='1'){echo("checked");}?>
							title="<?php echo($codig);?>"
							onclick="ActivarOtrosServ('<?php echo($codig);?>');"
							subact="<?php echo($codig);?>" /> <input type="text"
							name="val_<?php echo($codig);?>[]"
							id="val_<?php echo($codig);?>[]" class="PlanOtros" maxlength="20"
							value="<?php echo($cont);?>"
							style="width: 50px; text-align: center; text-transform: uppercase;"
							title="<?php echo($codig);?>" /></td>
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
			value="<?php echo($idProy);?>" class="PlanOtros" /> <input
			type="hidden" name="t25_version" value="<?php echo($idVersion);?>"
			class="PlanOtros" /> <input type="hidden" name="t25_anio"
			value="<?php echo($idAnio);?>" class="PlanOtros" /> <input
			type="hidden" name="t25_trim" value="<?php echo($idTrim);?>"
			class="PlanOtros" />

		<script language="javascript">
function ActivarOtrosServ(subact)
{
	$('.PlanOtros:input[subact="'+subact+'"]').each( function(i) {
												  var iTxt = document.getElementsByName("txt_"+subact+"[]")[i];
												   if(iTxt.className=="PlanOtros")
												    { iTxt.value = (this.checked ? "1" : "0"); }
												  } ) ;
}

function LoadProv2()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.PlanOtros').val();
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
	$('#cboprov.PlanOtros').html('<option> Cargando ... </option>');
	$('#cbodist.PlanOtros').html('');
	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function ProvSuccessCallback2(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cboprov.PlanOtros').html(respuesta);
  if($("#cboprov.PlanOtros" ).val()=="")
  {
  	$('#cboprov.PlanOtros').focus();
  }
  else
  {
  	LoadDist2();
  }
}
function LoadDist2()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.PlanOtros').val() + "&prov=" + $('#cboprov.PlanOtros').val() ;
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
	$('#cbodist.PlanOtros').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function DistSuccessCallback2(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbodist.PlanOtros').html(respuesta);
  
  if($("#cbodist.PlanOtros").val()=="")
  {
  	$('#cboprov.PlanOtros').focus();
  }
  else
  {
  	LoadCase2();
  }
  
  $('#cbodist.PlanOtros').focus();
}
function LoadCase2()
{
	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto.PlanOtros').val() + "&prov=" + $('#cboprov.PlanOtros').val()+ "&dist=" + $('#cbodist.PlanOtros').val() ;
	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
	$('#cbocase.PlanOtros').html('<option> Cargando ... </option>');
	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
function CaseSuccessCallback2(req)
{
  var respuesta = req.xhRequest.responseText;
  $('#cbocase.PlanOtros').html(respuesta);
  $('#cbocase.PlanOtros').focus();
}
</script>

		<script language="javascript" type="text/javascript">
function ReLoadPlanesOtros()
{
	var BodyForm = "action=<?php echo(md5("ListaPlanOtros"));?>&idProy=<?php echo($idProy);?>&idAnio=<?php echo($idAnio);?>&idTrim=<?php echo($idTrim);?>&t25_ver_inf="+$('#t25_ver_inf').val()+"&dpto=" + $('#cbodpto.PlanOtros').val() +"&prov=" + $('#cboprov.PlanOtros').val() +"&dist=" + $('#cbodist.PlanOtros').val() +"&case=" + $('#cbocase.PlanOtros').val() ;
	var sURL = "inf_trim_plan_otros.php";
	$('#divPlanOtros').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessPlanOtros, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorLoad });
}

function Guardar_PlanOtros()
{
<?php $ObjSession->AuthorizedPage(); ?>	

var BodyForm=$("#FormData .PlanOtros").serialize();
if(confirm("Estas seguro de Guardar el avance en Otros Servicios para el informe Trimestral ?"))
{
    var sURL = "inf_trim_process.php?action=<?php echo(md5('save_plan_otros'));?>";
    var req = Spry.Utils.loadURL("POST", sURL, true, PlanOtrosSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}
}
function PlanOtrosSuccessCallback(req)
{ 
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
    alert(respuesta.replace(ret,""));
	ReLoadPlanesOtros();
  }
  else
  {alert(respuesta);}  
}

</script>

<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>