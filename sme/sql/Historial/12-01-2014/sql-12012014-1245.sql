/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 17:10]
// Registra Carátula de Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_ins_inf_monext_cab`;
DROP PROCEDURE IF EXISTS `sp_ins_inf_se_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_ins_inf_se_cab`(IN _proy VARCHAR(10), 
            IN _anio  INT,
            IN _entregable  INT,
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME, 
            IN _estado INT,
            IN _fec_ini_vis DATETIME,
            IN _fec_ter_vis DATETIME,
            IN _usr VARCHAR(20))
BEGIN
    DECLARE _existe INT;
    DECLARE _cod INT;
    DECLARE _vb CHAR;
    
    SELECT IFNULL(COUNT(t02_entregable), 0)
    INTO _existe
    FROM t30_inf_se 
    WHERE t02_cod_proy = _proy
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
      
    IF _existe > 0 then
       SET _cod = 2;
    ELSE
        SELECT t30_estado 
        INTO _cod
        FROM t30_inf_se
        WHERE t02_cod_proy = _proy
        AND t02_anio <> _anio
        AND t02_entregable <> _entregable;
        
        IF _cod <> 285 then
           SET _cod = 3; -- Existe otro informe en proceso 
        ELSE
            /*SELECT IFNULL(COUNT(t02_cod_proy),0) 
            INTO _existe
            FROM t31_plan_me 
            WHERE t02_cod_proy = _proy
            AND t31_anio = _anio
            AND t31_id = _entregable;*/
        
           SET _existe = 1; -- Preguntar: si una visita debe estar asociada a un entregable
            
            IF _existe = 1 THEN
                /*SELECT t31_vb_v1
                INTO    _vb
                FROM    t31_plan_me 
                WHERE   t02_cod_proy = _proy;
                AND t31_id = _entregable
                AND t31_anio = _anio;*/ -- Preguntar: Relacionada al anterior
                
                SET _vb = "1"; -- Temporal
                    
                IF _vb = "1" THEN
                    INSERT INTO t30_inf_se (t02_cod_proy, 
                    t02_anio,
                    t02_entregable, 
                    t30_fch_pre, 
                    t30_periodo,    
                    t30_estado,
                    t30_fec_ini_vis,
                    t30_fec_ter_vis,
                    usr_crea, 
                    fch_crea, 
                    est_audi)
            VALUES( _proy, 
                    _anio,
                    _entregable, 
                    _fchpres, 
                    _periodo,
                    _estado, 
                    _fec_ini_vis,
                    _fec_ter_vis,
                    _usr, 
                    NOW(), 
                    '1');
                    SET _cod = 1;
                ELSE
                    SET _cod = 4; -- la visita aun no a sido aprobada
                END IF;
            ELSE
                SET _cod = 5; -- aun no se solicito una fecha para la visita
            END IF;
        END IF;
    END IF;
    
    SELECT ROW_COUNT() AS numrows, _cod AS codigo;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 12:58]
// Calcula puntaje de calaficación del Informe de Supervisión
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS `fn_puntaje_califica_inf_me`;
DROP FUNCTION IF EXISTS `fn_puntaje_califica_inf_se`;

DELIMITER $$

CREATE FUNCTION `fn_puntaje_califica_inf_se`(_proy VARCHAR(10), _anio INT, _entregable INT) RETURNS INT(11)
    DETERMINISTIC
BEGIN
    DECLARE _result INT;
    SELECT (
        IFNULL(ev1.cod_ext,0) + 
        IFNULL(ev2.cod_ext,0) + 
        IFNULL(ev3.cod_ext,0) + 
        IFNULL(ev4.cod_ext,0) +
        IFNULL(ev5.cod_ext,0) +
        IFNULL(ev6.cod_ext,0) +
        IFNULL(ev7.cod_ext,0)
       )
    INTO _result
    FROM  t30_inf_se me
    LEFT JOIN adm_tablas_aux ev1 ON (me.t30_crit_eva1=ev1.codi) 
    LEFT JOIN adm_tablas_aux ev2 ON (me.t30_crit_eva2=ev2.codi) 
    LEFT JOIN adm_tablas_aux ev3 ON (me.t30_crit_eva3=ev3.codi) 
    LEFT JOIN adm_tablas_aux ev4 ON (me.t30_crit_eva4=ev4.codi) 
    LEFT JOIN adm_tablas_aux ev5 ON (me.t30_crit_eva5=ev5.codi) 
    LEFT JOIN adm_tablas_aux ev6 ON (me.t30_crit_eva6=ev6.codi) 
    LEFT JOIN adm_tablas_aux ev7 ON (me.t30_crit_eva7=ev7.codi) 
    WHERE me.t02_cod_proy = _proy
    AND me.t02_anio = _anio
    AND me.t02_entregable = _entregable;
    
   RETURN _result;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 12:58]
