/*
// -------------------------------------------------->
// AQ 2.0 [16-01-2014 12:25]
// Lista Características de Indicadores 
// de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_prod_en_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_prod_en_entregable`(in _proy varchar(10), _ver int, _anio INT, _entregable INT)
BEGIN
    SELECT DISTINCT
        concat(prod.t08_cod_comp,'.',prod.t09_cod_act) as codigo,
        concat(prod.t08_cod_comp,'.',prod.t09_cod_act, ' ', prod.t09_act ) as producto,
        prod.t08_cod_comp,
        prod.t09_cod_act,
        prod.t09_act,
        prod.t09_obs
    FROM t09_act prod 
    JOIN t02_entregable_act_ind prog ON (prod.t02_cod_proy = prog.t02_cod_proy AND prod.t08_cod_comp = prog.t08_cod_comp AND prod.t09_cod_act = prog.t09_cod_act AND prog.t02_anio = _anio AND prog.t02_mes = _entregable)
    WHERE prod.t02_cod_proy = _proy 
    AND prod.t02_version = _ver 
    ORDER BY prod.t08_cod_comp, prod.t09_cod_act;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [17-01-2014 16:24]
// Generación de versión inicial incluyendo 
// entregables de todo el proyecto
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
    FROM t02_entregable WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
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
// AQ 2.0 [17-01-2014 16:24]
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
    FROM t02_entregable WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
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
// AQ 2.0 [17-01-2014 17:45]
// Lista Características de Indicadores 
// de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_car_ind_prod_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_car_ind_prod_se`(IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _comp INT, 
                        IN _prod INT,
                        IN _ind INT,
                        IN _anio INT,
                        IN _entregable INT)
BEGIN
    SELECT 
        DISTINCT car.t09_cod_act,
        car.t09_cod_act_ind,
        car.t09_cod_act_ind_car,
        car.t09_ind AS nombre,
        inf.t09_obs AS obs,
        inf.t09_avance AS avance
    FROM t09_act_ind_car car
    JOIN t02_entregable_act_ind_car prog ON(car.t02_cod_proy=prog.t02_cod_proy AND car.t02_version = prog.t02_version AND car.t08_cod_comp=prog.t08_cod_comp AND car.t09_cod_act=prog.t09_cod_act AND car.t09_cod_act_ind=prog.t09_cod_act_ind AND car.t09_cod_act_ind_car=prog.t09_cod_act_ind_car AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
    LEFT JOIN t09_prod_ind_car_inf_se inf ON(prog.t02_cod_proy=inf.t02_cod_proy AND prog.t08_cod_comp=inf.t08_cod_comp AND prog.t09_cod_act=inf.t09_cod_prod AND prog.t09_cod_act_ind=inf.t09_cod_prod_ind AND prog.t09_cod_act_ind_car=inf.t09_cod_prod_ind_car AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
    WHERE car.t02_cod_proy = _proy
      AND car.t02_version = _ver
      AND car.t08_cod_comp = _comp
      AND car.t09_cod_act = _prod
      AND car.t09_cod_act_ind = _ind
    ORDER BY car.t09_cod_act_ind_car;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [17-01-2014 18:35]
// Lista Características de Indicadores 
// de Producto del Informe de Supervisión Interna
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_car_ind_prod_si`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_car_ind_prod_si`(IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _comp INT, 
                        IN _prod INT,
                        IN _ind INT,
                        IN _anio INT,
                        IN _entregable INT)
BEGIN
    SELECT 
        DISTINCT car.t09_cod_act,
        car.t09_cod_act_ind,
        car.t09_cod_act_ind_car,
        car.t09_ind AS nombre,
        inf.t09_obs AS obs,
        inf.t09_avance AS avance
    FROM t09_act_ind_car car
    JOIN t02_entregable_act_ind_car prog ON(car.t02_cod_proy=prog.t02_cod_proy AND car.t02_version = prog.t02_version AND car.t08_cod_comp=prog.t08_cod_comp AND car.t09_cod_act=prog.t09_cod_act AND car.t09_cod_act_ind=prog.t09_cod_act_ind AND car.t09_cod_act_ind_car=prog.t09_cod_act_ind_car AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
    LEFT JOIN t09_prod_ind_car_inf_si inf ON(prog.t02_cod_proy=inf.t02_cod_proy AND prog.t08_cod_comp=inf.t08_cod_comp AND prog.t09_cod_act=inf.t09_cod_prod AND prog.t09_cod_act_ind=inf.t09_cod_prod_ind AND prog.t09_cod_act_ind_car=inf.t09_cod_prod_ind_car AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
    WHERE car.t02_cod_proy = _proy
      AND car.t02_version = _ver
      AND car.t08_cod_comp = _comp
      AND car.t09_cod_act = _prod
      AND car.t09_cod_act_ind = _ind
    ORDER BY car.t09_cod_act_ind_car;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [17-01-2014 18:23]
// Lista Indicadores de Producto del Informe de Supervisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_act_monext`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_prod_se`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_ind_prod_se`(IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _comp INT, 
                        IN _prod INT, 
                        IN _anio INT,
                        IN _entregable INT)
BEGIN
    SELECT ind.t08_cod_comp,
        com.t08_comp_desc AS componente,
        ind.t09_cod_act,
        act.t09_act AS actividad,
        ind.t09_cod_act_ind,
        ind.t09_ind AS indicador,
        ind.t09_um,
        ind.t09_mta AS plan_mtatotal,
        IFNULL(
        (SELECT e.t09_cod_act_ind_val 
         FROM t02_entregable_act_ind e 
         WHERE e.t02_cod_proy=ind.t02_cod_proy 
           AND e.t02_version=ind.t02_version
           AND e.t08_cod_comp=ind.t08_cod_comp 
           AND e.t09_cod_act=ind.t09_cod_act
           AND e.t09_cod_act_ind=ind.t09_cod_act_ind
           AND e.t02_anio = _anio
           AND e.t02_mes = _entregable
         ), 0) AS meta_al_entregable,
        IFNULL(
        (SELECT SUM(a.t09_ind_avanc) 
         FROM t09_entregable_ind_inf a 
         WHERE a.t02_cod_proy=ind.t02_cod_proy 
           AND a.t08_cod_comp=ind.t08_cod_comp 
           AND a.t09_cod_prod=ind.t09_cod_act
           AND a.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL a.t09_ind_anio YEAR), INTERVAL a.t09_ind_entregable MONTH) < DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtaacum,
        IFNULL(
        (SELECT SUM(b.t09_ind_avanc) 
         FROM t09_entregable_ind_inf b 
         WHERE b.t02_cod_proy=ind.t02_cod_proy 
           AND b.t08_cod_comp=ind.t08_cod_comp 
           AND b.t09_cod_prod=ind.t09_cod_act
           AND b.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL b.t09_ind_anio YEAR), INTERVAL b.t09_ind_entregable MONTH) = DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_avance,
        IFNULL(
        (SELECT SUM(c.t09_ind_avanc) 
         FROM t09_entregable_ind_inf c 
         WHERE c.t02_cod_proy=ind.t02_cod_proy 
           AND c.t08_cod_comp=ind.t08_cod_comp 
           AND c.t09_cod_prod=ind.t09_cod_act
           AND c.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL c.t09_ind_anio YEAR), INTERVAL c.t09_ind_entregable MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtatotal,
        inf.t09_obs AS obs,
        inf.t09_avance AS avance
    FROM t09_act_ind ind
    INNER JOIN t08_comp com ON(ind.t02_cod_proy=com.t02_cod_proy AND ind.t02_version=com.t02_version AND ind.t08_cod_comp=com.t08_cod_comp)
    INNER JOIN t09_act act ON(ind.t02_cod_proy=act.t02_cod_proy AND ind.t02_version=act.t02_version AND ind.t08_cod_comp=act.t08_cod_comp AND ind.t09_cod_act=act.t09_cod_act)
    LEFT  JOIN t09_prod_ind_inf_se inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t02_anio=_anio AND inf.t02_entregable=_entregable)
    WHERE ind.t02_cod_proy = _proy
      AND ind.t02_version = _ver
      AND ind.t08_cod_comp = _comp
      AND ind.t09_cod_act = _prod
    GROUP BY ind.t08_cod_comp, com.t08_comp_desc, ind.t09_cod_act, act.t09_act,
         ind.t09_cod_act_ind, ind.t09_um, ind.t09_ind, ind.t09_mta;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [17-01-2014 18:34]
// Lista Indicadores de Producto del Informe de Supervisión Interna
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_act_monint`;
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_prod_si`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_ind_prod_si`(IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _comp INT, 
                        IN _prod INT, 
                        IN _anio INT,
                        IN _entregable INT)
BEGIN
    SELECT ind.t08_cod_comp,
        com.t08_comp_desc AS componente,
        ind.t09_cod_act,
        act.t09_act AS actividad,
        ind.t09_cod_act_ind,
        ind.t09_ind AS indicador,
        ind.t09_um,
        ind.t09_mta AS plan_mtatotal,
        IFNULL(
        (SELECT e.t09_cod_act_ind_val 
         FROM t02_entregable_act_ind e 
         WHERE e.t02_cod_proy=ind.t02_cod_proy 
           AND e.t02_version=ind.t02_version
           AND e.t08_cod_comp=ind.t08_cod_comp 
           AND e.t09_cod_act=ind.t09_cod_act
           AND e.t09_cod_act_ind=ind.t09_cod_act_ind
           AND e.t02_anio = _anio
           AND e.t02_mes = _entregable
         ), 0) AS meta_al_entregable,
        IFNULL(
        (SELECT SUM(a.t09_ind_avanc) 
         FROM t09_entregable_ind_inf a 
         WHERE a.t02_cod_proy=ind.t02_cod_proy 
           AND a.t08_cod_comp=ind.t08_cod_comp 
           AND a.t09_cod_prod=ind.t09_cod_act
           AND a.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL a.t09_ind_anio YEAR), INTERVAL a.t09_ind_entregable MONTH) < DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtaacum,
        IFNULL(
        (SELECT SUM(b.t09_ind_avanc) 
         FROM t09_entregable_ind_inf b 
         WHERE b.t02_cod_proy=ind.t02_cod_proy 
           AND b.t08_cod_comp=ind.t08_cod_comp 
           AND b.t09_cod_prod=ind.t09_cod_act
           AND b.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL b.t09_ind_anio YEAR), INTERVAL b.t09_ind_entregable MONTH) = DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_avance,
        IFNULL(
        (SELECT SUM(c.t09_ind_avanc) 
         FROM t09_entregable_ind_inf c 
         WHERE c.t02_cod_proy=ind.t02_cod_proy 
           AND c.t08_cod_comp=ind.t08_cod_comp 
           AND c.t09_cod_prod=ind.t09_cod_act
           AND c.t09_cod_prod_ind=ind.t09_cod_act_ind
           AND DATE_ADD(DATE_ADD(NOW(), INTERVAL c.t09_ind_anio YEAR), INTERVAL c.t09_ind_entregable MONTH) <= DATE_ADD(DATE_ADD(NOW(), INTERVAL _anio YEAR), INTERVAL _entregable MONTH)
         ),0) AS ejec_mtatotal,
        inf.t09_obs AS obs,
        inf.t09_avance AS avance
    FROM t09_act_ind ind
    INNER JOIN t08_comp com ON(ind.t02_cod_proy=com.t02_cod_proy AND ind.t02_version=com.t02_version AND ind.t08_cod_comp=com.t08_cod_comp)
    INNER JOIN t09_act act ON(ind.t02_cod_proy=act.t02_cod_proy AND ind.t02_version=act.t02_version AND ind.t08_cod_comp=act.t08_cod_comp AND ind.t09_cod_act=act.t09_cod_act)
    LEFT  JOIN t09_prod_ind_inf_si inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t02_anio=_anio AND inf.t02_entregable=_entregable)
    WHERE ind.t02_cod_proy = _proy
      AND ind.t02_version = _ver
      AND ind.t08_cod_comp = _comp
      AND ind.t09_cod_act = _prod
    GROUP BY ind.t08_cod_comp, com.t08_comp_desc, ind.t09_cod_act, act.t09_act,
         ind.t09_cod_act_ind, ind.t09_um, ind.t09_ind, ind.t09_mta;
END $$

DELIMITER ;


/*
// -------------------------------------------------->
// AQ 2.0 [17-01-2014 18:42]
// Lista de Indicadores de Producto del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_ind_prod_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_ind_prod_entregable`(IN _proy VARCHAR(10), 
                    IN _ver INT,
                    IN _comp INT,
                    IN _prod INT, 
                    IN _anio INT, 
                    IN _entregable INT)
BEGIN
    /* Seleccionamos Los Indicadores de Producto y aquellos indicadores que ya fueron informados*/
    SELECT  ind.t08_cod_comp,
        com.t08_comp_desc AS componente,
        ind.t09_cod_act,
        act.t09_act AS actividad,
        ind.t09_cod_act_ind,
        ind.t09_ind AS indicador,
        ind.t09_um,
        ind.t09_mta AS plan_mtatotal,
        /*SUM(CASE WHEN fn_numero_entregable(mta.t09_ind_anio,mta.t09_ind_entregable) < fn_numero_entregable(_anio,_entregable) THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtaacum,*/
        IFNULL(SUM(CASE WHEN fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, prog.t02_anio, prog.t02_mes) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) THEN prog.t09_cod_act_ind_val ELSE 0 END), 0) AS plan_mtaacum,
        /*SUM(CASE WHEN mta.t09_ind_anio=_anio AND mta.t09_ind_mes=_entregable THEN mta.t09_ind_mta ELSE 0 END) AS plan_mtames,*/
        IFNULL(prog.t09_cod_act_ind_val, 0) AS plan_mtames,
        /*IFNULL(
        (SELECT e.t09_cod_act_ind_val 
         FROM t02_entregable_act_ind e 
         WHERE e.t02_cod_proy=ind.t02_cod_proy 
           AND e.t02_version=ind.t02_version
           AND e.t08_cod_comp=ind.t08_cod_comp 
           AND e.t09_cod_act=ind.t09_cod_act
           AND e.t09_cod_act_ind=ind.t09_cod_act_ind
           AND e.t02_anio = _anio
           AND e.t02_mes = _entregable
         ), 0) AS plan_mtames,*/
        IFNULL((SELECT SUM(inf2.t09_ind_avanc) 
         FROM t09_entregable_ind_inf inf2 
         WHERE inf2.t02_cod_proy=ind.t02_cod_proy 
           AND inf2.t08_cod_comp=ind.t08_cod_comp  
           AND inf2.t09_cod_prod=ind.t09_cod_act 
           AND inf2.t09_cod_prod_ind=ind.t09_cod_act_ind 
           AND fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, inf2.t09_ind_anio, inf2.t09_ind_entregable) < fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) 
         ),0) AS ejec_mtaacum,
        IFNULL(MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_ind_avanc ELSE NULL END), 0) AS ejec_mtames,
        IFNULL((SELECT SUM(inf2.t09_ind_avanc) 
         FROM t09_entregable_ind_inf inf2 
         WHERE inf2.t02_cod_proy=ind.t02_cod_proy 
           AND inf2.t08_cod_comp=ind.t08_cod_comp  
           AND inf2.t09_cod_prod=ind.t09_cod_act 
           AND inf2.t09_cod_prod_ind=ind.t09_cod_act_ind 
           AND fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, inf2.t09_ind_anio, inf2.t09_ind_entregable) <= fn_numero_entregable(ind.t02_cod_proy, ind.t02_version, _anio, _entregable) 
         ), 0) AS ejec_mtatotal,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_descrip ELSE NULL END) AS descripcion,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_logros  ELSE NULL END) AS logros,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_dificul ELSE NULL END) AS dificultades,
        MAX(CASE WHEN inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable THEN inf.t09_obs ELSE NULL END) AS observaciones
    FROM       t09_act_ind ind
    INNER JOIN t08_comp    com  ON(ind.t02_cod_proy=com.t02_cod_proy AND ind.t02_version=com.t02_version AND ind.t08_cod_comp=com.t08_cod_comp )
    INNER JOIN t09_act     act  ON(ind.t02_cod_proy=act.t02_cod_proy AND ind.t02_version=act.t02_version AND ind.t08_cod_comp=act.t08_cod_comp AND ind.t09_cod_act=act.t09_cod_act)
    LEFT JOIN t09_entregable_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp  AND ind.t09_cod_act=inf.t09_cod_prod AND ind.t09_cod_act_ind=inf.t09_cod_prod_ind AND inf.t09_ind_anio=_anio AND inf.t09_ind_entregable=_entregable)
    /*LEFT JOIN t09_act_ind_mtas prog ON(ind.t02_cod_proy=prog.t02_cod_proy AND ind.t02_version=prog.t02_version AND ind.t08_cod_comp=prog.t08_cod_comp AND ind.t09_cod_act = prog.t09_cod_act AND ind.t09_cod_act_ind=prog.t09_cod_act_ind AND prog.t09_ind_anio=_anio AND prog.t09_ind_mes=_entregable)*/
    LEFT JOIN t02_entregable_act_ind prog ON(ind.t02_cod_proy=prog.t02_cod_proy AND ind.t02_version=prog.t02_version AND ind.t08_cod_comp=prog.t08_cod_comp AND ind.t09_cod_act = prog.t09_cod_act AND ind.t09_cod_act_ind=prog.t09_cod_act_ind AND prog.t02_anio=_anio AND prog.t02_mes=_entregable)
    WHERE ind.t02_cod_proy = _proy
      AND ind.t02_version = _ver
      AND ind.t08_cod_comp = _comp
      AND ind.t09_cod_act = _prod
      AND ind.t09_ind <> '' 
    GROUP BY ind.t08_cod_comp, com.t08_comp_desc, ind.t09_cod_act, act.t09_act,
         ind.t09_cod_act_ind, ind.t09_um, ind.t09_ind, ind.t09_mta;
END $$

DELIMITER ;