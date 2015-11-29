<?php include("../../../includes/constantes.inc.php"); ?>
<?php include("../../../includes/validauser.inc.php"); ?>
<?php require(constant('PATH_CLASS')."HardCode.class.php");  ?>
<?php require(constant('PATH_CLASS')."BLProyecto.class.php");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
	$objFunc->SetTitle("Marco Lógico");
	$objFunc->SetSubTitle("Marco Lógico");
?>
<meta name="keywords" content="<?php echo($objFunc->Keywords); ?>" />
<meta name="description" content="<?php echo($objFunc->MetaTags); ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="../../../img/feicon.ico" type="image/x-icon">
<title><?php echo($objFunc->Title);?></title>
<link href="../../../css/template.css" rel="stylesheet" media="all" />
<script src="<?php echo(PATH_JS);?>general.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<script src="../../../SpryAssets/xpath.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryData.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPagedView.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery-1.2.6.js" type="text/javascript"></script>
<script src="../../../jquery.ui-1.5.2/jquery.numeric.js" type="text/javascript"></script>
<script src="../../../js/s3Slider.js" type="text/javascript"></script>
<script src="../../../js/commons.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryPopupDialog.js" type="text/javascript"></script>
<script src="../../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
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

function ExportarMarcoLogico()
{
	var arrayControls = new Array();
		arrayControls[0] = "idProy=" + $('#txtCodProy').val();
		arrayControls[1] = "idVersion=" + $('#cboversion').val();
	var params = arrayControls.join("&");
	var sID = "3" ;
	//http://localhost/FE/sme/reportes/reportviewer.php?ReportID=3&idProy=C-08-02&idVersion=2
	showReport(sID, params);
}

function showReport(reportID, params)
{
    var newURL = "<?php echo constant('PATH_RPT') ;?>reportviewer.php?ReportID=" + reportID + "&" + params ;
    $('#FormData').attr({target: "winReport"});
    //alert($('#FormData').attr({target: "winReport"}));
    $('#FormData').attr({action: newURL});
    $('#FormData').submit();
    $('#FormData').attr({target: "_self"});
    $("#FormData").removeAttr("action");
}

function ChangeVersion(id)
{
    var sVersion = $("#cboversion").val();
    if(sVersion > 0)
    {
    $('#FormData').submit();
    }
    else {alert("No se especificado la version del Proyecto");}
}
</script>