// Verifica si el Informe de Supervisión existe
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS `fn_existe_inf_monext`;
DROP FUNCTION IF EXISTS `fn_existe_inf_se`;

DELIMITER $$

CREATE FUNCTION `fn_existe_inf_se`(_proy VARCHAR(10), _anio INT, _entregable INT) RETURNS TINYINT(1)
    DETERMINISTIC
BEGIN
    DECLARE _existe INT;
    SELECT  IFNULL(COUNT(t02_entregable), 0)
    INTO _existe
    FROM t30_inf_se 
    WHERE t02_cod_proy = _proy
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
      
    IF _existe > 0 then
       RETURN TRUE;
    ELSE
       RETURN FALSE;
    END IF;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 12:58]
// Obtiene Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_get_inf_monext`;
DROP PROCEDURE IF EXISTS `sp_get_inf_se`;

DELIMITER $$

CREATE PROCEDURE `sp_get_inf_se`(IN _proy VARCHAR(10), 
               IN _anio INT,
               IN _entregable INT)
BEGIN
    SELECT  
            t02_cod_proy,
            t02_anio,
            t02_entregable, 
            DATE_FORMAT(t30_fch_pre,'%d/%m/%Y') AS fch_pre, 
            t30_periodo AS periodo,
            t30_estado AS estado, 
            t30_intro, 
            t30_fuentes, 
            DATE_FORMAT(t30_fec_ini_vis,'%d/%m/%Y') AS iniVisita, 
            DATE_FORMAT(t30_fec_ter_vis,'%d/%m/%Y') AS terVisita, 
            t30_crit_eva1, 
            t30_crit_eva2, 
            t30_crit_eva3, 
            t30_crit_eva4, 
            t30_crit_eva5, 
            t30_crit_eva6,
            t30_crit_eva7,
            t30_apro, 
            t30_apro_fch, 
            t30_avance, 
            t30_logros, 
            t30_dificul, 
            t30_reco_proy, 
            t30_reco_fe, 
            t30_califica, 
            usr_crea, 
            fch_crea, 
            usr_actu, 
            fch_actu, 
            est_audi,
            fn_puntaje_califica_inf_se(t02_cod_proy, t02_anio, t02_entregable) AS puntaje,
            t30_obs AS obsGP
    FROM    t30_inf_se
    WHERE   t02_cod_proy = _proy
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [11-01-2014 21:22]
// Lista Informes de Supervisión Externa
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_monext`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_se`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN
    SELECT  
        inf.t02_cod_proy,
        inf.t02_anio,
        CONCAT('Año ',inf.t02_anio) AS anio,
        inf.t02_entregable,
        DATE_FORMAT(inf.t30_fch_pre,'%d/%m/%Y') AS fec_pre, 
        inf.t30_periodo AS periodo, 
        inf.t30_estado, 
        est.descrip AS estado,
        SUBSTRING(inf.t30_intro, 1, 150) AS introduccion,
        SUBSTRING(inf.t30_fuentes, 1, 150) AS fuentes,
        fn_numero_entregable(_proy, _ver, inf.t02_anio, inf.t02_entregable) AS entregable
    FROM t30_inf_se inf
    LEFT JOIN adm_tablas_aux est ON (inf.t30_estado = est.codi) 
    WHERE inf.t02_cod_proy = _proy
    ORDER BY entregable ASC;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [11-01-2014 21:27]
