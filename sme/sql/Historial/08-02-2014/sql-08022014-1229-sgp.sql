/*
// -------------------------------------------------->
// AQ 2.0 [08-02-2014 12:29]
// Opciones del Menú 
// --------------------------------------------------<
*/
REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5100', 'V°B° Informe Financiero', 1, '/sgp/sme/proyectos/informes/inf_financ_list_vb.php', '_self', 'MNU40000', 1, NULL, 11, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5100', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5000', 'V°B° Informe Técnico', 1, '/sgp/sme/proyectos/informes/inf_mes_list_vb.php', '_self', 'MNU40000', 1, NULL, 12, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5000', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5200', 'V°B° Informe de Entregable', 1, '/sgp/sme/proyectos/informes/inf_entregable_list_vb.php', '_self', 'MNU40000', 1, NULL, 13, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5200', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU41100';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU41000';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU42000';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4500';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4800';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU42900';

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU36000', 'V°B° Informe Financiero', 1, '/sgp/sme/proyectos/informes/inf_financ_list_vb.php', '_self', 'MNU30000', 1, NULL, 5, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU36000', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU35000', 'V°B° Informe Técnico', 1, '/sgp/sme/proyectos/informes/inf_mes_list_vb.php', '_self', 'MNU30000', 1, NULL, 6, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU35000', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU37000', 'V°B° Informe de Entregable', 1, '/sgp/sme/proyectos/informes/inf_entregable_list_vb.php', '_self', 'MNU30000', 1, NULL, 7, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU37000', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());
