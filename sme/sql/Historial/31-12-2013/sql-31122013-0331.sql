/*
// -------------------------------------------------->
// AQ 2.0 [31-12-2013 03:31]
// Tabla para el registro de Avance de Indicadores 
// de Producto del Informe de Entregable
// --------------------------------------------------<
*/
DROP TABLE IF EXISTS `t09_entregable_ind_inf`;

CREATE TABLE IF NOT EXISTS `t09_entregable_ind_inf` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) DEFAULT NULL,
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_prod` int(11) NOT NULL,
  `t09_cod_prod_ind` int(11) NOT NULL,
  `t09_ind_anio` int(11) NOT NULL,
  `t09_ind_entregable` int(11) NOT NULL,
  `t09_ind_avanc` double DEFAULT NULL,
  `t09_descrip` TEXT DEFAULT NULL,
  `t09_logros` TEXT DEFAULT NULL,
  `t09_dificul` TEXT DEFAULT NULL,
  `t09_obs` TEXT DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t08_cod_comp`,`t09_cod_prod`,`t09_cod_prod_ind`,`t09_ind_anio`,`t09_ind_entregable`),
  KEY `fk_t09_entregable_ind` (`t02_cod_proy`,`t08_cod_comp`,`t09_cod_prod`,`t09_cod_prod_ind`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;