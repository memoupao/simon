
/* Actualizacion de perfiles y valores de estado del proyecto */

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_sel_proy_est`$$

CREATE PROCEDURE `sp_sel_proy_est`(IN _user VARCHAR(20))
BEGIN  
/*DECLARE _estado_suspendido INT DEFAULT 43 ; 
DECLARE _estado_cancelado INT DEFAULT 44 ; 
DECLARE _estado_cerrado INT DEFAULT 187 ; */

DECLARE _estado_suspendido INT DEFAULT 42 ; 
DECLARE _estado_cancelado INT DEFAULT 43 ; 
DECLARE _estado_cerrado INT DEFAULT 44 ; 

DECLARE _tipouser INT ;
DECLARE _inst VARCHAR(10);
DECLARE _proy VARCHAR(10);
SELECT tipo_user, IFNULL(t01_id_uni,'*'), IFNULL(t02_cod_proy,'*')
INTO  _tipouser, _inst, _proy
FROM adm_usuarios
WHERE coduser = _user ;
## IF _tipouser = 7 THEN 
IF _tipouser = 16 THEN 
SELECT
	inst.t01_sig_inst AS ejecutor,
	proy.t02_cod_proy AS codigo,
	proy.t02_nro_exp AS 'exp', 
	proy.t02_version AS vs,
	proy.t02_nom_proy AS nombre, 
	DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS inicio, 
	DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') AS termino, 
	proy.t01_id_inst, 
	inst.t01_nom_inst AS nomejecutor,
	proy.usr_crea,
	proy.t02_estado
FROM	  t02_dg_proy proy
LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
WHERE proy.t02_version = fn_ult_version_proy(proy.t02_cod_proy)
  AND proy.t02_estado NOT IN (_estado_suspendido, _estado_cancelado, _estado_cerrado)
  AND proy.t02_moni_ext = (CASE WHEN '*' = _inst THEN proy.t02_moni_ext ELSE _inst END ) 
  AND proy.t02_cod_proy = (CASE WHEN '*' = _proy THEN proy.t02_cod_proy ELSE _proy END ) ;
END IF ;
## IF _tipouser = 8 THEN 
IF _tipouser = 13 THEN 
SELECT
	inst.t01_sig_inst AS ejecutor,
	proy.t02_cod_proy AS codigo,
	proy.t02_nro_exp AS 'exp', 
	proy.t02_version AS vs,
	proy.t02_nom_proy AS nombre, 
	DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS inicio, 
	DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') AS termino, 
	proy.t01_id_inst, 
	inst.t01_nom_inst AS nomejecutor,
	proy.usr_crea,
	proy.t02_estado
FROM	  t02_dg_proy proy
LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
WHERE proy.t02_version = fn_ult_version_proy(proy.t02_cod_proy)
  AND proy.t02_estado NOT IN (_estado_suspendido, _estado_cancelado, _estado_cerrado)
  AND proy.t02_moni_fina = (CASE WHEN '*' = _inst THEN proy.t02_moni_fina ELSE _inst END ) 
  AND proy.t02_cod_proy = (CASE WHEN '*' = _proy THEN proy.t02_cod_proy ELSE _proy END ) ;
END IF ;
/*IF _tipouser = 6 THEN 
SELECT
	inst.t01_sig_inst AS ejecutor,
	proy.t02_cod_proy AS codigo,
	proy.t02_nro_exp AS 'exp', 
	proy.t02_version AS vs,
	proy.t02_nom_proy AS nombre, 
	DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS inicio, 
	DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') AS termino, 
	proy.t01_id_inst, 
	inst.t01_nom_inst AS nomejecutor,
	proy.usr_crea,
	proy.t02_estado
FROM	  t02_dg_proy proy
LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
WHERE proy.t02_version = fn_ult_version_proy(proy.t02_cod_proy)
  AND proy.t02_estado NOT IN (_estado_suspendido, _estado_cancelado, _estado_cerrado)
  AND proy.t02_moni_tema = (CASE WHEN '*' = _inst THEN proy.t02_moni_tema ELSE _inst END ) 
  AND proy.t02_cod_proy = (CASE WHEN '*' = _proy THEN proy.t02_cod_proy ELSE _proy END ) ;
END IF ;
*/
IF _tipouser = 2 THEN 
SELECT
	inst.t01_sig_inst AS ejecutor,
	proy.t02_cod_proy AS codigo,
	proy.t02_nro_exp AS 'exp', 
	proy.t02_version AS vs,
	proy.t02_nom_proy AS nombre, 
	DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS inicio, 
	DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') AS termino, 
	proy.t01_id_inst, 
	inst.t01_nom_inst AS nomejecutor,
	proy.usr_crea,
	apr.t02_aprob_ml,
	apr.t02_aprob_cro,
	apr.t02_aprob_pre,
	proy.t02_estado
FROM	  t02_dg_proy proy
LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
LEFT JOIN t02_aprob_proy apr ON (proy.t02_cod_proy= apr.t02_cod_proy)
WHERE proy.t02_version = fn_ult_version_proy(proy.t02_cod_proy)
  AND proy.t02_estado NOT IN (_estado_suspendido, _estado_cancelado, _estado_cerrado)
  AND proy.t01_id_inst = (CASE WHEN '*' = _inst THEN proy.t01_id_inst ELSE _inst END ) 
  AND proy.t02_cod_proy = (CASE WHEN '*' = _proy THEN proy.t02_cod_proy ELSE _proy END ) ;
END IF ;
## IF _tipouser <> 2 AND _tipouser <> 6 AND _tipouser <> 7 AND _tipouser <> 8 THEN 
IF _tipouser <> 2 AND _tipouser <> 13 AND _tipouser <> 16  THEN 
SELECT
	inst.t01_sig_inst AS ejecutor,
	proy.t02_cod_proy AS codigo,
	proy.t02_nro_exp AS 'exp', 
	proy.t02_version AS vs,
	proy.t02_nom_proy AS nombre, 
	DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS inicio, 
	DATE_FORMAT(proy.t02_fch_ter,'%d/%m/%Y') AS termino, 
	proy.t01_id_inst, 
	inst.t01_nom_inst AS nomejecutor,
	proy.usr_crea,
	proy.t02_estado
FROM	  t02_dg_proy proy
LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
WHERE proy.t02_version = fn_ult_version_proy(proy.t02_cod_proy)
  AND proy.t02_estado NOT IN (_estado_suspendido, _estado_cancelado, _estado_cerrado)
  AND proy.t02_cod_proy = (CASE WHEN '*' = _proy THEN proy.t02_cod_proy ELSE _proy END ) ;
END IF ;
END$$

DELIMITER ;


/* Nuevo Cargo y Unidad de GP: */

insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('300','Gestor de Proyectos','GP ','','1','7','1','ad_fondoe','2013-11-24',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('301','Gestor de Proyectos','GP ','','1','8','1','ad_fondoe','2013-11-24',NULL,NULL);


UPDATE t90_equi_fe SET t90_unid_fe=300, t90_carg_equi=301 WHERE t90_id_equi IN (1,2,3,4,5,6);

alter table `t02_dg_proy` drop column `t01_id_cta`;




/* Correccion en la validacion si es aprobado o no.*/
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_proyecto`$$

