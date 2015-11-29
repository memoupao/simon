/*
// -------------------------------------------------->
// AQ 2.1 [29-05-2014 01:13]
// Incluye fecha exacte de inicio y fin
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_get_poa`;

DELIMITER $$

CREATE PROCEDURE `sp_get_poa`(IN _proy VARCHAR(10), IN _anio INT)
BEGIN
  DECLARE _gen INT;
  DECLARE _verProy INT;
  
  SELECT t02_gen, t02_version
  INTO _gen, _verProy
  FROM t02_proy_version
  WHERE t02_cod_proy=_proy
  AND t02_tipo = 'POA'
  AND t02_anio = _anio;
    
  SELECT poa.t02_cod_proy, 
         poa.t02_anio, 
         poa.t02_periodo, 
         poa.t02_punto_aten, 
         poa.t02_politica, 
         poa.t02_benefic, 
         poa.t02_otras_interv, 
         poa.t02_estado, 
         poa.t02_estado_mf, 
         poa.t02_aprob_cmt , 
         poa.t02_aprob_cmf , 
         poa.t02_obs_cmt , 
         poa.t02_obs_cmf ,
         poa.usr_crea, 
         poa.fch_crea,
         _gen AS 'generado',
         _verProy AS 'version',
         poa.t02_unsuspend_flg,
         vb_se_tec,
         vb_se_fin,
         DATE_ADD(d.t02_fch_ini, INTERVAL (_anio-1) YEAR) AS inicio,
         DATE_SUB(DATE_ADD(d.t02_fch_ini, INTERVAL _anio YEAR), INTERVAL 1 DAY) AS fin
  FROM t02_poa poa
  JOIN t02_dg_proy d ON (poa.t02_cod_proy = d.t02_cod_proy AND d.t02_version = _anio + 1)
  WHERE poa.t02_cod_proy = _proy 
  AND poa.t02_anio = _anio;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [29-05-2014 01:47]
// Datos agrupados por comas
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS  `sp_sel_ambito_agrupado`;

DELIMITER $$
CREATE PROCEDURE `sp_sel_ambito_agrupado`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN 
  SELECT 
    GROUP_CONCAT(DISTINCT dep.nom_ubig SEPARATOR ', ') AS dpto,
    GROUP_CONCAT(DISTINCT pro.nom_ubig SEPARATOR ', ') AS prov,
    GROUP_CONCAT(DISTINCT dis.nom_ubig SEPARATOR ', ') AS dist
  FROM t03_dist_proy t03
  LEFT JOIN adm_ubigeo dep ON (t03.t03_dpto=dep.cod_dpto AND dep.cod_prov='00' AND dep.cod_dist='00')
  LEFT JOIN adm_ubigeo pro ON (t03.t03_dpto=pro.cod_dpto AND t03.t03_prov=pro.cod_prov AND pro.cod_dist='00')
  LEFT JOIN adm_ubigeo dis ON (t03.t03_dpto=dis.cod_dpto AND t03.t03_prov=dis.cod_prov AND t03.t03_dist=dis.cod_dist)
  WHERE t02_cod_proy = _proy
    AND t02_version  = _ver;
END $$
DELIMITER ;