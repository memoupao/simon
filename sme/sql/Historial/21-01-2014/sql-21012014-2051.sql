/*
// -------------------------------------------------->
// AQ 2.0 [17-01-2014 18:42]
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
        /*SUM(CASE WHEN mta.t09_ind_anio=_anio AND mta.t09_ind_mes=_entregable THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtames,*/
        /*IFNULL(prog.t09_cod_act_ind_val, 0) AS plan_mtames,*/
        IFNULL(
        (SELECT e.t09_cod_act_ind_val 
         FROM t02_entregable_act_ind e 
         WHERE e.t02_cod_proy=ind.t02_cod_proy 
           AND e.t02_version=ind.t02_version
           AND e.t08_cod_comp=ind.t08_cod_comp 
           AND e.t09_cod_act=ind.t09_cod_act
           AND e.t09_cod_act_ind=ind.t09_cod_act_ind
           AND e.t02_anio = _anio
           AND e.t02_mes = _entregable
         ), 0) AS plan_mtames,
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
    LEFT JOIN t09_entregable_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable)
    /*LEFT JOIN t09_act_ind_mtas prog ON(ind.t02_cod_proy=prog.t02_cod_proy AND ind.t02_version=prog.t02_version AND ind.t08_cod_comp=prog.t08_cod_comp AND ind.t09_cod_act = prog.t09_cod_act AND ind.t09_cod_act_ind=prog.t09_cod_act_ind AND prog.t09_ind_anio=_anio AND prog.t09_ind_mes=_entregable)*/
    LEFT JOIN t02_entregable_act_ind prog ON(ind.t02_cod_proy=prog.t02_cod_proy AND ind.t02_version=prog.t02_version AND ind.t08_cod_comp=prog.t08_cod_comp AND ind.t09_cod_act = prog.t09_cod_act AND ind.t09_cod_act_ind=prog.t09_cod_act_ind AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
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
// AQ 2.0 [21-01-2014 21:00]
// Registro de Indicadores de Componente del Informe de Entregable
// Eliminación de Acumulado y Entregable Planeado
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

/*
// -------------------------------------------------->
// AQ 2.0 [21-01-2014 21:48]
// Lista Características de Indicadores 
// de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_car_ind_prod_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_car_ind_prod_se`(IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _comp INT, 
                        IN _prod INT,
                        IN _ind INT,
                        IN _anio INT,
                        IN _entregable INT)
BEGIN
    SELECT 
        DISTINCT car.t09_cod_act,
        car.t09_cod_act_ind,
        car.t09_cod_act_ind_car,
        car.t09_ind AS nombre,
        inf.t09_obs AS obs,
        inf.t09_avance AS avance
    FROM t09_act_ind_car car
    JOIN t02_entregable_act_ind_car prog ON(car.t02_cod_proy=prog.t02_cod_proy AND car.t02_version = prog.t02_version AND car.t08_cod_comp=prog.t08_cod_comp AND car.t09_cod_act=prog.t09_cod_act AND car.t09_cod_act_ind=prog.t09_cod_act_ind AND car.t09_cod_act_ind_car=prog.t09_cod_act_ind_car)
    LEFT JOIN t09_prod_ind_car_inf_se inf ON(prog.t02_cod_proy=inf.t02_cod_proy AND prog.t08_cod_comp=inf.t08_cod_comp AND prog.t09_cod_act=inf.t09_cod_prod AND prog.t09_cod_act_ind=inf.t09_cod_prod_ind AND prog.t09_cod_act_ind_car=inf.t09_cod_prod_ind_car AND prog.t02_anio=inf.t02_anio AND prog.t02_mes=inf.t02_entregable)
    WHERE car.t02_cod_proy = _proy
      AND car.t02_version = _ver
      AND car.t08_cod_comp = _comp
      AND car.t09_cod_act = _prod
      AND car.t09_cod_act_ind = _ind
      AND prog.t02_anio=_anio 
      AND prog.t02_mes=_entregable
    ORDER BY car.t09_cod_act_ind_car;
END $$

DELIMITER ;