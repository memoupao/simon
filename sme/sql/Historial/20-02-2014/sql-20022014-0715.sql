/*
// -------------------------------------------------->
// AQ 2.0 [20-02-2014 07:15]
// Incidencia 144: Renumeración de Códigos
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_financ_mp_supervision`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_financ_mp_supervision`(IN _proy VARCHAR(10), 
                         IN _anio INT , 
                         IN _mes  INT ,
                         IN _cod_fte INT)
BEGIN
	DECLARE _ver  INT ;
	DECLARE _ver_anio  INT DEFAULT fn_version_proy_poa(_proy, _anio); 
	DECLARE _comp INT ;
	DECLARE _act  INT ;
	DECLARE _sub  INT ;
	DECLARE _cat  INT ;
	DECLARE _supervision DOUBLE;
	DECLARE _supervision_int INT;

	SELECT fn_ult_version_proy(_proy) INTO _ver;
	SELECT 10, 7, 0, 0 INTO _comp, _act, _sub, _cat;
	
	SELECT IFNULL(SUM(t03_monto),0), IFNULL(SUM(t03_monto),0) 
	INTO _supervision, _supervision_int 
    FROM t03_mp_gas_supervision
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver_anio;
       
	SELECT CONCAT(_comp,'.',_act ) AS codigo, 
    'Gastos de Supervisión' AS descripcion,
    _supervision_int AS gasto_tot,
    _supervision AS total_fuente,
    (_supervision/12) AS planeado,
    (SELECT SUM(gas.t41_gasto) 
    FROM t41_inf_financ_gasto gas 
    WHERE gas.t02_cod_proy = _proy
	AND gas.t08_cod_comp   = _comp
	AND gas.t09_cod_act    = _act
	AND gas.t09_cod_sub    = _sub 
	AND gas.t41_cate_gasto = _cat
	AND gas.t41_fte_finan  = _cod_fte
	AND gas.t40_anio= _anio
	AND gas.t40_mes= _mes
    ) AS ejecutado,
     
     (SELECT MAX(gas.t41_obs) 
          FROM  t41_inf_financ_gasto gas 
         WHERE gas.t02_cod_proy= _proy
		           AND gas.t08_cod_comp   = _comp
		           AND gas.t09_cod_act    = _act
		           AND gas.t09_cod_sub    = _sub
		           AND gas.t41_cate_gasto = _cat           
		           AND gas.t41_fte_finan  =_cod_fte
		           AND gas.t40_anio= _anio
		           AND gas.t40_mes= _mes
		     ) AS observ;
END $$

DELIMITER ;