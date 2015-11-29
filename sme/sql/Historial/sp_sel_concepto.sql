DROP PROCEDURE IF EXISTS sp_sel_concepto;

DELIMITER $$
CREATE PROCEDURE sp_sel_concepto(IN in_concepto VARCHAR(30))
BEGIN
	CASE in_concepto
        WHEN 'linea' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_linea AS numero
            FROM t00_linea
            ORDER BY t00_nom_lar ASC;
        WHEN 'tasa' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_tasa AS numero
            FROM t00_tasa
            ORDER BY t00_nom_lar ASC;
        WHEN 'banco' THEN 
            SELECT 
		        t00_nom_lar AS nombre, 
		        t00_nom_abre AS abreviatura, 
		        t00_cod_bco AS numero
		    FROM t00_bancos 
		    ORDER BY t00_nom_lar ASC;
	    WHEN 'moneda' THEN 
	       SELECT 
		        t00_nom_lar AS nombre, 
		        t00_nom_abre AS abreviatura, 
		        t00_cod_mon AS numero
		    FROM t00_tipo_moneda 
		    ORDER BY t00_nom_abre ASC;
		WHEN 'tipo_anexo' THEN 
           SELECT 
                t02_anx_tip_desc AS nombre, 
                t02_anx_tip_abrev AS abreviatura, 
                t02_anx_tip_cod AS numero
            FROM t02_proy_anx_tip 
            ORDER BY t02_anx_tip_desc ASC;
        WHEN 'tipo_cuenta' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_cta AS numero
            FROM t00_tipo_cuenta 
            ORDER BY t00_nom_abre ASC;
	END CASE;
END $$
DELIMITER ;