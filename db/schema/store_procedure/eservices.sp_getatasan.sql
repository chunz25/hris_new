-- FUNCTION: eservices.sp_getatasan(character varying, character varying, refcursor)

-- DROP FUNCTION eservices.sp_getatasan(character varying, character varying, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getatasan(
	v_satkerid character varying,
	v_keyword character varying,
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
        SELECT p.pegawaiid, p.nik, fnnamalengkap(p.namadepan, p.namabelakang) nama, j.jabatan,
            public.fnsatkerlevel(vj.satkerid,'2') AS divisi, 
            loc.lokasi, p.telp, p.hp, p.alamat
        FROM pegawai p
        LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
        LEFT JOIN satker s ON vj.satkerid = s.satkerid
        LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
        LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
        WHERE s.satkerid LIKE v_satkerid || '%'
        AND (p.nik = v_keyword OR UPPER(p.namadepan) LIKE '%' || UPPER(v_keyword) || '%');
        
    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_getatasan(character varying, character varying, refcursor)
    OWNER TO postgres;

