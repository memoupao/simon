DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_sel_tip_inst_proy`$$

CREATE PROCEDURE `sp_sel_tip_inst_proy`(IN _concurso  VARCHAR(10), IN _estado  VARCHAR(10))
BEGIN
SELECT
      IFNULL(adm_tablas_aux.abrev,'--') AS tipoinst
    , COUNT(t02_dg_proy.t02_cod_proy) AS cantidad,
		SUM(fn_costo_presupuesto_fe(t02_dg_proy.t02_cod_proy, t02_dg_proy.t02_version)) AS FE,
		SUM(fn_costo_presupuesto_otros(t02_dg_proy.t02_cod_proy, t02_dg_proy.t02_version)) AS otros,
		fn_costos_total_proyecto(t02_dg_proy.t02_cod_proy, t02_dg_proy.t02_version) AS total, 
		t01_dir_inst.t01_tipo_inst
FROM
    t01_dir_inst
    INNER JOIN t02_dg_proy ON (t01_dir_inst.t01_id_inst = t02_dg_proy.t01_id_inst)
    LEFT JOIN adm_tablas_aux ON (adm_tablas_aux.codi = t01_dir_inst.t01_tipo_inst)
    LEFT JOIN adm_tablas_aux e ON (t02_dg_proy.t02_estado = e.codi)
WHERE  t02_dg_proy.t02_version = fn_ult_version_proy(t02_dg_proy.t02_cod_proy)	 
  AND 	SUBSTR(t02_cod_proy,3,2) = (CASE WHEN _concurso='*' THEN SUBSTR(t02_cod_proy,3,2) ELSE _concurso END )
  AND 	t02_estado = (CASE WHEN _estado='*' THEN t02_estado ELSE _estado END )    
GROUP BY adm_tablas_aux.descrip
ORDER BY 2 DESC ;
		
END$$

DELIMITER ;