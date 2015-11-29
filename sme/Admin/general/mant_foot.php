<?php
/**
 * CticServices
 *
 * Código reutilizable incuido en el pié de paǵina para el mantenimiento de conceptos.
 *
 * @package	Admin/general
 * @author	AQ
 * @since	Version 2.0
 *
 */
?>
<input type="button" class="Boton" title="Ir a la Primera Pagina"
	onclick="pvLista.firstPage();" value="<<"/>
	          		<input 
	type="button" class="Boton" title="Pagina Anterior"
	onclick="pvLista.previousPage();" value="<"/>
					<input 
	type="button" class="Boton" title="Pagina Siguiente"
	onclick="pvLista.nextPage();" value=">" />
<input type="button" class="Boton" title="Ir a la Ultima Pagina"
	onclick="pvLista.lastPage();" value=">>" />
</th>
</tr>
</tfoot>
</table>
<p spry:state="loading" align="center" id="pLoading">
	<img src="../img/indicator.gif" width="16" height="16" /><br>
	Cargando...
</p>
</div>

</div>
<div id="divContentEdit"
	style="position: relative; font-family: Arial, Helvetica, sans-serif; padding-left: 5px; padding-right: 3px; border: none;">
</div>

<script language="javascript" type="text/javascript">
    function btnNuevo_Clic()
	{
		$("#divContent").fadeOut("slow");
		$("#divContent").css('display', 'none');
		var url = "man_concepto_edit.php?action=<?php echo(md5("ajax_new"));?>&id=&concepto=<?php echo($concepto);?>" ;
		loadUrlSpry("divContentEdit",url);
		return;
	}

    function btnEditar_Clic(id)
    {
	    if(dsLista.getRowCount()==0)
	    {
	    	alert("No ha seleccionado ningun registro.");
	    	return;
	    }

	    var url = "man_concepto_edit.php?action=<?php echo(md5("ajax_edit"));?>&id="+id+"&concepto=<?php echo($concepto);?>";
	    loadUrlSpry("divContentEdit",url);
	    $("#divContent").fadeOut("slow");
	    $("#divContent").css('display', 'none');

	    return;
    }

    function ReloadLista()
    {
	    $("#divContentEdit").fadeOut("slow");
	    $("#divContentEdit").css('display', 'none');
	    $("#divContent").fadeIn("slow");
	    $("#divContent").css('display', 'block');
	    dsLista.loadData();
    }

    function CancelEdit()
    {
	    $("#divContentEdit").fadeOut("slow");
	    $("#divContent").fadeIn("slow");
	    $("#divContent").css('display', 'block');
	    $("#divContentEdit").css('display', 'none');
	    return true;
    }

    function Eliminar(codigo,Descripcion)
    {
	    <?php $ObjSession->AuthorizedPage(); ?>

	    if(dsLista.getRowCount()==0)
	    {
		    alert("No hay Registros para eliminar");
		    return;
	    }

	    if(confirm("Estas seguro de eliminar el " + Descripcion))
	    {
		    var BodyForm = "id="+codigo;
		    var sURL = "man_concepto_process.php?action=<?php echo(md5("ajax_del"))?>&concepto=<?php echo($concepto);?>";
	    	var req = Spry.Utils.loadURL("POST", sURL, true, MySuccessCall, { postData: BodyForm, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
	    }
    }

    var idContainerLoading = "";
    function loadUrlSpry(ContainerID, pURL)
    {
		idContainerLoading = "#"+ContainerID;
		$(idContainerLoading).css('display', 'none');
		var req = Spry.Utils.loadURL("GET", pURL, true, MySuccessLoad, { headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }, errorCallback: MyErrorCall});
    }

    function MySuccessLoad(req)
    {
    	var respuesta = req.xhRequest.responseText;
    	$(idContainerLoading).css('display', 'block');
    	$(idContainerLoading).html(respuesta);
    	var htmlshowin = $("#EditForm").html();
    	$(idContainerLoading).html(htmlshowin);
    	$(idContainerLoading).fadeIn("slow");
    	return;
    }

    function MySuccessCall(req)
    {
	    var respuesta = req.xhRequest.responseText;
	    respuesta = respuesta.replace(/^\s*|\s*$/g,"");
	    var ret = respuesta.substring(0,5);

	    if(ret=="Exito"){
		    alert(respuesta.replace(ret,"")); dsLista.loadData();
	    }
	    else{
		    alert(respuesta);
	    }
    }

    function MyErrorCall(req){
    	alert("ERROR: " + req);
    }

</script>

<script language="javascript">
  LoadDataGrid();
  </script>

<div id="panelPWD" class="popupContainer">
	<div class="popupBox">
		<div class="popupBar">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%">Cambiar Contraseña</td>
					<td align="right"><a class="popupClose" href="javascript:;"
						onclick="spryPopupDialogPWD.displayPopupDialog(false);"><b>X</b></a></td>
				</tr>
			</table>
		</div>

		<div class="popupContent" style="background-color: #FFF;">
			<div id="popupText"></div>

			<div id="divChangePWD"></div>

		</div>
	</div>
</div>

<script language="JavaScript" type="text/javascript">
  var spryPopupDialogPWD= new Spry.Widget.PopupDialog("panelPWD", {modal:true, allowScroll:true, allowDrag:true});

   function ChangePWD()
	{
		var rowid = dsLista.getCurrentRowID();
		var row = dsLista.getRowByID(rowid);
		var iduser = '' ;
		if(row)
		{ iduser = row.coduser; }

		var url = 'man_usu_pwd.php?mode=<?php echo(md5("change_pwd_mante"));?>&id='+iduser ;
		loadUrlSpry("divChangePWD", url)
		spryPopupDialogPWD.displayPopupDialog(true);
		return false ;
	}

  </script>


<!-- InstanceEndEditable -->
</form>
</div>
<div id="footer">
	<?php include("../includes/Footer.php"); ?>
  </div>

<!-- Fin de Container Page-->
</div>

<script language="javascript" type="text/javascript">
//FormData : Formulario Principal
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
<!-- InstanceEnd -->
</html>