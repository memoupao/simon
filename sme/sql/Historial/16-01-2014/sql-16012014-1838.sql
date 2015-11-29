/*
// -------------------------------------------------->
// AQ 2.0 [14-01-2014 10:21]
// 
// --------------------------------------------------<
*/
/*REPLACE INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) VALUES
(280, 'Elaboración', 'Elaboración', '-', '1', 34, 1, 'ad_fondoe', NOW(), NULL, NULL),
(281, 'Revisión', 'Revisión', '-', '1', 34, 2, 'ad_fondoe', NOW(), NULL, NULL),
(282, 'Corrección', 'Corrección', '-', '1', 34, 3, 'ad_fondoe', NOW(), NULL, NULL),
(283, 'V°B°', 'V°B°', '-', '1', 34, 4, 'ad_fondoe', NOW(), NULL, NULL),
(284, 'Corrección RA', 'Corrección RA', '-', '1', 34, 5, 'ad_fondoe', NOW(), NULL, NULL),
(285, 'Aprobado', 'Aprobado', '-', '1', 34, 6, 'ad_fondoe', NOW(), NULL, NULL);*/
DROP TABLE IF EXISTS `t45_inf_mi_subact`;
DROP TABLE IF EXISTS `t45_inf_mi_anexos`;
DROP TABLE IF EXISTS `t45_inf_mi`;
DROP TRIGGER IF EXISTS `trg_upd_inf_mi`;

DROP TABLE IF EXISTS `t45_inf_si_act`;
DROP TABLE IF EXISTS `t45_inf_si_anexos`;
DROP TABLE IF EXISTS `t45_inf_si`;

CREATE TABLE IF NOT EXISTS `t45_inf_si` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` int(11) DEFAULT NULL COMMENT 'Año de Ejecución',
  `t02_entregable` int(11) NOT NULL COMMENT 'Entregable asociado',
  `t45_periodo` varchar(50) DEFAULT NULL,
  `t45_fch_pre` datetime DEFAULT NULL COMMENT 'Fecha de Presentacion del Informe',
  `t45_estado` int(11) DEFAULT NULL COMMENT 'Proceso Cerrado',
  `t45_intro` text COMMENT 'Introduccion',
  `t45_fuentes` text COMMENT 'Metodos y fuentes',
  `t45_crit_eva1` int(11) DEFAULT NULL COMMENT 'Limitaciones',
  `t45_crit_eva2` int(11) DEFAULT NULL COMMENT 'Factores Positivos',
  `t45_crit_eva3` int(11) DEFAULT NULL COMMENT 'Perspectivas',
  `t45_crit_eva4` int(11) DEFAULT NULL,
  `t45_crit_eva5` int(11) DEFAULT NULL,
  `t45_crit_eva6` int(11) DEFAULT NULL,
  `t45_crit_eva7` int(11) DEFAULT NULL,
  `t45_apro` bit(1) DEFAULT NULL,
  `t45_apro_fch` datetime DEFAULT NULL COMMENT 'Fecha de Aprobacion',
  `t45_avance` text,
  `t45_logros` text,
  `t45_dificul` text,
  `t45_reco_proy` text,
  `t45_reco_fe` text,
  `t45_califica` text,
  `t45_fec_ini_vis` datetime DEFAULT NULL COMMENT 'Fecha de Inicio de la Visita',
  `t45_fec_ter_vis` datetime DEFAULT NULL COMMENT 'Fecha de Termino de la Visita',
  `t45_obs` text,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`, `t02_anio`, `t02_entregable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de Informes de Supervisor Interno - Cabecera';

CREATE TABLE IF NOT EXISTS `t45_inf_si_anexos` (
  `t45_cod_anx` int(11) NOT NULL,
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` int(11) DEFAULT NULL COMMENT 'Año de Ejecución',
  `t02_entregable` int(11) NOT NULL COMMENT 'Entregable asociado',
  `t45_nom_file` varchar(50) DEFAULT NULL COMMENT 'Fecha de Presentación del Informe',
  `t45_url_file` varchar(100) DEFAULT NULL,
  `t45_desc_file` TEXT DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`, `t02_anio`, `t02_entregable`, `t45_cod_anx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de Documentos Anexados al Informe de Supervisión Interna Externa';

