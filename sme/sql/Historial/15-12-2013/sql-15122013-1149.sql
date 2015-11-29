/*
// -------------------------------------------------->
// AQ 2.0 [15-12-2013 11:49]
// Campo adicional para registrar el usuario 
// que realiza la aprobación. 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_poa_actualiza_presupuesto;

DELIMITER $$

CREATE PROCEDURE `sp_poa_actualiza_presupuesto`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN
DECLARE _anio INT;
DECLARE _total DOUBLE ;
SELECT fn_anio_x_version_proy(_proy, _ver) INTO _anio ;
IF _anio > 0 THEN
  /* Actualizamos las Metas Totales del Año */  
  UPDATE t09_subact s
    SET s.t09_mta = (SELECT SUM(m.t09_mta) 
               FROM t09_sub_act_mtas m
              WHERE m.t02_cod_proy = s.t02_cod_proy
                AND m.t02_version  = s.t02_version  
                AND m.t08_cod_comp = s.t08_cod_comp
                AND m.t09_cod_act  = s.t09_cod_act
                AND m.t09_cod_sub  = s.t09_cod_sub
                AND m.t09_anio = _anio
            )
 WHERE s.t02_cod_proy = _proy 
   AND s.t02_version  = _ver   ;
  /* Actualizamos los Costos Totales */
  UPDATE t10_cost_sub c
    SET c.t10_cost_tot = (c.t10_cost_parc * IFNULL((SELECT s.t09_mta 
               FROM t09_subact s
              WHERE s.t02_cod_proy = c.t02_cod_proy
                AND s.t02_version  = c.t02_version  
                AND s.t08_cod_comp = c.t08_cod_comp
                AND s.t09_cod_act  = c.t09_cod_act
                AND s.t09_cod_sub  = c.t09_cod_sub
            ),0))
 WHERE c.t02_cod_proy = _proy 
   AND c.t02_version  = _ver  ;
  
  /* Actualizamos Las Tablas del Manejo del proyecto */  
  -- Personal del Proyexcto 
  UPDATE t03_mp_per p
     SET p.t03_gasto_tot = (IFNULL((SELECT SUM(m.t03_mta) 
                      FROM t03_mp_per_metas m 
                     WHERE m.t02_cod_proy = p.t02_cod_proy
                       AND m.t02_version  = p.t02_version
                       AND m.t03_id_per   = p.t03_id_per 
                       AND m.t03_anio = _anio),0) * p.t03_remu_prom)
  WHERE p.t02_cod_proy = _proy
    AND p.t02_version  = _ver   ;
  -- Equipamiento del Proyexcto
  UPDATE t03_mp_equi e
     SET e.t03_costo_tot = (IFNULL((SELECT SUM(m.t03_mta) 
                      FROM t03_mp_equi_metas m 
                     WHERE m.t02_cod_proy = e.t02_cod_proy
                       AND m.t02_version  = e.t02_version
                       AND m.t03_id_equi   = e.t03_id_equi 
                       AND m.t03_anio = _anio),0) * e.t03_costo)
  WHERE e.t02_cod_proy = _proy
    AND e.t02_version  = _ver   ;
    
 -- Costos de Funcionamiento del proyecto
 UPDATE t03_mp_gas_fun_part p
   SET p.t03_meta = IFNULL((SELECT SUM(m.t03_mta) 
                  FROM t03_mp_gas_fun_metas m 
                 WHERE m.t02_cod_proy = p.t02_cod_proy
                   AND m.t02_version  = p.t02_version
                   AND m.t03_partida  = p.t03_partida 
                   AND m.t03_anio = _anio),0)
 WHERE p.t02_cod_proy = _proy 
   AND p.t02_version  = _ver  ;
  
  /* ------ Actualizacion de las Fuentes de Financimiento  */   
  
  -- > Actualizar Fuentes - Costos Operativos
  UPDATE t10_cost_fte
     SET t10_mont = fn_poa_aporte_fte_financ_rubro(t02_cod_proy, _anio, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cod_fte)
   WHERE t02_cod_proy = _proy
     AND t02_version = _ver ;
   
  -- > Actualizar Fuentes - Personal del proyecto
  UPDATE t03_mp_per_ftes
     SET t03_monto = fn_poa_aporte_fte_financ_pers(t02_cod_proy, _anio, t03_id_per, t03_id_inst)
   WHERE t02_cod_proy = _proy
     AND t02_version  = _ver;
     
  -- > Actualizar Fuentes - Equipamiento del proyecto
  UPDATE t03_mp_equi_ftes
     SET t03_monto = fn_poa_aporte_fte_financ_equi(t02_cod_proy, _anio, t03_id_equi, t03_id_inst)
   WHERE t02_cod_proy = _proy
     AND t02_version  = _ver;
     
  -- > Actualizar Fuentes - Gastos de Funcionamiento
  UPDATE  t03_mp_gas_fun_ftes
     SET  t03_monto = fn_poa_aporte_fte_gast_func(t02_cod_proy, _anio, t03_partida, t03_id_gasto , t03_id_inst)
   WHERE  t02_cod_proy = _proy
     AND  t02_version  = _ver;
   
  UPDATE  t03_mp_gas_adm
     SET  t03_monto = fn_gastos_adm_anual(t02_cod_proy, t03_id_inst, _anio )
   WHERE  t02_cod_proy = _proy
     AND  t02_version  = _ver ;
  
   UPDATE t03_mp_linea_base 
      SET t03_monto = fn_linea_base_anual(t02_cod_proy, 10 , _anio ) -- fn_linea_base(t02_cod_proy, t02_version)
    WHERE  t02_cod_proy = _proy  AND  t02_version  = _ver; 
   
   UPDATE t03_mp_imprevistos 
      SET t03_monto = fn_imprevistos_anual(t02_cod_proy, 10 , _anio) -- fn_imprevistos(t02_cod_proy, t02_version)
    WHERE  t02_cod_proy = _proy  AND  t02_version  = _ver;  
   
   UPDATE t03_mp_gas_supervision 
      SET t03_monto = fn_supervision_anual(t02_cod_proy, 10 , _anio)
    WHERE  t02_cod_proy = _proy  AND  t02_version  = _ver;
 
END IF ;
END $$
DELIMITER ;

DROP FUNCTION IF EXISTS fn_supervision_anual;

DELIMITER $$

CREATE FUNCTION `fn_supervision_anual`(_proy VARCHAR(10), _fte INT, _anio INT) RETURNS double
    DETERMINISTIC
BEGIN
 DECLARE _retMonto DOUBLE;
 DECLARE _numanios INT DEFAULT fn_duracion_proy(_proy, 1);
 DECLARE _nummeses INT DEFAULT fn_numero_meses_proy(_proy,1);
 DECLARE _aportemes DOUBLE;
  
 SELECT (IFNULL(sup.t03_monto,0) / _nummeses)
   INTO _aportemes
   FROM t03_mp_gas_supervision sup
  WHERE sup.t02_cod_proy = _proy
    AND sup.t03_id_inst = _fte
    AND sup.t02_version = 1;
 
 IF _anio = _numanios THEN
     IF _nummeses <  (_numanios * 12 ) THEN
        SET _retMonto = (_aportemes * ( _nummeses - ((_numanios-1) * 12 ) ) ) ;
     ELSE
        SET _retMonto = (_aportemes * 12 ) ;
     END IF ;
 ELSE
    SET _retMonto = (_aportemes * 12 ) ;
 END IF;
  RETURN _retMonto;
 END $$
DELIMITER ;