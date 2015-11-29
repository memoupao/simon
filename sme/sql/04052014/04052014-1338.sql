-- --------------------------------------------------------------------------------
-- DA, V2.1
-- RF-013 Exportable del Informe Mensual Técnico
-- --------------------------------------------------------------------------------


DROP procedure IF EXISTS `sp_sel_inf_sub_act_meses`;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sel_inf_sub_act_meses`(
				IN _proy VARCHAR(10), 
				IN _comp INT, 
				IN _activ INT, 
				IN _subactiv INT, 
				IN _anio INT, 
				IN _mes INT)
BEGIN
DECLARE _ver INT;
DECLARE _ver_anio  INT ;

SELECT fn_ult_version_proy(_proy) INTO _ver ;
SELECT fn_version_proy_poa(_proy, _anio) INTO _ver_anio ;

SELECT 	sub.t08_cod_comp,
	
	sub.t09_cod_act,
	sub.t09_cod_sub,
	sub.t09_sub AS subactividad,

		
	SUM(CASE WHEN mta.t09_anio=_anio AND mta.t09_mes=_mes THEN mta.t09_mta ELSE 0 END) AS plan_mtames,
	MAX(CASE WHEN inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes THEN inf.t09_sub_avanc ELSE NULL END) AS ejec_mtames

	 
FROM  	   t09_subact  sub
LEFT  JOIN t09_sub_act_mtas mta ON(sub.t02_cod_proy=mta.t02_cod_proy AND sub.t02_version=mta.t02_version  AND sub.t08_cod_comp=mta.t08_cod_comp AND sub.t09_cod_act = mta.t09_cod_act AND sub.t09_cod_sub=mta.t09_cod_sub AND mta.t09_anio<=_anio)
LEFT  JOIN t09_act_sub_mtas_inf inf ON(sub.t02_cod_proy=inf.t02_cod_proy AND sub.t08_cod_comp=inf.t08_cod_comp  AND sub.t09_cod_act=inf.t09_cod_act AND sub.t09_cod_sub=inf.t09_cod_sub AND inf.t09_sub_anio=_anio AND inf.t09_sub_mes=_mes)
WHERE sub.t02_cod_proy=_proy
  AND sub.t08_cod_comp=_comp
  AND sub.t09_cod_act=_activ
  AND sub.t02_version = _ver_anio 
  AND sub.t09_cod_sub = _subactiv  
GROUP BY sub.t08_cod_comp, sub.t09_cod_act;
	 
END$$

DELIMITER ;







