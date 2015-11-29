-- --------------------------------------------------------------------------------
-- DA v2:
-- Nuevo campo de Sector y producto promovido en el reporte de Datos generales del proyecto.
-- --------------------------------------------------------------------------------

DROP procedure IF EXISTS `sp_rpt_ficha_proyecto_all`;

DELIMITER $$
CREATE PROCEDURE `sp_rpt_ficha_proyecto_all`(IN _proy VARCHAR(10),  IN _ver   INT, IN _all TINYINT)
BEGIN
DECLARE _fe INT DEFAULT 10 ;
DECLARE _benef INT DEFAULT 63 ;
	SELECT	  inst.t01_sig_inst ,
		  proy.t02_cod_proy ,
		  proy.t02_nro_exp , 
		  proy.t02_version ,
		  proy.t02_nom_proy , 
		  proy.t01_id_inst, 
		  inst.t01_nom_inst ,
		  DATE_FORMAT(inst.t01_fch_fund,'%d/%m/%Y') AS t01_fch_fund,
		  proy.t02_fin,
		  proy.t02_pro,
			proy.t02_dire_proy as direccion,
			proy.t02_ciud_proy as ciudad,
		  fn_duracion_proy(proy.t02_cod_proy, proy.t02_version) AS duracion,
		  DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS t02_fch_ini,
		  DATE_FORMAT(proy.t02_fch_apro,'%d/%m/%Y') AS t02_fch_apro,
			DATE_FORMAT(proy.t02_fch_ire,'%d/%m/%Y') AS t02_fch_ire,
			DATE_FORMAT(proy.t02_fch_tre,'%d/%m/%Y') AS t02_fch_tre,
			DATE_FORMAT(proy.t02_fch_tam,'%d/%m/%Y') AS t02_fch_tam,
		  inst.t01_pres_anio AS presup_prom_anual,
		  NULL AS inst_colabora,
		  CONCAT(mt.t90_ape_pat,' ',mt.t90_ape_mat,', ',mt.t90_nom_equi) AS  moni_tema,
		  CONCAT(mf.t90_ape_pat,' ',mf.t90_ape_mat,', ',mf.t90_nom_equi) AS  moni_fina,
		  CONCAT(ext.t01_ape_pat,' ',ext.t01_ape_mat,', ',ext.t01_nom_cto) AS moni_exte,
		  proy.t02_ben_obj,
		  
		  fn_costos_total_proyecto(_proy, 1) AS t02_pres_tot,
		  
		  
		  fn_total_aporte_fuentes_financ3(_proy , 1, _fe ) AS t02_pres_fe,		 
		  
		  fn_total_aporte_fuentes_financ3(_proy , 1, proy.t01_id_inst ) AS t02_pres_eje,
		  
		  
		  ( fn_costos_total_proyecto(_proy, 1) - fn_total_aporte_fuentes_financ3(_proy , 1, _fe )   )AS aportes_contra,
		  
		  proy.t02_dire_proy,
		  proy.t02_ciud_proy,
		  proy.t02_tele_proy,
		  proy.t02_fax_proy,
		  proy.t02_mail_proy,
		  proy.t02_num_mes,
			proy.t02_num_mes_amp,
		  t.descrip,
          	  secmain.nom_tabla AS sector_main,
		  sec.descrip AS sector,
		  sub.descrip AS subsector,
		  sector_prod.t02_obs AS prod_promovido,


		  c.t02_nom_benef AS beneficiario,
		  ic.t01_nro_cta AS cuenta,		 
		  b.t00_nom_lar AS banco,
		  tp.t00_nom_lar AS tipocuenta,
		  m.t00_nom_lar AS moneda,
		  t.descrip as estado,
		  proy.usr_crea
		
	FROM	  t02_dg_proy proy
	LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
	LEFT  JOIN  t90_equi_fe   mt   ON(proy.t02_moni_tema  =  mt.t90_id_equi) 
	LEFT  JOIN  t90_equi_fe   mf   ON(proy.t02_moni_fina  =  mf.t90_id_equi) 
	LEFT  JOIN  t01_inst_cto  ext  ON(proy.t02_moni_ext = CONCAT(ext.t01_id_inst,'.',ext.t01_id_cto))
	
	LEFT  JOIN  t02_proy_ctas   c   ON(proy.t02_cod_proy  =  c.t02_cod_proy) 
	LEFT  JOIN  t01_inst_ctas   ic   ON(ic.t01_id_inst  =  c.t01_id_inst AND ic.t01_id_cta=c.t01_id_cta) 
	LEFT  JOIN  t00_bancos b ON(b.t00_cod_bco  =  ic.t00_cod_bco) 
	LEFT  JOIN  t00_tipo_cuenta tp ON(tp.t00_cod_cta  =  ic.t00_cod_cta) 
	LEFT  JOIN  t00_tipo_moneda m ON(m.t00_cod_mon  =  ic.t00_cod_mon) 
	LEFT  JOIN  adm_tablas_aux   t   ON(proy.t02_estado  =  t.codi) 

	LEFT  JOIN t02_sector_prod sector_prod ON (proy.t02_cod_proy = sector_prod.t02_cod_proy)  
	LEFT  JOIN  adm_tablas secmain ON (sector_prod.t02_sector_main=secmain.cod_tabla)
    	LEFT  JOIN  adm_tablas_aux sec ON (sector_prod.t02_sector=sec.codi)
	LEFT  JOIN  adm_tablas_aux2 sub ON (sector_prod.t02_subsec=sub.codi)
	
	WHERE proy.t02_cod_proy = (CASE WHEN _all = 1 THEN proy.t02_cod_proy ELSE _proy END)
	  AND proy.t02_version  = (CASE WHEN _all = 1 THEN fn_ult_version_proy(proy.t02_cod_proy) ELSE _ver END);	
