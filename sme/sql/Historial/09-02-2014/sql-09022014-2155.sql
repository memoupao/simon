/*
// -------------------------------------------------->
// AQ 2.0 [09-02-2014 21:55]
// Lista Informes Financieros pendientes de revisión
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_sel_inf_financ_vb;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_financ_vb`(IN _user VARCHAR(10))
BEGIN
	DECLARE var_all_proy INT DEFAULT 0;
	DECLARE var_type_user INT DEFAULT 0;
	
	SELECT IF(t02_cod_proy = '*', 1, 0), tipo_user INTO var_all_proy, var_type_user FROM adm_usuarios WHERE coduser = _user;
	
	IF var_type_user = 16 THEN /* SE */ 
		IF var_all_proy = 1 THEN
		   SELECT  inf.t02_cod_proy AS proy, 
		        inf.t40_anio, 
		        CONCAT('Año ',inf.t40_anio) AS anio,
		        inf.t40_mes, 
		        CONCAT('Mes ', inf.t40_mes) AS mes,
		        DATE_FORMAT(inf.t40_fch_pre,'%d/%m/%Y') AS fec_pre, 
		        inf.t40_periodo,
		        fn_nom_mes(inf.t40_periodo) AS periodo,
		        SUBSTRING(inf.t40_obs,1,205) AS observaciones,
		        est1.descrip AS est_eje,
		        est2.descrip AS est_moni,
		        FORMAT(IFNULL(
		        (
		        SELECT SUM(gas.t41_gasto) 
		             FROM  t41_inf_financ_gasto gas 
		             WHERE gas.t02_cod_proy= inf.t02_cod_proy
		               AND gas.t40_anio=inf.t40_anio
		               AND gas.t40_mes=inf.t40_mes)
		        ,0), 2) AS monto,
		        fn_numero_mes(inf.t40_anio, inf.t40_mes) AS nummes,
		        (CONCAT('Mes ', inf.t40_mes, ' (',fn_nom_mes(inf.t40_periodo), ')')) as descripcion,
		        IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
		    FROM      t40_inf_financ  inf
		    LEFT JOIN adm_tablas_aux  est1 ON (inf.t40_est_eje = est1.codi) 
		    LEFT JOIN adm_tablas_aux  est2 ON (inf.t40_est_mon = est2.codi)
		    WHERE inf.vb_se = 0
		    AND inf.t40_est_eje = 46
		    ORDER BY inf.t02_cod_proy, inf.t40_anio, inf.t40_mes, inf.t40_periodo;
		ELSE
			SELECT  inf.t02_cod_proy AS proy, 
		        inf.t40_anio, 
		        CONCAT('Año ',inf.t40_anio) AS anio,
		        inf.t40_mes, 
		        CONCAT('Mes ', inf.t40_mes) AS mes,
		        DATE_FORMAT(inf.t40_fch_pre,'%d/%m/%Y') AS fec_pre, 
		        inf.t40_periodo,
		        fn_nom_mes(inf.t40_periodo) AS periodo,
		        SUBSTRING(inf.t40_obs,1,205) AS observaciones,
		        est1.descrip AS est_eje,
		        est2.descrip AS est_moni,
		        FORMAT(IFNULL(
		        (
		        SELECT SUM(gas.t41_gasto) 
		             FROM  t41_inf_financ_gasto gas 
		             WHERE gas.t02_cod_proy= inf.t02_cod_proy
		               AND gas.t40_anio=inf.t40_anio
		               AND gas.t40_mes=inf.t40_mes)
		        ,0), 2) AS monto,
		        fn_numero_mes(inf.t40_anio, inf.t40_mes) AS nummes,
		        (CONCAT('Mes ', inf.t40_mes, ' (',fn_nom_mes(inf.t40_periodo), ')')) as descripcion,
		        IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
		    FROM      t40_inf_financ  inf
		    JOIN adm_usuarios u ON inf.t02_cod_proy = u.t02_cod_proy
		    LEFT JOIN adm_tablas_aux  est1 ON (inf.t40_est_eje = est1.codi) 
		    LEFT JOIN adm_tablas_aux  est2 ON (inf.t40_est_mon = est2.codi) 
		    WHERE inf.vb_se = 0
		    AND inf.t40_est_eje = 46
		    AND u.coduser = _user
		    ORDER BY inf.t02_cod_proy, inf.t40_anio, inf.t40_mes, inf.t40_periodo;
		END IF;
	ELSEIF var_type_user = 13 THEN /* GP */
	   IF var_all_proy = 1 THEN
           SELECT  inf.t02_cod_proy AS proy, 
                inf.t40_anio, 
                CONCAT('Año ',inf.t40_anio) AS anio,
                inf.t40_mes, 
                CONCAT('Mes ', inf.t40_mes) AS mes,
                DATE_FORMAT(inf.t40_fch_pre,'%d/%m/%Y') AS fec_pre, 
                inf.t40_periodo,
                fn_nom_mes(inf.t40_periodo) AS periodo,
                SUBSTRING(inf.t40_obs,1,205) AS observaciones,
                est1.descrip AS est_eje,
                est2.descrip AS est_moni,
                FORMAT(IFNULL(
                (
                SELECT SUM(gas.t41_gasto) 
                     FROM  t41_inf_financ_gasto gas 
                     WHERE gas.t02_cod_proy= inf.t02_cod_proy
                       AND gas.t40_anio=inf.t40_anio
                       AND gas.t40_mes=inf.t40_mes)
                ,0), 2) AS monto,
                fn_numero_mes(inf.t40_anio, inf.t40_mes) AS nummes,
                (CONCAT('Mes ', inf.t40_mes, ' (',fn_nom_mes(inf.t40_periodo), ')')) as descripcion,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM      t40_inf_financ  inf
            LEFT JOIN adm_tablas_aux  est1 ON (inf.t40_est_eje = est1.codi) 
            LEFT JOIN adm_tablas_aux  est2 ON (inf.t40_est_mon = est2.codi)
            WHERE inf.t40_est_eje = 46
            ORDER BY inf.t02_cod_proy, inf.t40_anio, inf.t40_mes, inf.t40_periodo;
        ELSE
            SELECT  inf.t02_cod_proy AS proy, 
                inf.t40_anio, 
                CONCAT('Año ',inf.t40_anio) AS anio,
                inf.t40_mes, 
                CONCAT('Mes ', inf.t40_mes) AS mes,
                DATE_FORMAT(inf.t40_fch_pre,'%d/%m/%Y') AS fec_pre, 
                inf.t40_periodo,
                fn_nom_mes(inf.t40_periodo) AS periodo,
                SUBSTRING(inf.t40_obs,1,205) AS observaciones,
                est1.descrip AS est_eje,
                est2.descrip AS est_moni,
                FORMAT(IFNULL(
                (
                SELECT SUM(gas.t41_gasto) 
                     FROM  t41_inf_financ_gasto gas 
                     WHERE gas.t02_cod_proy= inf.t02_cod_proy
                       AND gas.t40_anio=inf.t40_anio
                       AND gas.t40_mes=inf.t40_mes)
                ,0), 2) AS monto,
                fn_numero_mes(inf.t40_anio, inf.t40_mes) AS nummes,
                (CONCAT('Mes ', inf.t40_mes, ' (',fn_nom_mes(inf.t40_periodo), ')')) as descripcion,
                IF(inf.vb_se = 1, 'V°B°', '') AS vb_se
            FROM      t40_inf_financ  inf
            JOIN adm_usuarios u ON inf.t02_cod_proy = u.t02_cod_proy
            LEFT JOIN adm_tablas_aux  est1 ON (inf.t40_est_eje = est1.codi) 
            LEFT JOIN adm_tablas_aux  est2 ON (inf.t40_est_mon = est2.codi) 
            WHERE inf.t40_est_eje = 46
            AND u.coduser = _user
            ORDER BY inf.t02_cod_proy, inf.t40_anio, inf.t40_mes, inf.t40_periodo;
        END IF;
	END IF;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [09-02-2014 21:55]
