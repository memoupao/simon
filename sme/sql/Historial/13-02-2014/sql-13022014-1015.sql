/*
// -------------------------------------------------->
// AQ 2.0 [13-02-2014 10:15]
// Campo para el V°B° del SE
// --------------------------------------------------<
*/
ALTER TABLE `t02_poa` ADD `vb_se_tec` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `t02_poa` ADD `vb_se_fin` TINYINT(1) NOT NULL DEFAULT 0;

UPDATE t02_poa SET vb_se_tec = 1 WHERE t02_estado = 257;
UPDATE t02_poa SET vb_se_fin = 1 WHERE t02_estado = 262;