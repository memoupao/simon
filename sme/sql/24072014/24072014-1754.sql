/*
// -------------------------------------------------->
// AQ 2.1 [23-07-2014 15:54]
// Fixed: Incidencia #245
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS `fn_sel_duracion_en_el_anio`;

DELIMITER $$

CREATE FUNCTION `fn_sel_duracion_en_el_anio`(_proy varchar(10), _ver int) RETURNS INT
    DETERMINISTIC
BEGIN
  DECLARE _return INT DEFAULT 0;
  DECLARE _duracion_meses INT DEFAULT 0;
  DECLARE _duracion_anios INT DEFAULT 0;
  
  SELECT MAX(d.t02_anio), (dg.t02_num_mes + dg.t02_num_mes_amp) 
  INTO _duracion_anios, _duracion_meses
  FROM t02_duracion d, t02_dg_proy dg
  WHERE dg.t02_cod_proy = d.t02_cod_proy
  AND dg.t02_version  = d.t02_version
  AND dg.t02_cod_proy = _proy
  AND dg.t02_version  = _ver;

  IF MOD(_duracion_meses, _duracion_anios) = 0 THEN
    SET _return = 12;
  ELSE
    SET _return = _duracion_meses - (_ver - 2) * 12;
  END IF;

  RETURN _return;
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS `fn_sel_factor_duracion`;

DELIMITER $$

CREATE FUNCTION `fn_sel_factor_duracion`(_proy varchar(10), _ver int) RETURNS DOUBLE
    DETERMINISTIC
BEGIN
  DECLARE _return DOUBLE DEFAULT 0;
  DECLARE _duracion_meses INT DEFAULT 0;
  DECLARE _duracion_anios INT DEFAULT 0;
  
  SELECT fn_sel_duracion_en_el_anio(_proy, _ver) / (t02_num_mes + t02_num_mes_amp)
  INTO _return
  FROM t02_dg_proy
  WHERE t02_cod_proy = _proy
  AND t02_version  = _ver;

  RETURN _return;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_get_mp_gastos_adm_poa`;

DELIMITER $$

CREATE PROCEDURE `sp_get_mp_gastos_adm_poa`(
        IN _proy VARCHAR(10), 
        IN _ver INT)
BEGIN

  SELECT
      f.t01_id_inst AS codigo,
      i.t01_sig_inst AS fuente,
      g.t03_monto * fn_sel_factor_duracion(_proy, _ver) AS monto_max,
      (SELECT ga.t03_monto 
          FROM t03_mp_gas_adm ga 
          WHERE ga.t02_cod_proy = _proy 
          AND ga.t02_version = _ver
          AND ga.t03_id_inst = g.t03_id_inst) AS costo
  FROM t03_mp_gas_adm g
  LEFT JOIN t02_fuente_finan f ON(g.t03_id_inst = f.t01_id_inst AND g.t02_cod_proy = f.t02_cod_proy)
  LEFT JOIN t01_dir_inst i ON(f.t01_id_inst = i.t01_id_inst)
  WHERE g.t02_cod_proy = _proy
  AND g.t02_version  = 1;

END $$
DELIMITER ;