/*
// -------------------------------------------------------------------------->
// AQ 2.1 [06-05-2014 09:18]
// Incidencia #169
// --------------------------------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_sub_act`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_sub_act`(
        IN _proy VARCHAR(10), 
        IN _comp INT, 
        IN _activ INT, 
        IN _anio INT, 
        IN _mes INT)
BEGIN
DECLARE _ver INT;
DECLARE _ver_anio  INT ;

SELECT fn_ult_version_proy(_proy) INTO _ver ;
SELECT fn_version_proy_poa(_proy, _anio) INTO _ver_anio ;

SELECT  sub.t08_cod_comp,
  com.t08_comp_desc AS componente,
  sub.t09_cod_act,
  act.t09_act AS actividad,
  sub.t09_cod_sub,
  sub.t09_sub AS subactividad,
  sub.t09_um,
  
  -- sub.t09_mta_repro AS plan_mtatotal,
  (SELECT fn_get_mta_reprog(sub.t02_cod_proy, _ver_anio, sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub, _anio, TRUE)) AS plan_mtatotal,
  sub.t09_mta AS plan_mtaanio,
  
  
  SUM(CASE WHEN fn_numero_mes(mta.t09_anio,mta.t09_mes) < fn_numero_mes(_anio,_mes) THEN mta.t09_mta ELSE 0 END) AS plan_mtaacum,
  SUM(CASE WHEN mta.t09_anio=_anio AND mta.t09_mes=_mes THEN mta.t09_mta ELSE 0 END) AS plan_mtames,
  IFNULL((SELECT SUM(inf2.t09_sub_avanc) 
   FROM t09_act_sub_mtas_inf inf2 
   WHERE inf2.t02_cod_proy=sub.t02_cod_proy 
     AND inf2.t08_cod_comp=sub.t08_cod_comp  
     AND inf2.t09_cod_act=sub.t09_cod_act 
     AND inf2.t09_cod_sub=sub.t09_cod_sub 
     AND fn_numero_mes(inf2.t09_sub_anio, inf2.t09_sub_mes) < fn_numero_mes(_anio,_mes)
     
   ),0) AS ejec_mtaacum,
   MAX(CASE WHEN inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes THEN inf.t09_sub_avanc ELSE NULL END) AS ejec_mtames,
   IFNULL((SELECT SUM(inf2.t09_sub_avanc) 
   FROM t09_act_sub_mtas_inf inf2 
   WHERE inf2.t02_cod_proy=sub.t02_cod_proy 
     AND inf2.t08_cod_comp=sub.t08_cod_comp  
     AND inf2.t09_cod_act=sub.t09_cod_act 
     AND inf2.t09_cod_sub=sub.t09_cod_sub 
     AND fn_numero_mes(inf2.t09_sub_anio, inf2.t09_sub_mes) <= fn_numero_mes(_anio,_mes)
     
   ),0) AS ejec_mtatotal,
   MAX(CASE WHEN inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes THEN inf.t09_descrip ELSE NULL END) AS descripcion,
   MAX(CASE WHEN inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes THEN inf.t09_logros  ELSE NULL END) AS logros,
   MAX(CASE WHEN inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes THEN inf.t09_dificul ELSE NULL END) AS dificultades,
   MAX(CASE WHEN inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes THEN inf.t09_omt ELSE NULL END) AS omt
FROM       t09_subact  sub
INNER JOIN t08_comp    com  ON(sub.t02_cod_proy=com.t02_cod_proy AND sub.t02_version=com.t02_version  AND sub.t08_cod_comp=com.t08_cod_comp )
INNER JOIN t09_act     act  ON(sub.t02_cod_proy=act.t02_cod_proy AND sub.t02_version=act.t02_version  AND sub.t08_cod_comp=act.t08_cod_comp AND sub.t09_cod_act=act.t09_cod_act)
LEFT  JOIN t09_sub_act_mtas mta ON(sub.t02_cod_proy=mta.t02_cod_proy AND sub.t02_version=mta.t02_version  AND sub.t08_cod_comp=mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub=mta.t09_cod_sub AND mta.t09_anio<=_anio)
LEFT  JOIN t09_act_sub_mtas_inf inf ON(sub.t02_cod_proy=inf.t02_cod_proy AND sub.t08_cod_comp=inf.t08_cod_comp  AND sub.t09_cod_act=inf.t09_cod_act AND sub.t09_cod_sub=inf.t09_cod_sub AND inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes)
WHERE sub.t02_cod_proy=_proy
  AND sub.t08_cod_comp=_comp
  AND sub.t09_cod_act=_activ
  AND sub.t02_version = _ver_anio 
GROUP BY sub.t08_cod_comp, com.t08_comp_desc, sub.t09_cod_act, act.t09_act ,
   sub.t09_cod_sub, sub.t09_sub , sub.t09_um ;
END $$
DELIMITER ;