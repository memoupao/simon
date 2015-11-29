-- --------------------------------------------------------------------------------
-- DA v2
-- Actualizacion de terminos.
-- --------------------------------------------------------------------------------

UPDATE `pg`.`adm_parametros` SET `tit_proy`='Sistema de Gestión de Proyectos de FE' WHERE `idparam`='1';


DROP procedure IF EXISTS `sp_ins_inf_financ_cab`;
DELIMITER $$
CREATE PROCEDURE `sp_ins_inf_financ_cab`(IN _proy VARCHAR(10), 
			IN _anio INT, 
			IN _mes INT, 
			IN _fchpres DATETIME , 
			IN _periodo VARCHAR(50), 
			IN _obs  TEXT ,
			IN _estado INT,
			IN _usr VARCHAR(20),                          IN _inf_financ_ter INT)
BEGIN
  DECLARE _msg   VARCHAR(100);
  DECLARE _existe INT ;
	DECLARE _mesactual INT;
	DECLARE _mesanterior INT;
	DECLARE _anio_ant INT;
	DECLARE _mes_ant INT;
	DECLARE _aprobado INT DEFAULT 135;
	
  SELECT COUNT(1)
  INTO _existe
  FROM t40_inf_financ
  WHERE t02_cod_proy = _proy
    AND t40_anio     = _anio
    AND t40_mes      = _mes ; 
		SELECT fn_numero_mes(_anio, _mes) INTO _mesactual;
		SELECT fn_numero_mes(_anio, _mes)-1 INTO _mesanterior;
		SELECT fn_numero_mes_rev(_mesanterior, 1) INTO _anio_ant;
		SELECT fn_numero_mes_rev(_mesanterior, 2) INTO _mes_ant;
		 IF _mesactual !=1 THEN
			SELECT t40_est_eje INTO _aprobado
			FROM t40_inf_financ
			 WHERE t02_cod_proy = _proy
				AND t40_anio     = _anio_ant
				AND t40_mes      = _mes_ant ;
		END IF;
  
  IF _existe > 0 THEN
    SELECT CONCAT('El informe para el periodo Año ', _anio, ', Mes ', _mes, ' ya fue registrado')
    INTO _msg ;
    SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
	
	ELSEIF _aprobado != 135 THEN
			SELECT CONCAT('El informe para el periodo Año ', _anio_ant, ', Mes ', _mes_ant, ' todavía no tiene el visto bueno del Gestor de Proyectos.')
			INTO _msg ;
			SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
	
  ELSE
  
    INSERT INTO t40_inf_financ 
	( t02_cod_proy, 
	  t40_anio, 
	  t40_mes, 
	  t40_fch_pre, 
	  t40_periodo, 
	  t40_est_eje, 
	  t40_obs, 
	  usr_crea, 
	  fch_crea, 
	  usr_actu, 
	  fch_actu, 
	  est_audi,
	  inf_fi_ter
	)
	VALUES
	( _proy , 
	  _anio, 
	  _mes, 
	  _fchpres, 
	  _periodo, 
	  _estado, 
	  _obs, 
	  _usr, 
	  NOW(), 
	  NULL, 
	  NULL , 
	  '1',
		_inf_financ_ter
	);
     
    SELECT ROW_COUNT() AS numrows, _mes AS codigo, '' AS msg;
     
  END IF ;
END$$

DELIMITER ;







DROP procedure IF EXISTS `sp_ins_inf_mes_cab`;
DELIMITER $$
CREATE PROCEDURE `sp_ins_inf_mes_cab`(IN _proy varchar(10), 
            IN _anio INT, 
            IN _mes INT, 
            IN _periodo varchar(50), 
            IN _fchpres datetime , 
            IN _estado INT,
            IN _usr varchar(20))
