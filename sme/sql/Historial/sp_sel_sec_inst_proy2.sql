DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_sel_sec_inst_proy2`$$

CREATE PROCEDURE `sp_sel_sec_inst_proy2`(IN _concurso  VARCHAR(10), IN _estado  VARCHAR(10))
BEGIN
		DECLARE _contador INT ;
		DECLARE _sql_filas TEXT;
		DECLARE _sql TEXT;
		DECLARE _tipoinst VARCHAR(20);
		DROP TEMPORARY TABLE IF EXISTS tmpTipoInst;
		CREATE TEMPORARY TABLE tmpTipoInst
		( id INT AUTO_INCREMENT,
		  codigo INT,
		  nombre VARCHAR(250),
		  PRIMARY KEY (id)
		) ;
		INSERT INTO tmpTipoInst(codigo, nombre)
			SELECT  DISTINCT
				IFNULL(a.codi,0) AS codi,
				IFNULL(a.abrev,'') AS abrev
			FROM			t01_dir_inst	AS i
				INNER JOIN	t02_dg_proy 	AS p ON (i.t01_id_inst = p.t01_id_inst)
				LEFT JOIN	adm_tablas_aux	AS a ON (i.t01_tipo_inst = a.codi )
				LEFT JOIN	adm_tablas_aux	AS e ON (p.t02_estado = e.codi)
			WHERE	p.t02_version = fn_ult_version_proy(p.t02_cod_proy) 		AND
					SUBSTR(p.t02_cod_proy,3,2) = (CASE WHEN _concurso='*' THEN SUBSTR(p.t02_cod_proy,3,2) ELSE _concurso END )		AND
					p.t02_estado = (CASE WHEN _estado='*' THEN p.t02_estado ELSE _estado END )   
			GROUP BY a.descrip
			ORDER BY COUNT(p.t02_cod_proy) DESC; 
		SELECT 1 , ' ' INTO _contador, _sql_filas ;
		WHILE (SELECT COUNT(1) FROM  tmpTipoInst) >= _contador DO
			SELECT nombre 
			INTO _tipoinst 
			FROM tmpTipoInst 
			WHERE id=_contador ;
			
			SET _sql_filas = CONCAT(_sql_filas, ' , COUNT(IF(a.abrev = "', _tipoinst, '",p.t01_id_inst,NULL )) AS \'', _tipoinst, '\'');
			SELECT _contador +1 INTO _contador ;
		END WHILE ;
		SELECT CONCAT('
				SELECT
				a.abrev, s.descrip ' ,
				_sql_filas, 
				', FORMAT(SUM(p.t02_pres_fe), 2) AS "APORTE FE", 
				FORMAT(SUM(p.t02_pres_otro), 2) AS "OTROS APORTES", 
				FORMAT(IFNULL(SUM(p.t02_pres_fe)+SUM(p.t02_pres_otro),0), 2) AS "PRESUPUESTO TOTAL" ',
				' FROM      t02_dg_proy   AS  p
				  LEFT JOIN t01_dir_inst   AS  i ON (i.t01_id_inst = p.t01_id_inst)
				  LEFT JOIN adm_tablas_aux AS  a ON (a.codi = i.t01_tipo_inst)
				  LEFT JOIN adm_tablas_aux AS  s ON (s.codi = p.t02_sect_prod) 
				  LEFT JOIN  adm_tablas_aux AS e ON (p.t02_estado=e.codi)
				WHERE  p.t02_version = fn_pri_version_proy(p.t02_cod_proy) 
				  AND p.t02_estado = ', IF (_estado = '*', 'p.t02_estado', _estado), ' 
				  GROUP BY s.descrip 
				  ORDER BY -s.descrip DESC, s.descrip') INTO _sql;
		
		SELECT _sql INTO @txtsql;
		PREPARE stmt FROM @txtsql;
		EXECUTE stmt;
		DEALLOCATE PREPARE stmt; 
		DROP TEMPORARY TABLE tmpTipoInst;
END$$

DELIMITER ;