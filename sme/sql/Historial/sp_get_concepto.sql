DROP PROCEDURE IF EXISTS sp_get_concepto;

DELIMITER $$
CREATE PROCEDURE sp_get_concepto (IN in_concepto VARCHAR(30), IN in_numero INT)
BEGIN
	CASE in_concepto
        WHEN 'linea' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_linea AS numero
            FROM t00_linea
            WHERE t00_cod_linea = in_numero;
        WHEN 'tasa' THEN 
            SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_tasa AS numero
            FROM t00_tasa
            WHERE t00_cod_tasa = in_numero;
        WHEN 'banco' THEN 
            SELECT 
		        t00_nom_lar AS nombre, 
		        t00_nom_abre AS abreviatura, 
		        t00_cod_bco AS numero
		    FROM t00_bancos
		    WHERE t00_cod_bco = in_numero;
        WHEN 'moneda' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_mon AS numero
            FROM t00_tipo_moneda 
            WHERE t00_cod_mon = in_numero;
        WHEN 'tipo_anexo' THEN 
            SELECT 
                t02_anx_tip_desc AS nombre, 
                t02_anx_tip_abrev AS abreviatura, 
                t02_anx_tip_cod AS numero
            FROM t02_proy_anx_tip
            WHERE t02_anx_tip_cod = in_numero;
        WHEN 'tipo_cuenta' THEN 
           SELECT 
                t00_nom_lar AS nombre, 
                t00_nom_abre AS abreviatura, 
                t00_cod_cta AS numero
            FROM t00_tipo_cuenta 
            WHERE t00_cod_cta = in_numero;
    END CASE;
END $$
DELIMITER ;