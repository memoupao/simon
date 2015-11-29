<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");

require_once (constant("PATH_CLASS") . "BLProyecto.class.php");
require_once (constant("PATH_CLASS") . "BLEjecutor.class.php");
require_once (constant("PATH_CLASS") . "BLTablasAux.class.php");

$OjbTab = new BLTablasAux();
$action = $objFunc->__Request('action');
$idProy = $objFunc->__Request('idProy');
$tSolicitud = "";
$abrev = "";
$ml = $objFunc->__Request('ml');
$cron = $objFunc->__Request('cron');
$pre = $objFunc->__Request('pre');
// modificado 01/11/2011
$proy = $objFunc->__Request('proy');
$vbpy = $objFunc->__Request('vbpy');
$evml = $objFunc->__Request('evml');
$evcr = $objFunc->__Request('evcr');
$evpr = $objFunc->__Request('evpr');
$evel = $objFunc->__Request('evel');
$scp = $objFunc->__Request('scp');
$idscp = $objFunc->__Request('idscp');
$reff = $objFunc->__Request('reff');
$ceff = $objFunc->__Request('ceff');
$aeff = $objFunc->__Request('aeff');
$estadoeff = $objFunc->__Request('estadoeff');
$codigoeff = $objFunc->__Request('codigoeff');
$srdg = $objFunc->__Request('srdg');
$ieva = $objFunc->__Request('ieva');

// -------------------------------------------------->
// AQ 2.0 [28-11-2013 17:15]
// Envío a Revisión y Aprobación
// del Cronograma de Productos
$evcrprod = $objFunc->__Request('evcrprod');
$cronprod = $objFunc->__Request('cronprod');
// --------------------------------------------------<

// DA 2.0 [28-11-2013 09:21]
// La variable $idVer deberia de ser la version del proyecto no esta definida
// y se utiliza (mas abajo) como parametro en la funcion ProyectoSeleccionar
// Por el momento si no esta definido optamos por darle el valor de 1 (version 1)
if (!isset($idVer)) {
    $idVer = 1;
}
// -------------------------------------------------->


$objProy = new BLProyecto();
$rproy = $objProy->ProyectoSeleccionar($idProy, $idVer);
$nom_proy = $rproy['t02_cod_proy'] . " - " . $rproy['t01_sig_inst'];

$objFunc->SetSubTitle("Aprobación del Proyecto");
if ($ml == '1') {
    $objFunc->SetSubTitle("Aprobación Marco Lógico");
    $tSolicitud = "Marco Lógico del Proyecto";
    $abrev = "ml";
}
if ($cron == '1') {
    $objFunc->SetSubTitle("Aprobación Cronograma de Actividades");
    $tSolicitud = "Cronograma de Actividades";
    $abrev = "cron";
}
if ($pre == '1') {
    $objFunc->SetSubTitle("Aprobación Presupuesto");
    $tSolicitud = "Presupuesto del Proyecto";
    $abrev = "pre";
}
// modificado 01/11/2011
if ($proy == '1') {
    $objFunc->SetSubTitle("Aprobación Proyecto");
    $tSolicitud = "Proyecto";
    $abrev = "proy";
}
if ($vbpy == '1') {
    $objFunc->SetSubTitle("Solicitud de Aprobación del Proyecto");
    $tSolicitud = "Proyecto";
    $abrev = "vbpy";
}
if ($evml == '1') {
    $objFunc->SetSubTitle("Solicitud de Revisión del Marco Lógico");
    $tSolicitud = "Marco Lógico";
    $abrev = "evml";
}
if ($evcr == '1') {
    $objFunc->SetSubTitle("Solicitud de Revisión del Cronograma de Actividades");
    $tSolicitud = "Cronograma de Actividades";
    $abrev = "evcr";
}
if ($evel == '1') {
    $objFunc->SetSubTitle("Solicitud de Aprobación del Equipo Lider");
    $tSolicitud = "Equipo Lider";
    $abrev = "evel";
}
if ($evpr == '1') {
    $objFunc->SetSubTitle("Solicitud de Aprobación del Presupuesto");
    $tSolicitud = "Presupuesto";
    $abrev = "evpr";
}
if ($scp == '1') {
    $objFunc->SetSubTitle("Aprobación de Solicitud de Cambio de Personal");
    $tSolicitud = "Cambio de Personal";
    $abrev = "scp";
}
if ($reff == '1') {
    $objFunc->SetSubTitle("Solicitud de Revisión del Informe de Evaluación Financiera de Fondoempleo");
    $tSolicitud = "Evaluación Financiera de Fondoempleo";
    $abrev = "reff";
}
if ($ceff == '1') {
    $objFunc->SetSubTitle("Solicitud de Corrección del Informe de Evaluación Financiera de Fondoempleo");
    $tSolicitud = "Evaluación Financiera de Fondoempleo";
    $abrev = "ceff";
}
if ($aeff == '1') {
    $objFunc->SetSubTitle("Solicitud de Aprobación del Informe de Evaluación Financiera de Fondoempleo");
    $tSolicitud = "Evaluación Financiera de Fondoempleo";
    $abrev = "aeff";
}
if ($srdg == '1') {
    $objFunc->SetSubTitle("Solicitud de Revisión de Datos Generales");
    $tSolicitud = "Revisión del Proyecto";
    $abrev = "srdg";
}
if ($ieva == '1') {
    $objFunc->SetSubTitle("Confirmación de proyecto habilitado");
    $tSolicitud = "Información del Proyecto";
    $abrev = "ieva";
}

