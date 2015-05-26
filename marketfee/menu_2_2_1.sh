#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
DBL=@ODSNAS
#DBL=@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM

sqlplus  c2bi/telkom123@pangrango<<!
set serverout on

prompt"---------------gat data h_f_conso_agregat-----------------"

set serveroutput on
truncate table h_f_conso_agregat
/
DECLARE
  CURSOR c1 IS
 select a.an_fact,
                  a.per_fact,
                  a.ncli,
                  a.ndos,
                  a.code_agregat,
                  a.montant 
   from h_f_conso_agregat$DBL a,dossier$DBL b,p_autocom$DBL c
             where   a.an_fact= $THN
  		           and a.per_fact= $BLN
  		           and a.groupe_fact = 'A'
 		             and a.ncli=b.ncli(+)
 		             and a.ndos=b.ndos(+)
 		             and b.cautocom=c.cautocom(+)
 		             and c.centite in (36,37,38,39,40,41,42,43,44,45,46,47,48,49);
 type t__tab is table of c1%rowtype index by binary_integer;
  t_tab t__tab; 		             
w_c NUMBER(9) := 0;
BEGIN
open c1;
     loop
        w_c := w_c+1;
            FETCH c1
              BULK COLLECT INTO t_tab 
              LIMIT 1000;
              FORALL i IN t_tab.first..t_tab.last
           insert into h_f_conso_agregat values t_tab(i);
                   COMMIT;
       EXIT WHEN c1%NOTFOUND;
		          END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
       END;
/

select count(*) Jumlah_row from h_f_conso_agregat where AN_FACT=$THN AND PER_FACT=$BLN
/
exit
!