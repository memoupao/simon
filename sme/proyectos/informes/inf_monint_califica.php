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
$row = $objInf->getInfSI($idProy, $anio, $idEntregable);
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
				<td><b>Calificación</b></td>
			</tr>
		</table>
		<div id="divTableLista" class="TableGrid">
			<table width="750" cellpadding="0" cellspacing="0">
				<tbody class="data" bgcolor="#FFFFFF">
					<tr>
						<td align="left" valign="middle"><table width="100%" border="0"
								cellpadding="0" cellspacing="0" class="TableEditReg">
								<tr>
									<td width="71%">&nbsp;</td>
									<td width="29%" align="center"><b>Valoración</b></td>
								</tr>
								<tr>
									<td align="left">Relación planificado y ejecutado operativo</td>
									<td align="center">
									    <select name="t45_crit_eva1" class="Boton"
										id="t45_crit_eva1" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $objTablas = new BLTablasAux();
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva1'], 'cod_ext');
                                            ?>
                                        </select>
                                    </td>
								</tr>
								<tr>
									<td align="left">Relación entre ejecución financiera y
										ejecución técnica.</td>
									<td align="center"><select name="t45_crit_eva2" class="Boton"
										id="t45_crit_eva2" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva2'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Avance de actividades críticas</td>
									<td align="center"><select name="t45_crit_eva3" class="Boton"
										id="t45_crit_eva3" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva3'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Calidad de las Capacitaciones</td>
									<td align="center"><select name="t45_crit_eva4" class="Boton"
										id="t45_crit_eva4" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva4'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Calidad&nbsp; y congruencia&nbsp; (capacidad
										de cobertura del ámbito del proyecto) del equipo técnico</td>
									<td align="center"><select name="t45_crit_eva5" class="Boton"
										id="t45_crit_eva5" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva5'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Opinión de los beneficiarios respecto al
										proyecto y sus resultados</td>
									<td align="center"><select name="t45_crit_eva6" class="Boton"
										id="t45_crit_eva6" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva6'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
								<tr>
									<td align="left">Manejo adecuado del Proyecto</td>
									<td align="center"><select name="t45_crit_eva7" class="Boton"
										id="t45_crit_eva7" style="width: 150px;"
										onchange="CalcularResultado();">
											<option value="" selected="selected"></option>
                                            <?php
                                            $rs = $objTablas->ValoraInformesME();
                                            $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $row['t45_crit_eva7'], 'cod_ext');
                                            ?>
                                          </select></td>
								</tr>
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
						<td align="left" valign="middle"><span class="nota" id="mensaje_califica"></span></td>
					</tr>
					<tr>
						<td align="left" valign="middle">
						    <b>Sustento de Calificación</b>
						    <br/>
							<textarea name="t45_califica" id="t45_califica" class="obs"><?php echo($row['t45_califica']);?></textarea>
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
        		arrParams[3] = "t45_crit_eva1=" + $("#t45_crit_eva1").val();
        		arrParams[4] = "t45_crit_eva2=" + $("#t45_crit_eva2").val();
        		arrParams[5] = "t45_crit_eva3=" + $("#t45_crit_eva3").val();
        		arrParams[6] = "t45_crit_eva4=" + $("#t45_crit_eva4").val();
        		arrParams[7] = "t45_crit_eva5=" + $("#t45_crit_eva5").val();
        		arrParams[8] = "t45_crit_eva6=" + $("#t45_crit_eva6").val();
        		arrParams[9] = "t45_crit_eva7=" + $("#t45_crit_eva7").val();
        		arrParams[10] = "t45_califica=" + $("#t45_califica").val();

                var BodyForm = arrParams.join("&");

                if(confirm("Estas seguro de Guardar las calificaciones, para el Informe de Supervisión?"))
                {
                	var sURL = "inf_monint_process.php?action=<?php echo(md5('ajax_calificacion'));?>";
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
            	var eva1 = $("#t45_crit_eva1 option[value='"+$("#t45_crit_eva1").val()+"']").attr("title");
            	var eva2 = $("#t45_crit_eva2 option[value='"+$("#t45_crit_eva2").val()+"']").attr("title");
            	var eva3 = $("#t45_crit_eva3 option[value='"+$("#t45_crit_eva3").val()+"']").attr("title");
            	var eva4 = $("#t45_crit_eva4 option[value='"+$("#t45_crit_eva4").val()+"']").attr("title");
            	var eva5 = $("#t45_crit_eva5 option[value='"+$("#t45_crit_eva5").val()+"']").attr("title");
            	var eva6 = $("#t45_crit_eva6 option[value='"+$("#t45_crit_eva6").val()+"']").attr("title");
            	var eva7 = $("#t45_crit_eva7 option[value='"+$("#t45_crit_eva7").val()+"']").attr("title");

            	if(isNaN(eva1) || eva1==null || eva1==''){eva1=0;}
            	if(isNaN(eva2) || eva2==null || eva2==''){eva2=0;}
            	if(isNaN(eva3) || eva3==null || eva3==''){eva3=0;}
            	if(isNaN(eva4) || eva4==null || eva4==''){eva4=0;}
            	if(isNaN(eva5) || eva5==null || eva5==''){eva5=0;}
            	if(isNaN(eva6) || eva6==null || eva6==''){eva6=0;}
            	if(isNaN(eva7) || eva7==null || eva7==''){eva7=0;}

            	var resultado = parseInt(eva1) + parseInt(eva2) + parseInt(eva3) + parseInt(eva4) + parseInt(eva5) + parseInt(eva6) + parseInt(eva7) ;
            	var resultext = "";
            	if(resultado > 6) {resultext = "Desaprobado" ; $("#mensaje_califica").html("");}
            	if(resultado >= 6 && resultado <= 10) {resultext = "Aprobado con Reservas" ;$("#mensaje_califica").html("");}
            	if(resultado > 10) {resultext = "Aprobado"; $("#mensaje_califica").html("El supervisor considera que el proyecto se encuentra ejecutándose adecuadamente y se prevé que cumpla sus metas en el periodo establecido");}

            	$("#spanTotResult").html(resultado);
            	$("#spanTotResult2").html(resultext);
            }

            CalcularResultado();
            </script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>