// -------------------------------------------------->
// AQ 2.0 [28-11-2013 17:15]
// Envío a Revisión y Aprobación
// del Cronograma de Productos
if ($evcrprod == '1') {
    $tSolicitud = "Cronograma de Productos";
    $objFunc->SetSubTitle("Solicitud de Revisión del ".$tSolicitud);
    $abrev = "evcrprod";
}
if ($cronprod == '1') {
    $tSolicitud = "Cronograma de Productos";
    $objFunc->SetSubTitle("Aprobación Cronograma del ".$tSolicitud);
    $abrev = "cronprod";
}
// --------------------------------------------------<

// $action = md5("solicita")
// $action = md5("aprueba")

if ($objFunc->__QueryString() == '') {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
    <title></title>
    <script language="javascript" type="text/javascript" src="../../../jquery.ui-1.5.2/jquery-1.2.6.js"></script>
    <link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
<?php
}
?>
  <div id="toolbar" style="height: 4px;" class="BackColor">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="25%"><button class="Button"
						onclick="spryPopupDialog01.displayPopupDialog(false); return false;"
						value="Volver y Cerrar" style="white-space: nowrap;">Volver y
						Cerrar</button></td>
				<td width="14%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="50%" align="right"><?php echo($objFunc->SubTitle) ;?></td>
			</tr>
		</table>
	</div>
	<table width="558" border="0" cellspacing="1" cellpadding="0"
		class="TableEditReg" style="margin-left: 20px;">
		<tr>
			<td height="79"><em> <span class="contenttext"><strong>
	<?php

