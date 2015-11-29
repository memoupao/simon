﻿<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require (constant('PATH_CLASS') . "BLTablas.class.php");
require (constant('PATH_CLASS') . "BLTablasAux.class.php");
require (constant('PATH_CLASS') . "BLProyecto.class.php");
require (constant('PATH_CLASS') . "BLEjecutor.class.php");
require (constant('PATH_CLASS') . "HardCode.class.php");
require (constant('PATH_CLASS') . "Mail.class.php");

$objProy = new BLProyecto();
$OjbTab = new BLTablasAux();
$HC = new HardCode();

$view = $objFunc->__GET('mode');
$accion = $objFunc->__GET('accion');
$isUnsuspendFlg = false;

$row = 0;
$row_aprob_proy = 0;

// -------------------------------------------------->
// DA 2.0 [23-10-2013 15:36]
// Valores por defecto cuando se adicione un NUEVO proyecto.
$t02_gratificacion = $HC->gratificacion;
$t02_porc_cts = $HC->porc_CTS;
$t02_porc_ess = $HC->porc_ESS;
$t02_porc_gast_func = $HC->Porcent_Gast_Func;
$t02_porc_linea_base = $HC->Porcent_Linea_Base;
$t02_porc_imprev = $HC->Porcent_Imprevistos;
$t02_proc_gast_superv = $HC->porcentGastSupervProy;
// --------------------------------------------------<

if ($view == md5("ajax_edit")) {
    if ($accion == md5("editar")) {
        $objFunc->SetSubTitle("Editando Proyecto");
    } else {
        $objFunc->SetSubTitle("Ver Proyecto");
    }

    $id = $objFunc->__GET('idproy');
    $vs = $objFunc->__GET('vs');

    $row = $objProy->ProyectoSeleccionar($id, $vs);

    $t02_cod_proy = $row['t02_cod_proy'];
    $t02_version = $row['t02_version'];
    $t02_nro_exp = $row['t02_nro_exp'];

    // -------------------------------------------------->
    // DA 2.0 [29-10-2013 11:53]
    // Nueva variable que captura el valor de t00_cod_linea para mostrarse como
    // seleccionado en la lista select
    $t00_cod_linea = $row['t00_cod_linea'];
    // --------------------------------------------------<

    $t01_id_inst = $row['t01_id_inst'];
    // -------------------------------------------------->
    // DA 2.0 [29-10-2013 11:53]
    // Nueva variable de Cuenta Bancaria del Proyecto
    // $t01_id_cta = $row['cuenta_bancaria'];
    // --------------------------------------------------<
    $t02_nom_proy = $row['t02_nom_proy'];
    $t02_fch_apro = $row['apro'];
    $t02_fch_ini = $row['ini'];
    $t02_fch_ter = $row['fin'];
    $t02_fin = $row['t02_fin'];
    $t02_pro = $row['t02_pro'];
    $t02_ben_obj = $row['t02_ben_obj'];

    // -------------------------------------------------->
    // DA 2.0 [29-10-2013 15:22]
    // Campos no seran tomados en cuenta y seran eliminados
    /*
     * $t02_amb_geo 	=$row['t02_amb_geo']; $t02_pres_fe	=$row['t02_pres_fe']; $t02_pres_eje =$row['t02_pres_eje']; $t02_pres_otro	=$row['t02_pres_otro']; $t02_pres_tot	=$row['t02_pres_tot'];
     */
    // $t02_moni_fina =$row['t02_moni_fina'];
    $t02_moni_fina = 0;
    // $t02_sup_inst =$row['t02_sup_inst'];
    $t02_sup_inst = 0;
    // --------------------------------------------------<

    $t02_moni_tema = $row['t02_moni_tema'];
    $t02_moni_ext = $row['t02_moni_ext'];
    $t02_dire_proy = $row['t02_dire_proy'];
    $t02_ciud_proy = $row['t02_ciud_proy'];
    $t02_tele_proy = $row['t02_tele_proy'];
    $t02_fax_proy = $row['t02_fax_proy'];
    $t02_mail_proy = $row['t02_mail_proy'];
    $t02_fch_isc = $row['isc'];
    $t02_fch_ire = $row['ire'];
    $t02_fch_tre = $row['tre'];
    $t02_fch_tam = $row['tam'];
    $t02_num_mes = $row['mes'];
    $t02_num_mes_amp = $row['t02_num_mes_amp'];
    $t02_estado = $row['t02_estado'];
    $usr_crea = $row['usr_crea'];
    $fch_crea = $row['fch_crea'];
    $usr_actu = $row['usr_actu'];
    $fch_actu = $row['fch_actu'];
    $est_audi = $row['est_audi'];
    // -------------------------------------------------->
    // DA 2.0 [29-10-2013 15:22]
    // Nuevo campo de Sectores
    //$t02_sector_main = $row['t02_sect_main'];
    //$t02_prod_promovido = $row['t02_prod_promovido'];
    // --------------------------------------------------<
    //$t02_sector = $row['t02_sect_prod'];
    //$t02_subsector = $row['t02_subsect_prod'];

    $t02_cre_fe = $row['t02_cre_fe'];
    $t02_cta_banco = $row['t01_id_cta'];
    $env_rev = $row['env_rev'];
    $t02_vb_proy = $row['t02_vb_proy'];
    $t02_aprob_proy = $row['t02_aprob_proy'];

    // -------------------------------------------------->
    // AQ 2.0 [20-10-2013 11:07]
    // Aprobación de Proyecto
    $btnAprobProy = "";
    $chkVBProy = "";
    if ($ObjSession->PerfilID != $HC->SP) {
        $btnAprobProy = 'style="display:none;"';
        $chkVBProy = " disabled";
    }

    if($t02_vb_proy == '1'){
        $chkVBProy .= " checked";
    }
    // --------------------------------------------------<

    // $t02_nom_benef =$row['t02_nom_benef'];

    // $objProy = NULL;
    // Se va a modificar el registro !!

    // -------------------------------------------------->
    // DA 2.0 [23-10-2013 15:36]:
    // Nuevas variables para las tasas para el proyecto en el momento de edicion:
    // DA 2.0 [08-11-2013 11:33]:
    // Nueva tasa de gastos de supervisión del proyecto
    $t02_gratificacion = $row['t02_gratificacion'];
    $t02_porc_cts = $row['t02_porc_cts'];
    $t02_porc_ess = $row['t02_porc_ess'];
    $t02_porc_gast_func = $row['t02_porc_gast_func'];
    $t02_porc_linea_base = $row['t02_porc_linea_base'];
    $t02_porc_imprev = $row['t02_porc_imprev'];
    $t02_proc_gast_superv = $row['t02_proc_gast_superv'];
    // --------------------------------------------------<
    
    
    // -------------------------------------------------->
    // DA 2.1 [19-04-2014 19:13]:
    // Nuevo campo de Instituciones asociadas o colaboradoras [RF-001]:
    $t02_inst_asoc = $row['t02_inst_asoc'];
    // --------------------------------------------------<    
    
    
} else {
    $objFunc->SetSubTitle("Nuevo Proyecto");
}

if($view=='') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$objFunc->SetTitle("Proyectos FONDOEMPLEO");
$objFunc->verifyAjax();
    if (! $objFunc->Ajax) {
        ?>
    <meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
    <meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo($objFunc->Title);?></title>
    <link href="../../../css/template.css" rel="stylesheet" media="all" />
    <link href="../../../jquery.ui-1.5.2/themes/ui.datepicker.css" rel="stylesheet" type="text/css" />
    <script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
    <script src="../../../jquery.ui-1.5.2/ui/ui.datepicker.js" type="text/javascript"></script>
    <style type="text/css">
        #toolbar .Button {
        	color: #000;
        }
    </style>
<?php } ?>
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div id="mainContent">
			<form id="FormData" method="post"
				enctype="application/x-www-form-urlencoded"
				action="<?php echo($_SERVER['PHP_SELF']);?>">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="1%">&nbsp;</td>
						<td width="73%">&nbsp;</td>
						<td width="26%">&nbsp;</td>
					</tr>
					<tr>
						<td height="18">&nbsp;</td>
						<td><b style="text-decoration: underline"> </b> &nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>
				<div id="divContent">
