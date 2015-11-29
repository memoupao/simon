/*
// -------------------------------------------------->
// AQ 2.0 [14-02-2014 10:10]
// Lista POAs Técnicos pendientes de revisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_sel_poa_tec_vb`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_poa_tec_vb`(IN _user VARCHAR(10))
BEGIN
    DECLARE var_all_proy INT DEFAULT 0;
	DECLARE var_type_user INT DEFAULT 0;
	
	SELECT IF(t02_cod_proy = '*', 1, 0), tipo_user INTO var_all_proy, var_type_user FROM adm_usuarios WHERE coduser = _user;
	
	IF var_type_user = 16 THEN /* SE */ 
	    IF var_all_proy = 1 THEN
			SELECT
			    poa.t02_cod_proy AS proy,
		        poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
			    concat('Año ', poa.t02_anio) AS anio, 
			    poa.t02_periodo AS periodo, 
			    substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
			    FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
			    est.descrip AS estado,
			    IF(poa.vb_se_tec = 1, 'V°B°', '') AS vb_se
			FROM t02_poa poa, adm_tablas_aux est
			WHERE poa.t02_estado = est.codi 
            AND poa.t02_estado = 46
            AND poa.vb_se_tec = 0 
			ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        ELSE
           SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_tec = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_usuarios u, adm_tablas_aux est
            WHERE poa.t02_cod_proy = u.t02_cod_proy
            AND poa.t02_estado = est.codi 
            AND poa.t02_estado = 46
            AND poa.vb_se_tec = 0
            AND u.coduser = _user
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        END IF;
    ELSEIF var_type_user = 13 THEN /* GP */ 
        IF var_all_proy = 1 THEN
            SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_tec = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_tablas_aux est
            WHERE poa.t02_estado = est.codi 
            AND poa.t02_estado = 46
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        ELSE
           SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_tec = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_usuarios u, adm_tablas_aux est
            WHERE poa.t02_cod_proy = u.t02_cod_proy
            AND poa.t02_estado = est.codi 
            AND poa.t02_estado = 46
            AND u.coduser = _user
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        END IF;
    END IF;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [14-02-2014 12:28]
// Se incluyen campos vb_se_tec y vb_se_fin
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_get_poa`;

DELIMITER $$

CREATE PROCEDURE `sp_get_poa`(IN _proy VARCHAR(10), IN _anio INT)
BEGIN
	DECLARE _gen INT;
	DECLARE _verProy INT;
	
	SELECT t02_gen, t02_version
	INTO _gen, _verProy
	FROM t02_proy_version
	WHERE t02_cod_proy=_proy
	AND t02_tipo = 'POA'
	AND t02_anio = _anio;
	  
	SELECT poa.t02_cod_proy, 
	       poa.t02_anio, 
	       poa.t02_periodo, 
	       poa.t02_punto_aten, 
	       poa.t02_politica, 
	       poa.t02_benefic, 
	       poa.t02_otras_interv, 
	       poa.t02_estado, 
	       poa.t02_estado_mf, 
	       poa.t02_aprob_cmt , 
	       poa.t02_aprob_cmf , 
	       poa.t02_obs_cmt , 
	       poa.t02_obs_cmf ,
	       poa.usr_crea, 
	       poa.fch_crea,
	       _gen AS 'generado',
	       _verProy AS 'version',
	       poa.t02_unsuspend_flg,
	       vb_se_tec,
	       vb_se_fin
	FROM t02_poa poa
	WHERE poa.t02_cod_proy = _proy 
	AND poa.t02_anio = _anio;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [14-02-2014 13:13]
// Se incluye campo vb_se_tec
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_poa_upd_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_poa_upd_cab`(IN _proy VARCHAR(10), 
                    IN _anio INT, 
                    IN _periodo VARCHAR(50), 
                    IN _punto_aten TEXT, 
                    IN _politica TEXT, 
                    IN _benefic  TEXT, 
                    IN _otras_interv TEXT, 
                    IN _estado INT,
                    IN _obscmt TEXT, 
                    IN _aprob INT,
                    IN _usr VARCHAR(20),
                    IN _vb_se INT)
