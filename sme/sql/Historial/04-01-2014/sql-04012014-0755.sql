/*
// -------------------------------------------------->
// AQ 2.0 [04-01-2014 07:55]
// Eliminación de Entregable 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_del_inf_trim_cab`;
DROP PROCEDURE IF EXISTS `sp_del_inf_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_del_inf_entregable`(
            IN _proy VARCHAR(10),
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT)
BEGIN
    DECLARE _numrows INT;
    
    DELETE FROM t25_inf_entregable 
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
  
    SELECT ROW_COUNT() INTO _numrows ;  
    
    DELETE FROM t25_inf_entregable_at 
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable
    AND t02_version = _ver;
    
    DELETE FROM t25_inf_entregable_capac
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable
    AND t02_version = _ver;
    
    DELETE FROM t25_inf_entregable_cred  
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable
    AND t02_version = _ver;
    
    DELETE FROM t25_inf_entregable_otros  
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable
    AND t02_version = _ver;
    
    DELETE FROM t07_prop_ind_inf  
    WHERE t02_cod_proy = _proy 
    AND t07_ind_anio = _anio 
    AND t07_ind_entregable = _entregable
    AND t02_version = _ver;
    
    DELETE FROM t08_comp_ind_inf  
    WHERE t02_cod_proy = _proy 
    AND t08_ind_anio = _anio 
    AND t08_ind_entregable = _entregable
    AND t02_version = _ver;
    
    DELETE FROM t09_entregable_ind_inf  
    WHERE t02_cod_proy = _proy 
    AND t09_ind_anio = _anio 
    AND t09_ind_entregable = _entregable
    AND t02_version = _ver;
	  
	SELECT _numrows AS numrows, _ver AS codigo;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [04-01-2014 08:25]
// Lista Anexos del Informe de Entregable 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_sel_inf_anx_inf_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_sel_inf_anx_inf_entregable`(
                IN _proy VARCHAR(10), 
                IN _ver INT,
                IN _anio INT, 
                IN _entregable INT)
BEGIN
    SELECT 
	    t25_cod_anx,
	    t25_nom_file,
	    t25_url_file,
	    t25_desc_file
    FROM t25_inf_entregable_anx
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [04-01-2014 09:03]
// Registra Anexo del Informe de Entregable 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_ins_inf_anx_foto_trim`;
DROP PROCEDURE IF EXISTS `sp_ins_inf_anx_inf_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_ins_inf_anx_inf_entregable`(
                IN _proy VARCHAR(10), 
                IN _ver INT,
                IN _anio INT, 
                IN _entregable INT,
                IN _nom VARCHAR(100),
                IN _desc TEXT,
                IN _ext VARCHAR(5),
                IN _usr VARCHAR(20))
BEGIN
    DECLARE _url VARCHAR(50);
    DECLARE _id INT;
    
    SELECT IFNULL(max(t25_cod_anx),0)+1
    INTO _id
    FROM t25_inf_entregable_anx
    WHERE t02_cod_proy = _proy 
    AND  t25_anio =_anio 
    AND  t25_entregable = _entregable 
    AND  t02_version =_ver;
    
    SELECT CONCAT(_proy,'_',_anio,'_',_entregable,'_',_ver,'_',_id,'.', _ext) INTO _url;
    
    INSERT INTO t25_inf_entregable_anx 
    (t02_cod_proy,
    t02_version,
    t25_anio, 
    t25_entregable, 
    t25_cod_anx, 
    t25_nom_file, 
    t25_url_file, 
    t25_desc_file, 
    usr_crea, 
    fch_crea
    )
    VALUES
    ( _proy,
      _ver, 
      _anio, 
      _entregable, 
      _id, 
      _nom, 
      _url, 
      _desc, 
      _usr, 
      now()
    );
    
    SELECT ROW_COUNT() AS numrows, _id AS codigo, _url AS url; 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [04-01-2014 10:16]
// Eliminación de Anexo del Informe de Entregable 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_del_inf_anx_foto_trim`;
DROP PROCEDURE IF EXISTS `sp_del_anx_inf_entregable`;

DELIMITER $$

CREATE PROCEDURE `sp_del_anx_inf_entregable`(
            IN _proy varchar(10),
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _codanx INT)
BEGIN
    DECLARE _url varchar(100);
  
    SELECT t25_url_file into _url
    FROM t25_inf_entregable_anx
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver
    AND t25_cod_anx = _codanx ;
    
    DELETE FROM t25_inf_entregable_anx
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver
    AND t25_cod_anx = _codanx;
    
    SELECT ROW_COUNT() AS numrows, _url AS url; 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [04-01-2014 11:34]
// Actualiza Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_trim_cab`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_cab`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_cab`(IN _proy varchar(10), 
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _anio_new INT, 
            IN _entregable_new INT, 
            IN _periodo VARCHAR(50), 
            IN _fchpres DATETIME, 
            IN _estado INT,
            IN _usr VARCHAR(20),
            IN _obs_gp TEXT)
BEGIN
    UPDATE t25_inf_entregable 
    SET t25_anio = _anio_new, 
	    t25_entregable  = _entregable_new, 
	    t25_fch_pre = _fchpres, 
	    t25_periodo = _periodo, 
	    t25_estado  = _estado, 
	    usr_actu = _usr, 
	    fch_actu = now(),
	    obs_gp = _obs_gp
    WHERE t02_cod_proy = _proy 
	AND t25_anio = _anio 
	AND t25_entregable = _entregable 
	AND t02_version = _ver;
	
	SELECT ROW_COUNT() as numrows, _ver as codigo;
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [04-01-2014 12:05]
// Cambia de Estado a Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_trim_cambio_estado`;
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_cambio_estado`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_cambio_estado`(
            IN _proy VARCHAR(10),
            IN _ver INT,
            IN _anio INT,
            IN _entregable INT, 
            IN _estado INT,
            IN _obs_gp TEXT)
BEGIN
 UPDATE t25_inf_entregable 
  SET   t25_estado  = _estado,
        t25_fch_pre = (CASE WHEN _estado=46 THEN NOW() ELSE t25_fch_pre END),
        obs_gp = _obs_gp
  WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
     
  SELECT ROW_COUNT() AS numrows , '' AS msg;
     
END $$

DELIMITER ;