// Incluye campo vb_se
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_get_inf_financ;

DELIMITER $$

CREATE PROCEDURE `sp_get_inf_financ`(IN _proy VARCHAR(10), 
                      IN _anio INT,
                      IN _mes  INT)
BEGIN
	SELECT  inf.t02_cod_proy, 
	    inf.t40_anio, 
	    inf.t40_mes, 
	    DATE_FORMAT(inf.t40_fch_pre,'%d/%m/%Y') AS t40_fch_pre, 
	    inf.t40_periodo , 
	    inf.t40_est_eje , 
	    inf.t40_est_mon ,
	    inf.t40_obs,
	    inf.t40_obs_moni,
	    DATE_FORMAT(inf.t40_apro_fch,'%d/%m/%Y') AS t40_apro_fch,
	    t40_otro_ing, 
	    t40_abo_bco, 
	    t40_otro_ing_obs, 
	    t40_abo_bco_obs, 
	    DATE_FORMAT(inf.t40_cor_ctb,'%d/%m/%Y') AS t40_cor_ctb,
	    t40_caja, 
	    t40_bco_mn, 
	    t40_ent_rend, 
	    t40_cxc, 
	    t40_cxp, 
	    t40_exc,
	    t40_caja_obs, 
	    t40_bco_mn_obs, 
	    t40_ent_rend_obs, 
	    t40_cxc_obs, 
	    t40_cxp_obs, 
	    t40_obs_exc,
	    inf_fi_ter,
	    vb_se
	FROM 
	    t40_inf_financ inf
	WHERE inf.t02_cod_proy = _proy
	  AND inf.t40_anio     = _anio
	  AND inf.t40_mes      = _mes ; 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [10-02-2014 00:30]
// Incluye campo vb_se
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_upd_inf_financ_cab;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_financ_cab`(IN _proy VARCHAR(10), 
            IN _anio INT, 
            IN _mes INT, 
            IN _anio_new INT, 
            IN _mes_new INT, 
            IN _fchpres DATETIME , 
            IN _periodo VARCHAR(50), 
            IN _obs  TEXT ,
            in _obsmoni text,
            IN _est_eje INT,
            IN _est_mon INT,
            IN _usr VARCHAR(20),                           
            IN _inf_financ_ter INT,
            IN _vb_se INT)
BEGIN
  UPDATE t40_inf_financ 
  SET   t40_anio = _anio_new, 
    t40_mes  = _mes_new, 
    t40_fch_pre = _fchpres, 
    t40_periodo = _periodo, 
    t40_est_eje = _est_eje, 
    t40_est_mon = _est_mon,
    t40_obs     = _obs,
    t40_obs_moni= _obsmoni,
    usr_actu = _usr, 
    inf_fi_ter = _inf_financ_ter,
    fch_actu = NOW(),
    vb_se = _vb_se
  WHERE t02_cod_proy=_proy 
    AND t40_anio=_anio 
    AND t40_mes=_mes;
    
    SELECT ROW_COUNT() AS numrows, _mes AS codigo;
                
END $$

DELIMITER ;