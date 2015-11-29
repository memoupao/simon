/*
// -------------------------------------------------->
// AQ 2.1 [23-05-2014 23:03]
// RF-015 Exportable Informe de Entregable 
// Obtiene el Resumen de Avance Alcanzado
// --------------------------------------------------<
*/

DROP PROCEDURE IF EXISTS sp_inf_entregable_avance_alcanzado;

DELIMITER $$
CREATE PROCEDURE `sp_inf_entregable_avance_alcanzado`(IN _proy VARCHAR(10), 
  IN _ver INT,
  IN _anio INT,
  IN _mes INT) 
BEGIN

SELECT
  t08_cod_comp AS num_comp,
  t08_comp_desc AS nom_comp,
  fn_nro_prods_en_comp(t02_cod_proy, t02_version, t08_cod_comp) AS nro_prods,
  fn_nro_prods_en_entregable_en_comp(t02_cod_proy, t02_version, t08_cod_comp, _anio, _mes) AS nro_prods_entregable,
  fn_nro_prods_completados_en_entregable_en_comp(t02_cod_proy, t02_version, t08_cod_comp, _anio, _mes) AS nro_prods_entregable_completados,
  fn_desembolso_planeado_del_entregable_comp(t02_cod_proy, t02_version, t08_cod_comp, _anio, _mes) AS planeado_en_entregable,
  fn_desembolso_ejecutado_del_entregable_comp(t02_cod_proy, t02_version, t08_cod_comp, _anio, _mes) AS ejecutado_en_entregable
FROM t08_comp
WHERE t02_cod_proy= _proy
  AND t02_version= _ver
  ORDER BY t08_cod_comp;

END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [24-05-2014 00:45]
// Obtiene el número de productos en un componente
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_nro_prods_en_comp;

DELIMITER $$

CREATE FUNCTION fn_nro_prods_en_comp(_proy VARCHAR(10), 
    _ver  INT, 
    _comp INT) RETURNS INT
    DETERMINISTIC
BEGIN
    DECLARE _total INT;
    
    SELECT COUNT(t09_cod_act)
      INTO _total
      FROM  t09_act
    WHERE t02_cod_proy = _proy
      AND t02_version  = _ver 
      AND t08_cod_comp = _comp;
        
    RETURN IFNULL(_total, 0);
    
    END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [24-05-2014 00:54]
// Obtiene el número de productos a controlar en 
// entregables en un componente
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_nro_prods_en_entregable_en_comp;

DELIMITER $$

CREATE FUNCTION fn_nro_prods_en_entregable_en_comp(_proy VARCHAR(10), 
    _ver  INT, 
    _comp INT,
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
      AND t08_cod_comp = _comp
      AND t02_anio = _anio
      AND t02_mes = _mes;
        
    RETURN IFNULL(_total, 0);
    
    END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [24-05-2014 01:14]
// Obtiene el número de productos completados al 100%
// en Entregables en un Componente
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_nro_prods_completados_en_entregable_en_comp;

DELIMITER $$

CREATE FUNCTION fn_nro_prods_completados_en_entregable_en_comp(_proy VARCHAR(10), 
    _ver  INT, 
    _comp INT,
    _anio INT,
    _mes INT) RETURNS INT
    DETERMINISTIC
BEGIN
    DECLARE _total INT;
    
    SELECT COUNT(DISTINCT i.t09_cod_act)
    INTO _total 
    FROM t09_act_ind i
    LEFT JOIN t09_entregable_ind_inf inf ON(i.t02_cod_proy=inf.t02_cod_proy 
                                            AND i.t08_cod_comp=inf.t08_cod_comp 
                                            AND i.t09_cod_act=inf.t09_cod_prod 
                                            AND i.t09_cod_act_ind=inf.t09_cod_prod_ind 
                                            AND inf.t09_ind_anio=_anio 
                                            AND inf.t09_ind_entregable=_mes)
    WHERE i.t02_cod_proy = _proy
    AND i.t02_version = _ver
    AND i.t08_cod_comp = _comp
    AND i.t09_mta = IFNULL(inf.t09_ind_avanc, 0);
        
    RETURN IFNULL(_total, 0);
    
    END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [24-05-2014 02:37]
// Obtiene el desembolso planeado del entregable 
// por componente
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_del_entregable_comp;

DELIMITER $$
CREATE FUNCTION `fn_desembolso_planeado_del_entregable_comp`(_proy VARCHAR(10), 
  _ver INT,
  _comp INT,
  _anio INT,
  _mes  INT) RETURNS DOUBLE
BEGIN

DECLARE _entregable_act INT DEFAULT 0;
DECLARE _entregable_ant INT DEFAULT 0;
DECLARE _planeado DOUBLE DEFAULT 0;

SET _entregable_ant = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
SET _entregable_act = fn_numero_mes(_anio, _mes);

SELECT
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

  AS planeado
   
INTO _planeado
FROM t10_cost_sub cost
WHERE cost.t02_cod_proy = _proy
  AND cost.t02_version = _anio + 1
  AND cost.t08_cod_comp = _comp;

  RETURN _planeado;
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [24-05-2014 02:45]
// Obtiene el desembolso ejecutado del entregable 
// por componente
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_desembolso_ejecutado_del_entregable_comp;

DELIMITER $$
CREATE FUNCTION `fn_desembolso_ejecutado_del_entregable_comp`(_proy VARCHAR(10), 
  _ver INT,
  _comp INT,
  _anio INT,
  _mes  INT) RETURNS DOUBLE
BEGIN

DECLARE _entregable_act INT DEFAULT 0;
DECLARE _entregable_ant INT DEFAULT 0;
DECLARE _ejecutado DOUBLE DEFAULT 0;

SET _entregable_ant = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
SET _entregable_act = fn_numero_mes(_anio, _mes);

SELECT IFNULL(SUM(t41_gasto), 0)
INTO _ejecutado
FROM t41_inf_financ_gasto
WHERE t02_cod_proy = _proy
  AND t02_version = _anio + 1
  AND t08_cod_comp = _comp
  AND fn_numero_mes(t40_anio, t40_mes) BETWEEN _entregable_ant AND _entregable_act;

  RETURN _ejecutado;
END $$
DELIMITER ;