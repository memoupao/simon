/*
// -------------------------------------------------->
// AQ 2.0 [27-12-2013 09:55]
// Fixed: Cálculo de último mes de Informe Técnico
// ya no se considera el trimestre. 
// --------------------------------------------------<
*/

DROP PROCEDURE IF EXISTS `sp_get_inf_mes_ult`;

DELIMITER $$

CREATE PROCEDURE `sp_get_inf_mes_ult`(IN _proy VARCHAR(10))
BEGIN
  
	DECLARE _existe   INT;
	DECLARE _t20_anio INT DEFAULT 1;
	DECLARE _t20_mes INT DEFAULT 1;
	DECLARE _new_anio INT DEFAULT 1;
	DECLARE _nuevo_mes INT DEFAULT 1;
	DECLARE _new_mes INT DEFAULT 1;
	DECLARE _periodo VARCHAR(20);
  
	SELECT  COUNT(1)  
	INTO _existe
	FROM t20_inf_mes
	WHERE t02_cod_proy = _proy;
    
    IF _existe > 0 THEN
        SELECT  
            w.t20_anio,
		    w.t20_mes,
		    w.nuevo_mes,
		    fn_numero_mes_rev(w.nuevo_mes,1) AS new_anio,
		    fn_numero_mes_rev(w.nuevo_mes,2) AS new_mes,
		    fn_nom_periodo(_proy,fn_numero_mes_rev(w.nuevo_mes,1),fn_numero_mes_rev(w.nuevo_mes,2)) AS periodo 
        FROM (
			SELECT 
                t20_anio, 
			    t20_mes, 
			    fn_numero_mes(t20_anio, t20_mes)+1 AS sig_mes,
			    -- (CASE WHEN ((fn_numero_mes(t20_anio, t20_mes)+1) MOD 3) = 0 THEN (fn_numero_mes(t20_anio, t20_mes)+1) + 1 ELSE (fn_numero_mes(t20_anio, t20_mes)+1) END) AS nuevo_mes
			    fn_numero_mes(t20_anio, t20_mes)+1 AS nuevo_mes
			FROM t20_inf_mes
			WHERE t02_cod_proy = _proy
			ORDER BY t20_anio DESC, t20_mes DESC 
			LIMIT 1) AS w;
    ELSE  
		SELECT _t20_anio AS t20_anio 
		, _t20_mes AS t20_mes 
		, _nuevo_mes AS nuevo_mes 
		, _new_anio AS new_anio 
		, _new_mes AS new_mes 
		, fn_nom_periodo(_proy,1,1) AS periodo;    
  END IF ;
END $$

DELIMITER ;