<link href="../../../SpryAssets/SpryPopupDialog.css" rel="stylesheet" type="text/css" />
<link href="../../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
</head>
<body class="oneColElsCtrHdr">
<div id="container">
    <div id="banner">
      <?php include("../../../includes/Banner.php"); ?>
  </div>
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
    <td width="58%" >
    <?php
		$objProy = new BLProyecto();
		$Function  = new Functions();

		$ObjSession->VerifyProyecto();
		$ObjSession->VerProyecto = 1 ;
		$row =0;

		if( $ObjSession->CodProyecto != "" && $ObjSession->VerProyecto > 0 )
        {
            $row = $objProy->GetProyecto($ObjSession->CodProyecto, $ObjSession->VerProyecto);
        }

		$tab1=$objFunc->__POST('tab1');
		$tab2=$objFunc->__POST('tab2');
		$tab3=$objFunc->__POST('tab3');
		$tab4=$objFunc->__POST('tab4');
		$tab5=$objFunc->__POST('tab5');
		$frame=$objFunc->__POST('frame');

        if($tab1==""){$tab1=0;}
        if($tab2==""){$tab2=0;}
        if($tab3==""){$tab3=0;}
        if($tab4==""){$tab4=0;}
        if($tab5==""){$tab5=0;}
	?>
      <fieldset id="proyecto">
  <legend>Busqueda de Proyectos</legend>

        <table width="91%" border="0" cellpadding="0" cellspacing="2" class="TableEditReg">
          <tr>
            <td width="102" valign="middle" nowrap="nowrap">

              <input name="txtidInst" type="hidden" id="txtidInst" value="<?php echo($row['t01_id_inst']);?>" />
              <!--input name="txtCodProy" type="text" id="txtCodProy" size="16" title="Ingresar Siglas de la Institución" style="text-align:center;" readonly="readonly" value="<d?php echo($row['t02_cod_proy']);?>" / modificado 01/12/2011-->
			  <input name="txtCodProy" type="text" id="txtCodProy" size="16" title="Ingresar Siglas de la Institución" style="text-align:center;" readonly="readonly" value="<?php echo($row['t02_cod_proy']);?>" />
              </td>
            <td width="17" align="center" valign="middle" nowrap="nowrap">
            <input type="image" name="btnBuscar" id="btnBuscar" src="../../../img/gosearch.gif" width="14" height="15" class="Image" onclick="Buscarproyectos(); return false;" title="Seleccionar proyectos Ejecutoras" /></td>
            <td width="13" align="right" valign="middle">vs</td>
            <td width="39" valign="middle">
            <select name="cboversion" id="cboversion" onchange="ChangeVersion(this.id);">
            <?php
            	$rsVer = $objProy->ListaVersiones($row['t02_cod_proy']) ;
				$objFunc->llenarCombo($rsVer,'t02_version','nom_version',$row['t02_version']);
			?>
            </select>
            <script language="javascript">
			$("#cboversion").val("1");
			$("#cboversion option").attr('disabled','disabled');
			$("#cboversion option:first").removeAttr('disabled');
			</script>
            </td>
            <td width="277" valign="middle"><input name="txtNomejecutor" type="text" readonly="readonly" id="txtNomejecutor" size="53" value="<?php echo($row['t01_nom_inst']);?>" /></td>
            </tr>
          <tr>
            <td colspan="5" valign="middle" nowrap="nowrap"><input name="txtNomproyecto" type="text" readonly="readonly" id="txtNomproyecto" size="88" value="<?php echo($row['t02_nom_proy']);?>" /></td>
            </tr>
          </table>
        </fieldset></td>
    <td width="0%"></td>
   <td width="42%">&nbsp;</td>
  </tr>
  </table>
  <div style="float:left; width:100%; white-space:nowrap; margin-bottom:8px; margin-top:8px;">
   <div id="toolbar" style="display: block;
    float: left;
    padding-bottom: 5px;
    width: 99%;">
    <table width="100%">
      <tr>
        <td align="left">
          <?php
			$vsProy = $row['t02_version'];
			$t02_aprob_ml = $row['t02_aprob_ml'] ;
			$apr_moni = $row['t02_aprob_ml_mon'] ;
			$apr_moni_obs = $row['t02_obs_ml'] ;
			$apr_moni_fch = $row['t02_fch_ml_mon'] ;
			$t02_vb_proy = $row['t02_vb_proy'] ;
			$t02_env_ml = $row['t02_env_ml'];
			$HC = new HardCode();

			if($vsProy==1 && $t02_vb_proy==1) {
                if($t02_aprob_ml==1) { ?>
                    Marco Lógico con V°B°                    
                <?php
                } else if($ObjSession->PerfilID==$HC->Ejec) {
                    if($t02_env_ml==1) { ?>
                        Enviado a Revisión
    	             <?php
                    } else { ?>
                        <button class="boton" onclick="SolicitaAprobarML(); return false;">Enviar a Revisión</button>
                    <?php
                    }
                } else if($ObjSession->PerfilID==$HC->GP && $t02_env_ml==1) { ?>
                        <button class="boton" onclick="enviarCorreccion(); return false;">Enviar a Corrección</button>
                        <button class="boton" onclick="AprobarML(); return false;">Dar V°B°</button>
                    <?php
                } else { ?>
                    Marco Lógico sin V°B°
                <?php
                }
                ?>
                
                <button class="boton" onclick="ExportarMarcoLogico(); return false;">Exportar</button>
                
                <?php 
		     } ?>
        </td>
      </tr>
    </table>
    </div>
  </div>
  <div id="divContent" style="font-family: Arial, Helvetica, sans-serif; padding-left:5px; padding-right:3px;" >
  <br />
  <br />	<?php if($t02_vb_proy==1){ ?>
    <div id="ssTabML" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0" onclick="getTabIndex(0,'#tab1');" >Finalidad</li>
        <li class="TabbedPanelsTab" tabindex="1" onclick="LoadInherit('iOG_definicion'); getTabIndex(1,'#tab1');">Proposito</li>
        <li class="TabbedPanelsTab" tabindex="2" onclick="LoadInherit('iOE_definicion'); getTabIndex(2,'#tab1');">Componentes</li>
        <!-- -------------------------------------------------- >
        // AQ 2.0 [29-10-2013 14:38]
        // Cambio de Actividades a Productos -->
		<li class="TabbedPanelsTab" tabindex="3" onclick="LoadInherit('iAct_definicion'); getTabIndex(3,'#tab1');">Productos</li>
		<!-- -------------------------------------------------- < -->
        </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
        <br />
          <div id="ssTabML_Fin" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
              <li class="TabbedPanelsTab" tabindex="0" onclick="LoadInherit('iOD_definicion'); getTabIndex(0,'#tab2');">Definición</li>
              <li class="TabbedPanelsTab" tabindex="1" onclick="LoadInherit('iOD_indicadores'); getTabIndex(1,'#tab2');">Indicadores</li>
              <li class="TabbedPanelsTab" tabindex="2" onclick="LoadInherit('iOD_hipotesis'); getTabIndex(2,'#tab2');">Supuestos</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
              <div class="TabbedPanelsContent">
                <iframe id="iOD_definicion" name="iOD_definicion" src="ml_def_OD.php?idProy=<?php echo($ObjSession->CodProyecto);?>&idVersion=<?php echo($ObjSession->VerProyecto)?>&modif=<?php if(($ObjSession->PerfilID==$HC->Ejec || $ObjSession->PerfilID==$HC->GP)&& ($t02_env_ml==1 || $t02_aprob_ml==1)) echo md5("enable");?>" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
              </iframe>
              </div>
              <div class="TabbedPanelsContent">
               <iframe id="iOD_indicadores" name="iOD_indicadores" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		       </iframe>
              </div>
              <div class="TabbedPanelsContent">
               <iframe id="iOD_hipotesis" name="iOD_hipotesis" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		       </iframe>
              </div>
            </div>
          </div>
          <p>&nbsp;</p>
        </div>
        <div class="TabbedPanelsContent">
          <br />
          <div id="ssTabML_Prop" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
              <li class="TabbedPanelsTab" tabindex="0" onclick="LoadInherit('iOG_definicion'); getTabIndex(0,'#tab3');">Definición</li>
              <li class="TabbedPanelsTab" tabindex="1" onclick="LoadInherit('iOG_indicadores'); getTabIndex(1,'#tab3');">Indicadores</li>
              <li class="TabbedPanelsTab" tabindex="2" onclick="LoadInherit('iOG_hipotesis'); getTabIndex(2,'#tab3');">Supuestos</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
              <div class="TabbedPanelsContent">
              	<iframe id="iOG_definicion" name="iOG_definicion" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
			    </iframe>
              </div>
              <div class="TabbedPanelsContent">
              	<iframe id="iOG_indicadores" name="iOG_indicadores" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
			    </iframe>
              </div>
              <div class="TabbedPanelsContent">
              	<iframe id="iOG_hipotesis" name="iOG_hipotesis" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		        </iframe>
              </div>
            </div>
          </div>
          <p>&nbsp;</p>
        </div>
        <div class="TabbedPanelsContent">
        <br />
          <div id="ssTabML_Comp" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
              <li class="TabbedPanelsTab" tabindex="0" onclick="LoadInherit('iOE_definicion'); getTabIndex(0,'#tab4');">Definición</li>
              <li class="TabbedPanelsTab" tabindex="1" onclick="LoadInherit('iOE_indicadores'); getTabIndex(1,'#tab4');">Indicadores</li>
              <li class="TabbedPanelsTab" tabindex="2" onclick="LoadInherit('iOE_hipotesis'); getTabIndex(2,'#tab4');">Supuestos</li>
			</ul>
            <div class="TabbedPanelsContentGroup">
              <div class="TabbedPanelsContent">
              	<iframe id="iOE_definicion" name="iOE_definicion" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		    	</iframe>
              </div>
              <div class="TabbedPanelsContent">
              	<iframe id="iOE_indicadores" name="iOE_indicadores" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		    	</iframe>
              </div>
              <div class="TabbedPanelsContent">
              	<iframe id="iOE_hipotesis" name="iOE_hipotesis" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		    	</iframe>
              </div>
		    </div>
          </div>
          <p>&nbsp;</p>
        </div>
<div class="TabbedPanelsContent">
<br />
  <div id="ssTabActiv" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
        <!-- -------------------------------------------------- >
        // AQ 2.0 [29-10-2013 14:38]
        // Cambio de Actividades a Productos y Adición de pestaña "Caracterśiticas" -->
        <li class="TabbedPanelsTab" tabindex="0" onclick="LoadInherit('iAct_definicion'); getTabIndex(0,'#tab5');">Definiciones</li>
        <li class="TabbedPanelsTab" tabindex="1" onclick="LoadInherit('iAct_indicadores'); getTabIndex(1,'#tab5');">Indicadores</li>
        <li class="TabbedPanelsTab" tabindex="2" onclick="LoadInherit('iAct_caracteristicas'); getTabIndex(2,'#tab5');">Características</li>
        <!-- -------------------------------------------------- < -->
</ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
      	<iframe id="iAct_definicion" name="iAct_definicion" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:370px;" scrolling="no" class="Iframe">
		</iframe>
      </div>
      <div class="TabbedPanelsContent">
      	<iframe id="iAct_indicadores" name="iAct_indicadores" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:470px;" scrolling="no" class="Iframe">
		</iframe>
      </div>
      <!-- -------------------------------------------------- >
      // AQ 2.0 [29-10-2013 15:25]
      // Cambio de Actividades a Productos y Adición de pestaña "Caracterśiticas" -->
      <div class="TabbedPanelsContent">
      	<iframe id="iAct_caracteristicas" name="iAct_caracteristicas" src="" frameborder="0" framespaging="0" marginwidth="0"  style="width:100%; height:470px;" scrolling="no" class="Iframe">
		</iframe>
      </div>
      <!-- -------------------------------------------------- < -->
</div>
  </div>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</div>
    </div>	<?php }else{ ?>		<?php if($row['t02_cod_proy']) {?>			<div id="Alerta" style="padding:20px; font-size:14px; color:#003366;"> Para continuar con el llenado del Marco Lógico, el Proyecto "<?php echo $row['t02_cod_proy']; ?>" debe ser Aprobado</div>		<?php }else{ ?>			<div id="Alerta" style="padding:20px; font-size:14px; color:#003366;"> Elija un Proyecto, antes de continuar.. </div>		<?php } ?>	<?php } ?>
 	<input name="tab1" type="hidden" id="tab1" value="<?php echo($tab1)?>" />
    <input name="tab2" type="hidden" id="tab2" value="" />
    <input name="tab3" type="hidden" id="tab3" value="" />
    <input name="tab4" type="hidden" id="tab4" value="" />
    <input name="tab5" type="hidden" id="tab5" value="" />
    <input name="frame" type="hidden" id="frame" value="" />

    <script language="javascript" type="text/javascript" >
	function LoadInherit(idFrame)
	{
		var IFrame = document.getElementById(idFrame);
		var sUrl = "about:blank";
		var params = "idProy=<?php echo($ObjSession->CodProyecto);?>&idVersion=<?php echo($ObjSession->VerProyecto)?>&modif=<?php if($ObjSession->PerfilID==$HC->Ejec && ($t02_env_ml==1 || $t02_aprob_ml==1)) echo md5("enable");?>&iframe="+idFrame;
		if(IFrame==null) { return false; }

		switch(IFrame.id)
		{
			case "iOD_definicion"  : sUrl = "ml_def_OD.php?" + params ; break;
			case "iOD_indicadores" : sUrl = "ml_ind_OD.php?" + params ; break;
			case "iOD_hipotesis"   : sUrl = "ml_hip_OD.php?" + params ; break;
			case "iOG_definicion"  : sUrl = "ml_def_OG.php?" + params ; break;
			case "iOG_indicadores" : sUrl = "ml_ind_OG.php?" + params ; break;
			case "iOG_hipotesis"   : sUrl = "ml_hip_OG.php?" + params ; break;
			case "iOE_definicion"  : sUrl = "ml_def_OE.php?" + params ; break;
			case "iOE_indicadores" : sUrl = "ml_ind_OE.php?" + params ; break;
			case "iOE_hipotesis"   : sUrl = "ml_hip_OE.php?" + params ; break;
			case "iAct_definicion" : sUrl = "ml_act_OE.php?" + params ; break;
			case "iAct_indicadores" : sUrl = "ml_ind_act.php?" + params ; break;
			// -------------------------------------------------- >
	    // AQ 2.0 [29-10-2013 13:25]
	    // Cambio de Actividades a Productos y Adición de pestaña "Características"
			case "iAct_caracteristicas" : sUrl = "ml_car_ind_act.php?" + params ; break;
	    // -------------------------------------------------- <
		}
		$('#frame').val(idFrame);
	try
	{
		if (window.frames[IFrame.id].location=="" || window.frames[IFrame.id].location=="about:blank")
		 {
			window.frames[IFrame.id].location = sUrl ;
		 }
	}
	catch(ex)
	{  alert("No se ha logrado cargar la pagina !!!"); }

		return false;
	}

	function getTabIndex(index,idTab){
		 $(idTab).val(index);
		}

	</script>
  </div>
  <div id="panelBusquedaProy" class="popupContainer" style="visibility:hidden;" >
    <div class="popupBox">
      <div class="popupBar">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100%">BUSQUEDA DE PROYECTOS</td>
            <td align="right">
              <a class="popupClose" href="javascript:;" onclick="spryPopupDialogBuqueda.displayPopupDialog(false);"><b>X</b></a></td>
            </tr>
          </table>
        </div>

      <div class="popupContent">
        <div id="popupText" >
        </div>
        <div id="toolbar" style="height:8px;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" >
          <tr>
            <td width="11%" height="25" valign="middle">Buscar:</td>
            <td width="30%" valign="top"><input name="txtBuscaproyecto" type="text" class="Boton" id="txtBuscaproyecto" style="text-align:center;" title="Nombre del Proyecto" size="35" onkeyup="StartFilterInstTimer();" /><br /></td>
            <td width="5%" valign="middle">&nbsp;&nbsp;<input type="image" src="../../../img/btnRecuperar.gif" width="16" height="16" onclick="dsproyectos.loadData(); return false;" title="Actualizar Datos" /></td>
            <td width="5%" valign="middle"></td>
            <td width="49%" valign="middle"><span >Mostrar</span>
              <select name="cbopageSize" id="cbopageSize" onchange="pvProyectos.setPageSize(parseInt(this.value));" style="width:60px">
                <option value="5">5 Reg.</option>
                <option value="10" selected="selected">10 Reg.</option>
                <option value="20" >20 Reg.</option>
                <option value="30" >30 Reg.</option>
                <option value="50" >50 Reg.</option>
              </select>
              <span >Ir a </span> <span spry:region="pvProyectosPagedInfo" style="width:75px;">
              <select name="cboFilter" id="cboFilter" onchange="pvProyectos.goToPage(parseInt(this.value));" spry:repeatchildren="pvProyectosPagedInfo" style="width:60px">
                <option spry:if="({ds_PageNumber} == pvProyectos.getCurrentPage())" value="{ds_PageNumber}" selected="selected">Pag. {ds_PageNumber}</option>
                <option spry:if="({ds_PageNumber} != pvProyectos.getCurrentPage())" value="{ds_PageNumber}">Pag. {ds_PageNumber}</option>
              </select>
              </span></td>
            </tr>
          </table>
          </div>
          <div class="TableGrid" spry:region="pvProyectos dsproyectos">
           <p spry:state="loading" align="center"><img src="../../../img/indicator.gif" width="16" height="16" /><br/>Cargando...</p>
           <table width="580" border="0" cellpadding="0" cellspacing="0">
              <thead>
              <tr>
                <th width="115" height="26" align="center" spry:sort="ejecutor">EJECUTOR</th>
                <th width="59" align="center" spry:sort="codigo">CODIGO</th>
                <th width="38" align="center" spry:sort="exp">EXP</th>
                <th width="20" align="center" spry:sort="vs">VS</th>
                <th width="335" align="center" spry:sort="nombre">DESCRIPCION DEL PROYECTO</th>
                </tr>
              </thead>
              <tbody class="data" bgcolor="#FFFFFF">
              <tr class="RowData" spry:repeat="pvProyectos" spry:setrow="pvProyectos" id="{@id}" spry:select="RowSelected">
                <td align="left">{ejecutor}</td>
                <!--td align="center"><A href="javascript:Seleccionarproyecto();" title="Seleccionar Proyecto">{codigo}</A></td modificado 30/11/2011-->
				<td align="center" spry:choose="spry:choose">
					<span spry:when="'{t02_estado}' == '40'"> {codigo}</span>
					<a href="javascript:Seleccionarproyecto();" title="Seleccionar Proyecto" spry:when="'{t02_estado}' != '40'">{codigo}</a>
				</td>
                <td align="center">{exp}</td>
                <td align="center">{vs}</td>
                <td align="left">{nombre}</td>
                </tr>
             </tbody>
             <tfoot>
             <tr>
                <th width="115" >&nbsp;</th>
                <th width="59" align="right" >&nbsp;</th>
                <th colspan="3" align="right" ><input type="button" class="Boton" title="Ir a la Primera Pagina"  onclick="pvProyectos.firstPage();"    value="&lt;&lt;" />
                  <input type="button" class="Boton" title="Pagina Anterior" onclick="pvProyectos.previousPage();" value="&lt;" />
                  <input type="button" class="Boton" title="Pagina Siguiente" onclick="pvProyectos.nextPage();" value="&gt;" />
                  <input type="button" class="Boton" title="Ir a la Ultima Pagina" onclick="pvProyectos.lastPage();" value="&gt;&gt;" /></th>
                </tr>
             </tfoot>
            </table>

          </div>
        </div>
      </div>