<?php } ?>
                    <script src="../../../jquery.ui-1.5.2/jquery.maskedinput.js" type="text/javascript"></script>
					<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
					<script src="../../../js/commons.js" type="text/javascript"></script>
					<br />
					<div id="EditForm" style="border: solid 1px #D3D3D3;">
						<div id="toolbar" style="height: 4px;" class="BackColor">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td width="8%"><button class="Button" id="btnGuardar" onclick="btnGuardar_Clic(); return false;">Guardar</button></td>
									<td width="13%"><button class="Button" onclick="btnCancelar_Clic(); return false;">Volver y Cerrar</button></td>
									<td width="13%">
									    <?php if ($t02_aprob_proy == '1'){ ?>
                    				        Proyecto Aprobado
									    <?php
									    }
									    else{ ?>
                                            <button class="Button" onclick="btnAprobar(); return false;" <?php echo($btnAprobProy);?>>Aprobar Proyecto</button>
                                        <?php }?>
								    </td>
									<td width="40%" colspan="3">&nbsp;</td>
									<td width="25%" align="right"><?php echo($objFunc->SubTitle);?></td>
								</tr>
							</table>
						</div>	<?php
    /* modificado 29/11/2011 */
    $usrEva = "";
    $userEjec = "";
    // -------------------------------------------------->
    // AQ 2.0 [20-10-2013 11:07]
    // Ahora para el SP tiene acceso inicial.
    //if ($ObjSession->PerfilID == $HC->EVA || $ObjSession->PerfilID == $HC->GP) {
    // --------------------------------------------------<
    if ($ObjSession->PerfilID == $HC->GP) {
        $usrEva = "readonly='readonly'";
    } else
        if ($ObjSession->PerfilID == $HC->Ejec) {
            $userEjec = "readonly='readonly'";
        }
    ?>
  <table width="760" border="0" cellpadding="0" cellspacing="0"
							class="TableEditReg">
							<tr>
								<td colspan="6">
									<fieldset>
										<legend>Datos Generales</legend>
										<table border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg">
											<tr>
												<td width="132" valign="bottom">Expediente</td>
												<td colspan="4" valign="bottom">Ejecutor</td>
		  <?php
    if ($t02_estado == $HC->Proy_PorIniciar) {
        ?>
          	<td valign="bottom">
		 	<?php
            if ($view == md5("ajax_edit")) { ?>
                <input type='checkbox' name='chkVB' id='chkVB' <?php echo($chkVBProy);?>/> V°B° SP
            <?php
            }
        ?>
		  </td>
		  <?php
    } else
        if ($t02_estado == $HC->Proy_Ejecucion) {
            ?>
		  <td><input type='checkbox' name='chkVB' id='chkVB' <?php echo($chkVBProy);?>/>V°B° SP</td>
		  <?php }?>
        </tr>
		<tr>
			<td height="29">
			    <input name="t02_nro_exp" type="text" id="t02_nro_exp" value="<?php echo($t02_nro_exp); ?>" size="15" maxlength="5" class="Proyectos" />
		     </td>
			<td colspan="3">
			    <select name="t01_id_inst" id="t01_id_inst" style="width: 240px" <?php if($t02_cod_proy != "") {echo('disabled');} ?> class="Proyectos">
					<option></option>
            <?php
                $rs = $objProy->ListaEjecutores();
                $objFunc->llenarCombo($rs, 't01_id_inst', 't01_sig_inst', $t01_id_inst);
            ?>
                </select>
            <?php if($t02_cod_proy != "") {echo('<input name="t01_id_inst" id="t01_id_inst" type="hidden" value="'.$t01_id_inst.'"/>');} ?></td>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td width="132" height="51">Código<br />
            <?php if($t02_cod_proy != ""){$inac="disabled='disabled'";}?>

            <select name="cboConcurso" id="cboConcurso" style="width: 45px;" class="Proyectos" onchange="CrearCodigoProy();" <?php echo($inac); ?>>
            <?php
                $arrcod = explode("-", $t02_cod_proy);
                $rs = $OjbTab->ListaConcursosCrear();
                $objFunc->llenarComboI($rs, 'codigo', 'abreviatura', $arrcod[1]);
            ?>
            </select>
            <input name="txtnumproy" id="txtnumproy" type="text" size="5" class="Proyectos" maxlength="6" value="<?php echo($arrcod[2]); ?>" <?php echo($inac); ?> onkeyup="CrearCodigoProy();" />
            <br />
            <input name="t02_cod_proy" id="t02_cod_proy" type="text" size="15" class="Proyectos" maxlength="10" value="<?php echo($t02_cod_proy); ?>" readonly="readonly" />
            <?php if($t02_cod_proy != "") {echo('<input name="t02_cod_proy" id="t02_cod_proy" type="hidden" value="'.$t02_cod_proy.'"/>');} ?>
            </td>
												<td colspan="5">Nombre del proyecto<br /> <textarea
														name="t02_nom_proy" cols="90" rows="2" class="Proyectos"
														id="t02_nom_proy"><?php echo($t02_nom_proy); ?></textarea></td>
											</tr>
											<tr>
												<td nowrap="nowrap">Fecha Aprobación <br /> (Firma del
													Convenio)
												</td>
												<td width="121">Fecha de Inicio Según Convenio</td>
												<td width="108">Estado</td>
												<td width="118" id='fchReinicTit'>Fecha de Reinicio</td>
												<td colspan="2" id='stateObsTit'>&nbsp;</td>
											</tr>
											<tr>
												<td><input name="t02_fch_apro" id="t02_fch_apro" type="text"
													size="15" maxlength="10"
													value="<?php echo($t02_fch_apro); ?>" class="Proyectos" />
												</td>
												<td><input name="t02_fch_ini" id="t02_fch_ini" type="hidden"
													size="12" maxlength="10"
													value="<?php echo($t02_fch_ini); ?>" class="Proyectos" /> <input
													name="t02_fch_isc" id="t02_fch_isc" type="text" size="15"
													maxlength="10" value="<?php echo($t02_fch_isc); ?>"
													class="Proyectos" /></td>
												<td><input name="t02_fch_ter" id="t02_fch_ter" type="hidden"
													size="12" maxlength="10"
													value="<?php echo($t02_fch_ter); ?>" class="Proyectos" /> <input
													type='hidden' name='origProyState' id='origProyState'
													value='<?php echo $t02_estado; ?>' /> <input type='hidden'
													name='unsuspendFlg' id='unsuspendFlg'
													value="<?php echo $isUnsuspendFlg ? '1' : '0';?>" /> <input
													type='hidden' name='numMonthsSuspended'
													id='numMonthsSuspended' value='0' /> <select
													id="t02_estado" name="t02_estado" style="width: 130px"
													class="Proyectos">
				<?php
    $rs = $OjbTab->ListaEstados();
    $objFunc->llenarCombo($rs, 'codigo', 'descripcion', $t02_estado);
    ?>
				</select></td>
												<td id="fchReninicTd" align="center"><input type="text"
													name="t02_fch_reinic" id="t02_fch_reinic" size="15"
													maxlength="10" value="<?php echo date('d/m/Y'); ?>"
													class="Proyectos" /></td>
												<td colspan="2" id='stateObsCont'>&nbsp;</td>
											</tr>
											<tr>
												<td height="30" valign="middle">Fecha de Inicio Real</td>
												<td align="center">N° de Meses</td>
												<td valign="middle">Fecha de Término Real</td>
												<td width="111" align="center" valign="middle"><p>N° de
														Meses de Ampliación</p></td>
												<td width="113" valign="middle">Fecha de Término por
													Ampliación</td>
												<td width="115" valign="top">&nbsp;</td>
											</tr>
											<tr>
												<td><input name="t02_fch_ire" id="t02_fch_ire" type="text"
													size="15" maxlength="10"
													value="<?php echo($t02_fch_ini); ?>" class="Proyectos" /></td>
												<td align="center"><input name="t02_num_mes"
													id="t02_num_mes" onchange="CalculaFecha(); return false;"
													type="text" size="3" maxlength="3"
													value="<?php echo($t02_num_mes);?>" class="Proyectos" /></td>
												<td><div id="capa" style="display: none;"></div> <input
													name="t02_fch_tre" id="t02_fch_tre"
													onchange="CalculaTermino(); return false;" type="text"
													size="15" maxlength="10"
													value="<?php echo($t02_fch_tre); ?>" class="Proyectos" /></td>
												<td align="center"><input name="t02_num_mes_amp"
													id="t02_num_mes_amp"
													onchange="CalculaAmpliacion(); return false;" type="text"
													size="3" maxlength="3"
													value="<?php echo($t02_num_mes_amp);?>" class="Proyectos" /></td>
												<td><input name="t02_fch_tam" id="t02_fch_tam" type="text"
													size="15" maxlength="10"
													value="<?php echo($t02_fch_tam); ?>" class="Proyectos" /></td>
												<td></td>
											</tr>

											<tr>
												<td height="30" valign="bottom">Linea</td>
												<td colspan='5'></td>
											</tr>
											<tr>
												<td><select class="Proyectos" name="t00_cod_linea"
													id='t00_cod_linea' style="width: 130px">
														<option></option>
                    <?php

                    $rs = $objProy->ListaLineas();
                    $objFunc->llenarCombo($rs, 't00_cod_linea', 't00_nom_abre', $t00_cod_linea,'t00_nom_lar');

                    ?>
                </select></td>
												<td colspan='5'>
												    <div id="preview_t00_cod_linea"></div>
												</td>
											</tr>
