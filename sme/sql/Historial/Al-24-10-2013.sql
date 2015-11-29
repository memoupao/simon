/**********************************************
   sql-21102013-1528.sql
 **********************************************/
/*
Actualizaciones para mantenimiento de tasas y lineas:
*/
##################################################### >
# AQ 2.0 [21-10-2013 17:55]
# Cambio a términos singulares.
#
DROP TABLE IF EXISTS `t00_tasa`;
CREATE TABLE `t00_tasa` (                                         
             `t00_cod_tasa` int(11) NOT NULL COMMENT 'Codigo de Tasa',        
             `t00_nom_abre` varchar(15) NOT NULL COMMENT 'Nombre Abreviado',  
             `t00_nom_lar` varchar(50) NOT NULL COMMENT 'Nombre Largo',       
             `usr_crea` varchar(20) DEFAULT NULL,                             
             `fch_crea` datetime DEFAULT NULL,                                
             `usr_actu` varchar(20) DEFAULT NULL,                             
             `fch_actu` datetime DEFAULT NULL,                                
             `est_audi` char(1) DEFAULT NULL,                                 
             PRIMARY KEY (`t00_cod_tasa`)                                     
           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `t00_linea`;           
CREATE TABLE `t00_linea` (                                         
             `t00_cod_linea` int(11) NOT NULL COMMENT 'Codigo de Linea',        
             `t00_nom_abre` varchar(15) NOT NULL COMMENT 'Nombre Abreviado',  
             `t00_nom_lar` varchar(50) NOT NULL COMMENT 'Nombre Largo',       
             `usr_crea` varchar(20) DEFAULT NULL,                             
             `fch_crea` datetime DEFAULT NULL,                                
             `usr_actu` varchar(20) DEFAULT NULL,                             
             `fch_actu` datetime DEFAULT NULL,                                
             `est_audi` char(1) DEFAULT NULL,                                 
             PRIMARY KEY (`t00_cod_linea`)                                     
           ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Procedure Information For - sgp.sp_sel_concepto*/
DROP PROCEDURE IF EXISTS sp_sel_concepto;

DELIMITER $$
CREATE PROCEDURE sp_sel_concepto(IN in_concepto VARCHAR(30))
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_linea AS numero
            FROM t00_linea
            ORDER BY t00_nom_lar ASC;
        WHEN 'tasa' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_tasa AS numero
            FROM t00_tasa
            ORDER BY t00_nom_lar ASC;
        WHEN 'banco' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_bco AS numero
            FROM t00_bancos 
            ORDER BY t00_nom_lar ASC;
        WHEN 'moneda' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_mon AS numero
            FROM t00_tipo_moneda 
            ORDER BY t00_nom_abre ASC;
        WHEN 'tipo_anexo' THEN 
           SELECT 
                t02_anx_tip_desc AS nombre, 
                t02_anx_tip_abrev AS abreviatura, 
                t02_anx_tip_cod AS numero
            FROM t02_proy_anx_tip 
            ORDER BY t02_anx_tip_desc ASC;
        WHEN 'tipo_cuenta' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_cta AS numero
            FROM t00_tipo_cuenta 
            ORDER BY t00_nom_abre ASC;
    END CASE;
END $$
DELIMITER ;

/*Procedure Information For - sgp.sp_ins_concepto*/
DROP PROCEDURE IF EXISTS sp_ins_concepto;

DELIMITER $$
CREATE PROCEDURE sp_ins_concepto(IN in_concepto VARCHAR(30), IN in_nom VARCHAR(50), IN in_abre VARCHAR(15), IN in_usr VARCHAR(20))
BEGIN
    DECLARE var_id INT;
    
    CASE in_concepto
        WHEN 'linea' THEN 
            SELECT IFNULL(max(t00_cod_linea),0) + 1 INTO var_id FROM t00_linea;
            INSERT INTO t00_linea (t00_cod_linea, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'tasa' THEN 
            SELECT IFNULL(max(t00_cod_tasa),0) + 1 INTO var_id FROM t00_tasa;
            INSERT INTO t00_tasa (t00_cod_tasa, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'banco' THEN 
            SELECT IFNULL(max(t00_cod_bco),0) + 1 INTO var_id FROM t00_bancos;
            INSERT INTO t00_bancos (t00_cod_bco, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'moneda' THEN 
            SELECT IFNULL(max(t00_cod_mon),0) + 1 INTO var_id FROM t00_tipo_moneda;
            INSERT INTO t00_tipo_moneda (t00_cod_mon, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'tipo_anexo' THEN 
            SELECT IFNULL(max(t02_anx_tip_cod),0) + 1 INTO var_id FROM t02_proy_anx_tip;
            INSERT INTO t02_proy_anx_tip (t02_anx_tip_cod, t02_anx_tip_desc, t02_anx_tip_abrev, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'tipo_cuenta' THEN 
            SELECT IFNULL(max(t00_cod_cta),0) + 1 INTO var_id FROM t00_tipo_cuenta;
            INSERT INTO t00_tipo_cuenta (t00_cod_cta, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
    END CASE;

    SELECT ROW_COUNT() AS numrows, var_id AS codigo;
END $$
DELIMITER ;


/*Procedure Information For - sgp.sp_get_concepto*/
DROP PROCEDURE IF EXISTS sp_get_concepto;

DELIMITER $$
CREATE PROCEDURE sp_get_concepto (IN in_concepto VARCHAR(30), IN in_numero INT)
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_linea AS numero
            FROM t00_linea
            WHERE t00_cod_linea = in_numero;
        WHEN 'tasa' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_tasa AS numero
            FROM t00_tasa
            WHERE t00_cod_tasa = in_numero;
        WHEN 'banco' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_bco AS numero
            FROM t00_bancos
            WHERE t00_cod_bco = in_numero;
        WHEN 'moneda' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_mon AS numero
            FROM t00_tipo_moneda 
            WHERE t00_cod_mon = in_numero;
        WHEN 'tipo_anexo' THEN 
            SELECT 
                t02_anx_tip_desc AS nombre, 
                t02_anx_tip_abrev AS abreviatura, 
                t02_anx_tip_cod AS numero
            FROM t02_proy_anx_tip
            WHERE t02_anx_tip_cod = in_numero;
        WHEN 'tipo_cuenta' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_cta AS numero
            FROM t00_tipo_cuenta 
            WHERE t00_cod_cta = in_numero;
    END CASE;
END $$
DELIMITER ;



/*Procedure Information For - sgp.sp_upd_concepto*/
DROP PROCEDURE IF EXISTS sp_upd_concepto;

DELIMITER $$
CREATE PROCEDURE sp_upd_concepto(IN in_concepto VARCHAR(30), IN in_id int, IN in_nom VARCHAR(50), IN in_abre VARCHAR(15), IN in_usr VARCHAR(20))
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            UPDATE t00_linea   
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_linea = in_id;
        WHEN 'tasa' THEN 
            UPDATE t00_tasa
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_tasa = in_id;
        WHEN 'banco' THEN 
            UPDATE t00_bancos 
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_bco = in_id;
        WHEN 'moneda' THEN 
            UPDATE t00_tipo_moneda 
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_mon = in_id;
        WHEN 'tipo_anexo' THEN 
            UPDATE t02_proy_anx_tip 
            SET t02_anx_tip_desc = in_nom,
                t02_anx_tip_abrev = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t02_anx_tip_cod = in_id;
        WHEN 'tipo_cuenta' THEN 
            UPDATE t00_tipo_cuenta 
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_cta = in_id;
    END CASE;
    
    SELECT ROW_COUNT() AS numrows;
END $$
DELIMITER ;


/*Procedure Information For - sgp.sp_del_concepto*/
DROP PROCEDURE IF EXISTS sp_del_concepto;

DELIMITER $$
CREATE PROCEDURE sp_del_concepto (IN in_concepto VARCHAR(30), IN in_numero INT)
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            DELETE FROM t00_linea WHERE t00_cod_linea = in_numero;
        WHEN 'tasa' THEN 
            DELETE FROM t00_tasa WHERE t00_cod_tasa = in_numero;
        WHEN 'banco' THEN 
            DELETE FROM t00_bancos WHERE t00_cod_bco = in_numero;
        WHEN 'moneda' THEN 
            DELETE FROM t00_tipo_moneda WHERE t00_cod_mon = in_numero;
        WHEN 'tipo_anexo' THEN 
            DELETE FROM t02_proy_anx_tip WHERE t02_anx_tip_cod = in_numero;
        WHEN 'tipo_cuenta' THEN 
            DELETE FROM t00_tipo_cuenta WHERE t00_cod_cta = in_numero;
    END CASE;

    SELECT ROW_COUNT() AS numrows;
END $$
DELIMITER ;

##################################################### <

/**********************************************
   sql-24102013-1510.sql
 **********************************************/
/*
Registro en los menus de nuevos mantenimintos
*/
##################################################### >
# AQ 2.0 [24-10-2013 16:10]
# Datos para las líneas.
#

INSERT INTO `t00_linea` (`t00_cod_linea`, `t00_nom_abre`, `t00_nom_lar`) VALUES
(0, 'Otros', ''),
(1, 'Línea 1', 'Capacitación'),
(2, 'Línea 2', 'Certificación'),
(3, 'Línea 3', 'Emprendimiento'),
(4, 'Línea 4', 'Productivo');
##################################################### <

/**********************************************
   sql-21102013-1553.sql
 **********************************************/
/* No se considera, lo corrije: sql-24102013-1510.sql. */

/**********************************************
   sql-22102013-1734.sql
 **********************************************/
/* Adiciona la columna t00_cod_linea en la tabla t02_dg_proy  */
alter table `t02_dg_proy` add column `t00_cod_linea` int(11) NOT NULL after `t02_nro_exp`;

/* Adiciona el indice fk_t02_dg_proy_t00_cod_linea en la tabla t02_dg_proy  */
alter table `t02_dg_proy` add index `fk_t02_dg_proy_t00_cod_linea` (`t00_cod_linea`);

/* Actualiza los valores de la columna t00_cod_linea por el momento como pruebas con el valor 1 
esto para registra o crear las relaciones */
UPDATE t02_dg_proy SET t00_cod_linea = 1;

/* Nueva relacion de la tabla t02_dg_proy con la tabla t00_linea*/
alter table `t02_dg_proy` add constraint `t02_dg_proy_ibfk_2` FOREIGN KEY (`t00_cod_linea`) REFERENCES `t00_linea` (`t00_cod_linea`) ON DELETE NO ACTION  ON UPDATE CASCADE ;

/* Se adiciono un nuevo campo de t00_cod_linea y parametro _t00_cod_linea en el procedure sp_ins_proyecto */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_proyecto`$$

CREATE PROCEDURE `sp_ins_proyecto`(IN _t02_nro_exp  VARCHAR(20) ,
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
                    IN _t02_sector      INT,
                    IN _t02_subsector   INT,
                    IN _t02_cre_fe      CHAR(1),
                    IN _UserID      VARCHAR(20))
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
      
    SELECT CONCAT('El proyecto esta registrado por el Ejecutor ')
    INTO _msg; 
    SELECT 0 AS numrows,_t02_cod_proy AS codigo, _msg AS msg ;
    
   LEAVE trans1;
  END IF;
   
   
SET AUTOCOMMIT=0;
 START TRANSACTION ;
    
    /*establecer las fechas de inicio y termino para manejo del proyecto*/   
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
    t02_sect_prod, 
    t02_subsect_prod, 
    t02_cre_fe, 
    t02_fch_isc, 
    t02_fch_ire, 
    t02_fch_tre, 
    t02_fch_tam, 
    t02_num_mes, 
    t02_num_mes_amp, 
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
    _t02_sector, 
    _t02_subsector, 
    _t02_cre_fe, 
    _t02_fch_isc, 
    _t02_fch_ire, 
    _t02_fch_tre, 
    _t02_fch_tam, 
    _t02_num_mes, 
    _t02_num_mes_amp,
    _UserID, 
    NOW(), 
    '1'
    );
    INSERT INTO t02_aprob_proy(t02_cod_proy, usu_crea, fch_crea) VALUES (_t02_cod_proy,_UserID, NOW());
    
           
    SELECT ROW_COUNT() INTO _num_rows;
    
    CALL sp_calcula_anios_proy(_t02_cod_proy, _vs);
    
    SELECT COUNT(t02_sector)  INTO   _existe
      FROM t02_sector_prod  
     WHERE t02_cod_proy  = _t02_cod_proy
       AND t02_sector    = _t02_sector
       AND t02_subsec    = _t02_subsector;
    
    IF _existe <=0 THEN
      CALL sp_ins_sector_prod(_t02_cod_proy, _t02_sector, _t02_subsector, 'Principal', _UserID );
    END IF;
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','','','',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','','','',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'','','',_UserID,NOW());
    
    IF _t02_cre_fe='S' THEN 
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'','','',_UserID,NOW());
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/**********************************************
   sql-22102013-2027.txt
 **********************************************/

/* Se adiciono un nuevo campo de t00_cod_linea y parametro _t00_cod_linea en el procedure sp_ins_proyecto */
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
                    IN _sect_prod INT,
                    IN _subsect_prod INT,
                    IN _t02_cre_fe CHAR(1),
                    IN _usr VARCHAR(20))
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
 
    /*establecer las fechas de inicio y termino para manejo del proyecto*/   
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
        t02_sect_prod = _sect_prod , 
        t02_subsect_prod = _subsect_prod , 
        usr_actu = _usr , 
        fch_actu = NOW()                    
    WHERE t02_cod_proy = _cod_proy 
      AND t02_version = _version ;
   
    SELECT ROW_COUNT() INTO _num_rows;
    
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
    
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/**********************************************
   sql-22102013-2027.txt
 **********************************************/
/* Cambio de longitud y comentarios*/
alter table `t00_tasa` change `t00_nom_abre` `t00_nom_abre` varchar(50) character set latin1 collate latin1_swedish_ci NOT NULL comment 'Nombre de Tasa', change `t00_nom_lar` `t00_nom_lar` varchar(20) character set latin1 collate latin1_swedish_ci NOT NULL comment 'Valor de Tasa';

/* actualizacion en los tipos de caracteres*/
alter table `t00_tasa` change `t00_nom_abre` `t00_nom_abre` varchar(50) character set utf8 NOT NULL comment 'Nombre de Tasa', change `t00_nom_lar` `t00_nom_lar` varchar(20) character set utf8 NOT NULL comment 'Valor de Tasa', change `usr_crea` `usr_crea` varchar(20) character set utf8 NULL , change `usr_actu` `usr_actu` varchar(20) character set utf8 NULL , change `est_audi` `est_audi` char(1) character set utf8 NULL ;

/**********************************************
   sql-23102013-2115.sql
 **********************************************/
/* Nueva tabla de tasas para los proyecto */
DROP TABLE IF EXISTS `t02_tasas_proy`;
CREATE TABLE `t02_tasas_proy` (                    
                  `t02_cod_proy` varchar(10) NOT NULL,             
                  `t02_version` int(11) NOT NULL,                  
                  `t02_gratificacion` varchar(10) DEFAULT NULL,    
                  `t02_porc_cts` varchar(10) DEFAULT NULL,         
                  `t02_porc_ess` varchar(10) DEFAULT NULL,         
                  `t02_porc_gast_func` varchar(10) DEFAULT NULL,   
                  `t02_porc_linea_base` varchar(10) DEFAULT NULL,  
                  `t02_porc_imprev` varchar(10) DEFAULT NULL,      
                  `usr_crea` varchar(20) DEFAULT NULL,             
                  `fch_crea` datetime DEFAULT NULL,                
                  `usr_actu` varchar(20) DEFAULT NULL,             
                  `fch_actu` datetime DEFAULT NULL,                
                  `est_audi` char(1) DEFAULT NULL,                 
                  PRIMARY KEY (`t02_cod_proy`,`t02_version`)       
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                

/* Valores de prueba para enlace de tablas */
truncate table `t02_tasas_proy`;
INSERT INTO t02_tasas_proy   SELECT t02_cod_proy, t02_version, 0, 0, 0, 0, 0, 0,usr_crea,fch_crea,usr_actu, fch_actu, est_audi  FROM t02_dg_proy;

/* adicionamos indice a la nueva tabla */
alter table `t02_tasas_proy` drop PRIMARY key, add PRIMARY key (`t02_cod_proy`, `t02_version`);


/* Actualizacion del procedimiento de registro de nuevo proyecto con 
las nuevas tasas */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_proyecto`$$

CREATE  PROCEDURE `sp_ins_proyecto`(IN _t02_nro_exp     VARCHAR(20) ,
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
                    IN _t02_sector      INT,
                    IN _t02_subsector   INT,
                    IN _t02_cre_fe      CHAR(1),

                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),

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
      
    SELECT CONCAT('El proyecto esta registrado por el Ejecutor ')
    INTO _msg; 
    SELECT 0 AS numrows,_t02_cod_proy AS codigo, _msg AS msg ;
    
   LEAVE trans1;
  END IF;
   
   
SET AUTOCOMMIT=0;
 START TRANSACTION ;
    
    /*establecer las fechas de inicio y termino para manejo del proyecto*/   
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
    t02_sect_prod, 
    t02_subsect_prod, 
    t02_cre_fe, 
    t02_fch_isc, 
    t02_fch_ire, 
    t02_fch_tre, 
    t02_fch_tam, 
    t02_num_mes, 
    t02_num_mes_amp, 
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
    _t02_sector, 
    _t02_subsector, 
    _t02_cre_fe, 
    _t02_fch_isc, 
    _t02_fch_ire, 
    _t02_fch_tre, 
    _t02_fch_tam, 
    _t02_num_mes, 
    _t02_num_mes_amp,
    _UserID, 
    NOW(), 
    '1'
    );
    INSERT INTO t02_aprob_proy(t02_cod_proy, usu_crea, fch_crea) VALUES (_t02_cod_proy,_UserID, NOW());
    
    #
    # -------------------------------------------------->
    # DA 2.0 [23-10-2013 20:45]
    # Registramos en las tasas en la tabla t02_tasas_proy: 
    #
    INSERT INTO t02_tasas_proy (t02_cod_proy, t02_version, t02_gratificacion, t02_porc_cts, t02_porc_ess, 
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _UserID, now() );
    # --------------------------------------------------<
    
           
    SELECT ROW_COUNT() INTO _num_rows;
    
    CALL sp_calcula_anios_proy(_t02_cod_proy, _vs);
    
    SELECT COUNT(t02_sector)  INTO   _existe
      FROM t02_sector_prod  
     WHERE t02_cod_proy  = _t02_cod_proy
       AND t02_sector    = _t02_sector
       AND t02_subsec    = _t02_subsector;
    
    IF _existe <=0 THEN
      CALL sp_ins_sector_prod(_t02_cod_proy, _t02_sector, _t02_subsector, 'Principal', _UserID );
    END IF;
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','','','',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','','','',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'','','',_UserID,NOW());
    
    IF _t02_cre_fe='S' THEN 
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'','','',_UserID,NOW());
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;



/* Actualizacion del procedimiento de edicion de un proyecto con 
las nuevas tasas */

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
                    IN _sect_prod INT,
                    IN _subsect_prod INT,
                    IN _t02_cre_fe CHAR(1),

                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),


                    IN _usr VARCHAR(20))
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
 
    /*establecer las fechas de inicio y termino para manejo del proyecto*/   
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
        t02_sect_prod = _sect_prod , 
        t02_subsect_prod = _subsect_prod , 
        usr_actu = _usr , 
        fch_actu = NOW()                    
    WHERE t02_cod_proy = _cod_proy 
      AND t02_version = _version ;

    SELECT ROW_COUNT() INTO _num_rows;

    
    #
    # -------------------------------------------------->
    # DA 2.0 [23-10-2013 20:45]
    # Registramos en las tasas en la tabla t02_tasas_proy: 
    #
    UPDATE t02_tasas_proy SET  
        t02_gratificacion = _t02_gratificacion,
        t02_porc_cts = _t02_porc_cts,
        t02_porc_ess = _t02_porc_ess,
        t02_porc_gast_func = _t02_porc_gast_func, 
        t02_porc_linea_base = _t02_porc_linea_base, 
        t02_porc_imprev = _t02_porc_imprev,
        usr_actu = _usr,
        fch_actu = NOW()
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version;
    # --------------------------------------------------<
    
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
    
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;




/* Actualizacion del procedimiento de seleccion de un proyecto 
con las nuevas tasas */

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_proyecto`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    p.t01_id_inst, 
    i.t01_sig_inst,
    p.t02_nom_proy, 
    DATE_FORMAT(p.t02_fch_apro,'%d/%m/%Y') AS apro, 
    DATE_FORMAT(p.t02_fch_ini,'%d/%m/%Y') AS ini, 
    DATE_FORMAT(p.t02_fch_ter,'%d/%m/%Y') AS fin , 
    p.t02_fin, 
    p.t02_pro, 
    p.t02_ben_obj, 
    p.t02_amb_geo, 
    p.t02_cre_fe,
    p.t02_pres_fe,
    p.t02_pres_eje,
    p.t02_pres_otro, 
    p.t02_pres_tot, 
    p.t02_moni_tema, 
    p.t02_moni_fina, 
    p.t02_moni_ext, 
    p.t02_sup_inst, 
    p.t02_dire_proy, 
    p.t02_ciud_proy, 
    p.t02_tele_proy, 
    p.t02_fax_proy, 
    p.t02_mail_proy,
    DATE_FORMAT((CASE WHEN p.t02_fch_isc =0 THEN NULL ELSE p.t02_fch_isc END),'%d/%m/%Y') AS isc,
    DATE_FORMAT(p.t02_fch_ire,'%d/%m/%Y') AS ire, 
    DATE_FORMAT(p.t02_fch_tre,'%d/%m/%Y') AS tre, 
    DATE_FORMAT((CASE WHEN p.t02_fch_tam =0 THEN NULL ELSE p.t02_fch_tam END),'%d/%m/%Y') AS tam,
    p.t02_num_mes AS mes,
    p.t02_num_mes_amp,
    p.t02_sect_prod,
    p.t02_subsect_prod,
    p.t02_estado, 
    i.t01_web_inst,
    cta.t01_id_cta,
    cta.t02_nom_benef,
    p.usr_crea, 
    p.fch_crea, 
    p.usr_actu, 
    p.fch_actu, 
    p.est_audi,
    p.env_rev,
    apr.t02_vb_proy, 
    apr.t02_aprob_proy, 
    apr.t02_fch_vb_proy, 
    apr.t02_fch_aprob_proy, 
    apr.t02_obs_vb_proy, 
    apr.t02_obs_aprob_proy, 
    apr.t02_aprob_ml, 
    apr.t02_aprob_cro, 
    apr.t02_aprob_pre, 
    apr.t02_fch_ml, 
    apr.t02_fch_cro, 
    apr.t02_fch_pre, 
    apr.t02_aprob_ml_mon, 
    apr.t02_aprob_cro_mon, 
    apr.t02_aprob_pre_mon, 
    apr.t02_obs_ml, 
    apr.t02_obs_cro, 
    apr.t02_obs_pre, 
    apr.t02_fch_ml_mon, 
    apr.t02_fch_cro_mon, 
    apr.t02_fch_pre_mon,
    
    tp.t02_gratificacion,
    tp.t02_porc_cts,
    tp.t02_porc_ess,
    tp.t02_porc_gast_func,
    tp.t02_porc_linea_base,
    tp.t02_porc_imprev
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;



/* Actualizacion del procedimiento de Eliminar proyecto con
 respecto a la tabla de tasas */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_del_proy`$$

CREATE  PROCEDURE `sp_del_proy`(IN _proy VARCHAR(10), IN _vs INT)
BEGIN
declare numrows int;
DELETE from t09_act WHERE t09_act.t02_cod_proy = _proy;
DELETE from t08_comp WHERE t08_comp.t02_cod_proy = _proy;
DELETE from t02_dg_proy WHERE t02_dg_proy.t02_cod_proy = _proy AND t02_dg_proy.t02_version=_vs;
DELETE from t02_fuente_finan WHERE t02_fuente_finan.t02_cod_proy = _proy;
DELETE from t02_aprob_proy WHERE t02_aprob_proy.t02_cod_proy = _proy;
DELETE from t02_sector_prod WHERE t02_sector_prod.t02_cod_proy = _proy;
#
# -------------------------------------------------->
# DA 2.0 [23-10-2013 21:14]
# Eliminamos los items registrados en la tabla t02_tasas_proy: 
#
DELETE from t02_tasas_proy WHERE t02_tasas_proy.t02_cod_proy = _proy AND t02_tasas_proy.t02_version=_vs;
# --------------------------------------------------<

set numrows = 1;
SELECT numrows;
END$$

DELIMITER ;

/**********************************************
   sql-24102013-1510.sql
 **********************************************/
/*
Registro en los menus de nuevos mantenimintos
*/
##################################################### >
# AQ 2.0 [21-10-2013 17:42]
# Cambio de url a términos singulares.
# Ordenamiento alfabético de las nuevas opciones.
#
DELETE FROM adm_menus WHERE mnu_cod IN ('MNU92500', 'MNU92600', 'MNU92300', 'MNU92400', 'MNU92700', 'MNU92800');

INSERT INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU92300', 'Bancos', 1, '/sgp/Admin/man_concepto.php?item=banco', '_self', 'MNU91000', 1, '', 4, '', '1'),
('MNU92400', 'Monedas', 1, '/sgp/Admin/man_concepto.php?item=moneda', '_self', 'MNU91000', 1, '', 5, '', '1'),
('MNU92500', 'Tasas', 1, '/sgp/Admin/man_concepto.php?item=tasa', '_self', 'MNU91000', 1, '', 7, '', '1'),
('MNU92600', 'Lineas', 1, '/sgp/Admin/man_concepto.php?item=linea', '_self', 'MNU91000', 1, '', 6, '', '1'),
('MNU92700', 'Tipos de Cuenta', 1, '/sgp/Admin/man_concepto.php?item=tipo_cuenta', '_self', 'MNU91000', 1, '', 9, '', '1'),
('MNU92800', 'Tipos de Anexo', 1, '/sgp/Admin/man_concepto.php?item=tipo_anexo', '_self', 'MNU91000', 1, '', 8, '', '1');
##################################################### <