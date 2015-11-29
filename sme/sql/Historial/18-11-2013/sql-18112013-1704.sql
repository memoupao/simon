/* 
Actualizacion del proceso de registro de una cuenta bancaria al proyecto.
*/

DELIMITER $$

DROP PROCEDURE IF EXISTS  `sp_upd_cta_inst_proy`$$

CREATE PROCEDURE `sp_upd_cta_inst_proy`(
			IN _proy varchar(10), 
			IN _t01_bco    	INT ,
			in _codcta      int ,
			IN _nrocta 	VARCHAR(25), 
			in _mon         int ,
			in _benef       varchar(100),
			IN _usr_crea  	VARCHAR(20))
TRX1:BEGIN
    declare _id int ;
    declare _t01_id_inst int ;
    declare _rowcount int;
    declare _existe boolean ;
    
    /* Validaciones */    
    if not EXISTS(SELECT pcta.t01_id_cta   FROM  t02_proy_ctas pcta  INNER JOIN t01_inst_ctas cta ON(pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta)   WHERE pcta.t02_cod_proy = _proy AND cta.t01_nro_cta=_nrocta   ) then
       /*if EXISTS(select t01_id_cta from t01_inst_ctas  where t00_cod_bco = _t01_bco and t01_nro_cta = _nrocta) then
          SELECT -1 AS numrows, 0 AS codigo, 'La Cuenta Bancaria ya fue registrada' AS msg ;
          leave TRX1;
       end if ;*/
## -------------------------------------------------->
## DA 2.0 [18-11-2013 17:02]
## La Cuenta tampoco deberia de existir para cualquier otro proyecto
	if EXISTS(SELECT pcta.t01_id_cta   FROM  t02_proy_ctas pcta  INNER JOIN t01_inst_ctas cta ON(pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta)   WHERE cta.t01_nro_cta=_nrocta ) then
		SELECT -1 AS numrows, 0 AS codigo, 'La Cuenta Bancaria ya fue registrada' AS msg ;
		leave TRX1;
	end if ;
## --------------------------------------------------<	
    end if;
    
	
    select t01_id_inst 
      into _t01_id_inst
      from t02_dg_proy 
     where t02_cod_proy=_proy 
       and t02_version=fn_ult_version_proy(_proy);
    
    if not EXISTS(select t01_id_cta from t01_inst_ctas where t01_id_inst = _t01_id_inst and t00_cod_bco = _t01_bco and t01_nro_cta=_nrocta ) then
	SELECT IFNULL( MAX(t01_id_cta),0)+1 INTO _id  FROM t01_inst_ctas WHERE t01_id_inst = _t01_id_inst ; 
    
     update t02_proy_ctas set est_audi = '0' where t02_cod_proy = _proy ;
     
     INSERT INTO t01_inst_ctas 
	(t01_id_inst, 
	t01_id_cta, 
	t00_cod_bco, 
	t00_cod_cta, 
	t01_nro_cta, 
	t00_cod_mon, 
	t01_txt_ref, 
	t01_cta_def, 
	usr_crea, 
	fch_crea, 
	est_audi
	)
	VALUES
	( _t01_id_inst,
	_id,
	_t01_bco,
	_codcta,
	_nrocta,
	_mon,
	'',
	'1',
	_usr_crea,
	NOW(),
	'1'
	);
	
	select ROW_COUNT() into _rowcount ;
	
    else 
	SELECT t01_id_cta 
	  into _id
	FROM t01_inst_ctas 
	WHERE t01_id_inst = _t01_id_inst 
	  and t00_cod_bco = _t01_bco 
	  AND t01_nro_cta = _nrocta ;
	  
	SELECT 1 INTO _rowcount ;
    end if ;
    
    call sp_upd_cta_proy(_proy, _t01_id_inst , _id, _benef, _usr_crea) ;
    
    SELECT _rowcount AS numrows, _id AS codigo, '' AS msg ;
     
END$$

DELIMITER ;



