DROP PROCEDURE IF EXISTS `sp_ejec_anio`;

DELIMITER $$
CREATE PROCEDURE `sp_ejec_anio`(
									IN pAnio INT,
									IN pConcurso VARCHAR(10), 
									IN pSector VARCHAR(10), 
									IN pRegion INT)
BEGIN
	DECLARE aIniDate DATE;
	DECLARE aFinDate DATE;
	
	SELECT STR_TO_DATE(CONCAT('', pAnio, '/01/01'), '%Y/%m/%d') INTO aIniDate;
	SELECT STR_TO_DATE(CONCAT('', pAnio, '/12/31'), '%Y/%m/%d') INTO aFinDate;
	
		
	SELECT	pAnio,
			SUM(fn_costo_total_proyecto_periodo(proy.t02_cod_proy, proy.t02_version, proy.mes_ini, proy.mes_fin)) AS planeado,
			SUM(total_eject_periodo_pre(proy.t02_cod_proy, proy.mes_ini, proy.mes_fin)) AS ejecutado			 
	FROM (
			SELECT	proy2.t02_cod_proy, 
					proy2.t02_version, 
					proy2.t02_sect_prod,
					proy2.fch_ini,
					proy2.fch_ter,
					CASE 
						WHEN aIniDate > proy2.fch_ini THEN 
							CEILING(DATEDIFF(aIniDate, proy2.fch_ini) / 30) + 1
						ELSE 
							1 
					END AS mes_ini,
					CASE 
						WHEN  aFinDate < proy2.fch_ter THEN 
							FLOOR(DATEDIFF(aFinDate, proy2.fch_ini) / 30) 
						ELSE 
							FLOOR(DATEDIFF(proy2.fch_ter, proy2.fch_ini) / 30) 
					END AS mes_fin
			FROM (
					SELECT	t02_cod_proy, t02_version, t02_sect_prod,
							fn_fecha_inicio_proy(t02_cod_proy, t02_version) AS fch_ini,
							fn_fecha_termino_proy(t02_cod_proy, t02_version) AS fch_ter
					FROM	t02_dg_proy
					WHERE 	t02_version = fn_pri_version_proy(t02_cod_proy)
				) proy2
			WHERE	YEAR(proy2.fch_ini) = pAnio 
				OR ( YEAR(proy2.fch_ini) < pAnio AND YEAR(proy2.fch_ter) = pAnio )
				OR ( YEAR(proy2.fch_ini) < pAnio AND YEAR(proy2.fch_ter) > pAnio )
		) proy
		LEFT JOIN	(SELECT t02_cod_proy, IFNULL(t03_dpto, 0) AS t03_dpto
					FROM t03_dist_proy 
					WHERE t02_version = fn_pri_version_proy(t02_cod_proy)
					GROUP BY t02_cod_proy)	dist	ON (proy.t02_cod_proy = dist.t02_cod_proy)
		WHERE	SUBSTR(proy.t02_cod_proy,3,2) = (CASE WHEN pConcurso = '*' THEN SUBSTR(proy.t02_cod_proy,3,2) ELSE pConcurso END)
			AND proy.t02_sect_prod = (CASE WHEN pSector = "*" THEN proy.t02_sect_prod ELSE pSector END)
			AND	IFNULL(dist.t03_dpto, 0) = (CASE WHEN pRegion = '*' THEN IFNULL(dist.t03_dpto, 0) ELSE pRegion END);
END$$

DELIMITER ;