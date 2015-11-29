/*
// -------------------------------------------------->
// AQ 2.0 [31-12-2013 10:38]
// Lista de Indicadores de Producto del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_prod_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_ind_prod_entregable`(IN _proy VARCHAR(10), 
                    IN _ver INT,
                    IN _comp INT,
                    IN _prod INT, 
                    IN _anio INT, 
                    IN _entregable INT)
BEGIN
    /* Seleccionamos Los Indicadores de Producto y aquellos indicadores que ya fueron informados*/
    SELECT  ind.t08_cod_comp,
	    com.t08_comp_desc AS componente,
	    ind.t09_cod_act,
	    act.t09_act AS actividad,
	    ind.t09_cod_act_ind,
	    ind.t09_ind AS indicador,
	    ind.t09_um,
	    ind.t09_mta AS plan_mtatotal,
	    /*SUM(CASE WHEN fn_numero_entregable(mta.t09_ind_anio,mta.t09_ind_entregable) < fn_numero_entregable(_anio,_entregable) THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtaacum,*/
	    IFNULL(SUM(CASE WHEN fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, prog.t02_mes) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _entregable) THEN prog.t09_cod_act_ind_val ELSE 0 END), 0) AS plan_mtaacum,
	    /*SUM(CASE WHEN mta.t09_ind_anio=_anio AND mta.t09_ind_mes=_entregable THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtames,*/
	    IFNULL(prog.t09_cod_act_ind_val, 0) AS plan_mtames,
	    IFNULL((SELECT SUM(inf2.t09_ind_avanc) 
	     FROM t09_entregable_ind_inf inf2 
	     WHERE inf2.t02_cod_proy=ind.t02_cod_proy 
	       AND inf2.t08_cod_comp=ind.t08_cod_comp  
	       AND inf2.t09_cod_prod=ind.t09_cod_act 
	       AND inf2.t09_cod_prod_ind=ind.t09_cod_act_ind 
	       AND fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, inf2.t09_ind_entregable) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _entregable) 
	     ),0) AS ejec_mtaacum,
	    IFNULL(MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_ind_avanc ELSE NULL END), 0) AS ejec_mtames,
	    IFNULL((SELECT SUM(inf2.t09_ind_avanc) 
	     FROM t09_entregable_ind_inf inf2 
	     WHERE inf2.t02_cod_proy=ind.t02_cod_proy 
	       AND inf2.t08_cod_comp=ind.t08_cod_comp  
	       AND inf2.t09_cod_prod=ind.t09_cod_act 
	       AND inf2.t09_cod_prod_ind=ind.t09_cod_act_ind 
	       AND fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, inf2.t09_ind_entregable) <= fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _entregable) 
	     ), 0) AS ejec_mtatotal,
	    MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_descrip ELSE NULL END) AS descripcion,
	    MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_logros  ELSE NULL END) AS logros,
	    MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_dificul ELSE NULL END) AS dificultades,
	    MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_obs ELSE NULL END) AS observaciones
	FROM       t09_act_ind ind
	INNER JOIN t08_comp    com  ON(ind.t02_cod_proy=com.t02_cod_proy AND ind.t02_version=com.t02_version  AND ind.t08_cod_comp=com.t08_cod_comp )
	INNER JOIN t09_act     act  ON(ind.t02_cod_proy=act.t02_cod_proy AND ind.t02_version=act.t02_version  AND ind.t08_cod_comp=act.t08_cod_comp AND ind.t09_cod_act=act.t09_cod_act)
	LEFT  JOIN t09_entregable_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable)
	LEFT JOIN t02_entregable_act_ind prog ON(ind.t02_cod_proy=prog.t02_cod_proy AND ind.t02_version=prog.t02_version AND ind.t08_cod_comp=prog.t08_cod_comp AND ind.t09_cod_act = prog.t09_cod_act AND ind.t09_cod_act_ind=prog.t09_cod_act_ind AND prog.t02_anio<=_anio AND prog.t02_mes=_entregable)
	WHERE ind.t02_cod_proy = _proy
	  AND ind.t02_version = _ver
	  AND ind.t08_cod_comp = _comp
	  AND ind.t09_cod_act = _prod
	  AND ind.t09_ind <> '' 
	GROUP BY ind.t08_cod_comp, com.t08_comp_desc, ind.t09_cod_act, act.t09_act,
	     ind.t09_cod_act_ind, ind.t09_um, ind.t09_ind, ind.t09_mta;
END $$

DELIMITER ;


/*
// -------------------------------------------------->
// AQ 2.0 [31-12-2013 12:40]
// Registro de Avance de Indicadores de Producto del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_ind_producto`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_ind_producto`(IN _proy VARCHAR(10), 
    IN _ver INT, IN _comp INT,IN _prod INT, IN _prodind INT, 
    IN _anio INT,IN _entregable INT, IN _avance DOUBLE, 
    IN _descrip TEXT, IN _logros TEXT, IN _dificul TEXT, 
    IN _obs TEXT, IN _usr VARCHAR(20))
BEGIN
	DECLARE _existe INT;
    
    SELECT  IFNULL(COUNT(t09_cod_prod_ind), 0)
    INTO _existe
    FROM t09_entregable_ind_inf
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod    
    AND t09_cod_prod_ind = _prodind 
    AND t09_ind_anio = _anio
    AND t09_ind_entregable = _entregable;
  
    IF _existe <= 0 THEN 
	    BEGIN
	        INSERT INTO t09_entregable_ind_inf 
	        (
	        t02_cod_proy, 
	        t02_version, 
	        t08_cod_comp, 
	        t09_cod_prod, 
	        t09_cod_prod_ind, 
	        t09_ind_anio, 
	        t09_ind_entregable, 
	        t09_ind_avanc, 
	        t09_descrip, 
	        t09_logros, 
	        t09_dificul,
	        t09_obs,
	        usr_crea, 
	        fch_crea, 
	        est_audi
	        )
	        VALUES
	        (
	        _proy,
	        _ver,
	        _comp,   
	        _prod,
	        _prodind,
	        _anio,
	        _entregable,
	        _avance,
	        _descrip,
	        _logros,
	        _dificul,
	        _obs,
	        _usr,
	        NOW(),
	        '1'
	        );
	    END;
    ELSE
	    BEGIN
	        UPDATE t09_entregable_ind_inf 
	           SET  t09_ind_avanc=_avance,
	            t09_descrip=_descrip, 
	            t09_logros=_logros, 
	            t09_dificul=_dificul,
	            t09_obs = _obs,
	            usr_actu = _usr,
	            fch_actu = NOW()
	          WHERE  t02_cod_proy   = _proy 
	            AND  t08_cod_comp   = _comp 
	            AND  t09_cod_prod    = _prod    
	            AND  t09_cod_prod_ind= _prodind 
	            AND  t09_ind_anio   = _anio
	            AND  t09_ind_entregable = _entregable;
        END;
    END IF; 
 
    SELECT ROW_COUNT() INTO _existe;
 
    DELETE FROM t09_entregable_ind_inf 
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod    
    AND t09_ind_anio = _anio
    AND t09_ind_entregable = _entregable 
    AND (t09_ind_avanc  <=0 AND t09_descrip='' AND t09_logros='' AND t09_dificul='')
    AND t02_version = _ver;
 
    SELECT _existe AS numrows, _prodind AS codigo;                  
END $$

DELIMITER ;