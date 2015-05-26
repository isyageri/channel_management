#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
#TGL=`date +"%Y%m"`
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
BLN2=`echo $TGL|cut -c3-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
sqlplus  c2bi/telkom123@pangrango<<!
prompt"----------------------insert FLEXI----------------------"
DECLARE
                CURSOR t1  IS
		select * from odsflexi.REV_DETAIL_109_FLEXI_$BLN2@DWHNAS_JKT 
		where notel in (select distinct nd notel from ten_nd where cprod='9') 
		and REVENUE_TYPE_ID=to_char('080101');
		 w_c NUMBER(9) := 0;
BEGIN
delete from CUST_RINTA_FLX where periode='$TGL';
FOR i IN t1
        LOOP
           w_c := w_c+1;

	   insert into CUST_RINTA_FLX
                        (
			PERIODE,
			  DATEL_ID,
			  LINECATS_ITEM_ID,
			  PRODUCT_LINE_ID,
			  REVENUE_TYPE_ID,
			  NCLI,
			  NDOS,
			  NOTEL,
			  REVENUE,
			  ACCOUNT_NUM,
			  CPROD
                         )
	                VALUES
			( i.PERIODE,
			  i.DATEL_ID,
			  i.LINECATS_ITEM_ID,
			  i.PRODUCT_LINE_ID,
			  i.REVENUE_TYPE_ID,
			  i.NCLI,
			  i.NDOS,
			  i.NOTEL,
			  i.REVENUE,
			  i.ACCOUNT_NUM,
			   9
			   );
	   IF MOD(W_C,1000)=0 THEN
		      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
prompt"--------------update bayar  FLEXI-----------------"

declare
cursor c1 is  select  nfact,nper,telp,payment_date
     from ods_trems.trems_payment@DWHNAS_JKT a,ten_nd b
     where  nper='$TGL' and a.telp=b.nd and b.cprod=9
     group by nfact,nper,telp,payment_date ;
w_c number(15) := 0;
w_d number(15) := 0;
w_e number(15) := 0;
begin
   for i in c1
loop
w_e := w_e+1;
  update cust_rinta_flx set tgl_byr=i.payment_date,
                        status_pembayaran='sudah bayar'
                        where notel=i.telp  and REVENUE_TYPE_ID='080101' and periode=i.nper;
   IF MOD(W_E,1000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('Ter-Update = '||w_e||' ssl'||' '||'');
  commit;
end;
/
prompt"--------------------------insert into marketing fee------------------"
DECLARE
        cnt NUMBER(5);
        I_TEN_ID NUMBER(10);
        v_err VARCHAR2(20);
        V_STATUS_BAYAR VARCHAR2(1);
CURSOR cursor_1 IS
select PERIODE PERIOD,
        NOTEL ND,
       '0' NCLI,
       '0' NDOS,
        REVENUE CF_NOM,
	STATUS_PEMBAYARAN
       from CUST_RINTA_FLX a,
            ten_nd b,
            pgl_ten c,
            cust_pgl d 
       where a.notel=b.nd
       and b.TEN_ID=c.TEN_ID
       and c.PGL_ID=d.PGL_ID
       and d.ENABLE_FEE=1
       and b.CPROD=9
       and a.REVENUE_TYPE_ID=to_char('080101')
       and periode='$TGL';
              fs cursor_1%ROWTYPE;

BEGIN
        
	       delete from ten_usage where period=to_char($TGL) and nd in (select notel nd from CUST_RINTA_FLX);
	commit;
        delete from ten_payment where period=to_char($TGL) and nd in (select notel nd from CUST_RINTA_FLX);
	commit;
        COMMIT;
        OPEN cursor_1;
        cnt:=0;
        LOOP FETCH cursor_1 INTO fs;
                EXIT WHEN cursor_1%NOTFOUND;
                cnt:=cnt+1;
                BEGIN
                                            
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 1597, fs.CF_NOM);
                        
                        --- sudah bayar
                        IF fs.STATUS_PEMBAYARAN='sudah bayar' 
                        THEN
                        V_STATUS_BAYAR := '1';
                        ELSE
                        V_STATUS_BAYAR := '0';
                        END IF;
                        INSERT INTO TEN_PAYMENT(PERIOD, NCLI, NDOS, ND, IS_PAID) VALUES($TGL, fs.NCLI, fs.NDOS, fs.ND, V_STATUS_BAYAR);
                END;
                IF MOD(cnt,100)=0
                THEN COMMIT;
                END IF;
        END LOOP;
        COMMIT;

	
        
        DELETE FROM TEN_USAGE WHERE PERIOD=$TGL AND CF_NOM IS NULL;
        
        COMMIT;

END;
/
select to_char(sysdate,'DD-MONTH-YYYY HH24:mi:ss') SELESAI from dual
/
!
exit
