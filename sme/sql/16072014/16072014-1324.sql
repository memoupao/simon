/*
// -------------------------------------------------->
// AQ 2.1 [16-07-2014 13:24]
// Observaciones
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
  DECLARE _lbtmp DOUBLE;
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

  SELECT SUM(IFNULL(t03_monto,0)) INTO _lbtmp FROM t03_mp_linea_base WHERE t02_cod_proy = _proy AND t02_version = 1;
  SET _linebase = (CASE WHEN _idfte = _fteFE THEN _lbtmp ELSE 0 END);

  IF _idfte = _fteFE THEN
    SELECT IFNULL(SUM(t60_mto), 0) INTO _desembolsado FROM t60_desembolsado d 
    JOIN t02_dg_proy proy ON (d.t02_cod_proy = proy.t02_cod_proy AND proy.t02_version=fn_ult_version_proy(proy.t02_cod_proy))
    WHERE proy.t02_cod_proy = _proy
    AND LAST_DAY(DATE_ADD(proy.t02_fch_ini, INTERVAL fn_numero_mes(_anio, _mes)-1 MONTH)) >= d.t60_fch_depo;
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
      THEN  (
        SELECT IFNULL(SUM(t60_mto), 0) FROM t60_desembolsado d 
        JOIN t02_dg_proy proy ON (d.t02_cod_proy = proy.t02_cod_proy AND proy.t02_version=fn_ult_version_proy(proy.t02_cod_proy))
        WHERE proy.t02_cod_proy = _proy
        AND LAST_DAY(DATE_ADD(proy.t02_fch_ini, INTERVAL i.nummes-1 MONTH)) >= d.t60_fch_depo 
        AND FIRST_DAY(DATE_ADD(proy.t02_fch_ini, INTERVAL i.nummes-1 MONTH)) <= d.t60_fch_depo)
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
// AQ 2.1 [16-07-2014 15:44]
// Observaciones
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS `fn_monto_total_desemb_plan_periodo_mes`;

DELIMITER $$
CREATE FUNCTION `fn_monto_total_desemb_plan_periodo_mes`( 
              pProy VARCHAR(10), 
              pVer INT, 
              pFte  INT,
              pMesIni INT, 
              pMesFin INT
            ) RETURNS double
    DETERMINISTIC
BEGIN
    DECLARE aMontoPlanSubAct  DOUBLE DEFAULT 0;
    DECLARE aMontoPlanPersonal  DOUBLE DEFAULT 0;
    DECLARE aMontoPlanEquipo  DOUBLE DEFAULT 0;
    DECLARE aMontoPlanGasFunc DOUBLE DEFAULT 0;
    DECLARE aMontoPlanGasAdmin  DOUBLE DEFAULT 0;
    DECLARE aMontoPlanLineaBase DOUBLE DEFAULT 0;
    DECLARE aMontoPlanImprev  DOUBLE DEFAULT 0;
    DECLARE aMontoPlanSuperv  DOUBLE DEFAULT 0;


    
  SELECT    SUM(costo_mes) AS costo_mes
  INTO    aMontoPlanSubAct
  FROM (
    SELECT    CONCAT(cost.t08_cod_comp,'.', cost.t09_cod_act, '.', cost.t09_cod_sub, '.', cost.t10_cod_cost) AS actividad,
          SUM(cost.t10_cu * cost.t10_cant * mta.t09_mta * (fte.t10_porc / 100)) AS costo_mes
    FROM    t09_sub_act_mtas mta
    INNER JOIN  t09_subact    sub  ON(mta.t02_cod_proy = sub.t02_cod_proy 
                    AND mta.t02_version = sub.t02_version 
                    AND mta.t08_cod_comp = sub.t08_cod_comp 
                    AND mta.t09_cod_act = sub.t09_cod_act 
                    AND mta.t09_cod_sub = sub.t09_cod_sub)
    LEFT  JOIN  t10_cost_sub  cost ON(sub.t02_cod_proy = cost.t02_cod_proy 
                    AND sub.t02_version = cost.t02_version 
                    AND sub.t08_cod_comp = cost.t08_cod_comp 
                    AND sub.t09_cod_act = cost.t09_cod_act 
                    AND sub.t09_cod_sub = cost.t09_cod_sub)
    LEFT  JOIN  t10_cost_fte    fte  ON(cost.t02_cod_proy = fte.t02_cod_proy 
                    AND cost.t02_version = fte.t02_version 
                    AND cost.t08_cod_comp = fte.t08_cod_comp 
                    AND cost.t09_cod_act = fte.t09_cod_act 
                    AND cost.t09_cod_sub = fte.t09_cod_sub 
                    AND cost.t10_cod_cost = fte.t10_cod_cost 
                    AND fte.t01_id_inst = pFte)
    WHERE     mta.t02_cod_proy = pProy
        AND mta.t02_version  = pVer 
        AND fn_numero_mes(mta.t09_anio, mta.t09_mes) = pMesIni
        AND fte.t10_porc > 0
    GROUP BY   cost.t08_cod_comp, cost.t09_cod_act, cost.t09_cod_sub, cost.t10_cod_cost
  ) t;
  SELECT    SUM(costo_mes) AS costo_mes
  INTO    aMontoPlanPersonal
  FROM (
    SELECT    per.t03_id_per AS actividad,
          SUM(mta.t03_mta * per.t03_remu_prom * (fte.t03_porc / 100)) AS costo_mes
    FROM    t03_mp_per_metas mta
    INNER JOIN  t03_mp_per      per ON (mta.t02_cod_proy = per.t02_cod_proy 
                      AND mta.t02_version = per.t02_version 
                      AND mta.t03_id_per = per.t03_id_per)
    LEFT  JOIN  t03_mp_per_ftes   fte ON (per.t02_cod_proy = fte.t02_cod_proy 
                      AND per.t02_version = fte.t02_version 
                      AND per.t03_id_per = fte.t03_id_per 
                      AND fte.t03_id_inst = pFte)
    WHERE     mta.t02_cod_proy = pProy
        AND mta.t02_version  = pVer 
        AND fn_numero_mes(mta.t03_anio, mta.t03_mes) BETWEEN pMesIni AND pMesFin 
        AND fte.t03_porc  > 0
    GROUP BY per.t03_id_per
  ) t;
    
    
  SELECT    SUM(costo_mes) AS costo_mes
  INTO    aMontoPlanEquipo
  FROM (
    SELECT    equ.t03_id_equi AS actividad,
          SUM(mta.t03_mta * t03_costo * (fte.t03_porc / 100)) AS costo_mes
    FROM    t03_mp_equi_metas   mta
    INNER JOIN  t03_mp_equi     equ ON (mta.t02_cod_proy = equ.t02_cod_proy 
                      AND mta.t02_version = equ.t02_version 
                      AND mta.t03_id_equi = equ.t03_id_equi)
    LEFT  JOIN  t03_mp_equi_ftes  fte ON (equ.t02_cod_proy = fte.t02_cod_proy 
                      AND equ.t02_version = fte.t02_version 
                      AND equ.t03_id_equi = fte.t03_id_equi 
                      AND fte.t03_id_inst = pFte)
    WHERE     mta.t02_cod_proy = pProy
        AND mta.t02_version  = pVer 
        AND fn_numero_mes(mta.t03_anio, mta.t03_mes) BETWEEN pMesIni AND pMesFin 
        AND fte.t03_porc  > 0
    GROUP BY equ.t03_id_equi
  ) t;
    
  SELECT    SUM(costo_mes) AS costo_mes
  INTO    aMontoPlanGasFunc
  FROM (
    SELECT    cost.t03_id_gasto AS actividad,
          SUM(cost.t03_cu *   cost.t03_cant * mta.t03_mta * (fte.t03_porc / 100)) AS costo_mes
    FROM    t03_mp_gas_fun_metas  mta
    JOIN    t03_mp_gas_fun_part   par   ON (mta.t02_cod_proy = par.t02_cod_proy 
                          AND mta.t02_version = par.t02_version 
                          AND mta.t03_partida = par.t03_partida)
    LEFT JOIN t03_mp_gas_fun_cost   cost  ON (cost.t02_cod_proy = par.t02_cod_proy 
                          AND cost.t02_version = par.t02_version 
                          AND cost.t03_partida = par.t03_partida)
    LEFT JOIN t03_mp_gas_fun_ftes   fte   ON (fte.t02_cod_proy = cost.t02_cod_proy 
                          AND fte.t02_version = cost.t02_version 
                          AND fte.t03_partida = cost.t03_partida 
                          AND fte.t03_id_gasto = cost.t03_id_gasto
                          AND fte.t03_id_inst = pFte)
    WHERE     mta.t02_cod_proy = pProy
        AND mta.t02_version  = pVer 
        AND fn_numero_mes(mta.t03_anio, mta.t03_mes) BETWEEN pMesIni AND pMesFin 
        AND fte.t03_porc  > 0
    GROUP BY cost.t03_id_gasto
  ) t;
  SELECT    SUM(meta * monto) AS costo_mes
  INTO    aMontoPlanGasAdmin
  FROM (
    SELECT    4 AS actividad,
          ((pMesFin -pMesIni) + 1) AS meta,
          ( adm.t03_monto / fn_numero_meses_proy(adm.t02_cod_proy, adm.t02_version)) AS monto,
          100 AS porc
    FROM    t03_mp_gas_adm adm
    WHERE   adm.t02_cod_proy = pProy
        AND adm.t02_version  = pVer 
        AND adm.t03_id_inst  = pFte
        AND adm.t03_monto  > 0 
        AND fn_numero_meses_proy(adm.t02_cod_proy, adm.t02_version) >= pMesFin
  ) t;

  SELECT    SUM(meta * monto) AS costo_mes
  INTO    aMontoPlanImprev
  FROM (
    SELECT    6 AS actividad,
          ((pMesFin -pMesIni) + 1) AS meta,
          ( imp.t03_monto / fn_numero_meses_proy(imp.t02_cod_proy, imp.t02_version)) AS monto,
          100 AS porc
    FROM    t03_mp_imprevistos imp
    WHERE   imp.t02_cod_proy = pProy
        AND imp.t02_version  = pVer 
        AND imp.t03_monto  > 0 
        AND fn_numero_meses_proy(imp.t02_cod_proy, imp.t02_version) >= pMesFin
  ) t;

  SELECT    SUM(meta * monto) AS costo_mes
  INTO    aMontoPlanSuperv
  FROM (
    SELECT    6 AS actividad,
          ((pMesFin -pMesIni) + 1) AS meta,
          ( sup.t03_monto / fn_numero_meses_proy(sup.t02_cod_proy, sup.t02_version)) AS monto,
          100 AS porc
    FROM    t03_mp_gas_supervision sup
    WHERE   sup.t02_cod_proy = pProy
        AND sup.t02_version  = pVer 
        AND sup.t03_monto  > 0 
        AND fn_numero_meses_proy(sup.t02_cod_proy, sup.t02_version) >= pMesFin
  ) t;

  RETURN  IFNULL(aMontoPlanSubAct, 0) + IFNULL(aMontoPlanPersonal, 0)  + IFNULL(aMontoPlanEquipo, 0) + 
      IFNULL(aMontoPlanGasFunc, 0) + IFNULL(aMontoPlanGasAdmin, 0) + IFNULL(aMontoPlanLineaBase, 0) + 
      IFNULL(aMontoPlanImprev, 0) + IFNULL(aMontoPlanSuperv, 0);
  
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS FIRST_DAY;

DELIMITER $$
CREATE FUNCTION FIRST_DAY(day DATE)
RETURNS DATE DETERMINISTIC
BEGIN
  RETURN ADDDATE(LAST_DAY(SUBDATE(day, INTERVAL 1 MONTH)), 1);
END $$
DELIMITER ;