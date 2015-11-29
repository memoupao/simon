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
