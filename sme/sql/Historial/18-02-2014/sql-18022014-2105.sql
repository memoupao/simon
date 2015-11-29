-- --------------------------------------------------------------------------------
-- DA V2
-- Correccion de los montos en presupuesto - 
-- manejo del proyecto - seccion de personal.
-- --------------------------------------------------------------------------------


DROP function IF EXISTS `fn_gasto_total_mp_personal`;
DELIMITER $$
CREATE FUNCTION `fn_gasto_total_mp_personal`(_proy CHAR(10),       _vs INT) RETURNS double
BEGIN
	DECLARE _costoTotal DOUBLE;
	SELECT SUM(ROUND(t03_gasto_tot,2))
	INTO _costoTotal
	FROM t03_mp_per
	WHERE t02_cod_proy=_proy
  AND t02_version = _vs;
	RETURN ifnull(_costoTotal,0) ;
END$$

DELIMITER ;










DROP procedure IF EXISTS `sp_rpt_presup_analitico`;

DELIMITER $$

CREATE PROCEDURE `sp_rpt_presup_analitico`(IN _proy VARCHAR(10), IN _ver INT)
BEGIN
DECLARE _sql      LONGTEXT;
DECLARE _contador  INT ;
DECLARE _codfte   INT ;
DECLARE _nomfte   VARCHAR(50) ;
DECLARE _codFE    INT DEFAULT 10 ;

DECLARE _temp_comp_ope VARCHAR(500);
DECLARE _temp_act_ope  VARCHAR(500);
DECLARE _temp_sub_ope  VARCHAR(500);
DECLARE _temp_cat_ope  VARCHAR(700);
DECLARE _fte_comp_ope TEXT DEFAULT '';
DECLARE _fte_act_ope  TEXT DEFAULT '';
DECLARE _fte_sub_ope  TEXT DEFAULT '';
DECLARE _fte_cat_ope  TEXT DEFAULT '';

DECLARE _temp_comp_mp VARCHAR(500);
DECLARE _temp_act_mp  VARCHAR(500);
DECLARE _temp_sub_mp  VARCHAR(500);
DECLARE _temp_cat_mp  VARCHAR(700);
DECLARE _fte_comp_mp TEXT DEFAULT '';
DECLARE _fte_act_mp  TEXT DEFAULT '';
DECLARE _fte_sub_mp  TEXT DEFAULT '';
DECLARE _fte_cat_mp  TEXT DEFAULT '';

DROP TEMPORARY TABLE IF EXISTS tmpFuentes;
CREATE TEMPORARY TABLE tmpFuentes 
( id INT AUTO_INCREMENT,
  idfte INT,
  nomfte VARCHAR(250),
  PRIMARY KEY (id)
) ;
INSERT INTO tmpFuentes(idfte, nomfte)
SELECT 	f.t01_id_inst,
	i.t01_sig_inst
FROM 	  t02_fuente_finan f
inner JOIN t01_dir_inst     i ON (f.t01_id_inst=i.t01_id_inst)
WHERE f.t02_cod_proy=_proy 
  AND f.t01_id_inst = _codFE
UNION ALL
SELECT 	f.t01_id_inst,
	i.t01_sig_inst
FROM 	  t02_fuente_finan f
inner JOIN t01_dir_inst     i ON (f.t01_id_inst=i.t01_id_inst)
WHERE f.t02_cod_proy=_proy 
  AND f.t01_id_inst <> _codFE ;
  

SET _temp_comp_ope= "(SELECT 	SUM(fte.t10_mont)
		       FROM 	t10_cost_fte fte
		      WHERE fte.t02_cod_proy = c.t02_cod_proy
		        AND fte.t02_version  = c.t02_version
		        AND fte.t08_cod_comp = c.t08_cod_comp
		        AND fte.t01_id_inst  = _idfte
		     ) AS '_nomfte' " ;
SET _temp_act_ope = " (  SELECT SUM(fte.t10_mont)
			   FROM t10_cost_fte fte
			  WHERE fte.t02_cod_proy = a.t02_cod_proy
			    AND fte.t02_version  = a.t02_version
			    AND fte.t08_cod_comp = a.t08_cod_comp
			    AND fte.t09_cod_act  = a.t09_cod_act
			    AND fte.t01_id_inst  = _idfte
			)  AS '_nomfte' " ;
			