<?php
// -------------------------------------------------->
// DA 2.1 [19-04-2014 19:13]:
// Nuevo campo de Instituciones asociadas o colaboradoras [RF-001]: 
?>
											
											<tr>
												<td valign="bottom" height="30" colspan="2">Instituciones asociadas o colaboradoras</td>
												<td colspan="4"></td>
											</tr>
											<tr>
												<td colspan="6">
													<textarea class="Proyectos" rows="3" cols="110" name="t02_inst_asoc"><?php echo $t02_inst_asoc;?></textarea>
												</td>
											</tr>
<?php // --------------------------------------------------< ?>											
											
										</table>

										<script type='text/javascript'>
		$(document).ready(function() {
			var aSuspSt = '<?php echo $HC->Proy_Suspendido; ?>';
			var aOrigSt = $('#origProyState').val();

			$('#fchReinicTit').hide();
			$('#stateObsTit').hide();
			$('#fchReninicTd').hide();
			$('#stateObsCont').hide();

			$('#t02_estado').change(function(pEvent){
				var aCurrSt = $(this).val();
				if (aOrigSt != aSuspSt && aCurrSt == aSuspSt) /* Changing TO suspended */ {
					$('#stateObsTit').show().html('Suspensión - Observaciones');
					$('#stateObsCont').show().html("<textarea cols='50' rows='2' name='t02_obs_susp'></textarea>");
					$('#unsuspendFlg').val('0');
					$('#numMonthsSuspended').val(0);
				}
				else if (aOrigSt == aSuspSt && aCurrSt != aSuspSt) /* Changing FROM suspended */ {
					$('#fchReinicTit').show();
					$('#fchReninicTd').show();
					$('#stateObsTit').show().html('Cambio de Suspensión - Observaciones');
					$('#stateObsCont').show().html("<textarea cols='35' rows='2' name='t02_obs_susp'></textarea>");
					$("#t02_fch_reinic").datepicker();
					$('#unsuspendFlg').val('1');
					var aReqAction = '<?php echo md5("ajax_num_susp_days"); ?>';
					var aVersion = '<?php echo $t02_version; ?>';
					var aUrl = "process.php?action=" + aReqAction + "&idProy=" + $('#t02_cod_proy').val() + "&idVer=" + aVersion;
					$.get(aUrl, function(pData) {
						$('#numMonthsSuspended').val(pData);
					});
				}
				else {
					$('#stateObsTit').html('&nbsp;');
					$('#stateObsCont').html('&nbsp;');
				}
			});

<?php
// -------------------------------------------------->
// DA 2.0 [08-11-2013 12:19]:
// Descripcion al seleccionar la lista de lineas.
// Obtener datos por defecto de las tasas del concurso ya registrados.
// En caso de no tener ningun valor es posible aunque no esta hora implementado,
// pero es posible por ejemplo si respuesta.data.func (tasa funcionales) esta vacio o nulo
// entonces jalar de los datos maestros como de $HC->Porcent_Gast_Func

?>
			$('#preview_t00_cod_linea').html($('select#t00_cod_linea option:selected').attr('title'));
		    $('select#t00_cod_linea').change(function(){
			    var elem = $(this).find('option:selected');
			    $('#preview_t00_cod_linea').html(elem.attr('title'));

			    if (elem.val().length > 0) {

				    var concurso = $('select#cboConcurso').find('option:selected');
				    if (concurso.val().length >0) {


                        $('input.Tasas').val('Cargando...');
                        var req = Spry.Utils.loadURL("POST", "process.php?action=<?php echo md5('getTasasPorLineaConcurso');?>",true,
                        	function(res){

                        	   if (typeof(res.xhRequest.responseText) != 'undefined' ) {

                        		   var respuesta = JSON.parse(res.xhRequest.responseText);
                        		   
									if ( typeof(respuesta.data.func)!= "undefined" ) {
									   $('input#t02_porc_gast_func').val(respuesta.data.func);
	                                   $('input#t02_porc_linea_base').val(respuesta.data.linea);
	                                   $('input#t02_porc_imprev').val(respuesta.data.imprev);
	                                   $('input#t02_proc_gast_superv').val(respuesta.data.superv);
	                                   
									} else {
									   $('input#t02_porc_gast_func').val('<?php echo $HC->Porcent_Gast_Func;?>');
	                                   $('input#t02_porc_linea_base').val('<?php echo $HC->Porcent_Linea_Base;?>');
	                                   $('input#t02_porc_imprev').val('<?php echo $HC->Porcent_Imprevistos;?>');
	                                   $('input#t02_proc_gast_superv').val('<?php echo $HC->porcentGastSupervProy;?>');
	                                   
									}
                                   
                        	   }


                            },
                            { postData: 'concurso='+concurso.val()+'&linea='+elem.val(), headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });



				    }

			    }



		    });

<?php // --------------------------------------------------< ?>

		    /* var elem = $('select#t01_id_cta').find('option:selected');
		    if (elem.val().length > 0) {
			   var banco = elem.attr('data-banco'),
			   tipocuenta = elem.attr('data-tipocuenta'), tipomoneda = elem.attr('data-tipomoneda');

			   $('#preview_t01_id_cta').html(banco+'<br>'+tipocuenta+': '+elem.text()+'<br>'+tipomoneda);
		    }


		    $('select#t01_id_cta').change(function(e){
			   var elem = $(this).find('option:selected');
			   $('#preview_t01_id_cta').empty();
			   if (elem.val().length > 0) {
				   var banco = elem.attr('data-banco'),
				   tipocuenta = elem.attr('data-tipocuenta'), tipomoneda = elem.attr('data-tipomoneda');

				   $('#preview_t01_id_cta').html(banco+'<br>'+tipocuenta+': '+elem.text()+'<br>'+tipomoneda);
			   }

		   });*/

<?php /*if ($ObjSession->PerfilID == $HC->SP) { ?>
		    $('select#t01_id_inst').change(function(e){
		    	var elem = $(this).find('option:selected');
		    	if (elem.val().length > 0) {

		    		$('select#t01_id_cta').html('<option>Cargando...</option>');
                    var req = Spry.Utils.loadURL("POST", "process.php?action=<?php echo md5('getCuentasBancariasInstitucion');?>",true,
                    	function(res){
                            var respuesta = JSON.parse(res.xhRequest.responseText);
                            $('select#t01_id_cta').find('option').remove();
                            $('select#t01_id_cta').append('<option></option>');
                            $.each(respuesta.data,function(i,e){
                                var opt = "<option value='"+e.valor+"' data-banco='"+e.banco+"' data-tipocuenta='"+e.tipocuenta+"' data-tipomoneda='"+e.tipomoneda+"'>"+e.texto+"</option>";
                            	$('select#t01_id_cta').append(opt);
                            });

                        },
                    { postData: 'inst='+elem.val(), headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

		    	}
			});

<?php } */?>

		});
	</script>


									</fieldset>
								</td>
							</tr>

