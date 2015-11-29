/*
// -------------------------------------------------->
// AQ 2.0 [15-12-2013 12:30]
// Cambio de Costos Indirectos según las Tasas
// --------------------------------------------------<
*/
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_proyecto`$$

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
        fch_actu = NOW()                    
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
        SET t02_estado = 41 /* En Ejecución */
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
        SET t02_estado = 40  /* Por Iniciar */
        WHERE t02_cod_proy = _cod_proy 
        AND t02_version = _version;
    END IF;
    
    /*
	// -------------------------------------------------->
	// AQ 2.0 [13-12-2013 09:51]
	// Cambio de Línea Base y Gastos de Supervisión 
	// según las Tasas ya que dependen directamente
	// de ellas.
	*/
    SET _aporteFE = fn_total_aporte_fuentes_financ3(_cod_proy, _version , 10);
    
    UPDATE t03_mp_linea_base 
    SET t03_monto = ((_aporteFE * _t02_porc_linea_base) / 100)
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version; 
   
    UPDATE t03_mp_gas_supervision 
    SET t03_monto = ((_aporteFE * _t02_proc_gast_superv) / 100)
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version;
    
    call sp_upd_proyecto_costos(_cod_proy, _version);
    -- --------------------------------------------------<
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [15-12-2013 12:30]
// Se agrega Gastos de Supervisión
// --------------------------------------------------<
*/
ALTER TABLE `t02_dg_proy` ADD `t02_mto_supervision` DOUBLE NULL DEFAULT NULL AFTER `t02_mto_imprevisto`;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_proyecto_costos`$$

CREATE PROCEDURE `sp_upd_proyecto_costos`(IN _proy varchar(10), in _ver INT )
BEGIN
	update t02_dg_proy
	set    t02_mto_line_base = (SELECT IFNULL(SUM(t03_monto),0) 
	                              FROM t03_mp_linea_base
	                             WHERE t02_cod_proy = _proy AND t02_version = _ver ) ,
	       t02_mto_imprevisto = (SELECT IFNULL(SUM(t03_monto),0) 
	                               FROM t03_mp_imprevistos
	                              WHERE t02_cod_proy = _proy AND t02_version = _ver),
	       t02_pres_tot = fn_costos_total_proyecto(_proy, _ver),
	       t02_pres_fe = fn_costo_presupuesto_fe(_proy, _ver),
	       t02_pres_otro = fn_costo_presupuesto_otros(_proy, _ver),
	       t02_mto_supervision = (SELECT IFNULL(SUM(t03_monto),0) 
                                   FROM t03_mp_gas_supervision
                                  WHERE t02_cod_proy = _proy AND t02_version = _ver)
	where t02_cod_proy=_proy
	  and t02_version =_ver;
END $$

DELIMITER ;