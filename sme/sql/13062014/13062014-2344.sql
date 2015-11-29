-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-022 Maquetación, Estructura, Exportación y Registro
-- --------------------------------------------------------------------------------

DROP TABLE IF EXISTS t46_cron_visita_proy;

CREATE TABLE `t46_cron_visita_proy` (
  `t02_cod_proy` VARCHAR(10) NOT NULL,
  `anio` INT(11) NOT NULL,
  `t25_entregable` INT(11) NOT NULL,
  `fecha_visita_inicio` DATE NULL,
  `fecha_visita_termino` DATE NULL,
  `estado` INT(5) NULL COMMENT '309: Aprobado, 310: Pendiente, 311: Desaprobado',
  `costo_pago_1` DECIMAL(10,2) NULL,
  `costo_pago_2` DECIMAL(10,2) NULL,
  `usr_actu` CHAR(20) NULL,
  `fch_actu` DATETIME NULL,
  PRIMARY KEY (`t02_cod_proy`, `anio`, `t25_entregable`));


ALTER TABLE `t46_cron_visita_proy` 
CHANGE COLUMN `anio` `t25_anio` INT(11) NOT NULL ;



