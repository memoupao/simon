/*
// -------------------------------------------------->
// AQ 2.1 [03-06-2014 05:01]
// Depuración de Trimestres
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_rpt_inf_financ_anexo_01`;

DELIMITER $$
CREATE PROCEDURE `sp_rpt_inf_financ_anexo_01`( 
            IN _proy VARCHAR(10),
            IN _anio INT,
            IN _mes INT,
            IN _idfte INT)
BEGIN
  DECLARE _nummes INT DEFAULT fn_numero_mes(_anio, _mes);
  DECLARE _totalproy DOUBLE;
  DECLARE _linebase DOUBLE;
  DECLARE _desembolsado DOUBLE;
  DECLARE _saldo_desemb DOUBLE;
  DECLARE _fteFE   INT DEFAULT 10;

  IF _nummes = 0 THEN
    SELECT i.t40_anio, i.t40_mes, fn_numero_mes(i.t40_anio, i.t40_mes)
      INTO _anio, _mes, _nummes
      FROM t40_inf_financ i
     WHERE i.t02_cod_proy = _proy 
     ORDER BY  fn_numero_mes(i.t40_anio, i.t40_mes) DESC 
     LIMIT 1;
  END IF;

  SET _totalproy = fn_total_aporte_fuentes_financ3(_proy,1, _idfte );
  SET _linebase = (CASE WHEN _idfte = _fteFE THEN fn_mp_linea_base(_proy,1) ELSE 0 END);

  IF _idfte = _fteFE THEN
    -- SELECT IFNULL(SUM(d.t60_mto_des),0)
    --   INTO _desembolsado
    --   FROM t60_ejecucion_desemb d 
    --  WHERE d.t02_cod_proy = _proy
    --    AND d.t60_id_trim <= TRUNCATE((_nummes / 3),0) ;
    SELECT 100 INTO _desembolsado; 
  ELSE
    SELECT 0 INTO _desembolsado; 
  END IF;

  SET _saldo_desemb = (( _totalproy - _linebase ) - _desembolsado);

  DROP TEMPORARY TABLE IF EXISTS tmpInfMeses;
  DROP TEMPORARY TABLE IF EXISTS tmpInfMeses2;

  CREATE TEMPORARY TABLE IF NOT EXISTS tmpInfMeses
  SELECT  '' AS concepto,
    i.anio,
    i.mes, 
    i.nummes,
    -- i.trimestre,
    
    (CASE WHEN _idfte = _fteFE 
      THEN  (SELECT IFNULL(SUM(dd.t60_mto_des) ,0)
           FROM t60_ejecucion_desemb dd 
          WHERE dd.t02_cod_proy = _proy 
            AND DATE_FORMAT(dd.t60_fch_giro,'%Y%m') BETWEEN i.peri_real AND i.peri_real ) 
      ELSE 0
      END) AS mto_tot_des,
    
    fn_monto_total_desemb_plan_periodo_mes(i.proyecto, 1, _idfte , i.nummes, i.nummes) AS tot_mto_pre,
    i.monto_ejec AS gasto_ejecutado_sc,
    0 AS gasto_ejecutado_cc, 
    i.otro_ing AS otros_ingresos,
    i.abon_bco AS abono_bancos
  FROM  ( 
    SELECT  inf.t02_cod_proy AS proyecto,
      inf.t40_anio     AS anio, 
      inf.t40_mes      AS mes,
      fn_numero_mes(inf.t40_anio, inf.t40_mes) AS nummes,
      -- (CASE WHEN (fn_numero_mes(inf.t40_anio, inf.t40_mes) MOD 3)=0 THEN TRUNCATE(fn_numero_mes(inf.t40_anio, inf.t40_mes) / 3,0) ELSE TRUNCATE(fn_numero_mes(inf.t40_anio, inf.t40_mes) / 3,0) + 1 END) AS trimestre,
      (
      SELECT SUM(gas.t41_gasto) 
           FROM  t41_inf_financ_gasto gas 
           WHERE gas.t02_cod_proy= inf.t02_cod_proy
             AND gas.t40_anio=inf.t40_anio
             AND gas.t40_mes=inf.t40_mes
             AND gas.t41_fte_finan = _idfte
      ) AS monto_ejec,
      IFNULL(inf.t40_abo_bco,0) AS abon_bco,
      IFNULL(inf.t40_otro_ing,0) AS otro_ing,
      DATE_FORMAT(DATE_ADD(proy.t02_fch_ini, INTERVAL fn_numero_mes(inf.t40_anio, inf.t40_mes)-1 MONTH), '%Y%m') AS peri_real
    FROM    t40_inf_financ  inf
    LEFT JOIN t02_dg_proy     proy ON (inf.t02_cod_proy = proy.t02_cod_proy AND proy.t02_version=fn_ult_version_proy(proy.t02_cod_proy))
    WHERE   inf.t02_cod_proy = _proy
      AND fn_numero_mes(inf.t40_anio, inf.t40_mes) <= _nummes
    ORDER BY inf.t40_anio, inf.t40_mes, inf.t40_periodo
    ) AS i;
   
  CREATE TEMPORARY TABLE IF NOT EXISTS tmpInfMeses2
  SELECT  CONCAT('Mes de ', fn_nom_periodo(_proy, i.anio, i.mes) ) AS concepto,
    'mes' AS tipo,
    -- CONCAT(i.trimestre,'.', i.nummes ) AS orden,
    CONCAT(i.anio,'.', i.nummes ) AS orden,
    i.anio,
    i.mes, 
    i.nummes,
    -- i.trimestre,
    i.mto_tot_des,
    i.tot_mto_pre,
    i.gasto_ejecutado_sc,
    i.gasto_ejecutado_cc,
    i.otros_ingresos,
    i.abono_bancos
  FROM tmpInfMeses i;
  
  -- INSERT INTO tmpInfMeses2
  -- SELECT  CONCAT('Trimestre ', gi.trimestre, ' \n(',fn_nom_periodo(_proy, MAX(gi.anio), MAX(gi.mes)-2),' - ', fn_nom_periodo(_proy, MAX(gi.anio), MAX(gi.mes)),')') AS concepto,
  --   'trim' AS tipo,
  --   gi.trimestre AS orden,
  --   MAX(gi.anio),
  --   MAX(gi.mes), 
  --   MAX(gi.nummes),
  --   gi.trimestre,
  --   SUM(gi.mto_tot_des),
  --   SUM(gi.tot_mto_pre),
  --   SUM(gi.gasto_ejecutado_sc),
  --   SUM(gi.gasto_ejecutado_cc),
  --   SUM(gi.otros_ingresos),
  --   SUM(gi.abono_bancos)
  -- FROM tmpInfMeses gi
  -- GROUP BY gi.trimestre;

  INSERT INTO tmpInfMeses2(concepto, tipo, orden , mto_tot_des, tot_mto_pre, nummes) VALUES 
  ('MONTO TOTAL DEL PROYECTO ', 'a', 0, 0,_totalproy, 0),
  ('Linea de Base Fondoempleo', 'b', 0, 0, _linebase, 0),
  ('Total a Transferir ', 'c', 0, 0, (_totalproy - _linebase), 0),
  ('Monto desembolsado a la fecha ', 'd', 0, _desembolsado,0 , 0),
  ('Saldo a desembolsar ', 'e', 0, _saldo_desemb ,0 , 0);

  SELECT  i.concepto, 
    i.tipo,
    i.anio,
    i.mes, 
    i.nummes,
    -- i.trimestre,
    i.mto_tot_des,
    i.tot_mto_pre,
    i.gasto_ejecutado_sc,
    i.gasto_ejecutado_cc,
    i.otros_ingresos,
    i.abono_bancos
    FROM tmpInfMeses2 i 
  ORDER BY orden ASC, tipo ASC; 
 
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [03-06-2014 06:58]
// Inclusión de Gastos de Supervisión
// --------------------------------------------------<
*/
DROP VIEW IF EXISTS `v_actividades`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER
  VIEW `v_actividades` AS 
  SELECT 
    `a`.`t02_cod_proy` AS `t02_cod_proy`,
    `a`.`t02_version` AS `t02_version`,
    CONCAT(`a`.`t08_cod_comp`,'.',`a`.`t09_cod_act`) AS `codigo`,
    `a`.`t09_act` AS `descripcion` 
  FROM `t09_act` `a` 

  UNION ALL 
  SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.1' AS `codigo`,
    'Personal del Proyecto' AS `descripcion` 
  FROM `t02_dg_proy` `p` 

  UNION ALL 
  SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.2' AS `codigo`,
    'Equipamiento del Proyecto' AS `descripcion` 
  FROM `t02_dg_proy` `p` 

  UNION ALL 
  SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.3' AS `codigo`,
    'Gastos de Funcionamientos' AS `descripcion` 
  FROM `t02_dg_proy` `p` 

  UNION ALL 
  SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.4' AS `codigo`,
    'Gastos Administrativos' AS `descripcion` 
  FROM `t02_dg_proy` `p` 

  UNION ALL 
  SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.5' AS `codigo`,
    'Línea de Base Y Evaluación de Impacto' AS `descripcion` 
  FROM `t02_dg_proy` `p` 

  UNION ALL SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.6' AS `codigo`,
    'Imprevistos' AS `descripcion` 
  FROM `t02_dg_proy` `p`

  UNION ALL SELECT DISTINCT 
    `p`.`t02_cod_proy` AS `t02_cod_proy`,
    `p`.`t02_version` AS `t02_version`,
    '10.7' AS `codigo`,
    'Gastos de Supervisión' AS `descripcion` 
  FROM `t02_dg_proy` `p`;

