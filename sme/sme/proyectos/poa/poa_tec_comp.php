<?php
include("../../../includes/constantes.inc.php");
include("../../../includes/validauser.inc.php");
require (constant("PATH_CLASS") . "BLPOA.class.php");
require_once(constant("PATH_CLASS")."HardCode.class.php");

error_reporting("E_PARSE");
$idProy = $objFunc->__Request('idProy');
$idVersion = $objFunc->__Request('idVersion');
$idAnio = $objFunc->__Request('idAnio');
$objPOA = new BLPOA();
$objHC = new HardCode();

if ($idProy == "") {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/templateIframe.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>POA - Indicadores de Componentes</title>
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
<div id="divTableLista" class="TableGrid">
			<table width="780" cellpadding="0" cellspacing="0" class="">
				<thead>

				</thead>
				<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
					<td height="40" colspan="2" align="left" valign="middle"
						bgcolor="#FFFFFF"><span
						style="color: #003; font-size: 13px; font-weight: bold;">Metas de
							Indicadores de Componente para el Año.</span></td>
					<td height="40" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
					<td height="40" align="center" valign="middle" bgcolor="#FFFFFF">
						<div align="center" style="height: 20px; display: inline-table; padding-top: 25px;">
							<input type="button" value="Refrescar" width="22" title="Refrescar Metas de Indicadores" onclick="LoadComponentes(true);" class="btn_save_custom" />
							&nbsp; &nbsp;
                            <?php
                                $rowPOA = $objPOA->POA_Seleccionar($idProy, $idAnio);
                                $disabledGuardar = "";
                                if ($rowPOA['t02_estado'] == $objHC->especTecAprobRA) {
                                    $disabledGuardar = " disabled";
                                }
                            ?>
							<input type="button" value="Guardar" width="22" title="Guardar Metas de Indicadores" onclick="Guardar_IndicadoresComponente();" class="btn_save_custom" <?php echo($disabledGuardar);?>/> <br />
						</div>
					</td>
				</tr>
				<tbody class="data" bgcolor="#FFFFFF">
    <?php
    $iRs = $objPOA->POA_ListadoIndicadoresComponente($idProy, $idVersion, $idAnio);
    $RowIndex = 0;
    if ($iRs->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($iRs)) {
            $tipo = $row["tipo"];

            $itemText = trim($row['indicador']);
            if(empty($itemText)) continue;


            ?>
     <?php if($tipo=="componente") { ?>
      <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td height="15" colspan="5" align="left" valign="middle"><strong><?php echo($row['codigo'].".- ".$row['indicador']);?></strong></td>
					</tr>
					<tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="296" align="left" valign="middle"><strong>Indicador de
								Componente</strong></td>
						<td height="15" colspan="3" align="center" valign="middle"><strong>Valor</strong></td>
						<td width="220" align="center" valign="middle"><strong>
								Observaciones</strong></td>
					</tr>
     <?php } else { ?>
     <tr style="border: solid 1px #CCC; background-color: #eeeeee;">
						<td width="296" rowspan="2" align="left" valign="middle"
							bgcolor="#FFFFFF"><?php echo($row['codigo'].".- ".$row['indicador']);?>
         <input name="t08_cod_comp_ind[]" type="hidden"
							id="t08_cod_comp_ind[]" value="<?php echo($row['codigo']);?>"
							class="componentes" /> <br /> <span><strong style="color: red;">Unidad
									Medida</strong>: <?php echo( $row['um']);?></span> <br /> <span><strong
								style="color: red;">Meta Total</strong>: <?php echo($row['metatotal']);?></span>
						</td>
						<td width="85" height="16" align="center"><strong>Acumulado </strong></td>
						<td width="86" align="center"><strong>Anual</strong></td>
						<td width="91" align="center"><strong>Total</strong></td>
						<td width="220" rowspan="2" align="center" valign="top"
							bgcolor="#FFFFFF"><textarea name="txtIndCompDescrip[]" cols=""
								rows="3" id="txtIndCompDescrip[]"
								style="width: 100%; text-align: left;" class="componentes"><?php echo($row['descripcion']);?></textarea>
						</td>
					</tr>
					<tr>
						<td align="center" nowrap="nowrap"><input name="txtIndCompAcum[]"
							type="text" id="txtIndCompAcum[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejecutado_acum']);?>" size="4"
							readonly="readonly" class="componentes" /></td>
						<td align="center" nowrap="nowrap"><input name="txtIndCompMeta[]"
							type="text" id="txtIndCompMeta[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							onkeyup="TotalAvanceIndicadorComp('<?php echo($RowIndex);?>');"
							value="<?php echo($row['meta']);?>" size="4" class="componentes" /></td>
						<td align="center" nowrap="nowrap"><input name="txtIndCompTot[]"
							type="text" id="txtIndCompTot[]"
							style="padding: 0px; width: 100%; height: 100%; text-align: center;"
							value="<?php echo($row['ejecutado_acum'] + $row['meta']);?>"
							size="4" readonly="readonly" class="componentes" /></td>
					</tr>
     <?php

$RowIndex ++;
            }
            ?>

    <?php
        }
        $iRs->free();
    }     // Fin de Indicadores de Componentes
    else {
        echo ("<b style='color:red'>" . "El Proyecto no cuenta con Indicadores de componentes para el Año " . $idAnio . ", " . "o Aún no se ha generado la nueva Versión del Proyecto" . "...<br />Verificar el Marco Lógico</b>");
        exit();
    }

    ?>
    </tbody>
				<tfoot>
				</tfoot>
			</table>
			<input name="t02_cod_proy" type="hidden" class="componentes"
				id="t02_cod_proy" value="<?php echo($idProy);?>" /> <input
				name="t02_anio" type="hidden" class="componentes" id="t02_anio"
				value="<?php echo($idAnio);?>" /> <input name="t02_version"
				type="hidden" class="componentes" id="t02_version"
				value="<?php echo($idVersion);?>" />
			<script language="javascript" type="text/javascript">
