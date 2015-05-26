#!/bin/bash
#/******************************************************************/
#/* Program Name        :  Loading data Revenue TIBS               */
#/* Dibuat tgl          :  28 Februari 2012                        */
#/*                                                                */
#/* Modified         by :  Purwanto                                */
#/* Last Tested     Tgl :  30 Mei 2012                             */
#/*                  by :  Purwanto                                */
#/******************************************************************/
   while :
   do
   clear

   tput cup 23 1
   echo "                    Copyright (c) 2012 ISCDM,ISCS,ISEWS"
   echo "                             Information System CENTER"
   tput cup 1 1
   echo "                                                                    "
   echo "          +---------------------------------------------------------+"
   echo "          |######### <|  Loading data Pre Proses           |> ######|"
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
echo   "$thnbln"
   ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
	 ORACLE_SID=pangrango;
	 PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
	 export ORACLE_HOME ORACLE_SID PATH
blink=`tput blink`
norm=`tput rmso`
	          




echo "PROSES" 		          
sh menu_4_2.sh $thnbln       

TGL2=`date`
echo -n "                Selesai Proses $TGL2"
#clear
   echo " "
   echo -n "Tekan Enter untuk kembali ke menu sebelumnya ... "
   read tekan
done