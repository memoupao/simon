-- --------------------------------------------------------------------------------
-- DA, v2.1
-- RF-002 - Informe de Entregable - Registro de Introducción - Correcciones
-- --------------------------------------------------------------------------------

DROP procedure IF EXISTS `sp_upd_inf_entregable_cambio_estado`;

DELIMITER $$
CREATE PROCEDURE `sp_upd_inf_entregable_cambio_estado`(
            IN _proy VARCHAR(10),
            IN _ver INT,
            IN _anio INT,
            IN _entregable INT, 
            IN _estado INT,
            IN _obs_gp TEXT,
	    IN _intro_gp TEXT)
BEGIN
 UPDATE t25_inf_entregable 
  SET   t25_estado  = _estado,
        t25_fch_pre = (CASE WHEN _estado=46 THEN NOW() ELSE t25_fch_pre END),
        obs_gp = _obs_gp, 
	intro_gp = _intro_gp
  WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
     
  SELECT ROW_COUNT() AS numrows , '' AS msg;
     
END$$

DELIMITER ;






