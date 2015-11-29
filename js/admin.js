/*!
 * CticServices
 * DA 2.0 [04-11-2013 10:20]
 *  
 */

/*!
* Valida el Formulario de ingreso y edicion en mantenimientos
*
* @return boolean true si es correcto sino false en caso de error
*
*/
function isValidFormsMantenimiento()
{
	var isValid = true;
	if ($('form').length) {
		
		var form = $('form');
		var nombre = form.find('input#txtnombre'), abrev = form.find('input#txtabrev');
		//var nombre_concepto = form.find('input#nombre'), abreviatura_concepto = form.find('input#abreviatura');
		
		if(nombre.length==0) nombre = form.find('input#nombre');
		if(abrev.length==0) abrev = form.find('input#abreviatura');
		
		if ($('form').attr('action').match('man_tasa.php') || $('form').attr('action').match('man_parametros.php') ){
			if (abrev.length && abrev.val().length < 3) {
				isValid = false;
				alert('Ingrese mínimo 3 caracteres');
				abrev.focus();
			}
		} else {
			if (nombre.length && nombre.val().length < 3) {
				isValid = false;
				alert('Ingrese mínimo 3 caracteres');
				nombre.focus();
			}
			
			if (abrev.length && abrev.val().length < 3) {
				isValid = false;
				alert('Ingrese mínimo 3 caracteres');
				abrev.focus();
			}
			
		}
		
		 
		
		
		
		
			
		
		if (!isValid) {			
			return isValid;
		}
		
	
		if ($('form').attr('action').match('man_tipos_lista.php') || $('form').attr('action').match('man_subtipos_lista.php') ) {
			
			var orden = form.find('input#txtorden');
			if(orden.val().length == 0) {
				isValid = false;
				alert('Ingrese el Número de Orden');
				orden.focus();
			}
			
		}
		
		if ($('form').attr('action').match('man_concursos.php') ) {
			
			var anio = form.find('input#txtanio');
			if(anio.val().length == 0) {
				isValid = false;
				alert('Ingrese el Año del Concurso');
				anio.focus();
			}
			
			if (anio.val().match(/[0-9]*/)[0] < 4) {
				isValid = false;
				alert('El Año ingresado no es válido');
				anio.focus();
			}
			
			
		}
		
		if ($('form').attr('action').match('man_parametros.php') ) {
			
			var pnumero=/^\d+(\.\d{1,7})?$/;
			var valorTasa = form.find('input#nombre');
			
			if (!pnumero.test(valorTasa.val())) {
				isValid = false;
				alert('El valor de la Tasa no es válido');
				valorTasa.focus();
			} else {
				if (valorTasa.val() < 0 || valorTasa.val() > 100 ) {
					isValid = false;
					alert('El valor de la Tasa no es válido. Rango permitido es de 0 a 100');
					valorTasa.focus();
				}
			}
			
		}
		
		

		
		
			
						
		
	}
		
	return isValid;
}



(function($){
	
	$(document).ready(function(){
		
		
		
	});
	
	
})(jQuery);