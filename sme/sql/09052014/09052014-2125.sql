/*
// -------------------------------------------------------------------------->
// AQ 2.1 [07-05-2014 13:04]
// RF-
// Relacionado a: RF-009 - Informe POA - Agregar columna Meta Física Total Vigente 
// y revisar columna Variación
// --------------------------------------------------------------------------<
*/
DROP TABLE IF EXISTS `t60_desembolsado`;
CREATE TABLE IF NOT EXISTS `t60_desembolsado` (
  `t60_id` INT(4) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `t02_cod_proy` VARCHAR(10) NOT NULL,
  `t02_anio` TINYINT(4) NOT NULL COMMENT 'Año del Entregable',
  `t02_mes` TINYINT(4) NOT NULL COMMENT 'Mes del Entregable',
  `t60_tip_pago` INT(11) DEFAULT NULL COMMENT 'Tipo de Pago (Adelanto o Saldo)',
  `t60_mod_pago` INT(11) DEFAULT NULL COMMENT 'Modalidad de Pago (Transferencia, Cheque, etc)',
  `t60_inst_ori` INT(11) DEFAULT NULL,
  `t60_cta_ori` INT(11) DEFAULT NULL COMMENT 'Cuenta Origen de la Institucion FE',
  `t01_id_inst` INT(11) DEFAULT NULL COMMENT 'Codigo de la Institucion a Girar',
  `t01_id_cta` INT(11) DEFAULT NULL COMMENT 'Codigo de la Cuenta de la Institucion',
  `t60_benef` VARCHAR(150) DEFAULT NULL COMMENT 'Nombre del Beneficiario a Girar',
  `t60_fch_giro` DATE DEFAULT NULL COMMENT 'Fecha de Giro',
  `t60_fch_depo` DATE DEFAULT NULL COMMENT 'Fecha de Deposito',
  `t60_mto` DOUBLE DEFAULT NULL,
  `t60_cheque` VARCHAR(100) DEFAULT NULL,
  `t60_obs` TEXT,
  `usr_crea` CHAR(20) DEFAULT NULL,
  `fch_crea` DATETIME DEFAULT NULL,
  `usr_actu` CHAR(20) DEFAULT NULL,
  `fch_actu` DATETIME DEFAULT NULL,
  `est_audi` CHAR(1) DEFAULT NULL,
  PRIMARY KEY (`t60_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*
// -------------------------------------------------->
// AQ 2.1 [08-05-2014 22:49]
// RF-011 
// Obtiene el desembolso planeado acumulado al entregable
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

  -- Analizar Supervisión

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
// AQ 2.1 [08-05-2014 22:49]
// RF-011 
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
  
    select fn_numero_meses_proy(_proy, _ver) into _meta ;
    if _ver > 1 then
      if _meta mod 12 = 0 then
        set _meta = _meta / fn_duracion_proy(_proy, 1) ;
      else
        SET _meta =  _meta mod 12 ;
      end if ;
    end if ;
    
    select SUM((t03_monto / _meta )*(_mes_fin))
      into _plan
    from   t03_mp_gas_adm 
    where t02_cod_proy = _proy
      and t02_version  = _ver;
      
      RETURN IFNULL(_plan,0) ;
    
  END IF ;
  
  -- Línea Base
  IF _tipo = 5 THEN 
     
     if  fn_numero_mes(_anio, _mes) = fn_numero_meses_proy(_proy, _ver) or fn_numero_mes(_anio, _mes)=3 then
    SELECT (sum(t03_monto)/2)
      INTO _plan
      FROM   t03_mp_linea_base 
     WHERE t02_cod_proy = _proy
       AND t02_version  = _ver;
     else
    select 0 into _plan ;
     end if ;
     
     RETURN IFNULL(_plan,0) ;
     
  END IF ;
  
  -- Imprevistos
  IF _tipo = 6 THEN 
    SELECT fn_numero_meses_proy(_proy, _ver) INTO _meta ;
    
    IF _ver > 1 THEN
      IF _meta MOD 12 = 0 THEN
        SET _meta = _meta / fn_duracion_proy(_proy, 1) ;
      ELSE
        SET _meta =  _meta MOD 12 ;
      END IF ;
    END IF ;
    
    SELECT (t03_monto / _meta )*(_mes_fin)
      INTO _plan
    FROM   t03_mp_imprevistos 
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver;
      
      RETURN IFNULL(_plan,0) ;
  END IF ;

  -- Analizar Supervisión
  
  RETURN IFNULL(_plan,0) ;
END $$
DELIMITER ;


DROP PROCEDURE IF EXISTS sp_avance;
DROP FUNCTION IF EXISTS fn_monto_test;

/*
// -------------------------------------------------->
// AQ 2.1 [09-05-2014 21:31]
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

/*
// -------------------------------------------------->
// AQ 2.1 [10-05-2014 16:37]
// RF-005 
// Obtiene los desembolsos realizados al entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_sel_desembolsos_fe;
DROP PROCEDURE IF EXISTS sp_sel_desembolsos_ejecutados_al_entregable;
DROP PROCEDURE IF EXISTS sp_sel_desembolsos_al_entregable;

DELIMITER $$
CREATE PROCEDURE `sp_sel_desembolsos_al_entregable`(IN _proy VARCHAR(10), 
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
  AND fn_numero_mes(d.t02_anio, d.t02_mes) <= fn_numero_mes(_anio, _mes)
  ORDER BY entregable, t60_fch_depo;
  
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [11-05-2014 18:25]
// RF-005 
// Obtiene lo desembolsado al entregable
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_desembolsado_al_entregable;

DELIMITER $$
CREATE FUNCTION `fn_desembolsado_al_entregable`(_proy VARCHAR(10), 
  _anio INT,
  _mes  INT) RETURNS DOUBLE
BEGIN
  DECLARE var_desembolsado DOUBLE DEFAULT 0.0;

  SELECT IFNULL(SUM(t60_mto), 0) 
  INTO var_desembolsado 
  FROM t60_desembolsado 
  WHERE t02_cod_proy = _proy 
  AND fn_numero_mes(t02_anio, t02_mes) <= fn_numero_mes(_anio, _mes);

  RETURN var_desembolsado;
  
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [11-05-2014 21:29]
// RF-005 
// Registra lo desembolsado
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_ins_desembolso;
DROP PROCEDURE IF EXISTS sp_ins_desembolsado;
DROP PROCEDURE IF EXISTS sp_guardar_desembolsado;

DELIMITER $$
CREATE PROCEDURE `sp_guardar_desembolsado`(
  IN _id INT, 
  IN _proy VARCHAR(10), 
  IN _anio INT,
  IN _mes INT,
  IN _tipoPago INT,
  IN _instOrig INT,
  IN _ctaOrig INT,
  IN _modalidad INT,
  IN _fecGiro DATETIME,
  IN _monto DOUBLE,
  IN _cheque VARCHAR(100),
  IN _fecDepo DATETIME,
  IN _obs TEXT,
  IN _user VARCHAR(20))
BEGIN
DECLARE _msg TEXT;

IF (_id IS NULL OR _id = '') THEN
   
  INSERT INTO t60_desembolsado (
    t02_cod_proy, t02_anio, t02_mes,
    t60_tip_pago, t60_inst_ori, t60_cta_ori, 
    t60_mod_pago, t01_id_inst, t01_id_cta, 
    t60_fch_giro, t60_mto, t60_cheque, 
    t60_fch_depo, t60_obs, usr_crea, 
    fch_crea, est_audi)
  VALUES (
    _proy, _anio, _mes,
    _tipoPago, _instOrig, _ctaOrig,
    _modalidad, 
    (SELECT c.t01_id_inst FROM t02_proy_ctas c WHERE c.t02_cod_proy = _proy), 
    (SELECT cc.t01_id_cta FROM t02_proy_ctas cc WHERE cc.t02_cod_proy = _proy),
    _fecGiro, _monto, _cheque, _fecDepo,
    _obs, _user, NOW(), '1');

ELSE
  UPDATE t60_desembolsado SET 
    t60_tip_pago = _tipoPago, t60_inst_ori = _instOrig, t60_cta_ori = _ctaOrig, 
    t60_mod_pago = _modalidad, 
    t01_id_inst = (SELECT c.t01_id_inst FROM t02_proy_ctas c WHERE c.t02_cod_proy = _proy), 
    t01_id_cta = (SELECT cc.t01_id_cta FROM t02_proy_ctas cc WHERE cc.t02_cod_proy = _proy), 
    t60_fch_giro = _fecGiro, t60_mto = _monto, t60_cheque = _cheque, 
    t60_fch_depo = _fecDepo, t60_obs = _obs, usr_actu = _user, 
    fch_actu = NOW()
  WHERE 
    t60_id = _id;

END IF;

SELECT ROW_COUNT() AS numrows, '' AS msg ;
 
END $$
DELIMITER ;
