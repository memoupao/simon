-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-020 Reporte de Análisis Comparativo por Producto
-- --------------------------------------------------------------------------------


INSERT INTO `adm_reportes` (`tit_rpt`, `des_rpt`, `url_rpt`, `url_param`, `cat_rpt`, `estado`) VALUES ('Análisis Comparativo por Producto', 'Análisis Comparativo por Producto', '/pg/sme/reportes/rpt_bench_ope_prod.php', '/pg/sme/reportes/filter_matriz_prod.php', '3', '1');



INSERT INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_sort`, `mnu_admin`) VALUES ('MNU53001', 'Análisis Comparativo por Producto', '1', '/pg/sme/reportes/reportviewer.php?ReportID=85&cboConcurso=*', '_blank', 'MNU50000', '1', '3', '0');



INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',1,'1','0','0','0','amendoza','2014-06-03 23:09:59');
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',10,'1','0','0','0','amendoza','2014-06-03 23:10:27');
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',12,'1','0','0','0','amendoza','2014-06-03 23:11:49');
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',13,'1','0','0','0','amendoza','2014-06-03 23:11:10');
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',14,'1','0','0','0','amendoza','2014-06-03 23:10:48');
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',15,'1','0','0','0','amendoza','2014-06-03 23:11:35');
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU53001',16,'1','0','0','0','amendoza','2014-06-03 23:12:03');



