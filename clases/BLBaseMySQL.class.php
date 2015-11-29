<?php

class BLBASE_mysql
{
    /* identificador de conexión y consulta */
    var $Conexion_ID = 0;

    var $Consulta_ID = 0;

    var $lastquery = "";
    /* número de error y texto error */
    var $Errno = 0;

    var $Error = "";

    /*
     * Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función
     */
    function BLBASE_mysql($ConexionID)
    {
        $this->Conexion_ID = $ConexionID;
    }

    /* Ejecuta un consulta */
    function consulta($sql = "")
    {
        if ($sql == "") {
            $this->Error = "No ha especificado una consulta SQL";
            return 0;
        }
        // ejecutamos la consulta
        if (strtolower(substr($sql, 0, 6)) == 'select') {
            $ls_sql_dep = strtolower($sql);
            $ls_sql_dep = str_replace("%d/%m/%y", "%d/%m/%Y", $ls_sql_dep);
            $ls_sql_dep = str_replace("%d-%m-%y", "%d-%m-%Y", $ls_sql_dep);

            $this->Consulta_ID = mysql_query($ls_sql_dep, $this->Conexion_ID);
        } else {
            $this->Consulta_ID = mysql_query($sql, $this->Conexion_ID);
        }

        if (! $this->Consulta_ID) {
            $this->Errno = mysql_errno();
            $this->EvaluaError($sql);
        }
        /*
         * Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0
         */
        return $this->Consulta_ID;
    }

    function execute($sql = "")
    {
        if ($sql == "") {
            $this->Error = "No ha especificado una consulta SQL";
            return 0;
        }
        // ejecutamos la consulta
        $QueryRet = mysql_query($sql, $this->Conexion_ID);

        if (! $QueryRet) {
            $this->Errno = mysql_errno();
            $this->EvaluaError($sql);
        }
        /*
         * Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0
         */
        return $QueryRet;
    }

    // Obtiene Fecha y Hora del Servidor MYSQL
    function getdatetime()
    {
        $this->consulta("SELECT CONCAT(CURDATE(),' ',CURTIME())");
        $ls_fecha = $this->getrow();
        $ls_fecha = $ls_fecha[0];
        return $ls_fecha;
    }

    // Obtiene la Fila de una consulta
    function getrow()
    {
        if ($this->Consulta_ID != 0) {
            $rowReturn = mysql_fetch_row($this->Consulta_ID);
        }
        return $rowReturn;
    }

    // Obtiene texto de uan consulta sql de un solop campo (por ejemplo SELECT COUNT(*) FROM TABLA)
    function getstring($ls_sql)
    {
        $this->consulta($ls_sql);
        $ls_row = $this->getrow();
        $ls_return = $ls_row[0];
        return $ls_return;
    }

    // Autogenera un codigo Numerico de un campo de una tabla /opcional condicion Where
    function autogenerar($ls_tabla, $ls_campo, $ls_where)
    {
        $ls_sql = "SELECT (IFNULL(MAX(" . $ls_campo . "),0)+1) FROM " . $ls_tabla;

        if ($ls_where != "") {
            $ls_sql .= " WHERE " . $ls_where;
        }
        $ls_return = $this->getstring($ls_sql);
        return $ls_return;
    }

    /* Devuelve el número de campos de una consulta */
    function numcampos()
    {
        return mysql_num_fields($this->Consulta_ID);
    }

    /* Devuelve el nombre de un campo de una consulta a partir del index del campo */
    function nombrecampo($numcampo)
    {
        if ($this->Consulta_ID == 0) {
            return "";
        } else {
            return mysql_field_name($this->Consulta_ID, $numcampo);
        }
    }

    /* Devuelve el Tipo de dato de un campo */
    function tipodatocampo($numcampo)
    {
        return mysql_field_type($this->Consulta_ID, $numcampo);
    }
    /* Devuelve el tamaño de un campo */
    function tamanocampo($numcampo)
    {
        return mysql_field_len($this->Consulta_ID, $numcampo);
    }
    /* Devuelve flags de un campo */
    function flagscampo($numcampo)
    {
        return mysql_field_flags($this->Consulta_ID, $numcampo);
    }

