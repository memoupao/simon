/*
// -------------------------------------------------->
// AQ 2.0 [19-01-2014 15:42]
// Actividades del POA
// --------------------------------------------------<
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

SELECT  temp.t02_cod_proy,
    temp.t02_version,
    temp.codigo,
    temp.comp,
    temp.subact, 
    temp.descripcion, 
    temp.um, 
    temp.meta_acum,
    
    temp.mfi,
    temp.mpaa,
    temp.meaa,
    temp.meta_poa,
    temp.mpar,
    temp.resumen,
    temp.t09_obs_mt,
    
    (IFNULL(temp.mfi,0) - IFNULL(temp.meaa,0)) AS mtpe,
    (IFNULL(temp.meaa,0) + IFNULL(temp.meta_poa,0) +  IFNULL(temp.mpar,0)) AS mprog,
    ( (IFNULL(temp.meaa,0) + IFNULL(temp.meta_poa,0) +  IFNULL(temp.mpar,0)) - temp.mfi ) AS mvar,
    temp.act_add
FROM
(
SELECT     sub.t02_cod_proy, 
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
         THEN -- poa_ant.t09_mta_repro  
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
         END) AS mpaa, -- Meta Proyectada para el año Anterior
       IFNULL((
         SELECT SUM(inf.t09_sub_avanc) 
           FROM t09_act_sub_mtas_inf inf
          WHERE inf.t02_cod_proy = sub.t02_cod_proy
            AND inf.t08_cod_comp = sub.t08_cod_comp
            AND inf.t09_cod_act  = sub.t09_cod_act
            AND inf.t09_cod_sub  = sub.t09_cod_sub
            AND inf.t09_sub_anio <= (_anio-1)
       ),0) AS meaa, -- Meta Ejecutada en el Año Anterior
       (
        SELECT SUM(mta.t09_mta)
          FROM  t09_sub_act_mtas mta
         WHERE mta.t02_cod_proy = sub.t02_cod_proy 
           AND mta.t02_version  = sub.t02_version
           AND mta.t08_cod_comp = sub.t08_cod_comp 
           AND mta.t09_cod_act  = sub.t09_cod_act 
           AND mta.t09_cod_sub  = sub.t09_cod_sub 
           AND mta.t09_anio=_anio
       ) AS meta_poa, -- Meta del POA
       sub.t09_mta_proy AS mpar , -- Meta Proyectada para los Años restantes
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