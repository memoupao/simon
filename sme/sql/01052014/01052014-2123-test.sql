-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-011 Registro de la Ejecución de Desembolsos: RF-011 Maquetación, Estructura y Exportación
-- --------------------------------------------------------------------------------

DELETE FROM adm_reportes WHERE cod_rpt = '82';

INSERT INTO `adm_reportes` (`cod_rpt`, `tit_rpt`, `des_rpt`, `url_rpt`, `url_param`, `estado`) VALUES ('82', 'Cronograma de Desembolsos por Proyecto Planificado y Desembolsado', 'Cronograma de Desembolsos por Proyecto Planificado y Desembolsado', '/test/sme/reportes/rpt_ejec_desemb_entregable.php', '', '1');