// Elimina Informe de Supervisión Externa
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_del_inf_monext_cab`;
DROP PROCEDURE IF EXISTS `sp_del_inf_se`;

DELIMITER $$

CREATE PROCEDURE `sp_del_inf_se`(
            IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT)
BEGIN
    DELETE FROM t30_inf_se 
    WHERE t02_cod_proy=_proy 
    AND t02_anio = _anio 
    AND t02_entregable = _entregable;
  
    SELECT ROW_COUNT() AS numrows;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [11-01-2014 21:27]
// Actualiza Carátula de Informe de Supervisión Externa
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_monext_cab`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_cab`(IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT,
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME , 
            IN _estado INT,
            IN _intro TEXT,
            IN _fuentes TEXT,
            IN _fec_ini_vis DATETIME,
            IN _fec_ter_vis DATETIME,
            IN _obs TEXT,
            IN _usr VARCHAR(20))
BEGIN
    UPDATE  t30_inf_se 
    SET     t30_fch_pre     = _fchpres, 
            t30_estado      = _estado, 
            t30_intro       = _intro,
            t30_fuentes     = _fuentes,
            t30_fec_ini_vis = _fec_ini_vis,
            t30_fec_ter_vis = _fec_ter_vis,
            t30_obs         = _obs,
            usr_actu        = _usr, 
            fch_actu        = NOW() 
    WHERE   t02_cod_proy = _proy
        AND t02_anio = _anio
        AND t02_entregable = _entregable;
        
    SELECT ROW_COUNT() AS numrows;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 17:10]
// Lista Indicadores de Componente del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_monext_ind_comp`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_se_ind_comp`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_se_ind_comp`(IN _proy VARCHAR(10), 
                     IN _ver INT,
                     IN _comp INT,
                     IN _anio INT,
                     IN _entregable INT)
BEGIN
    SELECT 
        ind.t08_cod_comp_ind,
        ind.t08_ind AS indicador,
        ind.t08_um,
        ind.t08_mta AS plan_mtatotal,
        IFNULL(
        (SELECT SUM(a.t08_ind_avanc) 
         FROM t08_comp_ind_inf a 
         WHERE a.t02_cod_proy=ind.t02_cod_proy 
           AND a.t08_cod_comp=ind.t08_cod_comp 
           AND a.t08_cod_comp_ind=ind.t08_cod_comp_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL a.t08_ind_anio YEAR), INTERVAL a.t08_ind_entregable MONTH) < DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtaacum,
        IFNULL(
        (SELECT SUM(b.t08_ind_avanc) 
         FROM t08_comp_ind_inf b 
         WHERE b.t02_cod_proy=ind.t02_cod_proy 
           AND b.t08_cod_comp=ind.t08_cod_comp 
           AND b.t08_cod_comp_ind=ind.t08_cod_comp_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL b.t08_ind_anio YEAR), INTERVAL b.t08_ind_entregable MONTH) = DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_avance,
        IFNULL(
        (SELECT SUM(c.t08_ind_avanc) 
         FROM t08_comp_ind_inf c 
         WHERE c.t02_cod_proy=ind.t02_cod_proy 
           AND c.t08_cod_comp=ind.t08_cod_comp 
           AND c.t08_cod_comp_ind=ind.t08_cod_comp_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL c.t08_ind_anio YEAR), INTERVAL c.t08_ind_entregable MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtatotal,
        inf.t08_obs AS obs,
        inf.t08_avance AS avance
    FROM t08_comp_ind ind
    LEFT JOIN t08_comp_ind_inf_se inf ON (ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp AND ind.t08_cod_comp_ind=inf.t08_cod_comp_ind AND inf.t02_anio=_anio AND inf.t02_entregable=_entregable)
    WHERE ind.t02_cod_proy =_proy
      AND  ind.t02_version = _ver
      AND  ind.t08_cod_comp = _comp
    ORDER BY 1,2;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [11-01-2014 11:59]
// Actualiza Indicadores de Componente del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_monext_ind_comp`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_ind_comp`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_ind_comp`(
                    IN _proy VARCHAR(10), 
                    IN _comp INT ,
                    IN _compind INT ,  
                    IN _anio INT,
                    IN _entregable INT,
                    IN _obs TEXT,
                    IN _avance DOUBLE,
                    IN _usr VARCHAR(20)
                   )
