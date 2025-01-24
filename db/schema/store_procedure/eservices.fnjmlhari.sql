-- FUNCTION: eservices.fnjmlhari(date, date, integer)

-- DROP FUNCTION eservices.fnjmlhari(date, date, integer);

CREATE OR REPLACE FUNCTION eservices.fnjmlhari(
	v_start date,
	v_end date,
	v_jenis integer)
    RETURNS integer
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

DECLARE
	vd_jml INT;
BEGIN   
	
    IF v_jenis = 1 THEN    
        WITH min_max(start_date, end_date) AS (
           VALUES (v_start, v_end)
        ), date_range AS (
          SELECT end_date - start_date AS duration
          FROM min_max
        ), generateDate AS (
            SELECT (start_date + i) tgl
            FROM min_max c
            CROSS JOIN generate_series(0, (SELECT duration FROM date_range)) i
        )
        SELECT COUNT(*) INTO vd_jml
        FROM generateDate c
        LEFT JOIN eservices.harilibur h ON c.tgl = h.tgl
        WHERE h.tgl IS NULL AND date_part('dow', c.tgl) NOT IN (6,0);
        
    ELSEIF v_jenis = 2 THEN
        WITH min_max(start_date, end_date) AS (
           VALUES (v_start, v_end)
        ), date_range AS (
          SELECT end_date - start_date AS duration
          FROM min_max
        ), generateDate AS (
            SELECT (start_date + i) tgl
            FROM min_max c
            CROSS JOIN generate_series(0, (SELECT duration FROM date_range)) i
        )
        SELECT COUNT(*) INTO vd_jml
        FROM generateDate;        
    
    END IF;
    
    RETURN vd_jml;
                
END

$BODY$;

ALTER FUNCTION eservices.fnjmlhari(date, date, integer)
    OWNER TO postgres;

