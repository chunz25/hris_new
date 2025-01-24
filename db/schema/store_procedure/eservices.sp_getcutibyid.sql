-- FUNCTION: eservices.sp_getcutibyid(character varying, character varying, character varying, refcursor)

-- DROP FUNCTION eservices.sp_getcutibyid(character varying, character varying, character varying, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_getcutibyid(
	v_pegawaiid character varying,
	v_nourut character varying,
	v_tahun character varying,
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
        select a.pengajuanid, a.pegawaiid, a.nourut,
            c.nik, fnnamalengkap(c.namadepan, c.namabelakang) nama,    
            e.satkerid, public.fnsatkerlevel(e.satkerid,'2') AS divisi, e.jabatanid, i.jabatan, j.lokasi, c.telp, a.hp, c.alamat,
            TO_CHAR(a.tglpermohonan, 'DD/MM/YYYY') tglpermohonan,
            a.status statusid, b.status,    
            a.pelimpahan pelimpahanid, k.nik pelimpahannik, fnnamalengkap(k.namadepan, k.namabelakang) pelimpahannama,    
            n.jabatan pelimpahanjabatan, public.fnsatkerlevel(l.satkerid,'2') AS pelimpahansatker, o.lokasi pelimpahanlokasi, k.hp pelimpahanhp,    
            a.atasan1 atasanid, p.nik atasannik, fnnamalengkap(p.namadepan, p.namabelakang) atasannama,
            s.jabatan atasanjabatan, public.fnsatkerlevel(q.satkerid,'2') AS atasansatker, t.lokasi atasanlokasi,            
            a.atasan2 atasan2id, u.nik atasan2nik, fnnamalengkap(u.namadepan, u.namabelakang) atasan2nama,
            x.jabatan atasan2jabatan, public.fnsatkerlevel(v.satkerid,'2') AS atasan2satker, y.lokasi atasan2lokasi,            
            d.saldo AS jatahcuti,
            (d.saldo - COALESCE(f.jmlcuti,0)) AS sisacutithnini,
            CASE WHEN CURRENT_DATE > d.tglexpired THEN 0 ELSE g.sisacuti END AS sisacutithnlalu,
            a.files, a.filestype, a.verifikasinotes
        from eservices.pengajuancuti a
        left join eservices.statusverifikasi b on a.status = b.statusid
        left join pegawai c on a.pegawaiid = c.pegawaiid
        left join eservices.historysaldocuti d on a.pegawaiid = d.pegawaiid and d.tahun = cast(v_tahun as int)
        left join vwjabatanterakhir e on c.pegawaiid = e.pegawaiid
        left join (
            SELECT vc.pegawaiid, vc.tahun, SUM(vc.lama) jmlcuti
            FROM eservices.vwcuti vc
            WHERE vc.tahun = CAST(v_tahun AS INT) AND vc.status IN('7','9','10','11','12','13','15') AND vc.jeniscutiid IN('1','6') AND vc.pegawaiid = v_pegawaiid
            GROUP BY vc.pegawaiid, vc.tahun
        ) f on a.pegawaiid = f.pegawaiid
        left join eservices.vwcutitahunanterakhir g ON a.pegawaiid = g.pegawaiid AND g.tahun = (CAST(v_tahun AS INT)-1) AND g.status = '7'
        left join satker h ON e.satkerid = h.satkerid
        left join jabatan i ON e.jabatanid = i.jabatanid
        left join lokasi j ON e.lokasikerja = j.lokasiid
        left join pegawai k ON a.pelimpahan = k.pegawaiid
        left join vwjabatanterakhir l ON k.pegawaiid = l.pegawaiid
        left join satker m ON l.satkerid = m.satkerid
        left join jabatan n ON l.jabatanid = n.jabatanid
        left join lokasi o ON l.lokasikerja = o.lokasiid
        left join pegawai p ON p.pegawaiid = a.atasan1
        left join vwjabatanterakhir q ON p.pegawaiid = q.pegawaiid
        left join satker r ON q.satkerid = r.satkerid
        left join jabatan s ON q.jabatanid = s.jabatanid
        left join lokasi t ON q.lokasikerja = t.lokasiid
        left join pegawai u ON u.pegawaiid = a.atasan2
        left join vwjabatanterakhir v ON u.pegawaiid = v.pegawaiid
        left join satker w ON v.satkerid = w.satkerid
        left join jabatan x ON v.jabatanid = x.jabatanid
        left join lokasi y ON v.lokasikerja = y.lokasiid
        where a.pegawaiid = v_pegawaiid and a.nourut = CAST(v_nourut AS INT);        

    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_getcutibyid(character varying, character varying, character varying, refcursor)
    OWNER TO postgres;

