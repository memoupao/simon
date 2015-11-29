-- --------------------------------------------------------------------------------
-- DA V2.1
-- RF-003 - Informe de Supervisión - Registro de Participación de 
-- Beneficiarios por cada Indicador de Producto
-- 
-- RF-004 - Informe de Supervisión - Registro de Análisis del Avance 
-- Físico y Presupuestal por cada Indicador de Producto
-- 
-- --------------------------------------------------------------------------------


ALTER TABLE `t09_prod_ind_car_inf_se` 
DROP COLUMN `t09_avancefisicopres`,
DROP COLUMN `t09_partbenef`;




DROP procedure IF EXISTS `sp_upd_inf_se_ind_car_prod`;

DELIMITER $$

CREATE  PROCEDURE `sp_upd_inf_se_ind_car_prod`(
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
END$$

DELIMITER ;







DROP procedure IF EXISTS `sp_sel_inf_car_ind_prod_se`;

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
END$$

DELIMITER ;








