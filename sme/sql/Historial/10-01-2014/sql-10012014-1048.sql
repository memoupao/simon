/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 17:10]
// 
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS `fn_numero_entregable`;

DELIMITER $$

CREATE FUNCTION `fn_numero_entregable`(_proy VARCHAR(10), 
    _ver INT, _anio INT, _entregable INT) RETURNS INT(11)
    DETERMINISTIC
BEGIN
    DECLARE _num INT DEFAULT 0;
    
    SELECT sb.num
    INTO _num
    FROM (SELECT t02_cod_proy, t02_version, t02_mes AS mes, @curRow:=@curRow+1 AS num, t02_anio AS anio
            FROM t02_entregable 
            JOIN (SELECT @curRow := 0) r 
            WHERE t02_cod_proy = _proy 
            AND t02_version  = _ver) AS sb
    WHERE sb.t02_cod_proy = _proy 
    AND sb.t02_version = _ver
    AND sb.anio = _anio
    AND sb.mes = _entregable;
    
    RETURN _num;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-01-2014 11:28]
// Lista de Informes de Entregable del Proyecto
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN
    SELECT 
        inf.t02_cod_proy,
        inf.t25_anio,
        CONCAT('Año ',inf.t25_anio) AS anio,
        inf.t25_entregable,
        fn_numero_entregable(_proy, _ver, inf.t25_anio, inf.t25_entregable) AS entregable,
        DATE_FORMAT(inf.t25_fch_pre,'%d/%m/%Y') AS fec_pre,
        inf.t25_periodo AS periodo,
        (case when inf.t25_vb='1' then 'VB' else '' end) AS vb,
        inf.t25_vb_fch AS vb_fec,
        SUBSTRING(inf.t25_vb_txt,1,205) as aprueba_txt,
        (case when length(inf.t25_conclu)>205 then concat(SUBSTRING(inf.t25_conclu,1,205),' ...') else inf.t25_conclu end ) AS conclusiones,
        est.descrip AS estado
    FROM t25_inf_entregable inf
    LEFT JOIN adm_tablas_aux est ON (inf.t25_estado = est.codi)
    WHERE inf.t02_cod_proy = _proy
    ORDER BY inf.t25_anio, entregable;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-01-2014 11:44]
// Obtiene el último informe de Entregable
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS `fn_nom_periodo_entregable`;

DELIMITER $$

CREATE FUNCTION `fn_nom_periodo_entregable`(_proy VARCHAR(10), _ver INT, _anio INT, _mes INT) RETURNS varchar(50) CHARSET utf8 
    DETERMINISTIC
BEGIN
    DECLARE _fecini DATE;
    DECLARE _fec_per_ini DATE;
    DECLARE _fec_per_fin DATE;
    DECLARE _return VARCHAR(50);
    DECLARE _anio_ant INT DEFAULT 0;
    DECLARE _entregable_ant INT DEFAULT 0;
    
    SELECT IFNULL(t02_mes, 0), t02_anio
    INTO _entregable_ant, _anio_ant
    FROM t02_entregable
    WHERE t02_cod_proy = _proy 
    AND t02_version = _ver
    AND DATE_ADD(DATE_ADD(NOW(), INTERVAL t02_anio YEAR), INTERVAL t02_mes MONTH) < DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _mes MONTH)
    ORDER BY t02_anio DESC, t02_mes DESC
    LIMIT 1;
    
    IF _anio_ant > 1 THEN
        SET _anio_ant = _anio_ant - 1;
    ELSE
        SET _anio_ant = 0;
    END IF;
    
    SELECT fn_fecha_inicio_proy(_proy, _ver) INTO _fecini;
    
    SET _fec_per_ini = DATE_ADD(_fecini, INTERVAL _anio_ant YEAR);
    SET _fec_per_ini = DATE_ADD(_fec_per_ini, INTERVAL _entregable_ant MONTH);
    SET _fec_per_fin = DATE_ADD(_fecini, INTERVAL (_anio - 1) YEAR);
    SET _fec_per_fin = DATE_ADD(_fec_per_fin, INTERVAL (_mes - 1) MONTH);
    
    SET _return = CONCAT(fn_nom_mes(MONTH(_fec_per_ini)), ' ', YEAR(_fec_per_ini), ' - ', fn_nom_mes(MONTH(_fec_per_fin)), ' ', YEAR(_fec_per_fin));
 
    RETURN _return;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-01-2014 12:52]
