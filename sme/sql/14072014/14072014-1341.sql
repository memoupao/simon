/*
// -------------------------------------------------->
// AQ 2.0 [23-04-2014 18:54]
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
    SELECT t09_mta_proy INTO _mpar 
    FROM t09_subact 
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver 
    AND t08_cod_comp = _comp 
    AND t09_cod_act  = _prod
    AND t09_cod_sub  = _act 
    AND t02_version=_ver;

    SET _mta_reprog = _meaa + _meta_poa + _mpar;
     
  END IF;

  -- Para actividades nuevas
  IF _mta_reprog IS NULL THEN
    SELECT t09_mta_repro INTO _mta_reprog 
    FROM t09_subact 
    WHERE t02_cod_proy = _proy 
    AND t02_version  = _ver
    AND t08_cod_comp = _comp 
    AND t09_cod_act  = _prod 
    AND t09_cod_sub  = _act;
  END IF;

  RETURN _mta_reprog;
END $$
DELIMITER ;