SET _temp_sub_ope = " (  SELECT SUM(fte.t10_mont)
			   FROM t10_cost_fte fte
			  WHERE fte.t02_cod_proy = s.t02_cod_proy
			    AND fte.t02_version  = s.t02_version
			    AND fte.t08_cod_comp = s.t08_cod_comp
			    AND fte.t09_cod_act  = s.t09_cod_act
			    AND fte.t09_cod_sub  = s.t09_cod_sub
			    AND fte.t01_id_inst  = _idfte
			 ) AS '_nomfte' " ;
			 
SET _temp_cat_ope = "  ( SELECT  SUM(fte.t10_mont)
			   FROM     t10_cost_fte fte
			 INNER JOIN t10_cost_sub cost ON ( fte.t02_cod_proy = cost.t02_cod_proy AND fte.t02_version  = cost.t02_version AND fte.t08_cod_comp = cost.t08_cod_comp AND fte.t09_cod_act  = cost.t09_cod_act AND fte.t09_cod_sub  = cost.t09_cod_sub AND fte.t10_cod_cost = cost.t10_cod_cost )
			  WHERE fte.t02_cod_proy = s.t02_cod_proy
			    AND fte.t02_version  = s.t02_version
			    AND fte.t08_cod_comp = s.t08_cod_comp
			    AND fte.t09_cod_act  = s.t09_cod_act
			    AND fte.t09_cod_sub  = s.t09_cod_sub
			    AND cost.t10_cate_cost = p.t10_cate_cost
			    AND fte.t01_id_inst  = _idfte
			 )  AS '_nomfte' " ;

SET _temp_comp_mp= "  (	  SELECT SUM(aporte_fuente)
			    FROM v_manejo_proyecto_fuentes fte 
			   WHERE fte.proyecto = vmp.proyecto
			     AND fte.vs  = vmp.vs
			     AND fte.componente = vmp.componente
			     AND fte.idinst = _idfte
		       ) AS '_nomfte' " ;
SET _temp_act_mp= "  (	  SELECT SUM(aporte_fuente)
			    FROM v_manejo_proyecto_fuentes fte 
			   WHERE fte.proyecto = vmp.proyecto
			     AND fte.vs  = vmp.vs
			     AND fte.componente = vmp.componente
			     AND fte.actividad  = vmp.actividad
			     AND fte.idinst = _idfte
		       ) AS '_nomfte' " ;
SET _temp_sub_mp = "  (	  SELECT SUM(aporte_fuente)
			    FROM v_manejo_proyecto_fuentes fte 
			   WHERE fte.proyecto = vmp.proyecto
			     AND fte.vs  = vmp.vs
			     AND fte.componente = vmp.componente
			     AND fte.actividad  = vmp.actividad
			     AND fte.codsub     = vmp.codsub
			     AND fte.idinst = _idfte
		       ) AS '_nomfte' " ;
		       
SET _temp_cat_mp = "  (	  SELECT SUM(aporte_fuente)
			    FROM v_manejo_proyecto_fuentes fte 
			   WHERE fte.proyecto = vmp.proyecto
			     AND fte.vs  = vmp.vs
			     AND fte.componente = vmp.componente
			     AND fte.actividad  = vmp.actividad
			     AND fte.codsub     = vmp.codsub
			     AND fte.rubro      = (CASE WHEN vmp.subactividad IS NULL THEN vmp.codigo ELSE (vmp.cate_gasto - 80) END) 
			     AND fte.idinst = _idfte
		       ) AS '_nomfte' " ;
