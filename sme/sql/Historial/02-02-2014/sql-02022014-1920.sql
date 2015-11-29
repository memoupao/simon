/*
// -------------------------------------------------->
// AQ 2.0 [02-02-2014 20:01]
// Registro de Indicadores de Componente del Informe de Entregable
// Mostrar Total del Indicador del Componente
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable_ind_comp`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable_ind_comp`(IN _proy VARCHAR(10), 
                IN _ver INT,
                IN _comp INT,
                IN _anio INT, 
                IN _entregable INT)
BEGIN
    
    /* Seleccionamos Los Indicadores de Componente y aquellos indicadores que ya fueron informados*/
    SELECT  ind.t08_cod_comp_ind,
        ind.t08_ind AS indicador,
        ind.t08_um,
        /*ind.t08_mta AS plan_mtaTotal,*/
        IFNULL((SELECT a.t08_mta FROM t08_comp_ind a WHERE ind.t02_cod_proy=a.t02_cod_proy
                                            AND a.t02_version=1
                                            AND ind.t08_cod_comp=a.t08_cod_comp
                                            AND ind.t08_cod_comp_ind = a.t08_cod_comp_ind), 0) AS plan_mtaTotal,
        SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t08_ind_anio, inf.t08_ind_entregable) < fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _anio, _entregable)  THEN inf.t08_ind_avanc ELSE 0  END) AS ejec_mtaAcum,
        SUM(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable = _entregable  THEN inf.t08_ind_avanc ELSE 0  END) AS ejec_mtaEntregable,
        SUM(CASE WHEN fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, inf.t08_ind_anio, inf.t08_ind_entregable) <= fn_numero_entregable(inf.t02_cod_proy, inf.t02_version, _anio, _entregable) THEN IFNULL(inf.t08_ind_avanc, 0) ELSE 0  END) AS ejec_mtaTotal,
        MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_descrip ELSE NULL END) AS descripcion,
        MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_logros  ELSE NULL END) AS logros,
        MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_dificul ELSE NULL END) AS dificultades,
        MAX(CASE WHEN inf.t08_ind_anio=_anio AND inf.t08_ind_entregable=_entregable THEN inf.t08_obs ELSE NULL END) AS observaciones
    FROM       t08_comp_ind ind  
    LEFT JOIN  t08_comp_ind_inf inf ON(ind.t02_cod_proy=inf.t02_cod_proy AND ind.t08_cod_comp=inf.t08_cod_comp AND ind.t08_cod_comp_ind=inf.t08_cod_comp_ind AND inf.t08_ind_anio<=_anio)
    WHERE  ind.t02_cod_proy=_proy
      AND  ind.t02_version=_ver
      AND  ind.t08_cod_comp=_comp
    GROUP BY ind.t08_cod_comp_ind,
         ind.t08_ind,
         ind.t08_um,
         ind.t08_mta;
END $$

DELIMITER ;