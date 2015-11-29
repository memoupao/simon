/*
DA v2: 
Actualizacion de perfiles en aprobacion de desembolso.
*/


DROP procedure IF EXISTS `sp_upd_aprueba_desemb_parcial`;

DELIMITER $$

CREATE PROCEDURE `sp_upd_aprueba_desemb_parcial`(
			IN pProy VARCHAR(10),
			IN pAnio INT,
			IN pTrim INT,
			IN pMtoPlan DOUBLE,
			IN pIdAprob BIGINT,
			IN pNroAprob TINYINT,
			IN pMtoParApro DOUBLE,
			IN pAproMT  CHAR(1),
			IN pObsMT TEXT,
			IN pAproMF  CHAR(1),
			IN pObsMF TEXT,
			IN pAproCMT  CHAR(1),
			IN pObsCMT TEXT,
			IN pAproCMF  CHAR(1),
			IN pObsCMF TEXT,
			IN pUser VARCHAR(20))
BEGIN
DECLARE aFchAprob DATETIME DEFAULT NOW();
DECLARE aPerfil INT;
DECLARE aRowCount INT DEFAULT 0;
DECLARE aTotMtoAprob DOUBLE;
SELECT tipo_user INTO aPerfil FROM adm_usuarios WHERE coduser = pUser;
SELECT IFNULL(pIdAprob, 0) INTO pIdAprob;



/* IF pIdAprob > 0 AND aPerfil = 1 OR aPerfil = 6 THEN */
IF pIdAprob > 0 AND aPerfil = 13 THEN 
	UPDATE	t60_aprob_desemb_parcial
	SET 	t60_apro_mt = pAproMT,	
			t60_obs_mt = pObsMT,
			t60_fch_apro_mt = (CASE WHEN pAproMT = '1' THEN aFchAprob ELSE NULL END),
			t60_fch_apro_mf = (CASE WHEN pAproMT = '1' THEN aFchAprob ELSE NULL END),
			usr_actu = pUser,
			fch_actu = NOW()
	WHERE 	t60_id_aprob = pIdAprob AND t60_nro_aprob = pNroAprob;
	SELECT ROW_COUNT() INTO aRowCount;
END IF;
/*
IF pIdAprob > 0 AND aPerfil = 1 OR aPerfil = 8 THEN 
	UPDATE	t60_aprob_desemb_parcial
	SET 	t60_mto_par_aprob = pMtoParApro,
			t60_apro_mf = pAproMF,
			t60_obs_mf = pObsMF,
			t60_fch_apro_mf = (CASE WHEN pAproMF = '1' THEN aFchAprob ELSE NULL END),
			usr_actu = pUser,
			fch_actu = NOW()
	WHERE 	t60_id_aprob = pIdAprob AND t60_nro_aprob = pNroAprob;
	SELECT ROW_COUNT() INTO aRowCount;
END IF;
*/

/* IF pIdAprob > 0 AND aPerfil = 1 OR aPerfil = 4 THEN */
IF pIdAprob > 0 AND aPerfil = 15 THEN
	UPDATE	t60_aprob_desemb_parcial
	SET 	t60_apro_cmt = pAproCMT,
			t60_obs_cmt = pObsCMT,
			t60_fch_apro_cmt = (CASE WHEN pAproCMT = '1' THEN aFchAprob ELSE NULL END),
			t60_fch_apro_cmf = (CASE WHEN pAproCMT = '1' THEN aFchAprob ELSE NULL END),
			t60_fch_aprob = IF ((t60_apro_cmf = '1' AND pAproCMT = '1'), aFchAprob, t60_fch_aprob),
			usr_actu = pUser,
			fch_actu = NOW()
	WHERE 	t60_id_aprob = pIdAprob AND t60_nro_aprob = pNroAprob;
	SELECT ROW_COUNT() INTO aRowCount;
END IF;
/*
IF pIdAprob > 0 AND aPerfil = 1 OR aPerfil = 9 THEN 
	UPDATE	t60_aprob_desemb_parcial
	SET 	t60_mto_par_aprob = pMtoParApro,
			t60_apro_cmf = pAproCMF,
			t60_obs_cmf = pObsCMF,
			t60_fch_apro_cmf = (CASE WHEN pAproCMF = '1' THEN aFchAprob ELSE NULL END),
			t60_fch_aprob = IF ((t60_apro_cmt = '1' AND pAproCMF = '1'), aFchAprob, t60_fch_aprob),
			usr_actu = pUser,
			fch_actu = NOW()
	WHERE 	t60_id_aprob = pIdAprob AND t60_nro_aprob = pNroAprob;
	SELECT ROW_COUNT() INTO aRowCount;
END IF;
*/


