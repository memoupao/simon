
/* Nueva Tabla para los parametros del sistema */
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

# Datos iniciales de la tabla de parametros
truncate table `t00_parametro`;
insert  into `t00_parametro`(`t00_cod_param`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (1,'Gratificacion','6',NULL,NULL,NULL,NULL,NULL);
insert  into `t00_parametro`(`t00_cod_param`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (2,'Porcentaje CTS','8.333',NULL,NULL,'ad_fondoe','2013-11-07 14:53:52',NULL);
insert  into `t00_parametro`(`t00_cod_param`,`t00_nom_abre`,`t00_nom_lar`,`usr_crea`,`fch_crea`,`usr_actu`,`fch_actu`,`est_audi`) values (3,'Porcentaje ESS','9',NULL,NULL,NULL,NULL,NULL);
		  
		  
# Nuevo menu de parametros:
DELETE FROM adm_menus WHERE mnu_cod = 'MNU92900';
insert into `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) values('MNU92900','Parametros','1','/sgp/Admin/man_parametros.php','_self','MNU91000','1','','8','','1');

# actualizamos los ordenes de posicion:
update `adm_menus` set `mnu_cod`='MNU92700',`mnu_nomb`='Tipos de Cuenta',`mnu_apli`='1',`mnu_link`='/sgp/Admin/man_concepto.php?item=tipo_cuenta',`mnu_target`='_self',`mnu_parent`='MNU91000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='9',`mnu_img`='',`mnu_admin`='1' where `mnu_cod`='MNU92700';
update `adm_menus` set `mnu_cod`='MNU92800',`mnu_nomb`='Tipos de Anexo',`mnu_apli`='1',`mnu_link`='/sgp/Admin/man_concepto.php?item=tipo_anexo',`mnu_target`='_self',`mnu_parent`='MNU91000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='10',`mnu_img`='',`mnu_admin`='1' where `mnu_cod`='MNU92800';



# actualizamos el termino de tasas a tasas base:
update `adm_menus` set `mnu_cod`='MNU92500',`mnu_nomb`='Tasas Base',`mnu_apli`='1',`mnu_link`='/sgp/Admin/man_tasa.php',`mnu_target`='_self',`mnu_parent`='MNU91000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='7',`mnu_img`='',`mnu_admin`='1' where `mnu_cod`='MNU92500';



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




/* Actualizacion de la tabla de tasas */
truncate table `t00_tasa`;
insert into `t00_tasa` (`t00_cod_tasa`, `t00_nom_abre`, `t00_nom_lar`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('1','P. Gastos Func.','8.3','ad_fondoe','2013-10-22 18:57:38','ad_fondoe','2013-11-07 22:02:13',NULL);
insert into `t00_tasa` (`t00_cod_tasa`, `t00_nom_abre`, `t00_nom_lar`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('2','P.  Linea Base','4','ad_fondoe','2013-10-22 18:59:15',NULL,NULL,NULL);
insert into `t00_tasa` (`t00_cod_tasa`, `t00_nom_abre`, `t00_nom_lar`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('3','P. Imprevistos','1','ad_fondoe','2013-10-22 18:59:40','ad_fondoe','2013-11-04 16:14:26',NULL);
insert into `t00_tasa` (`t00_cod_tasa`, `t00_nom_abre`, `t00_nom_lar`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('4','Gastos de Supev. de Proyectos','1','ad_fondoe','2013-11-07 14:59:00',NULL,NULL,NULL);

