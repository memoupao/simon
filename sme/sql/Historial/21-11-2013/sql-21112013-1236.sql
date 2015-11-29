
/* Actualizacion del Cargo en contactos de instituciones: antes monitor externo ahora Supervisor Externo: */
update `adm_tablas_aux` set `codi`='60',`descrip`='Supervisor Externo',`abrev`='Superv. Externo',`cod_ext`=NULL,`flg_act`='1',`idTabla`='6',`orden`='1',`usu_crea`='',`fec_crea`='0000-00-00',`usu_actu`='',`fec_actu`='0000-00-00' where `codi`='60' and `orden`='1';


/* Actualizacion de terminos: */
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_del_institucion`$$

CREATE PROCEDURE `sp_del_institucion`( IN _t01_id_inst  INT  )
TRANX : BEGIN
  DECLARE _numrows  INT;
  declare _existe   int ;
  
  SELECT count(distinct t02_cod_proy) into _existe    
  FROM t02_dg_proy WHERE t01_id_inst=_t01_id_inst ;
  
  if _existe > 0 then
    SELECT 0 AS numrows, CONCAT('No se puede eliminar la Institución, es ejecutora de "',_existe,'" Proyectos.') as msg ;
    LEAVE TRANX;    
  end if ;  
  
  SELECT COUNT(DISTINCT t02_cod_proy) INTO _existe    
  FROM t02_fuente_finan WHERE t01_id_inst=_t01_id_inst ;
  
  IF _existe > 0 THEN
    SELECT 0 AS numrows, CONCAT('No se puede eliminar la Institución, es Co-Financiadora en "',_existe,'" Proyectos.') AS msg ;
    LEAVE TRANX;    
  END IF ;  
  
  SELECT COUNT(DISTINCT t02_cod_proy) INTO _existe    
  FROM t02_dg_proy WHERE  SUBSTRING_INDEX(t02_moni_ext,'.',1) = _t01_id_inst ;
  
  IF _existe > 0 THEN
## -------------------------------------------------->
## DA 2.0 [21-11-2013 14:04]
## Actualizacion de terminos: Monitora por Gestora:
    SELECT 0 AS numrows, CONCAT('No se puede eliminar la Institución, es Gestora en "',_existe,'" Proyectos.') AS msg ;
## --------------------------------------------------<
    LEAVE TRANX;    
  END IF ;   
  delete 
    FROM t01_dir_inst 
   WHERE t01_id_inst= _t01_id_inst ;
  
    SELECT ROW_COUNT() INTO _numrows;
    COMMIT;
    
    SELECT _numrows AS numrows, '' AS msg , _t01_id_inst AS codigo ;
       
  
END$$

DELIMITER ;



/* Perfil EVASIS Tambien pronto a Deshabilitarse: marcado con DEL 
Ojo todos los perfiles que tengan en la descripcion el termino DEL 
seran quitados o eliminados del sistema.
*/
update `adm_perfiles` set `per_cod`='6',`per_des`='Gestor de Proyecto - DEL',`per_abrev`='MT',`usr_crea`=NULL,`fch_crea`=NULL,`usr_actu`='ad_fondoe',`fch_actu`='2013-11-21 14:20:39' where `per_cod`='6';
update `adm_perfiles` set `per_cod`='11',`per_des`='EVASIS DEL',`per_abrev`='EVASIS',`usr_crea`='john.aima',`fch_crea`='2011-08-31 18:23:13',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-21 14:07:39' where `per_cod`='11';

/* Actualizacion de perfiles en los usuario: phuaman_mt y rmandujano y otros usuarios. */
update `adm_usuarios` set `coduser`='phuaman_mt',`tipo_user`='13',`nom_user`='Paul Huaman MT',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='diojes4@hotmail.com',`t01_id_uni`='3',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-10 20:46:26',`usr_actu`='ad_fondoe',`fch_actu`='2013-06-18 17:33:59',`est_audi`='1' where `coduser`='phuaman_mt';
update `adm_usuarios` set `coduser`='rmandujano',`tipo_user`='13',`nom_user`='rmandujano',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='rmandujano@fondoempleo.com.pe',`t01_id_uni`='2',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ldiaz1',`fch_crea`='2013-06-07 19:42:30',`usr_actu`='ad_fondoe',`fch_actu`='2013-06-18 22:42:18',`est_audi`='1' where `coduser`='rmandujano';

update `adm_usuarios` set `coduser`='rmuller',`tipo_user`='15',`nom_user`='carlos',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='lgdiazm@hotmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ldiaz1',`fch_crea`='2013-06-07 19:41:14',`usr_actu`='ldiaz1',`fch_actu`='2013-06-17 19:50:19',`est_audi`='1' where `coduser`='rmuller';
update `adm_usuarios` set `coduser`='cmt_julio',`tipo_user`='15',`nom_user`='julio',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='julio@gmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-07 22:47:49',`usr_actu`='ad_fondoe',`fch_actu`='2012-04-11 15:18:41',`est_audi`='1' where `coduser`='cmt_julio';
update `adm_usuarios` set `coduser`='phuama_cmt',`tipo_user`='15',`nom_user`='Paul Huaman CMT',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='diojes4@hotmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-10 23:21:06',`usr_actu`='ad_fondoe',`fch_actu`='2013-06-10 23:29:31',`est_audi`='1' where `coduser`='phuama_cmt';
update `adm_usuarios` set `coduser`='cmt_fondoe',`tipo_user`='15',`nom_user`='Coordinador Técnico',`clave_user`='202cb962ac59075b964b07152d234b70',`mail`='rosmerymc@hotmail.com',`t01_id_uni`='*',`t02_cod_proy`='*',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-06-07 21:20:55',`usr_actu`='ldiaz1',`fch_actu`='2013-06-17 19:50:02',`est_audi`='1' where `coduser`='cmt_fondoe';


/* Actualizacion de perfiles: */
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='12';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='13';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='14';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='15';
update `adm_menus_perfil` set `mnu_cod`='MNU11000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU11000' and `per_cod`='16';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='2';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='12';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='13';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='14',`ver`='1',`nuevo`='1',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='14';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='15';
update `adm_menus_perfil` set `mnu_cod`='MNU12000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU12000' and `per_cod`='16';
update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='2';
update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='13';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='14';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21000' and `per_cod`='16';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='12';
/*[02:55][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='14';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21100' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='2';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='14';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='15';
/*[02:55][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21200',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21200' and `per_cod`='16';
/*[02:55][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='2';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='13';
/*[02:55][ 171 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='14';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU21700',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU21700' and `per_cod`='16';
/*[02:55][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='2';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='12';
/*[02:55][ 110 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU22000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU22000' and `per_cod`='16';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='12';
/*[02:55][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='13';
/*[02:55][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='14';
/*[02:55][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='15';
/*[02:55][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23000' and `per_cod`='16';
/*[02:55][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='2';
/*[02:55][ 110 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='14';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='15';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23110',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23110' and `per_cod`='16';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='15';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23112',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23112' and `per_cod`='16';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='2';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='14';
/*[02:55][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='15';
/*[02:55][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23131',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23131' and `per_cod`='16';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='12';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='15';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23132',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23132' and `per_cod`='16';
/*[02:55][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='0',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='2';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='12';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='13';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='15';
/*[02:55][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU23134',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU23134' and `per_cod`='16';
/*[02:55][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='13';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='15';
/*[02:55][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU24100',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU24100' and `per_cod`='16';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='12';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='13';
/*[02:55][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='15';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU25000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU25000' and `per_cod`='16';
/*[02:55][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='2';
/*[02:55][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='12';
/*[02:55][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='13';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='14';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='15';
/*[02:55][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU26000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU26000' and `per_cod`='16';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='2';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='12';
/*[02:55][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='13';
/*[02:55][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='14';
/*[02:55][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='15';
/*[02:55][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27000' and `per_cod`='16';
/*[02:55][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='2';
/*[02:55][ 124 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='12';
/*[02:55][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='13';
/*[02:55][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='14';
/*[02:56][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU27100',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU27100' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='2';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='2';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='12';
/*[02:56][ 124 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='13';
/*[02:56][ 141 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='14';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='15';
/*[02:56][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28101',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28101' and `per_cod`='16';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='2';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='14';
/*[02:56][  31 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='15';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28102',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28102' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='2';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='12';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='13';
/*[02:56][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='15';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU28104',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU28104' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='12';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='15';
/*[02:56][  32 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='2';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='13';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='14';
/*[02:56][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='15';
/*[02:56][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU29100',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU29100' and `per_cod`='16';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='12';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='13';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU32000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU32000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='2';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='12';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='13';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU34000',`per_cod`='16',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU34000' and `per_cod`='16';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='2';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='13',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='13';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU41400',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU41400' and `per_cod`='16';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='12';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42600',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42600' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='12';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='13';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU42900',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU42900' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='2';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='13';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='14';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4400',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4400' and `per_cod`='16';
/*[02:56][  32 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='12';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='13';
/*[02:56][ 110 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='14';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4700',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4700' and `per_cod`='15';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='13';
/*[02:56][  31 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='14';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4800',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4800' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='12';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='13';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='14';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4900',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4900' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='12';
/*[02:56][  63 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='13';
/*[02:56][ 171 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU4910',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU4910' and `per_cod`='15';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='2';
/*[02:56][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='12',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='13';
/*[02:56][  94 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='15';
/*[02:56][ 171 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU52000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU52000' and `per_cod`='16';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='2';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='12',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='12';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='13';
/*[02:56][ 156 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='15';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU53000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU53000' and `per_cod`='16';
/*[02:56][  93 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='2';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='12',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='12';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='13';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='14';
/*[02:56][  46 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='15';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU55000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU55000' and `per_cod`='16';
/*[02:56][ 109 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='12';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='13';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='14';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='15';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU61000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU61000' and `per_cod`='16';
/*[02:56][  78 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='2',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='12';
/*[02:56][  16 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='13';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='14';
/*[02:56][ 125 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='15';
/*[02:56][  15 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU63000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU63000' and `per_cod`='16';
/*[02:56][  47 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU64000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU64000' and `per_cod`='12';
/*[02:56][ 140 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU64000',`per_cod`='14',`ver`='1',`nuevo`='1',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU64000' and `per_cod`='14';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU64000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU64000' and `per_cod`='15';
/*[02:56][ 172 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='2';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='12',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='12';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='13',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='13';
/*[02:56][   0 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='14',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='14';
/*[02:56][  62 ms]*/ update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='15',`ver`='1',`nuevo`='0',`editar`='1',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='15';
update `adm_menus_perfil` set `mnu_cod`='MNU71000',`per_cod`='16',`ver`='1',`nuevo`='0',`editar`='0',`eliminar`='0',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU71000' and `per_cod`='16';
update `adm_menus_perfil` set `mnu_cod`='MNU72000',`per_cod`='2',`ver`='1',`nuevo`='1',`editar`='1',`eliminar`='1',`usu_crea`='ad_fondoe',`fch_crea`='2013-11-21 14:54:21' where `mnu_cod`='MNU72000' and `per_cod`='2';






/* Mas longitud del link de menus: */
alter table `adm_menus` change `mnu_link` `mnu_link` varchar(250) character set utf8 collate utf8_general_ci NULL ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_menu`$$

