<?php
/**
 * CticServices
 *
 * Gestiona las Características de Productos
 *
 * @package     sme
 * @author      AQ
 * @since       Version 2.0
 *
 */
    include("../../../includes/constantes.inc.php");
    include("../../../includes/validauser.inc.php");
	require_once ("../../../clases/BLMarcoLogico.class.php");
	require_once("../../../clases/Functions.class.php");

	$Function  = new Functions();
	$objML = new BLMarcoLogico();

	$idProy = $Function->__GET('idProy');
	$idVersion = $Function->__GET('idVersion');
	$modifDispon = $Function->__GET('modif');
	$modificar = (md5("enable")==$modifDispon);

	$idComp = $Function->__POST("t08_cod_comp") ;
	$idAct  = $Function->__POST("t09_cod_act") ;
	$idInd  = $Function->__POST("t09_cod_act_ind") ;
	if($idComp==""){$idComp = $Function->__GET("t08_cod_comp") ; }
	if($idAct==""){$idAct = $Function->__GET("t09_cod_act") ; }
	if($idInd==""){$idInd = $Function->__GET("t09_cod_act_ind") ; }

	$urlEdicion = "ml_car_ind_act_edit.php?idProy=$idProy&idVersion=$idVersion&idOE=$idComp&idAct=$idAct&idInd=$idInd";
	$objFunc->SetSubTitle("Características de Producto");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=charset=utf-8" />
