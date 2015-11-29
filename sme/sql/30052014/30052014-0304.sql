/*
// -------------------------------------------------->
// AQ 2.0 [30-05-2014 03:04]
// Incluye campo de Instituciones Asociadas y Colaboradoras
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_rpt_ficha_proyecto`;

DELIMITER $$
CREATE PROCEDURE `sp_rpt_ficha_proyecto`(IN _proy VARCHAR(10), 
 IN _ver INT)
BEGIN
    DECLARE _fe INT DEFAULT 10 ;
    DECLARE _benef INT DEFAULT 63 ;

    SELECT inst.t01_sig_inst ,
          proy.t02_cod_proy ,
          proy.t02_nro_exp , 
          proy.t02_version ,
          proy.t02_nom_proy , 
          proy.t01_id_inst, 
          inst.t01_nom_inst ,
          DATE_FORMAT(inst.t01_fch_fund,'%d/%m/%Y') AS t01_fch_fund,
          proy.t02_fin,
          proy.t02_pro,
          proy.t02_dire_proy as direccion,
          proy.t02_ciud_proy as ciudad,
          fn_duracion_proy(proy.t02_cod_proy, proy.t02_version) AS duracion,
          DATE_FORMAT(proy.t02_fch_ini,'%d/%m/%Y') AS t02_fch_ini,
          DATE_FORMAT(proy.t02_fch_apro,'%d/%m/%Y') AS t02_fch_apro,
            DATE_FORMAT(proy.t02_fch_ire,'%d/%m/%Y') AS t02_fch_ire,
            DATE_FORMAT(proy.t02_fch_tre,'%d/%m/%Y') AS t02_fch_tre,
            DATE_FORMAT(proy.t02_fch_tam,'%d/%m/%Y') AS t02_fch_tam,
          inst.t01_pres_anio AS presup_prom_anual,
          NULL AS inst_colabora,
          CONCAT(mt.t90_ape_pat,' ',mt.t90_ape_mat,', ',mt.t90_nom_equi) AS  moni_tema,
          CONCAT(mf.t90_ape_pat,' ',mf.t90_ape_mat,', ',mf.t90_nom_equi) AS  moni_fina,
          CONCAT(ext.t01_ape_pat,' ',ext.t01_ape_mat,', ',ext.t01_nom_cto) AS moni_exte,
          proy.t02_ben_obj,
          fn_costos_total_proyecto(_proy, 1) AS t02_pres_tot,
          fn_total_aporte_fuentes_financ3(_proy , 1, _fe ) AS t02_pres_fe,       
          fn_total_aporte_fuentes_financ3(_proy , 1, proy.t01_id_inst ) AS t02_pres_eje,
          ( fn_costos_total_proyecto(_proy, 1) - fn_total_aporte_fuentes_financ3(_proy , 1, _fe )   )AS aportes_contra,
          proy.t02_dire_proy,
          proy.t02_ciud_proy,
          proy.t02_tele_proy,
          proy.t02_fax_proy,
          proy.t02_mail_proy,
          proy.t02_num_mes,
          proy.t02_num_mes_amp,
          t.descrip,
          sec.descrip AS sector,
          sub.descrip AS subsector,
          c.t02_nom_benef AS beneficiario,
          ic.t01_nro_cta AS cuenta,      
          b.t00_nom_lar AS banco,
          tp.t00_nom_lar AS tipocuenta,
          m.t00_nom_lar AS moneda,
          t.descrip as estado,
          proy.usr_crea,
          proy.t02_inst_asoc AS inst_asoc_colab
    FROM      t02_dg_proy proy
    LEFT JOIN t01_dir_inst inst ON (proy.t01_id_inst=inst.t01_id_inst)
    LEFT  JOIN  t90_equi_fe   mt   ON(proy.t02_moni_tema  =  mt.t90_id_equi) 
    LEFT  JOIN  t90_equi_fe   mf   ON(proy.t02_moni_fina  =  mf.t90_id_equi) 
    LEFT  JOIN  t01_inst_cto  ext  ON(proy.t02_moni_ext = CONCAT(ext.t01_id_inst,'.',ext.t01_id_cto))
    
    LEFT  JOIN  t02_proy_ctas   c   ON(proy.t02_cod_proy  =  c.t02_cod_proy) 
    LEFT  JOIN  t01_inst_ctas   ic   ON(ic.t01_id_inst  =  c.t01_id_inst AND ic.t01_id_cta=c.t01_id_cta) 
    LEFT  JOIN  t00_bancos b ON(b.t00_cod_bco  =  ic.t00_cod_bco) 
    LEFT  JOIN  t00_tipo_cuenta tp ON(tp.t00_cod_cta  =  ic.t00_cod_cta) 
    LEFT  JOIN  t00_tipo_moneda m ON(m.t00_cod_mon  =  ic.t00_cod_mon) 
    LEFT  JOIN  adm_tablas_aux   t   ON(proy.t02_estado  =  t.codi) 
    LEFT  JOIN  adm_tablas_aux sec ON (proy.t02_sect_prod=sec.codi)
    LEFT  JOIN  adm_tablas_aux2 sub ON (proy.t02_subsect_prod=sub.codi)
    
    WHERE proy.t02_cod_proy = _proy 
      AND proy.t02_version  = _ver  ;   
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.1 [30-05-2014 05:22]
// Fixed: Fecha exacta de inicio y fin
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
  JOIN t02_dg_proy d ON (poa.t02_cod_proy = d.t02_cod_proy AND d.t02_version = _verProy)
  WHERE poa.t02_cod_proy = _proy 
  AND poa.t02_anio = _anio;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [30-05-2014 11:36]
// Incidencia 218: Sólo para FE
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_financ_mp_supervision`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_financ_mp_supervision`(IN _proy VARCHAR(10), 
                         IN _anio INT , 
                         IN _mes  INT ,
                         IN _cod_fte INT)
BEGIN
  DECLARE _ver  INT ;
  DECLARE _ver_anio  INT DEFAULT fn_version_proy_poa(_proy, _anio); 
  DECLARE _comp INT ;
  DECLARE _act  INT ;
  DECLARE _sub  INT ;
  DECLARE _cat  INT ;
  DECLARE _supervision DOUBLE;
  DECLARE _supervision_int INT;

  SELECT fn_ult_version_proy(_proy) INTO _ver;
  SELECT 10, 7, 0, 0 INTO _comp, _act, _sub, _cat;
  
  SELECT IFNULL(SUM(t03_monto),0), IFNULL(SUM(t03_monto),0) 
  INTO _supervision, _supervision_int 
    FROM t03_mp_gas_supervision
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver_anio;
       
  SELECT CONCAT(_comp,'.',_act ) AS codigo, 
    'Gastos de Supervisión' AS descripcion,
    _supervision_int AS gasto_tot,
    IF (_cod_fte = 10, _supervision, 0) AS total_fuente,
    IF (_cod_fte = 10, (_supervision/12), 0) AS planeado,
    (SELECT SUM(gas.t41_gasto) 
    FROM t41_inf_financ_gasto gas 
    WHERE gas.t02_cod_proy = _proy
  AND gas.t08_cod_comp   = _comp
  AND gas.t09_cod_act    = _act
  AND gas.t09_cod_sub    = _sub 
  AND gas.t41_cate_gasto = _cat
  AND gas.t41_fte_finan  = _cod_fte
  AND gas.t40_anio= _anio
  AND gas.t40_mes= _mes
    ) AS ejecutado,
     
     (SELECT MAX(gas.t41_obs) 
          FROM  t41_inf_financ_gasto gas 
         WHERE gas.t02_cod_proy= _proy
               AND gas.t08_cod_comp   = _comp
               AND gas.t09_cod_act    = _act
               AND gas.t09_cod_sub    = _sub
               AND gas.t41_cate_gasto = _cat           
               AND gas.t41_fte_finan  =_cod_fte
               AND gas.t40_anio= _anio
               AND gas.t40_mes= _mes
         ) AS observ;
END $$

DELIMITER ;