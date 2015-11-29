/*
// -------------------------------------------------->
// AQ 2.0 [16-01-2014 12:25]
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
    JOIN t09_act_ind_car_ctrl prog ON(car.t02_cod_proy=prog.t02_cod_proy AND car.t02_version = prog.t02_version AND car.t08_cod_comp=prog.t08_cod_comp AND car.t09_cod_act=prog.t09_cod_act AND car.t09_cod_act_ind=prog.t09_cod_act_ind AND car.t09_cod_act_ind_car=prog.t09_cod_act_ind_car AND prog.t09_car_anio=_anio AND prog.t09_car_mes=_entregable)
    LEFT JOIN t09_prod_ind_car_inf_se inf ON(prog.t02_cod_proy=inf.t02_cod_proy AND prog.t08_cod_comp=inf.t08_cod_comp AND prog.t09_cod_act=inf.t09_cod_prod AND prog.t09_cod_act_ind=inf.t09_cod_prod_ind AND prog.t09_cod_act_ind_car=inf.t09_cod_prod_ind_car AND prog.t09_car_anio=_anio AND prog.t09_car_mes=_entregable)
    WHERE car.t02_cod_proy = _proy
      AND car.t02_version = _ver
      AND car.t08_cod_comp = _comp
      AND car.t09_cod_act = _prod
      AND car.t09_cod_act_ind = _ind
    ORDER BY car.t09_cod_act_ind_car;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [16-01-2014 12:25]
// Lista Características de Indicadores 
// de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_prod_en_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_prod_en_entregable`(in _proy varchar(10), _ver int, _anio INT, _entregable INT)
BEGIN
    SELECT 
        concat(prod.t08_cod_comp,'.',prod.t09_cod_act) as codigo,
        concat(prod.t08_cod_comp,'.',prod.t09_cod_act, ' ', prod.t09_act ) as producto,
        prod.t08_cod_comp,
        prod.t09_cod_act,
        prod.t09_act,
        prod.t09_obs
    FROM t09_act prod 
    JOIN t09_act_ind_mtas prog ON (prod.t02_cod_proy = prog.t02_cod_proy AND prod.t02_version = prog.t02_version AND prod.t08_cod_comp = prog.t08_cod_comp AND prod.t09_cod_act = prog.t09_cod_act AND prog.t09_ind_anio = _anio AND prog.t09_ind_mes = _entregable)
    WHERE prod.t02_cod_proy = _proy 
    AND prod.t02_version = _ver 
    ORDER BY prod.t08_cod_comp, prod.t09_cod_act;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [16-01-2014 16:59]
// Lista Indicadores de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_act_monext`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_prod_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_ind_prod_se`(IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _comp INT, 
                        IN _prod INT, 
                        IN _anio INT,
                        IN _entregable INT)
BEGIN
    SELECT ind.t08_cod_comp,
        com.t08_comp_desc AS componente,
        ind.t09_cod_act,
        act.t09_act AS actividad,
        ind.t09_cod_act_ind,
        ind.t09_ind AS indicador,
        ind.t09_um,
        ind.t09_mta AS plan_mtatotal,
        IFNULL(
        (SELECT e.t09_ind_mta 
         FROM t09_act_ind_mtas e 
         WHERE e.t02_cod_proy=ind.t02_cod_proy 
           AND e.t02_version=ind.t02_version
           AND e.t08_cod_comp=ind.t08_cod_comp 
           AND e.t09_cod_act=ind.t09_cod_act
           AND e.t09_cod_act_ind=ind.t09_cod_act_ind
           AND e.t09_ind_anio = _anio
           AND e.t09_ind_mes = _entregable
         ), 0) AS meta_al_entregable,
        IFNULL(
        (SELECT SUM(a.t09_ind_avanc) 
         FROM t09_entregable_ind_inf a 
         WHERE a.t02_cod_proy=ind.t02_cod_proy 
           AND a.t08_cod_comp=ind.t08_cod_comp 
           AND a.t09_cod_prod=ind.t09_cod_act
           AND a.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL a.t09_ind_anio YEAR), INTERVAL a.t09_ind_entregable MONTH) < DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtaacum,
        IFNULL(
        (SELECT SUM(b.t09_ind_avanc) 
         FROM t09_entregable_ind_inf b 
         WHERE b.t02_cod_proy=ind.t02_cod_proy 
           AND b.t08_cod_comp=ind.t08_cod_comp 
           AND b.t09_cod_prod=ind.t09_cod_act
           AND b.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL b.t09_ind_anio YEAR), INTERVAL b.t09_ind_entregable MONTH) = DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_avance,
        IFNULL(
        (SELECT SUM(c.t09_ind_avanc) 
         FROM t09_entregable_ind_inf c 
         WHERE c.t02_cod_proy=ind.t02_cod_proy 
           AND c.t08_cod_comp=ind.t08_cod_comp 
           AND c.t09_cod_prod=ind.t09_cod_act
           AND c.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL c.t09_ind_anio YEAR), INTERVAL c.t09_ind_entregable MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtatotal,
        inf.t09_obs AS obs,
        inf.t09_avance AS avance
    FROM t09_act_ind ind
    INNER JOIN t08_comp com ON(ind.t02_cod_proy=com.t02_cod_proy AND ind.t02_version=com.t02_version AND ind.t08_cod_comp=com.t08_cod_comp)
    INNER JOIN t09_act act ON(ind.t02_cod_proy=act.t02_cod_proy AND ind.t02_version=act.t02_version AND ind.t08_cod_comp=act.t08_cod_comp AND ind.t09_cod_act=act.t09_cod_act)
    LEFT  JOIN t09_prod_ind_inf_se inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t02_anio=_anio AND inf.t02_entregable=_entregable)
    WHERE ind.t02_cod_proy = _proy
      AND ind.t02_version = _ver
      AND ind.t08_cod_comp = _comp
      AND ind.t09_cod_act = _prod
    GROUP BY ind.t08_cod_comp, com.t08_comp_desc, ind.t09_cod_act, act.t09_act,
         ind.t09_cod_act_ind, ind.t09_um, ind.t09_ind, ind.t09_mta;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [16-01-2014 17:08]
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
        IFNULL(SUM(CASE WHEN fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, prog.t09_ind_anio, prog.t09_ind_mes) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) THEN prog.t09_ind_mta ELSE 0 END), 0) AS plan_mtaacum,
        /*SUM(CASE WHEN mta.t09_ind_anio=_anio AND mta.t09_ind_mes=_entregable THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtames,*/
        IFNULL(prog.t09_ind_mta, 0) AS plan_mtames,
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
    LEFT JOIN t09_entregable_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable)
    LEFT JOIN t09_act_ind_mtas prog ON(ind.t02_cod_proy=prog.t02_cod_proy AND ind.t02_version=prog.t02_version AND ind.t08_cod_comp=prog.t08_cod_comp AND ind.t09_cod_act = prog.t09_cod_act AND ind.t09_cod_act_ind=prog.t09_cod_act_ind AND prog.t09_ind_anio=_anio AND prog.t09_ind_mes=_entregable)
    WHERE ind.t02_cod_proy = _proy
      AND ind.t02_version = _ver
      AND ind.t08_cod_comp = _comp
      AND ind.t09_cod_act = _prod
      AND ind.t09_ind <> '' 
    GROUP BY ind.t08_cod_comp, com.t08_comp_desc, ind.t09_cod_act, act.t09_act,
         ind.t09_cod_act_ind, ind.t09_um, ind.t09_ind, ind.t09_mta;
END $$

DELIMITER ;