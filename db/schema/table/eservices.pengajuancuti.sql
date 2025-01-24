-- Table: eservices.pengajuancuti

-- DROP TABLE eservices.pengajuancuti;

CREATE TABLE eservices.pengajuancuti
(
    pengajuanid bigint NOT NULL,
    pegawaiid character varying(25) COLLATE pg_catalog."default" NOT NULL,
    nourut integer NOT NULL,
    periode character varying(12) COLLATE pg_catalog."default",
    tglpermohonan date,
    tglupdate timestamp without time zone,
    atasan1 character varying(25) COLLATE pg_catalog."default",
    atasan2 character varying(25) COLLATE pg_catalog."default",
    pelimpahan character varying(25) COLLATE pg_catalog."default",
    status character varying(2) COLLATE pg_catalog."default",
    verifikasinotes character varying(250) COLLATE pg_catalog."default",
    files character varying(150) COLLATE pg_catalog."default",
    filestype character varying(50) COLLATE pg_catalog."default",
    hp character varying(30) COLLATE pg_catalog."default",
    hrd character varying(30) COLLATE pg_catalog."default",
    CONSTRAINT pengajuancuti_pkey PRIMARY KEY (pengajuanid)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.pengajuancuti
    OWNER to postgres;