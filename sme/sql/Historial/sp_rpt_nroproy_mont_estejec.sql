DROP PROCEDURE IF EXISTS `sp_rpt_nroproy_mont_estejec`;

DELIMITER $$
CREATE PROCEDURE `sp_rpt_nroproy_mont_estejec`()
BEGIN
	SELECT 	conc.num_conc AS concurso,
			dep.nom_ubig AS region, 
			est.descrip AS estado,
			SUM(fn_costos_total_proyecto(proy.t02_cod_proy, proy.t02_version)) AS presupuesto , 
			COUNT(proy.t02_estado) AS nro_proy
	FROM 		adm_concursos 	conc
	JOIN		t02_dg_proy		proy ON (conc.num_conc = SUBSTRING(proy.t02_cod_proy, 3, 2))
	LEFT JOIN 	(SELECT t02_cod_proy, t02_version, t03_dpto 
				 FROM t03_dist_proy 
				 GROUP BY t02_cod_proy, t02_version) dp ON (dp.t02_cod_proy = proy.t02_cod_proy AND dp.t02_version = proy.t02_version)
	LEFT JOIN	adm_ubigeo		dep  ON (dp.t03_dpto = dep.cod_dpto AND dep.cod_prov = '00' AND dep.cod_dist = '00')
	LEFT JOIN	adm_tablas_aux	est	 ON (proy.t02_estado = est.codi)
	WHERE	proy.t02_version = fn_pri_version_proy(proy.t02_cod_proy)
	GROUP BY conc.num_conc, dep.nom_ubig, proy.t02_estado
	ORDER BY concurso, -region DESC, region, estado;
END$$

DELIMITER ;