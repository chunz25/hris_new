-- FUNCTION: eservices.sp_getlistcutipeg(character varying, character varying, character varying, character varying, character varying, character varying, integer, integer, refcursor, refcursor)

-- DROP FUNCTION eservices.sp_getlistcutipeg(character varying, character varying, character varying, character varying, character varying, character varying, integer, integer, refcursor, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getlistcutipeg(
	v_pegawaiid character varying,
	v_status character varying,
	v_mulai character varying,
	v_selesai character varying,
	v_satkerid character varying,
	v_keyword character varying,
	v_start integer,
	v_limit integer,
	v_count refcursor,
	v_result refcursor)
    RETURNS SETOF refcursor 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2018-03-26
----------------------------------------------------------------------

DECLARE
	vd_select VARCHAR(4000) := '';
    vd_from VARCHAR(4000) := '';
	vd_where VARCHAR(4000) := '';    
    vd_sort VARCHAR(4000) := '';    

BEGIN
    vd_select := ' a.pengajuanid, a.pegawaiid, a.nourut, b.nik, fnnamalengkap(b.namadepan, b.namabelakang) nama,
        d.jabatan, 
        public.fnsatkerlevel(c.satkerid,''1'') AS direktorat,
        public.fnsatkerlevel(c.satkerid,''2'') AS divisi,
        public.fnsatkerlevel(c.satkerid,''3'') AS departemen,
        public.fnsatkerlevel(c.satkerid,''4'') AS seksi,
        public.fnsatkerlevel(c.satkerid,''5'') AS subseksi,        
        a.periode, to_char(a.tglpermohonan,''DD/MM/YYYY'') tglpermohonan, a.status statusid, g.status, a.verifikasinotes,
        h.pegawaiid atasan1id, fnnamalengkap(h.namadepan, h.namabelakang) atasan1nama,
        i.pegawaiid atasan2id, fnnamalengkap(i.namadepan, i.namabelakang) atasan2nama,
        j.pegawaiid pelimpahanid, fnnamalengkap(j.namadepan, j.namabelakang) pelimpahannama, a.files, a.filestype';
    
    vd_from := ' eservices.pengajuancuti a
        left join pegawai b on a.pegawaiid = b.pegawaiid
        left join vwjabatanterakhir c on b.pegawaiid = c.pegawaiid
        left join jabatan d on c.jabatanid = d.jabatanid
        left join satker e on c.satkerid = e.satkerid
        left join lokasi f on c.lokasikerja = f.lokasiid
        left join eservices.statusverifikasi g on a.status = g.statusid
        left join pegawai h on a.atasan1 = h.pegawaiid
        left join pegawai i on a.atasan2 = i.pegawaiid
        left join pegawai j on a.pelimpahan = j.pegawaiid
    ';
    
    vd_where := ' AND c.satkerid LIKE ''' || v_satkerid || ''' || ''%''';
    
    IF v_pegawaiid <> '' OR v_pegawaiid IS NOT NULL THEN
    	vd_where := vd_where || ' AND a.pegawaiid = ''' || v_pegawaiid || '''';
    END IF;
    
    IF v_status <> '' OR v_status IS NOT NULL THEN
    	vd_where := vd_where || ' AND a.status = CAST(' || v_status || ' AS INT)';
    END IF;
    
    IF v_mulai IS NOT NULL AND v_selesai IS NOT NULL THEN
    	vd_where := vd_where || ' AND a.tglpermohonan BETWEEN TO_DATE(''' || v_mulai || ''',''DD/MM/YYYY'') AND TO_DATE(''' || v_selesai || ''',''DD/MM/YYYY'')';
    END IF;
    
    IF v_keyword IS NOT NULL AND v_keyword <> '' THEN
    	vd_where := vd_where || ' AND (b.nik = ''' || v_keyword || ''' OR UPPER(b.namadepan) LIKE ''%' || UPPER(v_keyword) || '%'') ';
    END IF;
        
    vd_sort := '
    	a.tglpermohonan DESC
    ';
        
	OPEN v_result FOR EXECUTE
    	'SELECT ' || vd_select || ' FROM ' || vd_from || ' WHERE 1=1 ' || vd_where || ' ORDER BY ' || vd_sort || ' OFFSET ' || v_start || ' LIMIT ' || v_limit;

    RETURN NEXT v_result;            

	OPEN v_count FOR EXECUTE
    	'SELECT COUNT(*) ' || ' FROM ' || vd_from || ' WHERE 1=1 ' || vd_where;
        
    RETURN NEXT v_count;            

END

$BODY$;

ALTER FUNCTION eservices.sp_getlistcutipeg(character varying, character varying, character varying, character varying, character varying, character varying, integer, integer, refcursor, refcursor)
    OWNER TO postgres;

