-- FUNCTION: eservices.cekpengajuan(character varying, character varying, character varying, integer)

-- DROP FUNCTION eservices.cekpengajuan(character varying, character varying, character varying, integer);

CREATE OR REPLACE FUNCTION eservices.cekpengajuan(
	v_pegawaiid character varying,
	v_tglmulai character varying,
	v_tglselesai character varying,
	v_nourut integer)
    RETURNS integer
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2017-11-23
-- Example: SELECT eservices.cekpengajuan('000000001086', '15/03/2017', '16/03/2017',NULL) AS jml
----------------------------------------------------------------------

DECLARE
	vd_count INT;
BEGIN   
	
    IF v_nourut IS NULL THEN
        select COUNT(*) INTO vd_count
        from eservices.pengajuancuti a
        left join eservices.detailpengajuancuti b on a.pengajuanid = b.pengajuanid
        where a.pegawaiid = v_pegawaiid
        and (
            TO_DATE(v_tglmulai,'DD/MM/YYYY') BETWEEN b.tglmulai AND b.tglselesai OR
            TO_DATE(v_tglselesai, 'DD/MM/YYYY') BETWEEN b.tglmulai AND b.tglselesai OR
            b.tglmulai BETWEEN TO_DATE(v_tglmulai,'DD/MM/YYYY') AND TO_DATE(v_tglselesai, 'DD/MM/YYYY') OR
            b.tglselesai BETWEEN TO_DATE(v_tglmulai,'DD/MM/YYYY') AND TO_DATE(v_tglselesai,'DD/MM/YYYY')
        );
    ELSE 
        select COUNT(*) INTO vd_count
        from eservices.pengajuancuti a
        left join eservices.detailpengajuancuti b on a.pengajuanid = b.pengajuanid
        where a.pegawaiid = v_pegawaiid
        and (
            TO_DATE(v_tglmulai,'DD/MM/YYYY') BETWEEN b.tglmulai AND b.tglselesai OR
            TO_DATE(v_tglselesai, 'DD/MM/YYYY') BETWEEN b.tglmulai AND b.tglselesai OR            
            b.tglmulai BETWEEN TO_DATE(v_tglmulai,'DD/MM/YYYY') AND TO_DATE(v_tglselesai, 'DD/MM/YYYY') OR
            b.tglselesai BETWEEN TO_DATE(v_tglmulai,'DD/MM/YYYY') AND TO_DATE(v_tglselesai,'DD/MM/YYYY')
        )
        and nourut <> v_nourut;
    END IF;
    
    RETURN vd_count;                
END

$BODY$;

ALTER FUNCTION eservices.cekpengajuan(character varying, character varying, character varying, integer)
    OWNER TO postgres;

