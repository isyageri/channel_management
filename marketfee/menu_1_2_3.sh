#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`

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


sqlplus  c2bi/telkom123@pangrango<<!
  
  set serveroutput on
  ALTER TABLE h_expedition DROP PARTITION period_$TGL;
  /
	alter table h_expedition add partition period_$TGL VALUES LESS THAN ($THN1, $BLN1)
	/
	
	exit
	!
	