IF (aRowCount = 0) THEN
	IF (pAproCMT != '1') THEN
		SET aFchAprob = NULL;
	END IF;
	
	IF (!EXISTS(SELECT * FROM t60_aprobacion_desemb WHERE t60_id_aprob = pIdAprob)) THEN
		INSERT INTO t60_aprobacion_desemb 
			(t02_cod_proy, 		t60_anio,		t60_trim, 
			t60_mto_plan,		t60_mto_aprob,	t60_apro_mt, 
			t60_obs_mt,			t60_apro_mf,	t60_obs_mf, 
			usr_crea,			fch_crea,		est_audi)
			VALUES
			(pProy,				pAnio,			pTrim, 
			pMtoPlan,			pMtoParApro,	pAproCMT, 
			pObsCMT, 			pAproCMF, 		pObsCMT,
			pUser,				NOW(), 			'1');
		SELECT LAST_INSERT_ID() INTO pIdAprob;
	END IF;
	IF IFNULL(pNroAprob, 0) = 0 THEN 
		SELECT IFNULL(MAX(t60_nro_aprob), 0) + 1 
		INTO pNroAprob 
		FROM t60_aprob_desemb_parcial
		WHERE t60_id_aprob = pIdAprob;
	END IF;
	
	IF (pNroAprob <= 3) THEN
		IF (pNroAprob = 3) THEN
			SELECT	t1.t60_mto_plan - t2.mto_acum AS saldo_aprob
			INTO	pMtoParApro
			FROM	t60_aprobacion_desemb t1 
			JOIN	(SELECT t60_id_aprob, SUM(t60_mto_par_aprob) AS mto_acum
						FROM t60_aprob_desemb_parcial
						GROUP BY t60_id_aprob) t2 
					ON (t1.t60_id_aprob = t2.t60_id_aprob)
			WHERE t1.t60_id_aprob = pIdAprob;		
		END IF;
		
		INSERT INTO t60_aprob_desemb_parcial (
			t60_id_aprob,	t60_nro_aprob,	t60_mto_par_aprob,
			t60_apro_mt,	t60_obs_mt,		t60_fch_apro_mt,
			t60_apro_mf,	t60_obs_mf,		t60_fch_apro_mf,
			t60_apro_cmt,	t60_obs_cmt,	t60_fch_apro_cmt,
			t60_apro_cmf,	t60_obs_cmf,	t60_fch_apro_cmf,
			usr_crea,		fch_crea,		est_audi)
			VALUES (
			pIdAprob,		pNroAprob,		pMtoParApro,
			pAproMT,		pObsMT,			(CASE WHEN pAproMT = '1' THEN CURDATE() ELSE NULL END),
			pAproMF,		pObsMF,			(CASE WHEN pAproMF = '1' THEN CURDATE() ELSE NULL END),
			pAproCMT,		pObsCMT,		(CASE WHEN pAproCMT = '1' THEN CURDATE() ELSE NULL END),
			pAproCMF,		pObsCMF,		(CASE WHEN pAproCMF = '1' THEN CURDATE() ELSE NULL END),
			pUser, 			NOW(),			'1');
		SELECT ROW_COUNT() INTO aRowCount ;
	ELSE
		SELECT "Numero maximo de Aprobaciones de Desembolsos alcanzado (3)." AS errorMsg;
	END IF;
ELSE



	SELECT t60_apro_cmt, t60_obs_cmt, t60_apro_cmf, t60_obs_cmf
	INTO pAproCMT, pObsCMT, pAproCMF, pObsCMF
	FROM t60_aprob_desemb_parcial
	WHERE t60_id_aprob = pIdAprob AND t60_nro_aprob = pNroAprob;
	
	IF (pAproCMT = '1') THEN
					
		SELECT SUM(t60_mto_par_aprob) INTO aTotMtoAprob
		FROM t60_aprob_desemb_parcial
		WHERE t60_id_aprob = pIdAprob;
		
		UPDATE	t60_aprobacion_desemb
		SET		t60_mto_aprob	= aTotMtoAprob,
				t60_fch_aprob	= CURDATE(),
				t60_is_desemb	= '0',
				t60_apro_mt		= '1',
				t60_obs_mt		= pObsCMT,
				t60_apro_mf		= '1',
				t60_obs_mf		= pObsCMF,
				usr_actu		= pUser,
				fch_actu		= NOW()
		WHERE	t60_id_aprob = pIdAprob;
		
	END IF;
END IF ;
SELECT aRowCount AS numRows, pIdAprob AS codAprob,  pNroAprob AS nroAprob;
END$$

DELIMITER ;



