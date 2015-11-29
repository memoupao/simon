/*
-- DA: Nuevo reporte de Reporte Avance Técnico - Financiero
-- Date: 2014-01-16 09:20
*/

INSERT INTO `adm_menus` (`mnu_cod`,`mnu_nomb`,`mnu_apli`,`mnu_link`,`mnu_target`,`mnu_parent`,`mnu_activo`,`mnu_class`,`mnu_sort`,`mnu_img`,`mnu_admin`) VALUES ('MNU56000','Reporte Avance Técnico - Financiero',1,'/pg/sme/reportes/reportviewer.php?ReportID=81','_blank','MNU50000',1,'',4,'','0');

INSERT INTO `adm_reportes` (`cod_rpt`, `tit_rpt`, `des_rpt`, `url_rpt`, `url_param`, `cat_rpt`, `orden`, `estado`) VALUES ('81', 'Reporte Avance Técnico - Financiero', 'Reporte Avance Técnico - Financiero', '/pg/sme/reportes/rpt_avance_tec_finan.php', '', '3', '', '1');


INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU56000',12,'1','0','0','0','ad_fondoe','2014-01-15 09:19:03');








