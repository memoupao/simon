/*
// -------------------------------------------------->
// AQ 2.0 [11-02-2014 15:59]
// Lista de Informes de Entregable del Proyecto
// Sin VB
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable_vb`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable_vb`(IN _user VARCHAR(10))
BEGIN
	DECLARE var_all_proy INT DEFAULT 0;
    DECLARE var_type_user INT DEFAULT 0;
    
    SELECT IF(t02_cod_proy = '*', 1, 0), tipo_user INTO var_all_proy, var_type_user FROM adm_usuarios WHERE coduser = _user;
    
    IF var_type_user = 16 THEN /* SE */ 
        IF var_all_proy = 1 THEN
		    SELECT 
                inf.t02_cod_proy AS proy,
                inf.t02_version AS version,
                inf.t25_anio,
                CONCAT('Año ',inf.t25_anio) AS anio,
                inf.t25_entregable,
                fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t25_anio, inf.t25_entregable) AS entregable,
                DATE_FORMAT(inf.t25_fch_pre,'%d/%m/%Y') AS fec_pre,
                inf.t25_periodo AS periodo,
                (case when inf.t25_vb='1' then 'VB' else '' end) AS vb,
                inf.t25_vb_fch AS vb_fec,
                SUBSTRING(inf.t25_vb_txt,1,205) as aprueba_txt,
                (case when length(inf.t25_conclu)>205 then concat(SUBSTRING(inf.t25_conclu,1,205),' ...') else inf.t25_conclu end ) AS conclusiones,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t25_inf_entregable inf, adm_tablas_aux est
            WHERE inf.t25_estado = est.codi 
            AND inf.t25_estado = 46
            AND inf.vb_se = 0
            ORDER BY inf.t02_cod_proy, inf.t25_anio, entregable;
	    ELSE
	       SELECT 
                inf.t02_cod_proy AS proy,
                inf.t02_version AS version,
                inf.t25_anio,
                CONCAT('Año ',inf.t25_anio) AS anio,
                inf.t25_entregable,
                fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t25_anio, inf.t25_entregable) AS entregable,
                DATE_FORMAT(inf.t25_fch_pre,'%d/%m/%Y') AS fec_pre,
                inf.t25_periodo AS periodo,
                (case when inf.t25_vb='1' then 'VB' else '' end) AS vb,
                inf.t25_vb_fch AS vb_fec,
                SUBSTRING(inf.t25_vb_txt,1,205) as aprueba_txt,
                (case when length(inf.t25_conclu)>205 then concat(SUBSTRING(inf.t25_conclu,1,205),' ...') else inf.t25_conclu end ) AS conclusiones,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t25_inf_entregable inf, adm_usuarios u, adm_tablas_aux est
            WHERE inf.t02_cod_proy = u.t02_cod_proy
            AND inf.t25_estado = est.codi 
            AND inf.t25_estado = 46
            AND inf.vb_se = 0
            AND u.coduser = _user
            ORDER BY inf.t02_cod_proy, inf.t25_anio, entregable;
        END IF;
    ELSEIF var_type_user = 13 THEN /* GP */ 
        IF var_all_proy = 1 THEN
            SELECT 
                inf.t02_cod_proy AS proy,
                inf.t02_version AS version,
                inf.t25_anio,
                CONCAT('Año ',inf.t25_anio) AS anio,
                inf.t25_entregable,
                fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t25_anio, inf.t25_entregable) AS entregable,
                DATE_FORMAT(inf.t25_fch_pre,'%d/%m/%Y') AS fec_pre,
                inf.t25_periodo AS periodo,
                (case when inf.t25_vb='1' then 'VB' else '' end) AS vb,
                inf.t25_vb_fch AS vb_fec,
                SUBSTRING(inf.t25_vb_txt,1,205) as aprueba_txt,
                (case when length(inf.t25_conclu)>205 then concat(SUBSTRING(inf.t25_conclu,1,205),' ...') else inf.t25_conclu end ) AS conclusiones,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t25_inf_entregable inf, adm_tablas_aux est
            WHERE inf.t25_estado = est.codi 
            AND inf.t25_estado = 46
            ORDER BY inf.t02_cod_proy, inf.t25_anio, entregable;
        ELSE
           SELECT 
                inf.t02_cod_proy AS proy,
                inf.t02_version AS version,
                inf.t25_anio,
                CONCAT('Año ',inf.t25_anio) AS anio,
                inf.t25_entregable,
                fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t25_anio, inf.t25_entregable) AS entregable,
                DATE_FORMAT(inf.t25_fch_pre,'%d/%m/%Y') AS fec_pre,
                inf.t25_periodo AS periodo,
                (case when inf.t25_vb='1' then 'VB' else '' end) AS vb,
                inf.t25_vb_fch AS vb_fec,
                SUBSTRING(inf.t25_vb_txt,1,205) as aprueba_txt,
                (case when length(inf.t25_conclu)>205 then concat(SUBSTRING(inf.t25_conclu,1,205),' ...') else inf.t25_conclu end ) AS conclusiones,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t25_inf_entregable inf, adm_usuarios u, adm_tablas_aux est
            WHERE inf.t02_cod_proy = u.t02_cod_proy
            AND inf.t25_estado = est.codi 
            AND inf.t25_estado = 46
            AND u.coduser = _user
            ORDER BY inf.t02_cod_proy, inf.t25_anio, entregable;
        END IF;
    END IF;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [11-02-2014 17:50]
// Incluye campo vb_se 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_cab`(IN _proy varchar(10), 
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME, 
            IN _estado INT,
            IN _usr VARCHAR(20),
            IN _obs_gp TEXT, 
            IN _vb_se INT)
BEGIN
    UPDATE t25_inf_entregable 
    SET t25_fch_pre = _fchpres, 
        t25_periodo = _periodo, 
        t25_estado  = _estado, 
        usr_actu = _usr, 
        fch_actu = now(),
        obs_gp = _obs_gp,
        vb_se = _vb_se
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
    
    SELECT ROW_COUNT() as numrows, _ver as codigo;
END $$

DELIMITER ;