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
('MNU92300', 'Bancos', 1, '/ctic/Admin/man_concepto.php?item=banco', '_self', 'MNU91000', 1, '', 4, '', '1'),
('MNU92400', 'Monedas', 1, '/ctic/Admin/man_concepto.php?item=moneda', '_self', 'MNU91000', 1, '', 5, '', '1'),
('MNU92500', 'Tasas', 1, '/ctic/Admin/man_tasa.php', '_self', 'MNU91000', 1, '', 7, '', '1'),
('MNU92600', 'Lineas', 1, '/ctic/Admin/man_concepto.php?item=linea', '_self', 'MNU91000', 1, '', 6, '', '1'),
('MNU92700', 'Tipos de Cuenta', 1, '/ctic/Admin/man_concepto.php?item=tipo_cuenta', '_self', 'MNU91000', 1, '', 9, '', '1'),
('MNU92800', 'Tipos de Anexo', 1, '/ctic/Admin/man_concepto.php?item=tipo_anexo', '_self', 'MNU91000', 1, '', 8, '', '1');
##################################################### <