-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-002: Informe de Entregable - Registro de Introduccion
-- --------------------------------------------------------------------------------

ALTER TABLE `t25_inf_entregable` ADD COLUMN `intro_gp` TEXT NULL  AFTER `obs_gp` ;



DROP procedure IF EXISTS `sp_ins_inf_entregable_caratula`;

DELIMITER $$
CREATE PROCEDURE `sp_ins_inf_entregable_caratula`(IN _proy VARCHAR(10), 
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME, 
            IN _estado INT,
            IN _usr VARCHAR(20), 
            IN _obs_gp TEXT,
	    IN _intro_gp TEXT)
BEGIN
    DECLARE _msg VARCHAR(100);
    DECLARE _mesactual INT;
    DECLARE _mesanterior INT;
    DECLARE _anio_ant INT;
    DECLARE _mes_ant INT;
    DECLARE _aprobado INT DEFAULT 0;
    DECLARE _existe INT;
    DECLARE _num_informes_ant INT DEFAULT 0;
    
    
    SELECT IFNULL(COUNT(t02_version),0) 
    INTO _existe 
    FROM t25_inf_entregable 
    WHERE t02_cod_proy = _proy AND t25_anio = _anio
    AND t25_entregable = _entregable
    AND t02_version = _ver;
      
    IF _existe > 0 THEN
        SET _msg = CONCAT('Ya fue registrado el Informe de Entregable seleccionado');
        SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
    ELSE
        
        SELECT IFNULL(t02_estado,0) INTO _existe FROM t02_poa WHERE t02_cod_proy = _proy AND t02_anio = _anio;
		  
		IF _existe <> 257 THEN
            SET _msg = CONCAT('El POA de este año no está aprobado');
            SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
		ELSE
            SELECT COUNT(*) 
		    INTO _num_informes_ant
		    FROM t20_inf_mes
		    WHERE t02_cod_proy = _proy 
		    AND t02_version = _ver
		    AND t20_anio = _anio
		    AND t20_mes < _entregable
		    AND t20_estado <> 135;
		    
            IF (_num_informes_ant > 0) THEN
                SET _msg = CONCAT('Existe al menos un Informe Técnico que todavía no tiene el VB del Gestor de Proyecto.');
                SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
	        ELSE
                SELECT COUNT(*) 
				INTO _num_informes_ant
				FROM t40_inf_financ
				WHERE t02_cod_proy = _proy 
				AND t40_anio = _anio
				AND t40_mes < _entregable
				AND t40_est_eje <> 135;
	            
	            IF (_num_informes_ant > 0) THEN
	                SET _msg = CONCAT('Existe al menos un Informe Financiero que todavía no está terminado.');
	                SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
	            ELSE
                   INSERT INTO t25_inf_entregable
	                (t02_cod_proy, 
	                t25_anio, 
	                t25_entregable, 
	                t25_fch_pre, 
	                t25_periodo,
	                t25_estado,  
	                t25_resulta, 
	                t25_conclu,
	                t25_limita,
	                t25_fac_pos,
	                t25_perspec,
	                t02_version, 
	                t25_vb,
	                t25_vb_fch,
	                usr_crea, 
	                fch_crea, 
	                est_audi, 
	                obs_gp,
			intro_gp
	                )
	                VALUES
	                (_proy, 
	                _anio, 
	                _entregable, 
	                _fchpres, 
	                _periodo, 
	                _estado, 
	                NULL,
	                NULL, 
	                NULL,
	                NULL,
	                NULL,
	                _ver, 
	                0,
	                NULL,
	                _usr, 
	                NOW(), 
	                '1',
	                _obs_gp,
			_intro_gp
	                );
	            
	                SELECT ROW_COUNT() AS numrows, _ver AS codigo;
                END IF;
	        END IF;
		END IF;
    END IF;
END$$

DELIMITER ;












DROP procedure IF EXISTS `sp_upd_inf_entregable_cab`;

DELIMITER $$
CREATE PROCEDURE `sp_upd_inf_entregable_cab`(IN _proy varchar(10), 
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME, 
            IN _estado INT,
            IN _usr VARCHAR(20),
            IN _obs_gp TEXT, 
	    IN _intro_gp TEXT, 
            IN _vb_se INT)
BEGIN
    UPDATE t25_inf_entregable 
    SET t25_fch_pre = _fchpres, 
        t25_periodo = _periodo, 
        t25_estado  = _estado, 
        usr_actu = _usr, 
        fch_actu = now(),
        obs_gp = _obs_gp,
	intro_gp = _intro_gp,
        vb_se = _vb_se
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
    
    SELECT ROW_COUNT() as numrows, _ver as codigo;
END$$

DELIMITER ;


