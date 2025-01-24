-- FUNCTION: public.fnsatkerlevel2(character varying)

-- DROP FUNCTION public.fnsatkerlevel2(character varying);

CREATE OR REPLACE FUNCTION public.fnsatkerlevel2(
	v_satkerid character varying)
    RETURNS character varying
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
    ROWS 0
AS $BODY$

DECLARE
	vd_tempsatkerid VARCHAR(30);
    vd_tempsatker VARCHAR(30);
    vd_satker VARCHAR(100);	
    vd_kepalaid VARCHAR(30);
	
BEGIN   		
    if length(v_satkerid) <= 2 then
    	select kepalaid into vd_kepalaid from satker where satkerid = '0101';
    	return vd_kepalaid;
    end if;
    
    
    vd_tempsatkerid := v_satkerid ;
	<< loop_l >>
    while length(v_satkerid) >= 2
    loop
    	begin
            select kepalaid into vd_kepalaid from satker where satkerid = vd_tempsatkerid;
            
            if vd_kepalaid is not null then
            	exit;
            else
            	begin
             		vd_tempsatkerid := substring(vd_tempsatkerid,1,(length(vd_tempsatkerid)-2));   
                    continue;
                end;            
            end if;
                        
        end;
    end loop;

    RETURN vd_kepalaid;
END

$BODY$;

ALTER FUNCTION public.fnsatkerlevel2(character varying)
    OWNER TO postgres;

