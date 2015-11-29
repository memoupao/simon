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
(1, 'Línea 1', 'Capacitación Laboral e Inserción Laboral'),
(2, 'Línea 2', 'Certificación de Competencias Laborales'),
(3, 'Línea 3', 'Fortalecimiento de Emprendimiento Juveniles'),
(4, 'Línea 4', 'Proyectos Productivos Sostenibles');
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
   sql-29102013-1152.sql
 **********************************************/
##################################################### >
# DA 2.0 [29-10-2013 11:51]
# Actualizacion en el procedure sp_get_proyecto, se aumento el campo t00_cod_linea 
# para mostrarse en la edicion o vista previa de datos generales del proyecto.
#
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_proyecto`$$

CREATE  PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    
    p.t00_cod_linea, 

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

##################################################### <

/**********************************************
   sql-29102013-1520.sql
 **********************************************/
/* Registro de nuevos perfiles */
replace into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('12','Supervisor de Proyectos','SP','ad_fondoe','2013-10-28 21:37:02',NULL,NULL);
replace into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('13','Gestor de Proyectos','GP','ad_fondoe','2013-10-28 21:37:43',NULL,NULL);

/* Registro de nuevo usuario para perfil de Supervisor de Proyectos: sptest1 */
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('sptest1','12','Supervisor de proyectos 1','060f56b22231cf8f2c9224911450f215','sptest1@localhost.com','*','','1','ad_fondoe','2013-10-28 21:42:40',NULL,NULL,'1');

/* Registro de permisos al nuevo usuario de  Supervisor de Proyectos */
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','12','1','1','1','0','ad_fondoe','2013-10-28 21:54:43');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','12','1','1','1','0','ad_fondoe','2013-10-28 21:54:43');

/**********************************************
   sql-29102013-1531.sql
 **********************************************/
/* Correccion al momento de registrar o editar en datos generales del proyecto */
alter table `t02_dg_proy` change `t02_pres_eje` `t02_pres_eje` double default '0' NOT NULL comment 'Presupuesto de la Intitucion ejecutora';

/**********************************************
   sql-29102013-2326.sql
 **********************************************/
/* Correccion al momento de registrar o editar en datos generales del proyecto */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_proyecto`$$

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
      
    SELECT CONCAT('El proyecto esta registrado por el Supervidor de Proyectos ')
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
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    # --------------------------------------------------<

    
    IF _t02_cre_fe='S' THEN 
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;


/**********************************************
   sql-30102013-1656.sql
 **********************************************/
  # Nuevo Perfil de Gerente de Gestion de Proyectos.
replace into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('14','Gerente de Gestión de Proyectos','GGP','ad_fondoe','2013-10-30 17:21:44',NULL,NULL);

# Limpia de datos ya registrados (datos de pruebas).
DELETE FROM  adm_menus_perfil where per_cod='12';

# Registro de permisos de perfiles.
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','2','1','1','1','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','2','1','1','1','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU72000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','2','1','1','1','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','4','1','1','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU72000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','4','1','1','1','1','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','7','1','1','1','1','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','12','0','1','0','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU64000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU64000','13','1','1','0','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','13','0','0','0','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU64000','14','1','1','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
replace into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');

# Nuevos usuarios con los nuevos perfiles creados:
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('ggptest1','14','Usuario de Prueba GGP 1','fb72c73629bf38484e741d6de2d0a4bf','ggptest1@localhost.com','*','','1','ad_fondoe','2013-10-30 17:33:07',NULL,NULL,'1');
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('gptest1','13','Usuario de Prueba GP 1','bb3697fa5349938b0a6639d85e627695','gptest1@locahost.com','*','','1','ad_fondoe','2013-10-30 17:34:18',NULL,NULL,'1');
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('ietest1','2','Usuario de Prueba IE 1','16006317e27c660903d0c79fa8b242bd','ietest1@localhost.com','2','','1','ad_fondoe','2013-10-30 17:35:27',NULL,NULL,'1');
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('ratest1','4','Usuario de Prueba RA 1','92b93c36ec6078ef3f8abecc4b0c23c2','ratest1@localhost.com','*','','1','ad_fondoe','2013-10-30 17:36:26',NULL,NULL,'1');
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('setest1','7','Usuario de Prueba SE 1','37fc6a866f73f511ff491b474f33eb03','setest1@localhost.com','2.2','','1','ad_fondoe','2013-10-30 17:38:01',NULL,NULL,'1');

/**********************************************
   sql-31102013-1103.sql
 **********************************************/

/**
 * CticServices
 *
 * Creación de la tabla para la gestión de 
 * las Características de Actividades (Productos)
 *
 * @package     sql
 * @author      AQ
 * @since       Version 2.0
 *
 */

--
-- Estructura de tabla para la tabla `t09_act_car`
--
DROP TABLE IF EXISTS `t09_act_car`;

CREATE TABLE IF NOT EXISTS `t09_act_car` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_act_car` int(11) NOT NULL,
  `t09_ind` varchar(250) DEFAULT NULL,
  `t09_mta` double DEFAULT NULL,
  `t09_fv` varchar(250) DEFAULT NULL,
  `t09_obs` varchar(500) DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_car`),
  KEY `FK_t09_act_car` (`t09_cod_act`,`t08_cod_comp`,`t02_version`,`t02_cod_proy`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t09_act_car`
--
ALTER TABLE `t09_act_car`
  ADD CONSTRAINT `t09_act_car_ibfk_1` FOREIGN KEY (`t09_cod_act`, `t08_cod_comp`, `t02_version`, `t02_cod_proy`) REFERENCES `t09_act` (`t09_cod_act`, `t08_cod_comp`, `t02_version`, `t02_cod_proy`) ON DELETE CASCADE ON UPDATE CASCADE;

/**********************************************
   sql-31102013-1403.sql
 **********************************************/
/**
 * CticServices
 *
 * Creación de la Tabla para la gestión de 
 * los Controles para las Características
 * de Actividades (Productos)
 *
 * @package     sql
 * @author      AQ
 * @since       Version 2.0
 *
 */
--
-- Estructura de tabla para la tabla `t09_act_car_ctrl`
--

DROP TABLE IF EXISTS `t09_act_car_ctrl`;

CREATE TABLE IF NOT EXISTS `t09_act_car_ctrl` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_act_car` int(11) NOT NULL,
  `t09_car_anio` tinyint(4) NOT NULL,
  `t09_car_mes` tinyint(4) NOT NULL,
  `t09_car_ctrl` tinyint(4) DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_car`,`t09_car_anio`,`t09_car_mes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t09_act_car_ctrl`
--
ALTER TABLE `t09_act_car_ctrl`
  ADD CONSTRAINT `t09_act_car_ctrl_ibfk_1` FOREIGN KEY (`t02_cod_proy`, `t02_version`, `t08_cod_comp`, `t09_cod_act`, `t09_cod_act_car`) REFERENCES `t09_act_car` (`t02_cod_proy`, `t02_version`, `t08_cod_comp`, `t09_cod_act`, `t09_cod_act_car`) ON DELETE CASCADE ON UPDATE CASCADE;
  
/**********************************************
   sql-02112013-1602.sql
 **********************************************/
/* actualización de perfil y usuario de responsable de area.*/

insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('15','Responsable de Area','RA','ad_fondoe','2013-11-02 15:54:04',NULL,NULL);
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('16','Supervisor Externo','SE','ad_fondoe','2013-11-02 16:21:51',NULL,NULL);

update `adm_perfiles` set `per_des`='Responsable de Área - DEL',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 15:53:45' where `per_cod`='4';
update `adm_usuarios` set `coduser`='ratest1',`tipo_user`='15',`nom_user`='Usuario de Prueba RA 1',`clave_user`='92b93c36ec6078ef3f8abecc4b0c23c2',`mail`='ratest1@localhost.com',`t01_id_uni`='*',`t02_cod_proy`='',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-10-30 17:36:26',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 15:58:55',`est_audi`='1' where `coduser`='ratest1';

update `adm_perfiles` set `per_cod`='3',`per_des`='Secretario Ejecutivo DEL',`per_abrev`='Sec.Ejec',`usr_crea`='john.aima',`fch_crea`=NULL,`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 16:22:48' where `per_cod`='3';
update `adm_perfiles` set `per_cod`='7',`per_des`='Supervisor Externo - DEL',`per_abrev`='ME',`usr_crea`=NULL,`fch_crea`=NULL,`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 16:19:44' where `per_cod`='7';
update `adm_usuarios` set `coduser`='setest1',`tipo_user`='16',`nom_user`='Usuario de Prueba SE 1',`clave_user`='37fc6a866f73f511ff491b474f33eb03',`mail`='setest1@localhost.com',`t01_id_uni`='*',`t02_cod_proy`='',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-10-30 17:38:01',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 16:25:50',`est_audi`='1' where `coduser`='setest1';

/**********************************************
   sql-04112013-1441.sql
 **********************************************/
/* Considerado en: sql-07112013-1207.sql */

/**********************************************
   sql-07112013-1207.sql
 **********************************************/
/* Considerado en: fix-11-11-2013-1433.sql */
          
/* Procedure para listado */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_sel_concepto`$$

CREATE PROCEDURE `sp_sel_concepto`(IN in_concepto VARCHAR(30))
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
    WHEN 'parametro' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_param AS numero
            FROM t00_parametro
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
END$$

DELIMITER ;

/*  Procedure para seleccion */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_concepto`$$

CREATE PROCEDURE `sp_get_concepto`(IN in_concepto VARCHAR(30), IN in_numero INT)
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
        WHEN 'parametro' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_param AS numero
            FROM t00_parametro
            WHERE t00_cod_param = in_numero;
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
END$$

DELIMITER ;



/* Actualizacion en el Procedure para la eliminacion de parametros */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_concepto`$$

CREATE  PROCEDURE `sp_ins_concepto`(IN in_concepto VARCHAR(30), IN in_nom VARCHAR(50), IN in_abre VARCHAR(15), IN in_usr VARCHAR(20))
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
    WHEN 'parametro' THEN 
            SELECT IFNULL(max(t00_cod_param),0) + 1 INTO var_id FROM t00_parametro;
            INSERT INTO t00_parametro (t00_cod_param, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
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
END$$

DELIMITER ;


/* actualizacion en el procedure para eliminar parametros */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_del_concepto`$$

CREATE PROCEDURE `sp_del_concepto`(IN in_concepto VARCHAR(30), IN in_numero INT)
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            DELETE FROM t00_linea WHERE t00_cod_linea = in_numero;
        WHEN 'tasa' THEN 
            DELETE FROM t00_tasa WHERE t00_cod_tasa = in_numero;
    WHEN 'parametro' THEN 
            DELETE FROM t00_parametro WHERE t00_cod_param = in_numero;
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
END$$

DELIMITER ;



/* actualizacion del procedure para la actualizacion de parametros */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_concepto`$$

CREATE  PROCEDURE `sp_upd_concepto`(IN in_concepto VARCHAR(30), IN in_id int, IN in_nom VARCHAR(50), IN in_abre VARCHAR(15), IN in_usr VARCHAR(20))
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
        WHEN 'parametro' THEN 
            UPDATE t00_parametro
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_param = in_id;
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
END$$

DELIMITER ;

/**********************************************
   sql-07112013-1945.sql
 **********************************************/
DROP TABLE IF EXISTS `t09_act_ind_car`;
DROP TABLE IF EXISTS `t09_act_car_ctrl`;
DROP TABLE IF EXISTS `t09_act_car`;

--
-- Estructura de tabla para la tabla `t09_act_ind_car`
--
CREATE TABLE IF NOT EXISTS `t09_act_ind_car` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_act_ind` int(11) NOT NULL,
  `t09_cod_act_ind_car` int(11) NOT NULL,
  `t09_ind` varchar(250) DEFAULT NULL,
  `t09_mta` double DEFAULT NULL,
  `t09_fv` varchar(250) DEFAULT NULL,
  `t09_obs` varchar(500) DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`),
  KEY `FK_t09_act_ind_car` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Filtros para la tabla `t09_act_ind_car`
--
ALTER TABLE `t09_act_ind_car`
  ADD CONSTRAINT `t09_act_ind_car_ibfk_1` 
  FOREIGN KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`) 
  REFERENCES `t09_act_ind` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`) 
  ON DELETE CASCADE ON UPDATE CASCADE;
  
