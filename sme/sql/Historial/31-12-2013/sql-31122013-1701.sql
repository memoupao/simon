/*
// -------------------------------------------------->
// AQ 2.0 [31-12-2013 17:01]
// Fixed: Informe Técnico
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_ins_inf_mes_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_ins_inf_mes_cab`(IN _proy varchar(10), 
            IN _anio INT, 
            IN _mes INT, 
            IN _periodo varchar(50), 
            IN _fchpres datetime , 
            IN _estado INT,
            IN _usr varchar(20))
BEGIN
  DECLARE _verInf   INT;
    DECLARE _msg   VARCHAR(100);
    DECLARE _mesactual INT;
    DECLARE _mesanterior INT;
    DECLARE _anio_ant INT;
    DECLARE _mes_ant INT;
    DECLARE _aprobado INT DEFAULT -1;
    declare suspYr INT;
    declare suspMt INT;
 
    SELECT fn_numero_mes(_anio, _mes) INTO _mesactual;
    SELECT fn_numero_mes(_anio, _mes)-1 INTO _mesanterior;
    SELECT fn_numero_mes_rev(_mesanterior, 1) INTO _anio_ant;
    SELECT fn_numero_mes_rev(_mesanterior, 2) INTO _mes_ant;
    IF _mesactual !=1 THEN
        SELECT t20_estado INTO _aprobado
        FROM t20_inf_mes
        WHERE t02_cod_proy = _proy
            AND t20_anio     = _anio_ant
            AND t20_mes      = _mes_ant ;
    END IF;
  
    if (_mesactual > 1) then
        SELECT t.t02_anio, IFNULL(t.diff, 12) AS duraction
        INTO suspYr, suspMt
        FROM (
            SELECT t2.t02_anio, t3.t02_fch_susp, t3.t02_fch_reinic,
                TIMESTAMPDIFF(MONTH, t3.t02_fch_susp, t3.t02_fch_reinic) + 1 AS diff
            FROM t02_dg_proy t1
            JOIN t02_poa t2 ON (t2.t02_cod_proy = t1.t02_cod_proy)
            LEFT JOIN t02_suspenciones t3 ON (t3.t02_cod_proy = t1.t02_cod_proy AND t3.t02_version = t2.t02_anio + 1)
            WHERE t1.t02_cod_proy = _proy AND t1.t02_version = 1) AS t
        WHERE t.t02_anio = _anio_ant
        ORDER BY t.t02_anio DESC
        LIMIT 1;
            
        IF (suspYr IS NOT NULL AND _mes_ant > suspMt) THEN
            SELECT 1 INTO _mesactual;
        END IF;
    end if;
    
    IF _mesactual != 1 and _aprobado != 135 THEN        
        SELECT CONCAT('El informe técnico para el periodo Año ', _anio_ant, ', Mes ', _mes_ant, ' todavía no tiene el visto bueno del monitor.')
        INTO _msg ;
        SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
    ELSE   
        SELECT  IFNULL(MAX(t20_ver_inf),0)+1 INTO _verInf 
        FROM    t20_inf_mes 
        WHERE   t02_cod_proy    = _proy  
            and t20_anio = _anio
            and t20_mes  = _mes;
 
        INSERT INTO t20_inf_mes 
            (t02_cod_proy, 
            t20_anio, 
            t20_mes, 
            t20_ver_inf, 
            t20_fch_pre, 
            t20_periodo, 
            t20_obs, 
            t20_estado, 
            t02_version, 
            usr_crea, 
            fch_crea, 
            est_audi
            )
        VALUES
            (_proy, 
            _anio, 
            _mes, 
            _verInf,  
            _fchpres, 
            _periodo, 
            '', 
            _estado, 
            fn_ult_version_proy(_proy), 
            _usr, 
            NOW(), 
            '1'
            );
        select ROW_COUNT() as numrows, _verInf as codigo, '' AS msg; /*Retornar el numero de registros afectados */         
    END IF;
    
END $$

DELIMITER ;