if ($reff == '1') {
    echo '&iquest; Estas Seguro de Enviar a Revisión  el Informe de Evaluacion Financiera de Fondoempleo &quot; ?';
} else
    if ($ceff == '1') {
        echo '&iquest; Estas Seguro de Enviar a Corrección  el Informe de Evaluacion Financiera de Fondoempleo &quot; ?';
    } else
        if ($aeff == '1') {
            echo '&iquest; Estas Seguro de Enviar a Aprobación el Informe de Evaluacion Financiera de Fondoempleo &quot; ?';
        } else
            if ($srdg == '1') {
                echo '&iquest; Estas Seguro de Enviar a Revisión los Datos Generales del Proyecto &quot; ?';
            } else
                if ($ieva == '1') {
                    echo '&iquest; Estas Seguro de Enviar la Información &quot; ?';
                }

                else
                    if ($cron == '1' || $cronprod == '1') {
                        echo '&iquest; Estas Seguro de Enviar a Aprobación el ' . $tSolicitud . ' del Proyecto ' . ($idProy) . '?';
                    } else
                        if ($evml == '1' || $evcr == '1' || $evpr == '1' || $evcrprod == '1') {
                            echo '&iquest; Estas Seguro de Enviar a Revisión el ' . $tSolicitud . ' del Proyecto ' . ($idProy) . '?';
                        } else {
                            ?>

	&iquest; Estas Seguro de Enviar a Aprobación <?php echo($tSolicitud); ?> &quot;<?php echo($idProy); ?>
	&quot; ?
    </strong></span><br />
    Al enviar a Aprobación no se permitirá modificar posteriormente cualquier elemento del <?php echo($tSolicitud); ?></em></td>
   <?php } ?>
   </tr>
		<tr>
			<td height="17"><strong>Mensaje
	<?php
    if ($scp == '1') {
        echo ' al Ejecutor';
    } elseif ($evcr == '1' || $evcrprod == '1') {
        echo ' al Gestor de Proyecto';
    } elseif ($ieva == '1') {
        echo ' a Administración';
    } else {
        echo 'Fondoempleo';
    }
    ?>
	</strong></td>
		</tr>
		<tr>
			<td><textarea name="txtmensaje" cols="" rows="6" class="Aprueba" id="txtmensaje" style="width: 99%"></textarea></td>
		</tr>
		<tr>
			<td height="58" align="right" id="toolbar"><input type="hidden"
				name="txttipoaprueba" id="txttipoaprueba"
				value="<?php echo($abrev); ?>" class="Aprueba" />

        	 <?php if( $reff=='1' || $ceff=='1' || $aeff=='1' ) { ?>
        	<button class="Button"
        					onclick="GuardarAprobacionFinanciero(); return false;"
        					value="Guardar" style="white-space: nowrap; color: black;">Enviar</button>
        	 <?php } elseif( $ieva=='1' ) { ?>
        	<button class="Button" onclick="GuardarEnvio(); return false;"
        				value="Guardar" style="white-space: nowrap; color: black;">Enviar</button>
        	<?php } else {?>
        	<button class="Button" onclick="GuardarAprobacion(); return false;"
        				value="Guardar" style="white-space: nowrap; color: black;">Enviar</button>
            <?php } ?>
        </td>
	</tr>
		<tr>
			<td height="58" align="right" id="toolbar"><input type="hidden"
				name="txtidscp" id="txtidscp" value="<?php echo($idscp); ?>"
				class="Aprueba" /></td>
		</tr>
	</table>
	<script language="javascript" type="text/javascript">
    function GuardarAprobacion()
    {
     <?php $ObjSession->AuthorizedPage(); ?>
        var BodyForm="idProy=<?php echo($idProy);?>&" + "estado=<?php echo($estadoeff);?>&" + "txteff=<?php echo($codigoeff);?>&" + $('#FormData .Aprueba').serialize();
        var sURL = "../planifica/proy_aprueba_process.php?action=<?php echo(md5("guardar_aprob_".$abrev));?>";
        var req = Spry.Utils.loadURL("POST", sURL, true, AprobacionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
    }

    function GuardarEnvio()
    {
     <?php $ObjSession->AuthorizedPage(); ?>
     var BodyForm="idProy=<?php echo($idProy);?>&" + $('#FormData .Aprueba').serialize();
     var sURL = "../planifica/proy_aprueba_process.php?action=<?php echo(md5("guardar_aprob_".$abrev));?>";
     var req = Spry.Utils.loadURL("POST", sURL, true, AprobacionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
     }
    function GuardarAprobacionFinanciero()
    {
     <?php $ObjSession->AuthorizedPage(); ?>
     var BodyForm="idProy=<?php echo($idProy);?>&" + "estado=<?php echo($estadoeff);?>&" + "txteff=<?php echo($codigoeff);?>&" + $('#FormData .Aprueba').serialize();
     var sURL = "../../proyectos/planifica/proy_aprueba_process.php?action=<?php echo(md5("guardar_aprob_".$abrev));?>";
     var req = Spry.Utils.loadURL("POST", sURL, true, AprobacionSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall });
    }

    function AprobacionSuccessCallback(req)
    {
      var respuesta = req.xhRequest.responseText;
      respuesta = respuesta.replace(/^\s*|\s*$/g,"");
      var ret = respuesta.substring(0,5);
      if(ret=="Exito")
      {
    	var vs = respuesta.substring(0,6);
    	alert(respuesta.replace(vs,""));
    	spryPopupDialog01.displayPopupDialog(false);
    	vs = vs.replace(ret,"");
    	Seleccionarproyecto();
      }
      else
      {alert(respuesta);}
    }

    function MyErrorCall(req)
    {	  alert("ERROR: " + req.responseText);   }
    </script>
<?php if($objFunc->__QueryString()==NULL){?>
</body>
</html>
<?php } ?>
