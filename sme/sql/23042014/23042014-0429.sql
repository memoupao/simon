/*
// -------------------------------------------------->
// AQ 2.0 [23-04-2014 04:29]
// RF-010 Incidencia 158
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_get_mp_gastos_adm_poa`;

DELIMITER $$

CREATE PROCEDURE `sp_get_mp_gastos_adm_poa`(
        IN _proy VARCHAR(10), 
        IN _ver INT)
BEGIN

  SELECT
      f.t01_id_inst AS codigo,
      i.t01_sig_inst AS fuente,
      g.t03_monto / (SELECT fn_duracion_proy(_proy, _ver)) AS monto_max,
      (SELECT ga.t03_monto 
          FROM t03_mp_gas_adm ga 
          WHERE ga.t02_cod_proy = _proy 
          AND ga.t02_version = _ver
          AND ga.t03_id_inst = g.t03_id_inst) AS costo
  FROM t03_mp_gas_adm g
  LEFT JOIN t02_fuente_finan f ON(g.t03_id_inst = f.t01_id_inst AND g.t02_cod_proy = f.t02_cod_proy)
  LEFT JOIN t01_dir_inst i ON(f.t01_id_inst = i.t01_id_inst)
  WHERE g.t02_cod_proy = _proy
  AND g.t02_version  = 1;

END $$
DELIMITER ;