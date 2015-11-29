/*
 DA v2:
 Cuando se selecciona el correo del IE y el correo del Usuario enlazado 
 con IE se obtienen dos correos cuando deberian de ser solo uno.
*/



DELIMITER $$

DROP TRIGGER IF EXISTS trg_ins_men$$

CREATE TRIGGER `trg_ins_men` AFTER UPDATE ON t20_inf_mes FOR EACH ROW
-- Edit trigger body code below this line. Do not edit lines above this one
BEGIN
      
    DECLARE _asunto  VARCHAR(200) DEFAULT "{asunto}";
    
    DECLARE _msg  TEXT DEFAULT "Señores {cab} del proyecto {proyecto} correspondiente al Año {anio} - Mes ({ref} ).
                Favor de Verficarlo.
                - Sistema de Evaluacion y Monitoreo.";
  
    DECLARE _id	INT;
    DECLARE _inst	VARCHAR(200); 
	DECLARE _mailproy	VARCHAR(200); 
	DECLARE _mailejec	VARCHAR(200); 	
    DECLARE _mon_tec	VARCHAR(200);
    DECLARE _mon_fin	VARCHAR(200);
    DECLARE _sistem	VARCHAR(200);    
    DECLARE _institucion VARCHAR(50);  
    DECLARE _destino	 VARCHAR(200); 
    DECLARE _est_rev 	INT DEFAULT 46;
    DECLARE _est_corr 	INT DEFAULT 47;
	DECLARE _est_vbmonitor 	INT DEFAULT 135;
	
    
    SELECT mail_admin
	INTO _sistem
	FROM 
	adm_parametros
	WHERE  idparam = 1;
   
	
   SELECT p.t02_mail_proy 
   INTO _mailproy    
    FROM 	t02_dg_proy  p
    WHERE p.t02_cod_proy=NEW.t02_cod_proy
    AND p.t02_version =fn_ult_version_proy(NEW.t02_cod_proy); 
   
    
    SELECT(SELECT i.t01_mail_inst 
	FROM t01_dir_inst i
	WHERE i.t01_id_inst=p.t01_id_inst )			
    INTO _inst    
    FROM t02_dg_proy p
    WHERE t02_cod_proy=NEW.t02_cod_proy
    AND t02_version =fn_ult_version_proy(NEW.t02_cod_proy); 
	
	 SELECT u.mail 
     INTO _mailejec    
     FROM 	adm_usuarios u
    INNER JOIN  t01_dir_inst i ON(u.t01_id_uni=i.t01_id_inst AND u.t02_cod_proy = NEW.t02_cod_proy)
		 INNER JOIN  t02_dg_proy  p ON(p.t01_id_inst=i.t01_id_inst)
    WHERE p.t02_cod_proy=NEW.t02_cod_proy
    AND p.t02_version =fn_ult_version_proy(NEW.t02_cod_proy)  LIMIT 0,1; 
	

    SELECT(SELECT e.t90_mail_equi 
	FROM t90_equi_fe e
	WHERE e.t90_id_equi=proy.t02_moni_tema ) 
    INTO _mon_tec
    FROM t02_dg_proy proy
    WHERE proy.t02_cod_proy=NEW.t02_cod_proy
    AND proy.t02_version = fn_ult_version_proy(NEW.t02_cod_proy); 
	
    
	SELECT(SELECT f.t90_mail_equi 
	FROM t90_equi_fe f
	WHERE f.t90_id_equi=proy.t02_moni_fina )  
    INTO _mon_fin
    FROM t02_dg_proy proy
    WHERE proy.t02_cod_proy=NEW.t02_cod_proy
    AND proy.t02_version = fn_ult_version_proy(NEW.t02_cod_proy);     
	
	
    SELECT MAX(i.t01_sig_inst) INTO _institucion
    FROM t01_dir_inst i
	INNER JOIN t02_dg_proy p ON ( p.t01_id_inst=i.t01_id_inst)
	WHERE p.t02_cod_proy= NEW.t02_cod_proy;
 
    
   IF NEW.t20_estado=_est_rev THEN 
		SELECT _mon_tec  INTO _destino ; 		
		SET _asunto = REPLACE(_asunto,"{asunto}","El Proyecto {proyecto} - {inst} , ha enviado a revisión su  informe técnico. ");
		SET _asunto = REPLACE(_asunto,"{proyecto}",NEW.t02_cod_proy);
		SET _asunto = REPLACE(_asunto,"{inst}",_institucion);			
		SET _msg = REPLACE(_msg,"{cab}","de Fondoempleo,
			--	La Institucion Ejecutora {inst} ha elaborado y enviado a revisión el informe técnico ");	
		SET _msg = REPLACE(_msg,"{inst}",_institucion);	
   END IF; 
	
	
    
    IF NEW.t20_estado=_est_corr THEN 	
		SET _destino =  CONCAT( 
				IFNULL(_mailproy,''),  
				IFNULL(CONCAT(', ', _mailejec ),'') 
			      ) ;
		SET _asunto = REPLACE(_asunto,"{asunto}","El informe técnico del Proyecto {proyecto} - {inst} , ha sido enviado a corrección ");
		SET _asunto = REPLACE(_asunto,"{proyecto}",NEW.t02_cod_proy);
		SET _asunto = REPLACE(_asunto,"{inst}",_institucion);
		set _msg = "Señores Ejecutores - {inst},
				El Informe Técnico del Año {anio}, mes {ref} del proyecto {proyecto}, ha sido observado, levantar las observaciones y solicitar nuevamente su revisión.
                - Sistema de Evaluacion y Monitoreo.";
		SET _msg = REPLACE(_msg,"{inst}",_institucion);	
    END IF; 
	
	
    IF NEW.t20_estado=_est_vbmonitor  THEN 	
		SET _destino =  CONCAT( 
				IFNULL(_mailproy,''),  
				IFNULL(CONCAT(', ', _mailejec ),'') 
			      ) ;
		SET _asunto = REPLACE(_asunto,"{asunto}","El informe técnico del Proyecto {proyecto} - {inst} , tiene el vb del monitor técnico ");
		SET _asunto = REPLACE(_asunto,"{proyecto}", NEW.t02_cod_proy);
		SET _asunto = REPLACE(_asunto,"{inst}",_institucion);			
		SET _msg = REPLACE(_msg,"{cab}","Ejecutores - {inst},
				Se ha dado el visto bueno al informe técnico ");
		SET _msg = REPLACE(_msg,"{inst}",_institucion);	
    END IF; 
	
	SET _msg = REPLACE(_msg,"{anio}",NEW.t20_anio);
	SET _msg = REPLACE(_msg,"{ref}",NEW.t20_mes);
	SET _msg = REPLACE(_msg,"{proyecto}",NEW.t02_cod_proy);
     
	SELECT IFNULL(MAX(id_men),0)+1
	INTO   _id
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
	(	_id, 
		_asunto, 
		_msg, 
		_inst, 
		_destino, 
		NOW(), 
		NEW.usr_actu
	);
	
END
$$
DELIMITER ;



