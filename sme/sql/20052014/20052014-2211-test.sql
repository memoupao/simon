-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-016 Maquetación, Estructura y Exportación
-- --------------------------------------------------------------------------------

DELETE FROM adm_reportes WHERE cod_rpt = '83';

INSERT INTO `adm_reportes` (`cod_rpt`, `tit_rpt`, `des_rpt`, `url_rpt`, `url_param`, `estado`) VALUES ('83', 'INFORME DE SUPERVISIÓN DE EJECUCIÓN DE ENTREGABLES', 'INFORME DE SUPERVISIÓN DE EJECUCIÓN DE ENTREGABLES', '/test/sme/reportes/rpt_inf_supervicion.php', '/test/sme/reportes/filter_proy_inf_anio_mes.php?tipInf=ISE', '1');


