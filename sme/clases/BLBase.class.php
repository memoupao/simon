<?php
require_once ("BLBaseMySQL.class.php");
// / -------------------------------------------------------------------------
// / Programmer Name : Aima R. Christian Created Date : 2010-06-01
// / Comments : Abstract Superclass
// / Define los metodos y propiedades heredables para implemen-
// / tar las clases concretas de logica del negocio.
// / -------------------------------------------------------------------------
class BLBase
{

    var $DBOBaseMySQL;

    var $Error;

    function __construct()
    {
        /* Especificaiones para LOAD */
    }
    
    // / <summary>
    // / Estable la Conexion abierta para consumirla
    // / </summary>
    // / <param name="$con">Objeto de Conexion MySQL </param>
    function SetConection($con)
    {
        $this->DBOBaseMySQL = new BLBASE_mysql($con);
    }
    // / <summary>
    // / Retorna la instancia de conexion del objeto DBOBaseMySQL
    // / </summary>
    function GetConection()
    {
        return $this->DBOBaseMySQL;
    }
    
    // / <summary>
    // / Ejecuta una sentencia SQL en la base de datos
    // / </summary>
    // / <param name="$sql">Sentencia SQL </param>
    // / <returns>Conjunto de Registros</returns>
    function Execute($sql)
    {
        $ret = $this->DBOBaseMySQL->execute($sql);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }
    // / <summary>
    // / Ejecuta una sentencia SQL en la base de datos, analiza fechas y numeros
    // / </summary>
    // / <param name="$sql">Sentencia SQL </param>
    // / <returns>Conjunto de Registros</returns>
    function ExecuteQuery($sql)
    {
        $ret = $this->DBOBaseMySQL->consulta($sql);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }
    // / <summary>
    // / Ejecuta una sentencia SQL en la base de datos, retorna el valor del
    // / primer registro y del primer campo.
    // / </summary>
    // / <param name="$sql">Sentencia SQL </param>
    // / <returns>Valor de tipo String</returns>
    function GetValue($sql)
    {
        $ret = $this->DBOBaseMySQL->getstring($sql);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }
    // / <summary>
    // / Retorna la fecha y hora de la Base de Datos
    // / </summary>
    // / <returns>Fecha tipo DATETIME </returns>
    function GetDateTime()
    {
        return $DBOBaseMySQL->getdatetime();
    }
    // / <summary>
    // / Genera Codigo correlativo para una Tabla
    // / </summary>
    // / <param name="$table">Tabla</param>
    // / <param name="$field">Campo de la tabla </param>
    // / <param name="$where">Condicion SQL</param>
    // / <returns>Numero correlativo</returns>
    function Autogenerate($table, $field, $where)
    {
        return $this->DBOBaseMySQL->autogenerar($table, $field, $where);
    }

    function ExecuteCreate($sql)
    {
        $ret = $this->DBOBaseMySQL->execute($sql);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }

    function ExecuteUpdate($sql)
    {
        $ret = $this->DBOBaseMySQL->execute($sql);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }

    function ExecuteDelete($sql)
    {
        $ret = $this->DBOBaseMySQL->execute($sql);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }

    function ExecuteProcedureEscalar($SPName, $arrayParams)
    {
        $ret = $this->DBOBaseMySQL->ExecuteNonQuerySP($SPName, $arrayParams);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }

    function ExecuteProcedureReader($SPName, $arrayParams)
    {
        $ret = $this->DBOBaseMySQL->ExecuteReaderSP($SPName, $arrayParams);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }

    function ExecuteFunction($FunctionName, $arrayParams)
    {
        $ret = $this->DBOBaseMySQL->ExecuteFunction($FunctionName, $arrayParams);
        $this->Error = $this->DBOBaseMySQL->Error;
        return $ret;
    }

    function ConvertDate($fecha)
    {
        return $this->DBOBaseMySQL->convertDate($fecha);
    }

    function ConvertNumber($numero)
    {
        $ret = str_replace(',', '', $numero);
        // $ret = floatval($ret);
        return $ret;
    }

    function GetError()
    {
        return $this->Error;
    }

    function GetArrayFields($rs)
    {
        $this->DBOBaseMySQL->Consulta_ID = $rs;
        
        $arrayFields[0] = "";
        for ($i = 0; $i < $this->DBOBaseMySQL->numcampos(); $i ++) {
            $arrayFields[$i] = $this->DBOBaseMySQL->nombrecampo($i);
        }
        
        return $arrayFields;
    }

    function iGetArrayFields($rs)
    {
        $arrayFields[0] = "";
        if ($rs) {
            $fields = mysqli_fetch_fields($rs);
            $x = 0;
            foreach ($fields as $fi => $f) {
                $arrayFields[$x] = $f->name;
                $x ++;
            }
        }
        return $arrayFields;
    }

    function ResultToArray($iresult)
    {
        return $this->DBOBaseMySQL->iQueryToArray($iresult);
    }

    function ResultToTranspose($iresult)
    {
        $ret = array();
        $fields = $this->iGetArrayFields($iresult);
        $fila = 0;
        while ($r = mysqli_fetch_assoc($iresult)) {
            foreach ($fields as $field) {
                $ret[$field][$fila] = $r[$field];
            }
            $fila ++;
        }
        return $ret;
    }

    function Destroy()
    {
        $this->DBOBaseMySQL = 0;
    }

    function Error()
    {
        return $this->DBOBaseMySQL->Error;
    }
}

?>
