-- FUNCTION: eservices.sp_addpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, refcursor)

-- DROP FUNCTION eservices.sp_addpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_addpengajuancuti(
	v_pegawaiid character varying,
	v_periode character varying,
	v_tglpermohonan character varying,
	v_atasan1 character varying,
	v_atasan2 character varying,
	v_pelimpahan character varying,
	v_status character varying,
	v_verifikasinotes character varying,
	v_files character varying,
	v_filestype character varying,
	v_hp character varying,
	v_result refcursor)
    RETURNS refcursor
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2018-03-20
-- Example:
----------------------------------------------------------------------
DECLARE
	vd_pengajuanid BIGINT;
    vd_nourut BIGINT;

BEGIN
	SELECT COALESCE(MAX(pengajuanid)+1,1) INTO vd_pengajuanid FROM eservices.pengajuancuti;
    SELECT COALESCE(MAX(nourut)+1,1) INTO vd_nourut FROM eservices.pengajuancuti WHERE pegawaiid = v_pegawaiid;
    
    INSERT INTO eservices.pengajuancuti(
        pengajuanid, pegawaiid, nourut, periode, tglpermohonan, tglupdate, atasan1, atasan2, pelimpahan,
        status, verifikasinotes, files, filestype, hp
    ) VALUES (
    	vd_pengajuanid, v_pegawaiid, vd_nourut, v_periode, TO_DATE(v_tglpermohonan, 'DD/MM/YYYY'), CURRENT_DATE, v_atasan1, v_atasan2, v_pelimpahan,
        v_status, v_verifikasinotes, v_files, v_filestype, v_hp
    );

	OPEN v_result FOR 
       SELECT vd_pengajuanid as pengajuanid, vd_nourut as nourut;
        
    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_addpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, refcursor)
    OWNER TO postgres;

