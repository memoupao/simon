/*
// -------------------------------------------------->
// AQ 2.0 [19-02-2014 21:10]
// Incidencia 139
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_actividad_costeo`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_actividad_costeo`(IN _proy varchar(10), 
IN _ver int, 
IN _comp int)
BEGIN
DECLARE _codfe INT ;
DECLARE _ideje INT ;
SELECT 10          INTO _codfe ;
SELECT t01_id_inst INTO _ideje FROM t02_dg_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT
          CONCAT( act.t08_cod_comp,'.', act.t09_cod_act) AS codigo,
          act.t09_cod_act,
          act.t09_act AS actividad,
          NULL AS um,
          NULL AS meta,
          SUM(cost.t10_cu * cost.t10_cant) AS ctoparcial,
          SUM((cost.t10_cu * cost.t10_cant) * sub.t09_mta)  AS ctototal,
          /*IFNULL(
          (SELECT SUM(fte.t10_mont) 
             FROM t10_cost_fte fte 
            WHERE fte.t02_cod_proy = cost.t02_cod_proy 
              AND fte.t02_version  = cost.t02_version
              AND fte.t08_cod_comp = cost.t08_cod_comp 
              AND fte.t09_cod_act  = cost.t09_cod_act 
            ),0) AS fuentesfinan,*/
            
          /*IFNULL(
          (SELECT SUM(fte.t10_mont) 
             FROM t10_cost_fte fte 
            WHERE fte.t02_cod_proy = cost.t02_cod_proy 
              AND fte.t02_version  = cost.t02_version
              AND fte.t08_cod_comp = cost.t08_cod_comp 
              AND fte.t09_cod_act  = cost.t09_cod_act 
              AND fte.t01_id_inst  = _codfe
            ),0) AS fte_fe,*/
            
            (SELECT fn_get_fto_act_fte(act.t02_cod_proy, act.t02_version, act.t08_cod_comp, act.t09_cod_act, _codfe)) AS fte_fe,
            
          /*IFNULL(
          (SELECT SUM(fte.t10_mont) 
             FROM t10_cost_fte fte 
            WHERE fte.t02_cod_proy = cost.t02_cod_proy 
              AND fte.t02_version  = cost.t02_version
              AND fte.t08_cod_comp = cost.t08_cod_comp 
              AND fte.t09_cod_act  = cost.t09_cod_act 
              AND fte.t01_id_inst  = _ideje
            ),0) AS ejecutor,*/
            
            (SELECT fn_get_fto_act_fte(act.t02_cod_proy, act.t02_version, act.t08_cod_comp, act.t09_cod_act, _ideje)) AS ejecutor,
            
            /*IFNULL(
          (SELECT SUM(fte.t10_mont) 
             FROM t10_cost_fte fte 
            WHERE fte.t02_cod_proy = cost.t02_cod_proy 
              AND fte.t02_version  = cost.t02_version
              AND fte.t08_cod_comp = cost.t08_cod_comp 
              AND fte.t09_cod_act  = cost.t09_cod_act 
              AND fte.t01_id_inst  NOT IN(_codfe,_ideje)
            ),0) AS otros*/
            
            (SELECT fn_get_fto_act_otros(act.t02_cod_proy, act.t02_version, act.t08_cod_comp, act.t09_cod_act, _codfe, _ideje)) AS otros
    FROM       t09_subact     sub  
    INNER JOIN t09_act        act  on(sub.t02_cod_proy = act.t02_cod_proy and sub.t02_version=act.t02_version AND sub.t08_cod_comp = act.t08_cod_comp AND sub.t09_cod_act=act.t09_cod_act) 
    LEFT  JOIN t10_cost_sub   cost ON(sub.t02_cod_proy = cost.t02_cod_proy AND sub.t02_version=cost.t02_version AND sub.t08_cod_comp = cost.t08_cod_comp AND sub.t09_cod_act=cost.t09_cod_act AND sub.t09_cod_sub= cost.t09_cod_sub )
    LEFT  JOIN adm_tablas_aux cat  ON(cost.t10_cate_cost = cat.codi)
    WHERE sub.t02_cod_proy=_proy
      AND sub.t02_version=_ver
      AND sub.t08_cod_comp=_comp
    GROUP BY 1, 2, 3, 4, 5 ;
    
END $$

DELIMITER ;

DROP FUNCTION IF EXISTS `fn_get_fto_act_fte`;

DELIMITER $$

CREATE FUNCTION `fn_get_fto_act_fte`(_proy VARCHAR(10), _ver INT, _comp INT, _act INT, _fte INT) RETURNS DOUBLE
BEGIN
	DECLARE res DOUBLE DEFAULT 0.0;
	
    SELECT IFNULL(SUM(t10_mont),0) 
    INTO res 
    FROM t10_cost_fte 
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver
    AND t08_cod_comp = _comp 
    AND t09_cod_act  = _act
    AND t01_id_inst = _fte;
    
    RETURN res;
END $$

DELIMITER ;

DROP FUNCTION IF EXISTS `fn_get_fto_act_otros`;

DELIMITER $$

CREATE FUNCTION `fn_get_fto_act_otros`(_proy VARCHAR(10), _ver INT, _comp INT, _act INT, _fe INT, _eje INT) RETURNS DOUBLE
BEGIN
    DECLARE res DOUBLE DEFAULT 0.0;
    
    SELECT IFNULL(SUM(t10_mont),0) 
    INTO res
    FROM t10_cost_fte
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver
    AND t08_cod_comp = _comp 
    AND t09_cod_act  = _act
    AND t01_id_inst NOT IN (_fe, _eje);
    
    RETURN res;
END $$

DELIMITER ;