-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-013 Exportable del Informe Mensual Técnico
-- --------------------------------------------------------------------------------


INSERT INTO `adm_tablas` (`cod_tabla`, `nom_tabla`, `flg_act`) VALUES ('39', 'Estado Cronograma de Visitas del Proyecto', '1');



INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `flg_act`, `idTabla`, `orden`) VALUES ('309', 'Aprobado', 'Aprobado', '1', '39', '1');
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `flg_act`, `idTabla`, `orden`) VALUES ('310', 'Pendiente', 'Pendiente', '1', '39', '2');
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `flg_act`, `idTabla`, `orden`) VALUES ('311', 'Desaprobado', 'Desaprobado', '1', '39', '3');




