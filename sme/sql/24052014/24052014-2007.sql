/*
// -------------------------------------------------->
// AQ 2.1 [23-05-2014 23:03]
// RF-015 Exportable Informe de Entregable 
// Obtiene el Resumen de Avance Alcanzado
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_del_entregable_fte_adelanto;

DELIMITER $$

CREATE FUNCTION fn_desembolso_planeado_del_entregable_fte_adelanto(_proy VARCHAR(10), 
    _ver INT, 
    _anio INT,
    _mes INT,
    _fte INT) RETURNS DOUBLE
    DETERMINISTIC
BEGIN
    DECLARE _adelanto DOUBLE;
    DECLARE _mes_ini INT DEFAULT 0;
    DECLARE _mes_fin INT DEFAULT 0;

    SET _mes_ini = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
    SET _mes_fin = fn_get_nro_mes_adelanto(_proy, _ver, _anio, _mes);
    SET _adelanto = fn_desembolso_planeado_periodo_fte(_proy, _ver, _mes_ini, _mes_fin, _fte);
        
    RETURN IFNULL(_adelanto, 0);
    
    END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [24-05-2014 00:47]
// Obtiene el Nombre del Mes y Año
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS fn_nom_periodo_anio_mes;
DELIMITER $$
CREATE FUNCTION `fn_nom_periodo_anio_mes`(_proy VARCHAR(10), _ver INT, _anio INT, _mes INT) RETURNS VARCHAR(50) CHARSET utf8
    DETERMINISTIC
BEGIN

DECLARE _fecini DATETIME;
DECLARE _return VARCHAR(50);

SET _anio = _anio - 1;

SET _fecini = fn_fecha_inicio_proy(_proy, _ver);
    
SET _fecini = DATE_ADD(_fecini, INTERVAL _anio YEAR);
SET _fecini = DATE_ADD(_fecini, INTERVAL _mes MONTH);

SET _return = CONCAT(fn_nom_mes(MONTH(_fecini)), ' ', YEAR(_fecini));
 
RETURN _return ;
END $$
DELIMITER ;


/*
// -------------------------------------------------->
// AQ 2.1 [23-05-2014 23:03]
// RF-015 Exportable Informe de Entregable 
// Obtiene el Resumen de Avance Alcanzado
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_del_entregable_fte_saldo;

DELIMITER $$

CREATE FUNCTION fn_desembolso_planeado_del_entregable_fte_saldo(_proy VARCHAR(10), 
    _ver  INT, 
    _anio INT,
    _mes INT,
    _fte INT) RETURNS DOUBLE
    DETERMINISTIC
BEGIN
    DECLARE _adelanto DOUBLE;
    DECLARE _saldo DOUBLE;
    DECLARE _total DOUBLE;

    SET _adelanto = fn_desembolso_planeado_del_entregable_fte_adelanto(_proy, _ver, _anio, _mes, _fte);
    SET _total = fn_desembolso_planeado_del_entregable_fte(_proy, _ver, _anio, _mes, _fte);
    SET _saldo = _total - _adelanto;
        
    RETURN IFNULL(_saldo, 0);
    
    END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [25-05-2014 00:20]
// Obtiene el Número del Mes de Adelanto
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_get_nro_mes_adelanto;

DELIMITER $$

CREATE FUNCTION fn_get_nro_mes_adelanto(_proy VARCHAR(10), 
    _ver INT, 
    _anio INT,
    _mes INT) RETURNS INT
    DETERMINISTIC
BEGIN
    DECLARE _num_entregable INT;
    DECLARE _mes_ini INT DEFAULT 0;
    DECLARE _mes_fin INT DEFAULT 0;

    SET _mes_ini = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
    SET _mes_fin = fn_numero_mes(_anio, _mes);

    SET _num_entregable = fn_numero_entregable(_proy, _ver, _anio, _mes);

    IF _num_entregable = 1 THEN
      SET _mes_fin = _mes_ini + 1; -- Hasta el 3er mes 
    ELSE
      SET _mes_fin = _mes_ini; -- Hasta el 2do mes 
    END IF;
        
    RETURN IFNULL(_mes_fin, 0);
    
    END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [21-05-2014 01:35]
// RF-011 
// Obtiene el desembolso planeado pro periodo y fuente
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS fn_desembolso_planeado_periodo_fte;

DELIMITER $$
CREATE FUNCTION `fn_desembolso_planeado_periodo_fte`(_proy VARCHAR(10), 
  _ver INT,
  _mes_ini INT,
  _mes_fin INT,
  _fte INT) RETURNS DOUBLE
BEGIN

DECLARE _planeado DOUBLE DEFAULT 0;

SELECT
  -- Componentes
  SUM(fn_costo_componentes_planificado_entregable_fte(cost.t02_cod_proy, cost.t02_version, 
                    cost.t08_cod_comp, cost.t09_cod_act, 
                    cost.t09_cod_sub , cost.t10_cod_cost,
                    _fte , _mes_ini, _mes_fin))

  +
  -- Personal
  IFNULL(
   ( SELECT SUM(fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    1, 0, p.t03_id_per, p.t03_id_inst, 
                                                    _mes_ini, _mes_fin))
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
                                                    _mes_ini, _mes_fin))
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
                                                    _mes_ini, _mes_fin))
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
                                                    _mes_ini, _mes_fin)
   ),0)

  +
  -- Imprevistos 
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    6, 0, NULL, _fte, 
                                                    _mes_ini, _mes_fin)
   ),0)

  +
  -- Supervisión
  IFNULL(
   ( SELECT fn_monto_planificado_mp_fte_periodo(cost.t02_cod_proy, cost.t02_version, 
                                                    7, 0, NULL, _fte, 
                                                    _mes_ini, _mes_fin)
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
// AQ 2.1 [24-05-2014 20:45]
// RF-011 
// Obtiene el desembolso planeado del entregable por fuente
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

  DECLARE _mes_ini INT DEFAULT 0;
  DECLARE _mes_fin INT DEFAULT 0;

  SET _mes_ini = fn_mes_entregable_ant(_proy, _ver, _anio, _mes) + 1;
  SET _mes_fin = fn_numero_mes(_anio, _mes);
  RETURN fn_desembolso_planeado_periodo_fte(_proy, _ver, _mes_ini, _mes_fin, _fte);
END $$
DELIMITER ;