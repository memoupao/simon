/*
// -------------------------------------------------->
// AQ 2.0 [27-12-2013 12:21]
// Tablas de Trimestres a Entregables
// --------------------------------------------------<
*/
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `t25_inf_entregable`;
CREATE TABLE IF NOT EXISTS `t25_inf_entregable` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) DEFAULT NULL COMMENT 'Versión del Proyecto',
  `t25_anio` int(11) NOT NULL,
  `t25_entregable` int(11) NOT NULL,
  `t25_fch_pre` datetime DEFAULT NULL COMMENT 'Fecha de Presentación del Informe',
  `t25_periodo` varchar(50) DEFAULT NULL,
  `t25_estado` int(11) DEFAULT NULL COMMENT 'Proceso\nCerrado',
  `t25_resulta` text COMMENT 'Análisis de resultado',
  `t25_conclu` text COMMENT 'Conclusiones',
  `t25_limita` text COMMENT 'Limitaciones\n',
  `t25_fac_pos` text COMMENT 'Factores Positivos',
  `t25_perspec` text COMMENT 'Perspectivas',
  `t25_vb` char(1) DEFAULT NULL COMMENT 'VB del GP',
  `t25_vb_fch` datetime DEFAULT NULL COMMENT 'Fecha de VB',
  `t25_vb_txt` text COMMENT 'Observaciones del VB',
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  `obs_gp` text,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de Informes de Entregable - Cabecera';

