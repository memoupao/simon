-- --------------------------------------------------------------------------------
-- DA v2
-- Actualizacion para el uso de la Linea Base en Manejo del proyecto  - Reporte
-- --------------------------------------------------------------------------------

DROP function IF EXISTS `fn_linea_base`;
DELIMITER $$
CREATE FUNCTION `fn_linea_base`(_proy varchar(10), _ver int) RETURNS double
    DETERMINISTIC
BEGIN
	declare _return double;
	declare _idFE int;
	declare _totalFE double ;

	declare _tasaLineaBase double ;

	SELECT t00_nom_lar INTO _tasaLineaBase FROM pg.t00_tasa WHERE t00_cod_tasa = 2;

	select 10 into _idFE ; 
	SELECT fn_total_aporte_fuentes_financ(_proy, _ver, _idFE) into _totalFE ;
	select (_totalFE * _tasaLineaBase)/100 into _return;
	
	RETURN _return ;
    END$$

DELIMITER ;