CREATE  PROCEDURE `sp_upd_menu`(
			in _cod   varchar(8),
			in _nom   varchar(50) ,
			IN _link  varchar(250), 
			in _isparent char(1),
			in _parent varchar(10),
			IN _targ   VARCHAR(10),
			in _img    varchar(100),
			in _act    char(1),
			in _ord    int,
			in _mod    char(1))
BEGIN
  declare _class varchar(20);
  
   select (case when _isparent='1' then 'MenuBarItemSubmenu' else '' end )
   into  _class;
   
   UPDATE adm_menus 
      SET
	mnu_nomb = _nom , 
	mnu_link = _link, 
	mnu_target = _targ , 
	mnu_parent = _parent , 
	mnu_activo = _act , 
	mnu_class  = _class , 
	mnu_sort   = _ord , 
	mnu_img    = _img , 
	mnu_admin  = _mod
    WHERE mnu_cod  = _cod ;   
     
    SELECT ROW_COUNT() AS numrows, _cod AS codigo, '' AS msg ;
     
END$$

DELIMITER ;


DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_ins_menu`$$

CREATE PROCEDURE `sp_ins_menu`(
			in _cod   varchar(8),
			in _nom   varchar(50) ,
			IN _link  varchar(250), 
			in _isparent char(1),
			in _parent varchar(10),
			IN _targ   VARCHAR(10),
			in _img    varchar(100),
			in _act    char(1),
			in _ord    int,
			in _mod    char(1))
BEGIN
  DECLARE _msg    varchar(100);
  declare _existe int ;
  declare _rowcount int ;
  declare _class varchar(20);
  
  
  
  select COUNT(1)
  into _existe
  from adm_menus
  where mnu_cod = _cod ;
  
  if _existe > 0 then
    select concat('El Menu ', _nom, ' ya fue registrado...')
    into _msg ;
    SELECT 0 AS numrows, 0 AS codigo, _msg AS msg ;
    
  else
   /*
    select IFNULL(max(mnu_cod),0) + 1
      into _cod
      from adm_menus ; 
   */
   
   select (case when _isparent='1' then 'MenuBarItemSubmenu' else '' end )
   into  _class;
   
    INSERT INTO adm_menus 
	(mnu_cod, 
	 mnu_nomb, 
	 mnu_apli, 
	 mnu_link, 
	 mnu_target, 
	 mnu_parent, 
	 mnu_activo, 
	 mnu_class, 
	 mnu_sort, 
	 mnu_img, 
	 mnu_admin
	)
	VALUES
	( _cod, 
	  _nom, 
	  1, 
	  _link, 
	  _targ, 
	  _parent, 
	  _act, 
	  _class, 
	  _ord, 
	  _img, 
	  _mod
	);
    select ROW_COUNT() into _rowcount ;
    
    call sp_ins_menu_pagina(_cod, _nom, _link);
    
    SELECT _rowcount  AS numrows, _cod AS codigo, '' AS msg ;
     
  end if ;
END$$

DELIMITER ;