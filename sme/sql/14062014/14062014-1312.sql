-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-022 Maquetación, Estructura, Exportación y Registro
-- --------------------------------------------------------------------------------



ALTER TABLE `t46_cron_visita_proy` 
ADD COLUMN `id_supervisor` VARCHAR(10) NULL COMMENT 't02_dg_proy.t02_moni_tema o t02_dg_proy.t02_moni_ext' AFTER `fch_actu`;





DROP procedure IF EXISTS `sp_sel_supervisores_visitas`;

DELIMITER $$
CREATE PROCEDURE `sp_sel_supervisores_visitas`( IN _proy VARCHAR(10),
					   IN _ver INT  )
BEGIN
DECLARE _inst INT ;
DECLARE _cargo INT ;
SELECT 60 INTO _cargo ; 

SELECT p.t01_id_inst
INTO _inst 
FROM  t02_dg_proy p 
WHERE p.t02_cod_proy = _proy
  AND p.t02_version = _ver ;


SELECT supervisores.* FROM (

SELECT 	CONCAT(c.t01_id_inst, '.', c.t01_id_cto) AS codigo,
	CONCAT('(SE) ', TRIM(c.t01_ape_pat), ' ', TRIM(c.t01_ape_mat), ', ', TRIM(c.t01_nom_cto)) AS nombres	 
FROM  t01_inst_cto c
WHERE c.t01_id_inst NOT IN(_inst)  
  AND c.t01_cgo_cto = _cargo
  
UNION 
SELECT 	i.t01_id_inst  AS codigo,
	CONCAT('(SE) ', i.t01_sig_inst) AS nombres 	
FROM  t01_inst_cto c
INNER JOIN t01_dir_inst i ON (c.t01_id_inst=i.t01_id_inst)
WHERE c.t01_id_inst NOT IN( _inst)
  AND c.t01_cgo_cto = _cargo 
ORDER BY  nombres

) AS supervisores 

LEFT JOIN t02_dg_proy p 
ON p.t02_moni_ext = supervisores.codigo 
WHERE p.t02_cod_proy = _proy AND p.t02_version = _ver 

UNION 

SELECT p.t02_moni_tema AS codigo, 
CONCAT('(GP) ',TRIM(fe.t90_ape_pat),' ', TRIM(fe.t90_ape_mat), ', ', TRIM(fe.t90_nom_equi)) AS nombres
FROM t02_dg_proy p 
LEFT JOIN t90_equi_fe fe ON fe.t90_id_equi = p.t02_moni_tema 
WHERE p.t02_cod_proy = _proy AND p.t02_version = _ver ;


END$$

DELIMITER ;






