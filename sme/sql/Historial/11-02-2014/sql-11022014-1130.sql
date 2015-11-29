/*
// -------------------------------------------------->
// AQ 2.0 [10-02-2014 08:58]
// Lista Informes Técnicos pendientes de revisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_delete_proy`;

DELIMITER $$

CREATE  PROCEDURE `sp_delete_proy`(IN _proy VARCHAR(10), IN _vs INT)
BEGIN
declare numrows int;
DELETE from t09_act WHERE t09_act.t02_cod_proy = _proy;
DELETE from t08_comp WHERE t08_comp.t02_cod_proy = _proy;
DELETE from t02_dg_proy WHERE t02_dg_proy.t02_cod_proy = _proy;
DELETE from t02_tasas_proy WHERE t02_tasas_proy.t02_cod_proy = _proy;
DELETE from t02_fuente_finan WHERE t02_fuente_finan.t02_cod_proy = _proy;
DELETE from t02_aprob_proy WHERE t02_aprob_proy.t02_cod_proy = _proy;
DELETE from t02_sector_prod WHERE t02_sector_prod.t02_cod_proy = _proy;
DELETE from t02_carta_fianza WHERE t02_carta_fianza.t02_cod_proy = _proy;
DELETE from t02_duracion WHERE t02_duracion.t02_cod_proy = _proy;

DELETE from t02_entregable WHERE t02_cod_proy = _proy;
DELETE from t02_entregable_act_ind WHERE t02_cod_proy = _proy;
DELETE from t02_entregable_act_ind_car WHERE t02_cod_proy = _proy;
DELETE from t02_suspenciones WHERE t02_cod_proy = _proy;

DELETE from t02_noobjecion_compra WHERE t02_noobjecion_compra.t02_cod_proy = _proy;
DELETE from t02_noobjecion_compra_anx WHERE t02_noobjecion_compra_anx.t02_cod_proy = _proy;
DELETE from t02_poa WHERE t02_poa.t02_cod_proy = _proy;
DELETE from t02_poa_anexos WHERE t02_poa_anexos.t02_cod_proy = _proy;
DELETE from t02_proy_anx WHERE t02_proy_anx.t02_cod_proy = _proy;
DELETE from t02_proy_ctas WHERE t02_proy_ctas.t02_cod_proy = _proy;
DELETE from t02_proy_version WHERE t02_proy_version.t02_cod_proy = _proy;
DELETE from t03_dist_proy WHERE t03_dist_proy.t02_cod_proy = _proy;
DELETE from t03_mp_equi WHERE t03_mp_equi.t02_cod_proy = _proy;
DELETE from t03_mp_equi_ftes WHERE t03_mp_equi_ftes.t02_cod_proy = _proy;
DELETE from t03_mp_equi_metas WHERE t03_mp_equi_metas.t02_cod_proy = _proy;
DELETE from t03_mp_gas_adm WHERE t03_mp_gas_adm.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_cost WHERE t03_mp_gas_fun_cost.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_ftes WHERE t03_mp_gas_fun_ftes.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_metas WHERE t03_mp_gas_fun_metas.t02_cod_proy = _proy;
DELETE from t03_mp_gas_fun_part WHERE t03_mp_gas_fun_part.t02_cod_proy = _proy;
DELETE from t03_mp_imprevistos WHERE t03_mp_imprevistos.t02_cod_proy = _proy;
DELETE from t03_mp_linea_base WHERE t03_mp_linea_base.t02_cod_proy = _proy;
DELETE from t03_mp_per WHERE t03_mp_per.t02_cod_proy = _proy;
DELETE from t03_mp_per_ftes WHERE t03_mp_per_ftes.t02_cod_proy = _proy;
DELETE from t03_mp_per_metas WHERE t03_mp_per_metas.t02_cod_proy = _proy;
DELETE from t04_equi_proy WHERE t04_equi_proy.t02_cod_proy = _proy;
DELETE from t04_sol_cambio_per WHERE t04_sol_cambio_per.t02_cod_proy = _proy;
DELETE from t06_fin_ind WHERE t06_fin_ind.t02_cod_proy = _proy;
DELETE from t06_fin_sup WHERE t06_fin_sup.t02_cod_proy = _proy;
DELETE from t07_prop_ind WHERE t07_prop_ind.t02_cod_proy = _proy;
DELETE from t07_prop_ind_inf WHERE t07_prop_ind_inf.t02_cod_proy = _proy;
DELETE from t07_prop_sup WHERE t07_prop_sup.t02_cod_proy = _proy;
DELETE from t08_comp_ind WHERE t08_comp_ind.t02_cod_proy = _proy;
DELETE from t08_comp_ind_inf WHERE t08_comp_ind_inf.t02_cod_proy = _proy;

DELETE from t08_comp_ind_inf_se WHERE t02_cod_proy = _proy;
DELETE from t08_comp_ind_inf_si WHERE t02_cod_proy = _proy;

DELETE from t08_comp_sup WHERE t08_comp_sup.t02_cod_proy = _proy;
DELETE from t09_act_ind WHERE t09_act_ind.t02_cod_proy = _proy;

DELETE from t09_act_ind_car WHERE t02_cod_proy = _proy;
DELETE from t09_act_ind_car_ctrl WHERE t02_cod_proy = _proy;
DELETE from t09_entregable_ind_inf WHERE t02_cod_proy = _proy;
DELETE from t09_prod_ind_inf_se WHERE t02_cod_proy = _proy;
DELETE from t09_prod_ind_inf_si WHERE t02_cod_proy = _proy;
DELETE from t09_prod_ind_car_inf_se WHERE t02_cod_proy = _proy;
DELETE from t09_prod_ind_car_inf_si WHERE t02_cod_proy = _proy;

DELETE from t09_act_inf_se WHERE t02_cod_proy = _proy;
DELETE from t09_act_inf_si WHERE t02_cod_proy = _proy;
DELETE from t09_act_ind_inf_mi WHERE t02_cod_proy = _proy;

DELETE from t09_act_ind_mtas WHERE t09_act_ind_mtas.t02_cod_proy = _proy;
DELETE from t09_act_ind_mtas_inf WHERE t09_act_ind_mtas_inf.t02_cod_proy = _proy;
DELETE from t09_act_sub_mtas_inf WHERE t09_act_sub_mtas_inf.t02_cod_proy = _proy;
DELETE from t09_act_sub_mtas_inf_mi WHERE t09_act_sub_mtas_inf_mi.t02_cod_proy = _proy;
DELETE from t09_sub_act_mtas WHERE t09_sub_act_mtas.t02_cod_proy = _proy;
DELETE from t09_sub_act_plan WHERE t09_sub_act_plan.t02_cod_proy = _proy;
DELETE from t09_subact WHERE t09_subact.t02_cod_proy = _proy;
DELETE from t10_cost_fte WHERE t10_cost_fte.t02_cod_proy = _proy;
DELETE from t10_cost_sub WHERE t10_cost_sub.t02_cod_proy = _proy;
DELETE from t11_bco_bene WHERE t11_bco_bene.t02_cod_proy = _proy;
DELETE from t12_plan_at WHERE t12_plan_at.t02_cod_proy = _proy;
DELETE from t12_plan_capac WHERE t12_plan_capac.t02_cod_proy = _proy;
DELETE from t12_plan_capac_tema WHERE t12_plan_capac_tema.t02_cod_proy = _proy;
DELETE from t12_plan_cred WHERE t12_plan_cred.t02_cod_proy = _proy;
DELETE from t12_plan_cred_benef WHERE t12_plan_cred_benef.t02_cod_proy = _proy;
DELETE from t12_plan_otros WHERE t12_plan_otros.t02_cod_proy = _proy;
DELETE from t20_inf_mes WHERE t20_inf_mes.t02_cod_proy = _proy;
DELETE from t20_inf_mes_anx WHERE t20_inf_mes_anx.t02_cod_proy = _proy;
DELETE from t20_inf_mes_prob WHERE t20_inf_mes_prob.t02_cod_proy = _proy;
DELETE from t25_inf_trim WHERE t25_inf_trim.t02_cod_proy = _proy;
DELETE from t25_inf_trim_anx WHERE t25_inf_trim_anx.t02_cod_proy = _proy;
DELETE from t25_inf_trim_at WHERE t25_inf_trim_at.t02_cod_proy = _proy;
DELETE from t25_inf_trim_capac WHERE t25_inf_trim_capac.t02_cod_proy = _proy;
DELETE from t25_inf_trim_cred WHERE t25_inf_trim_cred.t02_cod_proy = _proy;
DELETE from t25_inf_trim_otros WHERE t25_inf_trim_otros.t02_cod_proy = _proy;

DELETE from t25_inf_entregable WHERE t02_cod_proy = _proy;
DELETE from t25_inf_entregable_anx WHERE t02_cod_proy = _proy;
DELETE from t25_inf_entregable_at WHERE t02_cod_proy = _proy;
DELETE from t25_inf_entregable_capac WHERE t02_cod_proy = _proy;
DELETE from t25_inf_entregable_cred WHERE t02_cod_proy = _proy;
DELETE from t25_inf_entregable_otros WHERE t02_cod_proy = _proy;

DELETE from t30_inf_se WHERE t02_cod_proy = _proy;
DELETE from t30_inf_se_anexos WHERE t02_cod_proy = _proy;

DELETE from t31_plan_me WHERE t31_plan_me.t02_cod_proy = _proy;
DELETE from t31_plan_me_aprob WHERE t31_plan_me_aprob.t02_cod_proy = _proy;
DELETE from t32_plan_act WHERE t32_plan_act.t02_cod_proy = _proy;
DELETE from t32_plan_vta WHERE t32_plan_vta.t02_cod_proy = _proy;
DELETE from t40_inf_financ WHERE t40_inf_financ.t02_cod_proy = _proy;
DELETE from t40_inf_financ_anx WHERE t40_inf_financ_anx.t02_cod_proy = _proy;
DELETE from t41_inf_financ_gasto WHERE t41_inf_financ_gasto.t02_cod_proy = _proy;
DELETE from t42_inf_financ_gasto_det WHERE t42_inf_financ_gasto_det.t02_cod_proy = _proy;

DELETE from t45_inf_si WHERE t02_cod_proy = _proy;
DELETE from t45_inf_si_anexos WHERE t02_cod_proy = _proy;

DELETE from t46_cron_visita_mi WHERE t46_cron_visita_mi.t02_cod_proy = _proy;
DELETE from t50_equiv_codis WHERE t50_equiv_codis.t02_cod_proy = _proy;
DELETE from t50_plan_moni_ext WHERE t50_plan_moni_ext.t02_cod_proy = _proy;
DELETE from t51_inf_mf WHERE t51_inf_mf.t02_cod_proy = _proy;
DELETE from t51_inf_mf_anexos WHERE t51_inf_mf_anexos.t02_cod_proy = _proy;
DELETE from t51_inf_mf_avance_meta WHERE t51_inf_mf_avance_meta.t02_cod_proy = _proy;
DELETE from t51_inf_mf_avance_monto WHERE t51_inf_mf_avance_monto.t02_cod_proy = _proy;
DELETE from t51_inf_mf_docs WHERE t51_inf_mf_docs.t02_cod_proy = _proy;
DELETE from t51_inf_mf_gastos_no_aceptados WHERE t51_inf_mf_gastos_no_aceptados.t02_cod_proy = _proy;
DELETE from t51_inf_mf_observa WHERE t51_inf_mf_observa.t02_cod_proy = _proy;
DELETE from t52_inf_visita_mf WHERE t52_inf_visita_mf.t02_cod_proy = _proy;
DELETE from t52_inf_visita_mf_anexos WHERE t52_inf_visita_mf_anexos.t02_cod_proy = _proy;
DELETE from t55_inf_unico_anual WHERE t55_inf_unico_anual.t02_cod_proy = _proy;
DELETE from t55_inf_unico_anual_avance_meta WHERE t55_inf_unico_anual_avance_meta.t02_cod_proy = _proy;
DELETE from t55_inf_unico_anual_avance_presup WHERE t55_inf_unico_anual_avance_presup.t02_cod_proy = _proy;
DELETE from t59_aprob_primer_desemb WHERE t59_aprob_primer_desemb.t02_cod_proy = _proy;
DELETE from t60_aprobacion_desemb WHERE t60_aprobacion_desemb.t02_cod_proy = _proy;
DELETE from t60_desembolsos WHERE t60_desembolsos.t02_cod_proy = _proy;
DELETE from t60_ejecucion_desemb WHERE t60_ejecucion_desemb.t02_cod_proy = _proy;
DELETE from t60_ejecucion_desemb_me WHERE t60_ejecucion_desemb_me.t02_cod_proy = _proy;
DELETE from adm_usuarios WHERE adm_usuarios.t02_cod_proy = _proy;
set numrows = 1;
SELECT numrows;
END $$

DELIMITER ;