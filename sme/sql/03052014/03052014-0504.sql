/*
// -------------------------------------------------------------------------->
// AQ 2.1 [03-05-2014 05:04]
// Fixed: Columna Meta Reprogramada
// Relacionado a: RF-009 - Informe POA - Agregar columna Meta Física Total Vigente 
// y revisar columna Variación
// --------------------------------------------------------------------------<
*/
DROP PROCEDURE IF EXISTS `sp_poa_subact`;

DELIMITER $$

CREATE PROCEDURE `sp_poa_subact`(IN _proy VARCHAR(10), IN _ver  INT, IN _comp INT, IN _act  INT, IN _anio INT)
BEGIN

  DECLARE _ver_last INT;
  DECLARE _ver_cron INT;

  SELECT fn_pri_version_proy(_proy) INTO _ver_cron;
  IF(_anio>1) THEN
      SELECT fn_version_proy_poa(_proy, _anio-1) INTO _ver_last;
  ELSE
      SELECT _ver INTO _ver_last;
  END IF;

  SELECT temp.t02_cod_proy,
      temp.t02_version,
      temp.codigo,
      temp.comp,
      temp.subact, 
      temp.descripcion, 
      temp.um, 
      temp.meta_acum,
      temp.resumen,
      temp.t09_obs_mt,
      temp.mfi,      
      temp.mpaa,
      temp.mftv,
      temp.mreprog,
      temp.meaa,
      temp.meta_poa,
      temp.mpar, -- Meta Proyectada Años Restantes
      temp.act_add
  FROM
  (
  SELECT sub.t02_cod_proy, 
         sub.t02_version, 
         CONCAT(sub.t08_cod_comp,'.', sub.t09_cod_act, '.', sub.t09_cod_sub) AS codigo,
         sub.t08_cod_comp AS comp, 
         sub.t09_cod_act AS act, 
         sub.t09_cod_sub AS subact, 
         sub.t09_sub AS descripcion, 
         sub.t09_um AS um, 
         sub.t09_mta AS meta_acum,
         sub.t09_resumen as resumen,
         sub.t09_obs_mt,
       (
          SELECT SUM(mta.t09_mta)
            FROM t09_sub_act_mtas mta
           WHERE mta.t02_cod_proy = sub.t02_cod_proy 
             AND mta.t02_version  = _ver_cron
             AND mta.t08_cod_comp = sub.t08_cod_comp 
             AND mta.t09_cod_act  = sub.t09_cod_act 
             AND mta.t09_cod_sub  = sub.t09_cod_sub 
          ) AS mfi,
         
         (CASE WHEN _anio>1 
           THEN 
              (
              SELECT SUM(mta.t09_mta)
                FROM t09_sub_act_mtas mta
               WHERE mta.t02_cod_proy = sub.t02_cod_proy 
                 AND mta.t02_version  = _ver_last
                 AND mta.t08_cod_comp = sub.t08_cod_comp 
                 AND mta.t09_cod_act  = sub.t09_cod_act 
                 AND mta.t09_cod_sub  = sub.t09_cod_sub 
                 AND mta.t09_anio=(_anio - 1)
              )
           ELSE 0 
           END) AS mpaa, 
         (SELECT fn_get_mta_reprog(sub.t02_cod_proy, _ver_last, sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub, (_anio-1), FALSE)) AS mftv, 
         (SELECT fn_get_mta_reprog(sub.t02_cod_proy, _ver, sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub, _anio, TRUE)) AS mreprog,
         IFNULL((
           SELECT SUM(inf.t09_sub_avanc) 
             FROM t09_act_sub_mtas_inf inf
            WHERE inf.t02_cod_proy = sub.t02_cod_proy
              AND inf.t08_cod_comp = sub.t08_cod_comp
              AND inf.t09_cod_act  = sub.t09_cod_act
              AND inf.t09_cod_sub  = sub.t09_cod_sub
              AND inf.t09_sub_anio <= (_anio-1)
         ),0) AS meaa, 
         (
          SELECT SUM(mta.t09_mta)
            FROM t09_sub_act_mtas mta
           WHERE mta.t02_cod_proy = sub.t02_cod_proy 
             AND mta.t02_version  = sub.t02_version
             AND mta.t08_cod_comp = sub.t08_cod_comp 
             AND mta.t09_cod_act  = sub.t09_cod_act 
             AND mta.t09_cod_sub  = sub.t09_cod_sub 
             AND mta.t09_anio=_anio
         ) AS meta_poa, 
         sub.t09_mta_proy AS mpar , 
         IF(sub.t02_version > 1,
               IF(sub.t09_cod_sub IN (
                      SELECT sub.t09_cod_sub  AS subact        
                      FROM t09_subact       sub
                      WHERE  sub.t02_cod_proy = _proy
                        AND  sub.t02_version  = 1
                        AND  sub.t08_cod_comp = _comp
                        AND  sub.t09_cod_act  = _act
                        GROUP BY 1
                        ORDER BY subact
                          ),'','1')         
         ,'') AS act_add        
  FROM t09_subact sub 
  LEFT JOIN t09_subact poa_ant ON(sub.t02_cod_proy = poa_ant.t02_cod_proy AND poa_ant.t02_version = _ver_last AND sub.t08_cod_comp = poa_ant.t08_cod_comp AND sub.t09_cod_act =poa_ant.t09_cod_act AND sub.t09_cod_sub = poa_ant.t09_cod_sub) 
  WHERE sub.t02_cod_proy = _proy 
    AND sub.t02_version  = _ver 
    AND sub.t08_cod_comp = _comp 
    AND sub.t09_cod_act  = _act 
  ORDER BY sub.t09_cod_sub
  ) AS temp;
