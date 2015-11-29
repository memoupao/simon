/* Nuevos campos de Productos principales y productos promovidos de clasificacion de Sectores y Subsectores: */

alter table `t02_dg_proy` add column `t02_prod_principal` int(11) NULL COMMENT 'Producto Principal' after `t02_subsect_prod`;
alter table `t02_dg_proy` add column `t02_prod_promovido` varchar(150) NULL COMMENT 'Productos  Promovidos' after `t02_prod_principal`;


/* Nueva tabla para Producto principal */
CREATE TABLE `adm_tablas_aux3` (                  
                   `codi` int(10) NOT NULL DEFAULT '0',            
                   `descrip` varchar(100) NOT NULL,                
                   `abrev` varchar(15) NOT NULL,                   
                   `cod_ext` varchar(20) DEFAULT NULL,             
                   `flg_act` char(1) NOT NULL,                     
                   `idTabla` int(10) NOT NULL DEFAULT '0',         
                   `id_tabla_aux2` int(10) NOT NULL DEFAULT '0',    
                   `orden` int(10) DEFAULT NULL,                   
                   `usu_crea` varchar(20) NOT NULL,                
                   `fec_crea` date NOT NULL DEFAULT '0000-00-00',  
                   `usu_actu` varchar(20) NOT NULL,                
                   `fec_actu` date NOT NULL DEFAULT '0000-00-00',  
                   PRIMARY KEY (`codi`)                            
                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
				 

/* Nuevo menu de mantenimiento de Productos Principales*/				 
insert into `adm_menus` (`mnu_cod`, `mnu_nomb`, `mnu_apli`, `mnu_link`, `mnu_target`, `mnu_parent`, `mnu_activo`, `mnu_class`, `mnu_sort`, `mnu_img`, `mnu_admin`) values('MNU91400','Productos Principales','1','/sgp/Admin/man_prodprinc_lista.php','_self','MNU91000','1','','2','','1');

