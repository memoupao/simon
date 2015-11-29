/*
// -------------------------------------------------->
// AQ 2.0 [18-12-2013 08:21]
// Cálculo de la Línea Base
// Consideración para Proyectos de 1 año
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_linea_base_anual;

DELIMITER $$

CREATE FUNCTION `fn_linea_base_anual`(_proy VARCHAR(10), _fte INT, _anio INT) RETURNS double
    DETERMINISTIC
BEGIN
	DECLARE _retMonto DOUBLE;
	DECLARE _numanios INT DEFAULT fn_duracion_proy(_proy, 1);
 
    SELECT IFNULL(lb.t03_monto, 0)
    INTO _retMonto
    FROM t03_mp_linea_base lb
    WHERE lb.t02_cod_proy = _proy
    AND lb.t03_id_inst = _fte
    AND lb.t02_version = 1;
 
    IF _numanios > 1 THEN
        IF _anio = _numanios OR _anio = 1 THEN
            SET _retMonto = _retMonto / 2;
        ELSE
            SET _retMonto = 0;
        END IF;
    END IF;
  RETURN _retMonto;
END $$

DELIMITER ;

