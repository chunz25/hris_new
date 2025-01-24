-- FUNCTION: eservices.sp_updpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying)

-- DROP FUNCTION eservices.sp_updpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying);

CREATE OR REPLACE FUNCTION eservices.sp_updpengajuancuti(
	v_pegawaiid character varying,
	v_nourut character varying,
	v_periode character varying,
	v_tglpermohonan character varying,
	v_atasan1 character varying,
	v_atasan2 character varying,
	v_pelimpahan character varying,
	v_status character varying,
	v_verifikasinotes character varying,
	v_files character varying,
	v_filestype character varying,
	v_hp character varying)
    RETURNS void
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2018-04-18
-- Example:
----------------------------------------------------------------------
BEGIN
	BEGIN
            UPDATE eservices.pengajuancuti SET
                periode = v_periode,
                tglpermohonan = TO_DATE(v_tglpermohonan,'DD/MM/YYYY'),
                tglupdate = CURRENT_DATE,
                atasan1 = v_atasan1,
                atasan2 = v_atasan2,
                pelimpahan = v_pelimpahan,
                status = v_status,
                verifikasinotes = v_verifikasinotes,
                files = v_files,
                filestype = v_filestype,
                hp = v_hp
            WHERE pegawaiid = v_pegawaiid AND nourut = CAST(v_nourut AS INT);

        EXCEPTION WHEN OTHERS THEN
        COMMIT;
	END;
END;

$BODY$;

ALTER FUNCTION eservices.sp_updpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying)
    OWNER TO postgres;

