/*
Actualizaciones para mantenimiento de tasas y lineas:
*/
##################################################### >
# AQ 2.0 [21-10-2013 17:55]
# Cambio a términos singulares.
#
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