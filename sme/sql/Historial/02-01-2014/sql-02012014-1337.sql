/*
// -------------------------------------------------->
// AQ 2.0 [02-01-2014 13:37]
// Lista el Plan de Capacitación 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_lis_plan_capac_trim`;
DROP PROCEDURE IF EXISTS `sp_lis_plan_capac`;

DELIMITER $$

CREATE PROCEDURE `sp_lis_plan_capac`(IN _tipo INT,
  IN _proy VARCHAR(10),  IN _ver INT, IN _mod INT,  IN _sub varchar(10))
BEGIN
IF _tipo = 1 THEN  -- > Seleccionamos los Modulos
  SELECT v.codmodulo,
     v.nommodulo,
     count(v.codigo) as numsub
    from ( 
    SELECT  CONCAT(tema.t08_cod_comp,'.',tema.t09_cod_act,'.',tema.t09_cod_sub) AS codigo,
        sub.t09_sub ,
        tema.t12_cod_tema,
        tema.t12_tem_espe,
        tema.t12_nro_hora,
        tema.t12_nro_bene,
        plan.t12_modulo as codmodulo ,
        modu.descrip as nommodulo
    FROM       t12_plan_capac_tema tema
    INNER JOIN t12_plan_capac      plan ON(tema.t02_cod_proy=plan.t02_cod_proy AND tema.t02_version=plan.t02_version AND tema.t08_cod_comp=plan.t08_cod_comp AND tema.t09_cod_act=plan.t09_cod_act AND tema.t09_cod_sub=plan.t09_cod_sub)
    LEFT  JOIN t09_subact          sub  ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
    LEFT  JOIN adm_tablas_aux      modu ON(plan.t12_modulo=modu.codi)
    WHERE tema.t02_cod_proy = _proy
      AND plan.t02_version  = _ver
      ) v
    group by v.codmodulo, v.nommodulo ;
end IF ;

IF _tipo = 2 THEN  -- > Seleccionamos las Actividades por modulo
  SELECT v.codigo,
     v.t09_sub,
     COUNT(v.t12_cod_tema) as numtema
    FROM ( 
    SELECT  CONCAT(tema.t08_cod_comp,'.',tema.t09_cod_act,'.',tema.t09_cod_sub) AS codigo,
        sub.t09_sub ,
        tema.t12_cod_tema,
        tema.t12_tem_espe,
        tema.t12_nro_hora,
        tema.t12_nro_bene,
        plan.t12_modulo AS codmodulo ,
        modu.descrip AS nommodulo
    FROM       t12_plan_capac_tema tema
    INNER JOIN t12_plan_capac      plan ON(tema.t02_cod_proy=plan.t02_cod_proy AND tema.t02_version=plan.t02_version AND tema.t08_cod_comp=plan.t08_cod_comp AND tema.t09_cod_act=plan.t09_cod_act AND tema.t09_cod_sub=plan.t09_cod_sub)
    LEFT  JOIN t09_subact          sub  ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
    LEFT  JOIN adm_tablas_aux      modu ON(plan.t12_modulo=modu.codi)
    WHERE tema.t02_cod_proy = _proy
      AND plan.t02_version  = _ver
      ) v
     where v.codmodulo = _mod 
    GROUP BY  v.codigo, v.t09_sub ;
END IF ;

IF _tipo = 3 THEN  -- > Seleccionamos las Temas por Actividad
    SELECT  CONCAT(tema.t08_cod_comp,'.',tema.t09_cod_act,'.',tema.t09_cod_sub) AS codigo,
        sub.t09_sub ,
        tema.t12_cod_tema,
        tema.t12_tem_espe,
        tema.t12_nro_hora,
        tema.t12_nro_bene,
        plan.t12_modulo AS codmodulo ,
        modu.descrip AS nommodulo
    FROM       t12_plan_capac_tema tema
    INNER JOIN t12_plan_capac      plan ON(tema.t02_cod_proy=plan.t02_cod_proy AND tema.t02_version=plan.t02_version AND tema.t08_cod_comp=plan.t08_cod_comp AND tema.t09_cod_act=plan.t09_cod_act AND tema.t09_cod_sub=plan.t09_cod_sub)
    LEFT  JOIN t09_subact          sub  ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
    LEFT  JOIN adm_tablas_aux      modu ON(plan.t12_modulo=modu.codi)
    WHERE tema.t02_cod_proy = _proy
      AND plan.t02_version  = _ver
      and plan.t12_modulo   = _mod
      and CONCAT(tema.t08_cod_comp,'.',tema.t09_cod_act,'.',tema.t09_cod_sub) = _sub ;
END IF ;
  
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [02-01-2014 15:28]
// Actualización del Plan de Capacitación 
// del Informe de Entregable 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_upd_inf_entregable_capac`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_inf_entregable_capac`(  
                        IN _proy VARCHAR(10), 
                        IN _ver INT, 
                        IN _anio INT, 
                        IN _entregable INT,
                        IN _comp INT,
                        IN _act  INT,
                        IN _sub  INT,
                        IN _tema INT,
                        IN _beneficiarios  text, 
                        IN _calificaciones text,
                        IN _user   VARCHAR(20))
BEGIN
    DECLARE _rowcount INT;
    
    CALL sp_strTempTable('TTempBenef',_beneficiarios,'|');
    CALL sp_strTempTable('TTempCalif',_calificaciones,'|');

    START TRANSACTION ;
        
    DELETE FROM t25_inf_entregable_capac 
    WHERE t02_cod_proy = _proy  
      AND t08_cod_comp = _comp
      AND t09_cod_act = _act 
      AND t09_cod_sub = _sub 
      AND t12_cod_tema = _tema
      AND t25_anio = _anio
      AND t25_entregable = _entregable
      AND t02_version = _ver
      AND t11_cod_bene IN (SELECT b.txt FROM TTempBenef b INNER JOIN TTempCalif c ON b.id=c.id WHERE c.txt <> "");
      
    INSERT INTO t25_inf_entregable_capac 
        (t02_cod_proy,
        t02_version,
        t08_cod_comp, 
        t09_cod_act, 
        t09_cod_sub, 
        t12_cod_tema, 
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
        _tema,
        _anio,
        _entregable,
        b.txt AS benef,
        c.txt AS avanc,
        NOW(),
        _user,
        NULL,
        NULL,
        '1'
    FROM TTempBenef b
    INNER JOIN TTempCalif c ON(b.id=c.id)
    WHERE c.txt <> "";
    
    SELECT ROW_COUNT() INTO _rowcount;
    
    COMMIT;
    
    DROP TEMPORARY TABLE TTempBenef;
    DROP TEMPORARY TABLE TTempCalif;
    
    SELECT _rowcount AS numrows, _tema AS codigo ;
         
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [02-01-2014 17:07]
// Actualización del Plan de Capacitación 
// del Informe de Entregable 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_lis_inf_entregable_capac`;

DELIMITER $$

CREATE PROCEDURE `sp_lis_inf_entregable_capac`(IN _proy VARCHAR(10), IN _ver INT, 
  IN _anio INT, 
  IN _entregable INT, 
  IN _dpto CHAR(2), 
  IN _prov CHAR(2), 
  IN _dist CHAR(2),
  IN _case CHAR(2))
BEGIN
DECLARE _sql       LONGTEXT;
DECLARE _tmpsql    TEXT DEFAULT ' 
SELECT  ben.t11_cod_bene, 
    ben.t11_dni, 
    CONCAT(ben.t11_ape_pat, \' \' ,ben.t11_ape_mat, \', \', ben.t11_nom) AS nombres,
    sex.descrip AS sexo, 
    ben.t11_edad, 
    niv.descrip AS nivel,
    esp.descrip AS especialidad,
    (SELECT ubig.nom_ubig
        FROM adm_ubigeo ubig
        WHERE ubig.cod_dpto = ben.t11_dpto
        AND ubig.cod_prov = 0
        AND ubig.cod_dist = 0) AS departamento,
    (SELECT ubig.nom_ubig
        FROM adm_ubigeo ubig
        WHERE ubig.cod_dpto = ben.t11_dpto
        AND ubig.cod_prov = ben.t11_prov
        AND ubig.cod_dist = 0) AS provincia,
    (SELECT ubig.nom_ubig
        FROM adm_ubigeo ubig
        WHERE ubig.cod_dpto = ben.t11_dpto
        AND ubig.cod_prov = ben.t11_prov
        AND ubig.cod_dist = ben.t11_dist) AS distrito,
    (SELECT cas.nom_case
        FROM adm_caserios cas
        WHERE cas.cod_dpto = ben.t11_dpto
        AND cas.cod_prov = ben.t11_prov
        AND cas.cod_dist = ben.t11_dist
        AND cas.cod_case = ben.t11_case) AS caserio,
    ben.t11_direccion, 
    ben.t11_ciudad, 
    ben.t11_telefono, 
    ben.t11_celular, 
    ben.t11_mail, 
    sec1.descrip AS sec_prod_1, 
    sec2.descrip AS sec_prod_2, 
    sec3.descrip AS sec_prod_3,
    subsec1.descrip AS subsec_prod_1,
    subsec2.descrip AS subsec_prod_2,
    subsec3.descrip AS subsec_prod_3,
    
    ben.t11_unid_prod_1 as t11_nro_up_a, 
    ben.t11_nro_up_b, 
    ben.t11_nom_prod, 
    ben.t11_act_princ, 
    t11_unid_prod_1,
    t11_tot_unid_prod,
    t11_nro_up_b,
    t11_unid_prod_2,
    t11_tot_unid_prod_2,
    t11_nro_up_b_2,
    t11_unid_prod_3,
    t11_tot_unid_prod_3,
    t11_nro_up_b_3,
    est.descrip AS estado
{subquerys}
FROM      t11_bco_bene ben
INNER JOIN t12_plan_capac_tema plan ON (ben.t02_cod_proy = plan.t02_cod_proy AND plan.t02_version={_ver}) 
LEFT JOIN adm_tablas_aux sex ON (ben.t11_sexo = sex.codi) 
LEFT JOIN adm_tablas_aux niv ON (ben.t11_nivel_educ = niv.codi) 
LEFT JOIN adm_tablas_aux esp ON (ben.t11_especialidad = esp.codi and esp.idTabla =  26)
LEFT JOIN adm_tablas_aux sec1 ON (ben.t11_sec_prod = sec1.codi AND sec1.idTabla = 18) 
LEFT JOIN adm_tablas_aux sec2 ON (ben.t11_sec_prod_2 = sec2.codi AND sec1.idTabla = 18) 
LEFT JOIN adm_tablas_aux sec3 ON (ben.t11_sec_prod_3 = sec3.codi AND sec1.idTabla = 18) 
LEFT JOIN adm_tablas_aux2 subsec1 ON (ben.t11_subsector = subsec1.codi AND subsec1.idTabla = 18)
LEFT JOIN adm_tablas_aux2 subsec2 ON (ben.t11_subsec_prod_2 = subsec2.codi AND subsec1.idTabla = 18)
LEFT JOIN adm_tablas_aux2 subsec3 ON (ben.t11_subsec_prod_3 = subsec3.codi AND subsec1.idTabla = 18)
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
      SELECT cap.t15_avance 
        FROM t25_inf_entregable_capac cap
       WHERE cap.t02_cod_proy = \'{_proy}\'
         AND CONCAT(cap.t08_cod_comp,\'.\',cap.t09_cod_act,\'.\',cap.t09_cod_sub) = \'{_codsub}\'
         AND cap.t12_cod_tema = {_codtema}
         AND cap.t25_anio = {_anio}
         AND cap.t25_entregable = {_entregable}
         AND cap.t02_version = {_ver}
         AND cap.t11_cod_bene = ben.t11_cod_bene
      ) AS \'{_codsubtema}\'' ;
      
DECLARE _subsql TEXT;
DECLARE _contador  INT ;
DECLARE _codfte   INT ;
DECLARE _nomfte   VARCHAR(50) ;
DECLARE _codFE    INT DEFAULT 10 ;
DECLARE _escape INT DEFAULT 0;
DECLARE _codsub CHAR(10);
DECLARE _codtema INT;
DECLARE _nomtema VARCHAR(150);
DECLARE _nrohora DOUBLE ;
DECLARE _curtemas CURSOR 
                 FOR 
                 SELECT     CONCAT(tema.t08_cod_comp,'.',tema.t09_cod_act,'.',tema.t09_cod_sub) AS subact,
                tema.t12_cod_tema,
                tema.t12_tem_espe,
                tema.t12_nro_hora
            FROM       t12_plan_capac_tema tema
            INNER JOIN t12_plan_capac      plan ON(tema.t02_cod_proy=plan.t02_cod_proy AND tema.t02_version=plan.t02_version AND tema.t08_cod_comp=plan.t08_cod_comp AND tema.t09_cod_act=plan.t09_cod_act AND tema.t09_cod_sub=plan.t09_cod_sub)
            WHERE tema.t02_cod_proy = _proy
              AND plan.t02_version  = _ver 
            ORDER BY t12_modulo, subact , t12_cod_tema ;
              
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET _escape = 1;
SET _sql = '' ;
OPEN _curtemas;
REPEAT
FETCH _curtemas INTO _codsub , _codtema , _nomtema , _nrohora ;
IF NOT _escape THEN
    SET _subsql = REPLACE(_tmpsubsql,'{_proy}',_proy);
    SET _subsql = REPLACE(_subsql,'{_ver}',_ver);
    SET _subsql = REPLACE(_subsql,'{_codsub}',_codsub);
    SET _subsql = REPLACE(_subsql,'{_codtema}',_codtema);
    SET _subsql = REPLACE(_subsql,'{_anio}',_anio);
    SET _subsql = REPLACE(_subsql,'{_entregable}',_entregable);
    SET _subsql = REPLACE(_subsql,'{_codsubtema}', CONCAT(_codsub,'.',_codtema) );
    
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
SELECT _sql INTO @txtsql;
PREPARE stmt FROM @txtsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;  
 
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [02-01-2014 15:28]
// Lista el Plan de Asistencia Técnica 
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_lis_plan_at`;

DELIMITER $$

CREATE PROCEDURE `sp_lis_plan_at`(IN _tipo INT,
                     IN _proy VARCHAR(10),
                     IN _ver INT,
                     IN _mod INT)
BEGIN
IF _tipo = 1 THEN  -- > Seleccionamos los Modulos
  SELECT v.codmodulo,
     v.nommodulo,
     COUNT(v.codigo) AS numsub
    FROM ( 
    SELECT  CONCAT(plan.t08_cod_comp,'.',plan.t09_cod_act,'.',plan.t09_cod_sub) AS codigo,
        sub.t09_sub ,
        plan.t12_hor_cap,
        plan.t12_nro_ben,
        plan.t12_modulo AS codmodulo ,
        modu.descrip AS nommodulo
    FROM       t12_plan_at        plan 
    LEFT  JOIN t09_subact          sub  ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
    LEFT  JOIN adm_tablas_aux      modu ON(plan.t12_modulo=modu.codi)
    WHERE plan.t02_cod_proy = _proy
      AND plan.t02_version  = _ver
      and modu.descrip is not null
      ) v
    GROUP BY v.codmodulo, v.nommodulo ;
END IF ;
IF _tipo = 2 THEN  -- > Seleccionamos las SubActividades por modulo
  SELECT v.codigo,
     v.t09_sub,
     v.t12_hor_cap,
     COUNT(v.codigo) AS numtema
    FROM ( 
    SELECT  CONCAT(plan.t08_cod_comp,'.',plan.t09_cod_act,'.',plan.t09_cod_sub) AS codigo,
        sub.t09_sub ,
        plan.t12_hor_cap,
        plan.t12_nro_ben,
        plan.t12_modulo AS codmodulo ,
        modu.descrip AS nommodulo
    FROM       t12_plan_at        plan 
    LEFT  JOIN t09_subact          sub  ON(plan.t02_cod_proy=sub.t02_cod_proy AND plan.t02_version=sub.t02_version AND plan.t08_cod_comp=sub.t08_cod_comp AND plan.t09_cod_act=sub.t09_cod_act AND plan.t09_cod_sub=sub.t09_cod_sub)
    LEFT  JOIN adm_tablas_aux      modu ON(plan.t12_modulo=modu.codi)
    WHERE plan.t02_cod_proy = _proy
      AND plan.t02_version  = _ver
      AND modu.descrip IS NOT NULL
      ) v
     WHERE v.codmodulo = _mod 
    GROUP BY  v.codigo, v.t09_sub, v.t12_hor_cap ;
END IF ;
  
END $$

DELIMITER ;

/*
// -------------------------------------------------->
// AQ 2.0 [02-01-2014 18:36]
// Lista el Plan de Asistencia Técnica 
// del Informe de Entregable
// --------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_lis_inf_entregable_at`;

DELIMITER $$

CREATE PROCEDURE `sp_lis_inf_entregable_at`(IN _proy VARCHAR(10), IN _ver INT,
 IN _anio INT,  IN _entregable INT,  IN _dpto CHAR(2),  IN _prov CHAR(2), 
 IN _dist CHAR(2),  IN _case CHAR(2))
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
INNER JOIN t12_plan_at plan ON (ben.t02_cod_proy = plan.t02_cod_proy AND plan.t02_version={_ver}) 
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
      SELECT cap.t15_avance 
        FROM t25_inf_entregable_at cap
       WHERE cap.t02_cod_proy = \'{_proy}\'
         AND CONCAT(cap.t08_cod_comp,\'.\',cap.t09_cod_act,\'.\',cap.t09_cod_sub) = \'{_codsub}\'
         AND cap.t25_anio = {_anio}
         AND cap.t25_entregable = {_entregable}
         AND cap.t02_version = {_ver}
         AND cap.t11_cod_bene = ben.t11_cod_bene
      ) AS \'{_codsubtema}\'' ;
      
DECLARE _subsql TEXT;
DECLARE _contador  INT ;
DECLARE _codfte   INT ;
DECLARE _nomfte   VARCHAR(50) ;
DECLARE _codFE    INT DEFAULT 10 ;
DECLARE _escape INT DEFAULT 0;
DECLARE _codsub CHAR(10);
DECLARE _codtema INT;
DECLARE _nomtema VARCHAR(150);
DECLARE _nrohora DOUBLE ;
DECLARE _curtemas CURSOR 
                 FOR 
            SELECT  CONCAT(plan.t08_cod_comp,'.',plan.t09_cod_act,'.',plan.t09_cod_sub) AS subact,
                sub.t09_cod_sub,
                sub.t09_sub,
                plan.t12_hor_cap
            FROM       t12_plan_at       plan
            INNER JOIN t09_subact       sub ON (sub.t02_cod_proy=plan.t02_cod_proy AND sub.t02_version=plan.t02_version AND sub.t08_cod_comp=plan.t08_cod_comp AND sub.t09_cod_act=plan.t09_cod_act AND sub.t09_cod_sub=plan.t09_cod_sub)
            WHERE plan.t02_cod_proy = _proy
              AND plan.t02_version  = _ver
              AND plan.t12_modulo > 0
            ORDER BY t12_modulo, subact  ;
              
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET _escape = 1;
SET _sql = '' ;
OPEN _curtemas;
REPEAT
FETCH _curtemas INTO _codsub , _codtema , _nomtema , _nrohora ;
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