/*
// -------------------------------------------------->
// AQ 2.0 [10-02-2014 08:58]
// Lista Informes Técnicos pendientes de revisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_sel_inf_mes_vb;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_mes_vb`(IN _user VARCHAR(10))
BEGIN
    DECLARE var_all_proy INT DEFAULT 0;
    DECLARE var_type_user INT DEFAULT 0;
    
    SELECT IF(t02_cod_proy = '*', 1, 0), tipo_user INTO var_all_proy, var_type_user FROM adm_usuarios WHERE coduser = _user;
    
    IF var_type_user = 16 THEN /* SE */ 
        IF var_all_proy = 1 THEN
			SELECT inf.t02_cod_proy AS proy, 
                inf.t20_anio, 
                CONCAT('Año ',t20_anio) AS anio,
                inf.t20_mes, 
                CONCAT('Mes ', t20_mes) AS mes,
                inf.t20_ver_inf AS vsinf, 
                DATE_FORMAT(inf.t20_fch_pre,'%d/%m/%Y') AS fec_pre, 
                inf.t20_periodo AS periodo, 
                inf.t20_apro_fch AS fec_aprueba, 
                SUBSTRING(inf.t20_dificul,1,205) AS dificultades,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t20_inf_mes inf, adm_tablas_aux est
            WHERE inf.t20_estado = est.codi 
            AND inf.t20_estado = 46
            AND inf.vb_se = 0
            ORDER BY inf.t02_cod_proy, inf.t20_anio, inf.t20_mes, t20_fch_pre;
		ELSE
            SELECT inf.t02_cod_proy AS proy, 
                inf.t20_anio, 
                CONCAT('Año ',t20_anio) AS anio,
                inf.t20_mes, 
                CONCAT('Mes ', t20_mes) AS mes,
                inf.t20_ver_inf AS vsinf, 
                DATE_FORMAT(inf.t20_fch_pre,'%d/%m/%Y') AS fec_pre, 
                inf.t20_periodo AS periodo, 
                inf.t20_apro_fch AS fec_aprueba, 
                SUBSTRING(inf.t20_dificul,1,205) AS dificultades,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t20_inf_mes inf, adm_usuarios u, adm_tablas_aux est
            WHERE inf.t02_cod_proy = u.t02_cod_proy
            AND inf.t20_estado = est.codi 
            AND inf.t20_estado = 46
            AND inf.vb_se = 0
            AND u.coduser = _user
            ORDER BY inf.t02_cod_proy, inf.t20_anio, inf.t20_mes, t20_fch_pre;
        END IF;
    ELSEIF var_type_user = 13 THEN /* GP */
        IF var_all_proy = 1 THEN
            SELECT inf.t02_cod_proy AS proy, 
                inf.t20_anio, 
                CONCAT('Año ',t20_anio) AS anio,
                inf.t20_mes, 
                CONCAT('Mes ', t20_mes) AS mes,
                inf.t20_ver_inf AS vsinf, 
                DATE_FORMAT(inf.t20_fch_pre,'%d/%m/%Y') AS fec_pre, 
                inf.t20_periodo AS periodo, 
                inf.t20_apro_fch AS fec_aprueba, 
                SUBSTRING(inf.t20_dificul,1,205) AS dificultades,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t20_inf_mes inf, adm_tablas_aux est
            WHERE inf.t20_estado = est.codi 
            AND inf.t20_estado = 46
            ORDER BY inf.t02_cod_proy, inf.t20_anio, inf.t20_mes, t20_fch_pre;
        ELSE
            SELECT inf.t02_cod_proy AS proy, 
                inf.t20_anio, 
                CONCAT('Año ',t20_anio) AS anio,
                inf.t20_mes, 
                CONCAT('Mes ', t20_mes) AS mes,
                inf.t20_ver_inf AS vsinf, 
                DATE_FORMAT(inf.t20_fch_pre,'%d/%m/%Y') AS fec_pre, 
                inf.t20_periodo AS periodo, 
                inf.t20_apro_fch AS fec_aprueba, 
                SUBSTRING(inf.t20_dificul,1,205) AS dificultades,
                est.descrip AS estado,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM  t20_inf_mes inf, adm_usuarios u, adm_tablas_aux est
            WHERE inf.t02_cod_proy = u.t02_cod_proy
            AND inf.t20_estado = est.codi 
            AND inf.t20_estado = 46
            AND u.coduser = _user
            ORDER BY inf.t02_cod_proy, inf.t20_anio, inf.t20_mes, t20_fch_pre;
        END IF;
    END IF;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-02-2014 13:15]
// Incluye campo vb_se
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_upd_inf_mes_cab;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_mes_cab`(
            IN _proy varchar(10), 
            IN _anio INT, 
            IN _mes INT, 
            IN _anio_new INT, 
            IN _mes_new INT, 
            in _verInf int,
            IN _periodo varchar(50), 
            IN _fchpres datetime , 
            IN _estado INT,
            IN _usr varchar(20),
            IN _vb_se INT)
BEGIN
  update t20_inf_mes 
  set   t20_anio = _anio_new, 
    t20_mes  = _mes_new, 
    t20_fch_pre = _fchpres, 
    t20_periodo = _periodo, 
    t20_estado  = _estado, 
    t02_version = fn_ult_version_proy(_proy), 
    usr_actu = _usr, 
    fch_actu = now(),
    vb_se = _vb_se
  where t02_cod_proy=_proy 
    and t20_anio=_anio 
    and t20_mes=_mes 
    and t20_ver_inf = _verInf;
    
    SELECT ROW_COUNT() AS numrows, _verInf AS codigo ;                  
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-02-2014 15:42]
// Incluye campo vb_se
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_get_inf_mes;

DELIMITER $$

CREATE PROCEDURE `sp_get_inf_mes`(IN _proy VARCHAR(10), 
                           IN _anio INT,
                           IN _mes  INT,
                           IN _vs   INT)
BEGIN
	SELECT  inf.t02_cod_proy, 
	        inf.t20_anio, 
	        inf.t20_mes, 
	        inf.t20_ver_inf AS vsinf, 
	        DATE_FORMAT(inf.t20_fch_pre,'%d/%m/%Y') AS t20_fch_pre, 
	        inf.t20_periodo , 
	        inf.t20_avance  , 
	        inf.t20_apro_mt , 
	        inf.t20_apro_fch, 
	        inf.t20_obs , 
	        inf.t20_estado ,
	        inf.t20_ver_inf,
	        inf.t20_dificul,
	        inf.t20_program,
	        est.descrip as estado,
	        vb_se
	FROM 
	      t20_inf_mes inf 
	left join adm_tablas_aux est on(inf.t20_estado = est.codi)
	WHERE   inf.t02_cod_proy     = _proy
	    AND inf.t20_anio     = _anio
	    AND inf.t20_mes      = _mes;    
END $$

DELIMITER ;