    /* Devuelve el número de registros de una consulta */
    function numregistros()
    {
        if ($this->Consulta_ID == 0) {
            return 0;
        }
        return mysql_num_rows($this->Consulta_ID);
    }

    /* Devuelve el número de registros afectados en query (INSERT/UPFDATE/DELETE) */
    function numregistros_afectados()
    {
        if ($this->Consulta_ID == 0) {
            return 0;
        }
        return mysql_affected_rows($this->Consulta_ID);
    }

    // Inicializa una Transaccion
    function begin()
    {
        $null = mysql_query("START TRANSACTION", $this->Conexion_ID);
        return mysql_query("BEGIN", $this->Conexion_ID);
    }

    // Completa una Transaccion
    function commit()
    {
        return mysql_query("COMMIT", $this->Conexion_ID);
    }

    // Cancela una Transaccion
    function rollback()
    {
        return mysql_query("ROLLBACK", $this->Conexion_ID);
    }

    // Ejecuta Transaccion
    function transaction($query)
    {
        $retval = 1;

        $this->begin();

        $this->consulta($query);
        // if ($this->Errno==0){ $retval = 0; }
        if (mysql_affected_rows() == 0) {
            $retval = 0;
        }

        if ($this->Errno == 0) {
            if ($retval >= 0) {
                $this->commit();
                return true;
            } else {
                $this->rollback();
                return false;
            }
        } else {
            return false;
        }
    }

    // Genera where de Query "id='id' AND num=1 "
    function createWhere($ArrayCampos, $ArrayDatos)
    {
        $ls_return_where = "";

        for ($ax = 0; $ax < count($ArrayCampos); $ax ++) {
            $q_campo = "";
            $q_value = "";

            switch (substr($ArrayCampos[$ax], 0, 1)) {
                case "#": // Tipo Dato Numerico
                    $q_campo = substr($ArrayCampos[$ax], 1);
                    $q_value = $ArrayDatos[$ax];
                    break;
                case "@": // Tipo Dato Fecha
                    $q_campo = substr($ArrayCampos[$ax], 1);
                    $q_value = "'" . $this->convertDate($ArrayDatos[$ax]) . "'";
                    break;
                default: // Tipo ninguno (string)
                    $q_campo = $ArrayCampos[$ax];
                    $q_value = "'" . $ArrayDatos[$ax] . "'";
                    break;
            }

            $ls_query_where .= $q_campo . "=" . $q_value . " AND ";
        }

        $ls_return_where = " (" . substr($ls_query_where, 0, strlen($ls_query_where) - 4) . ") ";
        return $ls_return_where;
    }

    // Genera query INSERT INTO
    function createqueryInsert($tabla, $ArrayCampos, $ArrayDatos)
    {
        $ls_return_query = "";

        for ($ax = 0; $ax < count($ArrayCampos); $ax ++) {
            $lcampo = $ArrayCampos[$ax];
            // echo( $ArrayCampos[$ax] . " = " . $ArrayDatos[$ax] . "<br>") ;
            $q_campo = "";
            $q_value = "";

            switch (substr($lcampo, 0, 1)) {
                case "#": // Tipo Dato Numerico
                    $q_value = $ArrayDatos[$ax];
                    if (is_numeric($q_value) == false) {
                        $q_value = 0;
                    }
                    $q_campo = substr($lcampo, 1);
                    break;
                case "@": // Tipo Dato Fecha
                    $q_campo = substr($lcampo, 1);
                    $q_value = "'" . $this->convertDate($ArrayDatos[$ax]) . "'";
                    break;
                default: // Tipo ninguno (string)
                    $q_campo = $lcampo;
                    $q_value = "'" . mysql_real_escape_string($ArrayDatos[$ax]) . "'";
                    break;
            }

            $ls_query_insert .= $q_campo . ",";
            $ls_query_values .= $q_value . ",";
        }

        $ls_return_query = "INSERT INTO " . strtolower($tabla) . "(" . substr($ls_query_insert, 0, strlen($ls_query_insert) - 1) . ") ";
        $ls_return_query .= " VALUES(" . substr($ls_query_values, 0, strlen($ls_query_values) - 1) . ") ";

        return $ls_return_query;
    }