END$$

DELIMITER ;




-- --------------------------------------------------------------------------------
-- DA. V2
-- Actualizacion de listado de personal del proyecto para el reporte: Cronograma de actividades.
-- --------------------------------------------------------------------------------

DROP procedure IF EXISTS `sp_ml_per`;
DELIMITER $$
CREATE PROCEDURE `sp_ml_per`(IN _proy VARCHAR(10),  IN _ver INT)
BEGIN

SELECT  anio.t02_cod_proy, 
	anio.t02_version , 	
	per.t03_nom_per,
	aux.abrev, 	
	anio.t02_anio,
	per.t03_id_per,
	COUNT(anio.t02_anio) AS totalMeta,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=1 THEN mta.t03_mta ELSE 0 END) AS t03_mes1,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=2 THEN mta.t03_mta ELSE 0 END) AS t03_mes2,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=3 THEN mta.t03_mta ELSE 0 END) AS t03_mes3,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=4 THEN mta.t03_mta ELSE 0 END) AS t03_mes4,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=5 THEN mta.t03_mta ELSE 0 END) AS t03_mes5,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=6 THEN mta.t03_mta ELSE 0 END) AS t03_mes6,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=7 THEN mta.t03_mta ELSE 0 END) AS t03_mes7,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=8 THEN mta.t03_mta ELSE 0 END) AS t03_mes8,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=9 THEN mta.t03_mta ELSE 0 END) AS t03_mes9,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=10 THEN mta.t03_mta ELSE 0 END) AS t03_mes10,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=11 THEN mta.t03_mta ELSE 0 END) AS t03_mes11,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio AND mta.t03_mes=12 THEN mta.t03_mta ELSE 0 END) AS t03_mes12,
	SUM(CASE WHEN mta.t03_anio=anio.t02_anio THEN mta.t03_mta ELSE 0 END) AS t03_tot_anio
	
FROM       t02_duracion     anio
LEFT  JOIN t03_mp_per       per ON (anio.t02_cod_proy=per.t02_cod_proy AND anio.t02_version=per.t02_version )
LEFT  JOIN t03_mp_per_metas mta ON (per.t02_cod_proy=mta.t02_cod_proy AND per.t02_version=mta.t02_version AND per.t03_id_per=mta.t03_id_per)
LEFT JOIN adm_tablas_aux aux ON (per.t03_um=aux.codi)

 WHERE anio.t02_cod_proy= _proy
   AND anio.t02_version = _ver
GROUP BY 1,2,3,4,5 

order by per.t03_id_per, t02_anio ASC;

END$$

DELIMITER ;






