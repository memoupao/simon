
/* Nuevos datos de clasificacion de productos generales*/
insert into `adm_tablas`(`cod_tabla`,`nom_tabla`,`flg_act`) values ( '35','Sectores de Capacitacion','1');
insert into `adm_tablas`(`cod_tabla`,`nom_tabla`,`flg_act`) values ( '36','Sectores de Emprendimiento','1');


alter table `t02_dg_proy` drop column `t02_prod_principal`;
alter table `t02_dg_proy` add column `t02_sect_main` int(11) NULL COMMENT 'Sector - Clasificacion general' after `t02_est_imp`;
UPDATE t02_dg_proy SET t02_sect_main = 18;




DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_get_proyecto`$$

CREATE  PROCEDURE `sp_get_proyecto`(IN _proy VARCHAR(10), 
                   IN _vs   INT)
BEGIN
SELECT  p.t02_cod_proy, 
    p.t02_version, 
    p.t02_nro_exp, 
	
    p.t00_cod_linea, 
    p.t01_id_inst,
    p.t01_id_cta, 
    i.t01_sig_inst,
    p.t02_nom_proy, 
    DATE_FORMAT(p.t02_fch_apro,'%d/%m/%Y') AS apro, 
    DATE_FORMAT(p.t02_fch_ini,'%d/%m/%Y') AS ini, 
    DATE_FORMAT(p.t02_fch_ter,'%d/%m/%Y') AS fin , 
    p.t02_fin, 
    p.t02_pro, 
    p.t02_ben_obj, 
    p.t02_amb_geo, 
    p.t02_cre_fe,
    p.t02_pres_fe,
    p.t02_pres_eje,
    p.t02_pres_otro, 
    p.t02_pres_tot, 
    p.t02_moni_tema, 
    p.t02_moni_fina, 
    p.t02_moni_ext, 
    p.t02_sup_inst, 
    p.t02_dire_proy, 
    p.t02_ciud_proy, 
    p.t02_tele_proy, 
    p.t02_fax_proy, 
    p.t02_mail_proy,
    DATE_FORMAT((CASE WHEN p.t02_fch_isc =0 THEN NULL ELSE p.t02_fch_isc END),'%d/%m/%Y') AS isc,
    DATE_FORMAT(p.t02_fch_ire,'%d/%m/%Y') AS ire, 
    DATE_FORMAT(p.t02_fch_tre,'%d/%m/%Y') AS tre, 
    DATE_FORMAT((CASE WHEN p.t02_fch_tam =0 THEN NULL ELSE p.t02_fch_tam END),'%d/%m/%Y') AS tam,
    p.t02_num_mes AS mes,
    p.t02_num_mes_amp,
    p.t02_sect_main,
    p.t02_sect_prod,
    p.t02_subsect_prod,
    p.t02_prod_promovido,
    p.t02_estado, 
    i.t01_web_inst,
    cta.t01_id_cta,
    cta.t02_nom_benef,
    p.usr_crea, 
    p.fch_crea, 
    p.usr_actu, 
    p.fch_actu, 
    p.est_audi,
    p.env_rev,
    apr.t02_vb_proy, 
    apr.t02_aprob_proy, 
    apr.t02_fch_vb_proy, 
    apr.t02_fch_aprob_proy, 
    apr.t02_obs_vb_proy, 
    apr.t02_obs_aprob_proy, 
    apr.t02_aprob_ml, 
    apr.t02_aprob_cro, 
    apr.t02_aprob_pre, 
    apr.t02_fch_ml, 
    apr.t02_fch_cro, 
    apr.t02_fch_pre, 
    apr.t02_aprob_ml_mon, 
    apr.t02_aprob_cro_mon, 
    apr.t02_aprob_pre_mon, 
    apr.t02_obs_ml, 
    apr.t02_obs_cro, 
    apr.t02_obs_pre, 
    apr.t02_fch_ml_mon, 
    apr.t02_fch_cro_mon, 
    apr.t02_fch_pre_mon,
    
    tp.t02_gratificacion,
    tp.t02_porc_cts,
    tp.t02_porc_ess,
    tp.t02_porc_gast_func,
    tp.t02_porc_linea_base,
    tp.t02_porc_imprev,
    tp.t02_proc_gast_superv
FROM       t02_dg_proy p
LEFT JOIN t01_dir_inst i ON (p.t01_id_inst=i.t01_id_inst)
LEFT JOIN t02_proy_ctas cta ON (p.t02_cod_proy=cta.t02_cod_proy AND cta.est_audi=1)
LEFT JOIN t02_aprob_proy apr ON(p.t02_cod_proy=apr.t02_cod_proy)
LEFT JOIN t02_tasas_proy tp ON(p.t02_cod_proy=tp.t02_cod_proy AND p.t02_version = tp.t02_version)
WHERE p.t02_cod_proy = _proy 
AND p.t02_version=_vs ;     
END$$

DELIMITER ;



drop table `sgp`.`adm_tablas_aux3`;



insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('288','Capacitaci�n','Capacitaci�n','','1','35','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('289','Capacitaci�n e inserci�n','Capac. e Inserc','','1','35','2','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('290','Otros','Otros','','1','35','3','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('291','Gesti�n ambiental','Gest. Ambiental','','1','36','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('292','Emprendimientos','Emprendimientos','','1','36','2','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('293','Turismo','Turismo','','1','36','3','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('294','Artesan�a','Artesan�a','','1','36','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('295','Mypes','Mypes','','1','36','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('296','Agr�cola','Agr�cola','','1','18','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('297','Frut�cola','Frut�cola','','1','18','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('298','Gesti�n del agua','Gest. del agua','','1','18','1','ad_fondoe','2013-11-11',NULL,NULL);
insert into `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('299','Pecuario','Pecuario','','1','18','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');



insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('76','Papa','Papa','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('77','Algod�n','Algod�n','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('78','Flores','Flores','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('79','Frijol ','Frijol ','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('80','Hongos','Hongos','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('81','Ma�z','Ma�z','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('82','Or�gano','Or�gano','','1','18','296','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('83','Quinua','Quinua','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('84','Seguridad alimentaria','Seg. Aliment.','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('85','Tarwi','Tarwi','','1','18','296','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('86','Aguaymanto','Aguaymanto','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('87','Banano','Banano','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('88','Cacao','Cacao','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('89','Caf�','Caf�','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('90','Camu Camu','Camu Camu','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('91','Frut�cola','Frut�cola','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('92','Melocot�n','Melocot�n','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('93','Olivo','Olivo','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('94','Palta','Palta','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('95','Vitivin�cola','Vitivin�cola','','1','18','297','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('96','Gesti�n del agua','Gest. del agua','','1','18','298','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('97','Panela','Panela','','1','18','180','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('98','Alpacas','Alpacas','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('99','Ap�cola','Ap�cola','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('100','Cuyes','Cuyes','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('101','Ganader�a Vacuna','Ganad. Vacuna','','1','18','299','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('102','Truchas','Truchas','','1','18','299','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('103','Capacitaci�n','Capacitaci�n','','1','35','288','1','ad_fondoe','2013-11-11','ad_fondoe','2013-11-11');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('104','Capacitaci�n e inserci�n','Capac. e Inserc','','1','35','288','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('105','Certificaci�n de competencias','Cert. de Compe.','','1','35','290','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('106','Reciclaje','Reciclaje','','1','36','291','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('107','Artesania','Artesania','','1','36','294','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('108','Emprendimientos','Emprendimientos','','1','36','292','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('109','---','---','','1','36','292','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('110','Carpinter�a','Carpinter�a','','1','36','295','1','ad_fondoe','2013-11-11','','0000-00-00');
insert into `adm_tablas_aux2` (`codi`, `descrip`, `abrev`, `cod_ext`, `flg_act`, `idTabla`, `id_tabla_aux`, `orden`, `usu_crea`, `fec_crea`, `usu_actu`, `fec_actu`) values('111','Turismo','Turismo','','1','36','293','1','ad_fondoe','2013-11-11','','0000-00-00');






DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_upd_proyecto`$$

CREATE PROCEDURE `sp_upd_proyecto`( IN _version INT,
                    IN _nro_exp VARCHAR(20) ,
                    IN _cod_linea INT,
                    IN _inst INT,
		    IN _t01_id_cta INT,
                    IN _cod_proy VARCHAR(10) ,
                    IN _nom_proy VARCHAR(250) ,
                    IN _fch_apro DATE,                                  
                    IN _estado INT,                                 
                    IN _fin TEXT ,
                    IN _pro TEXT,
                    IN _ben_obj VARCHAR(5000) ,
                    IN _amb_geo VARCHAR(5000) ,
                    IN _moni_tema INT,
                    IN _moni_fina INT,
                    IN _moni_ext VARCHAR(10) ,
                    IN _sup_inst INT,
                    IN _dire_proy VARCHAR(200),
                    IN _ciud_proy VARCHAR(50) ,                                 
                    IN _tele_proy VARCHAR(30) ,
                    IN _fax_proy VARCHAR(30) ,
                    IN _mail_proy VARCHAR(50) ,                                 
                    IN _t02_fch_isc  DATE ,
                    IN _t02_fch_ire  DATE ,
                    IN _t02_fch_tre  DATE ,
                    IN _t02_fch_tam  DATE ,
                    IN _t02_num_mes INT,    
                    IN _t02_num_mes_amp INT,                                
		    IN _t02_sect_main INT,
                    IN _sect_prod INT,
                    IN _subsect_prod INT,
		    IN _t02_prod_promovido  VARCHAR(200),
                    IN _t02_cre_fe CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
                    IN _t02_proc_gast_superv     VARCHAR(10),
                    IN _usr VARCHAR(20))
BEGIN
    DECLARE _msg        VARCHAR(100);
    DECLARE _existe     INT ;
    DECLARE _fch_ini    DATE;
    DECLARE _fch_ter    DATE;
    DECLARE _num_rows   INT ;  
    DECLARE _numrows    INT ; 
    DECLARE _fcredito   INT DEFAULT 200;
 
 
SET AUTOCOMMIT=0;
 START TRANSACTION ;
 
    /*establecer las fechas de inicio y termino para manejo del proyecto*/   
    SELECT _t02_fch_ire, (CASE WHEN IFNULL(DATEDIFF(_t02_fch_tre, _t02_fch_tam),0) <> 0 THEN _t02_fch_tam ELSE _t02_fch_tre END)
    INTO _fch_ini, _fch_ter;
        
    UPDATE t02_dg_proy 
       SET
        t02_nro_exp = _nro_exp , 
        t00_cod_linea = _cod_linea , 
        t01_id_inst = _inst , 
	t01_id_cta = _t01_id_cta,
        t02_nom_proy = _nom_proy , 
        t02_fch_apro = _fch_apro , 
        t02_fch_ini = _fch_ini , 
        t02_fch_ter = _fch_ter , 
        t02_fin     = _fin , 
        t02_pro     = _pro , 
        t02_ben_obj = _ben_obj , 
        t02_amb_geo = _amb_geo ,
        t02_cre_fe  = _t02_cre_fe, 
        t02_moni_tema = _moni_tema , 
        t02_moni_fina = _moni_fina , 
        t02_moni_ext = _moni_ext , 
        t02_sup_inst = _sup_inst , 
        t02_dire_proy = _dire_proy , 
        t02_ciud_proy = _ciud_proy , 
        t02_tele_proy = _tele_proy , 
        t02_fax_proy = _fax_proy , 
        t02_mail_proy = _mail_proy ,        
        t02_fch_isc = _t02_fch_isc ,
        t02_fch_ire = _t02_fch_ire ,
        t02_fch_tre = _t02_fch_tre ,
        t02_fch_tam = _t02_fch_tam ,
        t02_num_mes = _t02_num_mes ,
        t02_num_mes_amp = _t02_num_mes_amp ,
        t02_estado = _estado , 
	t02_sect_main = _t02_sect_main,
        t02_sect_prod = _sect_prod , 
        t02_subsect_prod = _subsect_prod , 
	t02_prod_promovido = _t02_prod_promovido, 
        usr_actu = _usr , 
        fch_actu = NOW()                    
    WHERE t02_cod_proy = _cod_proy 
      AND t02_version = _version ;
    SELECT ROW_COUNT() INTO _num_rows;
    
    #
    # -------------------------------------------------->
    # DA 2.0 [23-10-2013 20:45]
    # Registramos en las tasas en la tabla t02_tasas_proy: 
    #
    UPDATE t02_tasas_proy SET  
        t02_gratificacion = _t02_gratificacion,
        t02_porc_cts = _t02_porc_cts,
        t02_porc_ess = _t02_porc_ess,
        t02_porc_gast_func = _t02_porc_gast_func, 
        t02_porc_linea_base = _t02_porc_linea_base, 
        t02_porc_imprev = _t02_porc_imprev,
        t02_proc_gast_superv = _t02_proc_gast_superv,
        usr_actu = _usr,
        fch_actu = NOW()
    WHERE t02_cod_proy = _cod_proy AND t02_version = _version;
    # --------------------------------------------------<
    
    CALL sp_calcula_anios_proy(_cod_proy, _version);
    
    SELECT COUNT(t02_sector)  INTO   _existe
      FROM t02_sector_prod  
     WHERE t02_cod_proy  = _cod_proy
       AND t02_sector    = _sect_prod
       AND t02_subsec    = _subsect_prod;
    
    IF _existe <=0 THEN
      CALL sp_ins_sector_prod(_cod_proy, _sect_prod, _subsect_prod, 'Principal', _usr );
    END IF;
    
    IF _t02_cre_fe='S' THEN 
      CALL sp_ins_fte_fin(_cod_proy,_fcredito,'','','',_usr,NOW());
    END IF;     
    
 COMMIT ;
    SELECT _num_rows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;




DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_ins_proyecto`$$

CREATE PROCEDURE `sp_ins_proyecto`(IN _t02_nro_exp     VARCHAR(20) ,
                    IN _t00_cod_linea   INT,
                    IN _t01_id_inst     INT,
		    IN _t01_id_cta     INT,
                    IN _t02_cod_proy    VARCHAR(10),
                    IN _t02_nom_proy    VARCHAR(250),
                    IN _t02_fch_apro    DATE,
                    IN _t02_estado      INT ,
                    IN _t02_fin     TEXT,
                    IN _t02_pro     TEXT,
                    IN _t02_ben_obj     VARCHAR(5000),
                    IN _t02_amb_geo     VARCHAR(5000),
                    IN _t02_moni_tema   INT,
                    IN _t02_moni_fina   INT,
                    IN _t02_moni_ext    VARCHAR(10),                    
                    IN _t02_sup_inst    INT,
                    IN _t02_dire_proy   VARCHAR(200),
                    IN _t02_ciud_proy   VARCHAR(50),
                    IN _t02_tele_proy    VARCHAR(30),
                    IN _t02_fax_proy    VARCHAR(30),
                    IN _t02_mail_proy   VARCHAR(50),
                    IN _t02_fch_isc     DATE,
                    IN _t02_fch_ire     DATE,
                    IN _t02_fch_tre     DATE,
                    IN _t02_fch_tam     DATE,   
                    IN _t02_num_mes     INT,
                    IN _t02_num_mes_amp INT,
                    IN _t02_sect_main   INT,
                    IN _t02_sector      INT,
                    IN _t02_subsector   INT,
                    IN _t02_prod_promovido   VARCHAR(200),
                    IN _t02_cre_fe      CHAR(1),
                    IN _t02_gratificacion       VARCHAR(10),
                    IN _t02_porc_cts        VARCHAR(10),
                    IN _t02_porc_ess        VARCHAR(10),
                    IN _t02_porc_gast_func      VARCHAR(10),
                    IN _t02_porc_linea_base     VARCHAR(10),
                    IN _t02_porc_imprev     VARCHAR(10),
		    IN _t02_proc_gast_superv     VARCHAR(10),			
                    IN _UserID      VARCHAR(20) )
trans1 : BEGIN                  
    DECLARE _msg        VARCHAR(100);
    DECLARE _existe     INT ;
    DECLARE _vs         INT DEFAULT 1;
    DECLARE _t02_fch_ini    DATE;
    DECLARE _fecha_ter  DATE;
    DECLARE _num_rows   INT ;
    DECLARE _numrows    INT ;
    DECLARE _fcredito   INT DEFAULT 200;
    SELECT COUNT(1)
    INTO _existe
    FROM t02_dg_proy
    WHERE t02_cod_proy=_t02_cod_proy AND t01_id_inst=_t01_id_inst;
    
  IF _existe > 0 THEN
      
    SELECT CONCAT('El proyecto esta registrado por el Supervidor de Proyectos ')
    INTO _msg; 
    SELECT 0 AS numrows,_t02_cod_proy AS codigo, _msg AS msg ;
    
   LEAVE trans1;
  END IF;
   
   
SET AUTOCOMMIT=0;
 START TRANSACTION ;
    
    /*establecer las fechas de inicio y termino para manejo del proyecto*/   
    SELECT _t02_fch_ire, (CASE WHEN IFNULL(DATEDIFF(_t02_fch_tre, _t02_fch_tam),0) <> 0 THEN _t02_fch_tam ELSE _t02_fch_tre END)
    INTO _t02_fch_ini, _fecha_ter;
   
   INSERT INTO t02_dg_proy 
    (
    t02_cod_proy, 
    t02_version, 
    t02_nro_exp,
    t00_cod_linea,
    t01_id_inst,
    t01_id_cta,
    t02_nom_proy, 
    t02_fch_apro, 
    t02_fch_ini, 
    t02_fch_ter, 
    t02_fin, 
    t02_pro, 
    t02_ben_obj, 
    t02_amb_geo, 
    t02_moni_tema, 
    t02_moni_fina, 
    t02_moni_ext, 
    t02_sup_inst, 
    t02_dire_proy, 
    t02_ciud_proy, 
    t02_tele_proy, 
    t02_fax_proy, 
    t02_mail_proy, 
    t02_estado, 
    t02_sect_main,
    t02_sect_prod, 
    t02_subsect_prod, 
    t02_prod_promovido,
    t02_cre_fe, 
    t02_fch_isc, 
    t02_fch_ire, 
    t02_fch_tre, 
    t02_fch_tam, 
    t02_num_mes, 
    t02_num_mes_amp, 
    usr_crea, 
    fch_crea, 
    est_audi
    )
    VALUES
    (
    _t02_cod_proy, 
    _vs, 
    _t02_nro_exp,
    _t00_cod_linea,
    _t01_id_inst,
    _t01_id_cta, 
    _t02_nom_proy, 
    _t02_fch_apro, 
    _t02_fch_ini, 
    _fecha_ter, 
    _t02_fin, 
    _t02_pro, 
    _t02_ben_obj, 
    _t02_amb_geo, 
    _t02_moni_tema, 
    _t02_moni_fina, 
    _t02_moni_ext, 
    _t02_sup_inst, 
    _t02_dire_proy, 
    _t02_ciud_proy, 
    _t02_tele_proy, 
    _t02_fax_proy, 
    _t02_mail_proy, 
    _t02_estado, 
    _t02_sect_main,
    _t02_sector, 
    _t02_subsector,
    _t02_prod_promovido, 
    _t02_cre_fe, 
    _t02_fch_isc, 
    _t02_fch_ire, 
    _t02_fch_tre, 
    _t02_fch_tam, 
    _t02_num_mes, 
    _t02_num_mes_amp,
    _UserID, 
    NOW(), 
    '1'
    );
    INSERT INTO t02_aprob_proy(t02_cod_proy, usu_crea, fch_crea) VALUES (_t02_cod_proy,_UserID, NOW());
    
    #
    # -------------------------------------------------->
    # DA 2.0 [23-10-2013 20:45]
    # Registramos en las tasas en la tabla t02_tasas_proy: 
    #
    INSERT INTO t02_tasas_proy (t02_cod_proy, t02_version, t02_gratificacion, t02_porc_cts, t02_porc_ess, 
            t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv ,usr_crea, fch_crea ) 
        VALUES ( _t02_cod_proy, _vs, _t02_gratificacion, _t02_porc_cts, _t02_porc_ess, 
            _t02_porc_gast_func, _t02_porc_linea_base, _t02_porc_imprev, _t02_proc_gast_superv, _UserID, now() );
    # --------------------------------------------------<
    
           
    SELECT ROW_COUNT() INTO _num_rows;
    
    CALL sp_calcula_anios_proy(_t02_cod_proy, _vs);
    
    SELECT COUNT(t02_sector)  INTO   _existe
      FROM t02_sector_prod  
     WHERE t02_cod_proy  = _t02_cod_proy
       AND t02_sector    = _t02_sector
       AND t02_subsec    = _t02_subsector;
    
    IF _existe <=0 THEN
      CALL sp_ins_sector_prod(_t02_cod_proy, _t02_sector, _t02_subsector, 'Principal', _UserID );
    END IF;
    #
    # -------------------------------------------------->
    # DA 2.0 [29-10-2013 22:57]
    # Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
    # ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
    # del tipo DOUBLE
    CALL sp_ins_fte_fin(_t02_cod_proy,'10','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,'63','0','','0',_UserID,NOW());
    CALL sp_ins_fte_fin(_t02_cod_proy,_t01_id_inst,'0','','0',_UserID,NOW());
    # --------------------------------------------------<
    
    IF _t02_cre_fe='S' THEN 
	#
	# -------------------------------------------------->
	# DA 2.0 [29-10-2013 22:57]
	# Correccion del valor del tercer y quinto parametro del procedimiento  sp_ins_fte_fin antes estaba vacio '',
	# ahora sera '0' porque en el campo t02_fuente_finan.t02_porc_def y t02_fuente_finan.t02_mto_financ son 
	# del tipo DOUBLE
        CALL sp_ins_fte_fin(_t02_cod_proy,_fcredito,'0','','0',_UserID,NOW());
	# --------------------------------------------------<
    END IF; 
    
    SELECT ROW_COUNT() INTO _numrows;
  COMMIT ;
    
    SELECT _numrows  AS numrows, _cod_proy AS codigo, _msg AS msg ;
END$$

DELIMITER ;




/* Ya no va el mantenimiento de Productos principales.*/
DELETE FROM adm_menus WHERE mnu_cod = 'MNU91400';