BEGIN
    UPDATE  t02_poa 
    SET     t02_periodo         = _periodo,
            t02_punto_aten      = _punto_aten,
            t02_politica        = _politica, 
            t02_benefic         = _benefic, 
            t02_otras_interv    = _otras_interv, 
            t02_estado          = _estado, 
            t02_aprob_cmt       = _aprob,
            t02_obs_cmt         = _obscmt,
            usr_actu            = _usr, 
            fch_actu            = NOW(),
            t02_tipo_poa        = 1,
            t02_fch_aprob       = IF((_estado = 257 AND t02_estado_mf = 262), CURDATE(), t02_fch_aprob),
            vb_se_tec           = _vb_se
    WHERE   t02_cod_proy        = _proy 
        AND t02_anio            = _anio;
       
SELECT ROW_COUNT() AS numrows, _anio AS codigo, '' AS msg;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [14-02-2014 15:41]
// Se incluye campo vb_se_fin
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_poa_upd_cabII`;

DELIMITER $$

CREATE PROCEDURE `sp_poa_upd_cabII`(IN _proy VARCHAR(10), 
                    IN _anio INT, 
                    IN _obscmf TEXT, 
                    IN _estadomf INT,
                    IN _aprob INT,
                    IN _usr VARCHAR(20),
                    IN _vb_se INT)
BEGIN
    UPDATE  t02_poa 
    SET     t02_aprob_cmf   = _aprob  ,
            t02_obs_cmf     = _obscmf ,
            t02_estado_mf   = _estadomf,
            usr_actu        = _usr, 
            fch_actu        = NOW(),
            t02_tipo_poa    = 0,
            t02_fch_aprob   = IF((_estadomf = 262 AND t02_estado = 257), CURDATE(), t02_fch_aprob),
            vb_se_fin       = _vb_se
    WHERE   t02_cod_proy    = _proy 
        AND t02_anio        = _anio;
    SELECT ROW_COUNT() AS numrows, _anio AS codigo, '' AS msg ;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [14-02-2014 15:44]
// Lista POAs Financieros pendientes de revisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_sel_poa_fin_vb`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_poa_fin_vb`(IN _user VARCHAR(10))
BEGIN
    DECLARE var_all_proy INT DEFAULT 0;
    DECLARE var_type_user INT DEFAULT 0;
    
    SELECT IF(t02_cod_proy = '*', 1, 0), tipo_user INTO var_all_proy, var_type_user FROM adm_usuarios WHERE coduser = _user;
    
    IF var_type_user = 16 THEN /* SE */ 
        IF var_all_proy = 1 THEN
            SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_fin = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_tablas_aux est
            WHERE poa.t02_estado_mf = est.codi 
            AND poa.t02_estado_mf = 259
            AND poa.vb_se_fin = 0 
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        ELSE
           SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_fin = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_usuarios u, adm_tablas_aux est
            WHERE poa.t02_cod_proy = u.t02_cod_proy
            AND poa.t02_estado_mf = est.codi 
            AND poa.t02_estado_mf = 259
            AND poa.vb_se_fin = 0
            AND u.coduser = _user
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        END IF;
    ELSEIF var_type_user = 13 THEN /* GP */ 
        IF var_all_proy = 1 THEN
            SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_fin = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_tablas_aux est
            WHERE poa.t02_estado_mf = est.codi 
            AND poa.t02_estado_mf = 259
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        ELSE
           SELECT
                poa.t02_cod_proy AS proy,
                poa.t02_anio,fn_version_proy_poa(poa.t02_cod_proy,poa.t02_anio) AS version,
                concat('Año ', poa.t02_anio) AS anio, 
                poa.t02_periodo AS periodo, 
                substring(poa.t02_punto_aten,1,200) AS punto_atencion, 
                FORMAT((fn_costos_total_proyecto(poa.t02_cod_proy, fn_version_proy_poa(poa.t02_cod_proy, poa.t02_anio))),2) AS presupuesto,
                est.descrip AS estado,
                IF(poa.vb_se_fin = 1, 'V°B°', '') AS vb_se
            FROM t02_poa poa, adm_usuarios u, adm_tablas_aux est
            WHERE poa.t02_cod_proy = u.t02_cod_proy
            AND poa.t02_estado_mf = est.codi 
            AND poa.t02_estado_mf = 259
            AND u.coduser = _user
            ORDER BY poa.t02_cod_proy, poa.t02_anio ASC;
        END IF;
    END IF;
END $$

DELIMITER ;