<?php
      // -------------------------------------------------- >
      // DA 2.0 [23-10-2013 15:36]
      // Nuevos campos para las tasas del proyecto:
?>

							<tr>
								<td colspan="6">
								    <fieldset>
										<legend>Tasas</legend>
										<table>
											<tr>
												<td width="16%">Gastos Administrativos (%)</td>
												<td width="16%">Linea Base (%)</td>
												<td width="16%">Imprevistos (%)</td>
												<td width="16%">Supervisión (%)</td>
											</tr>
											<tr>
												<td><input required class="Proyectos Tasas" type="text" size="5" id="t02_porc_gast_func"  name="t02_porc_gast_func" value="<?php echo $t02_porc_gast_func; ?>" /></td>
												<td><input required class="Proyectos Tasas" type="text" size="5" id="t02_porc_linea_base" name="t02_porc_linea_base" value="<?php echo $t02_porc_linea_base; ?>" /></td>
												<td><input required class="Proyectos Tasas" type="text" size="5" id="t02_porc_imprev" name="t02_porc_imprev" value="<?php echo $t02_porc_imprev; ?>" /></td>
												<td><input required class="Proyectos Tasas" type="text" size="5" id="t02_proc_gast_superv" name="t02_proc_gast_superv" value="<?php echo $t02_proc_gast_superv; ?>" /></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
							<tr>
							    <td colspan="6">
									<fieldset>
										<legend>Parametros</legend>
										<table>
											<tr>
												<td width="16%">Gratificación</td>
												<td width="16%">Porcentaje CTS</td>
												<td width="16%">Porcentaje ESS</td>
											</tr>
											<tr>
												<td><input required class="Proyectos" type="text" size="5" name="t02_gratificacion" value="<?php echo $t02_gratificacion; ?>" /></td>
												<td><input required class="Proyectos" type="text" size="5" name="t02_porc_cts" value="<?php echo $t02_porc_cts; ?>" /></td>
												<td><input required class="Proyectos" type="text" size="5" name="t02_porc_ess" value="<?php echo $t02_porc_ess; ?>" /></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>
<?php   // -------------------------------------------------- < ?>


<?php
$aSuspResult = $objProy->GetSuspentions($t02_cod_proy);
$aSuspExists = mysql_num_rows($aSuspResult) > 0;

if ($aSuspExists) {
    ?>
		<tr>
								<td colspan='6'>
									<fieldset>
										<legend>Suspensiones</legend>
										<table border='0' cellpadding='0' cellspacing='0'
											class='TableEditReg' style="width: 90%; margin: 0 auto;">
											<thead>
												<tr style='border-collapse: collapse; border-spacing: 0'>
													<th
														style="border-bottom: 1px solid #61210B; text-align: center">Suspensión</th>
													<th
														style="border-bottom: 1px solid #61210B; text-align: center">Versión</th>
													<th
														style="border-bottom: 1px solid #61210B; text-align: center">Levantamiento</th>
													<th
														style="border-bottom: 1px solid #61210B; text-align: center">Reinicio</th>
													<th
														style="border-bottom: 1px solid #61210B; text-align: center">Usuario</th>
													<th
														style="border-bottom: 1px solid #61210B; text-align: center">&nbsp;</th>
												</tr>
											</thead>
											<tbody>
		<?php
    while ($aSuspRow = mysql_fetch_assoc($aSuspResult)) {
        ?>
					<tr class='suspDatTr'>
													<td align='middle'><?php echo date('d/m/Y', strtotime($aSuspRow['t02_fch_susp'])); ?></td>
													<td align='middle'><?php echo $aSuspRow['t02_version']; ?></td>
													<td align='middle'><?php echo $aSuspRow['t02_fch_unsusp'] ? date('d/m/Y', strtotime($aSuspRow['t02_fch_unsusp'])) : ''; ?></td>
													<td align='middle'><?php echo $aSuspRow['t02_fch_reinic'] ? date('d/m/Y', strtotime($aSuspRow['t02_fch_reinic'])) : ''; ?></td>
													<td><?php echo $aSuspRow['usr_crea']; ?></td>
													<td align='middle'>
							<?php if ($aSuspRow['t02_obs_susp'] || $aSuspRow['t02_obs_unsusp']) { ?>
								<a href='javascript:void()' class='suspObsLnk'>Observaciones</a>
							<?php

} else
            echo "&nbsp;";
        ?>
						</td>
												</tr>
				<?php
        if ($aSuspRow['t02_obs_susp'] || $aSuspRow['t02_obs_unsusp']) {
            ?>
					<tr class='suspObsTr'>
													<td colspan='5'>
														<table border='0' cellpadding='0' cellspacing='0'
															style='width: 100%'>
															<tr>
																<th>Suspensión</th>
																<th>Levantamiento</th>
															</tr>
															<tr>
																<td><textarea style="width: 98%" rows='4'><?php echo $aSuspRow['t02_obs_susp']; ?></textarea></td>
																<td><textarea style="width: 98%" rows='4'><?php echo $aSuspRow['t02_obs_unsusp']; ?></textarea></td>
															</tr>
														</table>
													</td>
												</tr>
				<?php
        } // if
        ?>
		<?php
    } // while
    ?>
				</tbody>
										</table>
									</fieldset> <script type='text/javascript'>
				$('.suspObsTr').hide();
				$('.suspObsLnk').click(function(pEvent) {
					pEvent.preventDefault();
					pEvent.stopPropagation();
					$(this).parent().parent().next('.suspObsTr').toggle();
				});
			</script>
								</td>
							</tr>
	<?php
} // if
mysql_free_result($aSuspResult);
?>
    <tr>

								<td colspan="6">
									<fieldset>
										<legend>Detalles</legend>
										<table border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg">
											<tbody style="display:none;">
												<tr>
													<td width="121" nowrap="nowrap"><strong>Finalidad </strong></td>
													<td width="121">&nbsp;</td>
													<td width="121">&nbsp;</td>
													<td width="337">&nbsp;</td>
												</tr>
												<tr>
													<td colspan="4"><textarea name="t02_fin" cols="110"
															rows="3" class="Proyectos"><?php echo($t02_fin); ?></textarea></td>
												</tr>
												<tr>
													<td colspan="4"><strong>Propósito</strong></td>
												</tr>
												<tr>
													<td colspan="4"><textarea name="t02_pro" cols="110"
															rows="3" class="Proyectos"><?php echo($t02_pro); ?></textarea></td>
												</tr>
											</tbody>
											<tr>
												<td colspan="4"><strong>Población Beneficiaria </strong></td>
											</tr>
											<tr>
												<td colspan="4"><textarea name="t02_ben_obj" cols="110"
														rows="3" class="Proyectos" <?php echo $usrEva; ?>><?php echo($t02_ben_obj); ?></textarea></td>
											</tr>
          <?php
        // eliminado temporalmente
        /*
         * <tr> <td colspan="4"><strong>Ambito Geográfico </strong></td> </tr> <tr> <td colspan="4"><textarea name="t02_amb_geo" cols="110" rows="3" class="Proyectos" <?php echo $usrEva; ?>><?php echo($t02_amb_geo); ?></textarea></td> </tr>
         */
        ?>

<?php
// -------------------------------------------------->
// DA 2.0 [26-11-2013 14:39]:
// Los campos de sectores seran usados en la seccion de: Proyectos -> Proyecto Inicial -> Sector Productivo:

