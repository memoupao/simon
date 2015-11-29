/*
// -------------------------------------------------->
// AQ 2.0 [09-02-2014 10:42]
// Campo para el V°B° del SE
// --------------------------------------------------<
*/
ALTER TABLE `t40_inf_financ` ADD `vb_se` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `t20_inf_mes` ADD `vb_se` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `t25_inf_entregable` ADD `vb_se` TINYINT(1) NOT NULL DEFAULT 0;

UPDATE t40_inf_financ SET vb_se = 1 WHERE t40_est_eje = 135;
UPDATE t20_inf_mes SET vb_se = 1 WHERE t20_estado = 135;
UPDATE t25_inf_entregable SET vb_se = 1 WHERE t25_estado = 135;