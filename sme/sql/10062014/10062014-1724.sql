-- --------------------------------------------------------------------------------
-- AQ, V2.1
-- Fixed: Incidencia #227
-- --------------------------------------------------------------------------------
DROP procedure IF EXISTS `sp_ins_proyecto`;

DELIMITER $$

CREATE PROCEDURE `sp_ins_proyecto`(IN _t02_nro_exp     VARCHAR(20) ,
                    IN _t00_cod_linea   INT,
                    IN _t01_id_inst     INT,            
                    IN _t02_cod_proy    VARCHAR(10),
                    IN _t02_nom_proy    VARCHAR(250),
                    IN _t02_fch_apro    DATE,
                    IN _t02_estado      INT ,
                    IN _t02_fin     TEXT,
                    IN _t02_pro     TEXT,
                    IN _t02_ben_obj     VARCHAR(5000),
                    IN _t02_amb_geo     VARCHAR(5000),
                    IN _t02_moni_tema   INT,
                    IN _t02_moni_fina   INT,
                    IN _t02_moni_ext    VARCHAR(10),                    
                    IN _t02_sup_inst    INT,
                    IN _t02_dire_proy   VARCHAR(200),
                    IN _t02_ciud_proy   VARCHAR(50),
                    IN _t02_tele_proy    VARCHAR(30),
                    IN _t02_fax_proy    VARCHAR(30),
                    IN _t02_mail_proy   VARCHAR(50),
                    IN _t02_fch_isc     DATE,
                    IN _t02_fch_ire     DATE,
                    IN _t02_fch_tre     DATE,
                    IN _t02_fch_tam     DATE,   
                    IN _t02_num_mes     INT,
                    IN _t02_num_mes_amp INT,
                    
                    IN _t02_cre_fe      CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
					IN _t02_proc_gast_superv     VARCHAR(10),           
					IN _t02_inst_asoc     TEXT,
                    IN _UserID      VARCHAR(20) )
trans1 : BEGIN                  
    DECLARE _msg        VARCHAR(100);
    DECLARE _existe     INT ;
    DECLARE _vs         INT DEFAULT 1;
    DECLARE _t02_fch_ini    DATE;
    DECLARE _fecha_ter  DATE;
    DECLARE _num_rows   INT ;
    DECLARE _numrows    INT ;
    DECLARE _fcredito   INT DEFAULT 200;
    SELECT COUNT(1)
    INTO _existe
    FROM t02_dg_proy
    WHERE t02_cod_proy=_t02_cod_proy AND t01_id_inst=_t01_id_inst;
    
  IF _existe > 0 THEN
      
    SELECT CONCAT('El proyecto esta registrado por el Supervidor de Proyectos ')
    INTO _msg; 
    SELECT 0 AS numrows,_t02_cod_proy AS codigo, _msg AS msg ;
    
   LEAVE trans1;
  END IF;
   
   
