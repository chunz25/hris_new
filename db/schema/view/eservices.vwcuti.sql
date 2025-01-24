-- View: eservices.vwcuti

-- DROP VIEW eservices.vwcuti;

CREATE OR REPLACE VIEW eservices.vwcuti AS
 SELECT c.pengajuanid,
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
            dc.sisacuti
           FROM eservices.detailpengajuancuti dc
             LEFT JOIN eservices.pengajuancuti pc ON dc.pengajuanid = pc.pengajuanid
          WHERE date_part('year'::text, dc.tglmulai) = date_part('year'::text, dc.tglselesai)) c;

ALTER TABLE eservices.vwcuti
    OWNER TO postgres;


