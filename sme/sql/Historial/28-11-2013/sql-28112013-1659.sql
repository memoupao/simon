/*
// -------------------------------------------------->
// AQ 2.0 [28-11-2013 16:59]
// Aprobación del Cronograma de Productos
// por Entregable
// --------------------------------------------------<
*/
ALTER TABLE `t02_aprob_proy` ADD `t02_aprob_croprod` TINYINT NULL DEFAULT NULL COMMENT 'Aprueba Cronograma de Productos',
ADD `t02_fch_croprod` DATETIME NULL DEFAULT NULL ,
ADD `t02_aprob_croprod_mon` TINYINT NULL DEFAULT NULL ,
ADD `t02_obs_croprod` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Observaciones con respecto a la Aprobación del Cronograma de Productos',
ADD `t02_fch_croprod_mon` DATETIME NULL DEFAULT NULL ,
ADD `t02_fch_env_croprod` DATETIME NULL DEFAULT NULL ,
ADD `t02_env_croprod` TINYINT NULL DEFAULT NULL;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_upd_sol_aprob`$$

CREATE PROCEDURE `sp_upd_sol_aprob`(IN _proy VARCHAR(10), 
    IN _tip VARCHAR(20), -- pueden ser ml, cron, pres 
    IN _est INT  ,  -- Opcional
    IN _msg TEXT ,
    IN _usr VARCHAR(20))
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
           SET  t02_aprob_ml = _est ,
            t02_fch_ml   = NOW(),
            t02_aprob_ml_mon = NULL,
            t02_obs_ml  = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_ml, t02_fch_ml, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Marco Lógico',
                        CONCAT('El Marco Lógico del proyecto ', _proy, ', ha sido aprobado, para continuar con el ingreso del Cronograma de Actividades. \n\n', _msg,  '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/planifica/ml_index.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
    
END IF ;
IF _tip = 'cron' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_cro = _est ,
            t02_fch_cro   = NOW(),
            t02_aprob_cro_mon = NULL,
            t02_obs_cro = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_cro, t02_fch_cro, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Cronograma de Actividades',
                        CONCAT('El proyecto ', _proy, 
                                ' Cuenta con el VoBo del cronograma de actividades, ',
                                'puede continuar con el registro del presupuesto inicial. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF ;
IF _tip = 'pre' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_pre = _est ,
            t02_fch_pre   = NOW(),
            t02_aprob_pre_mon = NULL,
            t02_obs_pre = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_pre, t02_fch_pre, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Presupuesto',
                        CONCAT('El proyecto ', _proy, 
                        ' cuenta con el VoBo del presupuesto inicial, ',
                        'proceda a registrar Equipo Líder, Sector Productivo, Ambito Geográfico, ',
                        'Planes Específicos y Padrón de Beneficiarios, éste último debe ser revisado periodicamente. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF ;
IF _tip = 'proy' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_proy = _est ,
            t02_fch_aprob_proy  = NOW(),
            t02_obs_aprob_proy  = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_proy, t02_fch_aprob_proy, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Aprobación del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha sido aprobado. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr, 4
                         ) ;
    END IF ;
END IF ;
IF _tip = 'vbpy' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_vb_proy = _est ,
            t02_fch_vb_proy  = NOW(),
            t02_obs_vb_proy = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_vb_proy, t02_fch_vb_proy, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Solicitud Aprobación del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha solicitado su Aprobación para continuar con el ingreso del Marco Lógico. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr, 3
                         ) ;
    END IF ;
END IF;
IF _tip = 'evml' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_ml = _est ,
            t02_fch_env_ml = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_ml, t02_fch_env_ml, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_mt(  _proy, 
                        'Solicitud Revisión Marco Lógico',
                        CONCAT('El proyecto ', _proy, ', ha solicitado la Revisión del Marco Lógico. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/planifica/ml_index.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'evpr' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_pre = _est ,
            t02_fch_env_pre = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_pre, t02_fch_env_pre, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_mf(  _proy, 
                        'Solicitud Revisión del Presupuesto',
                        CONCAT('El proyecto ', _proy, ', solicita la revisión del presupuesto inicial. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'evel' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_el = _est ,
            t02_fch_env_el = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_el, t02_fch_env_el, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_fe(  _proy, 
                        'Solicitud Revisión del Equipo Lider',
                        CONCAT('El proyecto ', _proy, ', ha solicitado su Revisión del Equipo Lider \n\n', _msg ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'evcr' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_cro = _est ,
            t02_fch_env_cro = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_cro, t02_fch_env_cro, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_mt(  _proy, 
                        'Solicitud Revisión del Cronograma de Actividades',
                        CONCAT('El proyecto ', _proy, ', solicita la Revisión del Cronograma de Actividades. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         ) ;
    END IF ;
END IF;
IF _tip = 'scp' THEN
SET _rowcount =1;
    UPDATE t04_sol_cambio_per 
           SET  t04_aprob_cmf = 1, 
                t04_aprob_cmt = 1 
         WHERE t02_cod_proy = _proy ;
    SET _rowcount = ROW_COUNT() ;
    IF _rowcount >0 THEN
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Solicitud de Cambio de Personal',
                        CONCAT('La solicitud de cambio del personal del proyecto ', _proy, ', ha sido aprobada \n\n', _msg ),
                        _nomejec,
                        _usr
                         ) ;
        END IF; 
END IF;
IF _tip = 'srdg' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_dg_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_dg_proy 
           SET  env_rev = _est 
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    
    END IF;
    
    IF _rowcount >0 THEN -- Si todo es OK , procedemos a dejar el mensaje(El Demonio cada 5 min envia los mensajes)
        CALL sp_ins_mensaje_proy_a_personalizado(   _proy, 
                        'Solicitud de Revisión de Datos Generales del Proyecto',
                        CONCAT('El proyecto ', _proy, ', ha solicitado la revisión de sus Datos Generales. \n\n', _msg, '\n\nPuede revisar el proyecto haciendo <a href="http://localhost/sgp/sme/proyectos/datos/lista.php?txtCodProy=',_proy,'"> click Aquí </a>' ),
                        _nomejec,
                        _usr,
                        1
                         ) ;
    END IF ;
END IF;
/*
// -------------------------------------------------->
// AQ 2.0 [28-11-2013 16:59]
// Envío a Revisón y Aprobación de Cronograma de Productos
*/
IF _tip = 'evcrprod' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_env_croprod = _est ,
            t02_fch_env_croprod = NOW()
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_env_croprod, t02_fch_env_croprod, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN
        CALL sp_ins_mensaje_proy_a_mt(  _proy, 
                        'Solicitud Revisión del Cronograma de Productos',
                        CONCAT('El proyecto ', _proy, ', solicita la Revisión del Cronograma de Productos. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         );
    END IF ;
END IF;

IF _tip = 'cronprod' THEN
    IF EXISTS(SELECT t02_cod_proy FROM t02_aprob_proy WHERE t02_cod_proy = _proy) THEN
        UPDATE t02_aprob_proy 
           SET  t02_aprob_croprod = _est ,
            t02_fch_croprod   = NOW(),
            t02_aprob_croprod_mon = NULL,
            t02_obs_croprod = ''
         WHERE t02_cod_proy = _proy ;
         SET _rowcount = ROW_COUNT() ;
    ELSE
        INSERT INTO t02_aprob_proy(t02_cod_proy, t02_aprob_croprod, t02_fch_croprod, usu_crea, fch_crea)
                VALUES(_proy, _est, NOW(), _usr, NOW() );
        SET _rowcount = ROW_COUNT() ;
    END IF;
    
    IF _rowcount >0 THEN
        CALL sp_ins_mensaje_proy_a_ejecutor(    _proy, 
                        'Aprobación del Cronograma de Actividades',
                        CONCAT('El proyecto ', _proy, 
                                ' Cuenta con el VoBo del cronograma de actividades, ',
                                'puede continuar con el registro del presupuesto inicial. \n\n', _msg, '\n' ),
                        _nomejec,
                        _usr
                         );
    END IF ;
END IF ;
/* --------------------------------------------------< */

SELECT _rowcount AS numrows, _proy AS codigo ;  /*Retornar el numero de registros afectados */
                    
END $$

DELIMITER ;