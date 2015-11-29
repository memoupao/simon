/*
// -------------------------------------------------->
// AQ 2.1 [19-05-2014 05:45]
// RF-011 
// Obtiene el desembolso planeado acumulado al entregable
// Inclusión de Gastos de Supervisión
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

/*
// -------------------------------------------------->
// AQ 2.1 [19-05-2014 05:55]
// RF-011 
// Inclusión de Gastos de Supervisión
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_mnt_planificado_al_entregable;

DELIMITER $$

CREATE FUNCTION `fn_mnt_planificado_al_entregable`(_proy VARCHAR(10), _ver INT, _tipo INT, _anio INT, _mes INT) RETURNS double
BEGIN
  DECLARE _meta  DOUBLE;
  DECLARE _porc  DOUBLE;
  DECLARE _costo DOUBLE;
  DECLARE _plan  DOUBLE;
  DECLARE _mes_fin INT;

  SET _mes_fin = fn_numero_mes(_anio, _mes);

  -- Gastos Administrativos  
  IF _tipo = 4 THEN 
  
    SELECT fn_numero_meses_proy(_proy, _ver) into _meta;
    IF _ver > 1 THEN
      IF _meta mod 12 = 0 THEN
        SET _meta = _meta / fn_duracion_proy(_proy, 1);
      ELSE
        SET _meta =  _meta mod 12;
      END IF;
    END IF;
    
    SELECT SUM((t03_monto / _meta )*(_mes_fin))
      into _plan
    FROM t03_mp_gas_adm 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver;
      
      RETURN IFNULL(_plan,0);
    
  END IF;
  
  -- Línea Base
  IF _tipo = 5 THEN 
     
    IF fn_numero_mes(_anio, _mes) = fn_numero_meses_proy(_proy, _ver) or fn_numero_mes(_anio, _mes)=3 THEN
      SELECT (sum(t03_monto)/2)
        INTO _plan
        FROM t03_mp_linea_base 
       WHERE t02_cod_proy = _proy
         AND t02_version  = _ver;
    ELSE
      SELECT 0 into _plan;
    END IF;
     
    RETURN IFNULL(_plan,0);
     
  END IF;
  
  -- Imprevistos
  IF _tipo = 6 THEN 
    SELECT fn_numero_meses_proy(_proy, _ver) INTO _meta;
    
    IF _ver > 1 THEN
      IF _meta MOD 12 = 0 THEN
        SET _meta = _meta / fn_duracion_proy(_proy, 1);
      ELSE
        SET _meta =  _meta MOD 12;
      END IF;
    END IF;
    
    SELECT (t03_monto / _meta )*(_mes_fin)
      INTO _plan
    FROM t03_mp_imprevistos 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver;
      
      RETURN IFNULL(_plan,0);
  END IF;

  -- Supervisión
  IF _tipo = 7 THEN 
    SELECT fn_numero_meses_proy(_proy, _ver) INTO _meta;
    
    IF _ver > 1 THEN
      IF _meta MOD 12 = 0 THEN
        SET _meta = _meta / fn_duracion_proy(_proy, 1);
      ELSE
        SET _meta =  _meta MOD 12;
      END IF;
    END IF;
    
    SELECT (t03_monto / _meta )*(_mes_fin)
      INTO _plan
    FROM t03_mp_gas_supervision 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver;
      
      RETURN IFNULL(_plan,0);
  END IF;
  
  RETURN IFNULL(_plan,0);
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [19-05-2014 05:59]
// RF-005 
// Inclusión de Gastos de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_sel_inf_supervision_avance;

DELIMITER $$
CREATE PROCEDURE `sp_sel_inf_supervision_avance`(IN _proy VARCHAR(10), 
  IN _anio INT,
  IN _mes  INT)
BEGIN

DECLARE _mes_ini INT DEFAULT 1;
DECLARE _ver INT DEFAULT (_anio + 1);

SELECT
    v.t01_id_inst AS codigo,
    v.t01_sig_inst AS sigla,
    v.aporte AS aporte,
  IFNULL(
   ( SELECT SUM(gas.t41_gasto)
     FROM  t41_inf_financ_gasto gas 
     WHERE gas.t02_cod_proy=cost.t02_cod_proy
       AND gas.t41_fte_finan=v.t01_id_inst 
       AND fn_numero_mes(gas.t40_anio, gas.t40_mes) <= fn_numero_mes(_anio, _mes)
    )
   ,0) AS ejecutado,       
                    
  -- Componentes
  SUM(fn_mnt_planificado_acumulado_fte(cost.t02_cod_proy, cost.t02_version, 
                    cost.t08_cod_comp, cost.t09_cod_act, 
                    cost.t09_cod_sub , cost.t10_cod_cost,
                    v.t01_id_inst , _anio, _mes)) 

  +
  -- Personal
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    1, 0, p.t03_id_per, v.t01_id_inst, 
                                                    _mes_ini, fn_numero_mes(_anio, _mes)))
    FROM t03_mp_per_ftes p 
    WHERE p.t02_cod_proy = v.t02_cod_proy 
    AND p.t02_version = cost.t02_version 
    AND p.t03_id_inst = v.t01_id_inst
   ),0) 

  +
  -- Equipamiento
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    2, 0, e.t03_id_equi, v.t01_id_inst, 
                                                    _mes_ini, fn_numero_mes(_anio, _mes)))
    FROM t03_mp_equi_ftes e 
    WHERE e.t02_cod_proy = v.t02_cod_proy 
    AND e.t02_version = cost.t02_version 
    AND e.t03_id_inst = v.t01_id_inst
   ),0) 

  +
  -- Gastos de Funcionamiento    
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    3, g.t03_partida, g.t03_id_gasto, v.t01_id_inst, 
                                                    _mes_ini, fn_numero_mes(_anio, _mes)))
    FROM t03_mp_gas_fun_ftes g 
    INNER JOIN t03_mp_gas_fun_part parti ON (g.t02_cod_proy = parti.t02_cod_proy 
                                        AND g.t02_version = parti.t02_version 
                                        AND g.t03_partida = parti.t03_partida)
    WHERE g.t02_cod_proy = v.t02_cod_proy 
    AND g.t02_version = cost.t02_version 
    AND g.t03_id_inst = v.t01_id_inst
   ),0)

   +
  -- Gastos Administrativos 
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    4, 0, NULL, v.t01_id_inst, 
                                                    _mes_ini, fn_numero_mes(_anio, _mes))
   ),0)

  +
  -- Imprevistos 
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    6, 0, NULL, v.t01_id_inst, 
                                                    _mes_ini, fn_numero_mes(_anio, _mes))
   ),0)

  +
  -- Supervisión
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    7, 0, NULL, v.t01_id_inst, 
                                                    _mes_ini, fn_numero_mes(_anio, _mes))
   ),0)

   AS planeado,
   IFNULL(i.t30_obs, '') AS obs
FROM t10_cost_sub cost
INNER JOIN v_aportes_convenio v ON v.t02_cod_proy = cost.t02_cod_proy
LEFT JOIN t30_inf_se_observacion i ON (v.t02_cod_proy = i.t02_cod_proy AND v.t01_id_inst = i.t01_id_inst)
WHERE cost.t02_cod_proy= _proy
  AND cost.t02_version= _ver
  GROUP BY codigo;
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [19-05-2014 06:04]
// RF-005 
// Inclusión de Gastos de Supervisión
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_monto_planificado_mp_fte_periodo;

DELIMITER $$
CREATE FUNCTION `fn_monto_planificado_mp_fte_periodo`(_proy VARCHAR(10), _ver INT, _act INT, _sub INT, _cost INT, _fte INT, _mes_ini INT, _mes_fin INT) RETURNS DOUBLE
BEGIN
  DECLARE _meta  DOUBLE;
  DECLARE _porc  DOUBLE;
  DECLARE _costo DOUBLE;
  DECLARE _plan  DOUBLE;
  
  IF _act = 1 then 
  
    SELECT SUM(t03_mta)
      INTO _meta
      FROM  t03_mp_per_metas
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      and t03_id_per   = _cost
      AND (fn_numero_mes(t03_anio, t03_mes) BETWEEN _mes_ini AND _mes_fin );
    
    SELECT t03_remu_prom
      INTO _costo
      FROM  t03_mp_per
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t03_id_per   = _cost;
    
    SELECT  t03_porc
      INTO  _porc 
    FROM  t03_mp_per_ftes 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t03_id_per   = _cost
      AND t03_id_inst  = _fte;
    
    SELECT ((_meta * _costo)* (_porc/100)) INTO _plan; 
    
  END IF;
  
  IF _act = 2 THEN 
  
    SELECT SUM(t03_mta)
      INTO _meta
      FROM  t03_mp_equi_metas
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t03_id_equi  = _cost
      AND (fn_numero_mes(t03_anio, t03_mes) BETWEEN _mes_ini AND _mes_fin );
    
    SELECT t03_costo
      INTO _costo
      FROM  t03_mp_equi
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t03_id_equi  = _cost;
    
    SELECT  t03_porc
      INTO  _porc 
    FROM  t03_mp_equi_ftes 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t03_id_equi  = _cost
      AND t03_id_inst  = _fte;
    
    SELECT ((_meta * _costo)* (_porc/100)) INTO _plan; 
    
  END IF;
  
  IF _act = 3 THEN 
    SELECT SUM(t03_mta)
      INTO _meta
      FROM  t03_mp_gas_fun_metas
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      and t03_partida  = _sub
      AND (fn_numero_mes(t03_anio, t03_mes) BETWEEN _mes_ini AND _mes_fin );
    
    SELECT (t03_cant * t03_cu)
      INTO _costo
      FROM  t03_mp_gas_fun_cost
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      and t03_partida  = _sub
      AND t03_id_gasto = _cost;
    
    SELECT  t03_porc
      INTO  _porc 
    FROM  t03_mp_gas_fun_ftes 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t03_partida  = _sub
      and t03_id_gasto = _cost
      AND t03_id_inst  = _fte;
    
    SELECT ((_meta * _costo)* (_porc/100)) INTO _plan; 
    
  END IF;
  
  IF _act = 4 THEN 
  
    SELECT fn_numero_meses_proy(_proy, _ver) into _meta;
    IF _ver > 1 then
      IF _meta mod 12 = 0 then
        SET _meta = _meta / fn_duracion_proy(_proy, 1);
      ELSE
        SET _meta =  _meta mod 12;
      END IF;
    END IF;
    
    SELECT (t03_monto / _meta )*(_mes_fin - _mes_ini + 1)
      into _plan
    from   t03_mp_gas_adm 
    where t02_cod_proy = _proy
      and t02_version  = _ver
      and t03_id_inst  = _fte;
      
  END IF;
  
  IF _act = 5 THEN 
     
     IF  fn_numero_mes(_anio, _mes) = fn_numero_meses_proy(_proy, _ver) or fn_numero_mes(_anio, _mes)=3 then
    SELECT (SUM(t03_monto)/2)
      INTO _plan
      FROM   t03_mp_linea_base 
     WHERE t02_cod_proy = _proy
       AND t02_version  = _ver
       AND t03_id_inst  = _fte;
     ELSE
    SELECT 0 into _plan;
     END IF;
     
  END IF;
  
  IF _act = 6 THEN 
    SELECT fn_numero_meses_proy(_proy, _ver) INTO _meta;
    
    IF _ver > 1 THEN
      IF _meta MOD 12 = 0 THEN
        SET _meta = _meta / fn_duracion_proy(_proy, 1);
      ELSE
        SET _meta =  _meta MOD 12;
      END IF;
    END IF;
    
    SELECT (t03_monto / _meta )*(_mes_fin - _mes_ini + 1)
      INTO _plan
    FROM   t03_mp_imprevistos 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver
      AND t03_id_inst  = _fte;
      
  END IF;

  IF _act = 7 THEN 
    SELECT fn_numero_meses_proy(_proy, _ver) INTO _meta;
    
    IF _ver > 1 THEN
      IF _meta MOD 12 = 0 THEN
        SET _meta = _meta / fn_duracion_proy(_proy, 1);
      ELSE
        SET _meta =  _meta MOD 12;
      END IF;
    END IF;
    
    SELECT (t03_monto / _meta )*(_mes_fin - _mes_ini + 1)
      INTO _plan
    FROM t03_mp_gas_supervision 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver
      AND t03_id_inst  = _fte;
      
  END IF;
  
  RETURN IFNULL(_plan,0) ;
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [19-05-2014 08:00]
// RF-005 
// Obtiene los desembolsos realizados del entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_sel_desembolsos_del_entregable;
DROP PROCEDURE IF EXISTS sp_sel_desembolsados_del_entregable;

DELIMITER $$
CREATE PROCEDURE `sp_sel_desembolsados_del_entregable`(IN _proy VARCHAR(10), 
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
  ORDER BY t60_fch_depo, t60_fch_giro;
  
END $$
DELIMITER ;