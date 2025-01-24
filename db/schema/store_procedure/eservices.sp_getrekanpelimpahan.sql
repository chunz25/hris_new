-- FUNCTION: eservices.sp_getrekanpelimpahan(character varying, character varying, character varying, character varying, refcursor)

-- DROP FUNCTION eservices.sp_getrekanpelimpahan(character varying, character varying, character varying, character varying, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getrekanpelimpahan(
	v_pegawaiid character varying,
	v_satkerid character varying,
	v_keyword character varying,
	v_usergroupid character varying,
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
DECLARE 
	vd_lokasiid BIGINT;
    vd_gol INT;
    vd_golpeg INT;
    vd_nextloop INT := 0;
    vd_countdata INT := 0;

BEGIN    
    select loc.lokasiid, cast(l.gol as int), cast(l.gol as int) into vd_lokasiid, vd_gol, vd_golpeg
    from pegawai p
    left join vwjabatanterakhir vj on p.pegawaiid = vj.pegawaiid
    left join lokasi loc on vj.lokasikerja = loc.lokasiid
    left join level l on vj.levelid = l.levelid
    where p.pegawaiid = v_pegawaiid;
            
    begin 
        << loop_l >>
        while vd_nextloop = 0
        loop
            begin
                select count(*) into vd_countdata
                from pegawai p
                left join vwjabatanterakhir vj on p.pegawaiid = vj.pegawaiid
                left join level l on vj.levelid = l.levelid
                where vj.satkerid like v_satkerid || '%'
                and p.pegawaiid <> v_pegawaiid
                and cast(l.gol as int) = vd_gol;

                if vd_countdata > 0 then
                	vd_nextloop := 1;
                    vd_gol := vd_gol;
                else                	
                    if(select count(*) from satker where satkerid LIKE v_satkerid || '%' and LENGTH(satkerid) = LENGTH(v_satkerid) + 2) > 0 then
                    	vd_nextloop := 0;                        
                    else 
                    	vd_nextloop := 1;
                    end if;
                    vd_gol := (vd_gol + 1);
                end if;        	
            end;
        end loop;
    end;
	
    OPEN v_result FOR 
        SELECT a.*
        FROM (
            SELECT p.pegawaiid, p.nik, fnnamalengkap(p.namadepan, p.namabelakang) nama, j.jabatan,
                vj.satkerid, public.fnsatkerlevel(vj.satkerid,'2') AS divisi, 
                loc.lokasi, p.telp, p.hp, p.alamat, l.gol, vj.keteranganpegawai
            FROM pegawai p
            LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
            LEFT JOIN satker s ON vj.satkerid = s.satkerid
            LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
            LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
            LEFT JOIN level l ON vj.levelid = l.levelid            
            WHERE p.pegawaiid IN (
                CASE WHEN public.fnsatkerlevel2(v_satkerid) = v_pegawaiid THEN public.fnsatkerlevel2(public.fnsatkerlevel3(substring(v_satkerid,1,length(v_satkerid)-2))) ELSE public.fnsatkerlevel2(v_satkerid) END
            )

            UNION ALL

            SELECT p.pegawaiid, p.nik, fnnamalengkap(p.namadepan, p.namabelakang) nama, j.jabatan,
                vj.satkerid, public.fnsatkerlevel(vj.satkerid,'2') AS divisi, 
                loc.lokasi, p.telp, p.hp, p.alamat, l.gol, vj.keteranganpegawai
            FROM pegawai p
            LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
            LEFT JOIN satker s ON vj.satkerid = s.satkerid
            LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
            LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
            LEFT JOIN level l ON vj.levelid = l.levelid
            WHERE s.satkerid LIKE v_satkerid || '%'
            AND p.pegawaiid <> v_pegawaiid
            AND vj.lokasikerja = vd_lokasiid
            -- AND s.kepalaid IS NULL
            AND cast(l.gol as int) = (
				case when vd_golpeg >= 6 then cast(l.gol as int) else vd_gol end
			)                        
        ) a
        WHERE (a.nik = v_keyword OR UPPER(a.nama) LIKE '%' || UPPER(v_keyword) || '%')
        AND a.keteranganpegawai = '1'
        ORDER BY a.gol;  
        
    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_getrekanpelimpahan(character varying, character varying, character varying, character varying, refcursor)
    OWNER TO postgres;