/* ?>
                                            <tr>
												<td height="24" colspan="4"><strong>Principal Sector Productivo / SubSector</strong></td>
											</tr>
											<tr>
											 <td height="24"> <strong>Principal Sector</strong> </td>
											 <td>  <strong>Subsector</strong>  </td>
											 <td colspan="2"> <strong>Producto Principal</strong> </td>
											</tr>
											<tr>
												<td height="26">
												<select name="t02_sector_main" id="t02_sector_main" style="width: 160px"
													onchange="LoadSectores();" class="Proyectos" <?php echo $usrEva;?>>
													<option value="" selected="selected"></option>
                                                    <?php
                                                    $objTablas = new BLTablas();
                                                    $rsSectoresMain = $objTablas->getListaSectoresMain();
                                                    $objFunc->llenarCombo($rsSectoresMain, 'codigo', 'descripcion', $t02_sector_main);
                                                    ?>
                                                </select>
                                                </td>
                                                <td>
												<select name="t02_sec_prod" id="t02_sec_prod" style="width: 160px"
													onchange="LoadSubSectores();" class="Proyectos" <?php echo $usrEva;?>>
													<option value="" selected="selected"></option>
                                                    <?php
                                                    $objTablas = new BLTablasAux();
                                                    $rsSectores = $objTablas->SectoresProductivos($t02_sector_main);
                                                    $objFunc->llenarCombo($rsSectores, 'codigo', 'descripcion', $t02_sector);
                                                    ?>
                                                </select>
                                                </td>
                                                <td colspan="2">
                                                <select
													name="t02_subsec_prod" id="t02_subsec_prod" style="width: 220px" class="Proyectos"
													<?php echo $usrEva;?>>
                                                    <?php
                                                    $rsSubSectores = $objTablas->SubSectoresProductivos($t02_sector);
                                                    $objFunc->llenarCombo($rsSubSectores, 'codigo', 'descripcion', $t02_subsector);
                                                    ?>
                                                </select>
                                                </td>
                                            </td>
											</tr>
											<tr>
											     <td colspan="4">
											         <strong>Producto promovido</strong>
											     </td>
											</tr>
											<tr>
											     <td colspan="4">
											         <input type="text" name="t02_prod_promovido" id="t02_prod_promovido" class="Proyectos" value="<?php echo $t02_prod_promovido;?>" />
											     </td>
											</tr>

<?php */

// --------------------------------------------------<
?>
										</table>
									</fieldset>
								</td>
							</tr>
							<tr>




     <?php /* ?>
    <td colspan="2">
        <fieldset>
            <legend> Presupuesto y Desembolso</legend>
            <table border="0" cellpadding="0" cellspacing="0" class="TableEditReg" style="padding:1px;">

                <tr>
                    <td width="79" height="26" valign="top" nowrap="nowrap">Credito</td>
                    <td width="104" valign="top" nowrap="nowrap">
                        <select name="t02_cre_fe" id="t02_cre_fe" style="width:70px" class="Proyectos">
                            <?php if(isset($t02_cre_fe) && $t02_cre_fe!=""){ ?>
                                    <option value="" selected="selected"></option>
                                <?php if($t02_cre_fe=="S") { ?>
                                    <option value="S" selected="selected">Si</option>
                                    <option value="N">No</option>
                                <?php } if($t02_cre_fe=="N"){ ?>
                                    <option value="S">Si</option>
                                    <option value="N" selected="selected">No</option>
                            <?php       }
                                  } else { ?>
                                    <option value="" selected="selected"></option>
                                    <option value="S">Si</option>
                                    <option value="N">No</option>
                             <?php } ?>
                             </select>
                     </td>
                </tr>

                <tr>
                    <td colspan="2" align="center">
                        <fieldset>
                            <legend>Datos de la Cuenta Bancaria del proyecto</legend>
                            <?php if($t02_cta_banco >=1) { ?>
                            <?php $rowCta = $objProy->SeleccionarCuentaProyeto($id); ?>
                            <table width="300" border="0" cellpadding="0" cellspacing="0" style="padding:1px;">
                                <tr>
                                    <td width="68"><strong>Banco</strong></td>
                                    <td width="232" align="left"><?php echo($rowCta['banco']);?></td>
                                 </tr>
                                 <tr>
                                    <td><strong>Tipo</strong></td>
                                    <td align="left"><?php echo($rowCta['tipocuenta']);?></td>
                                  </tr>
                                  <tr>
                                    <td><strong>N° Cuenta</strong></td>
                                    <td align="left"><?php echo($rowCta['t01_nro_cta']);?></td>
                                   </tr>
                                   <tr>
                                        <td><strong>Moneda</strong>
                                   </td>
                                    <td align="left"><?php echo($rowCta['moneda']);?></td>
                                </tr>
                                <tr>
                                    <td nowrap="nowrap"><strong>Beneficiario</strong>&nbsp;</td>
                                    <td align="left"><?php echo($rowCta['beneficiario']);?></td>
                                 </tr>
                                 <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">
                                        <a href="javascript:;" onclick="ModificarCuenta();" title="Modificar Datos de la Cuenta" style="color:#666;">
                                            Modificar
                                        </a>
                                    </td>
                                 </tr>
                              </table>

                              <?php } else { ?>

                              <div id="toolbar"> <br />
                                <button class="Proyectos Button" onclick="AsignarCuenta(); return false;" value="Asignar Cuenta Bancaria" style="white-space:nowrap;" <?php if($ObjSession->PerfilID!=$HC->CMF && $ObjSession->PerfilID!=$HC->MF){echo "disabled";} ?>>
                                    Asignar Cuenta
                                </button>
                               </div>
                               <?php } ?>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="t02_pres_fe" type="hidden" id="t02_pres_fe" style="text-align:right" value="<?php echo(number_format($t02_pres_fe,2)); ?>" size="18" class="Proyectos" />
                        <input name="t02_pres_eje" type="hidden" id="t02_pres_eje" style="text-align:right" value="<?php echo(number_format($t02_pres_eje,2)); ?>" size="18" class="Proyectos"/>
                        <input name="t02_pres_otro" type="hidden" id="t02_pres_otro" style="text-align:right" value="<?php echo(number_format($t02_pres_otro,2)); ?>" size="18" class="Proyectos"/>
                    </td>
                    <!--Total-->
                    <td>
                        <input name="t02_pres_tot" type="hidden" id="t02_pres_tot" style="text-align:right" value="<?php echo(number_format($t02_pres_tot,2)); ?>" size="18" class="Proyectos"/>
                    </td>
                </tr>
            </table>
        </fieldset>
    </td>
    <?php  */ ?>




      <td width="444" colspan="6">
									<fieldset>
										<legend>Supervisión</legend>
										<table width="398" border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg">
											<tr>
												<td width="77" height="29" nowrap="nowrap">Gestor de Proyecto</td>
												<td width="138" nowrap="nowrap">
												    <select name="t02_moni_tema" id="t02_moni_tema" style="width: 240px" class="Proyectos">
														<option value=""></option>
    <?php
	// -------------------------------------------------->
	// DA 2.0 [24-11-2013 20:35]
	// Existen cargo o unidades en el equipo de fondo empleo registrados
	// con el cargo de Monitor Tematico, se creo en BD el cargo de Gestor de Proyecto:
	//$rs = $objProy->ListaMonitorTematico();
    $rs = $objProy->ListaGestoresDeProyectos();
    // --------------------------------------------------<
    $objFunc->llenarCombo($rs, 'codigo', 'nombres', $t02_moni_tema);
    ?>
                                                    </select>
                                                </td>
											</tr>
          <?php
        // Eliminado temporalmente
        /*
         * <tr> <td height="32" nowrap="nowrap">Monitor Financiero</td> <td><select name="t02_moni_fina" id="t02_moni_fina" style="width:240px" class="Proyectos"> <option value=""></option> <?php $rs = $objProy->ListaMonitorFinanciero(); $objFunc->llenarComboI($rs,'codigo','nombres',$t02_moni_fina); ?> </select></td> </tr>
         */
        ?>