    // Genera query UPDATE
    function createqueryUpdate($tabla, $ArrayCampos, $ArrayDatos, $strWehere)
    {
        $ls_return_query = "";
        $ls_query_update = "";
        for ($ax = 0; $ax < count($ArrayCampos); $ax ++) {
            $lcampo = $ArrayCampos[$ax];
            $q_campo = "";
            $q_value = "";

            switch (substr($lcampo, 0, 1)) {
                case "#": // Tipo Dato Numerico
                    $q_value = $ArrayDatos[$ax];
                    if (is_numeric($q_value) == false) {
                        $q_value = 0;
                    }
                    $q_campo = substr($lcampo, 1);
                    break;
                case "@": // Tipo Dato Fecha
                    $q_campo = substr($lcampo, 1);
                    $q_value = "'" . $this->convertDate($ArrayDatos[$ax]) . "'";
                    break;
                default: // Tipo ninguno (string)
                    $q_campo = $lcampo;
                    $q_value = "'" . mysql_real_escape_string($ArrayDatos[$ax]) . "'";
                    break;
            }

            $ls_query_update .= $q_campo . "=" . $q_value . ", ";
        }

        $ls_return_query = "UPDATE " . strtolower($tabla) . " SET ";
        $ls_return_query .= substr($ls_query_update, 0, strlen($ls_query_update) - 2) . " WHERE " . $strWehere;

        return $ls_return_query;
    }

    // Genera query DELETE
    function createqueryDelete($tabla, $ArrayCamposWhere, $ArrayDatosWhere, $lb_elimina = true)
    {
        $ls_return_query = "";
        $ls_where = $this->createWhere($ArrayCamposWhere, $ArrayDatosWhere);

        if ($lb_elimina == true) {
            $ls_return_query = "DELETE FROM " . strtolower($tabla) . " WHERE " . $ls_where;
        } else {
            $ls_return_query = "UPDATE " . strtolower($tabla) . " SET est_audi='E', fch_actu='" . date("Y-m-d H:i:s", time()) . "', usr_actu='" . $_SESSION["cod_usuario"] . "'  WHERE " . $ls_where;
        }

        return $ls_return_query;
    }

    // Convierte en formato de Fecha aceptable por MySql
    function convertDate($fecha)
    {
        error_reporting(0);
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $lafecha = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $lafecha;
    }

    function iQueryToArray($iRS)
    {
        if ($iRS == 0) {
            return 0;
        }
        $llrow = 0;
        while ($row = mysqli_fetch_assoc($iRS)) {
            $lReturn[$llrow] = $row;
            $llrow ++;
        }
        return $lReturn;
    }

    function querytoArray()
    {
        if ($this->Consulta_ID == 0) {
            return 0;
        }
        $llrow = 0;
        while ($row = mysql_fetch_row($this->Consulta_ID)) {
            $lReturn[$llrow] = $row;
            $llrow ++;
        }
        return $lReturn;
    }

    // Evalua el numero de error para mostrar un mensaje
    function EvaluaError($sql = "")
    {
        switch ($this->Errno) {
            case 1062: // Clave duplicada
                $entrys = array(
                    "Duplicate entry",
                    "for key 1"
                );
                $str = str_replace($entrys, "", mysql_error());
                $mensaje = "<font color=blue><b>Llave primaria duplicada </b><br>" . $str . "</font>";
                break;
            default: // Otro error no esperado
                $mensaje = mysql_error();
                break;
        }

        $this->Error = "Error en Query:<br />" . $sql . "<br />" . $mensaje;

        return $mensaje;
    }

