function btnCancelar_Clic()
{ CancelEdit(); }
	  
function MySuccessCallback(req)
{
  var respuesta = req.xhRequest.responseText;
  respuesta = respuesta.replace(/^\s*|\s*$/g,"");
  var ret = respuesta.substring(0,5);
  if(ret=="Exito")
  {
	alert(respuesta.replace(ret,""));
	ReloadLista();  
  }
  else
  {alert(respuesta);}  
  
}
		
function MyErrorCallback(req)
{
  alert("ERROR: " + req.xhRequest.responseText);
}

function serializeDIV(idContainer)
{
	var idFormSerialize = "frm_" + idContainer ;
	var idnewfrm = "#" + idFormSerialize;
	var idnewcontainer = "#" + idContainer;
	
    $('body').append('<form id="'+idFormSerialize+'" style="display:none;"></form>');
	var htmlcontainer = $(idnewcontainer).html();
    $(idnewfrm).html(htmlcontainer);
    var data = $(idnewfrm).serialize();
    $(idnewfrm).remove();
	return data;
}
