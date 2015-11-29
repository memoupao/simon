DROP PROCEDURE IF EXISTS sp_ins_concepto;

DELIMITER $$
CREATE PROCEDURE sp_ins_concepto(IN in_concepto VARCHAR(30), IN in_nom VARCHAR(50), IN in_abre VARCHAR(15), IN in_usr VARCHAR(20))
BEGIN
    DECLARE var_id INT;
    
    CASE in_concepto
        WHEN 'linea' THEN 
            SELECT IFNULL(max(t00_cod_linea),0) + 1 INTO var_id FROM t00_lineas;
            INSERT INTO t00_linea (t00_cod_linea, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'tasa' THEN 
            SELECT IFNULL(max(t00_cod_tasa),0) + 1 INTO var_id FROM t00_tasas;
            INSERT INTO t00_tasa (t00_cod_tasa, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'banco' THEN 
            SELECT IFNULL(max(t00_cod_bco),0) + 1 INTO var_id FROM t00_bancos;
            INSERT INTO t00_bancos (t00_cod_bco, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'moneda' THEN 
            SELECT IFNULL(max(t00_cod_mon),0) + 1 INTO var_id FROM t00_tipo_moneda;
            INSERT INTO t00_tipo_moneda (t00_cod_mon, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'tipo_anexo' THEN 
            SELECT IFNULL(max(t02_anx_tip_cod),0) + 1 INTO var_id FROM t02_proy_anx_tip;
            INSERT INTO t02_proy_anx_tip (t02_anx_tip_cod, t02_anx_tip_desc, t02_anx_tip_abrev, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
        WHEN 'tipo_cuenta' THEN 
            SELECT IFNULL(max(t00_cod_cta),0) + 1 INTO var_id FROM t00_tipo_cuenta;
            INSERT INTO t00_tipo_cuenta (t00_cod_cta, t00_nom_lar, t00_nom_abre, fch_crea, usr_crea)
            VALUES (var_id, in_nom, in_abre, NOW(), in_usr);
    END CASE;

    SELECT ROW_COUNT() AS numrows, var_id AS codigo;
END $$
DELIMITER ;