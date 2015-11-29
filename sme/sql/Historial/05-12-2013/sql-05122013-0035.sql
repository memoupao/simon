/*
// -------------------------------------------------->
// AQ 2.0 [05-12-2013 00:35]
// El monto Máximo se calculará 
// en la presentación del dato
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  sp_sel_mp_gastos_adm;

DELIMITER $$

CREATE PROCEDURE `sp_sel_mp_gastos_adm`(_proy VARCHAR(10), _ver INT)
BEGIN
DECLARE _contador INT ;
DECLARE _rowcount INT ;
DECLARE _codfte   INT ;
DECLARE _nomfte   VARCHAR(50) ;
DECLARE _sql      TEXT;
DECLARE _subquery TEXT;
DECLARE _sqlMP      TEXT;
DECLARE _subqueryMP TEXT;
CREATE TEMPORARY TABLE tmpFuentesFinanc
( id INT AUTO_INCREMENT,
  actividad VARCHAR(50) ,
  codigo INT,
  fuente VARCHAR(250),
  total_fuente  DOUBLE,
  PRIMARY KEY (id)
) ;
INSERT INTO tmpFuentesFinanc(actividad, codigo, fuente, total_fuente)
SELECT 'Personal del Proyecto' AS Actividad,
    f.t01_id_inst AS codigo,
    i.t01_sig_inst AS fuente,
    IFNULL(SUM(fte.t03_monto),0) AS total_fuente
FROM       t02_fuente_finan f
LEFT  JOIN t01_dir_inst     i   ON(f.t01_id_inst = i.t01_id_inst)
LEFT  JOIN t03_mp_per_ftes  fte ON(f.t02_cod_proy=fte.t02_cod_proy AND fte.t02_version=_ver AND f.t01_id_inst=fte.t03_id_inst)
WHERE f.t02_cod_proy=_proy
GROUP BY 1,2,3
UNION ALL
SELECT 'Equipamiento del Proyecto' AS Actividad,
    f.t01_id_inst AS codigo,
    i.t01_sig_inst AS fuente,
    IFNULL(SUM(fte.t03_monto),0) AS total_fuente
FROM       t02_fuente_finan f
LEFT  JOIN t01_dir_inst     i   ON(f.t01_id_inst = i.t01_id_inst)
LEFT  JOIN t03_mp_equi_ftes  fte ON(f.t02_cod_proy=fte.t02_cod_proy AND fte.t02_version=_ver AND f.t01_id_inst=fte.t03_id_inst)
WHERE f.t02_cod_proy=_proy
GROUP BY 1,2,3
UNION ALL
SELECT 'Gastos de Funcionamiento' AS Actividad,
    f.t01_id_inst AS codigo,
    i.t01_sig_inst AS fuente,
    IFNULL(SUM(fte.t03_monto),0) AS total_fuente
FROM       t02_fuente_finan f
LEFT  JOIN t01_dir_inst     i   ON(f.t01_id_inst = i.t01_id_inst)
LEFT  JOIN t03_mp_gas_fun_ftes  fte ON(f.t02_cod_proy=fte.t02_cod_proy AND fte.t02_version=_ver AND f.t01_id_inst=fte.t03_id_inst)
WHERE f.t02_cod_proy=_proy
GROUP BY 1,2,3 
UNION ALL
SELECT  'Costos Operativos' AS Actividad,
    ins.t01_id_inst AS codigo,
    ins.t01_sig_inst AS fuente,
    SUM(fte.t10_mont) AS total
FROM  t10_cost_fte fte
INNER JOIN t01_dir_inst ins ON ( fte.t01_id_inst=ins.t01_id_inst )
WHERE fte.t02_cod_proy=_proy
  AND fte.t02_version=_ver
  AND fte.t10_mont>0 
GROUP BY 1,2,3 ;
SELECT  tmp.codigo, 
    tmp.fuente,
    SUM(total_fuente) AS financiado,
    /*
    // -------------------------------------------------->
    // AQ 2.0 [05-12-2013 00:35]
    // El monto Máximo se calculará 
    // en la presentación del dato
    // (SUM(total_fuente)*8/100) AS maximo,
    // --------------------------------------------------<
    */
    IFNULL((
       SELECT t03_monto
         FROM t03_mp_gas_adm adm
         WHERE adm.t02_cod_proy=_proy AND adm.t02_version=_ver AND adm.t03_id_inst=tmp.codigo
    ),0) AS costo
  FROM tmpFuentesFinanc AS tmp
GROUP BY tmp.codigo, tmp.fuente ;
/* Liberamos los recursos utilizados*/
DROP TEMPORARY TABLE tmpFuentesFinanc;
END $$

DELIMITER ;