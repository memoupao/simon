/*
// -------------------------------------------------->
// AQ 2.0 [30-12-2013 11:50]
// Registro de Carátula del Informe de Entregable
// --------------------------------------------------<
*/
ALTER TABLE `t07_prop_ind_inf` CHANGE `t07_ind_trim` `t07_ind_entregable` INT(11) NOT NULL;
ALTER TABLE `t07_prop_ind_inf` ADD `t07_observaciones` TEXT NULL DEFAULT NULL AFTER `t07_logros`;
ALTER TABLE `t08_comp_ind_inf` CHANGE `t08_ind_trim` `t08_ind_entregable` INT( 11 ) NOT NULL;