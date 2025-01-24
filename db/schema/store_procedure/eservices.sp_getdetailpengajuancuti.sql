-- FUNCTION: eservices.sp_getdetailpengajuancuti(character varying, refcursor)

-- DROP FUNCTION eservices.sp_getdetailpengajuancuti(character varying, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getdetailpengajuancuti(
	v_pengajuanid character varying,
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

BEGIN
	OPEN v_result FOR 
        select a.detailpengajuanid, a.jeniscutiid, b.jeniscuti, a.detailjeniscutiid, c.detailjeniscuti,
            to_char(a.tglmulai,'DD/MM/YYYY') tglmulai, to_char(a.tglselesai, 'DD/MM/YYYY') tglselesai, 
            a.lama, a.satuan, a.sisacuti, 
            case when a.jeniscutiid = '3' then c.detailjeniscuti else a.alasancuti end alasancuti            
        from eservices.detailpengajuancuti a
        left join eservices.jeniscuti b on a.jeniscutiid = b.jeniscutiid
        left join eservices.detailjeniscuti c on a.detailjeniscutiid = c.detailjeniscutiid
        where a.pengajuanid = CAST(v_pengajuanid AS BIGINT)
        order by a.tglmulai asc;        

    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_getdetailpengajuancuti(character varying, refcursor)
    OWNER TO postgres;