ALTER TABLE `t45_inf_si_anexos`
  ADD CONSTRAINT `t45_inf_si_anexos_ibfk` FOREIGN KEY (`t02_cod_proy`, `t02_anio`, `t02_entregable`) REFERENCES `t45_inf_si` (`t02_cod_proy`, `t02_anio`, `t02_entregable`) ON DELETE CASCADE ON UPDATE CASCADE;
  
/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 17:10]
// Observaciones de Indicadoress de Componente del Informe de Supervisión Interna
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t08_comp_ind_inf_me`;
DROP TABLE IF EXISTS `t08_comp_ind_inf_si`;
CREATE TABLE IF NOT EXISTS `t08_comp_ind_inf_si` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` int(11) DEFAULT NULL COMMENT 'Año de Ejecución',
  `t02_entregable` int(11) NOT NULL COMMENT 'Entregable asociado',
  `t08_cod_comp` int(11) NOT NULL,
  `t08_cod_comp_ind` int(11) NOT NULL,
  `t08_obs` text,
  `t08_avance` DOUBLE DEFAULT NULL COMMENT 'Avance Verificado por el Supervisor',
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t08_cod_comp_ind`,`t08_cod_comp`,`t02_cod_proy`,`t02_anio`,`t02_entregable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Avance de indicadores de componentes según Supervisor Interno';

/*
// -------------------------------------------------->
// AQ 2.0 [05-01-2014 17:10]
// Observaciones de Indicadores de Producto del Informe de Supervisión Interna
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t09_act_ind_inf_me`;
DROP TABLE IF EXISTS `t09_prod_ind_inf_si`;

CREATE TABLE IF NOT EXISTS `t09_prod_ind_inf_si` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` int(11) DEFAULT NULL COMMENT 'Año de Ejecución',
  `t02_entregable` int(11) NOT NULL COMMENT 'Entregable asociado',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_prod` int(11) NOT NULL,
  `t09_cod_prod_ind` int(11) NOT NULL,
  `t09_obs` TEXT DEFAULT NULL,
  `t09_avance` DOUBLE DEFAULT NULL COMMENT 'Avance Verificado por el Supervisor',
  `usr_crea` char(20) DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t08_cod_comp`,`t09_cod_prod`,`t09_cod_prod_ind`,`t02_anio`,`t02_entregable`),
  KEY `fk_t09_prod_ind_mtas` (`t02_cod_proy`,`t08_cod_comp`,`t09_cod_prod`,`t09_cod_prod_ind`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Avance de indicadores de productos según Supervisor Interno';

/*
// -------------------------------------------------->
// AQ 2.0 [12-01-2014 17:41]
// Observaciones de Actividades del Informe de Supervisión Interna
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t09_act_sub_mtas_inf_me`;
DROP TABLE IF EXISTS `t09_act_inf_si`;

CREATE TABLE IF NOT EXISTS `t09_act_inf_si` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` int(11) DEFAULT NULL COMMENT 'Año de Ejecución',
  `t02_entregable` int(11) NOT NULL COMMENT 'Entregable asociado',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_prod` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_obs` varchar(5000) DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t08_cod_comp`,`t09_cod_prod`,`t09_cod_act`,`t02_anio`,`t02_entregable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
// -------------------------------------------------->
// AQ 2.0 [14-01-2014 17:57]
// Observaciones de Características de Indicadores 
// de Producto del Informe de Supervisión Interna
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t09_prod_ind_car_inf_si`;

CREATE TABLE IF NOT EXISTS `t09_prod_ind_car_inf_si` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` int(11) DEFAULT NULL COMMENT 'Año de Ejecución',
  `t02_entregable` int(11) NOT NULL COMMENT 'Entregable asociado',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_prod` int(11) NOT NULL,
  `t09_cod_prod_ind` int(11) NOT NULL,
  `t09_cod_prod_ind_car` int(11) NOT NULL,
  `t09_obs` TEXT DEFAULT NULL,
  `t09_avance` DOUBLE DEFAULT NULL COMMENT 'Avance Verificado por el Supervisor',
  `usr_crea` char(20) DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`, `t08_cod_comp`, `t09_cod_prod`, `t09_cod_prod_ind`, `t09_cod_prod_ind_car`, `t02_anio`, `t02_entregable`),
  KEY `fk_t09_prod_ind_car` (`t02_cod_proy`,`t08_cod_comp`,`t09_cod_prod`,`t09_cod_prod_ind`, `t09_cod_prod_ind_car`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Avance de características de indicadores de productos según Supervisor Interno';
