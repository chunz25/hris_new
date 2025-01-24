-- Table: eservices.statusverifikasi

-- DROP TABLE eservices.statusverifikasi;

CREATE TABLE eservices.statusverifikasi
(
    statusid character varying(10) COLLATE pg_catalog."default" NOT NULL,
    status character varying(200) COLLATE pg_catalog."default",
    CONSTRAINT statusverifikasi_pkey PRIMARY KEY (statusid)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.statusverifikasi
    OWNER to postgres;