<?php if ($view == md5("ajax_edit")) {     ?>
                                            <tr>
												<td height="26">Supervisor Externo</td>
												<td><select name="t02_moni_ext" id="t02_moni_ext" style="width: 240px" class="Proyectos">
														<option value=""></option>
              <?php
            $rs = $objProy->ListaMonitorExterno($t02_cod_proy, $t02_version);
            $objFunc->llenarComboGroupI($rs, 'codigo', 'nombres', $t02_moni_ext, 'tipo');
            ?>
                                                </select></td>
			                                 </tr>

<?php } ?>

          <?php
        // Eliminado temporalmente
        /*
         * <tr> <td nowrap="nowrap">Supervisor Proyecto </td> <td> <select name="t02_sup_inst" id="t02_sup_inst" style="width:240px" class="Proyectos"> <option value=""></option> <?php $rs = $objProy->ListaSupervisorInstitucional($t02_cod_proy, $t02_version); $objFunc->llenarComboI($rs,'codigo','nombres',$t02_sup_inst); ?> </select></td> </tr>
         */
        ?>

          </table>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td colspan="6">
									<fieldset>
										<legend>Ubicación</legend>
										<table border="0" cellpadding="0" cellspacing="0"
											class="TableEditReg">
											<tr>
												<td width="132" valign="bottom">Dirección</td>
												<td width="145" valign="bottom">&nbsp;</td>
												<td width="112" valign="bottom">&nbsp;</td>
												<td width="311" valign="bottom">Ciudad</td>
											</tr>
											<tr>
												<td height="23" colspan="3"><input name="t02_dire_proy"
													type="text" size="75" maxlength="200"
													value="<?php echo($t02_dire_proy); ?>" class="Proyectos"
													<?php echo $usrEva;?> /></td>
												<td><input name="t02_ciud_proy" type="text" size="17"
													maxlength="50" value="<?php echo($t02_ciud_proy); ?>"
													class="Proyectos" <?php echo $usrEva; ?> /></td>
											</tr>
											<tr>
												<td nowrap="nowrap">Teléfono</td>
												<td>Fax</td>
												<td>Mail</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td><input name="t02_tele_proy" id="t02_tele_proy"
													type="text" size="18" maxlength="50"
													value="<?php echo($t02_tele_proy); ?>" class="Proyectos"
													<?php echo $usrEva;?> /></td>
												<td><input name="t02_fax_proy" id="t02_fax_proy" type="text"
													size="18" maxlength="50"
													value="<?php echo($t02_fax_proy); ?>" class="Proyectos"
													<?php echo $usrEva;?> /></td>
												<td colspan="2"><input name="t02_mail_proy" type="text"
													size="40" maxlength="50"
													value="<?php echo($t02_mail_proy); ?>" class="Proyectos"
													<?php echo $usrEva; ?> />
       <?php

if ($id == "") {
        $sURL = "process.php?action=" . md5("ajax_new");
    } else {
        $sURL = "process.php?action=" . md5("ajax_edit") . "&vs=" . $vs;
    }
    // echo($sURL);
    ?>
      <input type="hidden" name="txturlsave" id="txturlsave"
													value="<?php echo($sURL); ?>" /></td>
											</tr>
										</table>
									</fieldset>
								</td>
							</tr>

	<?php
if ($view == md5("ajax_edit")) {
    ?>

							<tr>
							 <td colspan="6">

							 <fieldset>
                                <legend>Datos de la Cuenta Bancaria del proyecto</legend>
                                <?php if($t02_cta_banco >=1) { ?>
                                <?php $rowCta = $objProy->SeleccionarCuentaProyeto($id); ?>
                                <table width="300" border="0">
                                    <tr>
                                        <td width="68"><strong>Banco</strong></td>
                                        <td width="232" align="left"><?php echo($rowCta['banco']);?></td>
                                     </tr>
                                     <tr>
                                        <td><strong>Tipo</strong></td>
                                        <td align="left"><?php echo($rowCta['tipocuenta']);?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>N° Cuenta</strong></td>
                                        <td align="left"><?php echo($rowCta['t01_nro_cta']);?></td>
                                       </tr>
                                       <tr>
                                            <td><strong>Moneda</strong>
                                       </td>
                                        <td align="left"><?php echo($rowCta['moneda']);?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap="nowrap"><strong>Beneficiario</strong>&nbsp;</td>
                                        <td align="left"><?php echo($rowCta['beneficiario']);?></td>
                                     </tr>
                                     <?php if ($ObjSession->PerfilID == $HC->SP)  { ?>
                                     <tr>
                                        <td>&nbsp;</td>
                                        <td align="right">
                                            <a href="javascript:;" onclick="ModificarCuenta();" title="Modificar Datos de la Cuenta" style="color:#666;">
                                                Modificar
                                            </a>
                                        </td>
                                     </tr>
                                     <?php } ?>
                                  </table>

                                  <?php } else { ?>

                                      <?php if ($ObjSession->PerfilID == $HC->SP)  { ?>
                                       <div id="toolbar"> <br />
                                        <button class="Proyectos Button" onclick="AsignarCuenta(); return false;" value="Asignar Cuenta Bancaria" style="white-space:nowrap; color:#000;">
                                            Asignar Cuenta
                                        </button>
                                       </div>
                                       <?php } else { ?>
                                       <div style="margin:10px;">No existe cuenta bancaria registrada.</div>
                                       <?php } ?>

                                   <?php } ?>
                            </fieldset>




							 <?php /* ?>
							    <fieldset>
									<legend>Cuenta Bancaria</legend>
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									   <tr>
									       <td colspan=2>
									           <strong>Nro. de Cuenta</strong>
									       </td>

									   </tr>
									   <tr>
									       <td width="50%">

									           <select <?php if ($ObjSession->PerfilID != $HC->SP) echo 'disabled'; else echo 'name="t01_id_cta"'; ?>  id="t01_id_cta" class="Proyectos" style="width: 240px" >
									               <option></option>
                                                    <?php
                                                    $rsCuentas = $objProy->getCuentasBancarias($t02_cod_proy, $t01_id_inst);
                                                    $objFunc->llenarCombo($rsCuentas, 'valor', 'texto',$t01_id_cta,'',array('banco', 'tipocuenta', 'tipomoneda'));

                                                    ?>
									           </select>
									           <?php if ($ObjSession->PerfilID != $HC->SP) { ?>
									           <input type="hidden" name="t01_id_cta" value="<?php echo $t01_id_cta;?>" />

									           <?php } ?>

									       </td>
									       <td>
									           <div id="preview_t01_id_cta" style="width: 100%;"></div>
									       </td>
									   </tr>
								    </table>
								</fieldset>
								<?php */ ?>

							 </td>

							</tr>


                            <tr>
								<td colspan="6">
									<fieldset>
										<legend>Anexos de la Institución</legend>
										<div id="divAnexos" style="width: 100%;"></div>
									</fieldset>
								</td>
							</tr>

    <?php
}
?>
    <tr>
								<td colspan="6">&nbsp;</td>
							</tr>
						</table>
						<!--01/12/2011-->
						<!--prueba-->

