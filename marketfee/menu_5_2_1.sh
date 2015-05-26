#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
#TGL=`date +"%Y%m"`    #'1 month ago'
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
sqlplus c2bi/telkom123@jepunrac<<!
set serverout on
drop table TREMS_PAYMENT_$TGL
/

CREATE TABLE TREMS_PAYMENT_$TGL
(
  NFACT           VARCHAR2(35 BYTE),
  NPER          VARCHAR2(6 BYTE),
  TELP          VARCHAR2(15 BYTE),
  PAYMENT_DATE  VARCHAR2(14 BYTE),
  ABONEMEN NUMBER(15)
)
/
commit
/
CREATE INDEX I_telp_$TGL ON 
  trems_payment_$TGL (telp)
/

declare
cursor c1 is 
    select  a.nfact,
             a.nper,
             a.telp,
             a.payment_date,
             sum(a.PAYMENT_AMOUNT) abonemen
   from ods_trems.trems_payment@DWHNAS_JKT a,
                                cust_rinta b,
                                p_gl_acc c
    where  nper='$TGL'  
    and nfact=no_tagihan 
    and a.gl_acc=c.gl_acc 
    and an_fact=$THN 
    and per_fact=$BLN
    group by a.nfact,a.nper,a.telp,a.payment_date;
w_c number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
insert into  TREMS_PAYMENT_$TGL values (i.nfact,i.nper,i.telp,i.payment_date,i.abonemen);
   IF MOD(W_C,1000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('upate_byr = '||w_c||' ssl'||' '||'');
  commit;
end;
/

declare
cursor c1 is select  a.nper,a.telp,a.payment_date,a.abonemen
   from trems_payment_$TGL a,cust_rinta b
    where  a.telp=b.nd and nper='$TGL'  
    and an_fact=$THN and per_fact=$BLN 
    group by a.nper,a.telp,a.payment_date,a.abonemen;
w_c number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
update cust_rinta  set tgl_byr=i.payment_date,
                           status_pembayaran='sudah bayar',
                           abonemen=i.abonemen
                           where nd=i.telp 
                           and an_fact=$THN 
                           and per_fact=$BLN;
   IF MOD(W_C,10000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('upate_byr = '||w_c||' ssl'||' '||'');
  commit;
end;
/
!
exit
