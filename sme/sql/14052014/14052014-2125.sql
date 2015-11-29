/*
// -------------------------------------------------->
// AQ 2.1 [14-05-2014 23:05]
// RF-011 
// Obtiene el desembolso planeado acumulado al entregable
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_del_entregable;

DELIMITER $$
CREATE FUNCTION `fn_desembolso_planeado_del_entregable`(_proy VARCHAR(10), 
  _ver INT,
  _anio INT,
  _mes  INT) RETURNS DOUBLE
BEGIN

DECLARE _entregable_act INT DEFAULT 0;
DECLARE _entregable_ant INT DEFAULT 0;
DECLARE _planeado DOUBLE DEFAULT 0;

SET _entregable_ant = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
SET _entregable_act = fn_numero_mes(_anio, _mes);

SELECT
  -- IFNULL(
  --  ( SELECT SUM(gas.t41_gasto)
  --    FROM  t41_inf_financ_gasto gas 
  --    WHERE gas.t02_cod_proy=cost.t02_cod_proy
  --      AND fn_numero_mes(gas.t40_anio, gas.t40_mes) <= fn_numero_mes(_anio, _mes)
  --   )
  --  ,0) AS ejecutado,       
                    
  -- Componentes
  SUM(IFNULL(
   ( SELECT SUM(t09_mta * t10_cost_parc)
      FROM t09_sub_act_mtas m
      WHERE m.t02_cod_proy = cost.t02_cod_proy
      AND m.t02_version  = cost.t02_version
      and m.t08_cod_comp = cost.t08_cod_comp
      and m.t09_cod_act  = cost.t09_cod_act
      and m.t09_cod_sub  = cost.t09_cod_sub
      AND fn_numero_mes(m.t09_anio, m.t09_mes) BETWEEN _entregable_ant AND _entregable_act
    ),0))

  +
  -- Personal
  IFNULL(
   ( SELECT SUM(t03_remu_prom * m.t03_mta) 
      FROM t03_mp_per_metas m 
      JOIN t03_mp_per p ON (p.t02_cod_proy = m.t02_cod_proy
            AND p.t02_version  = m.t02_version 
            AND p.t03_id_per   = m.t03_id_per)
      WHERE m.t02_cod_proy = cost.t02_cod_proy
            AND m.t02_version  = cost.t02_version 
            AND fn_numero_mes(m.t03_anio, m.t03_mes) BETWEEN _entregable_ant AND _entregable_act
   ),0)

  +
  -- Equipamiento
  IFNULL(
   ( SELECT SUM(t03_costo * m.t03_mta) 
      FROM t03_mp_equi_metas m 
      JOIN t03_mp_equi e ON (e.t02_cod_proy = m.t02_cod_proy
            AND e.t02_version  = m.t02_version 
            AND e.t03_id_equi   = m.t03_id_equi)
      WHERE m.t02_cod_proy = cost.t02_cod_proy
            AND m.t02_version  = cost.t02_version 
            AND fn_numero_mes(m.t03_anio, m.t03_mes) BETWEEN _entregable_ant AND _entregable_act
   ),0)

  +
  -- Gastos de Funcionamiento
  IFNULL(
   ( SELECT SUM(c.t03_cu * m.t03_mta) 
      FROM t03_mp_gas_fun_metas m 
      JOIN t03_mp_gas_fun_part p ON (p.t02_cod_proy = m.t02_cod_proy
            AND p.t02_version = m.t02_version 
            AND p.t03_partida = m.t03_partida)
      JOIN t03_mp_gas_fun_cost c ON (c.t02_cod_proy = p.t02_cod_proy
            AND c.t02_version = p.t02_version 
            AND c.t03_partida = p.t03_partida)
      WHERE m.t02_cod_proy = cost.t02_cod_proy
            AND m.t02_version  = cost.t02_version 
            AND fn_numero_mes(m.t03_anio, m.t03_mes) BETWEEN _entregable_ant AND _entregable_act
   ),0)

   +
  -- Gastos Administrativos 
  IFNULL(
   ( SELECT fn_mnt_planificado_del_entregable(cost.t02_cod_proy, cost.t02_version, 
                                                    4, _anio, _mes)
   ),0)

  +
  -- Imprevistos 
  IFNULL(
   ( SELECT fn_mnt_planificado_del_entregable(cost.t02_cod_proy, cost.t02_version, 
                                                    6, _anio, _mes)
   ),0)

  +
  -- Supervisión
  IFNULL(
   ( SELECT fn_mnt_planificado_del_entregable(cost.t02_cod_proy, cost.t02_version, 
                                                    7, _anio, _mes)
   ),0)

  AS planeado
   
INTO _planeado
FROM t10_cost_sub cost
WHERE cost.t02_cod_proy= _proy
  AND cost.t02_version= _ver;

  RETURN _planeado;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS des_plan;

/*
// -------------------------------------------------->
// AQ 2.1 [08-05-2014 22:49]
// RF-011 
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_mnt_planificado_del_entregable;

DELIMITER $$

CREATE FUNCTION `fn_mnt_planificado_del_entregable`(_proy VARCHAR(10), _ver INT, _tipo INT, _anio INT, _mes INT) RETURNS double
BEGIN
  DECLARE _plan  DOUBLE;
  DECLARE _num_meses_entregable INT;
  DECLARE _num_meses_proyecto INT;
  DECLARE _factor DOUBLE;

  SET _num_meses_entregable = fn_nro_meses_entregable(_proy, _ver, _anio, _mes);
  SET _num_meses_proyecto = fn_numero_meses_proy(_proy, _ver);
  SET _factor = fn_nro_meses_entregable(_proy, _ver, _anio, _mes) / fn_numero_meses_proy(_proy, _ver);

  -- Gastos Administrativos  
  IF _tipo = 4 THEN 
  
    SELECT SUM(t03_monto) * _factor
    INTO _plan
    FROM t03_mp_gas_adm 
    WHERE t02_cod_proy = _proy
    AND t02_version  = _ver;
      
  END IF ;
  
  -- Línea Base
  -- IF _tipo = 5 THEN 
     
  --   IF fn_numero_mes(_anio, _mes) = fn_numero_meses_proy(_proy, _ver) or fn_numero_mes(_anio, _mes)=3 then
  --   SELECT (sum(t03_monto)/2)
  --     INTO _plan
  --     FROM   t03_mp_linea_base 
  --    WHERE t02_cod_proy = _proy
  --      AND t02_version  = _ver;
  --    else
  --   SELECT 0 INTO _plan ;
  --    end IF ;
     
  -- END IF ;
  
  -- Imprevistos
  IF _tipo = 6 THEN 
    
    SELECT (t03_monto * _factor)
    INTO _plan
    FROM  t03_mp_imprevistos 
    WHERE t02_cod_proy = _proy
    AND t02_version  = 1;
      
  END IF ;

  -- Supervisión
  IF _tipo = 7 THEN 
    
    SELECT (t03_monto * _factor)
    INTO _plan
    FROM  t03_mp_gas_supervision
    WHERE t02_cod_proy = _proy
    AND t02_version  = 1;
      
  END IF ;

  RETURN IFNULL(_plan,0) ;
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [14-05-2014 23:13]
// RF-005 
// Obtiene los desembolsos realizados del entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_sel_desembolsos_del_entregable;

DELIMITER $$
CREATE PROCEDURE `sp_sel_desembolsos_del_entregable`(IN _proy VARCHAR(10), 
  IN _ver INT,
  IN _anio INT,
  IN _mes  INT)
BEGIN

  SELECT d.t60_id, d.t02_cod_proy, 
    fn_numero_entregable(d.t02_cod_proy, _ver, d.t02_anio, d.t02_mes) AS entregable, 
    tp.t00_nom_lar as modalidad, 
    DATE_FORMAT( d.t60_fch_giro,'%d/%m/%Y') as t60_fch_giro,
    d.t60_mto,
    d.t60_cheque, 
    DATE_FORMAT(d.t60_fch_depo,'%d/%m/%Y') as t60_fch_depo,
    d.t60_obs
  FROM  t60_desembolsado d
  LEFT JOIN t00_tipo_pago tp on (d.t60_mod_pago=tp.t00_tip_pago)
  WHERE d.t02_cod_proy = _proy
  AND d.t02_anio = _anio
  AND d.t02_mes = _mes
  ORDER BY entregable, t60_fch_depo;
  
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [14-05-2014 23:14]
// RF-005 
// Obtiene lo desembolsado en el entregable
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_desembolsado_del_entregable;

DELIMITER $$
CREATE FUNCTION `fn_desembolsado_del_entregable`(_proy VARCHAR(10), 
  _anio INT,
  _mes  INT) RETURNS DOUBLE
BEGIN
  DECLARE var_desembolsado DOUBLE DEFAULT 0.0;

  SELECT IFNULL(SUM(t60_mto), 0) 
  INTO var_desembolsado 
  FROM t60_desembolsado 
  WHERE t02_cod_proy = _proy 
  AND t02_anio = _anio
  AND t02_mes = _mes;

  RETURN var_desembolsado;
  
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [15-05-2014 11:43]
// Obtiene el número de mes donde se ubica 
// el entregable anterior.
// Ejemplo: El entregable del Año 1 en el Mes 6.
// Respuesta: 12 * 1 + 6 = 18
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_mes_entregable_ant;

DELIMITER $$
CREATE FUNCTION `fn_mes_entregable_ant`(_proy VARCHAR(10), 
  _ver INT,
  _anio INT,
  _mes INT) RETURNS INT
BEGIN

  DECLARE _res INT DEFAULT 0;

  SELECT fn_numero_mes(t02_anio, t02_mes)
  INTO _res
  FROM t02_entregable
  WHERE t02_cod_proy = _proy 
  AND t02_version = _ver
  AND DATE_ADD(DATE_ADD(NOW(), INTERVAL t02_anio YEAR), INTERVAL t02_mes MONTH) < DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _mes MONTH)
  ORDER BY t02_anio DESC, t02_mes DESC
  LIMIT 1;

  RETURN _res;

END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [15-05-2014 12:13]
// Obtiene el número de meses del entregable
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_nro_meses_entregable;

DELIMITER $$
CREATE FUNCTION `fn_nro_meses_entregable`(_proy VARCHAR(10), 
  _ver INT,
  _anio INT,
  _mes INT) RETURNS INT
BEGIN

  DECLARE _res INT DEFAULT 0;

  SET _res = fn_numero_mes(_anio, _mes) - fn_mes_entregable_ant(_proy, _ver, _anio, _mes);

  RETURN _res;

END $$  
DELIMITER ;