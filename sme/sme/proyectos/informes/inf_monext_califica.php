<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLInformes.class.php");
require (constant("PATH_CLASS") . "BLTablasAux.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__POST('idProy');
$anio = $objFunc->__POST('anio');
$idEntregable = $objFunc->__POST('idEntregable');

if ($idProy == "" && $idNum == "" && $idTrim == "") {
    $idProy = $objFunc->__GET('idProy');
    $anio = $objFunc->__GET('anio');
    $idEntregable = $objFunc->__GET('idEntregable');
}

if ($idProy == "") {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title>Calificaciones</title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<form id="frmMain" name="frmMain" method="post" action="#">
<?php
}
$objInf = new BLInformes();
$row = $objInf->getInfSE($idProy, $anio, $idEntregable);
?>
        <table width="750" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width="82%" class="TableEditReg">&nbsp;</td>
				<td width="8%" rowspan="2" align="center" class="TableEditReg">
				    <input type="button" value="Refrescar" onclick="LoadCalificacion(true);" class="btn_save_custom" />
			    </td>
				<td width="10%" rowspan="2" align="right" valign="middle">
				    <input type="button" id='saveCalifBtn' value="Guardar" onclick="Guardar_Calificacion();" class="btn_save_custom" />
			    </td>
			</tr>
			<tr>
				<td><b>Aspectos Cualitativos</b></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><table width="100%" border="0"
								cellpadding="0" cellspacing="0" class="TableEditReg">
								<tr>
									<td width="71%">Características del trabajo del equipo técnico</td>
									<td width="29%" align="center"><b>Valoración</b></td>
								</tr>
								<tr>
									<td align="left">Registro adecuado de la participación de los beneficiarios en las actividades del proyecto.</td>
									<td align="center">
									    <select name="t30_crit_eva1" class="Boton"
										id="t30_crit_eva1" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $objTablas = new BLTablasAux();
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva1'], 'cod_ext');
                                            ?>
                                        </select>
                                    </td>
								</tr>
								<tr>
									<td align="left">Capacidad de atender a los beneficiarios en el ámbito de ejecución de parte del equipo técnico.</td>
									<td align="center"><select name="t30_crit_eva2" class="Boton"
										id="t30_crit_eva2" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva2'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Opinión de los beneficiarios respecto a los servicios prestados por el personal del proyecto.</td>
									<td align="center"><select name="t30_crit_eva3" class="Boton"
										id="t30_crit_eva3" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva3'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Disponibilidad de los medios de verificación de los indicadores del proyecto.</td>
									<td align="center"><select name="t30_crit_eva4" class="Boton"
										id="t30_crit_eva4" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva4'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								
								<?php /*?>
								<tr>
									<td align="left">Calidad&nbsp; y congruencia&nbsp; (capacidad
										de cobertura del ámbito del proyecto) del equipo técnico</td>
									<td align="center"><select name="t30_crit_eva5" class="Boton"
										id="t30_crit_eva5" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva5'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Opinión de los beneficiarios respecto al
										proyecto y sus resultados</td>
									<td align="center"><select name="t30_crit_eva6" class="Boton"
										id="t30_crit_eva6" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva6'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Manejo adecuado del Proyecto</td>
									<td align="center"><select name="t30_crit_eva7" class="Boton"
										id="t30_crit_eva7" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_eva7'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								
								<?php */?>
								
								
                                <?php
                                $aPuntaje = $row['puntaje'];
                                $aprobado = $objFunc->calificacionInforme($aPuntaje);
                                ?>
                                <tr>
									<td align="right"><b>RESULTADO</b></td>
									<td align="center"><span id="spanTotResult"><?php echo $aPuntaje; ?></span>
										&nbsp;&nbsp; <span id="spanTotResult2"><?php echo $aprobado; ?></span>
									</td>
								</tr>
							</table> <br></td>
					</tr>
					
					
					<tr>
						<td align="left" valign="middle">
						<br/>
						    <b>Calificación</b>						   
						    <br/> 
							<select name="t30_crit_final" class="Boton" id="t30_crit_final" style="width: 150px;" onchange="CalcularResultadoFinal();">
								<option value="" selected="selected"></option>
                                <?php
                                	$rs = $objTablas->getValoresCalificacionSE();
                                    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t30_crit_final'], 'cod_ext');
                                ?>
                            </select>
                            <br/><br/>
						</td>
					</tr>
					
					<tr>
						<td align="left" valign="middle">
						    <b>Sustento de Calificación</b>
						    <br/>
							<textarea name="t30_califica" id="t30_califica" class="obs"><?php echo($row['t30_califica']);?></textarea>
						</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>

			<input type="hidden" name="idProy" id="idProy" value="<?php echo($idProy);?>" />
			<input type="hidden" name="anio" id="anio" value="<?php echo($anio);?>" />
            <input type="hidden" name="idEntregable" id="idEntregable" value="<?php echo($idEntregable);?>" />

			<script language="javascript" type="text/javascript">
            $(document).ready(function(){
            	if ($('#pageMode').val() == 'view')
            		$('#saveCalifBtn').attr({disabled:'disabled'});
            });

            function Guardar_Calificacion()
            {
                <?php $ObjSession->AuthorizedPage(); ?>

                var arrParams = new Array();
        		arrParams[0] = "idProy=" + $("#idProy").val();
        		arrParams[1] = "anio=" + $("#anio").val();
        		arrParams[2] = "idEntregable=" + $("#idEntregable").val();
        		arrParams[3] = "t30_crit_eva1=" + $("#t30_crit_eva1").val();
        		arrParams[4] = "t30_crit_eva2=" + $("#t30_crit_eva2").val();
        		arrParams[5] = "t30_crit_eva3=" + $("#t30_crit_eva3").val();
        		arrParams[6] = "t30_crit_eva4=" + $("#t30_crit_eva4").val();
        		arrParams[8] = "t30_crit_final="+$("#t30_crit_final").val();        		
        		arrParams[10] = "t30_califica=" + $("#t30_califica").val();

                var BodyForm = arrParams.join("&");

                if(confirm("Estas seguro de Guardar las calificaciones, para el Informe de Supervisión?"))
                {
                	var sURL = "inf_monext_process.php?action=<?php echo(md5('ajax_calificacion'));?>";
                	var req = Spry.Utils.loadURL("POST", sURL, true, CalificacionesSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
                }
            }

            function CalificacionesSuccessCallback	(req)
            {
                var respuesta = req.xhRequest.responseText;
                respuesta = respuesta.replace(/^\s*|\s*$/g,"");
                var ret = respuesta.substring(0,5);

                if(ret=="Exito")
                {
                    LoadCalificacion(true);
                    alert(respuesta.replace(ret,""));
                }
                else
                {alert(respuesta);}
            }

            function CalcularResultado()
            {
            	var eva1 = $("#t30_crit_eva1 option[value='"+$("#t30_crit_eva1").val()+"']").attr("title");
            	var eva2 = $("#t30_crit_eva2 option[value='"+$("#t30_crit_eva2").val()+"']").attr("title");
            	var eva3 = $("#t30_crit_eva3 option[value='"+$("#t30_crit_eva3").val()+"']").attr("title");
            	var eva4 = $("#t30_crit_eva4 option[value='"+$("#t30_crit_eva4").val()+"']").attr("title");
            	
            	if(isNaN(eva1) || eva1==null || eva1==''){eva1=0;}
            	if(isNaN(eva2) || eva2==null || eva2==''){eva2=0;}
            	if(isNaN(eva3) || eva3==null || eva3==''){eva3=0;}
            	if(isNaN(eva4) || eva4==null || eva4==''){eva4=0;}
            	            
            	var resultado = parseInt(eva1) + parseInt(eva2) + parseInt(eva3) + parseInt(eva4);
            	var resultext = "";
            	if(resultado < 3) {resultext = "Mala" ;}
            	if(resultado >= 3 && resultado <= 5) {resultext = "Regular";}
            	if(resultado > 5) {resultext = "Buena"; }
				
            	$("#spanTotResult").html(resultado);
            	$("#spanTotResult2").html(resultext);
            }

            function CalcularResultadoFinal()
            {
            	var evaFinal = $("#t30_crit_final option[value='"+$("#t30_crit_final").val()+"']").attr("title");
            	if(isNaN(evaFinal) || evaFinal==null || evaFinal==''){
               		evaFinal=0;
               	}
            	var resultado = parseInt(evaFinal);
            	            	
            	
            }

            CalcularResultado();
            CalcularResultadoFinal();
            </script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>