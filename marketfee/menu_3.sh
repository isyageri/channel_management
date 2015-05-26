#!/bin/bash
#/******************************************************************/
#/* Program Name        :  Loading data Revenue ISISKA             */
#/* Dibuat tgl          :  10 September 2012                       */
#/*                                                                */
#/* Modified         by :                                          */
#/* Last Tested     Tgl :                                          */
#/*                  by :                                          */
#/******************************************************************/
   while :
   do
   clear

   tput cup 23 1
   echo "                    Copyright (c) 2012 ISCDM,ISCS,ISEWS"
   echo "                             Information System CENTER"
   tput cup 1 1
   echo "                                                                     "
   echo "          +---------------------------------------------------------+"
   echo "          |#########<| Proses updata detail tagihan        |> ######|"
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
THN=`echo $thnbln|cut -c1-4`
BLN=`echo $thnbln|cut -c5-6`
DBL=@ODSNAS
echo   "                 Mulai Proses $TGL"
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
blink=`tput blink`
norm=`tput rmso`
cektab=`sqlplus -s c2bi/telkom123@pangrango <<_OFF
      set pagesize 0
      set feed off
      set serverout on
      select  count(distinct per_fact) from h_f_conso_agregat@ODSNAS 
      where an_fact=$THN and per_fact=$BLN;
                 exit
                 _OFF`                
                                   
if [ $cektab = 1 ];
        then
sh menu_3_2.sh $thnbln        
#echo "tes_ok $thnbln"
  else
   echo  "+-----------------------------------------------------------------------------------+"
   echo -e '\E[37;44m'"\033[1m|######### <|  Data h_f_conso_agregat $thnbln@ODSNAS belum tersedia  |> ######|\033[0m"
   echo  "+-----------------------------------------------------------------------------------+"
fi
TGL2=`date`
echo -n "                Selesai Proses $TGL2"
#clear
   echo " "
   echo -n "Tekan Enter untuk kembali ke menu sebelumnya ... "
   read tekan
done