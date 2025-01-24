-- FUNCTION: eservices.sp_updstatusverifikasi(character varying, integer, character varying, character varying, character varying)

-- DROP FUNCTION eservices.sp_updstatusverifikasi(character varying, integer, character varying, character varying, character varying);

CREATE OR REPLACE FUNCTION eservices.sp_updstatusverifikasi(
	v_pegawaiid character varying,
	v_nourut integer,
	v_status character varying,
	v_notes character varying,
	v_hrdid character varying)
    RETURNS void
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2018-04-05
-- Example:
----------------------------------------------------------------------
BEGIN
	BEGIN
    
        UPDATE eservices.pengajuancuti SET status = v_status,
            tglupdate = CURRENT_DATE,
            verifikasinotes = v_notes,
            hrd = v_hrdid
            WHERE pegawaiid = v_pegawaiid AND nourut = v_nourut;

        EXCEPTION WHEN OTHERS THEN
        COMMIT;
	END;
END;

$BODY$;

ALTER FUNCTION eservices.sp_updstatusverifikasi(character varying, integer, character varying, character varying, character varying)
    OWNER TO postgres;

