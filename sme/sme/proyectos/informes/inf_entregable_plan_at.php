<?php
/**
 * CticServices
 *
 * Permite la edición del Plan de Asistencia Técnica para el Informe de Entregable
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
				    <b>Avance en Asistencia Técnica a Beneficiarios</b>
			    </th>
			    <th width="35%" rowspan="2">
				    <button onclick="Guardar_PlanAT(); return false;" class="boton">Guardar</button>
				    <button onclick="LoadPlanATList(); return false;" class="boton">Refrescar</button>
				    <button onclick="ExportPlanesAT(); return false;" class="boton">Exportar</button>
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
							    <select name="cbodpto_at" id="cbodpto_at" class="PlanAT PlanATList cmbnormal">
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
							    <select name="cboprov_at" id="cboprov_at" class="PlanAT PlanATList cmbnormal">
									<option value="" selected="selected"></option>
                                    <?php
                                    $objBene = new BLBene();
                                    $rsDpto = $objBene->ListaUbigeoProv($idProy, $dpto);
                                    $prov1 = $objFunc->llenarComboI($rsDpto, 'idprov', 'prov', $prov);
                                    ?>
                                </select>
                            </td>
						    <td>
						        <select name="cbodist_at" id="cbodist_at" class="PlanAT PlanATList cmbnormal">
									<option value="" selected="selected"></option>
                                    <?php
                                    $objTablas = new BLTablasAux();
                                    $rsProv = $objTablas->ListaDistritos($dpto, $prov);
                                    $objFunc->llenarComboI($rsProv, 'codigo', 'descripcion', $dist);
                                    ?>
                                </select>
                            </td>
							<td>
							    <select name="cbocase_at" id="cbocase_at" class="PlanAT PlanATList cmbnormal">
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

		<div class="TableGrid" id='PlanATTableGrid' style="overflow: auto; max-width: 880px; max-height: 350px;"></div>

		<input type="hidden" name="idProy" value="<?php echo($idProy);?>" class="PlanAT PlanATList" />
		<input type="hidden" name="idVersion" value="<?php echo($idVersion);?>" class="PlanAT PlanATList" />
		<input type="hidden" name="anio" value="<?php echo($anio);?>" class="PlanAT PlanATList" />
		<input type="hidden" name="idEntregable" value="<?php echo($idEntregable);?>" class="PlanAT PlanATList" />

		<script language="javascript">
		$(document).ready(function(){
	        LoadPlanATList();
    		$('#cbodpto_at').change(function(pEvent) {
    			LoadPlanATList();
    			LoadProvAT();
    		});

    		$('#cboprov_at').change(function(pEvent) {
    			LoadPlanATList();
    			if ($(pEvent.target).val()) {
    				LoadDistAT();
    			}
    			else {
    				$('#cbodist_at').html('');
    				$('#cbocase_at').html('');
    			}
    		});

    		$('#cbodist_at').change(function(pEvent) {
    			LoadPlanATList();
    			if ($(pEvent.target).val())
    				LoadCaseAT();
    			else
    				$('#cbocase_at').html('');
    		});

    		$('#cbocase').change(function(pEvent) {
    			LoadPlanATList();
    		});
    	});

		function LoadPlanATList()
    	{
    		var aQueryString = $('.PlanATList').serialize();

    		$('#PlanATTableGrid').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>")
    								.load('inf_entregable_plan_at_list.php?' + aQueryString);
    	}

        function ActivarPlanAT(subact)
        {
        	$('.PlanAT:input[subact="'+subact+'"]').each( function(i) {
    		  var iTxt = document.getElementsByName("txt_"+subact+"[]")[i];
    		    if(iTxt.className=="PlanAT")
    		    { iTxt.value = (this.checked ? "1" : "0"); }
    		  } ) ;
        }

        function LoadProvAT()
        {
        	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto_at').val();
        	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_provincias"))?>" ;
        	$('#cboprov_at').html('<option value=""> Cargando ... </option>');
        	$('#cbodist_at').html('');
        	var req = Spry.Utils.loadURL("POST", sURL, true, ProvSuccessCallback1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        }

        function ProvSuccessCallback1(req)
        {
            var respuesta = req.xhRequest.responseText;
            $('#cboprov_at').html(respuesta);

            if($("#cboprov_at" ).val()=="")
            {
                $('#cboprov_at').focus();
            }
            else
            {
                LoadDistAT();
            }
        }

        function LoadDistAT()
        {
        	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto_at').val() + "&prov=" + $('#cboprov_at').val() ;
        	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_distritos"))?>";
        	$('#cbodist_at').html('<option value=""> Cargando ... </option>');
        	var req = Spry.Utils.loadURL("POST", sURL, true, DistSuccessCallback1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        }
        function DistSuccessCallback1(req)
        {
            var respuesta = req.xhRequest.responseText;
            $('#cbodist_at').html(respuesta);

            if($("#cbodist_at").val()=="")
            {
                $('#cboprov_at').focus();
            }
            else
            {
                LoadCaseAT();
            }

            $('#cbodist_at').focus();
        }

        function LoadCaseAT()
        {
        	var BodyForm = "idproy=<?php echo($idProy);?>&dpto=" + $('#cbodpto_at').val() + "&prov=" + $('#cboprov_at').val()+ "&dist=" + $('#cbodist_at').val() ;
        	var sURL = "../anexos/amb_geo_process.php?action=<?php echo(md5("lista_caserios"))?>" ;
        	$('#cbocase_at').html('<option value=""> Cargando ... </option>');
        	var req = Spry.Utils.loadURL("POST", sURL, true, CaseSuccessCallback1, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
        }
        function CaseSuccessCallback1(req)
        {
          var respuesta = req.xhRequest.responseText;
          $('#cbocase_at').html(respuesta);
          $('#cbocase_at').focus();
        }

        function Guardar_PlanAT()
        {
            <?php $ObjSession->AuthorizedPage(); ?>

            var BodyForm=$("#FormData .PlanAT").serialize();
            if(confirm("Estas seguro de Guardar el avance en Asistencia Tecnica?"))
            {
                var sURL = "inf_entregable_process.php?action=<?php echo(md5('save_plan_at'));?>";
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
            LoadPlanATList();
          }
          else
          {alert(respuesta);}
        }

        function ExportPlanesAT()
        {
        	var params = "&idProy=<?php echo($idProy);?>&anio=<?php echo($anio);?>&idEntregable=<?php echo($idEntregable);?>";
        	var url = "<?php echo(constant("PATH_RPT"))?>reportviewer.php?ReportID=45" + params;
        	var win =  window.open(url, "wrpt_planAT", "fullscreen,scrollbars");
            win.focus();
        }
        </script>
		<fieldset style="color: navy; display: none;">
			<legend class="nota">Opciones</legend>
			<b>C</b> : Capacitado &nbsp;&nbsp;|&nbsp;&nbsp;
			<b>P</b> : En Proceso &nbsp;&nbsp;|&nbsp;&nbsp;
			<b>R</b> : Retirado <br />
			<span class="nota">Colocar en los cuadros de Texto sólo una de las 3 opciones, cualquier otro valor no sera tomado en cuenta.</span>
		</fieldset>
<?php if($idProy=="") { ?>
    </form>
</body>
</html>
<?php } ?>