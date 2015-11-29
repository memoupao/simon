DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_sel_sec_inst_proy3`$$

CREATE PROCEDURE `sp_sel_sec_inst_proy3`(IN _concurso  VARCHAR(10), IN _estado  VARCHAR(10))
BEGIN
	SELECT	w.region,
			w.actividad, 
			w.cantidad,
			fn_costo_presupuesto_fe(w.t02_cod_proy, w.t02_version) AS FE, 
			fn_costo_presupuesto_otros(w.t02_cod_proy, w.t02_version) AS OTROS
	FROM (
		SELECT	p.t02_cod_proy,
				p.t02_version,
				dep.nom_ubig AS region, 
				s.descrip AS actividad,
				COUNT(DISTINCT p.t02_cod_proy) AS cantidad
		FROM		t02_dg_proy  	p 
		LEFT JOIN 	t03_dist_proy 	dp	ON (dp.t02_cod_proy = p.t02_cod_proy AND dp.t02_version = p.t02_version)
		LEFT JOIN	adm_ubigeo 		dep ON (dp.t03_dpto = dep.cod_dpto AND dep.cod_prov = '00' AND dep.cod_dist = '00')
		LEFT JOIN	adm_tablas_aux  s 	ON (s.codi = p.t02_sect_prod) 
		WHERE 	p.t02_version = 1
			AND SUBSTR(p.t02_cod_proy,3,2) = (CASE WHEN _concurso='*' THEN SUBSTR(p.t02_cod_proy,3,2) ELSE _concurso END ) 
			AND p.t02_estado = (CASE WHEN _estado='*' THEN p.t02_estado ELSE _estado END ) 
		GROUP BY p.t02_cod_proy, p.t02_version, dep.nom_ubig, s.descrip 
		) w;
END$$

DELIMITER ;