/*
// -------------------------------------------------->
// AQ 2.0 [29-05-2014 20:44]
// Lista de Actividades del Informe de Supervisión
// Se retira parámetro _ver y se recalcula según el año.
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_act_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_act_se`(IN _proy VARCHAR(10), 
                IN _comp INT, 
                IN _prod INT, 
                IN _anio INT,
                IN _entregable INT)
BEGIN
    DECLARE _ver INT;

    SET _ver = _anio + 1;


	SELECT sub.t08_cod_comp,
	    com.t08_comp_desc AS componente,
	    sub.t09_cod_act,
	    act.t09_act AS actividad,
	    sub.t09_cod_sub,
	    sub.t09_sub AS subactividad,
	    sub.t09_um,
	    sub.t09_mta_repro AS plan_mtatotal,
	    IFNULL(
	    (SELECT SUM(a.t09_sub_avanc) 
	     FROM t09_act_sub_mtas_inf a 
	     WHERE a.t02_cod_proy = sub.t02_cod_proy 
	       AND a.t08_cod_comp = sub.t08_cod_comp 
	       AND a.t09_cod_act = sub.t09_cod_act
	       AND a.t09_cod_sub = sub.t09_cod_sub
	       AND DATE_ADD(DATE_ADD(NOW(), INTERVAL a.t09_sub_anio YEAR), INTERVAL a.t09_sub_mes MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
	    ), 0) AS ejecutado,
	    IFNULL(
	    (SELECT SUM(b.t09_mta) 
         FROM t09_sub_act_mtas b 
         WHERE b.t02_cod_proy = sub.t02_cod_proy 
           AND b.t08_cod_comp = sub.t08_cod_comp 
           AND b.t09_cod_act = sub.t09_cod_act
           AND b.t09_cod_sub = sub.t09_cod_sub
	       AND DATE_ADD(DATE_ADD(NOW(), INTERVAL b.t09_anio YEAR), INTERVAL b.t09_mes MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
	       AND b.t02_version = _ver
	    ), 0) AS programado,
	    inf.t09_obs AS obs
	FROM t09_subact sub
	INNER JOIN t08_comp com ON(sub.t02_cod_proy=com.t02_cod_proy AND sub.t02_version=com.t02_version AND sub.t08_cod_comp=com.t08_cod_comp)
	INNER JOIN t09_act act ON(sub.t02_cod_proy=act.t02_cod_proy AND sub.t02_version=act.t02_version AND sub.t08_cod_comp=act.t08_cod_comp AND sub.t09_cod_act=act.t09_cod_act)
	LEFT JOIN t09_act_inf_se inf ON(sub.t02_cod_proy=inf.t02_cod_proy AND sub.t08_cod_comp=inf.t08_cod_comp  AND sub.t09_cod_act=inf.t09_cod_prod AND sub.t09_cod_sub=inf.t09_cod_act AND inf.t02_anio=_anio AND inf.t02_entregable=_entregable)
	WHERE sub.t02_cod_proy = _proy
	  AND sub.t02_version = _ver
	  AND sub.t08_cod_comp = _comp
	  AND sub.t09_cod_act = _prod
	GROUP BY sub.t08_cod_comp, com.t08_comp_desc, sub.t09_cod_act, act.t09_act,
	     sub.t09_cod_sub, sub.t09_um, sub.t09_sub, sub.t09_mta;
     
END $$

DELIMITER ;