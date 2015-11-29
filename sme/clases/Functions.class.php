<?php

class Functions
{
    /* número de error y texto error */
    var $Errno = 0;

    var $Error = "";

    var $Title = "Pagina Sin Titulo";

    var $SubTitle = "Sin Sub-Titulo";

    var $MetaTags = "Sistema SGP";

    var $Keywords = "sistema, eml, monitoreo, seguimiento, fondoempleo, sgp";

    var $Ajax = false;

    var $Fecha = NULL;

    var $Hora = NULL;

    var $oSession = NULL;


    /**
     *
     * @var string  Cadena SALT para encriptacion de variables.
     */
    protected $saltEncrypt;


    /*
     * Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función
     */
    function __construct()
    {
        $this->saltEncrypt = '$sAlTcTiC2013$';

        // echo(get_browser());
        // echo(md5("ajax"));
        date_default_timezone_set("America/Lima");
        $this->Fecha = date("d/m/Y", time());
        $this->Hora = date("H:i:s", time());
        return true;
    }

    function SetTitle($title)
    {
        $this->Title = $title;
    }

    function SetSubTitle($subtitle)
    {
        $this->SubTitle = $subtitle;
    }

    function SetMetaTags($tags)
    {
        $this->MetaTags = $tags;
    }

    function SetKeyWords($keys)
    {
        $this->Keywords = $keys;
    }

    function __GET($idControl)
    {
        try {
            error_reporting(0);
            $ret = $_GET[$idControl];
        } catch (Exception $ex) {
            $ret = "";
        }

        return $ret;
    }

    function __QueryString($noKey = "")
    {
        try {
            error_reporting(0);
            $ret = "?";
            foreach ($_REQUEST as $key => $value) {
                $KeyValue = $key . "=" . $value . "&";
                $ret = str_replace($KeyValue, "", $ret);
                if ($key != "view" && $key != "proc" && $key != $noKey) {
                    $ret .= $key . "=" . $value . "&";
                }
            }
            if ($ret != "") {
                $ret = substr($ret, 0, strlen($ret) - 1);
            }
        } catch (Exception $ex) {
            $ret = "";
        }

        return $ret;
    }

    function __POST($idControl)
    {

        /*
         * Cuando no existe el indice no necesariamente es capturado por el catch ejemplo: Notice: Undefined index: txtuser in sgp\clases\Functions.class.php on line 71
         */
        if (! isset($_POST[$idControl]))
            return '';

        try {
            $ret = $_POST[$idControl];
        } catch (Exception $ex) {
            $ret = "";
        }

        return $ret;
    }

    function __Request($Index)
    {
        $value = $this->__POST($Index);
        if ($value == "") {
            $value = $this->__GET($Index);
        }
        return $value;
    }

    // Convierte en formato de Fecha aceptable por MySql
    function convertDate($fecha)
    {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $lafecha;
    }

    function GetPageName()
    {
        $page = $_SERVER['PHP_SELF'];
        return $page;
    }