BEGIN
  DECLARE _verInf   INT;
    DECLARE _msg   VARCHAR(100);
    DECLARE _mesactual INT;
    DECLARE _mesanterior INT;
    DECLARE _anio_ant INT;
    DECLARE _mes_ant INT;
    DECLARE _aprobado INT DEFAULT -1;
    declare suspYr INT;
    declare suspMt INT;
 
    SELECT fn_numero_mes(_anio, _mes) INTO _mesactual;
    SELECT fn_numero_mes(_anio, _mes)-1 INTO _mesanterior;
    SELECT fn_numero_mes_rev(_mesanterior, 1) INTO _anio_ant;
    SELECT fn_numero_mes_rev(_mesanterior, 2) INTO _mes_ant;
    IF _mesactual !=1 THEN
        SELECT t20_estado INTO _aprobado
        FROM t20_inf_mes
        WHERE t02_cod_proy = _proy
            AND t20_anio     = _anio_ant
            AND t20_mes      = _mes_ant ;
    END IF;
  
    if (_mesactual > 1) then
        SELECT t.t02_anio, IFNULL(t.diff, 12) AS duraction
        INTO suspYr, suspMt
        FROM (
            SELECT t2.t02_anio, t3.t02_fch_susp, t3.t02_fch_reinic,
                TIMESTAMPDIFF(MONTH, t3.t02_fch_susp, t3.t02_fch_reinic) + 1 AS diff
            FROM t02_dg_proy t1
            JOIN t02_poa t2 ON (t2.t02_cod_proy = t1.t02_cod_proy)
            LEFT JOIN t02_suspenciones t3 ON (t3.t02_cod_proy = t1.t02_cod_proy AND t3.t02_version = t2.t02_anio + 1)
            WHERE t1.t02_cod_proy = _proy AND t1.t02_version = 1) AS t
        WHERE t.t02_anio = _anio_ant
        ORDER BY t.t02_anio DESC
        LIMIT 1;
            
        IF (suspYr IS NOT NULL AND _mes_ant > suspMt) THEN
            SELECT 1 INTO _mesactual;
        END IF;
    end if;
    
    IF _mesactual != 1 and _aprobado != 135 THEN        
        SELECT CONCAT('El informe técnico para el periodo Año ', _anio_ant, ', Mes ', _mes_ant, ' todavía no tiene el visto bueno del Gestor de Proyectos.')
        INTO _msg ;
        SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
    ELSE   
        SELECT  IFNULL(MAX(t20_ver_inf),0)+1 INTO _verInf 
        FROM    t20_inf_mes 
        WHERE   t02_cod_proy    = _proy  
            and t20_anio = _anio
            and t20_mes  = _mes;
 
        INSERT INTO t20_inf_mes 
            (t02_cod_proy, 
            t20_anio, 
            t20_mes, 
            t20_ver_inf, 
            t20_fch_pre, 
            t20_periodo, 
            t20_obs, 
            t20_estado, 
            t02_version, 
            usr_crea, 
            fch_crea, 
            est_audi
            )
        VALUES
            (_proy, 
            _anio, 
            _mes, 
            _verInf,  
            _fchpres, 
            _periodo, 
            '', 
            _estado, 
            fn_ult_version_proy(_proy), 
            _usr, 
            NOW(), 
            '1'
            );
        select ROW_COUNT() as numrows, _verInf as codigo, '' AS msg;          
    END IF;
    
END$$

DELIMITER ;






DROP procedure IF EXISTS `sp_ins_inf_trim_cab`;
DELIMITER $$
CREATE  PROCEDURE `sp_ins_inf_trim_cab`(IN _proy VARCHAR(10), 
			IN _anio INT, 
			IN _trim INT, 
			IN _periodo VARCHAR(50), 
			IN _fchpres DATETIME , 
			IN _estado INT,
			IN _usr VARCHAR(20),   IN _obs_mt TEXT)
