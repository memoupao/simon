DROP TABLE IF EXISTS `t09_act_car_ctrl`;
DROP TABLE IF EXISTS `t09_act_car`;

--
-- Estructura de tabla para la tabla `t09_act_ind_car`
--
DROP TABLE IF EXISTS `t09_act_ind_car`;


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