SET AUTOCOMMIT=0;
 START TRANSACTION ;
    
       
    SELECT _t02_fch_ire, (CASE WHEN IFNULL(DATEDIFF(_t02_fch_tre, _t02_fch_tam),0) <> 0 THEN _t02_fch_tam ELSE _t02_fch_tre END)
    INTO _t02_fch_ini, _fecha_ter;
   
   INSERT INTO t02_dg_proy 
    (
    t02_cod_proy, 
    t02_version, 
    t02_nro_exp,
    t00_cod_linea,
    t01_id_inst,    
    t02_nom_proy, 
    t02_fch_apro, 
    t02_fch_ini, 
    t02_fch_ter, 
    t02_fin, 
    t02_pro, 
    t02_ben_obj, 
    t02_amb_geo, 
    t02_moni_tema, 
    t02_moni_fina, 
    t02_moni_ext, 
    t02_sup_inst, 
    t02_dire_proy, 
    t02_ciud_proy, 
    t02_tele_proy, 
    t02_fax_proy, 
    t02_mail_proy, 
    t02_estado, 
    
    t02_cre_fe, 
    t02_fch_isc, 
    t02_fch_ire, 
    t02_fch_tre, 
    t02_fch_tam, 
    t02_num_mes, 
    t02_num_mes_amp,
	t02_inst_asoc,
    usr_crea, 
    fch_crea, 
    est_audi
    )
    VALUES
    (
    _t02_cod_proy, 
    _vs, 
    _t02_nro_exp,
    _t00_cod_linea,
    _t01_id_inst,
    _t02_nom_proy, 
    _t02_fch_apro, 
    _t02_fch_ini, 
    _fecha_ter, 
    _t02_fin, 
    _t02_pro, 
    _t02_ben_obj, 
    _t02_amb_geo, 
    _t02_moni_tema, 
    _t02_moni_fina, 
    _t02_moni_ext, 
    _t02_sup_inst, 
    _t02_dire_proy, 
    _t02_ciud_proy, 
    _t02_tele_proy, 
    _t02_fax_proy, 
    _t02_mail_proy, 
    _t02_estado, 
    
    _t02_cre_fe, 
    _t02_fch_isc, 
    _t02_fch_ire, 
    _t02_fch_tre, 
    _t02_fch_tam, 
    _t02_num_mes, 
    _t02_num_mes_amp,
    _t02_inst_asoc,
    _UserID, 
    NOW(), 
    '1'
    );
    INSERT INTO t02_aprob_proy(t02_cod_proy, usu_crea, fch_crea) VALUES (_t02_cod_proy,_UserID, NOW());
    
    INSERT INTO t02_tasas_proy (t02_cod_proy, t02_version, t02_gratificacion, t02_porc_cts, t02_porc_ess, 
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv ,usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _t02_proc_gast_superv, _UserID, now() );
    
    SELECT ROW_COUNT() INTO _num_rows;
    
    CALL sp_calcula_anios_proy(_t02_cod_proy, _vs);
    
   
    
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    
    IF _t02_cre_fe='S' THEN 
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
    END IF; 

    CALL sp_poa_actualiza_presupuesto(_t02_cod_proy, _vs);
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;


