/* Registro de nuevos perfiles */
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('12','Supervisor de Proyectos','SP','ad_fondoe','2013-10-28 21:37:02',NULL,NULL);
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('13','Gestor de Proyectos','GP','ad_fondoe','2013-10-28 21:37:43',NULL,NULL);

/* Registro de nuevo usuario para perfil de Supervisor de Proyectos: sptest1 */
insert into `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) values('sptest1','12','Supervisor de proyectos 1','060f56b22231cf8f2c9224911450f215','sptest1@localhost.com','*','','1','ad_fondoe','2013-10-28 21:42:40',NULL,NULL,'1');

/* Registro de permisos al nuevo usuario de  Supervisor de Proyectos */
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU11000','12','1','1','1','0','ad_fondoe','2013-10-28 21:54:43');
insert into `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) values('MNU21000','12','1','1','1','0','ad_fondoe','2013-10-28 21:54:43');

