-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-006 - Informe de Supervisión - Modificar pestaña Calificación
-- --------------------------------------------------------------------------------

UPDATE `adm_tablas` SET `nom_tabla`='Valoraciones Informe SE' WHERE `cod_tabla`='16';
DELETE FROM `adm_tablas` WHERE cod_tabla IN ('37');
INSERT INTO `adm_tablas` (`cod_tabla`,`nom_tabla`, `flg_act`) VALUES ('37','Valores de Calificaciones SE', '1');


UPDATE t30_inf_se SET t30_crit_eva5 = 0, t30_crit_eva6 = 0, t30_crit_eva7 = 0;



UPDATE `adm_tablas_aux` SET `descrip`='Mala', `abrev`='Mala', `orden`='1' WHERE `codi`='52' and`orden`='3';
UPDATE `adm_tablas_aux` SET `descrip`='Regular', `abrev`='Regular' WHERE `codi`='51' and`orden`='2';
UPDATE `adm_tablas_aux` SET `descrip`='Buena', `abrev`='Buena', `orden`='3' WHERE `codi`='50' and`orden`='1';


DELETE FROM `adm_tablas_aux` WHERE codi IN ('304','305','306');
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`) VALUES ('304', 'Aprobado', 'Aprobado', '2', '1', '37', '1');
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`) VALUES ('305', 'Aprobado con Reserva', 'Aprobado con Reserva', '1', '1', '37', '2');
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`) VALUES ('306', 'Desaprobado', 'Desaprobado', '0', '1', '37', '3');




ALTER TABLE `t30_inf_se` ADD COLUMN `t30_crit_final` INT(11) NULL  AFTER `t30_crit_eva7` ;




DROP procedure IF EXISTS `sp_get_inf_se`;

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
			t30_crit_final,
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
END$$

DELIMITER ;









DROP procedure IF EXISTS `sp_upd_inf_se_calif`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_calif`(
            IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT,
            IN _eva1 INT, 
            IN _eva2 INT , 
            IN _eva3 INT,
            IN _eva4 INT,
            IN _evafinal INT,            
            IN _cali TEXT,
            IN _usr VARCHAR(20))
BEGIN
  UPDATE t30_inf_se 
     SET t30_crit_eva1 = _eva1, 
     t30_crit_eva2 = _eva2, 
     t30_crit_eva3 = _eva3, 
     t30_crit_eva4 = _eva4, 
     t30_crit_final = _evafinal,     
     t30_califica  = _cali,
     usr_actu = _usr, 
     fch_actu = NOW() 
   WHERE t02_cod_proy = _proy
     AND t02_anio = _anio
     AND t02_entregable = _entregable;
     
    SELECT ROW_COUNT() AS numrows;
END$$

DELIMITER ;