END $$
DELIMITER ;


/*
// -------------------------------------------------->
// AQ 2.1 [03-05-2014 05:04]
// Obtiene la Meta Reprogramada de una Actividad
// --------------------------------------------------<
*/
DROP FUNCTION IF EXISTS `fn_get_mta_reprog`;

DELIMITER $$

CREATE FUNCTION `fn_get_mta_reprog`(_proy VARCHAR(10), _ver INT, _comp INT, _prod INT, _act INT, _anio INT, _inicial BOOLEAN) RETURNS INT
BEGIN

  DECLARE _meaa INT;
  DECLARE _meta_poa INT;
  DECLARE _mpar INT;
  DECLARE _mta_reprog INT;

  IF _anio = 1 AND _inicial THEN
    SELECT SUM(t09_mta) INTO _mta_reprog
            FROM t09_sub_act_mtas            
            WHERE t02_cod_proy = _proy 
             AND t02_version  = 1
             AND t08_cod_comp = _comp
             AND t09_cod_act  = _prod
             AND t09_cod_sub  = _act;
  ELSE
    -- meaa
    SELECT IFNULL(SUM(t09_sub_avanc),0) INTO _meaa  
    FROM t09_act_sub_mtas_inf             
    WHERE t02_cod_proy = _proy
    AND t08_cod_comp = _comp
    AND t09_cod_act  = _prod
    AND t09_cod_sub  = _act
    AND t09_sub_anio <= (_anio-1);

    -- meta_poa
    SELECT IFNULL(SUM(t09_mta), 0) INTO _meta_poa
    FROM t09_sub_act_mtas                     
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver
    AND t08_cod_comp = _comp 
    AND t09_cod_act  = _prod 
    AND t09_cod_sub  = _act 
    AND t09_anio=_anio;

    -- mpar
    SELECT IFNULL(t09_mta_proy, 0) INTO _mpar 
    FROM t09_subact 
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver 
    AND t08_cod_comp = _comp 
    AND t09_cod_act  = _prod
    AND t09_cod_sub  = _act 
    AND t02_version=_ver;

    SET _mta_reprog = _meaa + _meta_poa + _mpar;
     
  END IF;

  RETURN _mta_reprog;
END $$
DELIMITER ;