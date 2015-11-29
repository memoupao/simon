/* Nueva tabla de Tasas de concursos*/

CREATE TABLE `adm_concursos_tasas` (                                                                     
                       `num_conc` char(2) NOT NULL COMMENT 'Numero del Consurso',                                             
                       `cod_linea` int(11) NOT NULL COMMENT 'Codigo de Linea',                                                
                       `porc_gast_func` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Gastos Funcionales',                      
                       `porc_linea_base` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Linea Base',                             
                       `porc_imprev` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Gastos Imprevistos',                         
                       `porc_gast_superv_proy` varchar(10) NOT NULL DEFAULT '0' COMMENT '% Gastos Supervision de proyectos',  
                       `fec_crea` datetime DEFAULT NULL,                                                                      
                       `usr_crea` varchar(20) DEFAULT NULL,                                                                   
                       `fec_actu` datetime DEFAULT NULL,                                                                      
                       `usr_actu` varchar(20) DEFAULT NULL,                                                                   
                       UNIQUE KEY `concurso_linea` (`num_conc`,`cod_linea`)                                                   
                     ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
					 
