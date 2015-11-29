/*
// -------------------------------------------------->
// AQ 2.0 [18-12-2013 08:21]
// Usuarios RA
// --------------------------------------------------<
*/
REPLACE INTO `adm_usuarios` (`coduser`, `tipo_user`, `nom_user`, `clave_user`, `mail`, `t01_id_uni`, `t02_cod_proy`, `estado`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`, `est_audi`) VALUES
('ra', '15', 'RA', 'db26ee047a4c86fbd2fba73503feccb6', 'ra@localhost.com', '*', '*', '1', 'ad_fondoe', NOW(), NULL, NULL, '1'),
('raam', '15', 'Ana Mendoza RA', 'db26ee047a4c86fbd2fba73503feccb6', 'amendozasouza@gmail.com', '*', '*', '1', 'ad_fondoe', NOW(), NULL, NULL, '1'),
('rakm', '15', 'Kelly Miñano RA', 'db26ee047a4c86fbd2fba73503feccb6', 'kmv1281@gmail.com', '*', '*', '1', 'ad_fondoe', NOW(), NULL, NULL, '1');

/*
// -------------------------------------------------->
// AQ 2.0 [18-12-2013 08:21]
// Permisos RA
// --------------------------------------------------<
*/
REPLACE INTO `adm_menus_perfil` (`mnu_cod`, `per_cod`, `ver`, `nuevo`, `editar`, `eliminar`, `usu_crea`, `fch_crea`) VALUES
('MNU11000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU12000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU21000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU21100', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU21200', 15, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU21700', 15, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU22000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU23000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU23110', 15, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU23112', 15, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU23131', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU23132', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU23133', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU23134', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU24100', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU25000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU26000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU27000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU27100', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28101', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28102', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28103', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28104', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU28200', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU29000', 15, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU29100', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU32000', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU34000', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU41100', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU41110', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU41400', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU42200', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU42400', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU42600', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU42800', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU42900', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU4400', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU4500', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU4700', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU4800', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU4900', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU4910', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU52000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU53000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU55000', 15, '1', '0', '0', '0', 'ad_fondoe', NOW()),
('MNU61000', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU63000', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU64000', 15, '1', '1', '1', '0', 'ad_fondoe', NOW()),
('MNU71000', 15, '1', '0', '1', '0', 'ad_fondoe', NOW()),
('MNU72000', 15, '1', '0', '1', '0', 'ad_fondoe', NOW());
