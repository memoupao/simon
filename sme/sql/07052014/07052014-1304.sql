/*
// -------------------------------------------------------------------------->
// AQ 2.1 [07-05-2014 13:04]
// RF-
// Relacionado a: RF-009 - Informe POA - Agregar columna Meta Física Total Vigente 
// y revisar columna Variación
// --------------------------------------------------------------------------<
*/
DROP TABLE IF EXISTS `t60_ejecucion_desemb`;
CREATE TABLE IF NOT EXISTS `t60_ejecucion_desemb` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_anio` tinyint(4) NOT NULL COMMENT 'Año del Entregable',
  `t02_mes` tinyint(4) NOT NULL COMMENT 'Mes del Entregable',
  `t60_tip_pago` int(11) DEFAULT NULL COMMENT 'Tipo de Pago (Adelanto o Saldo)',
  `t60_mod_pago` int(11) DEFAULT NULL COMMENT 'Modalidad de Pago (Transferencia, Cheque, etc)',
  `t60_inst_ori` int(11) DEFAULT NULL,
  `t60_cta_ori` int(11) DEFAULT NULL COMMENT 'Cuenta Origen de la Institucion FE',
  `t01_id_inst` int(11) DEFAULT NULL COMMENT 'Codigo de la Institucion a Girar',
  `t01_id_cta` int(11) DEFAULT NULL COMMENT 'Codigo de la Cuenta de la Institucion',
  `t60_benef` varchar(150) DEFAULT NULL COMMENT 'Nombre del Beneficiario a Girar',
  `t60_fch_giro` date DEFAULT NULL COMMENT 'Fecha de Giro',
  `t60_fch_depo` date DEFAULT NULL COMMENT 'Fecha de Deposito',
  `t60_mto_des` double DEFAULT NULL,
  `t60_cheque` varchar(100) DEFAULT NULL,
  `t60_obs` text,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_anio`,`t02_mes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `t60_ejecucion_desemb`
--
DROP TRIGGER IF EXISTS `tr_delete_total_desembolso`;
DROP TRIGGER IF EXISTS `tr_insert_total_desembolso`;
DROP TRIGGER IF EXISTS `tr_update_total_desembolso`;

DROP PROCEDURE IF EXISTS sp_sel_desembolso_resumen;
DROP FUNCTION IF EXISTS fn_mnt_planificado_acumulado;
DROP FUNCTION IF EXISTS fn_monto_planificado_mp_periodo;
DROP PROCEDURE IF EXISTS sp_avance;
DROP FUNCTION IF EXISTS fn_monto_test;