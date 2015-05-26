#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=`date +"%Y%m"`    #'1 month ago'
#TGL=$1
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
  JUMLAH_BAYAR NUMBER(15)
)
/
/*
create table TREMS_PAYMENT_$TGL as 
select  a.nfact,
             a.nper,
             a.telp,
             a.payment_date,
             a.PAYMENT_AMOUNT
   from ods_trems.trems_payment@DWHNAS_JKT a,
                                cust_rinta partition(period_$TGL) b
    where
    a.telp=b.nd  
    and nper='$TGL' 
    ---and nfact=no_tagihan 
    and substr(cpudt,1,6)='$TGL'
    and an_fact=$THN 
    and per_fact=$BLN
    and b.status_pembayaran is null
/ 
*/   	
commit
/
CREATE INDEX I_telp_$TGL ON 
  trems_payment_$TGL (telp)
/

declare
 CURSOR rec_cur IS
   select  a.nfact,
             a.nper,
             a.telp,
             a.payment_date,
             a.PAYMENT_AMOUNT
   from ods_trems.trems_payment@DWHNAS_JKT a,
                                cust_rinta partition(period_$TGL) b
    where
    a.telp=b.nd  
    and nper='$TGL' 
    --and nfact=no_tagihan 
    and substr(cpudt,1,6)='$TGL'
    and an_fact=$THN 
    and per_fact=$BLN
    and b.status_pembayaran is null;
     type t__tab is table of rec_cur%rowtype index by binary_integer;
       t_tab t__tab;
        w_c number := 0;
BEGIN
 open rec_cur; 
       LOOP
      w_c := w_c+1;
        FETCH rec_cur BULK COLLECT INTO t_tab LIMIT 5;
          FORALL i IN t_tab.FIRST .. t_tab.LAST
            insert into  TREMS_PAYMENT_$TGL values t_tab(i);
          commit;   
          EXIT WHEN rec_cur%NOTFOUND;       
          END LOOP;
END;
/



declare
cursor c1 is select  a.nper,
	                   a.telp,
	                   a.payment_date,
	                   sum(a.jumlah_bayar) jumlah_bayar
 from trems_payment_$TGL a,cust_rinta b
    where  a.telp=b.nd ---and nper='$TGL'  
    and an_fact=$THN and per_fact=$BLN 
    group by a.nper,a.telp,a.payment_date;
w_c number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
update cust_rinta  set tgl_byr=i.payment_date,
                           status_pembayaran='sudah bayar',
                           jumlah_bayar=i.jumlah_bayar
                           where nd=i.telp 
                           and an_fact=$THN 
                           and per_fact=$BLN;
   IF MOD(W_C,1000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('upate_byr = '||w_c||' ssl'||' '||'');
  commit;
end;
/
!
exit
