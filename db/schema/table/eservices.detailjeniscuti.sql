-- Table: eservices.detailjeniscuti

-- DROP TABLE eservices.detailjeniscuti;

CREATE TABLE eservices.detailjeniscuti
(
    detailjeniscutiid integer NOT NULL,
    jeniscutiid character varying(10) COLLATE pg_catalog."default",
    detailjeniscuti character varying(150) COLLATE pg_catalog."default",
    jatahcuti integer,
    satuan character varying(150) COLLATE pg_catalog."default",
    CONSTRAINT detailjeniscuti_pkey PRIMARY KEY (detailjeniscutiid)
)
WITH (
    OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE eservices.detailjeniscuti
    OWNER to postgres;