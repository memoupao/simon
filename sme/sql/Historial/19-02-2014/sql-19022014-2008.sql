-- --------------------------------------------------------------------------------
-- DA v2
-- Actualizacion de la longitud del parametro _nomtema con 250 caracteres como maximo
-- --------------------------------------------------------------------------------


DROP procedure IF EXISTS `sp_ins_plan_capac_tema`;

DELIMITER $$

CREATE PROCEDURE `sp_ins_plan_capac_tema`(IN _proy VARCHAR(10), 
			IN _ver INT, 
			IN _comp INT, 
			IN _act INT, 
			IN _sub INT, 
			IN _nomtema VARCHAR(250),
			IN _horas   DOUBLE,
			IN _benef   DOUBLE,			
			IN _usr VARCHAR(20), IN _horas_total double, IN _benef_total double)
trx1 : BEGIN
DECLARE _rowcount INT ;
declare _idtema INT;
DECLARE _nrotemas DOUBLE ;
DECLARE _nrohoras DOUBLE ;
DECLARE _nrobenef DOUBLE ;
select count(1) into _rowcount 
  from t12_plan_capac_tema
 WHERE t02_cod_proy = _proy 
   AND	t02_version = _ver 
   AND	t08_cod_comp= _comp
   AND	t09_cod_act = _act 
   AND	t09_cod_sub = _sub  
   AND  t12_tem_espe=_nomtema;
if _rowcount > 0 then
    SELECT 0 AS numrows, 0 AS codigo, 'Ya existe un registro con el mismo Nombre' as msg ;  
    leave trx1 ;
end if ;
SELECT ifnull(max(t12_cod_tema),0)+1 INTO _idtema
  FROM t12_plan_capac_tema
 WHERE t02_cod_proy = _proy 
   AND	t02_version = _ver 
   AND	t08_cod_comp= _comp
   AND	t09_cod_act = _act 
   AND	t09_cod_sub = _sub ;
   insert into t12_plan_capac_tema 
	(t02_cod_proy, 
	t02_version, 
	t08_cod_comp, 
	t09_cod_act, 
	t09_cod_sub, 
	t12_cod_tema, 
	t12_tem_espe, 
	t12_nro_hora, 
	t12_nro_bene, 
	fch_crea, 
	usr_crea, 
	est_audi
	)
	values
	( _proy ,
          _ver ,
	  _comp,
	  _act ,
	  _sub  ,
	  _idtema,
	  _nomtema, 
	  _horas, 
	  _benef, 
	  now(), 
	 _usr, 
	 '1'
	);
    
  SELECT ROW_COUNT() INTO _rowcount ;
   COMMIT ;
   
   SELECT COUNT(t12_cod_tema)
     INTO  _nrotemas
     FROM t12_plan_capac_tema   
    WHERE t02_cod_proy = _proy 
      AND t02_version = _ver 
      AND t08_cod_comp= _comp
      AND t09_cod_act = _act 
      AND t09_cod_sub = _sub ;
    UPDATE t12_plan_capac 
	SET t12_nro_tema = _nrotemas , 
	    t12_hor_cap  = _horas_total , 
	    t12_nro_ben  = _benef_total ,
	    usr_actu     = _usr , 
	    fch_actu     = NOW()
	 WHERE t02_cod_proy = _proy 
	   AND	t02_version = _ver 
	   AND	t08_cod_comp= _comp
	   AND	t09_cod_act = _act 
	   AND	t09_cod_sub = _sub  ;

SELECT _rowcount AS numrows, _idtema AS codigo, '' as msg ;  
END$$

DELIMITER ;






DROP procedure IF EXISTS `sp_upd_plan_capac_tema`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_plan_capac_tema`(IN _proy VARCHAR(10), 
			IN _ver INT, 
			IN _comp INT, 
			IN _act INT, 
			IN _sub INT, 
			in _idtema int,
			IN _nomtema varchar(250),
			in _horas   double,
			IN _benef   DOUBLE,			
			IN _usr VARCHAR(20), IN _horas_total double, IN _benef_total double)
BEGIN
DECLARE _rowcount INT ;
declare _nrotemas double ;
DECLARE _nrohoras DOUBLE ;
DECLARE _nrobenef DOUBLE ;
UPDATE t12_plan_capac_tema 
   SET	t12_tem_espe = _nomtema , 
	t12_nro_hora = _horas , 
	t12_nro_bene = _benef ,
	usr_actu   = _usr , 
	fch_actu   = NOW()
 WHERE t02_cod_proy = _proy 
   AND	t02_version = _ver 
   AND	t08_cod_comp= _comp
   AND	t09_cod_act = _act 
   AND	t09_cod_sub = _sub  
   and  t12_cod_tema=_idtema;
   
   select ROW_COUNT() into _rowcount ;
   
   select count(t12_cod_tema),  sum(t12_nro_hora), sum(t12_nro_bene)
     into  _nrotemas, _nrohoras, _nrobenef 
     from t12_plan_capac_tema   
    WHERE t02_cod_proy = _proy 
      AND t02_version = _ver 
      AND t08_cod_comp= _comp
      AND t09_cod_act = _act 
      AND t09_cod_sub = _sub ;
    UPDATE t12_plan_capac 
	SET t12_nro_tema = _nrotemas , 
	    t12_hor_cap  = _nrohoras , 
	    t12_nro_ben  = _nrobenef ,
	    usr_actu     = _usr , 
	    fch_actu     = NOW()
	 WHERE t02_cod_proy = _proy 
	   AND	t02_version = _ver 
	   AND	t08_cod_comp= _comp
	   AND	t09_cod_act = _act 
	   AND	t09_cod_sub = _sub  ;

SELECT _rowcount AS numrows, _sub AS codigo ;  
END$$

DELIMITER ;



