<?php

class Validator
{

    static public function validateRuc($pRuc)
    {
        if (! is_numeric($pRuc))
            return 'El valor no es numérico.';
        if (strlen($pRuc) != 11)
            return 'Número de Dígitos incorrecto.';
        
        
        // -------------------------------------------------->
        // DA 2.0 [14-11-2013 21:57]
        // Actualizacion del algoritmo para comprobar el RUC:
        // Fuente: Pagina de SUNAT
        $suma = 0;
        $x = 6;
        for ($i=0; $i<strlen($pRuc)-1; $i++) {
            if ( $i == 4 ) $x = 8;
            $digito = $pRuc[$i];
            $x--;
            if ( $i==0 ) $suma += ($digito*$x);
            else $suma += ($digito*$x);
        }
        $resto = $suma % 11;
        $resto = 11 - $resto;
    
        if ( $resto >= 10) $resto = $resto - 10;
        if ( $resto == $pRuc[ strlen($pRuc)-1 ] ) {
            
            return true;
        }
        
        return false;
        // --------------------------------------------------<
        
        /*
        $aHash = array(
            5,
            4,
            3,
            2,
            7,
            6,
            5,
            4,
            3,
            2
        );
        $aTotal = 0;
        $aChkDig = substr($pRuc, 10, 1);
        $aDiff = - 1;
        for ($i = 0; $i < strlen($pRuc) - 1; $i ++)
            $aTotal += intval(substr($pRuc, $i, 1)) * $aHash[$i];
        $aDiff = 11 - ($aTotal % 11);
        $aDiff = $aDiff == 11 ? 1 : ($aDiff == 10 ? 0 : $aDiff);
        return $aChkDig == $aDiff;
        */
    }

    static public function validateDate($pDate, $pDay = 0, $pMon = 1, $pYear = 2)
    {
        $aDateArr = explode('/', $pDate);
        return checkdate($aDateArr[$pMon], $aDateArr[$pDay], $aDateArr[$pYear]);
    }

    static public function compareDates($pDate1, $pDate2, $pDay = 0, $pMon = 1, $pYear = 2)
    {
        if (! Validator::validateDate($pDate1, $pDay, $pMon, $pYear) || ! Validator::validateDate($pDate2, $pDay, $pMon, $pYear)) {
            return false;
        }
        
        $aDate1Arr = explode('/', $pDate1);
        $aDate2Arr = explode('/', $pDate2);
        $aTime1 = mktime(0, 0, 0, $aDate1Arr[$pMon], $aDate1Arr[$pDay], $aDate1Arr[$pYear]);
        $aTime2 = mktime(0, 0, 0, $aDate2Arr[$pMon], $aDate2Arr[$pDay], $aDate2Arr[$pYear]);
        return (($aTime1 == $aTime2) ? 0 : ($aTime1 > $aTime2 ? 1 : - 1));
    }
}
?>
