/*
// -------------------------------------------------------------------------->
// AQ 2.1 [27-04-2014 02:25]
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
      temp.mfi, -- Meta Física Total Inicial
      temp.mpaa, -- Meta Proyectada del Año Anterior
      IF(_anio = 1, temp.mfi, temp.mftv) AS mftv, -- Meta Física Total Vigente (Nueva columna)
      temp.meaa, -- Meta Ejecutada Años Anteriores
      temp.meta_poa, -- Meta Total del Año X
      temp.mpar, -- Meta Proyectada Años Restantes
      temp.resumen,
      temp.t09_obs_mt,
      (IFNULL(temp.mfi,0) - IFNULL(temp.meaa,0)) AS mtpe, -- Meta Total por Ejecutar

      /* Meta Reprogramada: Diferencia si es el año 1
      -- POA 1 ok. Verificar en el POA 2 y los siguientes */
      IF(_anio = 1, IFNULL(temp.meaa,0) + IFNULL(temp.meta_poa,0) +  IFNULL(temp.mpar,0), temp.mprog) AS mprog, 
      -- -- (IFNULL(temp.meaa,0) + IFNULL(temp.meta_poa,0) +  IFNULL(temp.mpar,0)) AS mprog,
      -- temp.mprog,

      ( (IFNULL(temp.meaa,0) + IFNULL(temp.meta_poa,0) +  IFNULL(temp.mpar,0)) - IF(_anio = 1, temp.mfi, temp.mftv) ) AS mvar, -- Variac
      -- ( (IFNULL(temp.meaa,0) + IFNULL(temp.meta_poa,0) +  IFNULL(temp.mpar,0)) - temp.mfi ) AS mvar, -- Variac
      
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
         (SELECT fn_get_mta_reprog(sub.t02_cod_proy, (_ver_last), sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub, (_anio-1), FALSE)) AS mftv, 
         (SELECT fn_get_mta_reprog(sub.t02_cod_proy, _ver, sub.t08_cod_comp, sub.t09_cod_act, sub.t09_cod_sub, _anio, TRUE)) AS mprog,
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