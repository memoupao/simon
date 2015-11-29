
/* El perfil del Admin ya no tiene acceso mas solo a los de mantenimiento: */

DELETE FROM adm_menus_perfil WHERE per_cod='1';

/* IE ahora no puede editar Datos generales del proyecto: */
update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-12-04 22:51:32' where `mnu_cod`='MNU21000' and `per_cod`='2';

/* GP ahora puede editar Marco Lógico, Cronograma de Productos y Cronograma de Actividades :*/
update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-12-04 23:00:56' where `mnu_cod`='MNU22000' and `per_cod`='13';
update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-12-04 23:00:56' where `mnu_cod`='MNU23000' and `per_cod`='13';
update `adm_menus_perfil` set `mnu_cod`='MNU28200',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-12-04 23:00:56' where `mnu_cod`='MNU28200' and `per_cod`='13';