BEGIN
    DECLARE _existe INT;
 
    SELECT IFNULL(COUNT(t08_cod_comp_ind),0)
    INTO _existe
    FROM t08_comp_ind_inf_se
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp
    AND t08_cod_comp_ind= _compind 
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
  
    IF _existe <= 0 THEN 
        INSERT INTO t08_comp_ind_inf_se 
            (
            t02_cod_proy, 
            t08_cod_comp,
            t08_cod_comp_ind, 
            t02_anio,
            t02_entregable, 
            t08_obs,
            t08_avance,
            usr_crea, 
            fch_crea, 
            est_audi
            )
            VALUES
            (
            _proy,   
            _comp,
            _compind,   
            _anio,
            _entregable,
            _obs,
            _avance,
            _usr,
            NOW(),   
            '1'
            );
    ELSE
        UPDATE t08_comp_ind_inf_se 
           SET t08_obs = _obs,
            t08_avance = _avance,
            usr_actu = _usr,
            fch_actu = NOW()
          WHERE t02_cod_proy = _proy 
            AND t08_cod_comp = _comp
            AND t08_cod_comp_ind = _compind 
            AND t02_anio = _anio
            AND t02_entregable = _entregable;
    END IF; 
 
    SELECT ROW_COUNT() INTO _existe;
 
    DELETE FROM t08_comp_ind_inf_se 
    WHERE t02_cod_proy = _proy
    AND t08_cod_comp = _comp 
    AND t02_anio = _anio
    AND t02_entregable = _entregable
    AND (t08_obs = '' OR t08_obs = NULL)
    AND (t08_avance = NULL OR t08_avance=0);
 
    SELECT _existe AS numrows; 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [12-01-2014 10:24]
// Actualiza Indicadores de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_monext_ind_act`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_ind_prod`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_ind_prod`(
                    IN _proy VARCHAR(10),
                    IN _comp INT,
                    IN _prod INT,
                    IN _prod_ind INT,  
                    IN _anio INT,
                    IN _entregable INT,
                    IN _obs TEXT,
                    IN _avance DOUBLE,
                    IN _usr VARCHAR(20)
                   )
BEGIN
	DECLARE _existe INT;
    
	SELECT IFNULL(COUNT(t09_cod_prod_ind),0)
    INTO _existe
    FROM t09_prod_ind_inf_se
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod    
    AND t09_cod_prod_ind = _prod_ind 
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
  
  IF _existe <= 0 THEN 
        INSERT INTO t09_prod_ind_inf_se 
            (
            t02_cod_proy, 
            t08_cod_comp, 
            t09_cod_prod, 
            t09_cod_prod_ind, 
            t02_anio,
            t02_entregable, 
            t09_obs,
            t09_avance,
            usr_crea, 
            fch_crea, 
            est_audi
            )
            VALUES
            (
            _proy,   
            _comp,   
            _prod,
            _prod_ind,   
            _anio,
            _entregable,
            _obs,
            _avance,
            _usr,
            NOW(),   
            '1'
            );
    ELSE
        UPDATE t09_prod_ind_inf_se 
           SET  t09_obs = _obs,
            t09_avance = _avance,
            usr_actu = _usr,
            fch_actu = NOW()
          WHERE  t02_cod_proy = _proy 
            AND  t08_cod_comp = _comp 
            AND  t09_cod_prod = _prod    
            AND  t09_cod_prod_ind = _prod_ind 
            AND t02_anio = _anio
            AND t02_entregable = _entregable;
    END IF; 
 
    SELECT ROW_COUNT() INTO _existe;
 
    DELETE FROM t09_prod_ind_inf_se 
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod    
    AND t02_anio = _anio
    AND t02_entregable = _entregable
    AND (t09_obs = '' OR t09_obs = NULL)
    AND (t09_avance = NULL OR t09_avance=0);
 
    SELECT _existe AS numrows, _prod_ind AS codigo ;  
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [12-01-2014 10:51]
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
        (SELECT e.t09_cod_act_ind_val 
         FROM t02_entregable_act_ind e 
         WHERE e.t02_cod_proy=ind.t02_cod_proy 
           AND e.t02_version=ind.t02_version
           AND e.t08_cod_comp=ind.t08_cod_comp 
           AND e.t09_cod_act=ind.t09_cod_act
           AND e.t09_cod_act_ind=ind.t09_cod_act_ind
           AND e.t02_anio = _anio
           AND e.t02_mes = _entregable
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
// AQ 2.0 [12-01-2014 18:03]
// Lista de Actividades del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_sub_act_monext`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_act_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_act_se`(IN _proy VARCHAR(10), 
                IN _ver INT,
                IN _comp INT, 
                IN _prod INT, 
                IN _anio INT,
                IN _entregable INT)
