/**********************************************
   sql-02122013-1635.sql
 **********************************************/
/* Actualizacion de terminos de Monitor a Gestor: 
Directorio de Instituciones -> Relación de Institución con Fondoempleo:
*/

update `adm_tablas_aux` set `codi`='242',`descrip`='Gestor',`abrev`='Gestor',`cod_ext`='',`flg_act`='1',`idTabla`='29',`orden`='2',`usu_crea`='john.aima',`fec_crea`='2011-10-12',`usu_actu`=NULL,`fec_actu`=NULL where `codi`='242' and `orden`='2';



/* Nuevo proceso de registro del envio de correcciones del cronograma de productos: */

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_sol_correc`$$

CREATE PROCEDURE `sp_upd_sol_correc`(IN _proy VARCHAR(10),  IN _tip CHAR(8),  IN _est INT,  IN _msg TEXT, IN _usr VARCHAR(20))
BEGIN
DECLARE _rowcount INT DEFAULT 0 ; 
DECLARE _nomejec VARCHAR(30) ;
SELECT t01_sig_inst 
  INTO _nomejec 
  FROM t01_dir_inst 
 WHERE t01_id_inst = (SELECT p.t01_id_inst FROM t02_dg_proy p WHERE p.t02_cod_proy=_proy AND p.t02_version=1);
IF _tip = 'ml' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_ml = _est ,
            t02_obs_ml  = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_ml)
                VALUES(_proy, _est);
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN 
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Corrección del Marco Lógico',
                        CONCAT('Señores Ejecutores del proyecto ', _proy, ', su Marco Lógico ha sido observado y solicita su corrección. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/planifica/ml_index.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF ;
IF _tip = 'cron' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_cro = _est ,
            t02_obs_cro = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_cro)
                VALUES(_proy, _est);
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN 
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Corrección del Cronograma de Actividades',
                        CONCAT('El cronograma de actividades del proyecto ', _proy, 
                            ', ha sido observado, levante las observaciones y solicite nuevamente su revisión. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
            
    END IF ;
END IF ;


IF _tip = 'cronprod' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_croprod = _est ,
            t02_obs_croprod = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_croprod)
                VALUES(_proy, _est);
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN 
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Corrección del Cronograma de Productos',
                        CONCAT('El cronograma de productos del proyecto ', _proy, 
                            ', ha sido observado, levante las observaciones y solicite nuevamente su revisión. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
            
    END IF ;
END IF ;



IF _tip = 'pre' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_pre = _est ,
            t02_obs_pre = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_pre)
                VALUES(_proy, _est);
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN 
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Corrección del Presupuesto',
                        CONCAT('El presupuesto inicial del proyecto ', _proy, 
                        'ha sido observado, levante las observaciones y solicite nuevamente la revisión. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
            
    END IF ;
END IF ;
IF _tip = 'proy' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_dg_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_dg_proy 
           SET  env_rev = _est
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_dg_proy(t02_cod_proy, env_rev)
                VALUES(_proy, _est);
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN 
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Correción Datos Generales del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha solicitado la Corrección de los Datos Generales para su Aprobación.\n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr,
                        2
                         ) ;
            
    END IF ;
END IF ;
IF _tip = 'vbpr' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy
           SET  t02_vb_proy = _est
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, env_rev)
                VALUES(_proy, _est);
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN 
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Correción Datos Generales del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha solicitado la Corrección de los Datos Generales para su Aprobación\n\n', _msg ),
                        _nomejec,
                        _usr, 6
                         ) ;
            
    END IF ;
END IF ;
SELECT _rowcount AS numrows, _proy AS codigo ;  
                    
END$$

DELIMITER ;

/**********************************************
   sql-02122013-1804.sql
 **********************************************/
/* Sectores productivos: 
Nuevo campo para sectores principales: */
alter table `t02_sector_prod` add column `t02_sector_main` int(11) NOT NULL COMMENT 'Codigo del  Sector Principal' after `t02_cod_proy`,change `t02_sector` `t02_sector` int(11) NOT NULL comment 'Codigo del Sector - adm_Tablas_aux', drop primary key,  add primary key(`t02_cod_proy`, `t02_sector_main`, `t02_sector`, `t02_subsec`);

/**********************************************
   sql-02122013-1841.sql
 **********************************************/
/*
Padrón de Beneficiarios: Nuevos campos de Sectores principales:
*/
alter table `t11_bco_bene` add column `t11_sec_prod_main` int(11) NULL COMMENT 'Sector Principal' after `t11_fec_ter`, add column `t11_sec_prod_main_2` int(11) NULL COMMENT 'Sector Principal 2' after `t11_nro_up_b`, add column `t11_sec_prod_main_3` int(11) NULL COMMENT 'Sector Principal 3' after `est_audi`,change `t11_sec_prod` `t11_sec_prod` int(11) NULL , change `t11_sec_prod_2` `t11_sec_prod_2` int(11) NULL  comment 'Segundo sector productivo';