function Guardar_IndicadoresComponente	()
{
<?php $ObjSession->AuthorizedPage(); ?>


var comp = $('#cbocomponente_ind').val();
if(comp==""){alert("El componente seleccionado, no Tiene indicadores !!!"); return;}
var aValidFlg = true;
$("[id='txtIndCompMeta[]']").each(function(){
	var aValue = $(this).val();
	if (aValue != "" && isNaN(parseFloat(aValue))) {
		aValidFlg = false;
		alert("Valor Anual ingresdo no es correcto");
		$(this).focus();
		return false;
	}
});
if (!aValidFlg) return false;

var BodyForm=$("#FormData .componentes").serialize();

if(confirm("Estas seguro de Guardar el avance de los Indicadores de Componente para el Informe ?"))
{
	var sURL = "poa_tec_process.php?action=<?php echo(md5('ajax_ind_componente'));?>";
	var req = Spry.Utils.loadURL("POST", sURL, true, indCompSuccessCallback, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCallback });
}

}

function indCompSuccessCallback	(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	LoadComponentes(true);
	alert($('<div></div>').html(respuesta.replace(ret,"")).text());
  }
  else
  {alert(respuesta);}
}

function TotalAvanceIndicadorComp(x)
{
  var index=parseInt(x) ;
  var xTotal=document.getElementsByName("txtIndCompTot[]") ;
  var xAcum =document.getElementsByName("txtIndCompAcum[]");
  var xAnio =document.getElementsByName("txtIndCompMeta[]") ;

  var mtaacum =parseFloat(xAcum[index].value) ;
  var mtaanio =parseFloat(xAnio[index].value) ;
  if(isNaN(mtaacum)){mtaacum=0;}
  if(isNaN(mtaanio)){mtaanio=0;}
  var total=(mtaacum + mtaanio);
  xTotal[index].value = total ;

}
</script>

			<script type='text/javascript'>
	$(':input[id^=txtIndCompMeta]').numeric().pasteNumeric();
</script>

		</div>
<?php if($idProy=="") { ?>
</form>
	<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
<?php } ?>