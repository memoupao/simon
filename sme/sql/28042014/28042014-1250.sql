/*
// -------------------------------------------------->
// AQ 2.1 [28-04-2014 12:50]
// RF-005 
// --------------------------------------------------<
*/
DROP VIEW IF EXISTS v_aportes_convenio;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER
 VIEW v_aportes_convenio AS 

SELECT p.t02_cod_proy AS t02_cod_proy,
  t01_sig_inst,
  i.t01_id_inst AS t01_id_inst,
  ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, f.t01_id_inst), 2) AS aporte
FROM t02_dg_proy p 
LEFT JOIN t02_fuente_finan f ON f.t02_cod_proy = p.t02_cod_proy
LEFT JOIN t01_dir_inst i ON f.t01_id_inst = i.t01_id_inst
LEFT JOIN adm_tablas_aux e ON p.t02_estado = e.codi
WHERE p.t02_version = 1 
AND e.cod_ext IN (1,4,5);

/*
// -------------------------------------------------->
// AQ 2.1 [28-04-2014 17:25]
// RF-005 
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
                                                    3, 0, g.t03_id_gasto, v.t01_id_inst, 
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

  -- Analizar Supervisión

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


DROP FUNCTION IF EXISTS fn_mnt_planificado_acumulado_fte;

DELIMITER $$

CREATE FUNCTION fn_mnt_planificado_acumulado_fte(_proy VARCHAR(10), 
    _ver  INT, 
    _comp int,
    _act  int, 
    _sub  int,
    _cost int, 
    _fte  int,
    _anio int,
    _mes  int) RETURNS double
    DETERMINISTIC
BEGIN
    declare _meta  double;
    declare _porc  double;
    declare _costo double ;
    declare _plan  double ;
    
    SELECT SUM(t09_mta)
      INTO _meta
      FROM  t09_sub_act_mtas
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t08_cod_comp = _comp
      AND t09_cod_act  = _act
      AND t09_cod_sub  = _sub 
      AND fn_numero_mes(t09_anio, t09_mes) <= fn_numero_mes(_anio, _mes);
        
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


DROP PROCEDURE IF EXISTS sp_test;

DROP PROCEDURE IF EXISTS fn_planificado_acumulado_mp_fte;

/*
// -------------------------------------------------->
// AQ 2.1 [29-04-2014 13:23]
// RF-005 
// --------------------------------------------------<
*/

DROP TABLE IF EXISTS t30_inf_se_observacion;
CREATE TABLE IF NOT EXISTS t30_inf_se_observacion (
  t02_cod_proy varchar(10) NOT NULL,
  t02_anio int(11) NOT NULL DEFAULT '0' COMMENT 'Año de Ejecución',
  t02_entregable int(11) NOT NULL COMMENT 'Entregable asociado',
  t01_id_inst int(11) NOT NULL,
  t30_obs text,
  usr_crea char(20) DEFAULT NULL,
  fch_crea datetime DEFAULT NULL,
  usr_actu char(20) DEFAULT NULL,
  fch_actu datetime DEFAULT NULL,
  est_audi char(1) DEFAULT NULL,
  PRIMARY KEY (t02_cod_proy,t02_anio,t02_entregable,t01_id_inst)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de Observaciones encontradas para el Informe de Supervisión';

ALTER TABLE t30_inf_se_observacion
  ADD CONSTRAINT t30_inf_se_observacion_ibfk FOREIGN KEY (t02_cod_proy, t02_anio, t02_entregable) REFERENCES t30_inf_se (t02_cod_proy, t02_anio, t02_entregable) ON DELETE CASCADE ON UPDATE CASCADE;
