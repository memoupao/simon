/*
// -------------------------------------------------->
// AQ 2.0 [09-12-2013 14:04]
// Generación de versión inicial incluyendo 
// nuevas tablas: t02_entregable, t09_act_ind_car, 
// t09_act_ind_car_ctrl, t02_tasas_proy 
// y campos: t00_cod_linea.
// Considerar todos los años 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_poa_genera_version_inicial;

DELIMITER $$

CREATE PROCEDURE `sp_poa_genera_version_inicial`(IN _proy VARCHAR(10), 
                        IN _anio INT, 
                        IN _usr VARCHAR(20))
trans1 : BEGIN
    DECLARE _ver INT ;
    DECLARE _newVer INT ;
    DECLARE _priVer INT ;
    DECLARE _saltolinea VARCHAR(2);
    DECLARE _log TEXT;
     
    SELECT "\n", '' INTO _saltolinea, _log ;
    SELECT fn_ult_version_proy(_proy) INTO _ver ;
    SELECT fn_version_proy_poa(_proy, _anio) INTO _newVer ;
    SELECT fn_pri_version_proy(_proy) INTO _priVer ;
    IF IFNULL(_newVer,0) <= 0 THEN
    SELECT CONCAT('No se ha Guardado la caratula del POA, para el Año: ',_anio,', primero debe guardar la Caratula del POA y luego Generar la Versión') AS msg ;
       LEAVE trans1;
    END IF;

    SET AUTOCOMMIT=0;
    START TRANSACTION;
 
    SELECT 'Copiando desde : sp_poa_genera_version_inicial' INTO _log ;
     
    INSERT INTO t02_dg_proy(t02_cod_proy, t02_version, t02_nro_exp, t01_id_inst, t02_nom_proy, t02_fch_apro, t02_fch_ini, t02_fch_ter, t02_fin, t02_pro, t02_ben_obj, t02_amb_geo, t02_sect_prod, t02_subsect_prod, t02_pres_fe, t02_pres_eje, t02_pres_otro, t02_pres_tot, t02_moni_tema, t02_moni_fina, t02_moni_ext, t02_sup_inst, t02_dire_proy, t02_ciud_proy, t02_tele_proy, t02_fax_proy, t02_mail_proy, t02_estado, t02_mto_line_base, t02_mto_imprevisto, t02_est_imp, t02_cre_fe, t02_fch_isc, t02_fch_ire, t02_fch_tre, t02_fch_tam, t02_num_mes, t02_num_mes_amp, usr_crea, fch_crea, usr_actu, fch_actu, est_audi, env_rev)
    SELECT t02_cod_proy, _newVer, t02_nro_exp, t01_id_inst, t02_nom_proy, t02_fch_apro, t02_fch_ini, t02_fch_ter, t02_fin, t02_pro, t02_ben_obj, t02_amb_geo, t02_sect_prod, t02_subsect_prod, t02_pres_fe, t02_pres_eje, t02_pres_otro, t02_pres_tot, t02_moni_tema, t02_moni_fina, t02_moni_ext, t02_sup_inst, t02_dire_proy, t02_ciud_proy, t02_tele_proy, t02_fax_proy, t02_mail_proy, t02_estado, t02_mto_line_base, t02_mto_imprevisto, t02_est_imp, t02_cre_fe, t02_fch_isc, t02_fch_ire, t02_fch_tre, t02_fch_tam, t02_num_mes, t02_num_mes_amp, _usr, NOW(), NULL, NOW(), est_audi, env_rev  
    FROM t02_dg_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Proyectos "t02_dg_proy" - Registros:', ROW_COUNT()) INTO _log;
    
    INSERT INTO t02_duracion(t02_cod_proy, t02_version, t02_anio)
    SELECT t02_cod_proy, _newVer, t02_anio    
    FROM t02_duracion WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Años de Duracion "t02_duracion" - Registros:', ROW_COUNT()) INTO _log;
    
    INSERT INTO t03_dist_proy(t02_cod_proy, t02_version, t03_dpto, t03_prov, t03_dist, t03_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_dpto, t03_prov, t03_dist, t03_obs, _usr, NOW(), NULL, NOW(), est_audi    
    FROM t03_dist_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Distribucion Geografica del Proyecto "t03_dist_proy" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Finalidad */
    
    /* Indicadores de Finalidad */
    INSERT INTO t06_fin_ind(t02_cod_proy, t02_version, t06_cod_fin_ind, t06_ind, t06_um, t06_mta, t06_fv, t06_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t06_cod_fin_ind, t06_ind, t06_um, t06_mta, t06_fv, t06_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t06_fin_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Finalidad "t06_fin_ind" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Supuestos de Finalidad */
    INSERT INTO t06_fin_sup(t02_cod_proy, t02_version, t06_cod_fin_sup, t06_sup, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t06_cod_fin_sup, t06_sup, _usr, NOW(), NULL, NULL, est_audi
    FROM t06_fin_sup WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Supuestos de Finalidad "t06_fin_sup" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Propósito */
       
    /* Indicadores de Propósito */
    INSERT INTO t07_prop_ind(t02_cod_proy, t02_version, t07_cod_prop_ind, t07_ind, t07_um, t07_mta, t07_fv, t07_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t07_cod_prop_ind, t07_ind, t07_um, t07_mta, t07_fv, t07_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t07_prop_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Proposito "t07_prop_ind" - Registros:', ROW_COUNT()) INTO _log;   
       
    /* Supuestos de Propósito */
    INSERT INTO t07_prop_sup(t02_cod_proy, t02_version, t07_cod_prop_sup, t07_sup, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t07_cod_prop_sup, t07_sup, _usr, NOW(), NULL, NULL, est_audi
    FROM t07_prop_sup WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Supuestos de Proposito "t07_prop_sup" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Tasas del Proyecto */
    INSERT INTO t02_tasas_proy (t02_cod_proy, t02_version, t02_gratificacion, t02_porc_cts, t02_porc_ess, t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t02_gratificacion, t02_porc_cts, t02_porc_ess, t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv, _usr, NOW(), NULL, NULL, est_audi
    FROM t02_tasas_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Tasas del Proyecto "t02_tasas_proy" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Entregables */
    INSERT INTO t02_entregable (t02_cod_proy, t02_version, t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t02_anio, t02_mes, _usr, NOW(), NULL, NULL, est_audi
    FROM t02_entregable WHERE t02_cod_proy=_proy AND t02_version=_ver AND t02_anio>=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Entregables "t02_entregable" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Componentes */
    INSERT INTO t08_comp(t02_cod_proy, t02_version, t08_cod_comp, t08_comp_desc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t08_comp_desc, _usr, NOW(), NULL, NULL, est_audi
    FROM t08_comp WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Componentes "t08_comp" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Indicadores de Componentes */
    INSERT INTO t08_comp_ind(t02_cod_proy, t02_version, t08_cod_comp, t08_cod_comp_ind, t08_ind, t08_um, t08_mta, t08_fv, t08_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t08_cod_comp_ind, t08_ind, t08_um, t08_mta, t08_fv, t08_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t08_comp_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Componentes "t08_comp_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Supuestos de Componentes */
    INSERT INTO t08_comp_sup(t02_cod_proy, t02_version, t08_cod_comp, t08_cod_comp_sup, t08_sup, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t08_cod_comp_sup, t08_sup, _usr, NOW(), NULL, NULL, est_audi
    FROM t08_comp_sup WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Supuestos de Componentes "t08_comp_sup" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Productos */
    INSERT INTO t09_act(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_act, t09_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_act, t09_obs, _usr, NOW(), NULL, NULL, est_audi
                    FROM t09_act WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Productos "t09_act" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Indicadores de Producto */
    INSERT INTO t09_act_ind(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind, t09_um, t09_mta, t09_fv, t09_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind, t09_um, t09_mta, t09_fv, t09_obs, _usr, NOW(), NULL, NULL, est_audi
                    FROM t09_act_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Producto "t09_act_ind" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Productos - Metas */
    INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, _usr, NOW(), NULL, NULL, est_audi
                    FROM t09_act_ind_mtas WHERE t02_cod_proy=_proy AND t02_version=_ver AND t09_ind_anio>_anio;
                               
    INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, _usr, NOW(), NULL, NULL, est_audi
                    FROM t09_act_ind_mtas WHERE t02_cod_proy=_proy AND t02_version=_priVer AND t09_ind_anio=_anio;
                               
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Indicadores de Producto "t09_act_ind_mtas" - Registros:', ROW_COUNT()) INTO _log;   
    
    /* Características de los Indicadores de Producto */
    INSERT INTO t09_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_ind, t09_mta, t09_fv, t09_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_ind, t09_mta, t09_fv, t09_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act_ind_car WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Características de los Indicadores de Producto "t09_act_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Control de las Características de los Indicadores de Producto */
    INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act_ind_car_ctrl WHERE t02_cod_proy=_proy AND t02_version=_ver AND t09_car_anio>=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Control Características de los Indicadores de Producto "t09_act_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Actividades */
    INSERT INTO t09_subact(  t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_sub,
                                t09_um, t09_mta, t09_fv, t09_tipo_sub, t09_act_crit, t09_obs,
                                t09_mta_proy, t09_mta_repro, t09_resumen, fch_crea, usr_crea, usr_actu,
                                fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_sub,
            t09_um, t09_mta, t09_fv, t09_tipo_sub, t09_act_crit, t09_obs, 
            0,     t09_mta_repro, t09_resumen, NOW(), _usr,  NULL,
            NULL,  est_audi
    FROM t09_subact WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Actividades "t09_subact" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Actividades - Metas */
    INSERT INTO t09_sub_act_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_cod_mta, t09_anio, t09_mes, t09_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_cod_mta, t09_anio, t09_mes, t09_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_sub_act_mtas 
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t09_anio>_anio;
                               
    INSERT INTO t09_sub_act_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_cod_mta, t09_anio, t09_mes, t09_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_cod_mta, t09_anio, t09_mes, t09_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_sub_act_mtas 
    WHERE t02_cod_proy=_proy AND t02_version=_priVer AND t09_anio=_anio;                    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Actividades "t09_sub_act_mtas" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Presupuesto : Costeo de Actividades */
    INSERT INTO t10_cost_sub(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cost, t10_cate_cost, t10_um, t10_cant, t10_cu, t10_cost_parc, t10_cost_tot, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cost, t10_cate_cost, t10_um, t10_cant, t10_cu, t10_cost_parc, t10_cost_tot, _usr, NOW(), NULL, NULL, est_audi
    FROM t10_cost_sub WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Costeo de Actividades "t10_cost_sub" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Presupuesto Aportes de Fuentes de Financiamiento x Actividades */
    INSERT INTO t10_cost_fte(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cod_fte, t01_id_inst, t10_mont, t10_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cod_fte, t01_id_inst, t10_mont, t10_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM t10_cost_fte WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aportes x Fuentes de Financiamiento "t10_cost_fte" - Registros:', ROW_COUNT()) INTO _log;
    
    /*  Planes de Capacitacion  */
    INSERT INTO t12_plan_capac (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_nro_tema, t12_hor_cap, t12_nro_ben, t12_conten, t12_modulo, fch_crea, usr_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_nro_tema, t12_hor_cap, t12_nro_ben, t12_conten, t12_modulo, NOW(), _usr, NULL, NULL, est_audi
    FROM    t12_plan_capac
    WHERE   t02_cod_proy = _proy AND t02_version = _ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Planes de Capacitacion Cabecera "t12_plan_capac" - Registros:', ROW_COUNT()) INTO _log;
    INSERT INTO t12_plan_capac_tema (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_cod_tema, t12_tem_espe, t12_nro_hora, t12_nro_bene, fch_crea, usr_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_cod_tema, t12_tem_espe, t12_nro_hora, t12_nro_bene, NOW(), _usr, NULL, NULL, est_audi
    FROM    t12_plan_capac_tema
    WHERE   t02_cod_proy = _proy AND t02_version = _ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Planes de Capacitacion Detalle (Temas) "t12_plan_capac_tema" - Registros:', ROW_COUNT()) INTO _log;
    
    /*  Planes de Asistencia Tecnica    */
    INSERT INTO t12_plan_at (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_nro_tema, t12_hor_cap, t12_nro_ben, t12_conten, t12_modulo, fch_crea, usr_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_nro_tema, t12_hor_cap, t12_nro_ben, t12_conten, t12_modulo, NOW(), _usr, NULL, NULL, est_audi
    FROM    t12_plan_at
    WHERE   t02_cod_proy = _proy AND t02_version = _ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Planes de Asistencia Tecnica "t12_plan_at" - Registros:', ROW_COUNT()) INTO _log;
            
    /*  Planes de Creditos  */
    INSERT INTO t12_plan_cred (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_nro_ben, t12_mto_ben, t12_nro_cuo, t12_tasa_int, t12_obs, fch_crea, usr_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_nro_ben, t12_mto_ben, t12_nro_cuo, t12_tasa_int, t12_obs, NOW(), _usr, NULL, NULL, est_audi
    FROM    t12_plan_cred
    WHERE   t02_cod_proy = _proy AND t02_version = _ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Planes de Creditos Cabecera "t12_plan_cred" - Registros:', ROW_COUNT()) INTO _log;
    
    INSERT INTO t12_plan_cred_benef (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t11_cod_benef, t12_mto_ben, t12_nro_cuo, t12_tasa_int, t12_obs, fch_crea, usr_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t11_cod_benef, t12_mto_ben, t12_nro_cuo, t12_tasa_int, t12_obs, NOW(), _usr, NULL, NULL, est_audi
    FROM    t12_plan_cred_benef
    WHERE   t02_cod_proy = _proy AND t02_version = _ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Planes de Creditos Detalle "t12_plan_cred_benef" - Registros:', ROW_COUNT()) INTO _log;
    
    /*  Planes - Otros  */
    INSERT INTO t12_plan_otros (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_producto, t12_nro_ent, t12_nro_ben, t12_tot_ent, t12_conten, t12_tipo, fch_crea, usr_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t12_producto, t12_nro_ent, t12_nro_ben, t12_tot_ent, t12_conten, t12_tipo, NOW(), _usr, NULL, NULL, est_audi
    FROM    t12_plan_otros
    WHERE   t02_cod_proy = _proy AND t02_version = _ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Planes - Otros "t12_plan_otros" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Personal  */
    INSERT INTO t03_mp_per (t02_cod_proy, t02_version, t03_id_per, t03_nom_per, t03_tdr, t03_tdr_file, t03_um, 
                            t03_dedica, t03_remu, t03_perma, t03_remu_prom, t03_gasto_tot, usr_crea, fch_crea, 
                            usr_actu, fch_actu, est_audi, t03_mta_proy, t03_mta_repro)
    SELECT t02_cod_proy, _newVer, t03_id_per, t03_nom_per, t03_tdr, t03_tdr_file, t03_um, 
                            t03_dedica, t03_remu, t03_perma, t03_remu_prom, t03_gasto_tot, _usr, NOW(), 
                            NULL, NULL, est_audi, 0, t03_mta_repro
    FROM t03_mp_per WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Personal "t03_mp_per" - Registros:', ROW_COUNT()) INTO _log;
     
    /* Personal - Metas */
    INSERT INTO t03_mp_per_metas (t02_cod_proy, t02_version, t03_id_per, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_per, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_per_metas 
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t03_anio<_anio ;
                               
    INSERT INTO t03_mp_per_metas (t02_cod_proy, t02_version, t03_id_per, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_per, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_per_metas 
    WHERE t02_cod_proy=_proy AND t02_version=_priVer AND t03_anio=_anio ;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas del Personal "t03_mp_per_metas" - Registros:', ROW_COUNT()) INTO _log;
       
    /* Personal - Aportes de Fuentes de Financiamiento */
    INSERT INTO t03_mp_per_ftes (t02_cod_proy, t02_version, t03_id_per, t03_id_inst, t03_monto  , t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_per, t03_id_inst, t03_monto  , t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_per_ftes WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aporte de Fuentes de Financiamiento del Personal "t03_mp_per_ftes" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Equipamiento */
    INSERT INTO t03_mp_equi  (t02_cod_proy, t02_version, t03_id_equi, t03_nom_equi, t03_um, t03_cu, t03_cant, 
                                t03_costo, t03_costo_tot, usr_crea, fch_crea, usr_actu, fch_actu, est_audi,
                                t03_mta_proy, t03_mta_repro)
    SELECT  t02_cod_proy, _newVer, t03_id_equi, t03_nom_equi, t03_um, t03_cu, t03_cant, 
                        t03_costo, t03_costo_tot, _usr, NOW(), NULL , NULL, est_audi, 0, t03_mta_repro
    FROM t03_mp_equi 
    WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Equipamiento "t03_mp_equi" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Equipamiento - Metas */
    INSERT INTO t03_mp_equi_metas (t02_cod_proy, t02_version, t03_id_equi, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_equi, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_equi_metas 
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t03_anio<_anio ;
                               
    INSERT INTO t03_mp_equi_metas (t02_cod_proy, t02_version, t03_id_equi, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_equi, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_equi_metas 
    WHERE t02_cod_proy=_proy AND t02_version=_priVer AND t03_anio=_anio ;
                               
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Equipamiento "t03_mp_equi_metas" - Registros:', ROW_COUNT()) INTO _log;
    /* Equipamiento - Aportes de Fuentes de Financiamiento */
    INSERT INTO t03_mp_equi_ftes (t02_cod_proy, t02_version, t03_id_equi, t03_id_inst, t03_monto, t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT   t02_cod_proy, _newVer, t03_id_equi, t03_id_inst, t03_monto, t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_equi_ftes WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aporte de Fuentes de Financiamiento de Equipamiento "t03_mp_equi_ftes" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos de Funcionamiento */
    
    /* Partidas */
    INSERT INTO t03_mp_gas_fun_part(t02_cod_proy, t02_version, t03_partida, t03_um, t03_meta, usr_crea, fch_crea, 
                                        usr_actu, fch_actu, est_audi, t03_mta_proy, t03_mta_repro)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_um, t03_meta, _usr, NOW(), 
                                        NULL, NULL, est_audi, 0, t03_mta_repro
    FROM t03_mp_gas_fun_part WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Partidas "t03_mp_gas_fun_part" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Partidas - Metas */
    INSERT INTO t03_mp_gas_fun_metas (t02_cod_proy, t02_version, t03_partida, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_metas   
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t03_anio<_anio ;
       
    INSERT INTO t03_mp_gas_fun_metas (t02_cod_proy, t02_version, t03_partida, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_metas   
    WHERE t02_cod_proy=_proy AND t02_version=_priVer AND t03_anio=_anio ;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Partidas de Gastos "t03_mp_gas_fun_metas " - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos de Funcionamiento */
    INSERT INTO t03_mp_gas_fun_cost (t02_cod_proy, t02_version, t03_partida, t03_id_gasto, t03_descrip, t03_um, t03_cu, t03_cant, t03_cat_gast, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_id_gasto, t03_descrip, t03_um, t03_cu, t03_cant, t03_cat_gast, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_cost  WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Costos de Funcionamiento "t03_mp_gas_fun_cost " - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos de Funcionamiento - Aportes de Fuentes de Financiamiento */
    INSERT INTO t03_mp_gas_fun_ftes (t02_cod_proy, t02_version, t03_partida, t03_id_gasto, t03_id_inst, t03_monto, t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_id_gasto, t03_id_inst, t03_monto, t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_ftes  WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aporte de Fuentes Financ - Costos de Funcionamiento "t03_mp_gas_fun_ftes " - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos Administrativos */
    INSERT INTO t03_mp_gas_adm(t02_cod_proy, t02_version, t03_id_inst, t03_monto, t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, (t03_monto / fn_duracion_proy(t02_cod_proy,1) )  , t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_adm WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Costos Administrativos "t03_mp_gas_adm" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Linea de Base y Evaluación de Impacto */
    INSERT INTO t03_mp_linea_base(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
                  FROM t03_mp_linea_base WHERE t02_cod_proy=_proy AND t02_version=_ver;
    SELECT CONCAT(_log, _saltolinea, 'Copiado Linea de Base y Evaluación de Impacto  "t03_mp_linea_base" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Imprevistos */
    INSERT INTO t03_mp_imprevistos(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_imprevistos WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Imprevistos  "t03_mp_imprevistos" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Gastos de Supervisión de Proyectos */
    INSERT INTO t03_mp_gas_supervision(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_supervision WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Gastos de Supervisión de Proyectos  "t03_mp_gas_supervision" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Actualizamos los gastos con las nuevas metas */
    CALL sp_poa_actualiza_presupuesto(_proy, _newVer) ;
    
    /* Actualizamos las metas proyectadas y reprogramadas por default*/
    CALL sp_poa_actualiza_metas_reprogramadas(_proy, _anio) ;
    
    /* Actualizamos la Tabla t02_proy_version el Campo Generado en 1*/
    UPDATE t02_proy_version 
    SET t02_gen = 1,
        fch_gen = NOW(),
        usr_gen = _usr,
        gen_log = _log
    WHERE t02_cod_proy = _proy AND t02_tipo='POA' AND t02_anio= _anio;
    COMMIT ;
    SELECT '' AS msg, _log AS 'Log Generado';
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [09-12-2013 14:05]
// Generación de versión incluyendo 
// nuevas tablas: t02_entregable, t09_act_ind_car, 
// t09_act_ind_car_ctrl, t02_tasas_proy 
// y campos: t00_cod_linea. 
// Considerar todos los años
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_poa_genera_version;

DELIMITER $$

CREATE PROCEDURE sp_poa_genera_version(IN _proy VARCHAR(10), 
                                        IN _anio INT, 
                                        IN _usr VARCHAR(20))
trans1 : BEGIN
    DECLARE _ver INT;
    DECLARE _newVer INT;
    DECLARE _priVer INT;
    DECLARE _saltolinea VARCHAR(2);
    DECLARE _log TEXT;
     
    SELECT "\n", '' INTO _saltolinea, _log;
    SELECT fn_ult_version_proy(_proy) INTO _ver;
    SELECT fn_version_proy_poa(_proy, _anio) INTO _newVer;
    SELECT fn_pri_version_proy(_proy) INTO _priVer;
    
    IF IFNULL(_newVer,0) <= 0 THEN
    SELECT CONCAT('No existe el POA, del Año: ',_anio,', Para generar la Nueva Versión del Proyecto') AS msg;
    LEAVE trans1;
    END IF;
    
    SET AUTOCOMMIT=0;
    START TRANSACTION;
     
    SELECT 'Copiando desde : sp_poa_genera_version' INTO _log; 
     
    INSERT INTO t02_dg_proy(t02_cod_proy, t02_version, t02_nro_exp, t00_cod_linea, t01_id_inst, t02_nom_proy, t02_fch_apro, t02_fch_ini, t02_fch_ter, t02_fin, t02_pro, t02_ben_obj, t02_amb_geo, t02_sect_prod, t02_subsect_prod, t02_pres_fe, t02_pres_eje, t02_pres_otro, t02_pres_tot, t02_moni_tema, t02_moni_fina, t02_moni_ext, t02_sup_inst, t02_dire_proy, t02_ciud_proy, t02_tele_proy, t02_fax_proy, t02_mail_proy, t02_estado, t02_mto_line_base, t02_mto_imprevisto, t02_est_imp, t02_cre_fe, t02_fch_isc, t02_fch_ire, t02_fch_tre, t02_fch_tam, t02_num_mes, t02_num_mes_amp, usr_crea, fch_crea, usr_actu, fch_actu, est_audi, env_rev)
    SELECT t02_cod_proy, _newVer, t02_nro_exp, t00_cod_linea, t01_id_inst, t02_nom_proy, t02_fch_apro, t02_fch_ini, t02_fch_ter, t02_fin, t02_pro, t02_ben_obj, t02_amb_geo, t02_sect_prod, t02_subsect_prod, t02_pres_fe, t02_pres_eje, t02_pres_otro, t02_pres_tot, t02_moni_tema, t02_moni_fina, t02_moni_ext, t02_sup_inst, t02_dire_proy, t02_ciud_proy, t02_tele_proy, t02_fax_proy, t02_mail_proy, t02_estado, t02_mto_line_base, t02_mto_imprevisto, t02_est_imp, t02_cre_fe, t02_fch_isc, t02_fch_ire, t02_fch_tre, t02_fch_tam, t02_num_mes, t02_num_mes_amp, _usr, NOW(), NULL, NOW(), est_audi, env_rev  
    FROM t02_dg_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Proyectos "t02_dg_proy" - Registros:', ROW_COUNT()) INTO _log;
        
    INSERT INTO t02_duracion(t02_cod_proy, t02_version, t02_anio)
    SELECT t02_cod_proy, _newVer, t02_anio
    FROM t02_duracion WHERE t02_cod_proy = _proy AND t02_version = _ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Años de Duracion "t02_duracion" - Registros:', ROW_COUNT()) INTO _log;
    
    INSERT INTO t03_dist_proy(t02_cod_proy, t02_version, t03_dpto, t03_prov, t03_dist, t03_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_dpto, t03_prov, t03_dist, t03_obs, _usr, NOW(), NULL, NOW(), est_audi 
    FROM t03_dist_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado  Distribucion Geografica del Proyecto "t03_dist_proy" - Registros:', ROW_COUNT()) INTO _log;
    
    INSERT INTO t02_proy_anx(t02_cod_proy, t02_version, t02_cod_anx, t02_nom_file, t02_url_file, t02_desc_file, t02_anx_tip_cod, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t02_cod_anx, t02_nom_file, t02_url_file, t02_desc_file, t02_anx_tip_cod, _usr, NOW(), NULL, NOW(), est_audi 
    FROM t02_proy_anx WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Anexos del Proyecto "t02_proy_anx" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Finalidad */
    
    /* Indicadores de Finalidad */
    INSERT INTO t06_fin_ind(t02_cod_proy, t02_version, t06_cod_fin_ind, t06_ind, t06_um, t06_mta, t06_fv, t06_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t06_cod_fin_ind, t06_ind, t06_um, t06_mta, t06_fv, t06_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t06_fin_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Finalidad "t06_fin_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Supuestos de Finalidad */
    INSERT INTO t06_fin_sup (t02_cod_proy, t02_version, t06_cod_fin_sup, t06_sup, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t06_cod_fin_sup, t06_sup, _usr, NOW(), NULL, NULL, est_audi
    FROM t06_fin_sup WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Supuestos de Finalidad "t06_fin_sup" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Propósito */
    
    /* Indicadores de Propósito */
    INSERT INTO t07_prop_ind (t02_cod_proy, t02_version, t07_cod_prop_ind, t07_ind, t07_um, t07_mta, t07_fv, t07_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t07_cod_prop_ind, t07_ind, t07_um, t07_mta, t07_fv, t07_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t07_prop_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Proposito "t07_prop_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Supuestos de Propósito */
    INSERT INTO t07_prop_sup (t02_cod_proy, t02_version, t07_cod_prop_sup, t07_sup, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t07_cod_prop_sup, t07_sup, _usr, NOW(), NULL, NULL, est_audi
    FROM t07_prop_sup WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Supuestos de Proposito "t07_prop_sup" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Tasas del Proyecto */
    INSERT INTO t02_tasas_proy (t02_cod_proy, t02_version, t02_gratificacion, t02_porc_cts, t02_porc_ess, t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t02_gratificacion, t02_porc_cts, t02_porc_ess, t02_porc_gast_func, t02_porc_linea_base, t02_porc_imprev, t02_proc_gast_superv, _usr, NOW(), NULL, NULL, est_audi
    FROM t02_tasas_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Tasas del Proyecto "t02_tasas_proy" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Entregables */
    INSERT INTO t02_entregable (t02_cod_proy, t02_version, t02_anio, t02_mes, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t02_anio, t02_mes, _usr, NOW(), NULL, NULL, est_audi
    FROM t02_entregable WHERE t02_cod_proy=_proy AND t02_version=_ver AND t02_anio>=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Entregables "t02_entregable" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Componentes */
    INSERT INTO t08_comp (t02_cod_proy, t02_version, t08_cod_comp, t08_comp_desc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t08_comp_desc, _usr, NOW(), NULL, NULL, est_audi
    FROM t08_comp WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Componentes "t08_comp" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Indicadores de Componentes */
    INSERT INTO t08_comp_ind(t02_cod_proy, t02_version, t08_cod_comp, t08_cod_comp_ind, t08_ind, t08_um, t08_mta, t08_fv, t08_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t08_cod_comp_ind, t08_ind, t08_um, t08_mta, t08_fv, t08_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t08_comp_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Componentes "t08_comp_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Supuestos de Componentes */
    INSERT INTO t08_comp_sup(t02_cod_proy, t02_version, t08_cod_comp, t08_cod_comp_sup, t08_sup, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t08_cod_comp_sup, t08_sup, _usr, NOW(), NULL, NULL, est_audi
    FROM t08_comp_sup WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Supuestos de Componentes "t08_comp_sup" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Productos */
    INSERT INTO t09_act(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_act, t09_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_act, t09_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Productos "t09_act" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Indicadores de Producto */
    INSERT INTO t09_act_ind(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind, t09_um, t09_mta, t09_fv, t09_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind, t09_um, t09_mta, t09_fv, t09_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act_ind WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Indicadores de Producto "t09_act_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Producto - Metas */
    INSERT INTO t09_act_ind_mtas(t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_ind_anio, t09_ind_mes, t09_ind_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act_ind_mtas WHERE t02_cod_proy=_proy AND t02_version=_ver AND t09_ind_anio>=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Indicadores de Producto "t09_act_ind_mtas" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Características de los Indicadores de Producto */
    INSERT INTO t09_act_ind_car (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_ind, t09_mta, t09_fv, t09_obs, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_ind, t09_mta, t09_fv, t09_obs, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act_ind_car WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Características de los Indicadores de Producto "t09_act_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Control de las Características de los Indicadores de Producto */
    INSERT INTO t09_act_ind_car_ctrl (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_act_ind, t09_cod_act_ind_car, t09_car_anio, t09_car_mes, t09_car_ctrl, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_act_ind_car_ctrl WHERE t02_cod_proy=_proy AND t02_version=_ver AND t09_car_anio>=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Control Características de los Indicadores de Producto "t09_act_ind" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Actividades */
    INSERT INTO t09_subact (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_sub,
     t09_um,t09_mta, t09_fv,t09_tipo_sub, t09_act_crit, t09_obs, 
     t09_mta_proy, t09_mta_repro, t09_resumen, fch_crea, usr_crea, usr_actu,
     fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_sub, 
     t09_um,t09_mta, t09_fv,t09_tipo_sub, t09_act_crit, t09_obs, 
     0, 0, t09_resumen, NOW(), _usr, NULL,
     NULL, est_audi
    FROM t09_subact WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Actividades "t09_subact" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Actividades - Metas */
    INSERT INTO t09_sub_act_mtas (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_cod_mta, t09_anio, t09_mes, t09_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t09_cod_mta, t09_anio, t09_mes, t09_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t09_sub_act_mtas 
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t09_anio>=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Actividades "t09_sub_act_mtas" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Presupuesto : Costeo de Actividades */
    INSERT INTO t10_cost_sub (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cost, t10_cate_cost, t10_um, t10_cant, t10_cu, t10_cost_parc, t10_cost_tot, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cost, t10_cate_cost, t10_um, t10_cant, t10_cu, t10_cost_parc, t10_cost_tot, _usr, NOW(), NULL, NULL, est_audi
    FROM t10_cost_sub WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Costeo de Actividades "t10_cost_sub" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Presupuesto Aportes de Fuentes de Financiamiento x Actividades */
    INSERT INTO t10_cost_fte (t02_cod_proy, t02_version, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cod_fte, t01_id_inst, t10_mont, t10_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t08_cod_comp, t09_cod_act, t09_cod_sub, t10_cod_cost, t10_cod_fte, t01_id_inst, t10_mont, t10_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM t10_cost_fte WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aportes x Fuentes de Financiamiento "t10_cost_fte" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Personal */
    INSERT INTO t03_mp_per (t02_cod_proy, t02_version, t03_id_per, t03_nom_per, t03_tdr, t03_tdr_file, t03_um, 
     t03_dedica, t03_remu, t03_perma, t03_remu_prom, t03_gasto_tot, usr_crea, fch_crea, 
     usr_actu, fch_actu, est_audi, t03_mta_proy, t03_mta_repro)
    SELECT  t02_cod_proy, _newVer, t03_id_per, t03_nom_per, t03_tdr, t03_tdr_file, t03_um, 
     t03_dedica, t03_remu, t03_perma, t03_remu_prom, t03_gasto_tot, _usr, NOW(), 
     NULL, NULL, est_audi, 0, t03_mta_repro
    FROM  t03_mp_per WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Personal "t03_mp_per" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Personal - Metas */
    INSERT INTO t03_mp_per_metas (t02_cod_proy, t02_version, t03_id_per, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_per, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_per_metas 
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t03_anio<=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas del Personal "t03_mp_per_metas" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Personal - Aportes de Fuentes de Financiamiento */
    INSERT INTO t03_mp_per_ftes (t02_cod_proy, t02_version, t03_id_per, t03_id_inst, t03_monto  , t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT  t02_cod_proy, _newVer, t03_id_per, t03_id_inst, t03_monto  , t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_per_ftes WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aporte de Fuentes de Financiamiento del Personal "t03_mp_per_ftes" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Equipamiento */
    INSERT INTO t03_mp_equi  (t02_cod_proy, t02_version, t03_id_equi, t03_nom_equi, t03_um, t03_cu, t03_cant, 
     t03_costo, t03_costo_tot, usr_crea, fch_crea, usr_actu, fch_actu, est_audi,
     t03_mta_proy, t03_mta_repro)
    SELECT  t02_cod_proy, _newVer, t03_id_equi, t03_nom_equi, t03_um, t03_cu, t03_cant, 
     t03_costo, t03_costo_tot, _usr, NOW(), NULL, NULL, est_audi,
     0, t03_mta_repro
    FROM t03_mp_equi  WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Equipamiento "t03_mp_equi" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Equipamiento - Metas */
    INSERT INTO t03_mp_equi_metas (t02_cod_proy, t02_version, t03_id_equi, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_equi, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_equi_metas 
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t03_anio<=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Equipamiento "t03_mp_equi_metas" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Equipamiento - Aportes de Fuentes de Financiamiento */
    INSERT INTO t03_mp_equi_ftes (t02_cod_proy, t02_version, t03_id_equi, t03_id_inst, t03_monto, t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_equi, t03_id_inst, t03_monto, t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM  t03_mp_equi_ftes WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aporte de Fuentes de Financiamiento de Equipamiento "t03_mp_equi_ftes" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos de Funcionamiento */
    
    /* Partidas */
    INSERT INTO t03_mp_gas_fun_part(t02_cod_proy, t02_version, t03_partida, t03_um, t03_meta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi, t03_mta_proy, t03_mta_repro)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_um, t03_meta, _usr , NOW(), NULL, NULL, est_audi, 0, t03_mta_repro
    FROM t03_mp_gas_fun_part WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Partidas "t03_mp_gas_fun_part" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Partidas - Metas */
    INSERT INTO t03_mp_gas_fun_metas (t02_cod_proy, t02_version, t03_partida, t03_anio, t03_mes, t03_mta, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_anio, t03_mes, t03_mta, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_metas
    WHERE t02_cod_proy=_proy AND t02_version=_ver AND t03_anio<=_anio;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Metas de Partidas de Gastos "t03_mp_gas_fun_metas " - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos de Funcionamiento */
    INSERT INTO t03_mp_gas_fun_cost (t02_cod_proy, t02_version, t03_partida, t03_id_gasto, t03_descrip, t03_um, t03_cu, t03_cant, t03_cat_gast, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_id_gasto, t03_descrip, t03_um, t03_cu, t03_cant, t03_cat_gast, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_cost  WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Costos de Funcionamiento "t03_mp_gas_fun_cost " - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos de Funcionamiento - Aportes de Fuentes de Financiamiento */
    INSERT INTO t03_mp_gas_fun_ftes (t02_cod_proy, t02_version, t03_partida, t03_id_gasto, t03_id_inst, t03_monto, t03_porc, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_partida, t03_id_gasto, t03_id_inst, t03_monto, t03_porc, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_fun_ftes  WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Aporte de Fuentes Financ - Costos de Funcionamiento "t03_mp_gas_fun_ftes " - Registros:', ROW_COUNT()) INTO _log;
    
    /* Costos Administrativos */
    INSERT INTO t03_mp_gas_adm(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_adm WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Costos Administrativos "t03_mp_gas_adm" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Linea de Base y Evaluación de Impacto */
    INSERT INTO t03_mp_linea_base(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_linea_base WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Linea de Base y Evaluación de Impacto  "t03_mp_linea_base" - Registros:', ROW_COUNT()) INTO _log;
     
    /* Imprevistos */
    INSERT INTO t03_mp_imprevistos(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_imprevistos WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Imprevistos  "t03_mp_imprevistos" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Gastos de Supervisión de Proyectos */
    INSERT INTO t03_mp_gas_supervision(t02_cod_proy, t02_version, t03_id_inst, t03_monto, usr_crea, fch_crea, usr_actu, fch_actu, est_audi)
    SELECT t02_cod_proy, _newVer, t03_id_inst, 0, _usr, NOW(), NULL, NULL, est_audi
    FROM t03_mp_gas_supervision WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    SELECT CONCAT(_log, _saltolinea, 'Copiado Gastos de Supervisión de Proyectos  "t03_mp_gas_supervision" - Registros:', ROW_COUNT()) INTO _log;
    
    /* Actualizamos los gastos con las nuevas metas */
    CALL sp_poa_actualiza_presupuesto(_proy, _newVer);
    
    /* Actualizamos las metas reprogramadas*/
    CALL sp_poa_actualiza_metas_reprogramadas(_proy, _anio);
    
    /* Actualizamos la Tabla t02_proy_version el Campo Generado en 1*/ 
    UPDATE t02_proy_version 
    SET t02_gen = 1,
     fch_gen = NOW(),
     usr_gen = _usr,
     gen_log = _log
    WHERE t02_cod_proy = _proy AND t02_tipo='POA' AND t02_anio= _anio;
    
    COMMIT;
    
    SELECT '' AS msg, _log AS 'Log Generado';
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [09-12-2013 11:05]
// Eliminación de versión incluyendo
// nuevas tablas: t02_entregable, t09_act_ind_car, 
// t09_act_ind_car_ctrl, t02_tasas_proy
// y campos: t00_cod_linea.
// Considerar todos los años 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_poa_eliminar;

DELIMITER $$

CREATE PROCEDURE sp_poa_eliminar(
IN _proy VARCHAR(10), 
IN _anio  INT)
trans1 : BEGIN
    DECLARE _ver  INT DEFAULT  fn_version_proy_poa(_proy, _anio);
    DECLARE _existe INT;
    DECLARE _rowsaffect INT;
    SELECT COUNT(1) INTO _existe 
    FROM t02_poa 
     WHERE t02_cod_proy=_proy AND t02_anio=_anio AND t02_estado=135;
    IF _existe > 0 THEN
    SELECT 0 AS numrows, CONCAT('El POA, del Año: ',_anio,', tiene el VB del Monitor.') AS msg;
    LEAVE trans1;
    END IF;
     SET AUTOCOMMIT=0;
     START TRANSACTION;
    /* Informes */
    DELETE FROM t20_inf_mes WHERE t02_cod_proy=_proy AND t20_anio = _anio;
    DELETE FROM t09_act_ind_mtas_inf WHERE t02_cod_proy=_proy AND t09_ind_anio = _anio;
    DELETE FROM t09_act_sub_mtas_inf WHERE t02_cod_proy=_proy AND t09_sub_anio = _anio;
    DELETE FROM t25_inf_trim WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_anx WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_at WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_capac WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_cred WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_otros WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t08_comp_ind_inf WHERE t02_cod_proy=_proy AND t08_ind_anio = _anio;
    DELETE FROM t07_prop_ind_inf WHERE t02_cod_proy=_proy AND t07_ind_anio = _anio;
    DELETE FROM t40_inf_financ WHERE t02_cod_proy=_proy AND t40_anio = _anio;
    -- -------------------------------------------------------------------------------------
    -- DELETE FROM t30_inf_me WHERE t02_cod_proy=_proy AND t02_version = _ver
    -- DELETE FROM t45_inf_mi WHERE t02_cod_proy=_proy AND t02_version = _ver
    -- DELETE FROM t08_comp_ind_inf_me WHERE t02_cod_proy=_proy AND t02_version = _ver;
    -- DELETE FROM t08_comp_ind_inf_mi WHERE t02_cod_proy=_proy AND t02_version = _ver;
    -- DELETE FROM t09_act_ind_inf_me WHERE t02_cod_proy=_proy AND t02_version = _ver
    -- DELETE FROM t09_act_ind_inf_mi WHERE t02_cod_proy=_proy AND t02_version = _ver
    -- DELETE FROM t09_act_sub_mtas_inf_me WHERE t02_cod_proy=_proy AND t02_version = _ver
    -- DELETE FROM t09_act_sub_mtas_inf_mi WHERE t02_cod_proy=_proy AND t02_version = _ver
    -- -------------------------------------------------------------------------------------
    /* Planes Especificos a Beneficiarios en POA */
    DELETE FROM t12_plan_at WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_capac WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_capac_tema WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_cred WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_cred_benef WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_otros WHERE t02_cod_proy=_proy AND t02_version = _ver;
    -- -------------------------------------------------------------------------------------
    /* Cronograma y Presupuesto */
    DELETE FROM t09_sub_act_mtas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_sub_act_plan WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t10_cost_fte WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t10_cost_sub WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_subact WHERE t02_cod_proy=_proy AND t02_version = _ver;
     --  Manejo del Proyecto
    DELETE FROM t03_mp_per_metas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_per_ftes WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_per WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_equi_metas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_equi_ftes WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_equi WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_metas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_ftes WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_cost WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_part WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_imprevistos WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_linea_base WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_adm WHERE t02_cod_proy=_proy AND t02_version = _ver;
    -- -------------------------------------------------------------------------------------
    /* Marco Logico */
    
    /* Características de los Indicadores de Producto */
    DELETE FROM t09_act_ind_car WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    /* Control de las Características de los Indicadores de Producto */
    DELETE FROM t09_act_ind_car_ctrl WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    DELETE FROM t09_act_ind_mtas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_act_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_act WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t08_comp_sup WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t08_comp_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t08_comp WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t06_fin_sup WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t06_fin_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t07_prop_sup WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t07_prop_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    /* Anexos del proyecto */
    DELETE FROM t03_dist_proy WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t02_proy_anx WHERE t02_cod_proy=_proy AND t02_version = _ver;
    -- DELETE FROM t02_noobjecion_compra_anx WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t02_duracion WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t02_proy_version WHERE t02_cod_proy=_proy AND t02_version = _ver;
    /* POA */
    DELETE FROM t02_poa WHERE t02_cod_proy=_proy AND t02_anio = _anio;
    SELECT ROW_COUNT() INTO _rowsaffect;
    
    /* Tasas del Proyecto */
    DELETE FROM t02_tasas_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    /* Entregables */
    DELETE FROM t02_entregable WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    /* Proyecto */
    DELETE FROM t02_dg_proy WHERE t02_cod_proy=_proy AND t02_version = _ver;
    COMMIT;
    SELECT _rowsaffect AS numrows, _anio AS codigo, '' AS msg;
END $$

DELIMITER ;
