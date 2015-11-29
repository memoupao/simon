/*
// -------------------------------------------------->
// AQ 2.0 [27-02-2014 05:59]
// Fuentes Reporte de Presupuesto Analítico
// --------------------------------------------------<
*/
DROP VIEW IF EXISTS `v_manejo_proyecto_fuentes`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_manejo_proyecto_fuentes` AS 
SELECT 
    `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,1 AS `actividad`,12 AS `codsub`,
    `fte`.`t03_id_per` AS `rubro`, 'Personal del Proyecto' AS `nomactividad`,`fte`.`t03_id_inst` AS `idinst`,
    `i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
FROM (`t03_mp_per_ftes` `fte` 
left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`))) 
union all SELECT 
    `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,2 AS `actividad`,3 AS `codsub`,
    `fte`.`t03_id_equi` AS `rubro`, 'Equipamiento del Proyecto' AS `nomactividad`,`fte`.`t03_id_inst` AS `idinst`,
    `i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
from (`t03_mp_equi_ftes` `fte` 
left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`))) 

union all select 
    `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,3 AS `actividad`,
    `p`.`cod_ext` AS `codsub`,`c`.`cod_ext` AS `rubro`, 'Gastos de Funcionamiento' AS `nomactividad`,
    `fte`.`t03_id_inst` AS `idinst`,`i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
from (((((`t03_mp_gas_fun_ftes` `fte` 
            join `t03_mp_gas_fun_cost` `cost` on(((`fte`.`t02_cod_proy` = `cost`.`t02_cod_proy`) 
                and (`fte`.`t02_version` = `cost`.`t02_version`) 
                and (`fte`.`t03_partida` = `cost`.`t03_partida`) 
                and (`fte`.`t03_id_gasto` = `cost`.`t03_id_gasto`)))) 
            join `t03_mp_gas_fun_part` `parti` on(((`cost`.`t02_cod_proy` = `parti`.`t02_cod_proy`) 
                and (`cost`.`t02_version` = `parti`.`t02_version`) 
                and (`cost`.`t03_partida` = `parti`.`t03_partida`)))) 
            left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`))) 
            left join `adm_tablas_aux` `p` on((`parti`.`t03_partida` = `p`.`codi`))) 
            left join `adm_tablas_aux` `c` on((`cost`.`t03_cat_gast` = `c`.`codi`))) 
union all select 
    `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,4 AS `actividad`,
    NULL AS `codsub`,NULL AS `rubro`, 'Gastos Administrativos' AS `nomactividad`,`fte`.`t03_id_inst` AS `idinst`,
    `i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
from (`t03_mp_gas_adm` `fte` left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`))) 
union all select 
        `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,5 AS `actividad`,
        NULL AS `codsub`,NULL AS `rubro`, 'Línea de Base' AS `nomactividad`,`fte`.`t03_id_inst` AS `idinst`,
        `i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
    from (`t03_mp_linea_base` `fte` left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`))) 
