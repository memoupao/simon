-- --------------------------------------------------------------------------------
-- DA v2
-- Actualizacion en las tasas para el calculo de las remuneraciones del personal
-- --------------------------------------------------------------------------------

DROP function IF EXISTS `fn_remun_prom_mes`;

DELIMITER $$
CREATE  FUNCTION `fn_remun_prom_mes`(_basico double, _dedica double, _um INT) RETURNS double
    DETERMINISTIC
BEGIN
	DECLARE _remu  double;
	DECLARE _grati double;
	DECLARE _cts   double;
	DECLARE _ess   DOUBLE;
	declare _tipo  varchar(5);
	declare _return double;

	DECLARE _tasagrati double;	
	DECLARE _tasacts   double;
	DECLARE _tasaess   DOUBLE;
	
	select trim(cod_ext)
	into  _tipo 
	from  adm_tablas_aux
	where codi = _um ;



	SELECT t00_nom_lar into _tasagrati FROM t00_parametro WHERE t00_cod_param = 1;
	SELECT t00_nom_lar into _tasacts FROM t00_parametro WHERE t00_cod_param = 2;
	SELECT t00_nom_lar into _tasaess FROM t00_parametro WHERE t00_cod_param = 3;


	
	select (_basico * (_dedica/100)) , 0, 0, 0
	into _remu ,   _grati,   _cts,    _ess  ;
	
	if _tipo='1' then
		select 	(_remu / _tasagrati),
			(((_grati + _remu) * _tasacts)/100),
			(((_grati + _remu) * _tasaess)/100)
		into    _grati,
			_cts ,
			_ess ;
	end if;
	
	select (_remu + _grati + _cts + _ess)
	  into _return ;
	
	RETURN _return ;
    END$$

DELIMITER ;