/*
// -------------------------------------------------->
// AQ 2.1 [03-06-2014 09:21]
// Obtiene resumen presupuestal
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_inf_entregable_resumen_presup;

DELIMITER $$
CREATE PROCEDURE `sp_inf_entregable_resumen_presup`(IN _proy VARCHAR(10), 
  IN _ver INT,
  IN _anio INT,
  IN _mes INT) 
BEGIN

DECLARE _cod_fte_fe INT DEFAULT 10;

SELECT t.t02_anio AS anio, 
       t.t02_mes AS mes,
       t.posicion AS posicion,
       fn_numero_mes(t.t02_anio, t.t02_mes) - fn_mes_entregable_ant(t.t02_cod_proy, t.t02_version, t.t02_anio, t.t02_mes) AS duracion,
       fn_nro_prods_en_entregable(t.t02_cod_proy, t.t02_version, t.t02_anio, t.t02_mes) AS nro_productos,
       fn_desembolso_planeado_del_entregable_fte(t.t02_cod_proy, t.t02_version, t.t02_anio, t.t02_mes, _cod_fte_fe) AS desembolso_planeado
  FROM (SELECT t02_cod_proy, t02_version, t02_anio, t02_mes, @rownum := @rownum + 1 AS posicion
        FROM  t02_entregable
        JOIN (SELECT @rownum := 0) r
            WHERE t02_cod_proy = _proy
              AND t02_version  = _ver
              AND t02_anio  = _anio
        ORDER BY t02_anio, t02_mes) t;
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [03-06-2014 09:48]
// Obtiene el número de productos a controlar en un
// entregable
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_nro_prods_en_entregable;

DELIMITER $$

CREATE FUNCTION fn_nro_prods_en_entregable(_proy VARCHAR(10), 
    _ver  INT, 
    _anio INT,
    _mes INT) RETURNS INT
    DETERMINISTIC
BEGIN
    DECLARE _total INT;
    
    SELECT COUNT(DISTINCT t09_cod_act)
      INTO _total
      FROM  t02_entregable_act_ind
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t02_anio = _anio
      AND t02_mes = _mes;
        
    RETURN IFNULL(_total, 0);
    
    END $$
DELIMITER ;

