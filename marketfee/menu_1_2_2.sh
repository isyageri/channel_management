#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
       
DBL=@ODSNAS


sqlplus  c2bi/telkom123@pangrango<<!

  set serveroutput on
	ALTER TABLE H_EXPEDITION TRUNCATE PARTITION PERIOD_$TGL
	/
	
	exit
	!
	
