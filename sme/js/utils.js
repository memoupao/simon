function trim(cadena){
  cadena2 = "";
  len = cadena.length;
  for ( var i=0; i <= len ; i++ ) if ( cadena.charAt(i) != " " ){cadena2+=cadena.charAt(i);	}
  return cadena2;
}
function esnulo(campo){ return (campo == null||campo=="");}
function esnumero(campo){ return (!(isNaN( campo )));}
function eslongrucok(ruc){return ( ruc.length == 11 );}
function valruc(valor){
  var valor = trim(valor);
  if ( esnumero( valor ) ) {
    if ( valor.length == 8 ){
      var suma = 0, digito;
      for (i=0; i<valor.length-1;i++){
        digito = valor.charAt(i) - '0';
        if ( i==0 ) suma += (digito*2)
        else suma += (digito*(valor.length-i));
      }
      resto = suma % 11;
      if ( resto == 1) resto = 11;
      if ( resto + ( valor.charAt( valor.length-1 ) - '0' ) == 11 ){
        return true;
      }
    } else if ( valor.length == 11 ){
      suma = 0;
      x = 6;
      for ( i=0; i<valor.length-1;i++){
        if ( i == 4 ) x = 8;
        digito = valor.charAt(i) - '0';
        x--;
        if ( i==0 ) suma += (digito*x);
        else suma += (digito*x);
      }
      resto = suma % 11;
      resto = 11 - resto;
      
      if ( resto >= 10) resto = resto - 10;
      if ( resto == valor.charAt( valor.length-1 ) - '0' ){
        return true;
      }      
    }
  }
  return false;
}


function esrucok(ruc){
  return (!( esnulo(ruc) || !esnumero(ruc) || !eslongrucok(ruc) || !valruc(ruc) ));
}

