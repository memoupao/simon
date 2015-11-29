/**********************************************
   sql-24102013-1510.sql
 **********************************************/
/*
Registro en los menus de nuevos mantenimintos
*/
##################################################### >
# AQ 2.0 [21-10-2013 17:42]
# Cambio de url a términos singulares.
# Ordenamiento alfabético de las nuevas opciones.
#
REPLACE INTO `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) VALUES
('MNU92300', 'Bancos', 1, '/sgp/Admin/man_concepto.php?item=banco', '_self', 'MNU91000', 1, '', 4, '', '1'),
('MNU92400', 'Monedas', 1, '/sgp/Admin/man_concepto.php?item=moneda', '_self', 'MNU91000', 1, '', 5, '', '1'),
('MNU92500', 'Tasas', 1, '/sgp/Admin/man_tasa.php', '_self', 'MNU91000', 1, '', 7, '', '1'),
('MNU92600', 'Lineas', 1, '/sgp/Admin/man_concepto.php?item=linea', '_self', 'MNU91000', 1, '', 6, '', '1'),
('MNU92700', 'Tipos de Cuenta', 1, '/sgp/Admin/man_concepto.php?item=tipo_cuenta', '_self', 'MNU91000', 1, '', 9, '', '1'),
('MNU92800', 'Tipos de Anexo', 1, '/sgp/Admin/man_concepto.php?item=tipo_anexo', '_self', 'MNU91000', 1, '', 8, '', '1');
##################################################### <

/**********************************************
   sql-07112013-1108-sgp.sql
 **********************************************/
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
('MNU28200', 'Cronograma de Productos', 1, '/sgp/sme/proyectos/planifica/cronograma_producto.php', '_self', 'MNU20001', 1, '', 4, '', '0');

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

/**********************************************
   sql-07112013-1207.sql
 **********************************************/
# Nuevo menu de parametros:
DELETE FROM adm_menus WHERE mnu_cod = 'MNU92900';
insert into `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) values('MNU92900','Parametros','1','/sgp/Admin/man_parametros.php','_self','MNU91000','1','','8','','1');

# actualizamos los ordenes de posicion:
update `adm_menus` set `mnu_cod`='MNU92700',`mnu_nomb`='Tipos de Cuenta',`mnu_apli`='1',`mnu_link`='/sgp/Admin/man_concepto.php?item=tipo_cuenta',`mnu_target`='_self',`mnu_parent`='MNU91000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='9',`mnu_img`='',`mnu_admin`='1' where `mnu_cod`='MNU92700';
update `adm_menus` set `mnu_cod`='MNU92800',`mnu_nomb`='Tipos de Anexo',`mnu_apli`='1',`mnu_link`='/sgp/Admin/man_concepto.php?item=tipo_anexo',`mnu_target`='_self',`mnu_parent`='MNU91000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='10',`mnu_img`='',`mnu_admin`='1' where `mnu_cod`='MNU92800';

# actualizamos el termino de tasas a tasas base:
update `adm_menus` set `mnu_cod`='MNU92500',`mnu_nomb`='Tasas Base',`mnu_apli`='1',`mnu_link`='/sgp/Admin/man_tasa.php',`mnu_target`='_self',`mnu_parent`='MNU91000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='7',`mnu_img`='',`mnu_admin`='1' where `mnu_cod`='MNU92500';

/**********************************************
   sql-10112013-2116.sql
 **********************************************/
