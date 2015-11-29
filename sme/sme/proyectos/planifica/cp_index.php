<?php
/**
 * CticServices
 *
 * Gestiona el Cronograma de Productos
 *
 * @package     sme/proyectos/planifica
 * @author      AQ
 * @since       Version 2.0
 *
 */
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require_once (constant("PATH_CLASS") . "BLMarcoLogico.class.php");
require_once (constant("PATH_CLASS") . "HardCode.class.php");

$objML = new BLMarcoLogico();
$objProy = $objML->Proyecto;
$objHC = new HardCode();

$objFunc->SetTitle("Proyectos - Cronograma de Productos");
$objFunc->SetSubTitle("Cronograma de Productos");

//error_reporting(E_ALL);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
    <meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="../../../img/feicon.ico" type="image/x-icon"/>
    <link href="../../../css/template.css" rel="stylesheet" media="all" />
    <script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
    <script src="../../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
    <link href="../../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
    <script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
    <script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
    <script src="../../../SpryAssets/SpryPagedView.js" type="text/javascript"></script>
    <script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type=text/javascript></script>
    <script src="../../../js/s3Slider.js" type=text/javascript></script>
    <script src="../../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
    <script type="text/javascript">

    x=$(document);
    x.ready(inicializarEventos);

    function inicializarEventos()
    {
    	if ($("#txtCodProy").length > 0) {
    	$("#exportar").removeAttr("disabled");
    	}else{
    		$("#exportar").attr("disabled","-1");
    		}
    }

    var dsproyectos = null ;
    var pvProyectos = null;
    var pvProyectosPagedInfo = null;
    Loadproyectos();

    function Loadproyectos()
    {
    	var xmlurl = "../datos/process.xml.php?action=<?php echo(md5("buscar"));?>&idInst=<?php echo($ObjSession->IdInstitucion);?>";

    	dsproyectos = new Spry.Data.XMLDataSet(xmlurl, "proyectos/rowdata", {useCache: false});
    	pvProyectos	= new Spry.Data.PagedView(dsproyectos, { pageSize: 10});
    	pvProyectosPagedInfo = pvProyectos.getPagingInfo();
    }

    function ChangeVersion(id)
    {
        var sVersion = $("#cboversion").val();
        if(sVersion > 0) {
            $('#FormData').submit();
        }
        else {alert("No se especificado la version del Proyecto");}
    }

    function ExportarCronograma()
    {
    	var arrayControls = new Array();
    		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
    		arrayControls[1] = "idVersion=" + $('#cboversion').val();
    	var params = arrayControls.join("&");
    	var sID = "80" ;

    	//http://localhost/FE/sme/reportes/reportviewer.php?ReportID=3&idProy=C-08-02&idVersion=2
    	showReport(sID, params);
    }

    function showReport(reportID, params)
    {
        var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;

        $('#FormData').attr({target: "winReport"});
        $('#FormData').attr({action: newURL});
        $('#FormData').submit();
        $('#FormData').attr({target: "_self"});
        $("#FormData").removeAttr("action");
    }
    </script>
    <link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet" type="text/css" />
    <title><?php echo($objFunc->Title);?></title>
