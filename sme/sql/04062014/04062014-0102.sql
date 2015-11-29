/*
// -------------------------------------------------->
// AQ 2.1 [19-05-2014 05:45]
// Fixed: fn_mnt_planificado_del_entregable
// Cambiado a fn_mnt_planificado_al_entregable
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_al_entregable;

DELIMITER $$
CREATE FUNCTION `fn_desembolso_planeado_al_entregable`(_proy VARCHAR(10), 
  _ver INT,
  _anio INT,
  _mes  INT) RETURNS DOUBLE
BEGIN

DECLARE _mes_ini INT DEFAULT 1;
DECLARE _planeado DOUBLE DEFAULT 0;

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
      AND fn_numero_mes(t09_anio, t09_mes) <= fn_numero_mes(_anio, _mes)
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
            AND fn_numero_mes(m.t03_anio, m.t03_mes) <= fn_numero_mes(_anio, _mes)
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
            AND fn_numero_mes(m.t03_anio, m.t03_mes) <= fn_numero_mes(_anio, _mes)
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
            AND fn_numero_mes(m.t03_anio, m.t03_mes) <= fn_numero_mes(_anio, _mes)
   ),0)

   +
  -- Gastos Administrativos 
  IFNULL(
   ( SELECT fn_mnt_planificado_al_entregable(cost.t02_cod_proy, cost.t02_version, 
                                                    4, _anio, _mes)
   ),0)

  +
  -- Imprevistos 
  IFNULL(
   ( SELECT fn_mnt_planificado_al_entregable(cost.t02_cod_proy, cost.t02_version, 
                                                    6, _anio, _mes)
   ),0)

  +
  -- Supervisión
  IFNULL(
   ( SELECT fn_mnt_planificado_al_entregable(cost.t02_cod_proy, cost.t02_version, 
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

/*
// -------------------------------------------------->
// AQ 2.1 [04-06-2014 01:34]
// Obtiene resumen presupuestal
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_inf_entregable_resumen_presup;

DELIMITER $$
CREATE PROCEDURE `sp_inf_entregable_resumen_presup`(IN _proy VARCHAR(10), 
  IN _ver INT,
  IN _anio INT) 
BEGIN

DECLARE _cod_fte_fe INT DEFAULT 10;

SELECT 
  t02_mes,
  fn_numero_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS nro_entregable,
  fn_numero_mes(t02_anio, t02_mes) - fn_mes_entregable_ant(t02_cod_proy, t02_version, t02_anio, t02_mes) AS duracion,
  fn_nro_prods_en_entregable(t02_cod_proy, t02_version, t02_anio, t02_mes) AS nro_productos,
  fn_desembolso_planeado_del_entregable_fte(t02_cod_proy, t02_version, t02_anio, t02_mes, _cod_fte_fe) AS desembolso_planeado
FROM  t02_entregable
WHERE t02_cod_proy = _proy
AND t02_version  = _ver
AND t02_anio  = _anio
ORDER BY t02_anio, t02_mes;
END $$
DELIMITER ;