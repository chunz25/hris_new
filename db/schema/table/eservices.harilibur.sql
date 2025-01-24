-- Table: eservices.harilibur

-- DROP TABLE eservices.harilibur;

CREATE TABLE eservices.harilibur
(
    hariliburid integer NOT NULL,
    tgl date,
    keterangan character varying(200) COLLATE pg_catalog."default",
    status character varying(1) COLLATE pg_catalog."default",
    jenis character varying(1) COLLATE pg_catalog."default",
    CONSTRAINT harilibur_pkey PRIMARY KEY (hariliburid)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.harilibur
    OWNER to postgres;