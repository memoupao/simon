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