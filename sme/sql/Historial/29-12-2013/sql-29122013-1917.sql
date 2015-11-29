/*
// -------------------------------------------------->
// AQ 2.0 [29-12-2013 19:17]
// Lista de Informes de Entregable del Proyecto
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_entregable`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN
    SELECT 
	    inf.t02_cod_proy,
	    inf.t25_anio,
	    CONCAT('Año ',inf.t25_anio) AS anio,
	    inf.t25_entregable,
	    fn_nom_mes(inf.t25_entregable + MONTH(fn_fecha_inicio_proy(_proy, _ver)) - 1) AS entregable,
	    DATE_FORMAT(inf.t25_fch_pre,'%d/%m/%Y') AS fec_pre,
	    inf.t25_periodo AS periodo,
	    (case when inf.t25_vb='1' then 'VB' else '' end) AS vb,
	    inf.t25_vb_fch AS vb_fec,
	    SUBSTRING(inf.t25_vb_txt,1,205) as aprueba_txt,
	    (case when length(inf.t25_conclu)>205 then concat(SUBSTRING(inf.t25_conclu,1,205),' ...') else inf.t25_conclu end ) AS conclusiones,
	    est.descrip AS estado
    FROM t25_inf_entregable inf
    LEFT JOIN adm_tablas_aux est ON (inf.t25_estado = est.codi)
    WHERE inf.t02_cod_proy = _proy
    AND inf.t02_version = _ver
    ORDER BY inf.t25_anio, inf.t25_entregable, t25_fch_pre;
END $$

DELIMITER ;

/*
--fn_numero_entregable(inf.t25_anio, inf.t25_entregable) as nrotrim,
--fn_porc_cump_inf_entregable(inf.t02_cod_proy, inf.t25_anio, inf.t25_entregable ) AS cump_entregable,
--fn_porc_cump_inf_mes_acum(inf.t02_cod_proy, inf.t25_anio, (inf.t25_entregable * 3) ) AS cump_acum_entregable
*/