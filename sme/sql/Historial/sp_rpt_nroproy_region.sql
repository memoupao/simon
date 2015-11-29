DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_rpt_nroproy_region`$$

CREATE PROCEDURE `sp_rpt_nroproy_region`(IN _concurso  VARCHAR(10), IN _estado  VARCHAR(10))
BEGIN
	SELECT 	dep.nom_ubig AS region, 
			COUNT(DISTINCT p.t02_cod_proy) AS cantidad,
			SUM(fn_costo_presupuesto_fe(p.t02_cod_proy, p.t02_version)) AS FE, 
			SUM(fn_costo_presupuesto_otros(p.t02_cod_proy, p.t02_version)) AS OTROS
	FROM		t02_dg_proy 	p
	LEFT JOIN	(SELECT t02_cod_proy, t03_dpto 
				FROM t03_dist_proy 
				WHERE t02_version = fn_pri_version_proy(t02_cod_proy)
				GROUP BY t02_cod_proy)	dp	ON (p.t02_cod_proy = dp.t02_cod_proy)
	LEFT JOIN	adm_ubigeo 		dep ON (dp.t03_dpto = dep.cod_dpto AND dep.cod_prov = '00' AND dep.cod_dist = '00')
	WHERE		p.t02_version = fn_pri_version_proy(p.t02_cod_proy) 
		AND 	SUBSTR(p.t02_cod_proy,3,2) = (CASE WHEN _concurso='*' THEN SUBSTR(p.t02_cod_proy,3,2) ELSE _concurso END )
		AND 	p.t02_estado = (CASE WHEN _estado='*' THEN p.t02_estado ELSE _estado END )   
	GROUP BY dep.nom_ubig
	ORDER BY -region DESC, region;
    END$$

DELIMITER ;