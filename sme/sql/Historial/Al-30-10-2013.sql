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
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('12','Supervisor de Proyectos','SP','ad_fondoe','2013-10-28 21:37:02',NULL,NULL);
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('13','Gestor de Proyectos','GP','ad_fondoe','2013-10-28 21:37:43',NULL,NULL);

/* Registro de nuevo usuario para perfil de Supervisor de Proyectos: sptest1 */
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('sptest1','12','Supervisor de proyectos 1','060f56b22231cf8f2c9224911450f215','sptest1@localhost.com','*','','1','ad_fondoe','2013-10-28 21:42:40',NULL,NULL,'1');

/* Registro de permisos al nuevo usuario de  Supervisor de Proyectos */
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','12','1','1','1','0','ad_fondoe','2013-10-28 21:54:43');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','12','1','1','1','0','ad_fondoe','2013-10-28 21:54:43');

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
   sql-30102013-1103.sql
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
-- Volcado de datos para la tabla `t09_act_car_ctrl`
--

INSERT INTO `t09_act_car_ctrl` (`t02_cod_proy`, `t02_version`, `t08_cod_comp`, `t09_cod_act`, `t09_cod_act_car`, `t09_car_anio`, `t09_car_mes`, `t09_car_ctrl`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) VALUES
('C-09-38', 1, 1, 1, 1, 1, 4, 1, 'ADRA', '2013-06-25 11:19:07', NULL, NULL, '1'),
('C-09-38', 1, 1, 1, 2, 1, 3, 1, 'ADRA', '2013-06-24 17:24:19', NULL, NULL, '1'),
('C-09-38', 1, 1, 1, 2, 1, 6, 1, 'ADRA', '2013-06-24 17:24:19', NULL, NULL, '1'),
('C-09-38', 1, 1, 1, 2, 1, 9, 1, 'ADRA', '2013-06-24 17:24:19', NULL, NULL, '1'),
('C-09-38', 1, 1, 1, 2, 1, 12, 1, 'ADRA', '2013-06-24 17:24:19', NULL, NULL, '1'),
('C-09-38', 1, 1, 1, 3, 1, 9, 1, 'ADRA', '2013-06-24 17:26:35', NULL, NULL, '1'),
('C-09-38', 1, 1, 1, 3, 2, 9, 1, 'ADRA', '2013-06-24 17:26:35', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 1, 1, 1, 1, 'ADRA', '2013-06-24 17:30:29', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 2, 1, 2, 1, 'ADRA', '2013-06-24 17:32:17', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 3, 1, 2, 1, 'ADRA', '2013-06-24 17:33:49', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 3, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 4, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 5, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 6, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 7, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 8, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 9, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 10, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 11, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 1, 12, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 1, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 2, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 3, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 4, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 5, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 6, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 7, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 2, 4, 2, 8, 1, 'ADRA', '2013-06-24 17:36:12', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 1, 1, 6, 1, 'ADRA', '2013-06-24 17:39:10', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 1, 1, 9, 1, 'ADRA', '2013-06-24 17:39:10', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 1, 1, 12, 1, 'ADRA', '2013-06-24 17:39:10', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 2, 1, 6, 1, 'ADRA', '2013-06-24 17:40:38', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 2, 1, 9, 1, 'ADRA', '2013-06-24 17:40:38', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 2, 1, 12, 1, 'ADRA', '2013-06-24 17:40:38', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 3, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 4, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 5, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 6, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 7, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 8, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 9, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 10, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 11, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 1, 12, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 1, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 2, NULL, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 3, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 4, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 5, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 6, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 7, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 8, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 9, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 3, 3, 2, 10, 1, 'ADRA', '2013-06-24 17:43:40', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 1, 1, 6, 1, 'ADRA', '2013-06-24 17:45:15', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 1, 1, 9, 1, 'ADRA', '2013-06-24 17:45:15', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 1, 1, 12, 1, 'ADRA', '2013-06-24 17:45:15', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 2, 1, 6, 1, 'ADRA', '2013-06-24 17:47:15', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 2, 1, 9, 1, 'ADRA', '2013-06-24 17:47:15', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 2, 1, 12, 1, 'ADRA', '2013-06-24 17:47:15', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 3, 1, 5, 1, 'ADRA', '2013-06-24 17:48:28', NULL, NULL, '1'),
('C-09-38', 1, 1, 4, 4, 1, 4, 1, 'ADRA', '2013-06-24 17:49:42', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 1, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 2, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 3, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 4, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 5, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 6, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 7, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 8, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 9, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 10, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 11, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 1, 12, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 1, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 2, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 3, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 4, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 5, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 6, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 7, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 8, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 9, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 10, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 11, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 1, 2, 12, 1, 'ADRA', '2013-06-24 17:52:44', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 1, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 2, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 3, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 4, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 5, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 6, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 7, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 8, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 9, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 10, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 11, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 1, 12, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 1, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 2, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 3, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 4, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 5, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 6, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 7, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 8, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 9, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 10, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 11, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 2, 2, 12, 1, 'ADRA', '2013-06-24 17:55:33', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 1, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 2, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 3, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 4, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 5, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 6, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 7, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 8, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 9, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 10, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 11, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 1, 12, 1, 'ADRA', '2013-06-24 17:57:13', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 1, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 2, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 3, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 4, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 5, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 6, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 7, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 8, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 9, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 10, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 11, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 1, 5, 3, 2, 12, 1, 'ADRA', '2013-06-24 17:57:14', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 1, 1, 2, 1, 'ADRA', '2013-06-24 18:04:54', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 2, 1, 5, 1, 'ADRA', '2013-06-24 18:06:33', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 2, 1, 8, 1, 'ADRA', '2013-06-24 18:06:33', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 2, 1, 11, 1, 'ADRA', '2013-06-24 18:06:33', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 2, 2, 2, 1, 'ADRA', '2013-06-24 18:06:33', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 2, 2, 5, 1, 'ADRA', '2013-06-24 18:06:33', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 3, 1, 6, 1, 'ADRA', '2013-06-24 18:08:52', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 3, 1, 9, 1, 'ADRA', '2013-06-24 18:08:52', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 3, 1, 12, 1, 'ADRA', '2013-06-24 18:08:52', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 3, 2, 3, 1, 'ADRA', '2013-06-24 18:08:52', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 3, 2, 6, 1, 'ADRA', '2013-06-24 18:08:52', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 4, 1, 7, 1, 'ADRA', '2013-06-24 18:10:43', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 4, 1, 10, 1, 'ADRA', '2013-06-24 18:10:43', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 4, 2, 1, 1, 'ADRA', '2013-06-24 18:10:43', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 4, 2, 4, 1, 'ADRA', '2013-06-24 18:10:43', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 4, 2, 7, 1, 'ADRA', '2013-06-24 18:10:43', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 5, 1, 8, 1, 'ADRA', '2013-06-24 18:12:24', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 5, 1, 11, 1, 'ADRA', '2013-06-24 18:12:24', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 5, 2, 2, 1, 'ADRA', '2013-06-24 18:12:24', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 5, 2, 5, 1, 'ADRA', '2013-06-24 18:12:24', NULL, NULL, '1'),
('C-09-38', 1, 2, 1, 5, 2, 8, 1, 'ADRA', '2013-06-24 18:12:24', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 4, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 5, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 6, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 7, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 8, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 9, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 10, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 11, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 1, 12, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 1, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 2, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 3, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 4, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 5, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 6, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 7, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 8, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 9, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 10, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 11, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 1, 2, 12, 1, 'ADRA', '2013-06-24 18:21:03', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 4, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 5, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 6, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 7, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 8, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 9, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 10, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 11, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 1, 12, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 1, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 2, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 3, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 4, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 5, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 6, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 7, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 8, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 9, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 10, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 11, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 2, 2, 12, 1, 'ADRA', '2013-06-24 18:26:28', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 5, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 6, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 7, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 8, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 9, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 10, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 11, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 1, 12, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 1, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 2, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 3, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 4, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 5, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 6, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 7, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 8, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 9, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 10, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 11, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 3, 2, 12, 1, 'ADRA', '2013-06-24 19:11:44', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 1, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 2, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 3, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 4, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 5, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 6, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 7, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 8, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 9, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 10, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 11, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 1, 12, 1, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 1, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 2, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 3, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 4, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 5, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 6, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 7, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 8, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 9, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 10, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 11, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 4, 2, 12, NULL, 'ADRA', '2013-06-24 18:41:56', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 2, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 3, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 4, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 5, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 6, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 7, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 8, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 9, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 10, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 1, 11, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 2, 1, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 2, 3, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 2, 5, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 2, 7, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 2, 9, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 2, 5, 2, 11, 1, 'ADRA', '2013-06-24 18:33:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 1, 1, 3, 1, 'ADRA', '2013-06-24 18:35:41', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 2, 1, 4, 1, 'ADRA', '2013-06-24 18:36:42', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 3, 1, 3, 1, 'ADRA', '2013-06-24 18:38:00', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 3, 1, 4, 1, 'ADRA', '2013-06-24 18:38:00', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 3, 1, 5, 1, 'ADRA', '2013-06-24 18:38:00', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 1, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 2, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 3, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 4, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 5, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 6, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 7, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 8, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 9, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 10, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 11, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 1, 12, 1, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 1, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 2, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 3, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 4, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 5, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 6, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 7, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 8, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 9, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 10, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 11, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 4, 2, 12, NULL, 'ADRA', '2013-06-24 18:40:25', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 1, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 2, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 3, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 4, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 5, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 6, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 7, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 8, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 9, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 10, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 11, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 1, 12, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 1, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 2, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 3, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 4, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 5, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 6, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 7, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 8, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 9, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 10, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 11, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 3, 5, 2, 12, NULL, 'ADRA', '2013-06-24 18:44:10', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 1, 1, 4, 1, 'ADRA', '2013-06-24 18:46:07', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 2, 1, 4, 1, 'ADRA', '2013-06-24 19:09:00', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 5, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 6, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 7, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 8, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 9, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 10, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 11, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 1, 12, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 1, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 2, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 3, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 4, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 5, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 6, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 7, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 8, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 9, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 10, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 11, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 4, 3, 2, 12, 1, 'ADRA', '2013-06-24 19:11:13', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 1, 1, 4, 1, 'ADRA', '2013-06-24 19:13:31', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 2, 1, 3, 1, 'ADRA', '2013-06-24 19:14:18', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 5, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 6, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 7, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 8, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 9, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 10, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 11, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 1, 12, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 1, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 2, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 3, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 4, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 5, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 6, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 7, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 8, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 9, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 10, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 11, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 2, 5, 3, 2, 12, 1, 'ADRA', '2013-06-24 19:15:59', NULL, NULL, '1'),
('C-09-38', 1, 3, 1, 1, 1, 2, 1, 'ADRA', '2013-06-24 19:19:07', NULL, NULL, '1'),
('C-09-38', 1, 3, 1, 2, 1, 4, 1, 'ADRA', '2013-06-24 19:19:59', NULL, NULL, '1'),
('C-09-38', 1, 3, 1, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:20:51', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 1, 1, 4, 1, 'ADRA', '2013-06-24 19:21:59', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 1, 4, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 1, 6, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 1, 8, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 1, 10, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 1, 12, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 2, 2, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 2, 4, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 2, 6, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 2, 8, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 2, 2, 10, 1, 'ADRA', '2013-06-24 19:23:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 1, 6, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 1, 8, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 1, 10, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 1, 12, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 2, 2, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 2, 4, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 2, 6, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 2, 8, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 3, 2, 10, 1, 'ADRA', '2013-06-24 19:24:47', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 1, 4, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 1, 6, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 1, 8, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 1, 10, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 1, 12, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 2, 2, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 2, 4, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 2, 6, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 2, 8, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 2, 4, 2, 10, 1, 'ADRA', '2013-06-24 19:25:39', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 1, 1, 4, 1, 'ADRA', '2013-06-25 10:56:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 1, 1, 6, 1, 'ADRA', '2013-06-25 10:56:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 1, 1, 8, 1, 'ADRA', '2013-06-25 10:56:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 1, 1, 10, 1, 'ADRA', '2013-06-25 10:56:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 1, 1, 12, 1, 'ADRA', '2013-06-25 10:56:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 2, 1, 7, 1, 'ADRA', '2013-06-24 19:27:37', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:28:35', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 4, 1, 4, 1, 'ADRA', '2013-06-24 19:29:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 3, 5, 1, 4, 1, 'ADRA', '2013-06-24 19:30:17', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 1, 1, 7, 1, 'ADRA', '2013-06-24 19:31:38', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 1, 1, 10, 1, 'ADRA', '2013-06-24 19:31:38', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 2, 1, 4, 1, 'ADRA', '2013-06-24 19:32:53', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 2, 1, 10, 1, 'ADRA', '2013-06-24 19:32:53', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 2, 2, 4, 1, 'ADRA', '2013-06-24 19:32:53', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 2, 2, 9, 1, 'ADRA', '2013-06-24 19:32:53', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 3, 1, 6, 1, 'ADRA', '2013-06-24 19:33:43', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 3, 2, 1, 1, 'ADRA', '2013-06-24 19:33:44', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 4, 1, 3, 1, 'ADRA', '2013-06-24 19:35:01', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 1, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 2, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 3, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 4, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 5, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 6, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 7, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 8, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 9, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 10, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 11, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 1, 12, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 1, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 2, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 3, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 4, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 5, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 6, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 7, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 8, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 9, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 10, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 11, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 4, 5, 2, 12, NULL, 'ADRA', '2013-06-24 19:36:29', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 1, 1, 4, 1, 'ADRA', '2013-06-24 19:39:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 1, 1, 6, 1, 'ADRA', '2013-06-24 19:39:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 1, 1, 8, 1, 'ADRA', '2013-06-24 19:39:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 1, 1, 10, 1, 'ADRA', '2013-06-24 19:39:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 1, 1, 12, 1, 'ADRA', '2013-06-24 19:39:36', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 2, 1, 8, 1, 'ADRA', '2013-06-24 19:40:24', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 1, 4, 1, 'ADRA', '2013-06-24 19:41:32', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 1, 6, 1, 'ADRA', '2013-06-24 19:41:32', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 1, 8, 1, 'ADRA', '2013-06-24 19:41:32', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 1, 10, 1, 'ADRA', '2013-06-24 19:41:32', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 1, 12, 1, 'ADRA', '2013-06-24 19:41:32', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 2, 2, 1, 'ADRA', '2013-06-24 19:41:33', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 2, 4, 1, 'ADRA', '2013-06-24 19:41:33', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 2, 6, 1, 'ADRA', '2013-06-24 19:41:33', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 2, 8, 1, 'ADRA', '2013-06-24 19:41:33', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 3, 2, 10, 1, 'ADRA', '2013-06-24 19:41:33', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 4, 1, 5, 1, 'ADRA', '2013-06-24 19:42:21', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 4, 1, 11, 1, 'ADRA', '2013-06-24 19:42:21', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 5, 1, 6, NULL, 'ADRA', '2013-06-24 19:44:16', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 5, 1, 12, NULL, 'ADRA', '2013-06-24 19:44:16', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 5, 2, 6, NULL, 'ADRA', '2013-06-24 19:44:16', NULL, NULL, '1'),
('C-09-38', 1, 3, 5, 5, 2, 11, NULL, 'ADRA', '2013-06-24 19:44:16', NULL, NULL, '1'),
('C-10-100', 1, 1, 1, 1, 1, 1, 1, 'ADRA Prueb', '2012-08-26 17:10:44', NULL, NULL, '1'),
('C-10-100', 1, 1, 1, 1, 1, 4, 1, 'ADRA Prueb', '2012-08-26 17:10:44', NULL, NULL, '1'),
('C-10-100', 1, 1, 1, 1, 1, 7, 1, 'ADRA Prueb', '2012-08-26 17:10:44', NULL, NULL, '1'),
('C-10-100', 2, 1, 1, 1, 1, 1, 1, 'ad_fondoe', '2012-08-28 15:38:20', NULL, NULL, '1'),
('C-10-100', 2, 1, 1, 1, 1, 4, 1, 'ad_fondoe', '2012-08-28 15:38:20', NULL, NULL, '1'),
('C-10-100', 2, 1, 1, 1, 1, 7, 1, 'ad_fondoe', '2012-08-28 15:38:20', NULL, NULL, '1'),
('C-10-555', 1, 1, 1, 1, 1, 4, 1, 'ad_fondoe', '2012-04-23 15:33:31', NULL, NULL, '1'),
('C-10-555', 1, 1, 1, 1, 2, 5, 1, 'ad_fondoe', '2012-04-23 15:33:31', NULL, NULL, '1'),
('C-10-555', 2, 1, 1, 1, 1, 4, 1, 'paul_ej', '2012-04-29 09:57:14', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 1, 1, 6, NULL, 'ADRA 1', '2012-01-15 14:54:30', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 1, 1, 10, NULL, 'ADRA 1', '2012-01-15 14:54:30', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 1, 2, 4, NULL, 'ADRA 1', '2012-01-15 14:54:30', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 1, 2, 9, NULL, 'ADRA 1', '2012-01-15 14:54:30', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 1, 3, 5, NULL, 'ADRA 1', '2012-01-15 14:54:30', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 1, 3, 10, NULL, 'ADRA 1', '2012-01-15 14:54:30', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 2, 1, 9, NULL, 'ADRA 1', '2012-01-15 14:17:39', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 2, 2, 8, NULL, 'ADRA 1', '2012-01-15 14:17:39', NULL, NULL, '1'),
('C-10-90', 1, 1, 1, 2, 3, 10, NULL, 'ADRA 1', '2012-01-15 14:17:39', NULL, NULL, '1'),
('C-10-90', 1, 2, 1, 1, 1, 7, NULL, 'ADRA 1', '2012-01-15 14:23:59', NULL, NULL, '1'),
('C-10-90', 1, 2, 1, 1, 2, 8, NULL, 'ADRA 1', '2012-01-15 14:23:59', NULL, NULL, '1'),
('C-10-90', 1, 2, 1, 1, 3, 10, NULL, 'ADRA 1', '2012-01-15 14:24:00', NULL, NULL, '1'),
('C-10-90', 1, 2, 2, 1, 1, 4, 1, 'ADRA 1', '2012-01-15 14:28:21', NULL, NULL, '1'),
('C-10-90', 1, 2, 2, 1, 2, 6, 1, 'ADRA 1', '2012-01-15 14:28:21', NULL, NULL, '1'),
('C-10-90', 1, 2, 2, 1, 3, 8, 1, 'ADRA 1', '2012-01-15 14:28:22', NULL, NULL, '1'),
('C-10-90', 1, 2, 2, 2, 1, 11, NULL, 'ADRA 1', '2012-01-15 14:37:36', NULL, NULL, '1'),
('C-10-90', 1, 2, 2, 2, 2, 7, NULL, 'ADRA 1', '2012-01-15 14:37:36', NULL, NULL, '1'),
('C-10-90', 1, 2, 2, 2, 3, 11, NULL, 'ADRA 1', '2012-01-15 14:37:36', NULL, NULL, '1'),
('C-10-90', 2, 1, 1, 1, 1, 6, NULL, 'ADRA 1', '2012-01-15 17:54:44', NULL, NULL, '1'),
('C-10-90', 2, 1, 1, 1, 1, 10, NULL, 'ADRA 1', '2012-01-15 17:54:44', NULL, NULL, '1'),
('C-10-90', 2, 1, 1, 2, 1, 9, NULL, 'ADRA 1', '2012-01-15 17:54:44', NULL, NULL, '1'),
('C-10-90', 2, 2, 1, 1, 1, 7, NULL, 'ADRA 1', '2012-01-15 17:54:44', NULL, NULL, '1'),
('C-10-90', 2, 2, 2, 1, 1, 4, 1, 'ADRA 1', '2012-01-15 17:54:44', NULL, NULL, '1'),
('C-10-90', 2, 2, 2, 2, 1, 11, NULL, 'ADRA 1', '2012-01-15 17:54:44', NULL, NULL, '1');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `t09_act_car_ctrl`
--
ALTER TABLE `t09_act_car_ctrl`
  ADD CONSTRAINT `t09_act_car_ctrl_ibfk_1` FOREIGN KEY (`t02_cod_proy`, `t02_version`, `t08_cod_comp`, `t09_cod_act`, `t09_cod_act_car`) REFERENCES `t09_act_car` (`t02_cod_proy`, `t02_version`, `t08_cod_comp`, `t09_cod_act`, `t09_cod_act_car`) ON DELETE CASCADE ON UPDATE CASCADE;

/**********************************************
   sql-30102013-1656.sql
 **********************************************/
  # Nuevo Perfil de Gerente de Gestion de Proyectos.
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('14','Gerente de Gestión de Proyectos','GGP','ad_fondoe','2013-10-30 17:21:44',NULL,NULL);

# Limpia de datos ya registrados (datos de pruebas).
DELETE FROM  adm_menus_perfil where per_cod='12';

# Registro de permisos de perfiles.
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','2','1','1','1','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','2','1','1','1','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU72000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','2','1','1','1','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','2','1','0','0','0','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','2','1','1','0','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','2','1','1','1','1','ad_fondoe','2013-10-30 17:10:34');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','4','1','1','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU72000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','4','1','0','0','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','4','1','1','1','1','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','4','1','0','1','0','ad_fondoe','2013-10-30 17:18:11');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','7','1','1','1','1','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','7','1','0','0','0','ad_fondoe','2013-10-30 17:15:20');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','12','0','1','0','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU64000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','12','1','1','1','0','ad_fondoe','2013-10-30 16:52:09');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU64000','13','1','1','0','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','13','0','0','0','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','13','1','1','1','0','ad_fondoe','2013-10-30 17:05:18');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU12000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU61000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU24100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU55000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU26000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU71000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU27100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU64000','14','1','1','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU63000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28102','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU22000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23132','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23134','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4900','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU34000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4800','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21200','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU41400','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4700','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42900','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4400','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23112','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU32000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU4910','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23131','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28104','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU53000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29100','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU23110','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU29000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU25000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU52000','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21700','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU28101','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU42600','14','1','0','0','0','ad_fondoe','2013-10-30 17:27:23');



# Nuevos usuarios con los nuevos perfiles creados:
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('ggptest1','14','Usuario de Prueba GGP 1','fb72c73629bf38484e741d6de2d0a4bf','ggptest1@localhost.com','*','','1','ad_fondoe','2013-10-30 17:33:07',NULL,NULL,'1');
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('gptest1','13','Usuario de Prueba GP 1','bb3697fa5349938b0a6639d85e627695','gptest1@locahost.com','*','','1','ad_fondoe','2013-10-30 17:34:18',NULL,NULL,'1');
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('ietest1','2','Usuario de Prueba IE 1','16006317e27c660903d0c79fa8b242bd','ietest1@localhost.com','2','','1','ad_fondoe','2013-10-30 17:35:27',NULL,NULL,'1');
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('ratest1','4','Usuario de Prueba RA 1','92b93c36ec6078ef3f8abecc4b0c23c2','ratest1@localhost.com','*','','1','ad_fondoe','2013-10-30 17:36:26',NULL,NULL,'1');
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('setest1','7','Usuario de Prueba SE 1','37fc6a866f73f511ff491b474f33eb03','setest1@localhost.com','2.2','','1','ad_fondoe','2013-10-30 17:38:01',NULL,NULL,'1');
