#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
#TGL=`date +"%Y%m"`
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM
HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
TGL1=`date +%Y%m -d '1 month ago'`
sqlplus c2bi/telkom123@jepunrac<<!
drop table cust_ama_nd_$TGL
/
CREATE TABLE cust_ama_nd_$TGL
( ND                 VARCHAR2(15 BYTE)
)
/
CREATE INDEX I_ND_temp_$TGL ON cust_ama_nd_$TGL
(ND)
/
commit
/
insert into cust_ama_nd_$TGL select nd from cust_ama_$TGL group by nd
/
drop table cust_ama_nc_$TGL
/
create table cust_ama_nc_$TGL as select distinct nd,ncli,ndos from cust_rinta partition(period_$TGL) 
where nd not in (select nd from cust_ama_nd_$TGL) and an_fact=$THN and per_fact=$BLN
/
commit
/
CREATE INDEX I_nc_temp_$TGL ON cust_ama_nc_$TGL
(ncli,ndos,nd)
/
prompt"----------------------------- create table ----------------------------------"
drop table cust_ama_temp_$TGL
/
CREATE TABLE cust_ama_temp_$TGL
(
  ND_APPELE  VARCHAR2(20 BYTE),
  TGL_JAM    VARCHAR2(19 BYTE),
  DURASI     NUMBER(20),
  BIAYA      NUMBER(26,6),
  ND         VARCHAR2(15 BYTE)                  NOT NULL,
  TUJUAN     VARCHAR2(30 BYTE),
  INDICATIF  VARCHAR2(20 BYTE),
  CAC    VARCHAR2(19 BYTE)
)
/
commit
/

prompt"-----------------------------insert data----------------------------------"
DECLARE
  CURSOR t1 IS
select  a.nd_appele,
        substr(to_char(a.datdeb_com,'MM-DD-YYYY hh24:mi:ss'),1,20) TGL_Jam,
        a.duree durasi,
        a.mnt_com biaya,
        a.nd,
        '' tujuan,
 a.indicatif,
        a.cac
from $HCOM a,cust_ama_nc_$TGL b
WHERE  a.ncli=b.ncli and a.ndos=b.ndos and a.nd=b.nd and CODE_AGREGAT<>'1'
;
w_c NUMBER(9) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
           insert into cust_ama_temp_$TGL
           values (i.nd_appele,i.TGL_Jam,i.durasi,i.biaya,i.nd,i.tujuan,i.indicatif,i.cac);
            IF MOD(w_c,10000)=0 THEN
                      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
CREATE INDEX I_AMA_temp_$TGL ON cust_ama_temp_$TGL
(ND)
/
CREATE INDEX I_APPELE_temp_$TGL ON cust_ama_temp_$TGL
(ND_APPELE)
/
CREATE INDEX I_INDICATIF_temp_$TGL ON cust_ama_temp_$TGL
(INDICATIF,CAC)
/
commit
/
prompt"-----------------------------update tujuan int----------------------------------"
DECLARE
  CURSOR t1 IS
     select  indicatif,cac,lindicatif from p_indicatif;
w_c NUMBER(6) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
           UPDATE cust_ama_temp_$TGL
            SET  tujuan =i.lindicatif
             where indicatif=i.indicatif and cac=i.cac and indicatif is not null;
             IF MOD(W_C,10000)=0 THEN
                      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
prompt"-----------------------------update tujuan nas----------------------------------"
DECLARE
  CURSOR t1 IS
     select  nd_inf,nd_sup,lplannum from p_plannum;
w_c NUMBER(6) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
           UPDATE cust_ama_temp_$TGL
            SET  tujuan =i.lplannum
             where nd_appele between  i.nd_inf and i.nd_sup and length(i.nd_inf)=length(nd_appele) and length(i.nd_sup)=length(nd_appele) 
             and indicatif is null;
             IF MOD(W_C,1000)=0 THEN
                      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
insert into cust_ama_$TGL select * from cust_ama_temp_$TGL
/
commit
/
!