BEGIN
  DECLARE _verInf   INT;
	DECLARE _msg   VARCHAR(100);
	DECLARE _mesactual INT;
	DECLARE _mesanterior INT;
	DECLARE _anio_ant INT;
	DECLARE _mes_ant INT;
	DECLARE _aprobado INT DEFAULT 0;
		SELECT fn_numero_mes(_anio, (_trim*3))INTO _mesactual;
		SELECT fn_numero_mes(_anio, (_trim*3))-1 INTO _mesanterior;
		SELECT fn_numero_mes_rev(_mesanterior, 1) INTO _anio_ant;
		SELECT fn_numero_mes_rev(_mesanterior, 2) INTO _mes_ant;
		
		 IF _mesactual <> 1 THEN
			IF _mesanterior/3 = 0 THEN
				SELECT t25_estado INTO _aprobado
				FROM t25_inf_trim
			 WHERE t02_cod_proy = _proy
				AND t25_anio     = _anio_ant
				AND t25_trim      = _mes_ant/3 ;
			ELSE			
			SELECT t20_estado INTO _aprobado
			FROM t20_inf_mes
				WHERE t02_cod_proy = _proy
				AND t20_anio    = _anio_ant
				AND t20_mes     = _mes_ant 
				AND t20_ver_inf	= (SELECT	ifnull(MAX(t20_ver_inf),1)
									FROM	t20_inf_mes 
									WHERE	t02_cod_proy = _proy 
										AND t20_anio = _anio_ant 
										AND t20_mes = _mes_ant);
			
			END IF;
		ELSE
		SELECT 135 into _aprobado;
		END IF;
  
	
	IF _aprobado <> 135 THEN			
			SELECT CONCAT('El informe técnico para el periodo Año ', _anio_ant, ', Mes ', _mes_ant, ' todavía no tiene el visto bueno del Gestor de Proyectos.')
			INTO _msg ;
			SELECT 0 AS numrows, 0 AS codigo, _msg AS msg;
	ELSE
  SELECT IFNULL(MAX(t25_ver_inf),0)+1 INTO _verInf 
  FROM 	 t25_inf_trim 
  WHERE  t02_cod_proy	= _proy  
    AND  t25_anio = _anio
    AND  t25_trim  = _trim;
 
  INSERT INTO t25_inf_trim
	(t02_cod_proy, 
	t25_anio, 
	t25_trim, 
	t25_ver_inf, 
	t25_fch_pre, 
	t25_periodo,
	t25_estado,  
	t25_resulta, 
	t25_conclu,
	t25_limita,
	t25_fac_pos,
	t25_perspec,
	t02_version, 
	t25_apro_mt,
	t25_apro_fch,
	usr_crea, 
	fch_crea, 
	est_audi, 
	obs_mt
	)
	VALUES
	(_proy, 
	_anio, 
	_trim, 
	_verInf,  
	_fchpres, 
	_periodo, 
	_estado, 
	NULL,
	NULL, 
	NULL,
	NULL,
	NULL,
	fn_ult_version_proy(_proy), 
	0,
	NULL,
	_usr, 
	NOW(), 
	'1',
	_obs_mt
	);

 SELECT ROW_COUNT() AS numrows, _verInf AS codigo, _aprobado ; 
END IF;
END$$

DELIMITER ;







DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_admin`;
DELIMITER $$
CREATE PROCEDURE `sp_ins_mensaje_proy_a_admin`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20))
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
SET _mail_destino =  CONCAT(', ', fn_mail_x_proyecto(_proy,9) ) ;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte de EVASIS ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;







DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_ejecutor`;
DELIMITER $$
CREATE  PROCEDURE `sp_ins_mensaje_proy_a_ejecutor`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20))
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,1),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,0) ),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,8) ),'') 
		
			      ) ;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;




DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_fe`;
DELIMITER $$
CREATE  PROCEDURE `sp_ins_mensaje_proy_a_fe`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20))
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,2),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,1) ),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,3) ),''), 
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,5) ),'') 
		
			      ) ;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte del ejecutor del proyecto ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;






DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_financ`;
DELIMITER $$
CREATE  PROCEDURE `sp_ins_mensaje_proy_a_financ`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20), IN _tip char(4))
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
IF _tip = 'reff' THEN
SET _mail_origen= fn_mail_x_proyecto(_proy,3) ;
SET _mail_destino = fn_mail_x_proyecto(_proy,7) ;
SET _mensaje = CONCAT('Mensaje enviado automaticamente de Fondoempleo por parte del Gestor del proyecto ',_proy);
END IF;
		
IF _tip = 'ceff' THEN
SET _mail_origen= fn_mail_x_proyecto(_proy,7) ;
SET _mail_destino = fn_mail_x_proyecto(_proy,3) ;
SET _mensaje = CONCAT('Mensaje enviado automaticamente de Fondoempleo por parte del Responsable de Area del proyecto ',_proy);
END IF;
IF _tip = 'aeff' THEN
SET _mail_origen= fn_mail_x_proyecto(_proy,7) ;
SET _mail_destino = CONCAT(
						 ifnull(fn_mail_x_proyecto(_proy,3), ''), 
						 IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,6) ),''), 
						 IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,2) ),''), 
						 IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,10) ),'') 
					);
	
SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte del Responsable de Area del proyecto ',_proy);
END IF;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo');
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;







DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_mf`;
DELIMITER $$
CREATE PROCEDURE `sp_ins_mensaje_proy_a_mf`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20))
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,3),'')  
				) ;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte del ejecutor del proyecto ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;





DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_mt`;
DELIMITER $$
CREATE PROCEDURE `sp_ins_mensaje_proy_a_mt`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20))
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,2),'')  
			      ) ;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte del ejecutor del proyecto ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;








DROP procedure IF EXISTS `sp_ins_mensaje_proy_a_personalizado`;
DELIMITER $$
CREATE  PROCEDURE `sp_ins_mensaje_proy_a_personalizado`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20), IN _tipo INT)
BEGIN
DECLARE _mail_destino VARCHAR(200);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
IF _tipo=1 THEN
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,2),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,3) ),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,6) ),''), 
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,7) ),'') 
		
			      ) ;
END IF;
IF _tipo=2 THEN
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,0),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,1) ),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,8) ),'') 
		
			      ) ;
END IF;
IF _tipo=3 THEN
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,6),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,8) ),'') 
		
			      ) ;
END IF;
IF _tipo=4 THEN
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,2),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,3) ),''), 
			IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,7) ),''), 
IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,8) ),''), 
IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,0) ),''), 
IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,1) ),''), 
IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,10) ),'') 
			      ) ;
END IF;
IF _tipo=5 THEN
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,2),''),  
				IFNULL(fn_mail_x_proyecto(_proy,3),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,10) ),'') 
			      ) ;
END IF;
IF _tipo=6 THEN
SET _mail_destino =  fn_mail_x_proyecto(_proy,2);
END IF;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte del ejecutor del proyecto ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr
);
	
END$$

DELIMITER ;





DROP procedure IF EXISTS `sp_ins_mensaje_proy_scp`;
DELIMITER $$
CREATE  PROCEDURE `sp_ins_mensaje_proy_scp`(IN _proy VARCHAR(10), 
			IN _asunto VARCHAR(200) CHARSET utf8,
			IN _mensaje TEXT , 
			IN _firma   VARCHAR(100),
			IN _usr VARCHAR(20))
BEGIN
DECLARE _mail_destino VARCHAR(900);
DECLARE _mail_origen  VARCHAR(30) DEFAULT fn_mail_x_proyecto(_proy,0) ;
DECLARE _mail_admin   VARCHAR(30);
DECLARE _idmsg  BIGINT ;
SELECT mail_admin INTO _mail_admin FROM adm_parametros WHERE idparam=1 ;
IF _mail_origen = '' THEN
	SET _mail_origen = fn_mail_x_proyecto(_proy,1) ;
	IF _mail_origen = '' THEN
		SET _mail_origen = _mail_admin ;
	END IF; 
END IF; 
SET _mail_destino =  CONCAT(
				IFNULL(fn_mail_x_proyecto(_proy,2),''),  
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,3) ),''), 
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,5) ),''), 
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,6) ),''), 
				IFNULL(CONCAT(', ', fn_mail_x_proyecto(_proy,7) ),'') 
			      ) ;
IF _asunto = '' THEN 
	SET _asunto = 'Sistema de Gestión de Proyectos de Fondoempleo' ;
END IF ;  
IF _firma = '' THEN
	SET _firma = 'Sistema de Gestión de Proyectos.';
END IF ;
IF _mensaje = '' THEN
	SET _mensaje = CONCAT('Mensaje enviado automaticamente a Fondoempleo por parte del ejecutor del proyecto ',_proy);
END IF;
SET _mensaje = CONCAT(_mensaje,'<br /><br />',_firma);
SELECT IFNULL(MAX(id_men),0)+1
INTO   _idmsg
FROM adm_mensajes ;
INSERT INTO adm_mensajes 
(	id_men, 
	asu_men, 
	tex_men, 
	ori_men, 
	dest_men, 
	fec_men, 
	usu_men,
	est_men
)
VALUES
(	_idmsg, 
	_asunto, 
	_mensaje, 
	_mail_origen, 
	_mail_destino, 
	NOW(), 
	_usr,
	1
);
	
