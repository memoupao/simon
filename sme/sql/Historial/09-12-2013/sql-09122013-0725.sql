/*
// -------------------------------------------------->
// AQ 2.0 [09-12-2013 07:25]
// Cambio de conjunto de caracteres.
// --------------------------------------------------<
*/
ALTER TABLE `t12_plan_capac_tema` CHANGE `t12_tem_espe` `t12_tem_espe` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Temas Especificos';