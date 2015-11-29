/*
// -------------------------------------------------->
// AQ 2.1 [21-05-2014 01:35]
// RF-011 
// Obtiene el desembolso planeado acumulado al entregable por fuente
// Fixed [22-05-2014 02:17]: Cálculo de los Gastos de Funcionamiento
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_del_entregable_fte;

DELIMITER $$
CREATE FUNCTION `fn_desembolso_planeado_del_entregable_fte`(_proy VARCHAR(10), 
  _ver INT,
  _anio INT,
  _mes INT,
  _fte INT) RETURNS DOUBLE
BEGIN

DECLARE _entregable_act INT DEFAULT 0;
DECLARE _entregable_ant INT DEFAULT 0;
DECLARE _planeado DOUBLE DEFAULT 0;

SET _entregable_ant = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
SET _entregable_act = fn_numero_mes(_anio, _mes);

SELECT
  -- Componentes
  SUM(fn_costo_componentes_planificado_entregable_fte(cost.t02_cod_proy, cost.t02_version, 
                    cost.t08_cod_comp, cost.t09_cod_act, 
                    cost.t09_cod_sub , cost.t10_cod_cost,
                    _fte , _entregable_ant, _entregable_act))

  +
  -- Personal
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    1, 0, p.t03_id_per, p.t03_id_inst, 
                                                    _entregable_ant, _entregable_act))
    FROM t03_mp_per_ftes p 
    WHERE p.t02_cod_proy = cost.t02_cod_proy 
    AND p.t02_version = cost.t02_version 
    AND p.t03_id_inst = _fte
   ),0) 

  +
  -- Equipamiento
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    2, 0, e.t03_id_equi, e.t03_id_inst, 
                                                    _entregable_ant, _entregable_act))
    FROM t03_mp_equi_ftes e 
    WHERE e.t02_cod_proy = cost.t02_cod_proy 
    AND e.t02_version = cost.t02_version 
    AND e.t03_id_inst = _fte
   ),0) 

  +
  -- Gastos de Funcionamiento
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    3, g.t03_partida, g.t03_id_gasto, g.t03_id_inst, 
                                                    _entregable_ant, _entregable_act))
    FROM t03_mp_gas_fun_ftes g 
    INNER JOIN t03_mp_gas_fun_part parti ON (g.t02_cod_proy = parti.t02_cod_proy 
                                        AND g.t02_version = parti.t02_version 
                                        AND g.t03_partida = parti.t03_partida)
    WHERE g.t02_cod_proy = cost.t02_cod_proy 
    AND g.t02_version = cost.t02_version 
    AND g.t03_id_inst = _fte
   ),0)

   +
  -- Gastos Administrativos 
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    4, 0, NULL, _fte, 
                                                    _entregable_ant, _entregable_act)
   ),0)

  +
  -- Imprevistos 
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    6, 0, NULL, _fte, 
                                                    _entregable_ant, _entregable_act)
   ),0)

  +
  -- Supervisión
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    7, 0, NULL, _fte, 
                                                    _entregable_ant, _entregable_act)
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
// AQ 2.1 [21-05-2014 02:16]
// RF-011 
// Obtiene el costo de componente planificado del entregable por fuente
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_costo_componentes_planificado_entregable_fte;

DELIMITER $$

CREATE FUNCTION fn_costo_componentes_planificado_entregable_fte(_proy VARCHAR(10), 
    _ver  INT, 
    _comp int,
    _act  int, 
    _sub  int,
    _cost int, 
    _fte  int,
    _mes_inicio int,
    _mes_fin int) RETURNS double
    DETERMINISTIC
BEGIN
    declare _meta  double;
    declare _porc  double;
    declare _costo double;
    declare _plan  double;
    
    SELECT SUM(t09_mta)
      INTO _meta
      FROM  t09_sub_act_mtas
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t08_cod_comp = _comp
      AND t09_cod_act  = _act
      AND t09_cod_sub  = _sub 
      AND fn_numero_mes(t09_anio, t09_mes) BETWEEN _mes_inicio AND _mes_fin;
        
    select t10_cost_parc
      into _costo
      from  t10_cost_sub
    where t02_cod_proy = _proy
      and t02_version  = _ver 
      and t08_cod_comp = _comp
      and t09_cod_act  = _act
      and t09_cod_sub  = _sub
      and t10_cod_cost = _cost ;
        
    SELECT  t10_porc
      into  _porc 
    FROM    t10_cost_fte 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t08_cod_comp = _comp
      AND t09_cod_act  = _act
      AND t09_cod_sub  = _sub
      AND t10_cod_cost = _cost 
      and t01_id_inst  = _fte ;
    
    select ((_meta * _costo)* (_porc/100)) into _plan;
    
    RETURN ifnull(_plan,0) ;
    
    END $$
DELIMITER ;