END$$

DELIMITER ;






DROP procedure IF EXISTS `sp_poa_eliminar`;
DELIMITER $$
CREATE  PROCEDURE `sp_poa_eliminar`(
IN _proy VARCHAR(10), 
IN _anio  INT)
trans1 : BEGIN
    DECLARE _ver  INT DEFAULT  fn_version_proy_poa(_proy, _anio);
    DECLARE _existe INT;
    DECLARE _rowsaffect INT;
    SELECT COUNT(1) INTO _existe 
    FROM t02_poa 
     WHERE t02_cod_proy=_proy AND t02_anio=_anio AND t02_estado=135;
    IF _existe > 0 THEN
    SELECT 0 AS numrows, CONCAT('El POA, del Año: ',_anio,', tiene el VB del Gestor de Proyectos.') AS msg;
    LEAVE trans1;
    END IF;
     SET AUTOCOMMIT=0;
     START TRANSACTION;
    
    DELETE FROM t20_inf_mes WHERE t02_cod_proy=_proy AND t20_anio = _anio;
    DELETE FROM t09_act_ind_mtas_inf WHERE t02_cod_proy=_proy AND t09_ind_anio = _anio;
    DELETE FROM t09_act_sub_mtas_inf WHERE t02_cod_proy=_proy AND t09_sub_anio = _anio;
    DELETE FROM t25_inf_trim WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_anx WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_at WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_capac WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_cred WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t25_inf_trim_otros WHERE t02_cod_proy=_proy AND t25_anio = _anio;
    DELETE FROM t08_comp_ind_inf WHERE t02_cod_proy=_proy AND t08_ind_anio = _anio;
    DELETE FROM t07_prop_ind_inf WHERE t02_cod_proy=_proy AND t07_ind_anio = _anio;
    DELETE FROM t40_inf_financ WHERE t02_cod_proy=_proy AND t40_anio = _anio;
    
    
    
    
    
    
    
    
    
    
    
    DELETE FROM t12_plan_at WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_capac WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_capac_tema WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_cred WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_cred_benef WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t12_plan_otros WHERE t02_cod_proy=_proy AND t02_version = _ver;
    
    
    DELETE FROM t09_sub_act_mtas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_sub_act_plan WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t10_cost_fte WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t10_cost_sub WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_subact WHERE t02_cod_proy=_proy AND t02_version = _ver;
     
    DELETE FROM t03_mp_per_metas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_per_ftes WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_per WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_equi_metas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_equi_ftes WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_equi WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_metas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_ftes WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_cost WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_fun_part WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_imprevistos WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_linea_base WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t03_mp_gas_adm WHERE t02_cod_proy=_proy AND t02_version = _ver;
    
    
    
    
    DELETE FROM t09_act_ind_car WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    
    DELETE FROM t09_act_ind_car_ctrl WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    DELETE FROM t09_act_ind_mtas WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_act_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t09_act WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t08_comp_sup WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t08_comp_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t08_comp WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t06_fin_sup WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t06_fin_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t07_prop_sup WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t07_prop_ind WHERE t02_cod_proy=_proy AND t02_version = _ver;
    
    DELETE FROM t03_dist_proy WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t02_proy_anx WHERE t02_cod_proy=_proy AND t02_version = _ver;
    
    DELETE FROM t02_duracion WHERE t02_cod_proy=_proy AND t02_version = _ver;
    DELETE FROM t02_proy_version WHERE t02_cod_proy=_proy AND t02_version = _ver;
    
    DELETE FROM t02_poa WHERE t02_cod_proy=_proy AND t02_anio = _anio;
    SELECT ROW_COUNT() INTO _rowsaffect;
    
    
    DELETE FROM t02_tasas_proy WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    
    DELETE FROM t02_entregable WHERE t02_cod_proy=_proy AND t02_version=_ver;
    
    
    DELETE FROM t02_dg_proy WHERE t02_cod_proy=_proy AND t02_version = _ver;
    COMMIT;
    SELECT _rowsaffect AS numrows, _anio AS codigo, '' AS msg;
END$$

DELIMITER ;