</div>

<div id="panelPopup" class="popupContainer" style="height:500px; visibility:hidden;" >
<div class="popupBox">
  <div class="popupBar">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><span id="titlePopup"></span></td>
        <td align="right">
          <a class="popupClose" href="javascript:;" onclick="spryPopupDialog01.displayPopupDialog(false);"><b>X</b></a></td>
        </tr>
      </table>
    </div>

  <div class="popupContent" style="background-color:#FFF;">
    <div id="popupText" >
    </div>
    <div id="divChangePopup" style="background-color:#FFF; color:#333;"></div>
    </div>
  </div>
</div>

<script language="JavaScript" type="text/javascript">
var spryPopupDialogBuqueda= new Spry.Widget.PopupDialog("panelBusquedaProy", {modal:true, allowScroll:true, allowDrag:true});
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

function SolicitaAprobarML()
{
	var url = "proy_aprueba.php?idProy=<?php echo($row['t02_cod_proy']);?>&evml=1&action=<?php echo(md5('solicita')); ?>";
	loadPopup("Envio a Revisión el Marco Lógico", url);
	return ;
}
function enviarCorreccion(){
	var url = "../datos/proy_correccion.php?idProy=<?php echo($row['t02_cod_proy']);?>&ml=1&action=<?php echo(md5('solicita')); ?>";
	loadPopup("Envio a Corrección el Marco Lógico", url);
	return false;
}

