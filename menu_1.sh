#!/bin/bash
#/******************************************************************/
#/* Program Name        :  Loading data Revenue                    */
#/* Dibuat tgl          :  13 Februari 2014                        */
#/*                                                                */
#/* Modified         by :  Purwanto                                */
#/* Last Tested     Tgl :  13 Februari 2014                        */
#/*                  by :  Purwanto                                */
#/******************************************************************/
   while :
   do
   clear

   tput cup 23 1
   echo "                    Copyright (c) 2014 ISCDM"
   echo "                   Information System CENTER"
   tput cup 1 1
   echo "                                                                    "
   echo "          +---------------------------------------------------------+"
   echo "          |######### <|  Loading  Proses Bulanan           |> ######|"
   echo "          +---------------------------------------------------------+"
   tput cup 19 0
   echo "{ exit?, enter YYYYMM = q } "

   tput cup 10 0
   echo    "input Bultag YYYYMM:"


   tput cup 10 21
   echo -n "  "
   read thnbln;
   case $thnbln in
   q) exit;;
   x);;
   esac
   #clear
TGL=`date`
echo   "                 Mulai Proses $TGL"
#echo   "$thnbln"
   ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
	 ORACLE_SID=pangrango;
	 PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
	 export ORACLE_HOME ORACLE_SID PATH
blink=`tput blink`
norm=`tput rmso`
        
CEK=` sqlplus -s  c2bi/telkom123@jepunrac<<_OFF
	        set pagesize 0
          set feed off
          set serverout on
	       select substr(max(table_name),20,6)  from all_tables@dwhnas_bdg where table_name like 'TREMS_REKAP_REGALL_$thnbln%'
	       ;
	        exit
		_OFF
		`
		
if [[ $CEK == $thnbln ]]
               then
echo "PROSES" 
		          
sh menu_1_2.sh $thnbln   
else
	echo  $CEK "data dari DWHNAS belum tersedia"
	fi    

TGL2=`date`
echo -n "                Selesai Proses $TGL2"
#clear
   echo " "
   echo -n "Tekan Enter untuk kembali ke menu sebelumnya ... "
   read tekan
done