/* Nuevo menu de mantenimiento de Productos Principales*/                
insert into `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) values('MNU91400','Productos Principales','1','/sgp/Admin/man_prodprinc_lista.php','_self','MNU91000','1','','2','','1');

/**********************************************
   sql-20112013-1718.sql
 **********************************************/
/*
// -------------------------------------------------->
// AQ 2.0 [20-11-2013 17:18]
// Tabla de Productos Entregables 
// --------------------------------------------------<
*/
UPDATE adm_menus SET mnu_link = '/sgp/sme/proyectos/planifica/cp_index.php' WHERE mnu_cod = 'MNU28200';

/**********************************************
   sql-21112013-1236.sql
 **********************************************/
/* Actualizacion de terminos: */
update `adm_menus` set `mnu_cod`='MNU4900',`mnu_nomb`='Control de Pago a Supervisores Externos',`mnu_apli`='1',`mnu_link`='/sgp/sme/monitoreofe/adm/ctrl_pag_me.php',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='9',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU4900';
update `adm_menus` set `mnu_cod`='MNU4910',`mnu_nomb`='Autorización de Giro de Cheques a S.E.',`mnu_apli`='1',`mnu_link`='/sgp/sme/monitoreofe/adm/auto_giro_chk_me.php',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='10',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU4910';
update `adm_menus` set `mnu_cod`='MNU52000',`mnu_nomb`='Matriz General de Supervisión',`mnu_apli`='1',`mnu_link`='#\' onclick=\'window.open(\"/sgp/sme/reportes/reportviewer.php?ReportID=1\",\"Matriz\",\"fullscreen,scrollbars\");return false;',`mnu_target`='_blank',`mnu_parent`='MNU50000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='2',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU52000';
update `adm_menus` set `mnu_cod`='MNU30000',`mnu_nomb`='Supervisores Externos',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='3',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU30000';
update `adm_menus` set `mnu_cod`='MNU40000',`mnu_nomb`='Gestores Fondoempleo',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='4',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU40000';
update `adm_menus` set `mnu_cod`='MNU41000',`mnu_nomb`='Supervisión Técnica',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='1',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU41000';
update `adm_menus` set `mnu_cod`='MNU42000',`mnu_nomb`='Supervisión Financiera',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='2',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU42000';
update `adm_menus` set `mnu_cod`='MNU50000',`mnu_nomb`='Información Gerencial',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='5',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU50000';

update `adm_menus` set `mnu_cod`='MNU52000',`mnu_nomb`='Matriz General de Supervisión',`mnu_apli`='1',`mnu_link`='#\' onclick=\'window.open(\"/sgp/sme/reportes/reportviewer.php?ReportID=1\",\"Matriz\",\"fullscreen,scrollbars\");return false;',`mnu_target`='_blank',`mnu_parent`='MNU50000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='2',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU52000';
update `adm_menus` set `mnu_cod`='MNU50000',`mnu_nomb`='Información Gerencial',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='5',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU50000';
update `adm_menus` set `mnu_cod`='MNU41000',`mnu_nomb`='Supervisión Técnica',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='1',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU41000';
update `adm_menus` set `mnu_cod`='MNU42000',`mnu_nomb`='Supervisión Financiera',`mnu_apli`='1',`mnu_link`='#',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='MenuBarItemSubmenu',`mnu_sort`='2',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU42000';
update `adm_menus` set `mnu_cod`='MNU4910',`mnu_nomb`='Autorización de Giro de Cheques a S.E.',`mnu_apli`='1',`mnu_link`='/sgp/sme/monitoreofe/adm/auto_giro_chk_me.php',`mnu_target`='_self',`mnu_parent`='MNU40000',`mnu_activo`='1',`mnu_class`='',`mnu_sort`='10',`mnu_img`='',`mnu_admin`='0' where `mnu_cod`='MNU4910';

/**********************************************
   sql-23112013-1106.sql
 **********************************************/
/*
 * Reporte Cronograma de Producto
 */
INSERT INTO adm_reportes (`cod_rpt` ,`tit_rpt` ,`des_rpt` ,`url_rpt` ,`url_param` ,`cat_rpt` ,`orden` ,`fch_crea` ,`usr_crea` ,`fch_actu` ,`usr_actu` ,`estado`)
VALUES (
'80', 'Cronograma de Productos', NULL , '/sgp/sme/reportes/rpt_cp.php', '/sgp/sme/reportes/filter_proy.php', '2', NULL , NULL, NULL , NULL , NULL , '1'
);