/*
// -------------------------------------------------->
// AQ 2.0 [15-02-2014 18:15]
// Lista Informes de Supervisión sin V°B°
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_se_vb`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_se_vb`(IN _user VARCHAR(10))
BEGIN
	DECLARE var_all_proy INT DEFAULT 0;
	
    SELECT IF(t02_cod_proy = '*', 1, 0) INTO var_all_proy FROM adm_usuarios WHERE coduser = _user;
    
    IF var_all_proy = 1 THEN
        SELECT  
	        inf.t02_cod_proy AS proy,
	        inf.t02_anio,
	        CONCAT('Año ',inf.t02_anio) AS anio,
	        inf.t02_entregable,
	        DATE_FORMAT(inf.t30_fch_pre,'%d/%m/%Y') AS fec_pre, 
	        inf.t30_periodo AS periodo, 
	        inf.t30_estado, 
	        est.descrip AS estado,
	        SUBSTRING(inf.t30_intro, 1, 150) AS introduccion,
	        SUBSTRING(inf.t30_fuentes, 1, 150) AS fuentes,
	        fn_numero_entregable(inf.t02_cod_proy, fn_version_proy_poa(inf.t02_cod_proy, inf.t02_anio), inf.t02_anio, inf.t02_entregable) AS entregable
	    FROM t30_inf_se inf, adm_tablas_aux est
	    WHERE inf.t30_estado = est.codi
	    AND inf.t30_estado = 281
	    ORDER BY inf.t02_cod_proy, inf.t02_anio, entregable ASC;
    ELSE
        SELECT  
            inf.t02_cod_proy AS proy,
            inf.t02_anio,
            CONCAT('Año ',inf.t02_anio) AS anio,
            inf.t02_entregable,
            DATE_FORMAT(inf.t30_fch_pre,'%d/%m/%Y') AS fec_pre, 
            inf.t30_periodo AS periodo, 
            inf.t30_estado, 
            est.descrip AS estado,
            SUBSTRING(inf.t30_intro, 1, 150) AS introduccion,
            SUBSTRING(inf.t30_fuentes, 1, 150) AS fuentes,
            fn_numero_entregable(inf.t02_cod_proy, fn_version_proy_poa(inf.t02_cod_proy, inf.t02_anio), inf.t02_anio, inf.t02_entregable) AS entregable
        FROM t30_inf_se inf, adm_usuarios u, adm_tablas_aux est
        WHERE inf.t02_cod_proy = u.t02_cod_proy
        AND inf.t30_estado = est.codi
        AND inf.t30_estado = 281
        AND u.coduser = _user
        ORDER BY inf.t02_cod_proy, inf.t02_anio, entregable ASC;
    END IF;
END $$

DELIMITER ;