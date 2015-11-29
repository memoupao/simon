DROP PROCEDURE IF EXISTS sp_del_concepto;

DELIMITER $$
CREATE PROCEDURE sp_del_concepto (IN in_concepto VARCHAR(30), IN in_numero INT)
BEGIN
    CASE in_concepto
        WHEN 'linea' THEN 
            DELETE FROM t00_linea WHERE t00_cod_linea = in_numero;
        WHEN 'tasa' THEN 
            DELETE FROM t00_tasa WHERE t00_cod_tasa = in_numero;
        WHEN 'banco' THEN 
            DELETE FROM t00_bancos WHERE t00_cod_bco = in_numero;
        WHEN 'moneda' THEN 
            DELETE FROM t00_tipo_moneda WHERE t00_cod_mon = in_numero;
        WHEN 'tipo_anexo' THEN 
            DELETE FROM t02_proy_anx_tip WHERE t02_anx_tip_cod = in_numero;
        WHEN 'tipo_cuenta' THEN 
            DELETE FROM t00_tipo_cuenta WHERE t00_cod_cta = in_numero;
    END CASE;

    SELECT ROW_COUNT() AS numrows;
END $$
DELIMITER ;