DROP TABLE IF EXISTS `t25_inf_entregable_anx`;
CREATE TABLE IF NOT EXISTS `t25_inf_entregable_anx` (
  `t02_cod_proy` varchar(10) NOT NULL,
  `t02_version` int(11) DEFAULT NULL COMMENT 'Versión del Proyecto',
  `t25_anio` int(11) NOT NULL,
  `t25_entregable` int(11) NOT NULL,
  `t25_cod_anx` int(11) NOT NULL,
  `t25_nom_file` varchar(100) DEFAULT NULL COMMENT 'nombre del achivo',
  `t25_url_file` varchar(100) DEFAULT NULL COMMENT 'url del archivo',
  `t25_desc_file` TEXT DEFAULT NULL,
  `usr_crea` char(20) DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_actu` char(20) DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`,`t25_cod_anx`),
  KEY `fk_t25_inf_entregable_anx` (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla de Documentos Anexados al Informe de Entregable';

ALTER TABLE `t25_inf_entregable_anx`
  ADD CONSTRAINT `t25_inf_entregable_anx_ibfk` FOREIGN KEY (`t02_cod_proy`,`t02_version`, `t25_anio`, `t25_entregable`) REFERENCES `t25_inf_entregable` (`t02_cod_proy`, `t02_version`, `t25_anio`, `t25_entregable`) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE IF EXISTS `t25_inf_entregable_at`;
CREATE TABLE IF NOT EXISTS `t25_inf_entregable_at` (
  `t02_cod_proy` varchar(10) CHARACTER SET utf8 NOT NULL,
  `t02_version` int(11) DEFAULT NULL COMMENT 'Versión del Proyecto',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_sub` int(11) NOT NULL,
  `t25_anio` int(11) NOT NULL COMMENT 'Año',
  `t25_entregable` int(11) NOT NULL COMMENT 'Entregable',
  `t11_cod_bene` int(11) NOT NULL COMMENT 'Código del Beneficiario',
  `t15_avance` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'P:En Proceso, C: Capacitado, R: Retirado',
  `fch_crea` datetime DEFAULT NULL,
  `usr_crea` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `usr_actu` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_sub`,`t11_cod_bene`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `t25_inf_entregable_capac`;
CREATE TABLE IF NOT EXISTS `t25_inf_entregable_capac` (
  `t02_cod_proy` varchar(10) CHARACTER SET utf8 NOT NULL,
  `t02_version` int(11) DEFAULT NULL COMMENT 'Versión del Proyecto',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_sub` int(11) NOT NULL DEFAULT '0',
  `t12_cod_tema` int(11) NOT NULL COMMENT 'Código del Tema Específico de Capacitación',
  `t25_anio` int(11) NOT NULL COMMENT 'Año',
  `t25_entregable` int(11) NOT NULL COMMENT 'Entregable',
  `t11_cod_bene` int(11) NOT NULL COMMENT 'Código del Beneficiario',
  `t15_avance` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'P:En Proceso, C: Capacitado, R: Retirado',
  `fch_crea` datetime DEFAULT NULL,
  `usr_crea` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `usr_actu` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_sub`,`t12_cod_tema`,`t11_cod_bene`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `t25_inf_entregable_cred`;
CREATE TABLE IF NOT EXISTS `t25_inf_entregable_cred` (
  `t02_cod_proy` varchar(10) CHARACTER SET utf8 NOT NULL,
  `t02_version` int(11) DEFAULT NULL COMMENT 'Versión del Proyecto',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_sub` int(11) NOT NULL,
  `t25_anio` int(11) NOT NULL COMMENT 'Año',
  `t25_entregable` int(11) NOT NULL COMMENT 'Entregable',
  `t11_cod_bene` int(11) NOT NULL COMMENT 'Código del Beneficiario',
  `t15_avance` double DEFAULT NULL COMMENT 'Monto de Crédito',
  `fch_crea` datetime DEFAULT NULL,
  `usr_crea` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `usr_actu` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_sub`,`t11_cod_bene`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `t25_inf_entregable_otros`;
CREATE TABLE IF NOT EXISTS `t25_inf_entregable_otros` (
  `t02_cod_proy` varchar(10) CHARACTER SET utf8 NOT NULL,
  `t02_version` int(11) DEFAULT NULL COMMENT 'Versión del Proyecto',
  `t08_cod_comp` int(11) NOT NULL,
  `t09_cod_act` int(11) NOT NULL,
  `t09_cod_sub` int(11) NOT NULL,
  `t25_anio` int(11) NOT NULL COMMENT 'Año',
  `t25_entregable` int(11) NOT NULL COMMENT 'Entregable',
  `t11_cod_bene` int(11) NOT NULL COMMENT 'Código del Beneficiario',
  `t15_avance` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `t15_valor` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fch_crea` datetime DEFAULT NULL,
  `usr_crea` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `usr_actu` char(20) CHARACTER SET utf8 DEFAULT NULL,
  `fch_actu` datetime DEFAULT NULL,
  `est_audi` char(1) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`t02_cod_proy`,`t02_version`,`t25_anio`,`t25_entregable`,`t08_cod_comp`,`t09_cod_act`,`t09_cod_sub`,`t11_cod_bene`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
// -------------------------------------------------->
// AQ 2.0 [27-12-2013 13:44]
// Obtiene el último informe de Entregable
// --------------------------------------------------<
*/

DROP FUNCTION IF EXISTS `fn_nom_periodo_entregable`;

DELIMITER $$

CREATE FUNCTION `fn_nom_periodo_entregable`(_proy VARCHAR(10), _anio INT, _mes INT) RETURNS varchar(50) CHARSET utf8 
    DETERMINISTIC
BEGIN
    DECLARE _ver INT DEFAULT fn_ult_version_proy(_proy);
    DECLARE _fecini DATE;
	DECLARE _return VARCHAR(50);
	DECLARE _num_entregables_ant INT DEFAULT 0;
	DECLARE _entregable_ant INT DEFAULT 0;
	DECLARE _anio_ini INT DEFAULT 0;
	DECLARE _anio_fin INT DEFAULT 0;
	DECLARE _mes_ini INT DEFAULT 0;
    DECLARE _mes_fin INT DEFAULT 0;
	
	SELECT fn_fecha_inicio_proy(_proy, _ver)
    INTO _fecini;
    
    SET _anio_ini = YEAR(_fecini);
    SET _anio_fin = _anio_ini + (_anio - 1);
    
    SELECT COUNT(*) 
    INTO _num_entregables_ant
	FROM t02_entregable
	WHERE t02_cod_proy = _proy 
	AND t02_version = _ver
	AND t02_anio = _anio
	AND t02_mes < _mes;
	
	IF (_num_entregables_ant = 0) THEN
        SET _mes_ini = MONTH(_fecini);
        SET _mes_fin = _mes_ini + (_mes - 1);
    	SET _return = CONCAT(fn_nom_mes(_mes_ini), ' ', _anio_ini, ' - ', fn_nom_mes(_mes_fin), ' ', _anio_fin);
	ELSE
        SELECT MAX(t02_mes)
		INTO _entregable_ant
		FROM t02_entregable
		WHERE t02_cod_proy = _proy 
		AND t02_version = _ver
		AND t02_anio = _anio
		AND t02_mes < _mes
		LIMIT 1;
		
		SET _mes_ini = MONTH(_fecini) + _entregable_ant;
        SET _mes_fin = _mes_ini + (_mes - 1);
		
        SET _return = CONCAT(fn_nom_mes(_mes_ini), ' ', _anio_ini, ' - ', fn_nom_mes(_mes_fin), ' ', _anio_fin);
	END IF;
 
    RETURN _return;
END $$
DELIMITER ;

SET FOREIGN_KEY_CHECKS=1;