/*
// -------------------------------------------------->
// AQ 2.1 [26-04-2014 19:03]
// RF-005 
// --------------------------------------------------<
*/
DROP VIEW IF EXISTS v_aportes_convenio;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER
 VIEW v_aportes_convenio AS 
SELECT p.t02_cod_proy AS proy,
  i.t01_sig_inst AS ie,
  ROUND(p.t02_pres_tot, 2) AS presupuesto,
  ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, 10), 2) AS aporte_fe,
  ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, p.t01_id_inst), 2) AS aporte_ie, 
  ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, 63), 2) AS aporte_benef, 
  (ROUND(p.t02_pres_tot, 2) - ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, 10), 2) - ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, p.t01_id_inst), 2) - ROUND(fn_total_aporte_fuentes_financ3(p.t02_cod_proy, 1, 63), 2)) AS aporte_otros
FROM t02_dg_proy p 
LEFT JOIN t01_dir_inst i ON p.t01_id_inst = i.t01_id_inst
LEFT JOIN adm_tablas_aux e ON p.t02_estado = e.codi
WHERE p.t02_version = 1 
AND e.cod_ext IN (1,4,5);