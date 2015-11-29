DROP PROCEDURE IF EXISTS sp_upd_concepto;

DELIMITER $$
CREATE PROCEDURE sp_upd_concepto(IN in_concepto VARCHAR(30), IN in_id int, IN in_nom VARCHAR(50), IN in_abre VARCHAR(15), IN in_usr VARCHAR(20))
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            UPDATE t00_linea   
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_linea = in_id;
        WHEN 'tasa' THEN 
            UPDATE t00_tasa
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_tasa = in_id;
        WHEN 'banco' THEN 
            UPDATE t00_bancos 
		    SET t00_nom_lar = in_nom,
		        t00_nom_abre = in_abre,
		        usr_actu = in_usr, 
		        fch_actu = NOW()
		    WHERE t00_cod_bco = in_id;
        WHEN 'moneda' THEN 
            UPDATE t00_tipo_moneda 
		    SET t00_nom_lar = in_nom,
		        t00_nom_abre = in_abre,
		        usr_actu = in_usr, 
		        fch_actu = NOW()
		    WHERE t00_cod_mon = in_id;
	    WHEN 'tipo_anexo' THEN 
            UPDATE t02_proy_anx_tip 
            SET t02_anx_tip_desc = in_nom,
                t02_anx_tip_abrev = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t02_anx_tip_cod = in_id;
        WHEN 'tipo_cuenta' THEN 
            UPDATE t00_tipo_cuenta 
            SET t00_nom_lar = in_nom,
                t00_nom_abre = in_abre,
                usr_actu = in_usr, 
                fch_actu = NOW()
            WHERE t00_cod_cta = in_id;
    END CASE;
    
    SELECT ROW_COUNT() AS numrows;
END $$
DELIMITER ;