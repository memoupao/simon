/*
// -------------------------------------------------->
// AQ 2.0 [21-03-2014 18:31]
// Actualzación de Cuenta Bancaria 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_upd_ctas_inst;

DELIMITER $$

CREATE PROCEDURE `sp_upd_ctas_inst`(
			IN _id_inst INT,
			IN _id_cta INT, 
			IN _bco	INT,
			IN _codcta INT,
			IN _nrocta VARCHAR(20), 
			IN _mon INT,
			IN _usr VARCHAR(20))
BEGIN
    UPDATE t01_inst_ctas 
	SET t00_cod_bco = _bco, 
	t00_cod_cta = _codcta, 
	t01_nro_cta = _nrocta, 
	t00_cod_mon = _mon, 
	usr_actu = _usr, 
	fch_actu = NOW()
	WHERE t01_id_inst = _id_inst
	AND t01_id_cta = _id_cta;
    
    SELECT ROW_COUNT() AS numrows, _id_cta AS codigo, '' AS msg ;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [21-03-2014 20:29]
// Obtener Cuenta Bancaria 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS sp_get_cuenta;

DELIMITER $$

CREATE PROCEDURE `sp_get_cuenta`(IN _inst INT, IN _idcta INT)
BEGIN
SELECT cta.t01_id_cta,
    bco.t00_nom_lar AS banco,
    bco.t00_nom_abre AS banco2,
    tcta.t00_nom_lar AS tipocuenta,
    mon.t00_nom_lar AS moneda,
    cta.t01_nro_cta AS nrocuenta,
    cta.t01_txt_ref AS textoref,
    bco.t00_cod_bco AS cod_bco,
    tcta.t00_cod_cta AS tip_cta,
    mon.t00_cod_mon AS cod_mon
FROM      t01_inst_ctas    cta
LEFT JOIN t00_bancos       bco ON(cta.t00_cod_bco = bco.t00_cod_bco)
LEFT JOIN t00_tipo_cuenta tcta ON(cta.t00_cod_cta = tcta.t00_cod_cta)
LEFT JOIN t00_tipo_moneda  mon ON(cta.t00_cod_mon = mon.t00_cod_mon)
WHERE  cta.t01_id_inst = _inst 
  AND  cta.t01_id_cta  = _idcta;
  
END $$
DELIMITER ;