union all select 
        `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,6 AS `actividad`,
        NULL AS `codsub`,NULL AS `rubro`, 'Imprevistos' AS `nomactividad`,`fte`.`t03_id_inst` AS `idinst`,
        `i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
    from (`t03_mp_imprevistos` `fte` left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`)))
union all select 
        `fte`.`t02_cod_proy` AS `proyecto`,`fte`.`t02_version` AS `vs`,10 AS `componente`,7 AS `actividad`,
        NULL AS `codsub`,NULL AS `rubro`, 'Gastos de Supervisión' AS `nomactividad`,`fte`.`t03_id_inst` AS `idinst`,
        `i`.`t01_sig_inst` AS `siglas`,`fte`.`t03_monto` AS `aporte_fuente` 
    from (`t03_mp_gas_supervision` `fte` left join `t01_dir_inst` `i` on((`fte`.`t03_id_inst` = `i`.`t01_id_inst`)));
    
/*
// -------------------------------------------------->
// AQ 2.0 [27-02-2014 05:59]
// Fuentes Reporte de Presupuesto Analítico
// --------------------------------------------------<
*/
DROP VIEW IF EXISTS `v_manejo_proyecto_completo`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_manejo_proyecto_completo` AS 
select 
    `per`.`t02_cod_proy` AS `proyecto`,`per`.`t02_version` AS `vs`,10 AS `componente`,1 AS `actividad`,
     'Personal del Proyecto' AS `nom_actividad`,12 AS `codsub`,NULL AS `subactividad`,12 AS `cate_gasto`, 
     'Remuneraciones' AS `nom_cate_gasto`,`per`.`t03_id_per` AS `codigo`,`per`.`t03_nom_per` AS `nombre`,
     `unm`.`descrip` AS `um`,`per`.`t03_remu_prom` AS `gasto`,`per`.`t03_gasto_tot` AS `total`,
     (select sum(`mta`.`t03_mta`) AS `meta` from `t03_mp_per_metas` `mta` 
     where ((`per`.`t02_cod_proy` = `mta`.`t02_cod_proy`) 
     and (`per`.`t02_version` = `mta`.`t02_version`) 
     and (`per`.`t03_id_per` = `mta`.`t03_id_per`))) AS `meta` 
     from (`t03_mp_per` `per` 
     left join `adm_tablas_aux` `unm` on((`per`.`t03_um` = `unm`.`codi`))) 
     union all select `equi`.`t02_cod_proy` AS `proyecto`,`equi`.`t02_version` AS `vs`,10 AS `componente`,2 AS `actividad`, 
     'Equipamiento del Proyecto' AS `nom_actividad`,3 AS `codsub`,NULL AS `subactividad`,3 AS `cate_gasto`, 
     'Equipos y Bienes Duraderos' AS `nom_cate_gasto`,`equi`.`t03_id_equi` AS `codigo`,`equi`.`t03_nom_equi` AS `nombre`,
     `equi`.`t03_um` AS `um`,`equi`.`t03_costo` AS `gasto`,`equi`.`t03_costo_tot` AS `total`,(select sum(`mta`.`t03_mta`) AS `meta` 
     from `t03_mp_equi_metas` `mta` 
     where ((`equi`.`t02_cod_proy` = `mta`.`t02_cod_proy`) 
     and (`equi`.`t02_version` = `mta`.`t02_version`) 
     and (`equi`.`t03_id_equi` = `mta`.`t03_id_equi`))) AS `meta` 
     from `t03_mp_equi` `equi` 
     union all select `cost`.`t02_cod_proy` AS `proyecto`,`cost`.`t02_version` AS `vs`,10 AS `componente`,3 AS `actividad`, 
     'Gastos de Funcionamiento' AS `nom_actividad`,`p`.`cod_ext` AS `codsub`,`p`.`descrip` AS `subactividad`,`cost`.`t03_cat_gast` AS `cate_gasto`,
     `c`.`descrip` AS `nom_cate_gasto`,concat(`parti`.`t03_partida`, '.',`cost`.`t03_id_gasto`) AS `codigo`,`cost`.`t03_descrip` AS `nombre`,
     `cost`.`t03_um` AS `um`,(`cost`.`t03_cu` * `cost`.`t03_cant`) AS `gasto`,((`cost`.`t03_cu` * `cost`.`t03_cant`) * `parti`.`t03_meta`) AS `total`,
     `parti`.`t03_meta` AS `meta` 
     from (((`t03_mp_gas_fun_cost` `cost` 
     join `t03_mp_gas_fun_part` `parti` on(((`cost`.`t02_cod_proy` = `parti`.`t02_cod_proy`) 
     and (`cost`.`t02_version` = `parti`.`t02_version`) 
     and (`cost`.`t03_partida` = `parti`.`t03_partida`)))) 
     left join `adm_tablas_aux` `p` on((`parti`.`t03_partida` = `p`.`codi`))) 
     left join `adm_tablas_aux` `c` on((`cost`.`t03_cat_gast` = `c`.`codi`))) 
     union all select `adm`.`t02_cod_proy` AS `proyecto`,`adm`.`t02_version` AS `vs`,10 AS `componente`,4 AS `actividad`, 
     'Gastos Administrativos' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
     'Gastos Administrativos' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`adm`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
     sum(`adm`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
     from (`t03_mp_gas_adm` `adm` 
     left join `t02_dg_proy` `p` on(((`adm`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
     and (`adm`.`t02_version` = `p`.`t02_version`)))) 
     group by 1,2,3,4,5,6,7,8,9,10,11,12 
     union all select `lba`.`t02_cod_proy` AS `proyecto`,`lba`.`t02_version` AS `vs`,10 AS `componente`,5 AS `actividad`, 
     'Línea de Base y Evaluación de Impacto' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
     'Línea de Base y Evaluación de Impacto' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`lba`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
     sum(`lba`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
     from (`t03_mp_linea_base` `lba` 
     left join `t02_dg_proy` `p` on(((`lba`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
     and (`lba`.`t02_version` = `p`.`t02_version`)))) 
     group by 1,2,3,4,5,6,7,8,9,10,11,12 
     union all select `imp`.`t02_cod_proy` AS `proyecto`,`imp`.`t02_version` AS `vs`,10 AS `componente`,6 AS `actividad`, 
     'Imprevistos' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
     'Imprevistos' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`imp`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
     sum(`imp`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
     from (`t03_mp_imprevistos` `imp` 
     left join `t02_dg_proy` `p` on(((`imp`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
    and (`imp`.`t02_version` = `p`.`t02_version`)))) 
    group by 1,2,3,4,5,6,7,8,9,10,11,12
    union all select `sup`.`t02_cod_proy` AS `proyecto`,`sup`.`t02_version` AS `vs`,10 AS `componente`,7 AS `actividad`, 
     'Gastos de Supervisión' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
     'Gastos de Supervisión' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`sup`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
     sum(`sup`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
     from (`t03_mp_gas_supervision` `sup` 
     left join `t02_dg_proy` `p` on(((`sup`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
    and (`sup`.`t02_version` = `p`.`t02_version`)))) 
    group by 1,2,3,4,5,6,7,8,9,10,11,12;
    
/*
// -------------------------------------------------->
// AQ 2.0 [27-02-2014 05:59]
// Fuentes Reporte de Presupuesto Analítico
// --------------------------------------------------<
*/
DROP VIEW IF EXISTS `v_manejo_proyecto_reprog`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_manejo_proyecto_reprog` AS 
    select `per`.`t02_cod_proy` AS `proyecto`,`per`.`t02_version` AS `vs`,10 AS `componente`,1 AS `actividad`, 
    'Personal del Proyecto' AS `nom_actividad`,12 AS `codsub`,NULL AS `subactividad`,12 AS `cate_gasto`, 
    'Remuneraciones' AS `nom_cate_gasto`,`per`.`t03_id_per` AS `codigo`,`per`.`t03_nom_per` AS `nombre`,`unm`.`descrip` AS `um`,
    `per`.`t03_remu_prom` AS `gasto`,`per`.`t03_gasto_tot` AS `total`,`per`.`t03_mta_repro` AS `meta` 
    from (`t03_mp_per` `per` left join `adm_tablas_aux` `unm` on((`per`.`t03_um` = `unm`.`codi`))) 
    union all select `equi`.`t02_cod_proy` AS `proyecto`,`equi`.`t02_version` AS `vs`,10 AS `componente`,2 AS `actividad`, 
    'Equipamiento del Proyecto' AS `nom_actividad`,3 AS `codsub`,NULL AS `subactividad`,3 AS `cate_gasto`, 
    'Equipos y Bienes Duraderos' AS `nom_cate_gasto`,`equi`.`t03_id_equi` AS `codigo`,`equi`.`t03_nom_equi` AS `nombre`,
    `equi`.`t03_um` AS `um`,`equi`.`t03_costo` AS `gasto`,`equi`.`t03_costo_tot` AS `total`,`equi`.`t03_mta_repro` AS `meta` 
    from `t03_mp_equi` `equi` union all select `cost`.`t02_cod_proy` AS `proyecto`,`cost`.`t02_version` AS `vs`,10 AS `componente`,3 AS `actividad`, 
    'Gastos de Funcionamiento' AS `nom_actividad`,`p`.`cod_ext` AS `codsub`,`p`.`descrip` AS `subactividad`,`cost`.`t03_cat_gast` AS `cate_gasto`,
    `c`.`descrip` AS `nom_cate_gasto`,concat(`parti`.`t03_partida`, '.',`cost`.`t03_id_gasto`) AS `codigo`,`cost`.`t03_descrip` AS `nombre`,
    `cost`.`t03_um` AS `um`,(`cost`.`t03_cu` * `cost`.`t03_cant`) AS `gasto`,((`cost`.`t03_cu` * `cost`.`t03_cant`) * `parti`.`t03_mta_repro`) AS `total`,
    `parti`.`t03_mta_repro` AS `meta` 
    from (((`t03_mp_gas_fun_cost` `cost` 
    join `t03_mp_gas_fun_part` `parti` on(((`cost`.`t02_cod_proy` = `parti`.`t02_cod_proy`) 
    and (`cost`.`t02_version` = `parti`.`t02_version`) 
    and (`cost`.`t03_partida` = `parti`.`t03_partida`)))) 
    left join `adm_tablas_aux` `p` on((`parti`.`t03_partida` = `p`.`codi`))) 
    left join `adm_tablas_aux` `c` on((`cost`.`t03_cat_gast` = `c`.`codi`))) 
    union all select `adm`.`t02_cod_proy` AS `proyecto`,`adm`.`t02_version` AS `vs`,10 AS `componente`,4 AS `actividad`, 
    'Gastos Administrativos' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
    'Gastos Administrativos' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`adm`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
    sum(`adm`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
    from (`t03_mp_gas_adm` `adm` 
    left join `t02_dg_proy` `p` on(((`adm`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
    and (`adm`.`t02_version` = `p`.`t02_version`)))) 
    group by 1,2,3,4,5,6,7,8,9,10,11,12 
    union all select `lba`.`t02_cod_proy` AS `proyecto`,`lba`.`t02_version` AS `vs`,10 AS `componente`,5 AS `actividad`, 
    'Línea de Base y Evaluación de Impacto' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
    'Línea de Base y Evaluación de Impacto' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`lba`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
    sum(`lba`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
    from (`t03_mp_linea_base` `lba` 
    left join `t02_dg_proy` `p` on(((`lba`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
    and (`lba`.`t02_version` = `p`.`t02_version`)))) 
    group by 1,2,3,4,5,6,7,8,9,10,11,12 
    union all select `imp`.`t02_cod_proy` AS `proyecto`,`imp`.`t02_version` AS `vs`,10 AS `componente`,6 AS `actividad`, 
    'Imprevistos' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
    'Imprevistos' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`imp`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
    sum(`imp`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
    from (`t03_mp_imprevistos` `imp` 
    left join `t02_dg_proy` `p` on(((`imp`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
    and (`imp`.`t02_version` = `p`.`t02_version`)))) 
    group by 1,2,3,4,5,6,7,8,9,10,11,12
    union all select `sup`.`t02_cod_proy` AS `proyecto`,`sup`.`t02_version` AS `vs`,10 AS `componente`,7 AS `actividad`, 
    'Gastos de Supervisión' AS `nom_actividad`,NULL AS `codsub`,NULL AS `subactividad`,NULL AS `cate_gasto`, 
    'Gastos de Supervisión' AS `nom_cate_gasto`,0 AS `codigo`,0 AS `nombre`, 'Mes' AS `um`,(sum(`sup`.`t03_monto`) / `p`.`t02_num_mes`) AS `gasto`,
    sum(`sup`.`t03_monto`) AS `total`,`p`.`t02_num_mes` AS `meta` 
    from (`t03_mp_gas_supervision` `sup` 
    left join `t02_dg_proy` `p` on(((`sup`.`t02_cod_proy` = `p`.`t02_cod_proy`) 
    and (`sup`.`t02_version` = `p`.`t02_version`)))) 
    group by 1,2,3,4,5,6,7,8,9,10,11,12;
    
/*
// -------------------------------------------------->
// AQ 2.0 [27-02-2014 06:53]
// Costos Ejecutados
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_get_costos_ejecutados`;

DELIMITER $$
    
CREATE PROCEDURE `sp_get_costos_ejecutados`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN
    DECLARE aCont       INT;
    DECLARE aAnio       INT;
    DECLARE aTotEjec    DOUBLE;
    DECLARE aCostPers   DOUBLE;
    DECLARE aCostEquip  DOUBLE;
    DECLARE aCostFunc   DOUBLE;
    DECLARE aCostAdm    DOUBLE;
    DECLARE aCostImpr   DOUBLE;
    DECLARE aCostSup   DOUBLE;
    
    SELECT 1, 0, 0, 0, 0, 0, 0, 0
    INTO aCont, aTotEjec, aCostPers, aCostEquip, aCostFunc, aCostAdm, aCostImpr, aCostSup;
    
    WHILE aCont < _ver DO
        SELECT fn_anio_x_version_proy(_proy, aCont) INTO aAnio;
        SELECT fn_costo_componentes_anio(_proy, aAnio) + aTotEjec INTO aTotEjec;
        SELECT fn_costos_ejecutados_anio(_proy, aAnio, 1) + aCostPers INTO aCostPers;
        SELECT fn_costos_ejecutados_anio(_proy, aAnio, 2) + aCostEquip INTO aCostEquip;
        SELECT fn_costos_ejecutados_anio(_proy, aAnio, 3) + aCostFunc INTO aCostFunc;
        SELECT fn_costos_ejecutados_anio(_proy, aAnio, 4) + aCostAdm INTO aCostAdm;
        SELECT fn_costos_ejecutados_anio(_proy, aAnio, 6) + aCostImpr INTO aCostImpr;
        SELECT fn_costos_ejecutados_anio(_proy, aAnio, 7) + aCostSup INTO aCostSup;
        SET aCont = aCont + 1;
    END WHILE;
    
    SELECT  aCostPers   AS  'CostoPersonal',
            aCostEquip  AS  'CostoEquipamiento',
            aCostFunc   AS  'CostoFuncionamiento',
            aCostAdm    AS  'CostoAdministrativo',
            aCostImpr   AS  'CostoImprevisto',
            aCostSup    AS  'CostoSupervision',
            aTotEjec    AS  'TotalEjecutado';
    
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [27-02-2014 07:31]
// Línea Base
// --------------------------------------------------<
*/
DROP function IF EXISTS `fn_linea_base`;
DELIMITER $$
CREATE FUNCTION `fn_linea_base`(_proy varchar(10), _ver int) RETURNS double
    DETERMINISTIC
BEGIN
    declare _return double;
    declare _idFE int;
    declare _totalFE double ;

    declare _tasaLineaBase double ;

    SELECT t00_nom_lar INTO _tasaLineaBase FROM t00_tasa WHERE t00_cod_tasa = 2;

    select 10 into _idFE ; 
    SELECT fn_total_aporte_fuentes_financ(_proy, _ver, _idFE) into _totalFE ;
    select (_totalFE * _tasaLineaBase)/100 into _return;
    
    RETURN _return ;
    END$$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [27-02-2014 09:55]
// Línea Base
// --------------------------------------------------<
*/
DROP TRIGGER IF EXISTS trg_linea_base_update;
DROP PROCEDURE IF EXISTS `sp_linea_base_recalcular`;
DELIMITER $$
CREATE PROCEDURE `sp_linea_base_recalcular`(  
                        IN _proy VARCHAR(10), 
                        IN _ver INT,
                        IN _inst INT,
                        IN _usr VARCHAR(20))
BEGIN
    IF _ver = 1 THEN
        UPDATE t03_mp_linea_base 
        SET t03_monto = fn_linea_base_anual(_proy, _inst, t02_version - 1), usr_actu = _usr, fch_actu = NOW() 
        WHERE t02_cod_proy = _proy AND t02_version <> 1;
    END IF;
END $$
DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [05-12-2013 11:25]
// Inclusión de Gastos de Supervisión de Proyectos
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_mp_linea_base_imprevistos`;

DELIMITER $$
 
CREATE PROCEDURE `sp_upd_mp_linea_base_imprevistos`(  
                        IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _mto_lb DOUBLE, 
                        IN _mto_imp DOUBLE,
                        IN _mto_sup DOUBLE,
                        IN _usr   VARCHAR(20))
BEGIN
 declare _idFE int;
 declare _rows int ;
 select 10 into _idFE;  -- Codigo de Fondoempleo
 
    REPLACE INTO t03_mp_linea_base ( t02_cod_proy, 
                                    t02_version, 
                                    t03_id_inst, 
                                    t03_monto, 
                                    usr_crea, 
                                    fch_crea, 
                                    usr_actu, 
                                    fch_actu, 
                                    est_audi) 
                            VALUES ( _proy ,
                                    _ver, 
                                    _idFE, 
                                    _mto_lb, 
                                    _usr, 
                                    now(), 
                                    _usr, 
                                    now(), 
                                    '1'
                                    );
                                    
    CALL sp_linea_base_recalcular(_proy, _ver, _idFE, _usr);
                                    
    REPLACE INTO t03_mp_imprevistos (t02_cod_proy, 
                                    t02_version, 
                                    t03_id_inst, 
                                    t03_monto, 
                                    usr_crea, 
                                    fch_crea, 
                                    usr_actu, 
                                    fch_actu, 
                                    est_audi) 
                            VALUES ( _proy ,
                                    _ver, 
                                    _idFE, 
                                    _mto_imp, 
                                    _usr, 
                                    NOW(), 
                                    _usr, 
                                    NOW(), 
                                    '1'
                                    );
    REPLACE INTO t03_mp_gas_supervision (t02_cod_proy, 
                                    t02_version, 
                                    t03_id_inst, 
                                    t03_monto, 
                                    usr_crea, 
                                    fch_crea, 
                                    usr_actu, 
                                    fch_actu, 
                                    est_audi) 
                            VALUES( _proy ,
                                    _ver, 
                                    _idFE, 
                                    _mto_sup, 
                                    _usr, 
                                    NOW(), 
                                    _usr, 
                                    NOW(), 
                                    '1'
                                    );
                                    
    select ROW_COUNT() into _rows ;
    -- Actualizamos los totales en la Tabla de Proyectos
    call sp_upd_proyecto_costos(_proy, _ver);
                                
    SELECT _rows AS numrows, _proy AS codigo ;
         
END $$

DELIMITER ;