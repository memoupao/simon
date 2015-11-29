/*
-- DA: Nuevo reporte de Reporte Avance Técnico - Financiero
-- Date: 2014-01-16 09:21
*/

DROP procedure IF EXISTS `sp_rpt_avance_tec_finan`;

DELIMITER $$
CREATE  PROCEDURE `sp_rpt_avance_tec_finan`(						
					    IN _proyecto VARCHAR(10)
					    )
BEGIN

	select 
        `proy`.`t02_cod_proy` AS `codigo`,
        `proy`.`t02_version` AS `version`,
        `proy`.`t02_nom_proy` AS `titulo`,
        
        date_format(`proy`.`t02_fch_ter`, '%d/%m/%Y') AS `termino`,
        date_format(`proy`.`t02_fch_ire`, '%d/%m/%Y') AS `inic_real`,
        `ins`.`t01_nom_inst` AS `nominst`,
        `proy`.`t02_ben_obj` AS `beneficiarios`,
        
        
        
        if(((`proy`.`t02_fch_ire` is not null)
                and (date_format(`proy`.`t02_fch_ire`, '%d/%m/%Y') <> '00/00/0000')),
            date_format(`proy`.`t02_fch_ire`, '%d/%m/%Y'),
			date_format(`proy`.`t02_fch_ini`, '%d/%m/%Y')
            ) AS `fecha_inicio`,
        (case
            when
                (`proy`.`t02_estado` = 41)
            then
                if((ifnull(`proy`.`t02_num_mes_amp`, 0) > 0),
					date_format(`proy`.`t02_fch_tam`, '%d/%m/%Y'),
					date_format(`proy`.`t02_fch_tre`, '%d/%m/%Y')
				)
            else date_format(`proy`.`t02_fch_tre`, '%d/%m/%Y')
        end) AS `fecha_termino`
    from
        		
			`t02_dg_proy` `proy`
			join `t01_dir_inst` `ins` ON `proy`.`t01_id_inst` = `ins`.`t01_id_inst`
		
			left join `adm_tablas_aux` `est` ON `proy`.`t02_estado` = `est`.`codi`
					
		
    where
        `proy`.`t02_version` = fn_ult_version_proy(`proy`.`t02_cod_proy`) AND 
		`proy`.`t02_cod_proy` = (CASE WHEN _proyecto ='' THEN `proy`.`t02_cod_proy` ELSE _proyecto END) 
    order by `proy`.`t02_cod_proy`;

END$$

DELIMITER ;


