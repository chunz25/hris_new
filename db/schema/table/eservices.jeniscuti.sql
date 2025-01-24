-- Table: eservices.jeniscuti

-- DROP TABLE eservices.jeniscuti;

CREATE TABLE eservices.jeniscuti
(
    jeniscutiid character varying(10) COLLATE pg_catalog."default" NOT NULL,
    jeniscuti character varying(150) COLLATE pg_catalog."default",
    jatahcuti integer,
    satuan character varying(150) COLLATE pg_catalog."default",
    CONSTRAINT jeniscuti_pkey PRIMARY KEY (jeniscutiid)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.jeniscuti
    OWNER to postgres;