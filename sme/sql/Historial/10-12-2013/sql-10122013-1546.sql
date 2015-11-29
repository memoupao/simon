/*
// -------------------------------------------------->
// AQ 2.0 [10-12-2013 15:45]
// Campo adicional para registrar el usuario 
// que realiza la aprobación. 
// --------------------------------------------------<
*/
ALTER TABLE `t02_aprob_proy` ADD `usu_aprob_proy` TINYINT NULL DEFAULT NULL COMMENT 'Usuario que aprueba el proyecto';