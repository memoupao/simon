<?php

class DB_mysql
{
    /* variables de conexión */
    var $BaseDatos;

    var $Servidor;

    var $Usuario;

    var $Clave;
    
    /* identificador de conexión y consulta */
    var $Conexion_ID = 0;

    var $Consulta_ID = 0;
    
    /* número de error y texto error */
    var $Errno = 0;

    var $Error = "";
    
    /*
     * Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función
     */
    function DB_mysql($bd = "", $host = "", $user = "", $pass = "")
    {
        $this->BaseDatos = $bd;
        $this->Servidor = $host;
        $this->Usuario = $user;
        $this->Clave = $pass;
    }
    
    /* Conexión a la base de datos */
    function conectar($bd, $host, $user, $pass)
    {
        if ($bd != "")
            $this->BaseDatos = $bd;
        if ($host != "")
            $this->Servidor = $host;
        if ($user != "")
            $this->Usuario = $user;
        if ($pass != "")
            $this->Clave = $pass;
            
            // Conectamos al servidor
        $this->Conexion_ID = mysql_connect($this->Servidor, $this->Usuario, $this->Clave);
        
        // -------------------------------------------------->
        // AQ 2.0 [22-10-2013 15:10]
        // Uso de charset UTF-8
        mysql_set_charset('utf8', $this->Conexion_ID);
        // --------------------------------------------------<
        
        if (! $this->Conexion_ID) {
            $this->Error = "Ha fallado la conexión.";
            return 0;
        }
        
        // seleccionamos la base de datos
        if (! @mysql_select_db($this->BaseDatos, $this->Conexion_ID)) {
            $this->Error = "Imposible abrir " . $this->BaseDatos;
            return 0;
        }
        
        /*
         * Si hemos tenido éxito conectando devuelve el identificador de la conexión, sino devuelve 0
         */
        
        return $this->Conexion_ID;
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
            
            $this->Consulta_ID = @mysql_query($ls_sql_dep, $this->Conexion_ID);
        } else {
            $this->Consulta_ID = @mysql_query($sql, $this->Conexion_ID);
        }
        
        if (! $this->Consulta_ID) {
            $this->Errno = mysql_errno();
            $this->EvaluaError();
        }
        /*
         * Si hemos tenido éxito en la consulta devuelve el identificador de la conexión, sino devuelve 0
         */
        return $this->Consulta_ID;
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
                    $q_value = "'" . $ArrayDatos[$ax] . "'";
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
                    $q_value = "'" . $ArrayDatos[$ax] . "'";
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
        while ($row = mysqli_fetch_row($iRS)) {
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
    function EvaluaError()
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
            // case num : //Clave duplicada
            // $mensaje = substr($lcampo,1) ; $q_value = $ArrayDatos[$ax] ;
            // break ;
            default: // Otro error no esperado
                $mensaje = mysql_error();
                break;
        }
        
        $this->Error = $mensaje;
        
        return $mensaje;
    }
    
    // libera la conexion actual
    function Destroy()
    { // mysql_free_result($this);
        mysql_close($this->Conexion_ID);
    }
    
    // egion Stored Procedures
    // ExecuteProcedure: Ejecuta el SP, y retorna la primera fila de los resultados
    function ExecuteNonQuerySP($ProcedureName, $arrayParams = NULL)
    {
        $params = $this->PrepareParams($arrayParams);
        $sqlPrepare = " call " . $ProcedureName . "(" . $params . "); ";
        $ret = $this->ExecuteSP_FN($sqlPrepare);
        $row = mysqli_fetch_assoc($ret);
        $ret->free();
        return $row;
    }
    // ExecuteProcedure: Ejecuta el SP, y retorna el conjunto de Resultados
    function ExecuteReaderSP($ProcedureName, $arrayParams = NULL)
    {
        $params = $this->PrepareParams($arrayParams);
        $sqlPrepare = " call " . $ProcedureName . "(" . $params . "); ";
        $ret = $this->ExecuteSP_FN($sqlPrepare);
        return $ret;
        // $ret->free();
    }
    
    // Ejecuta una Funcion MySQL y retorna un valor No deterministico
    function ExecuteFunction($FuncytionName, $arrayParams = NULL)
    {
        $params = $this->PrepareParams($arrayParams);
        $sqlPrepare = " select " . $FuncytionName . "(" . $params . "); ";
        $ret = $this->ExecuteSP_FN($sqlPrepare);
        $row = mysqli_fetch_array($ret);
        $ret->free();
        return $row[0];
    }

    private function PrepareParams($arrayParams)
    {
        $ParmPrepare = "";
        if (is_array($arrayParams)) {
            for ($ax = 0; $ax < count($arrayParams); $ax ++) {
                $ParmPrepare .= "'" . $arrayParams[$ax] . "' , ";
            }
            
            $ParmPrepare = substr($ParmPrepare, 0, strlen($ParmPrepare) - 3);
        }
        return $ParmPrepare;
    }

    private function ExecuteSP_FN($strSQL)
    {
        // MYSQLI
        $mysqli = new mysqli($this->Servidor, $this->Usuario, $this->Clave, $this->BaseDatos);
        
        // -------------------------------------------------->
        // AQ 2.0 [22-10-2013 15:10]
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
                /* store first result set */
                $retStore = $mysqli->store_result();
                if ($retStore) {
                    $result[$indexResult] = $retStore;
                    $bMultiReturn = true;
                }
                /* print divider */
                if ($mysqli->more_results()) {
                    $indexResult ++;
                }
            } while ($mysqli->next_result());
        }
        /* close connection */
        $mysqli->close();
        $retStore = $result[count($result) - 1];
        $result = NULL;
        // $result->free(); //Liberar el Recurso luego de emplearlo
        return $retStore; // $result[count($result)-1] ;
    }
    
    // ndRegion
} // fin de la Clse DB_mysql

?>
