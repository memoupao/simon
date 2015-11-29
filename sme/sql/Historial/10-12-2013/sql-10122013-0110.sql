/*
// -------------------------------------------------->
// AQ 2.0 [10-12-2013 01:10]
// Tabla para la Programación de los Entregables 
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t02_entregable_act_ind`;

CREATE TABLE IF NOT EXISTS `t02_entregable_act_ind` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_act_ind` int(11) NOT NULL,
  `t02_anio` tinyint(4) NOT NULL,
  `t02_mes` tinyint(4) NOT NULL,
  `t09_cod_act_ind_val` DOUBLE NOT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`, `t02_anio`, `t02_mes`),
  KEY `FK_t02_entregable_act_ind` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`, `t09_cod_act_ind`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `t02_entregable_act_ind`
  ADD CONSTRAINT `t02_entregable_act_ind_ibfk_1` 
  FOREIGN KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`, `t09_cod_act_ind`) 
  REFERENCES `t09_act_ind` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`, `t09_cod_act_ind`) 
  ON DELETE CASCADE ON UPDATE CASCADE;
  
DROP TABLE IF EXISTS `t02_entregable_act_ind_car`;

CREATE TABLE IF NOT EXISTS `t02_entregable_act_ind_car` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) NOT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_act_ind` int(11) NOT NULL,
  `t09_cod_act_ind_car` int(11) NOT NULL,
  `t02_anio` tinyint(4) NOT NULL,
  `t02_mes` tinyint(4) NOT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`,`t02_anio`,`t02_mes`),
  KEY `FK_t02_entregable_act_ind_car` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `t02_entregable_act_ind_car`
  ADD CONSTRAINT `t02_entregable_act_ind_car_ibfk_1` 
  FOREIGN KEY (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`) 
  REFERENCES `t09_act_ind_car` (`t02_cod_proy`,`t02_version`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_act_ind`,`t09_cod_act_ind_car`) 
  ON DELETE CASCADE ON UPDATE CASCADE;
