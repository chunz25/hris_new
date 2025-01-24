-- FUNCTION: eservices.sp_generatesaldocuti(character varying, integer, integer, refcursor)

-- DROP FUNCTION eservices.sp_generatesaldocuti(character varying, integer, integer, refcursor);

CREATE OR REPLACE FUNCTION eservices.sp_generatesaldocuti(
	v_pegawaiid character varying,
	v_tahun integer,
	v_cutibersama integer,
	v_result refcursor)
    RETURNS refcursor
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

----------------------------------------------------------------------
-- Modified by Ary Kurniadi 
-- Modified date 2018-04-20
-- Example:
----------------------------------------------------------------------
DECLARE
	vd_jatahdefault INT;
    vd_saldoakhir INT;
    vd_cek1 INT;
    vd_cek2 INT;
    vd_sisa1 INT;
    vd_statuspeg INT;
    vd_history1 INT;
    vd_history2 INT;
    vd_saldo1 INT;
    vd_lama1 INT;
    vd_locked VARCHAR(1);
    vd_cuber1 INT;

BEGIN
	select count(pegawaiid) into vd_cek1 from eservices.historysaldocuti where pegawaiid = v_pegawaiid and tahun = v_tahun;
    select count(pegawaiid) into vd_cek2 from eservices.historysaldocuti where pegawaiid = v_pegawaiid and tahun = v_tahun-1;
	
    -- Ambil Cuti Bersama di Tahun - 1
	select cutibersama into vd_cuber1 from eservices.historysaldocuti where pegawaiid = v_pegawaiid and tahun = v_tahun-1;
    
    -- Ambil Jatah Default Cuti Tahunan
    SELECT b.jatahcuti INTO vd_jatahdefault
    FROM eservices.jeniscuti a
    LEFT JOIN eservices.detailjeniscuti b ON a.jeniscutiid = b.jeniscutiid
    WHERE b.detailjeniscutiid = 1;
    
    -- History saldo awal cuti tahun n-1
    select coalesce(hs.saldo,0) into vd_saldo1
    from pegawai p
    left join eservices.historysaldocuti hs on p.pegawaiid = hs.pegawaiid and hs.tahun = v_tahun-1
    where p.pegawaiid = v_pegawaiid;
	
    -- hitung jumlah lama cuti tahunan yang diambil pada tahun n-1    
    SELECT SUM(vc.lama) INTO vd_lama1
    FROM eservices.vwcuti vc
    WHERE vc.status = '5' AND vc.jeniscutiid = '1' AND vc.tahun = CAST(v_tahun AS INT)-1 AND vc.pegawaiid = v_pegawaiid
    GROUP BY vc.pegawaiid, vc.tahun;
	
    -- hitung jumlah sisa cuti pada tahun n-1
    vd_sisa1 := (vd_saldo1 - vd_cuber1) - vd_lama1;
    
    -- cek jumlah data riwayat cuti tahun n-1
    SELECT COUNT(*) INTO vd_history1
    FROM eservices.vwcuti vc
    WHERE vc.status = '5' AND vc.jeniscutiid = '1' AND vc.tahun = CAST(v_tahun AS INT)-1 AND vc.pegawaiid = v_pegawaiid
    GROUP BY vc.pegawaiid, vc.tahun;

    -- hitung saldo cuti
    IF vd_history1 = 0 THEN
    	vd_saldoakhir := vd_jatahdefault;
    ELSE
    	vd_saldoakhir := (vd_jatahdefault - v_cutibersama) + vd_sisa1;
    END IF;
    
    IF vd_cek2 <> 0 THEN
    	IF vd_cek1 = 0 THEN
        	INSERT INTO eservices.historysaldocuti(pegawaiid, tahun, saldo, locked, jatahawal, cutibersama) 
            VALUES(v_pegawaiid,v_tahun,vd_saldoakhir,null,vd_jatahdefault,v_cutibersama);
        ELSE
        	UPDATE eservices.historysaldocuti SET saldo = vd_saldoakhir, cutibersama = v_cutibersama WHERE pegawaiid = v_pegawaiid AND tahun = v_tahun;
        END IF;        
    END IF;
            
	OPEN v_result FOR 
		SELECT vd_cek1, vd_cek2, vd_jatahdefault, vd_saldo1, vd_lama1, vd_cuber1, vd_sisa1, vd_history1, vd_saldoakhir;

    RETURN v_result;            
END

$BODY$;

ALTER FUNCTION eservices.sp_generatesaldocuti(character varying, integer, integer, refcursor)
    OWNER TO postgres;

