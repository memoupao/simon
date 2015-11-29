##################################################### >
# AQ 2.0 [07-11-2013 11:29]
# Inclusión de opción "Cronograma de Productos"
#
UPDATE adm_menus SET mnu_sort = 5 WHERE mnu_cod = "MNU23000";
UPDATE adm_menus SET mnu_sort = 6 WHERE mnu_cod = "MNU24000";
UPDATE adm_menus SET mnu_sort = 7 WHERE mnu_cod = "MNU25000";
UPDATE adm_menus SET mnu_sort = 8 WHERE mnu_cod = "MNU26000";
UPDATE adm_menus SET mnu_sort = 9 WHERE mnu_cod = "MNU27000";
UPDATE adm_menus SET mnu_sort = 10 WHERE mnu_cod = "MNU28000";
UPDATE adm_menus SET mnu_sort = 11 WHERE mnu_cod = "MNU28100";

INSERT INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU28200', 'Cronograma de Productos', 1, '/ctic/sme/proyectos/planifica/cronograma_producto.php', '_self', 'MNU20001', 1, '', 4, '', '0');

INSERT INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU28200', 1, '1', '1', '1', '1', 'ad_fondoe', NOW()),
('MNU28200', 2, '1', '1', '1', '1', 'ad_fondoe', NOW()),
('MNU28200', 3, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28200', 4, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28200', 5, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU28200', 6, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28200', 8, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28200', 9, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28200', 11, '1', '0', '0', '0', 'ad_fondoe', NOW());

##################################################### <