</head>
<body class="oneColElsCtrHdr">
	<div id="container">
		<div class="MenuBarHorizontalBack">
			<ul id="MenuBar1" class="MenuBarHorizontal">
                <?php include("../../../includes/MenuBar.php"); ?>
            </ul>
		</div>
        <script type='text/javascript'>
            var MenuBar1 = new Spry.Widget.MenuBar('MenuBar1');
        </script>
		<div class="Subtitle">
            <?php include("../../../includes/subtitle.php");?>
        </div>
		<div class="AccesosDirecto">
            <?php include("../../../includes/accesodirecto.php"); ?>
        </div>
		<div id="mainContent">
			<form id="FormData" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo($_SERVER['PHP_SELF']);?>">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="0%">&nbsp;</td>
						<td width="56%">
                        <?php
                            $ObjSession->VerifyProyecto();
                            $ObjSession->VerProyecto = 1;
                            $row = 0;
                            if ($ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0) {
                                $row = $objML->Proyecto->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
                            }
                        ?>
                            <fieldset id="proyecto">
								<legend>Busqueda de Proyectos</legend>

								<table width="96%" border="0" cellpadding="0" cellspacing="2" class="TableEditReg">
									<tr>
										<td width="102" valign="middle" nowrap="nowrap"><input
											name="txtidInst" type="hidden" id="txtidInst"
											value="<?php echo($row['t01_id_inst']);?>" /> <input
											name="txtCodProy" type="text" id="txtCodProy" size="16"
											title="Ingresar Siglas de la Institución"
											style="text-align: center;" readonly="readonly"
											value="<?php echo($row['t02_cod_proy']);?>" /></td>
										<td width="16" align="center" valign="middle" nowrap="nowrap">
											<input type="image" name="btnBuscar" id="btnBuscar"
											src="../../../img/gosearch.gif"
											class="Image" onclick="Buscarproyectos(); return false;"
											title="Seleccionar proyectos Ejecutoras" />
										</td>
										<td width="21" align="right" valign="middle">vs</td>
										<td width="39" valign="middle"><select name="cboversion"
											id="cboversion" onchange="ChangeVersion(this.id);"
											style="min-width: 50px;">
                                                <?php
                                                    $rsVer = $objProy->ListaVersiones($row['t02_cod_proy']);
                                                    $objFunc->llenarCombo($rsVer, 't02_version', 'nom_version', $row['t02_version']);
                                                ?>
                                            </select>
                                            <script language="javascript">
                                    			$("#cboversion").val("1");
                                    			$("#cboversion option").attr('disabled','disabled');
                                    			$("#cboversion option:first").removeAttr('disabled');
                                			</script>
                            			</td>
										<td width="252" valign="middle"><input name="txtNomejecutor"
											type="text" readonly="readonly" id="txtNomejecutor" size="53"
											value="<?php echo($row['t01_nom_inst']);?>" /></td>
									</tr>
									<tr>
										<td colspan="5" valign="middle" nowrap="nowrap"><input
											name="txtNomproyecto" type="text" readonly="readonly"
											id="txtNomproyecto" size="88"
											value="<?php echo($row['t02_nom_proy']);?>" /></td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td width="0%"></td>
						<td width="44%" align="right" valign="bottom"
							style="padding-right: 30px;">&nbsp;</td>
					</tr>
				</table>
				<div id="panelPopup" class="popupContainer"
					style="height: 500px; visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"><span id="titlePopup"></span></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialog01.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>
						<div class="popupContent" style="background-color: #FFF;">
							<div id="popupText"></div>
							<div id="divChangePopup" style="background-color: #FFF; color: #333;"></div>
						</div>
					</div>
				</div>
				<script language="javascript" type="text/javascript">
                	function LoadSubActividades()
                	{
                		var idProy = '<?php echo($row['t02_cod_proy']);?>';
                		var idVersion = '<?php echo($row['t02_version']);?>' ;
                		var idComp = $('#cboComponente').val();

                		var BodyForm = "action=<?php echo(md5("lista_costeo"));?>&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComp+"&modif=<?php if(($row["t02_env_croprod"]==1 || $row["t02_aprob_croprod"]==1) && ($ObjSession->PerfilID==$objHC->Ejec || $ObjSession->PerfilID==$objHC->GP || $ObjSession->PerfilID==$objHC->Admin)){ echo md5("enable");}?>";
                	 	var sURL = "cp_lista.php";
                		$('#divCronograma').html("<p align='center'><img src='../../../img/indicator.gif' width='16' height='16' /><br>Cargando..<br></p>");
                	 	var req = Spry.Utils.loadURL("POST", sURL, true, SuccessLoadCosteo, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: onErrorCosteo });

                	}

                	function SuccessLoadCosteo(req)
                    {
                        var respuesta = req.xhRequest.responseText;
                        $("#divCronograma").html(respuesta);
                        $($('#ids').val()).html('X');
                        $($('#ids-crct').val()).html('X');
                        return;
                    }

                	function onErrorCosteo(req)
                	{
                		return ;
                	}

                	function solicitudRevision()
                	{
                			var url = "proy_aprueba.php?idProy=<?php echo($row['t02_cod_proy']);?>&evcrprod=1&action=<?php echo(md5('solicita')); ?>";
                			loadPopup("Envio a Revisión del Cronograma de Productos", url);
                			return ;
                	}

                	function solicitudAprob()
                	{
                	    //if($('input[name=chkVB]').is(':checked')){
                	        var url = "proy_aprueba.php?idProy=<?php echo($row['t02_cod_proy']);?>&cronprod=1&action=<?php echo(md5('solicita')); ?>";
                			loadPopup("Aprobación del Cronograma de Productos", url);
                		    return ;
                		/*}else{
                			alert("Confirmar con V°B°");
                		}*/
                	}

                	function enviarCorreccion(){
                		var url = "../datos/proy_correccion.php?idProy=<?php echo($row['t02_cod_proy']);?>&cronprod=1&action=<?php echo(md5('solicita')); ?>";
                		loadPopup("Envio a Corrección el Cronograma de Productos", url);
                		return ;
                	}

                	function btnGuardarMsg()
                	{
                		$('#FormData').submit();
                	}

                	var spryPopupDialog01     = new Spry.Widget.PopupDialog("panelPopup", {modal:true, allowScroll:true, allowDrag:true});
                	var htmlLoading = "<p align='center'><img src='<?php echo(constant("PATH_IMG"));?>indicator.gif' width='16' height='16' /><br>Cargando..<br></p>";

                	function loadPopup(title, url)
                	{

                		$('#titlePopup').html(title);
                		$('#divChangePopup').html(htmlLoading);
                		$('#divChangePopup').load(url);
                		spryPopupDialog01.displayPopupDialog(true);
                		return false ;
                	}

                	function closePopup()
                	{
                		$('#divChangePopup').html("");
                		spryPopupDialog01.displayPopupDialog(false);
                	}
            	</script>
                <?php if($row["t02_aprob_ml"]==1){?>
                <div id="divContent" class="crngr-prod" style="font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px;">
					<div>
						<table width="99%" border="0" cellpadding="0" cellspacing="2">
							<tr>
								<td width="12%" height="30" valign="bottom"><strong>COMPONENTE</strong></td>
								<td valign="bottom">
									<select name="cboComponente" class="TextDescripcion"
									id="cboComponente" style="width: 520px;"
									onchange="LoadSubActividades();">
                <?php
                    $rs = $objML->ListadoDefinicionOE($row['t02_cod_proy'], $row['t02_version']);
                    $objFunc->llenarComboSinItemsBlancos($rs, 't08_cod_comp', 'descripcion', "",'',array(),'t08_comp_desc');
                ?>
								</select> &nbsp;&nbsp;&nbsp;
									<input type="button" value="Refrescar" name="imgRefresh"
									id="imgRefresh" onclick="LoadSubActividades(); return false;"
									class="btn_save_custom" style="padding: 2px 4px;" /> <span
									id="toolbar">
										<button class="boton" onclick="ExportarCronograma()" id="exportar" type="button">Exportar</button>
								</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="left" valign="middle" id="toolbar2"><br />
                        		<?php
                        		if($row["t02_aprob_croprod"]==1) { ?>
                        		    Cronograma de Productos con V°B°
                        	    <?php
                                } else if($ObjSession->PerfilID==$objHC->Ejec){
                                    if($row["t02_env_croprod"]==1){
                                ?>
                                        Enviado a Revisión
                                    <?php
                        	        } else { ?>
                        				<button class="boton" onclick="solicitudRevision(); return false;"/>Enviar a Revisión</button>
                        			<?php
                        			}
                    	        }else if($ObjSession->PerfilID==$objHC->GP && $row["t02_env_croprod"]==1) { ?>
                    				    <button class="boton" onclick="enviarCorreccion(); return false;"/>Enviar a Corrección</button>
                    				    <button class="boton" onclick="solicitudAprob(); return false;"/>Dar V°B°</button>
                        		<?php  } ?>
                                    <br /><br />
                                </td>
							</tr>
							<tr>
								<td height="24" colspan="2">
									<table width="770" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td colspan="15">

												<div id="divCronograma"></div>

											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
  <?php }else{ ?>
		<?php if($row['t02_cod_proy']) {?>
			<div id="Alerta"
					style="padding: 20px; font-size: 14px; color: #003366;"> Para continuar con el llenado del Cronograma de Productos, el Marco Lógico del Proyecto "<?php echo $row['t02_cod_proy']; ?>"  debe ser Aprobado</div>
		<?php }else{ ?>
			<div id="Alerta"
					style="padding: 20px; font-size: 14px; color: #003366;">Elija un
					Proyecto, antes de continuar..</div>
		<?php } ?>
<?php } ?>
                <br />
				<div id="panelEditSubAct" class="popupContainer"
					style="visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%"></td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogEditReg.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>
						<div class="popupContent">
							<div id="popupText2"></div>
							<iframe class="Iframe" src="poa_sact_edit.php"
								id="iPopup" name="iPopup" scrolling="no"
								style="width: 99%; height: 380px;"></iframe>
						</div>
					</div>
				</div>

        <script language="javascript" type="text/javascript">
		  function VerifyActividades()
		  {
			  var idComponente = $('#cboComponente').val();
			  if(idComponente > 0)
			  {
				  var idAct = $('#cboActividad').val();
				  if(idAct=="" || idAct==null )
				  {LoadActividades();}
			  }
		  }

		  function btnProgramarEntregables()
		  {
			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto"); return false;}
			  $('#iPopup').attr('src', "cp_ent_prog.php?idProy="+idProy+"&idVersion="+idVersion);
			  spryPopupDialogEditReg.displayPopupDialog(true);
			  return true;
		  }

		  function btnNuevo_Clic(idActividad)
		  {
			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();
			  //var idActividad = $('#cboActividad').val();
			  //var idAnio = $('#cboAnios').val();

			  if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto"); return false;}
			  if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad"); return false;}

			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv=";
			  var url = "poa_sact_edit.php?action=<?php echo(md5("new"));?>" + params ;
			  $('#iSubActividades').attr('src',url);
			  spryPopupDialogEditReg.displayPopupDialog(true);
			  return true;
		  }

		  function btnProgramarIndicador(idAct, idInd)
		  {
			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();

			  if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto"); return false;}

			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idAct="+idAct+"&idInd="+idInd;
			  var url = "cp_ind_prog.php?action=<?php echo(md5("edit"));?>&view=edit" + params ;
			  $('#iPopup').attr('src',url);
			  spryPopupDialogEditReg.displayPopupDialog(true);
			  return true;
		  }

		  function btnProgramarCaracteristica(idAct, idInd, idCar)
		  {
			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();

			  if( idProy == '' || idProy==null) {alert("Seleccione un Proyecto"); return false;}

			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idAct="+idAct+"&idInd="+idInd+"&idCar="+idCar;
			  $('#iPopup').attr('src', "cp_car_prog.php?" + params );
			  spryPopupDialogEditReg.displayPopupDialog(true);
			  return true;
		  }

		  function btnEliminarIndicador(idAct, idInd, ind)
		  {
			  <?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>
			  if(!confirm("¿Estás seguro de Eliminar el Indicador \n\"" + ind + "\"  y todas sus Características?")){return false ;}

			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();
			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idAct="+idAct+"&idInd="+idInd;
			  var url = "cp_ind_prog.php?action=<?php echo(md5("del"));?>" + params;
			  $('#iPopup').attr('src',url);
			  return true;
		  }

		  function btnEliminarCaracteristica(idAct, idInd, idCar, car)
		  {
			  <?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>
			  if(!confirm("¿Estás seguro de Eliminar la Característica \n\"" + car + "\" ?")){return false ;}

			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();
			  var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idAct="+idAct+"&idInd="+idInd+"&idCar="+idCar;
			  var url = "cp_car_prog.php?action=<?php echo(md5("del"));?>" + params;
			  $('#iPopup').attr('src',url);
			  return true;
		  }

		  function btnEliminar_Clic(idActividad, idSAct, Subact)
		  {
			  <?php $ObjSession->AuthorizedPage('ELIMINAR'); ?>

			  if(!confirm("¿ Estás seguro de Eliminar el Registro seleccionado \n\"" + Subact + "\"?")){return false ;}

			  var idProy = $('#txtCodProy').val();
			  var idVersion = $('#cboversion').val();
			  var idComponente = $('#cboComponente').val();

			  if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad"); return false;}
			  if( idSAct == '' || idSAct==null) {alert("Seleccione una Actividad"); return false;}

			  var params = "&t02_cod_proy="+idProy+"&t02_version="+idVersion+"&t08_cod_comp="+idComponente+"&t09_cod_act="+idActividad+"&t09_cod_sub="+idSAct;
			  var url = "poa_sact_edit.php?proc=<?php echo(md5("del"));?>" + params ;
			  //spryPopupDialogEditReg.displayPopupDialog(true);
			  $('#iSubActividades').attr('src',url);

			  return true;
		  }

		  function btnMetas_Clic(idActividad, idSAct, anio)
		  {
			var idProy = $('#txtCodProy').val();
			var idVersion = $('#cboversion').val();
			var idComponente = $('#cboComponente').val();

			if( idActividad == '' || idActividad==null) {alert("Seleccione una Actividad"); return false;}
			if( idSAct == '' || idSAct==null) {alert("No se ha seleccionado ninguna Actividad"); return false;}
			var params = "&idProy="+idProy+"&idVersion="+idVersion+"&idComp="+idComponente+"&idActiv="+idActividad+"&idSActiv="+idSAct+"&anio="+anio;

			var url = "poa_meta_edit.php?action=<?php echo(md5("edit"));?>" + params ;
		    $('#iSubActividades').attr('src',url);
		    spryPopupDialogEditReg.displayPopupDialog(true);
		  }

		  function btnExportar_onclick()
		  {
			  alert("En etapa de Desarrollo") ;
			  return false;
		  }

		  function btnCancel_Clic()
		  {
			  spryPopupDialogEditReg.displayPopupDialog(false);
			  return true;
		  }

		  function btnSuccess()
		  {
			  spryPopupDialogEditReg.displayPopupDialog(false);
			  LoadSubActividades();
			  return true;
		  }
		  </script>
				<div id="panelBusquedaProy" class="popupContainer"
					style="visibility: hidden;">
					<div class="popupBox">
						<div class="popupBar">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="100%">BUSQUEDA DE PROYECTOS</td>
									<td align="right"><a class="popupClose" href="javascript:;"
										onclick="spryPopupDialogBuqueda.displayPopupDialog(false);"><b>X</b></a></td>
								</tr>
							</table>
						</div>

						<div class="popupContent">
							<div id="popupText"></div>
							<div id="toolbar" style="height: 8PX;">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="11%" height="25" valign="middle">Buscar:</td>
										<td width="30%" valign="top"><input name="txtBuscaproyecto"
											type="text" class="Boton" id="txtBuscaproyecto"
											style="text-align: center;" title="Nombre del Proyecto"
											size="35" onkeyup="StartFilterInstTimer();" /><br /></td>
										<td width="5%" valign="middle">&nbsp;&nbsp;<input type="image"
											src="../../../img/btnRecuperar.gif"
											onclick="dsproyectos.loadData(); return false;"
											title="Actualizar Datos" /></td>
										<td width="5%" valign="middle"></td>
										<td width="49%" valign="middle"><span>Mostrar</span> <select
											name="cbopageSize" id="cbopageSize"
											onchange="pvProyectos.setPageSize(parseInt(this.value));"
											style="width: 60px">
												<option value="5">5 Reg.</option>
												<option value="10" selected="selected">10 Reg.</option>
												<option value="20">20 Reg.</option>
												<option value="30">30 Reg.</option>
												<option value="50">50 Reg.</option>
										</select> <span>Ir a </span> <span
											spry:region="pvProyectosPagedInfo" style="width: 75px;"> <select
												name="cboFilter" id="cboFilter"
												onchange="pvProyectos.goToPage(parseInt(this.value));"
												spry:repeatchildren="pvProyectosPagedInfo"
												style="width: 60px">
													<option
														spry:if="({ds_PageNumber} == pvProyectos.getCurrentPage())"
														value="{ds_PageNumber}" selected="selected">Pag.
														{ds_PageNumber}</option>
													<option
														spry:if="({ds_PageNumber} != pvProyectos.getCurrentPage())"
														value="{ds_PageNumber}">Pag. {ds_PageNumber}</option>
											</select>
										</span></td>
									</tr>
								</table>
							</div>
							<div class="TableGrid" spry:region="pvProyectos dsproyectos">
								<p spry:state="loading" align="center">
									<img src="../../../img/indicator.gif" width="16" height="16" /><br/>
									Cargando...
								</p>
								<table width="580" border="0" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<th width="115" height="26" align="center"
												spry:sort="ejecutor">EJECUTOR</th>
											<th width="59" align="center" spry:sort="codigo">CODIGO</th>
											<th width="38" align="center" spry:sort="exp">EXP</th>
											<th width="20" align="center" spry:sort="vs">VS</th>
											<th width="335" align="center" spry:sort="nombre">DESCRIPCION
												DEL PROYECTO</th>
										</tr>
									</thead>
									<tbody class="data" bgcolor="#FFFFFF">
										<tr class="RowData" spry:repeat="pvProyectos"
											spry:setrow="pvProyectos" id="{@id}"
											spry:select="RowSelected">
											<td align="left">{ejecutor}</td>

											<td align="center">
                            				<?php if($ObjSession->PerfilID==$objHC->Ejec){ ?>
                            					<span spry:choose="spry:choose"> <a
                            													href="javascript:Seleccionarproyecto();"
                            													title="Seleccionar Proyecto"
                            													spry:when="'{t02_aprob_ml}' == '1'">{codigo}</a> <span
                            													spry:when="'{t02_aprob_ml}' != '1'">{codigo}</span>
                            											</span>
                            				<?php }else{ ?>
                            					<a href="javascript:Seleccionarproyecto();"
                            												title="Seleccionar Proyecto">{codigo}</a>
                            				<?php }?>
                            				</td>
											<td align="center">{exp}</td>
											<td align="center">{vs}</td>
											<td align="left">{nombre}</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<th width="115">&nbsp;</th>
											<th width="59" align="right">&nbsp;</th>
											<th colspan="3" align="right"><input type="button"
												class="Boton" title="Ir a la Primera Pagina"
												onclick="pvProyectos.firstPage();" value="&lt;&lt;" /> <input
												type="button" class="Boton" title="Pagina Anterior"
												onclick="pvProyectos.previousPage();" value="&lt;" /> <input
												type="button" class="Boton" title="Pagina Siguiente"
												onclick="pvProyectos.nextPage();" value="&gt;" /> <input
												type="button" class="Boton" title="Ir a la Ultima Pagina"
												onclick="pvProyectos.lastPage();" value="&gt;&gt;" /></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
                <script language="Javascript" type="text/javascript">
                    var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
                    var spryPopupDialogEditReg= new Spry.Widget.PopupDialog("panelEditSubAct", {modal:true, allowScroll:true, allowDrag:true});

        			function Filterproyectos()
        			{
        			var tf = document.getElementById("txtBuscaproyecto");
        			if (!tf.value)
        			{
        				dsproyectos.filter(null);
        				return;
        			}

        			var regExpStr = tf.value;
        			//regExpStr = "^" + regExpStr;

        			var regExp = new RegExp(regExpStr, "i");
        			var filterFunc = function(ds, row, rowNumber)
        			{
        				//var str = row["ejecutor"];
        				var str = row["nombre"]+"|"+row["ejecutor"]+"|"+row["codigo"] ;
        				if (str && str.search(regExp) != -1)
        					return row;
        				return null;
        			};

        			dsproyectos.filter(filterFunc);
        			}

        			function StartFilterInstTimer()
        			{
        			    if (StartFilterInstTimer.timerID)
        			        clearTimeout(StartFilterInstTimer.timerID);
        			    StartFilterInstTimer.timerID = setTimeout(function() { StartFilterInstTimer.timerID = null; Filterproyectos(); }, 100);
        			}

        			function Buscarproyectos()
                    {
        			    spryPopupDialogBuqueda.displayPopupDialog(true);
                    }

        			function Seleccionarproyecto()
        			{
        				var rowid = dsproyectos.getCurrentRowID()
        				var row = dsproyectos.getRowByID(rowid);
        				if(row)
        				{
        					$("#txtidInst").val(row.t01_id_inst);
        					$("#txtCodProy").val(row.codigo);
        					//$("#cboversion").val(row.vs);
        					$("#txtNomejecutor").val( htmlEncode(row.ejecutor));
        					$("#txtNomproyecto").val(htmlEncode(row.nombre));


        					spryPopupDialogBuqueda.displayPopupDialog(false);

        					$("#divContent").css('display', 'block');
        					$("#divContentEdit").css('display', 'none');

        					$('#FormData').submit();
        					var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
        					$.get(setURL);

        					return;
        				}
        				else
        				{ alert("Error al Seleccionar el Proyecto"); }
        			}

        			LoadSubActividades();
    		    </script>

                <?php
                if ($ObjSession->MaxVersionProy($ObjSession->CodProyecto) > $ObjSession->VerProyecto && $ObjSession->PerfilID != $objHC->Admin) {
                    echo ("<script>alert('Esta versión \"" . $ObjSession->VerProyecto."\" del Proyecto \"".$ObjSession->CodProyecto."\", no es Modificable');</script>"); }
                ?>
			</form>
		</div>
		<div id="footer">
	        <?php include("../../../includes/Footer.php"); ?>
        </div>
	</div>
	<script language="javascript" type="text/javascript">
        var FormData = document.getElementById("FormData");
        function CloseSesion()
        {
        	if(confirm("Estas seguro de Cerrar la Sesión de <?php echo($ObjSession->UserName);?>"))
            {
            	FormData.action = "<?php echo(constant("DOCS_PATH"));?>closesesion.php";
            	FormData.submit();
            }
        	return true;
        }
    </script>
</body>
</html>
