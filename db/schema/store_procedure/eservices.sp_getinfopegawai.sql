-- FUNCTION: eservices.sp_getinfopegawai(character varying, integer, refcursor)

-- DROP FUNCTION eservices.sp_getinfopegawai(character varying, integer, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getinfopegawai(
	v_pegawaiid character varying,
	v_tahun integer,
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
            loc.lokasi, p.telp, p.hp, p.alamat,
            hs.saldo AS jatahcuti,
            (COALESCE(hs.saldo,0) - COALESCE(c.jmlcuti,0)) AS sisacutithnini,
            CASE WHEN CURRENT_DATE > hs.tglexpired THEN 0 ELSE vct.sisacuti END AS sisacutithnlalu,
            COALESCE(c.jmlcuti, 0) lamacutithnini,
            pa.pegawaiid AS atasanid, pa.nik AS atasannik, fnnamalengkap(pa.namadepan, pa.namabelakang) AS atasannama, 
            public.fnsatkerlevel(va.satkerid,'2') AS atasandivisi, ja.jabatan AS atasanjabatan, loca.lokasi AS atasanlokasi,
            pa2.pegawaiid AS atasan2id, pa2.nik AS atasan2nik, fnnamalengkap(pa2.namadepan, pa2.namabelakang) AS atasan2nama,
            public.fnsatkerlevel(va2.satkerid,'2') AS atasan2divisi, ja2.jabatan AS atasan2jabatan, loca2.lokasi AS atasan2lokasi
        FROM pegawai p
        LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
        LEFT JOIN satker s ON vj.satkerid = s.satkerid
        LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
        LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid
        LEFT JOIN eservices.historysaldocuti hs ON p.pegawaiid = hs.pegawaiid AND hs.tahun = v_tahun
        LEFT JOIN (
            SELECT vc.pegawaiid, vc.tahun, SUM(vc.lama) jmlcuti
            FROM eservices.vwcuti vc
            WHERE vc.tahun = v_tahun AND vc.status IN('7','9','10','11','12','13','15') AND vc.jeniscutiid IN ('1','6') AND vc.pegawaiid = v_pegawaiid
            GROUP BY vc.pegawaiid, vc.tahun
        ) c ON p.pegawaiid = c.pegawaiid
        LEFT JOIN eservices.vwcutitahunanterakhir vct ON p.pegawaiid = vct.pegawaiid AND vct.tahun = (v_tahun-1) AND vct.status = '5'		
		LEFT JOIN satker ps ON ps.satkerid = (
			case 
				when s.kepalaid is null then substring(p.satkerid,1,length(p.satkerid)-2) 
				else p.satkerid
			end
		)
		LEFT JOIN pegawai pa ON pa.pegawaiid = ps.kepalaid
        LEFT JOIN vwjabatanterakhir va ON pa.pegawaiid = va.pegawaiid
        LEFT JOIN satker sa ON va.satkerid = sa.satkerid
        LEFT JOIN jabatan ja ON va.jabatanid = ja.jabatanid
        LEFT JOIN lokasi loca ON va.lokasikerja = loca.lokasiid		
		LEFT JOIN satker ps2 ON ps2.satkerid = (
			case 
				when sa.kepalaid is not null then substring(ps.satkerid,1,length(ps.satkerid)-2) 
				else ps.satkerid
			end
		)
		LEFT JOIN pegawai pa2 ON pa2.pegawaiid = ps2.kepalaid
        LEFT JOIN vwjabatanterakhir va2 ON pa2.pegawaiid = va2.pegawaiid
        LEFT JOIN satker sa2 ON va2.satkerid = sa2.satkerid
        LEFT JOIN jabatan ja2 ON va2.jabatanid = ja2.jabatanid
        LEFT JOIN lokasi loca2 ON va2.lokasikerja = loca2.lokasiid
        WHERE p.pegawaiid = v_pegawaiid;       
        
    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_getinfopegawai(character varying, integer, refcursor)
    OWNER TO postgres;

