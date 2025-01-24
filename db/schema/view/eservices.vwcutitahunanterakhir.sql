-- View: eservices.vwcutitahunanterakhir

-- DROP VIEW eservices.vwcutitahunanterakhir;

CREATE OR REPLACE VIEW eservices.vwcutitahunanterakhir AS
 SELECT c.ranking,
    c.pengajuanid,
    c.detailpengajuanid,
    c.pegawaiid,
    c.nourut,
    c.alasancuti,
    c.tglpermohonan,
    c.atasan1,
    c.atasan2,
    c.pelimpahan,
    c.status,
    c.verifikasinotes,
    c.jeniscutiid,
    c.detailjeniscutiid,
    c.tahun,
    c.tglmulai,
    c.tglselesai,
    c.lama,
    c.satuan,
    c.sisacuti
   FROM ( SELECT pc.pengajuanid,
            pc.pegawaiid,
            pc.nourut,
            dc.alasancuti,
            pc.tglpermohonan,
            pc.atasan1,
            pc.atasan2,
            pc.pelimpahan,
            pc.status,
            pc.verifikasinotes,
            dc.detailpengajuanid,
            dc.jeniscutiid,
            dc.detailjeniscutiid,
            date_part('year'::text, dc.tglmulai) AS tahun,
            dc.tglmulai,
            dc.tglselesai,
            dc.lama,
            dc.satuan,
            dc.sisacuti,
            rank() OVER (PARTITION BY pc.pegawaiid, (date_part('year'::text, dc.tglmulai)), dc.jeniscutiid ORDER BY dc.tglmulai DESC) AS ranking
           FROM eservices.detailpengajuancuti dc
             LEFT JOIN eservices.pengajuancuti pc ON dc.pengajuanid = pc.pengajuanid
          WHERE dc.jeniscutiid::text = '1'::text) c
  WHERE c.ranking = 1;

ALTER TABLE eservices.vwcutitahunanterakhir
    OWNER TO postgres;


