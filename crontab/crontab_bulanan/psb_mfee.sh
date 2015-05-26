#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
#TGL=`date +"%Y%m"`
TGL=$1
#THN=`echo $TGL|cut -c1-4`
#BLN=`echo $TGL|cut -c5-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
#TGL1=`date +%Y%m -d '1 month ago'`
sqlplus c2bi/telkom123@pangrango<<!
insert into TEN_USAGE_ETC
select  '$TGL' PERIODE,
         2953 ten_id,
         'PSB' ND,
         1572 CF_ID,
         count(*) cf_nom
from h_demande@ODSNAS.TELKOM.CO.ID a,
     ND_DET b
where  a.ncli=b.ncli
and    a.ndos_rsv=b.ndos 
       and coper='1'
       and etat_de='5'
       and substr(datop_de,4,6)=to_char(add_months(to_date($TGL,'YYYYMM'),-1),'MON-YY');
commit;
!

