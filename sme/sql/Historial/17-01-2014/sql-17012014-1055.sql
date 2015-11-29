/*
-- DA: Nuevo Mantenimiento de Desembolsos de Entregables.
-- Date: 2014-01-17 10:55
*/

INSERT INTO `adm_menus_perfil` (`mnu_cod`,`per_cod`,`ver`,`nuevo`,`editar`,`eliminar`,`usu_crea`,`fch_crea`) VALUES ('MNU4699',12,'1','0','0','0','ad_fondoe','2014-01-17 10:54:42');


ALTER TABLE `t02_entregable` ADD COLUMN `monto_desemb` DOUBLE(10,2) NOT NULL DEFAULT 0  AFTER `t02_mes` ;


DROP procedure IF EXISTS `sp_lis_proyectos_ejec_desemb_entre`;
DELIMITER $$
CREATE PROCEDURE `sp_lis_proyectos_ejec_desemb_entre`(IN _inst INT)
BEGIN

SELECT

  inst.t01_sig_inst AS ejecutor,
  proy.t02_cod_proy AS codigo,    
  proy.t02_version AS vs,
  proy.t02_nom_proy AS nombre,
	proy.env_rev,
  DATE_FORMAT((CASE WHEN proy.t02_fch_ini=0 THEN NULL ELSE proy.t02_fch_ini END),'%d/%m/%Y') AS inicio, 
  DATE_FORMAT((CASE WHEN proy.t02_fch_ter=0 THEN NULL ELSE proy.t02_fch_ter END),'%d/%m/%Y') AS termino,  
  FORMAT(fn_costos_total_proyecto(proy.t02_cod_proy,1),2) AS t02_pres_tot, 
 COUNT(entre.t02_cod_proy) AS total_entre 
FROM
  t02_entregable entre, t02_dg_proy proy

LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
WHERE proy.t02_version = fn_ult_version_proy(proy.t02_cod_proy) 
  AND proy.t01_id_inst  = (CASE WHEN _inst='*' THEN proy.t01_id_inst ELSE _inst END ) 
  AND proy.t02_cod_proy = entre.t02_cod_proy  
GROUP BY proy.t02_cod_proy ;

END$$

DELIMITER ;




DROP procedure IF EXISTS `sp_sel_proyectos_ejec_desemb_entre`;
DELIMITER $$
CREATE PROCEDURE `sp_sel_proyectos_ejec_desemb_entre`(IN _proy VARCHAR(10), IN _vs INT)
BEGIN

SELECT

  inst.t01_sig_inst AS ejecutor,
  proy.t02_cod_proy AS codigo,  
entre.t02_cod_proy  AS cod,

  proy.t02_nom_proy AS nombre,
  entre.t02_mes AS mes,
  entre.t02_anio AS anio,
  entre.monto_desemb AS monto,
  fn_nom_periodo_entregable(proy.t02_cod_proy, proy.t02_version, entre.t02_anio,entre.t02_mes ) AS periodo, 
  fn_numero_entregable(proy.t02_cod_proy, proy.t02_version, entre.t02_anio,entre.t02_mes) AS entregable 
 
FROM
  t02_entregable entre, t02_dg_proy proy

LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst = inst.t01_id_inst) 
WHERE proy.t02_cod_proy = entre.t02_cod_proy  
  AND entre.t02_cod_proy = _proy 
GROUP BY entre.t02_mes,entre.t02_anio
ORDER BY entre.t02_anio, entre.t02_mes;


END$$

DELIMITER ;




