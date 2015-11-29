/*
DA V2.1
Campo de caserio con mas ancho o longitud de caracteres.

*/

ALTER TABLE `adm_caserios` CHANGE COLUMN `cod_case` `cod_case` CHAR(4) NOT NULL COMMENT 'Codigo del Caserio'  ;

ALTER TABLE `t11_bco_bene` CHANGE COLUMN `t11_case` `t11_case` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Codigo de caserios'  ;





