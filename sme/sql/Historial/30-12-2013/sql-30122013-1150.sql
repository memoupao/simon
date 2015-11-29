/*
// -------------------------------------------------->
// AQ 2.0 [30-12-2013 11:50]
// Registro de Indicadores de Propósito del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_ind_prop`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_ind_prop`(
                    IN _proy VARCHAR(10),
                    IN _ver INT,
                    IN _propind INT,  
                    IN _anio INT, 
                    IN _entregable INT, 
                    IN _avance DOUBLE, 
                    IN _descrip TEXT,
                    IN _logros  TEXT,
                    IN _dificul TEXT,
                    IN _observaciones TEXT,
                    IN _usr VARCHAR(20)
                   )
BEGIN
 DECLARE _existe INT;
 
 SELECT  IFNULL(COUNT(t07_cod_prop_ind),0)
   INTO  _existe
   FROM  t07_prop_ind_inf
  WHERE  t02_cod_proy    = _proy 
    AND  t07_cod_prop_ind= _propind 
    AND  t07_ind_anio    = _anio
    AND  t07_ind_entregable    = _entregable;
  
  IF _existe <= 0 THEN 
    BEGIN
        INSERT INTO t07_prop_ind_inf 
            (
            t02_cod_proy, 
            t02_version, 
            t07_cod_prop_ind, 
            t07_ind_anio, 
            t07_ind_entregable, 
            t07_ind_avanc, 
            t07_descrip, 
            t07_logros, 
            t07_dificul, 
            t07_observaciones,
            usr_crea, 
            fch_crea, 
            est_audi
            )
            VALUES
            (
            _proy,   
            _ver,
            _propind,   
            _anio, 
            _entregable, 
            _avance, 
            _descrip,
            _logros,
            _dificul,
            _observaciones,
            _usr,
            NOW(),   
            '1'
            );
        
    END;
  ELSE
    BEGIN
        UPDATE t07_prop_ind_inf 
           SET  t07_ind_avanc=_avance,
            t07_descrip=_descrip, 
            t07_logros=_logros, 
            t07_dificul=_dificul,
            t07_observaciones = _observaciones,
            usr_actu = _usr,
            fch_actu = NOW()
          WHERE t02_cod_proy = _proy 
            AND t07_cod_prop_ind = _propind 
            AND t07_ind_anio = _anio
            AND t07_ind_entregable = _entregable 
            AND t02_version = _ver;
      END;
  END IF; 
 
 SELECT ROW_COUNT() INTO _existe;
 
 DELETE FROM t07_prop_ind_inf 
  WHERE t02_cod_proy   = _proy 
    AND t07_ind_anio   = _anio
    AND t07_ind_entregable    = _entregable 
    AND (t07_ind_avanc  <=0 AND t07_descrip='' AND t07_logros='' AND t07_dificul='')
    AND t02_version = _ver;
 
    SELECT _existe AS numrows, _propind AS codigo;
END $$

DELIMITER ;

/*
--fn_numero_entregable(inf.t25_anio, inf.t25_entregable) as nrotrim,
--fn_porc_cump_inf_entregable(inf.t02_cod_proy, inf.t25_anio, inf.t25_entregable ) AS cump_entregable,
--fn_porc_cump_inf_mes_acum(inf.t02_cod_proy, inf.t25_anio, (inf.t25_entregable * 3) ) AS cump_acum_entregable
*/
DROP FUNCTION IF EXISTS `fn_numero_entregable`;

DELIMITER $$

CREATE FUNCTION `fn_numero_entregable`(_proy VARCHAR(10), 
    _ver INT, _entregable INT) RETURNS INT(11)
    DETERMINISTIC
BEGIN
	DECLARE _num INT DEFAULT 0;
	
    SELECT sb.num
    INTO _num
    FROM (SELECT t02_mes AS mes, @curRow:=@curRow+1 AS num, t02_anio AS anio
            FROM t02_entregable 
            JOIN (SELECT @curRow := 0) r 
            WHERE t02_cod_proy = _proy 
            AND t02_version  = _ver) AS sb, t02_entregable e
    WHERE e.t02_cod_proy = _proy 
    AND e.t02_version = _ver
    AND sb.anio = _anio
    AND sb.t02_mes = _entregable;
    
    
    RETURN _num;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable_ind_prop`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable_ind_prop`(IN _proy VARCHAR(10), 
                    IN _ver INT, 
                    IN _anio INT, 
                    IN _entregable INT)
	BEGIN

	/* Seleccionamos Los Indicadores de Proposito, y aquellos indicadores que ya fueron informados*/
	SELECT  ind.t07_cod_prop_ind,
	    ind.t07_ind AS indicador,
	    ind.t07_um,
	    ind.t07_mta AS plan_mtaTotal,
	    SUM(0) AS plan_mtaAcum,
	    SUM(0) AS plan_mtaEntregable,
	    SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t07_ind_entregable) < fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _entregable) THEN inf.t07_ind_avanc ELSE 0 END) AS ejec_mtaAcum,
	    SUM(CASE WHEN inf.t07_ind_anio =_anio AND inf.t07_ind_entregable = _entregable THEN inf.t07_ind_avanc ELSE 0 END) AS ejec_mtaEntregable,
	    SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t07_ind_entregable) <= fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _entregable) THEN IFNULL(inf.t07_ind_avanc, 0) ELSE 0 END) AS ejec_mtaTotal,
	    MAX(CASE WHEN inf.t07_ind_anio =_anio AND inf.t07_ind_entregable=_entregable THEN inf.t07_descrip ELSE NULL END) AS descripcion,
	    MAX(CASE WHEN inf.t07_ind_anio =_anio AND inf.t07_ind_entregable=_entregable THEN inf.t07_logros  ELSE NULL END) AS logros,
	    MAX(CASE WHEN inf.t07_ind_anio =_anio AND inf.t07_ind_entregable=_entregable THEN inf.t07_dificul ELSE NULL END) AS dificultades,
	    MAX(CASE WHEN inf.t07_ind_anio =_anio AND inf.t07_ind_entregable=_entregable THEN inf.t07_observaciones ELSE NULL END) AS observaciones
	FROM t07_prop_ind ind
	LEFT JOIN t07_prop_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t07_cod_prop_ind=inf.t07_cod_prop_ind AND inf.t07_ind_anio<=_anio)
	WHERE ind.t02_cod_proy = _proy 
	  AND ind.t02_version = _ver
	GROUP BY ind.t07_cod_prop_ind,
	     ind.t07_ind,
	     ind.t07_um,
	     ind.t07_mta;
END $$

DELIMITER ;