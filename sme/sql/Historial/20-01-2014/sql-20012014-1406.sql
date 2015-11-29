/*
// -------------------------------------------------->
// AQ 2.0 [20-01-2014 14:06]
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
    JOIN t02_entregable_act_ind_car prog ON(car.t02_cod_proy=prog.t02_cod_proy AND car.t08_cod_comp=prog.t08_cod_comp AND car.t09_cod_act=prog.t09_cod_act AND car.t09_cod_act_ind=prog.t09_cod_act_ind AND car.t09_cod_act_ind_car=prog.t09_cod_act_ind_car AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
    LEFT JOIN t09_prod_ind_car_inf_se inf ON(prog.t02_cod_proy=inf.t02_cod_proy AND prog.t08_cod_comp=inf.t08_cod_comp AND prog.t09_cod_act=inf.t09_cod_prod AND prog.t09_cod_act_ind=inf.t09_cod_prod_ind AND prog.t09_cod_act_ind_car=inf.t09_cod_prod_ind_car AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
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
// AQ 2.0 [20-01-2014 15:46]
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
    DECLARE _count INT;
    
    SELECT IFNULL(COUNT(t02_entregable), 0)
    INTO _existe
    FROM t30_inf_se 
    WHERE t02_cod_proy = _proy
    AND t02_anio = _anio
    AND t02_entregable = _entregable;
      
    IF _existe > 0 then
       SET _cod = 2;
    ELSE
        SELECT  IFNULL(COUNT(t30_estado), 0)
        INTO _count
        FROM t30_inf_se
        WHERE t02_cod_proy = _proy
        AND t02_anio <> _anio
        AND t02_entregable <> _entregable
        AND t30_estado <> 285;
        
        IF _count > 0 then
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