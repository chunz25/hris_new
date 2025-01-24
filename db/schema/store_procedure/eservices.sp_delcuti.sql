-- FUNCTION: eservices.sp_delcuti(character varying, integer)

-- DROP FUNCTION eservices.sp_delcuti(character varying, integer);

CREATE OR REPLACE FUNCTION eservices.sp_delcuti(
	v_pegawaiid character varying,
	v_nourut integer)
    RETURNS void
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2017-11-23
-- Example:
----------------------------------------------------------------------
BEGIN
	BEGIN
    	DELETE FROM eservices.cuti WHERE pegawaiid = v_pegawaiid AND nourut = v_nourut;

        EXCEPTION WHEN OTHERS THEN
        COMMIT;
	END;
END;

$BODY$;

ALTER FUNCTION eservices.sp_delcuti(character varying, integer)
    OWNER TO postgres;

