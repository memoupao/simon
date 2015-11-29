-- --------------------------------------------------------------------------------
-- DA 2.0 [26-12-2013 18:16]
-- Actualizacion en asignacion de cuenta bancaria en el proyecto.
-- --------------------------------------------------------------------------------

DROP PROCEDURE IF EXISTS `sp_upd_cta_inst_proy`;

DELIMITER $$

CREATE  PROCEDURE `sp_upd_cta_inst_proy`(
            IN _proy VARCHAR(10), 
            IN _t01_bco     INT ,
            IN _codcta      INT ,
            IN _nrocta  VARCHAR(25), 
            IN _mon         INT ,
            IN _benef       VARCHAR(100),
            IN _usr_crea    VARCHAR(20))
TRX1:BEGIN
    DECLARE _id INT ;
    DECLARE _t01_id_inst INT ;
    DECLARE _rowcount INT;
    DECLARE _existe BOOLEAN ;
    
        
    IF NOT EXISTS(SELECT pcta.t01_id_cta   FROM  t02_proy_ctas pcta  INNER JOIN t01_inst_ctas cta ON(pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta)   WHERE pcta.t02_cod_proy = _proy AND cta.t01_nro_cta=_nrocta    and pcta.est_audi='1' ) THEN
       
    IF EXISTS(SELECT pcta.t01_id_cta   FROM  t02_proy_ctas pcta  INNER JOIN t01_inst_ctas cta ON(pcta.t01_id_inst=cta.t01_id_inst AND pcta.t01_id_cta=cta.t01_id_cta)   WHERE cta.t01_nro_cta=_nrocta   and pcta.est_audi='1') THEN
        SELECT -1 AS numrows, 0 AS codigo, 'La Cuenta Bancaria ya fue registrada' AS msg ;
        LEAVE TRX1;
    END IF ;
    END IF;
    
    
    SELECT t01_id_inst 
      INTO _t01_id_inst
      FROM t02_dg_proy 
     WHERE t02_cod_proy=_proy 
       AND t02_version=fn_ult_version_proy(_proy);
    
    IF NOT EXISTS(SELECT t01_id_cta FROM t01_inst_ctas WHERE t01_id_inst = _t01_id_inst AND t00_cod_bco = _t01_bco AND t01_nro_cta=_nrocta ) THEN
    SELECT IFNULL( MAX(t01_id_cta),0)+1 INTO _id  FROM t01_inst_ctas WHERE t01_id_inst = _t01_id_inst ; 
    
     UPDATE t02_proy_ctas SET est_audi = '0' WHERE t02_cod_proy = _proy ;
     
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
    
    SELECT ROW_COUNT() INTO _rowcount ;
    
    ELSE 
    SELECT t01_id_cta 
      INTO _id
    FROM t01_inst_ctas 
    WHERE t01_id_inst = _t01_id_inst 
      AND t00_cod_bco = _t01_bco 
      AND t01_nro_cta = _nrocta ;
      
    SELECT 1 INTO _rowcount ;
    END IF ;
    
    -- --------------------------------------------------------------------------------
    -- colocamos todos los proyectos registrados con est_audi = 0 para que no sean
    -- validos, y luego el metodo sp_upd_cta_proy registrara el seleccionado con el
    -- valor de 1
    UPDATE t02_proy_ctas SET est_audi = '0' WHERE t02_cod_proy = _proy ;
    --
    -- --------------------------------------------------------------------------------

    CALL sp_upd_cta_proy(_proy, _t01_id_inst , _id, _benef, _usr_crea) ;
    
    SELECT _rowcount AS numrows, _id AS codigo, '' AS msg ;
     
END $$

DELIMITER ;
