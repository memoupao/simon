/*
// -------------------------------------------------->
// AQ 2.0 [26-12-2013 08:45]
// Opción: Informe del Entregable
// --------------------------------------------------<
*/
REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU73000', 'Informe del Entregable', 1, '/sgp/sme/proyectos/informes/inf_entregable_list.php', '_self', 'MNU20000', 1, NULL, 13, NULL, '0');


/*
// -------------------------------------------------->
// AQ 2.0 [26-12-2013 08:58]
// Permisos para la opción: Informe del Entregable
// --------------------------------------------------<
*/
REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU73000', 2, '1', '1', '1', '1', 'ad_fondoe', NOW()),
('MNU73000', 3, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU73000', 4, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU73000', 5, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU73000', 6, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU73000', 8, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU73000', 9, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU73000', 12, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU73000', 13, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU73000', 15, '1', '0', '1', '0', 'ad_fondoe', NOW());

/*
// -------------------------------------------------->
// AQ 2.0 [26-12-2013 10:22]
// Enlace para la opción: Informe del Entregable
// --------------------------------------------------<
*/
INSERT INTO `adm_menus_pagina` (`mnu_cod`, `pag_cod`, `pag_nomb`, `pag_link`, `flg_act`) VALUES
('MNU73000', 1, 'inf_entregable_ind_prop.php', '/sgp/sme/proyectos/informes/inf_entregable_ind_prop.php', '1'),
('MNU73000', 2, 'inf_entregable_ind_act.php', '/sgp/sme/proyectos/informes/inf_entregable_ind_act.php', '1'),
('MNU73000', 3, 'inf_entregable_edit.php', '/sgp/sme/proyectos/informes/inf_entregable_edit.php', '1'),
('MNU73000', 4, 'inf_entregable_anx_foto.php', '/sgp/sme/proyectos/informes/inf_entregable_anx_foto.php', '1'),
('MNU73000', 5, 'inf_entregable_analisis.php', '/sgp/sme/proyectos/informes/inf_entregable_analisis.php', '1'),
('MNU73000', 6, 'inf_entregable_plan_capac.php', '/sgp/sme/proyectos/informes/inf_entregable_plan_capac.php', '1'),
('MNU73000', 7, 'inf_entregable_plan_at.php', '/sgp/sme/proyectos/informes/inf_entregable_plan_at.php', '1'),
('MNU73000', 8, 'inf_entregable_plan_cred.php', '/sgp/sme/proyectos/informes/inf_entregable_plan_cred.php', '1'),
('MNU73000', 9, 'inf_entregable_plan_otros.php', '/sgp/sme/proyectos/informes/inf_entregable_plan_otros.php', '1'),
('MNU73000', 10, 'inf_entregable_planes.php', '/sgp/sme/proyectos/informes/inf_entregable_planes.php', '1'),
('MNU73000', 11, 'inf_entregable_process.php', '/sgp/sme/proyectos/informes/inf_entregable_process.php', '1'),
('MNU73000', 12, 'inf_entregable_list.php', '/sgp/sme/proyectos/informes/inf_entregable_list.php', '1'),
('MNU73000', 13, 'inf_entregable_sub_act.php', '/sgp/sme/proyectos/informes/inf_entregable_sub_act.php', '1'),
('MNU73000', 14, 'inf_entregable_ind_comp.php', '/sgp/sme/proyectos/informes/inf_entregable_ind_comp.php', '1'),
('MNU73000', 15, 'inf_entregable_process.xml.php', '/sgp/sme/proyectos/informes/inf_entregable_process.xml.php', '1');