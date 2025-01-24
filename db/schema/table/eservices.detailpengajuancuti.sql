-- Table: eservices.detailpengajuancuti

-- DROP TABLE eservices.detailpengajuancuti;

CREATE TABLE eservices.detailpengajuancuti
(
    detailpengajuanid bigint NOT NULL,
    pengajuanid bigint,
    jeniscutiid character varying(10) COLLATE pg_catalog."default",
    detailjeniscutiid integer,
    tglmulai date,
    tglselesai date,
    lama integer,
    satuan character varying(150) COLLATE pg_catalog."default",
    sisacuti integer,
    alasancuti character varying COLLATE pg_catalog."default",
    CONSTRAINT detailpengajuancuti_pkey PRIMARY KEY (detailpengajuanid)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.detailpengajuancuti
    OWNER to postgres;