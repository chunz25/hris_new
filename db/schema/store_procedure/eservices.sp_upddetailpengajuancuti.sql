-- FUNCTION: eservices.sp_upddetailpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying)

-- DROP FUNCTION eservices.sp_upddetailpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying);

CREATE OR REPLACE FUNCTION eservices.sp_upddetailpengajuancuti(
	v_pengajuanid character varying,
	v_detailpengajuanid character varying,
	v_jeniscutiid character varying,
	v_detailjeniscutiid character varying,
	v_tglmulai character varying,
	v_tglselesai character varying,
	v_lama character varying,
	v_satuan character varying,
	v_sisacuti character varying)
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
DECLARE 
	vd_detailpengajuanid BIGINT;

BEGIN
	BEGIN
        UPDATE eservices.detailpengajuancuti SET
            jeniscutiid = v_jeniscutiid, 
            detailjeniscutiid = CAST(v_detailjeniscutiid AS INT), 
            tglmulai = TO_DATE(v_tglmulai, 'DD/MM/YYYY'), 
            tglselesai = TO_DATE(v_tglselesai, 'DD/MM/YYYY'), 
            lama = CAST(v_lama AS INT), 
            satuan = v_satuan, 
            sisacuti = CAST(v_sisacuti AS INT)
        WHERE detailpengajuanid = CAST(v_detailpengajuanid AS INT);
        
        EXCEPTION WHEN OTHERS THEN
        COMMIT;
	END;
END;

$BODY$;

ALTER FUNCTION eservices.sp_upddetailpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying)
    OWNER TO postgres;