SELECT 1 INTO _contador ;  
WHILE (SELECT COUNT(1) FROM  tmpFuentes) >= _contador DO
	SELECT idfte  ,  nomfte
	INTO   _codfte, _nomfte 
	FROM tmpFuentes 
	WHERE id = _contador;
	
	SET _fte_comp_ope = CONCAT(_fte_comp_ope, ',' , REPLACE(REPLACE(_temp_comp_ope,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	SET _fte_act_ope  = CONCAT(_fte_act_ope , ',' , REPLACE(REPLACE(_temp_act_ope ,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	SET _fte_sub_ope  = CONCAT(_fte_sub_ope , ',' , REPLACE(REPLACE(_temp_sub_ope ,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	SET _fte_cat_ope  = CONCAT(_fte_cat_ope , ',' , REPLACE(REPLACE(_temp_cat_ope ,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	
	SET _fte_comp_mp = CONCAT(_fte_comp_mp, ',' , REPLACE(REPLACE(_temp_comp_mp,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	SET _fte_act_mp  = CONCAT(_fte_act_mp , ',' , REPLACE(REPLACE(_temp_act_mp ,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	SET _fte_sub_mp  = CONCAT(_fte_sub_mp , ',' , REPLACE(REPLACE(_temp_sub_mp ,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	SET _fte_cat_mp  = CONCAT(_fte_cat_mp , ',' , REPLACE(REPLACE(_temp_cat_mp ,'_idfte', _codfte),'_nomfte', _nomfte) ) ;
	
	SET _contador = _contador + 1;
END WHILE;
DROP TEMPORARY TABLE tmpFuentes; 
SET _sql = "
SELECT 	c.t08_cod_comp AS codigo, 
	TRIM(c.t08_comp_desc) AS descripcion,
	NULL AS um,
	NULL AS parcial,
	NULL AS meta,
	( SELECT SUM( ROUND( ((cost.t10_cant * cost.t10_cu) * sub.t09_mta),2) )
	    FROM       t10_cost_sub cost
	    INNER JOIN t09_subact   sub  ON(sub.t02_cod_proy=cost.t02_cod_proy AND sub.t02_version=cost.t02_version AND sub.t08_cod_comp=cost.t08_cod_comp AND sub.t09_cod_act=cost.t09_cod_act AND sub.t09_cod_sub=cost.t09_cod_sub)
	   WHERE cost.t02_cod_proy=c.t02_cod_proy 
	     AND cost.t02_version =c.t02_version 
	     AND cost.t08_cod_comp=c.t08_cod_comp
	) AS total,
	'componente' AS tipo,
	' ' AS act_add,
	c.t08_cod_comp AS codigo_real
	[_fte_comp_ope]
FROM  t08_comp c
WHERE c.t02_cod_proy = _proy
  AND c.t02_version  = _ver
UNION ALL
SELECT 	CONCAT(a.t08_cod_comp,'.',a.t09_cod_act) AS codigo, 
	TRIM(a.t09_act) AS descripcion,
	NULL AS um,
	(
	  SELECT SUM(cost.t10_cant * cost.t10_cu) 
	    FROM t10_cost_sub cost
	   WHERE cost.t02_cod_proy=a.t02_cod_proy 
	     AND cost.t02_version =a.t02_version 
	     AND cost.t08_cod_comp=a.t08_cod_comp
	     AND cost.t09_cod_act =a.t09_cod_act
	) AS parcial,
	NULL AS meta,
	( SELECT SUM(  ROUND( ((cost.t10_cant * cost.t10_cu) * sub.t09_mta),2)    )
	    FROM       t10_cost_sub cost 
	    INNER JOIN t09_subact   sub  ON(sub.t02_cod_proy=cost.t02_cod_proy AND sub.t02_version=cost.t02_version AND sub.t08_cod_comp=cost.t08_cod_comp AND sub.t09_cod_act=cost.t09_cod_act AND sub.t09_cod_sub=cost.t09_cod_sub)
	   WHERE cost.t02_cod_proy=a.t02_cod_proy 
	     AND cost.t02_version =a.t02_version 
	     AND cost.t08_cod_comp=a.t08_cod_comp
	     AND cost.t09_cod_act =a.t09_cod_act
	) AS total,
	'actividad' AS tipo,
	' ' AS act_add,
	CONCAT(a.t08_cod_comp,'.',a.t09_cod_act) AS codigo_real
	[_fte_act_ope]
FROM  t09_act a
WHERE a.t02_cod_proy = _proy
  AND a.t02_version  = _ver
  
UNION ALL
SELECT 	CONCAT(s.t08_cod_comp,'.',s.t09_cod_act, '.', s.t09_cod_sub) AS codigo, 
	TRIM(s.t09_sub) AS descripcion,
	s.t09_um AS um,
	(
	  SELECT SUM(cost.t10_cant * cost.t10_cu) 
	    FROM t10_cost_sub cost
	   WHERE cost.t02_cod_proy=s.t02_cod_proy 
	     AND cost.t02_version =s.t02_version 
	     AND cost.t08_cod_comp=s.t08_cod_comp
	     AND cost.t09_cod_act =s.t09_cod_act
	     AND cost.t09_cod_sub =s.t09_cod_sub 
	) AS parcial,
	s.t09_mta AS meta,
	(( SELECT SUM(cost.t10_cant * cost.t10_cu) 
	    FROM t10_cost_sub cost
	   WHERE cost.t02_cod_proy=s.t02_cod_proy 
	     AND cost.t02_version =s.t02_version 
	     AND cost.t08_cod_comp=s.t08_cod_comp
	     AND cost.t09_cod_act =s.t09_cod_act
	     AND cost.t09_cod_sub =s.t09_cod_sub 
	) * s.t09_mta) AS total,
	'subactividad' AS tipo,
	IF(s.t02_version > 1,
			 IF(s.t09_cod_sub IN (
					SELECT suba.t09_cod_sub	AS subact		 
					FROM t09_subact suba
					WHERE  suba.t02_cod_proy = _proy
					  AND  suba.t08_cod_comp = s.t08_cod_comp 
					  AND  suba.t09_cod_act  = s.t09_cod_act
						AND  suba.t02_version  = 1
					  GROUP BY 1
					  ORDER BY subact
						),'','1')		  
	   ,'') AS act_add	,
	CONCAT(s.t08_cod_comp,'.',s.t09_cod_act, '.', s.t09_cod_sub) AS codigo_real
	[_fte_sub_ope]
FROM  t09_subact s
WHERE s.t02_cod_proy = _proy 
  AND s.t02_version  = _ver
UNION ALL
SELECT 	CONCAT(p.t08_cod_comp,'.', p.t09_cod_act,'.', p.t09_cod_sub,'.', t.cod_ext) AS codigo,
	TRIM(t.descrip) AS descripcion,
	NULL AS um,
	SUM(p.t10_cant * p.t10_cu) AS parcial,
	NULL AS meta,
	(( SELECT SUM(cost.t10_cant * cost.t10_cu) 
	    FROM t10_cost_sub cost 
	   WHERE cost.t02_cod_proy=p.t02_cod_proy 
	     AND cost.t02_version =p.t02_version 
	     AND cost.t08_cod_comp=p.t08_cod_comp
	     AND cost.t09_cod_act =p.t09_cod_act
	     AND cost.t09_cod_sub =p.t09_cod_sub
	     AND cost.t10_cate_cost=t.codi
	) * s.t09_mta) AS total,
	'rubro' AS tipo,
	' ' AS act_add,
	CONCAT(p.t08_cod_comp,'.', p.t09_cod_act,'.', p.t09_cod_sub,'.', p.t10_cate_cost) AS codigo_real
	[_fte_cat_ope]
FROM 	  t10_cost_sub p
INNER JOIN t09_subact  s ON(s.t02_cod_proy=p.t02_cod_proy AND s.t02_version=p.t02_version AND s.t08_cod_comp=p.t08_cod_comp AND s.t09_cod_act=p.t09_cod_act AND s.t09_cod_sub=p.t09_cod_sub)
LEFT  JOIN adm_tablas_aux t ON (p.t10_cate_cost=t.codi) 
WHERE p.t02_cod_proy = _proy 
  AND p.t02_version  = _ver
GROUP BY 1,2,3 
UNION ALL
 
SELECT 	vmp.componente AS codigo, 
	'Manejo del Proyecto' AS descripcion,
	NULL AS um,
	NULL AS parcial,
	NULL AS meta,
	SUM(ROUND((vmp.gasto * vmp.meta),2)) AS total,
	'componente' AS tipo,
	' ' AS act_add,
	CONCAT(80 + vmp.componente) AS codigo_real
	[_fte_comp_mp]
FROM  v_manejo_proyecto_completo vmp
WHERE vmp.proyecto = _proy
  AND vmp.vs = _ver 
  GROUP BY 1,2,3,4,5
UNION ALL
SELECT 	CONCAT(componente,'.',actividad) AS codigo, 
	vmp.nom_actividad AS descripcion,
	NULL AS um,
	SUM(vmp.gasto) parcial,
	NULL AS meta,
	SUM(ROUND((vmp.gasto * vmp.meta),2)) AS total,
	'actividad' AS tipo,
	' ' AS act_add,
	CONCAT(80 + componente,'.',actividad) AS codigo_real
	[_fte_act_mp]
FROM  v_manejo_proyecto_completo vmp
WHERE vmp.proyecto = _proy
  AND vmp.vs = _ver
 GROUP BY 1,2,3
UNION ALL
SELECT 	CONCAT(vmp.componente,'.',vmp.actividad, '.', vmp.codsub) AS codigo,
	(CASE WHEN vmp.subactividad IS NULL THEN 
	      vmp.nom_cate_gasto
	 ELSE
	      vmp.subactividad
	 END 
	) AS descripcion,
	vmp.um AS um,
	SUM(vmp.gasto) parcial,
	vmp.meta AS meta,
	SUM(ROUND((vmp.gasto * vmp.meta),2)) AS total,
	'subactividad' AS tipo,
	'' AS act_add,
	
	CONCAT(80 + vmp.componente,'.',vmp.actividad, '.', vmp.codsub) AS codigo_real
	[_fte_sub_mp]
FROM  v_manejo_proyecto_completo vmp
WHERE vmp.proyecto = _proy
  AND vmp.vs = _ver
  and vmp.codsub is not null
 GROUP BY 1,2
UNION ALL
SELECT 	
	(CASE WHEN vmp.subactividad IS NULL THEN 
	      CONCAT(vmp.componente,'.',vmp.actividad, '.', vmp.codsub,'.',vmp.codigo)
	 ELSE
	      CONCAT(vmp.componente,'.',vmp.actividad, '.', vmp.codsub,'.',(vmp.cate_gasto - 80))
	 END 
	) AS codigo,
	
	(CASE WHEN vmp.subactividad IS NULL THEN 
	      vmp.nombre
	 ELSE
	      vmp.nom_cate_gasto
	 END 
	) AS descripcion,
	(CASE WHEN vmp.subactividad IS NULL THEN vmp.um ELSE NULL END )AS um,
	SUM(vmp.gasto) parcial,
	(CASE WHEN vmp.subactividad IS NULL THEN vmp.meta ELSE NULL END )AS meta,
	SUM(ROUND((vmp.gasto * vmp.meta),2)) AS total,
	'rubro' AS tipo,
	' ' AS act_add,
	(CASE WHEN vmp.subactividad IS NULL THEN 
	      CONCAT(80 + vmp.componente,'.',vmp.actividad, '.', vmp.codsub,'.',vmp.codigo + 10)
	 ELSE
	      CONCAT(80 + vmp.componente,'.',vmp.actividad, '.', vmp.codsub,'.',(vmp.cate_gasto))
	 END 
	) AS codigo_real
	[_fte_cat_mp]
FROM  v_manejo_proyecto_completo vmp
WHERE vmp.proyecto = _proy
  AND vmp.vs = _ver
  and vmp.cate_gasto is not null
  GROUP BY 1, 2, 3 
  
ORDER BY codigo_real ; " ; 
  
 
SET _sql = REPLACE(_sql,' _proy', CONCAT(" '",_proy,"'"));
SET _sql = REPLACE(_sql,' _ver', _ver);
SET _sql = REPLACE(_sql,'[_fte_comp_ope]', _fte_comp_ope);
SET _sql = REPLACE(_sql,'[_fte_act_ope]' , _fte_act_ope );
SET _sql = REPLACE(_sql,'[_fte_sub_ope]' , _fte_sub_ope );
SET _sql = REPLACE(_sql,'[_fte_cat_ope]' , _fte_cat_ope );
SET _sql = REPLACE(_sql,'[_fte_comp_mp]', _fte_comp_mp);
SET _sql = REPLACE(_sql,'[_fte_act_mp]' , _fte_act_mp );
SET _sql = REPLACE(_sql,'[_fte_sub_mp]' , _fte_sub_mp );
SET _sql = REPLACE(_sql,'[_fte_cat_mp]' , _fte_cat_mp );
SELECT _sql INTO @txtsql;
PREPARE stmt FROM @txtsql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;  
 
 
 
END$$

DELIMITER ;








