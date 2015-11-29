-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-022 Cronograma de Visita del Proyecto
-- RF-024 Reporte Informe de Gestor
-- --------------------------------------------------------------------------------


UPDATE `adm_reportes` SET `tit_rpt`='Reporte Cronograma de visitas del proyecto', `des_rpt`='Reporte Cronograma de visitas del proyecto' WHERE `cod_rpt`='28';

DELETE FROM `adm_reportes` WHERE cod_rpt = '86';
INSERT INTO `adm_reportes` (`cod_rpt`, `tit_rpt`, `url_rpt`, `url_param`, `estado`) VALUES ('86', '', '/test/sme/reportes/rpt_informe_preliminar.php', '/test/sme/reportes/filter_proy_ficha.php', '1');



DELETE FROM `adm_menus` WHERE mnu_cod = 'MNU5801';
INSERT INTO `adm_menus` (`mnu_cod`,`mnu_nomb`,`mnu_apli`,`mnu_link`,`mnu_target`,`mnu_parent`,`mnu_activo`,`mnu_class`,`mnu_sort`,`mnu_img`,`mnu_admin`) VALUES ('MNU5801','Informe Preliminar',1,'/test/sme/reportes/reportviewer.php?ReportID=86','_blank','MNU40000',1,'',0,'','0');

DELETE FROM `adm_menus_pagina` WHERE mnu_cod = 'MNU5801';
INSERT INTO `adm_menus_pagina` (`mnu_cod`,`pag_cod`,`pag_nomb`,`pag_link`,`flg_act`) VALUES ('MNU5801',1,'Informe Preliminar','/test/sme/reportes/reportviewer.php?ReportID=86','1');

DELETE FROM `adm_menus_perfil` WHERE mnu_cod = 'MNU5801';
INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU5801',13,'1','1','1','1','','2014-06-14 21:28:39');




