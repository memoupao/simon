/*
// -------------------------------------------------->
// AQ 2.0 [01-01-2014 16:22]
// Información adicional del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable_analisis`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable_analisis`(
                IN _proy VARCHAR(10), 
                IN _ver INT,
                IN _anio INT, 
                IN _entregable  INT)
BEGIN
    SELECT t25_entregable, 
        t25_resulta, 
        t25_conclu, 
        t25_limita, 
        t25_fac_pos, 
        t25_perspec 
    FROM t25_inf_entregable
    WHERE t02_cod_proy=_proy
      AND t25_anio = _anio
      AND t25_entregable  = _entregable
      AND t02_version = _ver;
END $$

DELIMITER ;