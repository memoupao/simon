/* Sectores productivos: 
Nuevo campo para sectores principales: */

alter table `t02_sector_prod` add column `t02_sector_main` int(11) NOT NULL COMMENT 'Codigo del  Sector Principal' after `t02_cod_proy`,change `t02_sector` `t02_sector` int(11) NOT NULL comment 'Codigo del Sector - adm_Tablas_aux', drop primary key,  add primary key(`t02_cod_proy`, `t02_sector_main`, `t02_sector`, `t02_subsec`);