    function llenarCombo($ConsultaID, $IndexValue = 'id', $IndexText = 'Name', $ValueDefault = "", $IndexTitle = "", $arData = array())
    {
        if ($ValueDefault != "") {
            $ls_return = $ValueDefault;
        } else {
            $ls_return = "";
        }
        // Mostramos los registros en etiquetas <OPTION>
        if ($IndexTitle == "") {
            while ($row = mysql_fetch_assoc($ConsultaID)) { // Inicio while
                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = "selected";
                } else {
                    $selected = "";
                    $ls_return = $row[$IndexValue];
                }
                // -------------------------------------------------- >
                // DA 2.0 [09-11-2013 15:36]
                // Nuevos atributos de data-NAME para las etiquetas OPTION
                $cadData = '';
                foreach ($arData as $item) {
                    $cadData.= ' data-'.$item.'='.chr(34).$row[$item].chr(34).' ';
                }
                // -------------------------------------------------- <
                echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . $cadData.  ">" . $row[$IndexText] . "</option>\n";
            } // fin while
        } else {
            while ($row = mysql_fetch_assoc($ConsultaID)) { // Inicio while
                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = "selected";
                } else {
                    $selected = "";
                    $ls_return = $row[$IndexValue];
                }

                // -------------------------------------------------- >
                // DA 2.0 [09-11-2013 15:36]
                // Nuevos atributos de data-NAME para las etiquetas OPTION
                $cadData = '';
                foreach ($arData as $item) {
                    $cadData.= ' data-'.$item.'='.chr(34).$row[$item].chr(34).' ';
                }
                // -------------------------------------------------- <
                echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . $cadData.  " title='" . $row[$IndexTitle] . "'>" . $row[$IndexText] . "</option>\n";
            } // fin while
        }

        //mysql_free_result($ConsultaID);
        return $ls_return;
    } // Fin Function llenaCombo



    /**
     * Genera salida HTML de grupos de option con los resultados de
     * de una consulta a la Base de datos. Los datos son filtrados sin items vacios.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param resource $ConsultaID Resource devuelto de mysqli::query o mysqli_query (mixed)
     * @param string $IndexValue Es el valor del atributo value del option
     * @param string $IndexText Es el valor del texto del option
     * @param string $ValueDefault Es el valor por defecto que estará seleccionado
     * @param string $IndexTitle Es el valor del atributo title del option
     * @param array $arData Valores para atributos data-NOMBRECAMPO en la etiqueda OPTION
     * @param string $campoVacio    Nombre del campo ha verificar si es vacio o no.
     * @return boolean
     *
     */
    function llenarComboSinItemsBlancos($ConsultaID, $IndexValue = 'id', $IndexText = 'Name', $ValueDefault = "", $IndexTitle = "", $arData = array(), $campoVacio = null)
    {
        if ($ValueDefault != "") {
            $ls_return = $ValueDefault;
        } else {
            $ls_return = "";
        }
        // Mostramos los registros en etiquetas <OPTION>
        if ($IndexTitle == "") {
            while ($row = mysql_fetch_assoc($ConsultaID)) { // Inicio while
                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = "selected";
                } else {
                    $selected = "";
                    $ls_return = $row[$IndexValue];
                }
                // -------------------------------------------------- >
                // DA 2.0 [09-11-2013 15:36]
                // Nuevos atributos de data-NAME para las etiquetas OPTION
                $cadData = '';
                foreach ($arData as $item) {
                    $cadData.= ' data-'.$item.'='.chr(34).$row[$item].chr(34).' ';
                }
                // -------------------------------------------------- <

                if ($campoVacio) {
                    if ( !empty($row[$campoVacio]) ){
                        echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . $cadData.  ">" . $row[$IndexText] . "</option>\n";
                    }

                } else {
                    echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . $cadData.  ">" . $row[$IndexText] . "</option>\n";
                }


            } // fin while
        } else {
            while ($row = mysql_fetch_assoc($ConsultaID)) { // Inicio while
                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = "selected";
                } else {
                    $selected = "";
                    $ls_return = $row[$IndexValue];
                }

                // -------------------------------------------------- >
                // DA 2.0 [09-11-2013 15:36]
                // Nuevos atributos de data-NAME para las etiquetas OPTION
                $cadData = '';
                foreach ($arData as $item) {
                    $cadData.= ' data-'.$item.'='.chr(34).$row[$item].chr(34).' ';
                }
                // -------------------------------------------------- <
                if ($campoVacio) {
                    if ( !empty($row[$campoVacio]) ){
                        echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . $cadData.  " title='" . $row[$IndexTitle] . "'>" . $row[$IndexText] . "</option>\n";
                    }
                } else {
                    echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . $cadData.  " title='" . $row[$IndexTitle] . "'>" . $row[$IndexText] . "</option>\n";
                }
            } // fin while
        }

        //mysql_free_result($ConsultaID);
        return $ls_return;
    } // Fin Function llenaCombo




    /**
     * Genera salida HTML de grupos de option con los resultados de
     * de una consulta a la Base de datos.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param resource $ConsultaID Resource devuelto de mysqli::query o mysqli_query (mixed)
     * @param string $IndexValue Es el valor del atributo value del option
     * @param string $IndexText Es el valor del texto del option
     * @param string $ValueDefault Es el valor por defecto que estará seleccionado
     * @param string $IndexTitle Es el valor del atributo title del option
     * @param array $arData Valores para atributos data-NOMBRECAMPO en la etiqueda OPTION
     * @return boolean
     *
     */
    function llenarComboI($ConsultaID, $IndexValue = 'id', $IndexText = 'Name', $ValueDefault = '', $IndexTitle = '')
    {
        $cont = 0;
        if ($ValueDefault != "") {
            $ls_return = $ValueDefault;
        } else {
            $ls_return = '';
        }
        
        
        // Mostramos los registros en etiquetas <OPTION>
        if ($IndexTitle == '') {
        	

            while ($row = mysqli_fetch_assoc($ConsultaID)) { // Inicio while

                $cont ++;
                if ($cont == 1) {
                    $ls_return = $row[$IndexValue];
                }

                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = 'selected';
                    $ls_return = $row[$IndexValue];
                } else {
                    // $selected=($ConsultaID->num_rows==$cont && $ls_return=="" ? "selected" : "" );
                    $selected = "";
                    // $ls_return =$row[$IndexValue] ;
                    // if($selected!="") {$ls_return=$row[$IndexValue];}
                }

            	$texto = $row[$IndexText];
            	if (strstr($texto,'.-')) {
             		$arT = explode('.-',$texto);
            		$text = (isset($arT[1]) ? trim($arT[1]) : '');
            		if (!empty($text)) {
            			echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . ">" . $texto . "</option>\n";
            		}	
            	} else {
            		echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . ">" . $texto . "</option>\n";
            	}
            	
                
                
                // if($ls_return=="") { $ls_return =$row[$IndexValue] ; }
            } // fin while
        } else {
        	
        	
            while ($row = mysqli_fetch_assoc($ConsultaID)) { // Inicio while
                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = 'selected';
                    $ls_return = $row[$IndexValue];
                } else {
                    $selected = '';
                }
                if ($selected != '') {
                    $ls_return = $row[$IndexValue];
                }
                
                $texto = $row[$IndexText];
                
                if (strstr($texto,'.-')) {
                	$arT = explode('.-',$texto);
                	$text = (isset($arT[1]) ? trim($arT[1]) : '');                	
                	if (!empty($text)) {
                		echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected ." title='" . $row[$IndexTitle] . "'>" . $row[$IndexText] . "</option>\n";
                	}	
                } else {
                	echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected ." title='" . $row[$IndexTitle] . "'>" . $row[$IndexText] . "</option>\n";
                }
            					
                
            } // fin while
        }

        // -------------------------------------------------->
        // DA 2.0 [24-10-2013 11:28]
        // No se recomienda la limpieza o liberacion de recursos u objetos
        // cuando no se tienen resultados almacenados y sobre todo cuando
        // cuando hacemos uso de Procedimientos Almacenados ya que interrumpe
        // las posteriores o anterios procesos ejecutados.
        // DA 2.0 [08-11-2013 20:20]
        // Eliminado del proceso de free porque libera datos aun usados
        /*
        if ($ConsultaID) {
            $ConsultaID->free();
        }*/
        // --------------------------------------------------<
        return $ls_return;
    } // Fin de Metodo llenarComboI

    /**
     * Genera salida HTML de grupos de option o optgroup con los resultados de
     * de una consulta a la Base de datos.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param resource $ConsultaID
     *            Resource devuelto de mysqli::query o mysqli_query (mixed)
     * @param string $IndexValue
     *            Es el valor del atributo value del option
     * @param string $IndexText
     *            Es el valor del texto del option
     * @param string $ValueDefault
     *            Es el valor por defecto que estará seleccionado
     * @param string $indexGroup
     *            Indica si se genera con la etiqueta optiongroup
     * @return boolean
     *
     */
    function llenarComboGroupI($ConsultaID, $IndexValue = 'id', $IndexText = 'Name', $ValueDefault = "", $indexGroup = '')
    {
        if ($ValueDefault != "") {
            $ls_return = $ValueDefault;
        } else {
            $ls_return = '';
        }

        if ($indexGroup == "") {
            return;
        }

        $Agrupar = '';
        $index = 0;

        while ($row = mysqli_fetch_assoc($ConsultaID)) { // Inicio while

            if ($index == 0) {
                $GroupName = $row[$indexGroup];
            }

            if ($row[$indexGroup] == $GroupName) {
                if ($Agrupar != '') {
                    echo ("</optgroup> \n");
                }
                echo "<optgroup label='" . $row[$IndexText] . "'>" . "\n";
                $Agrupar = $row[$IndexValue];
            } else {
                if ($row[$IndexValue] == $ValueDefault) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                    $ls_return = $row[$IndexValue];
                }
                echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . ">" . $row[$IndexText] . "</option>\n";
            }

            if ($row[$indexGroup] == $GroupName) {
                if ($Agrupar != $row[$IndexValue]) {
                    echo ("</optgroup> \n");
                }
            }

            $index ++;
        } // fin while

        echo ("</optgroup> \n");

        // -------------------------------------------------->
        // DA 2.0 [24-10-2013 11:28]
        // No se recomienda la limpieza o liberacion de recursos u objetos
        // cuando no se tienen resultados almacenados y sobre todo cuando
        // cuando hacemos uso de Procedimientos Almacenados ya que interrumpe
        // las posteriores o anterios procesos ejecutados.
        if ($ConsultaID) {
            $ConsultaID->free();
        }
        // --------------------------------------------------<

        return $ls_return;
    } // Fin Metodo llenarComboGroupI
    function llenarComboGroupII($ConsultaID, $IndexValue = 'id', $IndexText = 'Name', $ValueDefault = "", $indexGroup = '')
    {
        if ($ValueDefault != "") {
            $ls_return = $ValueDefault;
        } else {
            $ls_return = "";
        }
        if ($indexGroup == "") {
            return;
        }
        $Agrupar = '';
        $index = 0;
        $valueGroup = '';
        while ($row = mysqli_fetch_assoc($ConsultaID)) { // Inicio while

            if ($index == 0) {
                $GroupName = $row[$indexGroup];
            } else {
                $valueGroup = $row[$indexGroup];
            }

            if ($valueGroup != $GroupName) {
                if ($valueGroup != '') {
                    echo ("</optgroup> \n");
                }
                echo "<optgroup label='" . $row[$indexGroup] . "'>" . "\n";
                $GroupName = $row[$indexGroup];
            }

            if ($row[$IndexValue] == $ValueDefault) {
                $selected = "selected";
            } else {
                $selected = "";
                $ls_return = $row[$IndexValue];
            }
            echo "<OPTION value=" . chr(34) . $row[$IndexValue] . chr(34) . " " . $selected . ">" . $row[$IndexText] . "</option>\n";

            $index ++;
        } // fin while

        echo ("</optgroup> \n");
        $ConsultaID->free();
        return $ls_return;
    } // Fin Function llenaCombo
    function Redirect($url)
    {
        // header(sprintf("Location: %s", $url));
        echo ("<html xmlns=http://www.w3.org/1999/xhtml>");
        echo ("<head>");
        echo ("<title></title>");
        echo ("<meta http-equiv=Content-Type content=text/html; charset=UTF-8 />");
        echo ('<META http-equiv="refresh" content="0;URL=' . $url . '">');
        echo ("</head>");
        echo ("<body");
        echo ("</body>");
        echo ("</html>");
        exit(0);
    }

    function FechaLarga($Fecha)
    {
        $arrFecha = explode("/", $Fecha, 3);
        $ret = $arrFecha[0] . " de " . $this->MonthName(abs($arrFecha[1])) . " del " . $arrFecha[2];
        return $ret;
    }

    function AnioActual()
    {
        return date("Y");
    }

    function MesActual()
    {
        return number_format(date("m"), 0);
    }

    function DiaActual()
    {
        return number_format(date("d"), 0);
    }

    function TrimestreActual()
    {
        $mes = number_format(date("m"), 0);
        if ($mes > 1 && $mes < 4) {
            $trim = 1;
        }
        if ($mes > 3 && $mes < 7) {
            $trim = 2;
        }
        if ($mes > 6 && $mes < 10) {
            $trim = 3;
        }
        if ($mes >= 10) {
            $trim = 4;
        }

        return $trim;
    }

    function MonthName($n)
    {
        // $timestamp = mktime(0, 0, 0, $n, 1, 2005);
        $ret = "";
        switch ($n) {
            case 1:
                $ret = 'Enero';
                break;
            case 2:
                $ret = 'Febrero';
                break;
            case 3:
                $ret = 'Marzo';
                break;
            case 4:
                $ret = 'Abril';
                break;
            case 5:
                $ret = 'Mayo';
                break;
            case 6:
                $ret = 'Junio';
                break;
            case 7:
                $ret = 'Julio';
                break;
            case 8:
                $ret = 'Agosto';
                break;
            case 9:
                $ret = 'Setiembre';
                break;
            case 10:
                $ret = 'Octubre';
                break;
            case 11:
                $ret = 'Noviembre';
                break;
            case 12:
                $ret = 'Dciembre';
                break;
        }

        return $ret;
    }

    function AgregarFecha($fecha, $numero, $interval = "MONTH")
    {
        $SQL = "SELECT DATE_FORMAT(INTERVAL -1 DAY +( DATE_ADD('" . $this->convertDate($fecha) . "', INTERVAL " . $numero . " " . $interval . " )),'%d/%m/%y');";

        $fecha = $this->oSession->GetValue($SQL);
        return $fecha;
    }

    function FechaAmpliacion($fecha, $numero, $interval = "MONTH")
    {
        $SQL = "SELECT DATE_FORMAT( DATE_ADD('" . $this->convertDate($fecha) . "', INTERVAL " . $numero . " " . $interval . " ),'%d/%m/%y');";

        $fecha = $this->oSession->GetValue($SQL);
        return $fecha;
    }

    function DiferenciaFecha($fecha1, $fecha2, $interval = "MONTH")
    {
        $SQL = "SELECT DATEDIFF('" . $this->convertDate($fecha2) . "', '" . $this->convertDate($fecha1) . "');";
        $numero = $this->oSession->GetValue($SQL);
        if ($interval == 'MONTH') {
            if ($numero < 0) {
                $numero = '';
            } else {
                $numero = floor($numero / 30);
            }
        }

        if ($interval == 'YEAR') {
            $numero = floor(($numero / 365), 2);
        }

        return $numero;
    }

    function NumeroTrimRev($trim, $tipo = "1")
    {
        $a = 0;
        $t = 0;

        if ($trim <= 4) {
            $a = 1;
            $t = $trim;
        } else {
            $a = intval($trim / 4);
            $a = ($trim % 4 == 0 ? $a - 1 : $a);
            $t = ($trim - ($a * 4));
            $a ++;
        }

        if ($tipo == 1) {
            return $a;
        }
        if ($tipo == 2) {
            return $t;
        }
    }

    function NumeroMes($anio, $mes)
    {
        return (12 * ($anio - 1)) + $mes;
    }

    function NumeroMesRev($mes, $tipo = "1")
    {
        $a = 0;
        $t = 0;

        if ($mes <= 12) {
            $a = 1;
            $t = $mes;
        } else {
            $a = intval($mes / 12);
            $a = ($mes % 12 == 0 ? $a - 1 : $a);
            $t = ($mes - ($a * 12));
            $a ++;
        }

        if ($tipo == 1) {
            return $a;
        }
        if ($tipo == 2) {
            return $t;
        }
    }

    function verifyURL($url)
    {
        if ($url == '') {
            return '';
        }
        $arr = explode("://", $url);
        $protocol = strtolower($arr[0]);
        if ($protocol == 'http' || $protocol == 'https' || $protocol == 'ftp') {
            return $url;
        } else {
            return 'http://' . $url;
        }
    }

    function verifyAjax()
    {
        $reqAjax = $this->__GET('mode');
        if (md5('ajax') == $reqAjax || md5('ajax_edit') == $reqAjax || md5('ajax_save') == $reqAjax || md5('ajax_del') == $reqAjax) {
            $this->Ajax = true;
        } else {
            $this->Ajax = false;
        }

        return;
    }

    function TracePOST($value = false)
    {
        if (! $value) {
            foreach ($_POST as $key => $value) {
                echo ("$" . "_POST['" . $key . "'],");
                echo ("<br>");
            }
        } else {
            foreach ($_POST as $key => $value) {
                echo ("$" . "_POST['" . $key . "']='" . $value . "',");
                echo ("<br>");
            }
        }
    }

    function TraceGET($value = false)
    {
        if (! $value) {
            foreach ($_GET as $key => $value) {
                echo ("$" . "_GET['" . $key . "'],");
                echo ("<br>");
            }
        } else {
            foreach ($_GET as $key => $value) {
                echo ("$" . "_GET['" . $key . "']='" . $value . "',");
                echo ("<br>");
            }
        }
    }

    function Debug($values = false)
    {
        $this->TracePOST($values);
        echo ("<hr /> ");
        $this->TraceGET($values);
        exit();
    }

    function MensajeError($Msg)
    {
        echo ("<font style='color:red; font-weight:bold; font-size:11px;'>" . $Msg . "</font>");
    }

    function MsgBox($Msg)
    {
        echo ("<script>alert(\"" . $Msg . "\");</script>");
    }

    function Javascript($HardCode)
    {
        echo ("<script type=\"text/javascript\"> \n" . $HardCode . " \n</script>");
    }

    function GenerateXML($result, $container = "container", $arrayFields)
    { // Creamos el texto XML output para usralo con Spry.
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $xml .= "<" . $container . "> \n";

        $idItem = 0;
        $roowItem = "";

        // $row = mysql_fetch_assoc($result);
        // do {
        while ($row = mysql_fetch_assoc($result)) {
            $idItem ++;
            $roowItem .= "	<rowdata id=\"" . $idItem . "\"> \n";
            for ($x = 0; $x < count($arrayFields); $x ++) {
                $colName = $arrayFields[$x];
                $colValue= htmlspecialchars($row[$colName], ENT_QUOTES, "UTF-8");
                $roowItem .= "		<" . $colName . ">" . $colValue . "</" . $colName . "> \n";
            }
            $roowItem .= "	</rowdata> \n";
        } // while ($row = mysql_fetch_assoc($result));
        $xml .= $roowItem;
        $xml .= "</" . $container . ">";

        return $xml;
    }

    function iGenerateXML($result, $container = "container", $arrayFields)
    { // Creamos el texto XML output para usralo con Spry.
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
        $xml .= "<" . $container . "> \n";

        $idItem = 0;
        $roowItem = "";

        while ($row = mysqli_fetch_assoc($result)) {
            $idItem ++;
            $roowItem .= "	<rowdata id=\"" . $idItem . "\"> \n";
            for ($x = 0; $x < count($arrayFields); $x ++) {
                $colName = $arrayFields[$x];
                // $colValue= $this->CarcateresEspeciales($row[$colName]);

                // -------------------------------------------------->
                // DA 2.0 [22-10-2013 18:53]
                // eliminado del utf8_encode ya que la coneccion es UTF8 por defecto.
                $colValue = htmlspecialchars($row[$colName], ENT_QUOTES, "UTF-8");
                // --------------------------------------------------<
                $roowItem .= "		<" . $colName . ">" . $colValue . "</" . $colName . "> \n";
            }
            $roowItem .= "	</rowdata> \n";
        }
        $xml .= $roowItem;
        $xml .= "</" . $container . ">";
        if ($result) {
            $result->free();
        }
        return $xml;
    }

    function resultToString($rs, $fields)
    {
        $rReturn = "";
        while ($row = mysql_fetch_assoc($rs)) {
            for ($x = 0; $x < count($fields); $x ++) {
                $colName = $fields[$x];
                $colValue = htmlspecialchars($row[$colName], ENT_QUOTES);
                $rReturn .= $colValue . " ";
            }
            $rReturn .= "\n";
        } // while ($row = mysql_fetch_assoc($result));
        return $rReturn;
    }

    function get_dir_size($dir_name)
    {
        $dir_size = 0;
        if (is_dir($dir_name)) {
            if ($dh = opendir($dir_name)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        if (is_file($dir_name . "/" . $file)) {
                            $dir_size += filesize($dir_name . "/" . $file);
                        }
                        /* check for any new directory inside this directory */
                        if (is_dir($dir_name . "/" . $file)) {
                            $dir_size += $this->get_dir_size($dir_name . "/" . $file);
                        }
                    }
                }
            }
        }
        closedir($dh);
        return ($dir_size / 1024);
    }

    // -------------------------------------------------->    
    // DA 2.1 [24-04-2014 13:36]    
    // Terminos actualizados segun RF-006
    function calificacionInforme($pCalificacion, $pValOpts, &$pExtValue)
    {
        $aResult = "";

        if ($pCalificacion < 3) {
            if ($pValOpts && is_array($pValOpts) && count($pValOpts) > 0)
                $pExtValue = $pValOpts[0];
            $aResult = "Mala";
        } elseif ($pCalificacion >= 3 && $pCalificacion <= 5) {
            if ($pValOpts && is_array($pValOpts) && count($pValOpts) > 0)
                $pExtValue = $pValOpts[1];
            $aResult = "Regular";
        } elseif ($pCalificacion > 5) {
            if ($pValOpts && is_array($pValOpts) && count($pValOpts) > 0)
                $pExtValue = $pValOpts[2];
            $aResult = "Buena";
        } else {
            $aResult = "Calificacion Inválida";
        }

        
        return $aResult;
    }
    // --------------------------------------------------<    

    function enviar_mail($ret)
    {
        require_once ("../../../lib/pjmail/Email.class.php");
        $mail = new Email();
		$id = array();
        while ($row = mysqli_fetch_assoc($ret)) {

            $mail->Asunto($row["asu_men"]);
            $mail->De($row["ori_men"], "");
            $mail->Para($row["dest_men"], "");
            // $mail->Para("cristhian_80@hotmail.com","John Aima Ramos");
            $mail->Mensaje($row["tex_men"], true);
            $id[] = $row["id_men"];
            $mail->Enviar();
        }
        return $id;
    }

    /**
     * Encripta una cadena usando 256bit AES.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $text Cadena de origen.
     * @return string Cadena formateada con base64.
     *
     */
    public function encrypt($text)
    {
        $salt = $this->saltEncrypt;
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    /**
     * Desencripta una cadena usando 256bit AES.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $text Cadena encriptada.
     * @return string Cadena original desencriptada.
     *
     */
    public function decrypt($text)
    {
        $salt = $this->saltEncrypt;
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
    
    /**
     * Obtener contenido desde una URL.
     *
     * @author DA
     * @since Version 2.0
     * @access public
     * @param string $url Direccion URL.
     * @return string Cotenido resultado de la URL ingresada.
     *
     */
    public function getContentFromURL($url)
    {
    	$runfile = trim($url);
    	
    	$ch = curl_init();    	
    	curl_setopt($ch, CURLOPT_URL, $runfile);    	
    	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);    	
    	curl_setopt($ch, CURLOPT_URL,$runfile);    	
    	$content = curl_exec ($ch);    	
    	curl_close ($ch);    	
    	return $content;
    }
    
    /**
     * Obtener la fecha en letras.
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @param string $fecha 		Fecha en formato dd/mm/aaaa
     * @param string $tipoSalida	Tipo de Salida del Formato,	por defecto es 1
     * @return string				Si $tipoSalida es 1: 		
     * 									Fecha en letras en formato: dd de mm del aaaa
     * 								Si $tipoSalisa es 2: 
     * 									Fecha en letras en formato: mes dia / anio
     * 								Si $tipoSalisa es 3: 
     * 									Fecha en letras en formato: mes anio
     *
     */
    
    public function fechaEnLetras($fecha, $tipoSalida = 1)
    {
    	
    	$mes = array('Enero', 'Febrero', 'Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    	$arF = explode('/',$fecha);
    	$m = (int)$arF[1];
    	
    	switch ($tipoSalida) {
    		case 1 :	$fecha = $arF[0].' de '.$mes[$m - 1].' del '.$arF[2];
    					break;
    		case 2 :	$fecha = $mes[$m - 1].' '.$arF[0].' / '.$arF[2];
    					break;
    		case 3 :	$fecha = $mes[$m - 1].'  '.$arF[2];
    					break;
    		default:	$fecha = $mes[$m - 1].' '.$arF[0].' / '.$arF[2];
    					break;     	
    	}
    	    	    
    	return $fecha;
    	
    }
    
    
    /**
     * Convierte el feriodo en formato Mes/Anio
     *
     * @author DA
     * @since Version 2.1
     * @access public
     * @param string $periodo 		Fecha en formato NombreMes YYYY     
     * @return string				Formtato de salida MM/YYYY
     *
     */
    public function periodoFormat($periodo)
    {
    	$periodo = ucfirst($periodo);
    	$meses = array('Enero', 'Febrero', 'Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    	$arPeriodo = explode(' ',$periodo);
    	
    	$mes = array_search($arPeriodo[0], $meses) + 1;
    	$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
    	return ($mes . '/'.$arPeriodo[1]);
    	
    }


}
// fin de la Clse Funciones


