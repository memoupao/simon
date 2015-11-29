-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-011 Registro de la Ejecución de Desembolsos: RF-011 Maquetación, Estructura y Exportación
-- --------------------------------------------------------------------------------



DELETE FROM adm_tablas WHERE cod_tabla = "38";
INSERT INTO `adm_tablas` (`cod_tabla`, `nom_tabla`, `flg_act`) VALUES ('38', 'Tipo de Desembolso', '1');


DELETE FROM adm_tablas_aux WHERE codi IN ("307","308");
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `flg_act`, `idTabla`, `orden`) VALUES ('307', 'Adelanto', 'Adelanto', '1', '38', '1');
INSERT INTO `adm_tablas_aux` (`codi`, `descrip`, `abrev`, `flg_act`, `idTabla`, `orden`) VALUES ('308', 'Saldo', 'Saldo', '1', '38', '2');



