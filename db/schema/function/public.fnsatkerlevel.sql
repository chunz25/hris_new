-- FUNCTION: public.fnsatkerlevel(character varying, character varying)

-- DROP FUNCTION public.fnsatkerlevel(character varying, character varying);

CREATE OR REPLACE FUNCTION public.fnsatkerlevel(
	v_satkerid character varying,
	v_level character varying)
    RETURNS character varying
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

DECLARE
	vd_satker VARCHAR(500);
	
BEGIN   
	IF v_level = '1' THEN
    	IF LENGTH(v_satkerid) > 3 THEN
        	SELECT satker INTO vd_satker FROM Satker WHERE satkerid = SUBSTR(v_satkerid, 1, 4);
        ELSE
        	vd_satker := NULL;
        END IF;
    ELSEIF v_level = '2' THEN
    	IF LENGTH(v_satkerid) > 5 THEN
        	SELECT satker INTO vd_satker FROM Satker WHERE satkerid = SUBSTR(v_satkerid, 1, 6);
        ELSE 
        	vd_satker := NULL;
        END IF;    
    ELSEIF v_level = '3' THEN
    	IF LENGTH(v_satkerid) > 7 THEN
        	SELECT satker INTO vd_satker FROM Satker WHERE satkerid = SUBSTR(v_satkerid, 1, 8);
        ELSE
        	vd_satker := NULL;
        END IF;
    ELSEIF v_level = '4' THEN
    	IF LENGTH(v_satkerid) > 9 THEN
        	SELECT satker INTO vd_satker FROM Satker WHERE satkerid = SUBSTR(v_satkerid, 1, 10);
        ELSE 
        	vd_satker := NULL;
        END IF;
    ELSEIF v_level = '5' THEN
    	IF LENGTH(v_satkerid) > 11 THEN
        	SELECT satker INTO vd_satker FROM Satker WHERE satkerid = SUBSTR(v_satkerid, 1, 12);
        ELSE 
        	vd_satker := NULL;
        END IF;
    END IF;
    
    RETURN vd_satker;
END

$BODY$;

ALTER FUNCTION public.fnsatkerlevel(character varying, character varying)
    OWNER TO postgres;

