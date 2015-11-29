/*
// -------------------------------------------------->
// AQ 2.0 [03-01-2014 11:15]
// Lista el Plan Otros 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_lis_plan_otros_trim`;
DROP PROCEDURE IF EXISTS `sp_lis_plan_otros`;

DELIMITER $$

CREATE PROCEDURE `sp_lis_plan_otros`(IN _tipo INT, IN _proy VARCHAR(10), IN _ver INT, IN _mod INT)
	BEGIN
	IF _tipo = 1 THEN  -- > Seleccionamos los Modulos
	  SELECT v.codtipo,
	     v.nomtipo,
	     COUNT(v.codigo) AS numsub
	    FROM ( 
	    SELECT  CONCAT(plan.t08_cod_comp,'.',plan.t09_cod_act,'.',plan.t09_cod_sub) AS codigo,
	        sub.t09_sub ,
	        plan.t12_nro_ent,
	        plan.t12_nro_ben,
	        plan.t12_tipo AS codtipo,
	        tip.descrip AS nomtipo
	    FROM t12_plan_otros plan 
	    LEFT  JOIN t09_subact sub ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
	    LEFT  JOIN adm_tablas_aux tip ON(plan.t12_tipo=tip.codi)
	    WHERE plan.t02_cod_proy = _proy
	      AND plan.t02_version  = _ver
	      and tip.descrip is not null
	      ) v
	    GROUP BY v.codtipo, v.nomtipo ;
	END IF ;
	IF _tipo = 2 THEN  -- > Seleccionamos las SubActividades por modulo
	  SELECT v.codigo,
	     v.t09_sub,
	     COUNT(v.codigo) AS numtema
	    FROM ( 
	    SELECT  CONCAT(plan.t08_cod_comp,'.',plan.t09_cod_act,'.',plan.t09_cod_sub) AS codigo,
	        sub.t09_sub,
	        plan.t12_nro_ent,
	        plan.t12_nro_ben,
	        plan.t12_tipo AS codtipo,
	        tip.descrip AS nomtipo
	    FROM t12_plan_otros plan 
	    LEFT JOIN t09_subact sub ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
	    LEFT JOIN adm_tablas_aux tip ON(plan.t12_tipo=tip.codi)
	    WHERE plan.t02_cod_proy = _proy
	      AND plan.t02_version  = _ver
	      AND tip.descrip IS NOT NULL
	      ) v
	     WHERE v.codtipo = _mod 
	    GROUP BY  v.codigo, v.t09_sub ;
	END IF ;
  
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [03-01-2014 11:15]
// Lista el Plan Otros 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_lis_inf_entregable_otros`;

DELIMITER $$

CREATE PROCEDURE `sp_lis_inf_entregable_otros`(IN _proy VARCHAR(10), IN _ver INT, 
  IN _anio INT, IN _entregable INT, IN _dpto CHAR(2), IN _prov CHAR(2), 
  IN _dist CHAR(2), IN _case CHAR(2))
	BEGIN
	DECLARE _sql       LONGTEXT;
	DECLARE _tmpsql    TEXT DEFAULT ' 
	SELECT  ben.t11_cod_bene, 
	    ben.t11_dni, 
	    CONCAT(ben.t11_ape_pat, \' \' ,ben.t11_ape_mat, \', \', ben.t11_nom) AS nombres,
	    sex.descrip AS sexo, 
	    ben.t11_edad, 
	    niv.descrip AS nivel, 
	    sec.descrip AS sector, 
	    ben.t11_unid_prod_1 as t11_nro_up_a, 
	    ben.t11_nro_up_b, 
	    ben.t11_nom_prod, 
	    ben.t11_direccion, 
	    ben.t11_ciudad, 
	    ben.t11_telefono, 
	    ben.t11_celular, 
	    ben.t11_mail, 
	    ben.t11_act_princ, 
	    est.descrip AS estado
	{subquerys}
	FROM      t11_bco_bene ben
	INNER JOIN t12_plan_otros plan ON (ben.t02_cod_proy = plan.t02_cod_proy AND plan.t02_version={_ver}) 
	LEFT JOIN adm_tablas_aux sex ON (ben.t11_sexo = sex.codi) 
	LEFT JOIN adm_tablas_aux niv ON (ben.t11_nivel_educ = niv.codi) 
	LEFT JOIN adm_tablas_aux sec ON (ben.t11_sec_prod = sec.codi) 
	LEFT JOIN adm_tablas_aux est ON (ben.t11_estado = est.codi) 
	WHERE 
	      ben.t02_cod_proy = \'{_proy}\'
	  AND ben.t11_dpto     = (CASE WHEN \'{_dpto}\'=\'\' THEN ben.t11_dpto ELSE \'{_dpto}\' END )
	  AND ben.t11_prov     = (CASE WHEN \'{_prov}\'=\'\' THEN ben.t11_prov ELSE \'{_prov}\' END )
	  AND ben.t11_dist     = (CASE WHEN \'{_dist}\'=\'\' OR \'{_dist}\'=\'00\'  THEN ben.t11_dist ELSE \'{_dist}\' END )
	  AND IFNULL(ben.t11_case,\'\')     = (CASE WHEN \'{_case}\'=\'\' OR \'{_case}\'=\'00\' THEN IFNULL(ben.t11_case,\'\') ELSE \'{_case}\' END )
	  
	GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17
	ORDER BY nombres ASC; ' ;
	DECLARE _tmpsubsql TEXT DEFAULT '
	     (
	      SELECT CONCAT(cap.t15_avance,\'|\', IFNULL(cap.t15_valor,\'\'))
	        FROM t25_inf_entregable_otros cap
	       WHERE cap.t02_cod_proy = \'{_proy}\'
	         AND CONCAT(cap.t08_cod_comp,\'.\',cap.t09_cod_act,\'.\',cap.t09_cod_sub) = \'{_codsub}\'
	         AND cap.t25_anio = {_anio}
	         AND cap.t25_entregable = {_entregable}
             AND cap.t02_version = {_ver}
	         AND cap.t11_cod_bene = ben.t11_cod_bene
	      ) AS \'{_codsubtema}\'' ;
	      
	DECLARE _subsql TEXT;
	DECLARE _contador  INT;
	DECLARE _codfte   INT;
	DECLARE _nomfte   VARCHAR(50);
	DECLARE _codFE    INT DEFAULT 10;
	DECLARE _escape INT DEFAULT 0;
	DECLARE _codsub CHAR(10);
	DECLARE _codtema INT;
	DECLARE _nomtema VARCHAR(150);
	DECLARE _nroentregado DOUBLE ;
	DECLARE _curtemas CURSOR 
	                 FOR 
	            SELECT  CONCAT(plan.t08_cod_comp,'.',plan.t09_cod_act,'.',plan.t09_cod_sub) AS subact,
	                sub.t09_cod_sub,
	                sub.t09_sub,
	                plan.t12_nro_ent
	            FROM t12_plan_otros plan
	            INNER JOIN t09_subact sub ON (sub.t02_cod_proy=plan.t02_cod_proy AND sub.t02_version=plan.t02_version AND sub.t08_cod_comp=plan.t08_cod_comp AND sub.t09_cod_act=plan.t09_cod_act AND sub.t09_cod_sub=plan.t09_cod_sub)
	            WHERE plan.t02_cod_proy = _proy
	              AND plan.t02_version  = _ver
	              AND plan.t12_tipo > 0
	            ORDER BY t12_tipo, subact;
	              
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET _escape = 1;
	SET _sql = '' ;
	OPEN _curtemas;
	REPEAT
	FETCH _curtemas INTO _codsub , _codtema , _nomtema , _nroentregado ;
	IF NOT _escape THEN
	    SET _subsql = REPLACE(_tmpsubsql,'{_proy}',_proy);
	    SET _subsql = REPLACE(_subsql,'{_ver}',_ver);
	    SET _subsql = REPLACE(_subsql,'{_codsub}',_codsub);
	    -- SET _subsql = REPLACE(_subsql,'{_codtema}',_codtema);
	    SET _subsql = REPLACE(_subsql,'{_anio}',_anio);
	    SET _subsql = REPLACE(_subsql,'{_entregable}',_entregable);
	    SET _subsql = REPLACE(_subsql,'{_codsubtema}', _codsub );
	    
	    SELECT CONCAT(_sql, '   ,',_subsql,'\n') INTO _sql ;
	    
	END IF;
	UNTIL _escape END REPEAT;
	CLOSE _curtemas;
	SET _sql = REPLACE(_tmpsql,'{subquerys}', _sql );
	SET _sql = REPLACE(_sql,'{_proy}', _proy);
	SET _sql = REPLACE(_sql,'{_ver}' , _ver);
	SET _sql = REPLACE(_sql,'{_dpto}', _dpto);
	SET _sql = REPLACE(_sql,'{_prov}', _prov);
	SET _sql = REPLACE(_sql,'{_dist}', _dist);
	SET _sql = REPLACE(_sql,'{_case}', _case);
	 SELECT _sql AS '_sql' ;
	SELECT _sql INTO @txtsql;
	PREPARE stmt FROM @txtsql;
	EXECUTE stmt;
	DEALLOCATE PREPARE stmt;  
 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [03-01-2014 16:55]
// Actualización de Plan de Asistencia Técnica
// en el Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_at`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_at`(  
                        IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _anio INT, 
                        IN _entregable INT,
                        IN _comp INT,
                        IN _act  INT,
                        IN _sub  INT,
                        IN _beneficiarios  TEXT, 
                        IN _calificaciones TEXT,
                        IN _user   VARCHAR(20))
BEGIN
    DECLARE _rowcount INT;
    
        CALL sp_strTempTable('TTempBenefAT',_beneficiarios,'|');
        CALL sp_strTempTable('TTempCalifAT',_calificaciones,'|');
        
    START TRANSACTION ;
        
    DELETE FROM t25_inf_entregable_at 
    WHERE t02_cod_proy = _proy  
      AND t08_cod_comp = _comp
      AND t09_cod_act = _act 
      AND t09_cod_sub = _sub 
      AND t25_anio = _anio
      AND t25_entregable = _entregable
      AND t02_version = _ver
      AND t11_cod_bene IN (SELECT b.txt FROM TTempBenefAT b INNER JOIN TTempCalifAT c ON b.id=c.id WHERE c.txt <> "");
      
    INSERT INTO t25_inf_entregable_at 
        (t02_cod_proy,
        t02_version,
        t08_cod_comp, 
        t09_cod_act, 
        t09_cod_sub, 
        t25_anio, 
        t25_entregable, 
        t11_cod_bene, 
        t15_avance, 
        fch_crea, 
        usr_crea, 
        usr_actu, 
        fch_actu, 
        est_audi
        )
    SELECT _proy,
        _ver,
        _comp,
        _act,
        _sub,
        _anio,
        _entregable  ,
        b.txt AS benef,
        c.txt AS avanc,
        NOW(),
        _user,
        NULL,
        NULL,
        '1'
    FROM TTempBenefAT b
    INNER JOIN TTempCalifAT c ON(b.id=c.id)
    WHERE c.txt <> "" ;
    
    SELECT ROW_COUNT() INTO _rowcount;
    
    COMMIT ;
    
    DROP TEMPORARY TABLE TTempBenefAT;
    DROP TEMPORARY TABLE TTempCalifAT;
    
    SELECT _rowcount AS numrows, _sub AS codigo ;
         
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [03-01-2014 17:58]
// Actualización de Plan de Otros Servicios
// en el Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_otros`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_otros`(  
                        IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _anio INT, 
                        IN _entregable INT,
                        IN _comp INT,
                        IN _act  INT,
                        IN _sub  INT,
                        IN _beneficiarios  TEXT, 
                        IN _calificaciones TEXT,
                        IN _valores        TEXT,
                        IN _user   VARCHAR(20))
BEGIN
    DECLARE _rowcount INT;
    
        CALL sp_strTempTable('TTempBenefOtros',_beneficiarios,'|');
        CALL sp_strTempTable('TTempCalifOtros',_calificaciones,'|');
        CALL sp_strTempTable('TTempValuesOtros',_valores,'|');
        
    START TRANSACTION ;
        
    DELETE FROM t25_inf_entregable_otros
    WHERE t02_cod_proy = _proy  
    AND t08_cod_comp = _comp
    AND t09_cod_act = _act 
    AND t09_cod_sub = _sub 
    AND t25_anio = _anio
    AND t25_entregable = _entregable
    AND t02_version = _ver 
    AND t11_cod_bene IN (SELECT b.txt FROM TTempBenefOtros b INNER JOIN TTempCalifOtros c ON b.id=c.id WHERE c.txt <> "");
      
    INSERT INTO t25_inf_entregable_otros 
        (t02_cod_proy,
        t02_version,
        t08_cod_comp, 
        t09_cod_act, 
        t09_cod_sub, 
        t25_anio, 
        t25_entregable, 
        t11_cod_bene, 
        t15_avance, 
        t15_valor,
        fch_crea, 
        usr_crea, 
        usr_actu, 
        fch_actu, 
        est_audi
        )
    SELECT _proy,
        _ver,
        _comp,
        _act,
        _sub,
        _anio,
        _entregable,
        b.txt AS benef,
        c.txt AS avanc,
        (case when c.txt='1' then d.txt  else null end)AS valor,
        NOW(),
        _user,
        NULL,
        NULL,
        '1'
    FROM TTempBenefOtros b
    INNER JOIN TTempCalifOtros c ON(b.id=c.id)
    INNER JOIN TTempValuesOtros d on(b.id=d.id)
    WHERE c.txt <> "" ;
    
    SELECT ROW_COUNT() INTO _rowcount;
    
    COMMIT ;
    
    DROP TEMPORARY TABLE TTempBenefOtros;
    DROP TEMPORARY TABLE TTempCalifOtros;
    DROP TEMPORARY table TTempValuesOtros;
    
    SELECT _rowcount AS numrows, _sub AS codigo ;
         
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [03-01-2014 19:28]
// Actualización de Análisis
// en el Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_analisis`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_analisis`(
            IN _proy varchar(10), 
            IN _ver INT,
            IN _anio INT, 
            IN _entregable INT, 
            IN _resul TEXT, 
            IN _concl TEXT, 
            IN _limit TEXT,
            IN _facto TEXT,
            IN _persp TEXT,
            IN _usr varchar(20))
BEGIN
    DECLARE _numrows INT;
  
    UPDATE t25_inf_entregable
    SET t25_resulta = _resul,
    t25_conclu = _concl,
    t25_limita = _limit,
    t25_fac_pos = _facto,
    t25_perspec = _persp,
    usr_actu = _usr
    WHERE t02_cod_proy = _proy 
    AND t25_anio = _anio 
    AND t25_entregable = _entregable 
    AND t02_version = _ver;
    
    SELECT ROW_COUNT() INTO _numrows;
    /*Retornar el numero de registros afectados */
    SELECT _numrows AS numrows, _ver AS codigo;  
END $$
DELIMITER ;