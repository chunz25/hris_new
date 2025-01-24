-- Table: eservices.historysaldocuti

-- DROP TABLE eservices.historysaldocuti;

CREATE TABLE eservices.historysaldocuti
(
    pegawaiid character varying(30) COLLATE pg_catalog."default" NOT NULL,
    tahun integer NOT NULL,
    saldo integer,
    locked character varying(1) COLLATE pg_catalog."default",
    jatahawal integer,
    cutibersama integer,
    tglexpired date,
    CONSTRAINT historysaldocuti_pkey PRIMARY KEY (pegawaiid, tahun)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.historysaldocuti
    OWNER to postgres;