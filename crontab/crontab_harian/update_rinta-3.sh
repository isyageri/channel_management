#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=`date +"%Y%m"`
TGL1=`sqlplus -s c2bi/telkom123@pangrango <<_OFF
      set pagesize 0
      set feed off
      set serverout on
      select  to_char(add_months(to_date($TGL,'YYYYMM'),-1),'YYYYMM') from dual;
                 exit
			          _OFF
			           `
TGL2=`sqlplus -s c2bi/telkom123@pangrango <<_OFF
      set pagesize 0
      set feed off
      set serverout on
      select  to_char(add_months(to_date($TGL,'YYYYMM'),-2),'YYYYMM') from dual;
                 exit
			          _OFF
			           `			           

#echo $TGL
#echo $TGL1
#echo $TGL2
sh update_rinta_add.sh $TGL 
sh update_rinta_add.sh $TGL1
sh update_rinta_add.sh $TGL2