<title><?php echo($objFunc->SubTitle) ;?></title>
<link href="../../../css/itemplate.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
    <form action="<?php echo($_SERVER['PHP_SELF']);?><?php echo("?idProy=".$idProy."&modif=$modifDispon&idVersion=".$idVersion);?>" method="post" enctype="multipart/form-data" id="frmMain">
        <div id="toolbar" style="height:4px;" class="Subtitle">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="5%">
                  <button class="Button" onclick="return Nuevo();" value="Guardar" <?php if($modificar) echo "disabled"; ?>>Nuevo </button>
              </td>
              <td width="11%"><button class="Button" value="Cancelar" onclick="return Refrescar();"> Refrescar</button></td>
              <td align="center" style="color:#00F;"><?php echo($objFunc->SubTitle) ;?></td>
            </tr>
            </table>
    	</div>
        <table width="742" height="45" border="0" cellpadding="0" cellspacing="0" class="TableEditReg">
            <tr>
                <td width="93" height="21" align="left"><span class="TextDescripcion"><b>Componente</b></span> </td>
                <td width="324" align="left">
                    <select name="t08_cod_comp" id="t08_cod_comp" style="width:520px;" onchange="ChangeLista(0);">
                    <option value="" selected="selected"></option>
                    <?php
                    	$rsOE = $objML->ListadoDefinicionOE($idProy, $idVersion);
                    	$Function->llenarComboSinItemsBlancos($rsOE,'t08_cod_comp','descripcion', $idComp,'',array(),'t08_comp_desc');
                    ?>
                    </select>
                </td>
                <td width="325" align="left">
                    <input type="button" value="Refrescar" title="Refrescar" name="btnRefresh" id="btnRefresh" style="background-color:#EEEEEE; border:1px solid ##999999; cursor:pointer; font-weight:bolder; font-size:11px;" />
                </td>
            </tr>
            <tr>
                <td height="22" align="left"><span class="TextDescripcion"><b>Producto</b></span></td>
                <td align="left">
                    <select name="t09_cod_act" id="t09_cod_act" style="width:530px;" onchange="ChangeLista(1);">
                        <option value=""></option>
                        <?php
                        	$rsAct = $objML->ListadoActividadesOE($idProy, $idVersion, $idComp);
                        	//$Function->llenarCombo($rsAct,'t09_cod_act','descripcion', $idAct);
                        	$Function->llenarComboSinItemsBlancos($rsAct,'t09_cod_act','descripcion', $idAct,'',array(),'t09_act');
                        ?>
                    </select>
                </td>
                <td align="left">&nbsp;</td>
            </tr>
            <tr>
                <td height="22" align="left"><span class="TextDescripcion"><b>Indicador</b></span></td>
                <td align="left">
                    <select name="t09_cod_act_ind" id="t09_cod_act_ind" style="width:530px;" onchange="ChangeLista(2);">
                        <option value=""></option>
                        <?php
                        	$rsInd = $objML->ListadoIndicadoresAct($idProy, $idVersion, $idComp, $idAct);
                        	//$Function->llenarCombo($rsInd,'t09_cod_act_ind', 'descripcion', $idInd);
                        	$Function->llenarComboSinItemsBlancos($rsInd,'t09_cod_act_ind', 'descripcion', $idInd,'',array(),'t09_ind');
                        ?>
                    </select>
                </td>
                <td align="left">&nbsp;</td>
            </tr>
            <tr>
                <td align="left">&nbsp;</td>
                  <?php
                  	$rCar = $objML->listarCaracteristicas($idProy, $idVersion, $idComp, $idAct, $idInd);
                	$rowCar = mysql_fetch_assoc($rCar); //Recuperamos el primer registro
                	$objML = NULL; //Liberamos recursos
                  ?>
            </tr>
        </table>
        <?php
            $GridHeigth=250;
            $GridWidth = 700;
        ?>
        <table  border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="11">&nbsp;</td>
                <td width="731" colspan="3" rowspan="2">
        	        <div class="TableGrid">
        	            <div style="overflow:auto; width:<?php echo($GridWidth-2);?>px; height:<?php echo($GridHeigth);?>px; border: 1px solid #abb6ec;">
                            <table width="<?php echo($GridWidth -20);?>" border="1" cellpadding="0" cellspacing="0" >
                                <thead>
                                    <tr>
                                        <th width="20" nowrap="nowrap" height="25" >&nbsp;</th>
                                        <th width="180">Característica</th>
                                        <th width="310">Fuentes de Verificación</th>
                                        <th width="20">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody class="data" >
                                <?php do { ?>
                                <?php 	$indicador = trim($rowCar['t09_ind']);
                                		//if(empty($indicador)) continue;
                                	?>
                                    <tr class="RowData">
                                        <td width="20" nowrap="nowrap">
                                            <span>
                                                <?php if($rowCar['t09_cod_act_ind_car']>0 && !$modificar) { ?>
                                                <a href="#"><img src="../../../img/b_edit.png" width="14" height="14" title="Editar Registro" border="0" onclick="Editar('<?php echo $rowCar['t09_cod_act_ind_car']; ?>');" /></a>
                                                <?php } ?>
                                            </span>
                                        </td>
                                        <td width="180" align="left"><?php echo $indicador; ?></td>
                                        <td align="left"><?php echo $rowCar['t09_fv']; ?></td>
                                        <td width="20" align="left">
                                            <?php /* if($rowCar['t09_cod_act_ind_car']>0 && !$modificar) { ?>
                                            <a href="#"><img src="../../../img/b_drop.png" width="14" height="14" title="Eliminar Registro" border="0" onclick="Eliminar('<?php echo $rowCar['t09_cod_act_ind_car']; ?>', '<?php echo $rowCar['t09_ind']; ?>');" /></a>
                                            <?php } */ ?>
                                        </td>
                                    </tr>
                                <?php } while ($rowCar = mysql_fetch_assoc($rCar)); ?>
                                </tbody>
                            </table>
                        </div>
        				<table width="<?php echo($GridWidth);?>" border="1" cellpadding="0" cellspacing="0">
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </form>

    <script language="javascript" type="text/javascript">
        function ChangeLista(tipo)
        {
            console.log("tipo: " + tipo);
            if(tipo==0){document.getElementById("t09_cod_act").value="";}
            //if(tipo==1){document.getElementById("t09_cod_act_ind").value="";}

            var formulario = document.getElementById("frmMain") ;
            console.log("action: " + formulario.action);
            formulario.submit() ; return false;
        }

        function Nuevo()
        {
            <?php $ObjSession->AuthorizedPage(); ?>
            var formulario = document.getElementById("frmMain") ;
            formulario.action = "<?php echo($urlEdicion);?>&view=new&id=";
            formulario.submit() ;
            return false;
        }

        function Editar(codigo)
        {
            var formulario = document.getElementById("frmMain") ;
            formulario.action = "<?php echo($urlEdicion);?>&view=edit&id=" + codigo;
            formulario.submit() ;
        }

        function Eliminar(codigo,Descripcion)
        {
            <?php $ObjSession->AuthorizedPage(); ?>
            if(confirm("Estas seguro de eliminar el Registro \n" + Descripcion))
            {
                var formulario = document.getElementById("frmMain") ;
                formulario.action = "<?php echo($urlEdicion);?>&id=" + codigo + "&proc=<?php echo(md5("del"));?>";
                formulario.submit() ;
            }
        }

        function Refrescar(){
            window.location.reload();
            return false;
        }
    </script>
</body>
</html>