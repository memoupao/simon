-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-005 - Informe de Supervisión - Mostrar el Resumen del Avance Presupuestal
-- --------------------------------------------------------------------------------


ALTER TABLE `t30_inf_se` ADD COLUMN `t30_af_obs_1` TEXT NULL COMMENT 'Obs. encontradas - FE'  AFTER `t30_obs` , ADD COLUMN `t30_af_obs_2` TEXT NULL COMMENT 'Obs. encontradas - IE'  AFTER `t30_af_obs_1` , ADD COLUMN `t30_af_obs_3` TEXT NULL COMMENT 'Obs. encontradas - Inst. Asoc.'  AFTER `t30_af_obs_2` , ADD COLUMN `t30_af_obs_4` TEXT NULL COMMENT 'Obs. encontradas - Inst. Colab.'  AFTER `t30_af_obs_3` , ADD COLUMN `t30_af_obs_5` TEXT NULL COMMENT 'Obs. encontradas - Beneficiarios'  AFTER `t30_af_obs_4` ;



DROP procedure IF EXISTS `sp_upd_inf_se_avfi`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_se_avfi`(
            IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _entregable INT,
            IN _obs1 TEXT, 
            IN _obs2 TEXT , 
            IN _obs3 TEXT,
            IN _obs4 TEXT,
            IN _obs5 TEXT,			
            IN _usr VARCHAR(20))
BEGIN
  UPDATE t30_inf_se 
     SET t30_af_obs_1 = _obs1, 
     t30_af_obs_2 = _obs2, 
     t30_af_obs_3 = _obs3, 
     t30_af_obs_4 = _obs4,
     t30_af_obs_5 = _obs5,
     usr_actu = _usr,
     fch_actu = NOW() 
   WHERE t02_cod_proy = _proy
     AND t02_anio = _anio
     AND t02_entregable = _entregable;

    SELECT ROW_COUNT() AS numrows;
END$$

DELIMITER ;





