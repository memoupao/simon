DELETE FROM `adm_menus_perfil` WHERE mnu_cod IN('MNU5100', 'MNU5000', 'MNU5200', 'MNU35500', 'MNU35700', 'MNU35400', 'MNU35600', 'MNU35800') AND per_cod = 13;
DELETE FROM `adm_menus` WHERE mnu_cod IN('MNU5100', 'MNU5000', 'MNU5200', 'MNU35500', 'MNU35700', 'MNU35400', 'MNU35600', 'MNU35800');

DELETE FROM `adm_menus_perfil` WHERE mnu_cod IN('MNU36000', 'MNU35000', 'MNU37000', 'MNU35500', 'MNU35700', 'MNU35400', 'MNU35600', 'MNU35800') AND per_cod = 16;
DELETE FROM `adm_menus` WHERE mnu_cod IN('MNU36000', 'MNU35000', 'MNU37000', 'MNU35500', 'MNU35700', 'MNU35400', 'MNU35600', 'MNU35800');

/* Ejecución de Desembolsos */
REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU4699', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

/*
// -------------------------------------------------->
// AQ 2.0 [13-02-2014 11:25]
// Opciones del Menú 
// --------------------------------------------------<
*/
REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5100', 'Carta Fianza', 1, '/pg/sme/proyectos/anexos/carta_fianza.php', '_self', 'MNU40000', 1, NULL, 3, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5100', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5200', 'Informe de Supervisión', 1, '/pg/sme/proyectos/informes/inf_monext_list.php', '_self', 'MNU40000', 1, NULL, 4, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5200', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5300', 'V°B° POA Especificación Técnica', 1, '/pg/sme/proyectos/poa/poa_tec_list_vb.php', '_self', 'MNU40000', 1, NULL, 5, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5300', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5400', 'V°B° POA Especificación Financiera', 1, '/pg/sme/proyectos/poa/poa_fin_list_vb.php', '_self', 'MNU40000', 1, NULL, 6, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5400', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5500', 'V°B° Informe Mensual Financiero', 1, '/pg/sme/proyectos/informes/inf_financ_list_vb.php', '_self', 'MNU40000', 1, NULL, 7, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5500', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5600', 'V°B° Informe Mensual Técnico', 1, '/pg/sme/proyectos/informes/inf_mes_list_vb.php', '_self', 'MNU40000', 1, NULL, 8, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5600', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5700', 'V°B° Informe de Entregable', 1, '/pg/sme/proyectos/informes/inf_entregable_list_vb.php', '_self', 'MNU40000', 1, NULL, 9, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5700', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU5800', 'V°B° Informe de Supervisión', 1, '/pg/sme/proyectos/informes/inf_monext_list_vb.php', '_self', 'MNU40000', 1, NULL, 10, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU5800', 13, '1', '1', '1', '1', 'ad_fondoe', NOW());

UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU41100';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU41000';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU42000';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4500';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4800';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU42900';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4700';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4910';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4900';
UPDATE adm_menus SET mnu_activo = 0 WHERE mnu_cod = 'MNU4600';

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU35100', 'V°B° POA Especificación Técnica', 1, '/pg/sme/proyectos/poa/poa_tec_list_vb.php', '_self', 'MNU30000', 1, NULL, 5, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU35100', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU35200', 'V°B° POA Especificación Financiera', 1, '/pg/sme/proyectos/poa/poa_fin_list_vb.php', '_self', 'MNU30000', 1, NULL, 6, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU35200', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU35300', 'V°B° Informe Mensual Técnico', 1, '/pg/sme/proyectos/informes/inf_mes_list_vb.php', '_self', 'MNU30000', 1, NULL, 7, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU35300', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU35400', 'V°B° Informe Mensual Financiero', 1, '/pg/sme/proyectos/informes/inf_financ_list_vb.php', '_self', 'MNU30000', 1, NULL, 8, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU35400', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());

REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU35500', 'V°B° Informe de Entregable', 1, '/pg/sme/proyectos/informes/inf_entregable_list_vb.php', '_self', 'MNU30000', 1, NULL, 9, NULL, '0');

REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU35500', 16, '1', '1', '1', '1', 'ad_fondoe', NOW());