function AprobarML()
{
    var url = "proy_aprueba.php?idProy=<?php echo($row['t02_cod_proy']);?>&ml=1&action=<?php echo(md5('solicita')); ?>";
    loadPopup("Aprobación del Marco Lógico", url);
    return false;
}

function btnGuardarMsg(){
	$('#FormData').submit();
}

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
		//LoadDataGrid(row.t01_id_inst);

		var setURL = "<?php echo(constant("PATH_SME"));?>utiles.php?action=<?php echo(md5("setProyDefault"));?>&idProy=" + row.codigo ;
$.get(setURL);

		return;
	}
	else
	{ alert("Error al Seleccionar el Proyecto !!!"); }
}
</script>

  <script type="text/javascript">
	<!--
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("ssTabML",{defaultTab: <?php echo($tab1); ?>} );
	var TabbedPanels2 = new Spry.Widget.TabbedPanels("ssTabML_Fin",{defaultTab: <?php echo($tab2); ?>} );
	var TabbedPanels3 = new Spry.Widget.TabbedPanels("ssTabML_Prop", {defaultTab: <?php echo($tab3); ?>} );
	var TabbedPanels4 = new Spry.Widget.TabbedPanels("ssTabML_Comp", {defaultTab: <?php echo($tab4); ?>} );
	var TabbedPanels5 = new Spry.Widget.TabbedPanels("ssTabActiv", {defaultTab: <?php echo($tab5); ?>} );
	var frame = "<?php echo($frame); ?>";
	if(frame!="iOD_definicion"){LoadInherit(frame);}
	//-->
  </script>

<?php	$ObjSession->CodProyecto="*";
	if($ObjSession->MaxVersionProy($ObjSession->CodProyecto) > $ObjSession->VerProyecto)
	  { echo("<script>alert('Esta versión \"".$ObjSession->VerProyecto."\" del Proyecto \"".$ObjSession->CodProyecto."\", no es Modificable !!!');</script>");}
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
	if(confirm("Estas seguro de Cerrar la Sesion de <?php echo($ObjSession->UserName);?>"))
	  {
			FormData.action = "<?php echo(constant("DOCS_PATH"));?>closesesion.php";
			FormData.submit();
	  }
	return true;
}
</script>
</body>
</html>