// Actualiza Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_trim_cab`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_cab`(IN _proy varchar(10), 
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME, 
            IN _estado INT,
            IN _usr VARCHAR(20),
            IN _obs_gp TEXT)
BEGIN
    UPDATE t25_inf_entregable 
    SET t25_fch_pre = _fchpres, 
        t25_periodo = _periodo, 
        t25_estado  = _estado, 
        usr_actu = _usr, 
        fch_actu = now(),
        obs_gp = _obs_gp
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
    
    SELECT ROW_COUNT() as numrows, _ver as codigo;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-01-2014 12:52]
// Lista Indicadores de Propósito del Informe de Entregable
// --------------------------------------------------<
*/
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
        SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t07_ind_anio, inf.t07_ind_entregable) < fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _anio, _entregable) THEN inf.t07_ind_avanc ELSE 0 END) AS ejec_mtaAcum,
        SUM(CASE WHEN inf.t07_ind_anio =_anio AND inf.t07_ind_entregable = _entregable THEN inf.t07_ind_avanc ELSE 0 END) AS ejec_mtaEntregable,
        SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t07_ind_anio, inf.t07_ind_entregable) <= fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _anio, _entregable) THEN IFNULL(inf.t07_ind_avanc, 0) ELSE 0 END) AS ejec_mtaTotal,
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

/*
// -------------------------------------------------->
// AQ 2.0 [10-01-2014 13:11]
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
        IFNULL(SUM(CASE WHEN fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, prog.t02_anio, prog.t02_mes) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) THEN prog.t09_cod_act_ind_val ELSE 0 END), 0) AS plan_mtaacum,
        /*SUM(CASE WHEN mta.t09_ind_anio=_anio AND mta.t09_ind_mes=_entregable THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtames,*/
        IFNULL(prog.t09_cod_act_ind_val, 0) AS plan_mtames,
        IFNULL((SELECT SUM(inf2.t09_ind_avanc) 
         FROM t09_entregable_ind_inf inf2 
         WHERE inf2.t02_cod_proy=ind.t02_cod_proy 
           AND inf2.t08_cod_comp=ind.t08_cod_comp  
           AND inf2.t09_cod_prod=ind.t09_cod_act 
           AND inf2.t09_cod_prod_ind=ind.t09_cod_act_ind 
           AND fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, inf2.t09_ind_anio, inf2.t09_ind_entregable) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) 
         ),0) AS ejec_mtaacum,
        IFNULL(MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_ind_avanc ELSE NULL END), 0) AS ejec_mtames,
        IFNULL((SELECT SUM(inf2.t09_ind_avanc) 
         FROM t09_entregable_ind_inf inf2 
         WHERE inf2.t02_cod_proy=ind.t02_cod_proy 
           AND inf2.t08_cod_comp=ind.t08_cod_comp  
           AND inf2.t09_cod_prod=ind.t09_cod_act 
           AND inf2.t09_cod_prod_ind=ind.t09_cod_act_ind 
           AND fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, inf2.t09_ind_anio, inf2.t09_ind_entregable) <= fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) 
         ), 0) AS ejec_mtatotal,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_descrip ELSE NULL END) AS descripcion,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_logros  ELSE NULL END) AS logros,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_dificul ELSE NULL END) AS dificultades,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_obs ELSE NULL END) AS observaciones
    FROM       t09_act_ind ind
    INNER JOIN t08_comp    com  ON(ind.t02_cod_proy=com.t02_cod_proy AND ind.t02_version=com.t02_version AND ind.t08_cod_comp=com.t08_cod_comp )
    INNER JOIN t09_act     act  ON(ind.t02_cod_proy=act.t02_cod_proy AND ind.t02_version=act.t02_version AND ind.t08_cod_comp=act.t08_cod_comp AND ind.t09_cod_act=act.t09_cod_act)
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
// AQ 2.0 [10-01-2014 13:17]
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
        SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t08_ind_anio, inf.t08_ind_entregable) < fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _anio, _entregable)  THEN inf.t08_ind_avanc ELSE 0  END) AS ejec_mtaAcum,
        SUM(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable = _entregable  THEN inf.t08_ind_avanc ELSE 0  END) AS ejec_mtaEntregable,
        SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t08_ind_anio, inf.t08_ind_entregable) <= fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _anio, _entregable) THEN IFNULL(inf.t08_ind_avanc, 0) ELSE 0  END) AS ejec_mtaTotal,
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