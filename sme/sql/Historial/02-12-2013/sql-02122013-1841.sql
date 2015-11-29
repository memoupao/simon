/*
Padrón de Beneficiarios: Nuevos campos de Sectores principales:
*/
alter table `t11_bco_bene` add column `t11_sec_prod_main` int(11) NULL COMMENT 'Sector Principal' after `t11_fec_ter`, add column `t11_sec_prod_main_2` int(11) NULL COMMENT 'Sector Principal 2' after `t11_nro_up_b`, add column `t11_sec_prod_main_3` int(11) NULL COMMENT 'Sector Principal 3' after `est_audi`,change `t11_sec_prod` `t11_sec_prod` int(11) NULL , change `t11_sec_prod_2` `t11_sec_prod_2` int(11) NULL  comment 'Segundo sector productivo';

