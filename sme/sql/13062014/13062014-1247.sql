/*
// -------------------------------------------------->
// AQ 2.1 [13-06-2014 12:47]
// Fixed: Incidencia #234
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS `fn_costos_componentes`;

DELIMITER $$
CREATE FUNCTION `fn_costos_componentes`(_proy varchar(10), _ver int) RETURNS double
    DETERMINISTIC
BEGIN
    declare _return double;
    
    SELECT  SUM(ROUND(cost.t10_cost_tot, 2))
    INTO    _return
    FROM    t10_cost_sub cost
    WHERE   cost.t02_cod_proy=_proy AND cost.t02_version=_ver;
    RETURN ifnull(_return,0) ;
    END $$
DELIMITER ;