--
-- Estructura de tabla para la tabla `t09_act_ind_car_ctrl`
--
DROP TABLE IF EXISTS `t09_act_ind_car_ctrl`;

CREATE TABLE IF NOT EXISTS `t09_act_ind_car_ctrl` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_act_ind` int(11) NOT NULL,
  `t09_cod_act_ind_car` int(11) NOT NULL,
  `t09_car_anio` tinyint(4) NOT NULL,
  `t09_car_mes` tinyint(4) NOT NULL,
  `t09_car_ctrl` tinyint(4) DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`,`t09_car_anio`,`t09_car_mes`),
  KEY `FK_t09_act_ind_car_ctrl` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Filtros para la tabla `t09_act_car_ctrl`
--
ALTER TABLE `t09_act_ind_car_ctrl`
  ADD CONSTRAINT `t09_act_ind_car_ctrl_ibfk_1` 
  FOREIGN KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`) 
  REFERENCES `t09_act_ind_car` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`) 
  ON DELETE CASCADE ON UPDATE CASCADE;

/**********************************************
   sql-07112013-2254.sql
 **********************************************/
/* Nueva tabla de Tasas de concursos*/

CREATE TABLE `adm_concursos_tasas` (                                                                     
                       `num_conc` char(2) NOT NULL COMMENT 'Numero del Consurso',                                             
                       `cod_linea` int(11) NOT NULL COMMENT 'Codigo de Linea',                                                
                       `porc_gast_func` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Gastos Funcionales',                      
                       `porc_linea_base` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Linea Base',                             
                       `porc_imprev` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Gastos Imprevistos',                         
                       `porc_gast_superv_proy` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Gastos Supervision de proyectos',  
                       `fec_crea` datetime DEFAULT NULL,                                                                      
                       `usr_crea` varchar(20) DEFAULT NULL,                                                                   
                       `fec_actu` datetime DEFAULT NULL,                                                                      
                       `usr_actu` varchar(20) DEFAULT NULL,                                                                   
                       UNIQUE KEY `concurso_linea` (`num_conc`,`cod_linea`)                                                   
                     ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
                     
/**********************************************
   sql-08112013-1129.sql
 **********************************************/
/* Nuevo campo de tasa del proyecto*/

alter table `t02_tasas_proy` add column `t02_proc_gast_superv` varchar(10) NULL after `t02_porc_imprev`;

/* Actualizacion en el borrado del proyecto */
DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_delete_proy`$$

CREATE  PROCEDURE `sp_delete_proy`(IN _proy VARCHAR(10), IN _vs INT)
BEGIN
declare numrows int;
DELETE from t09_act WHERE t09_act.t02_cod_proy = _proy;
DELETE from t08_comp WHERE t08_comp.t02_cod_proy = _proy;
DELETE from t02_dg_proy WHERE t02_dg_proy.t02_cod_proy = _proy;
#
# -------------------------------------------------->
# DA 2.0 [08-11-2013 11:45]
# Eliminamos datos tambien de las tasas del proyecto. 
#
DELETE from t02_tasas_proy WHERE t02_tasas_proy.t02_cod_proy = _proy;
# --------------------------------------------------<
DELETE from t02_fuente_finan WHERE t02_fuente_finan.t02_cod_proy = _proy;
DELETE from t02_aprob_proy WHERE t02_aprob_proy.t02_cod_proy = _proy;
DELETE from t02_sector_prod WHERE t02_sector_prod.t02_cod_proy = _proy;
DELETE from t02_carta_fianza WHERE t02_carta_fianza.t02_cod_proy = _proy;
DELETE from t02_duracion WHERE t02_duracion.t02_cod_proy = _proy;
DELETE from t02_noobjecion_compra WHERE t02_noobjecion_compra.t02_cod_proy = _proy;
DELETE from t02_noobjecion_compra_anx WHERE t02_noobjecion_compra_anx.t02_cod_proy = _proy;
DELETE from t02_poa WHERE t02_poa.t02_cod_proy = _proy;
DELETE from t02_poa_anexos WHERE t02_poa_anexos.t02_cod_proy = _proy;
DELETE from t02_proy_anx WHERE t02_proy_anx.t02_cod_proy = _proy;
DELETE from t02_proy_ctas WHERE t02_proy_ctas.t02_cod_proy = _proy;
DELETE from t02_proy_version WHERE t02_proy_version.t02_cod_proy = _proy;
DELETE from t03_dist_proy WHERE t03_dist_proy.t02_cod_proy = _proy;
DELETE from t03_mp_equi WHERE t03_mp_equi.t02_cod_proy = _proy;
DELETE from t03_mp_equi_ftes WHERE t03_mp_equi_ftes.t02_cod_proy = _proy;
DELETE from t03_mp_equi_metas WHERE t03_mp_equi_metas.t02_cod_proy = _proy;
DELETE from t03_mp_gas_adm WHERE t03_mp_gas_adm.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_cost WHERE t03_mp_gas_fun_cost.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_ftes WHERE t03_mp_gas_fun_ftes.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_metas WHERE t03_mp_gas_fun_metas.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_part WHERE t03_mp_gas_fun_part.t02_cod_proy = _proy;
DELETE from t03_mp_imprevistos WHERE t03_mp_imprevistos.t02_cod_proy = _proy;
DELETE from t03_mp_linea_base WHERE t03_mp_linea_base.t02_cod_proy = _proy;
DELETE from t03_mp_per WHERE t03_mp_per.t02_cod_proy = _proy;
DELETE from t03_mp_per_ftes WHERE t03_mp_per_ftes.t02_cod_proy = _proy;
DELETE from t03_mp_per_metas WHERE t03_mp_per_metas.t02_cod_proy = _proy;
DELETE from t04_equi_proy WHERE t04_equi_proy.t02_cod_proy = _proy;
DELETE from t04_sol_cambio_per WHERE t04_sol_cambio_per.t02_cod_proy = _proy;
DELETE from t06_fin_ind WHERE t06_fin_ind.t02_cod_proy = _proy;
DELETE from t06_fin_sup WHERE t06_fin_sup.t02_cod_proy = _proy;
DELETE from t07_prop_ind WHERE t07_prop_ind.t02_cod_proy = _proy;
DELETE from t07_prop_ind_inf WHERE t07_prop_ind_inf.t02_cod_proy = _proy;
DELETE from t07_prop_sup WHERE t07_prop_sup.t02_cod_proy = _proy;
DELETE from t08_comp_ind WHERE t08_comp_ind.t02_cod_proy = _proy;
DELETE from t08_comp_ind_inf WHERE t08_comp_ind_inf.t02_cod_proy = _proy;
DELETE from t08_comp_ind_inf_me WHERE t08_comp_ind_inf_me.t02_cod_proy = _proy;
DELETE from t08_comp_ind_inf_mi WHERE t08_comp_ind_inf_mi.t02_cod_proy = _proy;
DELETE from t08_comp_sup WHERE t08_comp_sup.t02_cod_proy = _proy;
DELETE from t09_act_ind WHERE t09_act_ind.t02_cod_proy = _proy;
DELETE from t09_act_ind_inf_me WHERE t09_act_ind_inf_me.t02_cod_proy = _proy;
DELETE from t09_act_ind_inf_mi WHERE t09_act_ind_inf_mi.t02_cod_proy = _proy;
DELETE from t09_act_ind_mtas WHERE t09_act_ind_mtas.t02_cod_proy = _proy;
DELETE from t09_act_ind_mtas_inf WHERE t09_act_ind_mtas_inf.t02_cod_proy = _proy;
DELETE from t09_act_sub_mtas_inf WHERE t09_act_sub_mtas_inf.t02_cod_proy = _proy;
DELETE from t09_act_sub_mtas_inf_me WHERE t09_act_sub_mtas_inf_me.t02_cod_proy = _proy;
DELETE from t09_act_sub_mtas_inf_mi WHERE t09_act_sub_mtas_inf_mi.t02_cod_proy = _proy;
DELETE from t09_sub_act_mtas WHERE t09_sub_act_mtas.t02_cod_proy = _proy;
DELETE from t09_sub_act_plan WHERE t09_sub_act_plan.t02_cod_proy = _proy;
DELETE from t09_subact WHERE t09_subact.t02_cod_proy = _proy;
DELETE from t10_cost_fte WHERE t10_cost_fte.t02_cod_proy = _proy;
DELETE from t10_cost_sub WHERE t10_cost_sub.t02_cod_proy = _proy;
DELETE from t11_bco_bene WHERE t11_bco_bene.t02_cod_proy = _proy;
DELETE from t12_plan_at WHERE t12_plan_at.t02_cod_proy = _proy;
DELETE from t12_plan_capac WHERE t12_plan_capac.t02_cod_proy = _proy;
DELETE from t12_plan_capac_tema WHERE t12_plan_capac_tema.t02_cod_proy = _proy;
DELETE from t12_plan_cred WHERE t12_plan_cred.t02_cod_proy = _proy;
DELETE from t12_plan_cred_benef WHERE t12_plan_cred_benef.t02_cod_proy = _proy;
DELETE from t12_plan_otros WHERE t12_plan_otros.t02_cod_proy = _proy;
DELETE from t20_inf_mes WHERE t20_inf_mes.t02_cod_proy = _proy;
DELETE from t20_inf_mes_anx WHERE t20_inf_mes_anx.t02_cod_proy = _proy;
DELETE from t20_inf_mes_prob WHERE t20_inf_mes_prob.t02_cod_proy = _proy;
DELETE from t25_inf_trim WHERE t25_inf_trim.t02_cod_proy = _proy;
DELETE from t25_inf_trim_anx WHERE t25_inf_trim_anx.t02_cod_proy = _proy;
DELETE from t25_inf_trim_at WHERE t25_inf_trim_at.t02_cod_proy = _proy;
DELETE from t25_inf_trim_capac WHERE t25_inf_trim_capac.t02_cod_proy = _proy;
DELETE from t25_inf_trim_cred WHERE t25_inf_trim_cred.t02_cod_proy = _proy;
DELETE from t25_inf_trim_otros WHERE t25_inf_trim_otros.t02_cod_proy = _proy;
DELETE from t30_inf_me WHERE t30_inf_me.t02_cod_proy = _proy;
DELETE from t30_inf_me_anexos WHERE t30_inf_me_anexos.t02_cod_proy = _proy;
DELETE from t31_plan_me WHERE t31_plan_me.t02_cod_proy = _proy;
DELETE from t31_plan_me_aprob WHERE t31_plan_me_aprob.t02_cod_proy = _proy;
DELETE from t32_plan_act WHERE t32_plan_act.t02_cod_proy = _proy;
DELETE from t32_plan_vta WHERE t32_plan_vta.t02_cod_proy = _proy;
DELETE from t40_inf_financ WHERE t40_inf_financ.t02_cod_proy = _proy;
DELETE from t40_inf_financ_anx WHERE t40_inf_financ_anx.t02_cod_proy = _proy;
DELETE from t41_inf_financ_gasto WHERE t41_inf_financ_gasto.t02_cod_proy = _proy;
DELETE from t42_inf_financ_gasto_det WHERE t42_inf_financ_gasto_det.t02_cod_proy = _proy;
DELETE from t45_inf_mi WHERE t45_inf_mi.t02_cod_proy = _proy;
DELETE from t45_inf_mi_anexos WHERE t45_inf_mi_anexos.t02_cod_proy = _proy;
DELETE from t45_inf_mi_subact WHERE t45_inf_mi_subact.t02_cod_proy = _proy;
DELETE from t46_cron_visita_mi WHERE t46_cron_visita_mi.t02_cod_proy = _proy;
DELETE from t50_equiv_codis WHERE t50_equiv_codis.t02_cod_proy = _proy;
DELETE from t50_plan_moni_ext WHERE t50_plan_moni_ext.t02_cod_proy = _proy;
DELETE from t51_inf_mf WHERE t51_inf_mf.t02_cod_proy = _proy;
DELETE from t51_inf_mf_anexos WHERE t51_inf_mf_anexos.t02_cod_proy = _proy;
DELETE from t51_inf_mf_avance_meta WHERE t51_inf_mf_avance_meta.t02_cod_proy = _proy;
DELETE from t51_inf_mf_avance_monto WHERE t51_inf_mf_avance_monto.t02_cod_proy = _proy;
DELETE from t51_inf_mf_docs WHERE t51_inf_mf_docs.t02_cod_proy = _proy;
DELETE from t51_inf_mf_gastos_no_aceptados WHERE t51_inf_mf_gastos_no_aceptados.t02_cod_proy = _proy;
DELETE from t51_inf_mf_observa WHERE t51_inf_mf_observa.t02_cod_proy = _proy;
DELETE from t52_inf_visita_mf WHERE t52_inf_visita_mf.t02_cod_proy = _proy;
DELETE from t52_inf_visita_mf_anexos WHERE t52_inf_visita_mf_anexos.t02_cod_proy = _proy;
DELETE from t55_inf_unico_anual WHERE t55_inf_unico_anual.t02_cod_proy = _proy;
DELETE from t55_inf_unico_anual_avance_meta WHERE t55_inf_unico_anual_avance_meta.t02_cod_proy = _proy;
DELETE from t55_inf_unico_anual_avance_presup WHERE t55_inf_unico_anual_avance_presup.t02_cod_proy = _proy;
DELETE from t59_aprob_primer_desemb WHERE t59_aprob_primer_desemb.t02_cod_proy = _proy;
DELETE from t60_aprobacion_desemb WHERE t60_aprobacion_desemb.t02_cod_proy = _proy;
DELETE from t60_desembolsos WHERE t60_desembolsos.t02_cod_proy = _proy;
DELETE from t60_ejecucion_desemb WHERE t60_ejecucion_desemb.t02_cod_proy = _proy;
DELETE from t60_ejecucion_desemb_me WHERE t60_ejecucion_desemb_me.t02_cod_proy = _proy;
DELETE from adm_usuarios WHERE adm_usuarios.t02_cod_proy = _proy;
set numrows = 1;
SELECT numrows;
END$$

DELIMITER ;
                     
/**********************************************
   sql-08112013-1804.sql
 **********************************************/

/* Nuevo campo de cuenta bancaria para el proyecto */
alter table `t02_dg_proy` add column `t01_id_cta` int(11) NOT NULL COMMENT 'Id de la cuenta' after `t01_id_inst`;
update t02_dg_proy SET t01_id_cta=1;

/* Actualizacion para cuenta bancaria del proyecto*/
DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_get_proyecto`$$

CREATE  PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    
    p.t00_cod_linea, 
    p.t01_id_inst,
    p.t01_id_cta, 
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
    tp.t02_porc_imprev,
    tp.t02_proc_gast_superv
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;


/* Actualizacion para el registro del proyecto con la cuenta bancaria*/
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_proyecto`$$

CREATE PROCEDURE `sp_ins_proyecto`(IN _t02_nro_exp     VARCHAR(20) ,
                    IN _t00_cod_linea   INT,
                    IN _t01_id_inst     INT,
            IN _t01_id_cta     INT,
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
            IN _t02_proc_gast_superv     VARCHAR(10),           
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
    t01_id_cta,
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
    _t01_id_cta, 
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
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv ,usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _t02_proc_gast_superv, _UserID, now() );
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
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    
    IF _t02_cre_fe='S' THEN 
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/* Actualizacion para la edicion del proyecto con la cuenta bancaria*/
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_proyecto`$$

CREATE PROCEDURE `sp_upd_proyecto`( IN _version INT,
                    IN _nro_exp VARCHAR(20) ,
                    IN _cod_linea INT,
                    IN _inst INT,
            IN _t01_id_cta INT,
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
                    IN _t02_proc_gast_superv     VARCHAR(10),
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
    t01_id_cta = _t01_id_cta,
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
        t02_proc_gast_superv = _t02_proc_gast_superv,
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

/**********************************************
   sql-10112013-2116.sql
 **********************************************/
/* Nuevos campos de Productos principales y productos promovidos de clasificacion de Sectores y Subsectores: */

alter table `t02_dg_proy` add column `t02_prod_principal` int(11) NULL COMMENT 'Producto Principal' after `t02_subsect_prod`;
alter table `t02_dg_proy` add column `t02_prod_promovido` varchar(150) NULL COMMENT 'Productos  Promovidos' after `t02_prod_principal`;


/* Nueva tabla para Producto principal */
CREATE TABLE `adm_tablas_aux3` (                  
                   `codi` int(10) NOT NULL DEFAULT '0',            
                   `descrip` varchar(100) NOT NULL,                
                   `abrev` varchar(15) NOT NULL,                   
                   `cod_ext` varchar(20) DEFAULT NULL,             
                   `flg_act` char(1) NOT NULL,                     
                   `idTabla` int(10) NOT NULL DEFAULT '0',         
                   `id_tabla_aux2` int(10) NOT NULL DEFAULT '0',    
                   `orden` int(10) DEFAULT NULL,                   
                   `usu_crea` varchar(20) NOT NULL,                
                   `fec_crea` date NOT NULL DEFAULT '0000-00-00',  
                   `usu_actu` varchar(20) NOT NULL,                
                   `fec_actu` date NOT NULL DEFAULT '0000-00-00',  
                   PRIMARY KEY (`codi`)                            
                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

/**********************************************
   fix-11-11-2013-1233.sql
 **********************************************/
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '08','1','1','1','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '08','2','1','1','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '08','3','1','1','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '08','4','1','1','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '09','1','1','1','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '09','2','0','1','1','0',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '09','3','1','0','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '09','4','1','1','2','0',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '10','1','1','1','1','1',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '10','2','0','0','0','0',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '10','3','0','0','0','0',NULL,NULL,NULL,NULL);
insert into `adm_concursos_tasas`(`num_conc`,`cod_linea`,`porc_gast_func`,`porc_linea_base`,`porc_imprev`,`porc_gast_superv_proy`,`fec_crea`,`usr_crea`,`fec_actu`,`usr_actu`) values ( '10','4','1','1','1','1',NULL,NULL,NULL,NULL);

/**********************************************
   fix-11-11-2013-1433.sql
 **********************************************/
DROP TABLE IF EXISTS `t00_parametro`;

CREATE TABLE `t00_parametro` (
  `t00_cod_param` int(11) NOT NULL COMMENT 'Codigo del Parametro',
  `t00_nom_abre` varchar(50) NOT NULL COMMENT 'Nombre de Parametro',
  `t00_nom_lar` varchar(20) NOT NULL COMMENT 'Valor de Parametro',
  `usr_crea` varchar(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` varchar(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t00_cod_param`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `t00_parametro` */

insert  into `t00_parametro`(`t00_cod_param`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (1,'Gratificacion','6',NULL,NULL,NULL,NULL,NULL);
insert  into `t00_parametro`(`t00_cod_param`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (2,'Porcentaje CTS','8.333',NULL,NULL,'ad_fondoe','2013-11-07 14:53:52',NULL);
insert  into `t00_parametro`(`t00_cod_param`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (3,'Porcentaje ESS','9',NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `t00_tasa` */

DROP TABLE IF EXISTS `t00_tasa`;

CREATE TABLE `t00_tasa` (
  `t00_cod_tasa` int(11) NOT NULL COMMENT 'Codigo de Tasa',
  `t00_nom_abre` varchar(50) NOT NULL COMMENT 'Nombre de Tasa',
  `t00_nom_lar` varchar(20) NOT NULL COMMENT 'Valor de Tasa',
  `usr_crea` varchar(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` varchar(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t00_cod_tasa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `t00_tasa` */

insert  into `t00_tasa`(`t00_cod_tasa`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (1,'P. Gastos Func.','8.3','ad_fondoe','2013-10-22 18:57:38','ad_fondoe','2013-11-07 22:02:13',NULL);
insert  into `t00_tasa`(`t00_cod_tasa`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (2,'P.  Linea Base','4','ad_fondoe','2013-10-22 18:59:15',NULL,NULL,NULL);
insert  into `t00_tasa`(`t00_cod_tasa`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (3,'P. Imprevistos','1','ad_fondoe','2013-10-22 18:59:40','ad_fondoe','2013-11-04 16:14:26',NULL);
insert  into `t00_tasa`(`t00_cod_tasa`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (4,'Gastos de Supev. de Proyectos','1','ad_fondoe','2013-11-07 14:59:00',NULL,NULL,NULL);

/**********************************************
   sql-11112013-1550.sql
 **********************************************/

/* Nuevos datos de clasificacion de productos generales*/
insert into `adm_tablas`(`cod_tabla`,`nom_tabla`,`flg_act`) values ( '35','Sectores de Capacitacion','1');
insert into `adm_tablas`(`cod_tabla`,`nom_tabla`,`flg_act`) values ( '36','Sectores de Emprendimiento','1');

alter table `t02_dg_proy` drop column `t02_prod_principal`;
alter table `t02_dg_proy` add column `t02_sect_main` int(11) NULL COMMENT 'Sector - Clasificacion general' after `t02_est_imp`;
UPDATE t02_dg_proy SET t02_sect_main = 18;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_proyecto`$$

CREATE  PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    
    p.t00_cod_linea, 
    p.t01_id_inst,
    p.t01_id_cta, 
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
    p.t02_sect_main,
    p.t02_sect_prod,
    p.t02_subsect_prod,
    p.t02_prod_promovido,
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
    tp.t02_porc_imprev,
    tp.t02_proc_gast_superv
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;

drop table `adm_tablas_aux3`;

insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('288','Capacitación','Capacitación','','1','35','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('289','Capacitación e inserción','Capac. e Inserc','','1','35','2','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('290','Otros','Otros','','1','35','3','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('291','Gestión ambiental','Gest. Ambiental','','1','36','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('292','Emprendimientos','Emprendimientos','','1','36','2','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('293','Turismo','Turismo','','1','36','3','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('294','Artesanía','Artesanía','','1','36','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('295','Mypes','Mypes','','1','36','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('296','Agrícola','Agrícola','','1','18','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('297','Frutícola','Frutícola','','1','18','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('298','Gestión del agua','Gest. del agua','','1','18','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('299','Pecuario','Pecuario','','1','18','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');

insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('76','Papa','Papa','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('77','Algodón','Algodón','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('78','Flores','Flores','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('79','Frijol ','Frijol ','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('80','Hongos','Hongos','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('81','Maíz','Maíz','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('82','Orégano','Orégano','','1','18','296','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('83','Quinua','Quinua','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('84','Seguridad alimentaria','Seg. Aliment.','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('85','Tarwi','Tarwi','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('86','Aguaymanto','Aguaymanto','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('87','Banano','Banano','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('88','Cacao','Cacao','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('89','Café','Café','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('90','Camu Camu','Camu Camu','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('91','Frutícola','Frutícola','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('92','Melocotón','Melocotón','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('93','Olivo','Olivo','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('94','Palta','Palta','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('95','Vitivinícola','Vitivinícola','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('96','Gestión del agua','Gest. del agua','','1','18','298','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('97','Panela','Panela','','1','18','180','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('98','Alpacas','Alpacas','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('99','Apícola','Apícola','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('100','Cuyes','Cuyes','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('101','Ganadería Vacuna','Ganad. Vacuna','','1','18','299','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('102','Truchas','Truchas','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('103','Capacitación','Capacitación','','1','35','288','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('104','Capacitación e inserción','Capac. e Inserc','','1','35','288','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('105','Certificación de competencias','Cert. de Compe.','','1','35','290','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('106','Reciclaje','Reciclaje','','1','36','291','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('107','Artesania','Artesania','','1','36','294','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('108','Emprendimientos','Emprendimientos','','1','36','292','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('109','---','---','','1','36','292','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('110','Carpintería','Carpintería','','1','36','295','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('111','Turismo','Turismo','','1','36','293','1','ad_fondoe','2013-11-11','','0000-00-00');

DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_upd_proyecto`$$

CREATE PROCEDURE `sp_upd_proyecto`( IN _version INT,
                    IN _nro_exp VARCHAR(20) ,
                    IN _cod_linea INT,
                    IN _inst INT,
            IN _t01_id_cta INT,
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
    t01_id_cta = _t01_id_cta,
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
        t02_proc_gast_superv = _t02_proc_gast_superv,
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

DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_ins_proyecto`$$

CREATE PROCEDURE `sp_ins_proyecto`(IN _t02_nro_exp     VARCHAR(20) ,
                    IN _t00_cod_linea   INT,
                    IN _t01_id_inst     INT,
            IN _t01_id_cta     INT,
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
                    IN _t02_sect_main   INT,
                    IN _t02_sector      INT,
                    IN _t02_subsector   INT,
                    IN _t02_prod_promovido   VARCHAR(200),
                    IN _t02_cre_fe      CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
            IN _t02_proc_gast_superv     VARCHAR(10),           
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
    t01_id_cta,
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
    t02_sect_main,
    t02_sect_prod, 
    t02_subsect_prod, 
    t02_prod_promovido,
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
    _t01_id_cta, 
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
    _t02_sect_main,
    _t02_sector, 
    _t02_subsector,
    _t02_prod_promovido, 
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
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv ,usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _t02_proc_gast_superv, _UserID, now() );
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
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    
    IF _t02_cre_fe='S' THEN 
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/* Ya no va el mantenimiento de Productos principales.*/
DELETE FROM adm_menus WHERE mnu_cod = 'MNU91400';

/**********************************************
   sql-11112013-1550.sql
 **********************************************/
 
 
 
 /**********************************************
   sql-13112013-0949.sql
 **********************************************/
 
 /* actualizacion de eliminado de concurso */
DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_del_concurso`$$

CREATE PROCEDURE `sp_del_concurso`(IN _id int)
BEGIN
 DECLARE _msg    VARCHAR(100);
 DECLARE _existe INT ;
 declare _rowsaffect int ;
 
 select distinct count(1) 
 into _existe
 from t02_dg_proy p  
 where SUBSTRING(p.t02_cod_proy,3,2) = _id ;
 if _existe > 0 then
    SELECT CONCAT('No se puede eliminar el concurso, tiene [',_existe,']', ' Proyectos registrados')
    INTO _msg ;
 end if;
 IF _existe <= 0 THEN
    delete FROM  adm_concursos WHERE num_conc=_id ;
    SELECT ROW_COUNT() ,'' into _rowsaffect, _msg ;
        delete FROM  adm_concursos_tasas WHERE num_conc=_id ;
  end if ;
 
/*Retornar el numero de registros afectados */  
SELECT _rowsaffect AS numrows, _id as codigo, _msg as msg ;  
END$$

DELIMITER ;


/* actualizacion de seleccion de un proyecto */
DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_get_proyecto`$$

CREATE  PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    
    p.t00_cod_linea, 
    p.t01_id_inst,
    p.t01_id_cta AS cuenta_bancaria, 
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
    p.t02_sect_main,
    p.t02_sect_prod,
    p.t02_subsect_prod,
    p.t02_prod_promovido,
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
    tp.t02_porc_imprev,
    tp.t02_proc_gast_superv
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;





 /**********************************************
   sql-17112013-0015.sql
 **********************************************/
 
 /* Actualizaciones en procedures: La cuenta bancaria del proyecto ya estaba implementado.*/

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_proyecto`$$

CREATE PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    
    p.t00_cod_linea, 
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
    p.t02_sect_main,
    p.t02_sect_prod,
    p.t02_subsect_prod,
    p.t02_prod_promovido,
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
    tp.t02_porc_imprev,
    tp.t02_proc_gast_superv
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;




DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_ins_proyecto`$$

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
                    IN _t02_sect_main   INT,
                    IN _t02_sector      INT,
                    IN _t02_subsector   INT,
                    IN _t02_prod_promovido   VARCHAR(200),
                    IN _t02_cre_fe      CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
            IN _t02_proc_gast_superv     VARCHAR(10),           
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
    t02_sect_main,
    t02_sect_prod, 
    t02_subsect_prod, 
    t02_prod_promovido,
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
    _t02_sect_main,
    _t02_sector, 
    _t02_subsector,
    _t02_prod_promovido, 
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
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv ,usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _t02_proc_gast_superv, _UserID, now() );
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
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    
    IF _t02_cre_fe='S' THEN 
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;





DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_upd_proyecto`$$

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
    t02_sect_main = _t02_sect_main,
        t02_sect_prod = _sect_prod , 
        t02_subsect_prod = _subsect_prod , 
    t02_prod_promovido = _t02_prod_promovido, 
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
        t02_proc_gast_superv = _t02_proc_gast_superv,
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





/**********************************************
   sql-18112013-1704.sql
 **********************************************/

 /* 
Actualizacion del proceso de registro de una cuenta bancaria al proyecto.
*/

DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_upd_cta_inst_proy`$$

CREATE PROCEDURE `sp_upd_cta_inst_proy`(
            IN _proy varchar(10), 
            IN _t01_bco     INT ,
            in _codcta      int ,
            IN _nrocta  VARCHAR(25), 
            in _mon         int ,
            in _benef       varchar(100),
            IN _usr_crea    VARCHAR(20))
TRX1:BEGIN
    declare _id int ;
    declare _t01_id_inst int ;
    declare _rowcount int;
    declare _existe boolean ;
    
    /* Validaciones */    
    if not EXISTS(SELECT pcta.t01_id_cta   FROM  t02_proy_ctas pcta  INNER JOIN t01_inst_ctas cta ON(pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta)   WHERE pcta.t02_cod_proy = _proy AND cta.t01_nro_cta=_nrocta   ) then
       /*if EXISTS(select t01_id_cta from t01_inst_ctas  where t00_cod_bco = _t01_bco and t01_nro_cta = _nrocta) then
          SELECT -1 AS numrows, 0 AS codigo, 'La Cuenta Bancaria ya fue registrada' AS msg ;
          leave TRX1;
       end if ;*/
## -------------------------------------------------->
## DA 2.0 [18-11-2013 17:02]
## La Cuenta tampoco deberia de existir para cualquier otro proyecto
    if EXISTS(SELECT pcta.t01_id_cta   FROM  t02_proy_ctas pcta  INNER JOIN t01_inst_ctas cta ON(pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta)   WHERE cta.t01_nro_cta=_nrocta ) then
        SELECT -1 AS numrows, 0 AS codigo, 'La Cuenta Bancaria ya fue registrada' AS msg ;
        leave TRX1;
    end if ;
## --------------------------------------------------<  
    end if;
    
    
    select t01_id_inst 
      into _t01_id_inst
      from t02_dg_proy 
     where t02_cod_proy=_proy 
       and t02_version=fn_ult_version_proy(_proy);
    
    if not EXISTS(select t01_id_cta from t01_inst_ctas where t01_id_inst = _t01_id_inst and t00_cod_bco = _t01_bco and t01_nro_cta=_nrocta ) then
    SELECT IFNULL( MAX(t01_id_cta),0)+1 INTO _id  FROM t01_inst_ctas WHERE t01_id_inst = _t01_id_inst ; 
    
     update t02_proy_ctas set est_audi = '0' where t02_cod_proy = _proy ;
     
     INSERT INTO t01_inst_ctas 
    (t01_id_inst, 
    t01_id_cta, 
    t00_cod_bco, 
    t00_cod_cta, 
    t01_nro_cta, 
    t00_cod_mon, 
    t01_txt_ref, 
    t01_cta_def, 
    usr_crea, 
    fch_crea, 
    est_audi
    )
    VALUES
    ( _t01_id_inst,
    _id,
    _t01_bco,
    _codcta,
    _nrocta,
    _mon,
    '',
    '1',
    _usr_crea,
    NOW(),
    '1'
    );
    
    select ROW_COUNT() into _rowcount ;
    
    else 
    SELECT t01_id_cta 
      into _id
    FROM t01_inst_ctas 
    WHERE t01_id_inst = _t01_id_inst 
      and t00_cod_bco = _t01_bco 
      AND t01_nro_cta = _nrocta ;
      
    SELECT 1 INTO _rowcount ;
    end if ;
    
    call sp_upd_cta_proy(_proy, _t01_id_inst , _id, _benef, _usr_crea) ;
    
    SELECT _rowcount AS numrows, _id AS codigo, '' AS msg ;
     
END$$

DELIMITER ;

/**********************************************
   sql-19112013-2132.sql
 **********************************************/
/*
// -------------------------------------------------->
// AQ 2.0 [19-11-2013 21:32]
// Aprobación de los Datos Generales del Proyecto 
// --------------------------------------------------<
*/
DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_upd_proyecto`$$

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
    t02_sect_main = _t02_sect_main,
        t02_sect_prod = _sect_prod , 
        t02_subsect_prod = _subsect_prod , 
    t02_prod_promovido = _t02_prod_promovido, 
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
        t02_proc_gast_superv = _t02_proc_gast_superv,
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
    
    #
    # -------------------------------------------------->
    # AQ 2.0 [19-11-2013 21:42]
    # Aprobación de Datos Generales
    #
    IF _aprobado IS NOT NULL THEN
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
    # --------------------------------------------------<
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/**********************************************
   sql-20112013-1718.sql
 **********************************************/
/*
// -------------------------------------------------->
// AQ 2.0 [20-11-2013 17:18]
// Tabla de Productos Entregables 
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t02_entregable`;

--
-- Estructura de tabla para la tabla `t02_entregable`
--
CREATE TABLE IF NOT EXISTS `t02_entregable` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t02_anio` tinyint(4) DEFAULT NULL,
  `t02_mes` tinyint(4) DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t02_anio`,`t02_mes`),
  KEY `FK_t02_entregable` (`t02_cod_proy`,`t02_version`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Filtros para la tabla `t02_entregable`
--
ALTER TABLE `t02_entregable`
  ADD CONSTRAINT `t02_entregable_ibfk_1` 
  FOREIGN KEY (`t02_cod_proy`,`t02_version`) 
  REFERENCES `t02_dg_proy` (`t02_cod_proy`,`t02_version`) 
  ON DELETE CASCADE ON UPDATE CASCADE;
  
/**********************************************
   sql-21112013-1236.sql
 **********************************************/
/* Actualizacion del Cargo en contactos de instituciones: antes monitor externo ahora Supervisor Externo: */
update `adm_tablas_aux` set `codi`='60',`descrip`='Supervisor Externo',`abrev`='Superv. Externo',`cod_ext`=NULL,`flg_act`='1',`idTabla`='6',`orden`='1',`usu_crea`='',`fec_crea`='0000-00-00',`usu_actu`='',`fec_actu`='0000-00-00' where `codi`='60' and `orden`='1';


/* Actualizacion de terminos: */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_del_institucion`$$

CREATE PROCEDURE `sp_del_institucion`( IN _t01_id_inst  INT  )
TRANX : BEGIN
  DECLARE _numrows  INT;
  declare _existe   int ;
  
  SELECT count(distinct t02_cod_proy) into _existe    
  FROM t02_dg_proy WHERE t01_id_inst=_t01_id_inst ;
  
  if _existe > 0 then
    SELECT 0 AS numrows, CONCAT('No se puede eliminar la Institución, es ejecutora de "',_existe,'" Proyectos.') as msg ;
    LEAVE TRANX;    
  end if ;  
  
  SELECT COUNT(DISTINCT t02_cod_proy) INTO _existe    
  FROM t02_fuente_finan WHERE t01_id_inst=_t01_id_inst ;
  
  IF _existe > 0 THEN
    SELECT 0 AS numrows, CONCAT('No se puede eliminar la Institución, es Co-Financiadora en "',_existe,'" Proyectos.') AS msg ;
    LEAVE TRANX;    
  END IF ;  
  
  SELECT COUNT(DISTINCT t02_cod_proy) INTO _existe    
  FROM t02_dg_proy WHERE  SUBSTRING_INDEX(t02_moni_ext,'.',1) = _t01_id_inst ;
  
  IF _existe > 0 THEN
## -------------------------------------------------->
## DA 2.0 [21-11-2013 14:04]
## Actualizacion de terminos: Monitora por Gestora:
    SELECT 0 AS numrows, CONCAT('No se puede eliminar la Institución, es Gestora en "',_existe,'" Proyectos.') AS msg ;
## --------------------------------------------------<
    LEAVE TRANX;    
  END IF ;   
  delete 
    FROM t01_dir_inst 
   WHERE t01_id_inst= _t01_id_inst ;
  
    SELECT ROW_COUNT() INTO _numrows;
    COMMIT;
    
    SELECT _numrows AS numrows, '' AS msg , _t01_id_inst AS codigo ;
       
  
END$$

DELIMITER ;



/* Perfil EVASIS Tambien pronto a Deshabilitarse: marcado con DEL 
Ojo todos los perfiles que tengan en la descripcion el termino DEL 
seran quitados o eliminados del sistema.
*/
update `adm_perfiles` set `per_cod`='6',`per_des`='Gestor de Proyecto - DEL',`per_abrev`='MT',`usr_crea`=NULL,`fch_crea`=NULL,`usr_actu`='ad_fondoe',`fch_actu`='2013-11-21 14:20:39' where `per_cod`='6';
update `adm_perfiles` set `per_cod`='11',`per_des`='EVASIS DEL',`per_abrev`='EVASIS',`usr_crea`='john.aima',`fch_crea`='2011-08-31 18:23:13',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-21 14:07:39' where `per_cod`='11';

/* Actualizacion de perfiles en los usuario: phuaman_mt y rmandujano y otros usuarios. */
update `adm_usuarios` set `coduser`='phuaman_mt',`tipo_user`='13',`nom_user`='Paul Huaman MT',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='diojes4@hotmail.com',`t01_id_uni`='3',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-10 20:46:26',`usr_actu`='ad_fondoe',`fch_actu`='2013-06-18 17:33:59',`est_audi`='1' where `coduser`='phuaman_mt';
update `adm_usuarios` set `coduser`='rmandujano',`tipo_user`='13',`nom_user`='rmandujano',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='rmandujano@fondoempleo.com.pe',`t01_id_uni`='2',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ldiaz1',`fch_crea`='2013-06-07 19:42:30',`usr_actu`='ad_fondoe',`fch_actu`='2013-06-18 22:42:18',`est_audi`='1' where `coduser`='rmandujano';

update `adm_usuarios` set `coduser`='rmuller',`tipo_user`='15',`nom_user`='carlos',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='lgdiazm@hotmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ldiaz1',`fch_crea`='2013-06-07 19:41:14',`usr_actu`='ldiaz1',`fch_actu`='2013-06-17 19:50:19',`est_audi`='1' where `coduser`='rmuller';
update `adm_usuarios` set `coduser`='cmt_julio',`tipo_user`='15',`nom_user`='julio',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='julio@gmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-07 22:47:49',`usr_actu`='ad_fondoe',`fch_actu`='2012-04-11 15:18:41',`est_audi`='1' where `coduser`='cmt_julio';
update `adm_usuarios` set `coduser`='phuama_cmt',`tipo_user`='15',`nom_user`='Paul Huaman CMT',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='diojes4@hotmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-10 23:21:06',`usr_actu`='ad_fondoe',`fch_actu`='2013-06-10 23:29:31',`est_audi`='1' where `coduser`='phuama_cmt';
update `adm_usuarios` set `coduser`='cmt_fondoe',`tipo_user`='15',`nom_user`='Coordinador Técnico',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='rosmerymc@hotmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-07 21:20:55',`usr_actu`='ldiaz1',`fch_actu`='2013-06-17 19:50:02',`est_audi`='1' where `coduser`='cmt_fondoe';


/* Actualizacion de perfiles: */
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='12';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='13';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='14';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='15';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='16';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='2';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='12';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='13';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='14',`ver`='1',`nuevo`='1',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='14';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='15';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='16';
update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='2';
update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='13';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='14';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='16';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='12';
/*[02:55][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='14';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='2';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='14';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='15';
/*[02:55][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='16';
/*[02:55][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='2';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='13';
/*[02:55][ 171 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='14';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='16';
/*[02:55][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='2';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='12';
/*[02:55][ 110 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='16';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='12';
/*[02:55][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='13';
/*[02:55][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='14';
/*[02:55][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='15';
/*[02:55][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='16';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='2';
/*[02:55][ 110 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='14';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='15';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='16';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='16';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='2';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='14';
/*[02:55][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='15';
/*[02:55][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='16';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='15';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='16';
/*[02:55][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='0',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='2';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='12';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='13';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='15';
/*[02:55][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='16';
/*[02:55][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='15';
/*[02:55][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='16';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='13';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='15';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='16';
/*[02:55][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='2';
/*[02:55][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='12';
/*[02:55][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='14';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='15';
/*[02:55][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='16';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='12';
/*[02:55][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='13';
/*[02:55][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='15';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='16';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='2';
/*[02:55][ 124 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='12';
/*[02:55][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='13';
/*[02:55][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='14';
/*[02:56][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='2';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='2';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='12';
/*[02:56][ 124 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='13';
/*[02:56][ 141 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='14';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='15';
/*[02:56][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='16';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='2';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='14';
/*[02:56][  31 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='15';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='2';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='12';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='13';
/*[02:56][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='15';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='12';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='15';
/*[02:56][  32 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='2';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='13';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='14';
/*[02:56][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='15';
/*[02:56][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='16';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='12';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='13';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='2';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='12';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='13';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='16',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='16';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='2';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='13',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='13';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='16';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='12';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='13';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='2';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='13';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='16';
/*[02:56][  32 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='12';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='13';
/*[02:56][ 110 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='14';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='13';
/*[02:56][  31 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='14';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='12';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='13';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='14';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='12';
/*[02:56][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='13';
/*[02:56][ 171 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='15';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='2';
/*[02:56][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='12',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='13';
/*[02:56][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='15';
/*[02:56][ 171 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='16';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='2';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='12',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='12';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='13';
/*[02:56][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='15';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='16';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='2';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='12',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='12';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='13';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='15';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='16';
/*[02:56][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='13';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='14';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='16';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='12';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='14';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='15';
/*[02:56][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU64000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU64000' and `per_cod`='12';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU64000',`per_cod`='14',`ver`='1',`nuevo`='1',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU64000' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU64000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU64000' and `per_cod`='15';
/*[02:56][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='12';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='13';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='14';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='15';
update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='16';
update `adm_menus_perfil` set `mnu_cod`='MNU72000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU72000' and `per_cod`='2';






/* Mas longitud del link de menus: */
alter table `adm_menus` change `mnu_link` `mnu_link` varchar(250) character set utf8 collate utf8_general_ci NULL ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_menu`$$

CREATE  PROCEDURE `sp_upd_menu`(
            in _cod   varchar(8),
            in _nom   varchar(50) ,
            IN _link  varchar(250), 
            in _isparent char(1),
            in _parent varchar(10),
            IN _targ   VARCHAR(10),
            in _img    varchar(100),
            in _act    char(1),
            in _ord    int,
            in _mod    char(1))
BEGIN
  declare _class varchar(20);
  
   select (case when _isparent='1' then 'MenuBarItemSubmenu' else '' end )
   into  _class;
   
   UPDATE adm_menus 
      SET
    mnu_nomb = _nom , 
    mnu_link = _link, 
    mnu_target = _targ , 
    mnu_parent = _parent , 
    mnu_activo = _act , 
    mnu_class  = _class , 
    mnu_sort   = _ord , 
    mnu_img    = _img , 
    mnu_admin  = _mod
    WHERE mnu_cod  = _cod ;   
     
    SELECT ROW_COUNT() AS numrows, _cod AS codigo, '' AS msg ;
     
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_menu`$$

CREATE PROCEDURE `sp_ins_menu`(
            in _cod   varchar(8),
            in _nom   varchar(50) ,
            IN _link  varchar(250), 
            in _isparent char(1),
            in _parent varchar(10),
            IN _targ   VARCHAR(10),
            in _img    varchar(100),
            in _act    char(1),
            in _ord    int,
            in _mod    char(1))
BEGIN
  DECLARE _msg    varchar(100);
  declare _existe int ;
  declare _rowcount int ;
  declare _class varchar(20);
  
  
  
  select COUNT(1)
  into _existe
  from adm_menus
  where mnu_cod = _cod ;
  
  if _existe > 0 then
    select concat('El Menu ', _nom, ' ya fue registrado...')
    into _msg ;
    SELECT 0 AS numrows, 0 AS codigo, _msg AS msg ;
    
  else
   /*
    select IFNULL(max(mnu_cod),0) + 1
      into _cod
      from adm_menus ; 
   */
   
   select (case when _isparent='1' then 'MenuBarItemSubmenu' else '' end )
   into  _class;
   
    INSERT INTO adm_menus 
    (mnu_cod, 
     mnu_nomb, 
     mnu_apli, 
     mnu_link, 
     mnu_target, 
     mnu_parent, 
     mnu_activo, 
     mnu_class, 
     mnu_sort, 
     mnu_img, 
     mnu_admin
    )
    VALUES
    ( _cod, 
      _nom, 
      1, 
      _link, 
      _targ, 
      _parent, 
      _act, 
      _class, 
      _ord, 
      _img, 
      _mod
    );
    select ROW_COUNT() into _rowcount ;
    
    call sp_ins_menu_pagina(_cod, _nom, _link);
    
    SELECT _rowcount  AS numrows, _cod AS codigo, '' AS msg ;
     
  end if ;
END$$

DELIMITER ;

/**********************************************
   sql-24112013-1757.sql
 **********************************************/
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
FROM      t02_dg_proy proy
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
FROM      t02_dg_proy proy
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
FROM      t02_dg_proy proy
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
FROM      t02_dg_proy proy
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
FROM      t02_dg_proy proy
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

/**********************************************
   sql-26112013-1443.sql
 **********************************************/
/* Actualizacion de Sectores productivos:*/

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_sel_sector_prod`$$

CREATE  PROCEDURE `sp_sel_sector_prod`(IN _proy VARCHAR(10))
BEGIN   
    SELECT  t02.t02_cod_proy,
        t02.t02_sector_main,
        t02.t02_sector,
        t02.t02_subsec,
        secmain.nom_tabla AS sector_main,
        sec.descrip AS sector,
        sub.descrip AS subsector,
        t02.t02_obs
    FROM t02_sector_prod t02
    LEFT JOIN adm_tablas secmain ON (t02.t02_sector_main=secmain.cod_tabla)
    LEFT JOIN adm_tablas_aux sec ON (t02.t02_sector=sec.codi)
    LEFT JOIN adm_tablas_aux2 sub ON (t02.t02_subsec=sub.codi)
    WHERE t02_cod_proy = _proy ;
END$$

DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_sector_prod`$$

CREATE PROCEDURE `sp_ins_sector_prod`(
            IN _proy VARCHAR(10), 
            IN _sectmain INT, 
            IN _sect INT, 
            IN _subs INT, 
            IN _obs  TEXT, 
            IN _usr VARCHAR(20))
BEGIN
  DECLARE _existe  INT;
  DECLARE _error VARCHAR(100);
  
  SELECT COUNT(t02_sector) 
  INTO   _existe
  FROM t02_sector_prod
  WHERE t02_cod_proy  = _proy
    AND t02_sector_main    = _sectmain
    AND t02_sector    = _sect
    AND t02_subsec    = _subs;
  
  IF _existe =0 THEN
  SELECT '' INTO _error ;
  
  INSERT INTO t02_sector_prod 
    ( t02_cod_proy, 
      t02_sector_main, 
      t02_sector, 
      t02_subsec, 
      t02_obs, 
      usr_crea, 
      fch_crea, 
      est_audi
    )
    VALUES
    ( _proy , 
      _sectmain , 
      _sect   , 
      _subs  , 
      _obs  , 
      _usr, 
      NOW(),
    '1'
    );
    
    SELECT ROW_COUNT() AS numrows, _subs AS codigo, _error AS 'error' ;
   ELSE
    SELECT 'Ya fue registrado el Sector Productivo para este Proyecto' INTO _error;
    SELECT 0 AS numrows, _subs AS codigo, _error AS 'error' ;
   END IF;
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_sector_prod`$$

CREATE  PROCEDURE `sp_upd_sector_prod`(
            IN _proy VARCHAR(10), 
            IN _sectmain_old INT, 
            IN _sect_old INT, 
            IN _subs_old INT, 
            IN _sectmain INT, 
            IN _sect INT, 
            IN _subs INT, 
            IN _obs TEXT, 
            IN _usr VARCHAR(20))
BEGIN
DECLARE _existe INT ;
SELECT COUNT(1) INTO _existe
FROM t02_sector_prod
WHERE   t02_cod_proy = _proy 
    AND t02_sector_main   = _sectmain 
    AND t02_sector   = _sect 
    AND t02_subsec   = _subs
    AND (t02_sector_main   <> _sectmain_old AND t02_sector   <> _sect_old   AND t02_subsec   <> _subs_old ) ;
IF _existe = 0 THEN 
  UPDATE t02_sector_prod 
     SET t02_sector_main = _sectmain , 
     t02_sector = _sect , 
     t02_subsec = _subs , 
     t02_obs  = _obs , 
     usr_actu = _usr ,
     fch_actu = NOW()
  WHERE t02_cod_proy = _proy 
    AND t02_sector_main   = _sectmain_old 
    AND t02_sector   = _sect_old 
    AND t02_subsec   = _subs_old  ;
  

SELECT ROW_COUNT() AS numrows, _subs AS codigo, '' AS 'error' ;
ELSE
SELECT 0 AS numrows, 0 AS codigo, 'Ya existe el Sector Productivo que intenta actualizar' AS 'error' ;
END IF;
END$$

DELIMITER ;

/* Los nuevos campos de sectores ya no van el proyecto: */

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_proyecto`$$

CREATE PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
    
    p.t00_cod_linea, 
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
/*
    p.t02_sect_main,
    p.t02_sect_prod,
    p.t02_subsect_prod,
    p.t02_prod_promovido,
*/
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
    tp.t02_porc_imprev,
    tp.t02_proc_gast_superv
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_proyecto`$$

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
                    /*IN _t02_sect_main   INT,
                    IN _t02_sector      INT,
                    IN _t02_subsector   INT,
                    IN _t02_prod_promovido   VARCHAR(200),*/
                    IN _t02_cre_fe      CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
            IN _t02_proc_gast_superv     VARCHAR(10),           
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
    /*t02_sect_main,
    t02_sect_prod, 
    t02_subsect_prod, 
    t02_prod_promovido,*/
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
    /*_t02_sect_main,
    _t02_sector, 
    _t02_subsector,
    _t02_prod_promovido, */
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
    
    INSERT INTO t02_tasas_proy (t02_cod_proy, t02_version, t02_gratificacion, t02_porc_cts, t02_porc_ess, 
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv ,usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _t02_proc_gast_superv, _UserID, now() );
    
    SELECT ROW_COUNT() INTO _num_rows;
    
    CALL sp_calcula_anios_proy(_t02_cod_proy, _vs);
    
/*    SELECT COUNT(t02_sector)  INTO   _existe
      FROM t02_sector_prod  
     WHERE t02_cod_proy  = _t02_cod_proy
       AND t02_sector    = _t02_sector
       AND t02_subsec    = _t02_subsector;
    
    IF _existe <=0 THEN
      CALL sp_ins_sector_prod(_t02_cod_proy, _t02_sector, _t02_subsector, 'Principal', _UserID );
    END IF;
    
 */   
    
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    
    IF _t02_cre_fe='S' THEN 
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;

/**********************************************
   sql-27112013-1116.sql
 **********************************************/
/*
// -------------------------------------------------->
// AQ 2.0 [27-11-2013 11:16]
// Aprobación de Datos Generales
// --------------------------------------------------<
*/
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
    
    IF _aprobado IS NOT NULL THEN
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

/*
// -------------------------------------------------->
// AQ 2.0 [27-11-2013 11:16]
// Permisos a todos los proyectos para el sptest1
// --------------------------------------------------<
*/
replace into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('sptest1','12','Supervisor de proyectos 1','060f56b22231cf8f2c9224911450f215','sptest1@localhost.com','*','*','1','ad_fondoe','2013-10-28 21:42:40',NULL,NULL,'1');

/**********************************************
   sql-27112013-1723.sql
 **********************************************/
/* Correccion de edicion del proyecto */
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
    
    ## IF _aprobado IS NOT NULL THEN
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

/**********************************************
   sql-28112013-1659.sql
 **********************************************/
/*
// -------------------------------------------------->
// AQ 2.0 [28-11-2013 16:59]
// Aprobación del Cronograma de Productos
// por Entregable
// --------------------------------------------------<
*/
ALTER TABLE `t02_aprob_proy` ADD `t02_aprob_croprod` TINYINT NULL DEFAULT NULL COMMENT 'Aprueba Cronograma de Productos',
ADD `t02_fch_croprod` DATETIME NULL DEFAULT NULL ,
ADD `t02_aprob_croprod_mon` TINYINT NULL DEFAULT NULL ,
ADD `t02_obs_croprod` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Observaciones con respecto a la Aprobación del Cronograma de Productos',
ADD `t02_fch_croprod_mon` DATETIME NULL DEFAULT NULL ,
ADD `t02_fch_env_croprod` DATETIME NULL DEFAULT NULL ,
ADD `t02_env_croprod` TINYINT NULL DEFAULT NULL;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_sol_aprob`$$

CREATE PROCEDURE `sp_upd_sol_aprob`(IN _proy VARCHAR(10), 
    IN _tip VARCHAR(20), -- pueden ser ml, cron, pres 
    IN _est INT  ,  -- Opcional
    IN _msg TEXT ,
    IN _usr VARCHAR(20))
BEGIN
DECLARE _rowcount INT DEFAULT 0 ; 
DECLARE _nomejec VARCHAR(30) ;

SELECT t01_sig_inst 
  INTO _nomejec 
  FROM t01_dir_inst 
 WHERE t01_id_inst = (SELECT p.t01_id_inst FROM t02_dg_proy p WHERE p.t02_cod_proy=_proy AND p.t02_version=1);
IF _tip = 'ml' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_ml = _est ,
            t02_fch_ml   = NOW(),
            t02_aprob_ml_mon = NULL,
            t02_obs_ml  = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_ml, t02_fch_ml, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Marco Lógico',
                        CONCAT('El Marco Lógico del proyecto ', _proy, ', ha sido aprobado, para continuar con el ingreso del Cronograma de Actividades. \n\n', _msg,  '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/planifica/ml_index.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
    
END IF ;
IF _tip = 'cron' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_cro = _est ,
            t02_fch_cro   = NOW(),
            t02_aprob_cro_mon = NULL,
            t02_obs_cro = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_cro, t02_fch_cro, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Cronograma de Actividades',
                        CONCAT('El proyecto ', _proy, 
                                ' Cuenta con el VoBo del cronograma de actividades, ',
                                'puede continuar con el registro del presupuesto inicial. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF ;
IF _tip = 'pre' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_pre = _est ,
            t02_fch_pre   = NOW(),
            t02_aprob_pre_mon = NULL,
            t02_obs_pre = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_pre, t02_fch_pre, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Presupuesto',
                        CONCAT('El proyecto ', _proy, 
                        ' cuenta con el VoBo del presupuesto inicial, ',
                        'proceda a registrar Equipo Líder, Sector Productivo, Ambito Geográfico, ',
                        'Planes Específicos y Padrón de Beneficiarios, éste último debe ser revisado periodicamente. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF ;
IF _tip = 'proy' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_proy = _est ,
            t02_fch_aprob_proy  = NOW(),
            t02_obs_aprob_proy  = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_proy, t02_fch_aprob_proy, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Aprobación del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha sido aprobado. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr, 4
                         ) ;
    END IF ;
END IF ;
IF _tip = 'vbpy' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_vb_proy = _est ,
            t02_fch_vb_proy  = NOW(),
            t02_obs_vb_proy = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_vb_proy, t02_fch_vb_proy, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Solicitud Aprobación del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha solicitado su Aprobación para continuar con el ingreso del Marco Lógico. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr, 3
                         ) ;
    END IF ;
END IF;
IF _tip = 'evml' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_ml = _est ,
            t02_fch_env_ml = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_ml, t02_fch_env_ml, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_mt(  _proy, 
                        'Solicitud Revisión Marco Lógico',
                        CONCAT('El proyecto ', _proy, ', ha solicitado la Revisión del Marco Lógico. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/planifica/ml_index.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'evpr' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_pre = _est ,
            t02_fch_env_pre = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_pre, t02_fch_env_pre, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_mf(  _proy, 
                        'Solicitud Revisión del Presupuesto',
                        CONCAT('El proyecto ', _proy, ', solicita la revisión del presupuesto inicial. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'evel' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_el = _est ,
            t02_fch_env_el = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_el, t02_fch_env_el, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_fe(  _proy, 
                        'Solicitud Revisión del Equipo Lider',
                        CONCAT('El proyecto ', _proy, ', ha solicitado su Revisión del Equipo Lider \n\n', _msg ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'evcr' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_cro = _est ,
            t02_fch_env_cro = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_cro, t02_fch_env_cro, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_mt(  _proy, 
                        'Solicitud Revisión del Cronograma de Actividades',
                        CONCAT('El proyecto ', _proy, ', solicita la Revisión del Cronograma de Actividades. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'scp' THEN
SET _rowcount =1;
    UPDATE t04_sol_cambio_per 
           SET  t04_aprob_cmf = 1, 
                t04_aprob_cmt = 1 
         WHERE t02_cod_proy = _proy ;
    SET _rowcount = ROW_COUNT() ;
    IF _rowcount >0 THEN
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Solicitud de Cambio de Personal',
                        CONCAT('La solicitud de cambio del personal del proyecto ', _proy, ', ha sido aprobada \n\n', _msg ),
                        _nomejec,
                        _usr
                         ) ;
        END IF; 
END IF;
IF _tip = 'srdg' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_dg_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_dg_proy 
           SET  env_rev = _est 
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Solicitud de Revisión de Datos Generales del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha solicitado la revisión de sus Datos Generales. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr,
                        1
                         ) ;
    END IF ;
END IF;
/*
// -------------------------------------------------->
// AQ 2.0 [28-11-2013 16:59]
// Envío a Revisón y Aprobación de Cronograma de Productos
*/
IF _tip = 'evcrprod' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_croprod = _est ,
            t02_fch_env_croprod = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_croprod, t02_fch_env_croprod, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN
        CALL sp_ins_mensaje_proy_a_mt(  _proy, 
                        'Solicitud Revisión del Cronograma de Productos',
                        CONCAT('El proyecto ', _proy, ', solicita la Revisión del Cronograma de Productos. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         );
    END IF ;
END IF;

IF _tip = 'cronprod' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_croprod = _est ,
            t02_fch_croprod   = NOW(),
            t02_aprob_croprod_mon = NULL,
            t02_obs_croprod = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_croprod, t02_fch_croprod, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Cronograma de Actividades',
                        CONCAT('El proyecto ', _proy, 
                                ' Cuenta con el VoBo del cronograma de actividades, ',
                                'puede continuar con el registro del presupuesto inicial. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         );
    END IF ;
END IF ;
/* --------------------------------------------------< */

SELECT _rowcount AS numrows, _proy AS codigo ;  /*Retornar el numero de registros afectados */
                    
END $$

DELIMITER ;

/**********************************************
   sql-29112013-1751.sql
 **********************************************/
/*
// -------------------------------------------------->
// AQ 2.0 [29-11-2013 17:51]
// Fuente de Financiamiento "Beneficiarios" por defecto
// --------------------------------------------------<
*/
UPDATE t01_dir_inst SET t01_id_inst = 63 WHERE t01_id_inst = 13;
