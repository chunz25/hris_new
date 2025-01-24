-- FUNCTION: eservices.sp_getverifikasicuti(character varying, character varying, character varying, character varying, character varying, integer, integer, refcursor, refcursor)

-- DROP FUNCTION eservices.sp_getverifikasicuti(character varying, character varying, character varying, character varying, character varying, integer, integer, refcursor, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getverifikasicuti(
	v_atasanid character varying,
	v_status character varying,
	v_mulai character varying,
	v_selesai character varying,
	v_satkerid character varying,
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
-- Modified date 2018-04-01
----------------------------------------------------------------------

DECLARE
	vd_select VARCHAR(4000) := '';
    vd_from VARCHAR(4000) := '';
	vd_where VARCHAR(4000) := '';    
    vd_sort VARCHAR(4000) := '';    

BEGIN
   vd_select := ' a.pengajuanid, 
        a.pegawaiid, a.nourut, a.periode, to_char(a.tglpermohonan,''DD/MM/YYYY'') tglpermohonan, 
        a.status statusid, b.status, a.verifikasinotes, a.hp,
        c.nik, fnnamalengkap(c.namadepan, c.namabelakang) nama, e.jabatan,
        a.pelimpahan pelimpahanid, h.nik pelimpahannik, fnnamalengkap(h.namadepan, h.namabelakang) pelimpahannama,
        a.atasan1 atasan1id, i.nik atasan1nik, fnnamalengkap(i.namadepan, i.namabelakang) atasan1nama,
        a.atasan2 atasan2id, j.nik atasan2nik, fnnamalengkap(j.namadepan, j.namabelakang) atasan2nama';
    
    vd_from := ' eservices.pengajuancuti a
        left join eservices.statusverifikasi b on a.status = b.statusid
        left join pegawai c on a.pegawaiid = c.pegawaiid
        left join vwjabatanterakhir d on c.pegawaiid = d.pegawaiid
        left join jabatan e on d.jabatanid = e.jabatanid
        left join satker f on d.satkerid = f.satkerid
        left join lokasi g on d.lokasikerja = g.lokasiid
        left join pegawai h on a.pelimpahan = h.pegawaiid
        left join pegawai i on a.atasan1 = i.pegawaiid
        left join pegawai j on a.atasan2 = j.pegawaiid
    ';
    
    vd_where := ' and d.satkerid like ''' || v_satkerid || ''' || ''%''';
    
    vd_where := vd_where || ' and a.status <> ''5''';
    
    vd_where := vd_where || ' and a.status in (
        case 
            when a.atasan1 is not null and a.atasan1 = '''||v_atasanid||''' then ''2'' 
            else  
                case 
                    when a.atasan1 is null and a.atasan2 = '''||v_atasanid||''' then ''2''                    
                    when a.atasan1 is not null and a.atasan2 = '''||v_atasanid||''' then ''3''
                end
        end
    )';
                
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

ALTER FUNCTION eservices.sp_getverifikasicuti(character varying, character varying, character varying, character varying, character varying, integer, integer, refcursor, refcursor)
    OWNER TO postgres;