BEGIN
	SELECT sub.t08_cod_comp,
	    com.t08_comp_desc AS componente,
	    sub.t09_cod_act,
	    act.t09_act AS actividad,
	    sub.t09_cod_sub,
	    sub.t09_sub AS subactividad,
	    sub.t09_um,
	    sub.t09_mta_repro AS plan_mtatotal,
	    IFNULL(
	    (SELECT SUM(a.t09_sub_avanc) 
	     FROM t09_act_sub_mtas_inf a 
	     WHERE a.t02_cod_proy = sub.t02_cod_proy 
	       AND a.t08_cod_comp = sub.t08_cod_comp 
	       AND a.t09_cod_act = sub.t09_cod_act
	       AND a.t09_cod_sub = sub.t09_cod_sub
	       AND DATE_ADD(DATE_ADD(NOW(), INTERVAL a.t09_sub_anio YEAR), INTERVAL a.t09_sub_mes MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
	    ), 0) AS ejecutado,
	    IFNULL(
	    (SELECT SUM(b.t09_mta) 
         FROM t09_sub_act_mtas b 
         WHERE b.t02_cod_proy = sub.t02_cod_proy 
           AND b.t08_cod_comp = sub.t08_cod_comp 
           AND b.t09_cod_act = sub.t09_cod_act
           AND b.t09_cod_sub = sub.t09_cod_sub
	       AND DATE_ADD(DATE_ADD(NOW(), INTERVAL b.t09_anio YEAR), INTERVAL b.t09_mes MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
	       AND b.t02_version = _ver
	    ), 0) AS programado,
	    inf.t09_obs AS obs
	FROM t09_subact sub
	INNER JOIN t08_comp com ON(sub.t02_cod_proy=com.t02_cod_proy AND sub.t02_version=com.t02_version AND sub.t08_cod_comp=com.t08_cod_comp)
	INNER JOIN t09_act act ON(sub.t02_cod_proy=act.t02_cod_proy AND sub.t02_version=act.t02_version AND sub.t08_cod_comp=act.t08_cod_comp AND sub.t09_cod_act=act.t09_cod_act)
	LEFT JOIN t09_act_inf_se inf ON(sub.t02_cod_proy=inf.t02_cod_proy AND sub.t08_cod_comp=inf.t08_cod_comp  AND sub.t09_cod_act=inf.t09_cod_prod AND sub.t09_cod_sub=inf.t09_cod_act AND inf.t02_anio=_anio AND inf.t02_entregable=_entregable)
	WHERE sub.t02_cod_proy = _proy
	  AND sub.t02_version = _ver
	  AND sub.t08_cod_comp = _comp
	  AND sub.t09_cod_act = _prod
	GROUP BY sub.t08_cod_comp, com.t08_comp_desc, sub.t09_cod_act, act.t09_act,
	     sub.t09_cod_sub, sub.t09_um, sub.t09_sub, sub.t09_mta;
     
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [12-01-2014 10:51]
// Lista Indicadores de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_monext_sub_act`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_act`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_act`(
                    IN _proy VARCHAR(10), 
                    IN _comp INT, 
                    IN _prod INT, 
                    IN _act INT ,  
                    IN _anio INT,
                    IN _entregable INT,
                    IN _obs TEXT,
                    IN _usr VARCHAR(20)
                   )
BEGIN
    DECLARE _existe INT;
 
    SELECT IFNULL(COUNT(t09_cod_act),0)
    INTO _existe
    FROM t09_act_inf_se
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod
    AND t09_cod_act = _act
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
  
    IF _existe <= 0 THEN 
        INSERT INTO t09_act_inf_se 
            (
            t02_cod_proy,
            t02_anio,
            t02_entregable,
            t08_cod_comp, 
            t09_cod_prod, 
            t09_cod_act, 
            t09_obs, 
            usr_crea, 
            fch_crea, 
            est_audi
            )
            VALUES
            (
            _proy,
            _anio,
            _entregable,
            _comp,
            _prod,
            _act,  
            _obs,
            _usr,
            NOW(),   
            '1'
            );
    ELSE
        UPDATE t09_act_inf_se 
           SET t09_obs = _obs, 
            usr_actu = _usr,
            fch_actu = NOW()
          WHERE t02_cod_proy = _proy 
            AND t08_cod_comp = _comp 
            AND t09_cod_prod = _prod    
            AND t09_cod_act = _act 
            AND t02_anio = _anio
            AND t02_entregable = _entregable;
    END IF; 
 
    SELECT ROW_COUNT() INTO _existe;
 
    DELETE FROM t09_act_inf_se 
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod
    AND t02_anio = _anio
    AND t02_entregable = _entregable
    AND t09_obs = '';
 
    SELECT _existe AS numrows;  
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [12-01-2014 11:58]
// Actualiza Conclusiones del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_monext_conc`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_conc`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_conc`(
            IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT,
            IN _avance TEXT, 
            IN _logros TEXT , 
            IN _dificul TEXT,
            IN _recom_proy TEXT,
            IN _recom_fe TEXT,
            IN _usr VARCHAR(20))