DROP procedure IF EXISTS `sp_upd_proyecto`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_proyecto`( IN _version INT,
                    IN _nro_exp VARCHAR(20) ,
                    IN _cod_linea INT,
                    IN _inst INT,
                    IN _cod_proy VARCHAR(10) ,
                    IN _nom_proy VARCHAR(250) ,
                    IN _fch_apro DATE,                                  
                    IN _estado INT,                                 
                    IN _fin TEXT ,
                    IN _pro TEXT,
                    IN _ben_obj VARCHAR(5000) ,
                    IN _amb_geo VARCHAR(5000) ,
                    IN _moni_tema INT,
                    IN _moni_fina INT,
                    IN _moni_ext VARCHAR(10) ,
                    IN _sup_inst INT,
                    IN _dire_proy VARCHAR(200),
                    IN _ciud_proy VARCHAR(50) ,                                 
                    IN _tele_proy VARCHAR(30) ,
                    IN _fax_proy VARCHAR(30) ,
                    IN _mail_proy VARCHAR(50) ,                                 
                    IN _t02_fch_isc  DATE ,
                    IN _t02_fch_ire  DATE ,
                    IN _t02_fch_tre  DATE ,
                    IN _t02_fch_tam  DATE ,
                    IN _t02_num_mes INT,    
                    IN _t02_num_mes_amp INT,                                
                    IN _t02_cre_fe CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
                    IN _t02_proc_gast_superv     VARCHAR(10),
		    IN _t02_inst_asoc TEXT,
                    IN _usr VARCHAR(20),
                    IN _vb TINYINT)
BEGIN
    DECLARE _msg        VARCHAR(100);
    DECLARE _existe     INT ;
    DECLARE _fch_ini    DATE;
    DECLARE _fch_ter    DATE;
    DECLARE _num_rows   INT ;  
    DECLARE _numrows    INT ; 
    DECLARE _fcredito   INT DEFAULT 200;
    DECLARE _aporteFE DOUBLE;
 
SET AUTOCOMMIT=0;
START TRANSACTION;
 
    SELECT _t02_fch_ire, (CASE WHEN IFNULL(DATEDIFF(_t02_fch_tre, _t02_fch_tam),0) <> 0 THEN _t02_fch_tam ELSE _t02_fch_tre END)
    INTO _fch_ini, _fch_ter;
        
    UPDATE t02_dg_proy 
       SET
        t02_nro_exp = _nro_exp , 
        t00_cod_linea = _cod_linea , 
        t01_id_inst = _inst ,   
        t02_nom_proy = _nom_proy , 
        t02_fch_apro = _fch_apro , 
        t02_fch_ini = _fch_ini , 
        t02_fch_ter = _fch_ter , 
        t02_fin     = _fin , 
        t02_pro     = _pro , 
        t02_ben_obj = _ben_obj , 
        t02_amb_geo = _amb_geo ,
        t02_cre_fe  = _t02_cre_fe, 
        t02_moni_tema = _moni_tema , 
        t02_moni_fina = _moni_fina , 
        t02_moni_ext = _moni_ext , 
        t02_sup_inst = _sup_inst , 
        t02_dire_proy = _dire_proy , 
        t02_ciud_proy = _ciud_proy , 
        t02_tele_proy = _tele_proy , 
        t02_fax_proy = _fax_proy , 
        t02_mail_proy = _mail_proy ,        
        t02_fch_isc = _t02_fch_isc ,
        t02_fch_ire = _t02_fch_ire ,
        t02_fch_tre = _t02_fch_tre ,
        t02_fch_tam = _t02_fch_tam ,
        t02_num_mes = _t02_num_mes ,
        t02_num_mes_amp = _t02_num_mes_amp ,
        t02_estado = _estado , 
        usr_actu = _usr , 
        fch_actu = NOW(),
	    t02_inst_asoc = _t02_inst_asoc

    WHERE t02_cod_proy = _cod_proy 
      AND t02_version = _version ;
    SELECT ROW_COUNT() INTO _num_rows;
    
    UPDATE t02_tasas_proy SET  
        t02_gratificacion = _t02_gratificacion,
        t02_porc_cts = _t02_porc_cts,
        t02_porc_ess = _t02_porc_ess,
        t02_porc_gast_func = _t02_porc_gast_func, 
        t02_porc_linea_base = _t02_porc_linea_base, 
        t02_porc_imprev = _t02_porc_imprev,
        t02_proc_gast_superv = _t02_proc_gast_superv,
        usr_actu = _usr,
        fch_actu = NOW()
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version;
    
    CALL sp_calcula_anios_proy(_cod_proy, _version);
    
    IF _t02_cre_fe='S' THEN 
      CALL sp_ins_fte_fin(_cod_proy,_fcredito,'','','',_usr,NOW());
    END IF;
    
    IF _vb = 1 THEN
        UPDATE t02_aprob_proy
        SET t02_cod_proy = _cod_proy, 
	        t02_vb_proy = 1, 
	        t02_fch_vb_proy = NOW(), 
	        usu_crea = _usr, 
	        fch_crea = NOW()
        WHERE t02_cod_proy = _cod_proy;
        
        UPDATE t02_dg_proy 
        SET t02_estado = 41 
        WHERE t02_cod_proy = _cod_proy 
        AND t02_version = _version;
    ELSE
        UPDATE t02_aprob_proy
        SET t02_cod_proy = _cod_proy, 
	        t02_vb_proy = NULL, 
	        t02_fch_vb_proy = NULL, 
	        usu_crea = _usr, 
	        fch_crea = NOW()
        WHERE t02_cod_proy = _cod_proy;
        
        UPDATE t02_dg_proy 
        SET t02_estado = 40  
        WHERE t02_cod_proy = _cod_proy 
        AND t02_version = _version;
    END IF;
    
    
    SET _aporteFE = fn_total_aporte_fuentes_financ3(_cod_proy, _version , 10);
    
    UPDATE t03_mp_linea_base 
    SET t03_monto = ((_aporteFE * _t02_porc_linea_base) / 100)
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version; 
   
    UPDATE t03_mp_gas_supervision 
    SET t03_monto = ((_aporteFE * _t02_proc_gast_superv) / 100)
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version;
    
    call sp_upd_proyecto_costos(_cod_proy, _version);

    CALL sp_poa_actualiza_presupuesto(_cod_proy, _version);
    
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

DROP TRIGGER IF EXISTS trg_upd_proy;