CREATE  PROCEDURE `sp_upd_proyecto`( IN _version INT,
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
            IN _t02_sect_main INT,
                    IN _sect_prod INT,
                    IN _subsect_prod INT,
            IN _t02_prod_promovido  VARCHAR(200),
                    IN _t02_cre_fe CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
                    IN _t02_proc_gast_superv     VARCHAR(10),
                    IN _usr VARCHAR(20),
                    IN _aprobado TINYINT)
BEGIN
    DECLARE _msg        VARCHAR(100);
    DECLARE _existe     INT ;
    DECLARE _fch_ini    DATE;
    DECLARE _fch_ter    DATE;
    DECLARE _num_rows   INT ;  
    DECLARE _numrows    INT ; 
    DECLARE _fcredito   INT DEFAULT 200;
 
 
SET AUTOCOMMIT=0;
 START TRANSACTION ;
 
       
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
    t02_sect_main = _t02_sect_main,
        t02_sect_prod = _sect_prod , 
        t02_subsect_prod = _subsect_prod , 
    t02_prod_promovido = _t02_prod_promovido, 
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
    
    SELECT COUNT(t02_sector)  INTO   _existe
      FROM t02_sector_prod  
     WHERE t02_cod_proy  = _cod_proy
       AND t02_sector    = _sect_prod
       AND t02_subsec    = _subsect_prod;
    
    IF _existe <=0 THEN
      CALL sp_ins_sector_prod(_cod_proy, _sect_prod, _subsect_prod, 'Principal', _usr );
    END IF;
    
    IF _t02_cre_fe='S' THEN 
      CALL sp_ins_fte_fin(_cod_proy,_fcredito,'','','',_usr,NOW());
    END IF;
    
    
    
    
    
    
    #IF _aprobado IS NOT NULL THEN
    IF _aprobado = 1 THEN
        REPLACE INTO t02_aprob_proy 
            (t02_cod_proy, t02_aprob_proy, t02_fch_aprob_proy, 
            t02_vb_proy, t02_fch_vb_proy, usu_crea, fch_crea)
        VALUES(_cod_proy, 1, NOW(), 1, NOW(), _usr, NOW() );
        
        UPDATE t02_dg_proy 
        SET t02_estado = 41
        WHERE t02_cod_proy = _cod_proy 
        AND t02_version = _version;
    ELSE
        REPLACE INTO t02_aprob_proy 
            (t02_cod_proy, t02_aprob_proy, t02_fch_aprob_proy, 
            t02_vb_proy, t02_fch_vb_proy, usu_crea, fch_crea)
        VALUES(_cod_proy, NULL, NULL, NULL, NULL, _usr, NOW() );
        
        UPDATE t02_dg_proy 
        SET t02_estado = 40
        WHERE t02_cod_proy = _cod_proy 
        AND t02_version = _version;
    END IF;
    
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;


