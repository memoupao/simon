/*
// -------------------------------------------------->
// AQ 2.0 [30-12-2013 22:32]
// Registro de Indicadores de Componente del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable_ind_comp`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable_ind_comp`(IN _proy VARCHAR(10), 
                IN _ver INT,
				IN _comp INT,
				IN _anio INT, 
				IN _entregable INT)
BEGIN
    
	/* Seleccionamos Los Indicadores de Componente y aquellos indicadores que ya fueron informados*/
	SELECT  ind.t08_cod_comp_ind,
	    ind.t08_ind AS indicador,
	    ind.t08_um,
	    ind.t08_mta AS plan_mtaTotal,
	    SUM(0)      AS plan_mtaAcum,
	    SUM(0)      AS plan_mtaEntregable,
	    SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t08_ind_entregable) < fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _entregable)  THEN inf.t08_ind_avanc ELSE 0  END) AS ejec_mtaAcum,
	    SUM(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable = _entregable  THEN inf.t08_ind_avanc ELSE 0  END) AS ejec_mtaEntregable,
	    SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t08_ind_entregable) <= fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _entregable) THEN IFNULL(inf.t08_ind_avanc, 0) ELSE 0  END) AS ejec_mtaTotal,
	    MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_descrip ELSE NULL END) AS descripcion,
	    MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_logros  ELSE NULL END) AS logros,
	    MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_dificul ELSE NULL END) AS dificultades,
	    MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_obs ELSE NULL END) AS observaciones
	FROM       t08_comp_ind ind  
	LEFT JOIN  t08_comp_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp AND ind.t08_cod_comp_ind=inf.t08_cod_comp_ind AND inf.t08_ind_anio<=_anio)
	WHERE  ind.t02_cod_proy=_proy
	  AND  ind.t02_version=_ver
	  AND  ind.t08_cod_comp=_comp
	GROUP BY ind.t08_cod_comp_ind,
	     ind.t08_ind,
	     ind.t08_um,
	     ind.t08_mta;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_ind_comp_obs`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_ind_comp_obs`(IN _proy VARCHAR(10), 
    IN _ver INT, IN _comp INT, IN _compind INT, IN _anio INT, IN _entregable INT, 
    IN _avance DOUBLE, IN _descrip TEXT, IN _logros TEXT,
    IN _dificul TEXT,IN _obs TEXT, IN _usr TEXT)
	BEGIN
	 DECLARE _existe INT;
	 
	 SELECT  IFNULL(COUNT(t08_cod_comp_ind),0)
	   INTO  _existe
	   FROM  t08_comp_ind_inf
	  WHERE  t02_cod_proy    = _proy 
	    and  t08_cod_comp    = _comp
	    AND  t08_cod_comp_ind= _compind 
	    AND  t08_ind_anio    = _anio
	    AND  t08_ind_entregable    = _entregable ;
	  
	  IF _existe <= 0 THEN 
	    BEGIN
	        INSERT INTO t08_comp_ind_inf 
	            (
	            t02_cod_proy, 
	            t02_version, 
	            t08_cod_comp,
	            t08_cod_comp_ind, 
	            t08_ind_anio, 
	            t08_ind_entregable, 
	            t08_ind_avanc, 
	            t08_descrip, 
	            t08_logros, 
	            t08_dificul,
	            t08_obs,
	            usr_crea, 
	            fch_crea, 
	            est_audi
	            )
	            VALUES
	            (
	            _proy   ,   
	            _ver ,
	            _comp,
	            _compind ,   
	            _anio , 
	            _entregable , 
	            _avance , 
	            _descrip ,
	            _logros  ,
	            _dificul ,
	            _obs,
	            _usr,
	            NOW()   ,   
	            '1'
	            );
	        
	    END;
	  ELSE
	    BEGIN
	        UPDATE t08_comp_ind_inf 
	           SET  t08_ind_avanc=_avance,
	            t08_descrip=_descrip, 
	            t08_logros=_logros, 
	            t08_dificul=_dificul, 
	            t08_obs = _obs,
	            usr_actu = _usr,
	            fch_actu = NOW()
	          WHERE  t02_cod_proy    = _proy 
	            AND  t08_cod_comp    = _comp
	            AND  t08_cod_comp_ind= _compind 
	            AND  t08_ind_anio    = _anio
	            AND  t08_ind_entregable    = _entregable ;
	      END;
	  END IF; 
	 
	 SELECT ROW_COUNT() INTO _existe;
	 
	 DELETE FROM t08_comp_ind_inf 
	  WHERE  t02_cod_proy   = _proy
	    AND  t08_cod_comp   = _comp 
	    AND  t08_ind_anio   = _anio
	    AND  t08_ind_entregable   = _entregable 
	    AND  (t08_ind_avanc  <=0 AND t08_descrip='' AND t08_logros='' AND t08_dificul='' and t08_obs) ;
	 
	/*Retornar el numero de registros afectados */      
	SELECT _existe AS numrows, _compind AS codigo ; 
END $$

DELIMITER ;