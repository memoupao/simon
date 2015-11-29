<?php
/**
 * CticServices
 *
 * Permite la edición del Plan de Otras Actividades para el Informe de Entregable
 *
 * @package     sme/proyectos/informes
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require_once (constant("PATH_CLASS") . "BLInformes.class.php");
require_once (constant("PATH_CLASS") . "BLPOA.class.php");
require_once (constant("PATH_CLASS") . "BLBene.class.php");
require_once (constant('PATH_CLASS') . "BLTablasAux.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$anio = $objFunc->__Request('anio');
$idEntregable = $objFunc->__Request('idEntregable');
$dpto = $objFunc->__Request('dpto');
$prov = $objFunc->__Request('prov');
$dist = $objFunc->__Request('dist');
$case = $objFunc->__Request('case');

if ($idProy == "") {
    ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Plan de Asistencia Técnica</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
?>
        <table width="1000" border="0" cellspacing="0" cellpadding="0">
			<tr>
			    <th width="60%">
				    <b>Avance en Otras Actividades a Beneficiarios</b>
			    </th>
			    <th width="35%" rowspan="2">
				    <button onclick="Guardar_PlanOtros(); return false;" class="boton">Guardar</button>
				    <button onclick="LoadPlanOtrosList(); return false;" class="boton">Refrescar</button>
			    </th>
			</tr>
			<tr>
				<th align="left">
					<table width="300" border="0" cellspacing="2" class="TableEditReg">
						<tr>
							<td align="center">Departamento</td>
							<td align="center">Provincia</td>
							<td align="center">Distrito</td>
							<td align="center">Caserio</td>
						</tr>
						<tr>
							<td>
							    <select name="cbodpto_ot" id="cbodpto_ot" class="PlanOtros PlanOtrosList cmbnormal">
                                <?php
                                $objBene = new BLBene();
                                $rsDpto = $objBene->ListaUbigeoDpto($idProy);
                                $dpto1 = $objFunc->llenarComboI($rsDpto, 'iddpto', 'dpto', $dpto);
                                if ($dpto1 != $dpto) {
                                    $dpto = $dpto1;
                                }
                                ?>
                                </select>
                            </td>
							<td>
							    <select name="cboprov_ot" id="cboprov_ot" class="PlanOtros PlanOtrosList cmbnormal">
									<option value="" selected="selected"></option>
                                    <?php
                                    $objBene = new BLBene();
                                    $rsDpto = $objBene->ListaUbigeoProv($idProy, $dpto);
                                    $prov1 = $objFunc->llenarComboI($rsDpto, 'idprov', 'prov', $prov);
                                    ?>
                                </select>
                            </td>
							<td>
							    <select name="cbodist_ot" id="cbodist_ot" class="PlanOtros PlanOtrosList cmbnormal">
									<option value="" selected="selected"></option>
                                    <?php
                                    $objTablas = new BLTablasAux();
                                    $rsProv = $objTablas->ListaDistritos($dpto, $prov);
                                    $objFunc->llenarComboI($rsProv, 'codigo', 'descripcion', $dist);
                                    ?>
                                </select>
                            </td>
							<td>
							    <select name="cbocase_ot" id="cbocase_ot" class="PlanOtros PlanOtrosList cmbnormal">
									<option value="" selected="selected"></option>
                                    <?php
                                    $rsCase = $objTablas->ListaCaserios($dpto, $prov, $dist);
                                    $objFunc->llenarComboI($rsCase, 'codigo', 'descripcion', $case);
                                    ?>
                                </select>
                            </td>
						</tr>
					</table>
				</th>
			</tr>
		</table>

		<div class="TableGrid" id="PlanOtrosTableGrid" style="overflow: auto; max-width: 880px; max-height: 350px;"></div>

		<input type="hidden" name="idProy" value="<?php echo($idProy);?>" class="PlanOtros PlanOtrosList" />
		<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" class="PlanOtros PlanOtrosList" />
		<input type="hidden" name="anio" value="<?php echo($anio);?>" class="PlanOtros PlanOtrosList" />
		<input type="hidden" name="idEntregable" value="<?php echo($idEntregable);?>" class="PlanOtros PlanOtrosList" />

		<script language="javascript">
		$(document).ready(function(){
	        LoadPlanOtrosList();
    		$('#cbodpto_ot').change(function(pEvent) {
    			LoadPlanOtrosList();
    			LoadProvOtros();
    		});

    		$('#cboprov_ot').change(function(pEvent) {
    			LoadPlanOtrosList();
    			if ($(pEvent.target).val()) {
    				LoadDistOtros();
    			}
    			else {
    				$('#cbodist_ot').html('');
    				$('#cbocase_ot').html('');
    			}
    		});

    		$('#cbodist_ot').change(function(pEvent) {
    			LoadPlanOtrosList();
    			if ($(pEvent.target).val())
    				LoadCaseOtros();
    			else
    				$('#cbocase_ot').html('');
    		});

    		$('#cbocase_ot').change(function(pEvent) {
    			LoadPlanOtrosList();
    		});
    	});

		function LoadPlanOtrosList()
    	{
    		var aQueryString = $('.PlanOtrosList').serialize();
    		$('#PlanOtrosTableGrid').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>")
    								.load('inf_entregable_plan_otros_list.php?' + aQueryString);
    	}

        function ActivarOtrosServ(subact)
        {
        	$('.PlanOtros:input[subact="'+subact+'"]').each( function(i) {
			  var iTxt = document.getElementsByName("txt_"+subact+"[]")[i];
			   if(iTxt.className=="PlanOtros")
			    { iTxt.value = (this.checked ? "1" : "0"); }
			  } ) ;
        }

        function LoadProvOtros()
        {
        	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto_ot').val();
        	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
        	$('#cboprov_ot').html('<option value=""> Cargando ... </option>');
        	$('#cbodist_ot').html('');
        	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        }

        function ProvSuccessCallback2(req)
        {
          var respuesta = req.xhRequest.responseText;
          $('#cboprov_ot').html(respuesta);
          if($("#cboprov_ot" ).val()=="")
          {
          	$('#cboprov_ot').focus();
          }
          else
          {
          	LoadDistOtros();
          }
        }

        function LoadDistOtros()
        {
        	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto_ot').val() + "&prov=" + $('#cboprov_ot').val() ;
        	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>" ;
        	$('#cbodist_ot').html('<option value=""> Cargando ... </option>');
        	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        }

        function DistSuccessCallback2(req)
        {
          var respuesta = req.xhRequest.responseText;
          $('#cbodist_ot').html(respuesta);

          if($("#cbodist_ot").val()=="")
          {
          	$('#cboprov_ot').focus();
          }
          else
          {
          	LoadCaseOtros();
          }

          $('#cbodist_ot').focus();
        }

        function LoadCaseOtros()
        {
        	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto_ot').val() + "&prov=" + $('#cboprov_ot').val()+ "&dist=" + $('#cbodist_ot').val() ;
        	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
        	$('#cbocase.PlanOtros').html('<option value=""> Cargando ... </option>');
        	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback2, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        }

        function CaseSuccessCallback2(req)
        {
          var respuesta = req.xhRequest.responseText;
          $('#cbocase_ot').html(respuesta);
          $('#cbocase_ot').focus();
        }

        function Guardar_PlanOtros()
        {
        <?php $ObjSession->AuthorizedPage(); ?>

        var BodyForm=$("#FormData .PlanOtros").serialize();
        if(confirm("Estas seguro de Guardar el avance en Otros Servicios?"))
        {
            var sURL = "inf_entregable_process.php?action=<?php echo(md5('save_plan_otros'));?>";
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
                LoadPlanOtrosList();
            }
            else
            {alert(respuesta);}
        }

    </script>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>