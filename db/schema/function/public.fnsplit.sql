-- FUNCTION: public.fnsplit(character varying, character varying)

-- DROP FUNCTION public.fnsplit(character varying, character varying);

CREATE OR REPLACE FUNCTION public.fnsplit(
	v_string character varying,
	v_delim character varying)
    RETURNS TABLE(split character varying) 
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 1000
AS $BODY$

DECLARE
	vd_string varchar(200) := v_string;
    vd_pos int := 0;
    vd_str varchar(200);
	
BEGIN   
	vd_pos := strpos(vd_string, v_delim);
	
    if vd_pos = 0 then
    	split := vd_string;
        return next;
    else
		begin
            while (vd_pos != 0) loop 
                vd_str := substr(vd_string,1,(vd_pos-1));
                split := vd_str;        	
                return next;

                vd_string := substr(vd_string,vd_pos+1,length(vd_string));
                vd_pos := strpos(vd_string, v_delim);

                if vd_pos = 0 then
                    split := vd_string;
                    return next;
                end if;

            end loop;
        end;        
    end if;
END

$BODY$;

ALTER FUNCTION public.fnsplit(character varying, character varying)
    OWNER TO postgres;

