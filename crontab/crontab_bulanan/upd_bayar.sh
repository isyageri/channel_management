#!/bin/ksh
   ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
   ORACLE_SID=jepun;
   PATH=$PATH:$ORACLE_HOME/bin:/home/marketfee
   export ORACLE_HOME ORACLE_SID PATH
TGL=`date +"%Y%m"`
TGL1=`date +%Y%m%d`
TGL2=`date +%Y%m%d -d '3 day ago'`
sqlplus c2bi/telkom123@jepunrac<<!
set serveroutput on
select to_char(sysdate,'DD-MONTH-YYYY HH24:mi:ss') MULAI from dual
/
prompt"--------------update bayar POTS dan FLEXI-----------------"

declare
cursor c1 is  select  nfact,nper,telp,payment_date
     from ods_trems.trems_payment@DWHNAS_JKT a,ten_nd b
     where  a.payment_date between  '$TGL2'  and '$TGL1' and a.telp=b.nd 
     group by nfact,nper,telp,payment_date ;
w_c number(15) := 0;
w_d number(15) := 0;
w_e number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
update cust_rinta  set tgl_byr=i.payment_date,
                        status_pembayaran='sudah bayar'
                        where nd=i.telp and no_tagihan=i.nfact
			and an_fact=substr(i.nper,1,4) and per_fact=substr(i.nper,5,2);
   IF MOD(W_C,1000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
  commit;
   for i in c1
loop
w_d := w_d+1;
update ten_payment set is_paid=1
          where nd=i.telp and period=substr(i.nper,1,6);
   IF MOD(W_D,1000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('Ter-Update = '||w_d||' ssl'||' '||'');
  commit;
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
select to_char(sysdate,'DD-MONTH-YYYY HH24:mi:ss') SELESAI from dual
/
!
exit