BEGIN
  UPDATE t30_inf_se 
     SET t30_avance = _avance, 
     t30_logros = _logros, 
     t30_dificul = _dificul, 
     t30_reco_proy = _recom_proy,
     t30_reco_fe = _recom_fe,
     usr_actu = _usr,
     fch_actu = NOW() 
   WHERE t02_cod_proy = _proy
     AND t02_anio = _anio
     AND t02_entregable = _entregable;

    SELECT ROW_COUNT() AS numrows;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [13-01-2014 01:27]
// Actualiza Calificación del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_monext_calif`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_calif`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_calif`(
            IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT,
            IN _eva1 INT, 
            IN _eva2 INT , 
            IN _eva3 INT,
            IN _eva4 INT,
            IN _eva5 INT,
            IN _eva6 INT,
            IN _eva7 INT,
            IN _cali TEXT,
            IN _usr VARCHAR(20))
BEGIN
  UPDATE t30_inf_se 
     SET t30_crit_eva1 = _eva1, 
     t30_crit_eva2 = _eva2, 
     t30_crit_eva3 = _eva3, 
     t30_crit_eva4 = _eva4, 
     t30_crit_eva5 = _eva5,
     t30_crit_eva6 = _eva6,
     t30_crit_eva7 = _eva7,
     t30_califica  = _cali,
     usr_actu = _usr, 
     fch_actu = NOW() 
   WHERE t02_cod_proy = _proy
     AND t02_anio = _anio
     AND t02_entregable = _entregable;
     
    SELECT ROW_COUNT() AS numrows;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [13-01-2014 01:59]
// Registra Anexos del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_ins_inf_monext_anexos`;
DROP PROCEDURE IF EXISTS `sp_ins_inf_se_anexos`;

DELIMITER $$

CREATE PROCEDURE `sp_ins_inf_se_anexos`(
                IN _proy VARCHAR(10), 
                IN _anio INT, 
                IN _entregable INT,
                IN _nom VARCHAR(100),
                IN _desc TEXT,
                IN _ext VARCHAR(5),
                IN _usr VARCHAR(20)
                )
BEGIN
	DECLARE _url VARCHAR(50);
	DECLARE _id INT;
	
	SELECT IFNULL(MAX(t30_cod_anx),0)+1
	INTO _id
	FROM t30_inf_se_anexos
	WHERE t02_cod_proy = _proy 
	    AND t02_anio = _anio 
	    AND t02_entregable = _entregable;
	    
	SELECT CONCAT(_proy,'_',_anio,'_',_entregable,'_',_id,'.', _ext) INTO _url;
	
	INSERT INTO t30_inf_se_anexos 
	    (t02_cod_proy, 
	    t02_anio, 
	    t02_entregable, 
	    t30_cod_anx, 
	    t30_nom_file, 
	    t30_url_file, 
	    t30_desc_file, 
	    usr_crea, 
	    fch_crea
	    )
	    VALUES
	    ( _proy, 
	      _anio, 
	      _entregable, 
	      _id , 
	      _nom , 
	      _url , 
	      _desc, 
	      _usr, 
	      NOW()
	    );
	    
	SELECT ROW_COUNT() AS numrows, _id AS codigo, _url AS url; 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [13-01-2014 01:59]
