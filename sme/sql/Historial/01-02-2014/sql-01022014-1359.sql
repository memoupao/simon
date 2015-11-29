-- --------------------------------------------------------------------------------
-- DA V2
-- Actualizacion del registo de costos de presupuesto de componentes con retorno
-- del nuevo id ingresado.
-- --------------------------------------------------------------------------------


DROP procedure IF EXISTS `sp_ins_costeo`;
DELIMITER $$
CREATE PROCEDURE `sp_ins_costeo`(IN _proy varchar(10), IN _ver INT, IN _comp INT, IN _activ INT, 
								IN _subact INT 		, IN _descrip varchar(200) , IN _categ INT,
								IN _um 	varchar(50) , IN _cant	double		   , IN _cu	double,
								IN _usr varchar(20))
BEGIN
  DECLARE _cod_cost INT;
  DECLARE _meta_sub double;
  
  SELECT IFNULL(MAX(t10_cod_cost),0)+1 INTO _cod_cost 
  FROM 	 t10_cost_sub 
  WHERE  t02_cod_proy	= _proy  AND t02_version	= _ver		AND
		 t08_cod_comp	= _comp  AND t09_cod_act	= _activ	AND
		 t09_cod_sub	= _subact ;
 
 SELECT IFNULL(t09_mta,0) INTO _meta_sub
  FROM 	 t09_subact 
  WHERE  t02_cod_proy	= _proy AND t02_version	= _ver		AND
		 t08_cod_comp	= _comp AND t09_cod_act	= _activ	AND
		 t09_cod_sub	= _subact ;
 
INSERT INTO  t10_cost_sub ( t02_cod_proy,  t02_version , t08_cod_comp , t09_cod_act, t09_cod_sub, 
							t10_cod_cost,  t10_cost	   , t10_cate_cost, t10_um	   , t10_cant, t10_cu,
							t10_cost_parc, t10_cost_tot, usr_crea	  , fch_crea   , est_audi)
	   VALUES 			  ( _proy		 , _ver		   , _comp		  , _activ	   , _subact,
	   						_cod_cost	 , _descrip	   , _categ		  , _um		   , _cant   , _cu ,
	   						(_cant * _cu) , (_meta_sub * (_cant * _cu)),_usr , NOW(), '1');
select ROW_COUNT() as numrows, _cod_cost as codigo, _cod_cost as newid ;  					
END$$

DELIMITER ;



