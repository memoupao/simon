<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="380" nowrap="nowrap"><span class="title"><?php echo($objFunc->SubTitle) ;?></span></td>
		<td width="304" align="right">
    <?php if($ObjSession->MostrarBotonesAccesoDirecto) { ?>
    <div style="width: 044px;" align="left"> 
    	<?php
        $ObjSession->VerifyProyecto(); // Verifamos el QueryString de Proyectos
        $rsBotones = $ObjSession->ListaBotonesAccesoDirecto($ObjSession->PerfilID);
        $Index = 0;
        while ($rowB = mysqli_fetch_assoc($rsBotones)) {
            $img = $rowB['mnu_img'];
            if ($img == '') {
                $img = 'addicon.gif';
            }
            $url = $rowB['mnu_link'] . $rowB['cparam'] . 'proyecto=' . $ObjSession->CodProyecto . '&version=' . $ObjSession->VerProyecto;
            $imgButon = "<a href='" . $url . "' target='" . $rowB['mnu_target'] . "' title='" . $rowB['mnu_nomb'] . "'><img src='" . constant('PATH_IMG') . $img . "' width='23' height='23' /></a> \n";
            $imgSpace = "<img src='" . constant('PATH_IMG') . "blank.gif' width='2' height='21' /> \n";
            
            if ($Index > 0) {
                echo ($imgSpace);
            }
            echo ($imgButon);
            
            $Index ++;
        }
        $rsBotones->free();
        ?>
    </div>
    <?php } ?>
    </td>
		<td width="116" align="right">
			<div style="width: 400px; vertical-align: text-top;">
				<b><?php echo(substr($ObjSession->PerfilName,0,25));?>: </b>
    	<?php echo(substr($ObjSession->UserName,0,25));?>&nbsp;&nbsp;
    	<a href="<?php echo(constant("DOCS_PATH"));?>default.php"><img
					src="<?php echo(constant('PATH_IMG'));?>home.png"
					title="Página de Inicio" width="21" height="21" /></a> <img
					src="<?php echo(constant('PATH_IMG'));?>blank.gif" width="2"
					height="21" /> 
        <?php if($ObjSession->PerfilID==1 && strpos($_SERVER['PHP_SELF'],"/Admin/") <=0) { ?>
        <a
					href="<?php echo(constant("DOCS_PATH")."Admin/default.php");?>"
					target="_blank"><img
					src="<?php echo(constant('PATH_IMG'));?>config.gif"
					title="Modulo de Administradores y Configuración del Sistema"
					width="23" height="23" /></a> <img
					src="<?php echo(constant('PATH_IMG'));?>blank.gif" width="2"
					height="21" /> 
        <?php } ?>
        <a href="#"><img
					src="<?php echo(constant('PATH_IMG'));?>/help.png"
					title="Manual de Ayuda" width="21" height="21" /></a> <img
					src="<?php echo(constant('PATH_IMG'));?>blank.gif" width="2"
					height="21" /> <a href="#"><img
					src="<?php echo(constant('PATH_IMG'));?>close.png"
					title="Cerrar Sesion" width="21" height="21"
					onclick="CloseSesion();" /></a>
			</div>
		</td>
	</tr>
</table>