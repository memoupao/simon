DROP procedure IF EXISTS `sp_sel_plan_visita`;
DROP procedure IF EXISTS `sp_upd_plan_visita_me`;
DROP procedure IF EXISTS `sp_ins_plan_visita_me`;



DELIMITER $$

CREATE PROCEDURE `sp_sel_plan_visita`( IN _proy VARCHAR(10), in _visita int)
BEGIN
declare _min_envio_solicitud int ;
if _visita <= 0 THEN
SELECT MIN(t31_id)  into _min_envio_solicitud
FROM t31_plan_me 
WHERE t02_cod_proy = _proy
  AND IFNULL(t31_fec_vis,0) = 0 ;
  
  
SELECT  vis.t02_cod_proy,
	vis.t31_id,
	vis.t31_anio,
	vis.t31_mes,
	fn_nom_mes(vis.t31_mes) AS nommes,
	vis.t31_obs,
	vis.t31_mto_v1,
	vis.t31_mto_v2,
	vis.t31_mto_tot,
	DATE_FORMAT((CASE WHEN vis.t31_fec_vis =0 THEN NULL ELSE vis.t31_fec_vis END),'%d/%m/%Y') AS t31_fec_vis,
	DATE_FORMAT((CASE WHEN vis.t31_fec_ter =0 THEN NULL ELSE vis.t31_fec_ter END),'%d/%m/%Y') AS t31_fec_ter,
	vis.t31_vb_v1,	
	vis.t31_des_v1,
	vis.t31_des_v2,
	vis.t31_des_tot,
	(case when vis.t31_id = _min_envio_solicitud then '1' else '0' end) as 'sol_via',
	vis.usr_crea,
	vis.est_audi
FROM t31_plan_me vis
WHERE  vis.t02_cod_proy=_proy ;
ELSE
SELECT  vis.t02_cod_proy,
	vis.t31_id,
	vis.t31_anio,
	vis.t31_mes,
	fn_nom_mes(vis.t31_mes) AS nommes,
	vis.t31_obs,
	vis.t31_mto_v1,
	vis.t31_mto_v2,
	vis.t31_mto_tot,
	DATE_FORMAT((CASE WHEN vis.t31_fec_vis =0 THEN NULL ELSE vis.t31_fec_vis END),'%d/%m/%Y') AS t31_fec_vis,
	DATE_FORMAT((CASE WHEN vis.t31_fec_ter =0 THEN NULL ELSE vis.t31_fec_ter END),'%d/%m/%Y') AS t31_fec_ter,
	vis.t31_vb_v1,	
	vis.t31_des_v1,
	vis.t31_des_v2,
	vis.t31_des_tot,
	(CASE WHEN vis.t31_id = _min_envio_solicitud THEN '1' ELSE '0' END) AS 'sol_via',
	vis.usr_crea,
	vis.est_audi
FROM t31_plan_me vis
WHERE vis.t02_cod_proy = _proy 
  AND vis.t31_id       = _visita ;

END IF ;
END$$
DELIMITER ;






DELIMITER $$

CREATE PROCEDURE `sp_ins_plan_visita_me`(
			IN _proy VARCHAR(10), 
			IN _anio INT, 
			IN _mes INT, 
			IN _obs TEXT, 
			IN _mto1 DOUBLE,
			IN _mto2 DOUBLE,
			IN _fecvis DATETIME,
			IN _fecter DATETIME,
			IN _vb CHAR(1),			
			IN _usr VARCHAR(20))
BEGIN
  DECLARE _id   INT;
  DECLARE _existe  INT;
  DECLARE _error VARCHAR(100);
  
  SELECT IFNULL(MAX(t31_id),0)+1 INTO _id  
  FROM 	 t31_plan_me 
  WHERE  t02_cod_proy	= _proy  ;
  
  SELECT COUNT(t31_id) 
  INTO   _existe
  FROM t31_plan_me
  WHERE t02_cod_proy=_proy
    AND t31_anio = _anio
    AND t31_mes = _mes;
  
  IF _existe =0 THEN
  SELECT '' INTO _error ;
  
  INSERT INTO t31_plan_me 
	( t02_cod_proy, 
	  t31_id, 
	  t31_anio, 
	  t31_mes, 
	  t31_obs, 
	  t31_mto_v1,
	  t31_mto_v2 ,
	  t31_mto_tot ,
	  t31_fec_vis ,
	t31_fec_ter ,
	t31_vb_v1   ,	
	  usr_crea, 
	  fch_crea, 
	  est_audi
	)
	VALUES
	( _proy , 
	  _id   , 
	  _anio , 
	  _mes  , 
	  _obs  , 
	  _mto1,
	  _mto2,
	  (_mto1 + _mto2),
	  _fecvis,
	  _fecter,
	  _vb ,	  
	  _usr, 
	  NOW(),
	'1'
	);
    
    SELECT ROW_COUNT() AS numrows, _id AS codigo, _error AS 'error' ;
   ELSE
    SELECT 'Ya fue Planeado al menos una visita, para este periodo' INTO _error;
    SELECT 0 AS numrows, _id AS codigo, _error AS 'error' ;
   END IF;
END$$
DELIMITER ;





DELIMITER $$

CREATE PROCEDURE `sp_upd_plan_visita_me`(
			IN _proy VARCHAR(10), 
			IN _id INT,
			IN _anio INT, 
			IN _mes INT, 
			IN _obs TEXT, 
			in _mto1 double,
			IN _mto2 DOUBLE,
			IN _fecvis datetime,
			IN _fecter DATETIME,
			IN _vb char(1),			
			IN _usr VARCHAR(20))
BEGIN
 
  UPDATE t31_plan_me 
     SET t31_anio = _anio , 
	t31_mes  = _mes , 
	t31_obs  = _obs , 
	t31_mto_v1 = _mto1,
	t31_mto_v2 = _mto2,
	t31_mto_tot = (_mto1 + _mto2),
	t31_fec_vis = _fecvis,
	t31_fec_ter = _fecter,
	t31_vb_v1   = _vb ,	
	usr_actu = _usr ,
	fch_actu = NOW()
  WHERE
	t02_cod_proy = _proy AND t31_id = _id ;
  

SELECT ROW_COUNT() AS numrows, _id AS codigo ;
END$$
DELIMITER ;