<script language="javascript" type="text/javascript">
	function CrearCodigoProy()
	{
		var strcod = new String($("#txtnumproy").val().trim());
		var newcodigo = "C-" + $("#cboConcurso").val() + "-" + strcod.replace("_","").toUpperCase() ;
		$('#t02_cod_proy').val(newcodigo);
	}

   /*modificado 01/12/2011*/

	function btnGuardarMsg()
	{
		<?php if($ObjSession->PerfilID==$HC->GP){?>
		$("#chkVB").attr("disabled","disabled");
		$("#solAprob").attr("disabled","disabled");
		$("#Correccion").attr("disabled","disabled");
		dsLista.loadData(); return false;	<?php

} else
    if ($ObjSession->PerfilID == $HC->SP) {
        ?>
		 btnGuardar_Clic();	<?php

}     // Actualizar Recarga
    else
        if ($ObjSession->PerfilID == $HC->Ejec) {
            ?>
		 $("#FormData").submit();
			//btnEditar_Clic($('#t02_cod_proy').val(),'<?php echo($vs);?>', '<?php echo($accion);?>'); return false;
		<?php } ?>
	}

	function btnGuardar_Clic()
	{
		 <?php $ObjSession->AuthorizedPage(); ?>

		if( $('#t01_id_inst').val()=="" ) {alert("Ingrese la Institución Ejecutora"); $('#t01_id_inst').focus(); return false;}
		if (isNaN(parseFloat($("#txtnumproy").val().trim()))) {alert("Número de Proyecto no es válido"); $('#txtnumproy').focus(); return false;}
		var aPryNum = $('#txtnumproy');
		while (aPryNum.val().length < 3)
		    aPryNum.val('0' + aPryNum.val());

		if ($('#t02_cod_proy').val() == "")
			CrearCodigoProy();

		if( $('#t02_cod_proy').val()=="" ) {alert("Ingrese el Código del Proyecto"); $('#t02_cod_proy').focus(); return false;}
		if( $('#t02_nom_proy').val()=="" ) {alert("Ingrese el Nombre del Proyecto"); $('#t02_nom_proy').focus(); return false;}
		if( $('#t02_fch_apro').val()=="" ) {alert("Ingrese la Fecha Aprobación (Firma del Convenio)"); $('#t02_fch_apro').focus(); return false;}
		if( $('#t02_fch_isc').val()=="" ) {alert("Ingrese la Fecha de Inicio Según Convenio"); $('#t02_fch_isc').focus(); return false;}
		if( $('#t02_fch_ire').val()=="" ) {alert("Ingrese la Fecha de Inicio Real"); $('#t02_num_mes').focus(); return false;}
		if( $('#t02_num_mes').val()=="" ) {alert("Ingrese el Número de Meses"); $('#t02_num_mes').focus(); return false;}
		if( $('#t02_fch_tre').val()=="" ) {alert("Ingrese la Fecha de Término Real"); $('#t02_fch_tre').focus(); return false;}
		

		var fecha1	=	$("#t02_fch_ire").attr("value");
		var fecha2	=	$("#t02_fch_tre").attr("value");
		var fecha3	=	$("#t02_fch_tam").attr("value");
		var f_isc	=	$("#t02_fch_isc").attr("value");

		if( fecha1!="" && f_isc=="" ) {alert("La Fecha de Inicio Real no puede ser ingresado\nPrimero debe ingresar la Fecha de Inicio según Convenio "); return false;}

		f1 = CDate(fecha1);
		f2 = CDate(fecha2);
		f3 = CDate(fecha3);
		f_isc = CDate(f_isc);

		if( f1<f_isc ) {alert("La Fecha de Inicio Real no puede ser menor a la Fecha de Inicio Según Convenio"); return false;}
		if( f2<f1 ) {alert("La Fecha de Término Real no puede ser menor a la Fecha de Inicio Real"); return false;}

		var aEjeState = '<?php echo $HC->Proy_Ejecucion; ?>';
		if ($('#unsuspendFlg').val() == '1' && $('#t02_estado').val() == aEjeState) {
			var aUnsuspDate = new Date().setHours(0,0,0,0);
			var aReinicDate = CDate($('#t02_fch_reinic').val());
			if (aReinicDate < aUnsuspDate) {
				alert("Fecha de Reinicio no puede ser en el pasado");
				$('#t02_fch_reinic').focus();
				return false;
			}
			if ($('#t02_num_mes_amp').val() < $('#numMonthsSuspended').val()) {
				alert("Debido a que se esta levantando la suspensión a este proyecto, se debe ingresar\nuna ampliación mayor o igual al número de meses que estuvo en suspensión.")
				$('#t02_num_mes_amp').focus();
				return false;
			}
		}
<?php /* ?>
		if( $('#t02_sector_main').val()=="" ) {alert("Seleccione por favor: Principal Sector"); return false;}
		if( $('#t02_sec_prod').val()=="" ) {alert("Seleccione por favor: Subsector");  return false;}
		if( $('#t02_subsec_prod').val()=="" ) {alert("Seleccione por favor: Producto Principal");  return false;}
<?php */ ?>
		var BodyForm = $("#FormData").serialize() ;
		var sURL = $('#txturlsave').val();
		var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessGuardar, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });

		return false;
	}

	function MySuccessGuardar(req)
	{
	  var respuesta = req.xhRequest.responseText;
	  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	  var ret = respuesta.substring(0,5);
	  if(ret=="Exito")
	  {
		dsLista.loadData();
		var vs = respuesta.substring(0,7);
		alert(respuesta.replace(vs,""));
		vs = vs.replace(ret,"");
<?php // -------------------------------------------------->
// DA 2.0 [19-11-2013 17:46]
// Cuando termina de guardar siempre deberia de ir al modo de editar, cosa que ocurria
// lo contraria solo cuando estaba en el estado nuevo aqui el valor de $accion era en blanco.
/*
?>
		btnEditar_Clic($('#t02_cod_proy').val(),vs.trim(), '<?php echo($accion);?>');
<?php
*/
// --------------------------------------------------<
?>
		btnEditar_Clic($('#t02_cod_proy').val(),vs.trim(), '<?php echo md5('editar');?>');
	  }
	  else
	  {alert(respuesta);}

	 }

	function btnAprobar()
	{
		<?php $ObjSession->AuthorizedPage(); ?>
		if (confirm('Esta seguro de aprobar el Proyecto?')) {
			var BodyForm = "idProy=<?php echo($id);?>";
			var req = Spry.Utils.loadURL("POST", "process.php?action=<?php echo(md5("aprobarProyecto"));?>", true, MySuccessAprobar, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
        }
	}

	function MySuccessAprobar(req)
	{
        var r = req.xhRequest.responseText;
        var msj = "";

        switch (r) {
            case '1': msj = "Antes debe aprobar el Marco Lógico"; break;
            case '2': msj = "Antes debe aprobar el Cronograma de Productos"; break;
            case '3': msj = "Antes debe aprobar el Cronograma de Actividades"; break;
            case '4': msj = "Antes debe aprobar el Presupuesto"; break;
            case '5': msj = "Proyecto aprobado correctamente"; break;
            case '6': msj = "Proyecto ya aprobado"; break;
            default: msj = "Error interno"; break;
        }
        alert(msj);

    	btnEditar_Clic('<?php echo($id);?>','<?php echo($vs);?>', '<?php echo md5('editar');?>');
	 }

  function CalcularPresup()
  {

	  var fe = $('#t02_pres_fe').val();
	  var eje = $('#t02_pres_eje').val();
	  var otro = $('#t02_pres_otro').val();
	  var total = (CNumber(fe) + CNumber(eje) + CNumber(otro));
	  $('#t02_pres_tot').val(total);
  }
  function CNumber(str)
  {
	  var numero =0;
	  if (str !="" && str !=null)
	  { numero = parseFloat(str);}

	  if(isNaN(numero)) { numero=0;}

	 return numero;
  }

function CalculaFecha()
{
	var fecha	=	$("#t02_fch_ire").val();
	var numero	=	$("#t02_num_mes").val();
	var sURL   = "process_datos.php?action=<?php echo(md5("ajax_fecha_mes"));?>";
	var BodyForm = "fecha="+fecha+"&numero="+numero;

	$('#t02_fch_tre').val("Cargando ...");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalculaFecha, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
}

function SuccessCalculaFecha(req)
{
	$('#t02_fch_tre').val(req.xhRequest.responseText) ;
}

function CalculaTermino()
{
	var fecha1	=	$("#t02_fch_ire").attr("value");
	var fecha2	=	$("#t02_fch_tre").attr("value");
	var sURL = "process_datos.php?&action=<?php echo(md5("ajax_dif_fecha"))?>";
	var BodyForm = "fecha1="+fecha1+"&fecha2="+fecha2;

	$('#t02_num_mes').val("...")
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalculaTermino, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
}

function SuccessCalculaTermino(req)
{
	var strNumero = jQuery.trim(req.xhRequest.responseText) ;
	$('#t02_num_mes').val(strNumero) ;
}


function AsignarCuenta()
{
	if("<?php echo($id);?>"=="")
	{
		alert("Antes de Asignar la Cuenta Bancaria, se debe Grabar los Datos del Proyecto.") ;
		return;
	}

	var nameBenef = encodeURIComponent($('#t01_id_inst').find('option:selected').text());
	var url = "proy_edit_cta.php?action=<?php echo(md5("ajax_new"));?>&idProy=<?php echo($id);?>&idVersion=<?php echo($vs);?>&idInst=<?php echo($t01_id_inst);?>&nameInst="+nameBenef;
	loadPopup("Asignacion de Cuenta Bancaria del Proyecto", url);
}

function ModificarCuenta()
{
	var nameBenef = $('#t01_id_inst').find('option:selected').text();
	var url = "proy_edit_cta.php?action=<?php echo(md5("ajax_edit"));?>&idProy=<?php echo($id);?>&idVersion=<?php echo($vs);?>&idInst=<?php echo($t01_id_inst);?>&nameInst="+nameBenef;
	loadPopup("Asignacion de Cuenta Bancaria del Proyecto", url);
}


function CalculaAmpliacion()
{
	var fecha	=	$("#t02_fch_tre").val();
	var numero	=	$("#t02_num_mes_amp").val();
	var sURL   = "process_datos.php?action=<?php echo(md5("ajax_fecha_amp"));?>";
	var BodyForm = "fecha="+fecha+"&numero="+numero;

	$('#t02_fch_tam').val("Cargando ...");
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalculaAmpliacion, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
}

function SuccessCalculaAmpliacion(req)
{
	$('#t02_fch_tam').val(req.xhRequest.responseText) ;
}


function CalculaMesAmp()
{
	var fecha1	=	$("#t02_fch_tre").attr("value");
	var fecha2	=	$("#t02_fch_tam").attr("value");
	var sURL = "process_datos.php?&action=<?php echo(md5("ajax_mes_amp"))?>";
	var BodyForm = "fecha1="+fecha1+"&fecha2="+fecha2;

	$('#t02_num_mes_amp').val("...")
	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessCalculaMesAmp, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
}

function SuccessCalculaMesAmp(req)
{
	var strNumero = jQuery.trim(req.xhRequest.responseText) ;
	$('#t02_num_mes_amp').val(strNumero) ;
}

 	  LoadAnexos();
	  function LoadAnexos()
		{
		var url = "proy_anexos.php?id=<?php echo($id);?>&vs=<?php echo($vs);?>&accion=<?php echo($accion)?>";
		loadUrlSpry("divAnexos",url);
		}		//modificado 28/11/2011

		// AQ: Se elimina metodos de revisión y aprobación de otros actores.
		// Ahora todo lo hace el SP

	 <?php if($accion!=md5("editar") && $view == md5("ajax_edit")) { ?>
	  $('#btnGuardar').attr("disabled","disabled");
	  $('.Proyectos').attr("disabled","disabled");
	  $('#btnRevision').attr("disabled","disabled");
	  $('#solAprob').attr("disabled","disabled");
	  $('#chkVB').attr("disabled","disabled");
	  $('#Correccion').attr("disabled","disabled");

	  <?php } else { ?>


		$.mask.definitions['0']='[0123456789 ]';

		//$("#t02_tele_proy").mask('(0?0)999-9999');
		//$("#t02_fax_proy").mask('(0?0)999-9999');

		// $('#t02_cod_proy').mask('C-99-99?*');
		$("#txtnumproy").mask('00?*');

		$("#t02_num_mes").numeric().pasteNumeric();

		<?php if($view == md5("ajax_new")) { ?>
			$("#t02_estado").val("<?php echo($HC->Proy_PorIniciar)?>");
		<?php } ?>
		<?php if($ObjSession->PerfilID!=$HC->SP) { ?>

		$("#t02_estado option").attr('disabled','disabled');
		$("#t02_estado option:selected").removeAttr('disabled');
		<?php } ?>
		<?php if($ObjSession->PerfilID==$HC->SP && $t02_vb_proy!=1) { ?>
		$("#t02_estado option").attr('disabled','disabled');
		$("#t02_estado option:selected").removeAttr('disabled');
		<?php } ?>
		<?php if($t02_estado==$HC->Proy_Ejecucion){?>
		//$("#t02_estado ").attr('disabled','disabled');
		$("#t02_estado").find("option[value='40']").attr('disabled','disabled');

		  <?php }?>

	  <?php } ?>

	   switch("<?php echo($ObjSession->PerfilID);?>")
	   {
	   case "<?php echo($HC->Ejec);?>" :
	   		$("#t02_nom_proy").attr("readonly","readonly");
            $("#t02_fch_apro").attr("readonly","readonly");
            $("#t02_fch_isc").attr("readonly","readonly");
            $("#t02_fch_ire").attr("readonly","readonly");
            $("#t02_num_mes").attr("readonly","readonly");
            $("#t02_fch_tre").attr("readonly","readonly");
            $("#t02_num_mes_amp").attr("readonly","readonly");
            $("#t02_fch_tam").attr("readonly","readonly");

			$("#t02_moni_tema option").attr('disabled','disabled');
            $("#t02_moni_tema option:selected").removeAttr('disabled');

			$("#t02_moni_fina option").attr('disabled','disabled');
            $("#t02_moni_fina option:selected").removeAttr('disabled');

			$("#t02_moni_ext option").attr('disabled','disabled');
            $("#t02_moni_ext option:selected").removeAttr('disabled');

			$("#t02_sup_inst option").attr('disabled','disabled');
            $("#t02_sup_inst option:selected").removeAttr('disabled');
            $("#t02_cre_fe option").attr('disabled','disabled');
            $("#t02_cre_fe option:selected").removeAttr('disabled');

			break;
		 case "<?php echo($HC->GP);?>" :
		 $("#t02_nom_proy").attr("readonly","readonly");
		 $("#t02_fch_apro").attr("readonly","readonly");
		 $("#t02_fch_isc").attr("readonly","readonly");
		 $("#t02_fch_ire").attr("readonly","readonly");
		 $("#t02_num_mes").attr("readonly","readonly");
		 $("#t02_fch_tre").attr("readonly","readonly");
		 $("#t02_num_mes_amp").attr("readonly","readonly");
		 $("#t02_fch_tam").attr("readonly","readonly");
		 $("#t02_moni_tema option").attr('disabled','disabled');
		 $("#t02_moni_tema option:selected").removeAttr('disabled');
		 $("#t02_moni_fina option").attr('disabled','disabled');
		 $("#t02_moni_fina option:selected").removeAttr('disabled');
		 $("#t02_moni_ext option").attr('disabled','disabled');
		 $("#t02_moni_ext option:selected").removeAttr('disabled');
		 $("#t02_sup_inst option").attr('disabled','disabled');
		 $("#t02_sup_inst option:selected").removeAttr('disabled');
		 $("#t02_sec_prod option").attr('disabled','disabled');
		 $("#t02_sec_prod option:selected").removeAttr('disabled');
		 $("#subsec option").attr('disabled','disabled');
		 $("#subsec option:selected").removeAttr('disabled');
		 $("#t02_cre_fe option").attr('disabled','disabled');
		 $("#t02_cre_fe option:selected").removeAttr('disabled');
		 $("#t02_subsec_prod option").attr('disabled','disabled');
		 $("#t02_subsec_prod option:selected").removeAttr('disabled');
		 break;
		case "<?php echo($HC->SP);?>" :
			EnabledDatpicker();
			break;
		case "<?php echo($HC->RA);?>" :
			//$("#t02_moni_fina option").attr('disabled','disabled');
            //$("#t02_moni_fina option:selected").removeAttr('disabled');
			EnabledDatpicker();
		    break;
		<?php /*
		case "<?php echo($HC->CMF);?>" : */
			/*$("#t02_moni_tema option").attr('disabled','disabled');
            $("#t02_moni_tema option:selected").removeAttr('disabled');
			$("#t02_moni_ext option").attr('disabled','disabled');
            $("#t02_moni_ext option:selected").removeAttr('disabled');
			$("#t02_sup_inst option").attr('disabled','disabled');
            $("#t02_sup_inst option:selected").removeAttr('disabled');*/
			//EnabledDatpicker();			break;
		?>
	   }

	   function EnabledDatpicker()
	   {
		    $("#t02_fch_apro").datepicker();
			$("#t02_fch_ini").datepicker();
			$("#t02_fch_ter").datepicker();
			$("#t02_fch_isc").datepicker();
			$("#t02_fch_ire").datepicker();
			$("#t02_fch_tre").datepicker();
			$("#t02_fch_tam").datepicker();
		}
  </script>
					</div>
					<iframe id="ifrmUploadFile" name="ifrmUploadFile" style="display: none;"></iframe>
  <?php if($view=='') { ?>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
<?php } ?>