    // egion Stored Procedures
    // ExecuteNonQuerySP: Ejecuta el SP, y retorna la primera fila de los resultados
    function ExecuteNonQuerySP($ProcedureName, $arrayParams = NULL)
    {
        $params = $this->PrepareParams($arrayParams);
        $sqlPrepare = " call " . $ProcedureName . "(" . $params . "); ";
        // echo($sqlPrepare);
        // exit();
        $ret = $this->ExecuteSP_FN($sqlPrepare);
        if ($ret) {
            $row = mysqli_fetch_assoc($ret);
            $ret->free();
        } else {
            $row = NULL;
        }
        return $row;
    }
    // ExecuteReaderSP: Ejecuta el SP, y retorna el conjunto de Resultados
    function ExecuteReaderSP($ProcedureName, $arrayParams = NULL)
    {
        $params = $this->PrepareParams($arrayParams);
        $sqlPrepare = " call " . $ProcedureName . "(" . $params . "); ";
        $ret = $this->ExecuteSP_FN($sqlPrepare);
        return $ret;
        // $ret->free();
    }

    // ExecuteFunction: Ejecuta una Funcion MySQL y retorna un valor No deterministico
    function ExecuteFunction($FuncytionName, $arrayParams = NULL)
    {
        $params = $this->PrepareParams($arrayParams);
        $sqlPrepare = " select " . $FuncytionName . "(" . $params . "); ";
        $ret = $this->ExecuteSP_FN($sqlPrepare);
        $row = mysqli_fetch_array($ret);
        $ret->free();
        return $row[0];
    }

    // Funciones que preparan y ejecutan el SP and Function
    private function PrepareParams($arrayParams)
    {
        $ParmPrepare = "";
        if (is_array($arrayParams)) {
            /*
             * CTIC - 20-10-2013 11:07 Corrección: mysql_real_escape_string convierte los valores boleanos en 1 o 0 en PHP 5.4
             */
            for ($ax = 0; $ax < count($arrayParams); $ax ++) {
                if (is_bool($arrayParams[$ax])) {
                    $bool = ($arrayParams[$ax]) ? 'true' : 'false';
                    $ParmPrepare .= '' . $bool . ' , ';
                } else {
                    $ParmPrepare .= "'" . mysql_real_escape_string($arrayParams[$ax]) . "' , ";
                }
            }
            $ParmPrepare = substr($ParmPrepare, 0, strlen($ParmPrepare) - 3);
        }

        return $ParmPrepare;
    }

    private function ExecuteSP_FN($strSQL)
    {
        // MYSQLI
        $this->lastquery = $strSQL;
        $mysqli = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PWD'), constant('DB_NAME'));

        // -------------------------------------------------->
        // AQ 2.0 [22-10-2013 15:15]
        // Uso de charset UTF-8
        mysqli_set_charset($mysqli, "utf8");
        // --------------------------------------------------<

        /* check connection */
        if (mysqli_connect_errno()) {
            $this->Error = mysqli_connect_error();
            return 0;
        }
        /* execute multi query */
        $indexResult = 0;
        $bMultiReturn = false;

        if ($mysqli->multi_query($strSQL)) {
            do {
                $retStore = $mysqli->store_result();
                if ($retStore) {
                    $result[$indexResult] = $retStore;
                    $bMultiReturn = true;
                }
                if ($mysqli->more_results()) {
                    $indexResult ++;
                }
            } while ($mysqli->next_result());

            $retStore = $result[count($result) - 1];
            $result = NULL;
        } else {
            $retStore = NULL;
            $this->Error = 'Error Numero: ' . $mysqli->errno . '   -   ' . $mysqli->error;
        }
        /* close connection */
        $mysqli->close();

        // $result->free(); //Liberar el Recurso luego de emplearlo
        return $retStore; // $result[count($result)-1] ;
    }

    // ndRegion
} // fin de la Clse BLBASE_mysql

?>
