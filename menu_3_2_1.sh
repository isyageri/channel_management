#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
#TGL=`date +"%Y%m"`
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM
HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
TGL1=`date +%Y%m -d '1 month ago'`
sqlplus c2bi/telkom123@pangrango<<!

prompt"-----------------------------create table----------------------------------"
drop table cust_ama_$TGL
/
CREATE TABLE cust_ama_$TGL
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
from $HCOM a,cust_rinta partition(period_$TGL) b
WHERE  a.ncli=b.ncli 
   and a.ndos=b.ndos 
   and CODE_AGREGAT<>'1';
   TYPE fetch_array IS TABLE OF t1%ROWTYPE index by binary_integer;
   s_array fetch_array;
w_c NUMBER(9) := 0;
BEGIN
 OPEN t1;
 LOOP
   FETCH t1 BULK COLLECT INTO s_array LIMIT 10000;
   FORALL i IN 1..s_array.COUNT
    INSERT INTO cust_ama_$TGL VALUES s_array(i);
    COMMIT;
    EXIT WHEN t1%NOTFOUND;
  END LOOP;
  CLOSE t1;
  COMMIT;
END;
/
CREATE INDEX I_AMA_$TGL ON CUST_AMA_$TGL
(ND)
/
CREATE INDEX I_APPELE_$TGL ON CUST_AMA_$TGL
(ND_APPELE)
/
CREATE INDEX I_INDICATIF_$TGL ON CUST_AMA_$TGL
(INDICATIF,CAC)
/
commit
/

DROP TABLE P_PLANNUM
/
CREATE TABLE P_PLANNUM as select * from P_PLANNUM@ODSNAS
/
CREATE INDEX I_PLAN ON P_PLANNUM
(ND_INF, ND_SUP, LG_ND)
/
DROP TABLE p_indicatif
/
CREATE TABLE p_indicatif as select * from p_indicatif@ODSNAS
/
CREATE INDEX I_IND ON P_INDICATIF
(INDICATIF, CAC)
/

prompt"-----------------------------update tujuan int----------------------------------"
DECLARE
  CURSOR t1 IS
     select  indicatif,cac,lindicatif from p_indicatif;
     TYPE fetch_array IS TABLE OF t1%ROWTYPE index by binary_integer; 
     s_array fetch_array;
     
     w_c NUMBER(6) := 0;
BEGIN
  FOR i IN t1
        LOOP
           w_c := w_c+1;
           UPDATE cust_ama_$TGL
            SET  tujuan =i.lindicatif
             where indicatif=i.indicatif and cac=i.cac and indicatif is not null;
             IF MOD(W_C,1000)=0 THEN
                      COMMIT;
           END IF;
           EXIT WHEN t1%NOTFOUND;
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
           UPDATE cust_ama_$TGL
            SET  tujuan =i.lplannum
             where nd_appele between  i.nd_inf and i.nd_sup and length(i.nd_inf)=length(nd_appele) and length(i.nd_sup)=length(nd_appele) 
             and indicatif is null;
             IF MOD(W_C,1000)=0 THEN
                      COMMIT;
           END IF;
           EXIT WHEN t1%NOTFOUND;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/

!