// Registra Anexo del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_monext_anexos`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_se_anexos`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_se_anexos`(
                IN _proy VARCHAR(10), 
                IN _anio INT, 
                IN _entregable INT)
BEGIN
	SELECT t30_cod_anx,
	    t30_nom_file,
	    t30_url_file,
	    t30_desc_file
	FROM t30_inf_se_anexos
	WHERE t02_cod_proy = _proy 
    AND t02_anio = _anio 
    AND t02_entregable = _entregable;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [13-01-2014 02:49]
// Elimina Anexo del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_del_inf_monext_anexos`;
DROP PROCEDURE IF EXISTS `sp_del_inf_se_anexo`;

DELIMITER $$

CREATE PROCEDURE `sp_del_inf_se_anexo`(
            IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT,
            IN _codanx INT)
BEGIN
  DECLARE _url VARCHAR(100);
  
  SELECT t30_url_file INTO _url
  FROM t30_inf_se_anexos
  WHERE t02_cod_proy=_proy 
    AND t02_anio = _anio 
    AND t02_entregable = _entregable
    AND t30_cod_anx = _codanx;
    
  DELETE FROM t30_inf_se_anexos
  WHERE t02_cod_proy = _proy 
    AND t02_anio = _anio 
    AND t02_entregable = _entregable
    AND t30_cod_anx = _codanx;
    
    SELECT ROW_COUNT() AS numrows, _url AS url; 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [14-01-2014 20:15]
// Actualiza Características de Indicadores 
// de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_se_ind_car_prod`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_ind_car_prod`(
                    IN _proy VARCHAR(10),
                    IN _comp INT,
                    IN _prod INT,
                    IN _prod_ind INT,  
                    IN _prod_ind_car INT,
                    IN _anio INT,
                    IN _entregable INT,
                    IN _obs TEXT,
                    IN _avance DOUBLE,
                    IN _usr VARCHAR(20)
                   )
BEGIN
    DECLARE _existe INT;
    
    SELECT IFNULL(COUNT(t09_cod_prod_ind_car),0)
    INTO _existe
    FROM t09_prod_ind_car_inf_se
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod    
    AND t09_cod_prod_ind = _prod_ind 
    AND t09_cod_prod_ind_car = _prod_ind_car 
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
  
  IF _existe <= 0 THEN 
        INSERT INTO t09_prod_ind_car_inf_se 
            (
            t02_cod_proy, 
            t08_cod_comp, 
            t09_cod_prod, 
            t09_cod_prod_ind, 
            t09_cod_prod_ind_car,
            t02_anio,
            t02_entregable, 
            t09_obs,
            t09_avance,
            usr_crea, 
            fch_crea, 
            est_audi
            )
            VALUES
            (
            _proy,   
            _comp,   
            _prod,
            _prod_ind,
            _prod_ind_car,
            _anio,
            _entregable,
            _obs,
            _avance,
            _usr,
            NOW(),   
            '1'
            );
    ELSE
        UPDATE t09_prod_ind_car_inf_se 
           SET  t09_obs = _obs,
            t09_avance = _avance,
            usr_actu = _usr,
            fch_actu = NOW()
          WHERE  t02_cod_proy = _proy 
            AND  t08_cod_comp = _comp 
            AND  t09_cod_prod = _prod    
            AND  t09_cod_prod_ind = _prod_ind
            AND  t09_cod_prod_ind_car = _prod_ind_car
            AND t02_anio = _anio
            AND t02_entregable = _entregable;
    END IF; 
 
    SELECT ROW_COUNT() INTO _existe;
 
    DELETE FROM t09_prod_ind_car_inf_se 
    WHERE t02_cod_proy = _proy 
    AND t08_cod_comp = _comp 
    AND t09_cod_prod = _prod 
    AND t09_cod_prod_ind = _prod_ind
    AND t02_anio = _anio
    AND t02_entregable = _entregable
    AND (t09_obs = '' OR t09_obs = NULL)
    AND (t09_avance = NULL OR t09_avance=0);
 
    SELECT _existe AS numrows;  
END $$

DELIMITER ;
