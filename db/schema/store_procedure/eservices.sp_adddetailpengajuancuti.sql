-- FUNCTION: eservices.sp_adddetailpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying)

-- DROP FUNCTION eservices.sp_adddetailpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying);

CREATE OR REPLACE FUNCTION eservices.sp_adddetailpengajuancuti(
	v_pengajuanid character varying,
	v_jeniscutiid character varying,
	v_detailjeniscutiid character varying,
	v_tglmulai character varying,
	v_tglselesai character varying,
	v_lama character varying,
	v_satuan character varying,
	v_sisacuti character varying,
	v_alasan character varying)
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
    SELECT COALESCE(MAX(detailpengajuanid)+1,1) INTO vd_detailpengajuanid FROM eservices.detailpengajuancuti;

    INSERT INTO eservices.detailpengajuancuti(
        detailpengajuanid, pengajuanid, jeniscutiid, detailjeniscutiid, 
        tglmulai, tglselesai, lama, satuan, sisacuti, alasancuti
    ) VALUES(
        vd_detailpengajuanid,CAST(v_pengajuanid AS BIGINT),v_jeniscutiid,CAST(v_detailjeniscutiid AS INT),
        TO_DATE(v_tglmulai,'DD/MM/YYYY'),TO_DATE(v_tglselesai,'DD/MM/YYYY'),CAST(v_lama AS INT),v_satuan,CAST(v_sisacuti AS INT), v_alasan
    );

END;

$BODY$;

ALTER FUNCTION eservices.sp_adddetailpengajuancuti(character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying, character varying)
    OWNER TO postgres;

