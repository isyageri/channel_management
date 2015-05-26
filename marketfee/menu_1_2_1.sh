#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`

if [[ $BLN = 01  ]]
               then
                TGL1=`expr $THN - 1`12
                 else
                TGL1=`expr $TGL - 1`
        fi
THN1=`echo $TGL1|cut -c1-4`
BLN1=`echo $TGL1|cut -c5-6`        
        
        
DBL=@ODSNAS
#DBL=@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM

cektab=`sqlplus -s c2bi/telkom123@pangrango <<_OFF
      set pagesize 0
      set feed off
      set serverout on
      select  count(distinct per_fact) from h_expedition 
      where an_fact=$THN and per_fact=$BLN;
                 exit
                 _OFF`
                 
echo "$cektab"  
 
 if [ $cektab -eq 1 ];
        then
      echo "ADD TRUNCATE PARTITION period_$TGL"
      sh menu_1_2_2.sh $TGL
         else
      echo "ADD period_$TGL LESS THAN ($THN1, $BLN1)"
        sh menu_1_2_3.sh $TGL
      fi
      
sqlplus  c2bi/telkom123@pangrango<<!

set serverout on
prompt"---------------gat data H_EXPEDITION-----------------"
set serveroutput on

declare
   cursor c1 is
   SELECT     HE.AN_FACT, 
              HE.PER_FACT,
	            HE.NFACT,
              HE.NCLI,
              HE.NDOS,
              HE.ND,
              HE.MNT_ABO,
              HE.MNT_REGABO,
              he.mnt_tck_d,
	            he.MNT_TCK_C,
              he.mnt_taxe_fact,
              case 
                when he.mnt_solde_fact > 1000000 THEN 6000
                when he.mnt_solde_fact >= 250000 THEN 3000
                 else 0 end METERAI,
				      ---he.mnt_solde_fact METERAI,
				      he.mnt_ht_fact,
				      cl.nom,
				      he.cprod,
				      he.mnt_solde_fact
 FROM  h_expedition$DBL HE,client$DBL cl,dossier$DBL dos,p_autocom$DBL pa
               WHERE he.groupe_fact = 'A'
               AND he.an_fact=$THN
               AND he.per_fact=$BLN
               AND he.mnt_solde_fact >= 0
               AND he.ncli = cl.ncli(+)
               AND he.ncli=dos.ncli(+)
               AND he.ndos=dos.ndos(+)
	             and dos.cautocom=pa.cautocom(+)
               and pa.centite in (36,37,38,39,40,41,42,43,44,45,46,47,48,49); 
  
  type t__tab is table of c1%rowtype index by binary_integer;
  t_tab t__tab;
            
  w_c number(26,6) := 0;
 begin
   ---for i in c1
   
   open c1;
     loop
       w_c := w_c+1;
								         FETCH c1
                         BULK COLLECT INTO t_tab 
                         LIMIT 1000;
                         FORALL i IN t_tab.first..t_tab.last
                          insert into H_EXPEDITION partition(period_$TGL)  values t_tab(i);
  
                         COMMIT;
       EXIT WHEN c1%NOTFOUND;
    end loop;
    close cs1;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
   commit;
 end;
/

select count(*) Jumlah_row from h_expedition where AN_FACT=$THN AND PER_FACT=$BLN
/
exit
!                         