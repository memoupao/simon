/* actualización de perfil y usuario de responsable de area.*/

insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('15','Responsable de Area','RA','ad_fondoe','2013-11-02 15:54:04',NULL,NULL);
insert into `adm_perfiles` (`per_cod`, `per_des`, `per_abrev`, `usr_crea`, `fch_crea`, `usr_actu`, `fch_actu`) values('16','Supervisor Externo','SE','ad_fondoe','2013-11-02 16:21:51',NULL,NULL);

update `adm_perfiles` set `per_des`='Responsable de Área - DEL',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 15:53:45' where `per_cod`='4';
update `adm_usuarios` set `coduser`='ratest1',`tipo_user`='15',`nom_user`='Usuario de Prueba RA 1',`clave_user`='92b93c36ec6078ef3f8abecc4b0c23c2',`mail`='ratest1@localhost.com',`t01_id_uni`='*',`t02_cod_proy`='',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-10-30 17:36:26',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 15:58:55',`est_audi`='1' where `coduser`='ratest1';

update `adm_perfiles` set `per_cod`='3',`per_des`='Secretario Ejecutivo DEL',`per_abrev`='Sec.Ejec',`usr_crea`='john.aima',`fch_crea`=NULL,`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 16:22:48' where `per_cod`='3';
update `adm_perfiles` set `per_cod`='7',`per_des`='Supervisor Externo - DEL',`per_abrev`='ME',`usr_crea`=NULL,`fch_crea`=NULL,`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 16:19:44' where `per_cod`='7';
update `adm_usuarios` set `coduser`='setest1',`tipo_user`='16',`nom_user`='Usuario de Prueba SE 1',`clave_user`='37fc6a866f73f511ff491b474f33eb03',`mail`='setest1@localhost.com',`t01_id_uni`='*',`t02_cod_proy`='',`estado`='1',`usr_crea`='ad_fondoe',`fch_crea`='2013-10-30 17:38:01',`usr_actu`='ad_fondoe',`fch_actu`='2013-11-02 16:25:50',`est_audi`='1' where `coduser`='setest1';



