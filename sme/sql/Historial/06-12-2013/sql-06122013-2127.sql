
/* Actualizacion de tipo de collation en el campo de t12_tem_espe: */

alter table `t12_plan_capac_tema` change `t02_cod_proy` `t02_cod_proy` varchar(10) character set utf8 collate utf8_general_ci NOT NULL, change `t12_tem_espe` `t12_tem_espe` text character set utf8 collate utf8_general_ci NULL  comment 'Temas Especificos';



